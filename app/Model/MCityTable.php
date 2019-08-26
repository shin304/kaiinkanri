<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MCityTable extends DbModel
{
    /**
     * @var MCityTable
     */
    private static $_instance = null;
    protected $table = 'm_city';

    /**
     * @return MCityTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new MCityTable();
        }
        return self::$_instance;
    }

    // ここに実装して下さい

    public function get_pref_id($city_id) {
        $sql = "select pref_id from {$this->getTableName(true)} where id=?";
        $row = $this->fetch($sql, array($city_id));
        if (!empty($row['pref_id'])) {
            return $row['pref_id'];
        }
        return null;
    }

    public function getListByPref($pref_id) {
        $sql = "SELECT id, name FROM {$this->getTableName(true)} WHERE pref_id=?";
        $list = $this->fetchAll($sql, array($pref_id));
        return $list;
    }

    public function getPrefCityName($city_id) {
        $sql = "select concat(A.name, B.name) as pref_city_name from m_city B left join m_pref A on (B.pref_id=A.id) where B.id=?";
        $row = $this->fetch($sql, array($city_id));
        if (empty($row['pref_city_name'])) {
            return "";
        }
        return $row['pref_city_name'];
    }
}
