@extends('_parts.portal.portal_layout')

@section('content')
<script type="text/javascript">
    $(function() {
        $("input[name='return']").click(function() {
            $message_key = $("input[name='message_key']").val();
            $("#action_form").attr('action', "{{$_app_path}}program/?message_key="+$message_key);
            $("#action_form").submit();
            return false;
        });
    });
</script>
    <div class="title_bar">
        「{{$data['program_name']}}」 クレジットカード決済
    </div>
    <div class="content_header"></div>
    <div class="content_detail">
        @include('Portal.student_info'){{--学習塾情報--}}

        <form id="action_form" action="{{$info['payment_link']}}" target="_top" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('Portal.credit_info', array('target' => 'program')){{--プログラム情報--}}

            <div class="exe_button">
                <input class="submit" type="submit" value="決済">
                <input class="submit" type="submit" name="return" value="戻る">
                <input type="hidden" name="message_key" value="{{request('message_key')}}"/>
            </div>
        </form>
    </div>
    <div class="content_footer"></div>
@stop

