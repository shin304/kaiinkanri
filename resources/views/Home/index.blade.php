@extends('Home.master_layout')
@section('content')
<div class="topbnr1">
    <ul class="bxslider1">
        <li><img src="/img{{$_app_path}}bnr01.jpg" class="" alt=""></li>
        <li><img src="/img{{$_app_path}}bnr02.jpg" class="" alt=""></li>
        <li><img src="/img{{$_app_path}}bnr03.jpg" class="" alt=""></li>
        <li><img src="/img{{$_app_path}}bnr04.jpg" class="" alt=""></li>
    </ul>
    <div class="top_messege">
        <div class="width">
            <div class="top_messege_box">
                <p class="p_title koz"><span>コース・プラン・イベント・請求管理まで</span><br/>
                    会員ビジネスをトータルサポート</p>
                <p class="tm_p1">これから創業する方、すでに創業して会員の管理に対して面倒さを感じている方。<br/>
                    [サービス名]は会員ビジネスを運営されている会社さまを強力なバックアップでサポートいたします。</p>
                <a href="{{$_app_path}}contact">
                    <button class="btn_big">お問い合せ</button>
                </a>
            </div><!--  top_messege_box -->
            <img src="/img{{$_app_path}}topimg.png" alt="" class="mac_img">
        </div><!-- width -->
    </div><!--top_messege  -->
</div>

<script>
    $(document).ready(function () {
        $('.bxslider1').bxSlider({
            auto: true
        });
    });
</script>

<div class="box_gray padd15">
    <div class="width">
        <div class="index_top_news">
            <div class="itn_left koz">
                NEWS
            </div>
            <div class="itn_right">
                <ul>
                    <li>
                        <p class="tab bg_red">キャンペーン実施中</p>
                    </li>
                    <li>
                        <p class="p1">スタンダードプランが使用料無料のキャンペーン実施中（2017年3月末まで）</p>
                    </li>
                </ul>
            </div>
        </div><!-- index_top_news -->
    </div>
</div><!-- index_top_news -->

