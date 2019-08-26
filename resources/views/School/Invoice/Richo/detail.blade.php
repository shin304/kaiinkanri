@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
	$("a[href='#edit']").click(function() {
		java_post("{{$_app_path}}invoice/edit?id={{$request['id']}}");
		return false;
	});
	$( "#dialog-confirm" ).dialog({
		title: "{{$lan::get('invoice_delete_title')}}",
		autoOpen: false,
		dialogClass: "no-close",
		resizable: false,
		modal: true,
		buttons: {
			"{{$lan::get('delete_title')}}": function() {
				java_post("{{$_app_path}}invoice/deletecomplete?id={{$request['id']}}&fromedit=1");
				return false;
			},
			"{{$lan::get('cancel_title')}}": function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$("a[href='#delete']").click(function() {
		$( "#dialog-confirm" ).dialog('open');
		return false;
	});
	$("#btn_confirm_invoice").click(function() {
		java_post("{{$_app_path}}invoice/singleEditComplete?parent_id={{$data['id']}}&id={{$request['id']}}");
		return false;
	});
	$("#btn_before").click(function() {
		window.location.href = "{{$_app_path}}invoice/detail/?pre";
		return false;
	});
	$("#btn_after").click(function() {
		window.location.href = "{{$_app_path}}invoice/detail/?next";
		return false;
	});
});
</script>
@include('_parts.invoice.axis_menu')

	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('invoice_detail') !!}</div> --}}
		@include('_parts.topic_list')
	<h3 id="content_h3" class="box_border1">{{$lan::get('detailed_information_title')}}</h3>
	<div id="section_content">
		
		@if( $request['action_message'])
		<div class="alart_box box_shadow">
			<ul class="message_area">
				<li class="@if( $request['action_status'] == 'OK')
								info_message
							@else 
								error_message
							@endif">
					{{$request['action_message']}}
				</li>
			</ul>
			<div id="data_table"></div>
		</div>
		@endif
		
		<form id="action_form" method="post">
			<div id="center_content_main">
				<input type="hidden" name="id" value="{{$request['id']}}"/>
				@include('_parts.invoice.axis_form')
				<br/>
					
				<div id="section_content_in">
					<input type="hidden" name="level" value="0"/>
					 @if( $request['is_established'] != "1" || $request['active_flag'] == "0") 
					<input type="submit" class="btn_green" id="btn_confirm_invoice" value="{{$lan::get('confirm_title')}}" />
					 @endif
					
					@if( $first != 1 or $last != 1)
					<br/><br/>
					@if( $first != 1)<input type="submit" class="btn_green" id="btn_before" value="{{$lan::get('previous_title')}}" />@endif
					@if( $last != 1)<input type="submit" class="btn_green" id="btn_after" value="{{$lan::get('next_title')}}" />@endif
					@endif
				</div>
			</div>
		</form>
	</div>
	
@stop
<div id="dialog-confirm"  style="display: none;">
	{{$lan::get('confirm_delete_title')}}
</div>

