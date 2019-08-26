<?php
use App\Http\Controllers\School\StudentController;
use Illuminate\Support\Facades\File;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */


//

Route::get ( '/input_email', 'School\LoginController@inputEmailChangePassword' );
Route::post ( '/password_reminder', 'School\LoginController@passwordChange' );
Route::post ( '/passwordChange', 'School\LoginController@storeNewPassword' );
Route::get ( '/confirmCode', 'School\LoginController@confirmCode' );
Route::post ( '/confirmCodeSucsess', 'School\LoginController@storeConfirmCode' );
Route::post ( '/email', 'School\LoginController@checkEmail' );
Route::get ( '/home/blowfish', 'HomeController@blowfish' );


Route::get ( '/school', 'School\LoginController@index' )->name ( 'school' );

Route::get ( '/school/login', 'School\LoginController@index' );
Route::post ( '/ajaxGetPschool', 'School\LoginController@ajaxGetPschool' );

Route::post ( '/school/login', 'School\LoginController@login' )->name ( 'login' );

Route::get ( '/school/home', 'School\HomeController@index' );
Route::get ( '/school/home/searchNotice', 'School\HomeController@ajaxSearchNotice' );
Route::get ( '/school/home/searchMail', 'School\HomeController@ajaxSearchMail' );
Route::get ( '/school/home/updateNoticeViewDate', 'School\HomeController@ajaxUpdateNoticeViewDate' );
Route::get ( '/school/home/searchSystemLog', 'School\HomeController@ajaxSearchSystemLog' );
Route::get ( '/school/home/updateSystemLogViewdate', 'School\HomeController@ajaxUpdateSystemLogViewdate' );


// StudentController
Route::get ( '/school/student', ['as' => 'student','uses' => 'School\StudentController@execute']);
Route::post ( '/school/student', ['as' => 'student','uses' => 'School\StudentController@execute']);
Route::get ( '/school/student/entry', ['as' => 'student_entry','uses' => 'School\StudentController@executeEntry']);
Route::post ( '/school/student/entry', ['as' => 'student_entry','uses' => 'School\StudentController@executeEntry']);
Route::get('/school/student/ajax_list_parent', ['as' => 'ajax_list_parent', 'uses' => 'School\StudentController@ajaxListParent']);
Route::get('/school/student/ajax_get_parent', ['as' => 'ajax_get_parent', 'uses' => 'School\StudentController@ajaxGetParent']);
Route::post('/school/student/ajax_change_student_state', ['as' => 'ajax_change_student_state', 'uses' => 'School\StudentController@ajaxChangeStudentState']);
Route::post('/school/student/delete', ['as' => 'delete', 'uses' => 'School\StudentController@delete']);
// Route::post ( '/school/student/addparent', ['as' => 'student_add_parent', 'uses' => 'School\StudentController@executeAddParent']);
Route::post ( '/school/student/edit', ['as' => 'student_edit','uses' => 'School\StudentController@executeEntry']);
Route::get ( '/school/student/edit', 'School\StudentController@executeEntry' );
Route::get ( '/school/student/uploadinput', [ 
        'as' => 'student_upload',
        'uses' => 'School\StudentController@displayUpload' 
] );
Route::post ( '/school/student/uploadinput', [ 
        'as' => 'student_upload',
        'uses' => 'School\StudentController@displayUpload' 
] );

Route::post ( '/school/student/importCsv', 'School\StudentController@importCsv' );
Route::post ( '/school/student/export_csv', 'School\StudentController@exportCsv' );
Route::post ( '/school/student/download_template', 'School\StudentController@downloadTemplate' );
Route::post ( '/school/student/complete', 'School\StudentController@newComplete' );
Route::post ( '/school/student/detail', [ 
        'as' => 'student_detail',
        'uses' => 'School\StudentController@newDetail' 
] );
Route::get ( '/school/student/detail', [
        'as' => 'student_detail',
        'uses' => 'School\StudentController@newDetail'
] );
Route::get ( '/school/ajaxSchool/city', 'School\AjaxSchoolController@executeCity' );
Route::get ( '/school/ajaxSchool/consignor', 'School\AjaxSchoolController@executeConsignor' );
Route::get ( '/school/ajaxInvoice/getinitfee', 'School\AjaxInvoiceSchool@executeGetInitFee' );
Route::post ( '/school/student/getStudentType', 'School\StudentController@ajax_get_student_type' );
// end Student controller

// Parent Controller
Route::get ( '/school/parent/list2', 'School\ParentController@executeList2' );
Route::post ( '/school/parent/list2', 'School\ParentController@executeList2' );
Route::get ( '/school/parent/list', 'School\ParentController@executeList' );
Route::post ( '/school/parent/list', 'School\ParentController@executeList' );
Route::get ( '/school/parent', [ 
        'as' => 'parent',
        'uses' => 'School\ParentController@execute' 
] );
Route::post ( '/school/parent/complete', 'School\ParentController@executeComplete' );
// Route::post ( '/school/parent/complete', 'School\ParentController@executeComplete' );
Route::get ( '/school/parent/entry', [ 
        'as' => 'parent_entry',
        'uses' => 'School\ParentController@executeEntry' 
] );
Route::post ( '/school/parent/entry', [ 
        'as' => 'parent_entry',
        'uses' => 'School\ParentController@executeEntry' 
] );
Route::get ( '/school/parent/detail', [ 
        'as' => 'parent_detail',
        'uses' => 'School\ParentController@executeDetail' 
] );
Route::post ( '/school/parent/detail', [ 
        'as' => 'parent_detail',
        'uses' => 'School\ParentController@executeDetail' 
] );
Route::post ( '/school/parent/edit', [ 
        'as' => 'parent_edit',
        'uses' => 'School\ParentController@executeEntry' 
] );
Route::get ( '/school/parent/edit', [ 
        'as' => 'parent_edit',
        'uses' => 'School\ParentController@executeEntry' 
] );

