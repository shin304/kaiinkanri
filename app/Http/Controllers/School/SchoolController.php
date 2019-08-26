<?php

namespace App\Http\Controllers\School;

use App\Common\Constants;
use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\AdditionalCategoryTable;
use App\Model\MPrefTable;
use App\Model\MCityTable;
use App\Model\PaymentMethodBankRelTable;
use App\Model\PaymentMethodDataTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\PaymentMethodTable;
use App\Model\PaymentMethodValidationTable;
use App\Model\PschoolTable;
use App\Model\LoginAccountTable;
use App\Model\PschoolBankAccountTable;
use App\Model\MStudentTypeTable;
use App\Model\SchoolMenuTable;
use App\Model\StaffTable;
use App\Model\RoutinePaymentTable;
use App\Model\StudentTable;
use App\Model\InvoiceAdjustNameTable;
use App\Model\StudentGradeTable;
use App\Model\HierarchyTable;
use App\Model\PaymentAgencyTable;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Http\Request;
use App\ConstantsModel;
use App\Http\Controllers\Common\Validaters;
use App\Http\Controllers\Common\FileUpload;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Carbon\Carbon;
use App\Model\InvoiceItemTable;
use App\Lang;
use Validator;

/**
 * デフォルトの動作（ログインページを表示する）
 */
class SchoolController extends _BaseSchoolController {
    const SESSION_REGIST_OK = 'school.school.regist_ok';
    const SESSION_SEARCH_KEY = 'session.admin.school.search.key';
    const SESSION_PAGE_KEY = 'session.admin.school.page.key';
    const SESSION_SORT_KEY = 'session.admin.school.sort.key';
    const PASSWORD_MAX = 64;
    const PASSWORD_MIN = 8;
    const NAME_MAX = 255;
    const PASSWORD_REGEX = "/^[a-zA-Z0-9-\/:-@\[-\`\{-\~]+$/";
    const STR_DATA = 'data';
    const STR_ERROR = 'err';
    const SCHOOL_CODE_LENGTH_MIN = 100000;
    const SCHOOL_CODE_LENGTH_MAX = 999999;
    const DEFAULT_CARD_IMG = "_card_simple.png";
    private static $subject_list;
    private static $WEATHER_URL_JP = 'http://weather.yahoo.co.jp/weather/search';
    private static $WEATHER_RSS_JP = 'http://rss.weather.yahoo.co.jp/rss/days/';
    protected static $ACTION_URL = 'school';
    protected static $LANG_URL = 'school';
    private static $TEMPLATE_URL = 'school';
    private $lan;
    private $schoolCategory;
    private $pref_list;
    private $city_list;
    private $bank_account_type_list;
    private $close_date_list;
    private $invoice_date_list;
    private $withdrawal_date_list;
    private $amt_disp_type_list;
    private $payment_style_list;
    private $currencies;
    private $business_divisions_list;
    private $org_type;
    private $org_status;
    private $languages_input;
    private $country_list;
    protected $_loginAdmin;
    private $_hankakumojiRegix = '^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$';
    private $_hankakumoji = '^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\ ]+$';
    private $_zenkakukatakanaRegix = '^[ア-ン゛゜ァ-ォャ-ョー「」、\　\ ]+$';
    public function __construct(Request $request) {
        parent::__construct ();
        $message_content = parent::getMessageLocale ();
        $this->lan = new Lang ( $message_content );
        $this->_loginAdmin = parent::getLoginAdmin ();
        // 生徒区分
        $this->schoolCategory = ConstantsModel::$dispSchoolCategory;

        // 都道府県
        $this->pref_list = MPrefTable::getInstance ()->getList ();
        
        // 市区町村
        if ($request->has ( 'pref_id' )) {
            $this->city_list = MCityTable::getInstance ()->getList ( array (
                    'pref_id' => $request->pref_id
            ));
        }
        /*$language = 1; cannot find useages*/
        // 口座種別
        $this->bank_account_type_list = ConstantsModel::$type_of_bank_account [session()->get( 'school.login.language')];
        
        // 〆日
        $this->close_date_list = ConstantsModel::$close_date_list[session()->get( 'school.login.language')];
        
        // 支払日
        $this->invoice_date_list = ConstantsModel::$invoice_date_list[session()->get( 'school.login.language')];
        
        // 口座引落日
        $this->withdrawal_date_list = ConstantsModel::$withdrawal_date_list;
        
        // 金額表示
        $this->amt_disp_type_list = ConstantsModel::$amt_disp_type_list[session()->get( 'school.login.language')];
        
        // 支払形態
        $this->payment_style_list = ConstantsModel::$payment_style[session()->get( 'school.login.language')];
        
        //
        $this->currencies = ConstantsModel::$currencies;
        
        $this->business_divisions_list = ConstantsModel::$business_divisions_type[session()->get( 'school.login.language')];
        
        $this->org_type = ConstantsModel::$org_type[session()->get( 'school.login.language')];
        
        $this->org_status = ConstantsModel::$org_status[session()->get( 'school.login.language')];
        
        $this->languages_input = ConstantsModel::$languages_input[session()->get( 'school.login.language')];
        $this->country_list = ConstantsModel::$country_list[session()->get( 'school.login.language')];
    }
    public function execute(Request $request) {

        $regist_message = "";
        if (session()->has(self::SESSION_REGIST_OK ) && session()->get( self::SESSION_REGIST_OK ) == 1) {
            session()->forget( self::SESSION_REGIST_OK );
            $regist_message = 1;
        }

        // 自分の塾の情報を得る
        $pschool_data = PschoolTable::getInstance ()->load ( $this->_loginAdmin ['id'] );
        $login_data = LoginAccountTable::getInstance ()->load ( $pschool_data['login_account_id'] );
        
        $bank_data = PschoolBankAccountTable::getInstance ()->getActiveList ( array (
                'pschool_id' => $pschool_data['id']
        ) );
        
        $data = $pschool_data;
        $data ['_login_id'] = isset($login_data ['login_id'])? $login_data ['login_id']:"";
        //get agency name and other agency name
        if(isset($data['payment_agency_id'])){
            $data['payment_agency_name']= PaymentAgencyTable::getInstance()->getAgencyName($data['payment_agency_id'])['agency_name'];
        }
        if(isset($data['other_payment_agency_id'])){
            $data['other_payment_agency_name']= PaymentAgencyTable::getInstance()->getAgencyName($data['other_payment_agency_id'])['agency_name'];
        }
        //
        if (isset ( $bank_data [0] )) {
            $data ['_invoice_type'] = $bank_data [0] ['invoice_type'];
            $data ['_bank_type'] = $bank_data [0] ['bank_type'];
            $data ['_bank_code'] = $bank_data [0] ['bank_code'];
            $data ['_bank_name'] = $bank_data [0] ['bank_name'];
            $data ['_branch_code'] = $bank_data [0] ['branch_code'];
            $data ['_branch_name'] = $bank_data [0] ['branch_name'];
            $data ['_bank_account_type'] = $bank_data [0] ['bank_account_type'];
            $data ['_bank_account_number'] = $bank_data [0] ['bank_account_number'];
            $data ['_bank_account_name'] = $bank_data [0] ['bank_account_name'];
            
            $data ['_bank_account_name_kana'] = $bank_data [0] ['bank_account_name_kana'];
            $data ['_post_account_kigou'] = $bank_data [0] ['post_account_kigou'];
            $data ['_post_account_number'] = $bank_data [0] ['post_account_number'];
            $data ['_post_account_name'] = $bank_data [0] ['post_account_name'];
            $data ['consignor_code'] = $bank_data [0] ['consignor_code'];
            $data ['consignor_name'] = $bank_data [0] ['consignor_name'];
            $data ['address_post1'] = substr ($pschool_data['zip_code'], 0, 3 );
            $data ['address_post2'] = substr ($pschool_data['zip_code'], 3, 4 );
            
            // 前払い・後払い
            $data ['payment_style'] = $pschool_data['payment_style'];
            if (! empty ( $pschool_data['currency'] )) {
                $data ['currency'] = $pschool_data['currency'];
            } else {
                $data ['currency'] = 1; // default 円
            }
        }
        $bank_account_type_list = $this->bank_account_type_list;
        $request->merge( $data );
        foreach ( $bank_account_type_list as $key => $item ) {
            if ($request ['_bank_account_type'] == $key) {
                $request ['_bank_account_type'] = $item;
            }
        }
        $city_list = array ();
        // 市区町村
        if ($request->has ( 'pref_id' )) {
            $city_list = MCityTable::getInstance ()->getList ( array (
                    'pref_id' => $request ['pref_id'] 
            ) );
        } else {
            $city_list = $this->city_list;
        }
        $pschool = $pschool_data;
        $loginaccount = $login_data;
        $bank = $bank_data;
        //dd($bank);
        $data = $data;
        $is_manager = $this->isManager ();
        $lan = $this->lan;
        $languages_input = $this->languages_input;
        $pref_list = $this->pref_list;
        $amt_disp_type_list = $this->amt_disp_type_list;
        $country_list = $this->country_list;
        $payment_style_list = $this->payment_style_list;
        $currencies = $this->currencies;
        $invoice_date_list = $this->invoice_date_list;
        $close_date_list = $this->close_date_list;
        // 支払方法
        //$payment_list = PaymentMethodPschoolTable::getInstance()->getActiveList(array('pschool_id'=>session('school.login')['id']), array('sort_no'));
        // change to get agency name
        $payment_list = PaymentMethodPschoolTable::getInstance()->getListMethodPschool(session()->get('school.login.id'));

        // テンプレート
        if (session()->get ( 'school.login.country_code') == 81) {//81 : Vietnamese
            return view ( 'School.School.index', compact ( 'regist_message', 'request', 'bank_account_type_list', 'close_date_list', 'invoice_date_list', 'payment_style_list', 'pref_list', 'currencies', 'country_list', 'amt_disp_type_list', 'languages_input', 'auths', 'lan', 'city_list', 'pschool', 'loginaccount', 'bank', 'data', 'is_manager', 'payment_list' ) );
        } else {
            // return $this->convertSmartyPath ( 'school/top2.html' );
        }
    }
    public function executeInput(Request $request) {
        // clear error message
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }

        $regist_message = "";
        if (session()->has( self::SESSION_REGIST_OK ) && session ()->get ( self::SESSION_REGIST_OK ) == 1) {
            session()->forget( self::SESSION_REGIST_OK );
            $regist_message = 1;
        }

