<div class="panel-body">

    <table class="" id="table3_2" style="width: 100%;">
        <colgroup>
            <col style="width: 30%;">
            <col style="width: 70%">
        </colgroup>
        <tr>
            <td class="t3_2td2" style=" min-width: 240px;">{{$lan::get('select_fee_title')}}</td>
            <td class="t3_2td3" style="text-align: left;">
                <select style="width: 60%;" id="class-fee-select" name="plan_id">
                    @foreach($class_fee_plan_list as $fee)
                        <option value="{{$fee['id']}}" f-data="@if (array_get($fee, 'fee')) {{number_format(array_get($fee, 'fee'))}} @else 0 @endif {{$lan::get('jap_yen_title')}}" f-data-unit="@if (array_get($fee, 'payment_unit_name')) {{array_get($fee, 'payment_unit_name')}} @else null @endif"
                                @if (isset($student_class) && $student_class->plan_id == $fee['id'] ) selected="selected" @endif>{{array_get($fee, 'fee_plan_name')}}
                        </option>
                    @endforeach
                </select>
                <label style="align: center;color:#117700;opacity: 0.8;"></label>
                <label style="float: right;color:#0000ff;opacity: 0.8;"></label>
            </td>
        </tr>
        <tr>
            <td class="t3_2td2">{{$lan::get('number_of_payment_title')}}</td>
            <td class="t3_2td3" style="text-align: left;">

                <select name="number_of_payment" style="width: 70px;" >
                    @foreach ($month_list as $key=>$month)
                        <option value="{{$key}}" @if (isset($student_class) && $student_class->number_of_payment == $key ) selected="selected" @endif> {{$month}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class=" message_schedule">
                    <p class="error_message" id="input-schedule-err" style="display: none;">{{ $lan::get('input_all_schedule_error') }}</p>
                    <p class="error_message" id="total-fee-err" style="display: none;">{{ $lan::get('total_fee_schedule_error') }}</p>
                    <p class="error_message" id="input-fee-err" style="display: none;">{{$lan::get('input_fee_schedule_error')}}</p>
                </span>
            </td>
        </tr>
        @foreach ($month_list as $key=>$month)
            <tr class="block_payment_{{$key}}" >
                <td><input type="text" class="DateInput" name="" value="@if( isset($class_payment_schedule) && array_key_exists($key , $class_payment_schedule) ){{$class_payment_schedule[$key]['schedule_date']}}@endif" placeholder=" {{$lan::get('pay_schedule_date')}}"/>
                    </td>
                <td><input type="text" style="text-align: right;width: 150px;" value="@if( isset($class_payment_schedule) && array_key_exists($key , $class_payment_schedule) ){{$class_payment_schedule[$key]['schedule_fee']}}@endif" placeholder="{{$lan::get('schedule_fee_title')}}"
                           pattern="\d*" oncopy="return false" onpaste="return false" oninput="check_numtype(this)" style="ime-mode:disabled"> {{$lan::get('jap_yen_title')}}</td>
            </tr>
        @endforeach
        <tr class="total-fee-block">
            <td class="t3_2td2">{{$lan::get('total_fee_schedule_title')}}</td>
            <td class="t3_2td3"><label id="total-fee" style="color: #b5002b; opacity: 0.8;" data="0">0</label> {{$lan::get('jap_yen_title')}}</td>
        </tr>

        @if(isset($request) && $request->offsetExists('show_payment_list'))
        <tr>
            <td class="t3_2td2">{{$lan::get('payment_method_select')}}</td>
            <td class="t3_2td3" style="text-align: left;">
                <select style="width: 50%;" name="payment_method">
                    @foreach($payment_method as $item)
                        <option value="{{$item['id']}}" @if($item['id'] == $student_class['payment_method']) selected @endif
                                                        @if(isset($item['disabled'])) disabled @endif>
                            {{$lan::get(array_get($item,'name'))}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        @endif
        {{--<tr>--}}
            {{--<td colspan="2" ><input type="checkbox" id="send-mail-flag" name="notices_mail_flag" @if (isset($student_class) && $student_class->notices_mail_flag) checked @endif><label for="send-mail-flag">{{$lan::get('notice_mail_title')}}</label>  {{$lan::get('explain_notice_mail_title')}}</td>--}}
        {{--</tr>--}}
    </table>
</div>

<script type="text/javascript">
    $(function () {
        //料金選択
        $('#class-fee-select').change(function () {
            $(this).next().text($(this).find(":selected").attr('f-data-unit'));
            $(this).next().next().text($(this).find(":selected").attr('f-data'));
        });

        // 支払方法選択
        $('select[name=number_of_payment]').change(function () {
            // close all block payment
            $('tr[class^=block_payment]').hide();
            $('tr[class^=block_payment]').find('td').children().removeAttr('name');

            $data = $(this).find(":selected").val();

            if ($data !=99) { // 1回～12回
                // $fee : Ex: ["", "10,000", "", "円"]
//                $fee = $('#class-fee-select').find(":selected").attr('f-data').split(' ');
//                $fee = (parseFloat($fee[1].replace(',',''))) ? (parseFloat($fee[1].replace(',',''))) : 0; console.log($fee);
//                $avg_fee = Math.floor($fee/ parseInt($data));

                $('tr[class^=block_payment]').each(function (idx, el) {
                    // $class_name : 'block_payment_12'
                    $class_name = $(el).attr('class').split('_');
                    if (parseInt($class_name[2]) <= parseInt($data)) {
                        $(el).show();

                        // add name
                        $(el).find('td').children().first().attr('name', 'payment_date['+$class_name[2]+']');
                        $(el).find('td').children().last().attr('name', 'payment_fee['+$class_name[2]+']');
                    }

                });
                $('.total-fee-block').show();

                $('#send-mail-flag').removeAttr("disabled");
            } else {
                $('.total-fee-block').hide();
                //disable notice_send_mail flag
                $('#send-mail-flag').prop('disabled', true);
            }
            update_total_fee();
        });

        $(document).on('ready', function () {
            update_total_fee();
        });

        $(document).on('keyup', '[name^=payment_fee\\[]', function () {
            update_total_fee();
        });

        $('#class-fee-select').change();
        $('select[name=number_of_payment]').change();
    });

    function update_total_fee() {
        // $fee : Ex: ["", "10,000", "", "円"]
        $fee = $('#class-fee-select').find(":selected").attr('f-data').split(' ');
        $fee = (parseFloat($fee[1].replace(',',''))) ? (parseFloat($fee[1].replace(',',''))) : 0;

        $total_fee = 0;
        $('[name^=payment_fee\\[]').each(function (idx, el) {
            $total_fee += (parseInt($(el).val()))? parseInt($(el).val()) : 0;
        });
        ($fee > $total_fee) ? $('#total-fee').css('color', '#b5002b'): $('#total-fee').css('color', '#008000');
        $('#total-fee').attr('data', $total_fee);

        // number format 000,000,000...
        while (/(\d+)(\d{3})/.test($total_fee.toString())){
            $total_fee = $total_fee.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
        $('#total-fee').text($total_fee);
    }

    function validate_chedule() {
        $('.message_schedule p').hide();
        $data = $('select[name=number_of_payment]').find(':selected').val();
        $is_valid = true;
        if ($data !=99) {
            // 支払基準日
            $('[name^=payment_date]').each(function () {
               if ($(this).val() == '') {$is_valid = false;$('#input-schedule-err').show();}
            });
            // 金額
            var regex=/^[\d\,]+$/;
            $('[name^=payment_fee]').each(function () {
               if ($(this).val() == '') {$is_valid = false;$('#input-schedule-err').show();}
               
               if (!$(this).val().trim().match(regex)){$is_valid = false;$('#input-fee-err').show();}
            });
            // total fee
            $fee = $('#class-fee-select').find(":selected").attr('f-data').split(' ');
            $fee = (parseFloat($fee[1].replace(',',''))) ? (parseFloat($fee[1].replace(',',''))) : 0;

            $total_fee = parseFloat($('#total-fee').attr('data'));
            if ($total_fee < $fee) {$is_valid = false;$('#total-fee-err').show();}
        }

        return $is_valid;
    }
</script>
<style>
    .xdsoft_datetimepicker .xdsoft_year{
        display: none;
    }
</style>