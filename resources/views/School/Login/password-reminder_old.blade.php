<!DOCTYPE html>
<html>
<head>
<!-- Favicon -->
<link rel="shortcut icon" href="/images/favicon.jpg" type="image/x-icon">
<title>パスワードの再設定</title> 
<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1"/>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" type="text/css" href="/css/school/style.css"/>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css"/>

<link href='https://fonts.googleapis.com/css?family=Libre+Baskerville' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>

<!-- jQuery library (served from Google) -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>

</head>

<style type="text/css">
    .rf-field {
        padding-top: 0.75em;
        /*padding-right: 1em;*/
        padding-bottom: 0.75em;
        padding-left: 1em;
        max-width: 100%;
        font-size: 14px;
        /*line-height: normal;*/
        border-top-color: rgb(221, 221, 221);
        border-top-style: solid;
        border-top-width: 1px;
        border-right-color: rgb(221, 221, 221);
        border-right-style: solid;
        border-right-width: 1px;
        border-bottom-color: rgb(221, 221, 221);
        border-bottom-style: solid;
        border-bottom-width: 1px;
        border-left-color: rgb(221, 221, 221);
        border-left-style: solid;
        border-left-width: 1px;
        /*border-image-source: initial;
        border-image-slice: initial;
        border-image-width: initial;
        border-image-outset: initial;
        border-image-repeat: initial;*/
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        /*background-color: #f6f6f6;*/
        box-sizing: border-box;
        /*-webkit-appearance: none;*/
    }
    .rf-block {
        display: block;
        width: 100%;
    }
    .rf-form-label {
        display: block;
        font-weight: 700;
    }
    .rf-form>fieldset {
        border-top: none;
        border-right: none;
        border-left: none;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: rgb(255, 255, 255);
        padding: 1em 0;
        margin: 0;
    }
    .rf-form {
        margin-right: 0px;
        margin-bottom: 1em;
        margin-left: 0px;
        /*border-top: 1px solid #ddd;
        border-top-width: 1px;
        border-top-style: solid;
        border-top-color: rgb(221, 221, 221);*/
    }
    mark.rf-label {
        padding-top: 0.1em;
        padding-right: 0.5em;
        padding-bottom: 0.1em;
        padding-left: 0.5em;
        color: #bf0000;
    }
    mark.rf-label, mark.rf-label-primary {
        display: inline-block;
        background: 0 0;
    }
    .rf-form-label .rf-align-right {
        float: right;
        font-weight: 400;
    }
    .rf-silver {
        color: #bbb;
    }
    .rf-tiny {
        font-size: .786em;
    }
    .rf-button-primary {
        color: #fff;
        border-top-color: #036EB8;
        border-right-color: #036EB8;
        border-bottom-color: #036EB8;
        border-left-color: #036EB8;
        background: #036EB8 !important;;
    }
    [class*=rf-button] {
        cursor: pointer;
        display: inline-block;
        margin: .5em 0;
        padding: .75em 1.5em;
        font-weight: 600;
        line-height: inherit;
        text-align: center;
        vertical-align: middle;
        border-width: 0px;
        border-style: solid;
        border-radius: 5px;
    }
    .login_box{
        width: 320px;
        margin-top: 30px;
        border: solid 1px #fff;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 3px;

        padding: 50px 30px;
        box-shadow: 0 0px 3px rgba(64, 64, 64, 0.42);
    }
    .p_login{
        font-size: 26px;
        font-weight: 100;
        color: #595757;
    }
    .login_box input[type="text"], .login_box input[type="password"] {
        padding: 12px 3%;
        width: 100%;
    }
    #login_password_main {
        border-top: solid 5px #003389;
        padding-top: 40px;
    }
