@extends('_parts.master_layout')

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<script type="text/javascript">
$(function() {

    $("input[name='calendar_color']").hexColorPicker({
        "container":"dialog",
//        "colorModel":"hsl",
//        "pickerWidth":250,
//        "size":10,
        "style":"hex",
        "outputFormat":"<hexcode>",
        "colorizeTarget":true,
//        "innerMargin":1
    });
    var init_color = $("input[name='calendar_color']").val();
    $("input[name='calendar_color']").css('background-color', '#'+init_color);

    // init tinymce tool
    tinymce.init({
      selector: 'textarea#message_text',
      menubar:false,
      toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
    });

    $.datetimepicker.setLocale('ja');
    jQuery(function(){
        jQuery('.DateInput').datetimepicker({
            format: 'Y-m-d',
            timepicker:false,
            scrollMonth : false,
            scrollInput : false
        });
    });

    // 戻るbutton
    $("#btn_return").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}bulletinboard');
        $("#action_form").submit();
        return false;
    });

    // ファイル追加
    var nowFileIndex =  ($('.tbl_clone_file').length)-1;
    $("#fileAdd").click(function(){

        var newTable = $( "TABLE", "#fileBase" ).clone();//fileBaseのIDのTABLEタグをnewTableへ
        var newHR    = $( "HR"   , "#fileBase" ).clone();//fileBaseのIDのHRタグをnewHRへ

        $( "#inputActive2" ).append( newTable );//inputActive2のID指定にnewTableの内容を追加する
        $( "#inputActive2" ).append( newHR    );//inputActive2のID指定にnewHRの内容を追加する

        // 表示
        $( newTable ).show();
        nowFileIndex++;
        return false;
    });

    // 削除処理設定
    $( ".fileDelete" ).click( function(e){
        var div         = $(this).closest("div");
        var file_id     = $(div).attr('id').split('_')[1];
        var file_name   = $(div).find('a').text();
        var title       = '{{$lan::get('delete_title')}}';
        // var content     = '「'+ file_name + '」' + '{{$lan::get('file_delete_content_title')}}';
        var content     = '{{$lan::get('file_delete_content_title')}}';
        if ($.isNumeric( file_id )) {
            var delete_dialog = $( "#delete-file-dialog-confirm" );
            delete_dialog.dialog({
                title: title,
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "OK": function() {
                        $.ajax({
                            type:"get",
                            dataType:"text",
                            url: "{{$_app_path}}bulletinboard/deleteUploadFile",
                            data: { file_id : file_id },
                            contentType: "application/x-www-form-urlencoded",
                            success: function(data) {
                                delete_dialog.dialog( "close" );
                                if (data == 'success') {
                                    div.remove();
                                }
                            },
                            error: function(xhr, status) {
                            },
                        });
                    },
                    "{{$lan::get('cancel_title')}}": function() {
                        delete_dialog.dialog( "close" );
                    }
                }
            });
            delete_dialog.html(content);
            delete_dialog.dialog('open');
        }
        return false;
    });
});

function save_confirm() {
    var title = '{{$lan::get('save_confirm_title')}}';
    var content = '{{$lan::get('save_confirm_content_title')}}';
    var action_url = '{{$_app_path}}bulletinboard/complete';
    common_save_confirm(title, content, action_url);
}
</script>

