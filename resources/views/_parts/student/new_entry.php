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
			"{{$_app_path)ajaxSchool/city",
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
		@if( $request.orgparent_id)
			$("#entry_form").attr('action',	'{{$_app_path)parent/detail');
		{{else)
			$("#entry_form").attr('action',	'{{$_app_path)parent');
		@endif
		$("#entry_form").submit();
		return false;
	});
	$(".submit2").click(function() {
		$("#entry_form").attr('action',
				'{{$_app_path)parent/confirm');
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
			@if( $request.bank_type == 2)
				$("#bankinfo").hide();
				$("#postinfo").show();
			{{elseif $request.bank_type == 1)
				$("#postinfo").hide();
				$("#bankinfo").show();
			{{else)
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
			var strMail = "{{$request.parent_mailaddress2)";
			if( strMail.length < 1 ){
				$(this).val("");
			}
		}
		$bInit = false;
	});

});




$(function(){
	$( "A.inputDelete").click( function(e){
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
	return false;
}

@if ($request.payment)
var nowInputIndex =  {{ $request.payment|@count );
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
<span class="aster">&lowast;</span>
{{$lan['mandatory_items_marked']}}
<form id="entry_form" name="entry_form" method="post">
	<input type="hidden" name="function" value="{{$request->function)" /> <input
		type="hidden" name="login_account_id"
		value="{{$request->login_account_id)" /> @if(
	($request->orgparent_id)) <input type="hidden" name="orgparent_id"
		value="{{$request->orgparent_id)" /> @endif @if(
	$request->link_enable) @include('_parts.student.hidden') @endif @endif

	<table id="table6">
		<colgroup>
			<col width="30%" />
			<col width="70%" />
		</colgroup>
		<tr>
			<td class="t6_td1">{{$lan['given_name']}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_m" style="ime-mode: active;"
				type="text" name="parent_name" value="{{$request->parent_name}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan['kana_name']}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_m" style="ime-mode: active;"
				type="text" name="name_kana" value="{{$request->name_kana}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan['email_address_1']}}<span class="aster">&lowast;</span></td>
			<td class="t4td2"><input class="text_m" style="ime-mode: inactive;"
				type="text" name="parent_mailaddress1"
				value="{{$request->parent_mailaddress1}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan['email_address_2']}}</td>
			<td class="t4td2"><input class="text_m" style="ime-mode: inactive;"
				type="text" name="parent_mailaddress2"
				value="{{$request->parent_mailaddress2}}" /></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan['password']}}</td>
			<td class="t4td2"><input class="text_m" type="password"
				name="parent_pass" @if( $request['orgparent_id'])
						/> <br /> <span class="col_msg">{{$lan['only_change_input']}}</span>
				@else value="{{$request->parent_pass}}"/> @endif</td>
		</tr>
	</table>

	<div id="AdjustInfo">
		<h4>
			{{$lan['premium_discount']}}/h4>
			<table id="table6">
				<colgroup>
					<col width="30%" />
					<col width="70%" />
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan['premium_discount_items']}}</td>
					<td>

						<div id="inputActive">
							@if( $request['payment'] && $request['payment']|@count > 0)
							@foreach($request['payment'] as $k =>$v)
							<div class="InputArea">
								<table>
									<tr>
										<td class="t4d2">{{$lan['target_month']}} <select
											name="payment[
									{{$smarty.foreach.i_loop.index)][payment_month]"
											id="payment_month_{{$smarty.foreach.i_loop.index)"
											class="formItem PaymentMonth">
												<option value=""></option> @foreach ($month_list as $key =>
												$row)
												<option value="{{$key}}" @if( $key==
													$v.payment_month )selected@endif>{{$row}}</option> @foreach
										</select> &nbsp;{{$lan['abstract']}}<select
											name="payment[{{foreach.index}}][payment_adjust]"
											id="payment_adjust_{{foreach.index)"
											class="formItem PaymentAdjust">
												<option value=""></option> @foreach($invoice_adjust_list as
												$key => $row)
												<option value="{{$row['id'])" @if( $v.payment_adjust==
													$row['id'])selected@endif>{{$row['name']}}</option>
												@endforeach
										</select> &nbsp;{{$lan['price']}}<input type="text"
											name="payment[{{foreach.index)][payment_fee]"
											id="payment_fee_{{foreach.index)" class="formItem InputFee"
											style="ime-mode: inactive; width: 80px;"
											value="{{$v.payment_fee}}" />&nbsp;{{$lan['parts_parent_entry_57']}}<input
											type="hidden" name="payment[{{foreach.index}}][payment_id]"
											id="payment_id_{{$smarty.foreach.i_loop.index}}"
											value="{{$v.payment_id}}" /> <a class="inputDelete" href="#"><input
												type="button" value="{{$delete}}" /></a>
										</td>
									</tr>
								</table>
							</div>
							@foreach @endif
						</div>

						<div style="margin: 10px 10px 17px 120px;">
							<button id="inputAdd" style="width: 100px">{{$lan['add_items']}}</button>
						</div>

						<div id="inputBase" style="display: none;">
							<table>
								<tr>
									<td class="t4d2">{{$lan['target_month']}}<select
										class="formItem NewPaymentMonth" title="payment_month">
											<option value=""></option> @foreach ($month_list as $key =>
											$row)
											<option value="{{$key}}">{{$row}}</option> @endforeach
									</select> &nbsp;{{$lan['abstract']}}<select
										class="formItem NewPaymentAdjust" title="payment_adjust">
											<option value=""></option> @foreach ($invoice_adjust_list as
											$row)
											<option value="{{$row['id']}}">{{$row['name']}}</option>
											@endforeach
									</select> &nbsp;{{$lan['price']}}<input type="text"
										class="formItem NewPaymentFee"
										style="ime-mode: inactive; width: 80px;" value=""
										title="payment_fee" />&nbsp;{{$lan['circle']}} <input
										type="hidden" class="formItem NewPaymentId" value=""
										title="payment_id" /> <a class="inputDelete" href="#"><input
											type="button" value="{{$delete}}" /></a>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
	
	</div>
</form>