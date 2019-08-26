@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
	$(function() {

		/* 保存ボタン */
		$("#btn_submit").click(function() {
			// 対象・非対象チェックボックスの値を送信するための処理
			set_checkbox_value();
			// 更新区分別に実行アクションを分岐させる
			var active_student_id = $("input[name=active_student_id]").val();
			var func = 0;
			if ($('input[name="function"]:checked').val() == 3 ){
				func = 3;
			}
			else {
				func = 5;
				@if($request['id'])
					func = 1;
				@endif
			}
			
			if(func == "1") {
				// 更新
				$("#entry_form").attr("action", "{{$_app_path}}invoice/editconfirm");
				$('<input />').attr('type', 'hidden').attr('name', 'id').attr('value', "{{array_get($request, 'id')}}").appendTo('#entry_form');
				$('<input />').attr('type', 'hidden').attr('name', 'fromedit').attr('value', '1').appendTo('#entry_form');
				$("#entry_form").submit();
				return false;
			} else if (func == "2") {
				// 削除
				$("#entry_form").attr("action", "{{$_app_path}}invoice/delete");
				$('<input />').attr('type', 'hidden').attr('name', 'id').attr('value', "{{array_get($request, 'id')}}").appendTo('#entry_form');
				$('<input />').attr('type', 'hidden').attr('name', 'fromedit').attr('value', '1').appendTo('#entry_form');
				$("#entry_form").submit();
			} else if (func == "3") {
				// 無効
				$("#entry_form").attr("action", "{{$_app_path}}invoice/disabled");
				$('<input />').attr('type', 'hidden').attr('name', 'id').attr('value', "{{array_get($request, 'id')}}").appendTo('#entry_form');
				$('<input />').attr('type', 'hidden').attr('name', 'fromedit').attr('value', '1').appendTo('#entry_form');
				$("#entry_form").submit();
			} else {
				// 新規
				$("#entry_form").attr("action", "{{$_app_path}}invoice/entryconfirm");
			}
		});

		$("#add_discount_row").click(function(){
			var add_row = $("#discount_row_template").find("li").clone(true);
			add_row.find("[name=template_discount_id]").attr("id", "discount"+discount_count);
			add_row.find("[name=template_discount_price]").attr("id", "_discount"+discount_count);
			add_row.find("[name=template_discount_id]").attr("name", "discount_id[]");
			add_row.find("[name=template_discount_name]").attr("name", "discount_name[]");
			add_row.find("[name=template_discount_price]").attr("name", "discount_price[]");
			$("#discount_area").append(add_row);
			discount_count++;
		});

		$("#add_custom_item_row").click(function(){
			var add_row = $("#custom_item_row_template").find("li").clone(true);
			var student_id = $("input[name=active_student_id]").val();
			add_row.find("[name=template_custom_item_id]").attr("id", "adjust" + student_id + "_" + adjust_count);
			add_row.find("[name=template_custom_item_price]").attr("id", "_adjust" + student_id + "_" + adjust_count);
			add_row.find("[name=template_custom_item_id]").attr("name", "custom_item_id[" + student_id + "][]");
			add_row.find("[name=template_custom_item_name]").attr("name", "custom_item_name[" + student_id + "][]");
			add_row.find("[name=template_custom_item_price]").attr("name", "custom_item_price[" + student_id + "][]");
			$("#custom_item_area").append(add_row);
			adjust_count++;
		});

		// 合計金額、割引額合計を求める。
		function set_total_price() {
			var discount_sum = 0;
			$('input[name="discount_price[]"]').each(function(){
				var val = $(this).val();
				if (val != "" && !isNaN(val)) {
					discount_sum += parseInt(val);
				}
			});
			$("#discount_price").text(String(discount_sum).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));

			var price_sum = 0;
			$('input[name*="custom_item_price"], input[name*="class_price"], input[name*="course_price"], input[name*="program_price"]').each(function(){
				var val = $(this).val();
				if (val != "" && !isNaN(val)) {
					price_sum += parseInt(val);
				}
			});

			var _class_except_count = 0;
			$('input[name*="_class_except"]').each(function(){
				_class_except_count++;
				if($(this).val() == 1) {
					var class_price_count = 0;
					$('input[name*="class_price"]').each(function() {
						class_price_count++;
						if(_class_except_count == class_price_count) {
							var val = $(this).val();
							if (val != "" && !isNaN(val)) {
								price_sum -= parseInt(val);
							}
							return false;
						}
					});
            	}
			});
			var _course_except_count = 0;
			$('input[name*="_course_except"]').each(function(){
				_course_except_count++;
				if($(this).val() ==1) {
					var course_price_count = 0;
					$('input[name*="course_price"]').each(function() {
						course_price_count++;
						if(_course_except_count == course_price_count) {
							var val = $(this).val();
							if (val != "" && !isNaN(val)) {
								price_sum -= parseInt(val);
							}
							return false;
						}
					});
            	}
			});
			var _program_except_count = 0;
			$('input[name*="_program_except"]').each(function(){
				_program_except_count++;
				if($(this).val() ==1) {
					var program_price_count = 0;
					$('input[name*="program_price"]').each(function() {
						program_price_count++;
						if(_program_except_count == program_price_count) {
							var val = $(this).val();
							if (val != "" && !isNaN(val)) {
								price_sum -= parseInt(val);
							}
							return false;
						}
					});
            	}
			});

			var amount = price_sum - discount_sum;
			$("[name=amount]").text(String(amount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));

			var tax_price = 0;
			var amount_tax = 0;
			var def_tax = $("input[name=sales_tax_rate]").val();
			if (!def_tax) def_tax = 0;
			var sales_tax_rate = parseFloat(def_tax);
			if ($("input[name=amount_display_type]").val() == "0") {
				tax_price = Math.floor(amount * (sales_tax_rate * 100) / ((sales_tax_rate * 100) + 100));
				amount_tax = amount;
			} else {
				tax_price = Math.floor(amount * sales_tax_rate);
				amount_tax = amount + tax_price;
			}
			$("[name=tax_price]").text(String(tax_price).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));
			$("[name=amount_tax]").text(String(amount_tax).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));
			
			return false;
		}

		var active_student_id = $("input[name=active_student_id]").val();
		$(document).on("change", 'input[name="class_except['+active_student_id+'][]"]', function() {
			set_checkbox_value(active_student_id);
			set_total_price();
		});
		$(document).on("change", 'input[name="course_except['+active_student_id+'][]"]', function() {
			set_checkbox_value(active_student_id);
			set_total_price();
		});

		$(document).on("keyup", 'input[name="discount_price[]"]', function() {
			set_total_price();
		});

		$(document).on("change", 'input[name="discount_price[]"]', function() {
			set_total_price();
		});

		$(document).on("keyup", 'input[name*="custom_item_price"]', function() {
			set_total_price();
		});

		$(document).on("change", 'input[name*="custom_item_price"]', function() {
			set_total_price();
		});

		/* 割引 */
		$(".discount_select").change(function() {
			
			var kinds = $(this).val();
			var price = 0;
			var idxnm = $(this).attr("id");
			
			if (kinds == ""){
			}else if ( kinds > 0){
				// 金額の初期値
				price = discount_valus[kinds];
			}
// 			alert(price);
			// 金額の表示
			$('input[id="_'+idxnm+'"]').val(price);
			// 合計金額の更新
			set_total_price();
			
			return false;
		});
		/* 割引・割増 */
		$(".adjust_select").change(function() {
			var kinds = $(this).val();
			var price = 0;
			var idxnm = $(this).attr("id");
			
			if (kinds == ""){
			}else if (kinds > 0){
				// 金額の初期値
				price = adjust_valus[kinds];
			}

			// 金額の表示
			$('input[id="_'+idxnm+'"]').val(price);
			// 合計金額の更新
			set_total_price();
			
			return false;
		});

		set_total_price();

		$(".invoice_student_tab").click(function() {
			// 対象・非対象チェックボックスの値を送信するための処理
			@if(!empty($is_new))
			$("#entry_form").attr("action", "{{$_app_path}}invoice/entry?tab_change");
			@else 
			$("#entry_form").attr("action", "{{$_app_path}}invoice/edit?tab_change");
			@endif 
			$("[name=active_student_id]").val($(this).find("[name=select_tab_mode]").val());
			$("#entry_form").submit();
		});
		
	});

	function set_checkbox_value(student_id) {
		var class_except_count = 0;
		$('input[name*="class_except['+student_id+']"]').each(function(){
			if (this.type == "checkbox") {
				class_except_count++;
				var box_value = 0;
				if($(this).is(':checked')) {
					box_value = 1;
            	}
				var _class_except_count = 0;
				$('input[name*="_class_except['+student_id+']"]').each(function() {
					_class_except_count++;
					if(class_except_count == _class_except_count) {
						$(this).val(box_value);
						return false;
					}
				});
			}
		});
		var course_except_count = 0;
		$('input[name*="course_except['+student_id+']"]').each(function(){
			if (this.type == "checkbox") {
				course_except_count++;
				var box_value = 0;
				if($(this).is(':checked')) {
					box_value = 1;
            	}
				var _course_except_count = 0;
				$('input[name*="_course_except['+student_id+']"]').each(function() {
					_course_except_count++;
					if(course_except_count == _course_except_count) {
						$(this).val(box_value);
						return false;
					}
				});
			}
		});
	}

	var discount_valus = [];
	@foreach($discount_valus as $key => $item)
		discount_valus[{{$key}}] = {{$item}};
	@endforeach

	var adjust_valus = [];
	@foreach($adjust_valus as $key => $item)
		adjust_valus[{{$key}}] = {{$item}};
	@endforeach
	
	var discount_count = {{count($request['discount_name'])}};
	@if(is_numeric($request['active_student_id']))
	var adjust_count = {{count($request['custom_item_name'][$request['active_student_id']])}};
	@endif
	
