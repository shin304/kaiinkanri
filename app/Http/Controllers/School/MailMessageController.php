<?php

namespace App\Http\Controllers\School;

use App\Common\Constants;
use App\Common\HandelCoopSendMail;
use App\Http\Controllers\School\Invoice\BaseInvoiceController;
use App\Http\Controllers\School\Invoice\InvoiceController;
use App\Model\InvoiceHeaderTable;
use App\Model\InvoiceItemTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\PschoolTable;
use App\Module\Invoice\BaseInvoice;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Model\StudentCourseRelTable;
use App\Model\StudentProgramTable;
use App\Model\ProgramFeePlanTable;
use App\Model\CourseFeePlanTable;
use App\Model\StudentGradeTable;
use App\Model\MStudentTypeTable;
use App\Model\ConsultationTable;
use App\Model\CourseCoachTable;
use App\Model\MailMessageTable;
use App\Model\HierarchyTable;
use App\Model\StudentTable;
use App\Model\ProgramTable;
use App\Model\CourseTable;
use App\Model\ParentTable;
use App\Model\LessonTable;
use App\Model\ClassTable;
use App\Model\EntryTable;
use App\ConstantsModel;
use App\Mail\MailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Lang;
use Validator;
use File;
use View;

class MailMessageController extends _BaseSchoolController
{   use \App\Common\Email;
    protected static $ACTION_URL = 'mailMessage';
    private static $TEMPLATE_URL = 'mail_message';
    private static $bread_name = 'メール送信先選択';
    protected static $LANG_URL = 'mail_message';

    private $relative_item;
    private $_joined_memmber_no = 0;
    const invoice_background_color = array (
            '1' => array('top'=> '#8FC31F', 'bottom' => '#7aa71b'),
            '2' => array('top'=> '#b0b4f2', 'bottom' => '#7c7fa8'),
            '3' => array('top'=> '#fcc692', 'bottom' => '#ff9f42'),
            '4' => array('top'=> '#6873fb', 'bottom' => '#455db5'),
            '5' => array('top'=> '#64a857', 'bottom' => '#406d37'),
            '6' => array('top'=> '#da84ff', 'bottom' => '#9557af'),

    );
    public function __construct()
    {
        parent::__construct();
        // 多国語対応
        $message_content = parent::getMessageLocale();
        $this->lan = new Lang($message_content);
    
    //  event_type_id : (ENTRY_TYPE)
    //      1 : 面談管理
    //      2 :  イベント
    //      3 : プログラム
    
    //  msg_type_id : (MAIL_MESSAGE_TYPE)
    //      1 : 請求書
    //      2 : 面談(申込)
    //      3 : イベント
    //      4 : 面談(日程)
    //      5 : プログラム
    //      6 : お知らせ
        $this->recoverWithInput(request(), array('relative_id', 'msg_type_id', 'event_type_id', 'event_name'));


        self::$TEMPLATE_URL = 's_mail_message';
        self::$bread_name = 'メール送信先選択';

        // 帯色のリスト
        // $grades = StudentGradeTable::getInstance ()->getGradeSelect2( session('school.login')['id'] );
        // view()->share('grades', $grades);
        // ステータスのリスト
        $states = array (
                '1' => ConstantsModel::$states[$this->current_lang]['1'] ,
                // '2' => ConstantsModel::$states[$this->current_lang]['2'],
                '9' => ConstantsModel::$states[$this->current_lang]['9']
        );
        view()->share('states', $states);
        // 生徒区分
        // view()->share('schoolCategory', ConstantsModel::$dispSchoolCategory);

        // 2017-05-17 Customize by Kieu
        // TODO: compare fee_type to determine CourseFeePlan
        // 受講料
        
        $total_member   = 0;
        $total_fee      = 0;
        switch (request('event_type_id')) {
            case 2: // イベント
                $this->relative_item = CourseTable::getInstance()->getRow(array('pschool_id'=>session('school.login')['id'], 'id'=>request('relative_id')));
                
                $total_fee = EntryTable::getInstance ()->getTotalFeeByEventId(request('relative_id'));

                break;
            case 3: //プログラム
                $this->relative_item = ProgramTable::getInstance()->getRow(array('pschool_id'=>session('school.login')['id'], 'id'=>request('relative_id')));
                $total_fee = EntryTable::getInstance ()->getTotalFeeByProgramId(request('relative_id'));
                
                break;
            
            default:
                # code...
                return redirect('/school/home');
                break;
        }

        // total capacity
        $total_capacity = ($this->relative_item['non_member_flag'] == 1)? ($this->relative_item['member_capacity'] + $this->relative_item['non_member_capacity']) : $this->relative_item['member_capacity'];
        $this->relative_item['total_capacity'] = $total_capacity;

        // 生徒数

        $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( session('school.login')['id'], array (
                    'entry_type'    => request('event_type_id'),
                    'relative_id'   => request('relative_id'),
                    'enter'         => 1  //参加
            )); 

        $this->_joined_memmber_no = 0;
        
        foreach ($entries as $value) {
            // Update 2017-06-08 : Count total_member
            if ($value['total_member']) { 
                $this->_joined_memmber_no += $value['total_member'];
            }
        }

        request()->merge($this->relative_item);
        
        view()->share('joined_memmber_no', $this->_joined_memmber_no);
        view()->share('total_fee', (float)$total_fee);


    }

