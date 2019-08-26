@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
	$("#btn_return").click(function() {
		@if( isset($request['id']))
			$("#action_form").attr('action', '{{$_app_path}}broadcastmail/detail');
		@else
			$("#action_form").attr('action', '{{$_app_path}}broadcastmail');
		@endif
		$("#action_form").submit();
		return false;
	});
	$( "#dialog-confirm" ).dialog({
		title:"{{$lan::get('mail_send_confirm')}}",  
		autoOpen: false,
		dialogClass: "no-close",
		resizable: false,
		modal: true,
		buttons: {
			"{{$lan::get('confirm')}}": function() {
				$( this ).dialog( "close" );
				$("#action_form").attr('action', '{{$_app_path}}broadcastmail/completeSend/id={{$request['id']}}&send_flag={{$request['send_flag']}}');
				$("#action_form").submit();
				return false;
			},
			"{{$lan::get('close')}}": function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$("#btn_submit").click(function() {
		$( "#dialog-confirm" ).dialog('open');
		return false;
	});
	$("#select_tos_btn").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}broadcastmail/select');
		$("#action_form").submit();
		return false;
	});
	$("#btn_save").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}broadcastmail/save?id={{$request['id']}}');
		$("#action_form").submit();
		return false;
	});
	$(":text[name='title']").keyup(function() {
		var title = $(":text[name='title']").val();
		var content = $("textarea[name='content']").val();
		if( content && title){
			$(".exe_button").show();
		}
		else {
			$(".exe_button").hide();
		}
		return false;
	});
	$("textarea[name='content']").keyup(function() {
		var title = $(":text[name='title']").val();
		var content = $("textarea[name='content']").val();
		if( title && content ){
			$(".exe_button").show();
		}
		else {
			$(".exe_button").hide();
		}
		return false;
	});
	 $("#students").tablesorter( {
	        headers: {
	            0: { sorter: false },
	            1: { sorter: false },
	            3: { sorter: false }
	        }
	    } );
	    $("#teachers").tablesorter();
});

</script>

