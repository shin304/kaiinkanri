<?php

namespace App\Model;

use DB;
use App\ConstantsModel;
use Illuminate\Database\Eloquent\Model;

class AppInfoTable extends DbModel
{
	//
	protected $table = 'app_info';

	const CREATED_AT = 'register_date';
	const UPDATED_AT = 'update_date';

	private static $_instance = null;

	public static function getInstance() {
		if (is_null ( self::$_instance )) {
			self::$_instance = new AppInfoTable ();
		}
		return self::$_instance;
	}


	//ここに実装してください
	public function getAccount($req=null) {
		$bind = array();

		$sql = " SELECT l.id, l.login_id, l.auth_type, IF(l.auth_type = 1, 0, p.id) as pschool_id
				, IF(l.auth_type = 1, 'いくてる', p.name) as pschool_name, p.language, a.id as info_id, a.workbook_title
				, a.workbook_type_ids, a.news_title, a.news_type_ids
				 FROM login_account as l
				 LEFT JOIN pschool as p ON (l.id = p.login_account_id AND p.active_flag = 1 AND p.delete_date is NULL)
				 LEFT JOIN app_info as a ON (((l.auth_type = 1 AND a.pschool_id is NULL) OR (l.auth_type != 1 AND p.id = a.pschool_id)) AND a.delete_date is NULL)
				 WHERE l.delete_date is NULL ";
		if (!empty($req['id'])) {
			$sql.= " AND l.id = ? ";
			$bind[] = $req['id'];
		} elseif (!empty($req['loginid']) && !empty($req['password'])) {
			$sql.= " AND l.login_id = ? AND l.login_pw = MD5(?) ";
			$bind[] = $req['loginid'];
			$bind[] = $req['password'];
		} else {
			return array();
		}

		$account = $this->fetch($sql, $bind);
		if (empty($account['id']) || empty($account['auth_type']) || ($account['auth_type'] != 1 && empty($account['info_id']))) {
			$account = array();
		}
		return $account;
	}

	public function getMemberCount($req=null) {
		$bind = array();

		$sql = " SELECT COUNT(m.id) as `all`, COUNT(if(m.active_flag=1,1,0)) as `active`
				 , COUNT(if(m.active_flag=1 and m.status=1,1,0)) as `real`
				 FROM app_member as m
				 WHERE m.delete_date is NULL ";
		if (!empty($req['info_id'])) {
			$sql.= " AND m.info_id = ? ";
			$bind[] = $req['info_id'];
		} else {
			$sql.= " AND m.info_id is NULL ";
		}

		return $this->fetch($sql, $bind);
	}

	public function getWorkbookCount($req=null) {
		$bind = array();

		$sql = " SELECT COUNT(w.id) as `all`, COUNT(if(w.is_public=1,1,0)) as `active`
				 FROM app_workbook as w
				 WHERE w.delete_date is NULL ";
		if (!empty($req['info_id'])) {
			$sql.= " AND w.info_id = ? ";
			$bind[] = $req['info_id'];
		} else {
			$sql.= " AND w.info_id is NULL ";
		}

		return $this->fetch($sql, $bind);
	}

	public function getInfoList($req=null) {
		$bind = array();

		$sql = " SELECT i.id as info_id, p.id as pschool_id, IFNULL(p.name, 'いくてるアプリ') as pschool_name
				 FROM app_info as i
				 LEFT JOIN pschool as p ON (i.pschool_id = p.id AND p.delete_date is NULL)
				 WHERE i.delete_date is NULL ";
		if (!empty($req['info_id'])) {
 			$sql.= " AND i.id = ? ";
 			$bind[] = $req['info_id'];
 		}
		if (!empty($req['pschool_name'])) {
			$sql.= " AND p.name like ? ";
			$bind[] = "%".$req['pschool_name']."%";
		}
		if (!empty($req['register_date_from'])) {
			$sql.= " AND i.register_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['register_date_from']));
		}
		if (!empty($req['register_date_to'])) {
			$sql.= " AND i.register_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['register_date_to']));
		}
		if (!empty($req['update_date_from'])) {
			$sql.= " AND i.update_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['update_date_from']));
		}
		if (!empty($req['update_date_to'])) {
			$sql.= " AND i.update_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['update_date_to']));
		}
		return $this->fetchAll($sql, $bind);
	}

