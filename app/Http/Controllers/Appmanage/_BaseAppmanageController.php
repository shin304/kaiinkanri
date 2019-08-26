<?php

namespace App\Http\Controllers\Appmanage;
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


class _BaseAppmanageController extends _BaseAppController {

	const SESSION_BREAD_LIST = 'session_class_bread_list';
	// メニュー番号を保存するキー
	const SESSION_MENU_NUMBER = 'session_menu_number';
	// メニューのタイトル
	var $main_caption;
	protected static $LANG_URL = "";
	protected $current_lang;
	protected $main_title;
	var $current_country_code;
	protected $_loginAdmin = null;
	private $CLEAR_STR = "?clr";

	public function __construct($no_check=false) {
		parent::__construct ();

		//現在時刻
		session()->put('now_date', date('Y-m-d H:i:s'));

		//ログイン者のチェック
		if ($no_check) {
			session()->forget('appmanage.account');
		} else {
			$account = AppInfoTable::getInstance()->getAccount(session()->pull('appmanage.account'));
			if (empty($account)) {
				return redirect('/appmanage')->send();
			} else {
				session()->put('appmanage.account', $account);
			}
		}
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
}
