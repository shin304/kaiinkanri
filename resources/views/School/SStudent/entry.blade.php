@extends('_parts.master_layout')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/student.css" />
    <style type="text/css">
        img.h120 {
            width: auto;
            max-height: 120px;
        }
        .submit2:hover, .top_btn li:hover, .submit_return:hover, input[type="button"]:hover, #generateAddressOther:hover, #inputAdd:hover, #generateAddressDlgStu:hover, #generateAddress2:hover, #generateAddress:hover, #generateAddressDlg:hover, button[type="button"]:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .top_btn li {
            font-size: 12px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        input[type="button"], #inputAdd, button[type="button"] {
            font-size: 12px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .submit2, .submit_return {
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            font-size: 14px !important;
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>

    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-group"></i> {{$lan::get('main_title')}}</h2>
        <div class="center_content_header_right">
            <div class="top_btn"></div>
        </div>
        <div class="clr"></div>
    </div>

    <div id="section_content">
        <h3 id="content_h3" class="box_border1">{{$lan::get('detailed_information_title')}}
            {{array_get($request, 'id') ? $lan::get('edit_title') : $lan::get('register_title')}}
        </h3>

        <div id="section_content_in" class="student_info_container">
            @if($request->has('errors'))
                <ul class="message_area">
                    @foreach($request->errors->all() as $error)
                        <li class="error_message">{{$lan::get($error)}}</li>
                    @endforeach
                </ul>
            @endif


            @if (session()->get('status'))
                <ul class="message_area">
                    <li class="alert alert-success" role="alert" style="color: blue;">{{session()->pull('status')}}</li>
                </ul>
            @endif

            <form id="action_form" name="action_form" method="post" enctype="multipart/form-data" autocomplete="off">
                {{ csrf_field() }}
                {{--Extra info for validation--}}
                <input type="hidden" id="id" name="id" value="{{array_get($request, 'id')}}"/>
                <input type="hidden" id="parent_id" name="parent_id" value="{{array_get($request, 'parent_id')}}"/>
                {{--<input type="hidden" id="login_account_id" name="login_account_id" value="{{array_get($request, 'login_account_id')}}" />--}}
                {{--<input type="hidden" id="login_account_temp_id" name="login_account_temp_id" value="{{array_get($request, 'login_account_temp_id')}}" />--}}
                {{--<input type="hidden" id="parent_bank_account_id" name="parent_bank_account_id" value="{{array_get($request, 'parent_bank_account_id')}}" />--}}
                <input type="hidden" id="have_student_join_info" name="have_student_join_info"
                       value="{{array_get($request, 'have_student_join_info')}}"/>
                <input type="hidden" id="have_student_address_info" name="have_student_address_info"
                       value="{{array_get($request, 'have_student_address_info')}}"/>
                <input type="hidden" id="have_parent_address_info" name="have_parent_address_info"
                       value="{{array_get($request, 'have_parent_address_info')}}"/>
                <input type="hidden" id="have_payment_info" name="have_payment_info"
                       value="{{array_get($request, 'have_payment_info')}}"/>
                <input type="hidden" id="have_payment_adjust" name="have_payment_adjust"
                       value="{{array_get($request, 'have_payment_adjust')}}"/>
                <input type="hidden" id="have_payment_adjust_student" name="have_payment_adjust_student"
                       value="{{array_get($request, 'have_payment_adjust_student')}}"/>


                <div class="group_header">
                    <h4 style="display: inline-block">{{$lan::get('student_information_title')}}</h4>
                    <div class="exe_button" style="float: right; line-height: 40px">
                        <input id="contact1" type="button" value="{{$lan::get('Student1')}}"
                               class="@if($request->has('have_student_join_info')) display_none @endif"/>
                        <input id="contact2" type="button" value="{{$lan::get('Student2')}}"
                               class="@if($request->has('have_student_address_info')) display_none @endif"/>
                    </div>
                </div>
                <div style="clear: both"></div>
                <span class="aster">&lowast;</span>{{$lan::get('items_mandatory_title')}}<br/>
                <table id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    {{--画像--}}
                    <tr>
                        <td class="t6_td1">{{$lan::get('image_title')}}</td>
                        <td class="t4td2">
                            <div class="imgInput">
                                @if(request("student_img") && \Illuminate\Support\Facades\Storage::disk('uploads')->exists(request("student_img")))
                                    <img src="/storage/uploads/{{(request("student_img"))}}" id="student_avatar"
                                         alt="student image" class="imgView h120"/><br/>
                                @else
                                    <img src="/img/school/_nouser.png" id="student_avatar" alt="student image"
                                         class="imgView h120"/><br/>
                                @endif

                                <input type="file" id="student_img" name="student_img_file" size="30"/>
                                <input type="hidden" name="card_img" value="{{array_get($request, 'card_img')}}"/>
                            </div>
                        </td>
                    </tr>
                    {{--ステータス--}}
                    @if(request()->has('id'))
                        <tr>
                            <td class="t6_td1">{{$lan::get('status_title')}}</td>
                            <td class="t4td2">
                                <select name="student_state" id="student_state">
                                    <option label="{{$lan::get('in_teaching_title')}}"
                                            value="{{\App\ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT}}"
                                            @if(request('student_state') == \App\ConstantsModel::$MEMBER_STATUS_UNDER_CONTRACT) selected @endif>{{$lan::get('in_teaching_title')}}</option>
                                    <option label="{{$lan::get('withdraw_title')}}"
                                            value="{{\App\ConstantsModel::$MEMBER_STATUS_END_CONTRACT}}"
                                            @if (request('student_state') == \App\ConstantsModel::$MEMBER_STATUS_END_CONTRACT) selected @endif>{{$lan::get('withdraw_title')}}</option>
                                </select>
                            </td>
                        </tr>
                    @endif
                    {{--会員番号--}}
                    <tr>
                        <td class="t6_td1">{{$lan::get('member_no_title')}} <span class="aster">*</span></td>
                        <td class="t4td2">
                            <input type="text" name="student_no" value="{{request('student_no')}}"
                                   placeholder="@if (session()->has('school.login.prefix_code')){{session('school.login.prefix_code')}}-@endif{{$lan::get('student_no_sample_code')}}">
                        </td>
                    </tr>
                    {{--個人・法人--}}
                    <tr>
                        <td class="t6_td1">{{$lan::get('student_category_title')}}</td>
                        <td class="t4td2">
                            <label>
                                <input type="radio" name="student_category"
                                       value="{{\App\ConstantsModel::$MEMBER_CATEGORY_PERSONAL}}"
                                       @if(!array_get($request, "student_category") || array_get($request, "student_category") == \App\ConstantsModel::$MEMBER_CATEGORY_PERSONAL) checked @endif>
                                {{$lan::get("member_personal")}}
                            </label>
                            <label>
                                <input type="radio" name="student_category"
                                       value="{{\App\ConstantsModel::$MEMBER_CATEGORY_CORP}}"
                                       @if(array_get($request, "student_category") == \App\ConstantsModel::$MEMBER_CATEGORY_CORP) checked @endif>
                                {{$lan::get("member_corp")}}
                            </label>
                        </td>
                    </tr>

                {{-- 法人の場合の法人情報 - Accordion corporation info begin--}}
                    <tr id="corporation_info_container"
                        class="@if(!array_get($request, "student_category") || array_get($request, "student_category") == \App\ConstantsModel::$MEMBER_CATEGORY_PERSONAL) display_none @endif" >
                        <td colspan="2">
                            <p class="drop_down" data-toggle="collapse" href="#collapse_corporation_info" style="width: 100%;padding:10px;margin-bottom: 0;">{{$lan::get('corporation_info_title')}}<i style="width:5%;float:right;" class="fa fa-chevron-down"></i></p>
                            <div id="collapse_corporation_info" class="panel-collapse collapse">
                                {{--Accordion repretative info begin--}}
                                <div class="representative">
                                    <p class="drop_down" data-toggle="collapse" href="#collapse_representative" style="width: 90%;text-align: center;">{{$lan::get('representative_info_title')}}<i style="float:right" class="fa fa-chevron-down" style="width: 10%"></i></p>
                                    <div id="collapse_representative" class="panel-collapse collapse">
                                        <table class="dialog-table" id="table6">
                                            <colgroup>
                                                <col width="29%"/>
                                                <col width="81%"/>
                                            </colgroup>
                                            <tbody>
                                                <tr>
                                                    <td>{{$lan::get('representative_name_title')}}</td>
                                                    <td><input class="text_m" type="text" name="representative_name" value="{{array_get($request, 'representative_name')}}"/></td>
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('furigana_title')}}</td>
                                                    <td><input class="text_m" type="text" name="representative_name_kana" value="{{array_get($request, 'representative_name_kana')}}"/></td>
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('position_title')}}</td>
                                                    <td><input class="text_m" type="text" name="representative_position" value="{{array_get($request, 'representative_position')}}"/></td>
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('representative_mail_title')}}</td>
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td style="padding-left: 0px;"><input class="text_m" type="text" name="representative_email" value="{{array_get($request, 'representative_email')}}"/></td>
                                                                <td><input type="checkbox" name="representative_send_mail_flag" value="1" @if (array_get($request,"representative_send_mail_flag") == 1) checked @endif></td>
                                                                <td>メール受信</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$lan::get('representative_tel_title')}}</td>
                                                    <td><input class="text_m" type="text" name="representative_tel" value="{{array_get($request, 'representative_tel')}}"/></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{--Accordion repretative info end--}}

                                {{--Accordion person_in_charge info begin--}}
                                <div id="person_in_charge">
                                    @if ( $request->has('person_in_charge') && ( count(array_get($request, 'person_in_charge')) > 0 ))
                                        @foreach( array_get($request, 'person_in_charge') as $idx => $person )
                                            <div class="person_in_charge">
                                                <p class="drop_down" data-toggle="collapse" href="#collapse_person_{{$loop->index+1}}" style="width: 90%">{{$lan::get('person_in_charge_info_title')}}{{$loop->index+1}}<i class="fa fa-chevron-down"></i></p>
                                                <p style="float:right;"><button type="button" onclick="removePersonInChargeRow(this); return false;" >{{$lan::get('delete_title')}}</button></p>
                                                <div id="collapse_person_{{$loop->index+1}}" class="panel-collapse collapse">
                                                    <table class="dialog-table" id="table6">
                                                        <colgroup>
                                                            <col width="29%"/>
                                                            <col width="81%"/>
                                                        </colgroup>
                                                        <tbody>
                                                        <tr>
                                                            <td>{{$lan::get('person_in_charge_name_title')}}</td>
                                                            <td><input class="text_m" type="text" name="person_in_charge[{{$loop->index+1}}][person_name]" value="{{array_get($person, "person_name")}}"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('furigana_title')}}</td>
                                                            <td><input class="text_m" type="text" name="person_in_charge[{{$loop->index+1}}][person_name_kana]" value="{{array_get($person, "person_name_kana")}}"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('position_title')}}</td>
                                                            <td><input class="text_m" type="text" name="person_in_charge[{{$loop->index+1}}][person_position]" value="{{array_get($person, "person_position")}}" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('office_name_title')}}</td>
                                                            <td><input class="text_m" type="text" name="person_in_charge[{{$loop->index+1}}][person_office_name]" value="{{array_get($person, "person_office_name")}}" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('office_tel_title')}}</td>
                                                            <td><input class="text_m" type="text" name="person_in_charge[{{$loop->index+1}}][person_office_tel]" value="{{array_get($person, "person_office_tel")}}" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$lan::get('person_in_charge_mail_title')}}</td>
                                                            <td>
                                                                <table>
                                                                    <tr>
                                                                        <td  style="padding-left: 0px;"><input class="text_m" type="text" name="person_in_charge[{{$loop->index+1}}][person_email]" value="{{array_get($person, "person_email")}}" /></td>
                                                                        <td><input id="custom7" type="checkbox" name="person_in_charge[{{$loop->index+1}}][check_send_mail_flag]" value="1" @if (array_get($person,"check_send_mail_flag") == 1) checked @endif></td>
                                                                        <td>メール受信</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            {{--<td><input class="text_m" type="text" name="person_in_charge[{{$loop->index+1}}][person_email]" value="{{array_get($person, "person_email")}}" /></td>--}}
                                                            {{--<td><input class="text_m" type="checkbox" name="person_in_charge[{{$loop->index+1}}][check_send_mail]" value="{{array_get($person, "check_send_mail")}}"></td>--}}
                                                        </tr>
                                                        <input type="hidden" name="person_in_charge[{{$loop->index+1}}][id]" value="{{array_get($person, "id")}}"/>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                {{--Accordion person_in_charge info begin--}}
                                <p style="text-align: center;margin-top:10px;">
                                    <input id="total_person_in_charge" type="hidden" value="{{count(array_get($request, 'person_in_charge'))}}" />
                                    <input type="button" id="addPersonInCharge" style="width: 100px" value="{{$lan::get('add_person_in_charge')}}"/>
                                </p>
                            </div>
                        </td>
                    </tr>
                {{-- 法人の場合の法人情報 -Accordion corporation info end--}}

                    {{--法人の場合の人数--}}
                    <tr id="total_member_container"
                        class="@if(!array_get($request, "student_category") || array_get($request, "student_category") == \App\ConstantsModel::$MEMBER_CATEGORY_PERSONAL) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('total_member')}}
                            <span class="aster">*</span>
                        </td>
                        <td class="t4td2">
                            <input class="text_m ime_inactive text_right" style="width: 60px;" id="total_member"
                                   type="number" min="1" name="total_member"
                                   value="{{array_get($request, 'total_member')}}"/>
                            {{$lan::get('person')}}
                        </td>
                    </tr>
                    {{--種別--}}
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('category_title')}}
                            <span class="aster">&lowast;</span>
                        </td>
                        <td class="t4td2">
                            <select name="m_student_type_id">
                                @foreach($studentTypes as $type)
                                    <option value="{{array_get($type, 'id')}}"
                                            @if(request('m_student_type_id') == array_get($type, 'id')) selected @endif>{{array_get($type, 'name')}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    {{--名前--}}
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('first_name_title')}}
                            <span class="aster">&lowast;</span>
                        </td>
                        <td class="t4td2">
                            <input class="text_m ime_active" id="student_name" type="text" name="student_name"
                                   value="{{array_get($request, 'student_name')}}"
                                   placeholder="{{$lan::get('first_name_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--フリガナ--}}
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('furigana_title')}}
                        </td>
                        <td class="t4td2">
                            <input class="text_m ime_active" type="text" id="student_name_kana" name="student_name_kana"
                                   value="{{array_get($request, 'student_name_kana')}}"
                                   placeholder="{{$lan::get('furigana_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--ローマ字--}}
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('latin_alphabet_title')}}
                        </td>
                        <td class="t4td2">
                            <input class="text_m ime_active" type="text" name="student_romaji"
                                   value="{{array_get($request, 'student_romaji')}}"
                                   placeholder="{{$lan::get('latin_alphabet_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--メールアドレス--}}
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('email_address_title')}}
                            <span class="aster">&lowast;</span>
                        </td>
                        <td class="t4td2">
                            <input class="text_m ime_active" id="mailaddress" type="text" name="mailaddress"
                                   value="{{array_get($request, 'mailaddress')}}"
                                   placeholder="{{$lan::get('email_address_title')}}{{$lan::get('placeholder_input_temp')}}"/>&nbsp;<b>{{$lan::get('use_as_login_title')}}</b>
                        </td>
                    </tr>
                    <tr @if	(array_get($request, 'id')) style="display: none;" @endif>
                        <td class="t6_td1">
                            {{$lan::get('password_title')}}
                            <span class="aster">&lowast;</span>
                        </td>
                        <td class="t4td2" style = "display: -webkit-inline-box; width: 100%">
                            <input class="text_m" type="password" id="student_pass" name="student_pass"
                                   autocomplete="new-password" value="{{array_get($request, 'student_pass')}}"
                                   placeholder="{{$lan::get('password_title')}}{{$lan::get('placeholder_input_temp')}}"/> &nbsp;
                            <div>
                                @if	(array_get($request, 'id'))
                                    <span class="col_msg"><b>※{{$lan::get('input_only_to_change_title')}}</b></span><br/>
                                @endif
                                <span class="col_msg"><b>※{{$lan::get('password_regex_warning')}}</b></span>
                            </div>
                        </td>
                    </tr>
                    {{--生年月日--}}
                    <tr id="birthday_container"
                        class="@if(request("student_category") == \App\ConstantsModel::$MEMBER_CATEGORY_CORP)) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('birthday_title')}}
                            <span class="aster">&lowast;</span>
                        </td>
                        <td class="t4td2">
                            <input type="text" name="birthday" id="birthday" value="{{request('birthday')}}" style="">
                        </td>
                    </tr>
                    {{--性別--}}
                    <tr id="sex_container"
                        class="@if(request("student_category") == \App\ConstantsModel::$MEMBER_CATEGORY_CORP)) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('gender_title')}}
                        </td>
                        <td class="t4td2">
                            <select name="sex" style="width: 70px">
                                <option value="1"
                                        @if(array_get($request, 'sex') == '1') selected @endif>{{$lan::get('male_title')}}</option>
                                <option value="2"
                                        @if(array_get($request, 'sex') == '2') selected @endif>{{$lan::get('female_title')}}</option>
                                <option value="3"
                                        @if(array_get($request, 'sex') == '3') selected @endif>{{$lan::get('unknown_title')}}</option>
                            </select>
                        </td>
                    </tr>

                    {{--入会日--}}
                    <tr id="enter_date" class="@if(!$request->has('have_student_join_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('join_date_title')}}
                        </td>
                        <td>
                            <input class="DateInput" id="enter_date1" type="text" name="enter_date"
                                   value="{{array_get($request, 'enter_date')}}"
                                   placeholder="{{$lan::get('join_date_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--入会理由--}}
                    <tr id="enter_memo" class="@if(!$request->has('have_student_join_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('join_memo_title')}}
                        </td>
                        <td class="t4td2">
                            <!-- <textarea class="ime_active" id="enter_memo1" name="enter_memo" cols="30" rows="4"
                                      placeholder="{{$lan::get('given_name')}}{{$lan::get('join_memo_title')}}">{{array_get($request, 'enter_memo')}}</textarea> -->
                            <textarea class="ime_active" id="enter_memo1" name="enter_memo" cols="30" rows="4"
                                      placeholder="{{$lan::get('enter_memo_placeholder')}}">{{array_get($request, 'enter_memo')}}</textarea>
                        </td>
                    </tr>
                    {{--退会日--}}
                    <tr id="resign_date" class="@if(!$request->has('have_student_join_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('withdraw_date_title')}}
                        </td>
                        <td>
                            <input class="DateInput" id="resign_date1" type="text" name="resign_date"
                                   value="{{array_get($request, 'resign_date')}}"
                                   placeholder="{{$lan::get('withdraw_date_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--退会理由--}}
                    <tr id="resign_memo" class="@if(!$request->has('have_student_join_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('withdraw_memo_title')}}
                        </td>
                        <td class="t4td2">
                            <textarea form="action_form" class="ime_active" id="resign_memo1" name="resign_memo"
                                      cols="30" rows="4"
                                      placeholder="{{$lan::get('withdraw_memo_title')}}{{$lan::get('placeholder_input_temp')}}">{{array_get($request, 'resign_memo')}}</textarea>
                        </td>
                    </tr>
                    {{--郵便番号--}}
                    <tr id="student_zip_code"
                        class="@if(!$request->has('have_student_address_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('postal_code_title')}}
                        </td>
                        <td>
                            &#12306;&nbsp;
                            <input class="text_ss ime_inactive" id="student_zip_code1" maxlength="3"
                                   style="width: 48px;" type="text" name="student_zip_code1"
                                   value="{{array_get($request, 'student_zip_code1')}}"
                                   pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                            &nbsp;－
                            <input class="text_ss ime_inactive" id="student_zip_code2" maxlength="4"
                                   style="width: 60px;" type="text" name="student_zip_code2"
                                   value="{{array_get($request, 'student_zip_code2')}}"
                                   pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)"  style="ime-mode:disabled"/>&nbsp;&nbsp;
                            <button type="button" id="generateAddress" style="color: #595959;height: 30px;background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);">{{$lan::get('generate_address')}}</button>
                        </td>
                    </tr>
                    {{--都道府県名--}}
                    <tr id="_pref_id" class="@if(!$request->has('have_student_address_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('state_name_title')}}
                        </td>
                        <td class="t4td2">
                            <select name="_pref_id" id="_pref_id1">
                                <option value=""></option>
                                @foreach($prefList as $key => $item)
                                    <option value="{{$key}}"
                                            @if(array_get($request, '_pref_id') == $key) selected @endif>{{$item}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    {{--市区町村名--}}
                    <tr id="_city_id" class="@if(!$request->has('have_student_address_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('city_name_title')}}
                        </td>
                        <td class="t4td2">
                            <select name="_city_id" id="_city_id1">
                                <option value=""></option>
                                @foreach($cityListForStudent as $key => $item)
                                    <option value="{{$key}}"
                                            @if(array_get($request, '_city_id') == $key) selected @endif>{{$item}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    {{--番地--}}
                    <tr id="student_address"
                        class="@if(!$request->has('have_student_address_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('address_number_title')}}
                        </td>
                        <td class="t4td2">
                            <input class="text_l" id="student_address1" type="text" name="student_address"
                                   value="{{array_get($request, 'student_address')}}"
                                   placeholder="{{$lan::get('address_number_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--ビル名--}}
                    <tr id="student_building"
                        class="@if(!$request->has('have_student_address_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('building_title')}}
                        </td>
                        <td class="t4td2">
                            <input class="text_l" id="student_building1" type="text" name="student_building"
                                   value="{{array_get($request, 'student_building')}}"
                                   placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--連絡先電話番号--}}
                    <tr id="student_phone_no"
                        class="@if(!$request->has('have_student_address_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('contact_phone_number')}}
                            <span class="aster">&lowast;</span>
                        </td>
                        <td class="t4td2">
                            {{--<input class="text_m ime_inactive" id="student_phone_no1" type="text"--}}
                                   {{--name="student_phone_no" value="{{array_get($request, 'student_phone_no')}}"--}}
                                   {{--placeholder="{{$lan::get('contact_phone_number')}}{{$lan::get('placeholder_input_temp')}}"--}}
                                   {{--pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>--}}
                            <input class="text_m ime_inactive" id="student_phone_no1" type="text"
                                   name="student_phone_no" value="{{array_get($request, 'student_phone_no')}}"
                                   placeholder="{{$lan::get('contact_phone_number')}}{{$lan::get('placeholder_input_temp')}}"
                                   pattern="\d*" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                        </td>
                    </tr>
                    {{--携帯電話--}}
                    <tr id="student_handset_no"
                        class="@if(!$request->has('have_student_address_info')) display_none @endif">
                        <td class="t6_td1">
                            {{$lan::get('mobile_phone_title')}}
                        </td>
                        <td class="t4td2">
                            <input class="text_m ime_inactive" id="student_handset_no1" type="text"
                                   name="student_handset_no" value="{{array_get($request, 'student_handset_no')}}"
                                   placeholder="{{$lan::get('mobile_phone_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                    {{--有効期限--}}
                    <tr id="valid_date">
                        <td class="t6_td1">
                            {{$lan::get('valid_date_title')}}
                        </td>
                        <td>
                            <input class="DateInput" id="valid_date" type="text" name="valid_date"
                                   value="{{array_get($request, 'valid_date')}}"
                                   placeholder="{{$lan::get('valid_date_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                        </td>
                    </tr>
                </table>
                {{--割増・割引--}}
                <div id="AdjustInfoStudent">
                    <h4>{{$lan::get('premium_discount')}}</h4>
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        {{--割増・割引項目--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('premium_discount_items')}}
                            </td>
                            <td>
                                <div id="inputActiveStudent">

                                    @if($request->has('payment_student') && (count($request['payment_student']) >0))
                                        @foreach(array_get($request, 'payment_student') as $k =>$v)
                                            <input type="hidden" name="payment_student_delete[{{array_get($v,'payment_id')}}]" value ="0"/>
                                            <div class="InputAreaStudent">
                                                <table class="adjust_payment_container_student">
                                                    <tr>
                                                        <td class="t4d2">
                                                            {{--対象月--}}
                                                            {{$lan::get('target_month')}}
                                                            <select name="payment_student[{{$loop->index}}][month]"
                                                                    form="action_form">
                                                                <option value=""></option>
                                                                @foreach ($month_list as $key =>$row)
                                                                    <option value="{{$key}}"
                                                                            @if(array_get($v, 'month') == $key) selected @endif>{{$row}}</option>
                                                                @endforeach
                                                            </select>
                                                            {{--摘要--}}
                                                            &nbsp;{{$lan::get('abstract')}}
                                                            <select form="action_form"
                                                                    name="payment_student[{{$loop->index}}][invoice_adjust_name_id]"
                                                                    class="payment_adjust">
                                                                <option value=""></option>
                                                                @foreach($invoice_adjust_list as $key => $row)
                                                                    <option value="{{array_get($row, 'id')}}"
                                                                            @if(array_get($v, 'invoice_adjust_name_id') == array_get($row, 'id')) selected @endif>{{array_get($row, 'name')}}</option>
                                                                @endforeach
                                                            </select>
                                                            {{--金額--}}
                                                            &nbsp;{{$lan::get('price')}}
                                                            <input type="text" form="action_form"
                                                                   name="payment_student[{{$loop->index}}][adjust_fee]"
                                                                   class="payment_fee text_right" style="width: 80px;"
                                                                   value="{{array_get($v,'adjust_fee')}}"/>
                                                            &nbsp;{{$lan::get('circle')}}

                                                            <input type="hidden"
                                                                   name="payment_student[{{$loop->index}}][payment_id]"
                                                                   id="payment_id_{{$loop->index}}"
                                                                   value="{{array_get($v,'payment_id')}}"/>
                                                            <a class="inputDeleteStudent"href="#"
                                                              data-adjust_id="{{array_get($v,'payment_id')}}">
                                                                    <input type="button"
                                                                           value="{{$lan::get('delete')}}"/></a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div style="margin: 10px 10px 17px 120px;">
                                    <input type="button" id="inputAddStudent" style="width: 100px"
                                           value="{{$lan::get('add_items')}}"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                {{--その他--}}
                <h4>{{$lan::get('other_title')}}</h4>
                <table id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    {{--メモ--}}
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('memo_title')}}
                        </td>
                        <td class="t4td2">
                            <textarea form="action_form" class="ime_active" id="input3" name="memo1" cols="60" rows="10"
                                      placeholder="{{$lan::get('memo_title')}}{{$lan::get('placeholder_input_temp')}}">{{array_get($request, 'memo1')}}</textarea>
                        </td>
                    </tr>
                    {{--extra field--}}
                    @foreach($additionalCategories as $category)
                        <tr>
                            <td class="t6_td1">
                                {{array_get($category, 'name')}}
                            </td>
                            <td class="t4td2">
                                <input type="text" name="additional[{{array_get($category, 'code')}}]"
                                       value="{{array_get($request, 'additional.' . array_get($category, 'code'), array_get($category, 'value'))}}"
                                       placeholder="{{array_get($category, 'name')}}{{$lan::get('placeholder_input_temp')}}">
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{-- BEGIN 送付先宛名 ----------------------------------------------------------------------------------------------}}
                <div id="">
                    <h4>{{$lan::get('student_addressee_title')}}</h4>
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        <tr>
                            <td class="t6_td1"><label><input type="radio" name="other_name_flag" value="0" checked> {{$lan::get('student_name_title')}}</label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="t6_td1"><label><input type="radio" name="other_name_flag" value="1" @if ($request->other_name_flag == 1) checked @endif> {{$lan::get('other_title')}}</label></td>
                            <td class="t6_td1"><input type="text" class="l_text form-group" name="student_name_other" value="{{$request->student_name_other}}" placeholder="{{$lan::get('student_addressee_input_title')}}" maxlength="255"></td>
                        </tr>
                    </table>
                </div>
                {{--  END 送付先宛名---------------------------------------------------------------------------------------}}
                {{--  BEGIN 送付先住所---------------------------------------------------------------------------------------}}
                <div id="">
                    <h4>{{$lan::get('student_addressee_location_title')}}</h4>
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        <tr>
                            <td class="t6_td1"><label><input type="radio" name="other_address_flag" value="0" checked> {{$lan::get('student_registered_address_title')}}</label></td>
                            <td class="t6_td1"></td>
                        </tr>
                        <tr>
                            <td class="t6_td1"><label><input type="radio" name="other_address_flag" value="1" @if ($request->other_address_flag == 1) checked @endif> {{$lan::get('other_title')}}</label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="t6_td1 td_inner"> {{$lan::get('postal_code')}}</td>
                            <td>
                                &#12306;&nbsp;
                                <input style="width:50px;" type="text" name="zip_code1_other" value="{{$request->zip_code1_other}}" maxlength="3"
                                       pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&ndash;
                                <input class="text_ss" type="text" name="zip_code2_other" value="{{$request->zip_code2_other}}" maxlength="4"
                                       pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&nbsp;
                                <button type="button" id="generateAddressOther" style="color: #595959;height: 30px;">{{$lan::get('generate_address')}}</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="t6_td1 td_inner"> {{$lan::get('prefecture_name')}}</td>
                            <td class="t4td2">
                                <select name="pref_id_other" id="address_pref_other" style="width: 200px;">
                                    <option value=""></option>
                                    @foreach($prefList as $key => $item)
                                        <option value="{{$key}}" @if(($request->pref_id_other) == $key) selected="selected" @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="t6_td1 td_inner"> {{$lan::get('city_name')}}</td>
                            <td class="t4td2">
                                <select name="city_id_other" id="address_city_other" style="width: 200px;">
                                    <option value=""></option>
                                    @foreach($cityOtherList as $key => $item)
                                        <option value="{{$key}}" @if(($request->city_id_other) == $key) selected="selected" @endif>{{$item}}</option>
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="t6_td1 td_inner"> {{$lan::get('address_title')}}</td>
                            <td class="t4td2"><input class="text_m" style="ime-mode:active;" type="text" name="student_address_other" placeholder="{{$lan::get('address_title')}}{{$lan::get('placeholder_input_temp')}}" value="{{$request->student_address_other}}" maxlength="255"/></td>
                        </tr>
                        <tr>
                            <td class="t6_td1 td_inner"> {{$lan::get('building_title')}}</td>
                            <td class="t4td2"><input class="text_m" style="ime-mode:active;" type="text" name="student_building_other" placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_input_temp')}}" value="{{$request->student_building_other}}" maxlength="255"/></td>
                        </tr>
                    </table>
                </div>
                {{--  END 送付先住所---------------------------------------------------------------------------------------}}

                <br/>

                {{--請求先--}}
                <div class="group_header">
                    <h4 style="display: inline-block">{{$lan::get('billing_title')}}</h4>
                    {{--請求先選択--}}
                    <div class="exe_button" style="float: right; line-height: 40px">
                        <input type="button" id="btn_copy_student_address" value="{{$lan::get('btn_copy_student_address')}}"
                               class="display_none"/>
                        <input type="button" id="contact3" value="{{$lan::get('Student3')}}"
                               class="@if($request->has('have_parent_address_info')) display_none @endif"/>
                        <input type="button" id="contact4" value="{{$lan::get('Student4')}}"
                               class="@if($request->has('have_payment_info')) display_none @endif"/>
                        <input type="button" id="btn_reset_parent" value="{{$lan::get('reset_parent')}}"
                               class="@if(!$request->has('parent_id')) display_none @endif"/>
                        <input type="button" id="btn_add_parent" value="{{$lan::get('billing_selection_title')}}"
                               class="@if($request->has('parent_id')) display_none @endif"/>
                    </div>
                </div>
                <div style="clear: both"></div>

                <div id="parent_area">
                    <span class="aster">&lowast;</span>{{$lan::get('mandatory_items_marked')}}
                    <input type="hidden" name="login_account_id" value="{{array_get($request, 'login_account_id')}}"/>
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        {{--請求先名前--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('given_name')}}
                                <span class="aster">&lowast;</span>
                            </td>
                            <td class="t4td2">
                                <input class="text_m ime_active" id="parent_name" type="text" name="parent_name"
                                       value="{{array_get($request, 'parent_name')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('given_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>
                        {{--請求先フリガナ--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('kana_name')}}
                            </td>
                            <td class="t4td2">
                                <input class="text_m ime_active" type="text" id="name_kana" name="name_kana"
                                       value="{{array_get($request, 'name_kana')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('kana_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>
                        {{--請求先メールアドレス１--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('email_address_1')}}
                                <span class="aster">&lowast;</span>
                            </td>
                            <td class="t4td2">
                                <input class="text_m ime_active" id="parent_mailaddress1" type="text"
                                       name="parent_mailaddress1" value="{{array_get($request, 'parent_mailaddress1')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('email_address_1')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>
                        {{--請求先メールアドレス２--}}
                        {{--<tr>
                            <td class="t6_td1">
                                {{$lan::get('email_address_2')}}
                            </td>
                            <td class="t4td2">
                                <input class="text_m" type="text" id="parent_mailaddress2" name="parent_mailaddress2"
                                       value="{{array_get($request, 'parent_mailaddress2')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('email_address_2')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>--}}
                        {{--請求先パスワード--}}
                        <tr class="@if($request->has('parent_id')) display_none @endif">
                            <td class="t6_td1">
                                {{$lan::get('password_title')}}
                                <span class="aster">&lowast;</span>
                            </td>
                            <td class="t4td2" style="display: -webkit-inline-box; width: 100%">
                                <input class="text_m" type="password" id="parent_pass" name="parent_pass"
                                       autocomplete="new-password" value="{{array_get($request, 'parent_pass')}}"
                                       placeholder="{{$lan::get('password_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                                <div>
                                @if	(array_get($request, 'id'))
                                        <span class="col_msg"><b>※{{$lan::get('input_only_to_change_title')}}</b></span><br/>
                                @endif
                                    <span class="col_msg"><b>※{{$lan::get('password_regex_warning')}}</b></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                {{--請求先住所--}}
                <div id="street_address" class="@if(!$request->has('have_parent_address_info')) display_none @endif">
                    <h4>{{$lan::get('street_address')}}</h4>
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        {{--請求先郵便番号--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('postal_code')}}
                            </td>
                            <td>
                                &#12306;&nbsp;
                                <input form="action_form" class="text_ss ime_inactive" maxlength="3"
                                       style="width: 48px;" type="text" id="zip_code1" name="zip_code1"
                                       value="{{array_get($request, 'zip_code1')}}"
                                       @if($request->has('parent_id')) readonly @endif/>
                                &nbsp;－
                                <input form="action_form" class="text_ss" maxlength="4" style="width: 60px;" type="text"
                                       id="zip_code2" name="zip_code2" value="{{array_get($request, 'zip_code2')}}"
                                       @if($request->has('parent_id')) readonly @endif/>&nbsp;&nbsp;
                                <button type="button" id="generateAddress2" style="color: #595959;height: 30px;background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);" @if($request->has('parent_id')) readonly @endif>{{$lan::get('generate_address')}}</button>
                            </td>
                        </tr>
                        {{--請求先都道府県名--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('prefecture_name')}}
                            </td>
                            <td class="t4td2">
                                <select name="pref_id" id="pref_id" form="action_form"
                                        @if($request->has('parent_id')) disabled @endif>
                                    <option value=""></option>
                                    @foreach($prefList as $key => $item)
                                        <option value="{{$key}}"
                                                @if(array_get($request, 'pref_id') == $key) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        {{--請求先市区町村名--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('city_name')}}
                            </td>
                            <td class="t4td2">
                                <select name="city_id" id="city_id" style="width: 200px" form="action_form"
                                        @if($request->has('parent_id')) disabled @endif>
                                    @foreach($cityListForParent as $key => $item)
                                        <option value="{{$key}}"
                                                @if(array_get($request, 'city_id') == $key) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        {{--請求先番地--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('address_number_title')}}
                            </td>
                            <td class="t4td2">
                                <input form="action_form" class="text_l ime_active" type="text" id="address"
                                       name="address" value="{{array_get($request, 'address')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('address_number_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>
                        {{--請求先ビル名--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('building_title')}}
                            </td>
                            <td class="t4td2">
                                <input form="action_form" class="text_l ime_active" type="text" id="building"
                                       name="building" value="{{array_get($request, 'building')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>
                        {{--請求先連絡先電話番号--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('contact_phone_number')}}
                            </td>
                            <td class="t4td2">
                                <input form="action_form" class="text_m ime_inactive" type="text" id="phone_no"
                                       name="phone_no" value="{{array_get($request, 'phone_no')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('contact_phone_number')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>
                        {{--請求先携帯電話--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('mobile_phone')}}
                            </td>
                            <td class="t4td2">
                                <input form="action_form" class="text_m ime_inactive" type="text" id="handset_no"
                                       name="handset_no" value="{{array_get($request, 'handset_no')}}"
                                       @if($request->has('parent_id')) readonly
                                       @endif placeholder="{{$lan::get('mobile_phone')}}{{$lan::get('placeholder_input_temp')}}"/>
                            </td>
                        </tr>
                        {{--請求先メモ--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('memo')}}
                            </td>
                            <td class="t4td2">
                                <textarea form="action_form" class="ime_active" id="memo" name="memo" cols="30" rows="4"
                                          @if($request->has('parent_id')) readonly
                                          @endif placeholder="{{$lan::get('memo')}}{{$lan::get('placeholder_input_temp')}}">{{array_get($request, 'memo')}}</textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                {{--支払方法--}}
                <div id="payment" class="@if(!$request->has('have_payment_info')) display_none @endif">
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        {{--支払方法--}}
                        <tr>
                            <td class="t6_td1">
                                <strong>{{$lan::get('payment_method')}}</strong>
                            </td>
                            <td>
                                <select id="invoicetype" name="invoice_type" form="action_form"
                                        @if($request->has('parent_id')) disabled @endif>
                                    @foreach($payment_list as $item)
                                        <option value="{{array_get($item, 'payment_method_id')}}" @if (array_get($request, 'invoice_type') == array_get($item, 'payment_method_id')) selected="selected" @endif> {{$lan::get(array_get($item, 'payment_method_name'))}}</option>
                                    @endforeach
                                    {{--<option value="{{\App\ConstantsModel::$INVOICE_CASH_PAYMENT}}"
                                            @if (array_get($request, 'invoice_type') == \App\ConstantsModel::$INVOICE_CASH_PAYMENT) selected="selected" @endif>{{$lan::get('cash')}}</option>
                                    <option value="{{\App\ConstantsModel::$INVOICE_TRANSFER_PAYMENT}}"
                                            @if (array_get($request, 'invoice_type') == \App\ConstantsModel::$INVOICE_TRANSFER_PAYMENT) selected="selected" @endif>{{$lan::get('transfer')}}</option>
                                    <option value="{{\App\ConstantsModel::$INVOICE_BANK_PAYMENT}}"
                                            @if (array_get($request, 'invoice_type') == \App\ConstantsModel::$INVOICE_BANK_PAYMENT) selected="selected" @endif>{{$lan::get('account_transfer')}}</option>--}}
                                </select>
                            </td>
                        </tr>
                        {{--通知方法--}}
                        <tr>
                            <td class="t6_td1">
                                <strong>{{$lan::get('notification_method')}}</strong>
                            </td>
                            <td>
                                <select id="mailinfo" name="mail_infomation" form="action_form"
                                        @if($request->has('parent_id')) disabled @endif>
                                    <option value="{{\App\ConstantsModel::$NOTICE_BY_POST}}"
                                            @if ((array_get($request, 'mail_infomation') == \App\ConstantsModel::$NOTICE_BY_POST)) selected @endif>{{$lan::get('mailing')}}</option>
                                    <option value="{{\App\ConstantsModel::$NOTICE_BY_MAIL}}"
                                            @if ((array_get($request, 'mail_infomation') == \App\ConstantsModel::$NOTICE_BY_MAIL)) selected @endif>{{$lan::get('email')}}</option>
                                    <option value="{{\App\ConstantsModel::$NOTICE_NOT_SEND_MAIL}}"
                                            @if ((array_get($request, 'mail_infomation') == \App\ConstantsModel::$NOTICE_NOT_SEND_MAIL)) selected @endif>{{$lan::get('notsend')}}</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    {{--口座情報--}}
                    <div id="invoiceinfo"
                         class="@if(!$request->has('have_payment_info') || $request->invoice_type != \App\Common\Constants::$PAYMENT_TYPE['POST_RICOH']) display_none @endif">
                        <h4>{{$lan::get('account_information')}}</h4>
                        <table id="table6">
                            <colgroup>
                                <col width="30%"/>
                                <col width="70%"/>
                            </colgroup>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('financial_organizations')}}
                                </td>
                                <td>
                                    <select id="banktype" name="bank_type" form="action_form"
                                            @if($request->has('parent_id')) disabled @endif>
                                        <option value="{{\App\ConstantsModel::$FINANCIAL_TYPE_BANK}}"
                                                @if (array_get($request, 'bank_type') == \App\ConstantsModel::$FINANCIAL_TYPE_BANK) selected @endif>{{$lan::get('bank_credit_union')}}</option>
                                        <option value="{{\App\ConstantsModel::$FINANCIAL_TYPE_POST}}"
                                                @if (array_get($request, 'bank_type') == \App\ConstantsModel::$FINANCIAL_TYPE_POST) selected @endif>{{$lan::get('post_office')}}</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="bankinfo"
                         class="@if(!$request->has('have_payment_info') || $request->invoice_type != \App\Common\Constants::$PAYMENT_TYPE['TRAN_RICOH']) display_none @endif">
                        <table id="table6">
                            <colgroup>
                                <col width="30%"/>
                                <col width="70%"/>
                            </colgroup>
                            {{--金融機関コード--}}
                            <tr>
                                <td class="t6_td1">{{$lan::get('bank_code')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="bank_code" name="bank_code"
                                           value="{{array_get($request, 'bank_code')}}" class="l_text ime_inactive"
                                           @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('bank_code')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('half_width_number_4_digit')}}
                                </td>
                            </tr>
                            {{--金融機関名--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('financial_institution_name')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="bank_name" name="bank_name"
                                           value="{{array_get($request, 'bank_name')}}" class="l_text ime_inactive"
                                           @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('financial_institution_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('single_byte_uppercase_kana_up_15_character')}}
                                </td>
                            </tr>
                            {{--支店コード--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('branch_code')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="branch_code" name="branch_code"
                                           value="{{array_get($request, 'branch_code')}}" class="l_text ime_inactive"
                                           @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('branch_code')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('half_width_number_3_digit')}}
                                </td>
                            </tr>
                            {{--支店名--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('branch_name')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="branch_name" name="branch_name"
                                           value="{{array_get($request, 'branch_name')}}" class="l_text ime_inactive"
                                           @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('branch_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('single_byte_uppercase_kana_up_15_character')}}
                                </td>
                            </tr>
                            {{--口座種別--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('classification')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <select name="bank_account_type" id="bank_account_type" form="action_form"
                                            @if($request->has('parent_id')) disabled @endif>
                                        <option value=""></option>
                                        @if(isset($bank_account_type_list))
                                            @foreach($bank_account_type_list as $key => $item)
                                                <option value="{{$key}}"
                                                        @if (array_get($request, 'bank_account_type') == $key) selected @endif>{{$item}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            {{--口座番号--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('account_number')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="bank_account_number"
                                           name="bank_account_number"
                                           value="{{array_get($request, 'bank_account_number')}}"
                                           class="m_text ime_inactive" @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('account_number')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('half_width_number_7_digit')}}
                                </td>
                            </tr>
                            {{--口座名義--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('account_holder')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="bank_account_name"
                                           name="bank_account_name" value="{{array_get($request, 'bank_account_name')}}"
                                           class="l_text ime_inactive" @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('account_holder')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('bank_account_name_kana_warning')}}
                            </tr>
                            {{--口座名義（カナ）--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('account_kana_name')}}
                                    <span class="aster">*</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="bank_account_name_kana"
                                           name="bank_account_name_kana"
                                           value="{{array_get($request, 'bank_account_name_kana')}}"
                                           class="l_text ime_inactive" @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('account_kana_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                                           {{$lan::get('single_byte_uppercase_kana_up_30_character')}}</td>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="postinfo"
                         class="@if(!$request->has('have_payment_info') || $request->invoice_type != \App\ConstantsModel::$INVOICE_BANK_PAYMENT || $request->bank_type != \App\ConstantsModel::$FINANCIAL_TYPE_POST) display_none @endif">
                        <table id="table6">
                            <colgroup>
                                <col width="30%"/>
                                <col width="70%"/>
                            </colgroup>
                            {{--通帳記号--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_symbol')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="post_account_kigou"
                                           name="post_account_kigou"
                                           value="{{array_get($request, 'post_account_kigou')}}"
                                           class="m_text ime_inactive" @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('passbook_symbol')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('half_width_number_5_digit')}}
                                </td>
                            </tr>
                            {{--通帳番号--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_number')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="post_account_number"
                                           name="post_account_number"
                                           value="{{array_get($request, 'post_account_number')}}"
                                           class="m_text ime_inactive" @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('passbook_number')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('half_width_number_8_digit')}}
                                </td>
                            </tr>
                            {{--通帳名義--}}
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_name')}}
                                    <span class="aster">&lowast;</span>
                                </td>
                                <td>
                                    <input form="action_form" type="text" id="post_account_name"
                                           name="post_account_name" value="{{array_get($request, 'post_account_name')}}"
                                           class="l_text ime_inactive" @if($request->has('parent_id')) readonly
                                           @endif placeholder="{{$lan::get('passbook_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                                    {{$lan::get('single_byte_uppercase_kana_up_30_character')}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{--割増・割引--}}
                <div id="AdjustInfo">
                    <h4>{{$lan::get('premium_discount')}}</h4>
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        {{--割増・割引項目--}}
                        <tr>
                            <td class="t6_td1">
                                {{$lan::get('premium_discount_items')}}
                            </td>
                            <td>
                                <div id="inputActive">
                                    @if($request->has('payment') && (count($request['payment']) >0))
                                        @foreach(array_get($request, 'payment') as $k =>$v)
                                            <div class="InputArea">
                                                <table class="adjust_payment_container">
                                                    <tr>
                                                        <td class="t4d2">
                                                            {{--対象月--}}
                                                            {{$lan::get('target_month')}}
                                                            <select name="payment[{{$loop->index}}][month]"
                                                                    form="action_form"
                                                                    @if($request->has('parent_id')) disabled @endif>
                                                                <option value=""></option>
                                                                @foreach ($month_list as $key =>$row)
                                                                    <option value="{{$key}}"
                                                                            @if(array_get($v, 'month') == $key) selected @endif>{{$row}}</option>
                                                                @endforeach
                                                            </select>
                                                            {{--摘要--}}
                                                            &nbsp;{{$lan::get('abstract')}}
                                                            <select form="action_form"
                                                                    name="payment[{{$loop->index}}][invoice_adjust_name_id]"
                                                                    class="payment_adjust"
                                                                    @if($request->has('parent_id')) disabled @endif>
                                                                <option value=""></option>
                                                                @foreach($invoice_adjust_list as $key => $row)
                                                                    <option value="{{array_get($row, 'id')}}"
                                                                            @if(array_get($v, 'invoice_adjust_name_id') == array_get($row, 'id')) selected @endif>{{array_get($row, 'name')}}</option>
                                                                @endforeach
                                                            </select>
                                                            {{--金額--}}
                                                            &nbsp;{{$lan::get('price')}}
                                                            <input type="text" form="action_form"
                                                                   name="payment[{{$loop->index}}][adjust_fee]"
                                                                   class="payment_fee text_right" style="width: 80px;"
                                                                   value="{{array_get($v,'adjust_fee')}}"
                                                                   @if($request->has('parent_id')) readonly @endif/>
                                                            &nbsp;{{$lan::get('circle')}}

                                                            @if(!$request->has('parent_id'))
                                                                <a class="inputDelete" href="#">
                                                                    <input type="button"
                                                                           value="{{$lan::get('delete')}}"/>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                {{--Dont show add routine payment when in edit mode--}}
                                <div style="margin: 10px 10px 17px 120px;">
                                    <input type="button" id="inputAdd" style="width: 100px"
                                           value="{{$lan::get('add_items')}}"
                                           class="@if($request->has('parent_id')) display_none @endif"/>
                                </div>

                                <div id="inputBase" style="display: none;">
                                    <table>
                                        <tr>
                                            <td class="t4d2">
                                                {{$lan::get('target_month')}}
                                                <select class="formItem NewPaymentMonth" title="payment_month"
                                                        form="action_form">
                                                    <option value=""></option>
                                                    @if(isset($month_list))
                                                        @foreach ($month_list as $key => $row)
                                                            <option value="{{$key}}">{{$row}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                &nbsp;{{$lan::get('abstract')}}
                                                <select form="action_form" class="formItem NewPaymentAdjust"
                                                        title="payment_adjust">
                                                    <option value=""></option>
                                                    @if(isset($invoice_adjust_list))
                                                        @foreach ($invoice_adjust_list as $row)
                                                            <option value="{{array_get($row,'id')}}">{{array_get($row, 'name')}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                &nbsp;{{$lan::get('price')}}
                                                <input type="text" form="action_form" class="formItem NewPaymentFee"
                                                       style="width: 80px;" value="" title="payment_fee"/>
                                                &nbsp;{{$lan::get('circle')}}
                                                <input type="hidden" class="formItem NewPaymentId" value=""
                                                       title="payment_id"/>
                                                <a class="inputDelete" href="#">
                                                    <input type="button" value="{{$lan::get('delete')}}"/>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
<br/>
                <div class="exe_button">
                    <!-- <input id="save_data" class="submit2" type="button" value="{{$lan::get('run_title')}}"/> -->
                    <button id="save_data" class="submit2" type="button"><i class="fa fa-save " style="width: 20%;"></i>登録</button>
                    <!-- <input class="submit_return" id="submit_return" type="button"
                           value="{{$lan::get('return_title')}}"/> -->
                    <button class="submit_return" type="button" id="submit_return"><i class="fa fa-arrow-circle-left " style="width: 20%;"></i>{{$lan::get('return_title')}}</button>
                </div>
            </form>
        </div>


        {{--入会情報追加ダイヤログ--}}
        <div id="contactForm1" class="display_none">
            <h4 style="text-align: left">{{$lan::get('member_title')}}</h4>
            <table id="table6">
                <colgroup>
                    <col width="30%"/>
                    <col width="70%"/>
                </colgroup>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('join_date_title')}}
                    </td>
                    <td>
                        <input class="DateInput" type="text" id="dlg_enter_date" name="enter_date" value=""
                               placeholder="{{$lan::get('join_date_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('join_memo_title')}}
                    </td>
                    <td class="t4td2">
                        <textarea id="dlg_enter_memo" class="ime_active" name="enter_memo" cols="30" rows="4"
                                  placeholder="{{$lan::get('join_memo_title')}}{{$lan::get('placeholder_input_temp')}}"></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('withdraw_date_title')}}
                    </td>
                    <td>
                        <input class="DateInput" id="dlg_resign_date" type="text" name="resign_date" value=""
                               placeholder="{{$lan::get('withdraw_date_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('withdraw_memo_title')}}
                    </td>
                    <td class="t4td2">
                        <textarea class="ime_active" id="dlg_resign_memo" name="resign_memo" cols="30" rows="4"
                                  placeholder="{{$lan::get('withdraw_memo_title')}}{{$lan::get('placeholder_input_temp')}}"></textarea>
                    </td>
                </tr>
            </table>
        </div>

        {{--住所追加ダイヤログ--}}
        <div id="contactForm2" class="display_none">
            <h4 style="text-align: left">
                {{$lan::get('member_title')}}
            </h4>
            <p id="validate_student_phone_no" style="display: none"
               class="error_message">{{$lan::get('require_home_telephone')}}</p>
            <table id="table6">
                <colgroup>
                    <col width="30%"/>
                    <col width="70%"/>
                </colgroup>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('postal_code_title')}}
                    </td>
                    <td>
                        &#12306;&nbsp;
                        <input maxlength="3" class="text_ss ime_inactive" id="dlg_student_zip_code1" style="width: 48px"
                               type="text" name="student_zip_code1" value=""
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                        &nbsp;－
                        <input maxlength="4" class="text_ss ime_inactive" id="dlg_student_zip_code2"
                               style="width: 60px;" type="text" name="student_zip_code2" value=""
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&nbsp;&nbsp;
                        <button type="button" style="color: #595959;height: 30px;" id="generateAddressDlgStu">{{$lan::get('generate_address')}}</button>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('state_name_title')}}
                    </td>
                    <td class="t4td2">
                        <select name="_pref_id" id="dlg_address_pref">
                            <option value=""></option>
                            @foreach($prefList as $key => $item)
                                <option value="{{$key}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('city_name_title')}}
                    </td>
                    <td class="t4td2">
                        <select name="_city_id" id="dlg_address_city" style="width: 200px">
                            <option value=""></option>
                            @foreach($cityListForStudent as $key => $item)
                                <option value="{{$key}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('address_number_title')}}
                    </td>
                    <td class="t4td2">
                        <input class="text_l ime_active" id="dlg_student_address" type="text" name="student_address"
                               value=""
                               placeholder="{{$lan::get('address_number_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('building_title')}}
                    </td>
                    <td class="t4td2">
                        <input class="text_l ime_active" id="dlg_student_building" type="text" name="student_building"
                               value=""
                               placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('contact_phone_number')}}
                        <span class="aster">&lowast;</span>
                    </td>
                    <td class="t4td2">
                        <input class="text_m ime_inactive" id="dlg_student_phone_no" type="text" name="student_phone_no"
                               value=""
                               placeholder="{{$lan::get('contact_phone_number')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('mobile_phone_title')}}
                    </td>
                    <td class="t4td2">
                        <input class="text_m ime_inactive" id="dlg_student_handset_no" type="text"
                               name="student_handset_no" value=""
                               placeholder="{{$lan::get('mobile_phone_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
            </table>
        </div>

        {{--請求先ダイヤログ--}}
        <div id="contactForm3" class="display_none">
            <h4 style="text-align: left">{{$lan::get('street_address')}}</h4>
            <p id="validate_address" style="display: none" class="error_message">{{$lan::get('address_empty')}}</p>
            <table id="table6">
                <colgroup>
                    <col width="30%"/>
                    <col width="70%"/>
                </colgroup>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('postal_code')}}
                    </td>
                    <td>
                        &#12306;&nbsp;
                        <input maxlength="3" class="text_ss ime_inactive" id="dlg_zip_code1" style="width: 48px"
                               type="text" name="zip_code1" value=""
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                        &nbsp;－
                        <input maxlength="4" class="text_ss ime_inactive" id="dlg_zip_code2" style="width: 60px;"
                               type="text" name="zip_code2" value=""
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&nbsp;
                        <button type="button" id="generateAddressDlg" style="color: #595959;height: 30px;">{{$lan::get('generate_address')}}</button>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('prefecture_name')}}
                    </td>
                    <td class="t4td2">
                        <select name="pref_id" id="dlg_parent_pref_id" style="width: 200px">
                            <option value=""></option>
                            @if(isset($prefList))
                                @foreach($prefList as $key => $item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('city_name')}}
                    </td>
                    <td class="t4td2">
                        <select name="city_id" id="dlg_parent_city_id" style="width: 200px">
                            <option value=""></option>
                            @foreach($cityListForParent as $key => $item)
                                <option value="{{$key}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('address_number_title')}}
                    </td>
                    <td class="t4td2">
                        <input id="dlg_address" class="text_l ime_active" type="text" name="address"
                               placeholder="{{$lan::get('address_number_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('building_title')}}
                    </td>
                    <td class="t4td2">
                        <input id="dlg_building" class="text_l ime_active" type="text" name="building"
                               placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('contact_phone_number')}}
                    </td>
                    <td class="t4td2">
                        {{--<input id="dlg_phone_no" class="text_m ime_inactive" type="text" name="phone_no" value=""--}}
                               {{--placeholder="{{$lan::get('contact_phone_number')}}{{$lan::get('placeholder_input_temp')}}"--}}
                               {{--pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>--}}
                        <input id="dlg_phone_no" class="text_m ime_inactive" type="text" name="phone_no" value=""
                               placeholder="{{$lan::get('contact_phone_number')}}{{$lan::get('placeholder_input_temp')}}"
                               pattern="\d*" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('mobile_phone')}}
                    </td>
                    <td class="t4td2">
                        <input id="dlg_handset_no" class="text_m ime_inactive" type="text" name="handset_no" value=""
                               placeholder="{{$lan::get('mobile_phone')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('memo')}}
                    </td>
                    <td class="t4td2">
                        <textarea id="dlg_memo" id="input3" name="memo" cols="30" rows="4"
                                  placeholder="{{$lan::get('memo')}}{{$lan::get('placeholder_input_temp')}}"></textarea>
                    </td>
                </tr>
            </table>
        </div>

        {{--支払方法追加ダイヤログ--}}
        <div id="contactForm4" class="display_none">
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
                        <select name="invoice_type" id="dlg_invoice_type">
                            @foreach($payment_list as $item)
                                <option value="{{array_get($item, 'payment_method_id')}}" @if (array_get($request, 'invoice_type') == array_get($item, 'payment_method_id')) selected="selected" @endif> {{$lan::get(array_get($item, 'payment_method_name'))}}</option>
                            @endforeach
                            {{--<option value="{{\App\ConstantsModel::$INVOICE_CASH_PAYMENT}}"
                                    @if (array_get($request, 'invoice_type') == \App\ConstantsModel::$INVOICE_CASH_PAYMENT) selected="selected" @endif>{{$lan::get('cash')}}</option>
                            <option value="{{\App\ConstantsModel::$INVOICE_TRANSFER_PAYMENT}}"
                                    @if (array_get($request, 'invoice_type') == \App\ConstantsModel::$INVOICE_TRANSFER_PAYMENT) selected="selected" @endif>{{$lan::get('transfer')}}</option>
                            <option value="{{\App\ConstantsModel::$INVOICE_BANK_PAYMENT}}"
                                    @if (array_get($request, 'invoice_type') == \App\ConstantsModel::$INVOICE_BANK_PAYMENT) selected="selected" @endif>{{$lan::get('account_transfer')}}</option>--}}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        <strong>{{$lan::get('notification_method')}}</strong>
                    </td>
                    <td>
                        <select name="mail_infomation" id="dlg_mail_infomation">
                            <option value="0">{{$lan::get('mailing')}}</option>
                            <option value="1" selected>{{$lan::get('email')}}</option>
                            <option value="2">{{$lan::get('notsend')}}</option>
                        </select>
                    </td>
                </tr>
            </table>

            <div id="invoice_info1" style="display:none">
                <h4>{{$lan::get('account_information')}}</h4>
                <table id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('financial_organizations')}}
                        </td>
                        <td>
                            <select name="bank_type" id="dlg_bank_type">
                                <option value="1">{{$lan::get('bank_credit_union')}}</option>
                                <option value="2">{{$lan::get('post_office')}}</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="bank_info1" style="display:none">
                <table id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('bank_code')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" name="bank_code" id="dlg_bank_code" value="" class="l_text ime_inactive"
                                   placeholder="{{$lan::get('bank_code')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('half_width_number_4_digit')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('financial_institution_name')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_bank_name" name="bank_name" value="" class="l_text ime_inactive"
                                   placeholder="{{$lan::get('financial_institution_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('single_byte_uppercase_kana_up_15_character')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('branch_code')}} <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_branch_code" name="branch_code" value=""
                                   class="l_text ime_inactive"
                                   placeholder="{{$lan::get('branch_code')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('half_width_number_3_digit')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('branch_name')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_branch_name" name="branch_name" value=""
                                   class="l_text ime_inactive"
                                   placeholder="{{$lan::get('branch_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('single_byte_uppercase_kana_up_15_character')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('classification')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <select name="bank_account_type" id="dlg_bank_account_type">
                                @if(isset($bank_account_type_list))
                                    @foreach($bank_account_type_list as $key => $item)
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('account_number')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_bank_account_number" name="bank_account_number" value=""
                                   class="m_text"
                                   placeholder="{{$lan::get('account_number')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('half_width_number_7_digit')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('account_holder')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_bank_account_name" name="bank_account_name" value=""
                                   class="l_text ime_active"
                                   placeholder="{{$lan::get('account_holder')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('bank_account_name_kana_warning')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('account_kana_name')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_bank_account_name_kana" name="bank_account_name_kana" value=""
                                   class="l_text"
                                   placeholder="{{$lan::get('account_kana_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('single_byte_uppercase_kana_up_30_character')}}
                        </td>
                    </tr>
                </table>
            </div>

            <div id="postinfo1" @if($request->invoice_type != 2) style="display:none" @endif>
                <table id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    <tr>
                        <td class="t6_td1">{{$lan::get('passbook_symbol')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_post_account_kigou" name="post_account_kigou" value=""
                                   class="m_text ime_inactive"
                                   placeholder="{{$lan::get('passbook_symbol')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('half_width_number_5_digit')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('passbook_number')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_post_account_number" name="post_account_number" value=""
                                   class="m_text ime_inactive"
                                   placeholder="{{$lan::get('passbook_number')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('half_width_number_8_digit')}}
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                            {{$lan::get('passbook_name')}}
                            <span class="aster">*</span>
                        </td>
                        <td>
                            <input type="text" id="dlg_post_account_name" name="post_account_name" value=""
                                   class="l_text ime_active"
                                   placeholder="{{$lan::get('passbook_name')}}{{$lan::get('placeholder_input_temp')}}"/>
                            {{$lan::get('single_byte_uppercase_kana_up_30_character')}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{--確認ダイヤログ--}}
        <div id="confirm_save_dialog" class="no_title" style="display:none;">
            {{$lan::get('save_confirm')}}
        </div>

        {{--Warning when unactive student--}}
        <div id="warning_unactive_student" class="no_title" style="display:none;">
            請求が済んでいないので、削除できません。
        </div>

        <div id="parent_dialog" class="display_none">
            <table class="table1">
                <thead>
                <tr>
                    <th class="text_left">{{$lan::get('parent_name')}}</th>
                    <th class="text_left">{{$lan::get('parent_mail')}}</th>
                    <th class="text_left"></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div id="person_in_charge_clone" class="display_none">
            <div class="person_in_charge">
                <p class="drop_down" data-toggle="collapse" href="#collapse_person_*" style="width: 90%;">{{$lan::get('person_in_charge_info_title')}}*<i class="fa fa-chevron-down"></i></p>
                <p style="float:right;"><button type="button" onclick="removePersonInChargeRow(this); return false;" >{{$lan::get('delete_title')}}</button></p>
                <div id="collapse_person_*" class="panel-collapse collapse">
                    <table class="dialog-table" id="table6">
                        <colgroup>
                            <col width="29%"/>
                            <col width="81%"/>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td>{{$lan::get('person_in_charge_name_title')}}</td>
                                <td><input class="text_m" type="text" name="person_in_charge[*][person_name]"/></td>
                            </tr>
                            <tr>
                                <td>{{$lan::get('furigana_title')}}</td>
                                <td><input class="text_m" type="text" name="person_in_charge[*][person_name_kana]"/></td>
                            </tr>
                            <tr>
                                <td>{{$lan::get('position_title')}}</td>
                                <td><input class="text_m" type="text" name="person_in_charge[*][person_position]"/></td>
                            </tr>
                            <tr>
                                <td>{{$lan::get('office_name_title')}}</td>
                                <td><input class="text_m" type="text" name="person_in_charge[*][person_office_name]"/></td>
                            </tr>
                            <tr>
                                <td>{{$lan::get('office_tel_title')}}</td>
                                <td><input class="text_m" type="text" name="person_in_charge[*][person_office_tel]"/></td>
                            </tr>
                            <tr>
                                <td>{{$lan::get('person_in_charge_mail_title')}}</td>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="padding-left: 0px;"><input class="text_m" type="text" name="person_in_charge[*][person_email]"/></td>
                                            <td><input type="checkbox" name="person_in_charge[*][check_send_mail_flag]"></td>
                                            <td>メール受信</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var labels = {
                ok: "{{$lan::get('Submit')}}",
                cancel: "{{$lan::get('Clear')}}",
                confirm: "{{$lan::get('run_title')}}",
                main_title: "{{$lan::get('main_title')}}",
                select: "{{$lan::get('select')}}",
                parent_dialog_title: "{{$lan::get('billing_selection_title')}}",
                student_join_dialog_title: "{{$lan::get('Student1')}}",
                student_address_dialog_title: "{{$lan::get('Student2')}}",
                parent_address_dialog_title: "{{$lan::get('Student3')}}",
                payment_dialog_title: "{{$lan::get('Student4')}}"

            };
            var constant = {
                INVOICE_CASH_PAYMENT: "{{\App\Common\Constants::$PAYMENT_TYPE['CASH']}}",
                INVOICE_TRAN_RICOH: "{{\App\Common\Constants::$PAYMENT_TYPE['TRAN_RICOH']}}",
                INVOICE_CONV_RICOH: "{{\App\Common\Constants::$PAYMENT_TYPE['CONV_RICOH']}}",
                INVOICE_POST_RICOH: "{{\App\Common\Constants::$PAYMENT_TYPE['POST_RICOH']}}",
                INVOICE_CRED_ZEUS: "{{\App\Common\Constants::$PAYMENT_TYPE['CRED_ZEUS']}}",
                INVOICE_TRAN_BANK: "{{\App\Common\Constants::$PAYMENT_TYPE['TRAN_BANK']}}",

                NOTICE_BY_POST: "{{\App\ConstantsModel::$NOTICE_BY_POST}}",
                NOTICE_BY_MAIL: "{{\App\ConstantsModel::$NOTICE_BY_MAIL}}",
                FINANCIAL_TYPE_BANK: "{{\App\ConstantsModel::$FINANCIAL_TYPE_BANK}}",
                FINANCIAL_TYPE_POST: "{{\App\ConstantsModel::$FINANCIAL_TYPE_POST}}",
                MEMBER_CATEGORY_PERSONAL: "{{\App\ConstantsModel::$MEMBER_CATEGORY_PERSONAL}}",
                MEMBER_CATEGORY_CORP: "{{\App\ConstantsModel::$MEMBER_CATEGORY_CORP}}",
            };
            var originalStudentImage = $("#student_avatar").attr('src');
            var nowInputIndex = parseInt("@if($request['payment']) {{count($request->payment)}} @else 0 @endif");
            var nowInputStudent = parseInt("@if($request['payment_student']) {{count($request->payment_student)}} @else 0 @endif");
            var yearStart = new Date().getFullYear();
            yearStart -= "{{\App\ConstantsModel::$MEMBER_BIRTH_DAY_FROM_YEAR_RANGE}}";

            $.datetimepicker.setLocale('ja');
            $('.DateInput').datetimepicker({
                format: 'Y-m-d',
                step: 5,
                timepicker: false,
                scrollMonth: false,
                scrollInput: false
            });

            //set pref and city button
            $("#generateAddressOther").click(function (){
                var zipcode = $("input[name=zip_code1_other]").val()+$("input[name=zip_code2_other]").val();
                var _token = "{{csrf_token()}}";

                $.ajax({
                    type: "post",
                    url: "/school/parent/get_address_from_zipcode",
                    data: {zipcode: zipcode,_token: _token},
                    dataType:'json',
                    success: function(data) {
                        if(data.status == true){
                            var info = data.message;
                            $("#address_pref_other").val(info.pref_id);
                            setCityOther(info.pref_id, info.city_id);
                            $("input[name=student_address_other]").val(info.address);
                        }
                    }
                });
            });
            function setCityOther(pref_id, city_id){
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: "/school/ajaxSchool/city",
                    data: {pref_cd: pref_id},
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        data = JSON.stringify(data);
                        data = JSON.parse(data);

                        var result = data['city_list'];
                        var html = "<option value=''></option>";
                        for (x in result) {
                            var html = html + "<option value=" + x + ">" + result[x] + "</option>";
                        }

                        $('#address_city_other').html(html);
                        $("#address_city_other").val(city_id);

                    },
                    error: function (data) {

                    },
                });
            }
            $("#generateAddress").click(function (){
                var zipcode = $("input[name=student_zip_code1]").val()+$("input[name=student_zip_code2]").val();
                var _token = "{{csrf_token()}}";

                $.ajax({
                    type: "post",
                    url: "/school/parent/get_address_from_zipcode",
                    data: {zipcode: zipcode,_token: _token},
                    dataType:'json',
                    success: function(data) {
                        if(data.status == true){
                            var info = data.message;
                            $("#_pref_id1").val(info.pref_id);
                            setCity(info.pref_id, info.city_id);
                            $("#student_address1").val(info.address);
                        }
                    }
                });
            });
            function setCity(pref_id, city_id){
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: "/school/ajaxSchool/city",
                    data: {pref_cd: pref_id},
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        data = JSON.stringify(data);
                        data = JSON.parse(data);

                        var result = data['city_list'];
                        var html = "<option value=''></option>";
                        for (x in result) {
                            var html = html + "<option value=" + x + ">" + result[x] + "</option>";
                        }

                        $('#_city_id1').html(html);
                        $("#_city_id1").val(city_id);

                    },
                    error: function (data) {

                    },
                });
            }
            $("#generateAddressDlg").click(function (){
                var zipcode = $("#dlg_zip_code1").val()+$("#dlg_zip_code2").val();
                var _token = "{{csrf_token()}}";

                $.ajax({
                    type: "post",
                    url: "/school/parent/get_address_from_zipcode",
                    data: {zipcode: zipcode,_token: _token},
                    dataType:'json',
                    success: function(data) {
                        if(data.status == true){
                            var info = data.message;
                            $("#dlg_parent_pref_id").val(info.pref_id);
                            setCityDlg(info.pref_id, info.city_id);
                            $("#dlg_address").val(info.address);
                        }
                    }
                });
            });
            function setCityDlg(pref_id, city_id){
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: "/school/ajaxSchool/city",
                    data: {pref_cd: pref_id},
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        data = JSON.stringify(data);
                        data = JSON.parse(data);

                        var result = data['city_list'];
                        var html = "<option value=''></option>";
                        for (x in result) {
                            var html = html + "<option value=" + x + ">" + result[x] + "</option>";
                        }

                        $('#dlg_parent_city_id').html(html);
                        $("#dlg_parent_city_id").val(city_id);

                    },
                    error: function (data) {

                    },
                });
            }
            //
            $("#generateAddress2").click(function (){
                var zipcode = $("#zip_code1").val()+$("#zip_code2").val();
                var _token = "{{csrf_token()}}";

                $.ajax({
                    type: "post",
                    url: "/school/parent/get_address_from_zipcode",
                    data: {zipcode: zipcode,_token: _token},
                    dataType:'json',
                    success: function(data) {
                        if(data.status == true){
                            var info = data.message;
                            $("#pref_id").val(info.pref_id);
                            setCity2(info.pref_id, info.city_id);
                            $("#address").val(info.address);
                        }
                    }
                });
            });
            function setCity2(pref_id, city_id){
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: "/school/ajaxSchool/city",
                    data: {pref_cd: pref_id},
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        data = JSON.stringify(data);
                        data = JSON.parse(data);

                        var result = data['city_list'];
                        var html = "<option value=''></option>";
                        for (x in result) {
                            var html = html + "<option value=" + x + ">" + result[x] + "</option>";
                        }

                        $('#city_id').html(html);
                        $("#city_id").val(city_id);

                    },
                    error: function (data) {

                    },
                });
            }
            //
            $("#generateAddressDlgStu").click(function (){
                var zipcode = $("#dlg_student_zip_code1").val()+$("#dlg_student_zip_code2").val();
                var _token = "{{csrf_token()}}";

                $.ajax({
                    type: "post",
                    url: "/school/parent/get_address_from_zipcode",
                    data: {zipcode: zipcode,_token: _token},
                    dataType:'json',
                    success: function(data) {
                        if(data.status == true){
                            var info = data.message;
                            $("#dlg_address_pref").val(info.pref_id);
                            setCityDlgStu(info.pref_id, info.city_id);
                            $("#dlg_student_address").val(info.address);
                        }
                    }
                });
            });
            function setCityDlgStu(pref_id, city_id){
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: "/school/ajaxSchool/city",
                    data: {pref_cd: pref_id},
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        data = JSON.stringify(data);
                        data = JSON.parse(data);

                        var result = data['city_list'];
                        var html = "<option value=''></option>";
                        for (x in result) {
                            var html = html + "<option value=" + x + ">" + result[x] + "</option>";
                        }

                        $('#dlg_address_city').html(html);
                        $("#dlg_address_city").val(city_id);

                    },
                    error: function (data) {

                    },
                });
            }

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

            //Add new person in charge of corporation
            $('#addPersonInCharge').click(function () {
                $new_person_in_charge = $('#person_in_charge_clone').html();
                $number_of_person = parseInt($('#total_person_in_charge').val()) + 1;
                $('#total_person_in_charge').val($number_of_person);
                $new_person_in_charge = $new_person_in_charge.replace(/\*/g, $number_of_person);
                $( "#person_in_charge" ).append( $new_person_in_charge );
            });

            //Open dialog for 支払方法追加
            $('#contact4').click(function () {
                $('#contactForm4').dialog('open');
            });

            $('#birthday').datetimepicker({
                format: 'Y-m-d',
                yearStart: yearStart,
                timepicker: false,
                scrollMonth: false,
                scrollInput: false
            });

            //Remove jquery ui auto focus on first text field
            $.ui.dialog.prototype._focusTabbable = function () {
            };

            //入会情報ダイヤログの処理
            $('#contactForm1').dialog({
                autoOpen: false,
                modal: true,
                width: 450,
                dialogClass: 'fixed_dialog',
                title: labels.student_join_dialog_title,
                buttons: [
                    {
                        text: labels.ok, // OK
                        click: function () {
                            var enter_date = $('#dlg_enter_date').val();
                            var enter_memo = $('#dlg_enter_memo').val();
                            var resign_date = $('#dlg_resign_date').val();
                            var resign_memo = $('#dlg_resign_memo').val();
                            $("#enter_date1").val(enter_date);
                            $('#enter_date').fadeIn();
                            $("#enter_memo1").val(enter_memo);
                            $('#enter_memo').fadeIn();
                            $("#resign_date1").val(resign_date);
                            $('#resign_date').fadeIn();
                            $("#resign_memo1").val(resign_memo);
                            $('#resign_memo').fadeIn();
                            $('#contact1').hide();
                            $("#have_student_join_info").val(1);
                            $(this).dialog('close');
                        }
                    },
                    {
                        text: labels.cancel, // Cancel
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });

            //住所ダイヤログの処理
            $('#contactForm2').dialog({
                autoOpen: false,
                modal: true,
                width: 550,
                dialogClass: 'fixed_dialog',
                title: labels.student_address_dialog_title,
                buttons: [
                    {
                        text: labels.ok, // OK
                        click: function () {
                            var student_zip_code1 = $('#dlg_student_zip_code1').val();
                            var student_zip_code2 = $('#dlg_student_zip_code2').val();
                            var _pref_id = $('#dlg_address_pref').val();
                            var _city_id = $('#dlg_address_city').val();
                            var cityOptionDom = $("#dlg_address_city").html();
                            var student_address = $('#dlg_student_address').val();
                            var student_building = $('#dlg_student_building').val();
                            var student_phone_no = $('#dlg_student_phone_no').val();
                            var student_handset_no = $('#dlg_student_handset_no').val();
                            if (student_phone_no != "") {
                                $("#student_zip_code1").val(student_zip_code1);
                                $("#student_zip_code2").val(student_zip_code2);
                                $('#student_zip_code').fadeIn();
                                $("#_pref_id1").val(_pref_id);
                                $('#_pref_id').fadeIn();
                                $("#_city_id1").html(cityOptionDom).val(_city_id);
                                $('#_city_id').fadeIn();
                                $("#student_address1").val(student_address);
                                $("#student_building1").val(student_building);
                                $('#student_address').fadeIn();
                                $('#student_building').fadeIn();
                                $("#student_phone_no1").val(student_phone_no);
                                $("#validate_phone_no").val(1);
                                $('#student_phone_no').fadeIn();
                                $("#student_handset_no1").val(student_handset_no);
                                $('#student_handset_no').fadeIn();
                                $("#validate_student_phone_no").hide();
                                $('#contact2').hide();
                                $("#btn_copy_student_address").show();
                                $("#have_student_address_info").val(1);
                                $(this).dialog('close');
                            } else {
                                $("#validate_student_phone_no").show();
                            }
                        }
                    },
                    {
                        text: labels.cancel, // Cancel
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });

            //請求先住所ダイヤログの処理
            $('#contactForm3').dialog({
                autoOpen: false,
                modal: true,
                width: 550,
                dialogClass: 'fixed_dialog',
                title: labels.parent_address_dialog_title,
                buttons: [
                    {
                        text: labels.ok, // OK
                        click: function () {
                            var zip_code1 = $('#dlg_zip_code1').val();
                            var zip_code2 = $('#dlg_zip_code2').val();
                            var pref_id = $('#dlg_parent_pref_id').val();
                            var city_id = $('#dlg_parent_city_id').val();
                            var cityOptionDom = $('#dlg_parent_city_id').html();
                            var address = $('#dlg_address').val();
                            var building = $('#dlg_building').val();
                            var phone_no = $('#dlg_phone_no').val();
                            var handset_no = $('#dlg_handset_no').val();
                            var memo = $('#dlg_memo').val();
                            $("#zip_code1").val(zip_code1);
                            $("#zip_code2").val(zip_code2);
                            $("#pref_id").val(pref_id);
                            $("#city_id").html(cityOptionDom).val(city_id);
                            $("#address").val(address);
                            $("#building").val(building);
                            $("#validate_address_build").val(1);
                            $("#phone_no").val(phone_no);
                            $("#handset_no").val(handset_no);
                            $("#memo").val(memo);
                            $('#street_address').fadeIn();
                            $("#validate_address").hide();
                            $('#contact3').hide();
                            $(this).dialog('close');
                            $("#have_parent_address_info").val(1);
                        }
                    },
                    {
                        text: labels.cancel, // Cancel
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });

            //支払ダイヤログの処理
            $('#contactForm4').dialog({
                autoOpen: false,
                modal: true,
                width: 1000,
                minHeight: 500,
                maxHeight: 600,
                dialogClass: 'fixed_dialog',
                title: labels.payment_dialog_title,
                buttons: [
                    {
                        text: labels.ok, // OK
                        click: function () {
                            var invoice_type = $('#dlg_invoice_type').val();
                            var mail_infomation = $('#dlg_mail_infomation').val();
                            var bank_type = $('#dlg_bank_type').val();
                            var bank_code = $('#dlg_bank_code').val();
                            var bank_name = $('#dlg_bank_name').val();
                            var branch_code = $('#dlg_branch_code').val();
                            var branch_name = $('#dlg_branch_name').val();
                            var bank_account_type = $('#dlg_bank_account_type').val();
                            var bank_account_number = $('#dlg_bank_account_number').val();
                            var bank_account_name = $('#dlg_bank_account_name').val();
                            var bank_account_name_kana = $('#dlg_bank_account_name_kana').val();
                            var post_account_kigou = $('#dlg_post_account_kigou').val();
                            var post_account_number = $('#dlg_post_account_number').val();
                            var post_account_name = $('#dlg_post_account_name').val();

                            $("#invoicetype").val(invoice_type);
                            $("#mailinfo").val(mail_infomation);
                            $("#banktype").val(bank_type);
                            $("#bank_code").val(bank_code);
                            $("#bank_name").val(bank_name);
                            $("#branch_code").val(branch_code);
                            $("#branch_name").val(branch_name);
                            $("#bank_account_type").val(bank_account_type);
                            $("#bank_account_number").val(bank_account_number);
                            $("#bank_account_name").val(bank_account_name);
                            $("#bank_account_name_kana").val(bank_account_name_kana);
                            $("#post_account_kigou").val(post_account_kigou);
                            $("#post_account_number").val(post_account_number);
                            $("#post_account_name").val(post_account_name);
                            $('#contact4').hide();
                            $("#have_payment_info").val(1);
                            fadeInOutParentBankAccountDom(invoice_type, bank_type);
                            $(this).dialog('close');
                        }
                    },
                    {
                        text: labels.cancel, // Cancel
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });

            //Open dialog for 入会情報
            $('#contact1').click(function () {
                $('#contactForm1').dialog('open');
            });

            //Open dialog for 住所追加
            $('#contact2').click(function () {
                $('#contactForm2').dialog('open');
            });

            //Open dialog for 請求先住所追加
            $('#contact3').click(function () {
                $('#contactForm3').dialog('open');
            });

            //Open dialog for 支払方法追加
            $('#contact4').click(function () {
                $('#contactForm4').dialog('open');
            });

            //Bind auto set value for parent pass when type student pass
            $(document).on('keyup', '#student_pass', function () {
                if ($('#parent_name').prop('readonly')) {
                    return;
                }
                $('#parent_pass').val($(this).val());
            });

            //Bind auto set value for parent name when type student name
            $(document).on('keyup', '#student_name', function () {
                if ($('#parent_name').prop('readonly')) {
                    return;
                }
                $('#parent_name').val($(this).val());
            });

            //Bind auto set value for parent name kana when type student name kana
            $(document).on('keyup', '#student_name_kana', function () {
                if ($('#name_kana').prop('readonly')) {
                    return;
                }
                $('#name_kana').val($(this).val());
            });

            //Bind auto set value for parent mail address kana when type student mail address
            $(document).on('keyup', '#mailaddress', function () {
                if ($('#parent_mailaddress1').prop('readonly')) {
                    return;
                }
                $('#parent_mailaddress1').val($(this).val());
            });

            /**
             * Set city option by prefecture code
             * @param prefectureCode
             * @param elem - Jquery element of city
             * @param defaultCityId - Default value
             */
            var setCityByPrefectureCode = function (prefectureCode, elem, defaultCityId) {
                if (prefectureCode == "") {
                    elem.html('<option></option>');
                    return;
                }
                $.ajax({
                    type: "get",
                    url: "/school/ajaxSchool/city",
                    data: {pref_cd: prefectureCode},
                    success: function (data) {
                        data = JSON.parse(data);
                        var html = "<option value=''></option>";
                        $.each(data.city_list, function (index, val) {
                            html += '<option value="' + index + '">' + val + '</option>';
                        });
                        elem.html(html);
                        //Set default value
                        if (defaultCityId) {
                            elem.val(defaultCityId);
                        }
                    }
                });
            };

            //Change student prefecture in dialog to set city
            $("#dlg_address_pref").change(function () {
                var prefectureCode = $(this).val();
                setCityByPrefectureCode(prefectureCode, $('#dlg_address_city'));
            });

            //Change student prefecture on main page to set city
            $("#_pref_id1").change(function () {
                var prefectureCode = $(this).val();
                setCityByPrefectureCode(prefectureCode, $('#_city_id1'));
            });

            //Change parent prefecture in dialog to set city
            $("#dlg_parent_pref_id").change(function () {
                var prefectureCode = $(this).val();
                setCityByPrefectureCode(prefectureCode, $('#dlg_parent_city_id'));
            });

            //Change student_other prefecture on main page to set city
            $("#address_pref_other").change(function () {
                var prefectureCode = $(this).val();
                setCityByPrefectureCode(prefectureCode, $('#address_city_other'));
            });

            //Change parent prefecture on main page to set city
            $("#pref_id").change(function () {
                var prefectureCode = $(this).val();
                setCityByPrefectureCode(prefectureCode, $('#city_id'));
            });

            $('#student_state').change(function() {
                var student_state = $(this).val();
                var tomorrow = moment().add(1, 'days').format('YYYY-MM-DD');
                $('#resign_date1').datetimepicker('destroy');
                if (student_state == 1) { // 契約中 mindate: tommorow
                    if ( $('#resign_date1').val() < tomorrow ) {
                        $('#resign_date1').val('');
                    }
                    $('#resign_date1').datetimepicker({
                        format: 'Y-m-d',
                        step: 5,
                        timepicker: false,
                        scrollMonth: false,
                        scrollInput: false,
                        minDate: tomorrow
                    });
                } else { //　契約終了
                    checkCanUnactiveStudent();
                    $('#resign_date1').datetimepicker({
                        format: 'Y-m-d',
                        step: 5,
                        timepicker: false,
                        scrollMonth: false,
                        scrollInput: false,
                    });
                }
            });
            $('#student_state').change();

            //Setting dialog for save
            $("#confirm_save_dialog").dialog({
                title: labels.main_title,
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: [
                    {
                        text: labels.confirm,
                        click: function() {
                            //Find all disabled elem to clone data before submit because disabled elem will dont be sent to server
                            var disabledElem = $('#action_form').find('select:disabled');
                            var dom = "";
                            $.each(disabledElem, function(index, elem) {
                                dom += '<input type="hidden" name="'+ $(elem).attr('name') +'" value="'+ $(elem).val() +'">'
                            });
                            $("#action_form").append(dom);
                            $("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
                            $("#action_form").submit();
                            $(this).dialog( "close" );
                            return false;
                        }
                    },
                    {
                        text: labels.cancel,
                        click: function() {
                            $(this).dialog('close');
                        }
                    }
                ]
            });

            //Click save button
            $("#save_data").click(function () {
                var state = $("#student_state").val();
                var resign_date = $("#resign_date1").val();
                if(state == 9 || resign_date){
                    var check = checkCanUnactiveStudent();
                    if(!check){
                        return;
                    }
                }
                $("#confirm_save_dialog").dialog('open');
            });

            //Setting dialog for select parent
            $("#parent_dialog").dialog({
                width: 600,
                height: 500,
                title: labels.parent_dialog_title,
                autoOpen: false,
                dialogClass: "fixed_dialog",
                resizable: false,
                modal: true,
                buttons: [
                    {
                        text: labels.cancel,
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });

            //Setting dialog for warning unactive student
            $("#warning_unactive_student").dialog({
                width: 500,
                height: 150,
                title: labels.parent_dialog_title,
                autoOpen: false,
                dialogClass: "fixed_dialog",
                resizable: false,
                modal: true,
                buttons: [
                    {
                        text: labels.ok,
                        click: function () {
                            $(this).dialog('close');
                            return false;
                        }
                    }
                ]
            });

            //Show list parent
            $("#btn_add_parent").click(function () {
                $.ajax({
                    url: '/school/student/ajax_list_parent',
                    success: function (data) {
                        if (data.status) {
                            var dom = '';
                            var info;
                            $.each(data.message, function (index, val) {
                                info = {
                                    id: val.id,
//								parent_name: val.parent_name,
//								name_kana: val.name_kana,
//								parent_mailaddress1: val.parent_mailaddress1,
//								parent_mailaddress2: val.parent_mailaddress2,
//								login_pw: val.login_pw,
//								zip_code1: val.zip_code1,
//								zip_code2: val.zip_code2,
//								pref_id: val.pref_id,
//								city_id: val.city_id,
//								address: val.address,
//								phone_no: val.phone_no,
//								handset_no: val.handset_no,
//								memo: val.memo
                                };
                                dom += "<tr>" +
                                    "<td class='text_left'>" + val.parent_name + "</td>" +
                                    "<td class='text_left'>" + val.parent_mailaddress1 + "</td>" +
                                    "<td><input type='button' class='btn_select_parent' " + createDataAttrDom(info) + " value='" + labels.select + "'></td>" +
                                    "</tr>";
                                $("#parent_dialog table tbody").html(dom);
                                $("#parent_dialog").dialog("open");
                            })
                        }
                    }
                })
            });
            var fadeInOutParentBankAccountDom = function (invoiceType, bankType) {
                invoiceType = invoiceType.toString();
                $('#payment').fadeIn();
                switch (invoiceType) {
                    case constant.INVOICE_TRAN_RICOH:

                        $("#mailinfo").val("0");
                        $("#invoiceinfo").show();
                        $("#postinfo").hide();
                        $("#bankinfo").show();
                        if (bankType == constant.FINANCIAL_TYPE_BANK) {
                            $("#postinfo").hide();
                            $("#bankinfo").show();
                        } else {
                            $("#bankinfo").hide();
                            $("#postinfo").show();
                        }
                        break;
                    default:

                        $("#mailinfo").val("1");
                        $("#invoiceinfo").hide();
                        $("#bankinfo").hide();
                        $("#postinfo").hide();
                        break;
                }
            };

            //Select parent from list
            $(document).on("click", ".btn_select_parent", function () {
                var id = $(this).data("id");
                //Clear all field of adjust payment
                $(".adjust_payment_container").remove();
                //Hide some button
                $("#contact4").hide();
                $.ajax({
                    url: '/school/student/ajax_get_parent',
                    data: {id: id},
                    success: function (data) {
                        if (data.status) {
                            $("#have_parent_address_info").val(1);
                            $("#parent_id").val(id);
                            $("#parent_name").val(data.parent.parent_name).prop("readonly", true);
                            $("#name_kana").val(data.parent.name_kana).prop("readonly", true);
                            $("#parent_mailaddress1").val(data.parent.parent_mailaddress1).prop("readonly", true);
                            $("#parent_mailaddress2").val(data.parent.parent_mailaddress2).prop("readonly", true);
                            $("#parent_pass").val(data.parent.login_pw).prop("readonly", true).parent().parent().hide();
                            $("#zip_code1").val(data.parent.zip_code1).prop("readonly", true);
                            $("#zip_code2").val(data.parent.zip_code2).prop("readonly", true);
                            $("#pref_id").val(data.parent.pref_id).prop("disabled", true);
                            $("#address").val(data.parent.address).prop("readonly", true);
                            $("#building").val(data.parent.building).prop("readonly", true);
                            $("#phone_no").val(data.parent.phone_no).prop("readonly", true);
                            $("#handset_no").val(data.parent.handset_no).prop("readonly", true);
                            $("#memo").val(data.parent.memo).prop("readonly", true);
                            $("#city_id").prop("disabled", true);
                            $("#invoicetype").val(data.parent.invoice_type).prop("disabled", true);
                            $("#mailinfo").val(data.parent.mail_infomation).prop("disabled", true);


                            setCityByPrefectureCode(data.parent.pref_id, $("#city_id"), data.parent.city_id);
                            if (data.bank_account) {
                                $("#banktype").val(data.bank_account.bank_type).prop("disabled", true);
                                $("#bank_code").val(data.bank_account.bank_code).prop("readonly", true);
                                $("#bank_name").val(data.bank_account.bank_name).prop("readonly", true);
                                $("#branch_code").val(data.bank_account.branch_code).prop("readonly", true);
                                $("#branch_name").val(data.bank_account.branch_name).prop("readonly", true);
                                $("#bank_account_type").val(data.bank_account.bank_account_type).prop("disabled", true);
                                $("#bank_account_number").val(data.bank_account.bank_account_number).prop("readonly", true);
                                $("#bank_account_name").val(data.bank_account.bank_account_name).prop("readonly", true);
                                $("#bank_account_name_kana").val(data.bank_account.bank_account_name_kana).prop("readonly", true);
                                $("#post_account_kigou").val(data.bank_account.post_account_kigou).prop("readonly", true);
                                $("#post_account_number").val(data.bank_account.post_account_number).prop("readonly", true);
                                $("#post_account_name").val(data.bank_account.post_account_name).prop("readonly", true);
                                fadeInOutParentBankAccountDom(data.parent.invoice_type, data.bank_account.bank_type);
                            }
                            if (data.routine_payment) {
                                $.each(data.routine_payment, function (index, val) {
                                    createRoutinePaymentDom(val.month, val.invoice_adjust_name_id, val.adjust_fee, true);
                                });
                                $("#inputAdd").hide();
                            }

                        }
                    }
                });

                // Show field
                $("#street_address").fadeIn();
                $("#contact3").hide();
                $("#btn_reset_parent").show();
                $("#btn_copy_student_address").hide();
                $("#parent_dialog").dialog("close");
            });

            //Reset parent info
            $("#btn_reset_parent").click(function () {
                $("#parent_id").val("");
                $("#parent_name").val("").prop("readonly", false);
                $("#name_kana").val("").prop("readonly", false);
                $("#parent_mailaddress1").val("").prop("readonly", false);
                $("#parent_mailaddress2").val("").prop("readonly", false);
                $("#parent_pass").val("").prop("readonly", false).parent().parent().show();
                $("#zip_code1").val("").prop("readonly", false);
                $("#zip_code2").val("").prop("readonly", false);
                $("#pref_id").val("").prop("disabled", false);
                $("#address").val("").prop("readonly", false);
                $("#building").val("").prop("readonly", false);
                $("#phone_no").val("").prop("readonly", false);
                $("#handset_no").val("").prop("readonly", false);
                $("#memo").val("").prop("readonly", false);
                $("#city_id").html("").prop("disabled", false);
                //Only show copy button when user clicked on address info button
                if ($("#have_student_address_info").val() == 1) {
                    $("#btn_copy_student_address").show();
                }
                $("#btn_add_parent").show();
                //Bank
                $("#invoicetype").val(constant.INVOICE_CASH_PAYMENT).prop("disabled", false);
                $("#mailinfo").val(constant.NOTICE_BY_MAIL).prop("disabled", false);
                $("#banktype").val(constant.FINANCIAL_TYPE_BANK).prop("disabled", false);
                $("#bank_code").val("").prop("readonly", false);
                $("#bank_name").val("").prop("readonly", false);
                $("#branch_code").val("").prop("readonly", false);
                $("#branch_name").val("").prop("readonly", false);
                $("#bank_account_type").val("").prop("disabled", false);
                $("#bank_account_number").val("").prop("readonly", false);
                $("#bank_account_name").val("").prop("readonly", false);
                $("#bank_account_name_kana").val("").prop("readonly", false);
                $("#post_account_kigou").val("").prop("readonly", false);
                $("#post_account_number").val("").prop("readonly", false);
                $("#post_account_name").val("").prop("readonly", false);
                fadeInOutParentBankAccountDom(constant.INVOICE_CASH_PAYMENT, constant.FINANCIAL_TYPE_BANK);
                //Adjust payment
                $("#inputAdd").show();
                $(".adjust_payment_container").remove();
                $(this).hide();
            });

            //Copy student address to parent
            $("#btn_copy_student_address").click(function () {
                var zipCode1 = $("#student_zip_code1").val();
                var zipCode2 = $("#student_zip_code2").val();
                var prefId = $("#_pref_id1").val();
                var cityId = $("#_city_id1").val();
                var cityOptionDom = $("#_city_id1").html();
                var address = $("#student_address1").val();
                var building = $("#student_building1").val();
                var phoneNo = $("#student_phone_no1").val();
                var handsetNo = $("#student_handset_no1").val();

                $("#zip_code1").val(zipCode1);
                $("#zip_code2").val(zipCode2);
                $("#pref_id").val(prefId);
                $("#city_id").html(cityOptionDom).val(cityId);
                $("#address").val(address);
                $("#building").val(building);
                $("#phone_no").val(phoneNo);
                $("#handset_no").val(handsetNo);
                // Show field
                $("#street_address").fadeIn();
            });

            //Show or hide total member when change student category
            $("input[name=student_category][type=radio]").change(function(e){

                e.preventDefault();
                var type = $(this).val();

                // get m_student_type base on kojin
                var _token = "{{csrf_token()}}";
                $.ajax({
                    type: "post",
                    url: "/school/student/getStudentType",
                    data: {type: type,_token: _token},
                    dataType:'json',
                    success: function(data) {
                        if(data.status == true){
                            var types = data.message;
                            var html = "";
                            types.forEach(function(type, index){
                                html+="<option value='"+type.id+"'>"+type.name+"</option> ";
                            })
                            $("select[name=m_student_type_id]").html("");
                            $("select[name=m_student_type_id]").html(html);
                        }
                    }
                });

                //

                $("#total_member").val("");
                if (type == constant.MEMBER_CATEGORY_PERSONAL) {
                    $("#total_member_container").fadeOut();
                    $("#corporation_info_container").fadeOut();
                    $("#birthday_container").fadeIn();
                    $("#sex_container").fadeIn();
                } else {
                    $("#total_member_container").fadeIn();
                    $("#corporation_info_container").fadeIn();
                    $("#birthday_container").fadeOut();
                    $("#sex_container").fadeOut();
                }
            });

            //Show selected student image recently when select a file
            $("#student_img").change(function () {
                var file = $(this).prop('files')[0];
                var studentAvatar = $("#student_avatar");
                var fileReader = new FileReader();

                if (!file.type.match('image.*')) {
                    studentAvatar.attr('src', originalStudentImage);
                } else {
                    fileReader.onload = function () {
                        studentAvatar.attr('src', fileReader.result);
                    };
                    fileReader.readAsDataURL(file);
                }
            });

            $("#invoicetype").change(function () {
                var invoiceType = $(this).val();
                var bankType = $("#banktype").val();
                fadeInOutParentBankAccountDom(invoiceType, bankType)
            });

            $("#banktype").change(function () {
                var bankType = $(this).val();
                var invoiceType = $("#invoicetype").val();
                fadeInOutParentBankAccountDom(invoiceType, bankType)
            });

            //Set bank payment when chane invoice type in dialog
            $("#dlg_invoice_type").change(function () {
                var type = $("#dlg_invoice_type").val();
//                if (type == constant.INVOICE_CASH_PAYMENT || type == constant.INVOICE_TRAN_BANK) {
                if (type != constant.INVOICE_TRAN_RICOH) {
                    $("#dlg_mail_infomation").val("1");
                    $("#invoice_info1").hide();
                    $("#bank_info1").hide();
                    $("#postinfo1").hide();
                } else {
                    $("#dlg_mail_infomation").val("0");
                    $("#invoice_info1").show();
                    $("#bank_info1").show();
                }
            });

            //Set bank info when change bank type in dialog
            $("#dlg_bank_type").change(function () {
                var type = $(this).val();
                if (type == constant.FINANCIAL_TYPE_BANK) {
                    $("#postinfo1").hide();
                    $("#bank_info1").show();
                } else {
                    $("#bank_info1").hide();
                    $("#postinfo1").show();
                }
            });

            var createRoutinePaymentDom = function (month, invoiceAdjustNameId, adjustFee, disable) {
                var newTable = $('#inputBase table').clone();
                newTable.addClass('adjust_payment_container');
                newTable.find("select.NewPaymentMonth").attr('name', 'payment[' + nowInputIndex + '][month]').removeClass('NewPaymentMonth').val(month);
                newTable.find("select.NewPaymentAdjust").attr('name', 'payment[' + nowInputIndex + '][invoice_adjust_name_id]').removeClass('NewPaymentAdjust').addClass('payment_adjust').val(invoiceAdjustNameId);
                newTable.find("input.NewPaymentFee").attr('name', 'payment[' + nowInputIndex + '][adjust_fee]').removeClass('NewPaymentFee').addClass('payment_fee text_right').val(adjustFee);
                newTable.find("input.NewPaymentId").attr('name', 'payment[' + nowInputIndex + '][payment_id]').removeClass('NewPaymentId').addClass('payment_id');
                if (disable) {
                    newTable.find("input").prop("readonly", true);
                    newTable.find("select").prop("disabled", true);
                    newTable.find("a.inputDelete").remove();
                }
                $('#inputActive').append(newTable);
                $('#have_payment_adjust').val("1");
                nowInputIndex++;
            };
            // 受講料以外追加
            $("#inputAdd").click(function () {
                createRoutinePaymentDom();
            });

            // Set discount student
            $("#inputAddStudent").click(function () {
                createRoutinePaymentStudentDom();
            });
            var createRoutinePaymentStudentDom = function (month, invoiceAdjustNameId, adjustFee, disable) {
                var newTable = $('#inputBase table').clone();
                newTable.addClass('adjust_payment_container_student');
                newTable.find("select.NewPaymentMonth").attr('name', 'payment_student[' + nowInputStudent + '][month]').removeClass('NewPaymentMonth').val(month);
                newTable.find("select.NewPaymentAdjust").attr('name', 'payment_student[' + nowInputStudent + '][invoice_adjust_name_id]').removeClass('NewPaymentAdjust').addClass('payment_adjust').val(invoiceAdjustNameId);
                newTable.find("input.NewPaymentFee").attr('name', 'payment_student[' + nowInputStudent + '][adjust_fee]').removeClass('NewPaymentFee').addClass('payment_fee text_right').val(adjustFee);
                newTable.find("input.NewPaymentId").attr('name', 'payment_student[' + nowInputStudent + '][payment_id]').removeClass('NewPaymentId').addClass('payment_id');
                if (disable) {
                    newTable.find("input").prop("readonly", true);
                    newTable.find("select").prop("disabled", true);
                    newTable.find("a.inputDelete").remove();
                }
                $('#inputActiveStudent').append(newTable);
                $('#have_payment_adjust_student').val("1");
                nowInputStudent++;
            };


            //Get adjust fee when change adjust name
            $(document).on('change', '.payment_adjust', function () {
                var adjust = $(this).val();
                var paymentFeeElem = $(this).parent().find('.payment_fee');
                $.get(
                    "/school/ajaxInvoice/getinitfee",
                    {adjust: adjust},
                    function (data) {
                        // 金額設定
                        paymentFeeElem.val(data);
                    },
                    "jsonp"
                );
                return false;
            });

            //Delete payment adjust input
            $(document).on('click', '.inputDelete', function (e) {
                e.preventDefault();
                $(this).parent().parent().parent().parent().remove();
                if ($(".adjust_payment_container").length == 0) {
                    $('#have_payment_adjust').val("");
                }
            });
            //Delete payment adjust student
            $(document).on('click', '.inputDeleteStudent', function (e) {
                e.preventDefault();
                var adjust_id = $(this).data('adjust_id');
                $('input[name="payment_student_delete[' +adjust_id+ ']').val('1');
                $(this).parent().parent().parent().parent().remove();
                if ($(".adjust_payment_container_student").length == 0) {
                    $('#have_payment_adjust_student').val("");
                }

            });
            $('#custom7').on('change', function(){
                this.value = this.checked ? 1 : 0;
                // alert(this.value);
            }).change();
            $('#submit_return').click(function (e) {
                @if(request()->has('id'))
                    java_post('/school/student/detail?id={{request('id')}}');
                @else
                    java_post('/school/student');
                @endif

            });

            function nextForm(event) {
                //Enter key will move to another element
                if (event.keyCode == 0x0d) {
                    var current = document.activeElement;
                    var focus = 0;
                    for (var idx = 0; idx < document.action_form.elements.length; idx++) {
                        if (document.action_form[idx] == current) {
                            focus = idx;
                            break;
                        }
                    }
                    document.action_form[(focus + 1)].focus();
                }
            }

            window.document.onkeydown = nextForm;

            /**
             * Return HTML5 Data attribute as string
             * @param attr
             * @returns {string}
             */
            var createDataAttrDom = function (attr) {
                var dom = "";
                $.each(attr, function (key, val) {
                    dom += "data-" + key + "='" + ((val == null || val == "NULL" || val == "null") ? '' : val) + "' ";
                });
                return dom;
            };

//  送付先のその他
            $('[name=other_name_flag]').change(function () {
                if ($(this).is(":checked")) {
                    if ($(this).val() == 1) {
                        $('[name=student_name_other]').attr('disabled', false);
                    } else {
                        $('[name=student_name_other]').prop('disabled', true);
                    }
                }

            });
            $('[name=other_address_flag]').change(function () {
                if ($(this).is(":checked")) {
                    if ($(this).val() == 1) {
                        $('[name=zip_code1_other], [name=zip_code2_other], [name=pref_id_other], [name=city_id_other], [name=student_address_other], [name=student_building_other]').attr('disabled', false);
                    } else {
                        $('[name=zip_code1_other], [name=zip_code2_other], [name=pref_id_other], [name=city_id_other], [name=student_address_other], [name=student_building_other]').prop('disabled', true);
                    }
                }

            });

            $('[name=other_name_flag]').change();
            $('[name=other_address_flag]').change();
//  END -- 送付先のその他
            function checkCanUnactiveStudent(){
                var is_debit = {{array_get($request, 'is_debit_invoice', 0)}};
                if(is_debit){
                    $("#warning_unactive_student").dialog('open');
                    return false;
                }else{
                    return true;
                }
            }
        });
        $(function () {
            $("#invoicetype").change(function (e) {
                if ($(this).val() == 5) {
                    $("#mailinfo").val(1);
                    $("#mailinfo option[value=0]").hide();
                    $("#mailinfo option[value=2]").hide();
                } else if ($(this).val() == 7) {
                    $("#mailinfo").val(1);
                    $("#mailinfo option[value=0]").hide();
                    $("#mailinfo option[value=1]").show();
                    $("#mailinfo option[value=2]").show();
                } else if ($(this).val() ==3 || $(this).val() == 4) {
                    $("#mailinfo").val(2);
                    // $("#mailinfo option[value='0']").remove();
                    $("#mailinfo option[value=0]").hide();
                    $("#mailinfo option[value=2]").show();
                } else {
                    $("#mailinfo").val(0);
                    $("#mailinfo option[value=0]").show();
                    $("#mailinfo option[value=2]").show();
                }
            });
        });

        /**
         * 行を削除
         */
        function removePersonInChargeRow(obj) {
            // 行削除
            $(obj).closest("div").remove();
            return false;
        }

    </script>

@stop