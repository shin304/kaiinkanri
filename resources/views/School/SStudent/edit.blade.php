@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
$(function() {
	//datepicker追加
	var d = new Date();
	$(".DateInput").datepicker({
		 showOn: 'both',
		 dateFormat: 'yy-mm-dd',
		 changeMonth: true,
		 changeYear: true,
		 monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		 monthNamesShort: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		 dayNames: ['日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'],
 		 dayNamesShort: ['日','月','火','水','木','金','土'],
		 dayNamesMin: ['日','月','火','水','木','金','土'],
		 yearRange: '2000:'+(d.getYear()+1910),
		 prevText: '&#x3c;前', prevStatus: '前月を表示します',
		 prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '前年を表示します',
		 nextText: '次&#x3e;', nextStatus: '翌月を表示します',
		 nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '翌年を表示します',
		 currentText: '今日', currentStatus: '今月を表示します',
		 todayText: '今日', todayStatus: '今月を表示します',
		 clearText: 'クリア', clearStatus: '日付をクリアします',
		 closeText: '閉じる', closeStatus: '変更せずに閉じます'
	});
	/* 会員住所の都道府県 */
	$("#s_address_pref").change(function() {
		var pref_cd = $(this).val();
		if (pref_cd == "") {
			$("#s_address_city option").remove();
			$("#s_address_city").prepend($("<option>").html("").val(""));
			$("#s_selectaddress_city").text("");
			return;
		}
		{{-- $.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#s_address_city option").remove();
				for(var key in data.city_list){
					$("#s_address_city").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#s_address_city").prepend($("<option>").html("").val(""));
				$("#s_address_city").val("");
				$("#s_selectaddress_city").text("");
			},
			"jsonp"
		);--}}
		$.ajax({

            type:"get",
            dataType:"json",
            url: "/school/ajaxSchool/city",
            data: {pref_cd: pref_cd},
            contentType: "application/x-www-form-urlencoded",
            success: function(data) {
            	data = JSON.stringify(data);
            	data = JSON.parse(data);
            	
            	var result = data['city_list'];
            	var html = "<option value=''></option>";
            	for( x in result) {
            		var html =  html + "<option value=" + x +">" + result[x] +"</option>";
            	}
            		
            	$('#s_address_city').html(html);

                
            },
            error: function(data) {
            	console.log(data);
            },
        });

	});
	/* 会員受験地域１の都道府県 */
	$("#exam_pref1").change(function() {
		var pref_cd = $(this).val();
		if (pref_cd == "") {
			$("#exam_city1 option").remove();
			$("#exam_city1").prepend($("<option>").html("").val(""));
			$("#selectexam_city1").text("");
			return;
		}
		$.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#exam_city1 option").remove();
				for(var key in data.city_list){
					$("#exam_city1").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#exam_city1").prepend($("<option>").html("").val(""));
				$("#exam_city1").val("");
				$("#selectexam_city1").text("");
			},
			"jsonp"
		);
	});
	/* 会員受験地域２の都道府県 */
	$("#exam_pref2").change(function() {
			var pref_cd = $(this).val();
			if (pref_cd == "") {
				$("#exam_city2 option").remove();
				$("#exam_city2").prepend($("<option>").html("").val(""));
				$("#selectexam_city2").text("");
				return;
			}
		$.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#exam_city2 option").remove();
				for(var key in data.city_list){
					$("#exam_city2").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#exam_city2").prepend($("<option>").html("").val(""));
				$("#exam_city2").val("");
				$("#selectexam_city2").text("");
			},
			"jsonp"
		);
	});
	/* 会員受験地域３の都道府県 */
	$("#exam_pref3").change(function() {
			var pref_cd = $(this).val();
			if (pref_cd == "") {
				$("#exam_city3 option").remove();
				$("#exam_city3").prepend($("<option>").html("").val(""));
				$("#selectexam_city3").text("");
				return;
			}
		$.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#exam_city3 option").remove();
				for(var key in data.city_list){
					$("#exam_city3").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#exam_city3").prepend($("<option>").html("").val(""));
				$("#exam_city3").val("");
				$("#selectexam_city3").text("");
			},
			"jsonp"
		);
	});
	$("#btn_add_parent").click(function() {
		$("#action_form").attr('action', "{{$_app_path}}parent/list2");
		$("#action_form").submit();
		return false;
	});
	$("#btn_edit_parent").click(function() {
		$("#action_form").attr('action', "{{$_app_path}}parent/entry");
		$("#action_form").submit();
		return false;
	});

	$(".submit3").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}student/detail');
		@if ($request['parent_id'])
		$("#action_form").attr('action', '{{$_app_path}}parent/list2');
		@else
		$("#action_form").attr('action', '{{$_app_path}}student/detail');
		@endif
		$("#action_form").submit();
		return false;
	});
	$(".submit2").click(function() {
// 		$("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
// 		$("#action_form").submit();
// 		return false;

		$( "#dialog_active" ).dialog('open');
		return false;
	});
	
	$( "#dialog_active" ).dialog({
		title: '{{$lan::get('main_title')}}',
		autoOpen: false,
		dialogClass: "no-close",
		resizable: false,
		modal: true,
		buttons: {
			"{{$lan::get('run_title')}}": function() {
				$( this ).dialog( "close" );
				$("#action_form").attr('action', '{{ URL::to('/school/student/complete') }}');
		 		$("#action_form").submit();
				return false;
			},
			"{{$lan::get('cancel_title')}}": function() {
				$( this ).dialog( "close" );
				return false;
			}
		}
	});


	
	$("#btn_reset_parent").click(function() {
		$("input[name='select_parent']").val("");

		$(".select_parent").remove();
		$("input[name='parent_id']").val("");

		$(".input_parent").appendTo("#parent_area");
		$(this).remove();

		return false;
	});
	$("#btn_copy").click(function() {
		var name = $('#action_form [name=student_name]').val();
		$("#action_form [name='parent_name']").val(name);
		var kana = $('#action_form [name=student_kana]').val();
		$("#action_form [name='name_kana']").val(kana);
		var mail = $('#action_form [name=student_mail]').val();
		$("#action_form [name='parent_mailaddress1']").val(mail);
		var zip1 = $("#action_form :text[name='student_zip_code1']").val();
		$("#action_form :text[name='zip_code1']").val(zip1);
		var zip2 = $('#action_form :text[name=student_zip_code2]').val();
		$("#action_form :text[name=zip_code2]").val(zip2);
		var pref = $('#action_form select[name=_pref_id]').val();
		$("#action_form select[name='pref_id']").val(pref).change();
		$.get(
				"{{$_app_path}}ajaxSchool/city",
				{pref_cd: pref},
				function(data) {
					/* 市区町村 */
					for(var key in data.city_list){
						$("#s_address_city").append($("<option>").html(data.city_list[key]).val(key));
					}
					var city = $('#action_form select[name=_city_id]').val();
					$("#action_form [name='city_id']").val(city);
				    var selectCity = $("#action_form select[name=_city_id] option:selected").text();
				    $("#action_form select[name=city_id] option:selected").text(selectCity);
				},
				"jsonp"
			);
		var handset = $('#action_form :text[name=student_handset_no]').val();
		$("#action_form :text[name='handset_no']").val(handset);
		var phone = $('#action_form :text[name=student_phone_no]').val();
		$("#action_form :text[name='phone_no']").val(phone);
		var address = $('#action_form :text[name=student_address]').val();
		$("#action_form :text[name='address']").val(address);
		return false;
	});
});
</script>

