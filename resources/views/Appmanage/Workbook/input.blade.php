<!DOCTYPE html>
<html>
<head>
@include('Appmanage._parts.header')

<script type="text/javascript">
$(function() {
	$("#btn_return").click(function() {
		java_post('{{$_app_path}}workbook');
		return false;
	});

	$("#btn_copy").click(function() {
		$('#copy_flag').val('1');
		alert_modal('複製します。よろしいでしょうか？', 'よろしければOKボタンを押して下さい。', 1);
		return false;
	});

	$("#btn_submit").click(function() {
		$('#copy_flag').val('0');
		alert_modal('登録します。よろしいでしょうか？', 'よろしければOKボタンを押して下さい。', 1);
		return false;
	});

	$("body").on('click', '.delete_chapter', function() {
		var row = $(this).parent().parent().parent().empty().hidden();
		return false;
	});

	$("body").on('click', '.add_chapter', function() {
		var table = $("#dummy_chapter_box").children().clone(true);
		var wc = $('.chapter-box').length;
		var wq = 1;
		table.find('.chapter-num').text(wc);
		//modify name
		modify_name(table, wc, wq);
		//add
		$('#chapter_box').append(table);
		return false;
	});

	$("body").on('click', '.delete_question', function() {
		var row = $(this).closest("tr").empty().hidden();
		return false;
	});

	$("body").on('click', '.add_question', function() {
		var table = $(this).parent().prev();
		var row = $("#dummy_chapter_box").find('table.quest-table').children('tbody').children().clone(true);
		var wc = table.attr('data-wc');
		var wq = parseInt(table.attr('data-wq')) + 1;
		table.attr('data-wc', wc).attr('data-wq', wq);
		//modify name
		modify_name(row, wc, wq);
		//add
		table.append(row);
		return false;
	});

	$("body").on('change', '.question_regi_type', function() {
		if ($(this).val() == 1){
			//text
			$(this).parent().parent().parent().find('.text').show();
			$(this).parent().parent().parent().find('.audio').show();
			$(this).parent().parent().parent().find('.file').hide();
			$(this).parent().parent().parent().find('.word').css("visibility","visible");
		} else if ($(this).val() == 2) {
			//pdf
			$(this).parent().parent().parent().find('.text').hide();
			$(this).parent().parent().parent().find('.audio').hide();
			$(this).parent().parent().parent().find('.file').show();
			$(this).parent().parent().parent().find('.word').css("visibility","hidden");
		}
		return false;
	});

	$("body").on('click', '.question-modal-close', function() {
		var modal = $(this).parent().prev();
		var table = $(this).parent().parent().parent().parent().parent().parent();
		//title
		var title = modal.find('.question_title').val();
		table.find('.disp_title').val(title);
		//text
		var text = '';
		var type = modal.find('.question_regi_type').val();
		if (type == 2) {
			text = modal.find('.question_file').find('.file-name').val() || '';
		} else {
			text = modal.find('.question_text').val();
		}
		table.find('.disp_text').val(text);
		//choice
		var check = 0;
		modal.find('.choice_true').each(function(ii) {
			if ($(this).prop('checked')){
				check = $(this).val();
				$(this).next().val(1);
			} else {
				$(this).next().val(0);
			}
		});
		var arr = [];
		var mark = '';
		var jj = 0;
		modal.find('.choice_mark').each(function(ii) {
			if ($(this).val()){
				arr[jj] = $(this).val();
				if (ii == check){
					mark = $(this).val();
				}
				jj = jj+1;
			}
		});
		table.find('.choices').val(arr.join(','));
		table.find('.c_answer').val(mark);

		return false;
	});

	$("body").on('click', '.read-cell', function() {
		$(this).parent().parent().find('a.edit_question').click();
		return false;
	});

});

function alert_modal_confirm(){
	$("#dummy_chapter_box").remove();
	$("#action_form").attr('action', '{{$_app_path}}workbook/save');
	$("#action_form").submit();
	return false;
}

function select_clone(aaa, zzz) {
	var arr = [];
	aaa.find('select').each(function(i) {
		arr[i] = $(this).val();
	});
	zzz.find('select').each(function(j) {
		$(this).val(arr[j]);
	});
	zzz.find('textarea').each(function(k) {
		$(this).html($(this).val());
	});
}

function my_quest_copy(aaa) {
	aaa.find('input, textarea, select').each(function() {
		var nam = $(this).attr('name');
		if (nam.indexOf('[tags]') != -1 || nam.indexOf('[choices]') != -1 || nam.indexOf('[c_answer]') != -1) {
			$("form input[name='disp_"+nam+"']").val($(this).val());
		} else if (nam.indexOf('[question_type]') != -1) {
			var renam = nam;
			var reval = "";
			if ($(this).val() == 1){
				renam = nam.replace(/question_type/g, 'question_text');
				reval = aaa.find("textarea.disp_text").val();
			} else {
				renam = nam.replace(/question_type/g, 'question_file');
				reval = aaa.find("input.disp_file").val();
			}
			$("form input[name='disp_"+nam+"']").val(reval);
		}
	});
}

