<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodValidationTable extends DbModel {
    /**
     *
     * @var PaymentMethodValidationTable
     */
    private static $_instance = null;
    protected $table = 'payment_method_validation';

    /**
     *
     * @return PaymentMethodValidationTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PaymentMethodValidationTable();
        }
        return self::$_instance;
    }
    public function getPaymentValidation($request){
        $res = array();
        $rules = array();
        $messages = array();
        foreach($request->payment_setting_id as $key=>$value){
            $rule=$this->getValidationById($value);
            if(count($rule)){
                $tmp_rule=array();
                foreach ($rule as $k=>$v){
                    $tmp_rule[]= $v['rule'];
                    if(strpos($v['rule'],":")!==false){
                        $messages[$v['item_name'].'.'.explode(":",$v['rule'])[0]]= $v['message'];
                    }else{
                        $messages[$v['item_name'].'.'.$v['rule']]= $v['message'];
                    }
                }
                $rules[$rule[0]['item_name']] = implode("|",$tmp_rule);
            }
        }
        $res['rules'] = $rules;
        $res['messages'] = $messages;
        return $res;
    }
    public function getValidationById($payment_method_setting_id){
        $sql ="SELECT pmv.rule,pmv.message, pms.item_name ";
        $sql.=" FROM payment_method_validation pmv ";
        $sql.=" LEFT JOIN payment_method_setting pms ON pms.id = pmv.payment_method_setting_id ";
        $sql.=" WHERE pmv.payment_method_setting_id=".$payment_method_setting_id;
        $sql.=" AND pmv.delete_date IS NULL";
        return $this->fetchAll($sql);
    }

}
