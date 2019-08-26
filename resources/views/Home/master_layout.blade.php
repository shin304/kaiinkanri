<!DOCTYPE html>
<html>
<head>
<!-- Favicon -->
<link rel="shortcut icon" href="/img/home/favicon.png" type="image/x-icon">
<title>{{isset($title) ? $title : "らくらく会員管理 | コース・プラン・イベント・請求管理まで一元管理。 会員ビジネスをトータルサポートする らくらく会員管理"}}</title>
@include('Home._parts.html_header')
</head>
<body>
	@include('Home._parts.body_header')
	@yield('content')
	@include('_parts.html_footer')
</body>
</html>