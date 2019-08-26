@extends('_parts.master_layout')

@section('content')

<script type="text/javascript">
$(function() {
    $("#btn_return").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}course/list');
        $("#action_form").submit();
        return false;
    });
    $("#btn_submit").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}course/courseentry');
        $("#action_form").submit();
        return false;
    });
    $("#btn_mailmessage").click(function() {

        java_post("{{$_app_path}}mailMessage/select?event_id={{array_get($list,'id')}}&msg_type_id={{array_get($list,'type_id')}}&event_type_id={{array_get($list,'type2_id')}}");
        // location.href = "{{$_app_path}}mailMessage/select/?event_id={{array_get($list,'id')}}&msg_type_id={{array_get($list,'type_id')}}&event_type_id={{array_get($list,'type2_id')}}";
        return false;
    });
    $("a[href='#edit']").click(function() {
        java_post("{{$_app_path}}course/courseentry?course_id={{array_get($list,'id')}}");
        // location.href = '{{$_app_path}}course/courseentry?course_id={{array_get($list,'id')}}';
        return false;
    });
    $( "#dialog-confirm" ).dialog({
        title: '{{$lan::get('main_title')}}',
        autoOpen: false,
        dialogClass: "no-close",
        resizable: false,
        modal: true,
        buttons: {
            "{{$lan::get('delete_title')}}": function() {
                $( this ).dialog( "close" );
                $("#action_form input[name='function']").val("3");
                $("#action_form").attr('action', '{{$_app_path}}course/coursecomplete');
                $("#action_form").submit();
                return false;
            },
            "{{$lan::get('cancel_title')}}": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    $("a[href='#delete']").click(function() {
        $( "#dialog-confirm" ).dialog('open');
        return false;
    });
});

$(function() {
        //$(".tablesorter").tablesorter();
});
</script>

    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-list-alt"></i>{{$lan::get('main_title')}}</h2>
            <div class="center_content_header_right">
                <ul>
                        <a class="edit_btn" href="#edit"><li>{{$lan::get('course_edit')}}</li></a>
                        @if (count($student_list) < 1)
                            <a class="delete_btn" href="#delete"><li>{{$lan::get('course_delete')}}</li></a>
                        @endif
                </ul>
            </div>
            <div class="clr"></div>
    </div><!--center_content_header-->
    <div id="section_content">
        {{--<div id="topic_list" style="padding: 5px 10px;background:#B0AaA4;color:#fbfbfb;">
         {!! Breadcrumbs::render('school_course_detail') !!} 
        </div>--}}
        @include('_parts.topic_list')
        <h3 id="content_h3" class="box_border1">{{$lan::get('detail_info')}}</h3>
        <div class="info_content padding1 box_border1">
            <div class="info_info_right p15">
                <p>{{$lan::get('start_date')}}:{{date('Y-m-d', strtotime(array_get($list,'start_date')))}}</p>
                @if (array_get($list,'close_date'))<p>{{$lan::get('end_date')}}:{{date('Y-m-d', strtotime(array_get($list,'close_date')))}}</p>@endif
            </div>
            <p class="info_name p32">{{array_get($list,'course_title')}}</p>             <!-- イベント名称 -->
            <p class="info_info p18">{{array_get($list,'course_description')}}</p>       <!-- イベント内容 -->
            <div class="clr margin-bottom10"></div>
            <table id="table3_2">
                <colgroup>
                    <col width="50%"/>
                    <col width=""/>
                </colgroup>
                <tbody>
                    <tr>
                        <td class="t3_2td2">{{$lan::get('lecturer')}}</td>
                        <td class="t3_2td3" style="text-align:left;">
                            @foreach ($teacher_list as $teacher)
                            <li style="margin-bottom:0px;list-style-type:none;">{{array_get($teacher,'coach_name')}}</li>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td  class="t3_2td1" colspan="2">
                        {{$lan::get('tuition_fee')}}
                        </td>
                    </tr>

<!-- 受講料を可変化 -->
                    @foreach ($course_fee as $idx1=>$fee)
                    <tr>
                        <td class="t3_2td2">
                            {{array_get($fee,'name')}} {{$lan::get('tuition_fee')}}
                        </td>
                        <td class="t3_2td3">
                            @if (array_get($fee,'fee'))
                                {{number_format(array_get($fee,'fee'))}}&nbsp;{{$lan::get('circle')}}
                            @else
                                @if (array_get($fee,'fee') !=null ||  array_get($fee,'fee') !='')
                                    {{array_get($fee,'fee')}}&nbsp;{{$lan::get('circle')}}
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @forelse ($course_fee as $row)
                    @empty
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('tuition_fee')}}
                        </td>
                        <td class="t3_2td3">{{$lan::get('course_fee_not_found')}}</td>
                    </tr>
                    @endforelse
<!-- 受講料を可変化 -->

                </tbody>
            </table>
            </div>
            <h3 id="content_h3" class="box_border1">{{$lan::get('member_info')}}</h3>
        <div id="section_content_in">
        <table class="table1 tablesorter ">
                <thead>
                    <tr>
                        <th class="text_title" style="width:200px;">{{$lan::get('member_number')}}</th>
                        <th class="text_title" style="width:250px;">{{$lan::get('member_name')}}</th>
                        <th class="text_title" style="width: 80px;">{{$lan::get('member_type')}}</th>
                        <th class="text_title" style="width:200px;">{{$lan::get('tuition_type')}}</th>
                        <th class="text_title" style="width:110px;">{{$lan::get('tuition')}}</th>
                    </tr>
                </thead>
                @foreach ($student_list as $row)
                <tr class="table_row">
                    <td style="width:200px">{{array_get($row,'student_no')}}</td>
                    <td style="width:250px">
                        <a class="text_link" href="{{$_app_path}}student/detail?id={{array_get($row,'id')}}">
                        {{array_get($row,'student_name')}}</a>
                    </td>
                    <td style="width: 80px;text-align:center;">
                        {{$studentTypes[array_get($row,'student_type')]}}
                    </td>
                    <td style="width: 200px;">
                        @if (array_get($row,'fee_plan_name'))
                            {{array_get($row,'fee_plan_name')}}
                        @else
                            {{$lan::get('non_plan_name')}}
                        @endif
                    </td>
                    <td style="width: 110px;text-align:right;">
                        @if (array_get($row,'fee_plan_name'))
                            {{array_get($row,'fee')}}
                        @endif
                    </td>

                </tr>
                @endforeach
                @forelse ($student_list as $row)
                @empty
                    <tr>
                        <td colspan="5" class="error_row">{{$lan::get('info_no_display')}}</td>
                    </tr>
                @endforelse
            </table>
            <br/>
            <form id="action_form" method="post">
            {{ csrf_field() }}
                <input type="hidden" name="function" value="" />
                <input type="hidden" name="id" value="{{array_get($list,'id')}}" />
                <div class="exe_button">
                <input type="hidden" name="event_id" value="{{array_get($list,'id')}}"/>
                <input type="hidden" name="msg_type_id" value="{{array_get($list,'type_id')}}"/>
                <input type="hidden" name="event_type_id" value="{{array_get($list,'type2_id')}}"/>
                @if (array_get($list,'is_active'))
                    <input id="btn_mailmessage" class="submit3" type="submit" value="{{$lan::get('guide_mail')}}"/>
                @endif
            </div>
            </form>
            
        </div>
            
        
    </div>
    <div id="dialog-confirm"  style="display: none;">
    {{$lan::get('delete_ok')}}
    </div>
@stop