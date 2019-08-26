<?php

use Illuminate\Database\Seeder;
use App\Message;

class InvoiceMessageSeeder extends Seeder
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
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'process_invoice_error_message',
                'message_value'     => 'エラーが発生したため処理できませんでした。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'cannot_email_notify_message',
                'message_value'     => '編集中のため、メール通知できません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'list_screen_message',
                    'message_value'     => '編集確定する請求書を選択してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'list_screen_error',
                    'message_value'     => '編集中の請求を選択してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'process_invoice_error_message',
                    'message_value'     => 'エラーが発生したため処理できませんでした。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_item_edited',
                    'message_value'     => '%d件の請求書を編集しました',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'external_system_title',
                    'message_value'     => '外部システム連携',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'convenient_store_title',
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
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'owner_transfer_title',
                    'message_value'     => 'ゆうちょ振込',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'consumption_taxes_in_title',
                    'message_value'     => '消費税（内税',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'consumption_taxes_out_title',
                    'message_value'     => '消費税（外税',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
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
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'already_commit_cannot_delete_message',
                    'message_value'     => 'すでに確定済みのため、削除できません。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'already_billing_cannot_delete_message',
                    'message_value'     => 'すでに請求済みのため、削除できません。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_deleted_message',
                    'message_value'     => '請求書を削除しました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'process_invoice_error_message',
                    'message_value'     => 'エラーが発生したため処理できませんでした。。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'enter_summary_title',
                    'message_value'     => '摘要を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'enter_amount_title',
                    'message_value'     => '金額を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'enter_value_amount_title',
                    'message_value'     => '金額は数値で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_request_time_over',
                    'message_value'     => '依頼書の受付期限を過ぎています。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_already_exist',
                    'message_value'     => '口座振替依頼書が存在します。変更する場合は、存在する依頼書を取消してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'select_invoice_message',
                    'message_value'     => '請求書を選択してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'request_form_created',
                    'message_value'     => '依頼書を作成しました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'target_upload_file_not_exist',
                    'message_value'     => '対象アップロードファイルが存在しない',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_size_error_define_less_than',
                    'message_value'     => 'ファイルサイズエラー(規定より小さい)',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_size_error_data_record_size',
                    'message_value'     => 'ファイルサイズエラー(データレコードサイズ)',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_open_error',
                    'message_value'     => 'ファイルオープンエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'header_record_error',
                    'message_value'     => 'ヘッダーレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'data_record_error',
                    'message_value'     => 'データレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'trailer_record_error',
                    'message_value'     => 'トレーラレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'end_record_error',
                    'message_value'     => 'エンドレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'unknown_record_error',
                    'message_value'     => '不明レコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_is_processed',
                    'message_value'     => 'このファイルは、処理済みです',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_is_not_processed',
                    'message_value'     => 'このファイルは、処理対象ファイルではありません',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'data_other_school_error',
                    'message_value'     => '当該校以外のデータ',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'trailer_record_number_mismatch',
                    'message_value'     => 'トレーラーレコード データ件数不一致',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'withdrawal_amount_difference',
                    'message_value'     => '引落金額の相違:%s',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'target_data_not_exist',
                    'message_value'     => '対象データが存在しない:%s',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposit_management_title',
                'message_value'     => '入金管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_name_furigana',
                'message_value'     => '名前（フリガナ）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_name_furigana_holder',
                'message_value'     => '会員・請求先の漢字・フリガナ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_student_no',
                'message_value'     => '会員番号',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_student_type',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_invoice_year_month',
                'message_value'     => '請求月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_class',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_event',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_program',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_search',
                'message_value'     => '検索',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_clear_search',
                'message_value'     => 'すべてクリア',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_check_all',
                'message_value'     => '全て選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_check',
                'message_value'     => '選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_parent_student_name',
                'message_value'     => '請求先／会員名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_summary',
                'message_value'     => '摘要',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_amount',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_export_csv',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposit_all',
                'message_value'     => '一括入金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposit_single',
                'message_value'     => '個別入金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_currency',
                'message_value'     => '円',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_invoice_name',
                'message_value'     => '分請求',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_no_result',
                'message_value'     => 'データがありません',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_paid_date',
                'message_value'     => '入金日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_invoice_type',
                'message_value'     => '入金方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_export_receipt',
                'message_value'     => '領収書出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_parent_name',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_execute',
                'message_value'     => '実行',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_back',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_previous_text',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_next_text',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_paid_date_required',
                'message_value'     => '入金日は必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_paid_date_date',
                'message_value'     => '入金日を正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_invoice_type_required',
                'message_value'     => '入金方法は必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_invoice_header_checkbox',
                'message_value'     => '請求対象を選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_password',
                'message_value'     => 'パスワード：',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_announced_date',
                'message_value'     => 'メール送信日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_sent_announce_mail',
                'message_value'     => '催促メール送信',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_workflow_status',
                'message_value'     => '入金状態',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_no_deposit',
                'message_value'     => '未入金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposited',
                'message_value'     => '入金済み',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_password_required',
                'message_value'     => '実行するにはパスワードが必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_password_in',
                'message_value'     => 'パスワードが間違いました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_proviso',
                'message_value'     => '但書',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_mail_content',
                'message_value'     => 'メール内容は必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_mail_title',
                'message_value'     => 'メールタイトルは必要です',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_send_deposited_reminder',
                'message_value'     => '入金済みの請求を外してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_list_mail_template',
                'message_value'     => 'テンプレート選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'dp_list_mail_template',
            ])->first();
            $message->message_value = 'テンプレート選択';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_template_create',
                'message_value'     => 'テンプレート登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'dp_mail_template_create',
            ])->first();
            $message->message_value = 'テンプレート登録';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_reminder_mail_address',
                'message_value'     => '送付先',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_title',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_content',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_footer',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_send',
                'message_value'     => '送信',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'error_missing_file',
                'message_value'     => 'ファイルが存在しません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'wrong_format_file',
                'message_value'     => 'ファイルの形式が正しくありません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'import_conv_success',
                'message_value'     => '%dのレコードが更新されました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'import_conv_success',
            ])->first();
            $message->message_value = '%d件のレコードが更新されました。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'invoice_change_date_title',
                    'message_value'     => '作成・変更日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_payment_method',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_select',
                'message_value'     => '選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_reset',
                'message_value'     => 'リセット',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'invoice_creation_count',
                    'message_value'     => '請求書作成件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'select_parent_message',
                    'message_value'     => '請求先を選択してください',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'kozafurikae_deadline',
                    'message_value'     => '口座振替日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'create_data_external',
                    'message_value'     => '連携データ作成',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'upload_data_external',
                    'message_value'     => '連携データ結果取込',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'upload_data_btn',
                    'message_value'     => '取込み',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'total_invoice_amount',
                    'message_value'     => '請求合計金額',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'last_creation_date',
                    'message_value'     => '最終作成日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_or',
                'message_value'     => 'または',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'dp_sent_reminder_mail',
                    'message_value'     => '催促メール',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'update_success',
                'message_value'     => '更新しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_export_inform_message',
                    'message_value'     => '定期請求に含まれないコンビニ決済の未処理件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'yuucho_export_inform_message',
                    'message_value'     => '定期請求に含まれないゆうちょ振込の未処理件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // Toran 2017-11-30
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_header',
                    'message_value'     => '取込結果',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_deposit_number',
                    'message_value'     => '入金件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_deposit_amount',
                    'message_value'     => '入金金額',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_result_list',
                    'message_value'     => '取込結果詳細一覧',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_deposit_result',
                    'message_value'     => '入金結果',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_amount_charged',
                    'message_value'     => '入金額',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'external_delete_file_warning',
                    'message_value'     => '削除してよろしいですか？
