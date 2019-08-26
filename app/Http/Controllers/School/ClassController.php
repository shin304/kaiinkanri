<?php

namespace App\Http\Controllers\School;

use App\Common\Constants;
use Illuminate\Http\Request;
use App\ConstantsModel;
use App\Model\ClassTable;
use App\Model\CoachTable;
use App\Model\HierarchyTable;
use App\Model\ClassCoachTable;
use App\Model\ClassFeePlanTable;
use App\Model\StudentClassTable;
use App\Model\StudentTable;
use App\Model\MStudentTypeTable;
use App\Model\RoutinePaymentTable;
use App\Model\InvoiceAdjustNameTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\ClassPaymentScheduleTable;
use App\Lang;
use Validator;
use DB;

class ClassController extends _BaseSchoolController
{
	private static $TOP_URL = 'class/list';
	protected static $ACTION_URL = 'class';
	private static $TEMPLATE_URL = 'class';
	private static $bread_name = 'プラン';
	private static $student_name = '生徒';
	const SESSION_REGIST_OK = 'school.class.regist_ok';
	const SESSION_SEARCH_COND = 'school.class.search.cond';
	const SESSION_SEARCH_KEY = 'session.school.class.search.key';
    protected static $LANG_URL = 'class';
    private $_class_search_item = ['_c'];
    private $_class_search_session_key = 'class_search_form';

	public function __construct()
    {
    	parent::__construct();
    	// 多国語対応
    	$message_content = parent::getMessageLocale();
    	$this->lan = new Lang($message_content);
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
    }

    public function index(Request $request) {
	    $this->_initSearchDataFromSession($this->_class_search_item, $this->_class_search_session_key);

        $this->recoverWithInput($request, array('message_mode'));

        $this->clearOldInputSession();
		return $this->executeList($request);
    }

    public function executeList($request) {

        $array_search = $request->_c;
		$pschool_ids = array ();
		// 自支部の情報のみ表示する（2016.2.2の仕様変更）
		$pschool_ids [] = session('school.login')['id'];

		$display_list = ClassTable::getInstance()->getClassList4TopAxis($pschool_ids, $array_search, session('school.login')['id']);
		$display_list = $this->getSortListByClassCourse($display_list);
		return view('School.Class.index')->with('lan',$this->lan)->with('list', $display_list);
    }

	public function input(Request $request) {

        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
        $this->recoverWithInput($request, array('id'));



		// 自分の塾の情報を得る
		if ($request->has('id')) {

            $data = ClassTable::getInstance ()->load ($request->id);
            $request->merge($data);
            if ($data['payment_method']) {
                $request->offsetSet('payment_methods', explode(',',$data['payment_method'] ));
            }
            // 複数講師

				$request->offsetSet('teacher_ids', ClassCoachTable::getInstance ()->getCoachIDs ( $request, session('school.login')['id']));
            // 受講料金
            // 編集の初期表示
            if (!$request->has('_class_fee')) {

                $class_fee_list = array ();
                // 自支部の情報のみ表示する(2016.2.2の仕様変更)
                $pschool_ids [] = session('school.login.id');

                $class_fee = ClassFeePlanTable::getInstance()->getActiveList(array('class_id'=> $request->id, 'pschool_id IN (' . implode(',', $pschool_ids) . ') '), array('sort_no'));
//                $class_fee = ClassFeePlanTable::getInstance ()->getFees ( $request->id, $pschool_ids );
                foreach ( $class_fee as $value ) {
                    // とりあえず切り捨て
                    $value ['fee'] = floor ( $value ['fee'] );
                    $class_fee_list ['_class_fee'] [] = $value;
                }
                $request->merge($class_fee_list);
            }

            // その他費用
            if (!$request->has('payment')) {
                $routine_payments = RoutinePaymentTable::getInstance ()->getRoutinePayemntList ( session('school.login')['id'], 1, $request->id );
                $payment = array ();
                foreach ( $routine_payments as $payment_item ) {
                    $payment [] = array(
                        'payment_month'  => $payment_item ['month'],
                        'payment_adjust' => $payment_item ['invoice_adjust_name_id'],
                        'payment_fee'    => $payment_item ['adjust_fee'],
                        'payment_id'     => $payment_item ['id'],
                    );
                }
                if (count ( $payment ) > 0) {
                    $request->offsetSet('payment', $payment);
                }
            }
		} else {
            // 登録の初期表示
            if (!$request->has('_class_fee')) {
                $class_fee_list ['_class_fee'] [] = array ();
                $request->merge($class_fee_list);
            }
        }

        $parent_ids = array();
        $parent_list = HierarchyTable::getInstance ()->getParentPschoolIds ( session('school.login')['id'] );
        if (!empty($parent_list)) {
            if (isset($parent_list['pschool_id'])) {
                $parent_ids[] = $parent_list['pschool_id'];
            } else {
                $parent_ids = $parent_list;
            }
        }
        // 自支部の情報のみ表示する(2016.2.2の仕様変更)
        $parent_ids [] = session('school.login')['id'];
        $invoice_adjust_list = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ( $parent_ids );

        // 支払方法
        $payment_list = PaymentMethodPschoolTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']), array('sort_no'));

        // 講師
        $teacher_data 	= CoachTable::getInstance ()->getActiveList (array('pschool_id'=>session('school.login')['id']));
        // 回数の単位
        $attendUnitList = ConstantsModel::$attend_unit [session('school.login')['language']];
        //その他費用の対象月
        $month_list = ConstantsModel::$month_listEx [$this->current_lang];

        // price_setting_type : 料金設定 [1:会員種別による料金設定, 2: 受講回数による料金設定]
        if(empty($request['price_setting_type'])){
            $request->offsetSet('price_setting_type', session('school.login')['price_setting_type']);
        }

		return view('School.Class.input', compact('request', 'lan', 'attendUnitList', 'teacher_data', 'month_list', 'invoice_adjust_list', 'payment_list'));
	}

