<script type="text/javascript">
var	$bInit = true;

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
	$(".submit3").click(function() {
		@if( $request->orgparent_id)
			$("#entry_form").attr('action',	'{{$_app_path}}parent/detail');
		@else
			$("#entry_form").attr('action',	'{{$_app_path}}parent');
		@endif
		$("#entry_form").submit();
		return false;
	});
	$(".submit2").click(function() {
		$("#entry_form").attr('action',
				'{{$_app_path}}parent/confirm');
		$("#entry_form").submit();
		return false;
	});
	$("#invoicetype").change(function(){
		var type = $("#invoicetype").val();
		if( type != 2 ){
			$("#mailinfo").val("1");
			$("#invoiceinfo").hide();
			$("#bankinfo").hide();
			$("#postinfo").hide();
		}
		else {
			$("#mailinfo").val("0");
			$("#invoiceinfo").show();
			@if( $request->bank_type == 2}}
				$("#bankinfo").hide();
				$("#postinfo").show();
			{{elseif $request->bank_type == 1}}
				$("#postinfo").hide();
				$("#bankinfo").show();
			@else
				$("#postinfo").hide();
				$("#bankinfo").show();
			@endif
		}
	});
	$("#banktype").change(function(){
		var type = $("#banktype").val();
		if( type == 1 ){			// 銀行
			$("#postinfo").hide();
			$("#bankinfo").show();
		}
		else if( type == 2 ){
			$("#bankinfo").hide();
			$("#postinfo").show();
		}
	});

	$("input[name='parent_mailaddress2']").change(function(){
		if( $bInit ){
			var strMail = "{{$request->parent_mailaddress2}}";
			if( strMail.length < 1 ){
				$(this).val("");
			}
		}
		$bInit = false;
	});

});


$(function(){
	$( "A.inputDelete").click( function(e){
		var curr_id = $(this).attr("id");
 		var split = curr_id.split("_");
 		$('input[name="payment[' + split[1] + '][payment_del]"]').val('1');	// 削除フラグをON
		var activeTable = $(this).parent(".t4d2");
		e.preventDefault();
		inputDel( activeTable );
		return false;
	});
});

$(function() {

	// 受講料以外追加
	$("#inputAdd").click(function(){

		var newTable = $( "TABLE", "#inputBase" ).clone();//inputBaseのIDのTABLEタグをnewTableへ
		var newHR    = $( "HR"   , "#inputBase" ).clone();//inputBaseのIDのHRタグをnewHRへ

		$( ".formItem", newTable ).each( function(){//newTable内のformItemプラン指定のそれれぞれで
			var title = $( this ).attr( 'title' );//title属性の内容を変数titleへ
			$( this ).attr( 'name', 'payment[' + nowInputIndex + '][' +  title + ']');//name属性の内容をinput[nowInputIndex][title]へ
			$( this ).removeAttr( 'title' );//title属性を削除する

		});

		$( ".NewPaymentMonth",newTable).attr( 'id', 'payment_month_' + nowInputIndex  ).removeClass('NewPaymentMonth');//newTable内のNewDateInputプラン指定でid属性をDateInput_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".NewPaymentAdjust",newTable).attr( 'id', 'payment_adjust_' + nowInputIndex  ).removeClass('NewPaymentAdjust');//newTable内のNewex_fromTimeプラン指定でid属性をex_fromTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".NewPaymentFee",newTable).attr( 'id', 'payment_fee_' + nowInputIndex  ).removeClass('NewPaymentFee');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".NewPaymentId",newTable).attr( 'id', 'payment_id_' + nowInputIndex  ).removeClass('NewPaymentId');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".NewPaymentDel",newTable).attr( 'id', 'payment_del_' + nowInputIndex  ).removeClass('NewPaymentDel');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する

		$( "#inputActive" ).append( newTable );//inputActiveのID指定にnewTableの内容を追加する
		$( "#inputActive" ).append( newHR    );//inputActiveのID指定にnewHRの内容を追加する

		// 削除処理設定
		$( "A.inputDelete", newTable ).click( function(e){

			e.preventDefault();
			inputDel( newTable );
			return false;
		});

		// 摘要の初期値取得
//		$( "#payment_adjust_" + nowInputIndex, newTable ).change( function(e){
//			var aaa = $(this).val();
//		});

		$( "#payment_adjust_" + nowInputIndex, newTable ).change( function(e){

			var adjust = $(this).val();

			var id = $(this).attr("id");
			var split = id.split("_");
			var no = split[2];

			$.get(
				"/school/ajaxInvoice/getinitfee",
				{adjust: adjust},
				function(v_data)
				{
						// 金額設定
						$("#payment_fee_" + no).val(v_data);
					},
					"jsonp"
			);
			return false;
		});


		// 表示
		$( newTable ).show();

		nowInputIndex++;

		return false;
	});
});

