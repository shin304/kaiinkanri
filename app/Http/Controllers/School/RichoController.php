<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\InvoiceHeaderTable;
use App\Model\InvoiceItemTable;
use App\Model\InvoiceHeaderTemplateTable;
use App\Model\InvoiceHistoryItemTable;
use App\Model\InvoiceHistoryHeaderTable;
use App\Model\InvoiceItemTemplateTable;
use App\Model\MailMessageTable;
use App\Model\StudentTable;
use App\Model\ParentTable;
use App\Model\ClassTable;
use App\Model\ClassFeeTable;
use App\Model\CourseTable;
use App\Model\CourseFeeTable;
use App\Model\StudentCourseRelTable;
use App\Model\PschoolTable;
use App\Model\PschoolBankAccountTable;
use App\Model\SchoolMenuTable;
use App\Model\ParentBankAccountTable;
use App\Model\InvoiceRequestTable;
use App\Model\ClosingDayTable;
use App\Model\InvoiceAdjustNameTable;
use Illuminate\Http\Request;
use App\Model\HierarchyTable;
use App\Model\ProgramTable;
use App\Http\Controllers\Common\ValidateRequest;
use App\Http\Controllers\Common\Validaters;
use App\Lang;
use App\ConstantsModel;
use App\Http\Controllers\Common\AuthConfig;

define ( 'PDFTK_CMD', "/usr/bin/pdftk" );

/**
 * 請求書
 */
class RichoController extends _BaseSchoolController {
    private static $TOP_URL = 'invoice?menu';
    private static $MAIL_TEMPLATE = 'invoice_mail_notification.tpl';
    private static $MAIL_URL = '/portal/invoice/detail';
    private static $ACTION_URL = 'invoice';
    protected static $LANG_URL = 'invoice';
    // 入力画面の入力値を保存するキー
    const SESSION_INPUT_DATA = 'invoice_class_input_data';
    
    // 請求書検索画面の入力値を保存するキー
    const SESSION_SEARCH_COND = 'invoice_class_search_cond';
    
    // 入金チェック検索画面の入力値を保存するキー
    const SESSION_RECIEVE_SEARCH_COND = 'invoice_class_recieve_search_cond';
    
    // メール通知検索画面の入力値を保存するキー
    const SESSION_MAIL_SEARCH_COND = 'invoice_class_mail_search_cond';
    
    // 遷移元画面を保存するキー
    const SESSION_HISTORY_LIST = 'invoice_class_history_list';
    
    // メッセージを保存するキー
    const SESSION_MESSAGE = 'invoice_class_message';
    
    // 検索結果を保存するキー
    const SESSION_SEARCH_LIST = 'invoice_class_search_list';
    const SESSION_CURRENT_DISP_ID = 'invoice_class_current_disp_id';
    
    // アップロードファイル名を保存するキー
    const SESSION_UPLOAD_FILE = 'invoice_class_upload_file';
    
    // -------------------------------------------------------------------------
    // 全銀データ レコードフォーマット定義
    // -------------------------------------------------------------------------
    private static $LEYOUT = array (
            array (
                    'degit' => 1,
                    'format' => '%d',
                    'default' => 0 
            ), // ダミー
            array (
                    'degit' => 1,
                    'format' => '%d',
                    'default' => 1 
            ), // 1
            array (
                    'degit' => 2,
                    'format' => "%'.02d",
                    'default' => 91 
            ), // 2
            array (
                    'degit' => 1,
                    'format' => '%d',
                    '    default' => 0 
            ), // 3
            array (
                    'degit' => 10,
                    'format' => "%'.010d",
                    'default' => 0 
            ), // 4
            array (
                    'degit' => 20,
                    'format' => "%'.20s",
                    'default' => '' 
            ), // 5
            array (
                    'degit' => 20,
                    'format' => "%'.20s",
                    'default' => '' 
            ), // 6
            array (
                    'degit' => 4,
                    'format' => "%'.04d",
                    'default' => 0 
            ), // 7
            array (
                    'degit' => 4,
                    'format' => "%'.04d",
                    'default' => 0 
            ), // 8
            array (
                    'degit' => 15,
                    'format' => "%'.15s",
                    'default' => '' 
            ), // 9
            array (
                    'degit' => 3,
                    'format' => "%'.03d",
                    'default' => 0 
            ), // 10
            array (
                    'degit' => 15,
                    'format' => "%'.15s",
                    'default' => '' 
            ), // 11
            array (
                    'degit' => 1,
                    'format' => "%d",
                    'default' => 0 
            ), // 12
            array (
                    'degit' => 7,
                    'format' => "%'.07d",
                    'default' => 0 
            ), // 13
            array (
                    'degit' => 17,
                    'format' => "%'.17s",
                    'default' => '' 
            ), // 14
            
            array (
                    'degit' => 1,
                    'format' => '%d',
                    'default' => 2 
            ), // 15
            array (
                    'degit' => 4,
                    'format' => "%'.04d",
                    'default' => 0 
            ), // 16
            array (
                    'degit' => 15,
                    'format' => "%'.15s",
                    'default' => '' 
            ), // 17
            array (
                    'degit' => 3,
                    'format' => "%'.03d",
                    'default' => 0 
            ), // 18
            array (
                    'degit' => 15,
                    'format' => "%'.15s",
                    'default' => '' 
            ), // 19
            array (
                    'degit' => 4,
                    'format' => "%'.4s",
                    'default' => '' 
            ), // 20
            array (
                    'degit' => 1,
                    'format' => "%d",
                    'default' => 0 
            ), // 21
            array (
                    'degit' => 7,
                    'format' => "%'.07d",
                    'default' => 0 
            ), // 22
            array (
                    'degit' => 30,
                    'format' => "%'.30s",
                    'default' => '' 
            ), // 23
            array (
                    'degit' => 10,
                    'format' => "%'.010d",
                    'default' => 0 
            ), // 24
            array (
                    'degit' => 1,
                    'format' => "%d",
                    'default' => 0 
            ), // 25
            array (
                    'degit' => 20,
                    'format' => "%'.020d",
                    'default' => 0 
            ), // 26
            array (
                    'degit' => 1,
                    'format' => "%d",
                    'default' => 0 
            ), // 27
            array (
                    'degit' => 8,
                    'format' => "%'.8s",
                    'default' => '' 
            ), // 28
            
            array (
                    'degit' => 1,
                    'format' => '%d',
                    'default' => 8 
            ), // 29
            array (
                    'degit' => 6,
                    'format' => "%'.06d",
                    'default' => 0 
            ), // 30
            array (
                    'degit' => 12,
                    'format' => "%'.012d",
                    'default' => 0 
            ), // 31
            array (
                    'degit' => 6,
                    'format' => "%'.06d",
                    'default' => 0 
            ), // 32
            array (
                    'degit' => 12,
                    'format' => "%'.012d",
                    'default' => 0 
            ), // 33
            array (
                    'degit' => 6,
                    'format' => "%'.06d",
                    'default' => 0 
            ), // 34
            array (
                    'degit' => 12,
                    'format' => "%'.012d",
                    'default' => 0 
            ), // 35
            array (
                    'degit' => 65,
                    'format' => "%'.65s",
                    'default' => 0 
            ), // 36
            
            array (
                    'degit' => 1,
                    'format' => "%d",
                    'default' => 9 
            ), // 37
            array (
                    'degit' => 119,
                    'format' => "%'.119s",
                    'default' => '' 
            ) 
    ); // 38
       
