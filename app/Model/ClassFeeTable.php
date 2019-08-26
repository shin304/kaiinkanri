<?PHP
namespace App\Model;
class ClassFeeTable extends DbModel {

	/**
	 * @var ClassFeeTable
	 */
	private static $_instance = null;
	protected $table = 'class_fee';

	/**
	 * @return ClassFeeTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new ClassFeeTable();
		}
		return self::$_instance;
	}

	// ここに実装して下さい


}