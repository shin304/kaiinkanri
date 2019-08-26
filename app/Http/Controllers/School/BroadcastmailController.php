<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\MailMessageTable;
use App\Model\MStudentTypeTable;
use App\Model\MPrefTable;
use App\Model\MCityTable;
use App\Model\ClassTable;
use App\Model\BroadcastMailTable;
use App\Model\BroadcastMailStudentRelTable;
use App\Model\BroadcastMailAddresseeRelTable;
use App\Model\StudentTable;
use App\Model\ParentTable;
use App\Model\SchoolMenuTable;
use App\Model\StudentClassTable;
use App\Model\CoachTable;
use App\Model\ClassTeacherRelTable;
use App\Lang;
use App\Model\UploadFilesTable;
use Illuminate\Http\Request;
use App\ConstantsModel;
use App\Http\Controllers\Common\AuthConfig;
use App\Model\StaffTable;
use App\Http\Controllers\Common\Validaters;
use App\Http\Controllers\Common\ValidateRequest;
use App\Mail\MailTemplate;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Common\HandelCoopSendMail;
use DB;

/**
 * 生徒情報
 */
class BroadcastmailController extends _BaseSchoolController {
    use \App\Common\Email;

    private static $MAIL_TEMPLATE = '_mail.bcmail_notification';
    protected static $ACTION_URL = 'broadcastmail';
    protected static $LANG_URL = 'broadcast_mail';
    private static $TEMPLATE_URL = '/school/broadcast_mail';
    private static $STUDENT_TEMPLATE = 'student';
    private $studentTypes;
    private $schoolCategory;
    private $send_flag;
    private $bc_option;
    private $teacher_type;
    const SESSION_SEARCH_KEY = 'session.school.broadcastmail.search.key';
    private $_broadcast_search_item = ['_c'];
    private $_broadcast_search_session_key = 'broadcast_search_form';
    const SCHOOL_DIR        = 'school/';
    const BROADCAST_CATEGORY_CODE    = '2';
    private static $UPLOAD_DIR = '/storage/uploads/';

    public function __construct(Request $request) {
        parent::__construct ();
        $message_content = parent::getMessageLocale();
        $lan = new Lang ( $message_content );
        view ()->share ( 'lan', $lan );
        
        $language = 1;
        if (session()->get( 'school.login.language') !== null) {
            $language = session()->get( 'school.login.language');
        }
        
        $school_ids = array ();
        $school_ids [] = session()->get('school.login.id');

        $student_types = MStudentTypeTable::getInstance ()->getStudentTypeList($school_ids, $language );
        $this->studentTypes = $student_types;
        // 生徒区分
        
        $this->schoolCategory = ConstantsModel::$dispSchoolCategory;
        
        $this->send_flag = ConstantsModel::$send_flag [session()->get('school.login.language' )];
        $this->bc_option = ConstantsModel::$bc_option [session()->get('school.login.language' )];
        $this->teacher_type = ConstantsModel::$teacher_type [session()->get('school.login.language')];
    }
    public function execute(Request $request) {
        // メニューから遷移のとき、セッションに検索条件があったらそれをクリアする
        if (session()->get( self::SESSION_SEARCH_KEY ) !== null) {
            session()->forget( self::SESSION_SEARCH_KEY );
        }
        return $this->executeSearch ( $request );
    }
    public function executeSearch(Request $request) {
        $this->_initSearchDataFromSession($this->_broadcast_search_item, $this->_broadcast_search_session_key);
        $this->getBcOption($request );

        if (! $request->offsetExists( '_c' ) && session()->get( self::SESSION_SEARCH_KEY ) !== null) {
            $request->offsetSet('_c',session()->get( self::SESSION_SEARCH_KEY ));
        }
        // 検索条件が設定されていない場合
        if (! $request->offsetExists('_c')) {
            $request->offsetSet('_c',array());
        }
        
        // 送信済みメール一覧の取得
        $arry_search = array ();
        $arry_search ['pschool_id'] = session()->get( 'school.login.id' );
        if (! empty ( $request->_c['input_search'] )) {
            $arry_search ['input_search'] = $request->_c['input_search'];
        }
        $broadcast_mail_list = array ();
        $broadcast_mail_list = BroadcastMailTable::getInstance ()->getQueryList ( $arry_search, $request);

        $res_list = array ();
        $studentTypeList = MStudentTypeTable::getInstance ()->getList ( array (
                'pschool_id' => session()->get( 'school.login.id' )
        ) );
        $addressee_rel_tbl = BroadcastMailAddresseeRelTable::getInstance ();
        foreach ( $broadcast_mail_list as $mail ) {
            $res = $mail;
            $parent_count = $addressee_rel_tbl->getParentCount ( $mail ['id'] );
            $res ['parent_cnt'] = $parent_count;
            $teacher_count = $addressee_rel_tbl->getTeacherCount ( $mail ['id'] );
            $res ['teacher_cnt'] = $teacher_count;
            foreach ( $studentTypeList as $type ) {
                $res ['student_cnt'] [$type ['code']] = $addressee_rel_tbl->getStudentCount ( $mail ['id'], $type ['id'] );
            }
            $res_list [] = $res;
        }
        // 表示情報設定
        $broadcast_mail_list = $res_list;
//        dd($broadcast_mail_list);
        $send_flag = $this->send_flag;
        $bc_option = $this->bc_option;
        $student_types = $this->studentTypes;

        // TOPに飛ばす
        return view ( 'School.Broadcast_mail.index', compact ( 'broadcast_mail_list', 'student_types', 'bc_option', 'send_flag', 'auths', 'request' ) );
    }

