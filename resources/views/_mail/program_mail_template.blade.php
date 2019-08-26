<html>
<head></head>
<body>
<p>
{{$data['student_name']}}様
</p>
{!! $data['description'] !!}
<p>
詳細については、下記URLよりご確認ください。</br>

<a href="{{$data['url']}}">{{$data['url']}}</a></br></br>

※本メールに心当たりの無い方は、お手数ですが {{$data['contact']}} まで</br>
このメールを転送いただきますようお願い申し上げます。
</p>
{!! nl2br($data['mail_footer']) !!}
</body></html>