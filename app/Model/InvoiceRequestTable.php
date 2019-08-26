<?PHP

namespace App\Model;

require_once ('InvoiceHeaderTable.php');
require_once ('InvoiceRequestTable.php');
class InvoiceRequestTable extends DbModel {
    
    /**
     *
     * @var InvoiceRequestTable
     */
    private static $_instance = null;
    protected $table = 'invoice_request';
    public $timestamps = false;
    
    /**
     *
     * @return InvoiceRequestTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new InvoiceRequestTable ();
        }
        return self::$_instance;
    }
    
    // =========================================================================
    // 全銀フォーマットで引落データのダウンロード／アップロード用管理テーブル
    // =========================================================================
    
    // -------------------------------------------------------------------------
    // status_flag
    // 0:未処理 -
    // 1:処理中 -
    // 2:処理依頼中 -
    // 3:処理済み <-
    // 9:キャンセル <-
    // -------------------------------------------------------------------------
    
    /**
     * 処理をキャンセルする
     *
     * @param unknown $pschool_id            
     * @param unknown $processing_file_name            
     * @return number
     */
    public function setCancelStatusFlag($pschool_id, $file_name) {
        $iRet = 0;
        $this->beginTransaction ();
        $invoiceHeader = InvoiceHeaderTable::getInstance ();
        try {
            $Rows = $this->getList ( $where = array (
                    'pschool_id' => $pschool_id,
                    'processing_filename' => $file_name,
                    'status_flag < 2' 
            ) );

            foreach ( $Rows as $row_item ) {
                $row_item ['status_flag'] = 9;
                $row_item ['update_date'] = date ( 'Y-m-d H:i:s' );
                // $row_item ['delete_date'] = date ( 'Y-m-d H:i:s' );
                $this->updateRow ( $row_item, $where = array (
                        'id' => $row_item ['id'] 
                ) );
                
                // invoice_header
                $invoiceRow = $invoiceHeader->getRow ( $where = array (
                        'id' => $row_item ['invoice_header_id'] 
                ) );
//                $invoiceRow ['workflow_status'] = 11;
                $invoiceRow ['workflow_status'] = 1;
                $invoiceRow ['paid_date'] = null;
                $invoiceRow ['is_recieved'] = 0;
//                $invoiceRow ['is_requested'] = 9; // '請求書発送済み'に変更
                $invoiceRow ['is_requested'] = 1; // '編集完了'に変更
                $invoiceRow ['update_date'] = date ( 'Y-m-d H:i:s' );
                $invoiceHeader->updateRow ( $invoiceRow, $where = array (
                        'id' => $invoiceRow ['id'] 
                ) );
            }
            $this->commit ();
        } catch ( exception $exp ) {
            $this->rollBack ();
            $iRet - 1;
        }
        return $iRet;
    }
    public function getFileList($pschool_id, $invoice_year_month, $invoice_type = null, $state=1) {
        $bind = array ();
        $res = array ();
        $sql = " SELECT distinct a.processing_filename, a.invoice_year_month, a.register_date, a.request_date, a.status_flag, b.withdrawal_day, a.deadline, a.result_date ";
        $sql .= " FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN pschool AS b ON (a.pschool_id = b.id) ";
        $sql .= " LEFT JOIN invoice_header ih ON a.invoice_header_id = ih.id";
        $sql .= " WHERE a.pschool_id = ?";
        $sql .= " AND a.delete_date IS NULL";
        $bind [] = $pschool_id;
        
        // 請求年月
        if (! empty ( $invoice_year_month )) {
            $sql .= " AND a.invoice_year_month = ?";
            $bind [] = $invoice_year_month;
        }
        // count amount file except status 9
        if ( !empty($state) ){
            $sql .= " AND (status_flag = 1 OR status_flag = 2 OR status_flag = 3) ";
        }

        //filter by invoice type
        if(!empty($invoice_type)){
            $bind [] = $invoice_type;
            $sql .= " AND ih.invoice_type = ?";
        }

        $sql.= " GROUP BY a.processing_filename";

        $res = $this->fetchAll ( $sql, $bind );

        // 合計金額の算出
        foreach ( $res as $key => $value ) {
            if (! empty ( $value ['processing_filename'] )) {
                $binds = array ();
                
                $sqls = " SELECT a.amount, a.parent_id ";
                $sqls .= " FROM {$this->getTableName(true)} a ";
                // ファイル名
                $sqls .= " WHERE a.processing_filename = ?";
                $binds [] = $value ['processing_filename'];
                // 状態
                $sqls .= " AND (status_flag = 1 OR status_flag = 2 OR status_flag = 3 ) ";
                $sqls .= " ORDER BY a.parent_id ";
                
                $ary = $this->fetchAll ( $sqls, $binds );

                $total_count = 0;
                $total_amount = 0;
                $current_id = 0;
                foreach ( $ary as $item ) {
                    $total_amount += $item ['amount'];
                    $total_count ++;
                }
                
                $res [$key] ['total_cnt'] = $total_count;
                $res [$key] ['total_amount'] = $total_amount;
            }
        }

        return $res;
    }
    
