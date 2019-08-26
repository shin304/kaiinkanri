@extends('_parts.master_layout') @section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<script type="text/javascript">
$(function() {
	$(".submit2").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}student');
		$("#action_form").submit();
		return false;
	});
	$(".submit3").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}student/importCsv');
		$("#action_form").submit();
		return false;
	});
});
</script>
<style type="text/css">
.table1 th, .table1 td {
	padding: 3px 5px;
}
.td_l {
	text-align:left !important;
	padding: 3px 5px;
}
.td_c {
	text-align:center !important;
	padding: 3px 0px;
}
.td_r {
	text-align:right !important;
	padding: 3px 5px;
}
</style>
<div id="center_content_header" class="box_border1">
	<h2 class="float_left"><i class="fa fa-group"></i> {{$lan::get('main_title')}}</h2>

	<div class="center_content_header_right">
		<div class="top_btn"></div>
	</div>

	<div class="clr"></div>
</div>


<h3 id="content_h3" class="box_border1">ＣＳＶアップロード</h3>
<div id="section_content1">
	@if(request()->has('import_message'))
		<ul class="message_area">
			@if(request()->has('import_message.total_row'))
			<li>{{$lan::get('import_csv_total_row')}}{{request('import_message.total_row')}}</li>
			@endif
			@if(request()->has('import_message.total_inserted'))
			<li>{{$lan::get('import_csv_total_inserted')}}{{request('import_message.total_inserted')}}</li>
			@endif
			@if(request()->has('import_message.total_error'))
			<li>{{$lan::get('import_csv_total_error')}}{{request('import_message.total_error')}}</li>
			@endif
			@if(request()->has('import_message.other'))
			<li class="error_message">{{request('import_message.other')}}</li>
			@endif
			@if(request()->has('import_message.errors'))
				@foreach(request('import_message.errors') as $row => $error)
					@foreach($error->all() as $k => $message)
                        @if($k!='limit')
					        <li class="error_message">{{$row+1}}{{$lan::get('row')}}{{$lan::get($message)}}</li>
                        @else
                            <li class="error_message">{{$lan::get($message)}}</li>
                        @endif
					@endforeach
				@endforeach
			@endif
		</ul>
	@endif
	<form action="{{ URL::to('/school/student/importCsv') }}" method="post"
		id="action_form" enctype="multipart/form-data">
		<!-- <h4>生徒</h4> -->
		<table id="table6">
			<colgroup>
				<col width="30%" />
				<col width="40%" />
				<col width="30%" />
			</colgroup>
			<tr>
				<td class="t6_td1">{{$lan::get('set_encode_title')}}</td>
				<td class="t4td2">
					<select name="mode">
						<option @if(request('mode') == 0) selected @endif value="0">{{$lan::get('shift_js')}}</option>
						<option @if(request('mode') == 1) selected @endif value="1">{{$lan::get('utf_8')}}</option>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td class="t6_td1">
					{{$lan::get('csv_file_title')}}
					<span class="aster">&lowast;</span>
				</td>
				<td class="t4td2">
					<input type="file" name="import_file" value="" class="text_m" />
					{{ csrf_field() }}
				</td>
				<td>
					100件/1回
				</td>
			</tr>
		</table>
		@if(request()->has('import_message.limit'))
			<li class="error_message">{{request('import_message.limit')}}</li>
			<br />
		@endif
		<div class="exe_button">
			<!-- <input class="submit3" type="button" value="{{$lan::get('run_title')}}"/> -->
			<button class="submit3" type="submit"><i class="glyphicon glyphicon-floppy-disk " style="width: 20%;font-size:16px;"></i>登録</button>
			<button class="submit2" type="submit"><i class="glyphicon glyphicon-circle-arrow-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
			<!-- <input class="submit2" type="button" value="{{$lan::get('return_title')}}"/> -->
		</div>
		<br />
		<h5>{{$lan::get('csv_format_title')}}</h5>
		<div id="pop_csv">
			<table class="table1">
				<colgroup>
					<col width="4%" />
					<col width="20%" />
					<col width="6%" />
					<col width="4%" />
					<col width="40%" />
					<col width="20%" />
				</colgroup>
				<tr>
					<th class="td_l">{{$lan::get('csv_no')}}</th>
					<th class="td_l">{{$lan::get('item_name_title')}}</th>
					<th class="td_l">{{$lan::get('type_title')}}</th>
					<th class="td_l">{{$lan::get('required_title')}}</th>
					<th class="td_l">{{$lan::get('description_title')}}</th>
					<th class="td_l">{{$lan::get('example_title')}}</th>
				</tr>
				<tr>
					<td class="td_c">1</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('status_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">1:契約中/9:契約終了</td>
				</tr>
				<tr>
					<td class="td_c">2</td>
					<td class="td_l">{{$lan::get('student_no_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">3</td>
					<td class="td_l">{{$lan::get('student_category_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l"></td>
					<td class="td_l">個人／法人</td>
				</tr>
				<tr>
					<td class="td_c">4</td>
					<td class="td_l">{{$lan::get('total_member')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">法人の場合</td>
				</tr>
				<tr>
					<td class="td_c">5</td>
					<td class="td_l">{{$lan::get('student_type_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l">{{$lan::get('student_type_registered_title')}}</td>
					<td class="td_l">個人正会員 / 法人正会員</td>
				</tr>
				<tr>
					<td class="td_c">6</td>
					<td class="td_l">{{$lan::get('student_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l"></td>
					<td class="td_l">山田太郎</td>
				</tr>
				<tr>
					<td class="td_c">7</td>
					<td class="td_l">{{$lan::get('student_title_hiragana')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">8</td>
					<td class="td_l">{{$lan::get('student_furigana_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">9</td>
					<td class="td_l">{{$lan::get('latin_alphabet_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">Taro Yamada</td>
				</tr>
				<tr>
					<td class="td_c">10</td>
					<td class="td_l">{{$lan::get('student_email_address_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l"></td>
					<td class="td_l">t.yamada@mail.com</td>
				</tr>
				<tr>
					<td class="td_c">11</td>
					<td class="td_l">{{$lan::get('student_pass_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">12</td>
					<td class="td_l">{{$lan::get('student_birthday_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('format_calendar_year_month_title')}}</td>
					<td class="td_l">2000-01-01 または 2000/01/01</td>
				</tr>
				<tr>
					<td class="td_c">13</td>
					<td class="td_l">{{$lan::get('student_gender_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('specify_gender_title')}}</td>
					<td class="td_l">男性 / 女性</td>
				</tr>
				<tr>
					<td class="td_c">14</td>
					<td class="td_l">{{$lan::get('join_date_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('join_date_description')}}</td>
					<td class="td_l">2000-01-01 または 2000/01/01</td>
				</tr>
				<tr>
					<td class="td_c">15</td>
					<td class="td_l">{{$lan::get('join_memo_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">16</td>
					<td class="td_l">{{$lan::get('withdraw_date_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('withdraw_date_description')}}</td>
					<td class="td_l">2000-01-01 または 2000/01/01</td>
				</tr>
				<tr>
					<td class="td_c">17</td>
					<td class="td_l">{{$lan::get('withdraw_memo_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">18</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('postal_code_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">101-0044</td>
				</tr>
				<tr>
					<td class="td_c">19</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('state_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">20</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('city_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">21</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('address_number_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">22</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('building_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">23</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('contact_phone_number')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l"></td>
					<td class="td_l">03-5297-8207</td>
				</tr>
				<tr>
					<td class="td_c">24</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('mobile_phone_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">090-3297-8207</td>
				</tr>
				<tr>
					<td class="td_c">25</td>
					<td class="td_l">{{$lan::get('member_title')}}{{$lan::get('other_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">26</td>
					<td class="td_l">{{$lan::get('copy_and_use_member_info')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('copy_and_use_member_info_description')}}</td>
					<td class="td_l">1:行う</td>
				</tr>
				<tr>
					<td class="td_c">27</td>
					<td class="td_l">{{$lan::get('member_office')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">28</td>
					<td class="td_l">{{$lan::get('membership')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">29</td>
					<td class="td_l">{{$lan::get('member_school')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">30</td>
					<td class="td_l">{{$lan::get('guardian_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l">{{$lan::get('billing_name_description')}}</td>
					<td class="td_l">山田一郎</td>
				</tr>
				<tr>
					<td class="td_c">31</td>
					<td class="td_l">{{$lan::get('guardian_title_hiragana')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">32</td>
					<td class="td_l">{{$lan::get('guardian_name_kana_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">ヤマダイチロウ</td>
				</tr>
				<tr>
					<td class="td_c">33</td>
					<td class="td_l">{{$lan::get('guardian_email_address_1_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c">{{$lan::get('csv_required_mark')}}</td>
					<td class="td_l"></td>
					<td class="td_l">i.yamada@mail.com</td>
				</tr>
				<tr>
					<td class="td_c">34</td>
					<td class="td_l">{{$lan::get('guardian_password_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">35</td>
					<td class="td_l">{{$lan::get('guardian_postal_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('formar_numberic_title')}}</td>
					<td class="td_l">123-0011</td>
				</tr>
				<tr>
					<td class="td_c">36</td>
					<td class="td_l">{{$lan::get('guardian_state_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">千葉県</td>
				</tr>
				<tr>
					<td class="td_c">37</td>
					<td class="td_l">{{$lan::get('guardian_city_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">千葉市中央区</td>
				</tr>
				<tr>
					<td class="td_c">38</td>
					<td class="td_l">{{$lan::get('guardian_address_build_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">１－２－３ ＡＢＣ</td>
				</tr>
				<tr>
					<td class="td_c">39</td>
					<td class="td_l">{{$lan::get('guardian_title')}}{{$lan::get('building_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">ビル１階</td>
				</tr>
				<tr>
					<td class="td_c">40</td>
					<td class="td_l">{{$lan::get('guardian_home_phone_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">0123456789</td>
				</tr>
				<tr>
					<td class="td_c">41</td>
					<td class="td_l">{{$lan::get('guardian_mobile_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">03-1234-5678  / 045-123-4567</td>
				</tr>
				<tr>
					<td class="td_c">42</td>
					<td class="td_l">{{$lan::get('guardian_memo_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">43</td>
					<td class="td_l">{{$lan::get('notification_method')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">郵送 / メール</td>
				</tr>
				<tr>
					<td class="td_c">44</td>
					<td class="td_l">{{$lan::get('payer_method_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">現金 / 振込 / 口座振替</td>
				</tr>
				<tr>
					<td class="td_c">45</td>
					<td class="td_l">{{$lan::get('finan_organ_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('required_payment_acc_bank_title')}}</td>
					<td class="td_l">銀行・信用金庫 / ゆうちょ銀行</td>

				</tr>
				<tr>
					<td class="td_c">46</td>
					<td class="td_l">{{$lan::get('bank_code_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />4桁の半角数字
					</td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">47</td>
					<td class="td_l">{{$lan::get('finan_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />半角英大文字、半角カナ（小さい ﾔ
						ﾕ ﾖ ﾂを除く）１５文字まで
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">48</td>
					<td class="td_l">{{$lan::get('branch_code_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />３桁の半角数字
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">49</td>
					<td class="td_l">{{$lan::get('brch_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />半角英大文字、半角カナ（小さい ﾔ
						ﾕ ﾖ ﾂを除く）１５文字まで
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">50</td>
					<td class="td_l">{{$lan::get('bank_acc_type_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('required_payment_acc_bank_credit_title')}}/td>
					<td class="td_l">普通預金 / 当座預金</td>

				</tr>
				<tr>
					<td class="td_c">51</td>
					<td class="td_l">{{$lan::get('bank_acc_number_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />７桁の半角数字
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">52</td>
					<td class="td_l">{{$lan::get('bank_acc_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（銀行・信用金庫）のとき、必須です<br />半角英大文字、半角ｶﾅ（小さいﾔ ﾕ
						ﾖ ﾂを除く）30文字まで
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">53</td>
					<td class="td_l">{{$lan::get('passbook_code_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（ゆうちょ銀行）のとき、必須です<br /></td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">54</td>
					<td class="td_l">{{$lan::get('passbook_number_title')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（ゆうちょ銀行）のとき、必須です<br />7桁の半角数字（通帳番号８桁の上7桁）
					</td>

					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">55</td>
					<td class="td_l">{{$lan::get('passbook_name_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">支払方法が口座振替（ゆうちょ銀行）のとき、必須です<br />半角英大文字、半角ｶﾅ（小さいﾔ ﾕ
						ﾖ ﾂを除く）30文字まで
					</td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">56</td>
					<td class="td_l">{{$lan::get('name_of_representative')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('name_of_representative_description')}}</td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">57</td>
					<td class="td_l">{{$lan::get('represent')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">58</td>
					<td class="td_l">{{$lan::get('represent_title')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">59</td>
					<td class="td_l">{{$lan::get('represent_email')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">km.yamada@mail.com</td>
				</tr>
				<tr>
					<td class="td_c">60</td>
					<td class="td_l">{{$lan::get('receive_email_from_represent')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">受信する：1</td>
				</tr>
				<tr>
					<td class="td_c">61</td>
					<td class="td_l">{{$lan::get('represent_tel')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">03-3297-8207</td>
				</tr>
				<tr>
					<td class="td_c">62</td>
					<td class="td_l">{{$lan::get('person_name1')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('person_name1_description')}}</td>
					<td class="td_l">高橋</td>
				</tr>
				<tr>
					<td class="td_c">63</td>
					<td class="td_l">{{$lan::get('person_name_kana1')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">たかはし</td>
				</tr>
				<tr>
					<td class="td_c">64</td>
					<td class="td_l">{{$lan::get('person_position1')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">65</td>
					<td class="td_l">{{$lan::get('person_office_name1')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">66</td>
					<td class="td_l">{{$lan::get('person_in_charge_one_department_tel')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">03-3297-8207</td>
				</tr>
				<tr>
					<td class="td_c">67</td>
					<td class="td_l">{{$lan::get('contact_person_one_email')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">test@gmail.com</td>
				</tr>
				<tr>
					<td class="td_c">68</td>
					<td class="td_l">{{$lan::get('receive_email_to_person_in_charge_one')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">受信する：1</td>
				</tr>
				<tr>
					<td class="td_c">69</td>
					<td class="td_l">{{$lan::get('two_name_in_charge')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('two_name_in_charge_description')}}</td>
					<td class="td_l">高橋</td>
				</tr>
				<tr>
					<td class="td_c">70</td>
					<td class="td_l">{{$lan::get('person_in_charge_two')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">たかはし</td>
				</tr>
				<tr>
					<td class="td_c">71</td>
					<td class="td_l">{{$lan::get('two_position_in_charge')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">72</td>
					<td class="td_l">{{$lan::get('two_copy_person_in_charge')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">73</td>
					<td class="td_l">{{$lan::get('person_in_charge_two_department_tel')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">03-3297-8207</td>
				</tr>
				<tr>
					<td class="td_c">74</td>
					<td class="td_l">{{$lan::get('person_in_charge_two_email')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">test@gmail.com</td>
				</tr>
				<tr>
					<td class="td_c">75</td>
					<td class="td_l">{{$lan::get('receive_email_to_person_in_charge_two')}}</td>
					<td class="td_l">{{$lan::get('numeric_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">受信する：1</td>
				</tr>
				<tr>
					<td class="td_c">76</td>
					<td class="td_l">{{$lan::get('three_name_in_charge')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('three_name_in_charge_description')}}</td>
					<td class="td_l">高橋</td>
				</tr>
				<tr>
					<td class="td_c">77</td>
					<td class="td_l">{{$lan::get('person_in_charge_three')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">たかはし</td>
				</tr>
				<tr>
					<td class="td_c">78</td>
					<td class="td_l">{{$lan::get('three_position_in_charge')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">79</td>
					<td class="td_l">{{$lan::get('three_copy_person_in_charge')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l"></td>
				</tr>
				<tr>
					<td class="td_c">80</td>
					<td class="td_l">{{$lan::get('person_in_charge_three_department_tel')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">03-3297-8207</td>
				</tr>
				<tr>
					<td class="td_c">81</td>
					<td class="td_l">{{$lan::get('person_in_charge_three_email')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">test@gmail.com</td>
				</tr>
				<tr>
					<td class="td_c">82</td>
					<td class="td_l">{{$lan::get('receive_email_to_person_in_charge_three')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l"></td>
					<td class="td_l">受信する：1</td>
				</tr>
				<tr>
					<td class="td_c">83</td>
					<td class="td_l">{{$lan::get('control_item')}}</td>
					<td class="td_l">{{$lan::get('string_title')}}</td>
					<td class="td_c"></td>
					<td class="td_l">{{$lan::get('control_item_description')}}</td>
					<td class="td_l"></td>
				</tr>
			</table>
			<div>※ {{$lan::get('unnecessary_header_error_title')}}</div>
		</div>
	</form>
</div>
@stop
