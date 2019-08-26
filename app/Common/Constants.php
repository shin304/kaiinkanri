<?php
namespace App\Common;

class Constants {
    const IMG_MIME_TYPES = array(
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
    );

    const DEFAULT_PROFILE_IMG = '/img/school/default_user.png';

    const CASH = 'CASH';
    const TRAN_RICOH = 'TRAN_RICOH';
    const CONV_RICOH = 'CONV_RICOH';
    const POST_RICOH = 'POST_RICOH';
    const CRED_ZEUS = 'CRED_ZEUS';
    const TRAN_BANK = 'TRAN_BANK';
    const CRED_ZEUS_CONNECT = 'CRED_ZEUS_CONNECT';

    const invoice_background_color = array (
        '1' => array('top'=> '#8FC31F', 'bottom' => '#7aa71b'),
        '2' => array('top'=> '#b0b4f2', 'bottom' => '#7c7fa8'),
        '3' => array('top'=> '#fcc692', 'bottom' => '#ff9f42'),
        '4' => array('top'=> '#64a857', 'bottom' => '#406d37'),
        '5' => array('top'=> '#6873fb', 'bottom' => '#455db5'),
        '6' => array('top'=> '#da84ff', 'bottom' => '#9557af'),
        '7' => array('top'=> '#01DF74', 'bottom' => '#01DF74'),

    );

    static $PAYMENT_TYPE = [
        'CASH' => 1,
        'TRAN_RICOH' => 2,
        'CONV_RICOH' => 3,
        'POST_RICOH' => 4,
        'CRED_ZEUS' => 5,
        'TRAN_BANK' => 6,
        'CRED_ZEUS_CONNECT' => 7,
    ];

    // 請求方法
    public static $invoice_type = array (
        '2' => array(
            '1' => '現金',
            '2' => '口座振替',
            '3' => 'コンビニ決済',
            '4' => 'ゆうちょ振込',
            '5' => 'クレジットカード（都度）',
            '6' => '銀行振込',
            '7' => 'クレジットカード（継続）',
        ),
        '1' => array(
            '1' => 'Cash',
            '2' => 'Bank transfer Ricoh',
            '3' => 'Convenient store Ricoh',
            '4' => 'Post transfer Ricoh',
            '5' => 'Credit card',
            '6' => 'Bank transfer',
            '7' => 'Credit card conenct',
        )
    );


    const DEPOSIT_CANCEL = 99;
    public static $deposit_type = array(
        '2' => array(
            self::DEPOSIT_CANCEL => '入金取消し',
        ),
        '1' => array(
            self::DEPOSIT_CANCEL => 'Cancel deposit',
        )
    );
    public static $request_status = array (
        '2' => array(
            '0' => '',
            '1' => '作成済み',
            '2' => '処理中',
            '3' => '処理完了',
            '9' => '処理削除'
        ),
        '1' => array(
            '0' => '',
            '1' => 'Request form created',
            '2' => 'Under processing',
            '3' => 'Processing completed',
            '9' => 'Processing cancelled'
        )
    );
    public static $zengin_status = array (
        '2' => array(
            '0' => '振替済',
            '-1' => '資金不足',
            '-2' => '預金取引なし',
            '-3' => '預金者都合による振り替え停止',
            '-4' => '預金口座振替依頼書なし',
            '-8' => '委託者都合による振替停止',
            '-9' => 'その他'
        ),
        '1' => array(
            '0' => 'Transfer completed',
            '-1' => 'Money shortage',
            '-2' => 'No deposit transaction',
            '-3' => "Transfer stopped by depositor's condition",
            '-4' => "No bank transfer request form",
            '-8' => "Transfer stopped by consignor's condition",
            '-9' => 'Other'
        )
    );
    public static $dispSchoolCategory = array (
        0 => '小学',
        1 => '中学',
        2 => '高校',
        3 => '大学',
        11 => 'その他'
    );

    public static $header = array(
        '2' => array(
            'student_name'=>'会員名',
            'school'=>'学校',
            'year'=>'年',
            'class'=>'プラン',
            'month'=>'月',
            'day'=>'日'
        ),
        '1' => array(
            'student_name'=>'Student Name',
            'school'=>'School',
            'year'=>'Year',
            'class'=>'Class',
            'month'=>'Month',
            'day'=>'Day'
        )
    );

    const LOG_NOTIFICATION = '1';
    const LOG_ERROR = '2';
    public static $SYSTEM_LOG_STATUS = array(
        '2' => array(
            '1'=>'通知',
            '2'=>'エラー',
        ),
        '1' => array(
            '1'=>'Notification',
            '2'=>'Error',
        )
    );

