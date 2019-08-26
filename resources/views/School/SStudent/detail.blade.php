@extends('_parts.master_layout') @section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<style>
	.history_block {
		overflow-y: scroll;
		height: 125px;
	}
    .invoice_situation{
        width: 100%;
        margin-bottom: 10px;
    }
    .invoice_situation tr td{
        padding: 5px;
    }
    #table_situation {
        width: 100%;
    }

    #table_situation td{
        padding: 7px 5px;
        padding-left: 10px;
        vertical-align: middle;
        line-height: 25px;
    }

    #table_situation tr td:first-child{
        padding-left: 25px;
    }
    .tr_0{
        width: 100%;
    }
    .tr_1 td{
        background: rgba(99, 115, 140, 0.09);
    }
	.submit_return {
		height: 30px;
		border-radius: 5px;
		font-weight: normal;
		background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
	}
</style>
<form id="action_form" name="action_form" method="post">
	{{ csrf_field() }}
	<input type="hidden" name="function" value="" />
	<div id="center_content_header" class="box_border1">
		<h2 class="float_left"><i class="fa fa-group"></i> {{$lan::get('main_title')}}</h2>
		<div class="clr"></div>
	</div><!--center_content_header-->

	<div id="section_content">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width: 60%">
					<div style="padding: 10px;" class="box_border1">
						@if (isset($action_status))
						<ul class="message_area" style="padding-bottom: 10px;">
							<li class="@if($action_status == "OK"){{$lan::get('info_message')}}@else{{$lan::get('error_message')}}@endif">
								{{$lan::get('action_message')}}
							</li>
						</ul>
						@endif
						{{--会員情報--}}
						<table id="data_table" border="0" cellspacing="0" cellpadding="0" width="100%" style="padding-bottom:10px;">
							<colgroup>
								<col width="20%"/>
								<col width="20%"/>
								<col width="25%"/>
								<col width="25%"/>
								<col width="10%"/>
							</colgroup>
							<tr style="text-align:center;">
								<th class="dt_th1">{{$lan::get('status_title')}}</th>
								{{--ステータス--}}
								<td style="font-size:small;">
									@if((array_get($student, 'active_flag') && !array_get($student, 'resign_date'))
									|| (array_get($student, 'active_flag') && array_get($student, 'resign_date') > date('Y-m-d')))
										{{$lan::get('in_teaching_title')}}
                                    @elseif( !array_get($student, 'active_flag') )
										{{$lan::get('withdraw_title')}}
									@endif
								</td>
								{{--入会日--}}
								<th class="ut_th1" >{{$lan::get('join_date_title')}}</th>
								<td class="ut_td1" style="font-size:small;">
									{{array_get($student, 'enter_date')}}
								</td>
								<th class="ut_th1" >
									@if($student['student_category']==1)<b>{{$lan::get("member_personal")}}</b> @endif
									@if($student['student_category']==2)<b>{{$lan::get("member_corp")}}</b> @endif
								</th>
							</tr>
						</table>

						<table border="0" cellspacing="0" cellpadding="0" width="100%" style="padding-bottom:10px;">
							<colgroup>
								<col width="15%"/>
								<col width="20%"/>
								<col width="15%"/>
								<col width="20%"/>
								<col width="20%"/>
								<col width="5%"/>
							</colgroup>
							<tr>
								{{--画像--}}
								<td bgcolor="#f0f0f0" rowspan="13" style="text-align: center; vertical-align: middle;">
									@if(array_get($student, 'student_img') && \Illuminate\Support\Facades\Storage::disk('uploads')->exists(array_get($student, 'student_img')))
									<img src="/storage/uploads/{{array_get($student, 'student_img')}}" height="70" width="100"  alt="student image" class="imgView"/>
									@else
									<img src="/img/school/_nouser.png" alt="no image" class="imgView"/><br/>
									@endif
								</td>
							</tr>
							{{--空白行--}}
							<tr>
								<td colspan="5" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--会員番号--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('student_number_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_no')}}
								</td>
							</tr>
							{{--空白行--}}
							<tr>
								<td colspan="5" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--種別--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('category_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_type_name')}}
								</td>
							</tr>
							<tr>
								<td colspan="5" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--カナ名--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('kana_name_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_name_kana')}}
								</td>
							</tr>
							<tr>
								<td colspan="5" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--氏名--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('full_name_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_name')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--ローマ字--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('latin_alphabet_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_romaji')}}
								</td>
							</tr>
							<tr @if($student['student_category']==2) style="display: none;" @endif>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--性別--}}
							<tr @if($student['student_category']==2) style="display: none;" @endif>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('gender_title')}}</th>
								<td class="ut_td1" bgcolor="#eeeeee" style="padding:3px;">
									@if (array_get($student, 'sex') == 1)
										{{$lan::get('male_title')}}
									@elseif (array_get($student, 'sex') == 2)
										{{$lan::get('female_title')}}
									@else
										{{$lan::get('other_title')}}
									@endif
								</td>
								{{--生年月日--}}
								<th class="ut_th1" style="padding:3px 10px; text-align:center;">{{$lan::get('birthday_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="2">
									{{array_get($student, 'birthday')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
						</table>

						<table border="0" cellspacing="0" cellpadding="0" width="100%" style="padding-bottom:10px;">
							<colgroup>
								<col width="20%"/>
								<col width="15%"/>
							</colgroup>
							{{--郵便番号--}}
							<tr style="padding:3px;">
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('postal_code_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									@if(array_get($student, 'student_zip_code1')) {{array_get($student, 'student_zip_code1')}}&nbsp;-&nbsp;{{array_get($student, 'student_zip_code2')}} @endif
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--住所--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('address_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_pref_name')}}&nbsp;{{array_get($student, 'student_city_name')}}&nbsp;{{array_get($student, 'student_address')}}&nbsp;{{array_get($student, 'student_building')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--TEL--}}
							<tr>
								<th class="ut_th1"  style="padding:3px 10px;">{{$lan::get('tel')}}</th>
								<td class="ut_td1"  style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_phone_no')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--携帯--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('mobile_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'student_handset_no')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--メールアドレス--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px; font-size:small;">{{$lan::get('email_address_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'mailaddress')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>

							{{--メモ・備考--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px;">{{$lan::get('note_remarks_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'memo1')}}
								</td>
							</tr>
							<tr>
								<th class="ut_th1" style="padding:3px 10px;"></th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'memo2')}}
								</td>
							</tr>
							<tr>
								<th class="ut_th1" style="padding:3px 10px;"></th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									{{array_get($student, 'memo3')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--有効期限--}}
							<tr>
								<th class="ut_th1" style="padding:3px 10px; font-size:small;">{{$lan::get('valid_date_title')}}</th>
                                @if(date('Y-m-d')>array_get($student, 'valid_date'))
                                    <td class="ut_td1" style="padding:3px; color: red; font-weight: 700;" bgcolor="#eeeeee" colspan="4">
                                        {{array_get($student, 'valid_date')}}
                                    </td>
                                @else
                                    <td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
                                        {{array_get($student, 'valid_date')}}
                                    </td>
                                @endif
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>

                            {{--extra field--}}
                            @foreach($additionalCategories as $category)
                                {{--@if(array_get($request, 'additional.' . array_get($category, 'code'), array_get($category, 'value')))--}}
                                    <tr>
                                        <th class="ut_th1" style="padding:3px 10px; font-size:small;">{{array_get($category, 'name')}}</th>
                                        <td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
                                               {{array_get($request, 'additional.' . array_get($category, 'code'), array_get($category, 'value'))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-size:xx-small;" height="4"></td>
                                    </tr>
                                {{--@endif--}}
                            @endforeach

                            {{--Representative accordion--}}
                            @if($student['student_category']==2)
                                <tr>
                                    <th class="ut_th1" style="padding:3px 10px; font-size:small;">{{$lan::get('corporation_info_title')}}</th>
                                    <td colspan="4"><div style="padding:3px; background-color: #eeeeee" class="drop_down" data-toggle="collapse" href="#collapse_representative">{{$lan::get('representative_info_title')}}<i style="width:5%;float:right;" class="fa fa-chevron-down"></i></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="font-size:xx-small;" height="4"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="4">
                                        <div id="collapse_representative" class="panel-collapse collapse">
                                            <table class="dialog-table" id="table6">
                                                <colgroup>
													<col width="22%"/>
													<col width="48%"/>
													<col width="30%"/>
                                                </colgroup>
                                                <tbody>
                                                <tr>
                                                    <td>{{$lan::get('representative_name_title')}}</td>
                                                    <td>{{array_get($student, 'representative_name')}}</td>
													<td></td>
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('furigana_title')}}</td>
                                                    <td>{{array_get($student, 'representative_name_kana')}}</td>
													<td></td>
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('position_title')}}</td>
                                                    <td>{{array_get($student, 'representative_position')}}</td>
													<td></td>
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('representative_mail_title')}}</td>
                                                    <td>{{array_get($student, 'representative_email')}}</td>
													@if ($student['representative_send_mail_flag'] == 1)
														<td>&#10004; メール受信する</td>
													@else
														<td>&#10006; メール受信しない</td>
													@endif
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('representative_tel_title')}}</td>
                                                    <td>{{array_get($student, 'representative_tel')}}</td>
													<td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>

                                @if ( $request->has('person_in_charge') && ( count(array_get($request, 'person_in_charge')) > 0 ))
                                    @foreach( array_get($request, 'person_in_charge') as $idx => $person )
                                        <tr>
                                            <td></td>
                                            <td colspan="4">
                                                <div style="padding:3px; background-color: #eeeeee" class="drop_down" data-toggle="collapse" href="#collapse_person_{{$loop->index+1}}" style="width: 100%">{{$lan::get('person_in_charge_info_title')}}{{$loop->index+1}}<i class="fa fa-chevron-down" style="float: right; width: 5%"></i></div>
                                                <div id="collapse_person_{{$loop->index+1}}" class="panel-collapse collapse">
                                                    <table class="dialog-table" id="table6">
                                                        <colgroup>
                                                            <col width="22%"/>
                                                            <col width="48%"/>
															<col width="30%"/>
                                                        </colgroup>
                                                        <tbody>
                                                        <tr>
                                                            <td>{{$lan::get('person_in_charge_name_title')}}</td>
                                                            <td>{{array_get($person, "person_name")}}</td>
															<td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('furigana_title')}}</td>
                                                            <td>{{array_get($person, "person_name_kana")}}</td>
															<td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('position_title')}}</td>
                                                            <td>{{array_get($person, "person_position")}}</td>
															<td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('office_name_title')}}</td>
                                                            <td>{{array_get($person, "person_office_name")}}</td>
															<td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('office_tel_title')}}</td>
                                                            <td>{{array_get($person, "person_office_tel")}}</td>
															<td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('person_in_charge_mail_title')}}</td>
                                                            <td>{{array_get($person, "person_email")}}</td>
															@if ($person['check_send_mail_flag'] == 1)
																<td>&#10004; メール受信する</td>
															@else
																<td>&#10006; メール受信しない</td>
															@endif
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="font-size:xx-small;" height="4"></td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
						</table>
					</div>

					{{--請求先情報--}}
					<h3 id="content_h3" class="box_border1">{{$lan::get('billing_title')}}</h3>
					<div style="padding:10px;" class="box_border1">
						<table border="0" cellspacing="0" cellpadding="0" width="100%" style="padding-bottom:10px;">
							<colgroup>
								<col width="20%"/>
								<col width="30%"/>
								<col width="20%"/>
								<col width="30%"/>
							</colgroup>
							{{--請求先情報--}}
							<tr style="text-align:center; padding:3px;">
								{{--請求先情報--}}
								<th class="ut_th1" style="font-size:small;">{{$lan::get('payer_information_title')}}</th>
								<td class="ut_td1" bgcolor="#eeeeee">
									@if (array_get($student, 'student_name') == array_get($student, 'parent_name'))
										{{$lan::get('personal_title')}}
									@else
										{{array_get($student, 'parent_name')}}
									@endif
								</td>
								{{--支払方法--}}
								<th class="ut_th1" style="font-size:small;">{{$lan::get('payer_method_title')}}</th>
								<td class="ut_td1" bgcolor="#eeeeee">
									@if (array_key_exists(array_get($student, 'invoice_type'), $invoice_type))
                                        <li style = "list-style-type: none; margin : auto; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[array_get($student, 'invoice_type')]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[array_get($student, 'invoice_type')]['top']}} 0%, {{$invoice_background_color[array_get($student, 'invoice_type')]['bottom']}} 100%); color :white ; font-weight: 500" >
										    {{$lan::get($invoice_type[array_get($student, 'invoice_type')])}}
                                        </li>
									@endif
									
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							<tr style="text-align:center; padding:3px;">
								{{--連絡先電話番号--}}
								<th class="ut_th1" style="font-size:small;">{{$lan::get('contact_phone_number')}}</th>
								<td class="ut_td1" bgcolor="#eeeeee">
									{{array_get($student, 'phone_no')}}
								</td>
								{{--携帯電話--}}
								<th class="ut_th1" style="font-size:small;">{{$lan::get('mobile_phone')}}</th>
								<td class="ut_td1" bgcolor="#eeeeee">
									{{array_get($student, 'handset_no')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--メールアドレス--}}
							<tr style="padding:3px;">
								<th class="ut_th1" style="font-size:small;">{{$lan::get('email_address_title')}}</th>
								<td class="ut_td1" bgcolor="#eeeeee" colspan="3">
									{{array_get($student, 'parent_mailaddress1')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--郵便番号--}}
							<tr style="padding:3px;">
								<th class="ut_th1" style="padding:3px">{{$lan::get('postal_code_title')}}</th>
								<td class="ut_td1" style="padding:3px;" bgcolor="#eeeeee" colspan="4">
									@if(array_get($student, 'zip_code1')) {{array_get($student, 'zip_code1')}}&nbsp;-&nbsp;{{array_get($student, 'zip_code2')}} @endif
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							{{--住所--}}
							<tr style="padding:3px;">
								<th class="ut_th1" style="font-size:small;">{{$lan::get('address_title')}}</th>
								<td class="ut_td1" bgcolor="#eeeeee" colspan="3">
									{{array_get($student, 'parent_pref_name')}}&nbsp;{{array_get($student, 'parent_city_name')}}&nbsp;{{array_get($student, 'address')}}&nbsp;{{array_get($student, 'building')}}
								</td>
							</tr>
							<tr>
								<td colspan="4" style="font-size:xx-small;" height="4"></td>
							</tr>
							@if (array_get($student, 'invoice_type') == \App\ConstantsModel::$INVOICE_BANK_PAYMENT)
								@if (array_get($student, 'bank_type') == \App\ConstantsModel::$FINANCIAL_TYPE_BANK)
									{{--銀行--}}
									<tr style="padding:3px;">
										<th class="ut_th1" style="font-size:small;">{{$lan::get('bank_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee">
											{{array_get($student, 'bank_name')}} ({{array_get($student, 'bank_code')}})
										</td>
										{{--支店--}}
										<th class="ut_th1" style="font-size:small;">{{$lan::get('branch_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee">
											{{array_get($student, 'branch_name')}} ({{array_get($student, 'branch_code')}})
										</td>
									</tr>
									<tr style="padding:3px;">
										<td colspan="4" style="font-size:xx-small;" height="4"></td>
									</tr>
									{{--種別--}}
									<tr style="padding:3px;">
										<th class="ut_th1" style="font-size:small;">{{$lan::get('ginkou_type_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee">
											{{array_get($type_of_bank_account, array_get($student, 'bank_account_type'))}}
										</td>
										{{--口座番号--}}
										<th class="ut_th1" style="font-size:small;">{{$lan::get('bank_acc_number_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee">
											{{array_get($student, 'bank_account_number')}}
										</td>
									</tr>
									<tr style="padding:3px;">
										<td colspan="4" style="font-size:xx-small;" height="4"></td>
									</tr>
									{{--名義人--}}
									<tr>
										<th class="ut_th1" style="font-size:small;">{{$lan::get('candidate_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee" colspan="3">
											{{array_get($student, 'bank_account_name')}}
										</td>
									</tr>
								@else
									<tr>
										<td colspan="4" style="font-size:xx-small;" height="4"></td>
									</tr>
									{{--記号--}}
									<tr style="padding:3px;">
										<th class="ut_th1" style="font-size:small;">{{$lan::get('symbol_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee">
											{{array_get($student, 'post_account_kigou')}}
										</td>
										{{--通帳番号--}}
										<th class="ut_th1" style="font-size:small;">{{$lan::get('passbook_number_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee">
											{{array_get($student, 'post_account_number')}}
										</td>
									</tr>
									<tr>
										<td colspan="4" style="font-size:xx-small;" height="4"></td>
									</tr>
									{{--種別--}}
									<tr style="padding:3px;">
										<th class="ut_th1" style="font-size:small;">{{$lan::get('ginkou_type_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee" colspan="3">
											@if (array_get($student, 'post_account_type') == 1)
												総合口座、通常貯金、通常貯蓄貯金
											@else
												振替口座
											@endif
										</td>
									</tr>
									<tr>
										<td colspan="4" style="font-size:xx-small;" height="4"></td>
									</tr>
									{{--通帳名義--}}
									<tr style="padding:3px;">
										<th class="ut_th1" style="font-size:small;">{{$lan::get('candidate_title')}}</th>
										<td class="ut_td1" bgcolor="#eeeeee" colspan="3">
											{{array_get($student, 'post_account_name')}}
										</td>
									</tr>
								@endif
							@endif
						</table>
					</div>
				</td>


				{{--HERE--}}
				<td bgcolor="#bbbbbb" width="1"></td>
				<td style="width:40%">
					<h3 id="content_h3" class="box_border1">{{$lan::get('detailed_information_title')}}</h3>
					<div style="padding:10px;" class="box_border1">
						{{--所属プラン--}}
						{{--@if($classes)--}}
						{{--<h3 id="content_h3" class="h3_3">{{$lan::get('participant_class')}}</h3>--}}
						{{--<div class="history_block">--}}
							{{--@foreach ($classes as $class)--}}
								{{--<p class="participation_block">--}}
									{{--<span class="participation_name">{{array_get($class, 'class_name')}}</span>--}}
									{{--<span class="participation_date">--}}
										{{--{{Carbon\Carbon::parse(array_get($class, 'start_date'))->format('Y-m-d')}}--}}
										{{--@if(array_get($class, 'delete_date'))--}}
											{{--{{Carbon\Carbon::parse(array_get($class, 'delete_date'))->format('Y-m-d')}}--}}
										{{--@elseif(array_get($class, 'close_date'))--}}
											{{--{{Carbon\Carbon::parse(array_get($class, 'close_date'))->format('Y-m-d')}}--}}
										{{--@endif--}}
									{{--</span>--}}
								{{--</p>--}}
							{{--@endforeach--}}
						{{--</div>--}}
						{{--@endif--}}
                        @if($classes)
                            <h3 id="content_h3" class="h3_3">{{$lan::get('participant_class')}}</h3>
                            <div class="history_block">
                                <table id="table_situation">
                                    @foreach ($classes as $k => $class)
                                        <tr class="tr_{{fmod($k,2)}}">
                                            <td colspan="3">{{array_get($class, 'class_name')}}</td>
                                        </tr>
                                        <tr class="tr_{{fmod($k,2)}}">
                                            <td class="participation_date">
                                            {{Carbon\Carbon::parse(array_get($class, 'start_date'))->format('Y-m-d')}}
                                            </td>
                                            <td style="width: 10px;">
                                                @if(array_get($class, 'delete_date') || array_get($class, 'close_date'))
                                                    -
                                                @else
                                                    &nbsp;
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_get($class, 'delete_date'))
                                                    {{Carbon\Carbon::parse(array_get($class, 'delete_date'))->format('Y-m-d')}}
                                                @elseif(array_get($class, 'close_date'))
                                                    {{Carbon\Carbon::parse(array_get($class, 'close_date'))->format('Y-m-d')}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
						{{--参加イベント--}}
						@if($courses)
							<h3 id="content_h3" class="h3_3">{{$lan::get('participant_event')}}</h3>
							<div class="history_block">
                                <table id="table_situation">
                                    @foreach ($courses as $k => $course)
                                        <tr  class="tr_{{fmod($k,2)}}">
                                            <td colspan="3">{{array_get($course, 'course_title')}}</td>
                                        </tr>
                                        <tr class="tr_{{fmod($k,2)}}">
                                            <td class="participation_date">
                                                {{Carbon\Carbon::parse(array_get($course, 'register_date'))->format('Y-m-d')}}
                                            </td>
                                            <td style="width: 10px;">
                                                @if(array_get($course, 'delete_date') || array_get($course, 'close_date'))
                                                    -
                                                @else
                                                    &nbsp;
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_get($course, 'delete_date'))
                                                    {{Carbon\Carbon::parse(array_get($course, 'delete_date'))->format('Y-m-d')}}
                                                @elseif(array_get($course, 'close_date'))
                                                    {{Carbon\Carbon::parse(array_get($course, 'close_date'))->format('Y-m-d')}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
							</div>
						@endif
						{{--参加プログラム--}}
						@if($programs)
							<h3 id="content_h3" class="h3_3">{{$lan::get('participant_program')}}</h3>
							<div class="history_block">
                                <table id="table_situation">
                                    @foreach ($programs as $k => $program)
                                        <tr class="tr_{{fmod($k,2)}}">
                                            <td colspan="3">{{array_get($program, 'program_name')}}</td>
                                        </tr>
                                        <tr class="tr_{{fmod($k,2)}}">
                                            <td class="participation_date">
                                                {{Carbon\Carbon::parse(array_get($program, 'register_date'))->format('Y-m-d')}}
                                            </td>
                                            <td style="width:10px;">
                                                @if(array_get($program, 'delete_date') || array_get($program, 'close_date'))
                                                    -
                                                @else
                                                    &nbsp;
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_get($program, 'delete_date'))
                                                  {{Carbon\Carbon::parse(array_get($program, 'delete_date'))->format('Y-m-d')}}
                                                @elseif(array_get($program, 'close_date'))
                                                   {{Carbon\Carbon::parse(array_get($program, 'close_date'))->format('Y-m-d')}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
							</div>
						@endif
						{{--支払履歴--}}
						@if($invoices)
							<h3 id="content_h3" class="h3_3">{{$lan::get('invoice_history_situation_new')}}</h3>
							<div class="history_block">
                                <table class="invoice_situation" id="table_situation">
                                    @foreach ($invoices as $k => $invoice)
                                        <tr class="tr_{{fmod($k,2)}}"">
                                            <td colspan="2">
                                                @if(array_get($invoice,'is_nyukin')==0)
                                                    {{Carbon\Carbon::parse(array_get($invoice,'invoice_year_month'))->format('Y年m月')}}{{$lan::get("invoice_year_month_name")}}
                                                @elseif(array_get($invoice,'is_nyukin')==1)
                                                    {{array_get($invoice,'item_name')}}&nbsp;イベント
                                                @else
                                                    {{array_get($invoice,'item_name')}}&nbsp;プログラム
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="tr_{{fmod($k,2)}}"">
                                            <td style="width: 60%">
                                                @if (array_get($invoice, 'paid_date'))
                                                    {{Carbon\Carbon::parse(array_get($invoice, 'paid_date'))->format('Y-m-d')}}
                                                @elseif(array_get($invoice, 'invoice_type'))
                                                    <li style = "text-align : center; list-style-type: none; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[array_get($invoice, 'invoice_type')]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[array_get($invoice, 'invoice_type')]['top']}} 0%, {{$invoice_background_color[array_get($invoice, 'invoice_type')]['bottom']}} 100%); color :white ; font-weight: 500" >
                                                        {{$lan::get($invoice_type[array_get($invoice, 'invoice_type')])}}
                                                    </li>
                                                @endif
                                            </td>
                                            <td>
                                                @if (array_get($invoice, 'paid_date'))
                                                    <?php
                                                    $type = $invoice['invoice_type'];
                                                    if (array_get($invoice, 'is_recieved') == 1 && !empty( $invoice['deposit_invoice_type'])) {
                                                        $type = $invoice['deposit_invoice_type'];
                                                    }
                                                    ?>
                                                    <li style = "text-align : center; list-style-type: none; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[$type]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[$type]['top']}} 0%, {{$invoice_background_color[$type]['bottom']}} 100%); color :white ; font-weight: 500" >
                                                        {{$lan::get($invoice_type[$type])}}
                                                    </li>
                                                @else
                                                    <span class="new" style="display: inline-block;text-align: center;  width :120px; border-radius: 5px;">
                                                        {{$lan::get('not_payment_title')}}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
							</div>
						@endif
					</div>
				</td>
			</tr>
		</table>
		<br>
		<p>
			<!-- <input id="edit" class="submit_return" type="button" value="{{$lan::get('edit_title')}}"> -->
            @if($edit_auth)
            <button id="edit" class="submit_return" type="submit"><i class="glyphicon glyphicon-edit " style="width: 20%;font-size:16px;"></i>{{$lan::get('edit_title')}}</button>
            @endif
			@if ($delete_enable && $edit_auth)
			<!-- <input id="delete_btn" class="submit_return" type="button" value="{{$lan::get('delete_title')}}"> -->
			<button id="delete_btn" class="submit_return" type="submit"><i class="glyphicon glyphicon-minus-sign " style="width: 20%;font-size:16px;"></i>{{$lan::get('delete_title')}}</button>
			@endif
			<!-- <input class="submit_return" id="submit_return" type="button" value="{{$lan::get('return_title')}}"/>  -->
			<button id="submit_return" class="submit_return" type="submit"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>&nbsp;
		</p>
		<br>
		<p>
			@if(request()->has('prev_id'))
				<input type="submit" class="btn_green" id="btn_before" value="{{$lan::get('previous_text')}}">
			@endif
			@if(request()->has('next_id'))
				<input type="submit" class="btn_green" id="btn_after" value="{{$lan::get('next_text')}}">
			@endif

		</p>
	</div>

</form>


<div id="dialog_check" class="no_title" style="display:none;">
	{{$lan::get('really_want_to_confirm_title')}}
</div>
<div id="dialog_complete" class="no_title" style="display:none;">
	{{$lan::get('really_want_to_prepayment_title')}}
</div>
<div id="dialog-confirm"  style="display: none;">
	{{$lan::get('really_want_to_delete_title')}}
</div>
<div id="dialog_pdf" style="display:none;">
	{{$lan::get('create_pdf_of_membership_card_title')}}
</div>
<div id="dialog_active" class="no_title" style="display:none;">
	{{$lan::get('change_status_title')}}
</div>

<script type="text/javascript">
	$(document).ready(function() {
        // Accordion change icon when toggle
        $(".drop_down").click(function(e){
            e.preventDefault();
            if($(this).children().hasClass("fa fa-chevron-down")){
                $(this).children().removeClass("fa fa-chevron-down");
                $(this).children().addClass("fa fa-chevron-up");
            }else if($(this).children().hasClass("fa fa-chevron-up")){
                $(this).children().removeClass("fa fa-chevron-up");
                $(this).children().addClass("fa fa-chevron-down");
            }
        });

		var labels = {
			ok: "{{$lan::get('run_title')}}",
			delete: "{{$lan::get('delete_title')}}",
			cancel: "{{$lan::get('cancel_title')}}"
		};

		var WindowHeight = $(window).height() -44; //WindowHeightは変数のため任意の名前をつける
		if(WindowHeight > 320){ //開いた画面が320px以上なら実行
			//body要素に高さを書き込む
			$('#wrapper').css('height',WindowHeight+'px');
		}

		$("#edit").click(function() {
			java_post("{{$_app_path}}student/edit?id={{$student['id']}}");
			return false;
		});

		//Dialog when delete student
		$("#dialog-confirm").dialog({
			title: '{{$lan::get('main_title')}}',
			autoOpen: false,
			dialogClass: "no-close",
			resizable: false,
			modal: true,
			buttons: [
				{
					text: labels.ok, // OK
					click: function() {
						$(this).dialog("close");
						java_post("{{$_app_path}}student/delete?id={{$student['id']}}");
						return false;
					}
				},
				{
					text: labels.cancel, //Cancel
					click: function() {
						$(this).dialog("close");
					}
				}
			]
		});

		//Delete student
		$("#delete_btn").click(function() {
			$( "#dialog-confirm" ).dialog('open');
			return false;
		});

		//Dialog when change student status
		$( "#dialog_active" ).dialog({
			title: '{{$lan::get('main_title')}}',
			autoOpen: false,
			dialogClass: "no-close",
			resizable: false,
			modal: true,
			buttons: [
				{
					text: labels.ok, // OK
					click: function() {
						var studentId = "{{array_get($student, 'id')}}";
						var studentState = $("#student_state").val();
						var _token = "{{csrf_token()}}";
						$.ajax({
							type: "post",
							url: "/school/student/ajax_change_student_state",
							data: {id: studentId, state: studentState, _token: _token},
							success: function(data) {
							}
						});
						$( this ).dialog( "close" );
						return false;
					}
				},
				{
					text: labels.cancel, // Cancel
					click: function() {
						$( this ).dialog( "close" );
						var def_state = "@if ($student['resign_date']) 3 @elseif ($student['active_flag']) 1 @else 2 @endif";
						$("#student_state").val(def_state)
						return false;
					}
				}
			]
		});

		//Change status
		$("#student_state").change(function() {
			$( "#dialog_active" ).dialog('open');
			return false;
		});

		$("#btn_before").click(function() {
			java_post("{{$_app_path}}student/detail?id={{request('prev_id')}}");
			return false;
		});

		$("#btn_after").click(function() {
			java_post("{{$_app_path}}student/detail?id={{request('next_id')}}");
			return false;
		});
		$("#submit_return").click(function() {
			java_post("{{$_app_path}}student");
			return false;
		});
	});
</script>
@stop
