<?php

namespace App\Http\Controllers\Portal;

use App\Common\Constants;
use App\Http\Controllers\School\Invoice\BaseInvoiceController;
use App\Http\Controllers\School\Invoice\InvoiceController;
use App\Model\CourseTable;
use App\Model\ParentTable;
use App\Model\PaymentMethodBankRelTable;
use App\Model\PaymentMethodDataTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\ProgramTable;
use App\Model\StudentTable;
use App\Model\InvoiceHeaderTable;
use App\Module\Invoice\BaseInvoice;
use Illuminate\Http\Request;
use App\Model\MailMessageTable;
use App\Model\EntryTable;
use App\Model\PschoolTable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\ConstantsModel;
use Validator;

class PortalController extends _BasePortalController
{
    protected static $PORTAL_VIEW = array(
        'event' => array (  'index' => 'Portal.Event.index',
                            'confirm' => 'Portal.Event.confirm',
                            'complete' => 'Portal.Event.complete',
                            'result' => 'Portal.Event.result',
                            'pay' => 'Portal.Event.payment_confirm'),
        'program' => array( 'index' => 'Portal.Program.index',
                            'confirm' => 'Portal.Program.confirm',
                            'complete' => 'Portal.Program.complete',
                            'result' => 'Portal.Program.result',
                            'pay' => 'Portal.Program.payment_confirm')
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

        if ( $request->has('result') ) {

        }

        if ( $request->has('entry_code') ) {
            // case result page: check valid entry_code & return mail_message info
            $this->checkEntryCode($request);
        } else if ( $request->has('message_key') ) {
            // check valid message_key & return mail_message info
            $this->checkMessageKey($request);
        } else {
            exit;
        }

        // get info of school
        $this->SCHOOL = PschoolTable::getInstance()->getSchoolInfoPortal($this->MESSAGE_KEY);

        // get fee of student
        $this->FEE_PLAN = $this->getFeePlan($this->MAIL_MESSAGE);

        // get data of object (event, program)
        $data           = $this->getData($this->MAIL_MESSAGE);
        $data['fee']    = $this->FEE_PLAN['fee'];
        $this->DATA     = $data;

        // get entry
        $this->ENTRY = EntryTable::getInstance()->getActiveRow( array(
            'entry_type'  => array_search($this->ENTRY_TYPE, ConstantsModel::$ENTRY_TYPE),
            'student_id'  => $this->MAIL_MESSAGE['student_id'],
            'relative_id' => $this->MAIL_MESSAGE['relative_ID']
        ));

        $invoice_finished = false;
        if(!empty($this->ENTRY['invoice_id'])){
            $invoice = InvoiceHeaderTable::getInstance()->load($this->ENTRY['invoice_id']);
            if(!empty($invoice) && $invoice['workflow_status'] == 31 ){
                $invoice_finished = true;
            }
            $this->ENTRY['invoice_finished'] = $invoice_finished;
        }

    }

    public function index(Request $request) {

        // recover old input
        $this->recoverWithInput($request, array('join_student_number'));

        // save last_refer in mail_message table - メッセージをクリックしたら　以下の値が変わる。
        $this->saveLastRefer($this->MAIL_MESSAGE);

        // check enter in entry table && finish payment

        if($this->ENTRY['enter'] == 1 && ($this->ENTRY['payment_method'] != Constants::CRED_ZEUS || $this->ENTRY['invoice_finished'] )){
            return redirect()->route('portal_'.$this->MESSAGE_TYPE.'_result', ['entry_code' => $this->ENTRY['code']]);
        }


        return view(self::$PORTAL_VIEW[$this->MESSAGE_TYPE]['index'])
            ->with( 'pschool', $this->SCHOOL )
            ->with( 'fee_plan', $this->FEE_PLAN )
            ->with( 'data', $this->DATA )
            ->with( 'entry', $this->ENTRY )
            ->with( 'request' , $request);
    }


