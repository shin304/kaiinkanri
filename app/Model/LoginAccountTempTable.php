<?php

namespace App\Model;

class LoginAccountTempTable extends DbModel {
    /**
     *
     * @var LoginAccountTempTable
     */
    private static $_instance = null;
    
    /**
     *
     * @return LoginAccountTempTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new LoginAccountTempTable ();
        }
        return self::$_instance;
    }
    protected $table = 'login_account_temp';
    public $timestamps = false;
    
    
}
