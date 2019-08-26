<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class PaymentMethodTotalSeeder extends Seeder
{
    use Seedable;
    protected $seedersPath = __DIR__.'/';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed(PaymentMeThodSeeder::class);
        $this->seed(PaymentMethodSettingSeeder::class);
        $this->seed(PaymentMethodValidationSeeder::class);
//        $this->seed(PaymentMethodDataSeeder::class);
    }
}
