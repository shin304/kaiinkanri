<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserTable extends DbModel {
    /**
     *
     * @var UserTable
     */
    private static $_instance = null;
    protected $table = 'users';
    
    /**
     *
     * @return UserTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new UserTable ();
        }
        return self::$_instance;
    }
}
