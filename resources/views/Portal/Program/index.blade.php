@extends('_parts.portal.portal_layout')

@section('content')
<script type="text/javascript">
    $(function(){
        $('.submit').click(function (e) {
            e.preventDefault();
            var msg = "プログラム「{{$data['program_name']}}」に" + $(this).data("mess") + "<br>よろしいですか。";
            common_confirm("確認", msg, {action: $(this).attr('name')});
        });
    });
</script>
    <div class="title_bar">
        「{{$data['program_name']}}」 プログラム申し込み
    </div>
    <div class="content_header"></div>
    <div class="content_detail">
        @include('Portal.student_info'){{--学習塾情報--}}

        <form id="action_form" action="{{$_app_path}}program/confirm" method="POST" enctype="multipart/form-data" novalidate>
            {{ csrf_field() }}
            @if ( request('payment') == 'fail' ) <p style="color: #ff0000;">クレジットカード決済が振られました。</p> @endif
            @include('Portal.Program.info'){{--プログラム情報--}}

            {{--有効と未答え--}}
            @if ( $data['is_active'] && ( !isset($entry) || ($entry && $entry['enter'] == 1 && empty($entry['payment_method'])) || ($entry && $entry['payment_method'] == App\Common\Constants::CRED_ZEUS && !$entry['invoice_finished'])) && !$request['view'] )
                @include('Portal.input')
                <div class="exe_button">
                    <input class="submit" type="submit" name="join" value="参加" data-mess="参加申込みします。"/>
                    <input class="submit" type="submit" name="unjoin" value="不参加" data-mess="参加しません。"/>
                    <input type="hidden" name="message_key" value="{{request('message_key')}}"/>
                    <input type="hidden" name="action" value=""/>
                </div>
            @endif
        </form>
    </div>
    <div class="content_footer"></div>
@stop

