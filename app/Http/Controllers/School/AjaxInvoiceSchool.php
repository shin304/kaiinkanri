<?php

namespace App\Http\Controllers\School;

use App\Lang;
use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\InvoiceAdjustNameTable;
use App\Model\InvoiceHeaderTable;
use App\Model\PschoolTable;
use App\Model\PschoolBankAccountTable;
use App\Model\InvoiceItemTable;
use App\Model\ClassTable;
use App\Model\RoutinePaymentTable;
use App\Model\ClosingDayTable;
use Illuminate\Http\Request;
use App\Model\ParentTable;
use App\ConstantsModel;
use App\Model\MailMessageTable;
use PDF;
use Mail;
use App\Mail\MailInvoice;
use File;
define ( 'PDFTK_CMD', "/usr/bin/pdftk" );
define("MAIL_FROM", "icteltest01@asto-system.net" );
class AjaxInvoiceSchool extends _BaseSchoolController {
    private $created_pdf_list = array ();
    private $tempDir;
    private $_result;
    const TEMP_PDF_NAME = 'output.pdf';
    const SESS_PDF_KEY = 'session_ajax_pdf';
    const SESS_PDF_FILE_KEY = 'session_ajax_pdf_file';
    private static $MAIL_TEMPLATE = 'invoice_mail_notification.tpl';
    private static $MAIL_URL = '/portal/invoice/detail';
    public function execute() {
        return false;
    }
    /**
     * Ajaxで送られてきたリクエストをエンコード
     *
     * @param string $dat            
     */
    private function ajaxRequestConvert($dat) {
        return $dat;
    }
    
    /**
     * JSONコードを出力する
     *
     * @param unknown_type $dat            
     */
    protected function printJSON($dat) {
        // UTF-8で返却します
        print json_encode ( $dat );
    }
    
    /**
     * Ajaxリクエストを取得する
     *
     * @param string $key            
     * @return string
     */
    protected function getRequest(Request $request, $key) {
        if ($request->exists ( $key )) {
            return $this->ajaxRequestConvert ( $request [$key] );
        }
        return null;
    }
    
    /**
     * JSONPコードを出力する
     *
     * @param unknown_type $dat            
     * @param string $callback            
     */
    protected function printJSONP($dat, $callback) {
        // UTF-8で返却します
        print $callback . '(' . json_encode ( $dat ) . ')';
    }
    
    /**
     * 割引・割増の初期値取得
     *
     * @return boolean
     */
    public function executeGetInitFee(Request $request) {
        $callback = $this->getRequest ( $request, 'callback' );
        
        if (! isset ( $request ['adjust'] )) {
            $ret = "";
            $this->printJSONP ( $ret, $callback );
//             return false;
        }
        
        // 取得
        $row = InvoiceAdjustNameTable::getInstance ()->getRow ( $where = array (
                'id' => $request ['adjust'],
                // 'pschool_id'=>$_SESSION['school.login']['id'],
                'delete_date IS NULL' 
        ) );
        // dd($row);
        if (count ( $row ) > 0) {
            if ($row ['type'] == 0) {
                $ret = $row ['initial_fee'];
            } else {
                $ret = $row ['initial_fee'] * - 1;
            }
            if (session ( 'school.login' ) ['country_code'] == 81) {
                $ret = intval ( $ret );
            }
        } else {
            $ret = "";
        }
        // dd($cal);
        $this->printJSONP ($ret, $callback);
//         return false;
    }
    
