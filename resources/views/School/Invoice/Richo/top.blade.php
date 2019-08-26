@extends('_parts.master_layout') @section('content')
	<script type="text/javascript">
	
	$(function() {
		
		var nowInputIndex =  1;		// 項目一括設定
		
		var discount_count = 1;
		
		// 請求書年月取得
		@if(isset($heads))
		var year = "{{array_get($heads, 'invoice_year')}}";
		var month = "{{array_get($heads, 'invoice_month')}}";
		@endif
		var year_month = year + "-" + month;
		var day = [year,month];

		
		$('#selectall').click(function() {  //on click
	        if(this.checked) { // check select status
	            $('.parent_select').each(function() { //loop through each checkbox
	            	if(!this.disabled)
	                	this.checked = true;  //select all checkboxes with class "question_select"
	            });
	        }else{
	            $('.parent_select').each(function() { //loop through each checkbox
	                this.checked = false; //deselect all checkboxes with class "question_select"
	            });
	        }
	    });
		$("#school_type").change(function(){
			var school_cat = $(this).val();
			if (!school_cat)
			{
				$("#grade_option option").remove();
				$("#grade_option").prepend($("<option>").html("").val(""));
				return;
			}
			$.get(
				"{{$_app_path}}ajaxMailMessage/school",
				{school_cat:school_cat},
				function(data)
				{
					var desc = "{{$lan::get('student_year_title')}}";
					$("#grade_option option").remove();
					$("#grade_option").append($("<option>").html(desc).val(key));
					for(var key in data.grades)
					{
						var school_year_id = (parseInt(key)) + 1;
						var school_year = school_year_id + desc;
						$("#grade_option").append($("<option>").html(school_year).val(school_year_id));
					}
					$("#grade_option").prepend($("<option>").html("").val(""));
					$("#grade_option").val("");
				},
				"jsonp"
			);
		});
		$("#data_table").tablesorter({
			headers: {
				0: { sorter: false}
			}
		});
 		$("#arrear_list").tablesorter({
 		});


		$('#search_cond_clear').click(function() {  // clear
			$("select[name='invoice_year_from_d']").val("");
			$("select[name='invoice_month_from_d']").val("");
			$("select[name='invoice_year_to_d']").val("");
			$("select[name='invoice_month_to_d']").val("");

			$("input[name='parent_name']").val("");
			$("select[name='school_category']").val("");
			$("select[name='school_year']").val("");
			$("#school_grade option").remove();

			$("input[name='student_name']").val("");
			$("select[name='class_id']").val("");

			$("select[name='is_established']").val("");
			$("select[name='is_requested']").val("");

			$("select[name='workflow_status']").val("");

			$("select[name='course_id']").val("");
			$("select[name='is_recieved']").val("");

			$('input[name="invoice_type_d[0]"').prop("checked",false);
			$('input[name="invoice_type_d[1]"').prop("checked",false);
			$('input[name="invoice_type_d[2]"').prop("checked",false);
			$('input[name="invoice_type_d[3]"').prop("checked",false);
			
			$('input[name="paid_type_d[0]"').prop("checked",false);
			$('input[name="paid_type_d[1]"').prop("checked",false);

			$('input[name="inactive_flag_d"').prop("checked",false);

		});

		$('#search_cond_simple_clear').click(function() {  // clear
			$("select[name='invoice_year_from_s']").val("");
			$("select[name='invoice_month_from_s']").val("");
			$("select[name='invoice_year_to_s']").val("");
			$("select[name='invoice_month_to_s']").val("");

			$('input[name="invoice_type_s[0]"').prop("checked",false);
			$('input[name="invoice_type_s[1]"').prop("checked",false);
			$('input[name="invoice_type_s[2]"').prop("checked",false);
			$('input[name="invoice_type_s[3]"').prop("checked",false);
			
			$('input[name="paid_type_s[0]"').prop("checked",false);
			$('input[name="paid_type_s[1]"').prop("checked",false);

			$('input[name="inactive_flag_s"').prop("checked",false);
		});

		$('#current_month_d, #current_month_s').click(function() {

			$.get(
				"{{$_app_path}}ajaxInvoice/newest_year_month",
				{},
				function(data)
				{
					var curr_year  = data.curr_year;
					var curr_month = data.curr_month;
					$("select[name*='invoice_year_from']").val(curr_year);
					$("select[name*='invoice_month_from']").val(curr_month);
					$("select[name*='invoice_year_to']").val(curr_year);
					$("select[name*='invoice_month_to']").val(curr_month);
				},
				"jsonp"
			);
		});

		$('#search_condition_detail_btn').click(function() {
			$('#detail_search').hide();
			$('#simple_search').show();
		});
		
		$('#search_condition_simple_btn').click(function() {
			$('#simple_search').hide();
			$('#detail_search').show();
		});

		$('#btn_confirm').click(function() {
			$("#action_form").attr("action", "{{$_app_path}}invoice/multiEditComplete?invoice_year_month={{array_get($heads, 'invoice_year_month')}}");
			$("#action_form").submit();
			return false;
	    });

		$(document).ready(function(){
			if( {{$request['search_cond']}} == 1){
				$('#simple_search').hide();
			}
			if( {{$request['search_cond']}} == 2){
				$('#detail_search').hide();
			}
		});

		// 一括請求項目追加
		$('#invoice_itemcreate').click(function() {
			// 表示は必ず保護者
			$("input[name=parent_indivi_div]").val("1");
			$('input[name=parent_indivi_div]:eq(0)').prop('checked', true);
			
			$(".message_area li").remove();

			$.get(
				// 作成対象の年月分取得   関数を呼び出すが、戻り値は使用しない
				"{{$_app_path}}ajaxInvoice/getinvoiceyearmonth",
				{},
		 		function(data)
		 		{
					var func;
					$("#invoic_item_create_dialog").dialog({
						 title :("{{$lan::get('add_amount_invoice_item_title')}}".replace(/%m/g,month)).replace(/%Y/g,year),	
						 width : 1000,
						 buttons : {
							"{{$lan::get('register_title')}}" : function() {
								// データ設定
								var parent_indivi_div = $('input[name=parent_indivi_div]:eq(0)').prop('checked') ? 1 : 2;
								var data_cnt = 0;
								var school_category = ""; 
								var school_year = "";
								var class_event = "";
								var adjust = "";
								var fee = "";

								$("select").each(function(index, element){
			 						var elm_id = new String($(element).attr("id"));
			 						
									if( elm_id.indexOf("class_event") != -1 ){
			 							if( class_event.length > 0 )	class_event += ",";
			 							class_event += $("#" + elm_id).val() + " ";
			 						}
			 						if( elm_id.indexOf("adjust") != -1 ){
			 							if( adjust.length > 0 )	adjust += ",";
			 							adjust += $("#" + elm_id).val() + " ";
			 						}
							    });
								
								$("input").each(function(index, element){
			 						var elm_id = new String($(element).attr("id"));
			 						
			 						if( elm_id.indexOf("fee") != -1 ){
			 							if( fee.length > 0 )	fee += ",";
			 							fee += $("#" + elm_id).val() + " ";
			 							data_cnt++;
			 						}
							    });

						        $.ajax({
			            			url: "{{$_app_path}}ajaxInvoice/setspotinvoice",
			            			data: {
			            				parent_indivi_div: parent_indivi_div,
										data_cnt:          data_cnt,
										school_category:   school_category,
										school_year:       school_year,
										class_event:       class_event,
										adjust:            adjust,
										fee:               fee,
										year_month:        year_month,
									},
			                        dataType: 'json',
						            success: function(v_data){
 										if( v_data.result_code == "OK"){
											$("#inputActive").children().remove();
											$("#invoic_item_create_dialog").dialog("close");
											
											// リロード
											$("#action_form").attr("action", "{{$_app_path}}invoice/search?simple&search_cond=2&invoice_year_month=" + year_month + "&invoice_year_to_s=" + year + "&invoice_month_to_s=" + month + "&invoice_year_from_s=" + year + "&invoice_month_from_s=" + month);
											$("#action_form").submit();
											return false;
										} else {
										for(var key in v_data.error){
												$(".message_area").append("<li class='error_message'>" + v_data.error[key] + "</li>");
											}
										}
										return false;
						            },
			            			error: function(xhr, status, error){
										$("#inputActive").children().remove();
										$("#invoic_item_create_dialog").dialog("close");
			            			}
			        			});
								// 項目削除
//								return false;;
							},
							"{{$lan::get('cancel_title')}}" : function() {
								// 項目削除
								$("#inputActive").children().remove();
								$(this).dialog("close");
								return false;;
							}
						}
					});
		 			
		 		},
				"jsonp"
			);

			// とりあえず表示されたとき1個表示
 			addItem(nowInputIndex);
 			nowInputIndex++;
		});

		// 請求項目追加
		$('#inputAdd').click(function() {
			
			addItem(nowInputIndex);
			nowInputIndex++;
		});
		
		// 保護者／個別
 		$("input[name=parent_indivi_div]").change(function() {

 			var selected = $('input[name=parent_indivi_div]:eq(0)').prop('checked')
  			if( selected ){
				// 保護者 プラン／イベントをdisable
				$("select[name *= 'class_event_div']").each( function(){
					var id_option = "#" + $(this).attr("id");
					$(id_option + " option").remove();
					$(id_option).prepend($("<option>").html("").val(""));

					$(this).attr("disabled", "disabled");
				});
				
			} else {
				// プラン プラン／イベントをenable
				$("select[name *= 'class_event_div']").each( function(){
					$(this).removeAttr("disabled");
					
					// 現在表示されている項目に対して、更新
					var id_class = "#" + $(this).attr("id");
					var ids = new String($(this).attr("id"));
					var split = ids.split("_");
					
					var school_category_id = "#school_category_" + split[3];
					var school_year_id     = "#school_year_" + split[3];
					var school_category    = $(school_category_id).val();
					var school_year        = $(school_year_id).val();

 					$.get(
 						"{{$_app_path}}ajaxInvoice/getclassevent",
 						{school_category:school_category,
 						school_year:school_year,
 						},
 						function(data)
 						{
							$(id_class + " option").remove();
							for(var key in data.class_event_list){
								$(id_class).append($("<option>").html(data.class_event_list[key]).val(key));
							}
							$(id_class).prepend($("<option>").html("").val(""));
 						},
 						"jsonp"
 					);
				});
			}
 		});
		
 	});

	// 項目追加
	function addItem(nowInputIndex){
		
		var newTable = $( "TABLE", "#inputBase" ).clone();//inputBaseのIDのTABLEタグをnewTableへ
		var newHR    = $( "HR"   , "#inputBase" ).clone();//inputBaseのIDのHRタグをnewHRへ

		$( ".formItem", newTable ).each( function(){//newTable内のformItemプラン指定のそれれぞれで
			var title = $( this ).attr( 'title' );//title属性の内容を変数titleへ
			$( this ).attr( 'name', 'input[' + nowInputIndex + '][' +  title + ']');//name属性の内容をinput[nowInputIndex][title]へ
			$( this ).removeAttr( 'title' );//title属性を削除する

		});

		$( ".NewCategory",newTable).attr( 'id', 'school_category_' + nowInputIndex  ).removeClass('NewCategory');//newTable内のNewDateInputプラン指定でid属性をDateInput_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".NewYear",newTable).attr( 'id', 'school_year_' + nowInputIndex  ).removeClass('NewYaer');//newTable内のNewfromTimeプラン指定でid属性をfromTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".NewClass",newTable).attr( 'id', 'class_event_div_' + nowInputIndex  ).removeClass('NewClass');//newTable内のNewtoTimeプラン指定でid属性をtoTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".NewAdjust",newTable).attr( 'id', 'invoice_adjust_' + nowInputIndex  ).removeClass('NewAdjust');//newTable内のNewex_fromTimeプラン指定でid属性をex_fromTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		$( ".Newfee",newTable).attr( 'id', 'invoice_fee_' + nowInputIndex  ).removeClass('Newfee');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
		
		$( "#inputActive" ).append( newTable );//inputActiveのID指定にnewTableの内容を追加する
		$( "#inputActive" ).append( newHR    );//inputActiveのID指定にnewHRの内容を追加する
		
		
  		var id_category = "#school_category_" + nowInputIndex;
  		var id_year     = "#school_year_" + nowInputIndex;
  		var id_class    = "#class_event_div_" + nowInputIndex;

		// プラン・イベントの場合
		var selected_div = $('input[name=parent_indivi_div]:eq(1)').prop('checked')
		if( selected_div ){
			var class_id = "#" + "class_event_div_" + nowInputIndex;
			$(class_id).removeAttr("disabled");

			var school_category = $(id_category).val();
 			if( school_category == null )	school_category = "";
 			var school_year     = $(id_year).val();
 			if( school_year == null )	school_year = "";

			$.get(
				"{{$_app_path}}ajaxInvoice/getclassevent",
				{school_category:school_category,
				school_year:school_year,
				},
 				function(data)
 				{
					$(class_id + " option").remove();
					for(var key in data.class_event_list){
						$(class_id).append($("<option>").html(data.class_event_list[key]).val(key));
					}
					$(class_id).prepend($("<option>").html("").val(""));
 				},
 				"jsonp"
 			);
		}

		// 削除処理設定
		$( "A.inputDelete", newTable ).click( function(e){
			e.preventDefault();
			inputDel( newTable );
			return false;
		});

		
		// 摘要の初期値取得
		$( "#invoice_adjust_" + nowInputIndex, newTable ).change( function(e){
			
			var adjust = $(this).val();
			
			var id = $(this).attr("id");
			var split = id.split("_");
			var no = split[2];

 			$.get(
 				"/school/ajaxInvoice/getinitfee",
 				{adjust: adjust},
 				function(v_data)
 				{
 					// 金額設定
 					$("#invoice_fee_" + no).val(v_data);
 				},
 				"jsonp"
 			);
			return false;
		});
		
		
		$( newTable ).show();
		
  		// 学校区分が選択された
		$( "#school_category_" + nowInputIndex, newTable ).change( function(e){
			
 			var school_category = $(id_category).val();
 			if( school_category == null )	school_category = "";
 			var school_year     = $(id_year).val();
 			if( school_year == null )	school_year = "";

 			var kinds = $(id_category).val();
			
			if (kinds == "") {
				// 設定なし
				$(id_year + " option").remove();
				$(id_year).prepend($("<option>").html("").val(""));
			}
			else if (kinds < 10) {
				// 学年の最大値
				var years = 3;
				if (kinds == 0){
					years = 6;
				}else if (kinds == 3) {
					years = 4;
				}
				// optionの設定
				$(id_year + " option").remove();
				for(var key = 1; key <= years; key++){
					$(id_year).append($("<option>").html(key+"年").val(key));
				}
				$(id_year).prepend($("<option>").html("").val(""));
				$(id_year).val("");
			}
			else {
				// 学生ではない
				$(id_year + " option").remove();
				$(id_year).prepend($("<option>").html("").val(""));
			}

			$.get(
				"/school/ajaxInvoice/getclassevent",
				{
					school_category : school_category,
					school_year : school_year,
				},
				function(data)
				{
					$(id_class + " option").remove();

					for(var key in data.class_event_list){
						$(id_class).append($("<option>").html(data.class_event_list[key]).val(key));
					}
					$(id_class).prepend($("<option>").html("").val(""));
				},
				"jsonp"
			);
			return false;
		});

  		
		// 学年
		$( "#school_year_" + nowInputIndex, newTable ).change( function(e){

			var school_category = $(id_category).val();
 			if( school_category == null )	school_category = "";
 			var school_year     = $(id_year).val();
 			if( school_year == null )	school_year = "";

 			$.get(
				"/school/ajaxInvoice/getclassevent",
				{
					school_category : school_category,
					school_year : school_year,
				},
				function(data)
				{
					$(id_class + " option").remove();
					for(var key in data.class_event_list){
						$(id_class).append($("<option>").html(data.class_event_list[key]).val(key));
					}
					$(id_class).prepend($("<option>").html("").val(""));
				},
				"jsonp"
			);
		});
		
		nowInputIndex++;
		
		return false;
	}
	
	// 削除
	function inputDel( item ){
		$( item ).next().remove();
		$( item ).remove();
		return false;
	}
	
	</script>
	
