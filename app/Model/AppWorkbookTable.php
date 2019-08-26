<?php

namespace App\Model;

use DB;
use App\ConstantsModel;
use Illuminate\Database\Eloquent\Model;

class AppWorkbookTable extends DbModel
{
	//
	protected $table = 'app_workbook';

	const CREATED_AT = 'register_date';
	const UPDATED_AT = 'update_date';

	private static $_instance = null;

	public static function getInstance() {
		if (is_null ( self::$_instance )) {
			self::$_instance = new AppWorkbookTable ();
		}
		return self::$_instance;
	}


	//ここに実装してください
	public function getWorkbookList($req=null) {
		//workbook
		$bind = array();
		$sql = " SELECT *, id as workbook_id
				 FROM app_workbook
				 WHERE delete_date is NULL ";
		if (!empty($req['info_id'])) {
			$sql.= " AND info_id = ? ";
			$bind[] = $req['info_id'];
		}
		if (!empty($req['title'])) {
			$sql.= " AND title like ? ";
			$bind[] = "%{$req['title']}%";
		}
		if (!empty($req['subtitle'])) {
			$sql.= " AND subtitle like ? ";
			$bind[] = "%{$req['subtitle']}%";
		}
		if (!empty($req['register_date_from'])) {
			$sql.= " AND register_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['register_date_from']));
		}
		if (!empty($req['register_date_to'])) {
			$sql.= " AND register_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['register_date_to']));
		}
		if (!empty($req['update_date_from'])) {
			$sql.= " AND update_date >= ? ";
			$bind[] = date('Y-m-d 00:00:00', strtotime($req['update_date_from']));
		}
		if (!empty($req['update_date_to'])) {
			$sql.= " AND update_date <= ? ";
			$bind[] = date('Y-m-d 23:59:59', strtotime($req['update_date_to']));
		}
		$workbook_list = $this->fetchAll($sql, $bind);

		//score
		$bind = array();
		$sql = " SELECT workbook_id, COUNT(id) as cnt, MAX(score) as max_score, MIN(score) as min_score, AVG(score) as avg_score
				 FROM app_my_record
				 -- WHERE delete_date is null
				 GROUP BY workbook_id ";
		$summary_list = $this->fetchAll($sql);

		//merge
		$ret = array();
		foreach ($workbook_list as $workbook) {
			$score = array('cnt'=>0, 'max_score'=>0, 'min_score'=>0, 'avg_score'=>0);
			foreach ($summary_list as $summary) {
				if ($workbook['id'] == $summary['workbook_id']) {
					$score = $summary;
				}
			}
			$ret[] = array_merge($workbook, $score);
		}

		return $ret;
	}

	public function getWorkbook($req=null) {
		$bind = array();

		$sql = " SELECT * , id as workbook_id
				 FROM app_workbook
				 WHERE delete_date is NULL
				 AND id = ? ";
		$bind[] = empty($req['workbook_id'])? 0:$req['workbook_id'];

		$workbook = $this->fetch($sql, $bind);
		$workbook['chapter_list'] = $this->getChapterList($req);
		return $workbook;
	}

	public function getChapterList($req=null) {
		$bind = array();

		$sql = " SELECT * , id as chapter_id
				 FROM app_workbook_chapter
				 WHERE delete_date is NULL
				 AND workbook_id = ? ";
		$bind[] = empty($req['workbook_id'])? 0:$req['workbook_id'];

		$chapter_list = $this->fetchAll($sql, $bind);

		//add question
		foreach ($chapter_list as &$chapter) {
			$chapter['question_list'] = $this->getQuestionList($chapter);
		}

		return $chapter_list;
	}

