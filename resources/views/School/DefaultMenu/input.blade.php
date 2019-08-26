@extends('_parts.master_layout')

@section('content')
<script type="text/javascript">
$(function() {

	$('.menu_item').change(function() {
		//Ex: li: <li idx="1"><input type="checkbox" class="menu_item"...</li>
		var li = $(this).parent().clone();

		var idx = ($(this).parent().attr('idx'));li.children().attr('name', 'default_menu['+idx+']');
		// append for the first time OR top list
		if ($('#default_menu_side li').length == 0 || idx == 0) {
			$('#default_menu_side').prepend(li);
		} else {
			
			//append to next position
			i = idx-1; flag = false;
			while (i >= 0) {
				objPre = '#default_menu_side li[idx='+i+']';
				if ($(objPre).length != 0) {

					$(li).insertAfter(objPre);
					i=0;flag=true;
				}
				i--;
			}
			// case cannot find next position, then append to top
			if (!flag) {
				$('#default_menu_side').prepend(li);
			}
		}
		
	});
});
</script>
	<div id="center_content_header" class="box_border1">
		<h2 class="float_left">{{$lan['default_menu_title']}}</h2>
	</div>
	<h3 id="content_h3" class="box_border1"></h3>
	<div id="section_content1">
		<form action="/school/defaultmenu/input" method="post" name="form1" enctype="multipart/form-data">
		{{ csrf_field() }}
		<table>
			<tr>
				<td>
					<select class="form-control" name="role_list" >
					<option value=""></option>
					@foreach ($roleList as $index => $role)
					<option value="{{$index}}">{{$role}}</option>
					@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<h3>menu structure</h3>
					<ul id="master_menu_side">
					@foreach ($menuList as $index => $menu) 
					<li idx="{{$index}}">
					<input type="checkbox" class="menu_item" value="{{$menu->id}}"></label>{{$menu->menu_name}}
					</li>
					@endforeach
					</ul>
				</td>
				<td>
					<h3>menu assign</h3>
					<ul id="default_menu_side" >
						
					</ul>
				</td>
			</tr>
			<tr>
				<td><input type="submit" name="btn" value="保存"></td>
			</tr>
		</table>
		</form>
	</div>
@stop
