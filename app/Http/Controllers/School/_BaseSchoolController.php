<?php

namespace App\Http\Controllers\School;
use App\Common\Constants;
use App\Http\Controllers\Common\_BaseAppController;
use App\Model\PschoolTable;
use App\Model\StaffTable;
use App\Model\CoachTable;
use App\Model\SchoolMenuTable;
use App\Model\MStudentTypeTable;
use App\ConstantsModel;
use App\Common\ImageHandler;
use Illuminate\Support\Facades\Mail;
use DB;

class _BaseSchoolController extends _BaseAppController {
    const SESSION_BREAD_LIST = 'session_class_bread_list';
    // メニュー番号を保存するキー
    const SESSION_MENU_NUMBER = 'session_menu_number';
    // メニューのタイトル
    var $main_caption;
    protected static $LANG_URL = "";
    protected $current_lang;
    protected $main_title;
    var $current_country_code;
    protected $_loginAdmin = null;
    private $CLEAR_STR = "?clr";
    public function __construct() {
        parent::__construct ();
        $this->getLeftMenu ();
        $this->current_lang = session ( 'school.login.lang_code' );
    }

    public function getLeftMenu() {
        if (session ()->has ( 'school.login' ) && session()->has('hierarchy.role')) {
            $member_type = strtoupper(session('hierarchy.role'));

            $menu_list = null;
            $menu_auth = array();
            $sub_menu_list = array ();
            $main_captions = array();
            if(array_search($member_type, ConstantsModel::$member_type) == 4 ){
                // case parent login

                $menu_list = SchoolMenuTable::getInstance ()->getActiveMenuListNew2(session ( 'school.login.id'), ConstantsModel::$LOGIN_AUTH_PARENT);
            }elseif ( array_search($member_type, ConstantsModel::$member_type) == 5){
                // case student login
                $student = DB::table ( 'student' )->where('id', session ( 'school.login.origin_id'))->first();
                $student_type_id =  $student->m_student_type_id;
                $menu_list = SchoolMenuTable::getInstance ()->getActiveMenuListNew2(session ( 'school.login.id'), ConstantsModel::$LOGIN_AUTH_STUDENT, $student_type_id);
            }else{

                $menu_list = SchoolMenuTable::getInstance ()->getActiveMenuListNew2(session ( 'school.login.origin_id'), array_search($member_type, ConstantsModel::$member_type));
            }
            foreach ( $menu_list as $key => $value ) {
                // setting for auth
                $action_url = (strpos($value['action_url'], $this->CLEAR_STR))? substr($value['action_url'], 0, -4) : $value['action_url'];
                $menu_auth[$action_url] = array(
                        'viewable' => $value['viewable'],
                        'editable' => $value['editable'],
                );
                // push to main caption
                $main_captions[$value['master_menu_id']] = $value['menu_name_key'];
                $menu_path = explode ( "/", $value ['menu_path'] );
                array_pop ( $menu_path );
                if (count ( $menu_path ) > 1) {

                    $sub_menu_list [$menu_path [0]] [$key] = $value;
                    unset ( $menu_list [$key] );
                }
            }

            view ()->share ( 'menuList', $menu_list );
            view ()->share ( 'submenuList', $sub_menu_list );
            session ( [ 'menu_auth' => $menu_auth ] );
            session ( [ 'main_captions' => $main_captions ] );
        }
    }
    
