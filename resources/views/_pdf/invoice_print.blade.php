{{--<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>請求書印刷</title>
	<link type="text/css" rel="stylesheet" href="css/school/invoice_print.css" />
	<title>請求書印刷</title>
	<style type='text/css'>
		body{
			font-family: ipag;

		}
	</style>
</head>
<body>--}}
<div id="content" style="page-break-after: always;">
	<link type="text/css" rel="stylesheet" href="css/school/invoice_print.css" />
	<style type='text/css'>
		body{
			font-family: ipag;

		}
	</style>
	<div class="header_top">
		<p>{{Carbon\Carbon::parse(array_get($data, 'now_date'))->format('Y年m月d日')}}</p>
	</div>
	<header>
		<h1>請求書</h1>
	</header>
	<section id="pay_info">
		<div class="pay_info_left">
			<p class="company_name">{{array_get($data,'parent_name')}}  様</p>

			<p class="pay_p1">下記のとおりご請求申し上げます。</p>
			<p class="pay_much">ご請求金額　　&nbsp;&nbsp;&yen;{{number_format(array_get($data,'amount_tax'))}} -</p>
			<p class="pay_kigen">お支払期限：{{Carbon\Carbon::parse(array_get($data, 'due_date'))->format('Y年m月d日')}}</p>
		</div><!---->
		<div class="pay_info_right">
			<p class="my_company_name">{{array_get($data,'school_name')}}</p>
			@if(array_get($data,'school_zipcode_1') && array_get($data,'school_zipcode_2'))
			<p class="my_company_name">〒{{array_get($data,'school_zipcode_1')}}-{{array_get($data,'school_zipcode_2')}}</p>
			@endif

			<p class="my_company_address">{{array_get($data,'pref_name')}}&nbsp;{{array_get($data,'city_name')}}&nbsp;{{array_get($data,'school_address')}}</p>
			@if(array_get($data,'school_tel'))
			<p class="my_company_tel">{{array_get($data,'school_tel')}}</p>
			@endif
			<p class="my_company_daihyou">{{array_get($data,'school_daihyou')}}</p>
		</div>
		{{--<div class="kakuin">--}}
			<img class="kakuin" src="images/school/kakuin/1503480664.png">
		{{--</div>--}}
		<div class="clr"></div>
	</section>
	<table id="pay_table">
		<tr>
			<th class="th1">内容</th>
			<th class="th4">金額</th>
		</tr>
		@php
			$total_price = 0;
		@endphp
		@foreach( $data['student_list'] as $student_row)
		{{--{{array_get($student_row, 'student_name')}} 様<br>--}}

			{{--プラン--}}
			@foreach(array_get($data['class_name'], $student_row['id']) as $k=>$name)
				@if($name)
					<tr>
						<td>
							{{$name}}&nbsp;
						</td>
						<td class="td2">
							&yen;{{number_format(array_get($data['class_price'],$student_row['id'] )[$k])}}
						</td>
					</tr>
					@php
						$total_price += array_get($data['class_price'],$student_row['id'] )[$k];
					@endphp
				@endif

			@endforeach

			{{--イベント--}}
			@foreach(array_get($data['course_name'], $student_row['id']) as $k=>$name)
				@if($name)
					<tr>
						<td>
							{{$name}}&nbsp;
						</td>
						<td class="td2">
							&yen;{{number_format(array_get($data['course_price'],$student_row['id'] )[$k])}}
						</td>
					</tr>
					@php
						$total_price += array_get($data['course_price'],$student_row['id'][$k] )[$k];
					@endphp
				@endif

			@endforeach

			{{--個別入力--}}
			@foreach(array_get($data['custom_item_name'], $student_row['id']) as $k=>$name)
				@if( $name || array_get($data['custom_item_price'],$student_row['id'] )[$k])
					<tr>
						<td>
							{{$name}}&nbsp;
						</td>
						<td class="td2">
							@if(  is_numeric(array_get($data['custom_item_price'], $student_row['id'])[$k]))
								@if( array_get($data['custom_item_price'], $student_row['id'])[$k]  < 0)
									&yen;-{{number_format(replace(array_get($data['custom_item_price'],$student_row['id'] )[$k], '-', ''))}}
								@else
									&yen;{{number_format( array_get($data['custom_item_price'],$student_row['id'])[$k])}}
								@endif
								@php
									$total_price += array_get($data['custom_item_price'],$student_row['id'])[$k];
								@endphp
							@else
								&nbsp;
							@endif
						</td>
					</tr>
				@endif

			@endforeach
		@endforeach
	</table>


	<div id="center_content_header" class="box_border1">
		<div class="center_content_header_right">
			<table id="pay_table2">
				<tr>
					<th>
						小計&nbsp;&nbsp;&nbsp;
					</th>
					<td class="td2">
						&yen;{{number_format($total_price)}}
					</td>
				</tr>
				@foreach( array_get($data, 'discount_name') as $k=>$v)
					@if( $v || array_get($data,'discount_price')[$k])
						<tr>
							<th>
								{{$v}}&nbsp;&nbsp;&nbsp;
							</th>
							<td class="td2">
								&yen;-{{number_format(array_get($data,'discount_price')[$k])}}
							</td>
						</tr>
						@php
							$is_discount_exists = true;
						@endphp
					@endif
				@endforeach
				<tr>
					<th>
						消費税&nbsp;&nbsp;&nbsp;
					</th>
					<td class="td2">
						@if(array_get($data,'tax_price'))
						&yen;{{number_format(array_get($data,'tax_price'))}}
						@else
						&yen;<span id="tax_price"></span>
						@endif
					</td>
				</tr>
				<tr>
					<th>
						合計&nbsp;&nbsp;&nbsp;
					</th>
					<td class="td2">
						@if(array_get($data,'amount_tax'))
						&yen;{{number_format(array_get($data,'amount_tax'))}}
						@else
						&yen;<span id="amount_tax"></span>
						@endif
					</td>
				</tr>
			</table>
		</div>
		<div class="clr"></div>
	</div>
	<p class="pay_p2">いつもありがとうございます。</p>
	@if(array_get($data,'invoice_type ')== 0)			{{--現金--}}
	<div class="bank_account">
		お支払期限までにご持参いただけますよう　よろしくお願い申し上げます。
	</div>
	@elseif(array_get($data,'invoice_type ')== 1) 		{{--振込--}}
	<div class="bank_account">
		以下の口座へ お支払期限までに お振込みいただけますよう　よろしくお願い申し上げます。<br/>
		振込先<br />

		@if (array_get($data,'bank_name ') && array_get($data,'bank_account_name'))
		{{array_get($data,'bank_name')}}&nbsp;&nbsp;{{array_get($data,'branch_name')}}&nbsp;&nbsp;
		{{array_get($data,'bank_account_number')}}&nbsp;&nbsp;{{array_get($data,'bank_account_name')}}
		@elseif (array_get($data,'post_account_number ') && array_get($data,'post_account_name'))
		ゆうちょ銀行</br>
		記号：{{array_get($data,'post_account_kigou')}}&nbsp;&nbsp;
		番号：{{array_get($data,'post_account_number')}}&nbsp;&nbsp;
		{{array_get($data,'post_account_name')}}
		@endif
	</div>
	@elseif (array_get($data,'invoice_type ')== 2) 		{{--口座振替--}}
	<div class="bank_account">
		平成{{array_get($data,'due_date_y')}}年{{array_get($data,'due_date_m')}}月{{array_get($data,'due_date_d')}}日に
		お振替えさせていただきますので、よろしくお願い申し上げます。
	</div>
	@else								{{--その他--}}
	<div class="bank_account">
		お支払期限までにお支払いただけますよう　よろしくお願い申し上げます。
	</div>
	@endif
</div>
{{--</body>--}}
{{--</html>--}}