<div class="main_content">
    <div class="width">
        <h2>管理にかかる労力が50%以上削減<br/>
            <span class="font70">空いた時間は事業拡大にお使いください</span>
        </h2>

        <div class="list_4">
            <ul>
                <li>
                    <div class="l4_img"><img src="/img{{$_app_path}}bnr04.jpg"></div>
                    <div class="l4_box">
                        <p class="l4_p1">生徒管理</p>
                        <p class="l4_p2">
                            生徒一人一人の細かいデータ、受講しているレッスン、請求先の情報等を管理する事が出来ます。本部と支部がある場合には設定によって、本部が全ての支部の生徒の情報まで確認することが可能です。
                        </p>
                    </div>
                </li>
                <li>
                    <div class="l4_img"><img src="/img{{$_app_path}}bnr05.jpg"></div>
                    <div class="l4_box">
                        <p class="l4_p1">プログラム（コース）管理</p>
                        <p class="l4_p2">プログラムを新しく設定したり、募集の告知を送ることができます。
                            またそれぞれのプログラムごとの生徒の管理が可能です。
                        </p>
                    </div>
                </li>
                <li>
                    <div class="l4_img"><img src="/img{{$_app_path}}bnr03.jpg"></div>
                    <div class="l4_box">
                        <p class="l4_p1">イベント管理</p>
                        <p class="l4_p2">ジムや道場で発生する様々なイベントや、試合等の管理を行う事が出来きるようになります。</p>
                    </div>
                </li>
                <li>
                    <div class="l4_img"><img src="/img{{$_app_path}}bnr06.jpg"></div>
                    <div class="l4_box">
                        <p class="l4_p1">請求管理</p>
                        <p class="l4_p2">毎回、毎月発生する月謝等の請求書の管理が便利になります。<br/>
                            また、口座振替システムとの連携に対応し、さらに請求業務が楽になります。
                        </p>
                    </div>
                </li>
            </ul>
        </div><!-- list_4 -->

        <p class="tc mb20">会員ビジネスをされている企業の想定外のコストは「管理コスト」と言われています。<br/>
            一般的に、生徒が増えれることで、運営コストも並行して増えていき、事務作業だけで、何時間もの貴重な時間を割かなければなりません。<br/>
            [サービス名]を導入することで、生徒の情報を登録しておくだけで、クラス別の管理から毎月の支払い管理まで、<br/>
            簡単に運営管理することが可能になります。
        </p>

        <button class="btn_big center mb40">無料登録</button>

        <h2>フランチャイズ展開や、姉妹校も一緒に管理<br/>
            <span class="font70">本部・支店機能で可能性が大きく広がります</span>
        </h2>

        <img src="/img{{$_app_path}}img_2.png" class="w100 center mb20" alt="">


        <p class="tc mb40">本部支部機能を利用することで、御校の事業拡大を強力にサポートいたします。<br/>
            全スクールの講師から生徒の管理や、クラスやイベント、さらに支払状況などの一括管理をすることで、本部から一元管理を行うことがかのうになります。</p>

        <h2>導入事例</h2>
        <p class="tc mb20">会員数1500名以上の日本最大規模の柔術道場様で、現在弊社のシステムをご活用頂いております。</p>

    </div><!-- width -->


    <div class="bg_blue">
        <div class="width">


            <!-- Swiper -->
            <ul class="bxslider voice_box">
                <li>
                    <p class="p_title">各支部の講師/生徒の管理が非常に便利になりました </p>
                    <div class="voice_box_1">
                        <div class="vb_img"><img src="/img{{$_app_path}}bnr07.jpg" class="" alt=""></div>
                        <div class="voice_box_1_right">
                            <p class="p1 mb20">
                                本部の生徒が支部へ、また支部の生徒が本部や他の支部の練習に参加する際、離れた拠点間での所属の確認や生徒情報の詳細、受講しているコースなどの情報を会員証の情報を読み取ることでスムーズに確認ができるようになりました。各支部の講師/生徒の管理が非常に便利になりました。<br/>生徒の出欠管理や授業料の入金管理等、生徒数が増えると紙で管理することが困難になってきた業務を効率的に行えるようになり、本来の業務に時間をさけるようになりました。<br/>また、毎月の請求書の発行が不要になり、入金確認もスムーズに行うことができるようになり、請求に関する業務が楽になりました。
                            </p>
                            <p class="p2">柔術道場様</p>
                            <p class="p1">住　　　所：東京都世田谷区<br/>
                                利用プラン：スタンダードプラン
                            </p>
                        </div><!-- voice_box_1_right -->
                    </div><!-- voice_box_1 -->
                </li>


            </ul>


            <script>
                $(document).ready(function () {
                    $('.bxslider').bxSlider({
                        auto: true
                    });
                });
            </script>


        </div><!-- width -->
    </div><!-- bg_blue -->


    <h2>製品ラインナップ</h2>
    <p class="tc mb20">
        らくらく会員管理システムは、誰でもご利用しやすくするため、無料で使い始めることが可能です。<br/>
        まずはどんなものなのかを実際に体験してみてご判断くだささい。
    </p>


    <div class="price_list">
        <ul>
            <li>

                <p class="price_title">フリー</p>
                <div class="price_list_box">
                    <div class="price_box1">
                        <p class="price_title1">生徒数</p>
                        <p class="price_p2"><span>〜</span>10<span>人</span></p>
                    </div>
                    <div class="price_box1">
                        <p class="price_title1">本部支部管理</p>
                        <p class="price_p2">-</p>
                    </div>
                    <div class="price_box1 mb20">
                        <p class="price_title1">料金</p>
                        <p class="price_p2">無料</p>
                    </div>
                    <div class="price_box1 mb20">
                        <p class="p_info">・会員数10名様までなら、無料でご利用出来ます。<br/>
                            ・機能につきましては、一部有料制限がございます。</p>
                    </div>

                    <a href="{{$_app_path}}contact">
                        <button class="btn_small">お問合せ</button>
                    </a>
                </div>

            </li>

            <li>

                <p class="price_title">スタンダード</p>
                <div class="price_list_box">
                    <div class="price_box1">
                        <p class="price_title1">生徒数</p>
                        <p class="price_p2">11<span>人〜</span></p>
                    </div>
                    <div class="price_box1">
                        <p class="price_title1">本部支部管理</p>
                        <p class="price_p2">-</p>
                    </div>
                    <div class="price_box1 mb20">
                        <p class="price_title1">料金</p>
                        <p class="price_p2">10,000<span>円/月 〜</span></p>
                    </div>
                    <div class="price_box1 mb20">
                        <p class="p_info">・本部、支部の区分がないスクール向けのプランになります。<br/>
                            ・10,000円/月～になります。<br/>
                            ・機能の制限はございません。 </p>
                    </div>
                    <a href="{{$_app_path}}contact">
                        <button class="btn_small">お問合せ</button>
                    </a>
                </div>
            </li>

            <li>
                <p class="price_title">エンタープライズ</p>
                <div class="price_list_box ">
                    <div class="price_box1">
                        <p class="price_title1">生徒数</p>
                        <p class="price_p2">11<span>人〜</span></p>
                    </div>
                    <div class="price_box1">
                        <p class="price_title1">本部支部管理</p>
                        <p class="price_p2">◎</p>
                    </div>
                    <div class="price_box1 mb20">
                        <p class="price_title1">料金</p>
                        <p class="price_p2">お問い合せ</p>
                    </div>
                    <div class="price_box1 mb20">
                        <p class="p_info">・支部機能の他に支部を管理する本部機能を利用する場合のプランになります。<br/>
                            ・価格は人数により異なりますので、お問い合わせ下さい。<br/>
                            ・機能の制限はございません。<br/>
                            ・また追加費用になりますがシステムを改修して現在の業務に合わせた形に変更することも可能です 。 </p>
                    </div>
                    <a href="{{$_app_path}}contact">
                        <button class="btn_small">お問合せ</button>
                    </a>
                </div>

            </li>
        </ul>
    </div>

    <a href="{{$_app_path}}contact">
        <button class="btn_big center">お問合せ</button>
    </a>

</div><!-- main_content -->
@stop