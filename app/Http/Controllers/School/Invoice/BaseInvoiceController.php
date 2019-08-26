<?php

namespace App\Http\Controllers\School\Invoice;

use App\Common\Constants;
use App\ConstantsModel;
use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\ParentTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\PschoolTable;
use App\Module\Invoice\PaymentFactory;
use Illuminate\Http\Request;
use App\Model\InvoiceHeaderTable;

/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 8/21/2017
 * Time: 11:46 AM
 */
class BaseInvoiceController extends _BaseSchoolController
{
    const invoice_background_color = array (
        '1' => array('top'=> '#8FC31F', 'bottom' => '#7aa71b'),
        '2' => array('top'=> '#b0b4f2', 'bottom' => '#7c7fa8'),
        '3' => array('top'=> '#fcc692', 'bottom' => '#ff9f42'),
        '4' => array('top'=> '#64a857', 'bottom' => '#406d37'),
        '5' => array('top'=> '#6873fb', 'bottom' => '#455db5'),
        '6' => array('top'=> '#da84ff', 'bottom' => '#9557af'),
        '7' => array('top'=> '#01DF74', 'bottom' => '#01DF74'),
    );

    public function __construct() {
        parent::__construct ();
    }

    protected function getInvoiceStatusMenu(Request $request=null){
        $count_invoice= array();
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $pschool_id = session()->get('school.login.id');

        if($request->offsetExists('invoice_year_month')){
            $invoice_year_month = $request->invoice_year_month;
            $count_invoice = $invoiceHeaderTable->countInvoice($pschool_id,$invoice_year_month);
            $count_invoice = $count_invoice[0];
            if($count_invoice['invoice_year_month']== null){
                $count_invoice = array(
                    "invoice_year_month" => $request->invoice_year_month,
                    "cnt_entry" => 0,
                    "cnt_confirm" => 0,
                    "cnt_send" => 0,
                    "cnt_complete" => 0,
                    "register_date" => date('y-m-d H-i-s'),
                    "cnt_genkin" => 0,
                    "cnt_richo" => 0,
                    "cnt_other" => 0,
                    "cnt_all" => 0,
                );
            }
        }else{
            $count_invoice = $invoiceHeaderTable->countInvoice($pschool_id);
            // Toran fix : if empty or this month have no invoice -> create empty record
            $bool_flag = true;
            if(!empty($count_invoice)){
                foreach($count_invoice as $k=> $header){
                    if($header['invoice_year_month'] == date('Y-m')){
                        $bool_flag = false ;
                        break 1;
                    }
                }
            }

            if($bool_flag || empty($count_invoice) ){
                $temp_header = array(
                    "invoice_year_month" => date('Y-m'),
                    "cnt_entry" => 0,
                    "cnt_confirm" => 0,
                    "cnt_send" => 0,
                    "cnt_complete" => 0,
                    "register_date" => date('y-m-d H-i-s'),
                    "cnt_genkin" => 0,
                    "cnt_richo" => 0,
                    "cnt_other" => 0,
                    "cnt_all" => 0,
                );
                $count_invoice[] = $temp_header;
            }
        }

        return $count_invoice;
    }

    protected function getPaymentMethodBySchool($pschool_id) {

        $payment_method_pschool = PaymentMethodPschoolTable::getInstance();

        $cond = array(
            'pschool_id'=>$pschool_id,
        );
        $payment_methods = $payment_method_pschool->getList($cond);
        return $payment_methods;
    }

    public function executeGenerateInvoice(Request $request){
        $this->generateInvoice($request);
    }

