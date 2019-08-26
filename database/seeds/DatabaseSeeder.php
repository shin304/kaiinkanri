<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->call(AppSubjectTableSeeder::class);
    	$this->call(AppWorkbookTypeTableSeeder::class);
    	$this->call(AppNewsTypeTableSeeder::class);
    }
}