<style>
    .DateInput { width: 30% }
    .ui-datepicker-title { color: #000000}
    #fileAdd:hover, .submit2:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
    }
    #fileAdd {
        color: #595959;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
    }
    .submit2 {
        height: 30px;
        border-radius: 5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
</style>

<div id="center_content_header" class="box_border1">
    <h2 class="float_left"><i class="fa fa-newspaper-o"></i>{{$lan::get('main_title')}}</h2>
    <div class="clr"></div>
</div> <!-- center_content_header -->

<h3 id="content_h3" class="box_border1">@if (request('id')) {{$lan::get('edit_title')}}
                                        @else {{$lan::get('register_title')}} 
                                        @endif</h3>

@if (count($errors) > 0) 
    <ul class="message_area">
    @foreach ($errors->all() as $error)
    <li class="error_message">{{ $lan::get($error) }}</li>
    @endforeach
    </ul>
@endif

<div id="section_content1">
    <span class="aster">&lowast;</span>{{$lan::get('mandatory_items_title')}}

    <form action="#" method="post" id="action_form" name="action_form" enctype='multipart/form-data'>
    @if (request('id'))
        <input type="hidden" name="id" value="{{request('id')}}"/>
    @endif
    {{ csrf_field() }}
        <table id="table6">
            <colgroup>
                <col width="15%"/>
                <col width="85%"/>
            </colgroup>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('title')}}
                    <span class="aster">&lowast;</span>
                </td>
                <td>
                    <input style="ime-mode:active;width:70%;" type="text" name="title" value="{{ old('title', request('title')) }}" class="l_text" placeholder="{{$lan::get('title')}}{{$lan::get('please_input_title')}}">
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('message_title')}}
                </td>
                <td><textarea id="message_text" name="message" rows="5" cols="100" placeholder="{{$lan::get('message_title')}}{{$lan::get('please_input_title')}}">{{ old('message', request('message')) }}</textarea></td>
            </tr>
            <tr>
                <td  class="t6_td1">{{$lan::get('file_title')}}</td>
                <td>
                    <div id="inputActive2" >
                    @if ( count(request('files')) > 0 )
                        @foreach (request('files') as $key => $file)
                            <div id="file_{{array_get($file,'id')}}" style="padding-bottom: 10px;" >
                                <a href="{{$file_dir}}{{array_get($file,'file_path')}}" download="{{array_get($file,'disp_file_name')}}">{{array_get($file,'disp_file_name')}}</a>&nbsp;&nbsp;
                                <i class="fa fa-trash-o fileDelete" style="cursor: pointer;"></i>
                            </div>
                        @endforeach
                    @endif
                    </div>
                    <button id="fileAdd" style="height: 30px;width:15%;margin:10px 0px 10px 0px;"><i class="fa fa-plus"></i>{{$lan::get('add_file_title')}}</button>
                    <div id="fileBase" style="display:none;">
                        <table class="tbl_clone_file">
                            <tr>
                                <td class="t4d2">
                                    <input type="file" name="files[]">
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="t6_td1">
                    {{$lan::get('start_title')}}
                    <span class="aster">&lowast;</span>
                </td>
                <td>
                    <input type="text" class="DateInput" name="start_date" value="@if (old('start_date', request('start_date'))){{ date('Y-m-d', strtotime( old('start_date', request('start_date')))) }}@endif" placeholder="{{$lan::get('start_title')}}{{$lan::get('please_input_title')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('finish_title')}}
                </td>
                <td>
                    <input type="text" class="DateInput" name="finish_date" value="@if (old('finish_date', request('finish_date'))){{ date('Y-m-d', strtotime( old('finish_date', request('finish_date')))) }}@endif" placeholder="{{$lan::get('finish_title')}}{{$lan::get('please_input_title')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('target_title')}}
                </td>
                <td>
                    <label><input type="checkbox" name="target[staff]" value="1" @if (old('target.staff', request('target.staff')) == 1) checked @endif />&nbsp;{{$lan::get('staff_title')}}&nbsp;</label>
                    <label><input type="checkbox" name="target[teacher]" value="1" @if (old('target.teacher', request('target.teacher')) == 1) checked @endif />&nbsp;{{$lan::get('teacher_title')}}&nbsp;</label>
                    <label><input type="checkbox" name="target[student]" value="1" @if (old('target.student', request('target.student')) == 1) checked @endif />&nbsp;{{$lan::get('student_title')}}&nbsp;</label>
                    <label><input type="checkbox" name="target[parent]" value="1" @if (old('target.parent', request('target.parent')) == 1) checked @endif />&nbsp;{{$lan::get('parent_title')}}&nbsp;</label>
                    {{--<label><input type="checkbox" name="target[other]" value="1" @if (old('target.other', request('target.other')) == 1) checked @endif />&nbsp;{{$lan::get('other_title')}}&nbsp;</label>--}}
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    <label><input type="checkbox" name="calendar_flag" value="1" @if (old('calendar_flag', request('calendar_flag')) == 1) checked @endif />&nbsp;{{$lan::get('calendar_showing_title')}}</label>
                </td>
                <td>
                    {{$lan::get('calendar_showing_color_title')}}&nbsp;&nbsp;
<input name="calendar_color" value="
@if (old('calendar_color', request('calendar_color')))
{{ old('calendar_color', request('calendar_color')) }}
@else
808080
@endif" />
                </td>
            </tr>
        </table>
        <div class="exe_button">
            <!-- <input type="button" value="{{$lan::get('confirm_title')}}" id="btn_submit" class="submit2" onclick="save_confirm()" /> -->
            <button class="submit2" type="button" id="btn_submit" onclick="save_confirm()" style="color: #595959; font-weight: normal;font-size:14px;"><i class="glyphicon glyphicon-floppy-disk " style="width: 20%;font-size:16px;"></i>{{$lan::get('register_title')}}</button>
            <!-- <input type="button" value="{{$lan::get('return_title')}}" id="btn_return" class="submit2" /> -->
            <button class="submit2" type="submit" id="btn_return" style="color: #595959; font-weight: normal;font-size:14px;"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
        </div>
    </form>
</div><!-- Section Content1 -->
<div id="delete-file-dialog-confirm" style="display: none;"></div>
@stop 