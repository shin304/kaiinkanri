<?php

namespace App\Model;

use App\Model\HierarchyTable;
use Illuminate\Database\Eloquent\Model;

class MStudentTypeTable extends DbModel {
    /**
     *
     * @var MStudentTypeTable
     */
    private static $_instance = null;
    protected $table = 'm_student_type';
    
    /**
     *
     * @return MStudentTypeTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new MStudentTypeTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getStudentTypeList($school_ids, $language ,$student_category = null) {
        $bind = array ();
        $sql = " SELECT mst.* FROM m_student_type mst INNER JOIN pschool ps ON mst.pschool_id = ps.id ";
        $sql .= " WHERE mst.pschool_id IN (" . implode ( ",", $school_ids ) . ") ";
        $sql .= " AND mst.delete_date is null ";
        $sql .= " AND ps.language = ? ";
        $bind [] = $language;
        if(!empty($student_category)){
            $sql .= " AND mst.student_category = ? ";
            $bind [] = $student_category;
        }

        return $this->fetchAll ( $sql, $bind );
    }
    public function getStudentTypeName($school_id) {
        $bind = array ();
        $sql = " SELECT mst.* FROM m_student_type mst ";
        $sql .= " WHERE mst.pschool_id = ? ";
        $sql .= " AND mst.delete_date is null ";
        $bind [] = $school_id;
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * アクシス用
     * 
     * @param unknown $school_id            
     */
    public function getStudentTypeName_Axis($school_id, $type = null) {
        $pschool_parents = HierarchyTable::getInstance ()->getParentPschoolIds ( $school_id );
        $pschool_parents [] = $school_id;
        
        $parents = "";
        foreach ( $pschool_parents as $item ) {
            if (! empty ( $parents )) {
                $parents .= " ,";
            }
            $parents .= $item;
        }
        
        $bind = array ();
        $sql = " SELECT mst.* FROM m_student_type mst ";
        $sql .= " WHERE mst.pschool_id IN( " . $school_id . ") ";
        $sql .= " AND mst.delete_date is null ";
        
        if (! empty ( $type )) {
            $sql .= " AND mst.type = ? ";
            $bind [] = $type;
        }
        
        return $this->fetchAll ( $sql, $bind );
    }

    public function getListStudentTypeByPschool($pschool_id){
        $bind = array();
        $sql = " SELECT * FROM m_student_type ";
        $sql.= " WHERE pschool_id = ? AND delete_date is NULL";
        $bind[] = $pschool_id;

        return $this->fetchAll($sql,$bind);
    }
}
