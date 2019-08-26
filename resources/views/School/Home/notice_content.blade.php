<ul>
@if ($notices)
    @foreach ($notices as $key => $notice)
        <li>
            @if (is_null($notice['view_date']) || $notice['date'] > $notice['view_date'] )
                <p class="new">NEW</p>
            @else 
                <p></p>
            @endif
            @if ( $notice['notice_type'] == 'activity' )
                <a href="/school/invoice/detail?id={{$notice['invoice_header_id']}}&invoice_year_month={{$notice['year']}}-{{$notice['month']}}" class="notice_invoice">
                {{ Carbon\Carbon::parse($notice['date'])->format('Y-m-d') }} {{$notice['parent_name']}}さんの支払期限が過ぎました。
                <input type="hidden" name="id" value="{{$notice['invoice_header_id']}}">
                </a>
            @elseif ( $notice['notice_type'] == 'news' )
                <a href="/school/mailMessage/select?relative_id={{$notice['course_id']}}&msg_type_id=3&event_type_id=2&enable_send_mail={{$notice['send_mail_flag']}}" class="notice_event">
                {{ Carbon\Carbon::parse($notice['date'])->format('Y-m-d') }} 「{{$notice['title']}}」へ「{{$notice['student_name']}}」さんから申し込みがありました。
                <input type="hidden" name="id" value="{{$notice['entry_id']}}">
                </a>
            @elseif ( $notice['notice_type'] == 'program' )
                <a href="/school/mailMessage/select?relative_id={{$notice['program_id']}}&msg_type_id=5&event_type_id=3&enable_send_mail={{$notice['send_mail_flag']}}" class="notice_program">
                        {{ Carbon\Carbon::parse($notice['date'])->format('Y-m-d') }} 「{{$notice['title']}}」へ「{{$notice['student_name']}}」さんから申し込みがありました。
                        <input type="hidden" name="id" value="{{$notice['entry_id']}}">
                </a>
            @endif
        </li>
    @endforeach
@endif
</ul>