<!-- 	 メニュー  -->
	@include('_parts.invoice.axis_menu')

<!-- 	パンくず  -->
	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('invoice_search') !!}</div>  --}}
	@include('_parts.topic_list')
	
		<div class="alart_box box_shadow">
			@if( isset($action_status) and $action_status == "OK")
				<ul class="message_area"><li class="info_message">{{$action_message}}</li></ul>
			@elseif( isset($action_status))
				<ul class="message_area"><li class="error_message">{{$action_message}}</li></ul>
			@else
				<pre> {{$lan::get('select_confirm_edited_invoice_title')}}</pre>
			@endif
		</div>
		<div id="section_content_in">
			&nbsp;<input type="checkbox" id="selectall">&nbsp;{{$lan::get('select_all_title')}}</input><br/>
			<form id="action_form" method="post">
			 {{ csrf_field() }}
				<table class="table_list tablesorter body_scroll_table" id="data_table">
					<thead>
						<tr>
							<th style="width: 50px;;" class="text_title">{{$lan::get('selection_title')}}</th>
							<th style="width:140px;;" class="text_title header">{{$lan::get('member_name_title')}}</th>
							<th style="width:380px;;" class="text_title header">{{$lan::get('status_title')}}</th>
							<th style="width:110px;;" class="text_title header">{{$lan::get('invoice_amount_title')}}</th>
							<th style="width:100px;;" class="text_title header">{{$lan::get('invoice_method_title')}}</th>
							<th style="width:120px;;" class="text_title header">{{$lan::get('create_date_title')}}</th>
						</tr>
					</thead>
					<tbody>
					@if(isset($invoice_list))
						@foreach ($invoice_list as $idx => $row)
							<tr class="table_row">
								<td style="width:50px;text-align:center;">
									@if(array_get($row, 'workflow_status') < 1 && array_get($row, 'active_flag') == 1)
										<input type="checkbox" name="parent_ids[]" value="{{array_get($row, 'id')}}" class="parent_select"/>
									@endif
								</td>
								<td style="width:140px;">
									@foreach (array_get($row, 'student_list') as $student_row)
									{{--	@if(array_get($auths, 'student_detail') == 1 ) --}}
										<a  href="{{$_app_path}}invoice/detail?id={{array_get($row, 'id')}}">
											{{array_get($student_row, 'student_name')}}
										</a><br/>
										{{-- @else 
										<label>array_get($student_row, 'student_name')</label>
										@endif --}}
									@endforeach
								</td>
								<td style="width:380px;text-align:center;">
									<ul class="progress_ul ">
									@if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 0)
										<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
									@else
										<li class="bill1">{{$lan::get('status_imported_title')}}</li>
									@endif
									@if(array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 1)
										<li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
									@else
										<li class="bill2">{{$lan::get('confirmed_title')}}</li>
									@endif
									@if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 11)
										<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
									@else
										<li class="bill3">{{$lan::get('invoiced_title2')}}</li>
									@endif
									@if( array_get($row, 'active_flag') != 1 or array_get($row, 'workflow_status') < 31)
										<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>
									@else
										<li class="bill4">{{$lan::get('payment_already_title')}}</li>
									@endif
									</ul>

								</td>
								<td style="width:110px;text-align:right;">
									@if(array_get($row, 'amount_display_type') == "0" or !array_get($row, 'sales_tax_rate'))
									{{number_format(array_get($row, 'amount'))}}
									@else
									@php
										$x = array_get($row, 'amount');
										$y = array_get($row, 'sales_tax_rate');
										$amount_tax = x+floor(x*y);
									@endphp
									{{number_format($amount_tax)}}
									@endif
								</td>
								<td style="width:100px;text-align:center;">
									{{array_get($invoice_type, 'row.invoice_type')}}
								</td>
								<td style="width:120px;text-align:center;">
									@if(array_get($row, 'register_date'))
									{{Carbon\Carbon::parse(array_get($row, 'register_date'))->format('Y-m-d')}}	
									@endif
								</td>
							</tr>
							@endforeach
						@endif
						@if(empty($invoice_list))
							<tr class="table_row">
								<td class="error_row">{{$lan::get('information_displayed_title')}}</td>
							</tr>
						@endif
					</tbody>
				</table>
				@if( isset($invoice_list))
				<input type="submit" value="{{$lan::get('determine_invoice_title')}}" id="btn_confirm" class="btn_green"/>
				@endif
			</form> <!-- action_form -->
			
		</div> <!-- section_content_in -->
