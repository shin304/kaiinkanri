<?PHP

namespace App\Model;

class ReportCardTable extends DbModel {
    
    /**
     *
     * @var ReportCardTable
     */
    private static $_instance = null;
    protected $table = 'report_card';
    public $timestamps = false;
    /**
     *
     * @return ReportCardTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ReportCardTable ();
        }
        return self::$_instance;
    }
    public function getReportList($arryQuery = null) {
        $bind = array ();
        
        $sql = " SELECT a.*, b.card_name FROM report_card a";
        $sql .= " LEFT JOIN report_card_list b";
        $sql .= "  ON a.card_name_id = b.id AND a.pschool_id = b.pschool_id";
        $sql .= " WHERE a.pschool_id = ? AND a.student_id = ?";
        $sql .= " AND a.card_name_id != 0 AND a.delete_date is null";
        
        $bind [] = $arryQuery ['pschool_id'];
        $bind [] = $arryQuery ['student_id'];
        
        if (! empty ( $arryQuery ['id'] )) {
            $sql .= " AND a.id = ?";
            $bind [] = $arryQuery ['id'];
        }
        
        $sql .= ' ORDER BY a.card_date DESC';
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        
        return json_decode ( json_encode ( $res ), true );
    }
}