削除する場合、すでにファイルが外部システムと連携している場合、外部システムの方も対応してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                    'message_file_id' => 1,
                    'screen_key'      => 'school.invoice',
                    'message_key'     => 'combini_export_inform_message',
            ])->first();
            $message->message_value = '定期請求に含まれない コンビニ決済の未処理件数';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id' => 1,
                    'screen_key'      => 'school.invoice',
                    'message_key'     => 'yuucho_export_inform_message',
            ])->first();
            $message->message_value = '定期請求に含まれない ゆうちょ振込の未処理件数';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'inv_remark',
                'message_value'     => '※請求書を送付したもの（請求済）が、入金／未入金の対象となります',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_request_title',
                    'message_value'     => '今月作成の請求データ一覧',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_csv_export_title',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_export_confirm',
                'message_value'     => '文字コードを選択してください。 ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_cancel_title',
                'message_value'     => 'キャンセル ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'tranfer_title',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'tranfer_title_post',
                'message_value'     => 'ゆうちょ振込',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'cancel_btn_title',
                'message_value'     => '削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'cancel_btn_title',
            ])->first();
            $message->message_value = '削除';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id' => 1,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'cancel_confirm_title',
            ])->first();
            $message->message_value = '削除します。よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'cancel_confirm_title',
                'message_value'     => '削除します。よろしいですか？',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'invoice_total_amount_title',
                'message_value'     => '合計金額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'jap_yen_title',
                'message_value'     => '円',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // ===========================
        // message_file_id = 2 : en ==
        // ===========================
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'process_invoice_error_message',
                'message_value'     => 'エラーが発生したため処理できませんでした。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'cannot_email_notify_message',
                'message_value'     => '編集中のため、メール通知できません。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'list_screen_message',
                    'message_value'     => '編集確定する請求書を選択してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'list_screen_error',
                    'message_value'     => '編集中の請求を選択してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'process_invoice_error_message',
                    'message_value'     => 'エラーが発生したため処理できませんでした。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_item_edited',
                    'message_value'     => '%d件の請求書を編集しました',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'external_system_title',
                    'message_value'     => '外部システム連携',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'convenient_store_title',
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
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'owner_transfer_title',
                    'message_value'     => 'ゆうちょ振込',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'consumption_taxes_in_title',
                    'message_value'     => '消費税（内税',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'consumption_taxes_out_title',
                    'message_value'     => '消費税（外税',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
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
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'already_commit_cannot_delete_message',
                    'message_value'     => 'すでに確定済みのため、削除できません。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'already_billing_cannot_delete_message',
                    'message_value'     => 'すでに請求済みのため、削除できません。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_deleted_message',
                    'message_value'     => '請求書を削除しました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'process_invoice_error_message',
                    'message_value'     => 'エラーが発生したため処理できませんでした。。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'enter_summary_title',
                    'message_value'     => '摘要を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'enter_amount_title',
                    'message_value'     => '金額を入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'enter_value_amount_title',
                    'message_value'     => '金額は数値で入力してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_request_time_over',
                    'message_value'     => '依頼書の受付期限を過ぎています。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_already_exist',
                    'message_value'     => '口座振替依頼書が存在します。変更する場合は、存在する依頼書を取消してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'select_invoice_message',
                    'message_value'     => '請求書を選択してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'request_form_created',
                    'message_value'     => '依頼書を作成しました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'target_upload_file_not_exist',
                    'message_value'     => '対象アップロードファイルが存在しない',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_size_error_define_less_than',
                    'message_value'     => 'ファイルサイズエラー(規定より小さい)',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_size_error_data_record_size',
                    'message_value'     => 'ファイルサイズエラー(データレコードサイズ)',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_open_error',
                    'message_value'     => 'ファイルオープンエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'header_record_error',
                    'message_value'     => 'ヘッダーレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'data_record_error',
                    'message_value'     => 'データレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'trailer_record_error',
                    'message_value'     => 'トレーラレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'end_record_error',
                    'message_value'     => 'エンドレコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'unknown_record_error',
                    'message_value'     => '不明レコードエラー',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_is_processed',
                    'message_value'     => 'このファイルは、処理済みです',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'file_is_not_processed',
                    'message_value'     => 'このファイルは、処理対象ファイルではありません',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'data_other_school_error',
                    'message_value'     => '当該校以外のデータ',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'trailer_record_number_mismatch',
                    'message_value'     => 'トレーラーレコード データ件数不一致',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'withdrawal_amount_difference',
                    'message_value'     => '引落金額の相違:%s',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'target_data_not_exist',
                    'message_value'     => '対象データが存在しない:%s',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposit_management_title',
                'message_value'     => '入金管理',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_name_furigana',
                'message_value'     => '名前（フリガナ）',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_name_furigana_holder',
                'message_value'     => '会員・請求先の漢字・フリガナ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_student_no',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_student_type',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_invoice_year_month',
                'message_value'     => '請求月',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_class',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_event',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_program',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_search',
                'message_value'     => '検索',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_clear_search',
                'message_value'     => 'すべてクリア',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_check_all',
                'message_value'     => '全て選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_check',
                'message_value'     => '選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_parent_student_name',
                'message_value'     => '請求先／会員名',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_summary',
                'message_value'     => '摘要',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_amount',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_export_csv',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposit_all',
                'message_value'     => '一括入金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposit_single',
                'message_value'     => '個別入金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_currency',
                'message_value'     => '円',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_invoice_name',
                'message_value'     => '分請求',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_no_result',
                'message_value'     => 'データがありません',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_paid_date',
                'message_value'     => '入金日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_invoice_type',
                'message_value'     => '入金方法',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_export_receipt',
                'message_value'     => '領収書出力',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_parent_name',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_execute',
                'message_value'     => '実行',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_back',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_previous_text',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_next_text',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_paid_date_required',
                'message_value'     => '入金日は必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_paid_date_date',
                'message_value'     => '入金日を正しく入力してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_invoice_type_required',
                'message_value'     => '入金方法は必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_invoice_header_checkbox',
                'message_value'     => '請求対象を選択してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_password',
                'message_value'     => 'パスワード：',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_announced_date',
                'message_value'     => 'メール送信日',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_sent_announce_mail',
                'message_value'     => '催促メール送信',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_workflow_status',
                'message_value'     => '入金状態',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_no_deposit',
                'message_value'     => '未入金',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_deposited',
                'message_value'     => '入金済み',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_password_required',
                'message_value'     => '実行するにはパスワードが必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_password_in',
                'message_value'     => 'パスワードが間違いました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_proviso',
                'message_value'     => '但書',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_mail_content',
                'message_value'     => 'メール内容は必要です。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_mail_title',
                'message_value'     => 'メールタイトルは必要です',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_err_send_deposited_reminder',
                'message_value'     => '入金済みの請求を外してください。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_list_mail_template',
                'message_value'     => 'テンプレート選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'dp_list_mail_template',
            ])->first();
            $message->message_value = 'テンプレート選択';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_template_create',
                'message_value'     => 'テンプレート登録',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'dp_mail_template_create',
            ])->first();
            $message->message_value = 'テンプレート登録';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_reminder_mail_address',
                'message_value'     => '送付先',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_title',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_content',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_mail_footer',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_send',
                'message_value'     => '送信',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'bank_transfer_method_title',
                    'message_value'     => '銀行振込',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'error_missing_file',
                    'message_value'     => 'ファイルが存在しません。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'wrong_format_file',
                    'message_value'     => 'ファイルの形式が正しくありません。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'import_conv_success',
                    'message_value'     => '%dのレコードが更新されました。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'import_conv_success',
            ])->first();
            $message->message_value = '%d件のレコードが更新されました。';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'invoice_change_date_title',
                    'message_value'     => '作成・変更日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_payment_method',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_select',
                'message_value'     => '選択',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_reset',
                'message_value'     => 'リセット',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'invoice_creation_count',
                    'message_value'     => '請求書作成件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'select_parent_message',
                    'message_value'     => '請求先を選択してください',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'kozafurikae_deadline',
                    'message_value'     => '口座振替日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'create_data_external',
                    'message_value'     => '連携データ作成',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'upload_data_external',
                    'message_value'     => '連携データ結果取込',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'upload_data_btn',
                    'message_value'     => '取込み',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'total_invoice_amount',
                    'message_value'     => '請求合計金額',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'last_creation_date',
                    'message_value'     => '最終作成日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_or',
                'message_value'     => 'または',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '入金処理',
                    'message_key'       => 'dp_sent_reminder_mail',
                    'message_value'     => '催促メール',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'update_success',
                'message_value'     => '更新しました。',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_export_inform_message',
                    'message_value'     => '定期請求に含まれないコンビニ決済の未処理件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'yuucho_export_inform_message',
                    'message_value'     => '定期請求に含まれないゆうちょ振込の未処理件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        // Toran 2017-11-30
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_header',
                    'message_value'     => '取込結果',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_deposit_number',
                    'message_value'     => '入金件数',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_deposit_amount',
                    'message_value'     => '入金金額',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_result_list',
                    'message_value'     => '取込結果詳細一覧',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_deposit_result',
                    'message_value'     => '入金結果',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_amount_charged',
                    'message_value'     => '入金額',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'external_delete_file_warning',
                    'message_value'     => '削除してよろしいですか？
