@extends('_parts.master_layout')
@section('content')
{{--CSS content begin--}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/coach.css" />
{{--CSS content end--}}

{{--HTML content begin--}}
<div id="center_content_header" class="box_border1">
    <h2 class="float_left main-title"><i class="fa fa-black-tie"></i>{{$lan::get('main_title')}}</h2>
    <div class="clr"></div>
</div><!--center_content_header-->
<h3 id="content_h3" class="screen-title">
    @if (request('id')) {{$lan::get('detail_info_edit_title')}}@else{{$lan::get('detail_info_register_title')}}@endif
</h3>


@if (count($errors) > 0) 
    <ul class="message_area"> 
    @foreach ($errors->all() as $error)
        <li class="error_message">{{ $lan::get($error) }}</li>
    @endforeach
    </ul>
@endif
@if (session()->has('messages'))
    <ul class="message_area">
        <li class="info_message">{{ $lan::get(session()->pull('messages')) }}</li>
    </ul>
@endif

<div id="section_content1">
    <p><span class="aster">&lowast;</span>{{$lan::get('required_text_explain_title')}}</p>
    <form id="entry_form" name="entry_form" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{-- Hidden fields begin --}}
        @if(request()->has('id'))
            <input type="hidden" name="function" value="2" />
        @else
            <input type="hidden" name="function" value="1" />
        @endif
        <input type="hidden" name="id" value="{{request('id')}}"/>
        <input type="hidden" name="profile_img" value="{{request('profile_img')}}" />
        <input type="hidden" name="login_info_id" value="{{request('login_info_id')}}">
        <input type="hidden" name="login_info_temp_id" value="{{request('login_info_temp_id')}}"/>
        {{-- Hidden fields end --}}

        <table id="table6">
            <colgroup>
                <col width="30%"/>
                <col width="70%"/>
            </colgroup>
            <tr>
                <td class="t6_td1">{{$lan::get('profile_avatar_title')}}</td>
                <td class="t4td2">
                    @if(request()->has('profile_img'))
                        <img id="img_review" width="64" height="64" src1="/image/{{request('profile_img')}}" /> <br>
                    @else
                        <img id="img_review" width="64" height="64" src="/img/school/default_user.png" />  <br>
                    @endif
                    <input type="file" name="profile" id="profile" />
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('name_title')}}<span class="aster">&lowast;</span></td>
                <td class="t4td2">
                    <input class="text_m" style="ime-mode:active;" type="text" name="coach_name"
                           value="{{old('coach_name', request('coach_name'))}}" placeholder="{{$lan::get('name_title')}}{{$lan::get('placeholder_please_input')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('furigana_title')}}<span class="aster">&lowast;</span></td>
                <td class="t4td2">
                    <input class="text_m" style="ime-mode:active;" type="text" name="coach_name_kana" value="{{old('coach_name_kana', request('coach_name_kana'))}}"
                           placeholder="{{$lan::get('furigana_title')}}{{$lan::get('placeholder_please_input')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('email_title')}}<span class="aster">&lowast;</span></td>
                <td class="t4td2">
                    <input class="text_m" style="ime-mode:inactive;" type="text" name="coach_mail" value="{{old('coach_mail', request('coach_mail'))}}"
                           placeholder="{{$lan::get('email_title')}}{{$lan::get('placeholder_please_input')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('password_title')}}<span class="aster">&lowast;</span></td>
                <td class="t4td2">
                    <input class="text_m"  type="password" name="coach_pass1" placeholder="{{$lan::get('password_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        ※{{$lan::get('password_rule_title')}}
                    @if(request()->has('id') && request()->has('coach_pass1'))
                        <br/>
                        <span class="col_msg">※{{$lan::get('input_only_when_change_title')}}</span>
                    @endif
                    {{--<input type="hidden" name="old_pw" value="{{request('old_pw')}}"/>--}}
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('password_confirm_title')}}<span class="aster">&lowast;</span></td>
                <td class="t4td2">
                    <input class="text_m"  type="password" name="coach_pass2" placeholder="{{$lan::get('password_confirm_title')}}{{$lan::get('placeholder_please_input')}}"/>
                    @if(request()->has('id') && !request()->has('coach_pass2'))
                        <br/>
                        <span class="col_msg">※{{$lan::get('input_only_when_change_title')}}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('phone_number_title')}}</td>
                <td class="t4td2">
                    <input class="text_m" type="text" name="address1_phone" value="{{old('address1_phone', request('address1_phone'))}}"
                           placeholder="{{$lan::get('phone_number_title')}}{{$lan::get('placeholder_please_input')}}"/>
                </td>
            </tr>
            <tr>
                <td class="t6_td1">{{$lan::get('mobile_number_title')}}</td>
                <td class="t4td2">
                    <input class="text_m" type="text" name="mobile_no" value="{{old('mobile_no',request('mobile_no'))}}"
                           placeholder="{{$lan::get('mobile_number_title')}}{{$lan::get('placeholder_please_input')}}"/>
                </td>
            </tr>
        </table>

        {{-- Menu setting begin --}}
        <table>
            <colgroup>
                <col width="30%"/>
                <col width="70%"/>
            </colgroup>
            <tr>
                <td colspan="2"><b>{{$lan::get('permission_setting_title')}}</b></td>
            </tr>
            <tr>
                <td colspan="2">
                    @include('_parts.menu_auth')
                </td>
            </tr>
        </table>
        {{-- Menu setting end --}}

        {{--Accordion basic infos begin--}}
        <table class="accordion-table">
            <colgroup>
                <col width="30%"/>
                <col width="60%"/>
                <col width="10%"/>
            </colgroup>
            <tr>
                <td colspan="2"><b>{{$lan::get('basic_info_title')}}</b></td>
                <td class="drop_down" data-toggle="collapse" href="#collapse1"><i style="font-size:16px;" class="glyphicon glyphicon-plus" style="width: 10%"></i></td>
            </tr>
        </table>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="dialog-table" id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    <tr>
                        <td class="t6_td1">{{$lan::get('birthday_title')}}</td>
                        <td class="t4td2">
                            <input type="text" name="birth_date" class="text_m" id="dp_birth_date" value="{{request('birth_date')}}"
                                   placeholder="{{$lan::get('birthday_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('gender_title')}}</td>
                        <td class="t4td2">
                            <input type="radio" name="gender" value="1" id="1" @if(!request()->has('gender') || request('gender')==1)checked @endif/>
                            <label for="male">{{$lan::get('man_title')}}</label>
                            <input type="radio" name="gender" value="2" id="2" @if( request('gender')==2)checked @endif/>
                            <label for="female">{{$lan::get('woman_title')}}</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('note_title')}}</td>
                        <td class="t4td2">
                            <textarea name="note" cols="50" rows="8" placeholder="{{$lan::get('note_title')}}{{$lan::get('placeholder_please_input')}}">{{old('note',request('note'))}}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>{{$lan::get('education_background_title')}}</b></td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('last_education_background_title')}}</td>
                        <td class="t4td2">
                            <input class="text_m" type="text" name="highest_education" value="{{old('highest_education',request('highest_education'))}}"
                                   placeholder="{{$lan::get('last_education_background_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('graduate_year_title')}}</td>
                        <td class="t4td2">
                            <input class="text_m" type="text" id="dp_graduate_date" name="graduate_date" value="{{old('graduate_date', request('graduate_date'))}}"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>{{$lan::get('recruitment_info_title')}}</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('recruitment_year_title')}}</td>
                        <td class="t4td2">
                            <input class="text_m" type="text" id="dp_employment_date" name="employment_date" value="{{old('employment_date', request('employment_date'))}}"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('employment_status_title')}}</td>
                        <td class="t4td2">
                            <input type="radio" name="teacher_type" value="1" id="male" @if(old('teacher_type', request('teacher_type')) ==1)checked @endif/>
                            <label for="employee">{{$lan::get('employer_title')}}</label>
                            <input type="radio" name="teacher_type" value="2" @if(old('teacher_type', request('teacher_type'))==2)checked @endif/>
                            <label for="contract">{{$lan::get('contract_employer_title')}}</label>
                            <input type="radio" name="teacher_type" value="3" @if(old('teacher_type', request('teacher_type'))==3)checked @endif/>
                            <label for="part_time">{{$lan::get('parttime_employer_title')}}</label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        {{--Accordion basic infos end--}}

        {{--Accordion address1 begin--}}
        <table class="accordion-table">
            <colgroup>
                <col width="30%"/>
                <col width="60%"/>
                <col width="10%"/>
            </colgroup>
            <tr>
                <td colspan="2"><b>{{$lan::get('address_1_title')}}</b></td>
                <td class="drop_down" data-toggle="collapse" href="#collapse2"><i style="font-size:16px;" class="glyphicon glyphicon-plus" style="width: 10%"></i></td>
            </tr>
        </table>
        <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="dialog-table" id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    <tr>
                        <td class="t6_td1">{{$lan::get('postal_code_title')}}</td>
                        <td class="t4td2">
                        &#12306;&nbsp;<input class="text_ss" type="text" name="address1_zip1" value="{{old('address1_zip1', request('address1_zip1'))}}"
                                             pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;－
                        <input class="text_ss" type="text" name="address1_zip2" value="{{old('address1_zip2', request('address1_zip2'))}}"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&nbsp;
                            <button type="button" id="generateAddress">{{$lan::get('generate_address')}}</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('province_city_title')}}</td>
                        <td class="t4td2">
                        <select name="address1_pref_id" id="address1_pref" style="width:200px">
                            <option value=""></option>
                            @foreach($pref_data as $item)
                                <option value="{{$item->id}}" @if ($item->id == old('address1_pref_id', request('address1_pref_id'))) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('district_title')}}</td>
                        <td class="t4td2">
                        <select name="address1_city_id" id="address1_city" style="width:200px">
                            <option value=""></option>
                            @foreach($address1_city_data as $item)
                                <option value="{{$item->id}}" @if ($item->id == old('address1_city_id', request('address1_city_id'))) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('address_title')}}</td>
                        <td class="t4td2">
                            <input class="text_l" type="text" name="address1_address" value="{{old('address1_address', request('address1_address'))}}"
                                   placeholder="{{$lan::get('address_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('building_title')}}</td>
                        <td class="t4td2">
                            <input class="text_l" type="text" name="address1_building" value="{{old('address1_building', request('address1_building'))}}"
                                   placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        </td>
                    </tr>
                    {{--<tr>--}}
                        {{--<td class="t6_td1">{{$lan::get('phone_number_title')}}</td>--}}
                        {{--<td class="t4td2">--}}
                            {{--<input class="text_m" type="text" name="address1_phone" value="{{old('address1_phone', request('address1_phone'))}}"--}}
                                   {{--placeholder="{{$lan::get('phone_number_title')}}{{$lan::get('placeholder_please_input')}}"/>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                </table>
            </div>
        </div>
        {{--Accordion address1 begin--}}

        {{--Accordion address2 begin--}}
        <table class="accordion-table">
            <colgroup>
                <col width="30%"/>
                <col width="60%"/>
                <col width="10%"/>
            </colgroup>
            <tr>
                <td colspan="2"><b>{{$lan::get('address_2_title')}}</b></td>
                <td class="drop_down" data-toggle="collapse" href="#collapse3"><i style="font-size:16px;" class="glyphicon glyphicon-plus" style="width: 10%"></i></td>
            </tr>
        </table>
        <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="dialog-table" id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    <tr>
                        <td class="t6_td1">{{$lan::get('postal_code_title')}}</td>
                        <td class="t4td2">
                            &#12306;&nbsp;<input class="text_ss" type="text" name="address2_zip1" value="{{old('address2_zip1', request('address2_zip1'))}}"/>&nbsp;－
                            <input class="text_ss" type="text" name="address2_zip2" value="{{old('address2_zip2', request('address2_zip2'))}}"/>&nbsp;&nbsp;
                            <button type="button" id="generateAddress2">{{$lan::get('generate_address')}}</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('province_city_title')}}</td>
                        <td class="t4td2">
                            <select name="address2_pref_id" id="address2_pref" style="width:200px">
                                <option value=""></option>
                                @foreach($pref_data as $item)
                                    <option value="{{$item->id}}" @if ($item->id == old('address2_pref_id', request('address2_pref_id'))) selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('district_title')}}</td>
                        <td class="t4td2">
                            <select name="address2_city_id" id="address2_city" style="width:200px">
                                <option value=""></option>
                                @foreach($address2_city_data as $item)
                                    <option value="{{$item->id}}" @if ($item->id == old('address2_city_id', request('address2_city_id'))) selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('address_title')}}</td>
                        <td class="t4td2">
                            <input class="text_l" type="text" name="address2_address" value="{{old('address2_address', request('address2_address'))}}"
                                   placeholder="{{$lan::get('address_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('building_title')}}</td>
                        <td class="t4td2">
                            <input class="text_l" type="text" name="address2_building" value="{{old('address2_building', request('address2_building'))}}"
                                   placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="t6_td1">{{$lan::get('phone_number_title')}}</td>
                        <td class="t4td2">
                            <input class="text_m" type="text" name="address2_phone" value="{{old('address2_phone', request('address2_phone'))}}"
                                   placeholder="{{$lan::get('phone_number_title')}}{{$lan::get('placeholder_please_input')}}"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        {{--Accordion address2 end--}}

        {{--Accordion schedule begin--}}
        {{--<table class="accordion-table">--}}
            {{--<colgroup>--}}
                {{--<col width="30%"/>--}}
                {{--<col width="60%"/>--}}
                {{--<col width="10%"/>--}}
            {{--</colgroup>--}}
            {{--<tr>--}}
                {{--<td colspan="2"><b>{{$lan::get('schedule_title')}}</b></td>--}}
                {{--<td>--}}
                {{--<td class="drop_down" data-toggle="collapse" href="#collapse4">--}}
                    {{--<i style="font-size:16px;" class="glyphicon glyphicon-plus" style="width: 10%"></i>--}}
                {{--</td>--}}
            {{--</tr>--}}
        {{--</table>--}}
        {{--<div id="collapse4" class="panel-collapse collapse">--}}
            {{--<div class="panel-body">--}}
                {{--<table class="dialog-table" id="table6">--}}
                    {{--<colgroup>--}}
                        {{--<col width="30%"/>--}}
                        {{--<col width="70%"/>--}}
                    {{--</colgroup>--}}
                {{--</table>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--Accordion schedule end--}}

        {{-- Register button--}}
        @if(request()->has('id'))
            <div class="div-btn">
                <ul>
                    <!-- <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-save"></i>{{$lan::get('edit_title')}}</li></a> -->
                    <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-floppy-disk"></i>{{$lan::get('register_title')}}</li></a>
                    <a href="" class="button" id="btn_back"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-circle-arrow-left"></i>{{$lan::get('back_btn')}}</li></a>
                </ul>
            </div>
        @else
            <div class="div-btn">
                <ul>
                    <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal; "><i class="glyphicon glyphicon-floppy-disk"></i> {{$lan::get('register_title')}}</li></a>
                    <a href="" class="button" id="btn_back"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-circle-arrow-left"></i> {{$lan::get('back_btn')}}</li></a>
                </ul>
            </div>
        @endif
    </form>
    <div id="dialog_active" class="no_title" style="display:none;">
        {{$lan::get('message_save_confirm')}}
    </div>
