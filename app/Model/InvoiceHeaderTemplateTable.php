<?PHP
namespace App\Model;
class InvoiceHeaderTemplateTable extends DbModel {

	/**
	 * @var InvoiceHeaderTemplateTable
	 */
	private static $_instance = null;
	protected $table = 'invoice_header_template';

	/**
	 * @return InvoiceHeaderTemplateTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new InvoiceHeaderTemplateTable();
		}
		return self::$_instance;
	}

	// ここに実装して下さい


}