削除する場合、すでにファイルが外部システムと連携している場合、外部システムの方も対応してください。',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                    'message_file_id' => 2,
                    'screen_key'      => 'school.invoice',
                    'message_key'     => 'combini_export_inform_message',
            ])->first();
            $message->message_value = '定期請求に含まれない コンビニ決済の未処理件数';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                    'message_file_id' => 2,
                    'screen_key'      => 'school.invoice',
                    'message_key'     => 'yuucho_export_inform_message',
            ])->first();
            $message->message_value = '定期請求に含まれない ゆうちょ振込の未処理件数';
            $message->save();
        } catch (\Exception $e) {

        }

        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'inv_remark',
                'message_value'     => '※請求書を送付したもの（請求済）が、入金／未入金の対象となります',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'combini_request_title',
                    'message_value'     => '今月作成の請求データ一覧',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }

        // 2018-01-04
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_issue_date',
                    'message_value'     => '請求書発行日:',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_issue_date',
                    'message_value'     => '請求書発行日',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_header_title',
                    'message_value'     => 'ご請求書:',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_header_title',
                    'message_value'     => 'ご請求書',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 1,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_count_error',
                    'message_value'     => 'エラー:',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                    'message_file_id'   => 2,
                    'screen_key'        => 'school.invoice',
                    'screen_value'      => '請求管理',
                    'message_key'       => 'invoice_count_error',
                    'message_value'     => 'エラー:',
                    'comment'           => '',
                    'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                    'message_file_id' => 1,
                    'screen_key'      => 'school.invoice',
                    'message_key'     => 'invoice_header_title',
            ])->first();
            $message->message_value = 'ご請求書';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_csv_export_title',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_export_confirm',
                'message_value'     => '文字コードを選択してください。 ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '入金処理',
                'message_key'       => 'dp_cancel_title',
                'message_value'     => 'キャンセル ',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'tranfer_title',
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
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'tranfer_title_post',
                'message_value'     => 'ゆうちょ振込',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'cancel_btn_title',
                'message_value'     => '削除',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'cancel_confirm_title',
                'message_value'     => '削除します。よろしいですか？',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'cancel_btn_title',
            ])->first();
            $message->message_value = '削除';
            $message->save();
        } catch (\Exception $e) {

        }
        try {
            $message = Message::where([
                'message_file_id' => 2,
                'screen_key'      => 'school.invoice',
                'message_key'     => 'cancel_confirm_title',
            ])->first();
            $message->message_value = '削除します。よろしいですか？';
            $message->save();
        } catch (\Exception $e) {

        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'invoice_total_amount_title',
                'message_value'     => '合計金額',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'jap_yen_title',
                'message_value'     => '円',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        
        try{
            Message::create([
                'message_file_id'   => 1,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'invoice_select_payment_method_title',
                'message_value'     => '入金方法を選択して下さい',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
        try{
            Message::create([
                'message_file_id'   => 2,
                'screen_key'        => 'school.invoice',
                'screen_value'      => '請求管理',
                'message_key'       => 'invoice_select_payment_method_title',
                'message_value'     => '入金方法を選択して下さい',
                'comment'           => '',
                'register_admin'    => 1,
            ]);
        } catch (\Exception $e){
            // TODO log error message
        }
    }
}
