<?php

namespace App\Http\Controllers\Appmanage;

use DB;
use Log;
use DateTime;
use Validator;
use View;
use Exception;

use App\Common\Constants;
use App\Common\Email;
use App\ConstantsModel;
use App\Lang;
use App\MessageFile;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Model\AppInfoTable;
use App\Model\AppWorkbookTable;


class WorkbookController extends _BaseAppmanageController {

	private static $TOP_URL = 'workbook?menu';
	private static $ONE_HOUR = 60 * 60;/* 一時間 */

	private $request;

	public function __construct(Request $request) {
		parent::__construct ();
		$this->request = $request;
	}

	private function getAssignVals() {
		$assign = [];

		$assign['subject_list'] = AppWorkbookTable::getInstance()->getSubjectList();

		$assign['question_select_list'] = [1=>'テキスト', 2=>'PDF'];

		//ダミー
		$dummy_choice_list = [
				['id'=>0, 'choice_true'=>1, 'choice_mark'=>'ア', 'choice_word'=>'', 'choice_regi_type'=>1, 'choice_file'=>'', 'choice_file_name'=>''],
				['id'=>0, 'choice_true'=>0, 'choice_mark'=>'イ', 'choice_word'=>'', 'choice_regi_type'=>1, 'choice_file'=>'', 'choice_file_name'=>''],
				['id'=>0, 'choice_true'=>0, 'choice_mark'=>'ウ', 'choice_word'=>'', 'choice_regi_type'=>1, 'choice_file'=>'', 'choice_file_name'=>''],
				['id'=>0, 'choice_true'=>0, 'choice_mark'=>'エ', 'choice_word'=>'', 'choice_regi_type'=>1, 'choice_file'=>'', 'choice_file_name'=>''],
		];
		$dummy_question = [
				'id'=>0, 'sentence_id'=>0, 'sequence_no'=>1, 'tags'=>'問1', 'title'=>'', 'question_regi_type'=>1, 'choices'=>'ア,イ,ウ,エ', 'c_answer'=>'ア',
				'question_text'=>'', 'audio_file'=>'', 'audio_file_name'=>'', 'question_file'=>'', 'question_file_name'=>'',
				'description_text'=>'', 'description_file'=>'', 'description_file_name'=>'',
				'description_text'=>'', 'description_file'=>'', 'description_file_name'=>'',
				'choices_text'=>'', 'choices_file'=>'', 'choices_file_name'=>'', 'choice_list'=>$dummy_choice_list,
		];
		$assign['dummy_chapter'] = ['id'=>0, 'title'=>'', 'subtitle'=>'', 'subject_id'=>5, 'exam_time'=>10, 'full_score'=>100, 'question_list'=>[$dummy_question]];

		return $assign;
	}

	public function execute() {
		$_a = $this->getAssignVals();

		if (empty($this->request['_c'])) {
			if (session()->has('appmanage.workbook')) {
				$this->request['_c'] = session()->get('appmanage.workbook');
			} else {
				$this->request['_c'] = ['info_id'=>session()->get('appmanage.account.info_id')];
			}
		}

		//利用者
		if (session()->get('appmanage.account.auth_type') == 1) {
			$_a['info_list'] = AppInfoTable::getInstance()->getInfoList();
		} else {
			$_a['info_list'] = [];
		}

		//種別
		$_a['book_type_list'] = AppWorkbookTable::getInstance()->getTypeList($this->request['_c']);

		session()->put('appmanage.workbook', $this->request['_c']);
		$_a['list'] = AppWorkbookTable::getInstance()->getWorkbookList($this->request['_c']);

		return view('Appmanage.Workbook.index')->with('_a', $_a);
	}

	public function executeEdit() {
		$_a = $this->getAssignVals();

		if (empty($this->request['_i'])) {
			$workbook = AppWorkbookTable::getInstance()->getWorkbook($this->request);
			if (empty($workbook['info_id'])) {
				$workbook = [
					'info_id'=>session()->get('appmanage.account.info_id'),
					'subtitle'=>session()->get('appmanage.account.pschool_name'),
					'chapter_list'=>[$_a['dummy_chapter']],
				];
			}
			$this->request['_i'] = $workbook;
		}

		//利用者
		if (session()->get('appmanage.account.auth_type') == 1) {
			$_a['info_list'] = AppInfoTable::getInstance()->getInfoList();
		} else {
			$_a['info_list'] = [];
		}

		//種別
		$_a['book_type_list'] = AppWorkbookTable::getInstance()->getTypeList($this->request['_i']);

		return view('Appmanage.Workbook.input')->with('_a', $_a);
	}

