@extends('_parts.master_layout')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<style>
	.tbl_bank_info{
		margin-left: 10px !important;
	}
	.bank_header {
		border-top: solid 2px #bdbdbd !important;
	}
	.top_btn li:hover, input[type="button"]:hover {
		background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
		box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
		cursor: pointer;
		text-shadow: 0 0px #FFF;
	}
	.top_btn li {
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
	input[type="button"] {
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		text-shadow: 0 0px #FFF;
	}
</style>
<form id="search_form" method="post">
	<div class="section">
		<div id="center_content_header" class="box_border1">
			<h2 class="float_left"><i class="fa fa-university"></i> {{$lan::get('main_title')}}</h2>
			<div class="center_content_header_right">
				<div class="top_btn">
					<ul>
						@if($edit_auth)
                            {{--<a href="/school/school/input"><li style="color: #595959; font-weight: normal;"> <i class="glyphicon glyphicon-pencil"></i>&nbsp; {{$lan::get('edit_basic_info_title')}}</li></a>--}}

                            <a href="/school/school/inputindiv"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-share"></i>&nbsp; {{$lan::get('edit_individual_info_title')}}</li></a>

                            <a href="/school/school/accountlist"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-share"></i>&nbsp; {{$lan::get('login_privileges_setting_title')}}</li></a>

						@endif
					</ul>
				</div>
			</div>
			<div class="clr"></div>
		</div>

		{{--@include('_parts.topic_list')--}}
			<h3 id="content_h3" class="box_border1">{{$lan::get('detail_info_title')}}</h3>

		<div action="#" method="post" id="action_form">
		
		<div id="section_content_in">
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tbody>
				<tr>
					<td class="t6_td1">{{$lan::get('school_name_title')}}</td>
					<td>{{array_get($pschool, 'name')}}</td>
				</tr>

				<tr>
					<td class="t6_td1">{{$lan::get('school_code_title')}}</td>
					<td>
						{{array_get($pschool, 'pschool_code')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('representative_name_title')}}</td>
					<td>
						{{array_get($pschool, 'daihyou')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('official_position_title')}}</td>
					<td>
						{{array_get($pschool, 'official_position')}}
					</td>
				</tr>
				<tr>
					<td>{{$lan::get('prefix_code_title')}}</td>
					<td>{{request('prefix_code')}}</td>
				</tr>
			</tbody>
			</table>

		 	<h4>{{$lan::get('login_info_title')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">{{$lan::get('account_id_title')}}
					</td>
					<td>
						{{array_get($data, '_login_id')}}
						<input type="hidden" name="_login_id" value="{{array_get($data, '_login_id')}}" />
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('password_title')}}
					</td>
					<td>
						&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;
					</td>
				</tr>				
			</table>

			<h4>{{$lan::get('detail_info_title')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">
						{{$lan::get('language_title')}}
					</td>
					<td>
					@php
					$language = array_get($pschool, 'language');
					@endphp
						{{array_get($languages_input, $language)}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('country_title')}}
					</td>
					<td>
					@php
						$country_code = array_get($pschool, 'country_code');
					@endphp
						{{$country_list[$country_code]}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('postal_code_title')}}</td>
					<td>
						@if(isset($data['address_post1']))
							&#12306;{{array_get($data, 'address_post1')}}－{{array_get($data, 'address_post2')}}
						@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('city_title')}}
					</td>
					<td>
					@if(isset($pref_list))
						@foreach ($pref_list as $pref)
						@if(array_get($pschool, 'pref_id') == $pref['id'])
							{{$pref['name']}}
						@endif
						@endforeach
					@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('district_title')}}
					</td>
					<td>
					@if(isset($city_list))
						@foreach ($city_list as $city)
						@if(array_get($pschool, 'city_id') == $city['id'])
						{{$city['name']}}
						@endif
						@endforeach
					@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('address2_title')}}
					</td>
					<td>
						{{array_get($pschool, 'address')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('building_title')}}
					</td>
					<td>
						{{array_get($pschool, 'building')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('phone_number_title')}}
					</td>
					<td>
						{{array_get($pschool, 'tel')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('fax_title')}}
					</td>
					<td>
						{{array_get($pschool, 'fax')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('email_address_title')}}
					</td>
					<td>
						<table>
							<tr>
								<td style="padding-left: 0px;">
									{{array_get($pschool, 'mailaddress')}}
								</td>
								<td style="padding-left: 100px;">
									<b>
										{{$lan::get('email_content_title')}}
									</b>
								</td>
							</tr>
						</table>
					</td>

				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('home_page_title')}}
					</td>
					<td>
						{{array_get($pschool, 'web')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('amount_display_title')}}
					</td>
					<td>
					@if(isset($amt_disp_type_list))
						@foreach ($amt_disp_type_list as $amt_disp_type_id => $amt_disp_type)
						@if(array_get($pschool, 'amount_display_type') == $amt_disp_type_id)
						{{$amt_disp_type}}
						@endif
						@endforeach
					@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('tax_rate_title')}}
					</td>
					<td>
						{{array_get($pschool, 'sales_tax_rate')}}
					</td>
				</tr>
				<tr>
					<th colspan="3" class="btn_area_td">
					</th>
				</tr>

				<tr>
					<td class="t6_td1">{{$lan::get('tuition_payment_form_title')}}</td>
					@php
						$payment_style = array_get($pschool, 'payment_style');
					@endphp
					<td>{{$payment_style_list[$payment_style]}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('currency_title')}}</td>
					@php
					$currency = array_get($pschool, 'currency');
					@endphp
					<td>{{$currencies[$currency]}}</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('currency_decimal_point')}}
					</td>
					<td>
						{{array_get($pschool, 'currency_decimal_point')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('request_deadline_day_title')}}
					</td>
					<td>
					@if(isset($close_date_list))
						{{$lan::get('every_month_title')}}&nbsp;
						@foreach ($close_date_list as $close_date_id => $close_date)
						@if (array_get($pschool, 'invoice_closing_date') == $close_date_id)
						{{$close_date}}
						@endif
						@endforeach
						{{$lan::get('day_title')}}
					@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('request_payment_day_title')}}
					</td>
					<td>
					@if(isset($invoice_date_list))
							@if(array_get($request, 'payment_month') == 0) {{$lan::get('payment_this_month')}} @endif
							@if(array_get($request, 'payment_month') == 1) {{$lan::get('payment_next_month')}} @endif
							@if(array_get($request, 'payment_month') == 2) {{$lan::get('payment_second_following_month')}} @endif
                            @foreach ($invoice_date_list as $invoice_date_id => $invoice_date)
                                @if (array_get($pschool, 'payment_date') == $invoice_date_id)
                                {{$invoice_date}}
                                @endif
                            @endforeach{{$lan::get('day_title')}}
					@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('invoice_batch_title')}}
					</td>
					<td>
						{{$lan::get('every_month_title')}}&nbsp;
							@if(isset($invoice_date_list))
								@foreach ($invoice_date_list as $invoice_date_id => $invoice_date)
									@if( array_get($request, 'invoice_batch_date') == $invoice_date_id) {{$invoice_date}} @endif
								@endforeach
							@endif
						{{$lan::get('day_title')}}
					</td>
					<td></td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('price_setting_title_new')}}
					</td>
					<td>
						@if(array_get($request,'price_setting_type')==1){{$lan::get('price_setting_type_1')}}@endif
						@if(array_get($request,'price_setting_type')==2){{$lan::get('price_setting_type_2')}}@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('company_kakuin_path')}}</td>
					<td><div><img style="max-width: 64px;max-height: 64px;"
								  src="{{array_get($pschool,'kakuin_path')}}" class="imagePreview" /></div>
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('proviso')}}</td>
					<td>{{array_get($pschool, 'proviso')}}</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('require_password_when_process_deposit')}}</td>
					<td>
						@if(array_get($request, 'nyukin_pass_required')!= 1) {{$lan::get('not_do_title')}} @endif
						@if(array_get($request, 'nyukin_pass_required')== 1) {{$lan::get('do_title')}} @endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('deposit_default_search_invoice_year_month')}}</td>
					<td>
						@if(array_get($request, 'nyukin_before_month'))
						{{array_get($request, 'nyukin_before_month')}}{{$lan::get('before_month_title')}}
						@endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('show_number_corporation_title')}}</td>
					<td>
						@if(array_get($request, 'show_number_corporation')== 1) {{$lan::get('do_title')}} @endif
						@if(array_get($request, 'show_number_corporation')!= 1) {{$lan::get('not_do_title')}} @endif
					</td>
				</tr>
				<tr>
					<td class="t6_td1">{{$lan::get('check_zip_csv_title')}}</td>
					<td>
						@if(array_get($request, 'is_zip_csv')== 1) {{$lan::get('do_title')}} @endif
						@if(array_get($request, 'is_zip_csv')!= 1) {{$lan::get('not_do_title')}} @endif
					</td>
				</tr>
			</table>
			<h4>{{$lan::get('account_information')}}</h4>
				<br/>
					{{--@foreach( $bank as $item)
						<div style="margin-left:10px;">
							<div style="width:30%;float:left"/><b>{{$lan::get('financial_organizations')}}</b></div>
							<div style="width:70%"/><b>@if (array_get($item, 'bank_type') == 1) {{$lan::get('bank_title')}} @else {{$lan::get('post_title')}} @endif</b></div>
						</div>
						<table id="table6" class="tab_bank_info">
							<colgroup>
								<col width="30%"/>
								<col width="70%"/>
							</colgroup>
							<tr>
								<td class="t6_td1">{{$lan::get('bank_name_title')}}</td>
								<td class="t6_td1">@if (array_get($item, 'bank_type') == 1) {{array_get($item,'bank_name')}} @else {{array_get($item,'post_account_name')}} @endif</td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('preview_branch_name_title')}}</td>
								<td class="t6_td1">@if (array_get($item, 'bank_type') == 1) {{array_get($item,'branch_name')}} @else {{array_get($item,'post_account_number')}} @endif</td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('account_number')}}</td>
								<td class="t6_td1">@if (array_get($item, 'bank_type') == 1) {{array_get($item,'bank_account_number')}} @else {{array_get($item,'post_account_kigou')}} @endif</td>
							</tr>
						</table>
						<br/>
					@endforeach--}}

				@foreach( $bank as $item)
				<table id="table6" class="tbl_bank_info">
					<colgroup>
						<col width="30%"/>
						<col width="70%"/>
					</colgroup>
                    @if(array_get($item, 'bank_type') == 1)
                        <tr>
                            <tr class="bank_header">
                                <td class="t6_td1">{{$lan::get('financial_organizations')}}</td>
                                <td class="t6_td1">{{$lan::get('new_bank_title')}}</td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('bank_name_title')}}</td>
                                <td class="t6_td1">{{array_get($item,'bank_name')}}</td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('detail_bank_type_title')}}</td>
                                <td class="t6_td1">{{array_get($item,'branch_name')}}</td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('account_number')}}</td>
                                <td class="t6_td1">{{array_get($item,'bank_account_number')}}</td>
                            </tr>
                        </tr>
                    @else
                        <tr>
                        <tr class="bank_header">
                            <td class="t6_td1">{{$lan::get('financial_organizations')}}</td>
                            <td class="t6_td1">{{$lan::get('post_title')}}</td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('symbol_title')}}</td>
                            <td class="t6_td1">{{array_get($item,'post_account_kigou')}}</td>
                        </tr>
                        <tr>
                            <td class="t6_td1">@if (array_get($item, 'post_account_type') == 1) {{$lan::get('detail_bank_type_title')}} @else {{$lan::get('preview_branch_name_title')}} @endif</td>
                            <td class="t6_td1">@if (array_get($item, 'post_account_type') == 1)  @else {{substr(array_get($item,'post_account_number'),0,1)}} @endif</td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('passbook_number_title')}}</td>
                            <td class="t6_td1">@if (array_get($item, 'post_account_type') == 1) {{array_get($item,'post_account_number')}} @else {{substr(array_get($item,'post_account_number'),1,7)}} @endif</td>
                        </tr>
                        </tr>
                    @endif
				</table>
				@endforeach

			<h4>{{$lan::get('payment_method_title')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				@foreach($payment_list as $payment)
					<tr>
						<td class="t6_td1">{{$lan::get(array_get($payment, 'payment_method_name'))}}</td>
                        <td>{{array_get($payment, 'agency_name')}}</td>
					</tr>
				@endforeach
				
			</table>
			{{--<div @if (array_get($request, '_invoice_type') != 2) style="display:none" @endif>
			<h4>{{$lan::get('account_information')}}</h4>
			<div @if (array_get($request, '_bank_type') != null && array_get($request, '_bank_type') != 1) style="display:none" @endif>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">
						{{$lan::get('bank_code')}}
					</td>
					<td>
						{{array_get($request, '_bank_code')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('financial_institution_name')}}
					</td>
					<td>
						{{array_get($request, '_bank_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('branch_code')}}
					</td>
					<td>
						{{array_get($request, '_branch_code')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('branch_name')}}
					</td>
					<td>
						{{array_get($request, '_branch_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('classification')}}
					</td>
					<td>
						{{array_get($request, '_bank_account_type')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('account_number')}}
					</td>
					<td>
						{{array_get($request, '_bank_account_number')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('account_holder')}}
					</td>
					<td>
						{{array_get($request, '_bank_account_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('account_kana_name')}}
					</td>
					<td>
						{{array_get($request, '_bank_account_name_kana')}}
					</td>
				</tr>
			</table>
			</div>--}}
			
			{{--<div @if(array_get($request, '_bank_type') != 2) style="display:none" @endif>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">
						{{$lan::get('passbook_code_title')}}
					</td>
					<td>
						{{array_get($request, '_post_account_kigou')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('passbook_number_title')}}
					</td>
					<td>
						{{array_get($request, '_post_account_number')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('passbook_name_title')}}
					</td>
					<td>
						{{array_get($request, '_post_account_name')}}
					</td>
				</tr>
			</table>
			</div>
			
			<h4>{{$lan::get('invoice_info_title')}}</h4>
			<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">
						{{$lan::get('consignor_code_title')}}
					</td>
					<td>
						{{array_get($request, 'consignor_code')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('consignor_name_title')}}
					</td>
					<td>
						{{array_get($request, 'consignor_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						収納代行会社
					</td>
					<td>
						{{array_get($request, 'payment_agency_name')}}
					</td>
				</tr>
				<tr>
					<td class="t6_td1">
						{{$lan::get('debit_day_title')}}
					</td>
					<td>
						{{$lan::get('every_month_title')}}&nbsp;
						{{array_get($data, 'withdrawal_day')}}
						&nbsp;{{$lan::get('day_title')}}
					</td>
				</tr>
			</table>
			</div>--}}
			{{--<table id="table6">
				<colgroup>
					<col width="30%"/>
					<col width="70%"/>
				</colgroup>
				<tr>
					<td class="t6_td1">
						その他収納代行会社
					</td>
					<td>
						{{array_get($request, 'other_payment_agency_name')}}
					</td>
				</tr>
			</table>--}}
		</div>
			<div class="top_btn">
				<ul>
					@if($edit_auth)
						<a href="/school/school/input"><li style="color: #595959; font-weight: normal;margin-top: 5px;"> <i class="glyphicon glyphicon-edit " style="width: 20%;font-size:16px;"></i>&nbsp; {{$lan::get('edit_basic_info_title')}}</li></a>
					@endif
				</ul>
			</div>
		</form>
	</div>
</form>

	<!-- #main -->

	@stop