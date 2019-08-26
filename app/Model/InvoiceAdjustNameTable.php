<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceAdjustNameTable extends DbModel {
    /**
     *
     * @var InvoiceAdjustNameTable
     */
    private static $_instance = null;
    protected $table = 'invoice_adjust_name';
    public $timestamps = false;
    /**
     *
     * @return InvoiceAdjustNameTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new InvoiceAdjustNameTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
    
    /**
     * 一覧取得
     *
     * @param unknown $pschool_id            
     * @return multitype:number
     */
    public function getInvoiceAdjustList($pschool_ids) {
//         $ids = implode(',', $pschool_ids);
        $bind = array ();        
        $sql = " SELECT *";
        $sql .= " FROM invoice_adjust_name ";
        $sql .= " WHERE pschool_id IN (" . implode(',', $pschool_ids) . ")";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " AND active_flag = 1 ";
        $sql .= " ORDER BY sort_no ";
        $ret = $this->fetchAll ( $sql, $bind );
        $list = array ();
        foreach ( $ret as $item ) {
            if ($item ['type'] == 1) {
                $item ['initial_fee'] = $item ['initial_fee'] * - 1;
            }
            $list [] = $item;
        }
        
        return $list;
    }
    
    // =========================================================================
    // ここから、アクシス柔術向け機能追加版
    // =========================================================================
    
    /**
     * 上位階層含む一覧取得
     *
     * @param unknown $pschool_ids            
     * @return multitype:number
     */
    public function getInvoiceAdjustListHierarchy($pschool_id, $parents) {
//         dd($parents);
        $bind = array ();
        $list = array ();
        
        $sql = " SELECT * ";
        $sql .= " FROM invoice_adjust_name ";
        $sql .= " WHERE pschool_id IN (" . implode(',', $parents) . ")";
        $sql .= " AND delete_date IS NULL ";
        $sql .= " AND active_flag = 1 ";
        $sql .= " ORDER BY sort_no ";
        
        $ret = $this->fetchAll ( $sql, $bind );
        
        foreach ( $ret as $item ) {
            if ($item ['type'] == 1) {
                // /// $item ['initial_fee'] = $item ['initial_fee'] * - 1;
            }
            $list [] = $item;
        }
        
        return $list;
    }
}
