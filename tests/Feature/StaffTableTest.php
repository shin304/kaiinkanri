<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\StaffTable;

class StaffTableTest extends TestCase {
    
    /**
     * author Phuc Nguyen
     * Test get login info
     *
     * @var $loginid
     * @var $password
     *
     */
    public function testGetLoginInfo() {
        $loginid = "tung103@gmail.com";
        $password = "12345678";
        $staff = StaffTable::getInstance ();
        $result = $staff->getLoginInfo ( $loginid, $password );
        
        $this->assertContains ( "4", $result );
    }
}
