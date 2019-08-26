<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;
use App\Model\InvoiceHeaderTable;

class InvoiceHeaderTableTest extends TestCase
{
    private $admin;
    public function setUp()
    {
        parent::setUp();
        // Get last item on mail_message_table
        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
        
    }

// test school/home - Thang 2017/05/16
    public function testGetListNoticeActivity()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST NOTICE ACTIVITY (SCHOOL/HOME)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];

        echo "\n\t +++++";
        echo "\n\t TEST_CASE: GET LASTEST NOTICE ACTIVITY ";
        echo "\n\t +++++";
        $period = null;
        $notice_activity_lastest = InvoiceHeaderTable::getInstance()->getListNoticeByInvoice($pschool_id, $period);
        $this->assertNotNull($notice_activity_lastest, "{$pschool_id} has not any notice_invoice_header.");
        $this->assertEquals('2', count($notice_activity_lastest), '{$pschool_id} has not exactly 2 notice_invoice_header.({count($notice_activity_lastest)})');

        echo "\n\t +++++";
        echo "\n\t TEST_CASE: GET NOTICE ACTIVITY SINCE 7 DAYS AGO";
        echo "\n\t +++++";
        $period = 7;
        $notice_activity_7 = InvoiceHeaderTable::getInstance()->getListNoticeByInvoice($pschool_id, $period);
        $this->assertNotNull($notice_activity_7, "{$pschool_id} has not any notice_invoice_header.");
        $this->assertEquals('0', count($notice_activity_7), '{$pschool_id} has not exactly 1 notice_invoice_header.({count($notice_activity_7)})');

        echo "\n\t +++++";
        echo "\n\t TEST_CASE: GET NOTICE ACTIVITY SINCE 30 DAYS AGO";
        echo "\n\t +++++";
        $period = 30;
        $notice_activity_30 = InvoiceHeaderTable::getInstance()->getListNoticeByInvoice($pschool_id, $period);
        $this->assertNotNull($notice_activity_30, "{$pschool_id} has not any notice_invoice_header.");
        $this->assertEquals('2', count($notice_activity_30), '{$pschool_id} has not exactly 2 notice_invoice_header.({count($notice_news_30)})');
    }

    public function testUpdateViewDate()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: UPDATE VIEW DATE OF INVOICE HEADER (SCHOOL/HOME)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        $invoice_header_id = 515;
        $invoice_header = $invoiceHeaderTable->load($invoice_header_id);
        $this->assertNotNull($invoice_header, "{$invoice_header_id} has not info.");
        if ($invoice_header) {
            $now = date('Y-m-d h:i:s');
            $invoice_header['view_date'] = $now;
            $updated_invoice_header_id = $invoiceHeaderTable->updateRow($invoice_header, array('id'=>$invoice_header['id']));
            $updated_invoice_header = $invoiceHeaderTable->load($invoice_header_id);
            $this->assertNotNull($invoice_header, "{$invoice_header_id} is not saved successfully.");
            $this->assertEquals($now, $updated_invoice_header['view_date'], "{$invoice_header_id}-view_date is not saved exactly " );
        }
    }
// end ---- test school/home - Thang 2017/05/16
}
