<script type="text/javascript">
$(function() {
	$('#search_cond_clear').click(function() {  // clear
		$("input[name='select_word']").val("");
		$("select[name='select_grade']").val("");
		$("select[name='select_pschool']").val("");
		$("select[name='select_state']").val("");
		return false;
	});

	$('input[name="disp_billing"]').change(function() {
		var prop = $('#prop').prop('checked');
	    if (prop) {
	        $('#billing_state').show();
	      } else {
	        $('#billing_state').hide();;
	      }
	});
});
</script>

<table>
	<colgroup>
		<col width="10%" />
		<col width="30%" />
		<col width="10%" />
		<col width="30%" />
	</colgroup>
	<tr>
		<th>名前(フリガナ)</th>
		<td><input class="text_long" type="search" name="select_word"
			id="select_word" value="" /></td>
	</tr>
	<tr>
		<th>ステータス</th>
		<td><select name="select_state" id="select_state"
			style="max-width: 200px;">
				<option value=""></option>
				<option label="契約中" value="1" selected="selected">契約中</option>
				<option label="契約終了" value="9">契約終了</option>

		</select></td>
	</tr>
	<tr>
		<th>最新の請求情報</th>
		<td><input id="prop" type="checkbox" name="disp_billing" value="1"
			checked />&nbsp;表示する</td>
		<td id="billing_state"><select name="workflow_status"
			id="select_state" style="max-width: 200px;">
				<option value=""></option>
				<option label="編集中" value="0">編集中</option>
				<option label="編集完了" value="1">編集完了</option>
				<option label="請求書発送済み" value="11">請求書発送済み</option>
				<option label="請求データ作成済み" value="21">請求データ作成済み</option>
				<option label="口座振替 処理中" value="22">口座振替 処理中</option>
				<option label="口座振替未完了" value="29">口座振替未完了</option>
				<option label="入金済み" value="31">入金済み</option>

		</select></td>
	</tr>
	<tr>
		<th>{{$lan['register_date']}}</th>
		<td><input type="text" id="datepicker" name="register_date" value=""></td>
		<th>{{$lan['update_date']}}</th>
		<td><input type="text" id="datepicker1" name="update_date" value=""></td>
	</tr>
</table>

<div class="clr"></div>
<input type="submit" id="btn_student_search" class="submit"
	name="search_button" value="検索">
<input type="button" class="submit" id="search_cond_clear"
	value="すべてクリア">
<div class="clr"></div>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker1" ).datepicker();
  } );
  </script>