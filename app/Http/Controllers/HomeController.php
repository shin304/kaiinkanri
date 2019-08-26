<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\MailSettingController;
use App\Model\MCityTable;
use App\Model\MPrefTable;
use App\Model\ZipcodeAddressTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Model\TempSchoolInfoTable;
use DateTime;
use App\Common\Constants;
use Illuminate\Support\Facades\DB;
use PEAR;
use Crypt_Blowfish;

class HomeController extends Controller {
    use \App\Common\Email;
    // 管理者メールアドレス ※メールを受け取るメールアドレス
    const MAIL_ADMIN_TO = "t.kawasaki@asto-system.com";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $_app_path = "/home/";
        $title = "らくらく会員管理 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        return view ( 'Home.index', compact("_app_path", "title"));
    }

    public function feature(Request $request) {
        $_app_path = "/home/";
        $title = "機能一覧 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        return view ( 'Home.feature', compact("_app_path", "title"));
    }

    public function qa(Request $request) {
        $_app_path = "/home/";
        $title = "よくあるご質問 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        return view ( 'Home.qa', compact("_app_path", "title"));
    }

    public function company(Request $request) {
        $_app_path = "/home/";
        $title = "会社概要 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        return view ( 'Home.company', compact("_app_path", "title"));
    }

    public function contact(Request $request) {
        $_app_path = "/home/";
        $title = "お問い合わせ | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        return view ( 'Home.contact', compact("_app_path", "title"));
    }

    public function contactConfirm(Request $request) {
        $_app_path = "/home/";
        $title = "お問い合わせ | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        return view ( 'Home.contact_confirm', compact("_app_path", "title"));
    }

    public function contactSend(Request $request) {
        $_app_path = "/home/";
        $title = "お問い合わせ | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        Mail::send('Home._parts.contact_mail', ['request' => $request], function ($m) use ($request) {
            $m->from('kanri@rakuraku-kanri.com', 'らくらく会員管理');
            $m->to(self::MAIL_ADMIN_TO, $request->name)->subject('会員管理システムへのお問い合わせ');
        });
        return redirect("/home/contact_complete");
    }

    public function contactComplete(Request $request) {
        $_app_path = "/home/";
        $title = "お問い合わせ | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";
        return view ( 'Home.contact_complete', compact("_app_path", "title"));
    }
    /*
     *
     *
     */
    public function blowfish(Request $request) {


        $vectors = file_get_contents(public_path  ( 'demo/vectors.txt'));

        $b = Crypt_Blowfish::factory('ecb');

        $b->setKey("My key");

        $encrypted_txt = $b->encrypt($vectors);

        $encrypted = $encrypted_txt;

        $plain_text = $b->decrypt($encrypted);

        $file = fopen(public_path('demo/encrypted.txt'), 'w');

        fwrite($file, $encrypted);

        $size = filesize(public_path ( 'demo/encrypted.txt'));

//        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="encrypted.txt"');
//        header('Content-Transfer-Encoding: binary');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Pragma: public');
        header('Content-Length: ' . $size);
        readfile(public_path("demo/encrypted.txt"));
//        return view ( 'Home.blowfish');
    }

