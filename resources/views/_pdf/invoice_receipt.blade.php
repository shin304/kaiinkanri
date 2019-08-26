<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>請求書印刷</title>
	</head>
	<body>
			<div id="content">
				<div class="header_top">
					<p>平成{{$data.publish_date_y}}年{{$data.publish_date_m}}月{{$data.publish_date_d}}日</p>
				</div>
				<header>
					<h1>領収書</h1>
				</header>
				<section id="pay_info">
					<div class="pay_info_left">
						<p class="company_name">{{$data.parent_name}}  様</p>

						<p class="pay_p1">下記のとおり　領収いたしました。</p>
						<p class="pay_much">領収金額　　&nbsp;&nbsp;&yen;{{$data.amount_tax|number_format}} -</p>
						{{**<p class="pay_kigen">お支払期限：平成{{$data.due_date_y}}年{{$data.due_date_m}}月{{$data.due_date_d}}日</p>**}}
					</div><!---->
					<div class="pay_info_right">
						<p class="my_company_name">{{$data.school_name}}</p>
						<p class="my_company_address">
							{{$data.school_address}}
						</p>
						<p class="my_company_daihyou">{{$data.school_daihyou}}</p>
					</div>
					<div class="clr"></div>
				</section>
				<table id="pay_table">
					<tr>
						<th class="th1">内容</th>
						<th class="th4">金額</th>
					</tr>
					{{assign var="total_price" value="0"}}
					{{foreach from=$data.student_list item=student_row}}
						{{$student_row.student_name}} 様<br>
							{{* プラン *}}
							{{foreach from=$data.class_name[$student_row.id] item=name key=k}}
								{{if $name}}
									<tr>
										<td>
										{{$name}}&nbsp;
										</td>
										<td class="td2">
										&yen;{{$data.class_price[$student_row.id][$k]|number_format}}
										</td>
									</tr>
									{{assign var="total_price" value=$total_price+$data.class_price[$student_row.id][$k]}}
								{{/if}}
							{{/foreach}}

							{{* イベント *}}
							{{foreach from=$data.course_name[$student_row.id] item=name key=k}}
								{{if $name}}
									<tr>
										<td>
										{{$name}}&nbsp;
										</td>
										<td class="td2">
										&yen;{{$data.course_price[$student_row.id][$k]|number_format}}
										</td>
									</tr>
									{{assign var="total_price" value=$total_price+$data.course_price[$student_row.id][$k]}}
								{{/if}}
							{{/foreach}}

							{{* 個別入力 *}}
							{{foreach from=$data.custom_item_name[$student_row.id] item=name key=k}}
								{{if $name || $data.custom_item_price[$student_row.id][$k]}}
									<tr>
										<td>
											{{$name}}&nbsp;
										</td>
										<td class="td2">
										{{if $data.custom_item_price[$student_row.id][$k]|is_numeric}}
											{{if $data.custom_item_price[$student_row.id][$k] < 0}}
												&yen;-{{$data.custom_item_price[$student_row.id][$k]|replace:'-':''|number_format}}
											{{else}}
												&yen;{{$data.custom_item_price[$student_row.id][$k]|number_format}}
											{{/if}}
											{{assign var="total_price" value=$total_price+$data.custom_item_price[$student_row.id][$k]}}
										{{else}}
											&nbsp;
										{{/if}}
										</td>
									</tr>
								{{/if}}
							{{/foreach}}
						{{/foreach}}
							</table>

							<table id="pay_table2">
								<tr>
									<th>
										小計&nbsp;&nbsp;&nbsp;
									</th>
									<td class="td2">
										&yen;{{$total_price|number_format}}
									</td>
								</tr>
								{{foreach from=$data.discount_name key=k item=v}}
									{{if $v || $data.discount_price[$k]}}
										<tr>
											<th>
												{{$v}}&nbsp;&nbsp;&nbsp;
											</th>
											<td class="td2">
												&yen;-{{$data.discount_price[$k]|number_format}}
											</td>
										</tr>
										{{assign var="is_discount_exists" value=true}}
									{{/if}}
								{{/foreach}}
								<tr>
									<th>
										消費税&nbsp;&nbsp;&nbsp;
									</th>
									<td class="td2">
										{{if $data.tax_price}}
											&yen;{{$data.tax_price|number_format}}
										{{else}}
											&yen;<span id="tax_price"></span>
										{{/if}}
									</td>
								</tr>
								<tr>
									<th>
										合計&nbsp;&nbsp;&nbsp;
									</th>
									<td class="td2">
										{{if $data.amount_tax}}
											&yen;{{$data.amount_tax|number_format}}
										{{else}}
											&yen;<span id="amount_tax"></span>
										{{/if}}
									</td>
								</tr>
				</table>

				<div class="clr"></div>
				<p class="pay_p2">ありがとうございました。</p>
		</div>
	</body>
</html>