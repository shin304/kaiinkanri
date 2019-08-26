@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
	$(function() {
		/*$(".tablesorter").tablesorter({
			headers: {
				0: { sorter: false}
			}
		});*/
	});

	$(function() {
		$(".download_cancel_button").click(function() {
			return confirm("{{$lan::get('cancel_download_request_title')}}");
		});
	});

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
	});

	$(function() {
		function simple_clear(){
			$("select[name='invoice_year_from']").val("");
			$("select[name='invoice_month_from']").val("");
			$("select[name='invoice_year_to']").val("");
			$("select[name='invoice_month_to']").val("");
			$("select[name='invoice_status']").val("");

			$("select[name='class_id']").val("");

			$("select[name='is_established']").val("");

			$("select[name='invoice_year']").val("");
			$("select[name='invoice_month']").val("");

			$("select[name='course_id']").val("");
			$("select[name='is_recieved']").val("");

			$('input[name="invoice_type[0]"]').prop("checked",false);
			$('input[name="invoice_type[1]"]').prop("checked",false);
			$('input[name="invoice_type[2]"]').prop("checked",true);
			return false;
		}

		function detail_clear(){
			$("input[name='parent_name']").val("");
			$("input[name='student_name']").val("");
			$("select[name='school_category']").val("");
			$("select[name='school_year']").val("");
			$("#school_grade option").remove();
			return false;
		}

		$('#search_simple_clear').click(function() {  // clear
			simple_clear();
			return false;
		});

		$('#search_detail_clear').click(function() {  // clear
			simple_clear();
			detail_clear();
			return false;
		});

		$('#current_month').click(function() {
			var curr_date = new Date();
			var curr_year = curr_date.getFullYear();
			var curr_month = curr_date.getMonth() + 1;

			$("select[name='invoice_year_from']").val({{$drop_year}});
			$("select[name='invoice_month_from']").val({{$drop_month}});
			$("select[name='invoice_year_to']").val({{$drop_year}});
			$("select[name='invoice_month_to']").val({{$drop_month}});
			return false;
		});

		$(document).ready(function(){
			if( {{$search_cond}} == 1){
				$('#simple_search').hide();
				$("input[name='search_cond']").val("1");
			}else{
				$('#detail_search').hide();
				$("input[name='search_cond']").val("2");
			}
			return false;
		});

		$('#search_condition_detail_btn').click(function() {
			$('#detail_search').hide();
			$('#simple_search').show();

			detail_clear();
			$("input[name='search_cond']").val("2");
			return false;
		});

		$('#search_condition_simple_btn').click(function() {
			$('#simple_search').hide();
			$('#detail_search').show();

			$("input[name='search_cond']").val("1");
			return false;
		});

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

	});
</script>

{{-- 未入金リスト スクリプト --}}
{{-- include file="pages_pc/school/_parts/invoice/arrear_script.html" --}}
@include('_parts.invoice.arrear_script')
<style type="text/css">
	#wrapper .search_box td {
		font-weight: bold;
		vertical-align: middle;
	}
    #zengin_info {
  	     	padding-left:50px;
    }
</style>

