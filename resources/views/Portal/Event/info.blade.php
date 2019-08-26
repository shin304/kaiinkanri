@if ($entry && $entry['enter'] == 0){{--不参加--}}
    <p>以下の内容で、「{{$data['course_title']}}」不参加の申し込みを受け付けしました。</p>
@elseif ($entry && $entry['enter'] == 1 && $entry['payment_method']){{--参加--}}
    <p>以下の内容で、「{{$data['course_title']}}」参加の申し込みを受け付けしました。</p>
@else{{--未答え--}}
    <p>「{{$data['course_title']}}」のお知らせ</p>
@endif
<table class="table_input">
    <colgroup>
        <col width=25%>
        <col width=75%>
    </colgroup>
@if ($entry && $entry['enter'] == 1 && $entry['payment_method']){{--参加--}}
    <tr>
        <td class="text_title">受付番号</td>
        <td>{{ $entry['code'] }}</td>
    </tr>
    <tr>
        <td class="text_title">イベント名</td>
        <td>{{$data['course_title']}}</td>
    </tr>
@else{{--不参加と未答えの場合--}}
    <tr>
        <td class="text_title">イベントコード</td>
        <td>{!! $data['course_code'] !!}</td>
    </tr>
@endif
    <tr>
        <td class="text_title">イベント内容</td>
        <td>{!! $data['course_description'] !!}</td>
    </tr>
    <tr>
        <td class="text_title">開催日時</td>
        <td>
            {{Carbon\Carbon::parse($data['start_date'])->format('Y-m-d H:i')}}
            &nbsp;~&nbsp;
        @if ( Carbon\Carbon::parse($data['start_date'])->format('Y-m-d') == Carbon\Carbon::parse($data['close_date'])->format('Y-m-d') ){{--同じ開催日--}}
            {{Carbon\Carbon::parse($data['close_date'])->format('H:i')}}
        @else
            {{Carbon\Carbon::parse($data['close_date'])->format('Y-m-d H:i')}}
        @endif
        </td>
    </tr>
    <tr>
        <td class="text_title">開催場所</td>
        <td>{{$data['course_location']}}</td>
    </tr>
    @if ($data['teacher_name'])
        <tr>
            <td class="text_title">講師</td>
            <td>{{$data['teacher_name']}}</td>
        </tr>
    @endif

{{--参加人数・参加料金--}}
@if ($entry && $entry['enter'] == 1 && $entry['payment_method']){{--参加--}}
    <tr>
        <td class="text_title">参加料金</td>
        <td>
            @if ($data['fee'] == 0)
                無料
            @else
                &yen;
                @if ( $fee_plan['payment_unit'] == 1){{--　一人当たり　--}}
                    {{ number_format( $data['fee'] * $entry['total_member'], 0 ) }}&nbsp;
                @elseif ( $fee_plan['payment_unit'] == 2){{--全員で--}}
                    {{ number_format( $data['fee'], 0 ) }}&nbsp;
                @endif
                {{--税金--}}
                @if ( $pschool['amount_display_type'] == 0)
                    (税込)
                @elseif ( $pschool['amount_display_type'] == 1)
                    (税別)
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td class="text_title">支払方法</td>
        <td>
            {{\App\Common\Constants::$invoice_type[$pschool['language']][\App\Common\Constants::$PAYMENT_TYPE[$entry['payment_method']]]}}
        </td>
    </tr>

    {{--法人--}}
    @if ( $pschool['student_category'] == 2)
        <tr>
            <td class="text_title">参加人数</td>
            <td>{{$entry['total_member']}}人</td>
        </tr>
    @endif
@else{{--不参加と未答えの場合--}}
    <tr>
        <td class="text_title">参加料金</td>
        <td>
            @if ($data['fee'] == 0)
                無料
            @else
                &yen;
                {{ number_format( $data['fee'], 0 ) }}&nbsp;
                @if ( $fee_plan['payment_unit'] == 1){{--　一人当たり　--}}
                    / 人
                @elseif ( $fee_plan['payment_unit'] == 2){{--全員で--}}
                    / 全員
                @endif
                {{--税金--}}
                @if ( $pschool['amount_display_type'] == 0)
                    (税込)
                @elseif ( $pschool['amount_display_type'] == 1)
                    (税別)
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td class="text_title">募集定員</td>
        @if ($data['non_member_flag'])
            <td>会員：{{$data['member_capacity']}}人 、 非会員：{{$data['non_member_capacity']}}人</td>
        @else
            <td>{{$data['capacity']}}人</td>
        @endif
    </tr>
@endif
{{--end 参加人数・参加料金--}}
    <tr>
        <td class="text_title">募集締切日</td>
        <td>{{Carbon\Carbon::parse($data['recruitment_finish'])->format('Y-m-d')}}</td>
    </tr>
    <tr>
        <td class="text_title">お問い合わせ先</td>
        <td>
            @if ( $agent->isMobile() )
                <a href="tel:+{!! str_replace('-','', $data['contact_number']) !!}">{{$data['contact_number']}}</a>
            @else
                {{$data['contact_number']}}
            @endif
            &nbsp;<a href="mailto:{{$data['contact_email']}}?Subject=Re:{{$data['course_title']}}イベント申し込み">{{$data['contact_email']}}</a>
        </td>
    </tr>
    <tr>
        <td class="text_title">お問い合わせ先　担当者</td>
        <td>{{$data['person_in_charge']}}</td>
    </tr>
    @if($entry['payment_method'] == 'TRAN_BANK' && !empty($pschool['school_bank_info']))
        <tr>
            <td  class="text_title">お振込先口座番号</td>
            <td>
                {{array_get($pschool['school_bank_info'],'bank_name')}}&nbsp;
                {{array_get($pschool['school_bank_info'],'branch_name')}}&nbsp;
                {{array_get($pschool['school_bank_info'],'bank_account_type')}}&nbsp;
                {{array_get($pschool['school_bank_info'],'account_number')}}&nbsp;
                {{array_get($pschool['school_bank_info'],'account_name')}}&nbsp;
                <span style="color: #800000">※振込手数料は、振込者負担でお願いいたします</span>
            </td>
        </tr>
    @endif
</table>
<p style="padding-bottom: 10px;">
@if ($entry && $entry['enter'] == 1 && $entry['payment_method']){{--参加--}}
    ※{{$data['remark_1']}}
@else{{--不参加と未答えの場合--}}
    ※{{$data['remark']}}
@endif
</p>