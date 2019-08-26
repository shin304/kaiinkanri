<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\MailMessageTable;
use App\Model\EntryTable;
use App\Model\PschoolTable;
use App\Model\CourseTable;
use App\Model\ProgramTable;

use Illuminate\Http\Request;

class EntryTableTest extends TestCase
{
    private $admin;
    public function setUp()
    {
        parent::setUp();

        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
        
    }

// test portal/event - Thang 2017/05/09
/*
    Please comment portal/event TEST CODE to test school/home
    After running portal/event TEST CODE (insert/update new record), Please update TEST CODE of school/home in case testing school/home
*/
    // public function testPortalCourseComplete()
    // {
    //     echo "\n\t ====================";
    //     echo "\n\t TEST: UPDATE PORTAL COURSE (PORTAL/EVENT)";
    //     echo "\n\t ====================";
    //     $this->session(['login_account_id' => 0]);

    //     // set info to test
    //     $mkey = '3d91407d879f9315f350202ac1be9fde';
    //     $mail_data = MailMessageTable::getInstance()->getRow(array('message_key'=>$mkey));
    //     $entry_table = EntryTable::getInstance();
    //     $student_id = $mail_data['student_id'];
    //     $course_id = $mail_data['relative_ID'];
    //     $entry = array(
    //         'entry_type' => '2',
    //         'student_id' => $student_id,
    //         'relative_id' => $course_id,
    //         'relative_date' => date( 'Y-m-d H:i:s' ),
    //         'status' => '2',
    //         'enter' =>'',
    //         'last_refer_date' => date( 'Y-m-d H:i:s' ),
    //     );

    //     // Case 1 : join event
    //     echo "\nCase 1: join event\n";
    //     $entry['enter'] = 1;
    //     $insert_entry_id_1 =  $entry_table->save($entry);
    //     $new_entry_1 = $entry_table->load($insert_entry_id_1);
    //     $this->assertNotNull($new_entry_1, "Case 1: join event => Entry record is saved");
    //     if ($new_entry_1) {
    //         foreach ($new_entry_1 as $key => $value) {
    //             foreach ($entry as $idx => $item) {
    //                 if ($idx == $key) {
    //                     $this->assertEquals($item, $value, "$idx is not saved in DB ");
    //                 }
    //             }
    //         }
    //         // $this->assertArraySubset($entry, $new_entry_1 , "Case 1: join event => Entry record is not saved completely");
    //     }

    //     // Case 2 : unjoin event
    //     echo "\nCase 2: unjoin event\n";
    //     $entry['enter'] = 0;
    //     $insert_entry_id_2 =  $entry_table->save($entry);
    //     $new_entry_2 = $entry_table->load($insert_entry_id_2);
    //     $this->assertNotNull($new_entry_1, "Case 2: unjoin event => Entry record is saved");
    //     if ($new_entry_2) {
    //         foreach ($new_entry_2 as $key => $value) {
    //             foreach ($entry as $idx => $item) {
    //                 if ($idx == $key) {
    //                     $this->assertEquals($item, $value, "$idx is not saved in DB ");
    //                 }
    //             }
    //         }
    //         // $this->assertArraySubset($entry, $new_entry_2 , "Case 1: join event => Entry record is not saved completely");
    //     }
    // }
// end  ---- test portal/event - Thang 2017/05/09

// test school/home - Thang 2017/05/16
    // public function testGetEntriesOfCourses()
    // {
    //     echo "\n\t ====================";
    //     echo "\n\t TEST: GET LIST EVENT INFO OF CALENDAR (SCHOOL/HOME)";
    //     echo "\n\t ====================";
    //     $pschool_id = $this->admin['id'];
    //     // Load list event with pschool_id
    //     $course_list = CourseTable::getInstance()->getListOfCourse($pschool_id);
    //     $this->assertNotNull($course_list, "{$this->admin['login_id']}'s school hasn't any event");
    //     $this->assertEquals("2", count($course_list), "{$this->admin['login_id']}'s school hasn't exactly 2 events");
    //     // calculate total member & total fee of every course
    //     foreach ($course_list as $key => $course) {
    //         // calculate total member
    //         $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( $pschool_id, 
    //             array (
    //                     'entry_type' => 2,
    //                     'relative_id' => $course['id'],
    //                     'enter' => 1 
    //             ) );
    //         $total_member = count($entries);
    //         $total_fee = 0;
    //         // calculate total fee
    //         foreach ($entries as $value) {
    //             $total_fee += (float)$value['fee'];
    //         }
    //         if ($course['id'] == 108) {
    //             $this->assertNotNull($entries, "{$course['id']} has not any member.");
    //             $this->assertEquals('15', $total_member, "{$course['id']} has not exactly 15 members.");
    //             $this->assertEquals('300000000', $total_fee, "{$course['id']} does not collect exactly 300,000,000 円.");
    //         } else if ($course['id'] == 107) {
    //             $this->assertEquals('0', $total_member, "{$course['id']} has not exactly 0 member.");
    //             $this->assertEquals('0', $total_fee, "{$course['id']} does not collect exactly 0 円.");
    //         }
    //     }
    // }

    // public function testGetListNoticeNews()
    // {
    //     echo "\n\t ====================";
    //     echo "\n\t TEST: GET LIST NOTICE NEWS (SCHOOL/HOME)";
    //     echo "\n\t ====================";
    //     $pschool_id = $this->admin['id'];

