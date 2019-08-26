<?php

namespace App\Model;

use App\Common\Constants;
use App\ConstantsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request as Request;
use App\Model\AdditionalCategoryTable;
use App\Model\AdditionalCategoryRelTable;
use App\Model\StudentPersonInChargeTable;


class StudentTable extends DbModel {
    /**
     *
     * @var StudentTable
     */
    private static $_instance = null;
    
    /**
     *
     * @return StudentTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new StudentTable ();
        }
        return self::$_instance;
    }
    protected $table = 'student';
    public $timestamps = false;

    /**
     * Student_type belongs to student
     */
    public function studentType() {
        return $this->belongsTo('App\Model\MStudentTypeTable',  'm_student_type_id');
    }
    
    /**
     *
     * @return array
     */
    public function getStudentInfo() {
        $arr = array ();
        $arr = DB::table ( 'student' )->select ( 'pschool_id', 'student_name', 'student_phone_no', 'student_handset_no' )->get ();
        
        return json_decode ( json_encode ( $arr ), true );
    }
    public function insertStudent($insert) {
        try {
            DB::beginTransaction ();
            $id = DB::table ( 'student' )->insert ( $insert );
            DB::commit ();
            return 'Success';
        } catch ( Exception $e ) {
            DB::rollBack ();
            return $e;
        }
    }
    
    /**
     *
     * @return array student
     */
    public function getAll() {
        $arr = array ();
        $arr = DB::table ( 'student' )->get ();
        return json_decode ( json_encode ( $arr ), true );
    }
    
    // highest student no
    public function getHighestID() {
        $sql = " SELECT student_no AS student_no FROM {$this->getTableName(true)} ";
        $bind = array ();
        $sql .= ' WHERE active_flag = 1 AND delete_date is null ORDER BY student_no DESC';
        $res = json_decode ( json_encode ( $this->fetchAll ( $sql, $bind ) ), true );
        return $res [0] ['student_no'];
    }
    
    // get student count
    public function getStudentCount($pschool_id) {
        $sql = "SELECT COUNT(*) as count
			FROM student
			WHERE pschool_id = ?";
        $res = $this->fetch ( $sql, array (
                $pschool_id 
        ) );
        return $res ['count'];
    }
    
    // get Count by mail
    public function getCountByMail($id, $mail) {
        $sql = " SELECT count(*) AS cnt FROM {$this->getTableName(true)} ";
        $bind = array ();
        $sql .= ' WHERE id !=? AND mailaddress =? AND active_flag = 1 AND delete_date is null';
        $bind [] = $id;
        $bind [] = $mail;
        $res = $this->fetch ( $sql, $bind );
        return $res ['cnt'];
    }
    
    // Get Student name from id
    public function getName($student_id) {
        $sql = " SELECT student_name FROM {$this->getTableName(true)} WHERE id=";
        $sql .= $student_id;
        $res = $this->fetchAll ( $sql );
        return $res [0];
    }
    
    // get student table with parent_name
    public function getListwithParent($student_id) {
        $sql = " SELECT a.student_no,a.student_name,a.school_name,a.school_year,a.school_category , b.parent_name";
        $sql .= " FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN parent b ON a.parent_id = b.id";
        $sql .= " WHERE a.id=";
        $sql .= $student_id;
        $sql .= " AND a.active_flag = 1 AND a.delete_date is null AND b.delete_date is null";
        $res = $this->fetchAll ( $sql );
        return $res;
    }
    
    // Get Student email from id
    public function getMail($student_id) {
        $sql = " SELECT mailaddress FROM {$this->getTableName(true)} WHERE id=";
        $sql .= $student_id;
        $res = $this->fetchAll ( $sql );
        return $res [0];
    }
    
    // Get parent email from student id
    public function getParentMail($student_id) {
        $sql = " SELECT p.parent_mailaddress1, p.parent_mailaddress2 FROM";
        $sql .= " {$this->getTableName(true)} s AND parent p ON s.parent_id=p.id WHERE s.id=";
        $sql .= $student_id;
        $res = $this->fetchAll ( $sql );
        return $res [0];
    }
    
