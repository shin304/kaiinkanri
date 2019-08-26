<?php

namespace App\Http\Controllers\Portal;

use App\Model\ProgramTable;
use App\Model\ProgramFeePlanTable;
use App\Model\LessonTable;
use App\Model\EntryTable;
use App\ConstantsModel;

class ProgramController extends PortalController
{
    protected function getData($mail_message) {
        $program = ProgramTable::getInstance()->getActiveRow( array(
            'pschool_id'    => $mail_message['pschool_id'],
            'id'            => $mail_message['relative_ID'])
        );
        // get joined student amount
        $program['student_count'] = $this->getTotalJoinedStudent($mail_message['pschool_id'], $mail_message['relative_ID']);

        // get lessons of program
        $program['lessons'] = LessonTable::getInstance()->getLessonList($program['id']);

        // get total chair
        $capacity               = $program['non_member_capacity'] + $program['member_capacity'];
        $program['capacity']    = $capacity ? $capacity : 0;

        // get remain chair
        $remain_student             = $program['capacity'] - $program['student_count'];
        $program['remain_student']  = $remain_student ? $remain_student : 0;

        // get all person_in_charge
        $person_in_charge               = $program['person_in_charge1'] ? $program['person_in_charge1'] . '、' . $program['person_in_charge2'] : $program['person_in_charge2'];
        $program['person_in_charge']    = $person_in_charge;

        // check is deadline or not
        $program['is_active'] = 1;
        if ($program['application_deadline'] == 1 && ($program['remain_student'] <= 0 || (isset($program['recruitment_finish']) && $program['recruitment_finish'] < date('Y-m-d H:i:s')))) {
            $program['is_active'] = 0;
        }
        return $program;
    }

    protected function getFeePlan($mail_message) {
        return ProgramFeePlanTable::getInstance()->getProgramFeePortal($mail_message['id']);
    }

    private function getTotalJoinedStudent($pschool_id, $program_id) {
        // 生徒数
        $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( $pschool_id, array (
            'entry_type'    => array_search('program', ConstantsModel::$ENTRY_TYPE),
            'relative_id'   => $program_id,
            'enter'         => 1
        ) );

        $student_count = 0;
        foreach ($entries as $entry) {
            if ($entry['total_member']) {
                $student_count +=$entry['total_member'];
            }
        }
        return $student_count;
    }

}
