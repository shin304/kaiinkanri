<style>
    a {
    text-decoration: underline;
    color: blue;
    }
</style>
<script type="text/javascript">
$(function() {
	// 請求書年月取得
	
	$("#new_invoice_generate").click(function() {
		console.log(1);
		var currentTime = new Date();
 		var month = currentTime.getMonth() + 1;
 		var year = currentTime.getFullYear();

 		$("#dialog_new_generate").dialog({
 			dialogClass: "noTitle",
 			title: "新規請求書作成",
 			autoOpen: false,
 			resizable: false,
 			width: 600,
 			modal: true,
 			buttons: {
 				"OK": function() {
 					var invoice_year_month = $('select[name="invoice_year_month"]').val();
 					var link = "{{$_app_path}}invoice?menu&invoice_year_month=" + invoice_year_month;
 					
//  					java_post(link);
 					$('#dialog_form').submit();
 					$( this ).dialog( "close" );
 				},
 				"キャンセル": function() {
 					$( this ).dialog( "close" );
 				}
 			}
 		});
 		$("#dialog_new_generate").dialog("open");
	});

	
	@if(isset($heads))
		var year = "{{$heads['invoice_year']}}";
		var month = "{{$heads['invoice_month']}}";
	@endif
		var year_month = year + "-" + month;
		var day = [year,month];
		$("#invoice_create").click(function() {
			if (!$(this).hasClass("clicked")) {
				$(this).addClass("clicked");
				show_url_dialog("/school/invoice/parentselect?invoice_year_month="+year_month, {
					title: "{{$lan::get('select_guardian_create_invoice_title')}}",
					width: "510px",
					open_callback: function() {
						$("#invoice_create").removeClass("clicked");
					}
				});
			}
			return false;
		});

		
		$("#invoice_generate").click(function(e) {
			e.preventDefault();
			$.get(
				// 作成対象の年月分取得  とりあえず関数呼んでいるが、戻り値は使わない
				"{{$_app_path}}ajaxInvoice/getinvoiceyearmonth",
				{},
		 		function(data)
		 		{

					var string =("{{$lan::get('confirm_create_batch_title')}}".replace(/%m/g,month)).replace(/%Y/g,year);

		 			var msg = "<p>" + string + "</p>";
		 			var link = "{{$_app_path}}invoice/generate?menu&invoice_year_month=" + year_month;
		 			$("#dialog_generate").children("p").remove();
		 			$("#dialog_generate").append(msg);
		 			$("#dialog_generate").dialog({
		 				dialogClass: "noTitle",
		 				title: "{{$lan::get('invoice_batch_title')}}",
		 				autoOpen: false,
		 				resizable: false,
		 				height:140,
		 				width: 400,
		 				modal: true,
		 				buttons: {
		 					"{{$lan::get('ok_title')}}": function() {
// 		 						window.location.href = link;
		 						java_post(link);
		 						$( this ).dialog( "close" );
		 					},
		 					"{{$lan::get('cancel_title')}}": function() {
		 						$( this ).dialog( "close" );
		 					}
		 				}
		 			});
		 			$("#dialog_generate").dialog("open");
				},
				"jsonp"
			);
		});

	
});
</script>

	<div id="center_content_header" >
		<div class="c_content_header_left">
		@if( isset($heads))
			<h2 class="float_left">{{Carbon\Carbon::parse(array_get($heads,'invoice_year_month'))->format('Y年m月分請求書')}}</h2>
		@else
			<h2 class="float_left"><i class="fa fa-fax"></i>{{$lan::get('invoice_management_title')}}</h2>
			<div class="center_content_header_right">
				<div class="top_btn">
					<ul>
						{{-- @if( $edit_auth[8] == 1) --}}
							<a href="#" id="new_invoice_generate">
							<li><i class="fa fa-plus"></i>{{$lan::get('new_invoice_create_title')}}</li></a>
					{{--	@endif --}}
					</ul>
				</div>
			</div>
			@endif
		</div><!--.c_content_header_left-->

		@if( isset($heads))
			<ul class="progress_ul ">
				{{-- @if( $edit_auth[8] == 1) --}}
				<a class="text_link" href="{{$_app_path}}invoice/search?simple&search_cond=2&invoice_year_month={{$heads['invoice_year_month']}}&invoice_year_to_s={{$heads['invoice_year']}}&invoice_month_to_s={{$heads['invoice_month']}}&invoice_year_from_s={{$heads['invoice_year']}}&invoice_month_from_s={{$heads['invoice_month']}}">
				{{-- @endif --}}
				@if( array_get($heads,'cnt_entry'))
				<li class="bill1">{{$lan::get('status_imported_title')}}[{{array_get($heads,'cnt_entry')}}]</li>
				@else
				<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
				@endif
				{{-- @if( $edit_auth[8] == 1) --}}
				</a>
				{{-- @endif  --}}
				<li class="bill2 no_active" style="width: 20px; color: black; font-weight: bolder;">&gt;</li>
				{{-- @if( $edit_auth[8] == 1) --}}
				<a class="text_link" href="{{$_app_path}}invoice/search?simple=true&search_cond=2&invoice_year_month={{$heads['invoice_year_month']}}&invoice_year_to_s={{$heads['invoice_year']}}&invoice_month_to_s={{$heads['invoice_month']}}&invoice_year_from_s={{$heads['invoice_year']}}&invoice_month_from_s={{$heads['invoice_month']}}">
				{{-- @endif --}}
				@if( array_get($heads,'cnt_confirm'))
				<li class="bill2">{{$lan::get('confirmed_title')}}[{{array_get($heads,'cnt_confirm')}}]</li>
				@else
				<li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
				@endif
				{{-- @if( $edit_auth[8] == 1) --}}
				</a>
				{{-- @endif --}}
				<li class="bill2 no_active" style="width: 20px; color: black; font-weight: bolder;">&gt;</li>
				{{-- @if( $edit_auth[8] == 1)  --}}
				<a class="text_link" href="{{$_app_path}}invoice/mailsearch?invoice_year_month={{array_get($heads,'invoice_year_month')}}&invoice_year_to={{array_get($heads,'invoice_year')}}&invoice_month_to={{array_get($heads,'invoice_month')}}&invoice_year_from={{array_get($heads,'invoice_year')}}&invoice_month_from={{array_get($heads,'invoice_month')}}">
				{{-- @endif --}}
				@if( array_get($heads,'cnt_send'))
				<li class="bill3">{{$lan::get('invoiced_title2')}}[{{array_get($heads,'cnt_send')}}]</li>
				@else
				<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
				@endif
				{{-- @if( $edit_auth[8] == 1) --}}
				</a>
				{{-- @endif  --}}
				<li class="bill2 no_active" style="width: 20px; color: black; font-weight: bolder;">&gt;</li>
				{{-- @if( $edit_auth[8] == 1)  --}}
				<a class="text_link" href="{{$_app_path}}invoice/receivesearch?invoice_year_month={{$heads['invoice_year_month']}}&invoice_year_to={{$heads['invoice_year']}}&invoice_month_to={{$heads['invoice_month']}}&invoice_year_from={{$heads['invoice_year']}}&invoice_month_from={{$heads['invoice_month']}}">
				{{-- @endif --}}
				@if( array_get($heads,'cnt_complete'))
				<li class="bill4">{{$lan::get('payment_already_title')}}[{{array_get($heads,'cnt_complete')}}]</li>
				@else
				<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
				@endif
				{{-- @if( $edit_auth[8] == 1) --}}
				</a>
				{{-- @endif  --}}
			</ul>
		@endif
		<div class="c_content_header_right">
		<ul class="btn_ul">
			{{-- @if( $this_screen == 'detail' and $request['is_recieved'] != 1 and $request['workflow_status'] <= 1 and $edit_auth[8] == 1)
				@if($auths['invoice_edit'] == 1) --}}
				@if(!empty($this_screen))
				@if( $this_screen == 'detail' and $request['is_recieved'] != 1 and $request['workflow_status'] <= 1)
				<li class="no_active" style="background:#25b4c6;">
				<a class="edit_btn" href="#edit">{{$lan::get('edit_title')}}</a>
				</li>
				{{-- @endif  --}}
				
				{{-- @if($auths['invoice_delete'] == 1 && $request['is_established'] == 0) --}}
				@if($request['is_established'] == 0)
				<li class="no_active" style="background:#25b4c6;">
				<a class="delete_btn" href="#delete">{{$lan::get('delete_title')}}</a>
				</li>
				@endif
			@endif

			@if( $this_screen == 'search')
			<li class='popup'>
			<a class="">{{$lan::get('create_invoice_title')}}</a>
				<ul class='popup__body'>
					<li class='popup__list'>
					<a href="#" id="invoice_generate">{{$lan::get('create_invoice_general_title')}}</a>
					</li>
					<li class='popup__list'>
					<a href="#" id="invoice_create">{{$lan::get('create_invoice_particular_title')}}</a>
					</li>
				</ul>
			</li>
			@endif

			@if( $withdrawal_day and ($this_screen == 'search' or $this_screen == 'mailsearch' or $this_screen == 'receivesearch' or $this_screen == 'zengin' or $this_screen == 'upload' or $this_screen == 'download'))
			<li class='popup'>
			<a class="">{{$lan::get('account_tranfer_title')}}</a>
				<ul class='popup__body'>
					<li class='popup__list'>
					<a  href="{{$_app_path}}invoice/accounttransfer?search&invoice_year_month={{$heads['invoice_year_month']}}&invoice_year_to={{$heads['invoice_year']}}&invoice_month_to={{$heads['invoice_month']}}&invoice_year_from={{$heads['invoice_year']}}&invoice_month_from={{$heads['invoice_month']}}">{{$lan::get('account_tranfer_title')}}</a>
					</li>
					@if( $invoice_transfer_operation)
						<li class='popup__list'>
						<a  href="{{$_app_path}}invoice/download?invoice_year_month={{$heads['invoice_year_month']}}&invoice_year_to={{$heads['invoice_year']}}&invoice_month_to={{$heads['invoice_month']}}&invoice_year_from={{$heads['invoice_year']}}&invoice_month_from={{$heads['invoice_month']}}">{{$lan::get('create_account_request_title')}}</a>
						</li>
						<li class='popup__list'>
						<a  href="{{$_app_path}}invoice/upload?invoice_year_month={{$heads['invoice_year_month']}}&invoice_year_to={{$heads['invoice_year']}}&invoice_month_to={{$heads['invoice_month']}}&invoice_year_from={{$heads['invoice_year']}}&invoice_month_from={{$heads['invoice_month']}}">{{$lan::get('create_acount_capture_title')}}</a>
						</li>
					@endif
				</ul>
			</li>
			@endif
			@endif
		</ul>

		</div><!--.c_content_header_right-->
		<div class="clr"></div>
	</div><!--center_content_header-->

<div id="dialog_generate" style="display:none;">
	
</div> <!-- dialog_receive_check -->

<div id="dialog_new_generate" style="display:none;">
	<form method="post" id="dialog_form">
	{{ csrf_field() }}
		<table>
			<tr>
				<td style="width:300px;">
					作成したい請求の年月を指定してください
				</td>
				<td>
					<select name="invoice_year_month">
						{{-- @if(isset($select_year_month_list))  --}}
						@foreach($select_year_month_list as $key => $item)
						{{-- <option value="{{$item}}">{{$item}}</option>  --}}
						@if($select_year_month == $key)
						<option value="{{$item}}" selected="selected" @endif>{{$item}}</option>
						<option value="{{$item}}">{{$item}}</option>
						@endforeach
						{{-- @endif  --}}
					</select>
				</td>
			</tr>
		</table>
	</form>
</div> <!-- dialog_confirm -->

