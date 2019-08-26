<?PHP

namespace App\Model;

use App\Common\Constants;

require_once 'ClassTable.php';
require_once 'CourseTable.php';
require_once 'ParentTable.php';
require_once 'ProgramTable.php';


class InvoiceItemTable extends DbModel {
    
    /**
     *
     * @var InvoiceItemTable
     */
    private static $_instance = null;
    protected $table = 'invoice_item';
    public $timestamps = false;
    
    /**
     *
     * @return InvoiceItemTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new InvoiceItemTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getListOfStudent($school_id, $parent_name = null, $invoice_id = null) {
        $sql = " SELECT a.*,b.parent_name,b.student_name FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN student b ON a.student_id = b.id";
        $sql .= ' WHERE a.pschool_id = ? AND a.active_flag = 1 AND a.delete_date is null';
        $bind = array ();
        $bind [] = $school_id;
        if (isset ( $parent_name )) {
            if (strlen ( $parent_name )) {
                $sql .= " AND b.parent_name like ?";
                $bind [] = "%" . $parent_name . "%";
            }
        }
        if (empty ( $invoice_id )) {
            $sql .= ' AND a.invoice_id is null';
        } else {
            $sql .= ' AND a.invoice_id = ?';
            $bind [] = $invoice_id;
        }
        
        $res = array ();
        $sql .= ' ORDER BY b.parent_name ASC, a.id ASC';
        
        $res = $this->fetchAll ( $sql, $bind );
        
        return json_decode ( json_encode ( $res ), true );
        ;
    }
    public function getRowWithStudent($id) {
        $sql = " SELECT a.*,b.student_name FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN student b ON a.student_id = b.id";
        $sql .= ' WHERE a.id = ? AND a.invoice_id is null AND a.active_flag = 1 AND a.delete_date is null';
        $bind = array ();
        $bind [] = $id;
        $res = array ();
        
        $res = $this->fetch ( $sql, $bind );
        
        return $res;
    }
    
    /**
     * 「プラン」の請求項目を取得する。
     *
     * @param unknown $school_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function getClassItem($school_id, $parent_id, $year_month) {
        $sql = "SELECT " . "student.id" . ", student.student_type" . ", class.id AS class_id" . ", class.class_name" . ", CASE " . "WHEN student.student_type = 1 THEN " . "class.internal_fee " . "WHEN student.student_type = 2 THEN " . "class.external_fee " . "WHEN student.student_type = 3 THEN " . "class.prospect_fee " . "WHEN student.student_type = 4 THEN " . "class.former_fee " . "END AS class_fee " . "FROM " . "student " . "INNER JOIN student_class AS s_class " . "ON (" . "s_class.student_id = student.id " . "AND s_class.active_flag = 1 " . "AND s_class.delete_date IS NULL " . ") " . "INNER JOIN class " . "ON (" . "class.pschool_id = student.pschool_id " . "AND class.id = s_class.class_id " . "AND class.active_flag = 1 " . "AND class.delete_date IS NULL " . ") " . "WHERE " . "student.parent_id = ? " . "AND student.pschool_id = ? " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "ORDER BY " . "student.id ASC" . ", class.id ASC";
        
        $bind = array (
                $parent_id,
                $school_id 
        );
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * 「プラン」の請求項目を取得する。 会員版
     *
     * @param unknown $school_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function getClassItem_Axis($school_id, $parent_id, $year_month) {
        $sql = "SELECT " . " sc.student_id AS id, sp.student_type, c.id AS class_id, c.class_name, cfp.fee AS class_fee " . " ,sp.student_name " . "FROM " . "student_class AS sc " . "INNER JOIN class AS c " . "ON (" . "sc.class_id = c.id " . "AND sc.active_flag = 1 " . "AND sc.delete_date IS NULL " . "AND sc.pschool_id = ? " . "AND c.active_flag = 1 " . "AND (c.start_date IS NOT NULL OR SUBSTRING(c.start_date, 1, 7) <= ?) " . "AND (c.close_date IS NULL OR SUBSTRING(c.close_date, 1, 7) >= ?) " . "AND sc.plan_id IS NOT NULL " . ") " . "INNER JOIN class_fee_plan AS cfp " . "ON (" . "sc.plan_id = cfp.id " . ") " . "INNER JOIN " . " (SELECT s.id AS student_id, s.parent_id, s.student_type, s.student_name " . "  FROM student AS s " . "  INNER JOIN parent AS p " . "  ON (" . "      s.parent_id = p.id " . "	   AND s.pschool_id = ? " . "  ) " . "  WHERE s.delete_date IS NULL " . "  AND p.id = ? " . "  AND s.pschool_id = ? " . "  AND s.active_flag = 1 " . "  AND p.delete_date IS NULL ) AS sp " . "ON  (" . "sc.student_id = sp.student_id " . ") " . "ORDER BY sc.student_id ASC, sc.id ASC";
        
        $bind = array (
                $school_id,
                $year_month,
                $year_month,
                $school_id,
                $parent_id,
                $school_id 
        );
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * 「プラン」の請求項目を作成する。
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateClassItem($school_id, $login_account_id, $header_id, $parent_id, $year_month) {
        $item_insert_sql = "INSERT INTO invoice_item(" . "pschool_id" . ", invoice_id" . ", parent_id" . ", student_id" . ", class_id" . ", course_id" . ", item_name" . ", unit_price" . ", active_flag" . ", register_date" . ", register_admin" . ") " . "SELECT " . "student.pschool_id" . ", ?" . ", student.parent_id" . ", student.id" . ", class.id AS class_id" . ", NULL" . ", class.class_name" . ", class_fee.fee" . 
        // ", CASE " .
        // "WHEN student.student_type = 1 THEN " .
        // "class.internal_fee " .
        // "WHEN student.student_type = 2 THEN " .
        // "class.external_fee " .
        // "WHEN student.student_type = 3 THEN " .
        // "class.prospect_fee " .
        // "WHEN student.student_type = 4 THEN " .
        // "class.former_fee " .
        // "END AS class_fee " .
        ", 1" . ", ?" . ", ? " . "FROM " . "student " . "INNER JOIN student_class AS s_class " . "ON (" . "s_class.student_id = student.id " . "AND s_class.active_flag = 1 " . "AND s_class.delete_date IS NULL " . ") " . "INNER JOIN class " . "ON (" . "class.pschool_id = student.pschool_id " . "AND class.id = s_class.class_id " . "AND class.active_flag = 1 " . 
        // // "AND (class.start_date IS NULL OR SUBSTRING(class.start_date, 1, 7) <= ?) " .
        "AND (class.start_date IS NOT NULL OR SUBSTRING(class.start_date, 1, 7) <= ?) " . "AND (class.close_date IS NULL OR SUBSTRING(class.close_date, 1, 7) >= ?) " . "AND class.delete_date IS NULL " . ") " . "INNER JOIN class_fee " . "ON (" . "class_fee.class_id = class.id " . "AND class_fee.student_type = student.student_type " . "AND class_fee.delete_date IS NULL " . ") " . "WHERE " . "student.parent_id = ? " . "AND student.pschool_id = ? " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "ORDER BY " . "student.id ASC" . ", class.id ASC";
        
        $bind = array (
                $header_id,
                $this->getNow (),
                $login_account_id,
                $year_month,
                $year_month,
                $parent_id,
                $school_id 
        );
        InvoiceItemTable::getInstance ()->execute ( $item_insert_sql, $bind );
    }
    
    /**
     * 「プラン」の請求項目を取得する 会員版
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateClassItem_Axis($school_id, $login_account_id, $header_id, $parent_id, $year_month) {
        if ($_SESSION ['school.login'] ['country_code'] == 81) {
            $split = explode ( '-', $year_month );
            $invoice_year_month = $split [0] . "年" . $split [1] . "月分 ";
        } else {
            $invoice_year_month = $year_month . " ";
        }
        
        $item_insert_sql = "INSERT INTO invoice_item(" . "pschool_id" . ", invoice_id" . ", parent_id" . ", student_id" . ", class_id" . ", course_id" . ", item_name" . ", unit_price" . ", active_flag" . ", register_date" . ", register_admin" . ", program_id" . ") " . "SELECT " . "sc.pschool_id" . ", ?" . ", sp.parent_id" . ", sp.student_id" . ", sc.class_id" . ", NULL" . ", concat('" . $invoice_year_month . "', c.class_name, ' ', sp.student_name)" . 
        // ", c.class_name" .
        ", cfp.fee" . ", 1" . ", ?" . ", ? " . ", NULL " . "FROM " . "student_class AS sc " . "INNER JOIN class AS c " . "ON (" . "sc.class_id = c.id " . "AND sc.active_flag = 1 " . "AND sc.delete_date IS NULL " . "AND sc.pschool_id = ? " . "AND c.active_flag = 1 " . "AND (c.start_date IS NOT NULL OR SUBSTRING(c.start_date, 1, 7) <= ?) " . "AND (c.close_date IS NULL OR SUBSTRING(c.close_date, 1, 7) >= ?) " . ") " . "INNER JOIN class_fee_plan AS cfp " . "ON (" . "sc.plan_id = cfp.id " . ") " . "INNER JOIN " . " (SELECT s.id AS student_id, s.parent_id, s.student_name " . "  FROM student AS s " . "  INNER JOIN parent AS p " . "  ON (" . "      s.parent_id = p.id " . "	   AND s.pschool_id = ? " . "  ) " . "  WHERE s.delete_date IS NULL " . "  AND p.id = ? " . "  AND s.pschool_id = ? " . "  AND s.active_flag = 1 " . "  AND p.delete_date IS NULL ) AS sp " . "ON  (" . "sc.student_id = sp.student_id " . ") " . "ORDER BY sc.student_id ASC, sc.id ASC";
        
        $bind = array (
                $header_id,
                $this->getNow (),
                $login_account_id,
                $school_id,
                $year_month,
                $year_month,
                $school_id,
                $parent_id,
                $school_id 
        );
        
        InvoiceItemTable::getInstance ()->execute ( $item_insert_sql, $bind );
    }
    
    /**
     * 「イベント」の請求項目を取得する。
     *
     * @param unknown $school_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function getCourseItem($school_id, $parent_id, $year_month) {
        $sql = "SELECT " . "student.id" . ", student.student_type" . ", course.id AS course_id" . ", course.course_title" . ", CASE " . "WHEN student.student_type = 1 THEN " . "course.internal_fee " . "WHEN student.student_type = 2 THEN " . "course.external_fee " . "WHEN student.student_type = 3 THEN " . "course.prospect_fee " . "WHEN student.student_type = 4 THEN " . "course.former_fee " . "END AS course_fee " . "FROM " . "student " . "INNER JOIN student_course_rel AS s_course " . "ON (" . "s_course.student_id = student.id " . "AND s_course.active_flag = 1 " . "AND s_course.delete_date IS NULL " . "AND s_course.is_received = 0 " . ") " . "INNER JOIN course " . "ON (" . "course.pschool_id = student.pschool_id " . "AND course.id = s_course.course_id " . "AND course.active_flag = 1 " . "AND course.delete_date IS NULL " . ") " . "WHERE " . "student.parent_id = ? " . "AND student.pschool_id = ? " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "ORDER BY " . "student.id ASC" . ", course.id ASC";
        $bind = array (
                $parent_id,
                $school_id 
        );
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * 「イベント」の請求項目を取得する。 会員版
     *
     * @param unknown $school_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function getCourseItem_Axis($school_id, $parent_id, $year_month) {
        $sql = "SELECT " . "scr.student_id AS id" . ", sp.student_type" . ", c.id AS course_id" . ", c.course_title" . ", cfp.fee AS course_fee " . ", sp.student_name " . "FROM " . "student_course_rel AS scr " . "INNER JOIN course AS c " . "ON (" . "scr.course_id = c.id " . "AND scr.active_flag = 1 " . "AND scr.delete_date IS NULL " . "AND scr.is_received = 0 " . "AND c.pschool_id = ? " . "AND c.active_flag = 1 " . 
        // "AND (c.start_date IS NOT NULL OR SUBSTRING(c.start_date, 1, 7) <= ?) " .
        "AND SUBSTRING(c.start_date, 1, 7) = ? " . 
        // "AND (c.close_date IS NULL OR SUBSTRING(c.close_date, 1, 7) >= ?) " .
        "AND scr.plan_id IS NOT NULL " . ") " . "INNER JOIN course_fee_plan AS  cfp " . "ON (" . "scr.plan_id = cfp.id " . "AND scr.course_id = cfp.course_id " . "AND cfp.active_flag = 1 " . "AND cfp.delete_date IS NULL " . ") " . "INNER JOIN " . " (SELECT s.id AS student_id, s.parent_id, s.student_type, s.student_name " . "  FROM student AS s " . "  INNER JOIN parent AS p " . "  ON (" . " s.parent_id = p.id " . " AND s.pschool_id = ? " . "  ) " . "  WHERE s.delete_date IS NULL " . "  AND p.id = ? " . "  AND s.pschool_id = ? " . "  AND s.active_flag = 1 " . "  AND p.delete_date IS NULL ) AS sp " . "ON  (" . "scr.student_id = sp.student_id " . ") " . "ORDER BY scr.student_id ASC, scr.course_id ASC";
        
        $bind = array (
                $school_id,
                // $year_month,
                $year_month,
                $school_id,
                $parent_id,
                $school_id 
        );
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * 「イベント」の請求項目を作成する。
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateCourseItem($school_id, $login_account_id, $header_id, $parent_id, $year_month) {
        $item_insert_sql = "INSERT INTO invoice_item(" . "pschool_id" . ", invoice_id" . ", parent_id" . ", student_id" . ", class_id" . ", course_id" . ", item_name" . ", unit_price" . ", active_flag" . ", register_date" . ", register_admin" . ") " . "SELECT " . "student.pschool_id" . ", ?" . ", student.parent_id" . ", student.id" . ", NULL" . ", course.id AS course_id" . ", course.course_title" . ", course_fee.fee" . 
        // ", CASE " .
        // "WHEN student.student_type = 1 THEN " .
        // "course.internal_fee " .
        // "WHEN student.student_type = 2 THEN " .
        // "course.external_fee " .
        // "WHEN student.student_type = 3 THEN " .
        // "course.prospect_fee " .
        // "WHEN student.student_type = 4 THEN " .
        // "course.former_fee " .
        // "END AS course_fee " .
        ", 1" . ", ?" . ", ? " . "FROM " . "student " . "INNER JOIN student_course_rel AS s_course " . "ON (" . "s_course.student_id = student.id " . "AND s_course.active_flag = 1 " . "AND s_course.delete_date IS NULL " . "AND s_course.is_received = 0 " . ") " . "INNER JOIN course " . "ON (" . "course.pschool_id = student.pschool_id " . "AND course.id = s_course.course_id " . "AND course.active_flag = 1 " . 
        // // "AND (course.start_date IS NULL OR SUBSTRING(course.start_date, 1, 7) <= ?) " .
        "AND (course.start_date IS NOT NULL OR SUBSTRING(course.start_date, 1, 7) <= ?) " . "AND (course.close_date IS NULL OR SUBSTRING(course.close_date, 1, 7) >= ?) " . "AND course.delete_date IS NULL " . ") " . "INNER JOIN course_fee " . "ON (" . "course_fee.course_id = course.id " . "AND course_fee.student_type = student.student_type " . "AND course_fee.delete_date IS NULL " . ") " . "WHERE " . "student.parent_id = ? " . "AND student.pschool_id = ? " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "ORDER BY " . "student.id ASC" . ", course.id ASC";
        $bind = array (
                $header_id,
                $this->getNow (),
                $login_account_id,
                $year_month,
                $year_month,
                $parent_id,
                $school_id 
        );
        InvoiceItemTable::getInstance ()->execute ( $item_insert_sql, $bind );
    }
    
    /**
     * 「イベント」の請求項目を作成する。 会員版
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateCourseItem_Axis($school_id, $login_account_id, $header_id, $parent_id, $year_month) {
        if ($_SESSION ['school.login'] ['country_code'] == 81) {
            $split = explode ( '-', $year_month );
            $invoice_year_month = $split [0] . "年" . $split [1] . "月分 ";
        } else {
            $invoice_year_month = $year_month . " ";
        }
        
        $item_insert_sql = "INSERT INTO invoice_item(" . "pschool_id" . ", invoice_id" . ", parent_id" . ", student_id" . ", class_id" . ", course_id" . ", item_name" . ", unit_price" . ", active_flag" . ", register_date" . ", register_admin" . ", program_id " . ") " . "SELECT " . "c.pschool_id" . ", ?" . ", sp.parent_id" . ", sp.student_id" . ", NULL" . ", c.id AS course_id" . 
        // ", c.course_title" .
        ", concat('" . $invoice_year_month . "', c.course_title, ' ', sp.student_name) " . ", cfp.fee" . ", 1" . ", ?" . ", ? " . ", NULL " . "FROM " . "student_course_rel AS scr " . "INNER JOIN course AS c " . "ON (" . "scr.course_id = c.id " . "AND scr.active_flag = 1 " . "AND scr.delete_date IS NULL " . "AND scr.is_received = 0 " . "AND c.pschool_id = ? " . "AND c.active_flag = 1 " . 
        // "AND (c.start_date IS NOT NULL OR SUBSTRING(c.start_date, 1, 7) <= ?) " .
        "AND SUBSTRING(c.start_date, 1, 7) = ? " . 
        // "AND (c.close_date IS NULL OR SUBSTRING(c.close_date, 1, 7) >= ?) " .
        ") " . "INNER JOIN course_fee_plan AS  cfp " . "ON (" . "scr.plan_id = cfp.id " . "AND scr.course_id = cfp.course_id " . "AND cfp.active_flag = 1 " . "AND cfp.delete_date IS NULL " . ") " . "INNER JOIN " . " (SELECT s.id AS student_id, s.parent_id, s.student_name " . "  FROM student AS s " . "  INNER JOIN parent AS p " . "  ON (" . " s.parent_id = p.id " . " AND s.pschool_id = ? " . "  ) " . "  WHERE s.delete_date IS NULL " . "  AND p.id = ? " . "  AND s.pschool_id = ? " . "  AND s.active_flag = 1 " . "  AND p.delete_date IS NULL ) AS sp " . "ON  (" . "scr.student_id = sp.student_id " . ") " . "ORDER BY scr.student_id ASC, scr.course_id ASC";
        
        $bind = array (
                $header_id,
                $this->getNow (),
                $login_account_id,
                $school_id,
                // $year_month,
                $year_month,
                $school_id,
                $parent_id,
                $school_id 
        );
        
        InvoiceItemTable::getInstance ()->execute ( $item_insert_sql, $bind );
    }
    
    /**
     * 「プログラム」の請求項目を取得する。 会員版
     *
     * @param unknown $school_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function getProgramItem_Axis($school_id, $parent_id, $year_month) {
        $sql = "SELECT " . "sm.student_id AS id" . ", sp.student_type" . ", p.id AS program_id" . ", p.program_name" . ", pfp.fee AS program_fee " . " ,sp.student_name " . "FROM " . "student_program AS sm " . "INNER JOIN program AS p " . "ON (" . "sm.program_id = p.id " . "AND sm.active_flag = 1 " . "AND sm.delete_date IS NULL " . "AND sm.pschool_id = ? " . "AND p.active_flag = 1 " . 
        // "AND (p.start_date IS NOT NULL OR SUBSTRING(p.start_date, 1, 7) <= ?) " .
        "AND SUBSTRING(p.start_date, 1, 7) = ? " . 
        // "AND (p.close_date IS NULL OR SUBSTRING(p.close_date, 1, 7) >= ?) " .
        "AND sm.plan_id IS NOT NULL " . ") " . "INNER JOIN program_fee_plan AS pfp " . "ON (" . "sm.plan_id = pfp.id " . ") " . "INNER JOIN " . " (SELECT s.id AS student_id, s.parent_id, s.student_type, s.student_name " . "  FROM student AS s " . "  INNER JOIN parent AS p " . "  ON (" . "      s.parent_id = p.id " . "	   AND s.pschool_id = ? " . "  ) " . "  WHERE s.delete_date IS NULL " . "  AND p.id = ? " . "  AND s.pschool_id = ? " . "  AND s.active_flag = 1 " . "  AND p.delete_date IS NULL ) AS sp " . "ON  (" . "sm.student_id = sp.student_id " . ") " . "ORDER BY sm.student_id ASC, sm.program_id ASC";
        
        $bind = array (
                $school_id,
                // $year_month,
                $year_month,
                $school_id,
                $parent_id,
                $school_id 
        );
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * 「プログラム」の請求項目を取得する 会員版
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateProgramItem_Axis($school_id, $login_account_id, $header_id, $parent_id, $year_month) {
        if (session ( 'school.login' ) ['country_code'] == 81) {
            $split = explode ( '-', $year_month );
            $invoice_year_month = $split [0] . "年" . $split [1] . "月分 ";
        } else {
            $invoice_year_month = $year_month . " ";
        }
        
        $item_insert_sql = "INSERT INTO invoice_item(" . "pschool_id" . ", invoice_id" . ", parent_id" . ", student_id" . ", class_id" . ", course_id" . ", item_name" . ", unit_price" . ", active_flag" . ", register_date" . ", register_admin" . ", program_id" . ") " . "SELECT " . "sm.pschool_id" . ", ?" . ", sp.parent_id" . ", sp.student_id" . ", NULL" . ", NULL" . 
        // ", p.program_name" .
        ", concat('" . $invoice_year_month . "', p.program_name, ' ', sp.student_name) " . ", pfp.fee" . ", 1" . ", ?" . ", ? " . ", sm.program_id " . "FROM " . "student_program AS sm " . "INNER JOIN program AS p " . "ON (" . "sm.program_id = p.id " . "AND sm.active_flag = 1 " . "AND sm.delete_date IS NULL " . "AND sm.pschool_id = ? " . "AND p.active_flag = 1 " . 
        // "AND (p.start_date IS NOT NULL OR SUBSTRING(p.start_date, 1, 7) <= ?) " .
        "AND SUBSTRING(p.start_date, 1, 7) = ? " . 
        // "AND (p.close_date IS NULL OR SUBSTRING(p.close_date, 1, 7) >= ?) " .
        ") " . "INNER JOIN program_fee_plan AS pfp " . "ON (" . "sm.plan_id = pfp.id " . ") " . "INNER JOIN " . " (SELECT s.id AS student_id, s.parent_id, s.student_name " . "  FROM student AS s " . "  INNER JOIN parent AS p " . "  ON (" . "      s.parent_id = p.id " . "	   AND s.pschool_id = ? " . "  ) " . "  WHERE s.delete_date IS NULL " . "  AND p.id = ? " . "  AND s.pschool_id = ? " . "  AND s.active_flag = 1 " . "  AND p.delete_date IS NULL ) AS sp " . "ON  (" . "sm.student_id = sp.student_id " . ") " . "ORDER BY sm.student_id ASC, sm.program_id ASC";
        
        $bind = array (
                $header_id,
                $this->getNow (),
                $login_account_id,
                $school_id,
                // $year_month,
                $year_month,
                $school_id,
                $parent_id,
                $school_id 
        );
        
        InvoiceItemTable::getInstance ()->execute ( $item_insert_sql, $bind );
    }
    
    /**
     * invouce_itemテーブルにstudentテーブルのschool_year,school_category設定
     *
     * @param unknown $header_id            
     */
    public function setCategoryYear($header_id) {
        $bind = array (
                $header_id 
        );
        
        $sql = " UPDATE ";
        $sql .= "     invoice_item ii, ";
        $sql .= "     student ss ";
        $sql .= " SET ";
        $sql .= "     ii.school_category = ss.school_category, ";
        $sql .= "     ii.school_year = ss.school_year ";
        $sql .= " WHERE ";
        $sql .= "     ii.student_id = ss.id AND ii.invoice_id = ? ";
        
        $this->execute ( $sql, $bind );
    }
    
