<?PHP

namespace App\Model;

class BroadcastMailAddresseeRelTable extends DbModel {
    
    /**
     *
     * @var BroadcastMailAddresseeRelTable
     */
    private static $_instance = null;
    protected $table = 'broadcast_mail_addressee_rel';
    public $timestamps = false;
    /**
     *
     * @return BroadcastMailAddresseeRelTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new BroadcastMailAddresseeRelTable ();
        }
        return self::$_instance;
    }
    public function getParentCount($mail_id) {
        $bind = array ();
        $sql = " SELECT COUNT(*) as cnt FROM broadcast_mail_addressee_rel WHERE delete_date IS NULL ";
        $sql .= " AND addressee_type IN (2,3) AND broadcast_mail_id=? ";
        $bind [] = $mail_id;
        $res = $this->fetch ( $sql, $bind );
        return $res ['cnt'];
    }
    public function getTeacherCount($mail_id) {
        $bind = array ();
        $sql = " SELECT COUNT(*) as cnt FROM broadcast_mail_addressee_rel WHERE delete_date IS NULL ";
        $sql .= " AND addressee_type = 4 AND broadcast_mail_id=? ";
        $bind [] = $mail_id;
        $res = $this->fetch ( $sql, $bind );
        return $res ['cnt'];
    }
    public function getStudentCount($mail_id, $type_id) {
        $bind = array ();
        $sql = " SELECT COUNT(a.id) as cnt FROM broadcast_mail_addressee_rel a ";
        $sql .= " INNER JOIN student b ON a.addressee_id=b.id ";
        $sql .= " WHERE a.delete_date IS NULL AND b.delete_date IS NULL ";
        $sql .= " AND a.addressee_type = 1 AND a.broadcast_mail_id=? ";
        $sql .= " AND b.id=? ";
        $bind [] = $mail_id;
        $bind [] = $type_id;
        $res = $this->fetch ( $sql, $bind );
        return $res ['cnt'];
    }
}