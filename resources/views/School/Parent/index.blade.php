@extends('_parts.master_layout')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<link href="/css/display_box_search.css" rel="stylesheet">
<script src="/js/display_box_search.js"></script>
<script type="text/javascript">

$(function() {

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
            "/school/ajaxSchool/city",
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
    $("#btn_add_parent").click(function() {
        $("#link_form").attr('action', '/school/parent/edit');
        $("#link_form").submit();
        return false;
    });
    $("#btn_search").click(function() {
        @if (isset($parent_search))
        $("#search_form").attr('action', '/school/parent/list2');
        @else
        $("#search_form").attr('action', '/school/parent/list');
        @endif
                $("#search_form").submit();
            return false;
    });
    
    @foreach ($parent_list as $row)
        $("#btn_link{{array_get($row, 'orgparent_id')}}").click(function() {
            $("input[name='orgparent_id']").val({{$row['orgparent_id']}});

        
            $("#link_form").attr('action', '{{$_app_path}}student/edit');
            $("#link_form").submit();
            return false
        });
    @endforeach
    @foreach ($parent_list as $row)
        $("#btn_href{{$row['orgparent_id']}}").click(function() {
            $("input[name='orgparent_id']").val({{$row['orgparent_id']}});
            $("#link_form{{$row['orgparent_id']}}").attr('action', '{{$_app_path}}parent/detail');
            $("#link_form{{$row['orgparent_id']}}").submit();
            return false
        });
@endforeach
    //$(".tablesorter").tablesorter();


    $('#search_cond_clear').click(function() {  // clear
        $("input[name='_c[search_name]']").val("");
        $("input[name='_c[search_code]']").val("");
        $("#sort_field").val(1);
        $("#search_student_type").val('');
        $("select[name='_c[search_pref]']").val("");
        $("select[name='_c[search_city]']").val("");
        $("#address_city option").remove();
        $("select[name='_c[school_category]']").val("");
        $("select[name='_c[school_year]']").val("");
        $("#school_grade option").remove();
        $("#payment_method").val("");

    });
});
</script>
<style>
    .table1 th,.table1 td{
        text-align: left;
    }
    .table1 th {
        padding: 15px 0 5px 10px !important;
    }
    .table1 td {
        word-break: break-all;
        padding: 10px !important;
    }
    .body_scroll_table tbody{
        width: 100%;
    }
    .top_btn li:hover, .search_box #search_cond_clear:hover, .submit:hover, .btn_search:hover,input[type="button"]:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
        text-shadow: 0 0px #FFF;
    }
    .search_box #search_cond_clear, .submit {
        height: 31px;
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
</style>
        <div id="center_content_header" class="box_border1">
            <h2 class="float_left"><i class="fa fa-user-secret"></i> {{$lan::get('main_title')}}</h2>
            <div class="clr"></div>
        </div><!--center_content_header-->

        <div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
            <div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
        </div>
        <div class="search_box box_border1 padding1" id="display_box_search">

            <ul class="message_area">@if (session()->get('message_type'))
            <li class="alert alert-success" role="alert" style="color: blue;">
            {{session()->pull('message_type')}}</li> @endif 
        </ul>
        
            @if (isset($message))
                @if(message_type=='99')
                    <ul class="message_area">
                        <li class="error_message">
                            {{$lan::get('occurred')}}
                        </li>
                    </ul>
                @elseif ($message_type==98)
                    <ul class="message_area">
                        <li class="error_message">
                            {{$lan::get('not_deleted')}}
                        </li>
                    </ul>
                @else
                    <ul class="message_area">
                        <li class="info_message">
                        @if ($message_type==1)
                            {{$lan::get('registered')}}
                        @elseif( $message_type==2)
                            {{$lan::get('updated')}}
                        @elseif( $message_type==3)
                            {{$lan::get('deleted')}}
                        @elseif( $message_type==10)
                            {{$lan::get('insertcount')}}
                        @endif
                        </li>
                    </ul>
                @endif
                <br/>
            @endif
            
            <form id="search_form" method="post" id="display_box_search">
            {{ csrf_field() }}
                <table>
                    <tr>
                        <th style="width:10%;">{{$lan::get('name_search_new_title')}}</th>
                        <td style="width:30%;">
                            <input class="text_long" type="search" name="_c[search_name]" value="@if(isset($request->_c['search_name'])) {{$request->_c['search_name']}}@endif" placeholder="{{$lan::get('name_search_new_placeholder')}}"/>
                        </td>
                        <th style="width:10%;">{{$lan::get('payment_method')}}</th>
                        <td style="width:30%;">
                            <select name="_c[search_payment_method]" id="payment_method">
                                <option value=""></option>
                                @if(isset($paymentMethodList))
                                    @foreach($paymentMethodList as $key => $item)
                                    <option value="{{$key}}" @if(isset($request->_c['search_payment_method']) && $request->_c['search_payment_method'] ==$key) selected @endif   >{{$lan::get($item)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="width:10%;">{{$lan::get('student_no')}}</th>
                        <td>
                            <input class="text_long" type="search" name="_c[search_code]" value="@if(isset($request->_c['search_code'])) {{$request->_c['search_code']}}@endif" placeholder="{{$lan::get('student_no')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                        <th style="width:10%;">{{$lan::get('member_type_new_text')}}</th>
                        <td style="width:30%;">
                            <select name="_c[student_type]" id="search_student_type">
                                <option value=""></option>
                                {{--<option value="1" @if(isset($request->_c['student_type']) && $request->_c['student_type']==1) selected @endif>{{$lan::get('student_type_4')}}</option>
                                <option value="2" @if(isset($request->_c['student_type']) && $request->_c['student_type']==2) selected @endif>{{$lan::get('student_type_5')}}</option>--}}
                                @if(isset($list_student_type))
                                    @foreach($list_student_type as $k => $v)
                                        <option value="{{$v['id']}}" @if(isset($request->_c['student_type']) && $request->_c['student_type']==$v['id']) selected @endif>{{$v['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="clr"></div>

                <!-- <input style="height:29px;padding: 5px 10px !important;width: 150px !important;" type="submit" class="submit" name="query_button" id="btn_search" value="{{$lan::get('search')}}"/> -->
                <button class="btn_search" type="submit" name="query_button" id="btn_search" style="height:31.2px;width: 150px !important; font-size: 14px;font-weight: normal;color: #595959;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search')}}</button>
                <input type="button" class="submit" style="font-size: 14px;font-weight: normal; color: #595959;" id="search_cond_clear" value="{{$lan::get('clear')}}"/>
            </form>
        </div>
<div class="clr"></div>

<h3 id="content_h3"><b>{{$lan::get('list')}}</b></h3>
{{--<div class="search_box box_border1 padding1">--}}
        <div id="section_content1">
            <div style="float: left;margin-top: 10px;margin-left: 3px;">
                <p>{{request('total_records')}}{{$lan::get('items_list')}}</p>
            </div>
            <div class="center_content_header_right">
                <div class="top_btn">
                    @if (!isset($parent_search))
                        <ul>
                            @if($edit_auth)
                            <a href="/school/parent/entry"><li style="color: #595959; font-weight: normal;"><i class="fa fa-plus"></i> {{$lan::get('billing_registration')}}</li></a>

                            <a href="{{$_app_path}}label/index?type=2"><li style="color: #595959; font-weight: normal;"><i class="fa fa-print"></i> {{$lan::get('label_export_csv_title')}}</li></a>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
            <table style="width: 100%" class="table1 body_scroll_table">
                <thead>
                    <tr>
                        <th class="text_title header sort_parent_name" style="width:20%" data-sort="1"> {{$lan::get('claimant_name')}}
                            <i style="font-size:12px; "class="fa fa-chevron-down"></i>
                        </th>
                        <th style="width: 30%;" class="text_title header sort_mail_address" data-sort="1"> {{$lan::get('mail_address')}}
                            <i style="font-size:12px; "class="fa fa-chevron-down"></i>
                        </th>
                        <th style="width: 10%;" class="text_title header sort_student_type" data-sort="1">{{$lan::get('student_type')}}
                            <i style="font-size:12px; "class="fa fa-chevron-down"></i>
                        </th>
                        <th style="width: 8%;" class="text_title header sort_invoice_type" data-sort="1">{{$lan::get('invoice_type')}}
                            <i style="font-size:12px; "class="fa fa-chevron-down"></i>
                        </th>
                        <th style="width: 8.5%;" class="text_title header sort_mail_infomation" data-sort="1">{{$lan::get('mail_infomation')}}
                            <i style="font-size:12px; "class="fa fa-chevron-down"></i>
                        </th>
                        <th style="width: 8.5%;" class="text_title header"> {{$lan::get('membership_number')}}</th>
                        <th> </th>
                    </tr>
                </thead>
            </table>
            <div class="over_content">
            @foreach ($parent_list as $key=> $row)
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading parent_default {{--@if(isset($row['hojin']) && $row['hojin']==1 ) parent_company @else parent_default @endif --}}">
                            <table class="parent_row {{array_get(array_get($row,'student_list'),'student_category')}}">
                                <tr>
                                    @if (array_get($row,'invoice_type') == 2)
                                        @if (array_get($row, 'check_register') != 1)
                                            <td style="width: 20%"><a style="color: red !important;" class="text_link" href="{{$_app_path}}parent/detail?id={{array_get($row, 'student_list.id')}}&orgparent_id={{array_get($row, 'orgparent_id')}}&from_parent_top=1">{{array_get($row, 'parent_name')}}</a><span class="parent_name_kana" style="display:none">{{array_get($row,'name_kana')}}</span></td>
                                        @else
                                            <td style="width: 20%"><a class="text_link" href="{{$_app_path}}parent/detail?id={{array_get($row, 'student_list.id')}}&orgparent_id={{array_get($row, 'orgparent_id')}}&from_parent_top=1">{{array_get($row, 'parent_name')}}</a><span class="parent_name_kana" style="display:none">{{array_get($row,'name_kana')}}</span></td>
                                        @endif
                                    @else
                                        <td style="width: 20%"><a class="text_link" href="{{$_app_path}}parent/detail?id={{array_get($row, 'student_list.id')}}&orgparent_id={{array_get($row, 'orgparent_id')}}&from_parent_top=1">{{array_get($row, 'parent_name')}}</a><span class="parent_name_kana" style="display:none">{{array_get($row,'name_kana')}}</span></td>
                                    @endif
                                    <td style="width: 30%;">{{array_get($row, 'parent_mailaddress1')}}<span class="mail_address" style="display:none">{{array_get($row,'parent_mailaddress1')}}</span></td>
                                    <td style="width: 10%;">@if(isset($row['hojin']) && $row['hojin']==1){{$lan::get('student_type_5')}}
                                        @else {{$lan::get('student_type_4')}} @endif<span class="student_type" style="display:none">{{array_get($row,'hojin')}}</span></td>
                                    <td style="width: 8%;" >
                                        @if($row['invoice_type'])
                                            <li style = "text-align: center; list-style-type: none; margin : auto; width : 100px; border-radius: 5px;background-color: {{\App\Common\Constants::invoice_background_color[$row['invoice_type']]['top']}} ; background: linear-gradient(to bottom, {{\App\Common\Constants::invoice_background_color[$row['invoice_type']]['top']}} 0%, {{\App\Common\Constants::invoice_background_color[$row['invoice_type']]['bottom']}} 100%); color :white ; font-weight: 500" >
                                                {{\App\Common\Constants::$invoice_type[2][$row['invoice_type']]}}
                                            </li>
                                        @endif
                                        <span class="invoice_type" style="display:none">{{array_get($row,'invoice_type')}}</span>
                                    </td>
                                    <td style="width: 8.5%;text-align: right;" >
                                        @if($row['mail_infomation']) {{\App\ConstantsModel::$mail_infomation[2][$row['mail_infomation']]}} @endif
                                        <span class="mail_infomation" style="display:none">{{array_get($row,'mail_infomation')}}</span>
                                    </td>
                                    <td style="width: 8.5%;"></td>
                                    @if(!empty(array_get($row, 'student_list')))
                                        <td class="drop_down" data-toggle="collapse" href="#collapse{{array_get($row,'orgparent_id')}}"><i style="font-size:16px; text-align: center; padding-left: 70px; "class="fa fa-chevron-down"></i></td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            </table>
                    </div>
                    <div id="collapse{{array_get($row,'orgparent_id')}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table class="student_row">
                                @foreach (array_get($row, 'student_list') as $student_row)
                                @if(array_get($student_row, 'student_category')==2)
                                <tr  class="captain_row">
                                    <td style="width: 20%;padding-left: 10px;">
                                            {{array_get($student_row, 'student_name')}} @if(session()->get('school.login.show_number_corporation') == 1)({{array_get($student_row, 'total_member')}}人) @endif<br/>
                                    </td>
                                    <td style="width: 30%;">
                                            {{array_get($student_row, 'mailaddress')}}<br/>
                                    </td>
                                    <td style="width: 15%;padding-left: 10px;">
                                        {{array_get($student_row, 'name')}}
                                        <br/>
                                    </td>
                                    <td  style="width: 16%; padding-left: 20px;"></td>
                                    <td style="width: 12%;padding-left: 20px;">
                                            {{array_get($student_row, 'student_no')}}<br/>
                                    </td>
                                    <td></td>
                                </tr>
                                @else
                                    <tr>
                                        <td style="width: 20%;padding-left: 10px;">
                                            {{array_get($student_row, 'student_name')}}<br/>
                                        </td>
                                        <td style="width: 30%;">
                                            {{array_get($student_row, 'mailaddress')}}<br/>
                                        </td>
                                        <td style="width: 15%;padding-left: 10px;">
                                            {{array_get($student_row, 'name')}}
                                            <br/>
                                        </td>
                                        <td  style="width: 16%; padding-left: 20px;"></td>
                                        <td style="width: 12%;padding-left: 20px;">
                                            {{array_get($student_row, 'student_no')}}<br/>
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>
<style>
    body{
        margin: 0;
        padding: 0;
        min-width: 700px;
        background: rgba(246, 246, 246, 0.74) !important;
    }
    table.tablesorter{
        margin-bottom: 0px !important;
    }
    .panel-group{
        margin-bottom: 5px !important;
    }
    .panel-heading{
        border : none !important;
    }
    .panel-default{
        border-color: white !important;
    }
    .panel-default .parent_default {
        background-color: #f5f5f5 !important;
    }
    .panel-default .parent_company {
        background-color: #ffd991 !important;
    }
    .panel-default .panel-heading:hover{
        background-color: #e8e8e8 !important;
    }
    .panel-default .parent_company:hover{
        background-color: #fccc5d !important;
    }
    .parent_row,.student_row{
        width: 100%;
        word-break: break-all;

    }
    .parent_row td,.student_row td{
        padding-right: 10px;
        padding-top:5px;
        padding-bottom:5px;
    }
    .fa {
        margin:0px !important;
    }
    .panel-body{
        background-color: white;
    }
    /*.student_row .captain_row{
        background-color: #fce6bf;
    }*/
    .student_row tr td{
        background: #FBEFEF;
        padding :10px;
    }
    .student_row tr td:first-child{
        width: 12%;
        background: #A9BCF5;
    }
    .student_row .captain_row td {
        color: black;
    }
    .over_content{
        height: 400px;
        overflow: scroll;
    }
    .drop_down{
        cursor: pointer;
    }
    input[type="search"] {
        padding: 1px 5px;
        font-size: 13px;
    }
    #section_content1 .table1{
        margin-top: 45px;
    }
    #section_content1{
        padding-top: 10px;
    }
    #content_h3 {
        margin-top: 0px !important;
    }
    #center_content_header{
        height:40px;
        margin-top: -20px;
    }
</style>
<script>
    $(function(){
        $( window ).resize(function(){
            render_header();
        })
        $(".drop_down").click(function(e){
            e.preventDefault();

            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
                //add css
                //$(this).closest(".panel-heading").attr('style','background-color:#85cdf7 !important;color:white');


            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
                //add css
                //$(this).closest(".panel-heading").attr('style','background-color:#e8e8e8 !important;color:black');
//                $(this).closest(".parent_company").attr('style','background-color:#ffd991 !important;color:black');

            }
        })
        $(".sort_parent_name").click(function (e) {
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }
            var arr_header=[];
            $(".panel-group").each(function () {
                arr_header.push([$(this).find('.parent_name_kana').text(),$(this)]);
            });
            if($(this).data("sort")==1){
                console.log ($(this).data());
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
                $(".over_content").append(value[1]);
            });
        });
        $(".sort_mail_address").click(function (e) {
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }
            var arr_header=[];
            $(".panel-group").each(function () {
                arr_header.push([$(this).find('.mail_address').text(),$(this)]);
            });
            if($(this).data("sort")==1){
                console.log ($(this).data());
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
                $(".over_content").append(value[1]);
            });
        });
        $(".sort_student_type").click(function (e) {
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }
            var arr_header=[];
            $(".panel-group").each(function () {
                arr_header.push([$(this).find('.student_type').text(),$(this)]);
            });
            if($(this).data("sort")==1){
                console.log ($(this).data());
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
                $(".over_content").append(value[1]);
            });
        });
        $(".sort_invoice_type").click(function (e) {
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }
            var arr_header=[];
            $(".panel-group").each(function () {
                arr_header.push([$(this).find('.invoice_type').text(),$(this)]);
            });
            if($(this).data("sort")==1){
                console.log ($(this).data());
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
                $(".over_content").append(value[1]);
            });
        });
        $(".sort_mail_infomation").click(function (e) {
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }
            var arr_header=[];
            $(".panel-group").each(function () {
                arr_header.push([$(this).find('.mail_infomation').text(),$(this)]);
            });
            if($(this).data("sort")==1){
                console.log ($(this).data());
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
                $(".over_content").append(value[1]);
            });
        });
        function render_header(){
            var num =0;
            $(".parent_row").find('tr').first().find('td').each(function(){
                var width = $(this).width();
                $(".table1").find('th:eq('+num+')').width(width+10);
                num++;
            });
        }
        render_header();
    })
</script>
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

@stop
