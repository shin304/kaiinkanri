<?php

use Illuminate\Database\Seeder;
use App\Message;

class StudentMessageSeeder extends Seeder
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
                'screen_key' => 'school.student',
                'message_key' => 'student_type_required_title',
            ])->first();
            $message->message_value = '【会員】会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_name_required_title',
            ])->first();
            $message->message_value = '【会員】名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'students_furigana_required_title',
            ])->first();
            $message->message_value = '【会員】会員フリガナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_nickname_required_title',
            ])->first();
            $message->message_value = '【会員】会員ニックネームが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_email_address_required_title',
            ])->first();
            $message->message_value = '【会員】会員メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_birthday_year_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（年）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_birthday_month_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（月）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_birthday_day_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（日）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_school_name_required_title',
            ])->first();
            $message->message_value = '【会員】学校名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_section_required_title',
            ])->first();
            $message->message_value = '【会員】会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'state_name_exam_region_1_required_title',
            ])->first();
            $message->message_value = '受験地域1都道府県名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'guardian_information_required_title',
            ])->first();
            $message->message_value = '請求先情報が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'csv_file_required_title',
            ])->first();
            $message->message_value = 'CSVファイルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_type_required_title',
            ])->first();
            $message->message_value = '【会員】会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_name_required_title',
            ])->first();
            $message->message_value = '【会員】名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'members_furigana_required_title',
            ])->first();
            $message->message_value = '【会員】フリガナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_nickname_required_title',
            ])->first();
            $message->message_value = '【会員】ニックネームが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_email_address_required_title',
            ])->first();
            $message->message_value = '【会員】メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_birthday_year_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（年）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_birthday_month_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（月）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_birthday_day_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（日）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_school_name_required_title',
            ])->first();
            $message->message_value = '【会員】学校名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'member_section_required_title',
            ])->first();
            $message->message_value = '【会員】会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'billing_information_required_title',
            ])->first();
            $message->message_value = '請求先情報が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        //-----------
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "require_target_month",
                "message_value"     => "番目の%s対象月が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'require_target_month',
            ])->first();
            $message->message_value = '%s番目の対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'require_target_month',
            ])->first();
            $message->message_value = '%s番目の対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "require_abstract",
                "message_value"     => "番目の%s摘要が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'require_abstract',
            ])->first();
            $message->message_value = '%s番目の摘要が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'require_abstract',
            ])->first();
            $message->message_value = '%s番目の摘要が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "require_amount_of_money",
                "message_value"     => "番目の%s金額が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'require_amount_of_money',
            ])->first();
            $message->message_value = '%s番目の金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'require_amount_of_money',
            ])->first();
            $message->message_value = '%s番目の金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'post_office',
            ])->first();
            $message->message_value = 'ゆうちょ銀行';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'main_title',
            ])->first();
            $message->message_value = '会員情報';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "label_export_csv_title",
                "message_value"     => "ラベル用CSV出力",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "parent_name",
                "message_value" => "請求先氏名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "parent_mail",
                "message_value" => "メールアドレス",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "",
                "message_value" => "",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "csv_template",
                "message_value" => "CSVテンプレート",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_student",
                "message_value" => "全員",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_under_contract",
                "message_value" => "契約中",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_end_contract",
                "message_value" => "契約終了",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "require_home_telephone",
                "message_value" => "自宅電話は必須です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "select",
                "message_value" => "選択",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "reset_parent",
                "message_value" => "レセット",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "first_name_kana_title",
                "message_value" => "フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "btn_copy_student_address",
                "message_value" => "会員の住所をコピー",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_personal",
                "message_value" => "個人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_corp",
                "message_value" => "法人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_member",
                "message_value" => "人数",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_type_required",
                "message_value" => "会員種別は必須です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'err_student_type_required',
            ])->first();
            $message->message_value = '【会員】会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_required",
                "message_value" => "会員名前は必須です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'err_student_name_required',
            ])->first();
            $message->message_value = '【会員】名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_max",
                "message_value" => "会員名前は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_kana_max",
                "message_value" => "会員フリガナは255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_romaji_max",
                "message_value" => "会員ローマ字は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_required",
                "message_value" => "会員メールアドレスは必須です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'err_student_mailaddress_required',
            ])->first();
            $message->message_value = '【会員】メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_format",
                "message_value" => "会員メールアドレスの形式が不正です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_unique",
                "message_value" => "会員メールアドレスが既に使用されています。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_max",
                "message_value" => "会員メールアドレスはは64文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_birthday_required",
                "message_value" => "会員生年月は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_birthday_format",
                "message_value" => "会員生年月は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_enter_date_format",
                "message_value" => "入会日は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_resign_date_format",
                "message_value" => "退会日は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_zip_code_numeric",
                "message_value" => "郵便番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_zip_code_digits",
                "message_value" => "郵便番号は全部7桁で入力してください",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_address_max",
                "message_value" => "会員番地は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_building_max",
                "message_value" => "会員ビルは255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_required",
                "message_value" => "会員自宅電話は必要です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_numeric",
                "message_value" => "会員自宅電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_digits_between",
                "message_value" => "会員自宅電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_handset_no_numeric",
                "message_value" => "会員携帯電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_handset_no_digits_between",
                "message_value" => "会員携帯電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_name_required",
                "message_value" => "請求先名前は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_name_max",
                "message_value" => "請求先名前は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_name_kana_max",
                "message_value" => "請求先フリガナは255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_required",
                "message_value" => "請求先メールアドレス１は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_email",
                "message_value" => "請求先メールアドレス１は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_unique",
                "message_value" => "請求先メールアドレス１が既に使用されています。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_max",
                "message_value" => "請求先メールアドレス１は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress2_email",
                "message_value" => "請求先メールアドレス２は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress2_max",
                "message_value" => "請求先メールアドレス２は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_pass_required",
                "message_value" => "請求先パスワードは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_pass_required_max",
                "message_value" => "請求先パスワードは64文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_zip_code_numeric",
                "message_value" => "請求先郵便番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_zip_code_digits",
                "message_value" => "請求先郵便番号は全部7桁で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_address_required",
                "message_value" => "請求先番地・ビル名は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_address_max",
                "message_value" => "請求先番地は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_building_max",
                "message_value" => "請求先ビルは255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_phone_no_numeric",
                "message_value" => "請求先自宅電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_phone_no_digits_between",
                "message_value" => "請求先自宅電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_handset_no_numeric",
                "message_value" => "請求先携帯電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_handset_no_digits_between",
                "message_value" => "請求先携帯電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_code_required",
                "message_value" => "金融機関コードは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_code_max",
                "message_value" => "金融機関コードは4文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_code_numeric",
                "message_value" => "金融機関コードは数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_name_required",
                "message_value" => "金融機関名は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_name_max",
                "message_value" => "金融機関名は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_code_required",
                "message_value" => "支店コードは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_code_max",
                "message_value" => "支店コードは3文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_code_numeric",
                "message_value" => "支店コードは数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_name_required",
                "message_value" => "支店名は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_name_max",
                "message_value" => "支店名は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_type_required",
                "message_value" => "口座種別は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_number_required",
                "message_value" => "口座番号は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_number_max",
                "message_value" => "口座番号は7桁以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_number_numeric",
                "message_value" => "口座番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_required",
                "message_value" => "口座名義は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_max",
                "message_value" => "口座名義は30文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_kana_required",
                "message_value" => "口座名義（カナ）は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_kana_max",
                "message_value" => "口座名義（カナ）は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_kigou_required",
                "message_value" => "通帳記号は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_kigou_max",
                "message_value" => "通帳記号は5文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_kigou_numeric",
                "message_value" => "通帳記号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_number_required",
                "message_value" => "通帳番号は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_number_max",
                "message_value" => "通帳番号は8文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_number_numeric",
                "message_value" => "通帳番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_name_required",
                "message_value" => "通帳名義は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_name_max",
                "message_value" => "通帳名義は30文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "update_success",
                "message_value" => "保存されました",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_payment_month_required",
                "message_value" => "割増・割引の対象月は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_payment_adjust_required",
                "message_value" => "割増・割引の摘要は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_adjust_payment_fee_required",
                "message_value" => "割増・割引の金額は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_adjust_payment_fee_numeric",
                "message_value" => "割増・割引の金額は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_total_member_required",
                "message_value" => "法人の場合は人数が必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_total_member_numeric",
                "message_value" => "法人の場合は人数が数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "tel",
                "message_value" => "TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "participant_class",
                "message_value" => "所属プラン",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "participant_event",
                "message_value" => "参加イベント",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "participant_program",
                "message_value" => "参加プログラム",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "shift_jis",
                "message_value" => "Shift-jis",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "utf_8",
                "message_value" => "UTF-8",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "csv_no",
                "message_value" => "No.",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "csv_required_mark",
                "message_value" => "◎",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_img_image",
                "message_value" => "画像は正しく選択してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "who_export_csv",
                "message_value" => "さんがCSVを出力しました。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "write_log_warning",
                "message_value" => "CSVを出力すると履歴を書きますので、ご注意ください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'write_log_warning',
            ])->first();
            $message->message_value = 'CSVファイルを出力しますと、出力履歴に記録されます。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "select_encode_type",
                "message_value" => "ダウンロードする形式を選択",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'select_encode_type',
            ])->first();
            $message->message_value = 'ダウンロードする文字コードを選択してください。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "history_log",
                "message_value" => "出力履歴",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_type_exist",
                "message_value" => "会員種別は存在しないです。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "row",
                "message_value" => "行目",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "import_csv_total_row",
                "message_value" => "件数:",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "import_csv_total_error",
                "message_value" => "エラー:",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "import_csv_total_inserted",
                "message_value" => "更新した:",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "placeholder_input_temp",
                "message_value" => "を入力してください",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "previous_text",
                "message_value" => "前へ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "next_text",
                "message_value" => "次へ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_city_id_required",
                "message_value" => "会員の市区町村名を選択してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_city_id_required",
                "message_value" => "請求先の市区町村名を選択してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_kana_regex",
                "message_value" => "会員フリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_name_kana_regex",
                "message_value" => "請求先フリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_name_regex",
                "message_value" => "金融機関名は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_name_regex",
                "message_value" => "支店名は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_regex",
                "message_value" => "口座名義は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_name_regex",
                "message_value" => "通帳名義は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person",
                "message_value" => "人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_total_member_min",
                "message_value" => "人数は１より入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "contact_phone_number",
                "message_value" => "連絡先電話番号",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_regex",
                "message_value" => "会員の連絡先電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_handset_no_regex",
                "message_value" => "会員の携帯電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_phone_no_regex",
                "message_value" => "請求先の連絡先電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_handset_no_regex",
                "message_value" => "請求先の携帯電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "student_no_sample_code",
                "message_value" => "○○○○〇",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "student_no_auto_generate",
                "message_value" => "※会員番号は自動発番です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "address_number_title",
                "message_value" => "番地",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "building_title",
                "message_value" => "ビル",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                    "message_file_id" => 1,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "enter_memo_placeholder",
                    "message_value" => "入会理由を入力してください",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
