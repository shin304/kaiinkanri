{{--法人の場合--}}
@if ( $pschool['student_category'] == 2)
<div style="padding-bottom: 10px">
    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <p class="form_error_p">* {{$error}}</p>
        @endforeach
    @endif

    <p>
        参加人数<span style="color:red">∗</span>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="number" name="join_student_number" min="1" max="{{$data['remain_student']}}"
               value="{{ old('join_student_number', request('join_student_number')) }}"/>
        &nbsp;&nbsp;※最大参加人数 : {{$data['remain_student']}}
    </p>
</div>
@else
    <input type="hidden" name="join_student_number" value="1"/>
@endif