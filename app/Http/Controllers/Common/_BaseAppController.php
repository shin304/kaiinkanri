<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\ConstantsModel;
use App\Lang;
use Config;
use File;

class _BaseAppController extends Controller {
    protected $_id;
    public function __construct() {
        $request = Request::capture ();
        $this->_id = $request->segment ( 1 );
        view ()->share ( '_app_path', $this->get_app_path () );
        // 多言語設定
        app ()->setLocale ( config ( 'app.locale' ) );
        $path = resource_path () . '/lang/' . config ( 'app.locale' ) . '/';
        $content = File::getRequire ( $path . 'common.php' );
        view ()->share ( 'lan', new Lang ( $content ) );
    }
    public function session_not_found() {
        return redirect ()->back ()->withInput ();
    }
    
    /**
     * APPのIDを返却する。URLの第１パスと違う場合はオーバーライドすること
     * この返却値は、システム内のパスの設定に使います。
     */
    protected function get_app_id() {
        return $this->_id;
    }
    
    /**
     * <<<<<<< HEAD
     * <<<<<<< HEAD
     * APPのURLのパスを返却する。URLの第１パスと違う場合はオーバーライドすること
     * この返却値は、テンプレート内のURLの記述に使います。
     */
    protected function get_app_path() {
        return '/' . $this->_id . '/';
    }
    
    /**
     * 共通の初期処理
     * サブプランで当メソッドをオーバーライトする時は
     * 必ず先頭で、parent::init_app()を実装してください
     * また、noCheck=trueで実行した場合、権限チェックは行われません
     *
     * @param unknown_type $noCheck            
     */
    public function init_app() {
        
        // システム日時はここで
        $this->_system_datetime = time ();
        
        // APPのIDを取得
        $this->_app_id = $this->get_app_id ();
        
        define ( 'LOGIN_ACCOUNT_SESSION_NAME', $this->get_app_login_session_var () );
        
        // //SystemSetting情報を取得する。
        // $this->assignVars('system_setting', SystemSettingTable::getInstance()->getAll());
        
        // app名をアサイン
        // $this->assignVars ( '_app_name', ConstantsModel::$APP_NAME );
        session ()->put ( '_app_name', ConstantsModel::$APP_NAME );
        
        // APPのパスをアサイン
        $this->_app_path = $this->get_app_path ();
        session ()->put ( '_app_path', $this->_app_path );
        // $this->assignVars ( '_app_path', $this->_app_path );
    }
    
    // * APPのLOGINユーザーのSESSION変数名を返却する。URLの第１パスと違う場合はオーバーライドすること
    // */
    protected function get_app_login_session_var() {
        // return 'admin';
        return $this->get_app_id () . '.login';
    }
    protected function get_hierarchy_session_name() {
        return $this->get_app_id () . '.hierarchy';
    }
    
    /**
     * ログイン情報を取得する
     *
     * @param string $key            
     * @return string|array
     */
    protected function getLoginSession($key = null) {
        $session_name = $this->get_app_login_session_var ();
        
        // 2017-03-09 d.kieu check session
        // if (array_key_exists ( $session_name, session() )) {
        if (session ()->has ( $session_name )) {
            if (is_null ( $key )) {
                return session ( $session_name );
            }
            if (array_key_exists ( $key, session ( $session_name ) )) {
                return session ( $session_name ) [$key];
            }
        }
        return null;
    }
    
    /**
     * ソース直書から関数介してメッセージ取得
     *
     * @return session(school.lan) session(teacher.lan)| etc...
     */
    protected function set_language() {
        if (session ()->has ( $this->get_app_login_session_var () )) {
            
            // $lang_code : 1:英語,2:日本語
            // $resource_file : school,sport, etc...
            $lang_code = session ( $this->get_app_login_session_var () ) ['lang_code'];
            $resource_file = session ( $this->get_app_login_session_var () ) ['resource_file'];
            $pschool_id = session ( $this->get_app_login_session_var () ) ['id'];
            
            // $locale : "en", "jp", "vn", etc
            $locale = ConstantsModel::$lang_setting [$lang_code];
            app ()->setLocale ( $locale );
            try {
                $path = resource_path () . '/lang/' . $locale . '/'; // resources/lang/jp, resources/lang/en

                if (!File::exists ( $path . $resource_file . '.php' )) {

                    $resource_file = 'common';
                }
                
                $content = File::getRequire ( $path . $resource_file . '.php' );
                
                // Add left_panel : screen file use
                $left_content = array ();
                $file_menu = $path . 'left_panel.php';
                if (File::exists ( $file_menu )) {
                    $menu_content = File::getRequire ( $file_menu );

                    $content [$this->_id . '.left_panel'] = $menu_content;
                } 
                // add menu list into message file
                // $file_menu : ../resources/lang/jp/menu_a5e00132373a7031000fd987a3c9f87b.php
                // $menu_content = array ();
                // $file_menu = $path . 'menu_' . md5 ( $pschool_id ) . '.php';
                // if (File::exists ( $file_menu )) {
                //     $menu_content = File::getRequire ( $file_menu );
                // } else {
                //     // get menu list from master_menu file
                //     // $file_menu : ../resources/lang/jp/menu.php
                //     $file_menu = $path . 'menu.php';
                //     if (File::exists ( $file_menu )) {
                //         $menu_content = File::getRequire ( $file_menu );
                //     }
                // }
                // $content [$this->_id . '.menu'] = $menu_content;
                
                session ( [ 
                        $this->_id . '.lan' => $content 
                ] );
            } catch ( Illuminate\Filesystem\FileNotFoundException $ex ) {
                echo "The file doesn't exist";
            }
        }
    }
}
