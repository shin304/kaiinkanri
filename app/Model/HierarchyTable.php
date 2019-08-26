<?PHP

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HierarchyTable extends DbModel {
    
    /**
     *
     * @var HierarchyTable
     */
    private static $_instance = null;
    protected $table = 'hierarchy';
    public $timestamps = false;
    /**
     *
     * @return HierarchyTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new HierarchyTable ();
        }
        return self::$_instance;
    }
    
    /**
     *
     * @param unknown $pschool_id            
     */
    public function getHierarchy($pschool_id) {
        $sql = "SELECT hierarchy.id, hierarchy.group_id, layer, parent_id, manage_flag, pschool_id , 
			pschool.name as pschool_name , pschool.tantou, pschool.logo
			FROM hierarchy
			INNER JOIN pschool ON hierarchy.pschool_id = pschool.id 
			WHERE pschool_id = ?";
        $bind = array (
                $pschool_id 
        );
        return $this->fetch ( $sql, $bind );
    }
    
    /**
     */
    public function getHierarchyOrganization() {
        $sql = "SELECT hierarchy.*, groupedhr.member_count, pschool.name as pschool_name, pschool.tantou, pschool.logo
				FROM hierarchy
				INNER JOIN (
					SELECT group_id, MIN(layer) AS layer, SUM(count) as member_count
					FROM (SELECT hierarchy.*, count(student.id) as count
						FROM hierarchy
						LEFT JOIN student ON student.pschool_id = hierarchy.pschool_id
						GROUP BY hierarchy.pschool_id
						) hierarchy
					GROUP BY group_id
				) groupedhr	
				ON hierarchy.group_id = groupedhr.group_id 
				AND hierarchy.layer = groupedhr.layer
				INNER JOIN pschool ON pschool.id = hierarchy.pschool_id
				WHERE pschool.active_flag = 1 AND hierarchy.layer < 3 AND pschool.delete_date IS NULL";
        return $this->fetch ( $sql );
    }
    
    /**
     *
     * @param unknown $group_id            
     */
    public function getHierarchyHeadquarter($group_id) {
        $sql = "SELECT hierarchy.*, pschool.name as pschool_name, pschool.tantou, pschool.logo
				FROM (SELECT hierarchy.*, count(student.id) as count
					FROM hierarchy
					LEFT JOIN student ON student.pschool_id = hierarchy.pschool_id
					WHERE group_id IN (0,?)
					GROUP BY hierarchy.pschool_id
				) hierarchy
				LEFT JOIN pschool ON pschool.id = hierarchy.pschool_id 
				WHERE pschool.active_flag = 1 AND pschool.delete_date IS NULL 
				ORDER BY layer ASC ";
        $bind = array ();
        $bind [] = $group_id;
        $hierarchies = $this->fetch ( $sql, $bind );
        // Calculate the member count for higher organization
        for($i = sizeof ( $hierarchies ) - 1; $i >= 0; $i --) {
            $current = $hierarchies [$i];
            $count = $current ["count"];
            $layer = $current ["layer"];
            for($j = $i + 1; $j < sizeof ( $hierarchies ); $j ++) {
                if ($hierarchies [$j] ["layer"] > $layer + 1) {
                    break;
                }
                
                if ($hierarchies [$j] ["parent_id"] == $current ["id"]) {
                    $count += $hierarchies [$j] ["count"];
                }
            }
            $hierarchies [$i] ["count"] = $count;
        }
        return $hierarchies;
    }
    
    /**
     *
     * @param unknown $current_hierarchy            
     * @param unknown $list_hierarchy            
     * @return multitype:unknown
     */
    public function getHigherHierarchies($current_hierarchy, $list_hierarchy) {
        $highers = array ();
        $lastest_node = $current_hierarchy;
        
        for($i = sizeof ( $list_hierarchy ) - 1; $i >= 0; $i --) {
            if ($list_hierarchy [$i] ['layer'] < $current_hierarchy ['layer']) {
                if ($list_hierarchy [$i] ['id'] == $lastest_node ['parent_id']) {
                    $highers [] = $list_hierarchy [$i];
                    $lastest_node = $list_hierarchy [$i];
                }
            }
        }
        return $highers;
    }
    
    /**
     * 自分の親のid取得
     *
     * @param unknown $pschool_id            
     * @return multitype:unknown
     */
    public function getParentPschoolIds($pschool_id) {
        $parent_ids = array ();
        
        $bind = array ();
        $bind [] = $pschool_id;
        
        $sql = " SELECT A.*, B.country_code ";
        $sql .= " FROM hierarchy A ";
        $sql .= " INNER JOIN pschool B ON A.pschool_id = B.id ";
        $sql .= " WHERE pschool_id = ? ";
        
        $curr = $this->fetch ( $sql, $bind );
        
        if (! $curr) {
            return $parent_ids;
        }
        $layer = $curr ['layer'] - 1;
        
        $group_id = $curr ['group_id'];
        
        $country_code = $curr ['country_code'];
        while ( $layer > 1 ) {
            
            $sql = " SELECT A.* ";
            $sql .= " FROM hierarchy A ";
            $sql .= " INNER JOIN pschool B ON A.pschool_id = B.id ";
            $sql .= " WHERE A.layer = " . $layer;
            $sql .= " AND A.group_id = " . $group_id;
            $sql .= " AND B.country_code = " . $country_code;
            $ret = $this->fetch ( $sql, $bind );
            if ($ret && $layer > 1) {
                $layer = $ret ['layer'];
                $parent_ids [] = $ret ['pschool_id'];
            } else {
                $layer = 0;
            }
            $layer -= 1;
        }
        return $parent_ids;
    }
    
    // ここに実装して下さい
    public function getHeadOffice($param = null) {
        $bind = array ();
        
        $sql = " SELECT H.pschool_id, P.name ";
        $sql .= " FROM hierarchy AS H ";
        $sql .= " INNER JOIN pschool AS P ";
        $sql .= " ON H.pschool_id = P.id ";
        $sql .= " WHERE H.layer = 2 "; // 蝗｣菴捺悽驛ｨ縺ｯlayer=2
        $sql .= " AND P.delete_date IS NULL "; // 蠢�隕√°�ｼ�
        $sql .= " ORDER BY H.id ASC ";
        
        return $this->fetch ( $sql, $bind );
    }
    public function getChildrenHierachyById($hierarchy_id) {
        $bind = array ();
        $sql = " SELECT * FROM hierarchy ";
        $sql .= " WHERE parent_id = ? ";
        $bind [] = $hierarchy_id;
        
        return $this->fetch ( $sql, $bind );
    }
    public function getBranchInGroupCount($group_id) {
        $sql = "SELECT COUNT(*) as count FROM hierarchy WHERE group_id = ?";
        $res = $this->fetch ( $sql, array (
                $group_id 
        ) );
        if ($res && $res ['count'] > 0) {
            return $res ['count'] - 1;
        } else {
            throw new Exception ( 'Can not get branch count' );
        }
    }
    
    /**
     * 自分の配下の支部を取得
     *
     * @param unknown $parent_id            
     * @param unknown $country_code            
     */
    public function getList_pschoolName($parent_id, $country_code) {
        $bind = array ();
        $bind [] = $parent_id;
        $bind [] = $country_code;
        
        $sql = " SELECT P.id AS pschool_id, P.name, H.id, H.manage_flag ";
        $sql .= " FROM hierarchy AS H ";
        $sql .= " INNER JOIN pschool As P ";
        $sql .= " ON H.pschool_id = P.id ";
        $sql .= " WHERE H.parent_id = ? ";
        $sql .= " AND H.delete_date IS NULL ";
        $sql .= " AND P.delete_date IS NULL ";
        $sql .= " AND P.country_code = ? ";
        $sql .= " ORDER BY P.id ASC ";
        
        return $this->fetchAll ( $sql, $bind );
    }
}