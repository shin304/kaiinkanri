<?php

use Illuminate\Database\Seeder;
use App\Message;

class ProgramMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = array() ('' => , );

        // kieudtd : 2017-06-07 プログラム再改修
        // ===========================
        // message_file_id = 1 : jp
        // ===========================
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'ttl_msg_program_name_not_empty',
            ])->first();
            $message->message_value = '名称が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'hold_date_time',
                'message_value'     => '開催開始日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'dp_from',
                'message_value'     => 'から',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'dp_to',
                'message_value'     => 'まで',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'start_message',
                'message_value'     => '開始',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'end_message',
                'message_value'     => '終了',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_period_title',
                'message_value'     => '募集期間',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_register_title',
                'message_value'     => '会員登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'non_member_flag_title',
                'message_value'     => '非会員可',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'non_member_capacity_title',
                'message_value'     => '定員(非会員）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'footer_mail_title',
                'message_value'     => 'フッター',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'footer_mail_input_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_valid',
                'message_value'     => '問合せメールアドレスを正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_max_64',
                'message_value'     => '問合せメールアドレスは64文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_due_date_title',
                'message_value'     => '支払期限',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'to_title',
                'message_value'     => '～',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'start_date_require',
                'message_value'     => '開始日は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'start_date_require',
            ])->first();
            $message->message_value = '開始日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_start_required',
                'message_value'     => '募集開始日は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'recruitment_start_required',
            ])->first();
            $message->message_value = '募集開始日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'content',
                'message_value'     => '本文',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'content_require',
                'message_value'     => '本文は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'content_require',
            ])->first();
            $message->message_value = '本文が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'close_date_after_start_date',
                'message_value'     => '終了日は開始日以後にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_subject_less_than_255',
                'message_value'     => '件名は255文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_subject_required',
                'message_value'     => '件名は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'mail_subject_required',
            ])->first();
            $message->message_value = '件名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_start_before_start_date',
                'message_value'     => '募集開始日は開始日以前にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finish_after_recruitment_start',
                'message_value'     => '募集締切日は募集開始日以後にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finish_before_close_date',
                'message_value'     => '募集締切日は終了日以前にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_location_required',
                'message_value'     => '開催場所は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'program_location_required',
            ])->first();
            $message->message_value = '開催場所が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_due_date_required',
                'message_value'     => '支払期限は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'payment_due_date_required',
            ])->first();
            $message->message_value = '支払期限が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_capacity_required',
                'message_value'     => '定員(会員）は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'member_capacity_required',
            ])->first();
            $message->message_value = '定員(会員）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_capacity_within_10_digit',
                'message_value'     => '定員(会員）は10桁以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'student_type_required',
                'message_value'     => '%d番目の会員種別は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'student_type_required',
            ])->first();
            $message->message_value = '%d番目の会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'duplicate_student_type_error',
                'message_value'     => '%d番目の会員種別が同じものが存在します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'sending_mail_title',
                'message_value'     => '送信メール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'header_mail_title',
                'message_value'     => '件名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'tuition_fee',
                'message_value'     => '料金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finish_title',
                'message_value'     => '募集締切日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'recruitment_finish_title',
            ])->first();
            $message->message_value = '募集締切日';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_bank_transfer_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_capacity_title',
                'message_value'     => '定員(会員）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'name_title',
                'message_value'     => '名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'application_deadline_title',
                'message_value'     => '定員に達すると募集締切',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'guide_mail',
                'message_value'     => '案内メール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'send_view',
                'message_value'     => '（閲覧／送信）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'enter_list_export_title',
                'message_value'     => '操作',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'csv_export_title',
                'message_value'     => 'CSV出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'select_encode_title',
                'message_value'     => 'エンコードを選択してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_name_title',
                'message_value'     => 'プログラム名称',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'cash_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'open_start_date_time',
                'message_value'     => '開催開始日時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'open_start_date_time',
            ])->first();
            $message->message_value = '開催期間';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_per_total',
                'message_value'     => '参加者／定員',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_exist_title',
                'message_value'     => 'プログラムコードは既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'other_title',
                'message_value'     => 'その他',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'person_in_charge1_title',
                'message_value'     => '担当者１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'person_in_charge2_title',
                'message_value'     => '担当者２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_credit_card_title',
                'message_value'     => '信用カード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_location_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'remark_title',
                'message_value'     => '備考',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'header_mail_input_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'content_input_title',
                'message_value'     => '本文を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_location_input_title',
                'message_value'     => '開催場所を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_title',
                'message_value'     => 'コード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_input_title',
                'message_value'     => 'プログラムコードを入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'guide_program_code_title',
                'message_value'     => '５文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_number_title',
                'message_value'     => '問合せ電話番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_number_input_title',
                'message_value'     => '問合せ電話番号を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_title',
                'message_value'     => '問合せメール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_input_title',
                'message_value'     => '問合せメールアドレスを入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_required_title',
                'message_value'     => 'プログラムコードは必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'program_code_required_title',
            ])->first();
            $message->message_value = 'プログラムコードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_max_title',
                'message_value'     => 'プログラムコードは５文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_main_title',
                'message_value'     => 'メールテンプレート一覧',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_main_title_create',
                'message_value'     => 'テンプレート登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_create',
                'message_value'     => 'テンプレート登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'btn_list_mail_template',
                'message_value'     => 'テンプレート選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_option',
                'message_value'     => '全て',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_success',
                'message_value'     => 'メールテンプレートを作成しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        //2017-07-13 update message
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_create',
            ])->first();
            $message->message_value = 'テンプレート登録';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'btn_list_mail_template',
            ])->first();
            $message->message_value = 'テンプレート選択';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_main_title',
            ])->first();
            $message->message_value = 'メールテンプレート一覧';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_option',
            ])->first();
            $message->message_value = '全て';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_required_name',
            ])->first();
            $message->message_value = '名称を入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_delete_success',
                'message_value'     => 'メールテンプレートを削除しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_delete_main_title',
                'message_value'     => 'メールテンプレートの削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_error_message_required_name',
                'message_value'     => 'メールテンプレート名は既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
		try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'delete_ok',
                'message_value'     => '削除します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'delete_ok',
            ])->first();
            $message->message_value = '削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
		try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'delete_title',
                'message_value'     => '削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finished_title',
                'message_value'     => '募集締切',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'attention_title',
                'message_value'     => '注意事項',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'fee_plan_name_title',
                'message_value'     => '名称',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.program',
                'message_key' => 'save_confirm_content',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'export_confirm',
                'message_value'     => '文字コードを選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                    'screen_key'        => 'school.program',
                    'screen_value'      => 'プログラム管理',
                    'message_key'       => 'merge_event_to_schedule_invoice',
                    'message_value'     => '定期請求書と一緒に請求を',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.program',
                    'screen_value'      => 'プログラム管理',
                    'message_key'       => 'yes_title',
                    'message_value'     => '許可する',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.program',
                    'screen_value'      => 'プログラム管理',
                    'message_key'       => 'no_title',
                    'message_value'     => '許可しない',
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
                'screen_key' => 'school.program',
                'message_key' => 'ttl_msg_program_name_not_empty',
            ])->first();
            $message->message_value = '名称が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'hold_date_time',
                'message_value'     => '開催開始日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'dp_from',
                'message_value'     => 'から',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'dp_to',
                'message_value'     => 'まで',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'start_message',
                'message_value'     => '開始',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'end_message',
                'message_value'     => '終了',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_period_title',
                'message_value'     => '募集期間',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_register_title',
                'message_value'     => '会員登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'non_member_flag_title',
                'message_value'     => '非会員可',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'non_member_capacity_title',
                'message_value'     => '定員(非会員）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'footer_mail_title',
                'message_value'     => 'フッター',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'footer_mail_input_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_valid',
                'message_value'     => '問合せメールアドレスを正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_max_64',
                'message_value'     => '問合せメールアドレスは64文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_due_date_title',
                'message_value'     => '支払期限',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'to_title',
                'message_value'     => '～',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'start_date_require',
                'message_value'     => '開始日は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'start_date_require',
            ])->first();
            $message->message_value = '開始日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_start_required',
                'message_value'     => '募集開始日は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'recruitment_start_required',
            ])->first();
            $message->message_value = '募集開始日が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'content',
                'message_value'     => '本文',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'content_require',
                'message_value'     => '本文は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'content_require',
            ])->first();
            $message->message_value = '本文が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'close_date_after_start_date',
                'message_value'     => '終了日は開始日以後にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_subject_less_than_255',
                'message_value'     => '件名は255文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_subject_required',
                'message_value'     => '件名は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'mail_subject_required',
            ])->first();
            $message->message_value = '件名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_start_before_start_date',
                'message_value'     => '募集開始日は開始日以前にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finish_after_recruitment_start',
                'message_value'     => '募集締切日は募集開始日以後にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finish_before_close_date',
                'message_value'     => '募集締切日は終了日以前にしなければなりません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_location_required',
                'message_value'     => '開催場所は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'program_location_required',
            ])->first();
            $message->message_value = '開催場所が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_due_date_required',
                'message_value'     => '支払期限は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'payment_due_date_required',
            ])->first();
            $message->message_value = '支払期限が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_capacity_required',
                'message_value'     => '定員(会員）は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'member_capacity_required',
            ])->first();
            $message->message_value = '定員(会員）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_capacity_within_10_digit',
                'message_value'     => '定員(会員）は10桁以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'student_type_required',
                'message_value'     => '%d番目の会員種別は必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'student_type_required',
            ])->first();
            $message->message_value = '%d番目の会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'duplicate_student_type_error',
                'message_value'     => '%d番目の会員種別が同じものが存在します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'sending_mail_title',
                'message_value'     => '送信メール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'header_mail_title',
                'message_value'     => '件名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'tuition_fee',
                'message_value'     => '料金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finish_title',
                'message_value'     => '募集締切日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'recruitment_finish_title',
            ])->first();
            $message->message_value = '募集締切日';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_bank_transfer_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_capacity_title',
                'message_value'     => '定員(会員）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'name_title',
                'message_value'     => '名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'application_deadline_title',
                'message_value'     => '定員に達すると募集締切',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'guide_mail',
                'message_value'     => '案内メール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'send_view',
                'message_value'     => '（閲覧／送信）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'enter_list_export_title',
                'message_value'     => '操作',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'csv_export_tile',
                'message_value'     => 'CSV出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'select_encode_title',
                'message_value'     => 'エンコードを選択してください',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_name_title',
                'message_value'     => 'プログラム名称',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'cash_title',
                'message_value'     => '現金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'open_start_date_time',
                'message_value'     => '開催開始日時',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'open_start_date_time',
            ])->first();
            $message->message_value = '開催期間';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'member_per_total',
                'message_value'     => '参加者／定員',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_exist_title',
                'message_value'     => 'プログラムコードは既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'other_title',
                'message_value'     => 'その他',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'person_in_charge1_title',
                'message_value'     => '担当者１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'person_in_charge2_title',
                'message_value'     => '担当者２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'payment_credit_card_title',
                'message_value'     => '信用カード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_location_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'remark_title',
                'message_value'     => '備考',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'header_mail_input_title',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'content_input_title',
                'message_value'     => '本文を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_location_input_title',
                'message_value'     => '開催場所を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_title',
                'message_value'     => 'コード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_input_title',
                'message_value'     => 'プログラムコードを入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'guide_program_code_title',
                'message_value'     => '５文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_number_title',
                'message_value'     => '問合せ電話番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_number_input_title',
                'message_value'     => '問合せ電話番号を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_title',
                'message_value'     => '問合せメール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'contact_email_input_title',
                'message_value'     => '問合せメールアドレスを入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_required_title',
                'message_value'     => 'プログラムコードは必須です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'program_code_required_title',
            ])->first();
            $message->message_value = 'プログラムコードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'program_code_max_title',
                'message_value'     => 'プログラムコードは５文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_main_title',
                'message_value'     => 'メールテンプレート一覧',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_main_title_create',
                'message_value'     => 'テンプレート登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_create',
                'message_value'     => 'テンプレート登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'btn_list_mail_template',
                'message_value'     => 'テンプレート選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_option',
                'message_value'     => '全て',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
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
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_success',
                'message_value'     => 'メールテンプレートを作成しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_delete_success',
                'message_value'     => 'メールテンプレートを削除しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

         //2017-07-13 update message
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_create',
            ])->first();
            $message->message_value = 'テンプレート登録';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'btn_list_mail_template',
            ])->first();
            $message->message_value = 'テンプレート選択';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_main_title',
            ])->first();
            $message->message_value = 'メールテンプレート一覧';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_option',
            ])->first();
            $message->message_value = '全て';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'mail_template_required_name',
            ])->first();
            $message->message_value = '名称を入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_delete_main_title',
                'message_value'     => 'メールテンプレートの削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'mail_template_error_message_required_name',
                'message_value'     => 'メールテンプレート名は既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'delete_ok',
                'message_value'     => '削除します。よろしいですか。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'delete_ok',
            ])->first();
            $message->message_value = '削除します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'delete_title',
                'message_value'     => '削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // kieudtd : 2017-06-07 プログラム再改修
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'recruitment_finished_title',
                'message_value'     => '募集締切',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'attention_title',
                'message_value'     => '注意事項',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'fee_plan_name_title',
                'message_value'     => '名称',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'save_confirm_content',
            ])->first();
            $message->message_value = '設定内容を登録します。 よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'export_confirm',
                'message_value'     => '文字コードを選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.program',
                'screen_value'      => 'プログラム管理',
                'message_key'       => 'confirm_content',
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
                'screen_key' => 'school.program',
                'message_key' => 'ttl_msg_nessesary_to_input',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.program',
                'message_key' => 'ttl_msg_nessesary_to_input',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.program',
                    'screen_value'      => 'プログラム管理',
                    'message_key'       => 'merge_event_to_schedule_invoice',
                    'message_value'     => '定期請求書と一緒に請求を',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.program',
                    'screen_value'      => 'プログラム管理',
                    'message_key'       => 'yes_title',
                    'message_value'     => '許可する',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.program',
                    'screen_value'      => 'プログラム管理',
                    'message_key'       => 'no_title',
                    'message_value'     => '許可しない',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    }
}
