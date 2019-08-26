@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@if(isset($dataTypeContent->id))
    @section('page_title','編集')
@else
    @section('page_title','新規登録')
@endif
@section('content')
    <script type="text/javascript" src="/js/jquery-hex-colorpicker.js"></script>
    <link type="text/css" rel="stylesheet" href="/css/jquery-hex-colorpicker.css" />
    <style type="text/css">
        .tr_pschool{
            border: 2px solid white;
            color: #00008B;
            background-color: #e8e8e8 !important;
        }
        .tr_pschool:nth-of-type(2n+1) {
            background-color: white!important;
        }
 
        .small_button li{
            width: 65px !important;
            padding : 4px 5px 5px !important;
            margin-right: 3px !important;
            font-size: 12px !important;
        }
        .checkbox_grid li {
            display: block;
            float: left;
            width: 27%;
            padding-left: 0 !important;
            font-weight: normal;
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
            height: 200px;
            overflow: scroll;
        }
       
        .panel-group table tr td {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        #accordion_table_header {
            border-bottom: solid 2px #DCDDDD;
            border-top: solid 2px #DCDDDD;
            margin-bottom: 10px !important;
            padding-right: 17px; /* padding for scroll bar y */
        }
        .boder_box {
            border-left: solid 2px #DCDDDD;
            border-right: solid 2px #DCDDDD;
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
      
        .top_btn li {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
      
        input[type="button"] {
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            text-shadow: 0 0px #FFF;
        }
        
        table tr th {
            text-align: center;
            padding-right: 10px;
        }
        
        .div-btn li:hover, #btn_create_list:hover, #fileAdd:hover, #btn_load_list:hover, #submit2:hover {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .div-btn li, #btn_load_list, #btn_create_list, #fileAdd, .submit2 {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
        .display_calendar {
            display: inline-block;
            position: absolute;
            left: 10%;
        }
    </style>
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">

                    <div class="panel-heading">
                        <h3 class="panel-title">@if(isset($dataTypeContent->id)){{ '編集' }}@else{{ '新規登録' }}@endif</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form"
                          class="form-edit-add"
                          action="@if(isset($dataTypeContent->id)){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->id) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
                          method="post" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                    @if(isset($dataTypeContent->id))
                        {{ method_field("PUT") }}
                    @endif
                    <!-- CSRF TOKEN -->
                        {{ csrf_field() }}
                        <div class="panel-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (request('errors'))
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach (request('errors')->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        <!-- If we are editing -->
                            @if(isset($dataTypeContent->id))
                                <?php $dataTypeRows = $dataType->editRows; ?>
                            @else
                                <?php $dataTypeRows = $dataType->addRows; ?>
                            @endif
                                @if($dataTypeContent)
                                    {{--@foreach($dataTypeContent as $data)--}}
                                        <div class="form-group row">
                                            <label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">件名</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control form-control-sm" name="process" id="colFormLabelSm" placeholder="件名 を入力してください" value="@if(request()->process){{request()->process}}@elseif($dataTypeContent->process){{old( 'process', $dataTypeContent->process)}}@endif">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">内容</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control form-control-sm" name="message"  placeholder="お知らせ内容 件名 を入力してください" rows="4" cols="50">@if(request()->message){{request()->message}}@elseif($dataTypeContent->message){{old( 'message', $dataTypeContent->message)}}@endif</textarea> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="colFormLabelLg" class="col-sm-2 col-form-label col-form-label-lg">対象施設</label>
                                            <div class="col-sm-10">
                                                <div id="section_content">
                                                    <div style="height: 37px">
                                                        <label >
                                                            <input type="checkbox" id="check_all">&nbsp;全て選択
                                                        </label>
                                                    </div>
                                                    <div class="boder_box">
                                                        <div class="panel-group" id="accordion_table_header">
                                                            <table style="width: 100%">
                                                                <tbody>
                                                                <tr style="width: 100%">
                                                                    <th style="width: 10%"></th>
                                                                    <th style="text-align: left" class="invoice_payment_method sort_invoice_payment_method">施設名称
                                                                        <i style="font-size:12px;" class="fa fa-chevron-down"></i>
                                                                    </th>
                                                                    <th style="text-align: left" >契約種別</th>
                                                                </tr>
                                                              
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="over_content">
                                                            <div class="panel-group">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <table style="width: 100%">
                                                                            <tbody>
                                                                                @foreach($list_pschool as $pschool)
                                                                                    <tr style="width: 100%" class="tr_pschool">
                                                                                        @if(isset($list_pschool_notify_id)&&in_array($pschool->id,$list_pschool_notify_id))
                                                                                        <td style="width: 8%"><input type="checkbox" checked class="pschool_header_checkbox " name="list_pschool_id[]" value="{{$pschool->id}}"></td>
                                                                                        @else
                                                                                        <td style="width: 8%"><input type="checkbox"  class="pschool_header_checkbox" name="list_pschool_id[]" value="{{$pschool->id}}"></td>
                                                                                        @endif
                                                                                        <td style="width: 45%;" class="pschool">{{$pschool->name}}</td> 
                                                                                        @if(isset($pschool->plan_name))
                                                                                        <td style="width: 45%;" class="pschool">{{$pschool->plan_name}}</td>
                                                                                        @else
                                                                                        <td style="width: 45%;" class="pschool"></td>
                                                                                        @endif
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">表示期間</label>
                                            <div class="col-sm-10">
                                                <table style="width: 100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 100%">
                                                            <input autocomplete="off" style="width: 45%" class="date" type="text" name="date_from" value="@if (request('date_from')) {{date('Y-m-d',strtotime(request('date_from')))}}@elseif($dataTypeContent->start_date){{ $dataTypeContent->start_date}} @endif" placeholder="開始">
                                                            <input style="width: 13%;text-align: center"  type="text" name="" value="" placeholder="～" readonly>
                                                            <input autocomplete="off" style="width: 40%" class="date" type="text" name="date_to" value="@if (request('date_to')) {{date('Y-m-d',strtotime(request('date_to')))}}@elseif($dataTypeContent->end_date) {{ $dataTypeContent->end_date}} @endif" placeholder="終了">
                                                        </td>
                                                    </tr>
    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">カレンダーに記載</label>
                                            <div class="col-sm-10">
                                                <div style="height: 37px">
                                                    <label>
                                                        <input  id="calendar_flag" name="calendar_flag" type="checkbox" value="1" @if (request('calendar_flag')==1||$dataTypeContent->calendar_flag==1) checked @endif>
                                                    </label>
                                                    <div class="display_calendar">
                                                        <label>
                                                            表示期間
                                                            <input autocomplete="off" class="date" type="text" name="start_display_calendar" value="@if (request('start_display_calendar')) {{date('Y-m-d',strtotime(request('start_display_calendar')))}}@elseif($dataTypeContent->start_calendar_dis){{ $dataTypeContent->start_calendar_dis}} @endif" placeholder="開始">
                                                            ~
                                                            <input autocomplete="off"class="date" type="text" name="end_display_calendar" value="@if (request('end_display_calendar')) {{date('Y-m-d',strtotime(request('end_display_calendar')))}}@elseif($dataTypeContent->end_calendar_dis) {{ $dataTypeContent->end_calendar_dis}} @endif" placeholder="終了">
                                                        </label>
                                                        <p style="margin-top: 2%">
                                                            表示色&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <input name="calendar_color" value="@if (request('calendar_color')){{ request('calendar_color')}}@elseif($dataTypeContent->calendar_color){{$dataTypeContent->calendar_color}}@else @endif"/>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    {{--@endforeach--}}
                                @endif
                        </div><!-- panel-body -->
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">Save</button>
                            <a href="{{ URL::to('admin/oshirasekanri')}}"> <button type="button" class="btn btn-primary" id="btn_back">Return</button></a>
                        </div>
                    </form>
                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                          enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                               onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> Are You Sure</h4>
                </div>

                <div class="modal-body">
                    <h4>Are you sure you want to delete '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">Yes, Delete it!
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {}
        var $image
        

        $('document').ready(function () {
            $("input[name='calendar_color']").hexColorPicker({
                "container":"dialog",
                "style":"hex",
                "outputFormat":"<hexcode>",
                "colorizeTarget":true,
            });
            var init_color = $("input[name='calendar_color']").val();
            console.log(init_color);
            var b = init_color.trim();
            $("input[name='calendar_color']").css('background-color', '#'+b);// init tinymce tool
            tinymce.init({
                selector: 'textarea#message_text',
                menubar:false,
                toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
            });
            $('.toggleswitch').bootstrapToggle();
            if($('#calendar_flag').prop('checked')==true) {
                $('.display_calendar').show(600);
            } else {
                $('.display_calendar').hide(600);

            }
            $(document).on('change', '#calendar_flag', function(e) {
                $('.display_calendar').toggle(100);
            });
            @if ($isModelTranslatable)
            $('.side-body').multilingual({"editing": true});
            @endif
            var date_input=$('.date'); //our date input has the name "date"
            date_input.datepicker({
                dateFormat:'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                step : 5,
                scrollMonth : false,
                scrollInput : false,
                todayHighlight: true,
                autoclose: true,
                
            })
            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', function (e) {
                $image = $(this).parent().siblings('img');

                params = {
                    slug:   '{{ $dataTypeContent->getTable() }}',
                    image:  $image.data('image'),
                    id:     $image.data('id'),
                    field:  $image.parent().data('field-name'),
                    _token: '{{ csrf_token() }}'
                }
                $('.confirm_delete_name').text($image.data('image'));
                $('#confirm_delete_modal').modal('show');
            });
            $(document).on("change", "#check_all", function()  {
                var checked = $(this).prop("checked");
                $(".pschool_header_checkbox").prop("checked", checked);
            });
            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $image.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing image.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
        });
        
        function setYearMonth(isFrom) {
            var datePickerClass = '.date_picker_custom_to';
            var invoiceYearMonthId = '#invoice_year_month_to';
            if (isFrom) {
                datePickerClass = '.date_picker_custom_from';
                invoiceYearMonthId = '#invoice_year_month_from';
            }
            var month = leadingZero(parseInt($(datePickerClass + " .xdsoft_label.xdsoft_month span").text()), 2);
            var year = parseInt($(datePickerClass + " .xdsoft_label.xdsoft_year span").text());
            $(invoiceYearMonthId).val(year + '-' + month);
            $(datePickerClass + ' .xdsoft_date.xdsoft_day_of_week1.xdsoft_date').eq(1).trigger('click');
        }
        function resetYearMonth(isFrom) {
            if (isFrom) {
                $('#invoice_year_month_from').val('');
                $('#invoice_year_month_picker_from').val('').datetimepicker('hide');
            } else {
                $('#invoice_year_month_to').val('');
                $('#invoice_year_month_picker_to').val('').datetimepicker('hide');
            }
        }
        

        $(document).ready(function() {
            $("#common-dialog-confirm").dialog({
                title: '確認',
                autoOpen: false,
                dialogClass: "no-close",
                position: { my: 'top', at: 'top+150' },
                resizable: false,
                modal: true,
                buttons: {
                    "OK": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $(document).on("click",".drop_down",function(e){
                e.preventDefault();
                if ($(this).children().hasClass("fa-chevron-down")) {
                    $(this).children().removeClass("fa-chevron-down");
                    $(this).children().addClass("fa-chevron-up");
                } else if ($(this).children().hasClass("fa-chevron-up")) {
                    $(this).children().removeClass("fa-chevron-up");
                    $(this).children().addClass("fa-chevron-down");
                }
            });

            $(document).on("change", "#check_all", function()  {
                var checked = $(this).prop("checked");
                $(".invoice_header_checkbox").prop("checked", checked);
            });


            $(document).on('change', '#class_filter, #course_filter, #program_filter', function() {
                check_filter();
            });
            check_filter();

            $(document).on("click", "#search_cond_clear", function() {
                $("#form_deposit input[type=text]").val("");
                $("#form_deposit input[type=checkbox]").prop("checked", false);
                $("#class_filter").prop("checked",false);
                $("#course_filter").prop("checked",false);
                $("#program_filter").prop("checked",false);
                check_filter();
                $("#form_deposit select").val("");
            });
            function check_filter(){
                $("#form_deposit .chk_filter").each(function(){
                    var id = $(this).attr('id');
                    if ($(this).is(':checked')) {
                        $('span[href=#'+ id +']').show();
                        $('select[href=#'+ id +']').prop('disabled', false);
                    } else {
                        $('span[href=#'+ id +']').hide();
                        $('select[href=#'+ id +']').prop('disabled', true).val('');
                    }
                })
            }
            
            $(document).on('click', '#export_csv', function(e) {
                $('#href_clone').val($(this).attr('href'));
                $('input:radio#mode1').prop('checked', true);
                var res = $( "#exportcsv_dialog" ).dialog('open');
            });
            
            $(".sort_parent_name").click(function (e) {
                e.preventDefault();
                sort_accordion("parent_name",$(this));
            });

            $(".sort_invoice_date").click(function (e) {
                e.preventDefault();
                sort_accordion("invoice_date",$(this));
            });
            $(".sort_invoice_amount").click(function (e) {
                e.preventDefault();
                sort_accordion("invoice_amount",$(this));
            });
            $(".sort_invoice_payment_method").click(function (e) {
                e.preventDefault();
                sort_accordion("invoice_payment_method",$(this));
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

        })
    
    </script>
    @if($isModelTranslatable)
        <script src="{{ config('voyager.assets_path') }}/js/multilingual.js"></script>
    @endif
    <script src="{{ config('voyager.assets_path') }}/lib/js/tinymce/tinymce.min.js"></script>
    <script src="{{ config('voyager.assets_path') }}/js/voyager_tinymce.js"></script>
    <script src="{{ config('voyager.assets_path') }}/js/slugify.js"></script>
@stop
