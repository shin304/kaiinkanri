<?php

namespace App\Http\Controllers\Appmanage;

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


class LoginController extends _BaseAppmanageController {

	private static $TOP_URL = '';
	private static $ONE_HOUR = 60 * 60;/* 一時間 */

	private $request;

	public function __construct(Request $request) {
		parent::__construct(true);
		$this->request = $request;
	}

	public function execute() {
		return view('Appmanage.Login.index');
	}

	public function executeLogin() {
		//debug mode
		if (config('app.debug') && empty($this->request['_s']['loginid']) && empty($this->request['_s']['password'])) {
			$this->request['_s'] = ['loginid'=>'admin', 'password'=>'1234'];
		}

		//param check
		$rules = ['loginid'=>'required', 'password'=>'required'];
		$messages = ['loginid.required'=>'ログインIDが指定されていません。', 'password.required'=>'パスワードが指定されていません。'];
		$validator = Validator::make($this->request['_s'], $rules, $messages);
		if ($validator->fails()) {
			session()->put('appmanage.messages', ['ログインIDまたはパスワードが指定されていません。']);
			return view('Appmanage.Login.index');
		}

		//account check
		$account = AppInfoTable::getInstance()->getAccount($this->request['_s']);
		if (empty($account) || empty($account['pschool_name'])) {
			session()->put('appmanage.messages', ['ログインIDまたはパスワードが間違っています。']);
			return view('Appmanage.Login.index');
		} else {
			//ログイン情報をsessionに保持
			session()->put('appmanage', ['account'=>$account]);
		}

		return redirect('/appmanage/home');
	}

	public function executeLogout() {
		return redirect('/appmanage');
	}

}