Route::post ( '/school/parent/get_address_from_zipcode', 'School\ParentController@get_address_from_zipcode' );

// End parentController

// School Controller
Route::get ( '/school/school', [ 
        'as' => 'school',
        'uses' => 'School\SchoolController@execute' 
] );
Route::post ( '/school/school', [ 
        'as' => 'school',
        'uses' => 'School\SchoolController@execute' 
] );
Route::get ( '/school/school/schoollist', 'School\SchoolController@execute' );
Route::get ( '/school/school/adjustnameupdown', 'School\SchoolController@executeAdjustNameUpDown' );
Route::post ( '/school/school/adjustnameupdown', 'School\SchoolController@executeAdjustNameUpDown' );
Route::post ( '/school/school/adjustnameComplete', 'School\SchoolController@executeAdjustNameComplete' );
Route::get ( '/school/school/additionalcategory', [
    'as' => 'additional_category',
    'uses' => 'School\SchoolController@executeAdditionalCategory'
] );
Route::post ( '/school/school/additionalcategory', [
    'as' => 'additional_category',
    'uses' => 'School\SchoolController@executeAdditionalCategory'
] );
Route::post ( '/school/school/additionalCategoryComplete', 'School\SchoolController@executeAdditionalCategoryComplete' );
Route::get ( '/school/school/additionalCategoryComplete', 'School\SchoolController@executeAdditionalCategoryComplete' );
Route::get ( '/school/ajaxSchool/swapCategories', 'School\AjaxSchoolController@executeSwapCategories' );
Route::post ( '/school/school/ajax_remove_bank', 'School\SchoolController@ajax_remove_bank_account' );
Route::post ( '/school/school/ajax_save_bank_account', 'School\SchoolController@ajax_save_bank_account' );
Route::post ( '/school/school/ajax_change_default_bank_account', 'School\SchoolController@ajax_change_default_bank_account' );
Route::post ( '/school/school/ajax_get_all_bank_account', 'School\SchoolController@ajax_get_all_bank_account' );
Route::post ( '/school/school/get_detail_payment_method', 'School\SchoolController@ajax_get_payment_method_detail' );
Route::post ( '/school/school/get_pschool_payment_method', 'School\SchoolController@ajax_get_pschool_method_detail' );
Route::post ( '/school/school/save_detail_payment', 'School\SchoolController@ajax_save_detail_payment' );
Route::post ( '/school/school/set_payment_method', 'School\SchoolController@ajax_set_payment_method' );
Route::post ( '/school/school/set_default_bank_method', 'School\SchoolController@ajax_set_default_bank_method' );
Route::get ( '/school/school/input', [
        'as' => 'school_input',
        'uses' => 'School\SchoolController@executeInput' 
] );
Route::post ( '/school/school/input', [ 
        'as' => 'school_input',
        'uses' => 'School\SchoolController@executeInput' 
] );
Route::get ( '/school/school/inputindiv', [ 
        'as' => 'school_input_indiv',
        'uses' => 'School\SchoolController@executeInputIndiv' 
] );
Route::post ( '/school/school/inputindiv', [ 
        'as' => 'school_input_indiv',
        'uses' => 'School\SchoolController@executeInputIndiv' 
] );
Route::get ( '/school/school/adjustnameinput', [ 
        'as' => 'adjustnameinput',
        'uses' => 'School\SchoolController@executeAdjustNameInput' 
] );
Route::post ( '/school/school/adjustnameinput', [ 
        'as' => 'adjustnameinput',
        'uses' => 'School\SchoolController@executeAdjustNameInput' 
] );
Route::post ( '/school/school/completeIndiv', 'School\SchoolController@executeCompleteIndiv' );
Route::post ( '/school/school/complete', 'School\SchoolController@executeComplete' );
Route::get ( '/school/school/accountlist', 'School\SchoolController@executeAccountlist' );
Route::post ( '/school/school/accountlist', 'School\SchoolController@executeAccountlist' );
Route::get ( '/school/school/accountedit', 'School\SchoolController@executeAccountedit' );
Route::post ( '/school/school/accountedit', 'School\SchoolController@executeAccountedit' );
Route::post ( '/school/school/accountcomplete', 'School\SchoolController@executeAccountcomplete' );
Route::get ( '/school/school/accountdelete', 'School\SchoolController@executeAccountdelete' );

Route::get ( '/school/school/studentSetting', 'School\SchoolController@executeStudentSetting' );
Route::post ( '/school/school/saveStudentMenu', 'School\SchoolController@saveStudentMenu' );
Route::get ( '/school/school/parentSetting', 'School\SchoolController@executeParentSetting' );
Route::post ( '/school/school/saveParentMenu', 'School\SchoolController@saveParentMenu' );
Route::post ( '/school/school/loadStudentMenu', 'School\SchoolController@ajax_load_student_menu' );
// End School Controller

