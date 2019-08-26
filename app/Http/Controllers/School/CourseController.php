<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Model\PaymentMethodPschoolTable;
use App\Model\PschoolBankAccountTable;
use App\Model\StudentCourseRelTable;
use App\Model\RoutinePaymentTable;
use App\Model\CourseFeePlanTable;
use App\Model\MStudentTypeTable;
use App\Model\CourseCoachTable;
use App\Model\LessonCoachTable;
use App\Model\PschoolTable;
use App\Model\CourseTable;
use App\Model\ClassTable;
use App\Model\EntryTable;
use App\Model\CoachTable;
use App\Model\LessonTable;
use App\Model\StaffTable;
use App\ConstantsModel;
use App\Lang;
use Validator;
use Excel;
use DB;
use App\Common\CSVExport;

class CourseController extends _BaseSchoolController
{
    private static $TOP_URL = 'course/list';
    protected static $ACTION_URL = 'course';
    private static $TEMPLATE_URL = 'course';
    private static $bread_name = 'イベント管理';
    protected static $LANG_URL = 'course';
    private $_course_search_item = ['_c'];
    private $_course_search_session_key = 'course_search_form';

    public function __construct()
    {
        parent::__construct();
        // 多国語対応
        $message_content = parent::getMessageLocale();
        $this->lan = new Lang($message_content);

        if (!PschoolTable::getInstance()->isNormalShibu(session('school.login')['id'])){
            Redirect::to($this->get_app_path ());
            // HeaderUtil::redirect ( $this->get_app_path ());
        }

        if( session('school.login')['business_divisions'] == 1 || session('school.login')['business_divisions'] == 3){      // 運用区分が塾の場合
            self::$TEMPLATE_URL = 'course';
        }
        if( session('school.login')['business_divisions'] == 2 || session('school.login')['business_divisions'] == 4){      // 運用区分が会員クラブの場合
            self::$TEMPLATE_URL = 's_course';
        }
        self::$bread_name = ConstantsModel::$bread_list[$this->current_lang]['course'];
        $student_types = array();
        $m_type = MStudentTypeTable::getInstance()->getStudentTypeName_Axis(session('school.login')['id']);
        if (!empty($m_type)) {
            foreach ($m_type as $value) {
                $key = $value['id'];
                $student_types[$key] = array(
                    'name' => $value['name'],
                    'remark' => $value['remark'],
                    'payment_unit' => $value['payment_unit']
                    );
            }
        }
        view()->share('studentTypes', $student_types);
        // 生徒区分
        view()->share('schoolCategory', ConstantsModel::$dispSchoolCategory);

        //口座種別
        view()->share('bank_account_type_list', ConstantsModel::$type_of_bank_account[session('school.login')['language']]);
    }

    public function index(Request $request) {
        
        //パンくず
        // $this->set_menu_no(9);
        // $this->clear_bread_list();
        $this->clearOldInputSession(); 
        return $this->executeList($request);
    }

    public function executeList(Request $request) { 
        // $this->set_bread_list ( self::$ACTION_URL , ConstantsModel::$bread_list [$this->current_lang] ['course_manage'] );
        $this->_initSearchDataFromSession($this->_course_search_item, $this->_course_search_session_key);

        $disp_list = CourseTable::getInstance()->getCourseList4Top(session('school.login')['id'], $request->_c);
        if ($request->exists('add_caption')){
            $class_list = ClassTable::getInstance()->getClassList4Top(session('school.login')['id'], $request->_c);
            $disp_list = array_merge($disp_list, $class_list);
        }
        $disp_list = $this->getSortListByClassCourse($disp_list);
        return view('School.Course.index')->with('lan', $this->lan)->with('list', $disp_list);
    }

