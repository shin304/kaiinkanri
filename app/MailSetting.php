<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class MailSetting extends Model
{
    use Translatable;
    protected $translatable = [
        'name'
    ];
    protected $table = 'mail_setting';
    protected $fillable = [
        'slug',
        'name'
    ];
}
