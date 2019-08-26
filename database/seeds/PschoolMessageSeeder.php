<?php

use Illuminate\Database\Seeder;
use App\Message;

class PschoolMessageSeeder extends Seeder
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

        // 2017-06-09 text header table individual
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'required_ip_code_error_title',
                'message_value'     => 'IPコードは必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'length_ip_code_error_title',
                'message_value'     => 'IPコードは10桁の数字で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'ip_code_half_width_number_title',
                'message_value'     => 'IPコード(半角数字)',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'unsetting_post_office_error',
                'message_value'     => '郵便局の口座が登録されていません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'used_title',
                'message_value'     => '利用あり',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'unused_title',
                'message_value'     => '利用なし',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'pay_easy_title',
                'message_value'     => 'ペイジー',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'fusho_title',
                'message_value'     => '封書式',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'hagaki_title',
                'message_value'     => 'はがき式',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'itaki_nashi_title',
                'message_value'     => '委託なし',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'send_form_title',
                'message_value'     => '差し出し形式',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'student_type_code_title',
                'message_value'     => '種別コード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'student_type_remark_title',
                'message_value'     => '説明',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // modify errors add staff
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'staff_email_format_error',
                'message_value'     => 'メールアドレス２を正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'staff_email_exists',
                'message_value'     => 'メールアドレス２は既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'duplicate_member_type_error',
                'message_value'     => '「%s」会員種別コードが重複しています',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'kakuin_path_title',
                'message_value'     => '角印画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_agency_title',
                'message_value'     => '収納代行会社',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'other_payment_agency_title',
                'message_value'     => 'その他収納代行会社',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // 2017-14-06
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'screen_type_select_box_display',
                'message_value'     => '対象',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_student_select',
                'message_value'     => '会員情報',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_class_select',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_event_select',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_program_select',
                'message_value'     => 'プログラム',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_name_required_message',
                'message_value'     => '項目名の値（%d行目）は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_name_over_message',
                'message_value'     => '項目名は100文字まで入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_name_unique_message',
                'message_value'     => '「%s」項目名が重複しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_code_required_message',
                'message_value'     => '項目コードの値（%d行目）は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_code_over_message',
                'message_value'     => '項目コードは100文字まで入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_code_unique_message',
                'message_value'     => '「%s」項目コードが重複しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_additional_category_title',
                'message_value'     => '管理項目追加',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_name',
                'message_value'     => '名前を入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_email',
                'message_value'     => 'メールアドレスを入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_pass',
                'message_value'     => 'パスワードを入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_repass',
                'message_value'     => 'パスワードを入力してください（確認）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_email2',
                'message_value'     => 'メールアドレス2を入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // 2017-06-15

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_parent_select',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_teacher_select',
                'message_value'     => '先生',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'option_teacher_select',
            ])->first();
            $message->message_value = '講師';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'company_kakuin_path',
                'message_value'     => '社判画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-20-06
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_required_error',
                    'message_value'     => '会員接頭コードが必須です。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_max_error',
                    'message_value'     => '会員接頭コードは100文字以内で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_unique_error',
                    'message_value'     => '会員接頭コードが重複しています。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_title',
                    'message_value'     => '会員接頭コード',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'add_title',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'remove_title',
                    'message_value'     => '削除',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //29-06-2017
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'bank_title',
                    'message_value'     => '銀行',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'post_title',
                    'message_value'     => '郵便局',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'post_title',
            ])->first();
            $message->message_value = 'ゆうちょ銀行';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'default_bank_title',
                    'message_value'     => 'デフォルト',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'default_bank_title',
            ])->first();
            $message->message_value = '既定';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_bank_name_title',
                    'message_value'     => '金融機関名',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_branch_name_title',
                    'message_value'     => '支店名',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_bank_number',
                    'message_value'     => '口座番号',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'delete_bank_warning',
                    'message_value'     => '行を削除しますが、よろしいでしょうか。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
         try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'delete_bank_warning',
            ])->first();
            $message->message_value = 'この行を削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'price_setting_type_2',
                    'message_value'     => '受講回数による料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-07-05
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'edit_payment_title',
                    'message_value'     => 'お支払い方法の編集',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'payment_method_title',
                    'message_value'     => '決済方法',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // 2017-07-07
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'cash_method_title',
                    'message_value'     => '現金',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'bank_method_title',
                    'message_value'     => '口座振替',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'store_method_title',
                    'message_value'     => 'コンビニ決済',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'dialog_confirm_message',
                    'message_value'     => '保存しますがよろしいですか？',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'dialog_confirm_message',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_select_title',
                    'message_value'     => '口座',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        //2017-07-24
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'request_payment_day_title',
            ])->first();
            $message->message_value = '支払期限日';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'invoice_batch_title',
                    'message_value'     => '請求バッチ処理日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'proviso',
                'message_value'     => '但書',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'proviso',
            ])->first();
            $message->message_value = '領収書の但書';
            $message->save();
        } catch (\Exception $e) {

        }
        // edit title preview bank Toran 09/13
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.school',
                    'message_key' => 'preview_bank_name_title',
            ])->first();
            $message->message_value = '金融機関名／記号';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.school',
                    'message_key' => 'preview_branch_name_title',
            ])->first();
            $message->message_value = '支店／ー';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_account_number',
                    'message_value'     => '口座番号／番号',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'new_bank_title',
                    'message_value'     => '銀行・信用金庫',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'detail_bank_type_title',
                    'message_value'     => '支店',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => '1_plus_7_digit_title',
                    'message_value'     => '1桁 + 7桁の半角数字',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'code_title',
                    'message_value'     => 'コード',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'symbol_title',
                    'message_value'     => '記号',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'building_title',
                    'message_value'     => 'ビル',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'official_position_title',
                'message_value'     => '役職名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'require_password_when_process_deposit',
                'message_value'     => '入金処理時にパスワードの入力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'not_do_title',
                'message_value'     => 'しない',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'do_title',
                'message_value'     => 'する',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'deposit_default_search_invoice_year_month',
                'message_value'     => '入金処理でのデフォルト請求月検索',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'before_month_title',
                'message_value'     => '月前から',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'edit_basic_info_title',
            ])->first();
            $message->message_value = '編集';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.school",
                "screen_value" => "基本情報",
                "message_key" => "email_content_title",
                "message_value" => "※このメールアドレスが、送信メールの発信元となります。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id" => 1,
                    "screen_key" => "school.school",
                    "screen_value" => "基本情報",
                    "message_key" => "cannot_delete_last_row",
                    "message_value" => "最後の行を削除することが出来ません。",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.school",
                "screen_value" => "基本情報",
                "message_key" => "check_zip_csv_title",
                "message_value" => "出力CSVファイルにパスワードを付加する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.school",
                "screen_value" => "基本情報",
                "message_key" => "check_zip_csv_note",
                "message_value" => "※パスワードはログインメールアドレスに送信されます。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        // ===========================
        // message_file_id = 2 : en ==
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'required_ip_code_error_title',
                'message_value'     => 'IPコードは必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'length_ip_code_error_title',
                'message_value'     => 'IPコードは10桁の数字で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'ip_code_half_width_number_title',
                'message_value'     => 'IPコード(半角数字)',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'unsetting_post_office_error',
                'message_value'     => '郵便局の口座が登録されていません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'used_title',
                'message_value'     => '利用あり',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'unused_title',
                'message_value'     => '利用なし',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'pay_easy_title',
                'message_value'     => 'ペイジー',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'fusho_title',
                'message_value'     => '封書式',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'hagaki_title',
                'message_value'     => 'はがき式',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'itaki_nashi_title',
                'message_value'     => '委託なし',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'send_form_title',
                'message_value'     => '差し出し形式',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'student_type_code_title',
                'message_value'     => '種別コード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'student_type_remark_title',
                'message_value'     => '説明',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // modify errors add staff
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'staff_email_format_error',
                'message_value'     => 'メールアドレス２を正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'staff_email_exists',
                'message_value'     => 'メールアドレス２は既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'duplicate_member_type_error',
                'message_value'     => '「%s」会員種別コードが重複しています',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'kakuin_path_title',
                'message_value'     => '角印画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_agency_title',
                'message_value'     => '収納代行会社',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'other_payment_agency_title',
                'message_value'     => 'その他収納代行会社',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // 2017-14-06
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'screen_type_select_box_display',
                'message_value'     => '対象',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_student_select',
                'message_value'     => '会員情報',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_class_select',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_event_select',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_program_select',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_name_required_message',
                'message_value'     => '項目名の値（%d行目）は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_name_over_message',
                'message_value'     => '項目名は100文字まで入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_name_unique_message',
                'message_value'     => '「%s」項目名が重複しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_code_required_message',
                'message_value'     => '項目コードの値（%d行目）は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_code_over_message',
                'message_value'     => '項目コードは100文字まで入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_code_unique_message',
                'message_value'     => '「%s」項目コードが重複しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'text_additional_category_title',
                'message_value'     => '管理項目追加',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_name',
                'message_value'     => '名前を入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_email',
                'message_value'     => 'メールアドレスを入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_pass',
                'message_value'     => 'パスワードを入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_repass',
                'message_value'     => 'パスワードを入力してください（確認）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'placeholder_type_email2',
                'message_value'     => 'メールアドレス2を入力してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_parent_select',
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
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'option_teacher_select',
                'message_value'     => '先生',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'option_teacher_select',
            ])->first();
            $message->message_value = '講師';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'company_kakuin_path',
                'message_value'     => '社判画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-20-06
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_required_error',
                    'message_value'     => '会員接頭コードが必須です。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_max_error',
                    'message_value'     => '会員接頭コードは100文字以内で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_unique_error',
                    'message_value'     => '会員接頭コードが重複しています。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'prefix_code_title',
                    'message_value'     => '会員接頭コード',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'add_title',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'remove_title',
                    'message_value'     => '削除',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //29-06-2017
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'bank_title',
                    'message_value'     => '銀行',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'post_title',
                    'message_value'     => '郵便局',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'post_title',
            ])->first();
            $message->message_value = 'ゆうちょ銀行';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'default_bank_title',
                    'message_value'     => 'デフォルト',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'default_bank_title',
            ])->first();
            $message->message_value = '既定';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_bank_name_title',
                    'message_value'     => '金融機関名',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_branch_name_title',
                    'message_value'     => '支店名',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_bank_number',
                    'message_value'     => '口座番号',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'delete_bank_warning',
                    'message_value'     => '行を削除しますが、よろしいでしょうか。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'delete_bank_warning',
            ])->first();
            $message->message_value = 'この行を削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'price_setting_type_2',
                    'message_value'     => '受講回数による料金設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-07-05
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'edit_payment_title',
                    'message_value'     => 'お支払い方法の編集',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'payment_method_title',
                    'message_value'     => '決済方法',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // 2017-07-07
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'cash_method_title',
                    'message_value'     => '現金',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'bank_method_title',
                    'message_value'     => '口座振替',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'store_method_title',
                    'message_value'     => 'コンビニ決済',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'dialog_confirm_message',
                    'message_value'     => '保存しますがよろしいですか？',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'dialog_confirm_message',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_select_title',
                    'message_value'     => '口座',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'request_payment_day_title',
            ])->first();
            $message->message_value = '支払期限日';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'invoice_batch_title',
                    'message_value'     => '請求バッチ処理日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'proviso',
                'message_value'     => '但書',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'proviso',
            ])->first();
            $message->message_value = '領収書の但書';
            $message->save();
        } catch (\Exception $e) {

        }

        // edit title preview bank Toran 09/13
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.school',
                    'message_key' => 'preview_bank_name_title',
            ])->first();
            $message->message_value = '金融機関名／記号';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.school',
                    'message_key' => 'preview_branch_name_title',
            ])->first();
            $message->message_value = '支店／ー';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'preview_account_number',
                    'message_value'     => '口座番号／番号',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'new_bank_title',
                    'message_value'     => '銀行・信用金庫',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'detail_bank_type_title',
                    'message_value'     => '支店',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => '1_plus_7_digit_title',
                    'message_value'     => '1桁 + 7桁の半角数字',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'code_title',
                    'message_value'     => 'コード',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'symbol_title',
                    'message_value'     => '記号',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
