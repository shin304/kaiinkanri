<?php

namespace App\Http\Controllers\School;

use App\Common\Constants;
use App\Http\Controllers\School\Invoice\InvoiceController;
use App\Model\ClosingDayTable;
use App\Model\PaymentMethodPschoolTable;
use App\Model\StudentPersonInChargeTable;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Lang;
use App\Http\Controllers\School\StudentInfoController;
use App\Http\Controllers\School\_BaseSchoolController;
use App\Model\StudentTable;
use App\ConstantsModel;
use App\Model\MPrefTable;
use App\Model\MCityTable;
use App\Model\ParentTable;
use App\Model\LoginAccountTable;
use App\Model\HierarchyTable;
use App\Model\StudentGradeTable;
use App\Model\MStudentTypeTable;
use App\Model\PschoolTable;
use App\Model\StudentExamAreaTable;
use App\Model\InvoiceAdjustNameTable;
use App\Model\RoutinePaymentTable;
use App\Model\ParentBankAccountTable;
use App\Model\StudentGradeRelTable;
use App\Model\SchoolVisitHistoryTable;
use App\Model\ClassTable;
use App\Model\CourseTable;
use App\Model\ProgramTable;
use App\Model\InvoiceHeaderTable;
use App\Model\InvoiceItemTable;
use App\Model\ConsultationScheduleTableTable;
use App\Model\MSubjectHeadTable;
use App\Model\ExamScoreDetailTable;
use App\Model\ReportCardDetailTable;
use App\Model\HistoryLogTable;
use App\Model\AdditionalCategoryTable;
use App\Model\AdditionalCategoryRelTable;
use Excel;
use App\Model\StudentClassTable;
use App\Model\AxisLogStudentTable;
use App\Model\LoginAccountTempTable;
use App\Http\Controllers\Common\Validaters;
use Doctrine\DBAL\Schema\View;
use App\Http\Controllers\Common\AuthConfig;
use App\Model\StudentCourseRelTable;
use App\Model\EntryTable;
use App\Model\PaymentMethodTable;
use Illuminate\Validation\Rule;
use Mockery\CountValidator\Exception;
use Symfony\Component\CssSelector\Parser\Reader;
use Validator;
use App\Http\Controllers\Common\ValidateRequest;
use Carbon\Carbon;
use App\Http\Controllers\Common\FileUpload;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\DB;
use Crypt_Blowfish;
use ZipArchive;
use App\Common\CSVExport;

class StudentController extends _BaseSchoolController {

    use \App\Common\Email;
    private static $TOP_URL = 'student';
    protected static $ACTION_URL = 'student';
    protected static $LANG_URL = 'student';
    const SESSION_REGIST_OK = 'session.school.student.regist_ok';
    const SESSION_REQUESTS_KEY = 'session.school.student.requests';
    const SESSION_SEARCH_KEY = 'session.school.student.search.key';
    const SESSION_BREAD_LIST = 'session.class.bread_list';
    const LIMIT_ROW_IMPORT_IN_CSV_FILE = 100;
    private $lan;
    private $schoolCategory;
    private $bank_account_type_list;
    private $bread_name;
    private $template_url;
    private $_student_search_item = [
        'select_word',
        'select_state',
        'student_no',
        'from_register_date',
        'to_register_date',
        'from_update_date',
        'to_update_date',
        'student_type'
    ];
    private $_student_search_session_key = 'student_search_form';
    private $deadline_hours = ' 15:00:00';

    public function __construct(Request $request) {

        parent::__construct();
        $this->request = $request;

        // Setting multiple language
        $message_content = parent::getMessageLocale();
        $this->lan = new Lang($message_content);

        // Initialize data
        $this->schoolCategory = parent::getSchoolCategory();
        $this->bank_account_type_list = parent::getBankAccountList();
        $this->template_url = parent::getTemplateUrlStudent();

        if (! PschoolTable::getInstance()->isNormalShibu(session('school.login')['id'])) {
            return redirect($this->get_app_path());
        }
    }

    public function displayUpload() {

        $studentTypes = MStudentTypeTable::getInstance()->getStudentTypeList([session('school.login.id')], session('school.login.language'));
        return view('School.SStudent.upload', compact('studentTypes'));
    }

    public function exportCsv(Request $request) {

        $header = Constants::STUDENT_HEADER_CSV;
        $student_list = StudentTable::getInstance()->getStudentParentInfo(['pschool_id' => session('school.login.id')], true);
        if (! $student_list) {
            return $this->execute($request);
        }

        //Write log
        DB::table('history_log')->insert([
            'pschool_id' => session('school.login.id'),
            'type' => ConstantsModel::$HISTORY_LOG_STUDENT,
            'action' => __FUNCTION__,
            'message' => date('Y-m-d H:i:s') . ' ' . session('school.login.name') . $this->lan->get('who_export_csv'),
            'register_date' => date('Y-m-d H:i:s'),
            'register_admin' => session('school.login.login_account_id'),
        ]);

        $language = session('school.login.language');
        $result = [];
        foreach ($student_list as $key => $val) {

            if (($val['active_flag'] == 1 && $val['resign_date'] == null) || ($val['active_flag'] == 1 && $val['resign_date'] > date('Y-m-d H:i-s'))) {
                //                $student_state = "契約中";
                $student_state = 1;
            } else {
                //                $student_state = "契約終了";
                $student_state = 9;
            }

            $result[$key]['student_state'] = $student_state; //1 会員ステータス
            $result[$key]['student_no'] = $val['student_no']; // 2 会員番号
            $result[$key]['student_category'] = ConstantsModel::$STUDENT_CATEGORY[$language][$val['student_category']]; // 3 個人／法人
            $result[$key]['total_member'] = $val['total_member']; // 4 人数
            $result[$key]['student_type'] = $val['student_type_name']; //5 会員種別
            $result[$key]['student_name'] = $val['student_name']; // 6 会員名前
            $result[$key]['student_name_hiragana'] = $val['student_name_hiragana']; // 7 会員ひらがな
            $result[$key]['student_name_kana'] = $val['student_name_kana']; // 8 会員フリガナ
            $result[$key]['student_romaji'] = $val['student_romaji']; // 8 会員ニックネーム
            $result[$key]['mailaddress'] = $val['mailaddress']; // 10 会員メールアドレス
            $result[$key]['login_pw'] = null; // 11 会員パスワード
            $result[$key]['birthday'] = $val['birthday']; // 12 会員生年月日
            $result[$key]['sex'] = $val['sex'] ? ConstantsModel::$gender[$language][$val['sex']] : ""; // 13 会員性別
            $result[$key]['enter_date'] = $val['enter_date']; // 14 入会日
            $result[$key]['enter_memo'] = $val['enter_memo']; // 15 入会理由
            $result[$key]['resign_date'] = $val['resign_date']; // 16 退会日
            $result[$key]['resign_memo'] = $val['resign_memo']; // 17 退会理由
            $result[$key]['student_zip_code'] = empty($val['student_zip_code']) ? $val['student_zip_code1'] . $val['student_zip_code2'] : $val['student_zip_code']; // 18 会員郵便番号
            $result[$key]['student_pref'] = $val['student_pref_name']; // 19 会員都道府県名
            $result[$key]['student_city'] = $val['student_city_name']; // 20 会員市区町村名
            $result[$key]['student_address'] = $val['student_address']; // 21 会員番地
            $result[$key]['student_building'] = $val['student_building']; // 22 会員ビル
            $result[$key]['student_phone_no'] = $val['student_phone_no']; // 23 会員連絡先電話番号
            $result[$key]['student_handset_no'] = $val['handset_no']; // 24 会員携帯電話
            $result[$key]['student_name_other'] = $val['student_name_other']; // 25 会員送付先宛名

            $result[$key]['parent_name'] = $val['parent_name']; // 26 請求先名前
            $result[$key]['parent_name_hiragana'] = $val['parent_name_hiragana']; // 27 請求先名前ひらがな
            $result[$key]['name_kana'] = $val['name_kana']; // 28 請求先名前カナ
            $result[$key]['parent_mailaddress1'] = $val['parent_mailaddress1']; // 29 請求先メールアドレス１
            $result[$key]['login_pw'] = null; // 30 請求先パスワード
            $result[$key]['zip_code'] = empty($val['zip_code1']) ? $val['zip_code1'] . $val['zip_code2'] : $val['zip_code1']; // 31 請求先郵便番号
            $result[$key]['pref'] = $val['parent_pref_name']; // 32 請求先都道府県名
            $result[$key]['city'] = $val['parent_city_name']; // 33 請求先市区町村名
            $result[$key]['address'] = $val['address']; // 34 請求先番地
            $result[$key]['building'] = $val['building']; // 35 請求先ビル
            $result[$key]['phone_no'] = $val['phone_no']; // 36 請求先自宅電話番号
            $result[$key]['handset_no'] = $val['handset_no']; // 37 請求先携帯電話番号
            $result[$key]['memo'] = $val['memo']; // 38 請求先メモ
            $result[$key]['mail_infomation'] = isset($val['mail_infomation']) ? ConstantsModel::$mail_infomation[$language][$val['mail_infomation']] : ''; // 39 通知方法
            $result[$key]['invoice_type'] = ! empty($val['invoice_type']) ? Constants::$invoice_type[$language][$val['invoice_type']] : Constants::$invoice_type[$language][1]; // 40 支払方法
            $result[$key]['bank_type'] = isset($val['bank_type']) ? ConstantsModel::$bank_type[$language][$val['bank_type']] : ''; // 41 金融機関種別
            $result[$key]['bank_code'] = $val['bank_code']; // 42 金融機関コード
            $result[$key]['bank_name'] = $val['bank_name']; // 43 金融機関名
            $result[$key]['branch_code'] = $val['branch_code']; // 44 支店コード
            $result[$key]['branch_name'] = $val['branch_name']; // 45 支店名
            $result[$key]['bank_account_type'] = isset($val['bank_account_type']) ? ConstantsModel::$type_of_bank_account[$language][$val['bank_account_type']] : ''; // 46 口座種別
            $result[$key]['bank_account_number'] = $val['bank_account_number']; // 47 口座番号
            $result[$key]['bank_account_name'] = $val['bank_account_name']; // 48 口座名義
            $result[$key]['post_account_kigou'] = $val['post_account_kigou']; // 49 通帳記号
            $result[$key]['post_account_number'] = $val['post_account_number']; // 50 通帳番号
            $result[$key]['post_account_name'] = $val['post_account_name']; // 51 通帳名義
        }

//        $result = array_merge($header, $result);

        // Export
        $data ['dataFile'] = $result;
        $data ['header'] = $header;
        // create Random $key, and $code file name
        $code = ($this->generateRandomString(6));
        $currentTime = Carbon::now();
        $fileName = '会員情報_' . date('YmdHis', strtotime($currentTime));

        // change $is_crypt and  $crypt_key, must get from request
        $is_crypt = null;
        if (session('school.login.is_zip_csv') == 1) {
            $is_crypt = true;
        }
        $crypt_key = ConstantsModel::CRYPT_KEY_NUM;
        $data ['char_code'] = $request->mode;
        // get info for send mail
        $studentInfo = DB::table ( 'student' )->where('id', session ( 'school.login.origin_id'))->first();
        $to_email = isset($studentInfo) ? $studentInfo->mailaddress : null;
        $user_name = isset($studentInfo) ? $studentInfo->student_name : null;
        $data_send_mail = array ();

        $data ['data_send_mail'] = $data_send_mail;
        $data ['user_name'] = $user_name;
        $data ['to_email'] = $to_email;
        $data ['file_name'] = $fileName;

        CSVExport::exportZipCSV($data, $is_crypt, $crypt_key);
    }

