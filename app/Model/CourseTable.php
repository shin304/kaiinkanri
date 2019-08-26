<?PHP

namespace App\Model;

use App\Model\CourseTeacherRelTable;
use App\Model\EntryTable;
use Illuminate\Support\Facades\DB;
use App\ConstantsModel;

class CourseTable extends DbModel {

    /**
     *
     * @var CourseTable
     */
    private static $_instance = null;
    protected $table = 'course';
    public $timestamps = false;

    /**
     *
     * @return CourseTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new CourseTable ();
        }
        return self::$_instance;
    }

    // 2017-06-07 Tung Nguyen add ORM
    /**
     * The Coaches that belong to the course (Evemt)
     */
    public function coaches()
    {
        return $this->belongsToMany('App\Model\CoachTable', 'course_coach', 'course_id', 'coach_id')->whereNull('course_coach.delete_date');
    }

    public function getListOfCourse($pschool_id, $search = "", $detail = "") {
        $sql = "SELECT res.*, CASE WHEN m1.mail_count is NULL THEN '未' ELSE '済' END AS mail_sent, CASE WHEN m1.mail_count is NULL THEN 0 ELSE m1.mail_count END AS mail_count, CASE WHEN m2.mail_viewed is NULL THEN 0 ELSE m2.mail_viewed END AS mail_viewed,  CASE WHEN e.mail_join is NULL THEN 0 ELSE e.mail_join END AS mail_join FROM ";
        $sql .= " (SELECT *,3 as type_id, 2 as type2_id ";
        $sql .= "FROM course WHERE pschool_id = ? ";
        $bind [] = $pschool_id;
        $sql .= "AND delete_date is null) AS res ";
        $sql .= "LEFT JOIN (select count(*) as mail_count ,type, relative_ID from mail_message msg WHERE delete_date is null Group By type, relative_ID) m1 on (m1.type = res.type_id AND m1.relative_ID = res.id) ";
        $sql .= "LEFT JOIN (select count(*) as mail_viewed ,type, relative_ID, last_refer_date from mail_message WHERE delete_date is null and last_refer_date is NOT NULL Group by type, relative_ID) m2 on (m2.type = res.type_id AND m2.relative_ID = res.id AND last_refer_date is NOT NULL) ";
        $sql .= "LEFT JOIN (select count(*) as mail_join, entry_type, relative_id, enter from entry WHERE delete_date is null group by entry_type, relative_id, enter) e on (e.entry_type = res.type2_id AND e.relative_id = res.id AND enter = 1) ";
        $sql .= " WHERE res.delete_date is null";
        // if ($search != "")
        if (! empty ( $search ['input_search'] )) {
            $sql .= " and res.course_title LIKE ? ";
            $bind [] = "%" . $search ['input_search'] . "%";
        }
        if (! empty ( $detail )) {
            $sql .= " AND res.id = ? ";
            $bind [] = $detail;
        }
        $sql .= " ORDER BY res.start_date DESC";
        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }
    public function getListOfFutureCourse($pschool_id, $search = "") {
        $sql = "SELECT res.*, CASE WHEN m1.mail_count is NULL THEN '未' ELSE '済' END AS mail_sent, CASE WHEN m1.mail_count is NULL THEN 0 ELSE m1.mail_count END AS mail_count, CASE WHEN m2.mail_viewed is NULL THEN 0 ELSE m2.mail_viewed END AS mail_viewed,  CASE WHEN e.mail_join is NULL THEN 0 ELSE e.mail_join END AS mail_join FROM ";
        $sql .= " (SELECT *,3 as type_id, 2 as type2_id ";
        $sql .= "FROM course WHERE pschool_id = ? ";
        $bind [] = $pschool_id;
        $sql .= "AND delete_date is null) AS res ";
        $sql .= "LEFT JOIN (select count(*) as mail_count ,type, relative_ID from mail_message msg WHERE delete_date is null Group By type, relative_ID) m1 on (m1.type = res.type_id AND m1.relative_ID = res.id) ";
        $sql .= "LEFT JOIN (select count(*) as mail_viewed ,type, relative_ID, last_refer_date from mail_message WHERE delete_date is null and last_refer_date is NOT NULL Group by type, relative_ID) m2 on (m2.type = res.type_id AND m2.relative_ID = res.id AND last_refer_date is NOT NULL) ";
        $sql .= "LEFT JOIN (select count(*) as mail_join, entry_type, relative_id, enter from entry WHERE delete_date is null group by entry_type, relative_id, enter) e on (e.entry_type = res.type2_id AND e.relative_id = res.id AND enter = 1) ";
        // $sql .= " WHERE res.delete_date is null and res.start_date > NOW() ";
        $sql .= " WHERE res.delete_date is null and res.start_date >= CURDATE() ";
        if ($search != "") {
            $sql .= " and res.course_title LIKE ? ";
            $bind [] = "%{$search}%";
        }
        $sql .= " ORDER BY res.start_date";
        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }
    public function getListOfEvents($pschool_id, $search = "") {
        $sql = "SELECT res.id, res.title, res.type, type_id, type2_id, res.reg, res. upd, CASE WHEN m1.mail1 is NULL THEN '未' ELSE '済' END AS mail0, CASE WHEN m1.mail1 is NULL THEN 0 ELSE m1.mail1 END AS mail1, CASE WHEN m2.mail2 is NULL THEN 0 ELSE m2.mail2 END AS mail2,  CASE WHEN e.mail3 is NULL THEN 0 ELSE e.mail3 END AS mail3 FROM ";
        $sql .= "(SELECT id, consultation_title as title, '面談' as type, 2 as type_id, 1 as type2_id, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
        $sql .= "FROM consultation AS a WHERE a.pschool_id = ? ";
        $bind [] = $pschool_id;
        $sql .= "AND a.delete_date is null ";
        $sql .= " UNION ALL ";
        $sql .= "SELECT id, course_title as title, 'イベント' as type, 3 as type_id, 2 as type2_id, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
        $sql .= "FROM course AS b WHERE b.pschool_id = ? ";
        $bind [] = $pschool_id;
        $sql .= "AND b.delete_date is null) AS res ";
        $sql .= "LEFT JOIN (select count(*) as mail1 ,type, relative_ID from mail_message msg WHERE delete_date is null Group By type, relative_ID) m1 on (m1.type = res.type_id AND m1.relative_ID = res.id) ";
        $sql .= "LEFT JOIN (select count(*) as mail2 ,type, relative_ID, last_refer_date from mail_message WHERE delete_date is null and last_refer_date is NOT NULL Group by type, relative_ID) m2 on (m2.type = res.type_id AND m2.relative_ID = res.id AND last_refer_date is NOT NULL) ";
        $sql .= "LEFT JOIN (select count(*) as mail3, entry_type, relative_id, enter from entry WHERE delete_date is null group by entry_type, relative_id, enter) e on (e.entry_type = res.type2_id AND e.relative_id = res.id AND enter = 1) ";
        $sql .= " WHERE res.del is null";
        if ($search != "") {
            $sql .= " and res.title LIKE ? ";
            $bind [] = "%{$search}%";
        }
        $sql .= " ORDER BY IF (res.upd is null, res.reg, res.upd) DESC";
        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }

