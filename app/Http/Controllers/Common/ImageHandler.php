<?php

namespace App\Http\Controllers\Common;

class ImageHandler {
    private static $BUFF_SIZE = 1024;
    private $_width; // 表示しようとする最大の幅
    private $_height; // 表示しようとする最大の高さ
    private $_per; // 縮尺
                   // add@20090326
    private $_nomargin = 0; // 指定最大高さ、指定最大幅に対してマージンを作らない大きさにする。
                            // add@20090326
    private $_quality; // JPEG画像の質
    private $_nocompress; // 縮尺を変更しない
    private $_file; // 画像ファイルパス
    private $_data = ''; // 読み込んだデータ
    private $_dataType = ''; // 読み込んだデータのタイプ（gif, jpg, png）
    private $_dataWidth = 0; // 読み込んだデータの幅
    private $_dataHeight = 0; // 読み込んだデータの高さ
    
    /**
     * コンストラクタ
     *
     * @param unknown_type $path
     *            ファイルパス
     * @param unknown_type $mediaType
     *            メディアタイプ
     * @param unknown_type $params
     *            表示パラメータ（w=最大幅、h=最大高さ、per=パーセント）の配列
     */
    public function __construct($path, $mediaType, $params = array()) {
        if (count ( $params ) == 0) {
            $this->_nocompress = TRUE;
        } else {
            $this->_width = (isset ( $params ['w'] ) && preg_match ( '/^[0-9]+$/', $params ['w'] )) ? $params ['w'] : 0;
            $this->_height = (isset ( $params ['h'] ) && preg_match ( '/^[0-9]+$/', $params ['h'] )) ? $params ['h'] : 0;
            $this->_per = (isset ( $params ['per'] ) && preg_match ( '/^[0-9]+$/', $params ['per'] )) ? $params ['per'] : 0;
            
            $this->_nomargin = isset ( $params ['nm'] ) ? 1 : 0;
        }
        $this->_file = BASE_DATA_DIR . 'upload' . DIRECTORY_SEPARATOR . $path;
        
        $this->_quality = defined ( "DEFAULT_JPEG_QURITY" ) ? DEFAULT_JPEG_QURITY : 75;
    }
    
    /**
     * JPEGの画質を指定する。
     *
     * @param unknown_type $qly            
     */
    public function setJpegQuality($qly) {
        $this->_quality = $qly;
    }
    
    /**
     * 出力の実行
     */
    public function execute() {
        if (! file_exists ( $this->_file )) {
            HeaderUtil::fileNotFound ( false );
            exit ();
        }
        if (! $this->getImageInfo ()) {
            HeaderUtil::fileNotFound ( false );
            exit ();
        }
        if ($this->_nocompress) {
            if ($this->_dataType == "gif") {
                header ( "Content-type: image/gif" );
            } elseif ($this->_dataType == "jpg") {
                header ( "Content-type: image/jpeg" );
            } elseif ($this->_dataType == "png") {
                header ( "Content-type: image/png" );
            }
            print $this->_data;
        } else {
            $image = $this->compress ();
            $this->output ( $image );
            imagedestroy ( $image );
        }
    }
    public function executefile($path) {
        if (! file_exists ( $this->_file )) {
            // HeaderUtil::fileNotFound(false);
            // exit();
            return;
        }
        $this->getImageInfo ();
        $image = $this->compress ();
        $this->outputfile ( $image, $path );
        imagedestroy ( $image );
    }
    
    /**
     * ユーティリティー的な使い方
     */
    public function saveToFile($path, $type) {
        if (! file_exists ( $this->_file )) {
            return false;
        }
        if (! $this->getImageInfo ()) {
            return false;
        }
        if ($this->_nocompress) {
            $fp = fopen ( $path, "wb" );
            $ret = fwrite ( $fp, $this->_data );
            if ($ret === FALSE) {
                return false;
            }
            fclose ( $fp );
        } else {
            $image = $this->compress ();
            $this->outputToFile ( $image, $path, $type );
            imagedestroy ( $image );
        }
        return true;
    }
    
