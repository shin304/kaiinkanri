<?PHP

namespace App\Model;

class BroadcastMailDistinationTable extends DbModel {
    
    /**
     *
     * @var BroadcastMailDistinationTable
     */
    private static $_instance = null;
    protected $table = 'broadcast_mail_distination';
    public $timestamps = false;
    /**
     *
     * @return BroadcastMailDistinationTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new BroadcastMailDistinationTable ();
        }
        return self::$_instance;
    }
    
    /**
     *
     * @param unknown $pschool_id            
     */
    public function getHeadList($pschool_id, $mail_head_id = null) {
        $ret = array ();
        
        $mail_head_id = empty ( $mail_head_id ) ? 0 : $mail_head_id;
        $bind = array ();
        
        $bind [] = $pschool_id;
        $sql = " SELECT * ";
        $sql .= " FROM hierarchy ";
        $sql .= " WHERE pschool_id = ? ";
        $sql .= " AND group_id = 0 ";
        $sql .= " AND delete_date IS NULL ";
        $association = $this->fetch ( $sql, $bind );
        
        if (count ( $association ) > 0) {
            
            $bind2 = array ();
            $bind2 [] = $association ['id'];
            $bind2 [] = $mail_head_id;
            
            $sql = " SELECT HP.*, BMD.mail_head_id ";
            $sql .= " FROM ";
            $sql .= "  ( ";
            $sql .= "     SELECT H.* , P.name ";
            $sql .= "     FROM hierarchy AS H ";
            $sql .= "     INNER JOIN pschool AS P ON H.pschool_id = P.id ";
            $sql .= "     WHERE H.parent_id = ? ";
            $sql .= "     AND H.layer = 2 ";
            $sql .= "     AND H.delete_date IS NULL ";
            $sql .= "     AND P.delete_date IS NULL ";
            $sql .= " ) AS HP ";
            $sql .= " LEFT OUTER JOIN ";
            $sql .= " ( ";
            $sql .= "     SELECT mail_head_id, pschool_id ";
            $sql .= "     FROM broadcast_mail_distination ";
            $sql .= "     WHERE mail_head_id = ? ";
            $sql .= "     AND delete_date IS NULL ";
            $sql .= " ) AS BMD ";
            $sql .= " ON HP.pschool_id = BMD.pschool_id ";
            $sql .= " ORDER BY HP.id ASC ";
            
            $ret = $this->fetchAll ( $sql, $bind2 );
        }
        return $ret;
    }
    
    /**
     *
     * @param unknown $head_id            
     */
    public function getDetailList($head_id) {
        $bind = array ();
        $bind [] = $head_id;
        
        $sql = " SELECT BMD.*, P.name ";
        $sql .= " FROM broadcast_mail_distination AS BMD ";
        $sql .= " INNER JOIN pschool AS P ";
        $sql .= " ON BMD.pschool_id = P.id ";
        $sql .= " WHERE BMD.delete_date IS NULL ";
        $sql .= " AND BMD.mail_head_id = ? ";
        $sql .= " AND P.delete_date IS NULL ";
        
        return $this->fetchAll ( $sql, $bind );
    }
}