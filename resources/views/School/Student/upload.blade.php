@extends('_parts.master_layout') @section('content')
<div id="center_content_header" class="box_border1">
	<h2 class="float_left">{{session()->get('main_title')}}</h2>

	<div class="center_content_header_right">
		<div class="top_btn"></div>
	</div>

	<div class="clr"></div>
</div>
<div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">{!!
	Breadcrumbs::render('upload') !!}</div>
<!--center_content_header-->

<h3 id="content_h3" class="box_border1">ＣＳＶアップロード</h3>
<div id="section_content1">

	<form action="{{ URL::to('/school/student/importCsv') }}" method="post"
		id="action_form" enctype="multipart/form-data">
		<!-- <h4>生徒</h4> -->
		<table id="table6">
			<colgroup>
				<col width="30%" />
				<col width="70%" />
			</colgroup>
			<tr>
				<td>@if (session()->get('success') !== null)
					<div class="alert alert-success" role="alert" style="color: blue;">
						{{session()->get('success')}}</div> @endif @if
					(session()->get('error') !== null)
					<div class="alert alert-danger" role="alert" style="color: red;">
						{{session()->get('error')}}</div> @endif @if
					(session()->get('errors') !== null)
					<div class="alert alert-danger" role="alert" style="color: red;">
						{{session()->get('errors')}}</div> @endif
				</td>
			</tr>
			<tr>
				<td class="t6_td1">文字コード切替</td>
				<td class="t4td2"><select name="file_encode">
						<option selected value="0">Shift-jis</option>
						<option value="1">UTF-8</option>
				</select></td>
			</tr>
			<tr>
				<td class="t6_td1">CSVファイル<span class="aster">&lowast;</span></td>
				<td class="t4td2"><input type="file" name="import_file" value=""
					class="text_m" />{{ csrf_field() }} <!--
							<a href="#" onclick="$('#pop_csv').bPopup();return false;">CSVフォーマット</a>
							 --></td>
			</tr>
		</table>

		<div class="exe_button">
			<input class="submit2" type="button" value="戻る"
				onclick="location.href='{{ URL::to('/school/student') }}';" /> <input
				class="submit3" type="submit" value="確認" />
		</div>

		<br />
		<h5>CSVフォーマット</h5>
		<div id="pop_csv">
			<table class="table1">
				<!--
				<caption>CSVフォーマット</caption>
				 -->
				<colgroup>
					<col width="4%" />
					<col width="20%" />
					<col width="6%" />
					<col width="4%" />
					<col width="40%" />
					<col width="20%" />
				</colgroup>
				<tr>
					<th class="td_c">No.</th>
					<th class="td_c">項目名</th>
					<th class="td_c">型</th>
					<th class="td_c">必須</th>
					<th class="td_c">説明</th>
					<th class="td_c">例</th>
				</tr>
				<tr>
					<td class="td_r">1</td>
					<td class="td_l">会員種別</td>
					<td class="td_l">文字列</td>
					<td class="td_c">◎</td>
					<td class="td_l">登録されている会員種別であること</td>
					<td class="td_l">種別１ / 種別2</td>
				</tr>
				<td class="td_r">2</td>
				<td class="td_l">会員名前</td>
				<td class="td_l">文字列</td>
				<td class="td_c">◎</td>
				<td class="td_l"></td>
				<td class="td_l">山田太郎</td>
				</tr>
				<tr>
					<td class="td_r">3</td>
					<td class="td_l">会員番号</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">13桁の数字 データ更新時に必要</td>
					<td class="td_l">1234123412345</td>
				</tr>
				<tr>
					<td class="td_r">4</td>
					<td class="td_l">会員フリガナ</td>
					<td class="td_l">文字列</td>
					<td class="td_c">◎</td>
					<td class="td_l"></td>
					<td class="td_l">ヤマダタロウ</td>
				</tr>
				<tr>
					<td class="td_r">5</td>
					<td class="td_l">会員ニックネーム</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">ヤマダタロウ</td>
				</tr>
				<tr>
					<td class="td_r">6</td>
					<td class="td_l">会員メールアドレス</td>
					<td class="td_l">文字列</td>
					<td class="td_c">◎</td>
					<td class="td_l"></td>
					<td class="td_l">t.yamada@mail.com</td>
				</tr>
				<tr>
					<td class="td_r">7</td>
					<td class="td_l">会員生年月日</td>
					<td class="td_l">文字列</td>
					<td class="td_c">◎</td>
					<td class="td_l">西暦年-月-日の形式であること</td>
					<td class="td_l">2000-01-01</td>
				</tr>
				<tr>
					<td class="td_r">8</td>
					<td class="td_l">会員性別</td>
					<td class="td_l">文字列</td>
					<td class="td_c">◎</td>
					<td class="td_l">男性または女性を指定</td>
					<td class="td_l">男性 / 女性</td>
				</tr>
				<tr>
					<td class="td_r">9</td>
					<td class="td_l">請求先名前</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">会員が新規登録のとき、必須です</td>
					<td class="td_l">山田一郎</td>
				</tr>
				<tr>
					<td class="td_r">10</td>
					<td class="td_l">請求先名前カナ</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です</td>
					<td class="td_l">ヤマダイチロウ</td>
				</tr>
				<tr>
					<td class="td_r">11</td>
					<td class="td_l">請求先メールアドレス１</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です</td>
					<td class="td_l">i.yamada@mail.com</td>
				</tr>
				<tr>
					<td class="td_r">12</td>
					<td class="td_l">請求先メールアドレス２</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">i.yamada@mail.com</td>
				</tr>
				<tr>
					<td class="td_r">13</td>
					<td class="td_l">請求先パスワード</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">14</td>
					<td class="td_l">請求先郵便番号</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l">7桁の数値の形式であること</td>
					<td class="td_l">1230011</td>
				</tr>
				<tr>
					<td class="td_r">15</td>
					<td class="td_l">請求先都道府県名</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です<br />登録された都道府県名であること
					</td>
					<td class="td_l">千葉県</td>
				</tr>
				<tr>
					<td class="td_r">16</td>
					<td class="td_l">請求先市区町村名</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です<br />指定された都道府県に属する登録された市区町村名であること
					</td>
					<td class="td_l">千葉市中央区</td>
				</tr>
				<tr>
					<td class="td_r">17</td>
					<td class="td_l">請求先番地・ビル名</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です</td>
					<td class="td_l">１－２－３ ＡＢＣビル１階</td>
				</tr>
				<tr>
					<td class="td_r">18</td>
					<td class="td_l">請求先自宅電話番号</td>
					<td class="td_l">数値</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です</td>
					<td class="td_l">0123456789</td>
				</tr>
				<tr>
					<td class="td_r">19</td>
					<td class="td_l">請求先携帯電話番号</td>
					<td class="td_l">数値</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">11112345678</td>
				</tr>
				<tr>
					<td class="td_r">20</td>
					<td class="td_l">請求先メモ</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">21</td>
					<td class="td_l">通知方法</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です</td>
					<td class="td_l">郵送 / メール</td>
				</tr>
				<tr>
					<td class="td_r">22</td>
					<td class="td_l">支払方法</td>
					<td class="td_l">文字列</td>
					<td class="td_c">○</td>
					<td class="td_l">請求先名前が設定されているとき、必須です</td>
					<td class="td_l">現金 / 振込 / 口座振替</td>
				</tr>
				<tr>
					<td class="td_r">23</td>
					<td class="td_l">金融機関種別</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替のとき、必須です</td>
					<td class="td_l">銀行・信用金庫 / ゆうちょ銀行</td>

				</tr>
				<tr>
					<td class="td_r">24</td>
					<td class="td_l">金融機関コード</td>
					<td class="td_l">数値</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />4桁の半角数字
					</td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">25</td>
					<td class="td_l">金融機関名</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />半角英大文字、半角カナ（小さい ﾔ
						ﾕ ﾖ ﾂを除く）１５文字まで
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">26</td>
					<td class="td_l">支店コード</td>
					<td class="td_l">数値</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />３桁の半角数字
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">27</td>
					<td class="td_l">支店名</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />半角英大文字、半角カナ（小さい ﾔ
						ﾕ ﾖ ﾂを除く）１５文字まで
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">28</td>
					<td class="td_l">口座種別</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です</td>
					<td class="td_l">普通預金 / 当座預金</td>

				</tr>
				<tr>
					<td class="td_r">29</td>
					<td class="td_l">口座番号</td>
					<td class="td_l">数値</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />７桁の半角数字
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">30</td>
					<td class="td_l">口座名義</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />半角英大文字、半角ｶﾅ（小さいﾔ ﾕ
						ﾖ ﾂを除く）30文字まで
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">31</td>
					<td class="td_l">通帳記号</td>
					<td class="td_l">数値</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（ゆうちょ銀行）のとき、必須です<br /></td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">32</td>
					<td class="td_l">通帳番号</td>
					<td class="td_l">数値</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（ゆうちょ銀行）のとき、必須です<br />7桁の半角数字（通帳番号８桁の上7桁）
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">33</td>
					<td class="td_l">通帳名義</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（ゆうちょ銀行）のとき、必須です<br />半角英大文字、半角ｶﾅ（小さいﾔ ﾕ
						ﾖ ﾂを除く）30文字まで
					</td>

					<td class="td_l"></td>
				</tr>
				</tr>
				<tr>
					<td class="td_r">34</td>
					<td class="td_l">会員メモ１</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">35</td>
					<td class="td_l">会員メモ２</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_r">36</td>
					<td class="td_l">会員メモ３</td>
					<td class="td_l">文字列</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
			</table>
			<div>※ ヘッダ行は不要</div>
		</div>
	</form>
</div>
@stop
