<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use App\Model\BusinessTypeTable;
use App\Message;
use App\MessageFile;
use App\Lang;
use App\ConstantsModel;
use File;
use Validator;

class MessageFileController extends VoyagerBreadController
{   use BreadFileHandler;

    private $lang_list = array();
    public function __construct()
    {
       $this->lang_list = ConstantsModel::$languages_input[2];
    }

    public function index(Request $request)
    {   
        if (session ()->has('_old_input')) {
            $request->session()->forget ('_old_input');
        }
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
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
                $dataTypeContent = call_user_func([$model->where('delete_date', NULL)->with($relationships)->latest(), $getter]);
            } else {
                $dataTypeContent = call_user_func([$model->where('delete_date', NULL)->with($relationships)->orderBy('id', 'DESC'), $getter]);
            }

            //Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name)->where('delete_date', NULL), $getter]);
        }
        // TODO change value data to show
        $business_type_list= BusinessTypeTable::getInstance()->getActiveList();
        $business_type_select = array();
        foreach ($business_type_list as $value) {
            $business_type_select[$value['id']] = $value['resource_file'];
        }
        foreach ($dataTypeContent as &$data) {
            if ($data->bussiness_type_id && array_key_exists($data->bussiness_type_id, $business_type_select)) {
                $data->bussiness_type_id = $data->bussiness_type_id . '|' .$business_type_select[$data->bussiness_type_id];
            }

            if ($data->parent_id) {
                $data->parent_id = $data->lang_code . '|' . $data->parent_id;
            }
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

        // bussiness_type_id : bussiness_type_id|message_file_name Ex: Ex: 1|sports, 2|education, 3|school,....
        $dataTypeContent->bussiness_type_id = $dataTypeContent->bussiness_type_id . '|' .$dataTypeContent->message_file_name;
        $dataTypeContent->parent_id = $dataTypeContent->lang_code. '|' . $dataTypeContent->parent_id;

        if ($dataTypeContent->message_file_name == 'common') {
            $this->create_new_message_file('common', $dataTypeContent->lang_code);
        }

        $view = 'voyager::bread.read';

        if (view()->exists("Admin.$slug.read")) {
            $view = "Admin.$slug.read";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
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
        // bussiness_type_id : bussiness_type_id|message_file_name Ex: Ex: 1|sports, 2|education, 3|school,....
        // Edit => parent_id: itsefl
        $dataTypeContent->bussiness_type_id = $dataTypeContent->bussiness_type_id . '|' .$dataTypeContent->message_file_name;
        $dataTypeContent->parent_id = $dataTypeContent->lang_code. '|' . $dataTypeContent->id;
        $view = 'voyager::bread.edit-add';

        if (view()->exists("Admin.$slug.edit-add")) {
            $view = "Admin.$slug.edit-add";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {   
        // TODO custom value input
        $update_flag = $this->filterDataToSave($request, $id);

        // エラー表示用の配列
        // Unique (bussiness_type_id, lang_code)
        $rules = [ 
                'bussiness_type_id' => 'required|unique:message_file,bussiness_type_id,'.$id.',id,lang_code,' . $request->lang_code,
                'parent_id'         => 'required' 
        ];
        $messsages = array (
                'bussiness_type_id.required' => '業態は必須です。', // TODO get msg from resource files
                'bussiness_type_id.unique' => '業態と言語は存在しました。', 
                'parent_id.required' => 'ファイルコピーは必須です。' 
        );
        
        $validator = Validator::make ( request ()->all (), $rules, $messsages );
        
        if ($validator->fails ()) {
            return redirect ()->back ()->withInput ()->withErrors ( $validator->errors () );
        }

        $slug = $this->getSlug($request);

        
        
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // TODO save parent_id
        // Step1: Save parent_id of parent (Done at filterDataToSave), Step 2: Update add itself id
        if ($update_flag) {
            MessageFile::where('id', $data->id)->update(['parent_id'=> DB::raw('CONCAT(parent_id, id, "/")')]);
        }

        // TODO save screen & message on screen
        if ($request->exists('screen_list') && $request->exists('message_list')) {
            // check if exist message
            $message_check = Message::where('message_file_id', $data->id)->first();
            if (count($message_check) > 0) {
                // Edit
                $this->storeMessage($request, $data->id, 1);
            } else {
                // Insert New
                $this->storeMessage($request, $data->id);
            }
        }
        if (session ()->has('_old_input')) {
            $request->session()->forget ('_old_input');
        }
        if (session()->has('errors')) {
            $request->session()->forget('errors');
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

        if (view()->exists("Admin.$slug.edit-add")) { 
            $view = "Admin.$slug.edit-add";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        // TODO custom value input
        $this->filterDataToSave($request);

        // エラー表示用の配列
        // Unique (bussiness_type_id, lang_code)
        $rules = [ 
                'bussiness_type_id' => 'required|unique:message_file,bussiness_type_id,NULL,id,lang_code,' . $request->lang_code,
                'parent_id'         => 'required' 
        ];
        $messsages = array (
                'bussiness_type_id.required' => '業態は必須です。', // TODO get msg from resource files
                'bussiness_type_id.unique' => '業態と言語は存在しました。', 
                'parent_id.required' => 'ファイルコピーは必須です。',

        );
        
        $validator = Validator::make ( request ()->all (), $rules, $messsages );
        
        if ($validator->fails ()) {
            return redirect ()->back ()->withInput ()->withErrors ( $validator->errors () );
        }
        
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        // TODO save parent_id
        // Step1: Save parent_id of parent (Done at filterDataToSave), Step 2: Update add itself id
        MessageFile::where('id', $data->id)->update(['parent_id'=> DB::raw('CONCAT(parent_id, id, "/")')]);


        // TODO save screen & message on screen
        if ($request->exists('screen_list') && $request->exists('message_list')) {
            $this->storeMessage($request, $data->id);
        }
        if (session ()->has('_old_input')) {
            $request->session()->forget ('_old_input');
        }
        if (session()->has('errors')) {
            $request->session()->forget('errors');
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

    private function storeMessage($request, $message_file_id, $mode=null) {
        // TODO store screen name
        $screen_list = json_decode($request->screen_list, true);
        $screen_arr = array();
        $ids= array();
        foreach ($screen_list as $value) {
            $screen = array(
                'message_file_id'   => $message_file_id,
                'screen_key'        => $value['screen_key'],
                'screen_value'      => $value['screen_value']
            );
            if (!is_null($mode)) { //編集
                // $screen['id']               = $value['id'];
                // $screen['update_admin']     = 1;
                // $message_tbl->fill($screen)->save();
                Message::where('id', $value['id'])->update($screen);

                $ids[] = $value['id'];
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
        $message_list = json_decode($request->message_list, true);
        $data = array();
        foreach ($message_list as $value) {
            $message = array(
                'message_file_id'   => $message_file_id,
                'screen_key'        => $value['screen_key'],
                'screen_value'      => $value['screen_value'],
                'message_key'       => $value['message_key'],
                'message_value'     => $value['message_value'],
                'comment'           => $value['comment']
                );
            if (!is_null($mode)) { //編集
                // $message['id']               = $value['id'];
                // $message['update_admin']     = 1;
                Message::where('id', $value['id'])->update($message);

            }else {
                $message['register_admin']   = 1;
            }

            $data[] = $message;
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
        
        $lang_setting = ConstantsModel::$lang_setting;
        $lang_file_path = '/lang/' . $lang_setting[$request->lang_code] .'/'.$request->message_file_name.'.php';
        $this->destroy_file($lang_file_path);
        $contents = $this->write_content_message($screen_arr, $data);
        $this->create_file($lang_file_path, $contents);

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
        // delete all message on screen
        Message::where('message_file_id', $id)->delete();
        // Delete file message
        $lang_setting = ConstantsModel::$lang_setting;
        $message_file  = MessageFile::where('id', $id)->first();

        $lang_file_path = '/lang/' . $lang_setting[$message_file->lang_code] .'/'.$message_file->message_file_name.'.php';
        $this->destroy_file($lang_file_path); 


        $data = $data->destroy($id)
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
     * set options to dataRow's details
     *
     * @param object &$dataRows
     * @param object $id
     * @param $mode : 1: create, 2: edit, 3: other
     * @return void
     */
    protected function changeOptionInitData(&$dataRows, $mode, $id=null) {
        foreach ($dataRows as $key=>&$row) {
            
            $options    = array();
            $defaults   = '';
            if ('parent_id' == $row->field) {
                if ($mode == 3) {
                    $options[0] = null;
                }
                // $options: lang_code|message_file_id; Ex: 1|1, 2|1,..
                $message_file = DB::table('message_file')->where('pschool_id', 0)->whereNull('delete_date')->whereNotNull('message_file_name')->get();
                foreach ($message_file as $key => $value) {
                    $options[$value->lang_code . '|' . $value->id] = $value->message_file_name ;
                }
            }

            if ('lang_code' == $row->field) {
                // $options: １:英語、２：日本語,...
                $options = $this->lang_list;
            }

            if ('bussiness_type_id' == $row->field) {

                // $options: bussiness_type_id|resource_file; Ex: 1|sports, 2|education, 3|school,....
                // switch ($mode) {
                //     case 1: //登録
                //         // load business type which have not created message file yet
                //         $business_type_list= DB::table('business_type as b')->leftJoin('message_file as m', function($join) {
                //             $join->on('b.id', '=', 'm.bussiness_type_id');
                //             $join->whereNull('m.delete_date');
                //         })->select('b.*')->whereNull('b.delete_date')->whereNull('m.id')->get();
                        
                //         break;
                //     case 2: //編集
                //         // load business type which have not created message file yet
                //         // And itself
                //         $business_type_list= DB::table('business_type as b')->leftJoin('message_file as m', function($join) {
                //             $join->on('b.id', '=', 'm.bussiness_type_id');
                //             $join->whereNull('m.delete_date');
                //         })->select('b.*')->whereNull('b.delete_date')->whereNull('m.id')->orWhere('m.id', $id)->get();
                //         break;
                //     default:
                //         $options[0] = null;
                //         // get all
                //         $business_type_list= DB::table('business_type')->whereNull('delete_date')->get();
                //         break;
                // }
                if ($mode == 3) {
                    $options[0] = null;
                }
                $business_type_list= DB::table('business_type')->whereNull('delete_date')->get();
                
                foreach ($business_type_list as $key => $value) {
                    $options[$value->id.'|'.$value->resource_file] = $value->type_name;
                }
            }
            

            $row->details = json_encode(['default'=>$defaults,'options'=>$options]);
            
        }
    }

    /**
     * filter request input to save DB
     *
     * @param object &$dataRows
     * @param object $id
     * @param $mode : 1: create, 2: edit, 3: other
     * @return void
     */
    private function filterDataToSave(&$request, $id=null) {
        $up_parent_id_flag = true;
        // $request->parent_id : lang_code|message_file_id; Ex: 1|1, 2|1,..
        $parent_id = explode('|', $request->parent_id);
        if (isset($parent_id[1])) {
            // TODO Case Edit if select itself => do not save
            // TODO get selected parent_id to select parentofparent
            $parent_file = MessageFile::where('id', $parent_id[1])->first();
            // Step1: Save parent_id of parent, Step 2: Update add itself id (After insert)
            // parent_id :Ex: 1/2/4/7..
            $request->offsetSet('parent_id', $parent_file->parent_id);
            if ($parent_id[1] == $id) {
                // Case edit if select isself
                $up_parent_id_flag = false;
            } 
        }
        // cut prefix message file name Ex: school.php -> school
        $message_file_name = explode('.', $request->message_file_name);
        if (isset($message_file_name[0])) {
            $request->offsetSet('message_file_name', $message_file_name[0]);
        }

        // $request->bussiness_type_id: bussiness_type_id|resource_file: Ex: 1|sports, 2|education, 3|school,....
        $bussiness_type_id = explode('|', $request->bussiness_type_id);
        if (isset($bussiness_type_id[0])) {
            $request->offsetSet('bussiness_type_id', $bussiness_type_id[0]);
        }

        // 
        if ($id) {
            $request->offsetSet('update_admin', 1);
        } else {
            $request->offsetSet('register_admin', 1);
        }

        return $up_parent_id_flag;
    }

    
    /**
     * Ajax call to load all screen & message on screen of specific message file
     *
     * @param $message_file_id
     * @return view
     */
    public function loadScreenList(Request $request) {
        
        // TODO load screen list & message list of $request->id
        // Case Edit: Select another message_file_id to copy 
        $screen_list = array();
        $message_content = array();
        if ($request->has('id') && $request->id != $request->message_file_id) {
            $screen_list = DB::table('message')->where('message_file_id', $request->id)->whereNull('message_key')->whereNull('delete_date')->get();
            
            // TODO Merge message_value, comment from parent into message list
            $message_content = DB::select(DB::raw('select m1.id, m1.message_file_id, m1.screen_key, m1.screen_value, m1.message_key, m2.message_value, m2.comment from message as m1 inner join message as m2 on (m1.screen_key = m2.screen_key and m1.message_key = m2.message_key and m2.message_file_id = '.$request->message_file_id.') where m1.message_file_id = '.$request->id.' and m1.message_key is not null and m1.delete_date is null'));
            
        } 
        
        if (count($screen_list) == 0 && count($message_content) == 0) {
            // TODO load screen list & message list of $request->message_file_id
            $screen_list = DB::table('message')->where('message_file_id', $request->message_file_id)->whereNull('message_key')->whereNull('delete_date')->get();

            $message_content = DB::table('message')->where('message_file_id', $request->message_file_id)->whereNotNull('message_key')->whereNull('delete_date')->get();
        }
        $message_list = array();

        foreach ($message_content as $value) {
            $message_list[$value->id] = $value;
        }

        return view('_parts.message.message_content', compact('screen_list', 'message_list'));
    }

    /**
     * Export message file csv
     *
     * @param $message_file_id
     * @return success
     */
    public function exportCSV(Request $request) { 
        if ($request->offsetExists('message_file_id')) {
            $message_file = MessageFile::where('id', $request->message_file_id)->first();

            $this->export_message_csv($request->message_file_id,$message_file->message_file_name);
        }
        
    }
}
