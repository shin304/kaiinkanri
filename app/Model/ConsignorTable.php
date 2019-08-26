<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConsignorTable extends DbModel {
    /**
     *
     * @var ConsignorTable
     */
    private static $_instance = null;
    protected $table = 'consignor';
    
    /**
     *
     * @return ConsignorTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ConsignorTable();
        }
        return self::$_instance;
    }
    
    public function getListWithdrawalDay($consignor_id){
        $sql = " SELECT * FROM {$this->getTableName(true)} WHERE id =".$consignor_id;
        $bind = array();
        return $this->fetch($sql, $bind);
    }
}
