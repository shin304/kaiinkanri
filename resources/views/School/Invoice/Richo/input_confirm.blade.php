@extends('_parts.master_layout') @section('content')
	
@include('_parts.invoice.axis_menu')
	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('broadcastmail') !!}</div> --}}
		@include('_parts.topic_list')
	<h3 id="content_h3" class="box_border1">{{$lan::get('more_information_edit_title')}}</h3>
	
	<div id="section_content">
		@if( isset($is_new) and isset($is_invoice_exist))
		<div class="alart_box box_shadow">
			<ul class="message_area">
				<li class="info_message">{{$lan::get('invoice_current_month_title')}}</li>
			</ul>
		</div>
		@endif
		@if(isset($is_new))
		<form action="{{$_app_path}}invoice/entrycomplete" method="post" class="search_form">
		{{ csrf_field() }}
		@else
		<form action="{{$_app_path}}invoice/editcomplete" method="post" class="search_form">
		{{ csrf_field() }}
		@endif
			<input type="hidden" name="parent_id" value="{{$request['parent_id']}}"/>
			<input type="hidden" name="invoice_year_month" value="{{$request['invoice_year_month']}}"/>
			<input type="hidden" name="invoice_year" value="{{$request['invoice_year']}}"/>
			<input type="hidden" name="invoice_month" value="{{$request['invoice_month']}}"/>

			<input type="hidden" name="parent_memo" value="{{$request['parent_memo']}}"/>
			<input type="hidden" name="due_date" value="{{$request['due_date']}}"/>
			<input type="hidden" name="now_date_jp" value="{{$request['now_date_jp']}}"/>
			<input type="hidden" name="due_date_jp" value="{{$request['due_date_jp']}}"/>
			<input type="hidden" name="sales_tax_disp" value="{{$request['sales_tax_disp']}}"/>
			<input type="hidden" name="pbank_info" value="{{$request['pbank_info']}}"/>

			<div id="center_content_main">
				@include('_parts.invoice.axis_form')
			</div>
			<br/>
			
			<div id="section_content_in">
			@if(isset($is_new))
				<input class="btn_green" id="btn_return" type="submit" name="back_button" value="{{$lan::get('return_title')}}" onclick="form.action='{{$_app_path}}invoice/entry?back';"/>
			@else
				<input class="btn_green" id="btn_return" type="submit" name="back_button" value="{{$lan::get('return_title')}}" onclick="form.action='{{$_app_path}}invoice/edit?back';"/>
			@endif
				<input class="btn_green" id="btn_submit" type="submit" name="confirm_button" value="{{$lan::get('save_title')}}" />
			</div>
		</form>
	</div>
@stop
