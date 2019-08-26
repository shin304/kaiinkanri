<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\InvoiceAdjustNameTable;
use App\Model\PschoolTable;

class InvoiceAdjustNameTableTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
    }
    public function testGetInvoiceAdjustList()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: LOAD LIST INVOICE ADJUST OF ADMIN";
        echo "\n\t ====================";
        $invoice_adjust_list = InvoiceAdjustNameTable::getInstance()->getInvoiceAdjustList(array($this->admin['id']));
        echo "\n\tList Invoice Adjust for {$this->admin['login_id']} have ".count($invoice_adjust_list)." records\n\n";

        $this->assertNotNull($invoice_adjust_list);
    }
}
