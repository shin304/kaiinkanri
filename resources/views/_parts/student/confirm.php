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
</head>
@include('_parts.student.hidden')
<table id="table6">
	<colgroup>
		<col width="30%" />
		<col width="70%" />
	</colgroup>
	<tr>
		<td class="t6_td1">{{$given_name}}<span class="required">*</span></td>
		<td class="t4td2">{{$request->parent_name}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$kana_name}}<span class="required">*</span></td>
		<td class="t4td2">{{$request->name_kana}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$email_address_1}}<span class="required">*</span></td>
		<td class="t4td2">{{$request->parent_mailaddress1}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$email_address_2}}</td>
		<td class="t4td2">{{$request->parent_mailaddress2}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$password}}</td>
		<td class="t4td2">@if ($request->orgparent_id) @if
			($request->parent_pass) {{$change}} @else {{$it_does_not_change}}
			@endif @else <input type="hidden" value="{{$request->parent_pass}}" />
			@endif
		</td>
	</tr>

</table>

<h4>{{$street_address}}</h4>
<table id="table6">
	<colgroup>
		<col width="30%" />
		<col width="70%" />
	</colgroup>
	<tr>
		<td class="t6_td1">{{$postal_code}}</td>
		<td class="t4td2">{{$request->zip_code1}}－{{$request->zip_code2}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$name_of_prefectures}}<span class="required">*</span></td>
		<td class="t4td2">{{$pref_name}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$city_name}}<span class="required">*</span></td>
		<td class="t4td2">{{$city_name}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$bunch_building_name}}<span class="required">*</span></td>
		<td class="t4td2">{{$request->address}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$home_phone}}<span class="required">*</span></td>
		<td class="t4td2">{{$request->phone_no}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$mobile_phone}}</td>
		<td class="t4td2">{{$request->handset_no}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$memo}}</td>
		<td class="t4td2">{{$request->memo}}</td>
	</tr>
</table>

<table id="table6">
	<colgroup>
		<col width="30%" />
		<col width="70%" />
	</colgroup>
	<tr>
		<td class="t6_td1"><strong>{{$payment_method}}</strong></td>
		<td>@if ($request->invoice_type == 0) {{$cash}} @elseif
			($request->invoice_type == 1) {{$transfer}} @elseif
			($request->invoice_type == 2) {{$account_transfer}} @else {{$other}}
			@endif</td>
	</tr>
	<tr>
		<td class="t6_td1"><strong>{{$notification_method}}</strong></td>
		<td>@if ($request->mail_infomation == 0) {{$mailing}} @elseif
			($request->mail_infomation == 1) {{$email}} @else {{$other}} @endif</td>
	</tr>
</table>
@if ($request->invoice_type == 2 && $request->bank_type == 1)
<h4>{{$account_information_bank_credit_union}}</h4>
<table id="table6">
	<colgroup>
		<col width="30%" />
		<col width="70%" />
	</colgroup>
	<tr>
		<td class="t6_td1">{{$bank_code}}</td>
		<td>{{$request->bank_code}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$financial_institution_name}}</td>
		<td>{{$request->bank_name}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$branch_code}}</td>
		<td>{{$request->branch_code}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$branch_name}}</td>
		<td>{{$request->branch_name}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$classification}}</td>
		<td>@foreach ($bank_account_type_list as type) {{$type}} @endforeach</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$account_number}}</td>
		<td>{{$request->bank_account_number}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$account_holder}}</td>
		<td>{{$request->bank_account_name}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$account_kana_name}}</td>
		<td>{{$request->bank_account_name_kana}}</td>
	</tr>
</table>
@endif @if ($request->invoice_type == 2 && $request->bank_type == 2)
<h4>{{$account_information_post_office}}</h4>
<table id="table6">
	<colgroup>
		<col width="30%" />
		<col width="70%" />
	</colgroup>
	<tr>
		<td class="t6_td1">{{$passbook_symbol}} <span class="required">*</span>
		</td>
		<td>{{$request->post_account_kigou}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$passbook_number}} <span class="required">*</span>
		</td>
		<td>{{$request->post_account_number}}</td>
	</tr>
	<tr>
		<td class="t6_td1">{{$passbook_name}} <span class="required">*</span>
		</td>
		<td>{{$request->post_account_name}}</td>
	</tr>
</table>
@endif




<h4>{{$premium_discount}}</h4>
<table id="table6">
	<colgroup>
		<col width="30%" />
		<col width="70%" />
	</colgroup>
	<tr>
		<td class="t6_td1">{{$premium_discount_items}}</td>
		<td>@if ($request->payment) @foreach ($request->payment as row)
			<div class="InputArea">
				<table style="width: 750px;">
					<tr>
						<td width="120">@if ($row['payment_month'] == 99)
							{{$target_month_monthly}} @else
							{{$target_month}}{{$row['payment_month']}}{{$month}} @endif</td>
						<td width="120"">{{$abstract}}&nbsp;{{$row['payment_adjust_name']}}</td>
						<td width="120"">@if ($row['payment_fee'] != null) @if
							($row['payment_fee'] < 0) @assign var="aaa"
							value=$row['payment_fee'] }} {{ math equation=abs(a) a=$aaa
							assign=result }}
							{{$price}}&nbsp;▲{{$result|number_format}}&nbsp;{{$circle}} @else
							{{$price}}&nbsp;{{$row['payment_fee']|number_format}}&nbsp;{{$circle}}
							@endif @endif</td>
					</tr>
				</table>
			</div>@endforeach @endif
		</td>
	</tr>
</table>

