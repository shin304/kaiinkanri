@extends('_parts.master_layout')

@section('content')
	<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
	<link href="/css/display_box_search.css" rel="stylesheet">
	<script src="/js/display_box_search.js"></script>
	<script type="text/javascript">
	$(function() {

        $(".tablesorter").tablesorter( {
            headers: {
                1: { sorter: false },
                2: { sorter: false }
            }
        });
        $(".header").click(function (e) {
            e.preventDefault();
            if($(this).children().hasClass("fa-chevron-down")){
                $(this).children().removeClass("fa-chevron-down");
                $(this).children().addClass("fa-chevron-up");
            }else if($(this).children().hasClass("fa-chevron-up")){
                $(this).children().removeClass("fa-chevron-up");
                $(this).children().addClass("fa-chevron-down");
            }

        });
    	$('#search_cond_clear').click(function() {  // clear
    		$("input[name='_c[name]']").val("");
			$("input[name='add_caption']").prop("checked",false);
			$("#action_form").submit();
    		return false;
		});
	});
</script>
<style>
	.search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover, .btn_submit:hover {
		background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
		box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
		cursor: pointer;
		text-shadow: 0 0px #FFF;
	}
	.search_box #search_cond_clear {
		height: 29.5px;
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
	.btn_submit {
		color: #595959;
		height: 30px;
		border-radius: 5px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		font-size: 14px;
		font-weight: normal;
		text-shadow: 0 0px #FFF;
	}
</style>
	<div class="section">
		<div id="center_content_header" class="box_border1">
			<h2 class="float_left"><i class="fa fa-book"></i>{{$lan::get('main_title')}}</h2>
		<div class="clr"></div>
		</div><!--center_content_header-->
	<div id="box_display" class="box-display clearfix" onclick="showBoxSearch();">
		<div class="pull-left">{{$lan::get('search')}}</div><div class="cls-display pull-right"><i id="icon_drown_up" class="arrow up"></i></div>
	</div>
	<div class="search_box box_border1 padding1" id="display_box_search">

{{--	<div class="search_box box_border1 padding1">--}}
			<form id="action_form" action="{{$_app_path}}class" method="post" id="display_box_search">
			{{ csrf_field() }}
				<table>
					<tr>
						<th style="width:5%;">
							{{$lan::get('class_name_title')}}
						</th>
						<td style="width:30%;">
							<input class="text_long" type="search" name="_c[name]" value="{{old('_c.name', request('_c.name'))}}" placeholder="{{$lan::get('name_input_title')}}"/><!-- ///////////// value -->
						</td>
					</tr>
				</table>
				<div class="clr"></div>
				<!-- <input type="submit" class="submit" name="search_button" value="{{$lan::get('search_title')}}"/> -->
				<button class="btn_search" type="submit" name="search_button" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search_title')}}</button>
				<input type="button" class="submit" id="search_cond_clear" value="{{$lan::get('clear_title')}}"/>
			</form>
	</div>

		<div class="clr"></div>
		<h3 id="content_h3" class="box_border1">{{$lan::get('class_list_title')}}</h3>

	<div id="section_content1">

	@if (isset($regist_error))
		<ul class="message_area">
			<li class="error_message">
			@if ($regist_error == 1)
			{{$lan::get('register_title')}}
			@elseif ($regist_error == 2)
			{{$lan::get('update_title')}}
			@else
			{{$lan::get('delete_title')}}
			@endif
			{{$lan::get('failed_title')}}</li>
		</ul>
	@endif

	@if (request('message_mode'))
		<ul class="message_area">
			<li class="info_message">	
			@if (request('message_mode') == 1) {{$lan::get('success_register_title')}}
			@elseif (request('message_mode') == 2) {{$lan::get('update_success_title')}}
			@elseif (request('message_mode') == 3) {{$lan::get('success_delete_title')}}
			@endif
			</li>
		</ul>
	@endif

	{{--@if (request('mode'))
			<ul class="message_area">
				<li class="info_message">@if (request('mode') == 1) {{$lan::get('success_add_title')}} @elseif (request('mode') == 2) {{$lan::get('success_delete_title')}} @endif</li>
			</ul>
	@endif --}}
		<div class="center_content_header_right">
			<div class="top_btn">
				<ul>
					@if($edit_auth)
					<a href="{{$_app_path}}class/input"><li style="color: #595959; font-weight: normal;"><i class="fa fa-plus"></i>{{$lan::get('class_register')}}</li></a>
					@endif
				</ul>
			</div>
		</div>
		<div class="clr"></div>
		<table class="table1 tablesorter" >
			<thead>
			<tr class="head_tr">
				<th class="text_title header" style="width:200px;">{{$lan::get('class_name_title')}}<i style="font-size:12px;" class="fa fa-chevron-down"></i></th>
				<th class="text_title" style="width: 80px;">{{$lan::get('numer_member_title')}}</th>
				<th class="text_title" style="width:120px;">{{$lan::get('class_action_title')}}</th>
			</tr>
			</thead>
			<tbody>
			@foreach ( $list as $row)
				<tr class="table_row">
					<td style="width:200px;padding:4px 10px;text-align: left;">
						<a class="text_link" href="{{$_app_path}}class/detail?id={{$row['id']}}">
							{{$row['class_name']}}</a>
					</td>

					<td style="width:80px;padding:4px 10px;">{{$row['student_count']}}</td>
					<td style="width:120px;padding:4px 4px;">
						@if($edit_auth)
						@if (array_get($row,'is_active'))
							<a class="text_link button" href="{{$_app_path}}class/studentList?id={{$row['id']}}&mode=1">
								<button type="button" class="btn_submit"><i class="fa fa-plus " ></i>{{$lan::get('add_title')}}</button></a>
							<a class="text_link button" href="{{$_app_path}}class/studentList?id={{$row['id']}}&mode=2">
								<button type="button" class="btn_submit"><i class="fa fa-minus " ></i>{{$lan::get('member_delete_title')}}</button></a>
						@else
							<p style="color:gray;">{{$lan::get('end_title')}}</p>
						@endif
						@endif
					</td>
				</tr>

			@endforeach

			@forelse ($list as $row)
			@empty
				<tr>
					<td class="error_row" colspan="3">{{$lan::get('nothing_display_title')}}</td>
				</tr>
			@endforelse
			</tbody>
		</table>
	</div>
	</div><!--section-->

@stop