    // -----------------------------------------------------------------
       // 全銀データ フォーマット
       // ヘッダーレコード : 120byte + 2byte(CR + LF) 1
       // データレコード : 120byte + 2byte(CR + LF) n
       // トレーラーレコード : 120byte + 2byte(CR + LF) 1
       // エンドレコード : 120byte + 2byte(CR + LF) 1
       // -----------------------------------------------------------------
    const HEADER_RECORD_LEN = 122;
    const DATA_RECORD_LEN = 122;
    const TRAILER_RECORD_LEN = 122;
    const END_RECORD_LEN = 122;
    private static $TEMPLATE_URL = 'School.Invoice.Richo';
    protected $_loginAdmin;
    private $invoice_type;
    /*
     * 共通初期化処理
     */
    public function __construct() {
        parent::__construct ();
        if (PschoolTable::getInstance ()->isNormalShibu ( session ( 'school.login' ) ['id'] )) {
            return redirect ( $this->get_app_path () );
        }
        // if business divisions null
        if(session ( 'school.login' ) ['business_divisions'] == null){
        	session ()->put('school.login.business_divisions', 1);
        }
        $auths = AuthConfig::getAuth ( 'school' );
        $edit_auth = parent::setEditAuth ( session ( 'school.login' ) ['edit_authority'] );
        view ()->share ( 'auths', $auths );
        view ()->share ( 'edit_auth', $edit_auth );
        $message_content = parent::getMessageLocale ();
        $lan = new Lang ( $message_content );
        view ()->share ( 'lan', $lan );
        // 支払方法
        view ()->share ( 'invoice_type', ConstantsModel::$invoice_type [session ( 'school.login' ) ['language']] );
        view ()->share ( 'is_established_list', ConstantsModel::$is_established_of_invoice [session ( 'school.login' ) ['language']] );
        // 請求フラグ
        view ()->share ( 'is_requested_list', ConstantsModel::$is_requested_of_invoice [session ( 'school.login' ) ['language']] );
        // 入金フラグ
        view ()->share ( 'is_recieved_list', ConstantsModel::$is_recieved_of_invoice [session ( 'school.login' ) ['language']] );
        // メール通知対象フラグ
        view ()->share ( 'mail_announce_list', ConstantsModel::$mail_announce_list_of_invoice [session ( 'school.login' ) ['language']] );
        
        view ()->share ( 'schoolCategory', ConstantsModel::$dispSchoolCategory );
        // 口座振替
        view ()->share ( 'requesttable_status', ConstantsModel::$requesttable_status [session ( 'school.login' ) ['language']] );
        // 状況
        view ()->share ( 'workflow_status_list', ConstantsModel::$workflow_status [session ( 'school.login' ) ['language']] );
        // 全銀エラーコード
        view ()->share ( 'zengin_status_list', ConstantsModel::$zengin_status [session ( 'school.login' ) ['language']] );
        // 塾id
        view ()->share ( 'pschool_id', session ( 'school.login' ) ['id'] );
        
        for($i = 0; $i < 12; $i ++) {
            $select_year_month_list [$i] = date ( "Y-m", strtotime ( "+" . $i . " month" ) );
        }
        view ()->share ( 'select_year_month', $select_year_month_list [0] );
        view ()->share ( 'select_year_month_list', $select_year_month_list );
        
        $withdrawal_day = session ( 'school.login' ) ['withdrawal_day'];
        view ()->share ( 'withdrawal_day', $withdrawal_day );
        
        // -----------------------
        // リスト類の表示情報設定
        // -----------------------
        
        // 割引・割増
        $parents = array ();
        $parent_ids = HierarchyTable::getInstance ()->getParentPschoolIds ( session ( 'school.login' ) ['id'] );
        $parent_ids [] = session ( 'school.login' ) ['id'];
        $adjust_list = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ( $parent_ids );
        $discount_names = array ();
        $discount_valus = array ();
        $adjust_names = array ();
        $adjust_valus = array ();
        foreach ( $adjust_list as $key => $value ) {
            $adjust_names [$value ['id']] = $value ['name'];
            if (empty ( $value ['type'] )) {
                $adjust_valus [$value ['id']] = $value ['initial_fee'];
            } else {
                $adjust_valus [$value ['id']] = $value ['initial_fee'];
                $discount_names [$value ['id']] = $value ['name'];
                $discount_valus [$value ['id']] = $value ['initial_fee'] * - 1;
            }
        }
        
        // dd($adjust_names);
        view ()->share ( 'discount_names', $discount_names );
        view ()->share ( 'discount_valus', $discount_valus );
        view ()->share ( 'adjust_names', $adjust_names );
        view ()->share ( 'adjust_valus', $adjust_valus );
        // 年
        $year_list = array ();
        $start_year = 2014;
        $last_year = 2023;
        for($i = $start_year; $i <= $last_year; $i ++) {
            $year_list [$i] = $i;
        }
        // 月
        $month_list = array ();
        $start_month = 1;
        $last_month = 12;
        for($i = $start_month; $i <= $last_month; $i ++) {
            $month_list [$i] = $i;
        }
        
        view ()->share ( 'year_list', $year_list );
        view ()->share ( 'month_list', $month_list );
        
        // 請求年リスト
        $invoice_year_list = array ();
        $start_year = 2014;
        $last_year = 2023;
        for($i = $start_year; $i <= $last_year; $i ++) {
            $invoice_year_list [$i] = $i;
        }
        view ()->share ( 'invoice_year_list', $invoice_year_list );
        
        // プラン
        $class_list = ClassTable::getInstance ()->getList ( array (
                'pschool_id' => session ( 'school.login' ) ['id'],
                'active_flag' => 1,
                'delete_date is null' 
        ), array (
                'class_name' => 'ASC' 
        ) );
        $disp_class_list = array ();
        if (! empty ( $class_list )) {
            foreach ( $class_list as $idex => $row ) {
                $disp_class_list [$row ['id']] = $row ['class_name'];
            }
        }
        view ()->share ( 'class_list', $disp_class_list );
        // イベント
        $course_list = CourseTable::getInstance ()->getList ( array (
                'pschool_id' => session ( 'school.login' ) ['id'],
                'active_flag' => 1,
                'delete_date is null' 
        ), array (
                'course_title' => 'ASC' 
        ) );
        
        $disp_course_list = array ();
        if (! empty ( $course_list )) {
            foreach ( $course_list as $idex => $row ) {
                $disp_course_list [$row ['id']] = $row ['course_title'];
            }
        }
        view ()->share ( 'course_list', $disp_course_list );
        
        // 編集フラグ
        $this->_loginAdmin = parent::getLoginAdmin ();
        
        if (session ( self::SESSION_HISTORY_LIST ) == null) {
            // session(self::SESSION_HISTORY_LIST ) = array ();
            $_SESSION [self::SESSION_HISTORY_LIST] = array ();
        }
        
        // メッセージ
        if (session ( self::SESSION_MESSAGE ) !== null && ! empty ( session ( self::SESSION_MESSAGE ) )) {
            view ()->share ( 'action_status', session ( self::SESSION_MESSAGE ) ["action_status"] );
            view ()->share ( 'action_message', session ( self::SESSION_MESSAGE ) ["action_message"] );
            session()->forget ( self::SESSION_MESSAGE );
        }
        // 最新作成済み請求書年月取得
        $max_invoice_year_month = InvoiceHeaderTable::getInstance ()->getMaxInvoceDate ( session ( 'school.login' ) );
    }
    private function setSimpleCond(Request $request) {
        // 簡易検索
        if (isset ( $request ['invoice_year_from_s'] )) {
            $request ['invoice_year_from'] = $request ['invoice_year_from_s'];
        }
        if (isset ( $request ['invoice_month_from_s'] )) {
            $request ['invoice_month_from'] = $request ['invoice_month_from_s'];
        }
        if (isset ( $request ['invoice_year_to_s'] )) {
            $request ['invoice_year_to'] = $request ['invoice_year_to_s'];
        }
        if (isset ( $request ['invoice_month_to_s'] )) {
            $request ['invoice_month_to'] = $request ['invoice_month_to_s'];
        }
        
        for($idx = 0; $idx < 4; $idx ++) {
            $item_name1 = 'invoice_type';
            $item_name2 = 'invoice_type_s';
            if (isset ( $request [$item_name2] [$idx] )) {
                $request [$item_name1 . $idx] = $request [$item_name2] [$idx];
                $request [$item_name1] [$idx] = $request [$item_name2] [$idx];
            } else {
                $request [$item_name1 . $idx] = 0;
            }
        }
        for($idx = 0; $idx < 2; $idx ++) {
            $item_name1 = 'paid_type';
            $item_name2 = 'paid_type_s';
            if (isset ( $request [$item_name2] [$idx] )) {
                $request [$item_name1 . $idx] = $request [$item_name2] [$idx];
                $request [$item_name1] [$idx] = $request [$item_name2] [$idx];
            } else {
                $request [$item_name1 . $idx] = 0;
            }
        }
        if (isset ( $request ['inactive_flag_s'] ) && ! empty ( $request ['inactive_flag_s'] )) {
            $request ['inactive_flag'] = 1;
        }
    }
    private function setDetailCond(Request $request) {
        $request ['invoice_year_from'] = $request ['invoice_year_from_d'];
        $request ['invoice_month_from'] = $request ['invoice_month_from_d'];
        $request ['invoice_year_to'] = $request ['invoice_year_to_d'];
        $request ['invoice_month_to'] = $request ['invoice_month_to_d'];
        
        for($idx = 0; $idx < 4; $idx ++) {
            $item_name1 = 'invoice_type';
            $item_name2 = 'invoice_type_d';
            if (isset ( $request [$item_name2] [$idx] )) {
                $request [$item_name1 . $idx] = $request [$item_name2] [$idx];
                $request [$item_name1] [$idx] = $request [$item_name2] [$idx];
            } else {
                $request [$item_name1 . $idx] = 0;
            }
        }
        for($idx = 0; $idx < 2; $idx ++) {
            $item_name1 = 'paid_type';
            $item_name2 = 'paid_type_d';
            if (isset ( $request [$item_name2] [$idx] )) {
                $request [$item_name1 . $idx] = $request [$item_name2] [$idx];
                $request [$item_name1] [$idx] = $request [$item_name2] [$idx];
            } else {
                $request [$item_name1 . $idx] = 0;
            }
        }
        if (isset ( $request ['inactive_flag_d'] ) && ! empty ( $request ['inactive_flag_d'] )) {
            $request ['inactive_flag'] = 1;
        }
    }
    public function execute(Request $request) {
        // パンくず　クリア
        $this->set_menu_no ( 9 );
        $this->clear_bread_list ();
        
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 運用区分が塾の場合
            return $this->executeSearch ( $request );
        } else {
            // 運用区分が塾以外の場合
            return $this->executeList ( $request );
        }
    }
    public function executeList(Request $request) {
        // パンくず
        $this->set_bread_list ( self::$ACTION_URL, ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
        $this->set_history ( 0, self::$ACTION_URL . '/list?back' );
        
        // メニュー制御
//         $this_screen = 'list';
        view()->share('this_screen', 'list');
        
        // 請求書
        $invoice_list = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
        
        // return $this->convertSmartyPath(self::$TEMPLATE_URL.'list.html');
        return view ( self::$TEMPLATE_URL . '.list', compact ('invoice_list' ) );
    }
    
    /*
     * 検索画面
     */
    public function executeSearch(Request $request) {

//         if (empty ( $request ) || $request->exists("menu") || count ( $request ) == 1) {
        if (empty ( $request ) || $request->exists("menu")) {
            // 初期表示の場合
            $is_menu = true;
            for($idx = 0; $idx < 4; $idx ++) {
                $item_name = 'invoice_type' . $idx;
                $request [$item_name] = 1;
            }
            for($idx = 0; $idx < 2; $idx ++) {
                $item_name = 'paid_type' . $idx;
                $request [$item_name] = 1;
            }
            
            // 最新の請求書の年月取得
            $invoice_year_month = InvoiceHeaderTable::getInstance ()->getNewestYearMonth ( session ( 'school.login' ) ['id'] );
            $request ['invoice_year_to'] = substr ( $invoice_year_month ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $invoice_year_month ['invoice_year_month'], 5, 2 );
            $request ['search_cond'] = 2;
        } 
        // when back search
        else if (isset ( $request ["back"] ) && (session ( self::SESSION_SEARCH_COND ) !== null)) {
            $request = session ()->get ( self::SESSION_SEARCH_COND );
        } else if (isset ( $request ["new"] ) && isset ( $request ['invoice_year_month'] )) {
           
            $request ['invoice_year_from'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_from'] = substr ( $request ['invoice_year_month'], 5, 2 );
            $request ['invoice_year_to'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $request ['invoice_year_month'], 5, 2 );
            $request ['search_cond'] = 2;
        } else if (isset ( $request ["simple"] )) {
            // 簡易検索
            $this->setSimpleCond ( $request );
            $is_simple = true;
            $request ['search_cond'] = 2;
        } else {
            // 詳細検索
            $this->setDetailCond ( $request );
            $request ['search_cond'] = 1;
        }
        
//         $this_screen = 'search';
        view()->share('this_screen', 'search');
        $invoice_list = null;
        
        $invoice_list = InvoiceHeaderTable::getInstance ()->getListInclDisabledBySearch2 ( session ( 'school.login' ) ['id'], $request );

        $this->set_list_info ( $invoice_list );
        // $this->assignVars('invoice_list', $invoice_list);
        
        // session ()->put ( self::SESSION_SEARCH_COND, $request );
        // $_SESSION [self::SESSION_SEARCH_COND] = $request;
        
        $_SESSION [self::SESSION_SEARCH_COND] = $request;
//         session ()->put ( self::SESSION_SEARCH_COND, $request );
        // ---------------------------------------------------------------------
        // 一括請求項目追加
        // ---------------------------------------------------------------------
        // 学校区分
        $school_category = ConstantsModel::$dispSchoolCategory;
        // 摘要
        $parent_ids = HierarchyTable::getInstance ()->getParentPschoolIds ( session ( 'school.login' ) ['id'] );
        $parent_ids [] = session ( 'school.login' ) ['id'];
        $invoice_adjust = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ( $parent_ids );
        
        $heads = array ();
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 運用区分が塾の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_history ( 0, self::$ACTION_URL . "?back" );
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            
            // 運用区分が塾以外の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '/list?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
            $this->set_bread_list ( self::$ACTION_URL . '/search?simple&search_cond=2&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to_s=' . $heads [0] ['invoice_year'] . '&invoice_month_to_s=' . $heads [0] ['invoice_month'] . '&invoice_year_from_s=' . $heads [0] ['invoice_year'] . '&invoice_month_from_s=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_history ( 0, self::$ACTION_URL . '/search?simple&search_cond=2&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to_s=' . $heads [0] ['invoice_year'] . '&invoice_month_to_s=' . $heads [0] ['invoice_month'] . '&invoice_year_from_s=' . $heads [0] ['invoice_year'] . '&invoice_month_from_s=' . $heads [0] ['invoice_month'] );
        }
        // ---------------------------------------------------------------------
        // 口座振替対象のデータが作成されているかチェック
        // ---------------------------------------------------------------------
        $operation = 0;
        $invoice_head = InvoiceHeaderTable::getInstance ()->getList ( array (
                'pschool_id' => session ( 'school.login' ) ['id'],
                'invoice_year_month' => $heads [0] ['invoice_year_month'],
                'workflow_status > 1',
                'delete_date IS NULL' 
        ) );
        if (! empty ( $invoice_head ) && count ( $invoice_head ) > 0) {
            $operation = 1;
        }
        $invoice_transfer_operation = $operation;
        $heads = $heads [0];
        // return $this->convertSmartyPath(self::$TEMPLATE_URL.'top.html');
        return view ( self::$TEMPLATE_URL . '.top', compact ( 'request', 'withdrawal_day', 'invoice_list', 'school_category', 'invoice_adjust', 'heads', 'invoice_transfer_operation' ) );
    }
    
    /**
     * 請求書一括作成画面
     *
     * @return boolean
     */
    public function executeGenerate(Request $request) {
//         dd($request);
        if (! isset ( $request ['invoice_year_month'] )) {
            // 引数がない
            return false;
        }
        
        $invoice_year_month = $request ['invoice_year_month']; // 'yyyy-mm'形式
                                                               
        // ---------------------------------------------------------------------
                                                               // 支払期限日
                                                               // ---------------------------------------------------------------------
        $pschool = PschoolTable::getInstance ()->load ( session ( 'school.login' ) ['id'] );
        $withdrawal_day = $pschool ['withdrawal_day'];
        
        if ($withdrawal_day > 0) {
            
            $bank_year_month = $invoice_year_month;
            // 口座振替の場合
            if ($pschool ['payment_style'] == 1) {
                // 先払い 前月
                $bank_year_month = date ( 'Y-m', strtotime ( $bank_year_month . "-01" . "-1 month" ) );
            }
            
            $closeDay = ClosingDayTable::getInstance ()->getRow ( array (
                    'transfer_month' => $bank_year_month,
                    'transfer_day' => $withdrawal_day,
                    'delete_date IS NULL' 
            ) );
            
            $due_date_bank = $closeDay ['payment_date'];
        }
//         dd($due_date_bank);
        $due_date = $this->getInvoiceDueDate2 ( $invoice_year_month, $pschool );
//         dd($due_date);
        // ---------------------------------------------------------------------
        // 書き込み
        // ---------------------------------------------------------------------
        try {
            InvoiceHeaderTable::getInstance ()->beginTransaction ();
            
            // 請求書作成
            $generate_count = InvoiceHeaderTable::getInstance ()->generateInvoiceByYearMonth ( session ( 'school.login' ) ['id'], session ( 'school.login' ) ['login_account_id'], $this->_loginAdmin ["amount_display_type"], $this->_loginAdmin ["sales_tax_rate"], $invoice_year_month, $due_date, $due_date_bank, $withdrawal_day );
//             dd($generate_count);
            InvoiceHeaderTable::getInstance ()->commit ();
            
            if ($generate_count == 0) {
                $this->set_message ( "OK", ConstantsModel::$invoice_message [$this->current_lang] ['invoice_cannot_created'] );
            } else {
                $this->set_message ( "OK", sprintf ( ConstantsModel::$invoice_message [$this->current_lang] ['invoice_item_created'], $generate_count ) );
            }
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            $this->set_message ( "NG", ConstantsModel::$errors [$this->current_lang] ['process_invoice_error_message'] );
        }
        
        // 請求書検索画面に遷移する。
//         return back ()->withInput ();
            
//         $this->redirect_history(0);
        $level = 0;
        if (session()->exists(self::SESSION_HISTORY_LIST )) {
            if (is_null ( $level )) {
                $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
            }
            return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
        } else {
            // 戻り先が無いので、TOPに戻す。
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    
    /*
     * 保護者選択ダイアログ
     */
    public function executeParentSelect(Request $request) {
        $parent_list = ParentTable::getInstance ()->getParentListAndStudentListById ( session ( 'school.login' ) ['id'] );
        foreach ( $parent_list as $key => $parent ) {
            if (empty ( $parent ['student_list'] ) || ! empty ( $parent ['delete_date'] )) {
                array_forget( $parent_list,$key);
            }
        }
        // $this->assignVars ( 'parent_list', $parent_list );
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'parent_select.html' );
        return view ( self::$TEMPLATE_URL . '.parent_select', compact ( 'parent_list', 'request' ) );
    }
    
    /*
     * 請求書確認画面
     */
    public function executeDetail(Request $request) {
        if (isset ( $request ['pre'] )) {
            $request ['id'] = $this->get_pre_id ();
        } elseif (isset ( $request ['next'] )) {
            $request ['id'] = $this->get_next_id ();
        } else {
            $this->set_disp_id ( $request ['id'] );
        }
        
        $header = $this->checkEditParam ( $request );
        
        if (empty ( $header ["due_date"] )) {
            // 支払い期限のnull対策
            $due_dates = $header ['register_date'];
            if (date ( 'j', strtotime ( $due_dates ) ) > session ( 'school.login' ) ['payment_date']) {
                $due_dates = date ( 'Y-m-t', strtotime ( $due_dates . " next month" ) );
            }
            
            if (session ( 'school.login' ) ['payment_date'] == 99) {
                $due_dates = date ( 'Y-m-t', strtotime ( $due_dates ) );
            } else {
                $due_dates = date ( 'Y-m-', strtotime ( $due_dates ) ) . str_pad ( session ( 'school.login' ) ['payment_date'], 2, 0, STR_PAD_LEFT );
            }
            $header ["due_date"] = $due_dates;
        }
        
        $parentStudent = InvoiceHeaderTable::getInstance ()->getListBySearch ( session ( 'school.login' ) ['id'], array (
                'xxx_id' => $request ['id'] 
        ) );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
//         dd($parentStudent);
        $this->setFormDataFromDb ( $request, $header, $parentStudent );
        $data = $parentStudent;
        
        $first = "";
        $last = "";
        if ($this->is_first_id () == true) {
            $first = '1';
        }
        if ($this->is_last_id ()) {
            $last = '1';
        }
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 運用区分が塾の場合
            if (! isset ( $request ['pre'] ) && ! isset ( $request ['next'] )) {
                $this->set_bread_list ( self::$ACTION_URL . "/detail?id=" . $header ["id"], ConstantsModel::$bread_list [$this->current_lang] ['invoice_confirm_screen'] );
                $this->set_history ( 1, self::$ACTION_URL . "/detail?id=" . $header ["id"] );
            } else {
                $this->disp_bread_list ();
            }
        } else {
            // メニュー制御
//             $this_screen = 'detail';
            view()->share('this_screen', 'detail');
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
            
            // 運用区分が塾以外の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '/list?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
            // $this->set_bread_list ( self::$ACTION_URL . '/search?simple&search_cond=2&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to_s=' . $heads [0] ['invoice_year'] . '&invoice_month_to_s=' . $heads [0] ['invoice_month'] . '&invoice_year_from_s=' . $heads [0] ['invoice_year'] . '&invoice_month_from_s=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [1] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . "/detail?id=" . $header ["id"], ConstantsModel::$bread_list [$this->current_lang] ['invoice_confirm_screen'] );
            $this->set_history ( 0, self::$ACTION_URL . "/detail?id=" . $header ["id"] );
        }
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'detail.html' );
        return view ( self::$TEMPLATE_URL . '.detail', compact ( 'data', 'request', 'first', 'last', 'heads' ) );
    }
    
    /**
     * 請求書新規作成画面 個別作成
     *
     * @return boolean
     */
    public function executeEntry(Request $request) {
//     	dd($request);
        $is_new = true;
        view()->share('is_new', $is_new);
//         $this_screen = 'entry';
        view()->share('this_screen', 'entry');
        $this->set_bread_list ( self::$ACTION_URL . "/entry?back", ConstantsModel::$bread_list [$this->current_lang] ['invoice_edit_screen'] );
        
        $is_init = true;
        if (isset ( $request ["back"] )) {
            if (session ( self::SESSION_INPUT_DATA ) !== null) {
                $request = session ( self::SESSION_INPUT_DATA );
                $is_init = false;
            }
        } else if (isset ( $request ['tab_change'] )) {
            $is_init = false;
        }
        
        // 請求先取得
        $parent = ParentTable::getInstance ()->load ( $request ['parent_id'] );
        if (empty ( $parent )) {
            return false;
        }
        
        // ---------------------------------------------------------------------
        // 現在の日付で作成できるのは、何月分？ $target_month
        // ---------------------------------------------------------------------
        // 塾の情報取得
        $pschool_data = PschoolTable::getInstance ()->load ( session ( 'school.login' ) ['id'] );
        // 塾締日
        // $due_day = $pschool_data['due_date'];
        $close_day = $pschool_data ['invoice_closing_date'];
        // 先払い／後払い
        $pay_style = $pschool_data ['payment_style'];
        // 口座引落日
        $withdrawal_day = $pschool_data ['withdrawal_day'];
        
        // 現在日付取得
        $curr_date = date ( 'Y-m-d' );
        $curr_day = date ( 'd' );
        
        $target_month = $request ['invoice_year_month'];
        // ---------------------------------------------------------------------
        // 支払期限日
        // ---------------------------------------------------------------------
        if ($withdrawal_day > 0) {
            
            if ($pay_style == 1) {
                // 先払いの場合、翌月
                $target_date = $target_month . "-01";
            } else {
                // 後払い
                $target_date = $target_month . "-01";
                $last_date = date ( 't', $target_date );
                $target_date = $year_month . "-" . $last_date;
            }
            // 同じ口座振替日
            $close_days = ClosingDayTable::getInstance ()->getList ( array (
                    'transfer_day' => $withdrawal_day 
            ), array (
                    'transfer_month' => 'ASC' 
            ) );
            $target_id = $close_days [0] ['id'];
            $near_date = date ( 'U', strtotime ( $close_days [0] ['transfer_date'] ) );
            $base_date = date ( 'U', strtotime ( $target_date ) );
            $due_date_bank = $close_days [0] ['payment_date'];
            
            foreach ( $close_days as $close_item ) {
                $temp_date = date ( 'U', strtotime ( $close_item ['transfer_date'] ) );
                if (abs ( $base_date - $temp_date ) < abs ( $base_date - $near_date )) {
                    // 基準となる日付に近いもの
                    $target_id = $close_item ['id'];
                    $near_date = $temp_date;
                    $due_date_bank = $close_item ['payment_date'];
                }
            }
        }
        $due_date_cash = $this->getInvoiceDueDate2 ( $target_month, $pschool_data );
        
        // ---------------------------------------------------------------------
        // 書き込み
        // ---------------------------------------------------------------------
        $target_month = ! isset ( $request ["invoice_year_month"] ) ? $target_month : $request ["invoice_year_month"];
        $request ["invoice_year_month"] = $target_month;
        
        // $invoice_year = !isset($request["invoice_year"]) ? date('Y') : $request["invoice_year"];
        $invoice_year = ! isset ( $request ["invoice_year"] ) ? substr ( $target_month, 0, 4 ) : $request ["invoice_year"];
        // $invoice_month = !isset($request["invoice_month"]) ? date('n') : $request["invoice_month"];
        $invoice_month = ! isset ( $request ["invoice_month"] ) ? substr ( $target_month, 5, 2 ) : $request ["invoice_month"];
        $request ["invoice_year"] = $invoice_year;
        $request ["invoice_month"] = $invoice_month;
        $due_date = array ();
        $due_date = $this->getInvoiceDueDate ( $this->_loginAdmin ['due_date'] );
        
        if (! empty ( $withdrawal_day ) && $parent ['invoice_type'] == 2) {
            $request ["due_date"] = $due_date_bank;
            $split = explode ( "-", $due_date_bank );
        } else {
            // $request["due_date"] = sprintf('%04d', $due_date['year'])."-".sprintf('%02d', $due_date['month'])."-".sprintf('%02d', $due_date['day']);
            $request ["due_date"] = $due_date_cash;
            $split = explode ( "-", $due_date_cash );
        }
        $request ["invoice_due_year"] = $split [0];
        $request ["invoice_due_month"] = $split [1];
        $request ["invoice_due_day"] = $split [2];
        
        $request ["now_date_jp"] = $this->convJpDate ( date ( 'Y-m-d' ) );
        $request ["due_date_jp"] = $this->convJpDate ( $request ["due_date"] );
        $request ["sales_tax_disp"] = session ( 'school.login' ) ['sales_tax_rate'] * 100;
        
        $pbank = PschoolBankAccountTable::getInstance ()->getActiveRow ( $where = array (
                'pschool_id' => session ( 'school.login' ) ['id'] 
        ) );
        if (! empty ( $pbank ) && $pbank ['bank_type'] == 1) {
            $bank_type = ($pbank ['bank_account_type'] == 1) ? ConstantsModel::$type_of_bank_account [1] ['1'] : ConstantsModel::$type_of_bank_account [1] ['2'];
            $request ["pbank_info"] = $pbank ['bank_name'] . " " . $pbank ['branch_name'] . " " . $bank_type . " " . $pbank ['bank_account_number'] . " " . $pbank ['bank_account_name_kana'];
        } elseif (! empty ( $pbank )) {
            $request ["pbank_info"] = ConstantsModel::$form_keys [1] ['jp_post_bank'] . " " . $pbank ['post_account_kigou'] . " " . $pbank ['post_account_number'] . " " . $pbank ['post_account_name'];
        }
        
        $parentStudent = $this->checkEntryParam ( $request );
        $request ["parent_memo"] = $parentStudent ["memo"];
        
        $adjust_student_id = 0;
        
        $amount = 0;
        $sum_discount_price = 0;
        
        if ($is_init) {
            $request ['mail_announce'] = empty ( $parentStudent ['mail_infomation'] ) ? "0" : "1";
            $request ["amount_display_type"] = $this->_loginAdmin ["amount_display_type"];
            $request ["sales_tax_rate"] = $this->_loginAdmin ["sales_tax_rate"];
            
            // 割引
            
            $request ["active_student_id"] = $parentStudent ["student_list"] [0] ["id"];
            // プラン
            $arr ["class_id"] = array ();
            $arr ["class_name"] = array ();
            $arr ["class_price"] = array ();
            $arr ["class_except"] = array ();
            $arr ["_class_except"] = array ();
            // イベント
            $arr ["course_id"] = array ();
            $arr ["course_name"] = array ();
            $arr ["cource_price"] = array ();
            $arr ["cource_except"] = array ();
            $arr ["_cource_except"] = array ();
            // プログラム
            $arr ["program_id"] = array ();
            $arr ["program_name"] = array ();
            $arr ["program_price"] = array ();
            $arr ["program_except"] = array ();
            $arr ["_program_except"] = array ();
            // 割増
            $arr ["custom_item_id"] = array ();
            $arr ["custom_item_name"] = array ();
            $arr ["custom_item_price"] = array ();
            foreach ( $parentStudent ["student_list"] as $k => $v ) {
                // プラン
                $arr ["class_id"] [$v ["id"]] = array ();
                $arr ["class_name"] [$v ["id"]] = array ();
                $arr ["class_price"] [$v ["id"]] = array ();
                $arr ["class_except"] [$v ["id"]] = array ();
                $arr ["_class_except"] [$v ["id"]] = array ();
                // イベント
                $arr ["course_id"] [$v ["id"]] = array ();
                $arr ["course_name"] [$v ["id"]] = array ();
                $arr ["cource_price"] [$v ["id"]] = array ();
                $arr ["cource_except"] [$v ["id"]] = array ();
                $arr ["_cource_except"] [$v ["id"]] = array ();
                // プログラム
                $arr ["program_id"] [$v ["id"]] = array ();
                $arr ["program_name"] [$v ["id"]] = array ();
                $arr ["program_price"] [$v ["id"]] = array ();
                $arr ["program_except"] [$v ["id"]] = array ();
                $arr ["_program_except"] [$v ["id"]] = array ();
                // 割増
                // 保護者の割増を設定する場所がないので。
                if ($adjust_student_id == 0)
                    $adjust_student_id = $v ["id"];
            }
            
            $invoice_year_month = "";
            if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                if (session ( 'school.login' ) ['country_code'] == 81) {
                    $split = explode ( '-', $target_month );
                    $invoice_year_month = $split [0] . "年" . $split [1] . "月分 ";
                } else {
                    $invoice_year_month = $target_month . " ";
                }
            }
            
//             dd(session ( 'school.login' ) ['business_divisions']);
            // =================================================================
            // プラン
            // =================================================================
            // 受講料
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // -------------------------------------------------------------
                $class_item = InvoiceItemTable::getInstance ()->getClassItem ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                // -------------------------------------------------------------
                // 運用区分が会員クラブの場合
                // -------------------------------------------------------------
                $class_item = InvoiceItemTable::getInstance ()->getClassItem_Axis ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            }
            foreach ( $class_item as $k => $v ) {
                $arr ["class_id"] [$v ["id"]] [] = $v ["class_id"];
                if (isset ( $v ['student_name'] )) {
                    $arr ["class_name"] [$v ["id"]] [] = $invoice_year_month . $v ["class_name"] . " " . $v ['student_name'];
                } else {
                    $arr ["class_name"] [$v ["id"]] [] = $invoice_year_month . $v ["class_name"];
                }
                // プラン受講料の取得と設定
                // $request["class_price"][$v["id"]][] = $this->getClassPrice($v["class_id"], $v["student_type"]);
                $arr ["class_price"] [$v ["id"]] [] = $v ["class_fee"];
                $arr ["class_except"] [$v ["id"]] [] = 0;
                $arr ["_class_except"] [$v ["id"]] [] = 0;
                // $amount += $v["class_fee"];
            }
            // 受講料以外
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // -------------------------------------------------------------
                $class_adjust = ClassTable::getInstance ()->getClassAdjustList ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                // -------------------------------------------------------------
                // 運用区分が会員クラブの場合
                // -------------------------------------------------------------
                $class_adjust = ClassTable::getInstance ()->getClassAdjustList_Axis ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            }
            foreach ( $class_adjust as $k => $v ) {
                /*
                 * if( $v["adjust_fee"] >= 0 ){
                 * $request["custom_item_id"][$v["student_id"]][] = $v["invoice_adjust_name_id"];
                 * $request["custom_item_name"][$v["student_id"]][] = $v["name"];
                 * $request["custom_item_price"][$v["student_id"]][] = $v["adjust_fee"];
                 * } else {
                 * $request["discount_id"][] = $v["invoice_adjust_name_id"];
                 * $request["discount_name"][] = $v["name"];
                 * $request["discount_price"][] = $v["adjust_fee"] * -1;
                 * }
                 */
                $arr ["class_id"] [$v ["student_id"]] [] = $v ["class_id"];
                if (isset ( $v ['student_name'] )) {
                    $arr ["class_name"] [$v ["student_id"]] [] = $invoice_year_month . $v ["class_name"] . " " . $v ['student_name'] . " " . "(" . $v ['name'] . ")";
                } else {
                    $arr ["class_name"] [$v ["student_id"]] [] = $invoice_year_month . $v ["class_name"] . " " . $v ['name'];
                }
                $arr ["class_price"] [$v ["student_id"]] [] = $v ['adjust_fee'];
                $arr ["class_except"] [$v ["student_id"]] [] = 0;
                $arr ["_class_except"] [$v ["student_id"]] [] = 0;
            }
            
            // =================================================================
            // イベント
            // =================================================================
            // 受講料
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // -------------------------------------------------------------
                $course_item = InvoiceItemTable::getInstance ()->getCourseItem ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                // -------------------------------------------------------------
                // 運用区分が会員クラブの場合
                // -------------------------------------------------------------
                $course_item = InvoiceItemTable::getInstance ()->getCourseItem_Axis ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            }
            foreach ( $course_item as $k => $v ) {
                $arr ["course_id"] [$v ["id"]] [] = $v ["course_id"];
                if (isset ( $v ['student_name'] )) {
                    $arr ["course_name"] [$v ["id"]] [] = $invoice_year_month . $v ["course_title"] . " " . $v ['student_name'];
                } else {
                    $arr ["course_name"] [$v ["id"]] [] = $invoice_year_month . $v ["course_title"];
                }
                // イベント受講料の取得と設定
                // $request["course_price"][$v["id"]][] = $this->getCoursePrice($v["course_id"], $v["student_type"]);
                $arr ["course_price"] [$v ["id"]] [] = $v ["course_fee"];
                $arr ["course_except"] [$v ["id"]] [] = 0;
                $arr ["_course_except"] [$v ["id"]] [] = 0;
            }
            // 受講料以外
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // -------------------------------------------------------------
                $course_adjust = CourseTable::getInstance ()->getCourseAdjustList ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                // -------------------------------------------------------------
                // 運用区分が会員クラブの場合
                // -------------------------------------------------------------
                $course_adjust = CourseTable::getInstance ()->getCourseAdjustList_Axis ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            }
            foreach ( $course_adjust as $k => $v ) {
                /*
                 * if( $v["adjust_fee"] >= 0 ){
                 * $request["custom_item_id"][$v["student_id"]][] = $v["invoice_adjust_name_id"];
                 * $request["custom_item_name"][$v["student_id"]][] = $v["name"];
                 * $request["custom_item_price"][$v["student_id"]][] = $v["adjust_fee"];
                 * } else {
                 * $request["discount_id"][] = $v["invoice_adjust_name_id"];
                 * $request["discount_name"][] = $v["name"];
                 * $request["discount_price"][] = $v["adjust_fee"] * -1;
                 * }
                 */
                $arr ["course_id"] [$v ["student_id"]] [] = $v ["course_id"];
                if (isset ( $v ['student_name'] )) {
                    $arr ["course_name"] [$v ["student_id"]] [] = $invoice_year_month . $v ["course_title"] . " " . $v ['student_name'] . " " . "(" . $v ['name'] . ")";
                } else {
                    $arr ["course_name"] [$v ["student_id"]] [] = $invoice_year_month . $v ["course_title"];
                }
                // イベント受講料の取得と設定
                $arr ["course_price"] [$v ["student_id"]] [] = $v ["course_fee"];
                $arr ["course_except"] [$v ["student_id"]] [] = 0;
                $arr ["_course_except"] [$v ["student_id"]] [] = 0;
            }
            
            // =================================================================
            // プログラム
            // =================================================================
            // 受講料
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // -------------------------------------------------------------
                $program_item = array ();
            } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                // -------------------------------------------------------------
                // 運用区分が会員クラブの場合
                // -------------------------------------------------------------
                $program_item = InvoiceItemTable::getInstance ()->getProgramItem_Axis ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            }
            foreach ( $program_item as $k => $v ) {
                $arr ["program_id"] [$v ["id"]] [] = $v ["program_id"];
                if (isset ( $v ['student_name'] )) {
                    $arr ["program_name"] [$v ["id"]] [] = $invoice_year_month . $v ["program_name"] . " " . $v ['student_name'];
                } else {
                    $arr ["program_name"] [$v ["id"]] [] = $invoice_year_month . $v ["program_name"];
                }
                $arr ["program_price"] [$v ["id"]] [] = $v ["program_fee"];
                $arr ["program_except"] [$v ["id"]] [] = 0;
                $arr ["_program_except"] [$v ["id"]] [] = 0;
            }
            // 受講料以外
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // -------------------------------------------------------------
                $program_adjust = array ();
            } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                // -------------------------------------------------------------
                // 運用区分が会員クラブの場合
                // -------------------------------------------------------------
                $program_adjust = ProgramTable::getInstance ()->getProgramAdjustList_Axis ( session ( 'school.login' ) ['id'], $request ["parent_id"], $target_month );
            }
            foreach ( $program_adjust as $k => $v ) {
                /*
                 * if( $v["adjust_fee"] >= 0 ){
                 * $request["custom_item_id"][$v["student_id"]][] = $v["invoice_adjust_name_id"];
                 * $request["custom_item_name"][$v["student_id"]][] = $v["name"];
                 * $request["custom_item_price"][$v["student_id"]][] = $v["adjust_fee"];
                 * } else {
                 * $request["discount_id"][] = $v["invoice_adjust_name_id"];
                 * $request["discount_name"][] = $v["name"];
                 * $request["discount_price"][] = $v["adjust_fee"] * -1;
                 * }
                 *
                 */
                $arr ["program_id"] [$v ["student_id"]] [] = $v ["program_id"];
                if (isset ( $v ['student_name'] )) {
                    $arr ["program_name"] [$v ["student_id"]] [] = $invoice_year_month . $v ["program_name"] . " " . $v ['student_name'] . " " . "(" . $v ['name'] . ")";
                } else {
                    $arr ["program_name"] [$v ["student_id"]] [] = $invoice_year_month . $v ["program_name"];
                }
                $arr ["program_price"] [$v ["student_id"]] [] = $v ["program_fee"];
                $arr ["program_except"] [$v ["student_id"]] [] = 0;
                $arr ["_program_except"] [$v ["student_id"]] [] = 0;
                $amount += $v ["adjust_fee"];
            }
            
            // =================================================================
            // 保護者固有
            // =================================================================
            $parent_adjust = ParentTable::getInstance ()->getParentAdjustList ( session ( 'school.login' ) ['id'], $request ["parent_id"], $invoice_month );
            foreach ( $parent_adjust as $k => $v ) {
                if ($v ["adjust_fee"] >= 0) {
                    $arr ["custom_item_id"] [$adjust_student_id] [] = $v ["invoice_adjust_name_id"];
                    $arr ["custom_item_name"] [$adjust_student_id] [] = $v ["name"];
                    $arr ["custom_item_price"] [$adjust_student_id] [] = $v ["adjust_fee"];
                    $sum_discount_price += $v ["adjust_fee"];
                    ;
                } else {
                    $arr ["discount_id"] [] = $v ["invoice_adjust_name_id"];
                    $arr ["discount_name"] [] = $v ["name"];
                    $arr ["discount_price"] [] = $v ["adjust_fee"] * - 1;
                    $sum_discount_price += $v ["adjust_fee"] * - 1;
                }
            }
        }
        
        if (! isset ( $request ["discount_id"] )) {
            // 割引
            $arr ["discount_id"] = array (
                    "" 
            );
            $arr ["discount_name"] = array (
                    "" 
            );
            $arr ["discount_price"] = array (
                    "" 
            );
        }
        
        foreach ( $parentStudent ["student_list"] as $k => $v ) {
            if (! isset ( $arr ["custom_item_id"] [$v ["id"]] )) {
                $arr ["custom_item_id"] [$v ["id"]] = array (
                        "" 
                );
                $arr ["custom_item_name"] [$v ["id"]] = array (
                        "" 
                );
                $arr ["custom_item_price"] [$v ["id"]] = array (
                        "" 
                );
            }
        }
        
        $month_listEx = ConstantsModel::$month_listEx [session ( 'school.login' ) ['language']];
        
        // 割引・割増一覧リスト取得
        $parent_ids = HierarchyTable::getInstance ()->getParentPschoolIds ( session ( 'school.login' ) ['id'] );
        $parent_ids [] = session('school.login') ['id'];
        $invoice_adjust_list = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ( $parent_ids );
        
