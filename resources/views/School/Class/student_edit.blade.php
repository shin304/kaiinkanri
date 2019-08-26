@extends('_parts.master_layout')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />

    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-book"></i>{{$lan::get('main_title')}}</h2> <!--  プラン管理 -->
        <div class="center_content_header_right"></div>
        <div class="clr"></div>
    </div><!--center_content_header-->

    <h3 id="content_h3" class="box_border1">{{$lan::get('member_detail_info_title')}} {{$lan::get('edit_title')}}</h3> <!--会員情報 -->
    <form method="post" id="action_form" action="{{$_app_path}}class/studentList">
        {{ csrf_field() }}
        <div id="section_content">
            <!-- プラン基本情報 -->
            <div class="info_content padding1 box_border1">
                <div class="info_info_right p15">
                    @if (isset($class_info['start_date']) && $class_info['start_date'])<p>{{$lan::get('begin_day_title')}}：{{date('Y-m-d', strtotime($class_info['start_date']))}}</p>@endif
                    @if (isset($class_info['close_date']) && $class_info['close_date'])<p>{{$lan::get('end_day_title')}}：{{date('Y-m-d', strtotime($class_info['close_date']))}}</p>@endif
                </div>
                <p class="info_name p32">{{$class_info['class_name']}}</p><!-- イベント名称 -->
                <p class="info_info p18">{!! $class_info['class_description'] !!}</p><!-- イベント内容 -->
                <div class="clr"></div>
            </div>
            @if (isset($message_success) && !empty($message_success))
                <ul class="message_area">
                    <li class="info_message">{{ $lan::get($message_success) }}</li>
                </ul>
            @endif
            @if ($request->has('errors'))
                <ul class="message_area">
                    <li class="error_message">{{ $lan::get($request->errors) }}</li>
                </ul>
            @endif

            <div class="box_border1 padding1">
                <!-- 基準金額 -->
                <div class="panel panel-default" style="width: 70%;">
                    <div style="padding: 15px 15px 0 15px;">
                        <table class="subject-table" id="table3_2" style="width: 100%;">
                            <tr>
                                <td class="t3_2td2" width="25%">会員名</td>
                                <td class="t3_2td3" style="text-align: left;">{{$student_info['student_name']}}</td>
                            </tr>
                            <tr>
                                <td class="t3_2td2" width="25%">会員種別</td>
                                <td class="t3_2td3" style="text-align: left;">{{$student_info['student_type_name']}}</td>
                            </tr>
                        </table>
                    </div>
                    <div style="padding: 15px 15px 0 15px;">
                        <table class="" id="tb_start_close_date" style="width: 100%;">
                            <tr>
                                <td class="t3_2td2" width="25%">{{$lan::get('start_date_title')}}</td>
                                <td class="t3_2td3" style="text-align: left;">
                                    <input type="text" class="input_class_date" name="start_date" id="start_date"
                                           value="@if($student_info['start_date']){{$student_info['start_date']}}@endif"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="t3_2td2" width="25%">{{$lan::get('end_date_title')}}</td>
                                <td class="t3_2td3" style="text-align: left;">
                                    <input type="text" class="input_class_date" name="end_date" id="end_date"
                                           value="@if($student_info['end_date']){{$student_info['end_date']}}@endif"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <input type="hidden" name="id" value="{{request('id')}}" />
                    @include('_parts.student.payment_plan')
                </div> <!-- panel panel-default -->
                <div class="clr"></div>
            </div> <!-- box_border1 padding1 -->

            <ul class="message_area message_area_js" style="display: none;">
                <li class="error_message">{{$lan::get('select_error_title')}}</li>
            </ul>
            <div style="padding: 10px">
                <button class="submit2" type="button" id="btn_submit" style="font-size:14px;font-weight: normal !important;">
                    <i class="fa fa-floppy-o" style="width: 20%;font-size:16px;"></i>{{$lan::get('save_title')}}
                </button>
                <button class="submit2" type="button" id="btn_return" style="font-size:14px;font-weight: normal !important;"><i class="fa fa-arrow-circle-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
            </div>
        </div>

        <!-- BUTTON EXECUTE AREA -->
        <div id="section_content2">
            @if(request()->has('prev_id'))
                <input type="submit" class="btn_green" id="btn_before" value="{{$lan::get('previous_text')}}">
            @endif
            @if(request()->has('next_id'))
                <input type="submit" class="btn_green" id="btn_after" value="{{$lan::get('next_text')}}">
            @endif
        </div>
    </form>
    <style>
        .xdsoft_datetimepicker .xdsoft_year{
            display: block;
        }
    </style>
<script type="text/javascript">
    $(function() {
        $("#btn_before").click(function() {
            java_post("{{$_app_path}}class/studentEdit?prev_id={{request('prev_id')}}");
            return false;
        });

        $("#btn_after").click(function() {
            java_post("{{$_app_path}}class/studentEdit?next_id={{request('next_id')}}");
            return false;
        });

        $("#btn_submit").click(function() {
            $('.message_area_js').hide();

            update_total_fee();
            // validate
            if (!validate_chedule()) {
                return;
            }

            var title = '{{$lan::get('save_confirm_title')}}';
            // var content = '{{$lan::get('save_confirm_content')}}';
            var content = '{{$lan::get('confirm_content')}}';
            var action_url = '{{$_app_path}}class/studentStore';
            common_save_confirm(title, content, action_url);
            return false;
        });

        $("#btn_return").click(function() {
            $("#action_form").attr('action', '{{$_app_path}}class/detail');
            $("input[name=id]").val({{$class_info['id']}});
            $("#action_form").submit();
            return false;
        });

        $.datetimepicker.setLocale('ja');
        jQuery(function(){
            jQuery('.DateInput').datetimepicker({
                format: 'm-d',
                timepicker:false,
//                minDate: new Date(),
                scrollMonth : false,
                scrollInput : false,
                validateOnBlur:false,
            });
        });
        jQuery(function(){
            jQuery('.input_class_date').datetimepicker({
                timepicker:false,
                format: 'Y-m-d',
                scrollMonth : false,
                scrollInput : false
            });
        });
    });
    function flexible_year_show(){
        $('.input_class_date').click(function () {
            $(".xdsoft_datetimepicker .xdsoft_year").show();
        })
        $('.DateInput').click(function () {
            $(".xdsoft_datetimepicker .xdsoft_year").hide();
        })
    }
    flexible_year_show();
</script>
@stop