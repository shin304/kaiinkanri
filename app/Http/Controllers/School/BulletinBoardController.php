<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Http\Controllers\Controller;
use App\ConstantsModel;
use App\Lang;
use App\Model\BulletinBoardTable;
use App\Model\UploadFilesTable;
use Validator;

class BulletinBoardController extends _BaseSchoolController
{
    protected static $ACTION_URL = 'bulletinboard';
    // set to get language code in function getMessageLocale()
    protected static $LANG_URL = 'bulletin_board';
    private static $UPLOAD_DIR = '/storage/uploads/';
    private static $TOP_PAGE = '/school/bulletinboard';

    const SESSION_BULLETIN_ACTION    = 'school_bulletinboard_action';
    const TARGET_STAFF      = 0;
    const TARGET_TEACHER    = 1;
//    const TARGET_OTHER      = 2;
    const TARGET_STUDENT      = 2;
    const TARGET_PARENT      = 3;
    const SCHOOL_DIR        = 'school/';
    const BULLETIN_CATEGORY_CODE    = '1';

    private $_bulletin_search_item = ['search_title', 'search_start', 'search_finish'];
    private $_bulletin_search_session_key = 'bulletin_search_form';

    public function __construct()
    {
        parent::__construct();
        // 多国語対応
        $message_content = parent::getMessageLocale();
        $this->lan = new Lang($message_content);

        $this->_loginAdmin = $this->getLoginAdmin();
    }

    public function index(Request $request)
    {
        $this->clearOldInputSession();
        // メニューから遷移のとき、セッションに検索条件があったらそれをクリアする
        // if ( session()->exists(self::SESSION_BULLETIN_ACTION)) {
        //     session()->forget(self::SESSION_BULLETIN_ACTION);
        // }
        return $this->search($request);
    }

    public function search(Request $request)
    {
        $this->_initSearchDataFromSession($this->_bulletin_search_item, $this->_bulletin_search_session_key);

        // get message from session
        $message = null;
        if (session()->has(self::SESSION_BULLETIN_ACTION)) {
            $message = session()->pull(self::SESSION_BULLETIN_ACTION);
        }
        // get bulletin_list
        $condition = array( 'pschool_id'    => $this->_loginAdmin['id'], 
                            'account_type'  => $this->_loginAdmin['auth_type'],
                            'search_title'  => $request->search_title,
                            'search_start'  => $request->search_start,
                            'search_finish'  => $request->search_finish );
        $bulletin_list = BulletinBoardTable::getInstance()->getBulletinList($condition);
        return view('School.Bulletin_board.index')
                    ->with( 'lan' , $this->lan )
                    ->with( 'list' , $bulletin_list )
                    ->with( 'bulletin_target', ConstantsModel::$bulletin_target )
                    ->with( 'message' , $message[0] );
    }

    public function delete (Request $request)
    {
        // check exist of $request->delete_id
        if ($request->has('delete_id')) {
            $bulletinBoardTable = BulletinBoardTable::getInstance();
            $bulletin_board = $bulletinBoardTable->load($request->delete_id);
            if ($bulletin_board) {
                $uploadFilesTable = UploadFilesTable::getInstance();
                try {
                    // delete file(s) in DB
                    $bulletinBoardTable->deleteRow(array( 'id' => $request->delete_id ));
                    $files = $uploadFilesTable->getActiveList( array(
                                'category_code' => self::BULLETIN_CATEGORY_CODE,
                                'target_id'     => $bulletin_board['id']
                            ) );
                    // delete file(s) in storage
                    foreach ($files as $key => $file) {
                        $this->deleteUploadFile( $file['id'] );
                    }
                    // set message
                    session()->push(self::SESSION_BULLETIN_ACTION, 'delete_success');
                } catch (Exception $e) {
                    $this->_logger->error($e->getMessage());
                }
            }
        }
        return Redirect::to(self::$TOP_PAGE);
    }

    public function detail(Request $request)
    {
        // check exist of $request->id
        if ($request->has('id')) {
            $bulletinBoardTable = BulletinBoardTable::getInstance();
            $bulletin_board = $bulletinBoardTable->load($request->id);
            if ($bulletin_board) {
                $uploadFilesTable = UploadFilesTable::getInstance();
                // get file(s) info
                $files  = $uploadFilesTable->getActiveList(array(
                                                'category_code' => self::BULLETIN_CATEGORY_CODE,
                                                'target_id'     => $bulletin_board['id'],
                                            ));

                return view('School.Bulletin_board.detail') 
                            ->with( 'lan' , $this->lan )
                            ->with( 'bulletin_board' , $bulletin_board )
                            ->with( 'files' , $files )
                            ->with( 'file_dir', self::$UPLOAD_DIR );
            } else {
                return Redirect::to(self::$TOP_PAGE);
            }
        }
    }

