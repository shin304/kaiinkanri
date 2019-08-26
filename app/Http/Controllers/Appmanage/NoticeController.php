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

use App\Model\ParentTable;
use App\Model\StudentTable;
use App\Model\PschoolTable;
use App\Model\StaffTable;
use App\Model\CoachTable;
use App\Model\LoginAccountTable;
use App\Model\HierarchyTable;


class NoticeController extends _BaseAppmanageController {

	private static $TOP_URL = 'notice?menu';
	private static $ONE_HOUR = 60 * 60;/* 一時間 */

	private $request;

	public function __construct(Request $request) {
		parent::__construct ();
		$this->request = $request;
	}

	public function execute() {
		$errors = [];
		if (session()->has('errors')) {
			session()->forget('errors');
		}
		return view ( 'Appmanage.Notice.index' )->with( 'errors',$errors );
	}

}
