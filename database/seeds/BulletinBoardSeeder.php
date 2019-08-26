<?php

use Illuminate\Database\Seeder;
use App\Message;

class BulletinBoardSeeder extends Seeder
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

    // thangqg : 2017-06-17
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'please_input_title',
                'message_value'     => 'を入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'download_file_title',
                'message_value'     => 'ダウンロードファイル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'save_confirm_content_title',
                'message_value'     => '設定内容を登録します。 よろしいですか？',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.bulletin_board',
                'message_key' => 'file_delete_content_title',
            ])->first();
            $message->message_value = '添付ファイルを削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.bulletin_board',
                'message_key' => 'delete_confirm_title',
            ])->first();
            $message->message_value = '対象を削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
    // end -- thangqg : 2017-06-17

        // ===========================
        // message_file_id = 2 : en
        // ===========================

    // thangqg : 2017-06-17
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'please_input_title',
                'message_value'     => 'を入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'download_file_title',
                'message_value'     => 'ダウンロードファイル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'save_confirm_content_title',
                'message_value'     => '設定内容を登録します。 よろしいですか？',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.bulletin_board',
                'message_key' => 'file_delete_content_title',
            ])->first();
            $message->message_value = '添付ファイルを削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.bulletin_board',
                'message_key' => 'delete_confirm_title',
            ])->first();
            $message->message_value = '対象を削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
    // end -- thangqg : 2017-06-17
    // update -- thangqg : 2017-09-19
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'period_title',
                'message_value'     => '掲載期間',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.bulletin_board',
                'screen_value'      => '掲示板管理',
                'message_key'       => 'period_title',
                'message_value'     => '掲載期間',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    // end -- update -- thangqg : 2017-09-19
    // update -- thangqg : 2017-09-28
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.bulletin_board',
                'message_key'       => 'title_require',
            ])->first();
            $message->message_value = 'タイトルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.bulletin_board',
                'message_key'       => 'start_date_require',
            ])->first();
            $message->message_value = '掲載開始日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.bulletin_board',
                'message_key'       => 'mandatory_items_title',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.bulletin_board',
                'message_key'       => 'title_require',
            ])->first();
            $message->message_value = 'タイトルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.bulletin_board',
                'message_key'       => 'start_date_require',
            ])->first();
            $message->message_value = '掲載開始日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.bulletin_board',
                'message_key'       => 'mandatory_items_title',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
    // end -- update -- thangqg : 2017-09-28
    // add student parent
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.bulletin_board',
                    'screen_value'      => '掲示板管理',
                    'message_key'       => 'parent_title',
                    'message_value'     => '請求先',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.bulletin_board',
                    'screen_value'      => '掲示板管理',
                    'message_key'       => 'parent_title',
                    'message_value'     => '請求先',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.bulletin_board',
                    'screen_value'      => '掲示板管理',
                    'message_key'       => 'student_title',
                    'message_value'     => '会員',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.bulletin_board',
                    'screen_value'      => '掲示板管理',
                    'message_key'       => 'student_title',
                    'message_value'     => '会員',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.bulletin_board',
                    'screen_value'      => '掲示板管理',
                    'message_key'       => 'register_btn_title',
                    'message_value'     => '掲示板登録',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.bulletin_board',
                    'screen_value'      => '掲示板管理',
                    'message_key'       => 'register_btn_title',
                    'message_value'     => '掲示板登録',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    }
}
