<?php

namespace App\Http\Controllers\School;

use App\ConstantsModel;
use Illuminate\Http\Request;
use App\Model\PschoolTable;
use App\Model\EntryTable;
use App\Model\InvoiceHeaderTable;
use App\Model\BroadcastMailTable;
use App\Model\MailMessageTable;
use App\Model\CourseTable;
use App\Model\ClosingDayTable;
use App\Model\BulletinBoardTable;
use App\Lang;
use App\Module\Invoice\InvoiceHelper;
use App\Common\Constants;
use App\Model\SystemLogTable;
use Illuminate\Support\Facades\Log;
use DB;

class HomeController extends _BaseSchoolController {
    protected static $LANG_URL = 'home';
    protected static $DEFAULT_SEARCH_TYPE_LATEST = 1;  // Search by 最新30件
    protected static $SEARCH_TYPE_PERIOD = 2;  // Search by 期間
    protected static $ITEM_LIMIT = 30; // 30件

    public function __construct() {
        parent::__construct ();
        $message_content = parent::getMessageLocale ();
        $this->lan = new Lang ( $message_content );
        session ()->put ( 'isNormalShibu', PschoolTable::getInstance ()->isNormalShibu ( session ( 'school.login.id' ) ) );

        $this->_loginAdmin = $this->getLoginAdmin();
    }
    public function index() {
        // get school_id from login acc
        $pschool_id = $this->_loginAdmin ['id'];
        $course_list = CourseTable::getInstance()->getListOfCourse($pschool_id);
        // calculate total member & total fee of every course
        foreach ($course_list as $key => $course) {
            // calculate total member
            $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( $pschool_id, 
                array (
                        'entry_type'    => 2,
                        'relative_id'   => $course['id'],
                        'enter' => 1 
                ) );
            $total_member = count($entries);
            $total_fee = 0;
            // calculate total fee
            foreach ($entries as $value) {
                $total_fee += (float)$value['fee'];
            }
            $course_list[$key]['total_member'] = $total_member;
            $course_list[$key]['total_fee'] = $total_fee;
        }
        // $this->loadWeatherForecast();

        // 口座引落の定義書提出期限
        $withdrawal_day = InvoiceHelper::getInstance()->getWithdrawDay($pschool_id, Constants::$PAYMENT_TYPE['TRAN_RICOH']);
        $closingday_list = ClosingDayTable::getInstance()->getList(array('transfer_day'=>$withdrawal_day, 'delete_date IS NULL'), array('transfer_month'=>'ASC'));

        // 掲示板
        $condition = array( 'pschool_id'    => $pschool_id, 
                            'account_type'  => $this->_loginAdmin['auth_type'],
                            'is_home'       => 1, );

        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        // set text color of bulletin_list's item
        foreach ($bulletin_list as $key => $bulletin) {
            $r = hexdec(substr($bulletin['calendar_color'],0,2));
            $g = hexdec(substr($bulletin['calendar_color'],2,2));
            $b = hexdec(substr($bulletin['calendar_color'],4,2));

            if( $r + $g + $b > 382 ){
                // bright background color, use dark font
                $bulletin_list[$key]['calendar_text_color'] = '000000';
            } else {
                // dark background color, use bright font
                $bulletin_list[$key]['calendar_text_color'] = 'FFFFFF';
            }
        }
        // calendar holiday
        $holiday = DB::table('holiday')->whereNull('delete_date')->get();

        // 送信済みメール一覧の取得
        $mails = $this->getMails($pschool_id, self::$DEFAULT_SEARCH_TYPE_LATEST);
        // お知らせ・アクティビティ
        $notices = $this->getNotices($pschool_id, self::$DEFAULT_SEARCH_TYPE_LATEST);
        // システムのお知らせ
        $system_logs = $this->getSystemLogs($pschool_id, self::$DEFAULT_SEARCH_TYPE_LATEST);
        foreach ($system_logs as $key => $system_log) {
            $r = hexdec(substr($system_log['calendar_color'],0,2));
            $g = hexdec(substr($system_log['calendar_color'],2,2));
            $b = hexdec(substr($system_log['calendar_color'],4,2));

            if( $r + $g + $b > 382 ){
                // bright background color, use dark font
                $system_logs[$key]['calendar_text_color'] = '000000';
            } else {
                // dark background color, use bright font
                $system_logs[$key]['calendar_text_color'] = 'FFFFFF';
            }
        }
        return view ( 'School.Home.index' ) ->with ( 'lan', $this->lan )
                                            ->with ( 'notices', $notices )
                                            ->with ( 'mails', $mails )
                                            ->with ( 'system_logs', $system_logs )
                                            ->with ( 'courses', $course_list )
                                            ->with ( 'closingday_list', $closingday_list )
                                            ->with ( 'holiday', $holiday )
                                            ->with ( 'bulletins', $bulletin_list );
    }

    public function ajaxSearchNotice(Request $request)
    {
        $notices = $this->getNotices($this->_loginAdmin ['id'], $request['notice_search'], $request['notice_search_select']);
        return view('School.Home.notice_content')->with('notices', $notices);
    }

    public function ajaxSearchMail(Request $request)
    {
        $mails = $this->getMails($this->_loginAdmin ['id'], $request['mail_search'], $request['mail_search_select']);
        return view('School.Home.mail_content')->with('mails', $mails);
    }