    private function getBcOption(Request $request) {
        $request->offsetUnset('bc_parent');
        $request->offsetUnset('bc_student');
        $request->offsetUnset('bc_teacher');
        $request->offsetUnset('bc_staff');
        $search = $request->_c;
        $bc= array();
        if(isset($search['bc_option'])){
            foreach ($search['bc_option'] as $key => $value){
                $bc[$value] = $value;
            }
        }


        if (($request->offsetExists('parent_list') && sizeof ( $request->parent_list) > 0) || isset($bc[1])) {
            $request->offsetSet('bc_parent',1);
        }
        if (($request->offsetExists('student_list') && sizeof ( $request->student_list)>0 || isset($bc[2]))) {
            $request->offsetSet('bc_student',1);
        }
        if (($request->offsetExists('teacher_ids') && sizeof ( $request->teacher_ids) > 0 || isset($bc[3]))) {
            $request->offsetSet('bc_teacher',1);
        }
        if (($request->offsetExists('staff_ids') && sizeof ( $request->staff_ids) > 0 || isset($bc[4]))) {
            $request->offsetSet('bc_staff',1);
        }

        // 送信先
        if ( $request->offsetExists('bc_student') ||  $request->offsetExists('bc_parent') ||  $request->offsetExists('bc_teacher') ||  $request->offsetExists('bc_staff')) {
            if ( $request->offsetExists('bc_parent') && (empty($request->bc_student)) && empty ($request->bc_teacher) && empty ( $request->bc_staff)) {
                $request->offsetSet('bc_option',1);
            } elseif (empty ($request->bc_parent) &&  $request->offsetExists('bc_student') && empty ( $request->bc_teacher) && empty ( $request->bc_staff)) {
                $request->offsetSet('bc_option',2);
            } elseif (empty ($request->bc_parent) &&  empty ($request->bc_student) && $request->offsetExists('bc_teacher')  && empty ( $request->bc_staff)) {
                $request->offsetSet('bc_option',3);
            } elseif (empty ($request->bc_parent) &&  empty ($request->bc_student) && empty ( $request->bc_teacher) && $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',4);
            } elseif ($request->offsetExists('bc_parent') &&  $request->offsetExists('bc_student') && empty ( $request->bc_teacher) && empty ( $request->bc_staff)) {
                $request->offsetSet('bc_option',5);
            } elseif ($request->offsetExists('bc_parent') &&  empty ($request->bc_student) && $request->offsetExists('bc_teacher')  && empty ( $request->bc_staff)) {
                $request->offsetSet('bc_option',6);
            } elseif ($request->offsetExists('bc_parent') &&  empty ($request->bc_student) && empty ( $request->bc_teacher) && $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',7);
            } elseif ($request->offsetExists('bc_parent') &&   $request->offsetExists('bc_student') &&  $request->offsetExists('bc_teacher') && empty ( $request->bc_staff)) {
                $request->offsetSet('bc_option',8);
            } elseif ($request->offsetExists('bc_parent') &&   $request->offsetExists('bc_student') &&  empty ( $request->bc_teacher)&& $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',9);
            } elseif ($request->offsetExists('bc_parent') &&  empty ($request->bc_student) &&  $request->offsetExists('bc_teacher') && $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',10);
            } elseif ($request->offsetExists('bc_parent') &&  $request->offsetExists('bc_student') &&  $request->offsetExists('bc_teacher') && $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',11);
            } elseif (empty ($request->bc_parent) &&  $request->offsetExists('bc_student') && $request->offsetExists('bc_teacher') && empty ( $request->bc_staff)) {
                $request->offsetSet('bc_option',12);
            } elseif (empty ($request->bc_parent) &&  $request->offsetExists('bc_student') && empty ( $request->bc_teacher) && $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',13);
            } elseif (empty ($request->bc_parent) &&  $request->offsetExists('bc_student') && $request->offsetExists('bc_teacher') && $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',14);
            } elseif (empty ($request->bc_parent) &&  empty ($request->bc_student) && $request->offsetExists('bc_teacher') && $request->offsetExists('bc_staff')) {
                $request->offsetSet('bc_option',15);
            }
        }
        return true;
    }

