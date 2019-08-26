<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 8/23/2017
 * Time: 11:42 AM
 */

namespace App\Module\Invoice;

use App\Model\ClassTable;
use App\Model\EntryTable;
use App\Model\InvoiceAdjustNameTable;
use App\Model\InvoiceDebitTable;
use App\Model\InvoiceHeaderTable;
use App\Model\PschoolTable;
use App\Model\InvoiceItemTable;
use App\Model\PaymentMethodTable;
use App\Model\ClosingDayTable;
use App\Common\Constants;
use App\Model\StudentTable;


class BaseInvoice implements PaymentInterface
{

    const DEFAULT_KOZA_CLOSING_DAY = 27;
    public function __construct()
    {
    }

    function createInvoice($request = array(), $payment_type, $is_batch = false)
    {
        //reformat invoice_year_month for not getting wrong format (2017-1, 2018-2, etc.)
        if(isset($request['invoice_year_month'])){
            $temp = date("Y-m", strtotime ($request['invoice_year_month']));
            $request['invoice_year_month'] = $temp;
        }

        // get due_date of cash method
        $payment_date = $this->getDueDateOfPaymentMethod($request, $payment_type);
        $pschool = PschoolTable::getInstance()->load($request['id']);
        $class_invoice_monthly = array();
        $class_invoice_schedule = array();
        $event_invoice = array();
        $program_invoice = array();

        //check is from portal or not
        $is_portal = false;
        if(isset($request['is_portal'])){
            $is_portal = $request['is_portal'];
        }

        // get objects
        $is_nyukin = 0;

        if(!$is_portal){
            // get class objects
            $class_invoice_monthly = $this->getInvoiceObjectMonthlyClass($request, $payment_type);
            $class_invoice_schedule = $this->getInvoiceObjectScheduleClass($request, $payment_type);
            $event_invoice = $this->getInvoiceObjectEvent($request, $payment_type );
            $program_invoice = $this->getInvoiceObjectProgram($request, $payment_type);
        }else{
            // event & program objects

            $payment_type = Constants::$PAYMENT_TYPE[$request['payment_method']];
//            if($payment_type == Constants::$PAYMENT_TYPE['CASH'] || $payment_type == Constants::$PAYMENT_TYPE['CRED_ZEUS']){
            $is_batch = true;
//            }

            if(isset($request['entry_type']) &&  $request['entry_type'] == 2){
                $event_invoice = $this->getInvoiceObjectEvent($request, $payment_type );
                $is_nyukin = 1;
            }elseif(isset($request['entry_type']) &&  $request['entry_type'] == 3){
                $program_invoice = $this->getInvoiceObjectProgram($request, $payment_type);
                $is_nyukin = 2;
            }
        }

        $object_invoice = array_merge($class_invoice_monthly, $class_invoice_schedule, $event_invoice, $program_invoice);

        //dd($object_invoice);
        if(!empty($request['parent_id']) && empty($object_invoice) && $request['invoice_type']==$payment_type){
            $object_invoice[] = $this->createEmptyParentRecord($request) ;
        }

        // create invoice logic
        $this->processInvoiceList($pschool, $object_invoice, $payment_date, $is_batch, $is_nyukin, $request);
    }

