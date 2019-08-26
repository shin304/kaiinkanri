<?PHP
namespace App\Model;
use App\ConstantsModel;

class MailMessageTable extends DbModel {

	/**
	 * @var MailMessageTable
	 */
	private static $_instance = null;
	protected $table = 'mail_message';

	/**
	 * @return MailMessageTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new MailMessageTable();
		}
		return self::$_instance;
	}

	/**
	 * キーに該当するデータを取得する
	 * @param unknown $type
	 * @param unknown $mkey
	 * @return Ambigous <multitype:, mixed>
	 */
	public function getRowForShowPortal($mkey, $type=null) {
		if (is_null($type)) {
			$sql = "select * from {$this->getTableName(true)} where message_key=? and delete_date is null";
			return $this->fetch($sql, array($mkey));

		}
		//3か月という日付はここで定義する。
		$sql = "select * from {$this->getTableName(true)} where type=? and message_key=? and delete_date is null";
		return $this->fetch($sql, array($type, $mkey));
	}

	public function updateLastReferDate($id) {
		$sql = "update {$this->getTableName(true)} set last_refer_date=now(),update_date=now(),update_admin=? where id=?";
		$bind = array(0, $id);
		return $this->execute($sql, $bind);
	}

	/**
	 * Get mailing list for an event
	 * @param int $pschool_id
	 * @param int $event_type #1 consultation 2 course
	 * @param int $event_id
	 * @param int $school_year
	 * @param String $student_name
	 * @param String $school_category
	 * @return mail_list
	 */
	public function getEventMailList($pschool_id, $event_type, $event_id, $student_name = "", $school_category=null, $school_year=null, $exam_pref=null, $exam_city=null, $class_id=null, $student_types=array(), $student_no=null)
	{
		$bind = array();

		//s student table, i event table, e entry, s_list participating student list
		$sql = " SELECT s.id AS student_id, s.student_no, s.student_name, s.student_name_kana, s.school_name, s.school_category, s.school_year, s_list.enter, s_list.eid, s_list.status, s.parent_name, s.parent_mailaddress1, s.parent_mailaddress2 FROM ";
		//$sql .= " (SELECT a.* ";
		$sql .= " (SELECT a.pschool_id,a.active_flag,a.id,a.delete_date,a.student_type,a.student_no, a.student_name, a.student_name_kana, a.school_name, a.school_category, a.school_year, p.parent_name, p.parent_mailaddress1, p.parent_mailaddress2 ";
		$sql .= " FROM student a INNER JOIN ";
		$sql .= " parent p ON p.id = a.parent_id) as s ";
		$sql .= " left JOIN ";
		//Subquery for participating student list started
		$sql .= " (SELECT e.id As eid, e.entry_type, e.student_id AS s_id, e.status AS status, e.enter AS enter, e.last_refer_date AS last_refer_date FROM ";
		if ($event_type == 1)
		{
			$sql .= " consultation i ";
		}
		else
		{
			$sql .= " course i ";
		}
		$sql .= " INNER JOIN entry e ";
		$sql .= " ON e.relative_id = i.id ";
		$sql .= " WHERE e.entry_type = ? and e.delete_date is null and i.delete_date is null and i.pschool_id = ? and i.id = ?) AS s_list "; //active flag of event tables may be added here.
		//Subquery ended
		$sql .= " ON s.id = s_list.s_id ";
		$sql .= " WHERE s.pschool_id = ? and s.active_flag = 1 and s.delete_date is null and (s.parent_mailaddress1 is not null or s.parent_mailaddress2 is not null) ";

		$bind[] = $event_type;
		$bind[] = $pschool_id;
		$bind[] = $event_id;
		$bind[] = $pschool_id;
		if (!empty($student_name))
		{
//			$sql .= " AND s.student_name LIKE ? ";
			$sql .= " AND (s.student_name LIKE ? OR s.student_name_kana collate utf8_unicode_ci LIKE ?)";
//			$bind[] = "%".$student_name."%";
//			$bind[] = "%".$student_name."%";
			$bind[] = "%{$student_name}%";
			$bind[] = "%{$student_name}%";
		}
		if ( isset($school_category) && is_numeric($school_category) )
		{
			$sql .= " AND s.school_category = ? ";
			$bind[] = $school_category;
		}
		if ( isset($school_year) && is_numeric($school_year))
		{
			$sql .= " AND s.school_year = ? ";
			$bind[] = $school_year;
		}

		if (!empty($exam_pref)) {
			$sql .= " AND s.id IN (select student_id from student_exam_area where pref_id = ?)";
			$bind[] = $exam_pref;
		}
		if (!empty($exam_city)) {
			$sql .= " AND s.id IN (select student_id from student_exam_area where city_id = ?)";
			$bind[] = $exam_city;
		}
		if (!empty($class_id)) {
			$sql .= " AND s.id IN (select student_id from student_class where class_id = ?)";
			$bind[] = $class_id;
		}
		if (!empty($student_types)) {
			$sql .= " AND s.student_type IN (".implode(',', array_fill(1, count($student_types), '?')).")";
			foreach ($student_types as $realval) {
				$bind[] = $realval;
			}
		}
		if(!empty($student_no)){
			$sql .= "AND s.student_no = ?";
			$bind[] = $student_no;
		}

		$sql .= " ORDER BY s.student_name_kana ";
		$res = $this->fetchAll($sql, $bind);
		return $res;
	}