    public function newEntry(Request $request) {
        $mail_template_type = ConstantsModel::$MAIL_TEMPLATE_TYPE[$this->current_lang];

        //get class_list
        $list_condition = array (
                'pschool_id' => session()->get('school.login.id'),
                'active_flag' => 1,
                'delete_date is null'
        );
        $class_list = array ();
        $class_list = ClassTable::getInstance ()->getList ( $list_condition );
        $dispClassList = array ();
        if (! empty ( $class_list )) {
            foreach ( $class_list as $idex => $row ) {
                $dispClassList [$row ['id']] = $row ['class_name'];
            }
        }
        $request->offsetSet('class_list',$dispClassList );
        // 講師一覧
        $studentTypes = $this->studentTypes;
        $main_captions = session()->get( 'main_captions' );
        if ($request->offsetExists('id')) {
            $broadcast_id =  $request->id;
            $teacher_list = CoachTable::getInstance ()->getMailList ( session()->get( 'school.login.id'),$broadcast_id);
            $staff_list = StaffTable::getInstance ()->getMailList ( session()->get( 'school.login.id'),$broadcast_id);
        }else{
            $teacher_list = CoachTable::getInstance ()->getMailList ( session()->get( 'school.login.id'));
            $staff_list = StaffTable::getInstance ()->getMailList ( session()->get( 'school.login.id'));
        }
        if($request->offsetExists('errors')){
            return view( 'School.Broadcast_mail.new_entry', compact ( 'mail_template_type','studentTypes', 'request', 'staff_list', 'main_captions', 'teacher_list'));
        }
        if ($request->offsetExists('id')) {
            $bc = BroadcastMailTable::getInstance ()->getRow ( array (
                    'id' => $request->id,
                    'pschool_id' => session()->get('school.login.id')
            ));
            if (isset ( $bc )) {


                $uploadFilesTable = UploadFilesTable::getInstance();
                // get file(s) info
                $files = array();
                $files  = $uploadFilesTable->getActiveList(array(
                        'category_code' => self::BROADCAST_CATEGORY_CODE,
                        'target_id'     => $bc['id'],
                ));
                $bc['files'] = $files;
                $file_dir =  self::$UPLOAD_DIR ;
                $request->merge ( $bc );
            }
        }

        return view ( 'School.Broadcast_mail.new_entry', compact ( 'mail_template_type','studentTypes', 'request', 'staff_list', 'main_captions', 'teacher_list','files','file_dir' ) );
    }
    
    /**
     * validatorのルール作成
     */
    private function create_validate_rules(Request $request) {

        $rules = array (
                'title' =>'required|max:255',
                'content' => 'required|max:255',
                'footer' => 'max:255'
        );
        return $rules;
    }
    private function create_validate_message(Request $request){
        $message = array();

        $message['title.required']='msg_title_compulsory';
        $message['title.max']='msg_title_invalid';
        $message['content.required']='msg_contents_compulsory';
        $message['content.max']='msg_contents_invalid';
        //$message['footer.required']='msg_footer_compulsory';
        $message['footer.max']='msg_contents_compulsory';
        return $message;
    }