</script>

<script type="text/javascript">
$(function() {
	
	var nowInputIndex = 0;

	// 割引・割増設定ボタン
	$('#routine_adjust').click(function(){
		var parent_id = "{{$data['id']}}";

		$("#routine_adjust_dialog").dialog({
			
			 title : "{{$lan::get('discount_premium_setting_per_month_title')}}",
			 width : 650,
			 buttons : {
				"{{$lan::get('register_title')}}" : function() {
					
					// エラーメッセージ削除
					$(".message_area").children().remove();
					
					// データ集約
					var months = "";
					var adjust_ids = "";
					var fees = "";
					var ids = "";
					var data_cnt = 0;

					$("select").each(function(index, element){
 						var elm_id = new String($(element).attr("id"));
 						
 						if( elm_id.indexOf("payment_month_") != -1 ){
 							if( months.length > 0 )	months += ",";
 							months += $("#" + elm_id).val();
 							data_cnt++;
 						}
 						if( elm_id.indexOf("payment_adjust_") != -1 ){
 							if( adjust_ids.length > 0 )	adjust_ids += ",";
 							adjust_ids += $("#" + elm_id).val();
 						}
				    });
					
					$("input").each(function(index, element){
 						var elm_id = new String($(element).attr("id"));
 						
 						if( elm_id.indexOf("payment_fee_") != -1 ){
 							if( fees.length > 0 )	fees += ",";
 							fees += $("#" + elm_id).val();
 						}
 						if( elm_id.indexOf("payment_id_") != -1 ){
 							if( ids.length > 0 )	ids += ",";
 							ids += $("#" + elm_id).val();
 						}
				    });

					// 入力チェック
 					$.get(
 						"{{$_app_path}}ajaxinvoice/checkparentadjust",
 						{parent_id: parent_id,
 						 month:  months,
 						 adjust: adjust_ids,
 						 fee:    fees,
 						 id:     ids,
 						 count:  data_cnt,
 						 },
 						function(c_data)
 						{
 							if( c_data.status == "OK"){

								$.get(
			 	 					"{{$_app_path}}ajaxinvoice/registparentadjust",
			 	 					{parent_id: parent_id,
			 	 					 month:  months,
			 	 					 adjust: adjust_ids,
			 	 					 fee:    fees,
			 	 					 id:     ids},
			 	 					function(r_data)
			 	 					{
			 	 						// 項目削除
			 	 						$("#inputActive").children().remove();
			 	 						$("#inputAdd").unbind('click');
			 	 						nowInputIndex = 0;
		 	 							$("#routine_adjust_dialog").dialog("close");
		 	 						},
		 	 						"jsonp"
		 	 					);
		 						return false;
							} else {
								var msg = c_data.msg;
								// エラーメッセージ表示
								$(".message_area").append("<li class='error_message'>" + msg + "</li>");
							}
						},
						"jsonp"
					);
				},
				"{{$lan::get('cancel_title')}}" : function() {
					// 項目削除
					$("#inputActive").children().remove();
					$("#inputAdd").unbind('click');
					nowInputIndex = 0;
					$(this).dialog("close");
					return false;;
				}
			}
		});
		
		// 情報取得
		$.get(
			"/school/ajaxInvoice/getparentadjust",
			{parent_id: parent_id},
			function(data)
			{
				// 取得した件数分行追加
				for( var idx = 0; idx < data.adjust_list.length; idx++ ){

					// 項目追加
					addItem(nowInputIndex);
					// データ設定
					$("#payment_month_" + nowInputIndex).val(data.adjust_list[idx].month);
					$("#payment_adjust_" + nowInputIndex).val(data.adjust_list[idx].invoice_adjust_name_id);
					$("#payment_fee_" + nowInputIndex).val(data.adjust_list[idx].adjust_fee);
					$("#payment_id_" + nowInputIndex).val(data.adjust_list[idx].id);
					
					nowInputIndex++;
				}
			},
			"jsonp"
		);
		
		// 請求項目追加ボタン
		$('#inputAdd').click(function() {
			addItem(nowInputIndex);
			nowInputIndex++;
		});

	});
});