    /**
     * 等比圧縮時の実際の出力処理
     *
     * @param unknown_type $image
     *            データ
     */
    protected function output($image) {
        if ($this->_dataType == "gif") {
            header ( "Content-type: image/gif" );
            imagegif ( $image );
        } elseif ($this->_dataType == "jpg") {
            header ( "Content-type: image/jpeg" );
            // ImageJpeg($image, "", $this->_quality);
            imagejpeg ( $image );
        } elseif ($this->_dataType == "png") {
            header ( "Content-type: image/png" );
            imagepng ( $image );
        }
    }
    
    /**
     * 等比圧縮時の実際の出力処理（ファイルに...）本当は上のを拡張すべきだけどね...
     *
     * @param unknown_type $image
     *            データ
     * @param string $file
     *            ファイル名
     */
    protected function outputToFile($image, $file, $type) {
        if ($type == "gif") {
            imagegif ( $image, $file );
        } elseif ($type == "jpg") {
            imagejpeg ( $image, $file );
        } elseif ($type == "png") {
            imagepng ( $image, $file );
        }
    }
    
    /**
     * 等比圧縮の処理
     *
     * @return unknown 等比圧縮した結果データ
     */
    private function compress() {
        // サイズ計算
        $newSize = $this->calculateImageSzie ();
        // 元画像の再構成
        $oldImage = imagecreatefromstring ( $this->_data );
        if ($oldImage == FALSE) {
            // TODO 元のイメージの読み込みに失敗した。
            return null;
        }
        // 画像リソースの作成
        // gifはImageCreateTrueColorでサポートされていないみたい
        if (function_exists ( "imagecreatetruecolor" ) && $this->_dataType != "gif") {
            // if (function_exists("imagecreatetruecolor")) {
            $newImage = imagecreatetruecolor ( $newSize [0], $newSize [1] );
        } else {
            $newImage = imagecreate ( $newSize [0], $newSize [1] );
        }
        
        // ブレンドモードを無効にする
        imagealphablending ( $newImage, false );
        
        // 完全なアルファチャネル情報を保存するフラグをonにする
        imagesavealpha ( $newImage, true );
        
        // サイズ変換
        if (function_exists ( "imagecopyresampled" )) {
            if (isset ( $newSize [2] )) {
                imagecopyresampled ( $newImage, $oldImage, 0, 0, $newSize [2], $newSize [3], $newSize [0], $newSize [1], $this->_dataWidth - ($newSize [2] * 2), $this->_dataHeight - ($newSize [3] * 2) );
            } else {
                imagecopyresampled ( $newImage, $oldImage, 0, 0, 0, 0, $newSize [0], $newSize [1], $this->_dataWidth, $this->_dataHeight );
            }
        } else {
            if (isset ( $newSize [2] )) {
                imagecopyresized ( $newImage, $oldImage, 0, 0, $newSize [2], $newSize [3], $newSize [0], $newSize [1], $this->_dataWidth - ($newSize [2] * 2), $this->_dataHeight - ($newSize [3] * 2) );
            } else {
                imagecopyresized ( $newImage, $oldImage, 0, 0, 0, 0, $newSize [0], $newSize [1], $this->_dataWidth, $this->_dataHeight );
            }
        }
        // echo $newSize[0] . "," . $newSize[1] . ":" . $this->_dataWidth . "," . $this->_dataHeight . ":" . $this->_per . "," . $this->_width . "," . $this->_height;
        // die();
        imagedestroy ( $oldImage );
        return $newImage;
        // return $oldImage;
    }
    
