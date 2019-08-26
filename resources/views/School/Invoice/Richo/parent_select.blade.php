<script type="text/javascript">
	$(function() {
		$(".tablesorter", "#custom_dialog_area").tablesorter(
				{
		            headers: {
		                3: { sorter: false }
		            }
		        }
				);
	});
	$('.text_link_dummy').click(function() {
		var link = $(this).attr('href');
		var uri = link.split("?");
		
		if (uri[1]){
			// フォームの生成
			var form = document.createElement("form");
			form.setAttribute("action", uri[0]);
			form.setAttribute("method", "post");
			form.style.display = "none";
			document.body.appendChild(form);

			// パラメータの設定
			var params = uri[1].split("&");
			var input = document.createElement('input');
			$.each(params,function(idx,param){
				var value = param.split("=");
				input.setAttribute('type', 'hidden');
				input.setAttribute('name', value[0]);
				input.setAttribute('value', value[1]);
				form.appendChild(input);
			});
			
			form.submit();			
		}					
		return false;
    });
</script>

<script type="text/javascript">
$(function(){	
	$('.text_link').click(function() {
		var link = $(this).attr('href');
		location.href(link);
		return false;
	});
});

function java_post(link) {
	var uri = link.split("?");
	
	// フォームの生成
	var form = document.createElement("form");
	form.setAttribute("action", uri[0]);
	form.setAttribute("method", "post");
	form.style.display = "none";
	document.body.appendChild(form);

	if (uri[1]){
		// パラメータの設定
		var params = uri[1].split("&");
		$.each(params,function(idx,param){
			var value = param.split("=");
			var input = document.createElement('input');
			input.setAttribute('type', 'hidden');
			input.setAttribute('name', value[0]);
			input.setAttribute('value', value[1]);
			form.appendChild(input);
		});
	}
	
	form.submit();
}

</script>

<div class="content_detail">
	<table class="table_list tablesorter body_scroll_table">
		<thead>
			<tr>
				<th style="width:120px;" class="text_title">{{$lan::get('guardian_fullname_title')}}</th>
				<th style="width:160px;" class="text_title">{{$lan::get('membership_number_title')}}</th>
				<th style="width:120px;" class="text_title">{{$lan::get('member_name_title')}}</th>
				<th style="width:60px;" class="text_title">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		@if(isset($parent_list))
			@foreach ($parent_list as $idx => $row)
				<tr class="table_row">
					<td style="width:120px;">{{array_get($row, 'parent_name')}}</td>
					<td style="width:160px;">
						@foreach (array_get($row, 'student_list') as $student_row)
							{{array_get($student_row, 'student_no')}}<br/>
						@endforeach
					</td>
					<td style="width:120px;">
						@foreach (array_get($row, 'student_list') as $student_row)
							{{array_get($student_row, 'student_name')}}<br/>
						@endforeach
					</td>
					<td style="width:60px; text-align: center;">
					{{-- @if(array_get($auths, 'invoice_entry') == 1) --}}
					<a class="text_link" href="{{$_app_path}}invoice/entry?parent_id={{array_get($row, 'id')}}&invoice_year_month={{array_get($request, 'invoice_year_month')}}">{{$lan::get('selection_title')}}</a></td>
					{{-- @endif --}}
				</tr>
				@endforeach
			@endif
			@if(!isset($parent_list))
				<tr class="table_row">
					<td class="error_row">{{$lan::get('no_guardian_created_invoice_title')}}</td>
				</tr>
			@endif
		</tbody>
	</table>
</div>
