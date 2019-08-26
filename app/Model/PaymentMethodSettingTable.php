<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodSettingTable extends DbModel {
    /**
     *
     * @var PaymentMethodSettingTable
     */
    private static $_instance = null;
    protected $table = 'payment_method_setting';

    /**
     *
     * @return PaymentMethodSettingTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PaymentMethodSettingTable();
        }
        return self::$_instance;
    }

    public function getListDueDateInvoice($pschool_id,$invoice_year_month){

        $list_due_date = array();

        $list_payment_code = "";
        //TODO get due date for each method
        // Code = 001 : cash method
        $sql = "SELECT pm.id , pmp.payment_method_code, pmp.payment_agency_id,
                pms.item_type, pms.default_value
                FROM payment_method_pschool pmp
                LEFT JOIN payment_method pm ON pm.code = pmp.payment_method_code AND pm.payment_agency_id = pmp.payment_agency_id
                LEFT JOIN payment_method_setting pms ON pms.payment_method_id = pm.id AND pms.item_name = 'withdrawal_date'
                WHERE pschool_id = '.$pschool_id.'
                AND pm.delete_date IS NULL
                AND pmp.delete_date IS NULL
                AND payment_method_code = '001'
                ORDER BY pm.id";


        return $list_due_date;
    }
}
