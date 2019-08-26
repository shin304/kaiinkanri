<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')
</head>
<body id="login_main">
<div id="wrap">
	@include('Appmanage._parts.body')

	<div class="container">

		<ol class="breadcrumb">
			<li>会員管理</li>
			<li>会員一覧</li>
		</ol>

		@include('Appmanage._parts.message')

		<div class="panel panel-default">
			<div class="panel-heading"><a id="link_search"><span class="glyphicon glyphicon-search"></span>検索条件</a></div>

			<div class="panel-body" id="search_area">
				<form action="{{$_app_path}}member" method="post" id="search_form" class="form-horizontal">
					{{ csrf_field() }}

					@if (session()->get('appmanage.account.auth_type') == 1)
					<div class="form-group">
						<label class="col-sm-2 control-label">利用者</label>
						<div class="col-sm-4">
							<select class="form-control" name="_c[info_id]">
							<option value=""></option>
							@foreach ($_a['info_list'] as $info)
							<option value="{{ $info['info_id'] }}" @if (old('_c.info_id', request('_c.info_id')) == $info['info_id'] ) selected @endif >{{ $info['pschool_name'] }}</option>
							@endforeach
							</select>
						</div>
					</div>
					@else
					<input type="hidden" name="_c[info_id]" value="{{ old('_c.info_id', request('_c.info_id')) }}" />
					@endif

					<div class="form-group">
						<label class="col-sm-2 control-label">会員名</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="_c[member_name]" value="{{ old('_c.member_name', request('_c.member_name')) }}" />
						</div>
						<label class="col-sm-2 control-label">ニックネーム</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="_c[nickname]" value="{{ old('_c.nickname', request('_c.nickname')) }}" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">メールアドレス</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="_c[mailaddress]" value="{{ old('_c.mailaddress', request('_c.mailaddress')) }}" />
						</div>
						<label class="col-sm-2 control-label">誕生年</label>
						<div class="col-sm-4">
							<input type="number" class="form-control" name="_c[birth_year]" value="{{ old('_c.birth_year', request('_c.birth_year')) }}" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">種別</label>
						<div class="col-sm-4">
							<select class="form-control" name="_c[school_type]">
								<option value=""></option>
								<option value="1" @if (old('_c.school_type', request('_c.school_type')) == 1) selected @endif>中学</option>
								<option value="2" @if (old('_c.school_type', request('_c.school_type')) == 2) selected @endif>高校</option>
								<option value="3" @if (old('_c.school_type', request('_c.school_type')) == 3) selected @endif>大学</option>
								<option value="9" @if (old('_c.school_type', request('_c.school_type')) == 9) selected @endif>社会人</option>
							</select>
						</div>
						<label class="col-sm-2 control-label">性別</label>
						<div class="col-sm-4">
							<select name="_c[sex]" class="form-control" >
								<option value=""></option>
								<option value="1" @if (old('_c.sex', request('_c.sex')) == 1 ) selected @endif >男</option>
								<option value="2" @if (old('_c.sex', request('_c.sex')) == 2 ) selected @endif >女</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">状態1</label>
						<div class="col-sm-4">
							<select name="_c[active_flag]" class="form-control" >
								<option value=""></option>
								<option value="1" @if (old('_c.active_flag', request('_c.active_flag')) == 1 ) selected @endif >有効</option>
								<option value="2" @if (old('_c.active_flag', request('_c.active_flag')) == 2 ) selected @endif >無効</option>
							</select>
						</div>
						<label class="col-sm-2 control-label">状態2</label>
						<div class="col-sm-4">
							<select name="_c[status]" class="form-control" >
								<option value=""></option>
								<option value="1" @if (old('_c.status', request('_c.status')) == 1 ) selected @endif >本会員</option>
								<option value="2" @if (old('_c.status', request('_c.status')) == 2 ) selected @endif >仮会員</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">登録日</label>
						<div class="col-sm-4">
							<input type="text" class="form-control datepicker" name="_c[register_date_from]" value="{{ old('_c.register_date_from', request('_c.register_date_from')) }}" />
							～
							<input type="text" class="form-control datepicker" name="_c[register_date_to]" value="{{ old('_c.register_date_to', request('_c.register_date_to')) }}" />
						</div>
						<label class="col-sm-2 control-label">更新日</label>
						<div class="col-sm-4">
							<input type="text" class="form-control datepicker" name="_c[update_date_from]" value="{{ old('_c.update_date_from', request('_c.update_date_from')) }}" />
							～
							<input type="text" class="form-control datepicker" name="_c[update_date_to]" value="{{ old('_c.update_date_to', request('_c.update_date_to')) }}" />
						</div>
					</div>

					<div class="col-sm-12">
						<button id="btn_search" type="button" class="btn btn-primary">検索</button>
						<button id="btn_reset" type="button" class="btn btn-default">クリア</button>
					</div>
				</form>
			</div>
		</div><!-- end panel -->



		<div>
			<div class="col-md-6" style="margin: 10px 0;">
			@if (count($_a['list'])>0)
				<a onclick="return java_post('{{$_app_path}}member/export');" title="CSVダウンロード">
				<span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
				<span class="label label-primary">CSVダウンロード</span>
				</a>
			@endif
			</div>
			<div class="col-md-6">
				@include('Appmanage._parts.paginate')
			</div>
		</div><!-- end col-md-12 -->

		<div>
			<table class="table table-bordered table-striped tablepaginate">
				<thead>
				<tr>
					@if (session()->get('appmanage.account.auth_type') == 1)
					<th style="width:10%;">利用者</th>
					@endif
					<th>メールアドレス（ログインID）</th>
					<th style="width:10%;">会員名</th>
					<th style="width:10%;">ニックネーム</th>
					<th style="width: 8%;">誕生日</th>
					<th style="width: 6%;">種別</th>
					<th style="width: 6%;">性別</th>
					<th style="width: 6%;">状態1</th>
					<th style="width: 6%;">状態2</th>
					<th style="width: 8%;">登録日</th>
					<th style="width: 8%;">更新日</th>
				</tr>
				</thead>
				<tbody>
				@forelse ( $_a['list'] as $row )
				<tr>
					@if (session()->get('appmanage.account.auth_type') == 1)
					<td>@foreach ($_a['info_list'] as $info) @if ($info['info_id'] == $row['info_id']) {{ $info['pschool_name'] }} @endif @endforeach</td>
					@endif
					<td>{{ $row['mailaddress'] }}</td>
					<td>{{ $row['member_name'] }}</td>
					<td>{{ $row['nickname'] }}</td>
					<td align="center">{{ date('Y-m-d', strtotime($row['birthday'])) }}</td>
					<td>@if ($row['school_type']==1) 中学 @elseif ($row['school_type']==2) 高校 @elseif ($row['school_type']==3) 大学 @elseif ($row['school_type']) 社会人 @else 不明 @endif</td>
					<td>@if ($row['sex']==1) 男 @elseif ($row['sex']==2) 女 @else 不明 @endif</td>
					<td>@if ($row['active_flag']) 有効 @else 無効 @endif</td>
					<td>@if ($row['status']==1) 本会員 @elseif ($row['status']==2) 仮会員 @else 不明 @endif</td>
					<td align="center">{{ date('Y-m-d', strtotime($row['register_date'])) }}</td>
					<td align="center">@if ($row['update_date']) {{ date('Y-m-d', strtotime($row['update_date'])) }} @endif</td>
				</tr>
				@empty
				<tr>
				<td colspan="11" class="danger">データがありません。</td>
				</tr>
		        @endforelse
				</tbody>
			</table>
		</div><!-- end col-md-12 -->

	</div><!-- end container -->

	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>
