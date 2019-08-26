@extends('_parts.master_layout')

@section('content')
<script type="text/javascript">
    $(function() {
        $("#btn_return").click(function() {
            window.location.href = '{{$_app_path}}program/list';
            return false;
        });
        $("#btn_add").click(function() {
            window.location.href = "{{$_app_path}}program/studentList?id={{$program['id']}}&mode=1";
            // java_post("{{$_app_path}}program/studentList?id={{$program['id']}}&mode=1");
            return false;
        });
        $("#btn_del").click(function() {
             window.location.href = "{{$_app_path}}program/studentList?id={{$program['id']}}&mode=2";
            // java_post("{{$_app_path}}program/studentList?id={{$program['id']}}&mode=2");
            return false;
        });
        $("a[href='#edit']").click(function() {
            // $("#action_form").attr('action', '{{$_app_path}}program/input?id={{$program['id']}}');
            // $("#action_form").submit();
            location.href = '{{$_app_path}}program/input?id={{$program['id']}}';
            return false;
        });
        $( "#dialog-confirm" ).dialog({
            title: '{{$lan::get('main_title')}}',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "{{$lan::get('ttl_msg_delete')}}": function() { // 削除
                    $( this ).dialog( "close" );
                    $("#action_form input[name='mode']").val("2");
                    $("#action_form").attr('action', '{{$_app_path}}program/complete?id={{$program['id']}}');
                    $("#action_form").submit();
                    return false;
                },
                "{{$lan::get('ttl_cancel')}}": function() { // キャンセル
                    $( this ).dialog( "close" );
                }
            }
        });

        //datepicker追加
        var d = new Date();
        var d = new Date();
        $(".DateInput").datepicker({
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    monthNames: ['{{$lan::get('jan_title')}}','{{$lan::get('feb_title')}}','{{$lan::get('mar_title')}}','{{$lan::get('apr_title')}}','{{$lan::get('may_title')}}','{{$lan::get('jun_title')}}','{{$lan::get('jul_title')}}','{{$lan::get('aug_title')}}','{{$lan::get('sep_title')}}','{{$lan::get('oct_title')}}','{{$lan::get('nov_title')}}','{{$lan::get('dec_title')}}'],
                    monthNamesShort: ['{{$lan::get('jan_title')}}','{{$lan::get('feb_title')}}','{{$lan::get('mar_title')}}','{{$lan::get('apr_title')}}','{{$lan::get('may_title')}}','{{$lan::get('jun_title')}}','{{$lan::get('jul_title')}}','{{$lan::get('aug_title')}}','{{$lan::get('sep_title')}}','{{$lan::get('oct_title')}}','{{$lan::get('nov_title')}}','{{$lan::get('dec_title')}}'],
                    dayNames: ['{{$lan::get('sunday_title')}}','{{$lan::get('monday_title')}}','{{$lan::get('tuesday_title')}}','{{$lan::get('wednesday_title')}}','{{$lan::get('thursday_title')}}','{{$lan::get('friday_title')}}','{{$lan::get('saturday_title')}}'],
                    dayNamesShort: ['{{$lan::get('sun_title')}}','{{$lan::get('mon_title')}}','{{$lan::get('tue_title')}}','{{$lan::get('wed_title')}}','{{$lan::get('thu_title')}}','{{$lan::get('fri_title')}}','{{$lan::get('sat_title')}}'],
                    dayNamesMin: ['{{$lan::get('sun_title')}}','{{$lan::get('mon_title')}}','{{$lan::get('tue_title')}}','{{$lan::get('wed_title')}}','{{$lan::get('thu_title')}}','{{$lan::get('fri_title')}}','{{$lan::get('sat_title')}}'],
                    yearRange: '2000:'+(d.getYear()+1910),
                    prevText: '&#x3c;{{$lan::get('prev_title')}}', prevStatus: '{{$lan::get('show_previous_month_title')}}',
                    prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '{{$lan::get('show_previous_year_title')}}',
                    nextText: '{{$lan::get('next_title')}}&#x3e;', nextStatus: '{{$lan::get('show_next_month_title')}}',
                    nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '{{$lan::get('show_next_year_title')}}',
                    currentText: '{{$lan::get('today_title')}}', currentStatus: '{{$lan::get('show_this_month_title')}}',
                    todayText: '{{$lan::get('today_title')}}', todayStatus: '{{$lan::get('show_this_month_title')}}',
                    clearText: '{{$lan::get('clear_title')}}', clearStatus: '{{$lan::get('clear_date_title')}}',
                    closeText: '{{$lan::get('close_btn')}}', closeStatus: '{{$lan::get('close_without_change_title')}}'
        });
        $("a[href='#delete']").click(function() {
            $( "#dialog-confirm" ).dialog('open');
            return false;
        });
});
</script>
<script type="text/javascript">
   /* $(function() {
        $(".tablesorter").tablesorter();
    });*/