//         dd($invoice_adjust_list);
        $data = $parentStudent;
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
        }
        $request->merge ( $arr );
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'input.html' );
        return view ( self::$TEMPLATE_URL . '.input', compact ( 'request', 'month_listEx', 'invoice_adjust_list', 'data', 'heads' ) );
    }
    
    /*
     * 請求書新規作成　確認画面
     */
    public function executeEntryConfirm(Request $request) {
        
        // $this->assignVars ( 'is_new', true );
        view ()->share ( 'is_new', true );
//         $this_screen = 'entry_confirm';
        view()->share('this_screen', 'entry_confirm');
        $parentStudent = $this->checkEntryParam ( $request );
        $invoice_year_month = $request ["invoice_year_month"];
        $data = $parentStudent;
//         dd($data);
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 運用区分が塾の場合
            $this->disp_bread_list ();
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
            
            // 運用区分が塾以外の場合
            // $this->disp_bread_list ();
        }
        
        // 入力チェック
        $is_error = $this->entryInputCommonProcess ( $request, $parentStudent );
        if (isset ( $is_error )) {
            // 保護者の割引・割増
            $month_listEx = ConstantsModel::$month_listEx [session ( 'school.login' ) ['language']];
            
            $parent_ids = HierarchyTable::getInstance ()->getParentPschoolIds ( session ( 'school.login' ) ['id'] );
            $parent_ids [] = session('school.login')['id'];
            $invoice_adjust_list = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ( $parent_ids );
            // $this->assignVars ( 'invoice_adjust_list', $invoice_adjust_list );
            
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'input.html' );
            return view ( self::$TEMPLATE_URL . '.input', compact ( 'data', 'heads', 'month_listEx', 'invoice_adjust_list' ) );
        }
        
        $request ['workflow_status'] = $request ['is_established'];
        $this->set_bread_list ( self::$ACTION_URL . "/entryconfirm", ConstantsModel::$bread_list [$this->current_lang] ['invoice_edit_confirm_screen'] );
        
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'input_confirm.html' );
        return view ( self::$TEMPLATE_URL . '.input_confirm', compact ( 'data', 'request', 'heads' ) );
    }
    
    /*
     * 請求書新規作成　完了画面
     */
    public function executeEntryComplete(Request $request) {
//         dd($request);
        $is_new = true;
        view()->share('is_new', $is_new);
        // dd($_SESSION[self::SESSION_INPUT_DATA]);
        // if ($_SESSION[self::SESSION_INPUT_DATA]) == null) {
        // セッション情報がないので、TOPに戻す。
        // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
        // return redirect($this->get_app_path () . self::$TOP_URL );
        // }
        
        // $request = $_SESSION[self::SESSION_INPUT_DATA];
        $mode = "complete";
        $parentStudent = $this->checkEntryParam ( $request, $mode );
        
        $invoice_year_month = $request ["invoice_year"] . '-' . str_pad ( $request ["invoice_month"], 2, 0, STR_PAD_LEFT );
        
        $invoice_due_date = date ( 'Y-m-d', strtotime ( $request ["invoice_due_year"] . '-' . $request ["invoice_due_month"] . '-' . $request ["invoice_due_day"] ) );
        
        // 登録処理
        try {
            InvoiceHeaderTable::getInstance ()->beginTransaction ();
            
//             dd($request);
            // 請求書ヘッダーを作成する。
            $header = array (
                    "pschool_id" => session ( 'school.login' ) ['id'],
                    "parent_id" => $request ["parent_id"],
                    "discount_price" => $request ["sum_discount_price"],
                    "amount" => $request ["amount"],
                    "is_established" => $request ["is_established"],
                    "workflow_status" => empty($request ["is_established"]) ? "0" : $request ["is_established"],
                    "mail_announce" => $request ["mail_announce"],
                    "is_requested" => "0",
                    "is_recieved" => "0",
                    "invoice_year_month" => $invoice_year_month,
                    "due_date" => $invoice_due_date,
                    "amount_display_type" => $request ["amount_display_type"],
                    "sales_tax_rate" => $request ["sales_tax_rate"],
                    "active_flag" => 1,
                    "register_admin" => session ( 'school.login' ) ['login_account_id'] 
            );
            
            $header_id = InvoiceHeaderTable::getInstance ()->save ( $header );
            
            $this->entryCompleteCommonProcess ( $request, $header_id );
            
            InvoiceHeaderTable::getInstance ()->commit ();
            
            $this->set_message ( "OK", ConstantsModel::$invoice_message [$this->current_lang] ['invoice_created_message'] );
            session()->forget( self::SESSION_INPUT_DATA );
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            $this->set_message ( "NG", ConstantsModel::$errors [$this->current_lang] ['process_invoice_error_message'] );
        }
        
//         $this->redirect_history ( 0 );
//         return back ()->withInput ();
	$level = 0;
	if (session()->exists(self::SESSION_HISTORY_LIST )) {
	    if (is_null ( $level )) {
	        $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
	    }
	    return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
	} else {
	    // 戻り先が無いので、TOPに戻す。
	    // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
	    return redirect ( $this->get_app_path () . self::$TOP_URL );
	}
    }
    
    /*
     * 請求書編集画面
     */
    public function executeEdit(Request $request) {
        $is_init = true;
        if (isset ( $request ["back"] )) {
            if (session ( self::SESSION_INPUT_DATA ) !== null) {
                $request = session ()->get ( self::SESSION_INPUT_DATA );
                $is_init = false;
            }
        } else if (isset ( $request ["tab_change"] )) {
            $is_init = false;
        }
        
        $header = $this->checkEditParam ($request);
        if ($header ["is_recieved"] == "1") {
            // 入金済みの場合は編集できないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_payment_cannot_edit_message'] );
//             $this->redirect_history(0);
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        if ($is_init) {
            $this->setFormDataFromDb ( $request, $header, $parentStudent );
        }
        
        $data = $parentStudent;
        
        // ---------------------------------------------------------------------
        // 保護者の割引・割増
        // ---------------------------------------------------------------------
        $month_listEx = ConstantsModel::$month_listEx [session ( 'school.login' ) ['language']];
        
        $parent_ids = HierarchyTable::getInstance ()->getParentPschoolIds ( session ( 'school.login' ) ['id'] );
        $parent_ids [] = session ( 'school.login' ) ['id'];
        $invoice_adjust_list = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ( $parent_ids );
        // $this->assignVars ( 'invoice_adjust_list', $invoice_adjust_list );
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            $this->set_bread_list ( self::$ACTION_URL . "/edit?back", ConstantsModel::$bread_list [$this->current_lang] ['invoice_edit_screen'] );
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
            
            // 運用区分が塾以外の場合
            $this->set_bread_list ( self::$ACTION_URL . "/edit?back", ConstantsModel::$bread_list [$this->current_lang] ['invoice_edit_screen'] );
        }
        return view ( self::$TEMPLATE_URL . '.input', compact ( 'request', 'data', 'month_listEx', 'invoice_adjust_list', 'heads' ) );
    }
    
    /*
     * 請求書編集　確認画面
     */
    public function executeEditConfirm(Request $request) {
        $header = $this->checkEditParam ( $request );
        if ($header ["is_recieved"] == "1") {
            // 入金済みの場合は編集できないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_payment_cannot_edit_message'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        $request ["invoice_year_month"] = $header ["invoice_year_month"];
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
//             HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect( $this->get_app_path () . self::$TOP_URL);
        }
        

        $data = $parentStudent;
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            
//             view()->share('heads', $heads [0]);
            $this->set_history ( 0, self::$ACTION_URL . '/search?simple&search_cond=2&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to_s=' . $heads [0] ['invoice_year'] . '&invoice_month_to_s=' . $heads [0] ['invoice_month'] . '&invoice_year_from_s=' . $heads [0] ['invoice_year'] . '&invoice_month_from_s=' . $heads [0] ['invoice_month'] );
            $heads = $heads [0];
        }
        
        // 入力チェック
        $is_error = $this->entryInputCommonProcess ($request, $parentStudent );
        if ($is_error) {
            // 保護者の割引・割増
            $month_listEx = ConstantsModel::$month_listEx [session ( 'school.login' ) ['language']];
            
            $parent_ids = HierarchyTable::getInstance ()->getParentPschoolIds ( session ( 'school.login' ) ['id'] );
            $parent_ids [] = session ( 'school.login' ) ['id'];
            $invoice_adjust_list = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ( $parent_ids );
//             $this->assignVars ( 'invoice_adjust_list', $invoice_adjust_list );
            
            $this->disp_bread_list ();
            
//             return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'input.html' );
            return view ( self::$TEMPLATE_URL . '.input', compact ( 'data', 'heads', 'month_listEx', 'invoice_adjust_list' ) );
        }
        $this->set_bread_list ( self::$ACTION_URL . "/editconfirm", ConstantsModel::$bread_list [$this->current_lang] ['invoice_edit_confirm_screen'] );
        
