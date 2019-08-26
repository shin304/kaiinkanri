<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\CourseCoachTable;
use App\Model\PschoolTable;
use App\Model\CourseTable;

class CourseCoachTableTest extends TestCase
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
// test school/course - Kieu 2017/06/19
    public function testGetCoachIDs()
    {   
        echo "\n\t ====================";
        echo "\n\t TEST: GET COACH IDs";
        echo "\n\t ====================";

        $course_list =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
        echo "\n\tList Event for {$this->admin['login_id']} have ".count($course_list)." records\n\n";

        foreach ($course_list as $key => $value) {
            $coach_list = CourseCoachTable::getInstance()->getCoachIDs($value, $this->admin['id']);

            if (count($coach_list) > 0 && array_get($coach_list, 0) != "") {
                echo "\n\tArray coach ids of event {$value['course_title']} is [".implode(', ', $coach_list)."]\n\n";
                
            }
            $this->assertNotNull($coach_list);
            
        }
        
    }

    public function testGetCoachList()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET COACH LIST";
        echo "\n\t ====================";

        $course_list =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
        echo "\n\tList Event for {$this->admin['login_id']} have ".count($course_list)." records\n\n";

        foreach ($course_list as $key => $value) {
            $coach_list = CourseCoachTable::getInstance()->getCoachList($value['id'], $this->admin['id']);
            $coach_name = array_column($coach_list, 'coach_name');

            if (count($coach_name) > 0) {

                echo "\n\tArray coach name of event {$value['course_title']} is [".implode(', ', $coach_name)."]\n\n";
            }
            $this->assertNotNull($coach_list);

        }

    }
// end test school/course - Kieu 2017/06/19
}
