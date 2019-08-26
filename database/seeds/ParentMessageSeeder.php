<?php

use Illuminate\Database\Seeder;
use App\Message;

class ParentMessageSeeder extends Seeder
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
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.parent',
                'message_key' => 'main_title',
            ])->first();
            $message->message_value = '請求先情報';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "email_address_1_existed_err",
                "message_value"     => "請求先メールアドレス1は既に存在しています。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_registered_address_title",
                "message_value"     => "請求先登録住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_address_title",
                "message_value"     => "請求先住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "other_title",
                "message_value"     => "その他",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_name_title",
                "message_value"     => "請求先名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_addressee_title",
                "message_value"     => "請求先宛名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_addressee_input_title",
                "message_value"     => "請求先宛名を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        // 2017-06-07 text move previous parent
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "label_export_csv_title",
                "message_value"     => "ラベル用CSV出力",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'previous_text',
                'message_value'     => '前へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'next_text',
                'message_value'     => '次へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-06-07 text student type header table
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'student_type',
                'message_value'     => '会員種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-06-07 text student category =1 on selectbox
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'student_type_4',
                'message_value'     => '個人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-06-07 text student category =2 on selectbox
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'student_type_5',
                'message_value'     => '法人',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-06-07 text for search by student no
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'student_no',
                'message_value'     => '会員番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-06-07 text sort title
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'sorting_title',
                'message_value'     => '並べ替え',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-06-07 warning text for bank name
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'bank_account_name_kana_warning',
                'message_value'     => '※全角文字で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2017-06-07 text for search by student type
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'member_type_text',
                'message_value'     => '会員区分',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'items_list',
                'message_value'     => '件',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'name_search_new_title',
                'message_value'     => '氏名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'name_search_new_placeholder',
                'message_value'     => '請求先・会員氏名の一部を漢字・カナで入力します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'member_type_new_text',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'contact_phone_title',
                'message_value'     => '連絡先電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-16-06
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'input_only_to_change_title',
                'message_value'     => '変更する場合のみ入力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-26-06
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'address_title',
                    'message_value'     => '番地',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'address_error_max_64',
                    'message_value'     => '番地は64文字以内で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'building_error_max_64',
                    'message_value'     => 'ビルは64文字以内で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'building_mandatory',
                    'message_value'     => 'ビルは必須です。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'save_confirm',
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
                    'screen_key' => 'school.parent',
                    'message_key' => 'post_office',
            ])->first();
            $message->message_value = 'ゆうちょ銀行';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'type_title',
                'message_value'     => '種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'notsend',
                'message_value'     => '送信しない',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'credit_card_connect_method_title',
                'message_value'     => 'クレジットカード決済（継続）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.parent',
                'message_key' => 'credit_card_method_title',
            ])->first();
            $message->message_value = 'クレジットカード決済（都度）';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'invoice_type',
                'message_value'     => '支払方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'mail_infomation',
                'message_value'     => '通知方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        // ===========================
        // message_file_id = 2 : en ==
        // ===========================
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.parent',
                'message_key' => 'main_title',
            ])->first();
            $message->message_value = '請求先情報';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "email_address_1_existed_err",
                "message_value"     => "請求先メールアドレス1は既に存在しています。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_registered_address_title",
                "message_value"     => "請求先登録住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_address_title",
                "message_value"     => "請求先住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "other_title",
                "message_value"     => "その他",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_name_title",
                "message_value"     => "請求先名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_addressee_title",
                "message_value"     => "請求先宛名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "parent_addressee_input_title",
                "message_value"     => "請求先宛名を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.parent",
                "screen_value"      => "請求先管理",
                "message_key"       => "label_export_csv_title",
                "message_value"     => "ラベル用CSV出力",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'previous_text',
                'message_value'     => '前へ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'student_type',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'student_no',
                'message_value'     => '会員番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'sorting_title',
                'message_value'     => '並べ替え',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'member_type_text',
                'message_value'     => '会員区分',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'items_list',
                'message_value'     => '件',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'name_search_new_title',
                'message_value'     => '氏名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'name_search_new_placeholder',
                'message_value'     => '請求先・会員氏名の一部を漢字・カナで入力します。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'member_type_new_text',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'contact_phone_title',
                'message_value'     => '連絡先電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-16-06
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'input_only_to_change_title',
                'message_value'     => '変更する場合のみ入力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //2017-26-06
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'address_title',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'address_error_max_64',
                    'message_value'     => '番地は64文字以内で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'building_error_max_64',
                    'message_value'     => 'ビルは64文字以内で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'building_mandatory',
                    'message_value'     => 'ビルは必須です。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        //2017-07-13 update message　ビル　→　ビル名
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.parent',
                'message_key' => 'building_title',
            ])->first();
            $message->message_value = 'ビル名';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.parent',
                'message_key' => 'building_title',
            ])->first();
            $message->message_value = 'ビル名';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'save_confirm',
                    'message_value'     => '設定内容を登録します。 よろしいですか？',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
