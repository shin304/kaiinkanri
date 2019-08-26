<?PHP

namespace App\Model;

class ReportCardDetailTable extends DbModel {
    
    /**
     *
     * @var ReportCardDetailTable
     */
    private static $_instance = null;
    protected $table = 'report_card_detail';
    public $timestamps = false;
    
    /**
     *
     * @return ReportCardDetailTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ReportCardDetailTable ();
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
        
        $sql = " SELECT RCD.*, RCL.card_name, MST.sort_no ";
        $sql .= " FROM report_card_list AS RCL ";
        $sql .= " INNER JOIN report_card_detail AS RCD ";
        $sql .= " ON RCL.id = RCD.card_list_id ";
        $sql .= " INNER JOIN m_subject_template AS MST ";
        $sql .= " ON RCD.m_template_id = MST.id ";
        $sql .= " WHERE RCD.pschool_id = ? ";
        $sql .= " AND RCD.student_id = ? ";
        $sql .= " AND RCL.delete_date IS NULL ";
        $sql .= " AND RCD.delete_date IS NULL ";
        if (isset ( $cond ) && isset ( $cond ['report_card_id'] ) && ! empty ( $cond ['report_card_id'] )) {
            $sql .= " AND RCD.report_card_id = ? ";
            $bind [] = $cond ['report_card_id'];
        }
        if (isset ( $cond ) && isset ( $cond ['card_list_id'] ) && ! empty ( $cond ['card_list_id'] )) {
            $sql .= " AND RCD.card_list_id = ? ";
            $bind [] = $cond ['card_list_id'];
        }
        if (isset ( $cond ) && isset ( $cond ['school_category'] ) && is_numeric ( $cond ['school_category'] )) {
            $sql .= " AND RCD.school_category = ? ";
            $bind [] = $cond ['school_category'];
        }
        if (isset ( $cond ) && isset ( $cond ['school_year'] ) && is_numeric ( $cond ['school_year'] )) {
            $sql .= " AND RCD.school_year = ? ";
            $bind [] = $cond ['school_year'];
        }
        if (isset ( $cond ) && isset ( $cond ['card_name'] ) && ! empty ( $cond ['card_name'] )) {
            $sql .= " AND RCL.card_name = '%" . $cond ['card_name'] . "%'";
        }
        
        $sql .= " ORDER BY RCD.school_category DESC, RCD.school_year DESC, RCD.card_date DESC, RCD.card_list_id DESC, MST.sort_no ASC ";
        
        $search_result = json_decode ( json_encode ( $this->fetchAll ( $sql, $bind ) ), true );
        
        // ---------------------------------------------------------------------
        // ここから
        // ---------------------------------------------------------------------
        $search_list = array ();
        if (count ( $search_result ) > 0) {
            // 検索自体は、学年単位で検索を行うハズ。日付が新しいものからソートされている
            // その学年の最新教科・科目の定義のみ対象とする
            // 教科・項目数 + 1 の配列（名称）を作成
            $school_category = null;
            $school_year = null;
            // $exam_from_date = null;
            $card_list_id = 0;
            $m_head_id = 0;
            $Rows = array ();
            
            $first = true;
            foreach ( $search_result as $result_item ) {
                if ($first) {
                    $school_category = $result_item ['school_category'];
                    $school_year = $result_item ['school_year'];
                    $card_date = $result_item ['card_date'];
                    $card_list_id = $result_item ['card_list_id'];
                    $m_head_id = $result_item ['m_head_id'];
                    // 通知表の教科・科目取得
                    $subject = MSubjectHeadTable::getInstance ()->getSpecificSubjectCourseList ( $result_item ['m_head_id'] );
                    for($idx = 0; $idx < count ( $subject ) + 1; $idx ++) {
                        $Rows [$idx] = null;
                    }
                    $Rows ['id'] = $result_item ['id'];
                    $Rows [0] = $result_item ['card_name']; // 名称
                    $first = false;
                }
                if ($result_item ['school_category'] == $school_category && $result_item ['school_year'] == $school_year && $result_item ['m_head_id'] == $m_head_id) {
                    
                    if ($result_item ['card_list_id'] == $card_list_id) {
                        // 前回と同じテスト
                    } else {
                        // テストが変わった
                        // 以前の分を区切る
                        $search_list [] = $Rows;
                        
                        // $exam_from_date = $result_item['exam_from_date'];
                        $card_list_id = $result_item ['card_list_id'];
                        $m_head_id = $result_item ['m_head_id'];
                        
                        // 新しく作成
                        $Rows = array ();
                        
                        for($idx = 0; $idx < count ( $subject ) + 1; $idx ++) {
                            $Rows [$idx] = null;
                        }
                        
                        $Row ['id'] = $result_item ['id'];
                        $Rows [0] = $result_item ['card_name']; // 名称
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
    public function getScoreData($pschool_id, $student_id, $report_id = null) {
        $sql = " SELECT * ";
        $sql .= " FROM report_card_detail ";
        $sql .= " WHERE id = " . $report_id;
        $detail_row = $this->fetch ( $sql );
        
        if (empty ( $detail_row ) || count ( $detail_row ) < 1) {
            return null;
        }
        
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $student_id;
        $bind [] = $detail_row ['card_list_id'];
        // $bind[] = $detail_row['card_date'];
        $bind [] = $detail_row ['m_head_id'];
        
        $sql = " SELECT RCD.*, RCL.card_name, MST.sort_no ";
        $sql .= " FROM report_card_list AS RCL ";
        $sql .= " INNER JOIN report_card_detail AS RCD ";
        $sql .= " ON RCL.id = RCD.card_list_id ";
        $sql .= " INNER JOIN m_subject_template AS MST ";
        $sql .= " ON RCD.m_template_id = MST.id ";
        $sql .= " WHERE RCD.pschool_id = ? ";
        $sql .= " AND RCD.student_id = ? ";
        $sql .= " AND RCD.card_list_id = ? ";
        // $sql .= " AND RCD.card_date = ? ";
        $sql .= " AND RCD.m_head_id = ? ";
        if (isset ( $detail_row ['school_category'] ) && is_numeric ( $detail_row ['school_category'] )) {
            $sql .= " AND RCD.school_category = ? ";
            $bind [] = $detail_row ['school_category'];
        }
        if (isset ( $detail_row ['school_year'] ) && is_numeric ( $detail_row ['school_year'] )) {
            $sql .= " AND RCD.school_year = ? ";
            $bind [] = $detail_row ['school_year'];
        }
        $sql .= " AND RCL.delete_date IS NULL ";
        $sql .= " AND RCD.delete_date IS NULL ";
        
        $sql .= " ORDER BY RCD.school_category DESC, RCD.school_year DESC, RCD.card_list_id DESC, MST.sort_no ASC ";
        
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
}