<script type="text/javascript">
$(function() {
	$('#show_preview').click(function() {
		@if(!empty($is_new))
			$("#entry_form").attr("action", "{{$_app_path}}invoice/entry?tab_change");
		@else
			$("#entry_form").attr("action", "{{$_app_path}}invoice/edit?tab_change");
		@endif
		@if(!empty($is_new))
			$("#entry_form").attr("action", "{{$_app_path}}invoice/entry?tab_change");
		@else
			$("#entry_form").attr("action", "{{$_app_path}}invoice/edit?tab_change");
		@endif
		$("[name=active_student_id]").val("confirm");
		$("#entry_form").submit();
		return false;
	});

	$('#show_info').click(function() {
		@if(!empty($is_new))
			$("#entry_form").attr("action", "{{$_app_path}}invoice/entry?tab_change");
		@else
			$("#entry_form").attr("action", "{{$_app_path}}invoice/edit?tab_change");
		@endif
		$("[name=active_student_id]").val("student");
		$("#entry_form").submit();
		return false;
	});

	$("#btn_add_row").click(function() {
		// 行数取得
		var len = $(".adjust_name_table tr").length-1;
		// コピー作成
		var tbl_item = $("#tbl_clone tbody > tr").clone(true).appendTo($(".adjust_name_table > tbody"));
		// 名前を変更
		var student_id = $("input[name=active_student_id]").val();
		tbl_item.find("[name=template_custom_item_id]").attr("id", "adjust" + student_id + "_" + adjust_count);
		tbl_item.find("[name=template_custom_item_price]").attr("id", "_adjust" + student_id + "_" + adjust_count);
		tbl_item.find("[name=template_custom_item_id]").attr("name", "custom_item_id[" + student_id + "][]");
		tbl_item.find("[name=template_custom_item_name]").attr("name", "custom_item_name[" + student_id + "][]");
		tbl_item.find("[name=template_custom_item_price]").attr("name", "custom_item_price[" + student_id + "][]");
		adjust_count++;
		return false;
	});
});
</script>

<style>
.text-right {
text-align: right;
padding-right: 20px;
}
</style>

