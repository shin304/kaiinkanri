@extends('_parts.master_layout')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
    <script type="text/javascript">
        var $bInit = true;

        $(function () {
            /* 生徒住所の都道府県 */
            $("#address_pref, #address_pref_other").change(function () {
                var pref_cd = $(this).val();
                var city_name = ($(this).attr('id') == 'address_pref')? 'address_city' : 'address_city_other';
                if (pref_cd == "") {
                    $("#"+city_name+" option").remove();
                    $("#"+city_name+"").prepend($("<option>").html("").val(""));
                    $("#selecta ddress_city").text("");
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
                        console.log(result);
                        var html = "<option value=''></option>";
                        for (x in result) {
                            var html = html + "<option value=" + x + ">" + result[x] + "</option>";
                        }

                        $("#"+city_name).html(html);


                    },
                    error: function (data) {
                        alert('error');
                        console.log(data);
                    },
                });
            });
            $(".submit3").click(function () {
                @if( isset($request['orgparent_id']))
                    $("#entry_form").attr('action', '/school/parent/detail');
                @else
                    $("#entry_form").attr('action', '/school/parent');
                @endif
                $("#entry_form").submit();
                return false;
            });
// 	$(".submit2").click(function() {
// 		$("#entry_form").attr('action',
// 				'/school/parent/complete');
// 		$("#entry_form").submit();
// 		return false;
// 	});
            $("#submit_return").click(function () {
                $("#frm_return").submit();
            });

            $("#submit2").click(function () {
// 		$("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
// 		$("#action_form").submit();
// 		return false;
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
                    "{{$lan::get('run_title')}}": function () {
                        $(this).dialog("close");
// 				$("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
// 		 		$("#action_form").submit();
                        $("#entry_form").attr('action', '/school/parent/complete');
                        $("#entry_form").submit();

                        return false;
                    },
                    "{{$lan::get('cancel_title')}}": function () {
                        $(this).dialog("close");
                        return false;
                    }
                }
            });

            $("#invoicetype").change(function () {
                var type = $("#invoicetype").val();
                if (type != 2 ) {
                    //$("#mailinfo").val("1");
                    $("#invoiceinfo").hide();
                    $("#bankinfo").hide();
                    $("#postinfo").hide();
                }
                else {
                    //$("#mailinfo").val("0");
                    $("#invoiceinfo").show();
                    @if( isset($request->bank_type) && $request->bank_type == 2)
                        $("#bankinfo").hide();
                    $("#postinfo").show();
                    @elseif (isset($request->bank_type) && $request->bank_type == 1)
                        $("#postinfo").hide();
                    $("#bankinfo").show();
                    @else
                        $("#postinfo").hide();
                    $("#bankinfo").show();
                    @endif
                }
            });
            $("#invoicetype").change(); // edit 2017-09-07 kieudtd

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

            $("input[name='post_account_type']").click(function () {

                var type = $(this).val();
                if(type == 1){
                    $(".type_1_post").show();
                    $(".type_2_post").hide();
                }else{
                    $(".type_1_post").hide();
                    $(".type_2_post").show();
                }
            })
            $(document).on("keyup",".post_kigou_1",function(){
                $(".post_kigou_2").val($(this).val());
            });
            $(document).on("keyup",".post_kigou_2",function(){
                $(".post_kigou_1").val($(this).val());
            });
            $(document).on("keyup",".post_name_1",function(){
                $(".post_name_2").val($(this).val());
            });
            $(document).on("keyup",".post_name_2",function(){
                $(".post_name_1").val($(this).val());
            });

            {{--$("input[name='parent_mailaddress2']").change(function () {--}}
                {{--if ($bInit) {--}}
                    {{--var strMail = "{{$request->parent_mailaddress2}}";--}}
                    {{--if (strMail.length < 1) {--}}
                        {{--$(this).val("");--}}
                    {{--}--}}
                {{--}--}}
                {{--$bInit = false;--}}
            {{--});--}}

        });


        $(function () {
            $("A.inputDelete").click(function (e) {
                var curr_id = $(this).attr("id");
                var split = curr_id.split("_");
                $('input[name="payment[' + split[1] + '][payment_del]"]').val('1');
                var activeTable = $(this).parent(".t4d2");
                e.preventDefault();
                inputDel(activeTable);
                return false;
            });
            $(".PaymentAdjust").change(function (e) {

                var adjust = $(this).val();
                var id = $(this).attr("id");
                var split = id.split("_");
                var no = split[2];
                $.get(
                    "/school/ajaxInvoice/getinitfee",
                    {adjust: adjust},
                    function (v_data) {
                        // 金額設定
                        $("#payment_fee_" + no).val(v_data);

                        //change hidden
                        $("#real_payment_fee_"+no).val(v_data);
                    },
                    "jsonp"
                );
                //get value display and format
                return false;
            });
            $(document).on(" keyup ",".InputFee",function(){

                //get value display and format
                var num = $(this).val();
                num = num.replace(/\,/g,'');

                var order = $(this).data('order');
                //change hidden

                $("#real_payment_fee_"+order).val(num);
            })
        });

        $(function () {

            // 受講料以外追加
            $("#inputAdd").click(function () {

                var newTable = $( "TABLE", "#inputBase" ).clone();//inputBaseのIDのTABLEタグをnewTableへ
                var newHR    = $( "HR"   , "#inputBase" ).clone();//inputBaseのIDのHRタグをnewHRへ

                $( ".formItem", newTable ).each( function(){//newTable内のformItemプラン指定のそれれぞれで
                    var title = $( this ).attr( 'title' );//title属性の内容を変数titleへ
                    $( this ).attr( 'name', 'payment[' + nowInputIndex + '][' +  title + ']');//name属性の内容をinput[nowInputIndex][title]へ
                    $( this ).removeAttr( 'title' );//title属性を削除する

                });

                $(".NewPaymentMonth", newTable).attr('id', 'payment_month_' + nowInputIndex).removeClass('NewPaymentMonth');//newTable内のNewDateInputプラン指定でid属性をDateInput_＋nowInputIndexへ、同時にNewDateInputプランを削除する
                $(".NewPaymentAdjust", newTable).attr('id', 'payment_adjust_' + nowInputIndex).removeClass('NewPaymentAdjust');//newTable内のNewex_fromTimeプラン指定でid属性をex_fromTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
                $(".NewPaymentFee", newTable).attr('id', 'payment_fee_' + nowInputIndex).removeClass('NewPaymentFee');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
                $(".NewPaymentId", newTable).attr('id', 'payment_id_' + nowInputIndex).removeClass('NewPaymentId');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
                $(".NewPaymentDel", newTable).attr('id', 'payment_del_' + nowInputIndex).removeClass('NewPaymentDel');
                $("#inputActive").append(newTable);//inputActiveのID指定にnewTableの内容を追加する
                $("#inputActive").append(newHR);//inputActiveのID指定にnewHRの内容を追加する

                // 削除処理設定
                $("A.inputDelete", newTable).click(function (e) {

                    e.preventDefault();
                    inputDel(newTable);
                    return false;
                });

                /// 摘要の初期値取得


                $("#payment_adjust_" + nowInputIndex, newTable).change(function (e) {

                    var adjust = $(this).val();

                    var id = $(this).attr("id");
                    var split = id.split("_");
                    var no = split[2];
                    $.get(
                        "/school/ajaxInvoice/getinitfee",
                        {adjust: adjust},
                        function (v_data) {
                            // 金額設定
                            $("#payment_fee_" + no).val(v_data);
                        },
                        "jsonp"
                    );
                    return false;
                });


                // 表示
                $(newTable).show();

                nowInputIndex++;

                return false;
            });

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

        // 削除
        function inputDel(item) {
//	$( item ).next().remove();
            $(item).remove();

//	$( item ).empty();
            return false;
        }

                @if (isset($request['payment']))
        var nowInputIndex =  {{ count($request['payment']) }};
                @else
        var nowInputIndex = '0';
        @endif

        $(function () {

            $('[name=other_name_flag]').change(function () {
                if ($(this).is(":checked")) {
                    if ($(this).val() == 1) {
                        $('[name=parent_name_other]').attr('disabled', false);
                    } else {
                        $('[name=parent_name_other]').prop('disabled', true);
                    }
                }

            });
            $('[name=other_address_flag]').change(function () {
                if ($(this).is(":checked")) {
                    if ($(this).val() == 1) {
                        $('[name=zip_code1_other], [name=zip_code2_other], [name=pref_id_other], [name=city_id_other], [name=address_other], [name=building_other]').attr('disabled', false);
                    } else {
                        $('[name=zip_code1_other], [name=zip_code2_other], [name=pref_id_other], [name=city_id_other], [name=address_other], [name=building_other]').prop('disabled', true);
                    }
                }

            });

            $('[name=other_name_flag]').change();
            $('[name=other_address_flag]').change();
        })
    </script>

    <script type="text/javascript">
        function nextForm(event) {
            if (event.keyCode == 0x0d) {
                var current = document.activeElement;

                var forcus = 0;
                for (var idx = 0; idx < document.entry_form.elements.length; idx++) {
                    if (document.entry_form[idx] == current) {
                        forcus = idx;
                        break;
                    }
                }
                document.entry_form[(forcus + 1)].focus();
            }
        }
        window.document.onkeydown = nextForm;
    </script>
    <script type="text/javascript">
        var $bInit = true;

        function nextForm(event) {
            if (event.keyCode == 0x0d) {
                var current = document.activeElement;

                var forcus = 0;
                for (var idx = 0; idx < document.entry_form.elements.length; idx++) {
                    if (document.entry_form[idx] == current) {
                        forcus = idx;
                        break;
                    }
                }
                document.entry_form[(forcus + 1)].focus();
            }
        }
        window.document.onkeydown = nextForm;

        $(document).ready(function () {
            // set city pref from zip code
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
                            $("#address_pref").val(info.pref_id);
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

                        $('#address_city').html(html);
                        $("#address_city").val(city_id);

                    },
                    error: function (data) {

                    },
                });
            }
            //
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
                            $("input[name=address_other]").val(info.address);
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
        })
    </script>
    <style>
        .top_btn li:hover, .submit_return:hover, #inputAdd:hover,input[type="button"]:hover, #generateAddress:hover, #generateAddressOther:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .top_btn li {
            font-size: 14px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        input[type="button"], #generateAddress, #generateAddressOther {
            font-size: 14px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        .submit_return, #inputAdd {
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
            font-size: 14px;
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
    <div id="center_content_header" class="box_border1">

        <h2 class="float_left"><i class="fa fa-user-secret"></i> {{$lan::get('main_title')}}</h2>
        <div class="center_content_header_right">
            <div class="top_btn"></div>
        </div>
        <div class="clr"></div>

        {{-- <div id="topic_list"
        style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
        @if(isset($request['orgparent_id'])) {!!
        Breadcrumbs::render('parent_edit') !!} @else {!!
        Breadcrumbs::render('parent_entry') !!} @endif</div> --}}

        {{--@include('_parts.topic_list')--}}
    </div>
    <h3 id="content_h3" class="box_border1">
        @if (isset($request['orgparent_id']))
            {{$lan::get('edit')}}
        @else
            {{$lan::get('registration')}}
        @endif</h3>



    <div id="section_content_in">

        <ul class="message_area">@if (session()->get('success'))
                <li class="alert alert-success" role="alert" style="color: blue;">
                    {{session()->pull('success')}}</li> @endif
        </ul>
        @if(count($errors))
            <ul class="message_area">
                @foreach($errors->all() as $error)
                    <li class="error_message">{{$lan::get($error)}}</li>
                @endforeach
            </ul>
        @endif


        <div id="section_content">
            <form id="entry_form" name="entry_form" method="post">
                {{ csrf_field() }}
                <span class="aster">&lowast;</span>{{$lan::get('mandatory_items_marked')}}
                    <input type="hidden" name="function" value="{{$request->function}}"/>
                    <input type="hidden" name="login_account_id" value="{{$request->login_account_id}}"/>
                    @if (isset($request['orgparent_id']))
                        <input type="hidden" name="orgparent_id" value="{{$request->orgparent_id}}"/>
                    @endif

                    @if (isset($request['link_enable']))
                        @include('_parts.student.hidden') @endif
                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        <tr>
                            <td class="t6_td1">{{$lan::get('given_name')}}<span class="aster">&lowast;</span></td>
                            <td class="t4td2"><input class="text_m" style="ime-mode:active;" type="text" placeholder="{{$lan::get('given_name')}}{{$lan::get('placeholder_input_temp')}}"
                                                     name="parent_name" value="{{$request->parent_name}}"/></td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('kana_name')}}{{--<span class="aster">&lowast;</span>--}}</td>
                            <td class="t4td2"><input class="text_m" style="ime-mode:active;" type="text" placeholder="{{$lan::get('kana_name')}}{{$lan::get('placeholder_input_temp')}}"
                                                     name="name_kana" value="{{$request->name_kana}}"/></td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('email_address_1')}}<span class="aster">&lowast;</span></td>
                            <td class="t4td2"><input class="text_m" style="ime-mode:inactive;" type="text" placeholder="{{$lan::get('email_address_1')}}{{$lan::get('placeholder_input_temp')}}"
                                                     name="parent_mailaddress1"
                                                     value="{{$request->parent_mailaddress1}}"/>&nbsp;<b>{{$lan::get('use_as_login_title')}}</b>
                            </td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td class="t6_td1">{{$lan::get('email_address_2')}}</td>--}}
                            {{--<td class="t4td2"><input class="text_m" style="ime-mode:inactive;" type="text" placeholder="{{$lan::get('email_address_2')}}{{$lan::get('placeholder_input_temp')}}"--}}
                                                     {{--name="parent_mailaddress2"--}}
                                                     {{--value="{{$request->parent_mailaddress2}}"/></td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td class="t6_td1">{{$lan::get('password')}}<span class="aster">&lowast;</span></td>
                            <td class="t4td2" style="display: -webkit-inline-box; width: 100%">
                                <input class="text_m" type="password" placeholder="{{$lan::get('password')}}{{$lan::get('placeholder_input_temp')}}"
                                                     name="parent_pass" value="@if(isset($request->parent_pass)){{array_get($request, 'parent_pass')}}@endif"/>
                                <div>
                                    @if	(array_get($request, 'id'))<b><spanclass="col_msg">※{{$lan::get('input_only_to_change_title')}}</span></b><br/>
                                    @endif
                                    <span><b>※{{$lan::get('password_regex_warning')}}</b></span>
                                </div>
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
                            <td>
                                &#12306;&nbsp;<input class="text_ss" style="width:50px;ime-mode:inactive;" type="text"
                                         name="zip_code1" value="{{$request->zip_code1}}" maxlength="3"
                                         pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&ndash;
                                <input class="text_ss" style="width:60px;ime-mode:inactive;" type="text"
                                       name="zip_code2" value="{{$request->zip_code2}}" maxlength="4"
                                       pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                                &nbsp;&nbsp;&nbsp;
                                <button type="button" id="generateAddress" style="color: #595959;height: 30px;">{{$lan::get('generate_address')}}</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('prefecture_name')}}<span class="aster">&lowast;</span></td>
                            <td class="t4td2">
                                <select name="pref_id" id="address_pref" style="width:200px">
                                    <option value=""></option>
                                    @foreach($prefList as $key => $item)
                                            <option value="{{$key}}" @if(($request->pref_id) == $key) selected="selected" @endif>{{$item}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('city_name')}}<span class="aster">&lowast;</span></td>
                            <td class="t4td2">
                                <select name="city_id" id="address_city" style="width:200px">
                                    <option value=""></option>
                                    @foreach($cityList as $key => $item)
                                        @if(($request->city_id) == $key)
                                            <option value="{{$key}}" selected="selected" @endif>{{$item}}</option>
                                            <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('address_title')}}<span class="aster">&lowast;</span>
                            </td>
                            <td class="t4td2"><input class="text_l" style="ime-mode:active;" type="text" name="address"
                                             placeholder="{{$lan::get('address_title')}}{{$lan::get('placeholder_input_temp')}}" value="{{$request->address}}"/></td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('building_title')}}</td>
                            <td class="t4td2"><input class="text_l" style="ime-mode:active;" type="text" name="building"
                                                     placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_input_temp')}}" value="{{$request->building}}"/></td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('contact_phone_title')}}<span class="aster">&lowast;</span></td>
                            <td class="t4td2"><input class="text_m" style="ime-mode:inactive;" type="text"
                                                     placeholder="{{$lan::get('contact_phone_title')}}{{$lan::get('placeholder_input_temp')}}" name="phone_no" value="{{$request->phone_no}}"
                                                     pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/></td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('mobile_phone')}}</td>
                            <td class="t4td2"><input class="text_m" style="ime-mode:inactive;" type="text" placeholder="{{$lan::get('mobile_phone')}}{{$lan::get('placeholder_input_temp')}}"
                                                     name="handset_no" value="{{$request->handset_no}}"/></td>
                        </tr>
                        <tr>
                            <td class="t6_td1">{{$lan::get('memo')}}</td>
                            <td class="t4td2"><textarea style="ime-mode:active;" id="input3" name="memo" cols="30"
                                                placeholder="{{$lan::get('memo')}}{{$lan::get('placeholder_input_temp')}}" rows="4">{{$request->memo}}</textarea></td>
                        </tr>

                    </table>

                    <table id="table6">
                        <colgroup>
                            <col width="30%"/>
                            <col width="70%"/>
                        </colgroup>
                        <tr>
                            <td class="t6_td1"><strong>{{$lan::get('payment_method')}}</strong></td>
                            <td><select id="invoicetype" name="invoice_type">
                                    @foreach($payment_method_list as $k => $v)
                                        @if($request->invoice_type == array_get($v,'payment_method_value'))
                                            <option value="{{array_get($v,'payment_method_value')}}" selected>{{$lan::get(array_get($v,'payment_method_name'))}}</option>
                                        @else
                                            <option value="{{array_get($v,'payment_method_value')}}">{{$lan::get(array_get($v,'payment_method_name'))}}</option>
                                        @endif
                                    @endforeach
                                </select></td>
                        </tr>
                        <tr>
                            <td class="t6_td1"><strong>{{$lan::get('notification_method')}}</strong>
                            </td>
                            <td>
                                <select id="mailinfo" name="mail_infomation">
                                    <option value="0"
                                            @if (isset($request->mail_infomation) && ($request->mail_infomation == 0)) selected
                                            @endif>{{$lan::get('mailing')}}</option>
                                    <option value="1"
                                            @if (isset($request->mail_infomation) && ($request->mail_infomation == 1) || !isset($request->mail_infomation)) selected
                                            @endif>{{$lan::get('email')}}</option>
                                    <option value="2"
                                            @if (isset($request->mail_infomation) && ($request->mail_infomation == 2) || !isset($request->mail_infomation)) selected
                                            @endif>{{$lan::get('notsend')}}</option>
                                </select>
                            </td>
                        </tr>
                        @foreach($additionalCategories as $category)
                            <tr>
                                <td class="t6_td1">
                                    {{array_get($category, 'name')}}
                                </td>
                                <td class="t4td2">
                                    <input type="text" name="additional[{{array_get($category, 'code')}}]" value="{{array_get($request, 'additional.' . array_get($category, 'code'), array_get($category, 'value'))}}">
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div id="invoiceinfo"
                         @if ($request->invoice_type == 1) style="display:none" @endif>
                        <h4>{{$lan::get('account_information')}}</h4>
                        <table id="table6">
                            <colgroup>
                                <col width="30%"/>
                                <col width="70%"/>
                            </colgroup>
                            <tr>
                                <td class="t6_td1">{{$lan::get('financial_organizations')}}</td>
                                <td><select id="banktype" name="bank_type">
                                        <option value="1"
                                                @if (isset($request->bank_type) &&	($request->bank_type == null) || ($request->bank_type == 1))
                                                selected @endif>{{$lan::get('bank_credit_union')}}</option>
                                        <option value="2"
                                                @if (isset($request->bank_type) &&($request->bank_type == 2)) selected
                                                @endif>{{$lan::get('post_office')}}</option>
                                    </select></td>
                            </tr>
                        </table>
                    </div>
                    <div id="bankinfo"
                         @if ($request->invoice_type == 1 || ($request->bank_type != null && $request->bank_type != 1))
                         style="display:none" @endif>
                        <table id="table6">
                            <colgroup>
                                <col width="30%"/>
                                <col width="70%"/>
                            </colgroup>
                            <tr>
                                <td class="t6_td1">{{$lan::get('bank_code')}} <span class="aster">*</span>
                                </td>
                                <td><input style="ime-mode: inactive;" type="text"
                                           name="bank_code" value="{{$request->bank_code}}"
                                           class="l_text"/> <b>{{$lan::get('half_width_number_4_digit')}}</b></td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('financial_institution_name')}} <span
                                            class="aster">*</span>
                                </td>
                                <td><input style="ime-mode: inactive;" type="text"
                                           name="bank_name" value="{{$request->bank_name}}"
                                           class="l_text"/>
                                    <b>{{$lan::get('single_byte_uppercase_kana_up_15_character')}}</b></td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('branch_code')}} <span class="aster">*</span>
                                </td>
                                <td><input style="ime-mode: inactive;" type="text"
                                           name="branch_code" value="{{$request->branch_code}}"
                                           class="l_text"/> <b>{{$lan::get('half_width_number_3_digit')}}</b></td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('branch_name')}} <span class="aster">*</span>
                                </td>
                                <td><input style="ime-mode: inactive;" type="text"
                                           name="branch_name" value="{{$request->branch_name}}"
                                           class="l_text"/>
                                    <b>{{$lan::get('single_byte_uppercase_kana_up_15_character')}}</b></td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('classification')}} <span
                                            class="aster">*</span>
                                </td>
                                <td><select name="bank_account_type">
                                        @if(isset($bank_account_type_list))
                                            @foreach($bank_account_type_list as $key => $item)
                                                <option value="{{$key}}" @if (isset($request->bank_account_type)
												&& $request->bank_account_type == $key) selected
                                                        @endif>{{$item}}</option> @endforeach
                                        @endif
                                    </select></td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('account_number')}} <span
                                            class="aster">*</span>
                                </td>
                                <td><input style="ime-mode: inactive;" type="text"
                                           name="bank_account_number"
                                           value="{{$request->bank_account_number}}" class="m_text"/>
                                    <b>{{$lan::get('half_width_number_7_digit')}}</b></td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('account_name_halfsize')}} <span
                                            class="aster">*</span>
                                </td>
                                <td><input style="ime-mode: active;" type="text"
                                           name="bank_account_name_kana"
                                           value="{{$request->bank_account_name_kana}}" class="l_text"/>
                                    <b>{{$lan::get('single_byte_uppercase_kana_up_30_character')}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="t6_td1">{{$lan::get('account_holder')}} <span
                                            class="aster">*</span>
                                </td>
                                <td><input style="ime-mode: active;" type="text"
                                           name="bank_account_name"
                                           value="{{$request->bank_account_name}}" class="l_text"/>
                                    <b>{{$lan::get('bank_account_name_kana_warning')}}</b>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="postinfo"
                         @if ($request->invoice_type == 1 || ($request->bank_type != null && $request->bank_type != 2)) style="display:none" @endif>
                        <table id="table6">
                            <colgroup>
                                <col width="30%"/>
                                <col width="35%"/>
                                <col width="35%"/>
                            </colgroup>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('type_title')}}
                                </td>
                                <td>
                                    <label><input type="radio" name="post_account_type" value="1" @if($request->post_account_type!=2) checked @endif/>総合口座、通常貯金、通常貯蓄貯金</label>
                                </td>
                                <td>
                                    <label><input type="radio" name="post_account_type" value="2" @if($request->post_account_type==2) checked @endif>振替口座</label>
                                </td>
                            </tr>
                        </table>
                        <table id="table6" class="type_1_post" @if($request->post_account_type == 2) style="display: none;" @endif>
                            <colgroup>
                                <col width="30%"/>
                                <col width="35%"/>
                                <col width="35%"/>
                            </colgroup>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_symbol')}}
                                    <span class="aster">*</span>
                                </td>
                                <td>
                                    <input type="hidden" name="id" value="{{$request->id}}">
                                    <input style="ime-mode:inactive;" type="text" maxlength="5" name="post_account_kigou"
                                           value="{{$request->post_account_kigou}}" class="post_kigou_1 m_text"/>
                                </td>
                                <td>
                                    <b>※{{$lan::get('5_digit_no_title')}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_number')}}
                                    <span class="aster">*</span>
                                </td>
                                <td>
                                    <input style="ime-mode:inactive;" maxlength="8" type="text" name="post_account_number"
                                           value="{{$request->post_account_number}}" class="m_text"/>
                                </td>
                                <td>
                                    <b>※ {{$lan::get('8_digit_no_title')}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_name')}}
                                    <span class="aster">*</span>
                                </td>
                                <td>
                                    <input style="ime-mode:inactive;" type="text" name="post_account_name"
                                           value="{{$request->post_account_name}}" class=" post_name_1 l_text"/>
                                </td>
                                <td>
                                    <b>※{{$lan::get('30_single_kana_upper_title')}}</b>
                                </td>
                            </tr>
                        </table>
                        <table id="table6" class="type_2_post"  @if($request->post_account_type == 1 || !isset($request->post_account_type)) style="display: none;" @endif>
                            <colgroup>
                                <col width="30%"/>
                                <col width="35%"/>
                                <col width="35%"/>
                            </colgroup>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_symbol')}}
                                    <span class="aster">*</span>
                                </td>
                                <td>
                                    <input type="hidden" name="id" value="{{$request->id}}">
                                    <input style="ime-mode:inactive;" type="text" maxlength="5" name="post_account_kigou"
                                           value="{{$request->post_account_kigou}}" class="post_kigou_2 m_text"/>
                                </td>
                                <td>
                                    <b>※{{$lan::get('5_digit_no_title')}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_number')}}
                                    <span class="aster">*</span>
                                </td>
                                <td>
                                    <input style="ime-mode:inactive; width:50px!important;" maxlength="1" type="text" name="post_account_number_1"
                                           value="{{$request->post_account_number_1}}" class="m_text" > &nbsp;
                                    <input style="ime-mode:inactive; width:148px!important;"  maxlength="7" type="text" name="post_account_number_2"
                                           value="{{$request->post_account_number_2}}" class="m_text" />
                                </td>
                                <td>
                                    <span><b>※ {{$lan::get('1_plus_7_digit_title')}}</b></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="t6_td1">
                                    {{$lan::get('passbook_name')}}
                                    <span class="aster">*</span>
                                </td>
                                <td>
                                    <input style="ime-mode:inactive;" type="text" name="post_account_name"
                                           value="{{$request->post_account_name}}" class="post_name_2 l_text"/>
                                </td>
                                <td>
                                    <b>※{{$lan::get('30_single_kana_upper_title')}}</b>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="AdjustInfo">
                        <h4>{{$lan::get('premium_discount')}}</h4>
                        <table id="table6">
                            <colgroup>
                                <col width="30%"/>
                                <col width="70%"/>
                            </colgroup>
                            <tr>
                                <td class="t6_td1">{{$lan::get('premium_discount_items')}}</td>
                                <td>
                                    <div id="inputActive">
                                        @if(isset($request['payment']) && (count($request['payment']) > 0))
                                            @foreach(array_get($request, 'payment') as $k =>$v)
                                                <div class="InputArea">
                                                    <table>
                                                        <tr>
                                                            <td class="t4d2">{{$lan::get('target_month')}}
                                                                <select name="payment[{{$loop->index}}][payment_month]"
                                                                        form="entry_form"
                                                                        id="payment_month_{{$loop->index}}"
                                                                        class="formItem PaymentMonth">
                                                                    <option value=""></option>
                                                                    @if(isset($month_list))
                                                                        @foreach ($month_list as $key => $row)
                                                                            <option value="{{$key}}"
                                                                                    @if($key== array_get($v, 'payment_month')) selected @endif> {{$row}} </option>     @endforeach
                                                                    @endif
                                                                </select> &nbsp;{{$lan::get('abstract')}}<select
                                                                        form="entry_form"
                                                                        name="payment[{{$loop->index}}][payment_adjust]"
                                                                        id="payment_adjust_{{$loop->index}}"
                                                                        class="formItem PaymentAdjust">
                                                                    <option value=""></option>
                                                                    @foreach($invoice_adjust_list as $key => $row)
                                                                        <option value="{{$row['id']}}"
                                                                                @if(array_get($v, 'payment_adjust') == $row['id'])selected @endif>{{array_get($row, 'name')}}</option>
                                                                    @endforeach
                                                                </select> &nbsp;{{$lan::get('price')}}<input
                                                                                                             type="text"
                                                                                                             id="payment_fee_{{$loop->index}}"
                                                                                                             data-order="{{$loop->index}}"
                                                                                                             class="formItem InputFee"
                                                                                                             style="ime-mode: active; width: 80px;"
                                                                                                             value="@if(is_numeric(array_get($v,'payment_fee'))){{number_format(array_get($v,'payment_fee'))}} @else {{array_get($v,'payment_fee')}} @endif"/>&nbsp;{{$lan::get('circle')}}
                                                               <input form="entry_form" type="hidden"
                                                                       name="payment[{{$loop->index}}][payment_fee]"
                                                                       id="real_payment_fee_{{$loop->index}}"
                                                                       value="{{array_get($v,'payment_fee')}}"/>


                                                                <input form="entry_form"
                                                                       type="hidden"
                                                                       name="payment[{{$loop->index}}][payment_id]"
                                                                       id="payment_id_{{$loop->index}}"
                                                                       value="{{array_get($v,'payment_id')}}"/> <a
                                                                        class="inputDelete" href="#"
                                                                        id="AdjustDelete_{{$loop->index}}"><input
                                                                            type="button"
                                                                            value="{{$lan::get('delete')}}"/></a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div style="margin: 10px 10px 17px 120px;">
                                        <button id="inputAdd" style="width: 100px">{{$lan::get('add_items')}}</button>
                                    </div>
                                    <div id="inputBase" style="display: none;">
                                        <!-- 					review . Data get from controller or request-->
                                        <table>
                                            <tr>
                                                <td class="t4d2">{{$lan::get('target_month')}}<select
                                                            class="formItem NewPaymentMonth" title="payment_month"
                                                            form="entry_form">
                                                        <option value=""></option>
                                                        @if(isset($month_list))
                                                            @foreach ($month_list as $key => $row)
                                                                <option value="{{$key}}">{{$row}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select> &nbsp;{{$lan::get('abstract')}}<select form="entry_form"
                                                                                                     class="formItem NewPaymentAdjust"
                                                                                                     title="payment_adjust">
                                                        <option value=""></option>
                                                        @if(isset($invoice_adjust_list))
                                                            @foreach ($invoice_adjust_list as $row)
                                                                <option value="{{array_get($row, 'id')}}">{{array_get($row, 'name')}}</option> @endforeach
                                                        @endif
                                                    </select> &nbsp;{{$lan::get('price')}}
                                                    <input type="text" form="entry_form" class="formItem NewPaymentFee"
                                                           style="ime-mode: active; width: 80px;" value=""
                                                           title="payment_fee"/>&nbsp;{{$lan::get('circle')}}
                                                    <input type="hidden" class="formItem NewPaymentId" value=""
                                                           title="payment_id" form="entry_form"/> <a
                                                            class="inputDelete" href="#">
                                                        <!-- <input type="button" value="{{$lan::get('delete')}}"/> -->
                                                        <button type="button" style="color:#595959; width: 75px !important; font-size: 11px !important;" ><i class="glyphicon glyphicon-minus-sign " style="width: 15% !important;" ></i>&nbsp; {{$lan::get('delete')}}</button>
                                                        </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </td>
                            </tr>
                        </table>
                        <div id="">
                            <h4>{{$lan::get('parent_addressee_title')}}</h4>
                            <table id="table6">
                                <colgroup>
                                    <col width="30%"/>
                                    <col width="70%"/>
                                </colgroup>
                                <tr>
                                    <td class="t6_td1"><label style="font-weight: 500;"><input type="radio" name="other_name_flag" value="0" checked> {{$lan::get('parent_name_title')}}</label></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="t6_td1"><label style="font-weight: 500;"><input type="radio" name="other_name_flag" value="1" @if ($request->other_name_flag == 1) checked @endif> {{$lan::get('other_title')}}</label></td>
                                    <td class="t6_td1"><input type="text" class="l_text form-group" name="parent_name_other" value="{{$request->parent_name_other}}" placeholder="{{$lan::get('parent_addressee_input_title')}}" maxlength="255"></td>
                                </tr>
                            </table>
                        </div>
                        <div id="">
                            <h4>{{$lan::get('parent_address_title')}}</h4>
                            <table id="table6">
                                <colgroup>
                                    <col width="30%"/>
                                    <col width="70%"/>
                                </colgroup>
                                <tr>
                                    <td class="t6_td1"><label style="font-weight: 500;"><input type="radio" name="other_address_flag" value="0" checked> {{$lan::get('parent_registered_address_title')}}</label></td>
                                    <td class="t6_td1"></td>
                                </tr>
                                <tr>
                                    <td class="t6_td1"><label style="font-weight: 500;"><input type="radio" name="other_address_flag" value="1" @if ($request->other_address_flag == 1) checked @endif> {{$lan::get('other_title')}}</label></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="t6_td1 td_inner"> {{$lan::get('postal_code')}}</td>
                                    <td>
                                        &#12306;&nbsp;<input class="text_ss" style="width:50px;ime-mode:inactive;" type="text"
                                                 name="zip_code1_other" value="{{$request->zip_code1_other}}" maxlength="3"
                                                 pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&ndash;
                                        <input class="text_ss" style="width:60px;ime-mode:inactive;" type="text"
                                               name="zip_code2_other" value="{{$request->zip_code2_other}}" maxlength="4"
                                               pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;&nbsp;&nbsp;
                                        <button type="button" id="generateAddressOther" style="color: #595959;height: 30px;">{{$lan::get('generate_address')}}</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="t6_td1 td_inner"> {{$lan::get('prefecture_name')}}</td>
                                    <td class="t4td2">
                                        <select name="pref_id_other" id="address_pref_other" style="width:200px">
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
                                        <select name="city_id_other" id="address_city_other" style="width:200px">
                                            <option value=""></option>
                                            @foreach($cityOtherList as $key => $item)
                                                <option value="{{$key}}" @if(($request->city_id_other) == $key) selected="selected" @endif>{{$item}}</option>
                                                <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="t6_td1 td_inner"> {{$lan::get('address_title')}}
                                    </td>
                                    <td class="t4td2"><input class="text_l" style="ime-mode:active;" type="text" name="address_other" placeholder="{{$lan::get('address_title')}}{{$lan::get('placeholder_input_temp')}}" value="{{$request->address_other}}" maxlength="255"/></td>
                                </tr>
                                <tr>
                                    <td class="t6_td1 td_inner"> {{$lan::get('building_title')}}</td>
                                    <td class="t4td2"><input class="text_l" style="ime-mode:active;" type="text" name="building_other" placeholder="{{$lan::get('building_title')}}{{$lan::get('placeholder_input_temp')}}" value="{{$request->building_other}}" maxlength="255"/></td>
                                </tr>
                            </table>
                        </div>
                        <br/>
                        <div class="exe_button">
                            <button id="submit2" class="submit_return" type="button" style="font-weight: normal; font-size: 14px;"><i class="glyphicon glyphicon-floppy-disk " style="width: 20%;font-size:16px;"></i>登録</button> &nbsp;
                            <button id="submit_return" class="submit_return" type="button" style="font-weight: normal; font-size: 14px;"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return')}}</button> &nbsp;
                            <!-- <input class="submit_return" id="submit_return" type="button"
                                   value="{{$lan::get('return')}}"/> -->
                            <!-- <input class="submit2" type="button" value="{{$lan::get('confirm')}}"/> -->
                        </div>
                    </div>
                </form>


        </div>
        <!--section_content-->


        {{--</td>--}}
        <!--center_content-->
        <div id="dialog_active" class="no_title" style="display:none;">
            {{--あなたが保存したいです?--}}{{--保存しますが、よろしいでしょうか。--}}
            {{$lan::get('save_confirm')}}
        </div>
        <form id="frm_return" action="/school/parent/detail" method="post" style="display:none">
            {{ csrf_field() }}
            <input type="hidden" name="orgparent_id" value="{{$request->orgparent_id}}">
        </form>

@stop