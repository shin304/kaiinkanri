<?php
/**
 * Created by PhpStorm.
 * User: ティエン
 * Date: 2017-06-15
 * Time: 5:36 PM
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistoryLogTable extends DbModel {
    /**
     *
     * @var StudentTable
     */
    private static $_instance = null;

    /**
     *
     * @return StudentTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new HistoryLogTable ();
        }
        return self::$_instance;
    }
    protected $table = 'history_log';
    public $timestamps = false;
}