<script type="text/javascript">
$(function() {
	$('#search_cond_clear').click(function() {  // clear
		$("input[name='select_word']").val("");
		$("select[name='select_grade']").val("");
		$("select[name='select_pschool']").val("");
		$("select[name='select_state']").val("");
		return false;
	});

	$('input[name="disp_billing"]').change(function() {
		var prop = $('#prop').prop('checked');
	    if (prop) {
	        $('#billing_state').show();
	      } else {
	        $('#billing_state').hide();;
	      }
	});
});
</script>

<table>
	<input type="hidden" name="event_id" value="{{request('event_id')}}">
	<input type="hidden" name="msg_type_id" value="{{request('msg_type_id')}}">
	<input type="hidden" name="event_type_id" value="{{request('event_type_id')}}">
	<input type="hidden" name="event_type" value="{{request('event_type')}}">
	<input type="hidden" name="event_name" value="{{request('event_name')}}">
	<colgroup>
		<col width="10%" />
		<col width="30%" />
		<col width="10%" />
		<col width="30%" />
	</colgroup>
	<tr>
		<th>{{$lan::get('name_furigana')}}</th>
		<td><input class="text_long" type="search" name="select_word"
			id="select_word" value="{{request('select_word')}}" /></td>
	</tr>
	<tr>
		<th>{{$lan::get('status_title')}}</th>
		<td><select name="select_state" id="select_state"
			style="max-width: 200px;">
				<option value=""></option> 
				@foreach ($states as $key=>$item)
				<option value="{{$key}}" @if (request('select_state') == $key) selected @endif>{{$lan::get($item)}}</option> 
				@endforeach
		</select></td>
	</tr>
	<tr>
		<th>{{$lan::get('billing_tag_title')}}</th>
		<td><input id="prop" type="checkbox" name="disp_billing" value="1"
			@if (request('disp_billing') == '1') checked 
							@endif />&nbsp;表示する</td>
		<td id="billing_state" @if (request('disp_billing') !=1)
			style='display: none;' @endif><select name="workflow_status"
			id="select_state" style="max-width: 200px;">
				<option value=""></option> 
				@if (isset($workflow_status_list))
				@foreach($workflow_status_list as $key=>$item)
				<option value="{{$key}}" @if (request('workflow_status') == $key) selected @endif>{{$item}}</option> 
				@endforeach
				@endif
		</select></td>
	</tr>
	
</table>
<div class="clr"></div>
<input type="submit" id="btn_student_search" class="submit"
	name="search_button" value="{{$lan::get('search_title')}}">
<input type="button" id="search_cond_clear" class="submit"
	value="{{$lan::get('clear_all_title')}}" />
<div class="clr"></div>
