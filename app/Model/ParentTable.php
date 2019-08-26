<?PHP

namespace App\Model;
use Illuminate\Support\Facades\DB;
use App\Model\StudentTable;
class ParentTable extends DbModel {
    
    /**
     *
     * @var ParentTable
     */
    private static $_instance = null;
    
    /**
     *
     * @return ParentTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ParentTable ();
        }
        return self::$_instance;
    }
    protected $table = 'parent';
    public $timestamps = false;
    /**
     * ]
     * Get List of Parent
     * $search search conditions , no condition is array()
     */
    public function getParentList($search) {
        $sql = " SELECT p.*, s.student_name FROM parent p LEFT JOIN student s ON s.parent_id=p.id AND s.pschool_id = p.pschool_id ";
        $sql .= " WHERE p.pschool_id = ? and p.delete_date is null and s.delete_date is null ";
        $bind = array ();
        $bind [] = $_SESSION ['school.login'] ['id'];
        if ($search) {
            if (isset ( $search ['search_name'] ) && $search ['search_name']) {
                $sql .= " AND (p.parent_name like ? OR p.name_kana like ?)";
                $bind [] = "%" . $search ['search_name'] . "%";
                $bind [] = "%" . $search ['search_name'] . "%";
            }
            if (isset ( $search ['search_pref'] ) && $search ['search_pref']) {
                $sql .= " AND p.pref_id = ? ";
                $bind [] = $search ['search_pref'];
                if ($search ['search_city'] && $search ['search_city']) {
                    $sql .= " AND p.city_id = ? ";
                    $bind [] = $search ['search_city'];
                }
            }
        }
        // $sql .= " GROUP BY p.id ";
        $sql .= " ORDER BY p.name_kana ASC, p.id ASC";
        return $this->fetchAll ( $sql, $bind );
    }
    
    /**
     * ]
     * Get List of Parent
     * $search search conditions , no condition is array()
     */
    public function getParentList2($search) {
       // dd($search);
        $sql = " SELECT p.* FROM parent p ";
        //Toran edit for new search condition //
        $sql .= " LEFT JOIN student s ON s.parent_id = p.id ";
        $sql .= " WHERE p.pschool_id = ? and p.delete_date is null ";
        // for select new parent
        //$sql .= " AND s.active_flag = 1 AND s.delete_date IS NULL ";
        $bind = array ();
        $bind [] = session()->get ( 'school.login.id' );
        //$bind [] = '188'; // for unit test
        if ($search) {
            if (isset ( $search ['search_name'] ) && $search ['search_name']) {
                //Toran edit for new search condition //
                $sql .= " AND (p.parent_name LIKE ? OR p.name_kana LIKE ? OR 
                s.student_name LIKE ? OR s.student_name_kana LIKE ?)";

                $bind [] = "%" . $search ['search_name'] . "%";
                $bind [] = "%" . $search ['search_name'] . "%";
                $bind [] = "%" . $search ['search_name'] . "%";
                $bind [] = "%" . $search ['search_name'] . "%";

            }
            if (isset ( $search ['search_code'] ) && $search ['search_code']) {
                $sql .= " AND s.student_no LIKE ? ";

                $bind [] = "%" . $search ['search_code'] . "%";
            }
//            if (isset ( $search ['search_pref'] ) && $search ['search_pref']) {
//                $sql .= " AND p.pref_id = ? ";
//                $bind [] = $search ['search_pref'];
//                if (isset ( $search ['search_city'] ) && $search ['search_city']) {
//                    $sql .= " AND p.city_id = ? ";
//                    $bind [] = $search ['search_city'];
//                }
//            }
//            if (isset ( $search ['search_address'] ) && $search ['search_address']) {
//                $sql .= " AND p.address LIKE ? ";
//                $bind [] = "%" . $search ['search_address'] . "%";
//            }
            if (isset ( $search ['search_payment_method'] )) {
                $sql .= " AND p.invoice_type = ? ";
                $bind [] = $search ['search_payment_method'];
            }
            if(isset($search['student_type'])){
                $sql.= "AND s.m_student_type_id = ? ";
                $bind [] = $search ['student_type'];
            }

        }

        $sql.= " GROUP BY p.id";
        $sql .= " ORDER BY p.parent_name ASC,";
        $sql.= " p.id ASC";
        $parent = $this->fetchAll ( $sql, $bind );

