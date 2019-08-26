@extends('_parts.master_layout')
@section('content')
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<link href="/css/display_box_search.css" rel="stylesheet">
<script src="/js/display_box_search.js"></script>
<script type="text/javascript">
    $(function() {
        $(".tablesorter").tablesorter({headers: {
            3: { sorter: false},
            4: { sorter: false},
            5: { sorter: false},
        }});

        $.datetimepicker.setLocale('ja');
        jQuery(function(){
            jQuery('.DateTimeInput').datetimepicker({
                format: 'Y-m-d H:i', 
                step : 5,
                scrollMonth : false,
                scrollInput : false

            });
        });
        jQuery(function(){
            jQuery('.DateInput').datetimepicker({
                format: 'Y-m-d', 
                timepicker:false,
                scrollMonth : false,
                scrollInput : false
            });
        });

        $('#search_cond_clear').click(function() {  // clear
            //$("input[name='_c[input_search]']").val("");
            $("input[name='_c[name]'], input[name='_c[recruitment_from]'], input[name='_c[recruitment_to]'], input[name='_c[start_date_from]'], input[name='_c[start_date_to]']").val("");
            $("input[name='add_caption']").prop("checked",false);
            action_form.submit();
        });

        $( "#exportcsv_dialog" ).dialog({
        title: '{{$lan::get('csv_export_title')}}',
        autoOpen: false,
        dialogClass: "no-close",
        resizable: false,
        modal: true,
        buttons: {
            "OK": function() {
                $( this ).dialog( "close" );
                href = $('#href_clone').val() + $('[name=encode_option]:checked').val();
                location.href = href;
                return false;
            },
            "{{$lan::get('cancel_title')}}": function() {
                $( this ).dialog( "close" );
            }
        }
        });

        $('.btn-export').click(function(e) {
            e.preventDefault();
            $('#href_clone').val($(this).attr('href'));
            $('input:radio#mode1').prop('checked', true);
            var res = $( "#exportcsv_dialog" ).dialog('open');

            return false;
        });

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
    input[type="button"] {
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
</style>
    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-list-alt"></i>{{$lan::get('main_title')}}</h2>
        </div><!--center_content_header-->
     <div class="clr"></div>
    <div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
        <div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
    </div>
    <div class="search_box box_border1 padding1" id="display_box_search">

    {{--<div id="topic_list" style="padding: 5px 10px;background:#B0AaA4;color:#fbfbfb;">
    {!! Breadcrumbs::render('school_course') !!}
    </div> 
    @include('_parts.topic_list') --}}
        <div class="search_box box_border1 padding1">
        @if (request('message_type'))
            @if (request('message_type')==99)
            <ul class="message_area">
                <li class="error_message">
                    {{$lan::get('error_processed_occurred')}}
                </li>
            </ul>
            @else
            <ul class="message_area">
                <li class="info_message">
                @if (request('message_type')==1)
                    {{$lan::get('has_register')}}
                @elseif (request('message_type')==2)
                    {{$lan::get('has_update')}}
                @elseif (request('message_type')==3)
                    {{$lan::get('has_delete')}}
                @elseif (request('message_type')==51)
                    {{$lan::get('email_transmission_complete')}}
                @endif
                </li>
            </ul>
            @endif
            <br/>
        @endif

        <form name="action_form" action="{{$_app_path}}course/list" method="post">
        {{ csrf_field() }}
                <table>
                    <tr>
                        <th style="width:5%;">{{$lan::get('event_name_title') }}</th>
                        <td style="width:30%;"><input style="width: 70%" type="search" name="_c[name]" value="{{request('_c.name')}}" placeholder="{{ $lan::get('name_input_title') }}"/></td>
                    </tr>
                     <tr>
                        <th style="width:5%;">{{$lan::get('recruitment_period_title') }}</th>
                        <td style="width:30%;"><input class="DateInput" type="text" name="_c[recruitment_from]" value="@if (request('_c.recruitment_from')) {{date('Y-m-d',strtotime(request('_c.recruitment_from')))}} @endif" placeholder="{{$lan::get('start_message')}}"/>
                        {{$lan::get('to_title')}}
                        <input class="DateInput" type="text" name="_c[recruitment_to]" value="@if (request('_c.recruitment_to')) {{date('Y-m-d',strtotime(request('_c.recruitment_to')))}} @endif" placeholder="{{$lan::get('end_message')}}"/> </td>
                    </tr>
                    <tr>
                        <th style="width:5%;">{{$lan::get('open_start_date_time') }}</th>
                        <td style="width:30%;"><input class="DateTimeInput" type="text" name="_c[start_date_from]" value="@if (request('_c.start_date_from')) {{date('Y-m-d H:i',strtotime(request('_c.start_date_from')))}} @endif" placeholder="{{$lan::get('start_message')}}"/>
                        {{$lan::get('to_title')}}
                        <input class="DateTimeInput" type="text" name="_c[start_date_to]" value="@if (request('_c.start_date_to')) {{date('Y-m-d H:i',strtotime(request('_c.start_date_to')))}} @endif" placeholder="{{$lan::get('end_message')}}"/> </td>
                    </tr>
                </table>
                <div class="clr"></div>
                <!-- <input type="submit" class="submit" name="search_button" value="{{$lan::get('search')}}"/> -->
                <button class="btn_search" type="submit" name="search_button" id="btn_search" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search')}}</button>
                <input type="button" class="submit" id="search_cond_clear" value="{{$lan::get('clear_all')}}"/>
        </form>
        </div>
</div> <!--search_box box_border1 padding1-->

    <h3 id="content_h3" class="box_border1">{{$lan::get('lists')}}</h3>
        <div id="section_content1">
        <!-- イベント登録 -->
        <div class="center_content_header_right">
            <div class="top_btn">
                <ul>
                    @if($edit_auth)
                    <a href="{{$_app_path}}course/courseentry"><li style="color: #595959; font-weight: normal;"><i class="fa fa-plus"></i>{{$lan::get('course_registration')}}</li></a>
                    @endif
                </ul>
            </div>

        </div>
        <div class="clr"></div>

        @if (request('add_caption'))
        @else
        <table class="table1 tablesorter" >
            <thead>
                <tr>
                    <th class="text_title header" style="width:140px;">{{$lan::get('event_name_title')}}<i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
                    <th class="text_title header" style="width:100px;">{{$lan::get('recruitment_finish_title')}}<i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
                    <th class="text_title header" style="width:130px;">{{$lan::get('hold_date_time')}}<i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
                    <th class="text_title" style="width: 70px;">{{$lan::get('member_per_total')}}</th>
                    <th class="text_title" style="width:100px;">{{$lan::get('guide_mail')}}<br/>{{$lan::get('send_view')}}</th>
                    <th class="text_title" style="width: 70px;"><!--enter_list_export_title--></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($list as $row)
            <tr class="table_row">
                <td style="width:140px;text-align: left;padding-left: 20px;">
                    @if($edit_auth)
                    <a class="text_link" href="{{$_app_path}}course/courseentry?course_id={{array_get($row,'id')}}" >
                    {{array_get($row,'course_title')}}</a>
                    @else
                        {{array_get($row,'course_title')}}
                    @endif
                </td>
                <!-- 募集締切日 -->
                <td style="width:100px;">@if (array_get($row,'recruitment_finish')) {{date('Y-m-d', strtotime(array_get($row,'recruitment_finish')))}} @endif</td>
                 <!-- 開始日時 -->
                <td style="width:130px;">@if (array_get($row,'start_date')) {{date('Y-m-d H:i', strtotime(array_get($row,'start_date')))}} @endif</td>
                <td style="width: 70px;padding:4px 10px;">
                @if (array_get($row,'member_capacity'))
                    @php
                        $total_member = (array_get($row,'non_member_flag') == 1)? (array_get($row,'member_capacity')+array_get($row,'non_member_capacity')) : array_get($row,'member_capacity');
                    @endphp
                    {{array_get($row,'student_count')}}/{{$total_member}}
                @else 
                    {{array_get($row,'student_count')}}/_
                @endif
                     
                </td>
                <td style="width:100px;">
                    {{-- @if (array_get($row,'is_active')) --}}
                    @if (array_get($row,'send_mail_flag'))
                        <a class="text_link" href="{{$_app_path}}mailMessage/select?relative_id={{array_get($row,'id')}}&msg_type_id={{array_get($row,'type_id')}}&event_type_id={{array_get($row,'type2_id')}}&enable_send_mail=1" >
                        @if (isset($row['recruitment_finish']) && array_get($row,'recruitment_finish') < date('Y-m-d H:i:s'))
                            <input type="button" value="{{$lan::get('recruitment_finished_title')}}"/>
                        @elseif (array_get($row,'mail_sent')=="済")
                            {{array_get($row,'mail_sent')}}({{array_get($row,'mail_viewed')}}/{{array_get($row,'mail_count')}})
                        @else
                            <input type="button" value="{{$lan::get('guide_mail')}}"/>
                        @endif
                        </a>
                    @else
                        <a class="text_link" href="{{$_app_path}}mailMessage/select?relative_id={{array_get($row,'id')}}&msg_type_id={{array_get($row,'type_id')}}&event_type_id={{array_get($row,'type2_id')}}" ><input type="button" value="{{$lan::get('member_register_title')}}"/></a>
                    @endif 


                    {{-- @else
                    <p style="color:gray;">{{array_get($row,'mail_sent')}}({{array_get($row,'mail_count')}}/{{array_get($row,'mail_viewed')}})</p>
                    @endif --}}
                </td>
                <td>
                    @if (array_get($row,'student_count') > 0 && $edit_auth)
                    <a href="{{$_app_path}}course/exportcsv?event_id={{array_get($row,'id')}}" class="btn btn-export">
                    <input type="button" value="{{$lan::get('csv_export_title')}}"></a>
                    @endif
                </td>
            </tr>
            @endforeach
            @forelse ($list as $row)
                @empty
                    <tr>
                    <td class="error_row" colspan="6">{{$lan::get('no_information_displayed')}}</td>
                    </tr>
            @endforelse
            </tbody>
        </table>
        @endif
        </div><!--section_content-->
    <div id="exportcsv_dialog">
        <input type="hidden" id="href_clone" value="">
        <!-- select_encode_title -->
        <span>
            {{$lan::get('export_confirm')}}
        </span>
        <br /> <br />
        <input type="radio" name="encode_option" id="mode1" value="&mode=1" checked><label for="mode1">Shift-JIS(Excel)</label>&nbsp;&nbsp;
        <input type="radio" name="encode_option" id="mode2" value="&mode=2"><label for="mode2">UTF-8</label>
        </span>
    </div>
@stop