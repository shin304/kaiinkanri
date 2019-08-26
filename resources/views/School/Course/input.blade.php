@extends('_parts.master_layout')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />

    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-list-alt"></i>{{$lan::get('main_title')}}</h2>
            <div class="center_content_header_right">
                <div class="top_btn">
                    @php 
                        $course_id = old('course_id', request('course_id'));
                    @endphp
                    {{--@if ( $course_id && count($student_list) < 1)
                        <a class="delete_btn" href="#delete"><li>{{$lan::get('course_delete')}}</li></a>
                    @endif --}}
                </div>

            </div>
            <div class="clr"></div>
        </div><!--center_content_header-->

        <h3 id="content_h3" class="box_border1">@if (request('id')) {{$lan::get('edit')}}@else{{$lan::get('registration')}}@endif</h3>
        
        @if (count($errors) > 0) 
            <ul class="message_area"> 
            @foreach ($errors->all() as $error)
            <li class="error_message">{{ $lan::get($error) }}</li>
            @endforeach
            </ul>
        @endif 

        <div id="section_content1">
            <p><span class="aster">&lowast;</span>{{$lan::get('items_marked_with_mandatory')}}</p>
            <form id="action_form" name="action_form" method="post">
            {{ csrf_field() }}
                <input type="hidden" name="function" value="1"/>
                <input type="hidden" name="course_id" value="{{ $course_id }}"/>
                <input type="hidden" name="event_id" value="{{ old('event_id', request('event_id')) }}"/>
                <input type="hidden" name="id" value="{{ old('id', request('id')) }}"/>
                
                <table id="table6">
                    <colgroup>
                        <col style="width: 15%">
                        <col style="width: 35%">
                        <col style="width: 15%">
                        <col style="width: 35%">
                    </colgroup>
                    <tr>
                        <td class="t6_td1">
                            <div style="width: 130px;">
                                {{$lan::get('event_code_title')}}<span class="aster">&lowast;</span>
                            </div>
                        </td>
                        <td colspan="3" class="t6_td1"><input class="text_l" style="ime-mode:active;" type="text" name="course_code" value="{{ old('course_code', request('course_code')) }}" maxlength="5" placeholder="{{ $lan::get('guide_event_code_title') }}" /></td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('name')}}<span class="aster">&lowast;</span></td>
                        <td colspan="3"><input class="text_l form-group" style="ime-mode:active;" type="text" name="course_title" value="{{ old('course_title', request('course_title')) }}" placeholder="{{ $lan::get('name_input_title') }}" /></td>
                    </tr>
                    <tr>
                        <th colspan="3" class="btn_area_td"> <!-- 送信メール -->
                            <input type="checkbox" id="send_mail_flag" name="send_mail_flag" @if (old('send_mail_flag', request('send_mail_flag'))) checked @endif>
                            <b><label for="send_mail_flag">{{$lan::get('sending_mail_title')}}</label></b>
                        </th>
                        <th class="sending_mail_area" >
                            <div align="right">
                                <input type="button" id="btn_load_list" name="btn_load_list" value="{{$lan::get('btn_list_mail_template')}}" style="height: 32px; font-weight: 400;">
                                <div class="divider"></div>
                                <input type="button" id="btn_create_list" name="btn_create_list" value="{{$lan::get('mail_template_create')}}" style="height: 32px; font-weight: 400;">
                            </div>
                            
                        </th>
                    </tr>
                    <tr>
                        <div id="create_error" class="alert alert-warning" style="display: none;">{{$lan::get('mail_template_error')}}</div>
                    </tr>
                    <tr>
                        <div id="create_success" class="alert alert-success" style="display: none;">{{$lan::get('mail_template_success')}}</div>
                    </tr>
                    <tr class="sending_mail_area"> <!-- 件名 -->
                        <td class="t6_td1">{{$lan::get('header_mail_title')}}<span class="aster">&lowast;</span></td>
                        <td colspan="3"><input id="mail_subject" class="text_l form-group" style="ime-mode:active;" type="text" name="mail_subject" value="{{ old('mail_subject', request('mail_subject')) }}" placeholder="{{ $lan::get('header_mail_input_title') }}"/></td>
                    </tr>
                    <tr class="sending_mail_area"> <!-- 本文 -->
                        <td  class="t6_td1">{{$lan::get('content')}}<span class="aster">&lowast;</span></td>
                        <td colspan="3" style="padding-right: 20%">
                        <textarea style="ime-mode:active;" id="mail_description" name="course_description"  rows="5"  class="description_textarea" placeholder="{{ $lan::get('content_input_title') }}">{{ old('course_description', request('course_description')) }}</textarea>
                        </td>
                    </tr>
                    <tr class="sending_mail_area"> <!-- フッター -->
                        <td class="t6_td1">{{$lan::get('footer_mail_title')}}</td>
                        <td colspan="3" style="padding-right: 20%">
                            <textarea style="width: 100%;" id="mail_footer" style="ime-mode:active;" name="mail_footer">{{ old('mail_footer', request('mail_footer')) }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('lecturer')}}</td>
                        <td>
                            <ul style="float:left" id="teacher_area">
                                @php
                                $teacher_ids = old('teacher_ids', request('teacher_ids'));
                                @endphp
                                @if (count($teacher_ids)>0 )

                                @foreach ($teacher_ids as $k=>$v)
                                    <li style="margin-bottom:5px;list-style-type:none;">
                                        <select name="teacher_ids[]">
                                        <option value="" ></option>
                                        @foreach ($teacher_data as $row)
                                            <option value="{{array_get($row,'id')}}" @if ($v == array_get($row,'id'))selected @endif>{{array_get($row,'coach_name')}}</option>
                                        @endforeach
                                        </select>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                            <div style="margin-left:10px;float:left;">
                                <!-- <input type="button" value="{{$lan::get('add_to')}}" id="add_teacher_row"/> -->
                                <button id="add_teacher_row" type="button" style="font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_to')}}</button> &nbsp;
                            </div>
                            <div style="display:none;" id="teacher_row_template">
                                <ul>
                                    <li style="margin-bottom:5px;list-style-type:none;">
                                        <select name="template_teacher">
                                        <option value=""></option>
                                        @foreach ($teacher_data as $row)
                                            <option value="{{array_get($row,'id')}}">{{array_get($row,'coach_name')}}</option>
                                        @endforeach
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <!-- 仕様変更2017/06/05： 会員種別しかありません -->
                {{--<tr>
                    <td class="t6_td1"></td>
                    <td>
                        <input type="radio" name="fee_type" value="1" id="member_type" @if (old('fee_type', request('fee_type')) == 1) checked @endif>&nbsp;<label for="member_type"><b>{{$lan::get('fee_by_member_type')}}</b></label>
                    </td>
                    <td colspan="2"><input type="radio" name="fee_type" value="2" id="fee_number" @if (old('fee_type', request('fee_type')) != 1) checked @endif>&nbsp;<label for="fee_number"><b>{{$lan::get('fee_by_number')}}</b></label></td>
                </tr> --}}
                <!-- 仕様変更2017/05/16： 会員種別による料金設定 -->
                <tr id="fee_by_member_type">

                    <td class="t6_td1">
                    <input type="hidden" name="fee_type" value="1" />   <!-- 仕様変更2017/06/05： 会員種別しかありません -->
                    {{$lan::get('tution_fee')}}</td>
                    <td colspan="3">
                    <div id="inputMemberType" >
                    <table class="input_member_type" style="width: 90%">
                        <tr style="text-align:center;">
                            <td style="width: 10%" class="t6_td1">{{$lan::get('member_type_title')}}<span class="aster">&lowast;</span></td>
                            <td style="width: 30%" class="t6_td1">{{$lan::get('fee_plan_name_title')}}<span class="aster">&lowast;</span></td>
                            {{--<td style="width: 30%" class="t6_td1">{{$lan::get('remark_title')}}</td>--}}
                            <td style="width: 10%" class="t6_td1"></td>
                            <td style="width: 25%" class="t6_td1">{{$lan::get('rates_yen')}}<span class="aster">&lowast;</span></td>
                            <td style="width: 8%" class="t6_td1">{{$lan::get('display_order')}}</td>
                            <td style="width: 8%" class="t6_td1">{{$lan::get('invalid')}}</td>
                        </tr>
                        @php
                            $_course_fee1 = old('_course_fee1', request('_course_fee1'));
                        @endphp
                        @if ($_course_fee1 > 0)
                        @foreach ($_course_fee1 as $idx=>$row)

                        <tr>
                            <td>
                            <input class="sort_no" type="hidden" name="_course_fee1[{{$idx}}][sort_no]" value="{{$idx+1}}" />
                            <input type="hidden" name="_course_fee1[{{$idx}}][id]" value="{{array_get($row,'id')}}" />

                            <select name="_course_fee1[{{$idx}}][student_type_id]" class="student_types">
                                <option value=""></option>
                                @foreach ($studentTypes as $key=>$val)
                                    <option value="{{$key}}" @if ($key == array_get($row,'student_type_id')) selected @endif>{{$val['name']}}</option>
                                @endforeach
                            </select>
                            </td>
                            <td>
                                <input type="text" name="_course_fee1[{{$idx}}][fee_plan_name]" value="{{array_get($row,'fee_plan_name')}}" class="l_text text_ellipsis "/>
                            </td>
                            {{--<td>
                                <input type="text" name="_course_fee1[{{$idx}}][remark]" value="{{array_get($row,'remark')}}" class="l_text text_ellipsis " disabled  title="" />
                            </td>--}}
                            <td>
                            <!-- 選択リスト追加 :
                            ・一人当たり