<script type="text/javascript">
function nextForm(event)
{
	if (event.keyCode == 0x0d)
	{
		var current = document.activeElement;

		var forcus = 0;
		for( var idx = 0; idx < document.action_form.elements.length; idx++){
			if( document.action_form[idx] == current ){
				forcus = idx;
				break;
			}
		}
		document.action_form[(forcus + 1)].focus();
	}
}
window.document.onkeydown = nextForm;
</script>


<script type="text/javascript">
//$(function() {
//	/* 帯色 */
//	$("#grade_id").change(function() {
//		$("#gain_date").val("");
//	});
//});

/**
 * 行を削除
 */
function removeRow(obj, id) {
//	if (confirm('削除してもよろしいですか？')) {
		// 行削除
		$(obj).closest("tr").remove()
		// ＤＢに登録されていない場合、ここまで
		if(id) {
			// 削除リストへ追加
			var ids = $("#action_form input[name='_grades_remove_ids']").val();
			if(ids) {
				ids += ","+id;
			} else {
				ids = id;
			}
			$("#action_form input[name='_grades_remove_ids']").val(ids);
		}

		return false;
//	}
}
</script>

<script type="text/javascript">
$(function(){
	var setFileInput = $('.imgInput'),
	setFileImg = $('.imgView');

	setFileInput.each(function(){
		var selfFile = $(this),
		selfInput = $(this).find('input[type=file]'),
		prevElm = selfFile.find(setFileImg),
		orgPass = prevElm.attr('src');

		selfInput.change(function(){
			var file = $(this).prop('files')[0],
			fileRdr = new FileReader();

			if (!this.files.length){
				prevElm.attr('src', orgPass);
				return;
			} else {
				if (!file.type.match('image.*')){
					prevElm.attr('src', orgPass);
					return;
				} else {
					fileRdr.onload = function() {
						prevElm.attr('src', fileRdr.result);
					}
					fileRdr.readAsDataURL(file);
				}
			}
		});
	});
});
</script>

<script type="text/javascript">
var	$bInit = true;

$(function() {
	/* 生徒住所の都道府県 */
	$("#address_pref").change(function() {
		var pref_cd = $(this).val();
		if (pref_cd == "") {
			$("#address_city option").remove();
			$("#address_city").prepend($("<option>").html("").val(""));
			$("#selectaddress_city").text("");
			return;
		}
		{{-- $.get(
			"{{$_app_path}}ajaxSchool/city",
			{pref_cd: pref_cd},
			function(data) {
				/* 市区町村 */
				$("#address_city option").remove();
				for(var key in data.city_list){
					$("#address_city").append($("<option>").html(data.city_list[key]).val(key));
				}
				$("#address_city").prepend($("<option>").html("").val(""));
				$("#address_city").val("");
				$("#selectaddress_city").text("");
			},
			"jsonp"
		); --}}
		$.ajax({
            type:"get",
            dataType:"json",
            url: "/school/ajaxSchool/city",
            data: {pref_cd: pref_cd},
            contentType: "application/x-www-form-urlencoded",
            success: function(data) {
            	data = JSON.stringify(data);
            	data = JSON.parse(data);
            	
            	var result = data['city_list'];console.log(result);
            	var html = "<option value=''></option>";
            	for( x in result) {
            		var html =  html + "<option value=" + x +">" + result[x] +"</option>";
            	}
            		
            	$('#address_city').html(html);

                
            },
            error: function(data) {alert('error');
            	console.log(data);
            },
        });
	});
	$(".submit3").click(function() {
		@if( $request['orgparent_id'])
			$("#entry_form").attr('action',	'{{$_app_path}}parent/detail');
		@else
			$("#entry_form").attr('action',	'{{$_app_path}}parent');
		@endif
		$("#entry_form").submit();
		return false;
	});
	$(".submit2").click(function() {
		$("#entry_form").attr('action',
				'{{$_app_path}}parent/confirm');
		$("#entry_form").submit();
		return false;
	});
	$("#invoicetype").change(function(){
		var type = $("#invoicetype").val();
		if( type != 2 ){
			$("#mailinfo").val("1");
			$("#invoiceinfo").hide();
			$("#bankinfo").hide();
			$("#postinfo").hide();
		}
		else {
			$("#mailinfo").val("0");
			$("#invoiceinfo").show();
			@if( $request['bank_type'] == 2)
				$("#bankinfo").hide();
				$("#postinfo").show();
			@elseif ($request['bank_type'] == 1)
				$("#postinfo").hide();
				$("#bankinfo").show();
			@else
				$("#postinfo").hide();
				$("#bankinfo").show();
			@endif
		}
	});
	$("#banktype").change(function(){
		var type = $("#banktype").val();
		if( type == 1 ){			// 銀行
			$("#postinfo").hide();
			$("#bankinfo").show();
		}
		else if( type == 2 ){
			$("#bankinfo").hide();
			$("#postinfo").show();
		}
	});

	$("input[name='parent_mailaddress2']").change(function(){
		if( $bInit ){
			var strMail = "{{$request->parent_mailaddress2}}";
			if( strMail.length < 1 ){
				$(this).val("");
			}
		}
		$bInit = false;
	});

});




$(function(){
	$( "A.inputDelete").click( function(e){
		var activeTable = $(this).parent(".t4d2");
		e.preventDefault();
		inputDel( activeTable );
		return false;
	});
});

$(function() {

	// 受講料以外追加
	$("#inputAdd").click(function(){

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
//		$( "#payment_adjust_" + nowInputIndex, newTable ).change( function(e){
//			var aaa = $(this).val();
//		});

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

		nowInputIndex++;

		return false;
	});
});

// 削除
function inputDel( item ){
//	$( item ).next().remove();
	$( item ).remove();
	return false;
}

@if ($request['payment'])
	var nowInputIndex =  {{ count($request->payment) }};
@else
	var nowInputIndex =  0;
@endif


</script>

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
<script type="text/javascript">

