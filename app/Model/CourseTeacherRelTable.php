<?PHP

namespace App\Model;

class CourseTeacherRelTable extends DbModel {
    
    /**
     *
     * @var CourseTeacherRelTable
     */
    private static $_instance = null;
    protected $table = 'course_teacher_rel';
    public $timestamps = false;
    /**
     *
     * @return CourseTeacherRelTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new CourseTeacherRelTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getTeacherIDs($request) {
        $res = array ();
        
        if (isset ( $request ['id'] )) {
            $bind = array ();
            
            $sql = " Select t.teacher_id ";
            $sql .= " FROM course_teacher_rel as t ";
            $sql .= " WHERE t.delete_date is NULL ";
            $sql .= " AND t.course_id = ? ";
            $bind [] = $request ['id'];
            
            $res = $this->fetchAll ( $sql, $bind );
        }
        
        $ret = array ();
        // class_teacher_rel
        if (! empty ( $res )) {
            foreach ( $res as $v ) {
                $ret [] = $v ['teacher_id'];
            }
        }
        // null
        // //// if (empty($ret)) $ret[] = "";
        
        return $ret;
    }
}