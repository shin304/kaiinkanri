{{--請求先・会員名--}}
<p class="ct_p1">
    {{--@if ($pschool['parent_name'] != $pschool['student_name'])--}}
        {{--{{$pschool['parent_name']}}様 （{{$pschool['student_name']}}様）--}}
    {{--@else--}}
        {{--{{$pschool['parent_name']}}様--}}
    {{--@endif--}}
    {{$pschool['student_name']}} 様
</p>

<div class="title_bar2" style="float: right; text-align: left">
    <p class="juku_tel">〒{{substr($pschool['zip_code'],0,3)}}-{{substr($pschool['zip_code'],3,4)}}</p>
    <p class="juku_tel">{{$pschool['school_pref_name']}}{{$pschool['school_city_name']}}{{$pschool['school_address']}}</p>
    <p class="juku_tel"> {{$pschool['building']}}</p>
    <p class="juku_tel">{{$pschool['school_name']}}</p>
    <p class="juku_tel">TEL {{$pschool['school_tel']}}</p>
    <p class="juku_tel">{{$pschool['mailaddress']}}</p>
    <p class="juku_tel">{{$pschool['official_position']}} {{$pschool['daihyou']}}</p>
</div><!--title_bar2　学習塾情報-->
<div style="clear: both"></div>