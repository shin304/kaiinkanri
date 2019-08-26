<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\MailMessageTable;
use App\Model\PschoolTable;
use App\Model\CourseTable;
use Illuminate\Http\Request;
use DB;

class MailMessageTableTest extends TestCase
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

// test portal/event - Thang 2017/05/09
/*
    Please comment portal/event TEST CODE to test school/home
    After running portal/event TEST CODE (insert/update new record), Please update TEST CODE of school/home in case testing school/home
*/
    // public function testGetRowForShowPortal()
    // {
    //     echo "\nFunction: testGetRowForShowPortal\n";
    //     // $mkey existed
    //     $mkey = $this->test_item->message_key; 

    //     echo "\nCase 1: key existed  -  Key: {$mkey}\n";
    //     $type = null;
    //     $result = MailMessageTable::getInstance()->getRowForShowPortal($mkey, $type);
    //     $a = $this->assertNotNull($result, "\nCase: key existed  -  Key: {$mkey} \n=> Check Mail Message is existed: Fail " );

    //     // $mkey not exists
    //     echo "\nCase 2: key does not exist  -  Key: 11111111111111111111111111111111\n";
    //     $mkey = '11111111111111111111111111111111'; 
    //     $type = null;
    //     $result = MailMessageTable::getInstance()->getRowForShowPortal($mkey, $type);
    //     $this->assertNull($result, "\nCase: key does not exist  -  Key: 11111111111111111111111111111111 \n=> Check Mail Message is not existed: Fail ");

    //     // $mkey is null
    //     echo "\nCase 3: key is null\n";
    //     $mkey = ''; 
    //     $type = null;
    //     $result = MailMessageTable::getInstance()->getRowForShowPortal($mkey, $type);
    //     $this->assertNull($result, "\nCase: key is null \n=> Check Mail Message is not existed: Fail");
    // }

    // public function testSaveLastReferDate()
    // {
    //     echo "\nFunction: testSaveLastReferDate\n";
    //     $this->session(['login_account_id' => 0]);
    //     $MailMessageTable = MailMessageTable::getInstance();
    //     $mkey = $this->test_item->message_key;
    //     $type = null;
    //     $msg = $MailMessageTable->getRowForShowPortal($mkey, $type);
    //     $now = date( 'Y-m-d H:i:s' );
    //     $msg['last_refer_date'] = $now;
    //     $MailMessageTable->save($msg);
    //     $new_msg = $MailMessageTable->load($msg['id']);
    //     $this->assertEquals($now, $new_msg['last_refer_date'], 'SaveLastReferDate failed');
    // }
// end  ---- test portal/event - Thang 2017/05/09

// test school/home - Thang 2017/05/16
    // public function testGetListEventMail()
    // {
    //     echo "\n\t ====================";
    //     echo "\n\t TEST: GET LIST EVENT MAIL MESSAGE (SCHOOL/HOME)";
    //     echo "\n\t ====================";
    //     $pschool_id = $this->admin['id'];

    //     echo "\n\t +++++";
    //     echo "\n\t TEST_CASE: GET LASTEST EVENT MAIL MESSAGE ";
    //     echo "\n\t +++++";
    //     $period = null;
    //     $event_mail_lastest = MailMessageTable::getInstance ()-> getEventGroupMailList ($pschool_id, $period);
    //     $this->assertNotNull($event_mail_lastest, "{$pschool_id} has not any event_mail.");
    //     $this->assertEquals('2', count($event_mail_lastest), '{$pschool_id} has not exactly 2 event_mail.({count($event_mail_lastest)})');

    //     echo "\n\t +++++";
    //     echo "\n\t TEST_CASE: GET LIST EVENT MAIL MESSAGE SINCE 7 DAYS AGO";
    //     echo "\n\t +++++";
    //     $period = 7;
    //     $event_mail_7 = MailMessageTable::getInstance ()-> getEventGroupMailList ($pschool_id, $period);
    //     $this->assertNotNull($event_mail_7, "{$pschool_id} has not any event_mail.");
    //     $this->assertEquals('0', count($event_mail_7), '{$pschool_id} has not exactly 0 event_mail.({count($event_mail_7)})');

    //     echo "\n\t +++++";
    //     echo "\n\t TEST_CASE: GET LIST EVENT MAIL MESSAGE SINCE 30 DAYS AGO";
    //     echo "\n\t +++++";
    //     $period = 30;
    //     $event_mail_30 = MailMessageTable::getInstance ()-> getEventGroupMailList ($pschool_id, $period);
    //     $this->assertNotNull($event_mail_30, "{$pschool_id} has not any event_mail.");
    //     $this->assertEquals('2', count($event_mail_30), '{$pschool_id} has not exactly 2 event_mail.({count($notice_news_30)})');
    // }
