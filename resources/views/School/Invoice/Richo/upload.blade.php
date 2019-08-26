@extends('_parts.master_layout') @section('content')
	<script type="text/javascript">
	$(function() {
		$('#selectall').click(function() {  //on click
	        if(this.checked) { // check select status
	            $('.parent_select').each(function() { //loop through each checkbox
	            	if(!this.disabled)
	                	this.checked = true;  //select all checkboxes with class "question_select"
	            });
	        }else{
	            $('.parent_select').each(function() { //loop through each checkbox
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
					var desc = "{{$lan::get('student_year_title')}}";
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
 		/*$(".tablesorter").tablesorter({
// 			headers: {
// 				0: { sorter: false}
// 			}
 		});*/
	});
	</script>
	<style type="text/css">
		#wrapper .search_box td {
			font-weight: bold;
			vertical-align: middle;
		}
		.error_message {
			font-weight: bold;
			color: #ff0000;
		}
	</style>

		@include('_parts.invoice.axis_menu')

	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('broadcastmail') !!}</div> --}}
	@include('_parts.topic_list')
	
		<h3 id="content_h3" class="box_border1">{{$lan::get('create_acount_capture_title')}}</h3>
	
		@if( $action_status || !$next_proc)
		<div class="search_box box_border1 padding1">
			@if( $action_status}}
				<ul class="message_area">
					<li class="@if( $action_status == "OK")
							info_message @else error_message @endif">
						{{$action_message}}
					</li>
				</ul>
				<br/>
			@endif

			@if( !$next_proc)
			<form action="{{$_app_path}}invoice/upload" method="post" class="search_form" enctype="multipart/form-data">
				<input type="file" name="upload_file"></input>
				<input type="submit" class="btn_green" value="{{$read_in_title}}"/>
				<input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}"></input>
			</form>
			@endif
		</div>
		@endif
		
		@if( $upload_data)

		<div id="section_content">
			@if( $error_info)
				<div class="error_message">
					@foreach ($error_info as $idx => $row)
						{{$row['error_code']}} : {{$row['error_msg']}} @if($row['line']}} : line {{$row['line']}} @endif
					@endforeach
				</div>
			@endif

			<div id="section_content_in">
				@if( $next_proc == 1)
					<h4>{{$lan::get('reading_result_title')}}</h4>
				@elseif( $next_proc == 2)
					<h4>{{$lan::get('transfer_result_title')}}</h4>
				@endif
				<table id="table6">
					<colgroup>
						<col width="25%"/>
						<col width="75%"/>
					</colgroup>
					<tbody>
						<tr>
							<td class="t6_td1">{{$lan::get('transfer_date_title')}}</td>
							<td>@if(count($error_info) < 1)
								{{Carbon\Carbon::parse($withdrawal_date)->format('%Y年%m月%d日')}}
								@endif</td>
						</tr>
						<tr>
							<td class="t6_td1">{{$lan::get('transfer_number_title')}}</td>
							<td>@if(count($error_info) < 1)
									{{$trailer_record['success_cnt']}}{{$lan::get('item_title')}}
								@endif</td>
						</tr>
							<td class="t6_td1">{{$lan::get('tranfer_amount_title')}}</td>
							<td>@if( count($error_info) < 1)
									{{$trailer_record['success_amout']}}{{$lan::get('yen_title')}}
								@endif</td>
						</tr>
						<tr>
							<td class="t6_td1">{{$lan::get('non_transfer_number_title')}} {{$lan::get('transfer_result_title')}}</td>
							<td>@if(count($error_info) < 1)
									{{$trailer_record['fail_cnt']}}{{$lan::get('item_title')}}
								@endif</td>
						</tr>
						<tr>
							<td class="t6_td1">{{$lan::get('non_transfer_number_title')}}{{$lan::get('transfer_result_title')}}</td>
							<td>@if(count($error_info) < 1)
									{{$trailer_record['fail_ammount']}}{{$lan::get('yen_title')}}
								@endif</td>
						</tr>
					</tbody>
				</table>
				@if($next_proc == 1)
					<h4>{{$lan::get('reading_result_detailed_list_title')}}</h4>
				@elseif( $next_proc == 2)
					<h4>{{$lan::get('transfer_results_detailed_list_title')}}</h4>
				@endif
				<form action="{{$_app_path}}invoice/UploadComplete" method="post">
					<table class="table1 tablesorter ">
						<thead>
							<tr>
								<th style="width:140px;" class="text_title header">{{$lan::get('member_name_title')}}</th>
								<th style="width:380px;" class="text_title header">{{$lan::get('status_title')}}</th>
								<th style="width:110px;" class="text_title header">{{$lan::get('invoice_amount_title')}}</th>
								<th style="width:150px;" class="text_title header">{{$lan::get('transfer_result_title')}}</th>
								<th style="width:120px;" class="text_title header">{{$lan::get('create_date_title')}}</th>
							</tr>
						</thead>
						<tbody>
						@if(isset($data_record))
							@foreach ($data_record as $idx => $row)
								<tr class="table_row">
									<td style="width:140px;">
										@foreach ($row['item'] as $student_row)
											@if( $auths['student_detail'] == 1)
											<a class="text_link" href="{{$_app_path}}invoice/detail?id={{$row['id']}}">
												{{$student_row['student_name']}}
											</a><br/>
											@else 
											<label>{{$student_row['student_name']}}</label>
											@endif
										@endforeach
									</td>
									<td style="width:380px;text-align:center;">
										<ul class="progress_ul ">
										@if( $row['active_flag'] != 1 or $row['workflow_status'] < 0)
											<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
										@else
											<li class="bill1">{{$lan::get('status_imported_title')}}</li>
										@endif
										@if( $row['active_flag'] != 1 or $row['workflow_status'] < 1)
											<li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
										@else
											<li class="bill2">{{$lan::get('confirmed_title')}}</li>
										@endif
										@if( $row['active_flag'] != 1 or $row['workflow_status']' < 11)
											<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
										@else
											<li class="bill3">{{$lan::get('invoiced_title')}}</li>
										@endif
										@if( $row['active_flag'] != 1 or $row['workflow_status'] < 31)
											<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
										@else
											<li class="bill4">{{$lan::get('payment_already_title')}}</li>
										@endif
										</ul>
		
									</td>
									<td style="width:110px;text-align:right;">
										{{$row['amount']}}
									</td>
									<td style="width:150px;text-align:center;">
										{{$row['result_msg']}}
									</td>
									<td style="width:120px;text-align:center;">
										@if( $row['register_date']}} 
											{{Carbon\Carbon::parse($row['register_date'])->format('%Y-%m-%d')}}	
										@endif
									</td>
								</tr>
								@endforeach
								@endif
							@if(!isset($data_record))
								<tr class="table_row">
									<td class="error_row">{{$lan::get('information_displayed_title')}</td>
								</tr>
							@endif
						</tbody>
					</table>
					<br/>
					
					@if( $next_proc == 1 && count($error_info) < 1 && ($trailer_record['success_cnt'] + $trailer_record['fail_cnt'] >0 ))
						<input type="submit" value="{{$register_title}}" id="btn_confirm" class="submit3"/>
					@elseif( $next_proc == 2)

					@endif
				</form>
				</div>
			</div>
		@endif
	@stop

