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


class PushController extends _BaseApiController {

	public $request;

	public function __construct(Request $request) {
		$this->request = $request;
		parent::__construct ();
	}

	public function executeRegist() {
		$old = AppInfoTable::getInstance()->getPush($this->request);

		DB::beginTransaction();
		try {
			$push = [
				'id'=>$old['id'],
				'member_id'=>empty($this->request['member_id'])? null:$this->request['member_id'],
				'device_token'=>empty($this->request['dvt'])? null:$this->request['dvt'],
				'identifierForVendor'=>empty($this->request['idv'])? null:$this->request['idv'],
			];
			$push['id'] = $this->_save('app_push', $push);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData('thanks');
	}

}
