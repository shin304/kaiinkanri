<?PHP

namespace App\Model;

class SchoolVisitHistoryTable extends DbModel {
    
    /**
     *
     * @var SchoolVisitHistoryTable
     */
    private static $_instance = null;
    protected $table = 'school_visit_history';
    public $timestamps = false;
    
    /**
     *
     * @return SchoolVisitHistoryTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new SchoolVisitHistoryTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    // 最新来校日
    public function getLastVisitByStudentID($student_id, $session) {
        $bind = array ();
        $sql = ' SELECT MAX(a.visit_date) as last_date ';
        $sql .= ' FROM school_visit_history a ';
        $sql .= ' WHERE a.student_id = ? AND a.visit_pschool_id = ? AND a.delete_date is null ';
        
        $bind [] = $student_id;
        $bind [] = $session ['id'];
        $res = array ();
        $res = $this->fetch ( $sql, $bind );
        
        return empty ( $res ['last_date'] ) ? null : $res ['last_date'];
    }
    
    // 来校履歴
    public function getVisitListByStudentID($student_id, $session) {
        $bind = array ();
        $sql = ' SELECT a.visit_month, COUNT(DISTINCT a.visit_date) as visit_count';
        $sql .= ' FROM school_visit_history a ';
        $sql .= ' WHERE a.student_id = ? AND a.visit_pschool_id = ? AND a.delete_date is null ';
        $sql .= ' GROUP BY a.visit_month ';
        $sql .= ' ORDER BY a.visit_month DESC ';
        
        $bind [] = $student_id;
        $bind [] = $session ['id'];
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        
        return json_decode ( json_encode ( $res ), true );
    }
    
    /**
     * 対象月の来校回数取得
     *
     * @param unknown $student_id            
     * @param unknown $pschool_id            
     * @param unknown $month            
     */
    public function getVisitTimesMonth($student_id, $pschool_id, $month) {
        $bind = array ();
        
        $bind [] = $student_id;
        $bind [] = $pschool_id;
        $bind [] = $month;
        
        $sql = " SELECT COUNT(visit_month) AS visit_count ";
        $sql .= " FROM school_visit_history ";
        $sql .= " WHERE student_id = ? ";
        $sql .= " AND pschool_id = ? ";
        $sql .= " AND visit_month = ?";
        $sql .= " AND delete_date IS NULL ";
        
        return $this->fetch ( $sql, $bind );
    }
    
    // プラン
    public function getClassListByStudentID($student_id, $session) {
        $bind = array ();
        $sql = ' SELECT a.id, a.class_name, a.class_description ';
        $sql .= ' , c.attend_times_div, c.attend_times, c.fee ';
        $sql .= ' , COUNT(d.visit_date >= ? or null) as month_visits ';
        $sql .= ' , COUNT(d.visit_date >= ? or null) as week_visits ';
        $sql .= ' FROM class a ';
        $sql .= ' INNER JOIN student_class b ON a.id = b.class_id AND b.student_id = ? ';
        $sql .= ' INNER JOIN class_fee_plan c ON b.plan_id = c.id ';
        $sql .= ' LEFT JOIN school_visit_history d ON d.visit_type = 1 AND d.visit_course_id = b.class_id AND d.student_id = ? ';
        $sql .= ' WHERE a.pschool_id = ? AND a.active_flag = 1 AND a.delete_date is null AND b.delete_date is null ';
        $sql .= ' GROUP BY a.id, a.class_name, a.class_description, c.attend_times_div, c.attend_times, c.fee ';
        $sql .= ' ORDER BY a.start_date DESC, a.close_date DESC ';
        $bind [] = date ( 'Y-m-01 00:00:00' );
        if (date ( 'w' ) == 1) { // 週は月曜日始まり
            $bind [] = date ( 'Y-m-d 00:00:00' );
        } else {
            $bind [] = date ( 'Y-m-d 00:00:00', strtotime ( "previous monday" ) );
        }
        $bind [] = $student_id;
        $bind [] = $student_id;
        $bind [] = $session ['id'];
        $res = array ();
        $res = json_decode ( json_encode ( $this->fetchAll ( $sql, $bind ) ), true );
        if (empty ( $res ) || empty ( $res [0] ['id'] ))
            $res = array ();
        return $res;
    }
    