function modify_name(target, wc, wq) {
	target.find('input, select, textarea').each(function() {
		if ($(this).attr('name') != undefined) {
			var rename = $(this).attr('name').replace(/xxx/g, wc).replace(/yyy/g, wq);
			$(this).attr('name', rename);
		}
	});
	target.find('a.edit_question').each(function() {
		if ($(this).attr('data-target') != undefined) {
			var rename = $(this).attr('data-target').replace(/xxx/g, wc).replace(/yyy/g, wq);
			$(this).attr('data-target', rename);
			$(this).next().next().attr('id', rename.replace(/#/g, ''))
		}
	});
	target.find('table.quest-table').attr('data-wc', wc).attr('data-wq', wq);
	target.find('input.seq_no').val(wq);
	target.find('input.tags').val('問'+wq);
	return false;
}

</script>


<style type="text/css">
.quest-table,    .choice-table    { width: 100%; table-layout: fixed; }
.quest-table th, .choice-table th { padding: 4px; background-color: white; }
.quest-table td, .choice-table td { padding: 0px; height: 24px; background-color: white; }
.quest-table a,  .choice-table a  { font-size: 14px; }
.quest-cell { background-color: rgba(0,0,0,0); width:100%; height:100%; border: 0px solid; font-size: 14px; padding: 1px; }
.edit-cell  { background-color: rgba(0,0,0,0); }
.read-cell  { background-color: rgba(0,0,0,0); color: gray; }
.my-notes { font-size: 8px; color: gray; font-weight: normal; text-align: left; }
.alert-success { color: black; }
</style>

</head>
<body>
<div id="contents">
	@include('Appmanage._parts.body')

	<div class="container">

		<ol class="breadcrumb">
			<li>問題集管理</li>
			<li>問題集確認</li>
		</ol>

		<div class="alert alert-info"><span class="glyphicon glyphicon-asterisk required"></span>印のついた項目は必須入力です。</div>

		@include('Appmanage._parts.message')

		<form method="post" id="action_form" role="form" enctype="multipart/form-data" class="form-horizontal" >
			{{ csrf_field() }}
			<input type="hidden" name="_i[id]" value="{{ old('_i.id', request('_i.id')) }}" />
			<input type="hidden" name="_i[copy_flag]" id="copy_flag" value="0" />

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

			@if (session()->get('appmanage.account.auth_type') != 1 && count($_a['book_type_list']) == 1)
				@foreach ($_a['book_type_list'] as $option)<input type="hidden" name="_i[workbook_type_id]" value="{{ $option['book_type_id'] }}" />@endforeach
			@else
			<div class="form-group">
				<label class="col-sm-3 control-label">種別<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-4">
					<select name="_i[workbook_type_id]" class="form-control">
					@foreach ($_a['book_type_list'] as $option)<option value="{{ $option['book_type_id'] }}" @if ($option['book_type_id']==old('_i.workbook_type_id', request('_i.workbook_type_id'))) selected @endif>{{ $option['title'] }}</option>@endforeach
					</select>
				</div>
			</div>
			@endif

			<div class="form-group">
				<label class="col-sm-3 control-label">問題集タイトル<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-8">
					<input type="text" name="_i[title]" value="{{ old('_i.title', request('_i.title')) }}" class="form-control" maxlength="255" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">問題集配信元<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-8">
					<input type="text" name="_i[subtitle]" value="{{ old('_i.subtitle', request('_i.subtitle')) }}" class="form-control" maxlength="255" /></td>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">問題集詳細<span class="glyphicon glyphicon-asterisk required"></span></label>
				<div class="col-sm-8">
					<textarea name="_i[detail_text]" class="form-control" rows="5">{{ old('_i.detail_text', request('_i.detail_text')) }}</textarea>
				</div>
			</div>

			<div id="chapter_box" data-cnt="{{ count(old('_i.chapter_list', request('_i.chapter_list'))) }}">
			@foreach (old('_i.chapter_list', request('_i.chapter_list')) as $chapter)
				@include('Appmanage.Workbook._chapter_table', ['ccc'=>$loop->index, 'qqq'=>0, 'chapter'=>$chapter])
			@endforeach
			</div>

			<div id="dummy_chapter_box" class="d-none">
				@include('Appmanage.Workbook._chapter_table', ['ccc'=>'xxx', 'qqq'=>'yyy', 'chapter'=>$_a['dummy_chapter']])
			</div>

			<div class="col-md-12" style="margin-bottom: 30px;">
				<a class="add_chapter"><span class="glyphicon glyphicon-plus"></span><span class="label label-primary">テストの追加</span></a>
			</div>
		</form>

		<div class="col-sm-12">
			<button id="btn_submit" type="button" class="btn btn-primary">確認</button>
			<button id="btn_copy"   type="button" class="btn btn-warning">複製</button>
			<button id="btn_return" type="button" class="btn btn-default">戻る</button>
		</div>


	</div>

	@include('Appmanage._parts.footer')
</div><!-- end wrap -->

</body>
</html>
