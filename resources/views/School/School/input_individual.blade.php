@extends('_parts.master_layout') 

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<script type="text/javascript">
$(function() {
	$("#btn_return").click(function() {
		$("#action_form").attr('action', '/school/school/');
		$("#action_form").submit();
	});

// 	$("#btn_submit").click(function() {
// 		$("#action_form").attr('action', '/school/school/completeIndiv');
// 		$("#action_form").submit();
// 	});
	$("#btn_submit").click(function() {
// 		$("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
// 		$("#action_form").submit();
// 		return false;
		$( "#dialog_active" ).dialog('open');
		return false;
	});
	
	$( "#dialog_active" ).dialog({
		title: '{{$lan::get('main_title')}}',
		autoOpen: false,
		dialogClass: "no-close",
		resizable: false,
		modal: true,
		buttons: {
			"{{$lan::get('run_title')}}": function() {
				$( this ).dialog( "close" );
// 				$("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
// 		 		$("#action_form").submit();
		 		$("#action_form").attr('action',	'/school/school/completeIndiv');
		 		$("#action_form").submit();

				return false;
			},
			"{{$lan::get('cancel_title')}}": function() {
				$( this ).dialog( "close" );
				return false;
			}
		}
	});
	$("#btn_add_row").click(function() {
		// 行数取得
		var len = $(".student_type_table tr").length-1;
		// コピー作成
		var tbl_item = $("#tbl_clone tbody > tr").clone(true).appendTo($(".student_type_table > tbody"));
		// 名前を変更
		tbl_item.find('input').each(function(){
			name_str = $(this).attr('name');
			name_str = name_str.replace("*",len);
			$(this).attr('name',name_str);
		});
        tbl_item.find('select').each(function(){
            name_str = $(this).attr('name');
            name_str = name_str.replace("*",len);
            $(this).attr('name',name_str);
        });
	});
	$("#btn_add_row1").click(function() {
		exeAjaxUpDown (1);
	});
	$(".upDownBtn").on("click", function(e) {
		e.preventDefault();
		var idx = $(this).attr('name');
		exeAjaxUpDown(0, idx);
		return false;
	});


	$("#card_front").change(function(){
		readURLFront(this);
	});
	$("#card_back").change(function(){
		readURLBack(this);
	});
});
function updownRow(obj) {
	var idx = $(obj).attr('name');
	window.exeAjaxUpDown(0, idx);
	return false;
}

function exeAjaxUpDown(addItem,idx){
	var form  = $('#action_form');
	var url;
	if(addItem == 1){
		var len = $(".student_grade_table tr").length-1;
		console.log(len);
		url= "/school/school/ajaxinputindivobiupdown?addItem=1&idx="+(len-1);
	}else if(addItem == 0){
		url= "/school/school/ajaxinputindivobiupdown?updown=2&idx="+ idx;
	}else{
		url= "/school/school/ajaxinputindivobiupdown?delete=1&idx="+ idx;
	}

	$.ajax({
	   type:'POST',
	   url: url,
	   data: form.serialize(),
	   dataType: 'text',
	   async: false,
	   success: function(data) {
		   $(".student_grade_table tr[name='hasData']").remove();
	     	var array = JSON.parse(data);
	     	var trHTML="";
	     	
	     	for (i in array)
	     	{
	     		trHTML+='<tr name="hasData"><input type="hidden" name="grades['+ i +'][id]" value="'+array[i]["id"]+'" />';
	     		trHTML+='<td><input style="width:100px" type="text" name="grades['+ i +'][grade_color]" value="'+array[i]["grade_color"]+'" class="l_text"/></td>';
	     		trHTML+='<td><input style="width:300px" type="text" name="grades['+ i +'][grade_note]" value="'+array[i]["grade_note"]+'" class="l_text"/></td>';
	     		trHTML+='<td>';
	     		if (i == 0){
	     			trHTML+='<span style="border: none;" onclick="updownRow(this); return false;" name="'+ i +'">↓</span>';
	     		}else if(i == (array.length -1)){
	     			trHTML+='<span onclick="updownRow(this); return false;" name="'+ (i-1) +'">↑</span>';
	     		}else{
	     			trHTML+='<span onclick="updownRow(this); return false;" name="'+ i +'">↓</span>';
	     			trHTML+='<span onclick="updownRow(this); return false;" name="'+ (i-1) +'">↑</span>';
	     		}
				trHTML+='<input style="width:10px" type="hidden"  type="text" name="grades['+i+'][sort_no]" value="'+array[i]["sort_no"]+'" class="l_text"/>';
				trHTML+='</td>';
				// trHTML+='<td style="text-align:center;"><input type="button" value="{{$lan::get('delete_row_title')}}" onclick="removeGradeRow(this,'+i+'); return false;"/></td>';
				trHTML+='<td style="text-align:center;"><button type="button" style="font-size: 11px !important;" onclick="removeGradeRow(this,'+i+'); return false;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button></td>';
	     		trHTML+='</tr>';
	     	}
	     	if(trHTML!='Array'){
	     		$(".student_grade_table").append(trHTML);
	     	}
	   },
	   error:function(xhr, status, error){
			}
 		})
}

