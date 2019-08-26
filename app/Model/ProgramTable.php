<?PHP

namespace App\Model;

require_once 'ClassTeacherRelTable.php';
require_once 'StudentClassTable.php';
use Illuminate\Support\Facades\DB;

class ProgramTable extends DbModel {
    
    /**
     *
     * @var ProgramTable
     */
    private static $_instance = null;
    protected $table = 'program';
    public $timestamps = false;
    /**
     *
     * @return ProgramTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ProgramTable ();
        }
        return self::$_instance;
    }
    
    // =========================================================================
    // ここから、アクシス柔術向け機能追加版
    // =========================================================================
    public function getProgramListHierarchy($pschool_id, $parents) {
        $bind = array ();
        $ids = "";
        foreach ( $parents as $item ) {
            if (! empty ( $ids )) {
                $ids .= ", ";
            }
            $ids .= $item;
        }
        
        $sql = " SELECT * ";
        $sql .= " FROM program ";
        $sql .= " WHERE pschool_id IN( " . $ids . " ) ";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " AND active_flag = 1 ";
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    public function getProgramAndLesson($pschool_ids) {
        $ids = "";
        $bind = array ();
        foreach ( $pschool_ids as $items ) {
            if (! empty ( $ids )) {
                $ids .= ", ";
            }
            $ids .= $items;
        }
        
        $sql = " SELECT P.id as program_id, P.program_name, L.* FROM lesson L ";
        $sql .= " LEFT JOIN program P ON P.id = L.program_id ";
        $sql .= " WHERE L.pschool_id IN (" . $ids . ") ";
        $sql .= " AND L.delete_date IS NULL ";
        $sql .= " AND L.active_flag = 1 ";
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    public function getProgramList($pschool, $request = null) {
        $sql = " SELECT p.*,CASE WHEN m1.mail_count is NULL THEN '未' ELSE '済' END AS mail_sent, CASE WHEN m1.mail_count is NULL THEN 0 ELSE m1.mail_count END AS mail_count, CASE WHEN m2.mail_viewed is NULL THEN 0 ELSE m2.mail_viewed END AS mail_viewed FROM program p";
        $sql .= " LEFT JOIN (select count(*) as mail_count ,type, relative_ID, SUM(total_send) as mail_count2 from mail_message msg WHERE delete_date is null Group By type, relative_ID) m1 on (m1.type = 5 AND m1.relative_ID = p.id) ";
        $sql .= " LEFT JOIN (select count(*) as mail_viewed ,type, relative_ID, last_refer_date, SUM(total_send) as mail_viewed2 from mail_message WHERE delete_date is null and last_refer_date is NOT NULL Group by type, relative_ID) m2 on (m2.type = 5 AND m2.relative_ID = p.id AND last_refer_date is NOT NULL) ";
        $sql .= " WHERE ";
        $sql .= " p.pschool_id = ? ";
        $sql .= " AND p.delete_date IS NULL ";
        $sql .= " AND p.active_flag = 1 ";
        $bind = array ();
        $bind [] = $pschool;
        if (! empty ( $request ['name'] )) {
            $sql .= "  AND p.program_name like ? ";
            $bind [] = "%" . $request ['name'] . "%";
        }

        if (!empty ($request ['recruitment_from']) && !empty ($request ['recruitment_to'])) {
            $sql .= " AND   IF(p.recruitment_finish IS NULL, 
                            !(DATE_FORMAT(p.recruitment_start,'%Y-%m-%d') > ?),
                            !(DATE_FORMAT(p.recruitment_start,'%Y-%m-%d') > ? OR DATE_FORMAT(p.recruitment_finish,'%Y-%m-%d') < ?) ) ";
            $bind [] = $request ['recruitment_to'];
            $bind [] = $request ['recruitment_to'];
            $bind [] = $request ['recruitment_from'];
        } else if (!empty ($request ['recruitment_from'])) {
            $sql .= " AND   IF(p.recruitment_finish IS NULL,
                            1,
                            !(DATE_FORMAT(p.recruitment_finish,'%Y-%m-%d') < ?) ) ";
            $bind [] = $request ['recruitment_from'];
        } else if (!empty ($request ['recruitment_to'])) {
            $sql .= " AND !(DATE_FORMAT(p.recruitment_start,'%Y-%m-%d') > ?) ";
            $bind [] = $request ['recruitment_to'];
        }

        if (!empty ($request ['start_date_from']) && !empty ($request ['start_date_to'])) {
            $sql .= " AND   IF(p.close_date IS NULL, 
                            !(DATE_FORMAT(p.start_date,'%Y-%m-%d %H:%i') > ?),
                            !(DATE_FORMAT(p.start_date,'%Y-%m-%d %H:%i') > ? OR DATE_FORMAT(p.close_date,'%Y-%m-%d %H:%i') < ?) ) ";
            $bind [] = $request ['start_date_to'];
            $bind [] = $request ['start_date_to'];
            $bind [] = $request ['start_date_from'];
        } else if (!empty ($request ['start_date_from'])) {
            $sql .= " AND   IF(p.close_date IS NULL,
                            1,
                            !(DATE_FORMAT(p.close_date,'%Y-%m-%d %H:%i') < ?) ) ";
            $bind [] = $request ['start_date_from'];
        } else if (!empty ($request ['start_date_to'])) {
            $sql .= " AND !(DATE_FORMAT(p.start_date,'%Y-%m-%d %H:%i') > ?) ";
            $bind [] = $request ['start_date_to'];
        }

        $sql .= " ORDER BY p.id DESC ";
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * プログラムの割引・割増取得
     *
     * @param unknown $pschool_id            
     * @param unknown $parent_id            
     * @param unknown $data_div            
     */
    public function getProgramAdjustList($pschool_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
        
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $month;
        // $bind [] = $year_month;
        $bind [] = $year_month;
        $bind [] = $parent_id;
        $bind [] = $pschool_id;
        
        $sql = " SELECT RPIAN.*, SCCSP.id AS student_id ";
        $sql .= " FROM ";
        $sql .= " ( ";
        $sql .= "     SELECT RP.* , IAN.name ";
        $sql .= "     FROM routine_payment AS RP ";
        $sql .= "     INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= "     ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= "     WHERE RP.delete_date IS NULL ";
        $sql .= "     AND RP.pschool_id = ? ";
        $sql .= "     AND RP.data_div = 1 ";
        $sql .= "     AND RP.active_flag = 1 ";
        $sql .= "     AND (RP.month = ? OR RP.month = 99) ";
        $sql .= " ) AS RPIAN ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        $sql .= "     SELECT SCC.class_id, SP.id ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, SC.class_id ";
        $sql .= "         FROM student_class AS SC ";
        $sql .= "         INNER JOIN class AS C ";
        $sql .= "         ON SC.class_id = C.id ";
        $sql .= "         WHERE SC.delete_date IS NULL ";
        // $sql .= " AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND SUBSTRING(C.start_date, 1, 7) = ? ";
        // $sql .= " AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        $sql .= "     ) AS SCC ";
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT S.id ";
        $sql .= "         FROM student AS S ";
        $sql .= "         INNER JOIN parent AS P ";
        $sql .= "         ON S.parent_id = P.id ";
        $sql .= "         WHERE S.delete_date IS NULL ";
        $sql .= "         AND S.parent_id = ? ";
        $sql .= "         AND S.pschool_id = ? ";
        $sql .= "     ) AS SP ";
        $sql .= "     ON SCC.student_id = SP.id ";
        $sql .= " ) AS SCCSP ";
        $sql .= " ON RPIAN.data_id = SCCSP.class_id ";
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * プログラムの割引・割増取得 会員版
     *
     * @param unknown $pschool_id            
     * @param unknown $parent_id            
     * @param unknown $data_div            
     */
    public function getProgramAdjustList_Axis($pschool_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );
        
        $bind = array ();
        $bind [] = $month;
        // $bind [] = $year_month;
        $bind [] = $year_month;
        $bind [] = $parent_id;
        $bind [] = $pschool_id;
        
        $sql = " SELECT RPIAN.*, SMPSP.id AS student_id, SMPSP.student_name, SMPSP.program_name, SMPSP.program_id ";
        $sql .= " FROM ";
        $sql .= " ( ";
        $sql .= "     SELECT RP.* , IAN.name ";
        $sql .= "     FROM routine_payment AS RP ";
        $sql .= "     INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= "     ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= "     WHERE RP.delete_date IS NULL ";
        $sql .= "     AND RP.data_div = 1 ";
        $sql .= "     AND RP.active_flag = 1 ";
        $sql .= "     AND (RP.month = ? OR RP.month = 99) ";
        $sql .= " ) AS RPIAN ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        $sql .= "     SELECT SMP.program_id, SP.id, SP.student_name, SMP.program_name ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "         SELECT SM.student_id, SM.program_id, P.program_name ";
        $sql .= "         FROM student_program AS SM ";
        $sql .= "         INNER JOIN program AS P ";
        $sql .= "         ON SM.program_id = P.id ";
        $sql .= "         WHERE SM.delete_date IS NULL ";
        // $sql .= " AND (P.start_date IS NOT NULL AND SUBSTRING(P.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND SUBSTRING(P.start_date, 1, 7) = ? ";
        // $sql .= " AND (P.close_date IS NULL OR SUBSTRING(P.close_date, 1, 7) >= ?) ";
        $sql .= "     ) AS SMP ";
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT S.id, S.student_name ";
        $sql .= "         FROM student AS S ";
        $sql .= "         INNER JOIN parent AS P ";
        $sql .= "         ON S.parent_id = P.id ";
        $sql .= "         WHERE S.delete_date IS NULL ";
        $sql .= "         AND S.parent_id = ? ";
        $sql .= "         AND S.pschool_id = ? ";
        $sql .= "     ) AS SP ";
        $sql .= "     ON SMP.student_id = SP.id ";
        $sql .= " ) AS SMPSP ";
        $sql .= " ON RPIAN.data_id = SMPSP.program_id ";
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }

    public function getListProgramByStudent($studentId) {
        $data = DB::table('program AS p')
            ->select(DB::raw('p.program_name, p.close_date, sp.register_date, sp.delete_date'))
            ->join('student_program AS sp', 'sp.program_id', '=', 'p.id')
            ->join('entry AS e', 'e.relative_id', '=', 'p.id')
            ->where('sp.student_id', '=', $studentId)
            ->where('e.student_id', '=', $studentId)
            ->where('e.enter', '=', 1)
            ->whereNull('p.delete_date')
            ->get();
        return $this->convertToArray($data);
    }
}