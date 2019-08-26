<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DbModel extends Model {        
    private $_tableName = null;
    const KEY_COL = 'id';
    const R_DATE_COL = 'register_date';
    const U_DATE_COL = 'update_date';
    const D_DATE_COL = 'delete_date';
    const R_ADMIN_COL = 'register_admin';
    const U_ADMIN_COL = 'update_admin';
    const SYSTEM_ADMIN_ID = 1;
    final public function __construct() {
         parent::__construct();
         $this->_tableName = $this->getTable();
    }

    public function fetch($sql, $bind = array()) {
        $result = DB::select ( $sql, $bind );
        
        $data = array_map ( function ($object) {
            return ( array ) $object;
        }, $result );
            
        if (sizeof ( $data ) > 0) {
            return $data [0];
        }
        return null;
    }
    
    public function fetchAll($sql, $bind = array()) {
        $result = DB::select ( $sql, $bind );
        return array_map ( function ($object) {
            return ( array ) $object;
        }, $result );
    }
    
    /**
     * １行取得
     *
     * @param array $where            
     * @return array
     */
    public function getActiveRow($where) {
        $where [] = $this->getActiveWhere ();
        return $this->getRow ( $where );
    }

    /**
     * １行取得
     *
     * @param array $where            
     * @return array
     */
    final public function getRow($where = null) {
        $sql = " SELECT * FROM {$this->backQuote( $this->_tableName )} ";
        $bind = array ();
        if (! is_null ( $where )) {
            $sql .= ' WHERE ' . $this->makeConditionSql ( $bind, $where );
        }
        return $this->fetch ( $sql, $bind );
    }
    
    /**
     * 有効行の条件式
     *
     * @return unknown
     */
    protected function getActiveWhere() {
        return self::D_DATE_COL . " IS NULL ";
    }
    
    /**
     * 論理削除されていないリストを取得する
     *
     * @param array $where            
     * @param array $order            
     * @param array $limit            
     * @return array
     */
    public function getActiveList($where = array(), $order = null, $limit = null) {
        $where [] = $this->getActiveWhere ();
        return $this->getList ( $where, $order, $limit );
    }
    
    /**
     * 対象テーブル名を取得する
     *
     * @return string
     */
    final public function getTableName($hasBackQuote = false) {
        if ($hasBackQuote) {
            return $this->backQuote ( $this->_tableName );
        }
        return $this->_tableName;
    }
    
    /**
     * バッククォートで囲む
     *
     * @param string $name            
     * @return string
     */
    final protected function backQuote($name) {
        return '`' . trim ( $name ) . '`';
    }
    
    /**
     * 条件文生成
     *
     * @param array $bind            
     * @param array $where            
     * @param string $join            
     * @return string
     */
    final protected function makeConditionSql(&$bind, $where, $join = 'AND') {
        $items = array ();
        foreach ( $where as $key => $val ) {
            if (is_numeric ( $key )) {
                $items [] = $val;
            } else {
                if (is_array ( $val )) {
                    $temp = array ();
                    foreach ( $val as $valval ) {
                        $temp [] = '?';
                        $bind [] = $valval;
                    }
                    // 配列の場合、in句に展開しちゃいます。
                    if (count ( $val ) == 1) {
                        $items [] = "{$this->backQuote( $key )} = ?";
                    } else {
                        $items [] = "{$this->backQuote( $key )} in (" . implode ( ",", $temp ) . ")";
                    }
                } else {
                    if (strpos ( $val, '%' ) === false) {
                        if (preg_match ( '/^([^<>=]+)([><=]+)$/e', $key, $matchs )) {
                            $items [] = "{$this->backQuote($matchs[1])} {$matchs[2]} ?";
                        } else {
                            $items [] = "{$this->backQuote( $key )} = ?";
                        }
                    } else {
                        $items [] = "{$this->backQuote( $key )} like ?";
                    }
                    $bind [] = $val;
                }
            }
        }
        if (empty ( $items )) {
            return '';
        }
        return ' ( ' . implode ( " $join ", $items ) . ' ) ';
    }
    
    final protected function makeConditionSql2(&$bind, $where, $join = 'AND') {
        $cond[] = $where;
        $items = array ();
        foreach ( $cond as $key => $val ) {
            if (is_numeric ( $key )) {
                $items [] = $val;
            } else {
                if (is_array ( $val )) {
                    $temp = array ();
                    foreach ( $val as $valval ) {
                        $temp [] = '?';
                        $bind [] = $valval;
                    }
                    // 配列の場合、in句に展開しちゃいます。
                    if (count ( $val ) == 1) {
                        $items [] = "{$this->backQuote( $key )} = ?";
                    } else {
                        $items [] = "{$this->backQuote( $key )} in (" . implode ( ",", $temp ) . ")";
                    }
                } else {
                    if (strpos ( $val, '%' ) === false) {
                        if (preg_match ( '/^([^<>=]+)([><=]+)$/e', $key, $matchs )) {
                            $items [] = "{$this->backQuote($matchs[1])} {$matchs[2]} ?";
                        } else {
                            $items [] = "{$this->backQuote( $key )} = ?";
                        }
                    } else {
                        $items [] = "{$this->backQuote( $key )} like ?";
                    }
                    $bind [] = $val;
                }
            }
        }
        if (empty ( $items )) {
            return '';
        }
        return ' ( ' . implode ( " $join ", $items ) . ' ) ';
    }
    
    /**
     * 登録・更新
     *
     * @param array $data (Ex: array( 'name' => 名称, ... );) Case UPDATE: $data has element 'id' => $id
     * @param boolean $is_batch: batch process, unit test process
     * @return saved_id
     */
    public function save(array $data = [], $is_batch = false) {
        // GET login_account_id
        $login_account_id = ( $is_batch ) ? self::SYSTEM_ADMIN_ID : $this->getLoginId ();
        
        $schema = DB::getDoctrineSchemaManager();
        $columns =  $schema->listTableColumns($this->getTableName ());

        $execMap = array ();
        $data = array_change_key_case($data, CASE_LOWER );
        foreach ( $columns as $column_name=>$column ) {
            // Ex $type : "integer", "boolean", "string", ....
            if (array_key_exists ( $column_name, $data )) {
                $type = $column->getType()->getName();
                $value = ( $type != 'string' && $type != 'text' && $type != 'boolean' && $data [$column_name] === '')? NULL : $data [$column_name];
                $execMap [$column_name] = $value;
            }
        }
        
        // DBの操作はログインしないといけない
        if (isset ( $login_account_id )) {
            if (array_key_exists ( self::KEY_COL, $execMap ) && strlen ( $execMap [self::KEY_COL] )) { // 編集
                if (! array_key_exists ( self::U_ADMIN_COL, $execMap ) && array_key_exists ( self::U_ADMIN_COL, $columns )) {
                    $execMap [self::U_ADMIN_COL] = $login_account_id;
                }
                
                if (! array_key_exists ( self::U_DATE_COL, $execMap )) {
                    $execMap [self::U_DATE_COL] = $this->getNow ();
                }
                DB::table ( $this->getTableName () )->where ( self::KEY_COL, $execMap [self::KEY_COL] )->update ( $execMap );
                return $execMap [self::KEY_COL];
            } else { // 　新規追加
                unset ( $execMap [self::KEY_COL] );
                if (! array_key_exists ( self::R_DATE_COL, $execMap )) {
                    $execMap [self::R_DATE_COL] = $this->getNow ();
                }
                
                if (! array_key_exists ( self::R_ADMIN_COL, $execMap ) && array_key_exists ( self::R_ADMIN_COL, $columns )) {
                    $execMap [self::R_ADMIN_COL] = $login_account_id;
                }
                return DB::table ( $this->getTableName () )->insertGetId ( $execMap );
            }
        }
    }
    public function execute($query, $bind = array()) {
        return DB::statement ( $query, $bind );
    }
    
    /**
     * 行リスト取得
     *
     * @param array $where            
     * @param array $order            
     * @param array $limit            
     * @return array
     */
    final public function getList($where = null, $order = null, $limit = null) {
        $sql = " SELECT * FROM {$this->backQuote( $this->_tableName )} ";
        $bind = array ();
        if (! is_null ( $where )) {
            $sql .= ' WHERE ' . $this->makeConditionSql ( $bind, $where );
        }
        if (! is_null ( $order )) {
            $sql .= $this->makeOrderSql ( $order, true );
        }
        if (! is_null ( $limit )) {
            $sql .= ' LIMIT ';
            if (is_array ( $limit )) {
                foreach ( $limit as $key => $val ) {
                    $sql .= " $key,$val ";
                    break;
                }
            } else {
                $sql .= $limit;
            }
        }
//         dd($sql);
        return $this->fetchAll ( $sql, $bind );
    }
    final protected function makeOrderSql($order, $hasKey = false) {
        $sql = '';
        $items = array ();
        foreach ( $order as $key => $val ) {
            if (is_numeric ( $key )) {
                $items [] = "{$this->backQuote( $val )} ASC";
            } else {
                $items [] = "{$this->backQuote( $key )} $val";
            }
        }
        if (! empty ( $items ) && $hasKey) {
            $sql = ' ORDER BY ';
        }
        return $sql . implode ( " , ", $items );
    }
    final public function commit() {
        DB::commit ();
    }
    final public function rollBack() {
        DB::rollBack ();
    }
    public function getActiveCount($where = array()) {
        $where [] = $this->getActiveWhere ();
        return $this->getCount ( $where );
    }
    final public function getCount($where = null) {
        $sql = " SELECT count(*) AS cnt FROM {$this->backQuote( $this->_tableName )} ";
        $bind = array ();
        if (! is_null ( $where )) {
            $sql .= ' WHERE ' . $this->makeConditionSql ( $bind, $where );
        }
        $res = $this->fetch ( $sql, $bind );
        return $res ['cnt'];
    }

    /**
     * 条件でレコード論理削除
     *
     * @param array $map
     * @return unknown
     */
    public function logicalRemoveByCondition($where = array()) {
        $map = array ();
        $map [self::U_ADMIN_COL] = $this->getLoginId ();
        $map [self::U_DATE_COL] = $this->getNow ();
        $map [self::D_DATE_COL] = $this->getNow ();
        return $this->updateRow ( $map, $where );
    }
    
    /**
     * キーで１レコード論理削除
     *
     * @param array $map
     * @return unknown
     */
    public function logicRemove( $key, $map=array() ){
        $map[self::KEY_COL]     = $key;
        $map[self::U_ADMIN_COL] = $this->getLoginId();
        $map[self::U_DATE_COL]  = $this->getNow();
        $map[self::D_DATE_COL]  = $this->getNow();
        return $this->save( $map );
    }

    /**
     * 更新
     *
     * @param array $map            
     * @param array $where            
     * @return int
     */
    final public function updateRow($map, $where = null) {
        $sql = " UPDATE {$this->backQuote( $this->_tableName )} SET ";
        $sets = array ();
        $bind = array ();
        foreach ( $map as $key => $val ) {
            $sets [] = $this->backQuote ( $key ) . '=? ';
            $bind [] = strlen ( $val ) ? $val : null;
        }
        $sql .= implode ( ' , ', $sets );
        
        if (! is_null ( $where )) {
            
            $sql .= ' WHERE ' . $this->makeConditionSql( $bind, $where);
            
        }
        return $this->execute ( $sql, $bind );
    }
    
    /**
     * 削除
     *
     * @param array $where            
     * @return int
     */
    final public function deleteRow($where = null) {
        $sql = " DELETE FROM {$this->backQuote( $this->_tableName )} ";
        $bind = array ();
        if (! is_null ( $where )) {
            $sql .= ' WHERE ' . $this->makeConditionSql ( $bind, $where );
        }
        return $this->execute ( $sql, $bind );
    }
    
    /**
     * １行挿入
     *
     * @param array $map            
     * @return int
     */
    final public function insertRow($map) {
        return DB::table ( $this->getTableName () )->insertGetId ( $map );
    }
    
    /**
     * INSETステートメントを取得する
     *
     * @param array $keys            
     * @param int $type            
     * @return PDOStatement
     */
    final protected function getInsertStatement($keys, $type = self::PLACE_HOLDER_TYPE_NAME) {
        $sql = " INSERT INTO {$this->backQuote( $this->_tableName )} ";
        $sql .= ' ( `' . implode ( '`,`', $keys ) . '` ) VALUES ( ' . $this->placeHolders ( $keys, self::PLACE_HOLDER_TYPE_NAME ) . ' ) ';
        return $this->prepare ( $sql );
    }
    
    /**
     * プレースホルダーを編集する
     *
     * @param int $count            
     * @param int $type            
     * @return string
     */
    final protected function placeHolders($keys, $type) {
        $ary = array ();
        foreach ( $keys as $key ) {
            if ($type == self::PLACE_HOLDER_TYPE_INDEX) {
                $ary [] = '?';
            } elseif ($type == self::PLACE_HOLDER_TYPE_NAME) {
                $ary [] = $this->withColon ( $key );
            }
        }
        return implode ( ',', $ary );
    }
    
    /**
     * バイントマップを編集する
     *
     * @param unknown_type $map            
     * @param unknown_type $type            
     * @return unknown
     */
    final protected function getBindMap($map, $type = self::PLACE_HOLDER_TYPE_NAME) {
        $bind = array ();
        foreach ( $map as $key => $val ) {
            if ($type == self::PLACE_HOLDER_TYPE_INDEX) {
                $bind [] = strlen ( $val ) ? $val : null;
            } elseif ($type == self::PLACE_HOLDER_TYPE_NAME) {
                
                $bind [$this->withColon ( $key )] = strlen ( $val ) ? $val : null;
            }
        }
        return $bind;
    }
    
    /**
     * 先頭に':'を付加して返却する
     *
     * @param string $val            
     * @return string
     */
    final protected function withColon($val) {
        return ':' . $val;
    }
    
    /**
     * システム日付取得
     *
     * @return string
     */
    protected function getNow() {
        return date ( 'Y-m-d H:i:s' );
    }
    public function getLoginId() {
        if (session ()->has ( 'login_account_id' )) {
            return session ()->get ( 'login_account_id' );
        } else {
            return Auth::id ();
        }
    }
    public function load($key) {
        return $this->getRow ( array (
                self::KEY_COL => $key 
        ) );
    }
    final public function beginTransaction() {
        DB::beginTransaction ();
    }
    
    /**
     * セッションからログイン中の管理者IDを取得する
     *
     * @return int
     */
    protected function getLoginAdminIdBySession() {
        $admin_session = 'admin';
        if (defined ( 'LOGIN_ACCOUNT_SESSION_NAME' )) {
            $admin_session = LOGIN_ACCOUNT_SESSION_NAME;
        }
        $adminId = 0;
        if (session ()->exists ( $admin_session )) {
            if (session ( $admin_session )->exists ( 'login_account_id' )) {
                $adminId = session ( $admin_session ) ['login_account_id'];
            }
        }
        return $adminId;
    }
    public function getListById($id){
        $sql = " SELECT * FROM {$this->getTableName(true)} WHERE id =".$id;
        $bind = array();
        return $this->fetch($sql, $bind);
    }
    public function getLoginAccountTempByLoginAccountId($id){
        $sql = "SELECT * FROM login_account_temp where login_account_id =".$id;
        $bind = array();
        return $this->fetch($sql, $bind);
    }

    /**
     * Convert object collections to array
     * @param Object $result Model collection object
     */
    public function convertToArray($result) {
        return json_decode(json_encode($result), true);
    }
}
