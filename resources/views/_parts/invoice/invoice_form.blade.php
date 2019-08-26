<script type="text/javascript">
    $(function () {
        $('#show_preview').click(function () {
            $('#bill_info').hide();
            $('#bill_preview').show();
            $('#debit_invoice_info').hide();
            $('#show_preview').hide();
            $('#show_info').show();
            return false;
        });

        $('#show_info').click(function () {
            $('#bill_info').show();
            $('#bill_preview').hide();
            $('#debit_invoice_info').show();
            $('#show_preview').show();
            $('#show_info').hide();
            return false;
        });
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
                        <td>{{array_get($item_list,'parent_name')}}</td>
                    </tr>
                    <tr>
                        <td>{{$lan::get('member_name_title')}}</td>
                        <td>:</td>
                        <td>
                            @if(array_get($item_list,'student_list'))
                                @foreach (array_get($item_list,'student_list') as $student_row)
                                    {{array_get($student_row,'student_no')}} {{array_get($student_row,'student_name')}}&nbsp;{{array_get($student_row,'student_type_name')}}
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
                            @elseif( $item_list['is_established'] == "1")
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
                            @if(isset($item_list['mail_infomation']))
                                @if($item_list['mail_infomation'] == "1")
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
                            @if(isset($invoice_type) && $item_list['invoice_type'] !== null)
                                {{$invoice_type[$item_list['invoice_type']]}}
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
                            @if(isset($invoice_type) && $item_list['invoice_type'] !== null)
                                @if($item_list['invoice_type'] == 2 )
                                    {{$lan::get('kozafurikae_deadline')}}
                                @else
                                    {{$lan::get('deadline_payment_title')}}
                                @endif
                            @endif
                        </td>
                        <td>:</td>
                        <td>
                            @if(isset($item_list['due_date']))
                                {{Carbon\Carbon::parse($item_list['due_date'])->format('Y年m月d日')}}
                            @endif
                        </td>
                    </tr>
                </table>
                <button id="show_preview" class="mt10">{{$lan::get('preview_title')}}</button>
                <button id="show_info" class="mt10" style="display:none;">{{$lan::get('back_to_list_title')}}</button>
            </div>
            <div class="w5 float_right">
                <textarea placeholder="{{$lan::get('personal_note_title')}}" class="textarea2"
                          disabled>{{$request['parent_memo']}}</textarea>
            </div><!--w5 float_right-->
        </div>{{--billing_top--}}

        {{--DEBIT INFO--}}
        @if(isset($debit_data) && !empty($debit_data))
        <div id="debit_invoice_info">
            <p><b>未入金リスト</b></p>
            <table class="table2">
                <tr>
                    {{--<th class="text_title header" style="width:5%;"> </th>--}}
                    <th class="text_title header" style="width:70%;">{{$lan::get('items_title')}}</th>
                    <th class="text_title header" style="width:30%;">{{$lan::get('money_amount_title')}}</th>
                    {{--<th class="text_title header" style="width:30%;">{{$lan::get('payment_date_title')}}</th>--}}
                </tr>
                @foreach($debit_data as $k => $debit)
                    <tr>
                        {{--<td style="text-align: center; padding : 15px;">--}}
                            {{--<input type="checkbox" name ="debit_id[]" value="{{array_get($debit,'invoice_debit_id')}}">--}}
                        {{--</td>--}}
                        <td style="padding : 15px;">
                            <a style="color:red" href="{{$_app_path}}invoice/detail?id={{array_get($debit,'invoice_debit_id')}}&invoice_year_month={{array_get($debit,'debit_year_month')}}"
                               target="_blank">{{date('Y年m月',strtotime(array_get($debit,'debit_year_month').'-01')).'分'}}</a>
                        </td>
                        <td style="text-align: right ; padding : 15px; color:red">
                            \{{number_format(array_get($debit,'amount'))}}
                        </td>
                        {{--<td style="padding : 15px; color:red">--}}
                            {{--{{date('Y年m月d日',strtotime(array_get($debit,'due_date')))}}--}}
                        {{--</td>--}}
                    </tr>
                @endforeach
            </table>
        </div>
        @endif
        {{--BILL INFO--}}
        <div id="bill_info"  style="margin-top: 5px;">
            <p><b>請求情報</b></p>
            <table class="table2">
                <tr>
                    <th class="text_title header" style="width:70%;">{{$lan::get('items_title')}}</th>
                    {{--<th class="text_title header" style="width:10%;">{{$lan::get('payment_method_search_title')}}</th>--}}
                    {{--<th class="text_title header" style="width:20%;">{{$lan::get('payment_date_title')}}</th>--}}
                    <th class="text_title header" style="width:30%;">{{$lan::get('money_amount_title')}}</th>
                </tr>

            {{--for class item--}}
            @foreach($item_list['student_list'] as $k=>$student_row)
                @if(isset($item_list['class_name'][$student_row['id']]))
                    @foreach ($item_list['class_name'][$student_row['id']] as $k=> $name)
                        <tr>
                            <td>
                                <div class="grayout">{{$name}}</div>
                            </td>
                            {{--<td style="text-align: center">--}}
                                {{--<div class="grayout">{{$item_list['payment_method_name']}}&nbsp;</div>--}}
                            {{--</td>--}}
                            {{--<td style="text-align: center">--}}
                                {{--<div class="grayout">{{date('m月d日',strtotime($item_list['due_date']))}}&nbsp;</div>--}}
                            {{--</td>--}}
                            <td align="center">
                                <div class="grayout text-right">
                                    @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k] == 1)
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
            @foreach($item_list['student_list'] as $student_row)
                @if(isset($item_list['course_name'][$student_row['id']]))
                    @foreach($item_list['course_name'][$student_row['id']] as $k=> $name)

                    <tr>
                        <td>
                            <div class="grayout">{{$name}}</div>
                        </td>
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
                        <td align="center">
                            <div class="grayout text-right">
                                @if(isset($item_list['_course_except'][$student_row['id']][$k]) && $item_list['_course_except'][$student_row['id']][$k] == 1)
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
            @foreach($item_list['student_list'] as $student_row)
                @if(isset($item_list['program_name'][$student_row['id']]))
                    @foreach($item_list['program_name'][$student_row['id']] as $k=>$name)
                    <tr>
                        <td>
                            <div class="grayout">{{$name}}</div>
                        </td>
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
                        <td align="center">
                            <div class="grayout text-right">
                                @if(isset($item_list['_program_except'][$student_row['id']][$k]) && $item_list['_program_except'][$student_row['id']][$k] == 1)
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
            @foreach($item_list['student_list'] as $student_row)
                @if(isset($item_list['custom_item_id'][$student_row['id']]))
                    @foreach ($item_list['custom_item_id'][$student_row['id']] as $k => $v)

                        <tr>
                            <td>
                                <div class="grayout">
                                    {{$item_list['custom_item_name'][$student_row['id']][$k]}}
                                </div>
                            </td>
                            {{--<td><div class="grayout" style="text-align:center">--}}
                                    {{--@if($item_list['custom_item_price'][$student_row['id']][$k]>0 && isset($item_list['payment_method_name']))--}}
                                        {{--{{$item_list['payment_method_name']}}--}}
                                    {{--@else &nbsp;--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</td>--}}
                            {{--<td style="text-align: center">--}}
                                {{--@if($item_list['custom_item_price'][$student_row['id']][$k]>0)--}}
                                    {{--<div class="grayout">{{date('m月d日',strtotime($item_list['due_date']))}}&nbsp;</div>--}}
                                {{--@else--}}
                                    {{--<div class="grayout">&nbsp;</div>--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            <td>
                                <div class="grayout text-right">
                                    @if(isset($item_list['_custom_except'][$student_row['id']][$k]) && $item_list['_custom_except'][$student_row['id']][$k] == 1)
                                        {{$lan::get('invocie_outside_title')}}
                                    @else
                                        @if(is_numeric($item_list['custom_item_price'][$student_row['id']][$k]))
                                            @if($item_list['custom_item_price'][$student_row['id']][$k] < 0)
                                                -\{{number_format(str_replace("-","",$item_list['custom_item_price'][$student_row['id']][$k]))}}
                                            @else
                                                \{{number_format($item_list['custom_item_price'][$student_row['id']][$k])}}
                                            @endif
                                        @else
                                            &nbsp;
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
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
                        {{--<td style="text-align: center">--}}
                            {{--<div class="grayout">--}}
                                {{--&nbsp;--}}
                            {{--</div>--}}
                        {{--</td>--}}
                        {{--<td style="text-align: center">--}}
                            {{--@if(is_numeric($item_list['discount_price'][$k]))--}}
                                {{--@if ($item_list['discount_price'][$k] > 0)--}}
                                    {{--<div class="grayout">{{date('m月d日',strtotime($item_list['due_date']))}}&nbsp;</div>--}}
                                {{--@endif--}}
                            {{--@endif--}}
                        {{--</td>--}}
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

            </table>

            {{--info total amount--}}
            <div class="table2_bottom">
                <button class="btn5" style="display:none;">{{$lan::get('add_row_title')}}</button>

                <table class="table3">
                    <tr>
                        <td align="center">
                            <p class="text-right">{{$lan::get('subtotal_title')}}</p>
                        </td>
                        <td>
                            @if($item_list['amount'] >= 0)
                            <p class="text-right">&yen;{{number_format($item_list['amount'])}}</p>
                            @else
                            <p class="text-right">-&yen;{{number_format(str_replace("-","",$item_list['amount']))}}</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            @if($item_list['amount_display_type'] == 0)
                                <p class="text-right">{{$lan::get('consumption_taxes_in_title')}}
                                    @if(isset($item_list['sales_tax_disp'])) {{$item_list['sales_tax_disp']}}%)
                                    @endif
                                </p>
                            @else
                                <p class="text-right">{{$lan::get('consumption_taxes_out_title')}}
                                @if(isset($item_list['sales_tax_disp'])){{$item_list['sales_tax_disp']}}%)
                                @endif
                                </p>
                            @endif
                        </td>
                        <td>
                            @if($item_list['tax_price'] >= 0)
                                <p class="text-right">&yen;{{number_format($item_list['tax_price'])}}</p>
                            @else
                                <p class="text-right">-&yen;{{number_format(str_replace("-","",$item_list['tax_price']))}}</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <p class="text-right">{{$lan::get('total_title')}}<p>
                        </td>
                        <td>
                            @if($item_list['amount_tax'] >=0)
                                <p class="text-right">&yen;{{number_format($item_list['amount_tax'])}}</p>
                            @else
                                <p class="text-right">-&yen;{{number_format(str_replace("-","",$item_list['amount_tax']))}}</p>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="clr"></div>
            </div>
            {{-- end total amount--}}

        </div>{{--bill_info--}}

        {{--bill preview--}}
        <div id="bill_preview" style="display:none;">
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
                        @if (isset($item_list['kakuin_path']))
                            <img src="{{$item_list['kakuin_path']}}" style="position:relative; opacity:0.8; top:-65px; width: 70px;height: 70px;float: right">
                        @endif
                    </div>
                    <div class="clr"></div>
                </section>

                <table id="bill_table" style="font-weight: bold">
                    <tr>
                        <th style="width: 80%">{{$lan::get('items_title')}}</th>
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
                                        @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k] == 1)
                                            {{$lan::get('invocie_outside_title')}}
                                        @elseif(isset($item_list['class_price'][$student_row['id']][$k]))
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
                                    @if(isset($item_list['_course_except'][$student_row['id']][$k]) && $item_list['_course_except'][$student_row['id']][$k] == 1)
                                        {{$lan::get('invocie_outside_title')}}
                                    @elseif (isset($item_list['course_price'][$student_row['id']][$k]))
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
                                @if (isset($item_list['_program_except'][$student_row['id']][$k]) && $item_list['_program_except'][$student_row['id']][$k] == 1)
                                    {{$lan::get('invocie_outside_title')}}
                                @elseif(isset($item_list['program_price'][$student_row['id']][$k]))
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

                    {{--custom_item--}}
                    @foreach($item_list['student_list'] as $student_row)
                        @if(isset($item_list['custom_item_price'][$student_row['id']]))
                            @foreach ($item_list['custom_item_id'][$student_row['id']] as $k => $v)
                                <tr>
                                    <td>
                                        {{$item_list['custom_item_name'][$student_row['id']][$k]}}
                                    </td>
                                    {{--<td>--}}
                                        {{--@if($item_list['custom_item_price'][$student_row['id']][$k]>0 && isset($item_list['payment_method_name']))--}}
                                            {{--{{$item_list['payment_method_name']}}--}}
                                        {{--@else &nbsp;--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    {{--<td style="text-align: center">--}}
                                        {{--@if(isset($item_list['custom_item_price'][$student_row['id']][$k]) && $item_list['custom_item_price'][$student_row['id']][$k]>0)--}}
                                            {{--{{date('m月d日',strtotime($item_list['due_date']))}}&nbsp;--}}
                                        {{--@else &nbsp;--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    <td class="td2">
                                        @if (isset($item_list['_custom_except'][$student_row['id']][$k]) && $item_list['_custom_except'][$student_row['id']][$k] ==1 )
                                            {{$lan::get('invocie_outside_title')}}
                                        @else
                                            @if(is_numeric($item_list['custom_item_price'][$student_row['id']][$k]))
                                                @if($item_list['custom_item_price'][$student_row['id']][$k] < 0)
                                                    -\{{number_format(str_replace("-","",$item_list['custom_item_price'][$student_row['id']][$k]))}}
                                                @else
                                                \{{number_format($item_list['custom_item_price'][$student_row['id']][$k])}}
                                                @endif
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

                    {{--discount item--}}
                    @if(isset($item_list['discount_id']))
                        @foreach($item_list['discount_id'] as $k => $v)
                            <tr>
                                <td>
                                    {{$item_list['discount_name'][$k]}}
                                </td>
                                {{--<td>--}}
                                    {{--@if ($item_list['discount_price'][$k] > 0)--}}
                                        {{--{{$item_list['payment_method_name']}}--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--@if ($item_list['discount_price'][$k] > 0)--}}
                                        {{--{{date('m月d日',strtotime($item_list['due_date']))}}&nbsp;--}}
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
                                @if($item_list['tax_price'] >= 0) &yen;{{number_format($item_list['tax_price'])}}
                                @else  -&yen;{{number_format(str_replace("-","",$item_list['tax_price']))}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="th1">{{$lan::get('total_title')}}</th>
                            <td class="td2">
                                @if($item_list['amount_tax'] >= 0 ) &yen;{{number_format($item_list['amount_tax'])}}
                                @else -&yen;{{number_format(str_replace("-","",$item_list['amount_tax']))}}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="clr"></div>
                {{--ginko furikomi bank info--}}
                @if(array_get($item_list,'invoice_type') ==  \App\Common\Constants::$PAYMENT_TYPE['TRAN_BANK'])
                    <div>
                        <p>下記口座へ振込みお願いいたします。</p>
                        @if(array_get($item_list,'school_bank_info'))
                            {{array_get($item_list,'school_bank_info')['bank_name']}}&nbsp;
                            {{array_get($item_list,'school_bank_info')['branch_name']}}&nbsp;
                            {{array_get($item_list,'school_bank_info')['bank_account_type']}}&nbsp;
                            {{array_get($item_list,'school_bank_info')['account_number']}}&nbsp;
                            {{array_get($item_list,'school_bank_info')['account_name']}}&nbsp;
                        @endif
                        <p>なお、振込手数料はお客様負担でお願いいたします。</p>
                    </div>
                @endif
                {{--@if $item_list['pbank_info && $item_list['invoice_type == 3}}--}}
                {{--<div class="bank_account">{{$payee_title}}<br/>{{$item_list['pbank_info}}</div>--}}
                {{--@endif--}}
                <br/>
            </div>
        </div>
        {{--end bill preview--}}
    </div>
</div>

