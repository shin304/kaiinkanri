<?php

namespace App\Common;

use App\Model\InvoiceHeaderTable;
use Dompdf\Exception;
use Illuminate\Database\Eloquent\Model;
use App\Model\MailMessageTable;
use App\Model\BroadcastMailTable;
use App\Model\ProgramTable;
use App\Model\CourseTable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Facades\Storage;
use Log;

// 送信元メールアドレス
define("MAIL_FROM", "icteltest01@asto-system.net" );
define("MAIL_ADMIN", "info@rakuraku-kanri.com" );

trait Email {

    private static $EVENT       = array(  'type' => 3,
                                    'template'  => '_mail.event_mail_template',
                                    'mail_url'  => '/portal/event' );
    private static $PROGRAM     = array(  'type' => 5,
                                    'template'  => '_mail.program_mail_template',
                                    'mail_url'  => '/portal/program' );
    private static $BROADCAST   = array(  'type' => 6,
                                    'template'  => '_mail.bcmail_notification',
                                    'mail_url'  => '' );
    private static $RECEIPT     = array(  'type' => 1,
                                    'template'  => '_mail.invoice_mail_notification',
                                    'mail_url'  => '/portal/invoice' );
    private static $DEPOSIT     = array(  'type' => 7,
                                          'template'  => '_mail.deposit_reminder_template',
                                          'mail_url'  => '' );
    private static $PASSWORD     = array(
                                          'template'  => '_mail.password_reminder_template',
                                          'mail_url'  => '',
                                          'title'  => 'らくらく管理　パスワードの確認のメール');
    private static $SUCCESS     = array(
                                          'template'  => '_mail.change_success_password_template',
                                          'mail_url'  => '',
                                          'title'  => 'らくらく管理　パスワード変更完了メール');
    // デモスクールを使う
    private static $EMAIL_FOR_USER     = array(
                                        'template'  => '_mail.send_confirm_email_template',
                                        'mail_url'  => '',
                                        'title'  => 'らくらく管理　デモ版利用登録申請確認メール' );
    // デモスクールを使う
    private static $EMAIL_FOR_ADMIN     = array(
                                        'template'  => '_mail.send_email_admin_template',
                                        'mail_url'  => '',
                                        'title'  => 'らくらく管理　アドミンの確認のメール' );
    // デモスクールを使う
    private static $SEND_CONFIRM_EMAIL_FOR_USER     = array(
                                        'template'  => '_mail.send_confirm_email_user_template',
                                        'mail_url'  => '',
                                        'title'  => 'らくらく管理　デモ版登録完了メール' );
    private static $SEND_EMAIL_DECRYPT_FILE_FOR_USER     = array(
                                        'template'  => '_mail.send_email_decrypt_file_for_user_template',
                                        'mail_url'  => '',
                                        'title'  => '会員情報CSV出力のパスワードを送付いたします' );
    private static $SEND_LIST_EMAIL_USER     = array(
                                        'template'  => '_mail.send_list_email_user_template',
                                        'mail_url'  => '' );

    /**
     * スクールのＩＤ ように、メール送信 スケジュール
     * @param $pschool_id: スクールのＩＤ
     */
    public function sendMailBySchedule($pschool_id)
    {
        // get all mail_message of school by schedule
        $mail_messages = MailMessageTable::getInstance()->getListMailOnSchedule($pschool_id);
        foreach ($mail_messages as $key => $mail_message) {
            switch ( $mail_message['type'] ) {
                // case : イベント
                case self::$EVENT['type']:
                    $this->sendMailEventType($mail_message['id'], true);
                    break;

                // case: プログラム
                case self::$PROGRAM['type']:
                    $this->sendMailProgramType($mail_message['id'], true);
                    break;

                // case: お知らせ
                case self::$BROADCAST['type']:
                    $this->sendMailBroadcastType($mail_message['id'], true);
                    break;

                // case: 請求書
                case self::$RECEIPT['type']:
                    $this->sendMailReceiptType($mail_message['id'], true);
                    break;

                // case: 請求書
                case self::$DEPOSIT['type']:
                    $this->sendMailDepositReminderBatch($mail_message['id'], true);
                    break;
                default: break;
            }
        }
    }