//    public function upload($request){
//        $vectors = file_get_contents(public_path ( 'vectors.txt'));
//
//        $b = Crypt_Blowfish::factory('ecb');
//
//        $b->setKey("My key");
//
//        $encrypted_txt = $b->encrypt($vectors);
//
//        $encrypted = $encrypted_txt;
//
//        $plain_text = $b->decrypt($encrypted);
//
//        $file = fopen(public_path ('encrypted.txt'), 'w');
//
//        fwrite($file, $encrypted);
//
//        $size = filesize(public_path ( 'encrypted.txt'));
//
//        header('Content-Description: File Transfer');
//        header('Content-Type: plaintext/txt');
//        header('Content-Disposition: attachment; filename="encrypted.txt"');
//        header('Content-Transfer-Encoding: binary');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Pragma: public');
//        header('Content-Length: ' . $size);
//        readfile("encrypted.txt");
//    }

    public function registerTrial(Request $request) {
        session()->forget('errors');

        if (session()->has("_old_input")) {
            $request->merge(session("_old_input"));
            session()->forget("_old_input");
        }

        $_app_path = "/home/";
        $title = "デモ版利用登録 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";

        $prefList = MPrefTable::getInstance()->getList();
        $prefList = array_pluck($prefList, 'name', 'id');
        $cityList = array();

        return view ( 'Home.register', compact("request", "_app_path", "title", "prefList", "cityList"));
    }

    public function get_address_from_zipcode(Request $request) {
        try {
            if (! $request->ajax ()) {
                throw new Exception();
            }
            if(! $request->has ('zipcode')){
                return response()->json(['status' => false, 'message' => ""]);
            }

            $zipcodeAddressTable = ZipcodeAddressTable::getInstance();

            $res = $zipcodeAddressTable->getAddressFromZipcode( $request->zipcode );

            if(!empty($res)){
                return response()->json(['status' => true, 'message' => $res]);
            }else{
                return response()->json(['status' => false, 'message' => ""]);
            }

        }catch (Exception $e){
            throw new Exception();
        }
    }

    public function get_city(Request $request) {
        try {
            if (! $request->ajax ()) {
                throw new Exception();
            }
            if (!$request->offsetExists('pref_id')) {
                throw new Exception();
            }

            $pref_id = $request->pref_id;

            $cityList = MCityTable::getInstance ()->getListByPref( $pref_id );

            $dispCityList = array ();
            if (! empty ( $cityList )) {
                foreach ( $cityList as $idex => $row ) {
                    $dispCityList [$row ['id']] = $row ['name'];
                }
            }

            $ret ['city_list'] = $dispCityList;

            return response()->json($ret);
            //return $this->printJSON ( $ret );
        }catch (Exception $e){
            throw new Exception();
        }
    }

    public function confirmRegister(Request $request) {

        session()->forget('errors');

        $rules = $this->get_rules_register($request);

        $messages = $this->get_messages_register($request);

        $validator = Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {

            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        return $this->previewRegister($request);
    }

    private function get_rules_register(Request $request) {

        //TODO define validation rules

        $rules = [
               'customer_name' => 'required|max:255',
               'company_name' => 'required|max:255',
               'mail_address' => 'required|email|unique:login_account,login_id,,,auth_type,2,delete_date,NULL',
               'password' => 'required|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/',
               're_password' => 'required|min:8|max:16|same:password|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/',
               'zip_code1' => 'required|max:3',
               'zip_code2' => 'required|max:4',
               'pref_id' => 'required',
               'city_id' => 'required',
               'address' => 'required|max:255',
               'building' => 'max:64',
               'phone' => ['required', 'regex:/^(?:\d{10}|\d{3}-\d{3}-\d{4}|\d{2}-\d{4}-\d{4}|\d{3}-\d{4}-\d{4})$/'],
               'fax' => ['nullable','regex:/^(?:\d{10}|\d{3}-\d{3}-\d{4}|\d{2}-\d{4}-\d{4}|\d{3}-\d{4}-\d{4})$/'],
               'home_page' => 'max:255',
        ];

        return $rules;
    }

    private function get_messages_register(Request $request) {

        //TODO define validation message
        $messages = [
            'customer_name.required' => '代表者・登録者名称は必須です。',
            'customer_name.max' => '代表者・登録者名称を含めた255文字で入力してください。',
            'company_name.required' => '会社・組織名称は必須です。',
            'company_name.max' => '会社・組織名称を含めた255文字で入力してください。',
            'mail_address.required' => '登録メールアドレスは必須です。',
            'mail_address.email' => 'メールアドレスの形式に誤りがあります。',
            'mail_address.unique' => 'メールアドレスは既に存在しています。',
            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードを含めた8～16文字で入力してください。',
            'password.max' => 'パスワードを含めた8～16文字で入力してください。',
            're_password.same' => 'パスワードが一致しません。',
            'password.regex' => 'パスワードの形式に誤りがあります。',
            're_password.required' => 'パスワードの再確認は必須です。',
            're_password.min' => 'パスワードを含めた8～16文字で入力してください。',
            're_password.max' => 'パスワードを含めた8～16文字で入力してください。',
            're_password.regex' => 'パスワードの形式に誤りがあります。',   
            'zip_code1.required' => '郵便番号は必須です。',
            'zip_code1.max' => '郵便番号1を含めた3文字で入力してください。',
            'zip_code2.required' => '郵便番号は必須です。',
            'zip_code2.max' => '郵便番号2を含めた4文字で入力してください。',
            'pref_id.required' => '都道府県は必須です。',
            'city_id.required' => '市区町村は必須です。',
            'address.required' => '番地は必須です。',
            'address.max' => '番地を含めた255文字で入力してください。',
            'building.max' => 'ビル名を含めた64文字で入力してください。',
            'phone.required' => '連絡先電話番号は必須です。',
            'phone.regex' => '連絡先電話番号の形式に誤りがあります。',
            'fax.regex' => 'FAXの形式に誤りがあります。',
            'home_page.max' => 'ホームページを含めた255文字で入力してください。',
        ];

        return $messages;
    }

    public function previewRegister(Request $request) {

        session()->forget('errors');

        $_app_path = "/home/";
        $title = "デモ版利用登録 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";

        $prefList = MPrefTable::getInstance()->getList();
        $prefList = array_pluck($prefList, 'name', 'id');

        $cityList = MCityTable::getInstance()->getListByPref($request->pref_id);
        $cityList = array_pluck($cityList, 'name', 'id');

        return view ( 'Home.preview_register', compact("request", "_app_path", "title", "prefList", "cityList"));

    }

    // テーブルにデータを更新する
    public function storeRegister(Request $request) {

        $_app_path = "/home/";
        $title = "デモ版利用登録 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";

        $rules = $this->get_rules_register($request);

        $messages = $this->get_messages_register($request);

        $validator = Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {

            return $this->registerTrial($request)->withErrors($validator->errors());

        }

        //TODO store input to temp
        //レビューした後で「temp_school_info」テーブルにデータを更新する
        $school_info_table = TempSchoolInfoTable::getInstance ();
        $customer_name = $request->customer_name;
        $company_name = $request->company_name;
        $mail_address = $request->mail_address;
        $login_pw = md5($request->password);
        $zip_code = $request->zip_code1.$request->zip_code2;
        $pref_id = $request->pref_id;
        $city_id = $request->city_id;
        $address = $request->address;
        $building = $request->building;
        $phone = $request->phone;
        $fax = $request->fax;
        $home_page = $request->home_page;
        $register_code = ($this->generateRandomString(32));

        $school_info_table->insertSchoolInfo ($mail_address, $login_pw, $company_name, $customer_name, $zip_code, $pref_id, $city_id, $address, $building, $phone, $fax, $home_page, $register_code);

        //TODO send email for admin
        // メールを送る
        $to_email = $request->mail_address;
        $data = array();
        $is_sent = $this->sendConfirmEmail($data, $register_code, $to_email, false);

        if ($is_sent) {
            $this->logSuccess("Send mail message");
        } else {
            throw new \Exception("Send mail message fail ");
        }
        //TODO return success and welcome view

        return view('Home.success', compact("request", "_app_path", "title"));

    }

    public function mailConfirmed(Request $request) {
        $school_info_table = TempSchoolInfoTable::getInstance();
        $school_info = $school_info_table->getSchoolInfoAccountByCode ($request->code);
        //時間のチェック
        $current_date = new DateTime(date('Y-m-d H:i:s'));
        $time_code = new DateTime(date('Y-m-d H:i:s', strtotime($school_info->register_date)));
        $interval = $current_date->diff($time_code);
        $time_diff = ($interval->h)*60*60 + ($interval->i)*60 + ($interval->s);

        $_app_path = "/home/";
        $title = "デモ版利用登録 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理";

        if (($time_diff - Constants::ONE_HOUR) <= 1 && $school_info->status < Constants::STATUS_MAIL_CONFIRMED) {
//            TempSchoolInfoTable::getInstance ()->editStatusForSchoolInfoByCode($request->code);
            try {
                $school_info = $school_info->toArray();
                //check if email is exist -> lock all record include this one
                $exists_list = $school_info_table->where('mail_address', '=', $school_info['mail_address'] )->get()->toArray();
                if(count($exists_list) > 1){
                    $school_info['is_locked'] = 1;
                    foreach ($exists_list as $k => $temp_school){
                        $temp_school['is_locked'] = 1;
                        $school_info_table->save($temp_school, true);
                    }
                }

                //update status to mail confirmed
                $school_info['status'] = Constants::STATUS_MAIL_CONFIRMED; // メールを確認した
                $school_info_table->save($school_info, true);

            } catch (Exception $e) {
                $school_info_table->rollBack();
            }

            $school_info_table->commit();

            // メールリストを取る
            $to_array_mail_admin = array();
            $mail_list = DB::table('mail_setting')->where ('delete_date', null)->get();
            if (count($mail_list)>0) {
                foreach ($mail_list as $k => $item) {
                    array_push ($to_array_mail_admin, $item->mail);
                }
            } else {
                array_push ($to_array_mail_admin, self::MAIL_ADMIN_TO);
            }

            // メールを送る
            // $to_email = self::MAIL_ADMIN_TO;
            $data = array();
            $email_user = $school_info['mail_address'];
            $this->sendEmailForAdmin($data, $email_user, $to_array_mail_admin, false);
            return view('Home.mail_confirmed', compact("request", "_app_path", "title"));
        } else {
            return view('Home.mail_confirmed_error', compact("request", "_app_path", "title"));
        }
    }

    public function ajaxCheckEmail(Request $request)
    {
        if($request->ajax()){
            $school_info_table = TempSchoolInfoTable::getInstance();
            $temp = $school_info_table->checkEmailRegister($request->mail_address);
            if (!is_null($temp)) {
                $response = 1;
            } else {
                $response = 0;
            }
            return response()->json($response);
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
}