	public function getNewInfoList($req=null) {
		$ret = array();

		//ictel
		$sub = " SELECT id FROM app_info WHERE pschool_id is NULL AND delete_date is NULL ";
		$res = $this->fetch($sub);
		$ictel_id = (empty($res['id']))? 0:$res['id'];

		//pschool
		$bind = array();

		$sql = " SELECT id as pschool_id, name as pschool_name
				 FROM pschool
				 WHERE active_flag = 1 AND delete_date is NULL ";
		if (!empty($req['info_id']) && $req['info_id'] == $ictel_id) {
			//ictelはpschoolにない
			$ret[] = array('pschool_id'=>0, 'pschool_name'=>'いくてるアプリ');
			return $ret;
		} elseif (!empty($req['info_id'])) {
			//選択したの
			$sql.= " AND id in (SELECT pschool_id FROM app_info WHERE id = ?) ";
			$bind[] = $req['info_id'];
		} else {
			//利用していないの
			$sql.= " AND id not in (SELECT pschool_id FROM app_info WHERE pschool_id is not NULL AND delete_date is NULL) ";
			if (empty($ictel_id)) {
				$ret[] = array('pschool_id'=>0, 'pschool_name'=>'いくてるアプリ');
			}
		}
		$sql.= " ORDER BY id ";
		$res = $this->fetchAll($sql, $bind);

		foreach ($res as $row) {
			$ret[] = (array)$row;
		}
		return $ret;
	}

	public function getInfo($req=null) {
		$bind = array();

		$sql = " SELECT i.id, i.pschool_id, p.name as pschool_name, p.pschool_code, i.workbook_title
				 , i.workbook_type_ids, i.news_title, i.register_date, i.update_date
				 FROM app_info as i
				 LEFT JOIN pschool as p ON (i.pschool_id = p.id AND p.delete_date is NULL)
				 WHERE i.id = ? ";
		$bind[] = $req['info_id'];

		$res = $this->fetch($sql, $bind);

		//info
		$info = array();
		$info['info_id'] = empty($res['id'])? 0:$res['id'];
		$info['pschool_id'] = empty($res['pschool_id'])? 0:$res['pschool_id'];
		$info['pschool_name'] = empty($res['pschool_name'])? 'いくてるアプリ':$res['pschool_name'];
		$info['pschool_code'] = empty($res['pschool_code'])? null:$res['pschool_code'];
		$info['workbook_title'] = empty($res['workbook_title'])? '問題集':$res['workbook_title'];
		$info['news_title'] = empty($res['news_title'])? 'お知らせ':$res['news_title'];
		$info['register_date'] = empty($res['register_date'])? '':$res['register_date'];
		$info['update_date'] = empty($res['update_date'])? '':$res['update_date'];
		$info['book_type_ids'] = empty($res['workbook_type_ids'])? [1]:explode(',', $res['workbook_type_ids']);

		//workbook_type
		$info['book_type_list'] = $this->getWorkbookTypeList($req);

		//news_type
		$info['news_type_list'] = $this->getNewsTypeList($req);

		//会員数
		$info['member_counts'] = $this->getMemberCount($req);

		//問題集数
		$info['workbook_counts'] = $this->getWorkbookCount($req);

		return $info;
	}

	public function getWorkbookTypeList($req=null) {
		$bind = array();

		$sql = " SELECT id, title
				 FROM app_workbook_type
				 WHERE delete_date is NULL
				 ORDER BY sequence_no ";

		return $this->fetchAll($sql, $bind);
	}

	public function getNewsTypeList($req=null) {
		$bind = array();

		$sql = " SELECT id, title, entry_url, active_flag
				 FROM app_news_type
				 WHERE delete_date is NULL ";
		if (!empty($req['info_id'])) {
			$sql.= " AND info_id = ? ";
			$bind[] = $req['info_id'];
		} else {
			$sql.= " AND info_id is NULL ";
		}
		$sql.= " ORDER BY id ";

		return $this->fetchAll($sql, $bind);
	}