    //---------------------------------------------------------------------
    // 現在の日付で作成できるのは、何月分？ $target_month
    //---------------------------------------------------------------------
    private function getInvoiceYearMonth(){
        $ret = array();
        // 塾の情報取得
        $pschool_data = PschoolTable::getInstance()->load(session('school.login')['id']);
        // 塾締日
        //		$due_day = $pschool_data['due_date'];
        $due_day = $pschool_data['invoice_closing_date'];
        // 先払い／後払い
        $pay_style = $pschool_data['payment_style'];
        // 口座引落日
        $withdrawal_day = $pschool_data['withdrawal_day'];
    
        // 現在日付取得
        $curr_date = date('Y-m-d');
        $curr_day  = date('d');
    
        // 現在からどの月分が作成できるか？
        if( intval($withdrawal_day) < 1 ){
            // 口座引落ししない  塾の締日が判定の条件
    
            // 99が末日 大小比較で使用するのみなので、日付に変換しない
    
            if( intval($due_day) >= intval(date('d')) ){
                // 塾締日より前の場合
    
                // 今月請求
                $target_month = date('Y-m');
            } else {
                // 来月請求分
                $target_month = date('Y-m', strtotime("+1 month"));
            }
    
            if( $pay_style == 1 ){
                // 先払いの場合、翌月
                $target_month = date('Y-m', strtotime("+1 month"));
            }
        } else {
            // 口座引落しする  リコーリース殿への依頼書提出期限が判定の条件
            $target_month = date('Y-m');
            if( $pay_style == 1 ){
                // 先払いの場合、翌月
                $target_month = date('Y-m', strtotime("+1 month"));
            }
    
            // 提出期限取得
            $closingRow = ClosingDayTable::getInstance()->getRow($where=array('transfer_day'=>$withdrawal_day,
                    'transfer_month'=>"".$target_month,
                    'delete_date IS NULL'));
            if( $closingRow['deadline'] >= date('Y-m-d') ){
                // 提出期限を越えていない
                $target_month = substr($closingRow['deadline'], 0, 7);
            } else {
                // 提出期限を越えているので、次月分へ
                $target_month = date('Y-m', strtotime($closingRow['deadline'] . " +1 month"));
            }
        }
    
        $ret['year']  = substr($target_month, 0, 4);
        $ret['month'] = substr($target_month, 5, 2);
        $ret['year_month'] = $target_month;
        return $ret;
    }
    
    /**
     * 現在の日付から作成できる請求書の年月を取得する
     * @return boolean
     */
    public function executeGetInvoiceYearMonth(Request $request){
    
        $callback = $this->getRequest($request, 'callback');
    
        $ret = $this->getInvoiceYearMonth();
    
        $this->printJSONP($ret, $callback);
    }
    
    /**
     * 保護者の割引・割増定義取得
     */
    public function executeGetParentAdjust(Request $request){
    
        if( !isset($request['parent_id'])){
            return ;
        }
    
        $callback = $this->getRequest($request, 'callback');
    
        // 対象取得
        $adjust = RoutinePaymentTable::getInstance()->getRoutinePayemntList(session('school.login')['id'], 3, $request['parent_id']);
    
        $list = array();
        $list['result_code'] = "OK";
        $list['adjust_list'] = $adjust;
    
        $this->printJSONP($list, $callback);
    }
    
