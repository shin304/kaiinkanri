<script type="text/javascript">

$(function() {
	// 修正する場合はParent/top.htmlも同様に修正してください //
	/* 学年 */
	$("#school_kinds").change(setGrade);
	window.onload = setGrade('{{$request.school_year}}');

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

<select id="school_kinds" name="school_category" class="select1">
	<option value=""></option> @foreach($schoolCategory as $item)
	<option value="{{$item}}">{{$item}}</option> @endforeach
</select>
<select id="school_grade" name="school_year" class="select1">
	<option value=""></option>
</select>