// Broadcastmail Controller
Route::get ( '/school/broadcastmail/searchBroadcastmail', 'School\BroadcastmailController@executeSearchBroadcastmail' );
Route::get ( '/school/broadcastmail', [ 
        'as' => 'broadcastmail',
        'uses' => 'School\BroadcastmailController@execute' 
] );
Route::post ( '/school/broadcastmail', [
        'as' => 'broadcastmail',
        'uses' => 'School\BroadcastmailController@execute'
] );
Route::post ( '/school/broadcastmail/search', [ 
        'as' => 'broadcastmail_search',
        'uses' => 'School\BroadcastmailController@executeSearch' 
] );
Route::get ( '/school/broadcastmail/search', [ 
        'as' => 'broadcastmail_search',
        'uses' => 'School\BroadcastmailController@executeSearch' 
] );
Route::post ( '/school/broadcastmail/edit', 'School\BroadcastmailController@newEntry' );
Route::get ( '/school/broadcastmail/edit', 'School\BroadcastmailController@newEntry' );
Route::get ( '/school/broadcastmail/entry', [ 
        'as' => 'broadcastmail_entry',
        'uses' => 'School\BroadcastmailController@newEntry' 
] );
Route::post ( '/school/broadcastmail/entry', 'School\BroadcastmailController@newEntry' );
Route::post ( '/school/broadcastmail/completeSend', 'School\BroadcastmailController@executeCompleteSend' );
Route::get ( '/school/broadcastmail/completeSend', 'School\BroadcastmailController@executeCompleteSend' );
Route::post ( '/school/broadcastmail/save', 'School\BroadcastmailController@executeSave' );
Route::get ( '/school/broadcastmail/deleteUploadFile', ['as' => 'broadcast_delete_file', 'uses' => 'School\BroadcastmailController@ajaxDeleteUploadFile' ]);

// End Broadcastmail Controller

// Invoice controller
Route::get ( '/school/invoice', [
        'as' => 'invoice',
        'uses' => 'School\Invoice\InvoiceController@execute'
] );
Route::post ( '/school/invoice', [
        'as' => 'invoice',
        'uses' => 'School\Invoice\InvoiceController@execute'
] );
Route::get ( '/school/invoice/list', 'School\Invoice\InvoiceController@executeList' );
Route::post ( '/school/invoice/list', 'School\Invoice\InvoiceController@executeList' );

Route::get ( '/school/invoice/confirmation', 'School\Invoice\InvoiceController@executeConfirmation' );
Route::post ( '/school/invoice/confirmation', 'School\Invoice\InvoiceController@executeConfirmation' );

Route::get ( '/school/invoice/mailsend', 'School\Invoice\InvoiceController@executeMailSend' );
Route::post ( '/school/invoice/mailsend', 'School\Invoice\InvoiceController@executeMailSend' );

Route::get ( '/school/invoice/generate', 'School\Invoice\InvoiceController@executeGenerateInvoice' );
Route::post ( '/school/invoice/generate', 'School\Invoice\InvoiceController@executeGenerateInvoice' );

Route::get ( '/school/invoice/confirm', 'School\Invoice\InvoiceController@InvoiceConfirmStatus2' );
Route::post ( '/school/invoice/confirm', 'School\Invoice\InvoiceController@InvoiceConfirmStatus2' );

Route::get ( '/school/invoice/parentselect', 'School\Invoice\InvoiceController@getListParentSelect' );
Route::post ( '/school/invoice/parentselect', 'School\Invoice\InvoiceController@getListParentSelect' );

Route::get ( '/school/invoice/entry', 'School\Invoice\InvoiceController@entryInvoice' );
Route::post ( '/school/invoice/entry', 'School\Invoice\InvoiceController@entryInvoice' );

Route::get ( '/school/invoice/detail', 'School\Invoice\InvoiceController@detailInvoice' );
Route::post ( '/school/invoice/detail', 'School\Invoice\InvoiceController@detailInvoice' );

Route::get ( '/school/invoice/edit', 'School\Invoice\InvoiceController@editInvoice' );
Route::post ( '/school/invoice/edit', 'School\Invoice\InvoiceController@editInvoice' );

Route::get ( '/school/invoice/singleEditComplete', 'School\Invoice\InvoiceController@SingleInvoiceConfirm' );
Route::post ( '/school/invoice/singleEditComplete', 'School\Invoice\InvoiceController@SingleInvoiceConfirm' );

Route::get ( '/school/invoice/completeEditInvoice', 'School\Invoice\InvoiceController@completeEditInvoice' );
Route::post ( '/school/invoice/completeEditInvoice', 'School\Invoice\InvoiceController@completeEditInvoice' );

Route::get ( '/school/invoice/deleteInvoice', 'School\Invoice\InvoiceController@deleteInvoice' );
Route::post ( '/school/invoice/deleteInvoice', 'School\Invoice\InvoiceController@deleteInvoice' );

Route::get ( '/school/invoice/pdfExport', 'School\Invoice\InvoiceController@pdfExport' );
Route::post ( '/school/invoice/pdfExport', 'School\Invoice\InvoiceController@pdfExport' );

//入金処理 -- Deposit
Route::get('/school/invoice/deposit', 'School\Invoice\InvoiceController@deposit');
Route::post('/school/invoice/deposit', 'School\Invoice\InvoiceController@deposit');
Route::post('/school/invoice/deposit_process', 'School\Invoice\InvoiceController@depositProcess');
Route::get('/school/invoice/deposit_process',  ['as' => 'invoice_deposit_process',
                                                'uses'=>'School\Invoice\InvoiceController@depositProcess']);