        $parent_list = array ();
        if (! empty ( $parent )) {

            foreach ( $parent as $k => $v ) {

                $bind = array (
                        $v ["id"],
                        session ( 'school.login' ) ['id']
                );
                $sql = "SELECT student.*,mt.type,mt.name FROM student" ;
                $sql.= " LEFT JOIN m_student_type mt ON (mt.pschool_id = student.pschool_id AND mt.id = student.m_student_type_id)";
                $sql.= " WHERE " . "student.parent_id = ? " . "AND student.pschool_id = ? " . "AND student.active_flag = 1 " . "AND student.delete_date IS NULL ";

                if (isset ( $search ['school_category'] ) && is_numeric ( $search ['school_category'] )) {
                    $sql .= " AND student.school_category = ? ";
                    $bind [] = $search ['school_category'];
                }
                if (isset ( $search ['school_year'] ) && is_numeric ( $search ['school_year'] )) {
                    $sql .= " AND student.school_year = ? ";
                    $bind [] = $search ['school_year'];
                }

                $sql .= " ORDER BY student_no ASC ";

                $temp_student = $this->fetchAll ( $sql, $bind );
                if(isset($temp_student[0]['student_category']) && $temp_student[0]['student_category']==2){
                    $parent [$k]['hojin']= 1;
                    $parent [$k]['total_member']=$temp_student[0]['total_member'];
                };
                $parent [$k] ["student_list"] = $temp_student;
                $parent_list [] = $parent [$k];
            }
        }
        return $parent_list;
    }
    public function getParentListAndStudentListById($school_id, $parent_id = NULL) {
        $where = array (
                "pschool_id" => $school_id
        );
        if (! is_null ( $parent_id )) {
            $where ["id"] = $parent_id;
        }


        $order = array (
                "name_kana" => "ASC"
        );


        $parent = ParentTable::getInstance ()->getActiveList( $where, $order );

        if (! empty ( $parent )) {
            foreach ( $parent as $k => $v ) {
                $sql = "SELECT " . "s.* " . ", mst.name as student_type_name" . " FROM " . "student s " . " LEFT JOIN m_student_type AS mst ON mst.id = s.m_student_type_id " . " WHERE " . "s.parent_id = ? " . "AND s.pschool_id = ? " . "AND s.active_flag = 1 " . "AND s.delete_date IS NULL " . "ORDER BY " . " s.student_name ASC";
                
                $bind = array (
                        $v ["id"],
                        $school_id 
                );
                $parent [$k] ["student_list"] = $this->fetchAll ( $sql, $bind );
            }
        }
        return $parent;
    }
    public function getParentStudentListById($school_id, $parent_id) {
        $parent = $this->getParentListAndStudentListById ( $school_id, $parent_id );
        return reset ( $parent );
    }
    public function getLoginInfo($loginid, $password, $pschool_id = null) {
        $sql = 'SELECT A.*,B.login_id, B.auth_type as current_auth_type FROM parent A left join login_account B on (A.login_account_id=B.id) WHERE B.login_id=? AND B.login_pw=MD5(?) AND B.active_flag=1 and A.DELETE_DATE IS NULL and B.DELETE_DATE IS NULL';
        $bind = array (
                $loginid,
                $password
        );

        if ($pschool_id !== "" || $pschool_id !== null) {
            $sql .= " AND A.pschool_id = ? ";
            $bind [] = $pschool_id;
        }

        return $this->fetch ( $sql, $bind );
    }
    public function loadWithLoginAccount($id) {
        $sql = "
SELECT A.parent_name as name,A.*,B.login_id,C.name as pref_name,D.name as city_name FROM parent A left join login_account B on (A.login_account_id=B.id) left join m_pref C on (A.pref_id=C.id) left join m_city D on (A.city_id=D.id) WHERE A.id=? AND B.active_flag=1 and A.DELETE_DATE IS NULL and B.DELETE_DATE IS NULL
";
        $bind = array (
                $id 
        );
        return $this->fetch ( $sql, $bind );
    }
    
    /**
     * 保護者の割引・割増取得
     *
     * @param unknown $pschool_id            
     * @param unknown $parent_id            
     */
    public function getParentAdjustList($pschool_id, $parent_id, $month) {
        $bind = array ();
        $bind [] = $pschool_id;
        $bind [] = $parent_id;
        $bind [] = $month;
        
        $sql = " SELECT RP.*, IAN.name ";
        $sql .= " FROM routine_payment AS RP ";
        $sql .= " INNER JOIN invoice_adjust_name AS IAN ";
        $sql .= " ON RP.invoice_adjust_name_id = IAN.id ";
        $sql .= " WHERE RP.delete_date IS NULL ";
        $sql .= " AND RP.active_flag = 1 ";
        $sql .= " AND RP.pschool_id = ? ";
        $sql .= " AND RP.data_div = 3 ";
        $sql .= " AND RP.data_id = ? ";
        $sql .= " AND (RP.month = ? OR RP.month = 99) ";
        
        return $this->fetchAll ( $sql, $bind );
    }
    public function getTimeSendParent($addressee_id,$broadcast_id) {
        /*$bind = array ();
        $sql = "SELECT MAX(time_send) as time_send FROM `broadcast_mail` bm JOIN broadcast_mail_addressee_rel bmar on bmar.broadcast_mail_id = bm.id
        WHERE bmar.addressee_type in (2, 3) and bmar.addressee_id = ?";
        $bind [] = $addressee_id;
        $a = $this->fetch ( $sql, $bind );
        return implode ( "", $a );*/
        $sql = "SELECT mm.send_date 
                FROM mail_message mm 
                WHERE mm.type = 6 "; // => broadcastmail
        $sql.= " AND mm.target = 1 "; // => parent
        $sql.= " AND mm.target_id = '".$addressee_id."' AND mm.relative_ID ='".$broadcast_id."'";
        $a=$this->fetch($sql);
        return $a['send_date'];
    }

    public function getListLabelAll($pschool_id, $arr_search=array())
    {
        $search_name    = isset($arr_search['search_name'])? $arr_search['search_name'] : null;
        $member_ids   = isset($arr_search['member_ids'])? $arr_search['member_ids'] : null;
        $columns        = isset($arr_search['columns'])? $arr_search['columns'] : null;

        return DB::table('parent as p')->leftJoin('m_pref as mp', 'mp.id', DB::raw('IF(p.other_address_flag=1,p.pref_id_other, p.pref_id)'))->leftJoin('m_city as mc', 'mc.id', DB::raw('IF(p.other_address_flag=1,p.city_id_other, p.city_id)'))
            ->where('p.pschool_id', $pschool_id)
            ->when($member_ids, function ($query) use ($member_ids) {
                return $query->whereIn('p.id', $member_ids);
            })
            // check search name like student_name or student_name_kana
            ->when($search_name, function ($query) use ($search_name) {
                return $query->where('p.parent_name', 'like', '%'.$search_name .'%')->orWhere(DB::raw('p.name_kana collate utf8_unicode_ci'), 'like', '%'.$search_name .'%');
            })
            // select filter
            ->when($columns, function ($query) use ($columns) {
                return $query->select(DB::raw(implode(', ', preg_filter('/^/', 'p.', $columns))), DB::raw('IF(p.other_name_flag=1,p.parent_name_other, p.parent_name) as parent_name'), 'mp.name as pref_id', 'mc.name as city_id' ,DB::raw('IF(p.other_address_flag=1,p.address_other, p.address) as address'), DB::raw('IF(p.other_address_flag=1,p.building_other, p.building) as building'), DB::raw('IF(p.other_address_flag=1,concat(\'〒\', p.zip_code1_other, \'-\', p.zip_code2_other), concat(\'〒\', p.zip_code1, \'-\', p.zip_code2)) as zip_code'));
            }, function ($query) {
                return $query->select('p.id', DB::raw('IF(p.other_name_flag=1,p.parent_name_other, p.parent_name) as parent_name'), 'mp.name as pref_id', 'mc.name as city_id', DB::raw('IF(p.other_address_flag=1,p.address_other, p.address) as address'), DB::raw('IF(p.other_address_flag=1,p.building_other, p.building) as building'), DB::raw('IF(p.other_address_flag=1,concat(\'〒\', p.zip_code1_other, \'-\', p.zip_code2_other), concat(\'〒\', p.zip_code1, \'-\', p.zip_code2)) as zip_code'));
            })
//substr(p.zip_code,1,3)  substr(p.zip_code,4)
            ->get();
    }

    public function getParentInfoByStudentId($student_id) {
        return DB::table('parent as p')
            ->select('s.parent_id' ,'p.parent_mailaddress1', 'p.parent_mailaddress2')
            ->join('student as s','s.parent_id', '=', 'p.id')
            ->where('s.id', $student_id)
            ->first();
    }
}