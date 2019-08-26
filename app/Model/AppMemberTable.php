<?php

namespace App\Model;

use DB;
use App\ConstantsModel;
use Illuminate\Database\Eloquent\Model;

class AppMemberTable extends DbModel
{
	//
	protected $table = 'app_member';

	const CREATED_AT = 'register_date';
	const UPDATED_AT = 'update_date';

	private static $_instance = null;

	public static function getInstance() {
		if (is_null ( self::$_instance )) {
			self::$_instance = new AppMemberTable ();
		}
		return self::$_instance;
	}


	//ここに実装してください
	public function getMember($req=null) {
		$bind = array();
		$sql = " SELECT m.id as member_id, m.mailaddress, m.member_name, m.nickname, m.login_pw, m.school_year, m.birthday
				 , m.sex, m.coach_mailaddress, m.status, m.auth_key, m.pschool_id, m.pschool_code, m.pschool_mailaddress
				 , m.student_id, m.pref_id, p.name as pref_name, m.city_id, c.name as city_name, m.industry_type_id
				 , m.employees_type_id, m.info_id, IFNULL(s.name, 'いくてるアプリ') as info_name
				 FROM app_member as m
				 LEFT JOIN m_pref p ON (m.pref_id=p.id)
				 LEFT JOIN m_city c ON (m.city_id=c.id)
				 LEFT JOIN app_info i ON (m.info_id = i.id AND i.delete_date is NULL)
				 LEFT JOIN pschool s ON (i.pschool_id = s.id AND s.delete_date is NULL)
				 WHERE m.delete_date is null ";

		if (!empty($req['member_id'])) {
			$sql.= " AND m.id = ? ";
			$bind[] = $req['member_id'];
		} elseif (!empty($req['mailaddress'])) {
			$sql.= " AND m.mailaddress = ? ";
			$bind[] = $req['mailaddress'];
		} elseif (!empty($req['login_id']) && !empty($req['login_pw'])) {
			$sql.= " AND m.login_id = ? ";
			$bind[] = $req['login_id'];
			$sql.= " AND m.login_pw = ? ";
			$bind[] = MD5($req['login_pw']);
			if (!empty($req['app_code'])) {
				$sql.= " AND s.pschool_code = ? ";
				$bind[] = $req['app_code'];
			}
		} else {
			return array();
		}
		$member = $this->fetch($sql, $bind);

		//受験地域
		for ($cnt=1; $cnt<4; $cnt++) {
			$member['area'.$cnt.'_pref_id'] = NULL;
			$member['area'.$cnt.'_pref_name'] = NULL;
			$member['area'.$cnt.'_city_id'] = NULL;
			$member['area'.$cnt.'_city_name'] = NULL;
		}
		if (!empty($member['member_id'])) {
			$sql = " SELECT a.pref_id, p.name as pref_name, a.city_id, c.name as city_name ";
			$sql.= " FROM app_member_area_rel as a ";
			$sql.= " LEFT JOIN m_pref p on (a.pref_id=p.id) ";
			$sql.= " LEFT JOIN m_city c on (a.city_id=c.id) ";
			$sql.= " WHERE a.member_id = ? AND a.city_id is not NULL AND a.delete_date is NULL ";
			$area_list = $this->fetchAll($sql, array($member['member_id']));
			$cnt = 1;
			foreach ($area_list as $area) {
				$member['area'.$cnt.'_pref_id'] = $area['pref_id'];
				$member['area'.$cnt.'_pref_name'] = $area['pref_name'];
				$member['area'.$cnt.'_city_id'] = $area['city_id'];
				$member['area'.$cnt.'_city_name'] = $area['city_name'];
				$cnt++;
			}
		}

		//業種
		$member['industry_type_name'] = NULL;
		if (!empty($member['industry_type_id'])) {
			$industry_list = ConstantsModel::$industryList;
			$member['industry_type_name'] = $industry_list[$member['industry_type_id']];
		}

		// 従業員数
		$member['employees_type_name'] = NULL;
		if (!empty($member['employees_type_id'])) {
			$employees_list = ConstantsModel::$employeesList;
			$member['employees_type_name'] = $employees_list[$member['employees_type_id']];
		}

        // info_list
		$member['info_list'] = NULL;
		if (!empty($member['member_id'])) {
            $member['info_list'] = AppInfoTable::getInstance()->getPublishingInfoList($member);
		}

        // tab_list
        $member['tab_list'] = NULL;
        if (!empty($member['member_id'])) {
            $member['tab_list'] = AppInfoTable::getInstance()->getMenu($member);
        }

		return $member;
	}