/*function readURLFront(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	$('#img_card_front').attr('src', e.target.result);
	    }
	    reader.readAsDataURL(input.files[0]);
	}else{
		$('#img_card_front').attr('src', '/school/img/_card_simple.png');
	}
}

function readURLBack(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	$('#img_card_back').attr('src', e.target.result);
	    }
	    reader.readAsDataURL(input.files[0]);
	}else{
		$('#img_card_back').attr('src', '/school/img/_card_simple.png');
	}
}*/

/**
 * 行を削除
 */
function removeRow(obj, id) {
//	if (confirm('削除してもよろしいですか？')) {
		// 行削除
		$(obj).closest("tr").remove()
		// ＤＢに登録されていない場合、ここまで
		if(id) {
			// 削除リストへ追加
			var ids = $("#action_form input[name='_student_type_remove_ids']").val();
			if(ids) {
				ids += ","+id;
			} else {
				ids = id;
			}
			$("#action_form input[name='_student_type_remove_ids']").val(ids);
		}
//	}
}

/**
 * 行を削除
 */
function removeGradeRow(obj, id) {
	if (id == null) {
		// 行削除
		$(obj).closest("tr").remove()
		// ＤＢに登録されていない場合、ここまで
		if(id) {
			// 削除リストへ追加
			var ids = $("#action_form input[name='_student_grade_remove_ids']").val();
			if(ids) {
				ids += ","+id;
			} else {
				ids = id;
			}
			$("#action_form input[name='_student_grade_remove_ids']").val(ids);
		}
	}else{
		exeAjaxUpDown(3, id);
	}
}
</script>
<style>
	.top_btn li:hover, .div-btn li:hover, input[type="button"]:hover, #btn_add_row:hover,#btn_add_bank:hover  {
		background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
		box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
		cursor: pointer;
		text-shadow: 0 0px #FFF;
	}
	.div-btn li, #btn_add_row, .submit2, #btn_add_bank {
		color: #595959;
		height: 30px;
		border-radius: 5px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		/*font-size: 14px;*/
		font-weight: normal;
		text-shadow: 0 0px #FFF;
	}
	.top_btn li {
		border-radius: 5px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
</style>
<div id="center_content_header" class="box_border1">
	<h2 class="float_left"><i class="fa fa-university"></i> {{$lan::get('main_title')}}</h2>
	<div class="center_content_header_right">
		<div class="top_btn">
            @if($edit_auth)
                <a href="/school/school/additionalcategory"><li style="color: #595959; font-weight: normal;">{{$lan::get('text_additional_category_title')}}</li></a>
                <a href="/school/school/adjustnameinput"><li style="color: #595959; font-weight: normal;">{{$lan::get('discount_manage_title')}}</li></a>
            @endif
		</div>
	</div>
	<div class="clr"></div>
</div>

{{--@include('_parts.topic_list')--}}
<h3 id="content_h3" class="box_border1">{{$lan::get('edit_individual_info_title')}}</h3>

<div id="section_content1">
	{{--@if(isset($request->errors))
		<ul class="message_area">
			@foreach ($request->errors as $idx => $error)
			@if(isset($error['type']['notEmpty']))
			<li class="error_message">{{$lan::get('required_idex_error_title')}}</li>
			@endif
			@if( isset($error['type']['notNumeric']))
			<li class="error_message">{{$lan::get('format_idex_error_title')}}</li>
			@endif
			@if( isset($error['type']['overLength']))
			<li class="error_message">{{$lan::get('length_idex_error_title')}}</li>
			@endif
			@if( isset($error['name']['notEmpty']))
			<li class="error_message">{{$lan::get('required_student_name_error_title')}}</li>
			@endif
			@if( isset($error['name']['overLength']))
			<li class="error_message">{{$lan::get('length_student_name_error_title')}}</li>
			@endif
			@endforeach
			@if(isset($error['type']['duplicateValue']))
			<li class="error_message">{{$lan::get('duplicated_index_error_title')}}</li>@endif
			@if( isset($error['required_fee']['notInput']))
			<li class="error_message">{{$lan::get('required_price_setting_error_title')}}</li>@endif
		</ul>
	@endif --}}
	@if(count($request->errors))
		<ul class="message_area">
			@foreach($request->errors->all() as $error)
				<li class="error_message">{{$lan::get($error)}}</li>
			@endforeach
		</ul>
	@endif
	@if($request->regist_error)
	<ul class="message_area">
		<li class="error_message">{{$lan::get('failed_update_error_title')}}</li>
	</ul>
	@endif 
	@if($request->regist_message)
	<ul class="message_area">
		<li class="info_message">{{$lan::get('update_success_title')}}</li>
	</ul>
	@endif 
	<span class="aster">&lowast;</span>{{$lan::get('mandatory_items_title')}}
	
	<h4>{{$lan::get('student_type_title')}}</h4>

	<form action="#" method="post" id="action_form"
		enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="hidden" name="_student_type_remove_ids"
			value="{{array_get($request, '_student_type_remove_ids')}}" /> <input
			type="hidden" name="isShibu" value="0" />
		<table class="student_type_table" id="table6"
			style="margin-bottom: 10px;">
			<tr>
				<td class="t6_td1" style="width: 100px">{{$lan::get('student_type_code_title')}}<span
					class="aster">*</span></td>
				<td class="t6_td1">{{$lan::get('student_name_title')}}<span class="aster">*</span></td>
				<td class="t6_td1">{{$lan::get('student_category_title')}}</td>
				<td>{{$lan::get('student_type_remark_title')}}</td>
				<td class="t6_td1">{{$lan::get('screen_display_title')}}</td>
				<td class="t6_td1">{{$lan::get('tuition_setting_title')}}</td>
				<td class="t6_td1"></td>
			</tr>
			
			@if(isset($request['_studenttype'])) 
			@foreach($request['_studenttype'] as $idx1 => $studenttype)
			<tr>
				<td>@if(isset($studenttype['used']) && $studenttype['used']==true)
						<input type="hidden" name="_studenttype[{{$idx1}}][used]" value="true">
					@endif
					<input type="hidden" name="_studenttype[{{$idx1}}][id]"
					value="@if( isset($studenttype['id'])) {{$studenttype['id']}} @else null @endif" /> 
					@if( isset($studenttype['used']) && $studenttype['used']==true)
					<input 	style="width: 100px" type="text" value="{{array_get($studenttype, 'code')}}"
					class="l_text" disabled /> 
					<input style="width: 100px" type="hidden" name="_studenttype[{{$idx1}}][code]"
					value="{{array_get($studenttype, 'code')}}" class="l_text" />
					@else <input
					style="width: 100px" type="text"
					name="_studenttype[{{$idx1}}][code]"
					value="{{array_get($studenttype, 'code')}}" class="l_text" />
					@endif</td>
				
				<td><input type="text" name="_studenttype[{{$idx1}}][name]"
					value="{{array_get($studenttype, 'name')}}" class="l_text" />
				</td>
                <td>
                    <select name="_studenttype[{{$idx1}}][student_category]">
                        <option value="1" @if(array_get($studenttype, 'student_category')==1) selected @endif>{{$lan::get('member_personal')}}</option>
                        <option value="2" @if(array_get($studenttype, 'student_category')==2) selected @endif>{{$lan::get('member_corp')}}</option>
                    </select>
                </td>
				<td><input type="text" name="_studenttype[{{$idx1}}][remark]"
						   value="{{array_get($studenttype, 'remark')}}" class="l_text" />
				</td>
				<td>
					<div style="width: 100px">
						@if( isset($studenttype['used']) && $studenttype['used']==true) <label style="font-weight: normal !important;"> <input type="checkbox"
							value="1"
							@if(array_get($studenttype, 'is_display') == '1') checked
							@endif
							disabled />&nbsp; <input type="hidden"
							name="_studenttype[{{$idx1}}][is_display]"
							value="@if(array_get($studenttype, 'is_display') == '1') 1 @else 0 @endif" />&nbsp;{{$lan::get('display_select_title')}}
					</label> @else <label style="font-weight: normal !important;"> <input type="checkbox"
							name="_studenttype[{{$idx1}}][is_display]" value="1"
							@if( array_get($studenttype, 'is_display') == '1')
								checked
							@endif />&nbsp;{{$lan::get('display_select_title')}}
					</label> @endif
					</div>
				</td>
				
				<td>
					<div style="width: 100px">
						@if(isset($studenttype['used']) && $studenttype['used']==true) <label style="font-weight: normal !important;"> <input type="checkbox"
							value="1" @if( array_get($studenttype, 'required_fee') == '1')
								checked
								@endif
							disabled />&nbsp;{{$lan::get('required_tuition_select_title')}} <input
							type="hidden" name="_studenttype[{{$idx1}}][required_fee]"
							value="@if(array_get($studenttype, 'required_fee') == '1') 1 @else 0 @endif" />&nbsp;
					</label> @else <label style="font-weight: normal !important;"> <input type="checkbox"
							name="_studenttype[{{$idx1}}][required_fee]" value="1"
							@if(array_get($studenttype, 'required_fee') == '1') checked
							@endif />&nbsp;{{$lan::get('required_tuition_select_title')}}
					</label> @endif
					</div>
				</td>
				<td style="text-align: center;">
				@if(array_get($studenttype, 'used') && $studenttype['used']==true)
				
				@else 
					<!-- <input
					type="button" value="{{$lan::get('delete_row_title')}}"
					onclick="removeRow(this,@if(array_get($studenttype, 'id')) {{array_get($studenttype, 'id')}} @else null @endif); return false;" /> -->
					<button type="button" style="font-size: 11px !important;" onclick="removeRow(this,@if(array_get($studenttype, 'id')) {{array_get($studenttype, 'id')}} @else null @endif); return false;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
					@endif
				</td>
			</tr>
			@endforeach 
			@endif
		</table>
		<!-- <input type="button" value="{{$lan::get('add_row_title')}}" id="btn_add_row"	style="margin-bottom: 30px;" /> -->
		@if($edit_auth)
		    <button id="btn_add_row" type="button" style="margin-bottom: 30px; font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_row_title')}}</button>
        @endif
	
	<br />
		<div class="exe_button" style="margin-top: 10px;">
			<!-- <input type="button" value="{{$lan::get('confirm_title')}}" id="btn_submit" class="submit2" /> -->
			<button id="btn_submit" class="submit2" type="button"><i class="glyphicon glyphicon-floppy-disk " style="width: 20%;font-size:16px;"></i>登録</button> &nbsp; 
			<!-- <input type="button" value="{{$lan::get('return_title')}}" id="btn_return" class="submit3"/> -->
			<button id="btn_return" class="submit3" type="button" style="text-shadow: 0 0px #FFF;font-weight: normal !important;"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
			
		</div>
 	</form> 

<!-- 	 帯の色コーピ元 -->
	
	<div style="display: none;">
		<!-- テーブルコピー元ねた -->
		<table id="tbl_clone">
			<tbody>
				<tr>
					<td><input style="width: 100px" type="text"
						name="_studenttype[*][code]" value="" class="l_text" /></td>
					<td><input type="text" name="_studenttype[*][name]" value=""
						class="l_text" /></td>
                    <td>
                        <select name="_studenttype[*][student_category]">
                            <option value="1">{{$lan::get('member_personal')}}</option>
                            <option value="2">{{$lan::get('member_corp')}}</option>
                        </select>
                    </td>
					<td><input type="text" name="_studenttype[*][remark]" value=""
							   class="l_text" /></td>
					<td><label> <input type="checkbox"
							name="_studenttype[*][is_display]" value="1" checked />&nbsp;{{$lan::get('display_select_title')}}
					</label></td>
					<td><label> <input type="checkbox"
							name="_studenttype[*][required_fee]" value="1" />&nbsp;{{$lan::get('required_tuition_select_title')}}
					</label></td>
					<td style="text-align: center;">
					<!-- <input type="button" 	
						value="{{$lan::get('delete_row_title')}}"
						onclick="removeRow(this,null); return false;" /> -->
						<button id="btn_add_bank" type="button" style="font-size: 11px !important;" onclick="removeRow(this,null); return false;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button></td>
				</tr>
			</tbody>
		</table>
		</div>
<div id="dialog_active" class="no_title" style="display:none;">
	{{$lan::get('confirm_content')}}
</div>
		@stop