// 削除
function inputDel( item ){
//	$( item ).next().remove();
	$( item ).remove();

//	$( item ).empty();
	return false;
}

@if (isset($request['payment']))
var nowInputIndex =  {{ $request['payment'] }};
@else
var nowInputIndex =  0;
@endif


</script>

<script type="text/javascript">
function nextForm(event)
{
	if (event.keyCode == 0x0d)
	{
		var current = document.activeElement;

		var forcus = 0;
		for( var idx = 0; idx < document.entry_form.elements.length; idx++){
			if( document.entry_form[idx] == current ){
				forcus = idx;
				break;
			}
		}
		document.entry_form[(forcus + 1)].focus();
	}
}
window.document.onkeydown = nextForm;
</script>

</head>
@if (isset($errors))
<ul class="message_area">
</ul>
<br />
@endif
<span class="aster">&lowast;</span>
{{$mandatory_items_marked}}

<form id="entry_form" name="entry_form">
	<input type="hidden" name="function" value="{{$request['function']}}" />
	<input type="hidden" name="login_account_id"
		value="{{$request['login_account_id']}}" /> @if
	(isset($request['orgparent_id'])) <input type="hidden"
		name="orgparent_id" value="{{$request['orgparent_id']}}" /> @endif @if
	(isset($request['link_enable'])) @include('_parts.student.hidden')
	@endif

	<table id="table6">
		<colgroup>
			<col width="30%" />
			<col width="70%" />
		</colgroup>
		<tr>
			<td class="t6_td1">{{$given_name}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_m" style="ime-mode: active;"
				type="text" name="parent_name" value="{{$request->parent_name}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$kana_name}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_m" style="ime-mode: active;"
				type="text" name="name_kana" value="{{$request->name_kana}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$email_address_1}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_m" style="ime-mode: inactive;"
				type="text" name="parent_mailaddress1"
				value="{{$request->parent_mailaddress1}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$email_address_2}}</td>
			<td class="t4td2"><input class="text_m" style="ime-mode: inactive;"
				type="text" name="parent_mailaddress2"
				value="{{$request->parent_mailaddress2}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$password}}</td>
			<td class="t4td2"><input class="text_m" type="password"
				name="parent_pass" @if (isset($request['orgparent_id']))
						/> <br /> <span class="col_msg">{{$only_change_input}}</span>
				@else value="{{$request->parent_pass}}"/> @endif</td>
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
			<td>&#12306;&nbsp;<input class="text_ss"
				style="width: 40px; ime-mode: inactive;" type="text"
				name="zip_code1" value="{{$request->zip_code1}}" />&nbsp;－ <input
				class="text_ss" style="width: 60px; ime-mode: inactive;" type="text"
				name="zip_code2" value="{{$request->zip_code2}}" />
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$prefecture_name}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><select name="pref_id" id="address_pref"
				style="width: 200px"> @if(isset($request->pref_id))
					@foreach($prefList as $key => $item) @if(($request->pref_id) ==
					$item)
					<option value="{{$key}}" selected="selected" @endif>{{$request->pref_id}}</option>
					<option value="{{$key}}">{{$item}}</option> @endforeach @else
					<option value=""></option> @foreach($prefList as $key => $item)
					<option value="{{$key}}">{{$item}}</option> @endif
			</select></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$city_name}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><select name="city_id" id="address_city"
				style="width: 200px"> @if(isset($request->city_id))
					@foreach($cityList as $key => $item) @if(($request->city_id) ==
					$item)
					<option value="{{$key}}" selected="selected" @endif>{{$request->city_id}}</option>
					<option value="{{$key}}">{{$item}}</option> @endforeach @else
					<option value=""></option> @foreach($cityList as $key => $item)
					<option value="{{$key}}">{{$item}}</option> @endif
			</select></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$bunch_building_name}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_l" style="ime-mode: active;"
				type="text" name="address" value="{{$request->address}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$home_phone}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_m" style="ime-mode: inactive;"
				type="text" name="phone_no" value="{{$request->phone_no}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$mobile_phone}}</td>
			<td class="t4td2"><input class="text_m" style="ime-mode: inactive;"
				type="text" name="handset_no" value="{{$request->handset_no}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$memo}}</td>
			<td class="t4td2"><textarea style="ime-mode: active;" id="input3"
					name="memo" cols="30" rows="4">{{$request->memo}}</textarea></td>
		</tr>

	</table>

	<table id="table6">
		<colgroup>
			<col width="30%" />
			<col width="70%" />
		</colgroup>
		<tr>
			<td class="t6_td1"><strong>{{$payment_method}}</strong></td>
			<td><select id="invoicetype" name="invoice_type">
					<option value="0" @if( $request['invoice_type'] ==
						0)selected="selected" @endif>{{$cash}}</option>
					<option value="1" @if( $request['invoice_type'] ==
						1)selected="selected" @endif>{{$transfer}}</option>
					<option value="2" @if( $request['invoice_type'] ==
						null || $request['invoice_type'] == 2)selected="selected" @endif>{{$account_transfer}}</option>
			</select></td>
		</tr>
		<tr>
			<td class="t6_td1"><strong>{{$notification_method}}</strong></td>
			<td><select id="mailinfo" name="mail_infomation">
					<option value="0" @if( $request['mail_infomation'] ==
						0) selected@endif>{{$mailing}}</option>
					<option value="1" @if( $request['mail_infomation'] ==
						null || $request['mail_infomation'] == 1)selected@endif>{{$email}}</option>
			</select></td>
		</tr>
	</table>
	<div id="invoiceinfo" @if( $request['invoice_type'] !=null
		&& $request['invoice_type'] !=2) style="display: none" @endif>
		<h4>{{$account_information}}</h4>
		<table id="table6">
			<colgroup>
				<col width="30%" />
				<col width="70%" />
			</colgroup>
			<tr>
				<td class="t6_td1">{{$financial_organizations}}</td>
				<td><select id="banktype" name="bank_type">
						<option value="1" @if( $request['bank_type'] ==
							null || $request['bank_type'] == 1)selected@endif>{{$bank_credit_union}}</option>
						<option value="2" @if( $request['bank_type'] == 2)selected@endif>{{$post_office}}</option>
				</select></td>
			</tr>
		</table>
	</div>
	<div id="bankinfo" @if( ($request['invoice_type'] !=null
		&& $request['invoice_type'] !=2) || ($request['bank_type'] !=null
		&& $request['bank_type'] !=1)) style="display: none" @endif>
		<table id="table6">
			<colgroup>
				<col width="30%" />
				<col width="70%" />
			</colgroup>
			<tr>
				<td class="t6_td1">{{$bank_code}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: inactive;" type="text" name="bank_code"
					value="{{$request->bank_code}}" class="l_text" />
					{{$half_width_number_4_digit}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$financial_institution_name}} <span
					class="aster">*</span>
				</td>
				<td><input style="ime-mode: inactive;" type="text" name="bank_name"
					value="{{$request->bank_name}}" class="l_text" />
					{{$single_byte_uppercase_kana_up_15_character}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$branch_code}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: inactive;" type="text"
					name="branch_code" value="{{$request->branch_code}}" class="l_text" />
					{{$half_width_number_3_digit}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$branch_name}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: inactive;" type="text"
					name="branch_name" value="{{$request->branch_name}}" class="l_text" />
					{{$single_byte_uppercase_kana_up_15_character}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$classification}} <span class="aster">*</span>
				</td>
				<td><select name="bank_account_type">
						<option value=""></option> @foreach ($bank_account_type_list as
						$type_id => $type)
						<option value="{{$type_id}}" @if( $request['bank_account_type'] ==
							$type_id)
							selected@endif>{{$type}}</option> @endforeach
				</select></td>
			</tr>
			<tr>
				<td class="t6_td1">{{$account_number}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: inactive;" type="text"
					name="bank_account_number"
					value="{{$request->bank_account_number}}" class="m_text" />
					{{$half_width_number_7_digit}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$account_holder}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: active;" type="text"
					name="bank_account_name" value="{{$request->bank_account_name}}"
					class="l_text" /> {{$single_byte_uppercase_kana_up_30_character}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$account_kana_name}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: active;" type="text"
					name="bank_account_name_kana"
					value="{{$request->bank_account_name_kana}}" class="l_text" /></td>
			</tr>
		</table>
	</div>

	<div id="postinfo" @if( $request->
		invoice_type != 2 || $request->bank_type != 2) style="display:none"
		@endif>
		<table id="table6">
			<colgroup>
				<col width="30%" />
				<col width="70%" />
			</colgroup>
			<tr>
				<td class="t6_td1">{{$passbook_symbol}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: inactive;" type="text"
					name="post_account_kigou" value="{{$request->post_account_kigou}}"
					class="m_text" /> {{$half_width_number_5_digit}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$passbook_number}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: inactive;" type="text"
					name="post_account_number"
					value="{{$request->post_account_number}}" class="m_text" />
					{{$half_width_number_8_digit}}</td>
			</tr>
			<tr>
				<td class="t6_td1">{{$passbook_name}} <span class="aster">*</span>
				</td>
				<td><input style="ime-mode: active;" type="text"
					name="post_account_name" value="{{$request->post_account_name}}"
					class="l_text" /> {{$single_byte_uppercase_kana_up_30_character}}</td>
			</tr>
		</table>
	</div>


	<div id="AdjustInfo">
		<h4>{{$premium_discount}}</h4>
		<table id="table6">
			<colgroup>
				<col width="30%" />
				<col width="70%" />
			</colgroup>
			<tr>
				<td class="t6_td1">{{$premium_discount_items}}</td>
				<td>

					<div id="inputActive">
						@if( isset($request->payment) && $request->payment|@count > 0)
						@foreach ($request->payment as $k => $v)
						<div class="InputArea">
							<table>
								<tr>
									<td class="t4d2">{{$target_month}}<select
										name="payment[{{$loop.index}}][payment_month]"
										id="payment_month_{{$loop.index}}"
										class="formItem PaymentMonth">
											<option value=""></option> @foreach ($month_list as $key =>
											$row)
											<option value="{{$key}}" @if( $key==
												$v['payment_month'] )selected@endif>{{$row}}</option>
											@endforeach
									</select> &nbsp;{{$abstract}}<select
										name="payment[{{$loop.index}}][payment_adjust]"
										id="payment_adjust_{{$loop.index}}"
										class="formItem PaymentAdjust">
											<option value=""></option> @foreach ($invoice_adjust_list as
											$row)
											<option value="{{$row['id']}}" @if( $v['payment_adjust'] ==
												$row['id'])selected@endif>{{$row['name']}}</option>
											@endforeach
									</select> &nbsp;{{$price}}<input type="text"
										name="payment[{{$loop.index}}][payment_fee]"
										id="payment_fee_{{$loop.index}}" class="formItem InputFee"
										style="ime-mode: inactive; width: 80px; text-align: right;"
										value="{{$v['payment_fee']}}" />&nbsp;{{$parts_parent_entry_57}}

										<input type="hidden"
										name="payment[{{$loop.index}}][payment_id]"
										id="payment_id_{{$loop.index}}" value="{{$v['payment_id']}}" />

										<input type="hidden"
										name="payment[{{$loop.index}}][payment_del]"
										id="payment_del_{{$loop.index}}" value="{{$v['payment_del']}}" />
										<a class="inputDelete" href="#"
										id="adjustDelete_{{$loop.index}}"> <input type="button"
											value="{{$delete}}" /></a>
									</td>
								</tr>
							</table>
						</div>
						@endforeach @endif
					</div>

					<div style="margin: 10px 10px 17px 120px;">
						<button id="inputAdd" style="width: 100px">{{$add_items}}</button>
					</div>

					<div id="inputBase" style="display: none;">
						<table>
							<tr>
								<td class="t4d2">{{$target_month}}<select
									class="formItem NewPaymentMonth" title="payment_month">
										<option value=""></option> @foreach ($month_list as $key =>
										$row)
										<option value="{{$key}}">{{$row}}</option> @endforeach
								</select> &nbsp;{{$abstract}}<select
									class="formItem NewPaymentAdjust" title="payment_adjust">
										<option value=""></option> @foreach ($invoice_adjust_list as
										$row)
										<option value="{{$row['id']}}">{{$row['name']}}</option>
										@endforeach
								</select> &nbsp;{{$price}}<input type="text"
									class="formItem NewPaymentFee"
									style="ime-mode: inactive; width: 80px; text-align: right;"
									value="" title="payment_fee" />&nbsp; <input type="hidden"
									class="formItem NewPaymentId" value="" title="payment_id" /> <input
									type="hidden" class="formItem NewPaymentDel" value=""
									title="payment_del" /> <a class="inputDelete" href="#"><input
										type="button" value="{{$delete}}"
										id="AdjustDelete_{{$loop.index}}" /></a>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>