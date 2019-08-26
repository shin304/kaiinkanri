@extends('_parts.master_layout')

@section('content')
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />

	<form id="action_form" method="post">
	{{ csrf_field() }}
		<input type="hidden" name="mode" value="" />
		<div id="center_content_header" class="box_border1">
		<h2 class="float_left"><i class="fa fa-book"></i>{{$lan::get('main_title')}}</h2>
			<div class="center_content_header_right">
				
			</div>
			<div class="clr"></div>
		</div><!--center_content_header-->

		<h3 id="content_h3" class="box_border1">{{$lan::get('detail_info_title')}}</h3>
		@if (count($errors) > 0) 
		<ul class="message_area">
			@if (array_get($errors,'class_name.notEmpty'))<li class="error_message">{{$lan::get('required_class_name_error_title')}}</li>@endif
			@if (array_get($errors,'_class.notDelete'))<li class="error_message">{{$lan::get('can_not_delete_higher_error_title')}}</li>@endif
		</ul>
		@endif

		<div id="section_content">
		<div class="info_content padding1 box_border1">
			<div class="info_info_right p15">
				@if ($class['start_date'])<p>{{$lan::get('begin_day_title')}}：{{date('Y-m-d', strtotime($class['start_date']))}}</p>@endif
				@if ($class['close_date'])<p>{{$lan::get('end_day_title')}}：{{date('Y-m-d', strtotime($class['close_date']))}}</p>@endif
			</div>

			<p class="info_name p32">{{$class['class_name']}}</p>				<!-- イベント名称 -->
			<span class="info_info p18">{!! $class['class_description'] !!}</span>		<!-- イベント内容 -->
			<div class="clr margin-bottom10"></div>

			<table id="table3_2">
				<colgroup>
					<col width="50%"/>
					<col width=""/>
				</colgroup>
				<tr>
					<td class="t3_2td1" colspan="2">{{$lan::get('tuition_title')}}</td>
				</tr>
<!-- 受講料を可変化 -->
				@foreach ($class_fee as $idx1=>$fee)
					<tr>
						<td class="t3_2td2">
							{{array_get($fee,'name')}}
							@if (array_get($fee,'required_fee') == '1')<span class="aster">&lowast;</span>@endif
						</td>
						<td class="t3_2td3">
							@if (array_get($fee,'fee'))
								{{number_format(array_get($fee,'fee'))}}&nbsp;{{$lan::get('jap_yen_title')}} <!-- |number_format -->
							@else
								@if (array_get($fee,'fee') !=null ||  array_get($fee,'fee') !='')
									{{array_get($fee,'fee')}}&nbsp;{{$lan::get('jap_yen_title')}}
								@endif
							@endif
						</td>
					</tr>
					@endforeach
