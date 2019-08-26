<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class MessageFile extends Model
{
    use Translatable;

    protected $translatable = ['name'];

    protected $table = 'message_file';

    protected $fillable = ['slug', 'name'];
}
