<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\_BaseAppController;

class _BaseSystemController extends _BaseAppController {
    public function __construct() {
        parent::__construct ();
        
        $this->authenticate ();
    }
    public function authenticate() {
        $loginAdmin = $this->getLoginSession ();
        if (empty ( $loginAdmin )) {
            // あなた入っちゃだめです。
            return redirect ( $this->get_app_id () );
        }
        
        // 一応、再読み込み
        $admin = AdministratorTable::getInstance ()->loadWithLoginAccount ( $loginAdmin ['user_id'] );
        if (empty ( $admin )) {
            // あなた、たぶん、登録抹消されてます。
            return redirect ( $this->get_app_id () );
        }
        
        $loginAdmin ['authority'] = $admin ['authority'];
        $loginAdmin ['edit_authority'] = $admin ['edit_authority'];
        
        $session_name = $this->get_app_login_session_var ();
        session ()->flash ( $session_name, $loginAdmin );
        
        $this->_loginAdmin = $admin;
        view ()->share ( 'loginAdmin', $admin );
    }
}
