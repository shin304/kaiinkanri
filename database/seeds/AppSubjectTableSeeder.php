<?php

use Illuminate\Database\Seeder;

class AppSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate
    	DB::table('app_subject')->truncate();
    	
    	//data
    	$now = date('Y-m-d H:i:s');
    	$data_list = array(
    		array('name'=>'国語', 'sequence_no'=>1, 's_subject_id'=>1, 'register_date'=>$now, 'register_admin'=>1),	
    		array('name'=>'算数', 'sequence_no'=>2, 's_subject_id'=>2, 'register_date'=>$now, 'register_admin'=>1),	
    		array('name'=>'理科', 'sequence_no'=>3, 's_subject_id'=>3, 'register_date'=>$now, 'register_admin'=>1),	
    		array('name'=>'社会', 'sequence_no'=>4, 's_subject_id'=>4, 'register_date'=>$now, 'register_admin'=>1),	
    		array('name'=>'英語', 'sequence_no'=>5, 's_subject_id'=>5, 'register_date'=>$now, 'register_admin'=>1),	
    	);
    	
    	//insert
    	foreach ($data_list as $data) {
    		DB::table('app_subject')->insert($data);
    	}
    	
    }
}
