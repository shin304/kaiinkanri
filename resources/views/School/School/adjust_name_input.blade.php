@extends('_parts.master_layout')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<script type="text/javascript">
$(function() {

	$("#btn_return").click(function() {
		$("#action_form").attr('action', '/school/school/inputindiv');
		$("#action_form").submit();
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
		 		$("#action_form").attr('action','/school/school/adjustnameComplete');
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
		var len = $(".adjust_name_table tr").length-1;
		// コピー作成
		var tbl_item = $("#tbl_clone tbody > tr").clone(true).appendTo($(".adjust_name_table > tbody"));
		// 名前を変更
		tbl_item.find('input').each(function(){
			name_str = $(this).attr('name');
			name_str = name_str.replace("*",len);
			$(this).attr('name',name_str);
		});
		
		return false;
	});

	$(document).on("keyup","._adjust_display",function(){

	    //get value display and format
	    var num = $(this).val();
	    num = num.replace(/\,/g,'');

	    var order = $(this).data('order');
	    //change hidden

        $("#_adjust_real_"+order).val(num);
	})
});
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
			var ids = $("#action_form input[name='_adjust_name_remove_ids']").val();
			if(ids) {
				ids += ","+id;
			} else {
				ids = id;
			}
			$("#action_form input[name='_adjust_name_remove_ids']").val(ids);
		}
		
		return false;
