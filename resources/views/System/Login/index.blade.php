<!DOCTYPE html>
<html>
<head>
<title>Login page111</title> @include('system._parts.html_header')
</head>
<body id="login_main">
	<div id="wrap">
		<div id="contents" align="center">
			<form action="/system/login" method="post" name="form1">
				{{ csrf_field() }}
				<table cellspacing="10" cellpadding="10" border="0" width="350"
					class="data_tbl" style="text-align: center; margin-top: 50px;">
					<tbody>
						<tr>
							@if(count($errors))
							<td><div class="login_error" style="color: red;">
									@foreach($errors->all() as $error) {{$error}} @endforeach</div>
							</td> @endif
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td class="title">ログインID</td>
							<td class="data"><input type="text" id="textfield" name="loginid"></td>
						</tr>
						<tr>
							<td class="title">パスワード</td>
							<td class="data"><input type="password" id="textfield2"
								name="password"></td>
						</tr>
					</tbody>
				</table>
				<input type="button" style="margin-bottom: 30px;"
					value="{{$lan['login']}}" name="password"
					onclick="this.form.submit();">
			</form>
		</div>
	</div>
</body>
</html>