    /**
     * 保護者の割引・割増登録チェック
     * @return boolean
     */
    public function executeCheckParentAdjust(Request $request){
    
        $ret = array();
        $callback = $this->getRequest($request, 'callback');
    
    
        if( !isset($request['parent_id'])){
            $ret['status'] = "NG";
            $errors[] = array("msg"=>"内部エラー");
            $ret['errors'] = $errors;
            $this->printJSONP($ret, $callback);
//             return false;
        }
    
        if( !isset($request['month'])){
            $ret['status'] = "NG";
            $errors[] = array("msg"=>"内部エラー");
            $ret['errors'] = $errors;
            $this->printJSONP($ret, $callback);
//             return false;
        }
    
        if( !isset($request['adjust'])){
            $ret['status'] = "NG";
            $errors[] = array("msg"=>"内部エラー");
            $ret['errors'] = $errors;
            $this->printJSONP($ret, $callback);
//             return false;
        }
    
        if( !isset($request['fee'])){
            $ret['status'] = "NG";
            $errors[] = array("msg"=>"内部エラー");
            $ret['errors'] = $errors;
            $this->printJSONP($ret, $callback);
//             return false;
        }
    
        if( !isset($request['id'])){
            $ret['status'] = "NG";
            $errors[] = array("msg"=>"内部エラー");
            $ret['errors'] = $errors;
            $this->printJSONP($ret, $callback);
//             return false;
        }
    
        if( !isset($request['count'])){
            $ret['status'] = "NG";
            $errors[] = array("msg"=>"内部エラー");
            $ret['errors'] = $errors;
            $this->printJSONP($ret, $callback);
//             return false;
        }
    
    
        //---------------------------------------------------------------------
        // 取得したデータを配列にする
        //---------------------------------------------------------------------
        $months 	= explode(',', $request['month']);
        $adjusts    = explode(',', $request['adjust']);
        $fees 		= explode(',', $request['fee']);
        $ids 		= explode(',', $request['id']);
        $count      = $request['count'];
    
        $errors = array();
        // データ件数チェック
        if( count($months) < $count ){
            $errors[] = array("msg"=>"データマッチエラー");
        }
    
        if( count($adjusts) < $count ){
            $errors[] = array("msg"=>"データマッチエラー");
        }
    
        if( count($fees) < $count ){
            $errors[] = array("msg"=>"データマッチエラー");
        }
    
        if( count($ids) < $count ){
            $errors[] = array("msg"=>"データマッチエラー");
        }
    
        // 未入力チェック
        for( $idx = 0; $idx < $count; $idx++){
            if( intval($months[$idx]) < 1 ){
                $errors[] = array("msg"=>"対象月が設定されていません");
            }
        }
        for( $idx = 0; $idx < $count; $idx++){
            if( intval($adjusts[$idx]) < 1 ){
                $errors[] = array("msg"=>"摘要が設定されていません");
            }
        }
        for( $idx = 0; $idx < $count; $idx++){
            if( $fees[$idx] == "" ){
                $errors[] = array("msg"=>"金額が設定されていません");
            } else if( !preg_match("/^[-]?[0-9]+(\.[0-9]+)?$/", $fees[$idx]) ) {
                $errors[] = array("msg"=>"金額に数値を設定してください");
            }
        }
    
        // 同一のものが存在するか
        if( count($errors) < 1 ){
            for( $idx1 = 0; $idx1 < $count; $idx1++ ){
                for( $idx2 = 0; $idx2 < $count; $idx2++ ){
                    if( $idx1 != $idx2 ){
                        if( $months[$idx1] == $months[$idx2] &&
                                $adjusts[$idx1] == $adjusts[$idx2] &&
                                $idx1 < $idx2 ){
                                    // 同じもの発見
                                    $errors[] = array("msg"=> "対象月と摘要の組み合わせが同じものが存在します");
                        }
                    }
                }
            }
        }
    
        $status = array();
        if( count($errors) < 1 ){
            $ret['status'] = "OK";
        } else {
            $status = array();
            $ret['status'] = "NG";
            $ret['msg'] = $errors[0]['msg'];
        }
    
        $this->printJSONP($ret, $callback);
        //		print $callback . '(' . json_encode( $ret ). ')';
    
    }
    