　                           ・全員で -->
                                <select name="_course_fee1[{{$idx}}][payment_unit]">
                                    <option value="1" @if (array_get($row,'payment_unit') != 2) selected @endif>{{$lan::get('payment_unit_person_title')}}</option>
                                    <option value="2" @if (array_get($row,'payment_unit') == 2) selected @endif>{{$lan::get('payment_unit_everyone_title')}}</option>
                                </select>
                            </td>
                            <td class="t4d2">
                                    <input  style="ime-mode:inactive;width:150px;text-align: right;" type="text" name="_course_fee1[{{$idx}}][fee]" value="@if (is_numeric(array_get($row,'fee'))) {{number_format(array_get($row,'fee'))}} @else {{array_get($row,'fee')}} @endif" class="l_text"
                                            pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                            </td>
                            <td class="t4d2">
                                @if (count($_course_fee1) > 1)
                                    @if ($loop->first)
                                        <a  style="padding:5px;visibility:visible;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:hidden;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                    @elseif ($loop->last)
                                        <a  style="padding:5px;visibility:hidden;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:visible;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                    @else
                                        <a  style="padding:5px;visibility:visible;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:visible;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                    @endif
                                @else
                                        <a  style="padding:5px;visibility:hidden;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:hidden;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                @endif
                                </td>
                                <td class="t4d2" >
                                    <input style="width:50px;vertical-align:bottom;" type="checkbox" name="_course_fee1[{{$idx}}][active_flag]" value="0" @if (array_get($row,'active_flag') == '0')checked @endif/>&nbsp;
                                </td>
                                @if (array_get($row,'id') != '')
                                <td  class="t4d2" style="text-align:center;">
                                    <!-- <input style="visibility:hidden;" type="button" value="{{$lan::get('delete_row')}}" /> -->
                                    <button type="button" style="visibility:hidden; width: 75px !important; font-size: 11px !important;" onclick="removeRow(this); return false;" ><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;" ></i>&nbsp; {{$lan::get('delete_row')}}</button>
                                </td>
                                @else
                                <td  class="t4d2" style="text-align:center;">
                                    @if (!$loop->first)
                                    <!-- <input type="button" value="{{$lan::get('delete_row')}}" onclick="removeRow(this); return false;" /> -->
                                    <button type="button" style="width: 75px !important; font-size: 11px !important;" onclick="removeRow(this); return false;" ><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;" ></i>&nbsp; {{$lan::get('delete_row')}}</button>
                                    @endif
                                </td>
                                @endif
                        </tr>

                        @endforeach
                        @endif
                    </table>
                    </div>
                    <!-- <input type="button" value="{{$lan::get('add_row')}}" id="btn_add_row3"  style="margin:10px;"/> -->
                    <button id="btn_add_row3" type="button" style="margin:10px; font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_row')}}</button> &nbsp;
                    </td>
                </tr>
                <tr></tr>
                <!-- 仕様変更2017/05/16： 回数による料金設定 -->
                <tr id="fee_by_number" style=" display: none;">  <!-- 仕様変更2017/06/05： 会員種別しかありません -->
                    <td class="t6_td1">{{$lan::get('tution_fee')}}</td>
                    <td colspan="3">
                    <div id="inputActive" >
                        <table class="input_course_fee" style="width:750px;border-collapse:collapse;">
                            <tr style="text-align:center;">
                                <td class="t6_td1">
                                    {{$lan::get('name')}}
                                </td>
                                <td class="t6_td1">
                                    {{$lan::get('units_number_attempts')}}
                                </td>
                                <td class="t6_td1">
                                    {{$lan::get('number_attempts')}}
                                </td>
                                <td class="t6_td1">
                                    {{$lan::get('rates_yen')}}
                                </td>
                                <td class="t6_td1">
                                    {{$lan::get('display_order')}}
                                </td>
                                <td class="t6_td1">
                                    {{$lan::get('invalid')}}
                                </td>
                                <td class="t6_td1">
                                </td>
                            </tr>
                            
                            @php
                                $_course_fee = old('_course_fee', request('_course_fee'));
                            @endphp
                            @if ($_course_fee > 0)
                            @foreach ($_course_fee as $idx=>$row)

                            {{--@if ((!array_get($row, 'student_type_id') && array_get($row,'id')) || !old('id', request('id')))--}}
                            <tr>
                                <td class="t4d2">
                                <input class="sort_no" type="hidden" name="_course_fee[{{$idx}}][sort_no]" value="{{$idx+1}}" />
                                <input type="hidden" name="_course_fee[{{$idx}}][id]" value="{{array_get($row,'id')}}" />
                                    <input style="ime-mode:inactive;width:250px;" type="text" name="_course_fee[{{$idx}}][fee_plan_name]" value="{{array_get($row,'fee_plan_name')}}" class="l_text" />
                                </td>
                                <td class="t4d2">
                                    <select  style="width:100px;" name="_course_fee[{{$idx}}][attend_times_div]">
                                        <option value=""></option>
                                        @foreach ($attendTimesDivList as $key=>$val)
                                            <option value="{{$key}}" @if ( $key==array_get($row,'attend_times_div')) selected @endif>{{$val}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="t4d2">
                                    <input  style="ime-mode:inactive;width:75px;" type="text" name="_course_fee[{{$idx}}][attend_times]" value="{{array_get($row,'attend_times')}}" class="l_text" />
                                </td>
                                <td class="t4d2">
                                    <input  style="ime-mode:inactive;width:150px;" type="text" name="_course_fee[{{$idx}}][fee]" value="{{array_get($row,'fee')}}" class="l_text" />
                                </td>
                                <td class="t4d2">
                                @if (count($_course_fee) > 1)
                                    @if ($loop->first)
                                        <a  style="padding:5px;visibility:visible;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:hidden;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                    @elseif ($loop->last)
                                        <a  style="padding:5px;visibility:hidden;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:visible;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                    @else
                                        <a  style="padding:5px;visibility:visible;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:visible;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                    @endif
                                @else
                                        <a  style="padding:5px;visibility:hidden;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:hidden;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                                @endif
                                </td>
                                <td class="t4d2" >
                                    <input style="width:50px;vertical-align:bottom;" type="checkbox" name="_course_fee[{{$idx}}][active_flag]" value="0" @if (array_get($row,'active_flag') == '0')checked @endif/>&nbsp;
                                </td>
                                @if (array_get($row,'id') != '')
                                <td  class="t4d2" style="text-align:center;">
                                    <!-- <input style="visibility:hidden;" type="button" value="{{$lan::get('delete_row')}}" /> -->
                                    <button type="button" style="visibility:hidden; width: 75px !important; font-size: 11px !important;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row')}}</button>
                                </td>
                                @else
                                <td  class="t4d2" style="text-align:center;">
                                    <!-- <input type="button" value="{{$lan::get('delete_row')}}" onclick="removeRow(this); return false;" /> -->
                                    <button type="button" style="width: 75px !important; font-size: 11px !important;" onclick="removeRow(this); return false;" ><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;" ></i>&nbsp; {{$lan::get('delete_row')}}</button>
                                </td>
                                @endif
                            </tr>
                            
                            @endforeach
                            @endif
                        </table>
                    </div>
                    <!-- <input type="button" value="{{$lan::get('add_row')}}" id="btn_add_row"  style="margin:10px;"/> -->
                    <button id="btn_add_row" type="button" style="margin:10px; font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_row')}}</button> &nbsp;
                </td>
            </tr>


                    <tr>
                        <td class="t6_td1">{{$lan::get('start_date_time')}}<span class="aster">&lowast;</span></td>
                        <td><input class="DateTimeInput" type="text" name="start_date" value="@if (old('start_date', request('start_date'))) {{date('Y-m-d H:i',strtotime(old('start_date', request('start_date'))))}} @endif"/></td>
                        <td class="t6_td1">{{$lan::get('end_date_time')}}</td>
                        <td><input class="DateTimeInput" type="text" name="close_date" value="@if (old('close_date', request('close_date'))) {{date('Y-m-d H:i',strtotime(old('close_date', request('close_date'))))}} @endif"/></td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('recruitment_start_title')}}<span class="aster">&lowast;</span></td>
                        <td><input class="DateInput" type="text" name="recruitment_start" value="@if (old('recruitment_start', request('recruitment_start'))) {{date('Y-m-d',strtotime(old('recruitment_start', request('recruitment_start'))))}}  @endif "/></td>
                        <td class="t6_td1">{{$lan::get('recruitment_finish_title')}}</td>
                        <td><input class="DateInput" type="text" name="recruitment_finish" value="@if (old('recruitment_finish', request('recruitment_finish'))) {{date('Y-m-d',strtotime(old('recruitment_finish', request('recruitment_finish'))))}} @endif "/></td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('event_location_title')}}<span class="aster">&lowast;</span></td>
                        <td class="t6_td1" colspan="3"><input class="text_l form-group" style="ime-mode:active;" type="text" name="course_location" value="{{ old('course_location', request('course_location')) }}" placeholder="{{ $lan::get('event_location_input_title') }}"/></td>
                    </tr>

                    <tr>
                        <td class="t6_td1">{{$lan::get('contact_number_title')}}</td>
                        <td class="t6_td1" colspan="3"><input class="text_l form-group" style="ime-mode:active;" type="text" name="contact_number" value="{{ old('contact_number', request('contact_number')) }}" placeholder="{{ $lan::get('contact_number_input_title') }}"/></td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('contact_email_title')}}</td>
                        <td class="t6_td1" colspan="3"><input class="text_l form-group" style="ime-mode:active;" type="text" name="contact_email" value="{{ old('contact_email', request('contact_email')) }}" placeholder="{{ $lan::get('contact_email_input_title') }}"/></td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('person_in_charge_title')}}</td>
                        <td class="t6_td1" colspan="3">
                            {{$lan::get('person_in_charge1_title')}}
                            <select name="person_in_charge1">
                                <option></option>
                                @foreach ($staff_list as $key => $staff)
                                    <option value="{{$staff['staff_name']}}" @if (old('person_in_charge1', request('person_in_charge1')) == $staff['staff_name']) selected @endif >{{$staff['staff_name']}}</option>
                                @endforeach
                            </select>
                            {{$lan::get('person_in_charge2_title')}}
                            <select name="person_in_charge2">
                                <option></option>
                                @foreach ($staff_list as $key => $staff)
                                    <option value="{{$staff['staff_name']}}" @if (old('person_in_charge2', request('person_in_charge2')) == $staff['staff_name']) selected @endif>{{$staff['staff_name']}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">
                        <input type="hidden" name="active_flag" value="1" />
                        {{$lan::get('payment_type_title')}}</td>
                        <td style="word-spacing:20px" colspan="3">

                            @foreach($payment_list as $item)
                                <label style="margin-right: 20px;font-weight: 500;"><input type="checkbox" name="payment_methods[]" value="{{$item['payment_method_code']}}" @if (old('payment_methods', request('payment_methods')) && in_array($item['payment_method_code'], old('payment_methods', request('payment_methods')))) checked @endif>{{$lan::get(array_get($item, 'payment_method_name'))}}</label>
                            @endforeach
                            @forelse ($payment_list as $item)
                            @empty
                                <p class="error_message">{{$lan::get('notice_setting_payment_method')}}</p>
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('merge_event_to_schedule_invoice')}}</td>
                        <td style="word-spacing:20px" colspan="3">

                            <label><input type="radio" name="is_merge_invoice" value = "0" @if($request->is_merge_invoice == 0 || !$request->is_merge_invoice) checked @endif>{{$lan::get('no_title')}}</label>
                            <label><input type="radio" name="is_merge_invoice" value = "1" @if($request->is_merge_invoice == 1) checked @endif>{{$lan::get('yes_title')}}</label>
                        </td>
                    </tr>
                    <tr id="payment_due_date_block" style="display: none;">
                        <td class="t6_td1">{{$lan::get('payment_due_date_title')}}</td>
                        <td colspan="3"><input type="text" class="DateInput" name="payment_due_date" value="@if (old('payment_due_date', request('payment_due_date'))) {{date('Y-m-d',strtotime(old('payment_due_date', request('payment_due_date'))))}} @endif  "></td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('member_capacity_title')}}</td>
                        <td class="t6_td1"><input type="number" name="member_capacity" value="{{old('member_capacity', request('member_capacity'))}}" min="0" style="text-align: right;width: 100px;">&nbsp; {{$lan::get('name_title')}}</td>
                        <td class="t6_td1"><label><input type="checkbox" name="non_member_flag" value="1" @if (old('non_member_flag', request('non_member_flag')) == 1) checked @endif disabled>{{$lan::get('non_member_flag_title')}}</label></td>
                        <td class="t6_td1">{{$lan::get('non_member_capacity_title')}}<input type="number" name="non_member_capacity" value="{{old('non_member_capacity', request('non_member_capacity'))}}" min="0" style="text-align: right;width: 100px;">&nbsp; {{$lan::get('name_title')}}</td>
                    </tr>
                    {{--<tr>
                        <td class="t6_td1">{{$lan::get('non_member_capacity_title')}}</td>
                        <td class="t6_td1" colspan="3"><input type="number" name="non_member_capacity_title" value="{{old('non_member_capacity_title', request('non_member_capacity_title'))}}" min="0" style="text-align: right;width: 100px;">&nbsp; {{$lan::get('name_title')}}</td>
                    </tr> --}}
                    <tr>
                        <td class="t6_td1" colspan="4">
                        <input type="checkbox" id="application_deadline" name="application_deadline" value="1" @if (old('application_deadline', request('application_deadline')) == 1) checked @endif><label for="application_deadline">{{$lan::get('application_deadline_title')}}</label></td>
                        
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('remark_title')}}</td>
                        <td class="t6_td1" colspan="3">
                        <textarea style="ime-mode:active;" id="remark" name="remark" cols="100" rows="5"  class="description_textarea">{{ old('remark', request('remark')) }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('attention_title')}}</td>
                        <td class="t6_td1" colspan="3">
                            <textarea style="ime-mode:active;" name="remark_1" cols="100" rows="5"  class="description_textarea">{{ old('remark_1', request('remark_1')) }}</textarea>
                        </td>
                    </tr>
                </table>
                {{--<br/>
                <div class="exe_button">
                    <input type="button" value="{{$lan::get('confirm')}}" id="btn_submit" class="submit2" onclick="save_confirm()" />
                    <input type="button" value="{{$lan::get('return_title')}}" class="submit2" id="btn_return" />
                </div>--}}
                <div class="div-btn">
                    <ul>
                        <!-- <a href="" class="button" id="submit2" ><li><i class="glyphicon glyphicon-floppy-disk"></i>@if(old('id', request('id'))) {{$lan::get('edit')}} @else {{$lan::get('registration')}} @endif</li></a> -->
                        <a href="" class="button" id="submit2" ><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;{{$lan::get('registration')}} </li></a>
                        <a href="{{$_app_path}}course" class="text_link button" id="btn_back"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-circle-arrow-left"></i>&nbsp;{{$lan::get('return_title')}}</li></a>
                    </ul>
                </div>
            </form>
            <div style="display:none;"><!-- テーブルコピー元ねた -->
                <table id="tbl_clone">
                    <tbody>
                        <tr>
                            <td class="t4d2">
                            <input class="sort_no" type="hidden" name="_course_fee[*][sort_no]" value="" />
                            <input type="hidden" name="_course_fee[*][id]" value="" />
                                <input style="width:250px;" type="text" name="_course_fee[*][fee_plan_name]" value="" class="l_text" />
                            </td>
                            <td class="t4d2">
                                <select  style="width:100px;" name="_course_fee[*][attend_times_div]">
                                    <option value=""></option>
                                    @foreach ($attendTimesDivList as $key=>$val)
                                        <option value="{{$key}}" >{{$val}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="t4d2">
                                <input  style="width:75px;" type="text" name="_course_fee[*][attend_times]" value="" class="l_text" />
                            </td>
                            <td class="t4d2">
                                <input  style="width:150px;" type="text" name="_course_fee[*][fee]" value="" class="l_text" />
                            </td>
                            <td class="t4d2">
                                <a  style="padding:5px;visibility:hidden;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:visible;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                            </td>
                            <td class="t4d2" >
                                <input style="width:50px;vertical-align:bottom;" type="checkbox" name="_course_fee[*][active_flag]" value="0" />&nbsp;
                            </td>
                            <td  class="t4d2" style="text-align:center;">
                                <!-- <input type="button" value="{{$lan::get('delete_row')}}" onclick="removeRow(this); return false;" /> -->
                                <button type="button" style="width: 75px !important; font-size: 11px !important;" onclick="removeRow(this); return false;" ><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;" ></i>&nbsp; {{$lan::get('delete_row')}}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            <table id="tbl_clone3">
                    <tbody>
                        <tr>
                            <td class="t4d2">
                            <input class="sort_no" type="hidden" name="_course_fee1[*][sort_no]" value="" />
                            <input type="hidden" name="_course_fee1[*][id]" value="" />
                                <select name="_course_fee1[*][student_type_id]" class="student_types">
                                <option value=""></option>
                                @foreach ($studentTypes as $key=>$val)
                                    <option value="{{$key}}" >{{$val['name']}}</option>
                                @endforeach
                                </select>
                            </td>
                            <td class="t4d2">
                                <input type="text" name="_course_fee1[*][fee_plan_name]" value="" class="l_text text_ellipsis"/>
                            </td>
                            {{--<td class="t4d2">--}}
                                {{--<input type="text" name="_course_fee1[*][remark]" value="{{array_get($row,'remark')}}" class="l_text text_ellipsis" disabled  title="" />--}}
                            {{--</td>--}}
                            <td class="t4d2">
                            <!-- 選択リスト追加 :
                            ・一人当たり
　                           ・全員で -->
                                <select name="_course_fee1[*][payment_unit]">
                                    <option value="1">{{$lan::get('payment_unit_person_title')}}</option>
                                    <option value="2">{{$lan::get('payment_unit_everyone_title')}}</option>
                                </select>
                            </td>
                            <td class="t4d2">
                                <input  style="width:150px;text-align: right;" type="text" name="_course_fee1[*][fee]" value="" class="l_text" />
                            </td>
                            <td class="t4d2">
                                <a  style="padding:5px;visibility:hidden;" class="row_down" href="javascript:void(0)" onclick="moveRowDown(this); return false;">↓</a><a style="padding:5px;visibility:visible;" class="row_up" href="javascript:void(0)" onclick="moveRowUp(this); return false;">↑</a>
                            </td>
                            <td class="t4d2" >
                                <input style="width:50px;vertical-align:bottom;" type="checkbox" name="_course_fee1[*][active_flag]" value="0" />&nbsp;
                            </td>
                            <td  class="t4d2" style="text-align:center;">
                                <!-- <input type="button" value="{{$lan::get('delete_row')}}" onclick="removeRow(this); return false;" /> -->
                                <button type="button" style="width: 75px !important; font-size: 11px !important;" onclick="removeRow(this); return false;" ><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;" ></i>&nbsp; {{$lan::get('delete_row')}}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><!-- Section Content1 -->
    <div id="dialog-confirm"  style="display: none;">
    {{$lan::get('delete_ok')}}
    </div>

    @include('_mail.mail_template')
    <script type="text/javascript">
        $(function() {
            // init tinymce tool
            tinymce.init({
                selector: 'textarea#mail_description',
                menubar:false,
                toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
            });
            //datepicker追加
            var d = new Date();
            $.datetimepicker.setLocale('ja');

            jQuery(function(){
                jQuery('.DateTimeInput').datetimepicker({
                    format: 'Y-m-d H:i',
                    step : 5,
//                    minDate: new Date(),
                    scrollMonth : false,
                    scrollInput : false

                });
            });
            jQuery(function(){
                jQuery('.DateInput').datetimepicker({
                    format: 'Y-m-d',
                    timepicker:false,
//                    minDate: new Date(),
                    scrollMonth : false,
                    scrollInput : false
                });
            });


            $("#btn_return").click(function() {
                $("#action_form").attr('action', '{{$_app_path}}course');
                $("#action_form").submit();
                return false;
            });
            $('#submit2').click(function (e) {
                e.preventDefault();
                var title = '{{$lan::get('save_confirm_title')}}';
                // var content = '{{$lan::get('save_confirm_content')}}';
                var content = '{{$lan::get('confirm_content')}}';
                var action_url = '{{$_app_path}}course/coursecomplete';
                var arr_data = {};
                @if (request('id'))
                    arr_data = {'function':2};
                @endif
                common_save_confirm(title, content,action_url,arr_data);

            });
            $("#add_teacher_row").click(function(){
                var add_row = $("#teacher_row_template").find("li").clone(true);
                add_row.find("[name=template_teacher]").attr("name", "teacher_ids[]");
                $("#teacher_area").append(add_row);
                return false;
            });


            // 行追加
            $("#btn_add_row").click(function() {

                // 矢印の表示制御
                var i = 1;
                $(".row_up").each(function() {
                    if (i == 1) {
                        // 先頭行
                        $(this).css('visibility', 'hidden');
                    } else {
                        $(this).css('visibility', 'visible');
                    }
                    i++;
                });
                var len_row_down = $(".row_down").length;
                i = 1;
                $(".row_down").each(function() {
                    if(i == len_row_down) {
                        // コピー元ねた行
                        $(this).css('visibility', 'hidden');
                    } else {
                        $(this).css('visibility', 'visible');
                    }
                    i++;
                });

                // 行数取得
                var len = $(".input_course_fee tr").length-1;
                // コピー作成
                var tbl_item = $("#tbl_clone tbody > tr").clone(true).appendTo($(".input_course_fee > tbody"));
                // 名前を変更
                tbl_item.find('input,select').each(function(){
                    if (this.type != "button") {
                        name_str = $(this).attr('name');
                        name_str = name_str.replace("*",len);
                        $(this).attr('name',name_str);
                    }
                    // 表示順
                    if ($(this).hasClass("sort_no")) $(this).val(len+1);
                });

                return false;
            });

            // 行追加
            $("#btn_add_row3").click(function() {

                // 矢印の表示制御
                var i = 1;
                $(".row_up").each(function() {
                    if (i == 1) {
                        // 先頭行
                        $(this).css('visibility', 'hidden');
                    } else {
                        $(this).css('visibility', 'visible');
                    }
                    i++;
                });
                var len_row_down = $(".row_down").length;
                i = 1;
                $(".row_down").each(function() {
                    if(i == len_row_down) {
                        // コピー元ねた行
                        $(this).css('visibility', 'hidden');
                    } else {
                        $(this).css('visibility', 'visible');
                    }
                    i++;
                });

                // 行数取得
                var len = $(".input_member_type tr").length-1;
                // コピー作成
                var tbl_item = $("#tbl_clone3 tbody > tr").clone(true).appendTo($(".input_member_type > tbody"));
                // 名前を変更
                tbl_item.find('input,select').each(function(){
                    if (this.type != "button") {
                        name_str = $(this).attr('name');
                        name_str = name_str.replace("*",len);
                        $(this).attr('name',name_str);
                    }
                    // 表示順
                    if ($(this).hasClass("sort_no")) $(this).val(len+1);
                });

                return false;
            });

            if ($('#payment_cash').is(':checked')) {$('#payment_due_date_block').show();}
            $('#payment_cash').click(function() {
                if ($(this).is(':checked')) {
                    $('#payment_due_date_block').show();
                } else {
                    $('#payment_due_date_block').val('');
                    $('#payment_due_date_block').hide();
                }
            });

            $('[name=non_member_flag]').change(function() {
                if ($(this).is(':checked')) {
                    $('[name=non_member_capacity]').removeAttr('disabled');
                } else {
                    $('[name=non_member_capacity]').prop('disabled', true);
                }
            });
            $('[name=non_member_flag]').change();

            $( "#dialog-confirm" ).dialog({
                title: '{{$lan::get('main_title')}}',
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "{{$lan::get('delete_title')}}": function() {
                        $( this ).dialog( "close" );
                        $("#action_form input[name='function']").val("3");
                        $("#action_form").attr('action', '{{$_app_path}}course/coursecomplete');
                        $("#action_form").submit();
                        return false;
                    },
                    "{{$lan::get('cancel_title')}}": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
            $("a[href='#delete']").click(function() {
                $( "#dialog-confirm" ).dialog('open');
                return false;
            });

            // 仕様変更2017/06/05： 会員種別しかありません
            // $('input[name=fee_type]').change(function(){ console.log($(this).val());
            //     if ($(this).val() == 1) {
            //         $('#fee_by_member_type').show();
            //         $('#fee_by_number').hide();

            //     } else {
            //         $('#fee_by_member_type').hide();
            //         $('#fee_by_number').show();
            //     }

            // });

            // $('input[name=fee_type]:checked').change();


            // Click send_mail_flag
            if (!$('[name=send_mail_flag]').is(':checked')) {
                $('.sending_mail_area').toggle();

            }
            $('[name=send_mail_flag]').change(function() {
                $('.sending_mail_area').toggle();

            });

            $('[name=start_date]').change(function() {
                console.log($(this).val());
                $('[name=close_date]').val($(this).val());
            });

            var student_types = {!! json_encode($studentTypes) !!};
            // studentTypes change => display remark + payment_unit
            $('.student_types').change(function() {

                if ($(this).val() != '') {

                    var pre_name = $(this).attr('name').substring(0, 15);
                    var fee_plan_name = (pre_name+"[fee_plan_name]");

                    // student_types's element exist && fee_plan_name is null
                    //if (student_types.hasOwnProperty($(this).val()) && $('input[name="'+fee_plan_name+'"]').val() == '') {
                    if (student_types.hasOwnProperty($(this).val())) {
                        $('input[name="'+fee_plan_name+'"]').val(student_types[$(this).val()]['name']);
                        $('input[name="'+fee_plan_name+'"]').attr('title',student_types[$(this).val()]['name']);
                    }

//                    var remark_name = (pre_name+"[remark]");
//                    if (student_types.hasOwnProperty($(this).val())) {
//                        $('[name="'+remark_name+'"]').val(student_types[$(this).val()]['remark']);
//                        $('[name="'+remark_name+'"]').attr('title',student_types[$(this).val()]['remark']);
//                    }
                } else {
//                    $('[name="'+remark_name+'"]').val('');
//                    $('[name="'+remark_name+'"]').attr('title', '');

                }
            });
//            $('.student_types').change();
        });

        function nextForm(event)
        {
            if (event.keyCode == 0x0d)
            {
                var current = document.activeElement;

                var forcus = 0;
                for( var idx = 0; idx < document.action_form.elements.length-2; idx++){
                    if( (document.action_form.elements[idx].name != "mail_footer") && (document.action_form[idx] == current) ){
                        forcus = idx;
                        break;
                    }
                }
                document.action_form[(forcus + 1)].focus();
            }
        }
        window.document.onkeydown = nextForm;

        /**
         * 行を削除
         */
        function removeRow(obj) {
            // 行削除
            $(obj).closest("tr").remove()
            // リフレッシュ
            refresh_row_no(obj);
            refresh_display(obj);
            return false;
        }
        /**
         * 行を削除
         */
        function removeRow2(obj) {
            // 行削除
            $(obj).closest("tr").remove()
            return false;
        }

        /**
         * 行を上へ移動
         */
        function moveRowUp(obj) {
            var row = $(obj).parent().parent();
            // 前にtrがあるか？
            if($(row).prev("tr")) {
                $(row).insertBefore($(row).prev("tr")[0]);
            }
            // リフレッシュ
            refresh_row_no(obj);
            refresh_display(obj);
            return false;
        }

        /**
         * 行を下へ移動
         */
        function moveRowDown(obj) {
            var row = $(obj).parent().parent();
            if($(row).next("tr")) {
                $(row).insertAfter($(row).next("tr")[0]);
            }
            // リフレッシュ
            refresh_row_no(obj);
            refresh_display(obj);
            return false;
        }

        /**
         * sort_noをリフレッシュ
         */
        function refresh_row_no(obj) {
            var i = 1;
            $(".sort_no").each(function() {
                $(this).val(i);
                i++;
            });
            return false;
        }
        /**
         * 矢印の表示をリフレッシュ
         */
        function refresh_display(obj) {
            var i = 1;
            $(".row_up").each(function() {
                if (i == 1) {
                    // 先頭行
                    $(this).css('visibility', 'hidden');
                } else {
                    $(this).css('visibility', 'visible');
                }
                i++;
            });
            // 行数取得（コピー元ねた行を除く）
            var len = $(".row_down").length-1;
            // 行数取得（コピー元ねた行を含む）
            var len_incl_copy = $(".row_down").length;
            i = 1;
            $(".row_down").each(function() {
                if (i == len || i == len_incl_copy) {
                    // 表示最終行とコピー元ねた行
                    $(this).css('visibility', 'hidden');
                } else {
                    $(this).css('visibility', 'visible');
                }
                i++;
            });

            return false;
        }


// @author by MThu
    getTypeMail(1);
    </script>
    <style>
        .div-btn li:hover, #btn_create_list:hover, #add_teacher_row:hover, #btn_load_list:hover, #btn_add_row3:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .div-btn li, #btn_load_list, #btn_create_list, #add_teacher_row, #btn_add_row3 {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
@stop