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


class MasterController extends _BaseApiController {

	public $request;

	public function __construct(Request $request) {
		$this->request = $request;
		parent::__construct ();
	}

	public function executePref() {
		$pref_list = AppInfoTable::getInstance()->getPrefList();

		if (empty($pref_list)) {
			return $this->returnList(['都道府県がありません。'], 'NG');
		} else {
			return $this->returnList($pref_list);
		}
	}

	public function executeCity() {
		$city_list = AppInfoTable::getInstance()->getCityList($this->request);

		if (empty($city_list)) {
			return $this->returnList(['都道府県を選択してください。'], 'NG');
		} else {
			return $this->returnList($city_list);
		}
	}

	public function executeIndustryType() {
		$type = $this->convertResponse(ConstantsModel::$industryList);
		return $this->returnList($type);
	}

	public function executeEmployeesType() {
		$type = $this->convertResponse(ConstantsModel::$employeesList);
		return $this->returnList($type);
	}

	private function convertResponse($type_list) {
		$ret = array();
		foreach ($type_list as $key=>$val) {
			$ret[] = array('id'=>$key, 'name'=>$val);
		}
		return $ret;
	}
}