    /**
     * 保護者の割引・割増登録
     */
    public function executeRegistParentAdjust(Request $request){
    
        $bError = false;
        if( !isset($request['parent_id'])){
            $bError = true;
        }
    
        if( !isset($request['month'])){
            $bError = true;
        }
    
        if( !isset($request['adjust'])){
            $bError = true;
        }
    
        if( !isset($request['fee'])){
            $bError = true;
        }
    
        if( !isset($request['id'])){
            $bError = true;
        }
    
        $callback = $this->getRequest($request, 'callback');
        if( $bError){
            $ret['status'] = 'NG';
            $this->printJSONP($ret, $callback);
//             return false;
        }
    
    
        $ret = array();
    
        //---------------------------------------------------------------------
        // 取得したデータを配列にする
        //---------------------------------------------------------------------
        $months 	= explode(',', $request['month']);
        $adjust_ids = explode(',', $request['adjust']);
        $fees 		= explode(',', $request['fee']);
        $ids 		= explode(',', $request['id']);
    
    
        $PaymentTable = RoutinePaymentTable::getInstance();
    
        $PaymentTable->beginTransaction();
    
        try {
            // ①まず登録されているもの取得
            $registed_list = RoutinePaymentTable::getInstance()->getList($where=array('pschool_id'=>session('school.login')['id'],
                    'data_div'=>3,
                    'data_id'=>$request['parent_id'],
                    'delete_date IS NULL'));
    
            if( count($months) < 1 ){
                // ②既存の全部削除
                foreach ($registed_list as $regist_item){
                    $regist_item['active_flag']   	= 0;
                    $regist_item['delete_date']   	= date('Y-m-d H:i:s');
                    $regist_item['update_date']   	= date('Y-m-d H:i:s');
                    $regist_item['update_admin']	= session('school.login')['login_account_id'];
                    $PaymentTable->updateRow($regist_item, $where=array('id'=>$regist_item['id']));
                }
            } else {
                if( count($registed_list) < 1  ){
                    // ③全部登録
                    for( $idx = 0; $idx < count($months); $idx++ ){
                        $Row = array();
                        $Row['pschool_id']      = session('school.login')['id'];
                        $Row['data_div']        = 3;
                        $Row['data_id']         = $request['parent_id'];
                        $Row['month']           = $months[$idx];
                        $Row['invoice_adjust_name_id']    = $adjust_ids[$idx];
                        $Row['adjust_fee']      = $fees[$idx];
                        $Row['student_type']	= null;
                        $Row['register_date']   = date('Y-m-d H:i:s');
                        $Row['register_admin']	= session('school.login')['login_account_id'];
    
                        $PaymentTable->insertRow($Row);
                    }
                } else {
                    // ④一部登録と一部更新
                    for( $idx = 0; $idx < count($months); $idx++ ){
                        $bExist = false;
                        foreach ($registed_list as $regist_item ){
                            if( isset($ids[$idx]) && !empty($ids[$idx])){
                                if( $ids[$idx] == $regist_item['id']){
                                    $bExist = true;
                                    break;
                                }
                            }
                        }
                        if( $bExist ){
                            // 存在するので更新
                            $regist_item['month'] 			= $months[$idx];
                            $regist_item['invoice_adjust_name_id']    = $adjust_ids[$idx];
                            $regist_item['adjust_fee']      = $fees[$idx];
                            $regist_item['update_date']   	= date('Y-m-d H:i:s');
                            $regist_item['update_admin']	= null;
    
                            $PaymentTable->updateRow($regist_item, $where=array('id'=>$regist_item['id']));
                        }else {
                            // 存在しないので追加
                            $Row = array();
                            $Row['pschool_id']      = session('school.login')['id'];
                            $Row['data_div']        = 3;
                            $Row['data_id']         = $request['parent_id'];
                            $Row['month']           = $months[$idx];
                            $Row['invoice_adjust_name_id']    = $adjust_ids[$idx];
                            $Row['adjust_fee']      = $fees[$idx];
                            $Row['student_type']	= null;
                            $Row['register_date']   = date('Y-m-d H:i:s');
                            $Row['register_admin']	= session('school.login')['login_account_id'];
    
                            $PaymentTable->insertRow($Row);
                        }
                    }
    
                    // ⑤一部削除
                    foreach ($registed_list as $regist_item ){
                        $bExist = false;
                        for( $idx = 0; $idx < count($months); $idx++ ){
                            if( isset($ids[$idx]) && !empty($ids[$idx])){
                                if( $regist_item['id'] == $ids[$idx] ){
                                    $bExist = true;
                                    break;
                                }
                            }
                        }
                        if( !$bExist ){
                            // 存在しないので削除
                            $regist_item['active_flag']   	= 0;
                            $regist_item['delete_date']   	= date('Y-m-d H:i:s');
                            $regist_item['update_date']   	= date('Y-m-d H:i:s');
                            $regist_item['update_admin']	= session('school.login')['login_account_id'];
    
                            $PaymentTable->updateRow($regist_item, $where=array('id'=>$regist_item['id']));
                        }
                    }
                }
            }
    
            $PaymentTable->commit();
            $ret['status'] = 'OK';
        }catch (Exception $ex){
            $PaymentTable->rollBack();
            //			$this->_logger->error($ex->getMessage());
            //			$message_type = 99;	//エラーメッセージを表示
            $ret['status'] = 'NG';
        }
    
    
        $this->printJSONP($ret, $callback);
    }
    