	public function getMailConfirmation($condition)
	{
		$is_confirmed = 0;
		$bind = array();
		$sql = " SELECT COUNT(*) as count ";
		$sql .= " FROM mail_message ";
		$sql .= " WHERE ";
		$sql .= " type = ?  and relative_ID = ?  and student_id = ? and last_refer_date is not null and delete_date is null";
		$bind[] = $condition['type'];
		$bind[] = $condition['relative_ID'];
		$bind[] = $condition['student_id'];
		$res = $this->fetchAll($sql, $bind);
		if ($res[0]['count'])
		{
			$is_confirmed = 1;
		}
//second one
// 		$pschool_id = $_SESSION['school.login']['id'];
// 		$bind = array();
// 		$sql = "SELECT count(*) as count ";
// 		$sql .= "FROM entry ";
// 		$sql .= "WHERE ";
// 		$sql .= "entry_type = ? and event_id = ? and student_id = ? and status = 2 and delete_date is null ";
// 		$bind[] = $condition['type'];
// 		$bind[] = $condition['relative_ID'];
// 		$bind[] = $condition['student_id'];
// 		$res = $this -> fetchAll($sql, $bind);
// 		if ($res[0]['count'])
// 		{
// 			$is_confirmed = 1;
// 		}

		return $is_confirmed;
	}

