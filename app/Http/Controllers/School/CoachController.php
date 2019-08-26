<?php

namespace App\Http\Controllers\School;

use App\Common\UploadFileHandler;
use App\Model\LoginAccountTempTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\School\_BaseSchoolController;
use App\ConstantsModel;
use App\Model\MPrefTable;
use App\Model\MCityTable;
use App\Model\MTagSubjectTable;
use App\Model\MSubjectTemplateTable;
use App\Model\TeacherTable;
use App\Model\TeacherSubjectTable;
use App\Model\LoginAccountTable;
use App\Model\SchoolMenuTable;
use App\Model\SSubjectTable;
use App\Model\SCourseTable;
use App\Model\CoachTable;
use App\Model\ClassTable;
use App\Model\CourseTable;
use App\Model\ProgramTable;
use App\Model\LessonTable;
use App\Model\ClassCoachTable;
use App\Model\CourseCoachTable;
use App\Model\LessonCoachTable;
use App\Model\PschoolTable;
use App\Http\Controllers\Common\ValidateRequest;
use App\Http\Controllers\Common\Validaters;
use App\Lang;
use Illuminate\Support\Facades\Log;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CoachController extends _BaseSchoolController
{
    protected static $LANG_URL = 'coach';
    private $_coach_search_item = ['coach_name'];
    private $_coach_search_session_key = 'coach_search_form';

    /**
     * CoachController constructor.
     * @param Request $request
     */
    public function __construct(Request $request) {
        parent::__construct ();
        Log::info("debuginfo: " . session('school.login.id'));
        // 多国語対応
        $message_content = parent::getMessageLocale();
        $this->lan  = new Lang ( $message_content );
    }

    /**
     * 一覧画面の処理
     *
     * @param  Request $request
     * @return view
     */
    public function index(Request $request) {
        $this->_initSearchDataFromSession($this->_coach_search_item, $this->_coach_search_session_key);

        // TODO clear oldinput session
        $this->clearOldInputSession();

        // TODO get list coach of current school
        $coach_table = CoachTable::getInstance();
        $coach_list = $coach_table->getActiveCoachList($request->coach_name);

        //Process for prev - next in detail page
        $prev_next_student_ids = [];
        foreach ($coach_list as $coach) {
            $prev_next_student_ids[] = $coach['id'];
        }
        session()->put('prev_next_student_ids', $prev_next_student_ids);

        // TODO push list of coaches and related subjects to view
        $lan = $this->lan;
        return view('School.Coach.index', compact('coach_list', 'lan'));
    }

    /**
     * 登録画面の処理
     *
     * @param  Request $request
     * @return view
     */
    public function entry(Request $request) {
        $lan = $this->lan;

        // Clear errors from session. Fix session flash to remove this step.
        if(session()->has('errors')) {
            session()->forget('errors');
        }

        // TODO Load master data and then render HTML
        // get m_pref_data
        $pref_data = MPrefTable::getInstance()->get();

        // get m_city_data
        $address1_city_data = $address2_city_data = MCityTable::getInstance()->get();

        if($request->has('address1_pref_id')) {
            $address1_city_data = MCityTable::getInstance()->where(['pref_id' => $request->address1_pref_id])->get();
        }

        if($request->has('address2_pref_id')) {
            $address2_city_data = MCityTable::getInstance()->where(['pref_id' => $request->address2_pref_id])->get();
        }

        if($request->has('id')) {   // In case of Edit
            // Get coach by id
            // id from request. It means the coach is active
            $coach_data = CoachTable::getInstance()->find($request->id)->toArray();

            // Get login infos
            if(is_array($coach_data) && $coach_data['mail_address']) {
                $login_info = LoginAccountTable::getInstance()
                    ->where(['login_id' => $coach_data['mail_address'], 'pschool_id' => session('school.login')['id']])
                    ->first();

                $login_info_temp = LoginAccountTempTable::getInstance()
                    ->where(['login_id' => $login_info->login_id])
                    ->first();

                $request->offsetSet('coach_mail', $login_info->login_id);
//                $request->offsetSet('coach_pass1', $login_info->login_pw);
//                $request->offsetSet('coach_pass2', $login_info->login_pw);
//                $request->offsetSet('old_pw', $login_info->login_pw);
                $request->offsetSet('login_info_id', $login_info->id);
                $request->offsetSet('login_info_temp_id', $login_info_temp->id);

                // TODO load menu setting data
                $menu_setting_arr = $this->getMenuData($request->id, 3); // member_type = 3: Coach
            }

            // Merge coach_data to request
            $request->merge($coach_data);
        } else {
            // TODO load menu setting data
            $menu_setting_arr = $this->getMenuData();
        }

        $request->merge($menu_setting_arr);

        // TODO return to template file (entry.blade.php)
        return view('School.Coach.entry')
            ->with('request',$request)
            ->with( 'lan', $lan)
            ->with( 'pref_data', $pref_data)
            ->with( 'address1_city_data', $address1_city_data)
            ->with( 'address2_city_data', $address2_city_data);

    }

    /**
     * 登録画面の処理。DBに保存する
     *
     * @param  Request $request
     * @return view
     */
    public function store(Request $request) {

        // TODO use Laravel's validator to validate the request
        $validator = $this->_validateRequest($request);
        if ($validator->fails()) {
            $errors = array_unique($validator->errors()->all());

            $request->offsetSet('errors',$errors);
            return $this->entry($request)->withErrors($errors);
        }

        // TODO put all db actions into transaction
        DB::beginTransaction();
        try {
            // TODO save data to login_account and login_account_temp
            $login_account_table = LoginAccountTable::getInstance();
            $login_account_data = array();
            if($request->has('login_info_id')) { // edit
                $login_account_data['id'] = $request->login_info_id;
            }
            $login_account_data['login_id'] = $request->coach_mail;
            $login_account_data['active_flag'] = 1; // Default active, should change db default value
            $login_account_data['register_admin'] = session('login_account_id');
            $login_account_data['lang_code'] = session('school.login.lang_code');
            $login_account_data['pschool_id'] = session('school.login.id');
            $login_account_data['auth_type'] = 5; // 5=講師
            if ($request->has('coach_pass1')) {
                $login_account_data['login_pw'] = md5($request->coach_pass1);
            }
            $login_id = $login_account_table->save($login_account_data);

            // Save infos into login account temp (password was encrypted in base64)
            $login_account_tmp_table = LoginAccountTempTable::getInstance();
            $login_account_tmp_data = array();
            if($request->has('login_info_temp_id')) { // edit
                $login_account_tmp_data['id'] = $request->login_info_temp_id;
            }
            $login_account_tmp_data['login_id'] = $request->coach_mail;
            $login_account_tmp_data['login_pw_base64'] = base64_encode($request->coach_pass1);
            $login_account_tmp_data['login_account_id'] = $login_id;
            $login_account_tmp_table->save($login_account_tmp_data);

            // TODO save teacher's data to coach
            $coach_table = CoachTable::getInstance();
            $coach_data = array();
            $coach_data = $request->all(); // Get all input and save

            if($request->has('id')) { // Edit coach
                $coach_data['id'] = $request->id;
            }
            // Handle profile image upload
            $storage_path = storage_path('app/uploads/school/' . session('school.login.id'));
            $file = UploadFileHandler::getInstance()->uploadImage($request->file('profile'), $storage_path);

            if(!empty($file->getUploadStatus()) && $file->getUploadStatus()) {
                $coach_data['profile_img'] = $file->getFileName();
            } else if (!empty($file->getUploadStatus()) && !$file->getUploadStatus()) {
                // TODO return to entry page with errors
                return $this->entry($request)->withErrors($file->getErrors());
            }

            $coach_data['mail_address'] = $request->coach_mail;
            $coach_data['pschool_id'] = session('school.login.id');
            $coach_data['login_account_id'] = $login_id;
            $coach_data['active_flag'] = 1; // Default active
            if (request()->has('address1_zip1') && request()->has('address1_zip2')) {
                $coach_data['address1_zip'] = $request->address1_zip1 . $request->address1_zip2;
            }

            if (request()->has('address2_zip1') && request()->has('address2_zip2')) {
                $coach_data['address2_zip'] = $request->address2_zip1 . $request->address2_zip2;
            }
            $coach_id = $coach_table->save($coach_data);

            // TODO save menu settings
            $school_menu_table = SchoolMenuTable::getInstance ();
            $menu_select = $school_menu_table->getActiveMenuListNew($request->id,3); // member_type=3: coach

            $index = 1;
            if ($request->menu_list) {

                foreach ( $request->menu_list as $key => $value ) {
                    $menu = array (
                        "pschool_id" => $coach_id, // Coach id
                        "member_type" => 3, // Coach
                        "master_menu_id" => $key,
//                        "viewable" => isset($request->viewable_list[$key]) ? 1 : 0,
                        "viewable" => 1,
                        "editable" => isset($request->editable_list[$key]) ? 1 : 0,
                        "seq_no" => $index,
                        "active_flag" => 1,
                        "register_admin" => 1
                    );
                    // case update
                    if (isset ( $menu_select [$key] )) {
                        $menu ['id'] = $menu_select [$key] ['id'];
                        unset ( $menu_select [$key] );
                    }

                    $index ++;
                    $school_menu_table->save ( $menu );
                }
                if (count ( $menu_select ) > 0) {
                    foreach ( $menu_select as $key => $value ) {
                        $school_menu_table->logicRemove ( $value ['id'] );
                    }
                }
            }

            DB::commit();
            if($request->function == 1) { // 登録
                $this->clearOldInputSession();
                session()->put('messages', "register_success");
                return redirect('/school/coach/entry');
            } elseif($request->function == 2) { // 編集
                //session()->put('messages', "edit_success");
                return $this->detail($request);
            }
            return redirect('/school/coach/index');
        }catch (\Exception $e) {
            // TODO log errors, will be implement later
            // TODO rollback transaction
            DB::rollBack();
            dd($e);
        }

        // TODO return back to entry page
    }

    /**
     * 詳細画面
     *
     * @param  Request $request
     * @return view
     */
    public function detail() {
        $request = app('request');
//        dd($request);
        if($request->has('id')) {
            //Check prev - next request
            $prev_next_student_ids = session('prev_next_student_ids');
            $key_of_current_student = array_search($request->id, $prev_next_student_ids);
            if (isset($prev_next_student_ids[$key_of_current_student + 1])) {
                $request->offsetSet('next_id', $prev_next_student_ids[$key_of_current_student + 1]);
            }
            if (isset($prev_next_student_ids[$key_of_current_student - 1])) {
                $request->offsetSet('prev_id', $prev_next_student_ids[$key_of_current_student - 1]);
            }

            //TODO get coach infos by id then pass to view
            $coach_data = CoachTable::getInstance()->find($request->id)->toArray();
            $request->merge($coach_data);
            // TODO load menu setting data
            $menu_setting_arr = $this->getMenuData($request->id, 3); // member_type = 3: Coach

            // TODO get prefecture name, city name
            $address1_pref_name = "";
            $address1_city_name = "";
            $address2_pref_name = "";
            $address2_city_name = "";

            // Address1
            if($request->has('address1_pref_id')){
                $m_pref = MPrefTable::getInstance()->find($request->address1_pref_id);
                if(!is_null($m_pref)) {
                    $address1_pref_name = $m_pref->name;
                }
            }
            if($request->has('address1_city_id')){
                $m_city = MCityTable::getInstance()->find($request->address1_city_id);
                if(!is_null($m_city)) {
                    $address1_city_name = $m_city->name;
                }
            }

            // Address2
            if($request->has('address2_pref_id')){
                $m_pref = MPrefTable::getInstance()->find($request->address2_pref_id);
                if(!is_null($m_pref)) {
                    $address2_pref_name = $m_pref->name;
                }
            }
            if($request->has('address2_city_id')){
                $m_city = MCityTable::getInstance()->find($request->address2_city_id);
                if(!is_null($m_city)) {
                    $address2_city_name = $m_city->name;
                }
            }
            $request->merge($menu_setting_arr);
            // TODO return to detail page
            return view('School.Coach.detail')

                ->with( 'address1_pref_name', $address1_pref_name )
                ->with( 'address1_city_name', $address1_city_name )
                ->with( 'address2_pref_name', $address2_pref_name )
                ->with( 'address2_city_name', $address2_city_name );

        } else {
            // Return back to list page
            return redirect('school/coach');
        }
    }

    /**
     * Validate the request
     *
     * @param  Request $request
     * @return Validator\
     */
    private function _validateRequest(Request $request) {
        // Validator rules

        $rules = [
            "coach_name" => "required|max:255",
            "coach_name_kana" => array('required','max:255','regex:/^[ア-ン゛゜ァ-ォャ-ョー「」、\　\ ]+$/u'),
            "coach_mail" => "required|email|max:64|unique:login_account,login_id,".$request->login_info_id.",id,delete_date,NULL,pschool_id,".session('school.login')['id']."",
        ];

        // Errors messages
        $messages = [
            "coach_name.required" => "required_name_msg", // get message from message file
            "coach_name.max" => "over_length_name_msg",
            "coach_name_kana.required" => "required_furigana_msg",
            "coach_name_kana.max" => "over_length_furigana_msg",
            "coach_name_kana.regex" => "furigana_format_msg",
            "coach_mail.required" => "required_mail_msg",
            "coach_mail.email" => "invalid_email_format_msg",
            "coach_mail.max" => "over_length_mail_msg",
            "coach_mail.unique" => "existed_mail_msg",
            "coach_pass1.required" => "required_password_msg",
            "coach_pass1.min" => "under_length_password_msg",
            "coach_pass1.max" => "over_length_password_msg",
            "coach_pass1.regex" => "password_regex_msg",
            "coach_pass2.same" => "confirmed_password_msg",
        ];

        // case register
        if(!$request->has('id')){
            $rules['coach_pass1'] = array('required', 'min:8', 'max:16', 'regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/');
            $rules['coach_pass2'] = "same:coach_pass1";
        // case edit
        } else if ( $request->has('id') && $request->has('coach_pass1')) {
            $rules['coach_pass1'] = array('min:8', 'max:16', 'regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/');
            $rules['coach_pass2'] = "same:coach_pass1";
        }

        // If the input contains address1_zip1 or address1_zip2 -> validate
        if(request()->has('address1_zip1') || request()->has('address1_zip2')) {
            $rules['address1_zip1'] = "digits:3";
            $rules['address1_zip2'] = "digits:4";

            $messages['address1_zip1.digits'] = "postal_code_1_length_msg";
            $messages['address1_zip2.digits'] = "postal_code_1_length_msg";
        }

        // If the input contains address1_zip1 or address1_zip2 -> validate
        if(request()->has('address2_zip1') || request()->has('address2_zip2')) {
            $rules['address2_zip1'] = "digits:3";
            $rules['address2_zip2'] = "digits:4";

            $messages['address2_zip1.digits'] = "postal_code_2_length_msg";
            $messages['address2_zip2.digits'] = "postal_code_2_length_msg";
        }

        if (request()->has('address1_phone')) {
            $rules['address1_phone'] = "regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/u";
            $messages['address1_phone.regex'] = "phone_1_format_msg";
        }

        if (request()->has('address2_phone')) {
            $rules['address2_phone'] = "regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/u";
            $messages['address2_phone.regex'] = "phone_2_format_msg";
        }

        if (request()->has('mobile_no')) {
            $rules['mobile_no'] = "regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/u";
            $messages['mobile_no.regex'] = "mobile_format_msg";
        }

        return $validator = Validator::make(request()->all(), $rules, $messages);
    }

    /**
     * AJAXでこの関数を呼び出す。都道府県を選択する時該当する市区町村を取得する
     *
     * @param  String $id 都道府県ID
     * @return 市区町村
     */
    function getCityDataByPrefId(Request $request) {
        // TODO get city data by prefid
        $city_list = MCityTable::getInstance()->where(['pref_id' => $request->pref_id])->get();

        // return city data
        $city_data = array();
        foreach($city_list as $item) {
            $city_data[$item->id] = $item->name;
        }
        return $city_data;
    }
}