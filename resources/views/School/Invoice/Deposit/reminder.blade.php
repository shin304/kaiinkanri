<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="/js/school/mail_template.js"></script>
@extends('_parts.master_layout') @section('content')
    <style>
        #btn_load_list:hover, #btn_create_list:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .submit_return, #btn_load_list, #btn_create_list {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
    {{--Header--}}
    <div id="center_content_header" >
        <div class="c_content_header_left">
            <h2 class="float_left"><i class="fa fa-yen"></i>{{$lan::get('dp_deposit_management_title')}}</h2><br/>
        </div>
        <div class="clr"></div>
    </div>

    {{-- Error message--}}
    @if(request()->has('errors'))
        <div class="alart_box box_shadow">
            <ul class="message_area">
                @foreach(request()->errors as $error)
                    <li class="error_message">{{$lan::get($error)}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="section_content">
        {{--Dont know why I need to put csrf field in there, without this line I can not create new template--}}
        <form action="{{URL::to('/school/invoice/deposit_send')}}" method="post" id="action_form">
            {{ csrf_field() }}
            @foreach (array_get(request()->all(), 'invoice_header_ids', array()) as $id)
                <input type="hidden" name="invoice_header_ids[]" value="{{$id}}">
            @endforeach

            <table id="table6">
                <colgroup>
                    <col width="25%"/>
                    <col width="75%"/>
                </colgroup>
                <tr>
                    <td colspan="2" class="sending_mail_area" align="right">
                        <div style="padding-right: 10%">
                            <input type="button" id="btn_load_list" name="btn_load_list" value="{{$lan::get('dp_list_mail_template')}}" style="height: 32px; font-weight: 400;">
                            <input type="button" id="btn_create_list" name="btn_create_list" value="{{$lan::get('dp_mail_template_create')}}" style="height: 32px; font-weight: 400;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{{$lan::get('dp_reminder_mail_address')}}</td>
                    <td>
                        <ul>
                            @foreach($parents as $parent)
                                <li>{{array_get($parent, 'parent_name')}} &lt;{{array_get($parent, 'parent_mailaddress1')}}&gt;</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('dp_mail_title')}}<span class="aster">&lowast;</span></td>
                    <td>
                        <input style="ime-mode:active;" type="text" size="70" name="title" value="{{request()->title}}" id="mail_subject" placeholder="{{$lan::get('dp_mail_title')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('dp_mail_content')}}<span class="aster">&lowast;</span></td>
                    <td>
                        <textarea style="width:90%;ime-mode:active;" name="content" cols="10" rows="8" wrap="hard" class="description_textarea" id="mail_description" placeholder="{{$lan::get('dp_mail_content')}}">{{request()->get('content')}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('dp_mail_footer')}}</td>
                    <td>
                        <textarea style="ime-mode:active;width:80%" name="footer" rows="3" wrap="hard" class="footer_textarea" id="mail_footer" placeholder="{{$lan::get('dp_mail_footer')}}">{{request()->footer}}</textarea>
                    </td>
                </tr>
            </table>
            @include('_mail.mail_template')
            <div class="disable_edit" style="margin-top: 30px;margin-bottom: 30px;">
                <input type="checkbox" name="schedule_flag_update" id="schedule_flag" @if (old('schedule_flag', request('schedule_flag'))) checked @endif>&nbsp;&nbsp;<label for="schedule_flag">{{$lan::get('booking_time_send_title')}}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="word-spacing: 10px;">
                        <label>{{$lan::get('day_send_title')}}</label>&nbsp;<input form="action_form" type="text" class="DateTimeInput schedule_date" name="schedule_date_update" value="@if (old('schedule_date', request('schedule_date'))) {{date('Y-m-d H:i',strtotime(old('schedule_date', request('schedule_date'))))}} @endif">
                    </span>
            </div>
            <div class="exe_button">
                <button type="submit" id="btn_submit" class="submit_return"><i class="fa fa-send"></i>{{$lan::get('dp_send')}}</button>
                <button type="button" id="btn_back" class="submit_return"><i class="fa fa-arrow-circle-left"></i>{{$lan::get('dp_back')}}</button>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            tinymce.init({
                selector: 'textarea#mail_description',
                menubar: false,
                toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                plugins: "advlist autolink lists link image charmap print preview anchor"
            });
            getTypeMail(4);

            $("#btn_back").click(function() {
                java_post("{{$_app_path}}invoice/deposit");
                return false;
            });

            $.datetimepicker.setLocale('ja');

            jQuery(function(){
                jQuery('.DateTimeInput').datetimepicker({
                    format: 'Y-m-d H:i',
                    step : 5,
                    minDate: new Date(),
                    scrollMonth : false,
                    scrollInput : false
                });
            });
            jQuery(function(){
                jQuery('.DateInput').datetimepicker({
                    format: 'Y-m-d',
                    timepicker:false,
                });
            });

        });
    </script>
@stop
