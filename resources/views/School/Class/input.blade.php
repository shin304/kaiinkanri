@extends('_parts.master_layout')

@section('content')
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
	<style>
        #class_fee_area td {
            padding: 10px !important;
            padding-left: 5px !important;
        }
		#inputAdd:hover, .div-btn li:hover, .btn_button:hover {
			background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
			box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
			cursor: pointer;
			text-shadow: 0 0px #FFF;
		}
		.div-btn li, #inputAdd, .btn_button {
			color: #595959;
			height: 30px;
			border-radius: 5px;
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
			/*font-size: 14px;*/
			font-weight: normal;
			text-shadow: 0 0px #FFF;
		}
	</style>
	<div id="center_content_header" class="box_border1">
		<h2 class="float_left"><i class="fa fa-book"></i>{{$lan::get('main_title')}}</h2>
		<div class="clr"></div>
	</div> <!-- center_content_header -->


	<h3 id="content_h3" class="box_border1">{{$lan::get('detail_info_title')}}
		@if (old('id', request('id'))) {{$lan::get('edit_title')}}
		@else {{$lan::get('register_title')}} 
		@endif
	</h3>
	<!-- ///////////// success -->

	@if (count($errors) > 0) 
		<ul class="message_area">
		@foreach ($errors->all() as $error)
        <li class="error_message">{{ $lan::get($error) }}</li>
        @endforeach
		</ul>
	@endif 

	<div id="section_content1">
	<span class="aster">&lowast;</span>{{$lan::get('mandatory_items_title')}}

	<form action="#" method="post" id="action_form" name="action_form">
	@if (old('id', request('id')))
			<input type="hidden" name="id" value="{{old('id', request('id'))}}"/>
	@endif
	{{ csrf_field() }}
			<table id="table6">
			<colgroup>
				<col width="15%"/>
				<col width="85%"/>
			</colgroup>
			<tr>
				<td class="t6_td1">
					<div style="width: 130px;">
						{{$lan::get('class_name_title')}}<span class="aster">&lowast;</span>
					</div>
				</td>
				<td width="40px;">
					<!-- 名称 -->
					<input style="ime-mode:active;" type="text" name="class_name" value="{{old('class_name', request('class_name'))}}" class="l_text form-group" placeholder="{{$lan::get('name_input_title')}}">

				</td>
			</tr>
			<tr>
				<!-- 内容 -->
				<td class="t6_td1">{{$lan::get('subject_title')}}</td>
				<td><textarea id="course_description" style="ime-mode:active;" name="class_description" rows="5" cols="100">{{old('class_description', request('class_description'))}}</textarea></td>
			</tr>
				<!-- 講師 -->
			<tr>
				<td class="t6_td1">{{$lan::get('coach_title')}}</td>
				<td>
					<ul style="float:left; width: auto;" id="teacher_area">
						@php
							$teacher_ids = old('teacher_ids', request('teacher_ids'));
						@endphp
						@if (count(old('teacher_ids', request('teacher_ids')))>0 )

							@foreach (old('teacher_ids', request('teacher_ids')) as $k=>$v)
								<li style="margin-bottom:5px;list-style-type:none;">
									<select name="teacher_ids[]">
										<option value="" ></option>
										@foreach ($teacher_data as $row)
											<option value="{{array_get($row,'id')}}" @if ($v == array_get($row,'id'))selected @endif>{{array_get($row,'coach_name')}}</option>
										@endforeach
									</select>
								</li>
							@endforeach
						@endif
					</ul>
					<div style="float:left;padding-left:5px;">
						<!-- <input type="button" value="{{$lan::get('add_row_title')}}" id="add_teacher_row"/> -->
						<button id="add_teacher_row" type="button" class="btn_button" style="font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="color: #595959; width: 15% !important;"></i>&nbsp; {{$lan::get('add_row_title')}}</button> &nbsp;
					</div>
					<div style="display:none;" id="teacher_row_template">
						<ul>
							<li style="margin-bottom:5px;list-style-type:none;">
								<select name="template_teacher">
									<option value=""></option>
									@foreach ($teacher_data as $row)
										<option value="{{array_get($row,'id')}}">{{array_get($row,'coach_name')}}</option>
									@endforeach
								</select>
							</li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<!-- 支払方法-->
				<td class="t6_td1">
					{{$lan::get('payment_type_title')}} <span class="aster">&lowast;</span>
				</td>
				<td>
					@foreach($payment_list as $item)
						<label style="margin-right: 20px;font-weight: 500;"><input type="checkbox" name="payment_methods[]" value="{{$item['payment_method_code']}}" @if ((old('payment_methods', request('payment_methods')) && in_array($item['payment_method_code'], old('payment_methods', request('payment_methods'))))|| empty(old('payment_methods', request('payment_methods')))) checked @endif>{{$lan::get(array_get($item, 'payment_method_name'))}}</label>
					@endforeach
					@forelse ($payment_list as $item)
					@empty
						<p class="error_message">{{$lan::get('notice_setting_payment_method')}}</p>
					@endforelse
					
				</td>
				<td>

				</td>
			</tr>
			<tr>
				<td class="t6_td1">
					{{$lan::get('price_setting_title_new')}}
				</td>
				<td>
					<label style="font-weight: normal !important;"><input type="radio" name="price_setting_type"
																		  @if(array_get($request,'price_setting_type')==1)checked
																		  @endif value="1">{{$lan::get('price_setting_type_1')}}</label> &nbsp;&nbsp;&nbsp;&nbsp;
					<label style="font-weight: normal !important;"><input type="radio" name="price_setting_type"
																		  @if(array_get($request,'price_setting_type')==2)checked
																		  @endif value="2">{{$lan::get('price_setting_type_2')}}</label>
				</td>
			</tr>
			<tr>
				<!-- 代金 -->
				<td class="t6_td1">
					{{$lan::get('class_fee_type_title')}}
					<span class="aster">&lowast;</span>
				</td>
				<td>
					<div id="inputActive" >
						<table class="input_class_fee" style="border-collapse:collapse;">
							<tr>
								<th class="t6_td1" style="width:170px;">{{$lan::get('member_type_title')}}</th>
								<th class="t6_td1" style="width:220px;">{{$lan::get('class_name_title')}}</th>
								<th class="t6_td1" style="width:120px;"></th>
								<!-- // price_setting_type : 料金設定 [1:会員種別による料金設定, 2: 受講回数による料金設定] -->
								{{--@if (request('price_setting_type') == 2)--}}
								<th class="t6_td1 price_type_2" style="width:110px;">{{$lan::get('unit_number_lecture_title')}}</th>
								<th class="t6_td1 price_type_2" style="width:70px;">{{$lan::get('number_lecture_title')}}</th>
								{{--@endif--}}
								<th class="t6_td1" style="width:100px;">{{$lan::get('fee_title')}}</th>
								<th class="t6_td1" style="width:70px;">{{$lan::get('invalid_title')}}</th>
								<th class="t6_td1" style="width:70px;"></th>
							</tr>
							<tbody id="class_fee_area">
							@if ( count(old('_class_fee', request('_class_fee'))) > 0 )
								@foreach (old('_class_fee', request('_class_fee')) as $idx=>$row)
									<tr>
										<td class="t4d2" style="display: none;">
											<input type="hidden" name="_class_fee[{{$idx}}][id]" value="{{array_get($row,'id')}}" />
										</td>
										<td><!--会員種別 -->
											<select name="_class_fee[{{$idx}}][student_type_id]" class="student_types" style="width: 120px;">
												<option value=""></option>
												@foreach ($studentTypes as $key=>$val)
													<option value="{{$key}}" @if ($key == array_get($row,'student_type_id')) selected @endif>{{$val['name']}}</option>
												@endforeach
											</select>
										</td>
										<td class="t4d2"><!--名称 -->
											<input style="ime-mode:inactive; width:150px;" type="text" name="_class_fee[{{$idx}}][fee_plan_name]" value="{{array_get($row, 'fee_plan_name')}}" class="l_text"/>
										</td>
										<td>
											<!-- 選択リスト追加 :
                                            ・一人当たり
                　                           ・全員で -->
											<select name="_class_fee[{{$idx}}][payment_unit]" style="width: 100px;">
												<option value="1" @if (array_get($row,'payment_unit') != 2) selected @endif>{{$lan::get('payment_unit_person_title')}}</option>
												<option value="2" @if (array_get($row,'payment_unit') == 2) selected @endif>{{$lan::get('payment_unit_everyone_title')}}</option>
											</select>
										</td>
										<!-- // price_setting_type : 料金設定 [1:会員種別による料金設定, 2: 受講回数による料金設定] -->
										{{--@if (request('price_setting_type') == 2)--}}
											<td class = "price_type_2 t4d2">
												<select  style="width:100px;" name="_class_fee[{{$idx}}][attend_times_div]">
													@foreach ($attendUnitList as $key=>$val)
														<option value="{{$key}}" @if ($key == array_get($row,'attend_times_div')) selected="selected" @endif>{{$val}}</option>
													@endforeach
												</select>
											</td>
											<td class = "price_type_2 t4d2">
												<input style="ime-mode:inactive;width:60px;text-align: right;" type="number" name="_class_fee[{{$idx}}][attend_times]" value="{{array_get($row,'attend_times')}}" class="l_text" min="1"/>
											</td>
										{{--@endif--}}
										<td class="t4d2"> <!--料金 -->
											<input  style="ime-mode:inactive;width:90px;text-align: right;" type="text" name="_class_fee[{{$idx}}][fee]" value="@if (array_get($row,'fee')) {{number_format(array_get($row,'fee'))}} @endif" class="l_text"
                                                    pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
										</td>

										<td class="t4d2" ><!--無効 -->
											<input style="width:50px;vertical-align:bottom;" type="checkbox" name="_class_fee[{{$idx}}][active_flag]" value="0" @if (array_get($row,'active_flag') == '0') checked @endif/>&nbsp;
										</td>
										@if (array_get($row,'id') != '')
											<td  class="t4d2" style="text-align:center;">
												<!-- <input style="visibility:hidden;" type="button" value="{{$lan::get('add_row_title')}}" /> -->
												<button type="button" style="width: 75px !important; visibility:hidden; font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="color: #595959; width: 15% !important;"></i>&nbsp; {{$lan::get('add_row_title')}}</button>
											</td>
										@else
											<td  class="t4d2" style="text-align:center;">
												<!-- <input type="button" value="{{$lan::get('delete_row_title')}}" onclick="removeRow(this); return false;" /> -->
												<button type="button" class="btn_button" style="width: 75px !important; font-size: 11px !important;" onclick="removeRow(this); return false;" ><i class="glyphicon glyphicon-minus-sign " style="color: #595959; width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
											</td>
										@endif
									</tr>
								@endforeach
							@endif
							</tbody>

						</table>
					</div>
					@if (!request('reference'))
					<!-- <input type="button" value="{{$lan::get('add_row_title')}}" id="btn_add_row"  style="margin:10px;"/> -->
					<button id="btn_add_row" class="btn_button" type="button" style="margin:10px; font-size: 11px !important;"><i class="glyphicon glyphicon-plus-sign " style="color: #595959; width: 15% !important;"></i>&nbsp; {{$lan::get('add_row_title')}}</button> &nbsp;
					@endif
				</td>
				<td>
				</td>
			</tr>
			<!-- 受講料以外にかかる費用 -->
			<tr>
				<td  class="t6_td1">{{$lan::get('cost_not_tuition_free_title')}}</td>

				<td>
					<div id="inputActive2" >
					@if ( count(old('payment', request('payment'))) > 0 )
					
						@foreach (old('payment', request('payment')) as $k=>$v)
							<div class="InputArea" >
								<table style="width:750px;" id="{{$k}}" class="tbl_clone_payment">
									<tr>
										<td class="t4d2">

											{{$lan::get('target_month_title')}}<select name="payment[{{$k}}][payment_month]" id="payment_month_{{$k}}"  class="formItem PaymentMonth">
												<option value="" ></option>
												@foreach ($month_list as $key=>$row)
													<option value="{{$key}}" @if ($key == array_get($v,'payment_month')) selected @endif>{{$row}}</option>
												@endforeach
											</select>

											&nbsp;{{$lan::get('payment_title')}}<select name="payment[{{$k}}][payment_adjust]" id="payment_adjust_{{$k}}"  class="formItem PaymentAdjust">
												<option value="" ></option>
												@foreach ($invoice_adjust_list as $row)
													<option value="{{array_get($row,'id')}}" @if (array_get($v,'payment_adjust') == array_get($row,'id')) selected @endif>{{array_get($row,'name')}}</option>
												@endforeach
											</select>

											&nbsp;{{$lan::get('amout_money_title')}}<input type="text" name="payment[{{$k}}][payment_fee]"  id="payment_fee_{{$k}}"  class="formItem InputFee" style="ime-mode:inactive; width:80px;text-align: right;" value="@if (array_get($v,'payment_fee')) {{number_format(array_get($v,'payment_fee'))}} @endif"
                                                                                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;{{$lan::get('jap_yen_title')}}

											<input type="hidden" name="payment[{{$k}}][payment_id]" id="payment_id_{{$k}}" value="{{array_get($v,'payment_id')}}"/>
											<a class="inputDelete" href="#">
												<!-- <input type="button" value="{{$lan::get('delete_row_title')}}"/> -->
												<button type="button" style="color: #595959; width: 75px !important; font-size: 11px !important;"><i class="glyphicon glyphicon-minus-sign " style="color: #595959; width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
											</a>
										</td>
									</tr>
								</table>
							</div>
						@endforeach
					@endif
					</div>

					<div style="margin:10px 10px 17px 120px;">
						<button id="inputAdd" style="width:120px"><i class="glyphicon glyphicon-plus-sign " style="width: 15% !important;"></i>&nbsp;{{$lan::get('add_item_title')}}</button>
					</div>
					<div id="inputBase" style="display:none;">
						<table style="width:750px;" class="tbl_clone_payment">
							<tr>
								<td class="t4d2">
									{{$lan::get('target_month_title')}}<select  class="formItem NewPaymentMonth" title="payment_month">
										<option value="" ></option>
										@foreach ($month_list as $key=>$row)
											<option value="{{$key}}">{{$row}}</option>
										@endforeach
									</select>

									&nbsp;{{$lan::get('payment_title')}}<select  class="formItem NewPaymentAdjust" title="payment_adjust">
										<option value="" ></option>
										@foreach ($invoice_adjust_list as $row)
											<option value="{{$row['id']}}">{{$row['name']}}</option>
										@endforeach
									</select>
									&nbsp;{{$lan::get('amout_money_title')}}<input  type="text"  class="formItem NewPaymentFee" style="ime-mode:inactive; width:80px;text-align: right;" value=""  title="payment_fee"
                                            pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>&nbsp;{{$lan::get('jap_yen_title')}}
									<input  type="hidden"  class="formItem NewPaymentId" value=""  title="payment_id"/>
									<a class="inputDelete" href="#">
										<!-- <input type="button" value="{{$lan::get('delete_row_title')}}"/> -->
										<button type="button" style="color: #595959; width: 75px !important; font-size: 11px !important;"><i class="glyphicon glyphicon-minus-sign " style="color: #595959;width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
									</a>
								</td>
							</tr>
						</table>
					</div>
					
				</td>
			</tr>

			<tr>
				<!-- 開始日 -->
				<td class="t6_td1">
					{{$lan::get('begin_day_title')}}
				</td>
				<td>
					<input type="text" class="DateInput" name="start_date" value="@if (old('start_date', request('start_date'))) {{date('Y-m-d', strtotime(old('start_date', request('start_date'))))}} @endif"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$lan::get('not_set_start_date_and_not_reflected_in_bill')}}
				</td>
				<td>

				</td>
			</tr>
			<tr>
				<!-- 終了日 -->
				<td class="t6_td1">
					{{$lan::get('end_day_title')}}
				</td>
				<td>
					<input type="text" class="DateInput" name="close_date" value="@if (old('close_date', request('close_date'))) {{date('Y-m-d', strtotime(old('close_date', request('close_date'))))}} @endif"/>
				</td>
				<td>
				</td>
			</tr>
		</table>

		<br/>
		<div class="div-btn">
			<ul>
				<!-- <a href="" class="button" id="submit2" ><li><i class="glyphicon glyphicon-save"></i>@if(old('id', request('id'))) {{$lan::get('edit_title')}} @else {{$lan::get('class_register_title')}} @endif</li></a> -->
				<a href="" class="button" id="submit2" ><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-floppy-disk"></i>{{$lan::get('class_register_title')}}</li></a>
				<a href="@if (old('id', request('id'))){{$_app_path}}class/detail?id={{old('id', request('id'))}} @else {{$_app_path}}class @endif" class="text_link button" id="btn_back"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-circle-arrow-left"></i>{{$lan::get('return_title')}}</li></a>
			</ul>
		</div>

	</form>
	<div style="display: none;"><!-- テーブルコピー元ねた -->
			<table id="tbl_clone">
				<tbody>
					<tr>
						<td class="t4d2" style="display: none;">
							<input type="hidden" name="_class_fee[*][id]" value="" />
						</td>
						<td>
							<select name="_class_fee[*][student_type_id]" class="student_types" style="width: 120px;">
								<option value=""></option>
								@foreach ($studentTypes as $key=>$val)
									<option value="{{$key}}" >{{$val['name']}}</option>
								@endforeach
							</select>
						</td>
						<td class="t4d2">
							<input style="width: 150px;" type="text" name="_class_fee[*][fee_plan_name]" value="" class="l_text" />
						</td>
						<td>
							<!-- 選択リスト追加 :
                            ・一人当たり