	public function getStudent($req=null) {
		$bind = array();
		$sql = " SELECT s.id as student_id, s.mailaddress as pschool_mailaddress, s.student_name
				 , s.student_nickname, s.birthday, s.sex, s.parent_mailaddress1, s._pref_id, s._city_id
				 , p.id as pschool_id, p.pschool_code, p.name as pschool_name
				 , r.id as renkei_id, r.auth_key
				 FROM student as s
				 LEFT JOIN pschool as p ON (s.pschool_id = p.id AND p.delete_date is NULL)
				 LEFT JOIN app_auth_renkei as r ON (r.member_id = ? AND r.pschool_id = p.id AND r.student_id = s.id AND r.delete_date is NULL)
				 WHERE s.pschool_code = ? AND s.mailaddress = ? AND s.delete_date is NULL ";
		$bind[] = empty($req['member_id'])? 0:$req['member_id'];
		$bind[] = empty($req['pschool_code'])? 0:$req['pschool_code'];
		$bind[] = empty($req['pschool_mailaddress'])? 0:$req['pschool_mailaddress'];

	 	return $this->fetch($sql, $bind);
	}

	public function getAuthMailaddress($req=null) {
		$bind = array();
		$sql = " SELECT id,member_id,after_mailaddress,auth_key
				 FROM app_auth_mailaddress WHERE member_id = ? AND after_mailaddress = ? AND delete_date is NULL ";
		$bind[] = empty($req['member_id'])? 0:$req['member_id'];
		$bind[] = empty($req['mailaddress'])? 0:$req['mailaddress'];
		return $this->fetch($sql, $bind);
	}

	public function getMemberByStudent($req=null) {
		$bind = array();
		$sql = " SELECT s.login_id, s.login_pw, i.id as info_id, s.pschool_id, p.pschool_code
				 , s.mailaddress as pschool_mailaddress, s.id as student_id, s.mailaddress
				 , s.student_name as member_name, s.student_nickname as nickname
				 , s.birthday, s.sex, s.parent_mailaddress1 as coach_mailaddress
				 , s._pref_id as pref_id, s._city_id as city_id, 1 as active_flag, 1 as status
				 FROM student as s
				 INNER JOIN app_info as i ON (s.pschool_id = i.pschool_id AND i.delete_date is NULL)
				 INNER JOIN pschool as p ON (s.pschool_id = p.id AND p.delete_date is NULL)
				 WHERE s.delete_date is NULL ";

		if (!empty($req['login_id']) && !empty($req['login_pw'])) {
			$sql.= " AND s.login_id = ? ";
			$bind[] = $req['login_id'];
			$sql.= " AND s.login_pw = ? ";
			$bind[] = MD5($req['login_pw']);
			if (!empty($req['app_code'])) {
				$sql.= " AND s.pschool_code = ? ";
				$bind[] = $req['app_code'];
			}
		} else {
			return array();
		}

		return $this->fetch($sql, $bind);
	}

	public function getLocation($req=null) {
		$bind = array();
		$sql = " SELECT id, pref_id, city_id
				 FROM app_member_area_rel
				 WHERE id is not NULL ";
		if (!empty($req['member_id'])) {
			$sql.= " AND member_id = ? ";
			$bind[] = $req['member_id'];
		} else {
			return array();
		}
		$sql.= " ORDER BY id ";
		return $this->fetchAll($sql, $bind);
	}

}
