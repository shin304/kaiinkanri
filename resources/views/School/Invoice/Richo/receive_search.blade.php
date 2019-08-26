@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
	$(function() {
		/*$(".tablesorter").tablesorter( {
			headers: {
				0: { sorter: false },
				{{**8: { sorter: false },**}}
			}
		});*/
	});

	$(function() {
		//datepicker追加
		var d = new Date();
		$(".DateInput").datepicker({
			   showOn: 'both',
			   dateFormat: 'yy-mm-dd',
			   changeMonth: true,
			   changeYear: true,
			   monthNames: ["{{$lan::get('jan_title')}}","{{$lan::get('feb_title')}}","{{$lan::get('mar_title')}}","{{$lan::get('apr_title')}}","{{$lan::get('may_title')}}","{{$lan::get('jun_title')}}","{{$lan::get('jul_title')}}","{{$lan::get('aug_title')}}","{{$lan::get('sep_title')}}","{{$lan::get('oct_title')}}","{{$lan::get('nov_title')}}","{{$lan::get('dec_title}}"],
			   monthNamesShort: ["{{$lan::get('jan_title')}}","{{$lan::get('feb_title')}}","{{$lan::get('mar_title')}}","{{$lan::get('apr_title')}}","{{$lan::get('may_title')}}","{{$lan::get('jun_title')}}","{{$lan::get('jul_title')}}","{{$lan::get('aug_title')}}","{{$lan::get('sep_title')}}","{{$lan::get('oct_title')}}","{{$lan::get('nov_title')}}","{{$lan::get('dec_title')}}"],
			   dayNames: ["{{$lan::get('sunday_title')}}","{{$lan::get('monday_title')}}","{{$lan::get('tuesday_title')}}","{{$lan::get('wednesday_title')}}","{{$lan::get('thursday_title')}}","{{$lan::get('friday_title')}}","{{$lan::get('saturday_title')}}"],
			   dayNamesShort: ["{{$lan::get('sun_title')}}","{{$lan::get('mon_title')}}","{{$lan::get('tue_title')}}","{{$lan::get('wed_title')}}","{{$lan::get('thu_title')}}","{{$lan::get('fri_title')}}","{{$lan::get('sat_title')}}"],
			   dayNamesMin: ["{{$lan::get('sun_title')}}","{{$lan::get('mon_title')}}","{{$lan::get('tue_title')}}","{{$lan::get('wed_title')}}","{{$lan::get('thu_title')}}","{{$lan::get('fri_title')}}","{{$lan::get('sat_title')}}"],
			   yearRange: '1910:'+(d.getYear()+1910),
			   prevText: '&#x3c;{{$lan::get('prev_title')}}", prevStatus: "{{$lan::get('show_previous_month_title')}}",
			   prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: "{{$lan::get('show_previous_year_title')}}",
			   nextText: "{{$lan::get('next_title')}}&#x3e;', nextStatus: "{{$lan::get('show_next_month_title')}}",
			   nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: "{{$lan::get('show_next_year_title')}}",
			   currentText: "{{$lan::get('today_title')}}", currentStatus: "{{$lan::get('show_this_month_title')}}",
			   todayText: "{{$lan::get('today_title')}}", todayStatus: "{{$lan::get('show_this_month_title')}}",
			   clearText: "{{$lan::get('clear_title')}}", clearStatus: "{{$lan::get('clear_date_title')}}",
			   closeText: "{{$lan::get('close_btn')}}", closeStatus: "{{$lan::get('close_without_change_title')}}"
		});
	});

	$(function() {
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
					var desc = "年生";
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

		$(".receive_check_button").click(function(e) {
				var link = $('.receive_check_button').attr('href');
				e.preventDefault();
			    $( "#dialog_receive_check" ).dialog({
			      dialogClass: "noTitle",
			      title: "{{$check_payment_title}}",
			      autoOpen: false,
			      resizable: false,
			      height:140,
			      modal: true,
			      buttons: {
			        "{{$ok_title}}": function() {
			          //window.location.href = link;
			          java_post(link);
			          $( this ).dialog( "close" );
			        },
			        "{{$cancel_title}}": function() {
			          $( this ).dialog( "close" );
			        }
			      }
			    });
			    $("#dialog_receive_check").dialog("open");
		});

		$("#receive_dialog").click(function() {
			//$( "#dialog_confirm" ).dialog('open');
			$("#action_form").attr('action', '{{$_app_path}}invoice/receiveselect');
			$("#action_form").submit();
			return false;
		});
		$("#dialog_confirm").dialog({
			title: "{{$process_payment_title}}",
			autoOpen: "{{$dialog_open}}",
			dialogClass: 'no-close',
			resizable: false,
		    width: 350,
			modal: true,
			buttons: {
				"{{$go_btn}}": function() {
					var receipt = $("#dialog_confirm input[name='dialogs_receipt']:checked").val();
					if(receipt){	/* 領収書発行 */
						makePdf(0,'receipt');
						return false;
					}
					else{
						$("#dialog_form").attr('action', '{{$_app_path}}invoice/receivecomplete?back');
						$("#dialog_form").submit();
						return false;
					}
					$( this ).dialog( "close" );
				},
				"{{$cancel_title}}": function() {
					$( this ).dialog( "close" );
					return false;
				}
			}
		});
	  function getid(){
		  var parent_ids = [];
		  var i=0;
  		  $('.parent_select').each(function(){
  			  if(this.checked){
				  parent_ids[i] = $(this).val();
				  i++;
  			  }
  		  });
  		  return parent_ids;
	  }
	  var PdfMaker = {
			    go: function(id, mode) {
			    	var parent_ids = getid();
			        var defer = $.Deferred();
			        $.ajax({
			            url: "{{$_app_path}}ajaxInvoice/makereceipt",
			            data: {
			                parent_ids: parent_ids,
			                mode: mode
			            },
			            dataType: 'json',
			            beforeSend: function(xhr){
			            	$(".message_area").remove();
			            	$("#loading").show();
			            },
			            success: defer.resolve,
			            error: defer.reject,
			            complete: function() {
			            	$("#loading").hide();
			            }
			        });
			        return defer.promise();
			    }
			};

		function makePdf(id, mode) {

			PdfMaker.go(id, mode).done(function(data) {
				if (data.error) {
		        	html="<ul class='message_area'><li class='error_message' onclick='$(this).remove();' style='cursor: pointer;'>"+data.error+"</li></ul>";
		        	$("#data_table").before(html);
		        	return false;
		        }
		        else{
		        	if (data.id > 0){
		        		window.open("{{$_app_path}}ajaxInvoice/show?id="+data.id);
		        	}
					$("#dialog_form").attr('action', '{{$_app_path}}invoice/receivecomplete?back');
					$("#dialog_form").submit();
					return false;
		        }
		    });
		}
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
			$('input[name="invoice_type[2]"]').prop("checked",false);
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

			$.get(
				"{{$_app_path}}ajaxInvoice/newest_year_month",
				{},
				function(data)
				{
					var curr_year  = data.curr_year;
					var curr_month = data.curr_month;

					$("select[name='invoice_year_from']").val({{$drop_year}});
					$("select[name='invoice_month_from']").val({{$drop_month}});
					$("select[name='invoice_year_to']").val({{$drop_year}});
					$("select[name='invoice_month_to']").val({{$drop_month}});
				},
				"jsonp"
			);
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

	});
