<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use DateTime;
use Validator;

use App\Common\Constants;
use App\Common\Email;
use App\ConstantsModel;
use App\Lang;
use App\MessageFile;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

use App\Model\AppInfoTable;
use App\Model\AppMemberTable;
use App\Model\AppWorkbookTable;


class AccountController extends _BaseApiController {

	public $request;

	public function __construct(Request $request) {
		$this->request = $request;
		parent::__construct ();
	}

	public function executeLogin() {
		if (empty($this->request['login_id']) || empty($this->request['login_pw'])) {
			return $this->returnData('ログインID、パスワードは必須です。', 'NG');
		}

		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (!empty($member['member_id'])) {
			return $this->returnData($member);
		}

		$member = AppMemberTable::getInstance()->getMemberByStudent($this->request);
		if (!empty($member['student_id'])) {
			DB::beginTransaction();
			try {
				$member['school_type'] = $this->getSchoolType($member['birthday']);
				$member['auth_key'] = $this->createAuthKey();
				$member['member_id'] = $this->_save('app_member', $member);

				DB::commit();
			} catch (Exception $e) {
				DB::rollback();
				return $this->returnData('システムエラーが発生しました。', 'NG');
			}

			return $this->returnMember($member);
		}

		return $this->returnData('ログインIDまたはパスワードが間違っています。', 'NG');
	}

