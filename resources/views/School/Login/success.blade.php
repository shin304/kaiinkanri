<!DOCTYPE html>
<html>
<head>
<!-- Favicon -->
<link rel="shortcut icon" href="/images/favicon.jpg" type="image/x-icon">
<title>完了</title> 
<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1"/>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" type="text/css" href="/css/school/style.css"/>

<link href='https://fonts.googleapis.com/css?family=Libre+Baskerville' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>

<!-- jQuery library (served from Google) -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>

<style type="text/css">
    .rf-field {
        padding-top: 0.75em;
        padding-right: 1em;
        padding-bottom: 0.75em;
        padding-left: 1em;
        max-width: 100%;
        font-size: 16px;
        line-height: normal;
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
        border-image-source: initial;
        border-image-slice: initial;
        border-image-width: initial;
        border-image-outset: initial;
        border-image-repeat: initial;
        border-top-left-radius: 0.1em;
        border-top-right-radius: 0.1em;
        border-bottom-right-radius: 0.1em;
        border-bottom-left-radius: 0.1em;
        background-color: #f6f6f6;
        box-sizing: border-box;
        -webkit-appearance: none;
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
        border-bottom-color: rgb(221, 221, 221);
        padding: 1em 0;
        margin: 0;
    }
    .rf-form {
        margin-right: 0px;
        margin-bottom: 1em;
        margin-left: 0px;
        border-top: 1px solid #ddd;
        border-top-width: 1px;
        border-top-style: solid;
        border-top-color: rgb(221, 221, 221);
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
        border-width: 1px;
        border-style: solid;
        border-radius: .1em;
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
</style>
<body id="login_password_main">
    <div id="pagetop_main">
        <div align="center">
            <img src="img/school/rakurakulogo.jpg" class="logo">
            <div align="left" style="margin-right: 25%;  margin-left: 25%;">
                <div class="login_box" style="padding-top: 20px; margin-top: 10px; width: 620px; height: 300px;">
                    <div align="center">
                        <div class="row text-center">
                            <div class="col-sm-6 col-sm-offset-3">
                            <!-- <br><br> <h2 style="color:#0fad00">完了</h2> -->
                            <img src="img/school/check-logo.png" height="216" width="250">
                            <h3 style="font-size:25px;">{{$name}}様</h3>
                            <p style="font-size:20px;color:#5C5C5C;">パスワードの変更を受け付けました。 <br />メールのチェックをお願いします。</p>
                        <br><br>
                            </div>
                            
                        </div>
                    </div>
              </div>
                <!--.login_box-->
            </div>
           <!--  </form> -->
        </div>
    </div>
    <!-- end if PAGETOP -->
</body>
</html>