<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodDataTable extends DbModel {
    /**
     *
     * @var PaymentMethodDataTable
     */
    private static $_instance = null;
    protected $table = 'payment_method_data';

    /**
     *
     * @return PaymentMethodDataTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PaymentMethodDataTable();
        }
        return self::$_instance;
    }

    public function getIPCode($pschool_id, $payment_method_code) {
        $sql = "SELECT d.item_value AS ip_code, a.payment_link
                FROM payment_method_data d
                INNER JOIN payment_method_setting s ON d.payment_method_setting_id = s.id
                INNER JOIN payment_agency a ON s.payment_agency_id = a.id
                INNER JOIN payment_method_pschool p ON p.payment_agency_id = a.id
                WHERE d.delete_date IS NULL AND s.delete_date IS NULL AND p.delete_date IS NULL AND a.delete_date IS NULL
                AND p.payment_method_code = ? AND p.pschool_id = ? ";
        $bind[] = $payment_method_code;
        $bind[] = $pschool_id;
        $res = $this->fetch($sql, $bind);
        return $res;
    }

}
