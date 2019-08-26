<html>
<head></head>
<body>
<p>@if(array_get($data,'student_name')) {!! $data['student_name']!!} @elseif(array_get($data,'name')) {!! $data['name']!!} @endif 様</p>
</br>
<p>{!!  $data['content']!!} </p>

@if($data['interface_type'] != 3)
<p>
    詳細については、下記URLよりご確認ください。</br>

    <a href="{{$data['url']}}">{{$data['url']}}</a></br></br>

    ※本メールに心当たりの無い方は、お手数ですが {{$data['contact']}} まで</br>
    このメールを転送いただきますようお願い申し上げます。
</p>
@endif
--------------------------------------------------<br />
<p>{!! nl2br($data['footer']) !!}</p>
</div>
</body></html>