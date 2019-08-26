<?php
namespace App\Module\Invoice;
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 8/21/2017
 * Time: 12:43 PM
 */
interface PaymentInterface {


    function getInvoiceObjectMonthlyClass($request = array(), $payment_type);

    function getInvoiceObjectScheduleClass($request = array(), $payment_type);

    function getInvoiceObjectEvent($request = array(), $payment_type);

    function getInvoiceObjectProgram($request = array(), $payment_type);
    /*
     * 請求書作成
     */
    function createInvoice($request = array(), $payment_type, $is_batch = false);

    function processInvoiceList($pschool, $list_parent, $due_date, $is_batch = false);

    function isInvoiceHeaderExist($header);

    function saveInvoiceHeader($pschool, $header_data, $is_batch = false);

    function isInvoiceItemExist($item);

    function saveInvoiceItem($pschool, $item_data, $is_batch = false);

    function createParentAdjustItem($parent_data);

    function createClassAdjustItem($parent_data);

    function createEventAdjustItem($parent_data);

    function createProgramAdjustItem($parent_data);

    function updateAmountInvoiceHeader($header_id);

    function getDueDateOfPaymentMethod($request = array(), $payment_type);
}