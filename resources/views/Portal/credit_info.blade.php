{{--クレジットカード決済の情報--}}
<p>ゼウス社で、クレジットカード決済を行います。よろしいですか。</p>
<input type="hidden" name="clientip" value="{{$info['ip_code']}}">
<input type="hidden" name="money" value="{{request('fee')}}">
<input type="hidden" name="sendid" value="{{$entry['code']}}">
<input type="hidden" name="sendpoint" value="">
<input type="hidden" name="telno" value="{!! str_replace('-', '', $pschool['school_tel']) !!}">
<input type="hidden" name="email" value="{{$pschool['student_mail']}}">
<input type="hidden" name="success_url" value="http://{{$info['domain']}}{{$_app_path}}payment/result?sendid={{$entry['code']}}&target={{$target}}">
<input type="hidden" name="success_str" value="完了ページ">
<input type="hidden" name="failure_url" value="http://{{$info['domain']}}{{$_app_path}}{{$target}}/?message_key={{request('message_key')}}&payment=fail">
<input type="hidden" name="failure_str" value="申し込みページ">
{{--<input type="submit" value="クレジット決済ページへ">--}}

