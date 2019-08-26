<?PHP

namespace App\Model;

class MTagSubjectTable extends DbModel {
    const SUBJECT_SAVE_FAILURE = 11;
    const SUBJECT_SAVE_FAILURE_ALREADY_EXIST = 12;
    const SUBJECT_SAVE_UNNEEDED = 13;
    const TYPE_SUBJECT = 1;
    const TYPE_LARGE_CLASS = 2;
    const TYPE_MIDDLE_CLASS = 3;
    const TYPE_SMALL_CLASS = 4;
    const FUNC_REGISTER = 1;
    const FUNC_UPDATE = 2;
    const FUNC_DELETE = 3;
    
    /**
     *
     * @var MTagSubjectTable
     */
    private static $_instance = null;
    protected $table = 'm_tag_subject';
    public $timestamps = false;
    
    /**
     *
     * @return MTagSubjectTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new MTagSubjectTable ();
        }
        return self::$_instance;
    }
    
    /**
     * Get list of (id, name)
     *
     * @return multitype:
     */
    public function getBasicDataList() {
        $sql = " SELECT id, name FROM m_tag_subject ";
        $sql .= " WHERE delete_date is NULL ";
        $bind = array ();
        $res = $this->fetchAll ( $sql, $bind );
        return $res;
    }
    
    /**
     * Get only id and name from db table
     *
     * @param number $id            
     * @return array id and name
     */
    public function loadBasic($id = 0) {
        $bind = array ();
        $sql = " SELECT id, name FROM m_tag_subject ";
        $sql .= " WHERE delete_date is NULL ";
        if ($id != 0) {
            $sql .= " and id = ? ";
            $bind [] = $id;
        }
        $sql_res = $this->fetchAll ( $sql, $bind );
        return $sql_res [0];
    }
    
    /**
     * 今の科目を変更すると影響がでるデーターのリストを取得する。
     *
     * @param int $subject_id            
     */
    public function getDependentList($subject_id) {
        $list = array ();
        $subject = MTagSubjectTable::getInstance ()->loadBasic ( $subject_id );
        $content = array (
                'type_id' => self::TYPE_SUBJECT,
                'id' => $subject ['id'],
                'subject_name' => $subject ['name'],
                'large_class_name' => '',
                'middle_class_name' => '',
                'small_class_name' => '' 
        );
        $list [] = $content;
        $large_class_ids = MTagLargeClassTable::getInstance ()->getSublingLargeClassIds ( $subject_id );
        foreach ( $large_class_ids as $large_class ) {
            $list2 = MTagLargeClassTable::getInstance ()->getDependentList ( $large_class ['id'] );
            $list = array_merge ( $list, $list2 );
        }
        return $list;
    }
    
    /**
     * Get subject id
     *
     * @param String $name            
     * @return int id #0 if not found
     */
    public function getId($name) {
        printf ( 'name is %s', $name );
        $id = 0;
        $bind = array ();
        $sql = " SELECT id FROM m_tag_subject ";
        $sql .= " WHERE delete_date is null and name = ? ";
        $bind [] = $name;
        $res = $this->fetchAll ( $sql, $bind );
        if (! empty ( $res )) {
            $id = $res [0] ['id'];
        }
        return $id;
    }
    
    /**
     * 登録、親がない場合親も登録
     *
     * @param String $name            
     * @param int $id            
     * @return subject_id and error
     */
    public function adv_register($name, $id = 0) {
        $return = array ();
        $return ['subject_id'] = 0;
        $new_id = $this->getId ( $name );
        // The name is Not found
        if ($new_id == 0) {
            $subject = array (
                    'name' => $name 
            );
            if ($id != 0) {
                $subject ['id'] = $id;
            }
            $return ['subject_id'] = $this->save ( $subject );
        } elseif ($new_id == $id) {
            $return ['error'] = self::SUBJECT_SAVE_UNNEEDED;
        } else {
            $return ['error'] = self::SUBJECT_SAVE_FAILURE_ALREADY_EXIST;
        }
        echo "in Subject model";
        return $return;
    }
    public function getListBasic() {
        $sql = " SELECT id as subject_id, name as subject_name FROM m_tag_subject ";
        $sql .= " WHERE delete_date is null";
        $res = $this->fetchAll ( $sql, array () );
        return $res;
    }
}