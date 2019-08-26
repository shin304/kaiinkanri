<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Model\PaymentMethodPschoolTable;
use App\Model\PschoolBankAccountTable;
use App\Model\StudentProgramTable;
use App\Model\ProgramFeePlanTable;
use App\Model\MStudentTypeTable;
use App\Model\LessonCoachTable;
use App\Model\ProgramTable;
use App\Model\LessonTable;
use App\Model\CoachTable;
use App\Model\StaffTable;
use App\Model\EntryTable;
use App\ConstantsModel;
use App\Lang;
use Validator;
use Excel;
use DB;
use Illuminate\Support\Facades\Log;
use App\Common\CSVExport;

class ProgramController extends _BaseSchoolController
{
//    private static $TOP_URL = 'program';
    protected static $ACTION_URL = 'program';
//    private static $TEMPLATE_URL = 'program';
//    private static $bread_name = 'プログラム管理';
//    private static $student_name = '会員';
    const SESSION_REGIST_OK = 'school.program.regist_ok';
    const SESSION_SEARCH_COND = 'school.program.search.cond';
    const SESSION_SEARCH_KEY = 'session.school.program.search.key';
    private $_program_search_item = ['_c'];
    private $_program_search_session_key = 'program_search_form';

    protected static $LANG_URL = 'program';

    public function __construct()
    {   
        parent::__construct();
        // 多国語対応
        $message_content = parent::getMessageLocale();
        $this->lan = new Lang($message_content);
        
        // 生徒区分
        view()->share('schoolCategory', ConstantsModel::$dispSchoolCategory);

        $school_ids = array();
        if ( session()->has('school.hierarchy.pschool_parents')) {
            $school_ids = session('school.hierarchy')['pschool_parents'];
        }
        $school_ids [] = session('school.login')['id'];

        $student_types = array();
        $m_type = MStudentTypeTable::getInstance()->getStudentTypeList($school_ids, $this->current_lang);
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
        
    }

    public function index(Request $request) {
        $this->_initSearchDataFromSession($this->_program_search_item, $this->_program_search_session_key);

        // //パンくず
        $this->clearOldInputSession(); 
        // $this->set_menu_no(10);
        // $this->clear_bread_list();
        return $this->executeList($request);
    }

    public function executeList($request) {
        // $this->set_bread_list ( self::$ACTION_URL , ConstantsModel::$bread_list [$this->current_lang] ['program_manage'] );
        $this->_initSearchDataFromSession($this->_program_search_item, $this->_program_search_session_key);
        // ------------------
        // 画面表示情報の取得
        // ------------------
        // プログラム情報
        $program_list = ProgramTable::getInstance()->getProgramList(session('school.login')['id']);
        $display_list = array();
        foreach ($program_list as $index =>$value) {
            // 生徒数
            // $student_count = StudentProgramTable::getInstance()->getStudentListAll($value['id'], session('school.login')['id']);
            $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( session('school.login')['id'], array (
                    'entry_type'    => 3,
                    'relative_id'   => $value['id'],
                    'enter'         => 1  //参加
            ));
            $value['student_count'] = 0;
            foreach ($entries as $entry) {
                // Update 2017-06-08 : Count total_member
                if ($entry['total_member']) { 
                    $value['student_count'] += $entry['total_member'];
                }
            }
            // 開催状況の設定：終了日が、空または終了日>=本日のときＯＫ
            $value['is_active'] = false;
            if (empty($value['close_date']) || $value['close_date'] >= date('Y-m-d')){
                $value['is_active'] = true;
            }
            $display_list[] = $value;
        }
        return view('School.Program.index')->with('lan',$this->lan)->with('list', $display_list)->with('regist_message', session(self::SESSION_REGIST_OK));
        
    }