//	}
}
</script>
<style>
	.top_btn li:hover, .div-btn li:hover, input[type="button"]:hover, #btn_add_row:hover,.submit2:hover, button[type="button"]:hover  {
		background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
		box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
		cursor: pointer;
		text-shadow: 0 0px #FFF;
	}
	.div-btn li, #btn_add_row, .submit2 {
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
	}
	button[type="button"] {
		height: 30px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
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

	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('adjustnameinput') !!}</div> --}}
	{{--@include('_parts.topic_list')--}}
	<div id="section_content">
		<h3 id="content_h3" style="font-weight: bold !important;" class="box_border1">{{$lan::get('discount_edit_title')}}</h3>
		<div id="section_content_in">
			@if(count($errors))
			<ul class="message_area">
						@foreach($errors->all() as $error)  
				<li class="error_message">{{$lan::get($error)}}</li>
				@endforeach
			</ul>
			@endif

			<span class="aster">&lowast;</span>{{$lan::get('mandatory_items_title')}} 割引項目の場合は、マイナス数値を設定してください。

			<form action="#" method="post" id="action_form">
			{{ csrf_field() }}
			<input type="hidden" name="_adjust_name_remove_ids" value="{{$request['_adjust_name_remove_ids']}}" />
			<table class="adjust_name_table" id="table6" style="margin-bottom:10px;">
				<tr>
					<td class="t6_td1" >{{$lan::get('discount_name_title')}}<span class="aster">*</span></td>
					<td class="t6_td1" >{{$lan::get('code_title')}}<span class="aster">*</span></td>
					<td class="t6_td1" >{{$lan::get('amount_money_title')}}<span class="aster">*</span></td>
					{{--<td class="t6_td1" style="text-align:center;">{{$lan::get('discount_in_table_title')}}</td>--}}
					<td class="t6_td1" style="text-align:center;">{{$lan::get('availability_in_table_title')}}</td>
					<td class="t6_td1" style="text-align:center;">{{$lan::get('display_order')}}</td>
					<td class="t6_td1" style="text-align:center;">{{$lan::get('operation')}}</td>
				</tr>

				@if(isset($request['_adjust']))
				@foreach ($request['_adjust'] as $idx => $row)
				<tr>
					<td>
						<input type="hidden" name="_adjust[{{$idx}}][id]" value="{{array_get($row, 'id')}}"/>
						<input type="hidden" name="_adjust[{{$idx}}][used]" value="{{array_get($row, 'used')}}"/>
						@if( isset($row['used']))
							<input type="text"   value="{{$row['name']}}" class="l_text" disabled />
							<input type="hidden" name="_adjust[{{$idx}}][name]" value="{{array_get($row, 'name')}}"  />
						@else
							<input type="text" name="_adjust[{{$idx}}][name]" value="{{array_get($row, 'name')}}" class="l_text"  />
						@endif
					</td>
					<td>
						@if( isset($row['used']))
							<input type="text"   value="{{$row['code']}}" class="l_text" disabled />
							<input type="hidden" name="_adjust[{{$idx}}][code]" value="{{array_get($row, 'code')}}"  />
						@else
							<input type="text" name="_adjust[{{$idx}}][code]" value="{{array_get($row, 'code')}}" class="l_text"/>
						@endif
					</td>
					<td>
						@if( $country_code == 81)
							@if( isset($row['used']))
								<input type="text"  style="text-align:right;" value="{{number_format(array_get($row, 'initial_fee'))}}" class="l_text"  disabled />
								<input type="hidden" name="_adjust[{{$idx}}][initial_fee]" value="{{floor(array_get($row, 'initial_fee'))}}"  />
							@else
								<input type="text"  style="text-align:right;" class="_adjust_display" data-order="{{$idx}}" value="@if(is_numeric(array_get($row, 'initial_fee'))){{number_format(array_get($row, 'initial_fee'))}}@else {{array_get($row, 'initial_fee')}} @endif" class=" l_text"
                                       pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
								<input type="hidden" style="text-align:right;" id ="_adjust_real_{{$idx}}" name="_adjust[{{$idx}}][initial_fee]" value="{{floor(array_get($row, 'initial_fee'))}}"  />
							@endif
						@else
							@if( isset($row['used']))
								<input type="text" style="text-align:right;" name="_adjust[{{$idx}}][initial_fee]" value="{{array_get($row, 'initial_fee')}}"   class="l_text" disabled />
								<input type="hidden" name="_adjust[{{$idx}}][initial_fee]" value="{{floor(array_get($row, 'initial_fee'))}}" />
							@else
								<input type="text"  style="text-align:right;" class="_adjust_display" data-order="{{$idx}}" value="@if(is_numeric(array_get($row, 'initial_fee'))){{number_format(array_get($row, 'initial_fee'))}}@else array_get($row, 'initial_fee') @endif" class=" l_text"
                                       pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>/>
								<input type="hidden" style="text-align:right;" id ="_adjust_real_{{$idx}}" name="_adjust[{{$idx}}][initial_fee]" value="{{floor(array_get($row, 'initial_fee'))}}"  />
							@endif
						@endif
					</td>
					
					{{--<td style="text-align:center;">--}}
						{{--@if( isset($row['used']))--}}
							{{--<input type="checkbox" value="1" @if( isset($row['type']) && array_get($row, 'type') == 1) checked @endif disabled />&nbsp;--}}
							{{--<input type="hidden" name="_adjust[{{$idx}}][type]" value="@if( array_get($row, 'type') == 1) 1 @else 0 @endif" />--}}
						{{--@else--}}
							{{--<input type="checkbox" name="_adjust[{{$idx}}][type]" value="1" @if(isset($row['type']) && array_get($row, 'type') == 1) checked @endif />&nbsp;--}}
						{{--@endif--}}
					{{--</td>--}}
					<td style="text-align:center;">
						@if( isset($row['used']))
							<input type="checkbox"  value="1" @if(array_get($row, 'active_flag')) checked @endif disabled />&nbsp;
							<input type="hidden" name="_adjust[{{$idx}}][active_flag]" value="@if( array_get($row, 'active_flag')) 1 @else 0 @endif" />
						@else
							<input type="checkbox" name="_adjust[{{$idx}}][active_flag]" value="1" @if(array_get($row, 'active_flag')) checked @endif />&nbsp;
						@endif
					</td>

					<td style="width:10%; text-align: center">
						@if(array_get($request, '_adjust'))
							@if( $idx == 0)
								<a class="text_link" href="/school/school/adjustnameupdown?updown=2&idx={{$idx}}">↓</a>
							@elseif( $idx == (count(array_get($request, '_adjust'))-1))
								<a class="text_link" href="/school/school/adjustnameupdown?updown=2&idx={{$idx-1}}">↑</a>
							@else
								<a class="text_link" href="/school/school/adjustnameupdown?updown=2&idx={{$idx}}">↓</a>
								<a class="text_link" href="/school/school/adjustnameupdown?updown=2&idx={{$idx-1}}">↑</a>
							@endif
						@endif
					</td>
					

					<td style="text-align:center;">
						@if(isset($row['used']))
						　
						@else
							<!-- <input type="button" value="{{$lan::get('delete_row_title')}}" onclick="removeRow(this,@if( array_get($row, 'id')) {{array_get($row, 'id')}} @else null @endif); return false;"/> -->
							<button type="button" style="font-size: 11px !important;" onclick="removeRow(this,@if( array_get($row, 'id')) {{array_get($row, 'id')}} @else null @endif); return false;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
						@endif
					</td>
				</tr>
				@endforeach
				@endif
			</table>

            @if($edit_auth)
                <button id="btn_add_row" type="button" style="margin-bottom: 30px; font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_row_title')}}</button>
			@endif

			</form>
			
			<div style="display:none;"><!-- テーブルコピー元ねた -->
				<table id="tbl_clone">
					<tbody>
						<tr>
							<td>
								<input type="hidden" name="_adjust[*][id]" value=""/>
								<input type="text" name="_adjust[*][name]" value="" class="l_text"/>
							</td>
							<td>
								<input type="text" name="_adjust[*][code]" value="" class="l_text"/>
							</td>
							<td>
								<input type="text" name="_adjust[*][initial_fee]" value="" class="l_text"/>
							</td>
							{{--<td style="text-align:center;">--}}
								{{--<input type="checkbox" name="_adjust[*][type]" value="1"/>&nbsp;--}}
							{{--</td>--}}
							<td style="text-align:center;">
								<input type="checkbox" name="_adjust[*][active_flag]" value="1" checked="checked"/>&nbsp;
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
				    <button id="btn_submit" class="submit2" type="button"><i class="glyphicon glyphicon-floppy-disk " style="width: 20%;font-size:16px;"></i>登録</button> &nbsp;
				@endif

				<button id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
				
			</div>

		</div><!--section_content_in-->
	</div><!--section_content-->
<div id="dialog_active" class="no_title" style="display:none;">
	{{$lan::get('confirm_content')}}
</div>
@stop