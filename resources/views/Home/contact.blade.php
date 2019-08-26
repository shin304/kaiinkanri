@extends('Home.master_layout')
@section('content')
<div class="sub_topbnr">
    <div class="width"></div>
</div>
<div class="main_content sub_content">
    <div class="width">
        <h2>お問い合わせ</h2>
    </div><!-- width -->

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <form method="post" action="/home/contact_confirm">
                    {{ csrf_field() }}
                    <table class="table1">
                        <tr>
                            <th style="width:28%;">
                                <span class="hissu">必須</span><br />
                                お名前</th>
                            <td>
                                <span class=""><input type="text" name="name" value="" size="40" class="text1"/></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <span class="hissu">必須</span><br />
                                メールアドレス
                            </th>
                            <td>
                                <span class=""><input type="text" name="mail" value="" size="40" class="text1"  /></span>
                            </td>
                        </tr>
                        <tr>
                            <th>

                                会社名
                            </th>
                            <td>
                                <span class=""><input type="text" name="company" value="" size="40" class="text1"  /></span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                電話番号
                            </th>
                            <td>
                                <span class=""><input type="text" name="tel" value="" size="40" class="text1"/></span>
                            </td>
                        </tr>
                        <tr>
                            <th>

                                お問い合わせ種別
                            </th>
                            <td>
                                <span class="">
                                    <select name="type" class="">
                                        <option value="記事について">記事について</option>
                                        <option value="サービスについて">サービスについて</option>
                                        <option value="弊社について">弊社について</option>
                                        <option value="その他">その他</option>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                お問い合わせプラン
                            </th>
                            <td>
                                <label><input type="checkbox" name="plans[]" value="フリープラン"> フリープラン</label><br />
                                <label><input type="checkbox" name="plans[]" value="スタンダードプラン"> スタンダードプラン</label><br />
                                <label><input type="checkbox" name="plans[]" value="エンタープライズプラン"> エンタープライズプラン</label><br />
                            </td>
                        </tr>
                        <tr>
                            <th>お問い合わせ内容</th>
                            <td>
                                <span class=""><textarea name="qa_content" cols="40" rows="10" ></textarea></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" value="　 確認 　" class="submit1" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div><!-- width -->
</div><!-- main_content -->
@stop