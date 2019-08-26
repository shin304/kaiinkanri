@extends('_parts.master_layout') @section('content')

<script type="text/javascript">
$(function() {
	$("#data_table").tablesorter( {
		headers: {
			0: { sorter: false },
			1: { sorter: false },
			2: { sorter: false }
		}
	});
});
</script>

<!-- 	 メニュー  -->
	@include('_parts.invoice.axis_menu')
<!-- 	 パンくず  -->
	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('invoice') !!}</div> --}}
	@include('_parts.topic_list')
	<div id="section_content">

		@if(isset($action_status))
		<div class="alart_box box_shadow">
			@if( $action_status and $action_status == "OK")
				<ul class="message_area"><li class="info_message">{{$action_message}}</li></ul>
			@elseif($action_status)
				<ul class="message_area"><li class="error_message">{{$action_message}}</li></ul>
			@endif
		</div>
		@endif
				
			<table class="table1 tablesorter " id="data_table">
				<thead>
					<tr>
                        <th class="text_title" style="width:200px;">{{$lan::get('invoice_year_month_title')}}</th>
						<th class="text_title" style="width:480px;">{{$lan::get('invoice_information_title')}}</th>
						<th class="text_title" style="width:200px;">{{$lan::get('invoice_number_people_title')}}</th>
					</tr>
				</thead>
				<tbody>
				@if(isset($invoice_list))
					@foreach ($invoice_list as $idx => $heads)
					<tr class="table_row">
                        <td style="width:200px;">
                            <a id="test" class="text_link" href="{{$_app_path}}invoice/search?simple&search_cond=2&invoice_year_month={{array_get($heads, 'invoice_year_month')}}&invoice_year_to_s={{array_get($heads, 'invoice_year')}}&invoice_month_to_s={{array_get($heads, 'invoice_month')}}&invoice_year_from_s={{array_get($heads, 'invoice_year')}}&invoice_month_from_s={{array_get($heads, 'invoice_month')}}">
                                <p>{{Carbon\Carbon::parse(array_get($heads, 'invoice_year_month'))->format('Y年m月分請求書')}}	</p>
                                <p class="p10">
                                    @if(array_get($heads, 'register_date'))
                                        {{$lan::get('create_date_title')}}：{{array_get($heads, 'register_date')}}
                                    @endif</p>
                            </a>
                        </td>
						<td style="width:480px;">
							<ul class="progress_ul">
								@if(array_get($heads, 'cnt_entry'))
								<li class="bill1">{{$lan::get('status_imported_title')}}[{{array_get($heads, 'cnt_entry')}}]</li>
								@else
								<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
								@endif
								@if(array_get($heads, 'cnt_confirm'))
								<li class="bill2">{{$lan::get('confirmed_title')}}[{{array_get($heads, 'cnt_confirm')}}]</li>
								@else
								<li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
								@endif
								@if(array_get($heads, 'cnt_send'))
								<li class="bill3">{{$lan::get('invoiced_title2')}}[{{array_get($heads, 'cnt_send')}}]</li>
								@else
								<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
								@endif
								@if(array_get($heads, 'cnt_complete'))
								<li class="bill4">{{$lan::get('payment_already_title')}}[{{array_get($heads, 'cnt_complete')}}]</li>
								@else
								<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
								@endif
							</ul>
						</td>
						<td style="width:200px; text-align:center;">
							<p>{{$lan::get('billing_persons_title')}}{{array_get($heads, 'cnt_invoice')}}{{$lan::get('person_title')}}</p>
							<p class="p10">{{$lan::get('account_tranfer_title')}}：{{array_get($heads, 'cnt_richo')}} {{$lan::get('invoiced_title')}}{{array_get($heads, 'cnt_other')}}</p>
						</td>
					</tr>
						@endforeach
						@endif
				@if(!isset($invoice_list))
					<tr class="table_row">
						<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
					</tr>
				@endif
				</tbody>
			</table>
	</div> <!-- section_content -->
@stop
