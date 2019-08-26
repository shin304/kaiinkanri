<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Model\MailTemplateTable;
use App\Lang;
use App\ConstantsModel;
use Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class MailTemplateController extends _BaseSchoolController
{
    private $mail_template_type;
    private $mail_template;
    protected static $LANG_URL = 'mail_template';

    public function __construct()
    {
        parent::__construct();
        // 多国語対応
        $message_content = parent::getMessageLocale();
        $this->lan = new Lang($message_content);
    }

    /**
     * イベント登録画面の処理
     *
     * @param  Request $request
     * @return view of event and program
     */

    public function listMailTemplate(Request $request) {
        //　get instance of mail template
        $mail_template = MailTemplateTable::getInstance();
        // get list mail template
        $pschool_id = session()->get('school.login.id');
    	$mail_template_list = $mail_template->getListMailTemplate ($pschool_id);
        // return to view input of event
    	return response ()->json ( $mail_template_list );
    }

    public function listMailTemplateByMailType(Request $request) {
        // check request of type_mail has or no
        if ($request->has ('type_mail')){
            //　get instance of mail template 
            $mail_template = MailTemplateTable::getInstance();
            //
            $pschool_id = session()->get('school.login.id');

            // get list mail template when choose type mail
            $mail_template_list = $mail_template->getListMailTemplateByMailType ($pschool_id,$request->type_mail);
            return response ()->json ( $mail_template_list );
        }
        else {
            return Redirect::to ("/school/mailtemplate");
        }
    }

    public function listMailTemplateByID(Request $request) {
        // TODO check request of type_mail has or no
        if ($request->has ('id')){
            //　get instance of mail template 
            $mail_template = MailTemplateTable::getInstance();
            // get list mail template when choose type mail
            $mail_template_list = $mail_template->getListMailTemplateByID ($request->id);
            return response ()->json ( $mail_template_list );
        }
    }

    /**
     * イベント登録画面の処理
     *
     * @param  Request $request
     * @return view
     */
    public function deleteMailTemplate(Request $request) {
        try {          
            // get instance of mail template 
            $mail_template = MailTemplateTable::getInstance();
            // get list mail template by has id from request
            $mail_template_list = $mail_template->getListMailTemplateByID ( $request->id );
            // get function delete
            $result = $mail_template_list->deleteMailTemplate ( $request->id );
        } catch ( \Exception $e ) {
            // To do delete error
        }
    }

    // TODO get value from request after insert to event, program,... 
    public function insertMailTemplate(Request $request)
    {
        $result = null;
        $mail_template = MailTemplateTable::getInstance();
        $pschool_id = session()->get('school.login.id');
        $result = $mail_template->insertMailTemplate ($pschool_id, $request->name ,$request->mail_type, $request->subject, $request->body, $request->footer);
    }

    public function validateName(Request $request)
    {
        $boolean = null;
        try {
            $mail_template = MailTemplateTable::getInstance();
            $pschool_id = session()->get('school.login.id');
            $check_have_name = $mail_template->isExistNameTemplate ($pschool_id, $request->name );
            if ($check_have_name == 0) {
                $boolean = true;
            } else {
                $boolean = false;
            }
        } catch ( \Exception $e ) {
            // TODO check error
        }
        return json_encode($boolean);
    }
}
