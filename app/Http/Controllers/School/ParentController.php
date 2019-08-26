<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\AdditionalCategoryTable;
use App\Model\InvoiceHeaderTable;
use App\Model\MPrefTable;
use App\Model\MCityTable;
use App\Model\AdditionalCategoryRelTable;
use App\Model\MStudentTypeTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\StudentTable;
use App\Http\Controllers\Common\AuthConfig;
use App\Model\InvoiceAdjustNameTable;
use App\Model\ParentTable;
use App\Model\ParentBankAccountTable;
use App\Model\LoginAccountTable;
use App\Model\SchoolMenuTable;
use App\Model\PschoolTable;
use App\Model\RoutinePaymentTable;
use App\Model\HierarchyTable;
use App\Model\StudentClassTable;
use App\Model\ZipcodeAddressTable;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Http\Request;
use App\ConstantsModel;
use App\Http\Controllers\Common\ValidateRequest;
use App\Http\Controllers\Common\Validaters;
use App\Lang;
use App\Model\LoginAccountTempTable;
use Validator;
/**
 * 請求者情報
 */
class ParentController extends _BaseSchoolController {
    private static $TOP_URL = 'school/parent';
    protected static $ACTION_URL = 'parent';
    private static $STUDENT_ACTION = 'school/student';
    private static $TEMPLATE_URL = 'school/parent';
    protected static $LANG_URL = 'parent';
    private $template_url;
    private $parent_search;
    private $lan;
    private $bank_account_type_list;
    const SESSION_REQUESTS_KEY = 'session_school_student_requests';
    const SESSION_SEARCH_KEY = 'session_school_parent_search_key';
    const SESSION_REQUEST_VALUE = 'session_school_request_value';
    private $_hankakumojiRegix = '^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$';
    private $_zenkakukatakanaRegix = '^[ア-ン゛゜ァ-ォャ-ョー「」、\　\ ]+$';
    private $_zenkakuRegix = '^[\p{Hiragana}\p{Katakana}\p{Han}「」、\　\ ]+$';
    private $_parent_search_item = ['_c'];
    private $_parent_search_session_key = 'parent_search_form';

