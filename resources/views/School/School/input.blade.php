@extends('_parts.master_layout')
@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
    <script type="text/javascript">
        $(function () {
            $("#pref_id").change(function () {

                var pref_cd = $(this).val();
                if (pref_cd == "") {
                    $("#city_id option").remove();
                    $("#city_id").prepend($("<option>").html("").val(""));
                    return;
                }
                $.ajax({

                    type: "get",
                    dataType: "json",
                    url: "/school/ajaxSchool/city",
                    data: {pref_cd: pref_cd},
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


                    },
                    error: function (data) {
                        alert('error');
                        console.log(data);
                    },
                });

            });

            $("#base_name").change(function () {

                var consignor_id = $(this).val();

                $.ajax({

                    type: "get",
                    dataType: "json",
                    url: "/school/ajaxSchool/consignor",
                    data: {consignor_id: consignor_id},
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        data = JSON.stringify(data);
                        data = JSON.parse(data);

                        var result = data['consignorList'][0];
                        for (x in result) {
                            var html = html + "<option value=" + result[x] + ">" + result[x] + "</option>";
                        }
                        //$('#consignor_code').val(data['consignorList'][0]['consignor_code']);
                        //$('#consignor_name').val(data['consignorList'][0]['consignor_name']);
                        $('#withdrawal_day').html(html);


                    },
                    error: function (data) {
                        console.log(data);
                    },
                });

            });


            $("#btn_return").click(function () {
                $("#action_form").attr('action', '/school/school');
                $("#action_form").submit();
            });

            $("#btn_submit").click(function () {
                var title = '{{$lan::get('main_title')}}';
                var content = '{{$lan::get('dialog_confirm_message')}}';
                var action_url = '{{$_app_path}}school/complete';
                common_save_confirm(title, content,action_url);
                return false;
            });

            $("#numinput").keypress(function (e) {
                if ((e.which >= 48) && (e.which <= 57))        return true;	// "0" ～ "9"
                if (e.which == 8)        return true;						// BS
                if (e.which == 0)        return true;						// NULL
                return false;
                ;
            });
            $("#banktype").change(function () {
                var type = $("#banktype").val();
                if (type == 1) {			// 銀行
                    $("#postinfo").hide();
                    $("#bankinfo").show();
                }
                else if (type == 2) {
                    $("#bankinfo").hide();
                    $("#postinfo").show();
                }
            });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.imagePreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#kakuin_choose").change(function () {
                readURL(this);
            });

            //set pref and city button
            $("#generateAddress").click(function (){
                var zipcode = $("input[name=zip_code1]").val()+$("input[name=zip_code2]").val();
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
                            setCity(info.pref_id, info.city_id);
                            $("input[name=address]").val(info.address);
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

                        $('#city_id').html(html);
                        $("#city_id").val(city_id);

                    },
                    error: function (data) {

                    },
                });
            }
        });
    </script>

    <style>
        input[type="text"] {
            width: 350px;
        }
        .input_text{
            width:150px !important;
        }
        input[type="password"] {
            width: 250px;
        }

        .imagePreview {
            max-width: 64px;
            max-height: 64px;
        }

        .remove_prefix {
            cursor: pointer;
        }

        .content_bank {
            margin-top: 5px;
        }

        .add_bank, .edit_bank, .remove_bank, .payment_edit {
            font-size: 14px !important;
        }

        .content_bank {
            width: 100%;
        }

        label {
            font-weight: normal;
        }
        .div-btn li, #generateAddress, .remove_prefix, #btn_add_row, .submit2, #btn_add_bank {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
        .div-btn li:hover, input[type="button"]:hover, #generateAddress:hover, .remove_prefix:hover, #btn_add_row:hover, .submit2:hover, #btn_add_bank:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        input[type="button"] {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
    </style>

    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-university"></i> {{$lan::get('main_title')}}</h2>
        <div class="center_content_header_right">
            <div class="top_btn">
                {{--<ul>
                     <li><a href="{{$_app_path}}school/inputindiv">{{$lan::get('edit_individual_info_title')}}</a></li>
                </ul>--}}
            </div>
        </div>
        <div class="clr"></div>
    </div>

    {{--@include('_parts.topic_list')--}}
    <h3 id="content_h3" class="box_border1">{{$lan::get('detail_info_setting_title')}}</h3>

    <div id="section_content1">
        @if(count($errors))
            <ul class="message_area">
                @foreach($errors->all() as $error)
                    <li class="error_message">{{$lan::get($error)}}</li>
                @endforeach
            </ul>
        @endif

        @if( isset($regist_error))
            <ul class="message_area">
                <li class="error_message">{{$lan::get('failed_update_error_title')}}</li>
            </ul>
        @endif

        @if( isset($regist_message))
            <ul class="message_area">
                <li class="info_message">{{$lan::get('update_success_title')}}</li>
            </ul>
        @endif

        <span class="aster">&lowast;</span>{{$lan::get('mandatory_items_title')}}
        <h4>{{$lan::get('school_info_title')}}</h4>

        <form action="#" method="post" id="action_form" name="action_form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$request['id']}}"/>
            <table id="table6">
                <colgroup>
                    <col width="30%"/>
                    <col width="70%"/>
                </colgroup>

                <tr>
                    <td class="t6_td1">{{$lan::get('school_name_title')}}</td>
                    <td class="t4td2">
                        {{array_get($request, 'name')}}
                        <input type="hidden" name="name" value="{{array_get($request, 'name')}}" class="l_text"/>
                    </td>
                </tr>

                <tr>
                    <td class="t6_td1">{{$lan::get('school_code_title')}}</td>
                    <td class="schoop_input_left">
                        {{array_get($request, 'pschool_code')}}
                        <input type="hidden" name="pschool_code" value="{{array_get($request, 'pschool_code')}}"
                               class="l_text"/>
                    </td>

                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('representative_name_title')}}</td>
                    <td>
                        <input type="text" name="daihyou" value="{{array_get($request, 'daihyou')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('official_position_title')}}</td>
                    <td>
                        <input type="text" name="official_position" value="{{array_get($request, 'official_position')}}"/>
                    </td>
                </tr>
                <tr>
                    <td>{{$lan::get('prefix_code_title')}}</td>
                    {{--<td>{{$lan::get('prefix_code_title')}}<span class="aster">*</span></td>--}}
                    <td>
                        <div class="input_prefix">
                            @if(!isset($request->prefix_code))
                                <span id="prefix_clone_0" class="prefix_clone">
										<input class="clone_input" type="text"
                                               style="width: 65px!important;margin-top: 5px;margin-bottom: 5px;">
										<!-- <input type="button" class="remove_prefix"
                                               value="{{$lan::get('remove_title')}}"/> -->
                                        <button class="remove_prefix" type="button" style="font-size: 11px !important;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('remove_title')}}</button>
									</span>
                            @else
                                @php
                                    $arr_prefix = explode("-",$request->prefix_code);
                                @endphp
                                @foreach($arr_prefix as $key=>$code)
                                    <span id="prefix_clone_{{$key}}" class="prefix_clone">
											<input class="clone_input" type="text"
                                                   style="width: 65px!important;margin-top: 5px;margin-bottom: 5px;"
                                                   value="{{$code}}">
											<!-- <input type="button" class="remove_prefix"
                                                   value="{{$lan::get('remove_title')}}"/> -->
                                            <button class="remove_prefix" type="button" style="font-size: 11px !important;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('remove_title')}}</button>
										</span>
                                @endforeach
                            @endif
                        </div>
                        <!-- <input type="button" value="{{$lan::get('add_title')}}" id="btn_add_row"/> -->
                        <button id="btn_add_row" type="button" style="font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_title')}}</button><br>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <p id="prefix_code">@if(isset($request->prefix_code)){{$request->prefix_code}} @else
                                &nbsp; @endif</p>
                        <input type="hidden" name="prefix_code"
                               value="@if(isset($request->prefix_code)){{$request->prefix_code}} @endif">
                    </td>
                </tr>
            </table>

            <h4>{{$lan::get('login_info_title')}}</h4>
            <table id="table6">
                <colgroup>
                    <col width="30%"/>
                    <col width="70%"/>
                </colgroup>

                <tr>
                    <td class="t6_td1">
                        {{$lan::get('account_id_title')}}
                    </td>
                    <td>
                        {{array_get($request, '_login_id')}}
                        <input type="hidden" name="_login_id" value="{{array_get($request, '_login_id')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('password_title')}}
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="password" name="_login_pw"
                               value="{{array_get($request, '_login_pw')}}" class="l_text"/>
                        <br/>
                        <span class="col_msg"><b>※{{$lan::get('input_change_title')}}</b></span>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('password_confirm_title')}}
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="password" name="_login_pw_c"
                               value="{{array_get($request, '_login_pw_c')}}" class="l_text"/>
                        <br/>
                        <span class="col_msg"><b>※{{$lan::get('input_change_title')}}</b></span>
                    </td>
                </tr>
            </table>

            <h4>{{$lan::get('school_detail_info_title')}}</h4>
            <table id="table6">
                <colgroup>
                    <col width="30%"/>
                    <col width="70%"/>
                </colgroup>
                <tr>
                    <td class="t6_td1">{{$lan::get('postal_code_title')}}</td>
                    <td>
                        &#12306;&nbsp;
                        <input class="text_ss" style="width:45px;ime-mode:inactive;" type="text" name="zip_code1"
                               value="{{array_get($request, 'zip_code1')}}" maxlength="3"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&ndash;
                        <input class="text_ss" style="width:60px;ime-mode:inactive;" type="text" name="zip_code2"
                               value="{{array_get($request, 'zip_code2')}}" maxlength="4"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&nbsp;
                        <button type="button" id="generateAddress">{{$lan::get('generate_address')}}</button>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('city_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <select name="pref_id" id="pref_id">
                            <option value=""></option>
                            @if(isset($pref_list))
                                @foreach ($pref_list as $key => $pref)
                                    <option value="{{$pref['id']}}"
                                            @if(array_get($request, 'pref_id') ==	$pref['id']) selected>{{$pref['name']}}</option> @endif
                                    <option value="{{$key}}">{{$pref['name']}}</option> @endforeach
                            @endif
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('district_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <select name="city_id" id="city_id">
                            <option value=""></option>
                            @foreach ($city_list as $key => $city)
                                <option value="{{$city['id']}}"
                                        @if( array_get($request, 'city_id') ==	$city['id']) selected>{{$city['name']}}</option> @endif
                                <option value="{{$key}}">{{$city['name']}}</option> @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('address2_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:active;" type="text" name="address"
                               value="{{array_get($request, 'address')}}" class="l_text"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('building_title')}}

                    </td>
                    <td>
                        <input style="ime-mode:active;" type="text" name="building"
                               value="{{array_get($request, 'building')}}" class="l_text"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('phone_number_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="tel" value="{{array_get($request, 'tel')}}" class="l_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('fax_title')}}
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="fax" value="{{array_get($request, 'fax')}}" class="l_text"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('email_address_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td style="padding-left: 0px;">
                                    <input style="ime-mode:inactive;" type="text" name="mailaddress"
                                           value="{{array_get($request, 'mailaddress')}}" class="l_text"/>
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
                        <input style="ime-mode:inactive;" type="text" name="web" value="{{array_get($request, 'web')}}"
                               class="l_text"/>
                    </td>
                </tr>

                <tr>
                    <td class="t6_td1">
                        {{$lan::get('amount_display_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        @if(isset($amt_disp_type_list))
                            @foreach ($amt_disp_type_list as $amt_disp_type_id => $amt_disp_type)
                                <input type="radio" name="amount_display_type" value="{{$amt_disp_type_id}}"
                                       id="r_amt_disp_type_{{$amt_disp_type_id}}"
                                       @if( array_get($request, 'amount_display_type') == $amt_disp_type_id) checked/> @endif
                                <label for="r_amt_disp_type_{{$amt_disp_type_id}}" style="font-weight: normal !important;">{{$amt_disp_type}}</label>
                            @endforeach
                        @endif
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('tax_rate_title')}}
                        <span class="aster">*</span>
                    </td>

                    <td>
                        <input style="ime-mode:inactive;" type="text" name="sales_tax_rate" placeholder="[0.xxxx]で入力"
                               value="@if( !array_get($request, 'sales_tax_rate')) 0.08 @else {{array_get($request, 'sales_tax_rate')}} @endif"
                               class="l_text"/>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('tuition_payment_form_title')}}</td>
                    <td>
                        <select name="payment_style">
                            @if(isset($payment_style_list))
                                @foreach ($payment_style_list as $key => $payment)
                                    <option value="{{$key}}" @if( array_get($request, 'payment_style') == $key)
                                    selected @endif>{{$payment}}</option>

                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('currency_title')}}</td>
                    <td>
                        <select name="currency">
                            @foreach ($currencies as $key => $currency)
                                <option value="{{$key}}"
                                        @if( array_get($request, 'currency') == $key) selected @endif>{{$currency}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('currency_decimal_point')}}
                    </td>
                    <td>
                        <input style="ime-mode:inactive;" type="text" name="currency_decimal_point" placeholder=""
                               value="@if(!array_get($request, 'currency_decimal_point')) 0 @else {{array_get($request, 'currency_decimal_point')}} @endif"
                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('request_deadline_day_title')}}
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td width="90px !important" style="text-align: right">
                                    {{$lan::get('every_month_title')}}
                                </td>
                                <td>
                                    <select name="invoice_closing_date">
                                        @if(isset($close_date_list))
                                            @foreach ($close_date_list as $close_date_id => $close_date)
                                                <option value="{{$close_date_id}}"
                                                        @if(array_get($request, 'invoice_closing_date') == $close_date_id) selected @endif>{{$close_date}}</option>

                                            @endforeach
                                        @endif
                                    </select>
                                    &nbsp;{{$lan::get('day_title')}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('request_payment_day_title')}}
                        <span class="aster">*</span>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td width="90px !important">
                                    <select name="payment_month" dir="rtl">
                                        <option value="0" @if(array_get($request, 'payment_month') == 0) selected @endif>{{$lan::get('payment_this_month')}} </option>
                                        <option value="1" @if(array_get($request, 'payment_month') == 1) selected @endif>{{$lan::get('payment_next_month')}}</option>
                                        <option value="2" @if(array_get($request, 'payment_month') == 2) selected @endif>{{$lan::get('payment_second_following_month')}}</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="payment_date">
                                        <option value=""></option>
                                        @if(isset($invoice_date_list))
                                            @foreach ($invoice_date_list as $invoice_date_id => $invoice_date)
                                                <option value="{{$invoice_date_id}}"
                                                        @if( array_get($request, 'payment_date') == $invoice_date_id) selected @endif>{{$invoice_date}}</option>

                                            @endforeach
                                        @endif
                                    </select>
                                    &nbsp;{{$lan::get('day_title')}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('invoice_batch_title')}}
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td width="90px !important" style="text-align: right">
                                    {{$lan::get('every_month_title')}}&nbsp;
                                </td>
                                <td>
                                    <select name="invoice_batch_date">
                                        @if(isset($invoice_date_list))
                                            @foreach ($invoice_date_list as $invoice_date_id => $invoice_date)
                                                <option value="{{$invoice_date_id}}"
                                                        @if( array_get($request, 'invoice_batch_date') == $invoice_date_id) selected @endif>{{$invoice_date}}</option>

                                            @endforeach
                                        @endif
                                    </select>
                                    &nbsp;{{$lan::get('day_title')}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="t6_td1">
                        {{$lan::get('price_setting_title_new')}}
                    </td>
                    <td>
                        <label style="font-weight: normal !important;"><input type="radio" name="price_setting_type"
                                      @if(array_get($request,'price_setting_type')==1)checked
                                      @endif value="1">{{$lan::get('price_setting_type_1')}}</label> &nbsp;&nbsp;&nbsp;&nbsp;
                        <label style="font-weight: normal !important;"><input type="radio" name="price_setting_type"
                                      @if(array_get($request,'price_setting_type')==2)checked
                                      @endif value="2">{{$lan::get('price_setting_type_2')}}</label>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('company_kakuin_path')}}</td>
                    <td>
                        <div><img src="@if(isset($request->kakuin_path)){{array_get($request,'kakuin_path')}}@endif"
                                  class="imagePreview"/></div>
                        <input type="file" name="kakuin_path" class="imgInput" id="kakuin_choose"></td>
                    @if(isset($request->kakuin_path))<input type="hidden" name="kakuin_path_curr"
                                                            value="{{array_get($request,'kakuin_path')}}">
                    @endif
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('proviso')}}</td>
                    <td>
                        <input type="text" style="ime-mode:active;" name="proviso" value="{{array_get($request, 'proviso')}}">
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('require_password_when_process_deposit')}}</td>
                    <td>
                        <label><input type = "radio" name="nyukin_pass_required" value ="1" @if(array_get($request, 'nyukin_pass_required')== 1) checked @endif>{{$lan::get('do_title')}}</label>
                        <label><input type = "radio" name="nyukin_pass_required" value ="0" @if(array_get($request, 'nyukin_pass_required')!= 1) checked @endif>{{$lan::get('not_do_title')}}</label>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('deposit_default_search_invoice_year_month')}}</td>
                    <td>
                        <select name="nyukin_before_month">
                            <option value=""></option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{$i}}" @if(array_get($request, 'nyukin_before_month') == $i) selected @endif>{{$i}}</option>
                            @endfor
                        </select>
                        {{$lan::get('before_month_title')}}
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('show_number_corporation_title')}}</td>
                    <td>
                        <label><input type = "radio" name="show_number_corporation" value ="1" @if(array_get($request, 'show_number_corporation')== 1) checked @endif>{{$lan::get('do_title')}}</label>
                        <label><input type = "radio" name="show_number_corporation" value ="0" @if(array_get($request, 'show_number_corporation')!= 1) checked @endif>{{$lan::get('not_do_title')}}</label>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('check_zip_csv_title')}}</td>
                    <td>
                        <table>
                            <tr>
                                <td style="padding-left: 0px;">
                                    <label><input type = "radio" name="is_zip_csv" value ="1" @if(array_get($request, 'is_zip_csv')== 1) checked @endif>{{$lan::get('do_title')}}</label>
                                    <label><input type = "radio" name="is_zip_csv" value ="0" @if(array_get($request, 'is_zip_csv')!= 1) checked @endif>{{$lan::get('not_do_title')}}</label>&nbsp;
                                </td>
                                <td style="padding-left: 100px;">
                                    <b>{{$lan::get('check_zip_csv_note')}}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{--new bank account--}}
            <h4>{{$lan::get('account_information')}}</h4>
                <div >
                    <table id="table6">
                        <colgroup>
                            <col width="6%"></col>
                            <col width="10%"></col>
                            <col width="10%"></col>
                            <col width="10%"></col>
                            <col width="10%"></col>
                            <col width="10%"></col>
                        </colgroup>

                        <tr>
                            <th style="text-align:center">{{$lan::get('default_bank_title')}}</th>
                            <th>{{$lan::get('financial_organizations')}}</th>
                            <th>{{$lan::get('preview_bank_name_title')}}</th>
                            <th>{{$lan::get('preview_branch_name_title')}}</th>
                            <th>{{$lan::get('preview_account_number')}}</th>
                            <th></th>
                        </tr>

                    <tbody class="content_bank_account">
                    @foreach($bank_data as $key=>$bank)
                        @if(array_get($bank,'bank_type')==1)
                            <tr id="content_bank_{{$key}}" class="content_bank">
                                <td style="text-align: center" >
                                    <label style="font-weight: normal !important;"><input type="radio" data-bank_id="{{array_get($bank,'id')}}"
                                                  class="default_account"
                                                  @if(array_get($bank,'is_default_account')==1) checked @endif />
                                    </label>
                                </td>
                                <td><form><label style="font-weight: normal !important;"><input type="radio" name="_bank_type" value="1" checked>{{$lan::get('new_bank_title')}}
                                        </label>
                                        <label style="font-weight: normal !important;"><input type="radio" name="_bank_type"
                                                      value="2">{{$lan::get('post_title')}}
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <input type="text" class="input_text" disabled
                                           value="{{array_get($bank,'bank_name')}}">&nbsp;
                                </td>
                                <td>
                                    <input type="text" class="input_text" disabled
                                           value="{{array_get($bank,'branch_name')}}">&nbsp;
                                </td>
                                <td>
                                    <input type="text" class="input_text" disabled
                                           value="{{array_get($bank,'bank_account_number')}}">
                                </td>
                                <td>
                                    <input type="button" data-bank_id="{{$key}}" class="edit_bank"
                                           value="{{$lan::get('edit_title')}}">&nbsp;
                                    <input type="button" data-bank_id="{{array_get($bank,'id')}}"
                                           class="remove_bank" value="{{$lan::get('delete_title')}}">
                                </td>

                            </tr>
                        @else
                            <tr id="content_bank_{{$key}}" class="content_bank">
                                <td style="text-align: center">
                                    <label style="font-weight: normal !important;"><input type="radio" data-bank_id="{{array_get($bank,'id')}}"
                                                  class="default_account"
                                                  @if(array_get($bank,'is_default_account')==1) checked @endif/>
                                    </label>
                                </td>
                                <td><form><label style="font-weight: normal !important;"><input type="radio" name="_bank_type" value="1">{{$lan::get('new_bank_title')}}</label>
                                        <label style="font-weight: normal !important;"><input type="radio" name="_bank_type" value="2"
                                                      checked>{{$lan::get('post_title')}}</label>
                                    </form>
                                </td>
                                @if(array_get($bank,'post_account_type') == 1)
                                    <td>
                                        <input type="text" class="input_text" disabled
                                               value="{{array_get($bank,'post_account_kigou')}}">&nbsp;
                                    </td>
                                    <td>
                                        <input type="text" class="input_text" disabled
                                               value="">&nbsp;
                                    </td>
                                    <td>
                                        <input type="text" class="input_text" disabled
                                               value="{{array_get($bank,'post_account_number')}}">&nbsp;

                                    </td>
                                @else
                                    <td>
                                        <input type="text" class="input_text" disabled
                                               value="{{array_get($bank,'post_account_kigou')}}">&nbsp;
                                    </td>
                                    <td>
                                        <input type="text" class="input_text" disabled
                                               value="{{substr(array_get($bank,'post_account_number'),0,1)}}">&nbsp;
                                    </td>
                                    <td>
                                        <input type="text" class="input_text" disabled
                                               value="{{substr(array_get($bank,'post_account_number'),1,strlen(array_get($bank,'post_account_number')))}}">&nbsp;

                                    </td>
                                @endif
                                <td>
                                    <input type="button" data-bank_id="{{$key}}" class="edit_bank"
                                           value="{{$lan::get('edit_title')}}">&nbsp;
                                    <input type="button" data-bank_id="{{array_get($bank,'id')}}"
                                           class="remove_bank" value="{{$lan::get('delete_title')}}">
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    </table>
                    <!-- <input type="button" value="add_title" id="btn_add_bank"> -->
                    <button id="btn_add_bank" type="button" style="font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('add_title')}}</button>
                </div>
            {{--list payment method--}}
            <h4>{{$lan::get('payment_method_title')}}</h4>
            <table id="table6" class="table_payment_methods">
                <colgroup>
                    <col width="20%"/>
                    <col width="40%"/>
                    <col width="40%"/>
                </colgroup>
                @foreach($list_payment_method as $key=>$payment)
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" data-agency_id="{{$payment['payment_agency_id']}}"
                                       class="set_payment_method" name="payment_method_id" value="{{$payment['id']}}"
                                       @if(isset($payment['default'])) checked @endif>{{$payment['name']}}
                            </label>
                        </td>
                        {{--@if($payment['code']!='CASH' && $payment['code']!='TRAN_BANK')--}}
                        @if($payment['code']=='CASH')
                            <td></td>
                            <td></td>
                        @elseif($payment['code']=='TRAN_BANK')
                            <td></td>
                            <td>
                                <input type="button" class="payment_edit" data-payment_code="{{$payment['code']}}"
                                       value="{{$lan::get('edit_title')}}">
                            <td>
                        @else
                            <td>
                                <input type="text" disabled value="{{$payment['agency_name']}}">
                            </td>
                            <td>
                                <input type="button" class="payment_edit" data-payment_code="{{$payment['code']}}"
                                       value="{{$lan::get('edit_title')}}">
                            <td>
                        @endif
                    </tr>
                @endforeach
            </table>
            @include('School.School.payment_dialog_template')
            {{--payment end--}}

            {{--<div id="other_agency_info">
                <table id="table6">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                    </colgroup>
                    <tr>
                        <td class="t6_td1">{{$lan::get('other_payment_agency_title')}}</td>
                        <td>
                            <select name="other_payment_agency_id">
                                <option value=""></option>
                                @if(isset($consignor_list))
                                    @foreach($consignor_list as $consignor)
                                        <option value="{{array_get($consignor, 'id')}}"
                                                @if(array_get($request, 'other_payment_agency_id') == array_get($consignor, 'id')) selected="selected" @endif>{{array_get($consignor, 'agency_name')}}</option>
                                    @endforeach @endif
                            </select>
                        </td>
                        <td class="t6_td1"></td>
                    </tr>
                </table>
            </div>--}}
            <br/>
            <div class="exe_button" style="margin-top:10px;">
                <!-- <input type="button" value="{{$lan::get('confirm_title')}}" id="btn_submit" class="submit2"/> -->
                @if($edit_auth)
                    <button id="btn_submit" class="submit2" type="button"><i class="glyphicon glyphicon-floppy-disk " style="width: 20%;font-size:16px;font-weight: normal!important;"></i>登録</button> &nbsp;
                @endif
                <!-- <input type="button" value="{{$lan::get('return_title')}}" id="btn_return" class="submit3"/> -->
                <button id="btn_return" class="submit3" type="button" style="text-shadow: 0 0px #FFF;font-weight: normal !important;"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
                
            </div>
        </form>
    </div>
    <div style="display: none;">
		<span id="prefix_clone">
			<input class="clone_input" type="text" style="width: 65px!important;margin-top: 5px;margin-bottom: 5px;">
			<!-- <input type="button" class="remove_prefix" value="{{$lan::get('remove_title')}}"/> -->
            <button class="remove_prefix" type="button" style="font-size: 11px !important;"><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;"></i>&nbsp; {{$lan::get('remove_title')}}</button>
		</span>
    </div>
   <table style="display: none;">
            <tr id="form_bank_account">
                <td style="text-align: center">
                <input type="radio" class="default_account">
                </td>
                <td>
                <form class="myForm">
                    <label style="font-weight: normal !important;"><input type="radio" value="1" checked>{{$lan::get('new_bank_title')}}</label>
                    <label style="font-weight: normal !important;"><input type="radio" value="2">{{$lan::get('post_title')}}</label>
                </form>
                </td>
                <td><input type="text" class="input_text" disabled value="{{$lan::get('preview_bank_name_title')}}"></td>
                <td><input type="text" class="input_text" disabled value="{{$lan::get('preview_branch_name_title')}}"></td>
                <td><input type="text" class="input_text" disabled value="{{$lan::get('preview_account_number')}}"></td>
                <td><input type="button" class="add_bank" value="{{$lan::get('edit_title')}}">&nbsp;
                <input type="button" class="remove_bank" value="{{$lan::get('delete_title')}}"></td>
            </tr>
    </table>
    @include('School.School.bank_account_dialog')
    <script>
        $(function () {
            function nextForm(event) {
                if (event.keyCode == 0x0d) {
                    var current = document.activeElement;

                    var forcus = 0;
                    for (var idx = 0; idx < document.action_form.elements.length; idx++) {
                        if (document.action_form[idx] == current) {
                            forcus = idx;
                            break;
                        }
                    }
                    document.action_form[(forcus + 1)].focus();
                }
            }

            window.document.onkeydown = nextForm;

            function get_prefix_input() {
                var prefix_complete = "";
                $(".prefix_clone").each(function () {
                    if ($(this).find("input[type='text']").val() != "" && $(this).find("input[type='text']").val() != undefined) {
                        prefix_complete += $(this).find("input[type='text']").val();
                        prefix_complete += "-";
                    }
                })
                prefix_complete = prefix_complete.substr(0, prefix_complete.length - 1)
                $("#prefix_code").text(prefix_complete);
                $("input[name='prefix_code']").val(prefix_complete);
            }

            $("#btn_add_row").click(function () {
                var length = $(".input_prefix").find("input").length;
                var tbl_item = $("#prefix_clone").clone(true).appendTo($(".input_prefix"));
                tbl_item.attr('id', 'prefix_clone_' + length);
                tbl_item.addClass("prefix_clone");
            })

            $(".remove_prefix").click(function () {
                $(this).closest(".prefix_clone").remove();
                get_prefix_input();
            })
            $(".clone_input").keyup(function () {
                get_prefix_input();
            })
        })
    </script>
@stop