<html>
<head></head>
<body>
<p>{{$data['parent_name']}}&nbsp;様 <br /></p>

<p>請求書をお送りいたします。<br /></p>

<p>詳細については、下記URLよりご確認ください。 <br />

<a href="{{$data['url']}}">{{$data['url']}}</a> <br /></p>

<p>※本メールに心当たりの無い方は、お手数ですが {{$data['reply']}} まで<br/>このメールを転送いただきますようお願い申し上げます。 <br /></p>

<div> -------------------------------------------------- <br />
{{$data['school_name']}} <br />
〒{{$data['school_zipcode_1']}}-{{$data['school_zipcode_2']}}<br />
{{$data['school_pref']}}&nbsp;{{$data['school_city']}}&nbsp;{{$data['address']}} <br />
{{$data['building']}} <br />
{{$data['school_phone']}} <br />
{{$data['school_daihyou']}} <br />
お問合わせ先： {{$data['contact']}} <br />
-------------------------------------------------- <br />
</div>	
</body></html>