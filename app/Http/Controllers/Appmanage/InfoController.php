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


class InfoController extends _BaseAppmanageController {

	private $request;

	public function __construct(Request $request) {
		parent::__construct ();
		$this->request = $request;
	}

	public function execute() {
		$_a = ['list'=>[]];

		if (empty($this->request['_c'])) {
			if (session()->has('appmanage.info')) {
				$this->request['_c'] = session()->get('appmanage.info');
			} elseif (session()->get('appmanage.account.auth_type') == 1) {
				$this->request['_c'] = [];
			} else {
				$this->request['_c'] = ['info_id'=>session()->get('appmanage.account.info_id')];
			}
		}

		//一覧
		session()->put('appmanage.info', $this->request['_c']);
		$info_list = AppInfoTable::getInstance()->getInfoList($this->request['_c']);
		foreach ($info_list as $info) {
			$_a['list'][] = AppInfoTable::getInstance()->getInfo(['info_id'=>$info['info_id']]);
		}

		return view('Appmanage.Info.index')->with('_a', $_a);
	}

	public function executeEdit() {
		$_a = [];

		if (empty($this->request['_i'])) {
			$this->request['_i'] = AppInfoTable::getInstance()->getInfo($this->request);
		}

		//新規の利用者
		$_a['pschool_list'] = AppInfoTable::getInstance()->getNewInfoList($this->request['_i']);

		//課金リスト
		$_a['itunes_list'] = AppInfoTable::getInstance()->getiTunesList();

		//有料コース
		$_a['list'] = AppInfoTable::getInstance()->getFeeList($this->request['_i']);

		//有料ダミー
		$dummy_policy = ['id'=>'', 'title'=>'会員規約', 'policy'=>'◯◯◯◯会員になると...'];
		$_a['dummy_fee'] =  ['id'=>'', 'title'=>'◯◯◯◯会員', 'price'=>'', 'member_cnt'=>0, 'itunes_code'=>'', 'policy_list'=>[$dummy_policy], 'register_date'=>'', 'update_date'=>'', 'public_flag'=>0];

		return view('Appmanage.Info.input')->with('_a', $_a);
	}