	// 検索画面用
	public function getListBySearch($school_id, $request = array()) {
		$where = array();
		$bind = array();

		// 塾ID
		$where[] = "mail.pschool_id = ?";
		$bind[] = $school_id;

		// 通知タイプ
		if (isset($request["type"]) && strlen($request["type"])) {
			$where[] = "mail.type = ?";
			$bind[] = $request["type"];
		}

		// 確認状態
		if (isset($request["confirm_status"]) && strlen($request["confirm_status"])) {
			if ($request["confirm_status"] == "0") {
				$where[] = "mail.last_refer_date IS NULL";
			} else {
				$where[] = "mail.last_refer_date IS NOT NULL";
			}
		}

		// 保護者名
		if (isset($request["parent_name"]) && strlen($request["parent_name"])) {
			$where[] = "parent.parent_name LIKE ?";
			$bind[] = "%" . $request["parent_name"] . "%";
		}

		// 学年(カテゴリ)
		if ( isset($request["school_category"]) && is_numeric($request["school_category"]) ) {
			$where[] = "CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice_student.school_category = ?" .
						"ELSE " .
							"student.school_category = ? " .
						"END";
			$bind[] = $request["school_category"];
			$bind[] = $request["school_category"];
		}

		// 学年(年)
		if ( isset($request["school_year"]) && is_numeric($request["school_year"]) ) {
			$where[] = "CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice_student.school_year = ?" .
						"ELSE " .
							"student.school_year = ? " .
						"END";
			$bind[] = $request["school_year"];
			$bind[] = $request["school_year"];
		}

		// 生徒氏名
		if (isset($request["student_name"]) && strlen($request["student_name"])) {
			$where[] = "CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice_student.student_name LIKE ?" .
						"ELSE " .
							"student.student_name LIKE ? " .
						"END";
			$bind[] = "%" . $request["student_name"] . "%";
			$bind[] = "%" . $request["student_name"] . "%";
		}

		// 送付年
		if (isset($request["requested_year"]) && strlen($request["requested_year"])) {
			$where[] = "CASE " .
						"WHEN mail.type = 1 THEN " .
							"SUBSTRING(invoice.invoice_year_month, 1, 4) = ?" .
						"ELSE " .
							"DATE_FORMAT(mail.register_date, '%Y') = ? " .
						"END";
			$bind[] = $request["requested_year"];
			$bind[] = $request["requested_year"];
		}

		// 送付月
		if (isset($request["requested_month"]) && strlen($request["requested_month"])) {
			$where[] = "CASE " .
						"WHEN mail.type = 1 THEN " .
							"SUBSTRING(invoice.invoice_year_month, 5, 2) = ?" .
						"ELSE " .
							"DATE_FORMAT(mail.register_date, '%m') = ? " .
						"END";
			$bind[] = $request["requested_month"];
			$bind[] = $request["requested_month"];
		}

		// 送付年月
		if (isset($request["requested_year_month"]) && strlen($request["requested_year_month"])) {
			$where[] = "CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.invoice_year_month = ?" .
						"ELSE " .
							"DATE_FORMAT(mail.register_date, '%Y%m') = ? " .
						"END";
			$bind[] = $request["requested_year_month"];
			$bind[] = $request["requested_year_month"];
		}

		// 関連ID
		if (isset($request["relative_id"]) && strlen($request["relative_id"])) {
			$where[] = "mail.relative_ID = ?";
			$bind[] = $request["relative_id"];
		}

		$where_str = "";
		if (!empty($where)) {
			$where_str = " AND " . implode(" AND ", $where) . " ";
		}

		$sql = "SELECT " .
					"mail.id" .
					", mail.type" .
					", mail.relative_ID" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"CONCAT(" .
								"'請求書'" .
								", CAST(SUBSTRING(invoice.invoice_year_month, 1, 4) AS CHAR)" .
								", '年'" .
								", CAST(SUBSTRING(invoice.invoice_year_month, 5, 2) AS CHAR)" .
								", '月'" .
							") " .
						"WHEN mail.type = 2 OR mail.type = 4 THEN " .
							"consultation.consultation_title " .
						"WHEN mail.type = 3 THEN " .
							"course.course_title " .
						"END AS mail_title" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.invoice_year_month " .
						"ELSE " .
							"DATE_FORMAT(MIN(mail.register_date), '%Y%m') " .
						"END min_requested_year_month" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.invoice_year_month " .
						"ELSE " .
							"DATE_FORMAT(MAX(mail.register_date), '%Y%m') " .
						"END max_requested_year_month" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"NULL " .
						"ELSE " .
							"mail.relative_ID END AS group_key1" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.invoice_year_month " .
						"ELSE " .
							"NULL " .
						"END AS group_key2 " .
				"FROM " .
					"mail_message AS mail " .
					"LEFT JOIN invoice_header AS invoice " .
						"ON (" .
							"mail.type = 1 " .
							"AND mail.relative_ID = invoice.id " .
							"AND mail.pschool_id = invoice.pschool_id " .
							"AND invoice.active_flag = 1 " .
							"AND invoice.delete_date IS NULL " .
						") " .
					"LEFT JOIN invoice_item AS invoice_i " .
						"ON (" .
							"invoice.id = invoice_i.invoice_id " .
						") " .
					"LEFT JOIN student AS invoice_student " .
						"ON (" .
							"invoice_i.student_id = invoice_student.id " .
							"AND mail.pschool_id = invoice_student.pschool_id " .
							"AND invoice_student.active_flag = 1 " .
							"AND invoice_student.delete_date IS NULL " .
						") " .
					"LEFT JOIN consultation " .
						"ON (" .
							"(mail.type = 2 OR mail.type = 4) " .
							"AND mail.relative_ID = consultation.id " .
							"AND mail.pschool_id = consultation.pschool_id " .
							"AND consultation.active_flag = 1 " .
							"AND consultation.delete_date IS NULL " .
						") " .
					"LEFT JOIN course " .
						"ON (" .
							"mail.type = 3 " .
							"AND mail.relative_ID = course.id " .
							"AND mail.pschool_id = course.pschool_id " .
							"AND course.active_flag = 1 " .
							"AND course.delete_date IS NULL " .
						") " .
					"INNER JOIN parent " .
						"ON (" .
							"mail.parent_id = parent.id " .
							"AND mail.pschool_id = parent.pschool_id" .
						") " .
					"LEFT JOIN student " .
						"ON (" .
							"mail.student_id = student.id " .
							"AND mail.pschool_id = student.pschool_id " .
							"AND student.active_flag = 1 " .
							"AND student.delete_date IS NULL " .
						") " .
				"WHERE " .
					"(" .
						"CASE " .
							"WHEN mail.type = 1 THEN " .
								"invoice_student.id IS NOT NULL " .
							"ELSE " .
								"student.id IS NOT NULL " .
							"END " .
					") " .
					"AND CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.id IS NOT NULL " .
						"WHEN mail.type = 2 OR mail.type = 4 THEN " .
							"consultation.id IS NOT NULL " .
						"WHEN mail.type = 3 THEN " .
							"course.id IS NOT NULL " .
						"END " .
					$where_str .
				"GROUP BY " .
					"mail.type" .
					", group_key1" .
					", group_key2 " .
				"ORDER BY " .
					"min_requested_year_month DESC" .
					", mail.type ASC" .
					", mail_title ASC";

		$res = $this->fetchAll($sql, $bind);

		return $res;
	}

