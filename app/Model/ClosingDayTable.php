<?PHP

namespace App\Model;

class ClosingDayTable extends DbModel {
    
    /**
     *
     * @var ClosingDayTable
     */
    private static $_instance = null;
    protected $table = 'closing_day';
    public $timestamps = false;
    /**
     *
     * @return ClosingDayTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ClosingDayTable ();
        }
        return self::$_instance;
    }
    
    // =========================================================================
    // 全銀フォーマットで引落データのダウンロード／アップロード用管理テーブル
    // =========================================================================
    public function getClosingDay($withdrawal_day, $request_date) {
        $res = array ();
        $ret = array ();
        if (! empty ( $withdrawal_day )) {
            $bind = array ();
            
            $sql = " SELECT c.result_date, c.deadline ";
            $sql .= " FROM {$this->getTableName(true)} c";
            // 引落日（4,20,27）
            $sql .= " WHERE c.transfer_day = ?";
            $bind [] = $withdrawal_day;
            // 締切日
            $sql .= " AND c.deadline > ? ";
            $bind [] = $request_date;
            
            $sql .= " ORDER BY c.deadline ";
            $sql .= " LIMIT 1 ";
            
            $res = $this->fetchAll ( $sql, $bind );
        }
        
        if (! empty ( $res )) {
            $ret = $res [0];
        } else {
            $ret = array (
                    'result_date' => '',
                    'deadline' => '' 
            );
        }
        
        return $ret;
    }
}