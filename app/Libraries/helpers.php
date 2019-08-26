<?php
/**
 * Created by PhpStorm.
 * User: asto-user3
 * Date: 2017-12-06
 * Time: 1:17 PM
 */

if (! function_exists('array_get_not_null')) {
    /**
     * Get an item from an array using "dot" notation without null value
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get_not_null($array, $key, $default = null)
    {
        $value = array_get($array, $key, $default);
        return $value !== null ? $value : $default;
    }
}