    public function search(Request $request) {
        $this->_initSearchDataFromSession($this->_program_search_item, $this->_program_search_session_key);
        // $this->set_bread_list ( self::$ACTION_URL , ConstantsModel::$bread_list [$this->current_lang] ['program_manage'] );

        /*
         * _c は、<input type="text" name="_c[publish_datetime]" value="" /> のようにテンプレート側に記述することで、$request->_cが検索条件の配列になる
        */
        
        // ------------------
        // 画面表示情報の取得
        // ------------------
        // プログラム情報
        $program_list = ProgramTable::getInstance()->getProgramList(session('school.login')['id'], $request->_c);
        $display_list = array();
        foreach ($program_list as $index =>$value) {
            // 生徒数
//          $value['student_count'] = StudentProgramTable::getInstance()->getActiveCount(array('program_id'=>$value['id'], 'active_flag'=>1));
            $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( session('school.login')['id'], array (
                    'entry_type'    => 3,
                    'relative_id'   => $value['id'],
                    'enter'         => 1  //参加
            ));
            $value['student_count'] = 0;
            foreach ($entries as $entry) {
                // Update 2017-06-08 : Count total_member
                if ($entry['total_member']) { 
                    $value['student_count'] += $entry['total_member'];
                }
            }

            // 開催状況の設定：終了日が、空または終了日<本日のとき「終了」
            $value['is_active'] = true;
//          if (empty($value['close_date']) || strtotime($value['close_date']) < strtotime(date('Y-m-d'))) {
            if (empty($value['close_date']) || $value['close_date'] >= date('Y-m-d')){
//              $value['is_active'] = false;
                $value['is_active'] = true;
            }
            $display_list[] = $value;
        }

        return view('School.Program.index')->with('lan',$this->lan)->with('list', $display_list)->with('regist_message', session(self::SESSION_REGIST_OK));

    }

    public function input( Request $request) {
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
        $this->recoverWithInput($request, array('id'));
        
        $student_list = array(); // count student to disable DELETE button
        if ($request->has('id')) { // 編集の初期表示
            // 指定されたプログラムが存在する？自分の支部のデータであること。
            $progrma_row = ProgramTable::getInstance()->getActiveRow(array('id'=>request('id'), 'pschool_id'=>session('school.login')['id'], 'active_flag'=>1));

            $request->merge($progrma_row);
            if (empty($progrma_row)){
                $errors['program_id']['notExist'] = true;
                session()->push('errors', $errors);
                return $this->executeList($request);
            }
            if ($progrma_row['payment_method']) {
                $request->offsetSet('payment_methods', explode(',',$progrma_row['payment_method'] ));
            }
            $entry_list = EntryTable::getInstance()->getStudentListbyEventTypeAxis(session('school.login')['id'], array('entry_type'=>3, 'relative_id'=>$request->id, 'enter'=>1));
            foreach ($entry_list as $entry_row) {
                $student_list[] = $entry_row;
            }

            // 受講料金
            if (!$request->has('_program_fee')) {
                $program_fee_list = array();
                $program_fees = ProgramFeePlanTable::getInstance()->getActiveList(array('program_id'=>request('id')),array('sort_no'));
                foreach ($program_fees as $value) {
                    if( session('school.login')['country_code']== 81 ){
                        // とりあえず切り捨て
                        $value['fee'] = floor($value['fee']);
                    }
                    if ($value['student_type_id']) {
                        $program_fee_list['_program_fee1'][] = $value;
                    } else {
                        $program_fee_list['_program_fee'][] = $value;
                    }
                }
                $request->merge($program_fee_list);

            }
            // カリキュラム
            if (!$request->has('_lesson')) {
                $lesson_list = array();
                $lesson_rows = LessonTable::getInstance()->getActiveList(array('program_id'=>request('id')),array('start_date'));
//              if (is_array($lesson_rows)) {
                if (count($lesson_rows) > 0) {
                    foreach ($lesson_rows as $value) {
                        // 講師
                        $coach_rows = LessonCoachTable::getInstance()->getCoachByLesson($value['id'], 2);
                        if (is_array($coach_rows)) {
                            $count = count($coach_rows);
                            for ($i = 0; $i < $count; $i++) {
                                if ($i == 0) {
                                    $value['coach_id1'] = $coach_rows[$i]['coach_id'];
                                }
                                if ($i == 1) {
                                    $value['coach_id2'] = $coach_rows[$i]['coach_id'];
                                }
                            }
                        }
                        $lesson_list['_lesson'][] = $value;
                    }
                } else {
                    // 空白追加
                    $lesson_list['_lesson'][] = array();
                }

                $request->merge($lesson_list);
            }
        } else { // 登録の初期表示
            // 受講料金
            if (!$request->has('_program_fee')) {
                $program_fee_list['_program_fee'][] = array();
                $program_fee_list['_program_fee1'][] = array();
                $request->merge($program_fee_list);
            }
            // カリキュラム
            if (!$request->has('_lesson')) {
                $lesson_list['_lesson'][] = array();
                $request->merge($lesson_list);

            }
        }

        // 講師情報
        $coach_list = CoachTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']));
        // 担当者情報
        $staff_list = StaffTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']));

        // TODO get invoice_type of pschool_bank_account table to set default select 口座振替 (update 2017-06-09)
        // Case Add New Event
        if (!$request->has('id')) {
            $bank_account = PschoolBankAccountTable::getInstance()->getActiveRow(['pschool_id'=>session('school.login')['id']]);
            if (!empty($bank_account) && $bank_account['invoice_type'] == 2 ) { // '0=現金 1=振込 2=口座振替'
                $request->offsetSet('is_default_transfer', true);
            }
            // TODO default min 定員(会員） = 20人
            $request->offsetSet('member_capacity', 20);
            $request->offsetSet('non_member_capacity', 0);
        }
        // 支払方法
        $payment_list = PaymentMethodPschoolTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']), array('sort_no'));
        $mail_template_type = ConstantsModel::$MAIL_TEMPLATE_TYPE[$this->current_lang];
        //テンプレート
        return view('School.Program.input', compact('lan', 'program_data', 'staff_list', 'student_list', 'payment_list', 'mail_template_type','request', 'coach_list'));

    }

