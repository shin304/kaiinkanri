<?php

use Illuminate\Database\Seeder;
use App\Message;

class MailMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // kieudtd : 2017-06-08 
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.mail_message',
                'message_key' => 'only_few_seat_title',
            ])->first();
            $message->message_value = '会員定員は%d席しか残っていません。確認してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'ttl_fee_type',
                'message_value'     => '受講料種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'register_title',
                'message_value'     => '登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'member_select_title',
                'message_value'     => '会員選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'lesson_title',
                'message_value'     => 'カリキュラム',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'class_list_title',
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
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'main_program_title',
                'message_value'     => 'プログラム管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'program_code_title',
                'message_value'     => 'プログラムコード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'event_code_title',
                'message_value'     => 'イベントコード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'description_send_mail_schedule',
                'message_value'     => '設定された日時に自動的にメールを送信します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'person_in_charge_title',
                'message_value'     => '担当者',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'start_date_time',
                'message_value'     => '開始日時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'end_date_time',
                'message_value'     => '終了日時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'recruitment_start_title',
                'message_value'     => '募集開始日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'recruitment_deadline_title',
                'message_value'     => '募集締切日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'event_deadline_title',
                'message_value'     => '開催日時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'event_location_title',
                'message_value'     => '開催場所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'number_member_confirm_title',
                'message_value'     => '人参加',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'join_confirm_title',
                'message_value'     => '人参加します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'schedule_date_required',
                'message_value'     => '送信日時は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_title',
                'message_value'     => '会員種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_category_person_title',
                'message_value'     => '個人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_category_corporate_title',
                'message_value'     => '法人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_1',
                'message_value'     => '内部生',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_2',
                'message_value'     => '外部生',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_3',
                'message_value'     => '見込生上記以外',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_4',
                'message_value'     => '個人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_5',
                'message_value'     => '法人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'edit_page_title',
                'message_value'     => '編集画面',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.mail_message',
                'message_key' => 'edit_page_title',
            ])->first();
            $message->message_value = '編集';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.mail_message',
                    'message_key' => 'tuition_type_rates',
            ])->first();
            $message->message_value = '受講料種別 | 料金（円）  太字はメール送信済み';
            $message->save();
        } catch (\Exception $e) {

        }

        // ===========================
        // message_file_id = 2 : en
        // ===========================
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.mail_message',
                'message_key' => 'only_few_seat_title',
            ])->first();
            $message->message_value = '会員定員は%d席しか残っていません。確認してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'ttl_fee_type',
                'message_value'     => '受講料種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'register_title',
                'message_value'     => '登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'member_select_title',
                'message_value'     => '会員選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'lesson_title',
                'message_value'     => 'カリキュラム',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'class_list_title',
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
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'main_program_title',
                'message_value'     => 'プログラム管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'program_code_title',
                'message_value'     => 'プログラムコード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'event_code_title',
                'message_value'     => 'イベントコード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'description_send_mail_schedule',
                'message_value'     => '設定された日時に自動的にメールを送信します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'person_in_charge_title',
                'message_value'     => '担当者',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'start_date_time',
                'message_value'     => '開始日時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'end_date_time',
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
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'recruitment_start_title',
                'message_value'     => '募集開始日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'recruitment_deadline_title',
                'message_value'     => '募集締切日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'event_deadline_title',
                'message_value'     => '開催日時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'event_location_title',
                'message_value'     => '開催場所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'number_member_confirm_title',
                'message_value'     => '人参加',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'join_confirm_title',
                'message_value'     => '人参加します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'schedule_date_required',
                'message_value'     => '送信日時は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_title',
                'message_value'     => '会員種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_category_person_title',
                'message_value'     => '個人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_category_corporate_title',
                'message_value'     => '法人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_1',
                'message_value'     => '内部生',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_2',
                'message_value'     => '外部生',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_3',
                'message_value'     => '見込生上記以外',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_4',
                'message_value'     => '個人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'student_type_5',
                'message_value'     => '法人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'screen_value'      => '送信メール',
                'message_key'       => 'edit_page_title',
                'message_value'     => '編集画面',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.mail_message',
                'message_key' => 'edit_page_title',
            ])->first();
            $message->message_value = '編集';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.mail_message',
                'message_key'       => 'schedule_date_required',
            ])->first();
            $message->message_value = '送信日時が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.mail_message',
                'message_key'       => 'schedule_date_required',
            ])->first();
            $message->message_value = '送信日時が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.mail_message',
                    'message_key' => 'tuition_type_rates',
            ])->first();
            $message->message_value = '受講料種別 | 料金（円）  太字はメール送信済み';
            $message->save();
        } catch (\Exception $e) {

        }
    }
}
