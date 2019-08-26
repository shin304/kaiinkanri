<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ZipcodeAddressTable extends DbModel
{
    /**
     *
     * @var ZipcodeAddressTable
     */
    private static $_instance = null;

    /**
     *
     * @return ZipcodeAddressTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ZipcodeAddressTable ();
        }
        return self::$_instance;
    }
    protected $table = 'zipcode_address';

    public function getAddressFromZipcode($zipcode){

        $sql  = "SELECT m_city.id as city_id, m_pref.id as pref_id, zipcode_address.area_name as address
        FROM zipcode_address 
        LEFT JOIN m_city ON m_city.name = zipcode_address.city_name
        LEFT JOIN m_pref ON m_pref.name = zipcode_address.prefecture
        WHERE zipcode = '".$zipcode."'
        AND update_flag != 2 
        AND update_reason != 6";

        $res = $this->fetch($sql);

        return $res;
    }
}