    /**
     * イベント のメール送信 : send mail of event type
     * @param $mail_message_id: mail_message table's id
     * @param $is_batch: run batch process
     */
    public function sendMailEventType($mail_message_id, $is_batch = false)
    {
        $mailMessageTable = MailMessageTable::getInstance();
        $mail_info = $mailMessageTable->getMailInfoToSend($mail_message_id);
        $event = CourseTable::getInstance()->load( $mail_info['relative_ID'] );
        if ($event) {
            /* set mail info in view */
            // set subject of mail
            $mail_info['subject'] = ( isset($event['mail_subject']) ) ? $event['mail_subject']: 'イベントのお知らせ';
//            $subject = mb_encode_mimeheader($mail_info['subject']);
            $subject = $mail_info['subject'];

            // set body of mail
            $mail_info['event_description'] = $event['course_description'];
            // set footer of mail
            $mail_info['mail_footer']       = $event['mail_footer'];
            // set mail info
            $mail_info['event_title']       = $event['course_title'];

            // create URL
            $hash_code = "?message_key=". $mail_info['message_key'];
            $mail_info['url'] = $this->createActivateUrl( self::$EVENT['mail_url'], $hash_code );
            $mail_info['contact'] = $mail_info['school_mailaddress'];
            /* set mail info in view */

            // get to_mails : only send to parent mail
            $to_mails = array();
            if ( $mail_info['parent_mailaddress1'] ) {
                $to_mails[] = $mail_info['parent_mailaddress1'];
            }
            if ( $mail_info['parent_mailaddress2'] ) {
                $to_mails[] = $mail_info['parent_mailaddress2'];
            }

            if ( $to_mails ) {
                // send mail
                $is_sent = $this->send_email( self::$EVENT['template'] , $mail_info, $to_mails, $subject, $mail_info['school_mailaddress']);
                if ( $is_sent ) {
                    // update mail_message row
                    $mail_message = array(  'id'            => $mail_info['id'],
                                            'send_date'     => date("Y-m-d H:i:s"),
                                            'total_send'    => $mail_info['total_send'] + 1);
                    $saved_id = $mailMessageTable->save($mail_message, $is_batch);
                    $this->logSuccess( " Send mail message (ID:{$mail_info['relative_ID']}) : success (saved_id:{$saved_id})" );
                } else {
                    $this->logError( " Send mail message (ID:{$mail_info['relative_ID']}) : fail " );
                }
            }
        } else {
            $error = "School (ID:{$mail_info['pschool_id']}'s event (ID:{$mail_info['relative_ID']}) is not existed. No mail is sent!)";
            $this->logError( $error );
        }
    }

