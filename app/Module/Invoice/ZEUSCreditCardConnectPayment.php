<?php
/**
 * Created by PhpStorm.
 * User: ASTO-21
 * Date: 8/6/2018
 * Time: 12:28 PM
 */

namespace App\Module\Invoice;

use App\Common\Constants;

class ZEUSCreditCardConnectPayment extends BaseInvoice
{

    /**
     * ZEUSCreditCardConnectPayment constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    function getListParent($request = array()) {
        $payment_type = Constants::$PAYMENT_TYPE[Constants::CRED_ZEUS_CONNECT];
        return parent::getListParentSelect ($request, $payment_type);
    }


    function generateInvoice($request = array(), $is_batch = false)
    {
        $payment_type = Constants::$PAYMENT_TYPE[Constants::CRED_ZEUS_CONNECT];
        parent::createInvoice($request, $payment_type, $is_batch);
    }
}