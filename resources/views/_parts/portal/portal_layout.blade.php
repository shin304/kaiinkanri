<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title> @include('_parts.portal.portal_header')
</head>
<body>
    <div id="container">
        <div id="main_panel">
            <div class="content_box">
                @yield('content')
            </div>
        </div>
    </div>
</body>
@include('_parts.portal.portal_footer')
</html>