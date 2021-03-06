<?php

namespace App\Http\Controllers\Admin;

use App\Common\Constants;
use App\MPlanCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

class MPlanCategoryController extends VoyagerBreadController
{
    public function index(Request $request)
    {
        if (session ()->has('_old_input')) {
            $request->session()->forget ('_old_input');
        }
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->changeOptionInitData($dataType->browseRows, 3);

        // Check permission
        Voyager::canOrFail('browse_'.$dataType->name);
        $category_types = Constants::CATEGORY_TYPES;
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

        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($model);

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable','category_types'));
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
        // 2017/5/24 Tung fix bug clear errors
        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
        $slug = $this->getSlug($request);
        $category_types = Constants::CATEGORY_TYPES;
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

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable','category_types'));
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        //Toran check when edit category

        if(MPlanCategory::canEditDeleteCategory($data->id)){

            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            $message = array(
                    'message'    => "「{$dataType->display_name_singular}」が変更されました。",
                    'alert-type' => 'success'
            );

            if (session ()->has('_old_input')) {
                $request->session()->forget ('_old_input');
            }
        }else{

            $message = array(
                    'message'    => "この「{$dataType->display_name_singular}」は他の施設で使用中です。",
                    'alert-type' => 'error'
            );

        }

        //

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

        //Get category type from constant

        $category_types = Constants::CATEGORY_TYPES;

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

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable','category_types'));
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('add_'.$dataType->name);

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        if($data){
            if (session ()->has('_old_input')) {
                $request->session()->forget ('_old_input');
            }
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

        //Toran check when edit category

        if(MPlanCategory::canEditDeleteCategory($data->id)){

            $data =DB::table('m_plan_category')->where('id',$id)->update(array('delete_date'=>date('Y-m-d H:i:s')))
                    ? $message = array(
                            'message'    => "「{$dataType->display_name_singular}」が削除されました。",
                            'alert-type' => 'success'
                    )
                    : $message = array(
                            'message'    => "エラーが発生しました。削除できません。",
                            'alert-type' => 'error'
                    );

        }else{

            $message = array(
                    'message'    => "この「{$dataType->display_name_singular}」は他の施設で使用中です。",
                    'alert-type' => 'error'
            );

        }

        //

        return redirect()->route("voyager.{$dataType->slug}.index")->with(['message' => $message]);
    }
}