// 担当者情報 message
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_max",
                "message_value" => "担当者情報%dの担当者名は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_kana_max",
                "message_value" => "担当者情報%dのフリガナは255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_kana_regex",
                "message_value" => "担当者情報%dのフリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_position_max",
                "message_value" => "担当者情報%dの役職は64文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_office_name_max",
                "message_value" => "担当者情報%dの部署名は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_office_tel_regex",
                "message_value" => "担当者情報%dの部署TELは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_email_format",
                "message_value" => "担当者情報%dの部署メールは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_email_required",
                "message_value" => "担当者情報%dの部署メールは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
// end ---- 担当者情報 message
// 代表者情報 message
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_max",
                "message_value" => "代表者情報の代表者名は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_kana_max",
                "message_value" => "代表者情報のフリガナは255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_kana_regex",
                "message_value" => "代表者情報のフリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_position_max",
                "message_value" => "代表者情報の役職は64文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_tel_regex",
                "message_value" => "代表者情報のTELは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_email_format",
                "message_value" => "代表者情報のメールは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
// end ---- 代表者情報 message
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "corporation_info_title",
                "message_value" => "法人情報",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_info_title",
                "message_value" => "代表者情報",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_title",
                "message_value" => "代表者名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "position_title",
                "message_value" => "役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_mail_title",
                "message_value" => "代表者メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_tel_title",
                "message_value" => "代表者TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_info_title",
                "message_value" => "担当者情報",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_name_title",
                "message_value" => "担当者名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "office_name_title",
                "message_value" => "部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "office_tel_title",
                "message_value" => "部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_mail_title",
                "message_value" => "担当者メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "add_person_in_charge",
                "message_value" => "担当者を追加",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "furigana_title",
                "message_value" => "フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