    /**
     * 等比にする際のイメージサイズの計算
     *
     * @return unknown 圧縮後の幅、高さの配列
     */
    private function calculateImageSzie() {
        if ($this->_width == 0 && $this->_height == 0 && $this->_per == 0) {
            return array (
                    $this->_dataWidth,
                    $this->_dataHeight 
            );
        }
        if ($this->_width == 0 && $this->_height == 0 && $this->_per != 0) {
            return array (
                    $this->_dataWidth * $this->_per / 100,
                    $this->_dataHeight * $this->_per / 100 
            );
        }
        if ($this->_width != 0 && $this->_height == 0) {
            $ratio = $this->_width / $this->_dataWidth;
            return array (
                    $this->_width,
                    $this->_dataHeight * $ratio 
            );
        }
        if ($this->_width == 0 && $this->_height != 0) {
            $ratio = $this->_height / $this->_dataHeight;
            return array (
                    $this->_dataWidth * $ratio,
                    $this->_height 
            );
        }
        $wRatio = $this->_width / $this->_dataWidth;
        $hRatio = $this->_height / $this->_dataHeight;
        if ($this->_nomargin == 1) {
            if ($wRatio < $hRatio) {
                return array (
                        $this->_width,
                        $this->_height,
                        ($this->_dataWidth - $this->_width * $this->_dataHeight / $this->_height) / 2,
                        0 
                );
            } else if ($wRatio > $hRatio) {
                return array (
                        $this->_width,
                        $this->_height,
                        0,
                        ($this->_dataHeight - $this->_height * $this->_dataWidth / $this->_width) / 2 
                );
            } else {
                return array (
                        $this->_width,
                        $this->_height 
                );
            }
        } else {
            if ($wRatio > $hRatio) {
                return array (
                        $this->_dataWidth * $hRatio,
                        $this->_height 
                );
            } else if ($wRatio < $hRatio) {
                return array (
                        $this->_width,
                        $this->_dataHeight * $wRatio 
                );
            } else {
                return array (
                        $this->_width,
                        $this->_height 
                );
            }
        }
    }
    
    /**
     * 画像情報を取得する
     */
    private function getImageInfo() {
        $this->_dataWidth = 0;
        $this->_dataHeight = 0;
        $this->_dataType = '';
        $this->_data = '';
        
        $fp = fopen ( $this->_file, 'rb' );
        if ($fp) {
            while ( ! feof ( $fp ) ) {
                $this->_data .= fread ( $fp, self::$BUFF_SIZE );
            }
            fclose ( $fp );
        }
        
        if (empty ( $this->_data )) {
            return false;
        }
        
        if (preg_match ( "/^\x47\x49\x46/", $this->_data )) {
            $this->_dataType = 'gif';
        } elseif (preg_match ( "/^\xFF\xD8/", $this->_data )) {
            $this->_dataType = 'jpg';
        } elseif (preg_match ( "/^\x89\x50\x4E\x47\x0D\x0A\x1A\x0A/", $this->_data )) {
            $this->_dataType = 'png';
        } else {
            return false;
        }
        $im = imagecreatefromstring ( $this->_data );
        $this->_dataWidth = imagesx ( $im );
        $this->_dataHeight = imagesy ( $im );
        // echo $this->_dataWidth . "," . $this->_dataHeight;
        // die();
        return true;
    }
    
    /**
     * 等比圧縮時の実際の出力処理
     *
     * @param unknown_type $image
     *            データ
     */
    protected function outputfile($image, $path) {
        if ($this->_dataType == "gif") {
            imagegif ( $image, $path );
        } elseif ($this->_dataType == "jpg") {
            $quality = 100;
            if (empty ( $this->_size )) {
                imagejpeg ( $image, $path, $quality );
                // var_dump("path:".$path." q:".$quality."size:". filesize($path).":".$this->_size);
            } else {
                while ( $quality > 20 ) {
                    imagejpeg ( $image, $path, $quality );
                    // var_dump("path:".$path." q:".$quality."size:". filesize($path).":".$this->_size);
                    if (filesize ( $path ) < $this->_size)
                        break;
                    clearstatcache ();
                    $quality -= 3;
                }
            }
            // var_dump("q:".$quality.$path);
        } elseif ($this->_dataType == "png") {
            imagepng ( $image, $path );
        }
    }
}

