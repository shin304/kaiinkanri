<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')

<script type="text/javascript">
</script>

<style type="text/css">
#search_form input[type='number'] { width: 100px; text-align: right; }
</style>
</head>
<body>
<div id="contents">
	@include('Appmanage._parts.body')

	<div class="container">

		<ol class="breadcrumb">
		  <li>お知らせ管理</li>
		  <li>お知らせ一覧</li>
		</ol>

		@include('Appmanage._parts.message')

		<div class="panel panel-default">
			<div class="panel-heading">
				<a id="link_search"><span class="glyphicon glyphicon-search"></span>検索条件</a>
			</div>

			<div class="panel-body" id="search_area">
				<form action="{{$_app_path}}news" method="post" id="search_form" class="form-horizontal">
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
						<label class="col-sm-2 control-label">公開</label>
						<div class="col-sm-4">
							<select class="form-control" name="_c[status]">
								<option value=""></option>
								<option value="1" @if (old('_c.status', request('_c.status')) == 1) selected @endif>公開予定</option>
								<option value="2" @if (old('_c.status', request('_c.status')) == 2) selected @endif>公開中</option>
								<option value="3" @if (old('_c.status', request('_c.status')) == 3) selected @endif>公開済</option>
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
				</form>

				<div class="col-sm-12">
					<button id="btn_search" type="button" class="btn btn-primary">検索</button>
					<button id="btn_reset" type="button" class="btn btn-default">クリア</button>
				</div>
			</div>
		</div><!-- end panel -->


		<div>
			<div class="col-md-6" style="margin: 10px 0;">
				<a onclick="return java_post('{{$_app_path}}news/edit');"><span class="glyphicon glyphicon-plus"></span><span class="label label-primary">お知らせの新規追加</span></a>
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
					<th style="width: 8%;">表示日</th>
					<th style="width: 8%;">公開開始日</th>
					<th style="width: 8%;">公開終了日</th>
					<th style="width:10%;">リンク</th>
					<th style="width: 8%;">通知日時</th>
					<th style="width: 8%;">登録日時</th>
					<th style="width: 8%;">更新日時</th>
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
					<td align="center">{{ $row['disp_date'] }}</td>
					<td align="center">@if ($row['publish_date_from']) {{ date('Y-m-d', strtotime($row['publish_date_from'])) }}<br/>{{ date('H:i:s', strtotime($row['publish_date_from'])) }} @endif</td>
					<td align="center">@if ($row['publish_date_to']) {{ date('Y-m-d', strtotime($row['publish_date_to'])) }}<br/>{{ date('H:i:s', strtotime($row['publish_date_to'])) }} @endif</td>
					<td>
						@if ($row['link_url'])<a href="{!! $row['link_url'] !!}" target="_blank">ホームページ</a>@endif
						@if ($row['link_pdf'])<a href="{!! $row['link_pdf'] !!}" target="_blank">PDF</a>@endif
					</td>
					<td align="center">@if ($row['notification_datetime']) {{ date('Y-m-d', strtotime($row['notification_datetime'])) }}<br/>{{ date('H:i:s', strtotime($row['notification_datetime'])) }} @endif</td>
					<td align="center">@if ($row['register_date']) {{ date('Y-m-d', strtotime($row['register_date'])) }}<br/>{{ date('H:i:s', strtotime($row['register_date'])) }} @endif</td>
					<td align="center">@if ($row['update_date']) {{ date('Y-m-d', strtotime($row['update_date'])) }}<br/>{{ date('H:i:s', strtotime($row['update_date'])) }} @endif</td>
					<td align="center">
						<a onclick="return java_post('{{$_app_path}}news/edit?news_id={{$row['news_id']}}');" title="編集"><img src="/images{{$_app_path}}iconmonstr-edit-10-icon-16.png" /></a>&nbsp;
						<a onclick="return java_post('{{$_app_path}}news/delete?news_id={{$row['news_id']}}');" title="削除"><img src="/images{{$_app_path}}iconmonstr-x-mark-4-icon-16.png" /></a>
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
	</div><!-- end container -->

	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>
