@extends('_parts.master_layout')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />

    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-book"></i>{{$lan::get('main_title')}}</h2> <!--  プラン管理 -->
            <div class="center_content_header_right">
            </div>
            <div class="clr"></div>
        </div><!--center_content_header-->

        <h3 id="content_h3" class="box_border1">{{$lan::get('member_detail_info_title')}}</h3> <!--会員情報 -->
        <form method="post" id="action_form" action="{{$_app_path}}class/studentList">
        {{ csrf_field() }}
            <div id="section_content">
                <!-- プラン基本情報 -->
                <div class="info_content padding1 box_border1">
                    <div class="info_info_right p15">
                        @if (isset($class_info['start_date']) && $class_info['start_date'])<p>{{$lan::get('begin_day_title')}}：{{date('Y-m-d', strtotime($class_info['start_date']))}}</p>@endif
                        @if (isset($class_info['close_date']) && $class_info['close_date'])<p>{{$lan::get('end_day_title')}}：{{date('Y-m-d', strtotime($class_info['close_date']))}}</p>@endif
                    </div>
                    <p class="info_name p32">{{$class_info['class_name']}}</p>             <!-- イベント名称 -->
                    <span class="info_info p18" style="margin-bottom: 0;">{!! $class_info['class_description'] !!}</span>       <!-- イベント内容 -->
                    <div class="clr"></div>
                    
                </div>


                <h3 id="content_h3" class="box_border1">
                @if (request('mode')==1) {{$lan::get('add_title')}} @else {{$lan::get('confirm_or_delete_title')}} @endif</h3>
                <div class="search_box box_border1 padding1">
                    <!-- 基準金額 -->
                    @if (request('mode')==1)
                        <div class="panel panel-default" style="width: 60%;">
                            <div class="panel-heading">
                                <table class="coach-table-content">
                                    <tr>
    
                                        <td style="width: 100%"><b>{{$lan::get('setting_schedule_payment')}}</b></td>
                                        <td class="drop_down" data-toggle="collapse" href="#collapse1"><i class="fa fa-chevron-down" style="width: 10%;font-size:16px;"></i></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                @include('_parts.student.payment_plan')
                            </div>
                        </div> <!-- panel panel-default -->
                    @endif

                    <!-- student_search4 -->
                    <table>
                        <tr>
                            <th style="width:10%;">{{$lan::get('name_furigana_title')}}</th> <!-- 名前（フリガナ） -->
                            <td style="width:30%;"><input class="text_long" type="search" name="student_name" value={{old('student_name',request('student_name'))}} ></td>
                        </tr>
                        <tr>
                            <th style="width:10%;">{{$lan::get('member_phone_title')}}</th><!-- 会員番号 -->
                            <td style="width:30%;"><input class="text_long" type="search" name="student_no" value={{old('student_no', request('student_no'))}} ></td>

                        </tr>
                        <tr>
                            <th style="width:10%;">{{$lan::get('member_type_title')}}</th> <!-- 会員種別 -->
                            <td style="width:30%;vertical-align:middle;">
                                @if (request('_student_types'))
                                    @foreach (request('_student_types') as $index=>$type)
                                    <label>
                                    <input type="checkbox" name="_student_types[{{$index}}][type]" value="{{$type['id']}}" @if ($type['is_display'] == '1') checked @endif/>&nbsp;{{$type['name']}}
                                    </label>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{$lan::get('payment_type_title')}}</th>
                            <td>
                                @foreach($invoice_type as $k => $type)
                                    <label><input type = "checkbox" name="invoice_type_search[]" value="{{$k}}" @if(in_array($k,$filter) || empty($filter)) checked @endif /><span style="font-size: 12px !important;">{{$type}}</span></label> &nbsp;&nbsp;
                                @endforeach
                            </td>
                        </tr>
                    </table>
                    <div class="clr"></div>

                    <!-- <input type="submit" id="btn_student_search"  class="submit" name="search_button" value="{{$lan::get('search_title')}}"> -->
                    <button class="btn_search" type="submit" id="btn_student_search" name="search_button" style="height:29px;padding: 2px 10px !important;width: 150px !important;"><i class="glyphicon glyphicon-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search_title')}}</button>
                    <input type="button" class="submit" id="search_cond_clear" value="{{$lan::get('clear_title')}}"/>

                    <div class="clr"></div>
                </div> <!-- search_box box_border1 padding1 -->
                <ul class="message_area">
                    @if (session()->has('errors'))
                        @foreach ($errors->all() as $error)
                            <li class="error_message">{{ $lan::get($error) }}</li>
                        @endforeach
                    @endif
                </ul>
                <ul class="message_area message_area_js" style="display: none;">
                    <li class="error_message">{{$lan::get('select_error_title')}}</li>
                </ul>
            <div id="section_content_in">
            <input type="hidden" name="id" value="{{old('id', request('id'))}}" />
            <input type="hidden" name="mode" value="{{old('mode', request('mode'))}}" />
                <!-- 全て選択 -->
                @if (request('mode')!=3)
                    <label><input type="checkbox" id="select_all"/>&nbsp;&nbsp;{{$lan::get('select_all_title')}}</label><br/>
                @endif

                <table class="table_list tablesorter" >
                    <thead>
                    <tr class="head_tr">
                    @if (request('mode')!=3)
                        <th class="text_title" style="width: 10%;">{{$lan::get('select_title')}}</th>
                    @endif
                        <th class="text_title header" style="width: 30%;">{{$lan::get('member_name_title')}}<i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
                        <th class="text_title" style="width: 15%;">{{$lan::get('member_phone_title')}}</th>
                        <th class="text_title" style="width: 20%;">{{$lan::get('payment_type_title')}}</th>
                        <th class="text_title" style="width: 25%;">{{$lan::get('member_type_title')}}<i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
                        {{--<th class="text_title" @if (request('mode')== 1) style="width:310px;" @else style="width:300px;" @endif>{{$lan::get('fee_type_title')}}</th>--}}
                    </tr>
                    </thead>
                    <tbody class="table_row" style="width:900px;">
                    @foreach ($list as $idx=>$row)
                        <tr>
                            @if (request('mode')!=3)
                                <td style="width: 10%;">
                                    <input type="checkbox" name="students[{{$idx}}]"  class="select_rec" value="{{array_get($row,'id')}}" />
                                </td>
                            @endif
                                <td style="width: 30%;">{{array_get($row,'student_name')}}
                                </td>
                                <td style="width: 15%;">{{array_get($row,'student_no')}}</td>
                                <td style="width: 20%;@if(array_get($row,'method_supported') == false) color: red!important; ; font-weight: bold @endif">
                                    {{array_get($row,'payment_name')}}
                                </td>
                                <td style="width: 25%;">
                                    {{array_get($row,'student_type_name')}}
                                </td>
                                {{--<td style="width:250px;">
                                    @if (request('mode') == 1)
                                    <select  style="width:250px;" name="_class_fee_plan_id{{$idx}}">
                                        @foreach ($class_fee_plan as $key=>$val)
                                            <!-- auto select by m_student_type_id -->
                                            <option value="{{$key}}" @if ($key == array_get($row,'plan_id')) selected @elseif (!array_get($row,'plan_id') && array_get($row, 'm_student_type_id') == $val['student_type_id']) selected @endif >{{array_get($val, 'value')}}</option>
                                        @endforeach
                                    </select>
                                    @else
                                        {{array_get($row,'fee_plan_name')}}&nbsp;|&nbsp;{{array_get($row,'fee')}}
                                    @endif
                                </td>--}}

                        </tr>
                    @endforeach
                    @forelse ($list as $idx=>$row)
                    @empty
                        <tr>
                        <td class="error_row" @if (request('mode')!=3) colspan="5" @else colspan="4" @endif>{{$lan::get('nothing_display_title')}}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <!-- BUTTON EXECUTE AREA -->
                @if ($list && (request('mode') != 3))
                    <button class="submit2" type="button" id="btn_submit" >
                        @if (request('mode')==1)
                            <!--会員追加 -->
                            <i class="fa fa-plus " style="width: 20%;font-size:16px;"></i>{{$lan::get('add_title')}}
                        @elseif (request('mode')==2)
                            <!--会員削除 -->
                            <i class="fa fa-minus " style="width: 20%;font-size:16px;"></i>{{$lan::get('member_delete_title')}} @endif
                    </button>
                @endif
                    <button class="submit2" type="button" id="btn_return" ><i class="fa fa-arrow-circle-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>

            </div>
            </div>
        </form>
    <script type="text/javascript">
        $(function() {
            $(".tablesorter").tablesorter({
                headers: {
                    0: { sorter: false},
                    2: { sorter: false},
//                    3: { sorter: false},
                    4: { sorter: false}
                }
            });

            $(".header, .drop_down").click(function (e) {
                e.preventDefault();
                if($(this).children().hasClass("fa-chevron-down")){
                    $(this).children().removeClass("fa-chevron-down");
                    $(this).children().addClass("fa-chevron-up");
                }else if($(this).children().hasClass("fa-chevron-up")){
                    $(this).children().removeClass("fa-chevron-up");
                    $(this).children().addClass("fa-chevron-down");
                }

            });
            $(".drop_down").click();

            $.datetimepicker.setLocale('ja');

            jQuery(function(){
                jQuery('.DateInput').datetimepicker({
                    format: 'm-d',
                    timepicker:false,
//                    minDate: new Date(),
                    scrollMonth : false,
                    scrollInput : false,
                    validateOnBlur:false,
                });
            });

            $('#search_cond_clear').click(function () {
                $('[name=student_name]').val('');
                $('[name=student_no]').val('');
                $('[name^=_student_types]').prop('checked', true);
            });

            $('#select_all').click(function () {
                if ($(this).is(':checked')){
                    $('.select_rec').prop('checked', true);
                } else {
                    $('.select_rec').attr('checked', false);
                }
            });
            $('.select_rec').click(function () {
                if (!$(this).is(':checked')){
                    $('#select_all').attr('checked', false);
                }
            });
            $("#search_button").click(function() {
                $("#search_form").attr('action', '{{$_app_path}}class/studentList');
                $("#search_form").submit();
                return false;
            });

            // 会員追加・会員削除
            $("#btn_submit").click(function() {
                $('.message_area_js').hide();
                // validate
                if ($('.select_rec:checked').length == 0) {
                    $('.message_area_js').show();
                    return;
                }
                // student add  
                @if (request('mode')==1)
                if (!validate_chedule()) {
                    return;
                }
                @endif

                @if (request('mode')==1)
                var title =  '{{$lan::get('add_title')}}' ;
                // var content = '{{$lan::get('add_member_confirm_title,'.$class_info['class_name'])}}';
                var content = '{{$lan::get('add_member_confirm_title')}}';
                @else
                var title =  '{{$lan::get('member_delete_title')}}' ;
                // var content = '{{$lan::get('delete_member_confirm_title,'.$class_info['class_name'])}}';
                var content = '{{$lan::get('delete_member_confirm_title')}}';
                @endif
                
                
                var action_url = '{{$_app_path}}class/studentProc';
                common_save_confirm(title, content,action_url);

                return false;
            });

            $("#btn_return").click(function() {
                $("#action_form").attr('action', '{{$_app_path}}class');
                $("#action_form input[name='mode']").val(0);
                $("#action_form").submit();
                return false;
            });



        });

        $(function() {
            $("#school_type").change(function(){
                var school_cat = $(this).val();
                if (!school_cat)
                {
                    $("#grade_option option").remove();
                    $("#grade_option").prepend($("<option>").html("").val(""));
                    return;
                }
                $.get(
                    "{{$_app_path}}ajaxMailMessage/school",
                    {school_cat:school_cat},
                    function(data)
                    {
                        var desc = "{{$lan::get('birth_year_title')}}";
                        $("#grade_option option").remove();
                        $("#grade_option").append($("<option>").html(desc).val(key));
                        for(var key in data.grades)
                        {
                            var school_year_id = (parseInt(key)) + 1;
                            var school_year = school_year_id + desc;
                            $("#grade_option").append($("<option>").html(school_year).val(school_year_id));
                        }
                        $("#grade_option").prepend($("<option>").html("").val(""));
                        $("#grade_option").val("");
                    },
                    "jsonp"
                );
            });
            /* 生徒住所の都道府県 */
            $("#address_pref").change(function() {
                var pref_cd = $(this).val();
                if (pref_cd == "") {
                    $("#address_city option").remove();
                    $("#address_city").prepend($("<option>").html("").val(""));
                    $("#selectaddress_city").text("");
                    return;
                }
                $.get(
                    "{{$_app_path}}ajaxSchool/city",
                    {pref_cd: pref_cd},
                    function(data) {
                        /* 市区町村 */
                        $("#address_city option").remove();
                        for(var key in data.city_list){
                            $("#address_city").append($("<option>").html(data.city_list[key]).val(key));
                        }
                        $("#address_city").prepend($("<option>").html("").val(""));
                        $("#address_city").val("");
                        $("#selectaddress_city").text("");
                    },
                    "jsonp"
                );
            });

        });

    </script>
    <style>
        .search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover, .submit2:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .search_box #search_cond_clear {
            height: 29px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .top_btn li {
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .btn_search {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        input[type="button"] {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .submit2 {
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            font-size: 14px;
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
@stop