@extends('_parts.portal.portal_layout')

@section('content')
    <div class="title_bar">
        {{$data['course_title']}} イベント申し込み（結果）
    </div>
    <div class="content_header"></div>
    <div class="content_detail">
        @include('Portal.student_info')

        @if ( request()->has('sendid'))
            <p style="color:#0000ff">クレジットカード決済が成功しました。</p>
        @endif
        @include('Portal.Event.info')
        <div>
            <button type="button" style ="padding: 10px;font-size: 14px;min-width: 150px;border-radius: 5px;" id="print_entry">印刷</button>
        </div>
        <script>
            $(document).ready(function () {
                $("#print_entry").click(function () {
                    $(this).hide();
                    window.print();
                    $(this).show();
                })
            })
        </script>
        <style>
            #print_entry:hover{
                cursor: pointer;
            }
        </style>
    </div>
    <div class="content_footer"></div>
@stop

