<html>
<head></head>
<body>
<p>
{{$mail['mail_info']['student']['parent_name']}}様
</p>
<p>
{{$mail['event_name']}}のご案内をいたします。</br>

詳細については、下記URLよりご確認ください。</br>

<a href="{{$mail['url']}}">{{$mail['url']}}</a></br>

※本メールに心当たりの無い方は、お手数ですが {{$mail['reply']}} までこのメールを転送いただきますようお願い申し上げます。
</p>
<p>
--------------------------------------------------</br>
{{$mail['school_name']}}</br>
{{$mail['daihyou']}}</br>
お問合わせ先： {{$mail['contact']}}</br>
--------------------------------------------------
</p>
</body></html>