    // get message locale
    public function getMessageLocale() {
        // メッセージ取得
        // $lan_contents : ["school.home" => [...], "school.class"=>[...],...]
        // $session_name: school.lan
        // $message_block: school.default_menu
        $lan_contents = array ();
        $session_name = $this->_id . '.lan';
        if (session ()->has ( $session_name )) {
            
            // get message corresponding screen
            $message_contents = session ( $session_name );
            $message_block = $this->_id . "." . static::$LANG_URL;
            if (isset ( $message_contents [$message_block] )) {
                
                $lan_contents = $message_contents [$message_block];
            }
            
            // import message leftmenu
            if (array_key_exists ( $this->_id . ".menu", $message_contents )) {
                $lan_contents = array_merge ( $lan_contents, $message_contents [$this->_id . ".menu"] );
            }

            if (array_key_exists ( $this->_id . ".left_panel", $message_contents )) {
                $lan_contents = array_merge ( $lan_contents, $message_contents [$this->_id . ".left_panel"] );
            }
        }
        return $lan_contents;
    }
    public function getListStudentType() {
        $language = 1;
        
        // 多国語対応
        if (session ( 'school.login' ) ['language'] !== null) {
            $language = session ( 'school.login' ) ['language'];
        }
//         dd($language);
        // 生徒種別
        $school_ids = array ();
        if (session ( 'school.hierarchy' ) ['pschool_parents']) {
            $school_ids = session ( 'school.hierarchy' ) ['pschool_parents'];
        }
        $school_ids [] = session ( 'school.login' ) ['id'];
        $student_types = array ();
        $m_type = MStudentTypeTable::getInstance ()->getStudentTypeList ( $school_ids, $language);
        if (! empty ( $m_type )) {
            foreach ( $m_type as $value ) {
                $key = $value ['type'];
                $student_types [$key] = $value ['name'];
            }
        }
        return $student_types;
    }
    public function getSchoolCategory() {
        $schoolCategory = ConstantsModel::$dispSchoolCategory;
        return $schoolCategory;
    }
    public function getBankAccountList() {
        $bank_account_type_list = ConstantsModel::$type_of_bank_account [session ( 'school.login' ) ['language']];
        return $bank_account_type_list;
    }

    public function getTemplateUrlStudent() {
        if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
            // 運用区分が塾の場合
            $TEMPLATE_URL = 'student';
        } elseif (session ( 'School.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
            // 運用区分が会員クラブの場合
            $TEMPLATE_URL = 'student';
        } else {
            $TEMPLATE_URL = 'student';
        }
        return $TEMPLATE_URL;
    }
    public function getTemplateUrlParent() {
        if (session ( 'school.login' ) ['business_divisions'] == 1 || session ( 'school.login' ) ['business_divisions'] == 3) {
            // 運用区分が塾の場合
            $TEMPLATE_URL = 'parent';
        } elseif (session ( 'School.login' ) ['business_divisions'] == 2 || session ( 'school.login' ) ['business_divisions'] == 4) {
            // 運用区分が会員クラブの場合
            $TEMPLATE_URL = 'parent';
        }
        return $TEMPLATE_URL;
    }
    public function copyImage($item, $params = array()) {
        $prefix = "_";
        foreach ( $params as $idx => $param ) {
            $prefix .= $idx . $param;
        }
        // require_once 'ImageHandler.php';
        if (! empty ( $item )) {
            $filepath = (mb_convert_encoding ( $item, 'utf-8', 'sjis' ));
            // 縦横の上限値
            $pinfo = pathinfo ( $filepath );
            // 日本語ファイル名のコンバート
            // $item = str_replace('%', '',urlencode($pinfo['filename'])) . $prefix . "." . $pinfo['extension'];
            $item = request ()->id . date ( "_YmdHms_" ) . md5 ( uniqid ( mt_rand (), TRUE ) ) . $prefix . "." . $pinfo ['extension'];
            $dest_filepath = mb_convert_encoding ( IMAGE_FULL_PATH . $item, 'utf-8', 'sjis' );
            $imgfile = new ImageHandler ( $filepath, $pinfo ['extension'], $params );
            $imgfile->executefile ( $dest_filepath );
        }
        return $item;
    }
    /**
     * ログイン情報を取得する
     *
     * @param string $key            
     * @return string|array
     */
    protected function getLoginSession($key = null) {
        $session_name = $this->get_app_login_session_var ();
        if (array_key_exists ( $session_name, session () )) {
            if (is_null ( $key )) {
                return $_SESSION [$session_name];
            }
            if (array_key_exists ( $key, $_SESSION [$session_name] )) {
                return $_SESSION [$session_name] [$key];
            }
        }
        return null;
    }
    
    /**
     * APPのLOGINユーザーのSESSION変数名を返却する。URLの第１パスと違う場合はオーバーライドすること
     */
    protected function get_app_login_session_var() {
        // return 'admin';
        return $this->get_app_id () . '.login';
    }
    