// 削除
$(function(){
	$( "A.inputDelete").click( function(e){
		var activeTable = $(this).parent(".t4d2");
		e.preventDefault();
		inputDel( activeTable );
		return false;
	});
});

// 項目の追加
function addItem(nowInputIndex){
	
	var newTable = $( "TABLE", "#inputBase" ).clone();//inputBaseのIDのTABLEタグをnewTableへ
	var newHR    = $( "HR"   , "#inputBase" ).clone();//inputBaseのIDのHRタグをnewHRへ

	$( ".formItem", newTable ).each( function(){//newTable内のformItemプラン指定のそれれぞれで
		var title = $( this ).attr( 'title' );//title属性の内容を変数titleへ
		$( this ).attr( 'name', 'payment[' + nowInputIndex + '][' +  title + ']');//name属性の内容をinput[nowInputIndex][title]へ
		$( this ).removeAttr( 'title' );//title属性を削除する

	});

	$( ".NewPaymentMonth",newTable).attr( 'id', 'payment_month_' + nowInputIndex  ).removeClass('NewPaymentMonth');//newTable内のNewDateInputプラン指定でid属性をDateInput_＋nowInputIndexへ、同時にNewDateInputプランを削除する
	$( ".NewPaymentAdjust",newTable).attr( 'id', 'payment_adjust_' + nowInputIndex  ).removeClass('NewPaymentAdjust');//newTable内のNewex_fromTimeプラン指定でid属性をex_fromTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
	$( ".NewPaymentFee",newTable).attr( 'id', 'payment_fee_' + nowInputIndex  ).removeClass('NewPaymentFee');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
	$( ".NewPaymentId",newTable).attr( 'id', 'payment_id_' + nowInputIndex  ).removeClass('NewPaymentId');//newTable内のNewex_toTimeプラン指定でid属性をex_toTime_＋nowInputIndexへ、同時にNewDateInputプランを削除する
	
	$( "#inputActive" ).append( newTable );//inputActiveのID指定にnewTableの内容を追加する
	$( "#inputActive" ).append( newHR    );//inputActiveのID指定にnewHRの内容を追加する

	// 削除処理設定
	$( "A.inputDelete", newTable ).click( function(e){
		e.preventDefault();
		inputDel( newTable );
		return false;
	});

	// 摘要の初期値取得
	$( "#payment_adjust_" + nowInputIndex, newTable ).change( function(e){
		
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
				$("#payment_fee_" + no).val(v_data);
			},
			"jsonp"
		);
		return false;
	});
	
	// 表示
	$( newTable ).show();

	return false;
}