// update - thangqg - 2017/09/28
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_pass_error_title',
            ])->first();
            $message->message_value = 'パスワードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_retype_pass_error_title',
            ])->first();
            $message->message_value = 'パスワード（確認）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_school_name_error_title',
            ])->first();
            $message->message_value = '事業者名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_city_error_title',
            ])->first();
            $message->message_value = '都道府県が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_district_error_title',
            ])->first();
            $message->message_value = '市区町村が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_address_error_title',
            ])->first();
            $message->message_value = '住所が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_phone_error_title',
            ])->first();
            $message->message_value = '電話番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_fax_error_title',
            ])->first();
            $message->message_value = 'FAXが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_code_error_title',
            ])->first();
            $message->message_value = '金融機関コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_name_error_title',
            ])->first();
            $message->message_value = '金融機関名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_branch_code_error_title',
            ])->first();
            $message->message_value = '支店コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_branch_name_error_title',
            ])->first();
            $message->message_value = '支店名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_classification_error_title',
            ])->first();
            $message->message_value = '種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_acc_number_error_title',
            ])->first();
            $message->message_value = '口座番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_passbook_code_error_title',
            ])->first();
            $message->message_value = '通帳記号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_passbook_number_error_title',
            ])->first();
            $message->message_value = '通帳番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_acc_name_error_title',
            ])->first();
            $message->message_value = '口座名義が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_acc_name_kana_error_title',
            ])->first();
            $message->message_value = '口座名義（カナ）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_deadline_error_title',
            ])->first();
            $message->message_value = '請求〆日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_payment_error_title',
            ])->first();
            $message->message_value = '請求日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_direct_debit_error_title',
            ])->first();
            $message->message_value = '口座引落日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_amout_display_error_title',
            ])->first();
            $message->message_value = '金額表示が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_tax_error_title',
            ])->first();
            $message->message_value = '消費税率設定が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_consignor_code_error_title',
            ])->first();
            $message->message_value = '委託者コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_consignor_name_error_title',
            ])->first();
            $message->message_value = '委託者名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_staff_name_error_title',
            ])->first();
            $message->message_value = '名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_email_error_title',
            ])->first();
            $message->message_value = 'メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_subject_error_title',
            ])->first();
            $message->message_value = '教科が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_definition_name_error_title',
            ])->first();
            $message->message_value = '定義名称が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_discount_name_error_title',
            ])->first();
            $message->message_value = '名称（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_amount_money_error_title',
            ])->first();
            $message->message_value = '金額（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_idex_error_title',
            ])->first();
            $message->message_value = '会員種別の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_student_name_error_title',
            ])->first();
            $message->message_value = '会員種別の名称（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_belt_color_error_title',
            ])->first();
            $message->message_value = '帯の色の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_belt_note_error_title',
            ])->first();
            $message->message_value = 'ノートの値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_belt_level_error_title',
            ])->first();
            $message->message_value = '表示順番の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_ip_code_error_title',
            ])->first();
            $message->message_value = 'IPコードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'text_name_required_message',
            ])->first();
            $message->message_value = '項目名の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'text_code_required_message',
            ])->first();
            $message->message_value = '項目コードの値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'message_key'       => 'prefix_code_required_error',
            ])->first();
            $message->message_value = '会員接頭コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
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
                'screen_key'        => 'school.school',
                'message_key'       => 'required_pass_error_title',
            ])->first();
            $message->message_value = 'パスワードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_retype_pass_error_title',
            ])->first();
            $message->message_value = 'パスワード（確認）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_school_name_error_title',
            ])->first();
            $message->message_value = '事業者名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_city_error_title',
            ])->first();
            $message->message_value = '都道府県が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_district_error_title',
            ])->first();
            $message->message_value = '市区町村が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_address_error_title',
            ])->first();
            $message->message_value = '住所が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_phone_error_title',
            ])->first();
            $message->message_value = '電話番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_fax_error_title',
            ])->first();
            $message->message_value = 'FAXが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_code_error_title',
            ])->first();
            $message->message_value = '金融機関コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_name_error_title',
            ])->first();
            $message->message_value = '金融機関名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_branch_code_error_title',
            ])->first();
            $message->message_value = '支店コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_branch_name_error_title',
            ])->first();
            $message->message_value = '支店名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_classification_error_title',
            ])->first();
            $message->message_value = '種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_acc_number_error_title',
            ])->first();
            $message->message_value = '口座番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_passbook_code_error_title',
            ])->first();
            $message->message_value = '通帳記号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_passbook_number_error_title',
            ])->first();
            $message->message_value = '通帳番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_acc_name_error_title',
            ])->first();
            $message->message_value = '口座名義が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_bank_acc_name_kana_error_title',
            ])->first();
            $message->message_value = '口座名義（カナ）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_deadline_error_title',
            ])->first();
            $message->message_value = '請求〆日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_payment_error_title',
            ])->first();
            $message->message_value = '請求日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_direct_debit_error_title',
            ])->first();
            $message->message_value = '口座引落日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_amout_display_error_title',
            ])->first();
            $message->message_value = '金額表示が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_tax_error_title',
            ])->first();
            $message->message_value = '消費税率設定が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_consignor_code_error_title',
            ])->first();
            $message->message_value = '委託者コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_consignor_name_error_title',
            ])->first();
            $message->message_value = '委託者名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_staff_name_error_title',
            ])->first();
            $message->message_value = '名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_email_error_title',
            ])->first();
            $message->message_value = 'メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_subject_error_title',
            ])->first();
            $message->message_value = '教科が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_definition_name_error_title',
            ])->first();
            $message->message_value = '定義名称が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_discount_name_error_title',
            ])->first();
            $message->message_value = '名称（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_amount_money_error_title',
            ])->first();
            $message->message_value = '金額（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_idex_error_title',
            ])->first();
            $message->message_value = '会員種別の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_student_name_error_title',
            ])->first();
            $message->message_value = '会員種別の名称（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_belt_color_error_title',
            ])->first();
            $message->message_value = '帯の色の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_belt_note_error_title',
            ])->first();
            $message->message_value = 'ノートの値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_belt_level_error_title',
            ])->first();
            $message->message_value = '表示順番の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'required_ip_code_error_title',
            ])->first();
            $message->message_value = 'IPコードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'text_name_required_message',
            ])->first();
            $message->message_value = '項目名の値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'text_code_required_message',
            ])->first();
            $message->message_value = '項目コードの値（%d行目）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'prefix_code_required_error',
            ])->first();
            $message->message_value = '会員接頭コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'message_key'       => 'mandatory_items_title',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