    // public function getListOfEvents($pschool_id, $search = "")
    // {
    // $bind = array();
    // $sql = "SELECT res.id, res.title, res.type, type_id, type2_id, res.reg, res. upd, CASE WHEN m1.mail1 is NULL THEN '未' ELSE '済' END AS mail0, CASE WHEN m1.mail1 is NULL THEN 0 ELSE m1.mail1 END AS mail1, CASE WHEN m2.mail2 is NULL THEN 0 ELSE m2.mail2 END AS mail2, CASE WHEN e.mail3 is NULL THEN 0 ELSE e.mail3 END AS mail3 FROM ";
    // $sql .= "(SELECT id, consultation_title as title, '面談' as type, 2 as type_id, 1 as type2_id, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
    // $sql .= "FROM consultation AS a WHERE a.pschool_id = ? ";
    // $bind[] = $pschool_id;
    // $sql .= " UNION ALL ";
    // $sql .= "SELECT id, course_title as title, 'イベント' as type, 3 as type_id, 2 as type2_id, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
    // $sql .= "FROM course AS b WHERE b.pschool_id = ? ";
    // $bind[] = $pschool_id;
    // $sql .= ") AS res ";
    // $sql .= "LEFT JOIN (select count(*) as mail1 ,type, relative_ID from mail_message Group By type, relative_ID) m1 on (m1.type = res.type_id AND m1.relative_ID = res.id) ";
    // $sql .= "LEFT JOIN (select count(*) as mail2 ,type, relative_ID, last_refer_date from mail_message Group by type, relative_ID, last_refer_date) m2 on (m2.type = res.type_id AND m2.relative_ID = res.id AND last_refer_date is NOT NULL) ";
    // $sql .= "LEFT JOIN (select count(*) as mail3, entry_type, relative_id, enter from entry group by entry_type, relative_id, enter) e on (e.entry_type = res.type2_id AND e.relative_id = res.id AND enter = 1) ";
    // $sql .= " WHERE res.del is null";
    // if ($search != "")
    // {
    // $sql .= " and res.title LIKE ? ";
    // $bind[] = "%{$search}%" ;
    // }
    // $sql .= " ORDER BY IF (res.upd is null, res.reg, res.upd) DESC";
    // $res = $this->fetchAll($sql, $bind);
    // return $res;
    // }