// end --- corporation info message
// parent's other message
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_addressee_title",
                "message_value"     => "送付先宛名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_name_title",
                "message_value"     => "会員名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_addressee_input_title",
                "message_value"     => "送付先宛名を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_addressee_location_title",
                "message_value"     => "送付先住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_registered_address_title",
                "message_value"     => "会員登録住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
// end ---- parent's other message
        try{
            message::create([
                    "message_file_id" => 1,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "save_confirm",
                    "message_value" => "設定内容を登録します。 よろしいですか？",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
                'message_key'       => 'invoice_year_month_name',
                'message_value'     => '分請求',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.student',
                'message_key'     => 'invoice_year_month_name',
            ])->first();
            $message->message_value = '分請求';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
                'message_key'       => 'invoice_not_paid',
                'message_value'     => '未払い',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.student',
                'message_key'     => 'invoice_not_paid',
            ])->first();
            $message->message_value = '未払い';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
                'message_key'       => 'invoice_history_situation',
                'message_value'     => '請求状況',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.student',
                'message_key'     => 'invoice_history_situation',
            ])->first();
            $message->message_value = '請求状況';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id' => 1,
                    'screen_key'      => 'school.student',
                    'message_key'     => 'valid_date_title',
            ])->first();
            $message->message_value = '会員有効期限';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.student',
                    'message_key' => 'really_want_to_delete_title',
            ])->first();
            $message->message_value = '削除すると、このデータは使用できなくなりますが、よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "invoice_history_situation_new",
                    "message_value"     => "過去1年分の支払状況",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "bank_account_name_kana_warning",
                    "message_value"     => "※全角文字で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "bank_account_name_kana_warning",
                "message_value"     => "※全角文字で入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_title_hiragana",
                "message_value"     => "会員ひらがな",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "guardian_title_hiragana",
                "message_value"     => "請求先名前ひらがな",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_pass_title",
                "message_value"     => "会員パスワード",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "ginkou_type_title",
                "message_value"     => "種別",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
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
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
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
                'screen_key' => 'school.student',
                'message_key' => 'credit_card_method_title',
            ])->first();
            $message->message_value = 'クレジットカード決済（都度）';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'edit_basic_info_title',
            ])->first();
            $message->message_value = '編集';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "email_content_title",
                "message_value" => "※このメールアドレスが、送信メールの発信元となります。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'student_no_description',
            ])->first();
            $message->message_value = '最大13桁の英数字';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "join_date_description",
                "message_value" => "西暦年-月-日の形式であること",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "withdraw_date_description",
                "message_value" => "西暦年-月-日の形式であること",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "copy_and_use_member_info",
                "message_value" => "会員情報をコピーして使用",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "copy_and_use_member_info_description",
                "message_value" => "会員情報と同じ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_office",
                "message_value" => "会員勤務先",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "membership",
                "message_value" => "会員役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_school",
                "message_value" => "会員出身校",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "billing_name_description",
                "message_value" => "請求先名前が設定されているとき、必須です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "name_of_representative",
                "message_value" => "代表者名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "name_of_representative_description",
                "message_value" => "代表者名称が設定されていない場合、代表者情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent",
                "message_value" => "代表者フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent_title",
                "message_value" => "代表者役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent_email",
                "message_value" => "代表者メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_from_represent",
                "message_value" => "代表者にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent_tel",
                "message_value" => "代表者TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name1",
                "message_value" => "担当者1名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name1_description",
                "message_value" => "担当者1名称が設定されていない場合、担当者1情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_kana1",
                "message_value" => "担当者1フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_one_department_tel",
                "message_value" => "担当者1部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "contact_person_one_email",
                "message_value" => "担当者1メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_to_person_in_charge_one",
                "message_value" => "担当者1にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_name_in_charge",
                "message_value" => "担当者2名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_name_in_charge_description",
                "message_value" => "担当者2名称が設定されていない場合、担当者2情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_two",
                "message_value" => "担当者2フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_position_in_charge",
                "message_value" => "担当者2役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_copy_person_in_charge",
                "message_value" => "担当者2部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_two_department_tel",
                "message_value" => "担当者2部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_two_email",
                "message_value" => "担当者2メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_to_person_in_charge_two",
                "message_value" => "担当者2にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_name_in_charge",
                "message_value" => "担当者3名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_name_in_charge_description",
                "message_value" => "担当者3名称が設定されていない場合、担当者3情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_three",
                "message_value" => "担当者3フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_position_in_charge",
                "message_value" => "担当者3役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_copy_person_in_charge",
                "message_value" => "担当者3部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_three_department_tel",
                "message_value" => "担当者3部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_three_email",
                "message_value" => "担当者3メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_to_person_in_charge_three",
                "message_value" => "担当者3にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "control_item",
                "message_value" => "管理項目",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "control_item_description",
                "message_value" => "管理項目が登録されている場合CSVヘッダーに設定（可変）",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_office_name1",
                "message_value" => "担当者1部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_position1",
                "message_value" => "担当者1役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_email_type",
                "message_value" => "メールアドレスは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        // ===========================
        // message_file_id = 2 : en ==
        // ===========================
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_no_description',
            ])->first();
            $message->message_value = '最大13桁の英数字';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_type_required_title',
            ])->first();
            $message->message_value = '【会員】会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_name_required_title',
            ])->first();
            $message->message_value = '【会員】名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'students_furigana_required_title',
            ])->first();
            $message->message_value = '【会員】フリガナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_nickname_required_title',
            ])->first();
            $message->message_value = '【会員】ニックネームが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_email_address_required_title',
            ])->first();
            $message->message_value = '【会員】メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_birthday_year_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（年）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_birthday_month_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（月）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_birthday_day_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（日）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_school_name_required_title',
            ])->first();
            $message->message_value = '【会員】学校名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'student_section_required_title',
            ])->first();
            $message->message_value = '【会員】会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'state_name_exam_region_1_required_title',
            ])->first();
            $message->message_value = '受験地域1都道府県名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'guardian_information_required_title',
            ])->first();
            $message->message_value = '請求先情報が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'csv_file_required_title',
            ])->first();
            $message->message_value = 'CSVファイルが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_type_required_title',
            ])->first();
            $message->message_value = '【会員】会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_name_required_title',
            ])->first();
            $message->message_value = '【会員】名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'members_furigana_required_title',
            ])->first();
            $message->message_value = '【会員】フリガナが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_nickname_required_title',
            ])->first();
            $message->message_value = '会員ニックネームが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_email_address_required_title',
            ])->first();
            $message->message_value = '【会員】メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_birthday_year_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（年）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_birthday_month_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（月）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_birthday_day_required_title',
            ])->first();
            $message->message_value = '【会員】生年月日（日）が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_school_name_required_title',
            ])->first();
            $message->message_value = '【会員】学校名が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'member_section_required_title',
            ])->first();
            $message->message_value = '【会員】会員区分が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'billing_information_required_title',
            ])->first();
            $message->message_value = '請求先情報が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "require_target_month",
                "message_value"     => "【会員】番目の%s対象月が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'require_target_month',
            ])->first();
            $message->message_value = '【会員】%s番目の対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'require_target_month',
            ])->first();
            $message->message_value = '【会員】%s番目の対象月が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "require_abstract",
                "message_value"     => "【会員】番目の%s摘要が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_abstract',
            ])->first();
            $message->message_value='【会員】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_abstract',
            ])->first();
            $message->message_value='【会員】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "require_amount_of_money",
                "message_value"     => "【会員】番目の%s金額が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'require_amount_of_money',
            ])->first();
            $message->message_value = '【会員】%s番目の金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'require_amount_of_money',
            ])->first();
            $message->message_value = '【会員】%s番目の金額が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'post_office',
            ])->first();
            $message->message_value = 'ゆうちょ銀行';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'main_title',
            ])->first();
            $message->message_value = '会員情報';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "label_export_csv_title",
                "message_value"     => "ラベル用CSV出力",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "parent_name",
                "message_value" => "請求先氏名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "parent_mail",
                "message_value" => "メールアドレス",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "",
                "message_value" => "",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "csv_template",
                "message_value" => "CSVテンプレート",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_student",
                "message_value" => "全員",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_under_contract",
                "message_value" => "契約中",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_end_contract",
                "message_value" => "契約終了",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "require_home_telephone",
                "message_value" => "自宅電話は必須です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "select",
                "message_value" => "選択",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "reset_parent",
                "message_value" => "レセット",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "first_name_kana_title",
                "message_value" => "フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "btn_copy_student_address",
                "message_value" => "会員の住所をコピー",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_personal",
                "message_value" => "個人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_corp",
                "message_value" => "法人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "total_member",
                "message_value" => "人数",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_type_required",
                "message_value" => "会員種別は必須です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'err_student_type_required',
            ])->first();
            $message->message_value = '【会員】会員種別が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_required",
                "message_value" => "会員名前は必須です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'err_student_name_required',
            ])->first();
            $message->message_value = '【会員】名前が未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_max",
                "message_value" => "会員名前は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_kana_max",
                "message_value" => "会員フリガナは255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_romaji_max",
                "message_value" => "会員ローマ字は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_required",
                "message_value" => "会員メールアドレスは必須です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'err_student_mailaddress_required',
            ])->first();
            $message->message_value = '【会員】メールアドレスが未入力です。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_format",
                "message_value" => "会員メールアドレスの形式が不正です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_unique",
                "message_value" => "会員メールアドレスが既に使用されています。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_mailaddress_max",
                "message_value" => "会員メールアドレスはは64文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_birthday_required",
                "message_value" => "会員生年月は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_birthday_format",
                "message_value" => "会員生年月は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_enter_date_format",
                "message_value" => "入会日は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_resign_date_format",
                "message_value" => "退会日は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_zip_code_numeric",
                "message_value" => "郵便番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_zip_code_digits",
                "message_value" => "郵便番号は全部7桁で入力してください",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_address_max",
                "message_value" => "会員番地は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_required",
                "message_value" => "会員自宅電話は必要です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_numeric",
                "message_value" => "会員自宅電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_digits_between",
                "message_value" => "会員自宅電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_handset_no_numeric",
                "message_value" => "会員携帯電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_handset_no_digits_between",
                "message_value" => "会員携帯電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_name_required",
                "message_value" => "請求先名前は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_name_max",
                "message_value" => "請求先名前は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_name_kana_max",
                "message_value" => "請求先フリガナは255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_required",
                "message_value" => "請求先メールアドレス１は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_email",
                "message_value" => "請求先メールアドレス１は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_unique",
                "message_value" => "請求先メールアドレス１が既に使用されています。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress1_max",
                "message_value" => "請求先メールアドレス１は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress2_email",
                "message_value" => "請求先メールアドレス２は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_mailaddress2_max",
                "message_value" => "請求先メールアドレス２は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_pass_required",
                "message_value" => "請求先パスワードは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_pass_required_max",
                "message_value" => "請求先パスワードは64文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_zip_code_numeric",
                "message_value" => "請求先郵便番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_zip_code_digits",
                "message_value" => "請求先郵便番号は全部7桁で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_address_required",
                "message_value" => "請求先番地・ビル名は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_address_max",
                "message_value" => "請求先番地は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_building_max",
                "message_value" => "請求先ビルは255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_phone_no_numeric",
                "message_value" => "請求先自宅電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_phone_no_digits_between",
                "message_value" => "請求先自宅電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_handset_no_numeric",
                "message_value" => "請求先携帯電話は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_handset_no_digits_between",
                "message_value" => "請求先携帯電話は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_code_required",
                "message_value" => "金融機関コードは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_code_max",
                "message_value" => "金融機関コードは4文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_code_numeric",
                "message_value" => "金融機関コードは数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_name_required",
                "message_value" => "金融機関名は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_name_max",
                "message_value" => "金融機関名は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_code_required",
                "message_value" => "支店コードは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_code_max",
                "message_value" => "支店コードは3文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_code_numeric",
                "message_value" => "支店コードは数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_name_required",
                "message_value" => "支店名は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_name_max",
                "message_value" => "支店名は15文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_type_required",
                "message_value" => "口座種別は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_number_required",
                "message_value" => "口座番号は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_number_max",
                "message_value" => "口座番号は7桁以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_number_numeric",
                "message_value" => "口座番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_required",
                "message_value" => "口座名義は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_max",
                "message_value" => "口座名義は30文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_kana_required",
                "message_value" => "口座名義（カナ）は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_kana_max",
                "message_value" => "口座名義（カナ）は255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_kigou_required",
                "message_value" => "通帳記号は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_kigou_max",
                "message_value" => "通帳記号は5文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_kigou_numeric",
                "message_value" => "通帳記号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_number_required",
                "message_value" => "通帳番号は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_number_max",
                "message_value" => "通帳番号は8文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_number_numeric",
                "message_value" => "通帳番号は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_name_required",
                "message_value" => "通帳名義は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_name_max",
                "message_value" => "通帳名義は30文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "update_success",
                "message_value" => "保存されました",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_payment_month_required",
                "message_value" => "割増・割引の対象月は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_payment_adjust_required",
                "message_value" => "割増・割引の摘要は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_adjust_payment_fee_required",
                "message_value" => "割増・割引の金額は必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_adjust_payment_fee_numeric",
                "message_value" => "割増・割引の金額は数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_total_member_required",
                "message_value" => "法人の場合は人数が必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_total_member_numeric",
                "message_value" => "法人の場合は人数が数字で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "tel",
                "message_value" => "TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "participant_class",
                "message_value" => "所属プラン",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "participant_event",
                "message_value" => "参加イベント",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "participant_program",
                "message_value" => "参加プログラム",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "shift_jis",
                "message_value" => "Shift-jis",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "utf_8",
                "message_value" => "UTF-8",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "csv_no",
                "message_value" => "No.",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "csv_required_mark",
                "message_value" => "◎",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_img_image",
                "message_value" => "画像は正しく選択してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "who_export_csv",
                "message_value" => "さんがCSVを出力しました。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "write_log_warning",
                "message_value" => "CSVを出力すると履歴を書きますので、ご注意ください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'write_log_warning',
            ])->first();
            $message->message_value = 'CSVファイルを出力しますと、出力履歴に記録されます。';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "select_encode_type",
                "message_value" => "ダウンロードする形式を選択",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "history_log",
                "message_value" => "出力履歴",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_type_exist",
                "message_value" => "会員種別は存在しないです。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "row",
                "message_value" => "行目",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "import_csv_total_row",
                "message_value" => "件数:",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "import_csv_total_error",
                "message_value" => "エラー:",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "import_csv_total_inserted",
                "message_value" => "更新した:",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "placeholder_input_temp",
                "message_value" => "を入力してください",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "previous_text",
                "message_value" => "前へ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "next_text",
                "message_value" => "次へ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_city_id_required",
                "message_value" => "会員の市区町村名を選択してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_city_id_required",
                "message_value" => "請求先の市区町村名を選択してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_name_kana_regex",
                "message_value" => "会員フリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_parent_name_kana_regex",
                "message_value" => "請求先フリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_branch_name_regex",
                "message_value" => "金融機関名は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_name_regex",
                "message_value" => "支店名は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_bank_account_name_regex",
                "message_value" => "口座名義は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_post_account_name_regex",
                "message_value" => "通帳名義は正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person",
                "message_value" => "人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_total_member_min",
                "message_value" => "人数は１より入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "contact_phone_number",
                "message_value" => "連絡先電話番号",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_phone_no_regex",
                "message_value" => "会員の連絡先電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_handset_no_regex",
                "message_value" => "会員の携帯電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_phone_no_regex",
                "message_value" => "請求先の連絡先電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_handset_no_regex",
                "message_value" => "請求先の携帯電話番号が不正です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "student_no_sample",
                "message_value" => "○○○○〇",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "student_no_auto_generate",
                "message_value" => "※会員番号は自動発番です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "address_number_title",
                "message_value" => "番地",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "building_title",
                "message_value" => "ビル",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_building_max",
                "message_value" => "会員ビルは255文字以内入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        //2017-07-12 update message 名前(フリガナ) →　会員名
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'name_furigana',
            ])->first();
            $message->message_value = '会員名';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'name_furigana',
            ])->first();
            $message->message_value = '会員名';
            $message->save();
        } catch (\Exception $e) {

        }

        //2017-07-12 update message　種別　→　会員種別
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'category_title',
            ])->first();
            $message->message_value = '会員種別';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'category_title',
            ])->first();
            $message->message_value = '会員種別';
            $message->save();
        } catch (\Exception $e) {

        }

        // 2017-07-12 Add message 個人／法人
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "student_category_title",
                "message_value" => "個人／法人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "student_category_title",
                "message_value" => "個人／法人",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        //2017-07-12 update message　レセット　→　リセット
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'reset_parent',
            ])->first();
            $message->message_value = 'リセット';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'reset_parent',
            ])->first();
            $message->message_value = 'リセット';
            $message->save();
        } catch (\Exception $e) {

        }

        // 2017-07-12 Add message 会員番号が存在しています。
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_no_unique",
                "message_value" => "会員番号が存在しています。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_no_unique",
                "message_value" => "会員番号が存在しています。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 1,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_no_required",
                "message_value" => "【会員】会員番号が未入力です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_student_no_required",
                "message_value" => "【会員】会員番号が未入力です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        //2017-07-14 add placeholder name search
        try{
            message::create([
                    "message_file_id" => 1,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "student_name_placeholder",
                    "message_value" => "会員名(フリガナ）入力",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                    "message_file_id" => 2,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "student_name_placeholder",
                    "message_value" => "会員名(フリガナ）入力",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id" => 2,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "enter_memo_placeholder",
                    "message_value" => "入会理由を入力してください",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id" => 2,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "save_confirm",
                    "message_value" => "設定内容を登録します。 よろしいですか？",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'select_encode_type',
            ])->first();
            $message->message_value = 'ダウンロードする文字コードを選択してください。';
            $message->save();
        } catch (\Exception $e) {

        }

        // 担当者情報 message
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_max",
                "message_value" => "担当者情報%dの担当者名は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_kana_max",
                "message_value" => "担当者情報%dのフリガナは255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_kana_regex",
                "message_value" => "担当者情報%dのフリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_position_max",
                "message_value" => "担当者情報%dの役職は64文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_office_name_max",
                "message_value" => "担当者情報%dの部署名は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_office_tel_regex",
                "message_value" => "担当者情報%dの部署TELは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_email_format",
                "message_value" => "担当者情報%dの部署メールは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_email_required",
                "message_value" => "担当者情報%dの部署メールは必要です。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
