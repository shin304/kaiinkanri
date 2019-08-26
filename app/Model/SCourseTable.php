<?PHP

namespace App\Model;

class SCourseTable extends DbModel {
    
    /**
     *
     * @var SCourseTable
     */
    private static $_instance = null;
    protected $table = 's_course';
    public $timestamps = false;
    
    /**
     *
     * @return SCourseTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new SCourseTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getCourseList($pschool_id, $old_tag_id = null) {
        
        // 対象の項目取得
        $bind = array ();
        $sql = " SELECT * ";
        $sql .= " FROM s_course ";
        $sql .= " WHERE (pschool_id IS NULL OR pschool_id = ? )";
        $bind [] = $pschool_id;
        if ($old_tag_id != null) {
            $sql .= " AND old_tag_id = ? ";
            $bind [] = $old_tag_id;
        } else {
            $sql .= " AND old_tag_id IS NULL ";
        }
        $sql .= " OREDER BY sort_no ASC ";
        $list1 = $this->fetchAll ( $sql, $bind );
        
        // 対象以外の項目取得 不要な場合コメントにする
        $bind = array ();
        $sql = " SELECT * ";
        $sql .= " FROM s_course ";
        $sql .= " WHERE (pschool_id IS NULL OR pschool_id = ? )";
        $bind [] = $pschool_id;
        if ($old_tag_id != null) {
            $sql .= " AND old_tag_id <> ? ";
            $bind [] = $old_tag_id;
        } else {
            $sql .= " AND old_tag_id IS NOT NULL ";
        }
        $sql .= " OREDER BY sort_no ASC ";
        $list2 = $this->fetchAll ( $sql, $bind );
        
        // マージ
        foreach ( $list2 as $list2_item ) {
            $list1 [] = $list2_item;
        }
        
        return $list1;
    }
    
    /**
     * 選択リスト用
     */
    public function getCourseSelectList($cond = null) {
        $bind = array ();
        
        $sql = " SELECT id, old_tag_id, name";
        $sql .= " FROM s_course ";
        $sql .= " WHERE active_flag = 1 ";
        
        $sql .= " ORDER BY sort_no ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * System course管理リスト　用
     */
    public function getCourseList2($cond = null) {
        $bind = array ();
        
        $sql = " SELECT a.id as course_id, b.name as pschool_name, a.old_tag_id, a.name as course_name,a.active_flag, a.sort_no";
        $sql .= " FROM s_course a";
        $sql .= " LEFT JOIN pschool b ON a.pschool_id=b.id ";
        $sql .= " WHERE a.delete_date is NULL ";
        if (isset ( $cond ['course_name'] )) {
            $sql .= " and a.name like ? ";
            $bind [] = "%" . $cond ['course_name'] . "%";
        }
        $sql .= " ORDER BY sort_no ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    public function getLastSortNo() {
        $sql = " SELECT sort_no FROM s_course WHERE active_flag=1 AND delete_date is NULL ORDER BY sort_no DESC ";
        $res = $this->fetchAll ( $sql );
        return $res [0] ['sort_no'];
    }
}