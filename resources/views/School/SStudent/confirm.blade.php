@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
	$(function() {
		$("#btn_submit").click(function() {
			$("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
			$("#action_form").submit();
			return false;
		});
		$("#btn_return").click(function() {
			$("#action_form").attr('action', '{{ URL::to('/school/student/entry') }}');
			$("#action_form").submit();
			return false;
		});
	});
</script>
<div id="center_content_header" class="box_border1">
	<h2 class="float_left"><i class="fa fa-group"></i> {{$lan::get('main_title')}}</h2>
	<div class="center_content_header_right">
		<div class="top_btn"></div>
	</div>
	<div class="clr"></div>
</div>
<!--center_content_header-->

<div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	@foreach ($topic_list as $link => $topic) <a class="text_link"
		href="{{$_app_path}}{{$link}}">{{$topic}}</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;
	@if ($loop->last) {{$topic}} @endif @endforeach
</div>
<h3 id="content_h3" class="box_border1">{{$lan['detailed_information_title']}}
</h3>
<div id="section_content1">

	<form action="#" method="post" id="action_form">
		<form action="{{$_app_path}}student/complete" method="post">
			{{ csrf_field() }} @include('_parts.student.hidden')
			<p class="section_content_in_p p14">{{$lan['click_on_the_confirm_button_title']}}</p>

			<h4>{{$lan['member_title']}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['image_title']}}<span class="aster"></span></td>
					<td class="t4td2">
						<div class="imgInput">
							<input type="hidden" name="card_img"
								value="{{$request->card_img}}" /> <input type="hidden"
								name="student_img" value="{{$request->student_img}}" /> @if
							($request['student_img']) <img src="{{$request['student_img']}}"
								alt="" class="imgView" /> @endif
						</div>
						<!--/.imgInput-->
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['category_title']}}<span class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="student_type"
						value="{{$student_type}}" />{{$student_type}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['first_name_title']}}<span
						class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="student_name"
						value="{{$request->student_name}}" /> {{$request->student_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['furigana_title']}}<span class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="student_kana"
						value="{{$request->student_kana}}" /> {{$request->student_kana}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['latin_alphabet_title']}}</td>
					<td class="t4td2"><input type="hidden" name="student_romaji"
						value="{{$request->student_romaji}}" />
						{{$request->student_romaji}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['email_address_title']}}<span
						class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="student_mail"
						value="{{$request->student_mail}}" /> {{$request->student_mail}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['birthday_title']}}<span class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="student_birth_year"
						value="{{$request->student_birth_year}}" />
						{{$request->student_birth_year}}{{$lan['year_title']}}&nbsp; <input
						type="hidden" name="student_birth_month"
						value="{{$request->student_birth_month}}" />
						{{$request->student_birth_month}}{{$lan['month_title']}}&nbsp; <input
						type="hidden" name="student_birth_day"
						value="{{$request->student_birth_day}}" />
						{{$request->student_birth_day}}{{$lan['day_title']}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['gender_title']}}</td>
					<td class="t4td2"><input type="hidden" name="student_sex"
						value="{{$student_sex}}" /> {{$student_sex}}</td>
				</tr>
			</table>
			<h4>{{$lan['billing_title']}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['first_name_title']}}<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input type="hidden" name="parent_name"
						value="{{$request->parent_name}}" /> {{$request->parent_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">名前カナ</td>
					<td class="t4td2"><input type="hidden" name="name_kana"
						value="{{$request->name_kana}}" /> {{$request->name_kana}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['email_address_1_title']}}<span
						class="aster">&lowast;</span></td>
					<td class="t4td2"><input type="hidden" name="parent_mailaddress1"
						value="{{$request->parent_mailaddress1}}" />
						{{$request->parent_mailaddress1}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['email_address_2_title']}}</td>
					<td class="t4td2"><input type="hidden" name="parent_mailaddress2"
						value="{{$request->parent_mailaddress2}}" />
						{{$request->parent_mailaddress2}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['password_title']}}<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input type="hidden" name="parent_pass"
						value="{{$request->parent_pass}}" /> @if ($request['id']) @if
						($request['parent_pass']) [{{$lan['change_title']}}] @else
						[{{$lan['no_change_title']}}] @endif @endif</td>
				</tr>

			</table>
			<h4>{{$lan['premium_discount']}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['premium_discount_items']}}</td>
					<td>@if ($request->payment) @foreach ($request['payment'] as $key
						=> $row)
						<div class="InputArea">
							<table style="width: 750px;">
								<tr>
									<td width="100">@if ($row['payment_month'] == 99)
										{{$lan['target_month_monthly']}} @else <input type="hidden"
										name="payment[{{$loop->index}}][payment_month]"
										value="{{$row['payment_month']}}" />
										{{$lan['target_month']}}{{$row['payment_month']}}{{$lan['month']}}
										@endif
									</td>
									<td width="120"><input type="hidden"
										name="payment[{{$loop->index}}][payment_adjust]"
										value="{{$row['payment_adjust']}}" />
										{{$lan['abstract']}}&nbsp;{{$row['payment_adjust']}}</td>
									<td width="120">@if ($row['payment_fee'] != null) <input
										type="hidden" name="payment[{{$loop->index}}][payment_fee]"
										value="{{$row['payment_fee']}}" />
										{{$lan['price']}}&nbsp;{{$row['payment_fee']}}&nbsp;{{$lan['circle']}}
										@endif
									</td>
								</tr>
							</table>
						</div> @endforeach @endif
					</td>
				</tr>
			</table>
			<h4>{{$lan['other_title']}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['memo1_title']}}</td>
					<td class="t4td2"><input type="hidden" name="memo1"
						value="{{$request->memo1}}" /> {{$request->memo1}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['memo2_title']}}</td>
					<td class="t4td2"><input type="hidden" name="memo2"
						value="{{$request->memo2}}" /> {{$request->memo2}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['memo3_title']}}</td>
					<td class="t4td2"><input type="hidden" name="memo3"
						value="{{$request->memo3}}" /> {{$request->memo3}}</td>
				</tr>
			</table>

			<br />
			<div class="exe_button">
				<input class="submit3" type="submit"
					value="{{$lan['return_title']}}" id="btn_return" /> <input
					class="submit2" type="submit" value="{{$lan['confirm_title']}}"
					id="btn_submit" />
			</div>

		</form>

</div>
</div>
</td>
@stop
