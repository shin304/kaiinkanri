<?php

namespace App\Model;


class LessonTable extends DbModel
{
    /**
     *
     * @var LessonTable
     */
    private static $_instance = null;
    protected $table = 'lesson';
    
    /**
     *
     * @return LessonTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new LessonTable ();
        }
        return self::$_instance;
    }

    // 2017-06-07 Tung Nguyen add ORM
    /**
     * The coaches that belong to the lesson
     */
    public function coaches()
    {
        return $this->belongsToMany('App\Model\LessonTable', 'lesson_coach', 'lesson_id', 'coach_id')
                            ->leftJoin('coach', 'coach.id', '=', 'lesson_coach.coach_id')
                            ->whereNull('lesson_coach.delete_date');
    }
    
    public function getLessonByCoachId($pschool_id, $coach_id) {
        $bind = array();
        $sql = " SELECT p.program_name, l.* FROM lesson l ";
        $sql .= " INNER JOIN program p ON l.program_id = p.id ";
        $sql .= " INNER JOIN lesson_coach r ON l.id = r.lesson_id ";
        $sql .= " WHERE l.pschool_id = ? AND r.coach_id = ? AND r.delete_date is NULL ";
        $bind[] = $pschool_id;
        $bind[] = $coach_id;
    
        return $this->fetchAll($sql, $bind);
    }

    public function getLessonList ($program_id)
    {
        $lesson_list = $this->where('program_id',$program_id)->where('delete_date')->get()->toArray();
        foreach ($lesson_list as $key => $lesson) {
            $coaches_arr = $this::find($lesson['id'])->coaches()->pluck('coach_name')->toArray();
            $lesson_list[$key]['teacher'] = implode(', ', $coaches_arr);
        }
        return $lesson_list;
    }
}
