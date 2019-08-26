<?php

namespace App\Http\Controllers\School;

use App\Model\InvoiceItemTable;
use App\Model\PschoolBankAccountTable;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Model\PaymentMethodSettingTable;
use App\Model\InvoiceHeaderTable;
use App\Model\MailMessageTable;
use App\Model\PschoolTable;
use App\Model\ParentTable;
use App\ConstantsModel;
use App\Lang;
use Log;
use PDF;



class InvoiceController extends _BaseSchoolController
{   use \App\Common\Email;
    //private static $TOP_URL = 'invoice';
    //protected static $ACTION_URL = 'invoice';
    protected static $LANG_URL = 'invoice';
    private $lan;
    public function __construct() {
        parent::__construct ();
        $message_content = parent::getMessageLocale ();
        $this->lan = new Lang ( $message_content );
        return redirect ( $this->get_app_path ());
    }

    // to summary page
    public function execute(Request $request){
        //TODO get summary invoice list of pschool
        // get summary and count each payment method
        $invoice_list = $this->getInvoiceStatusMenu($request);
        $invoice_type_constant = ConstantsModel::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];
        return view("School.Invoice.summary",compact('invoice_list','invoice_type'));
    }


    /**
     * listing invoice in one certain month
     * @param Request $request
     * @return School.Invoice.top
     */
    public function executeList(Request $request){
        $this_screen = "list";
        //TODO listing invoice in month
        //get summary for menu
        if($request->offsetExists('invoice_year_month')){
            $invoice_year_month = $request->invoice_year_month;
            $count_invoice = $this->getInvoiceStatusMenu($request);
            view()->share('heads',$count_invoice);

            // get invoice list
            $invoice_list = InvoiceHeaderTable::getInstance()->getListInvoiceByMonth(session()->get('school.login.id'),$invoice_year_month);
            $invoice_type_constant = ConstantsModel::$invoice_type;
            $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];
            $lan = $this->lan;
            return view ( 'School.Invoice.top',compact('lan','this_screen','invoice_list','invoice_type','request'));
        }else{
            return redirect('/school/invoice');
        }
    }

    /**
     * Generate bulk invoice
     * Determine payment type of school (1 -> prepay , 2-> postpay
     * Call function to get list parent
     * @param Request $request
     */
    public function executeGenerateInvoice(Request $request){
        $pschoolTable = PschoolTable::getInstance();
        $paymentMethodSetting = PaymentMethodSettingTable::getInstance();
        $pschool = $pschoolTable->load(session()->get('school.login.id'));
        if(isset($request->invoice_year_month)){
            $invoice_year_month = $request->invoice_year_month;
            $invoiceHeaderTable = InvoiceHeaderTable::getInstance();

            $invoiceHeaderTable->beginTransaction();
            try{
                //TODO get list parent payment monthly
                $list_parent_monthly = $invoiceHeaderTable->getListParentMonthly($pschool['id'],$invoice_year_month);
                $list_parent_event = $invoiceHeaderTable->getListParentEvent($pschool['id'],$invoice_year_month);
                $list_parent_program = $invoiceHeaderTable->getListParentProgram($pschool['id'],$invoice_year_month);

                $list_parent_monthly = array_merge($list_parent_monthly,$list_parent_event,$list_parent_program);

                //$list_parent_monthly= $payme$invoiceHeaderTablentMethodSetting->getListDueDateInvoice($list_parent_monthly,$invoice_year_month);
                if(!empty($list_parent_monthly)){
                    $invoiceHeaderTable->processListInvoiceHeader($pschool,$list_parent_monthly);
                }


                //TODO get list parent payment schedule
                $schedule_month = date("Y-m",strtotime($invoice_year_month."-01"." -1 month"));
                $list_parent_schedule = $invoiceHeaderTable->getListParentSchedule($pschool['id'],$invoice_year_month,$schedule_month);
                //$list_parent_schedule= $paymentMethodSetting->getListDueDateInvoice($list_parent_schedule,$invoice_year_month);
                if(!empty($list_parent_schedule)){
                    $invoiceHeaderTable->processListInvoiceHeader($pschool,$list_parent_schedule);
                }
                $invoiceHeaderTable->commit();
                return $this->executeList($request);
            }catch (Exception $e){
                $invoiceHeaderTable->rollBack();
            }
        }else{
            return redirect()->to('/school/invoice');
        }
    }

    /**
     * @param : header_id and invoice_year_month
     * @return
     */
    public function executeDetail(Request $request){

        if(!isset($request->id)){
            return redirect()->to('/school/invoice');
        }
        $header_id = $request->id;
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $data = $invoiceHeaderTable->getDataParentStudent($header_id);

        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);
        return view('School.Invoice.detail',compact('request','data'));
    }
    /**
     * confirm stage1 -> stage2
     * @param Request $request
     */
    public function executeConfirm(Request $request){
        //TODO get list invoice selected stage 1 and update to stage 2
        $lan = $this->lan;
        if(!isset($request->parent_ids) || !isset($request->invoice_year_month)){
            $request->offsetSet('errors',$lan::get("list_screen_error"));
            return $this->executeList($request);
        }
        try {
            $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
            $invoiceHeaderTable->beginTransaction();

            foreach ($request->parent_ids as $id){
                $header = array(
                        "id" => $id,
                        "is_established" => "1",
                        "workflow_status" => 1
                );
                $invoiceHeaderTable->save($header);
            }

            $invoiceHeaderTable->commit();
            $request->offsetSet('messages',$lan::get("invoice_item_edited,".count($request->parent_ids)));
        } catch (Exception $ex) {
            InvoiceHeaderTable::getInstance()->rollBack();
            $request->offsetSet('errors',$lan::get("process_invoice_error_message"));
        }
        return $this->executeList($request);
    }

    // mail send and pdf export , update stage2 to stage 3
    public function executeMailSend(Request $request){
        //TODO get list invoice selected stage 2 and update to stage 3
        $lan = $this->lan;
        $str="";
        $invoiceHeaderTbl = InvoiceHeaderTable::getInstance();
        foreach ($request->parent_ids as $key=>$parent_id) {
            $request->offsetSet('id', $parent_id);
            $header = $this->checkEditParam($request);

            if ($header["is_established"] != "1") {
                // 確定済みでない場合はメール通知できないので、元の画面に戻す。
                Log::warning($lan::get('cannot_email_notify_message'));
                continue;		//$this->redirect_history(0);
            }

            $parentStudent = ParentTable::getInstance()->getParentStudentListById(session('school.login')['id'], $header["parent_id"]);
            if (empty($parentStudent)) {
                // 存在しない保護者なのでTOPに戻す。
                continue;
            }

            if($header["mail_announce"] == 0) {// 郵送の請求書の時
                ////////// 保護者の支払方法で、請求書のフォーマットが変わる   $parentRow['invoice_type'];

                $parentStudent = $invoiceHeaderTbl->getParentStudentListByInvoiceId($header["pschool_id"], $header["id"], $header["parent_id"]);
                if (!empty($parentStudent)) {

                    $data = $this->setPrintDataFromDb($header, $parentStudent);

                    $str .= $this->createPdf( $data ,$key);

                }

            } elseif($header["mail_announce"] == 1)	{// メール通知の請求書の時

                try {
                    $invoiceHeaderTbl->beginTransaction();

                    // メール通知済みにする。
                    $header_upd = array(
                        "id"                => $header["id"],
                        "is_mail_announced" => "1",
                        "is_requested"      => 1,
                        "announced_date"    => date('Y-m-d H:i:s'),
                        "update_admin"      => session('school.login')['login_account_id'],
                        "workflow_status"   => 11,
                    );

                    $invoiceHeaderTbl->save($header_upd);

                    // メール情報テーブルにメール情報を登録する。
                    $mail_message = MailMessageTable::getInstance()->getActiveRow(['type'=>1, 'relative_ID'=>$header["id"], 'parent_id'=>$parentStudent["id"]]);

                    $message = array();
                    if (empty($mail_message)) {
                        $message_key = md5($this->generateRandomString(64));
                        $message['type']         = 1;
                        $message['message_key']  = $message_key;
                        $message['relative_ID']  = $header["id"];
                        $message['pschool_id']   = session('school.login')['id'];
                        $message['parent_id']    = $parentStudent["id"];
                        $message['total_send']   = 0; // first send
                        $message_id = MailMessageTable::getInstance()->save($message);
                    } else {
                        $message_id = $mail_message['id'];
                    }
                    $invoiceHeaderTbl->commit();
                    $this->sendMailReceiptType($message_id);

//                    email_notify_to_invoice
                } catch (Exception $ex) {
                    $invoiceHeaderTbl->rollBack();
                    Log::error($lan::get('process_invoice_error_message'));
                }
            }

        }
        if ($str != "") {
            $domPdf = new Dompdf();
            $domPdf->loadHtml($str);
            $domPdf->render();
            $domPdf->stream('invoicePDF.pdf'); //array("compress"=>false, "Attachment"=>false)
        }
        $data = true;
        echo json_encode($data);
    }

    // list invoice that current stage3 for processing
    public function executeDepositProcess(Request $request){
        //TODO list invoice that current stage3 for processing

    }


    /**
     * get summary of invoice for progress menu
     * if invoice_year_month is not exists in request -> get all invoice and group by invoice_year_month
     * @param Request $request
     * @return array
     */
    private function getInvoiceStatusMenu(Request $request=null){
        $count_invoice= array();
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $pschool_id = session()->get('school.login.id');

        if($request->offsetExists('invoice_year_month')){
            $invoice_year_month = $request->invoice_year_month;
            $count_invoice = $invoiceHeaderTable->countInvoice($pschool_id,$invoice_year_month);
            $count_invoice = $count_invoice[0];
        }else{
            $count_invoice = $invoiceHeaderTable->countInvoice($pschool_id);
        }
        return $count_invoice;
    }

    /*
	 * 請求書編集時のパラメーターチェック処理
	 */
    private function checkEditParam($request) {
        if (!$request->has('id') || !strlen($request->id)) {
            // TOPに戻す。
            return redirect('/school/invoice');
        }

        $header = InvoiceHeaderTable::getInstance()->getActiveRow(array(
            "id"            => $request->id,
            "pschool_id"    => session('school.login')['id'],
//			"active_flag" => 1,
        ));
        if (empty($header)) {
            // 存在しない請求書なのでTOPに戻す。
            return redirect('/school/invoice');
        }
        return $header;
    }

    // ----------------StringUtils-------------------//
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

    private function createPdf( $data, $i) {

        try {

            return view('_pdf.invoice_print', compact( 'data'));
//            dump($dompdf);
//            $dompdf = new Dompdf($this->pdf->getOptions()); //
//            $dompdf->set_paper('A4', 'landscape');
//            $dompdf->loadHtml(view('_pdf.invoice_print', compact( 'data')));
//            $dompdf->outputHtml();
//            $dompdf->render();
//            return $dompdf->output();
//            $dompdf->stream('invoicePDF.pdf');
        } catch (Exception $e) {
            var_dump($e);
        }

//        return true;
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
}
