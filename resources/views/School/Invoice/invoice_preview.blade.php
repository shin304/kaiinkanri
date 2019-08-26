@extends('_parts.master_layout') @section('content')
<script type="text/javascript">
    $(function () {
        $("#show_info").click(function () {
            $("#action_form").submit();
        })
    });
</script>

<style>
    .text-right {
        text-align: right;
        padding-right: 20px;
    }
    .table2 th {
        text-align: center;
    }
</style>
@include('_parts.invoice.invoice_menu')
<form id="action_form" method="post" action="/school/invoice/edit">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$request['id']}}"/>
        <input type="hidden" name="invoice_year_month" value="{{$request['invoice_year_month']}}"/>
        <input type="hidden" name="current_student" value="{{$item_list['current_student']}}"/>
        <input type="hidden" name="preview_bool" value=""/>
    <div class="content_header">
        <div id="section_content_in">
            <div class="billing_top clearfix">
                <div class="w5 float_left">
                    <table width="100%">
                        <colgroup>
                            <col width="28%"/>
                            <col width="2%"/>
                            <col width="70%"/>
                        </colgroup>
                        <tr>
                            <td>{{$lan::get('billing_title')}}</td>
                            <td>:</td>
                            <td>{{array_get($data,'parent_name')}}</td>
                        </tr>
                        <tr>
                            <td>{{$lan::get('member_name_title')}}</td>
                            <td>:</td>
                            <td>
                                @if(array_get($data,'student_list'))
                                    @foreach (array_get($data,'student_list') as $student_row)
                                        {{array_get($student_row,'student_no')}} {{array_get($student_row,'student_name')}}
                                        <br/>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{$lan::get('invoice_year_month_title')}}</td>
                            <td>:</td>
                            <td>
                                @if(isset($request['invoice_year_month']))
                                    {{Carbon\Carbon::parse($request['invoice_year_month'])->format('Y年m月')}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{$lan::get('status_title')}}</td>
                            <td>:</td>
                            <td>
                                @if(isset($workflow_status_list) && isset($request['workflow_status']))
                                    {{$workflow_status_list[$request['workflow_status']]}}
                                @elseif( $data['is_established'] == "1")
                                    {{$lan::get('confirmed_title')}}
                                    <input type="hidden" name="is_established" value="1"/>
                                @else
                                    {{$lan::get('editing_title')}}
                                    <input type="hidden" name="is_established" value="0"/>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{$lan::get('notification_method_title')}}</td>
                            <td>:</td>
                            <td>
                                @if(isset($data['mail_infomation']))
                                    @if($data['mail_infomation'] == "1")
                                        {{$lan::get('e_mail_title')}}
                                    @else
                                        {{$lan::get('mail_title')}}
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{$lan::get('invoice_method_title')}}
                            </td>
                            <td>:</td>
                            <td>
                                @if(isset($invoice_type) && $data['invoice_type'] !== null)
                                    {{$invoice_type[$data['invoice_type']]}}
                                @endif
                            </td>
                        </tr>
                        @if(isset($request['invoice_paid_type']))
                            <tr>
                                <td>{{$lan::get('payment_method_search_title')}}</td>
                                <td>:</td>
                                <td>
                                    @if(isset($invoice_type) and isset($request['invoice_paid_type']))
                                        {{$invoice_type[$request['invoice_paid_type']]}}
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if(isset($request['invoice_paid_date']))
                            <tr>
                                <td>
                                    {{$lan::get('payment_date_title')}}
                                </td>
                                <td>
                                    :
                                </td>
                                <td>
                                    {{Carbon\Carbon::parse($request['invoice_paid_date'])->format('Y年m月d日')}}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                {{$lan::get('deadline_payment_title')}}
                            </td>
                            <td>:</td>
                            <td>
                                @if(isset($data['due_date']))
                                    {{Carbon\Carbon::parse($data['due_date'])->format('Y年m月d日')}}
                                @endif
                            </td>
                        </tr>
                    </table>
                    <button id="show_info" class="mt10">{{$lan::get('back_to_list_title')}}</button>
                </div>
                <div class="w5 float_right">
                    <textarea placeholder="{{$lan::get('personal_note_title')}}" class="textarea2"
                              disabled>{{$request['parent_memo']}}</textarea>
                </div><!--w5 float_right-->
            </div>{{--billing_top--}}
            <div id="bill_info" style="display: none">
                <table class="table2 adjust_name_table">
                    <tr>
                        <th class="text_title header" style="width:40%;">{{$lan::get('items_title')}}</th>
                        <th class="text_title header" style="width:20%;"></th>
                        <th class="text_title header" style="width:10%;">{{$lan::get('payment_method_search_title')}}</th>
                        <th class="text_title header" style="width:20%;">{{$lan::get('payment_date_title')}}</th>
                        <th class="text_title header" style="width:10%;">{{$lan::get('money_amount_title')}}</th>
                    </tr>

                {{--input resend bill info --}}
                    {{--for debit item--}}
                    @if(!empty($debit_data))
                        @foreach($debit_data as $k => $debit)
                            <tr>
                                <td>
                                    <input type="checkbox" class="chkbox_debit" data-amount="{{array_get($debit,'amount')}}" data-item_name ="{{date('Y年m月',strtotime(array_get($debit,'debit_year_month').'-01')).'分 未入金'}}"
                                           name ="debit_id[]" value="{{array_get($debit,'invoice_debit_id')}}" checked>
                                </td>
                                {{--<td>--}}

                                {{--</td>--}}
                                {{--<td>--}}
                                {{--{{date('m月d日',strtotime(array_get($debit,'due_date')))}}--}}
                                {{--</td>--}}
                                <td style="text-align: right">
                                    \{{number_format(array_get($debit,'amount'))}}
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    {{--for class item--}}
                    @foreach($data['student_list'] as $k=>$student_row)
                        @if(isset($item_list['class_name'][$student_row['id']]))
                            @foreach ($item_list['class_name'][$student_row['id']] as $k=> $name)
                                <tr>
                                    <td>
                                        <div class="grayout">{{$name}}</div>
                                    </td>
                                    <td>
                                        <div class="grayout" style="height: 41px;!important;">
                                            <input type="checkbox" class="item_except" name="class_except[{{$student_row['id']}}][]" value="1" @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k]==1) checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
                                        </div>
                                    </td>
                                    <td style="text-align: center">
                                        <div class="grayout">{{$item_list['payment_method_name']}}&nbsp;</div>
                                    </td>
                                    <td style="text-align: center">
                                        <div class="grayout">{{Carbon\Carbon::parse($item_list['due_date'])->format('m月d日')}}&nbsp;</div>
                                    </td>
                                    <td align="center">
                                        <div class="grayout text-right">
                                            @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k] ==1)
                                                {{$lan::get('invocie_outside_title')}}
                                            @else
                                                \{{number_format($item_list['class_price'][$student_row['id']][$k])}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    {{--end class item--}}

                    {{--for event(course) item--}}
                    @foreach($data['student_list'] as $student_row)
                        @if(isset($item_list['course_name'][$student_row['id']]))
                            @foreach($item_list['course_name'][$student_row['id']] as $k=> $name)

                                <tr>
                                    <td>
                                        <div class="grayout">{{$name}}</div>
                                    </td>
                                    <td>
                                        <div class="grayout" style="height: 41px;!important;">
                                            <input type="checkbox" class="item_except" name="course_except[{{$student_row['id']}}][]" value="1" @if(isset($item_list['_course_except'][$student_row['id']][$k]) && $item_list['_course_except'][$student_row['id']][$k]==1 ) checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
                                        </div>
                                    </td>
                                    <td><div class="grayout">&nbsp;</div></td>
                                    <td><div class="grayout">&nbsp;</div></td>
                                    <td align="center">
                                        <div class="grayout text-right">
                                            @if(isset($item_list['_course_except'][$student_row['id']][$k]) && $item_list['_course_except'][$student_row['id']][$k]==1)
                                                {{$lan::get('invocie_outside_title')}}
                                            @else
                                                \{{number_format($item_list['course_price'][$student_row['id']][$k])}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                        @endif
                    @endforeach
                    {{--end event item--}}

                    {{--program item--}}
                    @foreach($data['student_list'] as $student_row)
                        @if(isset($item_list['program_name'][$student_row['id']]))
                            @foreach($item_list['program_name'][$student_row['id']] as $k=>$name)
                                <tr>
                                    <td>
                                        <div class="grayout">{{$name}}</div>
                                    </td>
                                    <td>
                                        <div class="grayout" style="height: 41px;!important;">
                                            <input type="checkbox" class="item_except" name="program_except[{{$student_row['id']}}][]" value="1" @if(isset($item_list['_program_except'][$student_row['id']][$k]) && $item_list['_program_except'][$student_row['id']][$k]==1)checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
                                        </div>
                                    </td>
                                    <td><div class="grayout">&nbsp;</div></td>
                                    <td><div class="grayout">&nbsp;</div></td>
                                    <td align="center">
                                        <div class="grayout text-right">
                                            @if($item_list['_program_except'][$student_row['id']][$k])
                                                {{$lan::get('invocie_outside_title')}}
                                            @else
                                                \{{number_format($item_list['program_price'][$student_row['id']][$k])}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    {{--end program item--}}

                    {{--custom_item--}}
                    @foreach($data['student_list'] as $student_row)
                        @if(isset($item_list['custom_item_name'][$student_row['id']]))
                            @foreach ($item_list['custom_item_name'][$student_row['id']] as $k => $v)
                                @if(isset($item_list['custom_item_id'][$student_row['id']][$k]))
                                    <tr>
                                        <td>
                                            <div class="grayout">
                                                <input type="text" style="ime-mode:inactive; text-align:left;" name="custom_item_name[{{$student_row['id']}}][{{$k}}][name]" value="{{$v}}">
                                            </div>
                                        </td>
                                        <td><div class="grayout" style="text-align:center">
                                                <input type="checkbox" class="item_except" name="custom_except[{{$student_row['id']}}][{{$k}}]" value="1" @if(isset($item_list['_custom_except'][$student_row['id']][$k]) && $item_list['_custom_except'][$student_row['id']][$k]==1)checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
                                            </div>
                                        </td>
                                        <td style="text-align: center">
                                            @if($item_list['custom_item_price'][$student_row['id']][$k]>0)
                                                    <div class="grayout">{{Carbon\Carbon::parse($item_list['due_date'])->format('m月d日')}}&nbsp;</div>
                                            @else <div class="grayout">&nbsp;</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="grayout text-right">
                                                {{--@if(is_numeric($item_list['custom_item_price'][$student_row['id']][$k]))--}}
                                                    {{--@if($item_list['custom_item_price'][$student_row['id']][$k] < 0)--}}
                                                        {{---\{{number_format(str_replace("-","",$item_list['custom_item_price'][$student_row['id']][$k]))}}--}}
                                                    {{--@else}}--}}
                                                    {{--\{{number_format($item_list['custom_item_price'][$student_row['id']][$k])}}--}}
                                                    {{--@endif}}--}}
                                                {{--@else--}}
                                                    {{--&nbsp;--}}
                                                {{--@endif--}}
                                                <input type="text" style="ime-mode:inactive; text-align:right;" name="custom_item_price[{{$student_row['id']}}][{{$k}}]" value="{{$item_list['custom_item_price'][$student_row['id']][$k]}}">
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <input type="text" style="ime-mode:inactive; text-align:left;" name="custom_item[{{$student_row['id']}}][{{$k}}][name]" value="{{$item_list['custom_item_name'][$student_row['id']][$k]}}">
                                    <input type="text" style="ime-mode:inactive; text-align:left;" name="custom_item[{{$student_row['id']}}][{{$k}}][price]" value="{{$item_list['custom_item_price'][$student_row['id']][$k]}}">
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    {{--end custom item--}}

                    {{--discount item--}}
                    @if(isset($item_list['discount_id']))
                        @foreach($item_list['discount_id'] as $k => $v)
                            <tr>
                                <td>
                                    <div class="grayout">
                                        {{$item_list['discount_name'][$k]}}
                                    </div>
                                </td>
                                <td><div class="grayout">&nbsp;</div></td>
                                <td style="text-align: center">
                                    <div class="grayout">&nbsp;
                                    </div>
                                </td>
                                <td style="text-align: center">
                                    <div class="grayout">{{Carbon\Carbon::parse($item_list['due_date'])->format('m月d日')}}&nbsp;</div>
                                </td>
                                <td align="center">
                                    <div class="grayout text-right">
                                        @if(is_numeric($item_list['discount_price'][$k]))
                                            @if ($item_list['discount_price'][$k] < 0)
                                                -\{{number_format(str_replace("-","",$item_list['discount_price'][$k]))}}
                                            @else
                                                {{number_format($item_list['discount_price'][$k])}}
                                            @endif
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    {{--end discount item--}}

                    {{--discount item edit--}}
                    {{--@if(isset($request['custom_item']))--}}
                        {{--@foreach($request['custom_item'] as $key=>$value)--}}
                            {{--@foreach($value as $k=>$v)--}}
                                {{--<tr>--}}
                                    {{--<td>--}}
                                        {{--<div class="grayout">--}}
                                            {{--<input type="text" style="ime-mode:inactive; text-align:left;" name="custom_item[{{$key}}][{{$k}}][name]" value="{{$v['name']}}">--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                    {{--<td style="text-align: center; height: 50px!important;">--}}
                                        {{--<div class="grayout">--}}
                                            {{--<button style="font-size: 14px;" class="remove_custom_item">削除&nbsp;</button>--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                    {{--<td><div class="grayout" style="height: 100%;text-align:center; padding: 15px;">&nbsp;</div></td>--}}
                                    {{--<td><div class="grayout" style="height: 100%;text-align:center; padding: 15px;">&nbsp;</div></td>--}}
                                    {{--<td><div class="grayout">--}}
                                            {{--<input type="text" style="ime-mode:inactive; text-align:left;" name="custom_item[{{$key}}][{{$k}}][price]" value="{{$v['price']}}">--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--@if(isset($request['error_custom_item']))--}}
                                {{--@foreach($request['error_custom_item'] as $k=>$v)--}}
                                    {{--<tr>--}}
                                        {{--<td class="error_row">--}}
                                            {{--<ul class="message_area">--}}
                                                {{--<li class="error_message">{{$lan::get($v)}}</li>--}}
                                            {{--</ul>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                </table>
            </div>
            {{--end bill info--}}

            {{--bill preview--}}
            <div id="bill_preview">
                <div id="bill_content" style="font-weight: bold">
                    <div class="bill_header_top">
                        <div style="width: 90mm; height: 45mm; padding-left: 35px; padding-top: 15px; float: left">
                            〒@if(array_get($data, 'other_address_flag') == 1)
                                @if(isset($data['zip_code1_other']) && isset($data['zip_code2_other']))
                                    {{array_get($data, 'zip_code1_other')}}-{{array_get($data, 'zip_code2_other')}}
                                @endif
                            @else
                                @if(isset($data['zip_code1']) && isset($data['zip_code2']))
                                    {{array_get($data, 'zip_code1')}}-{{array_get($data, 'zip_code2')}}
                                @endif
                            @endif<br>
                            @if(array_get($data, 'other_address_flag') == 1){{array_get($data, 'address_other')}}@else{{array_get($data, 'pref_name')}}{{array_get($data, 'city_name')}}{{array_get($data, 'address')}}@endif<br>
                            @if(array_get($data, 'other_address_flag') == 1 && isset($data['building_other'])){{array_get($data, 'building_other')}}<br>
                            @elseif (array_get($data, 'other_address_flag') == 0 && isset($data['building'])){{array_get($data, 'building')}}<br>
                            @endif
                            {{array_get($data,'parent_name')}} 様<br>
                        </div>
                        <div style="float: right">{{$lan::get('invoice_issue_date')}}{{date('Y年m月d日')}}</div>
                        <div style="clear:both"></div>
                    </div>
                    <div id="bill_header">
                        <h1 style="text-decoration: underline">{{$lan::get('invoice_header_title')}}</h1>
                    </div>
                    <section id="bill_info">
                        <div class="bill_info_left">
                            {{--<p class="company_name">{{$item_list['parent_name']}}  {{$lan::get('mr_title')}}</p>--}}
                            {{--<p class="bill_p1">{{$lan::get('pay_with_following_invoice_info_title')}}</p>--}}
                            <table style="font-weight: bold">
                                <tbody>
                                @if($item_list['amount_tax'])
                                    <tr>
                                        <td>{{$lan::get('your_invoice_amount_title')}}</td>
                                        <td>:</td>
                                        <td>&yen;{{number_format($item_list['amount_tax'])}}</td>
                                    </tr>
                                @endif
                                @if(isset($item_list['due_date_jp']))
                                    <tr>
                                        @if($item_list['invoice_type'] == 2 )
                                            <td>{{$lan::get('kozafurikae_deadline')}}</td><td>:</td><td>{{$item_list['due_date_jp']}}</td>
                                        @else
                                            <td>{{$lan::get('deadline_payment_title')}}</td><td>:</td><td>{{$item_list['due_date_jp']}}</td>
                                        @endif
                                    </tr>
                                @endif
                                @if(isset($invoice_type) && $item_list['invoice_type'] !== null)
                                    <tr><td>{{$lan::get('dp_payment_method')}}</td><td>:</td><td>{{$invoice_type[$item_list['invoice_type']]}}</td></tr>
                                    @endif
                                    </p>
                                </tbody>
                            </table>
                        </div>
                        <div class="bill_info_right">
                            @if($item_list['school_zipcode_1'] && $item_list['school_zipcode_2'])<p class="my_company_name">〒{{$item_list['school_zipcode_1']}}-{{$item_list['school_zipcode_2']}}</p>@endif
                            <p class="my_company_address">
                                {{$item_list['pref_name']}}{{$item_list['city_name']}}{{$item_list['school_address']}}
                            </p>
                            @if($item_list['school_building'])<p>{{$item_list['school_building']}}</p>@endif
                            @if($item_list['school_name'])<p class="my_company_name">{{$item_list['school_name']}}</p>@endif
                            @if($item_list['school_tel'])<p class="my_company_tel">TEL {{$item_list['school_tel']}}</p>@endif
                            @if($item_list['school_mail'])<p class="my_company_tel">{{$item_list['school_mail']}}</p>@endif
                            @if($item_list['school_daihyou'])
                            <p class="my_company_daihyou">
                                @if($item_list['school_official_position'])
                                {{$item_list['school_official_position']}}
                                @endif
                                {{$item_list['school_daihyou']}}
                            </p>
                            @endif
                            <!--will change when have real kakuin image-->
                            <img src="{{$item_list['kakuin_path']}}" style="position:relative; opacity:0.8; top:-65px; width: 70px;height: 70px;float: right">
                        </div>
                        <div class="clr"></div>
                    </section>

                    <table id="bill_table" style="font-weight: bold">
                        <tr>
                            <th style="width: 80%">{{$lan::get('content_title')}}</th>
                            {{--<th style="width: 15%">{{$lan::get('payment_method_title')}}</th>--}}
                            {{--<th style="width: 15%">{{$lan::get('payment_date_title')}}</th>--}}
                            <th style="width: 20%">{{$lan::get('money_amount_title')}}</th>
                        </tr>

                        {{--class item--}}
                        @foreach($item_list['student_list'] as $student_row)
                            @if(isset($item_list['class_name'][$student_row['id']]))
                                @foreach($item_list['class_name'][$student_row['id']] as $k => $name)
                                    <tr>
                                        <td>
                                            {{$name}}
                                        </td>
                                        {{--<td>--}}
                                                {{--{{$item_list['payment_method_name']}}--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                                {{--{{date('m月d日',strtotime($item_list['due_date']))}}--}}
                                        {{--</td>--}}
                                        <td class="td2">
                                            @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k] ==1)
                                                {{$lan::get('invocie_outside_title')}}
                                            @elseif($item_list['class_price'][$student_row['id']][$k])
                                                &yen;{{number_format($item_list['class_price'][$student_row['id']][$k])}}
                                            @else
                                                &yen;0
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        {{--end class item--}}


                        {{-- event item --}}
                        @foreach ($item_list['student_list'] as $student_row)
                            @if(isset($item_list['course_name'][$student_row['id']]))
                                @foreach($item_list['course_name'][$student_row['id']] as $k => $name)
                                <tr>
                                    <td>
                                        {{$name}}
                                    </td>
                                    {{--<td></td>--}}
                                    {{--<td></td>--}}
                                    <td class="td2">
                                        @if(isset($item_list['_course_except'][$student_row['id']][$k]) && $item_list['_course_except'][$student_row['id']][$k]==1)
                                            {{$lan::get('invocie_outside_title')}}
                                        @elseif ($item_list['course_price'][$student_row['id']][$k])
                                            &yen;{{number_format($item_list['course_price'][$student_row['id']][$k])}}
                                        @else
                                            &yen;0
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        @endforeach
                        {{-- end event item --}}

                        {{--program item--}}
                        @foreach($item_list['student_list'] as $student_row)
                            @if(isset($item_list['program_name'][$student_row['id']]))
                                @foreach($item_list['program_name'][$student_row['id']] as $k => $name)
                                <tr>
                                    <td>
                                        {{$name}}
                                    </td>
                                    {{--<td></td>--}}
                                    {{--<td></td>--}}
                                    <td class="td2">
                                    @if ($item_list['_program_except'][$student_row['id']][$k])
                                        {{$lan::get('invocie_outside_title')}}
                                    @elseif($item_list['program_price'][$student_row['id']][$k])
                                        &yen;{{number_format($item_list['program_price'][$student_row['id']][$k])}}
                                    @else
                                        &yen;0
                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        @endforeach
                        {{-- end program item--}}

                        {{--custom_item_old--}}
                        @php
                            $total_price=0;
                        @endphp
                        @foreach($data['student_list'] as $student_row)
                            @if(isset($item_list['custom_item_name'][$student_row['id']]))
                                @foreach ($item_list['custom_item_name'][$student_row['id']] as $k => $v)
                                    <tr>
                                        <td>
                                            {{$item_list['custom_item_name'][$student_row['id']][$k]}}
                                        </td>
                                        {{--<td>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                            {{--@if($item_list['custom_item_price'][$student_row['id']][$k]>0)--}}

                                                    {{--{{Carbon\Carbon::parse($item_list['due_date'])->format('m月d日')}}&nbsp;--}}
                                            {{--@else &nbsp;--}}
                                            {{--@endif--}}
                                        {{--</td>--}}
                                        <td class="td2">
                                            @if (isset($item_list['_custom_except'][$student_row['id']][$k]) && $item_list['_custom_except'][$student_row['id']][$k]==1)
                                                {{$lan::get('invocie_outside_title')}}
                                            @else
                                                @if(is_numeric($item_list['custom_item_price'][$student_row['id']][$k]))
                                                    @if($item_list['custom_item_price'][$student_row['id']][$k] < 0)
                                                        -\{{number_format(str_replace("-","",$item_list['custom_item_price'][$student_row['id']][$k]))}}
                                                    @else
                                                    \{{number_format($item_list['custom_item_price'][$student_row['id']][$k])}}
                                                    @endif
                                                    @php
                                                        $total_price=$total_price+$item_list['custom_item_price'][$student_row['id']][$k]
                                                    @endphp
                                                @else
                                                    &nbsp;
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        {{--end custom item--}}

                        {{--current discount item--}}
                        @if(isset($item_list['discount_id']))
                            @foreach($item_list['discount_id'] as $k => $v)
                                <tr>
                                    <td>
                                        {{$item_list['discount_name'][$k]}}
                                    </td>
                                    {{--<td>--}}
                                        {{--@if ($item_list['discount_price'][$k] > 0)--}}
                                            {{--{{$item_list['class_payment_method'][$student_row['id']][$k]}}--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--@if ($item_list['discount_price'][$k] > 0)--}}
                                               {{--{{Carbon\Carbon::parse($item_list['due_date'])->format('m月d日')}}&nbsp;--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    <td class="td2">
                                            @if(is_numeric($item_list['discount_price'][$k]))
                                                @if ($item_list['discount_price'][$k] < 0)
                                                    -\{{number_format(str_replace("-","",$item_list['discount_price'][$k]))}}
                                                @else
                                                    {{number_format($item_list['discount_price'][$k])}}
                                                @endif
                                            @else
                                                &nbsp;
                                            @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{--end discount item--}}

                        {{--Debit info--}}
                        @if(!empty($debit_data))
                            @foreach($debit_data as $k => $debit)
                                <tr>
                                    <td>
                                        {{date('Y年m月',strtotime(array_get($debit,'debit_year_month').'-01')).'分 未入金'}}
                                    </td>
                                    {{--<td>--}}

                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--{{date('m月d日',strtotime(array_get($debit,'due_date')))}}--}}
                                    {{--</td>--}}
                                    <td style="text-align: right">
                                        \{{number_format(array_get($debit,'amount'))}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <div style="float: right">
                        <table id="pay_table2" style="font-weight: bold">
                            <tr>
                                <th class="th1">{{$lan::get('subtotal_title')}}</th>
                                <td class="td2">
                                    @if($item_list['amount'] >= 0)
                                    &yen;{{number_format($item_list['amount'])}}
                                    @else -&yen;{{number_format(str_replace("-","",$item_list['amount']))}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                @if($item_list['amount_display_type'] == 0)
                                    <th class="th1">{{$lan::get('consumption_taxes_in_title')}}
                                        @if($item_list['sales_tax_disp']) {{$item_list['sales_tax_disp']}}%)
                                        @endif
                                    </th>
                                @else
                                    <th class="th1">{{$lan::get('consumption_taxes_out_title')}}
                                        @if($item_list['sales_tax_disp']) {{$item_list['sales_tax_disp']}}%)
                                        @endif
                                    </th>
                                @endif
                                <td class="td2">
                                    @if($item_list['tax_price'] >= 0 )&yen;{{number_format($item_list['tax_price'])}}
                                    @else  -&yen;{{number_format(str_replace("-","",$item_list['tax_price']))}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="th1">{{$lan::get('total_title')}}</th>
                                <td class="td2">
                                    @if($item_list['amount_tax'] >= 0 )&yen;{{number_format($item_list['amount_tax'])}}
                                    @else -&yen;{{number_format(str_replace("-","",$item_list['amount_tax']))}}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="clr"></div>
                    {{--@if $item_list['pbank_info && $item_list['invoice_type == 3}}--}}
                    {{--<div class="bank_account">{{$payee_title}}<br/>{{$item_list['pbank_info}}</div>--}}
                    {{--@endif--}}
                    <br/>
                </div>
            </div>
            {{--end bill preview--}}
        </div>
    </div>
</form>
@stop