    /**
     * save record in entry table
     *
     * @param Request $request
     * @return
     */
    public function confirm(Request $request) {
        // check join or unjoin
        $enter = $request->action == 'join' ? 1 : 0;
        $payment_methods = array();
        $canMerge = false;
        $parent_id = $this->MAIL_MESSAGE['parent_id'];
        $parent = ParentTable::getInstance()->load($parent_id);
        //check if this entry can merge or not
        if($this->DATA['is_merge_invoice']){
            $canMerge = true;
            $methods_arr = explode(',',$this->DATA['payment_method'] );
            foreach ($methods_arr as $k => $v) {
                if (isset(Constants::$PAYMENT_TYPE[$v])) {
                    $payment_methods[$v] = Constants::$invoice_type[$this->SCHOOL['language']][Constants::$PAYMENT_TYPE[$v]];
                }
            }
            $parent_invoice_type = array_flip(Constants::$PAYMENT_TYPE)[$parent['invoice_type']];
            if(!in_array($parent_invoice_type,array_flip($payment_methods))){
                $canMerge = false;
            }


        }
        $this->DATA['canMerge'] = $canMerge;
        //

        // validate in case: join
        if ($enter) {
            if ($this->SCHOOL['student_category'] == ConstantsModel::$MEMBER_CATEGORY_CORP) {
                // validate number of joined
                $request->offsetSet('remain_student', $this->DATA['remain_student']);
                $rules      = $this->get_validate_rules($request);
                $messages   = $this->get_validate_message($request);
                $validator  = Validator::make(request()->all(), $rules, $messages);
                if ($validator->fails() || !$this->DATA['is_active']) {
                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }
                $this->clearOldInputSession();
            }

            /* 2017/08/18 支払方法 */
            // set array $payment_methods: key => method_code (CASH...), value => method_message (現金...)
            $methods_arr = explode(',',$this->DATA['payment_method'] );

            foreach ($methods_arr as $k => $v) {
                if (isset(Constants::$PAYMENT_TYPE[$v])) {
                    $payment_methods[$v] = Constants::$invoice_type[$this->SCHOOL['language']][Constants::$PAYMENT_TYPE[$v]];
                }
            }
            $merge_method = $payment_methods;
            $parent_invoice_type = array_flip (Constants::$PAYMENT_TYPE)[$parent['invoice_type']];

            foreach ($merge_method as $k => $method){
                if($k != $parent_invoice_type){
                    unset($merge_method[$k]);
                }
            }

            if(isset($payment_methods["TRAN_RICOH"])){
                unset($payment_methods["TRAN_RICOH"]);
            }


            // set default payment_method is CASH
//            if (!$payment_methods) {
//                $payment_methods['CASH'] = Constants::$invoice_type[$this->SCHOOL['language']][Constants::$PAYMENT_TYPE['CASH']];
//            }
            /* END - 2017/08/18 支払方法 */
        }

        // save into entry table
        $join_student_number = $request->join_student_number ? $request->join_student_number : 1;

        $entry_table = EntryTable::getInstance();
        try {
            $entry_item = array(
                'entry_type'        => array_search($this->ENTRY_TYPE, ConstantsModel::$ENTRY_TYPE),
                'student_id'        => $this->MAIL_MESSAGE['student_id'],
                'relative_id'       => $this->MAIL_MESSAGE['relative_ID'],
                'status'            => 0, // お支払い・0:未支払い、1:支払った
                'enter'             => $enter,
                'total_member'      => $join_student_number ,
                'last_refer_date'   => date( 'Y-m-d H:i:s' ),
                'enter_date'        => date( 'Y-m-d H:i:s' ),
                'relative_date'     => date( 'Y-m-d H:i:s' ),
            );
            $entry = $entry_table->getActiveRow(array(
                'entry_type'        => array_search($this->ENTRY_TYPE, ConstantsModel::$ENTRY_TYPE),
                'student_id'        => $this->MAIL_MESSAGE['student_id'],
                'relative_id'       => $this->MAIL_MESSAGE['relative_ID'],
            ));

            if ($entry) {
                $entry_item['id'] = $entry['id'];
            } else {
                $entry_item['code'] = $this->generateEntryCode();
            }
            // 料金は無料
            if ($this->FEE_PLAN['fee'] == 0) {
                $entry_item['payment_method'] = Constants::CASH;
            }
            $entry_table->save($entry_item, true);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }

        // redirect to un-join page
        if ( $enter == 0 ) {
            return redirect()->route('portal_'.$this->MESSAGE_TYPE, ['message_key' => $this->MESSAGE_KEY]);
        } else if ($this->FEE_PLAN['fee'] === 0) {
            return redirect()->route('portal_'.$this->MESSAGE_TYPE.'_result', ['entry_code' => $entry_item['code']] );
        }

        return view(self::$PORTAL_VIEW[$this->MESSAGE_TYPE]['confirm'])
            ->with( 'pschool', $this->SCHOOL )
            ->with( 'fee_plan', $this->FEE_PLAN )
            ->with( 'data', $this->DATA )
            ->with( 'entry', $this->ENTRY )
            ->with( 'join', $enter )
            ->with( 'payment_methods', $payment_methods )
            ->with('merge_method',$merge_method)
            ->with('parent',$parent);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse - redirect to result page
     * @update payment_method in entry table
     */
    public function complete(Request $request) {

        // save into entry table (payment_method)
        $entry_table = EntryTable::getInstance();
        try {
            $is_merge_invoice = $request->is_merge_invoice;
            if($is_merge_invoice){
                $this->ENTRY['payment_method'] = $request->payment_method_merge;
                $request->offsetSet('payment_method',$request->payment_method_merge);
            }else{
                $this->ENTRY['payment_method'] = $request->payment_method;
            }
            $this->ENTRY['is_merge_invoice'] = $request->is_merge_invoice;

            $entry_table->save($this->ENTRY, true);

            $student = StudentTable::getInstance()->load($this->ENTRY['student_id']);
            $parent = ParentTable::getInstance()->load($student['parent_id']);
            //get invoice year month
            $pschool_id = $this->DATA['pschool_id'];
            $pschool = PschoolTable::getInstance()->load($pschool_id);
            $invoice_closing_date = $pschool['invoice_closing_date'] == 99 ? date('Y-m-t') : date('Y-m-d',strtotime(date('Y-m-').$pschool['invoice_closing_date']));
            $payment_style = $pschool['payment_style'];
            $today = date('Y-m-d');
            $list_payment = PaymentMethodPschoolTable::getInstance()->getActiveList(array('pschool_id'=>$pschool_id),array('sort_no'));
            $is_koza = false;
            foreach ($list_payment as $k => $v){
                if($v['payment_method_code'] == Constants::TRAN_RICOH){
                    $is_koza = true;
                }
            }

            //
            $invoice_year_month = null;
            $invoiceController = new InvoiceController();

            // payment_style = 1 : prepay
            if($payment_style == 1){
                if($today>$invoice_closing_date){
                    $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+2 month"));
                }else{
                    if($is_koza){

                        $dead_line =  $invoiceController->getDeadLineOfPayment($pschool_id, date('Y-m',strtotime(date("Y-m-d")."+1 month")),2);  // increase 1 month cause this function will decrease 1 when return.
                        if($today<$dead_line){
                            $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
                        }else{
                            $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+2 month"));
                        }
                    }else{
                        $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
                    }
                }
            }else{ // payment_style = 2 : postpay
                if($today>$invoice_closing_date){
                    $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
                }else{
                    if($is_koza){
                        $dead_line = $invoiceController->getDeadLineOfPayment($pschool_id, date('Y-m',strtotime(date("Y-m-d")."+1 month")),2);  // increase 1 month cause this function will decrease 1 when return.
                        if($today<$dead_line){
                            $invoice_year_month = date('Y-m');
                        }else{
                            $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
                        }
                    }else{
                        $invoice_year_month = date('Y-m');
                    }
                }
            }

            //
            if(!$is_merge_invoice){  // do not merge to periodic invoice -> create single record with is_nyukin = 1

                $invoice = new BaseInvoice();
                $request->offsetSet('parent_id', $student['parent_id']);
                $request->offsetSet('invoice_year_month', $invoice_year_month);
                $request->offsetSet('entry_type', $this->ENTRY['entry_type']);
                $request->offsetSet('is_portal',true);
                $request->offsetSet('id',$this->DATA['pschool_id']);
                $request->offsetSet('relative_id',$this->ENTRY['relative_id']);
                $request->offsetSet ('entry_id', $this->ENTRY['id']);

                InvoiceHeaderTable::getInstance()->beginTransaction();
                $invoice->createInvoice($request, $parent['invoice_type'],false);
                InvoiceHeaderTable::getInstance()->commit();
            }else { // merge to periodic so create record in newest month nyukin = 0

                $invoice = new BaseInvoice();
                $request->offsetSet('parent_id', $student['parent_id']);
                $request->offsetSet('invoice_year_month', $invoice_year_month);
                $request->offsetSet('id',$this->DATA['pschool_id']);
                $request->offsetSet('relative_id',$this->ENTRY['relative_id']);
                $request->offsetSet('event_type_id', $this->ENTRY['entry_type']);
                $request->offsetSet('entry_type', $this->ENTRY['entry_type']);
                $request->offsetSet ('entry_id', $this->ENTRY['id']);

                InvoiceHeaderTable::getInstance()->beginTransaction();
                $invoice->createInvoice($request, $parent['invoice_type'],false);
                InvoiceHeaderTable::getInstance()->commit();
            }

        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
        // redirect to result
        return redirect()->route('portal_'.$this->MESSAGE_TYPE.'_result', ['entry_code' => $this->ENTRY['code']] );
    }

    /**
     * @return $view - result page
     */
    public function result() {
        // view

        if($this->ENTRY['payment_method'] == Constants::TRAN_BANK){
            $bank_info = PaymentMethodBankRelTable::getInstance()->getListBank($this->SCHOOL['id'],Constants::$PAYMENT_TYPE['TRAN_BANK']);
            if(!empty($bank_info['list_bank'])){
                foreach ($bank_info['list_bank'] as $k => $bank){
                    if($bank['is_default_account'] == 1){
                        $this->SCHOOL['school_bank_info'] = $bank;
                        $bank_account_type_list = ConstantsModel::$type_of_bank_account [session()->get( 'school.login.language')];
                        $this->SCHOOL['school_bank_info']['bank_account_type'] = $bank_account_type_list[$bank['bank_account_type']];
                    }
                }
            }
        }

        return view(self::$PORTAL_VIEW[$this->MESSAGE_TYPE]['result'])
            ->with( 'pschool', $this->SCHOOL )
            ->with( 'fee_plan', $this->FEE_PLAN )
            ->with( 'data', $this->DATA )
            ->with( 'entry', $this->ENTRY );
    }

    /**
     * @param Request $request
     * @return $view - credit card confirm page
     */
    public function pay(Request $request) {

        //save entry
        $entry = $this->ENTRY;
        $entry['payment_method'] = Constants::POST_RICOH;
        $entry['is_merge_invoice'] = 0;
        EntryTable::getInstance()->save($entry);

        //get invoice month
        $invoice_year_month = $this->get_invoice_newest_month();
        $student = StudentTable::getInstance()->load($entry['student_id']);

        //create invoice header
        $invoice = new BaseInvoice();
        $request->offsetSet('parent_id', $student['parent_id']);
        $request->offsetSet('invoice_year_month', $invoice_year_month);
        $request->offsetSet('entry_type', $entry['entry_type']);
        $request->offsetSet('is_portal',true);
        $request->offsetSet('id',$this->DATA['pschool_id']);
        $request->offsetSet('relative_id',$entry['relative_id']);
        $request->offsetSet('entry_id',$entry['id']);

        InvoiceHeaderTable::getInstance()->beginTransaction();
        $invoice->createInvoice($request, Constants::$PAYMENT_TYPE['CRED_ZEUS'],false);
        InvoiceHeaderTable::getInstance()->commit();

        $payment_selected = $request->payment_method;
        $payment_info = PaymentMethodDataTable::getInstance()->getIPCode($this->MAIL_MESSAGE['pschool_id'], $payment_selected);

        $info = array(
            'domain'        => $request->server('HTTP_HOST'),
            'ip_code'       => $payment_info['ip_code'],
            'payment_link'  => $payment_info['payment_link'],
        );
        // view
        return view(self::$PORTAL_VIEW[$this->MESSAGE_TYPE]['pay'])
            ->with( 'pschool', $this->SCHOOL )
            ->with( 'fee_plan', $this->FEE_PLAN )
            ->with( 'data', $this->DATA )
            ->with( 'entry', $this->ENTRY )
            ->with( 'info', $info );
    }

    /**
     * @param $request
     * @return $this - mail message info
     * @throws FileNotFoundException
     */
    private function checkMessageKey($request) {
        // get info from url
        $path_info = explode("/", $request->path());
        $type = $path_info[1];

        $mail_message = MailMessageTable::getInstance()->getMailMessageDetail($request->message_key);
        if ($mail_message && $mail_message['mail_message_type'] == $type) {
            $this->MESSAGE_KEY  = $request->message_key;
            $this->MESSAGE_TYPE = $this->ENTRY_TYPE = $mail_message['mail_message_type'];
            $this->MAIL_MESSAGE = $mail_message;
        } else {
            throw new FileNotFoundException("FileNotFound - message key does not exist");
            exit;
        }
    }

    /**
     * update last_refer_date in mail_message table
     *
     * @param $mail_message
     */
    private function saveLastRefer($mail_message) {
        $mail_message_table = MailMessageTable::getInstance();
        try {
            $mail_message['last_refer_date'] = date( 'Y-m-d H:i:s' );
            $mail_message_table->save($mail_message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            exit;
        }
    }

    protected function getData($mail_message) {}
    protected function getFeePlan($mail_message) {}

    /**
     * Get Now with milliseconds Ex: 170609082550493685
     */
    private function getDateWithMilliseconds() {
        $micro = microtime(true);
        $micro = sprintf("%06d",($micro - floor($micro)) * 1000000);
        $d = new \DateTime( date('Y-m-d H:i:s.'.$micro, $micro) );
        return $d->format("ymdHisu");
    }

    /**
     * In case: Redirect to result page. Check entry_code valid
     * @param $request
     * @return mail message info
     * @throws FileNotFoundException
     */
    private function checkEntryCode($request) {
        $path_info = explode("/", $request->path());
        $entry = EntryTable::getInstance()->getActiveRow(array('code' => $request->entry_code));
        if ( isset($path_info[2]) && $path_info[2] == 'result' && $entry) {
            $message_type = array_search($path_info[1], ConstantsModel::$MAIL_MESSAGE_TYPE);
            $mail_message = MailMessageTable::getInstance()->getActiveRow(array(
                'type'          => $message_type,
                'relative_ID'   => $entry['relative_id'],
                'student_id'    => $entry['student_id']
            ));
            $request->offsetSet('message_key', $mail_message['message_key']);
            $this->checkMessageKey($request);
        } else {
            throw new FileNotFoundException("FileNotFound - entry_code does not exist");
            exit;
        }
    }

    /**
     * Generate Entry Code
     * Ex: [Event_code]-[EVENT]-[Student_ID]
     * Ex: [Program_code]-[PROGRAM]-[Student_ID]
     */
    private function generateEntryCode(){
        if ($this->MESSAGE_TYPE == 'event') {
            $code = $this->DATA['course_code'] . '-' . strtoupper(uniqid());
        } else {
//            } elseif ($this->MESSAGE_TYPE == 'program') {
            $code = $this->DATA[$this->MESSAGE_TYPE.'_code'] . '-' . strtoupper(uniqid());
        }
        return $code;
    }

    private function get_validate_rules($request) {
        $rules = array(
            'join_student_number'  => "required|numeric|min:1|max:{$request->remain_student}",
        );
        return $rules;
    }

    private function get_validate_message($request) {
        $messages = array(
            'join_student_number.required'  => '参加人数は必要です。',
            'join_student_number.min'       => '1以上入力してください。',
            'join_student_number.max'       => "{$request->remain_student}以下入力してください。",
        );
        return $messages;
    }

    public function get_invoice_newest_month(){
        //get invoice year month
        $pschool_id = $this->DATA['pschool_id'];
        $pschool = PschoolTable::getInstance()->load($pschool_id);
        $invoice_closing_date = $pschool['invoice_closing_date'] == 99 ? date('Y-m-t') : date('Y-m-d',strtotime(date('Y-m-').$pschool['invoice_closing_date']));
        $payment_style = $pschool['payment_style'];
        $today = date('Y-m-d');
        $list_payment = PaymentMethodPschoolTable::getInstance()->getActiveList(array('pschool_id'=>$pschool_id),array('sort_no'));
        $is_koza = false;
        foreach ($list_payment as $k => $v){
            if($v['payment_method_code'] == Constants::TRAN_RICOH){
                $is_koza = true;
            }
        }

        //
        $invoice_year_month = null;
        $invoiceController = new InvoiceController();

        // payment_style = 1 : prepay
        if($payment_style == 1){
            if($today>$invoice_closing_date){
                $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+2 month"));
            }else{
                if($is_koza){

                    $dead_line =  $invoiceController->getDeadLineOfPayment($pschool_id, date('Y-m',strtotime(date("Y-m-d")."+1 month")),2);  // increase 1 month cause this function will decrease 1 when return.
                    if($today<$dead_line){
                        $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
                    }else{
                        $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+2 month"));
                    }
                }else{
                    $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
                }
            }
        }else{ // payment_style = 2 : postpay
            if($today>$invoice_closing_date){
                $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
            }else{
                if($is_koza){
                    $dead_line = $invoiceController->getDeadLineOfPayment($pschool_id, date('Y-m',strtotime(date("Y-m-d")."+1 month")),2);  // increase 1 month cause this function will decrease 1 when return.
                    if($today<$dead_line){
                        $invoice_year_month = date('Y-m');
                    }else{
                        $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+1 month"));
                    }
                }else{
                    $invoice_year_month = date('Y-m');
                }
            }
        }
        return $invoice_year_month;
    }
}