// 削除
function inputDel( item ){
	$( item ).next().remove();
	$( item ).remove();
	return false;
}
</script>

<!-- ENTERキーが入力されたら、次のフィールドへフォーカスを移動  -->
<script type="text/javascript">
function nextForm(event)
{
	if (event.keyCode == 0x0d)
	{
		var current = document.activeElement;

		var forcus = 0;
		for( var idx = 0; idx < document.entry_form.elements.length; idx++){
			if( document.entry_form[idx] == current ){
				forcus = idx;
				break;
			}
		}
		document.entry_form[(forcus + 1)].focus();
	}
}
window.document.onkeydown = nextForm;
</script>

@include('_parts.invoice.axis_menu')

	{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	{!!	Breadcrumbs::render('invoice_entry') !!}</div> --}}
@include('_parts.topic_list')
		<h3 id="content_h3" class="box_border1">{{$lan::get('detailed_information_title')}}
			@if($request->exists('id'))
				{{$lan::get('edit_title')}}
			@else
				{{$lan::get('create_title')}}
			@endif</h3>

		<div id="section_content">
		
			@if((isset($is_new) and isset($is_invoice_exist)) or isset($request['errors']['amount']['priceZero']))
			<div class="alart_box box_shadow">
			@if($is_new and $is_invoice_exist)
				<ul class="message_area">
					<li class="info_message">{{$lan::get('invoice_current_month_title')}}</li>
				</ul>
			@endif
			
			@if(isset($request['errors']['amount']['priceZero']))
				<ul class="message_area">
					<li class="error_message">{{$lan::get('invoiced_amount_below_zero_yen_title')}}</li>
				</ul>
			@endif
			
			</div>
			@endif
		
			<form action="#" name="entry_form" method="post" class="search_form" id="entry_form">
			{{ csrf_field() }}
				<input type="hidden" name="id" value="{{array_get($request, 'id')}}"/>
				<input type="hidden" name="parent_id" value="{{array_get($request, 'parent_id')}}"/>
				<input type="hidden" name="invoice_year_month" value="{{array_get($request, 'invoice_year_month')}}"/>
				<input type="hidden" name="active_student_id" value="{{array_get($request, 'active_student_id')}}"/>
				<input type="hidden" name="sales_tax_rate" value="{{array_get($request, 'sales_tax_rate')}}"/>
				<input type="hidden" name="amount_display_type" value="{{array_get($request, 'amount_display_type')}}"/>
				<input type="hidden" name="invoice_due_year" value="{{array_get($request, 'invoice_due_year')}}"/>
				<input type="hidden" name="invoice_due_month" value="{{array_get($request, 'invoice_due_month')}}"/>
				<input type="hidden" name="invoice_due_day" value="{{array_get($request, 'invoice_due_day')}}"/>
				<input type="hidden" name="tbl_is_established" value="{{array_get($request, 'tbl_is_established')}}"/>
				
				<input type="hidden" name="parent_memo" value="{{array_get($request, 'parent_memo')}}"/>
				<input type="hidden" name="due_date" value="{{array_get($request, 'due_date')}}"/>
				<input type="hidden" name="now_date_jp" value="{{array_get($request, 'now_date_jp')}}"/>
				<input type="hidden" name="due_date_jp" value="{{array_get($request, 'due_date_jp')}}"/>
				<input type="hidden" name="sales_tax_disp" value="{{array_get($request, 'sales_tax_disp')}}"/>
				<input type="hidden" name="pbank_info" value="{{array_get($request, 'pbank_info')}}"/>
	
				@if($request['id'])
					<input type="hidden" name="function" value="1"/>	