	public function complete(Request $request) {
		$mode = 0;

		if (!$request->has('mode')) {
			$mode = ($request->has('id'))? 2 : 1;  // １：登録、２：編集
		} else {
			$mode = 3; // 削除
		}
		//一応、再確認を行う。
		if( $mode != 3 ) {	// 登録、編集モード時は、必須データチェック
            // number_format fee
            $request_clone = $request->all();
            if ($request->has('_class_fee')) {
                foreach ($request_clone['_class_fee'] as &$class_fee) {
                    $class_fee['fee'] = str_replace(',', '', $class_fee['fee']);
                }
            }
            if ($request->has('payment')) {
                foreach ($request_clone['payment'] as &$payment) {
                    $payment['payment_fee'] = str_replace(',', '', $payment['payment_fee']);
                }
            }
            $request->replace($request_clone);

			//ヴぁりデート
			// エラー表示用の配列
			$rules = $this->get_validate_rules($request);
			$messages = $this->get_validate_message($request);


			$validator = Validator::make(request()->all(), $rules, $messages);

			if ($validator->fails()) {
	            return redirect()->back()->withInput()->withErrors($validator->errors());
	        }
		} else { //削除時

            $errors    = array();
            //自身以外が登録したプランはエラーにする
            $class_row = ClassTable::getInstance()->getRow(array('pschool_id'=>session('school.login')['id'], 'id'=>$request->id));
            if (empty($class_row)) {
                $errors['_class']['notDelete'] = true;
            }
            if (!empty($errors)) {
                // return $this->executeDetail();
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }

		//登録を行う
		$class_tbl = ClassTable::getInstance();
		$student_class_tbl = StudentClassTable::getInstance();
		$class_fee_plan_tbl = ClassFeePlanTable::getInstance();
		$class_coach_tbl = ClassCoachTable::getInstance();
        DB::beginTransaction();
		try {
			
			if ($mode ==3) { //削除時
				$class_tbl->logicRemove($request->id);
				$student_class_tbl->logicalRemoveByCondition(array('class_id'=>$request->id));
				$class_fee_plan_tbl->logicalRemoveByCondition(array('class_id'=>$request->id));
				$class_coach_tbl->logicalRemoveByCondition(array('class_id'=>$request->id));
				
			} else {

                $save['class_name']         = $request->class_name;
                $save['class_description']  = $request->class_description;
                $save['payment_method']     = implode(',', $request->payment_methods);
                $save['pschool_id']         = session('school.login')['id'];
                $save['active_flag']        = 1;
                $save['school_category']    = null; // 中学／高校
                $save['school_year']        = null; // 学年
                $save['start_date']         = $request->start_date;
                $save['close_date']         = $request->close_date;
                $save['price_setting_type'] = $request->price_setting_type;
                if ($request->has('org_class_id')) {    // データ引用があるとき
                    $class_row = $class_tbl->getRow(array('id' => $request->org_class_id));
                    if (!empty($class_row)) {
                        $save['org_pschool_id'] = $class_row['pschool_id'];
                        $save['org_class_id'] = $class_row['id'];
                    }
                }
                if ($request->has('id')) {
                    $save['id']         = $request->id;
                }
                $save['group_id'] = session('school.login')['group_id'];
                $class_id = $class_tbl->save($save);

                // プラン授業料
                foreach ($request->_class_fee as $key => $value) {

                    //1:会員種別による料金設定
                    $save_fee = array(
                        'fee_plan_name' => $value['fee_plan_name'], //名称
                        'student_type_id' => $value['student_type_id'], // 会員種別
                        'payment_unit' => $value['payment_unit'], // 1: 一人当たり, 2: 全員で
                        'fee' => $value['fee'], //料金
                        'sort_no' => $key + 1,
                        'active_flag' => (isset($value['active_flag']) && $value['active_flag'] == '0') ? 0 : 1, //無効
                    );

                    // 2:受講回数による料金設定
                    if ($request->price_setting_type == 2) {
                        $save_fee['attend_times_div'] = $value['attend_times_div']; // 回数の単位
                        $save_fee['attend_times'] = $value['attend_times']; // 回数
                    }
                    if (empty($value['id'])) {
                        // 追加
                        $save_fee['class_id'] = $class_id;
                        $save_fee['pschool_id'] = session('school.login')['id'];
                        $save_fee['group_id'] = session('school.login')['group_id'];
                    } else {
                        // 編集
                        $save_fee['id'] = $value['id'];
                    }
                    $class_fee_plan_tbl->save($save_fee);
                }

                // 2015-10-02 複数講師
                $class_coach_tbl->logicalRemoveByCondition(array('class_id' => $class_id, 'pschool_id' => session('school.login')['id']));
                if ($request->has('teacher_ids')) {
                    foreach ($request->teacher_ids as $teacher_id) {
                        if ($teacher_id) {

                            $map = array(
                                'group_id' => session('school.login')['group_id'],
                                'pschool_id' => session('school.login')['id'],
                                'class_id' => $class_id,
                                'coach_id' => $teacher_id,
                                'active_flag' => 1,
                            );
                            $class_coach_tbl->save($map);
                        }
                    }
                }

                //-------------------------------------------------------------
                // 受講料以外にかかる費用
                //-------------------------------------------------------------
                $payment_tbl = RoutinePaymentTable::getInstance();
                // ①まず登録されているもの取得
                $registed_list = array();

                if ($class_id) {
                    $registed_list = RoutinePaymentTable::getInstance()->getRoutinePayemntList(session('school.login')['id'], 1, $class_id);
                }

                if (count($request->payment) < 1) {
                    // ②既存の全部削除
                    foreach ($registed_list as $regist_item) {
//						$regist_item['active_flag']   	= 0;
//						$regist_item['delete_date']   	= date('Y-m-d H:i:s');
//						$regist_item['update_date']   	= date('Y-m-d  H:i:s');
//						$regist_item['update_admin']	= session('school.login')['login_account_id'];
//						$PaymentTable->updateRow($regist_item, $where=array('id'=>$regist_item['id']));
                        $payment_tbl->logicRemove($regist_item['id']);

                    }
                } else {
                    $registed_select_list = array();
                    foreach ($registed_list as $item) {
                        // todo set id to key
                        $registed_select_list[$item['id']] = $item;
                    }

                    foreach ($request->payment as $key=>$payment_item) {

                        $new_payment = array(
                            'month' => $payment_item['payment_month'],
                            'invoice_adjust_name_id' => $payment_item['payment_adjust'],
                            'adjust_fee' => $payment_item['payment_fee'],
                        );
//
                        if (empty($payment_item['payment_id'])) {
                            $new_payment['pschool_id'] = session('school.login')['id'];
                            $new_payment['data_div'] = 1;
                            $new_payment['data_id'] = $class_id;
                            $new_payment['register_date'] = date('Y-m-d H:i:s');
                            $new_payment['register_admin'] = session('school.login')['login_account_id'];
                        } else {

                            $new_payment['id'] = $payment_item['payment_id'];
                            $new_payment['update_date'] = date('Y-m-d H:i:s');
                            $new_payment['update_admin'] = session('school.login')['login_account_id'];
                            //todo unset $registed_select_list => final list
                            unset($registed_select_list[$payment_item['payment_id']]);
                        }
                        $payment_tbl->save($new_payment);

                    }

                    // ⑤一部削除
                    foreach ($registed_select_list as $unselect_item) {
                        $payment_tbl->logicRemove($unselect_item['id']);
                    }

                }
            }
         DB::commit();
		} catch (Exception $ex) {
            DB::rollBack();
			$this->_logger->error($ex->getMessage());
			return $this->input();
		}

		$request->offsetSet('message_mode', $mode);
		return redirect()->to('/school/class')->withInput();
	}

	public function detail( Request $request) {
        $this->clearOldInputSession();

		if (!$request->has('id')) {
			return redirect()->to('/school/class');
		}
		if (session()->has('errors')) {
            $request->session()->forget('errors');
        }

		// ----------------
		// プラン情報の取得
		// -----------------
		$class = ClassTable::getInstance()->load($request->id);

		// 終了？
		if (empty($class['close_date']) || $class['close_date'] >= date('Y-m-d')){
			$class['is_active'] = 1;
		}

		// ----------------
		// 講師情報の取得
		// -----------------
		$teacher_list = array();
		$teacher_ids = ClassCoachTable::getInstance()->getCoachIDs($class, session('school.login')['id']);
		foreach ($teacher_ids as $teacher_id){
			if (strlen($teacher_id)){
				$teacher_list[] = CoachTable::getInstance()->getRow($where=array('pschool_id'=>session('school.login')['id'], 'id'=>$teacher_id));
			}
		}

		$class_fee = array();
		$pschool_ids = array ();
		// 自支部の情報のみ表示する(2016.2.2の仕様変更)
		$pschool_ids [] = session('school.login') ['id'];
		$class_fee_list = ClassFeePlanTable::getInstance()->getFees(request('id'), $pschool_ids);
		foreach ($class_fee_list as $value) {
			$class_fee[] = array('name'=>$value['fee_plan_name'],'fee'=>$value['fee']);
		}

		//-----------------
		//生徒情報の取得
		//-----------------
        // search by type
        $arrQuery = array();
        if($request->offsetExists('_student_types')){
            $arrQuery['student_type'] = $request->_student_types;
        }
        //dd($arrQuery);
        //
		$student_list = array();
		$student_class_list = StudentClassTable::getInstance()->getStudentListExistsAxis($request->id, "", session('school.login')['id'],$arrQuery);
		foreach ($student_class_list as $value) {
			$value['fee'] = number_format(floor($value['fee']));
            $value['total_fee'] = number_format(floor($value['total_fee']));
            $value['sum_coop_fee'] = number_format(floor($value['sum_coop_fee']));
            $value['sum_coop_total_fee'] = number_format(floor($value['sum_coop_total_fee']));
			$student_list[] = $value;
		}

        // 支払方法
        $payment_method = array();
        if (!is_null($class['payment_method'])) {
            $payment_method = $this->getClassPaymentMethod($class['payment_method']);
        }

		//set student_type_name
		$student_class_ids = array();
        $studentTypeList = MStudentTypeTable::getInstance()->getStudentTypeList($pschool_ids, $this->current_lang);

        if(isset($arrQuery['student_type'])){
            foreach($studentTypeList as $k=>$type){
                if(in_array($type['id'],$arrQuery['student_type'])){
                    $studentTypeList[$k]['is_display'] =1;
                }else{
                    $studentTypeList[$k]['is_display'] =0;
                }
            }
        }else{
            foreach($studentTypeList as $k=>$type){
                $studentTypeList[$k]['is_display'] =1 ;
            }
        }
		foreach ($student_list as $idx=> $row){
            foreach ($studentTypeList as $rw){
                if ($rw['id'] == $row['m_student_type_id']){
                    $student_list[$idx]['student_type_name'] = $rw['name'];
                }
            }
            $student_class_ids[] = $row['student_class_id'];

            // for warning view
            if(!in_array($row['invoice_type'],$payment_method)){
                $student_list[$idx]['method_supported'] = false;
                $request->offsetSet('warning_flag',true);
            }else{
                $student_list[$idx]['method_supported'] = true;
            }

		}

        // use to next, previous edit
        session()->put('prev_next_class_student_ids', $student_class_ids);

        // todo get list schedule
        $student_payment_plan = array();

        if (count($student_class_ids) > 0) {
            $payment_schedule = ClassPaymentScheduleTable::getInstance()->getActiveList(['student_class_id IN ('.implode(', ', $student_class_ids).')']);
            foreach ($payment_schedule as $row) {
                $student_payment_plan[$row['student_class_id']][$row['payment_times_no']] = $row;
            }
        }
        //支払回数
        $month_list = ConstantsModel::$payment_times_list[$this->current_lang];
		return view('School.Class.detail', compact('request', 'lan', 'class_fee', 'class', 'teacher_list', 'student_list', 'student_payment_plan', 'month_list','studentTypeList','arrQuery'));
	}

    /**
     * Load info 会員追加・会員削除
     * @param Request $request
     * @return View
     */
	public function studentList(Request $request) {

        // merge number of payment to request
        if(isset($request->number_of_payment) && $request->number_of_payment>0 && isset($request->payment_date)
                && isset($request->payment_fee)){
            for($i =0; $i<$request->number_of_payment;$i++){
                $class_payment_schedule[$i+1]=array(
                        'payment_time_no'=>$i+1,
                        'schedule_date'=>$request['payment_date'][$i+1],
                        'schedule_fee'=>$request['payment_fee'][$i+1],
                );
            }
            $request->offsetSet('class_payment_schedule', $class_payment_schedule);
        }
        $student_class = array('plan_id'=>null,
                               'number_of_payment'=>null,
                               'notices_mail_flag'=>null);
        if(isset($request->number_of_payment)){
            $student_class['number_of_payment'] =$request->number_of_payment;
        }
        if(isset($request->plan_id)){
            $student_class['plan_id'] =$request->plan_id;
        }
        if(isset($request->notices_mail_flag)){
            $student_class['notices_mail_flag'] =$request->notices_mail_flag;
        }
        $student_class = (object)$student_class;
        $request->offsetSet('student_class', $student_class);
        // end merge

        $this->recoverWithInput($request, array('id','mode', 'student_name', '_student_types'));

        if (!$request->id || !$request->mode) {
			return redirect()->to('/school/class');
		}

		//プラン情報を割り当てる
        $class_id = $request->id;
		$class_info = ClassTable::getInstance()->load($class_id);

		// プラン受講料
		$pschool_ids = array ();
		$pschool_ids[]          = session('school.login')['id'];
		$class_fee_plan_list    = ClassFeePlanTable::getInstance()->getFees($class_id, $pschool_ids);
		$class_fee_plan         = array();
		foreach ($class_fee_plan_list as $idx => $row) {
            // $class_fee_plan: Ex: [1 => "A01 | 10,000（円）", 2 => "A02 | 20,000（円）", ...]
			$class_fee_plan[$row['id']]['value'] = $row['fee_plan_name'].' | '.number_format(floor($row['fee'])).'（円）';
			$class_fee_plan[$row['id']]['student_type_id'] = $row['student_type_id'];
		}
        // 支払方法
        $payment_method = array();
        if (!is_null($class_info['payment_method'])) {
            $payment_method = $this->getClassPaymentMethod($class_info['payment_method']);
        }

        //支払回数
        $month_list = ConstantsModel::$payment_times_list[$this->current_lang];

		// SEARCH CONDITION
		$student_name = ($request->has('student_name'))? ($request->student_name) : null;

		// 生徒種別
		$school_ids = array();
		if ( session()->has('school.hierarchy.pschool_parents')) {
			$school_ids = session('school.hierarchy')['pschool_parents'];
		}
		$school_ids [] = session('school.login')['id'];
		$studentTypeList = MStudentTypeTable::getInstance()->getStudentTypeList($school_ids, $this->current_lang);
		$dispStudentTypeList['_student_types'] = $studentTypeList;

		// 検索
		$arry_search = array();
        // 生徒種別の表示制御
		if ($request->has('_student_types') && count($request->_student_types) > 0) {
            $arry_search['student_type'] = array_column($request->_student_types, 'type');
			foreach ($dispStudentTypeList['_student_types'] as $key=>$value) {
                if (array_key_exists($key,$request->_student_types)) {
                    $dispStudentTypeList['_student_types'][$key]['is_display'] = 1;
                } else {
                    $dispStudentTypeList['_student_types'][$key]['is_display'] = 0;
                }
			}
		} else {
			// 初期表示
			foreach ($studentTypeList as $row){
				if ($row['is_display']) {
					$arry_search['student_type'][]=$row['id'];
				}
			}
		}
        $request->merge($dispStudentTypeList);

		if ($request->has('student_no')) {
            $arry_search['student_no'] = $request->student_no;
        }

        if($request->offsetExists('invoice_type_search')){
            $arry_search['invoice_type_search'] = $request->invoice_type_search;
        }

		$list = array();
		if ($request->mode == 1) { // プラン生追加
            $list = StudentClassTable::getInstance()->getStudentListNotExists($class_id, $student_name, session('school.login')['id'], $arry_search);
		} else { //プラン生削除
			$student_class_list = StudentClassTable::getInstance()->getStudentListExistsAxis($class_id, $student_name, session('school.login')['id'], $arry_search);
			foreach ($student_class_list as $value) {
				$value['fee'] = number_format(floor($value['fee']));
				$list[] = $value;
			}
		}

		//会員種別追加
        $invoice_types = Constants::$invoice_type[session()->get('school.login.language')];
		foreach ($list as $idx=> $row){
            foreach ($studentTypeList as $rw){
                if ($rw['id'] == $row['m_student_type_id']){
                    $list[$idx]['student_type_name'] = $rw['name'];
                }

            }
            if(!empty($row['invoice_type'])){
                $list[$idx]['payment_name'] = $invoice_types[$row['invoice_type']];
                if(!in_array($row['invoice_type'],$payment_method)){
                    $list[$idx]['method_supported'] = false;
                }else{
                    $list[$idx]['method_supported'] = true;
                }
            }
		}
		// get invoice list
        $filter = array();

        //dd($request->invoice_type_search);
        if($request->offsetExists('invoice_type_search')){
            $filter = $request->invoice_type_search;
        }
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];
        // mapping with detail student
//        session()->put('prev_next_student_ids', $prevNextStudentIds);

		return view('School.Class.student_list', compact('invoice_type','request', 'lan', 'class_info', 'class_fee_plan', 'class_fee_plan_list', 'list', 'month_list', 'payment_method','class_payment_schedule','student_class','filter'));
	}

