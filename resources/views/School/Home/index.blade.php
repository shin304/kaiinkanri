@extends('_parts.master_layout') 
@section('content')
<script type='text/javascript'>
$(document).ready(function() {
    // init holiday
    var holiday = [];
    @foreach($holiday as $v)
        holiday.push('{{$v->y_m_d}}');
    @endforeach

    $('#calendar').fullCalendar({
         header: {
             // title, prev, next, prevYear, nextYear, today, month agendaWeek agendaDay
             left: 'title',
             center: '',
             right: 'today prev,next'
         },
        dayNames: ['{{$lan::get('sunday_title')}}','{{$lan::get('monday_title')}}','{{$lan::get('tuesday_title')}}','{{$lan::get('wednesday_title')}}','{{$lan::get('thursday_title')}}','{{$lan::get('friday_title')}}','{{$lan::get('saturday_title')}}'],
        dayNamesShort: ['{{$lan::get('sun_title')}}','{{$lan::get('mon_title')}}','{{$lan::get('tue_title')}}','{{$lan::get('wed_title')}}','{{$lan::get('thu_title')}}','{{$lan::get('fri_title')}}','{{$lan::get('sat_title')}}'],
        titleFormat: {
            month: 'YYYY{{$lan::get('year_title')}}M{{$lan::get('month_title3')}}',
            week: 'YYYY{{$lan::get('year_title')}}M{{$lan::get('month_title2')}}D{{$lan::get('day_title2')}}',
            day: 'YYYY{{$lan::get('year_title')}}M{{$lan::get('month_title2')}}D{{$lan::get('day_title2')}} dddd'
        },
        // columnFormat: {
        //     month: 'dddd',    // 月
        //     week: 'M/D ddd', // 7(月)
        //     day: 'M/D ddd' // 7(月)
        // },
        buttonText: {
            today: '{{$lan::get('this_month_title')}}',
            month: '{{$lan::get('month_title')}}',
            week:  '{{$lan::get('week_title')}}',
            day:   '{{$lan::get('day_title')}}'
            },
        timeFormat: { // for event elements
            '': 'H:mm' // default
            },
        axisFormat: 'H:mm',
        timeFormat: {
            agenda: 'H:mm' //目絵は　'H:mm{ - H:mm}'
            },
        editable: false, //trueでスケジュールを編集可能にする
        draggable: false,
        allDaySlot: false,
        // change style of holiday cell
        dayRender: function (date, cell) {
            if ($.inArray(date.toISOString(), holiday ) != (-1)) {
                $('[data-date='+date.toISOString()+']').addClass('holiday');
            }
        },
        events: [
                    {  // こんな感じで定義します
                        id: "special_event",
                        title: "ICT 教材．教具展示会",
                        start: "2014-01-18",
                        // start: "2014-01-18 16:00",
                        // end: "2014-01-18 18:30",
                        color: "firebrick",
                        textcolor: "white"
                    },
                    // イベント
                    @foreach ($courses as $key => $course)
                        {
                            id: 'course',
                            title: '{{$course['course_title']}}',
                            start: '{{ Carbon\Carbon::parse($course['start_date'])->format('Y-m-d') }}',
                            start_time: '{{$course['start_date']}}',
                            close_time: '{{$course['close_date']}}',
                            total_member: '{{$course['total_member']}}',
                            total_fee: '{{$course['total_fee']}}',
                            capacity: '{{$course['member_capacity'] + $course['non_member_capacity']}}',
                            // end: '{{$course['close_date']}}',
                            color: 'rgba(65,105,225,0.5)',
                            textColor: 'black',
                            url: "{{$_app_path}}course/courseentry?course_id={{$course['id']}}",
                            location: '{{$course['course_location']}}',
                        },
                    @endforeach
                    // 掲示板
                    @foreach ($bulletins as $key => $bulletin)
                        @if ( $bulletin['calendar_flag'] == 1 ) 
                            {
                                id: 'bulletin',
                                title: '{{$bulletin['title']}}',
                                start: '{{$bulletin['start_date']}}',
                                finish: '{{$bulletin['finish_date']}}',
                                color: '#{{$bulletin['calendar_color']}}',
                                textColor: '#{{$bulletin['calendar_text_color']}}',
                                url: "{{$_app_path}}bulletinboard/detail?id={{$bulletin['id']}}",
                            },
                        @endif
                    @endforeach
                        //Notify system
                    @foreach ($system_logs as $key => $system_log)
                        @if ( $system_log['calendar_flag'] ==1&&!is_null($system_log['start_calendar_dis'])&&!is_null($system_log['end_calendar_dis']))
                            <?php
                                $end_calendar_dis = (new DateTime($system_log['end_calendar_dis']))->modify('+1 day')->format('Y-m-d');
                            ?>
                            {
                                id: 'system_log',
                                title: '{{$system_log['process']}}',
                                start: '{{$system_log['start_calendar_dis']}}',
                                end: '{{$end_calendar_dis}}',
                                description: '{{$system_log['message']}}',
                                color: '#{{$system_log['calendar_color']}}',
                                textColor: '#{{$system_log['calendar_text_color']}}',
                            },
            
                        @endif
                    @endforeach
                    // 請求データ期限
                    @foreach ($closingday_list as $key => $deadline)
                        {
                            id: 'deadline',
                            title: '{{$lan::get('billing_deadline_title')}}',
                            start: '{{$deadline['deadline']}}',
                            end: '{{$deadline['deadline']}}',
                            color: 'rgba(205,205,50,0.5)',
                            textColor: 'black',
                        },
                    @endforeach
                    // 振替結果通知
                    @foreach ($closingday_list as $key => $result)
                        {
                            id: 'result',
                            title: '{{$lan::get('transfer_result_notice_title')}}',
                            start: '{{$result['result_date']}}',
                            end: '{{$result['result_date']}}',
                            color: 'rgba(50,205,128,0.5)',
                            textColor: 'black',
                        },
                    @endforeach
                ],
        eventSources:  [
                            {
                            url:'http://www.google.com/calendar/feeds/ja.japanese%23holiday%40group.v.calendar.google.com/public/full/',
                            color: "red"
                            }
                        ],

        eventMouseover: function (data, event, view) {
            if (data.id == 'course') {
                var tooltip = '<div class="tooltip_event" style="background:#a0b4f0;">';
                tooltip += '{{$lan::get('title')}}: ' + data.title + '</br>';
                tooltip += '{{$lan::get('total_member_title')}}: ' + data.total_member + '{{$lan::get('person_title')}}';
                if (data.capacity && data.capacity != 0) {
                    tooltip += '/' + data.capacity;
                } else {
                    tooltip += '/_';
                }
                tooltip += '{{$lan::get('person_title')}}' + '</br>';
                tooltip += '{{$lan::get('total_fee_title')}}: ' + Number(data.total_fee).toLocaleString() + ' {{$lan::get('yen_title')}}' + '</br>';
                tooltip += '{{$lan::get('event_start_title')}}: ' + data.start_time.slice(0,16) + '</br>';
                if (data.close_time) {
                    tooltip += '{{$lan::get('event_end_title')}}: ' + data.close_time.slice(0,16) + '</br>';
                }
                if (data.location) {
                    tooltip += '{{$lan::get('event_location_title')}}: ' + data.location + '</br>';
                }
                tooltip += '</div>';
            } else if (data.id == 'bulletin') {
                var tooltip = '<div class="tooltip_event" style="background:'+data.color+';color:'+data.textColor+';">';
                tooltip += '{{$lan::get('title')}}: ' + data.title + '</br>';
                tooltip += '{{$lan::get('start_title')}}: ' + data.start.toISOString().substring(0, 10) + '</br>';
                if ( data.finish ) {
                    tooltip += '{{$lan::get('end_title')}}: ' + data.finish + '</br>'
                }
                tooltip += '</div>';
                
            } else if (data.id == 'deadline') {
                var tooltip = '<div class="tooltip_event" style="background:#e1e184;">';
                tooltip += '{{$lan::get('title')}}: ' + data.title + '</br>';
                tooltip += '{{$lan::get('payment_deadline_title')}}: ' + data.start.toISOString().substring(0, 10) + '</br>';
                tooltip += '</div>';
            } else if (data.id == 'result') {
                var tooltip = '<div class="tooltip_event" style="background:#6fdca6;">';
                tooltip += '{{$lan::get('title')}}: ' + data.title + '</br>';
                tooltip += '{{$lan::get('transfer_date_title')}}: ' + data.start.toISOString().substring(0, 10) + '</br>';
                tooltip += '</div>';
            }
            else if (data.id == 'system_log') {
                var tooltip = '<div class="tooltip_event system_log" style="background:'+data.color+';color:'+data.textColor+';">';
                if(data.description.length>=150) {
                    data.description=data.description.substring(0,150) + '...';
                    tooltip +=data.description + '</br>';

                }else {
                    tooltip +=data.description + '</br>';
                }
                

            }
            
            $("body").append(tooltip);
            $(this).mouseover(function (e) {
                $(this).css('z-index', 10000);
                $('.tooltip_event').fadeIn('5000');
                $('.tooltip_event').fadeTo('10', 2);
            }).mousemove(function (e) {
//                $('.tooltip_event').css('bottom', screen.height - e.pageY - (0.163*screen.height));
                $('.tooltip_event').css('right', e.view.outerWidth - e.pageX - (0.009*e.view.outerWidth));
                $('.tooltip_event').css('top', e.pageY + 10);
//                $('.tooltip_event').css('left', e.pageX + 10);
            });
        },
        eventMouseout: function (data, event, view) {
            $(this).css('z-index', 8);
            $('.tooltip_event').remove();
        },
    }); // end #calendar

    // 送信メール一覧
    var mail_search = $('input[name=mail_search]:checked').val();
    if (mail_search == 1) {
        $('select[name=mail_search_select]').prop('disabled', 'disabled');
    } else {
        $('select[name=mail_search_select]').removeAttr("disabled");
    }
    // 送信メール一覧・検索
    $('input[name=mail_search] , select[name=mail_search_select]').on('change', function(e) {
        var mail_search = $('input[name=mail_search]:checked').val();
        var mail_search_select = $('select[name=mail_search_select]').val();
        if (mail_search == 1) {
            $('select[name=mail_search_select]').prop('disabled', 'disabled');
        } else {
            $('select[name=mail_search_select]').removeAttr("disabled");
        }
        $.ajax({
            type:"get",
            dataType:"html",
            url: "/school/home/searchMail",
            data: { mail_search : mail_search,
                mail_search_select : mail_search_select},
            contentType: "application/x-www-form-urlencoded",
            success: function(data) {
                $(".mail_content").html(data);
            },
            error: function(data) {
                console.log("error");
            },
        });
    }); // end 送信メール一覧・検索

    // お知らせ・アクティビティ
    var notice_search = $('input[name=notice_search]:checked').val();
    if (notice_search == 1) {
        $('select[name=notice_search_select]').prop('disabled', 'disabled');
    } else {
        $('select[name=notice_search_select]').removeAttr("disabled");
    }
    // お知らせ・アクティビティ　検索
    $('input[name=notice_search] , select[name=notice_search_select]').on('change', function(e) {
        var notice_search = $('input[name=notice_search]:checked').val();
        var notice_search_select = $('select[name=notice_search_select]').val();
        if (notice_search == 1) {
            $('select[name=notice_search_select]').prop('disabled', 'disabled');
        } else {
            $('select[name=notice_search_select]').removeAttr("disabled");
        }
        $.ajax({
            type:"get",
            dataType:"html",
            url: "/school/home/searchNotice",
            data: { notice_search : notice_search,
                notice_search_select : notice_search_select},
            contentType: "application/x-www-form-urlencoded",
            success: function(data) {
                $(".notice_content").html(data);
            },
            error: function(data) {
                console.log("error");
            },
        });
    }); // end お知らせ・アクティビティ　検索

    // update view_date in entry_table or invoice_header_table
    $('.notice_event , .notice_program, .notice_invoice').on('click', function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        var type = $(this).attr('class');
        var id   = $(this).find('input[name=id]').val();
        $.ajax({
            type:"get",
            dataType:"text",
            url: "/school/home/updateNoticeViewDate",
            data: { notice_type : type,
                id : id},
            contentType: "application/x-www-form-urlencoded",
            success: function(data) {
                java_post(link);
            },
            error: function(xhr, status) {
            },
        });
    }); // end update view_date in entry_table or invoice_header_table

    // システムからのお知らせ
    var system_log_search = $('input[name=system_log_search]:checked').val();
    if (system_log_search == 1) {
        $('select[name=system_log_search_select]').prop('disabled', 'disabled');
    } else {
        $('select[name=system_log_search_select]').removeAttr("disabled");
    }
    // お知らせ・アクティビティ　検索
    $('input[name=system_log_search] , select[name=system_log_search_select]').on('change', function(e) {
        var system_log_search = $('input[name=system_log_search]:checked').val();
        var system_log_search_select = $('select[name=system_log_search_select]').val();
        if (system_log_search == 1) {
            $('select[name=system_log_search_select]').prop('disabled', 'disabled');
        } else {
            $('select[name=system_log_search_select]').removeAttr("disabled");
        }
        $.ajax({
            type:"get",
            dataType:"html",
            url: "/school/home/searchSystemLog",
            data: { system_log_search : system_log_search,
                system_log_search_select : system_log_search_select},
            contentType: "application/x-www-form-urlencoded",
            success: function(data) {
                $(".system_log_content").html(data);
            },
            error: function(data) {
                console.log("error");
            },
        });
    }); // end システムからのお知らせ　検索
    @foreach ($system_logs as $key => $system_log)
    $('#system_logs_{{$system_log['id']}}').click(function () {
        $('#new_{{$system_log['id']}}').css('display','none');
        if($('#new_{{$system_log['id']}}').css('display')) {
            $('#system_logs_{{$system_log['id']}}').css('margin-left','10%');
        }
        var notify_id=$('#id_notify_{{$system_log['id']}}').val();
        $.ajax({
            type:"get",
            dataType:"html",
            url: "/school/home/updateSystemLogViewdate",
            data: { notify_id : notify_id,
            },
            success: function(data) {
            },
            error: function(data) {
               
            },
        });
    })
    @endforeach
    
});
</script>
<style>
.text_link { text-decoration: none; }
.holiday {
    color: red !important;
    background-color: #fff0f0 !important;
}
    .system_log {
        max-width: 20% !important;
        letter-spacing: 3px;
    }
