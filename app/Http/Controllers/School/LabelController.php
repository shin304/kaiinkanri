<?php

namespace App\Http\Controllers\School;

use App\ConstantsModel;
use App\Lang;
use App\Model\MStudentTypeTable;
use App\Model\ParentTable;
use App\Model\StudentTable;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Excel;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Model\LoginAccountTable;
use App\Common\CSVExport;

class LabelController extends _BaseSchoolController
{
    use \App\Common\Email;

    protected static $LANG_URL = 'label';

    public function __construct() {
        parent::__construct ();

        $message_content = parent::getMessageLocale ();
        $this->lan = new Lang ( $message_content );
    }

    public function index(Request $request) {
        $template = DB::table('label_template')->where('id', $request->template_id)->first();
        if ($template) {
            $request->offsetSet('type', $template->label_type);
        }

        if ($request->has('type')) {

            $table_name = ($request->type == 1)? 'student' : 'parent';

//            Todo get list columns table
//            $column_titles = Schema::getColumnListing($table_name);

//            Todo exclude column unnecessary
//            $exlude_titles = $this->getExludeColumns($request->type);
//            $column_titles = array_diff($column_titles, $exlude_titles);

//          // get new column_titles base on constant model
            $column_titles = ConstantsModel::$LIST_LABEL[$table_name];

            // check if column in list exists in DB
            foreach($column_titles as $k => $column){
                if(!Schema::hasColumn($table_name, $column)){ //check whether users table has email column
                    unset($column_titles[$k]);
                }
            }
            // List default_column assign to view
            if ($request->type == 1) {

                if (!empty($template)) {

                    $default_column_titles = (!empty($template->columns))? explode(',', $template->columns) : array();
                } else {
                    $default_column_titles = ConstantsModel::$student_default_col;
                }
                $list = StudentTable::getInstance()->getListLabelAll(session ( 'school.login' )['id']);
            } else {

                if (!empty($template)) {

                    $default_column_titles =  (!empty($template->columns))? explode(',', $template->columns) : array();
                } else {
                    $default_column_titles = ConstantsModel::$parent_default_col;
                }
                $list = ParentTable::getInstance()->getListLabelAll(session ( 'school.login' )['id']);
            }

            // Search area
            $states = ConstantsModel::$states[$this->current_lang];
            $studentTypes = MStudentTypeTable::getInstance()->getStudentTypeList([session('school.login.id')], session('school.login.language'));

            return view('School.Label.index', compact('lan', 'list', 'column_titles', 'default_column_titles', 'states', 'template','studentTypes','table_name'));
        } else {
            return redirect()->to('/school/home');
        }
    }

    public function search(Request $request) {

        if ($request->has('type')) {
            $arr_search = array(
                'search_name' => request('search.name'),
                'search_state' => request('search.state'),
                'm_student_type' => request('student_type'),
            );
            $default_column_titles = $request->columns;
            $table_name = ($request->type == 1)? 'student' : 'parent';
            if ($request->type == 1) {

                $list = StudentTable::getInstance()->getListLabelAll(session ( 'school.login' )['id'], $arr_search);
            } else {

                $list = ParentTable::getInstance()->getListLabelAll(session ( 'school.login' )['id'], $arr_search);
            }

            $states = ConstantsModel::$states[$this->current_lang];
            $studentTypes = MStudentTypeTable::getInstance()->getStudentTypeList([session('school.login.id')], session('school.login.language'));
            return view('_parts.parent.student_search', compact('lan', 'list', 'default_column_titles', 'states', 'table_name','studentTypes'));
        }
    }

