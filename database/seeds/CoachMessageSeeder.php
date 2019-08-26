<?php

use Illuminate\Database\Seeder;
use App\Message;

class CoachMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tung Nguyen : 2017-06-12 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.coach',
                'message_key' => 'main_title',
            ])->first();
            $message->message_value = '講師情報';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'coach_name_kana_title',
                'message_value'     => '講師名（フリガナ）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'last_education_background_title',
            ])->first();
            $message->message_value = '最終学歴';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'address_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'building_title',
                'message_value'     => 'ビル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.coach',
                'message_key' => 'main_title',
            ])->first();
            $message->message_value = '講師情報';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'coach_name_kana_title',
                'message_value'     => '講師名（フリガナ）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-19 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'message_save_confirm',
                'message_value'     => '保存しますが、よろしいでしょうか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.coach',
                'message_key' => 'message_save_confirm',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'message_save_confirm',
                'message_value'     => '保存しますが、よろしいでしょうか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.coach',
                'message_key' => 'message_save_confirm',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        // Tung Nguyen : 2017-06-19 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'invalid_email_format_msg',
                'message_value'     => 'メールアドレスは正しくフォーマットで入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'invalid_email_format_msg',
                'message_value'     => 'メールアドレスは正しくフォーマットで入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-20 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'previous_text',
                'message_value'     => '前へ。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'previous_text',
            ])->first();
            $message->message_value = '前へ';
            $message->save();
        } catch (\Exception $e) {
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'next_text',
                'message_value'     => '次へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'previous_text',
                'message_value'     => '前へ。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'previous_text',
            ])->first();
            $message->message_value = '前へ';
            $message->save();
        } catch (\Exception $e) {
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'next_text',
                'message_value'     => '次へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'name_search_title',
                'message_value'     => '講師名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'name_search_title',
                'message_value'     => '講師名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'name_search_placeholder',
                'message_value'     => '講師氏名の一部を漢字・カナで入力します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'name_search_placeholder',
                'message_value'     => '講師氏名の一部を漢字・カナで入力します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'menu_structure',
                'message_value'     => 'メニュー構造',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'menu_structure',
                'message_value'     => 'メニュー構造',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'preview',
                'message_value'     => 'プレビュー',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'preview',
                'message_value'     => 'プレビュー',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'btn_back',
                'message_value'     => '戻る',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'btn_back',
                'message_value'     => '戻る',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'edit_success',
                'message_value'     => '更新されました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'edit_success',
                'message_value'     => '更新されました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'register_success',
                'message_value'     => '登録されました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'register_success',
                'message_value'     => '登録されました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'upload_max_size_error_msg',
                'message_value'     => 'ファイルサイズは%s以下でアップロードしてください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'upload_max_size_error_msg',
                'message_value'     => 'ファイルサイズは%s以下でアップロードしてください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'upload_file_error_msg',
                'message_value'     => 'ファイルアップロードのエラーが発生しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'upload_file_error_msg',
                'message_value'     => 'ファイルアップロードのエラーが発生しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'login_permission_alert',
                'message_value'     => 'ログイン情報が取れないので、ログインしてください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'login_permission_alert',
                'message_value'     => 'ログイン情報が取れないので、ログインしてください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Tung Nguyen : 2017-06-21 講師管理
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'placeholder_please_input',
                'message_value'     => 'を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'placeholder_please_input',
                'message_value'     => 'を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-07-12 update message
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.coach',
                'message_key' => 'name_search_title',
            ])->first();
            $message->message_value = '講師名';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.coach',
                'message_key' => 'name_search_title',
            ])->first();
            $message->message_value = '講師名';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.coach',
                'message_key' => 'name_search_placeholder',
            ])->first();
            $message->message_value = '講師名の一部（漢字・カナ）を入力';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.coach',
                'message_key' => 'name_search_placeholder',
            ])->first();
            $message->message_value = '講師名の一部（漢字・カナ）を入力';
            $message->save();
        } catch (\Exception $e) {

        }
        // Toran add for index view
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'category_title',
                    'message_value'     => 'カテゴリ',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'category_title',
                    'message_value'     => 'カテゴリ',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'name_title_2',
                    'message_value'     => '名称',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'name_title_2',
                    'message_value'     => '名称',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'start_date_title',
                    'message_value'     => '開催開始日時',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'start_date_title',
                    'message_value'     => '開催開始日時',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'course_title',
                    'message_value'     => 'イベント',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'course_title',
                    'message_value'     => 'イベント',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'class_title',
                    'message_value'     => 'プラン',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'class_title',
                    'message_value'     => 'プラン',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'program_title',
                    'message_value'     => 'プログラム',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'program_title',
                    'message_value'     => 'プログラム',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'last_education_background_title',
            ])->first();
            $message->message_value = '最終学歴';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'address_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'building_title',
                'message_value'     => 'ビル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
// update - Thangqg 2017/09/28
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_name_msg',
            ])->first();
            $message->message_value = '名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_furigana_msg',
            ])->first();
            $message->message_value = 'フリガナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_mail_msg',
            ])->first();
            $message->message_value = 'メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_password_msg',
            ])->first();
            $message->message_value = 'パスワードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_text_explain_title',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
    // message_file_id = 2
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_name_msg',
            ])->first();
            $message->message_value = '名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_furigana_msg',
            ])->first();
            $message->message_value = 'フリガナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_mail_msg',
            ])->first();
            $message->message_value = 'メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_password_msg',
            ])->first();
            $message->message_value = 'パスワードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'message_key'       => 'required_text_explain_title',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
// update - Thangqg 2017/09/28
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'not_show_passed_event_title',
                'message_value'     => '過去分を非表示',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.coach',
                'screen_value'      => '講師管理',
                'message_key'       => 'not_show_passed_event_title',
                'message_value'     => '過去分を非表示',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'generate_address',
                    'message_value'     => '〒→住所変換',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'generate_address',
                    'message_value'     => '〒→住所変換',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'close_date_title',
                    'message_value'     => '終了日時',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'close_date_title',
                    'message_value'     => '終了日時',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'password_warning_title',
                    'message_value'     => '半角英数文字または特殊文字(-,_,.,$,#,:@,!)で8文字以上16文字以下',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'password_warning_title',
                    'message_value'     => '半角英数文字または特殊文字(-,_,.,$,#,:@,!)で8文字以上16文字以下',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'over_length_password_msg',
                    'message_value'     => 'パスワードは16文字以内入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'over_length_password_msg',
                    'message_value'     => 'パスワードは16文字以内入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'password_regex_msg',
                    'message_value'     => 'パスワード は半角英数文字または特殊文字(-,_,.,$,#,:@,!)。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.coach',
                    'screen_value'      => '講師管理',
                    'message_key'       => 'password_regex_msg',
                    'message_value'     => 'パスワード は半角英数文字または特殊文字(-,_,.,$,#,:@,!)。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    }
}
