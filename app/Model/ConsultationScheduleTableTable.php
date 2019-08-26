<?PHP

namespace App\Model;

class ConsultationScheduleTableTable extends DbModel {
    
    /**
     *
     * @var ConsultationScheduleTableTable
     */
    private static $_instance = null;
    protected $table = 'consultation_schedule_table';
    public $timestamps = false;
    
    /**
     *
     * @return ConsultationScheduleTableTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ConsultationScheduleTableTable ();
        }
        return self::$_instance;
    }
    public function getScheduleListbyConsultationId($pschool_id, $consultation_id, $student_id = NULL) {
        $bind = array (
                $pschool_id,
                $consultation_id 
        );
        
        $where = array ();
        if (! is_null ( $student_id )) {
            $where [] = "schedule.student_id = ? ";
            $bind [] = $student_id;
        }
        
        $where = ! empty ( $where ) ? " AND " . implode ( " AND ", $where ) : "";
        
        $sql = "SELECT " . "student.id AS student_id" . ", student.student_no AS student_no" . ", student.student_name" . ", parent.id AS parent_id" . ", parent.parent_name" . ", parent.parent_mailaddress1" . ", MIN(schedule.start_time) AS start_time " . ", MIN(schedule.proc_date) AS proc_date " . "FROM " . "consultation_schedule_table AS schedule " . "INNER JOIN consultation " . "ON (" . "schedule.consultation_id = consultation.id " . "AND consultation.active_flag = 1 " . "AND consultation.pschool_id = ?" . ")" . "INNER JOIN student " . "ON (" . "schedule.student_id = student.id " . "AND student.active_flag = 1" . ") " . "INNER JOIN parent ON (student.parent_id = parent.id) " . "WHERE " . "schedule.consultation_id = ? " . "AND schedule.delete_date IS NULL " . $where . "GROUP BY schedule.student_id " . "ORDER BY schedule.proc_date ASC, schedule.start_time ASC";
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    public function getConsultationScheduleList($consultation_id) {
        $bind = array (
                $consultation_id 
        );
        
        $sql = " SELECT c.*, s.proc_date,s.start_time FROM consultation c LEFT JOIN consultation_schedule_table s";
        $sql .= " ON s.consultation_id = c.id AND c.active_flag = 1 ";
        $sql .= " WHERE c.id = ? ";
        $sql .= " ORDER BY s.proc_date ASC,s.start_time ASC";
        
        return $this->fetch ( $sql, $bind );
    }
    public function getStudentCountByID($consultation_id, $request = NULL) {
        $list_count = 0;
        $list = $this->getStudentListByID ( $consultation_id, $request );
        if (! empty ( $list ))
            $list_count = count ( $list );
        return $list_count;
    }
    public function getStudentListByID($consultation_id, $request = NULL) {
        $sql = " SELECT DISTINCT t.student_id ";
        $sql .= " FROM consultation_schedule_table as t ";
        $sql .= " INNER JOIN student as s ON t.student_id = s.id ";
        $sql .= " WHERE t.delete_date is NULL AND t.consultation_id = ? ";
        $bind = array (
                $consultation_id 
        );
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
}