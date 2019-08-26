<?php

use Illuminate\Database\Seeder;

class AppNewsTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate
    	DB::table('app_news_type')->truncate();
    	 
    	//data
    	$now = date('Y-m-d H:i:s');
    	$data_list = array(
    			array('title'=>'お知らせ', 'icon_type'=>1, 'active_flag'=>1, 'register_date'=>$now, 'register_admin'=>1),
    			array('title'=>'クラス', 'icon_type'=>2, 'active_flag'=>0, 'register_date'=>$now, 'register_admin'=>1),
    			array('title'=>'イベント', 'icon_type'=>3, 'active_flag'=>0, 'register_date'=>$now, 'register_admin'=>1),
    			array('title'=>'プログラム', 'icon_type'=>4, 'active_flag'=>0, 'register_date'=>$now, 'register_admin'=>1),
    	);
    	 
    	//insert
    	foreach ($data_list as $data) {
    		DB::table('app_news_type')->insert($data);
    	}
    }
}