Route::post('/school/invoice/deposit_end_process', 'School\Invoice\InvoiceController@depositEndProcess');
Route::post('/school/invoice/deposit_export', 'School\Invoice\InvoiceController@depositExport');
Route::get('/school/invoice/deposit_receipt', 'School\Invoice\InvoiceController@depositReceipt');
Route::post('/school/invoice/deposit_receipt', 'School\Invoice\InvoiceController@depositReceipt');
Route::post('/school/invoice/deposit_reminder', 'School\Invoice\InvoiceController@depositReminder');
Route::post('/school/invoice/deposit_send', 'School\Invoice\InvoiceController@depositSendReminder');

Route::get ( '/school/invoice/mailSearch', 'School\Invoice\InvoiceController@mailSearch' );
Route::post ( '/school/invoice/mailSearch', 'School\Invoice\InvoiceController@mailSearch' );

// Process file
    //kozafurikae
    Route::get ( '/school/invoice/ricohTransProc', 'School\Invoice\InvoiceController@ricohTransProcess' );
    Route::post ( '/school/invoice/ricohTransProc', 'School\Invoice\InvoiceController@ricohTransProcess' );

    Route::get ( '/school/invoice/ricohTransDownload', 'School\Invoice\InvoiceController@ricohTransDownload' );
    Route::post ( '/school/invoice/ricohTransDownload', 'School\Invoice\InvoiceController@ricohTransDownload' );

    Route::get ( '/school/invoice/ricohTransDownloadComplete', 'School\Invoice\InvoiceController@getZengin' );
    Route::post ( '/school/invoice/ricohTransDownloadComplete', 'School\Invoice\InvoiceController@getZengin' );

    Route::get ( '/school/invoice/ricohTransUpload', 'School\Invoice\InvoiceController@ricohTransUpload' );
    Route::post ( '/school/invoice/ricohTransUpload', 'School\Invoice\InvoiceController@ricohTransUpload' );

    Route::get ( '/school/invoice/ricohTransUploadComplete', 'School\Invoice\InvoiceController@ricohTransUploadComplete' );
    Route::post ( '/school/invoice/ricohTransUploadComplete', 'School\Invoice\InvoiceController@ricohTransUploadComplete' );

    Route::get ( '/school/invoice/canceltransfer', 'School\Invoice\InvoiceController@cancelRicohTrans' );
    Route::post ( '/school/invoice/canceltransfer', 'School\Invoice\InvoiceController@cancelRicohTrans' );

    //conbini

    Route::get ( '/school/invoice/ricohConvProc', 'School\Invoice\InvoiceController@ricohConvProcess' );
    Route::post ( '/school/invoice/ricohConvProc', 'School\Invoice\InvoiceController@ricohConvProcess' );

    Route::get ( '/school/invoice/ricohConvDownload', 'School\Invoice\InvoiceController@ricohConvDownload' );
    Route::post ( '/school/invoice/ricohConvDownload', 'School\Invoice\InvoiceController@ricohConvDownload' );

    Route::get ( '/school/invoice/ricohConvDownloadComplete', 'School\Invoice\InvoiceController@getKonbini' );
    Route::post ( '/school/invoice/ricohConvDownloadComplete', 'School\Invoice\InvoiceController@getKonbini' );

    Route::get ( '/school/invoice/cancelconv', 'School\Invoice\InvoiceController@cancelRicohConv' );
    Route::post ( '/school/invoice/cancelconv', 'School\Invoice\InvoiceController@cancelRicohConv' );

    Route::get ( '/school/invoice/ricohConvUpload', 'School\Invoice\InvoiceController@ricohConvUpload' );
    Route::post ( '/school/invoice/ricohConvUpload', 'School\Invoice\InvoiceController@ricohConvUpload' );

    Route::get ( '/school/invoice/ricohConvUploadComplete', 'School\Invoice\InvoiceController@ricohConvUploadComplete' );
    Route::post ( '/school/invoice/ricohConvUploadComplete', 'School\Invoice\InvoiceController@ricohConvUploadComplete' );

    //yuucho

    Route::get ( '/school/invoice/ricohPostProc', 'School\Invoice\InvoiceController@ricohPostProcess' );
    Route::post ( '/school/invoice/ricohPostProc', 'School\Invoice\InvoiceController@ricohPostProcess' );

    Route::get ( '/school/invoice/ricohPostDownload', 'School\Invoice\InvoiceController@ricohPostDownload' );
    Route::post ( '/school/invoice/ricohPostDownload', 'School\Invoice\InvoiceController@ricohPostDownload' );

    Route::get ( '/school/invoice/ricohPostDownloadComplete', 'School\Invoice\InvoiceController@getYuucho' );
    Route::post ( '/school/invoice/ricohPostDownloadComplete', 'School\Invoice\InvoiceController@getYuucho' );

    Route::get ( '/school/invoice/cancelpost', 'School\Invoice\InvoiceController@cancelRicohPost' );
    Route::post ( '/school/invoice/cancelpost', 'School\Invoice\InvoiceController@cancelRicohPost' );

//
//Route::get ( '/school/ajaxInvoice/mailsend', 'School\AjaxInvoiceSchool@executeMailSend' );
////test submit mailsend
//Route::post ( '/school/ajaxinvoice/mailsend', 'School\AjaxInvoiceSchool@executeMailSend' );
Route::get ( '/school/ajaxInvoice/getparentadjust', 'School\AjaxInvoiceSchool@executeGetParentAdjust' );
Route::get ( '/school/ajaxInvoice/checkparentadjust', 'School\AjaxInvoiceSchool@executeCheckParentAdjust' );
Route::get ( '/school/ajaxInvoice/registparentadjust', 'School\AjaxInvoiceSchool@executeRegistParentAdjust' );