	// 詳細画面用
	public function getSendListByDetail($school_id, $request = array()) {
		$where = array();
		$bind = array();

		// 塾ID
		$where[] = "mail.pschool_id = ?";
		$bind[] = $school_id;

		// 通知タイプ
		$where[] = "mail.type = ?";
		$bind[] = $request["type"];

		// 送付年月
		if (isset($request["requested_year_month"]) && strlen($request["requested_year_month"])) {
			$where[] = "CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.invoice_year_month = ?" .
						"ELSE " .
							"DATE_FORMAT(mail.register_date, '%Y%m') = ? " .
						"END";
			$bind[] = $request["requested_year_month"];
			$bind[] = $request["requested_year_month"];
		}

		// 関連ID
		if (isset($request["relative_id"]) && strlen($request["relative_id"])) {
			$where[] = "mail.relative_ID = ?";
			$bind[] = $request["relative_id"];
		}

		$where_str = "";
		if (!empty($where)) {
			$where_str = " AND " . implode(" AND ", $where) . " ";
		}

		$sql = "SELECT " .
					"mail.id" .
					", mail.type" .
					", mail.relative_ID" .
					", parent.id AS parent_id" .
					", parent.parent_name" .
					", student.id AS student_id" .
					", student.student_no AS student_no" .
					", student.student_name" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.invoice_year_month " .
						"ELSE " .
							"DATE_FORMAT(MIN(mail.register_date), '%Y%m') " .
						"END min_requested_year_month" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.invoice_year_month " .
						"ELSE " .
							"DATE_FORMAT(MAX(mail.register_date), '%Y%m') " .
						"END max_requested_year_month" .
					", CASE " .
						"WHEN MAX(mail.last_refer_date) IS NULL THEN " .
							"0 " .
						"ELSE " .
							"1 " .
						"END AS is_confirmed" .
					", CASE " .
						"WHEN mail.type = 1 THEN " .
							"mail.relative_ID " .
						"ELSE " .
							"mail.student_id " .
						"END AS group_key " .
				"FROM " .
					"mail_message AS mail " .
					"LEFT JOIN invoice_header AS invoice " .
						"ON (" .
							"mail.type = 1 " .
							"AND mail.relative_ID = invoice.id " .
							"AND mail.pschool_id = invoice.pschool_id " .
							"AND invoice.active_flag = 1 " .
							"AND invoice.delete_date IS NULL " .
						") " .
					"LEFT JOIN consultation " .
						"ON (" .
							"(mail.type = 2 OR mail.type = 4) " .
							"AND mail.relative_ID = consultation.id " .
							"AND mail.pschool_id = consultation.pschool_id " .
							"AND consultation.active_flag = 1 " .
							"AND consultation.delete_date IS NULL " .
						") " .
					"LEFT JOIN course " .
						"ON (" .
							"mail.type = 3 " .
							"AND mail.relative_ID = course.id " .
							"AND mail.pschool_id = course.pschool_id " .
							"AND course.active_flag = 1 " .
							"AND course.delete_date IS NULL " .
						") " .
					"INNER JOIN parent " .
						"ON (" .
							"mail.parent_id = parent.id " .
							"AND mail.pschool_id = parent.pschool_id" .
						") " .
					"LEFT JOIN student " .
						"ON (" .
							"mail.student_id = student.id " .
							"AND mail.pschool_id = student.pschool_id " .
							"AND student.active_flag = 1 " .
							"AND student.delete_date IS NULL " .
						") " .
				"WHERE " .
					"(" .
						"CASE " .
							"WHEN mail.type = 1 THEN " .
								"TRUE " .
							"ELSE " .
								"student.id IS NOT NULL " .
							"END " .
					") " .
					"AND CASE " .
						"WHEN mail.type = 1 THEN " .
							"invoice.id IS NOT NULL " .
						"WHEN mail.type = 2 OR mail.type = 4 THEN " .
							"consultation.id IS NOT NULL " .
						"WHEN mail.type = 3 THEN " .
							"course.id IS NOT NULL " .
						"END " .
					$where_str .
				"GROUP BY " .
					"mail.parent_id" .
					", group_key " .
				"ORDER BY " .
					"mail.relative_ID ASC" .
					", mail.parent_id ASC";

		$res = $this->fetchAll($sql, $bind);

		// 請求書の場合は生徒のリストを付加する。
		if ($request["type"] == "1") {
			$sql = "SELECT " .
						"b.* " .
					"FROM " .
						"invoice_item AS a " .
						"INNER JOIN student AS b " .
							"ON (a.student_id = b.id) " .
					"WHERE " .
						"a.invoice_id = ? " .
						"AND a.pschool_id = ? " .
						"AND b.active_flag = 1 " .
						"AND b.delete_date IS NULL " .
					"GROUP BY " .
						"b.id " .
					"ORDER BY " .
						"b.school_category ASC" .
						", b.school_year ASC" .
						", b.student_name ASC";

			foreach ($res as $k => $v) {
				$bind = array(
					$v["relative_id"],
					$school_id,
				);
				$res[$k]["student_list"] = $this->fetchAll($sql, $bind);
			}
		}

		return $res;
	}

