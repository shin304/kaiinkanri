<?php

use Illuminate\Database\Seeder;

class BroadcastMailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table ('broadcast_mail')->truncate();
        DB::table ('mail_message')->where('type',6)->delete();
    }
}
