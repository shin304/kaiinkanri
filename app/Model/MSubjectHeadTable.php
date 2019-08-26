<?PHP

namespace App\Model;

class MSubjectHeadTable extends DbModel {
    
    /**
     *
     * @var MSubjectHeadTable
     */
    private static $_instance = null;
    protected $table = 'm_subject_head';
    public $timestamps = false;
    
    /**
     *
     * @return MSubjectHeadTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new MSubjectHeadTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    
    /**
     * 一覧取得
     *
     * @param unknown $pschool_id            
     * @param string $ccnd            
     * @param string $order            
     */
    public function getSubjectHeadList($pschool_id, $cond = null, $order = null) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT * ";
        $sql .= " FROM m_subject_head ";
        $sql .= " WHERE pschool_id = ? ";
        // $sql .= " AND active_flag = 1 ";
        
        // 学校種別
        if (isset ( $cond ['school_category'] ) && is_numeric ( $cond ['school_category'] )) {
            $sql .= " AND school_category = ? ";
            $bind [] = $cond ['school_category'];
        }
        // 学年
        if (isset ( $cond ['school_year'] ) && is_numeric ( $cond ['school_year'] )) {
            $sql .= " AND school_year = ? ";
            $bind [] = $cond ['school_year'];
        }
        // 区分
        if (isset ( $cond ['template_type'] ) && $cond ['template_type'] != "") {
            $sql .= " AND template_type = ? ";
            $bind [] = $cond ['template_type'];
        }
        // 定義名称
        if (isset ( $cond ['template_name'] ) && $cond ['template_name'] != "") {
            $sql .= " AND name LIKE '%" . $cond ['template_name'] . "%'";
        }
        
        if (empty ( $order )) {
            $sql .= " ORDER BY sort_no ";
        } else {
            $orderby = "";
            foreach ( $order as $key => $val ) {
                if ($orderby != "")
                    $orderby .= ", ";
                $orderby .= $key . " " . $val;
            }
            if ($orderby != "") {
                $sql .= " ORDER BY " . $orderby;
            }
        }
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * デフォルト設定の科目・教科取得
     *
     * @param unknown $pschool_id            
     * @param string $template_type
     *            1:教科・科目 2:通知表
     * @param unknown $school_category            
     * @param unknown $school_year            
     */
    public function getDefaultSubjectCourseList($pschool_id, $template_type, $school_category, $school_year) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $template_type;
        
        $sql = " SELECT MST.*, SS.name AS subject_name, SC.name AS course_name";
        $sql .= " FROM m_subject_head AS MSH ";
        $sql .= " INNER JOIN m_subject_template AS MST ";
        $sql .= " ON MSH.id = MST.parent_id ";
        $sql .= " INNER JOIN s_subject AS SS ";
        $sql .= " ON MST.s_subject_id = SS.id ";
        $sql .= " LEFT OUTER JOIN s_course AS SC ";
        $sql .= " ON MST.s_course_id = SC.id ";
        $sql .= " WHERE MSH.pschool_id = ? ";
        $sql .= " AND MSH.template_type = ? ";
        if (isset ( $school_category ) && is_numeric ( $school_category )) {
            $sql .= " AND MSH.school_category = ? ";
            $bind [] = $school_category;
        }
        if (isset ( $school_year ) && is_numeric ( $school_year )) {
            $sql .= " AND MSH.school_year = ? ";
            $bind [] = $school_year;
        }
        $sql .= " AND MSH.default_flag = 1 ";
        $sql .= " AND MSH.delete_date IS NULL ";
        $sql .= " AND MSH.active_flag = 1 ";
        $sql .= " AND MST.active_flag = 1 ";
        
        $sql .= " ORDER BY MST.sort_no ASC ";
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * 特定のidを指定して科目・教科取得
     *
     * @param unknown $subject_course_id            
     */
    public function getSpecificSubjectCourseList($subject_course_id) {
        $bind = array ();
        $bind [] = $subject_course_id;
        
        $sql = " SELECT MST.*, SS.name AS subject_name, SC.name AS course_name";
        $sql .= " FROM m_subject_head AS MSH ";
        $sql .= " INNER JOIN m_subject_template AS MST ";
        $sql .= " ON MSH.id = MST.parent_id ";
        $sql .= " INNER JOIN s_subject AS SS ";
        $sql .= " ON MST.s_subject_id = SS.id ";
        $sql .= " LEFT OUTER JOIN s_course AS SC ";
        $sql .= " ON MST.s_course_id = SC.id ";
        $sql .= " WHERE MSH.id = ? ";
        $sql .= " AND MSH.delete_date IS NULL ";
        $sql .= " AND MSH.active_flag = 1 ";
        $sql .= " AND MST.active_flag = 1 ";
        
        $sql .= " ORDER BY MST.sort_no ASC ";
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
}