Route::get ( '/school/ajaxInvoice/getinvoiceyearmonth', 'School\AjaxInvoiceSchool@executeGetInvoiceYearMonth' );
Route::get ( '/school/ajaxInvoice/setspotinvoice', 'School\AjaxInvoiceSchool@executeSetSpotInvoice' );
Route::get ( '/school/ajaxInvoice/getclassevent', 'School\AjaxInvoiceSchool@executeGetClassEvent' );
Route::get ( '/school/ajaxMailMessage/school', 'School\ajaxMailMessage@executeSchool' );

// End invoice controller

// --DefaultMenuController--
Route::get ( '/school/defaultmenu', 'School\DefaultMenuController@index' );
Route::get ( '/school/defaultmenu/input', 'School\DefaultMenuController@input' );
Route::post ( '/school/defaultmenu/input', 'School\DefaultMenuController@doInput' )->name ( 'doInput' );
// <--End DefaultMenuController-->

// --SetMenuController--
Route::get ( '/school/menu', 'School\MenuController@index' );
Route::get ( '/school/menu/input', 'School\MenuController@input' );
Route::post ( '/school/menu/input', 'School\MenuController@doInput' )->name ( 'doInput' );
// <--End SetMenuController-->
Route::get ( '/school/importExport', 'School\CsvController@importExport' );

Route::get ( '/system', 'System\BusinessTypeController@index' )->name ( 'index' );

// プラン管理開始

Route::get ( '/school/class', ['as' => 'class', 'uses' => 'School\ClassController@index']);
Route::post ( '/school/class', ['as' => 'class', 'uses' => 'School\ClassController@index']);

Route::get ( '/school/class/input', ['as' => 'class_input', 'uses' => 'School\ClassController@input' ]);
Route::post ( '/school/class/input', ['as' => 'class_input', 'uses' => 'School\ClassController@input' ]);

Route::post('/school/class/complete', ['as' => 'class_complete', 'uses' => 'School\ClassController@complete']);

Route::get('/school/class/detail', ['as' => 'class_detail', 'uses' => 'School\ClassController@detail']);
Route::post('/school/class/detail', ['as' => 'class_detail', 'uses' => 'School\ClassController@detail']);

Route::get('/school/class/studentList', ['as' => 'class_student_list', 'uses' => 'School\ClassController@studentList']);
Route::post('/school/class/studentList', ['as' => 'class_student_list', 'uses' => 'School\ClassController@studentList']);

Route::post('/school/class/studentProc', ['as' => 'class_student_proc', 'uses' => 'School\ClassController@studentProc']);

Route::get('/school/class/studentEdit', ['as' => 'class_student_edit', 'uses' => 'School\ClassController@studentEdit']);
Route::post('/school/class/studentEdit', ['as' => 'class_student_edit', 'uses' => 'School\ClassController@studentEdit']);

Route::post('/school/class/studentStore', ['as' => 'class_student_store', 'uses' => 'School\ClassController@studentStore']);

Route::post('/school/class/studentSearch', ['as' => 'class_student_search', 'uses' => 'School\ClassController@studentSearch']);

// プラン管理終了

//講師管理開始
// 講師管理一覧
Route::get ( '/school/coach', ['as' => 'coach', 'uses' => 'School\CoachController@index']);
Route::post ( '/school/coach', ['as' => 'coach', 'uses' => 'School\CoachController@index']);

//　講師管理の検索
Route::get( '/school/coach/list', ['as' => 'coach_list', 'uses' => 'School\CoachController@index']);
Route::post( '/school/coach/list', ['as' => 'coach_list', 'uses' => 'School\CoachController@index']);

//　講師管理の登録
Route::get( '/school/coach/entry', ['as' => 'coach_entry', 'uses' => 'School\CoachController@entry']);
Route::post( '/school/coach/entry', ['as' => 'coach_entry', 'uses' => 'School\CoachController@entry']);

// 詳細画面
Route::get( '/school/coach/detail', ['as' => 'coach_detail', 'uses' => 'School\CoachController@detail']);
Route::post( '/school/coach/detail', ['as' => 'coach_detail_post', 'uses' => 'School\CoachController@detail']);

// AJAX call to get city
Route::get ( '/school/coach/getCityDataByPrefId', 'School\CoachController@getCityDataByPrefId' );

// Save data to database
Route::post( '/school/coach/store', ['as' => 'coach_save', 'uses' => 'School\CoachController@store']);

Route::get('/image/{image}', 'ImageController@display');


//講師管理終了

// イベント管理開始