        // 自分の塾の情報を得る
        if(!$request->has('errors')){
            $pschool_data = PschoolTable::getInstance ()->load ( $this->_loginAdmin ['id'] );
            $login_data = LoginAccountTable::getInstance ()->load ( $pschool_data ['login_account_id'] );
            $data = $pschool_data;
            $data ['_login_id'] = isset($login_data ['login_id'])?$login_data ['login_id']:"";
            $bank_data = PschoolBankAccountTable::getInstance ()->getActiveList ( array (
                    'pschool_id' => $pschool_data ['id']
            ));
//            dd($data);
            $request->merge ( $data );
            if (isset ( $bank_data [0] )) {
                $data ['_invoice_type'] = $bank_data [0] ['invoice_type'];
                $data ['_bank_type'] = $bank_data [0] ['bank_type'];
                $data ['_bank_code'] = $bank_data [0] ['bank_code'];
                $data ['_bank_name'] = $bank_data [0] ['bank_name'];
                $data ['_branch_code'] = $bank_data [0] ['branch_code'];
                $data ['_branch_name'] = $bank_data [0] ['branch_name'];
                $data ['_bank_account_type'] = $bank_data [0] ['bank_account_type'];
                $data ['_bank_account_number'] = $bank_data [0] ['bank_account_number'];
                $data ['_bank_account_name'] = $bank_data [0] ['bank_account_name'];

                $data ['_bank_account_name_kana'] = $bank_data [0] ['bank_account_name_kana'];
                $data ['_post_account_kigou'] = $bank_data [0] ['post_account_kigou'];
                $data ['_post_account_number'] = $bank_data [0] ['post_account_number'];
                $data ['_post_account_name'] = $bank_data [0] ['post_account_name'];
                $data ['consignor_code'] = $bank_data [0] ['consignor_code'];
                $data ['consignor_name'] = $bank_data [0] ['consignor_name'];
                $data ['zip_code1'] = substr ( $pschool_data ['zip_code'], 0, 3 );
                $data ['zip_code2'] = substr ( $pschool_data ['zip_code'], 3, 4 );

                // 前払い／後払い
                $data ['payment_style'] = $pschool_data ['payment_style'];
                if (! empty ( $pdata ['currency'] )) {
                    $data ['currency'] = $pdata ['currency'];
                } else {
                    $data ['currency'] = '1'; // default 円
                }
                if (! empty ( $pdata ['currency_decimal_point'] )) {
                    $data ['currency_decimal_point'] = $pdata ['currency_decimal_point'];
                } else {
                    $data ['currency_decimal_point'] = '0'; // default 0
                }
                // }
            }

            $request->merge ( $data );
        }else{
            $request->offsetSet('kakuin_path',$request->kakuin_path_curr);
        }
        $bank_data = PschoolBankAccountTable::getInstance ()->getActiveList ( array (
                'pschool_id' =>session()->get('school.login.id')
        ));
        foreach ($bank_data as $k => $v){
            if(isset($v['post_account_number'])){
                $bank_data[$k]['post_account_number_1'] = substr($v['post_account_number'],0,1);
                $bank_data[$k]['post_account_number_2'] = substr($v['post_account_number'],1,strlen($v['post_account_number']));
            }
        }
        $city_list = "";
        // 市区町村
        if ($request->has ( 'pref_id' )) {
            $city_list = MCityTable::getInstance ()->getList ( array (
                'pref_id' => $request->pref_id
            ));
        }

        $is_manager = $this->isManager ();
        $lan = $this->lan;
        $languages_input = $this->languages_input;
        $pref_list = $this->pref_list;
        $amt_disp_type_list = $this->amt_disp_type_list;
        $country_list = $this->country_list;
        $payment_style_list = $this->payment_style_list;
        $currencies = $this->currencies;
        $invoice_date_list = $this->invoice_date_list;
        $close_date_list = $this->close_date_list;
        $bank_account_type_list = $this->bank_account_type_list;
        $consignor_list = PaymentAgencyTable::getInstance ()->getList ();
        $errors= $request->errors;
//        dd($request);
        // テンプレート

        //get list payment method
            //Toran 29-06-2017
            $lan = $this->lan;
            $paymentMethodTable = PaymentMethodTable::getInstance();
            $list_payment_method = $paymentMethodTable->getListMethodDefaultPschool(session()->get('school.login.id'),$lan);
        //