    private function create_tempdir() {
        //PDF作成用一時フォルダ取得
        $this->tempDir = MiscUtil::createTempdir();
        if (!MiscUtil::endsWith($this->tempDir, DIRECTORY_SEPARATOR)) {
            $this->tempDir .= DIRECTORY_SEPARATOR;
        }
        $this->create_log[] = "一時フォルダ：{$this->tempDir} を作成しました。";
    }
    
    /*
     * 請求書編集時のパラメーターチェック処理
     */
    private function checkEditParam(Request $request) {
        if (!isset($request["id"]) || !strlen($request["id"])) {
            // TOPに戻す。
            //			HeaderUtil::redirect($this->get_app_path() . self::$TOP_URL);
        }
    
        $header = InvoiceHeaderTable::getInstance()->getRow(array(
                "id" => $request["id"],
                "pschool_id" => session('school.login')['id'],
                //			"active_flag" => 1,
                "delete_date is null",
        ));
    
        return $header;
    }
    
    private function createPdf( $id , $data , $mode="") {
        if( $mode == "receipt"){	// 領収書
            $tpl = '_pdf.invoice_receipt';
        }
        else{
            $tpl = '_pdf.invoice_print';
        }
        view()->share('data',$data);
        if(isset($data)){
            $pdf = PDF::loadView($tpl);
            return $pdf->download('show.pdf');
        }
        return view('School.Invoice.Richo.mail_search');
    }
    private function set_message( $status , $msg ){
        if( $status == 'NG'){
            $this->_result['error'] = $msg;
        }
        if( $status == 'OK'){
            $this->_result['result'] = $msg;
        }
    }
    
