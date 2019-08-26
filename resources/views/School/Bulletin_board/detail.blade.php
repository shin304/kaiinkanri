@extends('_parts.master_layout')

@section('content')

<script type="text/javascript">
$(function() {
    $("#btn_return").click(function() {
        window.location.href = '{{$_app_path}}bulletinboard/';
        return false;
    });
    $("#btn_del").click(function() {
        $( "#delete-dialog-confirm" ).dialog({
            title: '{{$lan::get('main_title')}}',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "{{$lan::get('delete_title')}}": function() {
                    $( this ).dialog( "close" );
                    java_post("{{$_app_path}}bulletinboard/delete?delete_id={{$bulletin_board['id']}}");
                    return false;
                },
                "{{$lan::get('cancel_title')}}": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        $( "#delete-dialog-confirm" ).html('{{$lan::get('delete_this_confirm_title')}}');
        $( "#delete-dialog-confirm" ).dialog('open');
        return false;
    });
});

</script>
<style>
    .submit2 {
        height: 30px;
        border-radius: 5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
    }
</style>
<input type="hidden" name="mode" value="" />
<div id="center_content_header" class="box_border1">
    <h2 class="float_left"><i class="fa fa-newspaper-o"></i>{{$lan::get('main_title')}}</h2>
    <div class="clr"></div>
</div><!--center_content_header-->

<h3 id="content_h3" class="box_border1">{{$lan::get('detail_info_title')}}
{{--
    <div class="center_content_header_right">
        <div class="top_btn">
            <ul>
                <a class="text_link" href="{{$_app_path}}bulletinboard/input?id={{$bulletin_board['id']}}"><li style="color: #595959; font-weight: normal;"><i class="fa fa-pencil-square-o"></i>{{$lan::get('edit_title')}}</li></a>
                <a id="btn_del" href="#"><li style="color: #595959; font-weight: normal;"><i class="fa fa-trash-o"></i>{{$lan::get('delete_title')}}</li></a>
            </ul>
        </div>
    </div>
--}}
</h3>

<div id="section_content">
<div class="info_content padding1 box_border1">
    <div class="info_info_right p15">
        @if ($bulletin_board['start_date'])<p>{{$lan::get('start_title')}}：{{date('Y-m-d', strtotime($bulletin_board['start_date']))}}</p>@endif
        @if ($bulletin_board['finish_date'])<p>{{$lan::get('finish_title')}}：{{date('Y-m-d', strtotime($bulletin_board['finish_date']))}}</p>@endif
    </div>

    <p class="p24">{{$bulletin_board['title']}}</p>               <!-- イベント名称 -->
    <div class="bulletin_message_panel">{!! $bulletin_board['message'] !!}</div>
    <ul style="list-style: none;">
        @if ($files)
            <li style="padding: 5px;">{{$lan::get('download_file_title')}}：</li>
            @foreach ($files as $key => $file)
                <li style="padding: 5px 40px;">
                    <a href="{{$file_dir}}{{$file['file_path']}}" download="{{$file['disp_file_name']}}"><i class="fa fa-download" aria-hidden="true"></i> {{$file['disp_file_name']}}</a>
                </li>
            @endforeach
        @endif
    </ul>
    <div class="clr margin-bottom10"></div>
    @if ( !request('from_home') )
    <div class="exe_button">
        <!-- <input type="button" value="{{$lan::get('return_title')}}" id="btn_return" class="submit2" /> -->
        <button class="submit2" type="submit" id="btn_return" style="font-weight: normal;font-size:14px;"><i class="fa fa-arrow-circle-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
    </div>
    @endif

</div>


</div>
<div id="delete-dialog-confirm" style="display: none;"></div>
@stop