　                           ・全員で -->
							<select name="_class_fee[*][payment_unit]" style="width: 100px;">
								<option value="1">{{$lan::get('payment_unit_person_title')}}</option>
								<option value="2">{{$lan::get('payment_unit_everyone_title')}}</option>
							</select>
						</td>
						<!-- // price_setting_type : 料金設定 [1:会員種別による料金設定, 2: 受講回数による料金設定] -->
						{{--@if (request('price_setting_type') == 2)--}}
						<td class = "price_type_2 t4d2">
							<select style="width:100px;" name="_class_fee[*][attend_times_div]">
								@foreach ($attendUnitList as $key=>$val)
								<option value="{{$key}}">{{$val}}</option>
								@endforeach
							</select>
						</td>
						<td class = "price_type_2 t4d2">
							<input  style="width:60px;text-align: right;" type="number" name="_class_fee[*][attend_times]" value="" class="l_text" min="1"/>
						</td>
						{{--@endif--}}
						<td class="t4d2">
							<input  style="width:90px;text-align: right;" type="text" name="_class_fee[*][fee]" value="" class="l_text"
                                    pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
						</td>
						
						<td class="t4d2" >
							<input style="width:50px;vertical-align:bottom;" type="checkbox" name="_class_fee[*][active_flag]" value="0" />&nbsp;
						</td>
						@if (!request('reference'))
						<td  class="t4d2" style="text-align:center;">
							<!-- <input type="button" value="{{$lan::get('delete_row_title')}}" onclick="removeRow(this); return false;" /> -->
							<button type="button" class="btn_button" style="width: 75px !important; font-size: 11px !important;" onclick="removeRow(this); return false;" ><i class="glyphicon glyphicon-minus-sign " style="color: #595959; width: 15% !important;"></i>&nbsp; {{$lan::get('delete_row_title')}}</button>
						</td>
						@endif
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- Section Content1 -->
	