<!-- 					実行区分「更新」 -->
				@else
					<input type="hidden" name="function" value="5"/>	
<!-- 					実行区分「新規」  -->
				@endif

				<div id="center_content_main">
					<!-- include axis_tab -->
					@include('_parts.invoice.axis_tab')
				</div>
			
				<div class="content_detail">
					@php
						$width_percent = 100 / (count(array_get($data, 'student_list')) + 1);
						$tab_color_list = array("powderblue", "lightyellow", "yellowgreen");
						$active_tab_color = "";
						
					@endphp
					<div class="clearfix" style="display:none">
					
						@foreach (array_get($data, 'student_list') as $student_row)
							@php
								$tab_color = $tab_color_list[$loop->iteration % count($tab_color_list)];
							@endphp
							<div style="width:{{$lan::get('width_percent')}}%;background-color:{{$lan::get('tab_color')}};" class="invoice_student_tab">
								<input type="hidden" name="select_tab_mode" value="{{array_get($student_row, 'id')}}"/>
								{{array_get($student_row, 'student_name')}}
							</div>
							@if(array_get($student_row, 'id') == array_get($request, 'active_student_id'))
								@php
									$active_tab_color = $tab_color;
								@endphp
							@endif
						@endforeach
						<div style="width:{{$lan::get('width_percent')}}%;background-color:bisque;" class="invoice_student_tab">
							<input type="hidden" name="select_tab_mode" value="{{array_get($student_row, 'id')}}"/>
							{{$lan::get('confirm_title')}}
						</div>
					</div>

					<div style="display:none">
					@if(array_get($request, 'class_name'))
						@foreach (array_get($request, 'class_name') as $student_id => $name_list)
							@foreach ($name_list as $k => $name)
								<input type="hidden" name="class_name[{{$student_id}}][]" value="{{$name}}"/>
								<input type="hidden" name="class_id[{{$student_id}}][]" value="{{$request['class_id'][$student_id][$k]}}"/>
								<input type="hidden" name="class_price[{{$student_id}}][]" value="{{$request['class_price'][$student_id][$k]}}"/>
								<input type="hidden" name="_class_except[{{$student_id}}][]" value="{{$request['_class_except'][$student_id][$k]}}"/>
							@endforeach
						@endforeach
					@endif
					@if(array_get($request, 'course_name'))
						@foreach (array_get($request, 'course_name') as $student_id => $name_list)
							@foreach ($name_list as $k => $name)
								<input type="hidden" name="course_name[{{$student_id}}][]" value="{{$name}}"/>
								<input type="hidden" name="course_id[{{$student_id}}][]" value="{{$request['course_id'][$student_id][$k]}}"/>
								<input type="hidden" name="course_price[{{$student_id}}][]" value="{{$request['course_price'][$student_id][$k]}}"/>
								<input type="hidden" name="_course_except[{{$student_id}}][]" value="{{$request['_course_except'][$student_id][$k]}}"/>
							@endforeach
						@endforeach
					@endif
					@if(array_get($request, 'program_name'))
						@foreach (array_get($request, 'program_name') as $student_id => $name_list)
							@foreach ($name_list as $k => $name)
								<input type="hidden" name="program_name[{{$student_id}}][]" value="{{$name}}"/>
								<input type="hidden" name="program_id[{{$student_id}}][]" value="{{$request['program_id'][$student_id][$k]}}"/>
								<input type="hidden" name="program_price[{{$student_id}}][]" value="{{$request['program_price'][$student_id][$k]}}"/>
								<input type="hidden" name="_program_except[{{$student_id}}][]" value="{{$request['_program_except'][$student_id][$k]}}"/>
							@endforeach
						@endforeach
					@endif
					</div>
				</div>

				<br/>
				<div id="section_content_in">
						<input class="btn_green" id="btn_submit" type="submit" name="confirm_button" value="{{$lan::get('confirm_title')}}" />
				</div> <!-- section_content_in -->
			</form>