// end - update - thangqg - 2017/09/28
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'building_title',
                    'message_value'     => 'ビル',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'official_position_title',
                'message_value'     => '役職名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'address2_title',
                    'message_value'     => '番地',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'address2_title',
                    'message_value'     => '番地',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'require_password_when_process_deposit',
                'message_value'     => '入金処理時にパスワードの入力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'not_do_title',
                'message_value'     => 'しない',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'do_title',
                'message_value'     => 'する',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'deposit_default_search_invoice_year_month',
                'message_value'     => '入金処理でのデフォルト請求月検索',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'before_month_title',
                'message_value'     => '月前から',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'generate_address',
                    'message_value'     => '〒→住所変換',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_name_halfsize',
                    'message_value'     => '口座名義（半角）',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_name_halfsize',
                    'message_value'     => '口座名義（半角）',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'bank_account_name_kana_warning',
                    'message_value'     => '※全角文字で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'bank_account_name_kana_warning',
                    'message_value'     => '※全角文字で入力してください。',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'student_setting_menu',
                    'message_value'     => '会員メニュー設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'student_setting_menu',
                    'message_value'     => '会員メニュー設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'parent_setting_menu',
                    'message_value'     => '請求先メニュー設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'parent_setting_menu',
                    'message_value'     => '請求先メニュー設定',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'detail_info_edit_title',
                    'message_value'     => '詳細情報編集',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'detail_info_edit_title',
                    'message_value'     => '詳細情報編集',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'required_text_explain_title',
                    'message_value'     => '印のついた項目が未入力です。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'required_text_explain_title',
                    'message_value'     => '印のついた項目が未入力です。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'permission_setting_title',
                    'message_value'     => '権限設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'permission_setting_title',
                    'message_value'     => '権限設定',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'back_btn',
                    'message_value'     => '戻る',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'back_btn',
                    'message_value'     => '戻る',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'message_save_confirm',
                    'message_value'     => '保存しますが、よろしいでしょうか。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'message_save_confirm',
                    'message_value'     => '保存しますが、よろしいでしょうか。',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'register_btn_title',
                    'message_value'     => 'スタッフ新規',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'register_btn_title',
                    'message_value'     => 'スタッフ新規',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'student_category_title',
                    'message_value'     => '個人／法人',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'student_category_title',
                    'message_value'     => '個人／法人',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'member_corp',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'member_corp',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'member_personal',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'member_personal',
                    'message_value'     => '個人',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_name_entered_zenkaku',
                    'message_value'     => '口座名義は全角文字で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_name_entered_zenkaku',
                    'message_value'     => '口座名義は全角文字で入力してください。',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_name_entered_hankaku',
                    'message_value'     => '口座名義（半角）は半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'account_name_entered_hankaku',
                    'message_value'     => '口座名義（半角）は半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'show_number_corporation_title',
                    'message_value'     => '法人の場合の人数表記',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'show_number_corporation_title',
                    'message_value'     => '法人の場合の人数表記',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
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
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'over_length_password_msg',
                    'message_value'     => 'パスワードは8文字以上16文字以下で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'over_length_password_msg',
                    'message_value'     => 'パスワードは8文字以上16文字以下で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'password_regex_warning',
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
                    'screen_key'        => 'school.school',
                    'screen_value'      => '基本情報',
                    'message_key'       => 'password_regex_warning',
                    'message_value'     => '半角英数文字または特殊文字(-,_,.,$,#,:@,!)で8文字以上16文字以下',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // Toran add payment_month text
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_this_month',
                'message_value'     => '当月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_this_month',
                'message_value'     => '当月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_next_month',
                'message_value'     => '翌月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_next_month',
                'message_value'     => '翌月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_second_following_month',
                'message_value'     => '翌々月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.school',
                'screen_value'      => '基本情報',
                'message_key'       => 'payment_second_following_month',
                'message_value'     => '翌々月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // Toran fix bank account message 02-27
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.school',
                'message_key' => 'bank_account_name_kana_warning',
            ])->first();
            $message->message_value = '※全角カタカナ文字で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'bank_account_name_kana_warning',
            ])->first();
            $message->message_value = '※全角カタカナ文字で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.school',
                'message_key' => 'edit_basic_info_title',
            ])->first();
            $message->message_value = '編集';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.school",
                "screen_value" => "基本情報",
                "message_key" => "email_content_title",
                "message_value" => "※このメールアドレスが、送信メールの発信元となります。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id" => 2,
                    "screen_key" => "school.school",
                    "screen_value" => "基本情報",
                    "message_key" => "cannot_delete_last_row",
                    "message_value" => "最後の行を削除することが出来ません。",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.school",
                "screen_value" => "基本情報",
                "message_key" => "check_zip_csv_title",
                "message_value" => "出力CSVファイルにパスワードを付加する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.school",
                "screen_value" => "基本情報",
                "message_key" => "check_zip_csv_note",
                "message_value" => "※パスワードはログインメールアドレスに送信されます。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
    }
}
