@extends('_parts.master_layout')
@section('content')
{{--CSS content begin--}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/coach.css" />
{{--CSS content end--}}

{{--HTML content begin--}}
<div id="center_content_header" class="box_border1">
    <h2 class="float_left main-title"><i class="fa fa-black-tie"></i>{{$lan::get('main_title')}}</h2>
    <div class="clr"></div>
</div><!--center_content_header-->
<h3 id="content_h3" class="screen-title">
    @if (request('id')) {{$lan::get('detail_info_edit_title')}}@else{{$lan::get('detail_info_register_title')}}@endif
</h3>


@if (count($errors) > 0) 
    <ul class="message_area"> 
    @foreach ($errors->all() as $error)
        <li class="error_message">{{ $lan::get($error) }}</li>
    @endforeach
    </ul>
@endif
@if (session()->has('messages'))
    <ul class="message_area">
        <li class="info_message">{{ $lan::get(session()->pull('messages')) }}</li>
    </ul>
@endif

<div id="section_content1">
    <p><span class="aster">&lowast;</span>{{$lan::get('required_text_explain_title')}}</p>
    <form id="entry_form" name="entry_form" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        {{-- Menu setting begin --}}
        <table width="100%">
            <colgroup>
                <col width="30%"/>
                <col width="70%"/>
            </colgroup>
            <tr>
                <td colspan="2"><b>{{$lan::get('permission_setting_title')}}</b></td>
            </tr>
            <tr>
                <td colspan="2">
                    @include('_parts.menu_auth')
                </td>
            </tr>
        </table>
        {{-- Menu setting end --}}



        {{-- Register button--}}
        @if(request()->has('id'))
            <div class="div-btn">
                <ul>
                    <!-- <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-save"></i>{{$lan::get('edit_title')}}</li></a> -->
                    <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;"><i class="glyphicon glyphicon-floppy-disk"></i> {{$lan::get('register_title')}}</li></a>
                    <a href="" class="button" id="btn_back"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;border-radius: 5px;"><i class="glyphicon glyphicon-circle-arrow-left"></i> {{$lan::get('back_btn')}}</li></a>
                </ul>
            </div>
        @else
            <div class="div-btn">
                <ul>
                    <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;border-radius: 5px;"><i class="glyphicon glyphicon-floppy-disk"></i> {{$lan::get('register_title')}}</li></a>
                    <a href="" class="button" id="btn_back"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;border-radius: 5px;"><i class="glyphicon glyphicon-circle-arrow-left"></i> {{$lan::get('back_btn')}}</li></a>
                </ul>
            </div>
        @endif
    </form>
    <div id="dialog_active" class="no_title" style="display:none;">
        {{$lan::get('message_save_confirm')}}
    </div>
</div>

{{--HTML content end--}}


{{--JS content begin--}}
<script type="text/javascript">

    $(function(){
        // 確認ボタン
        $("#submit2").click(function () {
            $("#dialog_active").dialog('open');
            return false;
        });

        $("#dialog_active").dialog({
            title: '{{$lan::get('main_title')}}',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "{{$lan::get('ok_btn')}}": function () {
                    $(this).dialog("close");
                    $("#entry_form").attr('action', '/school/school/saveParentMenu');
                    $("#entry_form").submit();
                    return false;
                },
                "{{$lan::get('cancel_title')}}": function () {
                    $(this).dialog("close");
                    return false;
                }
            }
        });

        $('#btn_back').click(function (event) {
            event.preventDefault();
            java_post("{{$_app_path}}school/accountlist");
            return false;
        });

    });

{{-- ここまで --}}
</script>
{{--JS content end--}}
    <style>
        .div-btn li:hover  {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .div-btn li {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
@stop