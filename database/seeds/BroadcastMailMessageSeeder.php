<?php

use Illuminate\Database\Seeder;
use App\Message;

class BroadcastMailMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ===========================
        // message_file_id = 1 : jp ==
        // ===========================

        // 2017-07-11
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'msg_title_compulsory',
            ])->first();
            $message->message_value = 'タイトルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'msg_contents_compulsory',
            ])->first();
            $message->message_value = '内容が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'required_broadcast_targer_op_error_title',
            ])->first();
            $message->message_value = '送信対象オプションが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'required_title_error_title',
            ])->first();
            $message->message_value = 'タイトルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'required_subject_error_title',
            ])->first();
            $message->message_value = '内容が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.broadcast_mail',
                'screen_value'      => 'お知らせ送信',
                'message_key'       => 'input_search_student_name_title',
                'message_value'     => '会員の氏名の一部（漢字・カナ）を入力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'status_title',
                    'message_value'     => 'ステータス',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'booking_time_send_title',
                    'message_value'     => '送信予約',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'member_title',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'withdrawal_members',
                    'message_value'     => '退会会員',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'placeholder_input_temp',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_title_title',
                    'message_value'     => 'メールタイトル',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_content_title',
                    'message_value'     => '本部',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_footer_title',
                    'message_value'     => 'メールフッター',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'sent_date_title',
                    'message_value'     => '送信日付',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'sent_date_title',
            ])->first();
            $message->message_value = '送信日時';
            $message->save();
        } catch (\Exception $e) {

        }
        //2017-07-12
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'teacher_title',
                    'message_value'     => '講師',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'staff_title',
                    'message_value'     => 'スタッフ',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // mail template message
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_name_title',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_type',
                    'message_value'     => '分類',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_main_title',
                    'message_value'     => 'メールテンプレート管理',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_create',
                    'message_value'     => 'メールテンプレートの作成',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'btn_list_mail_template',
                    'message_value'     => 'メールテンプレートのリート',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'btn_list_mail_template',
            ])->first();
            $message->message_value = 'テンプレート選択';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'mail_template_create',
            ])->first();
            $message->message_value = 'テンプレート登録';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_name',
                    'message_value'     => '名称を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_subject',
                    'message_value'     => '件名を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_body',
                    'message_value'     => '内容を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_footer',
                    'message_value'     => 'フッターを入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_option',
                    'message_value'     => '-- 分類をしてください --',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_data_empty',
                    'message_value'     => 'データがなし',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_error',
                    'message_value'     => 'エラーが発生しました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_success',
                    'message_value'     => 'メールテンプレートを作成しました。',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'state_title',
                    'message_value'     => '状態',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en ==
        // ===========================
        // 2017-07-11
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.broadcast_mail',
                'screen_value'      => 'お知らせ送信',
                'message_key'       => 'input_search_student_name_title',
                'message_value'     => '会員の氏名の一部（漢字・カナ）を入力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'status_title',
                    'message_value'     => 'ステータス',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'booking_time_send_title',
                    'message_value'     => '送信予約',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'member_title',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'withdrawal_members',
                    'message_value'     => '退会会員',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'placeholder_input_temp',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_title_title',
                    'message_value'     => 'メールタイトル',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_content_title',
                    'message_value'     => '本部',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_footer_title',
                    'message_value'     => 'メールフッター',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'sent_date_title',
                    'message_value'     => '送信日付',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'sent_date_title',
            ])->first();
            $message->message_value = '送信日時';
            $message->save();
        } catch (\Exception $e) {

        }
        //2017-07-12
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'teacher_title',
                    'message_value'     => '講師',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'staff_title',
                    'message_value'     => 'スタッフ',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // mail template message
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_name_title',
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
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_type',
                    'message_value'     => '分類',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_main_title',
                    'message_value'     => 'メールテンプレート管理',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_create',
                    'message_value'     => 'メールテンプレートの作成',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'btn_list_mail_template',
                    'message_value'     => 'メールテンプレートのリート',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'btn_list_mail_template',
            ])->first();
            $message->message_value = 'テンプレート選択';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'mail_template_create',
            ])->first();
            $message->message_value = 'テンプレート登録';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_name',
                    'message_value'     => '名称を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_subject',
                    'message_value'     => '件名を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_body',
                    'message_value'     => '内容を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_required_footer',
                    'message_value'     => 'フッターを入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_option',
                    'message_value'     => '-- 分類をしてください --',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_data_empty',
                    'message_value'     => 'データがなし',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_error',
                    'message_value'     => 'エラーが発生しました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'mail_template_success',
                    'message_value'     => 'メールテンプレートを作成しました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // uppdate body title Toran 2017-07-17
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.broadcast_mail',
                    'message_key' => 'mail_content_title',
            ])->first();
            $message->message_value = '本文';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.broadcast_mail',
                    'message_key' => 'mail_content_title',
            ])->first();
            $message->message_value = '本文';
            $message->save();
        } catch (\Exception $e) {

        }
        //
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'state_title',
                    'message_value'     => '状態',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'msg_title_compulsory',
            ])->first();
            $message->message_value = 'タイトルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'msg_contents_compulsory',
            ])->first();
            $message->message_value = '内容が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'required_broadcast_targer_op_error_title',
            ])->first();
            $message->message_value = '送信対象オプションが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'required_title_error_title',
            ])->first();
            $message->message_value = 'タイトルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.broadcast_mail',
                'message_key' => 'required_subject_error_title',
            ])->first();
            $message->message_value = '内容が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        //
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'add_file_title',
                    'message_value'     => '追加',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'add_file_title',
                    'message_value'     => '追加',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'valid_date_title',
                    'message_value'     => '有効期限',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'valid_date_title',
                    'message_value'     => '有効期限',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'register_btn_title',
                    'message_value'     => 'お知らせ登録',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'register_btn_title',
                    'message_value'     => 'お知らせ登録',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'attachment_title',
                    'message_value'     => '添付ファイル',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.broadcast_mail',
                    'screen_value'      => 'お知らせ送信',
                    'message_key'       => 'attachment_title',
                    'message_value'     => '添付ファイル',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.broadcast_mail',
                    'message_key' => 'valid_date_title',
            ])->first();
            $message->message_value = '会員有効期限';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.broadcast_mail',
                    'message_key' => 'valid_date_title',
            ])->first();
            $message->message_value = '会員有効期限';
            $message->save();
        } catch (\Exception $e) {

        }
    }
}
