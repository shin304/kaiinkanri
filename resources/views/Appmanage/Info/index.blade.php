<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')
<style type="text/css">
.tablepaginate p { margin: 0; }
</style>
</head>
<body>
<div id="contents">
	@include('Appmanage._parts.body')

	<div class="container">

		<ol class="breadcrumb">
		  <li>利用者管理</li>
		  <li>利用者一覧</li>
		</ol>

		@include('Appmanage._parts.message')

		@if (session()->get('appmanage.account.auth_type') == 1)
		<div class="panel panel-default">
			<div class="panel-heading"><a id="link_search"><span class="glyphicon glyphicon-search"></span>検索条件</a></div>
			<div class="panel-body" id="search_area">
				<form action="{{$_app_path}}info" method="post" id="search_form" class="form-horizontal">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="col-sm-2 control-label">利用者</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="_c[pschool_name]" value="{{ old('_c.pschool_name', request('_c.pschool_name')) }}">
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
		@else
		<input type="hidden" name="_c[info_id]" value="{{ old('_c.info_id', request('_c.info_id')) }}" />
		@endif

		<div>
			<!-- 新規登録ページ -->
			<div class="col-md-6" style="margin: 5px 0;">
				@if (session()->get('appmanage.account.auth_type') == 1)
				<a onclick="return java_post('{{$_app_path}}info/edit');"><span class="glyphicon glyphicon-plus"></span><span class="label label-primary">利用者の新規追加</span></a>
				@endif
			</div>

			<div class="col-md-6">
				@include('Appmanage._parts.paginate')
			</div>
		</div>

		<div>
			<table class="table table-bordered table-striped tablepaginate">
				<thead>
				<tr>
					<th>利用者</th>
					<th style="width: 10%;">問題集名称</th>
					<th style="width: 10%;">問題集種別</th>
					<th style="width: 10%;">お知らせ名称</th>
					<th style="width: 10%;">お知らせ種別</th>
					<th style="width:  8%;">会員数</th>
					<th style="width:  8%;">問題集数</th>
					<th style="width:  8%;">登録日</th>
					<th style="width:  8%;">更新日</th>
					<th style="width:  8%;"></th>
				</tr>
				</thead>
				<tbody>
				@forelse ($_a['list'] as $row)
				<tr>
					<td>{{ $row['pschool_name'] }}</td>
					<td>{{ $row['workbook_title'] }}</td>
					<td>@foreach ($row['book_type_list'] as $type) @foreach ($row['book_type_ids'] as $type_id) @if ($type['id'] == $type_id) <p>{{ $type['title'] }}</p>  @endif @endforeach @endforeach</td>
					<td>{{ $row['news_title'] }}</td>
					<td>@foreach ($row['news_type_list'] as $type) @if ($type['active_flag']) <p>{{ $type['title'] }} </p> @endif @endforeach</td>
					<td align="right">{{ $row['member_counts']['real'] }}</td>
					<td align="right">{{ $row['workbook_counts']['active'] }}</td>
					<td align="center">{{ date('Y-m-d', strtotime($row['register_date'])) }}</td>
					<td align="center">@if ($row['update_date']) {{ date('Y-m-d', strtotime($row['update_date'])) }} @endif</td>
					<td align="center">
						<a onclick="return java_post('{{$_app_path}}info/edit?info_id={{ $row['info_id'] }}');" title="編集"><img src="/images{{$_app_path}}iconmonstr-edit-10-icon-16.png" /></a>&nbsp;
						<a onclick="return java_post('{{$_app_path}}info/delete?info_id={{ $row['info_id'] }}');" title="削除"><img src="/images{{$_app_path}}iconmonstr-x-mark-4-icon-16.png" /></a>&nbsp;
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="10" class="danger">データがありません。</td>
				</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>

	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>
