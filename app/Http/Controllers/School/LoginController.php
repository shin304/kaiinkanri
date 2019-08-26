<?php

namespace App\Http\Controllers\School;

use App\Common\Constants;
use App\Lang;
use App\MessageFile;
use App\Model\ParentTable;
use App\Model\StudentTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PschoolTable;
use App\Model\StaffTable;
use App\Model\CoachTable;
use App\Model\LoginAccountTable;
use App\Model\HierarchyTable;
use App\ConstantsModel;
use Validator;
use DB;
use Illuminate\Support\Facades\Mail;
use Log;
use App\Common\Email;
use DateTime;
use Illuminate\Support\Facades\Redirect;

class LoginController extends _BaseSchoolController {
    use \App\Common\Email;
    private static $TOP_URL = 'home?menu';
    private static $ONE_HOUR = 60 * 60;/* 一時間 */
    
    private $request;
    public function __construct(Request $request) {
        parent::__construct ();
        $this->request = $request;
    }

    public function inputEmailChangePassword() {
        return view ( 'School.Login.input_email' );
    }

    public function passwordChange(Request $request) {
        $email = $request->valid_email;
        $login_acc = LoginAccountTable::getInstance ()->getSchoolByLoginID($request->valid_email);
        return view ( 'School.Login.password-reminder' )->with ('login_acc',$login_acc)->with ('email', $email);        
    }

    public function confirmCode(Request $request) {
        // $ONE_HOUR = 60 * 60;/* 一時間 */
        $account = LoginAccountTable::getInstance ()->checkLoginAcountByID($request->id);
        $current_date = new DateTime( date('Y-m-d H:i:s') );
        $time_code = new DateTime( date('Y-m-d H:i:s', strtotime($account->time_of_code) ) );
        $interval = $current_date->diff($time_code);
        // 時間のチェック
        $time_diff = ($interval->h)*60*60 + ($interval->i)*60 + ($interval->s);
        $email = $account->login_id;
        $id = $request->id;
        $request->offsetSet('time', $time_diff - self::$ONE_HOUR);
        $request->offsetSet('code', $account->code);
        return view ( 'School.Login.confirm_code', compact('id', 'email', 'request', 'time', 'code') );
    }

    public function index() {
        if (session()->has('errors')) {
            session()->forget('errors');
        }
        $pschoolList = array ();
        return view ( 'School.Login.index' );//->with ('pschoolList', $pschoolList);
    }

    public function login() {
        // エラー表示用の配列
        $rules = [ 
                'loginid' => 'required',
                'password' => 'required' 
        ];
        $messsages = array (
                'loginid.required' => 'ユーザー名またはパスワードが指定されていません。', // TODO get msg from resource files
                'password.required' => 'ユーザー名またはパスワードが指定されていません。' 
        );

        $validator = Validator::make ( request ()->all (), $rules, $messsages );
        
        if ($validator->fails ()) {
            return redirect ()->back ()->withInput ()->withErrors ( $validator->errors () );
        }

        $pschoolList = LoginAccountTable::getInstance ()->getPschoolForLogin($_REQUEST ['loginid'], $_REQUEST ['password']);

        if (count($pschoolList) > 1 && ($_REQUEST ['pschool_id'] == "" || $_REQUEST ['pschool_id'] == NULL)) {
            //Session::flash('message', 'エラーが発生された。');
            session()->flash('message', 'エラーが発生された。');
            return redirect ( '/school' );
        } else {
            if ($admin = $this->briard_validate_school ()) {
                return redirect ( '/school/home' );
            }
        }

        // ログイン失敗
        $validator->after ( function ($validator) {
            $validator->errors ()->add ( 'message', 'ユーザー名またはパスワードが間違っています。' );
        } );
        
        if ($validator->fails ()) {
            return redirect ()->back ()->withInput ()->withErrors ( $validator->errors () );
        }
    }

    public function ajaxGetPschool(Request $request) {

        try {
            if ($request->ajax()) {
                $loginid = $request->loginid;
                $password = $request->password;

                $pschoolList = LoginAccountTable::getInstance()->getPschoolForLogin($loginid, $password);
                return $pschoolList;
            }

        }catch (Exception $e){
            throw new Exception();
        }
    }
    
