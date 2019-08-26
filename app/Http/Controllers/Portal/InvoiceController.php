<?php

namespace App\Http\Controllers\Portal;

use App\ConstantsModel;
use App\Http\Controllers\Common\_BaseAppController;
use App\Model\InvoiceHeaderTable;
use App\Model\InvoiceItemTable;
use App\Model\PaymentMethodDataTable;
use App\Model\PaymentMethodBankRelTable;
use App\Common\Constants;
use App\Model\MailMessageTable;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class InvoiceController extends _BaseAppController
{

    private $MESSAGE_TYPE;
    private $MESSAGE_KEY;
    private $MAIL_MESSAGE;
    private $SCHOOL;
    private $DATA;
    private $FEE_PLAN;
    private $ENTRY;

    public function __construct(Request $request)
    {
        parent::__construct();
    }

    protected function getData($mail_message) {
        $pschool_id = $mail_message['pschool_id'];
        $invoice_id = $mail_message['relative_ID'];

        $invoice = InvoiceHeaderTable::getInstance()->getActiveRow(array(
            'id'            => $invoice_id,
            'pschool_id'    => $pschool_id,
        ));
        return $invoice;
    }

    public function index(Request $request) {
        $mail_message = $this->checkMessageKey($request);

        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoiceItemTable = InvoiceItemTable::getInstance();

        // data will be get from invoice header
        $data = $invoiceHeaderTable->getDataParentStudent($mail_message['relative_ID']);
        //return result page
        if ($data['is_recieved'] == 1 && $data['paid_date'] && $data['workflow_status'] == 31) {
            return view('Portal.Payment.invoice_result')
                ->with('result', 1)
                ->with('invoice', $data);
        }

        // get item of this header_id and process
        $item_list = $invoiceItemTable->getListItemInvoice($data);

        // get info for credit_card payment type
        $info = array();
        if ($item_list['invoice_type'] == Constants::$PAYMENT_TYPE['CRED_ZEUS']) {
            $payment_info = PaymentMethodDataTable::getInstance()->getIPCode($mail_message['pschool_id'], Constants::CRED_ZEUS);
            $info = array(
                'domain'        => $request->server('HTTP_HOST'),
                'ip_code'       => $payment_info['ip_code'],
                'payment_link'  => $payment_info['payment_link'],
                'message_key'   => $mail_message['message_key'],
                'fee'           => $item_list['amount'],
                'code'          => 'INVOICE',
                'parent_mail'   => $data['parent_mail'],
                'phone_no'      => $data['phone_no'],
            );
        } elseif($item_list['invoice_type'] == Constants::$PAYMENT_TYPE['TRAN_BANK']){
            $bank_info = PaymentMethodBankRelTable::getInstance()->getListBank(session('school.login.id'), $item_list['invoice_type']);
            if (!empty($bank_info['list_bank'])) {
                foreach ($bank_info['list_bank'] as $k => $bank) {
                    if ($bank['is_default_account'] == 1) {
                        $item_list['school_bank_info']                      = $bank;
                        $bank_account_type_list                             = ConstantsModel::$type_of_bank_account[session()->get('school.login.language')];
                        $item_list['school_bank_info']['bank_account_type'] = $bank_account_type_list[$bank['bank_account_type']];
                    }
                }
            }
        }

        // TODO: view
        return view('Portal.Invoice.index',compact('item_list', 'info'));
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
        if (!$mail_message || $mail_message['mail_message_type'] != $type) {
            throw new FileNotFoundException("FileNotFound - message key does not exist");
            exit;
        }
        return $mail_message;
    }
}