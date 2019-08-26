<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;
use App\Model\ProgramFeePlanTable;
use App\Model\ProgramTable;

class ProgramFeePlanTableTest extends TestCase
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

    public function testGetProgramFeeForStudentType()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: LOAD LIST FEE PLAN BY STUDENT TYPE";
        echo "\n\t ====================";

        $program_list =  ProgramTable::getInstance()->getProgramList($this->admin['id']);
        echo "\n\tList Program for {$this->admin['login_id']} have ".count($program_list)." records\n\n";

        $last_program = end($program_list);
        echo "\n\tTest Last Program: {$last_program['program_name']}\n";

        $program_fee = ProgramFeePlanTable::getInstance()->getProgramFeeForStudentType($last_program['id']);

        foreach ($program_fee as $idx => $value) {
            $value['fee'] = floor($value['fee']);
            echo "\n\tStudent Type: ".$value['student_type_id']. " Has Fee :".number_format($value['fee']);

        }
        $this->assertNotNull($program_fee);
    }
}
