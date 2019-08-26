<?php

use Illuminate\Database\Seeder;
use App\MasterMenu;

class InitMenuIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insert new icon for master menu , base on icon on main title of screen
        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_home',
            ])->first();
            $menu->icon_url = 'fa fa-home';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_logout',
            ])->first();
            $menu->icon_url = 'fa fa-power-off';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_association_headquarters',
            ])->first();
            $menu->icon_url = 'fa fa-power-off';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_organization_chart',
            ])->first();
            $menu->icon_url = 'fa fa-book';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_notification',
            ])->first();
            $menu->icon_url = 'fa fa-book';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_basic_info',
            ])->first();
            $menu->icon_url = 'fa fa-university';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_classes',
            ])->first();
            $menu->icon_url = 'fa fa-book';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_events',
            ])->first();
            $menu->icon_url = 'fa fa-list-alt';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_programs',
            ])->first();
            $menu->icon_url = 'fa fa-bullhorn';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_mail_broadcast',
            ])->first();
            $menu->icon_url = 'fa fa-envelope-o';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_billing',
            ])->first();
            $menu->icon_url = 'fa fa-fax';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_statistics',
            ])->first();
            $menu->icon_url = 'fa fa-book';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_members',
            ])->first();
            $menu->icon_url = 'fa fa-group';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_billing_contacts',
            ])->first();
            $menu->icon_url = 'fa fa-user-secret';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_coaches',
            ])->first();
            $menu->icon_url = 'fa fa-black-tie';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_bulletin_board',
            ])->first();
            $menu->icon_url = 'fa fa-newspaper-o';
            $menu->save();
        } catch (\Exception $e) {

        }

        try {
            $menu = MasterMenu::where([
                'menu_name_key'   => 'menu_deposit',
            ])->first();
            $menu->icon_url = 'fa fa-yen';
            $menu->save();
        } catch (\Exception $e) {

        }
    }
}
