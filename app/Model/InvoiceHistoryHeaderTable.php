<?PHP
namespace App\Model;
class InvoiceHistoryHeaderTable extends DbModel {

	/**
	 * @var InvoiceHistoryHeaderTable
	 */
	private static $_instance = null;
	protected $table = 'invoice_history_header';

	/**
	 * @return InvoiceHistoryHeaderTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new InvoiceHistoryHeaderTable();
		}
		return self::$_instance;
	}

	// ここに実装して下さい


}