<div class="invoice_student_tab_content invoice_confirm_tab"
	style="background-color: bisque;">
	<div class="invoice_header_area">
		<div class="parent_name">{{$data['parent_name']}}
			{{$lan::get('mr_title')}}</div>
		<div class="amount_tax">
			<span>{{$lan::get('invoice_amount_confirm_title')}}\</span> 
			@if(isset($request['amount_tax'])) <span>
				{{number_format($request['amount_tax'])}}</span>
			@else <span name="amount_tax"></span> @endif <span>{{$lan::get('include_consumption_taxes_title')}}</span>
		</div>
	</div>

	<br>
	<hr>
	<br> <br>

	<div class="invoice_body_area">
		{{$lan::get('subject_invoice_title')}} {{$request['invoice_year']}} {{$lan::get('year_title')}} {{$request['invoice_month']}}{{$lan::get('month_part_title')}}<br>
		{{$lan::get('payment_deadline_title')}} {{$request['invoice_due_year']}} {{$lan::get('year_title')}} {{$request['invoice_due_month']}} {{$lan::get('month_title')}} {{$request['invoice_due_day']}} {{$lan::get('day_title')}}<br>
		<br> {{$lan::get('your_invoice_summary_title')}}<br>

		@foreach ($data['student_list'] as $student_row)
		{{$student_row['student_name']}}{{$lan::get('mr_title')}}<br>
		@php
			$total_price = 0;
		@endphp
		<div class="clearfix student_item_list">
<!-- 			 プラン   -->
			@foreach ($request['class_name'][$student_row['id']] as $k => $name)
			 @if( $name)
			<div class="item_name">{{$name}}&nbsp;</div>
			<div class="item_price">
				\{{number_format($request['class_price'][$student_row['id']][$k])}}</div>
			<div>@if(isset($request['_class_except'][$student_row['id']][$k]))
				△\{{number_format($request['class_price'][$student_row['id']][$k])}} {{$lan::get('invocie_outside_title')}}
				@else &nbsp; @endif</div>

			@if( !isset($request['_class_except'][$student_row['id']][$k])) 
				@php
					$total_price = $total_price + $request['class_price'][$student_row['id']][$k];
				@endphp
			@endif
			@endif 
			@endforeach 
			
<!-- 			 イベント  -->
			@foreach($request['course_name'][$student_row['id']] as $k => $name)
			@if($name)
			<div class="item_name">{{$name}}&nbsp;</div>
			<div class="item_price">
				\{{number_format($request['course_price'][$student_row['id']][$k])}}</div>
			<div>@if( isset($request['_course_except'][$student_row['id']][$k]))
				△\{{number_format($request['course_price'][$student_row['id']][$k])}}
				{{$lan::get('invocie_outside_title')}}
				@else &nbsp; @endif</div>
			@if( !isset($request['_course_except'][$student_row['id']][$k]))
				@php
				$total_price = $total_price + $request['course_price'][$student_row['id']][$k];
				@endphp
			@endif @endif @endforeach

			<!-- 				 個別入力  -->
			@if(isset($request['custom_item_id'][$student_row['id']]))
			@foreach ($request['custom_item_id'][$student_row['id']] as $k => $v)
			@if( $v || $request['custom_item_price'][$student_row['id']][$k])
			<div class="item_name">@if( is_numeric($v) && $adjust_names[$v])
				{{truncate($adjust_names[$v],39)}}&nbsp; @else
				{{truncate($request['custom_item_name'][$student_row['id']][$k],39)}}&nbsp;
				@endif</div>
			<div class="item_price">
				@if(is_numeric($request['custom_item_price'][$student_row['id']][$k]))
				@if( $request['custom_item_price'][$student_row['id']][$k] < 0)
				▲\{{number_format(str_replace('-', ':',
				$request['custom_item_price'][$student_row['id']][$k]))}} @else
				\{{number_format($request['custom_item_price'][$student_row['id']][$k])}}
				@endif @php $total_price = $total_price +
				$request['custom_item_price'][$student_row['id']][$k]; @endphp @else
				&nbsp; @endif</div>
			<div>&nbsp;</div>
			@endif @endforeach
			@endif
			<div class="student_total">{{$lan::get('subtotal_title')}}
				&nbsp;&nbsp;&nbsp;</div>
			<div class="item_price">\{{number_format($total_price)}}</div>
		</div>
		@endforeach
	</div>

	<hr>

	@php $is_discount_exists = false; @endphp
	<div class="invoice_body_area">
		<div class="clearfix discount_item_list">
			@if(isset($request['discount_id'])) @foreach ($request['discount_id']
			as $k => $v) @if( $v || $request['discount_price'][$k])
			<div class="item_name">@if( is_numeric($v) && $adjust_names[$v])
				{{truncate($adjust_names[$v],39}}&nbsp; @else
				{{$request.discount_name[$k]|truncate:39}}&nbsp; @endif</div>
			<div class="item_price">
				@if(is_numeric($request['discount_price'][$k]))
				▲\{{number_format($request['discount_price'][$k])}} @else &nbsp;
				@endif</div>
			<br /> @php $is_discount_exists = true; @endphp @endif @endforeach
			@endif
		</div>
	</div>

	@if(isset($is_discount_exists))
	<hr>
	@endif

	<div class="invoice_footer_area">
		<div class="clearfix discount_item_list">
			<div class="item_name">
				{{$lan::get('total_title')}}&nbsp;&nbsp;&nbsp;</div>
			<div class="item_price">
				@if(isset($request['amount'])}}
				\{{number_format($request['amount'])}} @else \<span id="amount"></span>
				@endif
			</div>
		</div>
		<div class="clearfix discount_item_list">
			<div class="item_name">
				{{$lan::get('consumption_taxes_title')}}&nbsp;&nbsp;&nbsp;</div>
			<div class="item_price">
				@if(isset($request['tax_price'])}}
				\{{number_format($request['tax_price'])}} @else \<span
					id="tax_price"></span> @endif
			</div>

		</div>
		<div class="clearfix discount_item_list">
			<div class="item_name">
				{{$lan::get('include_taxes_title')}}&nbsp;&nbsp;&nbsp;</div>
			<div class="item_price">
				@if( isset($request['amount_tax'])
				\{{number_format($request['amount_tax']}} @else \<span
					name="amount_tax"></span> @endif
			</div>
		</div>
	</div>
</div>
