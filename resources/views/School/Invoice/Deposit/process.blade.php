<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@extends('_parts.master_layout') @section('content')
    <style>
        .content_accordion th {
            padding: 10px!important;
            background-color: #A9BCF5!important;
            border: 0.1px solid black !important;
        }
        .content_accordion td {
            padding: 10px 10px 10px 20px!important;
            background-color: #FBEFEF;
            border: 0.1px solid white !important;
        }
        .list_payment_method {
                  padding: 5px!important;
                   height: 25px;
        }
        .list_invoice{
            width: 25%;
        
         }
        label {
            font-weight: normal;
        }
        th > label {
            font-size: 12px;
            font-weight: bold;
        }
        .select_long {
            width: 270px;
        }
        .panel-group{
            margin-bottom: 0px !important;
        }
        .panel-default{
            border-color: white !important;
        }
        .panel-default .panel-heading:hover{
            background-color: #e8e8e8 !important;
        }
        .panel-body{
            background-color: white;
        }
        .over_content{
            height: 400px;
            overflow: scroll!important;
        }
        .text_date {
            width: 110px;
        }
        ul.message_area {
            margin-bottom: 0;
        }
        .panel-group table tr td {
            padding-left: 10px;
            padding-right: 10px;
        }
        .date_picker_custom {
            left: 450px !important;
        }
        .xdsoft_datetimepicker .xdsoft_month {
            width: 60px !important;
        }
        .xdsoft_datetimepicker {
            padding: 0 !important;
        }
        .btn_select_year_month, .btn_reset_year_month {
            margin-left: 5px;
            margin-top: 4px;
        }

        #accordion_table_header {
            border-bottom: solid 4px #DCDDDD;
            border-top: solid 4px #DCDDDD;
            margin-bottom: 10px !important;
            padding-right: 17px; /* padding for scroll bar y */
        }

        #accordion_table_header table tr td {
            color: #63738c;
            font-weight: bold;
            font-size: 13px;
        }

        #accordion_table_header table tr td:last-child {
            font-size: 15px;
        }

        #accordion_table_header .panel-default .panel-heading:hover {
            background-color: white !important;
        }

        #accordion_table_header .panel-default>.panel-heading {
            background-color: white;
        }
        .search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover, input[type="button"]:hover, #export_csv:hover, .submit_return:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .text_date {
            text-align: center;
            width: 200px;
        }
        .text_proviso {
            width: 332px;
        }
        .empty_tr {
            height: 15px;
        }

        .message_area li {
            float: none !important;
            padding: 0 !important;
        }
        .submit_return {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
    {{--Header--}}
    <div id="center_content_header" >
        <div class="c_content_header_left">
            <h2 class="float_left"><i class="fa fa-yen"></i>{{$lan::get('dp_deposit_management_title')}}</h2><br/>
        </div>
        <div class="clr"></div>
    </div>

    @if (session()->get('deposit_status'))
        <div class="alart_box box_shadow">
            <ul class="message_area">
                <li class="info_message">{{session()->pull('deposit_status')[1]}}</li>
            </ul>
        </div>
    @endif

    {{-- Error message--}}
    @if(request()->has('errors'))
        <div class="alart_box box_shadow">
            <ul class="message_area">
                @foreach(request()->errors as $error)
                    <li class="error_message">{{$lan::get($error)}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="section_content" class="search_box">
        {{--Data--}}
        <form id="deposit_form" action="{{URL::to('/school/invoice/deposit_end_process')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="action" value="{{request()->action}}">
            <input type="hidden" id="password" name="password" value="{{request()->password}}">
            <input type="hidden" name="pass_required" value="{{$pass_required}}">
            @foreach (array_get(request()->all(), 'invoice_header_ids', array()) as $id)
                <input type="hidden" name="invoice_header_ids[]" value="{{$id}}">
            @endforeach
            <table>
                @if ($invoice)
                    <input type="hidden" name="invoice_id" value="{{array_get($invoice, 'id')}}">
                    @if(Session::has('receipt_id'))
                    <input type="hidden" name="receipt_id" value="{{ Session::get('receipt_id')}}">
                    @endif
                    <tr>
                        <th>{{$lan::get('dp_parent_name')}}</th>
                        <td>{{array_get($invoice, 'parent_name')}}</td>
                    </tr>
                    <tr>
                        <th>{{$lan::get('dp_summary')}}</th>
                        @if(array_get($invoice,'is_nyukin')==0)
                        <td>{{Carbon\Carbon::parse(array_get($invoice,'invoice_year_month'))->format('Y年m月')}}{{$lan::get("dp_invoice_name")}}</td>
                        @else(array_get($invoice,'is_nyukin')==1)
                            <td>{{array_get($invoice,'item_name')}}</td>
                        @endif
                    </tr>
                    <tr>
                        <th>{{$lan::get('dp_amount')}}</th>
                        <td>{{number_format(array_get($invoice, 'amount', 0))}}{{$lan::get('dp_currency')}}</td>
                    </tr>
                    <tr class="empty_tr"><td colspan="2"></td></tr>
                    <tr>
                        <th>{{$lan::get('dp_paid_date')}}</th>
                        <td><input type="text" id="paid_date" class="text_date" name="paid_date" value="{{request()->paid_date}}" placeholder="{{$lan::get('dp_paid_date')}}"></td>
                    </tr>
                    <tr>
                        <th>{{$lan::get('dp_invoice_type')}}</th>
                        <td>
                            <select name="deposit_invoice_type" id="deposit_invoice_type" style="width: 200px">
                                <option value="" @if (array_get($invoice,'deposit_invoice_type')=="") selected @endif>{{$lan::get('invoice_select_payment_method_title')}}</option>
                                @foreach ($invoice_types as $type)
                                    <option value="{{array_get($type, 'payment_method_value')}}"  @if (array_get($type, 'payment_method_value') == array_get($invoice,'deposit_invoice_type')) selected @endif>{{$lan::get(array_get($type, 'payment_method_name'))}}</option>
                                @endforeach
                                @foreach ($deposit_types as $id => $name)
                                    <option value="{{$id}}" @if (request()->deposit_invoice_type == $id) selected @endif>{{$name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="empty_tr"><td colspan="2"></td></tr>
                    <tr id="receipt_container" style="@if (request()->deposit_invoice_type != 1) display: none @endif">
                        <td colspan="2">
                            <label style="font-weight: normal">
                                <input type="checkbox" name="receipt" id="receipt" @if (request()->receipt) checked @endif>{{$lan::get('dp_export_receipt')}}
                            </label>
                        </td>
                    </tr>
                    <tr id="proviso_container" style="@if (!request()->receipt) display: none @endif">
                        <th>領収書の但し書き</th>
                        <td colspan="2">
                            <input type="text" class="text_proviso" name="proviso" value="{{array_get(request(), 'proviso', $defaultProviso)}}" placeholder="{{$lan::get('dp_proviso')}}">
                        </td>
                    </tr>
                @endif
                    @if($invoices)
                        <tr class="empty_tr"><td colspan="2"></td></tr>
                        <tr>
                            <th>{{$lan::get('dp_paid_date')}}</th>
                            <td><input type="text" id="paid_date" class="text_date" name="paid_date" value="{{request()->paid_date}}" placeholder="{{$lan::get('dp_paid_date')}}"></td>
                        </tr>
                        <tr>
                            <th>{{$lan::get('dp_invoice_type')}}</th>
                            <td>
                                <select name="deposit_invoice_type" id="deposit_invoice_type" style="width: 200px">
                                    <option value="">{{$lan::get('invoice_select_payment_method_title')}}</option>
                                    @foreach ($invoice_types as $type)
                                        <option value="{{array_get($type, 'payment_method_value')}}" @if (array_get($type, 'payment_method_value') == request()->deposit_invoice_type) selected @endif>{{$lan::get(array_get($type, 'payment_method_name'))}}</option>
                                    @endforeach
                                    @foreach ($deposit_types as $id => $name)
                                        <option value="{{$id}}"@if (request()->deposit_invoice_type == $id) selected @endif>{{$name}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr class="empty_tr"><td colspan="2"></td></tr>
                        <tr id="receipt_container" style="@if (request()->deposit_invoice_type != 1) display: none @endif">
                            <td colspan="2">
                                <label style="font-weight: normal">
                                    <input type="checkbox" name="receipt" id="receipt" @if (request()->receipt) checked @endif>{{$lan::get('dp_export_receipt')}}
                                </label>
                            </td>
                        </tr>
                        <tr id="proviso_container" style="@if (!request()->receipt) display: none @endif">
                            <th>領収書の但し書き</th>
                            <td colspan="2">
                                <input type="text" class="text_proviso" name="proviso" value="{{array_get(request(), 'proviso', $defaultProviso)}}" placeholder="{{$lan::get('dp_proviso')}}">
                            </td>
                        </tr>
                    @endif
            </table>
            @if ($invoices)
       <div id="section_content">
           <div class="panel-group" id="accordion_table_header">
               <div class="panel panel-default">
                   <div class="panel-heading">
                       <table style="width: 100%">
                           <tr>
                               <td class="parent_name sort_parent_name list_invoice" data-sort="1">
                                   {{$lan::get('dp_parent_student_name')}}
                                   <i style="font-size:12px;" class="fa fa-chevron-down"></i>
                               </td>
                               <td class="invoice_date sort_invoice_date list_invoice" data-sort="1">
                                                                {{$lan::get('dp_summary')}}
                                                                <i style="font-size:12px;" class="fa fa-chevron-down"></i>
                               </td>
                               <td class="list_invoice">{{$lan::get('dp_amount')}}</td>
                               <td class="list_invoice">{{$lan::get('dp_payment_method')}}</td>
                
                           </tr>
                           @if (!$invoices)
                               <tr>
                                   <td colspan="8" align="center">{{$lan::get('dp_no_result')}}</td>
                               </tr>
                           @endif
                       </table>
                   </div>
               </div>
           </div>
           <div class="over_content">
               {{--Data--}}
               @foreach ($invoices as $invoice)
                   <div class="panel-group">
                       <div class="panel panel-default">
                           {{--Front Data--}}
                           <div class="panel-heading">
                               <table style="width: 100%;">
                                   <tr>
                                       <td style="width: 25%" >{{array_get($invoice, 'parent_name')}}<span class="parent_name" style="display: none">{{array_get($invoice, 'parent_name_kana')}}</span></td>
                                       @if(array_get($invoice,'is_nyukin')==0)
                                           <td style="width: 25%" class="invoice_date"><a target="_blank" href="{{$_app_path}}invoice/detail?id={{array_get($invoice,'id')}}&invoice_year_month={{array_get($invoice,'invoice_year_month')}}">{{Carbon\Carbon::parse(array_get($invoice,'invoice_year_month'))->format('Y年m月')}}{{$lan::get("dp_invoice_name")}}</a></td>
                                       @elseif(array_get($invoice,'is_nyukin')==1)
                                           <td style="width: 25%" class="invoice_date"><a target="_blank" href="/portal/event/?message_key={{array_get($invoice,'link')}}&view=1">{{array_get($invoice,'item_name')}}</a></td>
                                       @else
                                           <td style="width: 25%" class="invoice_date"><a target="_blank" href="/portal/program/?message_key={{array_get($invoice,'link')}}&view=1">{{array_get($invoice,'item_name')}}</a></td>
                                       @endif
                                       <td style="width: 23%">{{number_format(array_get($invoice, 'amount', 0))}}</td>
                                       <td style="width: 23%">
                                           <?php
                                           $type = $invoice['invoice_type'];
                                           if (array_get($invoice, 'is_recieved') == 1 && !empty( $invoice['deposit_invoice_type'])) {
                                               $type = $invoice['deposit_invoice_type'];
                                           }?>
                                           <li class="list_payment_method" style = "list-style-type: none;text-align: center; margin : auto; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[$type]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[$type]['top']}} 0%, {{$invoice_background_color[$type]['bottom']}} 100%); color :white ; font-weight: 500" >
                                               {{$invoice_type[$type]}}
                                           </li>
                                       </td>
                                       @if(array_get($invoice, 'student_list'))
                                           <td style="width: 2%" class="cursor_pointer drop_down" align="center" data-toggle="collapse" href="#collapse_{{array_get($invoice, 'id')}}">
                                               <i style="font-size:16px;" class="fa fa-chevron-down"></i>
                                           </td>
                                       @else
                                           <td style="width: 2%; font-size: 15px">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                       @endif
                                   </tr>
                               </table>
                           </div>
                           {{--Sub data--}}
                           <div id="collapse_{{array_get($invoice, 'id')}}" class="panel-collapse collapse">
                               <div class="panel-body">
                                   <table style="width: 100%" class="content_accordion" border="1">
                                       <thead>
                                       <tr>
                                           <th  style="width:33%; text-align: center;">{{$lan::get('member_name_title')}}</th>
                                           <th  style="width:33%; text-align: center;">{{$lan::get('dp_student_no')}}</th>
                                           <th  style="width:33%; text-align: center;">{{$lan::get('dp_student_type')}}</th>
                                       </tr>
                                       </thead>
                                       @foreach (array_get($invoice, 'student_list', array()) as $student)
                                           <tr style="height: 30px">
                                               <td style="width: 33%;text-align: center">{{array_get($student, 'student_name')}}</td>
                                               <td style="width: 33%;text-align: center">{{array_get($student, 'student_no')}}</td>
                                               <td style="width: 33%;text-align: center">{{array_get($student, 'student_type_name')}}</td>
                                           </tr>
                                       @endforeach
                                   </table>
                               </div>
                           </div>
                       </div>
                   </div>
               @endforeach
           </div>
           @endif
       </div>
                <div style="margin-top: 20px">
                    <button type="submit" id="btn_submit" class="submit_return"><i class="glyphicon glyphicon-floppy-disk"></i>{{$lan::get('dp_execute')}}</button>
                    <button type="button" id="btn_back" class="submit_return"><i class="glyphicon glyphicon-circle-arrow-left"></i>{{$lan::get('dp_back')}}</button>
                </div>
        </form>
        <br>
      
            @if(request()->has('prev_id'))
                <input type="submit" class="btn_green" id="btn_before" value="{{$lan::get('dp_previous_text')}}">
            @endif
            @if(request()->has('next_id'))
                <input type="submit" class="btn_green" id="btn_after" value="{{$lan::get('dp_next_text')}}">
            @endif
       
    </div>

    <div id="password_dialog">
        <p>{{$lan::get('dp_password')}}</p>
        <form autocomplete="off">
            <input type="password" style="width: 100%" autocomplete="new-password" placeholder="{{$lan::get('dp_password')}}">
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            if ($('#deposit_invoice_type').val() == 1) {
                $('#receipt_container').show(600);
            } else {
                $('#receipt_container').hide(600);
                $('#proviso_container').hide(600);
                $('#receipt').prop('checked', false);
            }
            @if (request()->deposit_receipt == true)
                $('#deposit_form').attr('action', '{{$_app_path}}invoice/deposit_receipt');
                $('#deposit_form').attr('target', '_blank');
                $('#deposit_form').submit();
                $('#deposit_form').attr('action', '{{$_app_path}}invoice/deposit_end_process');
                $('#deposit_form').removeAttr('target');
            @endif
            $.datetimepicker.setLocale('ja');
            $('#paid_date').datetimepicker({
                timepicker: true,
                format:'Y-m-d H:i:s'
            });

            $("#btn_before").click(function() {
                java_post("{{$_app_path}}invoice/deposit_process?action=2&invoice_id={{request('prev_id')}}&password={{request('password')}}");
                return false;
            });

            $("#btn_after").click(function() {
                java_post("{{$_app_path}}invoice/deposit_process?action=2&invoice_id={{request('next_id')}}&password={{request('password')}}");
                return false;
            });

            $("#btn_back").click(function() {
                java_post("{{$_app_path}}invoice/deposit");
                return false;
            });
            $("#password_dialog").dialog({
                title: '確認',
                autoOpen: false,
                dialogClass: "no-close",
                resizable: false,
                modal: true,
                buttons: {
                    "OK": function() {
                        $(this).dialog("close");
                        var password = $('#password_dialog input[type=password]').val();
                        $('#password').val(password);
                        $('#deposit_form').submit();
                    },
                    "Cancel": function() {
                        $(this).dialog("close");
                        $('#password').val('');
                    }
                }
            });


            $(document).on('click', '#btn_submit', function(e) {
                @if(isset($pass_required) && $pass_required == 1)
                var password = $("#password").val();
                if (!password) {
                    e.preventDefault();
                    $('#password_dialog').dialog('open');
                }
                @else
                    $('#deposit_form').submit();
                @endif
            });

            $(document).on('change', '#receipt', function(e) {
                $('#proviso_container').toggle(600);
            });

            $(document).on('change', '#deposit_invoice_type', function(e) {
                if ($(this).val() == 1) {
                    $('#receipt_container').show(600);
                } else {
                    $('#receipt_container').hide(600);
                    $('#proviso_container').hide(600);
                    $('#receipt').prop('checked', false);
                }
            });
        })
        $(".sort_parent_name").click(function (e) {
            e.preventDefault();
            sort_accordion("parent_name",$(this));
        });
        
        $(".sort_invoice_date").click(function (e) {
            e.preventDefault();
            sort_accordion("invoice_date",$(this));
        });
        
        function sort_accordion(className,ele){
                
            if(ele.children().hasClass("fa-chevron-down")){
                ele.children().removeClass("fa-chevron-down");
               ele.children().addClass("fa-chevron-up");
            }else if(ele.children().hasClass("fa-chevron-up")){
                ele.children().removeClass("fa-chevron-up");
                ele.children().addClass("fa-chevron-down");
            }
        var arr_header=[];
        $(".over_content .panel-group").each(function () {
                arr_header.push([$(this).find('.'+className).text(),$(this)]);
            });
        if(ele.data("sort")==1){
                ele.data("sort",2);
                arr_header = arr_header.sort(function(a,b) {
                       return (a[0] === b[0]) ? 0 : (a[0] > b[0]) ? -1 : 1
                        });
           }else{
                ele.data("sort",1);
                arr_header = arr_header.sort(function(a,b) {
                        return (a[0] === b[0]) ? 0 : (a[0] < b[0]) ? -1 : 1
                        });
            }

            $(".over_content").html('');
        arr_header.forEach(function (value) {
               $(".over_content").append(value[1]);
           });
    }
    </script>
@stop