    // get query list
//     public function getQueryList($arryQuery = null) {
//         $bind = array ();
//         $sql = " SELECT a.*, f.name as pschool_name, g.grade_id , i.workflow_status FROM {$this->getTableName(true)} a";
//         $sql .= " LEFT JOIN pschool f ON a.pschool_id = f.id";
//         $sql .= " LEFT JOIN student_grade_rel g ON ( a.id = g.student_id AND g.active_flag = 1 AND g.delete_date is NULL ) ";
//         $sql .= " LEFT JOIN invoice_header i ON ( a.parent_id = i.parent_id AND i.active_flag = 1 AND i.delete_date is NULL ) ";
//         $sql .= " WHERE a.delete_date is null";
//         $res = array ();
//         if (isset ( $arryQuery )) {
//             // 謾ｯ驛ｨ 2016/01/12
//             if (isset ( $arryQuery ['select_pschool'] )) {
//                 if (! $this->isEmpty ( $arryQuery ['select_pschool'] )) {
    public function getQueryList($arryQuery=null,$sort_cond = null) {
        
        $bind = array();
    
        $sql  = " SELECT a.*, f.name as pschool_name, g.grade_id , i.workflow_status, mst.name AS student_type_name FROM {$this->getTableName(true)} a";
        $sql .= " LEFT JOIN pschool f ON a.pschool_id = f.id";
        $sql .= " LEFT JOIN student_grade_rel g ON ( a.id = g.student_id AND g.active_flag = 1 AND g.delete_date is NULL ) ";
        $sql .= " LEFT JOIN invoice_header i ON ( a.parent_id = i.parent_id AND i.active_flag = 1 AND i.delete_date is NULL";
        if(!isset($arryQuery['workflow_status'])){
            $sql .= " AND i.register_date = (SELECT MAX(register_date) FROM invoice_header)";
        }
        $sql .= " ) ";
        $sql .= " LEFT JOIN m_student_type mst ON (mst.id = a.m_student_type_id AND mst.delete_date IS NULL )";
        $sql .= " WHERE a.delete_date is null";
    
        $res = array();
        if(isset($arryQuery)){
            // 支部           2016/01/12
            if(isset($arryQuery['select_pschool'])){
                if(!$this->isEmpty($arryQuery['select_pschool'])){
                    $sql .= " AND a.pschool_id in ( ? )";
                    $bind[] = $arryQuery['select_pschool'];
                }
            }
            elseif (isset($arryQuery['pschool_id'])){
                if(!$this->isEmpty($arryQuery['pschool_id'])){
                    $sql .= " AND (a.pschool_id = ? )";
                    $bind[] = $arryQuery['pschool_id'];
                }
            }
            if(isset($arryQuery['input_search'])){      // 生徒名（漢字またはカナ）
                if(!$this->isEmpty($arryQuery['input_search'])){
                    $sql .= " AND (a.student_name like ? OR a.student_name_kana collate utf8_unicode_ci like ?)";
                    $bind[] = "%".$arryQuery['input_search']."%";
                    $bind[] = "%".$arryQuery['input_search']."%";
                }
            }
            if(isset($arryQuery['input_search_student_no'])){       // 生徒名（漢字またはカナ）
                if(!$this->isEmpty($arryQuery['input_search_student_no'])){
                    $sql .= " AND (a.student_no LIKE ? )";
                    $bind[] = "%".$arryQuery['input_search_student_no']."%";
                }
            }
            if(isset($arryQuery['input_search_student_type'])){       // 生徒名（漢字またはカナ）
                if(!$this->isEmpty($arryQuery['input_search_student_type'])){
                    $sql .= " AND (a.m_student_type_id = ? )";
                    $bind[] = $arryQuery['input_search_student_type'];
                }
            }
            if(isset($arryQuery['school_category']) && is_numeric($arryQuery['school_category'])){      //  学校種別（中学・高校）
                //if( !$this->isEmpty($arryQuery['school_category'])){
                $sql .= " AND a.school_category = ?";
                $bind[] = $arryQuery['school_category'];
                //}
            }
            
            if(isset($arryQuery['school_year']) && is_numeric($arryQuery['school_year'])){      // 学年
                //if(!$this->isEmpty($arryQuery['school_year'])){
                $sql .= " AND a.school_year = ?";
                $bind[] = $arryQuery['school_year'];
                //}
            }
            if(isset($arryQuery['student_type'])){      // 生徒種別     2015/04/03
                if(!empty($arryQuery['student_type'])){
                    $sql .= " AND a.m_student_type_id in (".implode(',', array_fill(1, count($arryQuery['student_type']), '?')).")";
                    foreach ($arryQuery['student_type'] as $realval) {
                        $bind[] = $realval;
                    }
                }
            }
            
            if(isset($arryQuery['exam_pref'])){         // 受験地域(都道府県）       2015/04/03
                if(!$this->isEmpty($arryQuery['exam_pref'])){
                    $sql .= " AND a.id in (select student_id from student_exam_area where pref_id = ?)";
                    $bind[] = $arryQuery['exam_pref'];
                }
            }
            if(isset($arryQuery['exam_city'])){         // 受験地域(市区町村）       2015/04/03
                if(!$this->isEmpty($arryQuery['exam_city'])){
                    $sql .= " AND a.id in (select student_id from student_exam_area where city_id = ?)";
                    $bind[] = $arryQuery['exam_city'];
                }
            }
            if(isset($arryQuery['class_id'])){          // プラン名         2015/04/03
                if(!$this->isEmpty($arryQuery['class_id'])){
                    $sql .= " AND a.id in (select student_id from student_class where class_id = ?)";
                    $bind[] = $arryQuery['class_id'];
                }
            }
            if(isset($arryQuery['active_flag']) && $arryQuery['active_flag']!=2){          // プラン名         2015/04/03
                    $sql .= " AND a.active_flag = ? ";
                    $bind[] = $arryQuery['active_flag'];
            }
    
            // フリーワード           2016/01/12
            if(isset($arryQuery['select_word'])){
                if(!$this->isEmpty($arryQuery['select_word'])){
                    $sqls = array();
                    // 生徒名漢字
                    $sqls[] = " a.student_name like ? ";
                    $bind[] = "%".$arryQuery['select_word']."%";
                    // 生徒名カナ
                    $sqls[] = " a.student_name_kana collate utf8_unicode_ci like ? ";
                    $bind[] = "%".$arryQuery['select_word']."%";
                    // 生徒名ローマ字
                    $sqls[] = " a.student_romaji like ? ";
                    $bind[] = "%".$arryQuery['select_word']."%";
                    // 生徒番号
                    $sqls[] = " CONCAT(?, RIGHT( CONCAT('0000', a.student_no), 4)) like ? ";
                    $bind[] = $this->getPschoolCardCode($arryQuery['session']);
                    $bind[] = "%".$arryQuery['select_word']."%";
                    // 支部名
                    $sqls[] = " a.pschool_id in (select id from pschool where name like ?)";
                    $bind[] = "%".$arryQuery['select_word']."%";
                    // プラン名
                    $sqls[] = " a.id in (select sc.student_id from class as cc left join student_class as sc on sc.class_id = cc.id AND sc.active_flag = 1 AND sc.delete_date is NULL WHERE cc.class_name like ? AND cc.active_flag = 1 AND cc.delete_date is NULL )";
                    $bind[] = "%".$arryQuery['select_word']."%";
    
                    $sql .= " AND ( " . implode("OR", $sqls) . " )";
                }
            }
    
            // 帯色           2016/01/12
            if(isset($arryQuery['select_grade'])){
                if(!$this->isEmpty($arryQuery['select_grade'])){
                    $sql .= " AND a.id in (select student_id from student_grade_rel where grade_id = ? AND active_flag = 1 AND delete_date is NULL )";
                    $bind[] = $arryQuery['select_grade'];
                }
            }
    
            // ステータス        2016/01/12
            //$states = array('1'=>'受講中', '2'=>'休校中', '9'=>'退会');
            if(isset($arryQuery['select_state'])){
                if(!$this->isEmpty($arryQuery['select_state'])){
                    if ($arryQuery['select_state'] == 1){
                        $sql .= " AND ( (a.active_flag = 1 AND a.resign_date is NULL) OR (a.active_flag = 1 AND a.resign_date > NOW()) )";
                    }
                    elseif ($arryQuery['select_state'] == 2){
                        $sql .= " AND a.active_flag != 1 AND a.resign_date is NULL ";
                    }
                    elseif ($arryQuery['select_state'] == 9){
                        $sql .= " AND a.active_flag = 0 ";
                    }
                }
            }
            // 最新請求情報       2017/03/14
            if(isset($arryQuery['workflow_status'])){
                if(!$this->isEmpty($arryQuery['workflow_status'])){
                    $sql .= " AND i.workflow_status = ?";
                    $bind[] = $arryQuery['workflow_status'];
                }
            }
//            elseif (! isset ( $arryQuery ['active_flag'] )) {
//                $sql .= " AND a.active_flag != 0 ";
//            }
            if(isset($arryQuery['register_date'])){
                $sql .= " AND a.register_date like ?";
                $bind[] = $arryQuery['register_date']."%";
            }
            if(isset($arryQuery['update_date'])){
                $sql .= " AND a.update_date like ?";
                $bind[] = $arryQuery['update_date']."%";
            }

            if (isset($arryQuery['from_register_date'])) {
                $sql .= ' AND DATE(a.register_date) >= ?';
                $bind[] = $arryQuery['from_register_date'];
            }

            if (isset($arryQuery['to_register_date'])) {
                $sql .= ' AND DATE(a.register_date) <= ?';
                $bind[] = $arryQuery['to_register_date'];
            }

            if (isset($arryQuery['from_update_date'])) {
                $sql .= ' AND DATE(a.update_date) >= ?';
                $bind[] = $arryQuery['from_update_date'];
            }

            if (isset($arryQuery['to_update_date'])) {
                $sql .= ' AND DATE(a.update_date) <= ?';
                $bind[] = $arryQuery['to_update_date'];
            }

            if (isset($arryQuery['valid_date_from'])) {
                $sql .= ' AND DATE(a.valid_date) >= ?';
                $bind[] = $arryQuery['valid_date_from'];
            }

            if (isset($arryQuery['valid_date_to'])) {
                $sql .= ' AND DATE(a.valid_date) <= ?';
                $bind[] = $arryQuery['valid_date_to'];
            }
        }
        // $sql .= ' GROUP BY a.id';

        if(isset($sort_cond)){
            $sql .= ' ORDER BY '.implode(' ',$sort_cond);
        }else{
            $sql .= ' ORDER BY a.student_name_kana ASC';
        }
        
//        dd($sql,$bind);
        $res = $this->fetchAll($sql, $bind );

        return $res;
    }
    private function isEmpty($param_value = null) {
        $return_cd = false; // 蜈･蜉帙＆繧後※縺�繧�
        if (! strlen ( $param_value )) {
            // 遨ｺ譁�蟄�
            $return_cd = true; // 譛ｪ蜈･蜉�
        }

        return $return_cd;
    }
    public function getPschoolCardCode($session = null) {
        $parent_id = StudentGradeTable::getInstance ()->getParentID ( $session ['id'] );
        $parent_school = PschoolTable::getInstance ()->load ( $parent_id );
        // 譛ｬ驛ｨ繧ｳ繝ｼ繝�4譯�
        $parent_school ['pschool_code'] = empty ( $parent_school ['pschool_code'] ) ? $parent_school ['id'] : $parent_school ['pschool_code'];
        $code4 = sprintf ( '%04d', substr ( $parent_school ['pschool_code'], 0, 4 ) );
        // 謾ｯ驛ｨ繧ｳ繝ｼ繝�3譯�
        $session ['pschool_code'] = empty ( $session ['pschool_code'] ) ? $session ['id'] : $session ['pschool_code'];
        $code3 = sprintf ( '%03d', substr ( $session ['pschool_code'], 0, 3 ) );
        
        return $code4 . $code3;
    }
    public function getTimeSendStudent($addressee_id,$broadcast_id) {
        /*$bind = array ();
        $sql = "SELECT MAX(time_send) as time_send FROM `broadcast_mail` bm JOIN broadcast_mail_addressee_rel bmar on bmar.broadcast_mail_id = bm.id
        WHERE bmar.addressee_type = 1 and bmar.addressee_id = ?";
        $bind [] = $addressee_id;
        $a = $this->fetch ( $sql, $bind );
        return implode ( "", $a );*/
        $sql = "SELECT mm.send_date 
                FROM mail_message mm 
                WHERE mm.type = 6 "; // => broadcastmail
        $sql.= " AND mm.target = 2 "; // => student
        $sql.= " AND mm.target_id = '".$addressee_id."' AND mm.relative_ID ='".$broadcast_id."'";
        $a=$this->fetch($sql);
        return $a['send_date'];
    }

