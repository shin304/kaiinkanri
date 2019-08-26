<?PHP

namespace App\Model;

class PschoolTypesTable extends DbModel {
    
    /**
     *
     * @var PschoolTypesTable
     */
    private static $_instance = null;
    protected $table = 'pschool_types';
    public $timestamps = false;
    /**
     *
     * @return PschoolTypesTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new PschoolTypesTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
}