</div> <!-- section -->


<div id="invoic_item_create_dialog"  style="display:none;">

	<div>
		<table id="table5" style="width:100%;">
			<tr>
				<td class="t4td1" colspan="5">
					{{$lan::get('subject_invoice_title')}}
						<input type="radio" name="parent_indivi_div"  value="1" checked />{{$lan::get('guardian_title')}}
						&nbsp;&nbsp;
						<input type="radio" name="parent_indivi_div"  value="2"/>{{$lan::get('attend_class_title')}}
				</td>
			</tr>
		</table>
	</div>
	

	<div id="inputBase" style="display:none;">
		<table id="table4" style="width:100%;">
			<tr>
				<td class="t4td1" colspan="6">
					&nbsp;&nbsp;{{$lan::get('class_event_title')}}
						<select style="width: 180px" disabled=disabled  class="formItem NewClass"  title="class_event_div">
<!-- 							受講プランのみ  -->
							<option value="" ></option>
						</select>
					&nbsp;&nbsp;{{$lan::get('outline_title')}}
						<select  style="width: 140px" class="formItem NewAdjust" title="invoice_adjust">
							<option value="" ></option>
							@foreach ($invoice_adjust as $row)
								<option value="{{array_get($row, 'id')}}" >{{array_get($row, 'name')}}</option>
							@endforeach
						</select>
					&nbsp;&nbsp;{{$lan::get('amount_of_money_title')}}
						<input  type="text"  class="formItem Newfee" style="ime-mode:inactive; width:80px; text-align:right;" value=""  title="invoice_fee"/>
				{{--	@if(array_get($auths, 'invoice_delete') == 1) --}}
					<a class="inputDelete" href="#"><input type="button" value="{{$lan::get('delete_title')}}"/></a>
				{{--	@endif --}}
				</td>
			</tr>
		</table>
		<hr class="line"></hr>
	</div>
			
	<div style="margin:10px 10px 17px 120px;">
		<button id="inputAdd" style="width:100px">+{{$lan::get('add_item_title')}}</button>
	</div>
	<div >
		<ul class="message_area"></ul>
	</div>
	
	
</div>

@stop
