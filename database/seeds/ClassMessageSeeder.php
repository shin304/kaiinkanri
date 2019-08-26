<?php

use Illuminate\Database\Seeder;
use App\Message;

class ClassMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // kieudtd : 2017-06-07 イベント再改修
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'per_month_title',
                'message_value'     => '／月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'per_year_title',
                'message_value'     => '／年',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'member_delete_title',
                'message_value'     => '会員削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'explain_notice_mail_title',
                'message_value'     => '※１ヶ月前に通知を送ります。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'input_fee_schedule_error',
                'message_value'     => '金額は数値を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'total_pay_title',
                'message_value'     => '支払総額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'notice_setting_payment_method',
                'message_value'     => '基本情報で支払方法を設定してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'add_member_confirm_title',
                'message_value'     => '%sプランに会員を追加します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.class',
                'message_key' => 'add_member_confirm_title',
            ])->first();
            $message->message_value = '会員を追加します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'delete_member_confirm_title',
                'message_value'     => '%sプランから会員を削除します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.class',
                'message_key' => 'delete_member_confirm_title',
            ])->first();
            $message->message_value = '会員を除外します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'setting_schedule_payment',
                'message_value'     => '支払情報設定',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'select_fee_title',
                'message_value'     => '料金選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'number_of_payment_title',
                'message_value'     => '支払回数',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'notice_mail_title',
                'message_value'     => '事前通知',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_method_select',
                'message_value'     => '支払方法選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'total_fee_schedule_title',
                'message_value'     => '総額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'pay_schedule_date',
                'message_value'     => '支払基準日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'schedule_fee_title',
                'message_value'     => '金額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_method_required_title',
                'message_value'     => '支払方法を選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'input_all_schedule_error',
                'message_value'     => '全部支払基準日と金額を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'total_fee_schedule_error',
                'message_value'     => '総額を正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'name_input_title',
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
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_unit_person_title',
                'message_value'     => '一人当たり',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_unit_everyone_title',
                'message_value'     => '全員で',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_type_title',
                'message_value'     => '支払方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'number_attempt_not_selected',
                'message_value'     => '%d番目の受講回数の単位が選択されていません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'class_fee_type_title',
                'message_value'     => '代金種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'tuition_type_required_title',
                'message_value'     => '代金種別は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'student_type_id_required_title',
                'message_value'     => '%d番目の会員種別が未入力です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'save_title',
                'message_value'     => '保存',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'save_success_title',
                'message_value'     => '保存されました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'next_text',
                'message_value'     => '次へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'previous_text',
                'message_value'     => '前へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.class',
                'message_key' => 'delete_title',
            ])->first();
            $message->message_value = '除外';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.class',
                'message_key' => 'save_confirm_content',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'confirm_content',
                'message_value'     => '設定内容を登録します。 よろしいですか？',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'start_date_title',
                    'message_value'     => '加入日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'end_date_title',
                    'message_value'     => '退出日',
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
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'per_month_title',
                'message_value'     => '／月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'per_year_title',
                'message_value'     => '／年',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'member_delete_title',
                'message_value'     => '会員削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'explain_notice_mail_title',
                'message_value'     => '※１ヶ月前に通知を送ります。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'input_fee_schedule_error',
                'message_value'     => '金額は数値を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'total_pay_title',
                'message_value'     => '支払総額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'notice_setting_payment_method',
                'message_value'     => '基本情報で支払方法を設定してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'add_member_confirm_title',
                'message_value'     => '%sプランに会員を追加します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.class',
                'message_key' => 'add_member_confirm_title',
            ])->first();
            $message->message_value = '会員を追加します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'delete_member_confirm_title',
                'message_value'     => '%sプランから会員を削除します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.class',
                'message_key' => 'delete_member_confirm_title',
            ])->first();
            $message->message_value = '会員を除外します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'setting_schedule_payment',
                'message_value'     => '支払情報設定',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'select_fee_title',
                'message_value'     => '料金選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'number_of_payment_title',
                'message_value'     => '支払回数',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'notice_mail_title',
                'message_value'     => '事前通知',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_method_select',
                'message_value'     => '支払方法選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'total_fee_schedule_title',
                'message_value'     => '総額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'pay_schedule_date',
                'message_value'     => '支払基準日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'schedule_fee_title',
                'message_value'     => '金額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_method_required_title',
                'message_value'     => '支払方法を選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'input_all_schedule_error',
                'message_value'     => '全部支払基準日と金額を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'total_fee_schedule_error',
                'message_value'     => '総額を正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'name_input_title',
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
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_unit_person_title',
                'message_value'     => '一人当たり',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_unit_everyone_title',
                'message_value'     => '全員で',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'payment_type_title',
                'message_value'     => '支払方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'number_attempt_not_selected',
                'message_value'     => '%d番目の受講回数の単位が選択されていません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'class_fee_type_title',
                'message_value'     => '代金種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'tuition_type_required_title',
                'message_value'     => '代金種別は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'student_type_id_required_title',
                'message_value'     => '%d番目の会員種別が未入力です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'save_title',
                'message_value'     => '保存',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'save_success_title',
                'message_value'     => '保存されました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'next_text',
                'message_value'     => '次へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'previous_text',
                'message_value'     => '前へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.class',
                'message_key' => 'delete_title',
            ])->first();
            $message->message_value = '除外';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.class',
                'message_key' => 'save_confirm_content',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'screen_value'      => 'プラン管理',
                'message_key'       => 'confirm_content',
                'message_value'     => '設定内容を登録します。 よろしいですか？',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'start_date_title',
                    'message_value'     => '加入日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'end_date_title',
                    'message_value'     => '退出日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

    // update - Thangqg - 2017/09/28
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_class_name_error_title',
            ])->first();
            $message->message_value = 'プラン名称が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_target_month_error_title',
            ])->first();
            $message->message_value = '%d番目の対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_payment_error_title',
            ])->first();
            $message->message_value = '%d番目の摘要が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_amout_money_error_title',
            ])->first();
            $message->message_value = '%d番目の金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'tuition_type_required_title',
            ])->first();
            $message->message_value = '代金種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_execute_segment_error_title',
            ])->first();
            $message->message_value = '確認区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_member_section_error_title',
            ])->first();
            $message->message_value = '会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_student_section_error_title',
            ])->first();
            $message->message_value = '会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_tuition_error_title',
            ])->first();
            $message->message_value = '受講料が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.class',
                'message_key'       => 'mandatory_items_title',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
    // message_file_id = 2
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_class_name_error_title',
            ])->first();
            $message->message_value = 'プラン名称が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_target_month_error_title',
            ])->first();
            $message->message_value = '%d番目の対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_payment_error_title',
            ])->first();
            $message->message_value = '%d番目の摘要が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_amout_money_error_title',
            ])->first();
            $message->message_value = '%d番目の金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'tuition_type_required_title',
            ])->first();
            $message->message_value = '代金種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_execute_segment_error_title',
            ])->first();
            $message->message_value = '確認区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_member_section_error_title',
            ])->first();
            $message->message_value = '会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_student_section_error_title',
            ])->first();
            $message->message_value = '会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'required_tuition_error_title',
            ])->first();
            $message->message_value = '受講料が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.class',
                'message_key'       => 'mandatory_items_title',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
    // end - update - Thangqg - 2017/09/28
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_title',
                    'message_value'     => '料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_type_1',
                    'message_value'     => '会員種別による料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_type_2',
                    'message_value'     => '受講回数による料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_title',
                    'message_value'     => '料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_type_1',
                    'message_value'     => '会員種別による料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_type_2',
                    'message_value'     => '受講回数による料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_title_new',
                    'message_value'     => 'プランの料金設定方法',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'price_setting_title_new',
                    'message_value'     => 'プランの料金設定方法',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'message_key'       => 'price_setting_title_new',
            ])->first();
            $message->message_value = '料金設定方法';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'message_key'       => 'price_setting_title_new',
            ])->first();
            $message->message_value = '料金設定方法';
            $message->save();
        } catch (\Exception $e) {

        }
        //
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'unsupported_method_warning',
                    'message_value'     => '定義されていない支払方法が設定されています。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'unsupported_method_warning',
                    'message_value'     => '定義されていない支払方法が設定されています。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'warning_title',
                    'message_value'     => '警告',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'warning_title',
                    'message_value'     => '警告',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'payment_method_required',
                    'message_value'     => 'お支払い方法を選択してください',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.class',
                    'screen_value'      => 'プラン管理',
                    'message_key'       => 'payment_method_required',
                    'message_value'     => 'お支払い方法を選択してください',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    }
}