	public function getMemberList($req=null) {
		$bind = array();

		$sql = " SELECT id, info_id, mailaddress, member_name, nickname, birthday, school_type
				 , sex, active_flag, status, register_date, update_date
				 FROM app_member
				 WHERE delete_date is NULL ";
		if (!empty($req['info_id'])) {
			$sql.= " AND info_id = ? ";
			$bind[] = $req['info_id'];
		}
		if (!empty($req['member_name'])) {
			$sql.= " AND member_name like ? ";
			$bind[] = "%{$req['member_name']}%";
		}
		if (!empty($req['nickname'])) {
			$sql.= " AND nickname like ? ";
			$bind[] = "%{$req['nickname']}%";
		}
		if (!empty($req['mailaddress'])) {
			$sql.= " AND mailaddress like ? ";
			$bind[] = "%{$req['mailaddress']}%";
		}
		if (!empty($req['birth_year'])) {
			$sql.= " AND birthday like ? ";
			$bind[] = "{$req['birth_year']}%";
		}
		if (!empty($req['school_type']) && $req['school_type'] < 9) {
			$sql.= " AND school_type = ? ";
			$bind[] = $req['school_type'];
		} elseif (!empty($req['school_type'])) {
			$sql.= " AND school_type is NULL ";
		}
		if (!empty($req['sex'])) {
			$sql.= " AND sex = ? ";
			$bind[] = $req['sex'];
		}
		if (!empty($req['active_flag']) && $req['active_flag'] == 1) {
			$sql.= " AND active_flag = ? ";
			$bind[] = 1;
		} elseif (!empty($req['active_flag']) && $req['active_flag'] == 2) {
			$sql.= " AND active_flag = ? ";
			$bind[] = 0;
		}
		if (!empty($req['status']) && $req['status'] == 1) {
			$sql.= " AND status = ? ";
			$bind[] = 1;
		} elseif (!empty($req['status']) && $req['status'] == 2) {
			$sql.= " AND status = ? ";
			$bind[] = 0;
		}
		if (!empty($req['register_date_from'])) {
			$sql.= " AND register_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['register_date_from']));
		}
		if (!empty($req['register_date_to'])) {
			$sql.= " AND register_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['register_date_to']));
		}
		if (!empty($req['update_date_from'])) {
			$sql.= " AND update_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['update_date_from']));
		}
		if (!empty($req['update_date_to'])) {
			$sql.= " AND update_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['update_date_to']));
		}
		$sql.= " ORDER BY id ";

		return $this->fetchAll($sql, $bind);
	}

	public function getNewsList($req=null) {
		$bind = array();

		$sql = " SELECT id as news_id, info_id, title, subtitle, disp_date, publish_date_from, publish_date_to
				 , link_url, link_pdf, file_pdf, has_notification, notification_datetime, register_date, update_date
				 FROM app_news
				 WHERE delete_date is NULL ";
		if (!empty($req['info_id'])) {
			$sql.= " AND info_id = ? ";
			$bind[] = $req['info_id'];
		}
		if (!empty($req['title'])) {
			$sql.= " AND title like ? ";
			$bind[] = "%{$req['title']}%";
		}
		if (!empty($req['subtitle'])) {
			$sql.= " AND subtitle like ? ";
			$bind[] = "%{$req['subtitle']}%";
		}
		if (!empty($req['status']) && $req['status'] == 1) {
			$sql.= " AND publish_date_from is not NULL AND publish_date_from > ? ";
			$bind[] = date('Y-m-d H:i:s');
		} elseif (!empty($req['status']) && $req['status'] == 2) {
			$sql.= " AND (publish_date_from is NULL OR publish_date_from <= ?) AND (publish_date_to is NULL OR ? <= publish_date_to) ";
			$bind[] = date('Y-m-d H:i:s');
			$bind[] = date('Y-m-d H:i:s');
		} elseif (!empty($req['status']) && $req['status'] == 3) {
			$sql.= " AND publish_date_to is not NULL AND publish_date_to < ? ";
			$bind[] = date('Y-m-d H:i:s');
		}
		if (!empty($req['register_date_from'])) {
			$sql.= " AND register_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['register_date_from']));
		}
		if (!empty($req['register_date_to'])) {
			$sql.= " AND register_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['register_date_to']));
		}
		if (!empty($req['update_date_from'])) {
			$sql.= " AND update_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['update_date_from']));
		}
		if (!empty($req['update_date_to'])) {
			$sql.= " AND update_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['update_date_to']));
		}
		$sql.= " ORDER BY id ";

		return $this->fetchAll($sql, $bind);
	}

	public function getNews($req=null) {
		$bind = array();

		$sql = " SELECT *, id as news_id
				 FROM app_news
				 WHERE delete_date is NULL
				 AND id = ? ";
		$bind[] = empty($req['news_id'])? 0:$req['news_id'];

		return $this->fetch($sql, $bind);
	}

	public function getNewTypeList($req=null) {
		$bind = array();

		$sql = " SELECT id as news_type_id, title, title as news_type_name, icon_type as news_icon_type, entry_url as news_entry_url
				 FROM app_news_type
				 WHERE delete_date is NULL AND active_flag = 1 ";
		if (!empty($req['info_id'])) {
			$sql.= " AND info_id = ? ";
			$bind[] = $req['info_id'];
		} elseif (!empty($req['member_id'])) {
			$sql.= " AND info_id in (SELECT info_id FROM app_member WHERE id = ?) ";
			$bind[] = $req['member_id'];
		} else {
			$sql.= " AND info_id in (SELECT id FROM app_info WHERE pschool_id is NULL) ";
		}
		$sql.= " ORDER BY id ";

		return $this->fetchAll($sql, $bind);
	}

