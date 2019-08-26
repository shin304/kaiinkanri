@extends('_parts.master_layout')

@section('content')
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<link href="/css/display_box_search.css" rel="stylesheet">
<script src="/js/display_box_search.js"></script>
<script type="text/javascript">
    $(function() {
        $.datetimepicker.setLocale('ja');
        jQuery(function(){
            jQuery('.DateInput').datetimepicker({
                format: 'Y-m-d',
                timepicker:false,
                scrollMonth : false,
                scrollInput : false
            });
        });

        $('#search_cond_clear').click(function() {  // clear
            $("input[name='search_title']").val("");
            $("input[name='search_start']").val("");
            $("input[name='search_finish']").val("");
            $("#action_form").submit();
            return false;
        });
        $('.edit_row').click(function(e) {
            java_post($(this).attr('href'))
            return false;
        });
        $('.delete_row').click(function(e) {  // delete
            e.preventDefault();
            var id = $(this).data('id');
            // var content = '「' + $(this).data('title') + '」' + '{{$lan::get('delete_confirm_title')}}';
            var content = '{{$lan::get('delete_confirm_title')}}';
            var action_url = '{{$_app_path}}bulletinboard/complete';
            $( "#delete-dialog-confirm" ).dialog({
                title: '{{$lan::get('main_title')}}',
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "{{$lan::get('delete_title')}}": function() {
                        $( this ).dialog( "close" );
                        $("#action_form input[name='delete_id']").val(id);
                        $("#action_form").attr('action', '{{$_app_path}}bulletinboard/delete');
                        $("#action_form").submit();
                        return false;
                    },
                    "{{$lan::get('cancel_title')}}": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
            $( "#delete-dialog-confirm" ).html(content);
            $( "#delete-dialog-confirm" ).dialog('open');
            return false;
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
        height: 29.5px;
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
</style>
<div class="section">
<div id="center_content_header" class="box_border1">
    <h2 class="float_left"><i class="fa fa-newspaper-o"></i>{{$lan::get('main_title')}}</h2>
    <div class="clr"></div>
</div><!--center_content_header-->


    <div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
        <div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
    </div>
    <div class="search_box box_border1 padding1" id="display_box_search">
    <form id="action_form" action="{{$_app_path}}bulletinboard/search" method="post" id="display_box_search">
        {{ csrf_field() }}
        <input type="hidden" name="delete_id" value='0' />
        <table>
            <tr>
                <th style="width:5%;">{{$lan::get('title')}}</th>
                <td style="width:30%;">
                    <input style="width: 65%" type="search" name="search_title" value="{{request('search_title')}}" placeholder="{{$lan::get('title')}}{{$lan::get('please_input_title')}}"/>
                </td>
            </tr>
            <tr>
                <th style="width:5%;">{{$lan::get('period_title')}}</th>
                <td style="width:30%;">
                    <input class="DateInput" type="search" name="search_start" value="{{request('search_start')}}" placeholder="{{$lan::get('start_title')}}{{$lan::get('please_input_title')}}"/>～　<input class="DateInput" type="search" name="search_finish" value="{{request('search_finish')}}" placeholder="{{$lan::get('finish_title')}}{{$lan::get('please_input_title')}}"/>
                </td>
            </tr>
        </table>
        <div class="clr"></div>
        <!-- <input type="submit" class="btn_search" name="search_button" value="{{$lan::get('search_title')}}"><i class="glyphicon glyphicon-search " style="width: 20%;font-size:16px;"></i></input> -->
        <button class="btn_search" type="submit" name="search_button" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search_title')}}</button>
        <input type="button" class="submit" id="search_cond_clear" value="{{$lan::get('clear_title')}}"/>
    </form>
    </div> <!--search_box box_border1 padding1-->

    <h3 id="content_h3" class="box_border1">{{$lan::get('list_title')}}</h3>

    <div id="section_content1">
@if ($message) 
    <ul class="message_area">
        <li class="info_message">{{$lan::get($message)}}</li>
    </ul>
@endif 
    <div class="center_content_header_right">
        <div class="top_btn">
            <ul>
                @if($edit_auth)
                <a href="{{$_app_path}}bulletinboard/input"><li style="color: #595959; font-weight: normal;border-radius: 5px;"><i class="fa fa-plus"></i>{{$lan::get('register_btn_title')}}</li></a>
                @endif
            </ul>
        </div>
    </div>

    <table class="table1" >
        <thead>
            <tr class="head_tr">
                <th class="text_title" style="width:40%;">{{$lan::get('title')}}</th>
                <th class="text_title" style="width:15%;">{{$lan::get('start_title')}}</th>
                <th class="text_title" style="width:15%;">{{$lan::get('finish_title')}}</th>
                <th class="text_title" style="width:20%;">{{$lan::get('target_title')}}</th>
                <th class="text_title" style="width:10%;">{{$lan::get('action_title')}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $row)
                <tr>
                    <td style="text-align: left;padding-left: 1%;">
                        <a class="text_link" href="{{$_app_path}}bulletinboard/detail?id={{$row['id']}}">
                        {{$row['title']}}</a>
                    </td>
                    <td class="text_center">
                        {{$row['start_date']}}
                    </td>
                    <td class="text_center">
                        {{$row['finish_date']}}
                    </td>
                    <td style="text-align: left;">
                        @php
                            $target = explode(',', array_get($row,'target'));
                        @endphp
                        @if ( isset($target[0]) && $target[0] == 1) {{$lan::get($bulletin_target[0])}}&nbsp;@endif
                        @if ( isset($target[1]) && $target[1] == 1) {{$lan::get($bulletin_target[1])}}&nbsp;@endif
                        @if ( isset($target[2]) && $target[2] == 1) {{$lan::get($bulletin_target[2])}}@endif
                        @if ( isset($target[3]) && $target[3] == 1) {{$lan::get($bulletin_target[3])}}@endif
                    </td>
                    <td class="text_center">
                        @if($edit_auth)
                        <a class="edit_row" href="{{$_app_path}}bulletinboard/input?id={{$row['id']}}">
                            <input type="button" value="{{$lan::get('edit_title')}}"/></a>
                        <a class="delete_row" data-id="{{$row['id']}}" data-title="{{$row['title']}}">
                            <input type="button" value="{{$lan::get('delete_title')}}"/></a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="error_row" colspan="5">{{$lan::get('nothing_display_title')}}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div><!--section-->
<div id="delete-dialog-confirm" style="display: none;"></div>
@stop