    /**
     * 一斉送信実行
     */
    public function executeCompleteSend(Request $request) {
        $this->getBcOption ( $request );

        $pschool_id =  session()->get('school.login.id');
        $rules = $this->create_validate_rules($request);
        $messages = $this->create_validate_message($request);
        $validator = Validator::make(request()->all(), $rules, $messages);
        $errors = $validator->errors();
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $request->offsetSet('errors',$errors);
            return $this->newEntry ( $request );
        }
        
        $bcMail_tbl = BroadcastMailTable::getInstance ();
        $mailMessageTable = MailMessageTable::getInstance();

        $bcMail_tbl->beginTransaction ();
        try {
            $bcMail = array (
                    'title' => $request->title,
                    'content' => $request['content'],
                    'footer' => $request->footer,
                    'memo' => $request->memo,
                    'pschool_id' =>$pschool_id,

                    'time_send' => $time_send = date("Y-m-d H:i:s") 
            );
            if ($request->offsetExists('bc_option')) {
                $bcMail ['bc_option'] = $request->bc_option;
            }
            if($request->send_date==null){
                $bcMail['send_flag'] = 1;
            }
            if ((! empty ( $request->id))) {
                    $bcMail ['id'] = $request->id;
            }
            $bcMail_id = $bcMail_tbl->save ( $bcMail );
            $bcMail ['id'] = $bcMail_id;

            //save attachment to upload table

            if ($request->hasFile('files')) {
                $uploadFilesTable = UploadFilesTable::getInstance();
                $dir = self::SCHOOL_DIR . session()->get('school.login.id');
                $files = $request->file('files');
                foreach ($files as $key => $file) {
                    // get original info of file
                    $display_file_name = $file->getClientOriginalName();
                    $ext = $file->getClientOriginalExtension();

                    // generate file name
                    $real_file_name = md5( $display_file_name . uniqid() ) . '.' .$ext;

                    // save file to uploads folder
                    $file_path = $file->storeAs( $dir, $real_file_name, 'uploads' );

                    $upload_file = array(
                            'category_code' => self::BROADCAST_CATEGORY_CODE,
                            'target_id'     => $bcMail_id,
                            'file_path'     => $file_path,
                            'real_file_name' => $real_file_name,
                            'disp_file_name' => $display_file_name,
                    );
                    $uploadFilesTable->save($upload_file);
                }
            }

            // TODO override send mail

            if($request->has ( 'parent_list' )) {
                $parent_list = $request->parent_list;
                foreach ($parent_list as $key=>$parent){
                    $mail_message_data = array();
                    $mail_message_data['type']= 6; // 6=> broadcast mail
                    $mail_message_data['relative_ID']= $bcMail_id;
                    $mail_message_data['pschool_id']= $pschool_id;
                    $mail_message_data['target']= 1; // target = 1 => parent
                    $mail_message_data['target_id']= $key;
                    if($request->send_date !== null){
                        $mail_message_data['schedule_date']= $request->send_date ;
                        $mailMessageTable->updateMailMessage($mail_message_data);
                    }else{
                        $mail_message_id = $mailMessageTable->updateMailMessage($mail_message_data);
                        $bcMail_tbl->commit ();
                        $this->instantSendMail($bcMail_id,$mail_message_data['target'],$mail_message_data['target_id'],$mail_message_id);
                    }
                }
            }
            if($request->has ( 'student_list' )) {
                $student_list = $request->student_list;
                $studentTable = StudentTable::getInstance();
                $studentInfoArray = array ();
                $mailAddress = array ();
                foreach ($student_list as $key=>$student){
                    $studentInfo = $studentTable->getOnlyStudentInfoBroadcastMail($key);
                    $studentInfoArray[] = $studentInfo;
                    $mailAddress[] = $studentInfo['mailaddress'];
                    $mail_message_data = array();
                    $mail_message_data['type']= 6; // 6=> broadcast mail
                    $mail_message_data['relative_ID']= $bcMail_id;
                    $mail_message_data['pschool_id']= $pschool_id;
                    $mail_message_data['target']= 2; // target = 2 => student
                    $mail_message_data['target_id']= $key;
                    if($request->send_date !== null){
                        $mail_message_data['schedule_date']= $request->send_date ;
                        $mailMessageTable->updateMailMessage($mail_message_data);
                    }else{
                        $mail_message_id = $mailMessageTable->updateMailMessage($mail_message_data);

                        $bcMail_tbl->commit ();
                        // $this->instantSendMail($bcMail_id,$mail_message_data['target'],$mail_message_data['target_id'],$mail_message_id);
                    }
                }
                $processName = 'お知らせ送信';
                $processID = 1;
                $broadcastMailTable = BroadcastMailTable::getInstance();
                $broadcast_data = $broadcastMailTable->getBroadCastInfo($bcMail_id,$mail_message_data['target'],$mail_message_data['target_id']);
                $data_template_for_mail = $broadcast_data;
                $interface_type = 3; // お知らせ画面
                $is_check= HandelCoopSendMail::coop_send_mail($interface_type, $studentInfoArray, $mailAddress, $processName, $processID, $mail_message_id, $data_template_for_mail);
                if (!$is_check) {
                    $error_send_mail = 'メールアドレスが登録されていない会員が選択されています。';
                    $request->offsetSet('error_send_mail',$error_send_mail);
                    return $this->newEntry ( $request );
                }
            }
            if($request->has ( 'teacher_ids' )) {
                $teacher_list = $request->teacher_ids;
                foreach ($teacher_list as $key=>$teacher_id){
                    $mail_message_data = array();
                    $mail_message_data['type']= 6; // 6=> broadcast mail
                    $mail_message_data['relative_ID']= $bcMail_id;
                    $mail_message_data['pschool_id']= $pschool_id;
                    $mail_message_data['target']= 3; // target = 3 => teacher
                    $mail_message_data['target_id']= $teacher_id;
                    if($request->send_date !== null){
                        $mail_message_data['schedule_date']= $request->send_date ;
                        $mailMessageTable->updateMailMessage($mail_message_data);
                    }else{
                        $mail_message_id = $mailMessageTable->updateMailMessage($mail_message_data);
                        $bcMail_tbl->commit ();
                        $this->instantSendMail($bcMail_id,$mail_message_data['target'],$mail_message_data['target_id'],$mail_message_id);
                    }
                }
            }
            if($request->has ( 'staff_ids' )) {
                $staff_list = $request->staff_ids;
                foreach ($staff_list as $key=>$staff_id){
                    $mail_message_data = array();
                    $mail_message_data['type']= 6; // 6=> broadcast mail
                    $mail_message_data['relative_ID']= $bcMail_id;
                    $mail_message_data['pschool_id']= $pschool_id;
                    $mail_message_data['target']= 4; // target = 4 => staff
                    $mail_message_data['target_id']= $staff_id;
                    if($request->send_date !== null){
                        $mail_message_data['schedule_date']= $request->send_date ;
                        $mailMessageTable->updateMailMessage($mail_message_data);
                    }else{
                        $mail_message_id = $mailMessageTable->updateMailMessage($mail_message_data);
                        $bcMail_tbl->commit ();
                        $this->instantSendMail($bcMail_id,$mail_message_data['target'],$mail_message_data['target_id'],$mail_message_id);
                    }
                }
            }

            $bcMail_tbl->commit ();
            $request->offsetSet('id',$bcMail_id);
        } catch ( Exception $ex ) {
            $bcMail_tbl->rollBack ();
        }

