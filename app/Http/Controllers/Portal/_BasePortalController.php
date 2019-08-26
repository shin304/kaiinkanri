<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\_BaseAppController;


class _BasePortalController extends _BaseAppController
{
    public function __construct() {
        parent::__construct ();
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
}
