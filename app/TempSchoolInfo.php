<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class TempSchoolInfo extends Model
{
    use Translatable;
    protected $translatable = [
            'name'
    ];
    protected $table = 'temp_school_info';
    protected $fillable = [
            'slug',
            'name'
    ];
}
