@extends('_parts.master_layout')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<script type="text/javascript">
$(function() {

	$("#submit_return").click(function() {
		$("#frm_return").submit();
	});


	$("#btn_submit").click(function() {
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
		 		$("#action_form").attr('action','/school/school/additionalCategoryComplete');
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

		var len = $(".additional_category_tbl tr").length-1;

		var tbl_item = $("#tbl_clone tbody > tr").clone(true).appendTo($(".additional_category_tbl > tbody"));

		tbl_item.find('input').each(function(){
		    if($(this).attr('name')!= null && $(this).attr('name')!= undefined){
                name_str = $(this).attr('name');
                name_str = name_str.replace("*",len);
                $(this).attr('name',name_str);
			}
		});
        tbl_item.find('select').each(function(){
            if($(this).attr('name')!= null && $(this).attr('name')!= undefined){
                name_str = $(this).attr('name');
                name_str = name_str.replace("*",len);
                $(this).attr('name',name_str);
            }
        });
		return false;
	});

	$(".swap").click(function(){
	    var curr_id = $(this).closest('tr').data('sort_id');
	    if($(this).hasClass('swap_up')){
			var swap_id = $(this).closest('tr').prev().data('sort_id');
		}else if($(this).hasClass('swap_down')){
            var swap_id = $(this).closest('tr').next().data('sort_id');
		}
		//
		var this_element= $(this);
		// ajax swap server
        $.ajax({
            type:"get",
            dataType:"json",
            url: "/school/ajaxSchool/swapCategories",
            data: {curr_id: curr_id,swap_id:swap_id},
            contentType: "application/x-www-form-urlencoded",
            success: function(data) {
               if(data=="success"){
                   //js swap client
                   var curr_tr = this_element.closest('tr');
                   if(this_element.hasClass('swap_up')){
                       // swap row
                       var swap_tr = curr_tr.prev();
                       curr_tr.after(swap_tr);

                       //swap block
                       var block = this_element.closest('td');
                       var swap_block = swap_tr.find('td:eq(4)');
                       swap_block.before(block);
                       var back_block = curr_tr.find('td:eq(3)');
                       back_block.after(swap_block);

                       //
                       var curr_sort_name = block.find('input:hidden').attr('name');
                       var swap_sort_name = swap_block.find('input:hidden').attr('name');
                       block.find('input:hidden').attr('name',swap_sort_name);
                       swap_block.find('input:hidden').attr('name',curr_sort_name);

                   }else if(this_element.hasClass('swap_down')){
                       // swap row
                       var swap_tr = curr_tr.next();
                       curr_tr.before(swap_tr);

                       //swap block
                       var block = this_element.closest('td');
                       var swap_block = swap_tr.find('td:eq(4)');
                       swap_block.before(block);
                       var back_block = curr_tr.find('td:eq(3)');
                       back_block.after(swap_block);

                       //
                       var curr_sort_name = block.find('input:hidden').attr('name');
                       var swap_sort_name = swap_block.find('input:hidden').attr('name');
                       block.find('input:hidden').attr('name',swap_sort_name);
                       swap_block.find('input:hidden').attr('name',curr_sort_name);
                   }
			   }else{
				   alert('error',function () {
					   location.reload();
				   });
			   }
            },
            error: function(data) {alert('error');
                location.reload();
            }
        });
	})

});

