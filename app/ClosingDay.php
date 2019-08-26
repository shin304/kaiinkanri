<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class ClosingDay extends Model
{
    use Translatable;
    protected $translatable = [ 
            'name' 
    ];
    protected $table = 'closing_day';
    protected $fillable = [ 
            'slug',
            'name' 
    ];
}
