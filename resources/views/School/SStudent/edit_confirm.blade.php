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
<script type="text/javascript">
$(function() {
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
	@if ($request['id']) {{$lan['edit_title']}} @else
	{{$lan['register_title']}} @endif{{$lan['confirm_title']}}</h3>

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
							($request['student_img']) <img src="{{$request->student_img}}"
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
						class="required">&lowast;</span></td>
					<td class="t4td2"><input type="hidden" name="student_name"
						value="{{$request->student_name}}" /> {{$request->student_name}}</td>
				</tr>
				@if ($request['student_no'])
				<tr>
					<td class="t6_td1">{{$lan['member_no_title']}}<span
						class="required">&lowast;</span></td>
					<td class="t4td2"><input type="hidden" name="id"
						value="{{$request->id}}" /> <input type="hidden"
						name="orgparent_id" value="{{$request->orgparent_id}}" /> <input
						type="hidden" name="student_no" value="{{$request->student_no}}" />
						{{$request->student_no}}</td>
				</tr>
				@endif
				<tr>
					<td class="t6_td1">{{$lan['furigana_title']}}</td>
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
				<tr>
					<td class="t6_td1">{{$lan['join_date_title']}}</td>
					<td>@if ($request['enter_date'] != 0) <input type="hidden"
						name="enter_date" value="{{$request->enter_date}}" />
						{{$request->enter_date}} @endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['join_memo_title']}}</td>
					<td>{{$request->enter_memo}} <input type="hidden" name="enter_memo"
						value="{{$request->enter_memo}}" />
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['withdraw_date_title']}}</td>
					<td>@if ($request['resign_date'] != 0) <input type="hidden"
						name="resign_date" value="{{$request->resign_date}}" />
						{{$request->resign_date}} @endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['withdraw_memo_title']}}</td>
					<td><input type="hidden" name="resign_memo"
						value="{{$request->resign_memo}}" /> {{$request->resign_memo}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['postal_code_title']}}</td>
					<td><input type="hidden" name="student_zip_code1"
						value="{{$request->student_zip_code1}}" /> <input type="hidden"
						name="student_zip_code2" value="{{$request->student_zip_code2}}" />
						&#12306;&nbsp;{{$request->student_zip_code1}}&nbsp;－{{$request->student_zip_code2}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['state_name_title']}}<span
						class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="student_pref_name"
						value="{{$student_address_pref_name}}" />
						{{$student_address_pref_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['city_name_title']}}<span
						class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="student_city_name"
						value="{{$student_address_city_name}}" />
						{{$student_address_city_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['address_building_name_title']}}<span
						class="aster">&lowast;</span></td>
					<td class="t4td2"><input type="hidden" name="student_address"
						value="{{$request->student_address}}" />
						{{$request->student_address}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['home_phone_title']}}<span class="aster">&lowast;</span></td>
					<td class="t4td2"><input type="hidden" name="student_phone_no"
						value="{{$request->student_phone_no}}" />
						{{$request->student_phone_no}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['mobile_phone_title']}}</td>
					<td class="t4td2"><input type="hidden" name="student_handset_no"
						value="{{$request->student_handset_no}}" />
						{{$request->student_handset_no}}</td>
				</tr>
			</table>

			<h4>{{$lan['billing_title']}}</h4>

			@include('_parts.student.hidden')
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['given_name']}}<span class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="parent_name"
						value="{{$request->parent_name}}" /> {{$request->parent_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['kana_name']}}<span class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="name_kana"
						value="{{$request->name_kana}}" /> {{$request->name_kana}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['email_address_1']}}<span
						class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="parent_mailaddress1"
						value="{{$request->parent_mailaddress1}}" />
						{{$request->parent_mailaddress1}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['email_address_2']}}</td>
					<td class="t4td2"><input type="hidden" name="parent_mailaddress2"
						value="{{$request->parent_mailaddress2}}" />
						{{$request->parent_mailaddress2}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['password']}}</td>
					<td class="t4td2">@if ($request['orgparent_id']) @if
						($request->parent_pass) {{$lan['change']}} @else
						{{$lan['it_does_not_change']}} @endif @else @endif <input
						type="hidden" name="parent_pass" value="{{$request->parent_pass}}" />
					</td>
				</tr>

			</table>

			<h4>{{$lan['street_address']}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['postal_code']}}</td>
					<td class="t4td2"><input type="hidden" name="zip_code1"
						value="{{$request->zip_code1}}" /> <input type="hidden"
						name="zip_code2" value="{{$request->zip_code2}}" />
						{{$request->zip_code1}}－{{$request->zip_code2}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['name_of_prefectures']}}<span
						class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="pref_name"
						value="{{$pref_name}}" /> {{$pref_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['city_name']}}<span class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="city_name"
						value="{{$city_name}}" /> {{$city_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['bunch_building_name']}}<span
						class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="address"
						value="{{$request->address}}" /> {{$request->address}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['home_phone']}}<span class="required">*</span></td>
					<td class="t4td2"><input type="hidden" name="phone_no"
						value="{{$request->phone_no}}" /> {{$request->phone_no}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['mobile_phone']}}</td>
					<td class="t4td2"><input type="hidden" name="handset_no"
						value="{{$request->handset_no}}" /> {{$request->handset_no}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['memo']}}</td>
					<td class="t4td2"><input type="hidden" name="memo"
						value="{{$request->memo}}" /> {{$request->memo}}</td>
				</tr>
			</table>

			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1"><strong>{{$lan['payment_method']}}</strong></td>
					<td>@if ($request['invoice_type'] == 0) {{$lan['cash']}} @elseif
						($request->invoice_type == 1) {{$lan['transfer']}} @elseif
						($request->invoice_type == 2) {{$lan['account_transfer']}} @else
						{{$lan['other']}} @endif <input type="hidden" name="invoice_type"
						value="{{$request->invoice_type}}" />
					</td>
				</tr>
				<tr>
					<td class="t6_td1"><strong>{{$lan['notification_method']}}</strong></td>
					<td>@if ($request['mail_infomation'] == 0) {{$lan['mailing']}}
						@elseif ($request['mail_infomation'] == 1) {{$lan['email']}} @else
						{{$lan['other']}} @endif <input type="hidden"
						name="mail_infomation" value="{{$request->mail_infomation}}" />
					</td>
				</tr>
			</table>
			@if ($request['invoice_type'] == 2 && $request['bank_type'] == 1) <input
				type="hidden" name="bank_type" value="{{$request->bank_type}}" />
			<h4>{{$lan['account_information_bank_credit_union']}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['bank_code']}}</td>
					<td><input type="hidden" name="bank_code"
						value="{{$request->bank_code}}" /> {{$request->bank_code}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['financial_institution_name']}}</td>
					<td><input type="hidden" name="bank_name"
						value="{{$request->bank_name}}" /> {{$request->bank_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['branch_code']}}</td>
					<td><input type="hidden" name="bank_type"
						value="{{$request->branch_code}}" /> {{$request->branch_code}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['branch_name']}}</td>
					<td><input type="hidden" name="branch_name"
						value="{{$request->branch_name}}" /> {{$request->branch_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['classification']}}</td>
					<td>@foreach ($bank_account_type_list as $type) {{$type}} <input
						type="hidden" name="bank_account_type" value="{{$type}}" />
						@endforeach
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['account_number']}}</td>
					<td><input type="hidden" name="bank_account_number"
						value="{{$request->bank_account_number}}" />
						{{$request->bank_account_number}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['account_holder']}}</td>
					<td><input type="hidden" name="bank_account_name"
						value="{{$request->bank_account_name}}" />
						{{$request->bank_account_name}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['account_kana_name']}}</td>
					<td><input type="hidden" name="bank_account_name_kana"
						value="{{$request->bank_account_name_kana}}" />
						{{$request->bank_account_name_kana}}</td>
				</tr>
			</table>
			@endif @if ($request['invoice_type'] == 2 && $request['bank_type'] ==
			2)
			<h4>{{$lan['account_information_post_office']}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['passbook_symbol']}} <span
						class="required">*</span>
					</td>
					<td><input type="hidden" name="post_account_kigou"
						value="{{$request->post_account_kigou}}" />
						{{$request->post_account_kigou}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['passbook_number']}} <span
						class="required">*</span>
					</td>
					<td><input type="hidden" name="post_account_number"
						value="{{$request->post_account_number}}" />
						{{$request->post_account_number}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan['passbook_name']}} <span class="required">*</span>
					</td>
					<td><input type="hidden" name="post_account_name"
						value="{{$request->post_account_name}}" />
						{{$request->post_account_name}}</td>
				</tr>
			</table>
			@endif

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

			<br /> @if (isset($grades))
			<tr>
				<td class="t6_td1">{{$lan['obi_history_title']}}</td>
				<td class="t4td2">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td style="border-style: none;">{{$lan['obi_color_title']}}</td>
							<td style="border-style: none;"><select name="grade_id"
								style="max-width: 200px" disabled="disabled">
									<option value=""></option> @foreach($grades as $item)
									<option value="{{$item}}">{{$item}}</option> @endforeach
							</select></td>
							<td style="border-style: none;"></td>
							<td style="border-style: none;">{{$lan['acquisition_date_title']}}</td>
							<td style="border-style: none;"><input type="text"
								name="gain_date"
								value="@if ($request['gain_date'])
													{{$request->gain_date}}
												@endif"
								disabled="disabled" /> <input type="hidden"
								name="_grades_remove_ids"
								value="{{$request->_grades_remove_ids}}" /></td>
							<td style="border-style: none;"></td>
							<td style="border-style: none;">@if ($request['grade_id'])
								[{{$lan['add_title']}}] @else [{{$lan['add_title']}}] @endif</td>
						</tr>
						@foreach($request['grade_list'] as $idx => $row)
						<tr>
							<td style="border-style: none;"></td>
							<td style="border-style: none;"><select
								name="grade_list[{{$idx}}]['grade_id']"
								style="max-width: 200px;" disabled="disabled">
									<option value=""></option> @foreach($grades as $item)
									<option value="{{$item}}">{{$item}}</option> @endforeach
							</select></td>
							<td style="border-style: none;"></td>
							<td style="border-style: none;"></td>
							<td style="border-style: none;"><input type="hidden"
								name="grades_list[{{$idx}}]['id']" value="{{$row->id}}" /> <input
								type="hidden" name="grades_list[{{$idx}}]['grade_id']"
								value="{{$row->grade_id}}" /> <input type="hidden"
								name="grades_list[{{$idx}}]['gain_date']"
								value="{{$row->gain_date}}" /> <input type="hidden"
								name="grades_list[{{$idx}}]['gain_name']"
								value="{{$row->gain_name}}" /> <input type="hidden"
								name="grades_list[{{$idx}}]['gain_color']"
								value="{{$row->gain_color}}" /> <input class="DateInput"
								type="text" name="grade_list[{{$idx}}]['gain_date']"
								value="@if ($row['gain_date'])
													{{$row->gain_date}}
													@endif"
								disabled="disabled" /></td>
							<td style="border-style: none;"></td>
							<td style="border-style: none;"></td>
						</tr>
						@endforeach
					</table> 帯色: {{$grades[$request->grade_id]}} 取得日:
					{{$request->gain_date}}
				</td>
			</tr>
			@endif
			<div class="exe_button">
				<input class="submit3" type="submit"
					value="{{$lan['return_title']}}" id="btn_return" /> <input
					class="submit2" type="submit" value="{{$lan['confirm_title']}}"
					id="btn_submit" />
			</div>
			</table>
		</form>

</div>
</div>
</td>

@stop
