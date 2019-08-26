@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
	$('#selectAll1').click(function() {  //on click
        if(this.checked) { // check select status
            $('.select1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "question_select"
            });
        }else{
            $('.select1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "question_select"
            });
        }
    });
	$('#selectAll2').click(function() {  //on click
        if(this.checked) { // check select status
            $('.select2').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "question_select"
            });
        }else{
            $('.select2').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "question_select"
            });
        }
    });
	$('#selectAll3').click(function() {  //on click
        if(this.checked) { // check select status
            $('.select3').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "question_select"
            });
        }else{
            $('.select3').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "question_select"
            });
        }
    });
	$('#selectAll4').click(function() {  //on click
        if(this.checked) { // check select status
            $('.select4').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "question_select"
            });
        }else{
            $('.select4').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "question_select"
            });
        }
    });
	$("#school_type").change(function(){
		var school_cat = $(this).val();
		if (!school_cat)
		{
			$("#grade_option option").remove();
			$("#grade_option").prepend($("<option>").html("").val(""));
			return;
		}
		$.get(
			"{{$_app_path}}ajaxMailMessage/school",
			{school_cat:school_cat},
			function(data)
			{
				var desc = "{{$birth_year_title}}";
				$("#grade_option option").remove();
				$("#grade_option").append($("<option>").html(desc).val(key));
				for(var key in data.grades)
				{
					var school_year_id = (parseInt(key)) + 1;
					var school_year = school_year_id + desc;
					$("#grade_option").append($("<option>").html(school_year).val(school_year_id));
				}
				$("#grade_option").prepend($("<option>").html("").val(""));
				$("#grade_option").val("");
			},
			"jsonp"
		);
	});
	$("#btn_return").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}broadcastmail/entry');
		$("#action_form").submit();
		return false;
	});
	$("#btn_submit").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}broadcastmail/entry?select=1');
		$("#action_form").submit();
		return false;
	});
	$("#btn_search").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}broadcastmail/select');
		$("#action_form").submit();
		return false;
	});
    $("#students").tablesorter( {
        headers: {
            0: { sorter: false },
            2: { sorter: false }
        }
    } );
    $("#teachers").tablesorter( {
        headers: {
            0: { sorter: false }
        }
    } );
