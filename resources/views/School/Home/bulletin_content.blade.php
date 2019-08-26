<ul>
@if ($bulletins)
    @foreach ($bulletins as $key => $bulletin)
        <li>
            <i class="fa fa-clipboard"></i>
            <a class="text_link" href="/school/bulletinboard/detail?id={{$bulletin['id']}}&from_home=1">
                {{$bulletin['start_date']}}&nbsp;{{$bulletin['title']}}
            </a>
        </li>
    @endforeach
@endif
</ul>