</script>

<!-- 未入金リスト スクリプト -->
@include('_parts.invoice.arrear_script')

<style type="text/css">
	#wrapper .search_box td {
		font-weight: bold;
		vertical-align: middle;
	}
</style>

	@include('_parts.invoice.axis_menu')
	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('broadcastmail') !!}</div> --}}
	@include('_parts.topic_list')

	<div id="section_content">
		<div class="alart_box box_shadow">
			<ul class="message_area">
			@if( $action_status and $action_status == "OK")
				<li class="info_message">{{$action_message}}</li>
			@elseif($action_status)
				<li class="error_message">{{$action_message}}</li>
			@else
				<pre>{{$lan::get('select_payment_record_title')}}</pre>
			@endif
			</ul>
			<div id="data_table"></div>
		</div>

		<div id="section_content_in">
			&nbsp;<input type="checkbox" id="selectall">&nbsp;&nbsp;{{$lan::get('select_all_title')}}</input><br/>
			<form method="post" id="action_form">
			<table class="table_list tablesorter body_scroll_table">
				<thead>
					<tr>
						<th style="width: 50px;" class="text_title">{{$lan::get('selection_title')}}</th>
						<th style="width:140px;" class="text_title header">{{$lan::get('member_name_title')}}</th>
						<th style="width:380px;" class="text_title header">{{$lan::get('status_title')}}</th>
						<th style="width:110px;" class="text_title header">{{$lan::get('invoice_amount_title')}}</th>
						<th style="width:100px;" class="text_title header">{{$lan::get('invoice_method_title')}}</th>
						<th style="width:120px;" class="text_title header">{{$lan::get('create_date_title')}}</th>
					</tr>
				</thead>
				<tbody>
				@if(isset($invoice_list))
					@foreach ($invoice_list as $idx => $row)
						<tr class="table_row">
							<td style="width:50px;text-align:center;">
								@if( $row['workflow_status'] == 11 || $row['workflow_status'] == 29)
								<input type="checkbox" name="invoice_ids[]" value="{{$row['id']}}" class="parent_select" 
								@if( $dialog_open)
									checked
								@endif/>
								@endif
							</td>
							<td style="width:140px;">
								@foreach ($row['student_list'] as $student_row)
								{{--	@if( $auths['student_detail'] == 1 ) --}}
									<a class="text_link" href="{{$_app_path}}invoice/detail?id={{$row['id']}}">
										{{$student_row['student_name']}}
									</a><br/>
								{{--	@else
									<label>{{$student_row['student_name']}}</label>
									@endif --}}
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
									<li class="bill3">{{$lan::get('invoiced_title2')}}</li>
								@endif
								@if( $row['active_flag'] != 1 or $row['workflow_status'] < 31)
									<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
								@else
									<li class="bill4">{{$lan::get('payment_already_title')}}</li>
								@endif
								</ul>

							</td>
							<td style="width:110px;text-align:right;">
								@if( $row['amount_display_type'] == "0" or !$row['sales_tax_rate'])
								{{$row['amount']}}
								@else
									@php
										$x=$row['amount'];
										$y=$row['sales_tax_rate'];
										$amount_tax = x+floor(x*y);
									@endphp								
								{{$amount_tax}}
								@endif
							</td>
							<td style="width:100px;text-align:center;">
								{{$invoice_type[$row['invoice_type']]}}
							</td>
							<td style="width:120px;text-align:center;">
								@if( $row['register_date'])
									{{Carbon\Carbon::parse($row['register_date'])->format('%Y-%m-%dd')}}	
								@endif
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
			<input type="hidden" name="invoice_year_month" value="{{$heads['invoice_year_month']}}"/>
			</form>
			@if( count($invoice_list) > 0)
				<input type="submit" value="{{$lan::get('check_payment_title')}}" id="receive_dialog" class="btn_green"/>
			@endif
		</div> <!-- section_content_in -->
	</div> <!-- section_content -->

	<div id="dialog_confirm" style="display:none;">
		<form method="post" id="dialog_form">
			@foreach ($invoice_ids as $idx => $row)
				<input type="hidden" name="invoice_ids[]" value="{{$row}}"/>
			@endforeach
		<table>
			<tr>
				<td style="width:100px;">
					{{$lan::get('payment_category_title')}}
				</td>
				<td>
					<select name="dialogs_type">
						@foreach($invoice_type as $item)
						<option value="{{$item}}">{{$item}}</option>
						@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<td style="width:100px;">
					{{$lan::get('payment_day_title')}}
				</td>
				<td>
					<input class="DateInput" type="text" name="dialogs_date" value="{{$lan::get('invoice_nowdate')}}"></input>
				</td>
			</tr>
			<tr>
				<td style="width:100px;">
					<input type="checkbox" name="dialogs_receipt" value="1"/>{{$lan::get('receipt_issue_title')}}
				</td>
				<td>
				</td>
			</tr>
		</table>
		</form>
	</div> <!-- dialog_confirm -->

	<div id="dialog_receive_check" style="display:none;">
		{{$lan::get('payment_already_search_title')}}
	</div> <!-- dialog_receive_check -->
@stop


