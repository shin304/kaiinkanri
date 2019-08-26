<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;
use App\Model\MStudentTypeTable;

class MStudentTypeTableTest extends TestCase
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

    public function testGetStudentTypeName_Axis()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST STUDENT TYPE ";
        echo "\n\t ====================";
        // Load list event with student_count
        $result =  MStudentTypeTable::getInstance()->getStudentTypeName_Axis($this->admin['id']);
        echo "\n\tList Student Type for {$this->admin['login_id']} have ".count($result)." records\n";
        foreach ($result as $key => $value) {
            echo "\n\t".$value['name'];
        }
        $this->assertNotNull($result);
    }
}