    /**
     * 詳細画面
     */
    public function courseDetail(Request $request) { 
        $entry_link_str = self::$ACTION_URL.'/courseDetail';
        $list = array();
        if ($request->has('course_id'))
        {
            $entry_link_str .= '?course_id='.request('course_id');
            $course_list = CourseTable::getInstance()->getCourseList4Top(session('school.login')['id'], array('course_id'=>$request->course_id));
            $list = $course_list[0];
        }

        $entry = array();
        $student_list = array();
        if ($request->event_id)
        {
            $entry_list = EntryTable::getInstance()->getStudentListbyEventTypeAxis(session('school.login')['id'], array('entry_type'=>2, 'relative_id'=>$request->event_id, 'enter'=>1));
            foreach ($entry_list as $entry_row) {
                $entry_row['fee'] = number_format(floor($entry_row['fee']));
                $entry[] = $entry_row;
            }
            $entry_link_str .= '&event_id='.request('event_id');
        }
        // $this->set_bread_list($entry_link_str, self::$bread_name.ConstantsModel::$bread_list[$this->current_lang]['detail']);
        // 受講料
        $course_fee_list = array();
        $course_fee = CourseFeePlanTable::getInstance()->getActiveList(array('course_id'=>$request->course_id, 'active_flag'=>1),array('sort_no'));
        foreach ($course_fee as $value) {
            $course_fee_list[] = array( 'name'=>$value['fee_plan_name'],'fee'=>$value['fee']);
        }

        // 2015-10-05 複数講師
        $teacher_list = array();
        $teacher_list = CourseCoachTable::getInstance()->getCoachList($request->course_id, session('school.login')['id']);

        return view('School.Course.detail')->with('lan', $this->lan)->with('list', $list)->with('teacher_list', $teacher_list)->with('student_list', $entry)->with('course_fee', $course_fee_list);
    }

    public function courseEntry(Request $request) {
        // clear error message
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }

        $this->recoverWithInput($request, array('course_id'));

        $course = array();
        $student_list = array(); // count student to disable DELETE button
        if ($request->has('course_id'))
        {
            $course = CourseTable::getInstance()->getRow($where=array('pschool_id'=>session('school.login')['id'], 'id'=>$request->course_id));
            if ($course['payment_method']) {
                $request->offsetSet('payment_methods', explode(',',$course['payment_method'] ));
            }
            $entry_list = EntryTable::getInstance()->getStudentListbyEventTypeAxis(session('school.login')['id'], array('entry_type'=>2, 'relative_id'=>$request->course_id, 'enter'=>1));
            foreach ($entry_list as $entry_row) {
                $entry_row['fee'] = number_format(floor($entry_row['fee']));
                $student_list[] = $entry_row;
            }
        } else {
        }

        // TODO assign all course info into request
        $request->merge($course);

        // 受講料金
        if ($request->has('course_id')) {
            // 編集の初期表示
            if (!$request->exists('_course_fee') || !$request->exists('_course_fee1')) {
                $course_fee_list['_course_fee'] = array();
                $course_fee_list['_course_fee1'] = array();

                $course_fee = CourseFeePlanTable::getInstance()->getActiveList(array('course_id'=>$request->course_id), array('sort_no'));
                foreach ($course_fee as $value) {
                    // とりあえず切り捨て
                    $value['fee'] = floor($value['fee']);
                    
                    if ($value['student_type_id']) {
                        $course_fee_list['_course_fee1'][] = $value;
                    } else {
                        $course_fee_list['_course_fee'][] = $value;
                    }
                    
                }
                $request->merge($course_fee_list);
            }

            
        } else {
            // 登録の初期表示
            if (!$request->exists('_course_fee')) {
                $course_fee_list['_course_fee'][] = array();
                $course_fee_list['_course_fee1'][] = array();
                $request->merge($course_fee_list);

            }

        }

        // 回数単位
        $attendTimesDivList = ConstantsModel::$attend_times_div[session('school.login')['language']];

        // 複数講師
        if ($request->exists('course_id')) {
            if (!$request->exists('teacher_ids')) {
                $request->offsetSet('teacher_ids', CourseCoachTable::getInstance()->getCoachIDs($request->toArray(), session('school.login')['id']));
            }
        }
        $teacher_data = CoachTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']));   
        $staff_list = StaffTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']));

        $payment_method = ConstantsModel::$invoice_type[$this->current_lang];
        $mail_template_type = ConstantsModel::$MAIL_TEMPLATE_TYPE[$this->current_lang];