<!-- 受講料を可変化 -->
				@if (count($teacher_list) > 0)
				<tr>
					<td class="t3_2td1">{{$lan::get('coach_title')}}</td>
					<td>
						@foreach ($teacher_list as $row)
							{{array_get($row, 'coach_name')}} <br>
						@endforeach
					</td>
				</tr>
				@endif
			</table>
		</div>

        {{--search box--}}
        <div class="search_box">
            <form method="post" id="action_form" action="{{$_app_path}}class/detail">
                {{ csrf_field() }}
                <input type="hidden" name ="id" value="{{$request->id}}">
                <table>
                    <tr>
                        <th width="10%">{{$lan::get('member_type_title')}}&nbsp;</th>
                        <td>@if (isset($studentTypeList))
                                @foreach ($studentTypeList as $index=>$type)
                                    <label>
                                        <input type="checkbox" name="_student_types[{{$index}}]" value="{{$type['id']}}" @if ($type['is_display'] == '1') checked @endif/>&nbsp;{{$type['name']}}
                                    </label>
                                @endforeach
                            @endif</td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td>
                            <button class="btn_search" type="submit" id="btn_student_search" name="search_button" style="height:29px;padding: 2px 10px !important;width: 150px !important;"><i class="glyphicon glyphicon-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search_title')}}</button>
                            <input type="button" class="submit" id="search_cond_clear" value="{{$lan::get('clear_title')}}"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

		<h3 id="content_h3" class="box_border1">{{$lan::get('member_detail_info_title')}}</h3>
        <div>
		@if($edit_auth)
            <!--会員追加 -->
            <a class="text_link button" href="{{$_app_path}}class/studentList?id={{$class['id']}}&mode=1">
                <button type="button" class="btn_button" style="color: #000;"><i class="fa fa-plus " ></i>{{$lan::get('add_title')}}</button></a>
            <!--会員削除 -->
            <a class="text_link button" href="{{$_app_path}}class/studentList?id={{$class['id']}}&mode=2">
                <button type="button" class="btn_button" style="color: #000;"><i class="fa fa-minus " ></i>{{$lan::get('member_delete_title')}}</button></a><br/>
			@endif
        </div>
		<div id="section_content_in">
			<table class="table_list">
				<thead>
				<tr>
					<th class="text_title" style="width:20%;">{{$lan::get('member_name_title')}}</th>
                    <th class="text_title" style="width:20%;">{{$lan::get('member_phone_title')}}</th>
                    <th class="text_title" style="width:10%;">{{$lan::get('member_type_title')}}</th>
                    <th class="text_title" style="width:10%;">{{$lan::get('payment_type_title')}}</th>
                    <th class="text_title" style="width:10%;">{{$lan::get('number_of_payment_title')}}</th>
                    <th class="text_title" style="width:10%;">{{$lan::get('total_pay_title')}}</th>
                    {{--<th class="text_title" style="width:10%;">{{$lan::get('notice_mail_title')}}</th>--}}
                    <th class="text_title" style="width:10%;"></th>
				</tr>
				</thead>
			</table>
			<div class="over_content">
				@foreach ($student_list as $idx=>$row)
					<div class="panel-group">
						<div class="panel panel-default">
							<div class="panel-heading-block" >
								<table class="class-table-content" style="width: 100%;">
									<tr>
										<td style="width:20%;"><!-- 会員名 -->
											@if($edit_auth)
											<a class="text_link" href="{{$_app_path}}class/studentEdit?id={{array_get($row,'student_class_id')}}">{{array_get($row,'student_name')}}<i class="fa fa-edit" style="font-size:16px;vertical-align: bottom;margin-left: 5px;"></i> </a>
											@else
												{{array_get($row,'student_name')}}
											@endif
										</td>
										<td style="width:20%;"><!-- 会員番号-->
											{{array_get($row,'student_no')}}
										</td>

										<td style="width:10%;"><!-- 会員種別-->
											{{array_get($row,'student_type_name')}}
										</td>
										<td style="width:10%; @if(array_get($row,'method_supported') == false) color: red!important; ; font-weight: bold @endif"><!-- 支払方法-->
											{{$lan::get(array_get($row,'payment_method_name'))}}
										</td>
										<td style="width:10%;"><!-- 支払回数-->
											@if (array_key_exists(array_get($row,'number_of_payment'), $month_list)) {{$month_list[array_get($row,'number_of_payment')]}} @endif
										</td>
										<td style="width:10%;text-align:right;"><!-- 支払総額 99:毎月-->
											@if ($row['student_category'] == 2 && $row['payment_unit'] == 1)
												@if(array_get($row,'number_of_payment') == 99) {{array_get($row,'sum_coop_fee')}}{{$lan::get('jap_yen_title')}}{{$lan::get('per_month_title')}} @else {{array_get($row,'sum_coop_total_fee')}}{{$lan::get('jap_yen_title')}}{{$lan::get('per_year_title')}} @endif
											@else
												@if(array_get($row,'number_of_payment') == 99) {{array_get($row,'fee')}}{{$lan::get('jap_yen_title')}}{{$lan::get('per_month_title')}} @else {{array_get($row,'total_fee')}}{{$lan::get('jap_yen_title')}}{{$lan::get('per_year_title')}} @endif
											@endif
										</td>
										{{--<td style="width:10%;text-align:center;"><!-- 事前通知-->--}}
											{{--@if (array_get($row,'notices_mail_flag')) <i class="fa fa-check-square-o" style="width: 10%;font-size:16px;"></i> @else <i class="fa fa-square-o" style="width: 10%;font-size:16px;"></i> @endif--}}
										{{--</td>--}}
										<td style="width:10%;text-align:right;" class="drop_down" data-toggle="collapse" href="#collapse{{$idx}}"><i class="fa fa-chevron-right" style="width: 10%;font-size:16px;"></i></td>
									</tr>
								</table>
							</div>
							<div id="collapse{{$idx}}" class="panel-collapse collapse">
								<div class="panel-body">
									<table class="subject-table" >
										@if (array_key_exists($row['student_class_id'], $student_payment_plan))
											<tr>
												<th>{{$lan::get('pay_schedule_date')}}</th>
												<th>{{$lan::get('schedule_fee_title')}}</th>
												<th></th>
												<th></th>
											</tr>

											@foreach($student_payment_plan[$row['student_class_id']] as $item)
											<tr>
												<td>@if (array_get($item, 'schedule_date')) {{$item['schedule_date']}} @endif</td>
												<td style="text-align: right">@if (array_get($item, 'schedule_fee')) <label style="color: #4d7634; margin-right: 4px;">{{number_format($item['schedule_fee'])}}</label> @endif{{$lan::get('jap_yen_title')}}</td>
											</tr>
											@endforeach
											@forelse ($student_payment_plan[$row['student_class_id']] as $item)
											@empty
												<tr><td colspan="2" class="error_row">{{$lan::get('nothing_display_title')}}</td></tr>
											@endforelse
										@else
											@if ($row['student_category'] == 2 && $row['payment_unit'] == 1)
												<tr>
													<td>{{$month_list[99]}}</td>
													<td  style="text-align: right"><label style="color: #4d7634; margin-right: 4px;">{{array_get($row,'fee')}}</label> {{$lan::get('jap_yen_title')}}</td>
													<td  style="text-align: right"><label style="color: #4d7634; margin-right: 4px;">{{array_get($row,'total_member')}}</label> 人</td>
													<td  style="text-align: right"><label style="color: #4d7634; margin-right: 4px;">{{array_get($row,'sum_coop_fee')}}</label> {{$lan::get('jap_yen_title')}}</td>
												</tr>
											@else
												<tr>
													<td>{{$month_list[99]}}</td>
													<td  style="text-align: right"><label style="color: #4d7634; margin-right: 4px;">{{array_get($row,'fee')}}</label> {{$lan::get('jap_yen_title')}}</td>
												</tr>
											@endif
										@endif

									</table>
								</div>
							</div>
						</div>
					</div>
				@endforeach
				@forelse ($student_list as $row)
				@empty
					<div class="error_row">{{$lan::get('nothing_display_title')}}</div>
				@endforelse
			</div>
			<br>

			@if($edit_auth)
			<button class="submit2" type="button" id="btn_edit" ><i class="fa fa-edit " style="width: 20%;font-size:16px;"></i>{{$lan::get('edit_title')}}</button>
			@endif
			<button class="submit2" type="button" id="btn_return" ><i class="fa fa-arrow-circle-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
			@if (count($student_list) < 1 && $edit_auth)
			<button class="submit2" type="button" id="btn_delete" ><i class="fa fa-remove" style="width: 20%;font-size:16px;"></i>{{$lan::get('class_delete')}}</button>
			@endif

		</div>
		</div>
	</form>
