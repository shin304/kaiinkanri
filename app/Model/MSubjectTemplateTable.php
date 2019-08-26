<?PHP

namespace App\Model;

class MSubjectTemplateTable extends DbModel {
    const SUBJECT_SAVE_FAILURE = 11;
    const SUBJECT_SAVE_FAILURE_ALREADY_EXIST = 12;
    const SUBJECT_SAVE_UNNEEDED = 13;
    const TYPE_SUBJECT = 1;
    const TYPE_LARGE_CLASS = 2;
    const TYPE_MIDDLE_CLASS = 3;
    const TYPE_SMALL_CLASS = 4;
    const FUNC_REGISTER = 1;
    const FUNC_UPDATE = 2;
    const FUNC_DELETE = 3;
    
    /**
     *
     * @var MSubjectTemplateTable
     */
    private static $_instance = null;
    protected $table = 'm_subject_template';
    public $timestamps = false;
    
    /**
     *
     * @return MSubjectTemplateTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new MSubjectTemplateTable ();
        }
        return self::$_instance;
    }
    public function getAllCourseList() {
        $sql = "SELECT a.id, a.s_subject_id as subject_id, b.name as subject_name, ";
        $sql .= " a.s_course_id as course_id, c.name as course_name";
        $sql .= " FROM {$this->getTableName(true)} a";
        $sql .= " INNER JOIN s_subject b ON b.id=a.s_subject_id ";
        $sql .= " INNER JOIN s_course c ON c.id=a.s_course_id ";
        $sql .= " WHERE a.pschool_id=? and a.delete_date is NULL";
        $bind = array ();
        $bind [] = $_SESSION ['school.login'] ['id'];
        $list = $this->fetchAll ( $sql, $bind );
        return $list;
    }
    
    // 学種、学年、により教科リストの取得
    public function getListBySchool($condition) {
        $sql = "SELECT a.s_subject_id as subject_id, b.name as subject_name FROM {$this->getTableName(true)} a";
        $sql .= " INNER JOIN s_subject b ON b.id=a.s_subject_id ";
        $sql .= " WHERE a.pschool_id=? and a.delete_date is NULL";
        $sql .= " GROUP BY a.s_subject_id ";
        $bind = array ();
        $bind [] = $_SESSION ['school.login'] ['id'];
        if (isset ( $condition ['school_type'] )) {
            $sql .= " AND a.school_type =? ";
            $bind [] = $condition ['school_type'];
        }
        if (isset ( $condition ['school_year'] )) {
            $sql .= " AND a.school_year=? ";
            $bind [] = $condition ['school_year'];
        }
        $list = $this->fetchAll ( $sql, $bind );
        return $list;
    }
    
    // 教科による科目リストの取得
    public function getListBySubject($condition) {
        $sql = "SELECT a.s_course_id as course_id, b.name as course_name FROM {$this->getTableName(true)} a";
        $sql .= " INNER JOIN s_course b ON b.id=a.s_course_id ";
        $sql .= " WHERE a.pschool_id=? and a.delete_date is NULL ";
        $sql .= " GROUP BY a.s_course_id";
        $bind = array ();
        $bind [] = $_SESSION ['school.login'] ['id'];
        if (isset ( $condition ['s_subject_id'] )) {
            $sql .= " AND a.s_subject_id=? ";
            $bind [] = $condition ['s_subject_id'];
        }
        $list = $this->fetchAll ( $sql, $bind );
        return $list;
    }
    
    // 教科＋科目リスト
    public function getSubjectCourseList() {
        $sql = "SELECT a.id,a.s_subject_id as subject_id,a.s_course_id as course_id, b.name as subject_name,c.name as course_name FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN s_subject b ON b.id=a.s_subject_id ";
        $sql .= " LEFT JOIN s_course  c ON c.id=a.s_course_id ";
        $sql .= " WHERE a.pschool_id=? and a.delete_date is NULL";
        $bind = array ();
        $bind [] = session ( 'school.login' ) ['id'];
        $list = $this->fetchAll ( $sql, $bind );
        return $list;
    }
    
    /**
     * 講師管理で使用
     *
     * @param unknown $pschool_id            
     */
    public function getSubjectCourseList2($pschool_id) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT MST.id, MST.s_subject_id, MST.s_course_id, SS.name AS subject_name, SC.name AS course_name ";
        $sql .= " FROM m_subject_template AS MST";
        $sql .= " LEFT OUTER JOIN s_subject AS SS ";
        $sql .= " ON MST.s_subject_id = SS.id ";
        $sql .= " LEFT OUTER JOIN s_course AS SC ";
        $sql .= " ON MST.s_course_id = SC.id ";
        $sql .= " WHERE MST.delete_date IS NULL ";
        $sql .= " AND MST.pschool_id = ? ";
        $sql .= " GROUP BY MST.s_subject_id, MST.s_course_id ";
        $sql .= " ORDER BY MST.old_tag_id IS NULL ASC, MST.s_subject_id, MST.s_course_id ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    
    // 科目リスト
    public function getSubjectList() {
        $sql = "SELECT a.s_subject_id as subject_id, b.name as subject_name FROM {$this->getTableName(true)} a";
        $sql .= " INNER JOIN s_subject b ON b.id=a.s_subject_id ";
        $sql .= " WHERE a.pschool_id=? and a.delete_date is NULL";
        $sql .= " GROUP BY a.s_subject_id ";
        $bind = array ();
        $bind [] = session ( 'school.login' ) ['id'];
        $list = $this->fetchAll ( $sql, $bind );
        return $list;
    }
    
