<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SchoolDefaultMenuTable extends DbModel
{
    /**
	 *
	 * @var SchoolDefaultMenuTable
	 */
	private static $_instance = null;
	protected $table = 'school_default_menu';
	
	/**
	 *
	 * @return SchoolDefaultMenuTable
	 */
	public static function getInstance() {
		if (is_null ( self::$_instance )) {
			self::$_instance = new SchoolDefaultMenuTable ();
		}
		return self::$_instance;
	}

	public function getRoles($school_id) {
		$bind = array();
		$sql = "SELECT role FROM school_default_menu WHERE register_admin = ? GROUP BY role";
		$bind[] = $school_id;

		return $this->fetchAll($sql, $bind);
	}

	public function getListDefaultMenu($pschool_id) {
		$sql = "SELECT d.* FROM school_default_menu d INNER JOIN pschool p ON (p.role = d.role) WHERE p.id=" .$pschool_id;
		
		$res = 	$this->fetchAll ($sql);	

		$lst = array();
		foreach ($res as $key => $value) {
			$lst[$value['master_menu_id']] = $value;
		}
		return $lst ;
	}
}
