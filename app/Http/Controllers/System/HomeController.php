<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends _BaseSystemController {
    public function index() {
        return view ( 'System.Home.index' );
    }
}