<script type="text/javascript">
    $(document).ready(function () {
        if($("input[name=price_setting_type]:checked").val() == 1){
            $(".price_type_2").hide();
        }else{
            $(".price_type_2").show();
        }
    })
	$(function() {
        $(document).on("change","input[name=price_setting_type]", function () {
            if($(this).val() == 1){
                $(".price_type_2").hide();
            }else{
                $(".price_type_2").show();
            }
        })

        // init tinymce tool
        tinymce.init({
            selector: 'textarea#course_description',
            menubar:false,
            toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
        });
//        $('.input_class_fee th').each(function (index, el) {
//
//            console.log(index);
//            console.log($(el).width());
//            $('.input_class_fee tr').find('td:eq('+index+')').each(function (idx, e) {
//                $(e).width($(el).width());
//
//            })
//        })
//	    todo set sortable Class fee
        $( "#class_fee_area" ).sortable({
            revert: true,
            start: function(e, ui){
                $(ui.item).data('old-idx' , ui.item.index());
            },
            update: function(e, ui) {
                refresh_row_no(ui.item);
            }
        });
		$('#submit2').click(function (e) {
            e.preventDefault();
            var title = '{{$lan::get('save_confirm_title')}}';
            // var content = '{{$lan::get('save_confirm_content')}}';
            var content = '{{$lan::get('confirm_content')}}';
            var action_url = '{{$_app_path}}class/complete';
            common_save_confirm(title, content,action_url);
        })

		// 行追加
		$("#btn_add_row").click(function() {
			// 行数取得
			var len = $(".input_class_fee tr").length-1;
			// コピー作成
			var tbl_item = $("#tbl_clone tbody > tr").clone(true).appendTo($("tbody#class_fee_area"));
			// 名前を変更
			tbl_item.find('input,select').each(function(){
				if (this.type != "button") {
					name_str = $(this).attr('name');
					name_str = name_str.replace("*",len);
					$(this).attr('name',name_str);
				}
				// 表示順
			});
            $('select[name*=attend_times_div]').change();
			return false;
		});

        $("#add_teacher_row").click(function(){
            var add_row = $("#teacher_row_template").find("li").clone(true);
            add_row.find("[name=template_teacher]").attr("name", "teacher_ids[]");
            $("#teacher_area").append(add_row);
            return false;
        });
//	================FROM VALIDATE=================//
		$('select[name*=attend_times_div]').change(function () {

			if ($(this).val() == 0) {
			    // close next input
			    $(this).parent().next().children().hide();
			} else {
                // open next input
                $(this).parent().next().children().show();
			}
        });
        $('select[name*=attend_times_div]').change();

        $('.student_types').change(function () {
            $text =  $(this).find('option:selected').text();
            // copy text into 名称
            $(this).closest('tr').find('td:eq(2) input').val($text);
        });
	});

	$(function() {
		//datepicker追加
		$.datetimepicker.setLocale('ja');

		jQuery(function(){
			jQuery('.DateTimeInput').datetimepicker({
				format: 'Y-m-d H:i',
				step : 5,
				minDate: new Date(),
				scrollMonth : false,
				scrollInput : false

			});
		});
		jQuery(function(){
			jQuery('.DateInput').datetimepicker({
				format: 'Y-m-d',
				timepicker:false,
//				minDate: new Date(),
				scrollMonth : false,
				scrollInput : false
			});
		});
		
	});
	/**
	 * 行を削除
	 */
	function removeRow(obj) {
		// 行削除
		$(obj).closest("tr").remove();
		// リフレッシュ
		refresh_row_no($(obj).parent().parent());
//		refresh_display(obj);
		return false;
	}
	
	/**
	 * sort_noをリフレッシュ
	 */
	function refresh_row_no(obj) {

        $('#class_fee_area tr').each(function () {
            $idx = $(this).index();
            $(this).children().each(function (idx, el) {
                $this = $(el).children();
                if ($this[0].type != "button") {
					// name_arr : Ex: "_class_fee[1][attend_times_div]"
                    name_str 	= $this.attr('name');console.log('old:' +name_str);
                    start_at 	= name_str.indexOf("[")+1 ; // Ex: = 11
                    end_at 		= name_str.indexOf("]") ; // Ex: = 12


                    name_str = (end_at-start_at)== 2?
                        name_str.replace(/(.{11}).{2}/,"$1" + $idx) : name_str.replace(/(.{11}).{1}/,"$1" + $idx);
                    $this.attr('name',name_str);console.log('new:' +name_str);
                }
        	});
        });

		return false;
	}
	/**
	 * 矢印の表示をリフレッシュ
	 */
	function refresh_display(obj) {
		var i = 1;
		$(".row_up").each(function() {
			if (i == 1) {
				// 先頭行
				$(this).css('visibility', 'hidden');
			} else {
				$(this).css('visibility', 'visible');
			}
			i++;
		});
		// 行数取得（コピー元ねた行を除く）
		var len = $(".row_down").length-1;
		// 行数取得（コピー元ねた行を含む）
		var len_incl_copy = $(".row_down").length;
		i = 1;
		$(".row_down").each(function() {
			if (i == len || i == len_incl_copy) {
				// 表示最終行とコピー元ねた行
				$(this).css('visibility', 'hidden');
			} else {
				$(this).css('visibility', 'visible');
			}
			i++;
		});

		return false;
	}
	$(function() {


		var nowInputIndex =  ($('.tbl_clone_payment').length)-1;

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

			$( "#inputActive2" ).append( newTable );//inputActive2のID指定にnewTableの内容を追加する
			$( "#inputActive2" ).append( newHR    );//inputActive2のID指定にnewHRの内容を追加する

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

			nowInputIndex++;

			return false;
		});

		// 削除処理設定
		$( "A.inputDelete" ).click( function(e){
			$(this).parents('table[class=tbl_clone_payment]').remove();
			return false;
		});
	});
	// 削除
	function inputDel( item ){
		$( item ).remove();
		return false;
	}


	{{--@if (request('payment'))
	var nowInputIndex =  {{count(request('payment'))}};
	@else --}}
	{{--@endif --}}


</script>
@stop 