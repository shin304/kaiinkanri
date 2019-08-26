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


class HomeController extends _BaseAppmanageController {

	private static $TOP_URL = '/appmanage/home?menu';
	private $request;

	public function __construct(Request $request) {
		parent::__construct();
		$this->request = $request;
	}

	public function execute() {

		$_a = [];

		//会員数
		$_a['member_counts'] = AppInfoTable::getInstance()->getMemberCount(session('appmanage.account'));

		//問題集数
		$_a['workbook_counts'] = AppInfoTable::getInstance()->getWorkbookCount(session('appmanage.account'));

		return view('Appmanage.Home.index')->with('_a', $_a);
	}

}