    /*
     * 印刷アクション
     */
    public function executePrint( $id ) {
        // 請求書情報取得
        $header = InvoiceHeaderTable::getInstance()->getRow(array(
                "id" => $id,
                "pschool_id" => session('school.login')['id'],
                "active_flag" => 1,
                "delete_date IS NULL",
        ));
        if (empty($header)) {
            //無効ですページ
            $this->set_message('NG', '請求書情報が取得できませんでした。');
            return false;
        }
    
        // 保護者情報取得
        $parentRow = ParentTable::getInstance()->getRow($where=array("pschool_id" => session('school.login')['id'], 'id'=>$header["parent_id"]));
        if( empty($parentRow) || count($parentRow) < 1 ){
            //無効ですページ
            $this->set_message('NG', '請求書情報が取得できませんでした。');
            return false;
        }
    
        ////////// 保護者の支払方法で、請求書のフォーマットが変わる   $parentRow['invoice_type'];
    
        $parentStudent = InvoiceHeaderTable::getInstance()->getParentStudentListByInvoiceId($header["pschool_id"], $header["id"], $header["parent_id"]);
        if (empty($parentStudent)) {
            //無効ですページ
            $this->set_message('NG', '請求書情報が取得できませんでした。');
            return false;
        }
        $data = $this->setPrintDataFromDb($header, $parentStudent);
        //$this->assignVars('data', $parentStudent);
    
        return $data;
    }
    /*
     * メール通知　完了画面
     */
    public function executeMailSend(Request $request) {

//         dd($request);
        if( !$request->exists('parent_ids')){
            $this->_result['error'] = '請求書が選択されていません。';
            echo json_encode($this->_result);
            return false;
        }
//         $this->create_tempdir();
//         session()->put(self::SESS_PDF_KEY, $this->tempDir);
//         if( isset($_SESSION[self::SESS_PDF_FILE_KEY])){
//             $this->created_pdf_list = $_SESSION[self::SESS_PDF_FILE_KEY];
//         }
        
        $request['id'] = $request['parent_ids'];
        
        $header = $this->checkEditParam($request);
        if ($header["is_established"] != "1") {
            // 確定済みでない場合はメール通知できないので、元の画面に戻す。
            $this->set_message("NG", "編集中のため、メール通知できません。");
            //continue;		//$this->redirect_history(0);
        }
        
        $parentStudent = ParentTable::getInstance()->getParentStudentListById(session('school.login')['id'], $header["parent_id"]);
//         dd($parentStudent);
        if (empty($parentStudent)) {
            // 存在しない保護者なのでTOPに戻す。
            $this->set_message("NG", "保護者がいません。");
            //continue;		//HeaderUtil::redirect($this->get_app_path() . self::$TOP_URL);
        }
        
        // 支払い期限のnull対策
        $due_dates = $header['register_date'];
        if (date('j', strtotime($due_dates)) > session('school.login')['payment_date'] ){
            $due_dates = date('Y-m-t', strtotime($due_dates." next month"));
        }
        if (session('school.login')['payment_date']==99){
            $due_dates = date('Y-m-t', strtotime($due_dates));
        }else{
            $due_dates = date('Y-m-', strtotime($due_dates)) . str_pad(session('school.login')['payment_date'], 2, 0, STR_PAD_LEFT);
        }
        
        //			if($parentStudent["mail_infomation"] == 0)		// 郵送の請求書の時
        if(array_get($header,"mail_announce") == 0)		// 郵送の請求書の時
        {
        	
            $data = $this->executePrint( $request['id'] );	// $this->executePrint( $row );
            
            if(isset($data)){
                
                $this->createPdf( $request['id'] , $data );	// $this->createPdf( $row , $data );
            }
            
            try {
                InvoiceHeaderTable::getInstance()->beginTransaction();
    
                // 請求書発送済みにする。
                $headers = array(
                        "id" => $header["id"],
                        "is_mail_announced" => "1",
                        "is_requested" => 1,
                        "announced_date" => date('Y-m-d H:i:s'),
                        "due_date" => empty($header['due_date']) ? $due_dates : $header['due_date'],
                        "update_admin" => session('school.login')['login_account_id'],
                        "workflow_status" => ($header['workflow_status']<11) ? 11 : $header['workflow_status'],
                );
                InvoiceHeaderTable::getInstance()->save($headers);
                InvoiceHeaderTable::getInstance()->commit();
            } catch (Exception $ex) {
                InvoiceHeaderTable::getInstance()->rollBack();
                $this->set_message("NG", "エラーが発生したため処理できませんでした。");
            }
            $this->set_message("OK", "請求書を印刷しました。");
        }
        //			if($parentStudent["mail_infomation"] == 1)		// メール通知の請求書の時
        elseif($header["mail_announce"] == 1)		// メール通知の請求書の時
        {
            // メール通知を行う。
            try {
                InvoiceHeaderTable::getInstance()->beginTransaction();
    
                // メール通知済みにする。
                $headers = array(
                        "id" => $header["id"],
                        "is_mail_announced" => "1",
                        "is_requested" => 1,
                        "announced_date" => date('Y-m-d H:i:s'),
                        "due_date" => empty($header['due_date'])? $due_dates:$header['due_date'],
                        "update_admin" => session('school.login')['login_account_id'],
                        "workflow_status" => ($header['workflow_status']<11) ? 11 : $header['workflow_status'],
                );
                InvoiceHeaderTable::getInstance()->save($headers);
    
                // メール情報テーブルにメール情報を登録する。
                //set send date
                $time_send ="";
                if($request->schedule_date !== null){
                    $time_send = date("Y-m-d H:i:s", strtotime($request->schedule_date));
                }else{
                    $time_send = date("Y-m-d H:i:s");
                }
                $message_key = $this->getMailMessageKey();
                $message = array();
                $message['type'] = 1;
                $message['message_key'] = $message_key;
                $message['relative_ID'] = $header["id"];
                $message['pschool_id'] = session('school.login')['id'];
                $message['parent_id'] = $parentStudent["id"];
                $message['send_date'] =  $time_send;
                $message['register_admin'] = session('school.login')['login_account_id'];
    
                $arr = MailMessageTable::getInstance()->save($message);
//                 dd($arr);
                $send_mail_params = array();
                $send_from = session('school.login')['mailaddress'];
                $send_to = $parentStudent['parent_mailaddress1'];
                $send_subject = 'ご請求確定のお知らせ';
    
//                 $tpl = self::$MAIL_TEMPLATE;
                $assign_var = array();
                $parent_name = $parentStudent['parent_name'];
                $school_name = session('school.login')['name'];
                $contact = session('school.login')['mailaddress'];
                $daihyou = session('school.login')['daihyou'];
                $reply = MAIL_FROM;
                $url = $this->createActivateUrl(self::$MAIL_URL, "?message_key=".$message_key);
                
                Mail::to ( $send_to )->send ( new MailInvoice( $send_from, $send_subject, $parent_name,
                        $school_name, $contact, $daihyou, $url,$reply ) );
    
//                 if(!$this->send_mail($send_mail_params)){
//                     // 送信失敗
//                     throw new Exception("メールの送信に失敗しました。");
//                 }
    
                InvoiceHeaderTable::getInstance()->commit();
    
                $this->set_message("OK", "請求書をメール通知しました。");
            } catch (Exception $ex) {
                InvoiceHeaderTable::getInstance()->rollBack();
                $this->set_message("NG", "エラーが発生したため処理できませんでした。");
            }
        }
        //}
        session()->put(self::SESS_PDF_FILE_KEY, $this->created_pdf_list);
        $this->_result['id'] = count($this->created_pdf_list);
        echo json_encode($this->_result);
//         return false;
        //$this->redirect_history(0);
    }
    
