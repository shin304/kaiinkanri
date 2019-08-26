<?PHP

namespace App\Model;

require_once 'HierarchyTable.php';
class StudentGradeTable extends DbModel {
    
    /**
     *
     * @var StudentGradeTable
     */
    private static $_instance = null;
    protected $table = 'student_grade';
    public $timestamps = false;
    
    /**
     *
     * @return StudentGradeTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new StudentGradeTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    
    // 本部のID取得
    public function getParentID($pschool_id) {
        $pschool = $this->gethierarchyPschool ( $pschool_id );
        if (empty ( $pschool ))
            return $pschool_id;
        
        $ii = 0;
        while ( $ii < 1000 ) {
            $parent = $this->gethierarchyPschool ( null, $pschool ['parent_id'] );
            
            if (empty ( $parent ) || $pschool ['group_id'] != $parent ['group_id']) {
                break;
            } else {
                $pschool = array ();
                $pschool = $parent;
            }
            $ii += 1;
        }
        return $pschool ['pschool_id'];
    }
    
    //
    private function gethierarchyPschool($pschool_id = null, $parent_id = null) {
        $bind = array ();
        $sql = ' SELECT A.id, A.group_id, A.parent_id, A.pschool_id, A.manage_flag ';
        $sql .= ' FROM hierarchy as A ';
        $sql .= ' WHERE A.delete_date IS NULL ';
        if (! empty ( $pschool_id )) {
            $sql .= ' and A.pschool_id = ? ';
            $bind [] = $pschool_id;
        } elseif (! empty ( $parent_id )) {
            $sql .= ' and A.id = ? ';
            $bind [] = $parent_id;
        }
        $res = $this->fetch ( $sql, $bind );
        return $res;
    }
    
    // 帯色の選択肢
    public function getGradeSelect($pschool_id) {
        $bind = array ();
        $bind [] = $this->getParentID ( $pschool_id );
        
        $sql = " SELECT A.id, A.grade_color ";
        $sql .= " FROM student_grade as A";
        $sql .= " WHERE A.pschool_id = ? ";
        $sql .= " AND A.active_flag = 1 ";
        $sql .= " AND A.delete_date IS NULL ";
        $sql .= " ORDER BY A.sort_no ";
        
        $ret = $this->fetchAll ( $sql, $bind );
        $list = array ();
        foreach ( $ret as $item ) {
            if (! empty ( $item ['id'] ) && ! empty ( $item ['grade_color'] )) {
                $list [$item ['id']] = $item ['grade_color'];
            }
        }
        
        return $list;
    }
    
    /**
     *
     * @param unknown $pschool_id            
     * @return multitype:unknown
     */
    public function getGradeSelect2($pschool_id) {
        $bind = array ();
        
        $parents = HierarchyTable::getInstance ()->getParentPschoolIds ( $pschool_id );
        if (empty ( $parents ) || count ( $parents ) < 1) {
            // 同じ国コードがない
            $bind [] = $pschool_id;
        } else {
            $layer = 99;
            $base_parent = $pschool_id;
            foreach ( $parents as $parent_item ) {
                $parent_dat = HierarchyTable::getInstance ()->getRow ( array (
                        'pschool_id' => $parent_item 
                ) );
                if ($parent_dat ['layer'] < $layer) {
                    $layer = $parent_dat ['layer'];
                    $base_parent = $parent_item;
                }
            }
            $bind [] = $base_parent;
        }
        
        $sql = " SELECT A.id, A.grade_color ";
        $sql .= " FROM student_grade as A";
        $sql .= " WHERE A.pschool_id = ? ";
        $sql .= " AND A.active_flag = 1 ";
        $sql .= " AND A.delete_date IS NULL ";
        $sql .= " ORDER BY A.sort_no ";
        
        $ret = $this->fetchAll ( $sql, $bind );
        $arr = json_decode ( json_encode ( $ret ), true );
        $list = array ();
        foreach ( $arr as $item ) {
            if (! empty ( $item ['id'] ) && ! empty ( $item ['grade_color'] )) {
                $list [$item ['id']] = $item ['grade_color'];
            }
        }
        
        return $list;
    }
    
    // 帯色の選択肢
    public function getGradeHonbu($pschool_id) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT A.* ";
        $sql .= " FROM student_grade as A";
        $sql .= " WHERE A.pschool_id = ? ";
        $sql .= " AND A.active_flag = 1 ";
        $sql .= " AND A.delete_date IS NULL ";
        $sql .= " ORDER BY A.sort_no ";
        
        $ret = $this->fetchAll ( $sql, $bind );
        return $ret;
    }
    public function getGradeListWithCountryCode($group_id, $country_code) {
        $bind = array ();
        $bind [] = $group_id;
        $bind [] = $country_code;
        
        $sql = " SELECT A.* ";
        $sql .= " FROM student_grade as A";
        $sql .= " WHERE A.group_id = ? ";
        $sql .= " AND A.country_code = ? ";
        $sql .= " AND A.active_flag = 1 ";
        $sql .= " AND A.delete_date IS NULL ";
        $sql .= " ORDER BY A.sort_no ";
        
        $ret = $this->fetchAll ( $sql, $bind );
        return $ret;
    }
}