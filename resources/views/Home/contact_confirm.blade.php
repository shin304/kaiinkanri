@extends('Home.master_layout')
@section('content')
<div class="sub_topbnr">
    <div class="width"></div>
</div>
<div class="main_content sub_content">
    <div class="width">
        <h2>確認画面</h2>
    </div><!-- width -->

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <form method="post" action="/home/contact_send">
                    {{ csrf_field() }}
                    <table class="table1">
                        <tr>
                            <th style="width:28%;">
                                お名前
                            </th>
                            <td>
                                <span class="">
                                    <input type="hidden" name="name" value="{{request()->name}}"/>
                                    {{request()->name}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                メールアドレス
                            </th>
                            <td>
                                <span class="">
                                    <input type="hidden" name="mail" value="{{request()->mail}}" />
                                    {{request()->mail}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                会社名
                            </th>
                            <td>
                                <span class="">
                                    <input type="hidden" name="company" value="{{request()->company}}" />
                                    {{request()->company}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                電話番号
                            </th>
                            <td>
                                <span class="">
                                    <input type="hidden" name="tel" value="{{request()->tel}}"/>
                                    {{request()->tel}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                お問い合わせ種別
                            </th>
                            <td>
                                <span class="">
                                    <input type="hidden" name="type" value="{{request()->type}}"/>
                                    {{request()->type}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                お問い合わせプラン
                            </th>
                            <td>
                                <span>{{implode(", ", request()->get('plans', array()))}}</span>
                                @foreach(request()->get('plans', array()) as $plan)
                                    <input type="hidden" name="plans[]" value="{{$plan}}">
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>お問い合わせ内容</th>
                            <td>
                               <span class="">
                                    <input type="hidden" name="qa_content" value="{{request()->qa_content}}"/>
                                   {{request()->qa_content}}
                                </span>
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