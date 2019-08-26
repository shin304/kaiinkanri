<?php

namespace App\Http\Controllers\School\Invoice;

use App\Common\Constants;
use App\Common\Email;
use App\Http\Controllers\School\MailMessageController;
use App\Lang;
use App\Model\ClosingDayTable;
use App\Model\ClassTable;
use App\Model\CourseTable;
use App\Model\EntryTable;
use App\Model\InvoiceDebitTable;
use App\Model\InvoiceItemTable;
use App\Model\InvoiceRequestTable;
use App\Model\ParentBankAccountTable;
use App\Model\PaymentMethodBankRelTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\PaymentMethodTable;
use App\Model\LoginAccountTable;
use App\Model\MStudentTypeTable;
use App\Model\ProgramTable;
use App\Model\PschoolTable;
use App\Model\StudentCourseRelTable;
use App\Module\Invoice\PaymentFactory;
use DaveJamesMiller\Breadcrumbs\Exception;
use Faker\Provider\DateTime;
use Dompdf\Options;
use Illuminate\Http\Request;
use App\Model\InvoiceHeaderTable;
use App\Model\ParentTable;
use App\Model\PschoolBankAccountTable;
use App\Model\MailMessageTable;
use App\ConstantsModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Validator;
use Dompdf\Dompdf;
use App\Common\UploadFileHandler;
use App\Module\Invoice\InvoiceHelper;
use Illuminate\Support\Facades\File;
use App\Common\CSVExport;
use DB;
use Carbon\Carbon;

class InvoiceController extends BaseInvoiceController
{   use \App\Common\Email;
    //declare constant

    private $LIST_INVOICE_SESSION = "list_invoice_session";
    private $UPLOAD_STATUS = array(
            "DEFAULT"               => 0,
            "UPLOAD_ERROR"          => 1,
            "UPLOAD_SUCCESS"        => 2,
    );
    protected static $LANG_URL = 'invoice';
    private $lan;
    private $cashPayment;
    private $ricohTransferPayment;
    private $deadline_hours = ' 15:00:00';
    //-----------------------------------------------------------------
    // 全銀データ フォーマット
    // ヘッダーレコード   : 120byte + 2byte(CR + LF)  1
    // データレコード     : 120byte + 2byte(CR + LF)  n
    // トレーラーレコード : 120byte + 2byte(CR + LF)  1
    // エンドレコード     : 120byte + 2byte(CR + LF)  1
    //-----------------------------------------------------------------
    const HEADER_RECORD_LEN  = 122;
    const DATA_RECORD_LEN    = 122;
    const TRAILER_RECORD_LEN = 122;
    const END_RECORD_LEN     = 122;

    const SESSION_UPLOAD_PATH = "invoice_upload_path";
    const SESSION_UPLOAD_NAME = "invoice_upload_name";

    //end declare
    private $_invoice_search_item = ['invoice_type_search'];
    private $_invoice_search_session_key = 'invoice_search_form';
    private $_deposit_search_item = ['name_furigana', 'student_no', 'student_type_ids', 'invoice_year_month_from', 'invoice_year_month_to',
        'invoice_type', 'class_id', 'course_id', 'program_id', 'workflow_status', 'chk_filter' ,'invoice_type_ids', 'class_id', 'course_id', 'program_id'];
    private $_deposit_search_session_key = 'deposit_search_form';

    public function __construct() {
        parent::__construct ();
        $message_content = parent::getMessageLocale ();
        $this->lan = new Lang ( $message_content );

        $this->cashPayment = PaymentFactory::getPaymentMethod(Constants::CASH);
        $this->ricohTransferPayment = PaymentFactory::getPaymentMethod(Constants::TRAN_RICOH);

        return redirect ( $this->get_app_path ());
    }

    public function execute(Request $request){
        session()->forget($this->_invoice_search_session_key);

        //TODO list summary invoice of school
        //ex : /school/invoice
        // get summary and count each payment method

        //$invoice_list = $this->getInvoiceStatusMenu($request);
        $pschool_id = session()->get('school.login.id');
        $invoice_list = $this->getTotalSummary($pschool_id);

        // current month does not have record -> generate 1 empty default head
        $curr_month  = date('Y-m');
        $empty_month = $this->generateEmptyInvoiceMonth($request);
        foreach ($empty_month as $key => $value){
            if(!isset($invoice_list[$key])){
                $invoice_list = array_merge($empty_month,$invoice_list);
            }
        }

        //
        $invoice_background_color = Constants::invoice_background_color;

        // get 1 heads that have all payment type
        foreach ($invoice_list as $month => $heads){
            foreach ($heads as $k => $v){
                if(is_array($v)){
                    $invoice_list[$month]['main'] = $v;
                    break 1;
                }
            }
            foreach ($heads as $k => $v){
                if(is_array($v)){
                    $invoice_list[$month]['main_amount'] = $v;
                    break 1;
                }
            }
        }

        //check if have to export nyukin of combini and yuucho
        $newest_month = null;
        if(!empty($invoice_list)){
            $newest_month = array_keys($invoice_list)[0];
            $export_list = InvoiceRequestTable::getInstance()->getExportListByMonth($newest_month);
            if(!empty($export_list)){
                foreach ($export_list as $k => $v){
                    if($v['invoice_type']==3){
                        $invoice_list[$newest_month]['count']['export']['COMBINI'] = isset($invoice_list[$newest_month]['export']['COMBINI']) ? $invoice_list[$newest_month]['count']['export']['COMBINI']+= 1 : 1;
                        $invoice_list[$newest_month]['amount']['export']['COMBINI'] = isset($invoice_list[$newest_month]['export']['COMBINI']) ? $invoice_list[$newest_month]['amount']['export']['COMBINI']+= 1 : 1;
                    }elseif ($v['invoice_type']==4){
                        $invoice_list[$newest_month]['count']['export']['YUUCHO'] = isset($invoice_list[$newest_month]['export']['YUUCHO']) ? $invoice_list[$newest_month]['count']['export']['YUUCHO']+=1 : 1;
                        $invoice_list[$newest_month]['amount']['export']['YUUCHO'] = isset($invoice_list[$newest_month]['export']['YUUCHO']) ? $invoice_list[$newest_month]['amount']['export']['YUUCHO']+=1 : 1;
                    }

                }
            }
        }
        //dd($invoice_list);
        //
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];

