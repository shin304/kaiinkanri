<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')

<script type="text/javascript">
$(function() {
	$("#btn_return").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}news');
		$("#action_form").submit();
		return false;
	});

	$("#btn_submit").click(function() {
		alert_modal('登録します。よろしいでしょうか？', 'よろしければOKボタンを押して下さい。', 1);
		return false;
	});
});

function alert_modal_confirm(){
	$("#action_form").attr('action', '{{$_app_path}}news/save');
	$("#action_form").submit();
	return false;
}
</script>


<style type="text/css">
.table-bordered { margin-top: -10px; margin-bottom: 40px; }
.table-bordered th { width: 30%; vertical-align: middle !important; }
.table-bordered td { width: 70%; }
</style>

</head>
<body>
<div id="contents">
	@include('Appmanage._parts.body')

	<div class="container">

		<ol class="breadcrumb">
			<li>お知らせ管理</li>
			<li>お知らせ確認</li>
		</ol>

		<div class="alert alert-info"><span class="glyphicon glyphicon-asterisk required"></span>印のついた項目は必須入力です。</div>

		@include('Appmanage._parts.message')

		<form method="post" id="action_form" role="form" enctype="multipart/form-data" class="form-horizontal" >
			{{ csrf_field() }}
			<input type="hidden" name="_i[news_id]" value="{{ old('_i.news_id', request('_i.news_id')) }}" />

			@if (session()->get('appmanage.account.auth_type') != 1 || 1)
				<input type="hidden" name="_i[info_id]" value="{{ old('_i.info_id', request('_i.info_id')) }}" />
			@else
			<div class="form-group">
				<label class="col-sm-3 control-label">利用者<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-4">
					<select name="_i[info_id]" class="form-control">
					@foreach ($_a['info_list'] as $option)<option value="{{ $option['info_id'] }}" @if ($option['info_id']==old('_i.info_id', request('_i.info_id'))) selected @else disabled @endif>{{ $option['pschool_name'] }}</option>@endforeach
					</select>
				</div>
			</div>
			@endif

			@if (count($_a['news_type_list']) == 1 && session()->get('appmanage.account.auth_type') != 1)
				@foreach ($_a['news_type_list'] as $option)<input type="hidden" name="_i[news_type_id]" value="{{ $option['news_type_id'] }}" />@endforeach
			@else
			<div class="form-group">
				<label class="col-sm-3 control-label">種別<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-4">
					<select name="_i[news_type_id]" class="form-control">
					@foreach ($_a['news_type_list'] as $option)<option value="{{ $option['news_type_id'] }}" @if ($option['news_type_id']==old('_i.news_type_id', request('_i.news_type_id'))) selected @endif>{{ $option['title'] }}</option>@endforeach
					</select>
				</div>
			</div>
			@endif

			<div class="form-group">
				<label class="col-sm-3 control-label">タイトル<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-8">
					<input type="text" name="_i[title]" value="{{ old('_i.title', request('_i.title')) }}" class="form-control" maxlength="255" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">配信元<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-8">
					<input type="text" name="_i[subtitle]" value="{{ old('_i.subtitle', request('_i.subtitle')) }}" class="form-control" maxlength="255" /></td>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">内容サマリ<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-8">
					<input type="text" name="_i[content_title]" value="{{ old('_i.content_title', request('_i.content_title')) }}" class="form-control" maxlength="255" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">内容<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-8">
					<textarea name="_i[content]" class="form-control" rows="5">{{ old('_i.content', request('_i.content')) }}</textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">表示日<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-2">
					<input type="text" name="_i[disp_date]" value="@if (old('_i.disp_date', request('_i.disp_date'))) {{ old('_i.disp_date', request('_i.disp_date')) }} @endif" class="form-control datepicker" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">公開開始日時</label>
				<div class="col-sm-2">
					<input type="text" name="_i[publish_date_from]" value="{{ old('_i.publish_date_from', request('_i.publish_date_from')) }}" class="form-control datetimepicker" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">公開終了日時</label>
				<div class="col-sm-2">
					<input type="text" name="_i[publish_date_to]" value="{{ old('_i.publish_date_to', request('_i.publish_date_to')) }}" class="form-control datetimepicker" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">ホームページ</label>
				<div class="col-sm-8">
					<input type="text" name="_i[link_url]" value="{{ old('_i.link_url', request('_i.link_url')) }}" class="form-control" maxlength="255" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">PDF</label>
				<div class="col-sm-8">
					@include('Appmanage._parts.input_file', ['input'=>'', 'file'=>'file_pdf', 'file_val'=>old('_i.file_pdf', request('_i.file_pdf')), 'link'=>'link_pdf', 'link_val'=>old('_i.link_pdf', request('_i.link_pdf'))])
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">通知日時</label>
				<div class="col-sm-2">
					<input type="text" name="_i[notification_datetime]" value="{{ old('_i.notification_datetime', request('_i.notification_datetime')) }}" class="form-control datetimepicker" />
				</div>
			</div>

		</form>

		<div class="col-sm-12">
			<button id="btn_submit" type="button" class="btn btn-primary">確認</button>
			<button id="btn_return" type="button" class="btn btn-default">戻る</button>
		</div>

	</div>

	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>
