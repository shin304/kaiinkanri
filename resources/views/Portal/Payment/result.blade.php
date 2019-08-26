@extends('_parts.portal.portal_layout')

@section('content')
    <div class="title_bar">
        「{{$data['course_title']}}」 イベント申し込み
    </div>
    <div class="content_header"></div>
    <div class="content_detail">
        @include('Portal.student_info'){{--学習塾情報--}}

        @include('Portal'.$type.'.info'){{--情報--}}
    </div>
    <div class="content_footer"></div>
@stop

