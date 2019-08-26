<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClassCoachTable extends DbModel {
    /**
     *
     * @var ClassCoachTable
     */
    private static $_instance = null;
    protected $table = 'class_coach';
    
    /**
     *
     * @return ClassCoachTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ClassCoachTable ();
        }
        return self::$_instance;
    }
    public function getCoachIDs($request, $pschool_id) {
        $res = array ();

        if (isset ( $request ['id'] )) {
            $bind = array ();
            
            $sql = " SELECT coach_id ";
            $sql .= " FROM class_coach ";
            $sql .= " WHERE delete_date IS NULL ";
            $sql .= " AND active_flag = 1 ";
            $sql .= " AND class_id = ? ";
            $sql .= " AND pschool_id = ? ";
            $bind [] = $request ['id'];
            $bind [] = $pschool_id;
            
            $res = $this->fetchAll ( $sql, $bind );
        }
        $ret = array ();
        if (! empty ( $res )) {
            foreach ( $res as $v ) {
                $ret [] = $v ['coach_id'];
            }
        }
        // null
        if (empty ( $ret ))
            $ret [] = "";
        
        return $ret;
    }
    public function getCoachNames($class_id, $pschool_id) {
        $res = array ();
        
        $bind = array ();
        
        $sql = " SELECT c.coach_name";
        $sql .= " FROM coach c";
        $sql .= " INNER JOIN class_coach cc ON";
        $sql .= " c.id = cc.coach_id";
        $sql .= " AND cc.class_id = ? ";
        $sql .= " AND cc.pschool_id = ?";
        $sql .= " AND cc.delete_date IS NULL";
        $sql .= " WHERE c.delete_date IS NULL";
        $sql .= " AND c.active_flag = 1 ";
        $bind [] = $class_id;
        $bind [] = $pschool_id;
        
        return $res = $this->fetchAll ( $sql, $bind );
    }
}
