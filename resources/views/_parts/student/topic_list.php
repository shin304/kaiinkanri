<div id="topic_list"
	style="padding: 5px 10px; background: #B0AaA4; color: #fbfbfb;">
	@foreach ($topic_list as $link => $topic) @if ($loop->last) {{$topic}}
	@else <a class="text_link" href="{{$_app_path}}{{$link}}">{{$topic}}</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;
	@endif @endforeach 会員管理
</div>