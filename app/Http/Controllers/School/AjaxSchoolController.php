<?php

namespace App\Http\Controllers\School;

use App\Lang;
use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\AdditionalCategoryTable;
use App\Model\MCityTable;
use App\Model\StudentTable;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Http\Request;
use App\Model\PaymentAgencyTable;
use App\Model\MStudentTypeTable;
use App\Model\ParentTable;
use App\Model\ClassTable;
use App\Model\MPrefTable;
use App\ConstantsModel;
class AjaxSchoolController extends _BaseSchoolController {
    public function execute() {
        return false;
    }
    /**
     * Ajaxで送られてきたリクエストをエンコード
     *
     * @param string $dat            
     */
    private function ajaxRequestConvert($dat) {
        return $dat;
    }
    
    /**
     * JSONコードを出力する
     *
     * @param unknown_type $dat            
     */
    protected function printJSON($dat) {
        // UTF-8で返却します
        print json_encode ( $dat );
    }
    
    /**
     * Ajaxリクエストを取得する
     *
     * @param string $key            
     * @return string
     */
    protected function getRequest(Request $request, $key) {
        if ($request->exists ( $key )) {
            return $this->ajaxRequestConvert ( $request [$key] );
        }
        return null;
    }
    
    public function  executeSearchBroadcastmail(Request $request){
//         dd($request);
        $callback = $this->getRequest ( $request, 'callback' );
        
        $student_tbl = StudentTable::getInstance ();
        $m_student_type_tbl = MStudentTypeTable::getInstance ();
        $parent_tbl = ParentTable::getInstance ();
        
        $arry_search = array ();
        $arry_search ['pschool_id'] = session ( 'school.login' ) ['id'];
        if (isset ( $request ['input_search'] )) // 生徒名（漢字・カナ）
        {
            $arry_search ['input_search'] = $request ['input_search'];
        }
        if (isset ( $request ['input_search_student_no'] )) // 生徒名（漢字・カナ）
        {
            $arry_search ['input_search_student_no'] = $request ['input_search_student_no'];
        }
        if (isset ( $request ['class_id'] )) // プラン名 2015/04/03
        {
            $arry_search ['class_id'] = $request ['class_id'];
        }
        if (isset ( $request ['not_member'] )) // プラン名 2015/04/03
        {
            $arry_search ['active_flag'] = $request ['not_member'];
        }
        $arry_search ['student_type'] = $request ['student_types'];
        $pschool_id = session ( 'school.login' ) ['id'];
//         dd($arry_search);
        $student_list = StudentTable::getInstance ()->getQueryList ( $arry_search, $pschool_id );
//         dd($student_list);
        $parent_list = array ();
        foreach ( $student_list as $student ) {
            $student ['student_time_send'] = StudentTable::getInstance ()->getTimeSendStudent ( $student ['id'] );
            if (! array_key_exists ( $student ['parent_id'], $parent_list )) {
                $parent_list [$student ['parent_id']] = $parent_tbl->getRow ( $where = array (
                        'pschool_id' => session ( 'school.login' ) ['id'],
                        'id' => $student ['parent_id']
                ) );
                $parent_list [$student ['parent_id']] ['parent_time_send'] = ParentTable::getInstance ()->getTimeSendParent ( $parent_list [$student ['parent_id']] ['id'] );
            }
            $parent_list [$student ['parent_id']] ['students'] [$student ['id']] = $student;
            $m_student_type = $m_student_type_tbl->getActiveRow ( $where = array (
                    'pschool_id' => session ( 'school.login' ) ['id'],
                    'type' => $parent_list [$student ['parent_id']] ['students'] [$student ['id']] ['student_type']
            ) );
            $parent_list [$student ['parent_id']] ['students'] [$student ['id']] ['student_type'] = $m_student_type ['name'];
        }
//         $lan = $this->lan;
//         dd($lan);
//         return $this->printJSON ( $parent_list );
            return view('School.Broadcast_mail.parent_list', compact('parent_list'));
    }
    
