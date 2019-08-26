<?PHP

namespace App\Model;

class BroadcastMailStudentRelTable extends DbModel {
    
    /**
     *
     * @var BroadcastMailStudentRelTable
     */
    private static $_instance = null;
    protected $table = 'broadcast_mail_student_rel';
    public $timestamps = false;
    /**
     *
     * @return BroadcastMailStudentRelTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new BroadcastMailStudentRelTable ();
        }
        return self::$_instance;
    }
    
    // ここに実装して下さい
}