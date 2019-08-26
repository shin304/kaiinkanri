<div class="content_header">
	<div id="section_content_in">

	<table>
		<tr>
			<td colspan="2" style="width:400px;">
				{{$lan::get('guardian_fullname_confirm_title')}}
				{{$data['parent_name}}
			</td>
			<td style="width:300px;">
				{{$lan::get('invoice_month_title')}}
				{{$request['invoice_year']}}{{$lan::get('year_title')}}
				{{$request['invoice_month']}}{{$lan::get('month_title')}}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="clearfix">
				<div style="width:72px;float:left;">
					{{$student_name_confirm_title}}{{$lan::get('mail_title')}}
				</div>
				<ul style="float:left;width:320px;">
				@if(isset($data['student_list'))
					@foreach ($data['student_list'] as $student_row)
						<li style="float:left;width:90%;list-style-type:none;">
							<div style="width:60px;float:left;">
							@if(is_numeric($student_row['school_category']))
								{{$schoolCategory[$student_row['school_category']]}}
							@if( $student_row['school_year'])
								{{$student_row['school_year']}} 年 
							@endif
							@endif</div>
							<div style="width:200px;float:left;">{{$student_row['student_no']}} {{$student_row['student_name']}} </div>
						</li>
					@endforeach
				@endif
				</ul>
			</td>
			<td>
				<label>
					{{$lan::get('status_list_title']}}{{$workflow_status_list[$request['workflow_status']]}}
				</label>
				<br/>
				<label>
					{{$notification_method_confirm_title}}
					@if( $request['mail_announce'] == "1")
						{{$lan::get('e_mail_title')}}
					@else
						{{$lan::get('mail_title')}}
					@endif
				</label>
				<br/>
				<label>
					{{$lan::get('payment_method_confirm_title')}}
					{{$invoice_type[$data['invoice_type']]}}
				</label>
			</td>
		</tr>
	</table>
	</div>
</div>

<div class="content_detail">
	@include('_parts.invoice.confirm_tab')
</div>