// end ---- 担当者情報 message
// 代表者情報 message
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_max",
                "message_value" => "代表者情報の代表者名は255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_kana_max",
                "message_value" => "代表者情報のフリガナは255文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_kana_regex",
                "message_value" => "代表者情報のフリガナは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_position_max",
                "message_value" => "代表者情報の役職は64文字以内で入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_tel_regex",
                "message_value" => "代表者情報のTELは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_email_format",
                "message_value" => "代表者情報のメールは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
// end ---- 代表者情報 message
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "corporation_info_title",
                "message_value" => "法人情報",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_info_title",
                "message_value" => "代表者情報",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_name_title",
                "message_value" => "代表者名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "position_title",
                "message_value" => "役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_mail_title",
                "message_value" => "代表者メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "representative_tel_title",
                "message_value" => "代表者TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_info_title",
                "message_value" => "担当者情報",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_name_title",
                "message_value" => "担当者名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "office_name_title",
                "message_value" => "部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "office_tel_title",
                "message_value" => "部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_mail_title",
                "message_value" => "担当者メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "add_person_in_charge",
                "message_value" => "担当者を追加",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "furigana_title",
                "message_value" => "フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
// end --- corporation info message
// parent's other message
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_addressee_title",
                "message_value"     => "送付先宛名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_name_title",
                "message_value"     => "会員名",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_addressee_input_title",
                "message_value"     => "送付先宛名を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_addressee_location_title",
                "message_value"     => "送付先住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_registered_address_title",
                "message_value"     => "会員登録住所",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
                'message_key'       => 'invoice_year_month_name',
                'message_value'     => '分請求',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.student',
                'message_key'     => 'invoice_year_month_name',
            ])->first();
            $message->message_value = '分請求';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
                'message_key'       => 'invoice_not_paid',
                'message_value'     => '未払い',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.student',
                'message_key'     => 'invoice_not_paid',
            ])->first();
            $message->message_value = '未払い';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
                'message_key'       => 'invoice_history_situation',
                'message_value'     => '請求状況',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.student',
                'message_key'     => 'invoice_history_situation',
            ])->first();
            $message->message_value = '請求状況';
            $message->save();
        } catch (\Exception $e) {

        }