	public function executeInfo() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		return $this->returnMember($this->request);
	}

	public function executeEntry() {
		if (empty($this->request['authkey_need_flag'])) {
			return $this->returnData('会員登録のために、アプリのバージョンアップを行ってください。', 'NG');
		}

		$old = AppMemberTable::getInstance()->getMember($this->request);
		if (!empty($old['member_id'])) {
			return $this->returnData('会員登録されています。', 'NG');
		}

		// member
		$member = array();
		$member['mailaddress'] = empty($this->request['mailaddress'])? null:$this->request['mailaddress'];
		$member['login_pw'] = empty($this->request['login_pw'])? null:MD5($this->request['login_pw']);
		$member['password'] = empty($this->request['login_pw'])? '':$this->request['login_pw'];
		$member['member_name'] = empty($this->request['member_name'])? null:$this->request['member_name'];
		$member['nickname'] = empty($this->request['nickname'])? $member['member_name']:$this->request['nickname'];
		$member['birthday'] = empty($this->request['birthday'])? null:$this->request['birthday'];
		$member['sex'] = empty($this->request['sex'])? null:$this->request['sex'];
		$member['coach_mailaddress'] = empty($this->request['coach_mailaddress'])? $member['mailaddress']:$this->request['coach_mailaddress'];
		$member['city_id'] = empty($this->request['city_id'])? null:$this->request['city_id'];

		// validator
		$rules = array();
		$messages = array();
		$rules['member_name'] = 'required|between:1,255';
		$messages['member_name.between'] = 'お名前は255文字以内にしてください。';
		$messages['member_name.required'] = 'お名前を入力してください。';
		$rules['mailaddress'] = 'required|email';
		$messages['mailaddress.email'] = '正しいメールアドレスを入力してください。';
		$messages['mailaddress.required'] = 'メールアドレスを入力してください。';
		$rules['password'] = 'required|between:4,32';
		$messages['password.between'] = 'パスワードは4〜32文字にしてください。';
		$messages['password.required'] = 'パスワードを入力してください。';
		// $rules['birthday'] = 'required|date';
		// $messages['birthday.date'] = '正しい生年月日を選択してください。';
		// $messages['birthday.required'] = '生年月日を選択してください。';
		// $rules['sex'] = 'required';
		// $messages['sex.required'] = '性別を選択してください。';
		// $rules['city_id'] = 'required';
		// $messages['city_id.required'] = 'お住まいを選択してください。';
		$validator = Validator::make($member, $rules, $messages);
		if ($validator->fails()) {
			return $this->returnList($validator->errors()->messages(), 'NG');
		}

		DB::beginTransaction();
		try {
			unset($member['password']);
			$member['info_id'] = AppInfoTable::getInstance()->getInfoID();
			$member['pref_id'] = AppInfoTable::getInstance()->getPrefID($member);
			$member['school_type'] = $this->getSchoolType($member['birthday']);
			$member['login_id'] = $member['mailaddress'];
			$member['active_flag'] = 1;
			$member['status'] = 2; // 仮会員状態にする＆認証キーを発行する
			$member['auth_key'] = $this->createAuthKey();
			$member['member_id'] = $this->_save('app_member', $member);

			// 受験地域
			for ($cnt=1; $cnt<4; $cnt++) {
				$key = 'location'.$cnt;
				if (!empty($this->request[$key])) {
					$location = array('member_id'=>$member['member_id'], 'city_id'=>$this->request[$key]);
					$location['pref_id'] = AppInfoTable::getInstance()->getPrefID($location);
					$this->_save('app_member_area_rel', $location);
				}
			}

			DB::commit();

			// 認証キーを発行する
			$this->send_auth_key_mail($member);
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnMember($member);
	}

	public function executeEdit() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		// member & validator
		$member = array('id'=>$this->request['member_id']);
		$rules = array();
		$messages = array();
		if (isset($this->request['mailaddress'])) {
			$member['mailaddress'] = empty($this->request['mailaddress'])? null:$this->request['mailaddress'];
			$member['login_id'] = $member['mailaddress'];
			$rules['mailaddress'] = 'email';
			$messages['mailaddress.email'] = 'メールアドレスを指定してください。';
			$rules['mailaddress'] = 'required';
			$messages['mailaddress.required'] = 'メールアドレスが指定されていません。';
		}
		if (isset($this->request['login_pw'])) {
			$old = AppMemberTable::getInstance()->getMember($this->request);
			if (empty($this->request['login_pw_now']) || MD5($this->request['login_pw_now']) != $old['login_pw']) {
				return $this->returnData(MD5($this->request['login_pw_now']).'正しい現在のパスワードを入力してください。', 'NG');
			}

			$member['login_pw'] = empty($this->request['login_pw'])? null:MD5($this->request['login_pw']);
			$member['password'] = empty($this->request['login_pw'])? null:$this->request['login_pw'];
			$rules['password'] = 'between:4,32';
			$messages['password.between'] = 'パスワードは4〜32文字にしてください。';
		}
		if (isset($this->request['member_name'])) {
			$member['member_name'] = empty($this->request['member_name'])? null:$this->request['member_name'];
			$rules['member_name'] = 'required|between:1,255';
			$messages['member_name.between'] = '名前は255文字以内にしてください。';
			$messages['member_name.required'] = '名前が指定されていません。';
		}
		if (isset($this->request['nickname'])) {
			$member['nickname'] = empty($this->request['nickname'])? $member['member_name']:$this->request['nickname'];
			$rules['nickname'] = 'required|between:1,255';
			$messages['nickname.between'] = 'ニックネームは255文字以内にしてください。';
			$messages['nickname.required'] = 'ニックネームが指定されていません。';
		}
		if (isset($this->request['sex'])) {
			$member['sex'] = empty($this->request['sex'])? null:$this->request['sex'];
			$rules['sex'] = 'required';
			$messages['sex.required'] = '性別が指定されていません。';
		}
		if (isset($this->request['birthday'])) {
			$member['birthday'] = empty($this->request['birthday'])? null:$this->request['birthday'];
			$rules['birthday'] = 'nullable|date';
			$messages['birthday.date'] = '誕生日が不正です。';
			$rules['birthday'] = 'required';
			$messages['birthday.required'] = '誕生日が指定されていません。';
		}
		if (isset($this->request['coach_mailaddress'])) {
			$member['coach_mailaddress'] = empty($this->request['coach_mailaddress'])? null:$this->request['coach_mailaddress'];
			$rules['coach_mailaddress'] = 'nullable|email';
			$messages['coach_mailaddress.email'] = '保護者メールアドレスが不正です。';
		}
		if (isset($this->request['city_id'])) {
			$member['city_id'] = empty($this->request['city_id'])? null:$this->request['city_id'];
			$member['pref_id'] = AppInfoTable::getInstance()->getPrefID($member);
			$rules['city_id'] = 'required';
			$messages['city_id.required'] = 'お住まいが指定されていません。';
		}
		if (isset($this->request['industry_type_id'])) {
			$member['industry_type_id'] = empty($this->request['industry_type_id'])? null:$this->request['industry_type_id'];
		}
		if (isset($this->request['employees_type_id'])) {
			$member['employees_type_id'] = empty($this->request['employees_type_id'])? null:$this->request['employees_type_id'];
		}
		if (isset($this->request['info_id'])) {
			$info = AppInfoTable::getInstance()->getInfo($this->request);

			$member['info_id'] = empty($info['info_id'])? null:$info['info_id'];
			$member['pschool_id'] = empty($info['pschool_id'])? null:$info['pschool_id'];
			$member['pschool_code'] = empty($info['pschool_code'])? null:$info['pschool_code'];
			$rules['info_id'] = 'required';
			$messages['info_id.required'] = 'サービスが指定されていません。';
		}

		// validator
		$validator = Validator::make($member, $rules, $messages);
		if ($validator->fails()) {
			return $this->returnList($validator->errors()->messages(), 'NG');
		}

		DB::beginTransaction();
		try {
			unset($member['password']);
			$member['member_id'] = $this->_save('app_member', $member);

			// 受験地域
			if (isset($this->request['location1']) || isset($this->request['location2']) || isset($this->request['location3'])) {
				$old_location = AppMemberTable::getInstance()->getLocation($member);
				foreach ($old_location as $old) {
					$this->_save('app_member_area_rel', array('id'=>$old['id'], 'delete_date'=>date('Y-m-d H:i:s')));
				}
				$ii = 0;
				for ($cnt=1; $cnt<4; $cnt++) {
					$key = 'location'.$cnt;
					if (!empty($this->request[$key])) {
						$location = array('member_id'=>$member['member_id'], 'city_id'=>$this->request[$key]);
						$location['pref_id'] = AppInfoTable::getInstance()->getPrefID($location);
						$location['delete_date'] = NULL;
						if (!empty($old_location[$ii]['id'])) {
							$location['id'] = $old_location[$ii]['id'];
						}
						$this->_save('app_member_area_rel', $location);
						$ii++;
					}
				}
			}

			DB::commit();

			// 認証キーを発行する
			if (isset($this->request['mailaddress'])) {
				$this->send_auth_key_mail($member);
			}
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnMember($member);
	}

	public function executeKeyauth() {
		if (empty($this->request['member_id']) || empty($this->request['auth_key'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$member = AppMemberTable::getInstance()->getMember($this->request);
		if ($this->request['auth_key'] !== $member['auth_key']) {
			return $this->returnData('認証キーが異なります。', 'NG');
		}

		DB::beginTransaction();
		try {
			// ステータスを本会員にする
			$this->_save('app_member', array('id'=>$member['member_id'], 'status'=>1));

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData($member);
	}

	public function executeKeysend() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (empty($member['status']) || $member['status'] == 1) {
			return $this->returnData('すでに本会員として登録されています。', 'NG');
		}

		try {
			// 認証キーを再発行する
			$this->send_auth_key_mail($member);
		} catch (Exception $e) {
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData($member);
	}

	public function executeGetRecords() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$records = AppWorkbookTable::getInstance()->getMyRecordList($this->request);
		return $this->returnList($records);
	}

	public function executeDelRecords() {
		if (empty($this->request['workbook_id']) || empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$records = AppWorkbookTable::getInstance()->getMyRecordList($this->request);

		DB::beginTransaction();
		try {
			foreach ($records as $key => $record) {
				$record['id'] = $this->_save('app_my_record', ['id'=>$record['id'], 'delete_date'=>date('Y-m-d H:i:s')]);
			}

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData('削除しました。');
	}


	final protected function createAuthKey() {
		return mt_rand(100000, 999999);
	}

	private function send_auth_key_mail($mail) {
		$mail['reply'] = $this->_EMAIL_INFO;
		Mail::send(['text'=>'_mail.app_member_key_mail'], ['mail'=>$mail], function($message) use ($mail)
		{
			$message->from($mail['reply']);
			$message->to($mail['mailaddress']);
			$message->subject('[ICTEL] 会員登録のお知らせ');
		});

		return true;
	}

	public function executeInfoList() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$infos = AppInfoTable::getInstance()->getPublishingInfoList($this->request);
		return $this->returnList($infos);
	}

	private function getSchoolType($birthday=null) {
		$year = (date('n')<4)? date('Y'):date('Y', strtotime(' +1 year '));

		$age = 0;
		if (empty($birthday)) {
			return null;
		} elseif (date('n', strtotime($birthday)) < 4) {
			$age = $year - date('Y', strtotime($birthday));
		} else {
			$age = $year - date('Y', strtotime($birthday." +1 year "));
		}

		if ($age < 16) {
			return 1;
		} elseif ($age < 19) {
			return 2;
		} elseif ($age < 23) {
			return 3;
		} else {
			return 9;
		}
	}
}