	public function executeSave() {
		$_i = empty($this->request['_i'])? []:$this->request['_i'];
		$_f = empty($this->request['_f'])? []:$this->request['_f'];

		$_i['info_id'] = empty($_i['info_id'])? session()->get('appmanage.account.info_id'):$_i['info_id'];

		if (!empty($_f['chapter_list'])) {
			foreach ($_f['chapter_list'] as $ccc=>$chapter) {
				if (empty($chapter['question_list'])) {
					continue;
				}
				foreach ($chapter['question_list'] as $qqq=>$question) {
					foreach ($question as $key=>$quest) {
						if ($key == 'choice_list') {
							foreach ($question['choice_list'] as $sss=>$choice) {
								$file = $this->request->file("_f.chapter_list.{$ccc}.question_list.{$qqq}.choice_list.{$sss}.choice_file");
								if (!empty($file) && $file->isValid()) {
									$file_path = $file->store('public/appmanage/upload');
									$_i['chapter_list'][$ccc]['question_list'][$qqq]['choice_list'][$sss]['choice_file'] = $file_path;
								}
							}
						} else {
							$file = $this->request->file("_f.chapter_list.{$ccc}.question_list.{$qqq}.{$key}");
							if (!empty($file) && $file->isValid()) {
								$file_path = $file->store('public/appmanage/upload');
								$_i['chapter_list'][$ccc]['question_list'][$qqq][$key] = $file_path;
// 								$file_public = $file->move(public_path('files/appmanage'), $file_path);
//								$news['file_pdf'] = $file->getClientOriginalName();
// 								$news['link_pdf'] = str_replace(public_path(), '', url($file_public));
							}
						}
					}
				}
			}
		}


		//validator book
		$errors = [];
		$rules = [];
		$messages = [];
		$rules['info_id'] = 'required';
		$messages['info_id.required'] = '利用者が指定されていません。';
		$rules['workbook_type_id'] = 'required';
		$messages['workbook_type_id.required'] = '種別が指定されていません。';
		$rules['title'] = 'required';
		$messages['title.required'] = '問題集タイトルが指定されていません。';
		$rules['subtitle'] = 'required';
		$messages['subtitle.required'] = '問題集サブタイトルが指定されていません。';
		$rules['detail_text'] = 'required';
		$messages['detail_text.required'] = '問題集詳細が指定されていません。';
		$rules['chapter_list'] = 'required';
		$messages['chapter_list.required'] = '内容が指定されていません。';
		$validator = Validator::make($_i, $rules, $messages);
		if ($validator->fails()) {
			$errors = $validator->errors()->messages();
		}

		//validator chapter
		$c_errors = [];
		$c_rules = [];
		$c_messages = [];
		$c_rules['title'] = 'required';
		$c_messages['title.required'] = 'タイトルが指定されていません。';
		$c_rules['subtitle'] = 'required';
		$c_messages['subtitle.required'] = 'サブタイトルが指定されていません。';
		$c_rules['subject_id'] = 'required';
		$c_messages['subject_id.required'] = '教科が指定されていません。';
		$c_rules['question_list'] = 'required';
		$c_messages['question_list.required'] = '設問が指定されていません。';
		foreach ($_i['chapter_list'] as $chapter) {
			$validator = Validator::make($chapter, $c_rules, $c_messages);
			if ($validator->fails()) {
				$c_errors = array_merge($c_errors, $validator->errors()->messages());
			}
		}
		$errors = array_merge($errors, array_unique($c_errors));

		//validator question
		$q_errors = [];
		$q_rules = [];
		$q_messages = [];
		$q_rules['title'] = 'required';
		$q_messages['title.required'] = '設問タイトルが指定されていません。';
		$q_rules['choice_list'] = 'required';
		$q_messages['choice_list.required'] = '選択肢が指定されていません。';
		foreach ($_i['chapter_list'] as $chapter) {
			foreach ($chapter['question_list'] as $question) {
				$validator = Validator::make($question, $q_rules, $q_messages);
				if ($validator->fails()) {
					$q_errors = array_merge($q_errors, $validator->errors()->messages());
				}
			}
		}
		$errors = array_merge($errors, array_unique($q_errors));

		if (!empty($errors)) {
			$this->request['_i'] = $_i;
			session()->put('appmanage.messages', $errors);
			return $this->executeEdit();
		}

		//save
		DB::beginTransaction();
		try {
			$book = [];
			$book['id'] = (empty($_i['id']) || !empty($_i['copy_flag']))? null:$_i['id'];
			$book['info_id'] = empty($_i['info_id'])? null:$_i['info_id'];
			$book['pschool_id'] = AppInfoTable::getInstance()->getPschoolIdByInfoId($_i);
			$book['title'] = empty($_i['title'])? null:$_i['title'];
			$book['subtitle'] = empty($_i['subtitle'])? null:$_i['subtitle'];
			$book['detail_text'] = empty($_i['detail_text'])? null:$_i['detail_text'];
			$book['workbook_type_id'] = empty($_i['workbook_type_id'])? 1:$_i['workbook_type_id'];
			$book['is_free'] = 1;
			$book['active_flag'] = 1;
			$book['id'] = $this->_save('app_workbook', $book);

			foreach ($_i['chapter_list'] as $_i_chapter) {
				$chapter = [];
				$chapter['id'] = (empty($_i_chapter['id']) || !empty($_i['copy_flag']))? null:$_i_chapter['id'];
				$chapter['workbook_id'] = $book['id'];
				$chapter['subject_id'] = empty($_i_chapter['subject_id'])? null:$_i_chapter['subject_id'];
				$chapter['title'] = empty($_i_chapter['title'])? null:$_i_chapter['title'];
				$chapter['subtitle'] = empty($_i_chapter['subtitle'])? null:$_i_chapter['subtitle'];
				$chapter['exam_time'] = empty($_i_chapter['exam_time'])? 10:$_i_chapter['exam_time'];
				$chapter['full_score'] = empty($_i_chapter['full_score'])? 100:$_i_chapter['full_score'];
				$chapter['id'] = $this->_save('app_workbook_chapter', $chapter);

				foreach ($_i_chapter['question_list'] as $qqq=>$_i_question) {
					$sentence = [];
					$sentence['id'] = (empty($_i_question['sentence_id']) || !empty($_i['copy_flag']))? null:$_i_question['sentence_id'];
					$sentence['info_id'] = $book['info_id'];
					$sentence['pschool_id'] = $book['pschool_id'];
					$sentence['subject_id'] = $chapter['subject_id'];
					$sentence['title'] = empty($_i_question['title'])? null:$_i_question['title'];
					$sentence['question_regi_type'] = empty($_i_question['question_regi_type'])? null:$_i_question['question_regi_type'];
					$sentence['question_file'] = empty($_i_question['question_file'])? null:$_i_question['question_file'];
					$sentence['question_file_name'] = empty($_i_question['question_file_name'])? null:$_i_question['question_file_name'];
					$sentence['question_text'] = empty($_i_question['question_text'])? null:$_i_question['question_text'];
					$sentence['audio_file'] = empty($_i_question['audio_file'])? null:$_i_question['audio_file'];
					$sentence['audio_file_name'] = empty($_i_question['audio_file_name'])? null:$_i_question['audio_file_name'];
					$sentence['description_regi_type'] = empty($_i_question['question_regi_type'])? null:$_i_question['question_regi_type'];
					$sentence['description_file'] = empty($_i_question['description_file'])? null:$_i_question['description_file'];
					$sentence['description_file_name'] = empty($_i_question['description_file_name'])? null:$_i_question['description_file_name'];
					$sentence['description_text'] = empty($_i_question['description_text'])? null:$_i_question['description_text'];
					$sentence['choices_file'] = empty($_i_question['choices_file'])? null:$_i_question['choices_file'];
					$sentence['choices_file_name'] = empty($_i_question['choices_file_name'])? null:$_i_question['choices_file_name'];
					$sentence['choices_text'] = empty($_i_question['choices_text'])? null:$_i_question['choices_text'];
					$sentence['choices'] = empty($_i_question['choices'])? null:$_i_question['choices'];
					$sentence['c_answer'] = empty($_i_question['c_answer'])? null:$_i_question['c_answer'];
					$sentence['id'] = $this->_save('app_workbook_sentence', $sentence);

					$question = [];
					$question['id'] = (empty($_i_question['id']) || !empty($_i['copy_flag']))? null:$_i_question['id'];
					$question['workbook_id'] = $chapter['workbook_id'];
					$question['chapter_id'] = $chapter['id'];
					$question['sequence_no'] = empty($_i_question['sequence_no'])? $qqq+1:$_i_question['sequence_no'];
					$question['question_type'] = empty($sentence['audio_file'])? 0:1;
					$question['title'] = empty($_i_question['title'])? null:$_i_question['title'];
					$question['tags'] = empty($_i_question['tags'])? null:$_i_question['tags'];
					$question['choices'] = empty($_i_question['choices'])? null:$_i_question['choices'];
					$question['c_answer'] = empty($_i_question['c_answer'])? null:$_i_question['c_answer'];
					$question['sentence_id'] = $sentence['id'];
					$question['id'] = $this->_save('app_workbook_questions', $question);

					foreach ($_i_question['choice_list'] as $_i_choice) {
						$choice = [];
						$choice['id'] = (empty($_i_choice['id']) || !empty($_i['copy_flag']))? null:$_i_choice['id'];
						$choice['sentence_id'] = $sentence['id'];
						$choice['choice_true'] = empty($_i_choice['choice_true'])? 0:1;
						$choice['choice_mark'] = empty($_i_choice['choice_mark'])? null:$_i_choice['choice_mark'];
						$choice['choice_word'] = empty($_i_choice['choice_word'])? null:$_i_choice['choice_word'];
						$choice['choice_regi_type'] = empty($_i_choice['choice_file'])? 1:2;
						$choice['choice_file'] = empty($_i_choice['choice_file'])? null:$_i_choice['choice_file'];
						$choice['choice_file_name'] = empty($_i_choice['choice_file_name'])? null:$_i_choice['choice_file_name'];
						$choice['id'] = $this->_save('app_workbook_choice', $choice);
					}
				}
			}

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			session()->put('appmanage.messages', [['システムエラーが発生しました。']]);
			return $this->executeEdit();
		}

		$thisbook = AppWorkbookTable::getInstance()->getWorkbook(['workbook_id'=>$book['id']]);
		if (!empty($thisbook['is_public'])) {
			$this->request = ['workbook_id'=>$book['id']];
			return $this->executePack();
		} else {
			session()->put('appmanage.message', '更新しました。');
			return redirect('/appmanage/workbook');
		}
	}

