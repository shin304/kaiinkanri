@extends('Home.master_layout')
@section('content')
<div class="sub_topbnr">
    <div class="width">

    </div>
</div>

<div class="main_content sub_content">
    <div class="width">
        <h2>デモ版利用登録</h2>
    </div>

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <h3>これらの情報をデモ版に登録します</h3>
                
                <form method="post" id = "submit_form" action = "/home/storeRegister">
                    {{ csrf_field() }}
                    <table class="table1">
                        <tbody>
                            <tr>
                                <th class="t1_th1">代表者・登録者名称</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="customer_name" value="{{$request->customer_name}}"/>
                                        {{$request->customer_name}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1"> 会社・組織名称</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="company_name" value="{{$request->company_name}}"/>
                                        {{$request->company_name}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">登録メールアドレス</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="mail_address" value="{{$request->mail_address}}"/>
                                        {{$request->mail_address}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">郵便番号</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="zip_code1" value="{{$request->zip_code1}}"/>
                                        <input type="hidden" name="zip_code2" value="{{$request->zip_code2}}"/>
                                        {{$request->zip_code1}}-{{$request->zip_code2}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">都道府県名</th>
                                <td>
                                    <span class="">
                                        @if(isset($request->pref_id))
                                            <input type="hidden" name="pref_id" value="{{$request->pref_id}}"/>
                                            {{$prefList[$request->pref_id]}}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">市区町村名</th>
                                <td>
                                    <span class="">
                                        @if(isset($request->city_id))
                                            <input type="hidden" name="city_id" value="{{$request->city_id}}"/>
                                            {{$cityList[$request->city_id]}}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">番地</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="address" value="{{$request->address}}"/>
                                        {{$request->address}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">ビル名</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="building" value="{{$request->building}}"/>
                                        {{$request->building}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">連絡先電話番号</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="phone" value="{{$request->phone}}"/>
                                        {{$request->phone}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">FAX</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="fax" value="{{$request->fax}}"/>
                                        {{$request->fax}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="t1_th1">ホームページ</th>
                                <td>
                                    <span class="">
                                        <input type="hidden" name="home_page" value="{{$request->home_page}}"/>
                                        {{$request->home_page}}
                                        <input type="hidden" name="password" value="{{$request->password}}"/>
                                        <input type="hidden" name="re_password" value="{{$request->re_password}}"/>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <input type="submit" value="登録" class="blue_btn" id = "btn_submit"/> &nbsp;&nbsp;&nbsp;
                                    <input type="button" value="戻る" class="blue_btn" id = "btn_return" />
                                </td>
                            </tr>
                        </tbody>
                    </table><!-- table1 -->
                </form><!--form-->
            </div><!-- table_bg_box1 -->
        </div><!-- table_bg_box -->
    </div><!-- width -->
    {{--<a href="{{$_app_path}}contact">--}}
        {{--<button class="btn_big center mb40">お問合せ</button>--}}
    {{--</a>--}}
</div><!-- main_content -->
<script>
    $(document).ready(function () {

        $("#btn_return").click(function () {

            $("#submit_form").attr('action','/home/register');

            $("#submit_form").submit();
        })

    })
</script>
@stop