<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CoachTable extends DbModel {

    /**
     *
     * @var CoachTable
     */
    private static $_instance = null;
    protected $table = 'coach';
    public $timestamps = false;
    
    /**
     *
     * @return CoachTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new CoachTable ();
        }
        return self::$_instance;
    }

    // 2017-6-7 Tung Nguyen add ORM

    /**
     * The Events that belong to the coach
     */
    public function courses()
    {
        return $this->belongsToMany('App\Model\CourseTable', 'course_coach', 'coach_id', 'course_id')->wherePivot('delete_date',null);
    }

    /**
     * The Classes that belong to the coach
     */
    public function classes()
    {
        return $this->belongsToMany('App\Model\ClassTable', 'class_coach', 'coach_id', 'class_id')->wherePivot('delete_date',null);
    }

    /**
     * The Programs that belong to the coach
     */
    public function programs()
    {
        return $this->belongsToMany('App\Model\LessonTable', 'lesson_coach', 'coach_id', 'lesson_id')->wherePivot('delete_date',null);
    }
    
    /**
     * Coachのログイン確認
     *
     * @param unknown $login_id            
     * @param unknown $pwd            
     * @return CoachTable
     */
    public function getLoginInfo($login_id, $pwd, $pschool_id = null) {
        $sql = " SELECT A.*, B.login_id, C.business_divisions, C.language, C.country_code FROM coach A ";
        $sql .= " LEFT JOIN login_account B ON (A.login_account_id=B.id) ";
        $sql .= " LEFT JOIN pschool C ON (A.pschool_id=C.id) ";
        $sql .= " WHERE B.login_id = ? AND B.login_pw=MD5(?) ";
        $sql .= " AND B.active_flag=1 AND A.DELETE_DATE IS NULL ";
        $sql .= " AND B.DELETE_DATE IS NULL";
        $sql .= " AND C.group_id != 0";
        $bind = array (
                $login_id,
                $pwd
        );

        if ($pschool_id !== "" || $pschool_id !== null) {
            $sql .= " AND A.pschool_id = ? ";
            $bind [] = $pschool_id;
        }
        return $this->fetch ( $sql, $bind );
    }
    public function loadWithLoginAccount($id) {
        $sql = " SELECT A.*, B.login_id, B.auth_type, B.authority, B.edit_authority, C.withdrawal_day, C.business_divisions, C.language, C.country_code FROM coach A ";
        $sql .= " left join login_account B on (A.login_account_id=B.id) ";
        $sql .= " left join pschool C on (A.pschool_id=C.id) ";
        $sql .= " WHERE A.id=? AND B.active_flag=1 and A.DELETE_DATE IS NULL ";
        $sql .= " and B.DELETE_DATE IS NULL and C.DELETE_DATE IS NULL";
        $bind = array (
                $id 
        );
        return $this->fetch ( $sql, $bind );
    }
    public function getListOfCoach($school_id, $input_search) {
        $bind = array ();
        $inner_join = "";
        $where_clause = " WHERE a.pschool_id = ? AND a.delete_date is NULL ";
        $bind [] = $school_id;
        
        if (! empty ( $input_search ['coach_name'] )) {
            $where_clause .= " AND (a.coach_name like ? OR a.coach_name_kana like ?)";
            $bind [] = "%" . $input_search ['coach_name'] . "%";
            $bind [] = "%" . $input_search ['coach_name'] . "%";
        }
        if (! empty ( $input_search ['subject_id'] )) {
            $tmp = explode ( "|", $input_search ['subject_id'] );
            $type = $tmp [0];
            $id = $tmp [1];
            switch ($type) {
                case "class" :
                    // Get coach list by class
                    $inner_join .= " INNER JOIN class_coach c ON a.id = c.coach_id ";
                    $where_clause .= " AND c.class_id = ? ";
                    $bind [] = $id;
                    break;
                case "course" :
                    // Get coach list by course
                    $inner_join .= " INNER JOIN course_coach c ON a.id = c.coach_id ";
                    $where_clause .= " AND c.course_id = ? ";
                    $bind [] = $id;
                    break;
                case "lesson" :
                    // Get coach list by course
                    $inner_join .= " INNER JOIN lesson_coach lc ON a.id = lc.coach_id ";
                    $where_clause .= " AND lc.lesson_id = ? ";
                    $bind [] = $id;
                    break;
            }
        }
        
        $sql = " SELECT a.* FROM coach a " . $inner_join . $where_clause;
        $sql .= " GROUP BY a.id";
        return $this->fetchAll ( $sql, $bind );
    }
    public function getLastId() {
        $sql = " SELECT id FROM coach ORDER BY id DESC LIMIT 1";
        return $this->fetch ( $sql );
    }
    public function getMailList($school_id,$broadcast_id = NULL, $teacher_id = NULL) {
        $sql = " SELECT t.*, a.login_id ";
        if($broadcast_id !=null){
            $sql .=",mm.send_date as teacher_time_send";
        }
        $sql .= " FROM coach t ";
        $sql .= " INNER JOIN login_account a ON a.id = t.login_account_id";
        if($broadcast_id !=null){
            $sql .=" LEFT JOIN mail_message mm ON t.id = mm.target_id ";
            $sql .=" AND mm.relative_ID= ".$broadcast_id;
            $sql .=" AND mm.target= 3 ";// 3 => teacher
            $sql .=" AND mm.type= 6 "; // 6 => broadcast
        }
        
        $sql .= " WHERE t.pschool_id = ? ";
        $bind = array (
                $school_id 
        );
        $sql .= " AND t.active_flag	= 1 ";
        $sql .= " AND t.delete_date is null ";
        
        if (isset ( $teacher_id )) {
            $sql .= "AND t.id = ? ";
            $bind [] = $teacher_id;
        }
        $ret = $this->fetchAll ( $sql, $bind );
        if (isset ( $teacher_id ) && ! empty ( $ret )) {
            return $ret [0];
        } else {
            return $ret;
        }
    }
    public function getTimeSendTeacher($addressee_id) {
        $bind = array ();
        $sql = "SELECT MAX(time_send) as time_send FROM `broadcast_mail` bm JOIN broadcast_mail_addressee_rel bmar on bmar.broadcast_mail_id = bm.id
        WHERE bmar.addressee_type = 4 and bmar.addressee_id = ?";
        $bind [] = $addressee_id;
        $a = $this->fetch ( $sql, $bind );
        return implode ( "", $a );
    }


    /**
     * Get active (delete_date is null) coach list by coach_name or coach_name_kana
     *
     * @param  String $coach_name
     * @return $this
     */
    public function getActiveCoachList($coach_name = null) {
        return $this->where( function($q) use ($coach_name) {
            $q->where('coach_name', 'LIKE', '%'. $coach_name .'%')
                ->orWhere('coach_name_kana', 'LIKE', '%'. $coach_name .'%');
        })->where([
            'pschool_id' => session ('school.login.id'), 
            'delete_date' => NULL
            ])->get()->sortBy('coach_name_kana');
    }
}
