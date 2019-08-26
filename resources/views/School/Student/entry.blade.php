@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
	//datepicker追加
	var d = new Date();
	$(".DateInput").datepicker({
		   showOn: 'both',
		   dateFormat: 'yy-mm-dd',
		   changeMonth: true,
		   changeYear: true,
		   monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		   monthNamesShort: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		   dayNames: ['日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'],
		   dayNamesShort: ['日','月','火','水','木','金','土'],
		   dayNamesMin: ['日','月','火','水','木','金','土'],
		   yearRange: '2000:'+(d.getYear()+1910),
		   prevText: '&#x3c;前', prevStatus: '前月を表示します',
		   prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '前年を表示します',
		   nextText: '次&#x3e;', nextStatus: '翌月を表示します',
		   nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '翌年を表示します',
		   currentText: '今日', currentStatus: '今月を表示します',
		   todayText: '今日', todayStatus: '今月を表示します',
		   clearText: 'クリア', clearStatus: '日付をクリアします',
		   closeText: '閉じる', closeStatus: '変更せずに閉じます'
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
	/* 生徒受験地域１の都道府県 */
	$("#exam_pref1").change(function() {
		var pref_cd = $(this).val();
		if (pref_cd == "") {
			$("#exam_city1 option").remove();
			$("#exam_city1").prepend($("<option>").html("").val(""));
			$("#selectexam_city1").text("");
			return;
		}
		$.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#exam_city1 option").remove();
				for(var key in data.city_list){
					$("#exam_city1").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#exam_city1").prepend($("<option>").html("").val(""));
				$("#exam_city1").val("");
				$("#selectexam_city1").text("");
			},
			"jsonp"
		);
	});
	/* 生徒受験地域２の都道府県 */
	$("#exam_pref2").change(function() {
			var pref_cd = $(this).val();
			if (pref_cd == "") {
				$("#exam_city2 option").remove();
				$("#exam_city2").prepend($("<option>").html("").val(""));
				$("#selectexam_city2").text("");
				return;
			}
		$.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#exam_city2 option").remove();
				for(var key in data.city_list){
					$("#exam_city2").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#exam_city2").prepend($("<option>").html("").val(""));
				$("#exam_city2").val("");
				$("#selectexam_city2").text("");
			},
			"jsonp"
		);
	});
	/* 生徒受験地域３の都道府県 */
	$("#exam_pref3").change(function() {
			var pref_cd = $(this).val();
			if (pref_cd == "") {
				$("#exam_city3 option").remove();
				$("#exam_city3").prepend($("<option>").html("").val(""));
				$("#selectexam_city3").text("");
				return;
			}
		$.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#exam_city3 option").remove();
				for(var key in data.city_list){
					$("#exam_city3").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#exam_city3").prepend($("<option>").html("").val(""));
				$("#exam_city3").val("");
				$("#selectexam_city3").text("");
			},
			"jsonp"
		);
	});
	$("#btn_add_parent").click(function() {
		$("#action_form").attr('action', "{{$_app_path}}parent/list2");
		$("#action_form").submit();
		return false;
	});
	$("#btn_edit_parent").click(function() {
		$("#action_form").attr('action', "{{$_app_path}}parent/entry");
		$("#action_form").submit();
		return false;
	});

	$(".submit2").click(function() {
		$("#action_form").attr('action', '{{ URL::to('/school/student/confirm') }}');
		$("#action_form").submit();
		return false;
	});
	$("#btn_reset_parent").click(function() {
		$("input[name='select_parent']").val("");

		$(".select_parent").remove();
		$("input[name='parent_id']").val("");

		$(".input_parent").appendTo("#parent_area");
		$(this).remove();

		return false;
	});
});
</script>
<script type="text/javascript">
function nextForm(event)
{
	if (event.keyCode == 0x0d)
	{
		var current = document.activeElement;

		var forcus = 0;
		for( var idx = 0; idx < document.action_form.elements.length; idx++){
			if( document.action_form[idx] == current ){
				forcus = idx;
				break;
			}
		}
		document.action_form[(forcus + 1)].focus();
	}
}
window.document.onkeydown = nextForm;
</script>
<div id="center_content_header" class="box_border1">
	<h2 class="float_left">会員管理</h2>

	<div class="center_content_header_right">
		<div class="top_btn"></div>
	</div>

	<div class="clr"></div>