    public function getStudentParentInfo($filters, $returnList = false) {
        $columns = array(
            's.*',
            'p.parent_name',
            'p.parent_name_hiragana',
            'p.login_account_id',
            'p.login_pw',
            'p.name_kana',
            'p.parent_mailaddress1',
            'p.parent_mailaddress2',
            'p.pref_id',
            'p.city_id',
            'p.zip_code1',
            'p.zip_code2',
            'p.address',
            'p.building',
            'p.phone_no',
            'p.handset_no',
            'p.invoice_type',
            'p.mail_infomation',
            'p.memo',
            'pba.id AS parent_bank_account_id',
            'pba.bank_code',
            'pba.bank_type',
            'pba.bank_name',
            'pba.branch_code',
            'pba.branch_name',
            'pba.bank_account_type',
            'pba.bank_account_number',
            'pba.bank_account_name',
            'pba.bank_account_name_kana',
            'pba.post_account_kigou',
            'pba.post_account_number',
            'pba.post_account_name',
            'pba.post_account_type',
            'lat.id AS login_account_temp_id',
            'smp.name AS student_pref_name',
            'smc.name AS student_city_name',
            'pmp.name AS parent_pref_name',
            'pmc.name AS parent_city_name',
            'mst.name AS student_type_name'
        );
        $query = DB::table('student AS s')
            ->select(DB::raw(implode(',', $columns)))
            ->join('parent AS p', 'p.id', '=', 's.parent_id')
            ->leftJoin('login_account_temp AS lat', 'lat.id', '=', 'p.login_account_id')
            ->leftJoin('parent_bank_account AS pba', 'pba.parent_id', '=', 'p.id')
            ->leftJoin('m_pref AS smp', 'smp.id', '=', 's._pref_id')
            ->leftJoin('m_city AS smc', 'smc.id', '=', 's._city_id')
            ->leftJoin('m_pref AS pmp', 'pmp.id', '=', 'p.pref_id')
            ->leftJoin('m_city AS pmc', 'pmc.id', '=', 'p.city_id')
            ->leftJoin('m_student_type AS mst', 'mst.id', '=', 's.m_student_type_id')
            ->whereNull('s.delete_date')
            ->whereNull('p.delete_date') ;

        if (isset($filters['student_id'])) {
            $query->where('s.id', $filters['student_id']);
        }

        if (isset($filters['pschool_id'])) {
            $query->where('s.pschool_id', $filters['pschool_id']);
        }

        if (isset($filters['pschool_id'])) {
            $query->where('s.pschool_id', $filters['pschool_id']);
        }

        if ($returnList) {
            return $this->convertToArray($query->get());
        }
        return $this->convertToArray($query->first());
    }