</style>
<body id="login_password_main">
    <div id="pagetop_main">
        <div align="center">
            <img src="img/school/rakurakulogo.jpg" class="logo">
            <p class="p_login">パスワードの再設定</p>
            <!-- <form action="/school/password_reminder" method="post" name="form1" id="login_form"> -->
            <div align="left" style="margin-right: 36%;  margin-left: 36%;">
                <div class="login_box" style="padding-top: 20px; margin-top: 10px; width: 320px;">
                <!-- input error -->
                
                
                <!-- //input error -->
                <form class="rf-form" lang="ja" action="/passwordChange" method="POST" name="form_password" id="check_new_password_form">
                  {{ csrf_field() }}
                   <!-- @if(count($errors))
                    <div class="login_error" style="color: red;">
                        @foreach($errors->all() as $error) 
                            {{$error}} 
                        @endforeach
                    </div>
                   @endif -->
                  <fieldset>
                    <label>
                        <span class="rf-form-label" style="padding-top: 1em; font-weight: 700;">メールアドレス<mark class="rf-label rf-tiny">*</mark>
                        <!-- <span class="rf-align-right rf-silver rf-tiny">半角英数字</span> -->
                        </span>
                        <!-- check email exist or not -->
                        <!-- <input name="valid_email" id="valid_email" class="rf-field rf-block " type="email" placeholder="メールアドレス" onblur="testExistMail()"> -->
                        <input name="valid_email" id="valid_email" class="rf-field rf-block " type="email" placeholder="メールアドレス" onChange="hadelEmail();">
                    </label>
                    <div id="msg_error_email" style="display:none; color: red">メールアドレスは無効です。</div>
                    <div id="msg_success_email" style="display:none; color: green">メールアドレスは有効です。</div>
                    <div id="msg_error_email_null" style="display:none; color: red">メールアドレスは必須です。</div>
                    <div id="msg_error_email_format" style="display:none; color: red">メールアドレスの形式に誤りがあります。</div>
                  </fieldset>
                  
                  <fieldset>
                    <label>
                        <span class="rf-form-label">新しいパスワード<mark class="rf-label rf-tiny">*</mark></span>
                        <label style="color: #9e6d3b;">パスワード 半角英数文字または特殊文字 (-,_,.,$,#,:@,!) で8文字以上16文字以下</label>
                        <input name="new_password" id="new_password" style="margin-bottom: 3px; padding-top: 0.75em; padding-right: 1em; padding-bottom: 0.75em; padding-left: 1em;" class="rf-field rf-block " type="password" minlength="8" maxlength="16" placeholder="新しいパスワード" onChange="hadelPassword();">
                    </label>
                    <div id="msg_error_password_null" style="display:none; color: red">パスワードは必須です。</div>
                    <div id="msg_error_password_format" style="display:none; color: red">パスワードの形式に誤りがあります。</div>
                  </fieldset>

                  <fieldset>
                    <label>
                        <span class="rf-form-label">パスワードの再確認<mark class="rf-label rf-tiny">*</mark></span>
                        <input name="confirm_new_password" id="confirm_new_password" style="margin-bottom: 3px; padding-top: 0.75em; padding-right: 1em; padding-bottom: 0.75em; padding-left: 1em;" class="rf-field rf-block " type="password" minlength="8" maxlength="16" placeholder="パスワードの再確認" onChange="hadelConfirmPassword();">
                    </label>
                    <div id="msg_error_confirm_password_null" style="display:none; color: red">パスワードは必須です。</div>
                    <!-- <div style="font-style: italic; color: #9e6d3b" class="registrationFormAlert" id="divCheckPasswordMatch"></div> -->
                    <div style="font-style: italic; color: red" id="divCheckPasswordMatch"></div>
                  </fieldset>
                  
                  <input type="hidden" name="module" value="">

                  <!-- <p> <button name="register" type="submit" id="submit" class="rf-button-primary rf-block rf-large""><span class="glyphicon glyphicon-envelope"></span> 本人確認メールを送信</button> </p> -->
                  <p>
                      <input name="register" id="submit" type="button" class="rf-button-primary rf-block rf-large" 
                                            value="送信" onclick="return handleChange()">
                  </p>
                </form>
              </div>
                <!--.login_box-->
            </div>
           <!--  </form> -->
        </div>
    </div>
    <!-- end if PAGETOP -->
    <div id="dialog_active_password" class="no_title" style="display:none;">
        メールが送信されますので、受け取ったメールの指示に従って操作してください
    </div>
<!-- Javascript begin -->
<script type="text/javascript">
    $(document).ready(function (){
        $("#password").keyup(function(event){
            if(event.keyCode == 13){
                $("#check_new_password_form").submit();
            }
        });
    })
</script>
<script type="text/javascript">
// function checkPasswordMatch() {
//     $('#msg_error_confirm_password_null').hide();
//     var password = $("#new_password").val();
//     var confirmPassword = $("#confirm_new_password").val();

//     if (password != confirmPassword) {
//         $("#divCheckPasswordMatch").html("パスワードが一致しません！");
//     }
//     else {
//         $("#divCheckPasswordMatch").html("パスワードが一致！");
//     }
// }

// $(document).ready(function () {
//    $("#confirm_new_password").keyup(checkPasswordMatch);
// });
</script>
<script type="text/javascript">
function testExistMail() {
    var email=$("#valid_email").val();// value in field email
    $.ajax({
        type:'post',
        url:'/email',// put your real file name 
        data:{email: email, _token: "{{ csrf_token() }}"},
        success:function(data){
            if(data==1) {
                console.log('ok');
                $('#msg_error_email').hide();
                $('#msg_success_email').show();
                $('#submit').attr('disabled',false);
            }
            else {
                console.log('error');
                $('#msg_success_email').hide();
                $('#msg_error_email').show();
                $('#submit').attr('disabled',true);
            }
        },
        error: function(data) {
            console.log('error');
        }
    });
    $('#check_new_password_form').submit();
}
</script>
<script type="text/javascript">
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
function validatePassword(password) {
  var re = (/^[a-z A-Z 0-9\-_ \\\\.#\$:@\!]{8,}$/);
  return re.test(password);
}

function handleChange(input) {
    var email = $("#valid_email").val();　//　document.getElementById("valid_email").value;
    var password = $("#new_password").val();　//　document.getElementById("new_password").value;
    var confirm_password = $("#confirm_new_password").val();　//　document.getElementById("confirm_new_password").value;
    if (email === "") {
        $('#msg_error_email_null').show();
        $('#msg_error_email_format').hide();
        return false;
    }
    else if (password === "") {
        $('#msg_error_password_null').show();
        return false;
    }
    else if (confirm_password === "") {
        $('#msg_error_confirm_password_null').show();
        return false;
    }
    else if ((email === "") && (password === "") && (confirm_password === "")) {
        $('#msg_error_email_null').show();
        $('#msg_error_password_null').show();
        $('#msg_error_confirm_password_null').show();
        return false;
    }
    else if (!validateEmail (email)) {
        $('#msg_error_email_format').show();
        return false;
    }
    else if (!validatePassword (password)) {
        $('#msg_error_password_format').show();
        return false;
    }
    else if (password != confirm_password) {
        $("#divCheckPasswordMatch").html("パスワードが一致しません！");
        return false;
    }
    else {
        $("#dialog_active_password").dialog('open');
            return false;
    }
    return true;
}

function hadelEmail() {
    $('#msg_error_email_null').hide();
    var email = $("#valid_email").val();
    if (email == "") {
        $('#msg_error_email_null').show();
    }
    else {
        $('#msg_error_email_null').hide();
    }
}
function hadelPassword() {
    $('#msg_error_password_null').hide();
    var password = $("#new_password").val();
    if (password == "") {
        $('#msg_error_password_null').show();
    }
    else {
        $('#msg_error_password_null').hide();
    }
}
function hadelConfirmPassword() {
    $('#msg_error_confirm_password_null').hide();
    var password = $("#confirm_new_password").val();
    if (password == "") {
        $('#msg_error_confirm_password_null').show();
    }
    else {
        $('#msg_error_confirm_password_null').hide();
    }
}
</script>
<script type="text/javascript">
    $(function() {
        $('#dialog_active_password').dialog({ 
            title: 'お知らせ送信',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: [
                {
                    text: "OK",
                    // click: $.noop,
                    type: "submit",
                    form: "check_new_password_form", // <-- Make the association
                    click: function(){
                        $( this ).dialog( "close" );
                    }
                },
                {
                    text: "キャンセル",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
            // buttons: {
            //     "OK": function () {
            //         $(this).dialog("close");
            //         $("#check_new_password_form").attr('action', '/passwordChange');
            //         $("#check_new_password_form").submit();
            //         return false;
            //     },
            //     "キャンセル": function () {
            //         $(this).dialog("close");
            //         return false;
            //     }
            // } 
        });
    });
</script>
<!-- Javascript end -->
</body>
</html>