    /**
     * プログラム のメール送信 : send mail of program type
     * @param $mail_message_id: mail_message table's id
     * @param $is_batch: run batch process
     */
    public function sendMailProgramType($mail_message_id, $is_batch = false)
    {
        $mailMessageTable = MailMessageTable::getInstance();
        $mail_info = $mailMessageTable->getMailInfoToSend($mail_message_id);
        $program = ProgramTable::getInstance()->load( $mail_info['relative_ID'] );
        if ($program) {
            /* set mail info in view */
            // set subject of mail
            $mail_info['subject'] = ( isset($program['mail_subject']) ) ? $program['mail_subject']: 'プログラムのお知らせ';
//            $subject = mb_encode_mimeheader($mail_info['subject']);
            $subject = $mail_info['subject'];

            // set body of mail
            $mail_info['description']   = $program['description'];
            // set footer of mail
            $mail_info['mail_footer']   = $program['mail_footer'];
            // set mail info
            $mail_info['program_title'] = $program['program_name'];

            // create URL
            $hash_code = "?message_key=". $mail_info['message_key'];
            $mail_info['url'] = $this->createActivateUrl( self::$PROGRAM['mail_url'], $hash_code );
            $mail_info['contact'] = $mail_info['school_mailaddress'];
            /* set mail info in view */

            // get to_mails : only send to parent mail
            $to_mails = array();
            if ( $mail_info['parent_mailaddress1'] ) {
                $to_mails[] = $mail_info['parent_mailaddress1'];
            }
            if ( $mail_info['parent_mailaddress2'] ) {
                $to_mails[] = $mail_info['parent_mailaddress2'];
            }

            if ( $to_mails ) {
                // send mail
                $is_sent = $this->send_email( self::$PROGRAM['template'] , $mail_info, $to_mails, $subject, $mail_info['school_mailaddress']);
                if ( $is_sent ) {
                    // update mail_message row
                    $mail_message = array(  'id'            => $mail_info['id'],
                                            'send_date'     => date("Y-m-d H:i:s"),
                                            'total_send'    => $mail_info['total_send'] + 1);
                    $saved_id = $mailMessageTable->save($mail_message, $is_batch);
                    $this->logSuccess( " Send mail message (ID:{$mail_info['relative_ID']}) : success (saved_id:{$saved_id})" );
                } else {
                    $this->logError( " Send mail message (ID:{$mail_info['relative_ID']}) : fail " );
                }
            }
        } else {
            $error = "School (ID:{$mail_info['pschool_id']}'s program (ID:{$mail_info['relative_ID']}) is not existed. No mail is sent!)";
            $this->logError( $error );
        }
    }

    /**
     * お知らせ のメール送信
     * @param $mail_message_id: mail_message table's id
     * @param $is_batch: run batch process
     */
    public function sendMailBroadcastType($mail_message_id, $is_batch = false)
    {
        $mailMessageTable = MailMessageTable::getInstance();
        $broadcastMailTable = BroadcastMailTable::getInstance();
        $mail_info = $mailMessageTable->load($mail_message_id);
        $broadcast_data = $broadcastMailTable->getBroadCastInfo($mail_info['relative_ID'],$mail_info['target'],$mail_info['target_id']);
        if($broadcast_data){
            // send mail
            $is_sent = $this->send_email( self::$BROADCAST['template'] ,  $broadcast_data, array('0'=>$broadcast_data['login_id']), $broadcast_data['title'], $broadcast_data['school_mailaddress']);
            if ( $is_sent ) {
                $mail_message = array(  'id'            => $mail_info['id'],
                                        'send_date'     => date("Y-m-d H:i:s"),
                                        'total_send'    => $mail_info['total_send'] + 1);
                $saved_id = $mailMessageTable->save($mail_message, $is_batch);
                //update send flag
                $broadcast_mail = $broadcastMailTable->load($mail_info['relative_ID']);
                $broadcast_mail['send_flag']=1;
                $broadcastMailTable->save($broadcast_mail);
                //end update
                $this->logSuccess( " Send mail message (ID:{$mail_info['relative_ID']}) : success (saved_id:{$saved_id})" );
            } else {
                $this->logError( " Send mail message (ID:{$mail_info['relative_ID']}) : fail " );
            }
        }
    }

    public function sendMailPasswordReminder($data, $name, $code, $id, $to_email, $is_batch = false)
    {
        try {
                $data['name'] = $name;
                $data['code'] =  $code;
                $data['url'] = 'https://'.$_SERVER['HTTP_HOST'].'/confirmCode'.'?id='.$id;

                $now = getdate();
                $d=$now ['hours']+1;
                $year = $now ['year'];
                $data['datetime'] = $now ['mon'].'月'.$now ['yday'].'日'.' '.$d.'時'.$now ['minutes'].'分';
                $is_sent = $this->send_email( self::$PASSWORD['template'] ,  $data, [$to_email], self::$PASSWORD['title'], MAIL_FROM);

                if ($is_sent) {
                    $this->logSuccess("Send mail message success");
                } else {
                    throw new \Exception( "Send mail message fail");
                }
                return true;


        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            // dd($e->getMessage());
            return false;
        }
    }

