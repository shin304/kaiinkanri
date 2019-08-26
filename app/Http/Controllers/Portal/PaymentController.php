<?php

namespace App\Http\Controllers\Portal;

use App\Common\Invoice;
use App\Http\Controllers\School\Invoice\InvoiceController;
use App\Model\InvoiceItemTable;
use Illuminate\Http\Request;
use App\Model\EntryTable;
use App\ConstantsModel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\Model\InvoiceHeaderTable;
use App\Model\MailMessageTable;
use App\Model\PschoolTable;
use App\Model\CourseFeePlanTable;
use App\Model\ProgramFeePlanTable;
use App\Model\CourseTable;
use App\Model\ProgramTable;
use App\Model\LessonTable;
use App\Common\Constants;

class PaymentController extends _BasePortalController
{
    protected static $PORTAL_VIEW = array(
        'event' => array (
            'result' => 'Portal.Event.result'),
        'program' => array(
            'result' => 'Portal.Program.result')
    );

    private $MESSAGE_TYPE; //ex: event, program
    private $ENTRY_TYPE; //ex: event, program
    private $MESSAGE_KEY;
    private $MAIL_MESSAGE;
    private $SCHOOL;
    private $DATA;
    private $FEE_PLAN;
    private $ENTRY;

    public function __construct(Request $request) {
        parent::__construct();
    }

    public function result(Request $request) {
        switch ($request->target) {
            case 'event':
                return $this->executeCourse($request);
                break;
            case 'program':
                return $this->executeCourse($request);
                break;
            case 'invoice':
                return $this->executeInvoice($request);
                break;
            default:
                break;
        }
    }

    private function saveEntryInfo(Request $request){
        $entry = EntryTable::getInstance()->getActiveRow(array(
            'code'  => $request->sendid
        ));

        if ($entry) {
            $entry_table = EntryTable::getInstance();
            try {
                $entry['payment_method'] = Constants::CRED_ZEUS; //クレジットカード決済
                $entry['status'] = 1;
                $entry_table->save($entry, true);

            // update header
            $paid_date = date('Y-m-d H:i:s');
            if(!empty($entry['invoice_id'])){
                $invoice_head = array(
                    'id' => $entry['invoice_id'],
                    'deposit_invoice_type' => Constants::$PAYMENT_TYPE['CRED_ZEUS'],
                    'workflow_status' => 31,
                    'is_recieved' => 1,
                    'paid_date' => $paid_date
                );
                InvoiceHeaderTable::getInstance()->save($invoice_head,true);
            }

            //update course and program rel table

            $invoice_items = InvoiceItemTable::getInstance()->getActiveList(array('invoice_id' => $entry['invoice_id']));
            $invoiceController = new InvoiceController();
            $invoiceController->updateStatusEntry($invoice_items, 5 , $paid_date);


            } catch (Exception $ex) {
                Log::error($ex->getMessage());
            }
        } else {
            throw new FileNotFoundException("FileNotFound - Page does not exist");
            exit;
        }
        return $entry;
    }

    protected function executeCourse(Request $request){
        $entry = $this->saveEntryInfo($request);

        $this->ENTRY = $entry;
        $this->MESSAGE_TYPE = $this->ENTRY_TYPE = ConstantsModel::$ENTRY_TYPE[$entry['entry_type']];;

        $this->MAIL_MESSAGE = MailMessageTable::getInstance()->getActiveRow(array(
            'type'          => array_search($this->MESSAGE_TYPE, ConstantsModel::$MAIL_MESSAGE_TYPE),
            'relative_ID'   => $entry['relative_id'],
            'student_id'    => $entry['student_id']
        ));
        $this->MESSAGE_KEY  = $this->MAIL_MESSAGE['message_key'];
        $this->SCHOOL = PschoolTable::getInstance()->getSchoolInfoPortal($this->MESSAGE_KEY);
        $this->FEE_PLAN = $this->getFeePlan($this->MAIL_MESSAGE);
        $data           = $this->getData($this->MAIL_MESSAGE);
        $data['fee']    = $this->FEE_PLAN['fee'];
        $this->DATA     = $data;

        return view(self::$PORTAL_VIEW[$this->MESSAGE_TYPE]['result'])
            ->with( 'pschool', $this->SCHOOL )
            ->with( 'fee_plan', $this->FEE_PLAN )
            ->with( 'data', $this->DATA )
            ->with( 'entry', $this->ENTRY );
    }

    private function getFeePlan($mail_message) {
        $fee_plan = array();
        switch ($mail_message['type']) {
            case array_search('event', ConstantsModel::$MAIL_MESSAGE_TYPE):
                $fee_plan =  CourseFeePlanTable::getInstance()->getCourseFeePortal($mail_message['id']);
                break;

            case array_search('program', ConstantsModel::$MAIL_MESSAGE_TYPE):
                $fee_plan =  ProgramFeePlanTable::getInstance()->getProgramFeePortal($mail_message['id']);
                break;
            default:
                break;
        }
        return $fee_plan;
    }

    private function getData($mail_message) {
        $data = array();
        switch ($mail_message['type']) {
            case array_search('event', ConstantsModel::$MAIL_MESSAGE_TYPE):
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
                if ($event['application_deadline'] == 1 && ($event['remain_student'] <= 0 || $event['recruitment_finish'] < date('Y-m-d H:i:s'))) {
                    $event['is_active'] = 0;
                }
                $data = $event;
                break;

            case array_search('program', ConstantsModel::$MAIL_MESSAGE_TYPE):
                $program = ProgramTable::getInstance()->getActiveRow( array(
                        'pschool_id'    => $mail_message['pschool_id'],
                        'id'            => $mail_message['relative_ID'])
                );
                // get joined student amount
                $program['student_count'] = $this->getProgramTotalJoinedStudent($mail_message['pschool_id'], $mail_message['relative_ID']);

                // get lessons of program
                $program['lessons'] = LessonTable::getInstance()->getActiveList(array('program_id'=>$program['id']),array('start_date'));

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
                if ($program['application_deadline'] == 1 && ($program['remain_student'] <= 0 || $program['recruitment_finish'] < date('Y-m-d H:i:s'))) {
                    $program['is_active'] = 0;
                }
                $data = $program;
                break;
            default:
                break;
        }
        return $data;
    }

    private function getProgramTotalJoinedStudent($pschool_id, $program_id) {
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

    protected function executeInvoice(Request $request) {
        $mail_message = MailMessageTable::getInstance()->getActiveRow(array(
            'message_key'  => $request->sendid
        ));
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoice_header = $invoiceHeaderTable->getDataParentStudent($mail_message['relative_ID']);
        $result = 0;
        if ($invoice_header) {
            $header = array(
                "id"                => $invoice_header['id'],
                "is_recieved"       => "1",
                "paid_date"         => date('Y-m-d'),
                "workflow_status"   => 31,
            );
            $saved_id = $invoiceHeaderTable->save($header, true);
            $result = $saved_id ? 1 : 0;
        }
        return view('Portal.Payment.invoice_result')
            ->with('result' , $result)
            ->with('invoice' , $invoice_header);
    }
}