</script>

<form id="action_form" method="post">
{{ csrf_field() }}
        <input type="hidden" name="mode" value="" />
        <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-bullhorn"></i>{{$lan::get('main_title')}}</h2>
            <div class="center_content_header_right">
                <ul>
                    @php
                            $edit_auth = false;
                            $menu_no = session('session.menu.number');
                            if(array_key_exists($menu_no, session('menu_auth'))){
                            $edit_auth = (session('menu_auth')[$menu_no]['editable'] == 1)? true : false;
                            }

                    @endphp
                    
                        <a class="edit_btn" href="#edit"><li><!-- 編集 -->{{$lan::get('btn_edit_title')}}</li></a>
                        @if (count($student_list) < 1)
                            <a class="delete_btn" href="#delete"><li><!-- 削除 -->{{$lan::get('btn_delete_title')}}</li></a>
                        @endif
                    
                </ul>
            </div>
            <div class="clr"></div>
        </div><!--center_content_header-->

        @include('_parts.topic_list')
        <h3 id="content_h3" class="box_border1"><!-- 詳細情報 -->{{$lan::get('ttl_detail_information')}}</h3>
        <ul class="message_area">
        
        </ul>
        <div id="section_content">
        <div class="info_content padding1 box_border1">
            <div class="info_info_right p15">
                @if (array_get($program,'start_date'))<p><!-- 開始日： -->{{$lan::get('ttl_start_date')}}{{date('Y-m-d', strtotime($program['start_date']))}}</p>@endif
                @if (array_get($program,'close_date'))<p><!-- 終了日： -->{{$lan::get('ttl_close_date')}}{{date('Y-m-d', strtotime($program['close_date']))}} </p>@endif
            </div>
            <p class="info_name p32">{{array_get($program,'program_name')}}</p>
            <p class="info_info p18">{{nl2br(array_get($program,'description'))}}</p>
            <div class="clr margin-bottom10"></div>
            <table id="table3_2">
                <colgroup>
                    <col width="50%"/>
                    <col width=""/>
                </colgroup>
                <tr>
                    <td class="t3_2td1" colspan="2">
                    <!-- 受講料 -->{{$lan::get('ttl_fee')}}
                    </td>
                </tr>
                    @foreach ($program_fee as $idx1=>$fee)
                    <tr>
                        <td class="t3_2td2">
                            {{array_get($fee,'fee_plan_name')}}
                            @if (array_get($fee,'required_fee') == '1')<span class="aster">&lowast;</span>@endif
                        </td>
                        <td class="t3_2td3">
                            @if (array_get($fee,'fee'))
                                {{number_format(array_get($fee,'fee'))}}&nbsp;<!-- 円 -->{{$lan::get('ttl_yen')}}
                            @else
                                @if (array_get($fee,'fee') !=null ||  array_get($fee,'fee') !='')
                                    {{array_get($fee,'fee')}}&nbsp;<!-- 円 -->{{$lan::get('ttl_yen')}}
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
            </table>
        </div>
        <h3 id="content_h3" class="box_border1"><!-- カリキュラム -->{{$lan::get('ttl_lesson')}}</h3>
        <div id="section_content_in">
            <table class="table_list ">
                <thead>
                <tr>
                    <th class="text_title" style="width:100px;"><!-- 日付 -->{{$lan::get('ttl_date')}}</th>
                    <th class="text_title" style="width:250px;"><!-- 講義名 -->{{$lan::get('ttl_lesson_name')}}</th>
                    <th class="text_title" style="width:200px;"><!-- 講師 -->{{$lan::get('ttl_coach_name')}}</th>
                    <th class="text_title" style="width:200px;"><!-- 講師 -->{{$lan::get('ttl_coach_name')}}</th>
                </tr>
                </thead>
                @foreach ($lesson_list as $row)
                <tr>
                    <td style="width:100px;">
                        @if (array_get($row,'start_date'))
                        {{date('Y-m-d', strtotime($row['start_date']))}}
                        @endif
                    </td>
                    <td style="width:250px;">
                        {{array_get($row,'lesson_name')}}
                    </td>
                    <td style="width:200px;">
                        {{array_get($row,'coach_name1')}}
                    </td>
                    <td style="width:200px;">
                        {{array_get($row,'coach_name2')}}
                    </td>
                </tr>
                @endforeach
                @forelse ($lesson_list as $row)
                @empty
                    <tr>
                    <td class="error_row" colspan="4">{{$lan::get('ttl_msg_display_data_not_exists')}}</td>
                    </tr>
                @endforelse
            </table>
        </div>
        <br/>
        <h3 id="content_h3" class="box_border1"><!-- 会員情報 -->{{$lan::get('ttl_member_information')}}</h3>
        <div id="section_content_in">
            <table class="table_list ">
                <thead>
                <tr>
                    <th class="text_title" style="width:250px;"><!-- 会員番号 -->{{$lan::get('ttl_member_no')}}</th>
                    <th class="text_title" style="width:140px;"><!-- 会員名 -->{{$lan::get('ttl_member_name')}}</th>
                    <th class="text_title" style="width: 80px;"><!-- 会員種別 -->{{$lan::get('ttl_member_type')}}</th>
                    <th class="text_title" style="width:200px;"><!-- 受講料種別 -->{{$lan::get('ttl_fee_type')}}</th>
                    <th class="text_title" style="width:110px;"><!-- 受講料（円） -->{{$lan::get('ttl_fee_yen')}}</th>
                </tr>
                </thead>
                @foreach ($student_list as $row)
                <tr>
                    <td style="width:250px;">
                        {{array_get($row,'student_no')}}
                    </td>
                    <td style="width:140px;">
                        <a class="text_link" href="{{$_app_path}}student/detail?id={{array_get($row,'id')}}">
                        {{array_get($row,'student_name')}}
                        </a>
                    </td>

                    <td style="width: 80px;text-align:center;">
                        {{array_get($row,'student_type_name')}}
                    </td>
                    <td style="width: 200px;">
                        {{array_get($row,'fee_plan_name')}}
                    </td>
                    <td style="width: 110px;text-align:right;">
                        @if (array_get($row,'fee'))
                        {{number_format(array_get($row,'fee'))}}
                        @endif
                    </td>
                </tr>
                
                @endforeach
                @forelse ($student_list as $row)
                @empty
                    <tr>
                    <td class="error_row" colspan="5">{{$lan::get('ttl_msg_display_data_not_exists')}}</td>
                    </tr>
                @endforelse
            </table>
            <br/>
            @if (array_get($program,'is_active'))
                {{--  @if ((session('isNormalShibu') == 1)) --}}
                    <input id="btn_add" type="submit" class="submit3" value="{{$lan::get('ttl_msg_add')}}"/><!-- 追加 -->
                    <input id="btn_del" type="submit" class="submit3" value="{{$lan::get('ttl_msg_delete')}}"/><!-- 削除 -->
                {{-- @endif --}}
            @endif
        </div>
        </div>
    </form>

    <div id="dialog-confirm"  style="display: none;">
    <!-- 削除します。よろしいですか？ -->{{$lan::get('ttl_msg_confirm_delete')}}
    </div>
@stop