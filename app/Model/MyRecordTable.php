<?PHP

namespace App\Model;

class MyRecordTable extends DbModel {
    
    /**
     *
     * @var MyRecordTable
     */
    private static $_instance = null;
    protected $table = 'my_record';
    public $timestamps = false;
    /**
     *
     * @return MyRecordTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new MyRecordTable ();
        }
        return self::$_instance;
    }
    
    /**
     * アプリ成績表示情報を取得する。
     */
    public function getAppScoreList($pschool_id, $student_id) {
        $sql = " SELECT a.*, b.title as workbook_name, c.title as chapter_name ";
        $sql .= " FROM my_record a INNER JOIN workbook b ON a.workbook_id = b.id ";
        $sql .= " INNER JOIN workbook_chapter c ON a.chapter_id = c.id ";
        $sql .= " INNER JOIN member d ON a.member_id = d.id ";
        $sql .= " WHERE a.delete_date is null and b.delete_date is NULL and b.pschool_id = ? and ";
        $sql .= " c.delete_date is null and d.delete_date is null and d.student_id = ? ";
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $student_id;
        $arr = $this->fetchAll ( $sql, $bind );
        return json_decode ( json_encode ( $arr ), true );
    }
}