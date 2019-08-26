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
use Illuminate\Support\Facades\Response;

use App\Model\AppInfoTable;
use App\Model\AppMemberTable;
use App\Model\AppWorkbookTable;


class WorkbookController extends _BaseApiController {

	public $request;

	public function __construct(Request $request) {
		$this->request = $request;
		parent::__construct ();
	}

    public function execute() {
        $book_list = AppWorkbookTable::getInstance()->getPublishingWorkbookList($this->request);

        $type = AppWorkbookTable::getInstance()->getPublishingWorkbookTypeList($this->request);
        return $this->returnDic(['type'=>$type, 'book'=>$book_list]);
    }

	public function executeList() {
		$book_list = AppWorkbookTable::getInstance()->getPublishingWorkbookList($this->request);
		return $this->returnList($book_list);
	}

	public function executeDetail() {
		if (empty($this->request['workbook_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$workbook = AppWorkbookTable::getInstance()->getWorkbook($this->request);
		return $this->returnData($workbook);
	}

	public function executeAnswer() {
		if (empty($this->request['member_id']) || empty($this->request['workbook_id']) || empty($this->request['answer_list'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		// 会員チェック
		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (empty($member['member_id'])) {
			return $this->returnData('会員登録されていません。', 'NG');
		}

		DB::beginTransaction();
		try {
			$record = array();
			$record['member_id'] = $this->request['member_id'];
			$record['workbook_id'] = empty($this->request['workbook_id'])? null:$this->request['workbook_id'];
			$record['chapter_id'] = empty($this->request['chapter_id'])? null:$this->request['chapter_id'];
			$record['activity_start_datetime'] = empty($this->request['activity_start_datetime'])? null:$this->request['activity_start_datetime'];
			$record['activity_end_datetime'] = empty($this->request['activity_end_datetime'])? null:$this->request['activity_end_datetime'];
			$record['activity_time'] = empty($this->request['activity_time'])? null:$this->request['activity_time'];
			$record['score'] = empty($this->request['score'])? null:$this->request['score'];
			$record['per_c_answer'] = empty($this->request['per_c_answer'])? null:$this->request['per_c_answer'];
			$record['id'] = $this->_save('app_my_record', $record);

			$answer_list = json_decode($_REQUEST['answer_list'], true);
			foreach ($answer_list as $value) {
				$answer = array();
				$answer['record_id'] = $record['id'];
				$answer['question_id'] = empty($value['question_id'])? null:$value['question_id'];
				$answer['answer'] = empty($value['answer'])? null:$value['answer'];
				$answer['is_correct'] = empty($value['is_correct'])? 0:$value['is_correct'];
				$this->_save('app_my_answer', $answer);
			}

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData('登録しました。');
	}

	public function executeGetData() {
		if (empty($this->request['workbook_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$workbook = AppWorkbookTable::getInstance()->getWorkbook($this->request);
		return $this->returnData($workbook);
	}

	public function executeGetFile() {
		if (empty($this->request['workbook_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		//　問題集チェック
		$workbook = AppWorkbookTable::getInstance()->getWorkbook($this->request);
		if (empty($workbook['id'])) {
			return $this->returnData('この問題集は有効ではありません。', 'NG');
		}

		// 会員チェック
		$member = AppMemberTable::getInstance()->getMember($this->request);
		if (empty($member['member_id']) && empty($workbook['is_preinstalled'])) {
			return $this->returnData('ダウンロードするためには会員登録してください。', 'NG');
		}

		// 存在チェック
		$file_name = $workbook['code'] . '.zip';
		$file_path = storage_path('app/public/appmanage/book/') . $file_name;
		if (!file_exists($file_path)) {
			return $this->returnData('この問題集は有効ではありません。', 'NG');
		}

		$headers = ['Content-Type'=>'application/zip', 'X-Ictel-filename'=>$file_name];
		return Response::download($file_path , $file_name, $headers);
	}

	public function executeBookType() {
		$type = AppWorkbookTable::getInstance()->getPublishingWorkbookTypeList($this->request);
		return $this->returnList($type);
	}

}