    /**
     * Execute 会員追加・会員削除
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|void
     */
	public function studentProc(Request $request) {
		if (!$request->has('id') || !$request->has('mode') ) {
			return;
		}

        //ヴぁりデート
        // エラー表示用の配列
        $rules = ['students' => 'required', 'plan_id' => 'required_if:mode,1'];
        $messages = ['students.required' => 'select_error_title', 'plan_id.required_if' => 'payment_method_required_title'];


        $validator = Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

		$class_fee_plan_id_array = array();
//		if ($request->mode == '1') { // 会員追加
//            //料金ヴぁりデート
//            foreach ($request->students as $idx=>$student) {
//                $rules['_class_fee_plan_id'.($idx+1)] = 'required';
//                $messages['_class_fee_plan_id'.($idx+1). '.required'] = 'required_select_tuition_fee_title,'.($idx+1);
//            }
//            $validator = Validator::make(request()->all(), $rules, $messages);
//
//            if ($validator->fails()) {
//                return redirect()->back()->withInput()->withErrors($validator->errors());
//            }
//		}
		$student_class_tbl = StudentClassTable::getInstance();
        $class_payment_tbl = ClassPaymentScheduleTable::getInstance();
		DB::beginTransaction();

		try {

			foreach ($request->students as $index => $student_id) {
                $student_class_rec = $student_class_tbl->getActiveRow(array('class_id'=>$request->id, 'student_id'=>$student_id));
                $parent_payment_method = StudentTable::getInstance()->getParentPaymentMethod($student_id);
				if ($request->mode == 1) {
                    //追加モードのときは、追加する。
                    $save = array(
//						'plan_id' 		    => request('_class_fee_plan_id'.($index+1)) ,
                        'plan_id'           => $request->plan_id,
                        'payment_method'    => $parent_payment_method['invoice_type'],
                        'number_of_payment' => $request->number_of_payment,
                        'notices_mail_flag' => $request->has('notices_mail_flag'),
					);
                    if (empty($student_class_rec)) {
                        $save['class_id']       = $request->id;
                        $save['student_id']     = $student_id;
                        $save['pschool_id']     = session('school.login')['id'];
                        $save['active_flag']    = 1;
                        $save['start_date']    = date("Y-m-d");
                    } else {
                        $save['id'] = $student_class_rec['id'];
                    }
                    $student_class_id = $student_class_tbl->save($save);
                    //支払基準
                    if ($request->number_of_payment != 99) { // 毎月以外
                        // todo get existed schedule list
                        $schedule_list = array();
                        $class_payment_list = $class_payment_tbl->getActiveList(array('student_class_id'=>$student_class_id));
                        foreach ($class_payment_list as $row) {
                            // $schedule_list: Ex: [1回=>[...], 2回=>[...], 3回=>[...],...]
                            $schedule_list[$row['payment_times_no']] = $row;
                        }

                        foreach ($request->payment_date as $times=>$date) {
                            $new_item = array(
                                'schedule_date' => $date,
                                'schedule_fee' => array_get($request->payment_fee, $times),
                            );
                            if (array_key_exists($times, $schedule_list)) { // 編集
                                $new_item['id'] = $schedule_list[$times]['id'];
                                unset($schedule_list[$times]);
                            } else { // 追加
                                $new_item['student_class_id'] = $student_class_id;
                                $new_item['payment_times_no'] = $times;
                            }
                            $class_payment_tbl->save($new_item);
                        }

                        //todo delete unselect item
                        foreach ($schedule_list as $unselect) {
                            $class_payment_tbl->logicRemove($unselect['id']);
                        }
                    } else if (!empty($student_class_rec)) { // delete existed list
                        $class_payment_tbl->logicalRemoveByCondition(array('student_class_id'=>$student_class_rec['id']));
                    }
				} else {
					//削除モードのときは、削除する。
					$student_class_tbl->logicRemove($student_class_rec['id']);
                    // clear table schedule
                    $class_payment_tbl->logicalRemoveByCondition(array('student_class_id'=>$student_class_rec['id']));
				}
			}
            DB::commit();
		} catch (Exception $ex) {
            DB::rollBack();
			$this->_logger->error($ex->getMessage());
		}

		return redirect()->to('/school/class');
	}