        // TODO get invoice_type of pschool_bank_account table to set default select 口座振替 (update 2017-06-09)
        // Case Add New Event
        if (!$request->has('course_id')) {
            $bank_account = PschoolBankAccountTable::getInstance()->getActiveRow(['pschool_id'=>session('school.login')['id']]);
            if (!empty($bank_account) && $bank_account['invoice_type'] == 2 ) { // '0=現金 1=振込 2=口座振替'
                $request->offsetSet('is_default_transfer', true);
            }
            // TODO default min 定員(会員） = 20人
            $request->offsetSet('member_capacity', 20);
            $request->offsetSet('non_member_capacity', 0);
        }
//    dd($request);
        // 支払方法
        $payment_list = PaymentMethodPschoolTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']), array('sort_no'));

        return view('School.Course.input', compact('lan', 'attendTimesDivList', 'teacher_data', 'payment_method', 'student_list', 'staff_list', 'payment_list', 'mail_template_type','request'));
    }

    public function courseComplete(Request $request) {
        // TODO change Fee(in _course_fee1) format
        if ($request->has('_course_fee1')) {
            $request_clone = $request->all();
            foreach ($request_clone['_course_fee1'] as &$course_fee) {
                $course_fee['fee'] = str_replace(',', '', $course_fee['fee']);
            }
            $request->replace($request_clone);
        }


        $message_type = 99;
        if ($request->function != 3) {
            //ヴぁりデート
            // エラー表示用の配列
            $rules = $this->get_validate_rules($request);
            $messsages = $this->get_validate_message($request);
            $validator = Validator::make(request()->all(), $rules, $messsages);
                
            if ($validator->fails()) { 
                // session()->push('old_data', $request->input());
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
        }

        $err = array();
        if ($request->function == 3) {
            $err = $this->deleteCourse($request->id);
        }else{
            $course = array(
                    'pschool_id'        => session('school.login')['id'],
                    'course_title'      => $request->course_title,
                    'start_date'        => $request->start_date,
                    'close_date'        => ($request->close_date != '')? $request->close_date : null,
                    'course_description'=> $request->course_description,
                    'active_flag'       => $request->active_flag,
                    
                    // 仕様変更2017/05/09
                    'mail_subject'      => $request->mail_subject,
                    'mail_footer'       => $request->mail_footer,
                    'recruitment_start' => date('Y-m-d 00:00:00', strtotime($request->recruitment_start)),
                    'recruitment_finish'=> ($request->recruitment_finish != '')? date('Y-m-d 23:59:59', strtotime($request->recruitment_finish)) : null,
                    //payment_method: 1:現金・振込, 2:口座振替, 3:信用カード
                    'payment_method'    => (count($request->payment_methods) > 0)? implode(',', $request->payment_methods): null,
                    'payment_due_date'  => ($request->payment_due_date != '')? date('Y-m-d 23:59:59', strtotime($request->payment_due_date)) : null,
                    'fee_type'          => $request->fee_type,
                    'member_capacity'   => $request->member_capacity ,
                    'non_member_flag'   => $request->has('non_member_flag')? 1 : 0,
                    'application_deadline' => isset($request->application_deadline)? $request->application_deadline: 0,
                    // 仕様変更2017/06/06
                    'send_mail_flag'    => isset($request->send_mail_flag)? 1 : 0,
                    'course_location'   => $request->course_location,
                    'course_code'       => $request->course_code,
                    'contact_number'    => $request->contact_number,
                    'contact_email'     => $request->contact_email,
                    'remark'            => $request->remark,
                    'remark_1'          => $request->remark_1,
                    'person_in_charge1' => $request->person_in_charge1,
                    'person_in_charge2' => $request->person_in_charge2,
                    'is_merge_invoice'  => $request->is_merge_invoice,
            );

            if ($request->has('non_member_capacity')) {
                $course['non_member_capacity'] = $request->non_member_capacity;
            }
            //In case of editting, id is present
            if ($request->has('id')){
                $course['id'] = $request->id;
                // 2017-05-17 Update by Kieu
                // TODO check application_deadline & member_capacity to determine close day
                $this->checkToCloseEvent($request, $course);
            }

            $err = $this->saveCourse($request, $course);

            //by default, it is entry.
            if (!$request->function){
                    $request->function = 1;
            }
        }

        if ($err){
            session()->push('errors', $data);
            return Redirect::to("/school/course/courseentry");
        }else{
            $message_type = $request->function;
        }
        
        $request->offsetSet('message_type',$message_type);
        if ($request->function !=3 ) {
            $request->offsetSet('course_id',$request->course_idc);
            $request->offsetSet('event_id',$request->course_idc);
        }
        return $this->index($request);
    }

    private function checkToCloseEvent($request, &$course) {

        if ($course['application_deadline'] == 1) {
            // TODO check joined member
            // 生徒数
            $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( session('school.login')['id'], array (
                    'entry_type'    => 2,
                    'relative_id'   => $request->id,
                    'enter'         => 1 
            ));
            $total_member = 0;
            foreach ($entries as $value) {
                if ($value['total_member']) { 
                    $total_member += $value['total_member'];
                }
            }
            $non_member_capacity = ($request->has('non_member_capacity') && is_numeric($request->non_member_capacity))? $request->non_member_capacity : 0;
            $member_capacity = $request->has('non_member_flag')? ($request->member_capacity + $non_member_capacity) : $request->member_capacity;
            if ($total_member >= $member_capacity) {
                $course['recruitment_finish'] = date('Y-m-d H:i:s');
            }

        }
    }

    //Pass the course array to save
    private function saveCourse($request, $course){
        $err = array();

        $course_tbl = CourseTable::getInstance();
//      $table_c = CourseFeeTable::getInstance();
//      $table_d = CourseTeacherRelTable::getInstance();
        $table_d = CourseCoachTable::getInstance();

        $table_e = CourseFeePlanTable::getInstance();

        $lesson_tbl = LessonTable::getInstance();
        $lessonCoach_tbl = LessonCoachTable::getInstance();

        try{
            $course_id = $course_tbl->save($course);
            $request->offsetSet('course_idc',$course_id);

            // プラン授業料
            if ($request->has('id')){
                // 更新
                // 会員種別による料金設定
                if ($request->fee_type == 1 && count($request->_course_fee1)>0) {
                    foreach ($request->_course_fee1 as $value) {
                        $save = array();
                        $save['student_type_id']= $value['student_type_id'];
                        $save['fee_plan_name']  = $value['fee_plan_name'];
                        $save['fee']            = $value['fee'];
                        $save['sort_no']        = $value['sort_no'];
                        $save['payment_unit']   = $value['payment_unit'];
                        $save['active_flag']    = (isset($value['active_flag']) && $value['active_flag'] == '0')? 0 : 1;
                        
                        if (empty($value['id'])) {
                            // 追加
                            if ($value['student_type_id']) {
                                $save['course_id']      = $course_id;
                                $save['pschool_id']     = session('school.login')['id'];
                                $save['group_id']       = session('school.login')['group_id'];
                                $table_e->save($save);
                            }
                        } else {
                            // 編集
                            $save['id'] = $value['id'];
                            $table_e->save($save);
                        }
                    }
                } elseif (count($request->_course_fee)>0) { // 回数による料金設定
                    foreach ($request->_course_fee as $value) {
                        $save = array();
                        
                        $save['fee_plan_name']      = $value['fee_plan_name'];
                        $save['attend_times_div']   = $value['attend_times_div'];
                        $save['attend_times']       = $value['attend_times'];
                        $save['fee']            = $value['fee'];
                        $save['sort_no']        = $value['sort_no'];
                        $save['active_flag']    = (isset($value['active_flag']) && $value['active_flag'] == '0')? 0 : 1;
                        
                        if (empty($value['id'])) {
                            // 追加
                            if (strlen($value['fee_plan_name']) > 0) {
                                $save['course_id']      = $course_id;
                                $save['pschool_id']     = session('school.login')['id'];
                                $save['group_id']       = session('school.login')['group_id'];
                                $table_e->save($save);
                            }
                        } else {
                            // 編集
                            $save['id'] = $value['id'];
                            $table_e->save($save);
                        }
                    }
                }
                
            } else {
                // 登録
                // 会員種別による料金設定
                if ($request->fee_type == 1 && count($request->_course_fee1)>0) {
                    foreach ($request->_course_fee1 as $value) {
                        $save = array();
                        $save['course_id']      = $course_id;
                        $save['pschool_id']     = session('school.login')['id'];
                        $save['group_id']       = session('school.login')['group_id'];
                        $save['fee_plan_name']  = $value['fee_plan_name'];
                        $save['student_type_id']= $value['student_type_id'];
                        $save['fee']            = $value['fee'];
                        $save['sort_no']        = $value['sort_no'];
                        $save['payment_unit']   = $value['payment_unit'];
                        $save['active_flag']    = (isset($value['active_flag']) && $value['active_flag'] == '0')? 0 : 1;

                        $table_e->save($save);
                    } 
                } elseif (count($request->_course_fee)>0) {
                    foreach ($request->_course_fee as $value) {
                        $save = array();
                        $save['course_id']      = $course_id;
                        $save['pschool_id']     = session('school.login')['id'];
                        $save['group_id']       = session('school.login')['group_id'];
                        $save['fee_plan_name']      = $value['fee_plan_name'];
                        $save['attend_times_div']   = $value['attend_times_div'];
                        $save['attend_times']       = $value['attend_times'];
                        $save['fee']            = $value['fee'];
                        $save['sort_no']        = $value['sort_no'];
                        $save['active_flag']    = (isset($value['active_flag']) && $value['active_flag'] == '0')? 0 : 1;

                        $table_e->save($save);
                    } 
                } 
                
            }

            // 2015-10-02 複数講師
            $table_d->logicalRemoveByCondition(array('course_id'=>$course_id));
            if ($request->has('teacher_ids')) {
                foreach ($request->teacher_ids as $teacher_id) {
                    if ($teacher_id != '') {
                    $map = array(
                        'group_id'      => session('school.login')['group_id'],
                        'pschool_id'    =>session('school.login')['id'],
                        'course_id'     =>$course_id,
                        'coach_id'      =>$teacher_id,
                        'active_flag'   =>1
                        );
                    $table_d->save($map);
                    }
                
                }
            }
            
            //-------------------------------------------------------------
            // 受講料以外にかかる費用
            //-------------------------------------------------------------
            $PaymentTable = RoutinePaymentTable::getInstance();
            // ①まず登録されているもの取得
            $registed_list = array();
            if( isset($course_id) && !empty($course_id) ){
                $registed_list = RoutinePaymentTable::getInstance()->getList($where=array('pschool_id'=>session('school.login')['id'],'data_div'=>1, 'data_id'=>$course_id, 'delete_date IS NULL'));
            }

            if(!$request->has('payment') || count($request->payment) < 1 ){
                // ②既存の全部削除
                foreach ($registed_list as $regist_item){
                    $regist_item['active_flag']     = 0;
                    $regist_item['delete_date']     = date('Y-m-d H:i:s');
                    $regist_item['update_date']     = date('Y-m-d H:i:s');
                    $regist_item['update_admin']    = session('login_account_id');
                    $PaymentTable->updateRow($regist_item, $where=array('id'=>$regist_item['id']));
                }
            } else {
                if( count($registed_list) < 1  ){
                    // ③全部登録
                    foreach ($request->payment as $payment_item){
                        $Row = array();
                        $Row['pschool_id']      = session('school.login')['id'];
                        $Row['data_div']        = 2;
                        $Row['data_id']         = $course_id;
                        $Row['month']           = $payment_item['payment_month'];
                        $Row['invoice_adjust_name_id']    = $payment_item['payment_adjust'];
                        $Row['adjust_fee']      = $payment_item['payment_fee'];
                        $Row['student_type']    = null;
                        $Row['register_date']   = date('Y-m-d H:i:s');
                        $Row['register_admin']  = session('login_account_id');

                        $PaymentTable->insertRow($Row);
                    }
                } else {
                    // ④一部登録と一部更新
                    foreach ($request->payment as $payment_item ){
                        $bExist = false;
                        foreach ($registed_list as $regist_item ){
                            if( isset($payment_item['payment_id']) && !empty($payment_item['payment_id'])){
                                if( $payment_item['payment_id'] == $regist_item['id']){
                                    $bExist = true;
                                    break;
                                }
                            }
                        }
                        if( $bExist ){
                            // 存在するので更新
                            $regist_item['month']           = $payment_item['payment_month'];
                            $regist_item['invoice_adjust_name_id']    = $payment_item['payment_adjust'];
                            $regist_item['adjust_fee']      = $payment_item['payment_fee'];
                            $regist_item['update_date']     = date('Y-m-d H:i:s');
                            $regist_item['update_admin']    = null;

                            $PaymentTable->updateRow($regist_item, $where=array('id'=>$regist_item['id']));
                        }else {
                            // 存在しないので追加
                            $Row = array();
                            $Row['pschool_id']      = session('school.login')['id'];
                            $Row['data_div']        = 2;
                            $Row['data_id']         = $course_id;
                            $Row['month']           = $payment_item['payment_month'];
                            $Row['invoice_adjust_name_id']    = $payment_item['payment_adjust'];
                            $Row['adjust_fee']      = $payment_item['payment_fee'];
                            $Row['student_type']    = null;
                            $Row['register_date']   = date('Y-m-d H:i:s');
                            $Row['register_admin']  = session('login_account_id');

                            $PaymentTable->insertRow($Row);
                        }
                    }

                    // ⑤一部削除
                    foreach ($registed_list as $regist_item ){
                        $bExist = false;
                        foreach ($request->payment as $payment_item ){
                            if( isset($payment_item['payment_id']) && !empty($payment_item['payment_id'])){
                                if( $regist_item['id'] == $payment_item['payment_id'] ){
                                    $bExist = true;
                                    break;
                                }
                            }
                        }
                        if( !$bExist ){
                            // 存在しないので削除
                            $regist_item['active_flag']     = 0;
                            $regist_item['delete_date']     = date('Y-m-d H:i:s');
                            $regist_item['update_date']     = date('Y-m-d H:i:s');
                            $regist_item['update_admin']    = session('login_account_id');

                            $PaymentTable->updateRow($regist_item, $where=array('id'=>$regist_item['id']));
                        }
                    }
                }
            }

        }catch (Exception $ex){
            $this->_logger->error($ex->getMessage());
            $err [] = ConstantsModel::$errors[$this->current_lang]['regist_process_error'];
        }
        return $err;
    }
    //Pass only number
    private function deleteCourse($id)
    {   
        $err = array();
        $course_tbl = CourseTable::getInstance();

        $table_e = CourseFeePlanTable::getInstance();
        $table_f = StudentCourseRelTable::getInstance();
        $table_g = CourseCoachTable::getInstance();

        try{
            $course_tbl->logicRemove($id);
//          $table_c->logicalRemoveByCondition(array('course_id'=>$id));
            $table_e->logicalRemoveByCondition(array('course_id'=>$id));
            $table_f->logicalRemoveByCondition(array('course_id'=>$id));
            $table_g->logicalRemoveByCondition(array('course_id'=>$id));
        }catch (Exception $ex){
            $this->_logger->error($ex->getMessage());
            $err [] = ConstantsModel::$errors[$this->current_lang]['delete_process_error'];
        }
        return $err;
    }

    private function get_validate_rules($request) { 

        $rules = [  'course_code'       => 'required|max:5|unique:course,course_code,NULL,id,pschool_id,'.session('school.login')['id'],
                    'course_title'      => 'required|max:255', 
                    'mail_subject'      => 'required_if:send_mail_flag,on|max:255',
                    'course_description' => 'required_if:send_mail_flag,on', 
                    'start_date'        => 'required', 
                    'recruitment_start' => 'required|before_or_equal:start_date',
                    'recruitment_finish' => 'nullable|after_or_equal:recruitment_start',
                    'course_location'   => 'required',
                    'contact_email'         => 'nullable|email|max:64',
                    // 'payment_method'    => 'required',
//                    'payment_methods'   => 'required',
                    'member_capacity'   => 'nullable|numeric|max:9999999999', 
                    ];
        if ($request->has('close_date')) {
           $rules['close_date']         = 'after_or_equal:start_date';
           $rules['recruitment_finish'] = 'nullable|after_or_equal:recruitment_start|before_or_equal:close_date';

        }

        if ($request->has('id')) { 
            $rules['function']          = 'required';
            $rules['course_code']       = 'required|max:5|unique:course,course_code,' . $request->id. ',id,pschool_id,'.session('school.login')['id'];
        }

        if ($request->has('application_deadline')) {
            $rules['member_capacity']   = 'required|numeric|max:9999999999';
            
        }
        // 会員種別による料金設定
        if ($request->fee_type == 1 && $request->has('_course_fee1') && count($request->_course_fee1)>0) {
            $rules['_course_fee1.*.fee'] = 'required|numeric|max:9999999999';
            $rules['_course_fee1.*.student_type_id'] = 'required';

            // validate unique [student_type_id + fee_plan_name + payment_unit]
            $course_fee1 = $request->_course_fee1;
            $err_list = array();
            foreach ($course_fee1 as $key => $value) {
                array_shift($course_fee1);
                if (count($course_fee1) > 0 && !in_array($value['sort_no'], $err_list)) {
                    foreach ($course_fee1 as $key1 => $value1) {
                        if ($value['student_type_id'] == $value1['student_type_id'] && $value['fee_plan_name'] == $value1['fee_plan_name'] && $value['payment_unit'] == $value1['payment_unit']) {
                            $rules['_course_fee1.'.((int)$value1['sort_no']-1).'.student_type_id.mean'] = 'required';
                            $err_list[] = $value1['sort_no'];
                        }
                    }
                }
            }

//            $idx1;
//            foreach ($request->_course_fee1 as $key => $value) {
//                if ($key !=0) {
//                    if ($value['student_type_id'] == $idx1) {
//                        $rules['_course_fee1.'.$key.'.student_type_id.mean'] = 'required';
//
//                    }
//                }
//                $idx1 = $value['student_type_id'];
//            }
        // 回数による料金設定
        } elseif ($request->has('_course_fee') && count($request->_course_fee)>0) {
            $rules['_course_fee.*.fee_plan_name'] = 'required|max:255';
            $rules['_course_fee.*.attend_times_div'] = 'required';
            $rules['_course_fee.*.fee'] = 'required|numeric|max:9999999999';
            foreach ($request->_course_fee as $key => $value) {
                if ($value['attend_times_div'] == 1 || $value['attend_times_div'] == 2) {
                    $rules['_course_fee.' .$key . '.attend_times'] = 'required|numeric|max:9999999999';
                }
            } 
        }

        // if ($request->has('_lesson')) {
        //     $rules['_lesson.*.start_date'] = 'required';
        //     $rules['_lesson.*.lesson_name'] = 'required|max:255';
        // }

        $payment_method    = (count($request->payment_method) == 2)? 3: $request->payment_method[0];
        if ($payment_method == 1 || $payment_method == 3) { //
           // $rules['payment_due_date'] = 'required';
        }

        return $rules;
    }
    private function get_validate_message($request) {
        $messsages = array(
            'course_code.required' => 'event_code_required_title', // TODO get msg from resource files
            'course_code.max' => 'event_code_max_title',
            'course_code.unique' => 'event_code_exist_title',
            'course_title.required' => 'name_require', // TODO get msg from resource files
            'course_title.max' => 'name_require_less_than_255',
            'start_date.required' => 'start_date_mandatory', 
            'course_description.required_if' => 'content_require', 
            
            'close_date.after_or_equal' => 'close_date_after_start_date',
            'mail_subject.required_if' => 'mail_subject_required', 
            'mail_subject.max' => 'mail_subject_less_than_255', 
            'recruitment_start.required' => 'recruitment_start_required', 
            'recruitment_start.before_or_equal' => 'recruitment_start_before_start_date', 
            'recruitment_finish.required' => 'recruitment_finish_required', 
            'recruitment_finish.after_or_equal' => 'recruitment_finish_after_recruitment_start', 
            'recruitment_finish.before_or_equal' => 'recruitment_finish_before_close_date', 
            'course_location.required' => 'course_location_required',
            'contact_email.email' => 'contact_email_valid',
            'contact_email.max' => 'contact_email_max_64',
            'payment_methods.required' => 'payment_method_required',
            'payment_due_date.required' => 'payment_due_date_required', 
            'member_capacity.required' => 'member_capacity_required',
            'member_capacity.max' => 'member_capacity_within_10_digit',

        );

        if (count($request->_course_fee) > 0) {
            foreach ($request->_course_fee as $key => $value) {
            $messsages['_course_fee.'.$key.'.fee_plan_name.required'] = 'tuition_name_missing,'.($key+1);
            $messsages['_course_fee.'.$key.'.fee_plan_name.max'] = 'characters_tuition_name_255,'.($key+1);
            $messsages['_course_fee.'.$key.'.attend_times_div.required'] = 'unit_th_number_attempt_not_selected,'.($key+1);
            $messsages['_course_fee.'.$key.'.attend_times.required'] = 'required_unit_lecture_error_title,'.($key+1);
            $messsages['_course_fee.'.$key.'.attend_times.numeric'] = 'numeric_value_th_number_students,'.($key+1);
            $messsages['_course_fee.'.$key.'.attend_times.max'] = 'th_student_number_within_10_digit,'.($key+1);
            $messsages['_course_fee.'.$key.'.fee.required'] = 'th_tuition_not_entered,'.($key+1);
            $messsages['_course_fee.'.$key.'.fee.numeric'] = 'numeric_value_th_tuition,'.($key+1);
            $messsages['_course_fee.'.$key.'.fee.max'] = 'th_tuition_within_10_digit,'.($key+1);
            }
        }

        if (count($request->_course_fee1) > 0) {
            foreach ($request->_course_fee1 as $key => $value) {
            $messsages['_course_fee1.'.$key.'.fee.required'] = 'th_tuition_not_entered,'.($key+1);
            $messsages['_course_fee1.'.$key.'.fee.numeric'] = 'numeric_value_th_tuition,'.($key+1);
            $messsages['_course_fee1.'.$key.'.fee.max'] = 'th_tuition_within_10_digit,'.($key+1);
            $messsages['_course_fee1.'.$key.'.student_type_id.required'] = 'student_type_required,'.($key+1);
            $messsages['_course_fee1.'.$key.'.student_type_id.mean.required'] = 'duplicate_student_type_error,'.($key+1);

            }
        }

        if ($request->has('_lesson')) {
               
            foreach ($request->_lesson as $key => $value) {
                $messsages['_lesson.'.$key.'.start_date.required'] = 'ttl_msg_start_date_not_empty,'.($key+1);
                $messsages['_lesson.'.$key.'.lesson_name.required'] = 'ttl_msg_lesson_name_not_empty,'.($key+1);
                $messsages['_lesson.'.$key.'.lesson_name.max'] = 'ttl_msg_lesson_name_over_length,'.($key+1);
            }
        }
        return $messsages;
    }

    /**
     * Export message file csv
     *
     * @param $message_file_id
     * @return success
     */
    public function exportCSV(Request $request) { 

        if ($request->offsetExists('event_id')) {
            $pschool_id = session('school.login')['id'];
            $export_list = CourseTable::getInstance()->getExportList($pschool_id, $request->event_id);
            foreach ($export_list as $key => $value) {
                $export_list[$key]->id = $key+1;
            }
            // convert object to array
            $data = array_map ( function ($object) {
            return ( array ) $object;
            }, $export_list->toArray() ); 
            $header = array(
                'id' => 'No.',
                'course_title' => 'イベント名称',
                'code' => 'イベントコード',
                'name' => '会員種別',
                'student_name' => '会員名',
                'student_name_kana' => 'カナ',
                'student_no' => '会員番号',
                'total_member' => '参加人数',
                'is_received' => '支払状況',
                'payment_date' => '入金日'
            );

//            $data = array_merge($header, $data);

            // Export
            $data ['dataFile'] = $data;
            $data ['header'] = $header;
            $event_item = DB::table('course')->where('id', $request->event_id)->first();
            $file_name = '「'.$event_item->course_title .'」参加リスト';

            // change $is_crypt and  $crypt_key, must get from request
            $is_crypt = null;
            if (session('school.login.is_zip_csv') == 1) {
                $is_crypt = true;
            }
            $crypt_key = ConstantsModel::CRYPT_KEY_NUM;
            $data ['char_code'] = $request->mode;
            // get info for send mail
            $member_type = strtoupper(session('hierarchy.role'));
            $to_email = null;
            $user_name = null;
            $data_send_mail = array ();
            if (array_search($member_type, ConstantsModel::$member_type) == 4) {
                $parentInfo = DB::table('parent')->where('id', session('school.login.origin_id'))->first();
                $to_email = $parentInfo->parent_mailaddress1;
                $user_name = $parentInfo->parent_name;
            } else {
                $studentInfo = DB::table('student')->where('id', session('school.login.origin_id'))->first();
                $to_email = $studentInfo['mailaddress'];
                $user_name = $studentInfo['student_name'];
            }

            $data ['data_send_mail'] = $data_send_mail;
            $data ['user_name'] = $user_name;
            $data ['to_email'] = $to_email;
            $data ['file_name'] = $file_name;

            CSVExport::exportZipCSV($data, $is_crypt, $crypt_key);
        }
    }
}