    /**
     * 塾の教科ー科目一覧取得
     *
     * @param unknown $pschool_id            
     * @param string $cond            
     */
    public function getSubjectTemplateList($pschool_id, $cond = null) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT MS.*, SS.name AS subject_name, SC.name AS cource_name ";
        $sql .= " FROM m_subject_template AS MS";
        $sql .= " INNER JOIN s_subject AS SS ";
        $sql .= " ON MS.s_subject_id = SS.id ";
        $sql .= " LEFT OUTER JOIN s_course AS SC ";
        $sql .= " ON MS.s_course_id = SC.id ";
        $sql .= " WHERE MS.active_flag = 1 ";
        $sql .= " AND MS.pschool_id = ? ";
        if (isset ( $cond ['school_category'] ) && is_numeric ( $cond ['school_category'] )) {
            $sql .= " AND MS.school_category = ? ";
            $bind [] = $cond ['school_category'];
        }
        if (isset ( $cond ['school_year'] ) && is_numeric ( $cond ['school_year'] )) {
            $sql .= " AND MS.school_year = ? ";
            $bind [] = $cond ['school_year'];
        }
        if (isset ( $cond ['old_tag_id'] ) && empty ( $cond ['old_tag_id'] )) {
            $sql .= " AND MS.old_tag_id = ? ";
            $bind [] = $cond ['old_tag_id'];
        }
        if (isset ( $cond ['s_subject_id'] ) && empty ( $cond ['s_subject_id'] )) {
            $sql .= " AND MS.s_subject_id = ? ";
            $bind [] = $cond ['s_subject_id'];
        }
        if (isset ( $cond ['s_course_id'] ) && empty ( $cond ['s_course_id'] )) {
            $sql .= " AND MS.s_course_id = ? ";
            $bind [] = $cond ['s_course_id'];
        }
        if (isset ( $cond ['student_id'] ) && empty ( $cond ['student_id'] )) {
            $sql .= " AND MS.student_id = ? ";
            $bind [] = $cond ['student_id'];
        }
        if (isset ( $cond ['template_type'] ) && $cond ['template_type'] != "") {
            $sql .= " AND MS.template_type = ? ";
            $bind [] = $cond ['template_type'];
        }
        
        // $sql .= " ORDER BY MS.template_type ASC, MS.school_category ASC, MS.school_year ASC, MS.old_tag_id ASC, MS.s_subject_id ASC, MS.s_course_id ASC, MS.sort_no ASC ";
        $sql .= " ORDER BY MS.template_type ASC, MS.sort_no ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    public function getSubjectTemplateList2($head_id) {
        $bind = array ();
        $bind [] = $head_id;
        
        $sql = " SELECT MST.*, SS.name AS subject_name, SC.name AS course_name ";
        $sql .= " FROM m_subject_template AS MST";
        $sql .= " INNER JOIN s_subject AS SS ";
        $sql .= " ON MST.s_subject_id = SS.id ";
        $sql .= " LEFT OUTER JOIN s_course AS SC ";
        $sql .= " ON MST.s_course_id = SC.id ";
        $sql .= " WHERE MST.delete_date IS NULL ";
        $sql .= " AND MST.parent_id = ? ";
        
        $sql .= " ORDER BY sort_no ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * 対象レコード取得
     *
     * @param unknown $pschool_id            
     * @param unknown $subject_template_id            
     */
    public function getSubjectTemplateData($subject_template_id) {
        $bind = array ();
        $bind [] = $subject_template_id;
        
        $sql .= " SELECT * ";
        $sql .= " FROM m_subject_template ";
        $sql .= " WHERE active_flag = 1 ";
        $sql .= " AND id = ? ";
        $bind [] = $subject_template_id;
        
        return $this->fetch ( $sql, $bind );
    }
    
    /**
     * sort_noの最大値取得
     *
     * @param unknown $parent_id            
     */
    public function getMaxSortno($parent_id) {
        $bind = array ();
        $bind [] = $parent_id;
        
        $sql = " SELECT max(sort_no) AS sort_no ";
        $sql .= " FROM m_subject_template ";
        $sql .= " WHERE parent_id = ? ";
        
        $ret = $this->fetch ( $sql, $bind );
        
        return intval ( $ret ['sort_no'] );
    }
    
    /**
     * 塾で使用している教科一覧取得
     *
     * @param unknown $pschool_id            
     */
    public function getUseSubject($pschool_id) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT SS.id AS id, SS.name ";
        $sql .= " FROM m_subject_template AS MST ";
        $sql .= " LEFT OUTER JOIN s_subject AS SS ";
        $sql .= " ON MST.s_subject_id = SS.id ";
        $sql .= " WHERE MST.pschool_id = ? ";
        $sql .= " AND MST.old_tag_id IS NOT NULL ";
        $sql .= " GROUP BY MST.s_subject_id ";
        $sql .= " ORDER BY MST.s_subject_id ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * 塾で使用している対象教科の科目一覧取得
     *
     * @param unknown $pschool_id            
     * @param unknown $s_subject_id            
     */
    public function getDefineCourse($pschool_id, $s_subject_id) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $s_subject_id;
        
        $sql = " SELECT SC.id AS id, SC.name ";
        $sql .= " FROM m_subject_template AS MST ";
        $sql .= " INNER JOIN s_course AS SC ";
        $sql .= " ON MST.s_course_id = SC.id ";
        $sql .= " WHERE MST.pschool_id = ? ";
        $sql .= " AND MST.s_subject_id = ? ";
        $sql .= " AND MST.old_tag_id IS NOT NULL ";
        $sql .= " GROUP BY MST.s_course_id ";
        $sql .= " ORDER BY MST.s_course_id ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
}