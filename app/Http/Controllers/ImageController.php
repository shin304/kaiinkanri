<?php

namespace App\Http\Controllers;


use App\Common\Constants;
use Intervention\Image\ImageManager;

class ImageController extends Controller {
    public function display($image) {
        $manager = new ImageManager(['driver' => 'Imagick']);
        // TODO Firstly, need to check permissions and roles. At this project's scope, just only check login permission
        if(session()->has('school.login.id')) {
            $path = storage_path( '/app/uploads/school/'. session('school.login.id') .'/' . $image);
        } else { // If session login is null, return default image
            $path = public_path(Constants::DEFAULT_PROFILE_IMG);
        }
        return $manager->make($path)->response();
    }
}