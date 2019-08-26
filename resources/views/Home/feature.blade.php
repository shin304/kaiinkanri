@extends('Home.master_layout')
@section('content')
<div class="sub_topbnr">
    <div class="width">

    </div>
</div>

<div class="main_content sub_content">
    <div class="width">
        <h2>らくらく会員管理の機能一覧</h2>
    </div><!-- width -->

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <h3>らくらく管理　機能一覧</h3>
                <p class=" mb20">生徒一人一人の細かいデータ、レッスン、スケジュール、口座情報等、全ての情報を管理
                    一元管理する事により、生徒へのお知らせ・一斉メールの配信等、手間がかかりません
                    本部機能を持っているので、支部含め大人数の生徒の情報も管理できる。</p>
                <table class="table1">
                    <tbody>
                    <tr>
                        <th class="t1_th1">生徒管理</th>
                        <td>・生徒の氏名、生年月日、住所、電話番号、メールアドレス、引き落とし口座情報<br/>
                            ・同じスクール内にいる、兄弟等の家族の情報、連携機能。<br/>
                            ・レッスンへの出欠管理や、来校履歴などの情報が確認出来ます。
                        </td>
                    </tr>
                    <tr>
                        <th class="t1_th1">支払い者管理</th>
                        <td>毎月発生する月謝で誰が支払い、誰がまだ口座から引き落とし未払いの状態か分かります。
                            また、チケット制を採用している場所では会員証にバーコード等と連動させて、誰が何回どこのジムに来ているか等の詳細なデータを、管理画面で見る事が可能です。
                        </td>
                    </tr>
                    <tr>
                        <th class="t1_th1">講師管理</th>
                        <td>運営側から、講師、先生のプロフィールは勿論、スケジュール管理も行う事が出来ます。</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">プラン（クラス）管理</th>
                        <td>体験レッスンや、初心者向けのプラン等の振り分け、管理が出来ます。</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">プログラム（コース）管理</th>
                        <td>・各プログラム（コース）毎に生徒を管理する事が出来ます。<br/>
                            ・各コース毎に、料金設定の管理や出欠管理などを行うことが出来ます。
                        </td>
                    </tr>
                    <tr>
                        <th class="t1_th1">生徒来校情報/成績管理</th>
                        <td>生徒が何回来ているのか、またレッスンを何回受けているか、そして生徒の成績の管理も
                            全て管理画面にて、見る事が出来ます。
                        </td>
                    </tr>
                    <tr>
                        <th class="t1_th1">本部・支部管理</th>
                        <td>本部、支部機能を持つ道場やジム等の場合、本部と各支部との連携させる事で、全ての生徒の管理を本部側から、行う事が出来ます。</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">イベント管理</th>
                        <td>ジムや、道場等で年末年始の休業日、またジムで開催される特別講師を招いてのイベントが発生した際には、事前に生徒へ一斉送信でメールを送り知らせる事が出来ます。
                        </td>
                    </tr>
                    <tr>
                        <th class="t1_th1">面談管理</th>
                        <td>入会前に子供や、兄弟で申し込みたい場合等、申し込み主のご両親との面談日時を管理する事が出来ます。</td>
                    </tr>

                    <tr>
                        <th class="t1_th1">メール一斉送信機能</th>
                        <td>管理している生徒に向けて、一斉送信でお知らせ、既読情報、申し込み数も一目で管理出来るようになります。</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">メール管理</th>
                        <td>既存の会員からのメール、また外部からの問い合わせメール等も全て管理出来ます。</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">請求書一括作成</th>
                        <td>・請求書の情報は生徒の管理情報から一括管理して、CSVで発行が可能です。<br/>
                            ・請求書の作成、発行（切手、郵送）、確認等全ての手間・コストを大幅に削減可能です。<br/>
                            ・口座振替をして頂く事で、毎月の振込み手数料が発生する心配がございません。<br/>
                            ・その他、口座振替に関してのメリットは現金の取り扱いが無くなるので紛失・盗難リスクがなくなります。また集金にかかるコストも発生いたしません。
                        </td>
                    </tr>
                    <tr>
                        <th class="t1_th1">口座振替</th>
                        <td>毎月の月謝は口座振替をして頂く事で、自動振り替えなので振込み手数料を引かれる心配がございません。</td>
                    </tr>
                    <tr>
                        <th class="t1_th1">外部連携</th>
                        <td>その他、外部との連携機能もカスタマイズする事により、可能になります。</td>
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