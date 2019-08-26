<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<meta name="ictel_question_num" content="{{$sequence_no}}">
<meta name="ictel_question_title" content="{{$title}}">
<meta name="ictel_question_tag" content="{{$tags}}">
<meta name="ictel_question_answer_choices" content="{{$choices}}">
<meta name="ictel_question_c_answer" content="{{$c_answer}}">
<meta name="ictel_question_c_answer_percentage" content="{{-- $per_c_answer --}}0"><!--不明-->
<meta name="ictel_question_answer_time" content="5">
<meta name="ictel_question_score" content="{{-- $full_score --}}0">
<link href="css/style.css" rel="stylesheet" type="text/css">
<title></title>
</head>
<body>

@if ($tags && 0)
<section class="question_title_box">
<p class="question_main_title"><span class="question_no">{{-- $question_type --}}</span>@if ($tags) {{$tags}} @else リスニングテスト @endif</p>
</section><!--question_title_box終わり-->
@endif


<section id="question_box"><!--問題ボックス-->

<p class="question_title">{!! nl2br($question_text) !!}</p>

@if ($audio_file)
<audio class="audio" controls src="audio/{{$audio_file_name}}"></audio>
@endif

<div class="@if ($choices_text || $choices_file) question_child_box @endif"><!--子問題-->

@if ($choices_text)
<p class="question_title">{!! nl2br($choices_text) !!}</p>
@endif

@if ($choices_file)
<audio class="audio" controls src="audio/{{$choices_file_name}}"></audio>
@endif

@if ($choice_list)
<div class="kaitou clrfix">
	<table class="table" style="width: 100%;">
		@foreach ($choice_list as $mmm=>$choice)
		@if ($choice['choice_file'])
		@if ($loop->first)<tr>@elseif ($mmm % 2 == 0)</tr><tr>@endif
			<td style="padding: 1%;;">
				<div class="kaitou_img" style="width:100%; padding: 0; margin: 0;">
					<img src="image/{{$choice['choice_file_name']}}" width="100%;" >
					@if ($choice_list[2]['choice_file'])
					<span class="text_mark">{{$choice['choice_mark']}}</span>
					<span class="text_word">{!! nl2br($choice['choice_word']) !!}</span>
					@endif
				</div>
			</td>
		@if ($loop->last)</tr>@endif
		@elseif ($choice['choice_word'])
		<tr>
			<td style="text-align: left;">
				<p class="text_answer">
					<span class="text_mark">{{$choice['choice_mark']}}</span>
					<span class="text_word">{!! nl2br($choice['choice_word']) !!}</span>
				</p>
			</td>
		</tr>
		@endif
		@endforeach
	</table>
</div>
@endif

{{--
@if ($choice_list && count($choice_list) == 1)
	@foreach ($choice_list as $mmm=>$choice)
	@if ($choice['choice_file'])<img src="image/{{$choice['choice_file_name']}}" width="100%;" >
	@elseif ($choice['choice_word'])<p class="text_answer">{!! nl2br($choice['choice_word']) !!}</p>
	@endif
	@endforeach
@else
	<div class="kaitou clrfix">
		@foreach ($choice_list as $mmm=>$choice)
		@if ($choice['choice_file'])<div class="kaitou_img"><img src="image/{{$choice['choice_file_name']}}" width="100%;" ><span class="text_mark">{{$choice['choice_mark']}}</span> <span class="text_word">{!! nl2br($choice['choice_word']) !!}</span></div>
		@elseif ($choice['choice_word'])<p class="text_answer"><span class="text_mark">{{$choice['choice_mark']}}</span> <span class="text_word">{!! nl2br($choice['choice_word']) !!}</span></p>@endif
		@endforeach
		<div class="clr"></div>
	</div><!-- kaitou -->
@endif
--}}

</div><!-- question_child_box -->
</section><!-- question_box -->

<section id="answer_box"><!--解答ボックス-->
<p class="explain_title">正解</p>
<p class="answer_p"><span class="answer_no">{{$c_answer}}</span> {{-- $c_answer_word --}} </p>
@if ($description_text)<p class="question_title">{!! nl2br($description_text) !!}</p>@endif
</section>

</body>
</html>