    const STUDENT_HEADER_CSV = array(
        0 => "会員ステータス",
        1 => "会員番号◎",
        2 => "個人／法人◎",
        3 => "人数",
        4 => "会員種別◎",
        5 => "会員名前◎",
        6 => "会員ひらがな",
        7 => "会員フリガナ",
        8 => "会員ニックネーム",
        9 => "会員メールアドレス◎",
        10 => "会員パスワード",
        11 => "会員生年月日",
        12 => "会員性別",
        13 => "入会日",
        14 => "入会理由",
        15 => "退会日",
        16 => "退会理由",
        17 => "会員郵便番号◎",
        18 => "会員都道府県名",
        19 => "会員市区町村名",
        20 => "会員番地",
        21 => "会員ビル",
        22 => "会員連絡先電話番号◎",
        23 => "会員携帯電話",
        24 => "会員送付先宛名",
        25 => "請求先名前◎",
        26 => "請求先名前ひらがな",
        27 => "請求先名前カナ",
        28 => "請求先メールアドレス１◎",
        29 => "請求先パスワード",
        30 => "請求先郵便番号",
        31 => "請求先都道府県名",
        32 => "請求先市区町村名",
        33 => "請求先番地",
        34 => "請求先ビル",
        35 => "請求先自宅電話番号",
        36 => "請求先携帯電話番号",
        37 => "請求先メモ",
        38 => "通知方法",
        39 => "支払方法",
        40 => "金融機関種別",
        41 => "金融機関コード",
        42 => "金融機関名",
        43 => "支店コード",
        44 => "支店名",
        45 => "口座種別",
        46 => "口座番号",
        47 => "口座名義",
        48 => "通帳記号",
        49 => "通帳番号",
        50 => "通帳名義",
    );

    //    const DEFAULT_PARENT_MENU_KEY = array(
    //        'menu_home' => array(
    //                        'editable' => 0,
    //                        'viewable' => 1
    //                        ),
    //        'menu_members' => array(
    //                        'editable' => 1,
    //                        'viewable' => 1
    //                        ),
    //        'menu_billing_contacts' => array(
    //                                'editable' => 1,
    //                                'viewable' => 1
    //                                ),
    //        'menu_classes' => array(
    //                                'editable' => 0,
    //                                'viewable' => 1
    //                                ),
    //        'menu_events' => array(
    //                            'editable' => 0,
    //                            'viewable' => 1
    //                            ),
    //        'menu_programs' => array(
    //                                'editable' => 0,
    //                                'viewable' => 1
    //                                ),
    //        'menu_logout' => array(
    //                            'editable' => 1,
    //                            'view_able' => 1
    //                            )
    //    );

    const DEFAULT_PARENT_MENU_KEY = array(
        'menu_home',
        //            'menu_members',
        'menu_billing_contacts',
        'menu_billing',
        //        'menu_classes',
        //        'menu_events',
        //        'menu_programs',
        'menu_logout'
    );

    const DEFAULT_STUDENT_MENU_KEY = array(
        'menu_home',
        'menu_members',
        //        'menu_billing_contacts',
        //        'menu_classes',
        //        'menu_events',
        //        'menu_programs',
        'menu_logout'
    );

    const LOGIN_TYPE_MEMBER = "MEMBER" ;
    const LOGIN_TYPE_SCHOOL = "SCHOOL" ;
    const LOGIN_TYPE_ADMIN = "ADMIN" ;

    const CATEGORY_TYPES = array(
        '1' => '登録会員数',
        '2' => '有効会員数',
        '3' => '施設数（階層化の場合）'
    );

    const ERROR_LIMIT_PLAN_ACCESS = array(
        '001' => 'reach_limit_number_register',
        '002' => 'reach_limit_number_active'
    );

    // session key contain number of fail when create invoice
    const SESSION_COUNT_FAIL_INVOICE = 'count_not_supported_payment';

    const COUNTRY_CODE = array(
        "JP" => 81,
    );

    const ONE_HOUR = 60 * 60 ;/* 一時間 */

    /*
     * status of demo account registration
     * and japanese text base on status
     */
    const STATUS_SUBMIT_INFO = 1; // 1:申請済み
    const STATUS_MAIL_CONFIRMED = 2; // 2:承認待ち　
    const STATUS_APPROVED = 3; // 3:承認済み
    const STATUS_ADMIN_DENIED = 4; // 4:取消

    const DEMO_ACCOUNT_STATUS = array(
        '1' => '申請済み' ,
        '2' => '承認待ち' ,
        '3' => '承認済み' ,
        '4' => '取消'
    );
}
