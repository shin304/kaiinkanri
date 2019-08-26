@extends('Home.master_layout')
@section('content')
<div class="sub_topbnr">
    <div class="width">

    </div>
</div>

<div class="main_content sub_content">
    <div class="width">
        <h2>404</h2>
    </div><!-- width -->

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <table class="table1">
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <span class="">
                                    メールアドレスの確認のエラーが発生されました。<br/>
                                    時間が切れた。もう一度登録してください。
                                    <a style="font-size:20px;color:blue;" href="/home/register">登録</a>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- table_bg_box1 -->
        </div><!-- table_bg_box -->
    </div><!-- width -->
</div><!-- main_content -->
@stop