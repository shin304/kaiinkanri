<?php

namespace App\Model;


class ClassPaymentScheduleTable extends DbModel
{
    /**
     *
     * @var ClassPaymentScheduleTable
     */
    private static $_instance = null;
    protected $table = 'class_payment_schedule';
    public $timestamps = false;

    /**
     *
     * @return ClassPaymentScheduleTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new ClassPaymentScheduleTable ();
        }
        return self::$_instance;
    }

}
