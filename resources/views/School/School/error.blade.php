@if(isset($request->errors))
	<ul class="message_area">
		@foreach ($request->errors as $idx => $error) 
		@if(isset($request->errors['type']['notEmpty']))
		<li class="error_message">{{$lan['required_idex_error_title']}}</li>
		@endif 
		@if( isset($request->errors['type']['notNumeric']))
		<li class="error_message">{{$lan['format_idex_error_title']}}</li>
		@endif 
		@if( isset($request->errors['type']['overLength']))
		<li class="error_message">{{$lan['length_idex_error_title']}}</li>
		@endif 
		@if( isset($request->errors['name']['notEmpty']))
		<li class="error_message">{{$lan['required_student_name_error_title']}}</li>
		@endif 
		@if( isset($request->errors['name']['overLength']))
		<li class="error_message">{{$lan['length_student_name_error_title']}}</li>
		@endif 
		@endforeach 
		@if(isset($request->errors['type']['duplicateValue']))
		<li class="error_message">{{$lan['duplicated_index_error_title']}}</li>@endif
		@if( isset($request->errors['required_fee']['notInput']))
		<li class="error_message">{{$lan['required_price_setting_error_title']}}</li>@endif
	</ul>
	@endif 
	@if($request->regist_error)
	<ul class="message_area">
		<li class="error_message">{{$lan['failed_update_error_title']}}</li>
	</ul>
	@endif 
	@if($request->regist_message)
	<ul class="message_area">
		<li class="info_message">{{$lan['update_success_title']}}</li>
	</ul>
	@endif 





@if( isset($request-> errors))
		<ul class="message_area">
			@foreach ($request->errors as $idx => $error)
			@if(isset($request->errors['grade_color']['notEmpty']))
			<li class="error_message">{{$lan['required_belt_color_error_title']}}</li>
			@endif 
			@if( isset($request->errors['grade_note']['notEmpty']))
			<li class="error_message">{{$lan['required_belt_note_error_title']}}</li>
			@endif 
			@if( isset($request->errors['sort_no']['notEmpty']))
			<li class="error_message">{{$lan['required_belt_level_error_title']}}</li>
			@endif 
			@if( isset($request->errors['sort_no']['notNumeric']))
			<li class="error_message">{{$lan['format_belt_level_error_title']}}</li>
			@endif 
			@endforeach
		</ul>
		@endif
		
		
		
	     	var trHTML="{{$request['grades'] = '+ data +'}}";
		