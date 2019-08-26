<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\MasterMenuTable;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use Validator;
use File;
use App\Lang;

class MasterMenuController extends VoyagerBreadController

{
    private $PATH = 'menu_path';
    private $MENU_FILE = '/menu.php';
    protected static $LANG_URL = 'master_menu'; 

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

    //
    //      Browse our Data Type (B)READ
    //
    //****************************************

    public function index(Request $request)
    {   
        if (session()->has('errors')) {
            $request->session()->forget('errors');
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
        //*****customize by kieu*****//
        // handle sub menu
        $subMenuList = array();
        foreach ($dataTypeContent as $key => $value) {
            $menu_path = explode("/", $value->menu_path);
            array_pop($menu_path);
            if (count($menu_path) > 1) {
                
                $subMenuList[$menu_path[0]][$key] = $value;
                unset($dataTypeContent[$key]);
            }
            
        }
        //<--***customize***-->/
        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($model);

        $view = 'voyager::bread.browse';

        if (view()->exists("Admin.$slug.browse")) {
            $view = "Admin.$slug.browse";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'subMenuList'));
    }

    //***************************************
    //  Edit an item of our Data Type BR(E)AD
    //
    //****************************************

    public function edit(Request $request, $id)
    {   
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        //*****customize by kieu*****//
        $this->loadParentMenuList($dataType->editRows, $id);
        $subMenuList = MasterMenuTable::getInstance()->getChildList();
        $subMenuList = json_encode($subMenuList);

        $defaultList = DB::table('master_menu')->where('default_flag', '1')->get();
        //<--***customize***-->/
        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $relationships = $this->getRelationships($dataType);

        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? app($dataType->model_name)->with($relationships)->findOrFail($id)
            : DB::table($dataType->name)->where('id', $id)->first(); // If Model doest exist, get data from table name
        
        //*****customize by kieu*****//
        $menu_path = explode("/", $dataTypeContent->menu_path);
        array_pop($menu_path);
        if (count($menu_path) > 1) {
            $dataTypeContent->offsetSet($this->PATH, $menu_path[0]);
        }
        //<--***customize***-->/

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        //メッセージ多言語対応
        $resourceFolder = $this->getResourceLang($dataTypeContent->menu_name_key);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("Admin.$slug.edit-add")) {
            $view = "Admin.$slug.edit-add";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'subMenuList', 'defaultList', 'resourceFolder'));
    }
    
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        
        // エラー表示用の配列
        $rules = ['menu_name_key' => 'required', 'action_url' => 'required', 'icon_url' => 'required'];
        $messsages = array('menu_name_key.required' => 'メニュー名キーは必須です。', // TODO get msg from resource files
            'action_url.required' => 'アクションURLは必須です。',
            'icon_url.required' => 'アイコンURLは必須です。'
        );
        $validator = Validator::make(request()->all(), $rules, $messsages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        //*****customize by kieu*****//
        //expect custom 'menu_path' = '1/';
        $parent_id = $request->input($this->PATH);

        $path = $this->getMenuPath($request, $data->id);
        $request->offsetSet($this->PATH, $path);
        //<--***customize***-->/
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);
        
        //update sub_sequence_no
        if ($parent_id != 0) {
            $child_list = MasterMenuTable::getInstance()->getChildList($parent_id); 
            $child_list = array_slice($child_list, 1, count($child_list), true);

            $seq_lst = array();
            foreach ($child_list as $key => $value) {
                $seq_lst[$value['id']] = $key;
            }
            
            MasterMenuTable::getInstance()->updateSubSequenceNo($seq_lst);
        }

        // TODO update value of menu_name_key into message file
        $this->updateMenuMessageFile($request);

        $message = array(
            'message'    => "「{$dataType->display_name_singular}」が変更されました。",
            'alert-type' => 'success'
        );
        session()->push('message', $message);
        return redirect()
            ->route("voyager.{$dataType->slug}.edit", ['id' => $id]);
            
    }

    //***************************************
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************
	public function create(Request $request)
    {   
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
		$slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        //*****customize by kieu*****//
        $this->loadParentMenuList($dataType->addRows);
        $subMenuList = MasterMenuTable::getInstance()->getChildList();
        $subMenuList = json_encode($subMenuList);

        $defaultList = DB::table('master_menu')->where('default_flag', '1')->get();
        //<--***customize***-->/

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);

        //メッセージ多言語対応
        $resourceFolder = $this->getResourceLang();

        $view = 'voyager::bread.edit-add';

        if (view()->exists("Admin.$slug.edit-add")) {
            $view = "Admin.$slug.edit-add";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'subMenuList', 'defaultList', 'resourceFolder'));
	}

    // POST BRE(A)D
    public function store(Request $request)
    {
        
        // エラー表示用の配列
        $rules = ['menu_name_key' => 'required|unique:master_menu', 'action_url' => 'required', 'icon_url' => 'required'];
        $messsages = array('menu_name_key.required' => 'メニュー名キーは必須です。', 
            'menu_name_key.unique' => 'すでに登録されています。', 
            'action_url.required' => 'アクションURLは必須です。',
            'icon_url.required' => 'アイコンURLは必須です。'
        );
        $validator = Validator::make(request()->all(), $rules, $messsages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        } 
        
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);
        
        //*****customize by kieu*****//
        //sort sub_seq_no
        $parent_id = $request->input($this->PATH);
        //expect custom 'menu_path' = '1/';
        $last_id = DB::table('master_menu')->max('id');
        $path = $this->getMenuPath($request, (++$last_id));
        $request->offsetSet($this->PATH, $path);
        

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
        
        //update sub_sequence_no
        if ($parent_id != 0) {
            $child_list = MasterMenuTable::getInstance()->getChildList($parent_id); 
            $child_list = array_slice($child_list, 1, count($child_list), true);

            $seq_lst = array();
            foreach ($child_list as $key => $value) {
                $seq_lst[$value['id']] = $key;
            }

            MasterMenuTable::getInstance()->updateSubSequenceNo($seq_lst);
        }
        
        // TODO append menu_key & value into message file
        $this->updateMenuMessageFile($request);
        
        $message = array(
            'message'    => "「{$dataType->display_name_singular}」が登録されました。",
            'alert-type' => 'success'); 
        session()->push('message', $message);
        return redirect()
            ->route("voyager.{$dataType->slug}.edit", ['id' => $data->id]);
    }
    
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
        //*****customize by kieu*****//
        //check menu has child
        $child_list = MasterMenuTable::getInstance()->getChildList($data->id);
        //<--***customize***-->/

        if (count($child_list) > 1) {
            $message = array(
                'message'    => "先にサブメニューを作成してください。",
                'alert-type' => 'error',
                );
        } else {
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

            $data = $data->destroy($id)
            ? $message = array(
                'message'    => "「{$dataType->display_name_singular}」が削除されました。",
                'alert-type' => 'success',
            )
            : $message = array(
                'message'    => "エラーが発生したため処理できませんでした。 ",
                'alert-type' => 'error',
            );

        }

        return redirect()->route("voyager.{$dataType->slug}.index")->with(['message' => $message]);
    
    }
    /**
     * set options to dataRow's details
     *
     * @param object &$dataType->addRows | $dataType->editRows
     * @param object $id
     *
     * @return void
     */
    private function loadParentMenuList(&$dataRows, $id=null) {
        foreach ($dataRows as $key=>&$row) {
            
            $options    = array();
            $defaults   = '';
            if ($this->PATH == $row->field) {
                $master_menu_lst = MasterMenuTable::getInstance()->getParentMenuList($id);
                $options = ['0'=>null];
                foreach ($master_menu_lst as $idx => $rec) {
                    $options[$rec['id']] = $rec['menu_name_key'];
                }
            }
            if ('editable' == $row->field) {
                $defaults = '1';
                $options = array('0'=>'出来ない', '1'=>'出来る');
            }

            if ('default_flag' == $row->field) {
                $options = array('0'=>'いいえ', '1'=>'はい');
            }
            
            $row->details = json_encode(['default'=>$defaults,'options'=>$options]);
            
        }
    }

    private function getMenuPath($request, $node_id) {
        $path =($node_id).'/';
        if ((int)$request->input($this->PATH) != 0) {
            $path = $request->input($this->PATH) .'/'.$path;
        }

        return $path;
    }
    
    /**
     * get all folder in resource/lang and display value of menu_name_key
     * @var menu_name_key=null
     * @return resourceFolder
     */
    private function getResourceLang ($menu_name_key=null) {
        $path = resource_path() . '/lang';
        $directories = File::directories($path);
        $resourceFolder = array();
        foreach ($directories as $key => $directory) {
            // $locale :  Ex: "en", "jp", "vn"
            $locale = substr(str_replace($path, '', $directory), 1) ;
            $resourceFolder[$locale] = "";
            if (!is_null($menu_name_key)) {
              // check file exists or not
                $file = $directory . $this->MENU_FILE;
                if (!File::exists($file)) {
                    $this->createNewMenuFile($file);
                } 
                // $contents : Ex: ["home"=>'HOME', "logout" => 'LOGOUT', ...]
                $contents = File::getRequire($file);

                // $resourceFolder : ["en"=>"menu name", "jp" => "menu name"]
                if (array_key_exists($menu_name_key, $contents)) {
                    $resourceFolder[$locale] = $contents[$menu_name_key];
                }
            }
            
        }

        return $resourceFolder;
    }

    /**
     * Create new file name "menu.php"
     * @var file
     */
    private function createNewMenuFile($file) {

        $menu_list = MasterMenuTable::getInstance()->getListMenu();
        $contents = '<?php '.PHP_EOL.PHP_EOL.PHP_EOL.'return [';
        foreach ($menu_list as $key => $value) {
            $contents .= PHP_EOL.'"'.$value['menu_name_key'] .'" => "",';
        }
        $contents .= PHP_EOL.'];' ;
        
        file_put_contents($file, $contents);
    }

    /**
     * add new or update value of menu_name key
     * @var request
     */
    private function updateMenuMessageFile ($request) {
         // $request->$resource_content: Ex: ["en" => "menu name 1", "jp" => "menu name 2", ....]
        foreach ($request->resource_content as $key => $value) {
            $path = resource_path() . '/lang/' .$key;
            $file = $path . $this->MENU_FILE;
            if (!File::exists($file)) {
                $this->createNewMenuFile($file);
            } 
            // $contents_map: ['home'=>'HOME', 'logout'=>'LOGOUT', ...]
            $contents_map = File::getRequire($file); 
            $contents_map[$request->menu_name_key] = $value;
            
            // rewrite message content
            $contents_new = '<?php '.PHP_EOL.PHP_EOL.PHP_EOL.'return [';
                foreach ($contents_map as $key => $value) {
                    $contents_new .= PHP_EOL.'"'. $key .'" => "'. $value .'",';
                }
            $contents_new .= PHP_EOL.'];' ;

            // replace content message file
            File::put($file, $contents_new);
        }
    }
    
    /**

    */
    public function updateMenuMessageContent($request, $mode=null) {
        // TODO update into 
        // $lang_setting ['1' => 'en','2' => 'jp',...]
        $lang_setting = ConstantsModel::$lang_setting;
        if (!is_null($mode)) { //update
            
        } else { //add new
             // $request->$resource_content: Ex: ["en" => "menu name 1", "jp" => "menu name 2", ....]
            foreach ($request->resource_content as $key => $value) {
                $lang_code = array_search($key, $lang_setting);
                if ($lang_code) {
                    $abc = DB::select(DB::raw('select distinct(message_file_id) from message'));
                    $abcd = DB::select(DB::raw('select id from message_file where parent_id like "1/%"')); // nguoc lai 
                }
            }
        }
    }
}