    public function getListParentSelect(Request $request){

        $invoice_year_month = $request->invoice_year_month;
        $filter_list = InvoiceHeaderTable::getInstance()->getActiveList(array('invoice_year_month'=>$invoice_year_month,'is_nyukin' => 0));
        $filter = array();
        foreach ($filter_list as $v){
            $filter[] = $v['parent_id'];
        }

        $pschool_id = session()->get('school.login.id');
        $result_list = ParentTable::getInstance()->getParentListAndStudentListById($pschool_id);
        $invoice_type = Constants::$invoice_type ;
        $invoice_type = $invoice_type[session()->get('school.login.language')];
        $parent_list = array();

        foreach ($result_list as $k => $v){
            if(in_array($v['id'],$filter)){
                continue;
            }
            if(!empty($v['student_list'])){
                $parent_list[$v['id']] = array(
                    'parent_name' => $v['parent_name'],
                    'parent_name_kana' => $v['name_kana'],
                    'parent_id' => $v['id'],
                    'payment_method' =>  $invoice_type[$v['invoice_type']],
                    'invoice_type' => $v['invoice_type']
                );

                $parent_list[$v['id']]['student_id'] = $v['student_list'][0]['id'];
                $parent_list[$v['id']]['student_name'] = $v['student_list'][0]['student_name'];
                $parent_list[$v['id']]['student_no'] = $v['student_list'][0]['student_no'];
                $parent_list[$v['id']]['student_type'] = $v['student_list'][0]['student_type_name'];
            }
        }
//        $pschool_id = session()->get('school.login.id');
//        $request->offsetSet('id',$pschool_id);
//        $parent_list = array();
//        $result_list = array();
//        $methods = $this->getPaymentMethodBySchool($pschool_id);
//        $invoice_type = Constants::$invoice_type ;
//        $invoice_type = $invoice_type[session()->get('school.login.language')];
//        foreach ($methods as $method) {
//            $payment_methods = PaymentFactory::getPaymentMethod($method['payment_method_code']);
//            // get list parent
//            $list = $payment_methods->getListParent($request);
//            $result_list = array_merge($result_list,$list);
//        }
//
//        foreach ($result_list as $k => $v){
//            if(!isset($parent_list[$v['parent_id']])){
//                $parent_list[$v['parent_id']] = array(
//                        'student_id' => $v['student_id'],
//                        'student_name' => $v['student_name'],
//                        'student_no' => $v['student_no'],
//                        'parent_name' => $v['parent_name'],
//                        'parent_id' => $v['parent_id'],
//                        'payment_method' =>  $invoice_type[$v['payment_method']],
//                        'student_type' => $v['student_type'],
//                );
//            }
//        }
        $invoice_background_color = self::invoice_background_color;
        $invoice_type_constant = Constants::$invoice_type;
        $invoice_type = $invoice_type_constant[session()->get('school.login.lang_code')];

        return view('School.Invoice.parent_select',compact('request','parent_list','invoice_background_color','invoice_type'));
    }

    public function generateInvoice(Request $request, $is_batch = false){
        $methods = $this->getPaymentMethodBySchool($request->id);

        foreach ($methods as $method) {
            $payment_methods = PaymentFactory::getPaymentMethod($method['payment_method_code']);
            // create invoice
            $payment_methods->generateInvoice($request, $is_batch);
        }
    }

