<div id="bill_content">
    <div class="bill_header_top">
        <p>{{date('Y年m月d日')}}</p>
    </div>
    <div id="bill_header">
        <h1 style="text-decoration: underline">請求書</h1>
    </div>
    <section id="bill_info">
        <div class="bill_info_left">
            <p class="company_name">{{$item_list['parent_name']}}  様</p>
            <p class="bill_p1">下記のとおりご請求申し上げます。</p>
            <p class="bill_much">ご請求金額　
                @if($item_list['amount_tax'])
                    &nbsp;&nbsp;&yen;{{number_format($item_list['amount_tax'])}} -
                @endif
            </p>
            <p class="bill_kigen">
                @if(isset($item_list['due_date_jp']))
                    支払方法 : {{\App\Common\Constants::$invoice_type[$item_list['lang_code']][$item_list['invoice_type']]}}
                @endif
            </p>
            <p class="bill_kigen">
                @if(isset($item_list['due_date_jp']))
                    支払期限 : {{$item_list['due_date_jp']}}
                @endif
            </p>
        </div>
        <div class="bill_info_right">
            <p class="my_company_name">〒{{$item_list['school_zipcode_1']}}-{{$item_list['school_zipcode_2']}}</p>
            <p class="my_company_address">{{$item_list['pref_name']}}{{$item_list['city_name']}}{{$item_list['school_address']}}</p>
            <p class="my_company_address">{{$item_list['school_building']}}</p>
            <p class="my_company_name">{{$item_list['school_name']}}</p>
            <p class="my_company_tel">TEL {{$item_list['school_tel']}}</p>
            <p class="my_company_name">{{$item_list['school_mail']}}</p>
            <p class="my_company_daihyou">{{$item_list['school_official_position']}} {{$item_list['school_daihyou']}}</p>
            @if ($item_list['kakuin_path'])
                <img src="{{$item_list['kakuin_path']}}" style="position:relative; opacity:0.8; top:-65px; width: 70px;height: 70px;float: right">
            @endif
        </div>
        <div class="clr"></div>
    </section>

    <table id="bill_table">
        <tr>
            @if ($item_list['invoice_type'] != 5)
                <th style="width: 80%">内容</th>
                <th style="width: 20%">金額</th>
            @else
                <th style="width: 70%">内容</th>
                <th style="width: 30%">金額</th>
            @endif
        </tr>

        {{--class item--}}
        @foreach($item_list['student_list'] as $student_row)
            @if(isset($item_list['class_name'][$student_row['id']]))
                @foreach($item_list['class_name'][$student_row['id']] as $k => $name)
                    <tr>
                        <td>
                            {{$name}}
                        </td>
                        <td class="td2">
                            @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k] == 1)
                            （請求対象外）
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
                        <td class="td2">
                            @if(isset($item_list['_course_except'][$student_row['id']][$k]) && $item_list['_course_except'][$student_row['id']][$k] == 1)
                            （請求対象外）
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
                        <td class="td2">
                            @if (isset($item_list['_program_except'][$student_row['id']][$k]) && $item_list['_program_except'][$student_row['id']][$k] == 1)
                            （請求対象外）
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
                        <td class="td2">
                            @if(is_numeric($item_list['custom_item_price'][$student_row['id']][$k]))
                                @if($item_list['custom_item_price'][$student_row['id']][$k] < 0)
                                    -\{{number_format(str_replace("-","",$item_list['custom_item_price'][$student_row['id']][$k]))}}
                                @else
                                    \{{number_format($item_list['custom_item_price'][$student_row['id']][$k])}}
                                @endif
                            @else
                                &nbsp;
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

    <table id="pay_table2">
        <tr>
            <th class="th1">小計</th>
            <td class="td2">
                &yen;{{number_format(array_get_not_null($item_list, 'amount', 0))}}
            </td>
        </tr>
        <tr>
            @if($item_list['amount_display_type'] == 0)
                <th class="th1">消費税（内税
                    @if($item_list['sales_tax_disp']) {{$item_list['sales_tax_disp']}}%)
                    @endif
                </th>
            @else
                <th class="th1">消費税（外税
                    @if($item_list['sales_tax_disp']) {{$item_list['sales_tax_disp']}}%)
                    @endif
                </th>
            @endif
            <td class="td2">
                &yen;{{number_format(array_get_not_null($item_list, 'tax_price', 0))}}
            </td>
        </tr>
        <tr>
            <th class="th1">合計</th>
            <td class="td2">
                &yen;{{number_format(array_get_not_null($item_list, 'amount_tax', 0))}}
            </td>
        </tr>
    </table>
    <div class="clr"></div>
    @if ($item_list['invoice_type'] == \App\Common\Constants::$PAYMENT_TYPE['CRED_ZEUS'])
        @include('Portal.Invoice.invoice_credit_info')
    @elseif ($item_list['invoice_type'] == \App\Common\Constants::$PAYMENT_TYPE['TRAN_BANK'])
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

    <br/>
    <form>
        <input type="button" value="このページを印刷する" onclick="window.print();" />
    </form>
</div>