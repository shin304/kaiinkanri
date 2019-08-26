
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			@if (session()->has('appmanage.account'))
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img src="/images{{$_app_path}}iconmonstr-menu-2-icon-24.png" /></a>

					<ul class="dropdown-menu" role="menu">
						<li onclick="return java_post('{{$_app_path}}home/?menu');"><a href="#" >&nbsp;HOME&nbsp;</a></li>
						<li class="divider"></li>
						<li onclick="return java_post('{{$_app_path}}member/?menu');"><a href="#" >&nbsp;会員管理&nbsp;</a></li>
						<li onclick="return java_post('{{$_app_path}}workbook/?menu');"><a href="#" >&nbsp;問題集管理&nbsp;</a></li>
						<li onclick="return java_post('{{$_app_path}}news/?menu');"><a href="#">&nbsp;お知らせ管理&nbsp;</a></li>

						{{-- if $smarty.server.HTTP_HOST == 'ictelappstage.asto-system.asia' || $smarty.server.HTTP_HOST == 'ictel.local'}}
						<li class="divider"></li>
						<li onclick="return java_post('{{$_app_path}}notice/?menu');"><a href="#">&nbsp;PUSH通知実行テスト&nbsp;</a></li>
						{{/if --}}

						<li class="divider"></li>
						<li onclick="return java_post('{{$_app_path}}info/?menu');"><a href="#">&nbsp;利用者管理&nbsp;</a></li>
					</ul>
				</li>
			</ul>
			@endif

			<a class="navbar-brand" href="#">いくてるアプリの管理画面です</a>

			@if (session()->has('appmanage.account'))
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#" title="アカウント情報" onclick="$('#pop').bPopup();return false;"><img src="/images{{$_app_path}}iconmonstr-id-card-4-icon-24.png" /></a></li>
				<li><a href="#" title="ログアウト" onclick="$('[data-remodal-id=alert_logout]').remodal({ hashTracking: false }).open(); return false;"><img src="/images{{$_app_path}}eject-3-icon-24.png" /></a></li>
			</ul>
			<div id="pop" style="background-color: white; padding: 20px; display: none;" >
				<div class='arrow'></div>
				<div class='arrow-border'></div>
				<table>
					<tr>
						<th colspan="2">{{ session()->get('appmanage.account.pschool_name') }} さん</th>
					</tr>
					<tr>
						<th>ユーザー名：</th>
						<td>{{ session()->get('appmanage.account.login_id') }}</td>
					</tr>
					<tr>
						<th>IPアドレス：</th>
						<td>{{ Request::ip() }}</td>
					</tr>
					<tr>
						<th>現在時刻：</th>
						<td>{{ session()->get('now_date') }}</td>
					</tr>
				</table>
			</div>
			@endif

		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->

	@if (session()->has('appmange_admin'))
	@endif

</nav>