    public function input(Request $request)
    {
        // clear error message
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        } else {
            // set default calendar_flag = 1 when the first loads register page
            $request->offsetSet('calendar_flag', 1);
        }
        // recover $request->id
        $this->recoverWithInput($request, array('id'));
        // case: edit
        if ($request->has('id')) {
            $bulletin_board_item = BulletinBoardTable::getInstance()->load($request->id);

            // 対象を処理 (default: 'staff,teacher,other' => '0,0,0')
            $target = explode(",", $bulletin_board_item['target']);
            unset($bulletin_board_item['target']);
            $bulletin_board_item['target']['staff'] = isset($target[self::TARGET_STAFF])?$target[self::TARGET_STAFF]:0;
            $bulletin_board_item['target']['teacher'] = isset($target[self::TARGET_TEACHER])?$target[self::TARGET_TEACHER]:0;
            $bulletin_board_item['target']['student'] = isset($target[self::TARGET_STUDENT])?$target[self::TARGET_STUDENT]:0;
            $bulletin_board_item['target']['parent'] = isset($target[self::TARGET_PARENT])?$target[self::TARGET_PARENT]:0;

            // ファイルを取る
            $bulletin_board_item['files'] = UploadFilesTable::getInstance()
                                            ->getActiveList(array(
                                                'category_code' => self::BULLETIN_CATEGORY_CODE,
                                                'target_id'     => $request->id,
                                            ));
            $request->merge($bulletin_board_item);
        }
        return view('School.Bulletin_board.input')
                    ->with('lan',$this->lan)
                    ->with('file_dir', self::$UPLOAD_DIR);
    }

    public function complete(Request $request)
    {
        // エラー表示用の配列
        $rules = $this->get_validate_rules($request);
        $messsages = $this->get_validate_message($request);
        $validator = Validator::make(request()->all(), $rules, $messsages);
        if ($validator->fails()) { 
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        // END - エラー表示用の配列

        try {
            // target info (default: 'staff,teacher,other' => '0,0,0')
            $staff      = ($request->has('target.staff')) ? 1 : 0;
            $teacher    = ($request->has('target.teacher')) ? 1 : 0;
            $student      = ($request->has('target.student')) ? 1 : 0;
            $parent      = ($request->has('target.parent')) ? 1 : 0;
            $target     = $staff . "," . $teacher . "," . $student . "," . $parent;

            $bulletin_board = array(
                'title'             => $request->title,
                'message'           => $request->message,
                'start_date'        => $request->start_date,
                'finish_date'       => ($request->finish_date) ? $request->finish_date : null,
                'calendar_flag'     => ($request->calendar_flag) ? $request->calendar_flag : 0,
                'calendar_color'    => $request->calendar_color,
                'target'            => $target
            );
            // In case of editting, id is present
            if ($request->has('id')){
                $bulletin_board['id'] = $request->id;
            } else {
                $bulletin_board['pschool_id'] = $this->_loginAdmin['id'];
            }
            $saved_bulletin_board_id = BulletinBoardTable::getInstance()->save($bulletin_board);

            // ファイルを保存
            if ($request->hasFile('files')) {
                $uploadFilesTable = UploadFilesTable::getInstance();
                $dir = self::SCHOOL_DIR . $this->_loginAdmin['id'];
                $files = $request->file('files');
                foreach ($files as $key => $file) {
                    // get original info of file
                    $display_file_name = $file->getClientOriginalName();
                    $ext = $file->getClientOriginalExtension();

                    // generate file name
                    $real_file_name = md5( $display_file_name . uniqid() ) . '.' .$ext;

                    // save file to uploads folder
                    $file_path = $file->storeAs( $dir, $real_file_name, 'uploads' );

                    $upload_file = array(
                        'category_code' => self::BULLETIN_CATEGORY_CODE,
                        'target_id'     => $saved_bulletin_board_id,
                        'file_path'     => $file_path,
                        'real_file_name' => $real_file_name,
                        'disp_file_name' => $display_file_name,
                    );
                    $uploadFilesTable->save($upload_file);
                }
            } // end - ファイルを保存
        } catch (Exception $e) {
            $this->_logger->error($e->getMessage());
            return false;
        }
        // set message
        if ($request->has('id')){
            session()->push(self::SESSION_BULLETIN_ACTION, 'update_success');
        } else {
            session()->push(self::SESSION_BULLETIN_ACTION, 'insert_success');
        }
        return Redirect::to(self::$TOP_PAGE);
    }

    public function ajaxDeleteUploadFile(Request $request)
    {
        if ($request->has('file_id')) {
            $file_info_deleted = $this->deleteUploadFile($request->file_id);
            if ($file_info_deleted) {
                return 'success';
            }
        }
        return 'fail';
    }

    private function deleteUploadFile($file_id)
    {
        $uploadFilesTable = UploadFilesTable::getInstance();
        $file = $uploadFilesTable->load($file_id);
        if ($file) {
            $file_deleted = '';
            $file_row_deleted = '';
            try {
                // delete file in folder
                if ( Storage::disk('uploads')->exists($file['file_path'])) {
                    $file_deleted = Storage::disk('uploads')->delete($file['file_path']);
                } else {
                    echo " File does not exist ! ";
                }
                // delete row in upload_files table
                $file_row_deleted = $uploadFilesTable->deleteRow(array('id'=>$file_id));
            } catch (Exception $e) {
                $this->_logger->error($e->getMessage());
                return false;
            }
            if ( $file_deleted && $file_row_deleted ) {
                return true;
            }
        }
        return false;
    }

    private function get_validate_rules($request) { 
        $rules = [  'title'         => 'required|max:255', 
                    'start_date'    => 'required',
                    ];
        if ($request->has('finish_date')) {
           $rules['finish_date'] = 'after_or_equal:start_date';
        }
        return $rules;
    }

    private function get_validate_message($request) {
        $messsages = array(
            'title.required'    => 'title_require', // TODO get msg from language resource files
            'title.max'         => 'title_require_less_than_255',
            'start_date.required'    => 'start_date_require',
            'finish_date.after_or_equal' => 'finish_date_after_start_date',
        );
        return $messsages;
    }
}