        return view("School.Invoice.summary",compact('invoice_list','invoice_type','invoice_background_color','newest_month'));
    }
    public function executeConfirmation(Request $request){
        if(!$request->offsetExists('frm_search') || $request->frm_search != 1){
            $this->_initSearchDataFromSession($this->_invoice_search_item, $this->_invoice_search_session_key);
        }else{
            foreach ($this->_invoice_search_item as $item)
                if(!$request->offsetExists($item)){
                    $request->offsetSet($item,array());
                }
            $this->_initSearchDataFromSession($this->_invoice_search_item, $this->_invoice_search_session_key);
        }

        //TODO get list invoice of month-year get in param
        // ex : /school/invoice/list?invoice_year_month=2017-09
        // get summary for menu

        $this_screen = "confirm";
        $invoice_background_color = Constants::invoice_background_color;
        if($request->offsetExists('invoice_year_month')){
            $invoice_year_month = $request->invoice_year_month;
            $count_invoice = $this->getInvoiceStatusMenu($request);
            view()->share('heads',$count_invoice);
            // get invoice list
            $filter = array();

            //dd($request->invoice_type_search);
            if($request->offsetExists('invoice_type_search')){
                $filter = $request->invoice_type_search;
            }

            $invoice_list = InvoiceHeaderTable::getInstance()->getListInvoiceByMonth(session()->get('school.login.id'), $invoice_year_month, $filter);
            $invoice_type_constant = Constants::$invoice_type;
            $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];

            // generate array list in session for previous - next
            if(count($invoice_list)){
                foreach ($invoice_list as $k => $invoice){
                    $list_id[] = $invoice['id'];
                }
                session()->put($this->LIST_INVOICE_SESSION,$list_id);
            }
            // end session
            $lan = $this->lan;
            return view ( 'School.Invoice.top',compact('lan','this_screen','invoice_list','invoice_type','request','invoice_background_color','filter'));
        }else{
            return redirect('/school/invoice');
        }
    }
    public function executeList(Request $request){

        //get error from session if exists then clear session
        if(session()->has(Constants::SESSION_COUNT_FAIL_INVOICE)){
            $failed = session()->pull(Constants::SESSION_COUNT_FAIL_INVOICE);
            $request->offsetSet('failed_count',$failed);
            session()->forget(Constants::SESSION_COUNT_FAIL_INVOICE);
        }

        //
        if(!$request->offsetExists('frm_search') || $request->frm_search != 1){
            $this->_initSearchDataFromSession($this->_invoice_search_item, $this->_invoice_search_session_key);
        }else{
            foreach ($this->_invoice_search_item as $item)
            if(!$request->offsetExists($item)){
                $request->offsetSet($item,array());
            }
            $this->_initSearchDataFromSession($this->_invoice_search_item, $this->_invoice_search_session_key);
        }

        //TODO get list invoice of month-year get in param
        // ex : /school/invoice/list?invoice_year_month=2017-09
        // get summary for menu

        $this_screen = "list";
        $invoice_background_color = Constants::invoice_background_color;
        if($request->offsetExists('invoice_year_month')){
            $invoice_year_month = $request->invoice_year_month;
            $count_invoice = $this->getInvoiceStatusMenu($request);
            view()->share('heads',$count_invoice);
            // get invoice list
            $filter = array();

            //dd($request->invoice_type_search);
            if($request->offsetExists('invoice_type_search')){
                $filter = $request->invoice_type_search;
            }

            $invoice_list = InvoiceHeaderTable::getInstance()->getListInvoiceByMonth(session()->get('school.login.id'), $invoice_year_month, $filter);
            $invoice_type_constant = Constants::$invoice_type;
            $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];

            // generate array list in session for previous - next
            if(count($invoice_list)){
                foreach ($invoice_list as $k => $invoice){
                    $list_id[] = $invoice['id'];
                }
                session()->put($this->LIST_INVOICE_SESSION,$list_id);
            }
            // end session
            $lan = $this->lan;
            return view ( 'School.Invoice.top',compact('lan','this_screen','invoice_list','invoice_type','request','invoice_background_color','filter'));
        }else{
            return redirect('/school/invoice');
        }

    }
    public function executeGenerateInvoice(Request $request){

        //clear failed number in session
            session()->forget(Constants::SESSION_COUNT_FAIL_INVOICE);
        //

        $pschool_id = session()->get('school.login.id');
        $request->offsetSet('id',$pschool_id);
        if(isset($request->invoice_year_month)){
            try{
                InvoiceHeaderTable::getInstance()->beginTransaction();
                parent::executeGenerateInvoice($request);
                InvoiceHeaderTable::getInstance()->commit();
                return $this->executeList($request);
            }catch (Exception $e){
                InvoiceHeaderTable::getInstance()->rollBack();
            }
        }else{
            return redirect()->to('/school/invoice');
        }
    }

    public function entryInvoice(Request $request){
        if(!$request->offsetExists('parent_id') || !$request->offsetExists('invoice_year_month')){
            return redirect('school/invoice/');
        }

        $pschool_id = session()->get('school.login.id');
        $request->offsetSet('id',$pschool_id);

        try{
            InvoiceHeaderTable::getInstance()->beginTransaction();
            parent::generateInvoice($request);
            InvoiceHeaderTable::getInstance()->commit();

            $invoice_id = session('created_ids');
            if(!empty($invoice_id)){
                $request['id'] = $invoice_id;
                return $this->detailInvoice($request);
            }

            return $this->executeList($request);
        }catch (Exception $e){
            InvoiceHeaderTable::getInstance()->rollBack();
        }

    }

    public function detailInvoice(Request $request) {

        session()->forget('created_ids');
        // TODO return detail view of invoice
        if(!isset($request->id)){
            return redirect()->to('/school/invoice');
        }
        $header_id = $request->id;
        $this_screen = "detail";
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoiceItemTable = InvoiceItemTable::getInstance();

        // data will be get from invoice header
        $data = $invoiceHeaderTable->getDataParentStudent($header_id);
        $debit_data = InvoiceDebitTable::getInstance()->getInvoiceDebitDataById($header_id);

        // get item of this header_id and process
        $item_list = $invoiceItemTable->getListItemInvoice($data);

        //get list invoice from session and generate next - previous
        $pre_id = null;
        $next_id = null;
        if(session()->has($this->LIST_INVOICE_SESSION)){
            $list_invoice = session()->get($this->LIST_INVOICE_SESSION);
            if(count($list_invoice)>1){
                foreach ($list_invoice as $k => $v){
                    if($v==$request->id){
                        if(isset($list_invoice[$k-1])){
                            $pre_id  = $list_invoice[$k-1];
                        }
                        if(isset($list_invoice[$k+1])){
                            $next_id = $list_invoice[$k+1];
                        }
                    }
                }
            }
        }
        // Toran get bank for ginkou furikomi
        if($item_list['invoice_type'] == Constants::$PAYMENT_TYPE['TRAN_BANK']){
            $bank_info = PaymentMethodBankRelTable::getInstance()->getListBank(session('school.login.id'),$item_list['invoice_type']);
            if(!empty($bank_info['list_bank'])){
                foreach ($bank_info['list_bank'] as $k => $bank){
                    if($bank['is_default_account'] == 1){
                        $item_list['school_bank_info'] = $bank;
                        $bank_account_type_list = ConstantsModel::$type_of_bank_account [session()->get( 'school.login.language')];
                        $item_list['school_bank_info']['bank_account_type'] = $bank_account_type_list[$bank['bank_account_type']];
                    }
                }
            }
        }
        // end session
        $count_invoice = $this->getInvoiceStatusMenu($request);
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];


        view()->share('heads',$count_invoice);
        return view('School.Invoice.detail',compact('request', 'data', 'this_screen', 'invoice_type','item_list',
                                                    'pre_id','next_id','debit_data'));
    }

    /**
     * get list id invoice then set to status 2
     * @param Request $request
     * @return stage 2 view
     *
     */
    public function InvoiceConfirmStatus2(Request $request) {

        $lan = $this->lan;
        if(!isset($request->parent_ids) || !isset($request->invoice_year_month)){
            $request->offsetSet('errors',$lan::get("list_screen_error"));
            return $this->executeList($request);
        }
        try {
            $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
            $invoiceHeaderTable->beginTransaction();

            foreach ($request->parent_ids as $id){
                $header = array(
                        "id" => $id,
                        "is_established" => "1",
                        "workflow_status" => 1
                );
                $invoiceHeaderTable->save($header);
            }

            $invoiceHeaderTable->commit();
            $request->offsetSet('messages',$lan::get("invoice_item_edited,".count($request->parent_ids)));
        } catch (Exception $ex) {
            InvoiceHeaderTable::getInstance()->rollBack();
            $request->offsetSet('errors',$lan::get("process_invoice_error_message"));
        }
        return $this->executeConfirmation($request);
    }
    public function SingleInvoiceConfirm(Request $request){

        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoiceHeaderTable->beginTransaction();
        try {
            $header = array(
                "id" => $request->id,
                "is_established" => "1",
                "workflow_status" => "1",
            );
            $invoiceHeaderTable->save($header);
            $invoiceHeaderTable->commit();

        } catch (Exception $ex) {
            $invoiceHeaderTable->rollBack();
        }

        return $this->detailInvoice($request);
    }

    public function EditInvoice(Request $request){

        if(!$request->offsetExists('id') && !$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }
        if(!$request->offsetExists('id') && !$request->offsetExists('invoice_year_month')){
            return $this->executeList($request);
        }

        $header_id = $request->id;
        $invoice_year_moth = $request->invoice_year_month;
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoiceItemTable = InvoiceItemTable::getInstance();

        // data will be get from invoice header
        $data = $invoiceHeaderTable->getDataParentStudent($header_id);
        $debit_data = InvoiceDebitTable::getInstance()->getInvoiceDebitDataById($header_id);
        // get item of this header_id and process
        if($request->offsetExists('preview_bool')){
            $item_list = $this->processItemListAfterEdit($data,$request);
        }else{
            $item_list = $invoiceItemTable->getListItemInvoice($data);
        }
//dd($item_list);
        $request->offsetSet('this_screen','edit');
        $count_invoice = $this->getInvoiceStatusMenu($request);
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];
        view()->share('heads',$count_invoice);
        return view('School.Invoice.edit',compact('request','data','item_list','this_screen','count_invoice','invoice_type','debit_data'));
    }

    public function completeEditInvoice(Request $request){

        $rules = $this->validate_custom_rules($request);
        $message = $this->validate_custom_messages($request);
        $validator = Validator::make(request()->all(), $rules, $message);
        if($validator->fails()){
            $request->offsetSet('error_custom_item',$validator->errors()->all());
            return $this->editInvoice($request);
        }

        $header_id = $request->id;
        $invoice_year_moth = $request->invoice_year_month;
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoiceItemTable = InvoiceItemTable::getInstance();

        // data will be get from invoice header
        $data = $invoiceHeaderTable->getDataParentStudent($header_id);
        // get item of this header_id and process
        $item_list = $this->processItemListAfterEdit($data,$request);
        if(isset($request->preview_bool) && $request->preview_bool ==1 ){
            //return to preview page
            $count_invoice = $this->getInvoiceStatusMenu($request);
            $invoice_type_constant = Constants::$invoice_type;
            $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];
            view()->share('heads',$count_invoice);

            $debit_data = array();
            if($request->offsetExists('debit_id')){
                $debit_ids = implode("," ,$request->debit_id);
                $debit_data = InvoiceDebitTable::getInstance()->getListDebit($request->id, $debit_ids);
            }
            if(!empty($debit_data)){
                foreach ($debit_data as $debit){
                    $item_list['amount']+= $debit['amount'];

                }
                $amount = $item_list['amount'];
                $sales_tax_rate = floatval($item_list["sales_tax_rate"]);

                if ($item_list["amount_display_type"] == "0") {
                    $tax_price = floor($amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100));
                    $amount_tax = $amount;
                } else {
                    $tax_price = floor($amount * $sales_tax_rate);
                    $amount_tax = $amount + $tax_price;
                }
                $item_list["tax_price"] = $tax_price;
                $item_list["amount_tax"] = $amount_tax;
            }
            //dd($item_list);
            return view('School.Invoice.invoice_preview',compact('data','request','item_list','invoice_type','debit_data'));
        }else{
            // save edit and return to detail page
            // reget item list from db
            $invoice_item_list = $invoiceItemTable->getActiveList(array('invoice_id'=>$header_id));
            if(!empty($invoice_item_list)){
                $parent_id = $invoice_item_list[0]['parent_id'];
            }else{
                $parent_id = $data['parent_id'];
            }

            $res = array();
            $sum_discount_price = 0;
            $amount = 0;
            foreach($invoice_item_list as $k => $item){
                if (!isset($item["student_id"])) {
                    $res["discount_item"][] = $item;
                } elseif(isset($item["class_id"])) {
                    $res["class_item"][] = $item;
                } elseif(isset($item["course_id"])) {
                    $res['course_item'][] = $item;
                } elseif(isset($item["program_id"])) {
                    $res['program_item'][] = $item;
                } else {
                    $res["custom_item"][] =$item;
                }
            }

            // add new custom item
            $row = array();
            if($request->offsetExists('custom_item')){
                foreach($request->custom_item as $student_id =>$v){
                    foreach ($v as $k => $item){
                        $row[] = array(
                                'pschool_id' => session()->get('school.login.id'),
                                'invoice_id' => $header_id,
                                'parent_id'  => $parent_id,
                                'student_id' => $student_id,
                                'item_name'  => $item['name'],
                                'unit_price' => $item['price'],
                                'monthly_billing' => empty($invoice_item_list)? 0 : $invoice_item_list[0]['monthly_billing'],
                                'payment_method'  => $data['invoice_type'],
                                'due_date'  => $data['due_date'],
                                'active_flag' => 1,
                        );
                    }
                }
            }

            // if have debit => add to $row
            if($request->offsetExists('debit_id')){
                $debit_ids = implode("," ,$request->debit_id);
                $debit_data = InvoiceDebitTable::getInstance()->getListDebit($request->id, $debit_ids);

                foreach ($debit_data as $k=> $debit){
                    $row[] = array(
                            'pschool_id' => session()->get('school.login.id'),
                            'invoice_id' => $header_id,
                            'parent_id'  => $parent_id,
                            'student_id' => $request->current_student,
                            'item_name'  => date('Y年m月',strtotime($debit['debit_year_month'].'-01')).'未入金',
                            'unit_price' => $debit['amount'],
                            'monthly_billing' => empty($invoice_item_list)? 0 : $invoice_item_list[0]['monthly_billing'],
                            'payment_method'  => $data['invoice_type'],
                            'due_date'  => $debit['due_date'],
                            'active_flag' => 1,
                    );
                }
            }

            // process class except flag
            if(isset($res["class_item"])){
                foreach($res["class_item"] as $k => $v){
                    $res["class_item"][$k]['except_flag']=0;
                }
                if($request->offsetExists('class_except')){
                    foreach ($request->class_except as $student_id => $v){
                        foreach ($v as $k => $item){
                            $res["class_item"][$k]['except_flag'] = $item;
                        }
                    }
                }
            }

            // process event except flag
            if(isset($res["course_item"])){
                foreach($res["course_item"] as $k => $v){
                    $res["course_item"][$k]['except_flag']=0;
                }
                if($request->offsetExists('course_except')){
                    foreach ($request->course_except as $student_id => $v){
                        foreach ($v as $k => $item){
                            $res["course_item"][$k]['except_flag'] = $item;
                        }
                    }
                }
            }

            // process event except flag
            if(isset($res["program_item"])){
                foreach($res["program_item"] as $k => $v){
                    $res["program_item"][$k]['except_flag']=0;
                }
                if($request->offsetExists('program_except')){
                    foreach ($request->program_except as $student_id => $v){
                        foreach ($v as $k => $item){
                            $res["program_item"][$k]['except_flag'] = $item;
                        }
                    }
                }
            }

            // process custom except flag

            if(isset($res["custom_item"])){
                foreach($res["custom_item"] as $k => $v){
                    $res["custom_item"][$k]['except_flag']=0;
                }
                if($request->offsetExists('custom_except')){
                    foreach ($request->custom_except as $student_id => $v){
                        foreach ($v as $k => $item){
                            $res["custom_item"][$k]['except_flag'] = $item;
                        }
                    }
                }
            }

            // save to DB
            try{
                $invoiceItemTable->beginTransaction();
                //save except item
                foreach ($res as $k=> $group){
                    foreach ($group as $key => $item){
                        unset($item["update_date"]);
                        $invoiceItemTable->save($item);
                        $invoiceHeaderTable->updateAmountInvoiceHeader($item['invoice_id']);
                    }
                }

                //save new custom item and debit as new item
                foreach ($row as $k=>$v){
                    $invoiceItemTable->save($v);
                    $invoiceHeaderTable->updateAmountInvoiceHeader($v['invoice_id']);
                }

                // update invoice debit => set status to 1
                if(isset($debit_ids)){
                    InvoiceDebitTable::getInstance()->updateSetDebitComplete($debit_ids);
                }
                //
                $invoiceItemTable->commit();
                return $this->detailInvoice($request);
            }catch (Exception $e){
                $invoiceItemTable->rollBack();
            }

        }

    }

    public function deleteInvoice(Request $request){

        if(!$request->offsetExists('id') && !$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }
        if(!$request->offsetExists('id') && !$request->offsetExists('invoice_year_month')){
            return $this->executeList($request);
        }

        $header_data = InvoiceHeaderTable::getInstance()->load($request->id);

        if($header_data['is_established'] == 1 ){
            $request->offsetSet('errors','already_commit_cannot_delete_message');
            return $this->executeList($request);
        }elseif($header_data['is_requested'] == 1){
            $request->offsetSet('errors','already_billing_cannot_delete_message');
            return $this->executeList($request);
        }

        try {
            InvoiceHeaderTable::getInstance()->beginTransaction();

            // 請求書ヘッダーを削除する。
            $header = array(
                    "id" => $request->id,
                    "active_flag" => "0",
                    "delete_date" => date('Y-m-d H:i:s'),
                    "update_admin" => session()->get('school.login.login_account_id'),
            );
            InvoiceHeaderTable::getInstance()->save($header);

            // 請求書詳細を削除する
            $details = InvoiceItemTable::getInstance()->getList(array('invoice_id'=>$request->id, 'delete_date IS NULL'));
            foreach ($details as $detail_item){
                $detail_item['active_flag']  = 0;
                $detail_item['delete_date']  = date('Y-m-d H:i:s');
                $detail_item['update_admin'] = session()->get('school.login.login_account_id');
                InvoiceItemTable::getInstance()->save($detail_item);
            }

            InvoiceHeaderTable::getInstance()->commit();
            $request->offsetSet('messages','invoice_deleted_message');

        } catch (Exception $ex) {
            InvoiceHeaderTable::getInstance()->rollBack();
            $request->offsetSet('errors','process_invoice_error_message');
        }
        return $this->executeList($request);
        //return redirect()->to('/school/invoice/list?invoice_year_month='.$request->invoice_year_month);
    }

    /**
     * search all status 3 invoice
     * @param Request $request -> invoice_year month is mandatory
     * return stage 3 view
     */
    public function mailSearch(Request $request) {

        if(!$request->offsetExists('frm_search') || $request->frm_search != 1){
            $this->_initSearchDataFromSession($this->_invoice_search_item, $this->_invoice_search_session_key);
        }else{
            foreach ($this->_invoice_search_item as $item)
                if(!$request->offsetExists($item)){
                    $request->offsetSet($item,array());
                }
            $this->_initSearchDataFromSession($this->_invoice_search_item, $this->_invoice_search_session_key);
        }

        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }
        $this_screen='mail_search';
        $invoice_background_color = Constants::invoice_background_color;

        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);

        // get invoice list
        $filter = array();

        //dd($request->invoice_type_search);
        if($request->offsetExists('invoice_type_search')){
            $filter = $request->invoice_type_search;
        }
        $invoice_list = InvoiceHeaderTable::getInstance()->getListInvoiceByMonth(session()->get('school.login.id'),$invoice_year_month,$filter);
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];

        $lan = $this->lan;
        return view('School.Invoice.mail_search',compact('request','invoice_list','invoice_type','this_screen','invoice_background_color','filter'));

    }

    /**return view for import and export processing txt file of ricoh transfer
     * @param Request $request
     */
    public function ricohTransProcess(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }

        $this_screen='ricoh_trans';

        $pschool_id	= session()->get('school.login.id');
        $invoice_year_month = $request->invoice_year_month;
        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);
        //

        $transfer_list = InvoiceRequestTable::getInstance()->getAccountTranserList($pschool_id, $invoice_year_month, $cond = null, Constants::$PAYMENT_TYPE['TRAN_RICOH']);
        $process_flg = 0;
        foreach ($transfer_list as $key => $transfer){
            if($transfer['status_flag'] == 3 || $transfer['status_flag'] == 1 ){
                $process_flg = $transfer['status_flag'];
                break 1;
            }
        }

        //assign array status of file base on language
        $request->offsetSet('request_status',Constants::$request_status[session()->get('school.login.language')]);
        return view("School.Invoice.Ricoh.ricoh_transfer",compact('transfer_list','request','this_screen','process_flg'));
    }

    /**
     * @param Request $request (invoice_year_month is mandatory)
     * return view to export file for ricoh_transfer method
     */
    public function ricohTransDownload(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }

        $this_screen='ricoh_trans_download';

        $invoice_year_month = $request->invoice_year_month;
        $pschool_id = session()->get('school.login.id');
        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);
        //
        $invoice_type = Constants::$PAYMENT_TYPE['TRAN_RICOH'];
        $file_list = array();
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type);

        $invoice_list = InvoiceHeaderTable::getInstance()->getListRicohTransDownload($pschool_id, $invoice_year_month, $invoice_type);

        //assign array status of file base on language
        $request->offsetSet('request_status',Constants::$request_status[session()->get('school.login.language')]);

        //return redirect('ricohTransProc');
        return view('School.Invoice.Ricoh.download',compact('request','this_screen','invoice_list','file_list'));
    }

    /**
     * @param Request $request (invoice_year_month is mandatory)
     *
     * return view to upload file for ricoh_transfer method by default (there is no upload file)
     * process and show result of file
     */
    public function ricohTransUpload(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }
        $this_screen='ricoh_trans_upload';

        $invoice_year_month = $request->invoice_year_month;
        $pschool_id = session()->get('school.login.id');
        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);

        // DEFAULT : do not have upload file so return upload form
        if(!$request->offsetExists('upload_file') || empty($request->file('upload_file'))){
            // UPLOAD_STATUS['DEFAULT'] : return 0 -> default view with upload form
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['DEFAULT']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        //PROCESSING FILE

        //get file basic info

        $file = $request->file('upload_file');
        $file_name = $file->getClientOriginalName();
        $file_size = $file->getClientSize();
        $tmp_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_trans/tmp");
        $request->upload_file->move($tmp_path,$file->getClientOriginalName());
        $upload_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_trans/upload");
        $file = $tmp_path.'/'.$file_name;
        // set path and file name to session
        if(session()->exists(self::SESSION_UPLOAD_NAME)){
            session()->forget(self::SESSION_UPLOAD_NAME);
        }
        session()->put(self::SESSION_UPLOAD_NAME,$file_name);

        //check file size is smaller than minimum size or not
        if( $file_size < (self::HEADER_RECORD_LEN + self::TRAILER_RECORD_LEN + self::END_RECORD_LEN) ){
            $error = array(
                    'error_code'=>'001',
                    'error_msg' => 'file_size_error_define_less_than'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        //check file size diff is match or not
        $diff = $file_size - (self::HEADER_RECORD_LEN + self::TRAILER_RECORD_LEN + self::END_RECORD_LEN);
        if( ($diff / self::DATA_RECORD_LEN < 1 || $diff % self::DATA_RECORD_LEN > 0) && (($diff+2) / self::DATA_RECORD_LEN < 1 || ($diff+2) % self::DATA_RECORD_LEN > 0) ){
            $error = array(
                    'error_code'=>'002',
                    'error_msg' => 'file_size_error_data_record_size'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        //open file and check has errors or not
        $file_tmp = fopen($file, "r");
        //dd($file_tmp);
        if(!$file_tmp){
            $error = array(
                    'error_code'=>'003',
                    'error_msg' => 'file_open_error'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        //read file
        $helper = InvoiceHelper::getInstance();
        $line_cnt = 0;
        $data_record = array();
        while($f_data = fgets($file_tmp)){
            $line_cnt++;

            // SHISからUTF-8へ変換
            $f_data = mb_convert_encoding($f_data, "UTF-8", "SJIS");

            // 	データ区分取得
            $data_type = mb_substr($f_data, 0, 1);
            switch($data_type){
                case 1:
                    $header_record = $helper->getSplitUploadFile($f_data, $data_type);	// ヘッダーレコードのマッピング
                    if( empty($header_record) ){
                        $error = array(
                                'error_code'=>'011',
                                'error_msg' => 'header_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    break;
                case 2:
                    $data_record_data   = $helper->getSplitUploadFile($f_data, $data_type);	// データレコードのマッピング
                    if( empty($data_record_data) ){
                        $error = array(
                                'error_code'=>'012',
                                'error_msg' => 'data_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    $data_record[]      = $data_record_data;
                    break;
                case 8:
                    $trailer_record     = $helper->getSplitUploadFile($f_data, $data_type);	// トレーラレコードのマッピング
                    if( empty($trailer_record) ){
                        $error = array(
                                'error_code'=>'013',
                                'error_msg' => 'trailer_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    break;
                case 9:
                    $end_record         = $helper->getSplitUploadFile($f_data, $data_type);	// エンドレコードのマッピング
                    if( empty($trailer_record) ){
                        $error = array(
                                'error_code'=>'014',
                                'error_msg' => 'end_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    break;
                default:
                    $error = array(
                            'error_code'=>'015',
                            'error_msg' => 'unknown_record_error',
                            'line'=>$line_cnt
                    );
                    $request->offsetSet('errors',$error);
                    $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                    return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    break;
            }
        }
        // check file is processed or file is process (is exitst in upload folder)

        $target_file_path = $upload_path.'/'.$file_name;
        if(File::exists($target_file_path)) {
            $error = array(
                    'error_code'=>'017',
                    'error_msg' => 'file_is_processed'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        $request_row = InvoiceRequestTable::getinstance()->getRow($where=array("pschool_id" => $pschool_id, 'processing_filename'=>$file_name, 'status_flag = 1 OR status_flag = 2'));
        if( empty($request_row) || count($request_row) < 1 ){
            $error = array(
                    'error_code'=>'016',
                    'error_msg' => 'file_is_not_processed'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        //(3)対象塾のデータかチェック（銀行コードと口座番号）
        // check bank account
        $pschool_bank = PaymentMethodBankRelTable::getInstance()->getPschoolBankByMethod($pschool_id, Constants::$PAYMENT_TYPE['TRAN_RICOH']);

        $bankRows_id = 0;
        if( $pschool_bank['bank_type'] == 1){
            if( $header_record[8] == $pschool_bank['bank_code'] && $header_record[13] == $pschool_bank['bank_account_number']){
                $bankRows_id = $pschool_bank['id'];
            }
        } else {
            if( $header_record[8] == 9900 && $header_record[13] == mb_substr($pschool_bank['post_account_number'], 0, 7)){
                $bankRows_id = $pschool_bank['id'];
            }
        }

        if( $bankRows_id < 1 ){

            $error = array(
                    'error_code'=>'021',
                    'error_msg' => 'data_other_school_error'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }
        // (4)トレーラーレコード
        if( $trailer_record[30] != count($data_record)){
            // データ件数不一致
            $error = array(
                    'error_code'=>'022',
                    'error_msg' => 'trailer_record_number_mismatch'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        // 引落日
        $withdrawal_date = mb_substr($header_record[7],0,2). "-" . mb_substr($header_record[7],2,2);
        $request->offsetSet('withdrawal_date',$withdrawal_date);
        //Result display
        $data_display = array();
        // (5)データの突き合せ

        foreach ($data_record as $data_item){

            // invoice_requestテーブル
            $request_str = mb_substr($data_item[26], 5, 15);
            $request_str = ltrim($request_str, "0");
            $request_id  = intval($request_str, 10);

            $total_amount = 0;
            $header_ids = array();
            // 対象データ存在 invoice_request検索
            $request_rec = InvoiceRequestTable::getInstance()->getList($where=array('processing_filename'=>$file_name, 'request_id'=>$request_id, '(status_flag = 1 OR status_flag = 2)'), $order=array('id'));

            if( !empty($request_rec) && count($request_rec) > 0){
                // 対象あり
                // 振込金額が同じかチェックする
                foreach ($request_rec as $request_item){
                    $total_amount += intval($request_item['amount'], 10);
                    $header_ids[] = $request_item['invoice_header_id'];
                }
                if( $total_amount != intval($data_item[24], 10)){
                    // 引落金額が合わない
                    $error = array(
                            'error_code'=>'032',
                            'error_msg' => 'withdrawal_amount_difference,'.'request_id:'. $request_id . ':' . $total_amount . ":" . intval($data_item[24],10),
                    );
                    $request->offsetSet('errors',$error);
                    $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                    return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                }
            } else {
                // 対象レコードなし
                $error = array(
                        'error_code'=>'031',
                        'error_msg' => 'target_data_not_exist,'. $request_id,
                );
                $request->offsetSet('errors',$error);
                $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                // old system will add errors to an array so will be continue
                // new system we break out and return view;
                //continue;
            }

            foreach ($header_ids as $header_id){
                // invoice_headerテーブルとinvoice_itemテーブル
                $invoiceRows = InvoiceHeaderTable::getInstance()->getInvoiceHeader_Item($pschool_id, $header_id);

                $disp_data = array();
                $bFirst = true;
                foreach ($invoiceRows as $row_item){
                    if( $bFirst ){
                        $disp_data['id']           = $row_item['id'];
                        $disp_data['parent_id']    = $row_item['parent_id'];
                        $disp_data['parent_name']  = $row_item['parent_name'];
                        $disp_data['result_code']  = $data_item[27];
                        $disp_data['result_msg']   = Constants::$zengin_status[session()->get('school.login.language')][(intval($data_item[27],10) * -1)];
                        $disp_data['amount']       = intval($data_item[24],10);
                        $disp_data['invoice_year_month'] = sprintf("%s-%s", mb_substr($row_item['invoice_year_month'], 0,4), mb_substr($row_item['invoice_year_month'], 5, 2));
                        $bFirst = false;

                        $disp_data['active_flag']  = $row_item['active_flag'];
                        $disp_data['workflow_status']  = $row_item['workflow_status'];
                        $disp_data['invoice_type']  = $row_item['invoice_type'];
                        $disp_data['parent_invoice_type']  = $row_item['parent_invoice_type'];
                        $disp_data['register_date']  = $row_item['register_date'];
                    }

                    $item_data = array();
                    $item_data['student_id']      = $row_item['student_id'];
                    $item_data['student_no']      = $row_item['student_no'];
                    $item_data['student_name']    = $row_item['student_name'];
                    $categorys = Constants::$dispSchoolCategory;
                    $item_data['school_category'] = empty($row_item['school_category'])? '':$categorys[$row_item['school_category']];
                    $item_data['school_year']     = empty($row_item['school_year'])? '':$row_item['school_year'] . Constants::$header[session()->get('school.login.language')]['year'];

                    $disp_data['item'][] = $item_data;
                }
            }

            $data_display[] = $disp_data;
        }

        // (6)トレーラレコード編集
        $trailer_disp_data = array();
        $trailer_disp_data['total_cnt']     = intval($trailer_record[30], 10);
        $trailer_disp_data['amount']        = intval($trailer_record[31], 10);
        $trailer_disp_data['success_cnt']   = intval($trailer_record[32], 10);
        $trailer_disp_data['success_amout'] = intval($trailer_record[33], 10);
        $trailer_disp_data['fail_cnt']      = intval($trailer_record[34], 10);
        $trailer_disp_data['fail_ammount']  = intval($trailer_record[35], 10);

        //
        //dd($request->withdrawal_date);
        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_SUCCESS']);
        return view('School.Invoice.Ricoh.upload',compact('request','this_screen','data_display','trailer_disp_data'));

    }

    /** process file and update DB
     * @param Request $request
     */
    public function ricohTransUploadComplete(Request $request){

        $this_screen = "ricoh_trans_upload";
        $pschool_id = session()->get('school.login.id');
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        $invoice_type = Constants::$PAYMENT_TYPE['TRAN_RICOH'];
        view()->share('heads',$count_invoice);
        //check file is exists on system or not
        if( !session()->exists(self::SESSION_UPLOAD_NAME )) {
            $error = array(
                'error_code'=>'041',
                'error_msg' => 'target_upload_file_not_exist'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        $upload_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_trans/upload");
        $tmp_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_trans/tmp");
        $file_path = $tmp_path.'/'.session()->get(self::SESSION_UPLOAD_NAME);

        //open file and check has errors or not
        $file = fopen($file_path, "r");
        if(!$file){
            $error = array(
                    'error_code'=>'003',
                    'error_msg' => 'file_open_error'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        //read file
        $helper = InvoiceHelper::getInstance();
        $line_cnt = 0;
        $data_record = array();
        while($f_data = fgets($file)){
            $line_cnt++;

            // SHISからUTF-8へ変換
            $f_data = mb_convert_encoding($f_data, "UTF-8", "SJIS");

            // 	データ区分取得
            $data_type = mb_substr($f_data, 0, 1);
            switch($data_type){
                case 1:
                    $header_record = $helper->getSplitUploadFile($f_data, $data_type);	// ヘッダーレコードのマッピング
                    if( empty($header_record) ){
                        $error = array(
                                'error_code'=>'011',
                                'error_msg' => 'header_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    break;
                case 2:
                    $data_record_data   = $helper->getSplitUploadFile($f_data, $data_type);	// データレコードのマッピング
                    if( empty($data_record_data) ){
                        $error = array(
                                'error_code'=>'012',
                                'error_msg' => 'data_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    $data_record[]      = $data_record_data;
                    break;
                case 8:
                    $trailer_record     = $helper->getSplitUploadFile($f_data, $data_type);	// トレーラレコードのマッピング
                    if( empty($trailer_record) ){
                        $error = array(
                                'error_code'=>'013',
                                'error_msg' => 'trailer_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    break;
                case 9:
                    $end_record         = $helper->getSplitUploadFile($f_data, $data_type);	// エンドレコードのマッピング
                    if( empty($trailer_record) ){
                        $error = array(
                                'error_code'=>'014',
                                'error_msg' => 'end_record_error',
                                'line'=>$line_cnt
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                    break;
                default:
                    $error = array(
                            'error_code'=>'015',
                            'error_msg' => 'unknown_record_error',
                            'line'=>$line_cnt
                    );
                    $request->offsetSet('errors',$error);
                    $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                    return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    break;
            }
        }

        // check file is processed
        $file_name = session()->get(self::SESSION_UPLOAD_NAME);
        $target_file_path = $upload_path.'/'.$file_name;
        if(File::exists($target_file_path)) {
            $error = array(
                    'error_code'=>'017',
                    'error_msg' => 'file_is_processed'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        $request_row = InvoiceRequestTable::getinstance()->getRow($where=array("pschool_id" => $pschool_id, 'processing_filename'=>$file_name, 'status_flag = 1 OR status_flag = 2'));
        if( empty($request_row) || count($request_row) < 1 ){
            $error = array(
                    'error_code'=>'016',
                    'error_msg' => 'file_is_not_processed'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        //(3)対象塾のデータかチェック（銀行コードと口座番号）
        // check bank account
        $pschool_bank = PaymentMethodBankRelTable::getInstance()->getPschoolBankByMethod($pschool_id, Constants::$PAYMENT_TYPE['TRAN_RICOH']);

        $bankRows_id = 0;
        if( $pschool_bank['bank_type'] == 1){
            if( $header_record[8] == $pschool_bank['bank_code'] && $header_record[13] == $pschool_bank['bank_account_number']){
                $bankRows_id = $pschool_bank['id'];
            }
        } else {
            if( $header_record[8] == 9900 && $header_record[13] == mb_substr($pschool_bank['post_account_number'], 0, 7)){
                $bankRows_id = $pschool_bank['id'];
            }
        }

        if( $bankRows_id < 1 ){

            $error = array(
                    'error_code'=>'021',
                    'error_msg' => 'data_other_school_error'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        // (4)トレーラーレコード
        if( $trailer_record[30] != count($data_record)){
            // データ件数不一致
            $error = array(
                    'error_code'=>'022',
                    'error_msg' => 'trailer_record_number_mismatch'
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }

        // 引落日
        $withdrawal_date = mb_substr($header_record[7],0,2). "-" . mb_substr($header_record[7],2,2);
        $request->offsetSet('withdrawal_date',$withdrawal_date);
        //Result display
        $data_display = array();

        // (5)データの突き合せ
        $invoiceRequestTable = InvoiceRequestTable::getInstance();
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();

        $invoiceHeaderTable->beginTransaction();

        try{
            foreach ($data_record as $data_item){

                // invoice_requestテーブル
                $request_str = mb_substr($data_item[26], 5, 15);
                $request_str = ltrim($request_str, "0");
                $request_id  = intval($request_str, 10);

                $total_amount = 0;
                $header_ids = array();
                // 対象データ存在 invoice_request検索
                $request_rec = InvoiceRequestTable::getinstance()->getList($where=array('processing_filename'=>$file_name, 'request_id'=>$request_id, '(status_flag = 1 OR status_flag = 2)'), $order=array('id'));
                if( !empty($request_rec) && count($request_rec) > 0){
                    // 対象あり
                    // 振込金額が同じかチェックする
                    foreach ($request_rec as $request_item){
                        $total_amount += intval($request_item['amount'], 10);
                        $header_ids[] = $request_item['invoice_header_id'];
                    }
                    if( $total_amount != intval($data_item[24], 10)){
                        // 引落金額が合わない
                        $invoiceHeaderTable->rollBack();
                        $error = array(
                                'error_code'=>'032',
                                'error_msg' => 'withdrawal_amount_difference,'.'request_id:'. $request_id . ':' . $total_amount . ":" . intval($data_item[24],10),
                        );
                        $request->offsetSet('errors',$error);
                        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                        return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    }
                } else {
                    // 対象レコードなし
                    $invoiceHeaderTable->rollBack();
                    $error = array(
                            'error_code'=>'031',
                            'error_msg' => 'target_data_not_exist,'. $request_id,
                    );
                    $request->offsetSet('errors',$error);
                    $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
                    return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
                    // old system will add errors to an array so will be continue
                    // new system we break out and return view;
                    //continue;
                }

                foreach ($header_ids as $header_id){
                    // invoice_headerテーブルとinvoice_itemテーブル
                    $invoiceRows = InvoiceHeaderTable::getInstance()->getInvoiceHeader_Item($pschool_id, $header_id);

                    $disp_data = array();
                    $bFirst = true;
                    foreach ($invoiceRows as $row_item){
                        if( $bFirst ){
                            $disp_data['id']           = $row_item['id'];
                            $disp_data['parent_id']    = $row_item['parent_id'];
                            $disp_data['parent_name']  = $row_item['parent_name'];
                            $disp_data['result_code']  = $data_item[27];
                            $disp_data['result_msg']   = Constants::$zengin_status[session()->get('school.login.language')][(intval($data_item[27],10) * -1)];
                            $disp_data['amount']       = intval($data_item[24],10);
                            $disp_data['invoice_year_month'] = sprintf("%s-%s", mb_substr($row_item['invoice_year_month'], 0,4), mb_substr($row_item['invoice_year_month'], 5, 2));
                            $bFirst = false;
                        }

                        $item_data = array();
                        $item_data['student_id']      = $row_item['student_id'];
                        $item_data['student_no']      = $row_item['student_no'];
                        $item_data['student_name']    = $row_item['student_name'];
                        $categorys = Constants::$dispSchoolCategory;
                        $item_data['school_category'] = empty($row_item['school_category'])? '':$categorys[$row_item['school_category']];
                        $item_data['school_year']     = empty($row_item['school_year'])? '':$row_item['school_year'] . Constants::$header[session()->get('school.login.language')]['year'];

                        $disp_data['item'][] = $item_data;

                        //-----------------------------------------------------
                        // 情報更新 invoice_header
                        //-----------------------------------------------------
                        $invoiceRow = $invoiceHeaderTable->getRow($where=array("pschool_id" => $pschool_id, 'id'=>$row_item['id']));
                        if( $data_item[27] == 0 ){
                            $invoiceRow['is_recieved']  = 1;			// 入金済み
                            $invoiceRow['paid_date']    = date('Y-') . $withdrawal_date;
                            $invoiceRow['update_date']  = date('Y-m-d H:i:s');
                            $invoiceRow['invoice_type'] = 2;			// 口座引落
                            $invoiceRow['workflow_status'] = 31;		// 入金済み
                            $invoiceRow['deposit_invoice_type'] = Constants::$PAYMENT_TYPE['TRAN_RICOH'];

                        } else if( $invoiceRow['is_recieved'] == 1 && $invoiceRow['paid_date'] != null){
                            // 引落前に入金済み

                            // 何も処理しない

                        } else {
                            // 引落できなかった
                            $invoiceRow['is_recieved']  = intval($data_item[27],10) * -1;
                            // $invoiceRow['is_requested'] = 3;
                            $invoiceRow['update_date']  = date('Y-m-d H:i:s');
                            $invoiceRow['workflow_status'] = 29;		// 口座振替未完了
                            $invoiceRow['error_code'] = intval($data_item[27]);		// リコーリースからのコード
                        }
                        $invoiceHeaderTable->updateRow($invoiceRow, $where=array('id'=>$invoiceRow['id']));

                        // 2017-11-08 Toran update unpaid invoice
                        if( $data_item[27] == 0 ){
                            $this->updateDebitInvoice($invoiceRow['id'],date('Y-') . $withdrawal_date);
                        }
                    }
                    $data_display[] = $disp_data;
                }
                //-------------------------------------------------------------
                // 情報更新 invoice_request
                //-------------------------------------------------------------
                foreach($request_rec as $request_item){
                    $requestRow = $invoiceRequestTable->getRow($where=array("pschool_id" =>$pschool_id, 'id'=>$request_item['id']));
                    if( intval($data_item[27],10)  == 0 ){
                        $requestRow['status_flag'] = 3;
                    } else {
                        $requestRow['status_flag'] = intval($data_item[27], 10) * -1;
                        // $requestRow['status_flag'] = 3;
                    }
                    $requestRow['update_date'] = date('Y-m-d H:i:s');
                    $invoiceRequestTable->updateRow($requestRow, $where=array('id'=>$requestRow['id']));
                }
            }
        }catch (Exception $e){
            $invoiceHeaderTable->rollBack();
            $error = array(
                    'error_code'=>'032',
                    'error_msg' => 'withdrawal_amount_difference,'.'request_id:'. $request_id . ':' . $total_amount . ":" . intval($data_item[24],10),
            );
            $request->offsetSet('errors',$error);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh.upload',compact('request','this_screen'));
        }
        $invoiceHeaderTable->commit();

//          useless code ????
//        // (6)トレーラレコード編集
//        $trailer_disp_data = array();
//        $trailer_disp_data['total_cnt']     = intval($trailer_record[30], 10);
//        $trailer_disp_data['amount']        = intval($trailer_record[31], 10);
//        $trailer_disp_data['success_cnt']   = intval($trailer_record[32], 10);
//        $trailer_disp_data['success_amout'] = intval($trailer_record[33], 10);
//        $trailer_disp_data['fail_cnt']      = intval($trailer_record[34], 10);
//        $trailer_disp_data['fail_ammount']  = intval($trailer_record[35], 10);

        //move file to upload folder
        if(!$helper->moveFile($tmp_path.'/'.session()->get(self::SESSION_UPLOAD_NAME), $upload_path.'/'.session()->get(self::SESSION_UPLOAD_NAME), $pschool_id , $invoice_type)){
            $invoiceHeaderTable->rollback();
            $error = array(
                    'error_code'=>'003',
                    'error_msg' => 'file_open_error',
            );
            $request->offsetSet('errors',$error);
            return $this->ricohTransUpload($request);
        }else{
            $invoiceHeaderTable->commit();
            session()->forget(self::SESSION_UPLOAD_NAME);

            return redirect()->to('/school/invoice/list?invoice_year_month='.$invoice_year_month);
        }
        //
    }

    public function cancelRicohTrans(Request $request){

        $this_screen = 'ricoh_trans';

        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }elseif(!$request->offsetExists('file_name')){
            return $this->executeList($request);
        }

        $pschool_id	= session()->get('school.login.id');
        $invoice_year_month = $request->invoice_year_month;
        $processing_file_name = $request->file_name;

        InvoiceRequestTable::getInstance()->setCancelStatusFlag($pschool_id, $processing_file_name);

        return redirect()->to('/school/invoice/ricohTransProc?invoice_year_month='.$invoice_year_month);
    }

    public function validate_custom_rules(Request $request){

        $rules = array();

        if(isset($request->custom_item)){
            foreach ($request->custom_item as $key => $value){
                foreach ($value as $k=>$item){
                    $rules['custom_item.*.*.name'] = 'required';
                    $rules['custom_item.*.*.price'] = 'required|numeric';
                }
            }
        }

        return $rules;
    }

    public function validate_custom_messages(Request $request){
        $messages = array();

        if(isset($request->custom_item)){
            foreach ($request->custom_item as $key => $value) {
                foreach ($value as $k=>$item) {
                    $messages['custom_item.*.*.name.required'] = "enter_summary_title";
                    $messages['custom_item.*.*.price.required'] = "enter_amount_title";
                    $messages['custom_item.*.*.price.numeric'] = "enter_value_amount_title";
                }
            }
        }

        return $messages;

    }

    public function processItemListAfterEdit($data,$request){

        $arr_remove_custom = array();
        $cond = array(
                'invoice_id' => $data['id'],
                'active_flag' => 1
        );

        $item_list = InvoiceItemTable::getInstance()->getList($cond);
        $res = array();
        $sum_discount_price = 0;
        $amount = 0;
        $group_item = array();
        $custom_index = 0;
        foreach ($item_list as $k=>$item) {

            if (!isset($item["student_id"])) {
                $res["discount_id"][] = $item["invoice_adjust_name_id"];
                $res["discount_name"][] = $item["item_name"];
                $item["unit_price"] = floor($item["unit_price"]);
                $res["discount_price"][] = $item["unit_price"];
                $sum_discount_price += intval($item["unit_price"]);
                if(isset($item['payment_method'])){
                    $res["class_payment_method"][$item["student_id"]][] = Constants::$invoice_type[session()->get('school.login.lang_code')][$item['payment_method']] ;
                }
                if(!empty($item['due_date'])) {
                    $date = strtotime($item['due_date']);
                    $res["class_due_date"][$item["student_id"]][] = date('n', $date) . "月" . date('j', $date) . "日";
                }
                $group_item['discount_item'][$item["student_id"]][]=$item;

            } elseif(isset($item["class_id"])) {
                $res["class_id"][$item["student_id"]][] = $item["class_id"];
                $res["class_name"][$item["student_id"]][] = $item["item_name"];
                $res["class_price"][$item["student_id"]][] = $item["unit_price"];
                if(isset($item['payment_method'])){
                    $res["class_payment_method"][$item["student_id"]][] = Constants::$invoice_type[session()->get('school.login.lang_code')][$item['payment_method']] ;
                }
                if(!empty($item['due_date'])) {
                    $date = strtotime($item['due_date']);
                    $res["class_due_date"][$item["student_id"]][] = date('n', $date) . "月" . date('j', $date) . "日";
                }

                if(!isset($request['class_except'][$item["student_id"]][$k])){
                    $amount += $item["unit_price"];
                }else{
                    $res["_class_except"][$item["student_id"]][$k] = $request['class_except'][$item["student_id"]][$k];
                }

                $group_item['class_item'][$item["student_id"]][]=$item;
            } elseif(isset($item["course_id"])) {
                $res["course_id"][$item["student_id"]][] = $item["course_id"];
                $res["course_name"][$item["student_id"]][] = $item["item_name"];
                $res["course_price"][$item["student_id"]][] = $item["unit_price"];

                $res["_course_except"][$item["student_id"]][] = $item["except_flag"];
                if(!isset($request['course_except'][$item["student_id"]][$k])){
                    $amount += $item["unit_price"];
                }else{
                    $res["_course_except"][$item["student_id"]][$k] = $request['course_except'][$item["student_id"]][$k];
                }
                $group_item['course_item'][$item["student_id"]][]=$item;
            } elseif(isset($item["program_id"])) {
                $res["program_id"][$item["student_id"]][] = $item["program_id"];
                $res["program_name"][$item["student_id"]][] = $item["item_name"];
                $res["program_price"][$item["student_id"]][] = $item["unit_price"];

                $res["_program_except"][$item["student_id"]][] = $item["except_flag"];
                if(!isset($request['program_except'][$item["student_id"]][$k])){
                    $amount += $item["unit_price"];
                }else{
                    $res["_program_except"][$item["student_id"]][$k] = $request['program_except'][$item["student_id"]][$k];
                }
                $group_item['program_item'][$item["student_id"]][]=$item;
            } else {
                $res["custom_item_id"][$item["student_id"]][] = $item["id"];
                $res["custom_item_name"][$item["student_id"]][] = $item["item_name"];
                $res["custom_item_price"][$item["student_id"]][] = $item["unit_price"];

                if(!isset($request['custom_except'][$item["student_id"]][$custom_index])){
                    $amount += $item["unit_price"];
                }else{
                    $res["_custom_except"][$item["student_id"]][$custom_index] = $request['custom_except'][$item["student_id"]][$custom_index];
                }
                $group_item['custom_item'][$item["student_id"]][]=$item;
                $custom_index+=1;
            }
        }



        //process class except
        if(isset($request->class_except)){
            foreach($request->class_except as $student_id => $value){
                if(isset($res['_class_except'][$student_id])){
                    $res['_class_except'][$student_id] = $value;
                }
            }
        }
        //process event except
        if(isset($request->course_except)){
            foreach($request->course_except as $student_id => $value){
                if(isset($res['_course_except'][$student_id])){
                    $res['_course_except'][$student_id] = $value;
                }
            }
        }
        //process program except
        if(isset($request->program_except)){
            foreach($request->program_except as $student_id => $value){
                if(isset($res['_program_except'][$student_id])){
                    $res['_program_except'][$student_id] = $value;
                }
            }
        }
        //process custom except
        if(isset($request->custom_except)){
            foreach($request->custom_except as $student_id => $value){
                if(isset($res['_custom_except'][$student_id])){
                    $res['_custom_except'][$student_id] = $value;
                }
            }
        }

        //process new custom
        if(isset($request->custom_item)){
            foreach ($request->custom_item as $k=>$v){
                foreach($v as $key=>$value){
                    $res["custom_item_name"][$k][] = $value["name"];
                    $res["custom_item_price"][$k][] = $value["price"];
                    $amount += intval($value["price"]);
                }
            }
        }
        $amount = 0;

        //calculator amount again
        foreach($group_item as $k=>$type){
            if($k=='class_item'){
                foreach ($type as $student_id=> $value){
                    foreach ($value as $key=>$item){
                        if(!isset($res['_class_except'][$student_id][$key]) || $res['_class_except'][$student_id][$key]==0){
                            $amount+=$item['unit_price'];
                        }
                    }
                }
            }
            if($k=='course_item'){
                foreach ($type as $student_id=> $value){
                    foreach ($value as $key=>$item){
                        if(!isset($res['_course_except'][$student_id][$key]) || $res['_course_except'][$student_id][$key]==0){
                            $amount+=$item['unit_price'];
                        }
                    }
                }
            }
            if($k=='program_item'){
                foreach ($type as $student_id=> $value){
                    foreach ($value as $key=>$item){
                        if(!isset($res['_program_except'][$student_id][$key]) || $res['_program_except'][$student_id][$key]==0){
                            $amount+=$item['unit_price'];
                        }
                    }
                }
            }

            if($k == 'custom_item'){
                foreach ($type as $student_id=> $value){
                    foreach ($value as $key=>$item){
                        if(!isset($res['_custom_except'][$student_id][$key]) || $res['_custom_except'][$student_id][$key]==0){
                            $amount+=$item['unit_price'];
                        }
                    }
                }
            }
            if($k=='discount_item'){
                foreach ($type as $student_id=> $value){
                    foreach ($value as $key=>$item){
                        if(!isset($res['discount_item'][$student_id][$key]) || $res['discount_item'][$student_id][$key]==0){
                            $amount+=$item['unit_price'];
                        }
                    }
                }
            }

        }

        if(isset($request->custom_item)){
            foreach ($request->custom_item as $k=>$v){
                foreach($v as $key=>$value){
                    $amount += $value["price"];
                }
            }
        }
        // end calculator

        //  info of amount and tax
        $res = array_merge($res,$data);

        $tax_price = 0;
        $amount_tax = 0;

        //
        $res["sum_discount_price"] = $sum_discount_price;
        $amount += $sum_discount_price;
        $res["amount"] = $amount;
        $res['sales_tax_disp'] = $res['sales_tax_rate']*100;

        $sales_tax_rate = floatval($res["sales_tax_rate"]);
        if ($res["amount_display_type"] == "0") {
            $tax_price = floor($amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100));
            $amount_tax = $amount;
        } else {
            $tax_price = floor($amount * $sales_tax_rate);
            $amount_tax = $amount + $tax_price;
        }

        $res["tax_price"] = $tax_price;
        $res["amount_tax"] = $amount_tax;
        $res['due_date_jp'] = date('Y年m月d日',strtotime($res['due_date']));

        // cause custom item must have a student_id so get the first student in array as
        // current defautl student_id
        $res['current_student'] = $data['student_list'][0]['id'];

        $res = array_merge($item_list,$res);
        return $res;

    }

    /**
     * get current list file , if not exists any in state 1 (current send)
     * then generate new file
     * @param Request $request
     * @return ricoh transfer view
     */
    public function getZengin(Request $request) {
        $request->offsetSet('invoice_type',Constants::$PAYMENT_TYPE['TRAN_RICOH']);
        // 請求元情報取得
        $pschool = session()->get('school.login');
        $pschool_id = $pschool['id'];
        $invoice_year_month = $request->invoice_year_month;
        $helper = InvoiceHelper::getInstance();
        $invoice_type = Constants::$PAYMENT_TYPE['TRAN_RICOH'];

        // get pschool bank base on invoice type
        $pschool_bank = PaymentMethodBankRelTable::getInstance()->getPschoolBankByMethod($pschool_id, $request->invoice_type);
        if ( empty($pschool_bank) || !$request->offsetExists('invoice_ids') || !is_array($request->invoice_ids)) {
            $errors ="select_invoice_message" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohTransDownload($request);
        }

        // 締切日を過ぎているかチェック
        $curr_date = date('Y-m-d H:i:s');
        $deadline  = $this->getDeadLineOfPayment($pschool_id, $invoice_year_month, $request->invoice_type);

        if( $curr_date > $deadline ){
            $errors ="invoice_request_time_over" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohTransDownload($request);
        }

        // 当月分のダウンロードファイルが存在するか？

        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type);
        if( !empty($file_list) ){
            $errors ="file_already_exist" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohTransProcess($request);
        }

        // 引落日
        $dropdate = $helper->getDropdate();

        // フォルダなければ作成
        $file_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_trans/download");

        $req_date = empty($invoice_year_month)? date('Y-m', $dropdate) : $invoice_year_month;

        // ファイル名
        //1月a 2b 3c 4d 5e 6f 7g 8h 9i 10j 11k 12n
        $file_month = $helper->encode36(date('n', strtotime($req_date)) +9, 1);
        $file_year  = $helper->encode36(date('y', strtotime($req_date)), 2);
        $file_school = $helper->encode36($pschool_id, 3);
        $file_name = $file_month.$file_year.$file_school;

        // count total file exists
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type, null);
        $file_count = count($file_list);
        $file_name .= $helper->encode36($file_count, 2).".txt";
        // header_recoad
        $post_data = array();
        $post_data[] = $helper->getHeaderRecord($pschool, $pschool_bank, $dropdate);

        // 請求先情報取得
        $invoice_cnt = 0;
        $invoice_sum = 0;
        $req_table = InvoiceRequestTable::getInstance();

        $closingdate = $helper->getTransferDateInfo($invoice_year_month,$pschool_id, $invoice_type );

        // invoice_headerの更新＆invoice_requestに登録
        foreach ($request->invoice_ids as $ids){
            $invoice = InvoiceHeaderTable::getInstance()->getRow(array("pschool_id" => $pschool_id, 'id' =>$ids));
            $parent_bank = ParentBankAccountTable::getInstance()->getRow(array('parent_id' => $invoice['parent_id']));

            if (!empty($parent_bank)){
                // invoice_requestに登録
                $req_id = $invoice['parent_id'].date('md', $dropdate);

                // 税別表示?
                if ($invoice['amount_display_type'] == 1){
                    $receipt = floor($invoice['amount'] * (1+$invoice['sales_tax_rate']));
                }else{
                    $receipt = $invoice['amount'];
                }

                $req_table->beginTransaction();
                try{
                    $invoice['is_requested'] = 21;
                    $invoice['workflow_status'] = 21;
                    InvoiceHeaderTable::getInstance()->updateRow($invoice, array('id' => $invoice['id']));

                    $row = array();
                    if (empty($row)){
                        $row = array(
                                'processing_filename' => $file_name,
                                'pschool_id' => $invoice['pschool_id'],
                                //								'dayofwithdrawal' => date('md', $dropdate),
                                'dayofwithdrawal' => date('md', strtotime($closingdate['transfer_date'])),
                                'request_id' => $req_id,
                                'parent_id' => $invoice['parent_id'],
                                'invoice_header_id' => $ids,
                                'amount' => $receipt,
                                'request_date' => date('Y-m-d', $dropdate),
                                'status_flag' => 1,
                                //'total_cnt' => $total_cnt,
                                //'total_amount' => $total_amount,
                                'invoice_year_month' => $invoice['invoice_year_month'],
                                'result_date' => $closingdate['result_date'],
                                'deadline' => $closingdate['deadline'],
                                'register_date' => date('Y-m-d H:i:s')
                        );
                        $req_table->insertRow($row);
                    }else{
                        $row['processing_filename'] = $file_name;
                        $row['amount'] = $receipt;
                        $row['update_date'] = date('Y-m-d H:i:s');
                        $req_table->updateRow($row , array( 'id' => $row['id'] ));
                    }
                    $req_table-> commit();
                }catch (Exception $ex){
                    $req_table->rollBack();
                }
            }
        }

        // 親のIDを選別
        $parent_ids = InvoiceHeaderTable::getInstance()->getParentList($request->invoice_ids);
        foreach ($parent_ids as $key => $parent_id){
            $receipt = 0;
            $req_id = $parent_id['parent_id'].date('md', $dropdate);
            $req_list = $req_table->getList(array('pschool_id' => $invoice['pschool_id'], 'request_id' => $req_id, 'processing_filename' => $file_name));
            foreach ($req_list as $req){
                // 税別表示?
                if ($pschool['amount_display_type'] == 1){
                    //$receipt += round($req['amount'] * (1+$pschool['sales_tax_rate']), 0);
                    $receipt += $req['amount'];
                }else{
                    $receipt += $req['amount'];
                }
            }
            if ($receipt > 0){
                $parent_bank = ParentBankAccountTable::getInstance()->getRow(array('parent_id' => $req['parent_id']));
                if (!empty($parent_bank)){
                    $post_data[] = $helper->getDataRecord($req, $parent_bank, $receipt);
                    $invoice_cnt++;
                    $invoice_sum += $receipt;
                }
            }
        }
        //*/

        $post_data[] = $helper->getTrailerRecord($invoice_cnt, $invoice_sum);

        $post_data[] = $helper->getEndRecord();

        // 確認用
        $request->offsetSet('zengin_data',$post_data);

        // コード変換
        $post_code = $helper->getCode($post_data);

        // ファイルに保存
        $helper->getFile($file_path, $file_name, $post_code);

        // set filename,status and message to request
        $request->offsetSet('file_name',$file_name);
        $request->offsetSet('messages','request_form_created');
        $request->offsetSet('request_status',Constants::$request_status[session()->get('school.login.language')]);
        return $this->ricohTransProcess($request);
    }

    public function getDeadLineOfPayment($pschool_id, $invoice_year_month, $invoice_type){

        $pschool = PschoolTable::getInstance()->load($pschool_id);

        //
        if($invoice_type == Constants::$PAYMENT_TYPE['POST_RICOH']){
            $deadline = $pschool['payment_date'];
            if($deadline == 99){
                $deadline = date('Y-m-t',strtotime($invoice_year_month.'-01'));
            }
            return $deadline. $this->deadline_hours;
        }

        // get withdrawal date of payment_method and payment_agency
        $payment_type_id = array_flip(Constants::$PAYMENT_TYPE);
        $bind = array(
                $payment_type_id[$invoice_type],
                'withdrawal_date',
                $pschool_id
        );
        $sql = "SELECT pms.default_value, pmd.item_value
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
            $withdrawal_day = 27;
        }else{
            $default_value = explode(";", $res['default_value']);
            $value = $default_value[$res['item_value'] - 1];
            $payment_date = explode(":", $value);
            $withdrawal_day = $payment_date[1];
        }

        // get info from closing_day_table with $withdrawal_day
        $bind2 = array(
                date('Y-m', strtotime($invoice_year_month. "-01". "-1 month")), // duedate month  = transfer_ month -1
                $withdrawal_day
        );
        $date_sql = "SELECT transfer_month , transfer_date, deadline
                      FROM closing_day WHERE transfer_month = ? 
                      AND transfer_day = ?
                      ORDER BY deadline ASC LIMIT 1 ";
        $closeDay = ClosingDayTable::getInstance()->fetch($date_sql, $bind2);
        if(!empty($closeDay)){
            return $closeDay['deadline']. $this->deadline_hours;
        }else{
            return date('Y-m-', strtotime($invoice_year_month. "-01". "-1 month")).$withdrawal_day. $this->deadline_hours;
        }

    }
    // mail send and pdf export , update stage2 to stage 3
    public function executeMailSend(Request $request){
        //TODO get list invoice selected stage 2 and update to stage 3
        $lan = $this->lan;
        $str="";
        $arr_pdf = array();
        if(!$request->offsetExists('parent_ids')){
            $request->offsetSet('errors',"list_screen_error");
            return $this->mailSearch($request);
        }

        $invoiceHeaderTbl = InvoiceHeaderTable::getInstance();
        foreach ($request->parent_ids as $key=>$invoice_id) {

            // check parent mail method and update to invoice id
            InvoiceHeaderTable::getInstance()->updateInvoiceMailAnnouce($invoice_id);

            //
            $request->offsetSet('id', $invoice_id);
            $header = $this->checkEditParam($request);

            //check if amount <= 0 -> update directly to status 4 :completed

            if($header['amount'] <= 0){
                $header_upd = array(
                        "id" => $header["id"],
                        "paid_date" => date('Y-m-d H:i:s'),
                        "is_recieved" => 1,
                        "update_admin" => session()->get('school.login.id'),
                        "workflow_status" => 31,
                );

                $invoiceHeaderTbl->save($header_upd);
                continue;
            }


            // Toran check if workflow_status > 21 (stage3) -> pass
            if($header['workflow_status'] <= 21){
                if ($header["is_established"] != "1") {
                    // 確定済みでない場合はメール通知できないので、元の画面に戻す。
                    Log::warning($lan::get('cannot_email_notify_message'));
                    continue;		//$this->redirect_history(0);
                }

                $parentStudent = ParentTable::getInstance()->getParentStudentListById(session('school.login')['id'], $header["parent_id"]);
                if (empty($parentStudent)) {
                    // 存在しない保護者なのでTOPに戻す。
                    continue;
                }

                if($header["mail_announce"] == 0) {// 郵送の請求書の時
                    ////////// 保護者の支払方法で、請求書のフォーマットが変わる   $parentRow['invoice_type'];

                    $parentStudent = $invoiceHeaderTbl->getParentStudentListByInvoiceId($header["pschool_id"], $header["id"], $header["parent_id"]);
                    if (!empty($parentStudent)) {

//                        $data = $this->setPrintDataFromDb($header, $parentStudent);
//                        $str .= $this->createPdf( $data ,$key);
                        $arr_pdf[] =  $header["id"];
                    }

                    try {
                        $invoiceHeaderTbl->beginTransaction();

                        $header = array(
                                "id" => $header["id"],
                                "announced_date" => date('Y-m-d H:i:s'),
                                "is_requested" => 1,
                                "update_admin" => session('school.login')['login_account_id'],
                                "workflow_status" => 11,
                        );

                        $invoiceHeaderTbl->save($header);
                        $invoiceHeaderTbl->commit();
                    } catch (Exception $e) {
                        $invoiceHeaderTbl->rollBack();
                    }


                } elseif($header["mail_announce"] == 1)	{// メール通知の請求書の時

                    try {
                        $invoiceHeaderTbl->beginTransaction();

                        // メール通知済みにする。
                        $header_upd = array(
                                "id"                => $header["id"],
                                "is_mail_announced" => "1",
                                "is_requested"      => 1,
                                "announced_date"    => date('Y-m-d H:i:s'),
                                "update_admin"      => session('school.login')['login_account_id'],
                                "workflow_status"   => 11,
                        );

                        $invoiceHeaderTbl->save($header_upd);

                        // メール情報テーブルにメール情報を登録する。
                        $mail_message = MailMessageTable::getInstance()->getActiveRow(['type'=>1, 'relative_ID'=>$header["id"], 'parent_id'=>$parentStudent["id"]]);

                        $message = array();
                        if (empty($mail_message)) {
                            $message_key = md5($this->generateRandomString(64));
                            $message['type']         = 1;
                            $message['message_key']  = $message_key;
                            $message['relative_ID']  = $header["id"];
                            $message['pschool_id']   = session('school.login')['id'];
                            $message['parent_id']    = $parentStudent["id"];
                            $message['total_send']   = 0; // first send
                            $message_id = MailMessageTable::getInstance()->save($message);
                        } else {
                            $message_id = $mail_message['id'];
                        }
                        $invoiceHeaderTbl->commit();
                        $this->sendMailReceiptType($message_id);

                        //                    email_notify_to_invoice
                    } catch (Exception $ex) {
                        $invoiceHeaderTbl->rollBack();
                        Log::error($lan::get('process_invoice_error_message'));
                    }
                }
            }
        }

//        if ($str != "") {
//            $options = new Options();
//            $options->setIsRemoteEnabled(true);
//            $domPdf = new Dompdf($options);
//            $domPdf->loadHtml($str);
//            $domPdf->render();
//            $domPdf->stream('invoicePDF.pdf', array("compress"=>false, "Attachment"=>false)); //array("compress"=>false, "Attachment"=>false)
//        }
        if(!empty($arr_pdf)){
            $request->offsetSet('arr_pdf',$arr_pdf);
        }
        return $this->mailSearch($request);
    }
    public function pdfExport(Request $request){
        $ids = $request->arr_header;
        $invoiceHeaderTbl = InvoiceHeaderTable::getInstance();
        $str = "";
        foreach ($ids as $key => $header_id){
            $request->offsetSet('id', $header_id);
            $header = $this->checkEditParam($request);
            $parentStudent = ParentTable::getInstance()->getParentStudentListById(session('school.login')['id'], $header["parent_id"]);
            if (empty($parentStudent)) {
                // 存在しない保護者なのでTOPに戻す。
                continue;
            }
            $parentStudent = $invoiceHeaderTbl->getParentStudentListByInvoiceId($header["pschool_id"], $header["id"], $header["parent_id"]);

            if (!empty($parentStudent)) {
                $data = $this->setPrintDataFromDb($header, $parentStudent);
                $str .= $this->createPdf( $data ,$key);
            }
        }
        if ($str != "") {
            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $domPdf = new Dompdf($options);
            $domPdf->loadHtml($str);
            $domPdf->render();
            $domPdf->stream('invoicePDF.pdf', array("compress"=>false, "Attachment"=>false)); //array("compress"=>false, "Attachment"=>false)
        }
    }
    /*
	 * 請求書編集時のパラメーターチェック処理
	 */
    private function checkEditParam($request) {
        if (!$request->has('id') || !strlen($request->id)) {
            // TOPに戻す。
            return redirect('/school/invoice');
        }

        $header = InvoiceHeaderTable::getInstance()->getActiveRow(array(
            "id"            => $request->id,
            "pschool_id"    => session('school.login')['id'],
//			"active_flag" => 1,
        ));
        if (empty($header)) {
            // 存在しない請求書なのでTOPに戻す。
            return redirect('/school/invoice');
        }
        return $header;
    }
    // ----------------StringUtils-------------------//
    /**
     * @return string
     */
    public static function generateRandomString($length) {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= self::generateRandomChar();
        }
        return $result;
    }

    /**
     * @return string
     */
    public static function generateRandomChar() {
        $r = mt_rand(0, 61);
        if ($r < 10) {
            $c = $r;
        } elseif($r >= 10 && $r < 36) {
            $r -= 10;
            $c = chr($r + ord('a'));
        } else {
            $r -= 36;
            $c = chr($r + ord('A'));
        }
        return $c;
    }
    private function createPdf( $data, $i) {
        //dd($data);
        try {
            return view('_pdf.invoice_envelope', compact( 'data'));
//            dump($dompdf);
//            $dompdf = new Dompdf($this->pdf->getOptions()); //
//            $dompdf->set_paper('A4', 'landscape');
//            $dompdf->loadHtml(view('_pdf.invoice_print', compact( 'data')));
//            $dompdf->outputHtml();
//            $dompdf->render();
//            return $dompdf->output();
//            $dompdf->stream('invoicePDF.pdf');
        } catch (Exception $e) {
            var_dump($e);
        }

//return true;
    }
    /*
     * DBからとってきたデータを印刷画面表示用に加工する。
     */
    private function setPrintDataFromDb($header, $parentStudent) {
        $data = array();
        $data = $parentStudent;
        // Toran get bank for ginkou furikomi
        if($header['invoice_type'] == Constants::$PAYMENT_TYPE['TRAN_BANK']){
            $bank_info = PaymentMethodBankRelTable::getInstance()->getListBank(session('school.login.id'),$header['invoice_type']);
            if(!empty($bank_info['list_bank'])){
                foreach ($bank_info['list_bank'] as $k => $bank){
                    if($bank['is_default_account'] == 1){
                        $data['school_bank_info'] = $bank;
                        $bank_account_type_list = ConstantsModel::$type_of_bank_account [session()->get( 'school.login.language')];
                        $data['school_bank_info']['bank_account_type'] = $bank_account_type_list[$bank['bank_account_type']];
                    }
                }
            }
        }
        //
        $item_list = InvoiceItemTable::getInstance()->getList(array('invoice_id' => $header["id"]), array("class_id", "course_id", "id"));
        $data["active_student_id"] = $parentStudent["student_list"][0]["id"];
        $data["invoice_year_month"] = $header["invoice_year_month"];

        $data["amount_display_type"] = $header["amount_display_type"];
        $data["sales_tax_rate"] = $header["sales_tax_rate"];
        $data["mail_announce"] = $header["mail_announce"];
        $data["is_established"] = $header["is_established"];
        $data["is_recieved"] = $header["is_recieved"];

        $data["school_name"] = session('school.login')['name'];
        $data["school_city"] = session('school.login')['pref_name'] . session('school.login')['city_name'];
        $data["school_address"] = session('school.login')['address'].' '.session('school.login')['building'];
        $data["address"] = session('school.login')['address'];
        $data["school_building"] = session('school.login')['building'];
        $data["school_daihyou"] = session('school.login')['daihyou'];
        $data["school_tel"] = session('school.login')['tel'];
        $data["school_mail"] = session('school.login')['mailaddress'];
        $data["school_official_position"] = session('school.login')['official_position'];
        $data["school_zipcode"] = session('school.login')['zip_code'];
        if(isset(session('school.login')['zip_code1'])){
            $data["school_zipcode1"] = session('school.login')['zip_code1'];
        }else{
            $data["school_zipcode1"] =substr($data["school_zipcode"],0,3);
            $data["school_zipcode2"] =substr($data["school_zipcode"],3,4);
        }
        if(isset(session('school.login')['zip_code2'])){
            $data["school_zipcode2"] = session('school.login')['zip_code2'];
        }
        $publish_year = date('Y') - 1988;
        $data["publish_date_y"] = $publish_year;
        $data["publish_date_m"] = date('n');
        $data["publish_date_d"] = date('j');
        $data['due_date'] = $header["due_date"];
        $due_year = substr($header["due_date"], 0, 4) - 1988;
        $data["due_date_y"] = $due_year;
        $due_month = ltrim(substr($header["due_date"], 5, 2), '0');
        $data["due_date_m"] = $due_month;
        $due_day = ltrim(substr($header["due_date"], 8, 2), '0');
        $data["due_date_d"] = $due_day;

        $bank_account = PschoolBankAccountTable::getInstance()->getListPSchoolBank(session('school.login.id'), [$data['invoice_type']], true);

        if (!empty($bank_account)) {
            $data["bank_name"] = $bank_account['bank_name'];
            $data["branch_name"] = $bank_account['branch_name'];
            $bank_account_type = $bank_account['bank_account_type'] ? ConstantsModel::$type_of_bank_account[session('school.login')['language']][$bank_account['bank_account_type']] : null;
            // $data["bank_account_number"] = ConstantsModel::$type_of_bank_account[session('school.login')['language']][$bank_account['bank_account_type']] ." ". $bank_account['bank_account_number'];
            $data["bank_account_number"] = $bank_account['bank_account_number'] ? $bank_account_type ." ". $bank_account['bank_account_number'] : $bank_account_type;
            $data["bank_account_name"] = $bank_account['bank_account_name'];
            $data["bank_account_name_kana"] = $bank_account['bank_account_name_kana'];
            if (!empty($bank_account['post_account_number']) && !empty($bank_account['post_account_name'])) {
                $data["post_account_kigou"] = $bank_account['post_account_kigou'];
                $data["post_account_number"] = $bank_account['post_account_number'];
                $data["post_account_name"] = $bank_account['post_account_name'];
            }
        }

        $data["discount_name"] = array();
        $data["discount_price"] = array();
        $data["class_id"] = array();
        $data["class_name"] = array();
        $data["class_price"] = array();
        $data["course_id"] = array();
        $data["course_name"] = array();
        $data["cource_price"] = array();
        $data["program_id"] = array();
        $data["program_name"] = array();
        $data["program_price"] = array();
        $data["custom_item_name"] = array();
        $data["custom_item_price"] = array();
        foreach ($parentStudent["student_list"] as $k => $v) {
            $data["class_id"][$v["id"]] = array();
            $data["class_name"][$v["id"]] = array();
            $data["class_price"][$v["id"]] = array();
            $data["course_id"][$v["id"]] = array();
            $data["course_name"][$v["id"]] = array();
            $data["cource_price"][$v["id"]] = array();
            $data["program_id"][$v["id"]] = array();
            $data["program_name"][$v["id"]] = array();
            $data["program_price"][$v["id"]] = array();
            $data["custom_item_name"][$v["id"]] = array();
            $data["custom_item_price"][$v["id"]] = array();
        }

        $sum_discount_price = 0;
        $amount = 0;

        foreach ($item_list as $item) {
            if (!strlen($item["student_id"])) {
                $data["discount_name"][] = $item["item_name"];
                $data["discount_price"][] = str_replace("-", "", $item["unit_price"]);
                $sum_discount_price += intval($item["unit_price"]);

            } else if (strlen($item["class_id"])) {
                $data["class_id"][$item["student_id"]][] = $item["class_id"];
                $data["class_name"][$item["student_id"]][] = $item["item_name"];
                $data["class_price"][$item["student_id"]][] = $item["unit_price"];
                $data["_class_except"][$item["student_id"]][] = $item["except_flag"];
                if(!$item["except_flag"]){
                    $amount += $item["unit_price"];
                }
            } else if (strlen($item["course_id"])) {
                $data["course_id"][$item["student_id"]][] = $item["course_id"];
                $data["course_name"][$item["student_id"]][] = $item["item_name"];
                $data["course_price"][$item["student_id"]][] = $item["unit_price"];
                $data["_course_except"][$item["student_id"]][] = $item["except_flag"];
                if(!$item["except_flag"]) {
                    $amount += $item["unit_price"];
                }
            } else if (strlen($item["program_id"])) {
                $data["program_id"][$item["student_id"]][] = $item["program_id"];
                $data["program_name"][$item["student_id"]][] = $item["item_name"];
                $data["program_price"][$item["student_id"]][] = $item["unit_price"];
                $data["_program_except"][$item["student_id"]][] = $item["except_flag"];
                if(!$item["except_flag"]) {
                    $amount += $item["unit_price"];
                }
            } else {
                $data["custom_item_name"][$item["student_id"]][] = $item["item_name"];
                $data["custom_item_price"][$item["student_id"]][] = $item["unit_price"];
                $data["_custom_except"][$item["student_id"]][] = $item["except_flag"];
                if(!$item["except_flag"]) {
                    $amount += $item["unit_price"];
                }
            }
        }

        if (empty($data["discount_name"])) {
            $data["discount_name"][] = "";
            $data["discount_price"][] = "";
        }

        foreach ($data["custom_item_name"] as $student_id => $custom_item_name_list) {
            if (empty($custom_item_name_list)) {
                $data["custom_item_name"][$student_id][] = "";
                $data["custom_item_price"][$student_id][] = "";
            }
        }

        $data["sum_discount_price"] = $sum_discount_price;
        $amount += $sum_discount_price;
        $data["amount"] = $amount;

        $tax_price = 0;
        $amount_tax = 0;
        $sales_tax_rate = floatval($data["sales_tax_rate"]);
        if ($data["amount_display_type"] == "0") {
            $tax_price = floor($amount * ($sales_tax_rate * 100) / (($sales_tax_rate * 100) + 100));
            $amount_tax = $amount;
        } else {
            $tax_price = floor($amount * $sales_tax_rate);
            $amount_tax = $amount + $tax_price;
        }

        $data["tax_price"] = $tax_price;
        $data["amount_tax"] = $amount_tax;

        return $data;
    }

    /**
     * main view for ricoh convenient store process
     */
    public function ricohConvProcess(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }

        $this_screen='ricoh_conv';

        $pschool_id	= session()->get('school.login.id');
        $invoice_year_month = $request->invoice_year_month;
        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);
        //

        $transfer_list = InvoiceRequestTable::getInstance()->getAccountTranserList($pschool_id, $invoice_year_month, $cond = null ,Constants::$PAYMENT_TYPE['CONV_RICOH']);
        //dd($transfer_list);
        $process_flg = 0;
        foreach ($transfer_list as $key => $transfer){
            if($transfer['status_flag'] == 3 || $transfer['status_flag'] == 1 ){
                $process_flg = $transfer['status_flag'];
                break 1;
            }
        }
        //assign array status of file base on language
        $request->offsetSet('request_status',Constants::$request_status[session()->get('school.login.language')]);

        //get the newest month
        $newest_invoice_month = $this->get_newsest_invoice_month($pschool_id);

        return view("School.Invoice.Ricoh_conv.ricoh_conv",compact('transfer_list','request','this_screen','process_flg','newest_invoice_month'));
    }
    /**
     * @param Request $request (invoice_year_month is mandatory)
     * return view to export file for ricoh_convenient method
     */
    public function ricohConvDownload(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }

        $this_screen='ricoh_conv_download';

        $invoice_year_month = $request->invoice_year_month;
        $pschool_id = session()->get('school.login.id');
        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);
        //
        $invoice_type = Constants::$PAYMENT_TYPE['CONV_RICOH'];
        $file_list = array();
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type);

        $invoice_list = InvoiceHeaderTable::getInstance()->getListRicohTransDownload($pschool_id, null, $invoice_type);

        //assign array status of file base on language
        $request->offsetSet('request_status',Constants::$request_status[session()->get('school.login.language')]);
        return view('School.Invoice.Ricoh_conv.download',compact('request','this_screen','invoice_list','file_list'));
    }

    public function getKonbini(Request $request){

        $request->offsetSet('invoice_type',Constants::$PAYMENT_TYPE['CONV_RICOH']);
        $invoice_year_month = $request->invoice_year_month;
        $pschool_id = session()->get('school.login.id');
        $helper = InvoiceHelper::getInstance();
        $invoice_type = Constants::$PAYMENT_TYPE['CONV_RICOH'];

        // check if none ids
        if (!$request->offsetExists('invoice_ids') || !is_array($request->invoice_ids)) {
            $errors ="select_invoice_message" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohConvDownload($request);
        }

        //check if overtime
        $curr_date = date('Y-m-d H:i:s');
        $deadline  = $this->getDeadLineOfPayment($pschool_id, $invoice_year_month, $invoice_type);

        if( $curr_date > $deadline ){
            $errors ="invoice_request_time_over" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohConvDownload($request);
        }

        // check if file exists
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type);
//        if( !empty($file_list) ){
//            $errors ="file_already_exist" ;
//            $request->offsetSet('errors',$errors);
//            return $this->ricohConvProcess($request);
//        }

        // GENERATE FILE NAME BASE ON DATE
        $file_month = $helper->encode36(date('yn', strtotime($invoice_year_month)), 3);
        $file_school = $helper->encode36($pschool_id, 3);
        $file_name = $file_month.$file_school;

        // count total file exists
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type, null);
        $file_count = count($file_list);

        $file_name .= $helper->encode36($file_count, 2);
        //dd($file_list,$file_name);
        // GET DATA OF INVOICE
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoiceItemTable = InvoiceItemTable::getInstance();
        $data = array();
        foreach ($request->invoice_ids as $key => $id){
            $data_parent = $invoiceHeaderTable->getDataParentStudent($id);
            $data[$key] = $invoiceHeaderTable->load($id);
            $data[$key]['item_list'] = $invoiceItemTable->getActiveList(array('invoice_id'=> $id));
            $data[$key] =  array_merge($data[$key],$data_parent);
        }

        // process data to row for export
        $data = $helper->processDataRicohConv($data);

        // Export csv file

        $dest_path = storage_path("app/uploads/school/" . session()->get('school.login.id')."/ricoh_conv/download");

        if($helper->exportShiftJs($dest_path, $data, $file_name)){
            //success -> create record in invoice_request
            $closingdate = $helper->getTransferDateInfo($invoice_year_month,$pschool_id, $invoice_type );
            $req_table = InvoiceRequestTable::getInstance();
            $dropdate = $helper->getDropdate();

            foreach ($request->invoice_ids as $ids){
                $invoice = InvoiceHeaderTable::getInstance()->getRow(array("pschool_id" => $pschool_id, 'id' =>$ids));

                $req_id = $invoice['parent_id'].date('md', $dropdate);

                // 税別表示?
                if ($invoice['amount_display_type'] == 1){
                    $receipt = floor($invoice['amount'] * (1+$invoice['sales_tax_rate']));
                }else{
                    $receipt = $invoice['amount'];
                }

                $req_table->beginTransaction();
                try{
                    $invoice['is_requested'] = 21;
                    $invoice['workflow_status'] = 21;
                    InvoiceHeaderTable::getInstance()->updateRow($invoice, array('id' => $invoice['id']));

                    $row = array();
                    if (empty($row)){
                        $row = array(
                                'processing_filename' => $file_name,
                                'pschool_id' => $invoice['pschool_id'],
                                //								'dayofwithdrawal' => date('md', $dropdate),
                                'dayofwithdrawal' => date('md', strtotime($closingdate['transfer_date'])),
                                'request_id' => $req_id,
                                'parent_id' => $invoice['parent_id'],
                                'invoice_header_id' => $ids,
                                'amount' => $receipt,
                                'request_date' => date('Y-m-d', $dropdate),
                                'status_flag' => 1,
                                //'total_cnt' => $total_cnt,
                                //'total_amount' => $total_amount,
                                'invoice_year_month' => $invoice_year_month,
                                'result_date' => $closingdate['result_date'],
                                'deadline' => $closingdate['deadline'],
                                'register_date' => date('Y-m-d H:i:s')
                        );
                        $req_table->insertRow($row);
                    }else{
                        $row['processing_filename'] = $file_name;
                        $row['amount'] = $receipt;
                        $row['update_date'] = date('Y-m-d H:i:s');
                        $req_table->updateRow($row , array( 'id' => $row['id'] ));
                    }
                    $req_table-> commit();
                }catch (Exception $ex){
                    $req_table->rollBack();
                }
            }
            return $this->ricohConvDownload($request);
        }
        return $this->ricohConvProcess($request);
    }

    public function cancelRicohConv(Request $request){

        $this_screen = 'ricoh_conv';

        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }elseif(!$request->offsetExists('file_name')){
            return $this->executeList($request);
        }

        $pschool_id	= session()->get('school.login.id');
        $invoice_year_month = $request->invoice_year_month;
        $processing_file_name = $request->file_name;

        InvoiceRequestTable::getInstance()->setCancelStatusFlag($pschool_id, $processing_file_name);

        return redirect()->to('/school/invoice/ricohConvProc?invoice_year_month='.$invoice_year_month);
    }

    /**
     * @param Request $request (invoice_year_month is mandatory)
     *
     * return view to upload file for ricoh_conv method by default (there is no upload file)
     * process and show result of file
     */
    public function ricohConvUpload(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }
        $this_screen='ricoh_conv_upload';

        $invoice_year_month = $request->invoice_year_month;
        $pschool_id = session()->get('school.login.id');
        $invoice_type = Constants::$PAYMENT_TYPE['CONV_RICOH'];

        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);

        // DEFAULT : do not have upload file so return upload form
        if(!$request->offsetExists('upload_file') || empty($request->file('upload_file'))){
            // UPLOAD_STATUS['DEFAULT'] : return 0 -> default view with upload form
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['DEFAULT']);
            return view('School.Invoice.Ricoh_conv.upload',compact('request','this_screen'));
        }

        // PROCESSING FILE
        //get file basic info

        $file = $request->file('upload_file');
        $file_name = $file->getClientOriginalName();
        $file_size = $file->getClientSize();
        $tmp_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_conv/tmp");
        $request->upload_file->move($tmp_path,$file->getClientOriginalName());
        $upload_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_conv/upload");
        $file = $tmp_path.'/'.$file_name;

        // set path and file name to session
        if(session()->exists(self::SESSION_UPLOAD_NAME)){
            session()->forget(self::SESSION_UPLOAD_NAME);
        }
        session()->put(self::SESSION_UPLOAD_NAME,$file_name);

        $helper = InvoiceHelper::getInstance();
        $data_record = array();
        $data_record = $helper->processFileRicohConv($file);

        if(!is_array($data_record)){
            $request->offsetSet('errors',$data_record);
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh_conv.upload',compact('request','this_screen'));
        }

        // preview data back from imported file
        $preview_data = array();
        $preview_data['success_cnt'] = 0;
        $preview_data['success_amout'] = 0;

        $records = array();
        foreach($data_record as $k => $data){
            $header_id = $data[6];
            $paid_date = date("Y-m-d", strtotime($data[4]));
            $invoiceRows = InvoiceHeaderTable::getInstance()->getInvoiceHeader_Item($pschool_id, $header_id);

            if(empty($invoiceRows)){
                continue;
            }

            $disp_data = array();
            $bFirst = true;
            foreach ($invoiceRows as $row_item){
                if( $bFirst ){
                    $disp_data['id']           = $row_item['id'];
                    $disp_data['parent_id']    = $row_item['parent_id'];
                    $disp_data['parent_name']  = $row_item['parent_name'];
                    $disp_data['result_msg']   = $this->lan->get('dp_deposited');
                    $disp_data['amount']       = $row_item['amount'];
                    $preview_data['success_amout'] += $row_item['amount'];
                    $preview_data['success_cnt'] +=1;
                    $disp_data['invoice_year_month'] = sprintf("%s-%s", mb_substr($row_item['invoice_year_month'], 0,4), mb_substr($row_item['invoice_year_month'], 5, 2));
                    $bFirst = false;

                    $disp_data['active_flag']  = $row_item['active_flag'];
                    $disp_data['workflow_status']  = $row_item['workflow_status'];
                    $disp_data['is_nyukin']  = $row_item['is_nyukin'];
                    $disp_data['entry_name']  = $row_item['entry_name'];
                    $disp_data['invoice_type']  = $row_item['invoice_type'];
                    $disp_data['parent_invoice_type']  = $row_item['parent_invoice_type'];
                    $disp_data['paid_date']  = $paid_date;
                }

                $item_data = array();
                $item_data['student_id']      = $row_item['student_id'];
                $item_data['student_no']      = $row_item['student_no'];
                $item_data['student_name']    = $row_item['student_name'];
                $categorys = Constants::$dispSchoolCategory;
                $item_data['school_category'] = empty($row_item['school_category'])? '':$categorys[$row_item['school_category']];
                $item_data['school_year']     = empty($row_item['school_year'])? '':$row_item['school_year'] . Constants::$header[session()->get('school.login.language')]['year'];

                $disp_data['item'][] = $item_data;
            }

//            $record[$k]['parent_name'] = $parent['parent_name'];
//            $record[$k]['amount'] = $header['amount'];
//            $receive_date = \DateTime::createFromFormat('Ymd', $data[11]);
//            $record[$k]['receive_date'] = $receive_date->format('Y年m月d日');
//
//            $preview_data['amount'] += $header['amount'];
            if(!empty($disp_data)){
                $records[] =  $disp_data;
            }

        }

        $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_SUCCESS']);
        return view('School.Invoice.Ricoh_conv.upload',compact('request','this_screen','preview_data','records','file_name'));

    }
    public function ricohConvUploadComplete(Request $request){

        // lost session or missing file_name_tmp
        if(!$request->offsetExists('import_tmp_name') || session()->get(self::SESSION_UPLOAD_NAME) != $request->import_tmp_name ){
            $request->offsetSet('errors',"session_error");
            $request->offsetSet('upload_state',$this->UPLOAD_STATUS['UPLOAD_ERROR']);
            return view('School.Invoice.Ricoh_conv.upload',compact('request','this_screen'));
        }
        // basic data
        $pschool_id = session()->get('school.login.id');
        $helper = InvoiceHelper::getInstance();
        $file_name = $request->import_tmp_name;
        $tmp_path = storage_path('app/uploads/school/' . $pschool_id."/ricoh_conv/tmp");

        // get file from tmp and process
        $file = $tmp_path.'/'.$file_name;

        $data_record = array();
        $data_record = $helper->processFileRicohConv($file);
        // update DB transfered
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $ids = array ();
        try{
            $invoiceHeaderTable->beginTransaction();
            $total_amount_success = 0;
            $invoice_month = date('Y-m');

            foreach($data_record as $k => $data){


                $invoice_header = $invoiceHeaderTable->load($data[6]);
                $total_amount_success += $invoice_header['amount'];
                $invoice_month = $invoice_header['invoice_year_month'];

                // already transfered -> pass to next
                if($invoice_header['workflow_status'] == 31 && !empty($invoice_header['paid_date']) && $invoice_header['is_recieved'] != 0){
                    continue;
                }

                // update invoice header
                $ids[] = $data[6];
                $invoiceRow = array();
                $invoiceRow['id'] = $invoice_header['id'];
                $invoiceRow['is_recieved']  = 1;			// 入金済み

                $receive_date = \DateTime::createFromFormat('YmdHis', $data[4].$data[5].'01');
                $invoiceRow['paid_date'] = $receive_date->format('Y-m-d H-i-s');
                $invoiceRow['update_date']  = date('Y-m-d H:i:s');
                $invoiceRow['workflow_status'] = 31;		// 入金済み
                $invoiceRow['deposit_invoice_type'] = Constants::$PAYMENT_TYPE['CONV_RICOH'];

                $invoiceHeaderTable->save($invoiceRow);

                // 2017-11-08 Toran update unpaid invoice
                $this->updateDebitInvoice($invoice_header['id'],$receive_date->format('Y-m-d H-i-s'));
            }
//            // insert file info into invoice request
//            // not neccessary
//            if(count($ids) > 0){
//                $file_row = array(
//                    'processing_filename' => $file_name,
//                    'pschool_id' => $pschool_id,
//                    // 'dayofwithdrawal' => ,
//                    'status_flag' => 3, // import successed
//                    'invoice_year_month' => $invoice_month,
//                    'amount' => $total_amount_success,
//
//                );
//                InvoiceRequestTable::getInstance()->insertRow($file_row);
//            }

            $invoiceHeaderTable->commit();
        }catch (Exception $e){
            $invoiceHeaderTable->rollBack();
        }

        // success message
        $request->offsetSet('messages',"import_conv_success,".count($ids));
        return $this->ricohConvProcess($request);
    }

    /**return view for import and export processing txt file of yuucho
     * @param Request $request
     */
    public function ricohPostProcess(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }

        $this_screen='ricoh_post';

        $pschool_id	= session()->get('school.login.id');
        $invoice_type = Constants::$PAYMENT_TYPE['POST_RICOH'];
        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);
        //

        $transfer_list = InvoiceRequestTable::getInstance()->getAccountTranserList($pschool_id, $invoice_year_month, $cond = null, $invoice_type);
        $process_flg = 0;
        foreach ($transfer_list as $key => $transfer){
            if($transfer['status_flag'] == 3 || $transfer['status_flag'] == 1 ){
                $process_flg = $transfer['status_flag'];
                break 1;
            }
        }

        //assign array status of file base on language
        $request->offsetSet('request_status',Constants::$request_status[session()->get('school.login.language')]);

        //get newest month
        $newest_invoice_month = $this->get_newsest_invoice_month($pschool_id);

        return view("School.Invoice.Ricoh_post.ricoh_post",compact('transfer_list','request','this_screen','process_flg','newest_invoice_month'));
    }

    /**
     * @param Request $request (invoice_year_month is mandatory)
     * return view to export file for ricoh_transfer method
     */
    public function ricohPostDownload(Request $request){
        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }

        $this_screen='ricoh_post_download';
        $invoice_type = Constants::$PAYMENT_TYPE['POST_RICOH'];
        $pschool_id = session()->get('school.login.id');
        //
        $invoice_year_month = $request->invoice_year_month;
        $count_invoice = $this->getInvoiceStatusMenu($request);
        view()->share('heads',$count_invoice);
        //

        $file_list = array();
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type);

        $invoice_list = InvoiceHeaderTable::getInstance()->getListRicohTransDownload($pschool_id, $invoice_year_month, $invoice_type);

        //assign array status of file base on language
        $request->offsetSet('request_status',Constants::$request_status[session()->get('school.login.language')]);
        return view('School.Invoice.Ricoh_post.download',compact('request','this_screen','invoice_list','file_list'));
    }

    public function getYuucho(Request $request){

        $request->offsetSet('invoice_type',Constants::$PAYMENT_TYPE['POST_RICOH']);
        $invoice_year_month = $request->invoice_year_month;
        $pschool_id = session()->get('school.login.id');
        $helper = InvoiceHelper::getInstance();
        $invoice_type = Constants::$PAYMENT_TYPE['POST_RICOH'];

        // check if none ids
        if (!$request->offsetExists('invoice_ids') || !is_array($request->invoice_ids)) {
            $errors ="select_invoice_message" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohPostDownload($request);
        }

        //check if overtime
        $curr_date = date('Y-m-d H:i:s');
        $deadline  = $this->getDeadLineOfPayment($pschool_id, $invoice_year_month, $invoice_type);

        if( $curr_date > $deadline ){
            $errors ="invoice_request_time_over" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohPostDownload($request);
        }
        // check if file exists
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type);
        if( !empty($file_list) ){
            $errors ="file_already_exist" ;
            $request->offsetSet('errors',$errors);
            return $this->ricohPostDownload($request);
        }

        // GENERATE FILE NAME BASE ON DATE
        $file_month = $helper->encode36(date('ny', strtotime($invoice_year_month)), 3);
        $file_school = $helper->encode36($pschool_id, 3);

        $file_name = $file_month.$file_school;
        // count total file exists
        $file_list = InvoiceRequestTable::getInstance()->getFileList($pschool_id, $invoice_year_month, $invoice_type, null);
        $file_count = count($file_list);
        $file_name .= $helper->encode36($file_count, 2);

        // GET DATA OF INVOICE
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoiceItemTable = InvoiceItemTable::getInstance();
        $data = array();
        foreach ($request->invoice_ids as $key => $id){
            $data_parent = $invoiceHeaderTable->getDataParentStudent($id);
            $data[$key] = $invoiceHeaderTable->load($id);
            $data[$key]['item_list'] = $invoiceItemTable->getActiveList(array('invoice_id'=> $id));
            $data[$key] =  array_merge($data[$key],$data_parent);
        }

        // process data to row for export
        $data = $helper->processDataRicohConv($data);

        // Export csv file

        $dest_path = storage_path("app/uploads/school/" . session()->get('school.login.id')."/ricoh_post/download");

        if($helper->exportShiftJs($dest_path, $data, $file_name)){
            //success -> create record in invoice_request
            //$closingdate = $helper->getTransferDateInfo($invoice_year_month,$pschool_id, $invoice_type );
            $req_table = InvoiceRequestTable::getInstance();
            $dropdate = $helper->getDropdate();

            foreach ($request->invoice_ids as $ids){
                $invoice = InvoiceHeaderTable::getInstance()->getRow(array("pschool_id" => $pschool_id, 'id' =>$ids));

                $req_id = $invoice['parent_id'].date('md', $dropdate);

                // 税別表示?
                if ($invoice['amount_display_type'] == 1){
                    $receipt = floor($invoice['amount'] * (1+$invoice['sales_tax_rate']));
                }else{
                    $receipt = $invoice['amount'];
                }

                $req_table->beginTransaction();
                try{
                    $invoice['is_requested'] = 21;
                    $invoice['workflow_status'] = 21;
                    InvoiceHeaderTable::getInstance()->updateRow($invoice, array('id' => $invoice['id']));

                    $row = array();
                    if (empty($row)){
                        $row = array(
                                'processing_filename' => $file_name,
                                'pschool_id' => $invoice['pschool_id'],
                                //'dayofwithdrawal' => date('md', strtotime($closingdate['transfer_date'])),
                                'request_id' => $req_id,
                                'parent_id' => $invoice['parent_id'],
                                'invoice_header_id' => $ids,
                                'amount' => $receipt,
                                'request_date' => date('Y-m-d', $dropdate),
                                'status_flag' => 1,
                                //'total_cnt' => $total_cnt,
                                //'total_amount' => $total_amount,
                                'invoice_year_month' => $invoice['invoice_year_month'],
                                //'result_date' => $closingdate['result_date'],
                                //'deadline' => $closingdate['deadline'],
                                'deadline' => date('Y-m-d', $dropdate),
                                'register_date' => date('Y-m-d H:i:s')
                        );
                        $req_table->insertRow($row);
                    }else{
                        $row['processing_filename'] = $file_name;
                        $row['amount'] = $receipt;
                        $row['update_date'] = date('Y-m-d H:i:s');
                        $req_table->updateRow($row , array( 'id' => $row['id'] ));
                    }
                    $req_table-> commit();
                }catch (Exception $ex){
                    $req_table->rollBack();
                }
            }
            die;
        }
        return $this->ricohPostProcess($request);
    }

    public function cancelRicohPost(Request $request){

        $this_screen = 'ricoh_post';

        if(!$request->offsetExists('invoice_year_month')){
            return redirect()->to('/school/invoice');
        }elseif(!$request->offsetExists('file_name')){
            return $this->executeList($request);
        }

        $pschool_id	= session()->get('school.login.id');
        $invoice_year_month = $request->invoice_year_month;
        $processing_file_name = $request->file_name;

        InvoiceRequestTable::getInstance()->setCancelStatusFlag($pschool_id, $processing_file_name);

        return redirect()->to('/school/invoice/ricohPostProc?invoice_year_month='.$invoice_year_month);
    }

    //
    public function deposit(Request $request) {
        // Init search data

        if ($request->isMethod('POST')) {
            $this->_initSearchDataFromSession($this->_deposit_search_item, $this->_deposit_search_session_key);
        }

        $student_types = MStudentTypeTable::getInstance()->getListStudentTypeByPschool(session()->get('school.login.id'));
        $classes = ClassTable::getInstance()->getClasses([session()->get('school.login.id')]);
        $courses = CourseTable::getInstance()->getCourses([session()->get('school.login.id')]);
        $programs = ProgramTable::getInstance()->getProgramList(session()->get('school.login.id'));
        $invoice_types = PaymentMethodPschoolTable::getInstance()->getListPaymentMethod(session()->get('school.login.id'));
        $invoice_background_color = Constants::invoice_background_color;
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];

        // Search data
        $filters = $request->all();
        $filters['pschool_id'] = session()->get('school.login.id');
        if (!$request->offsetExists('workflow_status') || in_array(11, $request->workflow_status)) {
            // Default workflow status is none deposit
            $filters['workflow_status'] = array_unique(array_merge($request->get('workflow_status', array()), [11, 21, 29]));
            $request->offsetSet('workflow_status', $filters['workflow_status']);
        }

        if (!$request->offsetExists('student_type_ids')) {
            // Default search all student types
            $student_type_ids = array_column($student_types, 'id');
            $filters['student_type_ids'] = $student_type_ids;
            $request->offsetSet('student_type_ids', $student_type_ids);
        }

        if (!$request->offsetExists('invoice_type_ids')) {
            // Default search all invoice types
            $invoice_type_ids = array_column($invoice_types, 'payment_method_value');
            $filters['invoice_type_ids'] = $invoice_type_ids;
            $request->offsetSet('invoice_type_ids', $invoice_type_ids);
        } elseif ($request->invoice_type_ids === null) {
            $request->offsetUnset('invoice_type_ids');
        }

//        if (!$request->offsetExists('invoice_year_month') && session('school.login.nyukin_before_month')) {
        if (!$request->offsetExists('invoice_year_month_from') && session('school.login.nyukin_before_month')) {
            // Default invoice year month from pschool setting
            $invoice_year_month = date('Y-m', strtotime(date('Y-m-d') . '-'. session('school.login.nyukin_before_month') .' month'));
            $filters['invoice_year_month_from'] = $invoice_year_month;
            $request->offsetSet('invoice_year_month_from', $invoice_year_month);
        }

        if (in_array(31, $request->workflow_status)) {
            // Search by deposit invoice type when workflow status is deposited
            $filters['filter_by_deposit_invoice_type'] = true;
        }
        if (in_array(11, $request->workflow_status)) {
            // Search by invoice type when workflow status is non deposit
            $filters['filter_by_invoice_type'] = true;
        }

        $invoices = InvoiceHeaderTable::getInstance()->getDeposits($filters);

        // Process for prev - next in detail page
        $prev_next_invoice_header_ids = [];
        $prev_next_invoice_ids=$request->input('invoice_header_ids');

        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        foreach ($invoices as $k=> $invoice) {

            $prev_next_invoice_header_ids[] = $invoice['id'];
            $invoices[$k] = $invoiceHeaderTable->getClassCourseInfo($invoice);

        }

        session()->put('prev_next_deposit_invoice_header_ids', $prev_next_invoice_header_ids);
        return view("School.Invoice.Deposit.index",compact('request','student_types', 'classes', 'courses', 'programs', 'invoices', 'invoice_types','invoice_background_color','invoice_type'));
    }

    public function depositProcess(Request $request) {

        // Redirect to first page when no invoice selected on process deposit all
        if ($request->action == 1 && !$request->offsetExists('invoice_header_ids')) {
            return redirect('/school/invoice/deposit');
        }
        if(!$request->offsetExists('invoice_id')) {
            $session_invoice_header_ids = session('prev_next_deposit_invoice_header_ids');
            $session_invoice_header_ids=$request->input ('invoice_header_ids');
            session()->put('prev_next_deposit_invoice_header_ids', $session_invoice_header_ids);
        }

        // Get common data
        $invoice_types  = PaymentMethodPschoolTable::getInstance()->getListPaymentMethod(session()->get('school.login.id'));
        $deposit_types  = Constants::$deposit_type[session()->get('school.login.language')];
        $defaultProviso = session()->get('school.login.proviso');
        $pass_required = session()->get('school.login.nyukin_pass_required');
        $invoice_types = PaymentMethodPschoolTable::getInstance()->getListPaymentMethod(session()->get('school.login.id'));
        $invoice_background_color = Constants::invoice_background_color;
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];
        $invoices = [];
        $invoice = [];
        
        // Get invoice header id for process
        $id = null;
        if (!$request->offsetExists('invoice_header_ids')&&!$request->offsetExists('invoice_id')) {
            // No checked invoice header exist
            $id = $session_invoice_header_ids[0];
            $request->offsetSet('invoice_header_ids', [$id]);
        } else if ($request->offsetExists('invoice_header_ids')&& $request->action == 1) {
            foreach ($request->invoice_header_ids as $id){
                $invoices[] = InvoiceHeaderTable::getInstance()->getDeposits([
                    'id' => $id,
                    'pschool_id' => session()->get('school.login.id')], true);
            }
            $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
            foreach ($invoices as $k => $invoice_item) {
                $invoices[$k] = $invoiceHeaderTable->getClassCourseInfo($invoice_item);
            }
        } else {
            if ($request->action == 2) {
                // Single process
                if(!$request->offsetExists('invoice_id')) {
                    $list_id = session ('prev_next_deposit_invoice_header_ids');
                    $id = $list_id[0];
                } else {
                    $id=$request->input ('invoice_id');
                }
                if ($id) {
                    $invoice = InvoiceHeaderTable::getInstance()->getDeposits(['id' => $id, 'pschool_id' => session()->get('school.login.id')], true);
                    $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
                    $invoice = $invoiceHeaderTable->getClassCourseInfo($invoice);
                    if ($invoice) {
                        if($invoice['paid_date']==null) {
                            $invoice['deposit_invoice_type']=$invoice['invoice_type'];
                        }
                        if (!$request->offsetExists('paid_date')) {
                            $request->offsetSet('paid_date', $invoice['paid_date']);
                        }
                        if (!$request->offsetExists('invoice_type')) {
                            $request->offsetSet('invoice_type', $invoice['invoice_type']);
                        }
                    }
                }
            }
        }

        if ($request->action == 2) {
            // Get prev - next request for single process
            $session_invoice_header_ids=session('prev_next_deposit_invoice_header_ids');
            $current_invoice_header_key = array_search($id, $session_invoice_header_ids);
            $current_invoice_header_key = $current_invoice_header_key ? $current_invoice_header_key : 0;
            if (isset($session_invoice_header_ids[$current_invoice_header_key + 1])) {
                $request->offsetSet('next_id', $session_invoice_header_ids[$current_invoice_header_key + 1]);
            }
            if (isset($session_invoice_header_ids[$current_invoice_header_key - 1])) {
                $request->offsetSet('prev_id', $session_invoice_header_ids[$current_invoice_header_key - 1]);
            }
            if(session()->has('receipt_id')) {
                session()->forget('receipt_id');
                
            }
            if($request->offsetExists('id_receipt')) {
                session()->put('receipt_id',$request->id_receipt);
            }
        }
        return view("School.Invoice.Deposit.process", compact('invoice_types', 'deposit_types', 'invoice', 'defaultProviso','pass_required','invoices','invoice_background_color','invoice_type'));
    }

    public function depositEndProcess(Request $request) {
        // Get current login account password
        $account = LoginAccountTable::getInstance()->load(session()->get('login_account_id'));

        // Validation
        $rules = [
            'deposit_invoice_type' => ['required']
        ];

        if ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) {
            // Only validate when status is not cancel
            $rules['paid_date'] = ['required', 'date'];
        }
        $messages = [
            'paid_date.required'            => 'dp_err_paid_date_required',
            'paid_date.date'                => 'dp_err_paid_date_date',
            'deposit_invoice_type.required' => 'dp_err_invoice_type_required',
            'password_md5.required'         => 'dp_password_required',
            'password_md5.in'               => 'dp_password_in',
        ];
        if($request->offsetExists('pass_required') && $request->pass_required == 1 ){
            $rules['password_md5'] = ['required', Rule::in([$account['login_pw']])];
        }
        try {
            if (!$request->offsetExists('invoice_header_ids')&&$request->input ('action')==1) {
                throw new Exception();
            }
            $request->offsetSet('password_md5', md5($request->password));
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $request->offsetSet('errors', $validator->errors()->all());
                $request->offsetUnset('password');
                throw new Exception();
            }
            InvoiceHeaderTable::getInstance()->beginTransaction();
            $paid_date = ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? $request->paid_date : null;
            if ($request->input ('action')==1) {
                foreach ($request->invoice_header_ids as $id) {
                    $update = array(
                        'id'                   => $id,
                        'is_recieved'          => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? 1 : 0,
                        'workflow_status'      => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? 31 : 11, 'paid_date'            => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? $request->paid_date : null,
                        'deposit_invoice_type' => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? $request->deposit_invoice_type : 1,
                        'proviso'              => $request->proviso
                                               );
                    $success = InvoiceHeaderTable::getInstance()->save($update);
                    if (!$success) {
                        throw new Exception();
                    }
                   // Update event payment status
                  $invoice_items = InvoiceItemTable::getInstance()->getActiveList(array('invoice_id'=>$id));
                  $this->updateStatusEntry($invoice_items, $request->deposit_invoice_type, $paid_date);
                   if ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) {
                       $this->updateDebitInvoice($id, $request->paid_date);
                   }
                }
            } else {
                    $update = array(
                        'id'                   => $request->input ('invoice_id'),
                        'is_recieved'          => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? 1 : 0,
                        'workflow_status'      => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? 31 : 11,
                        'paid_date'            => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? $request->paid_date : null,
                        'deposit_invoice_type' => ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) ? $request->deposit_invoice_type : 1,
                        'proviso'              => $request->proviso
                    );
                $success = InvoiceHeaderTable::getInstance()->save($update);
                if (!$success) {
                    throw new Exception();
                }
                // Update event payment status
                $invoice_items = InvoiceItemTable::getInstance()->getActiveList(array('invoice_id'=>$request->input ('invoice_id')));
                $this->updateStatusEntry($invoice_items, $request->deposit_invoice_type, $paid_date);
                if ($request->deposit_invoice_type != Constants::DEPOSIT_CANCEL) {
                    $this->updateDebitInvoice($request->input ('invoice_id'), $request->paid_date);
                }
            }
            InvoiceHeaderTable::getInstance()->commit();
            $request->session()->flash('deposit_status', $this->lan->get('update_success'));
            if ($request->receipt) {
                // For export PDF
                $request->offsetSet('deposit_receipt', true);
            }
            if($request->input('action')==2) {
                $list_id_invoice=session('prev_next_deposit_invoice_header_ids');
                $current_invoice_key = array_search($request->invoice_id,$list_id_invoice );
                if(isset($list_id_invoice[$current_invoice_key+1])) {
                    $next_id=$list_id_invoice[$current_invoice_key+1];
                    $url_redirect='school/invoice/deposit_process?action=2&invoice_id='.$next_id.'&deposit_receipt='.$request->deposit_receipt.'&id_receipt='.$request->input ('invoice_id');
                    return redirect($url_redirect);
                }
              
            }
            return $this->depositProcess($request);
        } catch (Exception $e) {
            InvoiceHeaderTable::getInstance()->rollBack();
            return $this->depositProcess($request);
        }
    }

    public function depositExport(Request $request) {
        $filters = $request->all();
        $filters['pschool_id'] = session()->get('school.login.id');
        if (in_array(11, $request->workflow_status)) {
            // Default workflow status is none deposit
            $filters['workflow_status'] = array_unique(array_merge($request->get('workflow_status', array()), [11, 21, 29]));
        }
        if (in_array(31, $filters['workflow_status'])) {
            // Search by deposit invoice type when workflow status is deposited
            $filters['filter_by_deposit_invoice_type'] = true;
        }
        if (in_array(11, $filters['workflow_status'])) {
            // Search by invoice type when workflow status is non deposit
            $filters['filter_by_invoice_type'] = true;
        }

        if ($request->invoice_type_ids === null) {
            $request->offsetUnset('invoice_type_ids');
        }

        $invoices = InvoiceHeaderTable::getInstance()->getDeposits($filters);
        // Invoice type and Deposit type
        $invoice_types = Constants::$invoice_type[session()->get('school.login.language')] + Constants::$deposit_type[session()->get('school.login.language')];

        $export_data = [[
            'No.' => 'No.',
            'parent_name.' => $this->lan->get('dp_parent_name'),
            'invoice_year_month' => $this->lan->get('dp_invoice_year_month'),
            'amount' => $this->lan->get('dp_amount'),
            'paid_date' => $this->lan->get('dp_paid_date'),
            'invoice_type' => $this->lan->get('dp_invoice_type'),
            'announced_date' => $this->lan->get('dp_announced_date')
        ]];

        foreach ($invoices as $key => $invoice) {
            $export_data[] = [
                'No.' => $key + 1,
                'parent_name' => $invoice['parent_name'], //請求先
                'invoice_year_month' => date('Y年m月', strtotime($invoice['invoice_year_month'] . '-01')) . $this->lan->get('dp_invoice_name'),
                'amount' => $invoice['amount'],
                'paid_date' => $invoice['paid_date'] ? date('Y-m-d', strtotime($invoice['paid_date'])) : '',
                'invoice_type' => ($invoice['deposit_invoice_type']) ? $invoice_types[$invoice['deposit_invoice_type']] : $invoice_types[$invoice['invoice_type']],
                'announced_date' => $invoice['deposit_reminder_date'] ? date('Y-m-d', strtotime($invoice['deposit_reminder_date'])) : '',
            ];
        }

        $data ['header'] = null;
        $data ['dataFile'] = $export_data;
        $currentTime = Carbon::now();
        $fileName = '入金情報_' . date('YmdHis', strtotime($currentTime));
        // change $is_crypt and  $crypt_key, must get from request
        $is_crypt = null;
        if (session('school.login.is_zip_csv') == 1) {
            $is_crypt = true;
        }
        $crypt_key = ConstantsModel::CRYPT_KEY_NUM;
        $data ['char_code'] = $request->mode;

        // get info for send mail
        $member_type = strtoupper(session('hierarchy.role'));
        $to_email = null;
        $user_name = null;
        $data_send_mail = array ();
        if (array_search($member_type, ConstantsModel::$member_type) == 4) {
            $parentInfo = DB::table('parent')->where('id', session('school.login.origin_id'))->first();
            $to_email = $parentInfo->parent_mailaddress1;
            $user_name = $parentInfo->parent_name;
        } else {
            $studentInfo = DB::table('student')->where('id', session('school.login.origin_id'))->first();
            $to_email = $studentInfo['mailaddress'];
            $user_name = $studentInfo['student_name'];
        }

        $data ['data_send_mail'] = $data_send_mail;
        $data ['user_name'] = $user_name;
        $data ['to_email'] = $to_email;
        $data ['file_name'] = $fileName;

        CSVExport::exportZipCSV($data, $is_crypt, $crypt_key);
    }

    public function depositReceipt(Request $request){
        
        $filters = $request->all();
        $filters['pschool_id'] = session()->get('school.login.id');
        if($request->input('action')==2) {
            if($request->offsetExists('receipt_id')) {
                if(!empty($request->receipt_id)) {
                    $invoice_id[0]=$request->input('receipt_id');
                }
            } else {
                $invoice_id[0]=$request->input('invoice_id');
            }
            $filters['invoice_header_ids']= $invoice_id;
        }
        $invoices = InvoiceHeaderTable::getInstance()->getDeposits($filters);

        $html_str = "";
        foreach ($invoices as $invoice) {

            InvoiceHeaderTable::getInstance()->beginTransaction();
            $receipt_number = $this->generateReceiptNumber($invoice['id']);
            $invoice['receipt_number'] = $receipt_number;

            $receipt_number = substr($receipt_number,0,8)."-".substr($receipt_number,8,5);

            $invoice['receipt_count']+= 1;
            if($invoice['receipt_count'] < 10){
                $receipt_number .= '-0'.$invoice['receipt_count'];
            }else{
                $receipt_number .= '-'.$invoice['receipt_count'];
            }
            $invoice['receipt_no'] = $receipt_number;


            InvoiceHeaderTable::getInstance()->save($invoice);
            InvoiceHeaderTable::getInstance()->commit();
            $html_str .= view("School.Invoice.Deposit.receipt", compact('invoice'));
        }

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dom_pdf = new Dompdf($options);
        $dom_pdf->loadHtml($html_str);
        $dom_pdf->render();
        $dom_pdf->stream('invoicePDF.pdf', array("compress" => false, "Attachment" => false));
    }

    public function depositReminder(Request $request) {

        if (!$request->offsetExists('invoice_header_ids')) {
            return redirect('school/invoice/deposit');
        }

        $parents = InvoiceHeaderTable::getInstance()->getParentByInvoiceHeader($request->invoice_header_ids);
        $mail_template_type = ConstantsModel::$MAIL_TEMPLATE_TYPE[$this->current_lang];
        return view("School.Invoice.Deposit.reminder", compact('mail_template_type', 'parents'));
    }

    public function depositSendReminder(Request $request) {
        if (!$request->offsetExists('invoice_header_ids')) {
            return redirect('school/invoice/deposit');
        }

        try {
            // Validation
            $rules = [
                'title'   => ['required'],
                'content' => ['required']
            ];
            $messages = [
                'title.required'   => 'dp_err_mail_content',
                'content.required' => 'dp_err_mail_title',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $request->offsetSet('errors', $validator->errors()->all());
                throw new Exception();
            }

            // Get data
            $invoiceHeaderTable = InvoiceHeaderTable::getInstance();

            $filters = $request->all();
            $filters['pschool_id'] = session()->get('school.login.id');
            $invoices = $invoiceHeaderTable->getDeposits($filters);
            foreach ($invoices as $k => $invoice) {
                // Check deposited data exist
                if ($invoice['workflow_status'] == 31) {
                    $request->offsetSet('errors', ['dp_err_send_deposited_reminder']);
                    throw new Exception();
                }
                // Add request data to each invoice header
                $invoices[$k] = $invoice + $request->all();

                if ($request->has('schedule_flag_update')) {

                    // if schedule setting : save content to invoice header
                    $invoices[$k]['deposit_reminder_title'] = $request['title'];
                    $invoices[$k]['deposit_reminder_content'] = $request['content'];
                    $invoices[$k]['deposit_reminder_footer'] = $request['footer'];
                    $invoiceHeaderTable ->save($invoices[$k]);

                    // create record in mailmessage
                    $this->storeMailMessage($invoices[$k]);
                }
            }
            if ($request->has('schedule_flag_update')) { // setting time auto send mail
                //save mail message
                //$this->storeMailMessage();
            }else{
                $success = $this->sendMailDepositReminderType($invoices, false);
                if (!$success) {
                    throw new Exception();
                }
//                $this->sendMailDepositReminderBatch(1256,true);
            }

            $request->session()->flash('deposit_status', $this->lan->get('update_success'));
            return redirect('school/invoice/deposit');
        } catch (Exception $e) {
            return $this->depositReminder($request);
        }
    }
    /*
     * generate 1 empty record when there is no record of invoice exists (select month base on logic of school's payment type)
     */
    public function generateEmptyInvoiceMonth($request){

        $pschool_id = session()->get('school.login.id');
        $invoice_year_month = $this->get_newsest_invoice_month($pschool_id);

        // generate empty array base on month
        return $this->emptyInvoiceMonth($invoice_year_month);
    }
    private function emptyInvoiceMonth($invoice_year_month){
        $res = array();
        $res[$invoice_year_month] = array();
        return $res;
    }

    private function updateDebitInvoice($header_id, $paid_date = null){

        $bind[] = $paid_date != null ? $paid_date : date('Y-m-d H:i:s');
        $bind[] = $header_id;

        $sql = "UPDATE invoice_header 
                SET 
                    is_established = 1,
                    is_recieved  = 1,
                    workflow_status = 31,
                    paid_date = ? 
                WHERE id IN ";
        $sql.= "(SELECT invoice_debit_id FROM invoice_debit 
                WHERE invoice_header_id = ? 
                AND status = 1
                AND delete_date IS NULL)";

        InvoiceDebitTable::getInstance()->execute($sql,$bind);
    }

    private function generateReceiptNumber($invoice_id){

        $sql = "SELECT id, receipt_number 
                FROM invoice_header 
                WHERE receipt_number = (SELECT MAX(receipt_number) FROM invoice_header)";

        $res = InvoiceHeaderTable::getInstance()->fetch($sql);

        if(empty($res['receipt_number'])){
            $pre = date('Ymd');
            $suff = "00001";
            $receipt_number = $pre.$suff;
            InvoiceHeaderTable::getInstance()->save(array('id'=>$invoice_id, 'receipt_number'=>$receipt_number));

            return $receipt_number;

        }else{
            $date = substr($res['receipt_number'],0,8);
            if($date != date('Ymd')){
                $pre = date('Ymd');
                $suff = "00001";
                $receipt_number = $pre.$suff;
                InvoiceHeaderTable::getInstance()->save(array('id'=>$invoice_id, 'receipt_number'=>$receipt_number));

                return $receipt_number;
            }else{
                $current = InvoiceHeaderTable::getInstance()->load($invoice_id);

                if(!empty($current['receipt_number'])){
                    $date = substr($current['receipt_number'],0,8);
                    if($date != date('Ymd')){
                        $receipt_number = $res['receipt_number']+1;
                        $idd = InvoiceHeaderTable::getInstance()->save(array('id'=>$invoice_id, 'receipt_number'=>$receipt_number));

                        return $receipt_number;
                    }else{
                        $receipt_number = $current['receipt_number'];
                    }
                    return $receipt_number;
                }else{
                    $receipt_number = $res['receipt_number']+1;
                    $idd = InvoiceHeaderTable::getInstance()->save(array('id'=>$invoice_id, 'receipt_number'=>$receipt_number));

                    return $receipt_number;
                }
            }
        }
    }
    public function updateStatusEntry($invoice_items, $status ,$paid_date){

        if(empty($paid_date)){
            $paid_date = date('Y-m-d H:i:s');
        }
        foreach ($invoice_items as $item) {

            $event_type = null;
            if(isset($item['course_id'])){
                $event_type = 2 ;
                $relative_id = $item['course_id'];
                $sql_update = "UPDATE student_course_rel
                                SET is_received = 1, payment_date='".$paid_date."' 
                                WHERE course_id = ".$relative_id." AND student_id = ".$item['student_id'];

                $update = StudentCourseRelTable::getInstance()->execute($sql_update);

            }elseif(isset($item['program_id'])){
                $event_type = 3 ;
                $relative_id = $item['program_id'];
                $sql_update = "UPDATE student_program
                                SET is_received = 1, payment_date='".date('Y-m-d H:i:s')."' 
                                WHERE program_id = ".$relative_id." AND student_id = ".$item['student_id'];

                $update = StudentCourseRelTable::getInstance()->execute($sql_update);
            }
            if($event_type == null){
                continue;
            }
            $filter = array(
                'entry_type' => $event_type,
                'relative_id' =>  $relative_id,
                'student_id' => $item['student_id'],
            );
            $entry  = EntryTable::getInstance()->getActiveRow($filter);
            if($entry){
                $success = EntryTable::getInstance()->save([
                        'id'     => $entry['id'],
                        'status' => ($status != Constants::DEPOSIT_CANCEL) ? 1 : 0
                ],true);
                if (!$success) {
                    throw new Exception();
                }
            }

        }
    }

    public function get_newsest_invoice_month($pschool_id){

        //
        $invoice_year_month = null;

        //
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

        // payment_style = 1 : prepay
        if($payment_style == 1){
            if($today>$invoice_closing_date){
                $invoice_year_month = date('Y-m',strtotime(date("Y-m-d")."+2 month"));
            }else{
                if($is_koza){
                    $dead_line = $this-> getDeadLineOfPayment($pschool_id, date('Y-m',strtotime(date("Y-m-d")."+1 month")),2);  // increase 1 month cause this function will decrease 1 when return.
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
                    $dead_line = $this->getDeadLineOfPayment($pschool_id, date('Y-m',strtotime(date("Y-m-d")."+1 month")),2);  // increase 1 month cause this function will decrease 1 when return.
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
    private function storeMailMessage($invoice) {
        $mailMessageController = new MailMessageController();
        $mail_msg_tbl = MailMessageTable::getInstance();
        $msg_type       = 7;
        $relative_ID    = $invoice['id'];

        // TODO check exist Mail_Message
        $mail_message = $mail_msg_tbl->getActiveRow(['type'=>$msg_type, 'relative_ID'=>$relative_ID, 'parent_id'=>$invoice['parent_id']]);

        $save_m = array();
        if (empty($mail_message)) {

            $message_key = md5($mailMessageController->generateRandomString(64));
            $save_m['type']         = $msg_type;
            $save_m['message_key']  = $message_key;
            $save_m['relative_ID']  = $relative_ID;
            $save_m['target']       = 1;
            $save_m['target_id']    = $invoice['parent_id'];
            $save_m['pschool_id']   = $invoice['pschool_id'];
            $save_m['parent_id']    = $invoice['parent_id'];
            $save_m['total_send']   = 0; // first send

        } else {
            $save_m['id']           = $mail_message['id'];
        }

        // 送信予約
        if (!empty($invoice['schedule_flag_update'])) {
            $save_m['schedule_date']    = $invoice['schedule_date_update'];
        }

        $message_id = $mail_msg_tbl -> save($save_m);

        return $message_id;
    }
}