//     $('#teacher_table').hide();
    $('#student_box').change(function() {
    	if ($('#student_box').prop('checked') || $('#parent_box').prop('checked')) {
    		$('#student_table').show();
    	} else {
    		$('#student_table').hide();
    	}
    	return false;
    });
    $('#parent_box').change(function() {
    	if ($('#student_box').prop('checked') || $('#parent_box').prop('checked')) {
    		$('#student_table').show();
    	} else {
    		$('#student_table').hide();
    	}
    	return false;
    });
	$('#teacher_box').change(function() {
		if($(this).prop('checked')) {
			$('#teacher_table').show();
		} else {
			$('#teacher_table').hide();
		}
		return false;
	});
});
</script>
<td style="min-width: 700px;" id="center_content">
	<div class="section">
		<div id="center_content_header" class="box_border1">
			<h2 class="float_left"><i class="fa fa-envelope-o"></i>{{$broadcast_mail_main}}</h2>
			<div class="center_content_header_right">
				<div class="top_btn">
				</div>
			</div>
			<div class="clr"></div>
		</div><!--center_content_header-->
		<div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('parent') !!}</div>

		<form id="action_form" method="post" enctype="multipart/form-data">
		<h3 id="content_h3" class="box_border1">{{$select_addressees}}</h3>
		<div class="search_box box_border1 padding1"><!-- 検索の入力ボックスの両サイドの余白 -->
				<input type="hidden" name="title" value="{{$request['title']}}"/>
				<input type="hidden" name="id" value="{{$request['id']}}"/>
				<input type="hidden" name="send_flag" value="{{$request['send_flag']}}"/>
				<div style="display:none;">
					<textarea name="content">{{$request['content}}</textarea>
				</div>

<!-- 				{{**include file="pages_pc/school/_parts/student_search5.html" **}} -->
<!-- 				{{include file="pages_pc/school/_parts/student_search_broadcast.html" }} -->
		</div>
		<div id="section_content1">
			@if(isset($failed_deli_list))
				{{$fail_send_guardian_title}}
				@foreach ($failed_deli_list as $parent_name)
					{{$parent_name}}
				@endforeach
			@endif
			@if($request->errors)
				<ul class="message_area">
				<!-- TODO あとで実装すること -->
				@if(isset($request->errors['student_ids']['notEmpty']))<li class="error_message">{{$msg_pls_choose_addressees}}</li>@endif
				</ul>
				<br/>
			@endif
					<p>{{$bc_target}}<br/>
						<input id="student_box" type="checkbox" name="bc_student" value="1" @if(!$request['bc_option || $request['bc_option == 1 ) checked @endif >
						<label for="student">{{$student_and_parent}}</label></input>
						&nbsp;
						<input id="teacher_box" type="checkbox" name="bc_teacher" value="1" @if($request['bc_option == 1) checked @endif >
						<label for="teacher">{{$teacher}}</label></input>
					</p><br/>
				<div id="student_table">
				<table class="table_list tablesorter body_scroll_table" id="students">

					<thead>
						<tr>
						<th class="text_title" style="width:60px;"><input type="checkbox" id="selectAll1"></th>
<!-- 						<th class="text_title" style="width:60px;"><input type="checkbox" id="selectAll2">{{$abbr_parent_mail2}}</th> -->
						<th class="text_title header" style="width:200px;">{{$parent_name}}</th>
						<th class="text_title" style="width:60px;"><input type="checkbox" id="selectAll3">{{$abbr_student}}</th>
						<th class="text_title header" style="width:220px;">{{$student_no}}</th>
						<th class="text_title header" style="width:200px;">{{$student_name}}</th>
						<th class="text_title header" style="width:180px;">{{$furigana}}</th>
<!-- 					<th class="text_title header" style="width:180px;">{{$school}}</th> -->
						<th class="text_title header" style="width:100px;">{{$student_classification}}</th>
						</tr>
					</thead>

					<tbody>
					@if(isset($parent_list))
					@foreach ($parent_list as $parent)
					@php
						$parent_id = $parent['id'];
					@endphp
					<tr>
						<td style="width:60px;">
						@if($parent['parent_mailaddress1'])
							<input type="checkbox" class="select1" name="parent_list[{{$parent_id}}][parent_mail1_target]" value="1"
							   @if($request['parent_list'][$parent_id]['parent_mail1_target'])  checked="checked" @endif
							/>
						@endif
						</td>
<!-- 						<td style="width:60px;"> -->
<!-- 						@if($parent.parent_mailaddress2}} -->
<!-- 							<input type="checkbox" class="select2" name="parent_list[{{$parent_id}}][parent_mail2_target]" value="1" -->
<!-- 								@if(($request['parent_list.$parent_id.parent_mail2_target)}}  checked="checked" @endif -->
<!-- 							/> -->
<!-- 						@endif -->
<!-- 						</td> -->
						<td style="width:200px;">{{$parent['parent_name']}}</td>
						<td style="width:60px;">
							@foreach ($parent['students'] as $student)
							@php
								$student_id = $student['id'];
							@endphp
							<input type="checkbox" class="select3" name="student_list[{{$student_id}}][target]" value="1"
								@if($request['student_list'][$student_id]['target'])  checked="checked" @endif
							 /><br/>
							@endforeach
						</td>
						<td style="width:220px;text-align:center;">
							@foreach ($parent['students'] as $student)
								{{$student['student_no']}}<br/>
							@endforeach
						</td>
						<td style="width:200px;">
							@foreach ($parent['students'] as $student)
								{{$student['student_name']}}<br/>
							@endforeach
						</td>
						<td style="width:180px;">
							@foreach ($parent['students'] as $student)
								{{$student['student_name_kana']}}<br/>
							@endforeach
						</td>
<!-- 					<td style="width:180px;"> -->
<!-- 							{{foreach from=$parent.students item=student}} -->
<!-- 								{{$student.school_name}}<br/> -->
<!-- 							@endforeach -->
<!-- 						</td> -->
						<td style="width:100px;text-align:center;">
							@foreach ($parent['students'] as $student)
								{{$student['student_type']}}<br/>
							@endforeach
						</td>
						<input type="hidden" name="enter[]" value="{{$student['enter']}}"/>
					</tr>
					@else
					<tr>
						<td class="t4td2 error_row">{{$no_data_to_show}}</td>
					</tr>
					@endforeach
					@endif
					</tbody>
				</table>
				<br/>
				</div>
				<div id="teacher_table">
				<table class="table_list tablesorter body_scroll_table" id="teachers">
					<thead>
						<tr>
						<th class="text_title" style="width:50px;"><input type="checkbox" id="selectAll4"></th>
						<th class="text_title header" style="width:400px;">{{$teacher_name}}</th>
						</tr>
					</thead>
					<tbody>
					@if(isset($teacher_list))
					@foreach ($teacher_list as $teacher)
					<tr>
						<td style="width:50px;text-align:center;">
							<input type="checkbox" class="select4" name="teacher_ids[]" value="{{$teacher['id']}}"
							 @foreach ($request['teacher_ids'] as $teacher_id)
							   @if($teacher['id'] == $teacher_id)  checked="checked" @endif
							 @endforeach class="teacher_select">
							 </input>
						</td>
						<td style="width:400px;">{{$teacher['coach_name']}}</td>
					</tr>
					@else
					<tr>
						<td class="t4td2 error_row">{{$no_data_to_show}}</td>
					</tr>
					@endforeach
					@endif
					</tbody>
				</table>
				<br/>
				</div>
				<div class="exe_button">
					<!--
					<input type="button" value="戻る" id="btn_return" class="submit3"/>
					 -->
					<input type="button" value="{{$select}}" id="btn_submit" class="submit2" />
				</div>
		</div>
		</form>
@stop