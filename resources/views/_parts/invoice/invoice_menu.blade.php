<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<div id="center_content_header" >
    @if(isset($heads))
    <div class="c_content_header_left float_left">
        <h2 class="" style="font-weight: 700; display: inline-block">
            {{Carbon\Carbon::parse(array_get($heads,'invoice_year_month'))->format('Y年m月分請求書')}}
        </h2>
        @if(!empty($this_screen))
            @if( $this_screen == 'list' || $this_screen == 'mail_search' || $this_screen == 'confirm' )
                <label>操作 </label>
                <select id = "status_select" style="margin-top: 15px; padding-left : 30px;">
                    <option @if($this_screen == 'list') selected  @endif data-action = "{{$_app_path}}invoice/list?invoice_year_month={{array_get($heads,'invoice_year_month')}}">編集</option>
                    <option @if($this_screen == 'confirm') selected  @endif data-action = "{{$_app_path}}invoice/confirmation?invoice_year_month={{array_get($heads,'invoice_year_month')}}">請求確定</option>
                    <option @if($this_screen == 'mail_search') selected  @endif data-action = "{{$_app_path}}invoice/mailSearch?invoice_year_month={{array_get($heads,'invoice_year_month')}}">請求書送付</option>
                </select>
            @endif
        @endif
    </div>
       {{-- <ul class="progress_ul" style="margin-top: 15px;">
            <a class="text_link" href="{{$_app_path}}invoice/list?invoice_year_month={{array_get($heads,'invoice_year_month')}}">
                @if( array_get($heads,'cnt_entry'))
                    <li class="bill1">{{$lan::get('status_imported_title')}}[{{array_get($heads,'cnt_entry')}}]</li>
                @else
                    <li class="bill1 no_active">{{$lan::get('uncreated_title')}}</li>
                @endif
            </a>
            <li class="bill2 no_active" style="width: 20px; color: black; font-weight: bolder;">&gt;</li>
            <a class="text_link" href="{{$_app_path}}invoice/list?invoice_year_month={{array_get($heads,'invoice_year_month')}}">
                @if( array_get($heads,'cnt_confirm'))
                    <li class="bill2">{{$lan::get('confirmed_title')}}[{{array_get($heads,'cnt_confirm')}}]</li>
                @else
                    <li class="bill2 no_active">{{$lan::get('unsettled_title')}}</li>
                @endif
            </a>
            <li class="bill2 no_active" style="width: 20px; color: black; font-weight: bolder;">&gt;</li>
            <a class="text_link" href="{{$_app_path}}invoice/mailSearch?invoice_year_month={{array_get($heads,'invoice_year_month')}}">
                @if( array_get($heads,'cnt_send'))
                    <li class="bill3">{{$lan::get('invoiced_title2')}}[{{array_get($heads,'cnt_send')}}]</li>
                @else
                    <li class="bill3 no_active">{{$lan::get('uninvoiced_title')}}</li>
                @endif
            </a>
        </ul>--}}
    @endif

    <div class="c_content_header_right">
        @if($edit_auth)
            <ul class="btn_ul">
                @if(!empty($this_screen))
                    @if( $this_screen == 'list' || $this_screen == 'mail_search' || $this_screen == 'confirm' )
                        <li class='popup'>
                            <a class="">{{$lan::get('create_invoice_title')}}</a>
                            <ul class='popup__body'>
                                <li class='popup__list'>
                                    <a id="invoice_generate">{{$lan::get('create_invoice_general_title')}}</a>
                                </li>
                                <li class='popup__list'>
                                    <a id="invoice_create">{{$lan::get('create_invoice_particular_title')}}</a>
                                </li>
                            </ul>
                        </li>
                        <li class='popup'>
                            <a class="">{{$lan::get('external_system_title')}}</a>
                            <ul class='popup__body'>
                                <li class='popup__list'>
                                    <a class="text_link" href="{{$_app_path}}invoice/ricohTransProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('account_tranfer_title')}}</a>
                                </li>
                                <li class='popup__list'>
                                    <a class="text_link" href="{{$_app_path}}invoice/ricohConvProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('convenient_store_title')}}</a>
                                </li>
                                <li class='popup__list'>
                                    <a class="text_link" href="{{$_app_path}}invoice/ricohPostProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('owner_transfer_title')}}</a>
                                </li>
                            </ul>
                        </li>
                    {{--@elseif( $this_screen == 'detail')--}}
                        {{--@if($data)--}}
                            {{--@if($data['is_recieved'] != 1 and $data['workflow_status'] <= 1)--}}
                            {{--<li class="popup__list edit_btn" style="background:#25b4c6;">--}}
                                {{--<a class="edit_btn" href="#edit">{{$lan::get('edit_title')}}</a>--}}
                            {{--</li>--}}
                            {{--@endif--}}
                            {{--@if($data['is_established'] == 0)--}}
                                {{--<li class="popup__list delete_btn" style="background:#25b4c6;">--}}
                                    {{--<a class="delete_btn" href="#delete">{{$lan::get('delete_title')}}</a>--}}
                                {{--</li>--}}
                            {{--@endif--}}
                        {{--@endif--}}
                    {{--@elseif($this_screen == 'ricoh_trans')--}}
                        {{--<li class="no_active" style="background:#25b4c6;">--}}
                            {{--<a href="{{$_app_path}}invoice/ricohTransDownload?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('create_account_request_title')}}</a>--}}
                        {{--</li>--}}
                        {{--<li class="no_active" style="background:#25b4c6;">--}}
                            {{--<a href="{{$_app_path}}invoice/ricohTransUpload?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('create_acount_capture_title')}}</a>--}}
                        {{--</li>--}}
                    {{--@elseif($this_screen == 'ricoh_trans_download' || $this_screen == 'ricoh_trans_upload' )--}}
                        {{--<li class='no_active'  style="background:#25b4c6;">--}}
                            {{--<a  href="{{$_app_path}}invoice/ricohTransProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('account_tranfer_title')}}</a>--}}
                        {{--</li>--}}
                    {{--@elseif($this_screen == 'ricoh_conv')--}}
                        {{--<li class="no_active" style="background:#25b4c6;">--}}
                            {{--<a href="{{$_app_path}}invoice/ricohConvDownload?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('create_account_request_title')}}</a>--}}
                        {{--</li>--}}
                        {{--<li class="no_active" style="background:#25b4c6;">--}}
                            {{--<a href="{{$_app_path}}invoice/ricohConvUpload?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('create_acount_capture_title')}}</a>--}}
                        {{--</li>--}}
                    {{--@elseif($this_screen == 'ricoh_conv_download' || $this_screen == 'ricoh_conv_upload' )--}}
                        {{--<li class='no_active'  style="background:#25b4c6;">--}}
                            {{--<a  href="{{$_app_path}}invoice/ricohConvProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('convenient_store_title')}}</a>--}}
                        {{--</li>--}}
                    {{--@elseif($this_screen == 'ricoh_post')--}}
                        {{--<li class="no_active" style="background:#25b4c6;">--}}
                            {{--<a href="{{$_app_path}}invoice/ricohPostDownload?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('create_account_request_title')}}</a>--}}
                        {{--</li>--}}
                    {{--@elseif($this_screen == 'ricoh_post_download')--}}
                        {{--<li class='no_active'  style="background:#25b4c6;">--}}
                            {{--<a  href="{{$_app_path}}invoice/ricohPostProc?invoice_year_month={{array_get($heads,'invoice_year_month')}}">{{$lan::get('owner_transfer_title')}}</a>--}}
                        {{--</li>--}}
                    @endif
                @endif
            </ul>
        @endif
    </div><!--.c_content_header_right-->
    <div class="clr"></div>
</div>
<div id="dialog_generate" style="display:none;">
</div>
<form method="post" id="frm_generate_invoice" action="">
    {{ csrf_field() }}
    <input type="hidden" value="" name="invoice_year_month"/>
</form>
<script>
    $(function () {

        //select status
        $("#status_select").change(function () {

            var path = $(this).find(":selected").data('action');
            window.location = path;
        })

        // generate bulk invoice
        $("#invoice_generate").click(function () {
            var currentTime = "{{array_get($heads,'invoice_year_month')}}";
            var temp = currentTime.split("-");
            var month = temp[1];
            var year = temp[0];
            var string =("{{$lan::get('confirm_create_batch_title')}}".replace(/%m/g,month)).replace(/%Y/g,year);
            var msg = "<p>" + string + "</p>";
            var link = "{{$_app_path}}invoice/generate?invoice_year_month=" + currentTime;

            $("#dialog_generate").children("p").remove();
            $("#dialog_generate").append(msg);
            $("#dialog_generate").dialog({
                dialogClass: "noTitle",
                title: "{{$lan::get('invoice_batch_title')}}",
                autoOpen: false,
                resizable: false,
                height:140,
                width: 400,
                modal: true,
                buttons: {
                    "{{$lan::get('ok_title')}}": function() {

                        $("#frm_generate_invoice").attr("action","/school/invoice/generate");
                        $("input[name='invoice_year_month']").val(currentTime);
                        $("#frm_generate_invoice").submit();
                        $( this ).dialog( "close" );
                    },
                    "{{$lan::get('cancel_title')}}": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
            $("#dialog_generate").dialog("open");
        })

        //create single invoice : show dialog
        $("#invoice_create").click(function() {
                show_url_dialog("/school/invoice/parentselect?invoice_year_month={{array_get($heads,'invoice_year_month')}}", {
                    title: "{{$lan::get('select_parent_message')}}",
                    width: 950,
                    position: { my: "top+20%", at: "top+5%", of: window }
                });
            return false;
        });
    })
</script>