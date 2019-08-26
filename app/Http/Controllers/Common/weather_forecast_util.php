<?php

namespace App\Http\Controllers\Common;

require_once 'MPrefTable.php';
require_once 'MCityTable.php';
require_once 'PschoolTable.php';
define ( 'WEATHER_URL_JP', 'https://weather.yahoo.co.jp/weather/search' );
define ( 'WEATHER_RSS_JP', 'https://rss.weather.yahoo.co.jp/rss/days/' );
define ( 'WEATHER_URL_EN', 'https://weather.codes/search' );
define ( 'WEATHER_RSS_EN', 'https://weather.yahooapis.com/forecastrss?p=' );
function getWeatherRssLinkJP($data) {
    if (empty ( $data ['pref_id'] ) || empty ( $data ['city_id'] )) {
        return "";
    }
    $pref = MPrefTable::getInstance ()->load ( $data ['pref_id'] );
    $city = MCityTable::getInstance ()->load ( $data ['city_id'] );
    $weather_url = WEATHER_URL_JP . '/?p=' . $pref ['name'] . ' ' . $city ['name'];
    $weather_page = file_get_html ( $weather_url );
    $city_id = '';
    foreach ( $weather_page->find ( 'a' ) as $element ) {
        if (! empty ( $element->href ) && ($element->href != '#contents-start') && ($element->href != 'https://weather.yahoo.co.jp/weather/personal/') && ($element->href != '/weather/') && ($element->href != 'http://weather.yahoo.co.jp/weather/personal/weather/')) {
            $forecast_url = $element->href;
            $parts = explode ( '/', $forecast_url );
            $city_id = (isset ( $parts [6] )) ? $parts [6] : '';
            // $city_id = $parts [6];
            break;
        }
    }
    $forecast_rss = WEATHER_RSS_JP . $city_id . '.xml';
    $forecast_rss = str_replace ( '<dt>', '', $forecast_rss );
    $forecast_rss = str_replace ( '</dt>', '', $forecast_rss );
    return $forecast_rss;
}
function getWeatherRssLinkEn($data) {
    $forecast_rss = '';
    $country = ConstantsModel::$country_list ['2'] [$data ['country_code']];
    $address = $data ['address'];
    $parts = explode ( ',', $address );
    $city_name_str = end ( $parts );
    $city_name_str = strtolower ( $city_name_str );
    $city_parts = explode ( ' ', $city_name_str );
    $city_name = "";
    $cnt = count ( $city_parts );
    for($i = 0; $i < $cnt; $i ++) {
        $part = $city_parts [$i];
        if (! empty ( $part )) {
            $city_name .= $part;
            if ($i != $cnt - 1) {
                $city_name .= ' ';
            }
        }
    }
    $weather_url = WEATHER_URL_EN . '/?q=' . $city_name;
    $weather_url = str_replace ( ' ', '', $weather_url );
    $file = file_get_contents ( $weather_url );
    $weather_page = str_get_html ( $file );
    $loc_name = $city_name . ', ' . $country;
    $weather_code = '';
    if (! empty ( $weather_page )) {
        foreach ( $weather_page->find ( 'dd' ) as $element ) {
            $loc = $element->text ();
            $substr = stristr ( $loc, $loc_name );
            if ($substr || (strtolower ( $loc ) == strtolower ( $loc_name ))) {
                $weather_code = $element->prev_sibling ();
                break;
            }
        }
    }
    $forecast_rss = WEATHER_RSS_EN . $weather_code;
    $forecast_rss = str_replace ( '<dt>', '', $forecast_rss );
    $forecast_rss = str_replace ( '</dt>', '', $forecast_rss );
    return $forecast_rss;
}