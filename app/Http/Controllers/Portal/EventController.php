<?php

namespace App\Http\Controllers\Portal;

use App\Model\CourseTable;
use App\Model\CourseFeePlanTable;

class EventController extends PortalController
{
    protected function getData($mail_message) {
        $pschool_id = $mail_message['pschool_id'];
        $event_id   = $mail_message['relative_ID'];

        $event = CourseTable::getInstance()->getActiveRow(array(
            'id'            => $event_id,
            'pschool_id'    => $pschool_id,
        ));

        // teachers's name
        $coaches = CourseTable::find($event['id'])->coaches;
        $coach_name = array();
        foreach( $coaches as $coach ) {
            $coach_name[] = $coach->coach_name;
        }
        $teacher_name = implode(', ' , $coach_name);
        $event['teacher_name'] = $teacher_name;

        // joined student amount
        $event['student_count'] = CourseTable::getInstance()->getTotalJoinedStudent( $pschool_id, $event['id']);

        // get total chair
        $capacity           = $event['non_member_capacity'] + $event['member_capacity'];
        $event['capacity']  = $capacity ? $capacity : 0;

        // get remain chair
        $remain_student              = $event['capacity'] - $event['student_count'];
        $event['remain_student']     = $remain_student ? $remain_student : 0;

        // get all person_in_charge
        $person_in_charge           = $event['person_in_charge1'] ? $event['person_in_charge1'] . '、' . $event['person_in_charge2'] : $event['person_in_charge2'];
        $event['person_in_charge']  = $person_in_charge;

        // check is deadline or not
        $event['is_active'] = 1;
        if ($event['application_deadline'] == 1 && ($event['remain_student'] <= 0 || (isset($event['recruitment_finish']) && $event['recruitment_finish'] < date('Y-m-d H:i:s')))) {
            $event['is_active'] = 0;
        }

        return $event;
    }

    protected function getFeePlan($mail_message) {
        return CourseFeePlanTable::getInstance()->getCourseFeePortal($mail_message['id']);
    }

}
