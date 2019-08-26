<table>
	<tr>
		<td id="right_content" class="t1td3" width="237">
			<h2 id="content_h2" class="box_border1">
				<i class="fa fa-newspaper-o"></i>お知らせ/アクティビティ
			</h2>
			<div id="right_content_box">
				{{foreach from=$panel_news item=row}}
				<div class="news_box">
					<p class="p12 news_box_right_top">
						<i class="fa fa-bookmark"></i>お知らせ
					</p>
					<p class="p11 news_box_date">
						<i class="fa fa-clock-o"></i>02/15
					</p>
					<div class="clr"></div>
					<p class="p14 news_box_p1">「セミナー」への申し込みがありました。</p>
				</div>
				<!--news_box-->
				{{/foreach}} {{foreach
				from=$panel_info._activity_list|smarty:nodefaults item=act_row}}
				<div class="news_box">
					<p class="p12 news_box_right_top">
						<i class="fa fa-bookmark"></i>請求について
					</p>
					<p class="p11 news_box_date">
						<i class="fa fa-bookmark"></i>{{$act_row.payment_day|date_format:"%m/%d"}}
					</p>

					<p class="p14">{{$act_row.payer_name}}さんの請求期限が過ぎました。</p>
				</div>
				<!--news_box-->
				{{/foreach}} {{if !$panel_news&&!$panel_info._activity_list}}
				お知らせ/アクティビティはありません。 {{/if}}
			</div>
			<!--right_content_box-->
		</td>
	</tr>
</table>
<!--wrapper-->
