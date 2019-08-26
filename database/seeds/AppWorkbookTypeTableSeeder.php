<?php

use Illuminate\Database\Seeder;

class AppWorkbookTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//truncate
    	DB::table('app_workbook_type')->truncate();
    	 
    	//data
    	$now = date('Y-m-d H:i:s');
    	$data_list = array(
    			array('title'=>'高校受験', 'sequence_no'=>1, 'register_date'=>$now, 'register_admin'=>1),
    			array('title'=>'大学受験', 'sequence_no'=>2, 'register_date'=>$now, 'register_admin'=>1),
    			array('title'=>'社会人', 'sequence_no'=>3, 'register_date'=>$now, 'register_admin'=>1),
    	);
    	 
    	//insert
    	foreach ($data_list as $data) {
    		DB::table('app_workbook_type')->insert($data);
    	}
        
    }
}