    /**
     * APPのIDを返却する。URLの第１パスと違う場合はオーバーライドすること
     * この返却値は、システム内のパスの設定に使います。
     */
    protected function get_app_id() {
        return $this->_id;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see _BaseAppAction::session_not_found()
     */
    public function session_not_found() {
        return redirect ( $this->get_app_path () );
    }
    
    protected function getLoginAdmin() {
        $loginAdmin = session ( 'school.login' );
        if (empty ( $loginAdmin )) {
            // あなた入っちゃだめです。
            parent::session_not_found ();
            exit ();
        }

        // 一応、再読み込み
        if (! isset ( $loginAdmin ['staff_id'] ) && ! isset ( $loginAdmin ['coach_id'] )) { // 塾管理者の場合
            $admin = PschoolTable::getInstance ()->loadWithLoginAccount ( $loginAdmin ['id'] );
            if (empty ( $admin )) {
                // あなた、たぶん、登録抹消されてます。
                parent::session_not_found ();
                exit ();
            }
            $admin['origin_id'] = $admin['id'];

        } else if (isset ( $loginAdmin ['staff_id'] )) { // スタッフの場合
            $admin = StaffTable::getInstance ()->loadWithLoginAccount ( $loginAdmin ['staff_id'] );
            if (empty ( $admin )) {
                // あなた、たぶん、登録抹消されてます。
                parent::session_not_found ();
                exit ();
            } else {
                $pschool = PschoolTable::getInstance ()->getActiveRow ( array (
                        'id' => $admin ['pschool_id'] 
                ) );
                if (empty ( $pschool )) {
                    // そんな塾ありません
                    parent::session_not_found ();
                    exit ();
                }
                $admin ['staff_id'] = $admin ['id'];
                $admin ['id'] = $admin ['pschool_id'];
                $admin ['name'] = $pschool ['name'];
                $admin ['pschool_code'] = $pschool ['pschool_code'];

                $admin['origin_id'] = $admin['staff_id'];
            }
        } else if (isset ( $loginAdmin ['coach_id'] )) {
            $admin = CoachTable::getInstance ()->loadWithLoginAccount ( $loginAdmin ['coach_id'] );
            if (empty ( $admin )) {
                // あなた、たぶん、登録抹消されてます。
                parent::session_not_found ();
                exit ();
            } else {
                $pschool = PschoolTable::getInstance ()->getActiveRow ( array (
                        'id' => $admin ['pschool_id'] 
                ) );
                if (empty ( $pschool )) {
                    // そんな塾ありません
                    parent::session_not_found ();
                    exit ();
                }
                $admin ['coach_id'] = $admin ['id'];
                $admin ['id'] = $admin ['pschool_id'];
                $admin ['name'] = $pschool ['name'];
                $admin ['pschool_code'] = $pschool ['pschool_code'];

                $admin['origin_id'] = $admin['coach_id'];
            }
        }

        session ( 'school.login', $admin );
        $_loginAdmin = $admin;
        return $_loginAdmin;
    }

    final protected function send_mail($params) {
        $mail->mtaOption ( "-f " . $params ['from'] );
        if (is_array ( $params ['to'] )) {
            $mail->to ( $params ['to'] );
        } else {
            $mail->to ( array (
                    $params ['to'] 
            ) );
        }
        if (! empty ( $params ['cc'] )) {
            $mail->cc ( $params ['cc'] );
        }
        if (! empty ( $params ['bcc'] )) {
            $mail->bcc ( $params ['bcc'] );
        }
        $mail->subject ( $params ['subject'] );
        $mail->text ( $params ['text'] );
        $mail->from ( $params ['from'], '' );
        
        $ret = $mail->send ();
        if ($ret == false) {
            $this->_logger->error ( ConstantsModel::$logger [1] ['warn_send_mail'] );
        }
        return $ret;
    }

    /**
     * メール送信
     * @param $template: blade file to display content mail
     * @param $assign_var
     * @param $to_arr: array mail address
     * @param $subject
     */
    protected function common_send_mail($template, $assign_var, $to_arr, $subject) {
        if (view()->exists($template)) {
            Mail::send($template, ['mail' => $assign_var], function($message) use ($to_arr, $subject)
            {
                foreach ($to_arr as $email) {
                    $message->to($email)->subject($subject);
                }
            });
            return true;
        }

        return false;
        
    }

    final protected function mail_status_string($params) {
        $str = ConstantsModel::$form_keys [1] ['send_mail'];
        foreach ( $params as $key => $val ) {
            $str .= $key . '=' . $val . ', ';
        }
        return $str;
    }
    
    /**
     * メール本文の作成
     */
    protected function createMailMessage($tpl, $assign_var) {
        $tpl_path = '_mail' . DIRECTORY_SEPARATOR . $tpl;
        $file = $this->_smarty->template_dir . $tpl_path;
        if (file_exists ( $file )) {
            // 本文作成
            $this->_smarty->assign ( 'mail', $assign_var );
            $this->_smarty->assign ( 'request', $_REQUEST );
            $message = $this->_smarty->fetch ( $tpl_path );
            
            return $message;
        } else {
            throw new Exception ( 'Template File Not Found!!' );
        }
    }

    public function getSortListByClassCourse($disp_list, $active_flag = NULL) {
        if (count ( $disp_list ) > 1) {
            foreach ( $disp_list as $key => $value ) {
                if (! empty ( $active_flag ) && empty ( $value ['is_active'] )) {
                    unset ( $disp_list [$key] );
                } else {
                    $empty [$key] = empty ( $value ['start_date'] ) ? 1 : 0;
                    $start [$key] = $value ['start_date'];
                    $valid [$key] = empty ( $value ['is_active'] ) ? 1 : 0;
                }
            }

            array_multisort ( $empty, SORT_DESC, $valid, SORT_ASC, $start, SORT_DESC, $disp_list );
        }
        return $disp_list;
    }

    public function recoverWithInput($request, $data=array()) {
        foreach ($data as $value) {
            $request->offsetSet($value, old($value, $request->$value));
        }
    }

    public function clearOldInputSession() {
        if (session ()->has('_old_input')) {
            session()->forget ('_old_input');
        }

        if (session()->has('errors')) {
            session()->forget('errors');
        }
    }

    /**
     * Get Menu Setting Data
     *
     * @param  $id Login Account
     * @return array menu datas
     */
    public function getMenuData($id = null, $member_type = null, $m_student_type_id = null) {
        $default_menu_select = array ();

        // TODO get parent menu list
        $parent_member_type = array_search(strtoupper(session('hierarchy.role')), ConstantsModel::$member_type);
        // $default_menu_select: INSERT:template menu; EDIT:existed menu
        $menu_list = SchoolMenuTable::getInstance()->getActiveMenuListNew2(session('school.login.id' ), $parent_member_type);

        // handle sub menu
        $sub_menu_list = array ();
        foreach ( $menu_list as $key => $value ) {
            $menu_path = explode ( "/", $value ['menu_path'] );
            array_pop ( $menu_path );
            if (count ( $menu_path ) > 1) {
                $sub_menu_list [$menu_path [0]] [$key] = $value;
                unset( $menu_list [$key] );
            }

            // push into default list
            if ($value['default_flag'] || array_key_exists($menu_path [0], $default_menu_select)) { // check default_flag OR parent also default
                $default_menu_select[$value['master_menu_id']] = $value;
            }

        }

        if (! is_null( $id )) { // Edit
            // $menu_select : school menu list
            if(!empty($m_student_type_id)){
                $menu_select = SchoolMenuTable::getInstance()->getActiveMenuListNew2($id, $member_type, $m_student_type_id);
            }else{
                $menu_select = SchoolMenuTable::getInstance()->getActiveMenuListNew2($id, $member_type);
            }

            $default_menu_select = array_intersect_key ( $menu_select, $menu_list );
        }

        // handle sub menu
        $default_sub_menu_list = array ();
        foreach ( $default_menu_select as $key => $value ) {
            $menu_path = explode ( "/", $value ['menu_path'] );
            array_pop ( $menu_path );
            if (count ( $menu_path ) > 1) {
                $default_sub_menu_list [$menu_path [0]] [$key] = $value;
                unset( $default_menu_select [$key] );
            }
        }

        return [
            'parentMenuList' => $menu_list,
            'parentSubMenuList' => $sub_menu_list,
            'defaultMenuList' => $default_menu_select,
            'defaultSubMenuList' => $default_sub_menu_list
        ];
    }

    protected function _initSearchDataFromSession($items, $session_key) {
        if (request()->offsetExists('clr')) {
            return session()->forget($session_key);
        }
        //dd($items,session()->get($session_key));
        foreach ($items as $item) {
            if (request()->offsetExists($item)) {
                // Save to session new search info when form submitted
                session()->put($session_key . '.' . $item, request()->get($item));
            } elseif (session()->has($session_key . '.' . $item)) {
                // If nothing has submit, then try to get data from session
                request()->offsetSet($item, session($session_key . '.' . $item));
            }
        }
    }
}
