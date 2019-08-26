<?php

use Illuminate\Database\Seeder;
use App\MasterMenu;

class MasterMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = MasterMenu::firstOrNew(['menu_name_key' => 'menu_home']);
        if (!$menu->exists) {
            $menu->fill([
                    'menu_path' 	=> '1/',
                    'action_url' 	=> '',
                    'editable' 		=> 0,
                    'register_admin'=> 1,
                    'default_flag'  => 1,
                    'register_date'	=> date('Y-m-d h:i:s'),
                ])->save();
        }

        $menu = MasterMenu::firstOrNew(['menu_name_key' => 'menu_logout']);
        if (!$menu->exists) {
            $menu->fill([
                    'menu_path' 	=> '2/',
                    'action_url' 	=> 'logout',
                    'editable' 		=> 1,
                    'register_admin'=> 1,
                    'default_flag'  => 1,
                    'register_date'	=> date('Y-m-d h:i:s'),
                ])->save();
        }

        $menu = MasterMenu::firstOrNew(['menu_name_key' => 'menu_basic_info']);
        if (!$menu->exists) {
            $menu->fill([
                    'menu_path' 	=> '3/',
                    'action_url' 	=> 'basic_info',
                    'editable' 		=> 1,
                    'register_admin'=> 1,
                    'default_flag'  => 1,
                    'register_date'	=> date('Y-m-d h:i:s'),
                ])->save();
        }
    }
}