    /**
     * PSchoolテーブルで認証する
     *
     * @return unknown_type
     */
    private function validate_school() {
        
        // 該当のデータがテーブルに存在するか調べる。
        $school = PschoolTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'] );
        
        if ($school == null) {
            $school = StaffTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'] );
            if ($school == null) {
                return false;
            } else {
                $school ['staff_id'] = $school ['id'];
                $school ['id'] = $school ['pschool_id'];
                $school ['name'] = $school ['staff_name'];
            }
        }
        
        // 多言語情報を受ける
        // Ex: $lang_code : 1:英語,2:日本語,...
        // Ex: $resource_file : school,sport,...
        $lang_code = DB::table ( 'login_account' )->where ( 'login_id', $school ['login_id'] )->first ()->lang_code;
        if (! empty ( $school ['message_file'] )) {
            $resource_file = $school ['message_file'];
        } else {
            $resource_file = 'common'; // set default
            
            $business_type = DB::table ( 'business_type' )->where ( 'id', $school ['business_type_id'] )->first ();
            if (! empty ( $business_type ) && ! is_null ( $business_type->resource_file )) {
                $resource_file = $business_type->resource_file;
            }
        }
        
        $school ['lang_code'] = $lang_code;
        $school ['resource_file'] = $resource_file;
        
        // ログイン情報をセッションに保存
        session ( [ 
                $this->get_app_id () . '.login' => $school 
        ] );
        $login_session_name = $this->get_app_login_session_var ();
        session ( [ 
                $login_session_name => $school 
        ] );
        
        $this->set_language ();
        
        return $school;
    }
    
