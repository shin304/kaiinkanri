<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\PschoolTable;
use App\Model\CourseTable;

class CourseTableTest extends TestCase
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
    public function testGetCourseList4Top()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST INDEX PAGE";
        echo "\n\t ====================";
        // Load list event with student_count
        $result =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
        echo "\n\tList Event for {$this->admin['login_id']} have ".count($result)." records\n";
        $this->assertNotNull($result);

    }

    // test school/home - Thang 2017/05/16
    public function testGetListOfCourse()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST COURSE (SCHOOL/HOME)";
        echo "\n\t ====================";
        // Load list event with pschool_id
        $course_list = CourseTable::getInstance()->getListOfCourse($this->admin['id']);
        echo "\n\tList Event of {$this->admin['login_id']}'s school has ".count($course_list)." record(s)\n";
        $this->assertNotNull($course_list, "{$this->admin['login_id']}'s school hasn't any event");
    }

    public function testSaveCourse() {
        echo "\n\t ====================";
        echo "\n\t TEST: SAVE COURSE";
        echo "\n\t ====================";
        $this->session(['login_account_id' => 0]);
        
        $course = array(
            'pschool_id'        => $this->admin['id'],
            'course_title'      => 'Course Test',
            'start_date'        => date('Y-m-d H:i:s'),
            'course_description'=> 'course description',
            'active_flag'       => 1,
            
            'mail_subject'      => 'mail_subject',
            'recruitment_start' => date('Y-m-d H:i:s'),
            'recruitment_finish'=> date('Y-m-d H:i:s'),
            //payment_method: 1:現金・振込, 2:口座振替, 3:両方
            'payment_method'    => 3,
            'payment_due_date'  => date('Y-m-d H:i:s'),
            'fee_type'          => 1,
            'member_capacity'   => 100 ,
            'non_member_capacity' => 0,
            'application_deadline' => 0,
            );
        $course_id = CourseTable::getInstance()->save($course);
        echo "\n\t SAVE SUCCESS WITH ID: ". $course_id;
        echo "\n\t --------------------";
        $course_item = CourseTable::getInstance()->load($course_id);
        echo "\n\tpschool_id: " . $course_item['pschool_id'];
        echo "\n\tcourse_title: " . $course_item['course_title'];
        echo "\n\tstart_date: " . $course_item['start_date'];
        echo "\n\tcourse_description: " . $course_item['course_description'];
        echo "\n\tactive_flag: " . $course_item['active_flag'];
            
        echo "\n\tmail_subject: " . $course_item['mail_subject'];
        echo "\n\trecruitment_start: " . $course_item['recruitment_start'];
        echo "\n\trecruitment_finish: " . $course_item['recruitment_finish'];
            //payment_method: 1:現金・振込, 2:口座振替, 3:両方
        echo "\n\tpayment_method: " . $course_item['payment_method'];
        echo "\n\tpayment_due_date: " . $course_item['payment_due_date'];
        echo "\n\tfee_type: " . $course_item['fee_type'];
        echo "\n\tmember_capacity: " . $course_item['member_capacity'];
        echo "\n\tnon_member_capacity: " . $course_item['non_member_capacity'];
        echo "\n\tapplication_deadline: " . $course_item['application_deadline'];

        $this->assertNotNull($course_id);
        
        echo "\n\t ====================";
        echo "\n\t TEST: DELETE COURSE: ". $course_item['course_title'];
        echo "\n\t ====================";

        CourseTable::getInstance()->logicRemove($course_id);
        echo "\n\t DELETE SUCCESS WITH ID: ". $course_id;
        echo "\n\t --------------------";
        $course_item = CourseTable::getInstance()->load($course_id);
        echo "\n\t delete_date: ". $course_item['delete_date'];
        
    }

}