        return redirect('/school/broadcastmail');
    }
    public function instantSendMail($broadcast_id,$target,$target_id,$mail_message_id){
        $broadcastMailTable = BroadcastMailTable::getInstance();
        $mailMessageTable = MailMessageTable::getInstance();
        $mail_info = $mailMessageTable->load($mail_message_id);
        $broadcast_data = $broadcastMailTable->getBroadCastInfo($broadcast_id,$target,$target_id);

//        $is_sent = $this->send_email(self::$MAIL_TEMPLATE, $broadcast_data, array('0'=>$broadcast_data['login_id']),$broadcast_data['title'], $broadcast_data['school_mailaddress']);
        $is_sent = $this->send_email(self::$MAIL_TEMPLATE, $broadcast_data, array('0'=>$broadcast_data['login_id']),$broadcast_data['title'], session('school.login')['mailaddress']);
        if($is_sent){
            // update mail_message row
            $mail_message = array(  'id'            => $mail_info['id'],
                                    'send_date'     => date("Y-m-d H:i:s"),
                                    'total_send'    => $mail_info['total_send'] + 1);
            $saved_id = $mailMessageTable->save($mail_message);
            $status = 1; // 送信済み
        } else {
            $status = 0; // 未送信
        }

        // Write log
        $current_time = date('Y-m-d H:i:s');
        $path = "../storage/logs/".session('school.login.id')."/mail/Oshirasei/";
        $fileName = $path . "mail_debug.log";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($fileName, $current_time . ": " . '        ' . $broadcast_data['broadcast_mail_id'] . ',' . $broadcast_data['login_id'] . ',' . $status . "\n", FILE_APPEND);
    }

    public function executeSave(Request $request) {
        $this->getBcOption($request);
        $pschool_id =  session()->get('school.login.id');
        $rules = $this->create_validate_rules($request);
        $messages = $this->create_validate_message($request);
        $validator = Validator::make(request()->all(), $rules, $messages);
        $errors = $validator->errors();
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $request->offsetSet('errors',$errors);
            return $this->newEntry ( $request );
        }
        $bcMail_tbl = BroadcastMailTable::getInstance ();
        $mailMessageTable = MailMessageTable::getInstance();

        $bcMail_tbl->beginTransaction ();
        try {
            $bcMail = array (
                    'title' => $request->title,
                    'content' => $request['content'],
                    'footer' => $request->footer,
                    'memo' => $request->memo,
                    'pschool_id' => $pschool_id,
                    'time_send' => $time_send = date ("Y-m-d H:i:s")
            );
            if ($request->offsetExists ('bc_option')) {
                $bcMail ['bc_option'] = $request->bc_option;
            }
            if ((! empty ($request->id))) {
                    $bcMail ['id'] = $request->id;
            }else{
                $bcMail['send_flag'] = 0;
            }

            $bcMail_id = $bcMail_tbl->save ($bcMail);
            $bcMail ['id'] = $bcMail_id;
            //save attachment to upload table

            if ($request->hasFile('files')) {
                $uploadFilesTable = UploadFilesTable::getInstance();
                $dir = self::SCHOOL_DIR . session()->get('school.login.id');
                $files = $request->file('files');
                foreach ($files as $key => $file) {
                    // get original info of file
                    $display_file_name = $file->getClientOriginalName();
                    $ext = $file->getClientOriginalExtension();

                    // generate file name
                    $real_file_name = md5( $display_file_name . uniqid() ) . '.' .$ext;

                    // save file to uploads folder
                    $file_path = $file->storeAs( $dir, $real_file_name, 'uploads' );

                    $upload_file = array(
                            'category_code' => self::BROADCAST_CATEGORY_CODE,
                            'target_id'     => $bcMail_id,
                            'file_path'     => $file_path,
                            'real_file_name' => $real_file_name,
                            'disp_file_name' => $display_file_name,
                    );
                    $uploadFilesTable->save($upload_file);
                }
            }
            $bcMail_tbl->commit();
        }catch(Exception $ex ) {
            $bcMail_tbl->rollBack ();
        }
        $request->message_type = 52;
        return redirect("/school/broadcastmail");
    }
    /**
     * 下書きメールの削除
     */
