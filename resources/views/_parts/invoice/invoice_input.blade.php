<script type="text/javascript">
    $(function () {
        var real_amount = {{$item_list['amount']}};
        $('#show_preview').click(function () {
            $("input[name='preview_bool']").val(1);
            $("#action_form").submit();
            return false;
        });
        $('.chkbox_debit').each(function () {

            if($(this).is(":checked")){
                real_amount+= parseInt($(this).data('amount'));
                // append to view
                cloneDebitRecord($(this).data('item_name'),addCommas($(this).data('amount')),$(this).val());
            }
            calculatorAmount(real_amount);
        })
        $(document).on('change','.chkbox_debit',function () {
            var amount = parseInt($(".amount").text().replace(',',''));
            if($(this).is(":checked")){
                amount+= parseInt($(this).data('amount'));
                // append to view
                cloneDebitRecord($(this).data('item_name'),addCommas($(this).data('amount')),$(this).val());
            }else{
                amount-= parseInt($(this).data('amount'));
                // delete from view
                $(".record_debit_"+$(this).val()).remove();
            }
            calculatorAmount(amount);
        })

        $(document).on('change','.item_except',function () {
            var amount = parseInt($(".amount").text().replace(',',''));
            if($(this).is(":checked")){
                amount-= parseInt($(this).data('amount'));
            }else{
                amount+= parseInt($(this).data('amount'));
            }
            calculatorAmount(amount);
        })

        //change amount when keyup custom
        $(".template_custom_item_price").on('focusin',function(){
            var amount = parseInt($(".amount").text().replace(',',''));
            var current = 0;
            if($(this).val()!="" && $(this).val()!=null && $(this).val()!= undefined && !isNaN($(this).val())){
                current = parseInt($(this).val());
            }

            $(".template_custom_item_price").on('focusout',function () {
                var new_adjust = 0;
                if($(this).val()!="" && $(this).val()!=null && $(this).val()!= undefined && !isNaN($(this).val())){
                    new_adjust = parseInt($(this).val());
                }

                amount-= current;
                amount+= new_adjust;
                calculatorAmount(amount);
            })
        })


        //calculator tax and total base on amount and tax_display_type
        function calculatorAmount(amount){

            var display_type = $(".tax_txt").data('display_type');
            var tax_rate = $(".tax_txt").data('tax_rate')/100;
            if(display_type == 1 ){
                var tax = amount*tax_rate/100;
                var total = amount + tax;
            }else{
                var tax = Math.floor(amount * (tax_rate * 100) / ((tax_rate * 100) + 100));
                var total = amount;
            }

            $(".tax_val").text(addCommas(tax));
            $(".total_amount").text(addCommas(total));
            $(".amount").text(addCommas(amount));
        }
        // return number format xx,xxx,xxx
        function addCommas(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        function cloneDebitRecord(name,amount,id){

            var cloneHtml = '<tr class="record_debit_'+id+'"><td> <div class="grayout">'+name+'</div></td><td><div class="grayout">&nbsp;</div></td>'
                            +'<td align="center"><div class="grayout text-right">\\'+amount
                            +'</div></td></div></td></tr>';

            $('.adjust_name_table').append(cloneHtml);

            return;
        }

        $(".remove_custom_item").click(function(){
            var input = $(this).closest('tr').find('.template_custom_item_price');
            var amount = parseInt($(".amount").text().replace(',',''));

            var adjust = 0;
            if(input.val()!= null && input.val()!=undefined && input.val()!="" && !isNaN(input.val())){
                adjust = input.val();
                amount-= adjust;
            }

            calculatorAmount(amount);
            $(this).closest('tr').remove();
            $('.error_row').closest('tr').remove();
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
    .height_41 {
        height: 35px !important;
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
                        <td>{{array_get($data,'parent_name')}}</td>
                    </tr>
                    <tr>
                        <td>{{$lan::get('member_name_title')}}</td>
                        <td>:</td>
                        <td>
                            @if(array_get($data,'student_list'))
                                @foreach (array_get($data,'student_list') as $student_row)
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
                        <th class="text_title header" style="width:5%;"> </th>
                        <th class="text_title header" style="width:40%;">{{$lan::get('items_title')}}</th>
                        <th style="width:35%;"></th>
                        <th class="text_title header" style="width:20%;">{{$lan::get('money_amount_title')}}</th>
                        {{--<th class="text_title header" style="width:30%;">{{$lan::get('payment_date_title')}}</th>--}}
                    </tr>
                    @foreach($debit_data as $k => $debit)
                        <tr>
                            <td style="text-align: center; padding : 15px;">
                                <input type="checkbox" class="chkbox_debit" data-amount="{{array_get($debit,'amount')}}" data-item_name ="{{date('Y年m月',strtotime(array_get($debit,'debit_year_month').'-01')).'分 未入金'}}"
                                       name ="debit_id[]" value="{{array_get($debit,'invoice_debit_id')}}" @if( isset($request['debit_id']) && in_array(array_get($debit,'invoice_debit_id'),array_get($request,'debit_id'))) checked @endif>
                            </td>
                            <td style="padding : 15px;">
                                <a style="color:red" href="{{$_app_path}}invoice/detail?id={{array_get($debit,'invoice_debit_id')}}&invoice_year_month={{array_get($debit,'debit_year_month')}}"
                                   target="_blank">{{date('Y年m月',strtotime(array_get($debit,'debit_year_month').'-01')).'分'}}</a>
                            </td>
                            <td></td>
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
        <div id="bill_info" style="margin-top: 5px;">
            <p><b>請求情報</b></p>
            <table class="table2 adjust_name_table">
                <tr>
                    <th class="text_title header" style="width:50%;">{{$lan::get('items_title')}}</th>
                    <th class="text_title header" style="width:30%;"></th>
                    {{--<th class="text_title header" style="width:10%;">{{$lan::get('payment_method_search_title')}}</th>--}}
                    {{--<th class="text_title header" style="width:20%;">{{$lan::get('payment_date_title')}}</th>--}}
                    <th class="text_title header" style="width:20%;">{{$lan::get('money_amount_title')}}</th>
                </tr>

            {{--for class item--}}
            @foreach($data['student_list'] as $k=>$student_row)
                @if(isset($item_list['class_name'][$student_row['id']]))
                    @foreach ($item_list['class_name'][$student_row['id']] as $k=> $name)
                        <tr>
                            <td>
                                <div class="grayout">{{$name}}</div>
                            </td>
                            <td>
                                <div class="grayout">
                                    <input type="checkbox" class="item_except" data-amount="{{$item_list['class_price'][$student_row['id']][$k]}}" name="class_except[{{$student_row['id']}}][{{$k}}]" value="1" @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k]==1) checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
                                </div>
                            </td>
                            {{--<td style="text-align: center">--}}
                                {{--<div class="grayout">{{$item_list['payment_method_name']}}&nbsp;</div>--}}
                            {{--</td>--}}
                            {{--<td style="text-align: center">--}}
                                {{--<div class="grayout">{{date('m月d日',strtotime($item_list['due_date']))}}&nbsp;</div>--}}
                            {{--</td>--}}
                            <td align="center">
                                <div class="grayout text-right">
                                    @if(isset($item_list['_class_except'][$student_row['id']][$k]) && $item_list['_class_except'][$student_row['id']][$k] == 1 )
                                        {{$lan::get('invocie_outside_title')}}
                                    @else
                                        \{{number_format($item_list['class_price'][$student_row['id']][$k])}}</span>
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
                            <div class="grayout height_41">
                                <input type="checkbox" class="item_except" data-amount="{{$item_list['course_price'][$student_row['id']][$k]}}" name="course_except[{{$student_row['id']}}][{{$k}}]" value="1" @if(isset($item_list['_course_except'][$student_row['id']][$k]) && $item_list['_course_except'][$student_row['id']][$k] == 1 ) checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
                            </div>
                        </td>
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
                        <td align="center">
                            <div class="grayout text-right">
                                @if($item_list['_course_except'][$student_row['id']][$k])
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
                @if(isset($item_list['program_name']))
                    @foreach($item_list['program_name'][$student_row['id']] as $k=>$name)
                    <tr>
                        <td>
                            <div class="grayout">{{$name}}</div>
                        </td>
                        <td>
                            <div class="grayout">
                                <input type="checkbox" class="item_except" data-amount="{{$item_list['program_price'][$student_row['id']][$k]}}" name="program_except[{{$student_row['id']}}][{{$k}}]" value="1" @if(isset($item_list['_program_except'][$student_row['id']][$k]) && $item_list['_program_except'][$student_row['id']][$k] == 1)checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
                            </div>
                        </td>
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
                        {{--<td><div class="grayout">&nbsp;</div></td>--}}
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
            @php
                $total_price = 0
            @endphp
            @foreach($data['student_list'] as $student_row)
                @if(isset($item_list['custom_item_price'][$student_row['id']]))
                    @foreach ($item_list['custom_item_name'][$student_row['id']] as $k => $v)
                        @if(isset($item_list['custom_item_id'][$student_row['id']][$k]))
                            <tr>
                                <td>
                                    <div class="grayout">
                                        {{$item_list['custom_item_name'][$student_row['id']][$k]}}
                                    </div>
                                </td>
                                <td>
                                    <div class="grayout">
                                        <input type="checkbox" class="item_except" data-amount="{{$item_list['custom_item_price'][$student_row['id']][$k]}}" name="custom_except[{{$student_row['id']}}][{{$k}}]" value="1" @if(isset($item_list['_custom_except'][$student_row['id']][$k]) && $item_list['_custom_except'][$student_row['id']][$k]==1) checked @endif/>&nbsp;{{$lan::get('invoice_outside_title')}}
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
                                        {{--@if(isset($item_list['class_due_date'][$student_row['id']][$k]))--}}
                                            {{--<div class="grayout">{{$item_list['class_due_date'][$student_row['id']][$k]}}&nbsp;</div>--}}
                                        {{--@else--}}
                                            {{--<div class="grayout">{{Carbon\Carbon::parse($item_list['due_date'])->format('m月d日')}}&nbsp;</div>--}}
                                        {{--@endif--}}
                                        {{--@else <div class="grayout">&nbsp;</div>--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                <td>
                                    <div class="grayout text-right">
                                        @if(isset($item_list['_custom_except'][$student_row['id']][$k]) && $item_list['_custom_except'][$student_row['id']][$k]==1 )
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
                                    </div>
                                </td>
                            </tr>
                        @else

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
                        {{--<td style="text-align: center">--}}
                            {{--<div class="grayout">&nbsp;--}}
                            {{--</div>--}}
                        {{--</td>--}}
                        {{--<td style="text-align: center">--}}
                            {{--@if(isset($item_list['class_due_date'][$student_row['id']][$k]))--}}
                                {{--<div class="grayout">{{$item_list['class_due_date'][$student_row['id']][$k]}}&nbsp;</div>--}}
                            {{--@else--}}
                                {{--<div class="grayout">{{Carbon\Carbon::parse($item_list['due_date'])->format('m月d日')}}&nbsp;</div>--}}
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

            {{--custom item edit--}}
            @if(isset($request['custom_item']))
                @foreach($request['custom_item'] as $key=>$value)
                    @foreach($value as $k=>$v)
                        <tr class="template_custom_item">
                            <td>
                                <div class="grayout">
                                    <input type="text" style="ime-mode:inactive; text-align:left;" name="custom_item[{{$key}}][{{$k}}][name]" value="{{$v['name']}}">
                                </div>
                            </td>
                            <td style="text-align: center; height: 50px!important;">
                                <div class="grayout" style="text-align: left">
                                    <button style="font-size: 14px;" class="remove_custom_item">削除&nbsp;</button>
                                </div>
                            </td>
                            {{--<td><div class="grayout" style="height: 100%;text-align:center; padding: 15px;">&nbsp;</div></td>--}}
                            {{--<td><div class="grayout" style="height: 100%;text-align:center; padding: 15px;">&nbsp;</div></td>--}}
                            <td><div class="grayout text-right">
                                    <input type="text" style="ime-mode:inactive; text-align:right;" class="template_custom_item_price" name="custom_item[{{$key}}][{{$k}}][price]" value="{{$v['price']}}">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if(isset($request['error_custom_item']))
                        @foreach($request['error_custom_item'] as $k=>$v)
                            <tr>
                                <td class="error_row">
                                    <ul class="message_area">
                                        <li class="error_message">{{$lan::get($v)}}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
            {{--end discount item edit--}}

            </table>
            <!--fix adjust item by textbox-->
            <div style="display:none;">
                <table id="tbl_clone">
                    <tbody>
                    <tr class="template_custom_item">
                        <td>
                            <div class="grayout">
                                <input type="text" style="ime-mode:inactive; text-align:left;" name="template_custom_item_name"/>
                                <input type="hidden" name="template_custom_item_id"/>
                            </div>
                        </td>
                        <td style="text-align: left; height: 50px!important;">
                            <div class="grayout"><button style="font-size: 14px;" class="remove_custom_item">{{$lan::get('delete_title')}}&nbsp;</button>
                            </div>
                        </td>
                        {{--<td><div class="grayout" style="height: 100%;text-align:center; padding: 15px;">&nbsp;</div></td>--}}
                        {{--<td><div class="grayout" style="height: 100%;text-align:center; padding: 15px;">&nbsp;</div></td>--}}
                        <td>
                            <div class="grayout text-right">
                                <input class="template_custom_item_price" style="ime-mode:inactive; text-align:right;" type="text" name="template_custom_item_price" value=""
                                       pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"/>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <button class="btn5" id="btn_add_row">{{$lan::get('add_row_title')}}</button>
            <!--end-->

            {{--info total amount--}}
            <div class="table2_bottom">
                <button class="btn5" style="display:none;">{{$lan::get('add_row_title')}}</button>

                <table class="table3">
                    <tr>
                        <td align="center">
                            <p class="text-right">{{$lan::get('subtotal_title')}}</p>
                        </td>
                        <td>
                            @if($item_list['amount'])
                                <p class="text-right">&yen;<span class="amount">{{number_format($item_list['amount'])}}</span></p>
                            @else
                                <p class="text-right">&yen;<span class="amount">0</span></p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            @if($item_list['amount_display_type'] == 0)
                                <p class="text-right">{{$lan::get('consumption_taxes_in_title')}}
                                    @if($item_list['sales_tax_disp']) {{$item_list['sales_tax_disp']}}%)
                                    @endif
                                </p>
                            @else
                                <p class="text-right">{{$lan::get('consumption_taxes_out_title')}}
                                @if($item_list['sales_tax_disp']){{$item_list['sales_tax_disp']}}%)
                                @endif
                                </p>
                            @endif
                        </td>
                        <td>
                            <span class="display_none tax_txt" data-display_type="{{$item_list['amount_display_type']}}"
                                data-tax_rate="{{$item_list['sales_tax_disp']}}"></span>
                            @if($item_list['tax_price'])
                                <p class="text-right">&yen;<span class="tax_val">{{number_format($item_list['tax_price'])}}</span></p>
                            @else
                                <p class="text-right">&yen;<span class="tax_val">0</span></p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <p class="text-right">{{$lan::get('total_title')}}<p>
                        </td>
                        <td>
                            @if($item_list['amount_tax'])
                                <p class="text-right">&yen;<span class="total_amount">{{number_format($item_list['amount_tax'])}}</span></p>
                            @else
                                <p class="text-right">&yen;<span class="total_amount">0</span></p>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="clr"></div>
            </div>
            {{-- end total amount--}}
        </div>{{--bill_info--}}
    </div>
</div>