    /**
     * 保護者に対応するプランの割引・割増を追加
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateClassAdjustItem($pschool_id, $login_account_id, $header_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
        
        if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
            // -------------------------------------------------------------
            // 運用区分が塾の場合
            // -------------------------------------------------------------
            $adjust = ClassTable::getInstance ()->getClassAdjustList ( $pschool_id, $parent_id, $year_month );
        } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
            // -------------------------------------------------------------
            // 運用区分が会員クラブの場合
            // -------------------------------------------------------------
            $adjust = ClassTable::getInstance ()->getClassAdjustList_Axis ( $pschool_id, $parent_id, $year_month );
            
            if (session ( 'school.login' ) ['country_code'] == 81) {
                $split = explode ( '-', $year_month );
                $invoice_year_month = $split [0] . "年" . $split [1] . "月分 ";
            } else {
                $invoice_year_month = $year_month . " ";
            }
        }
        
        if (count ( $adjust ) > 0) {
            foreach ( $adjust as $item ) {
                $row = array ();
                $row ['pschool_id'] = $pschool_id;
                $row ['invoice_id'] = $header_id;
                $row ['parent_id'] = $parent_id;
                $row ['student_id'] = $item ['student_id'];
                $row ['class_id'] = $item ['class_id'];
                $row ['course_id'] = null;
                if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                    $row ['item_name'] = $invoice_year_month . $item ['class_name'] . " " . $item ['student_name'] . " " . "(" . $item ['name'] . ")";
                } else {
                    $row ['item_name'] = $item ['name'];
                }
                $row ['unit_price'] = $item ['adjust_fee'];
                $row ['active_flag'] = 1;
                $row ['register_date'] = date ( 'Y-m-d H:i:s' );
                $row ['register_admin'] = $login_account_id;
                $row ['invoice_adjust_name_id'] = $item ['invoice_adjust_name_id'];
                $row ['program_id'] = null;
                
                InvoiceItemTable::getInstance ()->insertRow ( $row );
            }
        }
    }
    
    /**
     * 保護者に対応するイベントの割引・割増を追加
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateCourseAdjustItem($pschool_id, $login_account_id, $header_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
        
        if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
            // -------------------------------------------------------------
            // 運用区分が塾の場合
            // -------------------------------------------------------------
            $adjust = CourseTable::getInstance ()->getCourseAdjustList ( $pschool_id, $parent_id, $year_month );
        } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
            // -------------------------------------------------------------
            // 運用区分が会員クラブの場合
            // -------------------------------------------------------------
            $adjust = CourseTable::getInstance ()->getCourseAdjustList_Axis ( $pschool_id, $parent_id, $year_month );
            
            if (session ( 'school.login' ) ['country_code'] == 81) {
                $split = explode ( '-', $year_month );
                $invoice_year_month = $split [0] . "年" . $split [1] . "月分 ";
            } else {
                $invoice_year_month = $year_month . " ";
            }
        }
        
        if (count ( $adjust ) > 0) {
            foreach ( $adjust as $item ) {
                $row = array ();
                $row ['pschool_id'] = $pschool_id;
                $row ['invoice_id'] = $header_id;
                $row ['parent_id'] = $parent_id;
                $row ['student_id'] = $item ['student_id'];
                $row ['class_id'] = null;
                $row ['course_id'] = $item ['course_id'];
                if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                    $row ['item_name'] = $invoice_year_month . $item ['course_title'] . " " . $item ['student_name'] . " " . "(" . $item ['name'] . ")";
                } else {
                    $row ['item_name'] = $item ['name'];
                }
                $row ['unit_price'] = $item ['adjust_fee'];
                $row ['active_flag'] = 1;
                $row ['register_date'] = date ( 'Y-m-d H:i:s' );
                $row ['register_admin'] = $login_account_id;
                $row ['invoice_adjust_name_id'] = $item ['invoice_adjust_name_id'];
                $row ['program_id'] = null;
                
                InvoiceItemTable::getInstance ()->insertRow ( $row );
            }
        }
    }
    
    /**
     * 保護者に対応するプログラムの割引・割増を追加
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateProgramAdjustItem($pschool_id, $login_account_id, $header_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
        
        if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
            // -------------------------------------------------------------
            // 運用区分が塾の場合
            // -------------------------------------------------------------
            $adjust = ProgramTable::getInstance ()->getProgramAdjustList ( $pschool_id, $parent_id, $year_month );
        } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
            // -------------------------------------------------------------
            // 運用区分が会員クラブの場合
            // -------------------------------------------------------------
            $adjust = ProgramTable::getInstance ()->getProgramAdjustList_Axis ( $pschool_id, $parent_id, $year_month );
            
            if (session ( 'school.login' ) ['country_code'] == 81) {
                $split = explode ( '-', $year_month );
                $invoice_year_month = $split [0] . "年" . $split [1] . "月分 ";
            } else {
                $invoice_year_month = $year_month . " ";
            }
        }
        
        if (count ( $adjust ) > 0) {
            foreach ( $adjust as $item ) {
                $row = array ();
                $row ['pschool_id'] = $pschool_id;
                $row ['invoice_id'] = $header_id;
                $row ['parent_id'] = $parent_id;
                $row ['student_id'] = $item ['student_id'];
                $row ['class_id'] = null;
                $row ['course_id'] = null;
                if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                    $row ['item_name'] = $invoice_year_month . $item ['program_name'] . " " . $item ['student_name'] . " " . "(" . $item ['name'] . ")";
                } else {
                    $row ['item_name'] = $item ['name'];
                }
                $row ['unit_price'] = $item ['adjust_fee'];
                $row ['active_flag'] = 1;
                $row ['register_date'] = date ( 'Y-m-d H:i:s' );
                $row ['register_admin'] = $login_account_id;
                $row ['invoice_adjust_name_id'] = $item ['invoice_adjust_name_id'];
                $row ['program_id'] = $item ['program_id'];
                
                InvoiceItemTable::getInstance ()->insertRow ( $row );
            }
        }
    }
    /**
     * 保護者への割引・割増を追加
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $header_id            
     * @param unknown $parent_id            
     * @param unknown $year_month            
     */
    public function generateParentAdjustItem($pschool_id, $login_account_id, $header_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
        
        $adjust = ParentTable::getInstance ()->getParentAdjustList ( $pschool_id, $parent_id, $month );
        
        if (count ( $adjust ) > 0) {
            foreach ( $adjust as $item ) {
                $row = array ();
                $row ['pschool_id'] = $pschool_id;
                $row ['invoice_id'] = $header_id;
                $row ['parent_id'] = $parent_id;
                $row ['student_id'] = $item ['student_id'];
                $row ['class_id'] = null;
                $row ['course_id'] = null;
                $row ['item_name'] = $item ['name'];
                $row ['unit_price'] = $item ['adjust_fee'];
                $row ['active_flag'] = 1;
                $row ['register_date'] = date ( 'Y-m-d H:i:s' );
                $row ['register_admin'] = $login_account_id;
                $row ['invoice_adjust_name_id'] = $item ['invoice_adjust_name_id'];
                $row ['program_id'] = null;
                
                InvoiceItemTable::getInstance ()->insertRow ( $row );
            }
        }
    }
    public function getListItemInvoice($data){

        $cond = array(
                'invoice_id' => $data['id'],
                'active_flag' => 1
        );
        $item_list = $this->getList($cond);
        $res = array();
        $sum_discount_price = 0;
        $amount = 0;
        foreach ($item_list as $item) {
            if (!isset($item["student_id"])) {
                $res["discount_id"][] = $item["invoice_adjust_name_id"];
                $res["discount_name"][] = $item["item_name"];
                $item["unit_price"] = floor($item["unit_price"]);
                $res["discount_price"][] = $item["unit_price"];
                $sum_discount_price += intval($item["unit_price"]);
                if(isset($item['payment_method'])){
                    if (session()->get('school.login.lang_code')) { // lang_code based in school
                        $res["class_payment_method"][$item["student_id"]][] = Constants::$invoice_type[session()->get('school.login.lang_code')][$item['payment_method']] ;
                    } else { // lang_code is japanese
                        $res["class_payment_method"][$item["student_id"]][] = Constants::$invoice_type[2][$item['payment_method']] ;
                    }
                }
                if(!empty($item['due_date'])) {
                    $date = strtotime($item['due_date']);
                    $res["class_due_date"][$item["student_id"]][] = date('n', $date) . "月" . date('j', $date) . "日";
                }
            } elseif(isset($item["class_id"])) {
                $res["class_id"][$item["student_id"]][] = $item["class_id"];
                $res["class_name"][$item["student_id"]][] = $item["item_name"];
                $res["class_price"][$item["student_id"]][] = $item["unit_price"];
                if(isset($item['payment_method'])){
                    if (session()->get('school.login.lang_code')) { // lang_code based in school
                        $res["class_payment_method"][$item["student_id"]][] = Constants::$invoice_type[session()->get('school.login.lang_code')][$item['payment_method']] ;
                    } else { // lang_code is japanese
                        $res["class_payment_method"][$item["student_id"]][] = Constants::$invoice_type[2][$item['payment_method']] ;
                    }
                }
                if(!empty($item['due_date'])) {
                    $date = strtotime($item['due_date']);
                    $res["class_due_date"][$item["student_id"]][] = date('n', $date) . "月" . date('j', $date) . "日";
                }
                $res["_class_except"][$item["student_id"]][] = $item["except_flag"];
                if (!$item["except_flag"]) {
                    $amount += $item["unit_price"];
                }
            } elseif(isset($item["course_id"])) {
                $res["course_id"][$item["student_id"]][] = $item["course_id"];
                $res["course_name"][$item["student_id"]][] = $item["item_name"];
                $res["course_price"][$item["student_id"]][] = $item["unit_price"];
                $res["_course_except"][$item["student_id"]][] = $item["except_flag"];
                if (!$item["except_flag"]) {
                    $amount += $item["unit_price"];
                }
            } elseif(isset($item["program_id"])) {
                $res["program_id"][$item["student_id"]][] = $item["program_id"];
                $res["program_name"][$item["student_id"]][] = $item["item_name"];
                $res["program_price"][$item["student_id"]][] = $item["unit_price"];
                $res["_program_except"][$item["student_id"]][] = $item["except_flag"];
                if (!$item["except_flag"]) {
                    $amount += $item["unit_price"];
                }
            } else {
                $res["custom_item_id"][$item["student_id"]][] = $item["id"];
                $res["custom_item_name"][$item["student_id"]][] = $item["item_name"];
                $res["custom_item_price"][$item["student_id"]][] = $item["unit_price"];
                $res["_custom_except"][$item["student_id"]][] = $item["except_flag"];
                if (!$item["except_flag"]) {
                    $amount += $item["unit_price"];
                }
            }
        }

        //  info of amount and tax
        $res = array_merge($res,$data);

        $tax_price = 0;
        $amount_tax = 0;

        //
        $res["sum_discount_price"] = $sum_discount_price;
        $amount += $sum_discount_price;
        $res["amount"] = $amount;
        $res['sales_tax_disp'] = $res['sales_tax_rate']*100;

        $sales_tax_rate = floatval($res["sales_tax_rate"]);

        if ($res["amount_display_type"] == "0") {
            $tax_price = floor($amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100));
            $amount_tax = $amount;
        } else {
            $tax_price = floor($amount * $sales_tax_rate);
            $amount_tax = $amount + $tax_price;
        }

