<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\School\_BaseSchoolController;
use Illuminate\Http\Request;
use App\Model\ExamScoreTable;
use App\Model\MyRecordTable;
use App\Model\ReportCardTable;
use App\Lang;

/**
 * 逕溷ｾ呈ュ蝣ｱ
 */
class StudentInfoController extends _BaseSchoolController {
    private static $TOP_URL = 'student';
    const SESSION_REQUESTS_KEY = 'session.school.student.requests';
    const SESSION_SEARCH_KEY = 'session.school.student.search.key';
    public function init() {
        parent::init ();
    }
    
    /**
     * 謌千ｸｾ諠�蝣ｱ繧偵Ο繝ｼ繝�
     */
    public static function loadScoreDetail(Request $request) {
        // ---------------
        // 謌千ｸｾ縺ｮ蜿門ｾ�
        // ---------------
        if ($request->has ( 'id' )) {
            $student_id = $request->id;
        } else {
            $student_id = $request->student_id;
        }
        $app_score_list = MyRecordTable::getInstance ()->getAppScoreList ( session ( 'school.login' ) ['id'], $student_id );
        $exam_score_list = ExamScoreTable::getInstance ()->getExamList ( array (
                'pschool_id' => session ( 'school.login' ) ['id'],
                'student_id' => $student_id 
        ) );
        // $exam_score_list = ExamScoreTable::getInstance()->getActiveList(array('pschool_id'=>$_SESSION['school.login']['id'], 'student_id' => $student_id));
        // $report_list = ReportCardTable::getInstance()->getActiveList(array('pschool_id'=>$_SESSION['school.login']['id'], 'student_id' => $student_id));
        $report_list = ReportCardTable::getInstance ()->getReportList ( array (
                'pschool_id' => session ( 'school.login' ) ['id'],
                'student_id' => $student_id 
        ) );
        
        // --------------
        // 陦ｨ遉ｺ諠�蝣ｱ縺ｮ險ｭ螳�
        // --------------
        // $this->assignVars('app_score_list', $app_score_list);
        // $this->assignVars('score_list', $exam_score_list);
        $score_list = $exam_score_list;
        // $this->assignVars('report_list', $report_list);
    }
}

