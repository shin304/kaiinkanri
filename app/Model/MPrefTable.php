<?php

namespace App\Model;

class MPrefTable extends DbModel
{
    /**
     * @var MPrefTable
     */
    private static $_instance = null;
    protected $table = 'm_pref';

    /**
     * @return MPrefTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new MPrefTable();
        }
        return self::$_instance;
    }

}
