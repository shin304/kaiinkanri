<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>{{$lan::get('invoice_print_title')}}</title>
		<link rel="stylesheet" type="text/css" href="{{$_app_path}}css/invoice_print.css" />
	</head>
	<body>
		<div class="description">
			<div class="content">
				<div class="header">
					<div class="title">
						{{$lan::get('your_invoice_title')}}
					</div>
					<hr>
					<div class="publish_date">
						{{$lan::get('heisei_title')}}
						{{$request['publish_date_y']}}
						{{$lan::get('year_title')}}
						{{$request['publish_date_m']}}
						{{$lan::get('month_title')}}
						{{$request['publish_date_d']}}
						{{$lan::get('day_title')}}
					</div>
					<hr>
					<div class="parent_name">
						{{$data['parent_name']}} 
						{{$lan::get('mr_title')}}
					</div>
					<div class="school_name">
						{{$request['school_name']}}
					</div>
					<div style="clear:both;"></div>
					<div class="school_address">
						{{$request['school_address']}}
					</div>
					<div class="school_address">
						{{$lan::get('representative_title')}}
						{{$request.school_daihyou}}
					</div>
					<div class="comment" >
						{{$raise_your_invoice_title}}
					</div>
					<br>
					<div class="amount_tax">
						{{$invoice_amount_confirm_title}}&nbsp;&nbsp;&yen;{{$request.amount_tax|number_format}}{{$include_consumption_taxes_title}}
					</div>
					<hr>
					<div class="due_date">
						{{$payment_deadline_title}}&nbsp;&nbsp;{{$heisei_title}}{{$request.due_date_y}}{{$year_title}}{{$request.due_date_m}}{{$month_title}}{{$request.due_date_d}}{{$day_title}}
					</div>
				</div>
				<br>
				<div class="body_area">
					{{$your_invoice_summary_title}}<br>

					{{foreach from=$data.student_list item=student_row}}
						{{$student_row.student_name}} {{$mr_title}}<br>
						{{assign var="total_price" value="0"}}
						<div class="clearfix student_item_list">
							{{* プラン *}}
							{{foreach from=$request.class_name[$student_row.id] item=name key=k}}
								{{if $name}}
									<div class="item_name">
										{{$name}}&nbsp;
									</div>
									<div class="item_price">
										&yen;{{$request.class_price[$student_row.id][$k]|number_format}}
									</div>
									{{assign var="total_price" value=$total_price+$request.class_price[$student_row.id][$k]}}
								{{/if}}
							{{/foreach}}

							{{* イベント *}}
							{{foreach from=$request.course_name[$student_row.id] item=name key=k}}
								{{if $name}}
									<div class="item_name">
										{{$name}}&nbsp;
									</div>
									<div class="item_price">
										&yen;{{$request.course_price[$student_row.id][$k]|number_format}}
									</div>
									{{assign var="total_price" value=$total_price+$request.course_price[$student_row.id][$k]}}
								{{/if}}
							{{/foreach}}

							{{* 個別入力 *}}
							{{foreach from=$request.custom_item_name[$student_row.id] item=name key=k}}
								{{if $name || $request.custom_item_price[$student_row.id][$k]}}
									<div class="item_name">
										{{$name}}&nbsp;
									</div>
									<div class="item_price">
										{{if $request.custom_item_price[$student_row.id][$k]|is_numeric}}
											{{if $request.custom_item_price[$student_row.id][$k] < 0}}
												△&yen;{{$request.custom_item_price[$student_row.id][$k]|replace:'-':''|number_format}}
											{{else}}
												&yen;{{$request.custom_item_price[$student_row.id][$k]|number_format}}
											{{/if}}
											{{assign var="total_price" value=$total_price+$request.custom_item_price[$student_row.id][$k]}}
										{{else}}
											&nbsp;
										{{/if}}
									</div>
								{{/if}}
							{{/foreach}}

							<div class="student_total">
								{{$subtotal_title}}&nbsp;&nbsp;&nbsp;
							</div>
							<div class="item_price">
								&yen;{{$total_price|number_format}}
							</div>
						</div>
					{{/foreach}}
				</div>

				<hr>

				{{assign var="is_discount_exists" value=false}}
				<div class="body_area">
					<div class="clearfix discount_item_list">
						{{foreach from=$request.discount_name key=k item=v}}
							{{if $v || $request.discount_price[$k]}}
								<div class="item_name">
									{{$v}}
								</div>
								<div class="item_price">
									△&yen;{{$request.discount_price[$k]|number_format}}
								</div>
								{{assign var="is_discount_exists" value=true}}
							{{/if}}
						{{/foreach}}
					</div>
				</div>

				{{if $is_discount_exists}}
					<hr>
				{{/if}}

				<div class="footer_area">
					<div class="clearfix discount_item_list">
						<div class="item_name">
							{{$total_title}}&nbsp;&nbsp;&nbsp;
						</div>
						<div class="item_price">
							{{if $request.amount}}
								&yen;{{$request.amount|number_format}}
							{{else}}
								&yen;<span id="amount"></span>
							{{/if}}
						</div>

						<div class="item_name">
							{{$consumption_taxes_title}}&nbsp;&nbsp;&nbsp;
						</div>
						<div class="item_price">
							{{if $request.tax_price}}
								&yen;{{$request.tax_price|number_format}}
							{{else}}
								&yen;<span id="tax_price"></span>
							{{/if}}
						</div>

						<div class="item_name">
							{{$include_taxes_title}}&nbsp;&nbsp;&nbsp;
						</div>
						<div class="item_price">
							{{if $request.amount_tax}}
								&yen;{{$request.amount_tax|number_format}}
							{{else}}
								&yen;<span id="amount_tax"></span>
							{{/if}}
						</div>
					</div>
					<br/><br/><br/>
					<div class="account_area">
						{{$transfer_follow_account_below_title}}
						<br/>
						{{if $request.bank_name && $request.bank_account_name}}
						<div class="bank_name">
							{{$request.bank_name}}&nbsp;&nbsp;{{$request.branch_name}}
							<br/>
							{{$request.bank_account_number}}
							<br/>
							{{$request.bank_account_name}}&nbsp;&nbsp;{{**$request.bank_account_name_kana**}}
						</div>
						{{elseif $request.post_account_number && $request.post_account_name}}
						<div class="bank_name">
							{{$japan_post_bank_title}}
							<br/>
							{{$symbol_title}}{{$request.post_account_kigou}}
							<br/>
							{{$number_title}}{{$request.post_account_number}}
							<br/>
							{{$request.post_account_name}}
						</div>
						{{/if}}
						<div style="clear:both;"></div>
						<br/>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>