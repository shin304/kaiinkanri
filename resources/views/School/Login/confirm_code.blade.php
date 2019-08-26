<!DOCTYPE html>
<html>
<head>
<!-- Favicon -->
<link rel="shortcut icon" href="/images/favicon.jpg" type="image/x-icon">
<title>コードの確認</title> 
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
        /*margin-top: 1em;*/
        margin-right: 0px;
        margin-bottom: 1em;
        margin-left: 0px;
        /*border-top: 1px solid #ddd;
        border-top-width: 1px;
        border-top-style: solid;
        border-top-color: rgb(221, 221, 221);*/
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
    .login_box input[type="text"] {
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
            <p class="p_login">コードの確認</p>
            <div align="left" style="margin-right: 36%;  margin-left: 36%;">
                <div class="login_box" style="padding-top: 20px; margin-top: 10px; width: 320px;">
                <!-- input error -->
                
                
                <!-- //input error -->
                <form class="rf-form" lang="ja" action="/confirmCodeSucsess" method="POST" name="form_password" id="confirm_code_form">
                  {{ csrf_field() }}
                  <!--  @if(count($errors))
                    <div class="login_error" style="color: red;">
                        @foreach($errors->all() as $error) 
                            {{$error}} 
                        @endforeach
                    </div>
                   @endif -->
                  <fieldset>
                    <label>
                        <span class="rf-form-label" style="padding-top: 1em; font-weight: 700;">コードを入力してください</span>
                        <input name="valid_code" id="valid_code" class="rf-field rf-block " style="border-bottom: solid 1px rgb(204, 204, 204);" type="text" maxlength="6" onChange="handleCode();">
                    </label>
                    <div id="msg_error_code_null" style="display:none; color: red">コードは必須です。</div>
                    <div id="msg_error_code" style="display:none; color: red">コードに誤りがあります。</div>
                    <div id="msg_error_time_code" style="display:none; color: blue">時間が切れた。他のコードを入力してください。</div>
                  </fieldset>
                  <input type="hidden" name="email" value="{{$email}}">
                  <input type="hidden" name="id" value="{{$request->id}}">
                  <input type="hidden" name="code" id="code" value="{{$request->code}}">
                  <input type="hidden" name="time" id="time" value="{{$request->time}}">
                  
                  <!-- <p> <button name="confirmCode" type="submit" id="submit_code" class="rf-button-primary rf-block rf-large""><span class="glyphicon glyphicon-envelope"></span> 本人確認メールを送信</button> </p> -->
                  <p>
                      <input name="confirmCode" id="submit_code" type="submit" style="height: 40px" class="rf-button-primary rf-block rf-large" value="確認" onclick="return handleChangeSubmit()">
                  </p>
                </form>
              </div>
                <!--.login_box-->
            </div>
           <!--  </form> -->
        </div>
    </div>
    <!-- end if PAGETOP -->
    <div id="dialog_confirm_code" class="no_title" style="display:none;">
        メールが送信されますので、受け取ったメールの指示に従って操作してください
    </div>
<!-- Javascript begin -->
<script type="text/javascript">
    function handleChangeSubmit(input) {
        var confirm_code = $("#valid_code").val(); //document.getElementById("valid_code").value;
        var code = $("#code").val();
        var time = $("#time").val(); // var time = document.getElementById("time").value;
        if (confirm_code === "") {
            $('#msg_error_code_null').show();
            $('#msg_error_time_code').hide();
            $('#msg_error_code').hide();
            return false;
        } else if (time > 0) {
            $('#msg_error_time_code').show();
            $('#msg_error_code_null').hide();
            $('#msg_error_code').hide();
            return false;
        } else if (confirm_code != code) {
            $('#msg_error_code').show();
            $('#msg_error_code_null').hide();
            $('#msg_error_time_code').hide();
            return false;
        } 
        return true;
    }

    function handleCode() {
        $('#msg_error_code').hide();
        $('#msg_error_code_null').hide();
        $('#msg_error_time_code').hide();
        var code = $("#valid_code").val();
        if (code == "") {
            $('#msg_error_code_null').show();
            $('#msg_error_time_code').hide();
            $('#msg_error_code').hide();
        } else {
            $('#msg_error_code_null').hide();
        }
    }

    $(function() {
        $('#dialog_confirm_code').dialog({
            title: 'お知らせ送信',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: [{
                    text: "OK",
                    // click: $.noop,
                    type: "submit",
                    form: "confirm_code_form", // <-- Make the association
                    click: function() {
                        $(this).dialog("close");
                    }
                },
                {
                    text: "キャンセル",
                    click: function() {
                        $(this).dialog("close");
                    }
                }
            ]
        });
    });
</script>
<!-- Javascript end -->
</body>
</html>