// end ---- parent's other message
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.student',
                    'message_key' => 'really_want_to_delete_title',
            ])->first();
            $message->message_value = '削除すると、このデータは使用できなくなりますが、よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'student_name_placeholder',
            ])->first();
            $message->message_value='会員氏名の一部（漢字・カナ）を入力';
            $message->save();
        }catch(\Exception$e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'student_name_placeholder',
            ])->first();
            $message->message_value='会員氏名の一部（漢字・カナ）を入力';
            $message->save();
        }catch(\Exception$e){

        }
// update - thangqg - 2017/09/29
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_parent_name_required',
            ])->first();
            $message->message_value='【請求先】名前が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_parent_name_required',
            ])->first();
            $message->message_value='【請求先】名前が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_parent_mailaddress1_required',
            ])->first();
            $message->message_value='【請求先】メールアドレス１が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_parent_mailaddress1_required',
            ])->first();
            $message->message_value='【請求先】メールアドレス１が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_student_birthday_required',
            ])->first();
            $message->message_value='【会員】生年月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_student_birthday_required',
            ])->first();
            $message->message_value='【会員】生年月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_parent_pass_required',
            ])->first();
            $message->message_value='【請求先】パスワードが未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'err_parent_pass_required',
            ])->first();
            $message->message_value='【請求先】パスワードが未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_home_telephone',
            ])->first();
            $message->message_value='【会員】連絡先電話番号が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_home_telephone',
            ])->first();
            $message->message_value='【会員】連絡先電話番号が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_home_telephone',
            ])->first();
            $message->message_value='【会員】連絡先電話番号が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_home_telephone',
            ])->first();
            $message->message_value='【会員】連絡先電話番号が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }

        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_target_month',
            ])->first();
            $message->message_value='【会員】%s番目の対象月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_target_month',
            ])->first();
            $message->message_value='【会員】%s番目の対象月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }

        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_abstract',
            ])->first();
            $message->message_value='【会員】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_abstract',
            ])->first();
            $message->message_value='【会員】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }

        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_amount_of_money',
            ])->first();
            $message->message_value='【会員】%s番目の金額が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'require_amount_of_money',
            ])->first();
            $message->message_value='【会員】%s番目の金額が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_require_target_month",
                "message_value"     => "【請求先】番目の%s対象月が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_target_month',
            ])->first();
            $message->message_value='【請求先】%s番目の対象月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_target_month',
            ])->first();
            $message->message_value='【請求先】%s番目の対象月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_require_abstract",
                "message_value"     => "【請求先】番目の%s摘要が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_abstract',
            ])->first();
            $message->message_value='【請求先】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_abstract',
            ])->first();
            $message->message_value='【請求先】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_require_amount_of_money",
                "message_value"     => "【請求先】番目の%s金額が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_amount_of_money',
            ])->first();
            $message->message_value='【請求先】%s番目の金額が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_amount_of_money',
            ])->first();
            $message->message_value='【請求先】%s番目の金額が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_require_target_month",
                "message_value"     => "【請求先】番目の%s対象月が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_target_month',
            ])->first();
            $message->message_value='【請求先】%s番目の対象月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_target_month',
            ])->first();
            $message->message_value='【請求先】%s番目の対象月が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_require_abstract",
                "message_value"     => "【請求先】番目の%s摘要が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_abstract',
            ])->first();
            $message->message_value='【請求先】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_abstract',
            ])->first();
            $message->message_value='【請求先】%s番目の摘要が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_require_amount_of_money",
                "message_value"     => "【請求先】番目の%s金額が未入力です。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_amount_of_money',
            ])->first();
            $message->message_value='【請求先】%s番目の金額が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_require_amount_of_money',
            ])->first();
            $message->message_value='【請求先】%s番目の金額が未入力です。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "amount_money_numeric",
                "message_value"     => "【会員】番目の%s金額には数値を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'amount_money_numeric',
            ])->first();
            $message->message_value='【会員】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'amount_money_numeric',
            ])->first();
            $message->message_value='【会員】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "amount_money_numeric",
                "message_value"     => "【会員】番目の%s金額には数値を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'amount_money_numeric',
            ])->first();
            $message->message_value='【会員】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'amount_money_numeric',
            ])->first();
            $message->message_value='【会員】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_amount_money_numeric",
                "message_value"     => "【請求先】番目の%s金額には数値を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_amount_money_numeric',
            ])->first();
            $message->message_value='【請求先】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_amount_money_numeric',
            ])->first();
            $message->message_value='【請求先】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "parent_amount_money_numeric",
                "message_value"     => "【請求先】番目の%s金額には数値を入力してください。",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            $message=Message::where([
                'message_file_id'   => 1,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_amount_money_numeric',
            ])->first();
            $message->message_value='【請求先】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
        try{
            $message=Message::where([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'message_key'       => 'parent_amount_money_numeric',
            ])->first();
            $message->message_value='【請求先】%s番目の金額には数値を入力してください。';
            $message->save();
        }catch(\Exception$e){
        }