// update - thangqg - 2017/09/21
        try{
            $message=Message::where([
                'message_file_id'   =>  1,
                'screen_key'        =>  'school.parent',
                'message_key'       =>  'account_holder_require',
            ])->first();
            $message->message_value='口座名義が未入力です。';
            $message->save();
        }catch(\Exception$e){

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_kana_require',
                'message_value'     => '口座名義（カナ）が未入力です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_kana_up_30_character',
                'message_value'     => '口座名義（カナ）は30文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_kana_entered_alphanumeric_kana',
                'message_value'     => '口座名義（カナ）は半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_information_post_bank',
                'message_value'     => '口座情報（ゆうちょ銀行）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }


        try{
            $message=Message::where([
                'message_file_id'   =>  2,
                'screen_key'        =>  'school.parent',
                'message_key'       =>  'account_holder_require',
            ])->first();
            $message->message_value='口座名義が未入力です。';
            $message->save();
        }catch(\Exception$e){

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_kana_require',
                'message_value'     => '口座名義（カナ）が未入力です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_kana_up_30_character',
                'message_value'     => '口座名義（カナ）は30文字以内で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_kana_entered_alphanumeric_kana',
                'message_value'     => '口座名義（カナ）は半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）で入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'account_information_post_bank',
                'message_value'     => '口座情報（ゆうちょ銀行）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
// end - update - thangqg - 2017/09/21
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.parent',
                    'message_key' => 'post_office',
            ])->first();
            $message->message_value = 'ゆうちょ銀行';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'name_search_new_placeholder',
            ])->first();
            $message->message_value = '請求先・会員の氏名の一部（漢字・カナ）を入力';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'name_search_new_placeholder',
            ])->first();
            $message->message_value = '請求先・会員の氏名の一部（漢字・カナ）を入力';
            $message->save();
        } catch (\Exception $e) {

        }
