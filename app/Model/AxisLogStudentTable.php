<?php

namespace App\Model;

use App\Model\AxisLogAdjustTable;
use App\Model\StudentGradeRelTable;

class AxisLogStudentTable extends DbModel {
    
    /**
     *
     * @var AxisLogStudentTable
     */
    private static $_instance = null;
    protected $table = 'axis_log_student';
    public $timestamps = false;
    /**
     *
     * @return AxisLogStudentTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new AxisLogStudentTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getAxisSchoolList($str_date = null) {
        $sql = ' SELECT A.*, B.name as pschool_name, B.payment_style, B.language ';
        $sql .= ' FROM hierarchy A ';
        $sql .= ' LEFT JOIN pschool B ON (A.pschool_id=B.id) ';
        $sql .= ' WHERE A.parent_id IS NOT NULL ';
        // $sql.= ' and A.manage_flag = 0 ';
        // $sql.= ' and A.pschool_id = 70 ';
        $sql .= ' and ( A.delete_date IS NULL OR SUBSTRING(A.delete_date, 1, 7) = ? )';
        $sql .= ' and ( B.delete_date IS NULL OR SUBSTRING(B.delete_date, 1, 7) = ? ) ';
        $bind = array (
                date ( 'Y-m', $str_date ),
                date ( 'Y-m', $str_date ) 
        );
        return $this->fetchAll ( $sql, $bind );
    }
    public function getAxisStudentList($school, $str_date = null) {
        $bind = array ();
        $sql = ' SELECT A.id as student_id, A.student_name, A.school_category as student_category_id, A.birthday, A.sex as student_sex, A.enter_date, A.resign_date, A.enter_memo as student_enter_memo, A.resign_memo as student_resign_memo, A.delete_date ';
        $sql .= ' , B.invoice_type as student_invoice_type_id ';
        $sql .= ' , C.type as student_type_id, C.name as student_type_name ';
        $sql .= ' , D.id as student_pref_id, D.name as student_pref_name ';
        $sql .= ' , E.id as student_area_id, E.name as student_area_name ';
        // $sql.= ' , G.id as student_grade_id, G.grade_color as student_grade_name, F.gain_date ';
        $sql .= ' , COUNT(H.student_id) as student_visit_count ';
        $sql .= ' FROM student as A ';
        $sql .= ' LEFT JOIN parent as B ON (A.parent_id=B.id) ';
        $sql .= ' LEFT JOIN m_student_type C ON (A.pschool_id=C.pschool_id AND A.student_type=C.type) ';
        $sql .= ' LEFT JOIN m_pref as D ON (A._pref_id=D.id ) ';
        $sql .= ' LEFT JOIN m_city as E ON (A._city_id=E.id ) ';
        // $sql.= ' LEFT JOIN student_grade_rel as F ON (A.id=F.student_id AND F.active_flag=1 AND F.delete_date IS NULL) ';
        // $sql.= ' LEFT JOIN student_grade as G ON (F.grade_id=G.id AND G.active_flag=1 AND G.delete_date IS NULL) ';
        $sql .= ' LEFT JOIN school_visit_history as H ON (A.id=H.student_id AND H.pschool_id=? AND H.visit_month=? ) ';
        $bind [] = $school ['pschool_id'];
        $bind [] = date ( 'Y-m', $str_date );
        $sql .= ' WHERE A.pschool_id = ? ';
        $bind [] = $school ['pschool_id'];
        $sql .= ' and ( A.enter_date IS NULL OR SUBSTRING(A.enter_date, 1, 7) <= ? ) ';
        $bind [] = date ( 'Y-m', $str_date );
        $sql .= ' and ( A.resign_date IS NULL OR SUBSTRING(A.resign_date, 1, 7) >= ? ) ';
        $bind [] = date ( 'Y-m', $str_date );
        $sql .= ' and ( A.delete_date IS NULL OR SUBSTRING(A.delete_date, 1, 7) = ? ) ';
        $bind [] = date ( 'Y-m', $str_date );
        $sql .= ' and ( B.delete_date IS NULL OR SUBSTRING(B.delete_date, 1, 7) = ? ) ';
        $bind [] = date ( 'Y-m', $str_date );
        $sql .= ' GROUP BY A.id ';
        $students = json_decode ( json_encode ( $this->fetchAll ( $sql, $bind ) ), true );
        
        // 支払方法
        $invoice_types = ConstantsModel::$invoice_type [$school ['language']];
        $student_categorys = ConstantsModel::$dispSchoolCategory;
        $adjustTable = AxisLogAdjustTable::getInstance ();
        foreach ( $students as $key => $value ) {
            if (empty ( $value ['resign_date'] ) && ! empty ( $value ['delete_date'] )) {
                $value ['resign_date'] = $value ['delete_date'];
            }
            $students [$key] ['id'] = null;
            $students [$key] ['group_id'] = $school ['group_id'];
            $students [$key] ['layer'] = $school ['layer'];
            $students [$key] ['pschool_id'] = $school ['pschool_id'];
            $students [$key] ['pschool_name'] = $school ['pschool_name'];
            $students [$key] ['log_year'] = date ( 'Y', $str_date );
            $students [$key] ['log_month'] = date ( 'n', $str_date );
            $students [$key] ['student_invoice_type_name'] = (empty ( $value ['student_invoice_type_id'] ) || empty ( $invoice_types [$value ['student_invoice_type_id']] )) ? null : $invoice_types [$value ['student_invoice_type_id']];
            $students [$key] ['student_category_name'] = (empty ( $value ['student_category_id'] ) || $student_categorys [$value ['student_category_id']]) ? null : $student_categorys [$value ['student_category_id']];
            $students [$key] ['student_birth_year'] = empty ( $value ['birthday'] ) ? null : date ( 'Y', strtotime ( $value ['birthday'] ) );
            $students [$key] ['student_birth_month'] = empty ( $value ['birthday'] ) ? null : date ( 'n', strtotime ( $value ['birthday'] ) );
            $students [$key] ['student_birth_day'] = empty ( $value ['birthday'] ) ? null : date ( 'j', strtotime ( $value ['birthday'] ) );
            $students [$key] ['student_zodiac'] = empty ( $value ['birthday'] ) ? null : $this->getZodiac ( $value ['birthday'] );
            $students [$key] ['student_age'] = empty ( $value ['birthday'] ) ? null : floor ( (date ( 'Ymt', $str_date ) - date ( 'Ymd', strtotime ( $value ['birthday'] ) )) / 10000 );
            $students [$key] ['student_enter_year'] = empty ( $value ['enter_date'] ) ? null : date ( 'Y', strtotime ( $value ['enter_date'] ) );
            $students [$key] ['student_enter_month'] = empty ( $value ['enter_date'] ) ? null : date ( 'n', strtotime ( $value ['enter_date'] ) );
            $students [$key] ['student_enter_day'] = empty ( $value ['enter_date'] ) ? null : date ( 'j', strtotime ( $value ['enter_date'] ) );
            $students [$key] ['student_resign_year'] = empty ( $value ['resign_date'] ) ? null : date ( 'Y', strtotime ( $value ['resign_date'] ) );
            $students [$key] ['student_resign_month'] = empty ( $value ['resign_date'] ) ? null : date ( 'n', strtotime ( $value ['resign_date'] ) );
            $students [$key] ['student_resign_day'] = empty ( $value ['resign_date'] ) ? null : date ( 'j', strtotime ( $value ['resign_date'] ) );
            $students [$key] ['student_months'] = empty ( $value ['enter_date'] ) ? null : $this->getMonths ( $value ['enter_date'], $value ['resign_date'], $str_date );
            
            $students [$key] += StudentGradeRelTable::getInstance ()->getGradeHistory ( $value ['student_id'], date ( 'Y-m-t', $str_date ) );
            $students [$key] ['student_grade_year'] = empty ( $students [$key] ['gain_date'] ) ? null : date ( 'Y', strtotime ( $students [$key] ['gain_date'] ) );
            $students [$key] ['student_grade_month'] = empty ( $students [$key] ['gain_date'] ) ? null : date ( 'n', strtotime ( $students [$key] ['gain_date'] ) );
            $students [$key] ['student_grade_day'] = empty ( $students [$key] ['gain_date'] ) ? null : date ( 'j', strtotime ( $students [$key] ['gain_date'] ) );
            $students [$key] ['student_grade_months'] = empty ( $students [$key] ['gain_date'] ) ? null : $this->getMonths ( $students [$key] ['gain_date'], null, $str_date );
            
            // no use
            $students [$key] ['group_name'] = null;
            $students [$key] ['student_enter_id'] = null;
            $students [$key] ['student_enter_name'] = null;
            $students [$key] ['student_resign_id'] = null;
            $students [$key] ['student_resign_name'] = null;
            
            $students [$key] += $adjustTable->getAxisInvoiceList ( $school, $str_date, $value ['student_id'] );
            $students [$key] ['register_date'] = date ( 'Y-m-d H:i:s' );
            
            unset ( $students [$key] ['birthday'] );
            unset ( $students [$key] ['enter_date'] );
            unset ( $students [$key] ['resign_date'] );
            unset ( $students [$key] ['delete_date'] );
            unset ( $students [$key] ['gain_date'] );
        }
        
        return $students;
    }
    public function getMonths($enter_date = null, $resign_date = null, $str_date = null) {
        if (empty ( $enter_date )) {
            return null;
        }
        
        if (empty ( $resign_date )) {
            $resign_date = date ( 'Y-m-t', $str_date );
        }
        
        $months = date ( 'Y', strtotime ( $resign_date ) ) * 12 - date ( 'Y', strtotime ( $enter_date ) ) * 12 + date ( 'n', strtotime ( $resign_date ) ) - date ( 'n', strtotime ( $enter_date ) );
        return $months;
    }
    public function getZodiac($birthday) {
        if (empty ( $birthday )) {
            return null;
        }
        
        $zodiac = null;
        $date = date ( 'nd', strtotime ( $birthday ) );
        if ($date < 121) {
            $zodiac = 10; // 山羊座
        } elseif ($date < 220) {
            $zodiac = 11; // 水瓶座
        } elseif ($date < 321) {
            $zodiac = 12; // 魚座
        } elseif ($date < 421) {
            $zodiac = 1; // 牡羊座
        } elseif ($date < 521) {
            $zodiac = 2; // 牡牛座
        } elseif ($date < 622) {
            $zodiac = 3; // 双子座
        } elseif ($date < 724) {
            $zodiac = 4; // 蟹座
        } elseif ($date < 824) {
            $zodiac = 5; // 獅子座
        } elseif ($date < 924) {
            $zodiac = 6; // 乙女座
        } elseif ($date < 1024) {
            $zodiac = 7; // 天秤座
        } elseif ($date < 1123) {
            $zodiac = 8; // 蠍座
        } elseif ($date < 1223) {
            $zodiac = 9; // 射手座
        } else {
            $zodiac = 10; // 山羊座
        }
        return $zodiac;
    }
    public function getPschoolsSelect($pschool_id) {
        $pshool_list = $this->getDispHierarchy ( $pschool_id );
        
        $pshools = array ();
        if (! empty ( $pshool_list )) {
            $alls = array ();
            foreach ( $pshool_list as $key => $value ) {
                $alls [] = $value ['id'];
            }
            $all_id = implode ( ",", $alls );
            $pshools [$all_id] = '';
            foreach ( $pshool_list as $key => $value ) {
                $pshools [$value ['id']] = $value ['name'];
            }
        }
        return $pshools;
    }
    public function getDispHierarchy($pschool_id) {
        $res = $this->getChildPschools ( $pschool_id, null, 5 );
        $ret = $res;
        
        $ii = 10;
        while ( $ii < 1000 ) {
            $res = $this->getChildPschools ( null, $res, $ii );
            
            if (empty ( $res )) {
                break;
            } else {
                // $ret = array_merge($ret, $res);
                
                $dummy_list = array ();
                foreach ( $ret as $parent ) {
                    $dummy_list [] = $parent;
                    foreach ( $res as $child ) {
                        if ($parent ['hierarchy_id'] == $child ['parent_id']) {
                            $dummy_list [] = $child;
                        }
                    }
                }
                $ret = $dummy_list;
                $ii += 5;
            }
            // echo "<!--".$ii. print_r($res, true)."-->";
        }
        
        return json_decode ( json_encode ( $ret ), true );
    }
    private function getChildPschools($pschool_id = null, $pschool_list = null, $ii = 5) {
        $bind = array (
                $ii 
        );
        $sql = ' SELECT A.id as hierarchy_id, B.id, B.name, ? as layer, A.parent_id ';
        $sql .= ' FROM hierarchy as A ';
        $sql .= ' LEFT JOIN pschool as B ON A.pschool_id = B.id ';
        $sql .= ' WHERE A.delete_date IS NULL ';
        if (! empty ( $pschool_id )) {
            $sql .= ' and A.pschool_id = ? ';
            $bind [] = $pschool_id;
        } elseif (! empty ( $pschool_list )) {
            $list = array ();
            foreach ( $pschool_list as $pschool ) {
                $list [] = ' A.parent_id = ? ';
                $bind [] = $pschool ['hierarchy_id'];
            }
            $sql .= ' and ( ' . implode ( "OR", $list ) . ' ) ';
        }
        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
        ;
    }
    public function getDispList($pschool, $search) {
        $ret = array (
                'id' => $pschool ['id'],
                'name' => $pschool ['name'],
                'layer' => $pschool ['layer'] 
        );
        
        for($month = 1; $month <= 12; $month ++) {
            $sum = $this->getDispMonth ( $pschool, $search, $month );
            $ret += array (
                    $month => $sum 
            );
        }
        
        return $ret;
    }
    public function getDispDetail($pschool, $search) {
        $ret = array (
                'id' => $pschool ['id'],
                'name' => $pschool ['name'],
                'layer' => $pschool ['layer'] 
        );
        
        if ($search ['dispTypes'] > 2) {
            // 入会理由等に変える
            $student_list = $this->getDispMonth ( $pschool, $search, $search ['dispMonths'], 'student_list' );
            if (empty ( $student_list ) || $student_list == - 1) {
                $ret += array (
                        'sum' => 0 
                );
            } else {
                $ret += array (
                        'sum' => count ( $student_list ) 
                );
                $ret += array (
                        'student_list' => $student_list 
                );
            }
        } else {
            // 性別・年代別
            for($detail = 1; $detail <= 12; $detail ++) {
                $sum = $this->getDispMonth ( $pschool, $search, $search ['dispMonths'], $detail );
                $ret += array (
                        $detail => $sum 
                );
            }
        }
        
        return $ret;
    }
    public function getDispMonth($pschool, $search, $month, $detail = null) {
        if (($search ['dispYears'] == date ( 'Y' ) && $month >= date ( 'n' )) || $search ['dispYears'] > date ( 'Y' )) {
            // 今月以降はデータない
            return - 1;
        }
        
        $sql = '';
        $bind = array ();
        if ($detail == 'student_list' && $search ['dispTypes'] == 3) {
            // 入会理由
            $sql = ' SELECT A.student_name, A.student_enter_year as s_year, A.student_enter_month as s_month, A.student_enter_day as s_day, A.student_enter_name as s_refer, A.student_enter_memo as s_memo ';
        } elseif ($detail == 'student_list') {
            // 退会理由
            $sql = ' SELECT A.student_name, A.student_resign_year as s_year, A.student_resign_month as s_month, A.student_resign_day as s_day, A.student_months as s_refer, A.student_resign_memo as s_memo ';
        } elseif ($search ['dispTypes'] == 2) {
            // 金額
            $sql = ' SELECT SUM(B.student_fee) as sum ';
        } else {
            // 会員数
            $sql = ' SELECT COUNT(DISTINCT A.student_id) as sum ';
        }
        $sql .= ' FROM axis_log_student as A ';
        
        $sql .= ' LEFT JOIN axis_log_event_student_rel as B ON A.student_id = B.log_student_id and B.log_year=? and B.log_month = ? ';
        $bind [] = $search ['dispYears'];
        $bind [] = $month;
        
        $sql .= ' LEFT JOIN axis_log_event as C ON B.log_event_id = C.id  and C.log_year=? and C.log_month = ? ';
        $bind [] = $search ['dispYears'];
        $bind [] = $month;
        
        $sql .= ' WHERE A.delete_date IS NULL ';
        
        if ($search ['dispTypes'] == 2 || (! empty ( $search ['dispKinds'] ) && $search ['dispKinds'] == 4)) {
            // 出稽古
            $sql .= ' and C.pschool_id = ? ';
            $bind [] = $pschool ['id'];
        } else {
            $sql .= ' and A.pschool_id = ? ';
            $bind [] = $pschool ['id'];
        }
        
        // 年月
        $sql .= ' and A.log_year=? and A.log_month = ? ';
        $bind [] = $search ['dispYears'];
        $bind [] = $month;
        
        // 新規会員数
        if ($search ['dispTypes'] == 3) {
            $sql .= ' and A.student_enter_year = ? ';
            $bind [] = $search ['dispYears'];
            $sql .= ' and A.student_enter_month = ? ';
            $bind [] = $month;
        }
        
        // 退会員数
        if ($search ['dispTypes'] == 4) {
            $sql .= ' and A.student_resign_year = ? ';
            $bind [] = $search ['dispYears'];
            $sql .= ' and A.student_resign_month = ? ';
            $bind [] = $month;
        }
        
        // イベント項目
        if (! empty ( $search ['dispEvents'] )) {
            $sql .= ' and C.event_category_id = ? ';
            $bind [] = $search ['dispKinds'];
            $sql .= ' and C.event_id = ? ';
            $bind [] = $search ['dispEvents'];
        } // イベント種類
elseif (! empty ( $search ['dispKinds'] )) {
            $sql .= ' and C.event_category_id = ? ';
            $bind [] = $search ['dispKinds'];
        }
        
        if (empty ( $detail )) {
        } elseif ($detail == 2) { // 男性
            $sql .= ' and A.student_sex = 1 ';
        } elseif ($detail == 3) { // 女性
            $sql .= ' and A.student_sex = 2 ';
        } elseif ($detail == 4) { // 10歳未満
            $sql .= ' and A.student_age < 10 ';
        } elseif ($detail == 5) { // 10代
            $sql .= ' and (A.student_age >= 10 AND A.student_age < 20) ';
        } elseif ($detail == 6) { // 20代
            $sql .= ' and (A.student_age >= 20 AND A.student_age < 30) ';
        } elseif ($detail == 7) { // 30代
            $sql .= ' and (A.student_age >= 30 AND A.student_age < 40) ';
        } elseif ($detail == 8) { // 40代
            $sql .= ' and (A.student_age >= 40 AND A.student_age < 50) ';
        } elseif ($detail == 9) { // 50代
            $sql .= ' and (A.student_age >= 50 AND A.student_age < 60) ';
        } elseif ($detail == 10) { // 60代
            $sql .= ' and (A.student_age >= 60 AND A.student_age < 70) ';
        } elseif ($detail == 11) { // 70代
            $sql .= ' and (A.student_age >= 70 AND A.student_age < 80) ';
        } elseif ($detail == 12) { // 80歳以上
            $sql .= ' and A.student_age >= 80 ';
        }
        
        // 日付順で並び替え
        if ($detail == 'student_list') {
            if ($search ['dispTypes'] == 3) {
                // 入会
                $sql .= ' ORDER BY A.student_enter_day ASC ';
            } else {
                // 退会
                $sql .= ' ORDER BY A.student_resign_day ASC ';
            }
        }
        
        if ($detail == 'student_list') {
            $res = $this->fetchAll ( $sql, $bind );
            return $res;
        } else {
            $res = $this->fetch ( $sql, $bind );
            $sum = empty ( $res ['sum'] ) ? 0 : $res ['sum'];
            return $sum;
        }
    }
    public function getDispYearList($pschool_list) {
        $pschool_ids = array ();
        foreach ( $pschool_list as $pschool ) {
            $pschool_ids [] = ' A.pschool_id = ' . $pschool ['id'] . ' ';
        }
        $str_pschool = implode ( "OR", $pschool_ids );
        
        $ret = array ();
        
        $bind = array ();
        $sql = ' SELECT MIN( A.log_year ) as min';
        $sql .= ' FROM axis_log_student as A ';
        $sql .= ' WHERE A.delete_date IS NULL ';
        
        $sql .= ' and ( ' . $str_pschool . ' ) ';
        
        $res = $this->fetch ( $sql, $bind );
        $max = date ( 'Y', strtotime ( "-1 month" ) );
        $min = empty ( $res ['min'] ) ? $max : $res ['min'];
        if ($_SESSION ['school.login'] ['language'] == 1) {
            // Japan
            for($year = $max; $year >= $min; $year --) {
                $ret [$year] = $year . "年";
            }
        } else {
            // Other country
            for($year = $max; $year >= $min; $year --) {
                $ret [$year] = $year;
            }
        }
        
        return $ret;
    }
    public function getDispYearMonths($pschool_list, $c, $years) {
        $bind = array ();
        $pschool_ids = array ();
        foreach ( $pschool_list as $pschool ) {
            $pschool_ids [] = ' A.pschool_id = ? ';
            $bind [] = $pschool ['id'];
        }
        $str_pschool = implode ( "OR", $pschool_ids );
        
        $sql = ' SELECT MIN( A.log_month ) as min';
        $sql .= ' FROM axis_log_student as A ';
        $sql .= ' WHERE A.delete_date IS NULL ';
        $sql .= ' and ( ' . $str_pschool . ' ) ';
        $sql .= ' and A.log_year = ? ';
        
        $min_year = date ( 'Y', strtotime ( "-1 month" ) );
        foreach ( $years as $year ) {
            $min_year = $year;
        }
        $bind [] = $min_year;
        
        $res = $this->fetch ( $sql, $bind );
        
        $now = $c ['dispYears'] * 12 + $c ['dispMonths'];
        $max = date ( 'Y', strtotime ( "-1 month" ) ) * 12 + date ( 'n', strtotime ( "-1 month" ) );
        $min = empty ( $res ['min'] ) ? $max : $min_year * 12 + $res ['min'];
        $ret = array (
                'max' => $max,
                'min' => $min,
                'now' => $now 
        );
        $common = "_c[dispTypes]=" . $c ['dispTypes'] . "&_c[dispKinds]=" . $c ['dispKinds'] . "&_c[dispEvents]=" . $c ['dispEvents'];
        $ret ['lastyear_param'] = $common . "&_c[dispYears]=" . ($c ['dispYears'] - 1) . "&_c[dispMonths]=" . $c ['dispMonths'];
        $ret ['lastmonth_param'] = $common . "&_c[dispYears]=" . $c ['dispYears'] . "&_c[dispMonths]=" . ($c ['dispMonths'] - 1);
        $ret ['nextmonth_param'] = $common . "&_c[dispYears]=" . $c ['dispYears'] . "&_c[dispMonths]=" . ($c ['dispMonths'] + 1);
        $ret ['nextyear_param'] = $common . "&_c[dispYears]=" . ($c ['dispYears'] + 1) . "&_c[dispMonths]=" . $c ['dispMonths'];
        $ret ['search_param'] = $common . "&_c[dispYears]=" . $c ['dispYears'];
        return $ret;
    }
    public function getDispEventList($pschool_list, $search) {
        $pschool_ids = array ();
        foreach ( $pschool_list as $pschool ) {
            $pschool_ids [] = ' A.pschool_id = ' . $pschool ['id'] . ' ';
        }
        $str_pschool = implode ( "OR", $pschool_ids );
        
        $ret = array ();
        for($kind = 1; $kind <= 3; $kind ++) {
            
            $bind = array ();
            $sql = ' SELECT DISTINCT A.event_id, A.event_name ';
            $sql .= ' FROM axis_log_event as A ';
            $sql .= ' WHERE A.delete_date IS NULL ';
            
            $sql .= ' and ( ' . $str_pschool . ' ) ';
            
            // 種別
            $sql .= ' and A.event_category_id = ? ';
            $bind [] = $kind;
            
            $res = $this->fetchAll ( $sql, $bind );
            $ret [$kind] = $res;
            
            if (! empty ( $search ['dispKinds'] ) && ! empty ( $search ['dispEvents'] ) && $search ['dispKinds'] == $kind && ! empty ( $res )) {
                foreach ( $res as $value ) {
                    if ($search ['dispEvents'] == $value ['event_id']) {
                        $ret [9] = $value ['event_name'];
                    }
                }
            }
        }
        
        return $ret;
    }
}
