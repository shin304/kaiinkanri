<?php
/**
 * Created by PhpStorm.
 * User: ASTO11
 * Date: 8/30/2017
 * Time: 10:32 AM
 */

namespace App\Module\Invoice;
use App\Model\ClosingDayTable;
use App\Model\PschoolTable;
use App\Common\Constants;
use App\Model\PaymentMethodTable;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceHelper {

    private static $_instance = null;
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new InvoiceHelper ();
        }
        return self::$_instance;
    }
    private static  $LEYOUT = array(array('degit'=>1, 'format'=>'%d',     'default'=>0),		// ダミー
                                    array('degit'=>1, 'format'=>'%d',     'default'=>1),		// 1
                                    array('degit'=>2, 'format'=>"%'.02d", 'default'=>91),		// 2
                                    array('degit'=>1, 'format'=>'%d', '    default'=>0),		// 3
                                    array('degit'=>10,'format'=>"%'.010d",'default'=>0),		// 4
                                    array('degit'=>20,'format'=>"%'.20s", 'default'=>''),		// 5
                                    array('degit'=>20,'format'=>"%'.20s", 'default'=>''),		// 6
                                    array('degit'=>4, 'format'=>"%'.04d", 'default'=>0),		// 7
                                    array('degit'=>4, 'format'=>"%'.04d", 'default'=>0),		// 8
                                    array('degit'=>15,'format'=>"%'.15s", 'default'=>''),		// 9
                                    array('degit'=>3, 'format'=>"%'.03d", 'default'=>0),		// 10
                                    array('degit'=>15,'format'=>"%'.15s", 'default'=>''),		// 11
                                    array('degit'=>1, 'format'=>"%d",     'default'=>0),		// 12
                                    array('degit'=>7, 'format'=>"%'.07d", 'default'=>0),		// 13
                                    array('degit'=>17,'format'=>"%'.17s", 'default'=>''),		// 14

                                    array('degit'=>1, 'format'=>'%d',     'default'=>2),		// 15
                                    array('degit'=>4, 'format'=>"%'.04d", 'default'=>0),		// 16
                                    array('degit'=>15,'format'=>"%'.15s", 'default'=>''),		// 17
                                    array('degit'=>3, 'format'=>"%'.03d", 'default'=>0),		// 18
                                    array('degit'=>15,'format'=>"%'.15s", 'default'=>''),		// 19
                                    array('degit'=>4, 'format'=>"%'.4s",  'default'=>''),		// 20
                                    array('degit'=>1, 'format'=>"%d",     'default'=>0),		// 21
                                    array('degit'=>7, 'format'=>"%'.07d", 'default'=>0),		// 22
                                    array('degit'=>30,'format'=>"%'.30s", 'default'=>''),		// 23
                                    array('degit'=>10,'format'=>"%'.010d",'default'=>0),		// 24
                                    array('degit'=>1, 'format'=>"%d",     'default'=>0),		// 25
                                    array('degit'=>20,'format'=>"%'.020d",'default'=>0),		// 26
                                    array('degit'=>1, 'format'=>"%d",     'default'=>0),		// 27
                                    array('degit'=>8, 'format'=>"%'.8s",  'default'=>''),		// 28

                                    array('degit'=>1, 'format'=>'%d',     'default'=>8),		// 29
                                    array('degit'=>6, 'format'=>"%'.06d", 'default'=>0),		// 30
                                    array('degit'=>12,'format'=>"%'.012d",'default'=>0),		// 31
                                    array('degit'=>6, 'format'=>"%'.06d", 'default'=>0),		// 32
                                    array('degit'=>12,'format'=>"%'.012d",'default'=>0),		// 33
                                    array('degit'=>6, 'format'=>"%'.06d", 'default'=>0),		// 34
                                    array('degit'=>12,'format'=>"%'.012d",'default'=>0),		// 35
                                    array('degit'=>65,'format'=>"%'.65s", 'default'=>0),		// 36

                                    array('degit'=>1,  'format'=>"%d",    'default'=>9),		// 37
                                    array('degit'=>119,'format'=>"%'.119s",'default'=>'') 		// 38
    );
    //
    const HEADER_RECORD_LEN  = 122;
    const DATA_RECORD_LEN    = 122;
    const TRAILER_RECORD_LEN = 122;
    const END_RECORD_LEN     = 122;

    // template array 1 record for csv file
    private static $csv_row_data = array(
            '1' => null,  // customer code
            '2' => null,  // customer name
            '3' => null,  // customer name kana
            '4' => null,  // customer zipcode
            '5' => null,  // address 1
            '6' => null,  // address 2
            '7' => null,  // phone number
            '8' => null,  // amount
            '9' => null,  // due_date  YYYY-MM-DD
            '10' => null,  // tax
            '11' => null,  // receiver's name1 (daihyou ??)
            '12' => null,  // 1:様2:御中3:殿
            '13' => null,  // receiver's name2
            '14' => null,  // 1:様2:御中3:殿
            '15' => null,  // receiver's name3
            '16' => null,  // 1:様2:御中3:殿
            '17' => null,  // file type ?? default 1
            '18' => null,  // page option 0:none 1:use
            '19' => null,  // page info
            '20' => null,  // page info
            '21' => null,  // page info
            '22' => null,  // page info
            '23' => null,  // page info
            '24' => null,  // page info
            '25' => null,  // page info
            '26' => null,  // page info
            '27' => null,  // page info
            '28' => null,  // page info
            '29' => null,  // page info
            '30' => null,  // comment of invoice
            '31' => null,  // title base on format 1
            '32' => null,  // title base on format 2
            '33' => null,  //
            '34' => null,  // title amount base on format

    );
    private static $csv_row_limit = array(
            '1' => 15,  // customer code
            '2' => 50,  // customer name
            '3' => 80,  // customer name kana
            '4' => 7,  // customer zipcode
            '5' => 30,  // address 1
            '6' => 30,  // address 2
            '7' => 15,  // phone number
            '8' => 12,  // amount
            '9' => 8,  // due_date  YYYY-MM-DD
            '10' => 8,  // tax
            '11' => 50,  // receiver's name1 (daihyou ??)
            '12' => 1,  // 1:様2:御中3:殿
            '13' => 50,  // receiver's name2
            '14' => 1,  // 1:様2:御中3:殿
            '15' => 50,  // receiver's name3
            '16' => 1,  // 1:様2:御中3:殿
            '17' => 1,  // file type ?? default 1
            '18' => 1,  // page option 0:none 1:use
            '19' => 1,  // page info
            '20' => 12,  // page info
            '21' => 12,  // page info
            '22' => 12,  // page info
            '23' => 12,  // page info
            '24' => 1,  // page info
            '25' => 75,  // page info
            '26' => 75,  // page info
            '27' => 75,  // page info
            '28' => 75,  // page info
            '29' => 8,  // page info
            '30' => 10,  // comment of invoice
            '31' => 30,  // title base on format 1
            '32' => 15,  // title base on format 2
            '33' => 24,  //
            '34' => 6,  // title amount base on format

    );

    // limit on file when import to
    private static $item_import_limit = array(
        '1' =>  5,
        '2' =>  2,
        '3' =>  4,
        '4' =>  8,
        '5' =>  4,
        '6' =>  15,
        '7' =>  44,
        '8' =>  6,
        '9' =>  30,
        '10' =>  7,
        '11' =>  8,
        '12' =>  8,
    );
    //
    private static $item_template_limit = array(
            '1' => 30,
            '2' => 15,
            '3' => 24,
            '4' => 12,
    );

    // number of column in import file of ricoh_conv
    private static $max_column = 12;

    /**
     * get dropdate base on  school payment_date
     * @return dropdate
     */
    public function getDropdate() {
        // 引落日
        $down_type = session()->get('school.login')['payment_date'];
        if ($down_type == 99){
            $dropdate = strtotime(date('Y-m-t'));
        }else if ($down_type >= date('d')){
            $dropdate = strtotime(date('Y-m-'.$down_type));
        }else{
            $dropdate = strtotime(date('Y-m-'.$down_type) . '+1 month');
        }
        return $dropdate;
    }
    public function encode36 ($num, $len) {
        $str = base_convert($num, 10, 36);
        $ret = str_pad($str, $len, "0", STR_PAD_LEFT);
        return $ret;
    }

    public function getHeaderRecord($pdata, $bdata, $dropdate) {

        $post = array();
        //1 データ区分1 半角数字1 １＝ヘッダーレコード
        $post[1] = $this->strSpace(1, 1);
        //2 種別コード2 半角数字91 91＝口座振替
        $post[2] = $this->strSpace(91, 2);
        //3 コード区分1 半角数字0 0＝ＪＩＳコード
        $post[3] = $this->strSpace(0, 3);
        //4 委託者コード10 半角数字委託者コード（提携先様の採番通りでも可）
        $post[4] = $this->strSpace($bdata['consignor_code'], 4);
        //5 委託者名（上） 20 半角英数ｶﾅ
        $post[5] = $this->strSpace(mb_substr($bdata['consignor_name'], 0, 20, "UTF-8") , 5);
        //6 委託者名（下） 20 半角英数ｶﾅ
        $post[6] = $this->strSpace(mb_substr($bdata['consignor_name'], 20, 20, "UTF-8"), 6);
        //7 引落日4 半角数字MMDD（月日）
        $post[7] = $this->strSpace(date('md', $dropdate), 7);
        //8 取引銀行番号4 半角数字
        if ( $bdata['bank_type'] == 2 ){
            $post[8] = $this->strSpace(9900, 8);
        }else{
            $post[8] = $this->strSpace($bdata['bank_code'], 8);
        }
        //9 取引銀行名15 半角英数ｶﾅ
        if ( $bdata['bank_type'] == 2 ){
            $post[9] = $this->strSpace("", 9);
        }else{
            $post[9] = $this->strSpace($bdata['bank_name'], 9);
        }
        //10 取引支店番号3 半角数字
        if ( $bdata['bank_type'] == 2 ){
            $post_account_kigou = $bdata['post_account_kigou'];
            if (strlen($post_account_kigou) > 3) $post_account_kigou = substr($post_account_kigou, 1,3);
            $post[10] = $this->strSpace($post_account_kigou, 10);
        }else{
            $post[10] = $this->strSpace($bdata['branch_code'], 10);
        }
        //11 取引支店名15 半角英数ｶﾅ
        if ( $bdata['bank_type'] == 2 ){
            $post[11] = $this->strSpace("", 11);
        }else{
            $post[11] = $this->strSpace($bdata['branch_name'], 11);
        }
        //12 預金種目1 半角数字
        if ( $bdata['bank_type'] == 2 ){
            $post[12] = $this->strSpace(1, 12);
        }else{
            $bank_account_type = $bdata['bank_account_type'];
            if ($bank_account_type != 2) $bank_account_type = 1;
            $post[12] = $this->strSpace($bank_account_type, 12);
        }
        //13 口座番号7 半角数字
        if ( $bdata['bank_type'] == 2 ){
            $post[13] = $this->strSpace($bdata['post_account_number'], 13);
        }else{
            $post[13] = $this->strSpace($bdata['bank_account_number'], 13);
        }
        //14 余白17 半角スペーススペース全て半角スペース
        $post[14] = $this->strSpace("", 14);
        //*/
        return $post;
    }

    public function getDataRecord($pdata, $bdata, $receipt){
        $post = array();
        //15 データ区分1 半角数字2 ２＝データレコード
        $post[15] = $this->strSpace(2, 15);
        //16 引落銀行番号4 半角数字郵便局は9900
        if ($bdata['bank_type'] == 2){
            $post[16] = $this->strSpace(9900, 16);
        }else{
            $post[16] = $this->strSpace($bdata['bank_code'], 16);
        }
        //17 引落銀行名15 半角英数ｶﾅ
        if ($bdata['bank_type'] == 2){
            $post[17] = $this->strSpace("", 17);
        }else{
            $post[17] = $this->strSpace($bdata['bank_name'], 17);
        }
        //18 引落支店番号3 半角数字郵便局は、通帳記号「１●●●０」の内、「●●●」の3桁を支店番号とする
        if ($bdata['bank_type'] == 2){
            $post_account_kigou = $bdata['post_account_kigou'];
            if (strlen($post_account_kigou) > 3) $post_account_kigou = substr($post_account_kigou, 1,3);
            $post[18] = $this->strSpace($post_account_kigou, 18);
        }else{
            $post[18] = $this->strSpace($bdata['branch_code'], 18);
        }
        //19 引落支店名15 半角英数ｶﾅ
        if ($bdata['bank_type'] == 2){
            $post[19] = $this->strSpace("", 19);
        }else{
            $post[19] = $this->strSpace($bdata['branch_name'], 19);
        }
        //20 余白4 半角スペーススペース全て半角スペース
        $post[20] = $this->strSpace("", 20);
        //21 預金種目1 半角数字「1：普通」と「2：当座」のみ（郵便局は１）
        if ($bdata['bank_type'] == 2){
            $post[21] = $this->strSpace(1, 21);
        }else{
            $bank_account_type = $bdata['bank_account_type'];
            if ($bank_account_type != 2) $bank_account_type = 1;
            $post[21] = $this->strSpace($bank_account_type, 21);
        }
        //22 口座番号7 半角数字郵便局は、通帳番号８桁の内、上7桁を口座番号とする。
        if ($bdata['bank_type'] == 2){
            $post[22] = $this->strSpace($bdata['post_account_number'], 22);
        }else{
            $post[22] = $this->strSpace($bdata['bank_account_number'], 22);
        }
        //23 預金者名30 半角英数ｶﾅ
        if ($bdata['bank_type'] == 2){
            $post[23] = $this->strSpace($bdata['post_account_name'], 23);
        }else{
            $post[23] = $this->strSpace($bdata['bank_account_name_kana'], 23);
        }
        //24 引落金額10 半角数字
        $post[24] = $this->strSpace($receipt, 24);
        //25 新規コード（※） 1 半角数字「1：１回目（新規）」か「2：変更分」か「0：２回目以降」のみ
        $post[25] = $this->strSpace(0, 25);
        //26 顧客番号20 半角英数字コレクト！画面上は下１５桁のみ表示ですが、取込に支障ございません。
        $post[26] = $this->strSpace($pdata['request_id'], 26);
        //27 振替結果コード1 半角数字0 請求時は０をセット。振替結果連絡時には、振替結果コード（※）をセット
        $post[27] = $this->strSpace(0, 27);
        //28 余白8 半角スペース全て半角スペース
        $post[28] = $this->strSpace("", 28);

        return $post;
    }
    private function strSpace($str, $len){
        // default
        if (!empty(self::$LEYOUT[$len]['default']) > 0){
            $str = self::$LEYOUT[$len]['default'];
        }
        // 小文字を大文字に変換
        //$str = strtoupper($str);
        // 全角を半角に変換
        $str = mb_convert_kana($str, "akh", "UTf-8");
        // UTF-8をSJISに変換
        $str = mb_convert_encoding($str, "SJIS", "UTf-8");

        //array('degit'=>17,'format'=>"%'.17s", 'default'=>''),		// 14
        $degit = self::$LEYOUT[$len]['degit'];
        $format = self::$LEYOUT[$len]['format'];

        if ( strpos($format, 's')  === false){
            //数字は右詰残り前ゼロ
            $str = str_pad($str, $degit, 0, STR_PAD_LEFT);
            //$str = sprintf($format, $str);
        }else{
            //文字は左詰残りスペース
            $str = str_pad($str, $degit, " ", STR_PAD_RIGHT);
            //$str = sprintf($format, $str);
        }

        if (mb_strlen($str) > $degit){
            $str = mb_substr($str, 0, $degit);
        }

        return $this->strMapping($str);
    }

    private function strMapping($str){
        $map = "";
        for ($ii = 0; $ii < strlen($str); $ii++){
            $word = substr($str, $ii, 1);
            $bin = bin2hex($word);
            $upper = hexdec(substr($bin,  0, 1));
            $lower = hexdec(substr($bin, -1, 1));

            if ($upper == 2){
                if ($lower != 8 && $lower != 9 && $lower != 13 && $lower != 14){
                    $bin = "20";
                }
            }elseif ($upper == 3){
                if ($lower > 9){
                    $bin = "20";
                }
            }elseif ($upper == 4){
                if ($lower == 0){
                    $bin = "20";
                }
            }elseif ($upper == 5){
                if ($lower > 10){
                    $bin = "20";
                }
            }elseif ($upper == 6){
                if ($lower == 0){
                    $bin = "20";
                }else{
                    $bin = dechex($upper -2).dechex($lower);
                }
            }elseif ($upper == 7){
                if ($lower > 10){
                    $bin = "20";
                }else{
                    $bin = dechex($upper -2).dechex($lower);
                }
            }elseif ($upper == 10){
                if ($lower < 5 ){
                    $bin = "20";
                }elseif ($lower == 7){
                    $bin = "b1";
                }elseif ($lower == 8){
                    $bin = "b2";
                }elseif ($lower == 9){
                    $bin = "b3";
                }elseif ($lower == 10){
                    $bin = "b4";
                }elseif ($lower == 11){
                    $bin = "b5";
                }elseif ($lower == 12){
                    $bin = "d4";
                }elseif ($lower == 13){
                    $bin = "d5";
                }elseif ($lower == 14){
                    $bin = "d6";
                }elseif ($lower == 15){
                    $bin = "c2";
                }
            }elseif ($upper == 11){
                if ($lower == 0){
                    $bin = "20";
                }
            }elseif ($upper == 12){

            }elseif ($upper == 13){

            }else{
                $bin = "20";
            }

            $map .= $bin;
        }
        return $this->hex8in($map);
    }

    private function hex8in( $str ) {
        $sbin = "";
        $len = strlen( $str );
        for ( $i = 0; $i < $len; $i += 2 ) {
            $sbin .= pack( "H*", substr( $str, $i, 2 ) );
        }

        return $sbin;
    }

    public function getTransferDateInfo($year_month, $pschool_id, $invoice_type)
    {
        $pschool = PschoolTable::getInstance()->load($pschool_id);
        $withdrawal_day = $this->getWithdrawDay($pschool_id, $invoice_type);

        $payment_style  = $pschool['payment_style'];

        if( $payment_style == 1 ){
            // 先払い
            $target_date = $year_month . "-01";
        } else {
            // 後払い
            $target_date = $year_month . "-01";
            $last_date = date('t', strtotime($target_date));
            $target_date = $year_month . "-" . $last_date;
        }

        // 同じ口座振替日
        $close_days = ClosingDayTable::getInstance()->getList(array('transfer_day'=>$withdrawal_day), array('transfer_month'=>'ASC'));
        $target_id = $close_days[0]['id'];
        $near_date = date('U', strtotime($close_days[0]['transfer_date']));
        $base_date = date('U', strtotime($target_date));

        foreach ($close_days as $close_item){
            $temp_date = date('U', strtotime($close_item['transfer_date']));
            if( abs($base_date - $temp_date) < abs($base_date - $near_date) ){
                // 基準となる日付に近いもの
                $target_id = $close_item['id'];
                $near_date = $temp_date;
            }
        }

        return ClosingDayTable::getInstance()->load($target_id);
    }
    public function getTrailerRecord($invoice_cnt, $invoice_sum){
        $post = array();
        //29 データ区分1 半角数字8 ８＝トレーラレコード
        $post[29] = $this->strSpace(8, 29);
        //30 合計件数6 半角数字データレコードの合計件数
        $post[30] = $this->strSpace($invoice_cnt, 30);
        //31 合計金額12 半角数字データレコードの合計金額
        $post[31] = $this->strSpace($invoice_sum, 31);
        //32 振替済件数6 半角数字振替結果連絡時：件数を記載
        $post[32] = $this->strSpace("", 32);
        //33 振替済金額12 半角数字振替結果連絡時：金額を記載
        $post[33] = $this->strSpace("", 33);
        //34 振替不能件数6 半角数字振替結果連絡時：件数を記載
        $post[34] = $this->strSpace("", 34);
        //35 振替不能金額12 半角数字振替結果連絡時：金額を記載
        $post[35] = $this->strSpace("", 35);
        //36 余白65 半角スペース
        $post[36] = $this->strSpace("", 36);

        return $post;
    }

    public function getEndRecord(){
        $post = array();
        //37 データ区分1 半角数字9 ９＝エンドレコード
        $post[37] = $this->strSpace(9, 37);
        //38 余白119 半角スペース
        $post[38] = $this->strSpace("", 38);

        return $post;
    }
    public function getCode($list) {
        $ret = "";
        $ii = 0;
        foreach ($list as $items){
            if ($ii != 0){
                $ret .= $this->hex8in("0D0A");
            }
            foreach ($items as $key => $item){
                $ret .= $item;
            }
            $ii++;
        }

        return $ret;
    }
    public function getFile($path, $name, $code) {

        if(!File::exists($path)) {
            // path does not exist
            // create path
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        // ファイルがなければ作成
        if(!File::exists($path."/".$name)){
            touch($path."/".$name);
        }

        // ファイルをオープン
        $file = fopen($path."/".$name,"w");

        // ファイルへ書き込み
        fwrite($file, $code);

        // ファイルを閉じる
        fclose($file);

        // ヘッダ
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $name);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($path."/".$name));

        // 対象ファイルを出力する。
        readfile($path."/".$name);
        //
    }
    public function moveFile($file, $dest_path, $pschool_id, $invoice_type){
        $res = true ; // result of moving file
        if($invoice_type == Constants::$PAYMENT_TYPE['TRAN_RICOH']){
            $upload_path = storage_path('app/uploads/school/' .$pschool_id."/ricoh_trans/upload");
        }elseif($invoice_type == Constants::$PAYMENT_TYPE['CONV_RICOH']){
            $upload_path = storage_path('app/uploads/school/' .$pschool_id."/ricoh_conv/upload");
        }

        if(!File::exists($upload_path)) {
            // path does not exist
            // create path
            File::makeDirectory($upload_path, $mode = 0777, true, true);
        }
        if (!File::copy($file, $dest_path)) {
            $res = false;
        }
        return $res;
    }
    public function getSplitUploadFile($data, $mode){

        $array_data = array();

        $start_idx = 0;
        $end_idx   = 0;

        switch($mode){
            case 1:		// ヘッダーレコード
                $start_idx = 1;
                $end_idx   = 14;
                if( mb_strlen($data) != self::HEADER_RECORD_LEN )	return null;
                break;
            case 2:		// データレコード
                $start_idx = 15;
                $end_idx   = 28;
                if( mb_strlen($data) != self::DATA_RECORD_LEN )	return null;
                break;
            case 8:		// トレーラレコード
                $start_idx = 29;
                $end_idx   = 36;
                if( mb_strlen($data) != self::TRAILER_RECORD_LEN )	return null;
                break;
            case 9 :	// エンドレコード
                $start_idx = 37;
                $end_idx   = 38;
                if( mb_strlen($data) != self::END_RECORD_LEN )	return null;
                break;
            default :
                return null;
                break;
        }

        $array_data[0] = "";
        $start_pos = 0;

        // データ取得
        for( $idx = $start_idx; $idx <= $end_idx; $idx++){
            $array_data[$idx] = mb_substr($data, $start_pos, self::$LEYOUT[$idx]['degit']);
            $start_pos += self::$LEYOUT[$idx]['degit'];
        }

        return $array_data;
    }
    public function exportShiftJs($path , $data ,$fileName){

        $fileName = mb_convert_encoding($fileName, 'sjis-win', 'UTF-8');
        $fileName.= ".csv";

        if(!File::exists($path)) {
            // path does not exist
            // create path
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        // ファイルがなければ作成
        if(!File::exists($path."/".$fileName)){
            touch($path."/".$fileName);
        }

        // ファイルをオープン
        $file = fopen($path."/".$fileName,"w");

        // ファイルへ書き込み
        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        // ファイルを閉じる
        fclose($file);

        // ヘッダ

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($path."/".$fileName));

        // 対象ファイルを出力する。
        readfile($path."/".$fileName);
        //
//        try{
//            header('Content-Type: text/plain;charset=UTF-8');
//            header("Cache-Control:private");
//            header("Content-Type: application/octet-stream");
//            header("Content-Disposition: attachment; filename=" . $fileName . '.csv');
//
//            $fp = fopen('php://output', 'w');
//            foreach ($data as $row) {
//                fputcsv($fp, $row);
//            }
//            fclose($fp);
//            //$dest_path = "app/uploads/school/" . session()->get('school.login.id')."/ricoh_conv/download/".$fileName.".csv";
//            //$this->moveFile($fp,$dest_path,session()->get('school.login.id'),Constants::$PAYMENT_TYPE['CONV_RICOH']);
//
//        }catch(Exception $e){
//            return false;
//        }
        return true;
    }

    public function processDataRicohConv($data_rough){

        //dd($data_rough);
        $res = array();
        foreach ($data_rough as $key => $data){
            //
            $res[$key] = self::$csv_row_data;
            $data['code'] = $this->generateCustomerCode($data['id']);
            //
            $res[$key][1] = $this->convertData($data,'code',1);
            $res[$key][2] = $this->convertData($data,'parent_name',2);
            $res[$key][3] = $this->convertData($data,'parent_name_kana',3);
            $res[$key][4] = $this->convertData($data,'zip_code',4);
            //$res[$key][5] = $this->convertData($data,'zip_code1',5);
            $res[$key][5] = mb_convert_encoding(mb_substr($data['p_pref_name'].$data['p_city_name'].$data['address'],0,self::$csv_row_limit[5],'sjis-win'),"sjis-win","UTF-8");
            $res[$key][6] = $this->convertData($data,'building',6);
            $res[$key][7] = $this->convertData($data,'phone_no',7);
            $res[$key][8] = $this->convertData($data,'amount',8);
            $res[$key][9] = mb_convert_encoding(date("Ymd",strtotime($data['due_date'])),"sjis-win","UTF-8");
            $res[$key][10] = $this->convertData($data,'tax_price',10);
            $res[$key][11] = $this->convertData($data,'parent_name',11);
            $res[$key][12] = 1 ; // 1 = Mr.
//            $res[$key][13] = $this->convertData($data,'sales_tax_rate',13);
//            $res[$key][14] = $this->convertData($data,'sales_tax_rate',14);
//            $res[$key][15] = $this->convertData($data,'sales_tax_rate',15);
//            $res[$key][16] = $this->convertData($data,'sales_tax_rate',16);
            $res[$key][17] = 1 ;
//            $res[$key][18] = ;
            $res[$key][19] = 0 ;  // have not yet setting convenient page
//            $res[$key][20] = ;
//            $res[$key][21] = ;
//            $res[$key][22] = ;
//            $res[$key][23] = ;
//            $res[$key][24] = ;
//            $res[$key][25] = ;
//            $res[$key][26] = ;
//            $res[$key][27] = ;
//            $res[$key][28] = ;
//            $res[$key][29] = ;
            $res[$key][30] = $this->convertData($data,'school_proviso',30);
            $res[$key][31] = mb_convert_encoding(mb_substr("項目",0,30, 'sjis-win'),"sjis-win","UTF-8");
            $res[$key][32] = mb_convert_encoding(mb_substr("数量",0,15, 'sjis-win'),"sjis-win","UTF-8");
//            $res[$key][33] = ;
            $res[$key][34] = mb_convert_encoding(mb_substr("金額",0,6, 'sjis-win'),"sjis-win","UTF-8");

            // generate items , each item will take 4 columns
            foreach ($data['item_list'] as $k => $item) {
                $res[$key][] = $this->convertDataItem($item['item_name'],1);
                $res[$key][] = 1;
                $res[$key][] = "";
                $res[$key][] = $this->convertDataItem($item['unit_price'],4);
            }
        }

        return $res;
    }

    /**
     * currently not encrypting or combine string so only return invoice_header_id
     * will modify if need more security :)))))))
     * @param $header_id
     * @return $header_id
     */
    private function generateCustomerCode($header_id){
        return $header_id;
    }

    /**
     * check if isset offset in data or not
     * if exists then sub string base on file's format
     * @param $data : list parent
     * @param $offset : base on header name
     * @param $key : order of header
     * @return string
     */
    private function convertData($data, $offset, $key){
        if(isset($data[$offset]) ){
            return  mb_convert_encoding(mb_substr($data[$offset],0,self::$csv_row_limit[$key],'sjis-win'),"sjis-win","UTF-8");
        }else{
            return "";
        }
    }

    /**
     *return null or trim of item data base on item_template_limit
     */

    private function convertDataItem($data, $key){
        return mb_convert_encoding(mb_substr($data,0,self::$item_template_limit[$key],'UTF-8'),"sjis-win","UTF-8");
    }

    public function processFileRicohConv($file_path){

        // default delimiter
        $delimiter  =  ',';

        //check is file exists
        if(!File::exists($file_path)){
            return "error_missing_file";
        }

        //processing
        $data_record = array();
        try{
            $file = fopen($file_path, "r");
            while($f_data = fgetcsv($file,$delimiter)){
                if(sizeof($f_data) > self::$max_column ){
                    return "wrong_format_file";
                }
                $temp = array();

                if($f_data[1] == 02){
                    foreach($f_data as $k=>$data){

                        $temp[$k+1] = mb_convert_encoding(mb_substr($data,0,self::$item_import_limit[$k+1],"sjis-win"),"UTF-8","sjis-win");
                    }
                    $data_record[] = $temp;
                }
            }
            return $data_record;
        }catch(Exception $e){
            return "error_missing_file";
        }

    }

    /**
     * @param $pschool_id
     * @param $invoice_type
     * @return int : withdrawDay
     */
    public function getWithdrawDay ($pschool_id, $invoice_type)
    {
        $bind = array(
            $invoice_type,
            $pschool_id
        );
        $sql = "SELECT pms.default_value, pmd.item_value
              FROM payment_method pm
              LEFT JOIN payment_method_setting pms ON pm.id = pms.payment_method_id AND pm.payment_agency_id = pms.payment_agency_id
              LEFT JOIN payment_method_data  pmd ON pmd.payment_method_setting_id = pms.id
              WHERE pm.id = ?
              AND pms.item_name = 'withdrawal_date' 
              AND pmd.pschool_id = ?
              AND pm.delete_date IS NULL 
              AND pms.delete_date IS NULL 
              ";
        $res = PaymentMethodTable::getInstance()->fetch($sql, $bind);
        if (empty($res)) {
            // avoid error when not setting withdrawal_date for method => return default 27
            $withdrawal_day = 27;
        } else {
            $default_value = explode(";", $res['default_value']);
            $value = $default_value[$res['item_value'] - 1];
            $payment_date = explode(":", $value);
            $withdrawal_day = $payment_date[1];
        }
        return $withdrawal_day;
    }

}