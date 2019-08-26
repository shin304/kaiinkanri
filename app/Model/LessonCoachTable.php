<?php

namespace App\Model;


class LessonCoachTable extends DbModel
{
    /**
     *
     * @var LessonCoachTable
     */
    private static $_instance = null;
    protected $table = 'lesson_coach';

    /**
     *
     * @return LessonCoachTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new LessonCoachTable ();
        }
        return self::$_instance;
    }

    public function getCoachByLesson($lesson_id, $limit=null) {
        $sql = "SELECT * FROM coach C ";
        $sql .= "INNER JOIN lesson_coach LC ON ";
        $sql .= "C.id = LC.coach_id ";
        $sql .= "AND LC.lesson_id = ? ";
        $sql .= "AND LC.delete_date IS NULL ";
        $sql .= "WHERE ";
        $sql .= "C.delete_date IS NULL ";
        $sql .= "AND C.active_flag = 1 ";
        if (!empty($limit)) {
            $sql .= "LIMIT ".$limit;
        }
        $bind = array();
        $bind[] = $lesson_id;

        return $this->fetchAll($sql, $bind);
    }
}
