<ul>
@if ($system_logs)
    @foreach ($system_logs as $key => $system_log)
        <li style="font-size: 14px">
            @if (is_null($system_log['view_date']))
                <p id="new_{{$system_log['id']}}" class="new">NEW</p>
            @else
                <p></p>
            @endif
            <input type="hidden" name="notify_id" id="id_notify_{{$system_log['id']}}" value="{{$system_log['id']}}"> 
            <a id="system_logs_{{$system_log['id']}}" href="#notify_{{$system_log['id']}}" rel="modal:open">{{ Carbon\Carbon::parse($system_log['date'])->format('Y-m-d') }} 「{{$system_log['status']}}」{{$system_log['process']}}</a>
            <div id="notify_{{$system_log['id']}}" class="modal">
                <p>{{$system_log['message']}}</p>
                {{--<a href="#" rel="modal:close">Close</a>--}}
            </div>
            
        </li>
    @endforeach
@endif
</ul>
