@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
	$('#search_cond_clear').click(function() {  // clear
		$("input[name='select_word']").val("");
		$("select[name='select_grade']").val("");
		$("select[name='select_pschool']").val("");
		$("select[name='select_state']").val("");
		return false;
	});

	$('input[name="disp_billing"]').change(function() {
		var prop = $('#prop').prop('checked');
	    if (prop) {
	        $('#billing_state').show();
	      } else {
	        $('#billing_state').hide();;
	      }
	});
});
</script>
<!-- @include('_parts.student.menu') -->
<div id="center_content_header" class="box_border1">
	<h2 class="float_left">{{session()->get('main_title')}}</h2>
	<div class="center_content_header_right">
		<div class="top_btn">
			<ul>
				<a href="{{ url('/school/student/entry') }}"><li><i
						class="fa fa-plus"></i> 会員登録</li></a>
				<a href="{{ url('/school/student/downloadtemplate') }}"><li><i
						class="fa fa-file-excel-o"></i> CSV Template</li></a>
				<a href="{{ url('/school/student/uploadinput') }}"><li><i
						class="fa fa-upload"></i> CSV登録</li></a>
				<a href="/school/student/downloadcsv/csv"><li><i
						class="fa fa-download"></i> CSV出力</li></a>
			</ul>
		</div>
	</div>
	<div class="clr"></div>
</div>
<div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">{!!
	Breadcrumbs::render('index') !!}</div>
<!--@foreach ($topic_list as $link => $topic) -->
<!-- <a class="text_link" href="{{$_app_path}}{{$link}}">{{$topic}}</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp; -->
<!-- 	@if ($loop->last) -->
<!-- 		{{$topic}} -->
<!-- 	@endif -->
<!-- @endforeach -->



<!-- </div> -->
<h3 id="content_h3" class="box_border1">一覧</h3>

@if (isset($message))
<ul class="message_area">
	@if($message == '99')
	<ul class="message_area">
		<li class="error_message">
			{{$lan['error_occurs_process_execute_title']}}</li>
	</ul>
	@endif
</ul>
@endif
<div class="search_box box_border1 padding1">



	<!-- 	<form action="{{ URL::to('/school/student/search') }}" method="get"> -->
	<form action="{{ URL::to('/school/student/search') }}"
		class="form-horizontal" method="post" enctype="multipart/form-data">
		<table>
			<colgroup>
				<col width="10%" />
				<col width="30%" />
				<col width="10%" />
				<col width="30%" />
			</colgroup>
			<tr>
				<th>名前(フリガナ)</th>
				<td><input class="text_long" type="search" name="select_word"
					id="select_word" value="" /></td>
			</tr>
			<tr>
				<th>ステータス</th>
				<td><select name="select_state" id="select_state"
					style="max-width: 200px;">
						<option value=""></option>
						<option label="契約中" value="1" selected="selected">契約中</option>
						<option label="契約終了" value="9">契約終了</option>

				</select></td>
			</tr>
			<tr>
				<th>最新の請求情報</th>
				<td><input id="prop" type="checkbox" name="disp_billing" value="1"
					checked />&nbsp;表示する</td>
				<td id="billing_state"><select name="workflow_status"
					id="select_state" style="max-width: 200px;">
						<option value=""></option>
						<option label="編集中" value="0">編集中</option>
						<option label="編集完了" value="1">編集完了</option>
						<option label="請求書発送済み" value="11">請求書発送済み</option>
						<option label="請求データ作成済み" value="21">請求データ作成済み</option>
						<option label="口座振替 処理中" value="22">口座振替 処理中</option>
						<option label="口座振替未完了" value="29">口座振替未完了</option>
						<option label="入金済み" value="31">入金済み</option>

				</select></td>
			</tr>
			<tr>
				<th>{{$lan['register_date']}}</th>
				<td><input type="text" id="datepicker" name="register_date" value=""></td>
				<th>{{$lan['update_date']}}</th>
				<td><input type="text" id="datepicker1" name="update_date" value=""></td>
			</tr>
		</table>

		<div class="clr"></div>
		<input type="submit" id="btn_student_search" class="submit"
			name="search_button" value="検索"> <input type="button" class="submit"
			id="search_cond_clear" value="すべてクリア">
		<div class="clr"></div>
		{{ csrf_field() }}
	</form>
</div>

<div id="section_content1">

	<table class="table1 tablesorter body_scroll_table"
		style="text-align: left;">
		<thead>
			<tr>
				<th>{{$lan['student_name']}}</th>
				<th>{{$lan['student_no_title']}}</th>
				<th>{{$lan['billing_tag_title']}}</th>
				<th>{{$lan['class_title']}}</th>
				<th>{{$lan['register_date']}}</th>
				<th>{{$lan['update_date']}}</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($student_list as $row)
			<tr>
				<td><a href="/school/student/detail/{{$row['id']}}">{{$row['student_name']}}</a></td>
				<td>{{$row['student_no']}}</td>
				<td>{{$row['workflow_status']}}</td>
				<td>@if ($row['class_list']) @foreach ($row['class_list'] as $class)
					@if ($class['class_name']) {{$class['class_name']}} @endif
					@endforeach @endif</td>
				<td>{{Carbon\Carbon::parse($row['register_date'])->format('Y年m月d日')}}</td>
				<td>{{Carbon\Carbon::parse($row['update_date'])->format('Y年m月d日')}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<script type="text/javascript">
		$(function() {
			//$(".tablesorter").tablesorter();
		});
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker1" ).datepicker();
  } );
  </script>
@stop
