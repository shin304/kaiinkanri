@extends('_parts.master_layout') @section('content')

	<script type="text/javascript">
	 $(function() {
         /*$(".tablesorter").tablesorter( {
              headers: {
                  {{**7: { sorter: false },**}}
                  8: { sorter: false },
              }
          } );*/

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

			$('#current_month').click(function() {

				$.get(
					"{{$_app_path}}ajaxInvoice/newest_year_month",
					{},
					function(data)
					{
						var curr_year  = data.curr_year;
						var curr_month = data.curr_month;
						$("select[name='invoice_year_from']").val(curr_year);
						$("select[name='invoice_month_from']").val(curr_month);
						$("select[name='invoice_year_to']").val(curr_year);
						$("select[name='invoice_month_to']").val(curr_month);
					},
					"jsonp"
				);
			});

			$('#search_cond_clear').click(function() {  // clear
				$("select[name='invoice_year_from']").val("");
				$("select[name='invoice_month_from']").val("");
				$("select[name='invoice_year_to']").val("");
				$("select[name='invoice_month_to']").val("");
				$("select[name='status_flag']").val("");
			});

		});
	</script>
	<style type="text/css">
		#wrapper .search_box td {
			font-weight: bold;
			vertical-align: middle;
		}
	</style>

<!-- 		 メニュー  -->
		@include('_parts.invoice.axis_menu')

<!-- 		 パンくず  -->
		{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('invoice_entry') !!}</div> --}}
	@include('_parts.topic_list')

		@if( $action_status)
		<div class="alart_box box_shadow">
			<ul class="message_area">
			@if( $action_status and $action_status == "OK")
				<li class="info_message">{{$lan::get('action_message')}}</li>
			@elseif( $action_status)
				<li class="error_message">{{$lan::get('action_message')}}</li>
			@endif
			</ul>
			<div id="data_table"></div>
		</div>
		@endif

		<div id="section_content1">
			<table class="table_list">
			<thead>
					<tr>
						<th style="width:100px;" class="text_title">{{$lan::get('invoice_year_month_title')}}</th>
						<th style="width:100px;" class="text_title">{{$lan::get('notice_date_title')}}</th>
						<th style="width:110px;" class="text_title">{{$lan::get('request_day_title')}}</th>
						<th style="width:100px;" class="text_title">{{$lan::get('submit_deadline_title')}}</th>
						<th style="width:90px;" class="text_title">{{$lan::get('file_name_title')}}</th>
						<th style="width:80px;" class="text_title">{{$lan::get('invoice_number_title')}}</th>
						<th style="width:110px;" class="text_title">{{$lan::get('invoice_amount_title')}}</th>
						<th style="width:130px;" class="text_title">{{$lan::get('status_title')}}</th>
						<th style="width:60px;text-align:center;" class="text_title">{{$lan::get('process_title')}}</th>

					</tr>
				</thead>
				<tbody>
					@foreach ($transfer_list as $idx => $row)
						<tr class="table_row">
							<td style="width:100px;text-align:center;">{{Carbon\Carbon::parse(array_get($row,'invoice_year_month'))->format('%Y年%m月')}}</td>
							<td style="width:100px;text-align:center;">{{Carbon\Carbon::parse(array_get($row,'result_date'))->format('%m月%d日')}}</td>
							<td style="width:110px;text-align:center;">{{Carbon\Carbon::parse(array_get($row,'register_date'))->format('%m月%d日')}}</td>
							<td style="width:100px;text-align:center;">{{Carbon\Carbon::parse(array_get($row,'deadline'))->format('%m月%d日')}}</td>
							<td style="width:90px;text-align:center;">{{substr(array_get($row,'processing_filename'),0,8)}}</td>
							<td style="width:80px;text-align:right;">{{number_format(array_get($row,'total_cnt'))}}</td>
							<td style="width:110px;text-align:right;">{{number_format(array_get($row,'total_amount'))}}</td>
							<td style="width:130px;text-align:center;">{{array_get($requesttable_status,'row.status_flag')}}</td>
							@if( (Carbon\Carbon::parse(array_get($row,'deadline'))->format('%Y-%m-%d')) > (Carbon\Carbon::parse(Carbon::now())->format('%Y-%m-%d')))
							<td style="width:60px;text-align:center;">
								@if( array_get($row, 'status_flag') < 3 )
									<a class="cancel_button" href="{{$_app_path}}invoice/canceltransfer?invoice_year_month={{array_get($heads,'invoice_year_month')}}&file_name={{array_get($row,'processing_filename')}}">
									{{$lan::get('cancel_btn_title')}}
									</a>
								@endif
							</td>
							@endif
						</tr>
					@endforeach
					@if(!isset($transfer_list))
					<tr class="table_row">
							<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
					</tr>
					@endif
				</tbody>
			</table>

	<div id="dialog_cancel" class="no_title" style="display:none;">
		{{$lan::get('cancel_confirm_title')}}
	</div> <!-- dialog_receive_check -->
@stop
