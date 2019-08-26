<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')

<script type="text/javascript">
$(function() {
	$("#btn_return").click(function() {
		java_post('{{$_app_path}}info');
		return false;
	});

	$("#btn_submit").click(function() {
		alert_modal('登録します。よろしいでしょうか？', 'よろしければOKボタンを押して下さい。', 1);
		return false;
	});

	$("body").on('click', '.add_fee', function() {
		var row = $('#dummy_fee_box').children().children().clone(true);
		row.addClass('fee_row');
		//modify name
		modify_name(row, $('.fee_row').length);
		//add
		$('#fee_box').append(row);
		$('#fee_empty').hide();
		row.find('a.edit_fee').click();
		return false;
	});

	$("body").on('click', '.delete_fee', function() {
		var row = $(this).closest("tr");
		var fee = row.find('.fee_id').val();
		if (fee) {
			row.find('.del_flag').val(1);
			row.hide();
		} else {
			row.remove();
		}
		return false;
	});

	$("body").on('click', '.fee-modal-close', function() {
		var modal = $(this).parent().prev();
		var table = $(this).parent().parent().parent().parent().parent().parent();
		//title
		var title = modal.find('input.fee_title').val();
		table.find('td.fee_title').text(title);
		//policy
		var policy = '';
		modal.find('input.policy_title').each(function(ii) {
			policy += '<p>'+$(this).val()+'</p>';
		});
		table.find('td.fee_policy').html(policy);
		//fee_price
		var price = modal.find('select.fee_price option:selected').text();
		table.find('td.fee_price').text(price);

		return false;
	});

	$("body").on('change', 'input.dummy_check', function() {
		if ($(this).prop('checked')) {
			$(this).parent().next().val(1);

			if ($(this).hasClass('entry_flag')) {
				$(this).parent().parent().prev().find('.dummy_value').val(1);
				$(this).parent().parent().prev().find('.active_flag').prop('checked', true);
			}
		} else {
			$(this).parent().next().val(0);

			if ($(this).hasClass('active_flag')) {
				$(this).parent().parent().next().find('.dummy_value').val(0);
				$(this).parent().parent().next().find('.entry_flag').prop('checked', false);
			}
		}

		return false;
	});

});

function alert_modal_confirm(){
	$("#action_form").attr('action', '{{$_app_path}}info/save');
	$("#action_form").submit();
	return false;
}

