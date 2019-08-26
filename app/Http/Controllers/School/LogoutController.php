<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends _BaseSchoolController
{
    public function __construct(Request $request) {
        parent::__construct ();
    }

    public function execute() {
        session()->flush();
        return redirect($this->get_app_path());
    }
}
