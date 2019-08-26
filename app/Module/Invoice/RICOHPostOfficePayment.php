<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 8/21/2017
 * Time: 12:51 PM
 */

namespace App\Module\Invoice;


use App\Common\Constants;

class RICOHPostOfficePayment extends BaseInvoice
{
    public function __construct()
    {
        parent::__construct();
    }

    function getListParent($request = array()) {
        $payment_type = Constants::$PAYMENT_TYPE[Constants::POST_RICOH];
        return parent::getListParentSelect ($request, $payment_type);
    }

    function generateInvoice($request = array(), $is_batch = false)
    {
        $payment_type = Constants::$PAYMENT_TYPE[Constants::POST_RICOH];
        parent::createInvoice($request, $payment_type, $is_batch);
    }
}