    public function ajaxSearchSystemLog(Request $request)
    {
        $system_logs = $this->getSystemLogs($this->_loginAdmin ['id'], $request['system_log_search'], $request['system_log_search_select']);
        return view('School.Home.system_log_content')->with('system_logs', $system_logs);
    }

    private function getNotices($pschool_id, $search_type, $search_condition = null)
    {
        $period = ($search_type == self::$SEARCH_TYPE_PERIOD && !is_null($search_condition)) ? $search_condition : null; // 期間で検索

        // Notice: Users reply email to join in event (or consultation)
        // お知らせのイベント: 参加するため、メールでお客様が返信した
        $notice_news = EntryTable::getInstance ()->getListbyCourse ( $pschool_id, $period );

        // お知らせのプログラム: 参加するため、メールでお客様が返信した
        $notice_programs = EntryTable::getInstance ()->getListbyProgram ( $pschool_id, $period );

        // お知らせ: お客様が学資を払ってない
        $notice_activities = InvoiceHeaderTable::getInstance()->getListNoticeByInvoice($pschool_id, $period);

        // マージ
        $notices = array_merge($notice_news, $notice_programs, $notice_activities);
        $notices = $this->array_msort($notices, array('date'=>SORT_DESC));

        // 最新30件で検索
        if ($search_type == self::$DEFAULT_SEARCH_TYPE_LATEST && sizeof($notices) > self::$ITEM_LIMIT) { // 最新30件
            $notices = array_slice($notices, 0, self::$ITEM_LIMIT);
        }
        return $notices;
    }

    public function getMails($pschool_id, $search_type, $search_condition = null)
    {
        $period = ($search_type == self::$SEARCH_TYPE_PERIOD && !is_null($search_condition)) ? $search_condition : null; // 期間で検索
        // お知らせのメール
        $broadcast_mail_list = BroadcastMailTable::getInstance ()->getBroadCastList ( $pschool_id, $period);

        // イベントのメール
        $event_mail_list = MailMessageTable::getInstance ()-> getEventGroupMailList ( $pschool_id, $period);

        // マージ
        $mails = array_merge($broadcast_mail_list, $event_mail_list);
        $mails = $this->array_msort($mails, array('date'=>SORT_DESC));

        // 最新30件で検索
        if ($search_type == self::$DEFAULT_SEARCH_TYPE_LATEST && sizeof($mails) > self::$ITEM_LIMIT) {
            $mails = array_slice($mails, 0, self::$ITEM_LIMIT);
        }
        return $mails;
    }

    private function getSystemLogs($pschool_id, $search_type, $search_condition = null)
    {
        $period = ($search_type == self::$SEARCH_TYPE_PERIOD && !is_null($search_condition)) ? $search_condition : null; // 期間で検索
        // System Log: get list by condition
        // システムのお知らせ
        $system_logs = SystemLogTable::getInstance ()->getListSystemLog ( $pschool_id, $period );
        $system_logs=SystemLogTable::getInstance()->FilterSystemLog($system_logs);
        // 最新30件で検索
        if ($search_type == self::$DEFAULT_SEARCH_TYPE_LATEST && sizeof($system_logs) > self::$ITEM_LIMIT) { // 最新30件
            $system_logs = array_slice($system_logs, 0, self::$ITEM_LIMIT);
        }

        foreach ($system_logs as $key => $log) {
            //set value of status
            $lang = is_numeric(session ( 'school.login' ) ['language']) ? session ( 'school.login' ) ['language'] : array_search('日本語', ConstantsModel::$languages);
            $system_logs[$key]['status'] = Constants::$SYSTEM_LOG_STATUS[$lang][$log['status']];

        }
        return $system_logs;
    }

    public function ajaxUpdateNoticeViewDate(Request $request)
    {
        $notice_type = $request->notice_type;
        $id = $request->id;
        if (is_null($notice_type) || is_null($id)) {
            return "notice_type or id does not exists";
        }
        // event - course update
        if ($notice_type == 'notice_event' || $notice_type == 'notice_program') {
            $entryTable = EntryTable::getInstance();
            $entry = $entryTable->load($id);
            if ($entry) {
                try {
                    $entry['view_date'] = date('Y-m-d H:i:s');
                    $entryTable->updateRow($entry, array('id'=>$entry['id']));
                } catch (\Exception $e) {
                    Log::error($e->getMessage ());
                    return "fail";
                }
            }
        // invoice_header update
        } else if ($notice_type == 'notice_invoice') {
            $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
            $invoice = $invoiceHeaderTable->load($id);
            if ($invoice) {
                try {
                    $invoice['view_date'] = date('Y-m-d H:i:s');
                    $invoiceHeaderTable->updateRow($invoice, array('id'=>$invoice['id']));
                } catch (\Exception $e) {
                    Log::error($e->getMessage ());
                    return "fail";
                }
            }
        }
        return "success";
    }

    /** Update status system_log. seen or not see.
     * @param Request $request
     */
    public  function  ajaxUpdateSystemLogViewdate(Request $request){
        SystemLogTable::getInstance ()->updateSystemLogViewdate ($request->notify_id);
    }
    // sort array by column
    private function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\''.$col.'\'],'.$order.',';
        }
        $eval = substr($eval,0,-1).');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k,1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }
}
