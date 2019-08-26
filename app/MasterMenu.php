<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class MasterMenu extends Model
{
    use Translatable;

    protected $translatable = ['name'];

    protected $table = 'master_menu';

    protected $fillable = ['slug', 'name'];
}