function modify_name(target, cnt) {
	target.find('input, select, textarea').each(function() {
		if ($(this).attr('name') != undefined) {
			var rename = $(this).attr('name').replace(/xxx/g, cnt);
			$(this).attr('name', rename);
		}
	});
	target.find('a.edit_fee').each(function() {
		if ($(this).attr('data-target') != undefined) {
			var rename = $(this).attr('data-target').replace(/xxx/g, cnt);
			$(this).attr('data-target', rename);
			$(this).next().next().attr('id', rename.replace(/#/g, ''))
		}
	});
	return false;
}

</script>


<style type="text/css">
#dummy_fee_box { display: none; }
#fee_box p { margin: 0; }
</style>

</head>
<body>
<div id="contents">
@include('Appmanage._parts.body')

	<div class="container">

		<ol class="breadcrumb">
		  <li>利用者管理</li>
		  <li>利用者確認</li>
		</ol>

		<div class="alert alert-info"><span class="glyphicon glyphicon-asterisk required"></span>印のついた項目は必須入力です。</div>

@include('Appmanage._parts.message')

		<form action="{{$_app_path}}news/confirm" method="post" id="action_form"  role="form" enctype="multipart/form-data" class="form-horizontal" >
			{{ csrf_field() }}
			<input type="hidden" name="_i[info_id]" value="{{ old('_i.info_id', request('_i.info_id')) }}" />

@if (session()->get('appmanage.account.auth_type') != 1)
			<input type="hidden" name="_i[pschool_id]" value="{{ session()->get('appmanage.account.pschool_id') }}">
@else
			<ol class="breadcrumb"><li>利用者<span class="glyphicon glyphicon-asterisk required"></span></li></ol>
			<div class="form-group">
				<label class="col-sm-3 control-label">利用者</label>
				<div class="col-sm-4">
					@if (old('_i.info_id', request('_i.info_id')))
					<select name="_i[pschool_id]" class="form-control">
					@foreach ($_a['pschool_list'] as $option) @if ($option['pschool_id']==old('_i.pschool_id', request('_i.pschool_id')))<option value="{{ $option['pschool_id'] }}" selected>{{ $option['pschool_name'] }}</option> @endif @endforeach
					</select>
					@else
					<select name="_i[pschool_id]" class="form-control">
					@foreach ($_a['pschool_list'] as $option)<option value="{{ $option['pschool_id'] }}" >{{ $option['pschool_name'] }}</option>@endforeach
					</select>
					@endif
				</div>
			</div>
@endif

			<ol class="breadcrumb"><li>メニュー名<span class="glyphicon glyphicon-asterisk required"></span></li></ol>
			<div class="form-group">
				<label class="col-sm-3 control-label">問題集</label>
				<div class="col-sm-4">
					<input type="text" name="_i[workbook_title]" value="{{ old('_i.workbook_title', request('_i.workbook_title')) }}" class="form-control" maxlength="32" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">お知らせ</label>
				<div class="col-sm-4">
					<input type="text" name="_i[news_title]" value="{{ old('_i.news_title', request('_i.news_title')) }}" class="form-control" maxlength="32" />
				</div>
			</div>

			<ol class="breadcrumb"><li>問題集種別<span class="glyphicon glyphicon-asterisk required"></span></li></ol>
@foreach (old('_i.book_type_list', request('_i.book_type_list')) as $bb=>$type)
			<div class="form-group">
				<input type="hidden" name="_i[book_type_list][{{$bb}}][id]" value="{{ $type['id'] }}" />
				<input type="hidden" name="_i[book_type_list][{{$bb}}][title]" value="{{ $type['title'] }}" />
				<label class="col-sm-3 control-label">{{ $type['title'] }}</label>
				<div class="col-sm-2">
					<label class="form-control"><input type="checkbox" name="_i[book_type_ids][]" value="{{ $type['id'] }}" @foreach (old('_i.book_type_ids', request('_i.book_type_ids')) as $type_id) @if ($type['id'] == $type_id) checked @endif @endforeach /> 表示 </label>
				</div>
			</div>
@endforeach

			<ol class="breadcrumb"><li>お知らせ種別<span class="glyphicon glyphicon-asterisk required"></span></li></ol>
@foreach (old('_i.news_type_list', request('_i.news_type_list')) as $nn=>$type)
			<div class="form-group">
				<div class="col-sm-1">
				</div>
				<div class="col-sm-2">
					<input type="hidden" name="_i[news_type_list][{{$nn}}][id]" value="{{ $type['id'] }}" >
				 	<input type="text" name="_i[news_type_list][{{$nn}}][title]" value="{{ $type['title'] }}" class="form-control" style="text-align: right;" maxlength="32"/>
				</div>
				<div class="col-sm-2">
					<label class="form-control"><input type="checkbox" class="dummy_check active_flag" value="1" @if ($type['active_flag']) checked @endif /> 表示 </label>
					<input type="hidden" class="dummy_value" name="_i[news_type_list][{{$nn}}][active_flag]" value="{{ $type['active_flag'] }}" />
				</div>
				<div class="col-sm-2">
					<label class="form-control"><input type="checkbox" class="dummy_check entry_flag" value="1" @if ($type['entry_url']) checked @endif /> 記事追加 </label>
					<input type="hidden" class="dummy_value" name="_i[news_type_list][{{$nn}}][entry_url]" value="{{ $type['entry_url'] }}" />
				</div>
			</div>
@endforeach

@if ($_a['itunes_list'])
			<ol class="breadcrumb"><li>有料コース</li></ol>

			<div>
				<div class="col-md-6" style="margin: 0 0 10px;">
					<a class="add_fee"><span class="glyphicon glyphicon-plus"></span><span class="label label-primary">有料の新規追加</span></a>
				</div>
				<div class="col-md-6" style="text-align: right;">
@include('Appmanage._parts.paginate')
				</div>
			</div>

			<div>
				<table id="fee_box" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th style="width:  4%;">公開</th>
						<th>名称</th>
						<th style="width: 20%;">規約</th>
						<th style="width: 20%;">価格帯</th>
						<th style="width:  8%;">会員数</th>
						<th style="width:  8%;">登録日</th>
						<th style="width:  8%;">更新日</th>
						<th style="width:  8%;"></th>
					</tr>
					</thead>
					<tbody>
@forelse ($_a['list'] as $fff=>$fee)
@include('Appmanage.Info._fee_table', ['fff'=>$fff, 'fee'=>$fee])
@empty
					<tr id="fee_empty">
						<td colspan="8" class="danger">データがありません。</td>
					</tr>
@endforelse
					</tbody>
				</table>
			</div>
@endif
		</form>


		<div class="col-sm-12">
			<div>
				<table id="dummy_fee_box">
					<tbody>
@include('Appmanage.Info._fee_table', ['fff'=>'xxx', 'fee'=>$_a['dummy_fee']])
					</tbody>
				</table>
			</div>
			<button id="btn_submit" type="button" class="btn btn-primary">確認</button>
			<button id="btn_return" type="button" class="btn btn-default">戻る</button>
		</div>

	</div>

@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>