	public function executeSave() {
		$_i = $this->request['_i'];

		//app_info
		$app_info = [];
		$app_info['id'] = $_i['info_id'];
		$app_info['pschool_id'] = empty($_i['pschool_id'])? null:$_i['pschool_id'];
		$app_info['workbook_title'] = empty($_i['workbook_title'])? null:$_i['workbook_title'];
		$app_info['workbook_type_ids'] = empty($_i['book_type_ids'])? null:implode(',', $_i['book_type_ids']);
		$app_info['news_title'] = empty($_i['news_title'])? null:$_i['news_title'];
		$app_info['news_type_ids'] = null;
		$app_info['news_type_titles'] = null;

		//news_type
		$news_type_list = [];
		foreach ($_i['news_type_list'] as $ii=>$type) {
			$news_type = [];
			$news_type['id'] = empty($_i['info_id'])? null:$type['id'];
			$news_type['pschool_id'] = empty($_i['pschool_id'])? null:$_i['pschool_id'];
			$news_type['title'] = empty($type['title'])? null:$type['title'];
			$news_type['icon_type'] = $ii + 1;
			$news_type['active_flag'] = empty($type['active_flag'])? 0:1;
			$news_type['entry_url'] = empty($type['entry_url'])? null:1;
			$news_type_list[] = $news_type;

			if (!empty($news_type['active_flag'])) {
				$app_info['news_type_ids'] = $type['id'];
				$app_info['news_type_titles'] = $type['title'];

				//有効なお知らせはタイトルを入れましょう
				if (empty($news_type['title'])) {
					$app_info['news_type_titles'] = null;
					break;
				}
			}
		}

		//validator
		$rules = [];
		$messages = [];
		$rules['workbook_title'] = 'required';
		$messages['workbook_title.required'] = 'メニュー名の問題集一覧が指定されていません。';
		$rules['news_title'] = 'required';
		$messages['news_title.required'] = 'メニュー名のお知らせが指定されていません。';
		$rules['workbook_type_ids'] = 'required';
		$messages['workbook_type_ids.required'] = '問題集種別が選択されていません。';
		$rules['news_type_ids'] = 'required';
		$messages['news_type_ids.required'] = 'お知らせ種別が選択されていません。';
		$rules['news_type_titles'] = 'required';
		$messages['news_type_titles.required'] = 'お知らせ種別が指定されていません。';
		$validator = Validator::make($app_info, $rules, $messages);
		if ($validator->fails()) {
			session()->put('appmanage.messages', $validator->errors()->messages());
			return $this->executeEdit();
		}

		//save
		DB::beginTransaction();
		try {
			unset($app_info['news_type_titles']);
			$app_info['id'] = $this->_save('app_info', $app_info);

			//news_type
			$news_type_ids = [];
			foreach ($news_type_list as &$news_type) {
				$news_type['info_id'] = $app_info['id'];
				$news_type['id'] = $this->_save('app_news_type', $news_type);
				if (!empty($news_type['active_flag'])) {
					$news_type_ids[] = $news_type['id'];
				}
			}
			$this->_save('app_info', ['id'=>$app_info['id'], 'news_type_ids'=>implode(',', $news_type_ids)]);

			//fee
			if (!empty($_i['fee_list'])) {
				$itunes_list = AppInfoTable::getInstance()->getiTunesList();

				foreach ($_i['fee_list'] as $fee) {
					foreach ($itunes_list as $iturns) {
						if ($fee['itunes_code'] == $iturns['itunes_code'] && !empty($fee['policy_list'])) {
							$app_fee = [];
							$app_fee['id'] = empty($fee['id'])? null:$fee['id'];
							$app_fee['info_id'] = $app_info['id'];
							$app_fee['pschool_id'] = $app_info['pschool_id'];
							$app_fee['title'] = empty($fee['title'])? null:$fee['title'];
							$app_fee['price'] = empty($iturns['price'])? 0:$iturns['price'];
							$app_fee['fee_type'] = empty($iturns['fee_type'])? 3:$iturns['fee_type'];
							$app_fee['itunes_code'] = empty($iturns['itunes_code'])? null:$iturns['itunes_code'];
							$app_fee['public_flag'] = empty($fee['public_flag'])? 0:1;
							$app_fee['delete_date'] = empty($fee['del_flag'])? null:date('Y-m-d H:i:s');
							$app_fee['id'] = $this->_save('app_fee', $app_fee);

							foreach ($fee['policy_list'] as $policy) {
								$app_policy = [];
								$app_policy['id'] = empty($policy['id'])? null:$policy['id'];
								$app_policy['info_id'] = $app_info['id'];
								$app_policy['pschool_id'] = $app_info['pschool_id'];
								$app_policy['app_fee_id'] = $app_fee['id'];
								$app_policy['title'] = empty($policy['title'])? null:$policy['title'];
								$app_policy['policy'] = empty($policy['policy'])? null:$policy['policy'];
								$app_policy['public_flag'] = 1;
								$app_policy['delete_date'] = empty($fee['del_flag'])? null:date('Y-m-d H:i:s');
								$app_policy['id'] = $this->_save('app_fee_policy', $app_policy);
							}
							break;
						}
					}
				}
			}

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			session()->put('appmanage.messages', [['システムエラーが発生しました。']]);
			return $this->executeEdit();
		}

		session()->put('appmanage.message', '更新しました。');
		return redirect('/appmanage/info');
	}

	public function executeDelete() {
		//save
		DB::beginTransaction();
		try {
			$this->_save('app_info', ['id'=>$this->request['info_id'], 'delete_date'=>date('Y-m-d H:i:s')]);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			session()->put('appmanage.messages', [['システムエラーが発生しました。']]);
			return $this->executeInfo();
		}

		session()->put('appmanage.message', '削除しました。');
		return redirect('/appmanage/info');
	}

	private function getAssignVals() {
		$assign = [];

		$assign['pschool_id'] = $this->getLoginSession('pschool_id');

		$assign['pschool_list'] = [];

		$assign['book_select_list'] = [1=>'高校受験', 2=>'大学受験', 3=>'社会人'];

		$assign['subject_list'] = [];

		$assign['choices_select_list'] = ['a,b,c,d', 'A,B,C,D', '1,2,3,4', 'ア,イ,ウ,エ', 'あ,い,う,え', 'い,ろ,は,に'];

		$assign['default_choices'] = ['ア','イ','ウ','エ'];

		$assign['tag'] = ['title'=>"問題集",'to'=>'公開', 'name'=>'', 'param'=>'', 'input'=>'input'];

		$assign['question_select_list'] = [1=>'テキスト', 2=>'PDF'];

		$assign['errors'] = [];

		return $assign;
	}

}