    public function __construct() {
        parent::__construct ();
        $message_content = parent::getMessageLocale ();
        $this->lan = new Lang ( $message_content );
        $this->bank_account_type_list = parent::getBankAccountList ();

//do not use hyerarchy this time
//        if (! PschoolTable::getInstance ()->isNormalShibu ( session ( 'school.login' ) ['id'] )) {
//            return redirect ( $this->get_app_path () );
//        }
        return redirect ( $this->get_app_path ());

    }
    public function execute(Request $request) {
        // メニューから遷移のとき、セッションに検索条件があったらそれをクリアする
        if (session()->exists(self::SESSION_SEARCH_KEY )) {
            session()->forget(self::SESSION_SEARCH_KEY );
        }
        if (session()->exists( self::SESSION_REQUESTS_KEY )) {
            session()->forget( self::SESSION_REQUESTS_KEY );
            view()->share('parent_search', "");
        }
        
        return $this->executeList ( $request );
    }
    public function executeList2(Request $request) {
    	$request->student_id = $request->id;
    	
        if (!session()->exists( self::SESSION_REQUESTS_KEY )) {
            session()->forget( self::SESSION_REQUEST_VALUE );
            session()->forget( self::SESSION_SEARCH_KEY );
        }
        session()->put(self::SESSION_REQUESTS_KEY, 1);
        if (session()->exists( self::SESSION_REQUEST_VALUE )) {
            $request = array_merge ( $request, session()->get( self::SESSION_REQUEST_VALUE ) );
        } else {
            $request->offsetUnset('_c'); // _c : array of search condition from view
            session()->put(self::SESSION_REQUEST_VALUE, $request);
        }
        view()->share('parent_search', 1);
        return $this->executeList ($request);
    }
    public function executeList(Request $request) {
        $this->_initSearchDataFromSession($this->_parent_search_item, $this->_parent_search_session_key);

        if (!$request ->has('_c')) {
            if (session()->exists( self::SESSION_SEARCH_KEY )) {
                $request->_c = session()->get ( self::SESSION_SEARCH_KEY );
            } else {
                if (session()->exists( self::SESSION_REQUESTS_KEY )) {
                    if (session()->get(self::SESSION_REQUESTS_KEY) == 1) { // 請求者選択時
                        if ($request->has ('student_kana')) {
                        $temp_search_name = array(
                            "search_name" => mb_substr($request->student_kana, 0, 1)
                        );
                            $request->offsetSet('_c',$temp_search_name);
                        }
                    }
                }
                // 検索条件が設定されていない場合
                if (! $request->offsetExists('_c')) {
                    $request->offsetSet('_c',array ());
                }
            }
        }
        
       session()->put(self::SESSION_SEARCH_KEY, $request->_c);
        
        // 生徒情報がある場合親とのリンクを有効化する
        if (count ( $request )) {
            $request ->offsetSet('link_enable',1);
        }
        
        // 都道府県
        $prefList = MPrefTable::getInstance ()->getList ();
        $dispPrefList = array ();
        if (! empty ( $prefList )) {
            foreach ( $prefList as $idex => $row ) {
                $dispPrefList [$row ['id']] = $row ['name'];
            }
        }
        // 市区町村
        $cityList = MCityTable::getInstance ()->getList ();
        $dispCityList = array ();
        if (! empty ( $cityList )) {
            foreach ( $cityList as $idex => $row ) {
                $dispCityList [$row ['id']] = $row ['name'];
            }
        }
        
        // ---------------
        // 検索結果の取得
        // ---------------
        $search = $request ->_c;

        $parent_list = ParentTable::getInstance ()->getParentList2 ( $search );
        foreach ( $parent_list as &$parent ) {
            $parent ['orgparent_id'] = $parent ['id'];
            unset ( $parent ['id'] );
        }
        // ---------------
        // 表示情報の設定
        // ---------------
        
        $prefList = $dispPrefList;
        $cityList = $dispCityList;

        // 生徒区分
        $schoolCategory = ConstantsModel::$dispSchoolCategory;
        $request->offsetUnset('function');
        $lan = $this->lan;

        //Toran generate current list for next and previous
        $curr_list = array();
        foreach ($parent_list as $k=>$v){
            $curr_list[$k]=$v['orgparent_id'];
        }
        session()->forget('parent_detail_pos');
        session()->forget('current_list');
        session()->put('current_list',$curr_list);
        $request->offsetSet('total_records',count($parent_list));
        //get list m_student_type
        $mStudentTbl = MStudentTypeTable::getInstance();
        $list_student_type = $mStudentTbl->getListStudentTypeByPschool(session()->get('school.login.id'));
        // 請求者検索画面に飛ばす
        if (session()->get('school.login.country_code') == 81) {
            // 支払方法
            $pschoolMethod = PaymentMethodPschoolTable::getInstance()->getListPaymentMethod(session()->get('school.login.id'));

            foreach ($pschoolMethod as $k=>$v){
                $paymentMethodList[$v['payment_method_value']] = $v['payment_method_name'];
            }

            return view ( 'School.Parent.index', compact ( 'prefList', 'request', 'lan', 'auths', 'cityList', 'parent_list', 'schoolCategory', 'paymentMethodList','list_student_type' ) );
        }
    }
    public function executeEntry(Request $request) {
        $init = false;
        $link_str = self::$ACTION_URL.'/entry';
        $request->offsetSet('function',1);
        if ($request->has ( 'orgparent_id' ) && !$request->has('errors')) {
            $link_str .= '?orgparent_id=' . $request->orgparent_id;
            $request->offsetSet('function',2);

            $init = true;
            $parent = ParentTable::getInstance ()->getRow ( array (
                    'id' => $request->orgparent_id,
                    'pschool_id' => session()->get ('school.login.id')
            ));

            $parent_bank_account = ParentBankAccountTable::getInstance ()->getActiveRow( array (
                    'parent_id' => $request->orgparent_id
            ) );
            //dd($parent_bank_account);
            $login_id = $parent ['login_account_id'];
            $parent_login = LoginAccountTable::getInstance ()->load ( $login_id );
            if (isset ( $parent_bank_account )) {
                $parent ['bank_type'] = $parent_bank_account ['bank_type'];
                $parent ['bank_code'] = $parent_bank_account ['bank_code'];
                $parent ['bank_name'] = $parent_bank_account ['bank_name'];
                $parent ['branch_code'] = $parent_bank_account ['branch_code'];
                $parent ['branch_name'] = $parent_bank_account ['branch_name'];
                $parent ['bank_account_type'] = $parent_bank_account ['bank_account_type'];
                $parent ['bank_account_number'] = $parent_bank_account ['bank_account_number'];
                $parent ['bank_account_name'] = $parent_bank_account ['bank_account_name'];
                
                $parent ['bank_account_name_kana'] = $parent_bank_account ['bank_account_name_kana'];
                $parent ['post_account_kigou'] = $parent_bank_account ['post_account_kigou'];
                $parent ['post_account_number'] = $parent_bank_account ['post_account_number'];
                $parent ['post_account_name'] = $parent_bank_account ['post_account_name'];
                $parent ['post_account_type'] = $parent_bank_account ['post_account_type'];
                if(isset($parent_bank_account ['post_account_number'])){
                    $parent ['post_account_number_1'] = substr($parent_bank_account ['post_account_number'],0,1);
                    $parent ['post_account_number_2'] = substr($parent_bank_account ['post_account_number'],1,7);
                }
            }
            $request->merge ( $parent );
        }
        
        if ($request->has ( 'parent' )) {
            $request->parent_id = $request->parent ['id'];
            $request->offsetUnset('parent');
        }
        $pschool_id = session()->get( 'school.login.id' );
        
        $request->offsetSet('pschool_id',$pschool_id);
        // 都道府県
        $prefList = MPrefTable::getInstance ()->getList ();
        $dispPrefList = array ();
        if (! empty ( $prefList )) {
            foreach ( $prefList as $idex => $row ) {
                $dispPrefList [$row ['id']] = $row ['name'];
            }
        }
        
        // 住所市区町村
        $dispCityList = array ();
        if ($request->has ( 'pref_id' )) {
            $cityList = MCityTable::getInstance ()->getListByPref ( $request->pref_id );
            if (! empty ( $cityList )) {
                foreach ( $cityList as $idex => $row ) {
                    $dispCityList [$row ['id']] = $row ['name'];
                }
            }
        }
        // 請求先住所-住所市区町村
        $dispCityOtherList = array ();
        if ($request->has ( 'pref_id_other' )) {
            $cityList = MCityTable::getInstance ()->getListByPref ( $request->pref_id_other );
            if (! empty ( $cityList )) {
                foreach ( $cityList as $idex => $row ) {
                    $dispCityOtherList [$row ['id']] = $row ['name'];
                }
            }
        }
        $prefList = $dispPrefList;
        $cityList = $dispCityList;
        $cityOtherList = $dispCityOtherList;

        // /////////////////////////////////////////////////////////////////////
        // 受講料以外にかかる費用
        // /////////////////////////////////////////////////////////////////////
        
        $month_list = ConstantsModel::$month_listEx [session()->get('school.login.language')];
        
        $parent_ids = HierarchyTable::getInstance ()->getParentPschoolIds (session()->get('school.login.id'));
        $parent_ids [] = session()->get('school.login.id');

        $invoice_adjust_list = InvoiceAdjustNameTable::getInstance ()->getInvoiceAdjustList ($parent_ids);

        if (! $request->offsetExists('payment') && $request->has ( 'orgparent_id' ) ) {
            $data_id = $request->has('id') ?  $request->id : $request->orgparent_id;
            $routine_payments = RoutinePaymentTable::getInstance ()->getRoutinePayemntList ( session()->get ('school.login.id'), 3, $data_id ); // 3 => data_div = 3 : parent type
            $payment = array ();
            foreach ( $routine_payments as $payment_item ) {
                $row = array ();
                $row ['payment_month'] = $payment_item ['month'];
                $row ['payment_adjust'] = $payment_item ['invoice_adjust_name_id'];
                $row ['payment_adjust_name'] = $payment_item ['name'];
                $row ['payment_fee'] = $payment_item ['adjust_fee'];
                $row ['payment_id'] = $payment_item ['id'];
                $row ['payment_del'] = 0;
                $payment [] = $row;
            }
            if (count ( $payment ) > 0) {
//                $request->payment = $payment;
                $request->offsetSet('payment', $payment);
            }
        }
        //additional categories
        $additionalCategories = AdditionalCategoryTable::getInstance()->getListData(['type' => ConstantsModel::$ADDITIONAL_CATEGORY_PARENT, 'related_id' => $request->id], true);

        $lan = $this->lan;
        $bank_account_type_list = $this->bank_account_type_list;
        // 生徒登録画面に飛ばす
        $errors = $request->errors;

        //get detail payment method of pschool
        $payment_method_list = PaymentMethodPschoolTable::getInstance()->getListPaymentMethod($pschool_id);
        if (session()->get( 'school.login.country_code')== 81) {
            return view ( 'School.Parent.entry', compact ( 'prefList', 'cityList', 'cityOtherList', 'month_list', 'invoice_adjust_list', 'lan', 'request', 'bank_account_type_list','errors','additionalCategories','payment_method_list') );
        }
    }
//    Combine comfirm and complete

