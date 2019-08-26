<?PHP

namespace App\Model;

use App\Common\Constants;

class InvoiceDebitTable extends DbModel {
    
    /**
     *
     * @var InvoiceDebitTable
     */
    private static $_instance = null;
    protected $table = 'invoice_debit';
    public $timestamps = false;
    /**
     *
     * @return InvoiceDebitTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new InvoiceDebitTable ();
        }
        return self::$_instance;
    }
    
    public function getInvoiceDebitDataById($header_id){

        $bind = array();

        $bind[] = $header_id;
        $sql = "SELECT ide.invoice_debit_id, ide.debit_year_month, ide.amount, ide.due_date ";
        $sql.= " FROM invoice_debit ide ";
        $sql.= " WHERE ide.invoice_header_id = ? ";
        $sql.= " AND ide.status  = 0 ";
        $sql.= " AND ide.delete_date IS NULL ";

        return $this->fetchAll($sql,$bind);
    }

    public function getListDebit($invoice_id, $debit_ids){

        $sql = "SELECT ide.invoice_debit_id, ide.debit_year_month, ide.amount, ide.due_date ";
        $sql.= " FROM invoice_debit ide ";
        $sql.= " WHERE ide.invoice_debit_id IN (".$debit_ids.") ";
        $sql.= " AND ide.invoice_header_id  = ".$invoice_id ;
        $sql.= " AND ide.status  = 0 ";
        $sql.= " AND ide.delete_date IS NULL ";

        return $this->fetchAll($sql);
    }

    public function updateSetDebitComplete($debit_ids){
        $sql = "UPDATE invoice_debit 
                SET status = 1 
                WHERE invoice_debit_id IN (".$debit_ids.")";
        $this->execute($sql);
    }
}