<?php

namespace App\Http\Controllers\Api;
use DB;
use Log;
use DateTime;
use Validator;

use App\Common\Constants;
use App\Common\Email;
use App\Common\ImageHandler;
use App\ConstantsModel;
use App\Lang;
use App\MessageFile;
use App\Http\Controllers\Common\_BaseAppController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

use App\Model\AppInfoTable;
use App\Model\AppMemberTable;
use App\Model\AppWorkbookTable;


class _BaseApiController extends _BaseAppController {

	private $_AUTH_KEY = 'tizXjltLQ5QiLUd2';

	public $_EMAIL_INFO = 'info@asto-system.com';

	public function __construct($key_check=true) {
		parent::__construct ();

		//認証キーチェック
		if ($key_check && (empty($this->request['apikey']) || $this->request['apikey'] != $this->_AUTH_KEY)) {
			exit;
		}

		//現在時刻
		session()->put('now_date', date('Y-m-d H:i:s'));
	}

	public function _save($name, $data) {
		$now = date('Y-m-d H:i:s');
		$admin = session()->get('appmanage.account.id');
		$data_id = empty($data['id'])? null:$data['id'];
		unset($data['id']);

		if (empty($data_id)) {
			$data['register_date'] = $now;
			$data['register_admin'] = $admin;
			$data_id = DB::table($name)->insertGetId($data);
		} else {
			$data['update_date'] = $now;
			$data['update_admin'] = $admin;
			DB::table($name)->where('id', $data_id)->update($data);
		}
		return $data_id;
	}

	public function execute() {
		return $this->returnState('NG');
	}

	protected function returnState($state='OK') {
		return response(array('res'=>$state));
	}

	protected function returnData($row, $state='OK') {
		$data = ['res'=>$state];
		if ($state == 'OK') {
			$data['data'] = $row;
		} else {
			$message = $row;
			if (is_array($row)) {
				foreach ($row as $key => $val) {
					$message = $val;
				}
			}
			$data['message'] = $message;
		}
		return response($data);
	}

	protected function returnList($rows, $state='OK') {
		$data = ['res'=>$state];
		if ($state == 'OK') {
			$data['list'] = $rows;
		} else {
			$messages = [];
			if (is_array($rows)) {
				foreach ($rows as $key => $row) {
					if (is_array($row)) {
						foreach ($row as $val) {
							$messages[$key] = $val;
						}
					} else {
						$messages[$key] = $row;
					}
				}
			} else {
				$messages = [$rows];
			}
			$data['messages'] = $messages;
		}
		return response($data);
	}

    protected function returnDic($rows, $state='OK') {
		$data = ['res'=>$state];
		if ($state == 'OK') {
			$data['dic'] = $rows;
		} else {
			$messages = [];
			if (is_array($rows)) {
				foreach ($rows as $key => $row) {
					if (is_array($row)) {
						foreach ($row as $val) {
							$messages[$key] = $val;
						}
					} else {
						$messages[$key] = $row;
					}
				}
			} else {
				$messages = [$rows];
			}
			$data['messages'] = $messages;
		}
		return response($data);
	}

	protected function returnMember($req, $err='会員情報の取得に失敗しました。') {
		$member = AppMemberTable::getInstance()->getMember($req);
		if (empty($member['member_id'])) {
			return $this->returnData($err, 'NG');
		} else {
			return $this->returnData($member);
		}
	}


}
