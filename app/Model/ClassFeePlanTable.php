<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClassFeePlanTable extends DbModel
{
    /**
	 * @var ClassFeePlanTable
	 */
	private static $_instance = null;
	protected $table = 'class_fee_plan';

	/**
	 * @return ClassFeePlanTable
	 */
	public static function getInstance(){
		if( is_null( self::$_instance ) ){
			self::$_instance = new ClassFeePlanTable();
		}
		return self::$_instance;
	}

	// ここに実装して下さい
	public function getFees($class_id, $pschool_ids) {

		$bind = array ();
		$sql = " SELECT *, case payment_unit WHEN 1 then '一人当たり' else '全員で' END as payment_unit_name from class_fee_plan";
		$sql .= " WHERE class_id = ?";
		$sql .= " AND pschool_id IN (" . implode(',' , $pschool_ids) . ")";
		$sql .= " AND delete_date IS NULL ";
		$sql .= " AND active_flag = 1 ";
		$sql .= " ORDER BY sort_no ASC";
		$bind[] = $class_id;

		return $this->fetchAll ( $sql, $bind );
	}

}
