<!DOCTYPE html>
<html lang="ja">
<!--<html>-->
<head>
	<!-- Favicon -->
	<link rel="shortcut icon" href="/images/favicon.jpg" type="image/x-icon">
	<title>らくらく会員管理</title>
	@include('_parts.html_header')
</head>
<body>
	@include('_parts.body_header')
	<table id="wrapper" style="height: 371px;">
		<tbody>
			<tr>
				<td  style="position: relative;z-index: 999;overflow:visible;width: 0;overflow: visible ;margin: 0;padding: 0" id="left_content"  >
                        <div id="section_content2" class="dynamic_menu dynamic_menu_hide">
                            @include('_parts.left_panel')
                        </div>
                </td>
				<td style="min-width: 700px;" id="center_content"  >

					<div class="section">@yield('content')</div>
				</td>
			</tr>
		</tbody>
	</table>

	@include('_parts.body_footer') @include('_parts.html_footer')

</body>
</html>