<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StaffTable extends DbModel {
    
    /**
     *
     * @var StaffTable
     */
    private static $_instance = null;
    protected $table = 'staff';
    public $timestamps = false;
    /**
     *
     * @return StaffTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new StaffTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getLoginInfo($loginid, $password, $pschool_id = null) {
        $sql = " SELECT A.*, B.login_id, C.business_divisions, C.language, C.country_code FROM staff A ";
        $sql .= " left join login_account B on (A.login_account_id=B.id) ";
        $sql .= " left join pschool C on (A.pschool_id=C.id) ";
        $sql .= " WHERE B.login_id = ? AND B.login_pw=MD5(?) ";
        $sql .= " AND B.active_flag=1 and A.DELETE_DATE IS NULL ";
        $sql .= " and B.DELETE_DATE IS NULL";
        $bind = array (
                $loginid,
                $password
        );

        if ($pschool_id !== "" || $pschool_id !== null) {
            $sql .= " AND A.pschool_id = ? ";
            $bind [] = $pschool_id;
        }
        return $this->fetch ( $sql, $bind );
    }
    public function loadWithLoginAccount($id) {
        $sql = " SELECT A.*, B.login_id, B.auth_type, B.authority, B.edit_authority, C.withdrawal_day, C.business_divisions, C.language, C.country_code FROM staff A ";
        $sql .= " left join login_account B on (A.login_account_id=B.id) ";
        $sql .= " left join pschool C on (A.pschool_id=C.id) ";
        $sql .= " WHERE A.id=? AND B.active_flag=1 and A.DELETE_DATE IS NULL ";
        $sql .= " and B.DELETE_DATE IS NULL and C.DELETE_DATE IS NULL";
        $bind = array (
                $id 
        );
        return $this->fetch ( $sql, $bind );
    }
    public function getMailList($school_id, $broadcast_id=NULL ,$staff_id = NULL) {
        $sql = " SELECT t.*, l.login_id as login_id";
        if($broadcast_id !=null){
            $sql .=" ,mm.send_date as staff_time_send ";
        }
        $sql .= " FROM staff t join login_account l on t.login_account_id = l.id";
        if($broadcast_id !=null){
            $sql .=" LEFT JOIN mail_message mm ON t.id = mm.target_id ";
            $sql .=" AND mm.relative_ID= ".$broadcast_id;
            $sql .=" AND mm.target= 4 ";// 4 => staff
            $sql .=" AND mm.type= 6 "; // 6 => broadcast
        }
        $sql .= " WHERE t.pschool_id = ? ";
        $bind = array (
                $school_id 
        );
        
        $sql .= " AND t.active_flag	= 1 ";
        $sql .= " AND t.delete_date is null ";
        
        if (isset ( $staff_id )) {
            $sql .= "AND t.id = ? ";
            $bind [] = $staff_id;
        }
        
        $ret = $this->fetchAll ( $sql, $bind );
        if (isset ( $staff_id ) && ! empty ( $ret )) {
            return $ret [0];
        } else {
            return $ret;
        }
    }
    public function getTimeSendStaff($addressee_id) {
        $bind = array ();
        $sql = "SELECT MAX(time_send) as time_send FROM `broadcast_mail` bm JOIN broadcast_mail_addressee_rel bmar on bmar.broadcast_mail_id = bm.id
        WHERE bmar.addressee_type = 5 and bmar.addressee_id = ?";
        $bind [] = $addressee_id;
        $a = $this->fetch ( $sql, $bind );
        return implode ( "", $a );
    }
}