$(function() {
	// 修正する場合はParent/top.htmlも同様に修正してください //
	/* 学年 */
	$("#school_kinds").change(setGrade);
	window.onload = setGrade('{{$request->school_year}}');

	function setGrade(grade) {
		var kinds = $("#school_kinds").val();
		
		if (kinds == "") {
			// 設定なし
			$("#school_grade option").remove();
			$("#school_grade").prepend($("<option>").html("").val(""));
		}
		else if (kinds < 10) {
			// 学年の最大値
			var years = 3;
			if (kinds == 0){
				years = 6;
			}else if (kinds == 3) {
				years = 8;
			}
			// optionの設定
			$("#school_grade option").remove();
			for(var key = 1; key <= years; key++){
				$("#school_grade").append($("<option>").html(key+"年").val(key));
			}
			$("#school_grade").prepend($("<option>").html("").val(""));
			$("#school_grade").val(grade);
		}
		else {
			// 学生ではない
			$("#school_grade option").remove();
			$("#school_grade").prepend($("<option>").html("").val(""));
		}
		
		return false;
	}
	
});
</script>

<style type="text/css">
img.h120 {
	width: auto;
	max-height: 120px;
}
</style>
<div id="center_content_header" class="box_border1">
	<h2 class="float_left"><i class="fa fa-group"></i> {{$lan::get('main_title')}}</h2>
	<div class="center_content_header_right">
		<div class="top_btn"></div>
	</div>
	<div class="clr"></div>
</div>
<!--center_content_header-->

{{-- <div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">{!!
	Breadcrumbs::render('student_edit') !!}</div>  --}}
@include('_parts.topic_list')
<div id="section_content">
	<h3 id="content_h3" class="box_border1">{{$lan::get('detailed_information_title')}}
		@if ($request->id) {{$lan::get('edit_title')}} @else
		{{$lan::get('register_title')}} @endif</h3>

	<div id="section_content_in">
			<ul class="message_area">@if (session()->get('success'))
				<li class="alert alert-success" role="alert" style="color: blue;">
					{{session()->pull('success')}}</li> @endif 
			</ul>
		<div id="section_content_in">
			@if($request->errors)
			<ul class="message_area">
				@if(isset($request->errors['student_name']['notEmpty']))
				<li class="error_message">{{$lan::get('member_name_required_title')}}</li>@endif
				@if(isset($request->errors['parent_pass']['notEmpty']))
			<li class="error_message">{{$lan::get('parent_pass_required_title')}}</li>@endif
				@if(isset($request->errors['student_type']['notEmpty']))
				<li class="error_message">{{$lan::get('member_type_required_title')}}</li>@endif
				@if(isset($request->errors['student_name']['notEmpty']))
				<li class="error_message">{{$lan::get('member_name_required_title')}}</li>@endif
				@if(isset($request->errors['student_name']['overLength']))
				<li class="error_message">{{$lan::get('member_name_maximum_255_characters_title')}}</li>@endif
				@if(isset($request->errors['student_name']['notDouble']))
				<li class="error_message">{{$lan::get('member_name_full_width_characters_title')}}</li>@endif
				@if(isset($request->errors['student_kana']['notEmpty']))
				<li class="error_message">{{$lan::get('members_furigana_required_title')}}</li>@endif
				@if(isset($request->errors['student_kana']['overLength']))
				<li class="error_message">{{$lan::get('member_furigana_maximum_255_characters_title')}}</li>@endif
				@if(isset($request->errors['student_kana']['isZenkakukana']))
				<li class="error_message">{{$lan::get('member_furigana_full_width_kana_characters_title')}}</li>@endif
				@if(isset($request->errors['student_nickname']['notEmpty']))
				<li class="error_message">{{$lan::get('member_nickname_required_title')}}</li>@endif
				@if(isset($request->errors['student_mail']['notEmpty']))
				<li class="error_message">{{$lan::get('member_email_address_required_title')}}</li>@endif
				@if(isset($request->errors['student_mail']['overLength']))
				<li class="error_message">{{$lan::get('member_email_address_maximum_64_characters_title')}}</li>@endif
				@if(isset($request->errors['student_mail']['mailAddress']))
				<li class="error_message">{{$lan::get('format_member_email_address_invalid_title')}}</li>@endif
				@if(isset($request->errors['student_mail']['isExist']))
				<li class="error_message">{{$lan::get('member_email_address_already_used_title')}}</li>@endif
				@if(isset($request->errors['student_pass1']['underLength']))
				<li class="error_message">{{$lan::get('member_password_minimum_8_digits_title')}}</li>@endif
				@if(isset($request->errors['student_pass1']['isAlNumSim']))
				<li class="error_message">{{$lan::get('member_password_alphanumeric_symbols_title')}}</li>@endif
				@if(isset($request->errors['student_pass2']['unMatch']))
				<li class="error_message">{{$lan::get('member_password_and_password_confirm_not_match_title')}}</li>@endif
				@if(isset($request->errors['student_birth_year']['notEmpty']))
				<li class="error_message">{{$lan::get('member_birthday_year_required_title')}}</li>@endif
				@if(isset($request->errors['student_birth_month']['notEmpty']))
				<li class="error_message">{{$lan::get('member_birthday_month_required_title')}}</li>@endif
				@if(isset($request->errors['student_birth_day']['notEmpty']))
				<li class="error_message">{{$lan::get('member_birthday_day_required_title')}}</li>@endif
				@if(isset($request->errors['school_name']['notEmpty']))
				<li class="error_message">{{$lan::get('member_school_name_required_title')}}</li>@endif
				@if(isset($request->errors['school_name']['overLength']))
				<li class="error_message">{{$lan::get('member_school_name_maximum_255_characters_title')}}</li>@endif
				@if(isset($request->errors['school_category']['notEmpty']))
				<li class="error_message">{{$lan::get('member_section_required_title')}}</li>@endif
				@if(isset($request->errors['school_year']['notEmpty']))
				<li class="error_message">{{$lan::get('both_members_section_title')}}</li>@endif
