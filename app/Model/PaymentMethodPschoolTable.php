<?php

namespace App\Model;

use App\Common\Constants;
use Illuminate\Database\Eloquent\Model;
use function SuperClosure\__reconstruct_closure;

class PaymentMethodPschoolTable extends DbModel {
    /**
     *
     * @var PaymentMethodPschoolTable
     */
    private static $_instance = null;
    protected $table = 'payment_method_pschool';

    /**
     *
     * @return PaymentMethodPschoolTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PaymentMethodPschoolTable();
        }
        return self::$_instance;
    }
    public function updatePschoolMethod($pschool_id,$method_data,$is_ajax){ // is_ajax -> change direct by clicking check box
        if($is_ajax){
            $payment_data = PaymentMethodTable::getInstance()->getActiveList(array('id'=>$method_data['payment_method_id']));
            $method_data['payment_method_code']=$payment_data[0]['code'];
            $method_data['payment_method_name']=$payment_data[0]['name'];
            $method_data['sort_no']=$payment_data[0]['sort_no'];
        }
        $current_data = $this->getActiveList(array('pschool_id'=>$pschool_id,'payment_method_code'=>$method_data['payment_method_code']));
        if(count($current_data)){
            $method_data['id']=$current_data[0]['id'];
        }
        if(isset($method_data['is_delete']) && $method_data['is_delete'] && isset($method_data['id'])){
            return $this->deleteRow(array('id'=>$method_data['id']));
        }else{
            return $this->save($method_data);
        }

    }
    public function getListPaymentMethod($pschool_id){
        $cond = array(
                'pschool_id'=>$pschool_id,
        );
        $payment_methods = $this->getList($cond);
        $res = array();
        foreach($payment_methods as $k => $method){
            $res[Constants::$PAYMENT_TYPE[$method['payment_method_code']]] = $method;
            $res[Constants::$PAYMENT_TYPE[$method['payment_method_code']]]['payment_method_value'] = Constants::$PAYMENT_TYPE[$method['payment_method_code']];
        }

        $res_tmp = array();
        foreach ($res as $key => $row)
        {
            $res_tmp[$key] = $row['sort_no'];
        }
        array_multisort($res_tmp, SORT_ASC, $res);

        // ksort($res);
        return $res;
    }

    public function getListMethodPschool($pschool_id){

        $bind= array();
        $bind[] = $pschool_id;

        $sql = "SELECT pmp.*, pa.agency_name ";
        $sql.= " FROM payment_method_pschool pmp";
        $sql.= " LEFT JOIN payment_agency pa ON pa.id = pmp.payment_agency_id ";
        $sql.= " WHERE pmp.pschool_id = ? ";
        $sql.= " AND pmp.delete_date IS NULL ";
        $sql.= " AND pa.delete_date IS NULL ";
        $sql.= " ORDER BY pmp.sort_no";

        return $this->fetchAll($sql,$bind);
    }
}