//    public function executeDelete() {
//        $message_type = 53;
//        if (! $this->requestKeyHasValue ( 'id' )) {
//            $message_type = 99;
//            // TOPの一覧画面にとばす
//            HeaderUtil::redirect ( $this->get_app_path () . 'broadcastmail/search?message_type=' . $message_type );
//        }
//
//        $bcMail_tbl = BroadcastMailTable::getInstance ();
//        $bcMail_addressee_rel_tbl = BroadcastMailAddresseeRelTable::getInstance ();
//        $bcMail_tbl->beginTransaction ();
//        try {
//            $bcMail_tbl->logicRemove ( $request ['id'] );
//            $bcMail_addressee_rel_tbl->logicalRemoveByCondition ( array (
//                    'broadcast_mail_id' => $request ['id']
//            ) );
//            $bcMail_tbl->commit ();
//        } catch ( Exception $ex ) {
//            $message_type = 99;
//            $bcMail_tbl->rollBack ();
//        }
//
//        // TOPの一覧画面にとばす
//        HeaderUtil::redirect ( $this->get_app_path () . 'broadcastmail/search?message_type=' . $message_type );
//    }
//    private function processOneMailMsg($template, $assign_var, $to_arr, $subject) {
//        if (view()->exists($template)) {
//            Mail::send($template, ['data' => $assign_var], function($message) use ($to_arr, $subject)
//            {
//                foreach ($to_arr as $email) {
//                    $message->to($email)->subject($subject);
//                }
//            });
//            return true;
//        }
//        return false;
//    }

    public function  executeSearchBroadcastmail(Request $request){
        $request->parent_list = $request['parent_list_arr'];
        $request->student_list = $request['student_list_arr'];
    
        $student_tbl = StudentTable::getInstance ();
        $m_student_type_tbl = MStudentTypeTable::getInstance ();
        $parent_tbl = ParentTable::getInstance ();
        $pschool_id = session()->get( 'school.login.id');
        $arry_search = array ();
        $arry_search ['pschool_id'] = $pschool_id;
        if ($request->offsetExists('input_search')) // 生徒名（漢字・カナ）
        {
            $arry_search ['input_search'] = $request->input_search;
        }
        if ($request->offsetExists('input_search_student_no')) // 生徒名（漢字・カナ）
        {
            $arry_search ['input_search_student_no'] = $request->input_search_student_no;
        }
        if ($request->offsetExists('class_id')) // プラン名 2015/04/03
        {
            $arry_search ['class_id'] = $request->class_id;
        }
        if ($request->offsetExists('active_flag')) // プラン名 2015/04/03
        {
            $arry_search ['active_flag'] = $request->active_flag;
        }
        //登録変更日
        if ($request->offsetExists('valid_date_from')) {
            $arry_search['valid_date_from'] = $request->valid_date_from;
        }
        if ($request->offsetExists('valid_date_to')) {
            $arry_search['valid_date_to'] = $request->valid_date_to;
        }
        $arry_search ['student_type'] = $request->student_types;

        $student_list = StudentTable::getInstance ()->getQueryList ($arry_search, $request->sort_cond);

        $parent_list = array ();
        foreach ( $student_list as $student ) {
            if($request->offsetExists('id')){
                $student ['student_time_send'] = $student_tbl->getTimeSendStudent ( $student ['id'],$request->id );

            }
            if (! array_key_exists ( $student ['parent_id'], $parent_list )) {
                $parent_list [$student ['parent_id']] = $parent_tbl->getRow ( $where = array (
                        'pschool_id' => session()->get( 'school.login.id'),
                        'id' => $student ['parent_id']
                ) );
                $parent_list [$student ['parent_id']] ['parent_time_send'] = $parent_tbl->getTimeSendParent ( $parent_list [$student ['parent_id']] ['id'],$request->id );
            }
            $parent_list [$student ['parent_id']] ['students'] [$student ['id']] = $student;
            $m_student_type = $m_student_type_tbl->getActiveRow ( $where = array (
                    'pschool_id' => session()->get( 'school.login.id'),
                    'type' => $parent_list [$student ['parent_id']] ['students'] [$student ['id']] ['student_type']
            ) );
            $parent_list [$student ['parent_id']] ['students'] [$student ['id']] ['student_type'] = $m_student_type ['name'];
        }

        $request->check_search = '1';
        return view('School.Broadcast_mail.parent_list', compact('parent_list', 'request'));
    }

    public function ajaxDeleteUploadFile(Request $request) {
        if ($request->has('file_id')) {
            $file_info_deleted = $this->deleteUploadFile($request->file_id);
            if ($file_info_deleted) {
                return 'success';
            }
        }
        return 'fail';
    }
    private function deleteUploadFile($file_id) {
        $uploadFilesTable = UploadFilesTable::getInstance();
        $file = $uploadFilesTable->load($file_id);
        if ($file) {
            $file_deleted = '';
            $file_row_deleted = '';
            try {
                // delete file in folder
                if ( Storage::disk('uploads')->exists($file['file_path'])) {
                    $file_deleted = Storage::disk('uploads')->delete($file['file_path']);
                } else {
                    echo " File does not exist ! ";
                }
                // delete row in upload_files table
                $file_row_deleted = $uploadFilesTable->deleteRow(array('id'=>$file_id));
            } catch (Exception $e) {
                $this->_logger->error($e->getMessage());
                return false;
            }
            if ( $file_deleted && $file_row_deleted ) {
                return true;
            }
        }
        return false;
    }
}