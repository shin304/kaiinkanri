<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')
</head>
<body>
<div id="contents">
	@include('Appmanage._parts.body')

	<div class="container">

		<ol class="breadcrumb">
		  <li>問題集管理</li>
		  <li>問題集一覧</li>
		</ol>

		@include('Appmanage._parts.message')

		<div class="panel panel-default">
			<div class="panel-heading"><a id="link_search"><span class="glyphicon glyphicon-search"></span>検索条件</a></div>
			<div class="panel-body" id="search_area">
				<form action="{{$_app_path}}workbook" method="post" id="search_form" class="form-horizontal">
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
						<label class="col-sm-2 control-label">タイトル</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="_c[title]" value="{{ old('_c.title', request('_c.title')) }}" />
						</div>
						<label class="col-sm-2 control-label">サブタイトル</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="_c[subtitle]" value="{{ old('_c.subtitle', request('_c.subtitle')) }}" />
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
						<button id="btn_reset"  type="button" class="btn btn-default">クリア</button>
					</div>
				</form>
			</div>
		</div>

		<div>
			<div class="col-md-6" style="margin: 10px 0;">
				<a onclick="return java_post('{{$_app_path}}workbook/edit');"><span class="glyphicon glyphicon-plus"></span><span class="label label-primary">問題集の新規追加</span></a>
			</div>
			<div class="col-md-6" style="text-align: right;">
				@include('Appmanage._parts.paginate')
			</div>
		</div>

		<div>
			<table class="table table-bordered table-striped tablepaginate">
				<thead>
				<tr>
					@if (session()->get('appmanage.account.auth_type') == 1)
					<th style="width:10%;">利用者</th>
					@endif
					<th>タイトル</th>
					<th style="width:20%;">サブタイトル</th>
					<th style="width: 6%;">回答数</th>
					<th style="width: 6%;">最高点</th>
					<th style="width: 6%;">最低点</th>
					<th style="width: 6%;">平均点</th>
					<th style="width: 8%;">登録日</th>
					<th style="width: 8%;">更新日</th>
					<th style="width: 8%;"></th>
				</tr>
				</thead>
				<tbody>
				@forelse ($_a['list'] as $row)
				<tr>
					@if (session()->get('appmanage.account.auth_type') == 1)
					<td>@foreach ($_a['info_list'] as $info) @if ($info['info_id'] == $row['info_id']) {{ $info['pschool_name'] }} @endif @endforeach</td>
					@endif
					<td>{{ $row['title'] }}</td>
					<td>{{ $row['subtitle'] }}</td>
					<td style="text-align: right;">{{ $row['cnt'] }}</td>
					<td style="text-align: right;">{{ sprintf('%0.2f', $row['max_score']) }}</td>
					<td style="text-align: right;">{{ sprintf('%0.2f', $row['min_score']) }}</td>
					<td style="text-align: right;">{{ sprintf('%0.2f', $row['avg_score']) }}</td>
					<td style="text-align: center;">{{ date('Y-m-d', strtotime($row['register_date'])) }}</td>
					<td style="text-align: center;">@if ($row['update_date']) {{ date('Y-m-d', strtotime($row['update_date'])) }} @endif</td>
					<td nowrap style="text-align: center;">
					<a onclick="return java_post('{{$_app_path}}workbook/edit?workbook_id={{ $row['id'] }}');" title="編集"><img src="/images{{$_app_path}}iconmonstr-edit-10-icon-16.png" /></a>&nbsp;
					@if ($row['is_public'])
					<a onclick="return java_post('{{$_app_path}}workbook/stop?workbook_id={{ $row['id'] }}');" title="公開中止"><img src="/images{{$_app_path}}iconmonstr-minus-6-16.png" /></a>&nbsp;
					@else
					<a onclick="return java_post('{{$_app_path}}workbook/pack?workbook_id={{ $row['id'] }}');" title="公開開始"><img src="/images{{$_app_path}}iconmonstr-upload-12-16.png" /></a>&nbsp;
					<a onclick="return java_post('{{$_app_path}}workbook/delete?workbook_id={{ $row['id'] }}');" title="削除"><img src="/images{{$_app_path}}iconmonstr-x-mark-4-icon-16.png" /></a>&nbsp;
					@endif
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="11" class="danger">データがありません。</td>
				</tr>
				@endforelse
				</tbody>
			</table>
		</div>





{{--





--}}
	</div>

	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>
