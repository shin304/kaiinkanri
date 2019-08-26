<?PHP

namespace App\Model;

use App\Common\Constants;
use App\Http\Controllers\School\MailMessageController;

require_once 'InvoiceItemTable.php';
require_once 'ClosingDayTable.php';
require_once 'InvoiceRequestTable.php';
class InvoiceHeaderTable extends DbModel {
    
    /**
     *
     * @var InvoiceHeaderTable
     */
    private static $_instance = null;
    protected $table = 'invoice_header';
    public $timestamps = false;
    /**
     *
     * @return InvoiceHeaderTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new InvoiceHeaderTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getListOfCurrentByStudent($school_id, $student_id) {
        $sql = " SELECT a.* FROM {$this->getTableName(true)} a";
        $sql .= " INNER JOIN invoice_item b ON a.id = b.invoice_id";
        $sql .= " AND b.student_id = ?";
        $sql .= " AND b.active_flag = 1";
        $sql .= " AND b.delete_date is null";
        $sql .= ' WHERE a.pschool_id = ? AND a.active_flag = 1 AND a.delete_date is null';
        $sql .= ' GROUP BY a.id ';
        $sql .= ' ORDER BY a.invoice_year_month DESC';
        $sql .= ' LIMIT 3';
        $bind = array ();
        $bind [] = $student_id;
        $bind [] = $school_id;
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
    }
    
    // get parent bank_type by parent_id
    public function getBankTypeByParentId($parent_id){
        $sql= "SELECT bank_type FROM parent_bank_account where parent_id = ?";
        $bind = array ();
        $bind [] = $parent_id;
        $arr = $this->fetch($sql,$bind);
        $result = "";
        foreach($arr as $item){
            $result = $item;
        }
        return $result;
    }
    
    // get send date mail message by parent_id
    public function getSendDateByParentId($parent_id){
        $sql= "SELECT send_date FROM mail_message where parent_id = ?";
        $bind = array ();
        $bind [] = $parent_id;
        $arr = $this->fetch($sql,$bind);
        $result = "";
        if(!is_null($arr)){
            foreach($arr as $item){
                $result = $item;
            }
            return $result;
        }
        return null;
    }
    
    // 検索画面用
    public function getListBySearch($school_id, $request = array()) {
        $where = array ();
        $bind = array ();
        
        // 塾ID
        $where [] = "a.pschool_id = ?";
        $bind [] = $school_id;
        
        // 請求書ID
        if (isset ( $request ["xxx_id"] ) && strlen ( $request ["xxx_id"] )) {
            $where [] = "a.id = ?";
            $bind [] = $request ["xxx_id"];
        }
        
        // 保護者名＆フリガナ
        if (isset ( $request ["parent_name"] ) && strlen ( $request ["parent_name"] )) {
            $where [] = "(c.parent_name LIKE ? OR c.name_kana LIKE ?)";
            $bind [] = "%" . $request ["parent_name"] . "%";
            $bind [] = "%" . $request ["parent_name"] . "%";
        }
        
        // 保護者名カナ
        if (isset ( $request ["parent_name_kana"] ) && strlen ( $request ["parent_name_kana"] )) {
            $where [] = "c.name_kana LIKE ?";
            $bind [] = "%" . $request ["parent_name_kana"] . "%";
        }
        
        // 保護者メール通知希望
        if (isset ( $request ["parent_mail_infomation"] ) && strlen ( $request ["parent_mail_infomation"] )) {
            // 請求書のメール設定に従う
            // $where[] = "c.mail_infomation = ?";
            // $bind[] = $request["parent_mail_infomation"];
        }
        
        // 生徒氏名＆フリガナ
        if (isset ( $request ["student_name"] ) && strlen ( $request ["student_name"] )) {
            $where [] = "(d.student_name LIKE ? OR d.student_name_kana LIKE ?)";
            $bind [] = "%" . $request ["student_name"] . "%";
            $bind [] = "%" . $request ["student_name"] . "%";
        }
        
        // 請求年月から
        if (isset ( $request ["invoice_year_from"] ) && strlen ( $request ["invoice_year_from"] ) && isset ( $request ["invoice_month_from"] ) && strlen ( $request ["invoice_month_from"] )) {
            $where [] = "a.invoice_year_month >= ?";
            $bind [] = sprintf ( "%04d-%02d", $request ["invoice_year_from"], $request ["invoice_month_from"] );
        }
        
        // 請求年月まで
        if (isset ( $request ["invoice_year_to"] ) && strlen ( $request ["invoice_year_to"] ) && isset ( $request ["invoice_month_to"] ) && strlen ( $request ["invoice_month_to"] )) {
            $where [] = "a.invoice_year_month <= ?";
            $bind [] = sprintf ( "%04d-%02d", $request ["invoice_year_to"], $request ["invoice_month_to"] );
        }
        
        // 請求年
        if (isset ( $request ["invoice_year"] ) && strlen ( $request ["invoice_year"] )) {
            $where [] = "SUBSTRING(a.invoice_year_month, 1, 4) = ?";
            $bind [] = $request ["invoice_year"];
        }
        
        // 請求月
        if (isset ( $request ["invoice_month"] ) && strlen ( $request ["invoice_month"] )) {
            // $where[] = "SUBSTRING(a.invoice_year_month, 5, 2) = ?";
            $where [] = "SUBSTRING(a.invoice_year_month, 6, 2) = ?";
            // $bind[] = $request["invoice_month"];
            $bind [] = sprintf ( "%02d", intval ( $request ["invoice_month"] ) );
        }
        
        // 学年(カテゴリ)
        if (isset ( $request ["school_category"] ) && is_numeric ( $request ["school_category"] )) {
            $where [] = "d.school_category = ?";
            $bind [] = $request ["school_category"];
        }
        
        // 学年(年)
        if (isset ( $request ["school_year"] ) && is_numeric ( $request ["school_year"] )) {
            $where [] = "d.school_year = ?";
            $bind [] = $request ["school_year"];
        }
        
        // プラン
        if (isset ( $request ["class_id"] ) && strlen ( $request ["class_id"] )) {
            $where [] = "b.class_id = ?";
            $bind [] = $request ["class_id"];
        }
        
        // イベント
        if (isset ( $request ["course_id"] ) && strlen ( $request ["course_id"] )) {
            $where [] = "b.course_id = ?";
            $bind [] = $request ["course_id"];
        }
        
        // 編集フラグ
        if (isset ( $request ["is_established"] ) && strlen ( $request ["is_established"] )) {
            $where [] = "a.is_established = ?";
            $bind [] = $request ["is_established"];
        }
        
        // 請求フラグ
        if (isset ( $request ["is_requested"] ) && strlen ( $request ["is_requested"] )) {
            $where [] = "a.is_requested = ?";
            $bind [] = $request ["is_requested"];
        }
        
        // 入金フラグ
        if (isset ( $request ["is_recieved"] ) && strlen ( $request ["is_recieved"] )) {
            $where [] = "a.is_recieved = ?";
            $bind [] = $request ["is_recieved"];
        }
        
        // メール通知対象フラグ
        if (isset ( $request ["mail_announce"] ) && $request ["mail_announce"] != "") {
            $where [] = "a.mail_announce = ?";
            $bind [] = $request ["mail_announce"];
        }
        
        // 状況
        if (isset ( $request ["workflow_status"] ) && $request ["workflow_status"] != "") {
            $where [] = "a.workflow_status = ?";
            $bind [] = $request ["workflow_status"];
        }
        
        // 請求書送付 未通知
        if (isset ( $request ["not_requested"] ) && $request ["not_requested"] == "1") {
            $where [] = "a.is_requested < 1 ";
        }
        
        // 選択した請求書
        if (! empty ( $request ["invoice_ids"] )) {
            $s = "";
            foreach ( $request ['invoice_ids'] as $value ) {
                $s .= " OR a.id = ? ";
                $bind [] = $value;
            }
            $where [] = "( " . substr ( $s, 3 ) . " )";
        }
        
        // チェックボックスの請求方法
        if (isset ( $request ["invoice_type"] ) && count ( $request ["invoice_type"] ) > 0) {
            $invoice_type = "";
            foreach ( $request ["invoice_type"] as $key => $val ) {
                if (empty ( $invoice_type )) {
                    $invoice_type .= "( ";
                } else {
                    $invoice_type .= " OR ";
                }
                $invoice_type .= " c.invoice_type = " . $key;
            }
            $invoice_type .= ") ";
            $where [] = $invoice_type;
        }
        
        $where_str = "";
        if (! empty ( $where )) {
            $where_str = " AND " . implode ( " AND ", $where ) . " ";
        }
        
        $sql = "SELECT " . "a.id " . ", a.parent_id " . ", c.parent_name " . ", c.mail_infomation " . ", c.name_kana AS parent_name_kana " . ", c.invoice_type " . ", a.invoice_year_month " . ", a.amount " . ", a.is_established " . ", a.is_requested " . ", a.is_recieved " . ", a.mail_announce " . ", a.is_mail_announced " . ", a.amount_display_type " . ", a.sales_tax_rate " . ", a.workflow_status " . ", a.active_flag " . ", a.register_date " . ", c.memo " . "FROM " . "invoice_header AS a " . "INNER JOIN invoice_item AS b ON (a.id = b.invoice_id AND b.student_id IS NOT NULL) " . "INNER JOIN parent AS c ON (a.parent_id = c.id) " . "INNER JOIN student AS d ON (b.student_id = d.id) " . "WHERE " . "a.active_flag = 1 " . "AND a.delete_date IS NULL " . "AND d.active_flag = 1 " . "AND d.delete_date IS NULL " . $where_str . "GROUP BY a.id " . "ORDER BY a.workflow_status DESC, a.invoice_year_month DESC, c.name_kana, a.id DESC";
        
        $res = $this->fetchAll ( $sql, $bind );
        $res = $this->getStudentList ( $res, $school_id, $request );
        
        // 請求書ID
        if (isset ( $request ["xxx_id"] ) && strlen ( $request ["xxx_id"] ) && ! empty ( $res )) {
            return $res [0];
        } else {
            return json_decode ( json_encode ( $res ), true );
        }
    }
    
    // 検索画面用
    public function getListInclDisabledBySearch($school_id, $request = array()) {
        $where = array ();
        $bind = array ();
        
        // 塾ID
        $where [] = "a.pschool_id = ?";
        $bind [] = $school_id;
        
        // 保護者名＆フリガナ
        if (isset ( $request ["parent_name"] ) && ! empty ( $request ["parent_name"] )) {
            $where [] = "(c.parent_name LIKE ? OR c.name_kana LIKE ?)";
            $bind [] = "%" . $request ["parent_name"] . "%";
            $bind [] = "%" . $request ["parent_name"] . "%";
        }
        
        // 保護者名カナ
        if (isset ( $request ["parent_name_kana"] ) && ! empty ( $request ["parent_name_kana"] )) {
            $where [] = "c.name_kana LIKE ?";
            $bind [] = "%" . $request ["parent_name_kana"] . "%";
        }
        
        // 生徒氏名＆フリガナ
        if (isset ( $request ["student_name"] ) && ! empty ( $request ["student_name"] )) {
            $where [] = "(d.student_name LIKE ? OR d.student_name_kana LIKE ?)";
            $bind [] = "%" . $request ["student_name"] . "%";
            $bind [] = "%" . $request ["student_name"] . "%";
        }
        
        // 請求年
        if (isset ( $request ["invoice_year"] ) && ! empty ( $request ["invoice_year"] )) {
            $where [] = "SUBSTRING(a.invoice_year_month, 1, 4) = ?";
            $bind [] = $request ["invoice_year"];
        }
        
        // 請求月
        if (isset ( $request ["invoice_month"] ) && ! empty ( $request ["invoice_month"] )) {
            // $where[] = "SUBSTRING(a.invoice_year_month, 5, 2) = ?";
            $where [] = "SUBSTRING(a.invoice_year_month, 6, 2) = ?";
            $bind [] = sprintf ( "%02d", intval ( $request ["invoice_month"] ) );
        }
        
        // 学年(カテゴリ)
        if (isset ( $request ["school_category"] ) && is_numeric ( $request ["school_category"] )) {
            $where [] = "d.school_category = ?";
            $bind [] = $request ["school_category"];
        }
        
        // 学年(年)
        if (isset ( $request ["school_year"] ) && is_numeric ( $request ["school_year"] )) {
            $where [] = "d.school_year = ?";
            $bind [] = $request ["school_year"];
        }
        
        // プラン
        if (isset ( $request ["class_id"] ) && ! empty ( $request ["class_id"] )) {
            $where [] = "b.class_id = ?";
            $bind [] = $request ["class_id"];
        }
        
        // イベント
        if (isset ( $request ["course_id"] ) && ! empty ( $request ["course_id"] )) {
            $where [] = "b.course_id = ?";
            $bind [] = $request ["course_id"];
        }
        
        /**
         * // 編集フラグ
         * if (isset($request["is_established"]) && strlen($request["is_established"])) {
         * $where[] = "a.is_established = ?";
         * $bind[] = $request["is_established"];
         * }
         *
         * // 請求フラグ
         * if (isset($request["is_requested"]) && strlen($request["is_requested"])) {
         * $where[] = "a.is_requested = ?";
         * $bind[] = $request["is_requested"];
         * }
         */
        
        // 入金フラグ
        if (isset ( $request ["is_recieved"] )) {
            if ($request ["is_recieved"] == "1") {
                $where [] = "a.is_recieved = 1";
            } else if ($request ["is_recieved"] == "0") {
                $where [] = "a.is_recieved <> 1";
            }
        }
        
        // メール通知対象フラグ
        if (isset ( $request ["mail_announce"] ) && ! empty ( $request ["mail_announce"] )) {
            $where [] = "a.mail_announce = ?";
            $bind [] = $request ["mail_announce"];
        }
        
        // 請求方法
        if (isset ( $request ["invoice_type"] ) && $request ["invoice_type"] != "") {
            $where [] = "c.invoice_type = ?";
            $bind [] = $request ["invoice_type"];
        }
        
        // 状況
        if (isset ( $request ["workflow_status"] ) && $request ["workflow_status"] != "") {
            $where [] = "a.workflow_status = ?";
            $bind [] = $request ["workflow_status"];
        }
        
        $where_str = "";
        if (! empty ( $where )) {
            $where_str = " AND " . implode ( " AND ", $where ) . " ";
        }
        
        $sql = "SELECT " . "a.id " . ", a.parent_id " . ", c.parent_name " . ", c.name_kana AS parent_name_kana " . ", c.mail_infomation " . ", c.invoice_type " . ", a.invoice_year_month " . ", a.amount " . ", a.is_established " . ", a.is_requested " . ", a.is_recieved " . ", a.paid_date " . ", a.mail_announce " . ", a.is_mail_announced " . ", a.amount_display_type " . ", a.sales_tax_rate " . ", a.active_flag " . ", a.invoice_type AS invoice_method " . ", a.workflow_status " . ", a.register_date " . "FROM " . "invoice_header AS a " . "INNER JOIN invoice_item AS b ON (a.id = b.invoice_id AND b.student_id IS NOT NULL) " . "INNER JOIN parent AS c ON (a.parent_id = c.id) " . "INNER JOIN student AS d ON (b.student_id = d.id) " . "WHERE " . "a.delete_date IS NULL " . 
        // "AND d.active_flag = 1 " .
        "AND d.delete_date IS NULL " . $where_str . "GROUP BY a.id " . "ORDER BY a.id DESC";
        
