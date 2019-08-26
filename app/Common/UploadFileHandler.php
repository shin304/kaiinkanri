<?php
namespace App\Common;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileHandler {
    private static $_instance = null;
    private $_ext = null;
    private $_file_name = null;
    private $_file_path = null;
    private $_errors = array();
    private $_upload_status = null;

    /**
     * @return null
     */
    public function getExt()
    {
        return $this->_ext;
    }

    /**
     * @param null $ext
     */
    public function setExt($ext)
    {
        $this->_ext = $ext;
    }


    /**
     * @return null
     */
    public function getFileName()
    {
        return $this->_file_name;
    }

    /**
     * @param null $file_name
     */
    public function setFileName($file_name)
    {
        $this->_file_name = $file_name;
    }

    /**
     * @return null
     */
    public function getFilePath()
    {
        return $this->_file_path;
    }

    /**
     * @param null $file_path
     */
    public function setFilePath($file_path)
    {
        $this->_file_path = $file_path;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->_errors = $errors;
    }

    /**
     * @return null
     */
    public function getUploadStatus()
    {
        return $this->_upload_status;
    }

    /**
     * @param null $upload_status
     */
    public function setUploadStatus($upload_status)
    {
        $this->_upload_status = $upload_status;
    } // 0: error, 1: success



    /**
     *
     * @return UploadFileHandler
     */
    public static function getInstance() {
        if (is_null ( self::$_instance )) {
            self::$_instance = new UploadFileHandler();
        }
        return self::$_instance;
    }

    public function uploadImage(UploadedFile $file = null, $path) {
        if (!is_null($file)) {
            if($file->isValid()){
                // TODO process upload file to storage
                $mime_type =  $file->getMimeType();
                if (array_key_exists($mime_type, Constants::IMG_MIME_TYPES) && !is_null($path)) {
                    $ext = array_get(Constants::IMG_MIME_TYPES, $mime_type);
                    $file_name = md5(time()). "." . $ext;
                    // Move to storage.
                    try{
                        $file->move($path, $file_name);
                        $this->_ext = $ext;
                        $this->_file_name = $file_name;
                        $this->_file_path = $path . $file_name;
                        $this->_upload_status = 1; // Upload success
                    } catch (\Exception $e) {
                        $errors[] = "upload_file_error_msg";
                        $this->_upload_status = 0;
                        $this->_errors = $errors;
                    }
                } else {
                    $errors[] = "upload_file_error_msg";
                    $this->_upload_status = 0;
                    $this->_errors = $errors;
                }
            } else {
                // TODO process error messages
                $upload_max_size = ini_get('upload_max_filesize');
                $errors = array();
                switch ($file->getError()) {
                    case UPLOAD_ERR_INI_SIZE: // File size exceeds upload_max_filesize (php.ini)
                        $errors[] = "upload_max_size_error_msg," . $upload_max_size;
                        break;
                    case UPLOAD_ERR_FORM_SIZE: // File size exceeds the upload limit defined in form
                        $errors[] = "upload_max_size_error_msg," . $upload_max_size;
                        break;
                    case UPLOAD_ERR_PARTIAL: // The file was only partially uploaded
                        $errors[] = "upload_file_error_msg";
                        break;
                    case UPLOAD_ERR_NO_FILE: // No file was uploaded.
                        $errors[] = "upload_file_error_msg";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR: // The file could not be written on disk. Server permission or capacity problems
                        $errors[] = "upload_file_error_msg";
                        break;
                    case UPLOAD_ERR_CANT_WRITE; // File could not be uploaded: missing temporary directory
                        $errors[] = "upload_file_error_msg";
                        break;
                    case UPLOAD_ERR_EXTENSION; // File upload was stopped by a PHP extension.
                        $errors[] = "upload_file_error_msg";
                        break;
                    default:
                        $errors[] = "upload_file_error_msg";
                        break;
                }
                $this->_upload_status = 0; // Upload errors
                $this->_errors = $errors;
            }
        }
        return $this;
    }
}