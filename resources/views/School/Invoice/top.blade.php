<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/invoice.css" />
@extends('_parts.master_layout') @section('content')
	@include('_parts.invoice.invoice_menu')
    <link href="/css/display_box_search.css" rel="stylesheet">
    <script src="/js/display_box_search.js"></script>
    <style>
        .small_button li{
            width: 65px !important;
            padding : 4px 5px 5px !important;
            margin-right: 3px !important;
            font-size: 12px !important;
        }
        label{
            font-weight: 100;
        }
        table .accordion_tbl , table .content_accordion{
            width: 100%;
        }
        .accordion_tbl td{
            padding: 10px 5px 5px !important;
        }
        .panel-heading{
            width: 100%;
            padding:0px !important;
        }
        .table_list th{
            text-align: center;
        }
        #btn_return:hover, #btn_clear:hover, .btn_search:hover, .div-btn li:hover, input[type="button"]:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .search_box #search_cond_clear, #btn_clear {
            padding-top: 3px;
            border-radius: 5px;
            height: 29.5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .btn_search {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .div-btn li, #btn_return {
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
    </style>
    <div class = "search_box">
        <h2>{{$lan::get('list_screen_message')}}</h2>
    </div>
    <div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
        <div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
    </div>
    <div class = "search_box box_border1 padding1" id="display_box_search">
        <form method="POST" id = "search_frm" action="/school/invoice/list">
            <input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}">
            {{ csrf_field() }}
            <table>
                <tbody>
                    <tr>
                        <th>{{$lan::get('payment_method_search_title')}}</th>
                        <td>
                            @foreach($invoice_type as $k => $type)
                                <label><input type = "checkbox" name="invoice_type_search[]" value="{{$k}}" @if(in_array($k,$filter) || empty($filter)) checked @endif /><span style="font-size: 12px !important;">{{$type}}</span></label> &nbsp;&nbsp;
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="frm_search" value="1">
            <div class="clr">&nbsp;</div>
            <button type="submit" class="btn_search" name="search_button" id="btn_student_search"><i class="fa fa-search"></i>{{$lan::get('search')}}</button>
            <button type="button" class="btn_search" id="btn_clear" style="padding: 3px 10px !important; width: 150px !important;"><i class="fa fa-close"></i>{{$lan::get('dp_clear_search')}}</button>
        </form>
    </div>
    <div id="section_content">
        @if(isset($request->errors))
            <ul class="message_area"><li class="error_message">{{$lan::get($request->errors)}}</li></ul>
        @endif
        @if(isset($request->failed_count) && $request->failed_count > 0)
            <ul class="message_area"><li class="error_message">{{$lan::get('invoice_count_error')}}{{$request->failed_count}}</li></ul>
        @endif
        @if(isset($request->messages))
            <ul class="message_area"><li class="info_message">{{$lan::get($request->messages)}}</li></ul>
        @endif

        <p style="font-weight: bold; font-size: 14px; color: black">{{$lan::get('inv_remark')}}</p>
        <input type="checkbox" id="selectall">&nbsp;{{$lan::get('select_all_title')}}</input><br>
        <form id="action_form" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}">
            <table class="table_list tablesorter" id="data_table">
                <thead style="width: 100%">
                <tr>
                    <th style="width:5%;text-align: center" class="text_title">{{$lan::get('selection_title')}}</th>
                    <th style="width:20%;text-align: left !important;" class="text_title header">{{$lan::get('guardian_fullname_title')}}</th>
                    <th style="width:30%;text-align: center" class="text_title header">{{$lan::get('status_title')}}</th>
                    <th style="width:10%;text-align: center" class="text_title header">{{$lan::get('invoice_amount_title')}}</th>
                    <th style="width:15%;text-align: center" class="text_title header">{{$lan::get('payment_method_search_title')}}</th>
                    <th style="width:15%;text-align: center" class="text_title header">{{$lan::get('invoice_change_date_title')}}</th>
                    <th style="width:2%;"></th>
                </tr>
                </thead>
                <tbody>
                @if(empty($invoice_list))
                    <tr class="table_row">
                        <td class="error_row" colspan="6">{{$lan::get('information_displayed_title')}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            @if(isset($invoice_list))
                @foreach ($invoice_list as $idx => $row)
                    {{--<tr class="table_row">--}}
                        {{--<td style="width:50px;text-align:center;">--}}
                            {{--@if(array_get($row, 'workflow_status') < 1 && array_get($row, 'active_flag') == 1)--}}
                                {{--<input type="checkbox" name="parent_ids[]" value="{{array_get($row, 'id')}}" class="parent_select"/>--}}
                            {{--@endif--}}
                        {{--</td>--}}
                        {{--<td style="width:140px;">--}}
                            {{--@foreach (array_get($row, 'student_list') as $student_row)--}}
                                {{--<a  href="{{$_app_path}}invoice/detail?id={{array_get($row, 'id')}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}" class="text_link">--}}
                                    {{--{{array_get($student_row, 'student_name')}}--}}
                                {{--</a><br/>--}}
                            {{--@endforeach--}}
                        {{--</td>--}}
                        {{--<td style="width:380px;text-align:center;">--}}
                            {{--<ul class="progress_ul small_button ">--}}
                                {{--@if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 0)--}}
                                    {{--<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>--}}
                                {{--@else--}}
                                    {{--<li class="bill1">{{$lan::get('status_imported_title')}}</li>--}}
                                {{--@endif--}}
                                {{--@if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 1)--}}
                                    {{--<li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>--}}
                                {{--@else--}}
                                    {{--<li class="bill2">{{$lan::get('confirmed_title')}}</li>--}}
                                {{--@endif--}}
                                {{--@if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 11)--}}
                                    {{--<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>--}}
                                {{--@else--}}
                                    {{--<li class="bill3">{{$lan::get('invoiced_title2')}}</li>--}}
                                {{--@endif--}}
                                {{--@if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 31)--}}
                                    {{--<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>--}}
                                {{--@else--}}
                                    {{--<li class="bill4">{{$lan::get('payment_already_title')}}</li>--}}
                                {{--@endif--}}
                            {{--</ul>--}}

                        {{--</td>--}}
                        {{--<td style="width:110px;text-align:right;">--}}
                            {{--@if(array_get($row, 'amount_display_type') == "0" or !array_get($row, 'sales_tax_rate'))--}}
                                {{--{{number_format(array_get($row, 'amount'))}}--}}
                            {{--@else--}}
                                {{--@php--}}
                                    {{--$x = array_get($row, 'amount');--}}
                                    {{--$y = array_get($row, 'sales_tax_rate');--}}
                                    {{--$amount_tax = $x+floor($x*$y);--}}
                                {{--@endphp--}}
                                {{--{{number_format($amount_tax)}}--}}
                            {{--@endif--}}
                        {{--</td>--}}
                        {{--<td style="width:100px;text-align:center!important; ">--}}
                            {{--<li style="margin: auto; width: 100px;border-radius: 5px;list-style-type: none;text-align: center;background-color: {{$invoice_background_color[$row['invoice_type']]}};color:white">--}}
                            {{--<li style = "list-style-type: none; margin : auto; width : 100px; border-radius: 5px;background-color: {{$invoice_background_color[$row['invoice_type']]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[$row['invoice_type']]['top']}} 0%, {{$invoice_background_color[$row['invoice_type']]['bottom']}} 100%); color :white ; font-weight: 500" >--}}
                                {{--{{$invoice_type[$row['invoice_type']]}}--}}
                            {{--</li>--}}
                        {{--</td>--}}
                        {{--<td style="width:120px;text-align:center;">--}}
                            {{--@if(array_get($row, 'register_date'))--}}
                                {{--{{Carbon\Carbon::parse(array_get($row, 'register_date'))->format('Y-m-d')}}--}}
                            {{--@endif--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table class="accordion_tbl">
                                    <tbody>
                                        <tr>
                                            <td style="width:5%; text-align: center;" class="text_title header">
                                                @if(array_get($row, 'workflow_status') < 1 && array_get($row, 'active_flag') == 1)
                                                    <input type="checkbox" name="parent_ids[]" value="{{array_get($row, 'id')}}" class="parent_select"/>
                                                @endif
                                            </td>
                                            {{--<td style="width:20%;" class="text_title header">--}}
                                                    {{--<a  href="{{$_app_path}}invoice/detail?id={{array_get($row, 'id')}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}" class="text_link">--}}
                                                    {{--{{array_get($row, 'parent_name')}}--}}
                                                    {{--</a><br/>--}}
                                            {{--</td>--}}
                                            @if (array_get($row, 'invoice_type') == 2 && array_get($row, 'check_register') != 1)
                                                <td style="width:20%;" class="text_title header">
                                                    <a  href="{{$_app_path}}parent/edit?orgparent_id={{array_get($row, 'parent_id')}}" class="text_link" style="color: red !important;">
                                                        {{array_get($row, 'parent_name')}}
                                                    </a><br/>
                                                </td>
                                            @else
                                                <td style="width:20%;" class="text_title header">
                                                    <a  href="{{$_app_path}}invoice/detail?id={{array_get($row, 'id')}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}" class="text_link">
                                                        {{array_get($row, 'parent_name')}}
                                                    </a><br/>
                                                </td>
                                            @endif
                                            <td style="width:30%; text-align: center;" class="text_title header">
                                                <ul class="progress_ul small_button ">
                                                @if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 0)
                                                <li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
                                                @else
                                                <li class="bill1">{{$lan::get('status_imported_title')}}</li>
                                                @endif
                                                @if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 1)
                                                <li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
                                                @else
                                                <li class="bill2">{{$lan::get('confirmed_title')}}</li>
                                                @endif
                                                @if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 11)
                                                <li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
                                                @else
                                                <li class="bill3">{{$lan::get('invoiced_title2')}}</li>
                                                @endif
                                                @if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 31)
                                                    @if(array_get($row, 'workflow_status') == 29 && array_get($row, 'error_code'))
                                                            <li class="bill4 transfer_error">{{$lan::get('not_payment_title')}}({{array_get($row, 'error_code')}})</li>
                                                    @else
                                                            <li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
                                                    @endif
                                                @else
                                                <li class="bill4">{{$lan::get('payment_already_title')}}</li>
                                                @endif
                                                </ul>
                                            </td>
                                            <td style="width:10%; text-align:right;" class="text_title header">
                                                @if(array_get($row, 'amount_display_type') == "0" or !array_get($row, 'sales_tax_rate'))
                                                {{number_format(array_get($row, 'amount'))}}
                                                @else
                                                @php
                                                $x = array_get($row, 'amount');
                                                $y = array_get($row, 'sales_tax_rate');
                                                $amount_tax = $x+floor($x*$y);
                                                @endphp
                                                {{number_format($amount_tax)}}
                                                @endif
                                            </td>
                                            <td style="width:15%; text-align: center;" class="text_title header">
                                                <li style = "list-style-type: none; margin : auto; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[$row['invoice_type']]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[$row['invoice_type']]['top']}} 0%, {{$invoice_background_color[$row['invoice_type']]['bottom']}} 100%); color :white ; font-weight: 500" >
                                                {{$invoice_type[$row['invoice_type']]}}
                                                </li>
                                            </td>
                                            <td style="width:10%; text-align: center;" class="text_title header">
                                                @if(array_get($row, 'register_date'))
                                                {{Carbon\Carbon::parse(array_get($row, 'register_date'))->format('Y-m-d')}}
                                                @endif
                                            </td>
                                            <td  style="width:5%;text-align: center" data-toggle="collapse" href="#collapse_{{array_get($row, 'id')}}" class="drop_down"><i  class="fa fa-chevron-down"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="collapse_{{array_get($row, 'id')}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="content_accordion" border="1">
                                        <thead>
                                            <tr>

                                                <th  style="width:20%; text-align: center;">{{$lan::get('member_name_title')}}</th>
                                                <th  style="width:20%; text-align: center;">{{$lan::get('dp_student_no')}}</th>
                                                <th  style="width:20%; text-align: center;">{{$lan::get('dp_student_type')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width:20%;" class="text_title header">
                                                    @foreach (array_get($row, 'student_list') as $student_row)
                                                        {{array_get($student_row, 'student_name')}}<br/>
                                                    @endforeach
                                                </td>
                                                <td style="width:20%">
                                                    @foreach (array_get($row, 'student_list') as $student_row)
                                                        {{array_get($student_row, 'student_no')}}<br/>
                                                    @endforeach
                                                </td>
                                                <td style="width:20%">
                                                    @foreach (array_get($row, 'student_list') as $student_row)
                                                        {{array_get($student_row, 'student_type_name')}}<br/>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            @if( isset($invoice_list) && $edit_auth)
                <input type="submit" value="{{$lan::get('determine_invoice_title')}}" id="btn_confirm" class="btn_green"/><br/><br/>
            @endif
            <button id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left" style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
        </form> <!-- action_form -->
    </div>
    <form action="/school/invoice" method="post" id="frm_return">
        {{ csrf_field() }}
    </form>

<script>
    $(function () {
        $("#btn_return").click(function () {
            $("#frm_return").submit();
        })
        $("#btn_clear").click(function () {
            $("input[name='invoice_type_search[]']").prop('checked',false);
        })
        $('#selectall').click(function() {  //on click

            if(this.checked) { // check select status
                $('.parent_select').each(function() { //loop through each checkbox
                    if(!this.disabled)
                        this.checked = true;  //select all checkboxes with class "question_select"
                });
            }else{
                $('.parent_select').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "question_select"
                });
            }
        });
        $('#btn_confirm').click(function() {
            $("#action_form").attr("action", "{{$_app_path}}invoice/confirm");
            $("#action_form").submit();
            return false;
        });
        $(".drop_down").click(function(e){
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }
        });
    });

</script>
@stop