// update - thangqg - 2017/09/29

        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "invoice_history_situation_new",
                    "message_value"     => "過去1年分の支払状況",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "bank_account_name_kana_warning",
                    "message_value"     => "※全角文字で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        //
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "generate_address",
                    "message_value"     => "〒→住所変換",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "generate_address",
                    "message_value"     => "〒→住所変換",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        //
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "valid_date_title",
                    "message_value"     => "有効期限",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "valid_date_title",
                    "message_value"     => "有効期限",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "total_title",
                    "message_value"     => "合計",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "total_title",
                    "message_value"     => "合計",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "use_as_login_title",
                    "message_value"     => "※ログインIDとして、使用されます。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "use_as_login_title",
                    "message_value"     => "※ログインIDとして、使用されます。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "reach_limit_number_active",
                    "message_value"     => "有効会員数を超えしました。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "reach_limit_number_active",
                    "message_value"     => "有効会員数を超えしました。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "reach_limit_number_register",
                    "message_value"     => "会員数を超えしました。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "reach_limit_number_register",
                    "message_value"     => "会員数を超えしました。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        //
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "password_regex_warning",
                    "message_value"     => "半角英数文字または特殊文字(-,_,.,$,#,:@,!)で8文字以上16文字以下",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "password_regex_warning",
                    "message_value"     => "半角英数文字または特殊文字(-,_,.,$,#,:@,!)で8文字以上16文字以下",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.student',
                    'message_key' => 'err_parent_pass_required_max',
            ])->first();
            $message->message_value = '請求先パスワードは8文字以上16文字以下で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.student',
                    'message_key' => 'err_parent_pass_required_max',
            ])->first();
            $message->message_value = '請求先パスワードは8文字以上16文字以下で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        //
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "member_password_required",
                    "message_value"     => "会員パスワードは必要です",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "member_password_required",
                    "message_value"     => "会員パスワードは必要です",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_member_pass_required_min",
                    "message_value"     => "会員パスワードは8文字以上16文字以下で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_member_pass_required_min",
                    "message_value"     => "会員パスワードは8文字以上16文字以下で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_parent_pass_required_min",
                    "message_value"     => "請求先パスワードは8文字以上16文字以下で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_parent_pass_required_min",
                    "message_value"     => "請求先パスワードは8文字以上16文字以下で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_member_pass_required_max",
                    "message_value"     => "会員パスワードは8文字以上16文字以下で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_member_pass_required_max",
                    "message_value"     => "会員パスワードは8文字以上16文字以下で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_member_pass_regex",
                    "message_value"     => "会員パスワード は半角英数文字または特殊文字(-,_,.,$,#,:@,!)で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_member_pass_regex",
                    "message_value"     => "会員パスワード は半角英数文字または特殊文字(-,_,.,$,#,:@,!)で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 1,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_parent_pass_regex",
                    "message_value"     => "請求先パスワード は半角英数文字または特殊文字(-,_,.,$,#,:@,!)で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id"   => 2,
                    "screen_key"        => "school.student",
                    "screen_value"      => "会員管理",
                    "message_key"       => "err_parent_pass_regex",
                    "message_value"     => "請求先パスワード は半角英数文字または特殊文字(-,_,.,$,#,:@,!)で入力してください。",
                    "comment"           => "",
                    "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                    'message_file_id' => 2,
                    'screen_key'      => 'school.student',
                    'message_key'     => 'valid_date_title',
            ])->first();
            $message->message_value = '会員有効期限';
            $message->save();
        } catch (\Exception $e) {

        }

        // Toran fix bank account message 02-27
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.student',
                'message_key' => 'bank_account_name_kana_warning',
            ])->first();
            $message->message_value = '※全角カタカナ文字で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'bank_account_name_kana_warning',
            ])->first();
            $message->message_value = '※全角カタカナ文字で入力してください。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_title_hiragana",
                "message_value"     => "会員ひらがな",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "guardian_title_hiragana",
                "message_value"     => "請求先名前ひらがな",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 2,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "student_pass_title",
                "message_value"     => "会員パスワード",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id"   => 1,
                "screen_key"        => "school.student",
                "screen_value"      => "会員管理",
                "message_key"       => "ginkou_type_title",
                "message_value"     => "種別",
                "comment"           => "",
                "register_admin"    => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
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
                'screen_key'        => 'school.student',
                'screen_value'      => '会員管理',
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
                'screen_key' => 'school.student',
                'message_key' => 'credit_card_method_title',
            ])->first();
            $message->message_value = 'クレジットカード決済（都度）';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            message::create([
                    "message_file_id" => 1,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "error_email_student",
                    "message_value" => "会員メールアドレスが存在しています",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                    "message_file_id" => 2,
                    "screen_key" => "school.student",
                    "screen_value" => "会員管理",
                    "message_key" => "error_email_student",
                    "message_value" => "会員メールアドレスが存在しています",
                    "comment" => "",
                    "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.student',
                'message_key' => 'edit_basic_info_title',
            ])->first();
            $message->message_value = '編集';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
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
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "join_date_description",
                "message_value" => "西暦年-月-日の形式であること",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "withdraw_date_description",
                "message_value" => "西暦年-月-日の形式であること",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "copy_and_use_member_info",
                "message_value" => "会員情報をコピーして使用",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "copy_and_use_member_info_description",
                "message_value" => "会員情報と同じ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_office",
                "message_value" => "会員勤務先",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "membership",
                "message_value" => "会員役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "member_school",
                "message_value" => "会員出身校",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "billing_name_description",
                "message_value" => "請求先名前が設定されているとき、必須です",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "name_of_representative",
                "message_value" => "代表者名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "name_of_representative_description",
                "message_value" => "代表者名称が設定されていない場合、代表者情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent",
                "message_value" => "代表者フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent_title",
                "message_value" => "代表者役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent_email",
                "message_value" => "代表者メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_from_represent",
                "message_value" => "代表者にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "represent_tel",
                "message_value" => "代表者TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name1",
                "message_value" => "担当者1名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name1_description",
                "message_value" => "担当者1名称が設定されていない場合、担当者1情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_name_kana1",
                "message_value" => "担当者1フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_one_department_tel",
                "message_value" => "担当者1部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "contact_person_one_email",
                "message_value" => "担当者1メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_to_person_in_charge_one",
                "message_value" => "担当者1にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_name_in_charge",
                "message_value" => "担当者2名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_name_in_charge_description",
                "message_value" => "担当者2名称が設定されていない場合、担当者2情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_two",
                "message_value" => "担当者2フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_position_in_charge",
                "message_value" => "担当者2役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "two_copy_person_in_charge",
                "message_value" => "担当者2部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_two_department_tel",
                "message_value" => "担当者2部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_two_email",
                "message_value" => "担当者2メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_to_person_in_charge_two",
                "message_value" => "担当者2にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_name_in_charge",
                "message_value" => "担当者3名称",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_name_in_charge_description",
                "message_value" => "担当者3名称が設定されていない場合、担当者3情報を無視する",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_three",
                "message_value" => "担当者3フリガナ",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_position_in_charge",
                "message_value" => "担当者3役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "three_copy_person_in_charge",
                "message_value" => "担当者3部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_three_department_tel",
                "message_value" => "担当者3部署TEL",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_in_charge_three_email",
                "message_value" => "担当者3メール",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "receive_email_to_person_in_charge_three",
                "message_value" => "担当者3にメール受信",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "control_item",
                "message_value" => "管理項目",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "control_item_description",
                "message_value" => "管理項目が登録されている場合CSVヘッダーに設定（可変）",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }

        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_office_name1",
                "message_value" => "担当者1部署名",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "person_position1",
                "message_value" => "担当者1役職",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
        try{
            message::create([
                "message_file_id" => 2,
                "screen_key" => "school.student",
                "screen_value" => "会員管理",
                "message_key" => "err_email_type",
                "message_value" => "メールアドレスは正しく入力してください。",
                "comment" => "",
                "register_admin" => 1,
            ]);
        } catch (\Exception $e){

        }
    }
}