    function processInvoiceList($pschool, $list_parent, $due_date, $is_batch = false, $is_nyukin = 0, $request = array()) {

        foreach ($list_parent as $k => $v) {

            // default header_flag = 1 and header_id =null, set due_date for each record ;
            $list_parent[$k]['header_flag'] = 1;
            $list_parent[$k]['header_id'] = null;
            $list_parent[$k]['due_date'] = $due_date;

            $list_parent[$k]['is_nyukin'] = $is_nyukin;
            $v['is_nyukin'] = $is_nyukin;
            $v['pschool_id'] = $pschool['id'];

            //generate item name for japanese
            $split = explode('-', $v['invoice_year_month']);
            $item_name = $split[0] . "年" . $split[1] . "月分 ";
            if (isset($v['class_name'])) {
                $item_name .= $v['class_name'] . " " . $v['student_name'];
            } elseif (isset($v['course_title'])) {
                $item_name .= $v['course_title'] . " " . $v['student_name'];
            } elseif (isset($v['program_name'])) {
                $item_name .= $v['program_name'] . " " . $v['student_name'];
            }

            $list_parent[$k]['item_name'] = $item_name;
            $v['item_name'] = $item_name;

            //check this parent_id is exist or not
            $header = $this->isInvoiceHeaderExist($v);

            if ($header) {
                // mean this parent_id is already exists in invoice_header so get this header_id
                $list_parent[$k]['header_id'] = $header['id'];

                //update entry table
                if(isset($request['entry_id'])){
                    EntryTable::getInstance()->save(
                            array('id'=>$request['entry_id'],'invoice_id'=>$list_parent[$k]['header_id'])
                            ,true);
                }

                //
                $v['invoice_id'] = $header['id'];

                // check item is exists or not
                if ($this->isInvoiceItemExist($v)) {
                    // item existed => unset from list
                    unset($list_parent[$k]);
                    continue;
                } else {
                    // item is not exist => set header_flag =0 : save item, not the header
                    $list_parent[$k]['header_flag'] = 0;
                }
            }

            if ($list_parent[$k]['header_flag'] == 1) {
                $list_parent[$k]['header_id'] = $this->saveInvoiceHeader($pschool, $list_parent[$k], $is_batch);
                //update entry table
                if(isset($request['entry_id'])){
                    EntryTable::getInstance()->save(
                            array('id'=>$request['entry_id'],'invoice_id'=>$list_parent[$k]['header_id'])
                            ,true);
                }
            }

            // create debit record base on parent_id and invoice_year_month
            $this->generateDebitInvoice($list_parent[$k]['header_id'],$v['parent_id'],$v['invoice_year_month']);

            //save item to invoice_item

            if(!empty($list_parent[$k]['amount'])){
                $item_id = $this->saveInvoiceItem($pschool, $list_parent[$k], $is_batch);
            }

            // get list adjust parent
            // check if header is exists => adjust for parent exists so do not insert
            // do not get if this is nyukin record
            if (!$header && $is_nyukin == 0) {
                $this->createParentAdjustItem($list_parent[$k]);
            }

            // get list adjust class
            if (isset($v['class_id']) && isset($v['class_name'])) {
                $this->createClassAdjustItem($list_parent[$k]);
            }

//            //TODO get list adjust event
//            if(isset($v['course_id'])&& isset($v['course_title'])){
//                $event_adjust_item = $this->createEventAdjustItem($list_parent[$k]);
//            }
//
//            //TODO get list adjust program
//            if(isset($v['program_id'])&& isset($v['program_name'])){
//                $program_adjust_item = $this->createProgramAdjustItem($list_parent[$k]);
//            }

            // update total amout of invoice header
            $this->updateAmountInvoiceHeader($list_parent[$k]['header_id']);
        }
        if(!empty($list_parent) && ! empty($list_parent[0]['header_id']) && !empty($request['parent_id']) && empty($request['is_portal']) && !$is_batch){

            session()->forget('created_ids');

            session(['created_ids' => $list_parent[0]['header_id']]);

            return $list_parent[0]['header_id'];
        }
    }

    function getInvoiceObjectMonthlyClass($request = array(), $payment_type)
    {

        $invoice_header_table = InvoiceHeaderTable::getInstance();
        $invoice_year_month = $request['invoice_year_month'];
        $pschool_id = $request['id'];
        $invoice_type = $payment_type;

        $bind = array(
            $invoice_year_month,
            $invoice_type,
            $invoice_year_month,
            $invoice_year_month,
            $pschool_id
        );
        $sql_class = "SELECT ? as invoice_year_month, class.pschool_id as pschool_id, parent.id as parent_id , parent.parent_name, parent.mail_infomation, s_class.payment_method as invoice_type,
                       s_class.student_id as student_id ,student.student_name, student.student_no, class.id as class_id, class.class_name, s_class.payment_method,
                       cfp.fee_plan_name, mst.name as student_type,
                       IF (student.student_category = 2 AND cfp.payment_unit = 1,cfp.fee * student.total_member,cfp.fee ) as amount
                FROM class 
                LEFT JOIN student_class s_class ON class.id = s_class.class_id
                LEFT JOIN student ON student.id = s_class.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                LEFT JOIN class_fee_plan cfp ON cfp.class_id = class.id AND s_class.plan_id = cfp.id
                LEFT JOIN m_student_type mst ON student.m_student_type_id = mst.id
                WHERE class.delete_date IS NULL 
                AND s_class.delete_date IS NULL 
                AND student.active_flag = 1 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL
                AND s_class.payment_method = ?
                AND (class.start_date IS NOT NULL AND SUBSTRING(class.start_date, 1, 7) <= ? )
                AND (class.close_date IS NULL OR SUBSTRING(class.close_date, 1, 7) >= ? ) 
                AND s_class.number_of_payment = 99
                AND class.pschool_id = ?";

        //add for single creation base on id
        if(!empty($request->parent_id)){
            $bind[] = $request->parent_id;
            $sql_class.= " AND parent.id = ? ";
        }

        $class_invoice = $invoice_header_table->fetchAll($sql_class, $bind);

        $class_invoice = $this->filterClassInvoiceByMethod($class_invoice);

        return $class_invoice;
    }

