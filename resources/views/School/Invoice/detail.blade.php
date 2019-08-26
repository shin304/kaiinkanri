<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@extends('_parts.master_layout') @section('content')
    <script type="text/javascript">
        $(function () {
//	$("a[href='#edit']").click(function() {
            $(".edit_btn").click(function () {
                java_post("{{$_app_path}}invoice/edit?id={{$request['id']}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}");
                return false;
            });
            $("#dialog-confirm").dialog({
                title: "{{$lan::get('invoice_delete_title')}}",
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "{{$lan::get('delete_title')}}": function () {
                        java_post("{{$_app_path}}invoice/deleteInvoice?id={{$data['id']}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}");
                        return false;
                    },
                    "{{$lan::get('cancel_title')}}": function () {
                        $(this).dialog("close");
                    }
                }
            });
//	$("a[href='#delete']").click(function() {
            $(".delete_btn").click(function () {
                $("#dialog-confirm").dialog('open');
                return false;
            });
            $("#btn_confirm_invoice").click(function () {
                java_post("{{$_app_path}}invoice/singleEditComplete?id={{$data['id']}}&invoice_year_month={{array_get($heads,'invoice_year_month')}}");
                return false;
            });
            $(".move_btn").click(function () {
                var id = $(this).data('move_id');
                java_post("{{$_app_path}}invoice/detail?id=" + id + "&invoice_year_month={{array_get($heads,'invoice_year_month')}}");
                return false;
            });
            $("#btn_return").click(function () {
                $("#frm_return").submit();
            })
        });
    </script>
    <style>
        #show_preview:hover, #btn_return:hover, #show_info:hover, .submit_return edit_btn:hover, .submit_return delete_btn:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        #show_preview, #btn_return, .submit_return edit_btn, #show_info, .submit_return delete_btn {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
    @include('_parts.invoice.invoice_menu')

    <h3 id="content_h3" class="box_border1">{{$lan::get('detailed_information_title')}}</h3>
    <div id="section_content">
        {{--@if( $request['action_message'])
        <div class="alart_box box_shadow">
            <ul class="message_area">
                <li class="@if( $request['action_status'] == 'OK')
                                info_message
                            @else
                                error_message
                            @endif">
                    {{$request['action_message']}}
                </li>
            </ul>
            <div id="data_table"></div>
        </div>
        @endif--}}

        <form id="action_form" method="post">
            <div id="center_content_main">
                <input type="hidden" name="id" value="{{$request['id']}}"/>
                @include('_parts.invoice.invoice_form')
                <br/>

                <div id="section_content_in">
                    <div style="margin-bottom: 15px;">
                        @if( ($data['is_established'] < 1 || $data['active_flag'] == "0") && $edit_auth)
                            <div style="float: left">
                                <input type="submit" class="btn_green" id="btn_confirm_invoice"
                                       value="{{$lan::get('determine_invoice_title')}}"/><br/>
                            </div>
                        @endif
                        @if( $this_screen == 'detail')
                            @if($data)
                                <div style="float: right">
                                    <ul class="btn_ul">
                                        @if($data['is_recieved'] != 1 and $data['workflow_status'] <= 1)
                                            <button class="submit_return edit_btn" type="button" style="border-radius: 5px;font-weight: normal;">
                                                <i class="glyphicon glyphicon-edit " style="width: 20%;font-size:16px;"></i>
                                                {{$lan::get('edit_title')}}
                                            </button>
                                        @endif
                                        @if($data['is_established'] == 0)
                                            <button class="submit_return delete_btn" type="button" style="border-radius: 5px;font-weight: normal;">
                                                <i class="glyphicon glyphicon-trash " style="width: 20%;font-size:16px;"></i>
                                                {{$lan::get('delete_title')}}
                                            </button>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </div>
                    @if($pre_id || $next_id )
                        <br/>

                        @if( $pre_id)<input class="btn_green move_btn" data-move_id="{{$pre_id}}"
                                            value="{{$lan::get('previous_title')}}" style="text-align: center"/>@endif
                        @if( $next_id)<input class="btn_green move_btn" data-move_id="{{$next_id}}"
                                             value="{{$lan::get('next_text')}}" style="text-align: center"/>@endif
                        <br/>
                    @endif
                    <br/>
                    <button id="btn_return" class="submit3" type="button"><i
                                class="glyphicon glyphicon-circle-arrow-left"
                                style="width: 20%;font-size:16px;"></i>{{$lan::get('return_title')}}</button>
                </div>
            </div>
        </form>
    </div>
    <form action="/school/invoice/list?invoice_year_month={{array_get($heads,'invoice_year_month')}}" method="post"
          id="frm_return">
        {{ csrf_field() }}
    </form>
@stop
<div id="dialog-confirm" style="display: none;">
    {{$lan::get('confirm_delete_title')}}
</div>

