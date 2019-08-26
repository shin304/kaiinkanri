<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;

class PschoolTableTest extends TestCase {
    /**
     * author Phuc Nguyen
     * Test get login info
     *
     * @var $loginid
     * @var $password
     * @var $kyoukai = false
     *     
     */
    public function testGetLoginInfo() {
        $loginid = "nakashika2@a.b";
        $password = "12345678";
        $kyoukai = false;
        $pschool = PschoolTable::getInstance ();
        $result = $pschool->getLoginInfo ( $loginid, $password, $kyoukai );
        
        $this->assertContains ( "nakashika2@a.b", $result );
    }
    
    /**
     * author Phuc Nguyen
     * Test load info with login account
     *
     * @var $id pschool
     *
     */
    public function testLoadWithLoginAccount() {
        $id = "86";
        $pschool = PschoolTable::getInstance ();
        $result = $pschool->loadWithLoginAccount ( $id );
        
        $this->assertContains ( "中村テスト支部管理２", $result );
    }
}
