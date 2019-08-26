<?php

namespace App\Http\Controllers\Admin;

use App\Common\Constants;
use App\MPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use App\Model\MasterMenuTable;
use App\Model\SchoolMenuTable;
use App\Model\LoginAccountTable;
use App\Model\HierarchyTable;
use App\Model\PschoolTable;
// use App\Http\Controllers\Common\weather_forecast_util;
use App\ConstantsModel;
use App\Lang;
use App\MessageFile;
use App\Message;
use App\Pschool;
use DB;
use File;
use Validator;

class PschoolController extends VoyagerBreadController
{   use BreadFileHandler;

    private $MENU_FILE = '/menu.php';
    protected static $LANG_URL = 'pschool'; 
    const SCHOOL_CODE_LENGTH_MIN = 100000;
    const SCHOOL_CODE_LENGTH_MAX = 999999;

    public function __construct()
    {
        // load message file language
        $message_content = array();
        if (session()->has('admin.lan')) {
            $message_file = session('admin.lan');
            if (array_key_exists(self::$LANG_URL, $message_file)) {
               $message_content = $message_file[self::$LANG_URL];
            }
        }
        
        view()->share('lan', new Lang ( $message_content ));
    }
    
    //****************************************
    //
    //      Browse our Data Type (B)READ
    //
    //****************************************
    public function index(Request $request)
    {   
        if (session ()->has('_old_input')) {
            session()->forget ('_old_input');
        }
       
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('browse_'.$dataType->name);

        $getter = $dataType->server_side ? 'paginate' : 'get';

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            $relationships = $this->getRelationships($dataType);
    
                $dataTypeContent = call_user_func([
                    $model->whereNull('pschool.delete_date')
                    ->where('C.layer', 2)
                    ->select('pschool.*', 'B.login_id', 'B.active_flag AS login_active_flag', 'C.layer', 'P.name AS pref_name', 'T.name AS city_name' ,'m_plan.plan_name' ,'pschool.limit_number_active',DB::RAW('SUM(CASE WHEN (student.delete_date IS NULL AND student.active_flag = 1) THEN 1 ELSE 0 END) AS active_student'))
                    ->leftJoin('login_account as B', 'pschool.login_account_id', '=', 'B.id')
                    ->leftJoin('hierarchy as C', 'pschool.id', '=', 'C.pschool_id')
                    ->leftJoin('m_pref as P', 'pref_id', '=', 'P.id')
                    ->leftJoin('m_city as T', 'city_id', '=', 'T.id')
                    ->leftJoin('m_plan', 'm_plan.id', '=', 'pschool.m_plan_id')
                    ->leftJoin('student', 'student.pschool_id', '=', 'pschool.id')
                    ->groupBy('pschool.id')
                    ->with($relationships)
                    ->orderBy('id', 'DESC'), $getter]);

                // $dataTypeContent = call_user_func([$dataType->model_name, 'getPschoolList']);
            
            //Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);

        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name)->where('delete_date','is', NULL), $getter]);
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($model);

        $view = 'voyager::bread.browse';

        if (view()->exists("Admin.$slug.browse")) {
            $view = "Admin.$slug.browse";
        }
           return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    //***************************************
    //  Edit an item of our Data Type BR(E)AD
    //
    //****************************************

    public function edit(Request $request, $id)
    {
        $this->removeErrors($request);

        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        
        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $relationships = $this->getRelationships($dataType);

        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? app($dataType->model_name)->with($relationships)->findOrFail($id)
            : DB::table($dataType->name)->where('id', $id)->first(); // If Model doest exist, get data from table name

        //----customize by Thang 2017/04/28
        $login_acc = DB::table('login_account')->where('id', $dataTypeContent->login_account_id)->first();
        $dataTypeContent->offsetSet('login_id', $login_acc->login_id);
        if ($dataTypeContent->zip_code) {
            $zip_code1 = substr( $dataTypeContent->zip_code , 0, 3);
            $dataTypeContent->offsetSet('zip_code1', $zip_code1);

            $zip_code2 = substr( $dataTypeContent->zip_code , 3);
            $dataTypeContent->offsetSet('zip_code2', $zip_code2);
        }
        if ($dataTypeContent->city_id && $dataTypeContent->pref_id) {
            $dataTypeContent->offsetSet('city_id', $dataTypeContent->pref_id.'_'.$dataTypeContent->city_id);
        }
        //end ----customize

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        $this->loadExtendData($dataType->editRows, $dataTypeContent);
        if (session()->has('old_data')) {
            $oldData = session('old_data')[0];
            $this->loadInput($dataTypeContent, $oldData);
            session()->forget('old_data');
        }

        //Get Master menu to assign
        $menuList = MasterMenuTable::getInstance()->getListMenu();
        
        $masterMenuList = array();
        $subMenuList = array();
        foreach ($menuList as $key => $value) {
            $menu_path = explode("/", $value['menu_path']);
            array_pop($menu_path);
            if (count($menu_path) > 1) {
                
                $subMenuList[$menu_path[0]][$value['id']] = $value;
            } else {
                $masterMenuList[$value['id']] = $value;
            }
            
        }
        //get current menu by id 
        $currentMenu = SchoolMenuTable::getInstance()->getActiveMenuListNew2 ($id);

        //Toran add get plan
        $plans = DB::table('m_plan')->select('m_plan.id','plan_name','number_register.category_value as number_register',
                'number_active.category_value as number_active', 'number_institution.category_value as number_institution',
                'm_plan.plan_amount', 'm_plan.validation_date')
                ->leftJoin('m_plan_category as number_register','number_register.id','=','m_plan.number_register_id')
                ->leftJoin('m_plan_category as number_active','number_active.id','=','m_plan.number_active_id')
                ->leftJoin('m_plan_category as number_institution','number_institution.id','=','m_plan.number_institution_id')
                ->whereNull('m_plan.delete_date')
                ->get();

        if(!empty($dataTypeContent->m_plan_id)){
            foreach($plans as $k => $plan){
                if($dataTypeContent->m_plan_id == $plan->id){
                    $plans[$k]->default = true;
                }
            }
        }
        //

        $applycationType = ConstantsModel::$applycationType;

        $view = 'voyager::bread.edit-add';

        if (view()->exists("Admin.$slug.edit-add")) {
            $view = "Admin.$slug.edit-add";
        }
//        dd($request);
        return view($view, compact('request', 'dataType', 'dataTypeContent', 'isModelTranslatable', 'masterMenuList', 'subMenuList', 'currentMenu', 'lan','plans', 'applycationType'));
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        
        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        // TODO validation data
        $condition = array(
                'id' => $id,
                'login_account_id' => $data['login_account_id'],
        );
        $rules = $this->get_validate_rules($request, $condition);
        $messsages = $this->get_validate_message();
        
        $validator = Validator::make(request()->all(), $rules, $messsages);

        if ($validator->fails()) {
            session()->push('old_data', $request->input());
            $request->offsetSet('errors',$validator->errors());
            return $this->edit($request, $id);
//            session()->push('old_data', $request->input());
//            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $school_menu_table = SchoolMenuTable::getInstance();
        $pschool_table = PschoolTable::getInstance();
        try {
            // TODO Update Login_Account
            $login_acc= array (
                'id' => $data['login_account_id'],
                'login_id' => $request->login_id,
                'update_admin' => Auth::id(),
                'active_flag' => $request->exists('active_flag') ? $request->active_flag : 1,
                'lang_code' => $request->language,
            );
            if ($request->has('login_pw')) {
                $login_acc['login_pw'] = md5($request->login_pw);
            }
            LoginAccountTable::getInstance()->save($login_acc);

            // TODO Custom data to update into Pschool 
            // zip_code Ex : 123-4567
            $request->offsetSet('zip_code', $request->zip_code1 . $request->zip_code2);
            // $request->city_id: prefid_cityid
            if ($request->exists('city_id') && $request->city_id != '0') {
                $cityId = explode('_', $request->city_id)[1];
                $request->offsetSet('city_id', $cityId);
            }
            $forecastRss = "";
            if ($request->country_code == 81) {
                // $forecastRss = weather_forecast_util::getWeatherRssLinkJP($request);
            } else {
                // $forecastRss = weather_forecast_util::getWeatherRssLinkEn($request);
            }
            $request->offsetSet('forecast_rss', $forecastRss);
            $request->offsetSet('update_date', date ( 'Y-m-d H:i:s' ));
            $request->offsetSet('update_admin', Auth::id());

            // Save business type
            // business_type_id: 1|sports, 2|education, 3|school,....
            $business_type_id = explode('|', $request->business_type_id);
            $request->offsetSet('business_type_id', $business_type_id[0]);

            if(!$request->offsetExists('custom_menu')){
                $request->offsetSet('custom_menu_flag', 0);
            }else{
                $request->offsetSet('custom_menu_flag', 1);
            }
            $request->offsetSet('application_type', $request->application_type);

            $this->insertUpdateDataWithoutValidation($request, $slug, $dataType->editRows, $data);
            DB::table('pschool')->where('id', $data->id)->update(['application_type'=>$request->application_type]);
            // TODO update menu item
            if($request->custom_menu == 1){

                $menu_list      = $request->request->get('menu_list', array());
                $viewable_list  = $request->request->get('viewable_list', array());
                $editable_list  = $request->request->get('editable_list', array());

            }else{
                $plan_menus = $school_menu_table->getActiveMenuListNew2($request->m_plan_id, array_flip(ConstantsModel::$member_type)['PLAN']);
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
                    $menu_list      = $request->request->get('menu_list', array());
                    $viewable_list  = $request->request->get('viewable_list', array());
                    $editable_list  = $request->request->get('editable_list', array());
                }
            }

            $menu_select    = $school_menu_table->getActiveMenuListNew($id);
            $menu_select_copy = $menu_select;
            $menu_ids       = array();

            $index = 1;
            foreach ($menu_list as $key => $value) {
                $menu = array(
                            "pschool_id"        => $id,
                            "master_menu_id"    => $value,
                            "viewable"          => isset($viewable_list[$key])? 1: 0,
                            "editable"          => isset($editable_list[$key])? 1: 0,
                            "seq_no"            => $index,
                            // "icon_url"           => ""
                            "active_flag"       => 1,
                        );
                        // case update 
                if (isset($menu_select[$value])) {
                    $menu['id'] = $menu_select[$value]['id'];
                    unset($menu_select[$value]);
                }
                $index++;
                $school_menu_table->save($menu);

                $menu_ids[] = $value;

            } 

            // Unselected menu will be deleted
            foreach ($menu_select as $key => $value) {
                $menu = array(
                            "id"                => $value['id'],
                            "viewable"          => 0,
                            "editable"          => 0,
                            "seq_no"            => 0,
                            "active_flag"       => 0,
                            "delete_date"       => date ( 'Y-m-d H:i:s' )
                );
                $school_menu_table->save($menu);
            }

            // TODO write menu file
            // $this->updateMenuMessageFile($menu_list, $id);

            /** =============MESSAGE MANAGER================**/
            // TODO Store Message File & Message On File
            $message_file_name = $business_type_id[1] . '_' . md5($data->id);
            $lang_setting = ConstantsModel::$lang_setting;
            $lang_file_path = '/lang/' . $lang_setting[$request->language] .'/'.$message_file_name.'.php';
            
            // Find parent message file: sport.php, school.php,....
            $parent_file = $this->getParentFile($business_type_id[0], $request->language);
            
            // update message file name to Pschool table
            $file_row = DB::table('message_file')->where('pschool_id', $data->id)->first();

            $new_file = array(
                'bussiness_type_id' => $business_type_id[0],
                'pschool_id'        => $data->id,
                'lang_code'         => $request->language,
                'message_file_name' => $message_file_name,
            );  
            if (empty($file_row)) { 
                // TODO add new file for pschool
                
                $new_file['parent_id']      = $parent_file->parent_id;     
                $new_file['register_admin'] = Auth::id();     
                $message_file_id = MessageFile::insertGetId ( $new_file );
                
                MessageFile::where('id', $message_file_id)->update(['parent_id'=> DB::raw('CONCAT(parent_id, id, "/")')]);
                
                // Call create new message
                $this->createNewMessageOnscreen($parent_file->id, $message_file_id, $lang_file_path, $menu_ids, true);

            // TODO update message file existed
            } else {
                // Change lang_code or business_type_id => change message file
                if (($file_row->bussiness_type_id != $business_type_id[0]) || ($file_row->lang_code != $request->language)) {
                    // Update message file name

                    $new_file['parent_id']      = $parent_file->parent_id . $file_row->id . '/';     
                    $new_file['update_admin']   = Auth::id();     

                    MessageFile::where('id', $file_row->id)->update($new_file);

                    // delete old file
                    $old_file_path = '/lang/' . $lang_setting[$file_row->lang_code] .'/'.$file_row->message_file_name.'.php';
                    $this->destroy_file($old_file_path); 
                }
                $message_file_id = $file_row->id;
                
            } 
            DB::table('pschool')->where('id', $data->id)->update(['message_file'=>$message_file_name]);
            DB::table('pschool')->where('id', $data->id)
                ->update(array(
                    'zip_code1' => $request->zip_code1,
                    'zip_code2' => $request->zip_code2,
                    'mailaddress' => $request->login_id,
                ));

            // Filter menu list 
            $menu_message_list = json_decode($request->menu_message_list, true);

            $delete_menus      = array();
            $add_menus         = array();
            foreach ($menu_select_copy as $value) {

                if ( array_key_exists($value['menu_name_key'], $menu_message_list) ) {
                // keep message of menu & unset $menu_message_list
                    unset($menu_message_list[$value['menu_name_key']]);
                    
                } else {
                // delete message of menu
                    
                    $delete_menus[] = $value['menu_name_key'];
                }
            }

            // List menu add new
            if (count($menu_message_list) > 0) {
                foreach ($menu_message_list as $key => $value) {
                    $message_value  = isset($value['message_value']) ? $value['message_value'] : '';
                    $comment        = isset($value['comment']) ? $value['comment'] : '';
                    $add_menus[$key] = [$message_value, $comment];
                }
                
            }

            if ($request->has('screen_list') && $request->has('message_list')) {
            //TODO Case admin updated message file
                
                $screen_list    = json_decode($request->screen_list);
                $message_list   = json_decode($request->message_list);

                // TODO update message file
                $this->storeMessage($screen_list, $message_list, $message_file_id, $lang_file_path, 1, $delete_menus, $add_menus);

            } elseif (count($delete_menus) > 0 || count($add_menus) > 0) {
                // Just update menu
//                if (count($delete_menus) > 0) {
//                    Message::where('message_file_id', $message_file_id)->whereIn('message_key', $delete_menus)->delete();
//                }
                // Insert new Item menu (Case Edit)
//                $data2 = array();
//                if (count($add_menus) > 0) {
//                    foreach ($add_menus as $key => $value) {
//                        $message = array(
//                        'message_file_id'   => $message_file_id,
//                        'screen_key'        => 'school.menu',
//                        'screen_value'      => 'メニュー',
//                        'message_key'       => $key, // menu_name_key
//                        'message_value'     => $value[0],
//                        'comment'           => $value[1],
//                        'register_admin'    => 1
//                        );
//
//                        $data2[] = $message;
//                    }
//
//                    Message::insert($data2); //insert chunked data
//                }
                
                $this->create_new_message_file($message_file_name, $request->language);

            }

            // Toran add for changing plan of school

            $planId = $request->m_plan_id;

            if($planId != $data->m_plan_id ){  // only update when plan_id is different

                if(MPlan::canUpdatePlan($data->id, $planId)){

                    $planDetail = MPlan::getPlanDetail($planId);

                    DB::table('pschool')->where('id', $data->id)
                            ->update(array(
                                    'm_plan_id' => $planDetail->id,
                                    'limit_number_register' => $planDetail->number_register,
                                    'limit_number_active' => $planDetail->number_active,
                                    'valid_date' => $planDetail->validation_date,
                            ));

                }else{

                    $message = array(
                            'message'    => "より大きなプランに変更することができます。",
                            'alert-type' => 'error'
                    );

                    return redirect()
                            ->route("voyager.{$dataType->slug}.edit", ['id' => $id])
                            ->with([
                                    'message'    => $message
                            ]);

                }
            }

            //

        } catch (Exception $e) {
            var_dump($e);
        }

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

    //****************************************
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create(Request $request)
    {
        //remove error if exist
        $this->removeErrors($request);

        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        // customize by Thang 2017/04/21
        $this->loadExtendData($dataType->addRows, $dataTypeContent);

        if (session()->has('old_data')) {
            $oldData = session('old_data')[0];
            $this->loadInput($dataTypeContent, $oldData);
            session()->forget('old_data');
        }
        // end customize

        //Get Master menu to assign
        $menuList = MasterMenuTable::getInstance()->getListMenu();
        
        $masterMenuList = array();
        $subMenuList = array();
        foreach ($menuList as $key => $value) {
            $menu_path = explode("/", $value['menu_path']);
            array_pop($menu_path);
            if (count($menu_path) > 1) {
                
                $subMenuList[$menu_path[0]][$value['id']] = $value;
            } else {
                $masterMenuList[$value['id']] = $value;
            }
            
        }

        $language = 2; //日本語
        $businessDivisions = ConstantsModel::$business_divisions_type[$language];
        $countryCode = ConstantsModel::$country_list[$language];

        //Toran add get plan
        $plans = DB::table('m_plan')->select('m_plan.id','plan_name','number_register.category_value as number_register',
                'number_active.category_value as number_active', 'number_institution.category_value as number_institution',
                'm_plan.plan_amount', 'm_plan.validation_date')
                ->leftJoin('m_plan_category as number_register','number_register.id','=','m_plan.number_register_id')
                ->leftJoin('m_plan_category as number_active','number_active.id','=','m_plan.number_active_id')
                ->leftJoin('m_plan_category as number_institution','number_institution.id','=','m_plan.number_institution_id')
                ->whereNull('m_plan.delete_date')
                ->get();

        //
        $applycationType = ConstantsModel::$applycationType;

        $view = 'voyager::bread.edit-add';
        if (view()->exists("Admin.$slug.edit-add")) {
            $view = "Admin.$slug.edit-add";
        }

        return view($view, compact('request','dataType', 'dataTypeContent', 'isModelTranslatable', 'masterMenuList', 'subMenuList', 'lan', 'businessDivisions', 'countryCode','plans', 'applycationType'));
    }

    // POST BRE(A)D
    public function store(Request $request) {

        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        // TODO validation data
        $rules = $this->get_validate_rules($request);
        $messsages = $this->get_validate_message();

        $validator = Validator::make(request()->all(), $rules, $messsages);
        if ($validator->fails()) {
            session()->push('old_data', $request->input());
            $request->offsetSet('errors',$validator->errors());
            return $this->create($request);
            //return redirect()->back()->withErrors($validator->errors());
        }

        $school_menu_table = SchoolMenuTable::getInstance();
        try {
            // TODO store login account
            $login_acc= array (
                'login_id'      => $request->login_id,
                'login_pw'      => md5($request->login_pw),
                'auth_type'     => 2,
                'register_admin' => Auth::id(),
                'active_flag'   => $request->exists('active_flag') ? $request->active_flag : 1,
                'lang_code'     => $request->language,
            );
            $login_acc_id = LoginAccountTable::getInstance()->save($login_acc);
            
            // TODO Custom data to update into Pschool 
            $pschool_code = mt_rand(self::SCHOOL_CODE_LENGTH_MIN,self::SCHOOL_CODE_LENGTH_MAX);
            $cnt = DB::table('pschool')->where('pschool_code', $pschool_code)->count();
            while ($cnt) {
                $pschool_code = mt_rand(self::SCHOOL_CODE_LENGTH_MIN,self::SCHOOL_CODE_LENGTH_MAX);
                $$cnt = DB::table('pschool')->where('pschool_code', $pschool_code)->count();
            }
            $request->offsetSet('pschool_code', $pschool_code);

            $groupId = $request->exists('admin_flag') ? '0' : DB::table('pschool')->max('group_id')+1;
            $request->offsetSet('group_id', $groupId);
            $request->offsetSet('pschool_type_id', 0);
            $request->offsetSet('card_front_img', '');
            $request->offsetSet('card_back_img', '');
            $request->offsetSet('login_account_id', $login_acc_id);
            // zip_code Ex : 123-4567
            $request->offsetSet('zip_code', $request->zip_code1 . $request->zip_code2);
            // $request->city_id: prefid_cityid
            if ($request->exists('city_id') && $request->city_id != '0') {
                $cityId = explode('_', $request->city_id)[1];
                $request->offsetSet('city_id', $cityId);
            }
            $forecastRss = "";
            if ($request->country_code == 81) {
                // $forecastRss = weather_forecast_util::getWeatherRssLinkJP($request);
            } else {
                // $forecastRss = weather_forecast_util::getWeatherRssLinkEn($request);
            }
            $request->offsetSet('forecast_rss', $forecastRss);
            $request->offsetSet('register_date', date ( 'Y-m-d H:i:s' ));
            $request->offsetSet('register_admin', Auth::id());

            // Save business type
            // business_type_id: 1|sports, 2|education, 3|school,....
            $business_type_id = explode('|', $request->business_type_id);
            $request->offsetSet('business_type_id', $business_type_id[0]);

            //
            if(!$request->offsetExists('custom_menu')){
                $request->offsetSet('custom_menu_flag', 0);
            }else{
                $request->offsetSet('custom_menu_flag', 1);
            }
            $request->offsetSet('application_type', $request->application_type);

            $data = $this->insertUpdateDataWithoutValidation($request, $slug, $dataType->addRows, new $dataType->model_name());
            DB::table('pschool')->where('id', $data->id)->update(['application_type'=>$request->application_type]);
            DB::table('pschool')->where('id', $data->id)
                ->update(array(
                    'zip_code1' => $request->zip_code1,
                    'zip_code2' => $request->zip_code2,
                    'mailaddress' => $request->mailaddress,
                ));

            // Save plan detail

            $planId = !empty($request->m_plan_id) ? $request->m_plan_id : null;

            if($planId){

                $request->offsetSet('m_plan_id',$planId);

                $planDetail = MPlan::getPlanDetail($planId);

                DB::table('pschool')->where('id', $data->id)
                        ->update(array(
                                'm_plan_id' => $planDetail->id,
                                'limit_number_register' => $planDetail->number_register,
                                'limit_number_active' => $planDetail->number_active,
                                'valid_date' => $planDetail->validation_date,
                        ));

            }
            //------- MENU ACTION
            // if have custom_menu_flag -> get menus from request
            // else get menus from m_plan_id
            $index = 1;

            if($request->custom_menu == 1){
                $menu_list      = $request->request->get('menu_list', array());
                $viewable_list  = $request->request->get('viewable_list', array());
                $editable_list  = $request->request->get('editable_list', array());
                $menu_ids       = array(); // Use for add message on screen

                foreach ($menu_list as $key => $value) {
                    $menu = array(
                        "pschool_id"        => $data->id,
                        "master_menu_id"    => $value,
                        "viewable"          => isset($viewable_list[$key])? 1: 0,
                        "editable"          => isset($editable_list[$key])? 1: 0,
                        "seq_no"            => $index,
                        "active_flag"       => 1,
                    );
                    $index++;
                    $school_menu_table->save($menu);

                    $menu_ids[] = $value;
                }
            }else{
                $plan_menus = $school_menu_table->getActiveMenuListNew2($planId, array_flip(ConstantsModel::$member_type)['PLAN']);
                $menu_ids       = array(); // Use for add message on screen

                foreach ($plan_menus as $key => $value) {
                    $menu = array(
                        "pschool_id"        => $data->id,
                        "master_menu_id"    => $value['master_menu_id'],
                        "viewable"          => $value['viewable'],
                        "editable"          => $value['editable'],
                        "seq_no"            => $index,
                        "active_flag"       => 1,
                    );
                    $index++;
                    $school_menu_table->save($menu);

                    $menu_ids[] = $value;
                }
            }

            //
            $hierarchy = array (
                        'group_id' => $groupId,
                        'layer' => 2,
                        'pschool_id' => $data->id,
                        'manage_flag' => "1"
            );

            HierarchyTable::getInstance()->save ($hierarchy);

            // $this->updateMenuMessageFile($menu_list, $data->id);
            //end ------- MENU ACTION

            // TODO Store Message File & Message On File
            $message_file_name = $business_type_id[1] . '_' . md5($data->id);
            // update message file name to Pschool table
            DB::table('pschool')->where('id', $data->id)->update(['message_file'=>$message_file_name]);
            
            $message_file = $this->getParentFile($business_type_id[0], $request->language);

            if ($message_file->id) {
                
                $new_file = array(
                    'parent_id'         => $message_file->parent_id,
                    'bussiness_type_id' => $business_type_id[0],
                    'pschool_id'        => $data->id,
                    'lang_code'         => $request->language,
                    'message_file_name' => $message_file_name,
                    'register_admin'    => Auth::id()
                    );                
                $new_message_file_id = MessageFile::insertGetId ( $new_file );
                // update parent_id
                MessageFile::where('id', $new_message_file_id)->update(['parent_id'=> DB::raw('CONCAT(parent_id, id, "/")')]);
         
                $lang_setting = ConstantsModel::$lang_setting;
                $lang_file_path = '/lang/' . $lang_setting[$request->language] .'/'.$message_file_name.'.php';
                
                // Call create new message
                $this->createNewMessageOnscreen($message_file->id, $new_message_file_id, $lang_file_path, $menu_ids, false);
                
            }

        } catch (Exception $e) {
            var_dump($e);
        }
        
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

    //****************************************
    //
    //  Read an item of our Data Type B(R)EAD
    //
    //****************************************
    public function show(Request $request, $id)
    {
        //Get method, if = delete -> return destroy
//        if($request->offsetExists('_method') && $request->_method == 'DELETE'){
//            return $this->destroy($request,$id);
//        }

        // GET THE SLUG, ex. 'posts', 'pages', etc.

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('read_'.$dataType->name);

        $relationships = $this->getRelationships($dataType);
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $dataTypeContent = call_user_func([$model->with($relationships), 'findOrFail'], $id);
            //dd($dataTypeContent );

            $dataTypeContent = call_user_func([
                    $model->whereNull('pschool.delete_date')
                    ->where('C.layer', 2)
                    ->select('pschool.*', 'B.login_id', 'B.active_flag AS login_active_flag', 'C.layer', 'P.name AS pref_name', 'T.name AS city_name' )
                    ->leftJoin('login_account as B', 'pschool.login_account_id', '=', 'B.id')
                    ->leftJoin('hierarchy as C', 'pschool.id', '=', 'C.pschool_id')
                    ->leftJoin('m_pref as P', 'pref_id', '=', 'P.id')
                    ->leftJoin('m_city as T', 'city_id', '=', 'T.id')
                    ->with($relationships), 'findOrFail'], $id);
            //dd($dataTypeContent);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        //Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        $view = 'voyager::bread.read';

        if (view()->exists("Admin.$slug.read")) {
            $view = "Admin.$slug.read";
        }

        $activeFlag = ConstantsModel::$org_status[2];
        $businessDivisions = ConstantsModel::$business_divisions_type[2];
        $countryCode = ConstantsModel::$country_list[2];

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'activeFlag', 'businessDivisions', 'countryCode'));
    }

    //****************************************
    //
    //         Delete an item BREA(D)
    //
    //****************************************
    public function destroy(Request $request, $id)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('delete_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        foreach ($dataType->deleteRows as $row) {
            if ($row->type == 'image') {
               if($data->{$row->field}){
                   $this->deleteFileIfExists('/uploads/'.$data->{$row->field});
               }


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
            // custom by Thang 27/04/2017
            
            if ($row->field == 'login_account_id') {
                DB::table('login_account')->where('id', '=', $data[$row->field])->update(array('delete_date'=>date('Y-m-d H:i:s')));
            }
        }

        DB::table('hierarchy')->where('pschool_id', '=', $data['id'])->update(array('delete_date'=>date('Y-m-d H:i:s')));
        DB::table('school_menu')->where('pschool_id', '=', $data['id'])->update(array('delete_date'=>date('Y-m-d H:i:s')));

        // delete all message on screen
        $message_file = MessageFile::where('pschool_id', $id)->first();
        if($message_file){
            MessageFile::where('pschool_id', $id)->delete();
            Message::where('message_file_id', $message_file->id)->delete();
            // Delete file message
            $lang_setting = ConstantsModel::$lang_setting;
            $lang_file_path = '/lang/' . $lang_setting[$message_file->lang_code] .'/'.$message_file->message_file_name.'.php';
            $this->destroy_file($lang_file_path);
        }



        $data->delete_date = date('Y-m-d H:i:s');
        $data->save()
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
    /**
     * Create new file name "menu.php"
     * @var file
     */
    private function createNewMenuFile($file, $menu_list) {

        $contents = '<?php '.PHP_EOL.PHP_EOL.PHP_EOL.'return [';
        foreach ($menu_list as $key => $value) {
            $contents .= PHP_EOL.'"'.$value['menu_name_key'] .'" => "",';
        }
        $contents .= PHP_EOL.'];' ;
        
        file_put_contents($file, $contents);
    }

    /**
     * assign block message for specific pschool
     * @param $menu_arr, $id
     */
    // private function updateMenuMessageFile($menu_arr, $id) {
    //     // TODO check message file exist or not
    //     $path = resource_path() . '/lang';
    //     $directories = File::directories($path);
    //     $menu_list = MasterMenuTable::getInstance()->getListMenu(array_keys($menu_arr));
    //     foreach ($directories as $key => $directory) {
            
    //         $file = $directory . $this->MENU_FILE;
    //         // TODO copy menu item to specific file
    //         $contents_filter = array();
    //         if (File::exists($file)) {
    //             // $menu_contents : Ex: ["home"=>'HOME', "logout" => 'LOGOUT', ...]
    //             $menu_contents = File::getRequire($file);
    //             $contents_filter = '<?php '.PHP_EOL.PHP_EOL.PHP_EOL.'return [';
        
    //             // TODO check menu item exist or not
    //             $pschool_menu_contents = array();
    //             $pschool_menu_file = $directory . '/menu_' . md5($id) . '.php';
    //             if (!File::exists($pschool_menu_file)) {
    //                 file_put_contents($pschool_menu_file, '');
    //             } else {
    //                 $pschool_menu_contents = File::getRequire($pschool_menu_file);
    //             }

    //             foreach ($menu_list as $key => $value) {
    //                 if (array_key_exists($value['menu_name_key'], $menu_contents)) {
    //                     $message_key    = $value['menu_name_key'];
    //                     // $message_text : if pschool_menu file does not exist, get value from master_menu
    //                     $message_text   = (array_key_exists($message_key, $pschool_menu_contents))?
    //                                     $pschool_menu_contents[$message_key] : $menu_contents[$message_key];

    //                     $contents_filter .= PHP_EOL.'"'. $message_key .'" => "'. $message_text .'",';
    //                 }
    //             }
    //             $contents_filter .= PHP_EOL.'];' ;
    //             // replace content message file
    //             File::put($pschool_menu_file, $contents_filter);
    //         } 
                
    //     }
    // }
    private function loadExtendData(&$dataRows, $data ) {
        foreach ($dataRows as $key=>&$row) {
            
            $options    = array();
            $defaults   = '';
            $language = 2; //日本語

            // active_flag: ['0' => '非アクティブ', '1' => 'アクティブ']; default: 1
            if ('active_flag' == $row->field) {
                $defaults = $data->{$row->field} ? $data->{$row->field} : '1';
                $options = ConstantsModel::$org_status[$language];
            // pref_id: ['pref_id'=>'pref_name', ...]
            } else if ('pref_id' == $row->field) {
                $defaults = $data->{$row->field} ? $data->{$row->field} : '0';
                $prefList = DB::table('m_pref')->get();
                $options = array('0' => "");
                foreach ($prefList as $key => $pref) {
                    $options[$pref->id] = $pref->name;
                }
            // city_id: ['pref_id_city_id'=>'city_name', ...]
            } else if ('city_id' == $row->field) {
                $defaults = $data->{$row->field} ? $data->pref_id."_".$data->{$row->field} : '0';
                $cityList = DB::table('m_city')->get();
                $options = array('0' => "");
                foreach ($cityList as $key => $city) {
                    $options[$city->pref_id.'_'.$city->id] = $city->name;
                }
            // business_divisions: ['1' => '塾','2' => '会員クラブ','3' => '塾本部・支部','4' => '会員クラブ本部・支部']
            } else if ('business_divisions' == $row->field) {
                $defaults = $data->{$row->field} ? $data->{$row->field} : '4';
                $businessDivisions = ConstantsModel::$business_divisions_type[$language];
                $options = array('0' => "");
                foreach ($businessDivisions as $key => $business) {
                    $options[$key] = $business;
                }
            // language:['1' => '英語','2' => '日本語']
            } else if ('language' == $row->field) {
                $defaults = $data->{$row->field} ? $data->{$row->field} : '0';
                $languages = ConstantsModel::$languages_input[2];
                foreach ($languages as $key => $language) {
                    $options[$key] = $language;
                }
            // country_code:['81' => '日本','82' => '韓国','61' => 'オーストラリア','64' => 'ニュージーランド','44' => 'イギリス','1'  => 'カナダ','55' => 'ブラジル']
            } else if ('country_code' == $row->field) {
                $defaults = $data->{$row->field} ? $data->{$row->field} : '81';
                $countryList = ConstantsModel::$country_list[$language];
                foreach ($countryList as $key => $country) {
                    $options[$key] = $country;
                }
            // business_type_id: 1|sports, 2|education, 3|school,....
            } else if ('business_type_id' == $row->field) {
                $business_type_list= DB::table('business_type')->whereNull('delete_date')->get();
                foreach ($business_type_list as $key => $value) {
                    $options[$value->id.'|'.$value->resource_file] = $value->type_name;
                }
            }

            $row->details = json_encode(['default'=>$defaults,'options'=>$options]);
        }
    }

    private function get_validate_rules($request, $data = array()) {
        $rules = [
                    'pschool_code'      => 'required|max:6|unique:pschool',
                    'name'              => 'required|max:255|unique:pschool',
                    'active_flag'       => 'required',
                    // 'zip_code1'         => 'size:3',
                    // 'zip_code2'         => 'size:4',
                    'address'           => 'max:255',
                    'login_id' => 'required|email|max:64|unique:login_account',
                    'login_pw' => 'required|min:8|max:16|regex:/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]+$/',
                    'business_type_id' => 'required',
                ];
        if (isset($data['id'])) {

            $rules['pschool_code']  = 'required|max:6|unique:pschool,pschool_code,'.$data['id'];
            $rules['name']          = 'required|max:255|unique:pschool,name,'.$data['id'];
            $rules['login_id']      = 'required|email|max:64|unique:login_account,login_id,'.$data['login_account_id'];

            if (!$request->has('login_pw')) {
                unset($rules['login_pw']);
            } else {
                $rules['login_pw_confirm'] = 'same:login_pw';
            }
        }
        return $rules;
    }

    private function get_validate_message() {
        $messsages = [
                        'pschool_code.required' => '本部コードは必須です。',
                        'pschool_code.max' => '本部コードは6文字以内で入力してください。',
                        'pschool_code.unique' => '本部コードは既に存在しています。',
                        'name.required' => '本部名は必須です。',
                        'name.max' => '本部名は255文字以内で入力してください。',
                        'name.unique' => '本部名は既に存在しています。',
                        'active_flag.required' => '状態は必須です。',
                        // 'zip_code1.size' => '郵便番号1は3文字で入力してください。',
                        // 'zip_code2.size' => '郵便番号2は3文字で入力してください。',
                        'address.overLength' => '住所は255文字以内で入力してください。',
                        'login_id.required' => 'メールアドレスは必須です。',
                        'login_id.max' => 'メールアドレスは64文字以内で入力してください。',
                        'login_id.email' => 'メールアドレスを正しく入力してください。',
                        'login_id.unique' => '管理者のメールアドレスは既に存在しています。',
                        'login_pw.required' => 'パスワードは必須です。',
                        'login_pw.max' => 'パスワードは16文字以内で入力してください。',
                        'login_pw.min' => 'パスワードは8文字以上で入力してください。',
                        'login_pw.regex' => 'パスワードは半角英数文字または特殊文字(-,_,.,$,#,:,`,!)で入力してください。',
                        // 'login_pw_confirm.required' => 'パスワード（確認）を入力してください。',
                        'login_pw_confirm.same' => '入力されたパスワードが一致しません。',
                        'business_type_id.required' => '業態は必須です。',
                    ];
        return $messsages;
    }

    private function removeErrors($request)
    {
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
    }

    private function loadInput(&$dataTypeContent, $oldData)
    {
        $dataTypeContent->offsetSet('login_id', array_get($oldData, 'login_id'));
        $dataTypeContent->offsetSet('name', array_get($oldData, 'name'));
        $dataTypeContent->offsetSet('active_flag', array_get($oldData, 'active_flag'));
        $dataTypeContent->offsetSet('zip_code1', array_get($oldData, 'zip_code1'));
        $dataTypeContent->offsetSet('zip_code2', array_get($oldData, 'zip_code2'));
        $dataTypeContent->offsetSet('pref_id', array_get($oldData, 'pref_id'));
        $dataTypeContent->offsetSet('city_id', array_get($oldData, 'city_id'));
        $dataTypeContent->offsetSet('address', array_get($oldData, 'address'));
        $dataTypeContent->offsetSet('tel', array_get($oldData, 'tel'));
        $dataTypeContent->offsetSet('fax', array_get($oldData, 'fax'));
        // $dataTypeContent->offsetSet('business_divisions', array_get($oldData, 'business_divisions'));
        $dataTypeContent->offsetSet('logo', array_get($oldData, 'logo'));
        $dataTypeContent->offsetSet('language', array_get($oldData, 'language'));
        $dataTypeContent->offsetSet('country_code', array_get($oldData, 'country_code'));
        $dataTypeContent->offsetSet('application_type', array_get($oldData, 'application_type'));
    }

    /**
    * Load message of parent & Call storeMessage()
    * @param $parent_message_id
    * @param $new_message_file_id
    * @param $lang_file_path
    * @param $menu_ids: id of menu assigned
    */
    private function createNewMessageOnscreen($parent_message_id, $new_message_file_id, $lang_file_path, $menu_ids, $is_update = false) {
        // get all message of parent file
        $screen_list = Message::where('message_file_id', $parent_message_id)->whereNull('message_key')->get();
        
        // TODO select all message on screen ( exclude menu)
        if($is_update){
            $message_list_1 = Message::where('message_file_id', $parent_message_id)->where('screen_key', '<>', 'school.menu')->whereNotNull('message_key')->get();
        }else{
            //        Toran modified for getting all menus
            $message_list_1 = Message::where('message_file_id', $parent_message_id)->whereNotNull('message_key')->get();
        }


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

    /**
     * Ajax call to load all screen & message on screen of specific message file
     *
     * @param $pschool_id
     * @return view
     */
    public function loadScreenList(Request $request) {
        
        $lang_code = $request->lang_code;
        $business_type_id = $request->business_type_id;
        
        // Get message file for Pschool
        $message_file = DB::table('message_file')->where('pschool_id', $request->pschool_id)->whereNull('delete_date')->first();

        $message_file_id = $message_file->id;
        $screen_list = DB::table('message')->where('message_file_id', $message_file_id)->whereNull('message_key')->whereNull('delete_date')->get();

        if ($request->has('message_file_id') && $request->message_file_id != $message_file_id) {
            
            $message_content = DB::select(DB::raw('select m1.id, m1.message_file_id, m1.screen_key, m1.screen_value, m1.message_key, m2.message_value, m2.comment from message as m1 inner join message as m2 on (m1.screen_key = m2.screen_key and m1.message_key = m2.message_key and m2.message_file_id = '.$request->message_file_id.') where m1.message_file_id = '.$message_file_id.' and m1.message_key is not null and m1.delete_date is null'));
            
        } else {
            $message_content = DB::table('message')->where('message_file_id', $message_file_id)->whereNotNull('message_key')->whereNull('delete_date')->get();
        }
        
        
        $message_list = array();

        foreach ($message_content as $value) {
            $message_list[$value->id] = $value;
        }
        
        // get list parent to copy
        // $parent_list = MessageFile::where('lang_code', $lang_code)->where(function ($query) use ($business_type_id) {
        //     $query->where('bussiness_type_id', $business_type_id)->orWhere('bussiness_type_id', 0);
        // })->get();
        $parent_list = MessageFile::where('lang_code', $lang_code)->where('bussiness_type_id', 0)->get();
        
        return view('_parts.message.message_content', compact('screen_list', 'message_list', 'parent_list'));
    }

    /**
    * Ajax call to store updated message content
    * @param $screen_list
    * @param $message_list
    * @return 'success'
    */

    // public function storeUpdatedMessage (Request $request) {

    //     $message_arr = [
    //         'lang_code'         => $request->lang_code,
    //         'bussiness_type_id' => $request->bussiness_type_id,
    //         // 'message_file_name' => $request->message_file_name,
    //         'screen_list'       => $request->screen_list,
    //         'message_list'      => $request->message_list
    //         ];
    //     session()->put('message_arr', $message_arr);

    //     return 'success';

    // }

    /**
     * Export message file csv
     *
     * @param $message_file_id
     * @return success
     */
    public function exportCSV(Request $request) { 
        if ($request->offsetExists('pschool_id')) {
            $message_file = MessageFile::where('pschool_id', $request->pschool_id)->first();

            $this->export_message_csv($message_file->id,$message_file->message_file_name);
        }
        
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
}
