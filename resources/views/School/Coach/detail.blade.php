@extends('_parts.master_layout')

@section('content')
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/coach.css" />
	@if (session()->has('messages'))
		<ul class="message_area">
			<li class="info_message">{{ $lan::get(session()->pull('messages')) }}</li>
		</ul>
	@endif
	<div id="center_content_header" class="box_border1">
		<h2 class="float_left main-title"><i class="fa fa-black-tie"></i>{{$lan::get('detail_info_title')}}</h2>
		<div class="clr"></div>
	</div>

	@if(request()->has('profile_img'))
		<img width="150" height="150" src="/image/{{request('profile_img')}}"/>
	@else
		<img id="img_review" width="150" height="150" src="/img/school/default_user.png" />	<br>
	@endif
	<p class="info_name p32">{{request('coach_name')}}</p>
	<table id="table6">
		<colgroup>
			<col width="30%"/>
			<col width="70%"/>
		</colgroup>
		<tr>
			<td class="t6_td1">{{$lan::get('furigana_title')}}</td>
			<td class="t4td2">
				{{request('coach_name_kana')}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('email_title')}}</td>
			<td class="t4td2">
				{{request('mail_address')}}
			</td>
		</tr>
		<tr>
			<td colspan="2"><b>{{$lan::get('permission_setting_title')}}</b></td>
		</tr>
		<tr>
			<td colspan="2">
				@include('_parts.menu_auth_display')
			</td>
		</tr>
		<tr>
			<td colspan="2"><b>{{$lan::get('basic_info_title')}}</b></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('birthday_title')}}</td>
			<td class="t4td2">
				{{request('birth_date')}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('gender_title')}}</td>
			<td class="t4td2">
				@if(!request()->has('gender') || request('gender')==1){{$lan::get('man_title')}}@else{{$lan::get('woman_title')}}@endif
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('note_title')}}</td>
			<td class="t4td2">
				{{request('note')}}
			</td>
		</tr>
		<tr>
			<td colspan="2"><b>{{$lan::get('education_background_title')}}</b></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('last_education_background_title')}}</td>
			<td class="t4td2">
				{{request('highest_education')}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('graduate_year_title')}}</td>
			<td class="t4td2">
				{{request('graduate_date')}}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<b>{{$lan::get('recruitment_info_title')}}</b>
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('recruitment_year_title')}}</td>
			<td class="t4td2">
				{{request('employment_date')}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('employment_status_title')}}</td>
			<td class="t4td2">
				@if(request()->has('teacher_type') || request('teacher_type')==1)
					{{$lan::get('employer_title')}}
				@elseif(request('teacher_type')==2)
					{{$lan::get('contract_employer_title')}}
				@elseif(request()->has('teacher_type')==3)
					{{$lan::get('parttime_employer_title')}}
				@endif
			</td>
		</tr>
		<tr>
			<td colspan="2"><b>{{$lan::get('address_1_title')}}</b></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('postal_code_title')}}</td>
			<td class="t4td2">
				&#12306;&nbsp;<input class="text_ss" type="text" name="address1_zip1" value="{{request('address1_zip1')}}" disabled/>&nbsp;－
				<input class="text_ss" type="text" name="address1_zip2" value="{{request('address1_zip2')}}" disabled/>
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('province_city_title')}}</td>
			<td class="t4td2">
				{{$address1_pref_name}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('district_title')}}</td>
			<td class="t4td2">
				{{$address1_city_name}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('address_title')}}</td>
			<td class="t4td2">
				{{request('address1_address')}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('building_title')}}</td>
			<td class="t4td2">
				{{request('address1_building')}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('phone_number_title')}}</td>
			<td class="t4td2">
				{{request('address1_phone')}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('mobile_number_title')}}</td>
			<td class="t4td2">
				{{request('mobile_no')}}
			</td>
		</tr>
		<tr>
			<td colspan="2"><b>{{$lan::get('address_2_title')}}</b></td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('postal_code_title')}}</td>
			<td class="t4td2">
				&#12306;&nbsp;<input class="text_ss" type="text" name="address2_zip1" value="{{request('address2_zip1')}}" disabled/>&nbsp;－
				<input class="text_ss" type="text" name="address2_zip2" value="{{request('address2_zip2')}}" disabled/>
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('province_city_title')}}</td>
			<td class="t4td2">
				{{$address2_pref_name}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('district_title')}}</td>
			<td class="t4td2">
				{{$address2_city_name}}
			</td>
		</tr>
		<tr>
			<td class="t6_td1">{{$lan::get('address_title')}}</td>
			<td class="t4td2">
				{{request('address2_address')}}
			</td>
		</tr>
        <tr>
            <td class="t6_td1">{{$lan::get('building_title')}}</td>
            <td class="t4td2">
                {{request('address2_building')}}
            </td>
        </tr>
		<tr>
			<td class="t6_td1">{{$lan::get('phone_number_title')}}</td>
			<td class="t4td2">
				{{request('address2_phone')}}
			</td>
		</tr>
		{{--<tr>--}}
			{{--<td colspan="2"><b>{{$lan::get('coach_schedule_title')}}</b></td>--}}
		{{--</tr>--}}
	</table>
	<div class="div-btn">
		<ul>
			@if($edit_auth)
			<a href="#" class="button" id="submit2"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-edit"></i>{{$lan::get('edit_title')}}</li></a>
			@endif
			<a href="#" class="button" id="btn_back"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-circle-arrow-left"></i>{{$lan::get('back_btn')}}</li></a>
		</ul>
	</div>

	<div class="div-prev-next">
	@if(request()->has('prev_id'))
		<input type="submit" class="btn_green" id="btn_before" value="{{$lan::get('previous_text')}}">
	@endif
	@if(request()->has('next_id'))
		<input type="submit" class="btn_green" id="btn_after" value="{{$lan::get('next_text')}}">
	@endif
	</div>
	<script>
		$(document).ready(function() {
            $("#btn_before").click(function() {
                java_post("{{$_app_path}}coach/detail?id={{request('prev_id')}}");
                return false;
            });
            $("#btn_after").click(function() {
                java_post("{{$_app_path}}coach/detail?id={{request('next_id')}}");
                return false;
            });
            // 戻るボタン
            $("#btn_back").click(function () {
                java_post("{{$_app_path}}coach");
                return false;
            });

            // 編集ボタン
            $("#submit2").click(function () {
                java_post("{{$_app_path}}coach/entry?id={{request('id')}}");
                return false;
            });
		});
	</script>
	<style>
		.div-btn li:hover {
			background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
			box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
			cursor: pointer;
		}
		.div-btn li {
			color: #595959;
			height: 30px;
			border-radius: 5px;
			background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
			/*font-size: 14px;*/
			font-weight: normal;
			text-shadow: 0 0px #FFF;
		}
	</style>
	{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
@stop