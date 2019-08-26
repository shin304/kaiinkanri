<?PHP

namespace App\Model;

class ParentBankAccountTable extends DbModel {
    
    /**
     *
     * @var ParentBankAccountTable
     */
    private static $_instance = null;

    /**
     *
     * @return ParentBankAccountTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ParentBankAccountTable ();
        }
        return self::$_instance;
    }
    protected $table = 'parent_bank_account';
    public $timestamps = false;
    
    // ここに実装して下さい
}