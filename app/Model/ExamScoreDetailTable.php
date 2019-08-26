<?PHP

namespace App\Model;

class ExamScoreDetailTable extends DbModel {
    
    /**
     *
     * @var ExamScoreDetailTable
     */
    private static $_instance = null;
    protected $table = 'exam_score_detail';
    public $timestamps = false;
    /**
     *
     * @return ExamScoreDetailTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ExamScoreDetailTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    
    /**
     * 一覧取得
     *
     * @param unknown $pschool_id            
     * @param unknown $student_id            
     * @param string $cond            
     */
    public function getSearchList($pschool_id, $student_id, $cond = null) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $student_id;
        
        $sql = " SELECT ESD.*, ESL.exam_name, MST.sort_no ";
        $sql .= " FROM exam_score_list AS ESL ";
        $sql .= " INNER JOIN exam_score_detail AS ESD ";
        $sql .= " ON ESL.id = ESD.exam_list_id ";
        $sql .= " INNER JOIN m_subject_template AS MST ";
        $sql .= " ON ESD.m_template_id = MST.id ";
        $sql .= " WHERE ESD.pschool_id = ? ";
        $sql .= " AND ESD.student_id = ? ";
        $sql .= " AND ESL.delete_date IS NULL ";
        $sql .= " AND ESD.delete_date IS NULL ";
        
        if (isset ( $cond ) && isset ( $cond ['subject_course_id'] ) && ! empty ( $cond ['subject_course_id'] )) {
            $sql .= " AND ESD.m_head_id = ? ";
            $bind [] = $cond ['subject_course_id'];
        }
        if (isset ( $cond ) && isset ( $cond ['exam_list_id'] ) && ! empty ( $cond ['exam_list_id'] )) {
            $sql .= " AND ESD.exam_list_id = ? ";
            $bind [] = $cond ['exam_list_id'];
        }
        if (isset ( $cond ) && isset ( $cond ['school_category'] ) && is_numeric ( $cond ['school_category'] )) {
            $sql .= " AND ESD.school_category = ? ";
            $bind [] = $cond ['school_category'];
        }
        if (isset ( $cond ) && isset ( $cond ['school_year'] ) && is_numeric ( $cond ['school_year'] )) {
            $sql .= " AND ESD.school_year = ? ";
            $bind [] = $cond ['school_year'];
        }
        if (isset ( $cond ) && isset ( $cond ['exam_name'] ) && ! empty ( $cond ['exam_name'] )) {
            $sql .= " AND EL.exam_name = '%" . $cond ['exam_name'] . "%'";
        }
        
        $sql .= " ORDER BY ESD.school_category DESC, ESD.school_year DESC, ESD.exam_from_date DESC, ESD.exam_list_id DESC, MST.sort_no ASC ";
        
        $search_result = json_decode ( json_encode ( $this->fetchAll ( $sql, $bind ) ), true );
        
