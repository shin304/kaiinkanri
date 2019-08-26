<?php

namespace App\Http\Controllers\Appmanage;

use DB;
use Log;
use DateTime;
use Validator;
use File;

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


class NewsController extends _BaseAppmanageController {

	private static $TOP_URL = 'news?menu';
	private static $ONE_HOUR = 60 * 60;/* 一時間 */

	private $request;

	public function __construct(Request $request) {
		parent::__construct ();
		$this->request = $request;
	}

	public function execute() {
		$_a = [];

		if (empty($this->request['_c'])) {
			if (session()->has('appmanage.news')) {
				$this->request['_c'] = session()->get('appmanage.news');
			} else {
				$this->request['_c'] = ['info_id'=>session()->get('appmanage.account.info_id'), 'status'=>2];
			}
		}

		//利用者
		if (session()->get('appmanage.account.auth_type') == 1) {
			$_a['info_list'] = AppInfoTable::getInstance()->getInfoList();
		} else {
			$_a['info_list'] = [];
		}

		session()->put('appmanage.news', $this->request['_c']);
		$_a['list'] = AppInfoTable::getInstance()->getNewsList($this->request['_c']);

		return view('Appmanage.News.index')->with('_a', $_a);
	}

	public function executeEdit() {
		$_a = [];

		if (empty($this->request['_i'])) {
			$news = AppInfoTable::getInstance()->getNews($this->request);
			if (empty($news)) {
				$news = [
					'info_id'=>session()->get('appmanage.account.info_id'),
					'subtitle'=>session()->get('appmanage.account.pschool_name'),
					'disp_date'=>date('Y-m-d'),
					'publish_date_from'=>date('Y-m-d H', strtotime('+1 hours')).':00:00',
				];
			}
			$this->request['_i'] = $news;
		}

		//利用者
		$_a['info_list'] = AppInfoTable::getInstance()->getInfoList();

		//種別
		$_a['news_type_list'] = AppInfoTable::getInstance()->getNewTypeList($this->request['_i']);

		return view('Appmanage.News.input')->with('_a', $_a);
	}

	public function executeSave() {
		$_i = $this->request['_i'];

		//news
		$news = [];
		$news['id'] = $_i['news_id'];
		$news['info_id'] = empty($_i['info_id'])? session()->get('appmanage.account.info_id'):$_i['info_id'];
		$news['pschool_id'] = AppInfoTable::getInstance()->getPschoolIdByInfoId($_i);
		$news['news_type_id'] = empty($_i['news_type_id'])? null:$_i['news_type_id'];
		$news['title'] = empty($_i['title'])? null:$_i['title'];
		$news['subtitle'] = empty($_i['subtitle'])? null:$_i['subtitle'];
		$news['content_title'] = empty($_i['content_title'])? null:$_i['content_title'];
		$news['content'] = empty($_i['content'])? null:$_i['content'];
		$news['disp_date'] = empty($_i['disp_date'])? null:$_i['disp_date'];
		$news['publish_date_from'] = empty($_i['publish_date_from'])? null:$_i['publish_date_from'];
		$news['publish_date_to'] = empty($_i['publish_date_to'])? null:$_i['publish_date_to'];
		$news['link_url'] = empty($_i['link_url'])? null:$_i['link_url'];
		$news['link_pdf'] = empty($_i['link_pdf'])? null:$_i['link_pdf'];
		$news['file_pdf'] = (empty($_i['link_pdf']) || empty($_i['file_pdf']))? null:$_i['file_pdf'];
		$news['has_notification'] = empty($_i['notification_datetime'])? 0:1;
		$news['notification_datetime'] = empty($_i['notification_datetime'])? null:$_i['notification_datetime'];

		//file
		$file = $this->request->file('_f.link_pdf');
		if (!empty($file) && $file->isValid()) {
 			$file_path = $file->store('public/appmanage/temp');
			$file_public = $file->move(public_path('files/appmanage'), $file_path);
			$news['file_pdf'] = $file->getClientOriginalName();
			$news['link_pdf'] = str_replace(public_path(), '', url($file_public));
		}

		//validator
		$rules = [];
		$messages = [];
		$rules['info_id'] = 'required';
		$messages['info_id.required'] = '利用者が指定されていません。';
		$rules['title'] = 'required';
		$messages['title.required'] = 'タイトルが指定されていません。';
		$rules['subtitle'] = 'required';
		$messages['subtitle.required'] = 'サブタイトルが指定されていません。';
		$rules['content_title'] = 'required';
		$messages['content_title.required'] = '内容サマリが指定されていません。';
		$rules['content'] = 'required';
		$messages['content.required'] = '内容が指定されていません。';
		$rules['disp_date'] = 'required|date';
		$messages['disp_date.required'] = '表示日が指定されていません。';
		$messages['disp_date.date'] = '表示日が不正です。';
		$rules['publish_date_from'] = 'nullable|date';
		$messages['publish_date_from.date'] = '公開開始日時が不正です。';
		$rules['publish_date_to'] = 'nullable|date';
		$messages['publish_date_to.date'] = '公開終了日時が不正です。';
		$rules['link_url'] = 'nullable|url';
		$messages['link_url.url'] = 'ホームページが不正です。';
		$rules['notification_datetime'] = 'nullable|date';
		$messages['notification_datetime.date'] = '通知日時が不正です。';
		$validator = Validator::make($news, $rules, $messages);
		if ($validator->fails()) {
			$news['news_id'] = $news['id'];
			$this->request['_i'] = $news;
			session()->put('appmanage.messages', $validator->errors()->messages());
			return $this->executeEdit();
		}

		//save
		DB::beginTransaction();
		try {
			$news_id = $this->_save('app_news', $news);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			session()->put('appmanage.messages', [['システムエラーが発生しました。']]);
			return $this->executeEdit();
		}

		session()->put('appmanage.message', '更新しました。');
		return redirect('/appmanage/news');
	}

	public function executeDelete() {
		//save
		DB::beginTransaction();
		try {
			$this->_save('app_news', ['id'=>$this->request['news_id'], 'delete_date'=>date('Y-m-d H:i:s')]);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			session()->put('appmanage.messages', [['システムエラーが発生しました。']]);
			return $this->execute();
		}

		session()->put('appmanage.message', '削除しました。');
		return redirect('/appmanage/news');
	}

}
