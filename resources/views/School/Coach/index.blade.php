@extends('_parts.master_layout')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/coach.css" />
<link href="/css/display_box_search.css" rel="stylesheet">
<script src="/js/display_box_search.js"></script>
<div id="center_content_header" class="box_border1">
    <h2 class="float_left main-title"><i class="fa fa-black-tie"></i>{{$lan::get('main_title')}}</h2>
    <div class="clr"></div>
</div><!--center_content_header-->


<div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
    <div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
</div>
<div class="search_box box_border1 padding1" id="display_box_search">
    <form action="{{$_app_path}}coach/list" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <table>
            <tr>
                <th style="width:10%;">{{$lan::get('name_search_title')}}</th>
                <td style="width:30%;">
                    <input class="text_long" type="search" name="coach_name" value="{{request('coach_name')}}"
                           placeholder="{{$lan::get('name_search_placeholder')}}"/>
                </td>
            </tr>
        </table>
        <div class="clr"></div>
            <!-- <input type="submit" class="submit" name="search_button" value="{{$lan::get('search_title')}}"/> -->
            <button class="btn_search" type="submit" name="search_button" id="btn_search" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search_title')}}</button>
            <input type="button" class="submit" id="search_cond_clear" value="{{$lan::get('clear_all_title')}}"/>
        </form>
</div>
<h3 id="content_h3" class="box_border1 screen-title">{{$lan::get('list_title')}}</h3>
<div id="section_content1">
    @if (request('message_type'))
        @if (request('message_type')==99)
            <ul class="message_area">
                <li class="error_message">
                    {{$lan::get('error_on_process_msg')}}
                </li>
            </ul>
        @else
            <ul class="message_area">
                <li class="info_message">
                    @if (request('message_type')==1)
                        {{$lan::get('registered_msg')}}
                    @elseif (request('message_type')==2)
                        {{$lan::get('updated_msg')}}
                    @elseif (request('message_type')==3)
                        {{$lan::get('deleted_msg')}}
                    @elseif (request('message_type')==10)
                        {{$request('insertcount')}} {{$lan::get('registered_items_count_msg')}}
                    @endif
                </li>
            </ul>
        @endif
        <br/>
    @endif

    <div class="center_content_header_right btn-register">
        <div class="top_btn">
            <ul>
                @if($edit_auth)
                <a href="/school/coach/entry"><li style="color: #595959; font-weight: normal;"><i class="fa fa-plus"></i> {{$lan::get('coach_regist_title')}}</li></a>
                @endif
            </ul>
        </div>
    </div>
    <table class="coach-table-header tablesorter " >
        <thead>
            <tr>
                <th class="text_title header" style="width: 10%">{{$lan::get('profile_avatar_title')}}</th>
                <th class="text_title header sort_button" style="width: 15%" data-sort="1">{{$lan::get('coach_name_title')}}<i class="fa fa-chevron-down"></i></th>
                <th class="text_title header" style="width: 20%">{{$lan::get('email_title')}}</th>
                <th class="text_title header" style="width: 15%">{{$lan::get('mobile_number_title')}}</th>
                <th class="text_title header" style="width: 10%"></th>
            </tr>
        </thead>
    </table>
    
    <div class="over_content">
        @foreach ( $coach_list as $row)
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table class="coach-table-content">
                        <tr>
                            <td style="width: 15%">
                            @php
                                $user_img = "/img/school/default_user.png";
                                if(!empty ($row->profile_img)) {
                                    $user_img = "/image/" . $row->profile_img;
                                }
                            @endphp
                            <img width="64" height="64" src="{{$user_img}}" onclick="goToDetail({{$row->id}})"/></td>
                            <td style="width: 22%">
                                <input type="hidden" name="id" value="{{$row->id}}}" />
                                <a class="text_link coach_name_text" onclick="goToDetail({{$row->id}})" data-name-kana="{{$row->coach_name_kana}}">{{$row->coach_name}}</a>
                            </td>
                            <td style="width: 29%"><a href ="mailto:{{$row->mail_address}}">{{$row->mail_address}}</a></td>
                            <td style="width: 15%">{{$row->mobile_no}}</td>
                            @if(!empty(json_decode(json_encode($row->courses),true)) || !empty(json_decode(json_encode($row->classes),true)) || !empty(json_decode(json_encode($row->programs),true)))
                                <td class="drop_down" data-toggle="collapse" href="#collapse{{$row->id}}"><i style="font-size:16px;" class="fa fa-chevron-down" style="width: 10%"></i></td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    </table>
                </div>
                <div id="collapse{{$row->id}}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <label><input type="checkbox" class="hide_passed_event"> {{$lan::get('not_show_passed_event_title')}}</label>
                        <table class="subject-table" border="1">
                            <tr style="background-color: #f5f5f5;">
                                <th>{{$lan::get('category_title')}}</th>
                                <th>{{$lan::get('name_title_2')}}</th>
                                <th>{{$lan::get('start_date_title')}}</th>
                                <th>{{$lan::get('close_date_title')}}</th>
                            </tr>
                        @foreach($row->courses as $item)
                            <tr data-start="{{Carbon\Carbon::parse($item->close_date)->format('YmdHis')}}">
                                <td>{{$lan::get('course_title')}}</td>
                                <td width="35%">{{$item->course_title}}</td>
                                <td>{{$item->start_date}}</td>
                                <td>{{$item->close_date}}</td>
                            </tr>
                        @endforeach
                        @foreach($row->classes as $item)
                            <tr data-start="{{Carbon\Carbon::parse($item->close_date)->format('YmdHis')}}">
                                <td>{{$lan::get('class_title')}}</td>
                                <td width="35%">{{$item->class_name}}</td>
                                <td>{{$item->start_date}}</td>
                                <td>{{$item->close_date}}</td>
                            </tr>
                        @endforeach
                        @foreach($row->programs as $item)
                            <tr data-start="{{Carbon\Carbon::parse($item->close_date)->format('YmdHis')}}">
                                <td>{{$lan::get('program_title')}}</td>
                                <td width="35%">{{$item->lesson_name}}</td>
                                <td>{{$item->start_date}}</td>
                                <td>{{$item->close_date}}</td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @forelse ($coach_list as $row)
            @empty
               <div class="error_row">{{$lan::get('record_not_title')}}</div>
        @endforelse
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('#search_cond_clear').click(function() {  // clear
            $("input[name='coach_name']").val("");
        });

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
    $(".sort_button").click(function (e) {
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
            $coach_name_kana = $(this).find('.coach_name_text').data('name-kana');
            arr_header.push([$coach_name_kana, $(this)]);
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
            $(".over_content").append(value[1]);
        });
    });

    $(document).on("change",".hide_passed_event",function(e){
        e.preventDefault();
        var hide    = $(this).is(':checked');
        var now     = parseInt({{date('YmdHis')}});
        var events  = $(this).closest('div').children().find('tr:not([data-start=""])');
        $.each(events,function(idx,event){
            var start_time = parseInt($(this).data('start'));
            if (start_time < now && hide) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    function goToDetail($id) {
        java_post("{{$_app_path}}coach/detail?id=" + $id);
    }

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
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
@stop