    public function sendMailPasswordSuccess($data, $name, $to_email, $is_batch = false)
    {
        try {
                $data['name'] = $name;
                $data['url'] = 'https://'.$_SERVER['HTTP_HOST'].'/school';
                $is_sent = $this->send_email(self::$SUCCESS['template'] ,  $data, [$to_email], self::$SUCCESS['title'], MAIL_FROM);

                if ($is_sent) {
                    $this->logSuccess("Send mail message success");
                } else {
                    throw new \Exception("Send mail message fail");
                }
                return true;


        } catch (\Exception $e) {
            $this->logError($e->getMessage());
             // dd($e->getMessage());
            return false;
        }
    }

    /**
     * Gửi mail cho user để xác nhận mail xem email user nhập có đúng không
     * @param $data array đưa dữ liệu ra ngoài template
     * @param $register_code varchar đưa code vào link để check email
     * @param $to_email varchar email người nhận
     * @param bool $is_batch
     * @return bool
     */
    public function sendConfirmEmail($data, $register_code, $to_email, $is_batch = false)
    {
        try {
            $data['url'] = 'https://'.$_SERVER['HTTP_HOST'].'/home/mailConfirmed'.'?code='.$register_code;

            $now = getdate();
            $d = $now['hours']+1;
            $year = $now['year'];
            $data['datetime'] = $now['mon'].'月'.$now['yday'].'日'.' '.$d.'時'.$now['minutes'].'分';
            $is_sent = $this->send_email(self::$EMAIL_FOR_USER['template'] ,  $data, [$to_email], self::$EMAIL_FOR_USER['title'], MAIL_FROM);

            if ($is_sent) {
                $this->logSuccess("Send mail message success");
            } else {
                throw new \Exception("Send mail message fail");
            }
            return true;


        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            // dd($e->getMessage());
            return false;
        }
    }

    /**
     * Gửi email cho admin để thông báo cho user mới vừa đăng ký
     * @param $data array đưa dữ liệu ra ngoài template
     * @param $email_user varchar email người nhận
     * @param $to_email varchar email admin
     * @param bool $is_batch
     * @return bool
     */
    public function sendEmailForAdmin($data, $email_user, $to_email, $is_batch = false)
    {
        try {
            $data['email'] = $email_user;
            $is_sent = $this->send_email(self::$EMAIL_FOR_ADMIN['template'] ,  $data, $to_email, self::$EMAIL_FOR_ADMIN['title'], MAIL_FROM);

            if ($is_sent) {
                $this->logSuccess("Send mail message success");
            } else {
                throw new \Exception("Send mail message fail");
            }
            return true;


        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            // dd($e->getMessage());
            return false;
        }
    }

    /**
     * Gửi emaail thông báo thành công cho user sau khi đã xác nhận
     * @param $data array đưa dữ liệu ra ngoài template
     * @param $to_email varchar email user
     * @param bool $is_batch
     * @return bool
     */
    public function sendEmailConfirmForUser($data, $to_email, $is_batch = false)
    {
        try {
            $data['email'] = $to_email;
            // $data['url'] = 'https://'.$_SERVER['HTTP_HOST'].'/school';
            $data['server'] = $_SERVER['HTTP_HOST'];
            $is_sent = $this->send_email(self::$SEND_CONFIRM_EMAIL_FOR_USER['template'] ,  $data, [$to_email], self::$SEND_CONFIRM_EMAIL_FOR_USER['title'], MAIL_FROM);

            if ($is_sent) {
                $this->logSuccess("Send mail message success");
            } else {
                throw new \Exception("Send mail message fail");
            }
            return true;


        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            // dd($e->getMessage());
            return false;
        }
    }