Route::get( '/school/course', ['as' => 'course', 'uses' => 'School\CourseController@index' ]);
Route::post( '/school/course', ['as' => 'course', 'uses' => 'School\CourseController@index' ]);
Route::get( '/school/course/list', ['as' => 'course_list', 'uses' => 'School\CourseController@executeList']);
Route::post( '/school/course/list', ['as' => 'course_list', 'uses' => 'School\CourseController@executeList']);
Route::post( '/school/course/courseentry', ['as' => 'course_entry', 'uses' => 'School\CourseController@courseEntry' ]);
Route::get( '/school/course/courseentry', ['as' => 'course_entry', 'uses' => 'School\CourseController@courseEntry' ]);
Route::post( '/school/course/coursecomplete', ['as' => 'course_complete', 'uses' => 'School\CourseController@courseComplete' ]);
Route::get('/school/course/courseDetail', ['as' => 'course_detail', 'uses' => 'School\CourseController@courseDetail']);
Route::post('/school/course/courseDetail', ['as' => 'course_detail', 'uses' => 'School\CourseController@courseDetail']);
Route::post('/school/course/search', ['as' => 'course_search', 'uses' => 'School\CourseController@search']);
Route::get('/school/course/exportcsv', ['as' => 'course_export', 'uses' => 'School\CourseController@exportCSV']);


// イベント管理終了

// メール送信先選択開始
Route::get ( '/school/mailMessage/select', ['as' => 'mailMessage','uses' => 'School\MailMessageController@select' ] );
Route::post ( '/school/mailMessage/select', ['as' => 'mailMessage','uses' => 'School\MailMessageController@select' ] );
Route::get ( '/school/mailMessage/getEventParentInfo', ['as' => 'getEventParent','uses' => 'School\MailMessageController@ajaxGetEventParentInfo' ] );
Route::post ( '/school/mailMessage/getEventParentInfo', ['as' => 'getEventParent','uses' => 'School\MailMessageController@ajaxGetEventParentInfo' ] );
Route::post ( '/school/mailMessage/completemail', ['as' => 'mailMessage_complete','uses' => 'School\MailMessageController@completeMail' ] );
Route::get ( '/school/mailMessage/completemail', ['as' => 'mailMessage_complete','uses' => 'School\MailMessageController@completeMail' ] );
Route::get ( '/school/mailMessage/entry', ['as' => 'mailMessage_entry','uses' => 'School\MailMessageController@executeEntry' ] );
Route::post ( '/school/mailMessage/entry', ['as' => 'mailMessage_entry','uses' => 'School\MailMessageController@executeEntry' ] );
Route::post ( '/school/mailMessage/entry2', ['as' => 'mailMessage_entry2','uses' => 'School\MailMessageController@executeEntry2' ] );
Route::post ( '/school/mailMessage/updateIsReceived', ['as' => 'mailMessage_complete','uses' => 'School\MailMessageController@updateIsReceived' ] );
Route::get ( '/school/mailMessage/entryMulti', ['as' => 'mailMessage_enterMulti','uses' => 'School\MailMessageController@entryMulti' ] );
Route::post ( '/school/mailMessage/entryMulti', ['as' => 'mailMessage_enterMulti','uses' => 'School\MailMessageController@entryMulti' ] );

// メール送信先選択終了

Route::get ( '/school/logout', 'School\LogoutController@execute' );

//  プログラム管理
Route::get( '/school/program', ['as' => 'program', 'uses' => 'School\ProgramController@index' ]);
Route::post( '/school/program', ['as' => 'program', 'uses' => 'School\ProgramController@index' ]);
Route::get( '/school/program/input', ['as' => 'program_input', 'uses' => 'School\ProgramController@input' ]);
Route::post( '/school/program/input', ['as' => 'program_input', 'uses' => 'School\ProgramController@input' ]);
Route::post( '/school/program/complete', ['as' => 'program_complete', 'uses' => 'School\ProgramController@complete']);
Route::post( '/school/program/reference', ['as' => 'program_reference', 'uses' => 'School\ProgramController@reference']);
Route::post( '/school/program/clear', ['as' => 'program_clear', 'uses' => 'School\ProgramController@clear' ]);
// Route::post( '/school/program/programcomplete', ['as' => 'program_complete', 'uses' => 'School\ProgramController@programComplete' ]);
Route::get('/school/program/detail', ['as' => 'program_detail', 'uses' => 'School\ProgramController@detail']);
Route::post('/school/program/detail', ['as' => 'program_detail', 'uses' => 'School\ProgramController@detail']);
Route::post('/school/program/search', ['as' => 'program_search', 'uses' => 'School\ProgramController@search']);
Route::get( '/school/program/studentList', ['as' => 'program_studentList', 'uses' => 'School\ProgramController@studentList' ]);
Route::post( '/school/program/studentList', ['as' => 'program_studentList', 'uses' => 'School\ProgramController@studentList' ]);
Route::post( '/school/program/studentProc', ['as' => 'program_studentProc', 'uses' => 'School\ProgramController@studentProc' ]);
Route::get('/school/program/exportcsv', ['as' => 'program_export', 'uses' => 'School\ProgramController@exportCSV']);
//  プログラム管理終了

// PORTAL Controller Event
Route::get('/portal/event/',['as'=>'portal_event','uses'=>'Portal\EventController@index']);
Route::post('/portal/event/',['as'=>'portal_event','uses'=>'Portal\EventController@index']);
Route::post('/portal/event/confirm',['as'=>'portal_event_confirm','uses'=>'Portal\EventController@confirm']);
Route::post('/portal/event/complete',['as'=>'portal_event_complete','uses'=>'Portal\EventController@complete']);
Route::get('/portal/event/result',['as'=>'portal_event_result','uses'=>'Portal\EventController@result']);
Route::post('/portal/event/result',['as'=>'portal_event_result','uses'=>'Portal\EventController@result']);
//Route::get('/portal/event/pay',['as'=>'portal_event_pay','uses'=>'Portal\EventController@pay']);
Route::post('/portal/event/pay',['as'=>'portal_event_pay','uses'=>'Portal\EventController@pay']);
// End PORTAL Controller Event

