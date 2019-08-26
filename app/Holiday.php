<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Holiday extends Model {
    use Translatable;
    protected $translatable = [ 
            'name' 
    ];
    protected $table = 'holiday';
    protected $fillable = [ 
            'slug',
            'name' 
    ];
}
