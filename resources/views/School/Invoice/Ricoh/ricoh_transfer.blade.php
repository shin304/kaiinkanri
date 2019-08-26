<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/invoice.css" />
@extends('_parts.master_layout') @section('content')
	<script type="text/javascript">

		$(function() {

            $("#btn_return").click(function () {
                $("#frm_return").submit();
            })

			$(".cancel_button").click(function(e) {
				var link = $('.cancel_button').attr('href');
				e.preventDefault();
			    $( "#dialog_cancel" ).dialog({
			      title: "{{$lan::get('account_tranfer_title')}}",
			      autoOpen: false,
			      resizable: false,
			      height:140,
			      modal: true,
			      buttons: {
			        "{{$lan::get('ok_title')}}": function() {
			          //window.location.href = link;
			          java_post(link);
			          $( this ).dialog( "close" );
			        },
			        "{{$lan::get('cancel_title')}}": function() {
			          $( this ).dialog( "close" );
			        }
			      }
			    });
			    $("#dialog_cancel").dialog("open");
			});

            $(".display_info").change(function () {
                var container = "panel_"+$(this).data('request_id');
                if($(this).is(":checked")){
                    $("#"+container).show(300);
                }else{
                    $("#"+container).hide(300);
                }
            })

		});
	</script>
	<style type="text/css">
		#wrapper .search_box td {
			font-weight: bold;
			vertical-align: middle;
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
            font-size: 20px;
        }
        .header_box ul{
            margin-top: 5px;
        }
		.table_list th{
            text-align: center;
        }
        table .accordion_tbl , table .content_accordion{
            width: 100%;
        }

        .accordion_tbl td{
            padding: 10px 5px 5px !important;
        }
        .panel-heading{
            width: 100%;
            padding:0px !important;
        }
        .table1 th{
            text-align: center;
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
        .isDisabled {
            color: #595959;
            text-decoration: none;
            font-size: 12px;
            border-radius: 3px;
            text-align: center;
            transition-duration: 0.1s;
            background-color: #f4f5f5;
            background-image: -webkit-linear-gradient(top, #f4f5f5, #dfdddd);
            padding: 3px 10px !important;
            width: 150px !important;
        }
	</style>

		@include('_parts.invoice.invoice_menu')

		@if(!empty($this_screen))
            <div class="header_box box_shadow">
                <div style="float: left">
                    <p>{{$lan::get('bank_method_title')}}</p>
                </div>

                <div style="float: right">
                    <ul class="btn_ul">
                        @if($process_flg ==0 || $process_flg == 1)
                            @if($process_flg != 1 )
                            <li class="no_active" style="background:#25b4c6;">
                                <a class="text_link" style="color:white !important;" href="{{$_app_path}}invoice/ricohTransDownload?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('create_data_external')}}</a>
                            </li>
                            @endif
                            <li class="no_active" style="background:#25b4c6;">
                                <a class="text_link" style="color:white !important;" href="{{$_app_path}}invoice/ricohTransUpload?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('upload_data_external')}}</a>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>
		@endif

		@if( isset($request['messages']))
		<div class="alart_box box_shadow">
			<ul class="message_area">
				<li class="info_message">{{$lan::get($request['messages'])}}</li>
			</ul>
			<div id="data_table"></div>
		</div>
		@endif

		<div id="section_content1">
			<table class="table_list">
			<thead>
					<tr>
                        <th style="width:20px;" class="text_title"></th>
						<th style="width:100px;" class="text_title">{{$lan::get('invoice_year_month_title')}}</th>
						<th style="width:100px;" class="text_title">{{$lan::get('notice_date_title')}}</th>
						<th style="width:130px;" class="text_title">{{$lan::get('request_day_title')}}</th>
						<th style="width:100px;" class="text_title">{{$lan::get('submit_deadline_title')}}</th>
						<th style="width:90px;" class="text_title">{{$lan::get('file_name_title')}}</th>
						<th style="width:80px;" class="text_title">{{$lan::get('invoice_number_title')}}</th>
						<th style="width:90px;" class="text_title">{{$lan::get('total_invoice_amount')}}</th>
						<th style="width:130px;" class="text_title">{{$lan::get('status_title')}}</th>
						<th style="width:60px;text-align:center;" class="text_title">{{$lan::get('process_title')}}</th>

					</tr>
				</thead>
				<tbody>
					@foreach ($transfer_list as $idx => $row)
						<tr class="table_row">
                            <td style="width: 20px;padding:5px 5px;text-align:center;">
                                @if($row['status_flag'] == 1 || $row['status_flag'] == 3)
                                    <input type="checkbox" class="display_info" data-request_id="{{$row['id']}}">
                                @endif
                            </td>
							<td style="width:100px;padding:5px 5px;text-align:center;">{{date('Y-m',strtotime($row['invoice_year_month'].'-01'))}}</td>
							<td style="width:100px;padding:5px 5px;text-align:center;">{{date('Y-m-d',strtotime($row['result_date']))}}</td>
							<td style="width:130px;padding:5px 5px;text-align:center;">{{date('Y-m-d',strtotime($row['register_date']))}}</td>
							<td style="width:100px;padding:5px 5px;text-align:center;">{{date('Y-m-d',strtotime($row['deadline']))}}</td>
							<td style="width: 90px;padding:5px 5px;text-align:center;">{{substr(array_get($row,'processing_filename'),0,8)}}</td>
							<td style="width: 80px;padding:5px 5px;text-align:center;">{{$row['total_cnt']}}</td>
							<td style="width:90px;padding:5px 5px;text-align:right;" >{{number_format($row['total_amount'])}}</td>
							<td style="width:140px;padding:5px 5px;text-align:center;">{{$request['request_status'][array_get($row,'status_flag')]}}</td>
                            {{--@if( date('Y-m-d',strtotime(array_get($row,'deadline'))) > (date('Y-m-d')))--}}
							<td style="width:60px;text-align:center;">
								@if( array_get($row, 'status_flag') < 3 )
									<a class="cancel_button" href="{{$_app_path}}invoice/canceltransfer?invoice_year_month={{array_get($heads,'invoice_year_month')}}&file_name={{array_get($row,'processing_filename')}}" style="color: #595959; text-decoration: none; font-size: 12px; border-radius: 3px; text-align: center; transition-duration: 0.1s; background-color: #f4f5f5; background-image: -webkit-linear-gradient(top, #f4f5f5, #dfdddd); padding: 3px 10px !important; width: 150px !important;">
									{{$lan::get('cancel_btn_title')}}
									</a>
								@endif
							</td>
							{{--@endif--}}
						</tr>
					@endforeach
					@if(!isset($transfer_list))
					<tr class="table_row">
							<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
					</tr>
					@endif
				</tbody>
			</table>
            @foreach($transfer_list as $key => $transfer)
                @if($transfer['status_flag'] == 1 || $transfer['status_flag'] == 3)
                    <div id="panel_{{$transfer['id']}}" style="display: none">
                        @foreach($transfer['detail_request'] as $idx => $row )
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <table class="accordion_tbl">
                                            <tbody>
                                            <tr>
                                                <td style="width:5%; text-align: center;" class="text_title header">

                                                </td>
                                                <td style="width:20%;" class="text_title header">
                                                    <a  href="{{$_app_path}}invoice/detail?id={{array_get($row, 'id')}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}" class="text_link">
                                                        {{array_get($row, 'parent_name')}}
                                                    </a><br/>
                                                </td>
                                                <td style="width:30%; text-align: center;" class="text_title header">
                                                    <ul class="progress_ul small_button ">
                                                        @if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 0)
                                                            <li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
                                                        @else
                                                            <li class="bill1">{{$lan::get('status_imported_title')}}</li>
                                                        @endif
                                                        @if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 1)
                                                            <li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
                                                        @else
                                                            <li class="bill2">{{$lan::get('confirmed_title')}}</li>
                                                        @endif
                                                        @if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 11)
                                                            <li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
                                                        @else
                                                            <li class="bill3">{{$lan::get('invoiced_title2')}}</li>
                                                        @endif
                                                        @if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 31)
                                                            @if(array_get($row, 'workflow_status') == 29 && array_get($row, 'error_code'))
                                                                <li class="bill4 transfer_error">{{$lan::get('not_payment_title')}}({{array_get($row, 'error_code')}})</li>
                                                            @else
                                                                <li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
                                                            @endif
                                                        @else
                                                            <li class="bill4">{{$lan::get('payment_already_title')}}</li>
                                                        @endif
                                                    </ul>
                                                </td>
                                                <td style="width:10%; text-align:right;" class="text_title header">
                                                    @if(array_get($row, 'amount_display_type') == "0" or !array_get($row, 'sales_tax_rate'))
                                                        {{number_format(array_get($row, 'amount'))}}
                                                    @else
                                                        @php
                                                            $x = array_get($row, 'amount');
                                                            $y = array_get($row, 'sales_tax_rate');
                                                            $amount_tax = $x+floor($x*$y);
                                                        @endphp
                                                        {{number_format($amount_tax)}}
                                                    @endif
                                                </td>
                                                <td style="width:15%; text-align: center;" class="text_title header">
                                                    {{substr(array_get($row,'processing_filename'),0,8)}}
                                                </td>
                                                <td style="width:10%; text-align: center;" class="text_title header">
                                                    @if(array_get($row, 'register_date'))
                                                        {{Carbon\Carbon::parse(array_get($row, 'register_date'))->format('Y-m-d')}}
                                                    @endif
                                                </td>
                                                <td  style="width:5%;text-align: center" data-toggle="collapse" href="#collapse_{{array_get($row, 'id')}}" class="drop_down"><i  class="fa fa-chevron-down"></i></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="collapse_{{array_get($row, 'id')}}" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <table class="content_accordion" border="1">
                                                <thead>
                                                <tr>

                                                    <th  style="width:20%; text-align: center;">{{$lan::get('member_name_title')}}</th>
                                                    <th  style="width:20%; text-align: center;">{{$lan::get('dp_student_no')}}</th>
                                                    <th  style="width:20%; text-align: center;">{{$lan::get('dp_student_type')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="width:20%;" class="text_title header">
                                                        @foreach (array_get($row, 'student_list') as $student_row)
                                                            {{array_get($student_row, 'student_name')}}<br/>
                                                        @endforeach
                                                    </td>
                                                    <td style="width:20%">
                                                        @foreach (array_get($row, 'student_list') as $student_row)
                                                            {{array_get($student_row, 'student_no')}}<br/>
                                                        @endforeach
                                                    </td>
                                                    <td style="width:20%">
                                                        @foreach (array_get($row, 'student_list') as $student_row)
                                                            {{array_get($student_row, 'student_type_name')}}<br/>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
			<button form="frm_return" id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left" style="width: 20%;font-size:16px;"></i>{{$lan::get('dp_back')}}</button>
	<div id="dialog_cancel" class="no_title" style="display:none;">
		{{$lan::get('cancel_confirm_title')}}
	</div> <!-- dialog_receive_check -->
    <form action="/school/invoice/list" method="post" id="frm_return">
        {{ csrf_field() }}
        <input type="hidden" name="invoice_year_month" value="{{$request->invoice_year_month}}">
    </form>
@stop
