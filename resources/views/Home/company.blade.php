@extends('Home.master_layout')
@section('content')

<div class="sub_topbnr">
    <div class="width">
    </div>
</div>

<div class="main_content sub_content">
    <div class="width">
        <h2>会社概要</h2>
    </div><!-- width -->

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <table class="table1">
                    <tbody>
                    <tr>
                        <th class="t1_th1">会社名</th>
                        <td>株式会社ASTO System</td>
                    </tr>

                    <tr>
                        <th class="t1_th1">事業内容</th>
                        <td>システム開発<br/>
                            <a target="_blank" href="http://s.rakujyuku.com/">学習塾管理システム 楽スクールの運営管理</a>
                        </td>
                    </tr>
                    <tr>
                        <th class="t1_th1">所在地</th>
                        <td>東京都千代田区鍛冶町1-4-7 彦田ビル2F</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">電話／FAX</th>
                        <td>03-5297-8207／03-5297-8209</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">代表取締役</th>
                        <td>高尾 哲</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">取引先銀行</th>
                        <td>三菱東京UFJ銀行　　巣鴨信用金庫</td>
                    </tr>
                    </tbody>
                </table><!-- table1 -->
            </div><!-- table_bg_box1 -->
        </div><!-- table_bg_box -->
    </div><!-- width -->
    <a href="{{$_app_path}}contact">
        <button class="btn_big center mb40">お問合せ</button>
    </a>
</div><!-- main_content -->
@stop