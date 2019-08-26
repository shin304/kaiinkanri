<?php

namespace App\Http\Middleware;

use App\ConstantsModel;
use Closure;

class MenuAuthHandle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $_app_path = $request->segment(1); // Ex : school
        $_action = $request->segment(2); // Ex: student

        $menu_auth = session('menu_auth');
        if (!is_null($menu_auth)) {
            // $menu_auth Ex : [ 'student'=> ['viewable'=> 1, 'editable'=>1], 'parent'=>['viewable'=> 1, 'editable'=>0], ...]
            if (array_key_exists($_action, $menu_auth)) {
                view()->share('edit_auth', $menu_auth[$_action]["editable"]);
                // put session to store parent action for label, mail_message
                session()->put($_app_path.'.prev_action', $_action);

                if ($menu_auth[$_action]["editable"] == 0 && !is_null($request->segment(3))) {
                    // check if edit link => redirect to homepage
                    if (in_array($request->segment(3), ConstantsModel::$edit_auth_datas)) {
                        return redirect()->to('school/home');
                    }
                }
            } else {
                $_prev_action = session($_app_path.'.prev_action');
                if (array_key_exists($_prev_action, $menu_auth)) {
                    view()->share('edit_auth', $menu_auth[$_prev_action]["editable"]);
                }
            }

        }
        return $next($request);
    }
}
