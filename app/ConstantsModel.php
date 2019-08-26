<?php

namespace App;


class ConstantsModel {
    public static $APP_NAME = "ICTEL";
    public static $GENETTA_LOC = "http://genetta.asto-system.biz/api/ps/";
    // public static $GENETTA_LOC = "http://genetta.loc/api/ps/";
    const GENETTA_APIKEY = "zZHiYUGail8AwYi0";
    const CRYPT_KEY_NUM = 8;

    /**
     * 1ページのページ表示件数
     *
     * @var unknown_type
     */
    const PER_PAGE = 20;
    // const PER_PAGE = 5;

    /**
     * ページャーの表示ページ数
     *
     * @var unknown_type
     */
    const PAGE_NAVI_MAX = 15;
    // const PAGE_NAVI_MAX = 2;

    /**
     * メニュー表示・編集権限フラグ
     */

    const Home_menu_no = 1; // HOME画面
    const Logout_menu_no = 2; // ログアウト
    const Orgchart_menu_no = 7; // 支部一覧
    const Lowermail_menu_no = 5; // お知らせ送信
    const School_menu_no = 6; // 基本情報
    const Class_menu_no = 8; // プラン管理
    const Course_menu_no = 9; // イベント管理
    const Program_menu_no = 10; // プログラム管理
    const Broadcast_menu_no = 11; // メール一斉送信
    const Invoice_menu_no = 12; // 請求管理
    const Statistic_menu_no = 13; // 統計管理
    const Student_menu_no = 14; // 生徒管理
    const Parent_menu_no = 15; // 請求先管理
    const Coach_menu_no = 16; // 講師管理

    // 協会の権限
    const AS_Home_menu_no = 1; // HOME画面
    const AS_Logout_menu_no = 2; // ログアウト
    const AS_Orgchart_menu_no = 4; // 加盟団体一覧
    const AS_Lowermail_menu_no = 5; // メール一斉送信
    const AS_School_menu_no = 6; // 基本情報

    //システム
    const S_Home_menu_no = 1; // HOME画面
    const S_Logout_menu_no = 2; // ログアウト
    const S_School_Manage_menu_no = 3; // 協会・本部管理

    /**
     * メニュー表示・編集権限フラグ
     */
    // 本部・支部の権限
    const Home_Auth              = 0x001000000000000; // HOME画面
    const Orgchart_Auth     	 = 0x004000000000000; // 組織図
    const Lowermail_Auth     	 = 0x000000000010000; // お知らせ送信
    const School_Auth     	 	 = 0x002000000000000; // 基本情報
    const Class_Auth             = 0x000000000010000; // プラン情報管理
    const Course_Auth            = 0x000000000020000; // イベント管理
    const Program_Auth           = 0x000000000040000; // プログラム管理
    const Broadcast_Auth         = 0x000000000080000; // メール一斉送信
    const Invoice_Auth           = 0x000000000100000; // 請求管理
    const Statistic_Auth         = 0x000000000000001; // 統計管理
    const Student_Auth           = 0x000000100000000; // 生徒管理
    const Parent_Auth            = 0x000000200000000; // 請求先管理
    const Coach_Auth             = 0x000000400000000; // 講師管理

    // ---------------
    // 問題集一覧検索情報の取得
    // ---------------
    public static $dispOutputOrderCategory1 = array (
        1 => '最終更新日時',
        2 => '登録日時',
        3 => '問題集タイトル',
        4 => 'ステータス'
    );
    public static $dispOutputOrderCategory2 = array (
        1 => '降順',
        2 => '昇順'
    );

    // ---------------
    // 条件情報の取得
    // ---------------
    // 学年
    public static $dispSchoolCategory = array (
        0 => '小学',
        1 => '中学',
        2 => '高校',
        3 => '大学',
        11 => 'その他'
    );
    public static $dispSchoolYear = array (
        1 => '1',
        2 => '2',
        3 => '3'
    );
    public static $dispMidSchoolYear = array (
        1 => '1年生',
        2 => '2年生',
        3 => '3年生',
        4 => '4年生',
        5 => '5年生',
        6 => '6年生'
    );
    public static $dispHighSchoolYear = array (
        1 => '1年生',
        2 => '2年生',
        3 => '3年生'
    );

    // public static $subjects = array(
    // 1 => '英語',
    // 2 => '数学',
    // 3 => '国語',
    // 9 => 'その他',
    // );

    // メンテナンスモード
    // const MAINTENANCE_MODE_ON = 2;
    // const MAINTENANCE_MODE_OFF = 1;
    // public static $MAINTENANCE_MODE = array(
    // self::MAINTENANCE_MODE_OFF => 'off',
    // self::MAINTENANCE_MODE_ON => 'on',
    // );

    // 口座種別
    public static $type_of_bank_account = array (
        '2' => array(
            '1' => '普通預金',
            '2' => '当座預金'
        ),
        '1' => array(
            '1' => 'Savings account',
            '2' => 'Checking account'
        )
    );
    // '4'=>'貯蓄預金',
    // '9'=>'その他',


    // 〆日のリスト
    public static $close_date_list = array (
        '2' => array(
            '1' => '01',
            '2' => '02',
            '3' => '03',
            '4' => '04',
            '5' => '05',
            '6' => '06',
            '7' => '07',
            '8' => '08',
            '9' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
            '31' => '31',
            '99' => '末'
        ),
        '1' => array(
            '1' => '01',
            '2' => '02',
            '3' => '03',
            '4' => '04',
            '5' => '05',
            '6' => '06',
            '7' => '07',
            '8' => '08',
            '9' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
            '31' => '31',
            '99' => 'End'
        )
    );

    // 請求日のリスト
    public static $invoice_date_list = array (
        '2' => array(
            '1' => '01',
            '2' => '02',
            '3' => '03',
            '4' => '04',
            '5' => '05',
            '6' => '06',
            '7' => '07',
            '8' => '08',
            '9' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
            '31' => '31',
            '99' => '末'
        ),
        '1' => array(
            '1' => '01',
            '2' => '02',
            '3' => '03',
            '4' => '04',
            '5' => '05',
            '6' => '06',
            '7' => '07',
            '8' => '08',
            '9' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
            '31' => '31',
            '99' => 'End'
        )
    );

    // 口座引落日
    public static $withdrawal_date_list = array (
        '4' => '4',
        '20' => '20',
        '27' => '27'
    );
    // '99'=>'末',


    // 金額表示
    public static $amt_disp_type_list = array (
        '2' => array(
            '0' => '税込表示',
            '1' => '税別表示'
        ),
        '1' => array(
            '0' => 'Tax including mode',
            '1' => 'Tax excluding mode'
        )
    );
    public static $difficulty_list = array (
        '3' => '易しい',
        '5' => '普通',
        '7' => '難しい'
    );

    /**
     * ****************************************
     */