    public function executeCity(Request $request) {
        // var_dump("11111111111");
        $callback = $this->getRequest ( $request, 'callback' );
        $pref_cd = $this->getRequest ( $request, 'pref_cd' );
        // var_dump($pref_cd);
        
        $cityList = MCityTable::getInstance ()->getListByPref ( $pref_cd );
        $dispCityList = array ();
        if (! empty ( $cityList )) {
            foreach ( $cityList as $idex => $row ) {
                $dispCityList [$row ['id']] = $row ['name'];
            }
        }
        // dd($dispCityList);
        
        $ret ['city_list'] = $dispCityList;
        
        // // Logger::getInstance()->info(print_r($ret,true));
        
        // print $callback . '(' . json_encode( $ret ). ')';
        
        return $this->printJSON ( $ret );
        // return false;
    }
    public function executeConsignor(Request $request) {
        $callback = $this->getRequest ( $request, 'callback' );
        $consignor_id = $this->getRequest ( $request, 'consignor_id' );
        $consignorList=array();
        if(!empty($consignor_id)){
            $consignorList[] = PaymentAgencyTable::getInstance ()->getListById($consignor_id);

            $withdrawal_day = array ();
            foreach ( $consignorList as $arr ){
                $withdrawal_day = [
                    'withdrawal_day1' => $arr ['withdrawal_day1'],
                    'withdrawal_day2' => $arr ['withdrawal_day2'],
                    'withdrawal_day3' => $arr ['withdrawal_day3'],
                    'withdrawal_day4' => $arr ['withdrawal_day4'],
                    'withdrawal_day5' => $arr ['withdrawal_day5']
                ];
            }
            $arrTemp[] = array_filter($withdrawal_day, function($value) { return $value !== null; });

            $consignorList = $arrTemp;
        }else{
            $withdrawal_day= ConstantsModel::$withdrawal_date_list;
            $arrTemp[] = array_filter($withdrawal_day, function($value) { return $value !== null; });
            $consignorList =$arrTemp;
        }


        $ret ['consignorList'] = $consignorList;
        
        // // Logger::getInstance()->info(print_r($ret,true));
        
        // print $callback . '(' . json_encode( $ret ). ')';
        
        return $this->printJSON ( $ret );
        // return false;
    }
    
    /**
     * 教科選択->科目一覧取得
     *
     * @return boolean
     */
    public function executeCourrseList() {
        $callback = $this->getRequest ( 'callback' );
        $pschool_id = $this->getRequest ( 'pschool_id' );
        $id = $this->getRequest ( 'id' );
        $old_tag_id = $this->getRequest ( 'old_tag_id' );
        
        $cource_list = SCourceTable::getCourceList ( $pschool_id, $old_tag_id );
        
        $this->printJSONP ( $cource_list, $callback );
        
        return false;
    }
    
    /**
     * 入力された生徒が会員かどうかチェック
     *
     * @return boolean
     */
    public function executeIsValidStudent() {
        $ret = array ();
        $callback = $this->getRequest ( 'callback' );
        $student_no = $this->getRequest ( 'student_no' );
        
        $student = StudentTable::getInstance ()->getRow ( array (
                'student_no' => $student_no 
        ) );
        if (empty ( $student ) || count ( $student ) < 1) {
            $ret ['result'] = "NG";
        } else {
            $ret ['result'] = "OK";
        }
        print $callback . json_encode ( $ret );
        return false;
    }
    public function executeSwapCategories(Request $request){
        $curr_id = $this->getRequest ( $request, 'curr_id' );
        $swap_id = $this->getRequest ( $request, 'swap_id' );
        $additionalCateTbl = AdditionalCategoryTable::getInstance();
        $curr_item = $additionalCateTbl->load($curr_id);
        $swap_item = $additionalCateTbl->load($swap_id);
        $msg = "";
        if($curr_item && $swap_item ){
            $additionalCateTbl->beginTransaction();
            try{
                $temp = $curr_item['sort_no'];
                $curr_item['sort_no'] = $swap_item['sort_no'];
                $swap_item['sort_no'] = $temp;

                $additionalCateTbl->save($curr_item);
                $additionalCateTbl->save($swap_item);
                $additionalCateTbl->commit();
                $msg="success";
            }catch (Exception $e){
                $additionalCateTbl->rollBack();
                $msg= "errors";
            }
        }else{
            $msg= "errors";
        }
        return $this->printJSON ($msg);
    }
}

