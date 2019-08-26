@extends('_parts.master_layout') @section('content')
<link href="/css/display_box_search.css" rel="stylesheet">
<script src="/js/display_box_search.js"></script>
<style>
	.center_content_header_right{
		margin-bottom: 10px;
	}
	.search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover {
		background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
		box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
		cursor: pointer;
		text-shadow: 0 0px #FFF;
	}
	.search_box #search_cond_clear {
		border-radius: 5px;
		height: 29.5px;
		font-size: 14px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
	.top_btn li {
		border-radius: 5px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
	.btn_search {
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
	input[type="button"] {
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
</style>
		<div id="center_content_header" class="box_border1">
		<h2 class="float_left"><i class="fa fa-envelope-o"></i>{{$lan::get('broadcast_mail_main')}}</h2>

		<div class="clr"></div>
		</div><!--center_content_header-->
		
		{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('broadcastmail') !!}</div>  --}}
	{{--@include('_parts.topic_list')--}}

		<div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
			<div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
		</div>
		<div class="search_box box_border1 padding1" id="display_box_search">
		@if( $request->message_type)
			@if( $request->message_type == 99)
			<ul class="message_area">
				<li class="error_message">
					{{$lan::get('msg_error_process_failed')}}
				</li>
			</ul>
			@else
			<ul class="message_area">
				<li class="info_message">
				@if( $request->message_type == 51)
					{{$lan::get('msg_mail_sent')}}
				@elseif( $request->message_type==52)
					{{$lan::get('msg_draft_saved')}}
				@elseif( $request->message_type==53)
					{{$lan::get('msg_draft_deleted')}}
				@elseif( $request->message_type==2)
					{{$lan::get('msg_changed')}}
				@elseif( $request->message_type==3)
					{{$lan::get('msg_deleted')}}
				@endif
				</li>
			</ul>
			@endif
			<br/>
		@endif

			<form action="{{$_app_path}}broadcastmail/search" method="post">
			{{ csrf_field() }}
				<table>
					<tr>
						<th style="width:120px;">{{$lan::get('title')}}</th>
						<td>
							<input class="text_long" type="search" name="_c[input_search]" value="@if(isset($request['_c']['input_search'])){{$request['_c']['input_search']}}@endif"
								   placeholder="{{$lan::get('title')}}{{$lan::get('placeholder_input_temp')}}"/>
						</td>
					</tr>
					<tr>
						<th>{{$lan::get('state_title')}}</th>
						<td>
							<select name="_c[send_flag]">
								<option value=""></option>
								@foreach($send_flag as $key => $item)
									@if(isset($request['_c']['send_flag']) && array_get($request, '_c.send_flag') == $key)
										<option value="{{$key}}" selected="selected">{{$item}}</option>
									@else
										<option value="{{$key}}">{{$item}}</option>
									@endif
								@endforeach
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<label style="font-weight: bold">&nbsp;&nbsp;&nbsp;&nbsp;{{$lan::get('bc_target')}}</label>&nbsp;&nbsp;
						</th>
						<td>
							<label><input type="checkbox" name="_c[bc_option][]" value="1"
										  @if(isset($request['_c']['bc_option']) && in_array(1,($request['_c']['bc_option']))) checked @endif>{{$lan::get('parent_title')}} &nbsp;</label>
							<label><input type="checkbox" name="_c[bc_option][]" value="2"
										  @if(isset($request['_c']['bc_option']) && in_array(2,($request['_c']['bc_option']))) checked @endif>{{$lan::get('student_title')}} &nbsp;</label>
							<label><input type="checkbox" name="_c[bc_option][]" value="3"
										  @if(isset($request['_c']['bc_option']) && in_array(3,($request['_c']['bc_option']))) checked @endif>{{$lan::get('teacher_title')}} &nbsp;</label>
							<label><input type="checkbox" name="_c[bc_option][]" value="4"
										  @if(isset($request['_c']['bc_option']) && in_array(4,($request['_c']['bc_option']))) checked @endif>{{$lan::get('staff_title')}} &nbsp;</label>
						</td>
					</tr>
				</table>
				<div class="clr"></div>
				<!-- <input type="submit" class="submit" name="search_button" value="{{$lan::get('search')}}"/> -->
				<button class="btn_search" type="submit" name="search_button" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search')}}</button>
				<input type="reset" class="submit" id="search_cond_clear" value="{{$lan::get('clear')}}"/>
			</form>
		</div>

        <h3 id="content_h3" class="box_border1">{{$lan::get('list')}}</h3>

		<div id="section_content1" >
			<div class="center_content_header_right">
				<div class="top_btn">
					<ul>
						@if($edit_auth)
						<a href="{{$_app_path}}broadcastmail/entry"><li style="color: #595959; font-weight: normal;"><i class="fa fa-plus"></i>{{$lan::get('register_btn_title')}}</li></a>
						@endif
					</ul>
				</div>
			</div>
			<table class="table1" >
				<thead>
				<tr>
					<th class="text_title header" style="text-align: left; padding-left: 10px !important;">{{$lan::get('title')}}</th>
					{{--<th class="text_title header" style="width:200px;">{{$lan::get('sent_datetime')}}</th>
					<th class="text_title header" style="width:160px;">{{$lan::get('sent_division')}}</th>
					<th class="text_title header" style="width:200px;">{{$lan::get('memo')}}</th>--}}
					<th class="text_title header" style="text-align: left">{{$lan::get('status_title')}}</th>
					<th class="text_title header" style="text-align: left">{{$lan::get('time_send')}}</th>
				</tr>
				</thead>
				@if(isset($broadcast_mail_list))
					@foreach ($broadcast_mail_list as $row)
					<tr>
						<td style="width:240px;text-align: left; padding-left: 10px;">
						<a class="text_link" href="{{$_app_path}}broadcastmail/edit?id={{array_get($row, 'id')}}">{{array_get($row, 'title')}}</a>
						</td>
						{{--<td style="width:130px;">
							@if( isset($row['send_flag']))
								@if(array_get($row, 'update_date'))
									{{Carbon\Carbon::parse(array_get($row, 'update_date'))->format('Y年m月d日')}}
								@else
									{{Carbon\Carbon::parse(array_get($row, 'register_date'))->format('Y年m月d日')}}
								@endif
							@else
								{{$lan::get('draft_saved_title')}}
							@endif</td>
						<td style="width:440px;">{{array_get($row, 'content')}}</td>
						<td style="width:200px;">{{array_get($row, 'memo')}}</td>--}}
						<td style="width:200px;text-align: left">@if(array_get($row, 'send_flag') == 1) {{array_get($send_flag, '1')}} @else {{array_get($send_flag, '0')}} @endif</td>
						<td style="width:200px;text-align: left">{{Carbon\Carbon::parse(array_get($row, 'time_send'))->format('Y-m-d')}}</td>
					</tr>
					@if($broadcast_mail_list == null)
					<tr>
						<td class="t4td2 error_row">{{$lan::get('no_data_to_show')}}</td>
					</tr>
					@endif
					@endforeach
				@endif
			 </table>

</div><!--section_content-->
 <script type="text/javascript">
     $(function(){
         $('#search_cond_clear').click(function() {  // clear

             $("input[name='_c[input_search]']").attr('value',"");
             $("input[name='_c[bc_option][]']").each(function(){
                 $(this).attr("checked","checked");
             });
             $("select[name='_c[send_flag]']").find('option:selected').removeAttr("selected");
         });
     });
 </script>
@stop