    public static function sendEmailDecryptFileForUser($data, $name, $key, $fileExportName, $to_email, $is_batch = false)
    {
        $data['name'] = $name;
        $data['key'] =  $key;
        $data['fileExportName'] = $fileExportName;
        $data['mail_user'] = $to_email;
        $data['mail_admin'] = MAIL_ADMIN;
        return $is_sent = self::send_email_edit( self::$SEND_EMAIL_DECRYPT_FILE_FOR_USER['template'] ,  $data, [$to_email], self::$SEND_EMAIL_DECRYPT_FILE_FOR_USER['title'], MAIL_FROM);
    }

    public static function sendListEmailUser($interface_type, $data, $to_email, $processName, $processID, $is_batch = false) {

        $data['mail_user'] = $to_email;
        $data['mail_admin'] = MAIL_ADMIN;
        $data['process_name'] = $processName;
        $data['process_id'] = $processID;
        $data['interface_type'] = $interface_type;
        $mail_sender= session('school.login')['mailaddress'];
        $subject = null;
        if ($processID == 1) {
            $subject = $data['title'];
        } elseif ($processID == 2) {
            $subject = $data['subject'];
        } else {
            $subject = $data['subject'];
        }

        // create URL
        if ($interface_type == 1) {
            $hash_code = "?message_key=". $data['message_key'];
            $data['url'] = self::createActivateUrl_Coop( self::$EVENT['mail_url'], $hash_code );
        } elseif ($interface_type == 2) {
            $hash_code = "?message_key=". $data['message_key'];
            $data['url'] = self::createActivateUrl_Coop( self::$PROGRAM['mail_url'], $hash_code );
        }
        $data['contact'] = $mail_sender;

        return $is_sent = self::send_email_edit( self::$SEND_LIST_EMAIL_USER['template'] ,  $data, [$to_email], $subject, $mail_sender);
    }

    public function sendMailDepositReminderType($invoices, $is_batch = false)
    {
        try {
            MailMessageTable::getInstance()->beginTransaction();
            foreach ($invoices as $invoice) {
                $data = [
                        'type'        => self::$DEPOSIT['type'],
                        'relative_ID' => $invoice['id'],
                        'pschool_id'  => session()->get('school.login.id'),
                        'target'      => 1, // Parent
                        'target_id'   => $invoice['parent_id'],
                        'send_date'   => date("Y-m-d H:i:s"),
                        'total_send'  => 1
                ];
                // Add name var for mail template use
                $invoice['name'] = $invoice['parent_name'];
                $is_sent = $this->send_email(self::$DEPOSIT['template'], $invoice, [$invoice['parent_mailaddress1']], $invoice['title'], $invoice['school_mailaddress'] );
                if ($is_sent) {
                    $id = MailMessageTable::getInstance()->save($data);
                    if (!$id) {
                        throw new \Exception();
                    }

                    // Update send mail date in invoice header
                    InvoiceHeaderTable::getInstance()->save(['id' => $invoice['id'], 'deposit_reminder_date' => date("Y-m-d H:i:s")]);
                    $this->logSuccess("Send mail message (ID:{$invoice['parent_id']}) : success (saved_id:{$id})");
                } else {
                    throw new \Exception( "Send mail message (ID:{$invoice['parent_id']}) : fail ");
                }
            }
            MailMessageTable::getInstance()->commit();
            return true;
        } catch (\Exception $e) {
            MailMessageTable::getInstance()->rollBack();
            $this->logError($e->getMessage());
            return false;
        }
    }