	public function executePack() {
		$error = $this->goZipMode($this->request);

		// errorがあれば
		if (!empty($error)){
			session()->put('appmanage.messages', [$error]);
			return $this->execute();
		}

		session()->put('appmanage.message', '公開しました。');
		return redirect('/appmanage/workbook');
	}

	public function executeStop() {
		//save
		DB::beginTransaction();
		try {
			$this->_save('app_workbook', ['id'=>$this->request['workbook_id'], 'is_public'=>0]);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			session()->put('appmanage.messages', [['システムエラーが発生しました。']]);
			return $this->execute();
		}

		session()->put('appmanage.message', '公開中止しました。');
		return redirect('/appmanage/workbook');
	}

	public function executeDelete() {
		//save
		DB::beginTransaction();
		try {
			$now = date('Y-m-d H:i:s');
			$this->_save('app_workbook', ['id'=>$this->request['workbook_id'], 'delete_date'=>$now]);

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			session()->put('appmanage.messages', [['システムエラーが発生しました。']]);
			return $this->execute();
		}

		session()->put('appmanage.message', '削除しました。');
		return redirect('/appmanage/workbook');
	}

	private function goZipMode($req) {
//		ini_set('mbstring.internal_encoding', 'utf-8');
//		$locale = setlocale(LC_ALL, 'ja_JP.UTF-8');
		mb_language("Japanese");
		mb_internal_encoding("utf-8");
		set_time_limit(0);
//		ini_set('memory_limit', '1024M');

		$error = [];

		$workbook = AppWorkbookTable::getInstance()->getWorkbook($req);
		if (empty($workbook)) {
			$error[] = "問題集がありません。";
			return $error;
		}

		//
		DB::beginTransaction();
		try {
			//path
			$path = "public/appmanage/book/";

			$workbook['code'] = 'wb'.sprintf('%07d', $workbook['id']);
			$book_path = $path.$workbook['code']."/";
			$this->_save('app_workbook', ['id'=>$workbook['id'], 'code'=>$workbook['code'], 'icon_type'=>2, 'is_public'=>1]);
			Storage::deleteDirectory($book_path);
			Storage::delete($path.$workbook['code'].".zip");

			//book
			$index = View::make('Appmanage.Workbook.__tpl_book', $workbook);
			Storage::put($book_path.'index.html', $index->render());
			File::copy(public_path('css/appmanage/_tpl_book.css'), storage_path('app/'.$book_path.'style.css'));

			//chapter
			foreach ($workbook['chapter_list'] as $chapter) {
				$chapter['code'] = 'wc'.sprintf('%07d', $chapter['id']);
				$chapter_path = $book_path.$chapter['code']."/";
				$this->_save('app_workbook_chapter', ['id'=>$chapter['id'], 'code'=>$chapter['code']]);

				$index = View::make('Appmanage.Workbook.__tpl_chapter', $chapter);
				Storage::put($chapter_path.'index.html', $index->render());
				Storage::makeDirectory($chapter_path.'css');
				File::copy(public_path('css/appmanage/_tpl_chapter.css'), storage_path('app/'.$chapter_path.'css/style.css'));

				foreach ($chapter['question_list'] as $qqq=>$quest) {
					$quest['sequence_no'] = $qqq+1;
					if (empty($quest['question_regi_type']) || $quest['question_regi_type'] != 2){
						//テキスト
						$quest['code'] = 'wq'.sprintf('%03d', $quest['sequence_no']).'.html';
						$index = View::make('Appmanage.Workbook.__tpl_question', $quest);
						Storage::put($chapter_path.$quest['code'], $index->render());

						//音声ファイル
						if (!empty($quest['audio_file']) && Storage::exists($quest['audio_file']) && !Storage::exists($chapter_path.'audio/'.$quest['audio_file_name'])){
							Storage::copy($quest['audio_file'], $chapter_path.'audio/'.$quest['audio_file_name']);
						}
						if (!empty($quest['choices_file']) && Storage::exists($quest['choices_file']) && !Storage::exists($chapter_path.'audio/'.$quest['choices_file_name'])){
							Storage::copy($quest['choices_file'], $chapter_path.'audio/'.$quest['choices_file_name']);
						}

						//選択肢ファイル
						foreach ($quest['choice_list'] as $choice){
							if (!empty($choice['choice_file']) && Storage::exists($choice['choice_file']) && !Storage::exists($chapter_path.'image/'.$choice['choice_file_name'])){
								Storage::copy($choice['choice_file'], $chapter_path.'image/'.$choice['choice_file_name']);
							}
						}
					} else {
						//PDF
						$quest['code'] = 'wq'.sprintf('%03d', $quest['sequence_no']).'.pdf';

						//設問ファイル
						if (!empty($quest['question_file']) && Storage::exists($quest['question_file']) && !Storage::exists($chapter_path.'q/'.$quest['question_file_name'])){
							Storage::copy($quest['question_file'], $chapter_path.'q/'.$quest['question_file_name']);
						}
						if (!empty($quest['description_file']) && Storage::exists($quest['description_file']) && !Storage::exists($chapter_path.'a/'.$quest['description_file_name'])){
							Storage::copy($quest['description_file'], $chapter_path.'a/'.$quest['description_file_name']);
						}
					}
					$this->_save('app_workbook_questions', ['id'=>$quest['id'], 'code'=>$quest['code'], 'sequence_no'=>$quest['sequence_no']]);
				}
			}

			//zip
			$cmd_res = null;
			$this->createZip(storage_path('app/'.$path), $workbook['code'], $cmd_res);
			if (!empty($cmd_res)) {
				throw new Exception('ZIPの作成に失敗しました。');
			} elseif (0) {
				$zip_file = storage_path('app/'.$path.$workbook['code'].".zip");
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="sample.zip"');
				header('Content-Length: '.filesize($zip_file));
				readfile($zip_file);
			}

			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			$error[] = 'システムエラーが発生しました。';
			return $error;
		}
		//*/

		return $error;
	}

	private function createZip($compress_dir, $save_code, &$ret) {
		$output = [];
		$res = [];

		$zip_cmd = "cd {$compress_dir};/usr/bin/zip -r {$save_code}.zip {$save_code};";
		exec($zip_cmd, $output, $ret);
		return true;
	}
}