@if( $file_name)
<meta http-equiv="Refresh" content="1;URL={{$_app_path}}invoice/downloadFile?file_name={{$file_name}}">
@endif
		{{-- メニュー  --}}
		{{-- include file="pages_pc/school/_parts/invoice/axis_menu.html" --}}
		@include('_parts.invoice.axis_menu')
		{{-- パンくず --}}
		{{-- include file="pages_pc/school/_parts/topic_list.html" --}}
		@include('_parts.topic_list')
		<div class="alart_box box_shadow">
			<ul class="message_area">
			@if( $action_status and $action_status == "OK")
				<li class="info_message">{{$action_message}}</li>
			@elseif( $action_status)
				<li class="error_message">{{$action_message}}</li>
			@else
				<pre>{{$lan::get('select_the_invoice_to_create_title')}}</pre>
			@endif
			</ul>
			<div id="data_table"></div>
		</div>

		<div id="section_content">

		<div id="section_content_in">
		<h4>{{$lan::get('last_month_request_list_title')}}</h4>
			<table class="table1 ">
				<thead>
					<tr>
						<th style="width:100px;padding:5px 5px;" class="text_title">{{$lan::get('invoice_year_month_title')}}</th>
						<th style="width:100px;padding:5px 5px;" class="text_title">{{$lan::get('notice_date_title')}}</th>
						<th style="width:100px;padding:5px 5px;" class="text_title">{{$lan::get('request_day_title')}}</th>
						<th style="width:100px;padding:5px 5px;" class="text_title">{{$lan::get('submit_deadline_title')}}</th>
						<th style="width: 90px;padding:5px 5px;" class="text_title">{{$lan::get('file_name_title')}}</th>
						<th style="width: 80px;padding:5px 5px;" class="text_title">{{$lan::get('invoice_number_title')}}</th>
						<th style="width:110px;padding:5px 5px;" class="text_title">{{$lan::get('invoice_amount_title')}}</th>
						<th style="width:140px;padding:5px 5px;" class="text_title">{{$lan::get('status_download_title')}}</th>
						<th style="width: 60px;padding:5px 5px;" class="text_title">{{$lan::get('process_title')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($file_list as $idx =>  $row)
						<tr class="table_row">
							<td style="width:100px;padding:5px 5px;text-align:center;">{{Carbon\Carbon::parse(array_get($row, 'invoice_year_month'))->format('%Y年%m月')}}</td>
							<td style="width:100px;padding:5px 5px;text-align:center;">{{Carbon\Carbon::parse(array_get($row, 'result_date'))->format('%Y年%m月')}}</td>
							<td style="width:100px;padding:5px 5px;text-align:center;">{{Carbon\Carbon::parse(array_get($row, 'register_date'))->format('%Y年%m月')}}</td>
							<td style="width:100px;padding:5px 5px;text-align:center;">{{Carbon\Carbon::parse(array_get($row, 'deadline'))->format('%Y年%m月')}}</td>
							<td style="width: 90px;padding:5px 5px;text-align:center;">{{substr(array_get($row,'processing_filename'),0,8)}}</td>
							<td style="width: 80px;padding:5px 5px;text-align:center;">{{$row.total_cnt}}</td>
							<td style="width:110px;padding:5px 5px;text-align:right;" >{{$row.total_amount|number_format}}</td>
							@if( (Carbon\Carbon::parse(array_get($row,'deadline'))->format('%Y-%m-%d')) > (Carbon\Carbon::parse(Carbon::now())->format('%Y-%m-%d')))
							<td style="width:140px;padding:5px 5px;text-align:center;">
								{{$requesttable_status[array_get($row,'status_flag')]}}
							</td>
							<td style="width: 60px;padding:5px 5px;text-align:center;">
								@if( array_get($row,'status_flag') == 3)

								@elseif( array_get($row,'status_flag')== 2)

								@elseif( array_get($row,'status_flag') == 1)
								<a class="cancel_button" href="{{$_app_path}}invoice/downloadCancel?invoice_year_month={{array_get($heads,'invoice_year_month')}}&file_name={{array_get($row,'processing_filename')}}">{{$lan::get('cancel_btn_title')}}</a>
								@endif

							</td>
							@endif
						</tr>
					@endforeach
					@if(!isset($file_list))
					<tr class="table_row">
							<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div><br/>


		<div id="section_content_in">
		<h4>{{$lan::get('pre_invoice_list_title')}}</h4>
			<form id="action_form" action="{{$_app_path}}invoice/DownloadComplete" method="post">
			<table class="table1 tablesorter body_scroll_table">
				<thead>
					<tr>
						<th style="width: 50px;" class="text_title">{{$lan::get('selection_title')}}</th>
						<th style="width:140px;" class="text_title header">{{$lan::get('member_name_title')}}</th>
						<th style="width:380px;" class="text_title header">{{$lan::get('status_title')}}</th>
						<th style="width:110px;" class="text_title header">{{$lan::get('invoice_amount_title')}}</th>
						<th style="width:100px;" class="text_title header">{{$lan::get('file_name_title')}}</th>
						<th style="width:120px;" class="text_title header">{{$lan::get('create_date_title')}}</th>

					</tr>
				</thead>
				<tbody>
					@foreach($invoice_list as $idx => $row)
						<tr class="table_row">
							<td style="width:50px;text-align:center;">
								@if( array_get($row,'workflow_status') != 0 && array_get($row,'workflow_status') != 1)
								<input type="checkbox" name="parent_ids[]" value="{{array_get($row,'id')}}" class="parent_select" checked="checked" @if( array_get($row,'workflow_status') != 11 && array_get($row,'workflow_status') != 29) disabled @endif/>
								@endif
								<input type="hidden" name="invioce_ids[]" value="{{array_get($row,'id')}}"/>
							</td>
							<td style="width:140px;">
							@if(array_get($row,'student_list'))
								@foreach(array_get($row,'student_list') as $student_row)
									{{-- @if(array_get($auths,'student_detail') == 1)  --}}
									<a class="text_link" href="{{$_app_path}}invoice/detail?id={{array_get($row,'id')}}">
										{{$student_row.student_name}}
									</a><br/>
									{{-- @else
									<label>{{$student_row.student_name}}</label>
									@endif  --}}
								@endforeach
							@endif
							</td>
							<td style="width:380px;text-align:center;">
								<ul class="progress_ul ">
								@if( array_get($row,'active_flag') != 1 || array_get($row,'workflow_status') < 0)
									<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
								@else
									<li class="bill1">{{$lan::get('status_imported_title')}}</li>
								@endif
								@if( array_get($row,'active_flag') != 1 || array_get($row,'workflow_status') < 1)
									<li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
								@else
									<li class="bill2">{{$lan::get('confirmed_title')}}</li>
								@endif
								@if( array_get($row,'active_flag')  != 1 || array_get($row,'workflow_status') < 11)
									<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
								@else
									<li class="bill3">{{$lan::get('invoiced_title')}}</li>
								@endif
								@if( array_get($row,'active_flag')  != 1 || array_get($row,'workflow_status') < 31)
									<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
								@else
									<li class="bill4">{{$lan::get('payment_already_title')}}</li>
								@endif
								</ul>

							</td>
							<td style="width:110px;text-align:right;">
								@if( array_get($row,'amount_display_type') == "0" || !array_get($row,'sales_tax_rate'))
								{{number_format(array_get($row,'amount'))}}
								@else
								@php
									$x = array_get($row,'amount');
									$y = array_get($row,'sales_tax_rate');
									$amount_tax = x+floor(x*y);
								@endphp
								{{number_format($amount_tax)}}
								@endif
							</td>
							<td style="width:100px;text-align:center;">
								{{substr(array_get($row,'processing_filename'),0,8)}}
							</td>
							<td style="width:120px;text-align:center;">
								@if( array_get($row,'register_date')) 
								{{ Carbon\Carbon::parse(array_get($row,'register_date'))->format('%Y-%m-%d') }}
								@endif
							</td>
						</tr>
					@endforeach
					@if(!isset($invoice_list))
					<tr class="table_row">
							<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
						</tr>
					@endif
				</tbody>
			</table>
				<input type="hidden" name="invoice_year_month" value="{{array_get($heads,'invoice_year_month')}}"></input>
			@if( count($invoice_list) > 0)
				<input type="submit" value="{{$lan::get('download_title')}}" id="btn_confirm" class="btn_green"/>
			@endif
			</form>
			</div> <!-- section_content_in -->
		</div> <!-- section_content -->

<div id="dialog_cancel" class="no_title" style="display:none;">
	{{$lan::get('cancel_confirm_title')}}
</div> <!-- dialog_receive_check -->

@stop

