<?PHP

namespace App\Model;

class SSubjectTable extends DbModel {
    
    /**
     *
     * @var SSubjectTable
     */
    private static $_instance = null;
    protected $table = 's_subject';
    public $timestamps = false;
    
    /**
     *
     * @return SSubjectTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new SSubjectTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    
    /**
     * 選択リスト用
     */
    public function getSubjectSelectList($cond = null) {
        $bind = array ();
        
        $sql = " SELECT id, old_tag_id, name";
        $sql .= " FROM s_subject ";
        $sql .= " WHERE active_flag = 1 ";
        
        $sql .= " ORDER BY sort_no ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * System subject管理リスト　用
     */
    public function getSubjectList($cond = null) {
        $bind = array ();
        
        $sql = " SELECT id as subject_id, old_tag_id, name as subject_name, sort_no, active_flag";
        $sql .= " FROM s_subject ";
        $sql .= " WHERE delete_date is NULL ";
        if (isset ( $cond ['subject_name'] )) {
            $sql .= " and name like ? ";
            $bind [] = "%" . $cond ['subject_name'] . "%";
        }
        $sql .= " ORDER BY sort_no ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    public function getLastSortNo() {
        $sql = " SELECT sort_no FROM s_subject WHERE active_flag=1 AND delete_date is NULL ORDER BY sort_no DESC ";
        $res = $this->fetchAll ( $sql );
        return $res [0] ['sort_no'];
    }
}