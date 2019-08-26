<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/invoice.css" />
@extends('_parts.master_layout') @section('content')
    <style>
        table .table_data td{
            font-size: 12px;
            text-align: center;
        }

    </style>
    <div id="center_content_header" >
        <div class="c_content_header_left">
            <h2 class="float_left"><i class="fa fa-fax"></i>{{$lan::get('invoice_management_title')}}</h2><br/>
        </div>
    </div>
    <br>
	<div id="section_content">
        <br>
        {{--<table class="table1 tablesorter " id="data_table">--}}
            {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th class="text_title" style="width:20%; text-align: center">{{$lan::get('invoice_year_month_title')}}</th>--}}
                    {{--<th class="text_title" style="width:50%; text-align: center">{{$lan::get('invoice_information_title')}}</th>--}}
                    {{--<th class="text_title" style="width:30%; text-align: center">{{$lan::get('invoice_number_people_title')}}</th>--}}
                {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--@if(isset($invoice_list))--}}
                {{--@foreach ($invoice_list as $idx => $heads)--}}
                {{--<tr class="table_row">--}}
                    {{--<td style="width:20%; text-align: center">--}}
                        {{--<a id="test" class="text_link" href="{{$_app_path}}invoice/list?invoice_year_month={{array_get($heads, 'invoice_year_month')}}">--}}
                            {{--<p>{{Carbon\Carbon::parse(array_get($heads, 'invoice_year_month'))->format('Y年m月分請求書')}}	</p>--}}
                            {{--<p class="p10">--}}
                                {{--@if(array_get($heads, 'register_date'))--}}
                                    {{--{{$lan::get('create_date_title')}}：{{array_get($heads, 'register_date')}}--}}
                                {{--@endif</p>--}}
                        {{--</a>--}}
                    {{--</td>--}}
                    {{--<td style="width:50%; text-align: center">--}}
                        {{--<ul class="progress_ul">--}}
                            {{--@if(array_get($heads, 'cnt_entry'))--}}
                            {{--<li class="bill1">{{$lan::get('status_imported_title')}}[{{array_get($heads, 'cnt_entry')}}]</li>--}}
                            {{--@else--}}
                            {{--<li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>--}}
                            {{--@endif--}}
                            {{--@if(array_get($heads, 'cnt_confirm'))--}}
                            {{--<li class="bill2">{{$lan::get('confirmed_title')}}[{{array_get($heads, 'cnt_confirm')}}]</li>--}}
                            {{--@else--}}
                            {{--<li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>--}}
                            {{--@endif--}}
                            {{--@if(array_get($heads, 'cnt_send'))--}}
                            {{--<li class="bill3">{{$lan::get('invoiced_title2')}}[{{array_get($heads, 'cnt_send')}}]</li>--}}
                            {{--@else--}}
                            {{--<li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>--}}
                            {{--@endif--}}
                            {{--@if(array_get($heads, 'cnt_complete'))--}}
                            {{--<li class="bill4">{{$lan::get('payment_already_title')}}[{{array_get($heads, 'cnt_complete')}}]</li>--}}
                            {{--@else--}}
                            {{--<li class="bill4 no_active">{{$lan::get('not_payment_title')}}</li>--}}
                            {{--@endif--}}
                        {{--</ul>--}}
                    {{--</td>--}}
                    {{--<td style="width:30%; text-align:center;">--}}
                        {{--<p>{{$lan::get('billing_persons_title')}}{{array_get($heads, 'cnt_all')}}{{$lan::get('person_title')}}</p>--}}
                        {{--<p class="p10">{{$invoice_type[1]}}:{{array_get($heads, 'cnt_genkin')}}&nbsp;--}}
                            {{--{{$invoice_type[2]}}：{{array_get($heads, 'cnt_richo')}}&nbsp;--}}
                            {{--{{$lan::get('other_title')}}：{{array_get($heads, 'cnt_other')}}&nbsp;--}}
                        {{--</p>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--@endif--}}
            {{--@if(!isset($invoice_list))--}}
                {{--<tr class="table_row">--}}
                    {{--<td class="error_row">{{$lan::get('information_displayed_title')}}</td>--}}
                {{--</tr>--}}
            {{--@endif--}}
            {{--</tbody>--}}
        {{--</table>--}}
        @foreach($invoice_list as $month => $heads)

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading" @if(!isset($heads['count'][0]) &&  !isset($heads['count'][1]) && !isset($heads['count'][11]) && !isset($heads['count'][31])) style="background-color: rgba(201, 243, 223, 0.9); " @endif>
                        <table class="table_header" @if(!isset($heads['count'][0]) &&  !isset($heads['count'][1]) && !isset($heads['count'][11]) && !isset($heads['count'][31])) style="background-color: rgba(201, 243, 223,0.46);" @endif>
                            <colgroup>
                                <col width="30%">
                                <col width="30%">
                                <col width="5%">
                                <col width="30%">
                                <col width="5%">
                            </colgroup>
                            <tr>
                                <td><a class="text_link" href="{{$_app_path}}invoice/list?invoice_year_month={{$month}}"><p>{{date('Y年m月分請求書',strtotime($month))}}</p></a></td>
                                {{--<td>{{$lan::get('invoice_creation_count')}} @if(isset($heads['count']['cnt_entry'])) {{str_replace('~', '&nbsp;',str_pad(array_get($heads['count'], 'cnt_entry'), 4, '~', STR_PAD_LEFT))}}@else {{str_replace('~', '&nbsp;',str_pad(0, 4, '~', STR_PAD_LEFT))}}@endif{{$lan::get('item_title')}} &nbsp;{{$lan::get('invoice_total_amount_title')}} @if(isset($heads['amount']['cnt_entry_amount'])) {{str_replace('~', '&nbsp;',str_pad(number_format(array_get($heads['amount'], 'cnt_entry_amount')), 10, '~', STR_PAD_LEFT))}}@else {{str_replace('~', '&nbsp;',str_pad(number_format(0), 10, '~', STR_PAD_LEFT))}}@endif{{$lan::get('jap_yen_title')}} </td>--}}
                                <td>{{$lan::get('invoice_creation_count')}} @if(isset($heads['count']['cnt_entry'])) {{str_replace('~', '&nbsp;',str_pad(array_get($heads['count'], 'cnt_entry'), 4, '~', STR_PAD_LEFT))}}@else {{str_replace('~', '&nbsp;',str_pad(0, 4, '~', STR_PAD_LEFT))}}@endif{{$lan::get('item_title')}} &nbsp;{{$lan::get('invoice_total_amount_title')}} <div style="text-align: right; float: right" > @if(isset($heads['amount']['cnt_entry_amount'])) {{str_replace('~', '&nbsp;',str_pad(number_format(array_get($heads['amount'], 'cnt_entry_amount')), 10, '~', STR_PAD_LEFT))}}@else {{str_replace('~', '&nbsp;',str_pad(0, 10, '~', STR_PAD_LEFT))}}@endif{{$lan::get('jap_yen_title')}}</div> </td>
                                <td style="text-align: center">
                                    @if($month == $newest_month && !empty($heads['count']['export']))
                                        @foreach($heads['count']['export'] as $key => $val)
                                            @if($key == 'COMBINI')
                                                <img src="{{asset("img/school/combini-icon.png")}}" width="40px;" height="30px;"
                                                title="{{$lan::get('convenient_store_title')}}"> &nbsp;
                                            @elseif($key == 'YUUCHO')
                                                <img src="{{asset("img/school/yucho-icon.png")}}" width="40px;" height="30px;"
                                                title="{{$lan::get('owner_transfer_title')}}"> &nbsp;
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{$lan::get('last_creation_date')}}：{{array_get($heads, 'register_date')}}</td>
                                <td class="drop_down" data-toggle="collapse" href="#collapse{{$month}}"><i style="font-size:16px;" class="fa fa-chevron-down"></i></td>
                            </tr>
                        </table>
                    </div>
                    <div id="collapse{{$month}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            @if($month == $newest_month && !empty($heads['count']['export']))
                                <table class="content_accordion">
                                    @foreach($heads['count']['export'] as $key => $val)
                                        <tr>
                                            <th style="min-width: 200px !important; background-color:@if($key == 'COMBINI') {{$invoice_background_color[3]['top']}} @else {{$invoice_background_color[4]['top']}} @endif ;">
                                                @if($key == 'COMBINI')<a href="{{$_app_path}}invoice/ricohConvProc?invoice_year_month={{$newest_month}}" class="text_link">{{$lan::get('combini_export_inform_message')}}</a>
                                                @else <a href="{{$_app_path}}invoice/ricohPostProc?invoice_year_month={{$newest_month}}" class="text_link">{{$lan::get('yuucho_export_inform_message')}}</a>
                                                @endif
                                            </th>
                                            <td style="border: 1px solid black !important; background-color:@if($key == 'COMBINI') {{$invoice_background_color[3]['top']}} @else {{$invoice_background_color[4]['top']}} @endif ;">{{$val}}{{$lan::get('item_title')}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                <br/>
                            @endif
                            <table class="table_data" id="myTable">
                                @if(isset($heads['count'][0]) || isset($heads['count'][1]) || isset($heads['count'][11]) || isset($heads['count'][31]))
                                    <tr  class="row_status_entry row_invoice" style="border-bottom: solid">
                                        <td style="background-color: rgb(249, 188, 188); color : black;"><b>{{$lan::get('status_imported_title')}}<br/>{{$heads['count'][0]['total']}}{{$lan::get('item_title')}}<br/>{{number_format($heads['amount'][0]['total_amount'])}}円</b></td>
                                        <td style="background-color: rgb(249, 188, 188); color : black"><b>{{$lan::get('confirmed_title')}}<br/>{{$heads['count'][1]['total']}}{{$lan::get('item_title')}}<br/>{{number_format($heads['amount'][1]['total_amount'])}}円</b></td>
                                        <td style="background-color: rgb(249, 188, 188); color : black"><b>{{$lan::get('invoiced_title2')}}<br/>{{$heads['count'][11]['total']}}{{$lan::get('item_title')}}<br/>{{number_format($heads['amount'][11]['total_amount'])}}円</b></td>
                                        <td style="background-color: rgb(249, 188, 188); color : black"><b>{{$lan::get('payment_already_title')}}<br/>{{$heads['count'][31]['total']}}{{$lan::get('item_title')}}<br/>{{number_format($heads['amount'][31]['total_amount'])}}円</b></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table>
                                                @if(isset($heads['count'][0]) && isset($heads['count'][0]))
                                                    @foreach($heads['count'][0] as $payment => $count)
                                                        @foreach($heads['amount'][0] as $payment_amount => $count_amount)
                                                            @if($payment == $payment_amount)
                                                            <tr class="row_status_entry row_invoice">
                                                                @if(is_numeric($payment))
                                                                    <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} <br /> {{number_format($count_amount)}}円 </td>
                                                                @endif
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @foreach($heads['main'] as $payment => $count)
                                                        <tr class="row_status_entry row_invoice">
                                                            @if(is_numeric($payment))
                                                                <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                @if(isset($heads['count'][1]) && isset($heads['count'][1]))
                                                    @foreach($heads['count'][1] as $payment => $count)
                                                        @foreach($heads['amount'][1] as $payment_amount => $count_amount)
                                                            @if($payment == $payment_amount)
                                                                <tr class="row_status_confirm row_invoice">
                                                                    @if(is_numeric($payment))
                                                                        <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} <br /> {{number_format($count_amount)}}円  </td>
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @foreach($heads['main'] as $payment => $count)
                                                        <tr class="row_status_confirm row_invoice">
                                                            @if(is_numeric($payment))
                                                                <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                @if(isset($heads['count'][11]) && isset($heads['count'][11]))
                                                    @foreach($heads['count'][11] as $payment => $count)
                                                        @foreach($heads['amount'][11] as $payment_amount => $count_amount)
                                                            @if($payment == $payment_amount)
                                                                <tr class="row_status_confirm row_invoice">
                                                                    @if(is_numeric($payment))
                                                                        <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} <br /> {{number_format($count_amount)}}円  </td>
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @foreach($heads['main'] as $payment => $count)
                                                        <tr class="row_status_confirm row_invoice">
                                                            @if(is_numeric($payment))
                                                                <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                @if(isset($heads['count'][31]) && isset($heads['count'][31]))
                                                    @foreach($heads['count'][31] as $payment => $count)
                                                        @foreach($heads['amount'][31] as $payment_amount => $count_amount)
                                                            @if($payment == $payment_amount)
                                                                <tr class="row_status_confirm row_invoice" style="border-right: solid; border-width: 1px;">
                                                                    @if(is_numeric($payment))
                                                                        <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} <br /> {{number_format($count_amount)}}円  </td>
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @foreach($heads['main'] as $payment => $count)
                                                        <tr class="row_status_confirm row_invoice">
                                                            @if(is_numeric($payment))
                                                                <td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white; border-top: none !important; border-right: none !important;">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </table>
                                        </td>
                                    </tr>

                                    {{--<tr class="row_status_entry row_invoice">--}}
                                        {{--@if(isset($heads[0]))--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('status_imported_title')}}<br/>{{$heads[0]['total']}}{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads[0] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@else--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('status_imported_title')}}<br/>0{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads['main'] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}

                                    {{--<tr class="row_status_confirm row_invoice">--}}
                                        {{--@if(isset($heads[1]))--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('confirmed_title')}}<br/>{{$heads[1]['total']}}{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads[1] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@else--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('confirmed_title')}}<br/>0{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads['main'] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}
                                    {{--</tr>--}}
                                    {{--<tr class="row_status_send row_invoice">--}}
                                        {{--@if(isset($heads[11]))--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('invoiced_title2')}}<br/>{{$heads[11]['total']}}{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads[11] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@else--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('invoiced_title2')}}<br/>0{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads['main'] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}
                                    {{--</tr>--}}
                                    {{--<tr class="row_status_complete row_invoice">--}}
                                        {{--@if(isset($heads[31]))--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('payment_already_title')}}<br/>{{$heads[31]['total']}}{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads[31] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>{{$count}}{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@else--}}
                                            {{--<td style="background-color: rgb(249, 188, 188); color : white">{{$lan::get('payment_already_title')}}<br/>0{{$lan::get('item_title')}}</td>--}}
                                            {{--@foreach($heads['main'] as $payment => $count)--}}
                                                {{--@if(is_numeric($payment))--}}
                                                    {{--<td style="background-color: {{$invoice_background_color[$payment]['top']}}; color : white">{{$invoice_type[$payment]}}<br/>0{{$lan::get('item_title')}} </td>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}
                                    {{--</tr>--}}
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
	</div>
     <script>
         $(function () {
             $(".drop_down").click(function(e){
                 e.preventDefault();

                 if($(this).children().hasClass("fa-chevron-down")){
                     $(this).children().removeClass("fa-chevron-down");
                     $(this).children().addClass("fa-chevron-up");
                 }else if($(this).children().hasClass("fa-chevron-up")){
                     $(this).children().removeClass("fa-chevron-up");
                     $(this).children().addClass("fa-chevron-down");
                 }
             });
         })
     </script>
@stop
