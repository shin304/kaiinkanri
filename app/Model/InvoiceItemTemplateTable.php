<?PHP
namespace App\Model;
class InvoiceItemTemplateTable extends DbModel {

	/**
	 * @var InvoiceItemTemplateTable
	 */
	private static $_instance = null;
	protected $table = 'invoice_item_template';

	/**
	 * @return InvoiceItemTemplateTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new InvoiceItemTemplateTable();
		}
		return self::$_instance;
	}

	// ここに実装して下さい
	public function getListOfStudent($school_id, $parent_name =null, $invoice_id=null) {
		$sql  = " SELECT a.*,b.parent_name,b.student_name FROM {$this->getTableName(true)} a";
		$sql .= " LEFT JOIN student b ON a.student_id = b.id";
		$sql .= ' WHERE a.pschool_id = ? AND a.active_flag = 1 AND a.delete_date is null';
		$bind = array();
		$bind[] = $school_id;
		if(isset($parent_name)){
			if(strlen($parent_name)){
				$sql .= " AND b.parent_name like ?";
				$bind[] = "%".$parent_name."%";
			}
		}
		if(empty($invoice_id)){
			$sql .= ' AND a.invoice_id is null';
		}else{
			$sql .= ' AND a.invoice_id = ?';
			$bind[] = $invoice_id;
		}

		$res = array();
		$sql .= ' ORDER BY b.parent_name ASC, a.id ASC';

		$res = $this->fetchAll($sql, $bind );

		return $res;
	}

	public function getRowWithStudent($id) {
		$sql  = " SELECT a.*,b.student_name FROM {$this->getTableName(true)} a";
		$sql .= " LEFT JOIN student b ON a.student_id = b.id";
		$sql .= ' WHERE a.id = ? AND a.invoice_id is null AND a.active_flag = 1 AND a.delete_date is null';
		$bind = array();
		$bind[] = $id;
		$res = array();

		$res = $this->fetch($sql, $bind );

		return $res;
	}


}