    /**
     * 口座振り込みリスト取得
     *
     * @param unknown $pschool_id            
     * @param string $cond            
     */
    public function getAccountTranserList($pschool_id, $invoice_year_month = null, $cond = null, $invoice_type = null) {
        $bind = array ();
        $bind [] = $pschool_id;

        if(!empty($invoice_year_month)){
            $bind [] = $invoice_year_month;
        }


        $sql = " SELECT iq.id, iq.processing_filename, COUNT(iq.processing_filename) AS total_cnt, ";
        $sql .= " SUM(iq.amount) AS total_amount, MAX(iq.status_flag) AS status_flag, ";
        $sql .= " MAX(iq.register_date) AS register_date, MAX(iq.invoice_year_month) AS invoice_year_month, ";
        $sql .= " MAX(iq.result_date) AS result_date, MAX(iq.deadline) AS deadline ";
        $sql .= " FROM invoice_request iq ";
        $sql .= " LEFT JOIN invoice_header ih ON ih.id = iq.invoice_header_id ";
        $sql .= " WHERE iq.pschool_id = ? ";
        $sql .= " AND iq.delete_date IS NULL ";

        if(!empty($invoice_year_month)) {
            $sql .= " AND iq.invoice_year_month = ? ";
        }
        if(!empty($invoice_type)){
            $bind [] = $invoice_type;
            $sql .= " AND ih.invoice_type = ?";
        }
        
        $sql .= " GROUP BY processing_filename, status_flag ";
        $sql .= " ORDER BY register_date DESC ";

        $transfer = $this->fetchAll ( $sql, $bind );
        
        // 同一保護者で、複数月引落の場合parent_idが重複しているので、重複を1件としてカウント
        $invoiceHeaderTable = InvoiceHeaderTable::getInstance();
        foreach ( $transfer as &$transfer_item ) {
            $bind = array (
                    $transfer_item ['processing_filename'] 
            );
            $sql = "SELECT DISTINCT parent_id,invoice_header_id ";
            $sql .= " FROM invoice_request ";
            $sql .= " WHERE processing_filename = ? ";
            
            $parentRows = $this->fetchAll ( $sql, $bind );
            $transfer_item ['total_cnt'] = count ( $parentRows );
            //if($transfer_item['status_flag'] == 3 || $transfer_item['status_flag'] == 1){
                foreach ($parentRows as $key => $value){
                    $transfer_item['detail_request'][] = $invoiceHeaderTable->getDataParentStudent($value['invoice_header_id']);
                }
            //}
        }
        
        return $transfer;
    }

    public function getExportListByMonth($newest_month = null){

        $sql = "SELECT id, invoice_type, is_nyukin 
                FROM invoice_header 
                WHERE delete_date IS NULL
               
                AND workflow_status >= 11
                AND workflow_status <29
                AND is_nyukin <> 0 
                AND pschool_id = ".session()->get('school.login.id');

        if(!empty($newest_month)){
           $sql.= "  AND ( invoice_type = 3 OR (invoice_type = 4  AND invoice_year_month = '".$newest_month."' ))";

        }

        $sql.= " AND id NOT IN (
                        SELECT invoice_header_id
                        FROM invoice_request
                        WHERE status_flag = 1 ";
//        if(!empty($newest_month)) {
//            $sql.= "AND invoice_year_month = '".$newest_month."' ";
//        }
        $sql.=" )";

        $res = $this->fetchAll($sql);

        return $res;
    }
}