</div>

{{--HTML content end--}}


{{--JS content begin--}}
<script type="text/javascript">
    var currentYear = new Date().getFullYear();
    var currentMonth = new Date().getMonth()+1;
    var currentDate = new Date().getDate();

    var defaultDate = currentYear - 25 +"-" + currentMonth + "-" + currentDate;
//console.log(defaultDate);
    $(function(){

        //set pref and city button
        $("#generateAddress").click(function (){
            var zipcode = $("input[name=address1_zip1]").val()+$("input[name=address1_zip2]").val();
            var _token = "{{csrf_token()}}";

            $.ajax({
                type: "post",
                url: "/school/parent/get_address_from_zipcode",
                data: {zipcode: zipcode,_token: _token},
                dataType:'json',
                success: function(data) {
                    if(data.status == true){
                        var info = data.message;
                        $("#address1_pref").val(info.pref_id);
                        setCity(info.pref_id, info.city_id);
                        $("input[name=address1_address]").val(info.address);
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

                    $('#address1_city').html(html);
                    $("#address1_city").val(city_id);

                },
                error: function (data) {

                },
            });
        }
        //
        $("#generateAddress2").click(function (){
            var zipcode = $("input[name=address2_zip1]").val()+$("input[name=address2_zip2]").val();
            var _token = "{{csrf_token()}}";

            $.ajax({
                type: "post",
                url: "/school/parent/get_address_from_zipcode",
                data: {zipcode: zipcode,_token: _token},
                dataType:'json',
                success: function(data) {
                    if(data.status == true){
                        var info = data.message;
                        $("#address2_pref").val(info.pref_id);
                        setCity2(info.pref_id, info.city_id);
                        $("input[name=address2_address]").val(info.address);
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

                    $('#address2_city').html(html);
                    $("#address2_city").val(city_id);

                },
                error: function (data) {

                },
            });
        }
        //
        $('#img_review').attr('src', $('#img_review').attr('src1'));

        // date picker Local setting
        var d = new Date();
        $.datetimepicker.setLocale('ja');
        // 生年月日
        $('#dp_birth_date').val(defaultDate); // Set default birth_day to 25 years before
        $('#dp_birth_date').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            startDate: defaultDate,
            defaultDate: defaultDate,
            timepicker:false,
            scrollMonth : false,
            scrollInput : false
        });

        // 卒業年月
        $('#dp_graduate_date').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            timepicker:false,
            scrollMonth : false,
            scrollInput : false
        });

        // 採用年月日
        $('#dp_employment_date').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            timepicker:false,
            scrollMonth : false,
            scrollInput : false
        });

        $(".drop_down").click(function(e){
            e.preventDefault();
            if($(this).children().hasClass("glyphicon glyphicon-plus")){
                $(this).children().removeClass("glyphicon glyphicon-plus");
                $(this).children().addClass("glyphicon glyphicon-minus");
            }else if($(this).children().hasClass("glyphicon glyphicon-minus")){
                $(this).children().removeClass("glyphicon glyphicon-minus");
                $(this).children().addClass("glyphicon glyphicon-plus");
            }
        });

        /* 講師住所１の都道府県 */
        $("#address1_pref").change(function () {
            var pref_cd = $(this).val();
            if (pref_cd == "") {
                $("#address1_city option").remove();
                $("#address1_city").prepend($("<option>").html("").val(""));
                return;
            }

            $.ajax({
                type: "get",
                dataType: "json",
                url: "/school/coach/getCityDataByPrefId",
                data: {pref_id: pref_cd},
                success: function (data) {
                    data = JSON.stringify(data);
                    data = JSON.parse(data);
                    var html = "<option value=''></option>";
                    for (id in data) {
                        var html = html + "<option value=" + id + ">" + data[id] + "</option>";
                    }

                    $('#address1_city').html(html);
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });

        /* 講師住所2の都道府県 */
        $("#address2_pref").change(function () {
            var pref_cd = $(this).val();
            if (pref_cd == "") {
                $("#address2_city option").remove();
                $("#address2_city").prepend($("<option>").html("").val(""));
                return;
            }

            $.ajax({
                type: "get",
                dataType: "json",
                url: "/school/coach/getCityDataByPrefId",
                data: {pref_id: pref_cd},
                success: function (data) {
                    data = JSON.stringify(data);
                    data = JSON.parse(data);
                    var html = "<option value=''></option>";
                    for (id in data) {
                        var html = html + "<option value=" + id + ">" + data[id] + "</option>";
                    }

                    $('#address2_city').html(html);
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });

        // 確認ボタン
        $("#submit2").click(function () {
            $("#dialog_active").dialog('open');
            return false;
        });

        $("#dialog_active").dialog({
            title: '{{$lan::get('main_title')}}',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "{{$lan::get('ok_btn')}}": function () {
                    $(this).dialog("close");
                    $("#entry_form").attr('action', '/school/coach/store');
                    $("#entry_form").submit();
                    return false;
                },
                "{{$lan::get('cancel_title')}}": function () {
                    $(this).dialog("close");
                    return false;
                }
            }
        });

        $('#btn_back').click(function (event) {
            event.preventDefault();
            $id = $('input[name="id"]').val();
            if($id > 0) {
                java_post("{{$_app_path}}coach/detail?id=" + $id);
            } else {
                java_post("{{$_app_path}}coach");
            }
            return false;
        });

        {{-- ENTERキーが入力されたら、次のフィールドへフォーカスを移動 --}}
        function nextForm(event)
        {
            if (event.keyCode == 0x0d)
            {
                var current = document.activeElement;

                var forcus = 0;
                for( var idx = 0; idx < document.action_form.elements.length; idx++){
                    if( document.action_form[idx] == current ){
                        forcus = idx;
                        break;
                    }
                }
                document.action_form[(forcus + 1)].focus();
            }
        }
        window.document.onkeydown = nextForm;

    });

{{-- ここまで --}}
</script>
<style>
    #generateAddress:hover, .div-btn li:hover, #generateAddress2:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
        text-shadow: 0 0px #FFF;
    }
    .div-btn li, #generateAddress, #generateAddress2 {
        color: #595959;
        height: 30px;
        border-radius: 5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        /*font-size: 14px;*/
        font-weight: normal;
        text-shadow: 0 0px #FFF;
    }
</style>
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
{{--JS content end--}}
@stop