	// =========================================================================
	// ここから、アクシス柔術向け機能追加版
	// =========================================================================
	public function getEventMailListAxis($pschool_id, $event_type, $event_id, $student_name_kana = "", $school_category=null, $school_year=null, $exam_pref=null, $exam_city=null, $class_id=null, $student_types=array(), $student_no=null, $arry_search=array())
	{
		$bind = array();

		//s student table, i event table, e entry, s_list participating student list
		$sql = " SELECT s.id AS student_id, s.student_no, s.student_name, s.student_name_kana, s.school_name, s.school_category, s.school_year, s.resign_date, s_list.enter, s_list.eid, s_list.status, s.parent_name, s.parent_mailaddress1, s.parent_mailaddress2, s.student_category, s.total_member, s.m_student_type_id, cfp.fee_plan_name, mst.name as student_type_name, cfp.fee, scr.plan_id,scr.is_received, s_list.joined_total, s_list.payment_method AS payment_selected, cfp2.id as default_plan_id, s_list.payment_method , s_list.invoice_id FROM ";
		$sql .= " (SELECT a.pschool_id,a.active_flag,a.id,a.delete_date,a.student_type,a.student_no, a.student_name, a.student_name_kana, a.school_name, a.school_category, a.school_year, a.resign_date, a.m_student_type_id, p.parent_name, p.parent_mailaddress1, p.parent_mailaddress2, a.student_category, a.total_member ";
		$sql .= " FROM student a INNER JOIN ";
		$sql .= " parent p ON p.id = a.parent_id) as s ";
		$sql .= " left JOIN ";
		//Subquery for participating student list started
		$sql .= " (SELECT e.id As eid, e.entry_type, e.student_id AS s_id, e.status AS status, e.enter AS enter, e.last_refer_date AS last_refer_date, e.total_member as joined_total, e.payment_method, e.invoice_id FROM ";
		if ($event_type == 1) {
			$sql .= " consultation i ";
		} elseif ($event_type == 2) {
			$sql .= " course i ";
		} elseif ($event_type == 3) {
			$sql .= " program i ";
		}
		
		$sql .= " INNER JOIN entry e ";
		$sql .= " ON e.relative_id = i.id ";
		$sql .= " WHERE e.entry_type = ? and e.delete_date is null and i.delete_date is null and i.pschool_id = ? and i.id = ?) AS s_list "; //active flag of event tables may be added here.
		//Subquery ended
		$sql .= " ON s.id = s_list.s_id ";

		if ($event_type == 1) {
			
		} elseif ($event_type == 2) {
			$sql .= " LEFT JOIN student_course_rel scr ON s.id = scr.student_id";
			$sql .= " AND scr.course_id = ?";
			$sql .= " AND scr.delete_date IS NULL";
			$sql .= " LEFT JOIN course_fee_plan cfp ON scr.plan_id = cfp.id ";
			$sql .= " LEFT JOIN course_fee_plan cfp2 ON s.m_student_type_id = cfp2.student_type_id AND cfp2.course_id = ? ";
		} elseif ($event_type == 3) {
			$sql .= " LEFT JOIN student_program scr ON s.id = scr.student_id";
			$sql .= " AND scr.program_id = ?";
			$sql .= " AND scr.delete_date IS NULL";
			$sql .= " LEFT JOIN program_fee_plan cfp ON scr.plan_id = cfp.id ";
			$sql .= " LEFT JOIN program_fee_plan cfp2 ON s.m_student_type_id = cfp2.student_type_id AND cfp2.program_id = ? ";
		}
		
		$sql .= " LEFT JOIN m_student_type mst ON (cfp.student_type_id = mst.id AND mst.pschool_id = ?) ";
		$sql .= " WHERE s.pschool_id = ? and s.delete_date is null and (s.parent_mailaddress1 is not null or s.parent_mailaddress2 is not null) ";

		$bind[] = $event_type;
		$bind[] = $pschool_id;
		$bind[] = $event_id;
		$bind[] = $event_id;
		$bind[] = $event_id;
		$bind[] = $pschool_id;
		$bind[] = $pschool_id;
		if (!empty($student_name_kana))
		{
			$sql .= " AND (s.student_name_kana collate utf8_unicode_ci LIKE ? OR s.student_name LIKE ? )";
			$bind[] = "%{$student_name_kana}%";
			$bind[] = "%{$student_name_kana}%";
		}
		if ( isset($school_category) && is_numeric($school_category) )
		{
			$sql .= " AND s.school_category = ? ";
			$bind[] = $school_category;
		}
		if ( isset($school_year) && is_numeric($school_year))
		{
			$sql .= " AND s.school_year = ? ";
			$bind[] = $school_year;
		}

		if (!empty($exam_pref)) {
			$sql .= " AND s.id IN (select student_id from student_exam_area where pref_id = ?)";
			$bind[] = $exam_pref;
		}
		if (!empty($exam_city)) {
			$sql .= " AND s.id IN (select student_id from student_exam_area where city_id = ?)";
			$bind[] = $exam_city;
		}
		if (!empty($class_id)) {
			$sql .= " AND s.id IN (select student_id from student_class where class_id = ?)";
			$bind[] = $class_id;
		}
		if (!empty($student_types)) {
			$sql .= " AND s.m_student_type_id IN (".implode(', ', $student_types).")";
			// $sql .= " AND s.student_type IN (".implode(',', array_fill(1, count($student_types), '?')).")";
			// foreach ($student_types as $realval) {
			// 	$bind[] = $realval;
			// }
		}
		if(!empty($student_no)){
			$sql .= "AND s.student_no = ?";
			$bind[] = $student_no;
		}

		if (empty($arry_search)) {
			$sql .= " AND s.active_flag = 1 ";
		}else{
			// 帯色
			if(isset($arry_search['select_grade'])){
				if(!empty($arry_search['select_grade'])){
					$sql .= " AND s.id in (select student_id from student_grade_rel where grade_id = ? AND active_flag = 1 AND delete_date is NULL )";
					$bind[] = $arry_search['select_grade'];
				}
			}
			// ステータス(1:受講中, 2:休校中, 9:退会)
			if(isset($arry_search['select_state'])){
				if(!empty($arry_search['select_state'])){
					if ($arry_search['select_state'] == 1){
						$sql .= " AND s.active_flag != 0 AND s.resign_date IS NULL ";
					}
					elseif ($arry_search['select_state'] == 2){
						$sql .= " AND s.active_flag != 1 AND s.resign_date IS NULL ";
					}
					elseif ($arry_search['select_state'] == 9){
						$sql .= " AND s.resign_date IS NOT NULL ";
					}
				}
			}else{
				$sql .= " AND s.active_flag != 0 ";
			}
		}

		// 「受講料種別」の検索項目を追加する
		if (isset($arry_search['fee_type_id'])) {
		    if (isset($arry_search['fee_type_id'][1]) && $arry_search['fee_type_id'][1] == "1") { // first loop
                $sql .= " AND (scr.plan_id = ? OR (scr.plan_id IS NULL AND cfp2.id = ?) OR (scr.plan_id IS NULL AND cfp2.id IS NULL)) ";
            } else {
                $sql .= " AND (scr.plan_id = ? OR (scr.plan_id IS NULL AND cfp2.id = ?)) ";
            }
            $bind[] = $arry_search['fee_type_id'][0];
            $bind[] = $arry_search['fee_type_id'][0];

        }

		$sql .= " GROUP BY s.id ORDER BY s.student_name_kana ";
		$res = $this->fetchAll($sql, $bind);
		return $res;
	}

