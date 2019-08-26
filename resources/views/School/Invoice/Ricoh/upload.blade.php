@extends('_parts.master_layout') @section('content')
	<script type="text/javascript">
	$(function() {
        $("#btn_return").click(function () {
            $("#frm_return").submit();
        })
	});
	</script>
	<style type="text/css">
		.error_message {
			font-weight: bold;
			color: #ff0000;
		}
		.header_box {
			min-height: 50px;
			background: #b0b4f2;
			padding: 5px;
			margin-bottom: 20px;
			color: #fff;
			vertical-align: middle !important;
		}
		.header_box p {
			padding: 5px !important;
			margin: 0px !important;
			font-size: 16px;
		}
		.header_box ul{
			margin-top: 5px;
		}
		#btn_return:hover {
			background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
			box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
			cursor: pointer;
			text-shadow: 0 0px #FFF;
		}
		#btn_return {
			color: #595959;
			height: 30px;
			border-radius: 5px;
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
			/*font-size: 14px;*/
			font-weight: normal;
			text-shadow: 0 0px #FFF;
		}
	</style>

		@include('_parts.invoice.invoice_menu')

		@if(!empty($this_screen))
			<div class="header_box box_shadow">
				<div style="float: left">
					<p>{{$lan::get('bank_method_title')}}</p>
				</div>
				{{--<div style="float: right">--}}
					{{--<ul class="btn_ul">--}}
                        {{--<li class='no_active'  style="background:#25b4c6;">--}}
                            {{--<a  href="{{$_app_path}}invoice/ricohTransProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('account_tranfer_title')}}</a>--}}
                        {{--</li>--}}
					{{--</ul>--}}
				{{--</div>--}}
			</div>
		@endif

        @if( isset($request['upload_state']) && ($request['upload_state'] == 0 || $request['upload_state'] == 1))
            <div class="search_box box_border1 padding1">
                <form action="{{$_app_path}}invoice/ricohTransUpload" method="post" class="search_form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" name="upload_file"><br/>
                    <input type="submit" class="btn_green" value="{{$lan::get('upload_data_btn')}}"/><br/>
                    <input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}"></input>
                    <br/><button id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left" style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
                </form>
            </div>
		@endif
		
		@if( isset($request['upload_state']) && ($request['upload_state'] == 1 || $request['upload_state'] == 2))

		<div id="section_content">
			@if(isset($request->errors))
				<div class="error_message">
						{{$request->errors['error_code']}} : {{$lan::get($request->errors['error_msg'])}} @if(isset($request->errors['line'])) : line {{$request->errors['line']}} @endif
				</div>
			@endif
            @if(isset($request['upload_state']) && ($request['upload_state'] == 2))
			<div id="section_content_in">
				@if( $request['upload_state'] == 1))
					<h4>{{$lan::get('reading_result_title')}}</h4>
				@elseif( $request['upload_state'] == 2)
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
							<td>{{date('Y年m月d日',strtotime(date('Y')."-".$request['withdrawal_date']))}}</td>
						</tr>
						<tr>
							<td class="t6_td1">{{$lan::get('transfer_number_title')}}</td>
							<td>{{$trailer_disp_data['success_cnt']}}{{$lan::get('item_title')}}</td>
						</tr>
							<td class="t6_td1">{{$lan::get('tranfer_amount_title')}}</td>
							<td>{{$trailer_disp_data['success_amout']}}{{$lan::get('yen_title')}}</td>
						</tr>
						<tr>
							<td class="t6_td1">{{$lan::get('non_transfer_number_title')}} {{$lan::get('transfer_result_title')}}</td>
							<td>{{$trailer_disp_data['fail_cnt']}}{{$lan::get('item_title')}}</td>
						</tr>
						<tr>
							<td class="t6_td1">{{$lan::get('non_transfer_number_title')}}{{$lan::get('transfer_result_title')}}</td>
							<td>{{$trailer_disp_data['fail_ammount']}}{{$lan::get('yen_title')}}</td>
						</tr>
					</tbody>
				</table>
				@if($request['upload_state'] == 1)
					<h4>{{$lan::get('reading_result_detailed_list_title')}}</h4>
				@elseif( $request['upload_state'] == 2)
					<h4>{{$lan::get('transfer_results_detailed_list_title')}}</h4>
				@endif
				<form action="{{$_app_path}}invoice/ricohTransUploadComplete" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}">
					<table class="table1">
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
						@if(isset($data_display))
							@foreach ($data_display as $idx => $row)
								<tr class="table_row">
									<td style="width:140px; padding-left:15px;">
										@foreach ($row['item'] as $student_row)
											<a class="text_link" href="{{$_app_path}}invoice/detail?id={{$row['id']}}">
												{{$student_row['student_name']}}
											</a><br/>
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
										@if( $row['active_flag'] != 1 or $row['workflow_status'] < 11)
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
										@if( $row['register_date'])
											{{date('Y-m-d',strtotime($row['register_date']))}}
										@endif
									</td>
								</tr>
								@endforeach
								@endif
							@if(!isset($data_display))
								<tr class="table_row">
									<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
								</tr>
							@endif
						</tbody>
					</table>
					<br/>

					@if( $request['upload_state'] == 2 && ($trailer_disp_data['success_cnt'] + $trailer_disp_data['fail_cnt'] >0 ))
						<input type="submit" value="{{$lan::get('register_title')}}" id="btn_confirm" class="submit3"/><br/>
					@elseif( $request['upload_state'] == 1)

					@endif
                    <br/><button id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left" style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
				</form>
				</div>
                @endif
			</div>
		@endif
        <form action="{{$_app_path}}invoice/ricohTransProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}" method="post" id="frm_return">
            {{ csrf_field() }}
        </form>
	@stop

