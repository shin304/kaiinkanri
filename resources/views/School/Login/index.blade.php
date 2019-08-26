<!DOCTYPE html>
<html>
<head>
    <!-- Favicon -->
    <link rel="shortcut icon" href="/images/favicon.jpg" type="image/x-icon">
    <title>らくらく会員管理</title> @include('_parts.html_header')
</head>
<body id="login_main">
    <div id="pagetop">
        <div align="center">
            <img src="img{{$_app_path}}rakurakulogo.jpg" class="logo">
            <p class="p_login">ログイン</p>

            <form action="/school/login" method="post" name="form1" id="login_form">

                {{ csrf_field() }}

                <div class="login_box">

                    @if(count($errors))
                    <div class="login_error" style="color: red;">
                        @foreach($errors->all() as $error) {{$error}} @endforeach</div>
                    @endif
                        <div style="text-align: left; margin-left: 10px;">
                            <span class="error_message" id="loginid_err"></span>
                        </div>
                        <input type="text" id="loginid" name="loginid" placeholder="メールアドレス">
                        <input type="password" id="password" name="password" placeholder="パスワード" style="margin-bottom: 5px;">
                        <div id="hideSelect" style="display: none;">
                            <span class="">
                                <select id = "pschool_id" name = "pschool_id" class="" style="width: 310px; margin-left: 5px;">
                                    <option value="">施設を選択してください</option>
                                    @if (!empty($pschoolList))
                                        @foreach($pschoolList as $key => $item)
                                            <option value="{{$key}}"
                                                @if(array_get_not_null($request, 'pschool_id') == $key) selected @endif>{{$item}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </span><br/>
                            <div style="text-align: left; margin-left: 10px;">
                                <span class="error_message" id="pschool_err"></span>
                            </div>
                        </div>

                        {{--@if (Session::has('message'))--}}
                            {{--<div class="alert alert-warning">{{ Session::get('message') }}</div>--}}
                        {{--@endif--}}

                        <input type="button" style="margin-bottom: 30px;margin-top: 20px;"
                               value="ログイン" name="btn_submit" id="btn_submit">
                    <a href="/input_email" target="_self" style="color: blue;">パスワードをお忘れの場合</a>
                </div>
                <!--.login_box-->

            </form>
        </div>
    </div>
    <!-- end if PAGETOP -->
<!-- Javascript begin -->
<script type="text/javascript">
    $(document).ready(function (){
        $("#password").keyup(function(event){
            if(event.keyCode == 13){
                // $("#login_form").submit();
                $("#btn_submit").click();
            }
        });
    });

    function getPschool(loginid, password){
        $.ajax({
            type: "post",
            async : false,
            url: "/ajaxGetPschool",
            data: {
                _token: "{{ csrf_token() }}",
                loginid: loginid,
                password: password
            },
            success: function (data) {

                var result = data;
                if (data.length === 0) {
                    $("#loginid_err").text("ユーザー名またはパスワードが指定されていません。");
                    $("#loginid_err").show();
                    $("input[name=loginid_id]").focus();
                } else {
                    var html = "<option value=''>施設を選択してください</option>";
                    for (x in result) {
                        html = html + "<option value=" + x + ">" + result[x] + "</option>";
                    }

                    $('#pschool_id').html(html);

                    if (Object.keys(data).length > 1) {
                        $("#hideSelect").show();
                        $("input[name=loginid]").prop('readonly', true);
                        $("input[name=password]").prop('readonly', true);
                    } else {
                        var pschool_id = $('select[name=pschool_id] option:eq(1)').val();
                        $('select[name=pschool_id] option:eq(1)').attr('selected', 'selected');
                    }
                }

            }
        });
    }

    //submit event
    $("#btn_submit").click(function () {
        var loginid = $("input[name=loginid]").val();
        var password = $("input[name=password]").val();
        var pschool_id = $('select[name=pschool_id]').val();
        $(".error_message").hide();
        if (loginid !== "" || password !== "") {
            if(pschool_id == "" || pschool_id == null || pschool_id == undefined){
                getPschool(loginid, password);
            }
        }

        if (!handleInput()) {
            return false;
        }else{
            $("#login_form").submit();
        }

    });

    // handle input by js
    function handleInput(){
        // check input required
        // $(".error_message").hide();

        var loginid = $("input[name=loginid]").val();
        var password = $("input[name=password]").val();
        var pschool_id = $('select[name=pschool_id]').val();
        if ($('select[name=pschool_id] option').length == 2) {
            return true;
        }

        if(loginid == "" || loginid == null || loginid == undefined){
            $("#loginid_err").text("ユーザー名またはパスワードが指定されていません。");
            $("#loginid_err").show();
            $("input[name=loginid_id]").focus();
            return false;
        }

        if(password == "" || password == null || password == undefined){
            $("#loginid_err").text("ユーザー名またはパスワードが指定されていません。");
            $("#loginid_err").show();
            $("input[name=loginid_id]").focus();
            return false;
        }

        if(pschool_id == "" || pschool_id == null || pschool_id == undefined){
            $("#pschool_err").text("施設は必須です。");
            $("#pschool_err").show();
            $("input[name=pschool_id]").focus();
            return false;
        }

        return true;
    }
</script>
<!-- Javascript end -->
</body>
</html>