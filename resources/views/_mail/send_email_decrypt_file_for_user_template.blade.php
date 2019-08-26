<html>
<head></head>
<body>
<p>{!! $data['name']!!}様</p>
<p>会員情報CSV出力のパスワードを送付いたします </p>
<p>ダウンロードファイル： {!! $data['fileExportName'] !!}</p>
<p>パスワード： {!! $data['key'] !!}</p>

--------------------------------------------------<br />
<p>らくらく会員管理</p>
<p>お問合わせ先： {!! $data['mail_admin']!!}</p>

</body></html>