	public function getEventGroupMailList($pschool_id, $period = null)
	{
		$bind = array();
		$sql = "SELECT DATE(CASE WHEN m.update_date IS NULL THEN m.register_date ELSE m.update_date END) AS date, c.course_title, c.id as event_id, c.recruitment_finish, m.type as msg_type_id, 'event' as mail_type, '2' as event_type_id, c.send_mail_flag
                FROM mail_message m
                INNER JOIN course c ON c.id = m.relative_ID
                WHERE m.delete_date is NULL AND c.delete_date is NULL
                AND m.type = 3 AND m.pschool_id = ? ";
		$bind[] = $pschool_id;
		if (!is_null($period)) {
            $sql .= " AND If (m.update_date is NULL, m.register_date, m.update_date) >= DATE_ADD(NOW(),INTERVAL -? DAY) ";
            $bind [] = $period;
        }
        $sql .=" GROUP BY m.relative_ID
				 ORDER BY date DESC";
		return $this->fetchAll($sql, $bind);
	}

	public function getListMailOnSchedule($pschool_id = null)
	{
		$bind = array();
		$sql = "
SELECT m.*
FROM mail_message m
WHERE m.delete_date IS NULL
AND ( (m.schedule_date IS NULL || m.schedule_date <= NOW()) AND (m.send_date IS NULL || m.schedule_date > m.send_date) ) ";
		if ( is_numeric($pschool_id) ) {
			$sql .= " AND m.pschool_id = ? ";
			$bind [] = $pschool_id;
		}
		$res = $this->fetchAll($sql, $bind);
		return $res;
	}