    /**
     * アクシス柔術　‐　PSchoolテーブルで認証する
     *
     * @return unknown_type
     */
    private function briard_validate_school() {

        // 該当のデータがテーブルに存在するか調べる。
        $school = null;
        if ($_REQUEST ['pschool_id'] !== "" || $_REQUEST ['pschool_id'] !== null) {
            $school = PschoolTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'], $_REQUEST ['pschool_id'] );
        } else {
            $school = PschoolTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'] );
        }

        $hierarchy_session = array ();
        if ($school != null) { // Admin
            $hierarchy_session ['role'] = ConstantsModel::$roles ['DANTAI'];
            $hierarchy_session['type'] = Constants::LOGIN_TYPE_SCHOOL;
            $school['origin_id'] = $school['id'];
        } else {
            if ($_REQUEST ['pschool_id'] !== "" || $_REQUEST ['pschool_id'] !== null) {
                $school = StaffTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'], $_REQUEST ['pschool_id'] );
            } else {
                $school = StaffTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'] );
            }
            if ($school != null) { // Staff
                $hierarchy_session ['role'] = ConstantsModel::$roles ['STAFF'];
                $hierarchy_session['type'] = Constants::LOGIN_TYPE_SCHOOL;
                $school ['staff_id'] = $school ['id'];
                $school ['id'] = $school ['pschool_id'];
                $school ['name'] = $school ['staff_name'];
                $school['origin_id'] = $school['staff_id'];
            } else {
                if ($_REQUEST ['pschool_id'] !== "" || $_REQUEST ['pschool_id'] !== null) {
                    $school = CoachTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'], $_REQUEST ['pschool_id'] );
                } else {
                    $school = CoachTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'] );
                }

                if ($school != null) { // Coach
                    $hierarchy_session ['role'] = ConstantsModel::$roles ['COACH'];
                    $hierarchy_session['type'] = Constants::LOGIN_TYPE_SCHOOL;
                    $school ['coach_id'] = $school ['id'];
                    $school ['id'] = $school ['pschool_id'];
                    $school ['name'] = $school ['coach_name'];
                    $school['origin_id'] = $school['coach_id'];
                }else{
                    if ($_REQUEST ['pschool_id'] !== "" || $_REQUEST ['pschool_id'] !== null) {
                        $school = ParentTable::getInstance()->getLoginInfo( $_REQUEST ['loginid'], $_REQUEST ['password'], $_REQUEST ['pschool_id'] );
                    } else {
                        $school = ParentTable::getInstance()->getLoginInfo( $_REQUEST ['loginid'], $_REQUEST ['password'] );
                    }
                    if ($school != null) { // Parent
                        $hierarchy_session ['role'] = ConstantsModel::$roles ['PARENT'];
                        $hierarchy_session['type'] = Constants::LOGIN_TYPE_MEMBER;
                        $school ['parent_id'] = $school ['id'];
                        $school ['id'] = $school ['pschool_id'];
                        $school ['name'] = $school ['parent_name'];
                        $school['origin_id'] = $school['parent_id'];

                    }else{
                        if ($_REQUEST ['pschool_id'] !== "" || $_REQUEST ['pschool_id'] !== null) {
                            $school = StudentTable::getInstance()->getLoginInfo( $_REQUEST ['loginid'], $_REQUEST ['password'], $_REQUEST ['pschool_id'] );
                        } else {
                            $school = StudentTable::getInstance()->getLoginInfo( $_REQUEST ['loginid'], $_REQUEST ['password'] );
                        }

                        if ($school != null) { // Student
                            $hierarchy_session ['role'] = ConstantsModel::$roles ['STUDENT'];
                            $hierarchy_session['type'] = Constants::LOGIN_TYPE_MEMBER;
                            $school ['student_id'] = $school ['id'];
                            $school ['id'] = $school ['pschool_id'];
                            $school ['name'] = $school ['student_name'];
                            $school['origin_id'] = $school['student_id'];

                        }
                    }
                }
            }
        }

        if (empty ( $school )) {
            return;
        }

        // 多言語情報を受ける
        // Ex: $lang_code : 1:英語,2:日本語,...
        // Ex: $resource_file : school,sport,...
        $lang_code = DB::table ( 'login_account' )->where ( 'login_id', $school ['login_id'] )->first()->lang_code;

        if (isset($school ['message_file'] ) && !empty( $school ['message_file'] )) {
            $resource_file = $school ['message_file'];
        } else {
            $resource_file = 'common'; // set default

            // check business_type to load parent message_file
            if (isset($school ['business_type_id']) && !empty($school ['business_type_id'])) {
                
                // $message_file : check bussiness_type_id, lang_code & pschool_id = 0
                $message_file = DB::table ( 'message_file' )->where( 'bussiness_type_id', $school ['business_type_id'])->where('lang_code', $lang_code)->where('pschool_id', 0)->first();
                if (!empty($message_file)) {
                    $resource_file = $message_file->message_file_name;
                }
            }else{
                if(empty($lang_code) || !empty($school['language']) ){
                    $lang_code = $school['language'];
                }

                $message_file = DB::table ( 'message_file' )->where('lang_code', $lang_code)->where('pschool_id', $school['id'])->first();
                if (!empty($message_file)) {
                    $resource_file = $message_file->message_file_name;
                }
            }
        }
        
        $school ['lang_code'] = $lang_code;
        $school ['resource_file'] = $resource_file;
        
        // get hierachy
        $current_hierarchy = HierarchyTable::getInstance ()->getHierarchy ( $school ['id'] );
        $pschool_parents = HierarchyTable::getInstance ()->getParentPschoolIds ( $school ['id'] );
        $hierarchy_session ['hierarchy_id'] = $current_hierarchy ['id'];
        $hierarchy_session ['hierarchy_layer'] = $current_hierarchy ['layer'];
        $hierarchy_session ['hierarchy_manage_flag'] = $current_hierarchy ['manage_flag'];
        $hierarchy_session ['pschool_parents'] = $pschool_parents;
        
        // ログイン情報をセッションに保存

        session ( [ 
                $this->get_app_id () . '.login' => $school 
        ] );

        session ( [ 
                'hierarchy' => $hierarchy_session 
        ] );
        $this->set_language ();

        return $school;
    }

