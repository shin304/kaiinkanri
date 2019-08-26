@extends('_parts.master_layout') @section('content')
<script type="text/javascript" src="/js{{$_app_path}}combodate.js"></script>
<script type="text/javascript">
$(function() {
	$('#datetime12').combodate({value:  moment().format('hh:mm a')}); 
});
		$(function() {
			$(".mail_send_button").click(function(e) {
				var link = $('.mail_send_button').attr('href');
				e.preventDefault();
			    $( "#dialog_mail_send" ).dialog({
			      dialogClass: "noTitle",
			      title: "{{$lan::get('send_invoice_title')}}",
			      autoOpen: false,
			      resizable: false,
			      height:140,
			      modal: true,
			      buttons: {
			        "{{$lan::get('ok_title')}}": function() {
			          java_post(link);
			          $( this ).dialog( "close" );
			        },
			        "{{$lan::get('cancel_title')}}": function() {
			          $( this ).dialog( "close" );
			        }
			      }
			    });
			    $("#dialog_mail_send").dialog("open");
			});
		});
		
		  $(function() {
	        /* $(".tablesorter").tablesorter( {
	              headers: {
	                  0: { sorter: false },
	                  8: { sorter: false }
	              }
	          } );*/
	     });

		  $(function() {

			$('#search_cond_clear').click(function() {  // clear
				$("select[name='mail_announce']").val("");
				$("select[name='invoice_year_from']").val("");
				$("select[name='invoice_month_from']").val("");
				$("select[name='invoice_year_to']").val("");
				$("select[name='invoice_month_to']").val("");
				$("input[name='not_requested']").prop("checked",false);
				$("select[name='workflow_status']").val("");
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

		  function setworkflow(){
		      $('#selectall').prop("checked", false);
	  		  $('.parent_select').each(function(){
	  			  if(this.checked){
					  this.checked = false;
					  $(this).parent("td").parent("tr").children("#status").children("#workflow").text("{{$lan::get('invoice_already_title')}}");
	  			  }
	  		  });
		  }

		  function getid(){
			  var parent_ids = [];
			  var i=0;
	  		  $('.parent_select').each(function(){
	  			  if(this.checked){
					  parent_ids[i] = $(this).val();
					  i++;
	  			  }
	  		  });
	  		  if( i == 0 ){
		  		  return false;
	  		  }
	  		  return parent_ids;
		  }

		  $(function(){
			 $("#btn_confirm").click(function(e){
		        	$(".message_area").remove();

		        	var content = '';
		        	if ($('#schedule_flag').is(':checked')) {
		                content = '{{$lan::get('booking_time_send_title')}}:';
		                $('.schedule_date').each(function(){
		                    content += ' '+$(this).val();
		                });
		                content += ' <br>';
		            }
					
		            if( getid() == false ){
				    	html="<ul class='message_area'><li class='error_message' onclick='$(this).remove();' style='cursor: pointer;'>{{$lan::get('invoice_not_selected_title')}}</li></ul>";
			        	$("#data_table").before(html);
			        	return false;
					}
	        		$( "#dialog_pdf" ).dialog({
		  			      dialogClass: "noTitle",
		  			      title: "{{$lan::get('send_invoice_title')}}",
		  			      autoOpen: false,
		  			      resizable: false,
		  			      width:400,
		  			      height:170,
		  			      modal: true,
		  			      buttons: {
		  			        "{{$lan::get('ok_title')}}": function() {
		  						 e.preventDefault();
		  						 makePdf();
		  			        	 $( this ).dialog( "close" );
		  			        },
		  			        "{{$lan::get('cancel_title')}}": function() {
		  			          $( this ).dialog( "close" );
		  			        }
		  			      }
	        			});
		  			    $("#dialog_pdf").dialog("open");
		  			  $("#dialog_pdf").html(content + "{{$lan::get('invoice_will_print_send_confirm_title')}}");
			 });
		  });

		  	    function makePdf(id, mode) {
		  	    	    //$("#dialog_pdf button:contains('OK')").buttons("disable"); // 押せないようにする
		  	    	    $("#dialog_pdf button:contains('{{$lan::get('cancel_title')}}')").button("disable"); // 押せないようにする
		  	    	    //var defer = $.Deferred();
		  	    	    var show_flag = 0;
				    	var parent_ids = getid();
				    	//alert(parent_ids.length);
		        	    $("#dialog_pdf").html("");
				    	html="{{$lan::get('in_progress_title')}}";
						$("#dialog_pdf").append(html);
						var schedule_date = '';
						if ($('#schedule_flag').is(':checked')) {
			                $('.schedule_date').each(function(){
			                	schedule_date += ' '+$(this).val();
			                });
			            }
				    	for( var i=0; i<parent_ids.length; i++) {
					    	//alert(parent_ids[i]);
					    	(function( i ){
					        $.ajax({
					            url: "{{$_app_path}}ajaxInvoice/mailsend",
					            data: {
					                //parent_ids: parent_ids,
					                parent_ids: parent_ids[i],
					                schedule_date: schedule_date,
					                mode: mode
					            },
					            dataType: 'json',
					            async: false,
					            beforeSend: function(xhr){
					            	$(".message_area").remove();
					            	$("#loading").show();
					            },
					            //success: defer.resolve,
					            success: function(data){
						            		show_flag += data.id;
						            		html="**";
								        	$("#dialog_pdf").append(html);
                                        },
					            //error: defer.reject,
					            error: function(){
						            		//alert('エラー発生！！');
					                   },
					            complete: function() {
					            	$("#loading").hide();
					            }
					        });
					    	})( i );
				        	//html="<ul class='message_area'><li class='info_message' onclick='$(this).remove();' style='cursor: pointer;'>"+(i+1)+"ページ処理中</li></ul>";
				        	//$("#data_table").before(html);
				    	}
		        	    $("#dialog_pdf").html("");
				    	$("#dialog_pdf").html("{{$lan::get('invoice_will_print_send_confirm_title')}}");
				    	setworkflow();
				    	if( show_flag > 0 ){
		  			        window.open("{{$_app_path}}ajaxInvoice/show?id="+i);
				    	}
			        	html="<ul class='message_area'><li class='info_message' onclick='$(this).remove();' style='cursor: pointer;'>{{$lan::get('invoice_was_print_send_confirm_title')}}</li></ul>";
			        	$("#data_table").before(html);
				    	//return defer.promise();
		  	    	};
	</script>
	<style type="text/css">
		#wrapper .search_box td {
			font-weight: bold;
			vertical-align: middle;
		}
	</style>

		{{-- メニュー  --}}
		{{-- include file="pages_pc/school/_parts/invoice/axis_menu.html" --}}
	@include('_parts.invoice.axis_menu')
		{{-- パンくず  --}}
		{{-- include file="pages_pc/school/_parts/topic_list.html" --}}
@include('_parts.topic_list')
	<div id="section_content">
		<div class="alart_box box_shadow">
			<ul class="message_area">
			@if( isset($action_status) && $action_status == "OK")
				<li class="info_message">{{$action_message}}</li>
			@elseif( isset($action_status))
				<li class="error_message">{{$action_message}}</li>
			@else
				<pre>{{$lan::get('select_payment_record_send_title')}}</pre>
			@endif
			</ul>
			<div id="data_table"></div>
		</div>
		
		<div id="section_content_in">
		&nbsp;<input type="checkbox" id="selectall">&nbsp;&nbsp;{{$lan::get('select_all_title')}}</input><br/>
		<form id="action_form" action="{{$_app_path}}ajaxinvoice/mailsend" method="post">
			<table class="table_list tablesorter body_scroll_table">
				<thead>
					<tr>
						<th style="width: 50px;" class="text_title">{{$lan::get('selection_title')}}</th>
						<th style="width:120px;" class="text_title header">{{$lan::get('member_name_title')}}</th>
						<th style="width:350px;" class="text_title header">{{$lan::get('status_title')}}</th>
						
						<th style="width:100px;" class="text_title header">{{$lan::get('bank_type')}}</th>
						<th style="width:100px;" class="text_title header">{{$lan::get('send_date')}}</th>
						
						<th style="width:110px;" class="text_title header">{{$lan::get('invoice_amount_title')}}</th>
						<th style="width:100px;" class="text_title header">{{$lan::get('invoice_method_title')}}</th>
						<th style="width:120px;" class="text_title header">{{$lan::get('create_date_title')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($invoice_list as $idx => $row)
						<tr class="table_row">
							<td style="width:50px;text-align:center;">
								@if( array_get($row,'is_established') == 1)
								<input type="checkbox" name="parent_ids[]" value="{{array_get($row,'id')}}" class="parent_select"></input>
								@endif
							</td>
							<td style="width:120px;">
								@foreach(array_get($row,'student_list') as $student_row)
									{{-- @if( array_get($auths,'student_detail') == 1) --}}
									<a class="text_link" href="{{$_app_path}}invoice/detail?id={{array_get($row,'id')}}">
										{{array_get($student_row,'student_name')}}
									</a><br/>
								{{-- 	@else 
									<label>{{$student_row.student_name}}</label>
									@endif  --}}
								@endforeach
							</td>
							<td style="width:350px;text-align:center;">
								<ul class="progress_ul ">
								@if( array_get($row,'active_flag') != 1 || array_get($row,'workflow_status') < 0)
								
									<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
								@else
									<li class="bill1">{{$lan::get('status_imported_title')}}</li>
								@endif
								@if( array_get($row,'active_flag') != 1 || array_get($row,'workflow_status') < 1)
									<li class="bill2 no_active">{{$lan::get('uncreated_title')}}</li>
								@else
									<li class="bill2">{{$lan::get('confirmed_title')}}</li>
								@endif
								@if( array_get($row,'active_flag') != 1 || array_get($row,'workflow_status') < 11)
									<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
								@else
									<li class="bill3">{{$lan::get('invoiced_title2')}}</li>
								@endif
								@if( array_get($row,'active_flag') != 1 || array_get($row,'workflow_status') < 31)
									<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
								@else
									<li class="bill4">{{$lan::get('payment_already_title')}}</li>
								@endif
								</ul>
							</td>
							
							<td style="width:100px;text-align:center;">
								{{array_get($row,'bank_type')}}
							</td>
							<td style="width:120px;text-align:center;">
								@if( array_get($row,'send_date')) 
								{{ Carbon\Carbon::parse(array_get($row,'send_date'))->format('Y-m-d') }}
								@endif
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
								{{$invoice_type[array_get($row,'invoice_type')]}}
							</td>
							<td style="width:100px;text-align:center;">
								@if( array_get($row,'register_date')) 
								{{ Carbon\Carbon::parse(array_get($row,'register_date'))->format('Y-m-d') }}
								@endif
							</td>
						</tr>
					@endforeach
					@if(empty($invoice_list))
					<tr class="table_row">
							<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
						</tr>
					@endif
				</tbody>
			</table>
			<br />
			<div>
                    <div>{{$lan::get('setting_send_mail_time_title')}}</div>
                    <input type="checkbox" name="schedule_flag" id="schedule_flag">&nbsp;&nbsp;<label for="schedule_flag">{{$lan::get('booking_time_send_title')}}</label>
                    <div style="word-spacing: 10px;">
                        <label>{{$lan::get('day_send_title')}}</label>&nbsp;
                        <input type="text" id="datepicker" class="DateInput schedule_date" name="schedule_date[]" value="{{request('schedule_date')}}">
                        &nbsp;&nbsp;
                        <label>{{$lan::get('time_send_title')}}</label>&nbsp;
                        <input type="text" id="datetime12" data-format="HH:mm" data-template="HH : mm" name="schedule_date[]" value="" class="schedule_date">
                    </div>
				</div>
				<br />
                </form>
			@if( count($invoice_list) > 0)
			<input type="submit" value="{{$lan::get('print_send_invoice_btn_title')}}" id="btn_confirm" class="btn_green"/>
			@endif
			
		</div> <!-- section_content_in -->
	</div> <!-- section_content -->

<div id="dialog_mail_send" style="display:none;">
	{{$lan::get('send_mail_confirm_title')}}
</div>
<div id="dialog_pdf" style="display:none;">
	{{-- PDFを新しいウィンドウで開きます。 --}}
	<p id="contentholder">{{$lan::get('invoice_will_print_send_confirm_title')}}</p>
</div>

<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
  
@stop

