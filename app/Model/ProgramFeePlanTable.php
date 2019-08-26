<?php

namespace App\Model;


class ProgramFeePlanTable extends DbModel
{
    /**
     * @var ProgramFeePlanTable
     */
    private static $_instance = null;
    protected $table = 'program_fee_plan';

    /**
     * @return ProgramFeePlanTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new ProgramFeePlanTable();
        }
        return self::$_instance;
    }

    public function getProgramFeeForStudentType($program_id) {
        $sql = ' SELECT c.*, m.name AS student_type_name, m.is_display FROM program_fee_plan c INNER JOIN m_student_type m ON (m.pschool_id = c.pschool_id AND m.id = c.student_type_id) WHERE c.program_id = ? AND c.active_flag = 1 AND c.delete_date IS NULL AND m.delete_date IS NULL ';
        $res = $this->fetchAll($sql, array($program_id));
        return $res;
    }

    public function getProgramFeePortal($mail_message_id) {
        $sql = "
SELECT c.*
FROM program_fee_plan c
INNER JOIN student_program s ON s.plan_id = c.id
INNER JOIN mail_message e ON e.relative_ID = s.program_id AND e.student_id = s.student_id
WHERE e.id = ? ";
        return $this->fetch($sql, array($mail_message_id));
    }
}
