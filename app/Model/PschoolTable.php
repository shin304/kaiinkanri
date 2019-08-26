<?php

namespace App\Model;

use App\Common\Constants;
use Illuminate\Database\Eloquent\Model;

class PschoolTable extends DbModel {
    /**
     *
     * @var PschoolTable
     */
    private static $_instance = null;
    protected $table = 'pschool';
    public $timestamps = false;
    /**
     *
     * @return PschoolTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PschoolTable ();
        }
        return self::$_instance;
    }
    public function getHonbu($group_id) {
        $sql = "SELECT pschool.*
			FROM pschool
			INNER JOIN hierarchy ON hierarchy.pschool_id = pschool.id
			WHERE pschool.group_id = ?
			ORDER BY layer ASC";
        return $this->fetch ( $sql, array (
                $group_id 
        ) );
    }
    public function getLoginInfo($loginid, $password, $pschool_id = null, $kyoukai = false) {
        $sql = "SELECT A.*, A.id AS pschool_id, B.login_id 
		FROM pschool A LEFT JOIN login_account B 
		ON (A.login_account_id = B.id) 
		WHERE B.login_id = ? AND B.login_pw=MD5(?) 
		AND B.active_flag = 1 and A.DELETE_DATE IS NULL 
		AND B.DELETE_DATE IS NULL ";
        
        if (! $kyoukai) {
            $sql .= " AND A.group_id != 0";
        } else {
            $sql .= " AND A.group_id = 0";
        }
        $bind = array (
            $loginid,
            $password
        );

        if ($pschool_id) {
            $sql .= " AND A.id = ? ";
            $bind [] = $pschool_id;
        }

        return $this->fetch ( $sql, $bind );
    }
    public function loadWithLoginAccount($id) {
        $sql = "SELECT A.*,B.login_id,B.auth_type,B.authority,B.edit_authority,C.name as pref_name,D.name as city_name FROM pschool A left join login_account B on (A.login_account_id=B.id) left join m_pref C on (A.pref_id=C.id) left join m_city D on (A.city_id=D.id) WHERE A.id=? AND B.active_flag=1 and A.DELETE_DATE IS NULL and B.DELETE_DATE IS NULL";
        $bind = array (
                $id 
        );
        return $this->fetch ( $sql, $bind );
    }
    public function isNormalShibu($pschool_id) {
        $sql = "SELECT hierarchy.manage_flag FROM pschool INNER JOIN hierarchy ON hierarchy.pschool_id = pschool.id WHERE pschool.id = ?";
        $res = $this->fetch ( $sql, array (
                $pschool_id 
        ) );
        if ($res && $res ['manage_flag'] == 0) {
            return true;
        } else {
            return false;
        }
    }
    /* get school, parent, student info from message_key (table mail_message) */
    public function getSchoolInfoPortal($message_key)
    {
        $bind = array();
        $sql = "
SELECT ps.id, ps.name as school_name, ps.amount_display_type, ps.tel as school_tel, ps.language, ps.zip_code, ps.building, ps.mailaddress, ps.daihyou, 
 mp.name as school_pref_name, mc.name as school_city_name, ps.address as school_address, s.student_name, s.student_category, s.mailaddress as student_mail, p.parent_name, ps.official_position
FROM pschool ps
INNER JOIN mail_message m ON m.pschool_id = ps.id
INNER JOIN student s ON m.student_id = s.id
INNER JOIN parent p ON m.parent_id = p.id
INNER JOIN m_pref mp ON ps.pref_id = mp.id
INNER JOIN m_city mc ON ps.city_id = mc.id
WHERE m.delete_date IS NULL AND ps.delete_date IS NULL AND s.delete_date IS NULL AND p.delete_date IS NULL AND m.message_key = ? ";
        $bind [] = $message_key;
        return $this->fetch($sql, $bind);
    }

    /**
     * @return list school set batch date = 99 (end of month)
     */
    public function getListSchoolBatchEndMonth(){
        $sql = " SELECT * FROM pschool 
                    WHERE delete_date IS NULL 
                    AND active_flag = 1
                    AND (invoice_batch_date = ".date('t')."
                        OR invoice_batch_date = 99) ";
        return $this->fetchAll($sql);
    }

    /*
     * Toran add : 2018-01-10
     * check if this school have access their limit account or not
     * base on plan and hyerachi
     * return result['result'] = 'ERROR' -> limited and cannot add more member
     * result['message'] -> error code from CONSTANT
     */
    public function reachLimitAccessOfPlan(){

        $result = array(
            'result' =>'SUCCESS',
            'message' => ''
        );

        $pschool_id = session()->get('school.login.id');

        $pschool = PschoolTable::getInstance()->load($pschool_id);

        $count_sql = "SELECT COUNT(id) AS TOTAL, 
                            SUM(CASE 
                                  WHEN active_flag = 1 
                                  THEN 1 ELSE 0 END) as ACTIVE 
                      FROM student 
                      WHERE pschool_id = ? 
                      AND delete_date IS NULL";

        $bind[] = $pschool_id;

        $students = $this->fetch($count_sql,$bind);

        if(!empty($pschool) && !empty($students)){

            if( $pschool['limit_number_register']!= null && $students['TOTAL'] >= $pschool['limit_number_register'] ){

                $result['result'] = 'ERROR';

                $result['message'] = Constants::ERROR_LIMIT_PLAN_ACCESS['001'];

            }elseif( $pschool['limit_number_active']!= null && $students['TOTAL'] >= $pschool['limit_number_active'] ){

                $result['result'] = 'ERROR';

                $result['message'] = Constants::ERROR_LIMIT_PLAN_ACCESS['002'];
            }
        }

        return $result;

    }
}
