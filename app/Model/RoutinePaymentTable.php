<?PHP

namespace App\Model;

class RoutinePaymentTable extends DbModel {
    
    /**
     *
     * @var RoutinePaymentTable
     */
    private static $_instance = null;
    
    /**
     *
     * @return RoutinePaymentTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new RoutinePaymentTable ();
        }
        return self::$_instance;
    }
    protected $table = 'routine_payment';
    public $timestamps = false;
    
    // ここに実装して下さい
    
    /**
     * 設定されている割引・割増取得
     *
     * @param unknown $pschool_id            
     * @param unknown $data_div            
     * @param unknown $data_div            
     */
    public function getRoutinePayemntList($pschool_id, $data_div, $data_id = null, $column = array()) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $data_div;
        if (!$column) {
            $column = ['RP.*', 'IAN.name'];
        }
        $column = implode($column, ',');
        $sql = " SELECT $column ";
        $sql .= " FROM routine_payment AS RP ";
        $sql .= " INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= " ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= " WHERE RP.pschool_id = ? ";
        $sql .= " AND RP.data_div = ? ";
        if (! empty ( $data_id )) {
            $sql .= " AND RP.data_id = ? ";
            $bind [] = $data_id;
        }
        $sql .= " AND RP.delete_date IS NULL ";
//        $sql .= " ORDER BY RP.month ";
        $sql .= " ORDER BY RP.id ";
        return $this->fetchAll ( $sql, $bind );
    }
}
