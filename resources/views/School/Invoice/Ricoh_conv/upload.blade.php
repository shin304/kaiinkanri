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
			background: #fcc692;
			padding: 5px;
			margin-bottom: 20px;
			color: #fff;
			vertical-align: middle !important;
		}
		.header_box p {
			padding: 5px !important;
			margin: 0px !important;
			font-size: 20px;
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
					<p>{{$lan::get('convenient_store_title')}}</p>
				</div>
				{{--<div style="float: right">--}}
					{{--<ul class="btn_ul">--}}
						{{--<li class='no_active'  style="background:#25b4c6;">--}}
							{{--<a  href="{{$_app_path}}invoice/ricohConvProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('convenient_store_title')}}</a>--}}
						{{--</li>--}}
					{{--</ul>--}}
				{{--</div>--}}
			</div>
		@endif

        @if( isset($request['upload_state']) && ($request['upload_state'] == 0 || $request['upload_state'] == 1))
            <div class="search_box box_border1 padding1">
                <form action="{{$_app_path}}invoice/ricohConvUpload" method="post" class="search_form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" name="upload_file"/><br/>
                    <input type="submit" class="btn_green" value="{{$lan::get('upload_data_btn')}}"/><br/>
                    <input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}" />
					<br/><button id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left" style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
                </form>
            </div>
		@endif
		
		@if( isset($request['upload_state']) && ($request['upload_state'] == 1 || $request['upload_state'] == 2))

		<div id="section_content">
			@if(isset($request->errors))
				<div class="error_message">
						{{$lan::get($request->errors)}}
				</div>
			@endif
            @if(isset($request['upload_state']) && ($request['upload_state'] == 2))
			<div id="section_content_in">
				@if( $request['upload_state'] == 1))
					<h4>{{$lan::get('reading_result_title')}}</h4>
				@elseif( $request['upload_state'] == 2)
					<h4>{{$lan::get('combini_header')}}</h4>
				@endif
				<table id="table6">
					<colgroup>
						<col width="25%"/>
						<col width="75%"/>
					</colgroup>
					<tbody>

						<tr>
							<td class="t6_td1">{{$lan::get('combini_deposit_number')}}</td>
							<td>{{$preview_data['success_cnt']}}{{$lan::get('item_title')}}</td>
						</tr>
							<td class="t6_td1">{{$lan::get('combini_deposit_amount')}}</td>
							<td>{{number_format($preview_data['success_amout'])}}{{$lan::get('yen_title')}}</td>
						</tr>

					</tbody>
				</table>
				@if($request['upload_state'] == 1)
					<h4>{{$lan::get('reading_result_detailed_list_title')}}</h4>
				@elseif( $request['upload_state'] == 2)
					<h4>{{$lan::get('combini_result_list')}}</h4>
				@endif
				<form action="{{$_app_path}}invoice/ricohConvUploadComplete" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}">
					<table class="table1">
						<thead>
							<tr>
								<th style="width:200px;" class="text_title header">{{$lan::get('member_name_title')}}</th>
								<th style="width:450px; text-align: center" class="text_title header">{{$lan::get('status_title')}}</th>
								<th style="width:70px; text-align: center" class="text_title header">{{$lan::get('combini_amount_charged')}}</th>
								<th style="width:150px; text-align: center" class="text_title header">{{$lan::get('combini_deposit_result')}}</th>
								<th style="width:120px; text-align: center" class="text_title header">{{$lan::get('dp_paid_date')}}</th>
							</tr>
						</thead>
						<tbody>
						@if(isset($records))
							@foreach ($records as $idx => $row)
								<tr class="table_row">
									<td style="width:200px;;">
										@if(array_get($row, 'is_nyukin') == 0)
											<a  href="{{$_app_path}}invoice/detail?id={{array_get($row, 'id')}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}" class="text_link">
												{{array_get($row, 'parent_name')}}
											</a><br/>
										@else
											{{array_get($row, 'parent_name')}}
										@endif
									</td>
									<td style="width:450px;">
                                        @if(array_get($row, 'is_nyukin') == 0)
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
                                                    <li class="bill3">{{$lan::get('invoiced_title2')}}</li>
                                                @endif
                                                @if( $row['active_flag'] != 1 or $row['workflow_status'] < 31)
                                                    <li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
                                                @else
                                                    <li class="bill4">{{$lan::get('payment_already_title')}}</li>
                                                @endif
                                            </ul>
                                        @else
                                            {{array_get($row, 'entry_name')}}
                                        @endif
									</td>
									<td style="width:70px;text-align:right; padding-right: 15px">
										{{number_format($row['amount'])}}
									</td>
									<td style="width:150px; text-align: center">
										{{$row['result_msg']}}
									</td>
									<td style="width:120px;text-align:center;">
										@if( $row['paid_date'])
											{{date('Y-m-d',strtotime($row['paid_date']))}}
										@endif
									</td>
								</tr>
                            @endforeach
                        @endif
                        @if(!isset($records))
                            <tr class="table_row">
                                <td class="error_row">{{$lan::get('information_displayed_title')}}</td>
                            </tr>
                        @endif
						</tbody>
					</table>
					<br/>

					@if( $request['upload_state'] == 2 && ($preview_data['success_cnt']>0 ))
						<input type="hidden" name="import_tmp_name" value="{{$file_name}}">
						<input type="submit" value="{{$lan::get('register_title')}}" id="btn_confirm" class="submit3"/>
					@elseif( $request['upload_state'] == 1)

					@endif
				</form>
				</div>
                @endif
			</div>
		@endif
    <form action="{{$_app_path}}invoice/ricohConvProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}" method="post" id="frm_return">
        {{ csrf_field() }}
    </form>
	@stop