<div class="content_header">
<div id="section_content_in">
	<div class="billing_top clearfix">
		<div class="w5 float_left">
			<table width="100%">
				<colgroup>
					<col width="28%"/>
					<col width="2%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td>{{$lan::get('guardian_fullname_title')}}</td>
					<td>:</td>
					<td>{{$data['parent_name']}}</td>
				</tr>
				<tr>
					<td>{{$lan::get('member_name_title')}}</td>
					<td>:</td>
					<td>
					@if(isset($data['student_list']))
						@foreach (array_get($data,'student_list') as $student_row)
							{{array_get($student_row,'student_no')}}{{array_get($student_row,'student_name')}}<br/>
						@endforeach
					@endif
					</td>
				</tr>
				<tr>
					<td>{{$lan::get('invoice_year_month_title')}}</td>
					<td>:</td>
					<td>
						@if( $request['active_student_id'] == "confirm")
							@if( isset($request['invoice_year_month'])) 
							{{Carbon\Carbon::parse($request['invoice_year_month'])->format('Y年m月')}}
							@endif
						 	<input type="hidden" name="invoice_year" value="{{$request['invoice_year']}}"/>
						 	<input type="hidden" name="invoice_month" value="{{$request['invoice_month']}}"/>
						@else
							<select name="invoice_year">
								@if(isset($invoice_year_list))
								@foreach($invoice_year_list as $item)
									@if($request['invoice_year'] == $item)
									<option value="{{$item}}" selected="selected">{{$item}}</option>
									@else
									<option value="{{$item}}">{{$item}}</option>
									@endif
								@endforeach
								@endif
								</select>&nbsp;{{$lan::get('year_title')}}
							<select name="invoice_month">
								@if(isset($month_list))
								@foreach($month_list as $item)
									@if($request['invoice_month'] == $item)
									<option value="{{$item}}" selected="selected">{{$item}}</option>
									@else
									<option value="{{$item}}">{{$item}}</option>
									@endif
								@endforeach
								@endif
							</select>&nbsp;{{$lan::get('month_title')}}
						@endif
					</td>
				</tr>
				<tr>
					<td>{{$lan::get('status_title')}}</td>
					<td>:</td>
					<td>
						@if( $request['active_student_id'] == "confirm")
							@if( $request['is_established'] == "1")
							{{$lan::get('confirmed_title')}}
							<input type="hidden" name="is_established" value="1"  />
							@else
							{{$lan::get('editing_title')}}
							<input type="hidden" name="is_established" value="0"  />
							@endif
						@else
							<label>
								<input type="checkbox" name="is_established" value="1" @if( $request['is_established'] == "1") checked @endif />
								{{$lan::get('to_confirm_title')}}
							</label>
						@endif

					</td>
				</tr>
				<tr>
					<td>{{$lan::get('notification_method_title')}}</td>
					<td>:</td>
					<td>
						@if( $request['active_student_id'] == "confirm")
						 	@if( $request['mail_announce'] == "1")
							{{$lan::get('e_mail_title')}}
						 	<input type="hidden" name="mail_announce" value="1" />
						 	@else
							{{$lan::get('mail_title')}}
						 	<input type="hidden" name="mail_announce" value="0" />
						 	@endif
						@else
							<label>
								<input type="checkbox" name="mail_announce" value="1" @if( $request['mail_announce'] == "1") checked @endif />
								{{$lan::get('email_notify_title')}}
							</label>
						@endif
					</td>
				</tr>
				<tr>
					<td>{{$lan::get('payment_method_search_title')}}</td>
					<td>:</td>
					<td>
						@if( isset($invoice_type) and $data['invoice_type']) {{$invoice_type[$data['invoice_type']]}} @endif
					</td>
				</tr>
				<tr>
					<td>{{$lan::get('deadline_payment_title')}}</td>
					<td>:</td>
					<td>
						@if( $request['due_date'])
							{{Carbon\Carbon::parse($request['due_date'])->format('Y年m月d日')}}	
						@endif
					</td>
				</tr>
			</table>

			@if( $request['active_student_id'] != "confirm")
				@foreach ($data['student_list'] as $k => $student_row)
					@if( $k == 0)
					<input type="hidden" name="select_disp" value="{{$student_row['id']}}"/>
					@endif
				@endforeach
				<button id="show_preview" class="mt10">{{$lan::get('preview_title')}}</button>
			@else
				<input type="hidden" name="select_disp" value="confirm"/>
				<button id="show_info" class="mt10">{{$lan::get('back_to_list_title')}}</button>
			@endif

		</div><!--w5 float_left-->

		<div class="w5 float_right">
			<textarea placeholder="{{$lan::get('personal_note_title')}}" class="textarea2" disabled style="margin-bottom:10px;">{{$request['parent_memo']}}</textarea>
			@if( $request['active_student_id'] != "confirm")
			<input type="button" value="{{$lan::get('premium_discount_monthly_title')}}" id="routine_adjust" class="float_right"/>
			@endif
		</div><!--w5 float_right-->
	</div><!--billing_top-->

	@if( $request['active_student_id'] != "confirm")
		<ul style="float:left" id="discount_area">
			@foreach ($request['discount_id'] as $k =>  $v)
				<li style="margin-bottom:5px;list-style-type:none;">
					<span>
						{{$lan::get('discount_confirm_title')}}<select id="discount{{$k}}" name="discount_id[]" class="discount_select" style="width:100px">
							<option value=""></option>
							@foreach ($invoice_adjust_list as $row)
								@if( array_get($row,'type') == 1)
									<option value="{{array_get($row,'id')}}" @if( $v == array_get($row,'id')) selected="selected" @endif>{{array_get($row,'name')}}</option>
								@endif
							@endforeach
						</select>
						<input type="hidden" name="discount_name[]" value="{{$request['discount_name'][$k]}}"/>
					</span>
					<span style="margin-left:15px;">
						{{$lan::get('discount_amount_confirm_title')}}<input id="_discount{{$k}}" style="ime-mode:inactive;  text-align:right;" class="text_s" type="text" name="discount_price[]" value="{{$request['discount_price'][$k]}}"/>
					</span>
					@if( isset($request['errors']['discount_id'][$k]['notEmpty']))
						<ul class="message_area">
							<li class="error_message">{{$lan::get('select_discount_reason_title')}}</li>
						</ul>
					@endif
					@if(isset($request['errors']['discount_name'][$k]['notEmpty']))
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_discount_reason_title')}}</li>
						</ul>
					@endif
					@if( isset($request['errors']['discount_price'][$k]['notEmpty']))
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_discount_amount_title')}}</li>
						</ul>
					@endif
					@if(($request['errors']['discount_price'][$k]['isNumeric']))
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_discount_value_title')}}</li>
						</ul>
					@endif
				</li>
			@endforeach

		</ul>
		<div style="margin-left:10px;float:left;">
			<input type="button" value="{{$lan::get('add_discount_detail_title')}}" id="add_discount_row"/>&nbsp;
		</div>
		<div style="display:none;" id="discount_row_template">
			<ul>
				<li style="margin-bottom:5px;list-style-type:none;">
					<span>
						{{$lan::get('discount_confirm_title')}}<select id="template_discount_id" name="template_discount_id"  class="discount_select" style="width:100px">
							<option value=""></option>
							@foreach($discount_names as $item)
							<option value="{{$item}}">{{$item}}</option>
							@endforeach
						</select>
						<input type="hidden" name="template_discount_name"/>
					</span>
					<span style="margin-left:15px;">
					{{$lan::get('discount_amount_confirm_title')}}<input id="template_discount_price" class="text_s" style="ime-mode:inactive;  text-align:right;" type="text" name="template_discount_price" value=""/>
					</span>
				</li>
			</ul>
		</div>
	@else
		@foreach ($request['discount_name'] as $k => $v)
			<input type="hidden" name="discount_id[]" value="{{$request['discount_id'][$k]}}"/>
			<input type="hidden" name="discount_name[]" value="{{$v}}"/>
			<input type="hidden" name="discount_price[]" value="{{$request['discount_price'][$k]}}"/>
		@endforeach
	@endif

	@if( $request['active_student_id'] != "confirm")
