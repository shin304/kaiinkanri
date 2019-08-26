<?php
use Illuminate\Http\Request;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */

Route::post ('/info/version', 'Api\InfoController@executeVersion');

Route::post ('/master/pref', 'Api\MasterController@executePref');
Route::post ('/master/city', 'Api\MasterController@executeCity');
Route::post ('/master/industryType', 'Api\MasterController@executeIndustryType');
Route::post ('/master/employeesType', 'Api\MasterController@executeEmployeesType');

Route::post ('/account/entry', 'Api\AccountController@executeEntry');
Route::post ('/account/login', 'Api\AccountController@executeLogin');
Route::post ('/account/info', 'Api\AccountController@executeInfo');
Route::post ('/account/edit', 'Api\AccountController@executeEdit');
Route::post ('/account/keyauth', 'Api\AccountController@executeKeyAuth');
Route::post ('/account/keysend', 'Api\AccountController@executeKeySend');
Route::post ('/account/getRecords', 'Api\AccountController@executeGetRecords');
Route::post ('/account/delRecords', 'Api\AccountController@executeDelRecords');
Route::post ('/account/infoList', 'Api\AccountController@executeInfoList');

Route::post ('/accountex/copyAnth', 'Api\AccountExController@executeCopyAuth');
Route::post ('/accountex/copyRegist', 'Api\AccountExController@executeCopyRegist');
Route::post ('/accountex/mailPreAuth', 'Api\AccountExController@executeMailPreAuth');
Route::post ('/accountex/mailPostAuth', 'Api\AccountExController@executeMailPostAuth');
Route::post ('/accountex/mailUpdate', 'Api\AccountExController@executeMailUpdate');
Route::post ('/accountex/renkeiAuth', 'Api\AccountExController@executeRenkeiAuth');
Route::post ('/accountex/renkeiRegist', 'Api\AccountExController@executeRenkeiRegist');
Route::post ('/accountex/renkeiRemove', 'Api\AccountExController@executeRenkeiRemove');
Route::post ('/accountex/item', 'Api\AccountExController@executeItem');
Route::post ('/accountex/feeList', 'Api\AccountExController@executeFeeList');
Route::post ('/accountex/feeResist', 'Api\AccountExController@executeFeeResist');
Route::post ('/accountex/feeRemove', 'Api\AccountExController@executeFeeRemove');

Route::post ('/workbook', 'Api\WorkbookController@execute');
Route::post ('/workbook/list', 'Api\WorkbookController@executeList');
Route::post ('/workbook/detail', 'Api\WorkbookController@executeDetail');
Route::post ('/workbook/answer', 'Api\WorkbookController@executeAnswer');
Route::post ('/workbook/getData', 'Api\WorkbookController@executeGetData');
Route::post ('/workbook/getFile', 'Api\WorkbookController@executeGetFile');
Route::post ('/workbook/bookType', 'Api\WorkbookController@executeBookType');

Route::post ('/news', 'Api\NewsController@execute');
Route::post ('/news/list', 'Api\NewsController@executeList');
Route::post ('/news/detail', 'Api\NewsController@executeDetail');
Route::post ('/news/type', 'Api\NewsController@executeType');
Route::post ('/news/menu', 'Api\NewsController@executeMenu');
Route::post ('/news/entry', 'Api\NewsController@executeEntry');

Route::post ('/push/regist', 'Api\PushController@executeRegist');
