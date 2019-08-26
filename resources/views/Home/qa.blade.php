@extends('Home.master_layout')
@section('content')
<div class="sub_topbnr">
    <div class="width">

    </div>
</div>

<div class="main_content sub_content">
    <div class="width">
        <h2>よくあるご質問</h2>
        <p class="tc mb20">お客様からのよくあるご質問をまとめました。<br/>下記にない場合はお気軽にお問い合わせください。</p>
    </div><!-- width -->

    <div class="table_bg_box">
        <div class="width">
            <div class="table_bg_box1">
                <h3>機能について</h3>
                <div class="qa_box">
                    <ul>
                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">10名まで無料とのことですが、ずっと無料で使えますか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">はい。新しくスクール運営を始める方に気軽にご利用いただけるよう、10名までの利用であればずっと無料でご利用いただけます。</p>
                            </div>
                        </li>

                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">スポーツジムでしか、使えませんか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">
                                    元は、学習塾の経営の効率を考えて構築されたシステムですが、生徒の管理、請求書作成等を必要とする全ての事業者様全般に使える仕組みになっております。</p>
                            </div>
                        </li>

                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">追加料金は必要ありませんか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">生徒管理システムは他にも多くございますが、他社には負けない、低価格でご提供させていただいております。</p>
                            </div>
                        </li>

                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">サーバーは別途用意する必要はありますか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">クラウドでのご提供となりますので、サーバーのご用意は不要です。弊社らくらく管理専用のサーバーでご利用いただけます。</p>
                            </div>
                        </li>


                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">無料で利用したいのですが可能ですか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">キャンペーン中は、3ヶ月間無料でご利用頂けます。また10名様まででしたら、無料で、ご利用頂けます。</p>
                            </div>
                        </li>

                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">スタンダードプランとエンタープライズプランの違いは何ですか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">スタンダードプランは1箇所（支部）のプランで、エンタープライズプランは、支部を管理する本部機能を持ったプランになります。</p>
                            </div>
                        </li>

                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">先生や講師が大勢いるのですが、運営側で管理が出来ますか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">はい、出来ます。運営側から講師のプロフィールは勿論、スケジュールの管理等も行えます。</p>
                            </div>
                        </li>

                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">最初に会員登録をする際には、入力作業が手間になりませんか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">エクセルデータで現在管理されている場合、すぐに会員登録情報は一括で登録が可能となっておりますので、ご安心下さい。</p>
                            </div>
                        </li>

                        <li>
                            <div class="qa_q">
                                <img src="/img{{$_app_path}}icon_q.png" class="q_icon" alt="">
                                <p class="q_title">現在、顧客情報はエクセルデータ等ではなく、紙で管理している場合は入力に手間がかかりますか？</p>
                            </div>
                            <div class="qa_a">
                                <p class="q_a1">
                                    エクセルデータが無い場合、紙ベースですと顧客情報は手入力で登録が必要になります。入力代行（有料）に付きましても弊社で承っておりますので、ご相談下さいませ。</p>
                            </div>
                        </li>

                    </ul>

                </div><!-- qa_box -->

                <script>
                    $(".qa_a").css("display", "none");
                    $(function () {
                        $(".qa_q").on("click", function () {
                            $(this).next().slideToggle("fast");
                        });
                    });
                </script>
            </div><!-- table_bg_box1 -->
        </div><!-- table_bg_box -->
    </div><!-- width -->
    <a href="{{$_app_path}}contact">
        <button class="btn_big center mb40">お問合せ</button>
    </a>
</div><!-- main_content -->
@stop