    public function select(Request $request){
        $this->recoverWithInput($request, array('relative_id', 'msg_type_id', 'event_type_id', 'event_name', 'student_ids', 'enable_send_mail'));
        if (request()->has('schedule_flag_update')) {
            request()->offsetSet('schedule_flag', request('schedule_flag_update'));
            request()->offsetUnset('schedule_flag_update');
        }
        if (request()->has('schedule_date_update')) {
            request()->offsetSet('schedule_date', request('schedule_date_update'));
            request()->offsetUnset('schedule_date_update');
        }
        // ============
        // タイトル部分
        // ============
//        $event_type;
        $event_name = "";
        $school_year    = null;

        switch ($request->event_type_id) {
            case 2: // イベント
                $event_name = $this->relative_item['course_title'];
                // イベント受講料
                $course_fee_plan = array(); // each record student
                $course_fee     = array(); // infomation

                // fee_type 1:会員種別による料金設定
                if ($this->relative_item['fee_type'] == '1') {
                    $fee_plan_list = CourseFeePlanTable::getInstance()->getCourseFeeForStudentType($request->relative_id);
                    
                    foreach ($fee_plan_list as $idx => $value) {
                        $course_fee[] = array(
                            'name'  => $value['student_type_name'],
                            'fee'   => $value['fee'],
                            'fee_plan_name'  => $value['fee_plan_name'],
                            'payment_unit'   => $value['payment_unit']
                        );
//                        $course_fee_plan[$value['id']]['value'] = $value['student_type_name'].' | '.number_format(floor($value['fee'])).'（円）';
                        $course_fee_plan[$value['id']]['fee_plan_name'] = $value['fee_plan_name'];
                        $course_fee_plan[$value['id']]['payment_unit']  = $value['payment_unit'];
                        $course_fee_plan[$value['id']]['fee']           = floor($value['fee']);
                        $course_fee_plan[$value['id']]['payment_unit_text']  = ConstantsModel::$PAYMENT_UNIT_TEXT[$this->current_lang][$value['payment_unit']];

                        // 会員の受講料のデフォルト=会員の会員種別の金額
                        $course_fee_plan[$value['id']]['student_type_id'] = $value['student_type_id'];
                    }
                    
                } //else { fee_type 2:回数による料金設定
                    // foreach ($fee_plan_list as $idx => $fee_plan_row) {
                    //     $course_fee_plan[$fee_plan_row['id']] = $fee_plan_row['fee_plan_name'].' | '.number_format(floor($fee_plan_row['fee'])).'（円）';
                    // }
                // }

                // 2015-10-05 イベントの学年の初期設定
                if ($request->school_year && is_numeric($request->school_year)) {
                    $school_year = $request->school_year;
                }
                
                if (empty($request->event_name) && !$request->exists('school_category') && isset($this->relative_item['school_category'])) {
                    $request->offsetSet('school_category', $this->relative_item['school_category']);
//                    $school_catgory = $request->school_category;
                }
               
                if (empty($request->event_name) && !$request->exists('school_year') && isset($this->relative_item['school_year'])) {
                    $request->offsetSet('school_year', $this->relative_item['school_year']);
                    $school_year = $request->school_year;
                }

                // 2017-05-10 複数講師: Kieudtd
                $teacher_list = array();
                $teacher_list = CourseCoachTable::getInstance()->getCoachList($request->relative_id, session('school.login')['id']);
                
                $payment_method = ConstantsModel::$invoice_type[$this->current_lang];
                break;
            case 3: //プログラム
                $event_name = $this->relative_item['program_name'];
                // fee_type 1:会員種別による料金設定
                $program_fee_plan = array(); // each record student
                $program_fee     = array(); // infomation
                if ($this->relative_item['fee_type'] == '1') {
                    $fee_plan_list = ProgramFeePlanTable::getInstance()->getProgramFeeForStudentType($request->relative_id);
                    
                    foreach ($fee_plan_list as $idx => $value) {
                        $program_fee[] = array(
                            'name'  => $value['student_type_name'],
                            'fee'   => $value['fee'],
                            'fee_plan_name'  => $value['fee_plan_name'],
                            'payment_unit'   => $value['payment_unit']
                        );
//                        $program_fee_plan[$value['id']]['value'] = $value['student_type_name'].' | '.number_format(floor($value['fee'])).'（円）';
                        $program_fee_plan[$value['id']]['fee_plan_name'] = $value['fee_plan_name'];
                        $program_fee_plan[$value['id']]['payment_unit']  = $value['payment_unit'];
                        $program_fee_plan[$value['id']]['fee']           = floor($value['fee']);
                        $program_fee_plan[$value['id']]['payment_unit_text']  = ConstantsModel::$PAYMENT_UNIT_TEXT[$this->current_lang][$value['payment_unit']];
                        // 会員の受講料のデフォルト=会員の会員種別の金額
                        $program_fee_plan[$value['id']]['student_type_id'] = $value['student_type_id'];
                    }
                    
                }

                $lesson_list = LessonTable::getInstance()->getActiveList(array('program_id'=>$this->relative_item['id']), array('start_date'));

                $request->offsetSet('lesson_list', $lesson_list);
                $payment_method = ConstantsModel::$invoice_type[$this->current_lang];
                break;
            
            default:
                # code...
                return redirect('/school/home');
                break;
        }
        
        $request->offsetSet('event_type',$request->event_type_id);
        $request->offsetSet('event_name',$event_name);
        //Important: 編集機能
        if (!$this->relative_item['recruitment_finish']) {
            $request->offsetSet('editable', 1);
        } elseif ($this->relative_item['recruitment_finish'] && ($this->relative_item['recruitment_finish'] > date('Y-m-d H:i:s'))) {
            $request->offsetSet('editable', 1);
        } 

        // // ステータス
        // if (!$request->has('_student_types')) {
        //     // 初回表示
        //     $request->offsetSet('select_state', 1);
        // }

        $selected_ids = array();
        if ($request->has('student_ids')) {
            $selected_ids = array_merge($selected_ids, $request->student_ids);
            $request->offsetSet('student_ids', array());
        }
        
        // ------------------
        // 生徒種別の情報取得
        // ------------------
        $student_types = $this->getStudentType($request);

        $class_list = ClassTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']));

        // ========
        // 検索部分
        // ========
        $arry_search = array();
        // 帯色
        if ($request->has('select_grade')) {
            $arry_search ['select_grade'] = $request->select_grade;
        }
        if ($request->has('select_state')) {
            $arry_search ['select_state'] = $request->select_state;
        } else {
            // 初回表示
            $arry_search ['select_state'] = 1;
            $request->offsetSet('select_state', 1);
        }
        if ($request->has('fee_type_id')) {
            // $request->fee_type_id Ex:  70|1
            $arry_search ['fee_type_id'] = explode('|', $request->fee_type_id);
        }

        $student_name_kana  = "";
        $school_category    = null;
        if ($request->select_word) {
            $student_name_kana = $request->select_word;
        }
        if ($request->school_category && is_numeric($request->school_category))
        {
            $school_category = $request->school_category;
        }
        $exam_pref  = null; // 受験地域(都道府県）       2015/04/03
        $exam_city  = null; // 受験地域(市区町村）       2015/04/03
        $class_id   = null;
        $student_no = null;
        if ($request->class_id) { // プラン名     2015/04/03
            $class_id = $request->class_id;
        }
        if ($request->input_search_student_no) {          // 生徒番号     2015/09/28
            $student_no = $request->input_search_student_no;
        }  
        $pschool_id = session('school.login')['id'];

        // ========
        // 一覧部分
        // ========
        $mailmsg_tbl = MailMessageTable::getInstance();
        $list = $mailmsg_tbl-> getEventMailListAxis($pschool_id, $request->event_type_id, $request->relative_id, $student_name_kana, $school_category, $school_year, $exam_pref, $exam_city, $class_id, $student_types, $student_no, $arry_search);

        // TODO loop to add delivery_count, confirmation, fee && shift student have no action in this event or program when event's recruitment_finish is over
        // check event is over and set to request [is_recruitment_finished]
        $is_recruitment_finished = 0;
        if (!is_null($this->relative_item['recruitment_finish']) && $this->relative_item['recruitment_finish'] < date('Y-m-d H:i:s')) {
            $request->offsetSet('is_recruitment_finished', 1);
            $is_recruitment_finished = 1;
        }
        foreach ($list as $idy => &$row) {
            $condition = array(
                'type'          => $request->msg_type_id,
                'relative_ID'   => $request->relative_id,
                'student_id'    => $row['student_id'],
            );

            // Update by Kieu: 2017/06/06 : Count send mail = total_send
            $mail_message_record = $mailmsg_tbl->getRow($condition);
            if ($mail_message_record) {
                $delivery_count = $mail_message_record['total_send'];
                $confirmation   = ($mail_message_record['last_refer_date'] != null)? 1 : 0;
            } else {
                $delivery_count = 0;
                $confirmation   = 0;
            }

            $row['selected'] = 0;
            if (in_array($row['student_id'] , $selected_ids)) {
                $row['selected'] = 1;
            }
            
            $row['delivery_count']  = $delivery_count;
            $row['confirmation']    = $confirmation;
            $row['fee'] = number_format(floor($row['fee']));

            // delete student have no action in this event or program when event's recruitment_finish is over
            if ($request->has('is_recruitment_finished') && $row['delivery_count'] == 0 && is_null($row['eid'])){
                unset($list[$idy]);
            }

            if(!empty($list[$idy]['payment_method'])){
                $list[$idy]['invoice_type'] = Constants::$PAYMENT_TYPE[$list[$idy]['payment_method']];
            }
        }

        // $link = self::$ACTION_URL.'/select';
        // $link .= "?event_type_id=" . $request->event_type_id . "&relative_id=" . $request->relative_id . "&msg_type_id=" . $request->msg_type_id;
        // $description = ConstantsModel::$bread_list[$this->current_lang]['mail_select'];
        // $this->set_bread_list($link, $description);
        
        $is_merge_invoice = $request->is_merge_invoice;
        $invoice_background_color = self::invoice_background_color;
        $invoice_type = Constants::$invoice_type[session()->get('school.login.lang_code')];

        // TODO return view
        switch ($request->event_type_id) {
            case 2:
                return view('School.Mail_message.select', compact('lan', 'list', 'school_year', 'course_fee', 'course_fee_plan', 'teacher_list', 'payment_method' , 'class_list', 'is_merge_invoice','invoice_background_color','invoice_type','is_recruitment_finished'));
                break;
            case 3:
                return view('School.Mail_message.select_program', compact('lan', 'list', 'school_year', 'program_fee', 'program_fee_plan', 'class_list', 'is_merge_invoice','invoice_background_color','invoice_type','is_recruitment_finished','payment_method' ));
                break;
            default:
                # code...
                break;
        }
        
    }

