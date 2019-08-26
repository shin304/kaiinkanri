<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use App\Lang;
use DB;
use File;
use Validator;
use DateTime;


class NotifyController extends VoyagerBreadController {

    use BreadFileHandler;
    protected static $LANG_URL = 'oshirasekanri';
    private $_notify_search_name = [
        'date_from',
        'date_to',
        'process'
    ];
    private $_notify_search_session_key = 'notify_search_form';

    /**Create sprint list notification,search notification
     * @param Request $request
     * @return
     */
    public function index(Request $request) {

        if (session()->has('_old_input')) {
            session()->forget('_old_input');
        }
        $slug = 'oshirasekanri';
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        Voyager::canOrFail('browse_' . $dataType->name);
        $getter = $dataType->server_side ? 'paginate' : 'get';
        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        // Check if BREAD is Translatable
        $view = 'voyager::bread.browse';
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $relationships = $this->getRelationships($dataType);
            if ($model->timestamps) {
                $dataTypeContent = call_user_func([
                    $model->where('delete_date', NULL)->with($relationships)->orderBy('register_date', 'DESC')->orderBy('update_date', 'DESC')->latest(),
                    $getter
                ]);
            } else {
                $dataTypeContent = call_user_func([
                    $model->where('delete_date', NULL)->with($relationships)->orderBy('register_date', 'DESC')->orderBy('update_date', 'DESC'),
                    $getter
                ]);
            }
            //Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([
                DB::table($dataType->name)->where('delete_date', NULL)
                    ->orderBy('register_date', 'DESC')->orderBy('update_date', 'DESC')->orderBy('update_date', 'DESC'),
                $getter
            ]);
        }
        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($model);
        //search notification
        $this->_initSearchDataFromSession($this->_notify_search_name, $this->_notify_search_session_key);
        if ($request->offsetExists('search_button') || session($this->_notify_search_session_key)) {
            if (($request->date_from || $request->date_to)) {
                $date_from = date("Y-m-d H:i", strtotime($request->input('date_from')));
                $date_to = date("Y-m-d H:i", strtotime($request->input('date_to')));
                $dayAfter = (new DateTime($date_to))->modify('+1 day')->format('Y-m-d');
                if (! empty($request->input('date_from')) && ! empty($request->input('date_to'))) {
                    $dataTypeContent = $dataTypeContent->where('register_date', '>=', $date_from)->where('register_date', '<', $dayAfter);
                } elseif (! empty($request->input('date_from'))) {
                    $dataTypeContent = $dataTypeContent->where('register_date', '>=', $date_from);
                } else {
                    $dataTypeContent = $dataTypeContent->where('register_date', '<', $dayAfter);
                }
            }
            if ($request->process) {
                $process = $request->process;
                $dataTypeContent = collect($dataTypeContent)->filter(function ($item) use ($process) {

                    // replace stristr with your choice of matching function
                    return false !== stristr($item->process, $process);
                });
            }
        }
        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }
        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));

    }

    /**
     * Create sprint edit notification
     * @param Request $request
     * @param $id       id notification
     * @return view 'voyager::notify.edit-add'
     */
    public function edit(Request $request, $id) {

        if (session()->has('errors')) {
            $request->session()->forget('errors');
        }
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->changeOptionInitData($dataType->editRows, 2, $id);
        // Check permission
        Voyager::canOrFail('edit_' . $dataType->name);
        $relationships = $this->getRelationships($dataType);
        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? app($dataType->model_name)->with($relationships)->findOrFail($id)
            : DB::table($dataType->name)->where('id', $id)->first(); // If Model doest exist, get data from table name
        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);
        $list_pschool = DB::table('pschool')->select('pschool.*', 'm_plan.plan_name')->leftJoin('m_plan', 'm_plan.id', '=', 'pschool.m_plan_id')->get();
        //get pschool in notify
        $notify_pschools = DB::table('system_log_pschool')->where('notify_id', $id)->where('active_flag', 1)->get();
        //Get list pschool of notify.
        $list_pschool_notify = array ();
        $list_pschool_notify_id = array ();
        foreach ($notify_pschools as $notify_pschool) {
            $list_pschool_notify[] = DB::table('pschool')->where('id', $notify_pschool->pschool_id)->first();
        }
        if ($list_pschool_notify) {
            foreach ($list_pschool_notify as $pschool_notify) {
                $list_pschool_notify_id[] = $pschool_notify->id;
            }
        }
        if ($request->errors) {
            $list_pschool_notify_id = $request->list_pschool_id;
        }
        $view = 'voyager::bread.edit-add';
        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }
        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'list_pschool', 'list_pschool_notify_id'));
    }

    /**
     * Create sprint add notification
     * @param Request $request
     * @return view 'voyager::notify.edit-add'
     */
    public function create(Request $request) {

        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);
        //Get data base on slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $this->changeOptionInitData($dataType->addRows, 1);
        // Check permission
        Voyager::canOrFail('add_' . $dataType->name);
        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? new $dataType->model_name()
            : false;
        // Check if BREAD is Translatable
        $isModelTranslatable = isBreadTranslatable($dataTypeContent);
        $list_pschool = DB::table('pschool')->get();
        $view = 'voyager::bread.edit-add';
        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }
        $list_pschool_notify_id=array();
        if ($request->errors) {
            $list_pschool_notify_id = $request->list_pschool_id;
        }
        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'list_pschool','list_pschool_notify_id'));
    }

    /**
     * Save data notification register
     * @param Request $request
     * @return list notify
     */
    public function store(Request $request) {   // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        Voyager::canOrFail('add_' . $dataType->name);
        $rules = $this->get_validate_rules($request);
        $messsages = $this->get_validate_message();
        //validate data from form
        $validator = Validator::make(request()->all(), $rules, $messsages);
        if ($validator->fails()) {
            session()->push('old_data', $request->input());
            $request->offsetSet('errors', $validator->errors());
            $request->offsetSet('list_pschool_id', $request->list_pschool_id);
            return $this->create($request);
        }
        DB::beginTransaction();
        try {//Save data into system_log table
            $data = array (
                'process'        => $request->process,
                'message'        => $request->message,
                'status'         => $request->exists('status') ? $request->status : 1,
                'start_date'     =>!empty($request->input('date_from')) ? date('Y-m-d H:i', strtotime($request->input('date_from'))):null,
                'end_date'       => !empty($request->input('date_to')) ? date('Y-m-d H:i', strtotime($request->input('date_to'))):null,
                'register_date'  => date('Y-m-d H:i:s'),
                'register_admin' => Auth::id(),
            );
            //if use choose calendar_flag
            if($request->offsetExists('calendar_flag')&&$request->calendar_flag==1){
                $data['calendar_flag']      = empty($request->calendar_flag) ? 0 : 1;
                $data['calendar_color']     =$request->calendar_color;
                $data['start_calendar_dis'] =$request->start_display_calendar;
                $data['end_calendar_dis']   =$request->end_display_calendar;
            }
            $id = DB::table('system_log')->insertGetId($data);
            //Save data into system_log_pschool table
            foreach ($request->list_pschool_id as $pchool_id) {
                $success = DB::table('system_log_pschool')->insertGetId(
                    [
                        'notify_id'      => $id,
                        'pschool_id'     => $pchool_id,
                        'register_date'  => date('Y-m-d H:i:s'),
                        'register_admin' => Auth::id(),
                        'active_flag'    => 1
                    ]
                );
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        if ($success) {
            if (session()->has('_old_input')) {
                $request->session()->forget('_old_input');
            }
            $message = array (
                'message' => "「{$dataType->display_name_singular}」が登録されました。",
                'alert-type' => 'success'
            );
        }
        return redirect('admin/oshirasekanri')
            ->with([
                'message' => $message
            ]);
    }

    /**
     * Save data when edit notification
     * @param Request $request
     * @param $id
     * @return list notify
     */
    public function update(Request $request, $id) {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        Voyager::canOrFail('add_' . $dataType->name);
        $rules = $this->get_validate_rules($request);
        $messsages = $this->get_validate_message();
        //validate data from form
        $validator = Validator::make(request()->all(), $rules, $messsages);
        if ($validator->fails()) {
            session()->push('old_data', $request->input());
            $request->offsetSet('errors', $validator->errors());
            $request->offsetSet('list_pschool_id', $request->list_pschool_id);
            $request->offsetSet('errors', $validator->errors());
            return $this->edit($request, $id);
        }
        //get notify by id
        $notify = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        //update data into system_log table
        $data = array (
            'process'       => $request->process,
            'message'       => $request->message,
            'status'        => $request->exists('status') ? $request->status : 1,
            'start_date'    =>!empty($request->input('date_from')) ? date('Y-m-d H:i', strtotime($request->input('date_from'))):null,
            'end_date'      => !empty($request->input('date_to')) ? date('Y-m-d H:i', strtotime($request->input('date_to'))):null,
            'update_date'   => date('Y-m-d H:i:s'),
            'update_admin'  => Auth::id(),
        );
        if($request->offsetExists('calendar_flag')&&$request->calendar_flag==1){
            $data['calendar_flag']      = empty($request->calendar_flag) ? 0 : 1;
            $data['calendar_color']     =$request->calendar_color;
            $data['start_calendar_dis'] =$request->start_display_calendar;
            $data['end_calendar_dis']   =$request->end_display_calendar;
            
        }else {
            $data['calendar_flag']      = 0;
            $data['calendar_color']     = null;
            $data['start_calendar_dis'] = null;  
            $data['end_calendar_dis']   = null;
        }
        DB::table('system_log')->where('id', $notify->id)->update($data);
        //update data into system_log_pschool table
        $system_log_pschool_id = array ();
        $system_log_pschools = DB::table('system_log_pschool')->where('notify_id', $notify->id)->get();
        foreach ($system_log_pschools as $system_log_pschool) {
            $system_log_pschool_id[] = $system_log_pschool->pschool_id;
        }
        $notify_in_system_pschool = array ();
        foreach ($request->list_pschool_id as $pchool_id) {
            if (in_array($pchool_id, $system_log_pschool_id)) {
                array_push($notify_in_system_pschool, $pchool_id);
            } else {
                DB::table('system_log_pschool')->insert(
                    [
                        'notify_id'      => $id,
                        'pschool_id'     => $pchool_id,
                        'register_date'  => date('Y-m-d H:i:s'),
                        'update_date'    => date('Y-m-d H:i:s'),
                        'register_admin' => Auth::id(),
                        'update_admin'   => Auth::id(),
                        'active_flag'    => 1,
                    ]
                );
            }
        }
        foreach ($system_log_pschools as $system_log_pschool) {
            if (! in_array($system_log_pschool->pschool_id, $notify_in_system_pschool)) {
                DB::table('system_log_pschool')->where('notify_id', $id)->where('pschool_id', $system_log_pschool->pschool_id)->update(
                    [
                        'active_flag' => null,
                        'update_admin' => null
                    ]
                );
            } else {
                DB::table('system_log_pschool')->where('notify_id', $id)->where('pschool_id', $system_log_pschool->pschool_id)->update(
                    [
                        'update_date'  => date('Y-m-d H:i:s'),
                        'update_admin' => Auth::id(),
                        'active_flag'  => 1,
                    ]
                );

            }
        }
        if (session()->has('_old_input')) {
            $request->session()->forget('_old_input');
        }
        $message = array (
            'message'    => "「{$dataType->display_name_singular}」が変更されました。",
            'alert-type' => 'success'
        );
        return redirect('admin/oshirasekanri')
            ->with([
                'message' => $message
            ]);
    }

    /**
     * Delete notification
     * @param Request $request
     * @param $id Id notification
     * @return sprint list notification
     */
    public function destroy(Request $request, $id) {

        $slug = 'oshirasekanri';
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        // Check permission
        Voyager::canOrFail('delete_' . $dataType->name);
        //get notify by id
        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        if ($data) {
            $message = DB::table('system_log')->where('id', $id)->update(array ('delete_date' => date('Y-m-d H:i:s')))
                ? $message = array (
                    'message' => "「{$dataType->display_name_singular}」が削除されました。",
                    'alert-type' => 'success'
                )
                : $message = array (
                    'message' => "エラーが発生しました。削除できません。",
                    'alert-type' => 'error'
                );
            //delete in  table system_log_pschool
            DB::table('system_log_pschool')->where('notify_id', $data->id)->update(array ('delete_date' => date('Y-m-d H:i:s')));
        } else {
            $message = array (
                'message' => "この「{$dataType->display_name_singular}」は他の施設で使用中です。",
                'alert-type' => 'error'
            );
        }
        return redirect()->route("voyager.{$dataType->slug}.index")->with(['message' => $message]);
    }

    /**
     * add rules validate
     * @param $request
     * @param array $data
     * @return array
     */
    private function get_validate_rules($request, $data = array ()) {
        $rules = [
            'process' => 'required',
            'message' => 'required|max:255',
            'list_pschool_id' => 'required'
        ];
        if($request->offsetExists('calendar_flag')&&$request->calendar_flag==1){
            $rules['start_display_calendar']='required';
            $rules['end_display_calendar']  ='required';
            $rules['calendar_color']        ='required';
        }
        return $rules;
    }

    /**
     * add message validate
     * @return array    store message validate
     */
    private function get_validate_message() {

        $messsages = [
            'process.required'               => '件名は必要です。',
            'message.required'               => 'お知らせ内容は必要です。',
            'list_pschool_id.required'       => '対象施設を選択してください。',
            'start_display_calendar.required'=>'カレンダーに表示する開始日を入力する必要があります',
            'end_display_calendar.required'  =>'カレンダーに表示する終了日を入力する必要があります',
            'calendar_color.required'        =>'カレンダーに表示される色を選択する必要があります'
        ];
        return $messsages;
    }

    /**
     * @param $items // array list key search default
     * @param $session_key // Name default of session.
     * @return
     */
    protected function _initSearchDataFromSession($items, $session_key) {

        if (request()->offsetExists('clr')) {
            return session()->forget($session_key);
        }
        foreach ($items as $item) {
            if (request()->offsetExists($item)) {
                // Save to session new search info when form submitted
                session()->put($session_key . '.' . $item, request()->get($item));
            } elseif (session()->has($session_key . '.' . $item)) {
                // If nothing has submit, then try to get data from session
                request()->offsetSet($item, session($session_key . '.' . $item));
            }
        }
    }
}