    // プログラム
    public function getProgramListByStudentID($student_id, $session, $now = null) {
        $bind = array ();
        $sql = ' SELECT a.id, a.program_name, a.description, a.pschool_id, b.student_id ';
        $sql .= ' , COUNT(c.program_id) as program_all, COUNT(c.start_date < ? or null) as program_now, COUNT(c.start_date = ? or null) as is_now ';
        $bind [] = date ( 'Y-m-d 00:00:00' );
        $bind [] = date ( 'Y-m-d 00:00:00' );
        $sql .= ' FROM program a ';
        $sql .= ' INNER JOIN student_program b ON a.id = b.program_id ';
        $sql .= ' INNER JOIN lesson c ON a.id = c.program_id ';
        $sql .= ' WHERE a.active_flag = 1 AND a.delete_date is null AND a.pschool_id = ? ';
        $bind [] = $session ['id'];
        $sql .= ' AND b.student_id = ? AND b.active_flag = 1 AND b.delete_date is NULL ';
        $bind [] = $student_id;
        // $sql .= ' AND a.start_date <= ? AND a.close_date >= ? ';
        // $bind [] = date('Y-m-d 00:00:00');
        // $bind [] = date('Y-m-d 00:00:00');
        
        $sql .= ' GROUP BY a.id, a.program_name, a.description, a.pschool_id, b.student_id ';
        $sql .= ' ORDER BY a.close_date DESC ';
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        if (empty ( $res ) || empty ( $res [0] ['id'] ))
            $res = array ();
        if (! empty ( $now ) && ! empty ( $res )) {
            $ret = array ();
            foreach ( $res as $key => $value ) {
                if (! empty ( $value ['is_now'] )) {
                    $ret [] = $value;
                }
            }
            return json_decode ( json_encode ( $ret ), true );
        } else {
            return json_decode ( json_encode ( $res ), true );
        }
    }
    
    /**
     * 本日やっている講義の一覧を取得
     *
     * @param unknown $pschool_id            
     */
    public function getTodaysLecture($pschool_id) {
        $bind1 = array ();
        $bind2 = array ();
        $bind3 = array ();
        
        // プラン
        $bind1 [] = $pschool_id;
        $bind1 [] = date ( 'Y-m-d' );
        $bind1 [] = date ( 'Y-m-d' );
        
        $sql = " SELECT C.* ";
        $sql .= " FROM class AS C";
        $sql .= " WHERE C.pschool_id = ? ";
        $sql .= " AND C.delete_date IS NULL ";
        $sql .= " AND C.active_flag = 1 ";
        $sql .= " AND (C.start_date IS NOT NULL OR SUBSTRING(C.start_date, 1, 7) <= ?) ";
        $sql .= " AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        $sql .= " ORDER BY C.id ASC ";
        $ret1 = $this->fetchAll ( $sql, $bind1 );
        
        // イベント
        $bind2 [] = $pschool_id;
        $bind2 [] = date ( 'Y-m-d' );
        
        $sql = " SELECT C.* ";
        $sql .= " FROM course AS C";
        $sql .= " WHERE C.pschool_id = ? ";
        $sql .= " AND C.delete_date IS NULL ";
        $sql .= " AND C.active_flag = 1 ";
        $sql .= " AND SUBSTRING(C.start_date, 1, 7) = ? ";
        $sql .= " ORDER BY C.id ASC ";
        $ret2 = $this->fetchAll ( $sql, $bind2 );
        
        // プログラム
        $bind3 [] = $pschool_id;
        $bind3 [] = date ( 'Y-m-d 00:00:00' );
        
        $sql = " SELECT P.* ";
        $sql .= " FROM lesson AS L ";
        $sql .= " INNER JOIN program AS P ";
        $sql .= " ON L.program_id = P.id ";
        $sql .= " WHERE L.pschool_id = ? ";
        $sql .= " AND L.start_date = ? ";
        $sql .= " AND L.delete_date IS NULL ";
        $sql .= " AND P.delete_date IS NULL ";
        $sql .= " ORDER BY P.id ASC ";
        $ret3 = $this->fetchAll ( $sql, $bind3 );
        
        $ret = array ();
        foreach ( $ret1 as $item1 ) {
            $ret ['C_' . $item1 ['id']] = $item1 ['class_name'];
        }
        foreach ( $ret2 as $item2 ) {
            $ret ['E_' . $item2 ['id']] = $item2 ['course_title'];
        }
        
        foreach ( $ret3 as $item3 ) {
            $ret ['P_' . $item3 ['id']] = $item2 ['program_name'];
        }
        
        return json_decode ( json_encode ( $ret ), true );
    }
}