<?PHP

namespace App\Model;

class ExamScoreTable extends DbModel {
    
    /**
     *
     * @var ExamScoreTable
     */
    private static $_instance = null;
    protected $table = 'exam_score';
    public $timestamps = false;
    /**
     *
     * @return ExamScoreTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ExamScoreTable ();
        }
        return self::$_instance;
    }
    public function getExamList($arryQuery = null) {
        $bind = array ();
        
        $sql = " SELECT a.*, b.exam_name FROM exam_score a";
        $sql .= " LEFT JOIN exam_score_list b";
        $sql .= "  ON a.exam_name_id = b.id AND a.pschool_id = b.pschool_id";
        $sql .= " WHERE a.pschool_id = ? AND a.student_id = ?";
        $sql .= " AND a.exam_name_id != 0 AND a.delete_date is null";
        
        $bind [] = $arryQuery ['pschool_id'];
        $bind [] = $arryQuery ['student_id'];
        
        if (! empty ( $arryQuery ['id'] )) {
            $sql .= " AND a.id = ?";
            $bind [] = $arryQuery ['id'];
        }
        
        $sql .= ' ORDER BY a.exam_date DESC';
        
        $res = array ();
        $res = $this->fetchAll ( $sql, $bind );
        
        return json_decode ( json_encode ( $res ), true );
    }
}