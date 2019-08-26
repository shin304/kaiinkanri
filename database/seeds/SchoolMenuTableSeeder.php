<?php

use Illuminate\Database\Seeder;
use App\Model\SchoolMenuTable;


class SchoolMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = 'UPDATE school_menu SET master_menu_id = IF(default_menu_id is null, 0, default_menu_id) WHERE master_menu_id = 0';
        try {
            SchoolMenuTable::getInstance()->execute($sql);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            throw new Exception("Error Processing When Update master_menu_id", 1);
            
        }
    }
}
