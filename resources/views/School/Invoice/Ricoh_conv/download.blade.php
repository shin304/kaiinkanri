<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/invoice.css" />
@extends('_parts.master_layout') @section('content')
<script type="text/javascript">

	$(function() {
		$(".download_cancel_button").click(function() {
			return confirm("{{$lan::get('cancel_download_request_title')}}");
		});

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
        $("#btn_return").click(function () {
            $("#frm_return").submit();
        })

        $("#btn_confirm").click(function () {
            setInterval(function () {
                location.reload();
            },2000)
        })

        $(".cancel_button").click(function(e) {
            var link = $('.cancel_button').attr('href');
            e.preventDefault();
            $( "#dialog_cancel" ).dialog({
                title: "{{$lan::get('account_transfer_title')}}",
                autoOpen: false,
                resizable: false,
                height: 'auto',
                width: 400,
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
        $(".drop_down").click(function () {
            var ele =$(this);
            if(ele.children().hasClass("fa-chevron-down")){
                ele.children().removeClass("fa-chevron-down");
                ele.children().addClass("fa-chevron-up");
            }else if(ele.children().hasClass("fa-chevron-up")){
                ele.children().removeClass("fa-chevron-up");
                ele.children().addClass("fa-chevron-down");
            }
        })

    });
</script>
<style type="text/css">
	#wrapper .search_box td {
		font-weight: bold;
		vertical-align: middle;
	}
    #zengin_info {
  	     	padding-left:50px;
    }
    .small_button li{
        width: 65px !important;
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
    table .accordion_tbl , table .content_accordion{
        width: 100%;
    }
    .accordion_tbl td{
        padding: 10px 5px 5px !important;
    }
    .table1 th{
        text-align: center;
    }

    #accordion_table_header thead tr:first-child {
        border-bottom: solid 4px #DCDDDD;
        border-top: solid 4px #DCDDDD;
        margin-bottom: 10px !important;
    }

    .panel-heading {
        padding: 10px 0 !important;
    }

    #accordion_table_header thead tr td {
        color: #63738c;
        font-weight: bold;
        font-size: 13px;
    }

    #accordion_table_header .panel-heading {
        border-radius: 0;
        border: none;
    }

    #accordion_table_header .panel-default {
        border: none;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    #accordion_table_header table tr td:last-child {
        font-size: 15px;
    }

    #accordion_table_header .panel-default>.panel-heading {
        background-color: white;
    }

    table tr td {
        /*border: 1px solid red!important;*/
    }

    .blank_link {
        color: #0044CC !important;
        text-decoration: underline;
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
        @if(isset($request['errors']))
            <div class="alart_box box_shadow">
                <ul class="message_area">
                        <br/><li class="error_message">{{$lan::get($request['errors'])}}</li>
                </ul>
                <div id="data_table"></div>
            </div>
        @endif
		<div id="section_content">

		<div id="section_content_in">
		<h4>{{$lan::get('combini_request_title')}}</h4>
			<table class="table1 ">
				<thead>
					<tr>
						{{--<th style="width:100px;padding:5px 5px;" class="text_title">{{$lan::get('invoice_year_month_title')}}</th>--}}
						{{--<th style="width:100px;padding:5px 5px;" class="text_title">{{$lan::get('notice_date_title')}}</th>--}}
						<th style="width:220px;padding:5px 5px;" class="text_title">{{$lan::get('request_day_title')}}</th>
						{{--<th style="width:100px;padding:5px 5px;" class="text_title">{{$lan::get('submit_deadline_title')}}</th>--}}
						<th style="width: 190px;padding:5px 5px;" class="text_title">{{$lan::get('file_name_title')}}</th>
						<th style="width: 180px;padding:5px 5px;" class="text_title">{{$lan::get('invoice_number_title')}}</th>
						<th style="width:90px;padding:5px 5px;" class="text_title">{{$lan::get('total_invoice_amount')}}</th>
						<th style="width:140px;padding:5px 5px;" class="text_title">{{$lan::get('status_download_title')}}</th>
						<th style="width: 60px;padding:5px 5px;" class="text_title">{{$lan::get('process_title')}}</th>
					</tr>
				</thead>
				<tbody>
                    @if(isset($file_list))
                        @foreach ($file_list as $idx =>  $row)
                            <tr class="table_row">
                                {{--<td style="width:100px;padding:5px 5px;text-align:center;">{{date('Y-m',strtotime($row['invoice_year_month'].'-01'))}}</td>--}}
                                {{--<td style="width:100px;padding:5px 5px;text-align:center;">{{date('Y-m-d',strtotime($row['result_date']))}}</td>--}}
                                <td style="width:220px;padding:5px 5px;text-align:center;">{{date('Y-m-d',strtotime($row['register_date']))}}</td>
                                {{--<td style="width:100px;padding:5px 5px;text-align:center;">{{date('Y-m-d',strtotime($row['deadline']))}}</td>--}}
                                <td style="width: 190px;padding:5px 5px;text-align:center;">{{substr(array_get($row,'processing_filename'),0,8)}}</td>
                                <td style="width: 180px;padding:5px 5px;text-align:center;">{{$row['total_cnt']}}</td>
                                <td style="width:90px;padding:5px 5px;text-align:right;" >{{number_format($row['total_amount'])}}</td>
                                @if( date('Y-m-d',strtotime(array_get($row,'deadline'))) > (date('Y-m-d')))
                                <td style="width:140px;padding:5px 5px;text-align:center;">
                                    {{$request['request_status'][array_get($row,'status_flag')]}}
                                </td>
                                <td style="width: 60px;padding:5px 5px;text-align:center;">
                                    @if( array_get($row,'status_flag') == 3)

                                    @elseif( array_get($row,'status_flag')== 2)

                                    @elseif( array_get($row,'status_flag') == 1)
                                    <a class="cancel_button" href="{{$_app_path}}invoice/cancelconv?invoice_year_month={{array_get($heads,'invoice_year_month')}}&file_name={{array_get($row,'processing_filename')}}">{{$lan::get('cancel_btn_title')}}</a>
                                    @endif

                                </td>
                                @endif
                            </tr>
                        @endforeach
					@else
					    <tr class="table_row">
							<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div><br/>


		<div id="section_content_in">
            <h4>{{$lan::get('pre_invoice_list_title')}}</h4>
                <form id="action_form" action="{{$_app_path}}invoice/ricohConvDownloadComplete" method="post">
                    {{ csrf_field() }}
                    {{--Header--}}
                    <div class="panel-group" id="accordion_table_header">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <table class="accordion_tbl">
                                    <thead>
                                    <tr>
                                        <td style="width:6%; text-align: center;" class="text_title header">{{$lan::get('selection_title')}}</td>
                                        <td style="width:20%;" class="text_title header">{{$lan::get('member_name_title')}}</td>
                                        <td style="width:35%; text-align: center;" class="text_title header">{{$lan::get('status_title')}}</td>
                                        <td style="width:10%; text-align:center;" class="text_title header">{{$lan::get('invoice_amount_title')}}</td>
                                        <td style="width:12%; text-align: center;" class="text_title header">{{$lan::get('file_name_title')}}</td>
                                        <td style="width:13%; text-align: center;" class="text_title header">{{$lan::get('create_date_title')}}</td>
                                        <td  style="width:5%;text-align: center" class="drop_down">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!$invoice_list)
                                        <tr class="table_row">
                                            <td class="error_row" colspan="7">{{$lan::get('information_displayed_title')}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{--Body--}}
                    @foreach($invoice_list as $idx => $row)
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <table class="accordion_tbl">
                                        <tbody>
                                        <tr>
                                            <td style="width:6%; text-align: center;" class="text_title header">
                                                @if( array_get($row,'workflow_status') != 0 &&  array_get($row,'amount') >0)
                                                    <input type="checkbox" name="invoice_ids[]" value="{{array_get($row,'id')}}" class="parent_select" checked="checked" @if( array_get($row,'workflow_status') != 1 && array_get($row,'workflow_status') != 29) disabled @endif/>
                                                @endif
                                            </td>
                                            <td style="width:20%;" class="text_title header">
                                                @if(array_get($row, 'is_nyukin') == 0)
                                                    <a  href="{{$_app_path}}invoice/detail?id={{array_get($row, 'id')}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}" class="blank_link">
                                                        {{array_get($row, 'parent_name')}}
                                                    </a><br/>
                                                @elseif(array_get($row, 'is_nyukin') == 1)
                                                    <a target="_blank" href="/portal/event/?message_key={{array_get($row,'link')}}" class="blank_link"> {{array_get($row, 'parent_name')}}</a>
                                                @elseif(array_get($row, 'is_nyukin') == 2)
                                                    <a target="_blank" href="/portal/program/?message_key={{array_get($row,'link')}}" class="blank_link">{{array_get($row, 'parent_name')}}</a>
                                                @endif
                                            </td>
                                            <td style="width:35%;" class="text_title header">
                                                @if(array_get($row, 'is_nyukin') == 0)
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
                                                            <li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
                                                        @else
                                                            <li class="bill4">{{$lan::get('payment_already_title')}}</li>
                                                        @endif
                                                    </ul>
                                                @elseif(array_get($row, 'is_nyukin') == 1)
                                                    {{array_get($row, 'item_name')}}
                                                @elseif(array_get($row, 'is_nyukin') == 2)
                                                    {{array_get($row, 'item_name')}}
                                                @endif
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
                                            <td style="width:12%; text-align: center;" class="text_title header">
                                                {{substr(array_get($row,'processing_filename'),0,8)}}
                                            </td>
                                            <td style="width:13%; text-align: center;" class="text_title header">
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
                    <input type="hidden" name="invoice_year_month" value="{{array_get($heads,'invoice_year_month')}}">
                    @if( count($invoice_list) > 0)
                    <input type="submit" value="{{$lan::get('download_title')}}" id="btn_confirm" class="btn_green"/><br/>
                    @endif
                    <br/><button id="btn_return" class="submit3" type="button"><i class="glyphicon glyphicon-circle-arrow-left" style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
                </form>
			</div> <!-- section_content_in -->
		</div><!-- section_content -->

<div id="dialog_cancel" class="no_title" style="display:none;">
	{{$lan::get('external_delete_file_warning')}}
</div> <!-- dialog_receive_check -->
<form action="{{$_app_path}}invoice/ricohConvProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}" method="post" id="frm_return">
    {{ csrf_field() }}
</form>
@stop

