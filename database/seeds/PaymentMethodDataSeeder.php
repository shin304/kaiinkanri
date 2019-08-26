<?php

use Illuminate\Database\Seeder;

class PaymentMethodDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table ('payment_method_data')->truncate();
        } catch (\Exception $e) {

        }
    }
}