        $res = $this->fetchAll ( $sql, $bind );
        
        $sql = "SELECT " . 
        // "b.* " .
        "b.*, a.school_category AS school_category_ii,  a.school_year AS school_year_ii " . "FROM " . "invoice_item AS a " . "INNER JOIN student AS b " . "ON (a.student_id = b.id) " . "WHERE " . "a.invoice_id = ? " . "AND a.pschool_id = ? " . 
        // "AND b.active_flag = 1 " .
        "AND b.delete_date IS NULL " . "GROUP BY " . "b.id " . "ORDER BY " . "b.school_category ASC" . ", b.school_year ASC" . ", b.student_name ASC";
        
        foreach ( $res as $k => $v ) {
            // $res[$k]["invoice_year_month"] = substr($v["invoice_year_month"], 0,4) . "-" . substr($v["invoice_year_month"], 4,2);
            // if ( strlen($v['invoice_year_month']) < 7){
            if (strlen ( $v ['invoice_year_month'] ) <= 7) {
                // $year = substr($v['invoice_year_month'], 0, 4);
                // $month = str_pad(substr($v["invoice_year_month"], 4,2), 2, 0, STR_PAD_LEFT);
                // $res[$k]["invoice_year_month"] = $year . "-" . $month;
                $res [$k] ["invoice_year_month"] = $v ['invoice_year_month'];
            }
            $bind = array (
                    $v ["id"],
                    $school_id 
            );
            $res [$k] ["student_list"] = $this->fetchAll ( $sql, $bind );
        }
        
