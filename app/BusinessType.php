<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class BusinessType extends Model {
    use Translatable;
    protected $translatable = [ 
            'name' 
    ];
    protected $table = 'business_type';
    protected $fillable = [ 
            'slug',
            'name' 
    ];
}
