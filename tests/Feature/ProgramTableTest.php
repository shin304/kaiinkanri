<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;
use App\Model\ProgramTable;
class ProgramTableTest extends TestCase
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

        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
        
    }
    public function testGetProgramList()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST INDEX PAGE";
        echo "\n\t ====================";
        // Load list event with student_count
        $result =  ProgramTable::getInstance()->getProgramList($this->admin['id']);
        echo "\n\tList Program for {$this->admin['login_id']} have ".count($result)." records\n";
        $this->assertNotNull($result);

    }

}
