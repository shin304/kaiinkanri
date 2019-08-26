<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Model\PschoolTable;

class CoachControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $login_id = 'test@asto-system.com';
        $password = '12345678';
        $school = PschoolTable::getInstance ()->getLoginInfo ( $login_id, $password );

        session(['login_account_id' => 0]);
        session(['school.login' => $school]);
        session(['lang_code' => 2]);
    }
    public function testIndex() {
    }
}