    public function executeComplete(Request $request) {

        $message_type = 0;
        // エラーメッセージを表示
        $old_req = $request;
        $parent_tbl = ParentTable::getInstance ();
        $parent_bank_tbl = ParentBankAccountTable::getInstance ();
        $login_tbl = LoginAccountTable::getInstance ();
        $login_temp_tbl = LoginAccountTempTable::getInstance ();

        // function =3 : case delete parent
        if ($request->function == 3) {
            // 処理不要
            $err = $this->deleteParent ($request->orgparent_id);
        } else {
            if($request->post_account_type == 2){
                $request->offsetSet('post_account_number',$request->post_account_number_1 . $request->post_account_number_2);
            }
            if (session()->get ( 'school.login.country_code' ) == 81) {
                $rules = $this->validate_parent_rules($request);
                $message = $this->validate_parent_messages($request);
                $validator = Validator::make(request()->all(), $rules, $message);

                // check syntax
                if(($request->invoice_type ==2) && session()->get ( 'school.login.country_code' )== 81){
                    if($request->bank_type==1){
                        if(!mb_ereg($this->_hankakumojiRegix, $request->bank_name)){
                            $validator->errors()->add('bank_name', 'financial_institution_name_alphanumeric_kana');
                        }
                        if(!mb_ereg($this->_hankakumojiRegix, $request->branch_name)){
                            $validator->errors()->add('branch_name', 'branch_name_alphanumeric_kana');
                        }
                        if($request->has('bank_account_name') && !mb_ereg($this->_zenkakukatakanaRegix, $request->bank_account_name)){
                            $validator->errors()->add('bank_account_name', 'account_name_entered_zenkaku');
                        }
                        if($request->has('bank_account_name_kana') && !mb_ereg($this->_hankakumojiRegix, $request->bank_account_name_kana)){
                            $validator->errors()->add('bank_account_name', 'account_kana_entered_alphanumeric_kana');
                        }
                    }elseif($request->bank_type==2){
                        if(!mb_ereg($this->_hankakumojiRegix, $request->post_account_name)){
                            $validator->errors()->add('post_account_name', 'passbook_name_entered_alphanumeric_kana');
                        }
                    }
                }
                if($request->has('name_kana')){
                    if(!mb_ereg($this->_zenkakukatakanaRegix, $request->name_kana)){
                        $validator->errors()->add('name_kana', 'character_valid_claimant_name_kana');
                    }
                }
                if($validator->errors()->all()!= null){
                        $request->offsetSet('errors', $validator->errors());
                        return $this->executeEntry($request);
                }
                if ($validator->fails()) {
                    $request->offsetSet('errors',$validator->errors());
                    return $this->executeEntry($request);
                }

            }

            if ($request->has ( 'payment' )) {
            	$temp_adjust = array ();
            	foreach ( $request->payment as $payment_item ) {
            		if (! isset ( $payment_item ['payment_del'] ) || (isset ( $payment_item ['payment_del'] ) && empty ( $payment_item ['payment_del'] ))) {
            			$temp_adjust [] = $payment_item;
            		}
            	}
            	$request->offsetUnset('payment ');

            	if (count ( $temp_adjust ) > 0) {
            		$request->offsetSet('payment',$temp_adjust);
            	}
            }

            $parent_tbl->beginTransaction ();
            try {
                $login = array (
                        'login_id' => $request->parent_mailaddress1,
                        'active_flag' => 1,
                        'auth_type' => 10, // Parent authority
                        'register_admin' => session()->get('school.login.login_account_id')
                );
                $login_temp = array (
                        'login_id' => $request->parent_mailaddress1,
                        'register_admin' => session()->get( 'school.login.login_account_id' )
                );
                $login ['login_pw'] = "";
                $login_temp ['login_pw_base64'] = "";
                $old_payment_id = null;
                if ($request->has ( 'orgparent_id' )) {
                    $parent = $parent_tbl->getRow ( array (
                            'id' => $request->orgparent_id,
                            'pschool_id' => session()->get ( 'school.login.id' )
                    ) );
                    $parent_bank = $parent_bank_tbl->getRow ( array (
                            'parent_id' => $request->orgparent_id
                    ) );
                    $login ['id'] = $parent ['login_account_id'];
                    if ($request->parent_pass) {
                        $login ['login_pw'] = md5 ( $request->parent_pass);
                    } else {
                        $old_login = $login_tbl->load ($request->login_account_id);
                        $old_login_temp = $login_temp_tbl->getLoginAccountTempByLoginAccountId($request ['login_account_id']);
                        $login ['login_pw'] = $old_login ['login_pw'] ? $old_login ['login_pw'] : "";
                        $login_temp ['login_pw_base64'] = $old_login_temp ['login_pw_base64'] ? $old_login_temp ['login_pw_base64'] : "";
                    }

                    $old_payment_id = $parent['invoice_type'];

                    // update mail_information in invoice_header when edit
//                    $old_mail_infor = $parent['mail_infomation'];
//
//                    if($old_mail_infor != $request->mail_infomation){
//
//                        InvoiceHeaderTable::getInstance()->updateMailAnnouce($request->orgparent_id,$request->mail_infomation);
//                    }

                } else {
                    if ($request->has ( 'parent_pass' )) {
                        $login ['login_pw'] = md5 ( $request->parent_pass);
                        $login_temp ['login_pw_base64'] = base64_encode($request->parent_pass);
                    }
                }
                $login['pschool_id'] = session()->get ( 'school.login.id' ); // edit 2017-09-06 kieu.dtd
                $login_id = $login_tbl->save ( $login );
                $login_temp['login_account_id'] = $login_id;
                $login_temp_id = $login_temp_tbl->save ( $login_temp );
                $request ['login_account_id'] = $login_id;
                
                $savea ['login_account_id'] = $login_id;
                $savea ['login_pw'] = $login ['login_pw'];
                $savea ['parent_name'] = $request ['parent_name'];
                $savea ['name_kana'] = session()->get ( 'school.login.country_code' )== 81 ? $request->name_kana : $request->parent_name;
                $savea ['parent_mailaddress1'] = $request->parent_mailaddress1;
                $savea ['parent_mailaddress2'] = $request->parent_mailaddress2;
                $savea ['pref_id'] = session()->get ( 'school.login.country_code' ) == 81 ? $request->pref_id : NULL;
                $savea ['city_id'] = session()->get ( 'school.login.country_code' ) == 81 ? $request->city_id : NULL;
                if (session()->get ( 'school.login.country_code' )== 81) {
                    $savea ['zip_code1'] = $request->zip_code1;
                    $savea ['zip_code2'] = $request->zip_code2;
                    $savea ['zip_code'] = $request->zip_code1 . $request->zip_code2;
                } else {
                    $savea ['zip_code'] = $request->zip_code;
                }
                $savea ['address'] = $request->address;
                $savea ['building'] = $request->building;
                $savea ['phone_no'] = $request->phone_no;
                $savea ['invoice_type'] = $request->invoice_type;
                $savea ['mail_infomation'] = $request->mail_infomation;
                $savea ['handset_no'] = $request->handset_no;
                $savea ['memo'] = $request->memo;

                if ($request->invoice_type == 2) {
                    $saveb ['bank_type'] = $request->bank_type;
                    $saveb ['bank_code'] = $request->bank_code;
                    $saveb ['bank_name'] = $request->bank_name;
                    $saveb ['branch_code'] = $request->branch_code;
                    $saveb ['branch_name'] = $request->branch_name;
                    $saveb ['bank_account_type'] = $request->bank_account_type;
                    $saveb ['bank_account_number'] = $request->bank_account_number;
                    $saveb ['bank_account_name'] = $request->bank_account_name;
                    $saveb ['bank_account_name_kana'] = $request->bank_account_name_kana;
                    $savea ['check_register'] = 1;
                }
                else {
                    $savea ['check_register'] = null;
                }

                $saveb ['post_account_type'] = $request->post_account_type;
                if($request->post_account_type == 1){
                    $saveb ['post_account_number'] = $request->post_account_number;
                }elseif($request->post_account_type == 2){
                    $saveb ['post_account_number'] = $request->post_account_number_1 . $request->post_account_number_2;
                }
                $saveb ['post_account_kigou'] = $request->post_account_kigou;
                $saveb ['post_account_name'] = $request->post_account_name;

                // $parent = $request;
                $savea ['pschool_id'] = session()->get ( 'school.login.id' );
                $saveb ['pschool_id'] = session()->get ( 'school.login.id' );
                if ($request->has ( 'orgparent_id' )) {
                    $savea ['id'] = $request->orgparent_id;
                    if ($parent_bank != NULL) {
                        $saveb ['id'] = $parent_bank ['id'];
                    }
                }

                // 2017-08-11 KIEUDTD add other info for print label
                $savea['other_name_flag'] = $request->other_name_flag; // 請求先宛名
                if ($request->other_name_flag == 1) {
                    $savea['parent_name_other'] = $request->parent_name_other;
                }
                $savea['other_address_flag'] = $request->other_address_flag; // 請求先住所
                if ($request->other_address_flag == 1) {
                    $savea['zip_code1_other'] = $request->zip_code1_other;
                    $savea['zip_code2_other'] = $request->zip_code2_other;
                    $savea['pref_id_other'] = $request->pref_id_other;
                    $savea['city_id_other'] = $request->city_id_other;
                    $savea['address_other'] = $request->address_other;
                    $savea['building_other'] = $request->building_other;
                }

                $parent_id = $parent_tbl->save ( $savea );
//                if ($parent_id != NULL && $savea ['invoice_type'] == 2) {
                if ($parent_id != NULL && $savea ['invoice_type'] != 1) {
                    $saveb ['parent_id'] = $parent_id;
                    $parent_bank_id = $parent_bank_tbl->save ( $saveb );
                }
                // -------------------------------------------------------------
                // Case parent update payment method -> update all payment method on student_class
                // -------------------------------------------------------------
                if (!is_null($old_payment_id) && $old_payment_id != $request->invoice_type) {
                    StudentClassTable::getInstance()->updatePaymentMethodByParent($parent_id, $request->invoice_type);
                }

                // -------------------------------------------------------------
                // 受講料以外にかかる費用
                // -------------------------------------------------------------
                $routine_payment_tbl = RoutinePaymentTable::getInstance ();
                // ①まず登録されているもの取得
                $registed_list = $routine_payment_tbl->getList ( $where = array (
                        'pschool_id' => session()->get('school.login.id' ),
                        'data_div' => 3, //1:プラン 2:イベント 3:保護者　4:プログラム
                        'data_id' => $request->orgparent_id,
                        'delete_date IS NULL' 
                ));

                //Additional category
                $additionalCategories = AdditionalCategoryTable::getInstance()->getListData(['type' => ConstantsModel::$ADDITIONAL_CATEGORY_PARENT, 'related_id' => $request->id], true);
                $additionalCategoryRelData = [];
                foreach ($additionalCategories as $category) {
                    $code = $category['code'];
                    //Only add data if code exist
                    if ($request->has('additional.' . $code)) {
                        $additionalCategoryRelData = [
                                'id' => $category['additional_category_rel_id'],
                                'additional_category_id' => $category['id'],
                                'pschool_id' => session()->get('school.login.id' ),
                                'type' => ConstantsModel::$ADDITIONAL_CATEGORY_PARENT,
                                'related_id' => $parent_id,
                                'value' => $request->additional[$code],
                                'update_date' => date('Y-m-d H:i:s'),
                                'update_admin' => session()->get('school.login.login_account_id')
                        ];
                        if ($category['additional_category_rel_id'] == null) {
                            $additionalCategoryRelData['register_admin'] = session()->get('school.login.login_account_id');
                            $additionalCategoryRelData['register_date'] = date('Y-m-d H:i:s');
                        }
                        AdditionalCategoryRelTable::getInstance()->save($additionalCategoryRelData);
                    }
                }
                //
                if (count ( $request->payment) < 1) {
                    // ②既存の全部削除
                    foreach ( $registed_list as $regist_item ) {
                        $regist_item ['active_flag'] = 0;
                        $regist_item ['delete_date'] = date ( 'Y-m-d H:i:s' );
                        $regist_item ['update_date'] = date ( 'Y-m-d H:i:s' );
                        $regist_item ['update_admin'] = session()->get ( 'school.login.login_account_id' );
                        $routine_payment_tbl->save ( $regist_item, $where = array (
                                'id' => $regist_item ['id'] 
                        ) );
                    }
                } else {
                    if (count ( $registed_list ) < 1) {
                        // ③全部登録
                        foreach ( $request->payment as $payment_item ) {
                            $row = array ();
                            $row ['pschool_id'] = session()->get ('school.login.id');
                            $row ['data_div'] = 3; //1:プラン 2:イベント 3:保護者　4:プログラム
                            $row ['data_id'] = $request->orgparent_id;
                            $row ['month'] = $payment_item ['payment_month'];
                            $row ['invoice_adjust_name_id'] = $payment_item ['payment_adjust'];
                            $row ['adjust_fee'] = $payment_item ['payment_fee'];
                            $row ['student_type'] = null;
                            $row ['register_date'] = date ( 'Y-m-d H:i:s' );
                            $row ['register_admin'] = session()->get('school.login.login_account_id');

                            $routine_payment_tbl->save ( $row );
                        }
                    } else {

                        // ④一部登録と一部更新
                        foreach ( $request->payment as $payment_item ) {
                            $bExist = false;
                            foreach ( $registed_list as $regist_item ) {
                                if (! empty($payment_item ['payment_id'])) {
                                    if ($payment_item ['payment_id'] == $regist_item ['id']) {
                                        $bExist = true;
                                        break;
                                    }
                                }
                            }
                            if ($bExist) {
                                // 存在するので更新
                                $regist_item ['month'] = array_get($payment_item, 'payment_month');
                                $regist_item ['invoice_adjust_name_id'] = $payment_item ['payment_adjust'];
                                $regist_item ['adjust_fee'] = $payment_item ['payment_fee'];
                                $regist_item ['update_date'] = date ( 'Y-m-d H:i:s' );
                                $regist_item ['update_admin'] = null;

                                $routine_payment_tbl->save ( $regist_item, $where = array (
                                        'id' => $regist_item ['id'] 
                                ) );
                            } else {
                                // 存在しないので追加
                                $row = array ();
                                $row ['pschool_id'] = session ( 'school.login' ) ['id'];
                                $row ['data_div'] = 3;
                                $row ['data_id'] = $request ['orgparent_id'];
                                $row ['month'] = array_get($payment_item, 'payment_month');
                                $row ['invoice_adjust_name_id'] = $payment_item ['payment_adjust'];
                                $row ['adjust_fee'] = $payment_item ['payment_fee'];
                                $row ['student_type'] = null;
                                $row ['register_date'] = date ( 'Y-m-d H:i:s' );
                                $row ['register_admin'] = session ( 'school.login' ) ['login_account_id'];

                                $routine_payment_tbl->save ( $row );
                            }
                        }

                        // ⑤一部削除
                        foreach ( $registed_list as $regist_item ) {
                            $bExist = false;
                            foreach ( $request->payment as $payment_item ) {
                                if (! empty($payment_item ['payment_id'])) {
                                    if ($regist_item ['id'] == $payment_item ['payment_id']) {
                                        $bExist = true;
                                        break;
                                    }
                                }
                            }
                            if (! $bExist) {
                                // 存在しないので削除
                                $regist_item ['active_flag'] = 0;
                                $regist_item ['delete_date'] = date ( 'Y-m-d H:i:s' );
                                $regist_item ['update_date'] = date ( 'Y-m-d H:i:s' );
                                $regist_item ['update_admin'] = session ( 'school.login' ) ['login_account_id'];

                                $routine_payment_tbl->save ( $regist_item, $where = array (
                                        'id' => $regist_item ['id'] 
                                ) );
                            }
                        }
                    }
                }
                
                $parent_tbl->commit ();
            } catch ( Exception $ex ) {
                $parent_tbl->rollBack ();
                $this->_logger->error ( $ex->getMessage () );
                $message_type = 99; // エラーメッセージを表示
            }
        }
        
        if (($request->has ( 'orgparent_id' )) && $request ['function'] == 3) {
            if ($err){
                $error = implode(" ",$err);
                session ()->put ( 'error_when_remove_parent_have_student', $error );
                return back()->withInput();
            }
            else{
                $message_type ='削除されました。'; // 削除
                session ()->put ( 'message_type', $message_type );
                return $this->execute($request);
            }
           
        } else if ($request->has ( 'orgparent_id' ) || isset ( $request ['parent'] ['id'] ) || $request->function == 2) {
            $message_type = 2; // 更新
        } else {
            $message_type = 1; // 新規
        }
        
        $lan = $this->lan;

        // エラーメッセージを表示
        if (session()->get( self::SESSION_REQUESTS_KEY ) !== null) {
            $request->offsetSet('orgparent_id',$parent_id);
            $prm = '?';
            foreach ( $request as $key => $val ) {
                $prm .= $key . '=' . $val . '&';
            }
            $request->prm = $prm;
            
            return $this->executeEntry ( $request );
        }

        if ($request->parent_search== 1) {
            // エラーメッセージを表示
            return view ( 'School.Parent.list2', compact ( 'message_type', 'request', 'lan' ) );
        } elseif($message_type==2) {
            //session ()->put ( 'success', '保存されました。' );
            // エラーメッセージを表示
            return $this->executeDetail ( $request );
        }elseif ($message_type==1){
            $request->offsetSet('orgparent_id',$parent_id);
            return $this->executeDetail ( $request );
        }
    }
    private function deleteParent($parent_id) {
        $student_tbl = StudentTable::getInstance ()->getActiveList ( array (
                'parent_id' => $parent_id,
                'pschool_id' => session()->get ( 'school.login.id' )
        ) );
        if ($student_tbl != NULL) {
            $err [] = ConstantsModel::$errors [$this->current_lang] ['delete_parent_process_error'];
            return $err;
        }
        $err = array ();
        $parent_tbl = ParentTable::getInstance ();
        $parent_bank = ParentBankAccountTable::getInstance ();
        $parent_bank_tbl = $parent_bank->getRow ( array (
                'parent_id' => $parent_id 
        ) );
        $parent_tbl->beginTransaction ();
        try {
            $parent_tbl->logicalRemoveByCondition ( array (
                    'id' => $parent_id,
                    'pschool_id' => session()->get( 'school.login.id' )
            ) );
            $parent_bank->logicalRemoveByCondition ( array (
                    'id' => $parent_bank_tbl ['id'] 
            ) );
            $parent_tbl->commit ();
        } catch ( Exception $ex ) {
            $parent_tbl->rollBack ();
            $this->_logger->error ( $ex->getMessage () );
            $err [] = ConstantsModel::$errors [$this->current_lang] ['delete_process_error'];
        }
        return $err;
    }
    public function executeDetail(Request $request) {


        // return to list when back to null Toran

        if(!$request->has('orgparent_id') && !$request->exists('pre') && !$request->exists('next')){
//            $this->set_bread_list ( self::$ACTION_URL . '/list', ConstantsModel::$bread_list [$this->current_lang] ['parent_manage'] );
            return redirect('/school/parent/list');
        }

        // get and set position - Toran
        $curr_list = session()->pull('current_list');

        if(!isset($curr_list)){
            return $this->executeList($request);
        }else{
            session()->forget('current_list');
            session()->put('current_list',$curr_list);
        }

        $last_index= count($curr_list)-1;
        $position= session()->pull('parent_detail_pos');

        if(empty($position)){
            foreach($curr_list as $k=>$v){
                if($request->orgparent_id == $v){
                    $position['current'] = $k;
                    $position['first']=0;
                    $position['last']=$last_index;
                    session()->forget('parent_detail_pos');
                    session()->put('parent_detail_pos',$position);
                    break;
                }
            }
        }else{

            if ($request->exists('pre')) {
                if($position['current']>0){
                    $position['current']= $position['current']-1;
                }
                $request->offsetSet('orgparent_id',$curr_list[$position['current']]);
            } elseif ($request ->exists('next')) {
                if($position['current']<$position['last']){
                    $position['current']= $position['current']+1;
                }
                $request->offsetSet('orgparent_id',$curr_list[$position['current']]);
            }else{

            }
            session()->forget('parent_detail_pos');
            session()->put('parent_detail_pos',$position);
        }
        
        $parent = array ();
        $parent = ParentTable::getInstance ()->getRow ( array (
                'id' => $request['orgparent_id'],
                'pschool_id' => session()->get ( 'school.login.id' )
        ) );

        $parent_bank_account = ParentBankAccountTable::getInstance ()->getRow ( array (
                'parent_id' => $request->orgparent_id
        ) );
        $pref_name = "";
        $city_name = "";
        $pref_other = "";
        $city_other = "";
        if (isset ( $parent )) {
            $pref = MPrefTable::getInstance ()->load ( $parent ['pref_id'] );
            $city = MCityTable::getInstance ()->load ( $parent ['city_id'] );
            $pref_other = MPrefTable::getInstance ()->load ( $parent ['pref_id_other'] );
            $city_other = MCityTable::getInstance ()->load ( $parent ['city_id_other'] );
            $pref_name = $pref;
            $city_name = $city;
        }
        if (isset ( $parent_bank_account )) {
            $parent ['bank_type'] = $parent_bank_account ['bank_type'];
            $parent ['bank_code'] = $parent_bank_account ['bank_code'];
            $parent ['bank_name'] = $parent_bank_account ['bank_name'];
            $parent ['branch_code'] = $parent_bank_account ['branch_code'];
            $parent ['branch_name'] = $parent_bank_account ['branch_name'];
            $parent ['bank_account_type'] = $parent_bank_account ['bank_account_type'];
            $parent ['bank_account_number'] = $parent_bank_account ['bank_account_number'];
            $parent ['bank_account_name'] = $parent_bank_account ['bank_account_name'];
            
            $parent ['bank_account_name_kana'] = $parent_bank_account ['bank_account_name_kana'];
            $parent ['post_account_kigou'] = $parent_bank_account ['post_account_kigou'];
            $parent ['post_account_number'] = $parent_bank_account ['post_account_number'];
            $parent ['post_account_name'] = $parent_bank_account ['post_account_name'];
            $parent ['post_account_type'] = $parent_bank_account ['post_account_type'];
        }
        
        $row = $parent;
        //additional categories
        $additionalCategories = AdditionalCategoryTable::getInstance()->getListData(['type' => ConstantsModel::$ADDITIONAL_CATEGORY_PARENT, 'related_id' => $request->orgparent_id], true);
        // /////////////////////////////////////////////////////////////////////
        // 受講料以外にかかる費用
        // /////////////////////////////////////////////////////////////////////

        $routine_payments = RoutinePaymentTable::getInstance ()->getRoutinePayemntList ( session()->get('school.login.id'), 3, $request->orgparent_id);
        $lan = $this->lan;
//        $auths = AuthConfig::getAuth ( 'school' );
        $bank_account_type_list = $this->bank_account_type_list;
        //get detail payment method of pschool
        $payment_method_list = PaymentMethodPschoolTable::getInstance()->getListPaymentMethod(session()->get('school.login.id' ));
        //
        if (session()->get( 'school.login.country_code' )== 81) {
            return view ( 'School.Parent.detail', compact ('payment_method_list','pref_name', 'pref_other', 'city_other', 'bank_account_type_list', 'request', 'lan', 'auths', 'city_name', 'routine_payments', 'row','position' ,'additionalCategories') );
        } else {
            // return $this->convertSmartyPath(self::$TEMPLATE_URL.'/detail2.html');
        }
    }

