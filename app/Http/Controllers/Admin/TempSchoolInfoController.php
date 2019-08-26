<?php

namespace App\Http\Controllers\Admin;

use App\Common\Constants;
use App\Common\Email;
use App\ConstantsModel;
use App\Lang;
use App\Message;
use App\MessageFile;
use App\Model\HierarchyTable;
use App\Model\MasterMenuTable;
use App\Model\MCityTable;
use App\Model\MPrefTable;
use App\Model\SchoolMenuTable;
use App\MPlan;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use App\Model\TempSchoolInfoTable;

class TempSchoolInfoController extends VoyagerBreadController
{
    use \App\Common\Email;
    use BreadFileHandler;

    const SCHOOL_CODE_LENGTH_MIN = 100000;
    const SCHOOL_CODE_LENGTH_MAX = 999999;
    const ERROR_EXIST_MAIL_LOGIN = -1;
    const DB_EXCEPTION = 0;
    const SAVE_INFO_SUCCESS = 1;
    const LAST_DAY_OF_MONTH = 99;
    const IS_DISPLAY_IN_DEMO_APPROVE = 1;

    protected static $LANG_URL = 'pschool';

    //***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Browse our Data Type (B)READ
    //
    //****************************************

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->changeOptionInitData($dataType->browseRows, 3);

        // Check permission
        Voyager::canOrFail('browse_'.$dataType->name);

        $getter = $dataType->server_side ? 'paginate' : 'get';

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            $relationships = $this->getRelationships($dataType);

            if ($model->timestamps) {
                $dataTypeContent = call_user_func([$model->whereIn('status', [Constants::STATUS_MAIL_CONFIRMED, Constants::STATUS_APPROVED] )->where('delete_date', NULL)->with($relationships)->latest(), $getter]);
            } else {
                $dataTypeContent = call_user_func([$model->whereIn('status', [Constants::STATUS_MAIL_CONFIRMED, Constants::STATUS_APPROVED] )->where('delete_date', NULL)->with($relationships)->orderBy('id', 'DESC'), $getter]);
            }

            //Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name)->where('delete_date', NULL), $getter]);
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($model);

