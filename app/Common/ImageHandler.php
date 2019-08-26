<?php

namespace App\Common;

class ImageHandler {

    public static function loadImage($img_path) {
        $type = exif_imagetype($img_path);
        $allowed_types = array(
            1,  // [] gif
            2,  // [] jpg
            3,  // [] png
        );
        if (!in_array($type, $allowed_types)) {
            return false;
        }

        try {
            switch ($type) {
                case 1 :
                    $img = imageCreateFromGif($img_path);
                    // Set the content type header - in this case image/gif
                    header('Content-Type: image/gif');
                    imagegif($img);
                    // Free up memory
                    imagedestroy($img);
                    break;
                case 2 :
                    $img = imageCreateFromJpeg($img_path);
                    // Set the content type header - in this case image/jpg
                    header('Content-Type: image/jpeg');
                    imagejpeg($img);
                    // Free up memory
                    imagedestroy($img);
                    break;
                case 3 :
                    $img = imageCreateFromPng($img_path);
                    // Set the content type header - in this case image/png
                    header('Content-Type: image/png');
                    imagepng($img);
                    // Free up memory
                    imagedestroy($img);
                    break;
                default:
                    // Set the content type header - in this case image/jpeg
                    $img = imageCreateFromPng(Constants::DEFAULT_PROFILE_IMG);
                    header('Content-Type: image/png');
                    imagepng($img);
                    // Free up memory
                    imagedestroy($img);
                    break;
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}