<!-- 		月別の割増・割引設定  -->
		<div id="routine_adjust_dialog"  style="display:none;">

			<div id="inputActive" >
				<div >
					<ul class="message_area">
					</ul>
				</div>
			</div>
		
			<div id="inputBase" style="display:none;">
				<table id="table4" style="width:100%;">
					<tr>
						<td class="t4d2">
							{{$lan::get('target_month_title')}}
							<select  class="formItem NewPaymentMonth" title="payment_month">
								<option value="" ></option>
								@foreach ($month_listEx as $key => $row)
									<option value="{{$key}}">{{$row}}</option>
								@endforeach
							</select>
								
							&nbsp;{{$lan::get('outline_title')}}
							<select  class="formItem NewPaymentAdjust" title="payment_adjust">
								<option value="" ></option>
								@foreach ($invoice_adjust_list as $row)
									<option value="{{array_get($row, 'id')}}">{{array_get($row, 'name')}}</option>
								@endforeach
							</select>
							&nbsp;{{$lan::get('amount_of_money_title')}}
							<input  type="text"  class="formItem NewPaymentFee" style="ime-mode:inactive; width:80px; text-align:right;" value=""  title="payment_fee"/>&nbsp;{{$lan::get('yen_title')}}
							<input  type="hidden"  class="formItem NewPaymentId" value=""  title="payment_id"/>
							<a class="inputDelete" href="#"><input type="button" value="{{$lan::get('delete_title')}}"/></a>
						</td>
					</tr>
				</table>
				<hr class="line">
			</div>
					
			<div style="margin:10px 10px 17px 120px;">
				<button id="inputAdd" style="width:100px">+{{$lan::get('add_item_title')}}</button>
			</div>
			
		</div>

		
@stop