        if (session()->get( 'school.login.country_code' )== 81) {// Vietnamese
            return view ( 'School.School.input', compact ( 'close_date_list','consignor_list','bank_account_type_list', 'invoice_date_list',
                    'list_payment_method','request', 'payment_style_list', 'pref_list', 'currencies', 'country_list', 'amt_disp_type_list', 'languages_input', 'auths', 'lan', 'city_list', 'pschool', 'loginaccount', 'bank', 'data', 'is_manager','errors','bank_data' ) );
        } else {
            // return $this->convertSmartyPath ( 'school/input2.html' );
        }
    }

    private function get_validate_rules($request) {
        $rules = [
            'name' => 'required|max:255',
            'address_post1' => 'digits:3',
            'address_post2' => 'digits:4',
            'pref_id' => 'required',
            'city_id' => 'required',
            'address' => 'required|max:255',
            'tel' => 'required|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/',
            'fax' => 'nullable|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/',
            'invoice_closing_date' => 'required',
            'payment_date' => 'required',
            'amount_display_type' => 'required',
            'sales_tax_rate' => 'required|regex:/^\d{1}\.\d{1,4}$/',
            'prefix_code' => 'nullable|max:100|unique:pschool,prefix_code,'.$request->id.',id'
        ];
        if($request->has('_login_pw')){
            $rules['_login_pw'] = 'min:8|regex:/^[a-zA-Z0-9-\/:-@\[-\`\{-\~]+$/';
            $rules['_login_pw_c'] ='same:_login_pw';
        }

        /*if($request->_invoice_type == 2){
            $rules['consignor_code'] = 'required';
            $rules['consignor_name'] = 'required';
            if ($request->_bank_type == 1) {
                $rules['_bank_code'] = 'required|numeric|digits_between:1,4';
                $rules['_bank_name'] = 'required|max:15';
                $rules['_branch_code'] = 'required|numeric|digits_between:1,3';
                $rules['_branch_name'] = 'required|max:15';
                $rules['_bank_account_type'] = 'required';
                $rules['_bank_account_number'] = 'required|numeric|digits_between:1,7';
                $rules['_bank_account_name'] = 'required|max:30';
            }
            if ($request->_bank_type == 2) {

                $rules['_post_account_kigou'] = 'required|numeric|digits_between:1,5';
                $rules['_post_account_number'] = 'required|numeric|digits_between:1,8';

                if($request->has('_post_account_name')){
                    $rules['_post_account_name'] = 'max:30';
                }
            }
        }*/
        if($request->has('mailaddress')){
            $rules['mailaddress'] = 'email|max:64';
        }
        //dd($rules);
        return $rules;
    }
    private function get_validate_message($request) {
        $message = [
                '_login_pw.min' => 'length_pass_error_title',
                '_login_pw.regex' => 'format_pass_error_title',
                '_login_pw_c.same' => 'match_password_error_title',
                'name.required' => 'required_school_name_error_title',
                'name.max' => 'length_school_name_error_title',
                'address_post1.digits' => 'length_postal_1_error_title',
                'address_post2.digits' => 'length_postal_2_error_title',
                'pref_id.required' => 'required_city_error_title',
                'city_id.required' => 'required_district_error_title',
                'address.required'  => 'required_address_error_title',
                'address.max' => 'length_address_error_title',
                'tel.required' => 'required_phone_error_title',
                'tel.regex' => 'invalid_phone_error_title',
                'fax.required' => 'required_fax_error_title',
                'fax.regex' => 'invalid_fax_error_title',
                'invoice_closing_date.required' => 'required_deadline_error_title',
                'payment_date.required' => 'required_payment_error_title',
                'amount_display_type.required' => 'required_amout_display_error_title',
                'sales_tax_rate.required' => 'required_tax_error_title',
                'sales_tax_rate.regex' => 'length_tax_error_title',
                'consignor_code.required' => 'required_consignor_code_error_title',
                'consignor_code.digits_between' => 'length_consignor_code_error_title',
                'consignor_name.required' => 'required_consignor_name_error_title',
                'consignor_name.max' => 'length_consignor_name_error_title',
                'prefix_code.required' => 'prefix_code_required_error',
                'prefix_code.max' => 'prefix_code_max_error',
                'prefix_code.unique' => 'prefix_code_unique_error',
                '_bank_code.required' => 'required_bank_code_error_title',
                '_bank_code.numeric' => 'format_bank_code_error_title',
                '_bank_code.digits_between' => 'length_bank_code_error_title',
                '_bank_name.required' => 'required_bank_name_error_title',
                '_bank_name.max' => 'length_bank_name_error_title',
                '_bank_name.regex' => 'format_bank_name_error_title',
                '_branch_code.required' => 'required_branch_code_error_title',
                '_branch_code.numeric' => 'format_branch_code_error_title',
                '_branch_code.digits_between' => 'length_branch_code_error_title',
                '_branch_name.required' => 'required_branch_name_error_title',
                '_branch_name.max' => 'length_branch_name_error_title',
                '_branch_name.regex' => 'format_branch_name_error_title',
                '_bank_account_type.required' => 'required_classification_error_title',
                '_bank_account_number.required' => 'required_bank_acc_number_error_title',
                '_bank_account_number.numeric' => 'format_bank_acc_number_error_title',
                '_bank_account_number.digits_between' => 'length_bank_acc_number_error_title',
                '_bank_account_name.required' => 'required_bank_acc_name_error_title',
                '_bank_account_name.max' => 'length_bank_acc_name_error_title',
                '_bank_account_name.regex' => 'format_bank_acc_name_error_title',
                '_post_account_kigou.required' => 'required_passbook_code_error_title', 
                '_post_account_kigou.numeric' => 'format_passbook_code_error_title',
                '_post_account_kigou.digits_between' => 'length_passbook_code_error_title',
                '_post_account_number.required' => 'required_passbook_number_error_title',
                '_post_account_number.numeric' => 'format_passbook_number_error_title',
                '_post_account_number.digits_between' => 'length_passbook_number_error_title',
                '_post_account_name.regex' => 'format_bank_acc_name_error_title',
                '_post_account_name.max' => 'length_bank_acc_name_error_title',
                'mailaddress.email' => 'format_email_error_title',
                'mailaddress.max' => 'length_email_error_title'
        ];
        return $message;
    }
    public function executeComplete(Request $request) {
        // 一応、再確認を行う。
        $rules = $this->get_validate_rules($request);
        $messsages = $this->get_validate_message($request);
        $validator = Validator::make(request()->all(), $rules, $messsages);

        //check syntax
       /* if(!mb_ereg($this->_hankakumojiRegix,$request->consignor_name)){
            $validator->errors()->add('consignor_name','format_consignor_name_error_title');
        }*/
        /*if($request->_invoice_type == 2){
            if ($request->_bank_type == 1) {
                if(!mb_ereg($this->_hankakumojiRegix,$request->_bank_name)){
                    $validator->errors()->add('_bank_name','format_bank_name_error_title');
                }
                if(!mb_ereg($this->_hankakumoji,$request->_branch_name)){
                    $validator->errors()->add('_branch_name','format_branch_name_error_title');
                }
                if(!mb_ereg($this->_hankakumojiRegix,$request->_bank_account_name)){
                    $validator->errors()->add('_bank_account_name','format_bank_acc_name_error_title');
                }
            }elseif ($request->_bank_type== 2) {
                if(!mb_ereg($this->_hankakumojiRegix,$request->_post_account_name)){
                    $validator->errors()->add('_post_account_name','format_bank_acc_name_error_title');
                }
            }
        }*/

        if($validator->errors()->all()!= null){
            $request->offsetSet('errors', $validator->errors());
            return $this->executeInput($request);
        }
        if ($validator->fails()) {
            $request->offsetSet('errors',$validator->errors());
            return $this->executeInput($request);
        }
        
        // 登録を行う
        $pschoolTable = PschoolTable::getInstance ();
        $pschoolBankTable = PschoolBankAccountTable::getInstance ();
        $loginAccountTable = LoginAccountTable::getInstance ();

        $pschoolTable->beginTransaction ();
        
        // 現状のデータを取得
        $orig_row = $pschoolTable->load ( $request->id);
        $orig_row_b = $pschoolBankTable->getRow ( array (
                'pschool_id' => $request->id,
                'delete_date is null' 
        ) );
        $regist_error = "";
        
        try {
            $save = array ();
            //image process
            if($request->offsetExists('kakuin_path')){
                $imageName = time().'.'.$request->kakuin_path->getClientOriginalExtension();
                $request->kakuin_path->move(public_path('/images/school/kakuin/'), $imageName);
                $save ['kakuin_path'] = "/images/school/kakuin/".$imageName;
                $request->offsetSet('kakuin_path',"/images/school/kakuin/".$imageName);
            }elseif($request->has("kakuin_path_curr")){
                $request->offsetSet('kakuin_path',$request->kakuin_path_curr);
            }
            //
            $save ['id'] = $request->id;
            $save ['daihyou'] = $request->daihyou;
            $save ['official_position'] = $request->official_position;
            $save ['nyukin_before_month'] = $request->nyukin_before_month;
            $save ['name'] = $request->name;
            $save ['mailaddress'] = $request->mailaddress;
            $save ['zip_code'] = ( $request->offsetExists('zip_code')? $request->zip_code : ($request->zip_code1 . $request->zip_code2));
            $save ['pref_id'] = $request->offsetExists('pref_id')? $request->pref_id : NULL;
            $save ['city_id'] =$request->offsetExists('city_id') ? $request->city_id : NULL;
            $save ['address'] = $request->address;
            $save ['building'] = $request->building;
            $save ['tel'] = $request->tel;
            $save ['fax'] = $request->fax;
            $save ['web'] = $request->web;
            $save ['invoice_closing_date'] = $request->invoice_closing_date;
            $save ['invoice_batch_date'] = $request->invoice_batch_date;
            $save ['payment_date'] = $request->payment_date;
            $save ['payment_month'] = $request->payment_month;
            // $save['withdrawal_date'] = $request['withdrawal_date'];
            $save ['withdrawal_day'] = $request->withdrawal_day;
            $save ['amount_display_type'] = $request->amount_display_type;
            $save ['sales_tax_rate'] = $request->sales_tax_rate;
            $save ['price_setting_type'] = $request->price_setting_type;
            // 支払形態 1:前払い 2:後払い
            $save ['payment_style'] = $request ->payment_style;
            $save ['currency'] = $request->currency;
            $save ['currency_decimal_point'] = $request->currency_decimal_point;
            $save ['payment_agency_id'] = $request->payment_agency_id;
            $save ['other_payment_agency_id'] = $request->other_payment_agency_id;
            $save ['prefix_code'] = $request->prefix_code;
            $save ['proviso'] = $request->proviso;
            $save ['nyukin_pass_required'] = $request->nyukin_pass_required;
            $save ['show_number_corporation'] = $request->show_number_corporation;
            $save ['is_zip_csv'] = $request->is_zip_csv;
            $pschoolTable->save ( $save );
            
            $save = array ();
            if (! empty ( $orig_row_b )) {
                $save ['id'] = $orig_row_b ['id'];
            }
            $save ['pschool_id'] = $request->id;
            //temp comment : save consignor to only the first bank
            /*$save ['invoice_type'] = $request->_invoice_type;
            $save ['bank_type'] = $request->_bank_type;
            $save ['bank_code'] = $request->_bank_code;
            $save ['bank_name'] = $request->_bank_name;
            $save ['branch_code'] = $request->_branch_code;
            $save ['branch_name'] = $request->_branch_name;
            $save ['bank_account_type'] = $request->_bank_account_type;
            $save ['bank_account_number'] = $request->_bank_account_number;
            $save ['bank_account_name'] = $request->_bank_account_name;
            
            $save ['bank_account_name_kana'] = $request->_bank_account_name_kana;
            $save ['post_account_kigou'] = $request->_post_account_kigou;
            $save ['post_account_number'] = $request->_post_account_number;
            $save ['post_account_name'] = $request->_post_account_name;*/
            $save ['consignor_code'] = $request->consignor_code;
            $save ['consignor_name'] = $request->consignor_name;
            $pschoolBankTable->save ( $save );
            
            $save = array ();
            $save ['id'] = $orig_row ['login_account_id'];
            if ($request->has ( '_login_pw' )) {
                $save ['login_pw'] = md5 ( $request->_login_pw);
            }
            $loginAccountTable->save ( $save );
            //dd($save);
            $pschoolTable->commit ();
        } catch ( Exception $ex ) {
            $pschoolTable->rollBack ();
            $regist_error = 1;
            return $this->executeInput ( $request );
        }
        // TOPの編集画面にとばす
        session ()->put ( self::SESSION_REGIST_OK, 1 );
        return $this->execute ( $request );
    }

    public function executeInputIndiv(Request $request) {
        /*$auths = AuthConfig::getAuth ( 'school' );
        $menu_auth = parent::setMenuAuth ( session ( 'school.login' ) ['authority'] );*/
        $pschool = PschoolTable::getInstance ()->load ( $this->_loginAdmin ['id'] );
//        if (isset ( $pschool )) {
//            if (! empty ( $pschool ['card_front_img'] )) {
//                $request ['card_front_img'] = $pschool ['card_front_img'];
//            } else {
//                $request ['card_front_img'] = self::DEFAULT_CARD_IMG;
//            }
//            if (! empty ( $pschool ['card_back_img'] )) {
//                $request ['card_back_img'] = $pschool ['card_back_img'];
//            } else {
//                $request ['card_back_img'] = self::DEFAULT_CARD_IMG;
//            }
//        }
        
        if (! $request->exists ( '_studenttype' )) {
            // 自分配下の支部一覧取得（自分も含めて）
            //$lower_ids = $this->getHierarchy ( session ( 'school.login' ) ['id'] );
            $lower_ids = session()->get( 'school.login.id' );
            $student_type_list = MStudentTypeTable::getInstance ()->getActiveList ( array (
                    'pschool_id' => session()->get ( 'school.login.id' )
            ) );
            if (! empty ( $student_type_list )) {
                foreach ( $student_type_list as $value ) {
                    // すでにstudentテーブルで使用されているかチェックする
                    
                    $bExist = false;
                    $temp = StudentTable::getInstance ()->getList ( array (
                            'pschool_id' => $lower_ids,
                            'm_student_type_id' => $value ['id']
                    ) );
                    if (! empty ( $temp ) || count ( $temp ) > 0) {
                        $bExist = true;
                    }
                    $value ['used'] = $bExist;
                    $student_type_disp_list ['_studenttype'] [] = $value;
                }
                $request->merge ( $student_type_disp_list );
            }
            
            $pdata ['_ap_record_display'] = $pschool ['ap_record_display'];
            
            // 今回いらないので、コメントする
            // $pdata ['_exam_record_display'] = $pschool ['exam_record_display'];
            // $pdata ['_school_record_display'] = $pschool ['school_record_display'];
            
            $request->merge ( $pdata );
        }
        // 帯色のリスト
        
//        if (! $request->exists ( 'grades' )) {
//            $student_grade_list = array ();
//
//         /*   $current_hierarchy = HierarchyTable::getInstance ()->getHierarchy ( session ( 'school.login' ) ['id'] );*/
//            // 帯色のリスト
//            $student_grade_list = StudentGradeTable::getInstance ()->getGradeHonbu ( session()->get('school.login.id'));
//            if (empty ( $student_grade_list )) {
//                $student_grade_list = StudentGradeTable::getInstance ()->getGradeListWithCountryCode ( $pschool ['group_id'], $pschool ['country_code'] );
//                $request ['isShibu'] = 1;
//            }
//            if (! empty ( $student_grade_list )) {
//                foreach ( $student_grade_list as $idx => $value ) {
//                    $student_grade_disp_list ['grades'] [] = $value;
//                }
//                $request->merge ( $student_grade_disp_list );
//            }
//        }

        /*if ($request->card_front['keep'] !== null) {
            $request->offsetSet('card_front_filename',$request->card_front['keep']['fullpath']);
            $request->offsetSet('card_front_type',$request->card_front['type']) ;
            $request->offsetSet('card_front_new_img',$request->card_front['keep']['basename']) ;
        }
        
        if ($request->card_back['keep'] !== null ) {
            $request ['card_back_filename'] = $request ['card_back'] ['keep'] ['fullpath'];
            $request ['card_back_type'] = $request ['card_back'] ['type'];
            $request ['card_back_new_img'] = $request ['card_back'] ['keep'] ['basename'];
        }*/
        
        $is_grade = "";
        // 帯色設定の有効
        if (session()->get( 'school.login.business_divisions')!= 1) { // 運用区分が塾ではない場合
            $is_grade = 1;
        }
        
        $listStudentType = array ();
//        Because of hyerarchy, may be always empty
//        if (session()->exists( 'school.hierarchy.pschool_parents')) {
//            $language = 1; //
//            if (session()->exists( 'school.login.language')) {
//                $language = session()->get( 'school.login.language');
//            }
//            $school_ids = session ( 'school.hierarchy' ) ['pschool_parents'];
//
//            $list_student_type = MStudentTypeTable::getInstance ()->getStudentTypeList ( $school_ids, $language );
//
//            if (! empty ( $list_student_type )) {
//                $listStudentType = $list_student_type;
//            }
//        }
        //dd($request);
        // テンプレート
        $lan = $this->lan;
        return view ( 'School.School.input_individual', compact ( 'listStudentType', 'request', 'lan', 'auths', 'menu_auth', 'edit_auth' ) );
    }
    public function executeCompleteIndiv(Request $request) {

        // バリデート
        $rules = $this->get_individual_rules($request);
        $messages= $this->get_individual_message($request);
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            $request->offsetSet('errors',$validator->errors());
            return $this->executeInputIndiv ( $request );
        }
        
        // 更新
        $student_type_table = MStudentTypeTable::getInstance ();
        $student_grade_table = StudentGradeTable::getInstance ();
        $pschool_table = PschoolTable::getInstance ();
        $student_type_table->beginTransaction ();
        try {
            // --------
            // 生徒種別
            // --------
            // 削除
            $ids = null;
            if ($request->has ( '_student_type_remove_ids' )) {
                
                $ids = explode ( ',', $request ['_student_type_remove_ids'] );
                foreach ( $ids as $id ) {
                    if (! empty ( $id )) {
                        $arr [] = $id;
                        
                        // タイプにユニークキーつけてるので物理削除にします
                        $student_type_table->logicRemove ( $id );
                    }
                }
            }

            // 登録・更新
            if ($request->has('_studenttype')) {
                foreach ( $request->_studenttype as $value ) {
                    $save = array ();
                    if (! empty ( $value ['id'] )) {
                        // 更新
                        $save ['id'] = $value ['id'];
                    }
                    $save ['pschool_id'] = session()->get( 'school.login.id' );
                    $save ['code'] = $value ['code'];
                    $save ['name'] = $value ['name'];
                    $save ['student_category'] = $value ['student_category'];
                    $save ['remark'] = $value ['remark'];
                    if (array_key_exists ( 'is_display', $value )) {
                        $save ['is_display'] = '1';
                    } else {
                        $save ['is_display'] = '0';
                    }
                    if (array_key_exists ( 'required_fee', $value )) {
                        $save ['required_fee'] = '1';
                    } else {
                        $save ['required_fee'] = '0';
                    }
                    $student_type_table->save ( $save );
                }
            }
            
            // --------
            // 成績表示
            // --------
            $save = array ();
            $save ['id'] = session()->get('school.login.id');
            if ($request->has ( '_ap_record_display' )) {
                $save ['ap_record_display'] = '1';
            } else {
                $save ['ap_record_display'] = '0';
            }
            if ($request->has ( '_exam_record_display' )) {
                $save ['exam_record_display'] = '1';
            } else {
                $save ['exam_record_display'] = '0';
            }
            if ($request->has ( '_school_record_display' )) {
                $save ['school_record_display'] = '1';
            } else {
                $save ['school_record_display'] = '0';
            }
            // 会員証用の画像
//            if (! empty ( $request ['is_new_card_front_upload'] )) { // upload new
//
//                $new_file = FileUpload::copyFromTemp ( $request ['card_front_new_img'], SCHOOL_IMG_DIR );
//                $save ['card_front_img'] = $new_file ['basename'];
//            }
//
//            if (! empty ( $request ['is_new_card_back_upload'] )) { // upload new
//                $new_file = FileUpload::copyFromTemp ( $request ['card_back_new_img'], SCHOOL_IMG_DIR );
//                $save ['card_back_img'] = $new_file ['basename'];
//            }
            
            $pschool_table->save ( $save );
            
            $student_type_table->commit ();
        } catch ( Exception $ex ) {
            $student_type_table->rollBack ();
            view ()->share ( 'regist_error', 1 );
            return $this->executeInputIndiv ( $request );
        }
        
        // 入力画面にとばす
        // session ()->put ( self::SESSION_REGIST_OK, 1 );
        // return $this->executeInputIndiv ( $request );
        return $this->execute ( $request );
    }
    
    /**
     * ログイン権限設定一覧画面
     */
    public function executeAccountlist() {
        /*$auths = AuthConfig::getAuth ( 'school' );
        $this->set_bread_list ( 'school/accountlist', ConstantsModel::$bread_list [$this->current_lang] ['account_list'] );*/
        $this->clearOldInputSession();
        $account = array ();
        $pdata = StaffTable::getInstance ()->getActiveList ( array (
                'pschool_id' => $this->_loginAdmin ['id'] 
        ) );
        foreach ( $pdata as $idx => $row ) {
            $ldata = LoginAccountTable::getInstance ()->getActiveRow ( array (
                    'id' => $row ['login_account_id'] 
            ) );
            $account [$idx] ['staff_name'] = $row ['staff_name'];
            $account [$idx] ['login_id'] = $ldata ['login_id'];
            $account [$idx] ['id'] = $row ['id'];
        }

        // テンプレート
        return view ( 'School.School.account_list' )->with ( 'lan', $this->lan )->with ( 'loginaccount', $account );
    }
    
    /**
     * ログイン権限設定編集画面
     */
    public function executeAccountedit(Request $request) {
        $this->languages_input = ConstantsModel::$languages_input[session()->get( 'school.login.language')];
        if (session ()->has ( 'errors' )) {
            $request->session ()->forget ( 'errors' );
        }
        $id_loginned = session()->get( 'school.login.login_account_id');
        $login_acc_info = LoginAccountTable::getInstance ()->load ( $id_loginned );
        // case add new school
        $link_str = 'school/accountedit';
        if ($request->has ( 'id' )) {
            // case edit account
            // session()->push("editaccount", "1");
            
            $link_str .= '/?id=' . $request->id;
            
            $staff_data = StaffTable::getInstance ()->getRow ( $where = array (
                    "pschool_id" => session()->get ( 'school.login.id' ),
                    "id" => $request->id
            ) );
            $login_data = LoginAccountTable::getInstance ()->load ( $staff_data ['login_account_id'] );
            $staff_data ['authority'] = $login_data ['authority'];
            $staff_data ['edit_authority'] = $login_data ['edit_authority'];
            if (! $request->has( 'login_id' )) {
                $request->merge($staff_data );
                $request->offsetSet('login_id',$login_data['login_id']);
                $request->offsetSet('lang_code',$login_data['lang_code']);
            }
            // メニュー権限
            $menu_setting_arr = $this->getMenuData( $request->id, 2 );
            $description = ConstantsModel::$bread_list [$this->current_lang] ['edit_account_info'];
        } else {
            // メニュー権限
            $menu_setting_arr = $this->getMenuData();
            $description = ConstantsModel::$bread_list [$this->current_lang] ['regist_account_info'];
        }
        // テンプレート

        $school_menu_list = SchoolMenuTable::getInstance ()->getActiveMenuList ( array (
                'pschool_id' => session()->get ('school.login.id')
        ));
        $request->merge($menu_setting_arr);
        return view ( 'School.School.account_edit' )->with ( 'lan', $this->lan )->with('language_input',$this->languages_input);

    }
    
    /**
     * ログインアカウント削除処理
     */
    public function executeAccountdelete(Request $request) {
        if ($request->has ( 'id' )) {
            $staff_tbl = StaffTable::getInstance ();
            $login_tbl = LoginAccountTable::getInstance ();
            
            $staff = $staff_tbl->getRow ( $where = array (
                    "pschool_id" => session()->get( 'school.login.id'),
                    'id' => $request->id
            ));
            
            try {
                $staff_tbl->logicRemove ($request->id);
                $login_tbl->logicRemove ($staff['login_account_id'] );
                $message_type = 33;
            } catch ( Exception $e ) {
                $message_type = 99;
            }
            $request->offsetSet('message_type',$message_type);
        }
        return $this->executeAccountlist ();
    }
    /**
     * ログイン権限設定編集完了
     */
    public function executeAccountcomplete(Request $request) {
        // ヴァりデート
        $pschool_id = session ( 'school.login' ) ['id'];
        $rules = [ 
                'staff_name' => 'required|max:255',
                'login_id' => 'required|email|max:64|unique:login_account,login_id,NULL,id,delete_date,NULL,pschool_id,'.$pschool_id . '',
                'staff_email' => 'nullable|email|unique:staff' 
        ];
        $messsages = array (
                'staff_name.required' => 'required_staff_name_error_title',
                'staff_name.max' => 'length_staff_name_error_title',
                'login_id.required' => 'required_email_error_title',
                'login_id.email' => 'format_email_error_title',
                'login_id.unique' => 'existed_mail_msg',
                'login_id.max' => 'length_email_error_title',
                
                'staff_pass1.required' => 'required_pass_error_title',
                'staff_pass1.min' => 'over_length_password_msg',
                'staff_pass1.max' => 'over_length_password_msg',
                'staff_pass1.regex' => 'password_regex_msg',
                'staff_pass2.same' => 'match_password_error_title',
                
                'staff_email.email' => 'staff_email_format_error',
                'staff_email.unique' => 'staff_email_exists'
        );

        if (! $request->has ( 'id' )) { // 登録
            $rules ['staff_pass1'] = 'required|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/';
            $rules ['staff_pass2'] = 'same:staff_pass1';
        } else if ($request->has ( 'staff_pass1' )) { // 編集：　パスワードチェンジ
            $rules ['staff_pass1'] = 'min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/';
            $rules ['staff_pass2'] = 'same:staff_pass1';
        }

        // 登録を行う
        $staff_tbl = StaffTable::getInstance ();
        $login_account_tbl = LoginAccountTable::getInstance ();
        $school_menu_table = SchoolMenuTable::getInstance ();
        
        $staff_item = array (); // スタッフ情報用
        $account_item = array (); // アカウント情報用
        
        if ($request->has('id')) { // 編集
            
            $staff_item ['id'] = $request->id;
            $staff = $staff_tbl->getRow ( $where = array (
                    "pschool_id" => session()->get( 'school.login.id' ),
                    "id" => $request->id
            ) );
            $account_item ['id'] = $staff ['login_account_id'];
            $rules ['login_id'] = 'required|email|max:64|unique:login_account,login_id,'.$account_item ['id'].',id,pschool_id,'.$pschool_id;
            $rules ['staff_email'] = 'nullable|email|unique:staff,id,' . $request->id;
        }
        $validator = Validator::make ( request()->all(), $rules, $messsages );

        if ($validator->fails()) {
            session ()->push ( 'old_data', $request->input ());
            return redirect ()->back ()->withInput ()->withErrors ( $validator->errors());
        }
        
        // 現状のデータを取得
        try {
            $account_item ['login_id'] = $request->login_id;
            if ($request->has ( 'staff_pass1' )) {
                $account_item ['login_pw'] = md5 ( $request->staff_pass1 );
            }
            $account_item ['auth_type'] = 3;
            // $account_item ['authority'] = $auth ['authority'];
            // $account_item ['edit_authority'] = $auth ['edit_authority'];
            $account_item ['active_flag'] = 1;
            $account_item ['lang_code'] = $request->staff_used_language; // edit 2017-14-06 Toran
            $account_item ['pschool_id'] = $pschool_id; // edit 2017-09-06 kieu.dtd
            $login_account_id = $login_account_tbl->save ( $account_item );
            
            $staff_item ['active_flag'] = 1;
            $staff_item ['pschool_id'] = session ( 'school.login' ) ['id'];
            $staff_item ['login_account_id'] = $login_account_id;
            $staff_item ['staff_name'] = $request->staff_name;
            $staff_item ['staff_email'] = $request->staff_email;
            // $staff_item ['phone_no'] = $request->phone_no;
            // $staff_item ['address'] = $request->address;
            $staff_item ['id'] = $staff_tbl->save ( $staff_item );
            session ()->forget ( 'editaccount' );
            
            // add menu
            // $school_menu_table->beginTransaction ();
            // $menu_select: ['1'=>[...], '2'=>[...],...] #key: master_menu_id
            // $staff_id = ($request->has( 'id' ))? $request->id : $staff_item ['id'];
            $menu_select = $school_menu_table->getActiveMenuListNew ($staff_item ['id'],2); // member_type=2: staff
            $index = 1;

            if ($request->menu_list) {

                foreach ( $request->menu_list as $key => $value ) {
                    $menu = array (
                        "pschool_id" => $staff_item ['id'],
                        "member_type" => 2, // STAFF
                        "master_menu_id" => $key,
//                        "viewable" => isset($request->viewable_list[$key]) ? 1 : 0,
                        "viewable" => 1, // update 2016/10/02
                        "editable" => isset($request->editable_list[$key]) ? 1 : 0,
                        "seq_no" => $index,
                        // "icon_url" => ""
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

        } catch ( Exception $ex ) {
            $this->_logger->error ( $ex->getMessage () );
            $this->assignVars ( 'regist_error', 1 );
            return $this->executeAccountlist ();
        }
        return $this->executeAccountlist ();
    }

    private function get_individual_rules(Request $request){
        $rules = [
            '_studenttype.*.name' =>'required|max:255',
        ];
        $pschool_id = session()->get ( 'school.login.id' );
        foreach ($request->_studenttype as $key => $value){
            if(isset($value['id'])){
                $rules['_studenttype.'.$key.'.code']='required|max:255|unique:m_student_type,code,'.$value['id'].',id,pschool_id,'.$pschool_id;
            }else{
                $rules['_studenttype.'.$key.'.code']='required|max:255|unique:m_student_type,code,NULL,id,pschool_id,'.$pschool_id;
            }
        }
        return $rules;
    }

    private function get_individual_message(Request $request){
    $message = array();
    foreach ($request->_studenttype as $key => $value){
        $message['_studenttype.'.$key.'.code.required'] = "required_idex_error_title,".($key+1);
        $message['_studenttype.'.$key.'.code.max'] = "length_idex_error_title,".($key+1);
        $message['_studenttype.'.$key.'.code.unique'] = "duplicate_member_type_error,".$value["code"];

        $message['_studenttype.'.$key.'.name.required'] = "required_student_name_error_title,".($key+1);
        $message['_studenttype.'.$key.'.name.max'] = "length_student_name_error_title,".($key+1);
    }
    return $message;
    }

    public function executeAdjustNameUpDown(Request $request) {

        // 割引・割増のリスト
        $adjust_names = array ();
        $where = array (
                'pschool_id' => session()->get( 'school.login.id' ),
                'active_flag' => '1' // is active
        );
        $order = array (
                'sort_no' => 'ASC' 
        );
        $adjust_names = InvoiceAdjustNameTable::getInstance ()->getActiveList ( $where, $order );

        // 並び替え
        if ($request->exists ( 'idx' )) {
            
            $adjust_idx = array ();
            $adjust_idy = array ();
            $idx = $request->idx;
            $adjust_idx = array_get ( $adjust_names, $idx );
            $idy = $idx + 1;
            $adjust_idy = array_get ( $adjust_names, $idy );
            $sort_idx = $adjust_idx ['sort_no'];
            $sort_idy = $adjust_idy ['sort_no'];
            
            InvoiceAdjustNameTable::getInstance ()->beginTransaction ();
            
            try {
                $adjust_idx ['sort_no'] = $sort_idy;
                $adjust_idx ['update_date'] = date ( 'Y-m-d H:i:s' );
                $adjust_idx ['update_admin'] = session()->get('school.login.login_account_id');
                $adjust_idx ['pschool_id'] = session()->get( 'school.login.id');
                InvoiceAdjustNameTable::getInstance ()->save ( $adjust_idx );
                
                $adjust_idy ['sort_no'] = $sort_idx;
                $adjust_idy ['update_date'] = date ( 'Y-m-d H:i:s' );
                $adjust_idy ['update_admin'] = session()->get('school.login.login_account_id');
                $adjust_idy ['pschool_id'] =session()->get( 'school.login.id');
                InvoiceAdjustNameTable::getInstance ()->save ( $adjust_idy );
                
                InvoiceAdjustNameTable::getInstance ()->commit ();
            } catch ( Exception $ex ) {
                InvoiceAdjustNameTable::getInstance ()->rollBack ();
                $this->assignVars ( 'regist_error', 88 );
            }
        }
        
        // テンプレート
        return $this->executeAdjustNameInput ( $request );
    }
    public function executeAdjustNameInput(Request $request) {
        /*if (session()->has('errors')) {
            $request->session()->forget('errors');
        }*/
        if (! $request->exists ( '_adjust' ) && !$request->has('errors')) {
            $adjust_names = array ();
            $where = array (
                    'pschool_id' => session()->get ( 'school.login.id' )
            );
            $order = array (
                    'active_flag' => 'DESC',
                    'sort_no' => 'ASC' 
            );
            $adjust_names = InvoiceAdjustNameTable::getInstance ()->getActiveList ( $where, $order );

            if (empty ( $adjust_names )) {
                $adjust_names = array ();
            }
            
            // 自分配下の支部一覧取得（自分も含めて）
            //$lower_ids = $this->getHierarchy ( session ( 'school.login' ) ['id'] );
            $lower_ids = session()->get('school.login.id');

           /* foreach ( $lower_ids as $id_item ) {
                if (! empty ( $ids )) {
                    $ids .= ",";
                }
                $ids .= $id_item;
            }*/


            $adjust_name_array = array ();
            // 自分が定義した内容がどっかで使用されているかチェック
            foreach ( $adjust_names as $adjust_item ) {

                $bExist = false;
                $temp1 = RoutinePaymentTable::getInstance ()->getList ( array (
                        'pschool_id' => $lower_ids,
                        'invoice_adjust_name_id' => $adjust_item ['id'] 
                ) );

                if (! empty ( $temp1 ) || count ( $temp1 ) > 0) {
                    $bExist = true;
                }
                if (! $bExist) {
                    $temp2 = InvoiceItemTable::getInstance ()->getList ( array (
                            'pschool_id' => $lower_ids,
                            'invoice_adjust_name_id' => $adjust_item ['id'] 
                    ) );
                    if (! empty ( $temp2 ) || count ( $temp2 ) > 0) {
                        $bExist = true;
                    }
                }
                if($bExist) $adjust_item ['used'] = $bExist;
                $adjust_name_array [] = $adjust_item;
            }
            $request ['_adjust'] = $adjust_name_array;
        }

        $adjust_types = array (
                ConstantsModel::$adjust_type [1] ['bonus'],
                ConstantsModel::$adjust_type [1] ['discount'] 
        );

        // 国コード
        $country_code = session()->get( 'school.login.country_code');
        // パンくず
//        $this->set_bread_list ( 'school/adjustnameinput', ConstantsModel::$bread_list [$this->current_lang] ['edit_discount_name'] );

        // テンプレート
        $lan = $this->lan;
        $errors = $request->errors;
        return view ( 'School.School.adjust_name_input', compact ( 'adjust_types', 'country_code', 'lan', 'request','errors' ) );
    }

    private function validate_adjustname_rule($request){
        $rules = [
                // '_adjust.*.initial_fee' =>'required|numeric|digits_between:0,10',
                '_adjust.*.initial_fee' =>'required|numeric',
        ];
        $pschool_id = session()->get ( 'school.login.id' );
        foreach ($request->_adjust as $key => $value){
            if(isset($value['id'])){
                //                 $rules['_adjust.'.$key.'.name']='required|max:255|unique:invoice_adjust_name,name,'.$value['id'];
                $rules['_adjust.'.$key.'.name']='required|max:255|unique:invoice_adjust_name,name,'.$value['id'].',id,pschool_id,'.$pschool_id;
                $rules['_adjust.'.$key.'.code']='required|max:50|unique:invoice_adjust_name,code,'.$value['id'].',id,pschool_id,'.$pschool_id;
            }else{
                //                 $rules['_adjust.'.$key.'.name']='required|max:255|unique:invoice_adjust_name,name';
                $rules['_adjust.'.$key.'.name']='required|max:255|unique:invoice_adjust_name,name,NULL,id,pschool_id,'.$pschool_id;
                $rules['_adjust.'.$key.'.code']='required|max:50|unique:invoice_adjust_name,code,NULL,id,pschool_id,'.$pschool_id;
            }
        }
        return $rules;
    }
    
    private function validate_adjustname_message($request){
        $message = array();
        foreach ($request->_adjust as $key => $value){
            $message['_adjust.'.$key.'.initial_fee.required'] = "required_amount_money_error_title,".($key+1);
            $message['_adjust.'.$key.'.initial_fee.numeric'] = "format_amount_money_error_title,".($key+1);
            // $message['_adjust.'.$key.'.initial_fee.digits_between'] = "length_amount_money_error_title,".($key+1);
        
            $message['_adjust.'.$key.'.name.required'] = "required_discount_name_error_title,".($key+1);
            $message['_adjust.'.$key.'.name.max'] = "length_discount_name_error_title,".($key+1);
            $message['_adjust.'.$key.'.name.unique'] = "different_discount_name_error_title,".($key+1);

            $message['_adjust.'.$key.'.code.required'] = "text_code_required_message,".($key+1);
            $message['_adjust.'.$key.'.code.max'] = "text_code_over_message,".($key+1);
            $message['_adjust.'.$key.'.code.unique'] = "text_code_unique_message,".($value['code']);
        }
        return $message;
    }

    public function executeAdjustNameComplete(Request $request) {
        if (! $request->exists ( '_adjust' )) {
            return $this->executeAdjustNameInput ( $request );
        }

        $rules = $this->validate_adjustname_rule($request);
        $messsages = $this->validate_adjustname_message($request);
        $validator = Validator::make(request()->all(), $rules, $messsages);
        
        if ($validator->fails()) {
            //session()->push('old_data', $request->input());
            //return redirect('/school/school/adjustnameinput')->withErrors($validator)->withInput();
            $request->offsetSet('errors',$validator->errors());
            return $this->executeAdjustNameInput($request);
        }
        // 削除
        if ($request ->has('_adjust_name_remove_ids')) {
            $del_list = explode ( ",", $request->_adjust_name_remove_ids);
            
            foreach ( $del_list as $del_id ) {
                InvoiceAdjustNameTable::getInstance ()->beginTransaction ();
                try {
                    $row = array ();
                    $row ['id'] = $del_id;
                    $row ['delete_date'] = date ( 'Y-m-d H:i:s' );
                    $row ['update_date'] = date ( 'Y-m-d H:i:s' );
                    $row ['update_admin'] = $this->_loginAdmin ['id'];
                    
                    InvoiceAdjustNameTable::getInstance ()->save ( $row );
                    InvoiceAdjustNameTable::getInstance ()->commit ();
                } catch ( Exception $ex ) {
                    InvoiceAdjustNameTable::getInstance ()->rollBack ();
                    $err [] = 66;
                }
            }
        }
        
        // 保存
        $sort_no = 0;
        // dd ( $request ['_adjust'] );

        foreach ( $request->_adjust as $row ) {
            $sort_no ++;
            InvoiceAdjustNameTable::getInstance ()->beginTransaction ();
            try {
                if ($row ['id'] == null) {
                    // 新規
                    $row ['id'] = null;
                    $row ['pschool_id'] = session()->get( 'school.login.id');
                    $row ['register_date'] = date ( 'Y-m-d H:i:s' );
                    $row ['register_admin'] = session ( 'school.login' ) ['login_account_id'];
//                    $row ['register_admin'] = $this->_loginAdmin ['id'];
                } else {
                    // 更新
                    $row ['update_date'] = date ( 'Y-m-d H:i:s' );
                    $row ['update_admin'] = session ( 'school.login' ) ['login_account_id'];
//                    $row ['update_admin'] = $this->_loginAdmin ['id'];
                }
                
                $row ['sort_no'] = $sort_no;
                $row ['type'] = empty ( $row ['type'] ) ? '0' : '1';
                $row ['active_flag'] = empty ( $row ['active_flag'] ) ? '0' : '1';
                
                InvoiceAdjustNameTable::getInstance ()->save ( $row );
                InvoiceAdjustNameTable::getInstance ()->commit ();
            } catch ( Exception $ex ) {
                InvoiceAdjustNameTable::getInstance ()->rollBack ();
                $err [] = 66;
            }
        }
        if (! empty ( $err )) {
             $request->errors = $err ;
             return $this->executeAdjustNameInput($request);
        } else {
            $request->regist_message = '77';
            $request->offsetUnset('_adjust');
            return redirect ()->to ('/school/school/adjustnameinput')->withInput();
        }
    }
    public function executeSchoolList() {
        // パンくず
        if ($this->isManager ()) {
//            $this->set_bread_list ( 'school/schoollist', ConstantsModel::$bread_list [1] ['branch_list'] );
            return $this->executeSchoolSearch ();
        } else {
            HeaderUtil::redirect ( $this->get_app_path () . 'school' );
        }
    }

    public function isManager() {
        $hierarchy = HierarchyTable::getInstance ()->getHierarchy ( $this->_loginAdmin ['id'] );
        return $hierarchy ['manage_flag'] == 1 ? true : false;
    }
    public function executeAdditionalCategory(Request $request){
        $pschool_id = session()->get('school.login.id');
        if($request->has('errors')){
            return view( 'School.School.additional_category_input',compact('request'));
        }
        $additionalCateTbl = AdditionalCategoryTable::getInstance();
        $list_category = $additionalCateTbl->getListCateOfPschool($pschool_id);
        $count_current_items = count($list_category);
        $request->offsetSet('count_current_items',$count_current_items);
        $request->offsetSet('list_category',$list_category);
        return view( 'School.School.additional_category_input',compact(''));
    }
    public function executeAdditionalCategoryComplete(Request $request){
        $pschool_id = session()->get('school.login.id');
        //
        if($request->isMethod('post')){
        $count_current_items = count(array_column($request->list_category,'id'));
        $request->offsetSet('count_current_items',$count_current_items);
        }
        //validate
        $rules = $this->validate_addition_rules($request);
        $messages = $this->validate_addition_messages($request);
        $validator =  Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {
            $request->offsetSet('errors',$validator->errors());
            return $this->executeAdditionalCategory($request);
        }

        //
        $additionalCateTbl = AdditionalCategoryTable::getInstance();
        $curr_max_sort = $additionalCateTbl->getMaxSortNo($pschool_id);
        $curr_max_sort+=1;

        // add or edit
        $list_category = $request->list_category;
        try{
            $additionalCateTbl->beginTransaction();
            foreach($list_category as $key =>$value){
                $temp = $value;
                if(!isset($value['active_flag'])){
                    $temp['active_flag'] = 0;
                }
                if(!isset($value['sort_no'])){
                    $temp['sort_no'] = $curr_max_sort;
                    $temp['pschool_id'] = $pschool_id;
                    $curr_max_sort+=1;
                }
                $temp['pschool_id'] = $pschool_id;
                $id = $additionalCateTbl->save($temp);
            }
            $additionalCateTbl->commit();
        }catch (Exception $e){
            $additionalCateTbl->rollBack();
        }
        // remove
        if ($request->has('_additional_cate_remove_ids')) {

            $ids = explode(',', $request->_additional_cate_remove_ids );
            try{
                $additionalCateTbl->beginTransaction();
                foreach ( $ids as $id ) {
                    if (!empty( $id )){
                        $additionalCateTbl->logicRemove ( $id );
                    }
                }
                $additionalCateTbl->commit();
            }catch (Exception $e){
                $additionalCateTbl->rollBack();
            }
        }
        $request->offsetUnset('list_category');
        return redirect()->to('/school/school/')->withInput();
    }
    public function validate_addition_rules($request){
        $rules = array();
        $pschool_id = session()->get ( 'school.login.id' );
        foreach ($request->list_category as $key => $value){
            if(isset($value['id'])){
                $rules['list_category.'.$key.'.name']='required|max:100|unique:additional_category,name,'.$value['id'].',id,pschool_id,'.$pschool_id.',type,'.$value['type'];
                $rules['list_category.'.$key.'.code']='required|max:100|unique:additional_category,code,'.$value['id'].',id,pschool_id,'.$pschool_id.',type,'.$value['type'];
            }else{
                $rules['list_category.'.$key.'.name']='required|max:100|unique:additional_category,name,NULL,id,pschool_id,'.$pschool_id.',type,'.$value['type'];
                $rules['list_category.'.$key.'.code']='required|max:100|unique:additional_category,code,NULL,id,pschool_id,'.$pschool_id.',type,'.$value['type'];
            }
        }
        return $rules;
    }
    public function validate_addition_messages($request){
        $message = array();
        foreach ($request->list_category as $key => $value){
            $message['list_category.'.$key.'.name.required'] = "text_name_required_message,".($key+1);
            $message['list_category.'.$key.'.name.max'] = "text_name_over_message";
            $message['list_category.'.$key.'.name.unique'] = "text_name_unique_message,".($value['name']);
            $message['list_category.'.$key.'.code.required'] = "text_code_required_message,".($key+1);
            $message['list_category.'.$key.'.code.max'] = "text_code_over_message";
            $message['list_category.'.$key.'.code.unique'] = "text_code_unique_message,".($value['code']);
        }
        return $message;
    }
    public function ajax_remove_bank_account(Request $request){
        try{
            if (!$request->ajax() || !$request->has('bank_id')) {
                throw new Exception();
            }

            $pschool_bank_table = PschoolBankAccountTable::getInstance();

            $pschool_bank_table->beginTransaction();
            try{
                $bank_id = $request->bank_id;
                $pschool_bank_table->logicRemove($bank_id);
                $pschool_bank_table->commit();
            }catch (Exception $e){
                $pschool_bank_table->rollBack();
                return response()->json(['status' => false, 'message' => 'Does not exists']);
            }
            return response()->json(['status' => true]);
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => 'Error']);
        }
    }
    public function ajax_save_bank_account(Request $request){
        try{
            if (!$request->ajax() || !$request->has('bank_data') || !$request->has('bank_type')) {
                throw new Exception();
            }
            //bank_account_rules
            if($request->bank_type ==1){
                $rules['bank_code'] = 'required|numeric|digits_between:1,4';
                $rules['bank_name'] =  array('required','max:15','regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u');
                $rules['branch_code'] = 'required|numeric|digits_between:1,3';
                $rules['branch_name'] = array('required','max:15','regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u');
                $rules['bank_account_type'] = 'required';
                $rules['bank_account_number'] = 'required|numeric|digits_between:1,7';
                $rules['bank_account_name'] = array('required','max:30','regex:/^[ア-ン゛゜ァ-ォャ-ョー「」、\　\ ]+$/u');
                $rules['bank_account_name_kana'] = array('max:255','regex:/^[ｰｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u');
            }else{
                $rules['post_account_kigou'] = 'required|numeric|digits_between:1,5';
                $rules['post_account_number'] = 'required|numeric|digits_between:1,8';
                $rules['post_account_name'] = array('max:30','regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u');
            }
            $messages =array(
                    'bank_code.required' => 'required_bank_code_error_title',
                    'bank_code.numeric' => 'format_bank_code_error_title',
                    'bank_code.digits_between' => 'length_bank_code_error_title',
                    'bank_name.required' => 'required_bank_name_error_title',
                    'bank_name.max' => 'length_bank_name_error_title',
                    'bank_name.regex' => 'format_bank_name_error_title',
                    'branch_code.required' => 'required_branch_code_error_title',
                    'branch_code.numeric' => 'format_branch_code_error_title',
                    'branch_code.digits_between' => 'length_branch_code_error_title',
                    'branch_name.required' => 'required_branch_name_error_title',
                    'branch_name.max' => 'length_branch_name_error_title',
                    'branch_name.regex' => 'format_branch_name_error_title',
                    'bank_account_type.required' => 'required_classification_error_title',
                    'bank_account_number.required' => 'required_bank_acc_number_error_title',
                    'bank_account_number.numeric' => 'format_bank_acc_number_error_title',
                    'bank_account_number.digits_between' => 'length_bank_acc_number_error_title',
                    'bank_account_name.required' => 'required_bank_acc_name_error_title',
                    'bank_account_name.max' => 'length_bank_acc_name_error_title',
                    'bank_account_name.regex' => 'account_name_entered_zenkaku',
                    'bank_account_name_kana.regex' => 'account_name_entered_hankaku',
                    'bank_account_name_kana.max' => 'length_bank_acc_name_kana_error_title',
                    'post_account_kigou.required' => 'required_passbook_code_error_title',
                    'post_account_kigou.numeric' => 'format_passbook_code_error_title',
                    'post_account_kigou.digits_between' => 'length_passbook_code_error_title',
                    'post_account_number.required' => 'required_passbook_number_error_title',
                    'post_account_number.numeric' => 'format_passbook_number_error_title',
                    'post_account_number.digits_between' => 'length_passbook_number_error_title',
                    'post_account_name.regex' => 'format_bank_acc_name_error_title',
                    'post_account_name.max' => 'length_bank_acc_name_error_title',
            );
            $bank_save = array();
            //
            $bank_temp = $request->bank_data;
            foreach($request->bank_data as $k => $v){
                if($v[0] == 'post_account_number_1'){
                    $post_1 = $v[1];
                }elseif($v[0] == 'post_account_number_2'){
                    $post_2 = $v[1];
                }elseif($v[0] == 'post_account_type'){
                    $post_account_type = $v[1];
                }elseif($v[0] == 'post_account_kigou' && $v[1]==null){
                    unset($bank_temp[$k]);
                }elseif($v[0] == 'post_account_number' && $v[1]==null){
                    unset($bank_temp[$k]);
                }elseif($v[0] == 'post_account_name' && $v[1]==null){
                    unset($bank_temp[$k]);
                }elseif($v[0] == 'post_account_number'){
                    $post_account_number = $v[1];
                }
            }
            $request->offsetUnset('bank_data');
            $request->offsetSet('bank_data',$bank_temp);

            //
            foreach($request->bank_data as $key=>$bank){
                $bank_save[$bank[0]]=$bank[1]; // => array to save to DB
                $request->offsetSet($bank[0],$bank[1]); // => request for validator
            }
            if(isset($post_account_type) && $post_account_type == 2){
                if(isset($post_1) && isset($post_2)){
                    $post_account_number = $post_1.$post_2;
                }
            }
            if(isset($post_account_number)){
                $request->offsetSet('post_account_number',$post_account_number);
            }
            
            //
            //dd($request->bank_data);
            $validator = Validator::make(request()->all(), $rules, $messages);
            $errors=array();
            $lan = $this->lan;
            if($validator->fails()){
                foreach ($validator->errors()->all() as $error){
                    $errors[]=$lan::get($error);
                }
                return response()->json(['status' => false, 'errors' => $errors]);
            }else{

                if($request->bank_type == 2 || (!empty($post_account_number) && !empty($post_account_type))){
                    $bank_save['post_account_number'] = $post_account_number ;
                    $bank_save['post_account_type'] = $post_account_type ;
                }

                //for bank type
                $bank_save['bank_type'] = $request->bank_type;
                $request->offsetSet('bank_account_type',$request->bank_type);
                //

                $bank_account_table = PschoolBankAccountTable::getInstance();
                $bank_account_table->beginTransaction();
                try{
                    if(!in_array("id",array_keys($bank_save))){
                        $bank_save['pschool_id'] = session()->get('school.login.id');
                    }
                    $id = $bank_account_table->save($bank_save);
                    $bank_account_table->commit();
                    return response()->json(['status' => true, 'message' => $id]);
                }catch(Exception $e1){
                    $bank_account_table->rollBack();
                    return response()->json(['status' => false, 'message' => $e1]);
                }
            }
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e ]);
        }
    }
    public function ajax_change_default_bank_account(Request $request){
        try {
            if (! $request->ajax () || ! $request->has ('bank_id')) {
                throw new Exception();
            }
            $bank_id = $request->bank_id;
            $pschool_id = session()->get('school.login.id');
            $bank_account_table= PschoolBankAccountTable::getInstance();
            $bank_account_table->beginTransaction();
            try{
                $rs=$bank_account_table->setDefaultBankAccount($bank_id,$pschool_id);
                $bank_account_table->commit();
                return response()->json(['status' => false, 'message' => $bank_id ]);

            }catch (Exception $e1){
                $bank_account_table->rollBack();
                return response()->json(['status' => false, 'message' => $e1 ]);
            }
        } catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e ]);
        }
    }
    public function ajax_get_all_bank_account(Request $request){
        try{
            if (! $request->ajax ()) {
                throw new Exception();
            }
            $pschool_id = session()->get("school.login.id");
            $pschool_bank_table = PschoolBankAccountTable::getInstance();
            $json_list_bank = $pschool_bank_table->getListPsBank($pschool_id);
            return response()->json(['status' => true, 'message' => $json_list_bank ]);
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e ]);
        }
    }
    public function ajax_get_payment_method_detail(Request $request){
        try{
            if (! $request->ajax () || ! $request->has('payment_code')) {
                throw new Exception();
            }
            $payment_code = $request->payment_code;
            $pschool_id = session()->get('school.login.id');
            $paymentMethodTable = PaymentMethodTable::getInstance();
            $lan = $this->lan;
            $paymentMethodBankRel = PaymentMethodBankRelTable::getInstance();

            //process only for TRAN BANK
            if($payment_code == Constants::TRAN_BANK ){
                $list_bank = $paymentMethodBankRel->getListBank($pschool_id,Constants::$PAYMENT_TYPE[$payment_code]);
                $res = array();
                $res[$payment_code]['bank_info'] = $list_bank;
                return response()->json(['status' => true, 'message' => $res ]);
            }
            //
            $data = $paymentMethodTable->getPaymentMethodDetail($payment_code,$pschool_id,$lan);



            $payment_agency=array();

            foreach($data as $key=>$value){
                $payment_agency[$value['agency_code']][]= $value;
            }

            foreach ($payment_agency as $agency_code=>$agency_data){
                $list_bank = $paymentMethodBankRel->getListBank($pschool_id,$agency_data[0]['payment_method_id']);
                $payment_agency[$agency_code]['bank_info'] = $list_bank;
                if ($agency_data[0]['payment_method_id'] == 4 && count($list_bank['list_bank']) == 0) { // ゆうちょ振込
                    return response()->json(['status' => false, 'message' => 'error' ]);
                }
            }

//            dd($payment_agency);
            return response()->json(['status' => true, 'message' => $payment_agency ]);
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e ]);
        }
    }
    public function ajax_save_detail_payment(Request $request){
        try{
            if (!$request->has('payment_method_id')) {
                throw new Exception();
            }
            $paymentDataTable = PaymentMethodDataTable::getInstance();
            $paymentMethodTable = PaymentMethodTable::getInstance();
            $paymentMethodPschool = PaymentMethodPschoolTable::getInstance();
            $paymentBankRel = PaymentMethodBankRelTable::getInstance();

            $paymentDataTable->beginTransaction();
            $pschool_id = session()->get('school.login.id');

            // TRAN BANK => save only default bank
            if($request->payment_method_id == Constants::$PAYMENT_TYPE['TRAN_BANK']){
                if ($request->has('default_bank')) {
                    //save PaymentMethodBankRel
                    $bank_rel = array(
                            'pschool_id'  => $pschool_id,
                            'payment_method_id' => $request->payment_method_id,
                            'bank_account_id'   => $request->default_bank,
                    );
                    $current_bankRel = $paymentBankRel->getActiveRow(array('pschool_id'=>$pschool_id,'payment_method_id'=>$request->payment_method_id));

                    if (!empty($current_bankRel)) {
                        $bank_rel['id'] =  $current_bankRel['id'];
                    }
                    $paymentBankRel->save($bank_rel);
                    $paymentDataTable->commit();
                    return response()->json(['status' => true, 'message' => 'success']);
                }
            }
            //validate request
            $paymentMethodValidation = PaymentMethodValidationTable::getInstance();
            $validation = $paymentMethodValidation->getPaymentValidation($request);

            foreach($request->inputs_div as $input=>$value){
                $request->offsetSet($input,$value);
            }

            $validator = Validator::make(request()->all(), $validation['rules'], $validation['messages']);
            if($validator->errors()->all()!= null){
                $lan= $this->lan;
                foreach ($validator->errors()->all() as $error){
                    $errors[]=$lan::get($error);
                }
                return response()->json(['status' => 'validation_fail', 'message' => $errors]);
            }


            try{

                $data_existed_list = array();
                $data_list = $paymentDataTable->getActiveList(array('pschool_id'=> $pschool_id, 'payment_method_id' => $request->payment_method_id,
                    'payment_method_setting_id IN ('.implode(',', $request->payment_setting_id).')' ));

                foreach ($data_list as $row) {
                    $data_existed_list[$row['item_name']] = $row;
                }

                //save PaymentMethodData
                foreach($request->inputs_div as $input=>$value){
                    $payment_data = array();
                    if (array_key_exists($input, $data_existed_list)) {
                        $payment_data['id']=$data_existed_list[$input]['id'];
                        $payment_data['update_date'] = date ( 'Y-m-d H:i:s' );

                    } else {
                        $payment_data['item_name']=$input;
                        $payment_data['payment_method_setting_id'] = ($request->has('payment_setting_id.'.$input))? request('payment_setting_id.'.$input): null;
                        $payment_data['payment_method_id'] = $request->payment_method_id;
                        $payment_data['pschool_id'] = $pschool_id;
                        $payment_data['register_date'] = date ( 'Y-m-d H:i:s' );

                    }
                    $payment_data['item_value']=$value;
                    $paymentDataTable->save($payment_data);
                }

                if ($request->has('default_bank')) {
                    //save PaymentMethodBankRel
                    $bank_rel = array(
                        'pschool_id'  => $pschool_id,
                        'payment_method_id' => $request->payment_method_id,
                        'bank_account_id'   => $request->default_bank,
                    );
                    $current_bankRel = $paymentBankRel->getActiveRow(array('pschool_id'=>$pschool_id,'payment_method_id'=>$request->payment_method_id));

                    if (!empty($current_bankRel)) {
                        $bank_rel['id'] =  $current_bankRel['id'];
                    }
                    $paymentBankRel->save($bank_rel);
                }
                //get payment method info by id
                $method_data= $paymentMethodTable->load($request->payment_method_id);

                // save to pschool table
                $data_method_pschool = array();
                $data_method_pschool['pschool_id'] = $pschool_id;
                $data_method_pschool['payment_agency_id']=$request->agency_id;
                $data_method_pschool['payment_method_code']=$method_data['code'];
                $data_method_pschool['payment_method_name']=$method_data['name'];

                $pschool_data = $paymentMethodPschool->updatePschoolMethod($pschool_id,$data_method_pschool,false);
                if(!$pschool_data){
                    $paymentDataTable->rollBack();
                    return response()->json(['status' => false, 'message' => 'Error when save method to school']);
                }
                // commit
                $paymentDataTable->commit();
                return response()->json(['status' => true, 'message' => 'success']);
            }catch(Rxception $e1){
                $paymentDataTable->rollBack();
                return response()->json(['status' => false, 'message' => $e1]);
            }
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e ]);
        }
    }
    public function ajax_get_pschool_method_detail(Request $request){
        try{
            if (!$request->ajax ()) {
                throw new Exception();
            }
            $paymentMethodTabel = PaymentMethodTable::getInstance();
            $pschool_id= session()->get('school.login.id');
            $lan = $this->lan;
            $methods=  $paymentMethodTabel->getListMethodDefaultPschool($pschool_id,$lan);
            if($methods){
                return response()->json(['status' => true, 'message' =>$methods ]);
            }
        }catch(Exception $e){
            return response()->json(['status' => false, 'message' => $e ]);
        }

    }
    public function ajax_set_payment_method(Request $request){
        try{
            if (!$request->ajax () || !$request->has('payment_method_id') || !$request->has('agency_id')) {
                throw new Exception();
            }
            $paymentMethodPschool = PaymentMethodPschoolTable::getInstance();
            $pschool_id= session()->get('school.login.id');
            $method_data['pschool_id'] =$pschool_id;
            $method_data['payment_method_id'] = $request->payment_method_id;
            $method_data['payment_agency_id'] = $request->agency_id;
            $method_data['is_delete'] = $request->is_delete;
            $method_data['sort_no'] = PaymentMethodTable::getInstance()->load($request->payment_method_id)['sort_no'];
            //
            $paymentMethodPschool->beginTransaction();
            $paymentMethodPschool->updatePschoolMethod($pschool_id,$method_data,true);
            $paymentMethodPschool->commit();
            return response()->json(['status' => true, 'message' =>'success' ]);

        }catch(Exception $e){
            $paymentMethodPschool->rollBack();
            return response()->json(['status' => false, 'message' => $e ]);
        }
    }
    public function ajax_set_default_bank_method(Request $request){
       try{
           if (!$request->ajax () || !$request->has('payment_method_id') || !$request->has('bank_id')) {
               throw new Exception();
           }
           $method_data = array();
           $pschool_id = session()->get('school.login.id');
           $method_data['pschool_id'] =$pschool_id;
           $method_data['payment_method_id'] = $request->payment_method_id;
           $method_data['bank_account_id'] = $request->bank_id;
           try{
               $paymentBankRel = PaymentMethodBankRelTable::getInstance();
               $paymentBankRel->beginTransaction();
               $id = $paymentBankRel->updateDefaultBank($method_data);
               $paymentBankRel->commit();
               return response()->json(['status' => true, 'message' =>$id ]);
           }catch (Exception $e1){
               $paymentBankRel->rollBack();
               return response()->json(['status' => false, 'message' => $e1 ]);
           }

       }catch(Exception $e){
           return response()->json(['status' => false, 'message' => $e ]);
       }
    }

    public function executeStudentSetting(Request $request){

        $pschool_id = session()->get('school.login.id');
        $school_menu_table = SchoolMenuTable::getInstance();
        $mStudentTypeTable = MStudentTypeTable::getInstance();
        $menu_setting_arr = $this->getMenuData($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT);
        $list_student_type = $mStudentTypeTable->getStudentTypeName($pschool_id);

        $m_student_type_id = $request->m_student_type_id;

        if(!empty($menu_setting_arr)){
            if(!empty($list_student_type)){

                $m_student_type_id = empty($m_student_type_id)?$list_student_type[0]['id']:$m_student_type_id;
                $menu_setting_arr = $this->getMenuData($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT,$m_student_type_id);
                $menu_select = $school_menu_table->getActiveMenuListNew($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT, $m_student_type_id); // member_type= 9: student

            }else{
                $menu_select = $school_menu_table->getActiveMenuListNew($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT); // member_type= 9: student
            }

            // GET ALL MENU OF STUDENT BY CONSTANT
            foreach ($menu_setting_arr['parentMenuList'] as $key => $value){
                if(!in_array($value['menu_name_key'], Constants::DEFAULT_STUDENT_MENU_KEY)){
                    unset($menu_setting_arr['parentMenuList'][$key]);
                }
            }

            if(empty($menu_select)){
                //  REMOVE DEFAULT MENU
                foreach ($menu_setting_arr['defaultMenuList'] as $key => $value){
                    unset($menu_setting_arr['defaultMenuList'][$key]);
                }
            }
        }

        $request->offsetSet('id', session()->get('school.login.id'));
        $request->merge($menu_setting_arr);

        return view('School.School.student_setting_menu', compact('request','list_student_type','m_student_type_id'));
    }

    public function ajax_load_student_menu(Request $request){
        try{
            if (!$request->ajax() || !$request->has('student_type')) {
                throw new Exception();
            }

            $pschool_id = session()->get('school.login.id');
            $school_menu_table = SchoolMenuTable::getInstance();
            $mStudentTypeTable = MStudentTypeTable::getInstance();

            $student_type = $request->student_type;
            $menu_setting_arr = $this->getMenuData($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT);

            $list_student_type = $mStudentTypeTable->getStudentTypeName($pschool_id);

            if(empty($list_student_type)){
                throw new Exception();
            }

            $type_exists = false;
            foreach ($list_student_type as $k => $v){
                if($v['id']==$student_type){
                    $type_exists = true;
                    break 1;
                }
            }

            if(!$type_exists){
                throw new Exception();
            }

            if(!empty($menu_setting_arr)){
                $menu_setting_arr = $this->getMenuData($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT,$student_type );
                $menu_select = $school_menu_table->getActiveMenuListNew($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT, $student_type); // member_type= 9: student

                // GET ALL MENU OF STUDENT BY CONSTANT
                foreach ($menu_setting_arr['parentMenuList'] as $key => $value){
                    if(!in_array($value['menu_name_key'], Constants::DEFAULT_STUDENT_MENU_KEY)){
                        unset($menu_setting_arr['parentMenuList'][$key]);
                    }
                }

                if(empty($menu_select)){
                    //  REMOVE DEFAULT MENU
                    foreach ($menu_setting_arr['defaultMenuList'] as $key => $value){
                        unset($menu_setting_arr['defaultMenuList'][$key]);
                    }
                }
            }

            $request->offsetSet('id', session()->get('school.login.id'));
            $request->merge($menu_setting_arr);

            return view('_parts.menu_auth_student', compact('request'));

        }catch (Exception $e){
            throw new Exception();
        }
    }

    public function saveStudentMenu (Request $request){

        DB::beginTransaction();
        try{
            // TODO save menu settings
            $pschool_id = session()->get('school.login.id');
            $m_student_type_id = $request->m_student_type_id;

            $school_menu_table = SchoolMenuTable::getInstance ();
            $menu_select = $school_menu_table->getActiveMenuListNew($pschool_id, ConstantsModel::$LOGIN_AUTH_STUDENT, $m_student_type_id); // member_type= 10: parent

            $index = 1;
            if ($request->menu_list) {

                foreach ( $request->menu_list as $key => $value ) {
                    $menu = array (
                            "pschool_id" => $pschool_id, //
                            "member_type" => ConstantsModel::$LOGIN_AUTH_STUDENT, // 10 : parent
                            "master_menu_id" => $key,
                            "viewable" => 1,
                            "editable" => isset($request->editable_list[$key]) ? 1 : 0,
                            "seq_no" => $index,
                            "active_flag" => 1,
                            "m_student_type_id" =>$m_student_type_id,
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
        }catch(Exception $e){
            DB::rollBack();
        }

        $request->offsetSet('m_student_type_id', $m_student_type_id);
        DB::commit();

        return $this->executeStudentSetting($request);
    }

    public function executeParentSetting(Request $request){

        $pschool_id = session()->get('school.login.id');
        $school_menu_table = SchoolMenuTable::getInstance ();

        $menu_setting_arr = $this->getMenuData($pschool_id, ConstantsModel::$LOGIN_AUTH_PARENT);

        if(!empty($menu_setting_arr)){
            $menu_select = $school_menu_table->getActiveMenuListNew($pschool_id, ConstantsModel::$LOGIN_AUTH_PARENT); // member_type= 10: parent

            // GET ALL MENU OF STUDENT BY CONSTANT
            foreach ($menu_setting_arr['parentMenuList'] as $key => $value){
                if(!in_array($value['menu_name_key'], Constants::DEFAULT_PARENT_MENU_KEY)){
                    unset($menu_setting_arr['parentMenuList'][$key]);
                }
            }

            if(empty($menu_select)){
                //  REMOVE DEFAULT MENU
                foreach ($menu_setting_arr['defaultMenuList'] as $key => $value){
                    unset($menu_setting_arr['defaultMenuList'][$key]);
                }
            }
        }

        $request->offsetSet('id', session()->get('school.login.id'));
        $request->merge($menu_setting_arr);

        return view('School.School.parent_setting_menu', compact('request'));
    }

    public function saveParentMenu (Request $request){

        DB::beginTransaction();
        try{
            // TODO save menu settings
            $pschool_id = session()->get('school.login.id');
            $school_menu_table = SchoolMenuTable::getInstance ();
            $menu_select = $school_menu_table->getActiveMenuListNew($pschool_id, ConstantsModel::$LOGIN_AUTH_PARENT); // member_type= 10: parent

            $index = 1;
            if ($request->menu_list) {

                foreach ( $request->menu_list as $key => $value ) {
                    $menu = array (
                            "pschool_id" => $pschool_id, //
                            "member_type" => ConstantsModel::$LOGIN_AUTH_PARENT, // 10 : parent
                            "master_menu_id" => $key,
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
        }catch(Exception $e){
            DB::rollBack();
        }

        DB::commit();

        return $this->executeParentSetting($request);
    }

}