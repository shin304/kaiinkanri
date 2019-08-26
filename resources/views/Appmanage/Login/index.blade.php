<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')
</head>
<body id="login_main">
<div id="wrap">
	@include('Appmanage._parts.body')
	
	<div id="contents" style="width: 600px; margin: 20px auto;">
		<form action="{{$_app_path}}login" method="post" id="login_form">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="textfield">ログインID</label>
				<input type="text" id="textfield" name="_s[loginid]" class="form-control" value="{{ old('_s.loginid', request('_s.loginid')) }}" placeholder="ログインIDを入力">
			</div>
			<div class="form-group">
				<label for="textfield">パスワード</label>
				<input type="password" id="textfield2" name="_s[password]" class="form-control" value="{{ old('_s.password', request('_s.password')) }}"placeholder="パスワードを入力">
			</div>
			@if (session()->has('appmanage.messages'))
			<div class="form-group" style="color: red;">
				@foreach(session()->pull('appmanage.messages') as $msg) <p>{{$msg}}</p> @endforeach
			</div>
			@endif
			<br/>
			<button type="button" class="btn btn-default" onclick="$('#login_form').submit();">ログイン</button>
		</form>
	</div>
	
	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>