<script type="text/javascript">
function nextForm(event)
{
	if (event.keyCode == 0x0d)
	{
		var current = document.activeElement;

		var forcus = 0;
		for( var idx = 0; idx < document.action_form.elements.length; idx++){
			if( document.action_form[idx] == current ){
				forcus = idx;
				break;
			}
		}
		document.action_form[(forcus + 1)].focus();
	}
}
window.document.onkeydown = nextForm;
</script>
		<div id="center_content_header" class="box_border1">
			<h2 class="float_left"><i class="fa fa-envelope-o"></i>{{$lan::get('broadcast_mail_main')}}</h2>
			<div class="center_content_header_right">
				<div class="top_btn">
				</div>
			</div>
			<div class="clr"></div>
		</div><!--center_content_header-->
		{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('parent') !!}</div>  --}}
	@include('_parts.topic_list')

		<h3 id="content_h3" class="box_border1">{{$lan::get('detail')}}
		@if( isset($request['id']))
			{{$lan::get('edit')}}
		@else
			{{$lan::get('register')}}
		@endif</h3>

		@if($request->errors)
		<ul class="message_area">
			@if( $request->errors['title']['notEmpty']))<li class="error_message">{{$lan::get('msg_title_compulsory')}}</li>@endif
			@if( $request->errors['title']['overLength']))<li class="error_message">{{$lan::get('msg_title_invalid')}}</li>@endif
			@if( $request->errors['content']['notEmpty']))<li class="error_message">{{$lan::get('msg_contents_compulsory')}}</li>@endif
			@if( $request->errors['content']['overLength']))<li class="error_message">{{$lan::get('msg_contents_invalid')}}</li>@endif
		</ul>
		@endif

		<div id="section_content1">
			<form id="action_form" method="post" name="action_form">
			@include('_parts.student.hidden')
				<input type="hidden" name="id" value="{{$request['id']}}"/>
				<input type="hidden" name="send_flag" value="{{$request['send_flag']}}"/>
				@if( isset($request['student_ids']))
					@foreach ($request['student_ids'] as $student_id)
						<input type="hidden" name="student_ids[]" value="{{$student_id}}"/>
					@endforeach
				@endif
				@if( isset($request['bc_option']))
					<input type="hidden" name="bc_option" value="{{$request['bc_option']}}"/>
				@else
					<input type="hidden" name="bc_option" value="1"/>
				@endif

				<table id="table6">
					<colgroup>
						<col width="30%"/>
						<col width="70%"/>
					</colgroup>
					<tr>
						<td  class="t6_td1">{{$lan::get('title')}}<span class="aster">&lowast;</span></td>
						<td>
							<input style="ime-mode:active;" type="text" name="title" value="{{$request['title']}}"/>
						</td>
					</tr>
					<tr>
						<td  class="t6_td1">{{$lan::get('contents')}}<span class="aster">&lowast;</span></td>
						<td>
						<textarea style="ime-mode:active;" name="content"  cols="40" rows="6"  wrap="hard" class="description_textarea">{{$request['content']}}</textarea>
						</td>
					</tr>
					<tr>
						<td  class="t6_td1">{{$lan::get('memo')}}</td>
						<td>
						<textarea style="ime-mode:active;" name="memo"  cols="40" rows="6"  wrap="hard" class="description_textarea">{{$request['memo']}}</textarea>
						</td>
					</tr>
				</table>
			
		
			@if( isset($parent_arr))
			<table class="table_list tablesorter body_scroll_table" id="students">
				<thead>
				<tr>
					<th class="text_title" style="width:60px;"></th>
					<th class="text_title header" style="width:290px;"> {{$lan::get('parent_name')}} </th>
					<th class="text_title" style="width:60px;"></th>
					<th class="text_title header" style="width:220px;"> {{$lan::get('student_no')}} </th>
					<th class="text_title header" style="width:260px;">{{$lan::get('student_name')}}</th>
					<th class="text_title header" style="width:130px;">{{$lan::get('student_classification')}}</th>
					<th class="text_title header" style="width:130px;">{{$lan::get('sent_datetime')}}</th>
				</tr>
				</thead>
				@if( $request['parent_list'] || $request['student_list'])								
				@foreach ($parent_arr as $parent)
				@if( $request['parent_list'] && (array_key_exists($parent['id'], $request['parent_list'])) || !$parent['id'])
				<tr>
					<input type="hidden" name="parents[]" value=""/>
					<td style="width:60px">
					<input type="checkbox" 
					@php
						$parent_id = $parent['id'];
					@endphp
					@if( isset($request['parent_list'][$parent_id]['parent_mail1_target'])) checked @endif/>
					</td>
					<td style="width:290px;">
						{{$parent['parent_name']}}
					</td>
					<td style="width:60px;">
						@foreach ($parent['students'] as $student)
							@php
								$student_id = $student['id'];
							@endphp
							<input type="checkbox" 
							@if( isset($request['student_list'][$student_id]['target'])) checked @endif/>
							<br/>
						@endforeach
					</td>
					<td style="width:220px;">
						@foreach ($parent['students'] as $student)
							{{$student['student_no']}}<br/>
						@endforeach
					</td>
					<td style="width:260px;">
						@foreach ($parent['students'] as $student)
							{{$student['student_name']}}<br/>
						@endforeach
					</td>
						<td style="width:130px;">
						@foreach ($parent['students'] as $student)
						{{$student['student_type']}}
						@endforeach
						</td>
					<td style="width:130px;">
						
						</td>
				</tr>
				@endif
				@endforeach
				@else
				<tr>
					<td class="error_row">{{$lan::get('no_data_to_show')}}{{$lan::get('student_name')}}</td>
				</tr>
				@endif
			</table>
			@endif
			
			<table class="table_list tablesorter body_scroll_table" id="teachers">
				<thead>
				<tr>
					<th class="text_title header" style="width:60px;"></th>
					<th class="text_title header" style="width:880px;">{{$lan::get('teacher_name')}} </th>
				</tr>
				</thead>
				@if( isset($request['teacher_ids']))
				@foreach ($teacher_list as $teacher)
				@if( array_search($teacher['id'], $request['teacher_ids']) == true )
				<tr>
					<td style="width:60px"><input type="checkbox" disabled="disabled"/></td>
					<td style="width:880px;">
						{{$teacher['coach_name']}}
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@if(!$request['teacher_ids'])
				<tr>
					<td class="error_row">{{$lan::get('no_data_to_show')}}</td>
				</tr>
				@endif
			</table>
				<div class="exe_button" @if( !$request['title'] || !$request['content']) style="display: none;" @endif>
					<input type="button" class="submit3" value="{{$lan::get('save_draft')}}" id="btn_save"/>
					@if( $request['parent_list'] || $request['teacher_ids'])
					<input  type="button"  class="submit2" name="regist_button" id="btn_submit" value="{{$lan::get('send')}}"/>
					@endif
				</div>
			</form>
		</div>

@stop

<div id="dialog-confirm"  style="display: none;">
{{$lan::get('mail_will_be_send_confirm')}}
</div>

