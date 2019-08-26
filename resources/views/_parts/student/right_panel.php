<td id="right_content" class="t1td3" width="237">
	<h2 id="content_h2" class="box_border1">
		<i class="fa fa-newspaper-o"></i>お知らせ/アクティビティ
	</h2>
	<div id="right_content_box">
		<div class="news_box">
			@foreach ($panel_news as $row)
			<p class="p12 news_box_right_top">
				<i class="fa fa-bookmark"></i>お知らせ
			</p>
			<p class="p11 news_box_date">
				<i class="fa fa-bookmark"></i> {{$row.date|date_format:"%m/%d"}}
			</p>

			<p class="p14">「{{$row.title}}」への申し込みがありました。</p>
		</div>
		<!--news_box-->
		@endforeach

		<div class="news_box">
			@foreach ($panel_info._activity_list as $act_row)
			<p class="p12 news_box_right_top">
				<i class="fa fa-bookmark"></i>請求について
			</p>
			<p class="p11 news_box_date">
				<i class="fa fa-bookmark"></i>{{$act_row.payment_day|date_format:"%m/%d"}}
			</p>

			<p class="p14">{{$act_row.payer_name}}さんの請求期限が過ぎました。</p>
		</div>
		<!--news_box-->
		@endforeach @if (!$panel_news&&!$panel_info._activity_list)
		お知らせ/アクティビティはありません。 @endif

	</div>
	<!--right_content_box-->

</td>
</tr>
</table>
<!--wrapper-->
