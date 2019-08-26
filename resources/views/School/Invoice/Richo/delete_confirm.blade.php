@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
	/* 削除ボタン */
	$("#delete_button").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}invoice/deletecomplete');
		$("#action_form").submit();
		return false;
	});
	/* 無効ボタン */
	$("#disabled_button").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}invoice/disabledcomplete');
		$("#action_form").submit();
		return false;
	});
});
</script>

	   	<div id="center_content_header" class="box_border1">
			<h2 class="float_left">{{$lan::get('main_title')}}</h2>
			@include('_parts.invoice.menu')
		<div class="clr"></div>
	</div><!--center_content_header-->

				<h3 id="content_h3" class="box_border1">{{$lan::get('invoice_confirmation_title')}}</h3>
				<div id="content_detail">
					<div id="center_content_main">
						<form action="#" method="post" class="search_form" id="action_form">
							<input type="hidden" name="id" value="{{$request['id']}}">
							<input type="hidden" name="invoice_year_month" value="{{$request['invoice_year_month']}}">

							@if(isset($request['action_status']))
								<ul class="message_area">
									<li class="@if($request['action_status'] == "OK") info_message @else error_message @endif">
										{{$request['action_message']}}
									</li>
								</ul>
								<br/>
							@endif

							@include('_parts.invoice.confirm_form')
							<br>
							<div class="exe_button">
								<input id="btn_return" class="submit3" type="submit" name="back_button" value="{{$lan::get('return_title')}}" onClick="history.back();return false;">
								@if(isset($request['disabled']))
									<input  id="disabled_button"  class="submit2" type="submit" name="disabled_button" value="{{{$lan::get('invalid_title')}}" >
								@else
									<input  id="delete_button"  class="submit2" type="submit" name="confirm_button" value="{{$lan::get('delete_title')}}" >
								@endif
							</div>
						</form>
					</div>
				</div><!-- #main -->
			</section><!-- #center_content -->

@stop

