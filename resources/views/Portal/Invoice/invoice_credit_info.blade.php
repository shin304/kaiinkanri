{{--クレジットカード決済の情報--}}
<form id="action_form" action="{{$info['payment_link']}}" target="_top" method="POST" enctype="multipart/form-data" style="float: right">
    {{ csrf_field() }}
    <input type="hidden" name="clientip" value="{{$info['ip_code']}}">
    <input type="hidden" name="money" value="{{$info['fee']}}">
    <input type="hidden" name="sendid" value="{{$info['code']}}">
    <input type="hidden" name="sendpoint" value="">
    <input type="hidden" name="telno" value="{!! str_replace('-', '', $info['phone_no']) !!}">
    <input type="hidden" name="email" value="{{$info['parent_mail']}}">
    <input type="hidden" name="success_url" value="http://{{$info['domain']}}{{$_app_path}}payment/result?sendid={{$info['message_key']}}&target=invoice">
    <input type="hidden" name="success_str" value="決済完了ページ">
    <input type="hidden" name="failure_url" value="http://{{$info['domain']}}{{$_app_path}}invoice?message_key={{$info['message_key']}}&payment=fail">
    <input type="hidden" name="failure_str" value="請求書ページ">
    <div class="exe_button">
        <input class="submit" type="submit" value="クレジットカード決済">
    </div>
</form>
