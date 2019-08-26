<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\PschoolTable;
use App\Model\StaffTable;
use App\Model\CoachTable;
use App\Http\Controllers\Common\_BaseAppController;
use Illuminate\Http\Request;

class AuthenticationHandle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $_app_path;
    public function handle($request, Closure $next)
    {
    	$this->_app_path = $request->segment(1);
        $_action = $request->segment(2);

        $login_session_name = $this->get_app_login_session_var();
        $loginAdmin = session($login_session_name);

        if ($request->path() == 'school' || $request->path() == 'school/login'){
            return $next($request);
        } else if($this->_app_path == 'admin'){
            return $next($request);
        } else if($this->_app_path == 'api'){
            return $next($request);
        } else if($this->_app_path == 'appmanage'){
            return $next($request);
        } else if($this->_app_path == 'portal'){
            return $next($request);
        } else if ($this->_app_path == 'image') {
            return $next($request);
        } else if ($this->_app_path == 'home') {
            return $next($request);
        }

        if (empty($loginAdmin) && !empty($_action)) {
            return redirect($this->_app_path);
        } else if (!empty($loginAdmin)) {
            // 一応、再読み込み
            if (! isset ( $loginAdmin ['staff_id'] ) && ! isset ( $loginAdmin ['coach_id'] )) { // 塾管理者の場合
                $admin = PschoolTable::getInstance ()->loadWithLoginAccount ( $loginAdmin ['id'] );

                if (empty ( $admin )) {
                    // あなた、たぶん、登録抹消されてます。
                    return redirect($this->_app_path);

                } else if (isset ( $loginAdmin ['staff_id'] )) { // スタッフの場合
                    $admin = StaffTable::getInstance ()->loadWithLoginAccount ( $loginAdmin ['staff_id'] );
                    if (empty ( $admin )) {
                        // あなた、たぶん、登録抹消されてます。
                        return redirect($this->_app_path);

                    } else {
                        $pschool = PschoolTable::getInstance ()->getActiveRow ( array (
                            'id' => $admin ['pschool_id']
                        ) );
                        if (empty ( $pschool )) {
                            // そんな塾ありません
                            return redirect($this->_app_path);
                        }
                        $admin ['staff_id'] = $admin ['id'];
                        $admin ['id'] = $admin ['pschool_id'];
                        $admin ['name'] = $pschool ['name'];
                        $admin ['pschool_code'] = $pschool ['pschool_code'];
                    }
                } else if (isset ( $loginAdmin ['coach_id'] )) {
                    $admin = CoachTable::getInstance ()->loadWithLoginAccount ( $loginAdmin ['coach_id'] );
                    if (empty ( $admin )) {
                        // あなた、たぶん、登録抹消されてます。
                        return redirect($this->_app_path);
                    } else {
                        $pschool = PschoolTable::getInstance ()->getActiveRow ( array (
                            'id' => $admin ['pschool_id']
                        ) );
                        if (empty ( $pschool )) {
                            // そんな塾ありません
                            return redirect($this->_app_path);
                        }
                        $admin ['coach_id'] = $admin ['id'];
                        $admin ['id'] = $admin ['pschool_id'];
                        $admin ['name'] = $pschool ['name'];
                        $admin ['pschool_code'] = $pschool ['pschool_code'];
                    }
                }
                $admin['lang_code'] = $loginAdmin['lang_code'];
                $admin['language'] = $loginAdmin['lang_code'];
                $admin['origin_id'] = $loginAdmin['origin_id'];
                session([$login_session_name => $admin]);
                view()->share('loginAdmin', $admin);
            }
            session(['login_account_id' => $loginAdmin['login_account_id']]);
        }
        return $next($request);
    }

    private function get_app_login_session_var()
    {
        return $this->_app_path . '.login';
    }
}
