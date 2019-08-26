<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Message extends Model
{
    use Translatable;

    protected $translatable = ['name'];

    protected $table = 'message';

    protected $fillable = ['slug', 'name'];
}
