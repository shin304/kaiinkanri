<?php

namespace App\Model;

use App\Common\Constants;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodTable extends DbModel {
    /**
     *
     * @var PaymentMethodTable
     */
    private static $_instance = null;
    protected $table = 'payment_method';
    /**
     *
     * @return PaymentMethodTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PaymentMethodTable();
        }
        return self::$_instance;
    }
    public function getListMethod(){
        $query = " SELECT pm.id, pm.name, pm.code, pm.payment_agency_id, ";
        $query.= " pa.agency_code,pa.agency_name";
        $query.= " FROM payment_method pm ";
        $query.= " LEFT JOIN payment_agency pa ON pa.id = pm.payment_agency_id";
        $query.= " WHERE pm.delete_date IS NULL ";
        $query.= " GROUP BY pm.code ";
        $query.= " ORDER BY pm.sort_no ";

        $res = $this->fetchAll($query);
        return $res;
    }
    public function getListMethodDefaultPschool($pschool_id,$lan){
        $res = $this->getListMethod();

        $query = " SELECT pm.id, pm.name, pm.code, pm.payment_agency_id , ";
        $query.= " pmp.pschool_id,pa.agency_code,pa.agency_name";
        $query.= " FROM payment_method pm";
        $query.= " LEFT JOIN payment_method_pschool pmp ON pm.code = pmp.payment_method_code  AND pm.payment_agency_id = pmp.payment_agency_id";
        $query.= " LEFT JOIN payment_agency pa ON pa.id = pm.payment_agency_id";
        $query.= " WHERE pm.delete_date IS NULL";
        $query.= " AND pmp.pschool_id =" .$pschool_id ;
        $query.= " AND pa.delete_date IS NULL ";
        $query.= " GROUP BY pm.code ";
        $query.= " ORDER BY pm.id ASC ";

        $list_method = $this->fetchAll($query);

        foreach($res as $key=>$value){
            $res[$key]['name'] = $lan::get($value['name']);
            foreach($list_method as $k=>$v){
                if($value['code']== $v['code']){
                    $res[$key]['default'] = 1;
                    $res[$key]['agency_code'] = $v['agency_code'];
                    $res[$key]['agency_name'] =$v['agency_name'];
                    $res[$key]['payment_agency_id'] =$v['payment_agency_id'];
                }
            }
            // for case that do not have agency for this method -> remove payment method
            if($res[$key]['code']!=Constants::CASH && $res[$key]['code']!=Constants::TRAN_BANK && ($res[$key]['agency_code']==null || empty($res[$key]['agency_code']))){
                unset($res[$key]);
            }
        }

        return $res;
    }
    public function getPaymentMethodDetail($payment_code,$pschool_id,$lan){
        $sql = "SELECT pm.id as payment_method_id, pm.name, pm.code, 
                pa.agency_name,pa.agency_code,
                pms.payment_agency_id ,pms.id as payment_setting_id,pms.item_type,pms.unit, pms.default_value,
                pms.item_name,pms.item_display_name,pms.note,
                pmd.id as payment_data_id,
                pmp.payment_agency_id as default_agency,
                ( CASE 
                  WHEN pmd.item_value IS NULL THEN ''
                  ELSE pmd.item_value
                END ) as item_value
                FROM payment_method pm 
                JOIN payment_method_setting pms ON (pms.payment_method_id = pm.id AND pms.delete_date IS  NULL )
                LEFT JOIN payment_method_data pmd ON pms.id = pmd.payment_method_setting_id AND pmd.pschool_id =".$pschool_id."
                LEFT JOIN payment_agency pa ON pa.id = pms.payment_agency_id
                LEFT JOIN payment_method_pschool pmp ON (pm.code = pmp.payment_method_code AND pmp.pschool_id = ".$pschool_id.")
                WHERE pm.delete_date IS NULL
                        AND pm.code ='".$payment_code."'
                ORDER BY pms.sort_no";

        $res = $this->fetchAll($sql);
        foreach($res as $key=>$value){
            $res[$key]['name'] = $lan::get($value['name']);
            $res[$key]['item_display_name'] = $lan::get($value['item_display_name']);
            $res[$key]['note'] = $lan::get($value['note']);
            if(isset($res[$key]['unit'])){
                $res[$key]['unit'] = $lan::get($value['unit']);
            }
            if ($value['item_type'] == 4) { // radio button
                $default_value = json_decode($value['default_value'], true);
                foreach ($default_value as $idx=>$item) {
                    $default_value[$idx] = $lan::get($item);
                }
                $res[$key]['default_value'] = json_encode($default_value);
            }
        }

        return $res;
    }

    public function getPaymentMethodPschool( $pschool_id) {
        $sql = "SELECT p.id as payment_method_id, ps.* FROM payment_method p INNER JOIN payment_method_pschool ps ON ps.payment_method_code = p.code
WHERE ps.pschool_id = ? ORDER BY p.sort_no ";

        return $this->fetchAll($sql, array($pschool_id));
    }
}