function removeRow(obj, id) {

		$(obj).closest("tr").remove();

		if(id) {
			// 削除リストへ追加
			var ids = $("#action_form input[name='_additional_cate_remove_ids']").val();
			if(ids) {
				ids += ","+id;
			} else {
				ids = id;
			}
			$("#action_form input[name='_additional_cate_remove_ids']").val(ids);
		}
		var length_tr = $(".additional_category_tbl").children().children().length;
 		var flag = 0;
		$("table .additional_category_tbl tr").each(function(){
		    if(flag==1){
		        element=$(this).find('td:eq(4)').find('.swap_up')
				if(element!=null && element!=undefined){
					element.remove();
		    	}
			}
            if(flag==length_tr-1){
                element=$(this).find('td:eq(4)').find('.swap_down')
                if(element!=null && element!=undefined){
                    element.remove();
                }
            }
            flag+=1;
		})
		return false;

}
</script>
<style>
	.swap{
		color: #0044CC !important;
		text-decoration: underline;
	}
	.swap:hover{
		cursor: pointer;
	}
	.top_btn li:hover, .div-btn li:hover, input[type="button"]:hover, #btn_add_row:hover,.submit2:hover, button[type="button"]:hover  {
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
	button[type="button"] {
		height: 30px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
</style>
	<div id="center_content_header" class="box_border1">
		<h2 class="float_left"><i class="fa fa-university"></i> {{$lan::get('main_title')}}</h2>
		<div class="center_content_header_right">
			<div class="top_btn">
			</div>
		</div>
		<div class="clr"></div>
	</div>

	<div id="section_content">
		<h3 id="content_h3" style="font-weight: bold !important;" class="box_border1">{{$lan::get('text_additional_category_title')}}</h3>
		<div id="section_content_in">

			@if(count(request('errors')))
				<ul class="message_area">
					@foreach(request('errors')->all() as $error)
						<li class="error_message">{{$lan::get($error)}}</li>
					@endforeach
				</ul>
			@endif

			<span class="aster">&lowast;</span>{{$lan::get('mandatory_items_title')}}

			<form action="#" method="post" id="action_form">
			{{ csrf_field() }}
			<input type="hidden" name="_additional_cate_remove_ids" value="@if(count($errors)){{request('_additional_cate_remove_ids')}}@endif" />
			<table class="additional_category_tbl" id="table6" style="margin-bottom:10px;">
				<tr>
					<td class="t6_td1" >{{$lan::get('screen_type_select_box_display')}}<span class="aster">*</span></td>
					<td class="t6_td1" >{{$lan::get('student_type_code_title')}}<span class="aster">*</span></td>
					<td class="t6_td1" >{{$lan::get('student_name_title')}}<span class="aster">*</span></td>
					<td class="t6_td1" style="text-align:center;">{{$lan::get('availability_in_table_title')}}</td>
					<td class="t6_td1" style="text-align:center;">{{$lan::get('display_order')}}</td>
					<td class="t6_td1" style="text-align:center;">{{$lan::get('operation')}}</td>
				</tr>
				@if(request()->has('list_category'))
				@foreach (request('list_category') as $idx => $row)
				<tr @if(isset($row['id']))data-sort_id="{{$row['id']}}@endif">
					<td>
						<input type="hidden" @if(isset($row['id']))name="list_category[{{$idx}}][id]" value="{{$row['id']}}@endif"/>
						<select name="list_category[{{$idx}}][type]">
							<option value="1" @if($row['type']==1) selected @endif>{{$lan::get('option_student_select')}}</option>
							{{--<option value="2" @if($row['type']==2) selected @endif>{{$lan::get('option_class_select')}}</option>
							<option value="3" @if($row['type']==3) selected @endif>{{$lan::get('option_event_select')}}</option>
							<option value="4" @if($row['type']==4) selected @endif>{{$lan::get('option_program_select')}}</option>--}}
							<option value="5" @if($row['type']==5) selected @endif>{{$lan::get('option_parent_select')}}</option>
							<option value="6" @if($row['type']==6) selected @endif>{{$lan::get('option_teacher_select')}}</option>
						</select>
					</td>
					<td>
						<input type="text" name="list_category[{{$idx}}][code]" value="{{array_get($row, 'code')}}" class="l_text"/>
					</td>
					<td>
						<input type="text" name="list_category[{{$idx}}][name]" value="{{array_get($row, 'name')}}" class="l_text"/>
					</td>
					<td class="temp_sort" style="text-align:center;">
							<input type="checkbox" name="list_category[{{$idx}}][active_flag]" value="1" @if( isset($row['active_flag']) && array_get($row, 'active_flag') == 1) checked @endif />&nbsp;
					</td>
					<td style="width:10%;text-align:center;">
						@if(request()->has('list_category'))
							@if(count(request('list_category'))!=1 && isset($row['sort_no']))
								@if( $idx == 0)
									<a class="swap swap_down">↓</a>
									<input type="hidden" name="list_category[{{$idx}}][sort_no]" value="{{$row['sort_no']}}">
								@elseif( $idx == request('count_current_items')-1)
									<a class="swap swap_up">↑</a>
									<input type="hidden" name="list_category[{{$idx}}][sort_no]" value="{{$row['sort_no']}}">
								@else
									<a class="swap swap_down">↓</a>
									<a class="swap swap_up">↑</a>
									<input type="hidden" name="list_category[{{$idx}}][sort_no]" value="{{$row['sort_no']}}">
								@endif
							@endif
						@endif
					</td>

					<td style="text-align:center;">
							<!-- <input type="button" value="{{$lan::get('delete_row_title')}}" onclick="removeRow(this,@if( array_get($row, 'id')) {{array_get($row, 'id')}} @else null @endif); return false;"/> -->
							<button type="button" style="font-size: 11px !important;" onclick="removeRow(this,@if( array_get($row, 'id')) {{array_get($row, 'id')}} @else null @endif); return false;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
					</td>
				</tr>
				@endforeach
				@endif
			</table>

			<!-- <input type="button" value="{{$lan::get('add_row_title')}}" id="btn_add_row"  style="margin-bottom:30px;"/> -->
            @if($edit_auth)
				<button id="btn_add_row" type="button" style="margin-bottom: 30px; font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_row_title')}}</button>
			@endif

			</form>
			
			<div style="display:none;"><!-- テーブルコピー元ねた -->
				<table id="tbl_clone">
					<tbody>
						<tr>
							<td>
								<select name="list_category[*][type]">
									<option value="1" >{{$lan::get('option_student_select')}}</option>
									<option value="5" >{{$lan::get('option_parent_select')}}</option>
									<option value="6" >{{$lan::get('option_teacher_select')}}</option>
								</select>
							</td>
							<td>
								<input type="text" name="list_category[*][code]" value="" class="l_text"/>
							</td>
							<td>
								<input type="text" name="list_category[*][name]" value="" class="l_text"/>
							</td>
							<td style="text-align:center;">
								<input type="checkbox" name="list_category[*][active_flag]" value="1" checked="checked"/>&nbsp;
							</td>
							<td></td>
							<td style="text-align:center;">
								<!-- <input type="button" value="{{$lan::get('delete_row_title')}}" onclick="removeRow(this,null); return false;"/> -->
								<button type="button" style="font-size: 11px !important;" onclick="removeRow(this,null); return false;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="exe_button" style="margin-top:10px;">
                @if($edit_auth)
                    <!-- <input type="submit" value="{{$lan::get('confirm_title')}}" id="btn_submit" class="submit2"/> -->
                    <button id="btn_submit" class="submit2" type="button"><i class="glyphicon glyphicon-floppy-disk " style="width: 20%;font-size:16px;"></i>登録</button> &nbsp;
				@endif
                <!-- <input class="submit2" id="submit_return" type="submit" value="{{$lan::get('return_title')}}"/> -->
				<button id="submit_return" class="submit2" type="button"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
			</div>
		</div><!--section_content_in-->
	</div><!--section_content-->
<div id="dialog_active" class="no_title" style="display:none;">
	{{$lan::get('confirm_content')}}
</div>
<form id="frm_return" action="/school/school" method="post" style="display:none">
	{{ csrf_field() }}
</form>
@stop