    /**
     * 入金処理の催促状メール
     * @param $mail_message_id: mail_message table's id
     * @param $content mail content
     * @param $is_batch: run batch process
     */
    public function sendMailDepositReminderBatch($mail_message_id, $is_batch = false)
    {
        try {
            MailMessageTable::getInstance()->beginTransaction();
            $invoice = MailMessageTable::getInstance()->getReminderMailContent($mail_message_id);

            // Add name var for mail template use
            $invoice['name'] = $invoice['parent_name'];
            $invoice['title'] = $invoice['deposit_reminder_title'];
            $invoice['content'] = $invoice['deposit_reminder_content'];
            $invoice['footer'] = $invoice['deposit_reminder_footer'];
            $is_sent = $this->send_email(self::$DEPOSIT['template'], $invoice, [$invoice['parent_mailaddress1']], $invoice['deposit_reminder_title'], $invoice['mailaddress'] );

            if ($is_sent) {
                $data = array(
                    'id' => $mail_message_id,
                    'send_date' => date('Y-m-d H:i:s'),
                    'total_send' => $invoice['total_send'] + 1
                );
                $id = MailMessageTable::getInstance()->save($data,$is_batch);

                if (!$id) {
                    throw new \Exception();
                }

                // Update send mail date in invoice header
                InvoiceHeaderTable::getInstance()->save(['id' => $invoice['id'], 'deposit_reminder_date' => date("Y-m-d H:i:s")],$is_batch);
                $this->logSuccess("Send mail message (ID:{$invoice['parent_id']}) : success (saved_id:{$id})");
            } else {
                throw new \Exception( "Send mail message (ID:{$invoice['parent_id']}) : fail ");
            }

            MailMessageTable::getInstance()->commit();
            return true;
        } catch (\Exception $e) {
            MailMessageTable::getInstance()->rollBack();
            $this->logError($e->getMessage());
            return false;
        }
    }


    /**
     * 請求書 のメール送信 : send mail of receipt type
     * @param $mail_message_id: mail_message table's id
     * @param $is_batch: run batch process
     */
    public function sendMailReceiptType($mail_message_id, $is_batch = false)
    {
        $mailMessageTable = MailMessageTable::getInstance();
        $mail_info = $mailMessageTable->getMailInfoToSend($mail_message_id);
        if(empty($mail_info["school_zipcode_1"]) || empty($mail_info["school_zipcode_2"]) ){
            $mail_info["school_zipcode_1"] = substr($mail_info["school_zipcode"],0,3);
            $mail_info["school_zipcode_2"] = substr($mail_info["school_zipcode"],3,4);
        }
        $invoice = InvoiceHeaderTable::getInstance()->load($mail_info['relative_ID']);

        if ($invoice) {
            /* set mail info in view */
            // set subject of mail
            $mail_info['subject'] = 'ご請求確定のお知らせ';
//            $subject = mb_encode_mimeheader($mail_info['subject']);
            $subject = $mail_info['subject'];
            // set mail info
            $mail_info['reply'] = $mail_info['school_mailaddress'];

            // create URL
            $hash_code = "?message_key=". $mail_info['message_key'];
            $mail_info['url'] = $this->createActivateUrl( self::$RECEIPT['mail_url'], $hash_code );
            $mail_info['contact'] = $mail_info['school_mailaddress'];
            /* set mail info in view */

            // get to_mails : only send to parent mail
            $to_mails = array();
            if ( $mail_info['parent_mailaddress1'] ) {
                $to_mails[] = $mail_info['parent_mailaddress1'];
            }
            if ( $mail_info['parent_mailaddress2'] ) {
                $to_mails[] = $mail_info['parent_mailaddress2'];
            }

            if ( $to_mails ) {
                // send mail
                $is_sent = $this->send_email( self::$RECEIPT['template'] , $mail_info, $to_mails, $subject, $mail_info['school_mailaddress']);
                if ( $is_sent ) {
                    // update mail_message row
                    $mail_message = array(  'id'            => $mail_info['id'],
                        'send_date'     => date("Y-m-d H:i:s"),
                        'total_send'    => $mail_info['total_send'] + 1);
                    $saved_id = $mailMessageTable->save($mail_message, $is_batch);
                    $this->logSuccess( " Send mail message (ID:{$mail_info['relative_ID']}) : success (saved_id:{$saved_id})" );
                } else {
                    $this->logError( " Send mail message (ID:{$mail_info['relative_ID']}) : fail " );
                }
            }
        } else {
            $error = "School (ID:{$mail_info['pschool_id']}'s Invoice header (ID:{$mail_info['relative_ID']}) is not existed. No mail is sent!)";
            $this->logError( $error );
        }
    }

