@extends('_parts.master_layout')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
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
	$(".submit3").click(function() {
		$("#action_form").attr('action',
				'{{$_app_path}}parent/list');
		$("#action_form").submit();
		return false;
	});
	$("#btn_return").click(function() {
		$("#action_form").attr(
				'action',
				'{{$_app_path}}parent/list');
		$("#action_form").submit();
		return false;
	});
	$("#btn_submit").click(function() {
		$("#action_form").attr('action',
				'{{$_app_path}}parent/complete');
		$("#action_form").submit();
		return false;
	});
	$("#submit2").click(function() {
		java_post("/school/parent/edit?orgparent_id={{$row['id']}}");
		return false;
	});
    $("#submit_return").click(function () {
        $("#frm_return").submit();
    });
	
// 	$("#submit2").click(function() {
// 		$("#action_form").attr('action', '/school/parent/edit?id={{$row['id']}}');
// 		$("#action_form").submit();
// 		return false;
// 	});
	
	$( "#dialog-confirm" ).dialog({
		title: "{{$lan::get('main_title')}}",
		autoOpen: false,
		dialogClass: "no-close",
		resizable: false,
		modal: true,
		buttons: {
			"削除": function() {
				$( this ).dialog( "close" );
				$("#action_form input[name='function']").val("3");
				$("#action_form").attr('action', '{{$_app_path}}parent/complete');
				$("#action_form").submit();
				return false;
			},
			"キャンセル": function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$("a[href='#delete']").click(function() {
		$( "#dialog-confirm" ).dialog('open');
		return false;
	});

    $("#btn_before").click(function() {
        window.location.href = "{{$_app_path}}parent/detail/?pre";
        return false;
    });
    $("#btn_after").click(function() {
        window.location.href = "{{$_app_path}}parent/detail/?next";
        return false;
    });
});
</script>
<style>
	.submit_return {
		height: 30px;
		border-radius: 5px;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		font-size: 14px;
		font-weight: normal;
		text-shadow: 0 0px #FFF;
	}
</style>

<form id="action_form" name="action_form" method="post">
	{{ csrf_field() }} <input type="hidden" name="function" value="" />
	@include('_parts.student.hidden')


	<div id="center_content_header" class="box_border1">

			<h2 class="float_left"><i class="fa fa-user-secret"></i> {{$lan::get('main_title')}}</h2>
			{{--<div class="center_content_header_right">
				<ul>
					--}}{{-- @if ((array_get($auths, 'parent_entry') == 1)  && ($request->from_student_top || $request->from_parent_top)) --}}{{--
						<a class="edit_btn" href="#edit" id="submit2" type="button"><li>{{$lan::get('edit')}}</li></a>
					--}}{{-- @endif --}}{{--
				</ul>
			</div>--}}
			<div class="clr"></div>
		</div><!--center_content_header-->
	<!--center_content_header-->
	{{-- <div id="topic_list"
		style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
		
		{!! Breadcrumbs::render('parent_detail') !!}</div>	--}}
		
		{{--@include('_parts.topic_list')--}}
		
		<h3 id="content_h3" class="box_border1">{{$lan::get('information_detail')}}</h3>
		<div id="section_content1">
		<ul class="message_area">@if (session()->get('error_when_remove_parent_have_student'))
			<li class="error_message" role="alert" style="color: red;">
			{{session()->pull('error_when_remove_parent_have_student')}}</li> @endif 
		</ul>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
					<tr>
						<td class="t6_td1">{{$lan::get('given_name')}}<span class="required">*</span></td>
						<td>{{array_get($row, 'parent_name')}}
						<input type="hidden" name="orgparent_id" value="{{array_get($row, 'id')}}"/></td>
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('kana_name')}}{{--<span class="required">*</span>--}}</td>
						<td>{{array_get($row, 'name_kana')}}</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('email_address_1')}}<span class="required">*</span></td>
						<td>{{array_get($row, 'parent_mailaddress1')}}</td>
					</tr>
					{{--<tr>--}}
						{{--<td class="t6_td1">{{$lan::get('email_address_2')}}</td>--}}
						{{--<td>{{array_get($row, 'parent_mailaddress2')}}</td>--}}
					{{--</tr>--}}
					<tr>
						<td class="t6_td1">{{$lan::get('password')}}<span class="required">*</span></td>
						<td>
							&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;
						</td>
					</tr>
				</table>

				<h4>{{$lan::get('street_address')}}</h4>
				<table id="table6">
					<colgroup>
						<col width="30%"/>
						<col width="70%"/>
					</colgroup>
					<tr>
						<td class="t6_td1">{{$lan::get('postal_code')}}</td>
						<td>{{array_get($row, 'zip_code1')}}－{{array_get($row, 'zip_code2')}}</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('name_of_prefectures')}}<span class="required">*</span></td>
						<td>
							{{array_get($pref_name, 'name')}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('city_name_title')}}<span class="required">*</span></td>
						<td>
							{{array_get($city_name, 'name')}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('address_title')}}<span class="required">*</span></td>
						<td>{{array_get($row, 'address')}}</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('building_title')}}</td>
						<td>{{array_get($row, 'building')}}</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('home_phone')}}<span class="required">*</span></td>
						<td>{{array_get($row, 'phone_no')}}</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('mobile_phone')}}</td>
						<td>{{array_get($row, 'handset_no')}}</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('memo')}}</td>
						<td>{{array_get($row, 'memo')}}</td>
					</tr>
				</table>

				<table id="table6">
					<colgroup>
						<col width="30%"/>
						<col width="70%"/>
					</colgroup>
					<tr>
						<td class="t6_td1">
							<strong>{{$lan::get('payment_method')}}</strong>
						</td>
						<td>
							@foreach($payment_method_list as $k => $v)
								@if(array_get($row,'invoice_type')==array_get($v,'payment_method_value'))
									{{$lan::get(array_get($v,'payment_method_name'))}}
								@endif
							@endforeach
						</td>
					</tr>
					<tr>
						<td class="t6_td1">
							<strong>{{$lan::get('notification_method')}}</strong>
						</td>
						<td>
						@if (array_get($row, 'mail_infomation') == 0)
							{{$lan::get('mailing')}}
						@elseif (array_get($row, 'mail_infomation') == 1)
							{{$lan::get('email')}}
						@else
							{{$lan::get('other')}}
						@endif
						</td>
					</tr>
					@foreach($additionalCategories as $category)
						<tr>
							<td class="t6_td1">
								{{array_get($category, 'name')}}
							</td>
							<td class="t4td2">
								{{array_get($request, 'additional.' . array_get($category, 'code'), array_get($category, 'value'))}}
							</td>
						</tr>
					@endforeach
				</table>
		@if (array_get($row, 'invoice_type') == 2 && array_get($row, 'bank_type') == 1)
			<h4>{{$lan::get('account_information_bank_credit_union')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan::get('bank_code')}}
					</td>
					<td>
						{{array_get($row, 'bank_code')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('financial_institution_name')}}
					</td>
					<td>
						{{array_get($row, 'bank_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('branch_code')}}
					</td>
					<td>
						{{array_get($row, 'branch_code')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('branch_name')}}
					</td>
					<td>
						{{array_get($row, 'branch_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('classification')}}
					</td>
					<td>
					@if($bank_account_type_list)
						@foreach ($bank_account_type_list as $type_id => $type)
						@if (array_get($row, 'bank_account_type') == $type_id)
						{{$type}}
						@endif
						@endforeach
					@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('account_number')}}
					</td>
					<td>
						{{array_get($row, 'bank_account_number')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('account_holder')}}
					</td>
					<td>
						{{array_get($row, 'bank_account_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('account_name_halfsize')}}
					</td>
					<td>
						{{array_get($row, 'bank_account_name_kana')}}
					</td>
				</tr>
			</table>
		@endif

		@if (array_get($row, 'invoice_type') == 2 && array_get($row, 'bank_type') == 2)
			<h4>{{$lan::get('account_information_post_bank')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">
						{{$lan::get('type_title')}}
					</td>
					<td>
						@if (array_get($row, 'post_account_type') == 1)
							総合口座、通常貯金、通常貯蓄貯金
						@else
							振替口座
						@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('passbook_symbol')}}
					</td>
					<td>
						{{array_get($row, 'post_account_kigou')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('passbook_number')}}
					</td>
					<td>
						{{array_get($row, 'post_account_number')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('passbook_name')}}
					</td>
					<td>
						{{array_get($row, 'post_account_name')}}
					</td>
				</tr>
			</table>
		@endif

			<h4>{{$lan::get('premium_discount')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan::get('premium_discount_items')}}</td>
					<td>
						@if (isset($routine_payments))
							@foreach($routine_payments as $key => $item)
								<div class="InputArea" >
									<table style="width:750px;">
										<tr>
											<td width="120">
												@if (array_get($item,'month') == 99)
													{{$lan::get('target_month_monthly')}}
												@else
												{{$lan::get('target_month')}}{{array_get($item,'month')}}{{$lan::get('month')}}
												@endif
											</td>
											<td  width="120">
											 	{{$lan::get('abstract')}}&nbsp;
											 	{{array_get($item,'name')}}
											</td>
											<td  width="120">
											 	{{$lan::get('price')}}&nbsp;{{number_format(array_get($item,'adjust_fee'))}}&nbsp;{{$lan::get('circle')}}
											</td>
										</tr>
									</table>
								</div>
							@endforeach
						@endif
					</td>
				</tr>
			</table>
			<h4>{{$lan::get('parent_addressee_title')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
					<tr>
						<td class="t6_td1">@if(array_get($row, 'other_name_flag') == 0){{$lan::get('parent_name_title')}}@else{{$lan::get('other_title')}}@endif</td>
						<td class="t6_td1">@if(array_get($row, 'other_name_flag') == 0){{array_get($row, 'parent_name')}}@else{{array_get($row, 'parent_name_other')}}@endif</td>
					</tr>

			</table>
			<h4>{{$lan::get('parent_address_title')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
					<tr>
						<td class="t6_td1">@if(array_get($row, 'other_address_flag') == 0){{$lan::get('parent_registered_address_title')}}@else{{$lan::get('other_title')}}@endif</td>
						<td class="t6_td1"></td>
					</tr>
					<tr>
						<td class="t6_td1 td_inner"> {{$lan::get('postal_code')}}</td>
						<td class="t4td2">@if(array_get($row, 'other_address_flag') == 0){{array_get($row, 'zip_code1')}}－{{array_get($row, 'zip_code2')}}@else{{array_get($row, 'zip_code1_other')}}－{{array_get($row, 'zip_code2_other')}}@endif</td>
					</tr>
					<tr>
						<td class="t6_td1 td_inner"> {{$lan::get('prefecture_name')}}</td>
						<td class="t4td2">@if(array_get($row, 'other_address_flag') == 0){{array_get($pref_name, 'name')}}@else{{array_get($pref_other, 'name')}}@endif</td>
					</tr>
					<tr>
						<td class="t6_td1 td_inner"> {{$lan::get('city_name')}}</td>
						<td class="t4td2">@if(array_get($row, 'other_address_flag') == 0){{array_get($city_name, 'name')}}@else{{array_get($city_other, 'name')}}@endif</td>
					</tr>
					<tr>
						<td class="t6_td1 td_inner"> {{$lan::get('address_title')}}
						<td class="t4td2">@if(array_get($row, 'other_address_flag') == 0){{array_get($row, 'address')}}@else{{array_get($row, 'address_other')}}@endif</td>
					</tr>
					<tr>
						<td class="t6_td1 td_inner"> {{$lan::get('building_title')}}</td>
						<td class="t4td2">@if(array_get($row, 'other_address_flag') == 0){{array_get($row, 'building')}}@else{{array_get($row, 'building_other')}}@endif</td>
					</tr>
			</table>
		 {{-- <input class="submit3" type="submit" id="btn_return" value="戻る" /> --}}
		<!-- <input class="submit_return" id="submit_return" type="button"
			   value="{{$lan::get('return')}}"/> &nbsp; -->
			@if($edit_auth)
			<button id="submit2" class="submit_return" type="submit" style="font-weight: normal;font-size:14px;"><i class="glyphicon glyphicon-edit " style="width: 20%;font-size:16px;"></i>{{$lan::get('edit')}}</button> &nbsp;
			@endif
		<!-- <input class="submit_return" id="submit2" type="button"
			   value="{{$lan::get('edit')}}"/> &nbsp; -->
		<button id="submit_return" class="submit_return" type="button" style="font-weight: normal;font-size:14px;"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return')}}</button> &nbsp;
		<br><br>
		@if($position)
			@if($position['current'] > $position['first'])
					<input type="submit" class="btn_green" id="btn_before" value="{{$lan::get('previous_text')}}">
			@endif
			@if($position['current']< $position['last'])
					<input type="submit" class="btn_green" id="btn_after" value="{{$lan::get('next_text')}}">
			@endif
		@endif
	</div>
</form>

<div id="dialog-confirm"  style="display: none;">
	{{$lan::get('delete_is_ok')}}
</div>
<form id="frm_return" action="/school/parent/" method="GET" style="display:none">
</form>

@stop
