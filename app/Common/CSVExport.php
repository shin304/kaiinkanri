<?php
/**
 * Created by PhpStorm.
 * User: ASTO-21
 * Date: 22/03/2018
 * Time: 02:29
 */

namespace App\Common;


use Dompdf\Exception;
use Illuminate\Contracts\Encryption\EncryptException;
use phpDocumentor\Reflection\Types\Null_;
use function Sodium\crypto_box_publickey_from_secretkey;
use Illuminate\Support\Facades\Storage;

define( 'FULL_PATH_FOLDER' , '../storage/app/file_download/'.''.session('school.login.id').'/' );
class CSVExport {

    use \App\Common\Email;

    /**
     * 関数名                transferCharCode
     * 処理内容              エンコードによってデータが出る
     * 入力パラメータ        $data, $char_code
     * @param $data          データベースから、データを取る
     * @param $char_code     1:SHIFT_JIS 2:UTF-8
     * 戻り値                出力文字列
     */
    private static function transferCharCode($data, $char_code) {

        $result_buf = null;
        // 郵便番号のマークを編集する
        foreach ($data as &$row) {
            if (isset($row['zip_code'])) {
                $row['zip_code'] = str_replace("〒", "", $row['zip_code']);
            }
        }
        // If $data != NULL && $char_code == 1 then convert $data to ShiftJS
        if ($char_code == 1) { // endcode SJIS
            foreach ($data as &$row) {
                array_walk_recursive($row, function(&$val) {

                    $val = mb_convert_encoding($val, 'sjis-win', 'UTF-8');
                });
            }
            $result_buf = $data;

        } else { // Encode UTF-8
            // If $data != NULL && $char_code != 1 then convert $data to UTF-8
            $result_buf = $data;
        }
        return $result_buf;
    }

    /**
     * 関数名                createCsvFile
     * 処理内容              CSVファイルを作成する
     * 入力パラメータ        $data, $header, $data_file_path
     * @param $data          データベースから、データを取る
     * @param $header        WEBで、ユーザーを選択したヘッダーを取る
     * @param data_file_path     ファイルを更新する所
     * 戻り値                1:OK それ以外NG
     */
    private static function createCsvFile($data, $header, $data_file_path) {

        $fp = null;
        // dd (iconv('UTF-8', 'CP932', $data_file_path));
        // Todo check $data if $data != NULL open new file
        if ($data != NULL) {
            $fp = fopen($data_file_path, 'w');
            // check export header or not
            if ($header != NULL) {
                // add header array
                $head [] = $header;

                $data = array_merge($head, $data);
            }
            // add data to array
            //** chage char_code */
            $dataF = self::transferCharCode($data, 1);

            foreach ($dataF as $row) {
                fputcsv($fp, $row);
            }

            fclose($fp);
        }
    }

    /**
     * 関数名                generateKey
     * 処理内容              ランダムキーを作成する
     * @param int $length キーの長さ
     * 戻り値                作成したキー　NULLの場合NG
     */
    private static function generateKey($length = 8) {

        // Todo set $key
        $myKey = (self::generateRandomString($length));
        return $myKey;
    }

    /**
     * @return string
     */
    private static function generateRandomString($length) {

        $result = '';
        for ($i = 0; $i < $length; $i ++) {
            $result .= self::generateRandomChar();
        }
        return $result;
    }

    /**
     * @return string
     */
    private static function generateRandomChar() {

        $r = mt_rand(0, 61);

        if ($r < 10) {
            $c = $r;
        } elseif ($r >= 10 && $r < 36) {
            $r -= 10;
            $c = chr($r + ord('a'));
        } else {
            $r -= 36;
            $c = chr($r + ord('A'));
        }
        return $c;
    }

    /**
     * 関数名                    createZip
     * 処理内容                  ファイルを作成する
     * 入力パラメータ            $output_file_path, $full_path_folder, $crypt_key
     * @param $full_path_folder   CSVファイルが有る所
     * @param $output_file_path zipファイルを更新する所
     * @param null $crypt_key ユーザー側にキーを入れる場合は、CSVファイルにキーを入れる
     * 戻り値                    1:ＯＫ　0:NG
     */
    private static function createZip($output_file_path, $full_path_folder, $crypt_key = null) {

        $file_attr = array ();
        //$key = null;
        // Todo get $data_file
        $data_file = glob($full_path_folder.'*');

        //        // get crypt key
        //        if ($crypt_key != NULL) {
        //            $key = self::generateKey($crypt_key);
        //        }
        $file_attr ['key'] = $crypt_key;
        $file_attr ['output_file_path'] = $output_file_path;
        $file_attr ['dataFile'] = $data_file;
        $file_attr ['full_path_folder'] = $full_path_folder;
        // Zipファイル作成
        return self::zipFile($file_attr);
    }