<!-- 	 通常  -->
	<div id="bill_info">
		<table class="table2 adjust_name_table">
				<tr>
					<th class="text_title header" style="width:70%;" colspan="2">{{$lan::get('items_title')}}</th>
					<th class="text_title header" style="width:30%;">{{$lan::get('money_amount_title')}}</th>
				</tr>
				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				 プラン  -->
				@foreach ($request['class_name'][$student_row['id']] as $k => $name)
				@if($name)
				<tr>
					<td style="width:50%;">
						<div class="grayout">
							{{$name}}
						</div>
					</td>
					<td style="width:20%;">
						<div class="grayout">
							<input type="checkbox" name="class_except[{{$student_row['id']}}][]" value="1" 
							@if( isset($request['_class_except'][$student_row['id']][$k]))
								checked
							@endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
						</div>
					</td>
					<td style="width:30%;">
						<div class="grayout text-right">
						
							@if( isset($request['class_price'][$student_row['id']][$k]))
								\{{$request['class_price'][$student_row['id']][$k]}}
							@else
								0
							@endif
						</div>
					</td>
				</tr>
				@endif

					@if( isset($request['errors']['class_id'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('id_not_specify_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['class_name'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_class_name_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['class_price'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_amount_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['class_price'][$student_row['id']][$k]['isNumeric']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_value_amount_title')}}</li>
						</ul>
					</td>
					@endif
				@endforeach
				@endforeach
				@endif
				
				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				 イベント -->
				@if(isset($request['course_name'][$student_row['id']]))
				@foreach ($request['course_name'][$student_row['id']] as $k => $name)
				@if($name)
				<tr>
					<td>
						<div class="grayout">{{$name}}</div>
					</td>
					<td style="width:20%;">
						<div class="grayout">
							<input type="checkbox" name="course_except[{{$student_row['id']}}][]" value="1" 
							@if(isset($request['_course_except'][$student_row['id']][$k]))
								checked
							@endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
						</div>
					</td>
					<td align="center">
						<div class="grayout text-right">
							@if(isset($request['course_price'][$student_row['id']][$k]))
								\{{$request['course_price'][$student_row['id']][$k]}}
							@else
								0
							@endif
						</div>
					</td>
				</tr>
				@endif

					@if( isset($request['errors']['course_id'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('id_not_specify_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['course_name'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_your_name_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['course_price'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_amount_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['course_price'][$student_row['id']][$k]['isNumeric']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_value_amount_title')}}</li>
						</ul>
					</td>
					@endif
				@endforeach
				@endif
				@endforeach
				@endif

				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				プログラム  -->
				@if(isset($request['program_name'][$student_row['id']]))
				@foreach ($request['program_name'][$student_row['id']] as $k =>  $name)
				@if($name)
				<tr>
					<td>
						<div class="grayout">{{$name}}</div>
					</td>
					<td style="width:20%;">
						<div class="grayout">
							<input type="checkbox" name="program_except[{{$student_row['id']}}][]" value="1" 
							@if(isset($request['_program_except'][$student_row['id']][$k]))
								checked
							@endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
						</div>
					</td>
					<td align="center">
						<div class="grayout text-right">
							@if(isset($request['program_price'][$student_row['id']][$k]))
								\{{$request['program_price'][$student_row['id']][$k]}}
							@else
								0
							@endif
						</div>
					</td>
				</tr>
				@endif
					@if( isset($request['errors']['program_id'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('id_not_specify_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['program_name'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_your_name_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['program_price'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_amount_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['program_price'][$student_row['id']][$k]['isNumeric']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_value_amount_title')}}</li>
						</ul>
					</td>
					@endif
				@endforeach
				@endif
				@endforeach
				@endif

							
				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				個別入力  -->
				@if(isset($request['custom_item_id'][$student_row['id']]))
				@foreach ($request['custom_item_id'][$student_row['id']] as $k => $v)
				
				
				@if( $v || $request['custom_item_price'][$student_row['id']][$k])
				
				<tr>
					<td style="width:70%;" colspan="2">
						<div class="grayout">
							<select id="adjust{{$student_row['id']}}_{{$k}}" name="custom_item_id[{{$student_row['id']}}][]" class="adjust_select">
								<option value=""></option>
								
								@if(isset($request['custom_item_name'][$student_row['id']][$k]) && ( is_numeric(!$v) || !$adjust_names[$v] ) )
									<option label="{{$request['custom_item_name'][$student_row['id']][$k]}}" value="{{$v}}" selected="selected"></option>
								@endif
								@if(isset($invoice_adjust_list))
								@foreach ($invoice_adjust_list as $row)
									@if( $row['type'] == 0 )
										<option value="{{$row['id']}}" 
										 @if( $v == $row['id']) selected="selected" @endif>{{$row['name']}}</option>
									@endif
								@endforeach
								@endif
							</select>
							<input type="hidden" name="custom_item_name[{{$student_row['id']}}][]" value="{{$request['custom_item_name'][$student_row['id']][$k]}}"/>
						</div>
					</td>
					<td style="width:30%;">
						<div class="grayout text-right">
							<input id="_adjust{{$student_row['id']}}_{{$k}}" style="ime-mode:inactive; text-align:right;" type="text" name="custom_item_price[{{$student_row['id']}}][]" value="{{$request['custom_item_price'][$student_row['id']][$k]}}"/>
						</div>
					</td>
				</tr>
				@endif

					@if( isset($request['errors']['custom_item_id'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('select_summary_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['custom_item_name'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_summary_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['custom_item_price'][$student_row['id']][$k]['notEmpty']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_amount_title')}}</li>
						</ul>
					</td>
					@endif
					@if( isset($request['errors']['custom_item_price'][$student_row['id']][$k]['isNumeric']))
					<td class="error_row">
						<ul class="message_area">
							<li class="error_message">{{$lan::get('enter_value_amount_title')}}</li>
						</ul>
					</td>
					@endif
				@endforeach
				@endif
				@endforeach
				@endif
		</table>


<!-- btn_add_row template -->
		<div style="display:none;">
			<table id="tbl_clone">
				<tbody>
					<tr>
						<td style="width:70%;" colspan="2">
						<div class="grayout">
							<select id="template_custom_item_id" name="template_custom_item_id" class="adjust_select">
								<option value=""></option>
								@foreach ($invoice_adjust_list as $row )
									@if( array_get($row,'type') == 0)
										<option value="{{array_get($row,'id')}}" >{{array_get($row,'name')}}</option>
									@endif
								@endforeach
							</select>
							<input type="hidden" name="template_custom_item_name"/>
						</div>
						</td>
						<td style="width:30%;">
						<div class="grayout text-right">
							<input id="template_custom_item_price" style="ime-mode:inactive; text-align:right;" type="text" name="template_custom_item_price" value=""/>
						</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="table2_bottom">
			<button class="btn5" id="btn_add_row">{{$lan::get('add_row_title')}}</button>

			<table class="table3">
				<tr>
					<td align="center">
						<p class="text-right">{{$lan::get('subtotal_title')}}</p>
					</td>
					<td>
						<p class="text-right">&yen;<span name="amount"></span></p>
					</td>
				</tr>
				<tr>
					<td align="center">
					@if( $request['amount']['_display_type'] == 0)
						<p class="text-right">{{$lan::get('consumption_taxes_in_title')}}
						@if( $request['sales_tax_disp']) 
							({{$request['sales_tax_disp']}}%)
						@endif</p>
					@else
						<p class="text-right">{{$lan::get('consumption_taxes_out_title')}}
						@if( $request['sales_tax_disp']) 
							({{$request['sales_tax_disp']}}%)
						@endif</p>
					@endif
					</td>
					<td>
						<p class="text-right">&yen;<span name="tax_price"></span></p>
					</td>
				</tr>
				<tr>
					<td align="center">
						<p class="text-right">{{$lan::get('total_title')}}<p>
					</td>
					<td>
						<p class="text-right">&yen;<span name="amount_tax"></span></p>
					</td>
				</tr>
			</table>
			<div class="clr"></div>
		</div>
	</div>

	@else
<!-- 	 プレビュー  -->
	<div id="bill_preview">

<!-- 			 2016/02/24 追加  -->
			@if(isset($request['custom_item_name']))
			@foreach ($request['custom_item_name'] as $student_id => $name_list)
				@if( $request['active_student_id'] != $student_id)
					@foreach ($name_list as $k => $name)
						<input type="hidden" name="custom_item_id[{{$student_id}}][]" value="{{$request['custom_item_id'][$student_id][$k]}}"/>
						<input type="hidden" name="custom_item_name[{{$student_id}}][]" value="{{$name}}"/>
						<input type="hidden" name="custom_item_price[{{$student_id}}][]" value="{{$request['custom_item_price'][$student_id][$k]}}"/>
					@endforeach
				@endif
			@endforeach
			@endif

		<div id="bill_content">
				<div class="bill_header_top">
				
					<p>{{$request['now_date_jp']}}</p>
				</div>
			<div id="bill_header">
				<h1>{{$lan::get('invoice_title')}}</h1>
			</div>
			<section id="bill_info">
				<div class="bill_info_left">
					<p class="company_name">{{$data['parent_name']}} {{$lan::get('mr_title')}}</p>

					<p class="bill_p1">{{$lan::get('pay_with_following_invoice_info_title')}}</p>
					<p class="bill_much">{{$lan::get('your_invoice_amount_title')}}&nbsp;&nbsp;&yen;
					@if( $request['amount']['_tax'])
						{{$request['amount']['_tax']}}
					@else
						<span name="amount_tax" class="bill_much"></span>
					@endif -</p>
					<p class="bill_kigen">
					@if( $request['due_date_jp'])
						{{$lan::get('payment_deadline_title')}}：{{$request['due_date_jp']}}
					@endif</p>
				</div><!---->
				<div class="bill_info_right">
					<p class="my_company_name">@if(isset($data['school_name'])) {{$data['school_name']}} @endif</p>
					<p class="my_company_address">
@if(isset($data['school_address'])) {{$data['school_address']}} @endif
					</p>
					<p class="my_company_daihyou">
					@if(isset($data['school_daihyou'])) {{$data['school_daihyou']}} @endif
					</p>
				</div>
			<div class="clr"></div>
			</section>

			<table id="bill_table">
				<tr>
					<th class="th1">{{$lan::get('content_title')}}</th>
					<th class="th4">{{$lan::get('money_amount_title')}}</th>
				</tr>
				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				プラン  -->
				@if(isset($request['class_name'][$student_row['id']]))
				@foreach ($request['class_name'][$student_row['id']] as  $k => $name)
				@if( $name)
				<tr>
					<td>
						{{$name}}
					</td>
					<td class="td2">
					@if( $request['_class_except'][$student_row['id']][$k])
						{{$lan::get('invocie_outside_title')}}
					@elseif( $request['class_price'][$student_row['id']][$k])
						&yen;{{$request['class_price'][$student_row['id']][$k]}}
					@else
						&yen;0
					@endif
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@endforeach
				@endif

				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				 イベント  -->
				@if(isset($request['course_name'][$student_row['id']]))
				@foreach ($request['course_name'][$student_row['id']] as $k => $name)
				@if( $name)
				<tr>
					<td>
						{{$name}}
					</td>
					<td class="td2">
					@if( $request['_course_except'][$student_row['id']][$k])
						{{$lan::get('invocie_outside_title')}}
					@elseif( $request['course_price'][$student_row['id']][$k])
						&yen;{{$request['course_price'][$student_row['id']][$k]}}
					@else
						&yen;0
					@endif
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@endforeach
				@endif

				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				プログラム -->
				@if(isset($request['program_name'][$student_row['id']]))
				@foreach ($request['program_name'][$student_row['id']] as $k =>  $name)
				@if( $name)
				<tr>
					<td>
						{{$name}}
					</td>
					<td class="td2">
					@if( $request['_program_except'][$student_row['id']][$k])
						{{$lan::get('invocie_outside_title')}}
					@elseif( $request['program_price'][$student_row['id']][$k])
						&yen;{{$request['program_price'][$student_row['id']][$k]}}
					@else
						&yen;0
					@endif
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@endforeach
				@endif


				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
<!-- 				 個別入力 -->
				@if(isset($request['custom_item_id'][$student_row['id']]))
				@foreach ($request['custom_item_id'][$student_row['id']] as $k => $v)
				@if( $v || $request['custom_item_price'][$student_row['id']][$k])
				<tr>
					<td>
					@if( is_numeric($v) and $adjust_names[$v])
						{{$adjust_names[$v]}}&nbsp;
					@else
						{{$request['custom_item_name'][$student_row['id']][$k]}}&nbsp;
					@endif
					</td>
					<td class="td2">
					@if(  is_numeric($request['custom_item_price'][$student_row['id']][$k]))
						@if( $request['custom_item_price'][$student_row['id']][$k] < 0)
							▲ &yen;{{$request['custom_item_price'][$student_row['id']][$k]}}
						@elseif( $request['custom_item_price'][$student_row['id']][$k])
							&yen;{{$request['custom_item_price'][$student_row['id']][$k]}}
						@else
							&yen;0
						@endif
					@else
						&nbsp;
					@endif
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@endforeach
				@endif

				@if(isset($request['discount_id']))
				@foreach ($request['discount_id'] as $k =>$v)
				@if( $v || $request['discount_price'][$k])
				<tr>
					<td>
					@if( is_numeric($v) and $adjust_names[$v])
						{{$adjust_names[$v]}}&nbsp;
					@else
						{{$request['discount_name'][$k]}}&nbsp;
					@endif
					</td>
					<td class="td2">
					@if(is_numeric($request['discount_price'][$k]))
						▲ &yen;{{$request['discount_price'][$k]}}
					@else
						&yen;0
					@endif
					</td>
				</tr>
				@endif
				@endforeach
				@endif
			</table>

			<table id="pay_table2">
				<tr>
					<th>{{$lan::get('subtotal_title')}}</th>
					<td class="td2">
						&yen;@if( $request['amount'])
							{{$request['amount']}}
						@else
							<span name="amount"></span>@endif
					</td>
				</tr>
				<tr>
					<th>{{$lan::get('consumption_taxes_title')}} 
						@if( $request['sales_tax_disp'])
							({{$request['sales_tax_disp']}}%)
						@endif</th>
					<td class="td2">
						&yen;@if( $request['tax_price'])
						{{$request['tax_price']}}
							@else<span name="tax_price"></span>@endif
					</td>
				</tr>
				<tr>
					<th>{{$lan::get('total_title')}}</th>
					<td class="td2">
						&yen;@if( $request['amount']['_tax'])
							{{$request['amount']['_tax']}}
							@else<span name="amount_tax">@endif
					</td>
				</tr>
			</table>
			<div class="clr"></div>
			<p class="pay_p2">{{$lan::get('thanks_title')}}</p>
			@if(isset($request['pbank_info']))
			<div class="bank_account">{{$lan::get('payee_title')}}<br/>{{$request['pbank_info']}}</div>
			@endif
			<br/>
		</div>
	</div>
	@endif
</div>
</div>

