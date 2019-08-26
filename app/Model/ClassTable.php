<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClassTable extends DbModel {
    /**
     *
     * @var ClassTable
     */
    private static $_instance = null;
    protected $table = 'class';
    /**
     *
     * @return ClassTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ClassTable ();
        }
        return self::$_instance;
    }

    // 2017-06-07 Tung Nguyen add ORM
    /**
     * The Coaches that belong to the class
     */
    public function coaches()
    {
        return $this->belongsToMany('App\Model\CoachTable', 'class_coach', 'class_id', 'coach_id');
    }

    public function getClassList4TopAxis($pschool_ids, $request = NULL, $pschool_id_own = null) {
        $ids = "";
        foreach ( $pschool_ids as $items ) {
            if (! empty ( $ids )) {
                $ids .= ", ";
            }
            $ids .= $items;
        }
        
        $sql = " Select c.*, IF (c.update_date IS NULL, c.register_date, c.update_date) AS last_update_date, t.teacher_name ";
        $sql .= " FROM class as c ";
        $sql .= " LEFT JOIN (select id, teacher_name from teacher WHERE delete_date is null) t on (t.id = c.teacher_id) ";
        $sql .= " where c.delete_date is NULL AND c.pschool_id IN (" . $ids . ")";
        
        $bind = array ();
        
        if (! empty ( $request ['class_id'] )) {
            $sql .= "  AND c.id = ? ";
            $bind [] = $request ['class_id'];
        }
        if (isset ( $request ['school_year'] ) && is_numeric ( $request ['school_year'] )) {
            $sql .= "  AND c.school_year = ? ";
            $bind [] = $request ['school_year'];
        }
        if (isset ( $request ['school_category'] ) && is_numeric ( $request ['school_category'] )) {
            $sql .= "  AND c.school_category = ? ";
            $bind [] = $request ['school_category'];
        }
        if (! empty ( $request ['name'] )) {
            $sql .= "  AND c.class_name like ? ";
            $bind [] = "%" . $request ['name'] . "%";
        }
        $sql .= " ORDER BY last_update_date DESC ";
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        
        foreach ( $res as $key => $value ) {
            $res [$key] ['menu_id'] = 4;
            
            // 複数講師
            $teachers = array ();
            // $teachers = ClassTeacherRelTable::getInstance()->getTeacherIDs($value);
            $teachers = ClassCoachTable::getInstance ()->getCoachNames ( $value ['id'], $pschool_id_own );
            if (! empty ( $teachers )) {
                $subcount = count ( $teachers ) - 1;
                if ($subcount > 0) {
                    $res [$key] ['teacher_name'] = $teachers [0] ['coach_name'] . " 他" . $subcount . "名";
                } else {
                    $res [$key] ['teacher_name'] = $teachers [0] ['coach_name'];
                }
            }
            
            // 生徒数
            $student_count = 0;
            $student_class = StudentClassTable::getInstance ()->getStudentListExistsAxis ( $value ['id'], "", session ( 'school.login.id' ) );
            if (! empty ( $student_class ))
                $student_count = count ( $student_class );
            $res [$key] ['student_count'] = $student_count;
            // $res[$key]['student_count'] = StudentClassTable::getInstance()->getActiveCount(array('class_id'=>$value['id']));
            
            // 終了？
            if (empty ( $value ['close_date'] ) || $value ['close_date'] >= date ( 'Y-m-d' )) {
                $res [$key] ['is_active'] = 1;
            }
        }
        
        return $res;
    }
    
    // ここに実装して下さい
    public function getListByStudent($student_id) {
        $bind = array ();
        $sql = " SELECT a.* FROM {$this->getTableName(true)} a ";
        $sql .= " INNER JOIN student_class b ON a.id = b.class_id ";
        $sql .= '  AND b.student_id = ?';
        $sql .= ' WHERE a.active_flag = 1 AND a.delete_date is null AND b.delete_date is null';
        $sql .= ' ORDER BY a.class_name ASC';
    
        $bind [] = $student_id;
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
    
        return json_decode ( json_encode ( $res ), true );
    }
    /**
     * プランの割引・割増取得
     *
     * @param unknown $pschool_id
     * @param unknown $parent_id
     * @param unknown $data_div
     */
    public function getClassAdjustList($pschool_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
    
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $month;
        $bind [] = $year_month;
        $bind [] = $year_month;
        $bind [] = $parent_id;
        $bind [] = $pschool_id;
    
        $sql = " SELECT RPIAN.*, SCCSP.id AS student_id ";
        $sql .= " FROM ";
        $sql .= " ( ";
        $sql .= "     SELECT RP.* , IAN.name ";
        $sql .= "     FROM routine_payment AS RP ";
        $sql .= "     INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= "     ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= "     WHERE RP.delete_date IS NULL ";
        $sql .= "     AND RP.pschool_id = ? ";
        $sql .= "     AND RP.data_div = 1 ";
        $sql .= "     AND RP.active_flag = 1 ";
        $sql .= "     AND (RP.month = ? OR RP.month = 99) ";
        $sql .= " ) AS RPIAN ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        $sql .= "     SELECT SCC.class_id, SP.id ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, SC.class_id ";
        $sql .= "         FROM student_class AS SC ";
        $sql .= "         INNER JOIN class AS C ";
        $sql .= "         ON SC.class_id = C.id ";
        $sql .= "         WHERE SC.delete_date IS NULL ";
        $sql .= "         AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        $sql .= "     ) AS SCC ";
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT S.id ";
        $sql .= "         FROM student AS S ";
        $sql .= "         INNER JOIN parent AS P ";
        $sql .= "         ON S.parent_id = P.id ";
        $sql .= "         WHERE S.delete_date IS NULL ";
        $sql .= "         AND S.parent_id = ? ";
        $sql .= "         AND S.pschool_id = ? ";
        $sql .= "     ) AS SP ";
        $sql .= "     ON SCC.student_id = SP.id ";
        $sql .= " ) AS SCCSP ";
        $sql .= " ON RPIAN.data_id = SCCSP.class_id ";
    
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * プランの割引・割増取得  会員版
     *
     * @param unknown $pschool_id
     * @param unknown $parent_id
     * @param unknown $data_div
     */
    public function getClassAdjustList_Axis($pschool_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
    
        $bind = array ();
        $bind [] = $month;
        $bind [] = $year_month;
        $bind [] = $year_month;
        $bind [] = $parent_id;
        $bind [] = $pschool_id;
    
        $sql = " SELECT RPIAN.*, SCCSP.id AS student_id, SCCSP.student_name, SCCSP.class_name, SCCSP.class_id ";
        $sql .= " FROM ";
        $sql .= " ( ";
        $sql .= "     SELECT RP.* , IAN.name ";
        $sql .= "     FROM routine_payment AS RP ";
        $sql .= "     INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= "     ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= "     WHERE RP.delete_date IS NULL ";
        $sql .= "     AND RP.data_div = 1 ";
        $sql .= "     AND RP.active_flag = 1 ";
        $sql .= "     AND (RP.month = ? OR RP.month = 99) ";
        $sql .= " ) AS RPIAN ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        $sql .= "     SELECT SCC.class_id, SP.id, SP.student_name, SCC.class_name ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, SC.class_id, C.class_name ";
        $sql .= "         FROM student_class AS SC ";
        $sql .= "         INNER JOIN class AS C ";
        $sql .= "         ON SC.class_id = C.id ";
        $sql .= "         WHERE SC.delete_date IS NULL ";
        $sql .= "         AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        $sql .= "     ) AS SCC ";
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT S.id, S.student_name ";
        $sql .= "         FROM student AS S ";
        $sql .= "         INNER JOIN parent AS P ";
        $sql .= "         ON S.parent_id = P.id ";
        $sql .= "         WHERE S.delete_date IS NULL ";
        $sql .= "         AND S.parent_id = ? ";
        $sql .= "         AND S.pschool_id = ? ";
        $sql .= "     ) AS SP ";
        $sql .= "     ON SCC.student_id = SP.id ";
        $sql .= " ) AS SCCSP ";
        $sql .= " ON RPIAN.data_id = SCCSP.class_id ";
    
        return $this->fetchAll ( $sql, $bind );
    }

    public function getClasses($pschool_ids) {
        $ids = "";
        foreach ( $pschool_ids as $items ) {
            if (! empty ( $ids )) {
                $ids .= ", ";
            }
            $ids .= $items;
        }

        $bind = array ();
        $sql = " SELECT DISTINCT * from class";
        $sql .= " WHERE pschool_id IN (" . $ids . ")";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " AND active_flag = 1 ";

        return $this->fetchAll ( $sql, $bind );
    }

    public function getClassByCoachId($pschool_id, $coach_id) {
        $bind = array();
        $sql = " SELECT * FROM class c ";
        $sql .= " INNER JOIN class_coach r ON c.id = r.class_id ";
        $sql .= " WHERE r.pschool_id = ? AND r.coach_id = ? AND r.delete_date is NULL ";
        $bind[] = $pschool_id;
        $bind[] = $coach_id;

        return $this->fetchAll($sql, $bind);
    }

    public function getListClassByStudent($studentId) {
        $data = DB::table('class AS c')
            ->select(DB::raw('c.class_name, c.close_date, sc.register_date, sc.delete_date'))
            ->join('student_class AS sc', 'sc.class_id', '=', 'c.id')
            ->where('sc.student_id', '=', $studentId)
            ->whereNull('c.delete_date')
            ->get();
        return $this->convertToArray($data);
    }
}