    public function complete(Request $request) {
        $mode = (!$request->has('mode')) ? 3 : $request->mode;

        //一応、再確認を行う。
        if( $mode != 2 ) {      // 削除以外のとき
            // TODO change Fee(in _program_fee1) format
            $request_clone = $request->all(); 
            foreach ($request_clone['_program_fee1'] as &$program_fee) {
                $program_fee['fee'] = str_replace(',', '', $program_fee['fee']);
            }
            $request->replace($request_clone);

            $rules = $this->get_validate_rules($request);
            $messsages = $this->get_validate_message($request);
            

            $validator = Validator::make(request()->all(), $rules, $messsages);
            if ($validator->fails()) { 
                    session()->push('old_data', $request->input());
                    return redirect()->to('/school/program/input')->withInput()->withErrors($validator->errors());
            }
            
        }

        // 登録を行う
        $program_tbl = ProgramTable::getInstance();
        $programFeePlan_tbl = ProgramFeePlanTable::getInstance();
        $studentProgram_tbl = StudentProgramTable::getInstance();
        $lesson_tbl = LessonTable::getInstance();
        $lessonCoach_tbl = LessonCoachTable::getInstance();

        try {
            if ($request->has('mode') && $request->mode ==2) { //削除時
                $program_tbl->logicRemove($request->id);
                $programFeePlan_tbl->logicalRemoveByCondition(array('program_id'=>$request->id));
                $studentProgram_tbl->logicalRemoveByCondition(array('program_id'=>$request->id));
                $lesson_tbl->logicalRemoveByCondition(array('program_id'=>$request->id));
                $lessonCoach_tbl->logicalRemoveByCondition(array('program_id'=>$request->id));
            } else {
                // ------------------
                // プログラムテーブル
                // ------------------
                $save = array(
                    'group_id'       => session('school.login')['group_id'],
                    'pschool_id'     => session('school.login')['id'],
                    'program_name'   => $request->program_name,
                    'active_flag'    => 1,
                    'send_mail_flag' => isset($request->send_mail_flag)? 1 : 0,
                    'mail_subject'   => $request->mail_subject,
                    'mail_footer'    => $request->mail_footer,
                    'description'    => $request->description,
                    'start_date'     => $request->start_date,
                    'close_date'     => ($request->close_date != '')? $request->close_date : null,
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
                    'program_location'  => $request->program_location,
                    'program_code'      => $request->program_code,
                    'contact_number'    => $request->contact_number,
                    'contact_email'     => $request->contact_email,
                    'remark'            => $request->remark,
                    'remark_1'          => $request->remark_1,
                    'person_in_charge1' => $request->person_in_charge1,
                    'person_in_charge2' => $request->person_in_charge2,
                    'is_merge_invoice' => $request->is_merge_invoice,
                );
                if ($request->has('non_member_capacity')) {
                    $save['non_member_capacity'] = $request->non_member_capacity;
                }

                if ($request->has('org_program_id')) {  // データ引用があるとき
                    $program_row = $program_tbl->getRow(array('id'=>$request->org_program_id));
                    if (!empty($program_row)) {
                        $save['org_pschool_id'] = $program_row['pschool_id'];
                        $save['org_program_id'] = $request->org_program_id;
                    }
                }

                

                if ($request->has('id')){
                    // 更新のとき
                    $save['id'] = $request->id;
                    // TODO check application_deadline & member_capacity to determine close day
                    $this->checkToCloseProgram($request, $save);
                }
                $program_id = $program_tbl->save($save);

                // ------------------------
                // プログラム受講料テーブル
                // ------------------------
                if ($request->has('id')){
                    foreach ($request->_program_fee1 as $value) {
                        $save = array();
                        $save['student_type_id']= $value['student_type_id'];
                        $save['fee_plan_name']  = $value['fee_plan_name'];
                        $save['fee']            = $value['fee'];
                        $save['sort_no']        = $value['sort_no'];
                        $save['payment_unit']   = $value['payment_unit']; //1:一人当たり, 2:全員で
                        $save['active_flag']    = (isset($value['active_flag']) && $value['active_flag'] == '0')? 0 : 1;
                        
                        if (empty($value['id'])) {
                            // 追加
                            if ($value['student_type_id']) {
                                $save['program_id']     = $program_id;
                                $save['pschool_id']     = session('school.login')['id'];
                                $save['group_id']       = session('school.login')['group_id'];
                            }
                        } else {
                            // 編集
                            $save['id'] = $value['id'];
                        }
                        $programFeePlan_tbl->save($save);

                    }
                } else {
                    foreach ($request->_program_fee1 as $value) {
                        $save = array();
                        $save['program_id']     = $program_id;
                        $save['pschool_id']     = session('school.login')['id'];
                        $save['group_id']       = session('school.login')['group_id'];
                        $save['student_type_id']= $value['student_type_id'];
                        $save['fee_plan_name']  = $value['fee_plan_name'];
                        $save['fee']            = $value['fee'];
                        $save['sort_no']        = $value['sort_no'];
                        $save['payment_unit']   = $value['payment_unit']; //1:一人当たり, 2:全員で
                        $save['active_flag']    = (isset($value['active_flag']) && $value['active_flag'] == '0')? 0 : 1;

                        $programFeePlan_tbl->save($save);
                    } 
                }
                
                if ($request->has('_lesson') && count($request->_lesson) > 0){
                    // -----------------
                    // レッスンテーブル
                    // -----------------
                    $save = array();
                    $lesson_id = null;
                    if ($request->has('id')){
                        // 更新
                        foreach ($request->_lesson as $value) {

                            if (strlen($value['start_date']) > 0 && strlen($value['lesson_name']) > 0) {
                                $save = array();
                                $save['lesson_name'] = $value['lesson_name'];
                                $save['active_flag']    = (isset($value['active_flag']) && ($value['active_flag'] == '0'))? 0 : 1;
                                $save['start_date'] = $value['start_date'];
                                if (empty($value['id'])) {
                                    // 追加
                                    $save['program_id'] = $program_id;
                                    $save['pschool_id'] = session('school.login')['id'];
                                    $save['group_id']   = session('school.login')['group_id'];
                                    
                                    $lesson_id = $lesson_tbl->save($save);
                                } else {
                                    // 編集
                                    $save['id'] = $value['id'];
                                    
                                    $lesson_id = $lesson_tbl->save($save);
                                }
                                $this->saveLessonCoach($lesson_id, $value);
                            } // End if (strlen($value['start_date']) && strlen($value['lesson_name']) > 0)
                            
                        }
                    } else {
                        // 登録
                        foreach ($request->_lesson as $value) {
                            $save = array();
                            if (strlen($value['start_date']) > 0) {
                                $save['program_id']  = $program_id;
                                $save['pschool_id']  = session('school.login')['id'];
                                $save['group_id']    = session('school.login')['group_id'];
                                $save['lesson_name'] = $value['lesson_name'];
                                $save['start_date']  = $value['start_date'];
                                $save['active_flag'] = (isset($value['active_flag']) && ($value['active_flag'] == '0'))? 0 : 1;
                                
                                $lesson_id = $lesson_tbl->save($save);
                                $this->saveLessonCoach($lesson_id, $value);
                            }
                        } // End foreach
                    }
                }
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $this->executeList();
        }

        //TOPの一覧画面にとばす
            session(['regist_message' => $mode]);

        return redirect()->to('/school/program');
    }

    private function saveLessonCoach($lesson_id, $input_lesson) {
        $lesson_tbl = LessonTable::getInstance();
        $lessonCoach_tbl = LessonCoachTable::getInstance();
        try {
            $lessonCoach_tbl->logicalRemoveByCondition(array('lesson_id'=>$lesson_id));
            if (!empty($input_lesson['coach_id1']) || !empty($input_lesson['coach_id2'])) {
                $lesson = $lesson_tbl->load($lesson_id);
                $save = array (
                    'program_id'    => $lesson['program_id'],
                    'pschool_id'    => $lesson['pschool_id'],
                    'group_id'      => $lesson['group_id'],
                    'lesson_id'     => $lesson_id
                );
                if (!empty($input_lesson['coach_id1'])) {
                    $save['coach_id']   = $input_lesson['coach_id1'];
                    $save['active_flag'] = (isset($input_lesson['active_flag']) && ($input_lesson['active_flag'] == '0'))? 0 : 1;
                    $lessonCoach_tbl->save($save);
                }
                if (!empty($input_lesson['coach_id2']) && $input_lesson['coach_id2'] != $input_lesson['coach_id1']) {
                    $save['coach_id']   = $input_lesson['coach_id2'];
                    $save['active_flag'] = (isset($input_lesson['active_flag']) && ($input_lesson['active_flag'] == '0'))? 0 : 1;
                    $lessonCoach_tbl->save($save);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            exit;
        }
    }

    /**
    *check select application_deadline & joined memeber to close program
    */
    private function checkToCloseProgram($request, &$program) {

        if ($program['application_deadline'] == 1) {
            // TODO check joined member
            // 生徒数
            $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( session('school.login')['id'], array (
                    'entry_type'    => 3, // Program
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
                $program['recruitment_finish'] = date('Y-m-d H:i:s');
            }

        }
    }

    private function get_validate_rules($request) { 
        $rules = [
                'program_code'          => 'required|max:5|unique:program,program_code,NULL,id,pschool_id,'.session('school.login')['id'],
                'program_name'          => 'required|max:255',
                'mail_subject'          => 'required_if:send_mail_flag,on|max:255',
                'description'           => 'required_if:send_mail_flag,on', 
                '_program_fee1'         => 'required',
                // '_program_fee.*.fee_plan_name' => 'required|max:255',
                // '_program_fee.*.fee'    => 'required|numeric|max:9999999999',
                'start_date'            => 'required', 
                'recruitment_start'     => 'required|before_or_equal:start_date',
                'recruitment_finish'    => 'nullable|after_or_equal:recruitment_start',
                'program_location'      => 'required',
                'contact_email'         => 'nullable|email|max:64',
                'member_capacity'       => 'nullable|numeric|max:9999999999', 
                    ];
        if ($request->has('close_date')) {
           $rules['close_date']         = 'after_or_equal:start_date';
           $rules['recruitment_finish'] = 'nullable|after_or_equal:recruitment_start|before_or_equal:close_date';

        }
        if ($request->has('id')) {
            $rules['program_code']       = 'required|max:5|unique:program,program_code,' . $request->id. ',id,pschool_id,'.session('school.login')['id'];
        }
        // 会員種別による料金設定
        if ($request->fee_type == 1 && $request->has('_program_fee1') && count($request->_program_fee1)>0) {
            $rules['_program_fee1.*.fee'] = 'required|numeric|max:9999999999';
            $rules['_program_fee1.*.student_type_id'] = 'required';

            // validate unique [student_type_id + fee_plan_name + payment_unit]
            $program_fee1 = $request->_program_fee1;
            $err_list = array();
            foreach ($program_fee1 as $key => $value) {
                array_shift($program_fee1);
                if (count($program_fee1) > 0 && !in_array($value['sort_no'], $err_list)) {
                    foreach ($program_fee1 as $key1 => $value1) {
                        if ($value['student_type_id'] == $value1['student_type_id'] && $value['fee_plan_name'] == $value1['fee_plan_name'] && $value['payment_unit'] == $value1['payment_unit']) {
                            $rules['_program_fee1.'.((int)$value1['sort_no']-1).'.student_type_id.mean'] = 'required';
                            $err_list[] = $value1['sort_no'];
                        }
                    }
                }
            }
//            $idx1=null;
//            foreach ($request->_program_fee1 as $key => $value) {
//                if ($key !=0) {
//                    if ($value['student_type_id'] == $idx1) {
//                        $rules['_program_fee1.'.$key.'.student_type_id.mean'] = 'required';
//
//                    }
//                }
//                $idx1 = $value['student_type_id'];
//            }
        // 回数による料金設定
        } 
        if ($request->has('_lesson')) {
            $rules['_lesson.*.start_date'] = 'required';
            $rules['_lesson.*.lesson_name'] = 'required|max:255';
            
        }

        if ($request->has('application_deadline')) {
            $rules['member_capacity']   = 'required|numeric|max:9999999999';
            
        }

        return $rules;
    }

    private function get_validate_message($request) {
        $messsages = [
                'program_code.unique' => 'program_code_exist_title',
                'program_code.required' => 'program_code_required_title',
                'program_code.max' => 'program_code_max_title',
                'program_name.required' => 'ttl_msg_program_name_not_empty',
                'program_name.max' => 'ttl_msg_program_name_over_length',
                '_program_fee1.required' => 'ttl_msg_program_fee_not_empty',

                'start_date.required' => 'start_date_require', 
                'description.required_if' => 'content_require', 
                'close_date.after_or_equal' => 'close_date_after_start_date',
                'mail_subject.required_if' => 'mail_subject_required', 
                'mail_subject.max' => 'mail_subject_less_than_255', 
                'recruitment_start.required' => 'recruitment_start_required', 
                'recruitment_start.before_or_equal' => 'recruitment_start_before_start_date', 
                'recruitment_finish.after_or_equal' => 'recruitment_finish_after_recruitment_start', 
                'recruitment_finish.before_or_equal' => 'recruitment_finish_before_close_date', 
                'program_location.required' => 'program_location_required',
                'contact_email.email' => 'contact_email_valid',
                'contact_email.max' => 'contact_email_max_64',
                'payment_due_date.required' => 'payment_due_date_required', 
                'member_capacity.required' => 'member_capacity_required',
                'member_capacity.max' => 'member_capacity_within_10_digit',
            ];
        if ($request->has('_program_fee1')) {
            foreach ($request->_program_fee1 as $key => $value) {
                // $messsages['_program_fee1.'.$key.'.fee_plan_name.required'] = 'ttl_msg_fee_plan_name_not_empty,'.($key+1);
                // $messsages['_program_fee1.'.$key.'.fee_plan_name.max'] = 'ttl_msg_fee_plan_name_over_length,'.($key+1);
                $messsages['_program_fee1.'.$key.'.fee.required'] = 'ttl_msg_fee_not_empty,'.($key+1);
                $messsages['_program_fee1.'.$key.'.fee.numeric'] = 'ttl_msg_fee_not_numeric,'.($key+1);
                $messsages['_program_fee1.'.$key.'.fee.max'] = 'ttl_msg_fee_over_length,'.($key+1);
                $messsages['_program_fee1.'.$key.'.student_type_id.required'] = 'student_type_required,'.($key+1);
                $messsages['_program_fee1.'.$key.'.student_type_id.mean.required'] = 'duplicate_student_type_error,'.($key+1);
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

        if ($request->offsetExists('program_id')) {
            $pschool_id = session('school.login')['id'];
            $export_list = DB::table('program as p')->join('entry as e', 'e.relative_id', '=', 'p.id') // get total_member, code
            ->join('student as s', 's.id', '=', 'e.student_id') //get student_name, student_name_kana, student_no
            ->join('student_program as sp', function ($join) { //get is_received, payment_date, plan_id
                $join->on('sp.program_id', '=', 'p.id')->on('sp.student_id', '=', 's.id');
            })
            ->leftJoin('program_fee_plan as pfp', 'pfp.id', '=', 'sp.plan_id') // get student_type_id
            ->leftJoin('m_student_type as mst', function ($join) use ($pschool_id) { //get name
                $join->on('mst.id', 'pfp.student_type_id')->on('mst.pschool_id', DB::raw($pschool_id));
            })
            ->where('s.pschool_id', $pschool_id)->where('p.id', $request->program_id)
            ->where('e.enter', 1)
            ->whereNull('p.delete_date')
            ->select('p.id', 'p.program_name', 'e.code', 'mst.name', 's.student_name', 's.student_name_kana', 's.student_no', 'e.total_member' ,DB::raw('(CASE sp.is_received WHEN 0 THEN "未入金" ELSE "入金済み" END) AS is_received'),'sp.payment_date')->orderBy('s.student_name_kana')->get();

            
            // convert object to array

            $export_list = $export_list->toArray();
            foreach ($export_list as $key => $value){
                $export_list[$key]->id = $key+1;
            }

            $data = array_map ( function ($object) {
            return ( array ) $object;
            }, $export_list);
            $header = array(
                'id' => 'No.',
                'program_name' => 'プログラム名称',
                'code' => 'プログラムコード',
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
            $program_item = DB::table('program')->where('id', $request->program_id)->first();
            $file_name = '「'.$program_item->program_name .'」参加リスト';

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
