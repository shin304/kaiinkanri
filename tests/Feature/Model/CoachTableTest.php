<?php

namespace Tests\Feature\Model;

use App\Model\CoachTable;
use App\Model\PschoolTable;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CoachTableTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $login_id = 'test@asto-system.com';
        $password = '12345678';
        $school = PschoolTable::getInstance ()->getLoginInfo ( $login_id, $password );

        session(['login_account_id' => 0]);
        session(['school.login' => $school]);
    }

    public function testInsertCoach() {
        // Fixtures
        $coach = array();
        $coach['coach_name'] = 'テスト';
        $coach['coach_name_kana'] = 'テスト';
        $coach['coach_mail'] = 'testest@gmail.com';
        $coach['coach_pass1'] = md5('12345678');
        $coach['coach_pass2'] = md5('12345678');

        // Execution
        $id = CoachTable::getInstance()->save($coach);

        // Assertion
        $this->assertNotEmpty($id);
        echo "\n testInsertCoach";
        echo "\n Inserted Id: " . $id;
        echo "\n TEST RESULT: SUCCESSFUL";
        echo "\n ==============================";
    }

    public function testGetCoachById() {
        // Fixtures
        $coach_id = 43;

        // Execution
        $coach = CoachTable::getInstance()->find($coach_id);

        // Assertion
        $this->assertEquals(43, $coach->id);
        echo "\n testGetCoachById";
        echo "\n Coach Id: " . $coach->id;
        echo "\n Coach Name: " . $coach->coach_name;
        echo "\n TEST RESULT: SUCCESSFUL";
        echo "\n ==============================";
    }

    public function testGetCoachActiveList() {
        // Fixtures

        // Execution
        $coach = CoachTable::getInstance()->getActiveCoachList();

        // Assertion
        $this->assertNotNull($coach);
        echo "\n testGetCoachActiveList";
        echo "\n Coach Size: " . count($coach);
        foreach ($coach as $item) {
            $this->assertNull($item->delete_date);
        }
        echo "\n TEST RESULT: SUCCESSFUL";
        echo "\n ==============================";
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
