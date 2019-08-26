<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;
use App\Model\BroadcastMailTable;

class BroadcastMailTableTest extends TestCase
{
    private $admin;
    public function setUp()
    {
        parent::setUp();

        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
        
    }

// test school/home - Thang 2017/05/16
    public function testGetListBroadCastMail()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST BROAD CAST MAIL (SCHOOL/HOME)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];

        echo "\n\t +++++";
        echo "\n\t TEST_CASE: GET LASTEST BROAD CAST MAIL ";
        echo "\n\t +++++";
        $period = null;
        $broadcast_mail_lastest = BroadcastMailTable::getInstance ()->getBroadCastList ( $pschool_id, $period);
        $this->assertNotNull($broadcast_mail_lastest, "{$pschool_id} has not any broadcast_mail.");
        $this->assertEquals('3', count($broadcast_mail_lastest), '{$pschool_id} has not exactly 3 broadcast_mail.({count($broadcast_mail_lastest)})');

        echo "\n\t +++++";
        echo "\n\t TEST_CASE: GET LIST BROAD CAST MAIL SINCE 7 DAYS AGO";
        echo "\n\t +++++";
        $period = 7;
        $broadcast_mail_7 = BroadcastMailTable::getInstance ()->getBroadCastList ( $pschool_id, $period);
        $this->assertNotNull($broadcast_mail_7, "{$pschool_id} has not any broadcast_mail.");
        $this->assertEquals('2', count($broadcast_mail_7), '{$pschool_id} has not exactly 2 broadcast_mail.({count($broadcast_mail_7)})');

        echo "\n\t +++++";
        echo "\n\t TEST_CASE: GET LIST BROAD CAST MAIL SINCE 30 DAYS AGO";
        echo "\n\t +++++";
        $period = 30;
        $broadcast_mail_30 = BroadcastMailTable::getInstance ()->getBroadCastList ( $pschool_id, $period);
        $this->assertNotNull($broadcast_mail_30, "{$pschool_id} has not any broadcast_mail.");
        $this->assertEquals('3', count($broadcast_mail_30), '{$pschool_id} has not exactly 3 broadcast_mail.({count($notice_news_30)})');
    }
// end ---- test school/home - Thang 2017/05/16
}
