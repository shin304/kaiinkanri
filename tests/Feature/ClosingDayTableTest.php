<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;
use App\Model\ClosingDayTable;

class ClosingDayTableTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    private $admin;
    public function setUp()
    {
        parent::setUp();
        // Get last item on mail_message_table
        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
    }

    public function testGetClosingDayList($value='')
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST CLOSING DAY OF CALENDAR (SCHOOL/HOME)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];
        $pschool = PschoolTable::getInstance()->load($pschool_id);
        $this->assertNotNull($pschool, "{$pschool_id} has no info");
        if( intval($pschool['withdrawal_day']) > 0 ){
            $closingday_list = ClosingDayTable::getInstance()->getList(array('transfer_day'=>$pschool['withdrawal_day'], 'delete_date IS NULL'), array('transfer_month'=>'ASC'));
            $this->assertNotNull($closingday_list, "{$pschool_id} has not any info of time of third-party company ");
            $this->assertEquals("12", count($closingday_list), "{$pschool_id} has not enough 12 months's info of third-party company ");
        }
    }
}
