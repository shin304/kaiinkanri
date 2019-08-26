@extends('_parts.master_layout') 

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript">
$(function() {
    var href;
    $("#btn_submit").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}school/accountinput');
        $("#action_form").submit();
        return false;
    });
    $(".submit3").click(function() {
        java_post('{{$_app_path}}school');
        return false;
    });
    $( "#dialog-delete" ).dialog({
        title: '{{$lan::get('main_title')}}',
        autoOpen: false,
        dialogClass: "no-close",
        resizable: false,
        modal: true,
        buttons: {
            "{{$lan::get('delete_title')}}": function() {
                $( this ).dialog( "close" );
                location.href =href ;
                return false;
            },
            "{{$lan::get('cancel_title')}}": function() {
                $( this ).dialog( "close" );
            }
        }
    });
    $("[id='accountedit']").click(function(e) {
        e.preventDefault();
        var link = $(this).attr("href");
        java_post(link);
       
        return false;
    });
    $("[id='accountdelete']").click(function() {
        href = $(this).attr("href");
        $( "#dialog-delete" ).dialog('open');
        return false;
    });
    $("#btnaddaccount").click(function() {
        // $("#action_form").attr('action', '{{$_app_path}}school/accountedit');
        // $("input[name='addaccount']").val("1");
        // $("#action_form").submit();
        location.href = '{{$_app_path}}school/accountedit';
        return false;
    });
});
</script>
<style>
    .top_btn li:hover, .div-btn li:hover, input[type="button"]:hover, #btn_return:hover, button[type="button"]:hover  {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
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
    .top_btn li {
        border-radius: 5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
    button[type="button"] {
        height: 30px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
</style>
    <div id="center_content_header" class="box_border1">
            <h2 class="float_left"><i class="fa fa-university"></i> {{$lan::get('main_title')}}</h2>
            <div class="center_content_header_right">
                <div class="top_btn">
                    <ul>
                       {{-- @if (isset($auths['school_accountedit']) && $auths['school_accountedit'] == 1) --}}
                        <a href="/school/school/studentSetting"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-education"></i>&nbsp; {{$lan::get('student_setting_menu')}}</li></a>

                        <a href="/school/school/parentSetting"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-user"></i>&nbsp; {{$lan::get('parent_setting_menu')}}</li></a>

                        {{-- @endif --}}
                     </ul>
                </div>
            </div>
            <div class="clr"></div>
    </div>
    {{--@include('_parts.topic_list')--}}
    <h3 id="content_h3" class="box_border1">{{$lan::get('login_privileges_setting_title')}}</h3>

        <div id="section_content_in">

            <div style="float: left">
                @if (request('message_type'))
                    @if (request('message_type')==99)
                        <ul class="message_area">
                            <li class="error_message">
                                {{$lan::get('an_error_title')}}
                            </li>
                        </ul>
                    @else
                        <ul class="message_area">
                            <li class="info_message">
                                @if (request('message_type')==31)
                                    {{$lan::get('success_register_title')}}
                                @elseif (request('message_type')==32)
                                    {{$lan::get('update_success_title')}}
                                @elseif (request('message_type')==33)
                                    {{$lan::get('success_delete_title')}}
                                @elseif (request('message_type')==10)
                                    {{$request('insertcount')}}{{$lan::get('$success_item_register_title')}}
                                @endif
                            </li>
                        </ul>
                    @endif
                @endif
            </div>

            <div style="float: right" class="top_btn">
                <ul>
                    <a class="button" id="btnaddaccount" href="#"><li style="color: #595959; font-weight: normal;"><i class="fa fa-plus"></i>{{$lan::get('register_btn_title')}}</li></a>
                </ul>
            </div>
            <div class="clr"></div>
            {{--<form action="#" method="post" id="action_form"> --}}
            <input type="hidden" id="addaccount" name="addaccount" value="" />
{{ csrf_field() }}
            <table id="table6">
                <colgroup>
                    <col width="25%"/>
                    <col width="40%"/>
                    <col width="25%"/>
                </colgroup>
                <tr>
                    <td class="t6_td1">{{$lan::get('staff_name_title')}}</td>
                    <td class="t6_td1">{{$lan::get('staff_acc_title')}}</td>
                    <td class="t6_td1">{{$lan::get('staff_action_title')}}</td>
                </tr>
                @foreach ($loginaccount as $idx1=>$row)
                <tr>
                    <td>
                        {{array_get($row,'staff_name')}}
                    </td>
                    <td>
                        {{array_get($row,'login_id')}}
                    </td>
                    <td>
                      {{--  @if ($auths['school_accountedit'] == 1) --}}
                        <a class="button" id="accountedit"  href="{{$_app_path}}school/accountedit?id={{array_get($row,'id')}}" title="{{array_get($row,'staff_name')}}">{{$lan::get('edit_title')}}</a>
                       {{-- @endif --}}
                        &nbsp;&nbsp;&nbsp;
                       {{--  @if ($auths['school_accountdelete'] == 1) --}}
                        <a class="button" id="accountdelete" href="{{$_app_path}}school/accountdelete?id={{array_get($row,'id')}}" title="{{array_get($row,'staff_name')}}">{{$lan::get('delete_title')}}</a>
                       {{-- @endif --}}
                    </td>
                </tr>
                @endforeach
            </table>
           {{-- </form> --}}
        </div>
        <br>
        <div class="exe_button" >

            <!-- <input type="button" value="{{$lan::get('return_title')}}" id="btn_return" class="submit3"/> -->
            <button id="btn_return" class="submit3" type="button"><i class="fa fa-arrow-circle-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>

        </div>
        <div id="dialog-delete"  style="display: none;">
            {{$lan::get('delete_staff_confirm_title')}}
        </div>
@stop