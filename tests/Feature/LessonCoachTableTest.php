<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\LessonCoachTable;
use App\Model\PschoolTable;
use App\Model\ProgramTable;
use App\Model\LessonTable;
class LessonCoachTableTest extends TestCase
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
    public function testGetCoachByLesson()
    {
        echo "\n\t ====================";
        echo "\n\t TEST: GET COACH BY LESSION";
        echo "\n\t ====================";
        // Load list event with student_count
        $result =  ProgramTable::getInstance()->getProgramList($this->admin['id']);
        echo "\n\tList Program for {$this->admin['login_id']} have ".count($result)." records\n";

        foreach ($result as $program) {
            $lesson_rows = LessonTable::getInstance()->getActiveList(array('program_id'=>$program['id']),array('start_date'));
            if (count($lesson_rows) > 0) {
                foreach ($lesson_rows as $value) {
                    // 講師
                    $coach_rows = LessonCoachTable::getInstance()->getCoachByLesson($value['id'], 2);
                    if (is_array($coach_rows)) {
                        foreach ($coach_rows as $key=>$coach) {
                            if ($key==0) {
                                echo "\n\tCoach 1 id : {$coach['id']} \n";

                            } else {
                                echo "\n\tCoach 2 id : {$coach['id']} \n";

                            }
                        }
                    }
                    $this->assertNotNull($coach_rows);
                }
            }
        }
        $this->assertNotNull($result);
    }
}
