@extends('_parts.portal.portal_layout')

@section('content')
<script type="text/javascript">
    $(function() {
        $("input[name='complete']").click(function() {
            if ( $("input[name='payment_method']:checked").val() == 'CRED_ZEUS' ) { // クレジットカード決済
                $("#action_form").attr('action', "{{$_app_path}}event/pay");
            }
            $("#action_form").submit();
            return false;
        });
        $("input[name='return']").click(function() {
            $message_key = $("input[name='message_key']").val();
            $("#action_form").attr('action', "{{$_app_path}}event/?message_key="+$message_key);
            $("#action_form").submit();
            return false;
        });
        $("input[name='payment_method']").change(function() {
            if ($(this).is(':checked')) {
                if ( this.value == 'CRED_ZEUS' ) { // クレジットカード決済
                    $("input[name='complete']").val('決済');
                } else {
                    $("input[name='complete']").val('完了');
                }
            }
        });
        $("input[name='payment_method']").change();
    });
</script>
    <div class="title_bar">
        「{{$data['course_title']}}」 イベント申し込み確認
    </div>
    <div class="content_header"></div>
    <div class="content_detail">
        @include('Portal.student_info'){{--学習塾情報--}}

        <form id='action_form' action="{{$_app_path}}event/complete" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('Portal.Event.info'){{--イベント情報--}}

            {{--法人と参加する場合--}}
            @if ( $pschool['student_category'] == 2)
                <div style="padding-bottom: 10px">
                    <p>
                        参加人数 : {{request('join_student_number')}} 人
                        <input type="hidden" name="join_student_number" value="{{request('join_student_number')}}"/>
                    </p>
                    <p>
                        @php
                            if ( $fee_plan['payment_unit'] == 1) {
                                $fee = intval($data['fee'] * request('join_student_number'));
                            } else {
                                $fee = intval($data['fee']);
                            }
                        @endphp
                        参加料金合計 : @if ($fee == 0)無料 @else &yen; {{number_format($fee)}} @endif
                        <input type="hidden" name="fee" value="{{$fee}}"/>
                    </p>
                </div>
            {{--個人と参加する場合--}}
            @elseif ( $pschool['student_category'] == 1)
                <input type="hidden" name="fee" value="{{intval($data['fee'])}}"/>
            @endif

            @include('Portal.payment_method_select')

            @if ($data['is_active'])
                <div class="exe_button">
                    <input class="submit" type="submit" name="complete" value="完了">
                    <input class="submit" type="submit" name="return" value="戻る">
                    <input type="hidden" name="message_key" value="{{request('message_key')}}"/>
                    {{--<input type="hidden" name="join" value="{{$join}}"/>--}}
                </div>
            @endif
        </form>
    </div>
    <div class="content_footer"></div>
@stop