// PORTAL Controller Program
Route::get('/portal/program/',['as'=>'portal_program','uses'=>'Portal\ProgramController@index']);
Route::post('/portal/program/',['as'=>'portal_program','uses'=>'Portal\ProgramController@index']);
Route::post('/portal/program/confirm',['as'=>'portal_program_confirm','uses'=>'Portal\ProgramController@confirm']);
Route::post('/portal/program/complete',['as'=>'portal_program_complete','uses'=>'Portal\ProgramController@complete']);
Route::get('/portal/program/result',['as'=>'portal_program_result','uses'=>'Portal\ProgramController@result']);
Route::post('/portal/program/result',['as'=>'portal_program_result','uses'=>'Portal\ProgramController@result']);
//Route::get('/portal/program/pay',['as'=>'portal_program_pay','uses'=>'Portal\ProgramController@pay']);
Route::post('/portal/program/pay',['as'=>'portal_program_pay','uses'=>'Portal\ProgramController@pay']);
// End PORTAL Controller Program

// PORTAL Controller Invoice
Route::get('/portal/invoice',['as'=>'portal_invoice','uses'=>'Portal\InvoiceController@index']);
// End PORTAL Controller Invoice

// PORTAL Credit Payment
Route::get('/portal/payment/result',['as'=>'portal_payment_result','uses'=>'Portal\PaymentController@result']);
// End PORTAL Credit Payment

// School/BulletinBoardController
Route::get ( '/school/bulletinboard', ['as' => 'bulletinboard_list', 'uses' => 'School\BulletinBoardController@index']);
Route::post ( '/school/bulletinboard', ['as' => 'bulletinboard_list', 'uses' => 'School\BulletinBoardController@index']);

Route::get ( '/school/bulletinboard/input', ['as' => 'bulletinboard_input', 'uses' => 'School\BulletinBoardController@input' ]);
Route::post ( '/school/bulletinboard/input', ['as' => 'bulletinboard_input', 'uses' => 'School\BulletinBoardController@input' ]);

Route::post('/school/bulletinboard/complete', ['as' => 'bulletinboard_complete', 'files' => true, 'uses' => 'School\BulletinBoardController@complete']);

Route::post('/school/bulletinboard/delete', ['as' => 'bulletinboard_delete', 'uses' => 'School\BulletinBoardController@delete']);

Route::get('/school/bulletinboard/detail', ['as' => 'bulletinboard_detail', 'uses' => 'School\BulletinBoardController@detail']);
Route::post('/school/bulletinboard/detail', ['as' => 'bulletinboard_detail', 'uses' => 'School\BulletinBoardController@detail']);

Route::get('/school/bulletinboard/search', ['as' => 'bulletinboard_search', 'uses' => 'School\BulletinBoardController@search']);
Route::post('/school/bulletinboard/search', ['as' => 'bulletinboard_search', 'uses' => 'School\BulletinBoardController@search']);

Route::get ( '/school/bulletinboard/deleteUploadFile', ['as' => 'bulletinboard_delete_file', 'uses' => 'School\BulletinBoardController@ajaxDeleteUploadFile' ]);

//ラベル印刷用のCSV出力
Route::get ( '/school/label/index', 'School\LabelController@index' );
Route::post ( '/school/label/index', 'School\LabelController@index' );
Route::get('/school/label/exportcsv', 'School\LabelController@exportCsv');
Route::get('/school/label/search', 'School\LabelController@search');
Route::get('/school/label/loadTemplate', 'School\LabelController@loadTemplate');
Route::get('/school/label/store', 'School\LabelController@store');
Route::get('/school/label/destroy', 'School\LabelController@destroy');

// End School/BulletinBoardController

Route::get ( '/system', 'System\LoginController@index' );

Route::get ( '/system/addNew', 'System\BusinessTypeController@add' )->name ( 'add' );

Route::post ( '/system/doAdd', 'System\BusinessTypeController@doAdd' )->name ( 'doAdd' );

Route::post ( '/system/doAdd', 'System\BusinessTypeController@doAdd' );

Route::get ( '/system/edit', 'System\BusinessTypeController@edit' )->name ( 'edit' );

Route::post ( '/system/edit', 'System\BusinessTypeController@doEdit' )->name ( 'doEdit' );

Route::post ( '/system/delete', 'System\BusinessTypeController@delete' )->name ( 'delete' );

Route::post ( '/system/login', 'System\LoginController@login' );

Route::get ( '/system/home', 'System\HomeController@index' );

Auth::routes ();