    // コマ時間
    public static $type_of_unit_time = array (
        '1' => '30',
        '2' => '60',
        '3' => '120',
        '4' => '180'
    );

    // 面談カテゴリー
    public static $type_of_consultation = array (
        1 => '面談',
        2 => 'その他'
    );

    // 面談場所スケジュール
    public static $plase_of_consultation = array (
        1 => '東京八王子校第一教室',
        2 => '東京八王子校第二教室',
        3 => '東京八王子校第三教室',
        4 => '名古屋栄校第A教室',
        5 => '名古屋栄校第B教室',
        9 => 'その他'
    );

    // 面談講師スケジュール
    public static $teacher_of_consultation = array (
        1 => '水野',
        2 => '田中',
        3 => '渡辺',
        4 => '佐藤',
        5 => '瀬戸山',
        9 => 'その他'
    );

    // コマ時間設定
    public static $koma_times = array (

        // 30分
        "1" => array (
            "09:00～09:30",
            "09:30～10:00",
            "10:00～10:30",
            "10:30～11:00",
            "11:00～11:30",
            "11:30～12:00",
            "12:00～12:30",
            "12:30～13:00",
            "13:00～13:30",
            "13:30～14:00",
            "14:00～14:30",
            "14:30～15:00",
            "15:00～15:30",
            "15:30～16:00",
            "16:00～16:30",
            "16:30～17:00",
            "17:00～17:30",
            "17:30～18:00",
            "18:00～18:30",
            "18:30～19:00",
            "19:00～19:30",
            "19:30～20:00"
        ),
        // 60分
        "2" => array (
            "09:00～10:00",
            "10:00～11:00",
            "11:00～12:00",
            "12:00～13:00",
            "13:00～14:00",
            "14:00～15:00",
            "15:00～16:00",
            "16:00～17:00",
            "17:00～18:00",
            "18:00～19:00",
            "19:00～20:00"
        ),
        // 120分
        "3" => array (
            "09:00～11:00",
            "11:00～13:00",
            "13:00～15:00",
            "15:00～17:00",
            "17:00～19:00"
        ),
        // 180分
        "4" => array (
            "09:00～12:00",
            "12:00～15:00",
            "15:00～18:00"
        )
    );

    // 面談割当check
    public static $assignment_plase_of_consultation = array (
        '2' => array(
            '1' => '未割当',
            '2' => '割当済み',
            '3' => '割当不可'
        ),
        '1' => array(
            '1' => 'Unassigned',
            '2' => 'Assigned',
            '3' => 'Unable to assign'
        )
    );

    // 編集フラグ
    public static $is_established_of_invoice = array (
        '2' => array(
            '0' => '編集中',
            '1' => '確定'
        ),
        '1' => array(
            '0' => 'Editing',
            '1' => 'Confirmed'
        )
    );

    // 請求フラグ
    public static $is_requested_of_invoice = array (
        '2' => array(
            '0' => '未請求',
            '1' => '請求済'
        ),
        '1' => array(
            '0' => 'Unclaimed',
            '1' => 'Invoiced'
        )
    );

    // 入金フラグ
    public static $is_recieved_of_invoice = array (
        '2' => array(
            '0' => '未入金',
            '1' => '入金済'
        ),
        '1' => array(
            '0' => 'Unpaid',
            '1' => 'Paid'
        )
    );

    // メール通知対象フラグ
    public static $mail_announce_list_of_invoice = array (
        '2' => array(
            '0' => 'メール通知非対象',
            '1' => 'メール通知対象'
        ),
        '1' => array(
            '0' => 'Mail notification nontarget',
            '1' => 'Mail notification target'
        )
    );

    // メール通知タイプ
    public static $mail_type_of_mail_message = array (
        '2' => array(
            '1' => '請求書',
            '2' => '面談(申込)',
            '3' => 'イベント',
            '4' => '面談(日程)',
            '5' => 'プログラム',
            '6' => 'お知らせ'
        ),
        '1' => array(
            '1' => 'Bill',
            '2' => 'Interview (application)',
            '3' => 'Event',
            '4' => 'Interview (schedule)',
            '5' => 'Program',
            '6' => 'Broadcastmail'
        )
    );

    // メール確認状態
    public static $confirm_status_of_mail_message = array (
        '2' => array(
            '0' => '未確認',
            '1' => '確認済'
        ),
        '1' => array(
            '0' => 'Unconfirmed',
            '1' => 'Confirmed'
        )
    );

    // 請求方法
    public static $invoice_type = array (
        '2' => array(
            '0' => '現金',
            '1' => '振込',
            '2' => '口座振替',
        ),
        '1' => array(
            '0' => 'Cash',
            '1' => 'Transfer',
            '2' => 'Bank transfer'
        )
    );
    // 通知方法
    public static $mail_infomation = array (
        '2' => array(
            '0' => '郵送',
            '1' => 'メール',
            '2' => '送信しない'
        ),
        '1' => array(
            '0' => 'Mailing',
            '1' => 'Email',
            '2' => 'Not send'
        )

    );