@if(isset($request->errors['address']['notEmpty']))
			<li class="error_message">{{$lan::get('address_empty')}}</li>@endif
				@include('_parts.student.hidden')

				@if(isset($request->errors['exam_pref1']['notEmpty']))
				<li class="error_message">{{$lan::get('state_name_exam_region_1_required_title')}}</li>@endif
				@if(isset($request->errors['exam_city1']['notEmpty']))
				<li class="error_message">{{$lan::get('city_name_exam_region_1_title')}}</li>@endif
				@if(isset($request->errors['exam_city2']['notEmpty']))
				<li class="error_message">{{$lan::get('city_name_exam_region_2_title')}}</li>@endif
				@if(isset($request->errors['exam_city3']['notEmpty']))
				<li class="error_message">{{$lan::get('city_name_exam_region_3_title')}}</li>@endif
				@if(isset($request->errors['parent_id']['notEmpty']))
				<li class="error_message">{{$lan::get('billing_information_required_title')}}</li>@endif

				@if(isset($request->errors['student_img']['notEmpty']))
				<li class="error_message">{{$lan::get('failed_to_upload_title')}}</li>@endif

				@if(isset($request->errors['student_address']['notEmpty']))
				<li class="error_message">{{$lan::get('student_address_empty')}}</li>@endif
				@if(isset($request->errors['student_phone_no']['notEmpty']))
				<li class="error_message">{{$lan::get('student_phone_no_empty')}}</li>@endif

				@if( isset($request->errors['payment_month']['notEmpty']))
						<li class="error_message">{{$lan::get('require_target_month')}}</li>
					@endif
					@if( isset($request->errors['payment_adjust']['notEmpty']))
						<li class="error_message">{{$lan::get('require_abstract')}}</li>
					@endif

					@if( isset($request->errors['payment_fee']['notEmpty']))
						<li class="error_message">{{$lan::get('require_amount_of_money')}}</li>
					@endif
					@if( isset($request->errors['payment_fee']['notNumeric']))
						<li class="error_message">{{$lan::get('amount_money_numeric')}}</li>
					@endif
					@if( isset($request->errors['payment_fee']['Mean']))
						<li class="error_message">{{$lan::get('target_month_there_same_thing')}}</li>
					@endif
							@if( isset($request->errors['bank_code']['notEmpty']))<li class="error_message">金融機関コードは必須です。</li>@endif
				@if( isset($request->errors['bank_code']['notEmpty']))<li class="error_message">{{$lan::get('require_financial_institutions_code')}}</li>@endif
				@if( isset($request->errors['bank_code']['isDigit']))<li class="error_message">{{$lan::get('financial_institutions_code_alphanumeric_character')}}</li>@endif
				@if( isset($request->errors['bank_code']['overLength']))<li class="error_message">{{$lan::get('please_enter_more_than_four_character_financial_institution_code')}}</li>@endif
				@if( isset($request->errors['bank_name']['notEmpty']))<li class="error_message">{{$lan::get('mandatory_financial_institution_name')}}</li>@endif
				@if( isset($request->errors['bank_name']['isHankaku']))<li class="error_message">{{$lan::get('financial_institution_name_alphanumeric_kana')}}</li>@endif
				@if( isset($request->errors['bank_name']['overLength']))<li class="error_message">{{$lan::get('financial_institution_name_255')}}</li>@endif
				@if( isset($request->errors['branch_code']['notEmpty']))<li class="error_message">{{$lan::get('require_branch_code')}}</li>@endif
				@if( isset($request->errors['branch_code']['isDigit']))<li class="error_message">{{$lan::get('branch_code_alphanumeric')}}</li>@endif
				@if( isset($request->errors['branch_code']['overLength']))<li class="error_message">{{$lan::get('within_three_character_branch_code')}}</li>@endif
				@if( isset($request->errors['branch_name']['notEmpty']))<li class="error_message">{{$lan::get('require_branch_name')}}</li>@endif
				@if( isset($request->errors['branch_name']['isHankaku']))<li class="error_message">{{$lan::get('branch_name_alphanumeric_kana')}}</li>@endif
				@if( isset($request->errors['branch_name']['overLength']))<li class="error_message">{{$lan::get('branch_name_255')}}/li>@endif
				@if( isset($request->errors['bank_account_type']['notEmpty']))<li class="error_message">{{$lan::get('require_financial_institution_type')}}</li>@endif
				@if( isset($request->errors['bank_account_number']['notEmpty']))<li class="error_message">{{$lan::get('require_account_number')}}</li>@endif
				@if( isset($request->errors['bank_account_number']['isDigit']))<li class="error_message">{{$lan::get('account_number_entered_number')}}</li>@endif
				@if( isset($request->errors['bank_account_number']['overLength']))<li class="error_message">{{$lan::get('account_number_up_7_character')}}</li>@endif
				@if( isset($request->errors['post_account_kigou']['notEmpty']))<li class="error_message">{{$lan::get('mandatory_passbook_symbol')}}</li>@endif
				@if( isset($request->errors['post_account_kigou']['isDigit']))<li class="error_message">{{$lan::get('number_passbook_symbol')}}</li>@endif
				@if( isset($request->errors['post_account_kigou']['overLength']))<li class="error_message">{{$lan::get('within_5_character_passbook_symbol')}}</li>@endif
				@if( isset($request->errors['post_account_numbe']['notEmpty']))<li class="error_message">{{$lan::get('require_passbook_number')}}</li>@endif
				@if( isset($request->errors['post_account_number']['isDigit']))<li class="error_message">{{$lan::get('passbook_number_entered_number')}}</li>@endif
				@if( isset($request->errors['post_account_number']['overLength']))<li class="error_message">{{$lan::get('passbook_number_up_8_character')}}</li>@endif
				@if( isset($request->errors['bank_account_name']['notEmpty']))<li class="error_message">{{$lan::get('account_holder_require')}}</li>@endif
				@if( isset($request->errors['bank_account_name']['isHankaku']))<li class="error_message">{{$lan::get('account_holder_entered_alphanumeric_kana')}}</li>@endif
				@if( isset($request->errors['bank_account_name']['overLength']))<li class="error_message">{{$lan::get('account_holder_up_30_character')}}</li>@endif
				@if( isset($request->errors['bank_account_name_kana']['notEmpty']))<li class="error_message">{{$lan::get('require_account_name_kana')}}</li>@endif
				@if( isset($request->errors['bank_account_name_kana']['overLength']))<li class="error_message">{{$lan::get('account_name_kana_up_255_character')}}</li>@endif
				@if( isset($request->errors['post_account_name']['notEmpty']))<li class="error_message">{{$lan::get('require_passbook_name')}}</li>@endif
				@if( isset($request->errors['post_account_name']['isHankaku']))<li class="error_message">{{$lan::get('passbook_name_entered_alphanumeric_kana')}}</li>@endif
				@if( isset($request->errors['post_account_name']['overLength']))<li class="error_message">{{$lan::get('passbook_name_up_30_character')}}</li>@endif

				{{-- Validate image --}}
				{{-- @if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
				@endif  --}}
				<br />
			</ul>
			@endif <span class="aster">&lowast;</span>{{$lan::get('items_mandatory_title')}}<br />
			<form id="action_form" name="action_form" method=post
				enctype="multipart/form-data">
				{{ csrf_field() }} @include('_parts.student.hidden')

				<h4>{{$lan::get('member_title')}}</h4>
				<table id="table6">
					<colgroup>
						<col width="30%" />
						<col width="70%" />
					</colgroup>
					<tr>
						<td class="t6_td1">{{$lan::get('image_title')}}<span class="aster"></span></td>
						<td class="t4td2">
							<div class="imgInput">
								@if (array_get($request,'student_img')) <img
									src="/images/student/{{$request['student_img']}}" alt="student image"
									class="imgView h120" /><br /> @else <img
									src="/img/school/_nouser.png" alt="no user"
									class="imgView h120" /><br /> @endif <input type="file"
									name="image" size="30" /> <input type="hidden" name="card_img"
									value="{{$request->card_img}}" /> <input type="hidden"
									name="student_img" value="{{$request['student_img']}}" />
							</div>
							<!--/.imgInput-->
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('category_title')}}<span class="aster">&lowast;</span></td>
						<td class="t4td2"><select name="student_type"> 
						
						@if	(array_get($request, 'student_type')) 
							@foreach($studentTypes as $key => $item) 
							@if(array_get($request, 'student_type') == $key)
								<option value="{{$key}}" selected="selected">{{$item}}</option>
							@else
							<option value="{{$key}}">{{$item}}</option> @endif @endforeach
						@else 
							@foreach($studentTypes as $key => $item)
							<option value="{{$key}}" selected="selected">{{$item}}</option>
							@endforeach 
						@endif
						</select></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('first_name_title')}}<span class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_m" bind="any_name"
							type="text" name="student_name" style="ime-mode: active;"
							@if (array_get($request, 'student_name')) 
							value="{{array_get($request, 'student_name')}}"
							@endif /> <input type="hidden" name="id" value="{{array_get($request, 'id')}}" />
							<input type="hidden" name="orgparent_id"
							value="{{$request->orgparent_id}}" /> <input type="hidden"
							name="student_no" style="ime-mode: active;"
							value="{{array_get($request, 'student_no')}}"></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('furigana_title')}}<span class="aster"></span></td>
						<td class="t4td2"><input class="text_m" type="text"
							name="student_kana" style="ime-mode: active;"
							value="{{array_get($request, 'student_name_kana')}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('latin_alphabet_title')}}<span
							class="aster"></span></td>
						<td class="t4td2"><input class="text_m" type="text"
							name="student_romaji" style="ime-mode: active;"
							value="{{array_get($request, 'student_romaji')}}"/></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('email_address_title')}}<span
							class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_m" bind="any_name2"
							type="text" name="student_mail" style="ime-mode: active;"
							 value="{{array_get($request, 'mailaddress')}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('birthday_title')}}<span class="aster">&lowast;</span></td>
						<td class="t4td2">{{$lan::get('calendar_title')}}&nbsp;<select
							name="student_birth_year"> @if(isset($request->year_birth))
								<option value="{{$request->year_birth}}">{{$request->year_birth}}</option>
								@if(isset($birthYearList))								
								@foreach($birthYearList as $item)
								<option value="{{$item}}">{{$item}}</option> @endforeach @else
								<option value=""></option> @foreach($birthYearList as $item)
								<option value="{{$item}}">{{$item}}</option> @endforeach @endif @endif
						</select>&nbsp;{{$lan::get('year_title')}} <select
							name="student_birth_month"> @if(isset($request->month_birth))
								<option value="{{$request->month_birth}}">{{$request->month_birth}}</option>
								@if(isset($birthYearList))	
								@foreach($birthMonthList as $item)
								<option value="{{$item}}">{{$item}}</option> @endforeach @else
								<option value=""></option> @foreach($birthMonthList as $item)
								<option value="{{$item}}">{{$item}}</option> @endforeach @endif @endif
						</select>&nbsp;{{$lan::get('month_title')}} <select
							name="student_birth_day"> @if(isset($request->day_birth))
								<option value="{{$request->day_birth}}">{{$request->day_birth}}</option>
								@if(isset($birthYearList))	
								@foreach($birthDayList as $item)
								<option value="{{$item}}">{{$item}}</option> @endforeach @else
								<option value=""></option> @foreach($birthDayList as $item)
								<option value="{{$item}}">{{$item}}</option> @endforeach @endif @endif
						</select>&nbsp;{{$lan::get('day_title')}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('gender_title')}}</td>
						<td class="t4td2"><select name="student_sex" style="width: 70px">
								@if(array_get($request, 'sex')) 
									@if(array_get($request, 'sex') == '1')
								<option value="1">{{$lan::get('male_title')}}</option>
								<option value="2">{{$lan::get('female_title')}}</option>
								<option value="3">{{$lan::get('unknown_title')}}</option>
								@elseif(array_get($request, 'sex') == '2')
								<option value="2">{{$lan::get('female_title')}}</option>
								<option value="1">{{$lan::get('male_title')}}</option> 
								<option value="3">{{$lan::get('unknown_title')}} 
								@elseif(array_get($request, 'sex') == '3')
								<option value="3">{{$lan::get('unknown_title')}}
								<option value="1">{{$lan::get('female_title')}}</option>
								<option value="2">{{$lan::get('male_title')}}</option> @endif
								@else
								<option value="1" style="">{{$lan::get('male_title')}}</option>
								<option value="2">{{$lan::get('female_title')}}</option>
								<option value="3">{{$lan::get('unknown_title')}}</option> @endif
						</select></td>
					</tr>

					<tr>
						<td class="t6_td1">{{$lan::get('join_date_title')}}</td>
						<td><input class="DateInput" type="text" name="enter_date"
							value="@if (array_get($request, 'enter_date')) {{array_get($request, 'enter_date')}} @elseif (array_get($student, 'enter_date')) {{array_get($student, 'enter_date')}} @endif" /></td>
					</tr>

					<tr>
						<td class="t6_td1">{{$lan::get('join_memo_title')}}</td>
						<td class="t4td2"><textarea style="ime-mode: active;" id="input3"
								name="enter_memo" cols="30" rows="4">@if (array_get($request, 'enter_memo')) {{array_get($request, 'enter_memo')}} @elseif (array_get($student, 'enter_memo')) {{array_get($student, 'enter_memo')}}	@endif</textarea></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('withdraw_date_title')}}</td>
						<td><input class="DateInput" type="text" name="resign_date"
							value="@if (array_get($request, 'resign_date')) {{array_get($request, 'resign_date')}} @elseif (array_get($student, 'resign_date')) {{array_get($student, 'resign_date')}} @endif" /></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('withdraw_memo_title')}}</td>
						<td class="t4td2"><textarea form="action_form"	style="ime-mode: active;" id="input3" name="resign_memo" cols="30" rows="4">@if (array_get($request, 'resign_memo')) {array_get($request, 'resign_memo')}} @elseif (array_get($student, 'resign_memo'))	{{array_get($student, 'resign_memo')}}	@endif</textarea></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('postal_code_title')}}</td>
						<td>&#12306;&nbsp;<input class="text_ss" minlength="3"
							maxlength="3" style="width: 40px; ime-mode: inactive;"
							type="text" name="student_zip_code1" value="{{array_get($request, 'student_zip_code1')}}" />&nbsp;－ <input
							class="text_ss" minlength="4" maxlength="4"
							style="width: 60px; ime-mode: inactive;" type="text"
							name="student_zip_code2" value="{{array_get($request, 'student_zip_code2')}}" />
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('state_name_title')}}<span class="aster"></span></td>
						<td class="t4td2"><select name="_pref_id" id="s_address_pref">
						<option value=""></option>
						@foreach($prefList as $key => $item)
							@if(array_get($request,'_pref_id') == $key)
							<option value="{{$key}}" selected @endif>{{$item}}</option>
							<option value="{{$key}}">{{$item}}</option>
						@endforeach
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('city_name_title')}}<span class="aster"></span></td>
						<td class="t4td2"><select name="_city_id" id="s_address_city"
							style="width: 200px"> 
							<option value=""></option>
							@foreach($cityListForStudent as $key => $item)
							@if(array_get($request,'_city_id') == $key)
							<option value="{{$key}}" selected @endif>{{$item}}</option>
							<option value="{{$key}}">{{$item}}</option>
						@endforeach
						</select></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('address_building_name_title')}}<span
							class="aster"></span></td>
						<td class="t4td2"><input class="text_l" style="ime-mode: active;"
							type="text" name="student_address"
							value="{{array_get($request, 'student_address')}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('home_phone_title')}}<span class="aster">&lowast;</span></td>
						<td class="t4td2"><input class="text_m"
							style="ime-mode: inactive;" type="text" name="student_phone_no"
							value="{{array_get($request, 'student_phone_no')}}" /></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$lan::get('mobile_phone_title')}}</td>
						<td class="t4td2"><input class="text_m"
							style="ime-mode: inactive;" type="text" name="student_handset_no"
							value="{{array_get($request, 'student_handset_no')}}" /></td>
					</tr>
				</table>
				@if (array_get($request, 'parent')) 
				<input type="hidden" name="parent_id"
					value="{{$request->parent['id']}}" /> 
				@endif 
				<input type="button"
					style="float: right" id="btn_add_parent"
					value="{{$lan::get('billing_selection_title')}}" />

				<h4>{{$lan::get('billing_title')}}</h4>
				<div id="parent_area">
					<span class="aster">&lowast;</span>{{$lan::get('mandatory_items_marked')}}
					<form id="entry_form" name="entry_form" method="post">
						<input type="hidden" name="function"
							value="{{$request->function}}" /> <input type="hidden"
							name="login_account_id" value="{{$request->login_account_id}}" />
						@if(array_get($request, 'orgparent_id')) <input type="hidden"
							name="orgparent_id" value="{{$request->orgparent_id}}" /> @endif
						@if(array_get($request, 'link_enable')) @include('_parts.student.hidden')
						@endif

						<table id="table6">
							<colgroup>
								<col width="30%" />
								<col width="70%" />
							</colgroup>
							<tr>
								<td class="t6_td1">{{$lan::get('given_name')}}<span class="aster">&lowast;</span></td>
								<td class="t4td2"><input class="text_m"
									style="ime-mode: active;" type="text" name="parent_name"
									bind="any_name" value="{{$request->parent_name}}" /></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('kana_name')}}</td>
								<td class="t4td2"><input class="text_m"
									style="ime-mode: active;" type="text" name="name_kana"
									value="{{$request->name_kana}}" /></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('email_address_1')}}<span
									class="aster">&lowast;</span></td>
								<td class="t4td2"><input class="text_m" bind="any_name2"
									style="ime-mode: active;" type="text"
									name="parent_mailaddress1"
									value="{{$request->parent_mailaddress1}}" /></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('email_address_2')}}</td>
								<td class="t4td2"><input class="text_m"
									style="ime-mode: active;" type="text"
									name="parent_mailaddress2"
									value="{{$request->parent_mailaddress2}}" /></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('password_title')}}<span class="aster">&lowast;</span></td>
								<td class="t4td2"><input class="text_m" type="password"
									name="parent_pass" value="{{array_get($request, 'parent_pass')}}" /> 
									@if	(array_get($request, 'id')) <br /> <span class="col_msg">※{{$lan::get('input_only_to_change_title')}}</span>
									@endif</td>
							</tr>
						</table>
						<h4>{{$lan::get('street_address')}}</h4>
						<table id="table6">
							<colgroup>
								<col width="30%" />
								<col width="70%" />
							</colgroup>

							<tr>
								<td class="t6_td1">{{$lan::get('postal_code')}}</td>
								<td>&#12306;&nbsp;<input class="text_ss" minlength="3"
									maxlength="3" style="width: 40px; ime-mode: inactive;"
									type="text" name="zip_code1" value="{{$request->zip_code1}}" />&nbsp;－
									<input class="text_ss" minlength="4" maxlength="4"
									style="width: 60px; ime-mode: inactive;" type="text"
									name="zip_code2" value="{{$request->zip_code2}}" />
								</td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('prefecture_name')}}</td>
								<td class="t4td2"><select name="pref_id" id="address_pref">
									<option value=""></option>
										@foreach($prefList as $key => $item)
										@if($request->pref_id == $key)
										<option value="{{$key}}" selected @endif>{{$item}}</option>
										<option value="{{$key}}">{{$item}}</option> @endforeach
								</select></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('city_name')}}</td>
								<td class="t4td2"><select name="city_id" id="address_city"
									style="width: 200px"> 
									{{-- <option value=""></option> --}}
									@foreach($cityListForParent as $key => $item)
										@if($request->city_id == $key)
										<option value="{{$key}}" selected @endif>{{$item}}</option>
										<option value="{{$key}}">{{$item}}</option> @endforeach
								</select></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('bunch_building_name')}}<span
									class="aster">&lowast;</span></td>
								<td class="t4td2"><input class="text_l"
									style="ime-mode: active;" type="text" name="address"
									value="{{$request->address}}" /></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('home_phone')}}</td>
								<td class="t4td2"><input class="text_m"
									style="ime-mode: inactive;" type="text" name="phone_no"
									value="{{$request->phone_no}}" /></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('mobile_phone')}}</td>
								<td class="t4td2"><input class="text_m"
									style="ime-mode: inactive;" type="text" name="handset_no"
									value="{{$request->handset_no}}" /></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('memo')}}</td>
								<td class="t4td2"><textarea style="ime-mode: active;"
										id="input3" name="memo" cols="30" rows="4">{{$request->memo}}</textarea></td>
							</tr>

						</table>



						<table id="table6">
							<colgroup>
								<col width="30%" />
								<col width="70%" />
							</colgroup>
							<tr>
								<td class="t6_td1"><strong>{{$lan::get('payment_method')}}</strong></td>
								<td><select id="invoicetype" name="invoice_type">
										<option value="0" 
										@if (($request->invoice_type == null) || $request->invoice_type == 0) selected="selected" @endif>{{$lan::get('cash')}}</option>
										<option value="1" @if ($request->invoice_type == 1) selected="selected" @endif>{{$lan::get('transfer')}}</option>
										<option value="2" @if (($request->invoice_type == 2)) selected="selected" @endif>{{$lan::get('account_transfer')}}</option>
								</select></td>
							</tr>
							<tr>
								<td class="t6_td1"><strong>{{$lan::get('notification_method')}}</strong>
								</td>
								<td><select id="mailinfo" name="mail_infomation">
										<option value="0" 
										@if (isset($request->mail_infomation) && ($request->mail_infomation == 0)) selected
											@endif>{{$lan::get('mailing')}}</option>
										<option value="1" 
										@if (isset($request->mail_infomation) && ($request->mail_infomation == null) || ($request->mail_infomation == 1)) selected
											@endif>{{$lan::get('email')}}</option>
								</select></td>
							</tr>
						</table>
						<div id="invoiceinfo"
						@if ($request->invoice_type != 2) style="display:none" @endif>
							<h4>{{$lan::get('account_information')}}</h4>
							<table id="table6">
								<colgroup>
									<col width="30%" />
									<col width="70%" />
								</colgroup>
								<tr>
									<td class="t6_td1">{{$lan::get('financial_organizations')}}</td>
									<td><select id="banktype" name="bank_type">
											<option value="1" 
											@if (isset($request->bank_type) &&	($request->bank_type == null) || ($request->bank_type == 1))
												selected @endif>{{$lan::get('bank_credit_union')}}</option>
											<option value="2" 
											@if (isset($request->bank_type) &&($request->bank_type == 2)) selected
												@endif>{{$lan::get('post_office')}}</option>
									</select></td>
								</tr>
							</table>
						</div>
						<div id="bankinfo" @if ($request->invoice_type != 2 || ($request->bank_type != null && $request->bank_type != 1)) 
					style="display:none" @endif>
							<table id="table6">
								<colgroup>
									<col width="30%" />
									<col width="70%" />
								</colgroup>
								<tr>
									<td class="t6_td1">{{$lan::get('bank_code')}} <span class="aster">*</span>
									</td>
									<td><input style="ime-mode: inactive;" type="text"
										name="bank_code" value="{{$request->bank_code}}"
										class="l_text" /> {{$lan::get('half_width_number_4_digit')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('financial_institution_name')}} <span
										class="aster">*</span>
									</td>
									<td><input style="ime-mode: inactive;" type="text"
										name="bank_name" value="{{$request->bank_name}}"
										class="l_text" />
										{{$lan::get('single_byte_uppercase_kana_up_15_character')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('branch_code')}} <span class="aster">*</span>
									</td>
									<td><input style="ime-mode: inactive;" type="text"
										name="branch_code" value="{{$request->branch_code}}"
										class="l_text" /> {{$lan::get('half_width_number_3_digit')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('branch_name')}} <span class="aster">*</span>
									</td>
									<td><input style="ime-mode: inactive;" type="text"
										name="branch_name" value="{{$request->branch_name}}"
										class="l_text" />
										{{$lan::get('single_byte_uppercase_kana_up_15_character')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('classification')}} <span
										class="aster">*</span>
									</td>
									<td><select name="bank_account_type">
											@if(isset($bank_account_type_list))
											 @foreach($bank_account_type_list as $key => $item)
											<option value="{{$key}}" @if (isset($request->bank_account_type)
												&& $request->bank_account_type == $key) selected
												@endif>{{$item}}</option> @endforeach
											@endif
									</select></td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('account_number')}} <span
										class="aster">*</span>
									</td>
									<td><input style="ime-mode: inactive;" type="text"
										name="bank_account_number"
										value="{{$request->bank_account_number}}" class="m_text" />
										{{$lan::get('half_width_number_7_digit')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('account_holder')}} <span
										class="aster">*</span>
									</td>
									<td><input style="ime-mode: active;" type="text"
										name="bank_account_name"
										value="{{$request->bank_account_name}}" class="l_text" />
										{{$lan::get('single_byte_uppercase_kana_up_30_character')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('account_kana_name')}}
									</td>
									<td><input style="ime-mode: active;" type="text"
										name="bank_account_name_kana"
										value="{{$request->bank_account_name_kana}}" class="l_text" />
									</td>
								</tr>
							</table>
						</div>

						<div id="postinfo" @if ($request->invoice_type != 2 || $request->bank_type != 2) style="display:none" @endif>
							<table id="table6">
								<colgroup>
									<col width="30%" />
									<col width="70%" />
								</colgroup>
								<tr>
									<td class="t6_td1">{{$lan::get('passbook_symbol')}} <span
										class="aster">*</span>
									</td>
									<td><input style="ime-mode: inactive;" type="text"
										name="post_account_kigou"
										value="{{$request->post_account_kigou}}" class="m_text" />
										{{$lan::get('half_width_number_5_digit')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('passbook_number')}} <span
										class="aster">*</span>
									</td>
									<td><input style="ime-mode: inactive;" type="text"
										name="post_account_number"
										value="{{$request->post_account_number}}" class="m_text" />
										{{$lan::get('half_width_number_8_digit')}}</td>
								</tr>
								<tr>
									<td class="t6_td1">{{$lan::get('passbook_name')}} <span
										class="aster">*</span>
									</td>
									<td><input style="ime-mode: active;" type="text"
										name="post_account_name"
										value="{{$request->post_account_name}}" class="l_text" />
										{{$lan::get('single_byte_uppercase_kana_up_30_character')}}</td>
								</tr>
							</table>
						</div>

						<div id="AdjustInfo">
							<h4>{{$lan::get('premium_discount')}}</h4>
							<table id="table6">
								<colgroup>
									<col width="30%" />
									<col width="70%" />
								</colgroup>
								<tr>
									<td class="t6_td1">{{$lan::get('premium_discount_items')}}</td>
									<td>
										<div id="inputActive">
											@if($request->has('payment') && (count($request['payment']) > 0))
											@foreach(array_get($request, 'payment') as $k =>$v)
											<div class="InputArea">
												<table>
													<tr>
														<td class="t4d2">{{$lan::get('target_month')}} <select name="payment[{{$loop->index}}][payment_month]" form="action_form" id="payment_month_{{$loop->index}}" class="formItem PaymentMonth">
																<option value=""></option> 
																@foreach ($month_list as $key => $row)
																<option value="{{$key}}" @if( $key== array_get($v, 'payment_month') ) selected  @endif >{{$row}}</option>
																@endforeach</select>														
														&nbsp;{{$lan::get('abstract')}}<select form="action_form" name="payment[{{$loop->index}}][payment_adjust]" id="payment_adjust_{{$loop->index}}" class="formItem PaymentAdjust">
																<option value=""></option> 
																@foreach($invoice_adjust_list as $key => $row) <option value="{{array_get($row, 'id')}}" @if( array_get($v, 'payment_adjust') == array_get($row, 'id'))selected @endif >{{array_get($row, 'name')}}</option>
																@endforeach 
																</select> &nbsp;{{$lan::get('price')}}<input type="text"
												form="action_form" name="payment[{{$loop->index}}][payment_fee]" id="payment_fee_{{$loop->index}}" class="formItem InputFee"
												style="ime-mode: active; width: 80px;"
												value="{{array_get($v,'payment_fee')}}" />&nbsp;{{$lan::get('circle')}}
														
												<input type="hidden" name="payment[{{$loop->index}}][payment_id]"
												id="payment_id_{{$loop->index}}" value="{{array_get($v,'payment_id')}}" />
												<a class="inputDelete" href="#"><input type="button"
													value="{{$lan::get('delete')}}" /></a>
														</td>
													</tr>
												</table>
											</div>
											@endforeach @endif
										</div>
										<div style="margin: 10px 10px 17px 120px;">
											<button id="inputAdd" style="width: 100px">{{$lan::get('add_items')}}</button>
										</div>
										<div id="inputBase" style="display: none;">
											<!-- 					review . Data get from controller or request-->
											<table>
												<tr>
													<td class="t4d2">{{$lan::get('target_month')}}<select
														class="formItem NewPaymentMonth" title="payment_month"
														form="action_form">
															<option value=""></option> 
															@if(isset($month_list))
															@foreach ($month_list as $key => $row)
															<option value="{{$row}}">{{$row}}</option> @endforeach
															@endif
													</select> &nbsp;{{$lan::get('abstract')}}<select
														form="action_form" class="formItem NewPaymentAdjust"
														title="payment_adjust">
															<option value=""></option> 
															@if(isset($invoice_adjust_list))
															@foreach ($invoice_adjust_list as $row)
															<option value="{{array_get($row, 'id')}}">{{$row['name']}}</option>
															@endforeach
															@endif
													</select> &nbsp;{{$lan::get('price')}} <input type="text"
														form="action_form" class="formItem NewPaymentFee"
														style="ime-mode: active; width: 80px;" value=""
														title="payment_fee" />&nbsp;{{$lan::get('circle')}} <input
														type="hidden" class="formItem NewPaymentId" value=""
														title="payment_id" /> <a class="inputDelete" href="#"> <input
															type="button" value="{{$lan::get('delete')}}" /></a>
													</td>
												</tr>
											</table>
										</div>

									</td>
								</tr>
							</table>
						</div>
						<h4>{{$lan::get('other_title')}}</h4>
						<table id="table6">
							<colgroup>
								<col width="30%" />
								<col width="70%" />
							</colgroup>
							<tr>
								<td class="t6_td1">{{$lan::get('memo1_title')}}</td>
								<td class="t4td2"><textarea form="action_form"
										style="ime-mode: active;" id="input3" name="memo1" cols="30"
										rows="4">{{$request->memo1}}</textarea></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('memo2_title')}}</td>
								<td class="t4td2"><textarea form="action_form"
										style="ime-mode: active;" id="input3" name="memo2" cols="30"
										rows="4">{{$request->memo2}}</textarea></td>
							</tr>
							<tr>
								<td class="t6_td1">{{$lan::get('memo3_title')}}</td>
								<td class="t4td2"><textarea form="action_form"
										style="ime-mode: active;" id="input3" name="memo3" cols="30"
										rows="4">{{$request->memo3}}</textarea></td>
							</tr>
						</table>
						<br />
						<div class="exe_button">
							<input class="submit2" type="button"
								value="{{$lan::get('confirm_title')}}" />
							<input class="button" type="reset"
								value="{{$lan::get('Reset')}}" />
