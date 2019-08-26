<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentAgencyTable extends DbModel {
    /**
     *
     * @var PaymentAgencyTable
     */
    private static $_instance = null;
    protected $table = 'payment_agency';

    /**
     *
     * @return PaymentAgencyTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PaymentAgencyTable();
        }
        return self::$_instance;
    }

    public function getListWithdrawalDay($consignor_id){
        $sql = " SELECT * FROM {$this->getTableName(true)} WHERE id =".$consignor_id;
        return $this->fetch($sql);
    }
    public function getAgencyName($agency_id){
        $sql= "SELECT agency_name FROM {$this->getTableName(true)} WHERE id =".$agency_id;
        return $this->fetch($sql);
    }
}
