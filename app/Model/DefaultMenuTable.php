<?PHP

namespace App\Model;

class DefaultMenuTable extends DbModel {
    
    /**
     *
     * @var DefaultMenuTable
     */
    private static $_instance = null;
    protected $table = 'default_menu';
    public $timestamps = false;
    /**
     *
     * @return DefaultMenuTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new DefaultMenuTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getMenuList($arryQuery = null) {
        $bind = array ();
        
        $sql = " SELECT a.*";
        $sql .= " FROM {$this->getTableName(true)} a";
        $sql .= " ORDER BY seq_no ASC";
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        
        return $res;
    }
}