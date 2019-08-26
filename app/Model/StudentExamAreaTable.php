<?PHP

namespace App\Model;

class StudentExamAreaTable extends DbModel {
    
    /**
     *
     * @var StudentExamAreaTable
     */
    private static $_instance = null;
    protected $table = 'student_exam_area';
    public $timestamps = false;
    
    /**
     *
     * @return StudentExamAreaTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new StudentExamAreaTable ();
        }
        return self::$_instance;
    }
}