</div>
@include('_parts.student.topic_list')
<!--center_content_header-->

<div id="section_content">
	<h3 id="content_h3" class="box_border1">詳細情報@if ($request['id'])編集@else
		登録 @endif</h3>

	<div id="section_content_in">

		<span class="aster">&lowast;</span>印のついた項目は必須入力です。<br />
		<form id="action_form" name="action_form" method="post">
			{{ csrf_field() }} @include('_parts.student.hidden') @if
			($request['id']) <input type="hidden" name="student_exam1_id"
				value="{{$studentExam_list[0].id}}" /> <input type="hidden"
				name="student_exam2_id" value="{{$studentExam_list[1].id}}" /> <input
				type="hidden" name="student_exam3_id"
				value="{{$studentExam_list[2].id}}" /> <input type="hidden"
				name="_parent_id" value="{{$parent_id_in}}" /> @endif
			<h4>生徒</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">種別<span class="aster">&lowast;</span></td>
					<td class="t4td2"><select name="student_type">
							<option value=""></option> @foreach($studentTypeList as $item)
							<option label="" value="{{$item}}">{{$item}}</option> @endforeach
					</select></td>
				</tr>
				<tr>
					<td class="t6_td1">名前<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input class="text_m" type="text"
						name="student_name" style="ime-mode: active;" /></td>
				</tr>
				<tr>
					<td class="t6_td1">フリガナ<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input class="text_m" type="text"
						name="student_kana" style="ime-mode: active;" /></td>
				</tr>
				<tr>
					<td class="t6_td1">ニックネーム<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input class="text_m" type="text"
						name="student_nickname" style="ime-mode: active;" /></td>
				</tr>
				<tr>
					<td class="t6_td1">メールアドレス<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input class="text_m" type="text"
						name="student_mail" style="ime-mode: inactive;" /></td>
				</tr>
				<tr>
					<td class="t6_td1">生年月日<span class="aster">&lowast;</span></td>
					<td class="t4td2">西暦&nbsp;<select name="student_birth_year">
							@foreach($birthYearList as $item)
							<option value="{{$item}}">{{$item}}</option> @endforeach
					</select>&nbsp;年 <select name="student_birth_month">
							@foreach($birthMonthList as $item)
							<option value="{{$item}}">{{$item}}</option> @endforeach
					</select>&nbsp;月 <select name="student_birth_day">
							@foreach($birthDayList as $item)
							<option value="{{$item}}">{{$item}}</option> @endforeach
					</select>&nbsp;日
					</td>
				</tr>
				<tr>
					<td class="t6_td1">性別</td>
					<td class="t4td2"><input type="radio" name="student_sex" value="1"
						checked="checked" />&nbsp;男性&nbsp;&nbsp; <input type="radio"
						name="student_sex" value="2" />&nbsp;女性</td>
				</tr>
				<tr>
					<td class="t6_td1">学校名<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input class="text_m" type="text"
						name="school_name" style="ime-mode: active;" /></td>
				</tr>
				<tr>
					<td class="t6_td1">生徒区分<span class="aster">&lowast;</span></td>
					<td class="t4td2">@include('_parts.student.student_grade')</td>
				</tr>
				<tr>
					<td class="t6_td1">問合せ日<span class="aster">&lowast;</span></td>
					<td><input class="DateInput" type="text" name="inquiry_date" /></td>
				</tr>
				<tr>
					<td class="t6_td1">入塾日<span class="aster">&lowast;</span></td>
					<td><input class="DateInput" type="text" name="enter_date" /></td>
				</tr>
				<tr>
					<td class="t6_td1">退塾日<span class="aster">&lowast;</span></td>
					<td><input class="DateInput" type="text" name="resign_date" /></td>
				</tr>
			</table>

			@if( $request->parent) <input type="hidden" name="parent_id"
				value="{{$request.parent.id}}" /> @endif <input type="button"
				style="float: right" id="btn_add_parent" value="保護者選択" /> @if
			($request->parent_id != '') <input type="button" style="float: right"
				id="btn_reset_parent" value="保護者選択解除" /> @endif

			<h4>保護者</h4>
			<div id="parent_area">
				@if ($request->orgparent_id != '') @include('_parts.student.entry')
				<!-- 保護者選択から表示 -->
				<table id="table6" class="select_parent">
					<colgroup>
						<col width="30%" />
						<col width="70%" />
					</colgroup>
					<tr>
						<td class="t6_td1">名前</td>
						<td class="t4td2">{{$request->parent_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス１</td>
						<td class="t4td2">{{$request->parent_mailaddress1}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス２</td>
						<td class="t4td2">{{$request->parent_mailaddress2}}</td>
					</tr>

					<tr>
						<td class="t6_td1">郵便番号</td>
						<td class="t4td2">{{$request->zip_code1}}－{{$request->zip_code2}}</td>
					</tr>
					<tr>
						<td class="t6_td1">都道府県名</td>
						<td class="t4td2">{{$pref_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">市区町村名</td>
						<td class="t4td2">{{$city_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">番地・ビル名</td>
						<td class="t4td2">{{$request->address}}</td>
					</tr>
				</table>
				@else
				<table>
					<tr>
						<th style="padding: 0px 0px 20px 100px;">保護者を選択してください</th>
					</tr>
				</table>
				<!-- 入力画面表示 -->
				<table id="table6" class="input_parent">
					<colgroup>
						<col width="30%" />
						<col width="70%" />
					</colgroup>
					<tr>
						<td class="t6_td1">名前<span class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_m" style="ime-mode: active;"
							type="text" name="parent_name" value="{{$request->parent_name}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">名前カナ<span class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_m" style="ime-mode: active;"
							type="text" name="name_kana" value="{{$request->name_kana}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス１<span class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_m"
							style="ime-mode: inactive;" type="text"
							name="parent_mailaddress1"
							value="{{$request->parent_mailaddress1}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス２</td>
						<td class="t4td2"><input class="text_m"
							style="ime-mode: inactive;" type="text"
							name="parent_mailaddress2"
							value="{{$request->parent_mailaddress2}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">パスワード</td>
						<td class="t4td2"><input class="text_m" type="password"
							name="parent_pass" value="{{$request->parent_pass}}" /> @if(
							$request['id']) <br /> <span class="col_msg">※変更する場合のみ入力</span>
							@endif</td>
					</tr>
					<tr>
						<td class="t6_td1">郵便番号</td>
						<td>&#12306;&nbsp;<input class="text_ss"
							style="width: 40px; ime-mode: inactive;" type="text"
							name="zip_code1" value="{{$request->zip_code1}}" />&nbsp;－ <input
							class="text_ss" style="width: 60px; ime-mode: inactive;"
							type="text" name="zip_code2" value="{{$request->zip_code2}}" />
						</td>
					</tr>
					<tr>
						<td class="t6_td1">都道府県名<span class="aster">&lowast;</span></td>
						<td class="t4td2"><select name="pref_id" id="address_pref">
								<option value=""></option> @foreach($prefList as $item)
								<option value="{{$item['id']}}">{{$item['name']}}</option>
								@endforeach
						</select></td>
					</tr>
					<tr>
						<td class="t6_td1">市区町村名<span class="aster">&lowast;</span></td>
						<td class="t4td2"><select name="city_id" id="address_city"
							style="width: 200px">
								<option value=""></option> @foreach($cityListForParent as $item)
								<option value="{{$item['id']}}">{{$item['name']}}</option>
								@endforeach
						</select></td>
					</tr>
					<tr>
						<td class="t6_td1">番地・ビル名<span class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_l" style="ime-mode: active;"
							type="text" name="address" value="{{$request->address}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">自宅電話<span class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_m"
							style="ime-mode: inactive;" type="text" name="phone_no"
							value="{{$request->phone_no}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">携帯電話</td>
						<td class="t4td2"><input class="text_m"
							style="ime-mode: inactive;" type="text" name="handset_no"
							value="{{$request->handset_no}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">メモ</td>
						<td class="t4td2"><textarea style="ime-mode: active;" id="input3"
								name="memo" cols="30" rows="4">{{$request->memo}}</textarea></td>
					</tr>
				</table>
				@endif
			</div>

			<h4>受験</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>

				<tr>
					<td class="t6_td1">地域１</td>
					<td></td>
				</tr>
				<tr>
					<td class="t6_td1">都道府県名</td>
					<td class="t4td2"><select name="exam_pref1" id="exam_pref1">
							<option value=""></option> @foreach($prefList as $item)
							<option value="{{$item['id']}}">{{$item['name_kana']}}</option>
							@endforeach
					</select></td>
				</tr>
				<tr>
					<td class="t6_td1">市区町村名</td>
					<td class="t4td2"><select name="exam_city1" id="exam_city1"
						style="width: 200px">
							<option value=""></option> @foreach($examCity1List as $item)
							<option value="{{$item['id']}}">{{$item['name_kana']}}</option>
							@endforeach
					</select></td>
				</tr>
				<tr>
					<td class="t6_td1">地域２</td>
				</tr>
				<tr>
					<td class="t6_td1">都道府県名</td>
					<td class="t4td2"><select name="exam_pref2" id="exam_pref2">
							<option value=""></option> @foreach($prefList as $item)
							<option value="{{$item['id']}}">{{$item['name_kana']}}</option>
							@endforeach
					</select></td>
				</tr>
				<tr>
					<td class="t6_td1">市区町村名</td>
					<td class="t4td2"><select name="exam_city2" id="exam_city2"
						style="width: 200px">
							<option value=""></option> @foreach($examCity2List as $item)
							<option value="{{$item['id']}}">{{$item['name_kana']}}</option>
							@endforeach
					</select></td>
				</tr>
				<tr>
					<td class="t6_td1">地域３</td>
				</tr>
				<tr>
					<td class="t6_td1">都道府県名</td>
					<td class="t4td2"><select name="exam_pref3" id="exam_pref3">
							<option value=""></option> @foreach($prefList as $item)
							<option value="{{$item['id']}}">{{$item['name_kana']}}</option>
							@endforeach
					</select></td>
				</tr>
				<tr>
					<td class="t6_td1">市区町村名</td>
					<td class="t4td2"><select name="exam_city3" id="exam_city3"
						style="width: 200px">
							<option value=""></option> @foreach($examCity3List as $item)
							<option value="{{$item['id']}}">{{$item['name_kana']}}</option>
							@endforeach
					</select></td>
				</tr>
			</table>

			<h4>その他</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>

				<tr>
					<td class="t6_td1">メモ１</td>
					<td class="t4td2"><textarea style="ime-mode: active;" id="input3"
							name="memo1" cols="30" rows="4">{{$request->memo1}}</textarea></td>
				</tr>
				<tr>
					<td class="t6_td1">メモ２</td>
					<td class="t4td2"><textarea style="ime-mode: active;" id="input3"
							name="memo2" cols="30" rows="4">{{$request->memo2}}</textarea></td>
				</tr>
				<tr>
					<td class="t6_td1">メモ３</td>
					<td class="t4td2"><textarea style="ime-mode: active;" id="input3"
							name="memo3" cols="30" rows="4">{{$request->memo3}}</textarea></td>
				</tr>

			</table>

			<br />
			<div class="exe_button">
				<input class="submit3" type="button" value="戻る" /> <input
					class="submit2" type="button" value="確認" />
			</div>
		</form>
	</div>
	<!--section_content_in-->
</div>

@stop
