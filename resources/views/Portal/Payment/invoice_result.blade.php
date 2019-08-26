@extends('_parts.portal.portal_layout')

@section('content')
    <div class="title_bar">
        @if (isset($invoice['parent_name']) && $invoice['parent_name'])
            <p>「{{$invoice['parent_name']}} 様」</p>
        @endif
    </div>
    <div class="content_header"></div>
    <div class="content_detail">
        @if (isset($result) && $result)
            <p style="color: blue;text-align: center">お支払いが完了いたしました。ありがとうございました。</p>
        @else
            <p style="color: red;text-align: center">エーラがあるので、管理者にご連絡ください。</p>
        @endif
    </div>
    <div class="content_footer"></div>
@stop

