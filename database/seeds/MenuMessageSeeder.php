<?php

use Illuminate\Database\Seeder;
use App\Message;

class MenuMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.menu',
                'screen_value'      => 'メニュー',
                'message_key'       => 'menu_deposit',
                'message_value'     => '入金管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_deposit',
            ])->first();
            $message->message_value = '入金処理';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_members',
            ])->first();
            $message->message_value = '会員情報';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_billing_contacts',
            ])->first();
            $message->message_value = '請求先情報';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_coaches',
            ])->first();
            $message->message_value = '講師情報';
            $message->save();
        } catch (\Exception $e) {

        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.menu',
                'screen_value'      => 'メニュー',
                'message_key'       => 'menu_deposit',
                'message_value'     => '入金管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_deposit',
            ])->first();
            $message->message_value = '入金処理';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_members',
            ])->first();
            $message->message_value = '会員情報';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_billing_contacts',
            ])->first();
            $message->message_value = '請求先情報';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.menu',
                'message_key' => 'menu_coaches',
            ])->first();
            $message->message_value = '講師情報';
            $message->save();
        } catch (\Exception $e) {

        }
    }
}
