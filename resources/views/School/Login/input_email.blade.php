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
        padding-bottom: 0.75em;
        padding-left: 1em;
        max-width: 100%;
        font-size: 14px;
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
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        box-sizing: border-box;
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
        margin-left: auto;
        margin-right: auto;
        width: 320px;
        margin-top: 30px;
        border: solid 1px #fff;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 3px;
        padding-bottom: 30px;
        padding-right: 30px;
        padding-left: 30px;
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
            <div align="left" style="margin-right: 36%;  margin-left: 36%;">
                <div class="login_box" style="padding-top: 20px; margin-top: 10px; width: 320px;">
                <!-- input error -->
                
                
                <!-- //input error -->
                <form class="rf-form" lang="ja" action="/password_reminder" method="POST" name="form_password" id="input_email_form">
                  {{ csrf_field() }}
                  <fieldset>
                    <label>
                        <span class="rf-form-label" style="padding-top: 1em; font-weight: 700;">メールアドレス<mark class="rf-label rf-tiny">*</mark>
                        </span>
                        <!-- check email exist or not -->
                        <!-- <input name="valid_email" id="valid_email" class="rf-field rf-block " type="email" placeholder="メールアドレス" onblur="testExistMail()"> -->
                        <input name="valid_email" id="valid_email" class="rf-field rf-block " type="email" placeholder="メールアドレス" onChange="handleEmail();">
                    </label>
                    <div id="msg_error_email" style="display:none; color: red">メールアドレスは無効です。</div>
                    <div id="msg_success_email" style="display:none; color: green">メールアドレスは有効です。</div>
                    <div id="msg_error_email_null" style="display:none; color: red">メールアドレスは必須です。</div>
                    <div id="msg_error_email_format" style="display:none; color: red">メールアドレスの形式に誤りがあります。</div>
                  </fieldset>
                  
                  <input type="hidden" name="module" value="">

                  <!-- <p> <button name="register" type="submit" id="submit" class="rf-button-primary rf-block rf-large""><span class="glyphicon glyphicon-envelope"></span> 送信</button> </p> -->
                  <p>
                      <input name="register" id="submit" type="submit" class="rf-button-primary rf-block rf-large" 
                                            value="次へ" onclick="return handleChange()">
                  </p>
                </form>
              </div>
                <!--.login_box-->
            </div>
           <!--  </form> -->
        </div>
    </div>
    <!-- end if PAGETOP -->
<!-- Javascript begin -->
<script type="text/javascript">
    $(document).ready(function() {
        $("#password").keyup(function(event) {
            if (event.keyCode == 13) {
                $("#input_email_form").submit();
            }
        });
    })

    function testExistMail(email) {
        var email = $("#valid_email").val(); // value in field email
        var result = null;
        $.ajax({
            type: 'post',
            async: false,
            url: '/email', // put your real file name 
            data: {
                email: email,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data == 1) {
                    console.log('ok');
                    // $('#msg_error_email').hide();
                    // $('#msg_success_email').show();
                    // $("#input_email_form").submit();
                    result = 1;
                } else {
                    console.log('error');
                    // $('#msg_success_email').hide();
                    // $('#msg_error_email').show();
                    result = 0;
                }

            },
            error: function(data) {
                console.log('error');
            }
        });
        return result;
    }

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function handleChange(input) {
        var email = $("#valid_email").val();　 //　document.getElementById("valid_email").value;
        var password = $("#new_password").val();　 //　document.getElementById("new_password").value;
        var confirm_password = $("#confirm_new_password").val();　 //　document.getElementById("confirm_new_password").value;
        if (email === "") {
            $('#msg_error_email_null').show();
            $('#msg_error_email_format').hide();
            return false;
        } else if (!validateEmail(email)) {
            $('#msg_error_email_format').show();
            return false;
        } else if (testExistMail(email) == 0) {
            $('#msg_error_email').show();
            return false;
        }
        return true;
    }

    function handleEmail() {
        $('#msg_error_email_null').hide();
        $('#msg_error_email').hide();
        $('#msg_success_email').hide();
        var email = $("#valid_email").val();
        if (email == "") {
            $('#msg_error_email_null').show();
        } else {
            $('#msg_error_email_null').hide();
        }
    }
</script>
<!-- Javascript end -->
</body>
</html>