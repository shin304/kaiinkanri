<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\MailMessageTable;
use App\Model\CourseTable;
use App\Model\StudentTable;
use App\Model\CourseFeePlanTable;
use App\Model\PschoolTable;
use DB;

class CourseFeePlanTableTest extends TestCase
{
    private $test_item;
    public function setUp()
    {
        parent::setUp();
        // Get last item on mail_message_table
        $this->test_item = DB::table('mail_message')->whereNotNull('relative_ID')->orderBy('id', 'desc')->first();

        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
    }

    public function testGetCoursePrice()
    {
        $mkey = $this->test_item->message_key; 
        echo "\nFunction: testGetCoursePrice\n";
        echo "\nCase: 1: key existed - Key => {$mkey}\n";
        // $mkey = '3d91407d879f9315f350202ac1be9fde';
        $mail_data = MailMessageTable::getInstance()->getRow(array('message_key'=>$mkey));
        $course_data = CourseTable::getInstance()->getRow(array('id'=>$mail_data['relative_ID']));
        $student_data = StudentTable::getInstance()->getRow(array('id'=>$mail_data['student_id']));
        $course_price = CourseFeePlanTable::getInstance()->getCoursePrice($course_data["id"], $student_data["id"]);
        $this->assertNotNull($course_price, "Case: 1: key existed => Course price is not correct");

        echo "\nCase: 2: key does not exist - Key => 11111111111111111111111111111111\n";
        $mkey = '11111111111111111111111111111111';
        $mail_data = MailMessageTable::getInstance()->getRow(array('message_key'=>$mkey));
        $course_data = CourseTable::getInstance()->getRow(array('id'=>$mail_data['relative_ID']));
        $student_data = StudentTable::getInstance()->getRow(array('id'=>$mail_data['student_id']));
        $course_price = CourseFeePlanTable::getInstance()->getCoursePrice($course_data["id"], $student_data["id"]);
        $this->assertEmpty($course_price, "Case: 2: key does not exist => Course price is not empty");
    }

    // test school/course - Kieu 2017/05/19
    public function testGetCourseFeePlan() {
        echo "\n\t ====================";
        echo "\n\t TEST: LOAD LIST FEE PLAN BY EVENT ID";
        echo "\n\t ====================";

        $course_list =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
        echo "\n\tList Event for {$this->admin['login_id']} have ".count($course_list)." records\n\n";

        $last_course = end($course_list);
        echo "\n\tTest Last Event: {$last_course['course_title']}\n";
        $course_fee = CourseFeePlanTable::getInstance()->getActiveList(array('course_id'=>$last_course['id']), array('sort_no'));

        foreach ($course_fee as $key => $value) {
            $value['fee'] = floor($value['fee']);
            if ($last_course['fee_type'] == 1) {
                echo "\n\tStudent Type: ".$value['student_type_id']. " Has Fee :".$value['fee'];
            } else {
                echo "\n\tFee Name: ".$value['fee_plan_name']. " Has Fee :".$value['fee'];

            }
        }
        $this->assertNotNull($course_fee);

    }

    public function testGetCourseFeeForStudentType() {
        echo "\n\t ====================";
        echo "\n\t TEST: LOAD LIST FEE PLAN BY STUDENT TYPE";
        echo "\n\t ====================";

        $course_list =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
        echo "\n\tList Event for {$this->admin['login_id']} have ".count($course_list)." records\n\n";

        $last_course = end($course_list);
        echo "\n\tTest Last Event: {$last_course['course_title']}\n";

        $course_fee = CourseFeePlanTable::getInstance()->getCourseFeeForStudentType($last_course['id']);

        foreach ($course_fee as $idx => $value) {
            $value['fee'] = floor($value['fee']);
            echo "\n\tStudent Type: ".$value['student_type_id']. " Has Fee :".$value['fee'];
            
        }
        $this->assertNotNull($course_fee);
        
    }
// end test school/course - Kieu 2017/05/19
}
