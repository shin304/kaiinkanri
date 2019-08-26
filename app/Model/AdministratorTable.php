<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdministratorTable extends DbModel {
    private static $_instance = null;
    protected $table = 'administrator';
    public $timestamps = false;
    /**
     *
     * @return AdministratorTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new AdministratorTable ();
        }
        return self::$_instance;
    }
    public function getLoginInfo($loginid, $password) {
        $sql = 'SELECT A.*,B.login_id FROM administrator A left join login_account B on (A.login_account_id=B.id) WHERE B.login_id=? AND B.login_pw=MD5(?) AND B.active_flag=1 and A.DELETE_DATE IS NULL and B.DELETE_DATE IS NULL';
        $bind = array (
                $loginid,
                $password 
        );
        return $this->fetch ( $sql, $bind );
    }
    public function loadWithLoginAccount($id) {
        $sql = 'SELECT A.*, B.login_id, B.edit_authority FROM administrator A left join login_account B on (A.login_account_id=B.id) WHERE A.id=? AND B.active_flag=1 and A.DELETE_DATE IS NULL and B.DELETE_DATE IS NULL';
        $bind = array (
                $id 
        );
        return $this->fetch ( $sql, $bind );
    }
}
