<?PHP

namespace App\Model;

class AssociationMenuTable extends DbModel {
    
    /**
     *
     * @var AssociationMenuTable
     */
    private static $_instance = null;
    protected $table = 'association_menu';
    public $timestamps = false;
    /**
     *
     * @return AssociationMenuTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new AssociationMenuTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    public function getMenuList($arryQuery = null) {
        $bind = array ();
        
        $sql = " SELECT a.*, b.id as default_id, b.menu_name as default_menu_name";
        $sql .= " FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN default_menu b ON a.default_menu_id = b.id";
        $sql .= " WHERE a.pschool_id = ? AND a.delete_date is null";
        $sql .= " ORDER BY a.seq_no ASC";
        
        $bind [] = $arryQuery ['pschool_id'];
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        
        return json_decode ( json_encode ( $res ), true );
        ;
    }
    public function getActiveMenuList($arryQuery = null) {
        $bind = array ();
        
        $sql = " SELECT a.*, b.id as default_id, b.action_url, b.menu_name as default_menu_name";
        $sql .= " FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN default_menu b ON a.default_menu_id = b.id";
        $sql .= " WHERE a.pschool_id = ? AND a.active_flag=1 AND a.delete_date is null";
        $sql .= " ORDER BY a.seq_no ASC";
        
        $bind [] = $arryQuery ['pschool_id'];
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $res ), true );
        ;
    }
}