<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\MessageFile;
use App\Message;
use App\ConstantsModel;
use File;
use Excel;
trait BreadFileHandler
{
    /**
     * Delete file if exist
     *
     * @param $file_name
     *
     */
    public function destroy_file($file_name) {
        if (File::exists(resource_path(). $file_name)) {
            File::delete(resource_path(). $file_name);
        }
    }

    /**
     * Create new File
     *
     * @param $file_name
     * @param $contents
     *
     */
    public function create_file($file_name, $contents=null) {
        if (!File::exists(resource_path(). $file_name)) {
            
            file_put_contents(resource_path(). $file_name, $contents);
        }
    }

    /**
     * Write content of File
     *
     * @param $attributes
     * @param $data
     *
     * @return contents
     */
    public function write_content_message($screens, $messages=array()) {
        // file_message struture:
        // "screen_key" => [   "message_key1" => ["message_value1", "comment1"],
        //                     "message_key2" => ["message_value2", "comment2"],
        //                     ...
        //                  ],
        $screen_arr = array();

        foreach ($screens as $key => $value) {
            // $screen_arr['school.home'] = \n "school.home => [ " 
            $screen_arr[$value['screen_key']] = PHP_EOL.'"'.$value['screen_key'] .'" => [ ';
        }
        
        foreach ($messages as $key => $value) {
            $row = "";
            if (array_key_exists($value['screen_key'], $screen_arr)) {
                // $row = \n "main_title" => ["HOME", "home screen"],
                $row .= PHP_EOL.'       "'.$value['message_key'] .'" => ["'.$value['message_value'] .'", "'.$value['comment'] .'"],';
                $screen_arr[$value['screen_key']] .= $row;
            }
        }
        
        foreach ($screen_arr as $key => &$value) {
            $value .= PHP_EOL.']';
        }
       
        $contents = '<?php '.PHP_EOL.PHP_EOL.PHP_EOL.'return [' . implode(',', $screen_arr). PHP_EOL.'];';
        return $contents;
    }

    /**
    * Export Message File csv
    * @param $message_file_id
    * @param $message_file_name
    */
    public function export_message_csv($message_file_id, $message_file_name) {

        Excel::create($message_file_name, function($excel) use ($message_file_id){
            
            $excel->sheet('Sheetname', function($sheet) use ($message_file_id) {
            Message::where('message_file_id', $message_file_id)->chunk(2000, function($data) use ($sheet) {
                $sheet->fromArray($data->toArray(), NULL, 'A1', false, false);
            }) ; 

        });
        })->download('csv');
        
    }

    /** 
    */
    public function create_new_message_file($file_name, $language) {

        $message_file = MessageFile::where('message_file_name', $file_name)->where('lang_code', $language)->first();

        if (!empty($message_file)) {
            $screen_list = Message::where('message_file_id', $message_file->id)->whereNull('message_key')->get();
            $message_content = Message::where('message_file_id', $message_file->id)->whereNotNull('message_key')->get();
            
            $lang_setting = ConstantsModel::$lang_setting;
            $lang_file_path = '/lang/' . $lang_setting[$language] .'/'.$file_name.'.php';

            $this->destroy_file($lang_file_path);
            $contents = $this->write_content_message($screen_list->toArray(), $message_content->toArray());
            $this->create_file($lang_file_path, $contents);
        }
        
    }

}
