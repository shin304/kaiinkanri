<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    private static $_message_arr = null;

    public function __construct($arr=null) {
    	self::$_message_arr = $arr;
    }
    
    /**
    * 
	* @var key
	* @return value message by key
	*/
	public static function get($key) {
        if (array_key_exists($key, self::$_message_arr)) {
            return self::$_message_arr[$key][0];
        } else {
            $arr = explode(',', $key);
            // sprintf('%dmessage', 1);
            if (count($arr) > 1 && array_key_exists($arr[0], self::$_message_arr)) {
                return sprintf(self::$_message_arr[$arr[0]][0], $arr[1]);
            } else {
                return $key;
            }

        }
	}
}
