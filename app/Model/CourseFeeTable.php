<?PHP
namespace App\Model;
class CourseFeeTable extends DbModel {

	/**
	 * @var CourseFeeTable
	 */
	private static $_instance = null;
	protected $table = 'course_fee';

	/**
	 * @return CourseFeeTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new CourseFeeTable();
		}
		return self::$_instance;
	}

	// ここに実装して下さい


}