<?php

namespace App\Model;

use App\ConstantsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdditionalCategoryRelTable extends DbModel {
    /**
     *
     * @var AdditionalCategoryTable
     */
    private static $_instance = null;
    protected $table = 'additional_category_rel';

    /**
     *
     * @return AdditionalCategoryTable
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new AdditionalCategoryRelTable();
        }
        return self::$_instance;
    }
}
