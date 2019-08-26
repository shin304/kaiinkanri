<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/invoice.css" />
@extends('_parts.master_layout') @section('content')
<link href="/css/display_box_search.css" rel="stylesheet">
<script src="/js/display_box_search.js"></script>
    <style>
        .small_button li{
            width: 65px !important;
            padding : 4px 5px 5px !important;
            margin-right: 3px !important;
            font-size: 12px !important;
        }
        .checkbox_grid li {
            display: block;
            float: left;
            width: 27%;
            padding-left: 0 !important;
            font-weight: normal;
        }
        label {
            font-weight: normal;
        }
        th > label {
            font-size: 12px;
            font-weight: bold;
        }
        .select_long {
            width: 270px;
        }
        .panel-group{
            margin-bottom: 0px !important;
        }
        .panel-default{
            border-color: white !important;
        }
        .panel-default .panel-heading:hover{
            background-color: #e8e8e8 !important;
        }
        .panel-body{
            background-color: white;
        }
        .over_content{
            height: 400px;
            overflow: scroll;
        }
        .text_date {
            width: 110px;
        }
        ul.message_area {
            margin-bottom: 0;
        }
        .panel-group table tr td {
            padding-left: 10px;
            padding-right: 10px;
        }
        .date_picker_custom {
            left: 450px !important;
        }
        .xdsoft_datetimepicker .xdsoft_month {
            width: 60px !important;
        }
        .xdsoft_datetimepicker {
            padding: 0 !important;
        }
        .btn_select_year_month, .btn_reset_year_month {
            margin-left: 5px;
            margin-top: 4px;
        }

        #accordion_table_header {
            border-bottom: solid 4px #DCDDDD;
            border-top: solid 4px #DCDDDD;
            margin-bottom: 10px !important;
            padding-right: 17px; /* padding for scroll bar y */
        }

        #accordion_table_header table tr td {
            color: #63738c;
            font-weight: bold;
            font-size: 13px;
        }

        #accordion_table_header table tr td:last-child {
            font-size: 15px;
        }

        #accordion_table_header .panel-default .panel-heading:hover {
            background-color: white !important;
        }

        #accordion_table_header .panel-default>.panel-heading {
            background-color: white;
        }
        .search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover, #export_csv:hover, .submit_return:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .search_box #search_cond_clear {
            height: 29.5px;
            padding-top: 3px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .top_btn li {
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
        #export_csv, .submit_return {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
        .box-display>.cls-display {
            padding-top: 8px;
        }
        #content_h3 {
            color: #5a5a5a;
            margin-bottom: 10px !important;
            font-weight: 700;
            font-size: inherit
        }

    </style>
    {{--Header--}}
    <div id="center_content_header" >
        <div class="c_content_header_left">
            <h2 class="float_left"><i class="fa fa-yen"></i>{{$lan::get('dp_deposit_management_title')}}</h2><br/>
        </div>
        <div class="clr"></div>
    </div>

    {{--Update message--}}
    @if (session()->get('deposit_status'))
    <div class="alart_box box_shadow">
        <ul class="message_area">
            <li class="info_message">{{session()->pull('deposit_status')[1]}}</li>
        </ul>
    </div>
    @endif

    {{--Search conatainer--}}
    <div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
        <div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
    </div>
    <div class="search_box" id="display_box_search">
        <form id="form_deposit" action="{{URL::to('/school/invoice/deposit')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="chk_filter">
            <input type="hidden" name="class_id">
            <input type="hidden" name="course_id">
            <input type="hidden" name="program_id">
            <input type="hidden" name="invoice_type_ids">
            <input type="hidden" name="student_type_ids">
            <table>
                {{--名前(フリガナ)--}}
                <tr>
                    <th>{{$lan::get('dp_name_furigana')}}</th>
                    <td>
                        <input class="text_long" type="text" name="name_furigana" id="" value="{{request()->name_furigana}}" placeholder="{{$lan::get('dp_name_furigana_holder')}}"/>
                    </td>
                </tr>
                {{--会員番号--}}
                <tr>
                    <th>{{$lan::get('dp_student_no')}}</th>
                    <td>
                        <input type="text" class="text_long" name="student_no" value="{{request()->student_no}}" placeholder="{{$lan::get('dp_student_no')}}">
                    </td>
                </tr>
                {{--会員種別--}}
                <tr>
                    <th>{{$lan::get('dp_student_type')}}</th>
                    <td>
                        <ul class="checkbox_grid">
                            @foreach ($student_types as $type)
                                <li>
                                    <label>
                                        <input type="checkbox" name="student_type_ids[]" value="{{array_get($type, 'id')}}" @if (in_array(array_get($type, 'id'), array_get_not_null(request()->all(), 'student_type_ids', array()))) checked @endif>
                                        {{array_get($type, 'name')}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                {{--請求月--}}
                <tr>
                    <th>{{$lan::get('dp_invoice_year_month')}}</th>
                    <td>
                        <div style="position: relative; display: inline-block">
                            <input type="text" name="invoice_year_month_from" id="invoice_year_month_from" class="text_date" value="{{request()->invoice_year_month_from}}" placeholder="{{$lan::get('start_message')}}">
                            <input type="text" id="invoice_year_month_picker_from" style="width: 0; border: none; outline: none; background: none; left: 0; position: absolute; z-index: -1" value="{{request()->invoice_year_month_from}}-01">
                            {{$lan::get('dp_from')}}
                        </div>
                        <div style="position: relative; display: inline-block">
                            <input type="text" name="invoice_year_month_to" id="invoice_year_month_to" class="text_date" value="{{request()->invoice_year_month_to}}" placeholder="{{$lan::get('end_message')}}">
                            <input type="text" id="invoice_year_month_picker_to" style="width: 0; border: none; outline: none; background: none; left: 0; position: absolute; z-index: -1" value="{{request()->invoice_year_month_to}}-01">
                            {{$lan::get('dp_to')}}
                        </div>
                    </td>
                </tr>
                {{--入金済み--}}
                <tr>
                    <th>{{$lan::get('payment_day_title')}}</th>
                    <td>
                        <div style="position: relative; display: inline-block">
                            <input type="text" name="payment_date_from" id="payment_date_from" class="text_date" value="{{request()->payment_date_from}}" placeholder="{{$lan::get('start_message')}}">
                            {{$lan::get('dp_from')}}
                        </div>
                        <div style="position: relative; display: inline-block">
                            <input type="text" name="payment_date_to" id="payment_date_to" class="text_date" value="{{request()->payment_date_to}}" placeholder="{{$lan::get('end_message')}}">
                            {{$lan::get('dp_to')}}
                        </div>
                    </td>
                </tr>
                {{--支払方法--}}
                <tr>
                    <th>{{$lan::get('dp_payment_method')}}</th>
                    <td>
                        <ul class="checkbox_grid">
                            @foreach ($invoice_types as $type)
                                <li>
                                    <label>
                                        <input type="checkbox" name="invoice_type_ids[]" value="{{array_get($type, 'payment_method_value')}}" @if (in_array(array_get($type, 'payment_method_value'), array_get(request()->all(), 'invoice_type_ids', array()))) checked @endif>
                                        {{$lan::get(array_get($type, 'payment_method_name'))}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                {{--入金状態--}}
                <tr>
                    <th>{{$lan::get('dp_workflow_status')}}</th>
                    <td>
                        <ul class="checkbox_grid">
                            <li>
                                <label>
                                    <input type="checkbox" name="workflow_status[]" value="11" @if (in_array(11, array_get(request()->all(), 'workflow_status', array()))) checked @endif>
                                    {{$lan::get('dp_no_deposit')}}
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" name="workflow_status[]" value="31" @if (in_array(31, array_get(request()->all(), 'workflow_status', array()))) checked @endif>
                                    {{$lan::get('dp_deposited')}}
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                {{--プラン--}}
                <tr>
                    <th>
                        <label>

                            <input type="checkbox" class ="chk_filter" name="chk_filter[]" value="1" id="class_filter" @if(request()->has('class_id') || $request->method()=='GET' || in_array(1, array_get_not_null(request()->all(), 'chk_filter', array()))) checked @endif>
                            {{$lan::get('dp_class')}}
                        </label>
                    </th>
                    <td>
                        <select name="class_id" id="class_id"  class="select_long" href="#class_filter" @if(!request()->has('class_id')) disabled @endif>
                            <option value=""></option>
                            @foreach ($classes as $class)
                                <option value="{{array_get($class, 'id')}}" @if (array_get($class, 'id') == request()->class_id) selected @endif>{{array_get($class, 'class_name')}}</option>
                            @endforeach
                        </select>
                        <span href="#class_filter" class="@if(!request()->has('class_id')) display_none @endif">{{$lan::get('dp_or')}}</span>
                    </td>
                </tr>
                {{--イベント--}}
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class ="chk_filter" name="chk_filter[]" value="2" id="course_filter" @if(request()->has('course_id') || $request->method()=='GET' || in_array(2, array_get_not_null(request()->all(), 'chk_filter', array()))) checked @endif>
                            {{$lan::get('dp_event')}}
                        </label>
                    </th>
                    <td>
                        <select name="course_id" id="course_id" class="select_long" href="#course_filter" @if(!request()->has('course_id')) disabled @endif>
                            <option value=""></option>
                            @foreach ($courses as $course)
                                <option value="{{array_get($course, 'id')}}" @if (array_get($course, 'id') == request()->course_id) selected @endif>{{array_get($course, 'course_title')}}</option>
                            @endforeach
                        </select>
                        <span href="#course_filter" class="@if(!request()->has('course_id')) display_none @endif">{{$lan::get('dp_or')}}</span>
                    </td>
                </tr>
                {{--プログラム--}}
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class ="chk_filter" name="chk_filter[]" value="3" id="program_filter" @if(request()->has('program_id') || $request->method()=='GET' || in_array(3, array_get_not_null(request()->all(), 'chk_filter', array()))) checked @endif>
                            {{$lan::get('dp_program')}}
                        </label>
                    </th>
                    <td>
                        <select name="program_id" id="program_id" class="select_long" href="#program_filter" @if(!request()->has('program_id')) disabled @endif>
                            <option value=""></option>
                            @foreach ($programs as $program)
                                <option value="{{array_get($program, 'id')}}" @if (array_get($program, 'id') == request()->program_id) selected @endif>{{array_get($program, 'program_name')}}</option>
                            @endforeach
                        </select>
                        <span href="#program_filter" class="@if(!request()->has('program_id')) display_none @endif">{{$lan::get('dp_or')}}</span>
                    </td>
                </tr>
            </table>

            <button class="btn_search" type="submit" name="search_button" id="btn_student_search"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('dp_search')}}</button>
            <button class="submit" type="button" id="search_cond_clear">{{$lan::get('dp_clear_search')}}</button>
        </form>
    </div>
    <h3 id="content_h3" class="box_border1">{{$lan::get('summary_title')}}</h3>
    <div id="section_content">
        <div style="height: 37px">
            <label class="float_left" style="padding-top: 5px">
                <input type="checkbox" id="check_all">
                {{$lan::get('dp_check_all')}}
            </label>
            <button type="button" class="float_right" id="export_csv"><i class="fa fa-download"></i>{{$lan::get('dp_export_csv')}}</button>
            {{--<a href="/school/invoice/deposit_export" class="fa fa-download">--}}
                {{--<input class="float_right" id="export_csv" type="button" value="{{$lan::get('dp_export_csv')}}"></a>--}}
        </div>

        {{--<table style="width: 100%" class="table1 body_scroll_table">--}}
            {{--<thead>--}}
            {{--<tr>--}}
                {{--<th style="width: 5%;" class="text_title header">{{$lan::get('dp_check')}}</th>--}}
                {{--<th style="width: 20%;" class="text_title header sort_parent_name"  data-sort="1">{{$lan::get('dp_parent_student_name')}}--}}
                    {{--<i style="font-size:12px; "class="fa fa-chevron-down"></i>--}}
                {{--</th>--}}
                {{--<th style="width: 15%; text-align: center" class="text_title header sort_invoice_date"  data-sort="1">{{$lan::get('dp_summary')}}--}}
                    {{--<i style="font-size:12px; "class="fa fa-chevron-down"></i>--}}
                {{--</th>--}}
                {{--<th style="width: 10%; text-align: center" class="text_title header">{{$lan::get('dp_amount')}}</th>--}}
                {{--<th style="width: 15%; text-align: center" class="text_title header">{{$lan::get('dp_payment_method')}}</th>--}}
                {{--<th style="width: 12%; text-align: center" class="text_title header">{{$lan::get('payment_day_title')}}</th>--}}
                {{--<th style="width: 12%; text-align: center" class="text_title header">{{$lan::get('dp_sent_reminder_mail')}}</th>--}}
                {{--<th> </th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--@if (!$invoices)--}}
            {{--<tr>--}}
                {{--<td colspan="6" align="center">{{$lan::get('dp_no_result')}}</td>--}}
            {{--</tr>--}}
            {{--@endif--}}
        {{--</table>--}}

        {{--Header--}}
        <div class="panel-group" id="accordion_table_header">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 5%">{{$lan::get('dp_check')}}</td>
                            <td style="width: 20%" class="parent_name sort_parent_name" data-sort="1">
                                {{$lan::get('dp_parent_student_name')}}
                                <i style="font-size:12px;" class="fa fa-chevron-down"></i>
                            </td>
                            <td style="width: 20%" class="invoice_date sort_invoice_date" data-sort="1">
                                {{$lan::get('dp_summary')}}
                                <i style="font-size:12px;" class="fa fa-chevron-down"></i>
                            </td>
                            <td style="width: 10%"  class="invoice_amount sort_invoice_amount" align="right">{{$lan::get('dp_amount')}}
                                <i style="font-size:12px;" class="fa fa-chevron-down"></i>
                            </td>
                            <td style="width: 15%" class="invoice_payment_method sort_invoice_payment_method" align="center">{{$lan::get('dp_payment_method')}}
                                <i style="font-size:12px;" class="fa fa-chevron-down"></i>
                            </td>
                            <td style="width: 14%" align="center">{{$lan::get('payment_day_title')}}</td>
                            <td style="width: 14%" align="center">{{$lan::get('dp_sent_reminder_mail')}}</td>
                            <td style="width: 2%" align="center">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        @if (!$invoices)
                        <tr>
                            <td colspan="8" align="center">{{$lan::get('dp_no_result')}}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        @if ($invoices)
        <form id="deposit_process_form" action="{{URL::to('/school/invoice/deposit_process')}}" method="post">
            {{csrf_field()}}
            <div class="over_content">
                {{--Data--}}
                @foreach ($invoices as $invoice)
                <div class="panel-group">
                    <div class="panel panel-default">
                        {{--Front Data--}}
                        <div class="panel-heading">
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 5%"><input type="checkbox" class="invoice_header_checkbox" name="invoice_header_ids[]" data-deposited="@if (array_get($invoice, 'workflow_status') == 31) 1 @else 0 @endif" value="{{array_get($invoice, 'id')}}"></td>
                                    <td style="width: 20%" >{{array_get($invoice, 'parent_name')}}<span class="parent_name" style="display: none">{{array_get($invoice, 'parent_name_kana')}}</span></td>
                                    @if(array_get($invoice,'is_nyukin')==0)
                                        <td style="width: 20%" class="invoice_date"><a target="_blank" href="{{$_app_path}}invoice/detail?id={{array_get($invoice,'id')}}&invoice_year_month={{array_get($invoice,'invoice_year_month')}}">{{Carbon\Carbon::parse(array_get($invoice,'invoice_year_month'))->format('Y年m月')}}{{$lan::get("dp_invoice_name")}}</a></td>
                                    @elseif(array_get($invoice,'is_nyukin')==1)
                                        <td style="width: 20%" class="invoice_date"><a target="_blank" href="/portal/event/?message_key={{array_get($invoice,'link')}}&view=1">{{array_get($invoice,'item_name')}}</a></td>
                                    @else
                                        <td style="width: 20%" class="invoice_date"><a target="_blank" href="/portal/program/?message_key={{array_get($invoice,'link')}}&view=1">{{array_get($invoice,'item_name')}}</a></td>
                                    @endif
                                    <td style="width: 10%" class="invoice_amount" align="right">{{number_format(array_get($invoice, 'amount', 0))}}</td>
                                    <td style="width: 15%" align="center" class="invoice_payment_method">
                                        <?php
                                        $type = $invoice['invoice_type'];
                                        if (array_get($invoice, 'is_recieved') == 1 && !empty( $invoice['deposit_invoice_type'])) {
                                            $type = $invoice['deposit_invoice_type'];
                                        }
                                        ?>
                                        <li style = "list-style-type: none;text-align: center; margin : auto; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[$type]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[$type]['top']}} 0%, {{$invoice_background_color[$type]['bottom']}} 100%); color :white ; font-weight: 500" >
                                            {{$invoice_type[$type]}}
                                        </li>
                                    </td>
                                    <td style="width: 14%" align="center">
                                        @if(array_get($invoice, 'paid_date'))
                                        {{Carbon\Carbon::parse(array_get($invoice,'paid_date'))->format('Y-m-d')}}
                                        @endif
                                    </td>
                                    <td style="width: 14%" align="center">
                                        @if (array_get($invoice, 'deposit_reminder_date'))
                                        {{Carbon\Carbon::parse(array_get($invoice,'deposit_reminder_date'))->format('Y-m-d H:i:s')}}
                                        @endif
                                    </td>
                                    @if(array_get($invoice, 'student_list'))
                                    <td style="width: 2%" class="cursor_pointer drop_down" align="center" data-toggle="collapse" href="#collapse_{{array_get($invoice, 'id')}}">
                                        <i style="font-size:16px;" class="fa fa-chevron-down"></i>
                                    </td>
                                    @else
                                    <td style="width: 2%; font-size: 15px">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    @endif
                                </tr>
                            </table>
                        </div>
                        {{--Sub data--}}
                        <div id="collapse_{{array_get($invoice, 'id')}}" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table style="width: 100%" class="content_accordion" border="1">
                                    <thead>
                                    <tr>
                                        <th  style="width:20%; text-align: center;">{{$lan::get('member_name_title')}}</th>
                                        <th  style="width:20%; text-align: center;">{{$lan::get('dp_student_no')}}</th>
                                        <th  style="width:20%; text-align: center;">{{$lan::get('dp_student_type')}}</th>
                                    </tr>
                                    </thead>
                                    @foreach (array_get($invoice, 'student_list', array()) as $student)
                                    <tr style="height: 30px">
                                        <td style="width: 20%">{{array_get($student, 'student_name')}}</td>
                                        <td style="width: 20%">{{array_get($student, 'student_no')}}</td>
                                        <td style="width: 20%">{{array_get($student, 'student_type_name')}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <br>
            <div>
                <button type="submit" name="action" id="deposit_all" class="submit_return" value="1">{{$lan::get('dp_deposit_all')}}</button>
                <button type="submit" name="action" class="submit_return" value="2">{{$lan::get('dp_deposit_single')}}</button>
                <button type="submit" name="action" id="reminder" class="submit_return">{{$lan::get('dp_sent_announce_mail')}}</button>
            </div>
        </form>
        @endif
    </div>

    <script type="text/javascript">
        $.datetimepicker.setLocale('ja');
        $('#invoice_year_month_picker_from').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false,
            className: 'date_picker_custom_from',
            onShow: function(currentTime, input) {
                $('.date_picker_custom_from .xdsoft_calendar').hide();
                $('.date_picker_custom_from .xdsoft_next').hide();
                $('.date_picker_custom_from .xdsoft_prev').hide();
                $('.date_picker_custom_from .xdsoft_today_button').hide();
                var monthPickerContainerSelector = $('.date_picker_custom_from .xdsoft_monthpicker');
                if (monthPickerContainerSelector.find('.btn_select_year_month').length == 0) {
                    monthPickerContainerSelector.append('<input type="button" class="btn_select_year_month" value="{{$lan::get('dp_select')}}" onclick="setYearMonth(true)">');
                }
                if (monthPickerContainerSelector.find('.btn_reset_year_month').length == 0) {
                    monthPickerContainerSelector.append('<input type="button" class="btn_reset_year_month" value="{{$lan::get('dp_reset')}}" onclick="resetYearMonth(true)">');
                }
            }
        });
        $('#invoice_year_month_picker_to').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false,
            className: 'date_picker_custom_to',
            onShow: function(currentTime, input) {
                $('.date_picker_custom_to .xdsoft_calendar').hide();
                $('.date_picker_custom_to .xdsoft_next').hide();
                $('.date_picker_custom_to .xdsoft_prev').hide();
                $('.date_picker_custom_to .xdsoft_today_button').hide();
                var monthPickerContainerSelector = $('.date_picker_custom_to .xdsoft_monthpicker');
                if (monthPickerContainerSelector.find('.btn_select_year_month').length == 0) {
                    monthPickerContainerSelector.append('<input type="button" class="btn_select_year_month" value="{{$lan::get('dp_select')}}" onclick="setYearMonth(false)">')
                }
                if (monthPickerContainerSelector.find('.btn_reset_year_month').length == 0) {
                    monthPickerContainerSelector.append('<input type="button" class="btn_reset_year_month" value="{{$lan::get('dp_reset')}}" onclick="resetYearMonth(false)">');
                }
            }
        });


        $('#payment_date_from').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });
        $('#payment_date_to').datetimepicker({
            timepicker: false,
            format:'Y-m-d',
            scrollInput: false,
            scrollMonth: false
        });

        function setYearMonth(isFrom) {
            var datePickerClass = '.date_picker_custom_to';
            var invoiceYearMonthId = '#invoice_year_month_to';
            if (isFrom) {
                datePickerClass = '.date_picker_custom_from';
                invoiceYearMonthId = '#invoice_year_month_from';
            }
            var month = leadingZero(parseInt($(datePickerClass + " .xdsoft_label.xdsoft_month span").text()), 2);
            var year = parseInt($(datePickerClass + " .xdsoft_label.xdsoft_year span").text());
            $(invoiceYearMonthId).val(year + '-' + month);
            $(datePickerClass + ' .xdsoft_date.xdsoft_day_of_week1.xdsoft_date').eq(1).trigger('click');
        }
        function resetYearMonth(isFrom) {
            if (isFrom) {
                $('#invoice_year_month_from').val('');
                $('#invoice_year_month_picker_from').val('').datetimepicker('hide');
            } else {
                $('#invoice_year_month_to').val('');
                $('#invoice_year_month_picker_to').val('').datetimepicker('hide');
            }
        }

        $(document).on('focus', '#invoice_year_month_from', function() {
            $('#invoice_year_month_picker_from').datetimepicker('show');
        });
        $(document).on('focus', '#invoice_year_month_to', function() {
            $('#invoice_year_month_picker_to').datetimepicker('show');
        });

        function leadingZero(str, max) {
            str = str.toString();
            return str.length < max ? leadingZero("0" + str, max) : str;
        }

        $(document).ready(function() {
            $("#common-dialog-confirm").dialog({
                title: '確認',
                autoOpen: false,
                dialogClass: "no-close",
                position: { my: 'top', at: 'top+150' },
                resizable: false,
                modal: true,
                buttons: {
                    "OK": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $(document).on("click",".drop_down",function(e){
                e.preventDefault();
                if ($(this).children().hasClass("fa-chevron-down")) {
                    $(this).children().removeClass("fa-chevron-down");
                    $(this).children().addClass("fa-chevron-up");
                } else if ($(this).children().hasClass("fa-chevron-up")) {
                    $(this).children().removeClass("fa-chevron-up");
                    $(this).children().addClass("fa-chevron-down");
                }
            });

            $(document).on("change", "#check_all", function()  {
                var checked = $(this).prop("checked");
                $(".invoice_header_checkbox").prop("checked", checked);
            });


            $(document).on('change', '#class_filter, #course_filter, #program_filter', function() {
                check_filter();
            });
            check_filter();

            $(document).on("click", "#search_cond_clear", function() {
                $("#form_deposit input[type=text]").val("");
                $("#form_deposit input[type=checkbox]").prop("checked", false);
                $("#class_filter").prop("checked",false);
                $("#course_filter").prop("checked",false);
                $("#program_filter").prop("checked",false);
                check_filter();
                $("#form_deposit select").val("");
            });
            function check_filter(){
                $("#form_deposit .chk_filter").each(function(){
                    var id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        $('span[href=#'+ id +']').show();
                        $('select[href=#'+ id +']').prop('disabled', false);
                    } else {
                        $('span[href=#'+ id +']').hide();
                        $('select[href=#'+ id +']').prop('disabled', true).val('');
                    }
                })
            }
            $(document).on('click', '#deposit_all', function(e) {
                var totalChecked = $("#deposit_process_form input:checked").length;
                if (totalChecked == 0) {
                    e.preventDefault();
                    $("#common-dialog-confirm").html("{{$lan::get('dp_err_invoice_header_checkbox')}}");
                    $("#common-dialog-confirm").dialog('open');
                }
            });

            $( "#exportcsv_dialog" ).dialog({
                title: '{{$lan::get('dp_csv_export_title')}}',
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "OK": function() {
                        $( this ).dialog( "close" );
                        $("#form_deposit").attr('action', "{{$_app_path}}invoice/deposit_export?mode="+$('[name=encode_option]:checked').val());
                        $("#form_deposit").submit();
                        $("#form_deposit").attr('action', "{{$_app_path}}invoice/deposit");
                    },
                    "{{$lan::get('dp_cancel_title')}}": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });

            $(document).on('click', '#export_csv', function(e) {
                $('#href_clone').val($(this).attr('href'));
                $('input:radio#mode1').prop('checked', true);
                var res = $( "#exportcsv_dialog" ).dialog('open');
            });

            $(document).on('click', '#reminder', function(e) {
                var checkbox = $("#deposit_process_form input:checked");
                var totalChecked = checkbox.length;
                if (totalChecked == 0) {
                    e.preventDefault();
                    $("#common-dialog-confirm").html("{{$lan::get('dp_err_invoice_header_checkbox')}}");
                    $("#common-dialog-confirm").dialog('open');
                } else {
                    var canProcess = true;
                    $.each(checkbox, function(index, elem) {
                        if ($(elem).data('deposited') == 1) {
                            e.preventDefault();
                            $("#common-dialog-confirm").html("{{$lan::get('dp_err_send_deposited_reminder')}}");
                            $("#common-dialog-confirm").dialog('open');
                            canProcess = false;
                        }
                    });
                    if (canProcess) {
                        $("#deposit_process_form").attr('action', "{{$_app_path}}invoice/deposit_reminder");
                        $("#deposit_process_form").submit();
                    }
                }
            });

            $(".sort_parent_name").click(function (e) {
                e.preventDefault();
                sort_accordion("parent_name",$(this));
            });

            $(".sort_invoice_date").click(function (e) {
                e.preventDefault();
                sort_accordion("invoice_date",$(this));
            });
            $(".sort_invoice_amount").click(function (e) {
                e.preventDefault();
                sort_accordion("invoice_amount",$(this));
            });
            $(".sort_invoice_payment_method").click(function (e) {
                e.preventDefault();
                sort_accordion("invoice_payment_method",$(this));
            });

            function sort_accordion(className,ele){

                if(ele.children().hasClass("fa-chevron-down")){
                    ele.children().removeClass("fa-chevron-down");
                    ele.children().addClass("fa-chevron-up");
                }else if(ele.children().hasClass("fa-chevron-up")){
                    ele.children().removeClass("fa-chevron-up");
                    ele.children().addClass("fa-chevron-down");
                }
                var arr_header=[];
                $(".over_content .panel-group").each(function () {
                    arr_header.push([$(this).find('.'+className).text(),$(this)]);
                });
                if(ele.data("sort")==1){
                    ele.data("sort",2);
                    arr_header = arr_header.sort(function(a,b) {
                        return (a[0] === b[0]) ? 0 : (a[0] > b[0]) ? -1 : 1
                    });
                }else{
                    ele.data("sort",1);
                    arr_header = arr_header.sort(function(a,b) {
                        return (a[0] === b[0]) ? 0 : (a[0] < b[0]) ? -1 : 1
                    });
                }

                $(".over_content").html('');
                arr_header.forEach(function (value) {
                    $(".over_content").append(value[1]);
                });
            }

        });
    </script>
    <div id="exportcsv_dialog">
        <input type="hidden" id="href_clone" value="">
        <!-- select_encode_title -->
        <span>
            {{$lan::get('dp_export_confirm')}}
        </span>
        <br /> <br />
        <input type="radio" name="encode_option" id="mode1" value="1" checked><label for="mode1">Shift-JIS(Excel)</label>&nbsp;&nbsp;
        <input type="radio" name="encode_option" id="mode2" value="2"><label for="mode2">UTF-8</label>
        </span>
    </div>
@stop
