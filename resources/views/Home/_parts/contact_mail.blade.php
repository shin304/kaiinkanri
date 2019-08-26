<html>
<header></header>
<body>
「会員管理システムへのお問い合わせ」からメールが届きました

<p>＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝</p>
【 お名前 】 {{$request->name}}<br>
【 Email 】 {{$request->mail}}<br>
【 会社名 】 {{$request->company}}<br>
【 電話番号 】 {{$request->tel}}<br>
【 お問い合わせ種別 】 {{$request->type}}<br>
【 プラン 】 {{implode(", ", $request->plans)}}<br>
【 お問い合わせ内容 】 {{$request->qa_content}}<br>
<p>＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝</p>
送信された日時：{{date( "Y/m/d (D) H:i:s", time())}}<br>
送信者のIPアドレス：{{$request->ip()}}<br>
送信者のホスト名：{{getHostByAddr($request->ip())}}<br>
問い合わせのページURL：https://rakuraku-kanri.com/home/contact
</body>
</html>