    private function validate_parent_rules($request){

        $mail_validation = 'required|email|max:64|unique:parent,parent_mailaddress1,'.$request->orgparent_id.',id,delete_date,NULL,pschool_id,'.session()->get('school.login.id');
        if(!empty($request->orgparent_id)){
            $students = StudentTable::getInstance()->getActiveList(array('parent_id'=>$request->orgparent_id));
            foreach ($students as $k => $student){
                $mail_validation.= '|unique:student,mailaddress,'.$student['id'].',id,delete_date,NULL,pschool_id,'.session()->get('school.login.id');
            }
        }
        $rules = [
            'parent_name' => 'required|max:255',
            'parent_mailaddress1' => $mail_validation,
            'pref_id' => 'required',
            'city_id' => 'required',
            'address' => 'required|max:64',
            'building' => 'max:64',
            'phone_no' => 'required|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/'
        ];
        if($request->has('zip_code1') || $request->has('zip_code2')){
            $rules['zip_code1']='digits:3';
            $rules['zip_code2']='digits:4';
        }
        if($request->has('name_kana')){
            $rules['name_kana']= 'max:255';
        }
        if($request->has('parent_mailaddress2')){
            $rules['parent_mailaddress2']= 'email|max:64';
        }
        if ($request->has ( 'payment' )) {
//            foreach ($request->payment as $key => $value){
                $rules['payment.*.payment_month'] = 'required';
                $rules['payment.*.payment_adjust'] = 'required';
                $rules['payment.*.payment_fee'] = 'required|numeric';
//            }
        }
        // if(($request->invoice_type == 2 || $request->invoice_type ==3) && session()->get ( 'school.login.country_code' )== 81){
        if(($request->invoice_type == 2) && session()->get ( 'school.login.country_code' )== 81){
            if($request->bank_type==1){
                $rules['bank_code']= 'required|numeric|digits_between:0,4' ;
                $rules['bank_name']= 'required|max:15' ;
                $rules['branch_code']= 'required|numeric|digits_between:0,3' ;
                $rules['branch_name']= 'required|max:15' ;
                $rules['bank_account_type']= 'required' ;
                $rules['bank_account_number']= 'required|numeric|digits_between:0,7' ;
                $rules['bank_account_name']= 'required' ;
                $rules['bank_account_name_kana']= 'required|max:30' ;
            }
            if($request->bank_type==2){
                $rules['post_account_kigou'] = 'required|numeric|digits_between:0,5' ;
                $rules['post_account_number'] = 'required|numeric|digits_between:0,8' ;
                $rules['post_account_name'] = 'required|max:30' ;
            }
        }
        if (!$request->offsetExists('orgparent_id')) {
            $rules['parent_pass']= "required|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/";
        }else{
            $rules['parent_pass']= "nullable|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/";
        }

        return $rules;
    }
    private function validate_parent_messages($request){
        $message = array();
        $message['parent_name.required']="claimant_name_required";
        $message['parent_name.max']="claimant_email_address_1_255";
        $message['parent_mailaddress1.required']="require_claimant_email_address_1";
        $message['parent_mailaddress1.max']="claimant_email_address_1_64";
        $message['parent_mailaddress1.email']="invalid_format_claimant_email_address_1";
        $message['parent_mailaddress1.unique']="email_address_1_existed_err";
        $message['zip_code1.digits']="zip_code_1_3_digit";
        $message['zip_code2.digits']="zip_code_2_4_digit";
        $message['pref_id.required']="require_state_name";
        $message['city_id.required']="require_city_name";
        $message['address.required']="mandatory_address";
        $message['address.max']="address_error_max_64";
//        $message['building.required']="building_mandatory";
        $message['building.max']="building_error_max_64";
        $message['phone_no.required']="require_home_telephone";
        $message['phone_no.regex']="invalid_home_phone_number";
        if($request->has('name_kana')){
            //$message['name_kana.regex']="character_valid_claimant_name_kana";
            $message['name_kana.max']="claimant_name_kana_255";
        }
        if($request->has('parent_mailaddress2')){
            $message['parent_mailaddress2.email']= 'invalid_format_claimant_mail_address_2';
            $message['parent_mailaddress2.max']= 'claimant_mail_address_2_64';
        }
        if($request->has ('payment')){
            foreach ($request->payment as $key => $value){
                $message['payment.'.$key.'.payment_month.required']="require_target_month,".($key+1);
                $message['payment.'.$key.'.payment_adjust.required']="require_abstract,".($key+1);
                $message['payment.'.$key.'.payment_fee.required']="require_amount_of_money,".($key+1);
                $message['payment.'.$key.'.payment_fee.numeric']="amount_money_numeric,".($key+1);
            }
        }
//        if(($request->invoice_type == 2 || $request->invoice_type ==3) && session()->get ( 'school.login.country_code' )== 81){
        if(($request->invoice_type == 2) && session()->get ( 'school.login.country_code' )== 81){
            if($request->bank_type==1){
                $message['bank_code.required'] = "require_financial_institutions_code";
                $message['bank_code.numeric'] = "financial_institutions_code_alphanumeric_character";
                $message['bank_code.digits_between'] = "please_enter_more_than_four_character_financial_institution_code";
                $message['bank_name.required'] = "mandatory_financial_institution_name";
                //$message['bank_name.regex'] = "financial_institution_name_alphanumeric_kana";
                $message['bank_name.max'] = "financial_institution_name_255";
                $message['branch_code.required'] = "require_branch_code";
                $message['branch_code.numeric'] = "branch_code_alphanumeric";
                $message['branch_code.digits_between'] = "within_three_character_branch_code";
                $message['branch_name.required'] = "require_branch_name";
                //$message['branch_name.regex'] = "branch_name_alphanumeric_kana";
                $message['branch_name.max'] = "branch_name_255";
                $message['bank_account_type.required'] = "require_financial_institution_type";
                $message['bank_account_number.required'] = "require_account_number";
                $message['bank_account_number.numeric'] = "account_number_entered_number";
                $message['bank_account_number.digits_between'] = "account_number_up_7_character";
                $message['bank_account_name.required'] = "account_holder_require";
                // $message['bank_account_name.regex'] = "account_holder_entered_alphanumeric_kana";
                // $message['bank_account_name.max'] = "account_holder_up_30_character";
                $message['bank_account_name_kana.required'] = "account_kana_require";
                $message['bank_account_name_kana.max'] = "account_kana_up_30_character";
            }
            if($request->bank_type==2){
                $message['post_account_kigou.required'] = "mandatory_passbook_symbol";
                $message['post_account_kigou.numeric'] = "number_passbook_symbol";
                $message['post_account_kigou.digits_between'] = "within_5_character_passbook_symbol";
                $message['post_account_number.required'] = "require_passbook_number";
                $message['post_account_number.numeric'] = "passbook_number_entered_number";
                $message['post_account_number.digits_between'] = "passbook_number_up_8_character";
                $message['post_account_name.required'] = "require_passbook_name";
                //$message['post_account_name.regex'] = "passbook_name_entered_alphanumeric_kana";
                $message['post_account_name.max'] = "passbook_name_up_30_character";
            }

        }

        $message['parent_pass.required'] = "parent_pass_required_title";
        $message['parent_pass.min'] = "over_length_password_msg";
        $message['parent_pass.max'] = "over_length_password_msg";
        $message['parent_pass.regex'] = "password_regex_msg";

        return $message;
    }

    public function get_address_from_zipcode(Request $request){
        try {
            if (! $request->ajax ()) {
                throw new Exception();
            }
            if(! $request->has ('zipcode')){
                return response()->json(['status' => false, 'message' => ""]);
            }

            $zipcodeAddressTable = ZipcodeAddressTable::getInstance();

            $res = $zipcodeAddressTable->getAddressFromZipcode($request->zipcode);

            if(!empty($res)){
                return response()->json(['status' => true, 'message' => $res]);
            }else{
                return response()->json(['status' => false, 'message' => ""]);
            }

        }catch (Exception $e){
            throw new Exception();
        }
    }
}