// Home routes by Tien君
Route::get('', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/home/feature', 'HomeController@feature');
Route::get('/home/index', 'HomeController@index');
Route::get('/home/qa', 'HomeController@qa');
Route::get('/home/company', 'HomeController@company');
Route::get('/home/contact', 'HomeController@contact');
Route::get('/home/register', 'HomeController@registerTrial');
Route::post('/home/register', 'HomeController@registerTrial');
Route::post('/home/contact_confirm', 'HomeController@contactConfirm');
Route::post('/home/contact_send', 'HomeController@contactSend');
Route::get('/home/contact_complete', 'HomeController@contactComplete');
Route::post ( '/home/get_address_from_zipcode', 'HomeController@get_address_from_zipcode' );
Route::get ( '/home/get_city', 'HomeController@get_city' );
Route::post ( '/home/confirmRegister', 'HomeController@confirmRegister' );
Route::get ( '/home/previewRegister', 'HomeController@previewRegister' );
Route::post ( '/home/storeRegister', 'HomeController@storeRegister' );
Route::get ( '/home/storeRegister', 'HomeController@storeRegister' );
Route::get ( '/home/mailConfirmed', 'HomeController@mailConfirmed' );
Route::post ( '/home/ajaxCheckEmail', 'HomeController@ajaxCheckEmail' );

// end home routes

Route::post ( '/admin/pschool/storeupdatedmessage', 'Admin\PschoolController@storeUpdatedMessage' );
Route::get ( '/admin/pschool/storeupdatedmessage', 'Admin\PschoolController@storeUpdatedMessage' );
Route::get ( '/admin/pschool/exportcsv', 'Admin\PschoolController@exportCSV' );
Route::get ( '/admin/pschool/loadscreen', 'Admin\PschoolController@loadScreenList' );
Route::get ( '/admin/messagefile/loadscreen', 'Admin\MessageFileController@loadScreenList' );
Route::get ( '/admin/messagefile/exportcsv', 'Admin\MessageFileController@exportCSV' );

//mail template
Route::get ( '/school/mailtemplate', 'School\MailTemplateController@listMailTemplate' );
Route::get ( '/school/mailtemplate/search', 'School\MailTemplateController@listMailTemplateByMailType' );
Route::delete ( '/school/mailtemplate/delete', 'School\MailTemplateController@deleteMailTemplate' );
Route::get ( '/school/mailtemplate/getInfo', 'School\MailTemplateController@listMailTemplateByID' );
Route::post ( '/school/mailtemplate/create', 'School\MailTemplateController@insertMailTemplate' );
Route::get ('/school/mailtemplate/validateName', 'School\MailTemplateController@validateName' );

//appmanage
Route::get  ('/appmanage', 'Appmanage\LoginController@execute');
Route::post ('/appmanage', 'Appmanage\LoginController@execute');
Route::get  ('/appmanage/login', 'Appmanage\LoginController@execute');
Route::post ('/appmanage/login', 'Appmanage\LoginController@executeLogin');
Route::get  ('/appmanage/logout', 'Appmanage\LoginController@executeLogout');
Route::post ('/appmanage/logout', 'Appmanage\LoginController@executeLogout');
Route::get  ('/appmanage/home', 'Appmanage\HomeController@execute');
Route::post ('/appmanage/home', 'Appmanage\HomeController@execute');
Route::get  ('/appmanage/member', 'Appmanage\MemberController@execute');
Route::post ('/appmanage/member', 'Appmanage\MemberController@execute');
Route::post ('/appmanage/member/edit', 'Appmanage\MemberController@executeEdit');
Route::post ('/appmanage/member/save', 'Appmanage\MemberController@executeSave');
Route::post ('/appmanage/member/delete', 'Appmanage\MemberController@executeDelete');
Route::post ('/appmanage/member/export', 'Appmanage\MemberController@executeExport');
Route::get  ('/appmanage/news', 'Appmanage\NewsController@execute');
Route::post ('/appmanage/news', 'Appmanage\NewsController@execute');
Route::post ('/appmanage/news/edit', 'Appmanage\NewsController@executeEdit');
Route::post ('/appmanage/news/save', 'Appmanage\NewsController@executeSave');
Route::post ('/appmanage/news/delete', 'Appmanage\NewsController@executeDelete');
Route::get  ('/appmanage/notice', 'Appmanage\NoticeController@execute');
Route::post ('/appmanage/notice', 'Appmanage\NoticeController@execute');
Route::post ('/appmanage/notice/edit', 'Appmanage\NoticeController@executeEdit');
Route::post ('/appmanage/notice/save', 'Appmanage\NoticeController@executeSave');
Route::post ('/appmanage/notice/delete', 'Appmanage\NoticeController@executeDelete');
Route::get  ('/appmanage/workbook', 'Appmanage\WorkbookController@execute');
Route::post ('/appmanage/workbook', 'Appmanage\WorkbookController@execute');
Route::post ('/appmanage/workbook/edit', 'Appmanage\WorkbookController@executeEdit');
Route::post ('/appmanage/workbook/save', 'Appmanage\WorkbookController@executeSave');
Route::post ('/appmanage/workbook/pack', 'Appmanage\WorkbookController@executePack');
Route::post ('/appmanage/workbook/stop', 'Appmanage\WorkbookController@executeStop');
Route::post ('/appmanage/workbook/delete', 'Appmanage\WorkbookController@executeDelete');
Route::get  ('/appmanage/info', 'Appmanage\InfoController@execute');
Route::post ('/appmanage/info', 'Appmanage\InfoController@execute');
Route::post ('/appmanage/info/edit', 'Appmanage\InfoController@executeEdit');
Route::post ('/appmanage/info/save', 'Appmanage\InfoController@executeSave');
Route::post ('/appmanage/info/delete', 'Appmanage\InfoController@executeDelete');

Route::group ( [ 
        'prefix' => 'admin' 
], function () {
    Voyager::routes ();
    Route::post('/closing-day/execute', ['as' => 'voyager.closing-day.execute', 'uses' => 'Admin\ClosingDayController@execute']);
    Route::post('/holiday/execute', ['as' => 'voyager.holiday.execute', 'uses' => 'Admin\HolidayController@execute']);
    Route::post('/oshirasekanri/search', [ 'uses' => 'Admin\NotifyController@index']);

} );


Auth::routes ();