    /**
     * @param Request $request
     */
    public function exportCsv(Request $request) {
//        $request->columns Ex: [student_no, student_name, student_name_kana,....]
        if (count($request->columns) > 0 && count($request->member_ids) > 0) {

            $data ['dataFile'] = $this->getData($request);

            $header = null;
//            Todo check export header or not
            if ($request->has('export_header')) {
                $header = $this->getHeader($request);
                //$data = array_merge($header, $data);
            }
            $data ['header'] = $header;
//            Todo check export_encode [1:SJIS, 2:UTF-8]
            $file_name = ($request->type == 1)? '会員ラベル印刷リスト' : '請求先ラベル印刷リスト';

            // change $is_crypt and  $crypt_key, must get from request
            $is_crypt = null;
            if (session('school.login.is_zip_csv') == 1) {
                $is_crypt = true;
            }
            $crypt_key = ConstantsModel::CRYPT_KEY_NUM;

            $data ['char_code'] = $request->export_encode;

            // get info for send mail
            $member_type = strtoupper(session('hierarchy.role'));
            $to_email = null;
            $user_name = null;
            $data_send_mail = array ();
            if (array_search($member_type, ConstantsModel::$member_type) == 4) {
                $parentInfo = DB::table('parent')->where('id', session('school.login.origin_id'))->first();
                $to_email = $parentInfo->parent_mailaddress1;
                $user_name = $parentInfo->parent_name;
            } else {
                $studentInfo = DB::table('student')->where('id', session('school.login.origin_id'))->first();
                $to_email = $studentInfo['mailaddress'];//$studentInfo->mailaddress;
                $user_name = $studentInfo['student_name'];//$studentInfo->student_name;
            }

            $data ['data_send_mail'] = $data_send_mail;
            $data ['user_name'] = $user_name;
            $data ['to_email'] = $to_email;
            $data ['file_name'] = $file_name;

            CSVExport::exportZipCSV($data, $is_crypt, $crypt_key);
        }
    }

    /**
     * Return full list header (Parent, Student)
     * @param $request
     * @return array
     */
    private function getHeader($request) {
        $master_header = array(
            "pschool_id" => "団体ID",
            "student_no" => "会員番号",
            "student_name_kana" => "カナ名",
            "student_nickname" => "ニックネーム",
            "student_name" => "氏名",
            "login_id" => "ログインID",
            "login_pw" => "パスワード",
            "mailaddress" => "メールアドレス",
            "birthday" => "生年月日",
            "sex" => "性別",
            "school_name" => "学校名",
            "school_year" => "学年",
            "school_category" => "施設カテゴリ",
            "inquiry_date" => "検索日",
            "enter_date" => "入会日",
            "resign_date" => "退会日",
            "zip_code" => "郵便番号",
            "student_zip_code1" => "郵便番号１",
            "student_zip_code2" => "郵便番号２",
            "_pref_id" => "都道府県",
            "_city_id" => "市区町村",
            "address_detail" => "番地",
            "parent_id" => "請求先ID",
            "parent_mailaddress1" => "請求先メールアドレス１",
            "parent_mailaddress2" => "請求先メールアドレス２",
            "parent_name" => "請求先名",
            "active_flag" => "アクティブフラッグ",
            "subject_course_id" => "担当イベント",
            "report_card_id" => "通知表定",
            "memo1" => "メモ１",
            "memo2" => "メモ２",
            "memo3" => "メモ３",
//            "student_address" => "住所",
            "student_address" => "番地",
            "student_phone_no" => "自宅電話",
            "student_handset_no" => "携帯電話",
            "association_mail" => "協会メール",
            "card_img" => "証デザイン画像",
            "student_img" => "画像",
            "student_romaji" => "会員ローマ字",
            "enter_memo" => "入会メモ",
            "resign_memo" => "退会メモ",
            "student_category" => "会員区分",
            "total_member" => "人数",
            "m_student_type_id" => "会員種別",
            "login_account_id" => "管理者ID",
            "name_kana" => "カナ名",
            "pref_id" => "都道府県",
            "city_id" => "市区町村",
            "zip_code1" => "郵便番号１",
            "zip_code2" => "郵便番号２",
//            "address" => "住所",
            "address" => "番地",
            "phone_no" => "自宅電話",
            "invoice_type" => "支払方法",
            "mail_infomation" => "通知方法",
            "handset_no" => "携帯電話",
            "memo" => "メモ",
            "student_building" => "ビル",
            "building" => "ビル",
        );
//        $columns = array_flip($request->columns);
//        return array_intersect_key($master_header,$columns);
        $headers = $request->columns;
        foreach ($headers as &$header) {
            if (array_key_exists($header, $master_header)) {
                $header = $master_header[$header];
            }
        }
        return $headers;
    }

