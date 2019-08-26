<script type="text/javascript">
$(function() {
	$("#school_type").change(function(){
		var school_cat = $(this).val();
		if (!school_cat)
		{
			$("#grade_option option").remove();
			$("#grade_option").prepend($("<option>").html("").val(""));
			return;
		}
		$.get(
			"{{$_app_path}}ajaxMailMessage/school",
			{school_cat:school_cat},
			function(data)
			{
				var desc = "年生";
				$("#grade_option option").remove();
				$("#grade_option").append($("<option>").html(desc).val(key));
				for(var key in data.grades)
				{
					var school_year_id = (parseInt(key)) + 1;
					var school_year = school_year_id + desc;
					$("#grade_option").append($("<option>").html(school_year).val(school_year_id));
				}
				$("#grade_option").prepend($("<option>").html("").val(""));
				$("#grade_option").val("");
			},
			"jsonp"
		);
	});

	/* 生徒住所の都道府県 */
	$("#address_pref").change(function() {
		var pref_cd = $(this).val();
		if (pref_cd == "") {
			$("#address_city option").remove();
			$("#address_city").prepend($("<option>").html("").val(""));
			$("#selectaddress_city").text("");
			return;
		}
		$.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#address_city option").remove();
				for(var key in data.city_list){
					$("#address_city").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#address_city").prepend($("<option>").html("").val(""));
				$("#address_city").val("");
				$("#selectaddress_city").text("");
			},
			"jsonp"
		);
	});

	$("#btn_student_search").click(function() {
		// hiddenのtypeを削除する
		$('.student_types').each(function(){
			name_str = $(this).attr('name');
			$('input[type="hidden"][name="'+name_str+'"]').remove();
		})
	});

	$('#search_cond_clear').click(function() {  // clear
		$("input[name='input_search']").val("");
		$("select[name='school_category']").val("");
		$("select[name='school_year']").val("");
		$("#school_grade option").remove();

		$("select[name='class_id']").val("");

		$("select[name='exam_pref']").val("");
		$("select[name='exam_city']").val("");
		$("#address_city option").remove();

		$("input[name='input_search_student_no']").val("");

		var types_count = "{{$request['_student_types']}}";
		for(var idx=0; idx<types_count; idx++){
			$("#student_type" + idx).prop("checked",false);
		}

	});
});

</script>
<script type="text/javascript">

$(function() {
	// 修正する場合はParent/top.htmlも同様に修正してください //
	/* 学年 */
	$("#school_kinds").change(setGrade);
	window.onload = setGrade("{{$request['school_year']}}");

	function setGrade(grade) {
		var kinds = $("#school_kinds").val();
		
		if (kinds == "") {
			// 設定なし
			$("#school_grade option").remove();
			$("#school_grade").prepend($("<option>").html("").val(""));
		}
		else if (kinds < 10) {
			// 学年の最大値
			var years = 3;
			if (kinds == 0){
				years = 6;
			}else if (kinds == 3) {
				years = 8;
			}
			// optionの設定
			$("#school_grade option").remove();
			for(var key = 1; key <= years; key++){
				$("#school_grade").append($("<option>").html(key+"年").val(key));
			}
			$("#school_grade").prepend($("<option>").html("").val(""));
			$("#school_grade").val(grade);
		}
		else {
			// 学生ではない
			$("#school_grade option").remove();
			$("#school_grade").prepend($("<option>").html("").val(""));
		}
		
		return false;
	}
	
});
</script>

<table>
	<tr>
		<th style="width: 10%;">名前(フリガナ)</th>
		<td style="width: 30%;"><input class="text_long" type="search"
			name="input_search" value={{$request['input_search']}} ></td>
		<th style="width: 10%;">生徒区分</th>
		<td style="width: 30%;"><select id="school_kinds"
			name="school_category" class="select1">
				<option value=""></option> 
				
				@foreach($schoolCategory as $item)
				<option value="{{$item}}">{{$item}}</option> @endforeach
				@endforeach
		</select> <select id="school_grade" name="school_year" class="select1">
				<option value=""></option>
		</select>
		</td>
	</tr>
	<tr>
		<th style="width: 10%;">生徒番号</th>
		<td style="width: 30%;"><input class="text_long" type="search"
			name="input_search_student_no"
			value={{$request['input_search_student_no']}}></td>
		<th style="width: 10%;">受験地域</th>
		<td style="width: 30%;"><select name="exam_pref" id="address_pref">
				<option value=""></option> 
				@foreach($prefList as $key =>$item)
								@if($request['exam_pref'] == $item)<option value="{{$key}}" selected="selected" @endif>{{$item}}</option>
								<option value="{{$key}}">{{$item}}</option>
								@endforeach
		</select> <select name="exam_city" id="address_city"
			style="width: 200px">
				<option value=""></option>
				@foreach($cityList as $key =>$item)
								@if($request['exam_city'] == $item)<option value="{{$key}}" selected="selected" @endif>{{$item}}</option>
								<option value="{{$key}}">{{$item}}</option>
								@endforeach
		</select></td>
	</tr>
	<tr>
		<th style="width: 10%;">生徒種別</th>
		<td style="width: 30%;">
		@foreach ($request['_student_types	as $index => $studenttype) 
			<input type="hidden" name="_student_types[{{$index}}][name]" value="{{$studenttype['name']}}" />
			<input type="hidden" name="_student_types[{{$index}}][type]"
			value="{{$studenttype['type']}}" /> <label> <input class="student_types"
				type="checkbox" id="student_type{{$index}}"
				name="_student_types[{{$index}}][is_display]"
				@if( $studenttype['is_display'] == 1) checked @endif/>&nbsp;{{$studenttype['name']}}
		</label> @endforeach
		</td>
		<th style="width: 10%;">{{$main_captions[4]}}</th>
		<td style="width: 30%;"><select name="class_id" class="select1">
				<option value=""></option> {
				@foreach($class_list as $key =>$item)
								@if($request['class_id'] == $key)<option value="{{$key}}" selected="selected" @endif>{{$item}}</option>
								<option value="{{$key}}">{{$item}}</option>
								@endforeach
		</select></td>
	</tr>
</table>
<div class="clr"></div>
<input type="submit" id="btn_student_search" class="submit"
	name="search_button" value="検索">
<input type="button" class="submit" id="search_cond_clear"
	value="すべてクリア">
<div class="clr"></div>
