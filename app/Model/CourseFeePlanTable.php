<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\ConstantsModel;

class CourseFeePlanTable extends DbModel
{
    /**
     * @var CourseFeePlanTable
     */
    private static $_instance = null;
    protected $table = 'course_fee_plan';

    /**
     * @return CourseFeePlanTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new CourseFeePlanTable();
        }
        return self::$_instance;
    }

    public function getCoursePrice($course_id, $student_id) {
        $sql = 'SELECT c.fee 
                FROM course_fee_plan c 
                INNER JOIN student_course_rel s ON s.plan_id = c.id 
                WHERE s.active_flag = 1 AND c.active_flag = 1 
                AND s.course_id = ? AND s.student_id = ? limit 1 ';
        $res = $this->fetchAll($sql, array($course_id, $student_id));
        return $res ? $res[0]['fee'] : null;
    }

    public function getCourseFeeForStudentType($course_id) {
        $sql = ' SELECT c.*, m.name AS student_type_name, m.is_display FROM course_fee_plan c INNER JOIN m_student_type m ON (m.pschool_id = c.pschool_id AND m.id = c.student_type_id) WHERE c.course_id = ? AND c.active_flag = 1 AND c.delete_date IS NULL AND m.delete_date IS NULL ';
        $res = $this->fetchAll($sql, array($course_id));
        return $res;
    }

    public function getCourseFeePortal($mail_message_id) {
        $sql = "
SELECT c.*
FROM course_fee_plan c
INNER JOIN student_course_rel s ON s.plan_id = c.id
INNER JOIN mail_message e ON e.relative_ID = s.course_id AND e.student_id = s.student_id
WHERE e.id = ? ";
        return $this->fetch($sql, array($mail_message_id));
    }
}
