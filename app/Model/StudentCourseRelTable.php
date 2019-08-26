<?PHP

namespace App\Model;

class StudentCourseRelTable extends DbModel {
    
    /**
     *
     * @var StudentCourseRelClassTable
     */
    private static $_instance = null;
    protected $table = 'student_course_rel';
    public $timestamps = false;
    
    /**
     *
     * @return StudentCourseRelClassTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new StudentCourseRelTable ();
        }
        return self::$_instance;
    }
    
    /**
     * 請求書IDを元に、入金済みフラグを更新する。
     */
    public function updateIsReceivedByInvoice($school_id, $login_account_id, $invoice_id) {
        $sql = "UPDATE " . "student_course_rel AS c_rel " . "INNER JOIN invoice_item AS i_item " . "ON (c_rel.course_id = i_item.course_id) " . "SET " . "c_rel.is_received = 1 " . ", c_rel.update_date = ? " . ", c_rel.update_admin = ? " . "WHERE " . "i_item.invoice_id = ?";
        $bind = array (
                $this->getNow (),
                $login_account_id,
                $invoice_id 
        );
        $this->execute ( $sql, $bind );
    }
}