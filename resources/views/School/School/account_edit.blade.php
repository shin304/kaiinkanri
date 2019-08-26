@extends('_parts.master_layout')

@section('content')
<script type="text/javascript">
$(function() {
    $(".submit3").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}school/accountlist');
        $("#action_form").submit();
        return false;
    });
    $(".submit2").click(function() {
        $('#view_edit input').attr('disabled', false)
        // $("#action_form").attr('action', '{{$_app_path}}school/accountconfirm');
        // $("#action_form").submit();
        return true;
    });
    $(".menu_auth").click(function() {
        if(!this.checked) {
            var idxnm = $(this).attr("name");
            var idynm = idxnm.replace( /auth/g , "edit" );
            $('input[name="'+idynm+'"]').prop("checked",false);
        }
    });
    $(".menu_edit").click(function() {
        if(this.checked) {
            var idxnm = $(this).attr("name");
            var idynm = idxnm.replace( /edit/g , "auth" );
            $('input[name="'+idynm+'"]').prop("checked",true);
        }
    });
});

function save_confirm() {
    var title = '{{$lan::get('save_confirm_title')}}';
    // var content = '{{$lan::get('save_confirm_content')}}';
    var content = '{{$lan::get('confirm_content')}}';
    var action_url = '{{$_app_path}}school/accountcomplete';
    common_save_confirm(title, content,action_url);
}


function nextForm(event)
{
    if (event.keyCode == 0x0d)
    {
        var current = document.activeElement;

        var forcus = 0;
        for( var idx = 0; idx < document.action_form.elements.length; idx++){
            if( document.action_form[idx] == current ){
                forcus = idx;
                break;
            }
        }
        document.action_form[(forcus + 1)].focus();
    }
}
window.document.onkeydown = nextForm;
</script>

<style type="text/css">
#auth label {
  display: block;
  float: left;
  width: 150px;
}
.submit2, .submit3 {
    color: #595959;
    height: 30px;
    border-radius: 5px;
    background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
    /*font-size: 14px;*/
    font-weight: normal;
    text-shadow: 0 0px #FFF;
}
</style>


    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-university"></i> {{$lan::get('main_title')}}</h2>
            <div class="center_content_header_right">
                <div class="top_btn">
                </div>
            </div>
        <div class="clr"></div>
    </div>
    {{--@include('_parts.topic_list')--}}
    <h3 id="content_h3" class="box_border1">{{$lan::get('login_privileges_setting_title')}}@if (request('id'))- {{$lan::get('edit_title')}} @else- {{$lan::get('register_title')}}@endif</h3>

    <div id="section_content1">

    @if (count($errors) > 0)
    <ul class="message_area">
        @foreach ($errors->all() as $error)
            <li class="error_message">{{$lan::get($error)}}</li>
        @endforeach
    </ul>
    @endif
    <p><span class="aster">&lowast;</span>{{$lan::get('mandatory_items_title')}}</p>
    <form id="action_form" name="action_form" method="post">
    {{ csrf_field() }}
        <input type="hidden" name="id" value="{{old('id', request('id'))}}"/>
        <table id="table6">
        @php
            $data = array();
            if (session()->has('old_data')) {
                $data = session()->pull('old_data')[0];
            } 
                                        
        @endphp
            <tr>
                <td class="t6_td1">{{$lan::get('staff_name_title')}}<span class="aster">&lowast;</span></td>
                <td class="t4td2">
                    <input class="text_m" style="ime-mode:active;" type="text" name="staff_name" value="{{old('staff_name', request('staff_name'))}}"
                           placeholder="{{$lan::get('placeholder_type_name')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('email_address_title')}}<span class="aster">&lowast;</span>
                </td>
                <td class="t4td2">
                    <input class="text_m" style="ime-mode:inactive;" type="text" name="login_id" value="{{old('login_id', request('login_id'))}}"
                    placeholder="{{$lan::get('placeholder_type_email')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('password_title')}}<span class="aster">&lowast;</span></td>
                <td class="t4td2" style="display: -webkit-inline-box; width: 100%">
                    @if (old('id', request('id')) && !old('staff_pass1', request('staff_pass1')))
                        <input class="text_m"  type="password" name="staff_pass1" placeholder="{{$lan::get('placeholder_type_pass')}}"/>
                            <div ></div>
                        <span class="col_msg"><b>※{{$lan::get('input_change_title')}}</b></span><br/>
                        <span class="col_msg"><b>※{{$lan::get('password_regex_warning')}}</b></span>
                    @else
                        <input class="text_m"  type="password" name="staff_pass1"value="{{request('staff_pass1')}}" placeholder="{{$lan::get('placeholder_type_pass')}}"/>
                        <span class="col_msg"><b>※{{$lan::get('password_regex_warning')}}</b></span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('password_confirm_title')}} <span class="aster">&lowast;</span></td>
                <td class="t4td2"><input class="text_m"  type="password" name="staff_pass2"
                    @if (old('id', request('id')) && !old('staff_pass2', request('staff_pass2')))
                    placeholder="{{$lan::get('placeholder_type_repass')}}"/>
                    @else
                        value="{{request('staff_pass2')}}" placeholder="{{$lan::get('placeholder_type_repass')}}"/>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="t6_td1">
                    {{$lan::get('language_title')}}
                </td>
                <td class="t4td2">
                    @if(isset($language_input))
                        <select name="staff_used_language">
                            @foreach($language_input as $key=>$value)
                                <option value="{{$key}}" @if(request('lang_code')==$key) selected @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    @endif
                </td>
            </tr>

        </table>
        @include('_parts.menu_auth')
        <br>
        <div class="exe_button" >
            @if($edit_auth)
                <button class="submit2" type="button" onclick="save_confirm()"><i class="fa fa-floppy-o " style="width: 20%;font-size:16px;" onclick="save_confirm()"></i>登録</button> &nbsp;
            @endif
            <button id="btn_return" class="submit3" type="button"><i class="fa fa-arrow-circle-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
            
        </div>
    </form>
    </div>
@stop