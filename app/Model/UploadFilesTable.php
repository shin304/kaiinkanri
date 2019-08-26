<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UploadFilesTable extends DbModel
{
    /**
     *
     * @var UploadFilesTable
     */
    private static $_instance = null;
    protected $table = 'upload_files';
    
    /**
     *
     * @return UploadFilesTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new UploadFilesTable ();
        }
        return self::$_instance;
    }
}