</style>
<div style="display:table;width: 100%;height: 100%;">
    <div id="home_center_content">
        <div id="home_center_content_header">
            <h2 class="float_left"><i class="fa fa-home"></i> {{$lan::get('main_title')}}</h2><br>
        </div>
        <div id="section_content2">
            <!-- 掲示板 -->
            <h3 id="content_h3"><i class="fa fa-newspaper-o"></i> {{$lan::get('bulletin_board_title')}}</h3>
            <div class="section_content2_in">
                <div>
                    <div id="section_content2_in_ul">
                        @include('School.Home.bulletin_content')
                    </div>
                </div>
            </div>
            <div class="clr"></div>

            <!-- 送信メール一覧 -->
            <h3 id="content_h3"><i class="fa fa-envelope"></i> {{$lan::get('mails_title')}}
                <div class="right_button_panel">
                    <label><input type="radio" name="mail_search" value="1" checked>{{$lan::get('30_lastest_title')}}</label>
                    <label><input type="radio" name="mail_search" value="2">{{$lan::get('period_title')}}</label>
                    <select name="mail_search_select">
                        <option value="7">{{$lan::get('7_days_ago_title')}}</option>
                        <option value="30">{{$lan::get('30_days_ago_title')}}</option>
                    </select>
                </div>
            </h3>
            <div class="section_content2_in">
                <div>
                    <div id="section_content2_in_ul" class="mail_content">
                        @include('School.Home.mail_content')
                    </div>
                </div>
            </div>
            <div class="clr"></div>

            <!-- お知らせ・アクティビティ -->
            <h3 id="content_h3"><i class="fa fa-history"></i> {{$lan::get('notices_title')}}
                <div class="right_button_panel">
                    <label><input type="radio" name="notice_search" value="1" checked>{{$lan::get('30_lastest_title')}}</label>
                    <label><input type="radio" name="notice_search" value="2">{{$lan::get('period_title')}}</label>
                    <select name="notice_search_select">
                        <option value="7">{{$lan::get('7_days_ago_title')}}</option>
                        <option value="30">{{$lan::get('30_days_ago_title')}}</option>
                    </select>
                </div>
            </h3>
            <div class="section_content2_in">
                <div>
                    <div id="section_content2_in_ul" class="notice_content">
                        @include('School.Home.notice_content')
                    </div>
                </div>
            </div>

            <!-- システムからのお知らせ -->
            <h3 id="content_h3"><i class="fa fa-desktop"></i> {{$lan::get('system_log_title')}}
                {{--<div class="right_button_panel">--}}
                    {{--<label><input type="radio" name="system_log_search" value="1" checked>{{$lan::get('30_lastest_title')}}</label>--}}
                    {{--<label><input type="radio" name="system_log_search" value="2">{{$lan::get('period_title')}}</label>--}}
                    {{--<select name="system_log_search_select">--}}
                        {{--<option value="7">{{$lan::get('7_days_ago_title')}}</option>--}}
                        {{--<option value="30">{{$lan::get('30_days_ago_title')}}</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
            </h3>
            <div class="section_content2_in">
                <div>
                    <div id="section_content2_in_ul" class="system_log_content">
                        @include('School.Home.system_log_content')
                    </div>
                </div>
            </div>

        </div><!--　section_content2　-->
    </div><!-- home_center_content -->
    <div id="home_right_content">
        <h2 id="content_h2"><i class="fa fa-calendar"></i>{{$lan::get('calendar_title')}}</h2>
        <div id="home_right_content_box">
            <div id='calendar'></div>
        </div><!--home_right_content_box-->
    </div><!--home_right_content-->
</div>
@stop
