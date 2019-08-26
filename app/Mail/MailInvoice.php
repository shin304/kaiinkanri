<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailInvoice extends Mailable {
    
    use Queueable, SerializesModels;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($send_from, $send_subject, $parent_name,
                        $school_name, $contact, $daihyou, $url,$reply) {
        $this->send_from = $send_from;
        $this->send_subject = $send_subject;
        $this->parent_name = $parent_name;
        $this->school_name = $school_name;
        $this->contact = $contact;
        $this->daihyou = $daihyou;
        $this->url = $url;
        $this->reply = $reply;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $send_from =  $this->send_from ;
        
        $send_subject = $this->send_subject;
        $parent_name = $this->parent_name;
        $school_name = $this->school_name;
        $contact = $this->contact;
        $daihyou = $this->daihyou;
        $url =  $this->url;
        $reply = $this->reply;
        return $this->view ( '_mail.invoice_mail_notification', compact ( 
                'parent_name', 'school_name', 'contact', 'daihyou', 'url', 'reply') )->from($send_from)->subject($send_subject);
        /* return $this->view('view.name'); */
    }
}