<?PHP

namespace App\Model;

class StudentClassTable extends DbModel {
    
    /**
	 * @var StudentClassTable
	 */
	private static $_instance = null;
    protected $table = 'student_class';

	/**
	 * @return StudentClassTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new StudentClassTable();
		}
		return self::$_instance;
	}
	public function getStudentListNotExists($class_id, $student_name, $pschool_id, $arryQuery=null) {
		$bind = array();
		$sql = "
			select student.*, parent.invoice_type from student
			left join parent on parent.id = student.parent_id
			where
			student.id not in (select student_id from student_class where class_id=? and delete_date is null)
			and student.pschool_id=?
			and student.active_flag=1
			and student.delete_date is null
		";
		$bind[] = $class_id;
		$bind[] = $pschool_id;
		if (!empty($student_name)) {
//			$sql .= " and student_name like ?";
			$sql .= " AND (student_name like ? OR student_name_kana collate utf8_unicode_ci like ?)";
			$bind[] = "%" . $student_name . "%";
			$bind[] = "%" . $student_name . "%";
		}

		if(isset($arryQuery)){
			if(isset($arryQuery['student_no'])){		// 生徒番号
				if( !$this->isEmpty($arryQuery['student_no'])){
					$sql .= " AND student_no LIKE ?";
					$bind[] = "%".$arryQuery['student_no']."%";
				}
			}
			if(isset($arryQuery['school_category']) && is_numeric($arryQuery['school_category'])){		//  学校種別（中学・高校）
				//if( !$this->isEmpty($arryQuery['school_category'])){
					$sql .= " AND school_category = ?";
					$bind[] = $arryQuery['school_category'];
				//}
			}
			if(isset($arryQuery['school_year']) && is_numeric($arryQuery['school_year'])){		// 学年
				//if(!$this->isEmpty($arryQuery['school_year'])){
					$sql .= " AND school_year = ?";
					$bind[] = $arryQuery['school_year'];
				//}
			}
			if(isset($arryQuery['student_type'])){		// 生徒種別
				if(!empty($arryQuery['student_type'])){
					$sql .= " AND m_student_type_id in (".implode(',', $arryQuery['student_type']).")"; // 仕様変更：student_type　m_student_type_id
				}
			}
			if(isset($arryQuery['exam_pref'])){			// 受験地域(都道府県
				if(!$this->isEmpty($arryQuery['exam_pref'])){
					$sql .= " AND id in (select student_id from student_exam_area where pref_id = ?)";
					$bind[] = $arryQuery['exam_pref'];
				}
			}
			if(isset($arryQuery['exam_city'])){			// 受験地域(市区町村）
				if(!$this->isEmpty($arryQuery['exam_city'])){
					$sql .= " AND id in (select student_id from student_exam_area where city_id = ?)";
					$bind[] = $arryQuery['exam_city'];
				}
			}
			if(!empty($arryQuery['invoice_type_search'])){
                $sql .= " AND parent.invoice_type in (".implode(",",$arryQuery['invoice_type_search']).")";
            }
		}

		$sql .=  " order by student_name_kana ASC";

		return $this->fetchAll($sql, $bind);
	}

	// =========================================================================
	// ここから、アクシス柔術向け機能追加版
	// =========================================================================
	public function getStudentListExistsAxis($class_id, $student_name, $pschool_id, $arryQuery=null) {
		$bind = array();
		$sql = "SELECT s.*, cfp.fee_plan_name, cfp.fee, case when s.total_member is null then 1*cfp.fee else s.total_member*cfp.fee end as sum_coop_fee, sc.payment_method as invoice_type, sc.id as student_class_id, sc.number_of_payment, sc.notices_mail_flag, pm.name as payment_method_name, sch.total_fee, case when s.total_member is null then 1*sch.total_fee else s.total_member*sch.total_fee end as sum_coop_total_fee, cfp.payment_unit ";
		$sql .= "FROM student s ";
		$sql .= "INNER JOIN student_class sc ON s.id = sc.student_id AND sc.class_id  = ? AND sc.delete_date IS NULL ";
		$sql .= "LEFT JOIN class_fee_plan cfp ON sc.plan_id = cfp.id ";
		$sql .= "LEFT JOIN payment_method pm ON pm.id = sc.payment_method ";
		$sql .= "LEFT JOIN ( select student_class_id, sum(schedule_fee) as total_fee from class_payment_schedule where delete_date is null group by student_class_id ) sch ON sch.student_class_id = sc.id ";
		$sql .= "WHERE ";
		$sql .= "s.pschool_id = ? ";
		$sql .= "AND s.active_flag = 1 ";
		$sql .= "AND s.delete_date is null ";
		$bind[] = $class_id;
		$bind[] = $pschool_id;
		if (!empty($student_name)) {
			//			$sql .= " and student_name like ?";
			$sql .= " AND (s.student_name like ? OR s.student_name_kana collate utf8_unicode_ci like ?)";
			$bind[] = "%" . $student_name . "%";
			$bind[] = "%" . $student_name . "%";
		}

		if(isset($arryQuery)){
			if(isset($arryQuery['student_no'])){		// 生徒番号
				if( !$this->isEmpty($arryQuery['student_no'])){
					$sql .= " AND s.student_no LIKE ? ";
					$bind[] = "%".$arryQuery['student_no']."%";
				}
			}
			if(isset($arryQuery['school_category']) && is_numeric($arryQuery['school_category'])){		//  学校種別（中学・高校）
				//if( !$this->isEmpty($arryQuery['school_category'])){
				$sql .= " AND s.school_category = ?";
				$bind[] = $arryQuery['school_category'];
				//}
			}
			if(isset($arryQuery['school_year']) && is_numeric($arryQuery['school_year'])){		// 学年
				//if(!$this->isEmpty($arryQuery['school_year'])){
				$sql .= " AND s.school_year = ?";
				$bind[] = $arryQuery['school_year'];
				//}
			}
			if(isset($arryQuery['student_type'])){		// 生徒種別
				if(!empty($arryQuery['student_type'])){
                    $sql .= " AND s.m_student_type_id in (".implode(',', $arryQuery['student_type']).")"; // 仕様変更：student_type　m_student_type_id
				}
			}
			if(isset($arryQuery['exam_pref'])){			// 受験地域(都道府県
				if(!$this->isEmpty($arryQuery['exam_pref'])){
					$sql .= " AND s.id in (select student_id from student_exam_area where pref_id = ?)";
					$bind[] = $arryQuery['exam_pref'];
				}
			}
			if(isset($arryQuery['exam_city'])){			// 受験地域(市区町村）
				if(!$this->isEmpty($arryQuery['exam_city'])){
					$sql .= " AND s.id in (select student_id from student_exam_area where city_id = ?)";
					$bind[] = $arryQuery['exam_city'];
				}
			}
		}

//		$sql .=  " order by s.student_name_kana ASC";
		$sql .=  " order by s.m_student_type_id, s.student_no ";

		return $this->fetchAll($sql, $bind);
	}

	private function isEmpty($param_value = null) {

		$return_cd = false;	// 入力されている
		if(!strlen($param_value)){
			// 空文字
			$return_cd = true;	//未入力
		}

		return $return_cd;
	}

    public function getStudentListExists($class_id, $student_name, $pschool_id, $arryQuery = null) {
        $bind = array ();
        $sql = "
        select * from student where
        id in (select student_id from student_class where class_id=? and delete_date is null)
        and pschool_id=?
        and delete_date is null
        ";
        $bind [] = $class_id;
        $bind [] = $pschool_id;
        if (! empty ( $student_name )) {
            // $sql .= " and student_name like ?";
            $sql .= " AND (student_name like ? OR student_name_kana collate utf8_unicode_ci like ?)";
            $bind [] = "%" . $student_name . "%";
            $bind [] = "%" . $student_name . "%";
        }
        
        if (isset ( $arryQuery )) {
            if (isset ( $arryQuery ['student_no'] )) { // 生徒番号
                if (! $this->isEmpty ( $arryQuery ['student_no'] )) {
                    $sql .= " AND student_no = ?";
                    $bind [] = $arryQuery ['student_no'];
                }
            }
            if (isset ( $arryQuery ['school_category'] ) && is_numeric ( $arryQuery ['school_category'] )) { // 学校種別（中学・高校）
                                                                                                             // if( !$this->isEmpty($arryQuery['school_category'])){
                $sql .= " AND school_category = ?";
                $bind [] = $arryQuery ['school_category'];
                // }
            }
            if (isset ( $arryQuery ['school_year'] ) && is_numeric ( $arryQuery ['school_year'] )) { // 学年
                                                                                                     // if(!$this->isEmpty($arryQuery['school_year'])){
                $sql .= " AND school_year = ?";
                $bind [] = $arryQuery ['school_year'];
                // }
            }
            if (isset ( $arryQuery ['student_type'] )) { // 生徒種別
                if (! empty ( $arryQuery ['student_type'] )) {
                    $sql .= " AND student_type in (" . implode ( ',', array_fill ( 1, count ( $arryQuery ['student_type'] ), '?' ) ) . ")";
                    foreach ( $arryQuery ['student_type'] as $realval ) {
                        $bind [] = $realval;
                    }
                }
            }
            if (isset ( $arryQuery ['exam_pref'] )) { // 受験地域(都道府県
                if (! $this->isEmpty ( $arryQuery ['exam_pref'] )) {
                    $sql .= " AND id in (select student_id from student_exam_area where pref_id = ?)";
                    $bind [] = $arryQuery ['exam_pref'];
                }
            }
            if (isset ( $arryQuery ['exam_city'] )) { // 受験地域(市区町村）
                if (! $this->isEmpty ( $arryQuery ['exam_city'] )) {
                    $sql .= " AND id in (select student_id from student_exam_area where city_id = ?)";
                    $bind [] = $arryQuery ['exam_city'];
                }
            }
        }
        
        $sql .= " order by student_name_kana ASC";
        
        return $this->fetchAll ( $sql, $bind );
    }
    
    public function getClassListByStudentID($student_id = null) {
        $ret = array ();
        if (! empty ( $student_id )) {
            $sql = " SELECT a.class_id, c.class_name FROM student_class a ";
            $sql .= " LEFT JOIN class c ON ( a.class_id = c.id AND c.active_flag = 1 AND c.delete_date is NULL )";
            $sql .= " WHERE a.student_id = ? AND a.active_flag = 1 AND a.delete_date is NULL ";
            $sql .= " ORDER BY a.register_date DESC";
            $bind = array (
                    $student_id 
            );
            $ret = $this->fetchAll ( $sql, $bind );
        }
        return json_decode ( json_encode ( $ret ), true );
        ;
    }

    public function updatePaymentMethodByParent($parent_id, $payment_method) {
        $sql = "UPDATE student_class sc 
                INNER JOIN student s ON sc.student_id = s.id
                INNER JOIN parent p ON p.id = s.parent_id 
                SET sc.payment_method = ? WHERE p.id = ? AND sc.delete_date IS NULL";

        return $this->execute($sql, array($payment_method, $parent_id));
    }
}