	public function getQuestionList($req=null) {
		$bind = array();

		$sql = " SELECT q.id, q.id as question_id, q.code, q.sequence_no, q.title, q.tags, q.sentence_id
		 		 , s.question_regi_type as question_type, q.full_score, q.per_c_answer, q.register_date, q.update_date
				 , s.question_regi_type, s.question_file, s.question_file_name, s.question_text
				 , s.audio_file, s.audio_file_name, s.description_file, s.description_file_name, s.description_text
				 , s.choices_file, s.choices_file_name, s.choices_text, s.choices, s.c_answer
				 FROM app_workbook_questions as q
				 LEFT JOIN app_workbook_sentence as s ON (q.sentence_id = s.id)
				 WHERE q.delete_date is NULL AND q.chapter_id = ?
				 ORDER BY q.sequence_no, q.id ";
		$bind[] = empty($req['id'])? 0:$req['id'];

		$question_list = $this->fetchAll($sql, $bind);

		//add choice
		foreach ($question_list as &$question){
			if (!empty($question['sentence_id'])){
				$bind = array();

				$sub = " SELECT id, choice_true, choice_mark, choice_word, choice_file, choice_file_name
						 FROM app_workbook_choice
						 WHERE delete_date is NULL AND sentence_id = ? ";
				$bind[] = $question['sentence_id'];
				if (!empty($question['choice_mark_require'])){
					$sub.= " AND choice_mark is not NULL ";
					$sub.= " AND (choice_word is not NULL OR choice_file is not NULL) ";
				}
				$question['choice_list'] = $this->fetchAll($sub, $bind);
			}
		}

		return $question_list;
	}

	public function getTypeList($req=null) {
		//app_info
		$bind = array();
		$sub = " SELECT workbook_type_ids as ids
				 FROM app_info
				 WHERE delete_date is NULL AND id = ? ";
		if (!empty($req['info_id'])) {
			$bind[] = $req['info_id'];
		} else {
			return [];
		}
		$app_info = $this->fetch($sub, $bind);

		//app_workbook_type
		$bind = array();
		$sql = " SELECT id as book_type_id, title
				 FROM app_workbook_type
				 WHERE delete_date is NULL AND id in (".(empty($app_info['ids'])? 1:$app_info['ids']).")
				 ORDER BY sequence_no ";

		return $this->fetchAll($sql, $bind);
	}

	public function getSubjectList($req=null) {
		$bind = array();
		$sql = " SELECT id as subject_id, name
				 FROM app_subject
				 WHERE delete_date is NULL ORDER BY sequence_no ";
		return $this->fetchAll($sql, $bind);
	}

	public function getMyRecordList($req=null) {
		$bind = array();
		$sql = " SELECT id, workbook_id, chapter_id, activity_start_datetime, activity_end_datetime, activity_time, score, per_c_answer
				 FROM app_my_record
				 WHERE member_id = ? AND delete_date is NULL ";
		$bind[] = empty($req['member_id'])? 0:$req['member_id'];
		if (!empty($req['workbook_id'])) {
			$sql.= " AND workbook_id = ? ";
			$bind[] = $req['workbook_id'];
		}
		$sql.= " ORDER BY register_date DESC ";
		$res = $this->fetchAll($sql, $bind);

		foreach ($res as &$row) {
			$bind = array();
			$sql = " SELECT question_id, answer, is_correct
					 FROM app_my_answer
					 WHERE record_id = ? AND delete_date is NULL ORDER BY question_id ";
			$bind[] = empty($row['id'])? 0:$row['id'];
			$row['answers'] = $this->fetchAll($sql, $bind);
		}

		return $res;
	}

	public function getPublishingWorkbookList($req=null) {
		$bind = array();
		$sql = " SELECT id as workbook_id, title, subtitle, is_preinstalled, is_free, price, icon_type
				 , workbook_type_id, register_date as publish_date , 0 as purchased
				 FROM app_workbook
				 WHERE delete_date is NULL and is_public=1 AND active_flag=1 ";
		if (!empty($req['member_id'])) {
			$sql.= " AND info_id IN (SELECT info_id FROM app_member WHERE id = ?) ";
			$bind[] = $req['member_id'];
		} else {
			$sql.= " AND pschool_id is NULL ";
		}
		$sql.= " ORDER BY register_date desc";
		return $this->fetchAll($sql, $bind);
	}

	public function getPublishingWorkbookTypeList($req=null) {
		$bind = array();
		$sql = " SELECT workbook_type_ids FROM app_info WHERE delete_date is NULL ";
		if (!empty($req['member_id'])) {
			$sql.= " AND id IN (SELECT info_id FROM app_member WHERE id = ?) ";
			$bind[] = $req['member_id'];
		} else {
			$sql.= " AND pschool_id is NULL  ";
		}
		$res = $this->fetch($sql, $bind);

		$bind = array();
		$sql = " SELECT t.id as book_type_id, t.title as book_type_name
				 FROM app_workbook_type as t
				 WHERE delete_date is NULL AND id in ({$res['workbook_type_ids']})
				 ORDER BY sequence_no ";
		return $this->fetchAll($sql, $bind);
	}
}
