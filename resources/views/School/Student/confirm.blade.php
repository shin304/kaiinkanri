@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
	$(function() {
		$("#btn_submit").click(function() {
			$("#action_form").attr('action', '{{$_app_path}}student/complete');
			$("#action_form").submit();
			return false;
		});
		$("#btn_return").click(function() {
			$("#action_form").attr('action', '{{$_app_path}}student/entry');
			$("#action_form").submit();
			return false;
		});
	});
</script>
<!-- @include('_parts.student.menu') -->
<div id="center_content_header" class="box_border1">
	<h2 class="float_left">会員管理</h2>
	<div class="clr"></div>
</div>
@include('_parts.student.topic_list')
<h3 id="content_h3" class="box_border1">
	詳細情報

	<div id="section_content1">
		<form action="#" method="post" id="action_form">
			<form action="{{$_app_path}}student/complete" method="post">
				@include('_parts.student.hidden')

				<p class="section_content_in_p p14">下記の内容で間違いがなければ、実行ボタンをクリックしてください。</p>

				<h4>生徒</h4>
				<table id="table6">
					<colgroup>
						<col width="30%" />
						<col width="70%" />
					</colgroup>
					<tr>
						<td class="t6_td1">種別<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="student_type"
							value="{{$request.student_type}}" /> {{$student_type_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">名前<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="student_name"
							value="{{$request.student_name}}" /> {{$request.student_name}}</td>
					</tr>
					@if($request.student_no)
					<tr>
						<td class="t6_td1">生徒番号<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="student_no"
							value="{{$request.student_no}}" /> {{$request.student_no}}</td>
					</tr>
					@endif
					<tr>
						<td class="t6_td1">フリガナ<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="student_kana"
							value="{{$request.student_kana}}" /> {{$request.student_kana}}</td>
					</tr>
					<tr>
						<td class="t6_td1">ニックネーム<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="student_nickname"
							value="{{$request.student_nickname}}" />
							{{$request.student_nickname}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="student_mail"
							value="{{$request.student_mail}}" /> {{$request.student_mail}}</td>
					</tr>
					<tr>
						<td class="t6_td1">生年月日<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="student_birth_year"
							value="{{$request.student_birth_year}}" />
							{{$request.student_birth_year}}年&nbsp; <input type="hidden"
							name="student_birth_month"
							value="{{$request.student_birth_month}}" />
							{{$request.student_birth_month}}月&nbsp; <input type="hidden"
							name="student_birth_day" value="{{$request.student_birth_day}}" />
							{{$request.student_birth_day}}日</td>
					</tr>
					<tr>
						<td class="t6_td1">性別</td>
						<td class="t4td2"><input type="hidden" name="student_sex"
							value="{{$request.student_sex}}" /> @if ($request.student_sex ==
							1) 男性 @else 女性 @endif</td>
					</tr>
					<tr>
						<td class="t6_td1">学校名<span class="required">*</span></td>
						<td class="t4td2"><input type="hidden" name="school_name"
							value="{{$request.school_name}}" /> {{$request.school_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">生徒区分<span class="required">*</span></td>
						<td class="t4td2">@if ($request.school_category | is_numeric)
							{{$schoolCategory[$request.school_category]}} @if
							($request.school_year) {{$request.school_year}}年 @endif @endif</td>
					</tr>
					<tr>
						<td class="t6_td1">問合せ日</td>
						<td>@if ($request.inquiry_date != 0) <input type="hidden"
							name="inquiry_date" value="{{$request.inquiry_date}}" />
							{{$request.inquiry_date|date_format:"%Y年%m月%d日"}} @endif
						</td>
					</tr>
					<tr>
						<td class="t6_td1">入塾日</td>
						<td>@if ($request.enter_date != 0) <input type="hidden"
							name="enter_date" value="{{$request.enter_date}}" />
							{{$request.enter_date | date_format:"%Y年%m月%d日"}} @endif
						</td>
					</tr>
					<tr>
						<td class="t6_td1">退塾日</td>
						<td>@if ($request.resign_date != 0) <input type="hidden"
							name="resign_date" value="{{$request.resign_date}}" />
							{{$request.resign_date|date_format:"%Y年%m月%d日"}} @endif
						</td>
					</tr>
				</table>

				<h4>保護者</h4>
				@include ('_parts.student.confirm') @if ($request.parent)
				<table id="table6">
					<colgroup>
						<col width="30%" />
						<col width="70%" />
					</colgroup>
					<tr>
						<td class="t6_td1">名前</td>
						<td class="t4td2">{{$request.parent.parent_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス1</td>
						<td class="t4td2">{{$request.parent.parent_mailaddress1}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス2</td>
						<td class="t4td2">{{$request.parent.parent_mailaddress2}}</td>
					</tr>

					<tr>
						<td class="t6_td1">郵便番号</td>
						<td class="t4td2">{{$request.parent.zip_code1}}－{{$request.parent.zip_code2}}</td>
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
						<td class="t4td2">{{$request.parent.address}}</td>
					</tr>
				</table>
				@else
				<table id="table6" class="input_parent">
					<colgroup>
						<col width="30%" />
						<col width="70%" />
					</colgroup>
					<tr>
						<td class="t6_td1">名前<span class="aster">&lowast;</span></td>
						<td class="t4td2">{{$request.parent_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">名前カナ<span class="aster">&lowast;</span></td>
						<td class="t4td2">{{$request.name_kana}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス１<span class="aster">&lowast;</span></td>
						<td class="t4td2">{{$request.parent_mailaddress1}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メールアドレス２</td>
						<td class="t4td2">{{$request.parent_mailaddress2}}</td>
					</tr>
					<tr>
						<td class="t6_td1">パスワード</td>
						<td class="t4td2">@if ($request.parent_pass) [変更します] @else
							[変更しません] @endif</td>
					</tr>
					<tr>
						<td class="t6_td1">郵便番号</td>
						<td>
							&#12306;&nbsp;{{$request.zip_code1}}&nbsp;－{{$request.zip_code2}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">都道府県名<span class="required">*</span></td>
						<td class="t4td2">{{$pref_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">市区町村名<span class="required">*</span></td>
						<td class="t4td2">{{$city_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">番地・ビル名<span class="aster">&lowast;</span></td>
						<td class="t4td2">{{$request.address}}</td>
					</tr>
					<tr>
						<td class="t6_td1">自宅電話<span class="aster">&lowast;</span></td>
						<td class="t4td2">{{$request.phone_no}}</td>
					</tr>
					<tr>
						<td class="t6_td1">携帯電話</td>
						<td class="t4td2">{{$request.handset_no}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メモ</td>
						<td class="t4td2">{{$request.memo}}</td>
					</tr>
				</table>
				@endif

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
						<td class="t4td2"><input type="hidden" name="exam_pref1"
							value="{{$request.exam_pref1}}" /> {{$exam_pref1_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">市区町村名</td>
						<td class="t4td2"><input type="hidden" name="exam_city1"
							value="{{$request.exam_city1}}" /> {{$exam_city1_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">地域２</td>
					</tr>
					<tr>
						<td class="t6_td1">都道府県名</td>
						<td class="t4td2"><input type="hidden" name="exam_pref2"
							value="{{$request.exam_pref2}}" /> {{$exam_pref2_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">市区町村名</td>
						<td class="t4td2"><input type="hidden" name="exam_city2"
							value="{{$request.exam_city2}}" /> {{$exam_city2_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">地域３</td>
					</tr>
					<tr>
						<td class="t6_td1">都道府県名</td>
						<td class="t4td2"><input type="hidden" name="exam_pref3"
							value="{{$request.exam_pref3}}" /> {{$exam_pref3_name}}</td>
					</tr>
					<tr>
						<td class="t6_td1">市区町村名</td>
						<td class="t4td2"><input type="hidden" name="exam_city3"
							value="{{$request.exam_city3}}" /> {{$exam_city3_name}}</td>
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
						<td class="t4td2">{{$request.memo1}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メモ２</td>
						<td class="t4td2">{{$request.memo2}}</td>
					</tr>
					<tr>
						<td class="t6_td1">メモ３</td>
						<td class="t4td2">{{$request.memo3}}</td>
					</tr>
				</table>

				<br />
				<div class="exe_button">
					<input class="submit3" type="submit" value="戻る" id="btn_return" />
					<input class="submit2" type="submit" value="確認" id="btn_submit" />
				</div>

			</form>
		</form>
	</div>
	<script type="text/javascript">
	$(function() {
		$("#btn_submit").click(function() {
			$("#action_form").attr('action', '{{$_app_path}}student/complete');
			$("#action_form").submit();
			return false;
		});
		$("#btn_return").click(function() {
			$("#action_form").attr('action', '{{$_app_path}}student/entry');
			$("#action_form").submit();
			return false;
		});
	});
</script>
	@stop