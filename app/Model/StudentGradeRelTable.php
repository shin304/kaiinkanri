<?PHP

namespace App\Model;

class StudentGradeRelTable extends DbModel {
    
    /**
     *
     * @var StudentGradeRelTable
     */
    private static $_instance = null;
    protected $table = 'student_grade_rel';
    public $timestamps = false;
    
    /**
     *
     * @return StudentGradeRelTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new StudentGradeRelTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    
    // 履歴取得
    public function getGradeHistory($student_id, $date = null) {
        $bind = array (
                $student_id 
        );
        
        $sql = ' SELECT A.id, A.grade_id, A.gain_date, A.active_flag, ';
        $sql .= ' B.grade_name, B.grade_color ';
        $sql .= ' FROM student_grade_rel as A ';
        $sql .= ' LEFT JOIN student_grade as B ON (A.grade_id=B.id) ';
        $sql .= ' WHERE A.student_id = ? ';
        $sql .= ' AND A.delete_date IS NULL ';
        if (! empty ( $date )) {
            $sql .= ' AND A.gain_date <= ? ';
            $bind [] = $date;
        }
        $sql .= ' ORDER BY gain_date DESC';
        
        $ret = $this->fetchAll ( $sql, $bind );
        if (! empty ( $date ) && ! empty ( $ret [0] )) {
            $res = array ();
            $res ['student_grade_id'] = empty ( $ret [0] ['id'] ) ? null : $ret [0] ['id'];
            $res ['student_grade_name'] = empty ( $ret [0] ['grade_color'] ) ? null : $ret [0] ['grade_color'];
            $res ['gain_date'] = empty ( $ret [0] ['gain_date'] ) ? null : $ret [0] ['gain_date'];
            return $res;
        }
        return $ret;
    }
}