<!-- 							<button type="reset" class="btn btn-default">{{$lan::get('Reset')}}</button> -->
							<style>
/* 							     .button{ */
/*                                     border: solid 1px #ccc; */
/* 	width: 150px; */
/* 	padding: 4px 0 3px; */
/* 	font-size: 1px; */
/* 	border-radius: 3px; */
/* 	text-align: center; */
/* 	transition-duration:0.1s; */
/* 							     } */
							     
							     .button{
							     width: 150px;
	padding: 3px 8px 2px;	
	font-size: 10px;
	border-radius: 5px;
	border: solid 1px #ccc;
/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ffffff+0,eaeaea+100 */
background: #ffffff; /* Old browsers */
background: -moz-linear-gradient(top,  #ffffff 0%, #eaeaea 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top,  #ffffff 0%,#eaeaea 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom,  #ffffff 0%,#eaeaea 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eaeaea',GradientType=0 ); /* IE6-9 */

}

.button:hover{
background-color: #d9dddd; background-image: -webkit-gradient(linear, left top, left bottom, from(#d9dddd), to(#c6c3c3));
 background-image: -webkit-linear-gradient(top, #d9dddd, #c6c3c3);
 background-image: -moz-linear-gradient(top, #d9dddd, #c6c3c3);
 background-image: -ms-linear-gradient(top, #d9dddd, #c6c3c3);
 background-image: -o-linear-gradient(top, #d9dddd, #c6c3c3);
 background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#d9dddd, endColorstr=#c6c3c3);
	box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
}
							     input[type="reset"]{
							     width: 150px;
	padding: 3px 8px 2px;	
	font-size: 14px;
	border-radius: 5px;
	border: solid 1px #ccc;
/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ffffff+0,eaeaea+100 */
background: #ffffff; /* Old browsers */
background: -moz-linear-gradient(top,  #ffffff 0%, #eaeaea 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top,  #ffffff 0%,#eaeaea 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom,  #ffffff 0%,#eaeaea 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eaeaea',GradientType=0 ); /* IE6-9 */

}
							</style>
						</div>

					</form>
				</div>
				<!--section_content_in-->
		
		</div>
		<!--section_content-->
	</div>
</div>
<br />
<!-- <div id="diva"> -->
<!-- <form id="form1" action="/school/student/edit" method="get"> 
	<a href="javascript:;" onclick="document.getElementById('form1').submit();" class="button"><input type="button" value="Previous"  ></a>-->
<!-- 	<input type="hidden" name="id" value="{{$request->id -1}}"/> -->
<!-- </form> -->
<!-- </div> -->
<!-- <div id="divb"> -->
<!-- <form id="form2" action="/school/student/edit" method="get"> 
	<a href="javascript:;" onclick="document.getElementById('form2').submit();" class="button"><input type="button" value="Next" style="float: right"></a>		-->
<!-- 	<input type="hidden" name="id" value="{{$request->id +1}}"/> -->
<!-- </form> -->
<!-- </div> -->
<style>
#diva {
	float: left;
}

#divb {
	float: right;
}
</style>
</td>

<script>
$("*[bind]").on('change keyup', function (e) {
 var to_bind = $(this).attr('bind');
 $("*[bind='"+to_bind+"']").html($(this).val());
 $("*[bind='"+to_bind+"']").val($(this).val());
})
$("div[bind]").bind("DOMSubtreeModified",function(){
 var to_bind = $(this).attr('bind');
 $("*[bind='"+to_bind+"']").html($(this).html());
 $("*[bind='"+to_bind+"']").val($(this).html());
}); 
</script>

<div id="dialog_active" class="no_title" style="display:none;">
	あなたが保存したいです?
</div>
@stop
