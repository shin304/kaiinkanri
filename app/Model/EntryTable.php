<?PHP

namespace App\Model;

class EntryTable extends DbModel {
    
    /**
     *
     * @var EntryTable
     */
    private static $_instance = null;
    protected $table = 'entry';
    public $timestamps = false;
    /**
     *
     * @return EntryTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new EntryTable ();
        }
        return self::$_instance;
    }
    public function getListbyEvent($student_id) {
        $bind = array ();
        $sql = "SELECT e.id, CASE WHEN e.entry_type = 1 THEN c.consultation_title WHEN e.entry_type = 2 THEN c2.course_title END AS title,  CASE WHEN e.entry_type = 1 THEN '面談' WHEN e.entry_type = 2 THEN 'イベント' END AS type, ";
        $sql .= "CASE WHEN  e.entry_type = 1 AND e.status = 1 THEN e.proposed_date WHEN  e.entry_type = 1 AND e.status != 1 THEN e.proposed_date ELSE NULL END AS date_time, ";
        $sql .= "CASE WHEN  e.entry_type = 1 AND e.status = 1 THEN e.proposed_date2 WHEN  e.entry_type = 1 AND e.status != 1 THEN e.proposed_date2 ELSE NULL END AS date_time2, ";
        $sql .= "CASE WHEN e.enter = 0 THEN '不参加' WHEN e.enter = 1 THEN '参加' WHEN e.enter is NULL THEN '未回答' END AS status2 ";
        $sql .= " FROM entry e ";
        $sql .= "LEFT JOIN consultation c ON ( e.entry_type = 1 AND c.id = e.relative_id AND c.delete_date is null ) ";
        $sql .= "LEFT JOIN course c2 ON ( e.entry_type = 2 AND c2.id = e.relative_id AND c2.delete_date is null ) ";
        $sql .= " where e.student_id = ? and e.delete_date is null";
        $bind [] = $student_id;
        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }
    
    /*
     * public function getListbyNews($pschool_id)
     * {
     *
     * $bind = array();
     * $sql = "SELECT e.id, e.entry_type, e.relative_id, CASE WHEN e.entry_type = 1 THEN c.consultation_title WHEN e.entry_type = 2 THEN c2.course_title END AS title , " ;
     * $sql .="CASE WHEN e.update_date is NULL THEN e.register_date ELSE e.update_date END AS date " ;
     * $sql .= " FROM entry e ";
     * $sql .= "LEFT JOIN consultation c ON ( e.entry_type = 1 AND c.id = e.relative_id AND c.delete_date is null AND c.pschool_id = ? ) " ;
     * $sql .= "LEFT JOIN course c2 ON ( e.entry_type = 2 AND c2.id = e.relative_id AND c2.delete_date is null AND c2.pschool_id = ? ) " ;
     * $bind[] = $pschool_id ;
     * $sql .= " where If (e.update_date is NULL, e.register_date, e.update_date) >= DATE_ADD(NOW(),INTERVAL -5 DAY) and e.delete_date is null " ;
     * $sql .= " group by e.entry_type, e.relative_id " ;
     * $bind[] = $pschool_id ;
     * $res = $this->fetchAll($sql, $bind);
     * return $res;
     *
     * }
     *
     */
    public function getListbyCourse($pschool_id, $period = null) {
        $bind = array();
        $sql = "SELECT A.id as entry_id, B.id as course_id, S.student_name, 
                (CASE WHEN A.update_date is NULL THEN A.register_date ELSE A.update_date END) as date,
                A.view_date,  B.course_title as title, B.send_mail_flag, 'news' as notice_type
                FROM entry A
                INNER JOIN course B ON A.relative_id = B.id
                INNER JOIN student S ON S.id = A.student_id
                WHERE B.pschool_id = {$pschool_id} 
                AND A.delete_date IS NULL AND B.delete_date IS NULL
                AND A.entry_type = 2
                AND A.payment_method IS NOT NULL
                AND A.enter = 1 ";
        if (!is_null($period)) {
            $sql .= "AND If (A.update_date is NULL, A.register_date, A.update_date) >= DATE_ADD(NOW(),INTERVAL -? DAY) ";
            $bind [] = $period;
        }
        $sql .= " order by date desc";
        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }

    public function getListbyProgram($pschool_id, $period = null) {
        $bind = array();
        $sql = "SELECT A.id as entry_id, B.id as program_id, S.student_name, 
                (CASE WHEN A.update_date is NULL THEN A.register_date ELSE A.update_date END) as date,
                A.view_date,  B.program_name as title, B.send_mail_flag, 'program' as notice_type
                FROM entry A
                INNER JOIN program B ON A.relative_id = B.id
                INNER JOIN student S ON S.id = A.student_id
                WHERE B.pschool_id = {$pschool_id} 
                AND A.delete_date IS NULL AND B.delete_date IS NULL
                AND A.entry_type = 3
                AND A.payment_method IS NOT NULL
                AND A.enter = 1 ";
        if (!is_null($period)) {
            $sql .= "AND If (A.update_date is NULL, A.register_date, A.update_date) >= DATE_ADD(NOW(),INTERVAL -? DAY) ";
            $bind [] = $period;
        }
        $sql .= " order by date desc";
        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }

    public function getStudentListbyEventId($pschool_id, $event_id) {
        $bind = array ();
        $sql = "select * from student where" . " id in (select student_id from entry where relative_id=? and delete_date is null)" . " and pschool_id=?" . " and active_flag=1" . " and delete_date is null";
        $bind [] = $event_id;
        $bind [] = $pschool_id;
        $sql .= " order by student_name_kana ASC";
        
        return $this->fetchAll ( $sql, $bind );
    }
    public function getEntryListbyEventId($pschool_id, $event_id) {
        $sql = "SELECT " . "student.id AS student_id" . ", student.student_no AS student_no" . ", student.student_name" . ", parent.parent_name" . ", parent.id AS parent_id" . ", entry.proposed_date " . ", entry.proposed_date2 " . ", entry.enter " . "FROM " . "entry " . "INNER JOIN consultation " . "ON (" . "entry.relative_id = consultation.id " . "AND consultation.active_flag = 1 " . "AND consultation.pschool_id = ?" . ")" . "INNER JOIN student " . "ON (" . "entry.student_id = student.id " . "AND student.active_flag = 1" . ") " . "INNER JOIN parent ON (student.parent_id = parent.id) " . "WHERE entry.relative_id = ? " . "ORDER BY entry.enter DESC, entry.proposed_date, entry.proposed_date2 ASC";
        $bind = array (
                $pschool_id,
                $event_id 
        );
        return $this->fetchAll ( $sql, $bind );
    }
    public function getStudentCountbyEventType($pschool_id, $request = NULL) {
        $list_count = 0;
        $res = $this->getStudentListbyEventType ( $pschool_id, $request );
        if (! empty ( $res ))
            $list_count = count ( $res );
        return $list_count;
    }
    public function getStudentListbyEventType($pschool_id, $request = NULL) {
        $sql = " SELECT s.* ";
        $sql .= " FROM entry AS e ";
        $sql .= " INNER JOIN student as s ON e.student_id = s.id ";
        $sql .= " WHERE e.delete_date is NULL ";
        // $sql .= " AND s.active_flag = 1 AND s.delete_date is NULL ";
        
        $bind = array ();
        
        if (! empty ( $request ['entry_type'] )) {
            $sql .= " AND e.entry_type = ? ";
            $bind [] = $request ['entry_type'];
        }
        if (! empty ( $request ['event_id'] )) {
            $sql .= " AND e.relative_id = ? ";
            $bind [] = $request ['event_id'];
        }
        if (! empty ( $request ['enter'] )) {
            $sql .= " AND e.enter = ? ";
            $bind [] = $request ['enter'];
        }
        
        return $this->fetchAll ( $sql, $bind );
    }
    
    // =========================================================================
    // ここから、アクシス柔術向け機能追加版
    // =========================================================================
    public function getStudentListbyEventTypeAxis($pschool_id, $request = NULL) {

        $sql = " SELECT s.*, cfp.fee_plan_name, cfp.fee, IF(ISNULL(e.payment_method), 0, e.total_member) as total_member, e.payment_method ";
        $sql .= " FROM entry AS e ";
        $sql .= " INNER JOIN student as s ON e.student_id = s.id ";
        if (!empty( $request ['entry_type'] ) && $request ['entry_type'] == 2) { // Event
            $sql .= " INNER JOIN student_course_rel as scr ON s.id = scr.student_id";
            $sql .= " AND e.relative_id = scr.course_id";
            $sql .= " AND scr.delete_date IS NULL";
            $sql .= " LEFT JOIN course_fee_plan as cfp ON scr.plan_id = cfp.id ";
            
        } elseif (!empty( $request ['entry_type'] ) && $request ['entry_type'] == 3) { // Program
            $sql .= " INNER JOIN student_program as scr ON s.id = scr.student_id";
            $sql .= " AND e.relative_id = scr.program_id";
            $sql .= " AND scr.delete_date IS NULL";
            $sql .= " LEFT JOIN program_fee_plan as cfp ON scr.plan_id = cfp.id ";
        }
        $sql .= " WHERE e.delete_date is NULL ";
        // $sql .= " AND s.active_flag = 1 AND s.delete_date is NULL ";
        
        $bind = array ();
        
        if (! empty ( $request ['entry_type'] )) {
            $sql .= " AND e.entry_type = ? ";
            $bind [] = $request ['entry_type'];
        }
        if (! empty ( $request ['relative_id'] )) {
            $sql .= " AND e.relative_id = ? ";
            $bind [] = $request ['relative_id'];
        }
        if (! empty ( $request ['enter'] )) {
            $sql .= " AND e.enter = ? ";
            $bind [] = $request ['enter'];
        }

        return $this->fetchAll ( $sql, $bind );
    }

    public function getTotalFeeByEventId($event_id) {
        $sql = "SELECT SUM(CASE WHEN c.payment_unit=2 THEN c.fee ELSE c.fee*e.total_member END) as total_fee 
                FROM entry e 
                INNER JOIN  student_course_rel s ON (s.course_id = e.relative_id AND s.student_id = e.student_id) 
                INNER JOIN course_fee_plan c ON (s.plan_id = c.id) 
                WHERE e.relative_id = ? AND e.enter =1 AND e.entry_type = 2 AND e.payment_method IS NOT NULL ";
        $res = $this->fetch($sql, [$event_id]);
        if ($res['total_fee']) {
            return $res['total_fee'];
        } else {
            return 0;
        }
    }

    public function getTotalFeeByProgramId($program_id) {
        $sql = "SELECT SUM(CASE WHEN c.payment_unit=2 THEN c.fee ELSE c.fee*e.total_member END) as total_fee 
                FROM entry e 
                INNER JOIN  student_program s ON (s.program_id = e.relative_id AND s.student_id = e.student_id) 
                INNER JOIN program_fee_plan c ON (s.plan_id = c.id) 
                WHERE e.relative_id = ? AND e.enter =1 AND e.entry_type = 3 AND e.payment_method IS NOT NULL";
        $res = $this->fetch($sql, [$program_id]);
        if ($res['total_fee']) {
            return $res['total_fee'];
        } else {
            return 0;
        }
    }
}
