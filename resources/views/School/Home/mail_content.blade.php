<ul>
@if ($mails)
    @foreach ($mails as $key => $mail)
        <li>
            @if ( $mail['mail_type'] == 'broadcast' )
                <i class="fa fa-envelope-o"></i>
                <a class="text_link" href="/school/broadcastmail/edit?id={{$mail['id']}}">
                {{$mail['date']}} {{$mail['title']}}
                </a>
            @elseif ( $mail['mail_type'] == 'event' )
                <i class="fa fa-list-alt"></i>
                <a class="text_link" href="/school/mailMessage/select?relative_id={{$mail['event_id']}}&msg_type_id={{$mail['msg_type_id']}}&event_type_id={{$mail['event_type_id']}}&enable_send_mail={{$mail['send_mail_flag']}}">
                {{$mail['date']}} {{$mail['course_title']}}
                </a>
            @endif
        </li>
    @endforeach
@endif
</ul>