    /**
     * URL作成 : create URL (event mail)
     * @param $type: mail message type (Event: "/portal/event" )
     * @param $hash_code: mail message key (ex: "?message_key={$message_key} )
     */
    protected function createActivateUrl($type, $hash_code) {
        if(!empty($_SERVER['HTTP_HOST'])){
            return 'https://' . $_SERVER['HTTP_HOST'] . $type . '/' . $hash_code;
        }else{
            return env('APP_URL').$type. '/' . $hash_code;
        }

    }

    /**
     * URL作成 : create URL (event mail)
     * @param $type: mail message type (Event: "/portal/event" )
     * @param $hash_code: mail message key (ex: "?message_key={$message_key} )
     */
    protected static function createActivateUrl_Coop($type, $hash_code) {
        if(!empty($_SERVER['HTTP_HOST'])){
            return 'https://' . $_SERVER['HTTP_HOST'] . $type . '/' . $hash_code;
        }else{
            return env('APP_URL').$type. '/' . $hash_code;
        }

    }

    /**
     * メール送信
     * @param $template: blade file to display content mail
     * @param $assign_var: array data info is showed in view
     * @param $to_arr: array mail address
     * @param $subject: subject of mail
     */
    protected function send_email($template, $assign_var, $to_arr, $subject, $sender_email) {
        /*=========================================
        $current_time = date('H:i:s');
        $fileName = "../storage/logs/" . "mail_debug.log";
        file_put_contents($fileName, $current_time . ":" . session('school.login')['mailaddress'] . "\n", FILE_APPEND);

        // var_export(session('school.login'), true)
        /*========================================*/
        if (view()->exists($template)) {

            $files = isset($assign_var['files'])? $assign_var['files']: array();

            Mail::send($template, ['data' => $assign_var], function($message) use ($to_arr, $subject, $sender_email, $files)
            {
                try{

                    foreach ($to_arr as $email) {
                        if (!$sender_email){
                            throw new \Exception("基本情報のメールアドレスを登録してください。\nPlease set the mail address of school in school's info menu. ");
                            exit;
                        }

                        $message->to($email)->subject($subject)->from($sender_email);
                        if(!empty($files)){
                            foreach ($files as $k => $v){
                                $message->attach(storage_path("/app/public/uploads/".$v['file_path']));
                            }
                        }
                    }
                }catch (\Exception $e){
                    dd("exception");
                    return false;
                }

            });
            return true;
        }
        return false;
    }

    /**
     * メール送信
     * @param $template: blade file to display content mail
     * @param $assign_var: array data info is showed in view
     * @param $to_arr: array mail address
     * @param $subject: subject of mail
     */
    protected static function send_email_edit($template, $assign_var, $to_arr, $subject, $sender_email) {

        if (view()->exists($template)) {

            $files = isset($assign_var['files'])? $assign_var['files']: array();

            Mail::send($template, ['data' => $assign_var], function($message) use ($to_arr, $subject, $sender_email, $files)
            {
                try{

                    foreach ($to_arr as $email) {
                        if (!$sender_email){
                            throw new \Exception("基本情報のメールアドレスを登録してください。\nPlease set the mail address of school in school's info menu. ");
                            exit;
                        }

                        $message->to($email)->subject($subject)->from($sender_email);
                        if(!empty($files)){
                            foreach ($files as $k => $v){
                                $message->attach(storage_path("/app/public/uploads/".$v['file_path']));
                            }
                        }
                    }
                }catch (\Exception $e){
                    dd("exception");
                    return false;
                }

            });
            return true;
        }
        return false;
    }

    /**
     * Log Error
     * @param $message: message string
     */
    protected function logError($message)
    {
//        printf ("\nERROR MESSAGE : %s\n", $message);
        Log::error($message);
    }

    /**
     * Log Success
     * @param $message: message string
     */
    protected function logSuccess($message)
    {
//        printf ("\nSUCCESS MESSAGE : %s\n", $message);
        Log::info($message);
    }
}