    protected function createMailMessage($tpl, $assign_var) {
    
        $tpl_path = resource_path () . '/views/_mail/'.$tpl;
    
        if (File::exists ( $tpl_path )) {
            $menu_content = File::get( $tpl_path );
            $abc = view('_mail.invoice_mail_notification');
            $view = View::make('_mail.invoice_mail_notification', ['mail' => $assign_var]);
    
            return $view->render();
        } else {
            throw new Exception( 'Template File Not Found!!' );
        }
    }
    
    /*
     * DBからとってきたデータを印刷画面表示用に加工する。
     */
    private function setPrintDataFromDb($header, $parentStudent) {
        $data = array();
        $data = $parentStudent;
        $item_list = InvoiceItemTable::getInstance()->getList(array('invoice_id' => $header["id"]), array("class_id", "course_id", "item_name", "unit_price"));
        $data["active_student_id"] = $parentStudent["student_list"][0]["id"];
        $data["invoice_year_month"] = $header["invoice_year_month"];
    
        $data["amount_display_type"] = $header["amount_display_type"];
        $data["sales_tax_rate"] = $header["sales_tax_rate"];
        $data["mail_announce"] = $header["mail_announce"];
        $data["is_established"] = $header["is_established"];
        $data["is_recieved"] = $header["is_recieved"];
    
        $data["school_name"] = session('school.login')['name'];
        $data["school_address"] = session('school.login')['pref_name'] . session('school.login')['city_name'] . session('school.login')['address'];
        $data["school_daihyou"] = session('school.login')['daihyou'];
    
        $publish_year = date('Y') - 1988;
        $data["publish_date_y"] = $publish_year;
        $data["publish_date_m"] = date('n');
        $data["publish_date_d"] = date('j');
    
        $due_year = substr($header["due_date"], 0, 4) - 1988;
        $data["due_date_y"] = $due_year;
        $due_month = ltrim(substr($header["due_date"], 5, 2), '0');
        $data["due_date_m"] = $due_month;
        $due_day = ltrim(substr($header["due_date"], 8, 2), '0');
        $data["due_date_d"] = $due_day;
    
        $bank_account = PschoolBankAccountTable::getInstance()->getRow(array('pschool_id' =>session('school.login')['id'], "delete_date IS NULL"));
        if (!empty($bank_account)) {
            $data["bank_name"] = $bank_account['bank_name'];
            $data["branch_name"] = $bank_account['branch_name'];
            $data["bank_account_number"] = ConstantsModel::$type_of_bank_account[session('school.login') ['language']][$bank_account['bank_account_type']] ." ". $bank_account['bank_account_number'];
            $data["bank_account_name"] = $bank_account['bank_account_name'];
            $data["bank_account_name_kana"] = $bank_account['bank_account_name_kana'];
    
            if (!empty($bank_account['post_account_number']) && !empty($bank_account['post_account_name'])) {
                $data["post_account_kigou"] = $bank_account['post_account_kigou'];
                $data["post_account_number"] = $bank_account['post_account_number'];
                $data["post_account_name"] = $bank_account['post_account_name'];
            }
        }
    
        $data["discount_name"] = array();
        $data["discount_price"] = array();
        $data["class_id"] = array();
        $data["class_name"] = array();
        $data["class_price"] = array();
        $data["course_id"] = array();
        $data["course_name"] = array();
        $data["cource_price"] = array();
        $data["custom_item_name"] = array();
        $data["custom_item_price"] = array();
        foreach ($parentStudent["student_list"] as $k => $v) {
            $data["class_id"][$v["id"]] = array();
            $data["class_name"][$v["id"]] = array();
            $data["class_price"][$v["id"]] = array();
            $data["course_id"][$v["id"]] = array();
            $data["course_name"][$v["id"]] = array();
            $data["cource_price"][$v["id"]] = array();
            $data["custom_item_name"][$v["id"]] = array();
            $data["custom_item_price"][$v["id"]] = array();
        }
    
        $sum_discount_price = 0;
        $amount = 0;
        foreach ($item_list as $item) {
            if (!strlen($item["student_id"])) {
                $data["discount_name"][] = $item["item_name"];
                $data["discount_price"][] = str_replace("-", "", $item["unit_price"]);
                $sum_discount_price += intval($item["unit_price"]);
            } else if (strlen($item["class_id"])) {
                $data["class_id"][$item["student_id"]][] = $item["class_id"];
                $data["class_name"][$item["student_id"]][] = $item["item_name"];
                $data["class_price"][$item["student_id"]][] = $item["unit_price"];
                $amount += $item["unit_price"];
            } else if (strlen($item["course_id"])) {
                $data["course_id"][$item["student_id"]][] = $item["course_id"];
                $data["course_name"][$item["student_id"]][] = $item["item_name"];
                $data["course_price"][$item["student_id"]][] = $item["unit_price"];
                $amount += $item["unit_price"];
            } else {
                $data["custom_item_name"][$item["student_id"]][] = $item["item_name"];
                $data["custom_item_price"][$item["student_id"]][] = $item["unit_price"];
                $amount += $item["unit_price"];
            }
        }
    
        if (empty($data["discount_name"])) {
            $data["discount_name"][] = "";
            $data["discount_price"][] = "";
        }
    
        foreach ($data["custom_item_name"] as $student_id => $custom_item_name_list) {
            if (empty($custom_item_name_list)) {
                $data["custom_item_name"][$student_id][] = "";
                $data["custom_item_price"][$student_id][] = "";
            }
        }
    
        $data["sum_discount_price"] = $sum_discount_price;
        $amount += $sum_discount_price;
        $data["amount"] = $amount;
    
        $tax_price = 0;
        $amount_tax = 0;
        $sales_tax_rate = floatval($data["sales_tax_rate"]);
        if ($data["amount_display_type"] == "0") {
            $tax_price = floor($amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100));
            $amount_tax = $amount;
        } else {
            $tax_price = floor($amount * $sales_tax_rate);
            $amount_tax = $amount + $tax_price;
        }
    
        $data["tax_price"] = $tax_price;
        $data["amount_tax"] = $amount_tax;
    
        return $data;
    }
    /**
     * アクティベートURLの生成
     */
    protected function createActivateUrl($type, $hash_code) {
        return 'http://' . $_SERVER['HTTP_HOST'] . $type . '/' . $hash_code;
    }
    
    
    /**
     * MailMessageテーブル用のハッシュキーを生成する。
     *
     */
    public static function getMailMessageKey() {
    
        $key = md5(self::generateRandomString(64));
    
        $message = MailMessageTable::getInstance()->getRow(array(
                "message_key" => $key,
        ));
        if (!empty($message)) {
            $key = self::getMailMessageKey();
        }
    
        return $key;
    }
    /**
     * @return string
     */
    public static function generateRandomChar() {
        $r = mt_rand(0, 61);
        if ($r < 10) {
            $c = $r;
        } elseif($r >= 10 && $r < 36) {
            $r -= 10;
            $c = chr($r + ord('a'));
        } else {
            $r -= 36;
            $c = chr($r + ord('A'));
        }
        return $c;
    }
    
    /**
     * @return string
     */
    public static function generateRandomString($length) {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= self::generateRandomChar();
        }
        return $result;
    }
}