	public function getPschoolIdByInfoId($req=null) {
		$bind = array();

		$sql = " SELECT pschool_id
				 FROM app_info
				 WHERE delete_date is NULL ";
		if (empty($req['info_id'])) {
			return null;
		} else {
			$sql.= " AND id = ? ";
			$bind[] = $req['info_id'];
		}

		$ret = $this->fetch($sql, $bind);
		return empty($ret['pschool_id'])? null:$ret['pschool_id'];
	}

	public function getPrefList() {
		$bind = array();

		$sql = " SELECT id, name
				 FROM m_pref
				 ORDER BY id ";

		return $this->fetchAll($sql, $bind);
	}

	public function getCityList($req=null) {
		$bind = array();

		$sql = " SELECT id, name
				 FROM m_city ";
		if (empty($req['pref_id'])) {
			return array();
		} else {
			$sql.= " WHERE pref_id = ? ";
			$bind[] = $req['pref_id'];
		}
		$sql.= " ORDER BY id ";

		return $this->fetchAll($sql, $bind);
	}

	public function getPrefID($req=null) {
		$bind = array();

		$sql = " SELECT pref_id FROM m_city ";
		if (empty($req['city_id'])) {
			return null;
		} else {
			$sql.= " WHERE id = ? ";
			$bind[] = $req['city_id'];
		}
		$res = $this->fetch($sql, $bind);
		return empty($res['pref_id'])? null:$res['pref_id'];
	}

	public function getPublishingNewsList($req=null) {
		$bind = array();

		$sql = " SELECT n.id as news_id, n.info_id, NULL as highschool_id, n.title, n.subtitle, n.disp_date, n.news_type_id, n.content_title, n.content
				 , n.publish_date_from, n.publish_date_to, n.link_url, n.link_pdf, n.file_pdf, n.member_id, t.icon_type
				 FROM app_news as n
				 LEFT JOIN app_news_type as t ON (n.news_type_id = t.id)
				 WHERE n.delete_date is NULL
				 AND ((n.publish_date_from is NULL) OR (n.publish_date_from < now()))
				 AND ((n.publish_date_to is NULL) OR (n.publish_date_to > now())) ";

		if (!empty($req['app_code'])) {
			$pschool_id = " SELECT id FROM pschool WHERE pschool_code = ? ";
			$sql.= " AND n.info_id in (SELECT id FROM app_info WHERE pschool_id in ({$pschool_id}) ) ";
			$bind[] = $req['app_code'];
		} elseif (!empty($req['member_id'])) {
			$sql.= " AND n.info_id in (SELECT info_id FROM app_member WHERE id = ?) ";
			$bind[] = $req['member_id'];
		} else {
			$sql.= " AND n.pschool_id is NULL ";
		}

		$sql.= " ORDER BY n.disp_date DESC ";

		return $this->fetchAll($sql, $bind);
	}

	public function getMenu($req=null) {
		$bind = array();

		$sql = " SELECT id, workbook_title, news_title FROM app_info WHERE delete_date is NULL ";
		if (!empty($req['member_id'])) {
			$sql.= " AND id in (SELECT info_id FROM app_member WHERE id = ?) ";
			$bind[] = $req['member_id'];
		} else {
			$sql.= " AND pschool_id is NULL ";
		}
		$res = $this->fetch($sql, $bind);

		$ret = array();
		$ret[] = ['menu_type_id'=>7, 'menu_type_name'=>empty($res['workbook_title'])? '問題集':$res['workbook_title'], 'is_initial'=>0];
		$ret[] = ['menu_type_id'=>5, 'menu_type_name'=>empty($res['news_title'])? 'お知らせ':$res['news_title'], 'is_initial'=>1];
		$ret[] = ['menu_type_id'=>6, 'menu_type_name'=>'その他', 'is_initial'=>0];
		return $ret;
	}

	public function getInfoID($req=null) {
		$bind = array();

		$sql = " SELECT id FROM app_info WHERE delete_date is NULL ";
		if (!empty($req['app_code'])) {
			$sql.= " AND pschool_id in (SELECT id FROM pschool WHERE pschool_code = ? AND delete_date is NULL) ";
			$bind[] = $req['app_code'];
		} elseif (!empty($req['pschool_id'])) {
			$sql.= " AND pschool_id = ? ";
			$bind[] = $req['pschool_id'];
		} elseif (!empty($req['pschool_code'])) {
			$sql.= " AND pschool_id in (SELECT id FROM pschool WHERE pschool_code = ? AND delete_date is NULL) ";
			$bind[] = $req['pschool_code'];
		} else {
			$sql.= " AND pschool_id is NULL ";
		}
		$res = $this->fetchAll($sql, $bind);

		return empty($res['id'])? 1:$res['id'];
	}