<div id="dialog-confirm"  style="display: none;">
	{{$lan::get('delete_confirm_title')}}
</div>
<div id="dialog-warning"  style="display: none;">
    {{$lan::get('unsupported_method_warning')}}
</div>
{{--<div id="dialog-copy"  style="display: none;">
	{{$lan::get('copy_action_title')}}<br/>
	{{$lan::get('new_class_title')}}
	<form id="copy_form" method="post">
		<input type="hidden" name="id" value="{{request('id')}}"/>
		<table>
			<tr>
				<td>{{$lan::get('class_name_title')}}<span class="aster">&lowast;</span></td>
				<td><input type="text" name="class_name" value=""/></td>
				<td><p id="name_check_msg" style="color:red">{{$lan::get('input_class_name_error_title')}}</p></td>
			</tr>
			<tr>
				<td>{{$lan::get('begin_day_title')}}</td>
				<td colspan="2"><input type="text" class="DateInput" name="start_date" value=""/></td>
			</tr>
			<tr>
				<td>{{$lan::get('end_day_title')}}</td>
				<td colspan="2"><input type="text" class="DateInput" name="close_date" value=""/></td>
			</tr>
		</table>
	</form>
</div>--}}

	<script type="text/javascript">
        $(function() {
            $( "#dialog-warning" ).dialog({
                title: "{{$lan::get('warning_title')}}",
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "OK": function() {
                        $( this ).dialog( "close" );
                        return false;
                    }
                }
            });
            @if($request->offsetExists('warning_flag'))
                $( "#dialog-warning" ).dialog('open');
            @endif

            $('#search_cond_clear').click(function () {
                $('[name^=_student_types]').prop('checked', true);
            });

            $("#btn_return").click(function() {
                java_post('{{$_app_path}}class');
                return false;
            });
            $("#btn_add").click(function() {
                java_post("{{$_app_path}}class/studentList?id={{$class['id']}}&mode=1");
                return false;
            });
            $("#btn_del").click(function() {
                java_post("{{$_app_path}}class/studentList?id={{$class['id']}}&mode=2");
                return false;
            });
            $("#btn_edit").click(function() {
                java_post("{{$_app_path}}class/input?id={{$class["id"]}}");
                return false;
            });
            $("#btn_delete").click(function() {
                $( "#dialog-confirm" ).dialog('open');
                return false;
            });
            $( "#dialog-confirm" ).dialog({
                title: "{{$lan::get('main_title')}}",
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "{{$lan::get('delete_title')}}": function() {
                        $( this ).dialog( "close" );
                        var link = '{{$_app_path}}class/complete?id={{$class["id"]}}&mode=2';
                        java_post(link);
                        // $("#action_form input[name='mode']").val("2");
                        // $("#action_form").attr('action', '{{$_app_path}}class/complete?id={{$class["id"]}}');
                        // $("#action_form").submit();
                        return false;
                    },
                    "{{$lan::get('cancel_title')}}": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
            $(".drop_down").click(function (e) {
                e.preventDefault();
                if($(this).children().hasClass("fa-chevron-right")){
                    $(this).children().removeClass("fa-chevron-right");
                    $(this).children().addClass("fa-chevron-down");
                }else if($(this).children().hasClass("fa-chevron-down")){
                    $(this).children().removeClass("fa-chevron-down");
                    $(this).children().addClass("fa-chevron-right");
                }

            });

			{{--$("#btn_copy").click(function(e) {
                e.preventDefault();
                $( "#dialog-copy" ).dialog({
                    title: 'プランコピー',
                    autoOpen: false,
                    dialogClass: "no-close",
                    resizable: false,
                    modal: true,
                    width: 450,
                    height: 280,
                    buttons: {
                        "{{$lan::get('copy_title')}}": function() {
                            $("#copy_form").attr('action', '{{$_app_path}}class/completeCopy');
                            $("#copy_form").submit();
                            $( this ).dialog( "close" );
                            return false;
                        },
                        "{{$lan::get('cancel_title')}}": function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
                $( "#dialog-copy" ).dialog("open");
                return false;
            });*/ --}}

        });

	</script>
	<style>
		.search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover, .btn_button:hover, .submit2:hover {
			background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
			box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
			cursor: pointer;
			text-shadow: 0 0px #FFF;
		}
		.search_box #search_cond_clear {
			height: 29px;
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		}
		.top_btn li {
			border-radius: 5px;
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		}
		.btn_search {
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		}
		input[type="button"] {
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
		}
		.submit2, .btn_button {
			color: #595959;
			height: 30px;
			border-radius: 5px;
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
			font-size: 14px;
			font-weight: normal;
			text-shadow: 0 0px #FFF;
		}
		.subject-table td {
			background: #f3faff;
			width: 0%;
			padding: 5px 15px;
			border: solid 1px #ddd !important;
		}
	</style>
@stop

