@extends('Home.master_layout')
@section('content')
<link type="text/css" rel="stylesheet" href="/css/school/jquery-ui.css" />
<div class="sub_topbnr">
    <div class="width"></div>
</div>
<div class="main_content sub_content">
    <div class="width">
        <h2>デモ版利用登録</h2>
    </div>

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <form method="post" id="form_submit" action="/home/confirmRegister">
                    {{ csrf_field() }}
                    <table class="table1">
                        <tr>
                            <th style="width:28%;">
                                代表者・登録者名称 &nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <!-- @if(!empty($errors))
                                    @foreach($errors->all() as $k => $error)
                                        {{$error}}
                                    @endforeach
                                @endif -->
                                <span class=""><input type="text" name="customer_name" value="{{array_get_not_null($request,'customer_name')}}" size="40" class="text1" placeholder="代表者・登録者名称を入力してください" maxlength="255" /></span><br/>
                                <span class="error_message" id="customer_name_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                会社・組織名称 &nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class=""><input type="text" name="company_name" value="{{array_get_not_null($request,'company_name')}}" size="40" class="text1" placeholder="会社・組織名称を入力してください" maxlength="255" /></span><br/>
                                <span class="error_message" id="company_name_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                登録メールアドレス&nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class=""><input type="text" name="mail_address" value="{{array_get_not_null($request,'mail_address')}}" size="40" class="text1" placeholder="登録メールアドレスを入力してください" maxlength="64"  /></span><br/>
                                <span class="error_message" id="mail_address_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle;">
                                パスワード&nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <div style="display: -webkit-inline-box; width: 100%">
                                    <div style="width: 60%">
                                        <span class=""><input style="width: 100%" type="password" name="password" value="{{array_get_not_null($request,'password')}}" size="40" class="text1" minlength="8" maxlength="16" /></span><br />
                                        <span class="error_message" id="password_err"></span>
                                    </div>
                                    <div style="width: 40%; margin-left: 10px;">
                                        <label style="color: #9e6d3b;">半角英数文字または特殊文字 (-,_,.,$,#,:@,!) で8文字以上120文字以下で設定してください</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                パスワード（確認）&nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class=""><input type="password" name="re_password" value="{{array_get_not_null($request,'re_password')}}" size="40" class="text1" minlength="8" maxlength="16" /></span><br />
                                <span class="error_message" id="re_password_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                郵便番号&nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class="">〒&nbsp;<input type="text" name="zip_code1" value="{{array_get_not_null($request,'zip_code1')}}" maxlength="3" class="text2" onkeypress="return isNumberKey(event)"/>
                                    - <input type="text" name="zip_code2" value="{{array_get_not_null($request,'zip_code2')}}" class="text2" maxlength="4" onkeypress="return isNumberKey(event)"/></span> &nbsp;
                                    <button class="grey_btn" type="button" id="generateAddress">〒 → 住所変換</button><br/>
                                    @if ($errors->has('zip_code1'))
                                        <div class="error_message">{{ $errors->first('zip_code1') }}</div>
                                    @elseif ($errors->has('zip_code2'))
                                        <div class="error_message">{{ $errors->first('zip_code2') }}</div>
                                    @endif
                                <span class="error_message" id="zip_code_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                都道府県名&nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class="">
                                    <select id = "pref_id" name = "pref_id" class="" style="width: 205px">
                                        <option value="">都道府県を選択してください</option>
                                        @foreach($prefList as $key => $item)
                                            <option value="{{$key}}"
                                                    @if(array_get_not_null($request, 'pref_id') == $key) selected @endif>{{$item}}
                                            </option>
                                        @endforeach
                                    </select>
                                </span>
                                <br />
                                <span class="error_message" id="pref_id_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                市区町村名&nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class="">
                                    <select id = "city_id" name = "city_id" class="" style="width: 205px">
                                        @foreach($cityList as $key => $item)
                                            <option value="{{$key}}"
                                                    @if(array_get_not_null($request, 'city_id') == $key) selected @endif>{{$item}}
                                            </option>
                                        @endforeach
                                    </select>
                                </span>
                                <br />
                                <span class="error_message" id="city_id_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                番地 &nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class=""><input type="text" id = "address" name="address" value="{{array_get_not_null($request,'address')}}" size="40" class="text1" placeholder="番地を入力してください" maxlength="255" /></span><br />
                                <span class="error_message" id="address_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                ビル名
                            </th>
                            <td>
                                <span class=""><input type="text" name="building" placeholder="ビル名を入力してください" value="{{array_get_not_null($request,'building')}}" size="40" class="text1" maxlength="64" /></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                連絡先電話番号 &nbsp;<span class="required">*</span>
                            </th>
                            <td>
                                <span class=""><input type="text" name="phone" value="{{array_get_not_null($request,'phone')}}" size="40" class="text1" placeholder="xxx-xxxx-xxxx" maxlength="64" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" onblur="check_numtype(this)" style="ime-mode:disabled"/></span><br />
                                <span class="error_message" id="phone_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                FAX
                            </th>
                            <td>
                                <span class=""><input type="text" name="fax" value="{{array_get_not_null($request,'fax')}}" size="40" class="text1" placeholder="FAXを入力してください" maxlength="64" pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" onblur="check_numtype(this)" style="ime-mode:disabled"/></span><br />
                                <span class="error_message" id="fax_err"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                ホームページ
                            </th>
                            <td>
                                <span class=""><input type="text" name="home_page" value="{{array_get_not_null($request,'home_page')}}" size="40" class="text1" placeholder="ホームページを入力してください" maxlength="255" /></span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" style="padding-left: 100px; padding-right: 50px; ">
                                <div style="font-size: 0.9em; max-height: 150px; overflow: scroll; border: solid 0.5px grey; margin-bottom: 10px; padding : 10px; background-color: white; box-shadow: #dcfff5">
                                    <span>
                                        規約には、本サービスを利用するにあたってのあなたの権利と義務が規定されております。 <br/>
                                        「規約に同意する」ボタンをクリックすると、あなたが本規約のすべての条件に同意したことになります。
                                    </span>
                                    <span>
                                        規約には、本サービスを利用するにあたってのあなたの権利と義務が規定されております。 <br/>
                                        「規約に同意する」ボタンをクリックすると、あなたが本規約のすべての条件に同意したことになります。
                                    </span>
                                    <span>
                                        規約には、本サービスを利用するにあたってのあなたの権利と義務が規定されております。 <br/>
                                        「規約に同意する」ボタンをクリックすると、あなたが本規約のすべての条件に同意したことになります。
                                    </span>
                                    <span>
                                        規約には、本サービスを利用するにあたってのあなたの権利と義務が規定されております。 <br/>
                                        「規約に同意する」ボタンをクリックすると、あなたが本規約のすべての条件に同意したことになります。
                                    </span>
                                </div>
                                <label><input type="checkbox" name="agreement">規約に同意する</label>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type = "button" value="確認画面" class="blue_btn" id = "btn_submit"/> &nbsp;&nbsp;&nbsp;
                                <input type = "button" value="すべてクリア" class="blue_btn" id = "clear_input" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div><!-- width -->
</div><!-- main_content -->
<div id="agreement_warning">
    <span>
        規約に同意してください
    </span>
</div>
<script>
    var default_city_id = "{{array_get_not_null($request, 'city_id')}}";

    // 1. global variable  (temporary define)
    var _return_value = "";

    // input value check
    function check_numtype(obj){

        // 2. variable define
        var txt_obj = $(obj).val();
        var text_length = txt_obj.length;

        // 3. input key check(numeric)
        if(txt_obj.match(/^[0-9\-]+$/)){
            _return_value = txt_obj;
        }else{
            // 3.1 input key not numeric
            if(text_length == 0){
                $(obj).val("");
                _return_value = "";
            }else{
                $(obj).val(_return_value);
            }
        }
    }

    $(document).ready(function () {

        //init message
        @if(!empty($errors))
            @php
                $message = $errors->messages();
                foreach ($message as $field => $text){
                    foreach ($text as $k => $v){
                        @endphp
                        $('#{{$field}}_err').append( '{{$v}}' );
                        @php
                    }
                }
            @endphp
        @endif

        //init dialog
        $( "#agreement_warning" ).dialog({
            title: '警告',
            autoOpen: false,
            resizable: false,
            buttons: {
                "OK": function() {
                    $( this ).dialog( "close" );
                    return false;
                }
            }
        });

        //set pref and city button
        $("#generateAddress").click(function (){
            var zipcode = $("input[name=zip_code1]").val()+$("input[name=zip_code2]").val();
            var _token = "{{csrf_token()}}";

            $.ajax({
                type: "post",
                url: "/home/get_address_from_zipcode",
                data: {zipcode: zipcode,_token: _token},
                dataType:'json',
                success: function(data) {
                    if(data.status == true){
                        var info = data.message;
                        $("#pref_id").val(info.pref_id);
                        setCity(info.pref_id, info.city_id);
                        $("input[name=address]").val(info.address);
                        $("#zip_code_err").hide();
                    }
                }
            });
        });
//        var test = "input[name=phone]";
//        handleNumberInput(test,test);
//        function handleNumberInput(input_field,message_field){
//            const MINUS_KEY_CODE = 189;
//            const NUMBER_1_KEY_CODE = 47;
//            const NUMBER_9_KEY_CODE = 58;
//            const BACK_KEY_CODE = 8;
//            var current_value = "";
//            var jp_selection = false;
//            $(input_field).on("keydown",function (e) {
//
//                var keyCode = (e.keyCode ? e.keyCode : e.which);
//                if ((keyCode > NUMBER_1_KEY_CODE && keyCode < NUMBER_9_KEY_CODE) || keyCode == MINUS_KEY_CODE || keyCode == BACK_KEY_CODE ) {
//                    current_value = $(this).val();
//                    jp_selection = false;
//                } else {
//                    e.preventDefault();
//                }
//            })
//
//            $(input_field).on("keyup",function (e) {
//                var keyCode = (e.keyCode ? e.keyCode : e.which);
//                console.log(keyCode);
//                if(keyCode >= 229){
//                    jp_selection = true;
//                    $(this).val(current_value);
//                }
//                if(keyCode == 13 && jp_selection){
//                    jp_selection = false;
//                    $(this).val(current_value);
//                }
//            })
//            $(input_field).change(function () {
//
//                console.log($(this).val());
//                if(jp_selection){
//                    jp_selection = false;
//                    $(this).val(current_value);
//                }else{
//                    current_value = $(this).val();
//                }
//
//            })
//        }
//
    })

    // get list cities by pref_id then select city
    // if city is null -> do not select
    function setCity(pref_id, city_id){
        $.ajax({
            type: "get",
            dataType: "json",
            url: "/home/get_city",
            data: {pref_id: pref_id},
            contentType: "application/x-www-form-urlencoded",
            success: function (data) {
                data = JSON.stringify(data);
                data = JSON.parse(data);

                var result = data['city_list'];
                var html = "<option value=''>市区町村を選択してください</option>";
                for (x in result) {
                    var html = html + "<option value=" + x + ">" + result[x] + "</option>";
                }

                $('#city_id').html(html);

                if(city_id!== null && city_id!== undefined){
                    $("#city_id").val(city_id);
                }
            },
            error: function (data) {

            },
        });
    }

    // event handle
    $("#pref_id").change(function () {
        var pref_id = $(this).val();
        if (pref_id == "" || pref_id === undefined) {
            $("#city_id option").remove();
            $("#city_id").prepend($("<option>").html("").val(""));
            return;
        } 

        setCity(pref_id, default_city_id);
        default_city_id = null;
    });

    // to load city when back if get error
    $("#pref_id").change();

    //clear input click event
    $("#clear_input").click(function () {

        $("input[type=text]").val("");
        $("input[type=password]").val("");
        $("select").val("");
    })

    //submit event
    $("#btn_submit").click(function () {

        if(!handleInput()){

            return false;

        }else{
            $("#form_submit").submit();
        }

    })

    function validatePassword(password) {
        var re = (/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]{8,}$/);
        return re.test(password);
    }

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function validateFAX(fax) {
        var re = /^(?:\d{10}|\d{3}-\d{3}-\d{4}|\d{2}-\d{4}-\d{4}|\d{3}-\d{4}-\d{4})?$/;
        return re.test(fax);
    }

    function validatePhone(phone) {
        var re = /^(?:\d{10}|\d{3}-\d{3}-\d{4}|\d{2}-\d{4}-\d{4}|\d{3}-\d{4}-\d{4})$/;
        return re.test(phone);
    }

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function testExistMail(mail_address) {
        var mail_address = $("input[name=mail_address]").val();
        var result = null;
        $.ajax({
            type: 'post',
            async: false,
            url: '/home/ajaxCheckEmail',
            data: {
                mail_address: mail_address,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                result = data;
            },
            error: function(data) {
                console.log('error');
            }
        });
        return result;
    }

    // handle input by js
    function handleInput(){

//        check agreement

       var agreement = $("input[name=agreement]").is(":checked");

       if(!agreement){
           $( "#agreement_warning" ).dialog('open');
           return false;
       }

//          check input required
       $(".error_message").hide();


       var customer_name = $("input[name=customer_name]").val();
       var company_name = $("input[name=company_name]").val();
       var mail_address = $("input[name=mail_address]").val();
       var password = $("input[name=password]").val();
       var re_password = $("input[name=re_password]").val();
       var zip_code1 = $("input[name=zip_code1]").val();
       var zip_code2 = $("input[name=zip_code2]").val();
       var pref_id = $('select[name=pref_id]').val();
       var city_id = $('select[name=city_id]').val()
       var address = $("input[name=address]").val();
       var phone = $("input[name=phone]").val();
       var fax = $("input[name=fax]").val();

       if(customer_name == "" || customer_name == null || customer_name == undefined){
           $("#customer_name_err").text("代表者・登録者名称は必須です。");
           $("#customer_name_err").show();
           $("input[name=customer_name]").focus();
           return false;
       }

       if(company_name == "" || company_name == null || company_name == undefined){
           $("#company_name_err").text("会社・組織名称は必須です。");
           $("#company_name_err").show();
           $("input[name=company_name]").focus();
           return false;
       }
       if(mail_address == "" || mail_address == null || mail_address == undefined){
           $("#mail_address_err").text("登録メールアドレスは必須です。");
           $("#mail_address_err").show();
           $("input[name=mail_address]").focus();
           return false;
       }
       if(!validateEmail(mail_address)){
           $("#mail_address_err").text("メールアドレスの形式に誤りがあります。");
           $("#mail_address_err").show();
           $("input[name=mail_address]").focus();
           return false;
       }
       if(testExistMail(mail_address) == 1){
            $("#mail_address_err").text("メールアドレスは既に存在しています。");
            $("#mail_address_err").show();
            $("input[name=mail_address]").focus();
            return false;
       }
       if(password == "" || password == null || password == undefined){
           $("#password_err").text("パスワードは必須です。");
           $("#password_err").show();
           $("input[name=password]").focus();
           return false;
       }
       if(!validatePassword(password)){
           $("#password_err").text("パスワードの形式に誤りがあります。");
           $("#password_err").show();
           $("input[name=password]").focus();
           return false;
       }
       if(re_password == "" || re_password == null || re_password == undefined){
           $("#re_password_err").text("パスワードの再確認は必須です。");
           $("#re_password_err").show();
           $("input[name=re_password]").focus();
           return false;
       }
       if(re_password != password){
           $("#re_password_err").text("パスワードが一致しません。");
           $("#re_password_err").show();
           $("input[name=re_password]").focus();
           return false;
       }
       if(zip_code1 == "" || zip_code1 == null || zip_code1 == undefined){
           $("#zip_code_err").text("郵便番号は必須です。");
           $("#zip_code_err").show();
           $("input[name=zip_code1]").focus();
           return false;
       }
       if(zip_code2 == "" || zip_code2 == null || zip_code2 == undefined){
           $("#zip_code_err").text("郵便番号は必須です。");
           $("#zip_code_err").show();
           $("input[name=zip_code2]").focus();
           return false;
       }
       if(pref_id == "" || pref_id == null || pref_id == undefined){
           $("#pref_id_err").text("都道府県は必須です。");
           $("#pref_id_err").show();
           $("input[name=pref_id]").focus();
           return false;
       }
       if(city_id == "" || city_id == null || city_id == undefined){
           $("#city_id_err").text("市区町村は必須です。");
           $("#city_id_err").show();
           $("input[name=city_id]").focus();
           return false;
       }
       if(address == "" || address == null || address == undefined){
           $("#address_err").text("番地は必須です。");
           $("#address_err").show();
           $("input[name=address]").focus();
           return false;
       }
       if(phone == "" || phone == null || phone == undefined){
           $("#phone_err").text("連絡先電話番号は必須です。");
           $("#phone_err").show();
           $("input[name=phone]").focus();
           return false;
       }
       if(!validatePhone(phone)){
           $("#phone_err").text("連絡先電話番号の形式に誤りがあります。");
           $("#phone_err").show();
           $("input[name=phone]").focus();
           return false;
       }
        if(!validateFAX(fax)){
            $("#fax_err").text("FAXの形式に誤りがあります。");
            $("#fax_err").show();
            $("input[name=fax]").focus();
            return false;
        }
        
        return true;
    }

</script>
@stop