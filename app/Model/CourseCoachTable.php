<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CourseCoachTable extends DbModel
{
    /**
     * @var CourseCoachTable
     */
    private static $_instance = null;
    protected $table = 'course_coach';

    /**
     * @return CourseCoachTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new CourseCoachTable();
        }
        return self::$_instance;
    }

    public function getCoachIDs($request, $pschool_id)
    {
        $res = array();

        if (isset($request['id'])){
            $bind = array();

            $sql  = " SELECT coach_id ";
            $sql .= " FROM course_coach ";
            $sql .= " WHERE delete_date IS NULL ";
            $sql .= " AND active_flag = 1 ";
            $sql .= " AND course_id = ? ";
            $sql .= " AND pschool_id = ? ";
            $bind[] = $request['id'];
            $bind[] = $pschool_id;

            $res = $this->fetchAll($sql, $bind);
        }
        $ret = array();
        if (!empty($res)){
            foreach ($res as $v){
                $ret[] = $v['coach_id'];
            }
        }
        // null
        if (empty($ret)) $ret[] = "";

        return $ret;
    }

    public function getCoachList($course_id, $pschool_id)
    {
        $sql  = " SELECT C.* ";
        $sql .= " FROM coach C";
        $sql .= " INNER JOIN course_coach CC ON ";
        $sql .= " C.id = CC.coach_id ";
        $sql .= " AND CC.course_id = ?";
        $sql .= " AND CC.active_flag = 1 ";
        $sql .= " AND CC.delete_date IS NULL ";
        $sql .= " WHERE ";
        $sql .= " C.delete_date IS NULL ";
        $sql .= " AND C.active_flag = 1 ";
        $sql .= " AND C.pschool_id = ? ";
        $bind = array();
        $bind[] = $course_id;
        $bind[] = $pschool_id;

        return  $this->fetchAll($sql, $bind);
    }
}