        // ---------------------------------------------------------------------
        // ここから
        // ---------------------------------------------------------------------
        $search_list = array ();
        if (count ( $search_result ) > 0) {
            // 検索自体は、学年単位で検索を行うハズ。日付が新しいものからソートされている
            // その学年の最新教科・科目の定義のみ対象とする
            // 教科・項目数 + 2 の配列（試験名・試験日付）を作成
            $school_category = null;
            $school_year = null;
            $exam_from_date = null;
            $exam_list_id = 0;
            $m_head_id = 0;
            $Rows = array ();
            
            $first = true;
            foreach ( $search_result as $result_item ) {
                if ($first) {
                    $school_category = $result_item ['school_category'];
                    $school_year = $result_item ['school_year'];
                    $exam_from_date = $result_item ['exam_from_date'];
                    $exam_list_id = $result_item ['exam_list_id'];
                    $m_head_id = $result_item ['m_head_id'];
                    // 成績の教科・科目取得
                    $subject = MSubjectHeadTable::getInstance ()->getSpecificSubjectCourseList ( $result_item ['m_head_id'] );
                    for($idx = 0; $idx < count ( $subject ) + 2; $idx ++) {
                        $Rows [$idx] = null;
                    }
                    $Rows ['id'] = $result_item ['id'];
                    $Rows [0] = $result_item ['exam_name']; // 名称
                    $Rows [count ( $subject ) + 1] = $result_item ['exam_from_date']; // 試験日
                    $first = false;
                }
                if ($result_item ['school_category'] == $school_category && $result_item ['school_year'] == $school_year && $result_item ['m_head_id'] == $m_head_id) {
                    
                    if ($result_item ['exam_from_date'] == $exam_from_date && $result_item ['exam_list_id'] == $exam_list_id) {
                        // 前回と同じテスト
                    } else {
                        // テストが変わった
                        // 以前の分を区切る
                        $search_list [] = $Rows;
                        
                        $exam_from_date = $result_item ['exam_from_date'];
                        $exam_list_id = $result_item ['exam_list_id'];
                        $m_head_id = $result_item ['m_head_id'];
                        
                        // 新しく作成
                        $Rows = array ();
                        
                        for($idx = 0; $idx < count ( $subject ) + 2; $idx ++) {
                            $Rows [$idx] = null;
                        }
                        
                        $Rows ['id'] = $result_item ['id'];
                        $Rows [0] = $result_item ['exam_name']; // 名称
                        $Rows [count ( $subject ) + 1] = $result_item ['exam_from_date']; // 試験日
                    }
                    
                    // データを対象配列インデックスへ設定
                    for($idx = 0; $idx < count ( $subject ); $idx ++) {
                        if ($subject [$idx] ['id'] == $result_item ['m_template_id']) {
                            $Rows [$idx + 1] = $result_item ['score'];
                            break;
                        }
                    }
                } else {
                    break;
                }
            }
            // 区切る
            $search_list [] = $Rows;
        }
        return $search_list;
    }
    
    /**
     *
     * @param unknown $pschool_id            
     * @param unknown $student_id            
     * @param string $score_id            
     * @return NULL|multitype:multitype:NULL unknown multitype:unknown multitype:NULL unknown
     */
    public function getScoreData($pschool_id, $student_id, $score_id = null) {
        $sql = " SELECT * ";
        $sql .= " FROM exam_score_detail ";
        $sql .= " WHERE id = " . $score_id;
        $score_row = $this->fetch ( $sql );
        
        if (empty ( $score_row ) || count ( $score_row ) < 1) {
            return null;
        }
        
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $student_id;
        $bind [] = $score_row ['exam_list_id'];
        $bind [] = $score_row ['exam_from_date'];
        $bind [] = $score_row ['exam_to_date'];
        $bind [] = $score_row ['m_head_id'];
        $bind [] = $score_row ['school_category'];
        $bind [] = $score_row ['school_year'];
        
        $sql = " SELECT ESD.*, ESL.exam_name, MST.sort_no ";
        $sql .= " FROM exam_score_list AS ESL ";
        $sql .= " INNER JOIN exam_score_detail AS ESD ";
        $sql .= " ON ESL.id = ESD.exam_list_id ";
        $sql .= " INNER JOIN m_subject_template AS MST ";
        $sql .= " ON ESD.m_template_id = MST.id ";
        $sql .= " WHERE ESD.pschool_id = ? ";
        $sql .= " AND ESD.student_id = ? ";
        $sql .= " AND ESD.exam_list_id = ? ";
        $sql .= " AND ESD.exam_from_date = ? ";
        $sql .= " AND ESD.exam_to_date = ? ";
        $sql .= " AND ESD.m_head_id = ? ";
        $sql .= " AND ESD.school_category = ? ";
        $sql .= " AND ESD.school_year = ? ";
        $sql .= " AND ESL.delete_date IS NULL ";
        $sql .= " AND ESD.delete_date IS NULL ";
        
        $sql .= " ORDER BY ESD.school_category DESC, ESD.school_year DESC, ESD.exam_from_date DESC, ESD.exam_list_id DESC, MST.sort_no ASC ";
        
        $search_result = json_decode ( json_encode ( $this->fetchAll ( $sql, $bind ) ), true );
        
        // ---------------------------------------------------------------------
        // ここから
        // ---------------------------------------------------------------------
        $search_list = array ();
        
        if (count ( $search_result ) > 0) {
            // 検索自体は、学年単位で検索を行うハズ。日付が新しいものからソートされている
            
            // 成績の教科・科目取得
            $subject = MSubjectHeadTable::getInstance ()->getSpecificSubjectCourseList ( $search_result [0] ['m_head_id'] );
            foreach ( $subject as $subject_item ) {
                $bExist = false;
                for($idx = 0; $idx < count ( $search_result ); $idx ++) {
                    if ($subject_item ['id'] == $search_result [$idx] ['m_template_id']) {
                        
                        $search_list [] = $search_result [$idx];
                        $bExist = true;
                        break;
                    }
                }
                if (! $bExist) {
                    $search_list [] = array ();
                }
            }
        }
        
        return $search_list;
    }
    
    /**
     * 試験結果の学校区分・学年
     *
     * @param unknown $pschool_id            
     * @param unknown $student_id            
     */
    public function getExamSchool($pschool_id, $student_id) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $student_id;
        
        $sql = " SELECT school_category,  school_year ";
        $sql .= " FROM exam_score_detail ";
        $sql .= " WHERE pschool_id = ? ";
        $sql .= " AND student_id = ? ";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " GROUP BY school_category, school_year ";
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * 編集画面用
     *
     * @param unknown $pschool_id            
     * @param unknown $student_id            
     * @param unknown $score_id            
     * @return multitype:unknown
     */
    public function getDetailData($pschool_id, $student_id, $score_id) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $student_id;
        $bind [] = $score_id;
        
        $sql = " SELECT ESD.*, ESL.exam_name ";
        $sql .= " FROM exam_score_detail AS ESD ";
        $sql .= " INNER JOIN exam_score_list AS ESL ";
        $sql .= " ON ESD.exam_list_id = ESL.id ";
        $sql .= " WHERE ESD.pschool_id = ? ";
        $sql .= " AND ESD.student_id = ? ";
        $sql .= " AND ESD.id = ? ";
        $sql .= " AND ESD.delete_date IS NULL ";
        $list1 = $this->fetch ( $sql, $bind );
        
        $details = array ();
        
        if (count ( $list1 ) > 0) {
            $bind = array ();
            $bind [] = $pschool_id;
            $bind [] = $student_id;
            $bind [] = $list1 ['exam_list_id'];
            $bind [] = $list1 ['exam_from_date'];
            $bind [] = $list1 ['exam_to_date'];
            $bind [] = $list1 ['school_category'];
            $bind [] = $list1 ['school_year'];
            $bind [] = $list1 ['m_head_id'];
            
            $sql = " SELECT * ";
            $sql .= " FROM exam_score_detail AS ESD ";
            $sql .= " INNER JOIN exam_score_list AS ESL ";
            $sql .= " ON ESD.exam_list_id = ESL.id ";
            $sql .= " WHERE ESD.pschool_id = ? ";
            $sql .= " AND ESD.student_id = ? ";
            $sql .= " AND ESD.exam_list_id = ? ";
            $sql .= " AND ESD.exam_from_date = ? ";
            $sql .= " AND ESD.exam_to_date = ? ";
            $sql .= " AND ESD.school_category = ? ";
            $sql .= " AND ESD.school_year = ? ";
            $sql .= " AND ESD.m_head_id = ? ";
            $sql .= " AND ESD.delete_date IS NULL ";
            $sql .= " ORDER BY ESD.id ";
            
            $list2 = $this->fetchAll ( $sql, $bind );
            
            $details [] = $list1;
            foreach ( $list2 as $item2 ) {
                $details [] = $item2;
            }
        }
        return $details;
    }
}