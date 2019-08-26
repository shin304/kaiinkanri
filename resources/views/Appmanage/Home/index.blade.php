<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')

<style type="text/css">
div#memberinfo { width: 400px; }
div#workbookinfo { width: 800px; }
div#myrecordinfo { width: 500px; }
div.info { margin: 0 auto; background-color: beige; padding: 4px 30px; text-align: center; }
div.info_title { border-bottom-style: groove; border-bottom-width: 2px; border-bottom-color: beige; font-size: 15px; font-weight: bold; margin: 4px; }
div.info_link { text-align: right; margin: 1px; }
div.info_link a { color: red; text-decoration: none; font-size: 12px; }
#infotable td { text-align: left; }
#infotable td.t_center { text-align: center; }
#infotable td.t_right { text-align: right; }
.panel-table th { font-size: 14px; font-weight: normal; text-align: left; }
.panel-table td { font-size: 14px; text-align: right; }
</style>

</head>
<body id="login_main">
<div id="wrap">
	@include('Appmanage._parts.body')
	<div style="width: 60%; margin: 0 auto;">
	
		<div class="panel panel-primary">
			<div class="panel-heading">
				情報取得日時
			</div>
			<div class="panel-body">
				{{ session()->get('now_date') }} 現在
			</div>
		</div>
		
		<br />
		<div class="panel panel-primary">
			<div class="panel-heading">
				会員数
			</div>
			<div class="panel-body">
				<table class="panel-table">
					<tr>
						<th>全会員数：</th>
						<td>{{ number_format($_a['member_counts']['all']) }} 人</td>
					</tr>
					<tr>
						<th>有効会員数：</th>
						<td>{{ number_format($_a['member_counts']['active']) }} 人</td>
					</tr>
					<tr>
						<th>本会員数：</th>
						<td>{{ number_format($_a['member_counts']['real']) }} 人</td>
					</tr>
				</table>
			</div>
			<div class="panel-footer"><a href="{{$_app_path}}member?menu">一覧へ</a></div>
		</div>
		
		<br />
		<div class="panel panel-primary">
			<div class="panel-heading">
				問題集数
			</div>
			<div class="panel-body">
				<table class="panel-table">
					<tr>
						<th>登録数：</th>
						<td>{{ number_format($_a['workbook_counts']['all']) }} 件</td>
					</tr>
					<tr>
						<th>公開数：</th>
						<td>{{ number_format($_a['workbook_counts']['active']) }} 件</td>
					</tr>
				</table>
			</div>
			<div class="panel-footer"><a href="{{$_app_path}}workbook?menu">一覧へ</a></div>
		</div>
	</div>
		
	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>