    private function getData($request) {
        $data = array();
        try {
            if ($request->type == 1) {

                $list = StudentTable::getInstance()->getListLabelAll(session ( 'school.login' )['id'], array('member_ids' =>$request->member_ids, 'columns'=>$request->columns));
            } else {

                $list = ParentTable::getInstance()->getListLabelAll(session ( 'school.login' )['id'], array('member_ids' =>$request->member_ids, 'columns'=>$request->columns));

            }

            // convert $export_list to array
            $data = array_map ( function ($object) {
                return ( array ) $object;
            }, $list->toArray() );

            // todo filter columns
            $columns = $request->columns;
            array_walk(
                $data,
                function(&$member) use ($columns) {
                    foreach ($member as $col=>$rec) {
                        if (!in_array($col, $columns)) {
                            unset($member[$col]);
                        }
                    }
                }
            );

        } catch (Exception $e) {
            // TODO log error message
        }
        return $data;
    }

    private function getExludeColumns($type)
    {
        if ($type == 1) {
            return ['id', 'pschool_id', 'student_type', 'student_name_kana', 'student_nickname', 'login_id', 'login_pw', 'mailaddress', 'birthday', 'sex', 'school_name', 'school_year', 'school_category', 'inquiry_date', 'enter_date', 'resign_date', 'student_zip_code1', 'student_zip_code2', 'parent_id', 'parent_mailaddress1', 'parent_mailaddress2', 'parent_name', 'active_flag', 'register_date', 'update_date', 'delete_date', 'register_admin', 'update_admin', 'subject_course_id', 'report_card_id', 'memo1', 'memo2', 'memo3', 'student_address', 'student_phone_no', 'student_handset_no', 'association_mail', 'card_img', 'student_img', 'student_romaji', 'enter_memo', 'resign_memo', 'student_category', 'total_member'];
        } else {
            return ['id', 'pschool_id', 'login_account_id','login_pw', 'name_kana', 'parent_mailaddress1', 'parent_mailaddress2', 'zip_code1', 'zip_code2', 'phone_no', 'invoice_type', 'mail_infomation', 'handset_no', 'memo', 'register_date', 'update_date', 'delete_date', 'register_admin', 'update_admin'];
        }
    }

    /**
     * Ajax call to load all exist template by type
     * @param Request $request
     */
    public function loadTemplate(Request $request)
    {
        if ($request->has('type')) {
            $template_list = DB::table('label_template')->where('label_type', $request->type)->whereNull('delete_date')->get();
            foreach ($template_list as &$template) {
                if (!empty($template->columns)) {
                    $request->offsetSet('columns', explode(',',$template->columns));
                    $template->columns_title = implode(', ', $this->getHeader($request));
                }

            }
            return json_encode($template_list);
        }
    }

    /**
     * Ajax call to store label template
     * @param Request $request
     * @return string
     */
    public function store(Request $request)
    {
        try {

            if ($request->has('name')) {
                $label = array(
                    'name'          => $request->name,
                    'label_type'    => $request->type,
                    'columns'       => ($request->columns && count($request->columns > 0))? implode(',', $request->columns): null,
                    'export_header' => $request->has('export_header'),
                    'encode'        => $request->export_encode,
                );

                DB::table('label_template')->insert($label);
            }

            return json_encode(true);
        } catch (Exception $e) {
            return json_encode(false);
        }


    }

    /**
     * Ajax call to delete label template
     * @param Request $request
     * @return string
     */
    public function destroy(Request $request)
    {
        try {

            if ($request->has('template_id')) {
                DB::table('label_template')->where('id', $request->template_id)->delete();
            }
            return json_encode(true);
        } catch (Exception $e) {
            return json_encode(false);
        }
    }

    /**
     * @return string
     */
    public static function generateRandomString($length) {

        $result = '';
        for ($i = 0; $i < $length; $i ++) {
            $result .= self::generateRandomChar();
        }
        // dd ($result);
        return $result;
    }

    /**
     * @return string
     */
    public static function generateRandomChar() {

        $r = mt_rand(0, 61);
        if ($r < 10) {
            $c = $r;
        } elseif ($r >= 10 && $r < 36) {
            $r -= 10;
            $c = chr($r + ord('a'));
        } else {
            $r -= 36;
            $c = chr($r + ord('A'));
        }
        return $c;
    }
}