	public function getPublishingInfoList($req=null) {
		$bind = array($req['member_id']);

		$pschool_id = " SELECT pschool_id FROM student WHERE login_id = (SELECT login_id FROM app_member WHERE id = ?) AND delete_date is NULL ";
		$sql = " SELECT i.id, IFNULL(p.name, 'いくてるアプリ') as name
				 FROM app_info as i
				 LEFT JOIN pschool as p ON (i.pschool_id = p.id AND p.delete_date is NULL)
				 WHERE i.delete_date is NULL AND (i.pschool_id is NULL OR i.pschool_id in ({$pschool_id}) )
				 ORDER BY i.id ";
		return $this->fetchAll($sql, $bind);
	}

	public function getiTunesList() {
		$bind = array();

		$sql = " SELECT *
				 FROM app_fee_itunes
				 WHERE delete_date is NULL AND public_flag = 1
				 ORDER BY price, id ";
		return $this->fetchAll($sql, $bind);
	}

	public function getFeeList($req=null) {
		$bind = [];

		$sql = " SELECT f.*, f.id as fee_id
				 FROM app_fee as f
				 WHERE f.delete_date is NULL ";
		if (!empty($req['info_id'])) {
			$sql.= " AND info_id = ? ORDER BY price DESC, id ";
			$bind[] = $req['info_id'];
		} else {
			return [];
		}
		$ret = $this->fetchAll($sql, $bind);

		foreach ($ret as $key => &$row) {
			$sql = " SELECT * FROM app_fee_policy WHERE delete_date is NULL AND app_fee_id = ? ";
			$row['policy_list'] = $this->fetchAll($sql, [$row['fee_id']]);
			$row['member_cnt'] = $this->getFeeMemberCnt($row);
		}
		return $ret;
	}

	public function getFeeMemberCnt($req=null) {
		$sql = " SELECT COUNT(member_id) as member_cnt
				 FROM app_member_fee_rel
				 WHERE delete_date is NULL ";
		if (!empty($req['fee_id'])) {
			$sql.= " AND fee_id = ? ";
			$bind[] = $req['fee_id'];
		} else {
			return 0;
		}
		$ret = $this->fetch($sql, $bind);
		return empty($ret['member_cnt'])? 0:$ret['member_cnt'];
	}

	public function getPublishingFeeList($req=null) {
		$bind = [];

		$sql = " SELECT f.*, f.id as fee_id, r.id as rel_id
				 FROM app_fee as f
				 LEFT JOIN app_member_fee_rel as r ON (f.id = r.fee_id AND r.member_id = ? AND (r.delete_date is NULL OR r.delete_date > NOW()))
				 WHERE f.public_flag = 1 AND f.delete_date is NULL AND f.info_id in (SELECT info_id FROM app_member WHERE id = ?) ORDER BY f.price DESC, f.id ";
		if (!empty($req['member_id'])) {
			$bind[] = $req['member_id'];
			$bind[] = $req['member_id'];
		} else {
			return [];
		}
		$fee_list = $this->fetchAll($sql, $bind);

		$ret = [];
		foreach ($fee_list as $fee) {
			$sql = " SELECT * FROM app_fee_policy WHERE delete_date is NULL AND app_fee_id = ? ";
			$policy_list = $this->fetchAll($sql, [$fee['fee_id']]);
			if (!empty($policy_list)) {
				$row = ['id'=>$fee['id'], 'title'=>$fee['title'], 'itunes_code'=>$fee['itunes_code'], 'fee_id'=>$fee['fee_id'], 'rel_id'=>$fee['rel_id']];
				foreach ($policy_list as $policy) {
					$row['subtitle'] = $policy['title'];
					$row['detail'] = $policy['policy'];
				}
				$ret[] = $row;
			}
		}

		return $ret;
	}

	public function getPush($req=null) {
		$bind = [];

		$sql = " SELECT id, member_id, device_token, identifierForVendor FROM app_push WHERE delete_date is NULL ";
		if (!empty($req['member_id'])) {
			$sql.= ' AND member_id = ? ';
			$bind[] = $req['member_id'];
		} else {
			return ['id'=>null];
		}
		return $this->fetch($sql, $bind);
	}
}