    function getInvoiceObjectScheduleClass($request = array(), $payment_type)
    {
        // TODO: Implement getInvoiceObjectScheduleClass() method.
        $invoice_header_table = InvoiceHeaderTable::getInstance();
        $invoice_year_month = $request['invoice_year_month'];
        $pschool_id = $request['id'];
        $schedule_month = date("Y-m", strtotime($invoice_year_month . "-01" . " -1 month"));

        $first = date('m-d', strtotime($schedule_month . "-01"));
        $last = date('m-t', strtotime($schedule_month . "-01"));

        $bind = array(
            $invoice_year_month,
            $schedule_month,
            $payment_type,
            $schedule_month,
            $schedule_month,
            $pschool_id,
            $first,
            $last,

        );
        $sql = "SELECT ? as invoice_year_month,class.pschool_id , parent.id as parent_id , parent.parent_name,parent.mail_infomation, s_class.payment_method as invoice_type,
                       s_class.student_id as student_id ,student.student_name, student.student_no, class.id as class_id, class.class_name, s_class.payment_method,
                       cps.id as schedule_id, cps.schedule_date, mst.name as student_type, 
                       IF (student.student_category = 2 AND cfp.payment_unit = 1,cps.schedule_fee * student.total_member,cps.schedule_fee ) as amount, 
                       ? as schedule_month
                FROM class 
                LEFT JOIN student_class s_class ON class.id = s_class.class_id
                LEFT JOIN class_payment_schedule cps ON cps.student_class_id = s_class.id
                LEFT JOIN student ON student.id = s_class.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                LEFT JOIN class_fee_plan cfp ON cfp.class_id = class.id AND s_class.plan_id = cfp.id
                LEFT JOIN m_student_type mst ON student.m_student_type_id = mst.id
                WHERE class.delete_date IS NULL 
                AND s_class.delete_date IS NULL 
                AND student.active_flag = 1 
                AND (student.resign_date IS NULL OR student.resign_date >= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date)) 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL
                AND s_class.payment_method = ?
                AND (class.start_date IS NOT NULL AND SUBSTRING(class.start_date, 1, 7) <= ? )
                AND (class.close_date IS NULL OR SUBSTRING(class.close_date, 1, 7) >= ? ) 
                AND (SUBSTRING(class.start_date, 6, 5) <= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date))
                AND (class.close_date IS NULL OR class.close_date >= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date))
                AND class.pschool_id = ?
                AND s_class.number_of_payment != 99
                AND (s_class.start_date IS NOT NULL AND s_class.start_date <= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date)) 
                AND (s_class.end_date IS NULL OR s_class.end_date >= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date)) 
                AND cps.schedule_date IS NOT NULL 
                AND cps.schedule_fee IS NOT NULL
                AND cps.schedule_date>= ? AND cps.schedule_date<= ? ";

        //add for single creation base on id
        if(!empty($request->parent_id)){
            $bind[] = $request->parent_id;
            $sql.= " AND parent.id = ? ";
        }

        $res = $invoice_header_table->fetchAll($sql, $bind);

        $res = $this->filterClassInvoiceByMethod($res);

        return $res;
    }

    function getInvoiceObjectEvent($request = array(), $payment_type)
    {
        // TODO: Implement getInvoiceObjectEvent() method.
        $invoice_header_table = InvoiceHeaderTable::getInstance();
        $invoice_year_month = $request['invoice_year_month'];
        $pschool_id = $request['id'];
        if(isset($request['is_portal'])){
            $is_merge_invoice = 0;
        }else{
            $is_merge_invoice = 1;
        }
        //

        $bind = array(
            $invoice_year_month,
            $payment_type,
            $pschool_id,
            $is_merge_invoice
        );

        $sql_event = "SELECT ? as invoice_year_month, course.pschool_id as pschool_id, parent.id as parent_id , parent.parent_name, parent.mail_infomation, ? as invoice_type,
                      scr.student_id as student_id ,student.student_name, student.student_no, course.id as course_id, course.course_title , parent.invoice_type as payment_method,
                      cfp.fee_plan_name , SUBSTRING(course.start_date, 1, 7) as start_month, mst.name as student_type, 
                      IF (student.student_category = 2 AND cfp.payment_unit = 1,cfp.fee * e.total_member, cfp.fee ) as amount
                FROM course 
                LEFT JOIN entry e ON (e.relative_id = course.id AND e.entry_type = 2)
                LEFT JOIN student_course_rel scr ON (course.id = scr.course_id AND scr.student_id = e.student_id)  
                LEFT JOIN course_fee_plan cfp ON scr.plan_id = cfp.id
                LEFT JOIN student ON student.id = scr.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                LEFT JOIN m_student_type mst ON student.m_student_type_id = mst.id
                WHERE course.delete_date IS NULL 
                AND course.active_flag = 1
                AND e.payment_method IS NOT NULL
                AND e.enter = 1
                AND scr.delete_date IS NULL 
                AND student.active_flag = 1 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL 
                AND course.pschool_id = ?
                AND e.is_merge_invoice = ? ";

        // create single invoice for event , is_nyukin = 1
        if(isset($request['relative_id']) && isset($request['entry_type']) && $request['entry_type'] == 2 ){
            $sql_event .= " AND course.id = ? ";
            $bind[] = $request['relative_id'];
        }else{
            $sql_event .= " AND (course.start_date IS NOT NULL AND SUBSTRING(course.start_date, 1, 7) = ? )";
            $bind[] = $invoice_year_month;
        }

        //add for single creation base on id
        if(!empty($request->parent_id)){
            $bind[] = $request->parent_id;
            $sql_event.= " AND parent.id = ? ";
        }

        $event_invoice = $invoice_header_table->fetchAll($sql_event, $bind);

        return $event_invoice;
    }

    function getInvoiceObjectProgram($request = array(), $payment_type)
    {
        // TODO: Implement getInvoiceObjectProgram() method.
        $invoice_header_table = InvoiceHeaderTable::getInstance();
        $invoice_year_month = $request['invoice_year_month'];
        $pschool_id = $request['id'];
        if(isset($request['is_portal'])){
            $is_merge_invoice = 0;
        }else{
            $is_merge_invoice = 1;
        }
        //
        $bind = array(
            $invoice_year_month,
            $payment_type,
            $pschool_id,
            $is_merge_invoice
        );
        $sql_program = "SELECT ? as invoice_year_month, program.pschool_id as pschool_id, parent.id as parent_id , parent.parent_name, parent.mail_infomation, ? as invoice_type,
                      sp.student_id as student_id ,student.student_name, student.student_no, program.id as program_id, program.program_name , parent.invoice_type as payment_method,
                      pfp.fee_plan_name , SUBSTRING(program.start_date, 1, 7) as start_month, mst.name as student_type, 
                      IF (student.student_category = 2 AND pfp.payment_unit = 1,pfp.fee * e.total_member, pfp.fee ) as amount
                FROM program 
                LEFT JOIN entry e ON (e.relative_id = program.id AND e.entry_type = 3)
                LEFT JOIN student_program sp ON program.id = sp.program_id
                LEFT JOIN program_fee_plan pfp ON sp.plan_id = pfp.id
                LEFT JOIN student ON student.id = sp.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                LEFT JOIN m_student_type mst ON student.m_student_type_id = mst.id
                WHERE program.delete_date IS NULL 
                AND program.active_flag = 1
                AND sp.active_flag = 1
                AND sp.delete_date IS NULL 
                AND student.active_flag = 1 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL
                AND program.pschool_id = ?
                AND e.is_merge_invoice = ? 
                AND e.payment_method IS NOT NULL
                AND e.enter = 1 ";

        // create single invoice for event , is_nyukin = 1
        if(isset($request['relative_id']) && isset($request['entry_type']) && $request['entry_type'] == 3 ){
            $sql_program .= " AND program.id = ? ";
            $bind[] = $request['relative_id'];
        }else{
            $sql_program .= " AND (program.start_date IS NOT NULL AND SUBSTRING(program.start_date, 1, 7) = ? )";
            $bind[] = $invoice_year_month;
        }

        //add for single creation base on id
        if(!empty($request->parent_id)){
            $bind[] = $request->parent_id;
            $sql_program.= " AND parent.id = ? ";
        }

        $program_invoice = $invoice_header_table->fetchAll($sql_program, $bind);

        return $program_invoice;
    }

    function getDueDateOfPaymentMethod($request = array(), $payment_type)
    {

        switch ($payment_type) {
            case 1: // Cash
                return $this->getSchoolDueDate($request);

            case 2: // Ricoh_transfer
                return $this->getRICOHDueDate($request);

            case 3:
                return $this->getSchoolDueDate($request);

            case 4:
                return $this->getSchoolDueDate($request);

            case 5:
                return $this->getSchoolDueDate($request);

            case 6:
                return $this->getSchoolDueDate($request);

            default:
                return $this->getSchoolDueDate($request);
        }

    }

    function isInvoiceHeaderExist($header)
    {
        // Implement isInvoiceHeaderExist() method.
        $header_cond = array(
            'pschool_id' => $header['pschool_id'],
            'parent_id' => $header["parent_id"],
            'invoice_year_month' => $header['invoice_year_month'],
            'is_nyukin' => $header['is_nyukin']
        );

        // if class object -> add check on invoice_type
        if($header['is_nyukin'] == 0){
            $header_cond["invoice_type"] = $header['invoice_type'];
        }

        $header_exist = InvoiceHeaderTable::getInstance()->getActiveList($header_cond);

        if (!empty($header_exist)) {
            // case of  nyukin exist -> update invoice type
            if($header['is_nyukin']!= 0){

                foreach ($header_exist as $key => $head){
                    $exist_item = array();
                    if($header['is_nyukin']==1){
                        $exist_item = InvoiceItemTable::getInstance()->getActiveList(array(
                                'invoice_id'=>$head['id'],
                                'course_id'=>$header['course_id']));
                    }else{
                        $exist_item = InvoiceItemTable::getInstance()->getActiveList(array(
                                'invoice_id'=>$head['id'],
                                'program_id'=>$header['program_id']));
                    }
                    if(!empty($exist_item)){
                        $head['invoice_type'] = $header['invoice_type'];
                        $head['update_date'] = date('Y-m-d H:i:s');
                        InvoiceHeaderTable::getInstance()->save($head);
                        return $head;
                    }
                }
                return false;
            }


            //return existed record
            return $header_exist[0];
        } else {
            return false;
        }
    }

    function saveInvoiceHeader($pschool, $header, $is_batch = false)
    {
        // Implement saveInvoiceHeader() method.
        $row = array(
            "pschool_id" => $pschool['id'],
            "parent_id" => $header["parent_id"],
            "discount_price" => "0",
            "is_established" => "0",
            "mail_announce" => empty($header["mail_infomation"]) ? "0" : "1",
            "is_requested" => "0",
            "is_recieved" => "0",
            "amount_display_type" => $pschool['amount_display_type'],
            "sales_tax_rate" => $pschool['sales_tax_rate'],
            "active_flag" => 1,
            "invoice_type" => $header['invoice_type'],
            "is_nyukin" => $header['is_nyukin'],
            "invoice_year_month" => $header['invoice_year_month'],
            "due_date" => $header['due_date'],
            "register_admin" => $pschool['id']
        );

        // Set workflow status to confirmed when create invoice from portal
        $is_nyukin = $header['is_nyukin'];
        if ($is_nyukin != 0) {
            $row["workflow_status"] = 11;
        }

        $header_id = InvoiceHeaderTable::getInstance()->save($row, $is_batch);

        return $header_id;
    }

    function isInvoiceItemExist($item)
    {
        // Implement isInvoiceItemExist() method.
        $item_cond = array(
            'pschool_id' => $item['pschool_id'],
            'parent_id' => $item["parent_id"],
            //'student_id' => $item['student_id'],
            'item_name' => $item['item_name'],
            'invoice_id' => $item['invoice_id']
        );
        $invoice_item_table = InvoiceItemTable::getInstance();
        $item_exist = $invoice_item_table->getActiveList($item_cond);
        if (!empty($item_exist)) {
            return true;
        } else {
            return false;
        }
    }

    function saveInvoiceItem($pschool, $item, $is_batch = false)
    {
        // Implement saveInvoiceItem() method.
        $invoice_item_table = InvoiceItemTable::getInstance();

        $monthly_billing = 0;
        if (isset($item['schedule_date'])) {
            $monthly_billing = 0; // 0 means this have set schedule_date
        } else {
            $monthly_billing = 1; // 1 means payment monthly
        }

        $row = array(
            'pschool_id' => $pschool['id'],
            'invoice_id' => $item['header_id'],
            'parent_id' => $item['parent_id'],
            'student_id' => $item['student_id'],
            'item_name' => $item['item_name'],
            'unit_price' => $item['amount'],
            'monthly_billing' => $monthly_billing,
            'payment_method' => $item['invoice_type'],
            'due_date' => $item['due_date'],
            'active_flag' => 1,
            'except_flag' => 0,
            'register_date' => date('Y-m-d H:i:s'),
            'register_admin' => $pschool['id']
        );
        //
        if (isset($item['class_id'])) {
            $row['class_id'] = $item['class_id'];
        } elseif (isset($item['course_id'])) {
            $row['course_id'] = $item['course_id'];
        } elseif (isset($item['program_id'])) {
            $row['program_id'] = $item['program_id'];
        }

        $item_id = $invoice_item_table->save($row, $is_batch);
        return $item_id;
    }

    /*
     *
     * when create new record and old record still not paid
     * ->create record invoice_debit
     */
    private function generateDebitInvoice($header_id , $parent_id , $invoice_year_month){

        $bind[] = $parent_id;
        $bind[] = $invoice_year_month;

        $sql = "SELECT id, invoice_year_month, amount, due_date 
                FROM invoice_header 
                WHERE parent_id = ? 
                AND invoice_year_month <> ? 
                AND workflow_status <> 31
                AND delete_date IS NULL 
                ";

        $debit_data = InvoiceHeaderTable::getInstance()->fetchAll($sql,$bind);

        foreach ($debit_data  as $k => $debit){

            $check = array();
            $check[] = $header_id;
            $check[] = $debit['id'];

            $check_sql = "SELECT id 
                        FROM invoice_debit 
                        WHERE invoice_header_id = ?
                        AND delete_date IS NULL 
                        AND invoice_debit_id = ?
                        ";
            $isExist = InvoiceDebitTable::getInstance()->fetchAll($check_sql,$check);
            if(empty($isExist)){
                $item = array();
                $item['invoice_header_id'] = $header_id;
                $item['invoice_year_month'] = $invoice_year_month;
                $item['invoice_debit_id'] = $debit['id'];
                $item['debit_year_month'] = $debit['invoice_year_month'];
                $item['amount'] = !empty($debit['amount']) ? $debit['amount'] : 0;
                $item['due_date'] = $debit['due_date'];
                $item['status'] = 0 ;
                InvoiceDebitTable::getInstance()->insertRow($item);
            }
        }
    }

    function createParentAdjustItem($parent_data)
    {
        // Implement createParentAdjustItem() method.
        $bind = array();
        $bind[] = $parent_data['pschool_id'];
        $bind[] = $parent_data['parent_id'];
        $bind[] = substr($parent_data['invoice_year_month'], 5, 2);

        $sql = " SELECT RP.*, IAN.name, parent.invoice_type ";
        $sql .= " FROM routine_payment AS RP ";
        $sql .= " INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= " ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= " INNER JOIN parent ON parent.id = RP.data_id ";
        $sql .= " WHERE RP.delete_date IS NULL ";
        $sql .= " AND RP.active_flag = 1 ";
        $sql .= " AND RP.pschool_id = ? ";
        $sql .= " AND RP.data_div = 3 ";
        $sql .= " AND RP.data_id = ? ";
        $sql .= " AND (RP.month = ? OR RP.month = 99) ";

        $adjust = InvoiceHeaderTable::getInstance()->fetchAll($sql, $bind);
        if (count($adjust) > 0) {
            foreach ($adjust as $item) {
                $row = array();
                $row['pschool_id'] = $parent_data['pschool_id'];
                $row['invoice_id'] = $parent_data['header_id'];
                $row['parent_id'] = $parent_data['parent_id'];
                $row['class_id'] = null;
                $row['course_id'] = null;
                $row['item_name'] = $item['name'];
                $row['unit_price'] = $item['adjust_fee'];
                $row['active_flag'] = 1;
                $row['due_date'] = '2017-09-05';
                $row['payment_method'] = $parent_data['invoice_type'];
                $row['register_date'] = date('Y-m-d H:i:s');
                $row['register_admin'] = $parent_data['pschool_id'];
                $row['invoice_adjust_name_id'] = $item['invoice_adjust_name_id'];
                $row['program_id'] = null;

                InvoiceItemTable::getInstance()->insertRow($row);
            }
        }
    }

    function createClassAdjustItem($parent_data)
    {
        // TODO: Implement createClassAdjustItem() method.
        $year_month = $parent_data['invoice_year_month'];
        $month = substr($year_month, 5, 2);

        $bind = array();
        $bind [] = $month;
        $bind [] = $year_month;
        $bind [] = $year_month;
        $bind [] = $parent_data['parent_id'];
        $bind [] = $parent_data['pschool_id'];

        $sql = " SELECT RPIAN.*, SCCSP.id AS student_id, SCCSP.student_name, SCCSP.class_name, SCCSP.class_id, SCCSP.payment_method ";
        $sql .= " FROM ";
        $sql .= " ( ";
        $sql .= "     SELECT RP.* , IAN.name ";
        $sql .= "     FROM routine_payment AS RP ";
        $sql .= "     INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= "     ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= "     WHERE RP.delete_date IS NULL ";
        $sql .= "     AND RP.data_div = 1 ";
        $sql .= "     AND RP.active_flag = 1 ";
        $sql .= "     AND (RP.month = ? OR RP.month = 99) ";
        $sql .= " ) AS RPIAN ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        $sql .= "     SELECT SCC.class_id, SP.id, SP.student_name, SCC.class_name ,SCC.payment_method";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, SC.class_id, C.class_name,SC.payment_method ";
        $sql .= "         FROM student_class AS SC ";
        $sql .= "         INNER JOIN class AS C ";
        $sql .= "         ON SC.class_id = C.id ";
        $sql .= "         WHERE SC.delete_date IS NULL ";
        $sql .= "         AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        $sql .= "     ) AS SCC ";
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT S.id, S.student_name ";
        $sql .= "         FROM student AS S ";
        $sql .= "         INNER JOIN parent AS P ";
        $sql .= "         ON S.parent_id = P.id ";
        $sql .= "         WHERE S.delete_date IS NULL ";
        $sql .= "         AND S.parent_id = ? ";
        $sql .= "         AND S.pschool_id = ? ";
        $sql .= "     ) AS SP ";
        $sql .= "     ON SCC.student_id = SP.id ";
        $sql .= " ) AS SCCSP ";
        $sql .= " ON RPIAN.data_id = SCCSP.class_id ";

        $adjust = InvoiceAdjustNameTable::getInstance()->fetchAll($sql, $bind);
        if (count($adjust) > 0) {
            foreach ($adjust as $item) {

                // if adjust is exists then pass it
                $item_name = $item['class_name'] . " " . $item['student_name'] . " " . "(" . $item['name'] . ")";
                $parent_data['item_name'] = $item_name;
                $parent_data['amount'] = $item['adjust_fee'];
                $parent_data['invoice_adjust_name_id'] = $item['invoice_adjust_name_id'];
                $parent_data['invoice_id'] = $parent_data['header_id'];

                if ($this->isInvoiceItemExist($parent_data)) {
                    continue;
                }
                // end check -> do insert
                $row = array();
                $row['pschool_id'] = $parent_data['pschool_id'];
                $row['invoice_id'] = $parent_data['header_id'];
                $row['parent_id'] = $parent_data['parent_id'];
                $row['student_id'] = $parent_data['student_id'];
                $row['class_id'] = $parent_data['class_id'];
                $row['course_id'] = null;
                $row['item_name'] = $parent_data['item_name'];
                $row['unit_price'] = $parent_data['amount'];
                $row['active_flag'] = 1;
                $row['register_date'] = date('Y-m-d H:i:s');
                $row['invoice_adjust_name_id'] = $parent_data['invoice_adjust_name_id'];
                $row['program_id'] = null;
                $row['monthly_billing'] = 0;
                $row['payment_method'] = $parent_data['invoice_type'];
                $row['due_date'] = $parent_data['due_date'];

                InvoiceItemTable::getInstance()->insertRow($row);
            }
        }

    }

    function createEventAdjustItem($parent_data)
    {
        // TODO: Implement createEventAdjustItem() method.

    }

    function createProgramAdjustItem($parent_data)
    {
        // TODO: Implement createProgramAdjustItem() method.
    }

    function updateAmountInvoiceHeader($header_id)
    {
        //  Implement updateAmountInvoiceHeader() method.
        $sql = "UPDATE invoice_header AS header " .
            "INNER JOIN (" .
            "SELECT item.invoice_id, sum(item.unit_price) AS sum_unit_price " .
            "FROM invoice_item AS item " .
            "WHERE item.invoice_id = ? " .
            "GROUP BY item.invoice_id" .
            ") AS item " .
            "ON (header.id = item.invoice_id) " .
            "SET amount = item.sum_unit_price " .
            "WHERE header.id = ?";
        $bind = array(
            $header_id,
            $header_id,
        );
        InvoiceHeaderTable::getInstance()->execute($sql, $bind);
    }

    function getSchoolDueDate($request = array())
    {
        $pschool = PschoolTable::getInstance()->load($request['id']);
        $payment_date = $pschool['payment_date'];
        $payment_month = $pschool['payment_month'];

        //get month base on setting payment_month
        $plus_month = -1 + $payment_month;
        if($plus_month > -1){
            $plus_month = "+" . $plus_month;
        }

        if ($payment_date == 99) { // 99: The last day of the month (28 or 30 or 31)
            $payment_date = date('Y-m-t', strtotime($request['invoice_year_month'] . '-01 ' . $plus_month .' month'));
        } else {
            $payment_date = date('Y-m-d', strtotime($request['invoice_year_month'] . '-' . $payment_date . ' ' . $plus_month .' month'));
        }
        return $payment_date;

    }

    function getRICOHDueDate($request = array())
    {
        // TODO: Implement getDueDateOfPaymentMethod() method.
        // get withdrawal date of payment_method and payment_agency
        $bind = array(
                Constants::TRAN_RICOH,
                'withdrawal_date',
                $request['id']
        );
        $sql = "SELECT pms.default_value, pmd.item_value, pm.payment_agency_id
              FROM payment_method pm
              LEFT JOIN payment_method_setting pms ON pm.id = pms.payment_method_id AND pm.payment_agency_id = pms.payment_agency_id
              LEFT JOIN payment_method_data  pmd ON pmd.payment_method_setting_id = pms.id
              WHERE pm.code = ?
              AND pms.item_name = ? 
              AND pmd.pschool_id = ?
              AND pm.delete_date IS NULL 
              AND pms.delete_date IS NULL 
              ";
        $res = PaymentMethodTable::getInstance()->fetch($sql, $bind);
        if(empty($res)){
            // avoid error when not setting withdrawal_date for method => return default 27
            $payment_agency_id = 1 ;
            $withdrawal_day = 27;
        }else{
            $default_value = explode(";", $res['default_value']);
            $value = $default_value[$res['item_value'] - 1];
            $payment_date = explode(":", $value);
            $withdrawal_day = $payment_date[1];
            $payment_agency_id = $res['payment_agency_id'];
        }

        // get info from closing_day_table with $withdrawal_day
        $bind2 = array(
                date('Y-m', strtotime($request['invoice_year_month']. "-01". "-1 month")), // duedate month  = transfer_ month -1
                $withdrawal_day,
                $payment_agency_id
        );
        $date_sql = "SELECT transfer_month , transfer_date
                      FROM closing_day WHERE transfer_month = ? 
                      AND transfer_day = ?
                      AND payment_agency_id = ?
                      ORDER BY deadline ASC LIMIT 1 ";
        $closeDay = ClosingDayTable::getInstance()->fetch($date_sql, $bind2);

        //TODO when do not have proper data on closing_day table -> get default day of month
        if(empty($closeDay)){
            return date('Y-m-d',strtotime($request['invoice_year_month']. "-".self::DEFAULT_KOZA_CLOSING_DAY. "-1 month"));
        }
        return $closeDay['transfer_date'];
    }

    function getListParentSelect($request = array(), $payment_type){
        $list_parent = array();
        // get objects
        $class_invoice_monthly = $this->getInvoiceObjectMonthlyClass($request, $payment_type);
        $class_invoice_schedule = $this->getInvoiceObjectScheduleClass($request, $payment_type);
        $event_invoice = $this->getInvoiceObjectEvent($request, $payment_type);
        $program_invoice = $this->getInvoiceObjectProgram($request, $payment_type);
        //
        $list_parent = array_merge($class_invoice_monthly,$class_invoice_schedule,$event_invoice,$program_invoice);

        foreach ($list_parent as $k => $v ){

            //generate item name for japanese
            $split = explode('-', $v['invoice_year_month']);
            $item_name = $split[0] . "年" . $split[1] . "月分 ";
            if (isset($v['class_name'])) {
                $item_name .= $v['class_name'] . " " . $v['student_name'];
            } elseif (isset($v['course_title'])) {
                $item_name .= $v['course_title'] . " " . $v['student_name'];
            } elseif (isset($v['program_name'])) {
                $item_name .= $v['program_name'] . " " . $v['student_name'];
            }

            $list_parent[$k]['item_name'] = $item_name;
            $v['item_name'] = $item_name;

            //remove exist item
            if($this->isInvoiceItemExist($v)){
                unset($list_parent[$k]);
                continue;
            }
        }
        return $list_parent;
    }

    private function createEmptyParentRecord($request){

        return array(
                "invoice_year_month" => $request['invoice_year_month'],
                "pschool_id" => $request['pschool_id'],
                "parent_id" => $request['parent_id'],
                "parent_name" => "",
                "mail_infomation" => "",
                "invoice_type" => $request['invoice_type'],
//                "student_id" => 1318
//                "student_name" => "鈴木　長男"
//                "student_no" => "A03-S1-01"
//                "class_id" => 169
//                "class_name" => "平日限定プラン"
//                "payment_method" => 2
//                "fee_plan_name" => "個人一般会員（午前）"
//                "student_type" => "個人一般会員"
//                "amount" => "8000.00"
        );
    }

    /*
     * This function filter class invoice objects
     * remove any record that payment method is not supported by class
     * return array() of invoice objects
     */
    private function filterClassInvoiceByMethod($invoices){

        // assign a variable to count number of class fail
        // then push to session
        if(session()->has(Constants::SESSION_COUNT_FAIL_INVOICE)){
            $fails = session()->pull(Constants::SESSION_COUNT_FAIL_INVOICE);
        }else{
            $fails = 0;
        }

        // count and remove elements
        if(!empty($invoices)){
            $invoice_types = Constants::$PAYMENT_TYPE;
            foreach ($invoices as $k => $invoice){
                if(!empty($invoice['class_id'])){
                    $class = ClassTable::getInstance()->load($invoice['class_id']);
                    $class_methods = explode(",",$class['payment_method']);
                    if(!empty($class_methods)){
                        foreach ($class_methods as $key => $method){
                            $class_methods[$key] = $invoice_types[$method];
                        }
                    }
                    if(!in_array($invoice['payment_method'],$class_methods)){
                        $fails += 1;
                        unset($invoices[$k]);
                    }
                }
            }
        }

        //push to session
        session()->put(Constants::SESSION_COUNT_FAIL_INVOICE, $fails);

        return $invoices;
    }
}