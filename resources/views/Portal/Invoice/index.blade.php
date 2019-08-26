@extends('_parts.portal.portal_layout')
<link type="text/css" rel="stylesheet" href="/css/school/invoice_print2.css" />

@section('content')
    <div class="content_header"></div>
    <div class="content_detail">
        @include('Portal.Invoice.bill_content')
    </div>
    <div class="content_footer"></div>
@stop

