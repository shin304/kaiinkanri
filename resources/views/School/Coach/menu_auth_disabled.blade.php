<!-- メニュー権限設定 -->
<tr>
	<td class="t6_td1">{{$lan::get('school_basic_info_title')}}</td>
	<td class="t4td2">
		<div id="auth">
			@foreach ($school_menu_list as $row) 
				@if ($row['default_menu_id'] == 1) <!-- HOME -->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label>
					<input type="checkbox" checked="checked" disabled="disabled" /> {{$lan::get('menu_view_title')}}&nbsp;&nbsp;&nbsp; 
					<input type="hidden" class="menu_auth" name="menu_auth_01" value="1" checked="checked" /> 
					<input type="hidden" class="menu_edit" name="menu_edit_01" value="1" checked="checked" /> <br /> 
				@endif 
			@endforeach
		</div>
	</td>
</tr>
<tr>
	<td class="t6_td1">{{$lan::get('master_manage_title')}}</td>
	<td class="t4td2">
		<div id="auth">
			@foreach ($school_menu_list as $row) 
				@if ($row['default_menu_id'] == 7) <!-- 支部一覧 -->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label>
					<input type="checkbox" class="menu_auth" name="menu_auth_02" value="1" @if (request('menu_auth_02')) checked @endif disabled/> {{$lan::get('menu_view_title')}} &nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_02" value="1" @if (request('menu_edit_02')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif 
				@if ($row['default_menu_id'] == 5) <!-- お知らせ送信 -->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_03" value="1" @if (request('menu_auth_03')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_03" value="1" @if (request('menu_edit_03')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 6) <!-- 基本情報-->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label>
					<input type="checkbox" class="menu_auth" name="menu_auth_04" value="1" @if (request('menu_auth_04')) checked @endif disabled/> {{$lan::get('menu_view_title')}}  &nbsp;&nbsp;&nbsp;
					<input type="checkbox" class="menu_edit" name="menu_edit_04" value="1" @if (request('menu_edit_04')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 8) <!-- プラン管理-->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_05" value="1" @if (request('menu_auth_05')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_05" value="1" @if (request('menu_edit_05')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif 
			@endforeach
		</div>
	</td>
</tr>
<tr>
	<td class="t6_td1">{{$lan::get('operational_manage_title')}}</td>
	<td class="t4td2">
		<div id="auth">
			@foreach ($school_menu_list as $row) 				
				@if ($row['default_menu_id'] == 9) <!-- イベント管理 -->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_06" value="1" @if (request('menu_auth_06')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_06" value="1" @if (request('menu_edit_06')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 10) <!-- プログラム管理 -->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_07" value="1" @if (request('menu_auth_07')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_07" value="1" @if (request('menu_edit_07')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 11) <!-- お知らせ送信 --> 
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_08" value="1" @if (request('menu_auth_08')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_08" value="1" @if (request('menu_edit_08')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif 
				@if ($row['default_menu_id'] == 12) <!-- 請求管理 --> 
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_09" value="1" @if (request('menu_auth_09')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_09" value="1" @if (request('menu_edit_09')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 13) <!-- 統計管理 -->
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label>
					<input type="checkbox" class="menu_auth" name="menu_auth_10" value="1" @if (request('menu_auth_10')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_10" value="1" @if (request('menu_edit_10')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 14) <!-- 会員管理 --> 
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_11" value="1" @if (request('menu_auth_11')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_11" value="1" @if (request('menu_edit_11')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 15) <!-- 請求先管理 --> 
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_12" value="1" @if (request('menu_auth_12')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_12" value="1" @if (request('menu_edit_12')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
				@if ($row['default_menu_id'] == 16) <!-- 講師管理 --> 
					<label style="font-weight: bold;">@if ($current_lang == 1)
						{{$row['menu_name']}}
					@elseif ($current_lang == 2)						
						{{$row['menu_name_2']}}	
					@endif</label> 
					<input type="checkbox" class="menu_auth" name="menu_auth_13" value="1" @if (request('menu_auth_13')) checked @endif disabled/> {{$lan::get('menu_view_title')}}	&nbsp;&nbsp;&nbsp; 
					<input type="checkbox" class="menu_edit" name="menu_edit_13" value="1" @if (request('menu_edit_13')) checked @endif disabled/> {{$lan::get('menu_edit_title')}} <br />
				@endif
			@endforeach
		</div>
	</td>
</tr>