	public function getMailInfoToSend($id)
	{
		$bind = array();
		$sql = "
                SELECT m.*, ps.name as school_name, ps.daihyou as school_daihyou, ps.mailaddress as school_mailaddress, 
                  ps.zip_code as school_zipcode , ps.zip_code1 as school_zipcode_1 , ps.zip_code2 as school_zipcode_2,
                  m_city.name as school_city, m_pref.name as school_pref, ps.address, ps.building , ps.tel as school_phone,
                  s.student_no, s.student_name, s.mailaddress as student_mailaddress, p.parent_name, 
                  p.parent_mailaddress1, p.parent_mailaddress2
                FROM mail_message m
                INNER JOIN pschool ps ON m.pschool_id = ps.id
                /*INNER JOIN student s ON m.student_id = s.id*/  /*Kieudtd update 2017-08-21 : Invoice Header student not require*/ 
                LEFT JOIN student s ON m.student_id = s.id
                LEFT JOIN m_city ON m_city.id =  ps.city_id
                LEFT JOIN m_pref ON m_pref.id = ps.pref_id
                INNER JOIN parent p ON m.parent_id = p.id
                WHERE m.delete_date IS NULL AND ps.delete_date IS NULL AND s.delete_date IS NULL AND p.delete_date IS NULL AND m.id = ? ";
		$bind [] = $id;
		return $this->fetch($sql, $bind);
	}
	public function updateMailMessage($mail_message_data){
        $bind = array();
        $sql = " SELECT id 
                  FROM mail_message m
                  WHERE pschool_id = ?
                  AND type= ?
                  AND relative_ID= ?
                  AND target = ?
                  AND target_id = ?";
        $bind[]=$mail_message_data['pschool_id'];
        $bind[]=$mail_message_data['type'];
        $bind[]=$mail_message_data['relative_ID'];
        $bind[]=$mail_message_data['target'];
        $bind[]=$mail_message_data['target_id'];
        $mail_message = $this->fetch($sql,$bind);
        if($mail_message && isset($mail_message['id'])){
            $mail_message_data['id'] = $mail_message['id'];
        }
        return $this->save( $mail_message_data);
    }
    public function getMailMessageDetail($message_key)
    {
        $sql = "
SELECT m.*, ps.name as school_name, ps.daihyou as school_daihyou, ps.mailaddress as school_mailaddress, s.student_no, s.student_name, s.mailaddress as student_mailaddress, p.parent_name, p.parent_mailaddress1, p.parent_mailaddress2
FROM mail_message m
INNER JOIN pschool ps ON m.pschool_id = ps.id
LEFT JOIN student s ON m.student_id = s.id
INNER JOIN parent p ON m.parent_id = p.id
WHERE m.delete_date IS NULL AND ps.delete_date IS NULL AND s.delete_date IS NULL AND p.delete_date IS NULL AND m.message_key = ? ";
        $bind [] = $message_key;
        $mail_message = $this->fetch($sql, $bind);
        if ($mail_message) {
            $mail_message['mail_message_type'] = ConstantsModel::$MAIL_MESSAGE_TYPE[$mail_message['type']];
        }
        return $mail_message;
    }

    public function getReminderMailContent($mail_message_id){
        $sql = "SELECT ih.* , pschool.mailaddress, parent.parent_mailaddress1, mm.total_send, parent.parent_name
                FROM mail_message mm
                LEFT JOIN invoice_header ih ON mm.relative_ID = ih.id
                LEFT JOIN pschool ON mm.pschool_id = pschool.id
                LEFT JOIN parent ON mm.target_id = parent.id
                WHERE mm.id = ".$mail_message_id."
                AND mm.type = 7
                ";

        $res = $this->fetch($sql);
        return $res;
    }
}
