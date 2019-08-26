@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="text/css" rel="stylesheet" href="/css/admin/menu_custom.css"/>
    <link type="text/css" rel="stylesheet" href="/css/admin/pschool.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop

@if(isset($dataTypeContent->id))
    @section('page_title','編集 '.$dataType->display_name_singular)
@else
    @section('page_title','新規登録 '.$dataType->display_name_singular)
@endif

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> @if(isset($dataTypeContent->id)){{ '編集' }}@else{{ '新規登録' }}@endif {{ $dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <div class="panel-heading">
                        <h3 class="panel-title">@if(isset($dataTypeContent->id)){{ '編集' }}@else{{ '新規登録' }}@endif {{ $dataType->display_name_singular }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form"
                          class="form-edit-add"
                          action="@if(isset($dataTypeContent->id)){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->id) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
                          method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if(isset($dataTypeContent->id))
                            {{ method_field("PUT") }}
                            <input type="hidden" name="menu_message_list" value="">
                            <input type="hidden" name="pschool_id" value="{{$dataTypeContent->id}}">
                        @endif
                        @php
                            $data = array();
                            if (session()->has('old_data')) {
                                $data = session()->pull('_old_data')[0];
                            }
                        //dd($request);
                        @endphp
                    <!-- CSRF TOKEN -->
                        {{ csrf_field() }}
                        <div class="panel-body">

                            <label class="require"> プログラム種別 </label>
                            <select name="application_type" class="form-control select2 select2-hidden-accessible"
                                    id="application_type_select">
                                    @foreach($applycationType as $k => $type)
                                    <option value="{{$k}}"{{($dataTypeContent->application_type == $k) ? 'selected' : ''}}>{{$type}}</option>
                                    @endforeach
                            </select>

                            <!-- Custom by Kieu -->
                            <h1 class="page-title1">
                                <i class="{{ $dataType->icon }}"></i>
                                @if(isset($dataTypeContent->id) && $dataTypeContent->message_file)<a href="#"
                                                                                                     class="btn btn-link btn-link-message">
                                    <i class="voyager-file-text"></i> メッセージファイル編集
                                </a>

                                <input type="hidden" id="flag_call_reload" value="1">
                                <input type="hidden" id="flag_call_update_menu" value="0">
                                @endif
                                {{--<a href="#" class="btn btn-success" onclick="display_menu_block">
                                    <i class="voyager-plus"></i> @if(isset($dataTypeContent->id))　{{ 'メニュー編集' }}@else{{ 'メニュー追加' }}@endif
                                </a> --}}
                            </h1>

                            @if (isset($request) && count($request->errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($request->errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{--Plan selection by Toran--}}
                            <div id="plan_selection">
                                <h4>契約種別</h4>

                                <label class="require"> 契約種別 </label>
                                <select name="m_plan_id" class="form-control select2 select2-hidden-accessible"
                                        id="plan_select_input">
                                    @if(!empty($plans))
                                        @foreach($plans as $k => $plan)
                                            <option value="{{$plan->id}}" @if(!empty($plan->default)) selected
                                                    @elseif(isset($request->m_plan_id) && $request->m_plan_id == $plan->id) selected @endif>{{$plan->plan_name}}</option>
                                        @endforeach
                                    @else
                                        <option></option>
                                    @endif
                                </select>
                                <div>
                                    <table id="content_plan" style="margin-bottom: 10px;">
                                        <thead>
                                        <tr>
                                            <th>名称</th>
                                            <th>登録会員数</th>
                                            <th>有効会員数</th>
                                            <th>施設数（階層化の場合）</th>
                                            <th>有効期限</th>
                                            <th>金額</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($plans as $k => $plan)
                                            <tr class="detail_plan_{{$plan->id}} detail_plan" style="display: none">
                                                <td>{{$plan->plan_name}}</td>
                                                <td>@if(!empty($plan->number_register)){{$plan->number_register}}@else
                                                        &#8734; @endif</td>
                                                <td>@if(!empty($plan->number_active)){{$plan->number_active}}@else
                                                        &#8734; @endif</td>
                                                <td>@if(!empty($plan->number_institution)){{$plan->number_institution}}@else
                                                        &#8734; @endif</td>
                                                <td>{{$plan->validation_date}}</td>
                                                <td style="text-align: right">{{number_format($plan->plan_amount)}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <input type="checkbox" name="custom_menu" value="1" @if($dataTypeContent->custom_menu_flag == 1) checked @endif><label id="custom_menu" data-toggle="collapse" href="#collapse_menu">&nbsp;カスタムメニュー</label>
                                </div>
                            </div>
                            {{--start menu select--}}
                            <div class="panel-collapse collapse page-menu-assign " id="collapse_menu">
                                <h4>メニュー設定</h4>
                                <div id="menu_left">
                                    <ul id="master_menu_side">
                                        @foreach ($masterMenuList as $index => $menu)
                                            <li idx="{{$index}}">
                                                <input type="checkbox" class="menu_item" value="{{$index}}"
                                                       id="master{{$index}}"
                                                       @if ($menu['default_flag'] == 1 || (isset($request['menu_list']) && in_array($index,$request['menu_list'])))
                                                       checked="checked"
                                                       @endif @if ($menu['default_flag'] == 1) disabled="disabled" @endif>
                                                <label for="master{{$index}}">{{$lan::get($menu['menu_name_key'])}}</label>
                                            </li>
                                            <!-- display sub menu -->
                                            @if (array_key_exists($index,$subMenuList))
                                                @foreach ($subMenuList[$index] as $idx => $submenu)
                                                    <li idx="{{$idx}}" class="submenu">
                                                        <input type="checkbox" class="menu_item" value="{{$idx}}"
                                                               id="master{{$idx}}"><label for="master{{$idx}}"
                                                                                          @if ($submenu['default_flag'] == 1)  disabled="disabled"
                                                                                          checked="checked" @endif >{{$lan::get($submenu['menu_name_key'])}}</label>
                                                    </li>
                                                @endforeach
                                            @endif

                                        @endforeach
                                    </ul>
                                </div>

                                <div id="menu_right">
                                    <span>表示・編集 順番</span>
                                    <ul id="menu_side">

                                    </ul>

                                    <ul id="view_edit">
                                        @foreach ($masterMenuList as $index => $menu)
                                            <li idx="{{$index}}" class="hideLi">
                                                <input type="checkbox" class="chkViewable" checked="checked"
                                                       @if ($menu['default_flag'] == 1)
                                                       disabled="disabled" @endif>
                                                <input type="checkbox" class="chkEditable"
                                                       @if ($menu['editable'] != 1) disabled="disabled"
                                                       @else checked="checked" @endif
                                                       @if ($menu['default_flag'] == 1) disabled="disabled" @endif>
                                            </li>
                                            <!-- Submenu -->
                                            @if (array_key_exists($index,$subMenuList))
                                                @foreach ($subMenuList[$index] as $idx => $submenu)
                                                    <li idx="{{$idx}}" class="hideLi submenu">
                                                        <input type="checkbox" class="chkViewable" checked="checked"
                                                               @if ($submenu['default_flag'] == 1) disabled="disabled" @endif>
                                                        <input type="checkbox" class="chkEditable"
                                                               @if ($submenu['editable'] != 1) disabled="disabled"
                                                               @else checked="checked" @endif
                                                               @if ($submenu['default_flag'] == 1) disabled="disabled" @endif>
                                                    </li>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </ul>

                                    <ul id="up_down">
                                        @foreach ($masterMenuList as $index => $menu)
                                            <li idx="{{$index}}" class="hideLi">
                                                <button type="button" id="btnDown" onclick="stepDown({{$index}})"><i
                                                            class="fa fa-caret-down"></i></button>
                                                <button type="button" id="btnUp" onclick="stepUp({{$index}})"><i
                                                            class="fa fa-caret-up"></i></button>
                                            </li>
                                            <!-- Submenu -->
                                            @if (array_key_exists($index,$subMenuList))
                                                @foreach ($subMenuList[$index] as $idx => $submenu)
                                                    <li idx="{{$idx}}" class="hideLi submenu">
                                                        <button type="button" id="btnDown"
                                                                onclick="stepDownSub({{$idx}})"><i
                                                                    class="fa fa-caret-down"></i></button>
                                                        <button type="button" id="btnUp" onclick="stepUpSub({{$idx}})">
                                                            <i class="fa fa-caret-up"></i></button>
                                                    </li>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- end custom -->
                            <!-- custom by Thang -->
                            <div id="login_acc_block">
                                <h4>管理者</h4>
                                <div class="form-group ">
                                    <label class="require"> メールアドレス </label>
                                    <input type="text" name="login_id" class="form-control"
                                           value="{{isset($dataTypeContent->login_id)? $dataTypeContent->login_id : array_get($data,'login_id')}}"
                                    />
                                </div>
                                <div class="form-group ">
                                    <label class="require"> パスワード </label>
                                    <input type="password" name="login_pw" value="" class="form-control"
                                           placeholder="********"/>
                                    <span><b>※半角英数文字または特殊文字(-,_,.,$,#,:@,!)で8文字以上16文字以下</b></span><br/>
                                    @if (isset($dataTypeContent->id))
                                        <br/><input type="password" name="login_pw_confirm" value=""
                                                    class="form-control" placeholder=" パスワード (確認)"/>
                                        <span class="col_msg">※変更する場合のみ入力</span>
                                    @endif
                                </div>
                            </div>
                            <!-- end custom by Thang -->
                            <!-- If we are editing -->
                            @if(isset($dataTypeContent->id))
                                <?php $dataTypeRows = $dataType->editRows; ?>
                            @else
                                <?php $dataTypeRows = $dataType->addRows; ?>
                            @endif
                            <div id="pschool_add_block">
                                <h4>基本情報</h4>
                                <!-- <h5>* 印のついた項目は必須入力です。</h4> -->
                                @foreach($dataTypeRows as $row)
                                    @if ($row->field == 'zip_code')
                                        <div class="form-group">
                                            <label for="name">{{ $row->display_name }}</label><br/>
                                            &#12306;&nbsp;<input class="form-control zip_code" type="text"
                                                                 name="zip_code1" maxlength="3" size="6"
                                                                 value="{{isset($dataTypeContent->zip_code1) ? $dataTypeContent->zip_code1 : '' }}">
                                            &nbsp;－&nbsp;
                                            <input class="form-control zip_code" type="text" name="zip_code2"
                                                   maxlength="4" size="8"
                                                   value="{{isset($dataTypeContent->zip_code2) ? $dataTypeContent->zip_code2 : '' }}">
                                        </div>
                                    @else
                                        <div class="form-group @if($row->type == 'hidden') hidden @endif">
                                            <!-- 本部名・状態・業態 require-->
                                            <label for="name"
                                                   class="@if($row->field == 'name' || $row->field == 'active_flag' || $row->field == 'business_type_id' || $row->field == 'pschool_code') require @endif">{{ $row->display_name }}</label>
                                        @include('voyager::multilingual.input-hidden-bread')
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                        @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                        @endforeach
                                        <!-- explain code string -->
                                            @if ($row->field == 'pschool_code') <span class="col_msg"><b>※６文字以内で入力してください。</b></span> @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div> <!-- pschool_add_block -->
                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">@if(isset($dataTypeContent->id)) 編集 @else
                                    登録 @endif</button>
                            @if(isset($dataTypeContent->id) && $dataTypeContent->message_file)
                                <a href="/admin/pschool/exportcsv?pschool_id={{$dataTypeContent->id}}"
                                   class="btn btn-primary">出力</a>
                            @endif
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                          enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                               onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> Are You Sure</h4>
                </div>

                <div class="modal-body">
                    <h4>Are you sure you want to delete '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">Yes, Delete it!
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
    <div id="modal-dialog-message">
        <div class="modal-content" style="height: 100%; padding:20px;display: inline-table">

            <div id="custom-screen-area">

            </div>
        </div>
    </div>
@stop

@section('javascript')
    <link type="text/css" rel="stylesheet" href="/css/admin/message.css"/>

    <script>
        var params = {}
        var $image

        $('document').ready(function () {

            //Toran js check menu
            $("#custom_menu").click(function(e) {
                e.preventDefault();
                if($("#collapse_menu").hasClass("in")){
                    $("input[name=custom_menu]").prop("checked", false);
                }else{
                    $("input[name=custom_menu]").prop("checked", "checked");
                }
            });
            $("input[name=custom_menu]").change(function(e) {
                $('#collapse_menu').collapse('toggle');
            });
            @if($dataTypeContent->custom_menu_flag ==1)
                $('#collapse_menu').collapse('toggle');
            @endif


            //Toran add js select plan

            $("#plan_select_input").change(function () {
                var plan_id = $(this).val();
                $(".detail_plan").hide();
                $(".detail_plan_" + plan_id).show();
            })
            $("#plan_select_input").change();
            //

            $('.toggleswitch').bootstrapToggle();

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function (i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', function (e) {
                $image = $(this).parent().siblings('img');

                params = {
                    slug: '{{ $dataTypeContent->getTable() }}',
                    image: $image.data('image'),
                    id: $image.data('id'),
                    field: $image.parent().data('field-name'),
                    _token: '{{ csrf_token() }}'
                }

                $('.confirm_delete_name').text($image.data('image'));
                $('#confirm_delete_modal').modal('show');
            });

            $('#confirm_delete').on('click', function () {
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if (response
                        && response.data
                        && response.data.status
                        && response.data.status == 200) {

                        toastr.success(response.data.message);
                        $image.parent().fadeOut(300, function () {
                            $(this).remove();
                        })
                    } else {
                        toastr.error("Error removing image.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            var colorCodes = {
                back: "#fff",
                front: "#888",
                side: "#369"
            };

// Custom by Kieu
//      本部コード: max 6 characters
//      運用区分 : Disable
            $('[name=pschool_code]').parent().insertAfter($('[name=group_id]').parent());
            $('[name=pschool_code]').attr('maxlength', 6)
            $('select[name=business_divisions]').attr('disabled', true);

            var cityList = $('select[name=city_id]').clone(true);
            var prefId = $('select[name=pref_id]').val();
            if (prefId) {
                showHideCityList(prefId, cityList);
            }
            $('select[name=pref_id]').on('change', function () {
                var prefId = $(this).val();
                showHideCityList(prefId, cityList);
            });
            var seq_lst = [];
            $('#master_menu_side li').each(function () {
                seq_lst.push(parseInt($(this).attr('idx')));
            })
                    @if (isset($currentMenu))
                    @foreach ($currentMenu as $idx => $menu)
            var master_menu_id = {{$menu['master_menu_id']}};
            $('.menu_item[value=' + master_menu_id + ']').attr('checked', true);
            $('.menu_item[value=' + master_menu_id + ']').change();
            @endforeach
            @else
            // $('.page-menu-assign').toggle();
            @endif
            // loadPosition(seq_lst);
        });
        function loadPositionBySeqNo(seq_lst) {
            var prev_id;
            var index;
            var prev_index;
                    @if (isset($currentMenu))
                    @foreach ($currentMenu as $idx => $menu)
            var master_menu_id = {{$menu['master_menu_id']}};

            // sort by seq_no
            @if (!$loop->first)
                index = seq_lst.indexOf(master_menu_id);
            prev_index = seq_lst.indexOf(prev_id);
            if (index < prev_index) {

                stepDown(master_menu_id, prev_id);
            }
            @endif
                prev_id = master_menu_id;

            @endforeach
            @endif
        }
        $('.btn-success').click(function (e) {
            e.preventDefault();
            // $('.page-menu-assign').toggle("slow");
            return false;
        });

        function showHideCityList(prefId, cityList) {
            var cityListRefined = cityList.clone(true).find('option[value^="' + prefId + "_" + '"]');
            $('select[name=city_id]').empty()
                .append("<option value='0'></option>")
                .append(cityListRefined);
        }

        var message_custom_area_clone;
        var menu_message_clone;
        var flag_updated_menu = 0;
        // Defind dialog contain message
        $("#modal-dialog-message").dialog({
            title: "メッセージファイル編集",
            autoOpen: false,
            dialogClass: 'no-close',
            resizable: false,
            width: 1500,
            height: 900,
            modal: true,
            buttons: {
                "保存": function () {
                    if (flag_updated_menu == 1) {
                        // $('#flag_call_update_menu').val(1); // reload
                        flag_updated_menu = 0;
                    }

                    $(this).dialog("close");
                    return false;
                },
                "キャンセル": function () {
                    // Call message_content.blade
                    reverse_message_content();
                    // reverse content dialog before updated
                    $("#modal-dialog-message .modal-content").html(message_custom_area_clone);


                    if (flag_updated_menu != 1) {
                        // reverse content menu before updated
                        custom_menu_for_message = jQuery.extend(true, {}, menu_message_clone);
                        flag_updated_menu = 0;
                    }


                    $(this).dialog("close");
                    return false;
                }
            }
        });

        // Click link -> call ajax-> fill to dialog ↑ content
        $('.btn-link-message').click(function (e) {
            $('#voyager-loader').show();
            e.preventDefault();

            // clone custom_menu_for_message before fill value
            // Deep copy
            menu_message_clone = jQuery.extend(true, {}, custom_menu_for_message);

            // Just call 1 time
            if ($('#flag_call_reload').val() == 1) {
                var url = '/admin/pschool/loadscreen';
                var lang_code = $('[name=language]').val();
                var business_type_id = $('[name=business_type_id]').val().split('|');
                $.ajax({
                    type: "GET",
                    data: {
                        @if ($dataTypeContent->id)
                        pschool_id: {{$dataTypeContent->id}} ,
                        @endif
                        lang_code: lang_code,
                        business_type_id: business_type_id[0]
                    },
                    dataType: "html",
                    url: url,
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        // Switch flag_call_reload to 0
                        $('#flag_call_reload').val('0');
                        // Switch flag_call_reload to 1
                        // $('#flag_call_update_menu').val(1);

                        $('#voyager-loader').hide();
                        $("#modal-dialog-message #custom-screen-area").html(data);
                        $("#modal-dialog-message").dialog('open');

                        // Change css
                        $("#modal-dialog-message").parent().css({zIndex: 9999});
                        $(".ui-dialog-buttonset button:first-child").addClass('btn-success');
                        $(".ui-dialog-buttonset button:nth-child(2)").addClass('btn-default');
                        $("[name=parent_id]").val({{$dataTypeContent->id}});
                        $("[name=parent_id]").change();

                        reload_menu();
                        message_custom_area_clone = $("#modal-dialog-message #custom-screen-area").clone();

                        fill_value_menu();
                        // Call message_content.blade
                        clone_message_content();
                    },
                    error: function (data) {
                        $('#voyager-loader').hide();
                        console.log("error");
                    },
                });
            } else {


                // rerun all js of message_content.blade
                start_all_js();

                // Call message_content.blade
                clone_message_content();
                $('#voyager-loader').hide();
                $("#modal-dialog-message").dialog('open');
                reload_menu();
                message_custom_area_clone = $("#modal-dialog-message #custom-screen-area").clone();

                fill_value_menu();
            }
        });

        // change business_type_id, language => set flag_call_reload = 1
        $('[name=business_type_id], [name=language]').change(function () {
            $('#flag_call_reload').val(1);
        });

        var master_message_menu ={!! json_encode($masterMenuList) !!};
                @if (isset($currentMenu))
        var selected_menu = {!! json_encode($currentMenu) !!}; // list menu assigned
                @else
        var selected_menu = []; // list menu assigned
                @endif

        var custom_menu_for_message = {}; // list menu MAP with message menu
        // get menu_name_key to key
        for (x in selected_menu) {
            custom_menu_for_message[selected_menu[x]['menu_name_key']] = selected_menu[x];
        }

        // Add or delete object menu, update into custom_menu_for_message
        $('.menu_item').change(function () {
            // get object menu
            var idx = ($(this).parent().attr('idx'));
            var menu = master_message_menu[idx];
            var menu_name_key = menu['menu_name_key'];

            if ($(this).is(":checked")) {
                if (!custom_menu_for_message.hasOwnProperty(menu_name_key)) {
                    menu['menu_text'] = $("label[for='master" + idx + "']").first().text();
                    custom_menu_for_message[menu_name_key] = menu;
                    $('#flag_call_update_menu').val(1);
                    flag_updated_menu = 1;
                }

            } else {
                delete custom_menu_for_message[menu_name_key];
                $('#flag_call_update_menu').val(1);
                flag_updated_menu = 1;

            }
        });

        // function to hide or add new menu on message list
        function reload_menu() {
            var custom_menu_for_message_clone = jQuery.extend({}, custom_menu_for_message); // filter
            // check flag_call_update_menu to reload menu
            // if ($('#flag_call_update_menu').val() == 1) {

            $('.school\\.menu').each(function () {

                // remove key not exist on custom_menu_for_message
                var menu_name_key = $(this).children(":first").val();
                if (!custom_menu_for_message.hasOwnProperty(menu_name_key)) {

                    $(this).hide();
                } else {

                    delete custom_menu_for_message_clone[menu_name_key];
                    $(this).show();
                }
            });

            // Add menu

            if (Object.keys(custom_menu_for_message_clone).length > 0) {
                for (x in custom_menu_for_message_clone) {
                    var div_clone = $('.school\\.menu').last().clone();
                    // Add class to define new item
                    div_clone.children().addClass('new-input');
                    // change message_key
                    div_clone.children(":first").attr('id', 'message_key|' + x);
                    div_clone.children(":first").val(x);
                    // change message_value
                    div_clone.children(":first").next().attr('id', 'message_value|' + x);
                    div_clone.children(":first").next().val(custom_menu_for_message_clone[x]['menu_text']);
                    // change comment
                    div_clone.children(":first").next().next().attr('id', 'comment|' + x);
                    div_clone.children(":first").next().next().val('');
                    div_clone.insertAfter($('.school\\.menu').last());
                    div_clone.show();
                }
            }
            // $('#flag_call_update_menu').val(0);
            // }

        }

        // function to create event onchange of input which menu was added
        function fill_value_menu() {
            // onchange of new menu
            $('input.new-input').change(function () {
                // input_id : {...}|menu_name_key
                var input_id = $(this).attr('id').split('|');

                var idx = custom_menu_for_message.hasOwnProperty(input_id[1]);
                if (idx) {
                    custom_menu_for_message[input_id[1]][input_id[0]] = $(this).val();
                }

            });

        }

        // Custom Save button to set value input
        // allow pass disable field view, edit to serve
        $('.save').click(function () {
            $('#view_edit input').attr('disabled', false);

            // Move screen_list, message_list in Edit Form to submit
            if ($("[name=screen_list]").length != 0 && $("[name=message_list]").length != 0) {
                $("[name=screen_list]").insertAfter($('[name=pschool_id]'));
                $("[name=message_list]").insertAfter($('[name=screen_list]'));
            }

            // menu_message_list: list custom menu when admin edit
            // Send menu_message_list to serve
            $("[name=menu_message_list]").val(JSON.stringify(custom_menu_for_message));

            return true;
        });
    </script>
    @if($isModelTranslatable)
        <script src="{{ config('voyager.assets_path') }}/js/multilingual.js"></script>
    @endif
    <script src="{{ config('voyager.assets_path') }}/lib/js/tinymce/tinymce.min.js"></script>
    <script src="{{ config('voyager.assets_path') }}/js/voyager_tinymce.js"></script>
    <script src="{{ config('voyager.assets_path') }}/js/slugify.js"></script>
    <script type="text/javascript" src="/js/admin/menu_custom.js"></script>
@stop
