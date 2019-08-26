<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailTemplate extends Mailable {
    
    use Queueable, SerializesModels;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($addressee_name, $contents, $subject) {
        $this->addressee_name = $addressee_name;
        $this->contents = $contents;
        $this->subject = $subject;
        // $this->from = $from;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $addressee_name = $this->addressee_name;
        $contents = $this->contents;
        $subject = $this->subject;
        // $from = $this->from;
        $school_name = session ( 'school.login' ) ['name'];
        $daihyou = session ( 'school.login' ) ['daihyou'];
        $mailaddress = session ( 'school.login' ) ['mailaddress'];
        
        return $this->view ( '_mail.bcmail_notification', compact ( 'addressee_name', 'contents', 'subject', 'school_name', 'daihyou', 'mailaddress' ) );
        /* return $this->view('view.name'); */
    }
}