    public function downloadTemplate(Request $request) {

        if ($request->mode == 1) {
            //UTF8
            $file = "files/student_utf_8.csv";
        } else {
            //Shift JIS
            $file = "files/student_shift_jis.csv";
        }
        return response()->download($file, '会員情報.csv');
    }

    public function importCsv(Request $request) {
        
        //Need to keep this var, because request->replace to clear it
        $mode = $request->mode;
        $array_search = array ('pschool_id' => session('school.login.id'));
        $student_list = StudentTable::getInstance()->getQueryList($array_search);
        $parent_list=ParentTable::getInstance()->getParentList2($search=null);
        if ($request->hasFile('import_file')) {
            // get path temp
            $path = $request->file('import_file')->getRealPath();
            $limit_row_check = (count(file($path)));
            if ($limit_row_check <= self::LIMIT_ROW_IMPORT_IN_CSV_FILE) {
                // get extension file
                $extensionFile = $request->file('import_file')->getClientOriginalExtension();
                // extensions system support
                $extensions = array ("csv", "xls", "xlsx");
                // check extension file have in extensions
                if (in_array($extensionFile, $extensions) === true) {
                    $studentTypes = MStudentTypeTable::getInstance()->getStudentTypeList([session('school.login.id')], session('school.login.language'));
                    $studentTypeKeyMaps = [];
                    foreach ($studentTypes as $type) {
                        $studentTypeKeyMaps[$type['name']] = $type['id'];
                    }

                    $fields = [
                        'student_state',
                        'student_no',
                        'student_category',
                        'total_member',
                        'student_type',
                        'student_name',
                        'student_name_hiragana',
                        'student_name_kana',
                        'student_romaji',
                        'mailaddress',
                        'student_pass',
                        'birthday',
                        'sex',
                        'enter_date',
                        'enter_memo',
                        'resign_date',
                        'resign_memo',
                        'student_zip_code',
                        'student_pref',
                        'student_city',
                        'student_address',
                        'student_building',
                        'student_phone_no',
                        'student_handset_no',
                        'student_name_other',
                        'parent_name',
                        'parent_name_hiragana',
                        'name_kana',
                        'parent_mailaddress1',
                        'parent_pass',
                        'parent_zip_code',
                        'pref',
                        'city',
                        'address',
                        'building',
                        'phone_no',
                        'handset_no',
                        'memo',
                        'mail_infomation',
                        'invoice_type',
                        'bank_type',
                        'bank_code',
                        'bank_name',
                        'branch_code',
                        'branch_name',
                        'bank_account_type',
                        'bank_account_number',
                        'bank_account_name',
                        'post_account_kigou',
                        'post_account_number',
                        'post_account_name',
                    ];
                    $mode = $request->mode;
                    $errors = [];
                    $row = 0;
                    $insertedRow = 0;
                    $isReachLimitStudent = false;
                    $language = session('school.login.language');
                    if (($handle = fopen($path, 'r')) !== false) {
                        while (($data = fgetcsv($handle)) !== false) {
                            if ($row == 0) {
                                $row ++;
                                continue;
                            }
                            // Check if school reach limit number of plan
                            if (! $isReachLimitStudent) {

                                $limit_check = PschoolTable::getInstance()->reachLimitAccessOfPlan();
                                if ($limit_check['result'] == 'ERROR') {

                                    $isReachLimitStudent = true;

                                }
                            }

                            if (! $isReachLimitStudent) { // Have not reach limit yet -> insert as usual

                                $data = array_pad($data, 48, '');
                                $data = array_combine($fields, $data);

                                //Shift JIS. Need to convert back to UTF-8

                                if ($mode == 0) {
                                    array_walk_recursive($data, function(&$val) {

                                        $val = mb_convert_encoding($val, 'UTF-8', 'sjis-win');
                                    });
                                }

                                $data['m_student_type_id'] = array_get($studentTypeKeyMaps, $data['student_type']);
                                $data['sex'] = array_get(array_flip(ConstantsModel::$gender[$language]), $data['sex']);
                                $data['mail_infomation'] = array_get(array_flip(ConstantsModel::$mail_infomation[$language]), $data['mail_infomation']);
                                $data['invoice_type'] = array_get(array_flip(Constants::$invoice_type[$language]), $data['invoice_type']);
                                $data['bank_type'] = array_get(array_flip(ConstantsModel::$bank_type[$language]), $data['bank_type']);
                                $data['bank_account_type'] = array_get(array_flip(ConstantsModel::$type_of_bank_account[$language]), $data['bank_account_type']);
                                $data['zip_code1'] = substr($data['parent_zip_code'], 0, 3);
                                $data['zip_code2'] = substr($data['parent_zip_code'], 3, 7);
                                $data['student_zip_code1'] = substr($data['student_zip_code'], 0, 3);
                                $data['student_zip_code2'] = substr($data['student_zip_code'], 3, 7);
                                $category = array_flip(ConstantsModel::$STUDENT_CATEGORY[$language]);
                                $data['student_category'] = array_get($category, $data['student_category']);
                                if ($data['student_pref'] != '') {
                                    $pref = MPrefTable::getInstance()->getRow(['name' => $data['student_pref']]);
                                    if ($pref) {
                                        $data['_pref_id'] = $pref['id'];
                                    }
                                    $city = MCityTable::getInstance()->getRow(['name' => $data['student_city']]);
                                    if ($city) {
                                        $data['_city_id'] = $city['id'];
                                    }
                                }

                                if ($data['pref'] != '') {
                                    $pref = MPrefTable::getInstance()->getRow(['name' => $data['pref']]);
                                    if ($pref) {
                                        $data['pref_id'] = $pref['id'];
                                    }
                                    $city = MCityTable::getInstance()->getRow(['name' => $data['city']]);
                                    if ($city) {
                                        $data['city_id'] = $city['id'];
                                    }
                                }

                                if ($data['invoice_type'] != '') {
                                    $data['have_payment_info'] = 1;
                                }
                                if ($data['address'] != '') {
                                    $data['have_parent_address_info'] = 1;
                                }
                                $request->replace($data);
                                $validator = $this->doValidate($request, 1);
                                $exists_mail_parent=false;
                                $exists_student_no=false;
                                foreach ($parent_list as $parent) {
                                    if(($data['parent_mailaddress1'] == $parent['parent_mailaddress1'])&&($data['parent_name'] == $parent['parent_name'])){
                                        $exists_mail_parent=true;
                                        $data['get_parent_id']=$parent['id'];
                                    }
                                }
                                foreach ($student_list as $student) {
                                    if ($data['student_no'] == $student['student_no']) {
                                        $exists_student_no = true;
                                    }
                                }
                                $request->replace($data);
                                if($exists_mail_parent==false) {
                                    $validator->sometimes('parent_pass', 'nullable|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/', function($input) {
                                        if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                                            return false;
                                        }
                                        return true;
                                    });
                                }
                                if($exists_student_no==false) {
                                    $validator->sometimes('student_no', 'required|max:128', function($input) {
                                        if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                                            return false;
                                        }
                                        return true;
                                    });
                                } elseif ($exists_student_no==true) {
                                    $validator->sometimes('student_no', 'required|max:128|unique:student', function($input) {
                                        if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                                            return false;
                                        }
                                        return true;
                                    });
                                }
                                //
                                if ($validator->errors()->all()) {
                                    $errors[$row] = $validator->errors();
                                    break;
                                } else {
                                    StudentTable::getInstance()->createStudentWithParent($request);
                                    $insertedRow ++;
                                }
                                $row ++;

                            } else { //Reach limit of student -> count rows and do nothing

                                $row ++;
                            }
                        }
                        fclose($handle);
                    }
                    $message = [
                        'total_row' => $row,
                        'total_inserted' => $insertedRow,
                        'total_error' => count($errors)
                    ];
                    if (! empty($limit_check) && $limit_check['result'] == "ERROR") {

                        $validator = Validator::make(array (), array (), array ());
                        $message['limit'] = $limit_check['message'];
                        $validator->errors()->add($limit_check['message'], $limit_check['message']);
                        $errors['limit'] = $validator->errors();

                    }
                    if ($errors) {
                        $message['errors'] = $errors;
                    }

                    $request->offsetSet('import_message', $message);

                } else {

                    $request->offsetSet('import_message', ['other' => 'ちょうどファイルcsvをサポートしたり']);

                }
            } else {
                $request->offsetSet('import_message', ['limit' => '取込データ件数が多すぎます。一度に取り込めるデータ件数は'.self::LIMIT_ROW_IMPORT_IN_CSV_FILE.'件までです。']);
            }

        } else {

            $request->offsetSet('import_message', ['other' => 'ファイルを確認してください。']);

        }
        $request->offsetSet('mode', $mode);
        return $this->displayUpload();
    }

    public function execute(Request $request) {

        // 2017-07-13　退会日を過ぎたら自動的に契約終了にする
        StudentTable::getInstance()->updateResignStatus();
        $message = $request->message_type;

        $this->_initSearchDataFromSession($this->_student_search_item, $this->_student_search_session_key);

        //Initial search conditions
        $array_search = array ('pschool_id' => session('school.login.id'));
        if (! $request->offsetExists('select_state')) {
            $request->offsetSet('select_state', ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT);
        }

        //Filter condition
        //名前(フリガナ)
        if ($request->offsetExists('select_word')) {
            $array_search['select_word'] = $request->select_word;
            $array_search ['session'] = session('school.login');
        }
        // ステータス
        if ($request->offsetExists('select_state')) {
            $array_search['select_state'] = $request->select_state;
        }
        //会員番号
        if ($request->offsetExists('student_no')) {
            $array_search['input_search_student_no'] = $request->student_no;
        }

        //会員種別
        if ($request->offsetExists('student_type')) {
            $array_search['input_search_student_type'] = $request->student_type;
        }
        //登録日
        if ($request->offsetExists('from_register_date')) {
            $array_search['from_register_date'] = $request->from_register_date;
        }
        if ($request->offsetExists('to_register_date')) {
            $array_search['to_register_date'] = $request->to_register_date;
        }
        //変更日
        if ($request->offsetExists('from_update_date')) {
            $array_search['from_update_date'] = $request->from_update_date;
        }
        if ($request->offsetExists('to_update_date')) {
            $array_search['to_update_date'] = $request->to_update_date;
        }
        //登録変更日
        if ($request->offsetExists('valid_date_from')) {
            $array_search['valid_date_from'] = $request->valid_date_from;
        }
        if ($request->offsetExists('valid_date_to')) {
            $array_search['valid_date_to'] = $request->valid_date_to;
        }
        //Get list student
        $student_list = StudentTable::getInstance()->getQueryList($array_search);

        //Process for prev - next in detail page
        $prevNextStudentIds = [];
        foreach ($student_list as $student) {
            $prevNextStudentIds[] = $student['id'];
        }
        session()->put('prev_next_student_ids', $prevNextStudentIds);

        //Get summary data
        $summary = $this->_getStudentSummary();

        //Get Export CSV log
        $historyLogs = HistoryLogTable::getInstance()->getList([
            'pschool_id' => session('school.login.id'),
            'type' => ConstantsModel::$HISTORY_LOG_STUDENT
        ]);

        //会員種別
        $studentTypes = MStudentTypeTable::getInstance()->getStudentTypeList([session('school.login.id')], session('school.login.language'));

        return view('School.SStudent.index', compact('student_list', 'request', 'message', 'lan', 'summary', 'historyLogs', 'studentTypes'));
    }

    public function executeEntry(Request $request) {

        // Default birthday
        $yearBirth = date("Y") - ConstantsModel::$MEMBER_DEFAULT_AGE;
        $monthBirth = date("m");
        $dayBirth = date("d");
        $student = array ();
        if ($request->offsetExists('id') && ! $request->has('errors')) {
            //Get student, parent, bank info
            $student = StudentTable::getInstance()->getStudentParentInfo(['student_id' => $request->id]);
            if (! $student) {
                return redirect('/school/student');
            }

            // 2017-07-13　退会日を過ぎたら自動的に契約終了にする
            if ($student['active_flag'] && $student['resign_date'] != null && $student['resign_date'] <= date('Y-m-d')) {
                $student['active_flag'] = 0;
                $student['update_date'] = date('Y-m-d');
                StudentTable::getInstance()->save($student);
            }

            if ($student['birthday'] && Carbon::createFromFormat('Y-m-d', $student['birthday']) !== false) {
                $birthday = explode('-', $student['birthday']);
                $yearBirth = $birthday[0];
                $monthBirth = $birthday[1];
                $dayBirth = $birthday[2];
            }
            //Get routine payment parent
            $routinePayments = RoutinePaymentTable::getInstance()->getRoutinePayemntList(session('school.login.id'), ConstantsModel::$DISCOUNT_PARENT, $student['parent_id'], [
                'month',
                'invoice_adjust_name_id',
                'adjust_fee'
            ]);
            //Get routine payment student
            $routinePaymentsStudent = RoutinePaymentTable::getInstance()->getRoutinePayemntList(session('school.login.id'), ConstantsModel::$DISCOUNT_STUDENT, $student['id'], [
                'RP.id as payment_id',
                'month',
                'invoice_adjust_name_id',
                'adjust_fee'
            ]);

            foreach ($routinePayments as &$value) {
                $value['adjust_fee'] = number_format(floor($value['adjust_fee']));
            }

            foreach ($routinePaymentsStudent as &$value) {
                $value['adjust_fee'] = number_format(floor($value['adjust_fee']));
            }
            //Check extra data have been set or not to show default html
            $student['have_student_join_info'] = 1;
            $student['have_student_address_info'] = 1;
            $student['have_parent_address_info'] = 1;
            $student['have_payment_info'] = 1;
            $student['have_payment_adjust'] = 1;
            //Status
            if (($student['active_flag'] == 1 && $student['resign_date'] == null) || ($student['active_flag'] == 1 && $student['resign_date'] > date('Y-m-d'))) {
                $student['student_state'] = ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT;
            } else {
                $student['student_state'] = ConstantsModel::$MEMBER_STATUS_END_CONTRACT;
            }

            //Check if this student have debit invoice
            $student['is_debit_invoice'] = InvoiceItemTable::getInstance()->checkIsDebitInvoice($request->id);

            //Get person_in_charge info
            $person_in_charge_list = StudentPersonInChargeTable::getInstance()->getActiveList(array ('student_id' => $request->id));

            $request->merge($student);
            $request->merge(['payment' => $routinePayments]);
            $request->merge(['payment_student' => $routinePaymentsStudent]);
            $request->merge(['person_in_charge' => $person_in_charge_list]);
        }
        //Set birthday if not exist
        if (! $request->has('birthday')) {
            $birth = $yearBirth . '-' . $monthBirth . '-' . $dayBirth;
            $request->offsetSet('birthday', $birth);
        }
        //Get address information
        $cityListForParent = array ();
        $cityListForStudent = array ();
        $prefList = MPrefTable::getInstance()->getList();
        $prefList = array_pluck($prefList, 'name', 'id');
        if ($request->offsetExists('pref_id')) {
            $cityList = MCityTable::getInstance()->getListByPref($request->pref_id);
            $cityListForParent = array_pluck($cityList, 'name', 'id');
        }
        if ($request->offsetExists('_pref_id')) {
            $cityList = MCityTable::getInstance()->getListByPref($request->_pref_id);
            $cityListForStudent = array_pluck($cityList, 'name', 'id');
        }
        // 送付先住所-市区町村
        $cityOtherList = array ();
        if ($request->has('pref_id_other')) {
            $cityList = MCityTable::getInstance()->getListByPref($request->pref_id_other);
            $cityOtherList = array_pluck($cityList, 'name', 'id');
        }

        //生年月日
        $birthYearList = array ();
        $birthMonthList = array ();
        $birthDayList = array ();
        $to = date('Y');
        $from = $to - ConstantsModel::$MEMBER_BIRTH_DAY_FROM_YEAR_RANGE;
        for ($i = $from; $i <= $to; $i ++) {
            $birthYearList[$i] = sprintf('%02d', $i);
        }
        for ($i = 1; $i <= 12; $i ++) {
            $birthMonthList[$i] = sprintf('%02d', $i);
        }
        for ($i = 1; $i <= 31; $i ++) {
            $birthDayList[$i] = sprintf('%02d', $i);
        }

        //Initialize data for routine payment
        $month_list = ConstantsModel::$month_listEx[session('school.login.language')];
        $parent_ids = HierarchyTable::getInstance()->getParentPschoolIds(session('school.login') ['id']);
        $parent_ids [] = session('school.login')['id'];
        $invoice_adjust_list = InvoiceAdjustNameTable::getInstance()->getInvoiceAdjustList($parent_ids);

        //Additional category
        $additionalCategories = AdditionalCategoryTable::getInstance()->getListData([
            'type' => ConstantsModel::$ADDITIONAL_CATEGORY_STUDENT,
            'related_id' => $request->id
        ], true);

        $lan = $this->lan;
        $schoolCategory = $this->schoolCategory;
        $bank_account_type_list = $this->bank_account_type_list;
        $success = $request->success;
        // 支払方法
        $payment_list = PaymentMethodTable::getInstance()->getPaymentMethodPschool(session('school.login')['id']);

        //会員種別
        if (! empty($student)) {
            $studentTypes = MStudentTypeTable::getInstance()->getStudentTypeList([session('school.login.id')], session('school.login.language'), $student['student_category']);
        } else {
            $studentTypes = MStudentTypeTable::getInstance()->getStudentTypeList([session('school.login.id')], session('school.login.language'), $request->offsetExists('student_category') ? $request->student_category : ConstantsModel::$MEMBER_CATEGORY_PERSONAL);
        }


        return view('School.SStudent.entry', compact('success', 'parent_id_in', 'parent', 'cityListForParent', 'cityListForStudent', 'student', 'studentExam_list', 'defaultStudentType', 'birthYearList', 'topic_list', 'birthMonthList', 'birthDayList', 'prefList', 'cityList', 'cityList', 'month_list', 'is_auto_student_no', 'studentTypeList', 'invoice_adjust_list', 'bank_account_type_list', 'schoolCategory', 'grades', 'studentTypes', 'request', 'lan', 'additionalCategories', 'cityOtherList', 'payment_list'));
    }

    public function newComplete(Request $request) {

        if ($request->has('payment_student')) {
            $request_clone = $request->all();
            foreach ($request_clone['payment_student'] as &$payment_student) {
                $payment_student['adjust_fee'] = str_replace(',', '', $payment_student['adjust_fee']);
            }
            $request->replace($request_clone);
        }
        if ($request->has('payment')) {
            $request_clone = $request->all();
            foreach ($request_clone['payment'] as &$payment) {
                $payment['adjust_fee'] = str_replace(',', '', $payment['adjust_fee']);
            }
            $request->replace($request_clone);
        }

        // Check if school reach limit number of plan
        if ($request->has('id')) { // Edit old student so check limit only when change unactive -> active

            if (! empty($request->student_state) && $request->student_state == 1) {

                $limit_check = PschoolTable::getInstance()->reachLimitAccessOfPlan();

                if ($limit_check['result'] == 'ERROR') {

                    $validator = Validator::make(array (), array (), array ());

                    $validator->errors()->add($limit_check['message'], $limit_check['message']);

                    $request->offsetSet('errors', $validator->errors());

                    return $this->executeEntry($request);
                }

            }

        } else { // register new student so always check limit
            $limit_check = PschoolTable::getInstance()->reachLimitAccessOfPlan();

            if ($limit_check['result'] == 'ERROR') {

                $validator = Validator::make(array (), array (), array ());

                $validator->errors()->add($limit_check['message'], $limit_check['message']);

                $request->offsetSet('errors', $validator->errors());

                return $this->executeEntry($request);
            }

        }
        // end check limit

        $validator = $this->doValidate($request);
        if ($validator->errors()->all()) {
            $request->offsetSet('errors', $validator->errors());
            return $this->executeEntry($request);
        }
        //Database process
        $status = StudentTable::getInstance()->createStudentWithParent($request);
        //        if ($status) {
        //            session()->put('status', $this->lan->get('update_success'));
        //        }
        if ($request->has('id')) {
            return $this->newDetail($request);
        } else {
            $request->offsetSet('id', $status);
            return $this->newDetail($request);
        }

        return redirect('/school/student/entry');
    }

    public function doValidate(Request $request, $is_importCSV = null) {
        $message = $this->_getValidationMessages();
        $rules = [];

        if (! $request->offsetExists('id') || $request->id == null) {
            if ($is_importCSV) {
                $rules['student_pass'] = 'nullable|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/';
            } else {
                if ($request->offsetExists('parent_id') || $request->parent_name != $request->student_name || $request->mailaddress != $request->parent_mailaddress1) {
                    $rules['student_pass'] = 'required|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/';
                }
            }
        } else {
            if ($request->offsetExists('student_pass') && $request->student_pass != null) {
                $rules['student_pass'] = 'min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/';
            }
        }
        $message['student_pass.required'] = 'member_password_required';
        $message['student_pass.min'] = 'err_member_pass_required_min';
        $message['student_pass.max'] = 'err_member_pass_required_max';
        $message['student_pass.regex'] = 'err_member_pass_regex';
        $message['parent_pass.min'] = 'err_parent_pass_required_min';
        $message['parent_pass.regex'] = 'err_parent_pass_regex';
        $message['mailaddress.email'] = 'err_email_type';
        //Not check validation payment student and payment discount
        /*if ($request->has('payment_student')) {
            $rules['payment_student.*.month'] = 'required';
            $rules['payment_student.*.invoice_adjust_name_id'] = 'required';
            $rules['payment_student.*.adjust_fee'] = 'required|numeric';
            foreach ($request->payment_student as $key => $value) {
                $message['payment_student.' . $key . '.month.required'] = "require_target_month," . ($key + 1);
                $message['payment_student.' . $key . '.invoice_adjust_name_id.required'] = "require_abstract," . ($key + 1);
                $message['payment_student.' . $key . '.adjust_fee.required'] = "require_amount_of_money," . ($key + 1);
                $message['payment_student.' . $key . '.adjust_fee.numeric'] = "amount_money_numeric," . ($key + 1);
            }
        }
        if ($request->has('payment')) {
            $rules['payment.*.month'] = 'required';
            $rules['payment.*.invoice_adjust_name_id'] = 'required';
            $rules['payment.*.adjust_fee'] = 'required|numeric';
            foreach ($request->payment as $key => $value) {
                $message['payment.' . $key . '.month.required'] = "parent_require_target_month," . ($key + 1);
                $message['payment.' . $key . '.invoice_adjust_name_id.required'] = "parent_require_abstract," . ($key + 1);
                $message['payment.' . $key . '.adjust_fee.required'] = "parent_require_amount_of_money," . ($key + 1);
                $message['payment.' . $key . '.adjust_fee.numeric'] = "parent_amount_money_numeric," . ($key + 1);
            }
        }*/
        //      add rule of 担当者
        if ($request->has('person_in_charge')) {
            $rules['person_in_charge.*.person_name'] = 'nullable|max:255';
            $rules['person_in_charge.*.person_name_kana'] = 'nullable|max:255|regex:/^[ア-ン゛゜ァ-ォャ-ョー「」ヴ、\　\ ]+$/u';
            $rules['person_in_charge.*.person_position'] = 'nullable|max:64';
            $rules['person_in_charge.*.person_office_name'] = 'nullable|max:255';
            $rules['person_in_charge.*.person_office_tel'] = 'nullable|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/';
            $rules['person_in_charge.*.person_email'] = null;
            foreach ($request->person_in_charge as $key => $person) {
                if (isset($person['check_send_mail_flag']) && ($person['check_send_mail_flag'] == 1)) {
                    $rules['person_in_charge.*.person_email'] = 'required|email';
                } else {
                    $rules['person_in_charge.*.person_email'] = 'nullable|email';
                }
            }

            foreach ($request->person_in_charge as $key => $person) {
                $message['person_in_charge.' . $key . '.person_name.max'] = "person_name_max," . $key;
                $message['person_in_charge.' . $key . '.person_name_kana.max'] = "person_name_kana_max," . $key;
                $message['person_in_charge.' . $key . '.person_name_kana.regex'] = "person_name_kana_regex," . $key;
                $message['person_in_charge.' . $key . '.person_position.max'] = "person_position_max," . $key;
                $message['person_in_charge.' . $key . '.person_office_name.max'] = "person_office_name_max," . $key;
                $message['person_in_charge.' . $key . '.person_office_tel.regex'] = "person_office_tel_regex," . $key;
                $message['person_in_charge.' . $key . '.person_email.email'] = "person_email_format," . $key;
                $message['person_in_charge.' . $key . '.person_email.required'] = "person_email_required," . $key;
            }
        }
        //  ENDING ---  add rule of 担当者
        $validator = Validator::make($request->all(), $rules, $message);

        if (!$is_importCSV) {
            $validator->sometimes('student_no', 'required|max:128|unique:student,student_no,' . $request->id . ',id,pschool_id,' . session('school.login.id'), function($input) {
                if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                    return false;
                }
                return true;
            });
        }

        $validator->sometimes('m_student_type_id', 'required', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_name', 'required|max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_name_kana', 'nullable|max:255|regex:/^[ア-ン゛゜ァ-ォャ-ョー「」ヴ、\　\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_romaji', 'max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_img_file', 'nullable|image', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('mailaddress', 'required|email|max:64', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });

        if (! empty($request->parent_id)) {
            $validator->sometimes('mailaddress', 'unique:parent,parent_mailaddress1,' . $request->parent_id . ',id,delete_date,NULL,pschool_id,' . session('school.login.id'), function($input) {

                if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                    return false;
                }
                return true;
            });
        }

        $validator->sometimes('enter_date', 'nullable|date', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('resign_date', 'nullable|date', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_zip_code1', 'nullable|numeric|digits:3', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_zip_code2', 'nullable|numeric|digits:4', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_address', 'max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_building', 'max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('student_handset_no', 'nullable|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('parent_name', 'required|max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return ! $input->parent_id;
        });
        $validator->sometimes('name_kana', 'nullable|max:255|regex:/^[ア-ン゛゜ァ-ォャ-ョー「」ヴ、\　\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('parent_mailaddress1', 'required|email|max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return ! $input->parent_id;
        });
        $validator->sometimes('parent_mailaddress2', 'nullable|email|max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('zip_code1', 'nullable|numeric|digits:3', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('zip_code2', 'nullable|numeric|digits:4', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('phone_no', 'nullable|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('handset_no', 'nullable|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        if ($is_importCSV) {
            $validator->sometimes('birthday', 'nullable|date', function($input) {

                if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                    return false;
                }
                return $input->student_category == ConstantsModel::$MEMBER_CATEGORY_PERSONAL;
            });
        } else {
            $validator->sometimes('birthday', 'required|date', function($input) {

                if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                    return false;
                }
                return $input->student_category == ConstantsModel::$MEMBER_CATEGORY_PERSONAL;
            });
        }
        $validator->sometimes('student_phone_no', 'required|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_student_address_info == 1;
        });

        $validator->sometimes('_city_id', 'required', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_student_address_info == 1 && $input->_pref_id;
        });
        $validator->sometimes('total_member', 'required|numeric|min:1', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->student_category == ConstantsModel::$MEMBER_CATEGORY_CORP;
        });
        $validator->sometimes('address', 'max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_parent_address_info == 1 && ! $input->parent_id;
        });
        $validator->sometimes('building', 'max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_parent_address_info == 1 && ! $input->parent_id;
        });
        $validator->sometimes('city_id', 'required', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_parent_address_info == 1 && ! $input->parent_id && $input->pref_id;
        });
        $validator->sometimes('bank_code', 'required|numeric|digits_between:1,4', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type != ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes([
            'bank_name',
            'branch_name'
        ], 'required|max:15|regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type != ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('branch_code', 'required|numeric|digits_between:1,3', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type != ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('bank_account_type', 'required', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type != ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('bank_account_number', 'required|numeric|digits_between:1,7|regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type != ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('bank_account_name', 'required|max:30|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}「」、\　\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type != ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('bank_account_name_kana', 'max:255|regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type != ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('post_account_kigou', 'required|numeric|digits_between:1,5', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type == ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('post_account_number', 'required|numeric|digits_between:1,8', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type == ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        $validator->sometimes('post_account_name', 'required|max:30|regex:/^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return $input->have_payment_info == 1 && ! $input->parent_id && $input->invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH'] && $input->bank_type == ConstantsModel::$FINANCIAL_TYPE_POST;
        });
        //        $validator->sometimes(['payment.*.month', 'payment.*.invoice_adjust_name_id'], 'required', function ($input) {
        //            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
        //                return false;
        //            }
        //            return $input->have_payment_adjust == 1;
        //        });
        //        $validator->sometimes('payment.*.adjust_fee', 'required|numeric', function ($input) {
        //            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
        //                return false;
        //            }
        //            return $input->have_payment_adjust == 1;
        //        });

        //        validate rule of 代表者
        $validator->sometimes('representative_name', 'nullable|max:255', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('representative_name_kana', 'nullable|max:255|regex:/^[ア-ン゛゜ァ-ォャ-ョー「」ヴ、\　\ ]+$/u', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('representative_position', 'nullable|max:64', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('representative_email', 'nullable|email', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        $validator->sometimes('representative_tel', 'nullable|regex:/^\d{2,4}-?\d{2,4}-?\d{4}$/', function($input) {

            if ($input->student_state == ConstantsModel::$MEMBER_STATUS_END_CONTRACT) {
                return false;
            }
            return true;
        });
        //  ENDING ---  validate rule of 代表者
        return $validator;

    }

    protected function _getValidationMessages() {

        $message = array (
            'student_img_file.image' => 'err_student_img_image',
            'm_student_type_id.required' => 'err_student_type_required',
            'total_member.required' => 'err_total_member_required',
            'total_member.numeric' => 'err_total_member_numeric',
            'total_member.min' => 'err_total_member_min',
            'student_name.required' => 'err_student_name_required',
            'student_name.max' => 'err_student_name_max',
            'student_name_kana.max' => 'err_student_name_kana_max',
            'student_name_kana.regex' => 'err_student_name_kana_regex',
            'student_romaji.max' => 'err_student_name_romaji_max',
            'mailaddress.required' => 'err_student_mailaddress_required',
            'mailaddress.email' => 'err_student_mailaddress_format',
            'mailaddress.unique' => 'err_student_mailaddress_unique',
            'mailaddress.max' => 'err_student_mailaddress_max',
            'birthday.required' => 'err_student_birthday_required',
            'birthday.date' => 'err_student_birthday_format',
            'enter_date.date' => 'err_student_enter_date_format',
            'resign_date.date' => 'err_student_resign_date_format',
            'student_zip_code1.numeric' => 'err_student_zip_code_numeric',
            'student_zip_code1.digits' => 'err_student_zip_code_digits',
            'student_zip_code2.numeric' => 'err_student_zip_code_numeric',
            'student_zip_code2.digits' => 'err_student_zip_code_digits',
            'student_address.max' => 'err_student_address_max',
            'student_building.max' => 'err_student_building_max',
            'student_phone_no.required' => 'err_student_phone_no_required',
            'student_phone_no.regex' => 'err_student_phone_no_regex',
            'student_handset_no.numeric' => 'err_student_handset_no_numeric',
            'student_handset_no.regex' => 'err_student_handset_no_regex',
            '_city_id.required' => 'err_student_city_id_required',
            'parent_name.required' => 'err_parent_name_required',
            'parent_name.max' => 'err_parent_name_max',
            'name_kana.max' => 'err_name_kana_max',
            'name_kana.regex' => 'err_parent_name_kana_regex',
            'parent_mailaddress1.required' => 'err_parent_mailaddress1_required',
            'parent_mailaddress1.email' => 'err_parent_mailaddress1_email',
            'parent_mailaddress1.unique' => 'err_parent_mailaddress1_unique',
            'parent_mailaddress1.max' => 'err_parent_mailaddress1_max',
            'parent_mailaddress2.email' => 'err_parent_mailaddress2_email',
            'parent_mailaddress2.max' => 'err_parent_mailaddress2_max',
            'parent_pass.required' => 'err_parent_pass_required',
            'parent_pass.max' => 'err_parent_pass_required_max',
            'zip_code1.numeric' => 'err_parent_zip_code_numeric',
            'zip_code1.digits' => 'err_parent_zip_code_digits',
            'zip_code2.numeric' => 'err_parent_zip_code_numeric',
            'zip_code2.digits' => 'err_parent_zip_code_digits',
            'city_id.required' => 'err_parent_city_id_required',
            'address.required' => 'err_parent_address_required',
            'address.max' => 'err_parent_address_max',
            'building.max' => 'err_parent_building_max',
            'phone_no.numeric' => 'err_parent_phone_no_numeric',
            'phone_no.regex' => 'err_phone_no_regex',
            'handset_no.numeric' => 'err_parent_handset_no_numeric',
            'handset_no.regex' => 'err_handset_no_regex',
            'bank_code.required' => 'err_bank_code_required',
            'bank_code.digits_between' => 'err_bank_code_digits_between',
            'bank_code.numeric' => 'err_bank_code_numeric',
            'bank_name.required' => 'err_bank_name_required',
            'bank_name.max' => 'err_bank_name_max',
            'bank_name.regex' => 'err_bank_name_regex',
            'branch_code.required' => 'err_branch_code_required',
            'branch_code.digits_between' => 'err_branch_code_digits_between',
            'branch_code.numeric' => 'err_branch_code_numeric',
            'branch_name.required' => 'err_branch_name_required',
            'branch_name.max' => 'err_branch_name_max',
            'branch_name.regex' => 'err_branch_name_regex',
            'bank_account_type.required' => 'err_bank_account_type_required',
            'bank_account_number.required' => 'err_bank_account_number_required',
            'bank_account_number.digits_between' => 'err_bank_account_number_digits_between',
            'bank_account_number.numeric' => 'err_bank_account_number_numeric',
            'bank_account_name.required' => 'err_bank_account_name_required',
            'bank_account_name.max' => 'err_bank_account_name_max',
            'bank_account_name.regex' => 'err_bank_account_name_regex',
            'bank_account_name_kana.max' => 'err_bank_account_name_kana_max',
            'post_account_kigou.required' => 'err_post_account_kigou_required',
            'post_account_kigou.digits_between' => 'err_post_account_kigou_digits_between',
            'post_account_kigou.numeric' => 'err_post_account_kigou_numeric',
            'post_account_number.required' => 'err_post_account_number_required',
            'post_account_number.digits_between' => 'err_post_account_number_digits_between',
            'post_account_number.numeric' => 'err_post_account_number_numeric',
            'post_account_name.required' => 'err_post_account_name_required',
            'post_account_name.max' => 'err_post_account_name_max',
            'post_account_name.regex' => 'err_post_account_name_regex',
            'student_no.required' => 'err_student_no_required',
            'student_no.unique' => 'err_student_no_unique',
            'representative_name.max' => 'representative_name_max',
            'representative_name_kana.max' => 'representative_name_kana_max',
            'representative_name_kana.regex' => 'representative_name_kana_regex',
            'representative_position.max' => 'representative_position_max',
            'representative_email.email' => 'representative_email_format',
            'representative_tel.regex' => 'representative_tel_regex',
        );
        return $message;
    }

    public function newDetail(Request $request) {

        //会員種別
        $studentTypes = parent::getListStudentType();

        if (! $request->has('id')) {
            return redirect($this->get_app_path() . self::$TOP_URL);
        }

        //Check prev - next request
        $prevNextStudentIds = session('prev_next_student_ids');
        if ($prevNextStudentIds && $prevNextStudentIds != null) {
            $keyOfCurrentStudent = array_search($request->id, $prevNextStudentIds);
            if (isset($prevNextStudentIds[$keyOfCurrentStudent + 1])) {
                $request->offsetSet('next_id', $prevNextStudentIds[$keyOfCurrentStudent + 1]);
            }
            if (isset($prevNextStudentIds[$keyOfCurrentStudent - 1])) {
                $request->offsetSet('prev_id', $prevNextStudentIds[$keyOfCurrentStudent - 1]);
            }
        }

        //会員情報
        $student = StudentTable::getInstance()->getStudentParentInfo(['student_id' => $request->id]);

        if (! $student) {
            return redirect($this->get_app_path() . self::$TOP_URL);
        }

        // 2017-07-13　退会日を過ぎたら自動的に契約終了にする
        if ($student['active_flag'] && $student['resign_date'] != null && $student['resign_date'] <= date('Y-m-d')) {
            $student['active_flag'] = 0;
            $student['update_date'] = date('Y-m-d');
            StudentTable::getInstance()->save($student);
        }

        //支払方法
        //        $invoice_type = ConstantsModel::$invoice_type[session('school.login.language')];
        //        $payment_list = PaymentMethodTable::getInstance()->getPaymentMethodPschool(session('school.login')['id']);
        //        $invoice_type = array();
        //        foreach ($payment_list as $item) {
        //            $invoice_type[$item['payment_method_id']] = $item['payment_method_name'];
        //        }
        $invoice_type = Constants::$invoice_type[session('school.login.language')];

        $type_of_bank_account = ConstantsModel::$type_of_bank_account[session('school.login.language')];

        //詳細情報
        $classes = ClassTable::getInstance()->getListClassByStudent($request->id);
        $courses = CourseTable::getInstance()->getListCourseByStudent($request->id);
        $programs = ProgramTable::getInstance()->getListProgramByStudent($request->id);

        //        $invoices = InvoiceHeaderTable::getInstance()->getActiveList(array('parent_id' => $student['parent_id'], 'workflow_status = 31 OR workflow_status = 11'));

        //get deposited invoice back to 1 year from now
        $pschool_id = session()->get('school.login.id');

        $invoice_year_month_to = $this->get_newsest_invoice_month($pschool_id);
        $invoice_year_month_from = date('Y-m', strtotime("-12 month", strtotime($invoice_year_month_to)));

        $filter = array (
            'pschool_id' => $pschool_id,
            'student_no' => $student['student_no'],
            'workflow_status' => array (11, 21, 22, 29, 31),
            'invoice_year_month_from' => $invoice_year_month_from,
            'invoice_year_month_to' => $invoice_year_month_to,
            'order_by' => 'invoice_year_month'
        );
        $invoices = InvoiceHeaderTable::getInstance()->getDeposits($filter);
        foreach ($invoices as $k => $invoice) {
            //if is_nyukin -> get event and program name base on invoice_item
            if ($invoice['is_nyukin'] != 0) {
                $item = InvoiceItemTable::getInstance()->getActiveList(array ('invoice_id' => $invoice['id']));
                $item = $item[0];

                if ($item['course_id'] != null) {
                    $event = CourseTable::getInstance()->load($item['course_id']);
                    $invoices[$k]['item_name'] = $event['course_title'];
                } elseif ($item['program_id'] != null) {
                    $program = ProgramTable::getInstance()->load($item['program_id']);
                    $invoices[$k]['item_name'] = $program['program_name'];
                }
            }
        }

        //削除可能フラグ
        //TODO need to add invoice
        $delete_enable = $this->isCanDelete($classes, $courses, $programs);
        //請求
        $invoice_list = array ();
        $list_h = InvoiceHeaderTable::getInstance()->getListOfCurrentByStudent(session('school.login') ['id'], $request->id);
        if (count($list_h) > 0) {
            $delete_enable = false;
        }

        foreach ($list_h as $row) {
            $order = array (
                'class_id' => 'DESC',
                'course_id' => 'DESC',
                'school_category' => 'DESC',
                'school_year' => 'DESC'
            );
            $list_i [] = InvoiceItemTable::getInstance()->getList(array (
                'invoice_id' => $row ['id'],
                'active_flag' => 1,
                'delete_date is null'
            ), $order);
        }

        foreach ($list_h as $index => $row_h) {
            $amount = 0;
            foreach ($list_i [$index] as $row_i) {
                $amount += $row_i ['unit_price'];
            }

            // 2015-11-04 蜑ｲ蠑輔�ｯinvoice_item縺ｫ縺ｯ縺�縺｣縺ｦ縺�繧九▲縺ｽ縺�
            $row_h ['discount_price'] = 0;

            // 2015-11-04 螟也ｨ弱�ｮ縺ｨ縺阪↓豸郁ｲｻ遞弱ｒ霑ｽ蜉�
            if ($row_h ['amount_display_type'] == 1) {
                $row_h ['tax_price'] = $amount * $row_h ['sales_tax_rate'];
            } else {
                $row_h ['tax_price'] = 0;
            }

            $invoice_list [$index] = $row_h;
            $invoice_list [$index] ['item'] = $list_i [$index];
            $invoice_list [$index] ['amount'] = $amount - $row_h ['discount_price'] + $row_h ['tax_price'];

            // 謾ｯ謇慕憾豕�
            if ($invoice_list [$index] ['is_recieved'] == 0) {
                if ($invoice_list [$index] ['due_date'] < date('Y-m-d')) {
                    // 譛ｪ邏�
                    $invoice_list [$index] ['payment_status_code'] = 0;
                } else {
                    // 縺ｾ縺�謾ｯ謇墓悄髯舌↓縺ｪ縺｣縺ｦ縺�縺ｪ縺�
                    $invoice_list [$index] ['payment_status_code'] = 2;
                    $invoice_list [$index] ['payment_status'] = "";
                }
            } else if ($invoice_list [$index] ['is_recieved'] == 1) {
                // 謾ｯ謇墓ｸ医∩
                $invoice_list [$index] ['payment_status_code'] = 1;
            } else {
                // 蜿｣蠎ｧ謖ｯ譖ｿ 繧ｨ繝ｩ繝ｼ縺ゅｊ
                $invoice_list [$index] ['payment_status_code'] = 2;
                $invoice_list [$index] ['payment_status'] = ConstantsModel::$zengin_status [1] [$invoice_list [$index] ['is_recieved']];
            }
        }


        // 蜑企勁蜿ｯ閭ｽ繝輔Λ繧ｰ
        $lan = $this->lan;

        // get edit_auth
        //        $auths = AuthConfig::getAuth ( 'school' );
        //        $edit_auth = _BaseSchoolController::setEditAuth ( session ( 'school.login' ) ['edit_authority'] );

        //get invoice background color
        $invoice_background_color = Constants::invoice_background_color;

        // additional cate
        //Additional category
        $additionalCategories = AdditionalCategoryTable::getInstance()->getListData([
            'type' => ConstantsModel::$ADDITIONAL_CATEGORY_STUDENT,
            'related_id' => $request->id
        ], true);

        //Get person_in_charge info
        $person_in_charge_list = StudentPersonInChargeTable::getInstance()->getActiveList(array ('student_id' => $request->id));
        $request->merge(['person_in_charge' => $person_in_charge_list]);

        return view('School.SStudent.detail', compact('studentTypes', 'invoices', 'request', 'tab_index', 'topic_list', 'contents_index', 'visit_list', 'last_visit', 'current_month_list', 'programs', 'read_only', 'app_score_list', 'score_list', 'report_list', 'student_id', 'student', 'parent', 'studentExam_list', 'classes', 'invoice_list', 'student_type_name', 'address_pref_name', 'address_city_name', 'student_address_pref_name', 'student_address_city_name', 'exam_pref1_name', 'exam_city1_name', 'exam_pref2_name', 'exam_city2_name', 'exam_pref3_name', 'exam_city3_name', 'courses', 'consul_list', 'ap_record_display', 'exam_record_display', 'school_record_display', 'pschool', 'invoice_type', 'type_of_bank_account', 'grade_list', 'person_flag', 'parent_bank', 'subject_list', 'original_subject', 'exam_detail_list', 'subject_select_list', 'original_subject', 'select_subject_template_id', 'original_report', 'reportcard_list', 'report_list', 'report_select_list', 'auths', 'select_report_template_id', 'delete_enable', 'edit_auth', 'lan', 'main_captions', 'invoice_background_color', 'additionalCategories'));

    }

    /**
     * Get Summary student data. All student, under contact, end contact student number
     * @return mixed
     */
    protected function _getStudentSummary() {

        $data = DB::table('student')->select(DB::raw('COUNT(id) AS total'))->addSelect(DB::raw('SUM(CASE WHEN (active_flag = 1 AND (resign_date IS NULL OR resign_date > NOW()) ) THEN 1 ELSE 0 END) AS under_contract'))->addSelect(DB::raw('SUM(CASE WHEN (active_flag = 0) THEN 1 ELSE 0 END) AS end_contract'))->where('pschool_id', "=", session('school.login.id'))->where('delete_date', '=', null)->first();
        $res = (array) $data;

        return $res;
    }

    public function ajaxListParent(Request $request) {

        try {
            if (! $request->ajax()) {
                throw new Exception();
            }
            $data = DB::table('parent AS p')->select('p.*')->where('p.pschool_id', "=", session('school.login.id'))->whereNull('p.delete_date')->orderBy('p.name_kana')->get();

            return response()->json(['status' => true, 'message' => $data]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => '']);
        }
    }

    public function ajaxGetParent(Request $request) {

        try {
            if (! $request->ajax()) {
                throw new Exception();
            }
            $parent = DB::table('parent')->where('id', "=", $request->id)->whereNull('delete_date')->first();
            $bankAccount = DB::table('parent_bank_account')->where('parent_id', "=", $request->id)->whereNull('delete_date')->first();
            $routinePayment = DB::table('routine_payment')->where('data_div', "=", ConstantsModel::$DISCOUNT_PARENT)->where('data_id', "=", $request->id)->whereNull('delete_date')->get();

            return response()->json([
                'status' => true,
                'parent' => $parent,
                'bank_account' => $bankAccount,
                'routine_payment' => $routinePayment
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => '']);
        }
    }

    public function ajaxChangeStudentState(Request $request) {

        try {
            if (! $request->ajax() || ! $request->has('id') || ! $request->has('state')) {
                throw new Exception();
            }

            $student = StudentTable::getInstance()->load($request->id);
            if (! $student) {
                throw new Exception();
            }

            $data = [
                'update_date' => date('Y-m-d H:i:s'),
                'update_admin' => session('school.login.login_account_id')
            ];
            if ($request->state == 1) {
                // 退会 -> 受講
                $data['active_flag'] = 1;
                $data['resign_date'] = null;
            } elseif ($request->state == 2) {
                // 受講 -> 休校
                $data['active_flag'] = 0;
                $data['resign_date'] = null;
            } else {
                // 休校 -> 退会
                $data['active_flag'] = 0;
                $data['resign_date'] = date('Y-m-d H:i:s');
            }

            $result = DB::table('student')->where('id', '=', $request->id)->update($data);

            if (! $result) {
                throw new Exception();
            }

            return response()->json(['status' => true]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => '']);
        }
    }

    public function delete(Request $request) {

        if (! $request->has('id')) {
            return redirect('/school/student/entry');
        }

        $student = StudentTable::getInstance()->load($request->id);
        if (! $student) {
            return redirect('/school/student');
        }

        $classes = ClassTable::getInstance()->getListClassByStudent($request->id);
        $courses = CourseTable::getInstance()->getListCourseByStudent($request->id);
        $programs = ProgramTable::getInstance()->getListProgramByStudent($request->id);
        //Check can delete or not
        //TODO need to add invoice
        $canDelete = $this->isCanDelete($classes, $courses, $programs);
        if (! $canDelete) {
            return redirect('/school/student');
        }

        $data = [
            'active_flag' => 0,
            'resign_date' => date('Y-m-d H:i:s'),
            'delete_date' => date('Y-m-d H:i:s'),
            'update_admin' => session('school.login.login_account_id')
        ];
        //remove logical student
        $result = DB::table('student')->where('id', '=', $request->id)->update($data);

        //remove logical parent
        $remaining_student_ids = DB::table('student')->where('parent_id', '=', $student['parent_id'])->whereNull('delete_date')->pluck('id')->toArray();
        // parent does not have other(s) children
        if (! $remaining_student_ids) {
            $data = [
                'update_date' => date('Y-m-d H:i:s'),
                'delete_date' => date('Y-m-d H:i:s'),
                'update_admin' => session('school.login.login_account_id')
            ];
            // execute remove logical parent
            DB::table('parent')->where('id', '=', $student['parent_id'])->where('pschool_id', '=', session()->get('school.login.id'))->update($data);
            // execute remove logical parent bank account
            DB::table('parent_bank_account')->where('parent_id', '=', $student['parent_id'])->update($data);
        }

        session()->put('status', $this->lan->get('update_success'));
        return redirect('/school/student');
    }

    /**
     * Only can delete student when not participate in any classes, courses, programs
     * @param $classes
     * @param $courses
     * @param $programs
     * @param $date date Default today
     * @return bool
     */
    public function isCanDelete($classes, $courses, $programs, $date = null) {

        if ($date == null) {
            $date = date('Y-m-d');
        }

        if (! $classes && ! $courses && ! $programs) {
            return true;
        }

        foreach ($classes as $class) {
            if ($class['close_date'] == null && $class['delete_date'] == null) {
                return false;
            }
            if (strtotime($class['close_date']) >= strtotime($date) && $class['delete_date'] == null) {
                return false;
            }
        }
        foreach ($courses as $course) {
            if ($course['close_date'] == null && $course['delete_date'] == null) {
                return false;
            }
            if (strtotime($course['close_date']) >= strtotime($date) && $course['delete_date'] == null) {
                return false;
            }
        }
        foreach ($programs as $program) {
            if ($program['close_date'] == null && $program['delete_date'] == null) {
                return false;
            }
            if (strtotime($program['close_date']) >= strtotime($date) && $program['delete_date'] == null) {
                return false;
            }
        }
        return true;
    }

    public function get_newsest_invoice_month($pschool_id) {

        //
        $invoice_year_month = null;

        //
        $pschool = PschoolTable::getInstance()->load($pschool_id);
        $invoice_closing_date = $pschool['invoice_closing_date'] == 99 ? date('Y-m-t') : date('Y-m-d', strtotime(date('Y-m-') . $pschool['invoice_closing_date']));
        $payment_style = $pschool['payment_style'];
        $today = date('Y-m-d');
        $list_payment = PaymentMethodPschoolTable::getInstance()->getActiveList(array ('pschool_id' => $pschool_id), array ('sort_no'));
        $is_koza = false;
        foreach ($list_payment as $k => $v) {
            if ($v['payment_method_code'] == Constants::TRAN_RICOH) {
                $is_koza = true;
            }
        }

        // payment_style = 1 : prepay
        if ($payment_style == 1) {
            if ($today > $invoice_closing_date) {
                $invoice_year_month = date('Y-m', strtotime(date("Y-m-d") . "+2 month"));
            } else {
                if ($is_koza) {
                    $dead_line = $this->getDeadLineOfPayment($pschool_id, date('Y-m', strtotime(date("Y-m-d") . "+1 month")), 2);  // increase 1 month cause this function will decrease 1 when return.
                    if ($today < $dead_line) {
                        $invoice_year_month = date('Y-m', strtotime(date("Y-m-d") . "+1 month"));
                    } else {
                        $invoice_year_month = date('Y-m', strtotime(date("Y-m-d") . "+2 month"));
                    }
                } else {
                    $invoice_year_month = date('Y-m', strtotime(date("Y-m-d") . "+1 month"));
                }
            }
        } else { // payment_style = 2 : postpay
            if ($today > $invoice_closing_date) {
                $invoice_year_month = date('Y-m', strtotime(date("Y-m-d") . "+1 month"));
            } else {
                if ($is_koza) {
                    $dead_line = $this->getDeadLineOfPayment($pschool_id, date('Y-m', strtotime(date("Y-m-d") . "+1 month")), 2);  // increase 1 month cause this function will decrease 1 when return.
                    if ($today < $dead_line) {
                        $invoice_year_month = date('Y-m');
                    } else {
                        $invoice_year_month = date('Y-m', strtotime(date("Y-m-d") . "+1 month"));
                    }
                } else {
                    $invoice_year_month = date('Y-m');
                }
            }
        }

        return $invoice_year_month;
    }

    public function getDeadLineOfPayment($pschool_id, $invoice_year_month, $invoice_type) {

        $pschool = PschoolTable::getInstance()->load($pschool_id);

        //
        if ($invoice_type == Constants::$PAYMENT_TYPE['POST_RICOH']) {
            $deadline = $pschool['payment_date'];
            if ($deadline == 99) {
                $deadline = date('Y-m-t', strtotime($invoice_year_month . '-01'));
            }
            return $deadline . $this->deadline_hours;
        }

        // get withdrawal date of payment_method and payment_agency
        $payment_type_id = array_flip(Constants::$PAYMENT_TYPE);
        $bind = array (
            $payment_type_id[$invoice_type],
            'withdrawal_date',
            $pschool_id
        );
        $sql = "SELECT pms.default_value, pmd.item_value
              FROM payment_method pm
              LEFT JOIN payment_method_setting pms ON pm.id = pms.payment_method_id AND pm.payment_agency_id = pms.payment_agency_id
              LEFT JOIN payment_method_data  pmd ON pmd.payment_method_setting_id = pms.id
              WHERE pm.code = ?
              AND pms.item_name = ? 
              AND pmd.pschool_id = ?
              AND pm.delete_date IS NULL 
              AND pms.delete_date IS NULL 
              ";
        $res = PaymentMethodTable::getInstance()->fetch($sql, $bind);
        if (empty($res)) {
            // avoid error when not setting withdrawal_date for method => return default 27
            $withdrawal_day = 27;
        } else {
            $default_value = explode(";", $res['default_value']);
            $value = $default_value[$res['item_value'] - 1];
            $payment_date = explode(":", $value);
            $withdrawal_day = $payment_date[1];
        }

        // get info from closing_day_table with $withdrawal_day
        $bind2 = array (
            date('Y-m', strtotime($invoice_year_month . "-01" . "-1 month")), // duedate month  = transfer_ month -1
            $withdrawal_day
        );
        $date_sql = "SELECT transfer_month , transfer_date, deadline
                      FROM closing_day WHERE transfer_month = ? 
                      AND transfer_day = ?
                      ORDER BY deadline ASC LIMIT 1 ";
        $closeDay = ClosingDayTable::getInstance()->fetch($date_sql, $bind2);
        return $closeDay['deadline'] . $this->deadline_hours;
    }

    public function ajax_get_student_type(Request $request) {

        try {
            if (! $request->ajax() || ! $request->offsetExists("type")) {
                throw new Exception();
            }
            $mStudentTable = MStudentTypeTable::getInstance();
            $data = $mStudentTable->getStudentTypeList([session('school.login.id')], session()->get('school.login.language'), $request->type);

            return response()->json(['status' => true, 'message' => $data]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => '']);
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

?>