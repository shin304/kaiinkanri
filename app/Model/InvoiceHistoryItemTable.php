<?PHP
namespace App\Model;
class InvoiceHistoryItemTable extends DbModel {

	/**
	 * @var InvoiceHistoryItemTable
	 */
	private static $_instance = null;
	protected $table = 'invoice_history_item';

	/**
	 * @return InvoiceHistoryItemTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new InvoiceHistoryItemTable();
		}
		return self::$_instance;
	}

	// ここに実装して下さい


}