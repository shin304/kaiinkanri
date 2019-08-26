@extends('_parts.master_layout')

@section('content')
<script type="text/javascript">
$(function() {
    $('#selectall').click(function() {  //on click
        if(this.checked) { // check select status
            $('.question_select').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "question_select"
            });
        }else{
            $('.question_select').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "question_select"
            });
        }
    });
    $("#search_button").click(function() {
        $("#search_form").attr('action', '{{$_app_path}}program/studentList');
        $("#search_form").submit();
        return false;
    });

    $("#btn_submit").click(function() {

        var selects = '';
        $('.question_select').each(function() {
            if (this.checked){
                selects += '1,';
            } else {
                selects += '0,';
            }
        });
        $('[name=selects]').val(selects);

        $("#action_form").attr('action', '{{$_app_path}}program/studentProc');
        $("#action_form").submit();
        return false;
    });

    $("#btn_return").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}program/list');
        $("#action_form input[name='mode']").val(0);
        $("#action_form").submit();
        return false;
    });

    /*$(".tablesorter").tablesorter({
        headers: {
                0: { sorter: false}
        }
    });*/

});

</script>

<script type="text/javascript">
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
<div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-bullhorn"></i>{{$lan::get('main_title')}}</h2>
            <div class="center_content_header_right">
                <div class="top_btn">
                    <ul>
                        <a href="{{$_app_path}}program"><li><i class="fa fa-search"></i><!-- プログラム検索 -->{{$lan::get('ttl_program_inquiry')}}</li></a>
                        @php
                            $edit_auth = false;
                            $menu_no = session('session.menu.number');
                            if(array_key_exists($menu_no, session('menu_auth'))){
                            $edit_auth = (session('menu_auth')[$menu_no]['editable'] == 1)? true : false;
                            }

                        @endphp
                        @if ($edit_auth)
                            <a href="{{$_app_path}}program/input"><li><i class="fa fa-plus"></i><!-- プログラム登録 -->{{$lan::get('ttl_program_register')}}</li></a>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="clr"></div>
        </div><!--center_content_header-->

       @include('_parts.topic_list')

        <h3 id="content_h3" class="box_border1"><!-- プログラム情報 -->{{$lan::get('ttl_program_information')}}</h3>
        <form method="post" id="action_form">
        {{ csrf_field()}}
        <div id="section_content">
            <div class="info_content padding1 box_border1">
                <div class="info_info_right p15">
                    @if (array_get($program_info,'start_date'))<p><!-- 開始日： -->{{$lan::get('ttl_start_date')}}{{date('Y-m-d', strtotime($program_info['start_date']))}}</p>@endif
                    @if (array_get($program_info,'close_date'))<p><!-- 終了日： -->{{$lan::get('ttl_close_date')}}{{date('Y-m-d', strtotime($program_info['close_date']))}}</p>@endif
                </div>
                <p class="info_name p32">{{array_get($program_info,'program_name')}}</p>
                <p class="info_info p18">{{nl2br(array_get($program_info,'description'))}}</p>
                <div class="clr"></div>
            </div>

            <h3 id="content_h3" class="box_border1">@if (request('mode')==1)<!-- 追加 -->{{$lan::get('ttl_msg_add')}}@else <!-- 確認・削除 -->{{$lan::get('ttl_confirm_delete')}}@endif</h3>
            <div class="search_box box_border1 padding1">
           
            <!-- student_search4.html"}} -->
            <table>
                    <tr>
                        <th style="width:10%;">
                            {{$lan::get('name_furigana_title')}}
                        </th>
                        <td style="width:30%;">
                            <input class="text_long" type="search" name="student_name" value="{{request('student_name')}}" >
                        </td>
                        
                    </tr>
                    <tr>
                        <th style="width:10%;">
                            {{$lan::get('member_phone_title')}}
                        </th>
                        <td style="width:30%;">
                            <input class="text_long" type="search" name="student_no" value="{{request('student_no')}}" >
                        </td>
                    </tr>
                    <tr>
                        <th style="width:10%;">
                            {{$lan::get('member_type_title')}}
                        </th>
                        <td style="width:30%;vertical-align:middle;">
                            @if (count(request('_student_types')) > 0)
                            @foreach (request('_student_types') as $index=>$studenttype)
                            <input type="hidden" name="_student_types[{{$index}}][name]" value="{{array_get($studenttype,'name')}}"/>
                            <input type="hidden" name="_student_types[{{$index}}][is_display]" value="{{array_get($studenttype,'is_display')}}"/>
                            <label>
                            <input class="student_types" type="checkbox" id="student_type{{$index}}" name="_student_types[{{$index}}][type]" value="{{array_get($studenttype,'type')}}" @if (array_get($studenttype,'is_display') == '1') checked @endif/>&nbsp;{{array_get($studenttype,'name')}}
                            </label>
                            @endforeach
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="clr"></div>
                <input type="submit" id="btn_student_search"  class="submit" name="search_button" value="{{$lan::get('search_title')}}">
                <div class="clr"></div>
        </div><!-- search_box -->
        <ul class="message_area">
            @if (session()->has('no_data')) @php session()->pull('no_data'); @endphp<li class="error_message"><!-- 選択してください。 -->{{$lan::get('ttl_msg_please_select')}}</li>@endif
            @if (session()->has('errors')) 
                @php $errors =  session()->pull('errors')[0]; @endphp
                @foreach ( $errors as $idx=>$error)
                    @if (isset($error['program_fee_plan_id']))
                        <li class="error_message">{{sprintf($lan::get('ttl_msg_please_select_fee'), $error['number'])}}</li>
                    @endif
                @endforeach
            @endif
        </ul>
        <div id="section_content_in">
            <input type="hidden" name="id" value="{{request('id')}}" />
            <input type="hidden" name="mode" value="{{request('mode')}}" />
            <input type="hidden" name="selects" />
            @if (!(request('mode')==3))
                <input type="checkbox" id="selectall" />&nbsp;&nbsp;<!-- 全て選択 -->{{$lan::get('ttl_select_all')}}<br/>
            @endif
            <table class="table1" >
                <thead>
                <tr class="head_tr">
                    @if (!(request('mode')==3))
                        <th class="text_title" style="width:50px;text-align:center;"><!-- 選択 -->{{$lan::get('ttl_select')}}</th>
                    @endif
                    <th class="text_title" style="width:250px;"><!-- 会員番号 -->{{$lan::get('ttl_member_no')}}</th>
                    <th class="text_title" style="width:170px;"><!-- 会員名 -->{{$lan::get('ttl_member_name')}}</th>
                    <th class="text_title" style="width: 80px;"><!-- 会員種別 -->{{$lan::get('ttl_member_type')}}</th>
                    <th class="text_title" @if (request('mode') == 1) style="width:310px;" @else style="width:300px;"@endif><!-- 受講料種別 | 料金（円） -->{{$lan::get('ttl_fee_type_charge')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as $idx=>$row)
                <tr class="table_row">
                    @if (!(request('mode')==3))
                        <td style="width:50px;text-align:center;">
                            <input type="checkbox" name="students[]"  class="question_select" value="{{array_get($row,'id')}}" />
                        </td>
                    @endif
                    <td style="width:250px;text-align:center;">{{array_get($row,'student_no')}}</td>
                    <td style="width:170px;">
                        <a class="text_link" href="{{$_app_path}}student/detail?id={{array_get($row,'id')}}">
                        {{array_get($row,'student_name')}}</a>
                    </td>
                    <td style="width: 80px;text-align:center">
                        {{array_get($row,'student_type_name')}}
                    </td>
                    <td style="width:300px;">
                        @if (request('mode') == 1)
                        <select  style="width:300px;" name="_program_fee_plan_id[]">
                            {{--<option value=""></option>--}}
                            @foreach ($program_fee_plan as $key=>$value)
                                <option value="{{$key}}" @if (array_get($row,'plan_id') == $key) @endif>{{$value}}</option>
                            @endforeach
                            <!-- html_options options=$program_fee_plan selected=array_get($row,'plan_id')}} -->
                        </select>
                        @else 
                            {{array_get($row,'fee_plan_name')}}&nbsp;|&nbsp;{{array_get($row,'fee')}}
                        @endif
                    </td>
                </tr>
                
                @endforeach
                @forelse ($list as $idx=>$row)
                @empty
                    <tr>
                    <td class="error_row" colspan="4">{{$lan::get('ttl_msg_display_data_not_exists')}}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @if ($list && !(request('mode') == 3))
                <input class="submit2" type="button" value="@if (request('mode')==1)追加 @elseif (request('mode')==2)削除 @endif" id="btn_submit" />
            @endif
        </form>
@stop