    public function studentEdit(Request $request) {
        // recover request ['id', 'message_success'] from old_input
        $this->recoverWithInput($request, array('id'));

        $message_success = '';
        if (session()->has('message_success')) {
            $message_success = session()->get('message_success');
            session()->forget('message_success');
        }

        // case: click previous_button
        if ( $request->has('prev_id')) {
            $request->offsetSet('id', $request->prev_id);
            $request->offsetUnset('prev_id');
        }
        // case: click next_button
        if ( $request->has('next_id')) {
            $request->offsetSet('id', $request->next_id);
            $request->offsetUnset('next_id');

        }
        if ( !$request->has('id')) {
            return redirect()->to('/school/class');
        }

        //Check prev - next request
        $prevNextStudentIds = session('prev_next_class_student_ids');
        $keyOfCurrentStudent = array_search($request->id, $prevNextStudentIds);
        if (isset($prevNextStudentIds[$keyOfCurrentStudent + 1])) {
            $request->offsetSet('next_id', $prevNextStudentIds[$keyOfCurrentStudent + 1]);
        }
        if (isset($prevNextStudentIds[$keyOfCurrentStudent - 1])) {
            $request->offsetSet('prev_id', $prevNextStudentIds[$keyOfCurrentStudent - 1]);
        }

        //プラン情報を割り当てる
        $student_class_id   = $request->id;
        $student_class      = StudentClassTable::find($student_class_id);
        $class_payment = ClassPaymentScheduleTable::getInstance()->getActiveList(
            array( 'student_class_id' => $student_class_id ),
            array( 'payment_times_no' => 'ASC' )
        );
        // reindex array from 1
        $class_payment_schedule = array();
        foreach ($class_payment as $row) {
//            $row['schedule_date'] = date('Y-') . $row['schedule_date'];
            $class_payment_schedule[$row['payment_times_no']] = $row;
        }

        $student_id = $student_class->student_id;
        $class_id   = $student_class->class_id;
        $class_info     = ClassTable::getInstance()->load($class_id);
        $student_info   = StudentTable::getInstance()->load($student_id);
        $student_type   = StudentTable::find($student_id)->studentType;
        $student_info['student_type_name'] = $student_type['name'];

        // プラン受講料
        $pschool_ids[]          = session('school.login')['id'];
        $class_fee_plan_list    = ClassFeePlanTable::getInstance()->getFees($class_id, $pschool_ids);
        $class_fee_plan         = array();
        foreach ($class_fee_plan_list as $idx => $row) {
            // $class_fee_plan: Ex: [1 => "A01 | 10,000（円）", 2 => "A02 | 20,000（円）", ...]
            $class_fee_plan[$row['id']]['value'] = $row['fee_plan_name'].' | '.number_format(floor($row['fee'])).'（円）';
            $class_fee_plan[$row['id']]['student_type_id'] = $row['student_type_id'];
        }

        // 支払方法
        $payment_methods = array();
        if (!is_null($class_info['payment_method'])) {
            $payment_methods = $this->getClassPaymentMethod($class_info['payment_method']);
        }

        if($student_class){
            $student_info['start_date'] =   $student_class->start_date ;
            $student_info['end_date'] =   $student_class->end_date ;
        }
        //支払回数
        $month_list = ConstantsModel::$payment_times_list[$this->current_lang];

        // get list payment of class
        // if parent's method is not KOZA => remove KOZA from list selection
        $invoice_types= Constants::$invoice_type[session()->get('school.login.language')];
        $payment_method = array();

        if( !empty($student_class['payment_method']) && !in_array($student_class['payment_method'],$payment_methods)){
            $payment_method[] = array(
                    'id' => $student_class['payment_method'],
                    'name' => $invoice_types[$student_class['payment_method']],
                    'disabled' => true,
            ) ;
        }

        if(!empty($payment_methods)){
            foreach($payment_methods as $k => $payment){
                if($payment == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $student_class['payment_method'] != Constants::$PAYMENT_TYPE['TRAN_RICOH']){
                    unset($payment_methods[$k]);
                }else{
                    $payment_method[] = array(
                            'id' => $payment,
                            'name' => $invoice_types[$payment]
                    ) ;
                }
            }
            $request->offsetSet('show_payment_list', true);
        }
        // view
        return view('School.Class.student_edit', compact('request', 'lan', 'class_info', 'student_info', 'class_fee_plan', 'class_fee_plan_list', 'month_list', 'payment_method', 'student_class', 'class_payment_schedule', 'message_success'));
    }
    /**
     * Store each student ( Edit payment schedule)
     * @param Request $request
     */
	public function studentStore(Request $request) {
        if (!$request->has('id')) {
            return;
        }

        // validate payment required
        $messages = ['payment_method.required' => "payment_method_required"];
        $rules = [
                'payment_method' => 'required'
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);

        //
        $class_payment_tbl = ClassPaymentScheduleTable::getInstance();
        $student_class_tbl = StudentClassTable::getInstance();

        //
        $class_payment =  $student_class_tbl->load($request->id);
        $class = ClassTable::getInstance()->load($class_payment['class_id']);
        $class_method = explode(",",$class['payment_method']);
        foreach ($class_method as $k => $method){
            $class_method[$k] = Constants::$PAYMENT_TYPE[$method];
        }
        if(!in_array($request->payment_method,$class_method)){
            $request->offsetSet("errors", "payment_method_required");
            return $this->studentEdit($request);
        }

        //
        if($validator->fails()){
            return $this->studentEdit($request);
        }

        DB::beginTransaction();

        try {
            $student_class_id = $request->id;

            // update student_class record
            $student_class = array(
                'id'                => $student_class_id,
                'plan_id'           => $request->plan_id,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'payment_method'    => $request->payment_method,
                'number_of_payment' => $request->number_of_payment,
                'notices_mail_flag' => $request->has('notices_mail_flag'),
            );
            $student_class_tbl->save($student_class);

            //支払基準
            if ($request->number_of_payment != 99) { // 毎月以外
                // todo get existed schedule list
                $schedule_list = array();
                $class_payment_list = $class_payment_tbl->getActiveList(array('student_class_id'=>$student_class_id));
                foreach ($class_payment_list as $row) {
                    // $schedule_list: Ex: [1回=>[...], 2回=>[...], 3回=>[...],...]
                    $schedule_list[$row['payment_times_no']] = $row;
                }

                foreach ($request->payment_date as $times=>$date) {
                    $new_item = array(
                        'schedule_date' => $date,
                        'schedule_fee' => array_get($request->payment_fee, $times),
                    );
                    if (array_key_exists($times, $schedule_list)) { // 編集
                        $new_item['id'] = $schedule_list[$times]['id'];
                        unset($schedule_list[$times]);
                    } else { // 追加
                        $new_item['student_class_id'] = $student_class_id;
                        $new_item['payment_times_no'] = $times;
                    }
                    $class_payment_tbl->save($new_item);
                }

                //todo delete unselect item
                foreach ($schedule_list as $unselect) {
                    $class_payment_tbl->logicRemove($unselect['id']);
                }
            } else { // delete existed list
                $class_payment_tbl->logicalRemoveByCondition(array('student_class_id'=>$student_class_id));
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            $this->_logger->error($ex->getMessage());
        }
        session()->put('message_success' , 'save_success_title');

        // todo return view
        return redirect()->route('class_student_edit')->withInput();
    }

	private function get_validate_rules($request) {
		$rules = [  'class_name' => 'required|max:255',
//            'payment_method' => 'required',
            '_class_fee' => 'required',
            'payment_methods' => 'required'
        ];


		if (count($request->_class_fee) > 0) {

            $rules['_class_fee.*.fee_plan_name']    = 'required|max:255';
            $rules['_class_fee.*.student_type_id']  = 'required';
//            $rules['_class_fee.*.attend_times_div'] = 'required';

            $rules['_class_fee.*.fee']              = 'required|numeric|max:9999999999';
            foreach ($request->_class_fee as $key => $value) {

                if(isset($value['attend_times_div']) && ( $value['attend_times_div'] == '1' || $value['attend_times_div'] == '2')) {
                $rules['_class_fee.'.$key.'.attend_times'] = 'required|numeric|max:9999999999';
                }
			}
		}
		
		if ($request->has('payment')) {

            $rules['payment.*.payment_month']   = 'required';
            $rules['payment.*.payment_adjust']  = 'required';
            $rules['payment.*.payment_fee']     = 'required|numeric';

            foreach ($request->payment as $key => $value) {
                foreach ($request->payment as $key2 => $value2) {
                    if ($key2 > $key) { // prevent duplicate
                        // $compare_arr Ex: ['payment_month'=>99, 'payment_adjust'=>1,...]
                        $compare_arr = array_intersect($value, $value2);
                        if (array_key_exists('payment_month', $compare_arr) &&
                            array_key_exists('payment_adjust', $compare_arr)) {
                            $rules['payment.'.$key.'.duplicate']     = 'required';
                        }
                    }
                }
            }

		}


		return $rules;
	}
	private function get_validate_message($request) {
		$messsages = array('class_name.required' => 'required_class_name_error_title', // TODO get msg from resource files
		 	'class_name.max' => 'length_class_name_error_title',
		 	'_class_fee.required' => 'tuition_type_required_title',
            'payment_methods.required' => 'payment_method_required_title',
		 	
		);

		if (count($request->_class_fee) > 0) {
			foreach ($request->_class_fee as $key => $value) {
			    $messsages['_class_fee.'.$key.'.fee_plan_name.required'] = 'required_tuition_name_error_title,' .($key+1);
                $messsages['_class_fee.'.$key.'.fee_plan_name.max'] = 'length_tuition_name_error_title,' .($key+1);
                $messsages['_class_fee.'.$key.'.student_type_id.required'] = 'student_type_id_required_title,' .($key+1);
//                $messsages['_class_fee.'.$key.'.attend_times_div.required'] = 'number_attempt_not_selected,' .($key+1);
                $messsages['_class_fee.'.$key.'.attend_times.required'] = 'required_unit_lecture_error_title,' .($key+1);
                $messsages['_class_fee.'.$key.'.attend_times.numeric'] = 'format_unit_lecture_error_title,' .($key+1);
                $messsages['_class_fee.'.$key.'.attend_times.max'] = 'length_unit_lecture_error_title,' .($key+1);
                $messsages['_class_fee.'.$key.'.fee.required'] = 'required_tuition_fee_error_title,' .($key+1);
                $messsages['_class_fee.'.$key.'.fee.numeric'] = 'format_tuition_fee_error_title,' .($key+1);
                $messsages['_class_fee.'.$key.'.fee.max'] = 'length_tuition_fee_error_title,' .($key+1);

		}
		}

		if (count($request->payment) > 0) {
			foreach ($request->payment as $key => $value) {
			$messsages['payment.'.$key.'.payment_month.required'] = 'required_target_month_error_title,' .($key+1);
			$messsages['payment.'.$key.'.payment_adjust.required'] = 'required_payment_error_title,' .($key+1);
			$messsages['payment.'.$key.'.payment_fee.required'] = 'required_amout_money_error_title,' .($key+1);
		 	$messsages['payment.'.$key.'.payment_fee.numeric'] = 'format_amout_money_error_title,' .($key+1);
		 	$messsages['payment.'.$key.'.duplicate.required'] = 'duplicated_targer_month_payment_error_title,' .($key+1);
		}
		}

		return $messsages;
	}

	private function getClassPaymentMethod($class_payment_method) {
        $payment_ids = explode(',', $class_payment_method);
//        $payment_list = DB::table('payment_method')->whereIn('code', $payment_ids)->get();
        $payment_list = DB::table('payment_method')->whereIn('code', $payment_ids)->orderBy('sort_no', 'ASC')->get();
        $payment_method = array_map ( function ($object) {
            return $object->id;
        }, $payment_list->toArray() );
        return $payment_method;
    }
}