// end ---- test school/home - Thang 2017/05/16
// test school/course - Kieu 2017/05/19
//     public function testGetEventMailListAxis() {
//         echo "\n\t ====================";
//         echo "\n\t TEST: GET LIST MAIL BY EVENT ID";
//         echo "\n\t ====================";
//         $pschool_id = $this->admin['id'];
//         $course_list =  CourseTable::getInstance()->getCourseList4Top($this->admin['id']);
//         echo "\n\tList Event for {$this->admin['login_id']} have ".count($course_list)." records\n\n";
//
//         $last_course = end($course_list);
//         $mailmsg_tbl = MailMessageTable::getInstance();
//         $list = $mailmsg_tbl->getEventMailListAxis($pschool_id, 2, $last_course['id']);
//         echo "\n\tList Student for Last Event {$last_course['course_title']} have ".count($list)." students \n";
//         $this->assertNotNull($list);
//
//         foreach ($list as &$row)
//         {
//             $condition = array(
//                 'type'          => 3,
//                 'relative_ID'   => $last_course['id'],
//                 'student_id'    => $row['student_id'],
//
//             );
//             // Update by Kieu: 2017/06/06 : Count send mail = total_send
//             $mail_message_record = $mailmsg_tbl->getRow($condition);var_dump($mail_message_record);
//             if ($mail_message_record) {
//                 $delivery_count = $mail_message_record['total_send'];
//                 $confirmation   = ($mail_message_record['last_refer_date'] != null)? 1 : 0;
//             } else {
//                 $delivery_count = 0;
//                 $confirmation   = 0;
//             }
//             $row['delivery_count'] = $delivery_count;
//             $row['confirmation'] = $confirmation;
//             $row['selected'] = 0;
//
//             $row['fee'] = number_format(floor($row['fee']));
//         }
//
//         echo "\n\t Member Number \t Member Name \t  Send Mail Count  ... ";
//         foreach ($list as $row) {
//             echo "\n\t " . $row['student_no'] . " \t " . $row['student_name'] . " \t ". $row['delivery_count'] . " \t ...";
//         }
//     }
// end test school/course - Kieu 2017/05/19

// test mail_batch - Thang 2017/06/15
    /*public function testGetListEventMailToSend()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET LIST ALL MAIL MESSAGE TO SEND (MAIL_BATCH)";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];
        $mailMessageTable = MailMessageTable::getInstance();
        $mail_messages = $mailMessageTable->getListMailOnSchedule($pschool_id);
        $this->assertNotNull($mail_messages, "{$pschool_id} has not any mail to send.");
        $this->assertEquals('3', count($mail_messages), '{$pschool_id} has not exactly 3 mail.({count($mail_messages)})');

        foreach ($mail_messages as $key => $mail_message) {
            $mail = array(  'id'            => $mail_message['id'],
                            'send_date'     => date("Y-m-d H:i:s"),
                            'total_send'    => $mail_message['total_send'] + 1);
            $saved_id = $mailMessageTable->save($mail, true);
            $saved_mail = $mailMessageTable->load($saved_id);
            $this->assertNotNull($saved_mail);
            $this->assertEquals($mail['send_date'], $saved_mail['send_date'], "{$mail_message['id']}'s send_date has not saved sucessfully");
            $this->assertEquals($mail['total_send'], $saved_mail['total_send'], "{$mail_message['id']}'s total_send has not saved sucessfully");
        }
    }*/
// end ---- test mail_batch - Thang 2017/06/15

// test mail_batch - Kieu 2017/06/19
    public function testGetMailInfoToSend()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET MAIL INFO TO SEND";
        echo "\n\t ====================";
        $pschool_id = $this->admin['id'];
        $mailMessageTable = MailMessageTable::getInstance();

//        TODO get one mail test
        $mail_id = $this->test_item->id;
        echo "\nTest  -  Key: {$mail_id}\n";

//        TODO get mail info by mail_id
        $mail_messages = $mailMessageTable->getMailInfoToSend($mail_id);

        $this->assertArrayHasKey('student_mailaddress', $mail_messages, " send mail to {$mail_messages['student_mailaddress']}.");

    }
// end ---- test mail_batch - Thang 2017/06/15
}