    // //pschool_id は　引数として　渡した方がいい
    // public function getListOfEvents($pschool_id, $search = "")
    // {
    // $sql = "SELECT res.id, res.title, res.type, res.reg, res. upd FROM ";
    // $sql .= "(SELECT id, consultation_title as title, '面談' as type, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
    // $sql .= "FROM consultation AS a WHERE a.pschool_id = ";
    // $sql .= $pschool_id;
    // $sql .= " UNION ALL ";
    // $sql .= "SELECT id, course_title as title, 'イベント' as type, `register_date` as reg, `update_date` as upd, `delete_date` as del ";
    // $sql .= "FROM course AS b WHERE b.pschool_id = ";
    // $sql .= $pschool_id;
    // $sql .= ") AS res ";
    // $sql .= " WHERE res.del is null";
    // if ($search != "")
    // {
    // $sql .= " and res.title LIKE '%";
    // $sql .= $search;
    // $sql .= "%' ";
    // }
    // $sql .= " ORDER BY IF (res.upd is null, res.reg, res.upd) DESC";
    // $bind = array();
    // $res = $this->fetchAll($sql, $bind);
    // return $res;
    // }
    public function getCourseList4Top($pschool_id, $request = NULL) {
        $sql = " SELECT res.*, CASE WHEN m1.mail_count is NULL THEN '未' ELSE '済' END AS mail_sent, CASE WHEN m1.mail_count is NULL THEN 0 ELSE m1.mail_count END AS mail_count, CASE WHEN m2.mail_viewed is NULL THEN 0 ELSE m2.mail_viewed END AS mail_viewed,  CASE WHEN e.mail_join is NULL THEN 0 ELSE e.mail_join END AS mail_join ";
        $sql .= " FROM (SELECT *, 3 as type_id, 2 as type2_id FROM course WHERE pschool_id = ? AND delete_date is null) AS res ";
        $bind = array (
                $pschool_id
        );
        // Update by Kieu 2017-06-08: New column total_send
        $sql .= " LEFT JOIN (select count(*) as mail_count ,type, relative_ID, SUM(total_send) as mail_count2 from mail_message msg WHERE delete_date is null Group By type, relative_ID) m1 on (m1.type = res.type_id AND m1.relative_ID = res.id) ";
        $sql .= " LEFT JOIN (select count(*) as mail_viewed ,type, relative_ID, last_refer_date, SUM(total_send) as mail_viewed2 from mail_message WHERE delete_date is null and last_refer_date is NOT NULL Group by type, relative_ID) m2 on (m2.type = res.type_id AND m2.relative_ID = res.id AND last_refer_date is NOT NULL) ";
        $sql .= " LEFT JOIN (select count(*) as mail_join, entry_type, relative_id, enter from entry WHERE delete_date is null group by entry_type, relative_id, enter) e on (e.entry_type = res.type2_id AND e.relative_id = res.id AND enter = 1) ";
        $sql .= " WHERE res.delete_date is null ";

        if (! empty ( $request ['course_id'] )) {
            $sql .= " AND res.id = ? ";
            $bind [] = $request ['course_id'];
        }
        if (! empty ( $request ['name'] )) {
            $sql .= " and res.course_title LIKE ? ";
            $bind [] = "%" . $request ['name'] . "%";
        }

        if (!empty ($request ['recruitment_from']) && !empty ($request ['recruitment_to'])) {
            $sql .= " AND   IF(res.recruitment_finish IS NULL, 
                            !(DATE_FORMAT(res.recruitment_start,'%Y-%m-%d') > ?),
                            !(DATE_FORMAT(res.recruitment_start,'%Y-%m-%d') > ? OR DATE_FORMAT(res.recruitment_finish,'%Y-%m-%d') < ?) ) ";
            $bind [] = $request ['recruitment_to'];
            $bind [] = $request ['recruitment_to'];
            $bind [] = $request ['recruitment_from'];
        } else if (!empty ($request ['recruitment_from'])) {
            $sql .= " AND   IF(res.recruitment_finish IS NULL,
                            1,
                            !(DATE_FORMAT(res.recruitment_finish,'%Y-%m-%d') < ?) ) ";
            $bind [] = $request ['recruitment_from'];
        } else if (!empty ($request ['recruitment_to'])) {
            $sql .= " AND !(DATE_FORMAT(res.recruitment_start,'%Y-%m-%d') > ?) ";
            $bind [] = $request ['recruitment_to'];
        }

        if (!empty ($request ['start_date_from']) && !empty ($request ['start_date_to'])) {
            $sql .= " AND   IF(res.close_date IS NULL, 
                            !(DATE_FORMAT(res.start_date,'%Y-%m-%d %H:%i') > ?),
                            !(DATE_FORMAT(res.start_date,'%Y-%m-%d %H:%i') > ? OR DATE_FORMAT(res.close_date,'%Y-%m-%d %H:%i') < ?) ) ";
            $bind [] = $request ['start_date_to'];
            $bind [] = $request ['start_date_to'];
            $bind [] = $request ['start_date_from'];
        } else if (!empty ($request ['start_date_from'])) {
            $sql .= " AND   IF(res.close_date IS NULL,
                            1,
                            !(DATE_FORMAT(res.close_date,'%Y-%m-%d %H:%i') < ?) ) ";
            $bind [] = $request ['start_date_from'];
        } else if (!empty ($request ['start_date_to'])) {
            $sql .= " AND !(DATE_FORMAT(res.start_date,'%Y-%m-%d %H:%i') > ?) ";
            $bind [] = $request ['start_date_to'];
        }

        $sql .= " ORDER BY res.start_date DESC";
        $res = $this->fetchAll ( $sql, $bind );

        foreach ( $res as $key => $value ) {
            $res [$key] ['menu_id'] = 6;

            // 複数講師
            $coaches = CourseTable::find($value['id'])->coaches;
            $coach_name = array();
            foreach( $coaches as $coach ) {
                $coach_name[] = $coach->coach_name;
            }
            $teacher_name = implode(', ' , $coach_name);
            $res [$key] ['teacher_name'] = $teacher_name;

            $res [$key] ['student_count'] = $this->getTotalJoinedStudent( session('school.login')['id'], $value ['id']);

            // 終了？
            // if (empty ( $value ['close_date'] ) || $value ['close_date'] >= date ( 'Y-m-d' )) {
            //     $res [$key] ['is_active'] = 1;
            // }

            // 仕様変更2017/05/09
            if ($value ['recruitment_finish'] >= date('Y-m-d H:i:s')) {
                $res [$key] ['is_active'] = 1;
            }

        }

        return json_decode ( json_encode ( $res ), true );
    }

    public function getTotalJoinedStudent($pschool_id, $course_id) {
        // 生徒数
        $entries = EntryTable::getInstance ()->getStudentListbyEventTypeAxis ( $pschool_id, array (
            'entry_type'    => array_search('event', ConstantsModel::$ENTRY_TYPE),
            'relative_id'   => $course_id,
            'enter'         => 1
        ) );

        $student_count = 0;
        foreach ($entries as $entry) {
            if ( $entry['total_member'] && $entry['payment_method'] ) {
                $student_count +=$entry['total_member'];
            }
        }
        return $student_count;
    }

    /**
     * イベントの割引・割増取得
     *
     * @param unknown $pschool_id
     * @param unknown $parent_id
     * @param unknown $data_div
     */
    public function getCourseAdjustList($pschool_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );

        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $month;
        $bind [] = $year_month;
        // $bind[] = $year_month;
        $bind [] = $parent_id;
        $bind [] = $pschool_id;

        $sql = " SELECT * ";
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
        $sql .= "     SELECT SCC.course_id ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, SC.course_id ";
        $sql .= "         FROM student_course_rel AS SC ";
        $sql .= "         INNER JOIN course AS C ";
        $sql .= "         ON SC.course_id = C.id ";
        $sql .= "         WHERE SC.delete_date IS NULL ";
        // $sql .= " AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND SUBSTRING(C.start_date, 1, 7) <= ? ";
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
        $sql .= " ON RPIAN.data_id = SCCSP.course_id ";

        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }

    // =========================================================================
    // ここから、アクシス柔術向け機能追加版
    // =========================================================================
    public function getCourseListHierarchy($pschool_id, $parents) {
        $ids = "";
        foreach ( $parents as $items ) {
            if (! empty ( $ids )) {
                $ids .= ", ";
            }
            $ids .= $items ['parent_id'];
        }

        $sql = " SELECT * ";
        $sql .= " FROM course ";
        $sql .= " WHERE pschool_id IN( " . $ids . " ) ";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " AND active_flag = 1 ";
        $sql .= " ORDER BY sort_no ";

        $ret = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $ret ), true );
    }
    public function getCourses($pschool_ids) {
        $ids = "";
        foreach ( $pschool_ids as $items ) {
            if (! empty ( $ids )) {
                $ids .= ", ";
            }
            $ids .= $items;
        }

        $bind = array ();
        $sql = " SELECT DISTINCT * from course";
        $sql .= " WHERE pschool_id IN (" . $ids . ")";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " AND active_flag = 1 ";

        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }
    public function getCourseByCoachId($pschool_id, $coach_id) {
        $bind = array ();
        $sql = " SELECT * FROM course c ";
        $sql .= " INNER JOIN course_coach r ON c.id = r.course_id ";
        $sql .= " WHERE r.pschool_id = ? AND r.coach_id = ? AND r.delete_date is NULL ";
        $bind [] = $pschool_id;
        $bind [] = $coach_id;

        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }

    /**
     * イベントの割引・割増取得 会員版
     *
     * @param unknown $pschool_id
     * @param unknown $parent_id
     * @param unknown $data_div
     */
    public function getCourseAdjustList_Axis($pschool_id, $parent_id, $year_month) {
        $month = substr ( $year_month, 5, 2 );

        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $month;
        // $bind[] = $year_month;
        $bind [] = $year_month;
        $bind [] = $parent_id;
        $bind [] = $pschool_id;

        $sql = " SELECT * ";
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
        $sql .= "     SELECT SCC.course_id, SCC.course_title, SP.student_name ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, SC.course_id, C.course_title ";
        $sql .= "         FROM student_course_rel AS SC ";
        $sql .= "         INNER JOIN course AS C ";
        $sql .= "         ON SC.course_id = C.id ";
        $sql .= "         WHERE SC.delete_date IS NULL ";
        // $sql .= " AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND SUBSTRING(C.start_date, 1, 7) = ? ";
        // $sql .= " AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        $sql .= "     ) AS SCC ";
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
        $sql .= "     ON SCC.student_id = SP.id ";
        $sql .= " ) AS SCCSP ";
        $sql .= " ON RPIAN.data_id = SCCSP.course_id ";

        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }

    public function getListCourseByStudent($studentId) {
        $data = DB::table('course AS c')
            ->select(DB::raw('c.course_title, c.close_date,scr.register_date, scr.delete_date'))
            ->join('entry AS e', 'e.relative_id', '=', 'c.id')
            ->join('student_course_rel AS scr', 'scr.course_id', '=', 'c.id')
            ->where('scr.student_id', '=', $studentId)
            ->where('e.student_id', '=', $studentId)
            ->where('e.enter', '=', 1)
            ->whereNull('c.delete_date')
            ->get();
        return $this->convertToArray($data);
    }

    public function getExportList($pschool_id, $course_id) {
        $export_list = DB::table('course as c')->join('entry as e', 'e.relative_id', '=', 'c.id') // get total_member, code
        ->join('student as s', 's.id', '=', 'e.student_id') //get student_name, student_name_kana, student_no
        ->join('student_course_rel as scr', function ($join) { //get is_received, payment_date, plan_id
            $join->on('scr.course_id', '=', 'c.id')->on('scr.student_id', '=', 's.id');
        })
        ->leftJoin('course_fee_plan as cfp', 'cfp.id', '=', 'scr.plan_id') // get student_type_id
        ->leftJoin('m_student_type as mst', function ($join) use ($pschool_id) { //get name
            $join->on('mst.id', 'cfp.student_type_id')->on('mst.pschool_id', DB::raw($pschool_id));
        })
        ->where('s.pschool_id', $pschool_id)->where('c.id', $course_id)
        ->where('e.enter', 1)
        ->whereNull('c.delete_date')
        ->select('c.id', 'c.course_title', 'e.code', 'mst.name', 's.student_name', 's.student_name_kana', 's.student_no', 'e.total_member', DB::raw('(CASE scr.is_received WHEN 0 THEN "未入金" ELSE "入金済み" END) AS is_received'),'scr.payment_date')->orderBy('s.student_name_kana')->get();
        return $export_list;
    }
}