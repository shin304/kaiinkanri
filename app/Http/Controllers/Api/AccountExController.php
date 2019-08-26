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


class AccountExController extends _BaseApiController {

	public $request;

	public function __construct(Request $request) {
		$this->request = $request;
		parent::__construct ();
	}

	public function executeRenkeiRemove() {
		if (empty($this->request['member_id']) || empty($this->request['pschool_code']) || empty($this->request['pschool_mailaddress'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$member = array('id'=>$this->request['member_id']);
		$member['pschool_code'] = null;
		$member['pschool_mailaddress'] = null;
		$member['pschool_id'] = null;
		$member['student_id'] = null;

		DB::beginTransaction();
		try {
			$member['member_id'] = $this->_save('app_member', $member);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnMember($member);
	}

	public function executeRenkeiAuth() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		} elseif (empty($this->request['pschool_code'])) {
			return $this->returnData('コードを指定してください。', 'NG');
		} elseif (empty($this->request['pschool_mailaddress'])) {
			return $this->returnData('メールアドレスを指定してください。', 'NG');
		}

		// 会員情報の確認
		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (empty($member['member_id'])) {
			return $this->returnData('会員登録されていません。', 'NG');
		}

		// 塾生徒の確認
		$student = AppMemberTable::getInstance()->getStudent($this->request);
		if (empty($student['pschool_id'])) {
			return $this->returnData('塾登録されていません。', 'NG');
		} elseif (empty($student['student_id'])) {
			return $this->returnData('生徒登録されていません。', 'NG');
		}

		DB::beginTransaction();
		try {
			$auth = array('auth_key'=>$this->createAuthKey());
			if (empty($student['pschool_auth_key'])) {
				$auth['member_id'] = $member['member_id'];
				$auth['pschool_id'] = $student['pschool_id'];
				$auth['student_id'] = $student['student_id'];
				$auth['pschool_code'] = $student['pschool_code'];
				$auth['pschool_mailaddress'] = $student['pschool_mailaddress'];
			} else {
				$auth['id'] = $student['renkei_id'];
			}
			$this->_save('app_auth_renkei', $auth);

			// 認証キーを発行する
			$student['member_name'] = $member['member_name'];
			$student['mailaddress'] = $member['mailaddress'];
			$student['auth_key'] = $auth['auth_key'];
			$this->send_renkei_key_mail($student);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData(array('auth_key'=>$auth['auth_key']));
	}

	private function send_renkei_key_mail($mail) {
		$mail['reply'] = $this->_EMAIL_INFO;
		Mail::send(['text'=>'_mail.app_renkei_key_mail'], ['mail'=>$mail], function($message) use ($mail)
		{
		    $message->from($mail['reply']);
		    $message->to($mail['mailaddress']);
			$message->subject('[ICTEL] らく塾連携設定のお知らせ');
		});

		return false;
	}

	public function executeRenkeiRegist() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		} elseif (empty($this->request['pschool_code'])) {
				return $this->returnData('コードを指定してください。', 'NG');
		} elseif (empty($this->request['pschool_mailaddress'])) {
			return $this->returnData('メールアドレスを指定してください。', 'NG');
		} elseif (empty($this->request['auth_key'])) {
			return $this->returnData('認証コードを指定してください。', 'NG');
		}

		// 塾生徒の確認
		$student = AppMemberTable::getInstance()->getStudent($this->request);
		if (empty($student['pschool_id'])) {
			return $this->returnData('塾登録されていません。', 'NG');
		} elseif (empty($student['student_id'])) {
			return $this->returnData('生徒登録されていません。', 'NG');
		} elseif (empty($student['renkei_id'])) {
			return $this->returnData('連携登録されていません。', 'NG');
		} elseif ($student['auth_key'] != $this->request['auth_key']) {
			return $this->returnData('認証キーが間違っています。', 'NG');
		}

		DB::beginTransaction();
		try {
			$member = array();
			$member['id'] = $this->request['member_id'];
			$member['pschool_code'] = $student['pschool_code'];
			$member['pschool_mailaddress'] = $student['pschool_mailaddress'];
			$member['student_id'] = $student['student_id'];
			$member['pschool_id'] = $student['pschool_id'];
			$this->_save('app_member', $member);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnMember($this->request);
	}

	public function executeCopyAuth() {
		if (empty($this->request['pschool_code'])) {
			return $this->returnData('コードを指定してください。', 'NG');
		} elseif (empty($this->request['pschool_mailaddress'])) {
			return $this->returnData('メールアドレスを指定してください。', 'NG');
		} elseif (empty($this->request['ictel_loginpw'])) {
			return $this->returnData('アプリパスワードを指定してください。', 'NG');
		}

		// 会員情報の確認
		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (!empty($member['member_id'])) {
			return $this->returnData('すでに会員登録されています。', 'NG');
		}

		// 塾生徒の確認
		$student = AppMemberTable::getInstance()->getStudent($this->request);
		if (empty($student['pschool_id'])) {
			return $this->returnData('塾登録されていません。', 'NG');
		} elseif (empty($student['student_id'])) {
			return $this->returnData('生徒登録されていません。', 'NG');
		}

		DB::beginTransaction();
		try {
			$auth = array('auth_key'=>$this->createAuthKey());
			if (empty($student['pschool_auth_key'])) {
				$auth['member_id'] = $member['member_id'];
				$auth['pschool_id'] = $student['pschool_id'];
				$auth['student_id'] = $student['student_id'];
				$auth['pschool_code'] = $student['pschool_code'];
				$auth['pschool_mailaddress'] = $student['pschool_mailaddress'];
			} else {
				$auth['id'] = $student['renkei_id'];
			}
			$this->_save('app_auth_renkei', $auth);

			// 認証キーを発行する
			$student['member_name'] = $member['member_name'];
			$student['mailaddress'] = $member['mailaddress'];
			$student['auth_key'] = $auth['auth_key'];
			$this->send_pschool_key_mail($student);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData(array('auth_key'=>$auth['auth_key']));
	}

	private function send_pschool_key_mail($mail) {
		$mail['reply'] = $this->_EMAIL_INFO;
		Mail::send(['text'=>'_mail.app_pschool_key_mail'], ['mail'=>$mail], function($message) use ($mail)
		{
		    $message->from($mail['reply']);
		    $message->to($mail['mailaddress']);
			$message->subject('[ICTEL] らく塾生徒情報からの会員登録のお知らせ');
		});

		return false;
	}

	public function executeCopyRegist() {
		if (empty($this->request['pschool_code'])) {
			return $this->returnData('コードを指定してください。', 'NG');
		} elseif (empty($this->request['pschool_mailaddress'])) {
			return $this->returnData('メールアドレスを指定してください。', 'NG');
		} elseif (empty($this->request['ictel_loginpw'])) {
			return $this->returnData('アプリパスワードを指定してください。', 'NG');
		} elseif (empty($this->request['auth_key'])) {
			return $this->returnData('認証コードを指定してください。', 'NG');
		}

		// 塾生徒の確認
		$student = AppMemberTable::getInstance()->getStudent($this->request);
		if (empty($student['pschool_id'])) {
			return $this->returnData('塾登録されていません。', 'NG');
		} elseif (empty($student['student_id'])) {
			return $this->returnData('生徒登録されていません。', 'NG');
		} elseif (empty($student['renkei_id'])) {
			return $this->returnData('連携登録されていません。', 'NG');
		} elseif ($student['auth_key'] != $this->request['auth_key']) {
			return $this->returnData('認証キーが間違っています。', 'NG');
		}

		DB::beginTransaction();
		try {
			$member = array();
			$member['login_id'] = $student['pschool_mailaddress'];
			$member['login_pw'] = MD5($this->request['ictel_loginpw']);
			$member['pschool_id'] = $student['pschool_id'];
			$member['student_id'] = $student['student_id'];
			$member['pschool_code'] = $student['pschool_code'];
			$member['mailaddress'] = $student['pschool_mailaddress'];
			$member['member_name'] = $student['student_name'];
			$member['nickname'] = $student['student_nickname'];
			$member['birthday'] = $student['birthday'];
			$member['sex'] = $student['sex'];
			$member['coach_mailaddress'] = $student['parent_mailaddress1'];
			$member['pref_id'] = $student['_pref_id'];
			$member['city_id'] = $student['_city_id'];
			$member['active_flag'] = 1;
			$member['status'] = 1;
			$member['auth_key'] = $this->createAuthKey();
			$this->_save('app_member', $member);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnMember($this->request);
	}

	public function executeMailPreAuth() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		// 会員情報の確認
		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (empty($member['member_id'])) {
			return $this->returnData('会員登録されていません。', 'NG');
		}

		// 変更前チェック
		$this->send_auth_mail_pre($member);
		return $this->returnData(array('auth_key'=>$member['auth_key']));
	}

	private function send_auth_mail_pre($mail) {
		$mail['reply'] = $this->_EMAIL_INFO;
		Mail::send(['text'=>'_mail.app_mailaddress_old_mail'], ['mail'=>$mail], function($message) use ($mail)
		{
			$message->from($mail['reply']);
			$message->to($mail['mailaddress']);
			$message->subject('[ICTEL] メールアドレス変更前の確認');
		});

		return false;
	}

	public function executeMailPostAuth() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		} elseif (empty($this->request['mailaddress'])) {
			return $this->returnData('変更後メールアドレスを指定してください。', 'NG');
		} elseif (empty($this->request['auth_key'])) {
			return $this->returnData('認証キーを指定してください。', 'NG');
		}

		// 会員情報の確認
		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (empty($member['member_id'])) {
			return $this->returnData('会員登録されていません。', 'NG');
		} elseif ($member['auth_key'] != $this->request['auth_key']) {
			return $this->returnData('認証キーが一致しない。', 'NG');
		}

		// 変更後のメールアドレスチェック
		$check = AppMemberTable::getInstance()->getMember(['mailaddress'=>$this->request['mailaddress']]);
		if (!empty($check['member_id'])) {
			return $this->returnData('このメールアドレスはすでに会員登録されています。', 'NG');
		}

		//auth_mailaddressテーブルに該当情報が存在する場合、その認証キーを取得
		$mail = AppMemberTable::getInstance()->getAuthMailaddress($this->request);

		DB::beginTransaction();
		try {
			$auth = array('auth_key'=>$this->createAuthKey());
			if (empty($mail['id'])) {
				$auth['member_id'] = $member['member_id'];
				$auth['after_mailaddress'] = $student['mailaddress'];
			} else {
				$auth['id'] = $mail['id'];
			}
			$this->_save('app_auth_mailaddress', $auth);

			// 認証キーを発行する
			$auth['member_name'] = $member['member_name'];
			$this->send_auth_mailaddress_post($auth);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData(array('auth_key'=>$auth['auth_key']));
	}

	private function send_auth_mailaddress_post($mail) {
		$mail['reply'] = $this->_EMAIL_INFO;
		Mail::send(['text'=>'_mail.app_mailaddress_new_mail'], ['mail'=>$mail], function($message) use ($mail)
		{
			$message->from($mail['reply']);
			$message->to($mail['mailaddress']);
			$message->subject('[ICTEL] メールアドレス変更後の確認');
		});

		return false;
	}

	public function executeMailUpdate() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		} elseif (empty($this->request['mailaddress'])) {
			return $this->returnData('変更後メールアドレスを指定してください。', 'NG');
		} elseif (empty($this->request['auth_key'])) {
			return $this->returnData('認証キーを指定してください。', 'NG');
		}

		$mail = AppMemberTable::getInstance()->getAuthMailaddress($this->request);
		if (empty($mail['auth_key']) || $mail['auth_key'] != $this->request['auth_key']) {
			return $this->returnData('正しい認証キーを入力してください。', 'NG');
		}

		DB::beginTransaction();
		try {
			$member = array();
			$member['id'] = $this->request['member_id'];
			$member['login_id'] = $this->request['mailaddress'];
			$member['mailaddress'] = $this->request['mailaddress'];
			$this->_save('app_member', $member);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnMember($member);
	}

	// 有料会員
	public function executeFeeList() {
		if (empty($this->request['member_id'])) {
			return $this->returnList([]);
		}

		$fee_list = AppInfoTable::getInstance()->getPublishingFeeList($this->request);
		return $this->returnList($fee_list);
	}

	public function executeFeeResist() {
		if (empty($this->request['member_id']) || empty($this->request['fee_id']) || empty($this->request['fee_receipt'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		DB::beginTransaction();
		try {
			$itunes = [];
			$itunes['member_id'] = $this->request['member_id'];
			$itunes['fee_id'] = $this->request['fee_id'];
			$itunes['fee_receipt'] = $this->request['fee_receipt'];
			$this->_save('app_member_fee_rel', $itunes);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData('regist');
	}

	public function executeFeeRemove() {
		if (empty($this->request['member_id']) || empty($this->request['rel_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		DB::beginTransaction();
		try {
			$itunes = array();
			$itunes['id'] = $this->request['rel_id'];
			$itunes['delete_date'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
			$this->_save('app_member_fee_rel', $itunes);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData('remove');
	}
	//*/
}
