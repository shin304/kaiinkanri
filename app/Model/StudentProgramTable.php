<?php

namespace App\Model;

class StudentProgramTable extends DbModel
{
    /**
     * @var StudentProgramTable
     */
    private static $_instance = null;
    protected $table = 'student_program';

    /**
     * @return StudentProgramTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new StudentProgramTable();
        }
        return self::$_instance;
    }

    // ここに実装して下さい
    /**
     * 指定されたプログラムと紐付けがない会員を取得します      あとから会員種別追加します。とりあえず版（川崎）
     * @param unknown_type $program_id
     * @param unknown_type $student_name
     * @param unknown_type $arryQuery
     */
    public function getStudentListNotExists($program_id, $pschool_id, $student_name, $arryQuery=null) {

        $pschool_parents   = HierarchyTable::getInstance ()->getParentPschoolIds($pschool_id);
        $pschool_parents[] = $pschool_id;
        $parent_ids = "";
        foreach ($pschool_parents as $item){
            if( !empty($parent_ids) ){
                $parent_ids .= ", ";
            }
            $parent_ids .= $item;
        }

        $sql  = " SELECT *, ST.name as student_type_name FROM student S ";
//      $sql .= "INNER JOIN m_student_type ST ON ";
//      $sql .= "S.pschool_id = ST.pschool_id ";
//      $sql .= "AND S.student_type = ST.type ";
        $sql .= " INNER JOIN ";
        $sql .= "  (SELECT type, name ";
        $sql .= "   FROM m_student_type ";
        $sql .= "   WHERE pschool_id IN( " . $parent_ids . ") ";
        $sql .= "   AND delete_date IS NULL) AS ST ";
        $sql .= "ON S.student_type = ST.type ";

        $sql .= " WHERE ";
        $sql .= " S.id NOT IN (SELECT student_id FROM student_program WHERE program_id = ? AND active_flag = 1 AND delete_date IS NULL) ";
        $sql .= " AND S.pschool_id = ? ";
        $sql .= " AND S.active_flag = 1 ";
        $sql .= " AND S.delete_date IS NULL ";
        $bind = array();
        $bind[] = $program_id;
        $bind[] = $pschool_id;
        if (!empty($student_name)) {
            $sql .= " AND (student_name like ? OR student_name_kana collate utf8_unicode_ci like ?)";
            $bind[] = "%" . $student_name . "%";
            $bind[] = "%" . $student_name . "%";
        }

        if(isset($arryQuery)){
            if(isset($arryQuery['student_no'])){        // 生徒番号
                if( !$this->isEmpty($arryQuery['student_no'])){
                    $sql .= " AND student_no = ?";
                    $bind[] = $arryQuery['student_no'];
                }
            }
            if(isset($arryQuery['student_type'])){      // 生徒種別
                if(!empty($arryQuery['student_type'])){
                    $sql .= " AND student_type in (".implode(',', array_fill(1, count($arryQuery['student_type']), '?')).")";
                    foreach ($arryQuery['student_type'] as $realval) {
                        $bind[] = $realval;
                    }
                }
            }
        }
        $sql .=  " ORDER BY student_name_kana ASC";
        return $this->fetchAll($sql, $bind);
    }

