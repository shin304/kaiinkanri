<?php

namespace App\Model;

use App\ConstantsModel;
use Illuminate\Database\Eloquent\Model;
use App\Common\Constants;

class TempSchoolInfoTable extends DbModel {
    /**
     *
     * @var TempSchoolInfoTable
     */
    private static $_instance = null;
    protected $table = 'temp_school_info';
    
    /**
     *
     * @return TempSchoolInfoTable
     */
    public static function getInstance() {
        if (is_null(self::$_instance )) {
            self::$_instance = new TempSchoolInfoTable();
        }
        return self::$_instance;
    }

    public function insertSchoolInfo($mail_address, $login_pw, $company_name, $customer_name, $zip_code, $pref_id, $city_id, $address, $building, $phone, $fax, $home_page, $register_code) {
        $school_info_table = TempSchoolInfoTable::getInstance();
        $school_info = array ();
        try {
            $school_info['mail_address'] = $mail_address;
            $school_info['login_pw'] = $login_pw;
            $school_info['company_name'] = $company_name;
            $school_info['customer_name'] = $customer_name;
            $school_info['zip_code'] = $zip_code;
            $school_info['pref_id'] = $pref_id;
            $school_info['city_id'] = $city_id;
            $school_info['address'] = $address;
            $school_info['building'] = $building;
            $school_info['phone'] = $phone;
            $school_info['fax'] = $fax;
            $school_info['home_page'] = $home_page;
            $school_info['register_code'] = $register_code;
            $school_info['status'] = 1; // 登録したところ

            $school_info_table->save($school_info, true);
        } catch (Exception $e) {
            $school_info_table->rollBack();
        }
        $school_info_table->commit();
    }

    public function getSchoolInfoAccountByCode($code)
    {
        $school_info = TempSchoolInfoTable::where('register_code', $code)->where('delete_date', null)->first();
        return $school_info;
    }

//    public function editStatusForSchoolInfoByCode($register_code) {
//
//        $school_info_table = TempSchoolInfoTable::getInstance();
//        $school_info = $this->getSchoolInfoAccountByCode($register_code)->toArray();
//
//        $school_info_table->beginTransaction();
//
//        try {
//            //check if email is exist -> lock all record include this one
//            $exists_list = $school_info_table->where('mail_address', '=', $school_info['mail_address'] )->get()->toArray();
//            if(count($exists_list) > 1){
//                $school_info['is_locked'] = 1;
//                foreach ($exists_list as $k => $temp_school){
//                    $temp_school['is_locked'] = 1;
//                    $school_info_table->save($temp_school, true);
//                }
//            }
//
//            //update status to mail confirmed
//            $school_info['status'] = Constants::STATUS_MAIL_CONFIRMED; // メールを確認した
//            $school_info_table->save($school_info, true);
//
//        } catch (Exception $e) {
//            $school_info_table->rollBack();
//        }
//
//        $school_info_table->commit();
//    }

    public function checkEmailRegister($email)
    {
        $school_info = LoginAccountTable::where('login_id', $email)->where('auth_type', ConstantsModel::$LOGIN_AUTH_SCHOOL)->where('delete_date', null)->first ();
        return $school_info;
    }
}
