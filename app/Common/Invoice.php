<?php

namespace App\Common;

use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Database\Eloquent\Model;
use App\Model\ProgramTable;
use App\Model\CourseTable;
use App\Model\PschoolTable;
use App\Model\InvoiceHeaderTable;
use App\Model\PaymentMethodPschoolTable;
use App\Module\Invoice\PaymentFactory;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\School\Invoice\BaseInvoiceController;
use DB;
use Log;


trait Invoice
{

    public function generateInvoiceDaily()
    {
        echo "\nRun in generate";
//        $year_month = date("Y-m");
        $year_month = date("Y-m", strtotime("+1 months", strtotime('now')));
        //$year_month = "2017-09";
        // TODO get school_id and run generate function

        //check today is last day of month or not
        if (date('j') != date('t')) {
            $list_pschool = PschoolTable::getInstance()->getActiveList(array('active_flag' => 1, 'invoice_batch_date' => date('j')));
        } else {
            $list_pschool = PschoolTable::getInstance()->getListSchoolBatchEndMonth();
        }
        //
        echo " has : " . count($list_pschool) . " schools";
        if ($list_pschool && count($list_pschool) > 0) {
            try {
                InvoiceHeaderTable::getInstance()->beginTransaction();
                $is_batch = true;
                foreach ($list_pschool as $pschool) {

                    echo "\npschool_id :  " . $pschool['id'];
                    $request = array ();//new \stdClass();
                    $request['id'] = $pschool['id'];
                    $request['invoice_year_month'] = $year_month;
                    $methods = $this->getPaymentMethodBySchool($request['id']);
                    foreach ($methods as $method) {
                        $payment_methods = PaymentFactory::getPaymentMethod($method['payment_method_code']);
                        // create invoice
                        $payment_methods->generateInvoice($request, $is_batch);
                    }
                }
                InvoiceHeaderTable::getInstance()->commit();
            } catch (Exception $e) {
                InvoiceHeaderTable::getInstance()->rollback();
            }
        }
    }

    protected function getPaymentMethodBySchool($pschool_id)
    {

        $payment_method_pschool = PaymentMethodPschoolTable::getInstance();

        $cond = array(
            'pschool_id' => $pschool_id,
        );
        $payment_methods = $payment_method_pschool->getList($cond);
        return $payment_methods;
    }

    /**
     * Log Error
     * @param $message : message string
     */
    protected function logError($message)
    {
//        printf ("\nERROR MESSAGE : %s\n", $message);
        Log::error($message);
    }

    /**
     * Log Success
     * @param $message : message string
     */
    protected function logSuccess($message)
    {
//        printf ("\nSUCCESS MESSAGE : %s\n", $message);
        Log::info($message);
    }
}