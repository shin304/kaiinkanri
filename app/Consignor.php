<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Consignor extends Model
{
    use Translatable;
    protected $translatable = [ 
            'name' 
    ];
    protected $table = 'consignor';
    protected $fillable = [ 
            'slug',
            'name' 
    ];
}
