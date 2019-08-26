<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\PaymentMethodTable;
use App\Common\Constants;

class PaymentMethodBankRelTable extends DbModel {
    /**
     *
     * @var PaymentMethodBankRelTable
     */
    private static $_instance = null;
    protected $table = 'payment_method_bank_rel';

    /**
     *
     * @return PaymentMethodBankRelTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PaymentMethodBankRelTable();
        }
        return self::$_instance;
    }
    public function getListBank($pschool_id,$method_id){
        $sql = "SELECT * FROM payment_method ";
        $sql.= " WHERE id = ".$method_id;
        $sql.= " AND delete_date IS NULL";

        $payment = $this->fetch($sql);
        if($payment['is_using_bank']==1){ // using bank -> then get list bank and default account
            $query="SELECT pba.id,pba.pschool_id,pba.bank_type,
                    pmbr.payment_method_id , 
                        (CASE 
                        WHEN pmbr.bank_account_id = pba.id THEN 1
                        ELSE null
                        END) is_default_bank,
                        (CASE 
                        WHEN pba.bank_type = 1 THEN pba.bank_name
                        ELSE pba.post_account_name
                        END) bank_name,
                        (CASE 
                        WHEN pba.bank_type = 1 THEN pba.branch_name
                        ELSE pba.post_account_kigou
                        END) branch_name,
                        (CASE 
                        WHEN pba.bank_type = 1 THEN pba.bank_account_number
                        ELSE pba.post_account_number
                        END) account_number,
                        (CASE 
                        WHEN pba.bank_type = 1 THEN pba.bank_account_name
                        ELSE pba.post_account_number
                        END) account_name,
                        pba.bank_account_type,
                        pba.is_default_account
                    FROM pschool_bank_account pba 
                    LEFT JOIN payment_method_bank_rel pmbr ON pmbr.pschool_id = pba.pschool_id AND pmbr.payment_method_id =".$method_id."
                    WHERE pba.pschool_id = ".$pschool_id." 
                    AND pba.delete_date is null";
            if ($method_id == PaymentMethodTable::where('code', Constants::POST_RICOH)->first()->id) { // ゆうちょ振込
                $query.= " AND pba.bank_type =2 ";
            }

            $temp = $this->fetchAll($query);
            // processing if do not have default, get default from bank_account table
            $is_set_default = false;
            foreach ($temp as $key=>$value){
                if($value['is_default_bank']!=null){
                    $is_set_default=true;
                    break;
                }
            }
            if(!$is_set_default){
                foreach ($temp as $key=>$value){
                    if($value['is_default_account']==1){
                        $temp[$key]['is_default_bank']=1;
                    }
                }
            }
            $payment['list_bank'] = $temp;
        }
        return $payment;
    }
    public function updateDefaultBank($method_data){
        $current_data = $this->getActiveList(array('pschool_id'=>$method_data['pschool_id'],'payment_method_id'=>$method_data['payment_method_id']));
        if(count($current_data)){
            $method_data['id'] = $current_data[0]['id'];
        }
        return $this->save($method_data);
    }

    public function getPschoolBankByMethod($pschool_id, $invoice_type){

        $bind = array(
            $pschool_id,
            $invoice_type,
        );
        $sql = " SELECT pm.id as payment_method_id , pa.id, pa.agency_code as consignor_code, pa.agency_name as consignor_name, 
                pba.pschool_id, pba.is_default_account, pba.invoice_type, pba.bank_type, pba.bank_code, pba.bank_name, pba.branch_code,  
                pba.branch_name,  pba.bank_account_type,  pba.bank_account_number,  pba.bank_account_name,  pba.bank_account_name_kana,  
                pba.post_account_kigou,  pba.post_account_name, pba.post_account_number ";
        $sql.= " FROM  payment_method_bank_rel pbr";
        $sql.= " LEFT JOIN pschool_bank_account pba ON pbr.bank_account_id = pba.id ";
        $sql.= " LEFT JOIN payment_method pm ON pbr.payment_method_id = pm.id ";
        $sql.= " LEFT JOIN payment_agency pa ON pm.payment_agency_id = pa.id";
        $sql.= " WHERE pbr.pschool_id = ? ";
        $sql.= " AND pbr.payment_method_id = ? ";
        $sql.= " AND pba.delete_date IS NULL";
        $sql.= " AND pbr.delete_date IS NULL";

        return $this->fetch($sql,$bind);
    }
}