        return json_decode ( json_encode ( $res ), true );
    }
    
    // 検索画面用(簡易版)
    public function getListInclDisabledBySearch2($school_id, $request = array()) {
        $where = array ();
        $bind = array ();
        
        // 塾ID
        $where [] = "a.pschool_id = ?";
        $bind [] = $school_id;
        
        // 保護者名＆フリガナ
        if (isset ( $request ["parent_name"] ) && ! empty ( $request ["parent_name"] )) {
            $where [] = "(c.parent_name LIKE ? OR c.name_kana LIKE ?)";
            $bind [] = "%" . $request ["parent_name"] . "%";
            $bind [] = "%" . $request ["parent_name"] . "%";
        }
        
        // 保護者名カナ
        if (isset ( $request ["parent_name_kana"] ) && ! empty ( $request ["parent_name_kana"] )) {
            $where [] = "c.name_kana LIKE ?";
            $bind [] = "%" . $request ["parent_name_kana"] . "%";
        }
        
        // 生徒氏名＆フリガナ
        if (isset ( $request ["student_name"] ) && ! empty ( $request ["student_name"] )) {
            $where [] = "(d.student_name LIKE ? OR d.student_name_kana LIKE ?)";
            $bind [] = "%" . $request ["student_name"] . "%";
            $bind [] = "%" . $request ["student_name"] . "%";
        }
        
        // 請求年 from
        if (isset ( $request ["invoice_year_from"] ) && ! empty ( $request ["invoice_year_from"] )) {
            $where [] = "SUBSTRING(a.invoice_year_month, 1, 4) >= ?";
            $bind [] = $request ["invoice_year_from"];
        }
        // 請求月
        if (isset ( $request ["invoice_month_from"] ) && ! empty ( $request ["invoice_month_from"] )) {
            $where [] = "SUBSTRING(a.invoice_year_month, 6, 2) >= ?";
            $bind [] = sprintf ( "%02d", intval ( $request ["invoice_month_from"] ) );
        }
        // 請求年 to
        if (isset ( $request ["invoice_year_to"] ) && ! empty ( $request ["invoice_year_to"] )) {
            $where [] = "SUBSTRING(a.invoice_year_month, 1, 4) <= ?";
            $bind [] = $request ["invoice_year_to"];
        }
        // 請求月
        if (isset ( $request ["invoice_month_to"] ) && ! empty ( $request ["invoice_month_to"] )) {
            $where [] = "SUBSTRING(a.invoice_year_month, 6, 2) <= ?";
            $bind [] = sprintf ( "%02d", intval ( $request ["invoice_month_to"] ) );
        }
        
        // 入金フラグ
        if (isset ( $request ["paid_type"] ) && count ( $request ["paid_type"] ) > 0) {
            $paid_str = "";
            foreach ( $request ["paid_type"] as $key => $val ) {
                if (empty ( $paid_str )) {
                    $paid_str .= "( ";
                } else {
                    $paid_str .= " OR ";
                }
                if ($key == 0) {
                    $paid_str .= "a.is_recieved <> 1";
                } else {
                    $paid_str .= "a.is_recieved = 1";
                }
            }
            $paid_str .= ")";
            $where [] = $paid_str;
        }
        
        // 請求方法
        if (isset ( $request ["invoice_type"] ) && count ( $request ["invoice_type"] ) > 0) {
            $invoice_type = "";
            foreach ( $request ["invoice_type"] as $key => $val ) {
                if (empty ( $invoice_type )) {
                    $invoice_type .= "( ";
                } else {
                    $invoice_type .= " OR ";
                }
                $invoice_type .= " c.invoice_type = " . $key;
            }
            $invoice_type .= ") ";
            $where [] = $invoice_type;
        }
        
        // 学年(カテゴリ)
        if (isset ( $request ["school_category"] ) && is_numeric ( $request ["school_category"] )) {
            $where [] = "d.school_category = ?";
            $bind [] = $request ["school_category"];
        }
        
        // 学年(年)
        if (isset ( $request ["school_year"] ) && is_numeric ( $request ["school_year"] )) {
            $where [] = "d.school_year = ?";
            $bind [] = $request ["school_year"];
        }
        
        // プラン
        if (isset ( $request ["class_id"] ) && ! empty ( $request ["class_id"] )) {
            $where [] = "b.class_id = ?";
            $bind [] = $request ["class_id"];
        }
        
        // 講習会
        if (isset ( $request ["course_id"] ) && ! empty ( $request ["course_id"] )) {
            $where [] = "b.course_id = ?";
            $bind [] = $request ["course_id"];
        }
        
        // 状況
        if (isset ( $request ["workflow_status"] ) && $request ["workflow_status"] != "") {
            $where [] = "a.workflow_status = ?";
            $bind [] = $request ["workflow_status"];
        }
        
        // 無効表示
        if (! isset ( $request ["inactive_flag"] ) || $request ["inactive_flag"] == "") {
            $where [] = "a.active_flag = 1";
        }
        
        $where_str = "";
        if (! empty ( $where )) {
            $where_str = " AND " . implode ( " AND ", $where ) . " ";
        }
        
        $sql = "SELECT " . "a.id " . ", a.parent_id " . ", c.parent_name " . ", c.name_kana AS parent_name_kana " . ", c.mail_infomation " . ", c.invoice_type " . ", a.invoice_year_month " . ", a.amount " . ", a.is_established " . ", a.is_requested " . ", a.is_recieved " . ", a.paid_date " . ", a.mail_announce " . ", a.is_mail_announced " . ", a.amount_display_type " . ", a.sales_tax_rate " . ", a.active_flag " . ", a.invoice_type AS invoice_method " . ", a.workflow_status " . ", a.register_date " . "FROM " . "invoice_header AS a " . "INNER JOIN invoice_item AS b ON (a.id = b.invoice_id AND b.student_id IS NOT NULL) " . "INNER JOIN parent AS c ON (a.parent_id = c.id) " . "INNER JOIN student AS d ON (b.student_id = d.id) " . "WHERE " . "a.delete_date IS NULL " . "AND d.active_flag = 1 " . "AND d.delete_date IS NULL " . $where_str . "GROUP BY a.id " . 
        "ORDER BY a.invoice_year_month DESC, c.name_kana, a.id DESC";
        $res = $this->fetchAll ( $sql, $bind );
        
        $sql = "SELECT " . 
        // "b.* " .
        "b.*, a.school_category AS school_category_ii,  a.school_year AS school_year_ii " . "FROM " . "invoice_item AS a " . "INNER JOIN student AS b " . "ON (a.student_id = b.id) " . "WHERE " . "a.invoice_id = ? " . "AND a.pschool_id = ? " . "AND b.active_flag = 1 " . "AND b.delete_date IS NULL " . "GROUP BY " . "b.id " . "ORDER BY " . "b.school_category ASC" . ", b.school_year ASC" . ", b.student_name ASC";
        
        foreach ( $res as $k => $v ) {
            // $res[$k]["invoice_year_month"] = substr($v["invoice_year_month"], 0,4) . "-" . substr($v["invoice_year_month"], 4,2);
            // if ( strlen($v['invoice_year_month']) < 7){
            if (strlen ( $v ['invoice_year_month'] ) <= 7) {
                // $year = substr($v['invoice_year_month'], 0, 4);
                // $month = str_pad(substr($v["invoice_year_month"], 4,2), 2, 0, STR_PAD_LEFT);
                // $res[$k]["invoice_year_month"] = $year . "-" . $month;
                $res [$k] ["invoice_year_month"] = $v ['invoice_year_month'];
            }
            $bind = array (
                    $v ["id"],
                    $school_id 
            );
            $res [$k] ["student_list"] = $this->fetchAll ( $sql, $bind );
        }
        return $res;
    }
    
    // 全銀ダウンロード検索用
    public function getListforDownloadBySearch($school_id, $request = array()) {
        $where = array ();
        $bind = array ();
        
        // 塾ID
        $where [] = "a.pschool_id = ?";
        $bind [] = $school_id;
        
        // 保護者名＆フリガナ
        if (isset ( $request ["parent_name"] ) && strlen ( $request ["parent_name"] )) {
            $where [] = "(c.parent_name LIKE ? OR c.name_kana LIKE ?)";
            $bind [] = "%" . $request ["parent_name"] . "%";
            $bind [] = "%" . $request ["parent_name"] . "%";
        }
        
        // 保護者名カナ
        if (isset ( $request ["parent_name_kana"] ) && strlen ( $request ["parent_name_kana"] )) {
            $where [] = "c.name_kana LIKE ?";
            $bind [] = "%" . $request ["parent_name_kana"] . "%";
        }
        
        // 生徒氏名＆フリガナ
        if (isset ( $request ["student_name"] ) && strlen ( $request ["student_name"] )) {
            $where [] = "(d.student_name LIKE ? OR d.student_name_kana LIKE ?)";
            $bind [] = "%" . $request ["student_name"] . "%";
            $bind [] = "%" . $request ["student_name"] . "%";
        }
        
        // 請求年月から
        if (isset ( $request ["invoice_year_from"] ) && strlen ( $request ["invoice_year_from"] ) && isset ( $request ["invoice_month_from"] ) && strlen ( $request ["invoice_month_from"] )) {
            $where [] = "a.invoice_year_month >= ?";
            $bind [] = sprintf ( "%04d-%02d", $request ["invoice_year_from"], $request ["invoice_month_from"] );
        }
        
        // 請求年月まで
        if (isset ( $request ["invoice_year_to"] ) && strlen ( $request ["invoice_year_to"] ) && isset ( $request ["invoice_month_to"] ) && strlen ( $request ["invoice_month_to"] )) {
            $where [] = "a.invoice_year_month <= ?";
            $bind [] = sprintf ( "%04d-%02d", $request ["invoice_year_to"], $request ["invoice_month_to"] );
        }
        
        // 請求年
        if (isset ( $request ["invoice_year"] ) && strlen ( $request ["invoice_year"] )) {
            $where [] = "SUBSTRING(a.invoice_year_month, 1, 4) = ?";
            $bind [] = $request ["invoice_year"];
        }
        
        // 請求月
        if (isset ( $request ["invoice_month"] ) && strlen ( $request ["invoice_month"] )) {
            // $where[] = "SUBSTRING(a.invoice_year_month, 5, 2) = ?";
            $where [] = "SUBSTRING(a.invoice_year_month, 6, 2) = ?";
            $bind [] = $request ["invoice_month"];
            $bind [] = sprintf ( "%02d", intval ( $request ["invoice_month"] ) );
        }
        
        // 学年(カテゴリ)
        if (isset ( $request ["school_category"] ) && is_numeric ( $request ["school_category"] )) {
            $where [] = "d.school_category = ?";
            $bind [] = $request ["school_category"];
        }
        
        // 学年(年)
        if (isset ( $request ["school_year"] ) && is_numeric ( $request ["school_year"] )) {
            $where [] = "d.school_year = ?";
            $bind [] = $request ["school_year"];
        }
        
        // プラン
        if (isset ( $request ["class_id"] ) && strlen ( $request ["class_id"] )) {
            $where [] = "b.class_id = ?";
            $bind [] = $request ["class_id"];
        }
        
        // イベント
        if (isset ( $request ["course_id"] ) && strlen ( $request ["course_id"] )) {
            $where [] = "b.course_id = ?";
            $bind [] = $request ["course_id"];
        }
        
        // 編集フラグ
        if (isset ( $request ["is_established"] ) && strlen ( $request ["is_established"] )) {
            $where [] = "a.is_established = ?";
            $bind [] = $request ["is_established"];
        }
        
        // 請求フラグ
        if (isset ( $request ["is_requested"] ) && strlen ( $request ["is_requested"] )) {
            $where [] = "a.is_requested = ?";
            $bind [] = $request ["is_requested"];
        }
        
        // 入金フラグ
        if (isset ( $request ["is_recieved"] ) && strlen ( $request ["is_recieved"] )) {
            if ($request ["is_recieved"] == "1") {
                $where [] = "a.is_recieved = 1";
            } else {
                $where [] = "a.is_recieved != 1";
            }
            // $where[] = "a.is_recieved = ?";
            // $bind[] = $request["is_recieved"];
        }
        
        // メール通知対象フラグ
        if (isset ( $request ["mail_announce"] ) && strlen ( $request ["mail_announce"] )) {
            $where [] = "a.mail_announce = ?";
            $bind [] = $request ["mail_announce"];
        }
        
        // 請求方法―→口座振替固定
        // if (isset($request["invoice_type"]) && strlen($request["invoice_type"])) {
        // $where[] = "c.invoice_type = 2";
        // $bind[] = $request["invoice_type"];
        // }
        
        // 未入金/入金済のチェックボックス
        if (! empty ( $request ['is_waiting'] ) && ! empty ( $request ['is_finish'] )) {
        } elseif (! empty ( $request ['is_waiting'] )) {
            $where [] = "a.is_recieved = ?";
            $bind [] = 0;
        } elseif (! empty ( $request ['is_waiting'] )) {
            $where [] = "a.is_recieved = ?";
            $bind [] = 1;
        }
        
        // チェックボックスの請求方法
        if (isset ( $request ["invoice_type"] ) && count ( $request ["invoice_type"] ) > 0) {
            $invoice_type = "";
            foreach ( $request ["invoice_type"] as $key => $val ) {
                if (empty ( $invoice_type )) {
                    $invoice_type .= "( ";
                } else {
                    $invoice_type .= " OR ";
                }
                $invoice_type .= " c.invoice_type = " . $key;
            }
            $invoice_type .= ") ";
            $where [] = $invoice_type;
        }
        
        $where_str = "";
        if (! empty ( $where )) {
            $where_str = " AND " . implode ( " AND ", $where ) . " ";
        }
        
        $sql = "SELECT " . "a.id " . ", a.parent_id " . ", c.parent_name " . ", c.name_kana AS parent_name_kana " . ", c.mail_infomation " . ", c.invoice_type " . ", a.invoice_year_month " . ", a.amount " . ", a.is_established " . ", a.is_requested " . ", a.is_recieved " . ", a.mail_announce " . ", a.is_mail_announced " . ", a.amount_display_type " . ", a.sales_tax_rate " . ", a.active_flag " . ", a.workflow_status " . ", a.register_date " . "FROM " . "invoice_header AS a " . "INNER JOIN invoice_item AS b ON (a.id = b.invoice_id AND b.student_id IS NOT NULL) " . "INNER JOIN parent AS c ON (a.parent_id = c.id) " . "INNER JOIN student AS d ON (b.student_id = d.id) " . "WHERE " . "a.delete_date IS NULL " . "AND d.active_flag = 1 " . "AND d.delete_date IS NULL " . $where_str . "GROUP BY a.id ";
        
        if (empty ( $request ['parent_sort'] )) {
            $sql .= "ORDER BY a.workflow_status DESC, a.invoice_year_month DESC, c.name_kana, a.id DESC";
        } else {
            $sql .= "ORDER BY c.name_kana, a.invoice_year_month DESC, a.id DESC";
        }

        $res = $this->fetchAll ( $sql, $bind );
        $res = $this->getStudentList ( $res, $school_id, $request );
        return json_decode ( json_encode ( $res ), true );
    }
    public function getStudentList($res, $school_id, $request = NULL) {
        $sql = "SELECT " . "a.school_category, " . "a.school_year, " . "b.id, " . "b.student_no, " . "b.student_type, " . "b.student_name " . "FROM " . "invoice_item AS a " . "INNER JOIN student AS b " . "ON (a.student_id = b.id) " . "WHERE " . "a.invoice_id = ? " . "AND a.pschool_id = ? " . "AND b.active_flag = 1 " . "AND b.delete_date IS NULL " . "GROUP BY " . "b.id " . "ORDER BY " . "b.school_category ASC" . ", b.school_year ASC" . ", b.student_name ASC";
        
        $ret = array ();
        if (isset ( $request ["invoice_status"] ) && strlen ( $request ["invoice_status"] )) {
            $req = $request ["invoice_status"];
        } else {
            $req = null;
        }
        
        foreach ( $res as $v ) {
            // 2015-07ではなく20157の対策
            if (strlen ( $v ['invoice_year_month'] ) < 7) {
                $year = substr ( $v ['invoice_year_month'], 0, 4 );
                $month = str_pad ( substr ( $v ['invoice_year_month'], 4, 2 ), 2, 0, STR_PAD_LEFT );
                $v ['invoice_year_month'] = $year . "-" . $month;
                // $res[$k]["invoice_year_month"] = $v['invoice_year_month'];
            }
            
            // 生徒のリスト
            $bind = array (
                    $v ["id"],
                    $school_id 
            );
            $v ['student_list'] = $this->fetchAll ( $sql, $bind );
            
            // ファイル名
            $binds = array ();
            $sqls = " SELECT e.processing_filename ";
            $sqls .= " FROM invoice_request e ";
            $sqls .= " WHERE e.invoice_header_id = ?";
            $binds [] = $v ['id'];
            $sqls .= " AND (status_flag = 1 OR status_flag = 2 OR status_flag = 3) ";
            
            $ary = $this->fetchAll ( $sqls, $binds );
            if (! empty ( $ary )) {
                $v ['processing_filename'] = $ary [0] ['processing_filename'];
            } else {
                $v ['processing_filename'] = '';
            }
            // */
            
            // 設定されていない場合対策
            if ($v ['workflow_status'] == 0) {
                $workflow_status = $v ['workflow_status'];
                // '0'=>'編集中','1'=>'編集完了','11'=>'請求書発送済み','21'=>'口座振替書作成済み','22'=>'金融機関処理中','29'=>'口座振替未完了','31'=>'入金済み',
                if ($v ['is_recieved'] == 1) {
                    $v ['workflow_status'] = 31;
                } elseif ($v ['is_requested'] == 22) {
                    $v ['workflow_status'] = 22;
                } elseif ($v ['is_requested'] == 21) {
                    $v ['workflow_status'] = 21;
                } elseif ($v ['is_established'] == 0) {
                    $v ['workflow_status'] = 0;
                } elseif ($v ['is_mail_announced'] != 0 || $v ['is_requested'] != 0 || $v ['workflow_status'] == 11) {
                    if ($v ['invoice_type'] == 2) {
                        $v ['workflow_status'] = 29;
                    } else {
                        $v ['workflow_status'] = 11;
                    }
                } else {
                    $v ['workflow_status'] = 1;
                }
                
                if ($workflow_status != $v ['workflow_status']) {
                    // 登録処理
                    try {
                        $this->beginTransaction ();
                        $header = array (
                                "id" => $v ['id'],
                                "workflow_status" => $v ['workflow_status'] 
                        );
                        $this->save ( $header );
                        InvoiceHeaderTable::getInstance ()->commit ();
                    } catch ( Exception $ex ) {
                        InvoiceHeaderTable::getInstance ()->rollBack ();
                    }
                }
            }
            // */
            
            // 絞り込み
            if ($req == null) {
                $ret [] = $v;
            } elseif ($req == 0 && $v ["workflow_status"] == 0) {
                $ret [] = $v;
            } elseif ($req == 1 && $v ["workflow_status"] == 1) {
                $ret [] = $v;
            } elseif ($req == 11 && $v ["workflow_status"] == 11) {
                $ret [] = $v;
            } elseif ($req == 21 && $v ["workflow_status"] == 21) {
                $ret [] = $v;
            } elseif ($req == 22 && $v ["workflow_status"] == 22) {
                $ret [] = $v;
            } elseif ($req == 29 && $v ["workflow_status"] == 29) {
                $ret [] = $v;
            } elseif ($req == 31 && $v ["workflow_status"] == 31) {
                $ret [] = $v;
            }
        }
        
        return $ret;
    }
    
    /**
     * 請求対象だが、まだ請求書を作成していない親の一覧を取得する。
     *
     * @param unknown $school_id            
     * @param unknown $year_month            
     * @param string $parent_id            
     */
    public function getGenerateTargetParentListByYearMonth($school_id, $year_month, $parent_id = NULL) {
        $bind = array (
                $year_month,
                $school_id 
        );
        
        $where = array ();
        if (! is_null ( $parent_id )) {
            $where [] = "parent.id = ? ";
            $bind [] = $parent_id;
        }
        
        $where = ! empty ( $where ) ? " AND " . implode ( " AND ", $where ) : "";
        
        $sql = "SELECT " . "parent.id" . ", parent.parent_name " . ", parent.mail_infomation " . "FROM " . "parent " . "INNER JOIN student " . "ON (student.parent_id = parent.id) " . "LEFT JOIN invoice_header AS i_h " . "ON (" . "i_h.parent_id = parent.id " . "AND i_h.invoice_year_month = ? " . "AND i_h.pschool_id = parent.pschool_id " . "AND i_h.active_flag = 1 " . "AND i_h.delete_date IS NULL " . ") " . "LEFT JOIN student_class AS s_class " . "ON (" . "s_class.student_id = student.id " . "AND s_class.active_flag = 1 " . "AND s_class.delete_date IS NULL " . ") " . "LEFT JOIN class " . "ON (" . "class.pschool_id = parent.pschool_id " . "AND class.id = s_class.class_id " . "AND class.active_flag = 1 " . "AND class.delete_date IS NULL " . ") " . "LEFT JOIN student_course_rel AS s_course " . "ON (" . "s_course.student_id = student.id " . "AND s_course.active_flag = 1 " . "AND s_course.delete_date IS NULL " . "AND s_course.is_received = 0 " . ") " . "LEFT JOIN course " . "ON (" . "course.pschool_id = parent.pschool_id " . "AND course.id = s_course.course_id " . "AND course.active_flag = 1 " . "AND course.delete_date IS NULL " . ") " . "WHERE " . "parent.pschool_id = ? " . "AND parent.delete_date IS NULL " . // 20150818 削除されてない親でけを取得
"AND student.pschool_id = parent.pschool_id " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "AND i_h.id IS NULL " . "AND (class.id IS NOT NULL OR course.id IS NOT NULL) " . $where . "GROUP BY parent.id " . "ORDER BY parent.id ASC";
        
//         $arr = $this->fetchAll ( $sql, $bind );
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * 請求対象だが、まだ請求書を作成していない親の一覧を取得する。
     *
     * @param unknown $school_id            
     * @param unknown $year_month            
     * @param string $parent_id            
     */
    public function getGenerateTargetParentListByYearMonth_Axis($school_id, $year_month, $parent_id = NULL) {
        $bind = array (
                $year_month,
                $school_id,
                $year_month,
                $year_month,
                // $year_month,
                $year_month,
                $year_month,
                // $year_month,
                $school_id 
        );
        
        $where = array ();
        if (! is_null ( $parent_id )) {
            $where [] = "parent.id = ? ";
            $bind [] = $parent_id;
        }
        
        $where = ! empty ( $where ) ? " AND " . implode ( " AND ", $where ) : "";
        
        $sql = "SELECT " . "parent.id" . ", parent.parent_name " . ", parent.mail_infomation " . ", parent.invoice_type " . "FROM " . "parent " . "INNER JOIN student " . "ON (student.parent_id = parent.id) " . "LEFT JOIN invoice_header AS i_h " . "ON (" . "i_h.parent_id = parent.id " . "AND i_h.invoice_year_month = ? " . "AND i_h.pschool_id = parent.pschool_id " . "AND i_h.active_flag = 1 " . "AND i_h.delete_date IS NULL " . ") " . "LEFT JOIN student_class AS s_class " . "ON (" . "s_class.student_id = student.id " . "AND s_class.pschool_id = ? " . "AND s_class.active_flag = 1 " . "AND s_class.delete_date IS NULL " . "AND s_class.plan_id IS NOT NULL " . ") " . "LEFT JOIN class " . "ON (" . "class.id = s_class.class_id " . "AND class.active_flag = 1 " . "AND class.delete_date IS NULL " . "AND (class.start_date IS NOT NULL AND SUBSTRING(class.start_date, 1, 7) <= ? ) " . "AND (class.close_date IS NULL OR SUBSTRING(class.close_date, 1, 7) >= ?) " . ") " . "LEFT JOIN student_course_rel AS s_course " . "ON (" . "s_course.student_id = student.id " . "AND s_course.active_flag = 1 " . "AND s_course.delete_date IS NULL " . "AND s_course.is_received = 0 " . "AND s_course.plan_id IS NOT NULL " . ") " . "LEFT JOIN course " . "ON (" . "course.id = s_course.course_id " . "AND course.active_flag = 1 " . "AND course.delete_date IS NULL " . 
        // "AND (course.start_date IS NOT NULL AND SUBSTRING(course.start_date, 1, 7) <= ? ) " .
        "AND SUBSTRING(course.start_date, 1, 7) = ? " . 
        // "AND (course.close_date IS NULL OR SUBSTRING(course.close_date, 1, 7) >= ?) " .
        ") " . "LEFT JOIN student_program AS s_program " . "ON (" . "s_program.student_id = student.id " . "AND s_program.active_flag = 1 " . "AND s_program.delete_date IS NULL " . "AND s_program.plan_id IS NOT NULL " . ") " . "LEFT JOIN program " . "ON (" . "program.id = s_program.program_id " . "AND program.active_flag = 1 " . "AND program.delete_date IS NULL " . 
        // "AND (program.start_date IS NOT NULL AND SUBSTRING(program.start_date, 1, 7) <= ? ) " .
        "AND SUBSTRING(program.start_date, 1, 7) = ? " . 
        // "AND (program.close_date IS NULL OR SUBSTRING(program.close_date, 1, 7) >= ?) " .
        ") " . 

        "WHERE " . "parent.pschool_id = ? " . "AND parent.delete_date IS NULL " . // 20150818 削除されてない親でけを取得
"AND student.pschool_id = parent.pschool_id " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "AND i_h.id IS NULL " . "AND (class.id IS NOT NULL OR course.id IS NOT NULL OR program.id IS NOT NULL) " . $where . "GROUP BY parent.id " . "ORDER BY parent.id ASC";
        return $this->fetchAll ( $sql, $bind );
    }
    
    // 請求書未作成の親一覧を取得する。
    public function getNotCreatedParentListByYearMonth($school_id, $year_month, $parent_id = NULL) {
        $bind = array (
                $year_month,
                $school_id 
        );
        
        $where = array ();
        if (! is_null ( $parent_id )) {
            $where [] = "parent.id = ? ";
            $bind [] = $parent_id;
        }
        
        $where = ! empty ( $where ) ? " AND " . implode ( " AND ", $where ) : "";
        
        $sql = "SELECT " . "parent.id" . ", parent.parent_name " . "FROM " . "parent " . "INNER JOIN student " . "ON (student.parent_id = parent.id) " . "LEFT JOIN invoice_header AS i_h " . "ON (" . "i_h.parent_id = parent.id " . "AND i_h.invoice_year_month = ? " . "AND i_h.pschool_id = parent.pschool_id " . "AND i_h.active_flag = 1 " . "AND i_h.delete_date IS NULL " . ") " . "WHERE " . "parent.pschool_id = ? " . "AND student.pschool_id = parent.pschool_id " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "AND i_h.id IS NULL " . $where . "GROUP BY parent.id " . "ORDER BY parent.id ASC";
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    public function getParentStudentListByInvoiceId($school_id, $invoice_id, $parent_id) {

        $sql_parent = "SELECT *,m_pref.name as pref_name, m_city.name as city_name , other_pref.name as other_pref_name, other_city.name as other_city_name
                      FROM parent 
                      LEFT JOIN m_pref ON parent.pref_id = m_pref.id
                      LEFT JOIN m_city ON parent.city_id = m_city.id
                      LEFT JOIN m_pref as other_pref ON parent.pref_id_other = other_pref.id
                      LEFT JOIN m_city as other_city ON parent.city_id_other = other_city.id
                      WHERE parent.id = ".$parent_id." 
                      AND parent.pschool_id = ".$school_id."
                      ;";

        $parent = $this->fetch($sql_parent);

        if (! empty ( $parent )) {
            $parent['address'] = $parent['pref_name'].$parent['city_name'].$parent['address'];
            $sql = "SELECT " . "DISTINCT student.* " . "FROM " . "invoice_item AS item " . "INNER JOIN student " . "ON (item.student_id = student.id) " . "WHERE " . "student.parent_id = ? " . "AND student.pschool_id = ? " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL " . "AND item.invoice_id = ? " . "ORDER BY " . "student.school_category ASC" . ", student.school_year ASC" . ", student.student_name ASC";
            
            $bind = array (
                    $parent_id,
                    $school_id,
                    $invoice_id 
            );
            $arr = $this->fetchAll ( $sql, $bind );
            $parent ["student_list"] = $arr;
        }
        
        return $parent;
    }
    
    /**
     * 年月指定で請求書を作成する。
     *
     * @param unknown $school_id            
     * @param unknown $login_account_id            
     * @param unknown $amount_display_type            
     * @param unknown $sales_tax_rate            
     * @param unknown $year_month            
     * @param unknown $due_date            
     */
    public function generateInvoiceByYearMonth($school_id, $login_account_id, $amount_display_type, $sales_tax_rate, $year_month, $due_date, $due_date_bank = null, $withdrawal_day = null) {
//         $parent_list = array();
        
        if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
            // -------------------------------------------------------------
            // 運用区分が塾の場合
            // -------------------------------------------------------------
            $parent_list = $this->getGenerateTargetParentListByYearMonth ( $school_id, $year_month );
        } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
            // -------------------------------------------------------------
            // 運用区分が会員クラブの場合
            // -------------------------------------------------------------
            $parent_list = $this->getGenerateTargetParentListByYearMonth_Axis ( $school_id, $year_month );
        }

        foreach ( $parent_list as $parent ) {
            
            if (! empty ( $withdrawal_day ) && array_get($parent,'invoice_type') == 2) {
                // 請求書ヘッダーを作成する。 口座振替
                $header = array (
                        "pschool_id" => $school_id,
                        "parent_id" => $parent ["id"],
                        "discount_price" => "0",
                        "is_established" => "0",
                        "mail_announce" => empty ( $parent ["mail_infomation"] ) ? "0" : "1",
                        "is_requested" => "0",
                        "is_recieved" => "0",
                        "invoice_year_month" => $year_month,
                        "amount_display_type" => $amount_display_type,
                        "sales_tax_rate" => $sales_tax_rate,
                        "active_flag" => 1,
                        "register_admin" => $login_account_id,
                        "due_date" => $due_date_bank 
                );
            } else {
                // 請求書ヘッダーを作成する。現金・振込み
                $header = array (
                        "pschool_id" => $school_id,
                        "parent_id" => $parent ["id"],
                        "discount_price" => "0",
                        "is_established" => "0",
                        "mail_announce" => empty ( $parent ["mail_infomation"] ) ? "0" : "1",
                        "is_requested" => "0",
                        "is_recieved" => "0",
                        "invoice_year_month" => $year_month,
                        "amount_display_type" => $amount_display_type,
                        "sales_tax_rate" => $sales_tax_rate,
                        "active_flag" => 1,
                        "register_admin" => $login_account_id,
                        "due_date" => $due_date 
                );
            }
            $header_id = InvoiceHeaderTable::getInstance ()->save ( $header );
            
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // -------------------------------------------------------------
                // 「プラン」の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateClassItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                // 「プラン」の割引・割増の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateClassAdjustItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                
                // 「イベント」の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateCourseItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                // 「イベント」の割引・割増の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateCourseAdjustItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
            } else if (session ( 'school.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
                // -------------------------------------------------------------
                // 運用区分が会員クラブの場合
                // -------------------------------------------------------------
                // 「プラン」の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateClassItem_Axis ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                // 「プラン」の割引・割増の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateClassAdjustItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                
                // 「イベント」の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateCourseItem_Axis ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                // 「イベント」の割引・割増の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateCourseAdjustItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                
                // 「プログラム」の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateProgramItem_Axis ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
                // 「プログラム」の割引・割増の請求項目を作成する。
                InvoiceItemTable::getInstance ()->generateProgramAdjustItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
            }
            
            // 「保護者」の割引・割増の請求項目を作成する。
            InvoiceItemTable::getInstance ()->generateParentAdjustItem ( $school_id, $login_account_id, $header_id, $parent ["id"], $year_month );
            
            // 合計金額を更新する。(この時点では割引額が存在しないので、考慮してません。)
            $sql = "UPDATE " . "invoice_header AS header " . "INNER JOIN (" . "SELECT " . "item.invoice_id" . ", sum(item.unit_price) AS sum_unit_price " . "FROM " . "invoice_item AS item " . "WHERE " . "item.invoice_id = ? " . "GROUP BY item.invoice_id" . ") AS item " . "ON (header.id = item.invoice_id) " . "SET amount = item.sum_unit_price " . "WHERE header.id = ?";
            $bind = array (
                    $header_id,
                    $header_id 
            );
            $this->execute ( $sql, $bind );
            
            if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
                // -------------------------------------------------------------
                // 運用区分が塾の場合
                // invouce_itemテーブルにstudentテーブルのschool_year,school_category設定
                // -------------------------------------------------------------
                InvoiceItemTable::getInstance ()->setCategoryYear ( $header_id );
            }
        }
        return count ( $parent_list );
    }
    public function getParentList($ids) {
        $sql = " SELECT DISTINCT IH.parent_id ";
        $sql .= " FROM invoice_header AS IH ";
        
        foreach ( $ids as $ii => $id ) {
            if ($ii == 0) {
                $sql .= " WHERE IH.id = ? ";
            } else {
                $sql .= " OR IH.id = ? ";
            }
        }
        $arr = $this->fetchAll ( $sql, $ids );
        return json_decode ( json_encode ( $arr ), true );
    }

    /**
     * 未入金リスト取得
     *
     * @param unknown $date            
     * @param unknown $pschool_id            
     * @param unknown $pay_method            
     */
    public function getArrearList($date, $pschool_id, $invoice_type = null) {
        $bind = array ();
        
        $sql = " SELECT HEAD.id, HEAD.parent_id, HEAD.parent_name, HEAD.amount, HEAD.payment_method, HEAD.is_recieved, HEAD.workflow_status, ";
        $sql .= " HEAD.sales_tax_rate, HEAD.amount_display_type, HEAD.invoice_year_month, ITEM.student_id, ITEM.student_name, ITEM.school_category, ITEM.school_year, ITEM.student_no ";
        $sql .= " FROM ";
        $sql .= "   (SELECT IH.*, PA.parent_name, PA.invoice_type AS payment_method ";
        $sql .= "   FROM invoice_header AS IH ";
        $sql .= "   INNER JOIN  parent AS PA ";
        $sql .= "   ON IH.parent_id = PA.id ";
        $sql .= "   WHERE IH.delete_date IS NULL ";
        $sql .= "   AND IH.active_flag = 1 ";
        $sql .= "   AND IH.is_recieved <> 1 ";
        $sql .= "   AND IH.pschool_id = ? ";
        $bind [] = $pschool_id;
        $sql .= "   AND IH.invoice_year_month <= ? ";
        $bind [] = $date;
        if ($invoice_type != null) {
            $sql .= " AND PA.invoice_type = ?";
            $bind [] = $invoice_type;
        }
        $sql .= ") AS HEAD ";
        $sql .= " INNER JOIN ";
        $sql .= "   (SELECT distinct II.invoice_id, II.student_id, II.school_year, II.school_category, ST.student_name, ST.student_no ";
        $sql .= "   FROM invoice_item AS II ";
        $sql .= "   INNER JOIN student AS ST ";
        $sql .= "   ON II.student_id = ST.id ";
        $sql .= "   WHERE II.active_flag = 1 ";
        $sql .= "   AND II.delete_date IS NULL ";
        // $sql .= " GROUP BY II.student_id ) AS ITEM ";
        $sql .= "   ) AS ITEM ";
        $sql .= " ON HEAD.id = ITEM.invoice_id ";
        $sql .= " ORDER BY HEAD.invoice_year_month ASC, HEAD.parent_id, ITEM.student_id ";
        
        $arr = $this->fetchAll ( $sql, $ids );
        $Rows = json_decode ( json_encode ( $arr ), true );
        // 多言語対応
        $bind = array ();
        $sql = " SELECT language FROM pschool WHERE id = ? AND delete_date IS NULL ";
        $bind [] = $pschool_id;
        $res = $this->fetch ( $sql, $bind );
        $lang = empty ( $res ['language'] ) ? 1 : $res ['language']; // Default is Japanese
        
        $invoice_method = ConstantsModel::$invoice_type [$lang];
        $school_category = ConstantsModel::$dispSchoolCategory;
        
        $list = array ();
        foreach ( $Rows as &$Row_item ) {
            if ($Row_item ['student_no'] == null) {
                $Row_item ['student_no'] = "";
            }
            $Row_item ['payment_method'] = $invoice_method [$Row_item ['payment_method']];
            
            if (intval ( $Row_item ['is_recieved'], 10 ) < 0) {
                $Row_item ['workflow_status'] = ConstantsModel::$workflow_status [$lang] [($Row_item ['workflow_status'])] . '<br/>' . ConstantsModel::$zengin_status [$lang] [($Row ['is_recieved'])];
            } else {
                $Row_item ['workflow_status'] = ConstantsModel::$workflow_status [$lang] [($Row_item ['workflow_status'])];
            }
            
            $Row_item ['school_category_name'] = $school_category [$Row_item ['school_category']];
            
            $list [] = $Row_item;
        }
        
        return $list;
    }
    
    /**
     * 前の請求書のidを取得。 ない場合０を返す
     */
    public function getBefore($pschool_id, $current) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $current;
        
        $sql = " SELECT id FROM invoice_header WHERE delete_date is NULL AND ";
        $sql .= " pschool_id = ? and id < ?ORDER BY id DESC LIMIT 0,1";
        $res = $this->fetch ( $sql, $bind );
        $before = 0;
        if ($res ['id']) {
            $before = $res ['id'];
        }
        return $before;
    }
    
    /**
     * 後の請求書のidを取得。 ない場合０を返す
     */
    public function getAfter($pschool_id, $current) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $current;
        
        $sql = " SELECT id FROM invoice_header WHERE delete_date is NULL AND ";
        $sql .= " pschool_id = ? and id > ?ORDER BY id LIMIT 0,1";
        $res = $this->fetch ( $sql, $bind );
        $after = 0;
        if ($res ['id']) {
            $after = $res ['id'];
        }
        return $after;
    }
    
    /**
     * 最新請求月取得
     *
     * @param unknown $pschool_id            
     * @return unknown
     */
    public function getNewestYearMonth($pschool_id) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT invoice_year_month ";
        $sql .= " FROM invoice_header ";
        $sql .= " WHERE pschool_id = ? ";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " GROUP BY invoice_year_month";
        $sql .= " ORDER BY invoice_year_month DESC ";
        $sql .= " LIMIT 1 ";
        $result = $this->fetch ( $sql, $bind );
        return $result;
    }
    
    /**
     * 保護者
     *
     * @param unknown $pschool_id            
     * @param unknown $target_year_month            
     * @param string $school_category            
     * @param string $school_year            
     */
    public function getSpotAdjustList($pschool_id, $target_year_month, $school_category = null, $school_year = null) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT IH.*, PS.school_category, PS.school_year, PS.student_id ";
        $sql .= " FROM invoice_header AS IH ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        $sql .= "     SELECT P.id, S.school_category, S.school_year, S.id AS student_id ";
        $sql .= "     FROM parent AS P ";
        $sql .= "     INNER JOIN student AS S ";
        $sql .= "     ON P.id = S.parent_id ";
        $sql .= "     WHERE P.delete_date IS NULL ";
        $sql .= "     AND P.pschool_id = ? ";
        if (! empty ( $school_category )) {
            $sql .= "     AND S.school_category = ? ";
            $bind [] = $school_category;
        }
        if (! empty ( $school_year )) {
            $sql .= "     AND S.school_year = ? ";
            $bind [] = $school_year;
        }
        $sql .= "     GROUP BY P.id ";
        $sql .= " ) AS PS ";
        $sql .= " ON IH.parent_id = PS.id ";
        $sql .= " WHERE IH.invoice_year_month = ? ";
        $bind [] = $target_year_month;
        $sql .= " AND IH.workflow_status < 1 ";
        $sql .= "AND IH.delete_date IS NULL ";
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * プラン
     *
     * @param unknown $pschool_id            
     * @param unknown $target_year_month            
     * @param string $school_category            
     * @param string $school_years            
     * @param unknown $class_event            
     */
    public function getSpotAdjustList2($pschool_id, $target_year_month, $school_category = null, $school_years = null, $class_event = null) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $class_id = "";
        if (! empty ( $class_event )) {
            $split = explode ( "_", $class_event );
            $class_id = $split [1];
        }
        
        $sql = " SELECT IH.*, SPSCC.school_category, SPSCC.school_year, SPSCC.student_id, SPSCC.class_id ";
        $sql .= " FROM invoice_header AS IH ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        
        $sql .= "     SELECT SP.*, SCC.class_id ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "        SELECT P.id, S.id AS student_id, S.school_category, S.school_year ";
        $sql .= "        FROM student AS S ";
        $sql .= "        INNER JOIN parent AS P ";
        $sql .= "        ON S.parent_id = P.id ";
        $sql .= "        WHERE S.pschool_id = ? ";
        $sql .= "        AND P.delete_date IS NULL ";
        $sql .= "        AND S.delete_date IS NULL ";
        if (! empty ( $school_category )) {
            $sql .= "        AND S.school_category = ? ";
            $bind [] = $school_category;
        }
        if (! empty ( $school_year )) {
            $sql .= "        AND S.school_year = ? ";
            $bind [] = $school_year;
        }
        $sql .= "     ) AS SP ";
        
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, C.id AS class_id ";
        $sql .= "         FROM class AS C ";
        $sql .= "         INNER JOIN student_class AS SC ";
        $sql .= "         ON C.id = SC.class_id ";
        $sql .= "         WHERE C.delete_date IS NULL ";
        $sql .= "         AND C.pschool_id = ? ";
        $sql .= "         AND SC.delete_date IS NULL ";
        $sql .= "         AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        $bind [] = $pschool_id;
        $bind [] = $target_year_month;
        $bind [] = $target_year_month;
        if (! empty ( $class_id )) {
            $sql .= "         AND C.id = ? ";
            $bind [] = $class_id;
        }
        $sql .= "     ) AS SCC ";
        $sql .= "     ON SP.student_id = SCC.student_id ";
        
        $sql .= " ) AS SPSCC ";
        $sql .= " ON IH.parent_id = SPSCC.id ";
        $sql .= " WHERE IH.delete_date IS NULL ";
        $sql .= " AND IH.pschool_id = ? ";
        $sql .= " AND IH.workflow_status < 1 ";
        
        $bind [] = $pschool_id;
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * イベント
     *
     * @param unknown $pschool_id            
     * @param unknown $target_year_month            
     * @param string $school_category            
     * @param string $school_years            
     * @param string $class_event            
     */
    public function getSpotAdjustList3($pschool_id, $target_year_month, $school_category = null, $school_years = null, $class_event = null) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $event_id = "";
        if (! empty ( $class_event )) {
            $split = explode ( "_", $class_event );
            $event_id = $split [1];
        }
        
        $sql = " SELECT IH.*, SPSCC.school_category, SPSCC.school_year, SPSCC.student_id, SPSCC.course_id";
        $sql .= " FROM invoice_header AS IH ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        
        $sql .= "     SELECT SP.*, SCC.course_id ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "        SELECT P.id, S.id AS student_id, S.school_category, S.school_year ";
        $sql .= "        FROM student AS S ";
        $sql .= "        INNER JOIN parent AS P ";
        $sql .= "        ON S.parent_id = P.id ";
        $sql .= "        WHERE S.pschool_id = ? ";
        $sql .= "        AND P.delete_date IS NULL ";
        $sql .= "        AND S.delete_date IS NULL ";
        if (! empty ( $school_category )) {
            $sql .= "        AND S.school_category = ? ";
            $bind [] = $school_category;
        }
        if (! empty ( $school_year )) {
            $sql .= "        AND S.school_year = ? ";
            $bind [] = $school_year;
        }
        $sql .= "     ) AS SP ";
        
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT SC.student_id, C.id AS course_id ";
        $sql .= "         FROM course AS C ";
        $sql .= "         INNER JOIN student_course_rel AS SC ";
        $sql .= "         ON C.id = SC.course_id ";
        $sql .= "         WHERE C.delete_date IS NULL ";
        $sql .= "         AND SC.delete_date IS NULL ";
        // $sql .= " AND (C.start_date IS NOT NULL AND SUBSTRING(C.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND SUBSTRING(C.start_date, 1, 7) = ? ";
        // $sql .= " AND (C.close_date IS NULL OR SUBSTRING(C.close_date, 1, 7) >= ?) ";
        // $bind[] = $target_year_month;
        $bind [] = $target_year_month;
        if (! empty ( $event_id )) {
            $sql .= "         AND C.id = ? ";
            $bind [] = $event_id;
        }
        $sql .= "     ) AS SCC ";
        $sql .= "     ON SP.student_id = SCC.student_id ";
        
        $sql .= " ) AS SPSCC ";
        $sql .= " ON IH.parent_id = SPSCC.id ";
        $sql .= " WHERE IH.delete_date IS NULL ";
        $sql .= " AND IH.pschool_id = ? ";
        $sql .= " AND IH.workflow_status < 1 ";
        
        $bind [] = $pschool_id;
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    
    /**
     * プログラム
     *
     * @param unknown $pschool_id            
     * @param unknown $target_year_month            
     * @param string $school_category            
     * @param string $school_years            
     * @param string $class_event            
     */
    public function getSpotAdjustList4($pschool_id, $target_year_month, $school_category = null, $school_years = null, $class_event = null) {
        $bind = array ();
        $bind [] = $pschool_id;
        
        $event_id = "";
        if (! empty ( $class_event )) {
            $split = explode ( "_", $class_event );
            $event_id = $split [1];
        }
        
        $sql = " SELECT IH.*, SPPSP.school_category, SPPSP.school_year, SPPSP.student_id, SPPSP.program_id";
        $sql .= " FROM invoice_header AS IH ";
        $sql .= " INNER JOIN ";
        $sql .= " ( ";
        
        $sql .= "     SELECT SP.*, PSP.program_id ";
        $sql .= "     FROM ";
        $sql .= "     ( ";
        $sql .= "        SELECT P.id, S.id AS student_id, S.school_category, S.school_year ";
        $sql .= "        FROM student AS S ";
        $sql .= "        INNER JOIN parent AS P ";
        $sql .= "        ON S.parent_id = P.id ";
        $sql .= "        WHERE S.pschool_id = ? ";
        $sql .= "        AND P.delete_date IS NULL ";
        $sql .= "        AND S.delete_date IS NULL ";
        if (! empty ( $school_category )) {
            $sql .= "        AND S.school_category = ? ";
            $bind [] = $school_category;
        }
        if (! empty ( $school_year )) {
            $sql .= "        AND S.school_year = ? ";
            $bind [] = $school_year;
        }
        $sql .= "     ) AS SP ";
        
        $sql .= "     INNER JOIN ";
        $sql .= "     ( ";
        $sql .= "         SELECT SP.student_id, P.id AS program_id ";
        $sql .= "         FROM program AS P ";
        $sql .= "         INNER JOIN student_program AS SP ";
        $sql .= "         ON P.id = SP.program_id ";
        $sql .= "         WHERE P.delete_date IS NULL ";
        $sql .= "         AND SP.delete_date IS NULL ";
        // $sql .= " AND (P.start_date IS NOT NULL AND SUBSTRING(P.start_date, 1, 7) <= ? ) ";
        $sql .= "         AND SUBSTRING(P.start_date, 1, 7) = ? ";
        // $sql .= " AND (P.close_date IS NULL OR SUBSTRING(P.close_date, 1, 7) >= ?) ";
        // $bind[] = $target_year_month;
        $bind [] = $target_year_month;
        if (! empty ( $event_id )) {
            $sql .= "         AND P.id = ? ";
            $bind [] = $event_id;
        }
        $sql .= "     ) AS PSP ";
        $sql .= "     ON SP.student_id = PSP.student_id ";
        
        $sql .= " ) AS SPPSP ";
        $sql .= " ON IH.parent_id = SPPSP.id ";
        $sql .= " WHERE IH.delete_date IS NULL ";
        $sql .= " AND IH.pschool_id = ? ";
        $sql .= " AND IH.workflow_status < 1 ";
        
        $bind [] = $pschool_id;
        
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
    public function getLastInvoiceByStudentID($student_id = null) {
        $ret = '';
        if (! empty ( $student_id )) {
            $sql = " SELECT DISTINCT a.workflow_status, c.invoice_id FROM invoice_header as a ";
            $sql .= " LEFT JOIN invoice_item c ON ( a.id = c.invoice_id AND c.active_flag = 1 AND c.delete_date is NULL )";
            $sql .= " WHERE c.student_id = ? ";
            $sql .= " AND a.active_flag = 1 ";
            $sql .= " AND a.delete_date is NULL ";
            $sql .= " ORDER BY a.invoice_year_month DESC LIMIT 20";
            $bind = array (
                    $student_id 
            );
            $res = $this->fetchAll ( $sql, $bind );
            
            if (! empty ( $res )) {
                $workflow_status = 31;
                foreach ( $res as $key => $value ) {
                    if ($value ['workflow_status'] < $workflow_status)
                        $workflow_status = $value ['workflow_status'];
                }
            } else {
                return '';
            }
            
            // 0：編集中、1：編集完了、11：請求書発送済み、21：口座振替書作成済、22：金融機関処理中、29：口座振替未完了、31：入金済
            // if ($workflow_status == 0){
            // $ret = '編集中';
            // }elseif ($workflow_status == 1){
            // $ret = '編集完了';
            // }elseif ($workflow_status == 11){
            // $ret = '請求書発送済み';
            // }elseif ($workflow_status == 21){
            // $ret = '口座振替書作成済';
            // }elseif ($workflow_status == 22){
            // $ret = '金融機関処理中';
            // }elseif ($workflow_status == 29){
            // $ret = '口座振替未完了';
            // }elseif ($workflow_status == 31){
            // $ret = '入金済';
            // }
            
            $language = session ( 'school.login' ) ['language'];
            switch ($workflow_status) {
                case 0 :
                case 1 :
                case 11 :
                case 21 :
                case 22 :
                case 29 :
                case 31 :
                    $ret = ConstantsModel::$workflow_status [$language] [$workflow_status];
                    break;
                default :
                    $ret = "";
                    break;
            }
        }
        return $ret;
    }
    public function getAxisInvoiceList($session = null, $request = null) {
        $ret = array ();
        $bind = array ();
        
        $sql = " SELECT a.invoice_year_month, SUBSTRING(a.invoice_year_month, 1, 4) as invoice_year, SUBSTRING(a.invoice_year_month, 6) as invoice_month, ";
        $sql .= " COUNT(*) as cnt_entry, COUNT(a.workflow_status > 0 or null) as cnt_confirm, COUNT(a.workflow_status > 1 or null) as cnt_send, COUNT(a.workflow_status = 31 or null) as cnt_complete, ";
        $sql .= " MAX(a.register_date) as register_date, COUNT(DISTINCT a.parent_id) as cnt_invoice, COUNT(DISTINCT a.parent_id, c.invoice_type = 2 or null) as cnt_richo, COUNT(DISTINCT a.parent_id) - COUNT(DISTINCT a.parent_id, c.invoice_type = 2 or null) as cnt_other ";
        $sql .= " FROM invoice_header as a ";
        $sql .= " LEFT JOIN parent c ON ( a.parent_id = c.id )";
        $sql .= " WHERE a.active_flag = 1 ";
        $sql .= " AND a.delete_date is NULL ";
        
        if (! empty ( $session ['id'] )) {
            $sql .= " AND a.pschool_id = ? ";
            $bind [] = $session ['id'];
        }
        if (! empty ( $request ['invoice_year_month'] )) {
            $sql .= " AND a.invoice_year_month = ? ";
            $bind [] = $request ['invoice_year_month'];
        }
        
        $sql .= " GROUP BY a.invoice_year_month ";
        $sql .= " ORDER BY a.invoice_year_month ";
        
        $res = json_decode ( json_encode ( $this->fetchAll ( $sql, $bind ) ), true );
        
        $last_month = date ( 'Y-m', strtotime ( "+1 month" ) );
        if (empty ( $session ['payment_style'] ) || $session ['payment_style'] == 1) {
            // update 2016/02/07
            // $last_month = date('Y-m', strtotime("+2 month"));
            // $year_mont_ret = $this->getInvoiceYearMonth();
            $year_mont_ret = $this->getInvoiceYearMonth2 ( $last_month );
            $last_month = $year_mont_ret ['year_month'];
        }
        
        if (empty ( $res )) {
            // blankをいれる
            $year_month = empty ( $request ['invoice_year_month'] ) ? $last_month : $request ['invoice_year_month'];
            $ret [] = $this->getAxisBlankInvoice ( $year_month );
        } elseif (empty ( $request ['invoice_year_month'] )) {
            // 空の年月があったらblankをいれる
            $aaa = $this->getAxisMonthDec ( $res [0] ['invoice_year_month'] );
            $zzz = $this->getAxisMonthDec ( $last_month );
            $last = end ( $res );
            $xxx = $this->getAxisMonthDec ( $last ['invoice_year_month'] );
            if ($xxx > $zzz)
                $zzz = $xxx;
            for($month = $aaa; $month <= $zzz; $month ++) {
                $row = array ();
                $year_month = $this->getAxisMonthEnc ( $month );
                foreach ( $res as $key => $value ) {
                    if (! empty ( $value ['invoice_year_month'] ) && $year_month == $value ['invoice_year_month']) {
                        $row = $value;
                    }
                }
                if (empty ( $row )) {
                    $ret [] = $this->getAxisBlankInvoice ( $year_month );
                } else {
                    $ret [] = $row;
                }
            }
            $ret = array_reverse ( $ret );
        } else {
            // sqlの逆順
            $ret = array_reverse ( $res );
        }
        
        return $ret;
    }
    private function getAxisMonthDec($year_month = null) {
        if (empty ( $year_month )) {
            $month = 0;
        } else {
            $month = substr ( $year_month, 0, 4 ) * 12 + substr ( $year_month, - 2 );
        }
        return $month;
    }
    private function getAxisMonthEnc($month = null) {
        if (empty ( $month )) {
            $year_month = 0;
        } else {
            $y = sprintf ( '%04d', floor ( $month / 12 ) );
            $m = sprintf ( '%02d', $month % 12 );
            if ($m == 0) {
                $y = sprintf ( '%04d', floor ( $month / 12 ) - 1 );
                $m = 12;
            }
            $year_month = $y . "-" . $m;
        }
        return $year_month;
    }
    private function getAxisBlankInvoice($year_month = null) {
        if (empty ( $year_month ))
            $year_month = date ( 'Y-m' );
        
        $res = array ();
        $res ['cnt_entry'] = 0;
        $res ['cnt_confirm'] = 0;
        $res ['cnt_send'] = 0;
        $res ['cnt_complete'] = 0;
        $res ['invoice_year_month'] = $year_month;
        $res ['invoice_year'] = substr ( $year_month, 0, 4 );
        $res ['invoice_month'] = substr ( $year_month, - 2 );
        $res ['register_date'] = null;
        $res ['cnt_invoice'] = 0;
        $res ['cnt_richo'] = 0;
        $res ['cnt_other'] = 0;
        return $res;
    }
    
    // ---------------------------------------------------------------------
    // 現在の日付で作成できるのは、何月分？ $target_month
    // ---------------------------------------------------------------------
    private function getInvoiceYearMonth() {
        $ret = array ();
        // 塾の情報取得
        $pschool_data = PschoolTable::getInstance ()->load ( $_SESSION ['school.login'] ['id'] );
        // 塾締日
        $due_day = $pschool_data ['invoice_closing_date'];
        // 先払い／後払い
        $pay_style = $pschool_data ['payment_style'];
        // 口座引落日
        $withdrawal_day = $pschool_data ['withdrawal_day'];
        
        // 現在日付取得
        $curr_date = date ( 'Y-m-d' );
        $curr_day = date ( 'd' );
        
        // 現在からどの月分が作成できるか？
        if (intval ( $withdrawal_day ) < 1) {
            // 口座引落ししない 塾の締日が判定の条件
            
            // 99が末日 大小比較で使用するのみなので、日付に変換しない
            
            if (intval ( $due_day ) >= intval ( date ( 'd' ) )) {
                // 塾締日より前の場合
                
                // 今月請求
                $target_month = date ( 'Y-m' );
            } else {
                // 来月請求分
                $target_month = date ( 'Y-m', strtotime ( "+1 month" ) );
            }
            
            if ($pay_style == 1) {
                // 先払いの場合、翌月
                $target_month = date ( 'Y-m', strtotime ( "+1 month" ) );
            }
        } else {
            // 口座引落しする リコーリース殿への依頼書提出期限が判定の条件
            $target_month = date ( 'Y-m' );
            if ($pay_style == 1) {
                // 先払いの場合、翌月
                $target_month = date ( 'Y-m', strtotime ( "+1 month" ) );
            }
            
            // 提出期限取得
            $closingRow = ClosingDayTable::getInstance ()->getRow ( $where = array (
                    'transfer_day' => $withdrawal_day,
                    'transfer_month' => "" . $target_month,
                    'delete_date IS NULL' 
            ) );
            if ($closingRow ['deadline'] >= date ( 'Y-m-d' )) {
                // 提出期限を越えていない
                $target_month = substr ( $closingRow ['deadline'], 0, 7 );
            } else {
                // 提出期限を越えているので、次月分へ
                $target_month = date ( 'Y-m', strtotime ( $closingRow ['deadline'] . " +1 month" ) );
            }
        }
        
        $ret ['year'] = substr ( $target_month, 0, 4 );
        $ret ['month'] = substr ( $target_month, 5, 2 );
        $ret ['year_month'] = $target_month;
        return $ret;
    }
    
    // ---------------------------------------------------------------------
    // 現在の日付で作成できるのは、何月分？ $target_month
    // ---------------------------------------------------------------------
    private function getInvoiceYearMonth2($year_month) {
        $ret = array ();
        // 塾の情報取得
        $pschool_data = PschoolTable::getInstance ()->load ( session('school.login') ['id'] );
        // 塾締日
        $due_day = $pschool_data ['invoice_closing_date'];
        // 先払い／後払い
        $pay_style = $pschool_data ['payment_style'];
        // 口座引落日
        $withdrawal_day = $pschool_data ['withdrawal_day'];
        
        // 現在日付取得
        $curr_date = date ( 'Y-m-d' );
        $curr_day = date ( 'd' );
        
        // 現在からどの月分が作成できるか？
        if (intval ( $withdrawal_day ) < 1) {
            // 口座引落ししない 塾の締日が判定の条件
            
            // 99が末日 大小比較で使用するのみなので、日付に変換しない
            
            if (intval ( $due_day ) >= intval ( date ( 'd' ) )) {
                // 塾締日より前の場合
                
                // 今月請求
                $target_month = date ( 'Y-m' );
            } else {
                // 来月請求分
                $target_month = date ( 'Y-m', strtotime ( "+1 month" ) );
            }
            
            if ($pay_style == 1) {
                // 先払いの場合、翌月
                $target_month = date ( 'Y-m', strtotime ( "+1 month" ) );
            }
        } else {
            // 口座引落しする リコーリース殿への依頼書提出期限が判定の条件
            $target_month = date ( 'Y-m' );
            if ($pay_style == 1) {
                // 先払いの場合、翌月
                $target_month = date ( 'Y-m', strtotime ( "+1 month" ) );
                $target_date = $year_month . "-01";
            } else {
                // 後払い
                $target_date = $year_month . "-01";
                $last_date = date ( 't', $target_date );
                $target_date = $year_month . "-" . $last_date;
            }
            
            // 同じ口座振替日
            $close_days = ClosingDayTable::getInstance ()->getList ( array (
                    'transfer_day' => $withdrawal_day 
            ), array (
                    'transfer_month' => 'ASC' 
            ) );
            $today = date ( 'U' );
            $deadline = "2038-12-31";
            // 本日より大きい中で最小の締切日を探す
            foreach ( $close_days as $close_item ) {
                $temp_date = date ( 'U', strtotime ( $close_item ['deadline'] ) );
                if ($today <= $temp_date) {
                    if ($close_item ['deadline'] <= $deadline) {
                        $deadline = $close_item ['deadline'];
                        $target_month = $close_item ['transfer_month'];
                    }
                }
            }
            
            if ($pay_style == 1) {
                // 先払いで20日・27日は月を+1
                if ($withdrawal_day == 20 || $withdrawal_day == 27) {
                    // 月 + 1
                    $target_month = date ( 'Y-m', strtotime ( $target_month . " +1 month" ) );
                }
            } else {
                // 後払いで4日は月を-1
                if ($withdrawal_day == 4) {
                    // 月 - 1
                    $target_month = date ( 'Y-m', strtotime ( $target_month . " -1 month" ) );
                }
            }
        }
        
        $ret ['year'] = substr ( $target_month, 0, 4 );
        $ret ['month'] = substr ( $target_month, 5, 2 );
        $ret ['year_month'] = $target_month;
        return $ret;
    }
    
    /**
     * 会員の支払状況取得
     *
     * @param unknown $parent            
     * @return multitype:unknown
     */
    public function getStudentPaymentStatus($parent) {
        $pschool = PschoolTable::getInstance ()->load ( $_SESSION ['school.login'] ['id'] );
        
        // 請求書情報取得
        $invoice_list = InvoiceHeaderTable::getInstance ()->getList ( array (
                'parent_id' => $parent ['id'],
                'delete_date IS NULL' 
        ), array (
                'invoice_year_month' => 'DESC' 
        ) );
        
        $invoice_result = array ();
        $read_flag = false;
        if (count ( $invoice_list ) > 0) {
            foreach ( $invoice_list as $list_item ) {
                $read_flag = true;
                if ($list_item ['due_date'] < date ( 'Y-m-d' )) {
                    // 支払期限日より過ぎているものを対象
                    if ($pschool ['withdrawal_day'] > 0 && $parent ['invoice_type'] == 2) {
                        // 口座振替の場合
                        // $invoice_request = InvoiceRequestTable::getInstance()->getList(array('invoice_header_id'=>$list_item['id']));
                        // foreach ($invoice_request as $request_item){
                        // if( $request_item['status_flag'] < 0 ){
                        // $paid_detail = array();
                        // $paid_detail = array('invoice_year_month'=>$list_item['invoice_year_month'], 'reason'=>ConstantsModel::$zengin_status[$_SESSION['school.login']['language']][$request_item['status_flag']], 'status'=>1);
                        // $invoice_result[] = $paid_detail;
                        // }
                        // }
                        
                        if (empty ( $list_item ['paid_date'] )) {
                            $paid_detail = array ();
                            if ($list_item ['is_recieved'] < 0) {
                                $paid_detail = array (
                                        'invoice_year_month' => $list_item ['invoice_year_month'],
                                        'reason' => ConstantsModel::$zengin_status [$_SESSION ['school.login'] ['language']] [$list_item ['is_recieved']],
                                        'status' => 1 
                                );
                            } else {
                                $paid_detail = array (
                                        'invoice_year_month' => $list_item ['invoice_year_month'],
                                        'reason' => ConstantsModel::$payment_result [$_SESSION ['school.login'] ['language']] ['1'],
                                        'status' => 1 
                                );
                            }
                            $invoice_result [] = $paid_detail;
                        }
                    } else {
                        // 現金または振込の場合
                        if ($list_item ['paid_date'] == null) {
                            $paid_detail = array ();
                            $paid_detail = array (
                                    'invoice_year_month' => $list_item ['invoice_year_month'],
                                    'reason' => ConstantsModel::$payment_result [$_SESSION ['school.login'] ['language']] ['1'],
                                    'status' => 1 
                            );
                            $invoice_result [] = $paid_detail;
                        }
                    }
                } else {
                    // まだ支払期限が来ていない
                    $paid_detail = array ();
                    $paid_detail = array (
                            'invoice_year_month' => $list_item ['invoice_year_month'],
                            'reason' => ConstantsModel::$payment_result [$_SESSION ['school.login'] ['language']] ['3'],
                            'status' => 0 
                    );
                    $invoice_result [] = $paid_detail;
                }
            }
            if (count ( $invoice_result ) < 1) {
                if ($read_flag) {
                    $paid_detail = array ();
                    $paid_detail = array (
                            'invoice_year_month' => $list_item ['invoice_year_month'],
                            'reason' => ConstantsModel::$payment_result [$_SESSION ['school.login'] ['language']] ['2'],
                            'status' => 0 
                    );
                    $invoice_result [] = $paid_detail;
                } else {
                    // まだ支払期限が来ていない
                    $paid_detail = array ();
                    $paid_detail = array (
                            'invoice_year_month' => $list_item ['invoice_year_month'],
                            'reason' => ConstantsModel::$payment_result [$_SESSION ['school.login'] ['language']] ['3'],
                            'status' => 0 
                    );
                    $invoice_result [] = $paid_detail;
                }
            }
        } else {
            // 請求履歴なし
            $paid_detail = array ();
            $paid_detail = array (
                    'invoice_year_month' => null,
                    'reason' => ConstantsModel::$payment_result [$_SESSION ['school.login'] ['language']] ['4'],
                    'status' => 0 
            );
            $invoice_result [] = $paid_detail;
        }
        return $invoice_result;
    }
    /**
     * 最近の請求書年月取得
     *
     * @param unknown $parent            
     * @return multitype:unknown
     */
    public function getMaxInvoceDate($session) {
        $ret = array ();
        $bind = array ();
        
        $sql = " SELECT MAX(a.invoice_year_month)";
        // $sql .= " COUNT(*) as cnt_entry, COUNT(a.workflow_status > 0 or null) as cnt_confirm, COUNT(a.workflow_status > 1 or null) as cnt_send, COUNT(a.workflow_status = 31 or null) as cnt_complete, ";
        // $sql .= " MAX(a.register_date) as register_date, COUNT(DISTINCT a.parent_id) as cnt_invoice, COUNT(DISTINCT a.parent_id, c.invoice_type = 2 or null) as cnt_richo, COUNT(DISTINCT a.parent_id) - COUNT(DISTINCT a.parent_id, c.invoice_type = 2 or null) as cnt_other ";
        $sql .= " FROM invoice_header as a ";
        // $sql .= " LEFT JOIN parent c ON ( a.parent_id = c.id )";
        $sql .= " WHERE a.active_flag = 1 ";
        $sql .= " AND a.delete_date is NULL ";
        
        if (! empty ( $session ['id'] )) {
            $sql .= " AND a.pschool_id = ? ";
            $bind [] = $session ['id'];
        }
        // if (!empty($request['invoice_year_month'])){
        // $sql .= " AND a.invoice_year_month = ? ";
        // $bind[] = $request['invoice_year_month'];
        // }
        
        // $sql .= " GROUP BY a.invoice_year_month ";
        // $sql .= " ORDER BY a.invoice_year_month ";
        
        $res = $this->fetch ( $sql, $bind );
        return $res;
    }

    public function getListNoticeByInvoice($pschool_id, $period = null)
    {
        $bind = array();
        $sql = "SELECT  i.id as invoice_header_id, i.due_date as date,YEAR(i.due_date) as year, MONTH(i.due_date) as month, i.view_date, p.parent_name, 'activity' as notice_type
                FROM invoice_header i
                INNER JOIN parent p ON i.parent_id = p.id
                WHERE i.due_date <= CURRENT_DATE() AND i.is_recieved = 0 AND i.active_flag = 1 AND i.delete_date IS NULL AND i.pschool_id = ? ";
        $bind[] = $pschool_id;
        if (!is_null($period)) {
            $sql .= " AND i.due_date  >= DATE_ADD(NOW(),INTERVAL -? DAY) ";
            $bind [] = $period;
        }
        $sql .= "ORDER BY date ASC";
        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }

    // new function declare here , other will refactor after everything done

    public function countInvoice($pschool_id, $invoice_year_month =null){
        $sql = "SELECT  i.invoice_year_month, COUNT(*) as cnt_entry, COUNT(i.workflow_status > 0 or NULL) as cnt_confirm, ";
        $sql.= " COUNT(i.workflow_status > 1 or NULL) as cnt_send, COUNT(i.workflow_status = 31 or NULL) as cnt_complete,
                i.register_date";
        if($invoice_year_month == null) {
            $sql.= " , COUNT( i.invoice_type = 1 OR NULL ) as cnt_genkin, ";
            $sql.= " COUNT( i.invoice_type = 2 OR NULL  ) as cnt_richo, ";
            $sql.= " COUNT( i.invoice_type > 2 OR NULL ) as cnt_other, ";
            $sql.= " COUNT( id ) as cnt_all ";
        }
        $sql.= " FROM invoice_header i ";
        $sql.= " WHERE i.pschool_id = ".$pschool_id." ";
        $sql.= " AND delete_date IS NULL ";
        if($invoice_year_month != null){
            $sql.= " AND invoice_year_month='".$invoice_year_month."' ";
        }else{
            $sql.= " GROUP BY invoice_year_month";
        }
        return $this->fetchAll($sql);
    }
    public function getListInvoiceByMonth($pschool_id , $invoice_year_month , $arr_invoice_type = null){

        $sql = "SELECT ih.id, ih.pschool_id, ih.parent_id,ih.discount_price, ih.amount_display_type, ";
        $sql.= " ih.amount, ih.is_established, ih.invoice_year_month, ih.due_date, ";
        $sql.= " ih.sales_tax_rate, ih.workflow_status, ih.active_flag, IF(ih.update_date IS NULL , ih.register_date , ih.update_date ) register_date, ";
        $sql.= " p.parent_name, p.name_kana as parent_name_kana, p.mail_infomation, p.check_register, ";
        $sql.= " ih.invoice_type,ih.error_code ";
        $sql.= " FROM invoice_header ih ";
        $sql.= " INNER JOIN parent p ON ih.parent_id = p.id ";
        $sql.= " WHERE ih.pschool_id = ".$pschool_id;

        //invoice type filter
        if(!empty($arr_invoice_type)){
           $filter = implode(",",$arr_invoice_type);
           $sql.= " AND ih.invoice_type IN (".$filter.") ";
        }
        //

        $sql.= " AND ih.invoice_year_month = '".$invoice_year_month."' ";
        $sql.= " AND ih.delete_date IS NULL AND ih.active_flag = 1 AND (ih.is_nyukin = 0 OR ih.is_nyukin IS NULL)";
        $sql.= " AND p.delete_date IS NULL ";

        $res = $this->fetchAll($sql);
        foreach ($res as $k=>$v){
            $res[$k]['student_list'] = $this->getStudentByParent($v['parent_id']);
        }
        return $res;
    }
    public function getStudentByParent($parent_id){

        $sql = " SELECT s.*, mst.name AS student_type_name FROM student s";
        $sql.= " INNER JOIN m_student_type mst ON mst.id = s.m_student_type_id";
        $sql.= " WHERE s.parent_id =".$parent_id." ";
        $sql.= " AND s.delete_date IS NULL ";
        $sql.= " AND s.active_flag = 1 AND (s.resign_date > NOW() OR s.resign_date IS NULL) ";

        return $this->fetchAll($sql);
    }
    /**
     * @param $pschool_id
     * @param $invoice_year_month
     *
     * return list of parents, each contain a list of student that set payment monthly
     */
    public function getListParentMonthly($pschool_id,$invoice_year_month){

        $bind = array(
                $invoice_year_month,
                $invoice_year_month,
                $invoice_year_month,
                $pschool_id
        );
        $sql_class = "SELECT ? as invoice_year_month, class.pschool_id as pschool_id, parent.id as parent_id , parent.parent_name, parent.mail_infomation, parent.invoice_type,
                       s_class.student_id as student_id ,student.student_name, class.id as class_id, class.class_name, s_class.payment_method,
                       cfp.fee_plan_name,cfp.fee as amount
                FROM class 
                LEFT JOIN student_class s_class ON class.id = s_class.class_id
                LEFT JOIN student ON student.id = s_class.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                LEFT JOIN class_fee_plan cfp ON cfp.class_id = class.id AND s_class.plan_id = cfp.id
                WHERE class.delete_date IS NULL 
                AND s_class.delete_date IS NULL 
                AND student.active_flag = 1 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL
                AND (class.start_date IS NOT NULL AND SUBSTRING(class.start_date, 1, 7) <= ? )
                AND (class.close_date IS NULL OR SUBSTRING(class.close_date, 1, 7) >= ? ) 
                AND s_class.number_of_payment = 99
                AND class.pschool_id = ?";
        $class_invoice = $this->fetchAll($sql_class, $bind);

        return $class_invoice;
    }
    public function getListParentEvent($pschool_id,$invoice_year_month){
        $bind = array(
                $invoice_year_month,
                $invoice_year_month,
                $pschool_id
        );
        $sql_event = "SELECT ? as invoice_year_month, course.pschool_id as pschool_id, parent.id as parent_id , parent.parent_name, parent.mail_infomation, parent.invoice_type,
                      scr.student_id as student_id ,student.student_name, course.id as course_id, course.course_title , scr.payment_method,
                      cfp.fee as amount, cfp.fee_plan_name , SUBSTRING(course.start_date, 1, 7) as start_month
                FROM course 
                LEFT JOIN student_course_rel scr ON course.id = scr.course_id
                LEFT JOIN course_fee_plan cfp ON scr.plan_id = cfp.id
                LEFT JOIN student ON student.id = scr.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                WHERE course.delete_date IS NULL 
                AND course.active_flag = 1
                AND scr.delete_date IS NULL 
                AND student.active_flag = 1 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL
                AND (course.start_date IS NOT NULL AND SUBSTRING(course.start_date, 1, 7) = ? )
                AND course.pschool_id = ?";

        $event_invoice = $this->fetchAll($sql_event, $bind);
        return $event_invoice;
    }

    public function getListParentProgram($pschool_id,$invoice_year_month){
        $bind = array(
                $invoice_year_month,
                $invoice_year_month,
                $pschool_id
        );
        $sql_program = "SELECT ? as invoice_year_month, program.pschool_id as pschool_id, parent.id as parent_id , parent.parent_name, parent.mail_infomation, parent.invoice_type,
                      sp.student_id as student_id ,student.student_name, program.id as program_id, program.program_name , sp.payment_method,
                      pfp.fee as amount, pfp.fee_plan_name , SUBSTRING(program.start_date, 1, 7) as start_month
                FROM program 
                LEFT JOIN student_program sp ON program.id = sp.program_id
                LEFT JOIN program_fee_plan pfp ON sp.plan_id = pfp.id
                LEFT JOIN student ON student.id = sp.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                WHERE program.delete_date IS NULL 
                AND program.active_flag = 1
                AND sp.active_flag = 1
                AND sp.delete_date IS NULL 
                AND student.active_flag = 1 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL
                AND (program.start_date IS NOT NULL AND SUBSTRING(program.start_date, 1, 7) = ? )
                AND program.pschool_id = ?";

        $program_invoice = $this->fetchAll($sql_program, $bind);
        return $program_invoice;
    }


    public function getListParentSchedule($pschool_id,$invoice_year_month,$schedule_month){

        $first = date('m-d',strtotime($schedule_month."-01"));
        $last = date('m-t',strtotime($schedule_month."-01"));

        $bind = array(
            $invoice_year_month,
            $schedule_month,
            $schedule_month,
            $schedule_month,
            $pschool_id,
            $first,
            $last,

        );
        $sql = "SELECT ? as invoice_year_month,class.pschool_id , parent.id as parent_id , parent.parent_name,parent.mail_infomation, parent.invoice_type,
                       s_class.student_id as student_id ,student.student_name, class.id as class_id, class.class_name, s_class.payment_method,
                       cps.id as schedule_id, cps.schedule_date, cps.schedule_fee as amount, ? as schedule_month
                FROM class 
                LEFT JOIN student_class s_class ON class.id = s_class.class_id
                LEFT JOIN class_payment_schedule cps ON cps.student_class_id = s_class.id
                LEFT JOIN student ON student.id = s_class.student_id
                LEFT JOIN parent ON parent.id = student.parent_id
                WHERE class.delete_date IS NULL 
                AND s_class.delete_date IS NULL 
                AND student.active_flag = 1 
                AND (student.resign_date IS NULL OR student.resign_date >= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date)) 
                AND student.delete_date IS NULL 
                AND parent.delete_date IS NULL
                AND (class.start_date IS NOT NULL AND SUBSTRING(class.start_date, 1, 7) <= ? )
                AND (class.close_date IS NULL OR SUBSTRING(class.close_date, 1, 7) >= ? ) 
                AND (SUBSTRING(class.start_date, 6, 5) <= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date))
                AND (class.close_date IS NULL OR class.close_date >= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date))
                AND class.pschool_id = ?
                AND s_class.number_of_payment != 99
                AND (s_class.start_date IS NOT NULL AND s_class.start_date <= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date)) 
                AND (s_class.end_date IS NULL OR s_class.end_date >= concat(DATE_FORMAT(NOW(), '%Y'),'-',cps.schedule_date)) 
                AND cps.schedule_date IS NOT NULL 
                AND cps.schedule_fee IS NOT NULL
                AND cps.schedule_date>= ? AND cps.schedule_date<= ? ";

        return $this->fetchAll($sql, $bind);
    }

    /**
     * @param $list_parent
     * check if parent_id is exist in table invoice_header or not
     * if exist then check if item is exist in table invoice_item
     * return list with header_flag = 1 => this record will insert to header and item
     * header_flag = 0 => only insert to item cause header is already exists
     */
    public function processListInvoiceHeader($pschool,$list_parent){

        foreach($list_parent as $k=>$v){

            // default header_flag = 1 and header_id =null;
            $list_parent[$k]['header_flag'] = 1;
            $list_parent[$k]['header_id'] = null;

            //generate item name for japanese
            $split = explode('-', $v['invoice_year_month']);
            $item_name = $split[0] . "年" . $split[1] . "月分 ";
            $item_name.= $v['class_name']. " ". $v['student_name'];
            $list_parent[$k]['item_name'] = $item_name;
            $v['item_name'] = $item_name;

            //check this parent_id is exist or not
            $header = $this->isInvoiceHeaderExist($v);
            if($header){
                // mean this parent_id is already exists in invoice_header so get this header_id
                $list_parent[$k]['header_id'] = $header['id'];
                // check item is exists or not
                if($this->isInvoiceItemExist($v)){
                    // item existed => unset from list
                    unset($list_parent[$k]);
                    break 1;
                }else{
                    // item is not exist => set header_flag =0 : save item, not the header
                    $list_parent[$k]['header_flag'] = 0;
                }
            }

            if($list_parent[$k]['header_flag']==1){
                $list_parent[$k]['header_id'] = $this->saveInvoiceHeader($pschool,$list_parent[$k]);
            }
            //TODO save item to invoice_item
            $item_id = $this->saveInvoiceItem($pschool,$list_parent[$k]);

            //TODO get list adjust parent
            // check if header is exists => adjust for parent exists so do not insert
            if(!$header){
                $parent_adjust_item = $this->saveParentAdjustItem($list_parent[$k]);
            }
            //TODO get list adjust class

            //TODO get list adjust event

            //TODO get list adjust program
            $this->updateAmountInvoiceHeader($list_parent[$k]['header_id']);
        }
    }
    public function isInvoiceHeaderExist($header){
        $header_cond = array(
                'pschool_id' =>$header['pschool_id'],
                'parent_id'  => $header["parent_id"],
                'invoice_year_month' => $header['invoice_year_month'],
        );

        $headerExist = $this->getActiveList($header_cond);
        if(!empty($headerExist)){
            return $headerExist[0];
        }else{
            return false;
        }
    }
    public function isInvoiceItemExist($item){
        $item_cond = array(
                'pschool_id' =>$item['pschool_id'],
                'parent_id'  => $item["parent_id"],
                'student_id' =>$item['student_id'],
                'class_id'   => $item['class_id'],
                'item_name'  => $item['item_name'],
        );
        $invoiceItemTable = InvoiceItemTable::getInstance();
        $itemExist = $invoiceItemTable->getActiveList($item_cond);
        if(!empty($itemExist)){
            return true;
        }else{
            return false;
        }
    }
    public function saveInvoiceHeader($pschool,$header){
        $row = array(
                "pschool_id" => $pschool['id'],
                "parent_id" => $header["parent_id"],
                "discount_price" => "0",
                "is_established" => "0",
                "mail_announce" => empty($header["mail_infomation"]) ? "0" : "1",
                "is_requested" => "0",
                "is_recieved" => "0",
                "amount_display_type" => $pschool['amount_display_type'],
                "sales_tax_rate" => $pschool['sales_tax_rate'],
                "active_flag" => 1,
                "invoice_type" => $header['invoice_type'],
                "invoice_year_month" => $header['invoice_year_month'],
                //"due_date" => $header['item_due_date'],
                "due_date" => '2017-09-05',
                "register_admin" => $pschool['id']
        );
        $header_id = $this->save($row);
        return $header_id;
    }
    public function saveInvoiceItem($pschool,$item){
        $invoiceItemTable = InvoiceItemTable::getInstance();

        $monthly_billing = 0;
        if(isset($item['schedule_date'])){
            $monthly_billing = 0; // 0 means this have set schedule_date
        }else{
            $monthly_billing = 1; // 1 means payment monthy
        }

        $row = array(
                'pschool_id'=>$pschool['id'],
                'invoice_id'=>$item['header_id'],
                'parent_id'=>$item['parent_id'],
                'student_id'=>$item['student_id'],
                'class_id'  => $item['class_id'],
                'item_name'  => $item['item_name'],
                'unit_price' => $item['amount'],
                'monthly_billing' => $monthly_billing,
                //'payment_method' => $item['payment_method'],
                'payment_method' => $item['invoice_type'],
                //'due_date' =>$item['item_due_date'],
                'due_date' =>'2017-09-05',
                'active_flag' => 1,
                'except_flag' => 0,
                'register_date' => date('Y-m-d H:i:s'),
                'register_admin' =>  $pschool['id']

        );
        $item_id = $invoiceItemTable->save($row);
        return $item_id;
    }
    public function updateAmountInvoiceHeader($header_id){
        $sql = "SELECT item.invoice_id, (CASE WHEN sum(item.unit_price) IS NOT NULL THEN sum(item.unit_price)
									WHEN sum(item.unit_price) IS NULL THEN 0
                            END
                            ) AS sum_unit_price  " .
                "FROM invoice_item AS item " .
                "WHERE item.invoice_id = ? " .
                "AND item.except_flag = 0 " .
                "AND item.delete_date IS NULL " .
                "GROUP BY item.invoice_id" ;

        $bind = array(
                $header_id,
        );
        $res = $this->fetch($sql, $bind);
        if(empty($res['sum_unit_price'])){
            $amount = 0;
        }else{
            $amount = $res['sum_unit_price'];
        }

        $sql_update = " UPDATE invoice_header 
                        SET amount = ?
                        WHERE id = ? ";
        $bind_update = array(
                $amount,
                $header_id
        );

        $this->execute($sql_update, $bind_update);
    }
    public function getDataParentStudent($header_id){

        $bind = array(
            $header_id
        );
        $sql = " SELECT ih.id, ih.is_nyukin, ih.pschool_id, ih.parent_id, parent.mail_infomation, parent.parent_name, parent.name_kana as parent_name_kana, IFNULL(parent.parent_mailaddress1, parent.parent_mailaddress2) as parent_mail, ";
        $sql.= " parent.address, parent.zip_code1, parent.zip_code2, parent.zip_code, parent.phone_no, parent.building, ";
        $sql.= " ih.invoice_type, ih.invoice_year_month, ih.amount, ih.is_established , ih.active_flag, ih.error_code ,";
        $sql.= " ih.is_requested, ih.is_recieved, ih.mail_announce,ih.amount_display_type, ih.sales_tax_rate, ih.workflow_status, ih.due_date, ih.paid_date, ";
        $sql.= " m_pref.name as p_pref_name, m_city.name as p_city_name ";
        $sql.= " FROM invoice_header ih ";
        $sql.= " LEFT JOIN parent ON ih.parent_id = parent.id ";
        $sql.= " LEFT JOIN m_pref ON m_pref.id = parent.pref_id ";
        $sql.= " LEFT JOIN m_city ON m_city.id = parent.city_id ";
        $sql.= " WHERE ih.id = ? ";
        $sql.= " AND ih.active_flag = 1 ";
        $sql.= " AND ih.delete_date IS NULL ";
        $sql.= " AND parent.delete_date IS NULL ";

        $res = $this->fetch($sql,$bind);

        // get list student of parent
        if(!empty($res)){
            $res['student_list'] = $this->getStudentByParent($res['parent_id']);
        }

        //get school_data
        $school_data = array();
        $school = PschoolTable::getInstance()->loadWithLoginAccount($res['pschool_id']);
        if ($school) {
            $school_data = array (
                'school_name'              => $school['name'],
                'school_address'           => $school['address'],
                'school_building'          => $school['building'],
                'pref_name'                => $school['pref_name'],
                'city_name'                => $school['city_name'],
                'school_daihyou'           => $school['daihyou'],
                'school_mail'              => $school['mailaddress'],
                'school_tel'               => $school['tel'],
                'school_zipcode_1'         => $school['zip_code1'],
                'school_zipcode_2'         => $school['zip_code2'],
                'school_zipcode'           => $school['zip_code'],
                'school_proviso'           => isset($school['proviso']) ? $school['proviso'] : null,
                'kakuin_path'              => $school['kakuin_path'],
                'lang_code'                => $school['language'],
                'school_official_position' => $school['official_position'],
            );
        }

        if(empty($school_data["school_zipcode_1"]) || empty($school_data["school_zipcode_2"]) ){
            $school_data["school_zipcode_1"] = substr($school_data["school_zipcode"],0,3);
            $school_data["school_zipcode_2"] = substr($school_data["school_zipcode"],3,4);
        }

        //add payment method name base on parent
        $res["payment_method_name"] = Constants::$invoice_type[$school_data['lang_code']][$res['invoice_type']];
        // merge and return
        $res = array_merge($res,$school_data);

        //calculator tax
        if ($res['amount_display_type'] == 0) {
            $res['tax_price'] = floor($res['amount'] * ($res['sales_tax_rate'] * 100) / (($res['sales_tax_rate'] * 100) + 100));
        } else {
            $res['tax_price'] = floor($res['amount'] * $res['sales_tax_rate']);
            $res['amount'] = $res['amount'] + $res['tax_price'];
        }

        $res = $this->getClassCourseInfo($res);

        return $res;
    }

    /**
     * @param $parent_data
     * get list adjust item of parent by parent_id and invoice_year_month
     * then insert to invoice_item
     */
    public function saveParentAdjustItem($parent_data){
        $bind = array();
        $bind[] = $parent_data['pschool_id'];
        $bind[] = $parent_data['parent_id'];
        $bind[] = substr($parent_data['invoice_year_month'],5,2);

        $sql  = " SELECT RP.*, IAN.name, parent.invoice_type ";
        $sql .= " FROM routine_payment AS RP ";
        $sql .= " INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= " ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= " INNER JOIN parent ON parent.id = RP.data_id ";
        $sql .= " WHERE RP.delete_date IS NULL ";
        $sql .= " AND RP.active_flag = 1 ";
        $sql .= " AND RP.pschool_id = ? ";
        $sql .= " AND RP.data_div = 3 ";
        $sql .= " AND RP.data_id = ? ";
        $sql .= " AND (RP.month = ? OR RP.month = 99) ";

        $adjust = $this->fetchAll($sql,$bind);
        if(count($adjust)>0){
            foreach ($adjust as $item ){
                $row = array();
                $row['pschool_id']     = $parent_data['pschool_id'];
                $row['invoice_id']     = $parent_data['header_id'];
                $row['parent_id']      = $parent_data['parent_id'];
                $row['class_id']       = null;
                $row['course_id']      = null;
                $row['item_name']      = $item['name'];
                $row['unit_price']     = $item['adjust_fee'];
                $row['active_flag']    = 1;
                $row['due_date']       = '2017-09-05';
                $row['payment_method'] = $parent_data['invoice_type'];
                $row['register_date']  = date('Y-m-d H:i:s');
                $row['register_admin'] = $parent_data['pschool_id'];
                $row['invoice_adjust_name_id'] = $item['invoice_adjust_name_id'];
                $row['program_id']     = null;

                InvoiceItemTable::getInstance()->insertRow($row);
            }
        }
    }

    public function getListRicohTransDownload($pschool_id, $invoice_year_month = null, $invoice_type){

        if(!empty($invoice_year_month)){
            $bind = array(
                    $invoice_year_month
            );
        }

        $bind[] =   $invoice_type;
        $bind[] =   $pschool_id;


        $sql = "SELECT " .
                " ih.id " .
                ", ih.parent_id " .
                ", ih.is_nyukin " .
                ", c.parent_name " .
                ", c.name_kana AS parent_name_kana " .
                ", c.mail_infomation " .
                ", c.invoice_type " .
                ", ih.invoice_year_month " .
                ", ih.amount " .
                ", ih.is_established " .
                ", ih.is_requested " .
                ", ih.is_recieved " .
                ", ih.mail_announce " .
                ", ih.is_mail_announced " .
                ", ih.amount_display_type " .
                ", ih.sales_tax_rate " .
                ", ih.active_flag " .
                ", ih.workflow_status " .
                ", ih.register_date " .
                ", e.processing_filename " .
                "FROM " .
                "invoice_header AS ih " .
                "INNER JOIN invoice_item AS b ON (ih.id = b.invoice_id AND b.student_id IS NOT NULL) " .
                "INNER JOIN parent AS c ON (ih.parent_id = c.id) " .
                "INNER JOIN student AS d ON (b.student_id = d.id) " .
                "LEFT JOIN invoice_request e ON e.invoice_header_id = ih.id " .
                "WHERE e.delete_date IS NULL " .
//                "AND ih.workflow_status = 11" ;
                "AND ih.workflow_status = 1" ;
        if(!empty($invoice_year_month)) {
            $sql.=  " AND ih.invoice_year_month = ? ";
        }
        $sql.=  " AND ih.invoice_type = ? ".
                " AND ih.pschool_id = ? ".
                " AND ih.delete_date IS NULL " .
                " AND d.active_flag = 1 " .
                " AND d.delete_date IS NULL " .
                " GROUP BY ih.id";
        $res = $this->fetchAll($sql,$bind);

        foreach($res as $k => $v){

                $res[$k] = $this->getClassCourseInfo($res[$k]);
                $res[$k]['student_list'] = $this->getStudentByParent($v['parent_id']);
        }
        return $res;
    }
    /**
     * 対象invoice_headerテーブルに紐づくinvoice_item取得
     *
     * @param unknown $pschool_id
     * @param unknown $invoice_header_id
     */
    public function getInvoiceHeader_Item($pschool_id, $invoice_header_id) {
        $bind = array ();

        $sql = " SELECT * ";
        $sql .= " FROM ";
        $sql .= "   (SELECT IH.*, PA.parent_name, PA.invoice_type as parent_invoice_type ";
        $sql .= "   FROM invoice_header AS IH ";
        $sql .= "   INNER JOIN  parent AS PA ";
        $sql .= "   ON IH.parent_id = PA.id ";
        $sql .= "   WHERE IH.id = ? ";
        $bind [] = $invoice_header_id;
        $sql .= "   AND IH.pschool_id = ?) AS HEAD ";
        $bind [] = $pschool_id;
        $sql .= " INNER JOIN ";
        $sql .= "   (SELECT II.invoice_id, II.student_id, ST.student_name, II.school_year, II.school_category, ST.student_no ";
        $sql .= "   FROM invoice_item AS II ";
        $sql .= "   INNER JOIN student AS ST ";
        $sql .= "   ON II.student_id = ST.id ";
        $sql .= "   WHERE II.invoice_id = ? ";
        $bind [] = $invoice_header_id;
        $sql .= "   GROUP BY II.student_id ) AS ITEM ";
        $sql .= " ON HEAD.id = ITEM.invoice_id ";

        $res = $this->fetchAll ( $sql, $bind );

        foreach($res as $k => $v){

            $res[$k]['entry_name']  = "";
            if($v['is_nyukin'] == 1){
                $entry_sql = "SELECT course_title FROM course WHERE id = 
                                      (SELECT course_id FROM invoice_item WHERE invoice_id = ".$v['id']." LIMIT 1)";
                $course_name = $this->fetch($entry_sql);
                if($course_name){
                    $res[$k]['entry_name']  = $course_name['course_title']." イベント";
                }
            }elseif ($v['is_nyukin'] == 2){
                $entry_sql = "SELECT program_name FROM program WHERE id = 
                                      (SELECT program_id FROM invoice_item WHERE invoice_id = ".$v['id']." LIMIT 1)";
                $program_name = $this->fetch($entry_sql);
                if($program_name){
                    $res[$k]['entry_name']  = $program_name['program_name']." プログラム";
                }
            }
        }
        return json_decode ( json_encode ( $res ), true );
    }

    /**
     * Get deposit data
     * @param array $filters
     * @param bool $singleResult
     * @return array|null
     */
    public function getDeposits($filters = array(), $singleResult = false) {
        $binds = [];
        $sql = "SELECT ih.id, ih.pschool_id, ih.parent_id,ih.discount_price, ih.amount_display_type, ";
        $sql .= " ih.amount, ih.is_established, ih.invoice_year_month, ih.due_date, ih.is_nyukin, ";
        $sql .= " ih.sales_tax_rate, ih.workflow_status, ih.active_flag, ih.register_date, ";
        $sql .= " p.parent_name, p.name_kana as parent_name_kana, p.mail_infomation, p.parent_mailaddress1, ";
        $sql .= " ih.invoice_type, ih.deposit_invoice_type, ih.paid_date, ih.is_mail_announced, ih.announced_date, ih.proviso, ps.mailaddress as school_mailaddress, ";
        $sql .= " ih.receipt_number , ih.receipt_count, ih.is_recieved, ih.deposit_reminder_date ";
        $sql .= " FROM invoice_header ih ";

        $parent_sub_query = "SELECT p.*";
        $parent_sub_query .= " FROM parent p";
        $parent_sub_query .= " INNER JOIN student s ON s.parent_id = p.id";
        $parent_sub_query .= " WHERE 1";

        $invoice_item_sub_query = "SELECT it.*";
        $invoice_item_sub_query .= " FROM invoice_item it";
        $invoice_item_sub_query .= " WHERE 1";

        // 会員・請求先の漢字・カナ
        if (isset($filters['name_furigana'])) {
            $binds[] = '%'. $filters['name_furigana'] . '%';
            $binds[] = '%'. $filters['name_furigana'] . '%';
            $binds[] = '%'. $filters['name_furigana'] . '%';
            $binds[] = '%'. $filters['name_furigana'] . '%';
            $parent_sub_query .= " AND (p.parent_name LIKE ? OR p.name_kana LIKE ? OR s.student_name LIKE ? OR s.student_name_kana LIKE ?)";
        }

        // 会員番号
        if (isset($filters['student_no'])) {
            $binds[] = $filters['student_no'];
            $parent_sub_query .= " AND s.student_no = ?";
        }

        // 会員種別
        if (isset($filters['student_type_ids']) && !empty($filters['student_type_ids']) ) {
            $parent_sub_query .= " AND s.m_student_type_id IN (". implode(',', $filters['student_type_ids']) .")";
        }

        // プラン
        if (isset($filters['class_id'])) {
            $binds[] = $filters['class_id'];
            $invoice_item_sub_query .= " AND it.class_id = ?";
        }

        // イベント
        if (isset($filters['course_id'])) {
            $binds[] = $filters['course_id'];
            $invoice_item_sub_query .= " AND it.course_id = ?";
        }

        // プログラム
        if (isset($filters['program_id'])) {
            $binds[] = $filters['program_id'];
            $invoice_item_sub_query .= " AND it.program_id = ?";
        }
        $parent_sub_query .= " GROUP BY p.id";
        $invoice_item_sub_query .= " GROUP BY it.invoice_id";

        $sql .= " INNER JOIN (". $parent_sub_query .") p ON ih.parent_id = p.id ";
        $sql .= " INNER JOIN (". $invoice_item_sub_query .") it ON it.invoice_id = ih.id ";
        $sql .= " INNER JOIN pschool ps ON ps.id = ih.pschool_id ";
        $sql .= " WHERE ih.pschool_id = " . $filters['pschool_id'];
        $sql .= " AND ih.delete_date IS NULL AND ih.active_flag = 1 ";
        $sql .= " AND p.delete_date IS NULL ";

        // Search by invoice header id
        if (isset($filters['id'])) {
            $binds[] = $filters['id'];
            $sql .= " AND ih.id = ?";
        }

        // Search by list invoice header id
        if (isset($filters['invoice_header_ids']) && $filters['invoice_header_ids']) {
            $sql .= " AND ih.id IN (". implode(',', $filters['invoice_header_ids']) .")";
        }

        // 請求月
        if (isset($filters['invoice_year_month'])) {
            $binds[] = $filters['invoice_year_month'];
            $sql .= " AND ih.invoice_year_month = ?";
        }
        // 請求月からしか検索しない Only input from
        if (isset($filters['invoice_year_month_from']) && !isset($filters['invoice_year_month_to'])) {
            $binds[] = $filters['invoice_year_month_from'];
            $sql .= " AND ih.invoice_year_month >= ?";
        }
        // 請求月までしか検索しない Only input to
        if (isset($filters['invoice_year_month_to']) && !isset($filters['invoice_year_month_from'])) {
            $binds[] = $filters['invoice_year_month_to'];
            $sql .= " AND ih.invoice_year_month <= ?";
        }
        //payment_date_from input
        if (isset($filters['payment_date_from'])) {
            $binds[] = $filters['payment_date_from'];
            $sql .= " AND SUBSTR(ih.paid_date,1,10) >= ?";
        }

        //payment_date_to input
        if (isset($filters['payment_date_to'])) {
            $binds[] = $filters['payment_date_to'];
            $sql .= " AND SUBSTR(ih.paid_date,1,10) <= ?";
        }

        // 請求月から〇〇までの範囲で検索 Search range from to
        if (isset($filters['invoice_year_month_from']) && isset($filters['invoice_year_month_to'])) {
            $binds[] = $filters['invoice_year_month_from'];
            $binds[] = $filters['invoice_year_month_to'];
            $sql .= " AND ih.invoice_year_month >= ? AND ih.invoice_year_month <= ?";
        }


//        if (isset($filters['invoice_type_ids'])) {
//            $sql .= " AND ih.invoice_type IN (" . implode(',', $filters['invoice_type_ids']) . ")";
//        }
        // 支払方法
        if (isset($filters['invoice_type_ids']) && !empty($filters['invoice_type_ids'])) {
            if (array_get($filters, 'filter_by_invoice_type') == true && array_get($filters, 'filter_by_deposit_invoice_type') == true) {
                $sql .= " AND (ih.invoice_type IN (" . implode(',', $filters['invoice_type_ids']) . ")
                         OR ih.deposit_invoice_type IN (" . implode(',', $filters['invoice_type_ids']) . ")
                      )";
            } elseif (array_get($filters, 'filter_by_invoice_type') == true) {
                $sql .= " AND ih.invoice_type IN (" . implode(',', $filters['invoice_type_ids']) . ")";
            } elseif (array_get($filters, 'filter_by_deposit_invoice_type') == true) {
                $sql .= " AND ih.deposit_invoice_type IN (" . implode(',', $filters['invoice_type_ids']) . ")";
            }
        }

        // 入金状態
        if (isset($filters['workflow_status'])) {
            $sql .= " AND ih.workflow_status IN (". implode(',', $filters['workflow_status']) .")";
        } else {
            $sql .= " AND (ih.workflow_status = 11 OR ih.workflow_status = 21 OR ih.workflow_status = 29 OR ih.workflow_status = 31)";
        }

        if(!empty($filters['order_by'])){
            $sql .= "ORDER BY ".$filters['order_by']." ASC";
        }else{
            $sql .= "ORDER BY p.name_kana ASC";
        }


        if ($singleResult) {
            $res = $this->fetch($sql, $binds);
            $res['student_list'] = $this->getStudentByParent($res['parent_id']);
        } else {
            $res = $this->fetchAll($sql, $binds);
            foreach ($res as $k=>$v){
                $res[$k]['student_list'] = $this->getStudentByParent($v['parent_id']);
            }
        }

        return $res;
    }

    public function getParentByInvoiceHeader($ids) {
        $sql = " SELECT p.* ";
        $sql .= " FROM invoice_header AS ih ";
        $sql .= " INNER JOIN parent AS p ON p.id = ih.parent_id ";

        foreach ( $ids as $ii => $id ) {
            if ($ii == 0) {
                $sql .= " WHERE ih.id = ? ";
            } else {
                $sql .= " OR ih.id = ? ";
            }
        }

        $sql .= " GROUP BY ih.parent_id";
        return $this->fetchAll($sql, $ids);
    }

    public function updateMailAnnouce($parent_id,$mail_information){

        $sql = "UPDATE invoice_header 
                SET mail_announce = ? 
                WHERE parent_id = ?
                AND delete_date IS NULL ";

        $bind = arraY(
            $mail_information,
            $parent_id
        );

        $this->execute($sql,$bind);
    }

    public function updateInvoiceMailAnnouce($invoice_id){

        $invoice = $this->load($invoice_id);
        $sql = "UPDATE invoice_header a
                SET mail_announce = (SELECT mail_infomation FROM parent WHERE id = ? )
                WHERE id = ?
                AND delete_date IS NULL ";

        $bind[] = $invoice['parent_id'];
        $bind[] = $invoice_id;

        $this->execute($sql,$bind);
    }

    public function getClassCourseInfo($invoice){

        //if is_nyukin -> get event and program name base on invoice_item
        if($invoice['is_nyukin'] !=0){

            $item = InvoiceItemTable::getInstance()->getActiveList(array('invoice_id'=>$invoice['id']));
            $item = $item[0];

            if($item['course_id'] != null){

                //get mail message : if null -> create record
                $mail_message = MailMessageTable::getInstance()->getActiveList(array('type'=>3,'relative_ID'=>$item['course_id'],'student_id'=>$item['student_id']));
                if(!$mail_message){
                    $student = StudentTable::getInstance()->load($item['student_id']);
                    $record = array(
                        'msg_type_id' => 3,
                        'relative_id' => $item['course_id']
                    );
                    $this->storeMailMessage($record,$student);
                }

                //
                $sql = "SELECT course.course_title , mail_message.message_key
                        FROM course 
                        LEFT JOIN mail_message ON mail_message.type = 3 AND mail_message.relative_id = ".$item['course_id']."
                        WHERE course.id =".$item['course_id']."
                        AND course.delete_date IS NULL 
                        AND mail_message.student_id = ".$item['student_id'] ;
                $event = $this->fetch($sql);
                $invoice['item_name'] = $event['course_title']." イベント";
                $invoice['link'] = $event['message_key'];

            }elseif($item['program_id'] != null){

                //get mail message : if null -> create record
                $mail_message = MailMessageTable::getInstance()->getActiveList(array('type'=>3,'relative_ID'=>$item['program_id'],'student_id'=>$item['student_id']));
                if(!$mail_message){
                    $student = StudentTable::getInstance()->load($item['student_id']);
                    $record = array(
                            'msg_type_id' => 5,
                            'relative_id' => $item['program_id']
                    );
                    $this->storeMailMessage($record,$student);
                }

                //
                $sql = "SELECT program.program_name , mail_message.message_key
                        FROM program 
                        LEFT JOIN mail_message ON mail_message.type = 5 AND mail_message.relative_id = ".$item['program_id']."
                        WHERE program.id =".$item['program_id']."
                        AND program.delete_date IS NULL
                        AND mail_message.student_id = ".$item['student_id'];
                $program = $this->fetch($sql);
                $invoice['item_name'] = $program['program_name']." プログラム";
                $invoice['link'] = $program['message_key'];

            }
        }else{ // != nyukin -> link to class detail

        }

        return $invoice;
    }

    private function storeMailMessage($request, $student) {

        $mail_msg_tbl = MailMessageTable::getInstance();
        $msg_type       = $request['msg_type_id'];
        $relative_ID    = $request['relative_id'];

        $mail_message = $mail_msg_tbl->getActiveRow(['type'=>$msg_type, 'relative_ID'=>$relative_ID, 'student_id'=>$student['id']]);

        $save_m = array();
        if (empty($mail_message)) {
            $mailMessageController = new MailMessageController();

            $message_key = md5($mailMessageController->generateRandomString(64));
            $save_m['type']         = $msg_type;
            $save_m['message_key']  = $message_key;
            $save_m['relative_ID']  = $relative_ID;
            $save_m['pschool_id']   = $student['pschool_id'];
            $save_m['parent_id']    = $student['parent_id'];
            $save_m['student_id']   = $student['id'];
            $save_m['total_send']   = 0;

        } else {
            $save_m['id']           = $mail_message['id'];
        }

        $message_id = $mail_msg_tbl -> save($save_m);

        return $message_id;
    }
}