    public function storeNewPassword(Request $request) {
        // エラー表示用の配列
        $rules = [ 
                // 'valid_email' => 'required|email',
                'new_password' => 'required|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/',
                'confirm_new_password' => 'required|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/',
        ];
        $messages = array (
                // 'valid_email.required' => 'メールが指定されていません。',
                // 'valid_email.email' => 'メールが間違っています。', // TODO get msg from resource files
                'new_password.required' => 'パスワードは必須です。',
                'new_password.min' => 'パスワードを含めた8～16文字で入力してください。',
                'new_password.max' => 'パスワードを含めた8～16文字で入力してください。',
                'new_password.regex' => 'パスワードの形式に誤りがあります。',
                'confirm_new_password.required' => 'パスワードが指定されていません。',
                'confirm_new_password.min' => 'パスワードを含めた8～16文字で入力してください。',
                'confirm_new_password.max' => 'パスワードを含めた8～16文字で入力してください。',
                'confirm_new_password.regex' => 'パスワードの形式に誤りがあります。',
        );
        
        $validator = Validator::make ( request ()->all (), $rules, $messages );

        if ($validator->fails ()) {
            return redirect ()->back ()->withInput ()->withErrors ( $validator->errors () );
        } else {
            $loginAccountTable = LoginAccountTable::getInstance();
            $get_acc = $loginAccountTable->checkLoginAcountByLoginID($request->email, $request->pschool_id);
            if (!is_null($get_acc)){
                $password_temp = $request->new_password;
                $code = ($this->generateRandomString(6));
                $time_code = date ( 'Y-m-d H:i:s' );
                $get_acc->insertNewPasswordTemp($request->email, $request->pschool_id, $code, $time_code, $password_temp);
                // メールを送る
                $to_email = $request->email;
                $data = array();
                $name = $loginAccountTable->getName( $get_acc->id );
                $id = $get_acc->id;
                $is_sent = $this->sendMailPasswordReminder($data, $name, $code, $id, $to_email, false);

                if ($is_sent) {
                    $this->logSuccess("Send mail message");
                } else {
                    throw new \Exception("Send mail message fail ");
                }

                return view ('School.Login.success')->with('name', $name);
            } else {
                return redirect ('/password_reminder');
                // return view ( 'School.Login.password-reminder');
            }
        } 
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
        // dd ($result);
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

    public function checkEmail(Request $request)
    {
        $get_acc = LoginAccountTable::getInstance();
        $temp = $get_acc->checkEmailLogin($request->email);
        if (!is_null($temp)) {
          $response = 1;
        } else {
          $response = 0;
        }
        return response()->json($response);
    }

    public function storeConfirmCode(Request $request)
    {
        // $ONE_HOUR = 60 * 60;/* s */
        $account = LoginAccountTable::getInstance ()->checkLoginAcountByID($request->id);
        $current_date = new DateTime( date('Y-m-d H:i:s') );
        $time_code = new DateTime( date('Y-m-d H:i:s', strtotime($account->time_of_code) ) );
        $interval = $current_date->diff($time_code);
        $time_diff = ($interval->h)*60*60 + ($interval->i)*60 + ($interval->s);
        $request->offsetSet('time', $time_diff - self::$ONE_HOUR);

        if (($account->code == $request->valid_code) && ($time_diff <= self::$ONE_HOUR)) {
            $password = md5($account->password_tmp);
            $account->changePassword($request->id, $password);

            if ($account->auth_type == 9) {
                LoginAccountTable::getInstance ()->changeStudentPassword($account->id, $account->login_pw);
            } elseif ($account->auth_type == 10) {
                LoginAccountTable::getInstance ()->changeParentPassword($account->id, $account->login_pw);
            }

            //　メールを送る
            $data = array();
            $to_email = $request->email;
            $name = LoginAccountTable::getInstance ()->getName($request->id);
            $is_sent = $this->sendMailPasswordSuccess($data, $name, $to_email, false);
            if ($is_sent) {
                $this->logSuccess("Send mail message");
            } else {
                throw new \Exception( "Send mail message fail ");
            }
            return view ('School.Login.success_confirm')->with('name', $name);
        } else {
             return Redirect::back();
        }
    }
}
