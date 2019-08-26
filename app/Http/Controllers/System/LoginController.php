<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdministratorTable;
use Validator;
use ConstantsModel;

class LoginController extends _BaseSystemController {
    public function __construct() {
        parent::__construct ();
    }
    public function index() {
        return view ( 'System.Login.index' );
    }
    public function login() {
        // エラー表示用の配列
        $rules = [ 
                'loginid' => 'required',
                'password' => 'required' 
        ];
        $messsages = array (
                'loginid.required' => 'ユーザー名またはパスワードが指定されていません。', // TODO get msg from resource files
                'password.required' => 'ユーザー名またはパスワードが指定されていません。' 
        );
        
        $validator = Validator::make ( request ()->all (), $rules, $messsages );
        
        if ($validator->fails ()) {
            return redirect ()->back ()->withInput ()->withErrors ( $validator->errors () );
        }
        
        if ($admin = $this->validate_admin ()) {
            return redirect ( '/system/home' );
        }
    }
    
    /**
     * Administratorテーブルで認証する
     * 
     * @return unknown_type
     */
    private function validate_admin() {
        
        // 該当のデータがadministratorテーブルに存在するか調べる。
        $admin = AdministratorTable::getInstance ()->getLoginInfo ( $_REQUEST ['loginid'], $_REQUEST ['password'] );
        if (empty ( $admin )) {
            return false;
        }
        
        // ログイン情報をセッションに保存
        $session = array ();
        $session ['login_id'] = $admin [0]->login_id;
        $session ['user_id'] = $admin [0]->id;
        $session ['user_name'] = $admin [0]->administrator_name;
        $session ['role'] = ConstantsModel::$roles ['SYSADMIN'];
        $session ['login_account_id'] = $admin [0]->login_account_id;
        
        session ( [ 
                'system.login' => $session 
        ] );
        
        return $admin;
    }
}
