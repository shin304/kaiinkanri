@extends('_parts.master_layout')

@section('content')
<script type="text/javascript">
$(function() {
	$(".submit3").click(function() {

		$("#action_form").attr('action', '{{$_app_path}}coach/input');
		$("#action_form").submit();

		return false;
	});
	$(".submit2").click(function() {
		$("#action_form").attr('action', '{{$_app_path}}coach/complete');
		$("#action_form").submit();
		return false;
	});
});
</script>

<div id="center_content_header" class="box_border1">
	<h2 class="float_left"><i class="fa fa-black-tie"></i>{{$lan::get('main_title')}}</h2>
		<div class="center_content_header_right">
			<div class="top_btn"></div>
		</div>
		<div class="clr"></div>
	</div><!--center_content_header-->
	<h3 id="content_h3" class="box_border1">
	@if (request('function') == 1)
		{{$lan::get('detail_info_register_confirm_title')}}
	@else
		{{$lan::get('detail_info_edit_confirm_title')}}
	@endif
	</h3>
	<div id="section_content">
		<div id="section_content_in">
			<form id="action_form" method="post">
			{{-- {{include file="pages_parts/hidden.html"}} --}}
					<p>{{$lan::get('right_content_check_msg')}}</p>
					<table id="table6">
					<colgroup>
						<col width="30%"/>
						<col width="70%"/>
					</colgroup>
					<tr>
						<td class="t6_td1">{{$lan::get('profile_avatar_title')}}</td>
						<td class="t4td2">
						@if $request.filename && isset($request.filename|smarty:nodefaults) }}
							{{if $request.change_image}}
								<img id="img_review" width="150" height="150" src="/school/images/{{$request.filename}}" />
							{{else}}
								<img id="img_review" width="150" height="150" src="readfile?filename={{$request.filename}}&type={{$request.type}}" />
							{{/if}}
						{{/if}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$name_title}}<span class="required">*</span></td>
						<td class="t4td2">
							{{$request.coach_name}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$furigana_title}}<span class="required">*</span></td>
						<td class="t4td2">
							{{$request.coach_name_kana}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$email_title}}<span class="required">*</span></td>
						<td class="t4td2">
							{{$request.coach_mail}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$password_title}}<span class="required">*</span></td>
						<td class="t4td2">
							{{if $request.coach_id}}
								{{if $request.coach_pass1}}[{{$updated_msg}}]{{else}}[{{$no_change_msg}}]{{/if}}
							{{else}}		
								{{if $request.coach_pass1}}
									{{section name=foo start=0 loop=$request.coach_pass1|strlen step=1}}●{{/section}}
								{{/if}}
							{{/if}}
							<input type="hidden" name="old_pw" value="{{$request.old_pw}}"/>
						</td>
					</tr>

					<tr>
						<td colspan="2"><b>{{$permission_setting_title}}</b></td>
					</tr>
					<!-- メニュー権限設定 -->
					@include ('School.Coach.menu_auth_disabled')
					<tr>
						<td colspan="2"><b>{{$basic_info_title}}</b></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$birthday_title}}</td>
						<td class="t4td2">
							{{$request.birth_date}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$gender_title}}</td>
						<td class="t4td2">
							{{if !$request.gender || $request.gender==1}}{{$man_title}}{{else}}{{$woman_title}}{{/if}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$note_title}}</td>
						<td class="t4td2">
							{{$request.note}}
						</td>
					</tr>
					<tr>
						<td colspan="2"><b>{{$education_background_title}}</b></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$last_education_background_title}}</td>
						<td class="t4td2">
							{{$request.highest_education}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$graduate_year_title}}</td>
						<td class="t4td2">
							{{$request.graduate_date}}
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>{{$recruitment_info_title}}</b>
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$recruitment_year_title}}</td>
						<td class="t4td2">
							{{$request.employment_date}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$employment_status_title}}</td>
						<td class="t4td2">
							{{if !$request.teacher_type || $request.teacher_type==1}}
							{{$employer_title}}
							{{elseif $request.teacher_type==2}}
  							{{$contract_employer_title}}
  							{{elseif $request.teacher_type==3}}
  							{{$parttime_employer_title}}
							{{/if}}
						</td>
					</tr>
					<tr>
						<td colspan="2"><b>{{$address_1_title}}</b></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$postal_code_title}}</td>
						<td class="t4td2">
						&#12306;&nbsp;<input class="text_ss"  style="width:40px;ime-mode:inactive;" type="text" name="address1_zip1" value="{{$request.address1_zip1}}" disabled/>&nbsp;－
						<input class="text_ss"  style="width:60px;ime-mode:inactive;" type="text" name="address1_zip2" value="{{$request.address1_zip2}}" disabled/>
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$province_city_title}}</td>
						<td class="t4td2">
						{{$address1_pref_name}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$district_title}}</td>
						<td class="t4td2">
						{{$address1_city_name}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$address_title}}</td>
						<td class="t4td2">
							{{$request.address1_address}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$phone_number_title}}</td>
						<td class="t4td2">
							{{$request.address1_phone}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$mobile_number_title}}</td>
						<td class="t4td2">
							{{$request.mobile_no}}
						</td>
					</tr>
					<tr>
						<td colspan="2"><b>{{$address_2_title}}</b></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$postal_code_title}}</td>
						<td class="t4td2">
						&#12306;&nbsp;<input class="text_ss"  style="width:40px;ime-mode:inactive;" type="text" name="address2_zip1" value="{{$request.address2_zip1}}" disabled/>&nbsp;－
						<input class="text_ss"  style="width:60px;ime-mode:inactive;" type="text" name="address2_zip2" value="{{$request.address2_zip2}}" disabled/>
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$province_city_title}}</td>
						<td class="t4td2">
						{{$address2_pref_name}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$district_title}}</td>
						<td class="t4td2">
						{{$address2_city_name}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$address_title}}</td>
						<td class="t4td2">
							{{$request.address2_address}}
						</td>
					</tr>
					<tr>
						<td class="t6_td1">{{$phone_number_title}}</td>
						<td class="t4td2">
							{{$request.address2_phone}}
						</td>
					</tr>
					<tr>
						<td colspan="2"><b>{{$coach_schedule_title}}</b></td>
					</tr>
					<tr>
						<td class="t6_td1">{{$schedule_title}}</td>
						<td class="t4td2">
						<div id="teacher_schedule">
						<table>
						<colgroup>
							<col width="100"/>
							<col width="150"/>
	  						<col width="150"/>
	  						<col width="150"/>
	  						<col width="150"/>
  						</colgroup>
						<tr>
							<td><input type="checkbox" id="monday" name="teach_day1" {{if $request.teach_day1}}checked{{/if}} disabled/><label for="monday"> {{$monday_title}}</label></td>
							<td>{{$start_working_time_title}}</td>
							<td>&nbsp;{{$request.day1_start}}</td>
							<td>{{$end_working_time_title}}</td>
							<td>&nbsp;{{$request.day1_end}}</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="tuesday" name="teach_day2" {{if $request.teach_day2}}checked{{/if}} disabled/><label for="tuesday"> {{$tuesday_title}}</label></td>
							<td>{{$start_working_time_title}}</td>
							<td>&nbsp;{{$request.day2_start}}</td>
							<td>{{$end_working_time_title}}</td>
							<td>&nbsp;{{$request.day2_end}}</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="wednesday" name="teach_day3" {{if $request.teach_day3}}checked{{/if}} disabled/><label for="wednesday"> {{$wednesday_title}}</label></td>
							<td>{{$start_working_time_title}}</td>
							<td>&nbsp;{{$request.day3_start}}</td>
							<td>{{$end_working_time_title}}</td>
							<td>&nbsp;{{$request.day3_end}}</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="thursday" name="teach_day4" {{if $request.teach_day4}}checked{{/if}} disabled/><label for="thursday"> {{$thursday_title}}</label></td>
							<td>{{$start_working_time_title}}</td>
							<td>&nbsp;{{$request.day4_start}}</td>
							<td>{{$end_working_time_title}}</td>
							<td>&nbsp;{{$request.day4_end}}</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="friday" name="teach_day5" {{if $request.teach_day5}}checked{{/if}} disabled/><label for="friday"> {{$friday_title}}</label></td>
							<td>{{$start_working_time_title}}</td>
							<td>&nbsp;{{$request.day5_start}}</td>
							<td>{{$end_working_time_title}}</td>
							<td>&nbsp;{{$request.day5_end}}</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="saturday" name="teach_day6" {{if $request.teach_day6}}checked{{/if}} disabled/><label for="saturday"> {{$saturday_title}}</label></td>
							<td>{{$start_working_time_title}}</td>
							<td>&nbsp;{{$request.day6_start}}</td>
							<td>{{$end_working_time_title}}</td>
							<td>&nbsp;{{$request.day6_end}}</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="sunday" name="teach_day7" {{if $request.teach_day7}}checked{{/if}} disabled/><label for="sunday"> {{$sunday_title}}</label></td>
							<td>{{$start_working_time_title}}</td>
							<td>&nbsp;{{$request.day7_start}}</td>
							<td>{{$end_working_time_title}}</td>
							<td>&nbsp;{{$request.day7_end}}</td>
						</tr>
						</table>
						</div>
						</td>
					</tr>
				</table>
				<br/>
				<div class="exe_button">
					<input class="submit3" type="submit" value="{{$back_btn}}"/>
					<input class="submit2" type="submit" value="{{$go_btn}}"/>
				</div>
				</div>
			</form>
		</div> <!-- section_content_in -->
	</div> <!-- section_content -->
@stop