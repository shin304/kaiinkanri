<?PHP

namespace App\Model;

class ClassTeacherRelTable extends DbModel {
    
    /**
     *
     * @var ClassTeacherRelTable
     */
    private static $_instance = null;
    protected $table = 'class_teacher_rel';
    public $timestamps = false;
    /**
     *
     * @return ClassTeacherRelTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ClassTeacherRelTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getTeacherIDs($request) {
        $res = array ();
        
        if (isset ( $request ['id'] )) {
            $bind = array ();
            
            $sql = " Select t.teacher_id ";
            $sql .= " FROM class_teacher_rel as t ";
            $sql .= " WHERE t.delete_date is NULL ";
            $sql .= " AND t.class_id = ? ";
            $bind [] = $request ['id'];
            
            $res = $this->fetchAll ( $sql, $bind );
        }
        
        $ret = array ();
        // class
        if (! empty ( $request ['teacher_id'] ))
            $ret [] = $request ['teacher_id'];
            // class_teacher_rel
        if (! empty ( $res )) {
            foreach ( $res as $v ) {
                if (! empty ( $request ['teacher_id'] ) && $request ['teacher_id'] != $v ['teacher_id']) {
                    $ret [] = $v ['teacher_id'];
                } elseif (empty ( $request ['teacher_id'] )) {
                    $ret [] = $v ['teacher_id'];
                }
            }
        }
        // null
        if (empty ( $ret ))
            $ret [] = "";
        
        return $ret;
    }
}