    /**
     * 関数名                zipFile
     * 処理内容                zipファイルにエクセルを入れる
     * @param $file_attr    エクセルファイルのリスト
     * 戻り値                1:OK それ以外失敗
     */
    private static function zipFile($file_attr = array ()) {

        // Check OS system
        $os = PHP_OS;
        // check data_file_attr
        $is_exist = false;
        if ($file_attr != NULL) {
            // check get $key from $request
//            foreach ($file_attr ['dataFile'] as &$value) {
//                $value = '../storage/app/file_download/' . $value . ' ';
//            }
            if ($file_attr ['key'] != NULL) {
                // Todo add file to zip with key
                if (strtoupper(substr($os, 0, 3) === 'WIN')) {
                    // Windows チェックして, ７zipをインストールしてください。
                    $cmd_str = '7z a -tzip -p' . $file_attr ['key'] . " " . $file_attr ['output_file_path'] . " " . $file_attr ['full_path_folder'] . '*';
                    shell_exec($cmd_str); // debug
                } else {
                     $cmd_str = 'cd ' . $file_attr ['full_path_folder'] . '; zip -r -P' . $file_attr ['key'] . " " . 'GetInfo.zip' . " " . '*';
                    shell_exec($cmd_str);
                }
            } else {
                // Todo add file to zip
                if (strtoupper(substr($os, 0, 3) === 'WIN')) {
                    // Windows チェックして, ７zipをインストールしてください。
                    $cmd_str = '7z -tzip a ' . " " . $file_attr ['output_file_path'] . " " . $file_attr ['full_path_folder'] . '*';
                    shell_exec($cmd_str);
                } else {
                    $cmd_str = 'cd ' . $file_attr ['full_path_folder'] . '; zip -r ' . " " . 'GetInfo.zip' . " " . '*';
                    shell_exec($cmd_str);
                }
            }
            // Zipファイルが存在するかどうかチェック
            $is_exist = file_exists($file_attr ['output_file_path']);
        }
        return $is_exist;
    }

    /**
     *    関数名                sendEmailDecryptFileForUser
     *    処理内容            ユーザーにメールに付けるランダムキーを送信する
     *    入力パラメータ        $data： CSVファイル
     *    戻り値                1:OK それ以外失敗
     **/
    public static function sendEmailDecryptFileForUsers($data = array ()) {

        $data_send_mail = $data ['data_send_mail'];
        $user_name = $data ['user_name'];
        $to_email = $data ['to_email'];
        $file_name = $data ['file_name'] . '.zip';
        // データをチェックする
        if ((isset($data_send_mail) && ! empty($user_name)) && isset($file_name) && ! empty($to_email)) {
            // if $data != NULL
            return self::sendEmailDecryptFileForUser($data_send_mail, $user_name, $data['key'], $file_name, $to_email, false);
        }
    }

    /**
     * 関数名                  zipFile
     * 処理内容                zipファイルにエクセルを入れて、ダウンロードする
     * @param $data            全部データを取る
     * @param null $is_crypt 暗号化するかどうかチェック
     * @param null $crypt_key キーをチェックする
     * @戻り値                 1:OK それ以外失敗
     */
    public static function exportZipCSV($data, $is_crypt = null, $crypt_key = null) {

        // フォルダがない場合はフォルダを作成
        $code = (self::generateRandomString(6));
        $full_path_folder = FULL_PATH_FOLDER.$code.'/';
        $out_path_folder = FULL_PATH_FOLDER;
        if (!file_exists($full_path_folder)) {
            mkdir($full_path_folder, 0777, true);
        }
        // ヘッダーを取る
        $ls_header = $data['header'];
        // データを取る
        $data_file = $data['dataFile'];
        // 文字コードをチャンジする
        $result_code = self::transferCharCode($data_file, $data['char_code']);
        // キーを取る
        $key = null;
        $is_sent = false;

        // $data ['data_file_path'] = '../storage/app/file_download/' . $data ['file_name'].'.csv';
        $data ['data_file_path'] = $full_path_folder.iconv('UTF-8', 'CP932',  $data ['file_name']) . '.csv';
        $data ['output_file_path'] = $full_path_folder.'GetInfo.zip';
        // dd (Storage::disk('csv')->url());

        if (! empty($result_code)) {
            self::createCsvFile($data_file, $ls_header, $data['data_file_path']);
            $result_csv_file = glob($full_path_folder.'*'); // Storage::disk('file_download/')->files();

            if (! empty($result_csv_file)) {
                if ($is_crypt) {
                    // パスワードを付ける
                    $key = self::generateKey($crypt_key);
                    $data ['key'] = $key;
                    // パスワードを付けてZipファイル作成
                    self::createZip($data['output_file_path'], $full_path_folder, $key);
                    // todo send mail
                    $is_sent = self::sendEmailDecryptFileForUsers($data);
                } else {
                    // パスワードを付けないでZipファイル作成
                    $is_sent = self::createZip($data['output_file_path'], $full_path_folder);
                }
                $size = filesize($data ['output_file_path']);
                // send the file to the browser as a download
                // header('Cache-Control: no-cache, must-revalidate');
                header("Content-length: $size");
                header('Content-type: application/zip');
                header('Content-disposition: attachment; filename="' . $data['file_name'] . '.zip');

                readfile($data ['output_file_path']);

                $files = glob($full_path_folder.'*'); //get all file names
                foreach($files as $file){
                    if(is_file($file))
                        unlink($file); //delete file
                }

                shell_exec('rm -rf '.$out_path_folder);
            }
        }
        return $is_sent;
    }
}