    public static $bank_type = array (
        '2' => array(
            '1' => '銀行・信用金庫',
            '2' => '郵便局'
        ),
        '1' => array(
            '1' => 'Bank',
            '2' => 'Post'
        )

    );
    public static $workflow_status = array (
        '2' => array(
            '0' => '編集中',
            '1' => '編集完了',
            '11' => '請求書発送済み',
            '21' => '請求データ作成済み',
            '22' => '口座振替 処理中',
            '29' => '口座振替未完了',
            '31' => '入金済み'
        ),
        '1' => array(
            '0' => 'Editing',
            '1' => 'Edit completed',
            '11' => 'Bill sent',
            '21' => 'Bank transfer statement created',
            '22' => 'Bank transfer under processing',
            '29' => 'Bank transfer incompleted',
            '31' => 'Paid'
        )
    );
    public static $requesttable_status = array (
        '2' => array(
            '0' => '',
            '1' => '請求データ作成済み',
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
    public static $booklet_active_flag = array (
        '2' => array(
            '0' => '未公開',
            '1' => '公開',
            '2' => 'プラン',
            '9' => '未'
        ),
        '1' => array(
            '0' => 'Unpublished',
            '1' => 'Published',
            '2' => 'Class',
            '9' => 'Not yet'
        )
    );
    public static $bc_option = array(
        '2' => array(
            '1'=>'会員のみ',
            '2'=>'請求先のみ',
            '3'=>'会員と請求先',
            '4'=>'講師のみ',
            '5'=>'会員と講師',
            '6'=>'請求先と講師',
            '7'=>'会員と請求先と講師'
        ),
        '1' => array(
            '1'=>'Students only',
            '2'=>'Parents only',
            '3'=>'Students and parents',
            '4'=>'Coaches only',
            '5'=>'Students and coaches',
            '6'=>'Parents and coaches',
            '7'=>'Students and parents and coaches'
        )
    );


    public static $send_flag = array (
        '2' => array(
            '0' => '下書き保存',
            '1' => '送信済み'
        ),
        '1' => array(
            '0' => 'Draft saved',
            '1' => 'Sent'
        )
    );
    public static $subject_template_type = array (
        '2' => array(
            '1' => '教科・科目',
            '2' => '通知表'
        ),
        '1' => array(
            '1' => 'Subjects',
            '2' => 'Report cards'
        )
    );
    public static $business_divisions_type = array (
        '2' => array(
            '1' => '塾',
            '2' => '会員クラブ',
            '3' => '塾本部・支部',
            '4' => '会員クラブ本部・支部'
        ),
        '1' => array(
            '1' => 'School',
            '2' => 'Member Club',
            '3' => 'School Headquarters & Branch',
            '4' => 'Member Club Headquarters & Branch'
        )
    );

    public static $org_type = array(
        '2' => array(
            '0' => '支部（普通）',
            '1' => '支部（管理）'
        ),
        '1' => array(
            '0' => 'Branch (Ordinary)',
            '1' => 'Branch (Admin)'
        )
    );
    public static $payment_style = array (
        '2' => array(
            '1' => '前払い',
            '2' => '後払い'
        ),
        '1' => array(
            '1' => 'Prepaid',
            '2' => 'Postpaid'
        )
    );
    public static $month_list = array (
        '2' => array(
            '1' => ' 1月',
            '2' => ' 2月',
            '3' => ' 3月',
            '4' => ' 4月',
            '5' => ' 5月',
            '6' => ' 6月',
            '7' => ' 7月',
            '8' => ' 8月',
            '9' => ' 9月',
            '10' => '10月',
            '11' => '11月',
            '12' => '12月'
        ),
        '1' => array(
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec'
        )
    );
    public static $teacher_type = array (
        '2' => array(
            '1' => '正社員',
            '2' => 'アルバイト'
        ),
        '1' => array(
            '1' => 'Permanent employee',
            '2' => 'Part-time employee'
        )
    );
    public static $month_listEx = array (
        '2' => array(
            '99' => '毎月',
            '1' => ' 1月',
            '2' => ' 2月',
            '3' => ' 3月',
            '4' => ' 4月',
            '5' => ' 5月',
            '6' => ' 6月',
            '7' => ' 7月',
            '8' => ' 8月',
            '9' => ' 9月',
            '10' => '10月',
            '11' => '11月',
            '12' => '12月'
        ),
        '1' => array(
            '99' => 'Every month',
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec'
        )
    );

    public static $roles = array (
        'SYSADMIN' => 'administrator',
        'KYOUKAI' => 'kyoukai',
        'DANTAI' => 'dantai',
        'STAFF' => 'staff',
        'COACH' => 'coach',
        'PARENT' => 'parent',
        'STUDENT' => 'student'
    );

    // 雇用
    public static $teacher_employment_type = array(
        '2' => array(
            '1'=>'正社員',
            '2'=>'契約社員',
            '3'=>'アルバイト'
        ),
        '1' => array(
            '1'=>'Permanent employee',
            '2'=>'Contract employee',
            '3'=>'Part-time employee'
        )
    );

    // 曜日
    public static $weekno = array(
        '2' => array(
            '1'=>'月',
            '2'=>'火',
            '3'=>'水',
            '4'=>'木',
            '5'=>'金',
            '6'=>'土',
            '7'=>'日'
        ),
        '1' => array(
            '1'=>'Mon',
            '2'=>'Tue',
            '3'=>'Wed',
            '4'=>'Thu',
            '5'=>'Fri',
            '6'=>'Sat',
            '7'=>'Sun'
        )
    );

    // 回数の単位
    public static $attend_times_div = array(
        '2' => array(
            '1'=>'月',
            '2'=>'週',
            '3'=>'１回',
            '9'=>'無制限'
        ),
        '1' => array(
            '1'=>'Month',
            '2'=>'Week',
            '3'=>'Once',
            '9'=>'Limitless'
        )
    );

    public static $org_status = array(
        '2' => array(
            '0' => '非アクティブ',
            '1' => 'アクティブ'
        ),
        '1' => array(
            '0' => 'Inactive',
            '1' => 'Active'
        )
    );

    public static $weather_imgs = array(
        '晴れ' => 'weather_sun.gif',
        '晴' => 'weather_sun.gif',
        '曇り' => 'weather_clouds.gif',
        '曇' => 'weather_clouds.gif',
        '雨' => 'weather_rain.gif',
        '雪' => 'weather_snow.gif',
        '晴時々曇' => 'weather_sun_clouds_st.gif',
        '晴時々雨' => 'weather_sun_rain_st.gif',
        '晴時々雪' => 'weather_sun_snow_st.gif',
        '曇時々晴' => 'weather_clouds_sun_st.gif',
        '曇時々雨' => 'weather_clouds_rain_st.gif',
        '曇時々雪' => 'weather_clouds_snow_st.gif',
        '雨時々晴' => 'weather_rain_sun_st.gif',
        '雨時々雲' => 'weather_rain_clouds_st.gif',
        '雨時々雪' => 'weather_rain_snow_st.gif',
        '雪時々晴' => 'weather_snow_sun_st.gif',
        '雪時々雲' => 'weather_snow_clouds_st.gif',
        '雪時々雨' => 'weather_snow_rain_st.gif',
        '晴後曇' => 'weather_sun_clouds_af.gif',
        '晴後雨' => 'weather_sun_rain_af.gif',
        '晴後雪' => 'weather_sun_snow_af.gif',
        '曇後晴' => 'weather_clouds_sun_af.gif',
        '曇後雨' => 'weather_clouds_rain_af.gif',
        '曇後雪' => 'weather_clouds_snow_af.gif',
        '雨後晴' => 'weather_rain_sun_af.gif',
        '雨後雲' => 'weather_rain_clouds_af.gif',
        '雨後雪' => 'weather_rain_snow_af.gif',
        '雪後晴' => 'weather_snow_sun_af.gif',
        '雪後雲' => 'weather_snow_clouds_af.gif',
        '雪後雨' => 'weather_snow_rain_af.gif',
        '暴風雪' => 'weather_blizzard.gif'
    );

    public static $languages = array(
        '2' => '日本語',
        '1' => 'English'
    );

    public static $languages_input = array(
        '2' => array(
            '2' => '日本語',
            '1' => '英語'
        ),
        '1' => array(
            '2' => 'Japanese',
            '1' => 'English'
        )
    );

    public static $currencies = array(
        '1' => '円',
        '2' => '＄',
        '3' => '€'
    );

    public static $country_list = array(
        '2' => array(
            '81' => '日本',
            '82' => '韓国',
            '61' => 'オーストラリア',
            '64' => 'ニュージーランド',
            '44' => 'イギリス',
            '1'  => 'カナダ',
            '55' => 'ブラジル'
        ),
        '1' => array(
            '81' => 'Japan',
            '82' => 'South Korea',
            '61' => 'Australia',
            '64' => 'New Zealand',
            '44' => /*'England',*/'United Kingdom',
            '1'  => 'Canada',
            '55' => 'Brazil'
        )
    );

    public static $bread_list = array(
        '2' => array(
            'edit_detail_info' 					=> '詳細情報編集', // SchoolAction
            'edit_confirm_detail_info' 			=> '詳細情報編集確認',
            'individual_info_setting' 			=> '個別情報設定',
            'confirm_invividual_info_setting'	=> '個別情報設定確認',
            'account_list'						=> 'ログイン権限設定一覧',
            'edit_account_info'					=> 'ログイン権限設定編集',
            'regist_account_info'				=> 'ログイン権限設定登録',
            'confirm_staff_info'				=> 'ログイン権限設定確認',
            'discount_list'						=> '割引・割増名称一覧',
            'edit_discount_name'				=> '割引・割増名称編集',
            'confirm_discount'					=> '割引・割増名称確認',
            'branch_list'						=> '支部一覧',
            'edit_branch'						=> '支部編集',
            'regist_branch'						=> '支部登録',
            'confirm_branch'					=> '支部確認',
            'branch_detail'						=> '支部詳細',
            'belt_color_list'					=> '帯色一覧',
            'belt_color_edit'					=> '帯色編集',
            'belt_color_confirm'				=> '帯色確認',
            'class_manage'						=> 'プラン管理',
            'list_detail_screen'				=> '詳細一覧画面',
            'edit_detail_screen'				=> '詳細編集画面',
            'confirm_detail_screen'				=> '詳細確認画面',
            'subject_course_setting'			=> '教科・科目設定',
            'edit_screen'						=> '編集画面',
            'confirm_screen'					=> '確認画面',
            'parent_select'						=> '請求先選択', // ParentAction
            'parent_manage'						=> '請求先管理',
            'parent_edit'						=> '請求先編集',
            'parent_register'					=> '請求先登録',
            'parent_confirm'					=> '請求先確認',
            'parent_detail'						=> '請求先詳細',
            'student'							=> '会員', 	// ClassAction
            'add_student'						=> '会員追加',
            'delete_student'					=> '会員削除',
            'edit'								=> '編集',
            'upload'							=> 'CSV登録',
            'register'							=> '登録',
            'detail'							=> '詳細',
            'confirm'							=> '確認',
            'class'								=> 'プラン',
            'course'							=> 'イベント',
            'course_manage'						=> 'イベント管理',// CourseAction
            'edit_confirm'						=> '編集確認',
            'summary'							=> '集計', //  StatisticsAction
            'list'								=> '一覧',
            'mailedit'							=> 'お知らせ編集',
            'mailregister'						=> 'お知らせ登録',
            'maildetail'						=> 'お知らせ詳細',
            'broadcastmail'						=> 'お知らせ送信',//BroadcastmailAction
            'select_destination'				=> '送信先選択',
            'mail_select'						=> 'メール送信先選択',
            'confirm_mail_select'				=> 'メール送信先選択確認',
            'coach'								=> '講師',			// Coach Action
            'coach_manage'						=> '講師管理',
            'coach_register'					=> '講師登録',
            'coach_edit'						=> '講師編集',
            'coach_confirm'						=> '講師確認',
            'coach_detail'						=> '講師詳細',
            'old_student'						=>'会員',// StudentAction
            'manage'							=>'管理',
            'edit_detail'						=>'詳細編集',
            'confirm_edit_detail'				=>'詳細編集確認',
            'invoice' 							=>'請求書',//invoice
            'invoice_list'						=>'請求書一覧',
            'invoice_confirm_screen'			=>'請求書確認画面',
            'invoice_edit_confirm_screen'		=>'請求書編集確認画面',
            'invoice_edit_screen'				=>'請求書編集画面',
            'payment_process'					=>'入金処理',
            'payment_check'						=>'入金チェック',
            'send_invoice'						=>'請求書送付',
            'account_transfer'					=>'口座振替',
            'create_form_account_transfer'		=>'請求データ作成',
            'account_transfer_capture_result'	=>'口座振込結果取込',
            'invoice_process'					=>'請求書処理',
            'csv_registration'					=>'CSV登録',
            'program'							=>'プログラム',
            'program_manage'					=>'プログラム管理',
            'program_detail'					=>'プログラム詳細',
            'program_input'						=>'プログラム登録',
            'program_confirm'					=>'プログラム確認',
            'program_edit'						=>'プログラム編集',
            'school'							=>'基本情報',
            'bulletin_board_manage'				=>'掲示板管理',
            'bulletin_board_detail'				=>'掲示板詳細',
            'bulletin_board_input'				=>'掲示板登録',
            'bulletin_board_confirm'			=>'掲示板確認',
            'bulletin_board_edit'				=>'掲示板編集',
        ),
        '1' => array(
            'edit_detail_info' 					=> 'Edit detail info',
            'edit_confirm_detail_info' 			=> 'Confirm edited detail info',
            'individual_info_setting' 			=> 'Individual info setting',
            'confirm_invividual_info_setting'	=> 'Confirm invividual info setting',
            'account_list'						=> 'Staff list',
            'edit_account_info'					=> 'Edit staff info',
            'regist_account_info'				=> 'Regist staff info',
            'confirm_staff_info'				=> 'Confirm staff info',
            'discount_list'						=> 'Discount list',
            'edit_discount_name'				=> 'Edit discount',
            'confirm_discount'					=> 'Confirm discount',
            'branch_list'						=> 'Branch list',
            'edit_branch'						=> 'Edit branch',
            'regist_branch'						=> 'Branch register',
            'confirm_branch'					=> 'Confirm branch',
            'branch_detail'						=> 'Branch detail',
            'belt_color_list'					=> 'Belt level list',
            'belt_color_edit'					=> 'Edit belt level',
            'belt_color_confirm'				=> 'Confirm belt level',
            'class_manage'						=> 'Class manage',
            'list_detail_screen'				=> 'List Detail Screen',
            'edit_detail_screen'				=> 'Edit Detail Screen',
            'confirm_detail_screen'				=> 'Confirm Detail Screen',
            'subject_course_setting'			=> 'Subject・Course Setting',
            'edit_screen'						=> 'Edit Screen',
            'confirm_screen'					=> 'Confirm Screen',
            'parent_select'						=> 'Parent Select',// ParentAction
            'parent_manage'						=> 'Parent Manage',
            'parent_edit'						=> 'Parent Edit',
            'parent_register'					=> 'Parent Register',
            'parent_confirm'					=> 'Parent Confirm',
            'parent_detail'						=> 'Parent Detail',
            'student'							=> 'Student', 	// ClassAction
            'add_student'						=> 'Add Student',
            'delete_student'					=> 'Delete Student',
            'edit'								=> 'Edit',
            'register'							=> 'Register',
            'detail'							=> 'Detail',
            'confirm'							=> ' Confirm',
            'class'								=> 'Class',
            'course'							=> 'Event',
            'course_manage'						=> 'Event Manage',// CourseAction
            'edit_confirm'						=> 'Edit Confirm',
            'summary'							=> 'Summary', //  StatisticsAction
            'list'								=> ' List',
            'mailedit'							=> 'Mail Edit',
            'mailregister'						=> 'Mail Register',
            'maildetail'						=> 'Mail Detail',
            'broadcastmail'						=> 'Broadcastmail',//BroadcastmailAction
            'select_destination'				=> 'Select Destination',
            'mail_select'						=> 'Mail Select',
            'confirm_mail_select'				=> 'Confirm Mail Select',
            'coach'								=> 'Coach',			// Coach Action
            'coach_manage'						=> 'Coach manage',
            'coach_register'					=> 'Coach register',
            'coach_edit'						=> 'Edit coach',
            'coach_confirm'						=> 'Confirm coach',
            'coach_detail'						=> 'Coach detail',
            'old_student'						=>' Student',
            'manage'							=>' Manage',
            'edit_detail'						=>' Edit Detail',
            'confirm_edit_detail'				=>' Confirm Edit Detail',
            'invoice' 							=>'Invoice',//invoice
            'invoice_list'						=>'Invoice List',
            'invoice_confirm_screen'			=>'Invoice Confirm Screen',
            'invoice_edit_confirm_screen'		=>'Invoice Edit Confirm Screen',
            'invoice_edit_screen'				=>'Invoice Edit Screen',
            'payment_process'					=>'Payment process',
            'payment_check'						=>'Payment Check',
            'send_invoice'						=>'Send Invoice',
            'account_transfer'					=>'Account Transfer',
            'create_form_account_transfer'		=>'Create Form Account Transfer',
            'account_transfer_capture_result'	=>'Account Transfer Capture Result',
            'invoice_process'					=>'Invoice Processing',
            'csv_registration'					=>'CSV Registration',
            'program'							=>'Program',
            'program_manage'					=>'Program manage',
            'program_detail'					=>'Program Detail',
            'program_input'						=>'Program Input',
            'program_confirm'					=>'Program Confirm',
            'program_edit'						=>'Program Edit',
            'school'							=>'School',
            'bulletin_board_manage'				=>'Bulletin Board Manage',
            'bulletin_board_detail'				=>'Bulletin Board Detail',
            'bulletin_board_input'				=>'Bulletin Board Input',
            'bulletin_board_confirm'			=>'Bulletin Board Confirm',
            'bulletin_board_edit'				=>'Bulletin Board Edit',
        )
    );

    public static $errors = array(
        '2' => array(
            'regist_process_error' 	=> '※登録処理に失敗しました。',
            'update_process_error' 	=> '※更新処理に失敗しました。',
            'delete_process_error' 	=> '※削除処理に失敗しました。',
            'required_error'		=> '※%sが入力されていません。',
            'required_1_error'		=> '※%sを正しく入力してください。',
            'email_length_error'	=> '※%sが長すぎます。64文字以下で設定してください。',
            'existed_email_error'	=> '※管理者のメールアドレスは既に存在しています。'	,
            'required_password'		=> '※パスワードが入力されていません。',
            'same_password_error'	=> '※入力されたパスワードが一致しません。',
            'password_regex'		=> '※パスワードは半角英数字及び記号のみ入力してください。',
            'password_min_error'	=> '※パスワードは8文字以上で設定してください ',
            'password_max_error'	=> '※パスワードが長すぎます。64文字以下で設定してください。',
            'required_branch_name'	=> '※支部名が入力されていません。',
            'branch_name_min_error'	=> '※組織名は255文字以下で設定してください。' ,
            'delete_parent_process_error' =>	'登録された会員がいるので、削除できません。',
            'register_student_error'=> '会員情報登録失敗(塾id=%s, 会員名前=%s)',
            'get_file_csv_error'	=> 'CSVファイルが取得できませんでした',
            'school_arr_error'		=>'スクールid:%s',
            'school_name_arr_error'	=>',スクール名:%s',
            'record_arr_error'		=>',レコード:%sの%d行目',
            'student_arr_error'		=>'エラー:%sの%d行目No.',
            'student_csv_upload_error'	=>'会員CSVアップロードエラー（%s）：',
            'invalid_url_error'		=>'不正なURLです。',
            'invoice_not_initialized_error'=>'請求書が初期化されていない',
            'template_file_error'	=>'テンプレートファイルが存在しない',
            'process_invoice_error_message' => 'エラーが発生したため処理できませんでした。',
            'send_mail_fail'		=>'メールの送信に失敗しました。',
            'invalid_request'		=>'無効な要求です。',
            'request_form_deleted'	=>'依頼書を削除しました。',
            'file_size_error_define_less_than'=>'ファイルサイズエラー(規定より小さい)',
            'file_size_error_data_record_size'=>'ファイルサイズエラー(データレコードサイズ)',
            'header_record_error'	=>'ヘッダーレコードエラー',
            'data_record_error'		=>'データレコードエラー',
            'trailer_record_error'	=>'トレーラレコードエラー',
            'end_record_error'		=>'エンドレコードエラー',
            'unknown_record_error'	=>'不明レコードエラー',
            'file_is_processed'		=>'このファイルは、処理済みです',
            'file_is_not_processed'	=>'このファイルは、処理対象ファイルではありません',
            'data_other_school_error'=>'当該校以外のデータ',
            'trailer_record_number_mismatch'=>'トレーラーレコード データ件数不一致',
            'withdrawal_amount_difference'=>'引落金額の相違:',
            'target_data_not_exist'	=>'対象データが存在しない:',
            'target_upload_file_not_exist'=>'対象アップロードファイルが存在しない',
            'file_open_error'		=>'ファイルオープンエラー',
            'data_writing_error'	=>'データ書き込みエラー',
        ),
        '1' => array(
            'regist_process_error' 	=> '※Regist process failed error.',
            'update_process_error' 	=> '※Update process failed error.',
            'delete_process_error' 	=> '※Delete process failed error.',
            'required_error'		=> '※Please enter value for %s field',
            'required_1_error'		=> '※Please enter value for %s field',
            'email_length_error'	=> '※%s field is too long. Please enter value less than or equal 64 characters',
            'existed_email_error'	=> '※Admin email is already existed',
            'required_password'		=> '※Please enter value for password field',
            'same_password_error'	=> '※Password and confirmed password has to be the same 入力されたパスワードが一致しません。',
            'password_regex'		=> '※Please enter only digits or symbols for password',
            'password_min_error'	=> '※Password has to be more than or equal 8 characters',
            'password_max_error'	=> '※Password has to be less than or equal 64 characters',
            'required_branch_name'	=> '※Please enter value for branch name field.',
            'branch_name_min_error'	=> '※Branch name has to be less than or equal 255 characters.',
            'delete_parent_process_error' => 'It can not be deleted ( Parent had some Students)',
            'register_student_error'=> 'Register Student failed error(School Id=%s, Student Name=%s)',
            'get_file_csv_error'	=> 'Can not get file CSV',
            'school_arr_error'		=>'School id:%s',
            'school_name_arr_error'	=>',School Name:%s',
            'record_arr_error'		=>',Record:%s of %dTh',
            'student_arr_error'		=>'Error:%s of %dTh No.',
            'student_csv_upload_error'	=> 'Student CSV Upload Error(%s): ',
            'invalid_url_error'		=>'Invalid URL.',
            'invoice_not_initialized_error'=>'Invoice is not initialized',
            'template_file_error'	=>'Template file is not exist',
            'process_invoice_error_message' => 'Cannot Process due to an error',
            'send_mail_fail'		=>'Send Mail Fail',
            'invalid_request'		=>'Invalid Request',
            'request_form_deleted'	=>'Request Form is Deleted',
            'file_size_error_define_less_than'=>'File Size Error (Less Than Defined)',
            'file_size_error_data_record_size'=>'File Size Error (Size Record Data)',
            'header_record_error'	=>'Header Record Error',
            'data_record_error'		=>'Data Record Error',
            'trailer_record_error'	=>'Trailer Record Error',
            'end_record_error'		=>'End Record Error',
            'unknown_record_error'	=>'Unknown Record Error',
            'file_is_processed'		=>'File is Processed',
            'file_is_not_processed'	=>'File is not to Processed',
            'data_other_school_error'=>'Data other than School',
            'trailer_record_number_mismatch'=>'Trailer Record Data is Mismatch',
            'withdrawal_amount_difference'=>'Difference Withdrawal Amount:',
            'target_data_not_exist'	=>'There is no Target Data:',
            'target_upload_file_not_exist'=>'There is no Target Upload Files',
            'file_open_error'		=>'File Opend Error',
            'data_writing_error'	=>'Data Writing_Error',
        )
    );

    public static $form_keys = array(
        '2' => array(
            'person_in_charge_mail' => '担当者のメールアドレス',
            'login_id' 				=> '管理者のメールアドレス',
            'manage_flag' 			=> '種類',
            'language'				=> '利用言語',
            'country_code'			=> '国',
            'male'					=> '男性',
            'female'				=> '女性',
            'bank_credit_unio'		=> '銀行・信用金庫',
            'jp_post_bank'			=> 'ゆうちょ銀行',
            'send_mail'				=> 'メール送信：'

        ),
        '1' => array(
            'person_in_charge_mail' => 'Person in charge email',
            'login_id' 				=> 'Admin email',
            'manage_flag' 			=> 'Branch type',
            'language'				=> 'Language',
            'country_code'			=> 'Country',
            'male'					=> 'Male',
            'female'				=> 'Female',
            'bank_credit_unio'		=> 'Bank-Credit',
            'jp_post_bank'			=> 'Japan Post Bank',
            'send_mail'				=> 'Send Mail：'
        )
    );

    public static $adjust_type = array(
        '2' => array(
            'bonus'	=> '割増',
            'discount' => '割引'

        ),
        '1' => array(
            'bonus'	=> 'Bonus',
            'discount' => 'Discount'
        )
    );

    public static $app_texts = array(
        '2' => array(
            'app_name'		=> 'らくらく請求'
        ),
        '1' => array(
            'app_name'		=> 'Axis Manage System'
        )
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

    public static $logger = array(
        '2' => array(
            'warn_input'			=> 'あなた入っちゃだめです。',
            'warn_deregister'		=> 'あなた、たぶん、登録抹消されてます。',
            'warn_shool_exsit'		=> 'そんな塾はありません。',
            'warn_send_mail'		=> 'メール送信失敗した模様...',
            'temporary_folder_created'=>'一時フォルダ：%sを作成しました。'
        ),
        '1' => array(
            'warn_input'			=> 'It is useless when you input',
            'warn_deregister'		=> 'Perhaps, have been deregistered',
            'warn_shool_exsit'		=> 'There is no school like that',
            'warn_send_mail'		=> 'Sending email pattern fail...',
            'temporary_folder_created'=>'Temporary Folder： %s was Created'
        )

    );

    /*
    public  static $states = array(
        '1' => array(
            '1' => '受講中',
            '2' => '休会中',
            '9' => '退会'
            ),
        '2' => array(
            '1' => 'In teaching',
            '2' => 'Closing',
            '9' => 'Withdraw'
            )
    );
    */
    public  static $states = array(

        '1' => array(
            '1' => 'In teaching',
            '9' => 'Withdraw'
        ),
        '2' => array(
            '1' => '契約中',
            '9' => '契約終了'
        ),
    );

    public static $subject_mail = array(
        '2' => array(
            'notice_confirm_invoice' => 'ご請求額確定のお知らせ',
            'implement_notice' => '実施のお知らせ',
            'holding_notice' => '開催のお知らせ'
        ),
        '1' => array(
            'notice_confirm_invoice' => 'Notice of Confirm Invoice',
            'implement_notice' => 'Implement Notice',
            'holding_notice' => 'Holding Notice'
        )
    );

    public static $dispTypes = array(
        '2' => array(
            'student_number' 		=> '生徒数',
            'amount' 				=> '金額',
            'new_student_number' 	=> '新規生徒数',
            'retreat_student_number'=>'退生徒数',
            'member_number'			=>'会員数',
            'new_member_number'		=>'新規会員数',
            'withdraw_member'		=>'退会員数'
        ),
        '1' => array(
            'student_number' 		=> 'Student No.',
            'amount' 				=> 'Amount',
            'new_student_number' 	=> 'New Student',
            'retreat_student_number'=>'Retreat Student No.',
            'member_number'			=>'Membership',
            'new_member_number'		=>'New Membership',
            'withdraw_member'		=>'Withdraw Member'
        )
    );

    public static $dispKinds = array(
        '2' => array(
            'class' => 'プラン',
            'event' => 'イベント',
            'program' => 'プログラム',
            'degeiko' => '出稽古'
        ),
        '1' => array(
            'class' => 'Class',
            'event' => 'Event',
            'program' => 'Program',
            'degeiko' => 'degeiko'
        )
    );

    public static $invoice_message = array(
        '2' => array(
            'invoice_cannot_created' 			=> '未作成の請求書はありませんでした。',
            'invoice_item_created' 				=> '%s件の請求書を作成しました。',
            'invoice_created_message'			=>'請求書を作成しました。',
            'already_payment_cannot_edit_message'=>'すでに入金済みのため、編集できません。',
            'select_invoice_editing'			=>'編集中の請求を選択してください。',
            'invoice_eddited_message'			=>'請求書を編集しました。',
            'invoice_confirmed_message'			=>'請求書を確定しました。',
            'already_commit_cannot_delete_message'=>'すでに確定済みのため、削除できません。',
            'already_billing_cannot_delete_message'=>'すでに請求済みのため、削除できません。',
            'invoice_deleted_message'			=>'請求書を削除しました。',
            'already_payment_cannot_disable'	=>'すでに入金済みのため、無効にできません。',
            'invoice_disabled_message'			=>'請求書を無効にしました。'	,
            'cannot_payment_process_message'	=>'編集中のため、入金処理できません。',
            'cannot_email_notify_message'		=>'編集中のため、メール通知できません。'	,
            'invoice_info_cannot_obtain'		=>'請求書情報が取得できませんでした。',
            'email_notify_to_invoice'			=>'請求書をメール通知しました。',
            'invoice_item_edited'				=>'件の請求書を編集しました。',
            'cancel_account_transfer'			=>'口座振替依頼書を取消しました。',
            'select_invoice_message'			=>'請求書を選択してください。',
            'request_form_created'				=>'依頼書を作成しました。',
            'file_already_exist'				=>'口座振替依頼書が存在します。変更する場合は、存在する依頼書を取消してください。',
            'invoice_request_time_over'			=>'依頼書の受付期限を過ぎています。',
        ),
        '1' => array(
            'invoice_cannot_created' 			=> 'There is no Created Invoice',
            'invoice_item_created' 				=> '%s Items Invoice Created',
            'invoice_created_message'			=>'Invoice is Created',
            'already_payment_cannot_edit_message'=>'Payment Already, It Cannot be Edited',
            'select_invoice_editing'			=>'Please Select Invoice to Edit',
            'invoice_eddited_message'			=>'Invoice is Edited',
            'invoice_confirmed_message'			=>'Invoice is Confirmed',
            'already_commit_cannot_delete_message'=>'Commit Already, It cannot be Deleted',
            'already_billing_cannot_delete_message'=>'Billing Already, It Cannot be Deleted',
            'invoice_deleted_message'			=>'Invoice is Deleted',
            'already_payment_cannot_disable'	=>'Payment Already, It Cannot be Disabled',
            'invoice_disabled_message'			=>'Invoice is disabled'	,
            'cannot_payment_process_message'	=>'For Editing, Cannot Payment',
            'cannot_email_notify_message'		=>'For Editing, Cannot Email Notification'	,
            'invoice_info_cannot_obtain'		=>'Invoice Information Cannot be Obtained',
            'email_notify_to_invoice'			=>'Email Notification successful to Invoice',
            'invoice_item_edited'				=>'Item Invoice Edited',
            'cancel_account_transfer'			=>'Account Transfer Request Form is Canceled',
            'select_invoice_message'			=>'Please Select Invoice.',
            'request_form_created'				=>'Request Form is Created',
            'file_already_exist'				=>'File Already Exist.',
            'invoice_request_time_over'			=>'Past the deadline time.',
        )
    );

    public static $gengo= array(
        '2' => array(
            'march' 	=> '平成',
            'showa' 	=> '昭和',
            'taisho' 	=> '大正',
            'meiji'		=>'明治',
            'first_year'=>'元年'
        ),
        '1' => array(
            'march' 	=> 'March',
            'showa' 	=> 'Showa',
            'taisho' 	=> 'Taisho',
            'meiji'		=>'Meiji',
            'first_year'=>' First Year'
        )
    );

    public static $gender = array(
        '2' => array(
            '1'		=> '男性',
            '2'		=> '女性',
            '3'     => 'その他'
        ),
        '1' => array(
            '1'		=> 'Male',
            '2'		=> 'Female',
            '3'     => 'Unknown'
        )
    );

    public static $payment_result = array(
        '2' => array(
            '1' 	=> '料金未納',
            '2' 	=> '支払済み',
            '3' 	=> '支払期限前',
            '4'		=> '履歴なし',
        ),
        '1' => array(
            '1' 	=> 'Non-payment',
            '2' 	=> 'Paid',
            '3' 	=> 'Payment before',
            '4'		=> 'No history',
        )
    );

    public static $lang_setting = array(
        '1' => 'en',
        '2' => 'jp'
    );

    public static $school_role = array(
        '1' => 'parent',
        '2' => 'teacher',
        '3' => 'student',
        '4' => 'staff',
    );

    // field in school_menu
    public static $member_type = array(
        '1' => 'DANTAI',
        '2' => 'STAFF',
        '3' => 'COACH',
        '4' => 'PARENT',
        '5' => 'STUDENT',
        '6' => 'PLAN'
    );

    public static $bulletin_target = [ 'staff_title' , 'teacher_title' , 'student_title', 'parent_title' ]; // TODO get msg from language resource files

    public static $MEMBER_STATUS_UNDER_CONTRACT = 1; //契約中
    public static $MEMBER_STATUS_END_CONTRACT = 9; //契約終了
    public static $MEMBER_STATUS_TMP = 2; //仮
    public static $MEMBER_CATEGORY_PERSONAL = 1; //個人
    public static $MEMBER_CATEGORY_CORP = 2; //法人
    public static $MEMBER_DEFAULT_AGE = 25; //会員のデフォルト年齢
    public static $MEMBER_BIRTH_DAY_FROM_YEAR_RANGE = 100; //100年まえ対応できる
    //支払方法
    public static $INVOICE_CASH_PAYMENT = 0; //現金
    public static $INVOICE_TRANSFER_PAYMENT = 1; //振り込み
    public static $INVOICE_BANK_PAYMENT = 2; //口座振替
    //支払通知方法
    public static $NOTICE_BY_POST = 0; //郵送
    public static $NOTICE_BY_MAIL = 1; //メール
    public static $NOTICE_NOT_SEND_MAIL = 2; //送信しない
    //金融機関種別
    public static $FINANCIAL_TYPE_BANK = 1; //銀行・信用金庫
    public static $FINANCIAL_TYPE_POST = 2; //郵便局
    //アカウント権限
    public static $LOGIN_AUTH_SYSTEM = 1; //システム
    public static $LOGIN_AUTH_SCHOOL = 2; //塾
    public static $LOGIN_AUTH_STAFF = 3; //スタッフ
    public static $LOGIN_AUTH_TEACHER = 5; //講師
    public static $LOGIN_AUTH_STUDENT = 9; //会員
    public static $LOGIN_AUTH_PARENT = 10; //保護者
    //割引
    public static $DISCOUNT_CLASS = 1; //割引・プラン
    public static $DISCOUNT_EVENT = 2; //割引・イベント
    public static $DISCOUNT_PARENT = 3; //割引・保護者
    public static $DISCOUNT_PROGRAM = 4; //割引・プログラム
    public static $DISCOUNT_STUDENT = 5; //割引・会員
    //History log
    public static $HISTORY_LOG_STUDENT = 1; //会員情報のログタイプ
    public static $HISTORY_LOG_PARENT = 2; //請求先のログタイプ
    //Additional category type
    public static $ADDITIONAL_CATEGORY_STUDENT = 1; //会員情報
    public static $ADDITIONAL_CATEGORY_CLASS = 2; //プラン
    public static $ADDITIONAL_CATEGORY_COURSE = 3; //イベント
    public static $ADDITIONAL_CATEGORY_PROGRAM = 4; //プログラム
    public static $ADDITIONAL_CATEGORY_PARENT = 5; //請求先
    public static $ADDITIONAL_CATEGORY_TEACHER = 6; //先生

    public static $student_default_col = array(
        '1' => 'student_name',
        '2' => 'zip_code',
        '3' => '_pref_id',
        '4' => '_city_id',
        '5' => 'student_address',
        '6' => 'student_building',
    );
    public static $parent_default_col = array(
        '1' => 'parent_name',
        '2' => 'zip_code',
        '3' => 'pref_id',
        '4' => 'city_id',
//        '5' => 'address',
        '6' => 'building',
    );

    // 利用回数の単位
    public static $attend_unit = array(
        '2' => array(
            '0'=>'指定なし',
            '1'=>'月',
            '2'=>'週'
        ),
        '1' => array(
            '0'=>'Unset',
            '1'=>'Month',
            '2'=>'Week'
        )
    );

    public static $payment_times_list = array (
        '2' => array(
            '99' => '毎月',
            '1' => ' 1回',
            '2' => ' 2回',
            '3' => ' 3回',
            '4' => ' 4回',
            '5' => ' 5回',
            '6' => ' 6回',
            '7' => ' 7回',
            '8' => ' 8回',
            '9' => ' 9回',
            '10' => '10回',
            '11' => '11回',
            '12' => '12回'
        ),
        '1' => array(
            '99' => 'Every month',
            '1' => 'once',
            '2' => 'twice',
            '3' => 'three times',
            '4' => 'four times',
            '5' => 'five times',
            '6' => 'six times',
            '7' => 'seven times',
            '8' => 'eight times',
            '9' => 'nine times',
            '10' => 'ten times ',
            '11' => 'eleven times',
            '12' => 'twelve time'
        )
    );
    public static $ENTRY_TYPE = array (
        '1' => 'interview',
        '2' => 'event',
        '3' => 'program'
    );
    public static $MAIL_MESSAGE_TYPE = array (
        '1' => 'invoice',
        '2' => 'interview_application',
        '3' => 'event',
        '4' => 'interview_schedule',
        '5' => 'program',
        '6' => 'broadcastmail',
        '7' => 'deposit'
    );
    public static $MAIL_TEMPLATE_TYPE = array (
        '2' => array(
            '1' => 'イベント',
            '2' => 'プログラム',
            '3' => 'お知らせ送信',
            '4' => '請求書' 
        ),
        '1' => array(
            '1' => 'Event',
            '2' => 'Program',
            '3' => 'Broadcastmail',
            '4' => 'Invoice' 
        )
    );
    public static $PAYMENT_UNIT_TEXT = array (
        '2' => array(
            '1' => '一人当たり',
            '2' => '全員で',
        ),
        '1' => array(
            '1' => 'Person',
            '2' => 'Group',
        )
    );
    // Toran add for getting list label output
    public static $LIST_LABEL = array(
        'student' => array(
            '0' => 'zip_code',
            '1' => '_pref_id',
            '2' => '_city_id',
            '3' => 'student_address',
            '4' => 'student_building',
            '5' => 'student_no',
            '6' => 'student_name',
            '7' => 'm_student_type_id'
        ),
        'parent' => array(
            '0' => 'zip_code',
            '1' => 'pref_id',
            '2' => 'city_id',
            '3' => 'address',
            '4' => 'building',
            '5' => 'parent_name'
        )
    );

    public static $edit_auth_datas = array(
        '0' => 'input',
        '1' => 'entry',
        '2' => 'entry2',
        '3' => 'create',
        '4' => 'entryMulti',
        '5' => 'courseentry',
        '6' => 'coursecomplete',
        '7' => 'edit',
        '8' => 'confirm',
        '9' => 'update',
        '10' => 'delete',
        '11' => 'uploadinput',
        '12' => 'complete',
        '13' => 'completeSend',
        '14' => 'save',
        '15' => 'store',
        '16' => 'inputindiv',
        '17' => 'adjustnameinput',
        '18' => 'completeIndiv',
        '19' => 'accountedit',
        '20' => 'accountcomplete',
        '21' => 'accountdelete',
        '22' => 'singleEditComplete',
        '23' => 'completeEditInvoice',
        '24' => 'deleteInvoice',
        '25' => 'studentProc',
        '26' => 'studentEdit',
        '27' => 'studentStore',
        '28' => 'destroy',
        '29' => 'mailsend',
        '30' => 'generate',
        '31' => 'parentselect',
        '32' => 'ricohTransProc',
        '33' => 'ricohTransDownload',
        '34' => 'ricohTransDownloadComplete',
        '35' => 'ricohTransUpload',
        '36' => 'ricohTransUploadComplete',
        '37' => 'ricohConvProc',
        '38' => 'ricohConvDownload',
        '39' => 'ricohConvDownloadComplete',
        '40' => 'ricohConvUpload',
        '41' => 'ricohConvUploadComplete',
        '42' => 'ricohPostProc',
        '43' => 'ricohPostDownload',
        '44' => 'ricohPostDownloadComplete',
        '45' => 'additionalcategory',
        '46' => 'additionalCategoryComplete',
        '47' => 'adjustnameComplete',
        '48' => 'accountlist',
    );
    public static $STUDENT_CATEGORY = array(
            '2' => array(
                    '1' => '個人',
                    '2' => '法人',
            ),
            '1' => array(
                    '1' => 'Individual',
                    '2' => 'Corporation',
            )
    );
    
    public static $industryList = array(
    		1  => '水産・農林業',
    		2  => '鉱業',
    		3  => '建設業',
    		4  => '製造業',
    		5  => '電気・ガス業',
    		6  => '運輸・情報通信業',
    		7  => '商業',
    		8  => '金融・保険業',
    		9  => '不動産業',
    		10 => 'サービス業',
    		11 => 'その他',
    );
    
    public static $employeesList = array(
    		1 => '100名未満',
    		2 => '100名以上300名未満',
    		3 => '300名以上1,000名未満',
    		4 => '1,000名以上',
    );

    // プログラムの種別
    public static $applycationType = array (
        0 => '会員管理',
        1 => '組合管理',
        2 => '顧客管理'
    );
}

