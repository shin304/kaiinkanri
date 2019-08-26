<script type="text/javascript">
$(function() {
	$('#show_preview').click(function() {
		$('#bill_info').hide();
		$('#bill_preview').show();

		$('#show_preview').hide();
		$('#show_info').show();
		return false;
	});

	$('#show_info').click(function() {
		$('#bill_info').show();
		$('#bill_preview').hide();

		$('#show_preview').show();
		$('#show_info').hide();
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
					<td>
						{{$lan::get('billing_title')}}
					</td>
					<td>
						:
					</td>
					<td>
						{{array_get($data,'parent_name')}}
					</td>
				</tr>
				<tr>
					<td>
						{{$lan::get('member_name_title')}}
					</td>
					<td>
						:
					</td>
					<td>
					@if(array_get($data,'student_list'))
					@foreach (array_get($data,'student_list') as $student_row)
						{{array_get($student_row,'student_no')}} {{array_get($student_row,'student_name')}}<br/>
					@endforeach
					@endif
					</td>
				</tr>
				<tr>
					<td>
						{{$lan::get('invoice_year_month_title')}}
					</td>
					<td>
						:
					</td>
					<td>
						@if(isset($request['invoice_year_month']))
							{{Carbon\Carbon::parse($request['invoice_year_month'])->format('Y年m月')}}
						@endif
					</td>
				</tr>
				<tr>
					<td>
						{{$lan::get('status_title')}}
					</td>
					<td>
						:
					</td>
					<td>
					
					@if(isset($workflow_status_list) && isset($request['workflow_status']))
						{{$workflow_status_list[$request['workflow_status']]}}
						
					@elseif( $request['is_established'] == "1")
						{{$lan::get('confirmed_title')}}
						<input type="hidden" name="is_established" value="1"  />
					@else
						{{$lan::get('editing_title')}}
						<input type="hidden" name="is_established" value="0"  />
						
					@endif
					</td>
				</tr>
				<tr>
					<td>
						{{$lan::get('notification_method_title')}}
					</td>
					<td>
						:
					</td>
					<td>
					@if(isset($request['mail_announce']))
						@if($request['mail_announce'] == "1")
							{{$lan::get('e_mail_title')}}
						@else
							{{$lan::get('mail_title')}}
						@endif
					@endif
					</td>
				</tr>
				<tr>
					<td>
						{{$lan::get('invoice_method_title')}}
					</td>
					<td>
						:
					</td>
					<td>
						@if(isset($invoice_type) && $data['invoice_type'] !== null)
							{{$invoice_type[$data['invoice_type']]}}
						@endif
					</td>
				</tr>
				@if(isset($request['invoice_paid_type']))
				<tr>
					<td>
						{{$lan::get('payment_method_search_title')}}
					</td>
					<td>
						:
					</td>
					<td>
						@if(isset($invoice_type) and isset($request['invoice_paid_type']))
							{{$invoice_type[$request['invoice_paid_type']]}}
						@endif
					</td>
				</tr>
				@endif
				@if(isset($request['invoice_paid_date']))
				<tr>
					<td>
						{{$lan::get('payment_date_title')}}
					</td>
					<td>
						:
					</td>
					<td>
						{{Carbon\Carbon::parse($request['invoice_paid_date'])->format('Y年m月d日')}}
					</td>
				</tr>
				@endif
				<tr>
					<td>
						{{$lan::get('deadline_payment_title')}}
					</td>
					<td>
						:
					</td>
					<td>
						@if(isset($request['due_date']))
							{{Carbon\Carbon::parse($request['due_date'])->format('Y年m月d日')}}
						@endif
					</td>
				</tr>
			</table>
			<button id="show_preview" class="mt10">{{$lan::get('preview_title')}}</button>
			<button id="show_info" class="mt10" style="display:none;">{{$lan::get('back_to_list_title')}}</button>

		</div><!--w5 float_left-->
		<div class="w5 float_right">
			<textarea placeholder="{{$lan::get('personal_note_title')}}" class="textarea2" disabled>{{$request['parent_memo']}}</textarea>
		</div><!--w5 float_right-->
	</div>

	<div id="bill_info">
		<table class="table2">
				<tr>
					<th class="text_title header" style="width:70%;">{{$lan::get('items_title')}}</th>
					<th class="text_title header" style="width:30%;">{{$lan::get('money_amount_title')}}</th>
				</tr>
				@if(isset($data['student_list']))
				@foreach ($data['student_list'] as $student_row)
				@if(isset($request['class_name'][$student_row['id']]))
				@foreach ($request['class_name'][$student_row['id']] as $k => $name)
					
						@if(isset($name))
						<tr>
							<td>
								<div class="grayout">{{$name}}</div>
							</td>
							<td align="center">
						<div class="grayout text-right">
							@if($request['_class_except'][$student_row['id']][$k])
								{{$lan::get('invocie_outside_title')}}
							@else
								\{{number_format($request['class_price'][$student_row['id']][$k])}}
							@endif
								</div>
							</td>
						</tr>
						@endif
				@endforeach
				@endif
				@endforeach
				@endif

				@if(isset($data['student_list']))
				@foreach($data['student_list'] as $student_row)
<!-- 				イベント  -->
				@if(isset($request['course_name'][$student_row['id']]))
				@foreach($request['course_name'][$student_row['id']] as $k => $name)
				@if($name)
				<tr>
					<td>
						<div class="grayout">{{$name}}</div>
					</td>
					<td align="center">
						<div class="grayout text-right">
							@if($request['_course_except'][$student_row['id']][$k])
								{{$lan::get('invocie_outside_title')}}
							@else
								\{{number_format($request['course_price'][$student_row['id']][$k])}}
							@endif
						</div>
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@endforeach
				@endif
				
				@if(isset($data['student_list']))
				@foreach($data['student_list'] as $student_row)
<!-- 				プログラム  -->
				@if(isset($request['program_name'][$student_row['id']]))
				@foreach($request['program_name'][$student_row['id']] as $k => $name)
				@if($name)
				<tr>
					<td>
						<div class="grayout">{{$name}}</div>
					</td>
					<td align="center">
						<div class="grayout text-right">
							@if($request['_program_except'][$student_row['id']][$k])
								{{$lan::get('invocie_outside_title')}}
							@else
								\{{number_format($request['program_price'][$student_row['id']][$k])}}
							@endif
						</div>
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@endforeach
				@endif
				
				@if(isset($data['student_list']))
				@foreach($data['student_list'] as $student_row)
<!-- 				個別入力  -->
				@if(isset($request['custom_item_id'][$student_row['id']]))
				@foreach ($request['custom_item_id'][$student_row['id']] as $k =>  $v )
				@if($v || $request['custom_item_price'][$student_row['id']][$k])
				<tr>
					<td>
						<div class="grayout">
							@if($v && array_get($adjust_names, $v))
								{{array_get($adjust_names, $v)}}&nbsp;
							@else
								{{$request['custom_item_name'][$student_row['id']][$k]}}&nbsp;
							@endif
						</div>
					</td>
					<td>
						<div class="grayout text-right">
							@if($request['custom_item_price'][$student_row['id']][$k])
								@if($request['custom_item_price'][$student_row['id']][$k] < 0)
									▲\{{number_format($request['custom_item_price'][$student_row['id']][$k])}}
								@else
									\{{number_format($request['custom_item_price'][$student_row['id']][$k])}}
								@endif
							@else
								&nbsp;
							@endif
						</div>
					</td>
				</tr>
				@endif
				@endforeach
				@endif
				@endforeach
				@endif

				@if(isset($request['discount_id']))
				@foreach($request['discount_id'] as $k => $v)
				@if($v || $request['discount_price'][$k])
				<tr>
					<td>
						<div class="grayout">
						@if($v && array_get($adjust_names, $v))
							{{array_get($adjust_names, $v)}}&nbsp;
						@else
							{{$request['discount_name'][$k]}}&nbsp;
						@endif
						</div>
					</td>
					<td align="center">
						<div class="grayout text-right">
						@if($request['discount_price'][$k])
							▲\{{number_format($request['discount_price'][$k])}}
						@else
							&nbsp;
						@endif
						</div>
					</td>
				</tr>
				@endif
			@endforeach
			@endif
		</table>

		<div class="table2_bottom">
			<button class="btn5" style="display:none;">{{$lan::get('add_row_title')}}</button>
			<table class="table3">
				<tr>
					<td align="center">
						<p class="text-right">{{$lan::get('subtotal_title')}}</p>
					</td>
					<td>
						@if(isset($request['amount']))
						<p class="text-right">&yen;{{number_format($request['amount'])}}</p>
						@else
						<p class="text-right">&yen;0</p>
						@endif
					</td>
				</tr>
				<tr>
					<td align="center">
						@if($request['amount_display_type'] == 0)
						<p class="text-right">{{$lan::get('consumption_taxes_in_title')}}
							@if(isset($request['sales_tax_disp'])) 
								({{$request['sales_tax_disp']}}%)
							@endif</p>
						@else
						<p class="text-right">{{$lan::get('consumption_taxes_out_title')}}
							@if(isset($request['sales_tax_disp']))
								{{$request['sales_tax_disp']}}%)
							@endif</p>
						@endif
					</td>
					<td>
						@if(isset($request['tax_price']))
						<p class="text-right">&yen;{{number_format($request['tax_price'])}}</p>
						@else
						<p class="text-right">&yen;0</p>
						@endif
					</td>
				</tr>
				<tr>
					<td align="center">
						<p class="text-right">{{$lan::get('total_title')}}<p>
					</td>
					<td>
						@if(isset($request['amount_tax']))
						<p class="text-right">&yen;{{number_format($request['amount_tax'])}}</p>
						@else
						<p class="text-right">&yen;0</p>
						@endif
					</td>
				</tr>
			</table>
			<div class="clr"></div>
		</div>
	</div>


	<div id="bill_preview" style="display:none;">
		<div id="bill_content">
			<div class="bill_header_top">
			<p>
					{{$request['now_date_jp']}}
				</p>
			</div>
			<div id="bill_header">
				<h1>{{$lan::get('invoice_title')}}</h1>
			</div>
			<section id="bill_info">
				<div class="bill_info_left">
					<p class="company_name">{{$data['parent_name']}} {{$lan::get('mr_title')}}</p>

					<p class="bill_p1">{{$lan::get('pay_with_following_invoice_info_title')}}</p>
					<p class="bill_much">{{$lan::get('your_invoice_amount_title')}}
					@if(isset($request['amount_tax']))　&nbsp;&nbsp;&yen;
					{{number_format($request['amount_tax'])}} -
					@endif</p>
					<p class="bill_kigen">@if(isset($request['due_date_jp']))
					{{$lan::get('payment_deadline_title')}}：
					{{$request['due_date_jp']}}
					@endif</p>
				</div><!---->
				<div class="bill_info_right">
					<p class="my_company_name">@if(isset($data['school_name'])) {{$data['school_name']}} @endif</p>
					<p class="my_company_address">
					@if(isset($data['school_address']))
						{{$data['school_address']}}
					@endif
					</p>
					<p class="my_company_daihyou">@if(isset($data['school_daihyou'])) {{$data['school_daihyou']}} @endif</p>
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
<!-- 				プラン -->
					@if(isset($request['class_name'][$student_row['id']]))
					@foreach ($request['class_name'][$student_row['id']] as $k => $name)
					@if(isset($name))
					<tr>
						<td>
							{{$name}}
						</td>
						<td class="td2">
						@if($request['_class_except'][$student_row['id']][$k])
							{{$lan::get('invocie_outside_title')}}
						@elseif( $request['class_price'][$student_row['id']][$k])
							&yen;{{number_format($request['class_price'][$student_row['id']][$k])}}
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
<!-- 				イベント -->
					@if(isset($request['course_name'][$student_row['id']]))
					@foreach ($request['course_name'][$student_row['id']] as $k => $name)
					@if(isset($name))
					<tr>
						<td>
							{{$name}}
						</td>
						<td class="td2">
						@if($request['_course_except'][$student_row['id']][$k])
							{{$lan::get('invocie_outside_title')}}
						@elseif( $request['course_price'][$student_row['id']][$k])
							&yen;{{number_format($request['course_price'][$student_row['id']][$k])}}
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
					@foreach ($request['program_name'][$student_row['id']] as $k => $name)
					@if(isset($name))
					<tr>
						<td>
							{{$name}}
						</td>
						<td class="td2">
						@if($request['_program_except'][$student_row['id']][$k])
							{{$lan::get('invocie_outside_title')}}
						@elseif( $request['program_price'][$student_row['id']][$k])
							&yen;{{number_format($request['program_price'][$student_row['id']][$k])}}
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
<!-- 				 個別入力  -->
					@if(isset($request['custom_item_id'][$student_row['id']]))
					@foreach ($request['custom_item_id'][$student_row['id']] as $k => $v)
					@if($v || $request['custom_item_price'][$student_row['id']][$k])
					<tr>
						<td>
						@if($v && array_get($adjust_names, $v))
							{{array_get($adjust_names, $v)}}&nbsp;
						@else
							{{$request['custom_item_name'][$student_row['id']][$k]}}&nbsp;
						@endif
						</td>
						<td class="td2">
						@if(isset($request['custom_item_price'][$student_row['id']][$k]))
							@if($request['custom_item_price'][$student_row['id']][$k] < 0)
								▲ &yen;{{number_format($request['custom_item_price'][$student_row['id']][$k])}}
							@elseif( $request['custom_item_price'][$student_row['id']][$k])
								&yen;{{number_format($request['custom_item_price'][$student_row['id']][$k])}}
							@endif
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
				
				@if(isset($request['discount_id']))
				@foreach ($request['discount_id'] as $k => $v)
				@if($v || $request['discount_price'][$k])
				<tr>
					<td>
					@if($v and array_get($adjust_names,$v))
						{{array_get($adjust_names,$v)}}&nbsp;
					@else
						{{$request['discount_name'][$k]}}&nbsp;
					@endif
					</td>
					<td class="td2">
					@if(isset($request['discount_price'][$k]))
						▲ &yen;{{number_format($request['discount_price'][$k])}}
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
					<th class="th1">{{$lan::get('subtotal_title')}}</th>
					<td class="td2">
						&yen;@if(isset($request['amount']))
								{{number_format($request['amount'])}}
							@else
								0
							@endif
					</td>
				</tr>
				<tr>
					@if($request['amount_display_type'] == 0)
					<th class="th1">{{$lan::get('consumption_taxes_in_title')}}
					@if(isset($request['sales_tax_disp'])) 
						{{$request['sales_tax_disp']}}%)
					@endif</th>
					@else
					<th class="th1">{{$lan::get('consumption_taxes_out_title')}}
					@if(isset($request['sales_tax_disp'])) 
						{{$request['sales_tax_disp']}}%)
					@endif</th>
					@endif
					<td class="td2">
						&yen;@if(isset($request['tax_price']))
							{{number_format($request['tax_price'])}}
							@else
								0
							@endif
					</td>
				</tr>
				<tr>
					<th class="th1">{{$lan::get('total_title')}}</th>
					<td class="td2">
						&yen;@if(isset($request['amount_tax']))
							{{number_format($request['amount_tax'])}}
							@else
								0
							@endif
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
	{{-- @endif  --}}

</div>
</div>

