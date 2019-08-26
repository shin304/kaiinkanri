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


class InfoController extends _BaseApiController {

	public $request;

	const API_MAJOR_VERSION = 1;
	const API_MINOR_VERSION = 0;
	const API_REVISION = 1;

	public function __construct(Request $request) {
		$this->request = $request;
		parent::__construct ();
	}

	public function executeVersion(Request $request) {
		$ver = array('major'=>self::API_MAJOR_VERSION, 'minor'=>self::API_MINOR_VERSION, 'reversion'=>self::API_REVISION);
		return $this->returnData($ver);
	}

}
