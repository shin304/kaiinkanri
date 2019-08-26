<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MailTemplateTableTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    private $admin;
    public function setUp()
    {
        parent::setUp();

        $loginid = 'test@asto-system.com';
        $password = '12345678';
        $this->admin = $school = PschoolTable::getInstance ()->getLoginInfo ( $loginid, $password );
        
    }

    /*
     * test get list mail template
     *
     */

    public function testGetListMailTemplate()
    {
    	echo "\n\t *******************";
        echo "\n\t TEST: GET LIST MAIL TEMPLATE";
        echo "\n\t --------------------";
        $results = MailTemplateTable::getInstance ()->getListMailTemplate ();
        $this->assertNotNull ( $results, 'Has not any record in your list' );
        echo "\n\t *******************\n";
    }

    /*
     * test search mail template
     * @var mail_type
     *
     */

    public function testGetListMailTemplateByMailType()
    {
    	$mail_type = '3';

    	echo "\n\t *******************";
        echo "\n\t TEST: GET LIST MAIL TEMPLATE BY SERACH TYPE: " . $mail_type;
        echo "\n\t --------------------";
        $results = MailTemplateTable::getInstance ()->getListMailTemplateByMailType ($mail_type);
        $this->assertNotNull ( $results, 'Has not any record have type '. $mail_type );
        echo "\n\t ******************\n";
    }

    /*
     * test search mail template
     * @var id
     *
     */

    public function testGetListMailTemplateByID()
    {
    	$id = '2';

    	echo "\n\t *******************";
        echo "\n\t TEST: GET LIST MAIL TEMPLATE BY SERACH ID: " . $id;
        echo "\n\t --------------------";
        $results = MailTemplateTable::getInstance ()->getListMailTemplateByID ($id);
        $this->assertNotNull ( $results, 'Has not any record have id '. $id );
        echo "\n\t ******************\n";
    }

    /*
     * test insert mail template
     * @var user name
     * @var user mail_type
     * @var user subject
     * @var user body
     * @var user footer
     */

    public function testInsertMailTemplate()
    {
        echo "\n\t*********************";   
        echo "\n\t TEST: INSERT MAIL TEMPLATE";
        echo "\n\t --------------------";
        $name = "unit test insert";
        $mail_type = 2;
        $subject = "test insert program";
        $body = "test insert program";
        $footer = "footer";
        $result = MailTemplateTable::getInstance ()->insertMailTemplate ( $name, $mail_type, $subject, $body, $footer );
        $this->assertTrue($result);
        echo "\n\t *******************\n";
    }

    public function testDeleteMailTemplate()
    {
        echo "\n\t*********************";   
        echo "\n\t TEST: DELETE MAIL TEMPLATE";
        echo "\n\t --------------------";
        $id = 2;
        $result = MailTemplateTable::getInstance ()->deleteMailTemplate ( $id );
        $this->assertTrue($result);
        echo "\n\t *******************\n";
    }
}