        // get list all plans
        $listPlan = DB::table('m_plan')->where('display_in_demo', '=' ,self::IS_DISPLAY_IN_DEMO_APPROVE)->whereNull('delete_date')->get()->toArray();

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'listPlan' ));
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |__) |
    //               |  _  /
    //               | | \ \
    //               |_|  \_\
    //
    //  Read an item of our Data Type B(R)EAD
    //
    //****************************************

    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->changeOptionInitData($dataType->readRows, 3);

        // Check permission
        Voyager::canOrFail('read_'.$dataType->name);

        $relationships = $this->getRelationships($dataType);
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $dataTypeContent = call_user_func([$model->with($relationships), 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        //Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        $prefList = MPrefTable::getInstance()->getList();
        $prefList = array_pluck($prefList, 'name', 'id');

        $cityList = MCityTable::getInstance()->getListByPref($dataTypeContent->pref_id);
        $cityList = array_pluck($cityList, 'name', 'id');

        // generate json for modal
        $result = array();
        if(!empty($dataTypeContent)){
            $result['登録メールアドレス'] = $dataTypeContent->mail_address;
            $result['会社・組織名称'] = $dataTypeContent->company_name;
            $result['代表者・登録者名称'] = $dataTypeContent->customer_name;
            $result['郵便番号'] = $dataTypeContent->zip_code;
            $result['都道府県名'] = $prefList[$dataTypeContent->pref_id];
            $result['市区町村名'] = $cityList[$dataTypeContent->city_id];
            $result['番地'] = $dataTypeContent->address;
            $result['ビル名'] = $dataTypeContent->building;
            $result['連絡先電話番号'] = $dataTypeContent->phone;
            $result['FAX'] = $dataTypeContent->fax;
            $result['ホームページ'] = $dataTypeContent->home_page;
            $result['確認コード'] = $dataTypeContent->register_code;
            $result['状態'] = Constants::DEMO_ACCOUNT_STATUS[$dataTypeContent->status];
        }

        return $result;

//            return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));

    }

    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  Edit an item of our Data Type BR(E)AD
    //
    //****************************************

    public function edit(Request $request, $id)
    {
        // 2017/5/24 Tung fix bug clear errors
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->changeOptionInitData($dataType->editRows, 2, $id);

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $relationships = $this->getRelationships($dataType);

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                ? app($dataType->model_name)->with($relationships)->findOrFail($id)
                : DB::table($dataType->name)->where('id', $id)->first(); // If Model doest exist, get data from table name

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        // dd($request);
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        $message = array(
                'message'    => "「{$dataType->display_name_singular}」が変更されました。",
                'alert-type' => 'success'
        );

        return redirect()
                ->route("voyager.{$dataType->slug}.edit", ['id' => $id])
                ->with([
                        'message'    => $message
                ]);
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create(Request $request)
    {
        // 2017/5/24 Tung fix bug clear errors
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->changeOptionInitData($dataType->addRows, 1);

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                ? new $dataType->model_name()
                : false;

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
        $message = array(
                'message'    => "「{$dataType->display_name_singular}」が登録されました。",
                'alert-type' => 'success'
        );

        return redirect()
                ->route("voyager.{$dataType->slug}.edit", ['id' => $data->id])
                ->with([
                        'message'    => $message
                ]);
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |  | |
    //               | |  | |
    //               | |__| |
    //               |_____/
    //
    //         Delete an item BREA(D)
    //
    //****************************************

    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('delete_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        foreach ($dataType->deleteRows as $row) {
            if ($row->type == 'image') {
                $this->deleteFileIfExists('/uploads/'.$data->{$row->field});

                $options = json_decode($row->details);

                if (isset($options->thumbnails)) {
                    foreach ($options->thumbnails as $thumbnail) {
                        $ext = explode('.', $data->{$row->field});
                        $extension = '.'.$ext[count($ext) - 1];

                        $path = str_replace($extension, '', $data->{$row->field});

                        $thumb_name = $thumbnail->name;

                        $this->deleteFileIfExists('/uploads/'.$path.'-'.$thumb_name.$extension);
                    }
                }
            }
        }

        $data = DB::table('temp_school_info')->where('id',$id)->update(array('delete_date'=>date('Y-m-d H:i:s')))
                ? $message = array(
                        'message'    => "「{$dataType->display_name_singular}」が削除されました。",
                        'alert-type' => 'success'
                )
                : $message = array(
                        'message'    => "エラーが発生しました。削除できません。",
                        'alert-type' => 'error'
                );

        return redirect()->route("voyager.{$dataType->slug}.index")->with(['message' => $message]);
    }

    // Change lock status
    public function lock(Request $request)
    {

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $mode_lock = $request->mode;

        $id = $request->id;

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        $data->is_locked = $mode_lock;

        $data->save();

        $message = array(
                'message'    => "「{$dataType->display_name_singular}」が登録されました。",
                'alert-type' => 'success'
        );

        return redirect()->route("voyager.{$dataType->slug}.index")->with(['message' => $message]);
    }
    public function approve(Request $request)
    {

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $id = $request->id;

        $plan_id = $request->plan_id;

        //check plan is for demo or not

        $plan = DB::table('m_plan')->where('id', '=', $plan_id)
                                ->where('display_in_demo', '=', 1)
                                ->whereNull('delete_date')->get()->toArray();
        if(empty($plan)){
            $message = array(
                'message'    => "ERROR",
                'alert-type' => 'error'
            );
            return redirect()->route("voyager.{$dataType->slug}.index")->with(['message' => $message]);
        }

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        $data->plan_id = $plan_id;

        // TODO call function save temp to pschool and login account

        $res = $this->save_temp_info_to_pschool($data);

        switch ($res) {
            case self::SAVE_INFO_SUCCESS :
                $message = array(
                        'message'    =>  $data->mail_address ."が登録されました。",
                        'alert-type' => 'success'
                );

                // TODO send mail inform success
                // メールを送る
                $to_email = $data->mail_address;
                $is_sent = $this->sendEmailConfirmForUser(array(), $to_email, false);
                if ($is_sent) {
                    $this->logSuccess("Send mail message");
                } else {
                    throw new \Exception( "Send mail message fail ");
                }
                // TODO update status of temp

                $data->status = Constants::STATUS_APPROVED ;

                unset($data->plan_id);

                $data->save();

                break;

            case self::DB_EXCEPTION :
                $message = array(
                        'message'    => "ERROR",
                        'alert-type' => 'error'
                );
                break;

            case self::ERROR_EXIST_MAIL_LOGIN :
                $message = array(
                        'message'    => $data->mail_address . " : このメールは既に存在します。",
                        'alert-type' => 'error'
                );
                break;

            default:
                break;
        }


        return redirect()->route("voyager.{$dataType->slug}.index")->with(['message' => $message]);
    }

    /*
     * function check temp data is exists or not
     * base on mail address on login_account table
     * return 1 : success save to pschool and login_account
     * return 0 : db exception
     * return -1 : exists login email
     */
    private function save_temp_info_to_pschool($temp_data){

        try{

//            $login = DB::table('login_account')->select('id')->where('login_id', '=', $temp_data->mail_address)->first();
//
//            if(!empty($login)){
//
//                return self::ERROR_EXIST_MAIL_LOGIN;
//
//            }else {

                //insert login account data

                $lang = array_flip(ConstantsModel::$lang_setting);

                $login_data = array(
                        'login_id' => $temp_data->mail_address,
                        'login_pw' => $temp_data->login_pw,
                        'auth_type' => ConstantsModel::$LOGIN_AUTH_SCHOOL,
                        'register_admin' => Auth::id(),
                        'active_flag' => 1,
                        'register_date' => date ('Y-m-d H:i:s'),
                        'lang_code' => $lang['jp']    // Default we set it to 日本語
                );

                $login_id = DB::table('login_account')->insertGetId( $login_data );

            //insert pschool data
                //generate pschool operator code
                $pschool_code = mt_rand(self::SCHOOL_CODE_LENGTH_MIN,self::SCHOOL_CODE_LENGTH_MAX);
                $cnt = DB::table('pschool')->where('pschool_code', $pschool_code)->count();
                while ($cnt) {
                    $pschool_code = mt_rand(self::SCHOOL_CODE_LENGTH_MIN,self::SCHOOL_CODE_LENGTH_MAX);
                    $$cnt = DB::table('pschool')->where('pschool_code', $pschool_code)->count();
                }
                // end generation

                if(!empty($login_id)){

                    $group_id = DB::table('pschool')->max('group_id')+1;

                    $school_data = array(
                        'group_id'             => $group_id,
                        'login_account_id'     => $login_id,
                        'daihyou'              => $temp_data->customer_name,
                        'name'                 => $temp_data->company_name,
                        'zip_code'             => $temp_data->zip_code,
                        'zip_code1'            => substr($temp_data->zip_code,0,3),
                        'zip_code2'            => substr($temp_data->zip_code,3,4),
                        'pschool_code'         => $pschool_code,
                        'pref_id'              => $temp_data->pref_id,
                        'city_id'              => $temp_data->city_id,
                        'address'              => $temp_data->address,
                        'building'             => ($temp_data->building != null) ? $temp_data->building : '',
                        'tel'                  => $temp_data->phone,
                        'fax'                  => ($temp_data->fax != null) ? $temp_data->fax : '',
                        'mailaddress'          => $temp_data->mail_address,
                        'web'                  => ($temp_data->home_page != null) ? $temp_data->home_page : '',
                        'invoice_closing_date' => 25, // set default date
                        'payment_date'         => self::LAST_DAY_OF_MONTH, // set default date
                        'invoice_batch_date'   => 20, // set default date
                        'register_date'        => date ('Y-m-d H:i:s'),
                        'language'             => $lang['jp'],    // Default we set it to 日本語
                        'register_admin'       => Auth::id (),
                        'country_code'         => Constants::COUNTRY_CODE["JP"],
                    );

                    $pschool_id =  DB::table('pschool')->insertGetId( $school_data );

                    // Save plan detail

                    $planId = !empty($temp_data->plan_id) ? $temp_data->plan_id : null;

                    if($planId != null){

                        $planDetail = MPlan::getPlanDetail($planId);

                        DB::table('pschool')->where('id', $pschool_id)->update (array (
                                'm_plan_id'             => $planDetail->id,
                                'limit_number_register' => $planDetail->number_register,
                                'limit_number_active'   => $planDetail->number_active,
                                'valid_date'            => $planDetail->validation_date,
                            ));

                    }

                    // Save menu default

                    $plan_menus = SchoolMenuTable::getInstance()->getActiveMenuListNew2($planId, array_flip(ConstantsModel::$member_type)['PLAN']);
                    if(!empty($plan_menus)){
                        foreach ($plan_menus as $k => $menu){
                            $menu_list[$menu['master_menu_id']] = $menu['master_menu_id'];
                            if($menu['editable'] == 1){
                                $viewable_list[$menu['master_menu_id']] = $menu['master_menu_id'];
                            }
                            if($menu['viewable'] == 1){
                                $editable_list[$menu['master_menu_id']] = $menu['master_menu_id'];
                            }
                        }
                    }else{
                        $menus = $this->get_default_menu_demo_account();
                        $menu_list      = $menus['menu_list'];
                        $viewable_list  = $menus['viewable_list'];
                        $editable_list  = $menus['editable_list'];
                    }

                    $menu_ids       = array(); // Use for add message on screen

                    $index = 1;
                    foreach ($menu_list as $key => $value) {
                        $menu = array (
                            "pschool_id"     => $pschool_id,
                            "master_menu_id" => $value,
                            "viewable"       => isset($viewable_list[$key]) ? 1 : 0,
                            "editable"       => isset($editable_list[$key]) ? 1 : 0,
                            "seq_no"         => $index,
                            "active_flag"    => 1,
                        );
                        $index++;

                        SchoolMenuTable::getInstance()->save($menu);

                        $menu_ids[] = $value;
                    }

                    //Save hyerachy

                    $hierarchy = array (
                            'group_id' => $group_id,
                            'layer' => 2,
                            'pschool_id' => $pschool_id,
                            'manage_flag' => "1"
                    );

                    HierarchyTable::getInstance()->save ($hierarchy);

                    // Default message file

                    $business_type_id = explode('|', "1|sports"); // business_type_id default  => 1|sports

                    $message_file_name = $business_type_id[1]. '_' . md5($pschool_id);

                    // update message file name to Pschool table
                    DB::table('pschool')->where('id', $pschool_id)->update(['message_file'=>$message_file_name]);

                    $message_file = $this->getParentFile( $business_type_id[0], 2);

                    if ($message_file->id) {

                        $new_file = array (
                                'parent_id' => $message_file->parent_id,
                                'bussiness_type_id' => $business_type_id[0],
                                'pschool_id' => $pschool_id,
                                'lang_code' => $lang['jp'], // Default we set it to 日本語
                                'message_file_name' => $message_file_name,
                                'register_admin' => Auth::id ()
                        );
                        $new_message_file_id = MessageFile::insertGetId ($new_file);
                        // update parent_id
                        MessageFile::where ('id', $new_message_file_id)->update (['parent_id' => DB::raw ('CONCAT(parent_id, id, "/")')]);

                        $lang_setting = ConstantsModel::$lang_setting;

                        $lang_file_path = '/lang/' . $lang_setting[$lang['jp']] . '/' . $message_file_name . '.php';

                        // Call create new message
                        $this->createNewMessageOnscreen ($message_file->id, $new_message_file_id, $lang_file_path, $menu_ids);

                        return self::SAVE_INFO_SUCCESS;
                    }
                }else{

                    return self::DB_EXCEPTION;

                }

//            }
        }catch(Exception $e){

            return self::DB_EXCEPTION;

        }

        return self::SAVE_INFO_SUCCESS;
    }

    /*
     * Get all master menus
     * set to 3 array as default
     */
    private function get_default_menu_demo_account(){

        $menus = array();

        $data = DB::table('master_menu')->select("*")->whereNull('delete_date')->get();

        $data->toArray();

        foreach($data->toArray() as $k => $item){

            $menus['menu_list'][$item->id] = $item->id;
            $menus['viewable_list'][$item->id] = "on";
            $menus['editable_list'][$item->id] = "on";

        }

        return $menus;
    }

    /**
     * Load message file table, get file parent to refer
     * @param $business_type_id
     * @param $language
     */
    private function getParentFile ($business_type_id, $language) {
        $parent_file = MessageFile::where('bussiness_type_id', $business_type_id)->where('pschool_id', 0)->where('lang_code', $language)->first();
        if (empty($parent_file)) {
            // get from common.php
            $parent_file = MessageFile::where('bussiness_type_id', 0)->where('pschool_id', 0)->where('lang_code', $language)->first();
        }

        return $parent_file;
    }

    /**
     * Load message of parent & Call storeMessage()
     * @param $parent_message_id
     * @param $new_message_file_id
     * @param $lang_file_path
     * @param $menu_ids: id of menu assigned
     */
    private function createNewMessageOnscreen($parent_message_id, $new_message_file_id, $lang_file_path, $menu_ids) {
        // get all message of parent file
        $screen_list = Message::where('message_file_id', $parent_message_id)->whereNull('message_key')->get();

        // TODO select all message on screen ( exclude menu)
        $message_list_1 = Message::where('message_file_id', $parent_message_id)->where('screen_key', '<>', 'school.menu')->whereNotNull('message_key')->get();

        // TODO select message for menu (Menu assigned)
        $message_list_2 = array();
        if (count($menu_ids) > 0) {

            // $message_key: get menu_name_key of menu assigned
            $master_menu = DB::table('master_menu')->whereIn('id', $menu_ids)->get();
            $message_key = array();
            foreach ($master_menu as $value) {
                $message_key[] = $value->menu_name_key;
            }

            if (count($message_key) > 0) {
                $message_list_2 = Message::where('message_file_id', $parent_message_id)->where('screen_key', 'school.menu')->whereIn('message_key', $message_key)->get();

            }
        }

        $this->storeMessage($screen_list, $message_list_1->merge($message_list_2), $new_message_file_id, $lang_file_path);
    }

    /**
     * Insert or Edit message
     * @param $screen_list, $message_list
     * @param $message_file_id
     * @param $lang_file_path
     * @param $mode null: insert, 1:update
     * @param $delete_menus [menu_name_key1, menu_name_key2,....]
     * @param $add_menus [menu_name_key1=>[message_value1, comment1], menu_name_key2=>[message_value2, comment2],...]
     */
    private function storeMessage($screen_list, $message_list, $message_file_id, $lang_file_path, $mode=null, $delete_menus=array(), $add_menus=array()) {
        // TODO store screen name
        $screen_arr = array();
        $ids= array();

        try {
            foreach ($screen_list as $value) {
                $screen = array(
                        'message_file_id'   => $message_file_id,
                        'screen_key'        => $value->screen_key,
                        'screen_value'      => $value->screen_value
                );
                if (!is_null($mode)) { //編集
                    // $screen['id']               = $value->id;
                    $screen['update_admin']     = 1;
                    // $message_tbl->fill($screen)->save();
                    Message::where('id', $value->id)->update($screen);

                    $ids[] = $value->id;
                } else {
                    $screen['register_admin']   = 1;
                }
                $screen_arr[] = $screen;
            }

            if (!is_null($mode)) {

            } else {
                Message::insert($screen_arr);
            }

            // TODO store message on screen
            $data = array();
            foreach ($message_list as $value) {
                $message = array(
                        'message_file_id'   => $message_file_id,
                        'screen_key'        => $value->screen_key,
                        'screen_value'      => $value->screen_value,
                        'message_key'       => $value->message_key,
                        'message_value'     => $value->message_value,
                        'comment'           => $value->comment
                );
                if (!is_null($mode)) { //編集

                    if (in_array($value->message_key, $delete_menus)) { //Delete
                        Message::where('id', $value->id)->delete();

                    } else {
                        // $message['id']               = $value->id;
                        $message['update_admin']     = 1;
                        Message::where('id', $value->id)->update($message);
                        $data[] = $message;
                    }


                }else {
                    $message['register_admin']   = 1;
                    $data[] = $message;
                }


            }

            // Insert new Item menu (Case Edit)
            $data2 = array();
            if (count($add_menus) > 0) {
                foreach ($add_menus as $key => $value) {
                    $message = array(
                            'message_file_id'   => $message_file_id,
                            'screen_key'        => 'school.menu',
                            'screen_value'      => 'メニュー',
                            'message_key'       => $key, // menu_name_key
                            'message_value'     => $value[0],
                            'comment'           => $value[1],
                            'register_admin'    => 1
                    );

                    $data2[] = $message;
                }

                Message::insert($data2); //insert chunked data
                $data = array_merge($data, $data2);
            }

            $collection = collect($data); //turn data into collection

            $chunks = $collection->chunk(100); //chunk into smaller pieces
            $chunks->toArray(); //convert chunk to array

            foreach ($chunks as $chunk) {
                if (!is_null($mode)) {
                    // Message::update($chunk->toArray()); //insert chunked data
                } else {
                    Message::insert($chunk->toArray()); //insert chunked data
                }
            }
        } catch (\Exception $e){
            // TODO log error message

        }

        $this->destroy_file($lang_file_path);
        $contents = $this->write_content_message($screen_arr, $data);
        $this->create_file($lang_file_path, $contents);
    }
}
