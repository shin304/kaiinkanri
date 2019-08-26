@extends('_parts.master_layout')

@section('content')
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<link href="/css/display_box_search.css" rel="stylesheet">
<script src="/js/display_box_search.js"></script>
<div id="center_content_header" class="box_border1">
	<h2 class="float_left"><i class="fa fa-group"></i> {{$lan::get('main_title')}}</h2>
	<div class="center_content_header_right">
		<div class="top_btn">
			<ul>

				<li>
                    <a class="edit_btn" href="#downloadtemplate" type="button"><i class="fa fa-file-excel-o"></i>{{$lan::get('csv_template')}}</a>
                </li>
                @if($edit_auth)
				<li>
                    <a href="{{ url('/school/student/uploadinput') }}"><i class="fa fa-upload"></i>{{$lan::get('csv_register_title')}}</a>
                </li>

				<li>
                    <a class="edit_btn" href="#downloadcsv" type="button"><i class="fa fa-download"></i>{{$lan::get('csv_export_tile')}}</a>
                </li>
                @endif
			</ul>
		</div>
	</div>
	<div class="clr"></div>
</div>

<div class="clr"></div>

@if (isset($message))
<ul class="message_area">
	@if($message == '99')
	<ul class="message_area">
		<li class="error_message">
			{{$lan::get('error_occurs_process_execute_title')}}</li>
	</ul>
	@endif
</ul>
@endif

@if (session()->get('status'))
    <ul class="message_area">
        <li class="alert alert-success" role="alert" style="color: blue;">{{session()->pull('status')}}</li>
    </ul>
