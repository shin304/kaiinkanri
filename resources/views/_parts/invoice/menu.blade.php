<script type="text/javascript">
$(function() {
	$("#invoice_generate").click(function(e) {
		e.preventDefault();

		$.get(
			// 作成対象の年月分取得
			"{{$_app_path}}ajaxInvoice/getinvoiceyearmonth",
			{},
	 		function(data)
	 		{
	 			// 請求書年月取得
	 			var year = data['year'];
	 			var month = data['month'];
	 			var year_month = data['year'] + "-" + data['month'];
	 			var day = [data['year'],data['month']];

// 	 			var string = year + "年" + month +"月分の請求書を一括作成しますか？";
				var string =("{{$lan::get('confirm_create_batch_title')}}".replace(/%m/g,month)).replace(/%Y/g,year);

	 			var msg = "<p>" + string + "</p>";
	 			var link = $('#invoice_generate').attr('href') + "&invoice_year_month=" + year_month;
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
	 						//window.location.href = link;
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

	$("#invoice_create").click(function() {
		if (!$(this).hasClass("clicked")) {
			$(this).addClass("clicked");
			show_url_dialog("/school/invoice/parentselect", {
				title: "{{$lan::get('select_guardian_create_invoice_title')}}",
				width: "510px",
				open_callback: function() {
					$("#invoice_create").removeClass("clicked");
				}
			});
		}
		return false;
	});
});
</script>

<div class="center_content_header_right">
		@if($edit_auth[8] == 1)
		<div class="top_btn">
			<ul>
			@if(isset($this_screen))
			@if( $this_screen == 'invoicemanage')
				<a href="{{$_app_path}}invoice/?menu"><li><i class="fa fa-search"></i>{{$lan::get('invoice_search_title')}</li></a>
				<a href="{{$_app_path}}invoice/receivesearch?menu"><li  style="margin-right: 100px;"><i class="fa fa-yen"></i>{{$lan::get('process_payment_title')}</li></a>
				<a href="{{$_app_path}}invoice/mailsearch/?menu"><li><i class="fa fa-envelope-o"></i>{{$lan::get('send_invoice_title')}</li></a>
			@elseif( $this_screen == 'mailsearch')
				<a href="{{$_app_path}}invoice/?menu"><li><i class="fa fa-search"></i>{{$lan::get('invoice_search_title')}</li></a>
				@if( $withdrawal_day != null)
					<a href="{{$_app_path}}invoice/accounttransfer?menu"><li><i class="fa fa-yen"></i>{{$lan::get('account_tranfer_title')}</li></a>
				@endif
				<a href="{{$_app_path}}invoice/receivesearch?menu"><li><i class="fa fa-yen"></i>{{$lan::get('cancel_tiprocess_payment_titletle')}</li></a>
			@elseif( $this_screen == 'receivesearch')
				<a href="{{$_app_path}}invoice/?menu"><li><i class="fa fa-search"></i>{{$lan::get('invoice_search_title')}</li></a>
				<a href="{{$_app_path}}invoice/mailsearch/?menu"><li><i class="fa fa-envelope-o"></i>{{$lan::get('send_invoice_title')}</li></a>
				@if( $withdrawal_day != null)
					<a href="{{$_app_path}}invoice/accounttransfer?menu"><li><i class="fa fa-yen"></i>{{$lan::get('account_tranfer_title')}</li></a>
				@endif
			@elseif( $this_screen == 'zengin')
				<a href="{{$_app_path}}invoice/?menu"><li><i class="fa fa-search"></i>{{$lan::get('invoice_search_title')}</li></a>
				<a href="{{$_app_path}}invoice/mailsearch/?menu"><li><i class="fa fa-envelope-o"></i>{{$lan::get('send_invoice_title')}</li></a>
				<a href="{{$_app_path}}invoice/receivesearch?menu"><li style="margin-right:100px;"><i class="fa fa-yen"></i>{{$lan::get('process_payment_title')</li></a>
				<a href="{{$_app_path}}invoice/download?menu"><li><i class="fa fa-yen"></i>{{$lan::get('create_account_request_title')}</li></a>
				<a href="{{$_app_path}}invoice/upload?menu"><li><i class="fa fa-yen"></i>{{$lan::get('create_acount_capture_title')}</li></a>
			@elseif( $this_screen == 'upload')
				<a href="{{$_app_path}}invoice/?menu"><li><i class="fa fa-search"></i>{{$lan::get('invoice_search_title')}</li></a>
				<a href="{{$_app_path}}invoice/mailsearch/?menu"><li><i class="fa fa-envelope-o"></i>{{$lan::get('send_invoice_title')</li></a>
				<a href="{{$_app_path}}invoice/accounttransfer?menu"><li><i class="fa fa-yen"></i>{{$lan::get('account_tranfer_title')}</li></a>
				<a href="{{$_app_path}}invoice/receivesearch?menu"><li><i class="fa fa-yen"></i>{{$lan::get('process_payment_title')}</li></a>
			@elseif( $this_screen == 'download')
				<a href="{{$_app_path}}invoice/?menu"><li><i class="fa fa-search"></i>{{$lan::get('invoice_search_title')}</li></a>
				<a href="{{$_app_path}}invoice/mailsearch/?menu"><li><i class="fa fa-envelope-o"></i>{{$lan::get('send_invoice_title')}</li></a>
				<a href="{{$_app_path}}invoice/accounttransfer?menu"><li><i class="fa fa-yen"></i>{{$lan::get('account_tranfer_title')}</li></a>
				<a href="{{$_app_path}}invoice/receivesearch?menu"><li><i class="fa fa-yen"></i>{{$lan::get('process_payment_title')}</li></a>
			@elseif( $this_screen == 'search')
				<a href="{{$_app_path}}invoice/mailsearch/?menu"><li><i class="fa fa-envelope-o"></i>{{$lan::get('send_invoice_title')}</li></a>
				@if( $withdrawal_day != null)
					<a href="{{$_app_path}}invoice/accounttransfer?menu"><li><i class="fa fa-yen"></i>{{$lan::get('account_tranfer_title')}</li></a>
				@endif
				<a href="{{$_app_path}}invoice/receiveSearch/?menu"><li style="margin-right: 100px;"><i class="fa fa-yen"></i>{{$lan::get('process_payment_title')}</li></a>
				<a href="{{$_app_path}}invoice/generate/?menu" id="invoice_generate"><li><i class="fa fa-plus"></i>{{$lan::get('create_invoice_general_title')}</li></a>
				<a href="{{$_app_path}}invoice/?menu" id="invoice_create"><li><i class="fa fa-plus-square-o"></i>{{$lan::get('create_invoice_particular_title')}</li></a>
			@else
				<a href="{{$_app_path}}invoice/mailsearch/?menu"><li><i class="fa fa-envelope-o"></i>{{$lan::get('send_invoice_title')}</li></a>
				@if( $withdrawal_day != null)
					<a href="{{$_app_path}}invoice/accounttransfer?menu"><li><i class="fa fa-yen"></i>{{$lan::get('account_tranfer_title')</li></a>
				@endif
				<a href="{{$_app_path}}invoice/receiveSearch/?menu"><li style="margin-right: 100px;"><i class="fa fa-yen"></i>{{$lan::get('process_payment_title')}</li></a>
				<a href="{{$_app_path}}invoice/generate/?menu" id="invoice_generate"><li><i class="fa fa-plus"></i>{{$lan::get('create_invoice_general_title')}</li></a>
				<a href="{{$_app_path}}invoice/?menu" id="invoice_create"><li><i class="fa fa-plus-square-o"></i>{{$lan::get('create_invoice_particular_title')}</li></a>
			@endif
			@endif
			</ul>
		</div>
		@endif
</div>
<div id="dialog_generate" style="display:none;">
	</div> <!-- dialog_receive_check -->