//         return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'input_confirm.html' );
        return view ( self::$TEMPLATE_URL . '.input_confirm', compact ( 'data', 'request', 'heads' ) );
    }
    
    /*
     * 請求書更新　完了画面
     */
    public function executeEditComplete(Request $request) {
        if (session ( self::SESSION_INPUT_DATA ) == null) {
            // セッション情報がないので、TOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        $request = session ()->get ( self::SESSION_INPUT_DATA );
        $header = $this->checkEditParam ($request);
        if ($header ["is_recieved"] == "1") {
            // 入金済みの場合は編集できないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_payment_cannot_edit_message'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        // $invoice_year_month = $request["invoice_year"] . $request["invoice_month"];
        $invoice_year_month = sprintf ( "%4d-%02d", $request ["invoice_year"], $request ["invoice_month"] );
        $invoice_due_date = date ( 'Y-m-d', strtotime ( $request ["invoice_due_year"] . '-' . $request ["invoice_due_month"] . '-' . $request ["invoice_due_day"] ) );
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        // 更新処理
        try {
            InvoiceHeaderTable::getInstance ()->beginTransaction ();
            
            $workflow_flag = 0;
            if ($request ["is_established"]) {
                $workflow_flag = 1; // 編集中
            }
            
            // 請求書ヘッダーを更新する。
            $header = array (
                    "id" => $header ["id"],
                    "discount_price" => $request ["sum_discount_price"],
                    "amount" => $request ["amount"],
                    "is_established" => empty($request ["is_established"]) ? "1" : $request ["is_established"],
                    "mail_announce" => $request ["mail_announce"],
                    "invoice_year_month" => $invoice_year_month,
                    "due_date" => $invoice_due_date,
                    "active_flag" => 1,
                    "update_admin" => session ( 'school.login' ) ['login_account_id'],
                    "workflow_status" => $workflow_flag 
            );
            InvoiceHeaderTable::getInstance ()->save ( $header );
            
            $this->entryCompleteCommonProcess ( $request, $header ["id"] );
            
            InvoiceHeaderTable::getInstance ()->commit ();
            
            $this->set_message ( "OK", ConstantsModel::$invoice_message [$this->current_lang] ['invoice_eddited_message'] );
            session()->forget ( self::SESSION_INPUT_DATA );
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            $this->set_message ( "NG", ConstantsModel::$errors [$this->current_lang] ['process_invoice_error_message'] );
        }
        
//         $this->redirect_history ( 0 );
        $level = 0;
        if (session()->exists(self::SESSION_HISTORY_LIST )) {
            if (is_null ( $level )) {
                $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
            }
            return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
        } else {
            // 戻り先が無いので、TOPに戻す。
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    
    /*
     * 請求書更新　請求一覧画面
     */
    public function executeMultiEditComplete(Request $request) {
        if (! isset ( $request ['parent_ids'] )) {
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['select_invoice_editing'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        try {
            InvoiceHeaderTable::getInstance ()->beginTransaction ();
            
            foreach ( $request ['parent_ids'] as $id ) {
                $header = array (
                        "id" => $id,
                        "is_established" => "1",
                        
                        "workflow_status" => 1 
                );
                InvoiceHeaderTable::getInstance ()->save ( $header );
            }
            
            InvoiceHeaderTable::getInstance ()->commit ();
            $this->set_message ( "OK", count ( $request ['parent_ids'] ) . ConstantsModel::$invoice_message [$this->current_lang] ['invoice_item_edited'] );
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            $this->set_message ( "NG", ConstantsModel::$errors [$this->current_lang] ['process_invoice_error_message'] );
        }
        
//         $this->redirect_history ( 0 );
        $level = 0;
        if (session()->exists(self::SESSION_HISTORY_LIST )) {
            if (is_null ( $level )) {
                $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
            }
            return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
        } else {
            // 戻り先が無いので、TOPに戻す。
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    
    /*
     * 個別確定
     */
    public function executeSingleEditComplete(Request $request) {
        $tbl = InvoiceHeaderTable::getInstance ();
        $tbl->beginTransaction ();
        try {
            
            $header = array (
                    // "id" => $request['parent_id'],
                    "id" => $request ['id'],
                    "is_established" => "1",
                    "workflow_status" => "1" 
            );
            $tbl->save ( $header );
            $tbl->commit ();
            
            $action_status = 'OK';
            $action_message = ConstantsModel::$invoice_message [$this->current_lang] ['invoice_confirmed_message'];
        } catch ( Exception $ex ) {
            $tbl->rollBack ();
            $action_status = 'NG';
            $action_message = ConstantsModel::$errors [$this->current_lang] ['process_invoice_error_message'];
        }
        $request ['action_status'] = $action_status;
        $request ['action_message'] = $action_message;
        return $this->executeDetail ( $request );
    }
    
    /*
     * 請求書削除　確認画面
     */
    public function executeDelete(Request $request) {
        $header = $this->checkEditParam ( $request );
        if ($header ["is_established"] == "1") {
            // 確定済みの場合は削除できないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_commit_cannot_delete_message'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        if ($header ["is_requested"] == "1") {
            // 請求済みの場合は削除できないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_billing_cannot_delete_message'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        $this->setFormDataFromDb ( $request, $header, $parentStudent );
        
        $data = $parentStudent;
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'delete_confirm.html' );
        return view ( self::$TEMPLATE_URL . 'delete_confirm', compact ( 'data' ) );
    }
    
    /*
     * 請求書削除　完了画面
     */
    public function executeDeleteComplete(Request $request) {
        $header = $this->checkEditParam ($request);
        if ($header ["is_established"] == "1") {
            // 確定済みの場合は削除できないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_commit_cannot_delete_message'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        if ($header ["is_requested"] == "1") {
            // 請求済みの場合は削除できないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_billing_cannot_delete_message'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        $request ["invoice_year_month"] = $header ["invoice_year_month"];
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
//             HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect($this->get_app_path () . self::$TOP_URL);
        }
        
        // 削除処理(論理削除)
        try {
            InvoiceHeaderTable::getInstance ()->beginTransaction ();
            
            // 請求書ヘッダーを削除する。
            $header = array (
                    "id" => $header ["id"],
                    "active_flag" => "0",
                    "delete_date" => date ( 'Y-m-d H:i:s' ),
                    "update_admin" => session ( 'school.login' ) ['login_account_id'] 
            );
            InvoiceHeaderTable::getInstance ()->save ( $header );
            
            // 請求書詳細を削除する
            $detail = InvoiceItemTable::getInstance ()->getList ( array (
                    'invoice_id' => $header ["id"],
                    'delete_date IS NULL' 
            ) );
            foreach ( $detail as $detail_item ) {
                $detail_item ['active_flag'] = 0;
                $detail_item ['delete_date'] = date ( 'Y-m-d H:i:s' );
                $detail_item ['update_admin'] = session ( 'school.login' ) ['login_account_id'];
                InvoiceItemTable::getInstance ()->save ( $detail_item );
            }
            
            InvoiceHeaderTable::getInstance ()->commit ();
            
            $this->set_message ( "OK", ConstantsModel::$invoice_message [$this->current_lang] ['invoice_deleted_message'] );
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            $this->set_message ( "NG", ConstantsModel::$errors [$this->current_lang] ['process_invoice_error_message'] );
        }
        
//         $this->redirect_history ( 0 );
        $level = 0;
        if (session()->exists(self::SESSION_HISTORY_LIST )) {
            if (is_null ( $level )) {
                $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
            }
            return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
        } else {
            // 戻り先が無いので、TOPに戻す。
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    
    /*
     * 請求書無効化　確認画面
     */
    public function executeDisabled(Request $request) {
        $header = $this->checkEditParam ();
        if ($header ["is_recieved"] == "1") {
            // 入金済みの場合は無効にできないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_payment_cannot_disable'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        $request ["disabled"] = 1;
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        $this->setFormDataFromDb ( $request, $header, $parentStudent );
        
        $data = $parentStudent;
//         $lan = $this->lan;
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'delete_confirm.html' );
        return view ( self::$TEMPLATE_URL . 'delete_confirm', compact ('data' ) );
    }
    
    /*
     * 請求書無効化　完了画面
     */
    public function executeDisabledComplete(Request $request) {
        $header = $this->checkEditParam ();
        if ($header ["is_recieved"] == "1") {
            // 入金済みの場合は無効にできないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [$this->current_lang] ['already_payment_cannot_disable'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        $request ["disabled"] = 1;
        $request ["invoice_year_month"] = $header ["invoice_year_month"];
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        // 無効処理(非アクティブ化)
        try {
            InvoiceHeaderTable::getInstance ()->beginTransaction ();
            
            // 請求書ヘッダーを削除する。
            $header = array (
                    "id" => $header ["id"],
                    "active_flag" => "0",
                    "update_admin" => session ( 'school.login' ) ['login_account_id'] 
            );
            InvoiceHeaderTable::getInstance ()->save ( $header );
            
            InvoiceHeaderTable::getInstance ()->commit ();
            
            $this->set_message ( "OK", ConstantsModel::$invoice_message [$this->current_lang] ['invoice_disabled_message'] );
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            $this->set_message ( "NG", ConstantsModel::$errors [$this->current_lang] ['process_invoice_error_message'] );
        }
        
//         $this->redirect_history ( 0 );
        $level = 0;
        if (session()->exists(self::SESSION_HISTORY_LIST )) {
            if (is_null ( $level )) {
                $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
            }
            return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
        } else {
            // 戻り先が無いので、TOPに戻す。
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    
    /*
     * 入金チェック　検索画面
     */
    public function executeReceiveSearch(Request $request) {
        $dropdate = $this->getDropdate ();
//         $this_screen = 'receivesearch';
        view()->share('this_screen', 'receivesearch');
        if (isset ( $request ["menu"] )) {
            // 初期表示の場合
            // $request["invoice_year"] = date('Y');
            // $request["invoice_month"] = date('n');
            $request ['invoice_year_from'] = "";
            $request ['invoice_month_from'] = "";
            $request ['invoice_year_to'] = date ( 'Y', $dropdate );
            $request ['invoice_month_to'] = date ( 'm', $dropdate );
            $request ['is_recieved'] = 0;
            $request ['invoice_type'] = array (
                    1,
                    1,
                    1 
            );
        } else if (isset ( $request ["back"] ) && session ( self::SESSION_RECIEVE_SEARCH_COND ) !== null) {
            $request = session ( self::SESSION_RECIEVE_SEARCH_COND );
        }
        
        $invoice_list = InvoiceHeaderTable::getInstance ()->getListBySearch ( session ( 'school.login' ) ['id'], $request );
        $this->set_list_info ( $invoice_list );
        // $this->assignVars ( 'invoice_list', $invoice_list );
        
        $pschool = session ( 'school.login' );
        
        $invoice_nowdate = date ( 'Y-m-d' );
        $dialog_open = false;
        
        $drop_year = date ( 'Y', $dropdate );
        $drop_month = date ( 'n', $dropdate );
        if (! empty ( $request ['search_cond'] ) && $request ['search_cond'] == 1) {
            $search_cond = 1;
        } else {
            $search_cond = 0;
        }
        if (isset ( $request ['invoice_type'] )) {
            foreach ( $request ['invoice_type'] as $key => $val ) {
                $item_name = 'invoice_type' . $key;
                $request [$item_name] = 1;
            }
        }
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // $this->clear_bread_list();
            $this->set_bread_list ( self::$ACTION_URL . "/receivesearch?back", ConstantsModel::$bread_list [$this->current_lang] ['payment_process'] );
            session ()->put ( self::SESSION_RECIEVE_SEARCH_COND, $request );
            $this->set_history ( 0, "invoice/receivesearch?back" );
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
            
            // 運用区分が塾以外の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '/list?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
            $this->set_bread_list ( self::$ACTION_URL . '/receivesearch?invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['payment_process'] );
            session ()->put ( self::SESSION_RECIEVE_SEARCH_COND, $request );
            $this->set_history ( 0, self::$ACTION_URL . '/receivesearch?invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'] );
        }
        
//         $lan = $this->lan;
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'receive_search.html' );
        return view ( self::$TEMPLATE_URL . 'receive_search', compact ( 'invoice_list', 'invoice_nowdate', 'dialog_open', 'drop_year', 'drop_month', 'search_cond', 'heads' ) );
    }
    
    /*
     * 支払選択ダイアログ
     */
    public function executeReceiveSelect(Request $request) {
        if (! $request->exists ( 'invoice_ids' ) || ! is_array ( $request ['invoice_ids'] )) {
            $request = session ()->get ( self::SESSION_RECIEVE_SEARCH_COND );
            $no_data = 1;
            return $this->executeReceiveSearch ( $request );
        } elseif ($request->exists ( 'invoice_year_month' )) {
            $dropdate = $this->getDropdate ();
            $request ['is_recieved'] = 0;
            $request ['invoice_type'] = array (
                    1,
                    1,
                    1 
            );
            $dialog_open = true;
        } else {
            $dropdate = $this->getDropdate ();
            $request ['invoice_year_from'] = "";
            $request ['invoice_month_from'] = "";
            $request ['invoice_year_to'] = date ( 'Y', $dropdate );
            $request ['invoice_month_to'] = date ( 'm', $dropdate );
            $request ['is_recieved'] = 0;
            $request ['invoice_type'] = array (
                    1,
                    1,
                    1 
            );
            $dialog_open = true;
        }
        
        $invoice_list = InvoiceHeaderTable::getInstance ()->getListBySearch ( session ( 'school.login' ) ['id'], $request );
        $this->set_list_info ( $invoice_list );
        // $this->assignVars ( 'invoice_list', $invoice_list );
        
        $invoice_types = $invoice_list [0] ['invoice_type'];
        $invoice_nowdate = date ( 'Y-m-d' );
        $invoice_ids = $request ['invoice_ids'];
        
        $drop_year = date ( 'Y', $dropdate );
        $drop_month = date ( 'n', $dropdate );
        if (! empty ( $request ['search_cond'] ) && $request ['search_cond'] == 1) {
            $search_cond = 1;
        } else {
            $search_cond = 0;
        }
        if (isset ( $request ['invoice_type'] )) {
            foreach ( $request ['invoice_type'] as $key => $val ) {
                $item_name = 'invoice_type' . $key;
                $request [$item_name] = 1;
            }
        }
        
        // $this->clear_bread_list();
        $this->set_bread_list ( self::$ACTION_URL . "/receivesearch?back", ConstantsModel::$bread_list [$this->current_lang] ['payment_check'] );
        // $_SESSION[self::SESSION_RECIEVE_SEARCH_COND] = $request;
        $this->set_history ( 0, "invoice/receivesearch?back" );
//         $lan = $this->lan;
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'receive_search.html' );
        return view ( self::$TEMPLATE_URL . 'receive_search', compact ( 'no_data', 'dialog_open', 'invoice_list', 'invoice_types', 'invoice_nowdate', 'invoice_ids', 'drop_year', 'drop_month', 'search_cond' ) );
    }
    public function executeReceiveComplete(Request $request) {
        if (! $request->exists ( 'invoice_ids' ) || ! is_array ( $request ['invoice_ids'] )) {
            $request = session ( self::SESSION_RECIEVE_SEARCH_COND );
            $no_data = 1;
            return $this->executeReceiveSearch ( $request );
        }
        
        // 支払い方法
        if (empty ( $request ['dialogs_type'] )) {
            $invoice_type = 0;
        } else {
            $invoice_type = $request ['dialogs_type'];
        }
        // 支払い日
        if (empty ( $request ['dialogs_date'] )) {
            $paid_date = date ( 'Y-m-d' );
        } else {
            $paid_date = $request ['dialogs_date'];
        }
        
        // まとめて入金にする
        foreach ( $request ['invoice_ids'] as $invoice_header_id ) {
            $this->setReceiveFinished ( $invoice_header_id, $invoice_type, $paid_date );
        }
        
        // まとめて領収書発行
        if (! empty ( $request ['dialogs_receipt'] )) {
        }
        $request ['no_data'] = $no_data;
        $request ['invoice_ids'] = null;
        return $this->executeReceiveSearch ( $request );
    }
    private function setReceiveFinished($invoice_header_id, $invoice_type, $paid_date) {
        InvoiceHeaderTable::getInstance ()->beginTransaction ();
        try {
            // 入金状態にする。
            $header = array (
                    "id" => $invoice_header_id,
                    "is_recieved" => "1",
                    "paid_date" => $paid_date,
                    "invoice_type" => $invoice_type,
                    "update_admin" => session ( 'school.login' ) ['login_account_id'],
                    "workflow_status" => 31 
            );
            InvoiceHeaderTable::getInstance ()->save ( $header );
            
            // イベントのテーブルに入金済みフラグを立てる。
            StudentCourseRelTable::getInstance ()->updateIsReceivedByInvoice ( session ( 'school.login' ) ['id'], session ( 'school.login' ) ['login_account_id'], $invoice_header_id );
            InvoiceHeaderTable::getInstance ()->commit ();
            // $this->set_message("OK", "請求書を入金済みにしました。");
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            // $this->set_message("NG", ConstantsModel::$errors[1]['process_invoice_error_message']);
        }
        
        return false;
    }
    
    /*
     * 入金チェック　完了画面
     */
    public function executeReceiveCheck() {
        $header = $this->checkEditParam ();
        if ($header ["is_established"] != "1") {
            // 確定済みでない場合は入金チェックできないので、元の画面に戻す。
            $this->set_message ( "NG", ConstantsModel::$invoice_message [1] ['cannot_payment_process_message'] );
//             $this->redirect_history ( 0 );
            $level = 0;
            if (session()->exists(self::SESSION_HISTORY_LIST )) {
                if (is_null ( $level )) {
                    $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                }
                return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
            } else {
                // 戻り先が無いので、TOPに戻す。
                return redirect ( $this->get_app_path () . self::$TOP_URL );
            }
        }
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        // 入金状態にする。
        try {
            InvoiceHeaderTable::getInstance ()->beginTransaction ();
            
            $header = array (
                    "id" => $header ["id"],
                    "is_recieved" => "1",
                    "paid_date" => date ( 'Y-m-d' ),
                    "update_admin" => session ( 'school.login' ) ['login_account_id'],
                    "workflow_status" => 31 
            );
            InvoiceHeaderTable::getInstance ()->save ( $header );
            
            // イベントのテーブルに入金済みフラグを立てる。
            StudentCourseRelTable::getInstance ()->updateIsReceivedByInvoice ( session ( 'school.login' ) ['id'], session ( 'school.login' ) ['login_account_id'], $header ["id"] );
            
            InvoiceHeaderTable::getInstance ()->commit ();
            
            // $this->set_message("OK", "請求書を入金済みにしました。");
        } catch ( Exception $ex ) {
            InvoiceHeaderTable::getInstance ()->rollBack ();
            // $this->set_message("NG", ConstantsModel::$errors[1]['process_invoice_error_message']);
        }
        
//         $this->redirect_history ( 0 );
        $level = 0;
        if (session()->exists(self::SESSION_HISTORY_LIST )) {
            if (is_null ( $level )) {
                $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
            }
            return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
        } else {
            // 戻り先が無いので、TOPに戻す。
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    
    /*
     * メール通知　検索画面
     */
    public function executeMailSearch(Request $request) {
//         $this_screen = 'mailsearch';
        view()->share('this_screen', 'mailsearch');
        if (isset ( $request ["menu"] )) {
            // 初期表示の場合
            $invoice_year_month = InvoiceHeaderTable::getInstance ()->getNewestYearMonth ( session ( 'school.login' ) ['id'] );
            $request ['invoice_year_to'] = substr ( $invoice_year_month ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $invoice_year_month ['invoice_year_month'], 5, 2 );
            
            $request ["workflow_status"] = 1;
        } else if (isset ( $request ["back"] ) && session ( self::SESSION_MAIL_SEARCH_COND ) !== null) {
            $request = session ()->get ( self::SESSION_MAIL_SEARCH_COND );
        }
        
        $invoice_list = InvoiceHeaderTable::getInstance ()->getListBySearch ( session ( 'school.login' ) ['id'], $request );
       foreach ($invoice_list as $key => $item){
           $bank_type_result = InvoiceHeaderTable::getInstance()->getBankTypeByParentId($item['parent_id']);
           $bank_type = ConstantsModel::$bank_type[session ( 'school.login' ) ['language']][$bank_type_result];
           $invoice_list[$key]['bank_type'] = $bank_type;
           $send_date = InvoiceHeaderTable::getInstance()->getSendDateByParentId($item['parent_id']);
           $invoice_list[$key]['send_date'] = $send_date;
       }
//        dd($invoice_list);
        $request->offsetUnset('is_recieved');
        $request->offsetUnset('parent_mail_infomation');
        $this->set_list_info ( $invoice_list );
        // $this->assignVars ( 'invoice_list', $invoice_list );
        
//         session ()->put ( self::SESSION_MAIL_SEARCH_COND, $request );
        $_SESSION [self::SESSION_MAIL_SEARCH_COND] = $request;
        $mail_infomation_list = ConstantsModel::$mail_infomation [session ( 'school.login' ) ['language']];
        
        $heads = array();
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 必ず 一覧 - 請求書送付 とする
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . "/mailsearch?back", ConstantsModel::$bread_list [$this->current_lang] ['send_invoice'] );
            $this->set_history ( 0, self::$ACTION_URL . "/mailsearch?back" );
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            
            // 運用区分が塾以外の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '/list?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
            $this->set_bread_list ( self::$ACTION_URL . '/mailsearch?invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['send_invoice'] );
            $this->set_history ( 0, self::$ACTION_URL . '/mailsearch?invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'] );
        }
        
        // ---------------------------------------------------------------------
        // 口座振替対象のデータが作成されているかチェック
        // ---------------------------------------------------------------------
        $operation = 0;
        $invoice_head = InvoiceHeaderTable::getInstance ()->getList ( array (
                'pschool_id' => session ( 'school.login' ) ['id'],
                'invoice_year_month' => $heads [0] ['invoice_year_month'],
                'workflow_status > 1',
                'delete_date IS NULL' 
        ) );
        $heads = $heads [0];
//         dd($heads);
        if (! empty ( $invoice_head ) && count ( $invoice_head ) > 0) {
            $operation = 1;
        }
        $invoice_transfer_operation = $operation;
//         $lan = $this->lan;
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'mail_search.html' );
        return view ( self::$TEMPLATE_URL . '.mail_search', compact ( 'invoice_list', 'mail_infomation_list', 'heads', 'invoice_transfer_operation' ) );
    }
    
    /*
     * メール通知　完了画面
     */
    public function executeMailSend() {
        // メールを送る
        foreach ( $request ['parent_ids'] as $row ) {
            $request ['id'] = $row;
            
            $header = $this->checkEditParam ();
            if ($header ["is_established"] != "1") {
                // 確定済みでない場合はメール通知できないので、元の画面に戻す。
                $this->set_message ( "NG", ConstantsModel::$invoice_message [1] ['cannot_email_notify_message'] );
                continue; // $this->redirect_history(0);
            }
            
            $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $header ["parent_id"] );
            if (empty ( $parentStudent )) {
                // 存在しない保護者なのでTOPに戻す。
                continue; // HeaderUtil::redirect($this->get_app_path() . self::$TOP_URL);
            }
            
            // if($parentStudent["mail_infomation"] == 0) // 郵送の請求書の時
            if ($header ["mail_announce"] == 0) // 郵送の請求書の時
{
                $this->createPdf ( $header );
                
                try {
                    InvoiceHeaderTable::getInstance ()->beginTransaction ();
                    
                    // 請求書発送済みにする。
                    $header = array (
                            "id" => $header ["id"],
                            "announced_date" => date ( 'Y-m-d H:i:s' ),
                            "is_requested" => 1,
                            "update_admin" => session ( 'school.login' ) ['login_account_id'],
                            "workflow_status" => 11 
                    );
                    InvoiceHeaderTable::getInstance ()->save ( $header );
                    InvoiceHeaderTable::getInstance ()->commit ();
                } catch ( Exception $ex ) {
                    InvoiceHeaderTable::getInstance ()->rollBack ();
                    $this->set_message ( "NG", ConstantsModel::$errors [1] ['process_invoice_error_message'] );
                }
                
                // continue;
            } // if($parentStudent["mail_infomation"] == 1) // メール通知の請求書の時
elseif ($header ["mail_announce"] == 1) // メール通知の請求書の時
{
                // メール通知を行う。
                try {
                    InvoiceHeaderTable::getInstance ()->beginTransaction ();
                    
                    // メール通知済みにする。
                    $header = array (
                            "id" => $header ["id"],
                            "is_mail_announced" => "1",
                            "is_requested" => 1,
                            "announced_date" => date ( 'Y-m-d H:i:s' ),
                            "update_admin" => session ( 'school.login' ) ['login_account_id'],
                            "workflow_status" => 11 
                    );
                    InvoiceHeaderTable::getInstance ()->save ( $header );
                    
                    // メール情報テーブルにメール情報を登録する。
                    $message_key = MiscUtil::getMailMessageKey ();
                    $message = array ();
                    $message ['type'] = 1;
                    $message ['message_key'] = $message_key;
                    $message ['relative_ID'] = $header ["id"];
                    $message ['pschool_id'] = session ( 'school.login' ) ['id'];
                    $message ['parent_id'] = $parentStudent ["id"];
                    $message ['register_admin'] = session ( 'school.login' ) ['login_account_id'];
                    
                    MailMessageTable::getInstance ()->save ( $message );
                    
                    $send_mail_params = array ();
                    $send_mail_params ['from'] = session ( 'school.login' ) ['mailaddress'];
                    $send_mail_params ['to'] = $parentStudent ['parent_mailaddress1'];
                    $send_mail_params ['subject'] = 'ご請求確定のお知らせ';
                    
                    $tpl = self::$MAIL_TEMPLATE;
                    $assign_var = array ();
                    $assign_var ['parent_name'] = $parentStudent ['parent_name'];
                    $assign_var ['school_name'] = session ( 'school.login' ) ['name'];
                    $assign_var ['contact'] = session ( 'school.login' ) ['mailaddress'];
                    $assign_var ['daihyou'] = session ( 'school.login' ) ['daihyou'];
                    $assign_var ['reply'] = MAIL_FROM;
                    $assign_var ['url'] = $this->createActivateUrl ( self::$MAIL_URL, "?message_key=" . $message_key );
                    $send_mail_params ['text'] = $this->createMailMessage ( $tpl, $assign_var );
                    
                    if (! $this->send_mail ( $send_mail_params )) {
                        // 送信失敗
                        throw new Exception ( ConstantsModel::$errors [1] ['send_mail_fail'] );
                    }
                    
                    InvoiceHeaderTable::getInstance ()->commit ();
                    
                    $this->set_message ( "OK", ConstantsModel::$invoice_message [1] ['email_notify_to_invoice'] );
                } catch ( Exception $ex ) {
                    InvoiceHeaderTable::getInstance ()->rollBack ();
                    $this->set_message ( "NG", ConstantsModel::$errors [1] ['process_invoice_error_message'] );
                }
                
//                 $this->redirect_history ( 0 );
                $level = 0;
                if (session()->exists(self::SESSION_HISTORY_LIST )) {
                    if (is_null ( $level )) {
                        $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
                    }
                    return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
                } else {
                    // 戻り先が無いので、TOPに戻す。
                    return redirect ( $this->get_app_path () . self::$TOP_URL );
                }
            }
        }
        $data = true;
        echo json_encode ( $data );
        // return false;
        // $this->redirect_history(0);
    }
    /*
     * PDF表示ファンクション
     */
    public function executeShow() {
        $this->exec_combine_cmd ();
        $contents = file_get_contents ( $this->tempDir . self::TEMP_PDF_NAME );
        header ( 'Content-Type: application/pdf' );
        echo $contents;
    }
    /*
     * 戻るアボタンクション
     */
    public function executeSimpleBack() {
        $level = isset ( $request ["level"] ) ? $request ["level"] : NULL;
        $this->redirect_history ( $level );
    }
    
    /*
     * 印刷アクション
     */
    public function executePrint(Request $request) {
//         $lan = $this->lan;
        if (! $request->has ( 'id' )) {
            // 無効ですページ
            $error = ConstantsModel::$errors [1] ['invalid_request'];
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'error.html' );
            return view ( self::$TEMPLATE_URL . 'error', compact ('error' ) );
        }
        // 請求書情報取得
        $header = InvoiceHeaderTable::getInstance ()->getRow ( array (
                "id" => $request ['id'],
                "pschool_id" => session ( 'school.login' ) ['id'],
                "active_flag" => 1,
                "delete_date IS NULL" 
        ) );
        if (empty ( $header )) {
            // 無効ですページ
            $error = ConstantsModel::$invoice_message [$this->current_lang] ['invoice_info_cannot_obtain'];
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'error.html' );
            return view ( self::$TEMPLATE_URL . 'error', compact ( 'error' ) );
        }
        
        // 保護者情報取得
        $parentRow = ParentTable::getInstance ()->getRow ( $where = array (
                "pschool_id" => session ( 'school.login' ) ['id'],
                'id' => $header ["parent_id"] 
        ) );
        if (empty ( $parentRow ) || count ( $parentRow ) < 1) {
            // 無効ですページ
            $error = ConstantsModel::$invoice_message [$this->current_lang] ['invoice_info_cannot_obtain'];
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'error.html' );
            return view ( self::$TEMPLATE_URL . 'error', compact ('error' ) );
        }
        
        // //////// 保護者の支払方法で、請求書のフォーマットが変わる $parentRow['invoice_type'];
        
        $parentStudent = InvoiceHeaderTable::getInstance ()->getParentStudentListByInvoiceId ( $header ["pschool_id"], $header ["id"], $header ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 無効ですページ
            $error = ConstantsModel::$invoice_message [1] ['invoice_info_cannot_obtain'];
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'error.html' );
            return view ( self::$TEMPLATE_URL . 'error', compact ( 'error' ) );
        }
        $this->setPrintDataFromDb ( $request, $header, $parentStudent );
        $data = $parentStudent;
        
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'print.html' );
        return view ( self::$TEMPLATE_URL . 'print', compact ('data' ) );
    }
    
    /*
     * 全銀データダウンロード
     */
    public function executeDownload(Request $request) {
        $dropdate = $this->getDropdate ();
//         $this_screen = 'download';
        view()->share('this_screen', 'download');
        if (! empty ( $request ['invoice_year_month'] )) {
            $request ['invoice_year_to'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $request ['invoice_year_month'], - 2 );
            $request ['invoice_year_from'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_from'] = substr ( $request ['invoice_year_month'], - 2 );
        } elseif (isset ( $request ["menu"] )) {
            // 初期表示の場合
            $request ['invoice_year_to'] = date ( 'Y', $dropdate );
            $request ['invoice_month_to'] = date ( 'm', $dropdate );
            $request ['invoice_year_from'] = "";
            $request ['invoice_month_from'] = "";
            $request ['is_recieved'] = 0;
        }
        $request ['invoice_type'] = array (
                '2' => 1 
        );
        
        // ファイル一覧
        if (! empty ( $request ["invoice_year_to"] ) && ! empty ( $request ["invoice_month_to"] )) {
            $year_month = sprintf ( "%04d-%02d", $request ["invoice_year_to"], $request ["invoice_month_to"] );
        } else {
            $year_month = null;
        }
        $file_list = InvoiceRequestTable::getInstance ()->getFileList ( session ( 'school.login' ) ['id'], $year_month );
        // $this->assignVars ( 'file_list', $file_list );
        
        // 請求元情報取得
        $pschool_bank = PschoolBankAccountTable::getInstance ()->getActiveList ( array (
                'pschool_id' => session ( 'school.login' ) ['id'] 
        ) );
        $pschool_bank [0] ['payment_date'] = session ( 'school.login' ) ['payment_date'];
        $pschool = $pschool_bank [0];
        
        // 請求先一覧
        $invoice_list = InvoiceHeaderTable::getInstance ()->getListforDownloadBySearch ( session ( 'school.login' ) ['id'], $request );
        // $this->assignVars ( 'invoice_list', $invoice_list );
        
        $drop_year = date ( 'Y', $dropdate );
        $drop_month = date ( 'n', $dropdate );
        if (! empty ( $request ['search_cond'] ) && $request ['search_cond'] == 1) {
            $search_cond = 1;
        } else {
            $search_cond = 0;
        }
        if (isset ( $request ['invoice_type'] )) {
            foreach ( $request ['invoice_type'] as $key => $val ) {
                $item_name = 'invoice_type' . $key;
                $request [$item_name] = 1;
            }
        }
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 運用区分が塾の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . "/accounttransfer", ConstantsModel::$bread_list [$this->current_lang] ['account_transfer'] );
            $this->set_bread_list ( self::$ACTION_URL . "/download?menu", ConstantsModel::$bread_list [$this->current_lang] ['create_form_account_transfer'] );
            $this->set_history ( 0, self::$ACTION_URL . "/download?menu" );
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
            
            // 運用区分が塾以外の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '/list?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
            $this->set_bread_list ( self::$ACTION_URL . '/search?simple&search_cond=2&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to_s=' . $heads [0] ['invoice_year'] . '&invoice_month_to_s=' . $heads [0] ['invoice_month'] . '&invoice_year_from_s=' . $heads [0] ['invoice_year'] . '&invoice_month_from_s=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . '/accounttransfer?search&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['account_transfer'] );
            $this->set_bread_list ( self::$ACTION_URL . '/download?invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['create_form_account_transfer'] );
            $this->set_history ( 0, self::$ACTION_URL . '/download?invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'] );
        }
//         $lan = $this->lan;
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'download.html' );
        return view ( self::$TEMPLATE_URL . 'download', compact ( 'file_list', 'pschool', 'invoice_list', 'drop_year', 'drop_month', 'search_cond', 'heads' ) );
    }
    /*
     * 全銀データダウンロード完了
     */
    public function executeDownloadComplete(Request $request) {
        $this->getZengin ();
        $request ["menu"] = 1;
        return $this->executeDownload ();
        
        $this->dispDebug ( $request );
        exit ();
        // 請求書検索画面に遷移する。
        // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
        return redirect ( $this->get_app_path () . self::$TOP_URL );
    }
    public function executeDownloadCancel(Request $request) {
        if (! empty ( $request ['id'] )) {
            $req = InvoiceRequestTable::getInstance ()->getRow ( array (
                    "pschool_id" => session ( 'school.login' ) ['id'],
                    'invoice_header_id' => $request ['id'],
                    'status_flag' => '1' 
            ) );
            $request ['file_name'] = $req ['processing_filename'];
        } elseif (empty ( $request ['file_name'] )) {
            exit ();
        }
        
        InvoiceRequestTable::getInstance ()->setCancelStatusFlag ( session ( 'school.login' ) ['id'], $request ['file_name'] );
        
        $request ["menu"] = 1;
        $action_status = 'OK';
        $action_message = ConstantsModel::$errors [1] ['request_form_deleted'];
        $request ['action_status'] = $action_status;
        $request ['action_message'] = $action_message;
        return $this->executeDownload ( $request );
    }
    public function executeDownloadFile(Request $request) {
        $this->filedown ( $request ["file_name"] );
    }
    
    /**
     * 全銀データフォーマットに合わせて電文分割
     *
     * @param unknown $data            
     * @param unknown $mode            
     * @return string
     */
    private function getSplitUploadFile($data, $mode) {
        $array_data = array ();
        
        $start_idx = 0;
        $end_idx = 0;
        
        switch ($mode) {
            case 1 : // ヘッダーレコード
                $start_idx = 1;
                $end_idx = 14;
                if (mb_strlen ( $data ) != self::HEADER_RECORD_LEN)
                    return null;
                break;
            case 2 : // データレコード
                $start_idx = 15;
                $end_idx = 28;
                if (mb_strlen ( $data ) != self::DATA_RECORD_LEN)
                    return null;
                break;
            case 8 : // トレーラレコード
                $start_idx = 29;
                $end_idx = 36;
                if (mb_strlen ( $data ) != self::TRAILER_RECORD_LEN)
                    return null;
                break;
            case 9 : // エンドレコード
                $start_idx = 37;
                $end_idx = 38;
                if (mb_strlen ( $data ) != self::END_RECORD_LEN)
                    return null;
                break;
            default :
                return null;
                break;
        }
        
        $array_data [0] = "";
        $start_pos = 0;
        
        // データ取得
        for($idx = $start_idx; $idx <= $end_idx; $idx ++) {
            $array_data [$idx] = mb_substr ( $data, $start_pos, self::$LEYOUT [$idx] ['degit'] );
            $start_pos += self::$LEYOUT [$idx] ['degit'];
        }
        
        return $array_data;
    }
    
    /*
     * 全銀データアップロード
     */
    public function executeUpload(Request $request) {
        // $this->dispDebug($request);
        $lan = $this->lan;
//         $this_screen = 'upload';
        view()->share('this_screen', 'upload');
        $error_info = array ();
        
        if (! empty ( $request ['invoice_year_month'] )) {
            $request ['invoice_year_to'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $request ['invoice_year_month'], - 2 );
            $request ['invoice_year_from'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_from'] = substr ( $request ['invoice_year_month'], - 2 );
        }
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 運用区分が塾の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . "/accounttransfer", ConstantsModel::$bread_list [$this->current_lang] ['account_transfer'] );
            $this->set_bread_list ( self::$ACTION_URL . "/upload", ConstantsModel::$bread_list [$this->current_lang] ['account_transfer_capture_result'] );
            $this->set_history ( 0, self::$ACTION_URL . "/upload" );
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
            
            // 運用区分が塾以外の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '/list?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
            $this->set_bread_list ( self::$ACTION_URL . '/search?simple&search_cond=2&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to_s=' . $heads [0] ['invoice_year'] . '&invoice_month_to_s=' . $heads [0] ['invoice_month'] . '&invoice_year_from_s=' . $heads [0] ['invoice_year'] . '&invoice_month_from_s=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . '/accounttransfer?search&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['account_transfer'] );
            $this->set_bread_list ( self::$ACTION_URL . '/upload?invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['account_transfer_capture_result'] );
            $this->set_history ( 0, self::$ACTION_URL . '/upload&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'] );
        }
        
        if (isset ( $request ['upload_file'] ['keep'] ['fullpath'] )) {
            
            $upload_data = 1;
            
            $pschool_id = session ( 'school.login' ) ['id'];
            // -----------------------------------------------------------------
            // 入力ファイルのチェック
            // -----------------------------------------------------------------
            // (1)ファイルのサイズチェック
            $full_path = $request ['upload_file'] ['keep'] ['fullpath'];
            $file_name = $request ['upload_file'] ['name'];
            
            if (session ( self::SESSION_UPLOAD_FILE ) == null) {
                // session(self::SESSION_UPLOAD_FILE) = array ();
                $_SESSION [self::SESSION_UPLOAD_FILE] = array ();
            } else {
                session_unset ( self::SESSION_UPLOAD_FILE );
            }
            session ( self::SESSION_UPLOAD_FILE ) ['fullpath'] = $request ['upload_file'] ['keep'] ['fullpath'];
            session ( self::SESSION_UPLOAD_FILE ) ['name'] = $request ['upload_file'] ['name'];
            
            $file_size = filesize ( $full_path );
            
            if ($file_size < (self::HEADER_RECORD_LEN + self::TRAILER_RECORD_LEN + self::END_RECORD_LEN)) {
                // ファイルが小さい
                $error_info [] = array (
                        'error_code' => '001',
                        'error_msg' => ConstantsModel::$errors [1] ['file_size_error_define_less_than'] 
                );
                // $this->assignVars ( 'error_info', $error_info );
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data' ) );
            }
            $diff = $file_size - (self::HEADER_RECORD_LEN + self::TRAILER_RECORD_LEN + self::END_RECORD_LEN);
            if (($diff / self::DATA_RECORD_LEN < 1 || $diff % self::DATA_RECORD_LEN > 0) && (($diff + 2) / self::DATA_RECORD_LEN < 1 || ($diff + 2) % self::DATA_RECORD_LEN > 0)) {
                // ファイルサイズがおかしい
                $error_info [] = array (
                        'error_code' => '002',
                        'error_msg' => ConstantsModel::$errors [$this->current_lang] ['file_size_error_data_record_size'] 
                );
                // $this->assignVars ( 'error_info', $error_info );
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data' ) );
            }
            
            // (2)ファイルの読み込み
            $file = fopen ( $full_path, "r" );
            if (! $file) {
                // ファイルオープンエラー たぶんないと思うが
                $error_info [] = array (
                        'error_code' => '003',
                        'error_msg' => ConstantsModel::$errors [$this->current_lang] ['file_open_error'] 
                );
                // $this->assignVars ( 'error_info', $error_info );
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'heads', 'upload_data' ) );
            }
            
            $line_cnt = 0;
            $data_record = array ();
            while ( $f_data = fgets ( $file ) ) {
                $line_cnt ++;
                
                // SHISからUTF-8へ変換
                $f_data = mb_convert_encoding ( $f_data, "UTF-8", "SJIS" );
                
                // データ区分取得
                $data_type = mb_substr ( $f_data, 0, 1 );
                switch ($data_type) {
                    case 1 :
                        $header_record = $this->getSplitUploadFile ( $f_data, $data_type ); // ヘッダーレコードのマッピング
                        if (empty ( $header_record )) {
                            $error_info [] = array (
                                    'error_code' => '011',
                                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['header_record_error'],
                                    'line' => $line_cnt 
                            );
                        }
                        break;
                    case 2 :
                        $data_record_data = $this->getSplitUploadFile ( $f_data, $data_type ); // データレコードのマッピング
                        if (empty ( $data_record_data )) {
                            $error_info [] = array (
                                    'error_code' => '012',
                                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['data_record_error'],
                                    'line' => $line_cnt 
                            );
                        }
                        $data_record [] = $data_record_data;
                        break;
                    case 8 :
                        $trailer_record = $this->getSplitUploadFile ( $f_data, $data_type ); // トレーラレコードのマッピング
                        if (empty ( $trailer_record )) {
                            $error_info [] = array (
                                    'error_code' => '013',
                                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['trailer_record_error'],
                                    'line' => $line_cnt 
                            );
                        }
                        break;
                    case 9 :
                        $end_record = $this->getSplitUploadFile ( $f_data, $data_type ); // エンドレコードのマッピング
                        if (empty ( $trailer_record )) {
                            $error_info [] = array (
                                    'error_code' => '014',
                                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['end_record_error'],
                                    'line' => $line_cnt 
                            );
                        }
                        break;
                    default :
                        $error_info [] = array (
                                'error_code' => '015',
                                'error_msg' => ConstantsModel::$errors [$this->current_lang] ['unknown_record_error'],
                                'line' => $line_cnt 
                        );
                        break;
                }
            }
            if (count ( $error_info )) {
                // $this->assignVars ( 'error_info', $error_info );
                // データフォーマットエラー
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data' ) );
            }
            
            // -----------------------------------------------------------------
            // 処理対象ファイルかどうかチェック
            // -----------------------------------------------------------------
            $target_file_path = sprintf ( "%spschool_%d/upload/%s", ZENGIN_DIR, $pschool_id, $file_name );
            if (file_exists ( $target_file_path )) {
                $error_info [] = array (
                        'error_code' => '017',
                        'error_msg' => ConstantsModel::$errors [$this->current_lang] ['file_is_processed'] 
                );
                // $this->assignVars ( 'error_info', $error_info );
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data' ) );
            }
            
            $RequestRow = InvoiceRequestTable::getinstance ()->getRow ( $where = array (
                    "pschool_id" => session ( 'school.login' ) ['id'],
                    'processing_filename' => $file_name,
                    'status_flag = 1 OR status_flag = 2' 
            ) );
            if (empty ( $RequestRow ) || count ( $RequestRow ) < 1) {
                $error_info [] = array (
                        'error_code' => '016',
                        'error_msg' => ConstantsModel::$errors [$this->current_lang] ['file_is_not_processed'] 
                );
                // $this->assignVars ( 'error_info', $error_info );
                // データフォーマットエラー
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data' ) );
            }
            
            // (3)対象塾のデータかチェック（銀行コードと口座番号）
            $bankRows = PschoolBankAccountTable::getInstance ()->getList ( $where = array (
                    'pschool_id' => $pschool_id,
                    'delete_date IS NULL' 
            ) );
            
            $bankRows_id = 0;
            foreach ( $bankRows as $bank_item ) {
                if ($bank_item ['bank_type'] == 1) {
                    if ($header_record [8] == $bank_item ['bank_code'] && $header_record [13] == $bank_item ['bank_account_number']) {
                        $bankRows_id = $bank_item ['id'];
                        break;
                    }
                } else {
                    if ($header_record [8] == 9900 && $header_record [13] == mb_substr ( $bank_item ['post_account_number'], 0, 7 )) {
                        $bankRows_id = $bank_item ['id'];
                        break;
                    }
                }
            }
            if ($bankRows_id < 1) {
                // 対象塾のデータではない
                $error_info [] = array (
                        'error_code' => '021',
                        'error_msg' => ConstantsModel::$errors [$this->current_lang] ['data_other_school_error'] 
                );
                // $this->assignVars ( 'error_info', $error_info );
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data' ) );
            }
            
            // (4)トレーラーレコード
            if ($trailer_record [30] != count ( $data_record )) {
                // データ件数不一致
                $error_info [] = array (
                        'error_code' => '022',
                        'error_msg' => ConstantsModel::$errors [$this->current_lang] ['trailer_record_number_mismatch'] 
                );
                // $this->assignVars ( 'error_info', $error_info );
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data' ) );
            }
            
            // 引落日
            $withdrawal_date = mb_substr ( $header_record [7], 0, 2 ) . "-" . mb_substr ( $header_record [7], 2, 2 );
            $withdrawal_date = date ( 'Y-' ) . $withdrawal_date;
            
            // -----------------------------------------------------------------
            // 処理結果表示
            // -----------------------------------------------------------------
            $data_disp_data = array ();
            // (5)データの突き合せ
            foreach ( $data_record as $data_item ) {
                
                // invoice_requestテーブル
                $request_str = mb_substr ( $data_item [26], 5, 15 );
                $request_str = ltrim ( $request_str, "0" );
                $request_id = intval ( $request_str, 10 );
                
                $total_amount = 0;
                $header_ids = array ();
                // 対象データ存在 invoice_request検索
                $request_rec = InvoiceRequestTable::getinstance ()->getList ( $where = array (
                        'processing_filename' => $file_name,
                        'request_id' => $request_id,
                        '(status_flag = 1 OR status_flag = 2)' 
                ), $order = array (
                        'id' 
                ) );
                if (! empty ( $request_rec ) && count ( $request_rec ) > 0) {
                    // 対象あり
                    // 振込金額が同じかチェックする
                    foreach ( $request_rec as $request_item ) {
                        $total_amount += intval ( $request_item ['amount'], 10 );
                        $header_ids [] = $request_item ['invoice_header_id'];
                    }
                    if ($total_amount != intval ( $data_item [24], 10 )) {
                        // 引落金額が合わない
                        $error_info [] = array (
                                'error_code' => '032',
                                'error_msg' => ConstantsModel::$errors [$this->current_lang] ['withdrawal_amount_difference'] . 'request_id:' . $request_id . ':' . $total_amount . ":" . intval ( $data_item [24], 10 ) 
                        );
                        // $this->assignVars ( 'error_info', $error_info );
                        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                        return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data', 'withdrawal_date' ) );
                    }
                } else {
                    // 対象レコードなし
                    $error_info [] = array (
                            'error_code' => '031',
                            'error_msg' => ConstantsModel::$errors [$this->current_lang] ['target_data_not_exist'] . $request_id 
                    );
                    continue;
                }
                
                foreach ( $header_ids as $header_id ) {
                    // invoice_headerテーブルとinvoice_itemテーブル
                    $invoiceRows = InvoiceHeaderTable::getInstance ()->getInvoiceHeader_Item ( $pschool_id, $header_id );
                    
                    $disp_data = array ();
                    $bFirst = true;
                    foreach ( $invoiceRows as $row_item ) {
                        if ($bFirst) {
                            $disp_data ['id'] = $row_item ['id'];
                            $disp_data ['parent_id'] = $row_item ['parent_id'];
                            $disp_data ['parent_name'] = $row_item ['parent_name'];
                            $disp_data ['result_code'] = $data_item [27];
                            $disp_data ['result_msg'] = ConstantsModel::$zengin_status [session ( 'school.login' ) ['language']] [(intval ( $data_item [27], 10 ) * - 1)];
                            $disp_data ['amount'] = intval ( $data_item [24], 10 );
                            // $disp_data['invoice_year_month'] = sprintf("%s-%02d", mb_substr($row_item['invoice_year_month'], 0,4), intval(mb_substr($row_item['invoice_year_month'], 4)));
                            $disp_data ['invoice_year_month'] = sprintf ( "%s-%s", mb_substr ( $row_item ['invoice_year_month'], 0, 4 ), mb_substr ( $row_item ['invoice_year_month'], 5, 2 ) );
                            $bFirst = false;
                            
                            $disp_data ['active_flag'] = $row_item ['active_flag'];
                            $disp_data ['workflow_status'] = $row_item ['workflow_status'];
                            $disp_data ['invoice_type'] = $row_item ['invoice_type'];
                            $disp_data ['parent_invoice_type'] = $row_item ['parent_invoice_type'];
                            $disp_data ['register_date'] = $row_item ['register_date'];
                        }
                        
                        $item_data = array ();
                        $item_data ['student_id'] = $row_item ['student_id'];
                        $item_data ['student_no'] = $row_item ['student_no'];
                        $item_data ['student_name'] = $row_item ['student_name'];
                        $categorys = ConstantsModel::$dispSchoolCategory;
                        $item_data ['school_category'] = empty ( $row_item ['school_category'] ) ? '' : $categorys [$row_item ['school_category']];
                        $item_data ['school_year'] = empty ( $row_item ['school_year'] ) ? '' : $row_item ['school_year'] . ConstantsModel::$header [1] ['year'];
                        
                        $disp_data ['item'] [] = $item_data;
                    }
                }
                
                $data_disp_data [] = $disp_data;
            }
            $data_record = $data_disp_data;
            
            // (6)トレーラレコード編集
            $trailer_disp_data = array ();
            $trailer_disp_data ['total_cnt'] = intval ( $trailer_record [30], 10 );
            $trailer_disp_data ['amount'] = intval ( $trailer_record [31], 10 );
            $trailer_disp_data ['success_cnt'] = intval ( $trailer_record [32], 10 );
            $trailer_disp_data ['success_amout'] = intval ( $trailer_record [33], 10 );
            $trailer_disp_data ['fail_cnt'] = intval ( $trailer_record [34], 10 );
            $trailer_disp_data ['fail_ammount'] = intval ( $trailer_record [35], 10 );
            $trailer_record = $trailer_disp_data;
            
            // (7)エラー
            // $this->assignVars ( 'error_info', $error_info );
            
            if (count ( $error_info ) < 1) {
                $next_proc = 1;
            }
        } else {
            $next_proc = 0;
        }
        
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
        return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'this_screen', 'heads', 'upload_data', 'withdrawal_date', 'data_record', 'trailer_record', 'next_proc' ) );
    }
    /*
     * 全銀データアップロード完了
     */
    public function executeUploadComplete(Request $request) {
        $lan = $this->lan;
        $error_info = array ();
        
        if (! isset ( session ( self::SESSION_UPLOAD_FILE ) ['fullpath'] )) {
            $error_info [] = array (
                    'error_code' => '041',
                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['target_upload_file_not_exist'] 
            );
            // $this->assignVars ( 'error_info', $error_info );
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info' ) );
        }
        
        $pschool_id = session ( 'school.login' ) ['id'];
        // ---------------------------------------------------------------------
        // アップロードファイルにエラーがない前提で、処理を行います
        // ---------------------------------------------------------------------
        $full_path = session ( self::SESSION_UPLOAD_FILE ) ['fullpath'];
        $file_name = session ( self::SESSION_UPLOAD_FILE ) ['name'];
        // (1)ファイルの読み込み
        $file = fopen ( $full_path, "r" );
        if (! $file) {
            // ファイルオープンエラー たぶんないと思うが
            $error_info [] = array (
                    'error_code' => '003',
                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['file_open_error'] 
            );
            // $this->assignVars ( 'error_info', $error_info );
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info' ) );
        }
        
        $line_cnt = 0;
        $data_record = array ();
        while ( $f_data = fgets ( $file ) ) {
            $line_cnt ++;
            
            // SHISからUTF-8へ変換
            $f_data = mb_convert_encoding ( $f_data, "UTF-8", "SJIS" );
            
            // データ区分取得
            $data_type = mb_substr ( $f_data, 0, 1 );
            switch ($data_type) {
                case 1 :
                    $header_record = $this->getSplitUploadFile ( $f_data, $data_type ); // ヘッダーレコードのマッピング
                    if (empty ( $header_record )) {
                        $error_info [] = array (
                                'error_code' => '011',
                                'error_msg' => ConstantsModel::$errors [$this->current_lang] ['header_record_error'],
                                'line' => $line_cnt 
                        );
                    }
                    break;
                case 2 :
                    $data_record_data = $this->getSplitUploadFile ( $f_data, $data_type ); // データレコードのマッピング
                    if (empty ( $data_record_data )) {
                        $error_info [] = array (
                                'error_code' => '012',
                                'error_msg' => ConstantsModel::$errors [$this->current_lang] ['data_record_error'],
                                'line' => $line_cnt 
                        );
                    }
                    $data_record [] = $data_record_data;
                    break;
                case 8 :
                    $trailer_record = $this->getSplitUploadFile ( $f_data, $data_type ); // トレーラーレコードのマッピング
                    if (empty ( $trailer_record )) {
                        $error_info [] = array (
                                'error_code' => '013',
                                'error_msg' => ConstantsModel::$errors [$this->current_lang] ['trailer_record_error'],
                                'line' => $line_cnt 
                        );
                    }
                    break;
                case 9 :
                    $end_record = $this->getSplitUploadFile ( $f_data, $data_type ); // エンドレコードのマッピング
                    if (empty ( $end_record )) {
                        $error_info [] = array (
                                'error_code' => '014',
                                'error_msg' => ConstantsModel::$errors [$this->current_lang] ['end_record_error'],
                                'line' => $line_cnt 
                        );
                    }
                    break;
                default :
                    $error_info [] = array (
                            'error_code' => '015',
                            'error_msg' => ConstantsModel::$errors [$this->current_lang] ['unknown_record_error'],
                            'line' => $line_cnt 
                    );
                    break;
            }
            
            if (count ( $error_info ) > 0) {
                // $this->assignVars ( 'error_info', $error_info );
                // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info' ) );
            }
        }
        
        // -----------------------------------------------------------------
        // 処理対象ファイルかどうかチェック
        // -----------------------------------------------------------------
        $target_file_path = sprintf ( "%spschool_%d/upload/%s", ZENGIN_DIR, $pschool_id, $file_name );
        if (file_exists ( $target_file_path )) {
            $error_info [] = array (
                    'error_code' => '017',
                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['file_is_processed'] 
            );
            // $this->assignVars ( 'error_info', $error_info );
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info' ) );
        }
        
        $RequestRow = InvoiceRequestTable::getinstance ()->getRow ( $where = array (
                "pschool_id" => session ( 'school.login' ) ['id'],
                'processing_filename' => $file_name,
                'status_flag = 1 OR status_flag = 2' 
        ) );
        if (empty ( $RequestRow ) || count ( $RequestRow ) < 1) {
            $error_info [] = array (
                    'error_code' => '016',
                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['file_is_not_processed'] 
            );
            // $this->assignVars ( 'error_info', $error_info );
            // データフォーマットエラー
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info' ) );
        }
        
        // (3)対象塾のデータかチェック（銀行コードと口座番号）
        $bankRows = PschoolBankAccountTable::getInstance ()->getList ( $where = array (
                'pschool_id' => $pschool_id,
                'delete_date IS NULL' 
        ) );
        
        $bankRows_id = 0;
        foreach ( $bankRows as $bank_item ) {
            if ($bank_item ['bank_type'] == 1) {
                if ($header_record [8] == $bank_item ['bank_code'] && $header_record [13] == $bank_item ['bank_account_number']) {
                    $bankRows_id = $bank_item ['id'];
                    break;
                }
            } else {
                if ($header_record [8] == 9900 && $header_record [13] == mb_substr ( $bank_item ['post_account_number'], 0, 7 )) {
                    $bankRows_id = $bank_item ['id'];
                    break;
                }
            }
        }
        
        if ($bankRows_id < 1) {
            // 対象塾のデータではない
            $error_info [] = array (
                    'error_code' => '021',
                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['data_other_school_error'] 
            );
            // $this->assignVars ( 'error_info', $error_info );
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info' ) );
        }
        
        // (4)トレーラーレコード
        if ($trailer_record [30] != count ( $data_record )) {
            // データ件数不一致
            $error_info [] = array (
                    'error_code' => '022',
                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['trailer_record_number_mismatch'] 
            );
            // $this->assignVars ( 'error_info', $error_info );
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info' ) );
        }
        
        // 引落日
        $withdrawal_date = mb_substr ( $header_record [7], 0, 2 ) . "-" . mb_substr ( $header_record [7], 2, 2 );
        $withdrawal_date = date ( 'Y-' ) . $withdrawal_date;
        
        // -----------------------------------------------------------------
        // 処理結果表示
        // -----------------------------------------------------------------
        $data_disp_data = array ();
        // (5)データの突き合せ
        
        $requestTable = InvoiceRequestTable::getInstance ();
        $headTable = InvoiceHeaderTable::getInstance ();
        
        $headTable->beginTransaction ();
        
        try {
            
            foreach ( $data_record as $data_item ) {
                
                // invoice_requestテーブル
                $request_str = mb_substr ( $data_item [26], 5, 15 );
                $request_str = ltrim ( $request_str, "0" );
                $request_id = intval ( $request_str, 10 );
                
                $total_amount = 0;
                $header_ids = array ();
                // 対象データ存在 invoice_request検索
                $request_rec = InvoiceRequestTable::getinstance ()->getList ( $where = array (
                        'processing_filename' => $file_name,
                        'request_id' => $request_id,
                        '(status_flag = 1 OR status_flag = 2)' 
                ), $order = array (
                        'id' 
                ) );
                if (! empty ( $request_rec ) && count ( $request_rec ) > 0) {
                    // 対象あり
                    // 振込金額が同じかチェックする
                    foreach ( $request_rec as $request_item ) {
                        $total_amount += intval ( $request_item ['amount'] );
                        $header_ids [] = $request_item ['invoice_header_id'];
                    }
                    if ($total_amount != intval ( $data_item [24] )) {
                        // 引落金額が合わない
                        $headTable->rollBack ();
                        $error_info [] = array (
                                'error_code' => '032',
                                'error_msg' => ConstantsModel::$errors [$this->current_lang] ['withdrawal_amount_difference'] . 'request_id:' . $request_id . ':' . $total_amount . ":" . intval ( $data_item [24] ) 
                        );
                        // $this->assignVars ( 'error_info', $error_info );
                        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                        return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'withdrawal_date' ) );
                    }
                } else {
                    // 対象レコードなし
                    $headTable->rollBack ();
                    $error_info [] = array (
                            'error_code' => '031',
                            'error_msg' => ConstantsModel::$errors [$this->current_lang] ['target_data_not_exist'] . $request_id 
                    );
                    // $this->assignVars ( 'error_info', $error_info );
                    // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
                    return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'withdrawal_date' ) );
                }
                
                foreach ( $header_ids as $header_id ) {
                    // invoice_headerテーブルとinvoice_itemテーブル
                    $invoiceRows = InvoiceHeaderTable::getInstance ()->getInvoiceHeader_Item ( $pschool_id, $header_id );
                    
                    $disp_data = array ();
                    $bFirst = true;
                    foreach ( $invoiceRows as $row_item ) {
                        if ($bFirst) {
                            $disp_data ['parent_id'] = $row_item ['parent_id'];
                            $disp_data ['parent_name'] = $row_item ['parent_name'];
                            $disp_data ['result_code'] = $data_item [27];
                            $disp_data ['result_msg'] = ConstantsModel::$zengin_status [session ( 'school.login' ) ['language']] [(intval ( $data_item [27], 10 ) * - 1)];
                            $disp_data ['amount'] = intval ( $data_item [24], 10 );
                            // $disp_data['invoice_year_month'] = sprintf("%s-%02d", mb_substr($row_item['invoice_year_month'], 0,4), intval(mb_substr($row_item['invoice_year_month'], 4)));
                            $disp_data ['invoice_year_month'] = sprintf ( "%s-%s", mb_substr ( $row_item ['invoice_year_month'], 0, 4 ), mb_substr ( $row_item ['invoice_year_month'], 4, 2 ) );
                            $bFirst = false;
                        }
                        
                        $item_data = array ();
                        $item_data ['student_id'] = $row_item ['student_id'];
                        $item_data ['student_no'] = $row_item ['student_no'];
                        $item_data ['student_name'] = $row_item ['student_name'];
                        $categorys = ConstantsModel::$dispSchoolCategory;
                        $item_data ['school_category'] = $categorys [$row_item ['school_category']];
                        $item_data ['school_year'] = $row_item ['school_year'] . ConstantsModel::$header [1] ['year'];
                        
                        $disp_data ['item'] [] = $item_data;
                        
                        // -----------------------------------------------------
                        // 情報更新 invoice_header
                        // -----------------------------------------------------
                        $invoiceRow = $headTable->getRow ( $where = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                'id' => $row_item ['id'] 
                        ) );
                        if ($data_item [27] == 0) {
                            $invoiceRow ['is_recieved'] = 1; // 入金済み
                                                             // $invoiceRow['is_requested'] = 3; //
                            $invoiceRow ['paid_date'] = date ( 'Y-' ) . $withdrawal_date;
                            $invoiceRow ['update_date'] = date ( 'Y-m-d H:i:s' );
                            $invoiceRow ['invoice_type'] = 2; // 口座引落
                            $invoiceRow ['workflow_status'] = 31; // 入金済み
                        } else if ($invoiceRow ['is_recieved'] == 1 && $invoiceRow ['paid_date'] != null) {
                            // 引落前に入金済み
                            
                            // 何も処理しない
                        } else {
                            // 引落できなかった
                            $invoiceRow ['is_recieved'] = intval ( $data_item [27], 10 ) * - 1;
                            // $invoiceRow['is_requested'] = 3;
                            $invoiceRow ['update_date'] = date ( 'Y-m-d H:i:s' );
                            $invoiceRow ['workflow_status'] = 29; // 口座振替未完了
                        }
                        $headTable->updateRow ( $invoiceRow, $where = array (
                                'id' => $invoiceRow ['id'] 
                        ) );
                    }
                    
                    $data_disp_data [] = $disp_data;
                }
                
                $data_record = $data_disp_data;
                
                // -------------------------------------------------------------
                // 情報更新 invoice_request
                // -------------------------------------------------------------
                foreach ( $request_rec as $request_item ) {
                    $requestRow = $requestTable->getRow ( $where = array (
                            "pschool_id" => session ( 'school.login' ) ['id'],
                            'id' => $request_item ['id'] 
                    ) );
                    if (intval ( $data_item [27], 10 ) == 0) {
                        $requestRow ['status_flag'] = 3;
                    } else {
                        $requestRow ['status_flag'] = intval ( $data_item [27], 10 ) * - 1;
                    }
                    $requestRow ['update_date'] = date ( 'Y-m-d H:i:s' );
                    $requestTable->updateRow ( $requestRow, $where = array (
                            'id' => $requestRow ['id'] 
                    ) );
                }
            }
        } catch ( exception $exp ) {
            $headTable->rollBack ();
            
            // 書き込みエラー発生
            $error_info [] = array (
                    'error_code' => '032',
                    'error_msg' => ConstantsModel::$errors [$this->current_lang] ['data_writing_error'] 
            );
            // $this->assignVars ( 'error_info', $error_info );
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'withdrawal_date', 'data_record' ) );
        }
        
        $headTable->commit ();
        
        $data_record = $data_disp_data;
        
        // (6)トレーラレコード編集
        $trailer_disp_data = array ();
        $trailer_disp_data ['total_cnt'] = intval ( $trailer_record [30], 10 );
        $trailer_disp_data ['amount'] = intval ( $trailer_record [31], 10 );
        $trailer_disp_data ['success_cnt'] = intval ( $trailer_record [32], 10 );
        $trailer_disp_data ['success_amout'] = intval ( $trailer_record [33], 10 );
        $trailer_disp_data ['fail_cnt'] = intval ( $trailer_record [34], 10 );
        $trailer_disp_data ['fail_ammount'] = intval ( $trailer_record [35], 10 );
        $trailer_record = $trailer_disp_data;
        
        // ---------------------------------------------------------------------
        // アップロードファイルを既定の場所に保存
        // data/zengin/pschool_xxxxx/upload/xxxxx.xxx
        // ---------------------------------------------------------------------
        // フォルダが存在するか pschool_xxxxx
        $path1 = sprintf ( "%spschool_%d", ZENGIN_DIR, $pschool_id );
        if (! file_exists ( $path1 )) {
            @mkdir ( $path1 );
            $path2 = sprintf ( "%s/upload", $path1 );
            @mkdir ( $path2 );
        } else {
            // フォルダが存在するか upload
            $path2 = sprintf ( "%s/upload", $path1 );
            if (! file_exists ( $path2 )) {
                @mkdir ( $path2 );
            }
        }
        $upload_path = sprintf ( "%s/%s", $path2, session ( self::SESSION_UPLOAD_FILE ) ['name'] );
        @copy ( $full_path, $upload_path );
        
        // セッション破棄
        session_unset ( self::SESSION_UPLOAD_FILE );
        
        // $this->assignVars ( 'error_info', $error_info );
        
        if (count ( $error_info ) > 0) {
            // $this->assignVars ( 'next_proc', 2 );
            // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'upload.html' );
            return view ( self::$TEMPLATE_URL . 'upload', compact ( 'error_info', 'withdrawal_date', 'data_record', 'trailer_record', 'next_proc' ) );
        } else {
            // 請求書検索画面に遷移する。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    
    /**
     * 口座振替メイン
     */
    public function executeAccountTransfer(Request $request) {
//         $this_screen = 'zengin';
        view()->share('this_screen', 'zengin');
        $pschool_id = session ( 'school.login' ) ['id'];
        
        if (! empty ( $request ['invoice_year_month'] )) {
            $request ['invoice_year_to'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $request ['invoice_year_month'], - 2 );
            $request ['invoice_year_from'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_from'] = substr ( $request ['invoice_year_month'], - 2 );
        } elseif (! isset ( $request ['search'] )) {
            
            // 最新の請求書の年月取得
            $invoice_year_month = InvoiceHeaderTable::getInstance ()->getNewestYearMonth ( session ( 'school.login' ) ['id'] );
            $request ['invoice_year_to'] = substr ( $invoice_year_month ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $invoice_year_month ['invoice_year_month'], 5, 2 );
        }
        
        $transfer = InvoiceRequestTable::getInstance ()->getAccountTranserList ( $pschool_id, $request );
        $transfer_list = $transfer;
        
        // menu_header
        if (self::$TEMPLATE_URL == 'invoice/') {
            // 運用区分が塾の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . "/accounttransfer", ConstantsModel::$bread_list [$this->current_lang] ['account_transfer'] );
            $this->set_history ( 0, self::$ACTION_URL . "/accounttransfer" );
        } else {
            // 請求書のタイトル
            $heads = InvoiceHeaderTable::getInstance ()->getAxisInvoiceList ( session ( 'school.login' ), $request );
            $heads = $heads [0];
            
            // 運用区分が塾以外の場合
            $this->clear_bread_list ();
            $this->set_bread_list ( self::$ACTION_URL . '/list?back', ConstantsModel::$bread_list [$this->current_lang] ['invoice'] );
            $this->set_bread_list ( self::$ACTION_URL . '/search?simple&search_cond=2&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to_s=' . $heads [0] ['invoice_year'] . '&invoice_month_to_s=' . $heads [0] ['invoice_month'] . '&invoice_year_from_s=' . $heads [0] ['invoice_year'] . '&invoice_month_from_s=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['invoice_list'] );
            $this->set_bread_list ( self::$ACTION_URL . '/accounttransfer?search&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'], ConstantsModel::$bread_list [$this->current_lang] ['account_transfer'] );
            $this->set_history ( 0, self::$ACTION_URL . '/accounttransfer?search&invoice_year_month=' . $heads [0] ['invoice_year_month'] . '&invoice_year_to=' . $heads [0] ['invoice_year'] . '&invoice_month_to=' . $heads [0] ['invoice_month'] . '&invoice_year_from=' . $heads [0] ['invoice_year'] . '&invoice_month_from=' . $heads [0] ['invoice_month'] );
        }
        
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'account_transfer.html' );
//         $lan = $this->lan;
        return view ( self::$TEMPLATE_URL . 'account_transfer', compact ( 'transfer_list', 'heads' ) );
    }
    
    /**
     * 取消処理
     */
    public function executeCancelTransfer(Request $request) {
//         $this_screen = 'zengin';
        view()->share('this_screen', 'zengin');
        if (! empty ( $request ['invoice_year_month'] )) {
            $request ['invoice_year_to'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_to'] = substr ( $request ['invoice_year_month'], - 2 );
            $request ['invoice_year_from'] = substr ( $request ['invoice_year_month'], 0, 4 );
            $request ['invoice_month_from'] = substr ( $request ['invoice_year_month'], - 2 );
        }
        
        $pschool_id = session ( 'school.login' ) ['id'];
        $processing_file_name = $request ['file_name'];
        
        // ステータスを取消に変更
        InvoiceRequestTable::getInstance ()->setCancelStatusFlag ( $pschool_id, $processing_file_name );
        
        $this->set_message ( "OK", ConstantsModel::$invoice_message [$this->current_lang] ['cancel_account_transfer'] );
        
        $this->redirect_history ( 0 );
        
        // 再読み込み
    }
    
    /*
     * 請求日の取得
     */
    private function getInvoiceDueDate($due_date) {
        if ($due_date == 99) {
            // 末日のとき
            $due_date = date ( 't' );
        }
        $today = date ( 'j' );
        // 本日との比較
        $month = date ( 'n' );
        $year = date ( 'Y' );
        if ($today > $due_date) {
            $month ++;
            if ($month > 12) {
                $month = 1;
                $year ++;
            }
        }
        
        $date = array (
                'year' => $year,
                'month' => $month,
                'day' => $due_date 
        );
        
        return $date;
    }
    
    /**
     * 現金・振込の支払期限日取得
     *
     * @param unknown $invoice_year_month            
     * @param unknown $pschool            
     */
    private function getInvoiceDueDate2($invoice_year_month, $pschool) {
        if ($pschool ['payment_style'] == 1) {
            // 先払い
            $year_month = date ( 'Y-m-d', strtotime ( $invoice_year_month . "-01" . " -1 month" ) );
        } else {
            $year_month = $invoice_year_month . "-01";
        }
        if ($pschool ['payment_date'] == 99) {
            $last_day = date ( 't', strtotime ( $year_month ) );
            $split = explode ( '-', $year_month );
            $year_month = $split [0] . "-" . $split [1] . "-" . $last_day;
        } else {
            $split = explode ( '-', $year_month );
            $year_month = $split [0] . "-" . $split [1] . "-" . sprintf ( "%02d", $pschool ['payment_date'] );
        }
        return $year_month;
    }
    
    /*
     * 請求書新規作成時のパラメーターチェック処理
     */
    private function checkEntryParam(Request $request, $mode = NULL) {
        if (! isset ( $request ["parent_id"] ) || ! strlen ( $request ["parent_id"] )) {
            // TOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        $parentStudent = ParentTable::getInstance ()->getParentStudentListById ( session ( 'school.login' ) ['id'], $request ["parent_id"] );
        if (empty ( $parentStudent )) {
            // 存在しない保護者なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        if (! isset ( $request ["invoice_year_month"] ) || ! strlen ( $request ["invoice_year_month"] )) {
            // 請求月が不正なのでTOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        // 当月分の請求書が存在する場合はメッセージを表示する。
        if ($mode != "complete") {
            $header = InvoiceHeaderTable::getInstance ()->getRow ( array (
                    "pschool_id" => session ( 'school.login' ) ['id'],
                    "parent_id" => $request ["parent_id"],
                    "invoice_year_month" => $request ["invoice_year_month"],
                    "active_flag" => 1,
                    "delete_date is null" 
            ) );
            if (! empty ( $header )) {
                view ()->share ( 'is_invoice_exist', true );
            }
        }
        
        return $parentStudent;
    }
    private function checkEditParam(Request $request) {
        if (! isset ( $request ["id"] ) || ! strlen ( $request ["id"] )) {
            // TOPに戻す。
            // HeaderUtil::redirect($this->get_app_path() . self::$TOP_URL);
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        $header = InvoiceHeaderTable::getInstance ()->getRow ( array (
                "id" => $request ["id"],
                "pschool_id" => session ( 'school.login' ) ['id'],
                // "active_flag" => 1,
                "delete_date is null" 
        ) );
        if (empty ( $header )) {
            // 存在しない請求書なのでTOPに戻す。
            // HeaderUtil::redirect($this->get_app_path() . self::$TOP_URL);
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
        
        return $header;
    }
    
    /*
     * DBからとってきたデータを画面表示用に加工する。
     */
    private function setFormDataFromDb(Request $request, $header, $parentStudent) {
        $request ["invoice_year_month"] = $header ["invoice_year_month"];
        $invoice_year = ! isset ( $request ["invoice_year"] ) ? substr ( $header ["invoice_year_month"], 0, 4 ) : $request ["invoice_year"];
        $invoice_month = ! isset ( $request ["invoice_month"] ) ? substr ( $header ["invoice_year_month"], 5, 2 ) : $request ["invoice_month"];
        $request ["invoice_year"] = $invoice_year;
        $request ["invoice_month"] = $invoice_month;
        $request ['invoice_year_to'] = $invoice_year;
        $request ['invoice_month_to'] = $invoice_month;
        $request ['invoice_year_from'] = $invoice_year;
        $request ['invoice_month_from'] = $invoice_month;
        
        $invoice_due_year = ! isset ( $request ["invoice_due_year"] ) ? substr ( $header ["due_date"], 0, 4 ) : $request ["invoice_due_year"];
        $invoice_due_month = ! isset ( $request ["invoice_due_month"] ) ? abs ( substr ( $header ["due_date"], 5, 2 ) ) : $request ["invoice_due_month"];
        $invoice_due_day = ! isset ( $request ["invoice_due_day"] ) ? abs ( substr ( $header ["due_date"], 8, 2 ) ) : $request ["invoice_due_day"];
        $request ["invoice_due_year"] = $invoice_due_year;
        $request ["invoice_due_month"] = $invoice_due_month;
        $request ["invoice_due_day"] = $invoice_due_day;
        
        $item_list = InvoiceItemTable::getInstance ()->getList ( array (
                'invoice_id' => $request ["id"],
                'delete_date IS NULL' 
        ), array (
                "class_id",
                "course_id",
                "item_name",
                "unit_price" 
        ) );
        $request ["active_student_id"] = $parentStudent ["student_list"] [0] ["id"];
        $request ["parent_memo"] = $parentStudent ["memo"];
        
        $request ["amount_display_type"] = $header ["amount_display_type"];
        $request ["sales_tax_rate"] = $header ["sales_tax_rate"];
        $request ["mail_announce"] = $header ["mail_announce"];
        $request ["is_established"] = $header ["is_established"];
        $request ["tbl_is_established"] = $header ["is_established"];
        $request ["is_recieved"] = $header ["is_recieved"];
        $request ["active_flag"] = $header ["active_flag"];
        $request ["workflow_status"] = $header ["workflow_status"];
        if (empty ( $request ["sales_tax_rate"] ))
            $request ["sales_tax_rate"] = 0;
        
        $request ["due_date"] = $header ["due_date"];
        $announced_date = empty ( $header ['announced_date'] ) ? date ( 'Y-m-d' ) : date ( 'Y-m-d', strtotime ( $header ['announced_date'] ) );
        $request ["now_date_jp"] = $announced_date; /* $this->convJpDate($announced_date); */
        $request ["due_date_jp"] = $request ["due_date"]; /* $this->convJpDate($request["due_date"]); */
        $request ["sales_tax_disp"] = $request ["sales_tax_rate"] * 100;
        $request ["invoice_paid_type"] = empty($header ["invoice_type"]) ? "2" : $header ["invoice_type"];
        $request ["invoice_paid_date"] = $header ["paid_date"];
        
        $pbank = PschoolBankAccountTable::getInstance ()->getActiveRow ( $where = array (
                'pschool_id' => session ( 'school.login' ) ['id'] 
        ) );
        if (! empty ( $pbank ) && $pbank ['bank_type'] == 1) {
            $bank_type = ($pbank ['bank_account_type'] == 1) ? ConstantsModel::$type_of_bank_account [$this->current_lang] ['1'] : ConstantsModel::$type_of_bank_account [$this->current_lang] ['2'];
            $request ["pbank_info"] = $pbank ['bank_name'] . " " . $pbank ['branch_name'] . " " . $bank_type . " " . $pbank ['bank_account_number'] . " " . $pbank ['bank_account_name_kana'];
        } elseif (! empty ( $pbank )) {
            $request ["pbank_info"] = ConstantsModel::$form_keys [$this->current_lang] ['jp_post_bank'] . " " . $pbank ['post_account_kigou'] . " " . $pbank ['post_account_number'] . " " . $pbank ['post_account_name'];
        }
        
        // 割引
        $arr ["discount_id"] = array ();
        $arr ["discount_name"] = array ();
        $arr ["discount_price"] = array ();
        // プラン
        $arr ["class_id"] = array ();
        $arr ["class_name"] = array ();
        $arr ["class_price"] = array ();
        // イベント
        $arr ["course_id"] = array ();
        $arr ["course_name"] = array ();
        $arr ["cource_price"] = array ();
        // プログラム
        $arr ["program_id"] = array ();
        $arr ["program_name"] = array ();
        $arr ["program_price"] = array ();
        // 割増
        $arr ["custom_item_id"] = array ();
        $arr ["custom_item_name"] = array ();
        $arr ["custom_item_price"] = array ();
        foreach ( $parentStudent ["student_list"] as $k => $v ) {
            // プラン
            $arr ["class_id"] [$v ["id"]] = array ();
            $arr ["class_name"] [$v ["id"]] = array ();
            $arr ["class_price"] [$v ["id"]] = array ();
            // イベント
            $arr ["course_id"] [$v ["id"]] = array ();
            $arr ["course_name"] [$v ["id"]] = array ();
            $arr ["cource_price"] [$v ["id"]] = array ();
            // プログラム
            $arr ["program_id"] [$v ["id"]] = array ();
            $arr ["program_name"] [$v ["id"]] = array ();
            $arr ["program_price"] [$v ["id"]] = array ();
            // 割増
            $arr ["custom_item_id"] [$v ["id"]] = array ();
            $arr ["custom_item_name"] [$v ["id"]] = array ();
            $arr ["custom_item_price"] [$v ["id"]] = array ();
        }
        
        $sum_discount_price = 0;
        $amount = 0;
        foreach ( $item_list as $item ) {
            if (! strlen ( $item ["student_id"] )) {
                $arr ["discount_id"] [] = $item ["invoice_adjust_name_id"];
                $arr ["discount_name"] [] = $item ["item_name"];
                if (session ( 'school.login' ) ['country_code'] == 81) {
                    $item ["unit_price"] = floor ( $item ["unit_price"] );
                }
                $arr ["discount_price"] [] = str_replace ( "-", "", $item ["unit_price"] );
                $sum_discount_price += intval ( $item ["unit_price"] );
            } else if (strlen ( $item ["class_id"] )) {
                $arr ["class_id"] [$item ["student_id"]] [] = $item ["class_id"];
                $arr ["class_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $arr ["class_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $arr ["_class_except"] [$item ["student_id"]] [] = $item ["except_flag"];
                if (! $item ["except_flag"]) {
                    $amount += $item ["unit_price"];
                }
            } else if (strlen ( $item ["course_id"] )) {
                $arr ["course_id"] [$item ["student_id"]] [] = $item ["course_id"];
                $arr ["course_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $arr ["course_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $arr ["_course_except"] [$item ["student_id"]] [] = $item ["except_flag"];
                if (! $item ["except_flag"]) {
                    $amount += $item ["unit_price"];
                }
            } else if (strlen ( $item ["program_id"] )) {
                $arr ["program_id"] [$item ["student_id"]] [] = $item ["program_id"];
                $arr ["program_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $arr ["program_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $arr ["_program_except"] [$item ["student_id"]] [] = $item ["except_flag"];
                if (! $item ["except_flag"]) {
                    $amount += $item ["unit_price"];
                }
            } else {
                $arr ["custom_item_id"] [$item ["student_id"]] [] = $item ["invoice_adjust_name_id"];
                $arr ["custom_item_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $arr ["custom_item_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $amount += $item ["unit_price"];
            }
        }
        
        // ---------------------------------------------------------------------
        // 割引・割増
        // ---------------------------------------------------------------------
        if (empty ( $arr ["discount_name"] )) {
            $arr ["discount_id"] [] = "";
            $arr ["discount_name"] [] = "";
            $arr ["discount_price"] [] = "";
        }
        
        foreach ( $arr ["custom_item_name"] as $student_id => $custom_item_name_list ) {
            if (empty ( $custom_item_name_list )) {
                $arr ["custom_item_id"] [$student_id] [] = "";
                $arr ["custom_item_name"] [$student_id] [] = "";
                $arr ["custom_item_price"] [$student_id] [] = "";
            }
        }
        
        $arr ["sum_discount_price"] = $sum_discount_price;
        $amount += $sum_discount_price;
        $request ["amount"] = $amount;
        
        $tax_price = 0;
        $amount_tax = 0;
        $sales_tax_rate = floatval ( $request ["sales_tax_rate"] );
        if ($request ["amount_display_type"] == "0") {
            $tax_price = floor ( $amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100) );
            $amount_tax = $amount;
        } else {
            $tax_price = floor ( $amount * $sales_tax_rate );
            $amount_tax = $amount + $tax_price;
        }
        
        $arr ["tax_price"] = $tax_price;
        $arr ["amount_tax"] = $amount_tax;
        // $request["amount_tax"] = money_format('%10n', $amount_tax);
        $request->merge ( $arr );
    }
    
    /*
     * DBからとってきたデータを印刷画面表示用に加工する。
     */
    private function setPrintDataFromDb(Request $request, $header, $parentStudent) {
        $item_list = InvoiceItemTable::getInstance ()->getList ( array (
                'invoice_id' => $header ["id"] 
        ), array (
                "class_id",
                "course_id",
                "item_name",
                "unit_price" 
        ) );
        $request ["active_student_id"] = $parentStudent ["student_list"] [0] ["id"];
        $request ["invoice_year_month"] = $header ["invoice_year_month"];
        
        $request ["amount_display_type"] = $header ["amount_display_type"];
        $request ["sales_tax_rate"] = $header ["sales_tax_rate"];
        $request ["mail_announce"] = $header ["mail_announce"];
        $request ["is_established"] = $header ["is_established"];
        $request ["is_recieved"] = $header ["is_recieved"];
        
        $request ["school_name"] = session ( 'school.login' ) ['name'];
        $request ["school_address"] = session ( 'school.login' ) ['pref_name'] . session ( 'school.login' ) ['city_name'] . session ( 'school.login' ) ['address'];
        $request ["school_daihyou"] = session ( 'school.login' ) ['daihyou'];
        
        $publish_year = date ( 'Y' ) - 1988;
        $request ["publish_date_y"] = $publish_year;
        $request ["publish_date_m"] = date ( 'n' );
        $request ["publish_date_d"] = date ( 'j' );
        
        $due_year = substr ( $header ["due_date"], 0, 4 ) - 1988;
        $request ["due_date_y"] = $due_year;
        $due_month = ltrim ( substr ( $header ["due_date"], 5, 2 ), '0' );
        $request ["due_date_m"] = $due_month;
        $due_day = ltrim ( substr ( $header ["due_date"], 8, 2 ), '0' );
        $request ["due_date_d"] = $due_day;
        
        $bank_account = PschoolBankAccountTable::getInstance ()->getRow ( array (
                'pschool_id' => session ( 'school.login' ) ['id'],
                "delete_date IS NULL" 
        ) );
        if (! empty ( $bank_account )) {
            $request ["bank_name"] = $bank_account ['bank_name'];
            $request ["branch_name"] = $bank_account ['branch_name'];
            $request ["bank_account_number"] = ConstantsModel::$type_of_bank_account [session ( 'school.login' ) ['language']] [$bank_account ['bank_account_type']] . " " . $bank_account ['bank_account_number'];
            $request ["bank_account_name"] = $bank_account ['bank_account_name'];
            $request ["bank_account_name_kana"] = $bank_account ['bank_account_name_kana'];
            
            if (! empty ( $bank_account ['post_account_number'] ) && ! empty ( $bank_account ['post_account_name'] )) {
                $request ["post_account_kigou"] = $bank_account ['post_account_kigou'];
                $request ["post_account_number"] = $bank_account ['post_account_number'];
                $request ["post_account_name"] = $bank_account ['post_account_name'];
            }
        }
        // 割引
        $request ["discount_id"] = array ();
        $request ["discount_name"] = array ();
        $request ["discount_price"] = array ();
        // プラン
        $request ["class_id"] = array ();
        $request ["class_name"] = array ();
        $request ["class_price"] = array ();
        // イベント
        $request ["course_id"] = array ();
        $request ["course_name"] = array ();
        $request ["cource_price"] = array ();
        // プログラム
        $request ["program_id"] = array ();
        $request ["program_name"] = array ();
        $request ["program_price"] = array ();
        // 割増
        $request ["custom_item_id"] = array ();
        $request ["custom_item_name"] = array ();
        $request ["custom_item_price"] = array ();
        foreach ( $parentStudent ["student_list"] as $k => $v ) {
            // プラン
            $request ["class_id"] [$v ["id"]] = array ();
            $request ["class_name"] [$v ["id"]] = array ();
            $request ["class_price"] [$v ["id"]] = array ();
            // イベント
            $request ["course_id"] [$v ["id"]] = array ();
            $request ["course_name"] [$v ["id"]] = array ();
            $request ["cource_price"] [$v ["id"]] = array ();
            // プログラム
            $request ["program_id"] [$v ["id"]] = array ();
            $request ["program_name"] [$v ["id"]] = array ();
            $request ["program_price"] [$v ["id"]] = array ();
            // 割増
            $request ["custom_item_id"] [$v ["id"]] = array ();
            $request ["custom_item_name"] [$v ["id"]] = array ();
            $request ["custom_item_price"] [$v ["id"]] = array ();
        }
        
        $sum_discount_price = 0;
        $amount = 0;
        foreach ( $item_list as $item ) {
            if (! strlen ( $item ["student_id"] )) {
                $request ["discount_id"] [] = $item ["invoice_adjust_name_id"];
                $request ["discount_name"] [] = $item ["item_name"];
                $request ["discount_price"] [] = str_replace ( "-", "", $item ["unit_price"] );
                $sum_discount_price += intval ( $item ["unit_price"] );
            } else if (strlen ( $item ["class_id"] )) {
                $request ["class_id"] [$item ["student_id"]] [] = $item ["class_id"];
                $request ["class_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $request ["class_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $amount += $item ["unit_price"];
            } else if (strlen ( $item ["course_id"] )) {
                $request ["course_id"] [$item ["student_id"]] [] = $item ["course_id"];
                $request ["course_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $request ["course_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $amount += $item ["unit_price"];
            } else if (strlen ( $item ["program_id"] )) {
                $request ["program_id"] [$item ["student_id"]] [] = $item ["program_id"];
                $request ["program_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $request ["program_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $amount += $item ["unit_price"];
            } else {
                $request ["custom_item_id"] [$item ["student_id"]] [] = $item ["invoice_adjust_name_id"];
                $request ["custom_item_name"] [$item ["student_id"]] [] = $item ["item_name"];
                $request ["custom_item_price"] [$item ["student_id"]] [] = $item ["unit_price"];
                $amount += $item ["unit_price"];
            }
        }
        
        if (empty ( $request ["discount_name"] )) {
            $request ["discount_id"] [] = "";
            $request ["discount_name"] [] = "";
            $request ["discount_price"] [] = "";
        }
        
        foreach ( $request ["custom_item_name"] as $student_id => $custom_item_name_list ) {
            if (empty ( $custom_item_name_list )) {
                $request ["custom_item_id"] [$student_id] [] = "";
                $request ["custom_item_name"] [$student_id] [] = "";
                $request ["custom_item_price"] [$student_id] [] = "";
            }
        }
        
        $request ["sum_discount_price"] = $sum_discount_price;
        $amount += $sum_discount_price;
        $request ["amount"] = $amount;
        
        $tax_price = 0;
        $amount_tax = 0;
        $sales_tax_rate = floatval ( $request ["sales_tax_rate"] );
        if ($request ["amount_display_type"] == "0") {
            $tax_price = floor ( $amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100) );
            $amount_tax = $amount;
        } else {
            $tax_price = floor ( $amount * $sales_tax_rate );
            $amount_tax = $amount + $tax_price;
        }
        
        $request ["tax_price"] = $tax_price;
        $request ["amount_tax"] = $amount_tax;
    }
    
    /*
     * 請求書登録・更新画面の入力時共通処理
     */
    private function entryInputCommonProcess(Request $request, $parentStudent) {
        $errors = array ();
        $validator = new ValidateRequest ( $this->create_validate_rules ( 'entry' ) );
        if ($validator->isValid2 ( $request )) {
            $errors = $validator->getErrors ();
        }
        
        // 割引額のチェック
        $sum_discount_price = 0;
        $amount = 0;
        if (! empty ( $request ["discount_name"] )) {
            foreach ( $request ["discount_name"] as $k => $v ) {
                $is_discount_id_input = false;
                if (! empty ( $request ["discount_id"] [$k] )) {
                    $is_discount_id_input = true;
                }
                
                $is_discount_name_input = false;
                if (strlen ( $v )) {
                    $is_discount_name_input = true;
                }
                
                $is_discount_price_input = false;
                if (isset ( $request ["discount_price"] [$k] ) && strlen ( $request ["discount_price"] [$k] )) {
                    $is_discount_price_input = true;
                }
                
                if ($is_discount_id_input || $is_discount_name_input || $is_discount_price_input) {
                    if (! $is_discount_name_input && ! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["discount_id"] [$k] )) {
                        $this->setErrorArrayColumn ( $errors, "discount_id", $k, "notEmpty" );
                    }
                    
                    if ($is_discount_name_input && ! Validaters::isValid ( Validaters::NOT_EMPTY, $v )) {
                        $this->setErrorArrayColumn ( $errors, "discount_name", $k, "notEmpty" );
                    }
                    
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["discount_price"] [$k] )) {
                        $this->setErrorArrayColumn ( $errors, "discount_price", $k, "notEmpty" );
                    } else if (! Validaters::isValid ( Validaters::IS_NUMERIC, $request ["discount_price"] [$k] )) {
                        $this->setErrorArrayColumn ( $errors, "discount_price", $k, "isNumeric" );
                    }
                    
                    if (! isset ( $errors ['discount_price'] [$k] )) {
                        $sum_discount_price += $request ["discount_price"] [$k];
                    }
                }
            }
        }
        
        $is_item_error_student_id = NULL;
        // 受講プランの入力チェック
        if (isset ( $request ["class_name"] )) {
            foreach ( $request ["class_name"] as $student_id => $class_name_list ) {
                $is_item_error = false;
                foreach ( $class_name_list as $k => $v ) {
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $v )) {
                        $this->setErrorArrayColumn2 ( $errors, "class_name", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    }
                    
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["class_id"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "class_id", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    }
                    
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["class_price"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "class_price", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    } else if (! Validaters::isValid ( Validaters::IS_NUMERIC, $request ["class_price"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "class_price", $student_id, $k, "isNumeric" );
                        $is_item_error = true;
                    }
                    
                    if (! isset ( $errors ["class_price"] [$student_id] [$k] )) {
                        if (! $request ["_class_except"] [$student_id] [$k]) {
                            $amount += $request ["class_price"] [$student_id] [$k];
                        }
                    }
                }
                if ($is_item_error) {
                    if (is_null ( $is_item_error_student_id )) {
                        $is_item_error_student_id = $student_id;
                    } else {
                        $is_item_error_student_id = $is_item_error_student_id < $student_id ? $is_item_error_student_id : $student_id;
                    }
                }
            }
        }
        
        // イベントの入力チェック
        if (isset ( $request ["course_name"] )) {
            foreach ( $request ["course_name"] as $student_id => $class_name_list ) {
                $is_item_error = false;
                foreach ( $class_name_list as $k => $v ) {
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $v )) {
                        $this->setErrorArrayColumn2 ( $errors, "course_name", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    }
                    
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["course_id"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "course_id", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    }
                    
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["course_price"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "course_price", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    } else if (! Validaters::isValid ( Validaters::IS_NUMERIC, $request ["course_price"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "course_price", $student_id, $k, "isNumeric" );
                        $is_item_error = true;
                    }
                    
                    if (! isset ( $errors ["course_price"] [$student_id] [$k] )) {
                        if (! $request ["_course_except"] [$student_id] [$k]) {
                            $amount += $request ["course_price"] [$student_id] [$k];
                        }
                    }
                }
                if ($is_item_error) {
                    if (is_null ( $is_item_error_student_id )) {
                        $is_item_error_student_id = $student_id;
                    } else {
                        $is_item_error_student_id = $is_item_error_student_id < $student_id ? $is_item_error_student_id : $student_id;
                    }
                }
            }
        }
        
        // プログラムの入力チェック
        if (isset ( $request ["program_name"] )) {
            foreach ( $request ["program_name"] as $student_id => $class_name_list ) {
                $is_item_error = false;
                foreach ( $class_name_list as $k => $v ) {
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $v )) {
                        $this->setErrorArrayColumn2 ( $errors, "program_name", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    }
                    
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["program_id"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "program_id", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    }
                    
                    if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["program_price"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "program_price", $student_id, $k, "notEmpty" );
                        $is_item_error = true;
                    } else if (! Validaters::isValid ( Validaters::IS_NUMERIC, $request ["program_price"] [$student_id] [$k] )) {
                        $this->setErrorArrayColumn2 ( $errors, "program_price", $student_id, $k, "isNumeric" );
                        $is_item_error = true;
                    }
                    
                    if (! isset ( $errors ["program_price"] [$student_id] [$k] )) {
                        if (! $request ["_program_except"] [$student_id] [$k]) {
                            $amount += $request ["program_price"] [$student_id] [$k];
                        }
                    }
                }
                if ($is_item_error) {
                    if (is_null ( $is_item_error_student_id )) {
                        $is_item_error_student_id = $student_id;
                    } else {
                        $is_item_error_student_id = $is_item_error_student_id < $student_id ? $is_item_error_student_id : $student_id;
                    }
                }
            }
        }
        
        // 摘要項目の入力チェック
        if (! empty ( $request ["custom_item_name"] )) {
            foreach ( $request ["custom_item_name"] as $student_id => $class_name_list ) {
                $is_item_error = false;
                foreach ( $class_name_list as $k => $v ) {
                    $is_id_input = false;
                    if (! empty ( $request ["custom_item_id"] [$student_id] [$k] )) {
                        $is_id_input = true;
                    }
                    
                    $is_name_input = false;
                    if (strlen ( $v )) {
                        $is_name_input = true;
                    }
                    
                    $is_price_input = false;
                    if (isset ( $request ["custom_item_price"] [$student_id] [$k] ) && strlen ( $request ["custom_item_price"] [$student_id] [$k] )) {
                        $is_price_input = true;
                    }
                    
                    if ($is_id_input || $is_name_input || $is_price_input) {
                        if (! $is_name_input && ! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["custom_item_id"] [$student_id] [$k] )) {
                            $this->setErrorArrayColumn2 ( $errors, "custom_item_id", $student_id, $k, "notEmpty" );
                            $is_item_error = true;
                        }
                        
                        if ($is_name_input && ! Validaters::isValid ( Validaters::NOT_EMPTY, $v )) {
                            $this->setErrorArrayColumn2 ( $errors, "custom_item_name", $student_id, $k, "notEmpty" );
                            $is_item_error = true;
                        }
                        
                        if (! Validaters::isValid ( Validaters::NOT_EMPTY, $request ["custom_item_price"] [$student_id] [$k] )) {
                            $this->setErrorArrayColumn2 ( $errors, "custom_item_price", $student_id, $k, "notEmpty" );
                            $is_item_error = true;
                        } else if (! Validaters::isValid ( Validaters::IS_NUMERIC, $request ["custom_item_price"] [$student_id] [$k] )) {
                            $this->setErrorArrayColumn2 ( $errors, "custom_item_price", $student_id, $k, "isNumeric" );
                            $is_item_error = true;
                        }
                        
                        if (! isset ( $errors ["custom_item_price"] [$student_id] [$k] )) {
                            $amount += $request ["custom_item_price"] [$student_id] [$k];
                        }
                    }
                }
                if ($is_item_error) {
                    if (is_null ( $is_item_error_student_id )) {
                        $is_item_error_student_id = $student_id;
                    } else {
                        $is_item_error_student_id = $is_item_error_student_id < $student_id ? $is_item_error_student_id : $student_id;
                    }
                }
            }
        }
        
        if (! is_null ( $is_item_error_student_id )) {
            $request ["active_student_id"] = $is_item_error_student_id;
        }
        
        $amount -= $sum_discount_price;
        if ($amount < 0) {
            $errors ['amount'] ['priceZero'] = true;
        }
        
        if (! empty ( $errors )) {
            if ($request ["active_student_id"] == "confirm") {
                $request ["active_student_id"] = $parentStudent ["student_list"] [0] ["id"];
            }
//             $this->assignVars ( 'errors', $errors );
            view()->share( 'errors', $errors );
            return true;
        }
        
        if (! isset ( $request ["is_established"] ) || $request ["is_established"] != "1") {
            $request ["is_established"] = "0";
        }
        
        if (! isset ( $request ["mail_announce"] ) || $request ["mail_announce"] != "1") {
            $request ["mail_announce"] = "0";
        }
        
        $request ["sum_discount_price"] = $sum_discount_price;
        $request ["amount"] = $amount;
        
        $tax_price = 0;
        $amount_tax = 0;
        $sales_tax_rate = floatval ( $request ["sales_tax_rate"] );
        if ($request ["amount_display_type"] == "0") {
            $tax_price = floor ( $amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100) );
            $amount_tax = $amount;
        } else {
            $tax_price = floor ( $amount * $sales_tax_rate );
            $amount_tax = $amount + $tax_price;
        }
        
        $request ["tax_price"] = $tax_price;
        $request ["amount_tax"] = $amount_tax;
        
        $_SESSION [self::SESSION_INPUT_DATA] = $request;
        // dd(session()->get('self::SESSION_INPUT_DATA'));
        // dd($_SESSION [self::SESSION_INPUT_DATA]);
    }
    
    /*
     * 請求書登録・更新画面の完了時共通処理
     */
    private function entryCompleteCommonProcess(Request $request, $header_id) {
        InvoiceItemTable::getInstance ()->deleteRow ( array (
                "invoice_id" => $header_id 
        ) );
        
        // 割引の明細を作成
        if (! empty ( $request ["discount_name"] )) {
            foreach ( $request ["discount_name"] as $k => $v ) {
                if ((strlen ( $v ) || ! empty ( $request ["discount_id"] [$k] )) && ! empty ( $request ["discount_price"] [$k] ) && strlen ( $request ["discount_price"] [$k] )) {
                    
                    $discount_id = null;
                    $discount_name = $v;
                    if (! empty ( $request ["discount_id"] [$k] )) {
                        $discount_id = $request ["discount_id"] [$k];
                        $where = array (
                                'pschool_id' => session ( 'school.login' ) ['id'],
                                'id' => $request ["discount_id"] [$k] 
                        );
                        $row = InvoiceAdjustNameTable::getInstance ()->getRow ( $where );
                        $discount_name = $row ['name'];
                    }
                    $item = array (
                            "pschool_id" => session ( 'school.login' ) ['id'],
                            "invoice_id" => $header_id,
                            "parent_id" => $request ["parent_id"],
                            "student_id" => NULL,
                            "class_id" => NULL,
                            "cource_id" => NULL,
                            "invoice_adjust_name_id" => $discount_id,
                            "item_name" => $discount_name,
                            "unit_price" => "-" . $request ["discount_price"] [$k],
                            "active_flag" => 1,
                            "register_admin" => session ( 'school.login' ) ['login_account_id'],
                            "program_id" => NULL 
                    );
                    InvoiceItemTable::getInstance ()->save ( $item );
                }
            }
        }
        
        // プランの明細を作成
        if (isset ( $request ["class_name"] )) {
            foreach ( $request ["class_name"] as $student_id => $class_name_list ) {
                foreach ( $class_name_list as $k => $v ) {
                    if (strlen ( $v ) && isset ( $request ["class_id"] [$student_id] [$k] ) && strlen ( $request ["class_id"] [$student_id] [$k] )) {
                        
                        $studentRow = StudentTable::getInstance ()->getRow ( $where = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                'id' => $student_id 
                        ) );
                        
                        $item = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                "invoice_id" => $header_id,
                                "parent_id" => $request ["parent_id"],
                                "student_id" => $student_id,
                                "class_id" => $request ["class_id"] [$student_id] [$k],
                                "cource_id" => NULL,
                                "item_name" => $v,
                                "unit_price" => $request ["class_price"] [$student_id] [$k],
                                "active_flag" => 1,
                                "except_flag" => $request ["_class_except"] [$student_id] [$k],
                                "register_admin" => session ( 'school.login' ) ['login_account_id'],
                                
                                "school_year" => $studentRow ['school_year'],
                                "school_category" => $studentRow ['school_category'],
                                
                                "program_id" => NULL 
                        );
                        InvoiceItemTable::getInstance ()->save ( $item );
                    }
                }
            }
        }
        
        // イベントの明細を作成
        if (isset ( $request ["course_name"] )) {
            foreach ( $request ["course_name"] as $student_id => $course_name_list ) {
                foreach ( $course_name_list as $k => $v ) {
                    if (strlen ( $v ) && isset ( $request ["course_id"] [$student_id] [$k] ) && strlen ( $request ["course_id"] [$student_id] [$k] )) {
                        
                        $studentRow = StudentTable::getInstance ()->getRow ( $where = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                'id' => $student_id 
                        ) );
                        
                        $item = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                "invoice_id" => $header_id,
                                "parent_id" => $request ["parent_id"],
                                "student_id" => $student_id,
                                "class_id" => NULL,
                                "course_id" => $request ["course_id"] [$student_id] [$k],
                                "item_name" => $v,
                                "unit_price" => $request ["course_price"] [$student_id] [$k],
                                "active_flag" => 1,
                                "except_flag" => $request ["_course_except"] [$student_id] [$k],
                                "register_admin" => session ( 'school.login' ) ['login_account_id'],
                                
                                "school_year" => $studentRow ['school_year'],
                                "school_category" => $studentRow ['school_category'],
                                
                                "program_id" => NULL 
                        );
                        InvoiceItemTable::getInstance ()->save ( $item );
                    }
                }
            }
        }
        
        // プログラムの明細を作成
        if (isset ( $request ["program_name"] )) {
            foreach ( $request ["program_name"] as $student_id => $program_name_list ) {
                foreach ( $program_name_list as $k => $v ) {
                    if (strlen ( $v ) && isset ( $request ["program_id"] [$student_id] [$k] ) && strlen ( $request ["program_id"] [$student_id] [$k] )) {
                        
                        $studentRow = StudentTable::getInstance ()->getRow ( $where = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                'id' => $student_id 
                        ) );
                        
                        $item = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                "invoice_id" => $header_id,
                                "parent_id" => $request ["parent_id"],
                                "student_id" => $student_id,
                                "class_id" => NULL,
                                "course_id" => NULL,
                                "item_name" => $v,
                                "unit_price" => $request ["program_price"] [$student_id] [$k],
                                "active_flag" => 1,
                                "except_flag" => $request ["_program_except"] [$student_id] [$k],
                                "register_admin" => session ( 'school.login' ) ['login_account_id'],
                                
                                "school_year" => $studentRow ['school_year'],
                                "school_category" => $studentRow ['school_category'],
                                
                                "program_id" => $request ["program_id"] [$student_id] [$k] 
                        );
                        InvoiceItemTable::getInstance ()->save ( $item );
                    }
                }
            }
        }
        
        // 個別入力の明細を作成
        if (isset ( $request ["custom_item_name"] )) {
            foreach ( $request ["custom_item_name"] as $student_id => $custom_item_name_list ) {
                foreach ( $custom_item_name_list as $k => $v ) {
                    if ((strlen ( $v ) || ! empty ( $request ["custom_item_id"] [$student_id] [$k] )) && ! empty ( $request ["custom_item_price"] [$student_id] [$k] ) && strlen ( $request ["custom_item_price"] [$student_id] [$k] )) {
                        
                        $studentRow = StudentTable::getInstance ()->getRow ( $where = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                'id' => $student_id 
                        ) );
                        
                        $custom_id = null;
                        $custom_name = $v;
                        if (! empty ( $request ["custom_item_id"] [$student_id] [$k] )) {
                            $custom_id = $request ["custom_item_id"] [$student_id] [$k];
                            $where = array (
                                    'pschool_id' => session ( 'school.login' ) ['id'],
                                    'id' => $custom_id 
                            );
                            $row = InvoiceAdjustNameTable::getInstance ()->getRow ( $where );
                            $custom_name = $row ['name'];
                        }
                        
                        $item = array (
                                "pschool_id" => session ( 'school.login' ) ['id'],
                                "invoice_id" => $header_id,
                                "parent_id" => $request ["parent_id"],
                                "student_id" => $student_id,
                                "class_id" => NULL,
                                "cource_id" => NULL,
                                "invoice_adjust_name_id" => $custom_id,
                                "item_name" => $custom_name,
                                "unit_price" => $request ["custom_item_price"] [$student_id] [$k],
                                "active_flag" => 1,
                                "register_admin" => session ( 'school.login' ) ['login_account_id'],
                                
                                "school_year" => $studentRow ['school_year'],
                                "school_category" => $studentRow ['school_category'],
                                
                                "program_id" => NULL 
                        );
                        InvoiceItemTable::getInstance ()->save ( $item );
                    }
                }
            }
        }
        
        // invouce_itemテーブルにstudentテーブルのschool_year,school_category設定
        InvoiceItemTable::getInstance ()->setCategoryYear ( $header_id );
    }
    private function setErrorArrayColumn(&$errors, $column_name, $index, $error_key) {
        if (! isset ( $errors [$column_name] )) {
            $errors [$column_name] = array ();
        }
        if (! isset ( $errors [$column_name] [$index] )) {
            $errors [$column_name] [$index] = array ();
        }
        $errors [$column_name] [$index] [$error_key] = true;
    }
    private function setErrorArrayColumn2(&$errors, $column_name, $index1, $index2, $error_key) {
        if (! isset ( $errors [$column_name] )) {
            $errors [$column_name] = array ();
        }
        if (! isset ( $errors [$column_name] [$index1] )) {
            $errors [$column_name] [$index1] = array ();
        }
        if (! isset ( $errors [$column_name] [$index1] [$index2] )) {
            $errors [$column_name] [$index1] [$index2] = array ();
        }
        $errors [$column_name] [$index1] [$index2] [$error_key] = true;
    }
    
    /**
     * validatorのルール作成
     */
    private function create_validate_rules($type) {
        $rules = array ();
        if ($type == 'entry') {
            $rules = array(
                    /*
                     'is_established' => array(
                             Validaters::BETWEEN_VALUE => array(2, 1),
                     ),
            */
            );
        } elseif ($type == 'edit_input') {
        }
        return $rules;
    }
    private function set_history($level, $action) {
        session ()->put( self::SESSION_HISTORY_LIST . '.'. $level, $action);
        for($i = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1; $i > $level; $i --) {
            session()->forget( self::SESSION_HISTORY_LIST ) [$i];
        }
    }
    private function redirect_history($level = NULL) {
        
        if (session()->exists(self::SESSION_HISTORY_LIST )) {
            if (is_null ( $level )) {
                $level = count ( session ( self::SESSION_HISTORY_LIST ) ) - 1;
            }
            return redirect ( $this->get_app_path (). session ( self::SESSION_HISTORY_LIST) [$level] );
        } else {
            // 戻り先が無いので、TOPに戻す。
            // HeaderUtil::redirect ( $this->get_app_path () . self::$TOP_URL );
            return redirect ( $this->get_app_path () . self::$TOP_URL );
        }
    }
    private function set_message($action_status, $action_message) {
    	session()->put(self::SESSION_MESSAGE, array (
                "action_status" => $action_status,
                "action_message" => $action_message 
        ));
    }
    private function getClassPrice($class_id, $student_type) {
        $price = 0;
        $class_fee = ClassFeeTable::getInstance ()->getActiveRow ( array (
                'class_id' => $class_id,
                'student_type' => $student_type 
        ) );
        if (! empty ( $class_fee )) {
            $price = $class_fee ['fee'];
        }
        return $price;
    }
    private function getCoursePrice($course_id, $student_type) {
        $price = 0;
        $course_fee = CourseFeeTable::getInstance ()->getActiveRow ( array (
                'course_id' => $course_id,
                'student_type' => $student_type 
        ) );
        if (! empty ( $course_fee )) {
            $price = $course_fee ['fee'];
        }
        return $price;
    }
    public function getDropdate() {
        // 引落日
        $down_type = session ( 'school.login' ) ['payment_date'];
        if ($down_type == 99) {
            $dropdate = strtotime ( date ( 'Y-m-t' ) );
        } else if ($down_type >= date ( 'd' )) {
            $dropdate = strtotime ( date ( 'Y-m-' . $down_type ) );
        } else {
            $dropdate = strtotime ( date ( 'Y-m-' . $down_type ) . '+1 month' );
        }
        return $dropdate;
    }
    
    // 全銀データのダウンロード
    private function getZengin(Request $request) {
        // 請求元情報取得
        $pschool = session ( 'school.login' );
        $pschool_bank = PschoolBankAccountTable::getInstance ()->getRow ( array (
                'pschool_id' => $pschool ['id'] 
        ) );
        
        // 締切日を過ぎているかチェック
        $closing_day = $this->getTransferDateInfo ( $request ['invoice_year_month'], session ( 'school.login' ) ['id'] );
        $curr_date = date ( 'Y-m-d H:i:s' );
        $deadline = $closing_day ['deadline'] . ' 15:00:00';
        if ($curr_date > $deadline) {
            $action_status = 'NG';
            $action_message = ConstantsModel::$invoice_message [$this->current_lang] ['invoice_request_time_over'];
            $request ['action_status'] = $action_status;
            $request ['action_message'] = $action_message;
            return $this->executeDownload ( $request );
        }
        
        // 当月分のダウンロードファイルが存在するか？
        $file_list = InvoiceRequestTable::getInstance ()->getFileList ( session ( 'school.login' ) ['id'], $request ['invoice_year_month'] );
        if (! empty ( $file_list )) {
            $action_status = 'NG';
            $action_message = ConstantsModel::$invoice_message [$this->current_lang] ['file_already_exist'];
            $request ['action_status'] = $action_status;
            $request ['action_message'] = $action_message;
            return $this->executeDownload ( $request );
        }
        
        if (empty ( $pschool_bank ) || ! $this->requestKeyExists ( 'parent_ids' ) || ! is_array ( $request ['parent_ids'] )) {
            $action_status = 'NG';
            $action_message = ConstantsModel::$invoice_message [$this->current_lang] ['select_invoice_message'];
            $request ['action_status'] = $action_status;
            $request ['action_message'] = $action_message;
            return $this->executeDownload ( $request );
        }
        
        // 引落日
        $dropdate = $this->getDropdate ();
        
        // フォルダなければ作成
        $file_path = $this->createDownloadFolder ();
        
        $req_date = empty ( $request ['invoice_year_month'] ) ? date ( 'Y-m', $dropdate ) : $request ['invoice_year_month'];
        
        // ファイル名
        // 1月a 2b 3c 4d 5e 6f 7g 8h 9i 10j 11k 12n
        $file_month = $this->encode36 ( date ( 'n', strtotime ( $req_date ) ) + 9, 1 );
        $file_year = $this->encode36 ( date ( 'y', strtotime ( $req_date ) ), 2 );
        $file_schol = $this->encode36 ( $pschool ['id'], 3 );
        $file_name = $file_month . $file_year . $file_schol;
        
        $file_list = InvoiceRequestTable::getInstance ()->getFileList ( session ( 'school.login' ) ['id'], $req_date, null );
        $file_count = count ( $file_list );
        $file_name .= $this->encode36 ( $file_count, 2 ) . ".txt";
        
        // header_recoad
        $post_data = array ();
        $post_data [] = $this->getHeaderRecord ( $pschool, $pschool_bank, $dropdate );
        
        // 請求先情報取得
        $invoice_cnt = 0;
        $invoice_sum = 0;
        $req_table = InvoiceRequestTable::getInstance ();
        
        $closingdate = $this->getTransferDateInfo ( $request ['invoice_year_month'], session ( 'school.login' ) ['id'] );
        
        // invoice_headerの更新＆invoice_requestに登録
        foreach ( $request ['parent_ids'] as $ids ) {
            $invoice = InvoiceHeaderTable::getInstance ()->getRow ( array (
                    "pschool_id" => session ( 'school.login' ) ['id'],
                    'id' => $ids 
            ) );
            $parent_bank = ParentBankAccountTable::getInstance ()->getRow ( array (
                    'parent_id' => $invoice ['parent_id'] 
            ) );
            if (! empty ( $parent_bank )) {
                // invoice_requestに登録
                $req_id = $invoice ['parent_id'] . date ( 'md', $dropdate );
                // $row = $req_table->getRow(array('pschool_id' => $invoice['pschool_id'], 'invoice_header_id' => $ids, 'request_id' => $req_id));
                
                // 税別表示?
                if ($invoice ['amount_display_type'] == 1) {
                    $receipt = floor ( $invoice ['amount'] * (1 + $invoice ['sales_tax_rate']) );
                } else {
                    $receipt = $invoice ['amount'];
                }
                
                $req_table->beginTransaction ();
                try {
                    $invoice ['is_requested'] = 21;
                    $invoice ['workflow_status'] = 21;
                    InvoiceHeaderTable::getInstance ()->updateRow ( $invoice, array (
                            'id' => $invoice ['id'] 
                    ) );
                    
                    $row = array ();
                    if (empty ( $row )) {
                        $row = array (
                                'processing_filename' => $file_name,
                                'pschool_id' => $invoice ['pschool_id'],
                                // 'dayofwithdrawal' => date('md', $dropdate),
                                'dayofwithdrawal' => date ( 'md', strtotime ( $closingdate ['transfer_date'] ) ),
                                'request_id' => $req_id,
                                'parent_id' => $invoice ['parent_id'],
                                'invoice_header_id' => $ids,
                                'amount' => $receipt,
                                'request_date' => date ( 'Y-m-d', $dropdate ),
                                'status_flag' => 1,
                                // 'total_cnt' => $total_cnt,
                                // 'total_amount' => $total_amount,
                                'invoice_year_month' => $invoice ['invoice_year_month'],
                                'result_date' => $closingdate ['result_date'],
                                'deadline' => $closingdate ['deadline'],
                                'register_date' => date ( 'Y-m-d H:i:s' ) 
                        );
                        $req_table->insertRow ( $row );
                    } else {
                        $row ['processing_filename'] = $file_name;
                        $row ['amount'] = $receipt;
                        $row ['update_date'] = date ( 'Y-m-d H:i:s' );
                        $req_table->updateRow ( $row, array (
                                'id' => $row ['id'] 
                        ) );
                    }
                    $req_table->commit ();
                } catch ( Exception $ex ) {
                    $req_table->rollBack ();
                }
            }
        }
        
        // 親のIDを選別
        $parent_ids = InvoiceHeaderTable::getInstance ()->getParentList ( $request ['parent_ids'] );
        foreach ( $parent_ids as $key => $parent_id ) {
            $receipt = 0;
            $req_id = $parent_id ['parent_id'] . date ( 'md', $dropdate );
            $req_list = $req_table->getList ( array (
                    'pschool_id' => $invoice ['pschool_id'],
                    'request_id' => $req_id,
                    'processing_filename' => $file_name 
            ) );
            foreach ( $req_list as $req ) {
                // 税別表示?
                if ($pschool ['amount_display_type'] == 1) {
                    // $receipt += round($req['amount'] * (1+$pschool['sales_tax_rate']), 0);
                    $receipt += $req ['amount'];
                } else {
                    $receipt += $req ['amount'];
                }
            }
            if ($receipt > 0) {
                $parent_bank = ParentBankAccountTable::getInstance ()->getRow ( array (
                        'parent_id' => $req ['parent_id'] 
                ) );
                if (! empty ( $parent_bank )) {
                    $post_data [] = $this->getDataRecord ( $req, $parent_bank, $receipt );
                    $invoice_cnt ++;
                    $invoice_sum += $receipt;
                }
            }
        }
        // */
        
        $post_data [] = $this->getTrailerRecord ( $invoice_cnt, $invoice_sum );
        
        $post_data [] = $this->getEndRecord ();
        
        // 確認用
        $request ['zengin_data'] = $post_data;
        
        // コード変換
        $post_code = $this->getCode ( $post_data );
        
        // ファイルに保存
        $this->getFile ( $file_path, $file_name, $post_code );
        
        $action_status = 'OK';
        $action_message = ConstantsModel::$invoice_message [$this->current_lang] ['request_form_created'];
        $request ['action_status'] = $action_status;
        $request ['action_message'] = $action_message;
        return $this->executeDownload ( $request );
    }
    private function encode36($num, $len) {
        $str = base_convert ( $num, 10, 36 );
        $ret = str_pad ( $str, $len, "0", STR_PAD_LEFT );
        return $ret;
    }
    private function createDownloadFolder() {
        // app/data/zenginのフォルダ
        $root_path = ZENGIN_DIR;
        if (! file_exists ( $root_path )) {
            // 存在しないときの処理（「$directory_path」で指定されたディレクトリを作成する）
            if (mkdir ( $root_path, 0777 )) {
                // 作成したディレクトリのパーミッションを確実に変更
                chmod ( $root_path, 0777 );
            }
        }
        
        // app/data/zengin/pschool_8のフォルダ
        $pschool_path = $root_path . '/pschool_' . session ( 'school.login' ) ['id'];
        if (! file_exists ( $pschool_path )) {
            // 存在しないときの処理（「$directory_path」で指定されたディレクトリを作成する）
            if (mkdir ( $pschool_path, 0777 )) {
                // 作成したディレクトリのパーミッションを確実に変更
                chmod ( $pschool_path, 0777 );
            }
        }
        
        // app/data/zengin/pschool_8/downloadのフォルダ
        $file_path = $pschool_path . '/download';
        if (! file_exists ( $file_path )) {
            // 存在しないときの処理（「$directory_path」で指定されたディレクトリを作成する）
            if (mkdir ( $file_path, 0777 )) {
                // 作成したディレクトリのパーミッションを確実に変更
                chmod ( $file_path, 0777 );
            }
        }
        
        return $file_path;
    }
    private function getHeaderRecord($pdata, $bdata, $dropdate) {
        $post = array ();
        // 1 データ区分1 半角数字1 １＝ヘッダーレコード
        $post [1] = $this->strSpace ( 1, 1 );
        // 2 種別コード2 半角数字91 91＝口座振替
        $post [2] = $this->strSpace ( 91, 2 );
        // 3 コード区分1 半角数字0 0＝ＪＩＳコード
        $post [3] = $this->strSpace ( 0, 3 );
        // 4 委託者コード10 半角数字委託者コード（提携先様の採番通りでも可）
        $post [4] = $this->strSpace ( $bdata ['consignor_code'], 4 );
        // 5 委託者名（上） 20 半角英数ｶﾅ
        $post [5] = $this->strSpace ( mb_substr ( $bdata ['consignor_name'], 0, 20, "UTF-8" ), 5 );
        // 6 委託者名（下） 20 半角英数ｶﾅ
        $post [6] = $this->strSpace ( mb_substr ( $bdata ['consignor_name'], 20, 20, "UTF-8" ), 6 );
        // 7 引落日4 半角数字MMDD（月日）
        $post [7] = $this->strSpace ( date ( 'md', $dropdate ), 7 );
        // 8 取引銀行番号4 半角数字
        if ($bdata ['bank_type'] == 2) {
            $post [8] = $this->strSpace ( 9900, 8 );
        } else {
            $post [8] = $this->strSpace ( $bdata ['bank_code'], 8 );
        }
        // 9 取引銀行名15 半角英数ｶﾅ
        if ($bdata ['bank_type'] == 2) {
            $post [9] = $this->strSpace ( "", 9 );
        } else {
            $post [9] = $this->strSpace ( $bdata ['bank_name'], 9 );
        }
        // 10 取引支店番号3 半角数字
        if ($bdata ['bank_type'] == 2) {
            $post_account_kigou = $bdata ['post_account_kigou'];
            if (strlen ( $post_account_kigou ) > 3)
                $post_account_kigou = substr ( $post_account_kigou, 1, 3 );
            $post [10] = $this->strSpace ( $post_account_kigou, 10 );
        } else {
            $post [10] = $this->strSpace ( $bdata ['branch_code'], 10 );
        }
        // 11 取引支店名15 半角英数ｶﾅ
        if ($bdata ['bank_type'] == 2) {
            $post [11] = $this->strSpace ( "", 11 );
        } else {
            $post [11] = $this->strSpace ( $bdata ['branch_name'], 11 );
        }
        // 12 預金種目1 半角数字
        if ($bdata ['bank_type'] == 2) {
            $post [12] = $this->strSpace ( 1, 12 );
        } else {
            $bank_account_type = $bdata ['bank_account_type'];
            if ($bank_account_type != 2)
                $bank_account_type = 1;
            $post [12] = $this->strSpace ( $bank_account_type, 12 );
        }
        // 13 口座番号7 半角数字
        if ($bdata ['bank_type'] == 2) {
            $post [13] = $this->strSpace ( $bdata ['post_account_number'], 13 );
        } else {
            $post [13] = $this->strSpace ( $bdata ['bank_account_number'], 13 );
        }
        // 14 余白17 半角スペーススペース全て半角スペース
        $post [14] = $this->strSpace ( "", 14 );
        // */
        return $post;
    }
    private function getDataRecord($pdata, $bdata, $receipt) {
        $post = array ();
        // 15 データ区分1 半角数字2 ２＝データレコード
        $post [15] = $this->strSpace ( 2, 15 );
        // 16 引落銀行番号4 半角数字郵便局は9900
        if ($bdata ['bank_type'] == 2) {
            $post [16] = $this->strSpace ( 9900, 16 );
        } else {
            $post [16] = $this->strSpace ( $bdata ['bank_code'], 16 );
        }
        // 17 引落銀行名15 半角英数ｶﾅ
        if ($bdata ['bank_type'] == 2) {
            $post [17] = $this->strSpace ( "", 17 );
        } else {
            $post [17] = $this->strSpace ( $bdata ['bank_name'], 17 );
        }
        // 18 引落支店番号3 半角数字郵便局は、通帳記号「１●●●０」の内、「●●●」の3桁を支店番号とする
        if ($bdata ['bank_type'] == 2) {
            $post_account_kigou = $bdata ['post_account_kigou'];
            if (strlen ( $post_account_kigou ) > 3)
                $post_account_kigou = substr ( $post_account_kigou, 1, 3 );
            $post [18] = $this->strSpace ( $post_account_kigou, 18 );
        } else {
            $post [18] = $this->strSpace ( $bdata ['branch_code'], 18 );
        }
        // 19 引落支店名15 半角英数ｶﾅ
        if ($bdata ['bank_type'] == 2) {
            $post [19] = $this->strSpace ( "", 19 );
        } else {
            $post [19] = $this->strSpace ( $bdata ['branch_name'], 19 );
        }
        // 20 余白4 半角スペーススペース全て半角スペース
        $post [20] = $this->strSpace ( "", 20 );
        // 21 預金種目1 半角数字「1：普通」と「2：当座」のみ（郵便局は１）
        if ($bdata ['bank_type'] == 2) {
            $post [21] = $this->strSpace ( 1, 21 );
        } else {
            $bank_account_type = $bdata ['bank_account_type'];
            if ($bank_account_type != 2)
                $bank_account_type = 1;
            $post [21] = $this->strSpace ( $bank_account_type, 21 );
        }
        // 22 口座番号7 半角数字郵便局は、通帳番号８桁の内、上7桁を口座番号とする。
        if ($bdata ['bank_type'] == 2) {
            $post [22] = $this->strSpace ( $bdata ['post_account_number'], 22 );
        } else {
            $post [22] = $this->strSpace ( $bdata ['bank_account_number'], 22 );
        }
        // 23 預金者名30 半角英数ｶﾅ
        if ($bdata ['bank_type'] == 2) {
            $post [23] = $this->strSpace ( $bdata ['post_account_name'], 23 );
        } else {
            $post [23] = $this->strSpace ( $bdata ['bank_account_name'], 23 );
        }
        // 24 引落金額10 半角数字
        $post [24] = $this->strSpace ( $receipt, 24 );
        // 25 新規コード（※） 1 半角数字「1：１回目（新規）」か「2：変更分」か「0：２回目以降」のみ
        $post [25] = $this->strSpace ( 0, 25 );
        // 26 顧客番号20 半角英数字コレクト！画面上は下１５桁のみ表示ですが、取込に支障ございません。
        $post [26] = $this->strSpace ( $pdata ['request_id'], 26 );
        // 27 振替結果コード1 半角数字0 請求時は０をセット。振替結果連絡時には、振替結果コード（※）をセット
        $post [27] = $this->strSpace ( 0, 27 );
        // 28 余白8 半角スペース全て半角スペース
        $post [28] = $this->strSpace ( "", 28 );
        
        return $post;
    }
    private function getTrailerRecord($invoice_cnt, $invoice_sum) {
        $post = array ();
        // 29 データ区分1 半角数字8 ８＝トレーラレコード
        $post [29] = $this->strSpace ( 8, 29 );
        // 30 合計件数6 半角数字データレコードの合計件数
        $post [30] = $this->strSpace ( $invoice_cnt, 30 );
        // 31 合計金額12 半角数字データレコードの合計金額
        $post [31] = $this->strSpace ( $invoice_sum, 31 );
        // 32 振替済件数6 半角数字振替結果連絡時：件数を記載
        $post [32] = $this->strSpace ( "", 32 );
        // 33 振替済金額12 半角数字振替結果連絡時：金額を記載
        $post [33] = $this->strSpace ( "", 33 );
        // 34 振替不能件数6 半角数字振替結果連絡時：件数を記載
        $post [34] = $this->strSpace ( "", 34 );
        // 35 振替不能金額12 半角数字振替結果連絡時：金額を記載
        $post [35] = $this->strSpace ( "", 35 );
        // 36 余白65 半角スペース
        $post [36] = $this->strSpace ( "", 36 );
        
        return $post;
    }
    private function getEndRecord() {
        $post = array ();
        // 37 データ区分1 半角数字9 ９＝エンドレコード
        $post [37] = $this->strSpace ( 9, 37 );
        // 38 余白119 半角スペース
        $post [38] = $this->strSpace ( "", 38 );
        
        return $post;
    }
    private function strSpace($str, $len) {
        // default
        if (! empty ( self::$LEYOUT [$len] ['default'] ) > 0) {
            $str = self::$LEYOUT [$len] ['default'];
        }
        // 小文字を大文字に変換
        // $str = strtoupper($str);
        // 全角を半角に変換
        $str = mb_convert_kana ( $str, "akh", "UTf-8" );
        // UTF-8をSJISに変換
        $str = mb_convert_encoding ( $str, "SJIS", "UTf-8" );
        
        // array('degit'=>17,'format'=>"%'.17s", 'default'=>''), // 14
        $degit = self::$LEYOUT [$len] ['degit'];
        $format = self::$LEYOUT [$len] ['format'];
        
        if (strpos ( $format, 's' ) === false) {
            // 数字は右詰残り前ゼロ
            $str = str_pad ( $str, $degit, 0, STR_PAD_LEFT );
            // $str = sprintf($format, $str);
        } else {
            // 文字は左詰残りスペース
            $str = str_pad ( $str, $degit, " ", STR_PAD_RIGHT );
            // $str = sprintf($format, $str);
        }
        
        if (mb_strlen ( $str ) > $degit) {
            $str = mb_substr ( $str, 0, $degit );
        }
        
        return $this->strMapping ( $str );
    }
    private function strMapping($str) {
        $map = "";
        for($ii = 0; $ii < strlen ( $str ); $ii ++) {
            $word = substr ( $str, $ii, 1 );
            $bin = bin2hex ( $word );
            $upper = hexdec ( substr ( $bin, 0, 1 ) );
            $lower = hexdec ( substr ( $bin, - 1, 1 ) );
            
            if ($upper == 2) {
                if ($lower != 8 && $lower != 9 && $lower != 13 && $lower != 14) {
                    $bin = "20";
                }
            } elseif ($upper == 3) {
                if ($lower > 9) {
                    $bin = "20";
                }
            } elseif ($upper == 4) {
                if ($lower == 0) {
                    $bin = "20";
                }
            } elseif ($upper == 5) {
                if ($lower > 10) {
                    $bin = "20";
                }
            } elseif ($upper == 6) {
                if ($lower == 0) {
                    $bin = "20";
                } else {
                    $bin = dechex ( $upper - 2 ) . dechex ( $lower );
                }
            } elseif ($upper == 7) {
                if ($lower > 10) {
                    $bin = "20";
                } else {
                    $bin = dechex ( $upper - 2 ) . dechex ( $lower );
                }
            } elseif ($upper == 10) {
                if ($lower < 5) {
                    $bin = "20";
                } elseif ($lower == 7) {
                    $bin = "b1";
                } elseif ($lower == 8) {
                    $bin = "b2";
                } elseif ($lower == 9) {
                    $bin = "b3";
                } elseif ($lower == 10) {
                    $bin = "b4";
                } elseif ($lower == 11) {
                    $bin = "b5";
                } elseif ($lower == 12) {
                    $bin = "d4";
                } elseif ($lower == 13) {
                    $bin = "d5";
                } elseif ($lower == 14) {
                    $bin = "d6";
                } elseif ($lower == 15) {
                    $bin = "c2";
                }
            } elseif ($upper == 11) {
                if ($lower == 0) {
                    $bin = "20";
                }
            } elseif ($upper == 12) {
            } elseif ($upper == 13) {
            } else {
                $bin = "20";
            }
            
            $map .= $bin;
        }
        return $this->hex8in ( $map );
    }
    private function hex8in($str) {
        $sbin = "";
        $len = strlen ( $str );
        for($i = 0; $i < $len; $i += 2) {
            $sbin .= pack ( "H*", substr ( $str, $i, 2 ) );
        }
        
        return $sbin;
    }
    private function getCode($list) {
        $ret = "";
        $ii = 0;
        foreach ( $list as $items ) {
            if ($ii != 0) {
                $ret .= $this->hex8in ( "0D0A" );
            }
            foreach ( $items as $key => $item ) {
                $ret .= $item;
            }
            $ii ++;
        }
        
        return $ret;
    }
    private function getFile($path, $name, $code) {
        // ファイルがなければ作成
        if (! file_exists ( $path . "/" . $name )) {
            touch ( $path . "/" . $name );
        }
        
        // ファイルをオープン
        $file = fopen ( $path . "/" . $name, "w" );
        
        // ファイルへ書き込み
        fwrite ( $file, $code );
        
        // ファイルを閉じる
        fclose ( $file );
        
        // $this->assignVars ( 'file_name', $name );
        $file_name = $name;
        return $file_name;
    }
    private function filedown($name) {
        $path = ZENGIN_DIR . '/pschool_' . session ( 'school.login' ) ['id'] . '/download';
        
        // ヘッダ
        header ( 'Content-Type: application/octet-stream' );
        header ( 'Content-Disposition: attachment; filename=' . $name );
        header ( 'Content-Transfer-Encoding: binary' );
        header ( 'Content-Length: ' . filesize ( $path . "/" . $name ) );
        
        // 対象ファイルを出力する。
        readfile ( $path . "/" . $name );
        
        // exit;
    }
    
    /*
     * 請求書処理画面
     */
    public function executeInvoiceManage(Request $request) {
//         $this_screen = 'invoicemanage';
        view()->share('this_screen', 'invoicemanage');
        $is_simple = false;
        $is_menu = false;
        
        if (empty ( $request ) || isset ( $request ["menu"] )) {
            // 初期表示の場合
            $request ["invoice_year"] = date ( 'Y' );
            $request ["invoice_month"] = date ( 'm' );
            $is_menu = true;
            for($idx = 0; $idx < 4; $idx ++) {
                $item_name = 'invoice_type' . $idx;
                $request [$item_name] = 1;
            }
            for($idx = 0; $idx < 2; $idx ++) {
                $item_name = 'paied_type' . $idx;
                $request [$item_name] = 1;
            }
        } else if (isset ( $request ["back"] ) && session ( self::SESSION_SEARCH_COND ) !== null) {
            $request = session ()->get ( self::SESSION_SEARCH_COND );
        } else if (isset ( $request ["simple"] )) {
            // 簡易検索
            $is_simple = true;
        }
        
        $invoice_list = null;
        if (! $is_menu && ! $is_simple) {
            $invoice_list = InvoiceHeaderTable::getInstance ()->getListInclDisabledBySearch ( session ( 'school.login' ) ['id'], $request );
            $request ['search_cond'] = 1;
        } else {
            $invoice_list = InvoiceHeaderTable::getInstance ()->getListInclDisabledBySearch2 ( session ( 'school.login' ) ['id'], $request );
            $request ['search_cond'] = 2;
            if (! $is_menu) {
                for($idx = 0; $idx < 4; $idx ++) {
                    $item_name = 'invoice_type' . $idx;
                    $request [$item_name] = 0;
                }
                if (isset ( $request ['invoice_type'] )) {
                    foreach ( $request ['invoice_type'] as $key => $val ) {
                        $item_name = 'invoice_type' . $key;
                        $request [$item_name] = 1;
                    }
                }
                for($idx = 0; $idx < 2; $idx ++) {
                    $item_name = 'paied_type' . $idx;
                    $request [$item_name] = 0;
                }
                if (isset ( $request ['paied_type'] )) {
                    foreach ( $request ['paied_type'] as $key => $val ) {
                        $item_name = 'paied_type' . $key;
                        $request [$item_name] = 1;
                    }
                }
            }
        }
        $request->offsetUnset('is_recieved');
        $request->offsetUnset('parent_mail_infomation');
//         unset ( $request ["is_recieved"] );
//         unset ( $request ['parent_mail_infomation'] );
        $this->set_list_info ( $invoice_list );
        // $this->assignVars ( 'invoice_list', $invoice_list );
        
        session ()->put ( self::SESSION_MAIL_SEARCH_COND, $request );
        // $this->clear_bread_list();
        $this->set_bread_list ( self::$ACTION_URL . "/invoiceManage?back", ConstantsModel::$bread_list [$this->current_lang] ['invoice_process'] );
        $this->set_history ( 0, self::$ACTION_URL . "/invoiceManage?back" );
        
        $pschool_id = session ( 'school.login' ) ['id'];
        $lan = $this->lan;
        // return $this->convertSmartyPath ( self::$TEMPLATE_URL . 'invoice_manage.html' );
        return view ( self::$TEMPLATE_URL . 'invoice_manage', compact ( 'lan', 'invoice_list', 'pschool_id' ) );
    }
    
    /**
     * AJAXで未入金分の一覧取得
     *
     * 入力は、支払方法 現金・振込・口座引落・すべて
     */
    public function executeGetarrearlist() {
        
        // $invoice_list = InvoiceHeaderTable::getInstance()->getListInclDisabledBySearch($_SESSION['school.login']['id'], $request);
        $pschool_id = $request ['pschool_id'];
        $invoice_type = $request ['invoice_type'];
        $date = date ( 'Y-m', strtotime ( "-1 month" ) );
        
        $datUTF = InvoiceHeaderTable::getInstance ()->getArrearList ( $date, $pschool_id, $invoice_type );
        $datUTF ['result'] = 'OK';
        print json_encode ( $datUTF );
    }
    private $tempDir;
    
    // use library laravel
    private function createPdf($data) {
        require_once BASE_COMMON_DIR . 'MiscUtil.php';
        
        $this->create_tempdir ();
        
        $filename = $this->tempDir . 'invoicePDF.pdf';
        
        ini_set ( 'memory_limit', "64M" );
        $html = "";
        $tpl = '_pdf/pdf.html';
        $smarty = new BaseView ();
        $file = $smarty->template_dir . $tpl;
        if (file_exists ( $file )) {
            // 本文作成
            // $smarty->assign('title', $title);
            $smarty->assign ( 'data', $data );
            $html = $smarty->fetch ( $tpl );
        } else {
            $this->error [] = ConstantsModel::$errors [$this->current_lang] ['template_file_error'];
            return false;
        }
        
        include_once BASE_COMMON_DIR . '_BaseMPDF.php';
        $pdf = new _BaseMPDF ();
        if (! empty ( $html )) {
            $pdf->writeHTML ( $html );
        }
        $pdf->Output ( $filename, 'F' );
        return true;
    }
    private function create_tempdir() {
        // PDF作成用一時フォルダ取得
        $this->tempDir = MiscUtil::createTempdir ();
        if (! MiscUtil::endsWith ( $this->tempDir, DIRECTORY_SEPARATOR )) {
            $this->tempDir .= DIRECTORY_SEPARATOR;
        }
        $this->create_log [] = sprintf ( ConstantsModel::$logger [$this->current_lang] ['temporary_folder_created'], '{$this->tempDir}' );
    }
    const TEMP_PDF_NAME = 'output.pdf';
    private function exec_combine_cmd() {
        // $cmd = PDFTK_CMD . ' '. implode(' ', $this->created_pdf_list) . ' cat output ' . $this->tempDir . self::TEMP_PDF_NAME . ' 2>&1';
        $cmd = PDFTK_CMD . ' ' . $this->tempDir . 'invoicePDF.pdf cat output ' . $this->tempDir . self::TEMP_PDF_NAME . ' 2>&1';
        
        $output = array ();
        if (MiscUtil::execCommand ( $cmd, $output )) {
            return $output;
        }
        return null;
    }
    /*
     * 検索結果一覧のidを、SESSIONに保存する
     */
    private function set_list_info($list) {
        $id_list = array ();
        
        foreach ( $list as $row ) {
            $id_list [] = $row ['id'];
        }
        if (session ( self::SESSION_SEARCH_LIST ) !== null) {
            session()->forget( self::SESSION_SEARCH_LIST );
        }
        
        session ()->put ( self::SESSION_SEARCH_LIST, $id_list );
//         dd(session(self::SESSION_SEARCH_LIST));
    }
    /*
     * 現在選ばれている詳細画面のIDを保存する
     */
    private function set_disp_id($id) {
        if (session ( self::SESSION_CURRENT_DISP_ID ) !== null) {
            session()->forget ( self::SESSION_CURRENT_DISP_ID );
        }
        session ()->put ( self::SESSION_CURRENT_DISP_ID, $id );
        // $this->dispDebug($_SESSION[self::SESSION_CURRENT_DISP_ID]);
    }
    /*
     * ひとつ前のIDを取り出す。
     */
    private function get_pre_id() {
        if (session ( self::SESSION_CURRENT_DISP_ID ) !== null && session ( self::SESSION_SEARCH_LIST ) !== null) {
            $cnt = count ( session ( self::SESSION_SEARCH_LIST ) );
            for($i = 0; $i < $cnt; $i ++) {
                if (session ( self::SESSION_SEARCH_LIST ) [$i] == session ( self::SESSION_CURRENT_DISP_ID )) {
                    if ($i == 0) {
                        return session ( self::SESSION_CURRENT_DISP_ID );
                    }
                    session()->put(self::SESSION_CURRENT_DISP_ID, session ( self::SESSION_SEARCH_LIST ) [$i - 1]);
                    return session ( self::SESSION_SEARCH_LIST ) [$i - 1];
                }
            }
        }
        return false;
    }
    /*
     * ひとつ次のIDを取り出す。
     */
    private function get_next_id() {
        if (session ( self::SESSION_CURRENT_DISP_ID ) !== null && session ( self::SESSION_SEARCH_LIST ) !== null) {
            $cnt = count ( session ( self::SESSION_SEARCH_LIST ) );
            for($i = 0; $i < $cnt; $i ++) {
                if (session ( self::SESSION_SEARCH_LIST ) [$i] == session ( self::SESSION_CURRENT_DISP_ID )) {
                    if ($i == $cnt - 1) {
                        return session ( self::SESSION_CURRENT_DISP_ID );
                    }
                    session()->put(self::SESSION_CURRENT_DISP_ID, session ( self::SESSION_SEARCH_LIST ) [$i + 1]);
                    return session ( self::SESSION_SEARCH_LIST ) [$i + 1];
                }
            }
        }
        return false;
    }
    /*
     * 最初のIDかどうか・・・。
     */
    private function is_first_id() {
        if (session ( self::SESSION_CURRENT_DISP_ID ) !== null && session ( self::SESSION_SEARCH_LIST ) !== null) {
            if (session ( self::SESSION_SEARCH_LIST ) [0] == session ( self::SESSION_CURRENT_DISP_ID )) {
                return true;
            }
        }
        return false;
    }
    /*
     * 最後のIDかどうか・・・。
     */
    private function is_last_id() {
        if (session ( self::SESSION_CURRENT_DISP_ID ) && session ( self::SESSION_SEARCH_LIST ) !== null) {
            $cnt = count ( session ( self::SESSION_SEARCH_LIST ) );
            if (session ( self::SESSION_SEARCH_LIST ) [$cnt - 1] == session ( self::SESSION_CURRENT_DISP_ID )) {
                return true;
            }
        }
        return false;
    }
    public function convJpDate($src) {
        list ( $year, $month, $day ) = explode ( '-', $src );
        if (! @checkdate ( $month, $day, $year ) || $year < 1869 || strlen ( $year ) !== 4 || strlen ( $month ) !== 2 || strlen ( $day ) !== 2)
            return false;
        $date = str_replace ( '-', '', $src );
        if ($date >= 19890108) {
            $gengo = ConstantsModel::$gengo [$this->current_lang] ['march'];
            $wayear = $year - 1988;
        } elseif ($date >= 19261225) {
            $gengo = ConstantsModel::$gengo [$this->current_lang] ['showa'];
            $wayear = $year - 1925;
        } elseif ($date >= 19120730) {
            $gengo = ConstantsModel::$gengo [$this->current_lang] ['taisho'];
            $wayear = $year - 1911;
        } else {
            $gengo = ConstantsModel::$gengo [$this->current_lang] ['meiji'];
            $wayear = $year - 1868;
        }
        switch ($wayear) {
            case 1 :
                $wadate = $gengo . ConstantsModel::$gengo [$this->current_lang] ['first_year'] . $month . ConstantsModel::$header [$this->current_lang] ['month'] . $day . ConstantsModel::$header [$this->current_lang] ['day'];
                break;
            default :
                $wadate = $gengo . sprintf ( "%d", $wayear ) . ConstantsModel::$header [$this->current_lang] ['year'] . sprintf ( "%d", $month ) . ConstantsModel::$header [$this->current_lang] ['month'] . sprintf ( "%d", $day ) . ConstantsModel::$header [$this->current_lang] ['day'];
        }
        return $wadate;
    }
    
    /**
     * 口座引落日等に関する情報
     */
    public function getTransferDateInfo($year_month, $pschool_id) {
        $pschool = PschoolTable::getInstance ()->load ( $pschool_id );
        $withdrawal_day = $pschool ['withdrawal_day'];
        $payment_style = $pschool ['payment_style'];
        
        if ($payment_style == 1) {
            // 先払い
            $target_date = $year_month . "-01";
        } else {
            // 後払い
            $target_date = $year_month . "-01";
            $last_date = date ( 't', $target_date );
            $target_date = $year_month . "-" . $last_date;
        }
        
        // 同じ口座振替日
        $close_days = ClosingDayTable::getInstance ()->getList ( array (
                'transfer_day' => $withdrawal_day 
        ), array (
                'transfer_month' => 'ASC' 
        ) );
        $target_id = $close_days [0] ['id'];
        $near_date = date ( 'U', strtotime ( $close_days [0] ['transfer_date'] ) );
        $base_date = date ( 'U', strtotime ( $target_date ) );
        
        foreach ( $close_days as $close_item ) {
            $temp_date = date ( 'U', strtotime ( $close_item ['transfer_date'] ) );
            if (abs ( $base_date - $temp_date ) < abs ( $base_date - $near_date )) {
                // 基準となる日付に近いもの
                $target_id = $close_item ['id'];
                $near_date = $temp_date;
            }
        }
        
        return ClosingDayTable::getInstance ()->load ( $target_id );
    }
}
