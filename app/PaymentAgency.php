<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class PaymentAgency extends Model
{
    use Translatable;

    protected $translatable = ['name'];

    protected $table = 'payment_agency';

    protected $fillable = ['slug', 'name'];
}
