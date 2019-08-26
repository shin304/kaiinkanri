<?php

namespace App\Http\Controllers\Appmanage;

use DB;
use Log;
use DateTime;
use Validator;
use Response;

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


class MemberController extends _BaseAppmanageController {

	private $request;

	public function __construct(Request $request) {
		parent::__construct ();
		$this->request = $request;
	}

	public function execute() {
		$_a = [];

		if (empty($this->request['_c'])) {
			if (session()->has('appmanage.member')) {
				$this->request['_c'] = session()->get('appmanage.member');
			} else {
				$this->request['_c'] = ['info_id'=>session()->get('appmanage.account.info_id')];
			}
		}

		//利用者
		if (session()->get('appmanage.account.auth_type') == 1) {
			$_a['info_list'] = AppInfoTable::getInstance()->getInfoList();
		} else {
			$_a['info_list'] = [];
		}

		session()->put('appmanage.member', $this->request['_c']);
		$_a['list'] = AppInfoTable::getInstance()->getMemberList($this->request['_c']);

		return view('Appmanage.Member.index')->with('_a', $_a);
	}

	public function executeExport() {
		//へっだー
		$csvHeader = [];
		$info_options = [];
		if (session()->get('appmanage.account.auth_type') == 1) {
			$csvHeader['pschool_name'] = '利用者';

			//利用者
			$info_list = AppInfoTable::getInstance()->getInfoList();
			$info_options = [];
			foreach ($info_list as $info) {
				$info_options[$info['info_id']] = $info['pschool_name'];
			}
		}
		$csvHeader['mailaddress'] = 'メールアドレス';
		$csvHeader['member_name'] = '会員名';
		$csvHeader['nickname'] = 'ニックネーム';
		$csvHeader['birthday'] = '誕生日';
		$csvHeader['school_type'] = '種別';
		$csvHeader['sex'] = '性別';
		$csvHeader['active_flag'] = '状態1';
		$csvHeader['status'] = '状態2';
		$csvHeader['register_date'] = '登録日';
		$csvHeader['update_date'] = '更新日';

		//会員
		$member_list = AppInfoTable::getInstance()->getMemberList(session()->get('appmanage.member'));

		$stream = fopen('php://temp', 'r+b');
		fputcsv($stream, $csvHeader);

		$csvHeader_key = array_keys($csvHeader);
		foreach ($member_list as $member) {
			$row = [];
			foreach ($csvHeader_key as $key) {
				if ($key == "pschool_name") {
					$row[] = empty($info_options[$member['info_id']])? '':$info_options[$member['info_id']];
				} elseif ($key == "school_type") {
					if (empty($member[$key])) {
						$row[] = '不明';
					} elseif ($member[$key] == 1) {
						$row[] = '中学';
					} elseif ($member[$key] == 2) {
						$row[] = '高校';
					} elseif ($member[$key] == 3) {
						$row[] = '大学';
					} else {
						$row[] = '社会人';
					}
				} elseif ($key == "sex") {
					if (empty($member[$key])) {
						$row[] = '不明';
					} elseif ($member[$key] == 1) {
						$row[] = '男';
					} elseif ($member[$key] == 2) {
						$row[] = '女';
					}
				} elseif ($key == "active_flag") {
					if (empty($member[$key])) {
						$row[] = '無効';
					} elseif ($member[$key] == 1) {
						$row[] = '有効';
					}
				} elseif ($key == "status") {
					if (empty($member[$key])) {
						$row[] = '仮会員';
					} elseif ($member[$key] == 1) {
						$row[] = '本会員';
					}
				} elseif (!empty($member[$key])) {
					$row[] = $member[$key];
				} else {
					$row[] = '';
				}
			}
			fputcsv($stream, $row);
		}
		rewind($stream);
		$csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
		$csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
		$headers = [
				'Content-Type' => 'text/csv',
				'Content-Disposition' => 'attachment; filename="member.csv"',
		];
		return Response::make($csv, 200, $headers);
	}
}
