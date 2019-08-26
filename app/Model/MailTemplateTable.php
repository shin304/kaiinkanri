<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MailTemplateTable extends Model
{
    protected $table = 'mail_template';
    private static $_instance = null;
    public $timestamps = false;

    /**
     * @return MailTemaplateTable
     */
    public static function getInstance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new MailTemplateTable();
        }
        return self::$_instance;
    }

    public function getListMailTemplate ($pschool_id) {
    	$mail_template_list = MailTemplateTable::all()->where('pschool_id',$pschool_id) ->where ('delete_date', null)->sortByDesc ('register_date');
    	return $mail_template_list;
    }

    public function getListMailTemplateByMailType($pschool_id, $type) {
    	$mail_template_list = MailTemplateTable::all()->where('pschool_id',$pschool_id)->where ('mail_type', $type)->where ('delete_date', null)->sortByDesc ('register_date');
    	return $mail_template_list;
    }

    public function getListMailTemplateByID($id) {
        $mail_template = MailTemplateTable::all()->where ('id', $id)->where ('delete_date', null)->first ();
        return $mail_template;
    }

    public function isExistNameTemplate($pschool, $name) {
        $result = MailTemplateTable::where ( 'name', $name )->where('pschool_id', $pschool)->count ();
        return $result;
    }

    public function deleteMailTemplate($id) {
        $mail_template = MailTemplateTable::where ( 'id', $id )->where ( 'delete_date', null )->first ();
        $mail_template->delete_date = date ( 'Y-m-d H:i:s' );
        $result = $mail_template->save ();
        return $result;
    }

    public function insertMailTemplate($pschool_id, $name, $mail_type, $subject, $body, $footer) {
        $mail_template = new MailTemplateTable ();
        $mail_template->pschool_id = $pschool_id;
        $mail_template->name = $name;
        $mail_template->mail_type = $mail_type;
        $mail_template->subject = $subject;
        $mail_template->body = $body;
        $mail_template->footer = $footer;
        $mail_template->register_date = date ( 'Y-m-d H:i:s' );
        $mail_template->update_date = null;
        $result = $mail_template->save ();
        return $result;
    }
}
