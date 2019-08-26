<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class SystemSetting extends Model
{
    use Translatable;

    protected $translatable = ['name'];

    protected $table = 'system_setting';

    protected $fillable = ['slug', 'name'];
}