    /**
     * Mail Select List is list of student selected for mailing
     * @param unknown $mail_select_list
     */
    public function completeMail(Request $request) {

        if(session()->has('errors')) {
            $request->session()->forget('errors');
        }

        $rules = [  'student_ids'       => 'required',
                    'schedule_date_update' => 'required_if:schedule_flag_update,on' 
                    ];
        $messsages = array(
            'student_ids.required' => '送信先を選択してください。', // TODO get msg from resource files
            'schedule_date_update.required_if' => '送信日は必須です。'
        );
        $validator = Validator::make(request()->all(), $rules, $messsages);
                
            if ($validator->fails()) { 
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
        $failed_deli_list = array();
        

        if ($request->event_type_id == 2) { // Event
            // TODO : $this->storeStudentRelEventType
            // 1: save fee_plan_id into StudentCourseRelTable (view info for portal)
            
            $this->storeStudentRelEventType($request);
        } elseif ($request->event_type_id == 3 ) { // Program
            // TODO : $this->storeStudentRelProgramType
            // 1: save fee_plan_id into StudentProgram (view info for portal)
            
            $this->storeStudentRelProgramType($request);      
        }
        
        // TODO store Event with schedule_date
        $this->saveSettingSendMailTime($request);

        if (count($request->student_ids) > 0) {
            foreach ($request->student_ids as $student_id) {

                $student = StudentTable::getInstance()->getRow(array('pschool_id'=>session('school.login')['id'], 'id'=>$student_id));
                
                // TODO store MailMessageTable with schedule_date
                if ($request->has('schedule_flag_update')) { // setting time auto send mail

                    $this->storeMailMessage($request, $student);
                } else { 
                    // TODO send mail immediately
                    // TODO Send mail & store MailMessageTable with send_date
                    $message_id = $this->storeMailMessage($request, $student);
                    $processName = null;
                    $processID = null;
                    $interface_type = null;
                    // TODO send mail
                    $studentTable = StudentTable::getInstance();
                    $studentInfoArray = array ();
                    $mailAddress = array ();

                    foreach ($request->student_ids as $item_id) {
                        $studentInfo = $studentTable->getOnlyStudentInfoBroadcastMail($item_id);
                        $studentInfoArray[] = $studentInfo;
                        $parent_info = ParentTable::getInstance()->getParentInfoByStudentId($studentInfo['id']);
                        if ($parent_info->parent_mailaddress1 != null) {
                            array_push($mailAddress, $parent_info->parent_mailaddress1);
                        }
                        if ($parent_info->parent_mailaddress2 != null) {
                            array_push($mailAddress, $parent_info->parent_mailaddress2);
                        }
                    }

                    $mailMessageTable = MailMessageTable::getInstance();
                    $mail_info = $mailMessageTable->getMailInfoToSend($message_id);
                    $event = CourseTable::getInstance()->load( $mail_info['relative_ID'] );
                    $program = ProgramTable::getInstance()->load( $mail_info['relative_ID'] );
                    if ($mail_info['type'] == 3) { // event
                        $mail_info = $this->getMailInfoWithEventOrProgram($message_id, $event, 2);
                    } elseif ($mail_info['type'] == 5) { // program
                        $mail_info = $this->getMailInfoWithEventOrProgram($message_id, $program, 3);
                    }

                    if ($request->event_type_id == 2) { // Event

                        $processName = 'イベント';
                        $processID = 2;
                        $interface_type = 1;// イベント画面
                        // $this->sendMailEventType($message_id);
                    } elseif ($request->event_type_id == 3 ) { // Program

                        $processName = 'プログラム';
                        $processID = 3;
                        $interface_type = 2;// プログラム画面
                        // $this->sendMailProgramType($message_id);
                    }

                    $data_template_for_mail = $mail_info;
                    $is_check = HandelCoopSendMail::coop_send_mail($interface_type, $studentInfoArray, $mailAddress, $processName, $processID, $message_id, $data_template_for_mail);
                    if (!$is_check) {
                        $error_send_mail = 'NULLメールがありますから、もう一度チェックお願い致します。';
                        $request->offsetSet('error_send_mail',$error_send_mail);
                        return redirect()->back()->withInput()->withErrors($error_send_mail);
                    }
                }
            }
        }
        
        if ($failed_deli_list) {
            session()->push('failed_deli_list', $failed_deli_list);
        } else {
            $message_type = 51;
        }

        return redirect()->route('mailMessage')->withInput();
//        return $this->select($request);
        // return Redirect::to('/school/course/list');
    }

    public function getMailInfoWithEventOrProgram($message_id, $object, $object_type) {

        $mailMessageTable = MailMessageTable::getInstance();
        $mail_info = $mailMessageTable->getMailInfoToSend($message_id);

        $mail_subject = null;
        if ($object_type == 2) {
            $mail_subject = 'イベントのお知らせ';
            $mail_info['event_id'] = $object['id'];
        } elseif ($object_type == 3) {
            $mail_subject = 'プログラムのお知らせ';
            $mail_info['program_id'] = $object['id'];
        }

        /* set mail info in view */
        // set subject of mail
        $mail_info['subject'] = (isset($object['mail_subject'])) ? $object['mail_subject'] : $mail_subject;
        //            $subject = mb_encode_mimeheader($mail_info['subject']);
        $subject = $mail_info['subject'];
        $template = null;
        if ($object_type == 2) {
            // set body of mail
            $mail_info['content'] = $object['course_description'];
            // set mail info
            $mail_info['title'] = $object['course_title'];
            $template = self::$EVENT['mail_url'];
        } else {
            // set body of mail
            $mail_info['content']   = $object['description'];
            // set mail info
            $mail_info['title'] = $object['program_name'];
            $template = self::$PROGRAM['mail_url'];
        }

        // set footer of mail
        $mail_info['footer'] = $object['mail_footer'];
        // create URL
        $hash_code = "?message_key=" . $mail_info['message_key'];
        $mail_info['url'] = $this->createActivateUrl($template, $hash_code);
        $mail_info['contact'] = $mail_info['school_mailaddress'];
        /* set mail info in view */

        return $mail_info;
    }
    
    private function saveSettingSendMailTime($request) {
        
        $relative_table = null;
        if ($request->event_type_id == 2) { // Event
            
            $relative_table = CourseTable::getInstance();
        } elseif ($request->event_type_id == 3 ) { // Program
            
            $relative_table = ProgramTable::getInstance();
        }

        // TODO store schedule_flag & schedule_date into
        if (!is_null($relative_table)) {
            $record = array(
                'id' => $request->relative_id,
                'schedule_flag' => ($request->has('schedule_flag_update'))? 1 : 0,
                'schedule_date' => ($request->has('schedule_flag_update'))? $request->schedule_date_update : null,
                'update_date'   => date('Y-m-d H:i:s'),
                'update_admin'  => session('school.login')['login_account_id']
            );
            $relative_table->save($record);
        }

    }

    private function storeMailMessage($request, $student) {
        $mail_msg_tbl = MailMessageTable::getInstance();
        $msg_type       = $request->msg_type_id;
        $relative_ID    = $request->relative_id;

        // TODO check exist Mail_Message
        $mail_message = $mail_msg_tbl->getActiveRow(['type'=>$msg_type, 'relative_ID'=>$relative_ID, 'student_id'=>$student['id']]);
        
        $save_m = array();
        if (empty($mail_message)) {
            
            $message_key = md5($this->generateRandomString(64));
            $save_m['type']         = $msg_type;
            $save_m['message_key']  = $message_key;
            $save_m['relative_ID']  = $relative_ID;
            $save_m['pschool_id']   = $student['pschool_id'];
            $save_m['parent_id']    = $student['parent_id'];
            $save_m['student_id']   = $student['id'];
            $save_m['total_send']   = 0; // first send

        } else {

            $save_m['id']           = $mail_message['id'];
            // $save_m['total_send']   = $mail_message['total_send']+1;
        }
        
        // 送信予約
        if ($request->has('schedule_flag_update')) {
            $save_m['schedule_date']    = $request->schedule_date_update;
        } 
        
        $message_id = $mail_msg_tbl -> save($save_m);

        return $message_id;
    }
    

    private function storeStudentRelEventType($request) {
        
        $mail_msg_tbl = MailMessageTable::getInstance();
        
        // custom by Kieu 2017/05/05: save fee_plan_id into StudentCourseRelTable (返事しない会員)
        $course_rel_table = StudentCourseRelTable::getInstance();
        $student_ids = $request->student_ids;

        foreach ($student_ids as $idx =>$student_id) {
            if ($request->has('_course_fee_plan_id'.$idx)) {
                $plan_id =request('_course_fee_plan_id'.$idx);
                $rel = $course_rel_table->getActiveRow($where=array('course_id'=>request('relative_id'), 'student_id' => $student_id));


                if (empty($rel)){
                    $course = array(
                            'course_id' => request('relative_id'),
                            'student_id' => $student_id,
                            'is_received' => 0,
                            'active_flag' => 1,
                            'register_date' => date('Y-m-d H:i:s'),
                            'register_admin' => session('school.login')['login_account_id'],
                            'plan_id' => $plan_id,
                    );
                    $course_rel_table->save($course);
                }else{
                    $course = array(
                            'id' => $rel['id'],
                            // 'active_flag' => !$rel['active_flag'],
                            'update_date' => date('Y-m-d H:i:s'),
                            'update_admin' => session('school.login')['login_account_id'],
                            'plan_id' => $plan_id
                    );
                    $course_rel_table->save($course);

                }
               
            }
        }

        // end custom
//         $list = array();
//         $index = 0;
//         $enter_data = $_REQUEST['enter'];

        
//         foreach ($student_ids as $idx =>$student_id)
//         {
//             $student = StudentTable::getInstance()->getRow($where=array('pschool_id'=>session('school.login')['id'], 'id'=>$student_id));
//             $parent_id = $student['parent_id'];
//             $parent = ParentTable::getInstance()->getRow($where=array('pschool_id'=>session('school.login')['id'], 'id'=>$parent_id));
//             $item['student'] = $student;
//             $condition = array(
//                     'type'          => $request->msg_type_id,
//                     'relative_ID'   => $request->relative_id,
//                     'student_id'    => $student_id

//             );
//             // Update by Kieu: 2017/06/06 : Count send mail = total_send
//             $mail_message_record = $mail_msg_tbl->getRow($condition);
//             if ($mail_message_record) {
//                 $delivery_count = $mail_message_record['total_send'];
//             } else {
//                 $delivery_count = 0;
//             }
            
//             // $confirmation : reply or not
//             $condition[] = 'last_refer_date is not null';
//             $mail_message_record = $mail_msg_tbl->getRow($condition);

//             $confirmation = ($mail_message_record)? 1 : 0;
            
            
//             $status = 0;
//             $enter = $enter_data[$index];
//             if (($enter == 1) && ($confirmation == 1))
//             {
//                 $status = 1;
//             }
//             $index = $index + 1;
//             $item['delivery_count'] = $delivery_count;
//             $item['confirmation'] = $confirmation;
//             $item['status'] = $status;
//             $item['parent_name'] = $parent['parent_name'];
//             $item['parent_mailaddress1'] = $parent['parent_mailaddress1'];
//             $item['parent_mailaddress2'] = $parent['parent_mailaddress2'];

//             if (isset($_REQUEST['_fee_plan_name'])) {
//                 if (isset($_REQUEST['_fee_plan_name'][$idx])) {
//                     $item['fee_plan_name'] = $_REQUEST['_fee_plan_name'][$idx];
//                 } else {
//                     $item['fee_plan_name'] = '';
//                 }
//                 if (isset($_REQUEST['_fee'][$idx])) {
//                     $item['fee'] = $_REQUEST['_fee'][$idx];
//                 } else {
//                     $item['fee'] = '';
//                 }
//             }
//             $list[] = $item;

// //          if( $delivery_count > 0 ){
// //              $sendmailcount++;
// //          }

//         }

//         return $list;
    }

    private function storeStudentRelProgramType($request) {
        
        $program_rel_table = StudentProgramTable::getInstance();
        $student_ids = $request->student_ids;

        foreach ($student_ids as $idx =>$student_id) {
            if ($request->has('_program_fee_plan_id'.$idx)) {
                $plan_id =request('_program_fee_plan_id'.$idx);
                $rel = $program_rel_table->getActiveRow(array('program_id'=>$request->relative_id, 'student_id' => $student_id));

                if (empty($rel)){
                    $program = array(
                            'program_id'    => $request->relative_id,
                            'student_id'    => $student_id,
                            'is_received'   => 0,
                            'active_flag'   => 1,
                            'register_date' => date('Y-m-d H:i:s'),
                            'register_admin' => session('school.login')['login_account_id'],
                            'plan_id'       => $plan_id,
                    );
                    $program_rel_table->save($program);
                }else{
                    $program = array(
                            'id' => $rel['id'],
                            // 'active_flag' => !$rel['active_flag'],
                            'update_date'   => date('Y-m-d H:i:s'),
                            'update_admin'  => session('school.login')['login_account_id'],
                            'plan_id'       => $plan_id
                    );
                    $program_rel_table->save($program);

                }
               
            }
        }
    }
    /**
     * 仕様変更：Input Payment Info
     */ 

    public function updateIsReceived(Request $request) {
    
        if($request->has('student_id') && $request->has('relative_id')) {
            // Get record relation
            $course_rel_table = StudentCourseRelTable::getInstance();
            $rel = $course_rel_table->getActiveRow($where=array('course_id'=>$request->relative_id, 'student_id' => $request->student_id));
            if ($rel) {
                $rel['is_received']     = 1;
                $rel['payment_type']    = $request->payment_type;
                $rel['payment_date']    = $request->payment_date;
                $rel['update_date']     = date('Y-m-d H:i:s');
                $rel['update_admin']    = session('school.login')['login_account_id'];
                
                $course_rel_table->save($rel);
            }
        }

        return redirect ()->to ('/school/mailMessage/select')->withInput();
    }
    /**
     * アクティベートURLの生成
     */
    protected function createActivateUrl($type, $hash_code) {
        return 'http://' . $_SERVER['HTTP_HOST'] . $type . '/' . $hash_code;
    }

    /**
     * メール本文の作成
     */
    protected function createMailMessage($tpl, $assign_var) {
        
        $tpl_path = resource_path () . '/views/_mail/'.$tpl; 
        
        if (File::exists ( $tpl_path )) {
            
            $menu_content = File::get( $tpl_path );
            $abc = view('_mail.event_mail_notification');
            $view = View::make('_mail.event_mail_notification', ['mail' => $assign_var]);

            return $view->render();
        } else {
            throw new Exception( 'Template File Not Found!!' );
        }
        
        // $file = $this->_smarty->template_dir . $tpl_path;

        // if( file_exists( $file ) ){
        //     // 本文作成
        //     // $this->_smarty->assign('mail',    $assign_var);
        //     $message = 'aaaaaaaaaaaaaaaa';
        //     // $message = $this->_smarty->fetch($tpl_path);

        //     return $message;
        // }
        // else
        // {
        //     throw new Exception( 'Template File Not Found!!' );
        // }
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

    public function executeEntry(Request $request) 
    {
        // if ($request->exists('join_regist')){
        //     // 入力チェック
        //     $errors = array();
            
        //     $rules = ['_course_fee_plan_id' => 'required'];
        //     $messsages = array('_course_fee_plan_id.required' => '受講料は必須です。'); // TODO get msg from resource files

        //     $validator = Validator::make ( request ()->all (), $rules, $messsages );
        
        //     if ($validator->fails ()) {
        //         return redirect ()->to ('/school/mailMessage/select')->withInput ()->withErrors ( $validator->errors () );
        //     }
            
        // }

        $entry_table = EntryTable::getInstance();
        $entry_table->beginTransaction();
        $entry = $entry_table->getActiveRow(array('entry_type'=>$request->event_type_id, 'student_id' => $request->student_id, 'relative_id' => $request->relative_id));

        // 登録処理
        try {
            $total_member = ($request->total_member)? $request->total_member : 1;
            $relative_code = ($request->event_type_id == 2) ? $this->relative_item['course_code'] : $this->relative_item['program_code'];
            if(!$request->offsetExists('payment_method_entry')){
                $request->payment_method_entry = Constants::CASH;
            }
            if(!$request->offsetExists('is_merge_invoice_entry')){
                $request->offsetSet('is_merge_invoice_entry',0);
            }
            //get invoice year month
            $pschool_id = session ('school.login.id');
            $pschool = PschoolTable::getInstance ()->load ($pschool_id);
            $invoice_closing_date = $pschool['invoice_closing_date'] == 99 ? date ('Y-m-t') : date ('Y-m-d', strtotime (date ('Y-m-') . $pschool['invoice_closing_date']));
            $payment_style = $pschool['payment_style'];
            $today = date ('Y-m-d');
            $list_payment = PaymentMethodPschoolTable::getInstance ()->getActiveList (array ('pschool_id' => $pschool_id), array ('sort_no'));
            $is_koza = false;
            foreach ($list_payment as $k => $v) {
                if ($v['payment_method_code'] == Constants::TRAN_RICOH) {
                    $is_koza = true;
                }
            }

            //
            $invoice_year_month = null;
            $invoiceController = new InvoiceController();

            // payment_style = 1 : prepay
            if ($payment_style == 1) {
                if ($today > $invoice_closing_date) {
                    $invoice_year_month = date ('Y-m', strtotime (date ("Y-m-d") . "+2 month"));
                } else {
                    if ($is_koza) {

                        $dead_line = $invoiceController->getDeadLineOfPayment ($pschool_id, date ('Y-m', strtotime (date ("Y-m-d") . "+1 month")), 2);  // increase 1 month cause this function will decrease 1 when return.
                        if ($today < $dead_line) {
                            $invoice_year_month = date ('Y-m', strtotime (date ("Y-m-d") . "+1 month"));
                        } else {
                            $invoice_year_month = date ('Y-m', strtotime (date ("Y-m-d") . "+2 month"));
                        }
                    } else {
                        $invoice_year_month = date ('Y-m', strtotime (date ("Y-m-d") . "+1 month"));
                    }
                }
            } else { // payment_style = 2 : postpay
                if ($today > $invoice_closing_date) {
                    $invoice_year_month = date ('Y-m', strtotime (date ("Y-m-d") . "+1 month"));
                } else {
                    if ($is_koza) {
                        $dead_line = $invoiceController->getDeadLineOfPayment ($pschool_id, date ('Y-m', strtotime (date ("Y-m-d") . "+1 month")), 2);  // increase 1 month cause this function will decrease 1 when return.
                        if ($today < $dead_line) {
                            $invoice_year_month = date ('Y-m');
                        } else {
                            $invoice_year_month = date ('Y-m', strtotime (date ("Y-m-d") . "+1 month"));
                        }
                    } else {
                        $invoice_year_month = date ('Y-m');
                    }
                }
            }
            //

            switch ($request->entry_status) {
                case 3:
                    // case 3:未応答
                    if (!empty($entry)) {
                        $entry_table->deleteRow(['id'=>$entry['id']]);
                    }
                    break;
                
                default:
                    // case 1:参加, 2:不参加
                    if (empty($entry)){
                        $new_entry = array(
                            'entry_type'    => $request->event_type_id,
                            'student_id'    => $request->student_id,
                            'relative_id'   => $request->relative_id,
                            'relative_date' => date('Y-m-d H:i:s'),
                            'status'        => 0, // 未支払い
                            'enter'         => ($request->entry_status == 1)? 1 : 0,
                            'total_member'  => $total_member,
                            'register_date' => date('Y-m-d H:i:s'),
                            'register_admin' => session('school.login')['login_account_id'],
                            'code'          => $this->generateEntryCode($relative_code),
                        );
                        // お支払方法設定: 現金・振込
                        if ($new_entry['enter'] == 1) {
                            $new_entry['payment_method'] = $request->payment_method_entry;
                            $new_entry['is_merge_invoice'] = $request->is_merge_invoice_entry;
                            $new_entry['enter_date'] = date('Y-m-d H:i:s');
                        }

                        $new_id = $entry_table->save($new_entry);
                    }else{
                        
                        $new_entry = array(
                            'id'            => $entry['id'],
                            'enter'         => ($request->entry_status == 1)? 1 : 0,
                            'status'        => 0, // 未支払い
                            'total_member'  => $total_member,
                            'update_date'   => date('Y-m-d H:i:s'),
                            'update_admin'  => session('school.login')['login_account_id'],
                        );
                        // お支払方法設定: 現金・振込
                        if ($new_entry['enter'] == 1) {
                            $new_entry['payment_method'] = $request->payment_method_entry;
                            $new_entry['is_merge_invoice'] = $request->is_merge_invoice_entry;
                            $new_entry['enter_date'] = date('Y-m-d H:i:s');
                        }
                        if (is_null($entry['code'])) {
                            $new_entry['code']  = $this->generateEntryCode($relative_code);
                        }

                        $new_id = $entry_table->save($new_entry);
                    }

                    break;
            }
            
            if ($request->has('event_type_id')) {
                $student_relative_table = null;
                $plan_id = null;
                $record = array();
                // イベント

                if ($request->event_type_id == 2) { 
                    $student_relative_table = StudentCourseRelTable::getInstance();
                    $rel = $student_relative_table->getActiveRow(array('course_id'=>$request->relative_id, 'student_id' => $request->student_id));
                    if($request->has('_course_fee_plan_id')) {
                        $plan_id = $request->_course_fee_plan_id;
                    }
                // プログラム
                } elseif ($request->event_type_id == 3) {
                    $student_relative_table = StudentProgramTable::getInstance();
                    $rel = $student_relative_table->getActiveRow(array('program_id'=>$request->relative_id, 'student_id' => $request->student_id));
                    if($request->has('_program_fee_plan_id')) {
                        $plan_id = $request->_program_fee_plan_id;
                    }
                }

                if (!is_null($student_relative_table)) {
                    switch ($request->entry_status) {
                        case 3:
                            // case 3:未応答
                            if (!empty($rel)) {
                                $student_relative_table->deleteRow(['id'=>$rel['id']]);
                            }
                            break;
                        default:
                            if (empty($rel)){
                                $record = array(
                                        // 'course_id'     => $request->relative_id,
                                        'student_id'    => $request->student_id,
                                        'is_received'   => 0,
                                        'active_flag'   => ($request->entry_status == 1)? 1 : 0,
                                        'register_date' => date('Y-m-d H:i:s'),
                                        'register_admin' => session('school.login')['login_account_id'],
                                        'plan_id'       => $plan_id,
                                );
                                // イベント
                                if ($request->event_type_id == 2) { 
                                    $record['course_id']    = $request->relative_id;
                                // プログラム
                                } elseif ($request->event_type_id == 3) {
                                    $record['program_id']   = $request->relative_id;
                                }

                            }else{
                                $record = array(
                                        'id'            => $rel['id'],
                                        'active_flag'   => ($request->entry_status == 1)? 1 : 0,
                                        'update_date'   => date('Y-m-d H:i:s'),
                                        'update_admin'  => session('school.login')['login_account_id']
                                );
                                if (!is_null($plan_id)) {
                                    $record['plan_id']   = $plan_id;
                                }
                            }
                            $student_relative_table->save($record);
                            break;
                    }
                    
                }
            }
            //create invoice record
            if ($request->entry_status == 1) {

                $is_merge_invoice = $request->is_merge_invoice_entry;  // 1 => merge , 0 => unmerge

                if(!$is_merge_invoice) {  // do not merge to periodic invoice -> create single record with is_nyukin = 1

                    $student = StudentTable::getInstance ()->load ($request->student_id);
                    $parent = ParentTable::getInstance()->load($student['parent_id']);
                    $request->offsetSet ('payment_method', $request->payment_method_entry);
                    $request->offsetSet ('entry_type', $request->event_type_id);

                    $invoice = new BaseInvoice();
                    $request->offsetSet ('parent_id', $parent['id'] );
                    $request->offsetSet ('invoice_year_month', $invoice_year_month);
                    $request->offsetSet ('is_portal', true);
                    $request->offsetSet ('id', session ('school.login.id'));
                    $request->offsetSet ('relative_id', $request->relative_id);
                    $request->offsetSet ('entry_id', !empty($new_id)? $new_id : $new_entry['id']);

                    $invoice->createInvoice($request, $parent['invoice_type'],false);

                    // after creating invoice record -> create mail message record

                    $this->storeMailMessage($request, $student);

                }else{ // merge to invoice => get newest invoice_month and creat record , nyukin = 0
                    $student = StudentTable::getInstance ()->load ($request->student_id);
                    $parent = ParentTable::getInstance()->load($student['parent_id']);
                    $request->offsetSet ('payment_method', $request->payment_method_entry);

                    $invoice = new BaseInvoice();
                    $request->offsetSet ('parent_id', $parent['id'] );
                    $request->offsetSet ('invoice_year_month', $invoice_year_month);
                    $request->offsetSet ('id', session ('school.login.id'));
                    $request->offsetSet ('entry_type', $request->event_type_id);
                    $request->offsetSet ('entry_id', !empty($new_id)? $new_id : $new_entry['id']);

                    $invoice->createInvoice($request, $parent['invoice_type'],false);
                }
            }else{
                // delete nyukin record base on entry

                if(!empty($entry) && !empty($entry['invoice_id'])){
                    if($entry['is_merge_invoice'] == 0){
                        InvoiceHeaderTable::getInstance()->deleteRow(array('id' => $entry['invoice_id']));
                    }else{
                        $filter = array(
                            'invoice_id' => $entry['invoice_id'],
                        );
                        if($entry['entry_type'] == 2){
                            $filter['course_id'] = $entry['relative_id'];
                        }else{
                            $filter['program_id'] = $entry['relative_id'];
                        }
                        InvoiceItemTable::getInstance()->deleteRow($filter);
                        $invoice = new BaseInvoice();
                        $invoice->updateAmountInvoiceHeader($entry['invoice_id']);
                    }
                }
            }
            // 2017-05-17 Update by Kieu
            // TODO check member_capacity & application_deadline to determine close event
            $is_join = ($request->entry_status == 1)? true: false;
            $this->checkToCloseEvent($request, $total_member, $is_join);

        } catch (Exception $ex) {
            $entry_table->rollBack();
        }
        $entry_table->commit();

        // entry with credit card -> send mail to parent to payment
        if($request->entry_status == 1 && $request->payment_method_entry == Constants::CRED_ZEUS){
            $request->offsetSet('student_ids',array($request->student_id));
            return $this->completeMail($request);
        }

        return redirect ()->to ('/school/mailMessage/select')->withInput();
    }

    public function entryMulti( Request $request) {
        //
        if (!$request->has('student_ids')) {
            return redirect ()->to ('/school/mailMessage/select')->withInput();
        }

        $entry_table = EntryTable::getInstance();
        $entry = $entry_table->getActiveList(array('entry_type'=>$request->event_type_id, 'student_id IN (' . implode(',', $request->student_ids) . ')', 'relative_id' => $request->relative_id));
        // 登録処理

        $entry_list = array();
        foreach ($entry as $item) {
            $entry_list[$item['student_id']] = $item;
        }

        try {
            $relative_code = ($request->event_type_id == 2) ? $this->relative_item['course_code'] : $this->relative_item['program_code'];
            switch ($request->entry_flag) {
                case 3:
                    // case 3:未応答
                    foreach ($entry_list as $item) {
                        $entry_table->deleteRow(['id'=>$item]);
                    }
//                    if (!empty($entry)) {
//                        $entry_table->deleteRow(['id'=>$entry['id']]);
//                    }
                    break;

                default:
                    // case 1:参加, 2:不参加
                    foreach ($request->student_ids as $student_id) {
                        if (!array_key_exists($student_id, $entry_list)){
                            $new_entry = array(
                                'entry_type'    => $request->event_type_id,
                                'student_id'    => $student_id,
                                'relative_id'   => $request->relative_id,
                                'relative_date' => date('Y-m-d H:i:s'),
                                'status'        => 0, // 未支払い
                                'enter'         => ($request->entry_flag == 1)? 1 : 0,
                                'total_member'  => 1,
                                'register_date' => date('Y-m-d H:i:s'),
                                'register_admin' => session('school.login')['login_account_id'],
                                'code'          => $this->generateEntryCode($relative_code),
                            );
                            // お支払方法設定: 現金・振込
                            if ($new_entry['enter'] == 1) {
                                $new_entry['payment_method'] = Constants::CASH;
                            }
//                            $entry_table->save($new_entry);
                        } else {

                            $new_entry = array(
                                'id'            => $entry_list[$student_id]['id'],
                                'enter'         => ($request->entry_flag == 1)? 1 : 0,
                                'status'        => 0, // 未支払い
                                'total_member'  => 1,
                                'update_date'   => date('Y-m-d H:i:s'),
                                'update_admin'  => session('school.login')['login_account_id'],
                            );
                            // お支払方法設定: 現金・振込
                            if ($new_entry['enter'] == 1) {
                                $new_entry['payment_method'] = Constants::CASH;
                            }
                            if (is_null($entry_list[$student_id]['code'])) {
                                $new_entry['code']  = $this->generateEntryCode($relative_code);
                            }

//                            $entry_table->save($new_entry);
                        }
                        $entry_table->save($new_entry);
                    }
                    break;
            }

            if ($request->has('event_type_id')) {
                $student_relative_table = null;
                // イベント
                if ($request->event_type_id == 2) {
                    $student_relative_table = StudentCourseRelTable::getInstance();
                    $rel = $student_relative_table->getActiveList(array('course_id'=>$request->relative_id, 'student_id IN (' . implode(',', $request->student_ids) . ')'));
//                    if($request->has('_course_fee_plan_id')) {
//                        $plan_id = $request->_course_fee_plan_id;
//                    }
                    // プログラム
                } elseif ($request->event_type_id == 3) {
                    $student_relative_table = StudentProgramTable::getInstance();
                    $rel = $student_relative_table->getActiveList(array('program_id'=>$request->relative_id, 'student_id IN (' . implode(',', $request->student_ids) . ')'));
//                    if($request->has('_program_fee_plan_id')) {
//                        $plan_id = $request->_program_fee_plan_id;
//                    }
                }

                $student_rel_list = array();
                foreach ($rel as $item) {
                    $student_rel_list[$item['student_id']] = $item['id'];
                }
                if (!is_null($student_relative_table)) {
                    switch ($request->entry_flag) {
                        case 3:
                            // case 3:未応答
                            foreach ($student_rel_list as $item) {
                                $student_relative_table->deleteRow(['id'=>$item]);
                            }
//                            if (!empty($rel)) {
//                                $student_relative_table->deleteRow(['id'=>$rel['id']]);
//                            }
                            break;
                        default:
                            foreach ($request->student_ids as $key=>$student_id) {
                                if (!array_key_exists($student_id, $student_rel_list)){
                                    $record = array(
                                        'student_id'    => $student_id,
                                        'is_received'   => 0,
                                        'active_flag'   => ($request->entry_flag == 1)? 1 : 0,
                                        'register_date' => date('Y-m-d H:i:s'),
                                        'register_admin' => session('school.login')['login_account_id'],
                                    );
                                    // イベント
                                    if ($request->event_type_id == 2) {
                                        $record['course_id']    = $request->relative_id;
                                        $record['plan_id']    = ($request->has('_course_fee_plan_id'.$key))? $request->get('_course_fee_plan_id'.$key): null;
                                        // プログラム
                                    } elseif ($request->event_type_id == 3) {
                                        $record['program_id']   = $request->relative_id;
                                        $record['plan_id']    = ($request->has('_program_fee_plan_id'.$key))? $request->get('_program_fee_plan_id'.$key): null;

                                    }
                                } else {
                                    $record = array(
                                        'id'            => $student_rel_list[$student_id],
                                        'active_flag'   => ($request->entry_flag == 1)? 1 : 0,
                                        'update_date'   => date('Y-m-d H:i:s'),
                                        'update_admin'  => session('school.login')['login_account_id'],
                                        'plan_id'       => ($request->has('_course_fee_plan_id'.$key))? $request->get('_course_fee_plan_id'.$key): null,
                                    );
// イベント
                                    if ($request->event_type_id == 2) {
                                        $record['plan_id']    = ($request->has('_course_fee_plan_id'.$key))? $request->get('_course_fee_plan_id'.$key): null;
                                        // プログラム
                                    } elseif ($request->event_type_id == 3) {
                                        $record['plan_id']    = ($request->has('_program_fee_plan_id'.$key))? $request->get('_program_fee_plan_id'.$key): null;

                                    }
                                }
                            $student_relative_table->save($record);
                            }
                            break;
                    }

                }
            }
        } catch (Exception $e) {
            // TODO log error message
        }
        $is_join = ($request->entry_flag == 1)? true: false;
        $this->checkToCloseEvent($request, count($request->student_ids), $is_join);

        return redirect ()->to ('/school/mailMessage/select')->withInput();
    }

    public function executeEntry2(Request $request)
    { 
        
        $entry_table = EntryTable::getInstance();
        $entry_list  = $entry_table->getActiveList($where = array('entry_type'=>$request->event_type_id, 'student_id IN ('.implode(',', $request->student_ids).')', 'relative_id' => $request->relative_id));
        $entry_select = array();
        foreach ($entry_list as $key => $value) {
            $entry_select[$value['student_id']] = $value;
        }

        // 登録処理
        try {
            $all_member = 0;// count all member join
            foreach ($request->student_ids as $key => $value) {
                $total_member   = ($request->has('student_total.'.$value))? $request->input('student_total.'.$value) : 1;
                $all_member     +=  $total_member;
                if (array_key_exists($value, $entry_select)) {
                    $entry_id = $entry_select[$value]['id'];
                    $entry = array(
                        'id'            => $entry_id,
                        'enter'         => 1,
                        'total_member'  => $total_member,
                        'update_date'   => date('Y-m-d H:i:s'),
                        'update_admin'  => session('school.login')['login_account_id']
                    );
                } else {
                    $entry = array(
                        'entry_type'    => $request->event_type_id,
                        'student_id'    => $value,
                        'relative_id'      => $request->relative_id,
                        'relative_date' => date('Y-m-d H:i:s'),
                        'status'        => 2,
                        'enter'         => 1,
                        'total_member'  => $total_member,
                        'register_date' => date('Y-m-d H:i:s'),
                        'register_admin' => session('school.login')['login_account_id'],
                        // 'code'          => $this->relative_item['course_code']. '-' . $this->getDateWithMilliseconds(),
                    );

                    if ($request->event_type_id == 2) {  // Event

                        $entry['code'] = $this->relative_item['course_code']. '-' . $this->getDateWithMilliseconds();
                    } elseif ($request->event_type_id == 3) { // Program
                        
                        $entry['code'] = $this->relative_item['program_code']. '-' . $this->getDateWithMilliseconds();
                    }

                }
                
                $entry_table->save($entry);
            }

            if ($request->has('event_type_id')) {
                $student_relative_table = null;
                $plan_name = null;
                $record = array();
                // イベント
                if ($request->event_type_id == 2) { 
                    $student_relative_table = StudentCourseRelTable::getInstance();
                    $rel = $student_relative_table->getActiveList(array('course_id'=>$request->relative_id, 'student_id IN ('.implode(',', $request->student_ids).')'));
                    $plan_name = '_course_fee_plan_id';
                    
                // プログラム
                } elseif ($request->event_type_id == 3) {
                    $student_relative_table = StudentProgramTable::getInstance();
                    $rel = $student_relative_table->getActiveList(array('program_id'=>$request->relative_id, 'student_id IN ('.implode(',', $request->student_ids).')'));
                    $plan_name = '_program_fee_plan_id';
                   
                }
                $rel_select = array();
                foreach ($rel as $value) {
                    $rel_select[$value['student_id']] = $value;
                }

                if (!is_null($student_relative_table)) {

                    foreach ($request->student_ids as $key => $value) {
                        $plan_id = null;

                        if ($request->has($plan_name.$key)) {
                            $plan_id = request($plan_name.$key);
                        } 
                        
                        if (array_key_exists($value, $rel_select)) {
                            
                            $record = array(
                                'id' => $rel_select[$value]['id'],
                                'active_flag'   => 1,
                                'update_date'   => date('Y-m-d H:i:s'),
                                'update_admin'  => session('school.login')['login_account_id']
                            );
                            if (!is_null($plan_id)) {
                                $record['plan_id']   = $plan_id;
                            }
                        } else {
                            $record = array(
                                'student_id'    => $value,
                                'is_received'   => 0,
                                'active_flag'   => 1,
                                'register_date' => date('Y-m-d H:i:s'),
                                'register_admin' => session('school.login')['login_account_id'],
                                'plan_id'       => $plan_id,
                            );

                            // イベント
                            if ($request->event_type_id == 2) { 
                                $record['course_id']    = $request->relative_id;
                            // プログラム
                            } elseif ($request->event_type_id == 3) {
                                $record['program_id']   = $request->relative_id;
                            }
                        }
                        
                    $student_relative_table->save($record);
                    }
                }
            }

            // 2017-05-17 Update by Kieu
            // TODO check member_capacity & application_deadline to determine close event
            $this->checkToCloseEvent($request, $all_member);
        } catch (Exception $ex) {
            //echo "rollback";
        }
        
        return redirect ()->to ('/school/mailMessage/select')->withInput();
    }

    private function checkToCloseEvent($request ,$member_cnt, $is_join=true) {
        $relative_table = null;
        if ($request->event_type_id == 2) { // Event
            $relative_table = CourseTable::getInstance();
        } elseif ($request->event_type_id == 3) { // Program
            $relative_table = ProgramTable::getInstance();
        }

        if (!is_null($relative_table)) {
            $total_capacity = $this->relative_item['total_capacity'];
            
            if ($this->relative_item['application_deadline'] == 1) {
                // TODO count joined 
                $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( session('school.login')['id'], array (
                        'entry_type'    => $request->event_type_id, // 2: Event, 3:Program
                        'relative_id'   => $this->relative_item['id'],
                        'enter' => 1 
                ));

                $joined_memmber_no = 0;
                foreach ($entries as $value) {
                    if ($value['total_member']) { 
                        $joined_memmber_no += $value['total_member'];
                    }
                }

                if ($joined_memmber_no >= $total_capacity && ($this->relative_item['recruitment_finish'] > date('Y-m-d H:i:s') || is_null($this->relative_item['recruitment_finish']))) {

                    $record_update = array(
                        'id' => $this->relative_item['id'],
                        'recruitment_finish' => date('Y-m-d H:i:s'),
                        );
                    $relative_table->save($record_update);
                } else {
//                    if ($this->relative_item['recruitment_finish'] < date('Y-m-d H:i:s')) {
//                        $record_update = array(
//                            'id' => $this->relative_item['id'],
//                            'recruitment_finish' => date('Y-m-d H:i:s', strtotime(' +1 day')),
//
//                        );
//                        $relative_table->save($record_update);
//                    }

                }
            }
        }
        
    }
    /**
    * Get Now with milliseconds Ex: 170609082550493685
    */
    private function getDateWithMilliseconds() {
        $micro = microtime(true);
        $micro = sprintf("%06d",($micro - floor($micro)) * 1000000);
        $d = new \DateTime( date('Y-m-d H:i:s.'.$micro, $micro) );
        return $d->format("ymdHisu");
    }

    private function getStudentType($request) {
        $student_types = array();

        $studentTypeList = array();
        $pschool_parents = array(); // list all pschool of parent
        $parent_list = HierarchyTable::getInstance ()->getParentPschoolIds( session('school.login')['id'] );
        if (!empty($parent_list)) {
            if (isset($parent_list['pschool_id'])) {
                $pschool_parents[] = $parent_list['pschool_id'];
            } else {
                $pschool_parents = $parent_list;
            }
        }
        $pschool_parents[] = session('school.login')['id']; 
        $schools = implode(',', $pschool_parents);
        
        $studentTypeList = MStudentTypeTable::getInstance()->getList(array("pschool_id IN(" . $schools . ")") );
        $dispStudentTypeList = array();
        if (!empty($studentTypeList)){
            foreach ($studentTypeList as $row){
                $dispStudentTypeList['_student_types'][$row['id']] = $row;
            }
        }
        if ($request->has('_student_types')) {
            
            $student_types = $request->_student_types;
        } else {
            
        }

        if ($request->has('_student_types')) {
            foreach ($dispStudentTypeList['_student_types'] as $index => &$value) {
                if (array_key_exists($index,$request->_student_types)) {
                    $value['is_display'] = 1;
                } else {
                    $value['is_display'] = 0;
                }
            }

        }
        $request->merge($dispStudentTypeList);
        return $student_types;
    }

    /**
     * Generate Entry Code
     * Ex: [Event_code]-[EVENT]-[Student_ID]
     * Ex: [Program_code]-[PROGRAM]-[Student_ID]
     */
    private function generateEntryCode($relative_code){
        $code = $relative_code . '-' . strtoupper(uniqid());
        return $code;
    }

    public function ajaxGetEventParentInfo(Request $request){

        if (!$request->ajax()) {
            throw new Exception();
        }
        try{
            $student_id = $request->student_id;
            $student = StudentTable::getInstance()->load($student_id);
            $parent = ParentTable::getInstance()->load($student['parent_id']);

            $methods_arr = explode(',',$request->payment_method);

            foreach ($methods_arr as $k => $v) {
                if (isset(Constants::$PAYMENT_TYPE[$v])) {
                    $payment_methods[$v] = Constants::$invoice_type[session()->get('school.login.language')][Constants::$PAYMENT_TYPE[$v]];
                }
            }

            $is_merge_invoice = $request->is_merge_invoice;
            $parent_invoice_type = array_flip (Constants::$PAYMENT_TYPE)[$parent['invoice_type']];
            $merge_invoice_type = $payment_methods;

            if($is_merge_invoice){
                foreach ($merge_invoice_type as $k => $method){
                    if($k != $parent_invoice_type){
                        unset($merge_invoice_type[$k]);
                    }
                }
            }
            $unmerge_invoice_type= $payment_methods;
            if(isset($unmerge_invoice_type["TRAN_RICOH"])){
                unset($unmerge_invoice_type["TRAN_RICOH"]);
            }
            $request->offsetSet('merge_invoice_type',$merge_invoice_type);
            $request->offsetSet('unmerge_invoice_type',$unmerge_invoice_type);


            return response()->json(['status' => true, 'message' => $request->all() ]);

        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e ]);
        }
    }
}