    /**
     * 指定されたプログラムと紐付いた会員を取得します
     * @param unknown_type $program_id
     * @param unknown_type $student_name
     * @param unknown_type $arryQuery
     */
    public function getStudentListExists($program_id, $pschool_id, $student_name=null, $arryQuery=null) {

        $pschool_parents   = HierarchyTable::getInstance ()->getParentPschoolIds($pschool_id);
        $pschool_parents[] = $pschool_id;
        $parent_ids = "";
        foreach ($pschool_parents as $item){
            if( !empty($parent_ids) ){
                $parent_ids .= ", ";
            }
            $parent_ids .= $item;
        }

        $sql  = " SELECT S.*, PFP.fee_plan_name, PFP.fee, ST.name as student_type_name FROM student S ";
        $sql .= "INNER JOIN student_program SP ON ";
        $sql .= "S.id = SP.student_id ";
        $sql .= "AND SP.program_id  = ? ";
        $sql .= "AND SP.delete_date IS NULL ";
        $sql .= "LEFT JOIN program_fee_plan PFP ON ";
        $sql .= "SP.plan_id = PFP.id ";
//      $sql .= "INNER JOIN m_student_type ST ON ";
//      $sql .= "S.pschool_id = ST.pschool_id ";
//      $sql .= "AND S.student_type = ST.type ";

        $sql .= " INNER JOIN ";
        $sql .= "  (SELECT type, name ";
        $sql .= "   FROM m_student_type ";
        $sql .= "   WHERE pschool_id IN( " . $parent_ids . ") ";
        $sql .= "   AND delete_date IS NULL) AS ST ";
        $sql .= "ON S.student_type = ST.type ";

        $sql .= "WHERE ";
        $sql .= "S.pschool_id = ? ";
        $sql .= "AND S.active_flag = 1 ";
        $sql .= "AND S.delete_date is null ";
        $bind = array();
        $bind[] = $program_id;
        $bind[] = $pschool_id;
        if (!empty($student_name)) {
            $sql .= " AND (student_name like ? OR student_name_kana collate utf8_unicode_ci like ?)";
            $bind[] = "%" . $student_name . "%";
            $bind[] = "%" . $student_name . "%";
        }

        if(isset($arryQuery)){
            if(isset($arryQuery['student_no'])){        // 生徒番号
                if( !$this->isEmpty($arryQuery['student_no'])){
                    $sql .= " AND student_no = ?";
                    $bind[] = $arryQuery['student_no'];
                }
            }
            if(isset($arryQuery['student_type'])){      // 生徒種別
                if(!empty($arryQuery['student_type'])){
                    $sql .= " AND student_type in (".implode(',', array_fill(1, count($arryQuery['student_type']), '?')).")";
                    foreach ($arryQuery['student_type'] as $realval) {
                        $bind[] = $realval;
                    }
                }
            }
        }

        $sql .=  " ORDER BY student_name_kana ASC";

        return $this->fetchAll($sql, $bind);
    }

    public function getStudentListAll($program_id, $pschool_id, $student_name=null, $arryQuery=null) {

        $pschool_parents   = HierarchyTable::getInstance ()->getParentPschoolIds($pschool_id);
        $pschool_parents[] = $pschool_id;
        $parent_ids = "";
        foreach ($pschool_parents as $item){
            if( !empty($parent_ids) ){
                $parent_ids .= ", ";
            }
            $parent_ids .= $item;
        }

        $sql  = " SELECT S.*, PFP.fee_plan_name, PFP.fee, ST.name as student_type_name  FROM student S ";
        $sql .= "INNER JOIN student_program SP ON ";
        $sql .= "S.id = SP.student_id ";
        $sql .= "AND SP.program_id  = ? ";
        $sql .= "AND SP.delete_date IS NULL ";
        $sql .= "LEFT JOIN program_fee_plan PFP ON ";
        $sql .= "SP.plan_id = PFP.id ";
//      $sql .= "INNER JOIN m_student_type ST ON ";
//      $sql .= "S.pschool_id = ST.pschool_id ";
//      $sql .= "AND S.student_type = ST.type ";

        $sql .= "INNER JOIN ";
        $sql .= "   (SELECT id, name, type ";
        $sql .= "   FROM m_student_type ";
        $sql .= "   WHERE pschool_id IN( " . $parent_ids . " ))  AS ST ";
        $sql .= "ON S.m_student_type_id = ST.id ";

        $sql .= "WHERE ";
        $sql .= "S.pschool_id = ? ";
        $bind = array();
        $bind[] = $program_id;
        $bind[] = $pschool_id;
        if (!empty($student_name)) {
            $sql .= " AND (student_name like ? OR student_name_kana collate utf8_unicode_ci like ?)";
            $bind[] = "%" . $student_name . "%";
            $bind[] = "%" . $student_name . "%";
        }

        if(isset($arryQuery)){
            if(isset($arryQuery['student_no'])){        // 生徒番号
                if( !$this->isEmpty($arryQuery['student_no'])){
                    $sql .= " AND student_no = ?";
                    $bind[] = $arryQuery['student_no'];
                }
            }
            if(isset($arryQuery['student_type'])){      // 生徒種別
                if(!empty($arryQuery['student_type'])){
                    $sql .= " AND student_type in (".implode(',', array_fill(1, count($arryQuery['student_type']), '?')).")";
                    foreach ($arryQuery['student_type'] as $realval) {
                        $bind[] = $realval;
                    }
                }
            }
        }

        $sql .=  " ORDER BY student_name_kana ASC";

        return $this->fetchAll($sql, $bind);
    }

    private function isEmpty($param_value = null) {

        $return_cd = false; // 入力されている
        if(!strlen($param_value)){
            // 空文字
            $return_cd = true;  //未入力
        }

        return $return_cd;
    }
}
