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
use App\Model\AppMemberTable;
use App\Model\AppWorkbookTable;


class NewsController extends _BaseApiController {

	public $request;

	public function __construct(Request $request) {
		$this->request = $request;
		parent::__construct ();
	}

    public function execute() {
        $news_list = AppInfoTable::getInstance()->getPublishingNewsList($this->request);

        $types = AppInfoTable::getInstance()->getNewTypeList($this->request);
        $ret = [];
        foreach ($types as $key => $type) {
            $ret[] = array('news_icon_type'=>$key+1, 'news_type_id'=>$type['news_type_id'], 'news_type_name'=>$type['title'], 'news_entry_url'=>$type['news_entry_url']);
        }
        return $this->returnDic(['type'=>$ret, 'news'=>$news_list]);
    }

	public function executeList() {
		$news_list = AppInfoTable::getInstance()->getPublishingNewsList($this->request);
		return $this->returnList($news_list);
	}

	public function executeDetail() {
		if (empty($this->request['news_id'])) {
			return $this->returnData('no parameter', 'NG');
		}

		$news = AppInfoTable::getInstance()->getNews($this->request);
		return $this->returnData($news);
	}

	public function executeType() {
		$types = AppInfoTable::getInstance()->getNewTypeList($this->request);

		$ret = [];
		foreach ($types as $key => $type) {
			$ret[] = array('news_icon_type'=>$key+1, 'news_type_id'=>$type['news_type_id'], 'news_type_name'=>$type['title'], 'news_entry_url'=>$type['news_entry_url']);
		}
		return $this->returnList($ret);
	}

	public function executeMenu() {
		$news = AppInfoTable::getInstance()->getMenu($this->request);
		return $this->returnList($news);
	}

	public function executeEntry() {
		if (empty($this->request['member_id'])) {
			return $this->returnData('パラメータが不足しています。', 'NG');
		}

		$member = AppMemberTable::getInstance()->getMember($this->request);

		// news
		$news = [];
		$news['id'] = empty($this->request['news_id'])? null:$this->request['news_id'];
		$news['info_id'] = empty($member['info_id'])? null:$member['info_id'];
		$news['pschool_id'] = empty($member['pschool_id'])? null:$member['pschool_id'];
		$news['news_type_id'] = empty($this->request['type_id'])? null:$this->request['type_id'];
		$news['title'] = empty($this->request['title'])? null:$this->request['title'];
		$news['subtitle'] = empty($member['nickname'])? $member['member_name']:$member['nickname'];
		$news['content'] = empty($this->request['content'])? null:$this->request['content'];
		$news['disp_date'] = empty($this->request['disp_date'])? date('Y-m-d'):$this->request['disp_date'];
		$news['publish_date_from'] = empty($this->request['show_date'])? date('Y-m-d H:i:s', strtotime("-1 seconds")):$this->request['show_date'];
		$news['publish_date_to'] = empty($this->request['hide_date'])? null:$this->request['hide_date'];
		$news['member_id'] = empty($member['member_id'])? null:$member['member_id'];

		// validator
		$rules = array();
		$messages = array();
		$rules['title'] = 'required|between:1,255';
		$messages['title.between'] = 'タイトルは255文字以内にしてください。';
		$messages['title.required'] = 'タイトルが指定されていません。';
		$rules['content'] = 'required';
		$messages['content.required'] = '内容が指定されていません。';
		$rules['member_id'] = 'required';
		$messages['member_id.required'] = '会員が指定されていません。';
		$validator = Validator::make($news, $rules, $messages);
		if ($validator->fails()) {
			return $this->returnList($validator->errors()->messages(), 'NG');
		}

		DB::beginTransaction();
		try {
			$news['id'] = $this->_save('app_news', $news);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return $this->returnData('システムエラーが発生しました。', 'NG');
		}

		return $this->returnData('登録しました。');
	}
}
