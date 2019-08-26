<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentPersonInChargeTable extends DbModel
{
    /**
     *
     * @var StudentPersonInChargeTable
     */
    private static $_instance = null;
    protected $table = 'student_person_in_charge';
    
    /**
     *
     * @return StudentPersonInChargeTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new StudentPersonInChargeTable ();
        }
        return self::$_instance;
    }
}
