<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 8/21/2017
 * Time: 1:39 PM
 */

namespace App\Module\Invoice;


use App\Common\Constants;

class PaymentFactory
{
    /**
     * @param $paymentType
     * @return BankTransferPayment|CashPayment|RICOHConvenientStorePayment|RICOHPostOfficePayment|RICOHTransferPayment|ZEUSCreditCardConnectPayment|ZEUSCreditCardPayment
     */
    public static function getPaymentMethod($paymentType) {
        switch ($paymentType) {
            case Constants::CASH:
                return new CashPayment();
            case Constants::TRAN_RICOH:
                return new RICOHTransferPayment();
            case Constants::CONV_RICOH:
                return new RICOHConvenientStorePayment();
            case Constants::POST_RICOH:
                return new RICOHPostOfficePayment();
            case Constants::CRED_ZEUS:
                return new ZEUSCreditCardPayment();
            case Constants::CRED_ZEUS_CONNECT:
                return new ZEUSCreditCardConnectPayment();
            case Constants::TRAN_BANK:
                return new BankTransferPayment();
        }
    }

}