<style type='text/css'>
    body{
        font-family: "ipag";
        font-size: 15px;
    }
    @page {
        size: 21cm 29.7cm;
        margin: 11mm 22mm 11mm 22mm;
    }
    .border1 {
        border-top: 1px solid;
        border-left: 1px solid;
        border-right: 1px solid;
    }
    .border2 {
        border-top: 1px solid;
        border-right: 1px solid;
        margin-left: -10px;
    }
    .border3 {
        border-top: 1px solid;
        border-left: 1px solid;
        border-right: 1px solid;
        border-bottom: 1px solid;
    }
    .border4 {
        border-top: 1px solid;
        border-right: 1px solid;
        margin-left: -10px;
        border-bottom: 1px solid;
    }
    .cell {
        width: 130px;
        display: inline-block;
        height: 25px;
        line-height: 20px
    }
    .th_cell {
        background-color: #e4e4e4;
    }
    .txt_right {
        text-align: right;
    }
</style>
<div style="page-break-after: always; margin: auto">
    <div style="font-size: 15px">
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
        <div style="float: right">{{$lan::get('invoice_issue_date')}}{{Carbon\Carbon::parse(array_get($data, 'now_date'))->format('Y年m月d日')}}</div>
        <div style="clear: both"></div>
    </div>

    <div style="width: 158px; text-align: center; margin: 25px auto; font-size: 35px; font-weight: bold; margin-bottom: 30px; text-decoration: underline">
        ご請求書
    </div>

    <div style="margin-bottom: 30px;">
        <div style="float: left; width: 300px; font-size: 15px">
            <table border="0">
            <tr>
                <td>{{$lan::get('your_invoice_amount_title')}}</td><td>:\{{number_format(array_get($data,'amount_tax'))}}</td>
            </tr>
            <tr>
            @if($data['invoice_type'] == \App\Common\Constants::$PAYMENT_TYPE['TRAN_RICOH'])
                    <td>口座振替日</td><td>:{{Carbon\Carbon::parse(array_get($data, 'due_date'))->format('Y年m月d日')}}</td>
            @else
                    <td>支払期限</td><td>:{{Carbon\Carbon::parse(array_get($data, 'due_date'))->format('Y年m月d日')}}</td>
            @endif
            </tr>
            <tr>
                <td>支払方法</td><td>:{{\App\Common\Constants::$invoice_type[2][$data['invoice_type']]}}</td>
            </tr>
            </table>
        </div>
        {{--<div style="float: right; width: 280px; font-size: 13px; position: relative">--}}
        {{--請求元住所: @if (array_get($data,'pref_name')){{array_get($data,'pref_name')}}@endif @if (array_get($data,'city_name')){{array_get($data,'city_name')}}@endif{{array_get($data,'school_address')}}<br>--}}
        {{--施設名: {{array_get($data,'school_name')}}<br>--}}
        {{--代表者名: {{array_get($data,'school_daihyou')}}<br>--}}
        {{--電話番号: {{array_get($data,'school_tel')}}<br>--}}
        {{--@if (session()->get('school.login.kakuin_path'))--}}
        {{--<img style="height: 60px; width: 60px; position: absolute; top: 15px; right: 0" src="{{request()->root()}}{{session()->get('school.login.kakuin_path')}}">--}}
        {{--@endif--}}
        {{--</div>--}}
        <div style="float: right; width: 220px; font-size: 13px; position: relative">
            @if(array_get($data,'school_zipcode1'))〒{{array_get($data,'school_zipcode1')}}-{{array_get($data,'school_zipcode2')}}<br/>@endif
            @if(array_get($data,'school_city')){{array_get($data,'school_city')}}{{array_get($data,'address')}}<br/>@endif
            @if(array_get($data,'school_building')){{array_get($data,'school_building')}}<br/>@endif
            @if(array_get($data,'school_name')){{array_get($data,'school_name')}}<br/>@endif
            @if(array_get($data,'school_tel'))TEL {{array_get($data,'school_tel')}}<br/>@endif
            @if(array_get($data,'school_mail')){{array_get($data,'school_mail')}}<br/>@endif
            @if(array_get($data,'school_daihyou')){{array_get($data,'school_official_position')}} {{array_get($data,'school_daihyou')}}<br/>@endif
            @if (session()->get('school.login.kakuin_path'))
            <img style="height: 60px; width: 60px; position: absolute; top: 50px; right: 0" src="{{request()->root()}}{{session()->get('school.login.kakuin_path')}}">
            @endif
        </div>
        <div style="clear: both"></div>
    </div>

    <table border="1" width="100%" style="border-collapse: collapse">
        <tr>
            <th align="center" style="background-color: #e4e4e4; width: 79%">{{$lan::get('items_title')}}</th>
            <th align="center" style="background-color: #e4e4e4; width: 21%">金額</th>
        </tr>
        @php $total_price = 0; @endphp
        @foreach( $data['student_list'] as $student_row)
            {{--プラン--}}
            @foreach(array_get($data['class_name'], $student_row['id']) as $k => $name)
                @if ($name)
                    <tr>
                        <td>{{$name}}</td>
                        <td align="right">
                            @if(isset($data['_class_except'][$student_row['id']][$k]) && $data['_class_except'][$student_row['id']][$k]== 1)
                                （請求対象外）
                            @else
                                &yen;{{number_format(array_get($data['class_price'],$student_row['id'])[$k])}}
                                @php $total_price += array_get($data['class_price'], $student_row['id'])[$k]; @endphp
                            @endif

                        </td>
                    </tr>
                @endif
            @endforeach

            {{--イベント--}}
            @foreach(array_get($data['course_name'], $student_row['id']) as $k => $name)
                @if ($name)
                    <tr>
                        <td>{{$name}}</td>
                        <td align="right">
                            @if(isset($data['_course_except'][$student_row['id']][$k]) && $data['_course_except'][$student_row['id']][$k]== 1)
                                （請求対象外）
                            @else
                                &yen;{{number_format(array_get($data['course_price'],$student_row['id'])[$k])}}
                                @php $total_price += array_get($data['course_price'],$student_row['id'])[$k]; @endphp
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach

            {{--プログラム--}}
            @foreach(array_get($data['program_name'], $student_row['id']) as $k => $name)
                @if ($name)
                    <tr>
                        <td>{{$name}}</td>
                        <td align="right">
                            @if(isset($data['_program_except'][$student_row['id']][$k]) && $data['_program_except'][$student_row['id']][$k]== 1)
                                （請求対象外）
                                @else
                                &yen;{{number_format(array_get($data['program_price'],$student_row['id'])[$k])}}
                                @php $total_price += array_get($data['program_price'],$student_row['id'])[$k]; @endphp
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach

            {{--個別入力--}}
            @foreach(array_get($data['custom_item_name'], $student_row['id']) as $k=>$name)
                @if( $name || array_get($data['custom_item_price'],$student_row['id'] )[$k])
                    <tr>
                        <td>{{$name}}</td>
                        <td align="right">
                            @if(isset($data['_custom_except'][$student_row['id']][$k]) && $data['_custom_except'][$student_row['id']][$k]== 1)
                                （請求対象外）
                            @else
                                @if (is_numeric(array_get($data['custom_item_price'], $student_row['id'])[$k]))
                                    @if (array_get($data['custom_item_price'], $student_row['id'])[$k] < 0)
                                        -&yen;{{number_format(str_replace('-', '', array_get($data['custom_item_price'],$student_row['id'])[$k]))}}
                                        @else
                                        &yen;{{number_format( array_get($data['custom_item_price'],$student_row['id'])[$k])}}
                                    @endif
                                    @php
                                        $total_price += array_get($data['custom_item_price'],$student_row['id'])[$k];
                                    @endphp
                                @else
                                    &nbsp;
                                @endif
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </table>

    <br>
    <div>
        <div style="float: left;">
            @if(array_get($data,'invoice_type') == \App\Common\Constants::$PAYMENT_TYPE['TRAN_BANK'])
                {{--振込--}}
                <div>
                    {{array_get($data, 'bank_name')}}<br>
                    {{array_get($data, 'branch_name')}}<br>
                    {{array_get($data, 'bank_account_number')}}<br>
                    {{array_get($data, 'bank_account_name')}}
                </div>
            @endif
        </div>

        {{--Summary--}}
        <div style="width: 262px; float: right;">
            <div>
                <span class="border1 cell th_cell">小計</span>
                <span class="border2 cell txt_right">&yen;{{number_format($total_price)}}</span>
            </div>
            @foreach (array_get($data, 'discount_name') as $k => $v)
                @if ($v || array_get($data,'discount_price')[$k])
                    <div>
                        <span class="border1 cell th_cell">{{$v}}</span>
                        <span class="border2 cell txt_right">-&yen;{{number_format(array_get($data,'discount_price')[$k])}}</span>
                    </div>
                    @php $is_discount_exists = true; @endphp
                @endif
            @endforeach
            <div>
                <span class="border1 cell th_cell">
                    @if($data['amount_display_type'] == 0)
                        消費税（内税{{$data['sales_tax_rate']*100}}％）
                    @else
                        消費税 {{$data['sales_tax_rate']*100}}％
                    @endif
                </span>
                <span class="border2 cell txt_right"> @if(array_get($data,'tax_price'))&yen;{{number_format(array_get($data,'tax_price'))}}@endif</span>
            </div>
            <div>
                <span class="border3 cell th_cell">合計金額</span>
                <span class="border4 cell txt_right">@if(array_get($data,'amount_tax'))&yen;{{number_format(array_get($data,'amount_tax'))}}@endif</span>
            </div>
        </div>
        <div style="clear: both"></div>
        {{--ginko furikomi bank info--}}
        @if(array_get($data,'invoice_type') ==  \App\Common\Constants::$PAYMENT_TYPE['TRAN_BANK'])
            <div>
                <p>下記口座へ振込みお願いいたします。</p>
                @if(array_get($data,'school_bank_info'))
                    {{array_get($data,'school_bank_info')['bank_name']}}&nbsp;
                    {{array_get($data,'school_bank_info')['branch_name']}}&nbsp;
                    {{array_get($data,'school_bank_info')['bank_account_type']}}&nbsp;
                    {{array_get($data,'school_bank_info')['account_number']}}&nbsp;
                    {{array_get($data,'school_bank_info')['account_name']}}&nbsp;
                @endif
                <p>なお、振込手数料はお客様負担でお願いいたします。</p>
            </div>
        @endif
    </div>
</div>
