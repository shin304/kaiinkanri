<?PHP

namespace App\Model;

class BroadcastMailHeadTable extends DbModel {
    
    /**
     *
     * @var BroadcastMailHeadTable
     */
    private static $_instance = null;
    protected $table = 'broadcast_mail_head';
    public $timestamps = false;
    /**
     *
     * @return BroadcastMailHeadTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new BroadcastMailHeadTable ();
        }
        return self::$_instance;
    }
    
    /**
     * 一覧取得
     *
     * @param unknown $pschool_id            
     */
    public function getQueryMailList($pschool_id, $cond = null) {
        /*
         * $bind = array();
         * $bind[] = $pschool_id;
         *
         * $sql = " SELECT BMH.*, BMD.pschool_id As send_pschool_id";
         * $sql .= " FROM broadcast_mail_head AS BMH ";
         * $sql .= " LEFT OUTER JOIN broadcast_mail_distination AS BMD ";
         * $sql .= " ON BMH.id = BMD.mail_head_id ";
         * $sql .= " WHERE BMH.delete_date IS NULL ";
         * $sql .= " AND BMH.pschool_id = ? ";
         * $sql .= " AND BMD.delete_date IS NULL ";
         *
         * if( isset($cond) ){
         *
         * }
         */
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT BMH.*, BMDP.pschool_id As send_pschool_id, BMDP.name AS pschool_name";
        $sql .= " FROM broadcast_mail_head AS BMH ";
        $sql .= " LEFT OUTER JOIN ";
        $sql .= "     (SELECT BMD.*, P.name ";
        $sql .= "      FROM broadcast_mail_distination AS BMD ";
        $sql .= "      INNER JOIN pschool AS P ";
        $sql .= "      ON BMD.pschool_id = P.id ";
        $sql .= "      WHERE BMD.delete_date IS NULL ) AS BMDP ";
        $sql .= " ON BMH.id = BMDP.mail_head_id ";
        $sql .= " WHERE BMH.delete_date IS NULL ";
        $sql .= " AND BMH.pschool_id = ? ";
        
        if (isset ( $cond )) {
        }
        
        return $this->fetchAll ( $sql, $bind );
        
        return $this->fetchAll ( $sql, $bind );
    }
}