// update - Thangqg 2018/09/28
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'claimant_name_required',
            ])->first();
            $message->message_value = '請求先名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_claimant_email_address_1',
            ])->first();
            $message->message_value = '請求先メールアドレス1が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_claimant_name_kana',
            ])->first();
            $message->message_value = '請求先名前カナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_state_name',
            ])->first();
            $message->message_value = '都道府県名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_city_name',
            ])->first();
            $message->message_value = '市区町村名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_address',
            ])->first();
            $message->message_value = '住所が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_home_telephone',
            ])->first();
            $message->message_value = '連絡先電話が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_financial_institutions_code',
            ])->first();
            $message->message_value = '金融機関コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_financial_institution_name',
            ])->first();
            $message->message_value = '金融機関名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_branch_code',
            ])->first();
            $message->message_value = '支店コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_branch_name',
            ])->first();
            $message->message_value = '支店名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_financial_institution_type',
            ])->first();
            $message->message_value = '金融機関種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_account_number',
            ])->first();
            $message->message_value = '口座番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_passbook_symbol',
            ])->first();
            $message->message_value = '通帳記号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_passbook_number',
            ])->first();
            $message->message_value = '通帳番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'account_holder_require',
            ])->first();
            $message->message_value = '口座名義が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_passbook_name',
            ])->first();
            $message->message_value = '通帳名義が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_target_month',
            ])->first();
            $message->message_value = '番目の%s対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_abstract',
            ])->first();
            $message->message_value = '番目の%s摘要が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_amount_of_money',
            ])->first();
            $message->message_value = '番目の%s金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'amount_money_numeric',
            ])->first();
            $message->message_value = '番目の%s金額には数値を入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_account_name_kana',
            ])->first();
            $message->message_value = '口座名義（カナ）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'building_mandatory',
            ])->first();
            $message->message_value = 'ビルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_items_marked',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.parent',
                'message_key'       => 'parent_pass_required_title',
            ])->first();
            $message->message_value = 'パスワードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
    // message_file_id = 2
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'claimant_name_required',
            ])->first();
            $message->message_value = '請求先名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_claimant_email_address_1',
            ])->first();
            $message->message_value = '請求先メールアドレス1が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_claimant_name_kana',
            ])->first();
            $message->message_value = '請求先名前カナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_state_name',
            ])->first();
            $message->message_value = '都道府県名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_city_name',
            ])->first();
            $message->message_value = '市区町村名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_address',
            ])->first();
            $message->message_value = '住所が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_home_telephone',
            ])->first();
            $message->message_value = '連絡先電話が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_financial_institutions_code',
            ])->first();
            $message->message_value = '金融機関コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_financial_institution_name',
            ])->first();
            $message->message_value = '金融機関名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_branch_code',
            ])->first();
            $message->message_value = '支店コードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_branch_name',
            ])->first();
            $message->message_value = '支店名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_financial_institution_type',
            ])->first();
            $message->message_value = '金融機関種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_account_number',
            ])->first();
            $message->message_value = '口座番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_passbook_symbol',
            ])->first();
            $message->message_value = '通帳記号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_passbook_number',
            ])->first();
            $message->message_value = '通帳番号が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'account_holder_require',
            ])->first();
            $message->message_value = '口座名義が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_passbook_name',
            ])->first();
            $message->message_value = '通帳名義が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_target_month',
            ])->first();
            $message->message_value = '番目の%s対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_abstract',
            ])->first();
            $message->message_value = '番目の%s摘要が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_amount_of_money',
            ])->first();
            $message->message_value = '番目の%s金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'amount_money_numeric',
            ])->first();
            $message->message_value = '番目の%s金額には数値を入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'require_account_name_kana',
            ])->first();
            $message->message_value = '口座名義（カナ）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'building_mandatory',
            ])->first();
            $message->message_value = 'ビルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'mandatory_items_marked',
            ])->first();
            $message->message_value = '印のついた項目が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'message_key'       => 'parent_pass_required_title',
            ])->first();
            $message->message_value = 'パスワードが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
// update - Thangqg 2018/09/28

        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => '5_digit_no_title',
                    'message_value'     => '５桁の半角数字。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => '5_digit_no_title',
                    'message_value'     => '５桁の半角数字。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => '8_digit_no_title',
                    'message_value'     => '８桁の半角数字。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => '8_digit_no_title',
                    'message_value'     => '８桁の半角数字。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => '30_single_kana_upper_title',
                    'message_value'     => '半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）３０文字まで。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => '30_single_kana_upper_title',
                    'message_value'     => '半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）３０文字まで。',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'use_as_login_title',
                    'message_value'     => '※ログインIDとして、使用されます。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'use_as_login_title',
                    'message_value'     => '※ログインIDとして、使用されます。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        //
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'message_key'       => 'account_kana_entered_alphanumeric_kana',
            ])->first();
            $message->message_value = '口座名義（半角）は半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.parent',
                    'message_key'       => 'account_kana_entered_alphanumeric_kana',
            ])->first();
            $message->message_value = '口座名義（半角）は半角英大文字、半角カナ（小さい ﾔ ﾕ ﾖ ﾂを除く）で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        //
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
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
                    'screen_key'        => 'school.parent',
                    'screen_value'      => '請求先管理',
                    'message_key'       => 'password_regex_msg',
                    'message_value'     => 'パスワード は半角英数文字または特殊文字(-,_,.,$,#,:@,!)。',
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
                'screen_key' => 'school.parent',
                'message_key' => 'bank_account_name_kana_warning',
            ])->first();
            $message->message_value = '※全角カタカナ文字で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.parent',
                'message_key' => 'bank_account_name_kana_warning',
            ])->first();
            $message->message_value = '※全角カタカナ文字で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
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
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'type_title',
                'message_value'     => '種別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'notsend',
                'message_value'     => '送信しない',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'credit_card_connect_method_title',
                'message_value'     => 'クレジットカード決済（継続）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.parent',
                'message_key' => 'credit_card_method_title',
            ])->first();
            $message->message_value = 'クレジットカード決済（都度）';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'invoice_type',
                'message_value'     => '支払方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.parent',
                'screen_value'      => '請求先管理',
                'message_key'       => 'mail_infomation',
                'message_value'     => '通知方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e) {

        }
    }
}