    //     echo "\n\t +++++";
    //     echo "\n\t TEST_CASE: GET LASTEST NOTICE NEWS ";
    //     echo "\n\t +++++";
    //     $period = null;
    //     $notice_news_lastest = EntryTable::getInstance ()->getListbyNews ( $pschool_id );
    //     $this->assertNotNull($notice_news_lastest, "{$pschool_id} has not any notice_news.");
    //     $this->assertEquals('15', count($notice_news_lastest), '{$pschool_id} has not exactly 15 notice_news.({count($notice_news)})');

    //     echo "\n\t +++++";
    //     echo "\n\t TEST_CASE: GET NOTICE NEWS SINCE 7 DAYS AGO";
    //     echo "\n\t +++++";
    //     $period = 7;
    //     $notice_news_7 = EntryTable::getInstance ()->getListbyNews ( $pschool_id, $period );
    //     $this->assertNotNull($notice_news_7, "{$pschool_id} has not any notice_news.");
    //     $this->assertEquals('2', count($notice_news_7), '{$pschool_id} has not exactly 2 notice_news.({count($notice_news_7)})');

    //     echo "\n\t +++++";
    //     echo "\n\t TEST_CASE: GET NOTICE NEWS SINCE 30 DAYS AGO";
    //     echo "\n\t +++++";
    //     $period = 30;
    //     $notice_news_30 = EntryTable::getInstance ()->getListbyNews ( $pschool_id, $period );
    //     $this->assertNotNull($notice_news_30, "{$pschool_id} has not any notice_news.");
    //     $this->assertEquals('14', count($notice_news_30), '{$pschool_id} has not exactly 14 notice_news.({count($notice_news_30)})');
    // }

    // public function testUpdateViewDate()
    // {
    //     echo "\n\t ====================";
    //     echo "\n\t TEST: UPDATE VIEW DATE OF ENTRY (SCHOOL/HOME)";
    //     echo "\n\t ====================";
    //     $pschool_id = $this->admin['id'];
    //     $entryTable = EntryTable::getInstance();
    //     $entry_id = 320;
    //     $entry = $entryTable->load($entry_id);
    //     $this->assertNotNull($entry, "{$entry_id} has not info.");
    //     if ($entry) {
    //         $now = date('Y-m-d h:i:s');
    //         $entry['view_date'] = $now;
    //         $updated_entry_id = $entryTable->updateRow($entry, array('id'=>$entry['id']));
    //         $updated_entry = $entryTable->load($entry_id);
    //         $this->assertNotNull($entry, "{$entry_id} is not saved successfully.");
    //         $this->assertEquals($now, $updated_entry['view_date'], "{$entry_id}-view_date is not saved exactly " );
    //     }
    // }
// end ---- test school/home - Thang 2017/05/16
// test school/course - Kieu 2017/05/19
//    public function testGetStudentListbyEventTypeAxis() {
//        echo "\n\t ====================";
//        echo "\n\t TEST: LOAD JOINED STUDENT BY EVENT ID";
//        echo "\n\t ====================";
//
//        $course_list =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
//        echo "\n\tList Event for {$this->admin['login_id']} have ".count($course_list)." records\n\n";
//
//        echo "\n\tLoop List Event to fine joined members\n";
//        echo "\n\t ====================";
//        $entry_list = array();
//        foreach ($course_list as $key => $value) {
//            echo "\n\t EVENT {$value['course_title']} ";
//
//            $entry_list = EntryTable::getInstance()->getStudentListbyEventTypeAxis($this->admin['id'], array('entry_type'=>2, 'relative_id'=>$value['id'], 'enter'=>1));
//            $total_member = 0;
//            foreach ($entry_list as $entry) {
//                if ($entry['total_member']) {
//                    $total_member += $entry['total_member'];
//                }
//            }
//            echo "HAS ".$total_member . " joined members. ";
//        }
//
//        $this->assertNotNull($entry_list);
//
//
//    }
// end test school/course - Kieu 2017/05/19

// test school/Mailmessage - Kieu 2017/05/19
    public function testGetTotalFeeByEventId()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET TOTAL FEE BY EVENT ID";
        echo "\n\t ====================";

//        TODO get list event
        $course_list =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
        echo "\n\tList Event for {$this->admin['login_id']} have ".count($course_list)." records\n\n";

        echo "\n\tLoop List Event to display total fee\n";
        echo "\n\t ====================";
        foreach ($course_list as $course) {
            $total_fee = EntryTable::getInstance ()->getTotalFeeByEventId($course['id']);
            echo "\n\tEvent {$course['course_title']} total fee : ". number_format($total_fee)."\n";
            $this->assertNotNull($total_fee);

        }

    }

    public function testGetTotalFeeByProgramId()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET TOTAL FEE BY PROGRAM ID";
        echo "\n\t ====================";
//        TODO get list program
        $program_list =  ProgramTable::getInstance()->getProgramList($this->admin['id']);
        echo "\n\tList Program for {$this->admin['login_id']} have ".count($program_list)." records\n";

        echo "\n\tLoop List Event to display total fee\n";
        echo "\n\t ====================";
        foreach ($program_list as $program) {
            $total_fee = EntryTable::getInstance ()->getTotalFeeByProgramId($program['id']);
            echo "\n\tProgram {$program['program_name']} total fee : ". number_format($total_fee)."\n";
            $this->assertNotNull($total_fee);

        }
    }
// end test school/Mailmessage - Kieu 2017/05/19
}