        $res["tax_price"] = $tax_price;
        $res["amount_tax"] = $amount_tax;
        $res['due_date_jp'] = date('Y年m月d日',strtotime($res['due_date']));

        // cause custom item must have a student_id so get the first student in array as
        // current default student_id
        if(!empty($data['student_list'])){
            $res['current_student'] = $data['student_list'][0]['id'];
        }

        $res = array_merge($item_list,$res);
        return $res;
    }

    public function getCourseInvoiceItemEntry($invoice_header_id) {
        $bind = [$invoice_header_id];
        $sql = 'SELECT it.id AS item_id, e.id AS entry_id
                FROM invoice_item it
                INNER JOIN invoice_header ih ON ih.id = it.invoice_id
                INNER JOIN entry e ON (e.relative_id = it.course_id AND e.student_id = it.student_id)
                WHERE ih.id = ? AND e.entry_type = 2';
        return $this->fetchAll($sql, $bind);
    }

    /*
     * check if this student have a debit invoice in past or not
     * return true / false
     */
    public function checkIsDebitInvoice($student_id){

        $bind = array();
        $sql  = "SELECT student_id 
                  FROM invoice_item ii
                  INNER JOIN invoice_header ih ON ii.invoice_id = ih.id
                  WHERE ii.student_id = ?
                  AND ih.workflow_status < 31 
                  AND ih.delete_date IS NULL 
                  AND ii.except_flag = 0";

        $bind[] = $student_id;
        $result = $this->fetchAll($sql,$bind);

        if($result){
            return 1;
        }
        return 0;
    }
}