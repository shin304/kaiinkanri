<?php
/**
 * Created by PhpStorm.
 * User: ASTO-21
 * Date: 5/9/2018
 * Time: 10:32
 */

namespace App\Common;


use App\Model\MailMessageTable;
use App\Model\StudentTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HandelCoopSendMail {

    use Email;

    /**
     *    関数名            check_loop_email
     *    処理内容        メールアドレスが重複するのチェック
     *    入力パラメータ    $listMail： メールアドレス一覧
     *    戻り値            1:OK それ以外失敗
     **/
    private static function check_loop_email($listMail) {

        // メールリストを取得して、ユニークをチェックする
        if ($listMail != null) {
            return array_unique($listMail, 0);
        }
    }

    /**
     *
     *    関数名            coop_send_mail
     *    処理内容          法人の場合のメール送信処理
     *    入力パラメータ    $studentInfo: 会員情報
     *                      $mailAddress: 会員情報のメールアドレス（請求先メールアドレス）
     *                      $processName: 処理区分（お知らせ送信・イベント・プログラム）
     *                      $processID: 処理区分の対象id
     *                      $mail_message_id:
     *                      $data: メール送信の内容
     *    戻り値            1:OK それ以外失敗
     **/
    public static function coop_send_mail($interface_type, $studentInfo, $mailAddress, $processName, $processID, $mail_message_id, $data) {

        $mailAddress_list = $mailAddress;
        $getListMail = null;
        $studentTable = StudentTable::getInstance();
        $mailMessageTable = MailMessageTable::getInstance();
        $is_sent = null;
        // 取得した会員情報の数分ループ
        foreach ($studentInfo as $item) {
            if ($item['student_category'] == 2) { // 会員が法人場合
                // メール受信フラグがONの会員情報に紐づく代表者・担当者のメールアドレスを取得
                $getInfo = $studentTable->getMailAddressAllMemberByStudentInfoID($item['id']);
                foreach ($getInfo as $student) {
                    if (($student['representative_send_mail_flag'] ==1)) {
                        array_push($mailAddress_list, $student['representative_email']);
                    }
                    if (($student['check_send_mail_flag'] ==1)) {
                        array_push($mailAddress_list, $student['person_email']);
                    }
                }
            }
        }
        // 取得したメールアドレスを保持する
        $getListMail = $mailAddress_list;
        // 保持しているメールアドレスで、重複を取り除く
        $getListMail_unique = self::check_loop_email($getListMail);
        $key = array_search(null, $getListMail_unique);

        // NULL値がある時、削除する
        if (! $key) {
            // メール送信
            foreach ($getListMail_unique as $item) {
                $is_sent = self::sendListEmailUser($interface_type, $data, $item, $processName, $processID);
                // DBにメール送信したことを記録する
                $mail_info = $mailMessageTable->load($mail_message_id);
                if ($is_sent) {
                    // メールメッセージをデータベースに追加してから、送信日刻を変する
                    $mail_message = array (
                        'id' => $mail_info['id'],
                        'send_date' => date("Y-m-d H:i:s"),
                        'total_send' => $mail_info['total_send'] + 1
                    );
                    $mailMessageTable->save($mail_message);
                    $status = 1; // 送信済み
                } else {
                    $status = 0; // 未送信
                }

                // Write log
                $current_time = date('Y-m-d H:i:s');
                if ($interface_type == 1) {
                    $type = 'Event';
                    $mail_id = $data['event_id'];
                } else if($interface_type == 2) {
                    $type = 'Program';
                    $mail_id = $data['program_id'];
                } else {
                    $type = 'Oshirasei';
                    $mail_id = $data['broadcast_mail_id'];
                }
                $path = "../storage/logs/".session('school.login.id')."/mail/" . $type . "/";
                $fileName = $path . "mail_debug.log";
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                file_put_contents($fileName, $current_time . ": " . '        ' . $mail_id . ',' . $item . ',' . $status . "\n", FILE_APPEND);
            }
            return true;
        } else {
            return false;
        }
    }
}