    // new function get summary info for accordion view of invoice
    public function getTotalSummary($pschool_id){
        $array_status = array(
            0,
            1,
            11,
            31
        );
        $sql = " SELECT invoice_year_month, workflow_status, invoice_type, COUNT(ih.id) as count, sum(amount) as amount, ";
        $sql.= " MAX(ih.register_date) AS register_date ";
        $sql.= " FROM invoice_header ih, payment_method pd ";
        $sql.= " WHERE pschool_id = ".$pschool_id." ";
        $sql.= " AND ih.invoice_type = pd.id ";
        $sql.= " AND ih.delete_date IS NULL ";
        $sql.= " AND (is_nyukin = 0 OR is_nyukin IS NULL) ";
        $sql.= " GROUP BY invoice_year_month, workflow_status, invoice_type ";
        $sql.= " ORDER BY invoice_year_month DESC, workflow_status ASC, sort_no ASC ";

        $res = InvoiceHeaderTable::getInstance()->fetchAll($sql);
        $heads= array();

        foreach($res as $k => $value){
            if($value['workflow_status'] > 11 && $value['workflow_status'] <31){
                $value['workflow_status'] = 11;
                $res[$k]['workflow_status'] = 11;
            }
//            $heads[$value['invoice_year_month']][$value['workflow_status']][$value['invoice_type']] = $value['count'];
            $heads[$value['invoice_year_month']]['count'][$value['workflow_status']][$value['invoice_type']] = $value['count'];
            $heads[$value['invoice_year_month']]['amount'][$value['workflow_status']][$value['invoice_type']] = $value['amount'];
            if(isset($heads[$value['invoice_year_month']]['register_date'])){
                $heads[$value['invoice_year_month']]['register_date'] = $heads[$value['invoice_year_month']]['register_date'] > $value['register_date'] ? $heads[$value['invoice_year_month']]['register_date'] : $value['register_date'] ;
            }else{
                $heads[$value['invoice_year_month']]['register_date'] = $value['register_date'];
            }
        }

        foreach ($heads as $month => $value){
            foreach ($value as $key => $item){
                foreach ($array_status as $status){
                    if(!isset($value['count'][$status])){
                        $heads[$month]['count'][$status] = array();
                    }
                    if(!isset($value['amount'][$status])){
                        $heads[$month]['amount'][$status] = array();
                    }
                }
            }
        }

        $init = $this->getEmptyInvoiceType($pschool_id);

        foreach ($heads as $month => $value){
            foreach ($value['count'] as $stage => $list_method){
                foreach ($init[$month] as $method=>$count){
                    if(!isset($list_method[$method])){
                        $heads[$month]['count'][$stage][$method] = 0;
                    }
                }
                if(is_array($list_method)){
                    ksort($heads[$month]['count'][$stage]);
                }
            }
            foreach ($value['amount'] as $stage => $list_method){
                foreach ($init[$month] as $method=>$count){
                    if(!isset($list_method[$method])){
                        $heads[$month]['amount'][$stage][$method] = 0;
                    }
                }
                if(is_array($list_method)){
                    ksort($heads[$month]['amount'][$stage]);
                }
            }
            ksort($heads[$month]);
        }

        foreach ($heads as $key=>$month){
            foreach($month['count'] as $status => $method){
                if(is_numeric($status)){
                    foreach($month['count'] as $other_status => $other_method){
                        if(is_numeric($other_status) && ($other_status > $status) && is_array($method)){
                            foreach ($method as $k => $v){
                                $heads[$key]['count'][$status][$k]+= $heads[$key]['count'][$other_status][$k];
                            }
                        }
                    }
                }
            }
            foreach($month['amount'] as $status => $method){
                if(is_numeric($status)){
                    foreach($month['amount'] as $other_status => $other_method){
                        if(is_numeric($other_status) && ($other_status > $status) && is_array($method)){
                            foreach ($method as $k => $v){
                                $heads[$key]['amount'][$status][$k]+= $heads[$key]['amount'][$other_status][$k];
                            }
                        }
                    }
                }
            }
        }

        foreach ($heads as $key=>$month){
            //$total = 0;
            foreach($month['amount'] as $status => $method){
                if(is_array($method)){

                    ksort($heads[$key]['amount'][$status]);
                    $total_stage_amount = 0;
                    foreach($method as $p_method=> $count){
                        $total_stage_amount+=$count; // total for 1 stage of month
                    }
                    $heads[$key]['amount'][$status]['total_amount'] = $total_stage_amount;
                }
            }
            foreach($month['count'] as $status => $method){
                if(is_array($method)){
                    // ksort($heads[$key]['count'][$status]);
                    //$heads[$key]['count'][$status] = array_replace(array_flip(array('1', '6', '2','3', '4', '5', '7')), $heads[$key]['count'][$status]);
                    uksort($heads[$key]['count'][$status], function ($a, $b)
                    {
                        $tmp = [
                            1 => 1,
                            6 => 2,
                            2 => 3,
                            3 => 4,
                            4 => 5,
                            5 => 6,
                            7 => 7
                        ];
                        return $tmp[$a] - $tmp[$b];
                    });

                    $total_stage_count = 0;
                    foreach($method as $p_method=> $count){
                        $total_stage_count+=$count; // total for 1 stage of month
                        //$total+=$count; // total of all month
                    }
                    $heads[$key]['count'][$status]['total'] = $total_stage_count;
                    $heads[$key]['count'][$status]['total_amount'] = $total_stage_amount;
                }
            }
            $heads[$key]['count']['cnt_entry'] =  $heads[$key]['count'][0]['total'];
            $heads[$key]['amount']['cnt_entry_amount'] =  $heads[$key]['amount'][0]['total_amount'];
        }

        return $heads;
    }

    public function getEmptyInvoiceType($pschool_id){

        $res = array();
        $sql = " SELECT DISTINCT invoice_type,invoice_year_month ";
        $sql.= " FROM invoice_header ih, payment_method pd WHERE ih.invoice_type = pd.id AND pschool_id = ".$pschool_id." AND ih.delete_date IS NULL ";
        $sql.= " ORDER BY invoice_year_month DESC, sort_no ASC";
        $all_invoice_type = InvoiceHeaderTable::getInstance()->fetchAll($sql);

        foreach ($all_invoice_type as $key=> $value){
            $res[$value['invoice_year_month']][$value['invoice_type']]= array();
        }

        return $res;
    }
}