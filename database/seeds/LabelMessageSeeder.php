<?php

use Illuminate\Database\Seeder;
use App\Message;

class LabelMessageSeeder extends Seeder
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
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_save_template_success_title',
                'message_value'     => 'テンプレートは保存しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'lb_save_template_success_title',
            ])->first();
            $message->message_value = 'テンプレートを登録しました。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_delete_template_success_title',
                'message_value'     => 'テンプレートは削除しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'lb_delete_template_success_title',
            ])->first();
            $message->message_value = 'テンプレートを削除しました。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_save_template_title',
                'message_value'     => 'テンプレート保存',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_select_template_title',
                'message_value'     => 'テンプレート選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_title',
                'message_value'     => '名称',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_required_title',
                'message_value'     => '名称を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_existed_title',
                'message_value'     => '名称は既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_building_title',
                'message_value'     => 'ビル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'no_information_display_title',
                'message_value'     => '表示する情報がありません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'member_name',
                'message_value'     => '会員名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'status_title',
                'message_value'     => 'ステータス',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'search_title',
                'message_value'     => '検索',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'clear_all_title',
                'message_value'     => 'すべてクリア',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_building_title',
                'message_value'     => 'ビル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'please_select_member_title',
                'message_value'     => '会員と出力項目を選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'student_main_title',
                'message_value'     => '会員管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'student_main_title',
            ])->first();
            $message->message_value = '会員情報';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'parent_main_title',
                'message_value'     => '請求先管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'parent_main_title',
            ])->first();
            $message->message_value = '請求先情報';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'output_item_title',
                'message_value'     => '出力項目',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'output_item_title',
            ])->first();
            $message->message_value = 'ラベル出力';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'select_all_title',
                'message_value'     => 'すべて選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'export_btn_title',
                'message_value'     => '出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_pschool_id_title',
                'message_value'     => '団体ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_no_title',
                'message_value'     => '会員番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_name_kana_title',
                'message_value'     => 'カナ名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_nickname_title',
                'message_value'     => 'ニックネーム',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_name_title',
                'message_value'     => '氏名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_login_id_title',
                'message_value'     => 'ログインID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_login_pw_title',
                'message_value'     => 'パスワード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_mailaddress_title',
                'message_value'     => 'メールアドレス',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_birthday_title',
                'message_value'     => '生年月日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_sex_title',
                'message_value'     => '性別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_school_name_title',
                'message_value'     => '学校名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_school_year_title',
                'message_value'     => '学年',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_school_category_title',
                'message_value'     => '施設カテゴリ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_inquiry_date_title',
                'message_value'     => '検索日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_enter_date_title',
                'message_value'     => '入会日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_resign_date_title',
                'message_value'     => '退会日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_zip_code_title',
                'message_value'     => '郵便番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_zip_code1_title',
                'message_value'     => '郵便番号１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_zip_code2_title',
                'message_value'     => '郵便番号２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb__pref_id_title',
                'message_value'     => '都道府県',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb__city_id_title',
                'message_value'     => '市区町村',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_address_detail_title',
                'message_value'     => '番地・ビル名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'reset_parent',
            ])->first();
            $message->message_value = 'リセット';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_id_title',
                'message_value'     => '請求先ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_mailaddress1_title',
                'message_value'     => '請求先メールアドレス１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_mailaddress2_title',
                'message_value'     => '請求先メールアドレス２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_name_title',
                'message_value'     => '請求先名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_active_flag_title',
                'message_value'     => 'アクティブフラッグ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_subject_course_id_title',
                'message_value'     => '担当イベント',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_report_card_id_title',
                'message_value'     => '通知表定',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo1_title',
                'message_value'     => 'メモ１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo2_title',
                'message_value'     => 'メモ２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo3_title',
                'message_value'     => 'メモ３',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_address_title',
                'message_value'     => '住所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'lb_student_address_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_phone_no_title',
                'message_value'     => '自宅電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_handset_no_title',
                'message_value'     => '携帯電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_association_mail_title',
                'message_value'     => '協会メール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_card_img_title',
                'message_value'     => '証デザイン画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_img_title',
                'message_value'     => '画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_romaji_title',
                'message_value'     => '会員ローマ字',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_enter_memo_title',
                'message_value'     => '入会メモ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_resign_memo_title',
                'message_value'     => '退会メモ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_category_title',
                'message_value'     => '会員区分',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_total_member_title',
                'message_value'     => '人数',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_m_student_type_id_title',
                'message_value'     => '会員種別ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'lb_m_student_type_id_title',
            ])->first();
            $message->message_value = '会員種別';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_login_account_id_title',
                'message_value'     => '管理者ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_kana_title',
                'message_value'     => 'カナ名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_pref_id_title',
                'message_value'     => '都道府県',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_city_id_title',
                'message_value'     => '市区町村',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_zip_code1_title',
                'message_value'     => '郵便番号１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_zip_code2_title',
                'message_value'     => '郵便番号２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_address_title',
                'message_value'     => '住所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'lb_address_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_phone_no_title',
                'message_value'     => '自宅電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_invoice_type_title',
                'message_value'     => '支払方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_mail_infomation_title',
                'message_value'     => '通知方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_handset_no_title',
                'message_value'     => '携帯電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo_title',
                'message_value'     => 'メモ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }

        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'confirm_header_export_title',
                'message_value'     => '先頭行にヘッダー出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'item_export_title',
                'message_value'     => '出力項目',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 1,
                'screen_key' => 'school.label',
                'message_key' => 'item_export_title',
            ])->first();
            $message->message_value = '出力文字コード';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => null,
                'message_value'     => null,
                'comment'           => null,
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.label',
                    'message_key' => 'lb_student_name_title',
            ])->first();
            $message->message_value = '会員名';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.label',
                    'message_key' => 'lb_address_detail_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.label',
                    'message_key' => 'lb_student_building_title',
            ])->first();
            $message->message_value = 'ビル名';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 1,
                    'screen_key' => 'school.label',
                    'message_key' => 'export_btn_title',
            ])->first();
            $message->message_value = 'CSV出力';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.label',
                    'screen_value'      => 'ラベル管理',
                    'message_key'       => 'student_type_title',
                    'message_value'     => '会員種別',
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
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_save_template_success_title',
                'message_value'     => 'テンプレートは保存しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'lb_save_template_success_title',
            ])->first();
            $message->message_value = 'テンプレートを登録しました。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_delete_template_success_title',
                'message_value'     => 'テンプレートは削除しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'lb_delete_template_success_title',
            ])->first();
            $message->message_value = 'テンプレートを削除しました。';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_save_template_title',
                'message_value'     => 'テンプレート保存',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_select_template_title',
                'message_value'     => 'テンプレート選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_title',
                'message_value'     => '名称',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_required_title',
                'message_value'     => '名称を入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }

        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_existed_title',
                'message_value'     => '名称は既に存在しています。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_building_title',
                'message_value'     => 'ビル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'no_information_display_title',
                'message_value'     => '表示する情報がありません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'member_name',
                'message_value'     => '会員名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'status_title',
                'message_value'     => 'ステータス',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'search_title',
                'message_value'     => '検索',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'clear_all_title',
                'message_value'     => 'すべてクリア',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_building_title',
                'message_value'     => 'ビル',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'please_select_member_title',
                'message_value'     => '会員と出力項目を選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'student_main_title',
                'message_value'     => '会員管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'student_main_title',
            ])->first();
            $message->message_value = '会員情報';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'parent_main_title',
                'message_value'     => '請求先管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'parent_main_title',
            ])->first();
            $message->message_value = '請求先情報';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'output_item_title',
                'message_value'     => '出力項目',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'output_item_title',
            ])->first();
            $message->message_value = 'ラベル出力';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'select_all_title',
                'message_value'     => 'すべて選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'export_btn_title',
                'message_value'     => '出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_pschool_id_title',
                'message_value'     => '団体ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_no_title',
                'message_value'     => '会員番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_name_kana_title',
                'message_value'     => 'カナ名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_nickname_title',
                'message_value'     => 'ニックネーム',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_name_title',
                'message_value'     => '氏名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_login_id_title',
                'message_value'     => 'ログインID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_login_pw_title',
                'message_value'     => 'パスワード',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_mailaddress_title',
                'message_value'     => 'メールアドレス',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_birthday_title',
                'message_value'     => '生年月日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_sex_title',
                'message_value'     => '性別',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_school_name_title',
                'message_value'     => '学校名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_school_year_title',
                'message_value'     => '学年',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_school_category_title',
                'message_value'     => '施設カテゴリ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_inquiry_date_title',
                'message_value'     => '検索日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_enter_date_title',
                'message_value'     => '入会日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_resign_date_title',
                'message_value'     => '退会日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_zip_code_title',
                'message_value'     => '郵便番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_zip_code1_title',
                'message_value'     => '郵便番号１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_zip_code2_title',
                'message_value'     => '郵便番号２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb__pref_id_title',
                'message_value'     => '都道府県',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb__city_id_title',
                'message_value'     => '市区町村',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_address_detail_title',
                'message_value'     => '番地・ビル名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_id_title',
                'message_value'     => '請求先ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_mailaddress1_title',
                'message_value'     => '請求先メールアドレス１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_mailaddress2_title',
                'message_value'     => '請求先メールアドレス２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_parent_name_title',
                'message_value'     => '請求先名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_active_flag_title',
                'message_value'     => 'アクティブフラッグ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_subject_course_id_title',
                'message_value'     => '担当イベント',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_report_card_id_title',
                'message_value'     => '通知表定',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo1_title',
                'message_value'     => 'メモ１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo2_title',
                'message_value'     => 'メモ２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo3_title',
                'message_value'     => 'メモ３',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_address_title',
                'message_value'     => '住所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'lb_student_address_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }

        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_phone_no_title',
                'message_value'     => '自宅電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_handset_no_title',
                'message_value'     => '携帯電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_association_mail_title',
                'message_value'     => '協会メール',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_card_img_title',
                'message_value'     => '証デザイン画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_img_title',
                'message_value'     => '画像',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_romaji_title',
                'message_value'     => '会員ローマ字',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_enter_memo_title',
                'message_value'     => '入会メモ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_resign_memo_title',
                'message_value'     => '退会メモ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_student_category_title',
                'message_value'     => '会員区分',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_total_member_title',
                'message_value'     => '人数',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_m_student_type_id_title',
                'message_value'     => '会員種別ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'lb_m_student_type_id_title',
            ])->first();
            $message->message_value = '会員種別';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_login_account_id_title',
                'message_value'     => '管理者ID',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_name_kana_title',
                'message_value'     => 'カナ名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_pref_id_title',
                'message_value'     => '都道府県',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_city_id_title',
                'message_value'     => '市区町村',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_zip_code1_title',
                'message_value'     => '郵便番号１',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_zip_code2_title',
                'message_value'     => '郵便番号２',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_address_title',
                'message_value'     => '住所',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'lb_address_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_phone_no_title',
                'message_value'     => '自宅電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_invoice_type_title',
                'message_value'     => '支払方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_mail_infomation_title',
                'message_value'     => '通知方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_handset_no_title',
                'message_value'     => '携帯電話',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'lb_memo_title',
                'message_value'     => 'メモ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
////////
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'confirm_header_export_title',
                'message_value'     => '先頭行にヘッダー出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => 'item_export_title',
                'message_value'     => '出力項目',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (Exception $e) {
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id'   => 2,
                'screen_key' => 'school.label',
                'message_key' => 'item_export_title',
            ])->first();
            $message->message_value = '出力文字コード';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.label',
                'screen_value'      => 'ラベル管理',
                'message_key'       => null,
                'message_value'     => null,
                'comment'           => null,
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.label',
                    'message_key' => 'lb_student_name_title',
            ])->first();
            $message->message_value = '会員名';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.label',
                    'message_key' => 'lb_address_detail_title',
            ])->first();
            $message->message_value = '番地';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.label',
                    'message_key' => 'lb_student_building_title',
            ])->first();
            $message->message_value = 'ビル名';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id'   => 2,
                    'screen_key' => 'school.label',
                    'message_key' => 'export_btn_title',
            ])->first();
            $message->message_value = 'CSV出力';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.label',
                    'screen_value'      => 'ラベル管理',
                    'message_key'       => 'student_type_title',
                    'message_value'     => '会員種別',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    }
}
