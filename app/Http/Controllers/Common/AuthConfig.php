<?php

namespace App\Http\Controllers\Common;

class AuthConfig {
    public static $auth_keys = array (
            'home' => 0x001000000000000, // HOME画面
            'orgchart' => 0x004000000000000, // 組織図
            'lowermail' => 0x000000000010000, // お知らせ送信
            'school' => 0x002000000000000, // 基本情報
            'class' => 0x000000000010000, // プラン情報管理
            'course' => 0x000000000020000, // イベント管理
            'program' => 0x000000000040000, // プログラム管理
            'broadcast' => 0x000000000080000, // メール一斉送信
            'invoice' => 0x000000000100000, // 請求管理
            'statistic' => 0x000000000000001, // 統計管理
            'student' => 0x000000100000000, // 生徒管理
            'parent' => 0x000000200000000, // 請求先管理
            'coach' => 0x000000400000000 
    ) // 講師管理
;
    public static $view_auth_datas = array (
            'system' => array (
                    'home' => array (
                            'home_',
                            'logout_',
                            'ajaxschool_',
                            'ajaxschool_city' 
                    ),
                    'school' => array (
                            'school_',
                            'school_menu',
                            'school_list' 
                    ) 
            ),
            'association' => array (
                    'home' => array (
                            'home_',
                            'logout_' 
                    ),
                    'school' => array (
                            'school_',
                            'school_input',
                            'school_accountlist',
                            'school_subjecttemplate',
                            'school_subjecttemplateupdown',
                            'school_subjecthead',
                            'school_adjustname',
                            'school_adjustnameupdown' 
                    ),
                    'lowermail' => array (
                            'lowermail_',
                            'lowermail_search',
                            'lowermail_select',
                            'lowermail_detail',
                            'lowermail_query',
                            'lowermail_download' 
                    ),
                    'orgchart' => array (
                            'orgchart_' 
                    ) 
            ),
            'school' => array (
                    'home' => array (
                            'home_',
                            'logout_',
                            'ajaxschool_',
                            'ajaxschool_city',
                            'ajaxschool_courrselist',
                            'ajaxpdf_',
                            'ajaxpdf_create',
                            'ajaxpdf_show',
                            'ajaxinvoice_',
                            'ajaxinvoice_mailsend',
                            'ajaxinvoice_mkereceipt',
                            'ajaxinvoice_print',
                            'ajaxinvoice_show',
                            'ajaxinvoice_getclassevent',
                            'ajaxinvoice_setspotinvoice',
                            'ajaxinvoice_getparentadjust',
                            'ajaxinvoice_registparentadjust',
                            'ajaxinvoice_checkparentadjust',
                            'ajaxinvoice_getinitfee',
                            'ajaxinvoice_getinvoiceyearmonth',
                            'ajaxinvoice_newestyearmonth',
                            'ajaxclass_',
                            'ajaxclass_namecheck' 
                    ),
                    'orgchart' => array (
                            'orgchart_' 
                    ),
                    'parent' => array (
                            'parent_',
                            'parent_list2',
                            'parent_list',
                            'parent_detail' 
                    ),
                    'student' => array (
                            'student_',
                            'student_search',
                            'student_detail',
                            'student_query',
                            'student_download',
                            'student_selectsubjecttemplate',
                            'student_selectreporttemplate' 
                    ),
                    'coach' => array (
                            'coach_',
                            'coach_list',
                            'coach_detail',
                            'coach_readfile' 
                    ),
                    'class' => array (
                            'class_',
                            'class_detail',
                            'class_list',
                            'class_search',
                            'class_studentlist',
                            'class_studentproc' 
                    ),
                    'course' => array (
                            'course_',
                            'course_coursedetail',
                            'course_list' 
                    ),
                    'program' => array (
                            'program_',
                            'program_detail',
                            'program_list',
                            'program_search',
                            'program_studentlist',
                            'program_studentproc' 
                    ),
                    'broadcast' => array (
                            'broadcastmail_',
                            'broadcastmail_search',
                            'broadcastmail_select',
                            'broadcastmail_detail',
                            'broadcastmail_query',
                            'broadcastmail_download',
                            'broadcastmail_dataconvert' 
                    ),
                    'invoice' => array (
                            'invoice_',
                            'invoice_detail',
                            'invoice_download',
                            'invoice_downloadcancel',
                            'invoice_downloadcomplete',
                            'invoice_downloadfile',
                            'invoice_generate',
                            'invoice_getarrearlist',
                            'invoice_invoicemanage',
                            'invoice_mailsearch',
                            'invoice_parentselect',
                            'invoice_print',
                            'invoice_receivecheck',
                            'invoice_receivecomplete',
                            'invoice_receivesearch',
                            'invoice_receiveselect',
                            'invoice_show',
                            'invoice_simpleback' 
                    ),
                    'statistic' => array (
                            'statistics_',
                            'statistics_list',
                            'statistics_detail',
                            'statistics_axis',
                            'statistics_axislog' 
                    ),
                    'school' => array (
                            'school_',
                            'school_accountlist',
                            'school_subjecttemplate',
                            'school_subjecthead',
                            'school_adjustname',
                            'school_schoollist',
                            'school_schoolsearch',
                            'school_schooldetail',
                            'school_studentgrade' 
                    ) 
            ) 
    );
    public static $edit_auth_datas = array (
            'system' => array (
                    'home' => array (
                            'home_',
                            'logout_',
                            'ajaxschool_',
                            'ajaxschool_city' 
                    ),
                    'school' => array (
                            'school_',
                            'school_menu',
                            'school_list',
                            'school_input',
                            'school_update',
                            'school_delete',
                            'school_confirm',
                            'school_completeinput',
                            'school_completeupdate',
                            'school_completedelete',
                            'school_readfile',
                            'menucontrol_default',
                            'menucontrol_update',
                            'menucontrol_reset',
                            'menucontrol_complete' 
                    ) 
            ),
            'association' => array (
                    'home' => array (
                            'home_',
                            'logout_' 
                    ),
                    'school' => array (
                            'school_',
                            'school_input',
                            'school_accountlist',
                            'school_accountedit',
                            'school_accountconfirm',
                            'school_accountdelete',
                            'school_accountcomplete',
                            'school_subjecttemplate',
                            'school_subjecttemplateentry',
                            'school_subjecttemplateconfirm',
                            'school_subjecttemplateupdown',
                            'school_subjecttemplatecomplate',
                            'school_subjectHead',
                            'school_subjectheadentry',
                            'school_subjectheadconfirm',
                            'school_subjectheadcomplate',
                            'school_adjustname',
                            'school_adjustnameupdown',
                            'school_adjustnameinput',
                            'school_adjustnameconfirm',
                            'school_adjustnamecomplete' 
                    ),
                    'lowermail' => array (
                            'lowermail_',
                            'lowermail_search',
                            'lowermail_select',
                            'lowermail_entry',
                            'lowermail_detail',
                            'lowermail_query',
                            'lowermail_download',
                            'lowermail_completesend',
                            'lowermail_save',
                            'lowermail_delete' 
                    ),
                    'orgchart' => array (
                            'orgchart_' 
                    ) 
            ),
            'school' => array (
                    'home' => array (
                            'home_',
                            'logout_',
                            'ajaxschool_',
                            'ajaxschool_city',
                            'ajaxschool_courrselist',
                            'ajaxpdf_',
                            'ajaxpdf_create',
                            'ajaxpdf_show',
                            'ajaxinvoice_',
                            'ajaxinvoice_mailsend',
                            'ajaxinvoice_mkereceipt',
                            'ajaxinvoice_print',
                            'ajaxinvoice_show',
                            'ajaxinvoice_getclassevent',
                            'ajaxinvoice_setspotinvoice',
                            'ajaxinvoice_getparentadjust',
                            'ajaxinvoice_registparentadjust',
                            'ajaxinvoice_checkparentadjust',
                            'ajaxinvoice_getinitfee',
                            'ajaxinvoice_getinvoiceyearmonth',
                            'ajaxinvoice_newestyearmonth',
                            'ajaxclass_',
                            'ajaxclass_namecheck' 
                    ),
                    'orgchart' => array (
                            'orgchart_' 
                    ),
                    'parent' => array (
                            'parent_',
                            'parent_list2',
                            'parent_list',
                            'parent_entry',
                            'parent_confirm',
                            'parent_complete',
                            'parent_detail' 
                    ),
                    'student' => array (
                            'student_',
                            'student_search',
                            'student_entry',
                            'student_confirm',
                            'student_complete',
                            'student_detail',
                            'student_query',
                            'student_download',
                            'student_uploadinput',
                            'student_uploadcomplete',
                            'student_receivecheck',
                            'student_receivecomplete',
                            'student_selectsubjecttemplate',
                            'student_selectreporttemplate' 
                    ),
                    'coach' => array (
                            'coach_',
                            'coach_list',
                            'coach_input',
                            'coach_edit',
                            'coach_delete',
                            'coach_confirm',
                            'coach_complete',
                            'coach_detail',
                            'coach_readfile' 
                    ),
                    'class' => array (
                            'class_',
                            'class_complete',
                            'class_completecopy',
                            'class_confirm',
                            'class_detail',
                            'class_input',
                            'class_list',
                            'class_search',
                            'class_studentlist',
                            'class_studentproc' 
                    ),
                    'course' => array (
                            'course_',
                            'course_coursecomplete',
                            'course_courseconfirm',
                            'course_coursedetail',
                            'course_courseentry',
                            'course_list' 
                    ),
                    'program' => array (
                            'program_',
                            'program_complete',
                            'program_completecopy',
                            'program_confirm',
                            'program_detail',
                            'program_input',
                            'program_list',
                            'program_search',
                            'program_studentlist',
                            'program_studentproc' 
                    ),
                    'broadcast' => array (
                            'broadcastmail_',
                            'broadcastmail_search',
                            'broadcastmail_select',
                            'broadcastmail_entry',
                            'broadcastmail_entry2',
                            'broadcastmail_detail',
                            'broadcastmail_query',
                            'broadcastmail_download',
                            'broadcastmail_completesend',
                            'broadcastmail_completesend2',
                            'broadcastmail_save',
                            'broadcastmail_delete',
                            'broadcastmail_dataconvert' 
                    ),
                    'invoice' => array (
                            'invoice_',
                            'invoice_accounttransfer',
                            'invoice_canceltransfer',
                            'invoice_delete',
                            'invoice_deletecomplete',
                            'invoice_detail',
                            'invoice_disabled',
                            'invoice_disabledcomplete',
                            'invoice_download',
                            'invoice_downloadcancel',
                            'invoice_downloadcomplete',
                            'invoice_downloadfile',
                            'invoice_edit',
                            'invoice_editcomplete',
                            'invoice_editconfirm',
                            'invoice_entry',
                            'invoice_entrycomplete',
                            'invoice_entryconfirm',
                            'invoice_generate',
                            'invoice_getarrearlist',
                            'invoice_invoicemanage',
                            'invoice_mailsearch',
                            'invoice_mailsend',
                            'invoice_multieditcomplete',
                            'invoice_parentselect',
                            'invoice_print',
                            'invoice_receivecheck',
                            'invoice_receivecomplete',
                            'invoice_receivesearch',
                            'invoice_receiveselect',
                            'invoice_show',
                            'invoice_simpleback',
                            'invoice_singleeditcomplete',
                            'invoice_upload',
                            'invoice_uploadcomplete' 
                    ),
                    'statistic' => array (
                            'statistics_',
                            'statistics_list',
                            'statistics_detail',
                            'statistics_axis',
                            'statistics_axislog' 
                    ),
                    'school' => array (
                            'school_',
                            'school_input',
                            'school_confirm',
                            'school_complete',
                            'school_inputindiv',
                            'school_confirmindiv',
                            'school_completeindiv',
                            'school_accountlist',
                            'school_accountedit',
                            'school_accountconfirm',
                            'school_accountdelete',
                            'school_accountcomplete',
                            'school_subjecttemplate',
                            'school_subjecttemplateentry',
                            'school_subjecttemplateconfirm',
                            'school_subjecttemplateupdown',
                            'school_subjecttemplatecomplate',
                            'school_subjecthead',
                            'school_subjectheadentry',
                            'school_subjectheadconfirm',
                            'school_subjectheadcomplate',
                            'school_adjustname',
                            'school_adjustnameupdown',
                            'school_adjustnameinput',
                            'school_adjustnameconfirm',
                            'school_adjustnamecomplete',
                            'school_schoollist',
                            'school_schoolsearch',
                            'school_schoolentry',
                            'school_schoolconfirm',
                            'school_schoolcomplete',
                            'school_schooldetail',
                            'school_schooldelete',
                            'school_studentgrade',
                            'school_studentgradeupdown',
                            'school_studentgradeinput',
                            'school_studentgradeconfirm',
                            'school_studentgradecomplete',
                            'menucontrol_default',
                            'menucontrol_update',
                            'menucontrol_reset',
                            'menucontrol_complete' 
                    ) 
            ) 
    );
    public static function getAuth($module) {
        $authority = session ( $module . '.login' ) ['authority'];
        $edit_authority = session ( $module . '.login' ) ['edit_authority'];
        
        $auths = array ();
        // view
        foreach ( self::$view_auth_datas [$module] as $auth_key => $list_actions ) {
            if (self::isWindows ()) {
                $permission = 1;
            } else {
                $permission = ($authority & self::$auth_keys [$auth_key]) ? 1 : 0;
            }
            
            foreach ( ( array ) $list_actions as $action_method ) {
                $auths [$action_method] = $permission;
            }
        }
        
        // edit
        foreach ( self::$edit_auth_datas [$module] as $auth_key => $list_actions ) {
            if (self::isWindows ()) {
                $permission = 1;
            } else {
                $permission = ($edit_authority & self::$auth_keys [$auth_key]) ? 1 : 0;
            }
            foreach ( ( array ) $list_actions as $action_method ) {
                if (! isset ( $auths [$action_method] ) || (isset ( $auths [$action_method] ) && $auths [$action_method] == 0)) {
                    $auths [$action_method] = $permission;
                }
            }
        }
        
        return $auths;
    }
    public static function isWindows() {
        if (strtoupper ( substr ( PHP_OS, 0, 3 ) ) === 'WIN' || PHP_OS == "Darwin") {
            return true;
        } else {
            return false;
        }
    }
    public static function checkPermission($_uri_array) {
        $module = strtolower ( $_uri_array [0] );
        $action = strtolower ( $_uri_array [1] );
        
        if (! isset ( $_SESSION [$module . '.login'] ) || ! isset ( $_SESSION [$module . '.login'] ['authority'] ) || empty ( $action )) {
            return 1;
        }
        
        if (sizeof ( $_uri_array ) > 2) {
            $method = strtolower ( implode ( '', preg_split ( "/\_/", $_uri_array [2] ) ) );
        } else {
            $method = '';
        }
        
        $auths = self::getAuth ( $module );
        if (array_key_exists ( $action . '_' . $method, $auths )) {
            return $auths [$action . '_' . $method];
        } else {
            return 0;
        }
    }
}
?>