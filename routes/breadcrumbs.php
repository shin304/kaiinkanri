<?php
// use Illuminate\Support\Facades\Route;
// //breadcrumb student
// Breadcrumbs::register ( 'student', function ($breadcrumbs) {
//     $breadcrumbs->push ( '会員管理', route('student') );
// } );

// Breadcrumbs::register ( 'student_entry', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'student' );
//     $breadcrumbs->push ( '会員編集', route('student_entry') );
// } );

// Breadcrumbs::register ( 'student_upload', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'student' );
//     $breadcrumbs->push ( '会員CSV登録', route('student_upload') );
// } );
// Breadcrumbs::register ( 'student_detail', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'student' );
//     $breadcrumbs->push ( '会員詳細', route('student_detail') );
// } );
// Breadcrumbs::register ( 'student_edit', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'student_detail' );
//     $breadcrumbs->push ( '会員詳細編集', route('student_edit') );
// } );
// // end breadcrumb student

// // breadcrump parent
// Breadcrumbs::register ( 'parent', function ($breadcrumbs) {
//     $breadcrumbs->push ( '請求先管理', route('parent') );
// } );

// Breadcrumbs::register ( 'parent_detail', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'parent' );
//     $breadcrumbs->push ( '請求先詳細', route('parent_detail') );
// } );

// Breadcrumbs::register ( 'parent_edit', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'parent_detail' );
//     $breadcrumbs->push ( '請求先編集', route('parent_edit') );
// } );
// Breadcrumbs::register ( 'parent_entry', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'parent' );
//     $breadcrumbs->push ( '請求先登録', route('parent_entry') );
// } );

// //School
// Breadcrumbs::register ( 'school', function ($breadcrumbs) {
//     $breadcrumbs->push ( '基本情報', route('school'));
// } );
// Breadcrumbs::register ( 'school_input', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'school' );
//     $breadcrumbs->push ( '詳細情報編集', route('school_input'));
// } );
//     Breadcrumbs::register ( 'school_input_indiv', function ($breadcrumbs) {
//         $breadcrumbs->parent ( 'school_input' );
//         $breadcrumbs->push ( '個別情報設定', route('school_input_indiv'));
// } );
// Breadcrumbs::register ( 'adjustnameinput', function ($breadcrumbs) {
//         $breadcrumbs->parent ( 'school_input_indiv' );
//             $breadcrumbs->push ( '割引・割増名称編集', route('adjustnameinput'));
//         } );
// //end school

// //Broadcastmail
// Breadcrumbs::register ( 'broadcastmail', function ($breadcrumbs) {
//     $breadcrumbs->push ( 'お知らせ送信', route( 'broadcastmail' ));
// } );
// Breadcrumbs::register ( 'broadcastmail_search', function ($breadcrumbs) {
//     $breadcrumbs->push ( 'お知らせ送信', route( 'broadcastmail_search' ));
// } );
// Breadcrumbs::register ( 'broadcastmail_entry', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'broadcastmail' );
//     $breadcrumbs->push ( 'お知らせ登録', route( 'broadcastmail_entry' ) );
// } );
// //end broadcastmail
// //class
// Breadcrumbs::register ( 'class', function ($breadcrumbs) {
//     $breadcrumbs->push ( 'プラン管理', route('class'));
// } );
// Breadcrumbs::register ( 'class_input', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'class' );
//     $breadcrumbs->push ( 'プラン管理登録', route('class_input'));
// } );
// Breadcrumbs::register ( 'class_detail', function ($breadcrumbs) {
//         $breadcrumbs->parent ( 'class' );
//         $breadcrumbs->push ( 'プラン管理詳細', route('class_detail'));
// } );
// Breadcrumbs::register ( 'class_edit', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'class_detail' );
//     $breadcrumbs->push ( 'プラン管理編集', route('class_input'));
// } );
// Breadcrumbs::register ( 'class_student_list', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'class_detail' );
//     $breadcrumbs->push ( '会員追加', route('class_student_list'));
// } );
// Breadcrumbs::register ( 'class_student_list_delete', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'class_detail' );
//     $breadcrumbs->push ( '会員削除', route('class_student_list'));
// } );
// //end Class
// //Invoice
//     Breadcrumbs::register ( 'invoice', function ($breadcrumbs) {
//         $breadcrumbs->push ( '請求書', route( 'invoice' ));
//     } );
//         Breadcrumbs::register ( 'invoice_search', function ($breadcrumbs) {
//             $breadcrumbs->parent ( 'invoice' );
//             $breadcrumbs->push ( '請求書一覧', route( 'invoice_search' ));
//         } );
//             Breadcrumbs::register ( 'invoice_detail', function ($breadcrumbs) {
//                 $breadcrumbs->parent ( 'invoice_search' );
//                 $breadcrumbs->push ( '請求書確認画面', route( 'invoice_detail' ));
//             } );
//                 Breadcrumbs::register ( 'invoice_entry', function ($breadcrumbs) {
//                     $breadcrumbs->parent ( 'invoice_detail' );
//                     $breadcrumbs->push ( '請求書編集画面', route( 'invoice_entry' ));
//                 } );
//                     Breadcrumbs::register ( 'invoice_transfer', function ($breadcrumbs) {
//                         $breadcrumbs->parent ( 'invoice_search' );
//                         $breadcrumbs->push ( '口座振替', route( 'invoice_transfer' ));
//                     } );


// Breadcrumbs::register ( 'school_accountlist', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'school' );
//     $breadcrumbs->push ( 'ログイン権限設定一覧', Route::get ( '/school/school/accountlist' ) );
// } );

// Breadcrumbs::register ( 'school_accountinput', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'school_accountlist' );
//     $breadcrumbs->push ( ' ログイン権限設定登録', Route::get ( '/school/school/accountedit' ) );
// } );

// Breadcrumbs::register ( 'school_accountedit', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'school_accountlist' );
//     $breadcrumbs->push ( 'ログイン権限設定編集', Route::get ( '/school/school/accountedit' ) );
// } );

// Breadcrumbs::register ( 'school_course', function ($breadcrumbs) {
//     $breadcrumbs->push ( 'イベント管理', Route::get ( 'course' ) );
// } );
// Breadcrumbs::register ( 'school_course_detail', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'school_course' );
//     $breadcrumbs->push ( 'イベント詳細', Route::get ( 'course_detail' ) );
// } );
// Breadcrumbs::register ( 'school_course_entry', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'school_course' );
//     $breadcrumbs->push ( 'イベント詳細', Route::get ( 'course_entry' ) );
// } );
// Breadcrumbs::register ( 'school_mail_select', function ($breadcrumbs) {
//     $breadcrumbs->parent ( 'school_course' );
//     $breadcrumbs->push ( 'メール送信先選択', Route::get ( 'mailMessage' ) );
// } );
?>