@endif

    <div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
        <div class="pull-left">{{$lan::get('search_title')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
    </div>
    <div class="search_box box_border1 padding1" id="display_box_search">
    <form action="{{ URL::to('/school/student') }}" id="member_search_form" class="form-horizontal" method="post"　id="display_box_search">
		<table>
			<tr>
                {{--名前(フリガナ)--}}
				<th>{{$lan::get('name_furigana')}}</th>
				<td><input class="text_long" type="search" name="select_word" id="select_word" value="{{$request->select_word}}"
                    placeholder="{{$lan::get('student_name_placeholder')}}"/></td>
                {{--登録日--}}
                <th>{{$lan::get('register_date')}}</th>
                <td>
                    <input type="text" id="from_register_date" class="ip_date_type" name="from_register_date" value="{{$request->from_register_date}}"> ～
                    <input type="text" id="to_register_date" class="ip_date_type" name="to_register_date" value="{{$request->to_register_date}}">
                </td>
			</tr>
            {{--ステータス--}}
			<tr>
				<th>{{$lan::get('status_title')}}</th>
				<td>
					<select name="select_state" id="select_state" style="max-width: 200px;">
						<option value="" {{( array_get($request, 'select_state') && '' == $request->select_state) ? 'selected' : ''}}></option>
						<option label="{{$lan::get('in_teaching_title')}}" value="{{@\App\ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT}}" {{@\App\ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT == $request->select_state ? 'selected' : ''}}>{{$lan::get('in_teaching_title')}}</option>
						<option label="{{$lan::get('withdraw_title')}}" value="{{@\App\ConstantsModel::$MEMBER_STATUS_END_CONTRACT}}" {{@\App\ConstantsModel::$MEMBER_STATUS_END_CONTRACT == $request->select_state ? 'selected' : ''}}>{{$lan::get('withdraw_title')}}</option>
					</select>
				</td>
                {{--変更日--}}
                <th>{{$lan::get('update_date')}}</th>
                <td>
                    <input type="text" id="from_update_date" class="ip_date_type" name="from_update_date" value="{{$request->from_update_date}}"> ～
                    <input type="text" id="to_update_date" class="ip_date_type" name="to_update_date" value="{{$request->to_update_date}}">
                </td>
			</tr>
            {{--会員番号--}}
			<tr>
				<th>{{$lan::get('student_number_title')}}</th>
				<td>
					<input type="text" name="student_no" id="student_no" value="{{$request->student_no}}">
				</td>
                <th>{{$lan::get('valid_date_title').$lan::get('sun_title')}}</th>
                <td>
                    <input type="text" id="valid_date_from" class="ip_date_type" name="valid_date_from" value="{{$request->valid_date_from}}"> ～
                    <input type="text" id="valid_date_to" class="ip_date_type" name="valid_date_to" value="{{$request->valid_date_to}}">
                </td>
			</tr>
            <tr>
                <td></td>
                <td></td>
                <th>{{$lan::get('student_type_title')}}</th>
                <td>
                    <select name="student_type" >
                        <option value=""></option>
                        @foreach($studentTypes as $type)
                            <option value="{{array_get($type, 'id')}}"
                                    @if(request('student_type') == array_get($type, 'id')) selected @endif>{{array_get($type, 'name')}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
		</table>

		<div class="clr"></div>
		<!-- <input type="submit" id="btn_student_search" class="submit" name="search_button" value="検索"> -->
        <button class="btn_search" type="submit" name="search_button" id="btn_student_search" style="height:30px;padding: 1px 10px !important;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>検索</button>
        <input type="button" class="submit" id="search_cond_clear" value="すべてクリア">
		<div class="clr"></div>
		{{ csrf_field() }}
	</form>
    </div>

<div style="float: left">
    <h3 id="content_h3" class="box_border1" >{{$lan::get('summary_title')}}</h3>
</div>
<div style="float: right; margin: 10px 0px 10px 0px">
    <span class="text_blue">{{$lan::get('total_under_contract')}}:</span>{{array_get_not_null($summary,'under_contract', 0)}}{{$lan::get('person')}}
    <span class="text_blue">{{$lan::get('total_end_contract')}}:</span>{{array_get_not_null($summary,'end_contract', 0)}}{{$lan::get('person')}}
    <span class="text_blue">※{{$lan::get('total_title')}}:</span>{{array_get_not_null($summary,'total', 0)}}{{$lan::get('person')}}
</div>
<div class="clr"></div>

<div id="section_content1">
    <p style="float: left">

    </p>
    @if($edit_auth)
        <div class="top_btn" style="float: right">
            <ul>
                <li>
                    <a href="{{ url('/school/student/entry') }}"><i class="fa fa-plus"></i>{{$lan::get('student_register_title')}}</a>
                </li>
                <li>
                    <a class="edit_btn" href="{{$_app_path}}label/index?type=1" type="button"><i class="fa fa-print"></i>{{$lan::get('label_export_csv_title')}}</a>
                </li>
            </ul>
        </div>
    @endif
    <div class="clr"></div>
	<table class="table1 tablesorter  ">
        <thead>
            <tr>
                <th class="text_left header sort_student_name">{{$lan::get('student_name')}}<i style="font-size:12px; "class="fa fa-chevron-down"></i></th>
                <th class="text_left header">{{$lan::get('student_no_title')}}<i style="font-size:12px; "class="fa fa-chevron-down"></i></th>
                <th class="text_left">{{$lan::get('email_address_title')}}</th>
                <th class="text_left">{{$lan::get('student_type_title')}}</th>
                {{--<th class="text_left header">{{$lan::get('register_date')}}<i style="font-size:12px; "class="fa fa-chevron-down"></i></th>--}}
                <th class="text_left header">{{$lan::get('register_title').$lan::get('update_date')}}<i style="font-size:12px; "class="fa fa-chevron-down"></i></th>
                <th class="text_left header">{{$lan::get('valid_date_title').$lan::get('sun_title')}}</th>
            </tr>
        </thead>
        <tbody class="student_content">
            @foreach ($student_list as $row)
            <tr class="table_row">
                <td class="text_left">
                    <a class="text_link" href="/school/student/detail?id={{array_get($row, 'id')}}&from_student_top=1">
                        {{$row['student_name']}}
                        @if(array_get($row, 'student_category') == \App\ConstantsModel::$MEMBER_CATEGORY_CORP && session()->get('school.login.show_number_corporation') == 1)
                            ({{array_get($row, 'total_member')}})
                        @endif
                    </a>
                    <span class="student_name_kana" style="display: none">{{array_get($row, 'student_name_kana')}}</span>
                </td>
                <td class="text_left">{{array_get($row, 'student_no')}}</td>
                <td class="text_left">{{array_get($row, 'mailaddress')}}</td>
                <td class="text_left">{{array_get($row, 'student_type_name')}}</td>
                <td class="text_left">{{Carbon\Carbon::parse(array_get_not_null($row, 'update_date',array_get($row, 'register_date')))->format('Y-m-d')}}</td>
                {{--<td class="text_left">@if(array_get($row, 'valid_date')){{Carbon\Carbon::parse(array_get($row, 'valid_date'))->format('Y-m-d')}}@endif</td>--}}
                @if(date('Y-m-d') > array_get($row, 'valid_date'))
                    <td class="text_left" style=" color: red; font-weight: 600;" >
                        {{array_get($row, 'valid_date')}}
                    </td>
                @else
                    <td class="text_left">
                        {{array_get($row, 'valid_date')}}
                    </td>
                @endif
            </tr>
            @endforeach
            @if(empty($student_list))
                <tr>
                    <td class="" colspan="6">{{$lan::get('no_information_display_title')}}</td>
                </tr>
            @endif
        </tbody>
	</table>
</div>

<script type="text/javascript">
    $(function() {
//        $( "#from_register_date, #to_register_date, #from_update_date, #to_update_date, #valid_date_from, #valid_date_to" ).datepicker({
//            dateFormat: 'yy-mm-dd'
//        });

        // Japanese date time picker
        $.datetimepicker.setLocale('ja');

        $('#from_register_date').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });
        $('#to_register_date').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });

        $('#from_update_date').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });
        $('#to_update_date').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });

        $('#valid_date_from').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });
        $('#valid_date_to').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });


        $(".sort_student_name").click(function (e) {
            e.preventDefault();
//            if($(this).children().hasClass("fa-chevron-down")){
//                $(this).children().removeClass("fa-chevron-down");
//                $(this).children().addClass("fa-chevron-up");
//            }else if($(this).children().hasClass("fa-chevron-up")){
//                $(this).children().removeClass("fa-chevron-up");
//                $(this).children().addClass("fa-chevron-down");
//            }
            var arr_header=[];
            $(".table_row").each(function () {
                arr_header.push([$(this).find('.student_name_kana').text(),$(this)]);
            });
            if($(this).data("sort")==1){
                $(this).data("sort",2);
                arr_header = arr_header.sort(function(a,b) {
                    return (a[0] === b[0]) ? 0 : (a[0] > b[0]) ? -1 : 1
                });
            }else{
                $(this).data("sort",1);
                arr_header = arr_header.sort(function(a,b) {
                    return (a[0] === b[0]) ? 0 : (a[0] < b[0]) ? -1 : 1
                });
            }

            $(".over_content").html('');
            arr_header.forEach(function (value) {
                $(".student_content").append(value[1]);
            });
        });

        $(".tablesorter").tablesorter({
            headers: {
                // Disable sort
                0: {sorter: false},
//                1: {sorter: false},
                2: {sorter: false},
                3: {sorter: false},
            }
        });
        //Change sorter class
        $(".header").click(function (e) {
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }

        });

        $('#search_cond_clear').click(function() {
            $('#member_search_form').find('input[type=text], input[type=search], select').val('');
        });

        $("a[href='#downloadcsv']").click(function() {
            $( "#dialog_active" ).dialog('open');
            return false;
        });
        $("a[href='#downloadtemplate']").click(function() {
            $( "#dialog_active2" ).dialog('open');
            return false;
        });

        $( "#dialog_active" ).dialog({
            title: '{{$lan::get('main_title')}}',
            autoOpen: false,
            width: 500,
            height: 400,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "Shift_JS": function() {
                    $(this).dialog("close");

                    // Toran add log by js - start
                    var log_message = "{{session()->get('school.login.name')}}" + "{{$lan::get('who_export_csv')}}";
                    var date = new Date();
                    var month = date.getMonth()+1 < 10 ? "0"+(date.getMonth()+1) : date.getMonth()+1;
                    var day = date.getFullYear() + "-" + month + "-" + date.getDate();
                    var time = date.toLocaleTimeString([], {timeZone: 'Asia/Tokyo'}).substr(0 , date.toLocaleTimeString().length - 2);
                    log_message = day+ " " + time + " " + log_message;
                    $("#dialog_active div").append( "<p>" + log_message + "</p>");
                    // end

                    java_post("{{$_app_path}}student/export_csv?mode=2");
                    return false;
                },
                "UTF-8": function() {
                    $(this).dialog("close");

                    // Toran add log by js - start
                    var log_message = "{{session()->get('school.login.name')}}" + "{{$lan::get('who_export_csv')}}";
                    var date = new Date();
                    var month = date.getMonth()+1 < 10 ? "0"+(date.getMonth()+1) : date.getMonth()+1;
                    var day = date.getFullYear() + "-" + month + "-" + date.getDate();
                    var time = date.toLocaleTimeString([], {timeZone: 'Asia/Tokyo'}).substr(0 , date.toLocaleTimeString().length - 2);
                    log_message = day+ " " + time + " " + log_message;
                    $("#dialog_active div").append( "<p>" + log_message + "</p>");
                    // end

                    java_post("{{$_app_path}}student/export_csv?mode=1");
                    return false;
                },
                "{{$lan::get('cancel_title')}}": function() {
                    $(this).dialog("close");
                    return false;
                }
            }
        });

        $( "#dialog_active2" ).dialog({
            title: '{{$lan::get('main_title')}}',
            autoOpen: false,
            width: 315,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "Shift_JS": function() {
                    $( this ).dialog( "close" );
                    java_post("{{$_app_path}}student/download_template?mode=2");
                    return false;
                },
                "UTF-8": function() {
                    $( this ).dialog( "close" );
                    java_post("{{$_app_path}}student/download_template?mode=1");
                    return false;
                },
                "{{$lan::get('cancel_title')}}": function() {
                    $( this ).dialog( "close" );
                    return false;
                }
            }
        });
    });
</script>
<style>
    .search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
        text-shadow: 0 0px #FFF;
    }
    .search_box #search_cond_clear {
        font-size: 14px;
        height: 29.5px;
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

</style>
{{--Export CSV Dialog--}}
<div id="dialog_active" >
    {{$lan::get('history_log')}}
    <div style="border: 1px solid #ccc; background-color: rgba(99, 115, 140, 0.09); height: 230px; padding-left: 5px; overflow-y: scroll">
        @foreach($historyLogs as $log)
        <p>{{array_get($log, 'message')}}</p>
        @endforeach
    </div>
    {{$lan::get('write_log_warning')}}
</div>
{{--Export CSV Template Dialog--}}
<div id="dialog_active2" style="display:none;">
    {{$lan::get('select_encode_type')}}
</div>
@stop