    public function getAutoGenerateStudentNo() {
        $student_count = StudentTable::getInstance()->getStudentCount(session('school.login.id'));
        $student_code = sprintf("%05d", $student_count + 1);
        return session('school.login.prefix_code') . '-' . $student_code;
    }

    public function createStudentWithParent(Request $request) {
        try {
            //Database process
            DB::beginTransaction();

            //check parent is exist or not
//            if(isset( $request->parent_mailaddress1)){
//                $parent = DB::table('parent')->select('id')->where('parent_mailaddress1','=',$request->parent_mailaddress1)->get();
//                if(!empty($parent)){
//                    $request->offsetSet('parent_id',$parent[0]->id);
//                }
//            }
            // Create Login account
            $loginAccountStudent = [
                    'login_id' => $request->mailaddress,
                    'login_pw' => md5($request->student_pass),
                    'auth_type' => ConstantsModel::$LOGIN_AUTH_STUDENT,
                    'active_flag' => 1,
                    'pschool_id' => session('school.login.id'),
                    'register_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'register_admin' => session('school.login.login_account_id'),
                    'update_admin' => session('school.login.login_account_id'),
                    'lang_code' => session('school.login.language')
            ];
            $loginStudentId = DB::table('login_account')->insertGetId($loginAccountStudent);

            //Update or Create student
            $studentData = [
                'id' => $request->id,
                'pschool_id' => session('school.login.id' ),
                'parent_id' => $request->parent_id,
                'student_no' => $request->student_no,
                'm_student_type_id' => $request->m_student_type_id,
                'student_name_kana' => $request->student_name_kana,
                'student_romaji' => $request->student_romaji,
                'student_name' => $request->student_name,
                'student_name_hiragana' => $request->student_name_hiragana,
                'login_account_id' => $loginStudentId,
                'login_pw' => md5($request->student_pass),
                'mailaddress' => $request->mailaddress,
                'birthday' => ($request->student_category == ConstantsModel::$MEMBER_CATEGORY_PERSONAL) ? $request->birthday : null,
                'sex' => ($request->student_category == ConstantsModel::$MEMBER_CATEGORY_PERSONAL) ? $request->sex : null,
                'zip_code' => $request->student_zip_code1 . $request->student_zip_code2,
                'student_zip_code1' => $request->student_zip_code1,
                'student_zip_code2' => $request->student_zip_code2,
                '_pref_id' => $request->_pref_id,
                '_city_id' => $request->_city_id,
                'parent_mailaddress1' => $request->parent_mailaddress1,
                'parent_name' => $request->parent_name,
                'active_flag' => 1,
                'memo1' => $request->memo1,
                'student_address' => $request->student_address,
                'student_building' => $request->student_building,
                'student_phone_no' => $request->student_phone_no,
                'student_handset_no' => $request->student_handset_no,
                'enter_memo' => $request->enter_memo,
                'enter_date' => $request->enter_date,
                'resign_memo' => $request->resign_memo,
                'valid_date' => $request->valid_date,
                'student_category' => $request->student_category,
                'total_member' => ($request->student_category == ConstantsModel::$MEMBER_CATEGORY_CORP) ? $request->total_member : null,
                'update_date' => date('Y-m-d H:i:s'),
                'update_admin' => session('school.login.login_account_id'),
                'representative_name' => $request->representative_name,
                'representative_name_kana' => $request->representative_name_kana,
                'representative_position' => $request->representative_position,
                'representative_email' => $request->representative_email,
                'representative_send_mail_flag' => $request->representative_send_mail_flag,
                'representative_tel' => $request->representative_tel
            ];
            //Set register date and student no for new student
            if (!$request->has('id')) {
//                $studentData['student_no'] = $this->getAutoGenerateStudentNo();
                $studentData['register_date'] = date('Y-m-d H:i:s');
            }

            //Change student status and do not update others field
//            if ($request->student_state == ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT) {
//                $studentData['active_flag'] = 1;
//                // 退会日は本日に設定したら、すぐ契約終了にする
//                if ($request->has('resign_date') && $request->resign_date <= date('Y-m-d')) {
//                    $studentData['active_flag'] = 0;
//                    $studentData['resign_date'] = date('Y-m-d');
//                } elseif ($request->has('resign_date') && $request->resign_date > date('Y-m-d')){
//                    $studentData['active_flag'] = 1;
//                    $studentData['resign_date'] = $request->resign_date;
//                }elseif(!$request->has('resign_date')){
//                    $studentData['resign_date']=null;
//                }
//            } elseif ($request->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
//                $studentData['active_flag'] = 0;
//                $studentData['resign_date'] = date('Y-m-d');
//            }
            if ($request->student_state == ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT) {
                $studentData['active_flag'] = 1;
            } elseif ($request->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                $studentData['active_flag'] = 0;
            }
            $studentData['resign_date'] = $request->resign_date;

            //Set image data
            if ($request->offsetExists('student_img_file') && $request->file('student_img_file')->isValid()) {
                $imageName = time() . '.' . $request->student_img_file->getClientOriginalExtension();
                $dir = 'schools/' . session('school.login.id') . '/students';
                $path = $request->student_img_file->storeAs($dir, $imageName, 'uploads');
                $studentData['student_img'] = $path;
            }

            //Set parent data
            if (!$request->has('parent_id')) {
                $loginAccountData = [
                    'login_id' => $request->parent_mailaddress1,
                    'login_pw' => md5($request->parent_pass),
                    'auth_type' => ConstantsModel::$LOGIN_AUTH_PARENT,
                    'active_flag' => 1,
                    'pschool_id' => session('school.login.id'),
                    'register_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'register_admin' => session('school.login.login_account_id'),
                    'update_admin' => session('school.login.login_account_id'),
                    'lang_code' => session('school.login.language')
                ];
                $loginAccountTempData = [
                    'login_id' => $request->parent_mailaddress1,
                    'login_pw_base64' => base64_encode($request->parent_pass),
                    'register_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'register_admin' => session('school.login.login_account_id'),
                ];
                $parentData = [
                    'pschool_id' => session('school.login.id'),
                    'login_pw' => md5($request->parent_pass),
                    'parent_name' => $request->parent_name,
                    'parent_name_hiragana' => $request->parent_name_hiragana,
                    'name_kana' => $request->name_kana,
                    'parent_mailaddress1' => $request->parent_mailaddress1,
                    'parent_mailaddress2' => $request->parent_mailaddress2,
                    'pref_id' => $request->pref_id,
                    'city_id' => $request->city_id,
                    'zip_code1' => $request->zip_code1,
                    'zip_code2' => $request->zip_code2,
                    'zip_code' => $request->zip_code1 . $request->zip_code2,
                    'address' => $request->address,
                    'building' => $request->building,
                    'phone_no' => $request->phone_no,
                    'invoice_type' => $request->invoice_type,
                    'mail_infomation' => $request->mail_infomation,
                    'handset_no' => $request->handset_no,
                    'memo' => $request->memo,
                    'register_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'register_admin' => session('school.login.login_account_id'),
                    'update_admin' => session('school.login.login_account_id'),
                ];

                //Save data
                $loginAccountId = DB::table('login_account')->insertGetId($loginAccountData);
                $loginAccountTempData['login_account_id'] = $loginAccountId;
                DB::table('login_account_temp')->insertGetId($loginAccountTempData);
                $parentData['login_account_id'] = $loginAccountId;
                if ($request->has('get_parent_id')) {
                    $parentId=$request->get_parent_id;
                }else {
                    $parentId = DB::table('parent')->insertGetId($parentData);
                }
                $studentData['parent_id'] = $parentId;
                //Save parent bank account
                if ($request->has('have_payment_info') && $request->invoice_type != Constants::$PAYMENT_TYPE['CASH'] && $request->invoice_type != Constants::$PAYMENT_TYPE['TRAN_BANK']) {
                    $bankAccountData = [
                        'parent_id'              => $parentId,
                        'bank_code'              => $request->bank_code,
                        'bank_type'              => $request->bank_type,
                        'bank_name'              => $request->bank_name,
                        'branch_code'            => $request->branch_code,
                        'branch_name'            => $request->branch_name,
                        'bank_account_type'      => $request->bank_account_type,
                        'bank_account_number'    => $request->bank_account_number,
                        'bank_account_name'      => $request->bank_account_name,
                        'bank_account_name_kana' => $request->bank_account_name_kana,
                        'post_account_kigou'     => $request->post_account_kigou,
                        'post_account_number'    => $request->post_account_number,
                        'post_account_name'      => $request->post_account_name,
                        'register_date' => date('Y-m-d H:i:s'),
                        'update_date' => date('Y-m-d H:i:s'),
                        'register_admin' => session('school.login.login_account_id'),
                        'update_admin' => session('school.login.login_account_id'),
                    ];
                    DB::table('parent_bank_account')->insertGetId($bankAccountData);
                }

                //Save routine payment
                $routinePaymentData = [];
                if ($request->has('have_payment_adjust') && $request->payment) {
                    foreach ($request->payment as $k => $payment) {
                        $routinePaymentData[] = [
                            'pschool_id' => session('school.login.id'),
                            'data_div' => ConstantsModel::$DISCOUNT_PARENT,
                            'data_id' => $parentId,
                            'month' => $payment['month'],
                            'invoice_adjust_name_id' => $payment['invoice_adjust_name_id'],
                            'adjust_fee' => $payment['adjust_fee'],
                            'active_flag' => 1,
                            'register_date' => date('Y-m-d H:i:s'),
                            'update_date' => date('Y-m-d H:i:s'),
                            'register_admin' => session('school.login.login_account_id'),
                            'update_admin' => session('school.login.login_account_id'),
                        ];
                    }
                }
                if ($routinePaymentData) {
                    DB::table('routine_payment')->insert($routinePaymentData);
                }
            }

            // 2017-08-16 THANGQG add other info for print label
            // 送付先宛名
            $studentData['other_name_flag'] = $request->other_name_flag;
            if ($request->other_name_flag == 1) {
                $studentData['student_name_other'] = $request->student_name_other;
            }
            // 送付先住所
            $studentData['other_address_flag'] = $request->other_address_flag;
            if ($request->other_address_flag == 1) {
                $studentData['zip_code1_other']         = $request->zip_code1_other;
                $studentData['zip_code2_other']         = $request->zip_code2_other;
                $studentData['pref_id_other']           = $request->pref_id_other;
                $studentData['city_id_other']           = $request->city_id_other;
                $studentData['student_address_other']   = $request->student_address_other;
                $studentData['student_building_other']  = $request->student_building_other;
            }
            // END -- 2017-08-16 THANGQG add other info for print label

            $studentId = $this->save($studentData);
            $routinePaymentTable = RoutinePaymentTable::getInstance();
            //Save routine payment student
            $routinePaymentStudent = [];
            if ($request->has('have_payment_adjust_student')) {
                foreach ($request->payment_student as $k => $payment_student) {
                    $routinePaymentStudent = array(
                            'pschool_id' => session()->get('school.login.id'),
                            'data_div' => ConstantsModel::$DISCOUNT_STUDENT,
                            'data_id' => $studentId,
                            'month' => $payment_student['month'],
                            'invoice_adjust_name_id' => $payment_student['invoice_adjust_name_id'],
                            'adjust_fee' => $payment_student['adjust_fee'],
                            'active_flag' => 1,
                            'register_date' => date('Y-m-d H:i:s'),
                            'update_date' => date('Y-m-d H:i:s'),
                            'register_admin' => session('school.login.login_account_id'),
                            'update_admin' => session('school.login.login_account_id'),
                    );
                    if(isset($payment_student['payment_id'])){
                        $routinePaymentStudent['id'] = $payment_student['payment_id'];
                    }
                    if ($routinePaymentStudent) {
                        //DB::table('routine_payment')->insert($routinePaymentStudent);
                        $routinePaymentTable->save($routinePaymentStudent);
                    }
                }
                $request->offsetUnset('payment_student');
            }
            if($request->has('payment_student_delete')){
                foreach ($request->payment_student_delete as $key=>$value){
                    if($value == 1){
                        DB::table('routine_payment')->where('id', '=', $key)->update(['delete_date' => date('Y-m-d H:i:s')]);
                    }
                }
                $request->offsetUnset('payment_student_delete');
            }

            //get list id of person_in_charge
            $old_person_list = StudentPersonInChargeTable::getInstance()->getActiveList(array('student_id' => $request->id));
            $old_person_id_list = array_column($old_person_list, 'id');

            //Save person_in_charge of corporation
            if ($request->has('person_in_charge')) {
                foreach ($request->person_in_charge as $idx => $person) {
                    $corporationPerson = array (
                        'student_id'            => $studentId,
                        'person_name'           => $person['person_name'],
                        'person_name_kana'      => $person['person_name_kana'],
                        'person_position'       => $person['person_position'],
                        'person_office_name'    => $person['person_office_name'],
                        'person_office_tel'     => $person['person_office_tel'],
                        'person_email'          => $person['person_email'],
                        'check_send_mail_flag'  => isset($person['check_send_mail_flag']) ? $person['check_send_mail_flag'] : 0,
                    );

                    if (!empty($person['id'])) { // edit
                        $corporationPerson['id']            = $person['id'];
                        $corporationPerson['update_date']   = date('Y-m-d H:i:s');
                        $corporationPerson['update_admin']  = session('school.login.login_account_id');
                        $old_key_remove = array_search($person['id'], $old_person_id_list );
                        unset($old_person_id_list[$old_key_remove]);
                    } else { // register
                        $corporationPerson['register_date']     = date('Y-m-d H:i:s');
                        $corporationPerson['register_admin']    = session('school.login.login_account_id');
                    }
                    StudentPersonInChargeTable::getInstance()->save($corporationPerson);
                }
                $request->offsetUnset('person_in_charge');
            }
            if (count($old_person_id_list) > 0) {
                DB::table('student_person_in_charge')->whereIn('id', $old_person_id_list)->update(['delete_date' => date('Y-m-d H:i:s')]);
            }

            //Additional category
            if (!$request->has('student_state') || $request->student_state == ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT) {
                $additionalCategories = AdditionalCategoryTable::getInstance()->getListData(['type' => ConstantsModel::$ADDITIONAL_CATEGORY_STUDENT, 'related_id' => $request->id], true);
                foreach ($additionalCategories as $category) {
                    $code = $category['code'];
                    //Only add data if code exist
                    if ($request->has('additional.' . $code)) {
                        $additionalCategoryRelData = [
                            'id' => $category['additional_category_rel_id'],
                            'additional_category_id' => $category['id'],
                            'pschool_id' => session('school.login.id' ),
                            'type' => ConstantsModel::$ADDITIONAL_CATEGORY_STUDENT,
                            'related_id' => $studentId,
                            'value' => $request->additional[$code],
                            'update_date' => date('Y-m-d H:i:s'),
                            'update_admin' => session('school.login.login_account_id')
                        ];
                        if ($category['additional_category_rel_id'] == null) {
                            $additionalCategoryRelData['register_admin'] = session('school.login.login_account_id');
                            $additionalCategoryRelData['register_date'] = date('Y-m-d H:i:s');
                        }
                        AdditionalCategoryRelTable::getInstance()->save($additionalCategoryRelData);
                    }
                }
            }

            DB::commit();
            return $studentId;

        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    // 2017-07-13　退会日を過ぎたら自動的に契約終了にする
    function updateResignStatus() {
        DB::table('student')
            ->where('active_flag', 1)
            ->where('resign_date', '<=', date('Y-m-d'))
            ->update(['active_flag' => 0,
                'update_date' => date('Y-m-d H:i:s')]);
    }

    public function getListLabelAll($pschool_id, $arr_search=array())
    {

        $search_name    = isset($arr_search['search_name'])? $arr_search['search_name'] : null;
        $search_state   = isset($arr_search['search_state'])? $arr_search['search_state'] : null;
        $m_student_type_id   = isset($arr_search['m_student_type'])? $arr_search['m_student_type'] : null;
        $member_ids     = isset($arr_search['member_ids'])? $arr_search['member_ids'] : null;
        $columns        = isset($arr_search['columns'])? $arr_search['columns'] : null;

            return  DB::table('student as s')->leftJoin('m_pref as mp', 'mp.id', DB::raw('IF(s.other_address_flag=1,s.pref_id_other, s._pref_id)'))->leftJoin('m_city as mc', 'mc.id', DB::raw('IF(s.other_address_flag=1,s.city_id_other, s._city_id)'))->leftJoin('m_student_type as mst', 'mst.id', 's.m_student_type_id')
            ->where('s.pschool_id', $pschool_id)
            ->when($member_ids, function ($query) use ($member_ids) {
                return $query->whereIn('s.id', $member_ids);
            })
            //check search name like student_name or student_name_kana
            ->when($search_name, function ($query) use ($search_name) {
                return $query->where(function($query) use ($search_name) {
                            $query->where('s.student_name', 'like', '%'.$search_name .'%')
                                    ->orWhere(DB::raw('s.student_name_kana collate utf8_unicode_ci'), 'like', '%'.$search_name .'%');
                });
            })
            //check m_student_type
            ->when($m_student_type_id, function ($query) use ($m_student_type_id){
                return $query->where('s.m_student_type_id', '=' , $m_student_type_id );
            })
            // check search_state [1 : active_flag != 0 & resign_date is null, 9 : resign_date is not null]
            ->when($search_state, function ($query) use ($search_state) {
                return $query->when($search_state == 1, function ($query) use ($search_state) {
                    return $query->where('s.active_flag', '!=', 0)->whereNull('s.resign_date');
                })->when($search_state == 9, function ($query) use ($search_state) {
                    return $query->whereNotNull('s.resign_date');
                });
            }, function ($query) {
                return $query->where('s.active_flag', '!=', 0);
            })
            // select filter
            ->when($columns, function ($query) use ($columns) {
                return $query->select(DB::raw(implode(', ', preg_filter('/^/', 's.', $columns))), 'mp.name as _pref_id', 'mc.name as _city_id', 's.m_student_type_id as m_student_type_id', DB::raw('IF(s.other_name_flag=1,s.student_name_other, s.student_name) as student_name'), DB::raw('IF(s.other_address_flag=1,s.student_address_other, s.student_address) as student_address'), DB::raw('IF(s.other_address_flag=1,s.student_building_other, s.student_building) as student_building'), DB::raw('IF(s.other_address_flag=1,concat(\'〒\', s.zip_code1_other, \'-\', s.zip_code2_other),concat(\'〒\', s.student_zip_code1, \'-\', s.student_zip_code2)) as zip_code'));
            }, function ($query) {
                return $query->select('s.id', 's.student_no', DB::raw('IF(s.other_name_flag=1,s.student_name_other, s.student_name) as student_name'), 'mp.name as _pref_id', 'mc.name as _city_id', DB::raw('IF(s.other_address_flag=1,s.student_address_other, s.student_address) as student_address'), DB::raw('IF(s.other_address_flag=1,s.student_building_other, s.student_building) as student_building'), 's.m_student_type_id as m_student_type_id', DB::raw('IF(s.other_address_flag=1,concat(\'〒\', s.zip_code1_other, \'-\', s.zip_code2_other),concat(\'〒\', s.student_zip_code1, \'-\', s.student_zip_code2)) as zip_code'));

            })
            ->get();
    }
    public function getParentPaymentMethod($student_id){
        $sql = "SELECT parent.invoice_type FROM student
                LEFT JOIN parent ON student.parent_id = parent.id
                WHERE student.id = ".$student_id."
                AND parent.delete_date IS NULL 
                AND student.delete_date IS NULL
                AND student.active_flag = 1
                AND student.resign_date IS NULL OR  student.resign_date >=  NOW()";
        return $this->fetch($sql);
    }
    public function getLoginInfo($loginid, $password, $pschool_id = null) {
        $sql = 'SELECT A.*,B.login_id, B.auth_type as current_auth_type FROM student A left join login_account B on (A.login_account_id=B.id) WHERE B.login_id=? AND B.login_pw=MD5(?) AND B.active_flag=1 and A.DELETE_DATE IS NULL and B.DELETE_DATE IS NULL';
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

    public function getStudentInfoByID($loginid, $password, $pschool_id = null) {
        $sql = 'SELECT A.*,B.login_id, B.auth_type as current_auth_type FROM student A left join login_account B on (A.login_account_id=B.id) WHERE B.login_id=? AND B.login_pw=? AND B.active_flag=1 and A.DELETE_DATE IS NULL and B.DELETE_DATE IS NULL';
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

    public function getOnlyStudentInfoBroadcastMail($id) {

        $sql = 'SELECT S.*
                FROM student S
                WHERE S.id=? 
                AND S.DELETE_DATE IS NULL';
        $bind = array (
            $id
        );
        return $this->fetch ( $sql, $bind );
    }

    public function getMailAddressAllMemberByStudentInfoID($student_id) {

        $sql = 'SELECT S.*, SC.* 
                FROM student S 
                left join student_person_in_charge SC on (S.id=SC.student_id) 
                WHERE S.id=? 
                AND S.DELETE_DATE IS NULL 
                AND SC.DELETE_DATE IS NULL
                AND (
                S.representative_send_mail_flag = 1 
                OR SC.check_send_mail_flag = 1 
                )';
        $bind = array (
            $student_id
        );
        return $this->fetchAll ( $sql, $bind );
    }
}
