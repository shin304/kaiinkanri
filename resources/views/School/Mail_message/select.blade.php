@extends('_parts.master_layout')

@section('content')
<script type="text/javascript" src="/js{{$_app_path}}combodate.js"></script>

<script type="text/javascript">
var seat_remain = {{request('total_capacity') - $joined_memmber_no}};
var application_deadline = {{(request('application_deadline')) ? 1 : 0}};
$(function() {
    $('#selectall').click(function() {  //on click
        if(this.checked) { // check select status
            $('.question_select').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "question_select"
            });
        }else{
            $('.question_select').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "question_select"
            });
        }
    });
    
    $('#selectallfee, #course_fee_list').change(function() {  //on click
        
        if ( $('#selectallfee').is(":checked")) {
            var plan_id = $('#course_fee_list').val();
            $( ".course_fee_plan" ).val(plan_id);
        } else {
            // reset
            $('.course_fee_plan').prop('selectedIndex', 0);
        }
        
    });

    $("#school_type").change(function(){
        var school_cat = $(this).val();
        if (!school_cat)
        {
            $("#grade_option option").remove();
            $("#grade_option").prepend($("<option>").html("").val(""));
            return;
        }
        $.get(
            "{{$_app_path}}ajaxMailMessage/school",
            {school_cat:school_cat},
            function(data)
            {
                var desc = "年生";
                $("#grade_option option").remove();
                $("#grade_option").append($("<option>").html(desc).val(key));
                for(var key in data.grades)
                {
                    var school_year_id = (parseInt(key)) + 1;
                    var school_year = school_year_id + desc;
                    $("#grade_option").append($("<option>").html(school_year).val(school_year_id));
                }
                $("#grade_option").prepend($("<option>").html("").val(""));
                $("#grade_option").val("");
            },
            "jsonp"
        );
    });
    $("#btn_edit").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}course/courseentry?course_id={{request('relative_id')}}');
        $("#action_form").submit();
        return false;
    });
    $("#btn_return").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}course');
        $("#action_form").submit();
        return false;
    });
    // $("#btn_submit").click(function() {
    //     $("#action_form").attr('action', '{{$_app_path}}mailMessage/completemail');
    //     $("#action_form").submit();
    //     return false;
    // });
    $("#btn_student_search").click(function() {
        $("#action_form").attr('action', '{{$_app_path}}mailMessage/select');
        $("#action_form").submit();
        return false;
    });

    // 仕様変更2017-05-11
    // datepickerの動的追加に対応
    $.datetimepicker.setLocale('ja');

    jQuery(function(){
        jQuery('.DateTimeInput').datetimepicker({
            format: 'Y-m-d H:i', 
            step : 5,
            minDate: new Date(),
            scrollMonth : false,
            scrollInput : false
        });
    });
    jQuery(function(){
        jQuery('.DateInput').datetimepicker({
            format: 'Y-m-d', 
            timepicker:false,
        });
    });
    
    $('#datetime12').combodate({value:  moment().format('hh:mm a')}); 

    $("#dialog_receive_confirm").dialog({
        title: "{{$lan::get('process_payment_title')}}",
        autoOpen: false,
        dialogClass: 'no-close',
        resizable: false,
        width: 370,
        height:200,
        modal: true,
        buttons: {
            "OK": function() {
                $("#dialog_form").submit();
                $( this ).dialog( "close" );
                return false;
            },
            "{{$lan::get('cancel_title')}}": function() {
                $( this ).dialog( "close" );
                return false;
            }
        }
    });

    $("#dialog_prevent_join_member").dialog({
        title: "{{$lan::get('confirm_seats_title')}}",
        autoOpen: false,
        dialogClass: 'no-close',
        resizable: false,
        modal: true,
        buttons: {
            "OK": function() {
                $( this ).dialog( "close" );
                return true;
            }
        }
    });
    $("#dialog_warning").dialog({
        title: "{{$lan::get('confirm_seats_title')}}",
        autoOpen: false,
        dialogClass: 'no-close',
        resizable: false,
        modal: true,
        buttons: {
            "OK": function() {
                $( this ).dialog( "close" );
                return true;
            }
        }
    });
    var is_fist_prevent = null;
    $("#dialog_join_confirm_member").dialog({
        title: "{{$lan::get('state')}}",
        autoOpen: false,
        dialogClass: 'no-close',
        resizable: false,
        modal: true,
        width: 'auto',
        buttons: {
            "OK": function() {
                var btnObj = $(this).data('param_1');
                is_fist_prevent = (is_fist_prevent == null )? $(this).data('param_2'): is_fist_prevent;
                var is_call_by_multi = $(this).data('param_3');

                // application_deadline & select 参加 & first time => dialog notice number
                if (application_deadline == 1 && $("[name=entry_status]:checked").val()==1 && is_fist_prevent) {
                    var join_cnt = 0;
                    if (is_call_by_multi) { // join_cnt = all checked item
                        $('input.question_select:checked').each(function() {
                            join_cnt += ($(this).attr('data-stu-category')==2)? parseInt($(this).attr('data-stu-total')) : 1;
                        });
                    } else {
//                        join_cnt = ($(btnObj).attr('data-stu-category')==2)? parseInt($(btnObj).attr('data-stu-total')) : 1;
                        join_cnt = ($(btnObj).attr('data-stu-category')==2)? parseInt($("[name=total_member]").val()) : 1;
                    }

                    if (join_cnt > seat_remain) {
                        seat_remain = (seat_remain < 0)? 0 : seat_remain;
                        var content = $('#dialog_prevent_join_member').html().replace("0","<b>"+seat_remain+"</b>");
                        $("#dialog_prevent_join_member").html(content);
                        $("#dialog_prevent_join_member").dialog('open');
                        is_fist_prevent = 0;
                        return false;
                    }
                }

                // update one student_id
                if ($('#btn_submit_multi').val() == "" || $('#btn_submit_multi').val() == undefined) {
                    $( this ).find('form').submit();
                }else {
                     $("[name=entry_flag]").val($("[name=entry_status]:checked").val());
                     $("#action_form").attr('action', '{{$_app_path}}mailMessage/entryMulti');
                     $("#action_form").submit();
                }

                is_fist_prevent = 1;
                $( this ).dialog( "close" );
                return false;
            },
            "{{$lan::get('cancel_title')}}": function() {
                $('#btn_submit_multi').val("");
                is_fist_prevent = 1;
                $( this ).dialog( "close" );
                return false;
            }
        }
    });

    $('.personal_payment').click(function() {
        var student_id = $(this).attr('stu_id');
        $("#dialog_receive_confirm input[name=student_id]").val(student_id);
        $("#dialog_receive_confirm").dialog('open');
        return false;
    });

    $("[name=total_member]").keyup(function() {
        if(parseInt($(this).val()) > $(this).attr('max')) {
            $(this).css('color', 'red');
        } else {
            $(this).css('color', '#000');
        }
    });
});

$(function() {
    $(".confirm_button").click(function() {
        return confirm("{{$lan::get('participate_confirm_title')}}");
    });
    $(".cancel_button").click(function() {
        return confirm("{{$lan::get('abstention_confirm_title')}}");
    });
});

$(function() {
        $(".entry_confirm_button").click(function(e) {
            e.preventDefault();
            @if(isset($is_recruitment_finished) && $is_recruitment_finished == 1)
                    $("#dialog_warning").dialog('open');
                    return;
            @endif
            var index = $(".entry_confirm_button").index(this);
            $("#dialog_join_confirm_member input[name=student_id]").val($(this).attr('data-stu-id'));
            $("#dialog_join_confirm_member input[name=_course_fee_plan_id]").val($('[name=_course_fee_plan_id'+index+']').val());

            var student_id = $(this).attr('data-stu-id');
            var _token = "{{csrf_token()}}";
            $.ajax({
                    url: "{{$_app_path}}mailMessage/getEventParentInfo",
                    data: {relative_id : {{request('relative_id')}},event_type_id : {{request('event_type_id')}},student_id: student_id,_token:_token},
                    dataType: 'json',
                    type: 'POST',
                    contentType: "application/x-www-form-urlencoded",
                    success: function(data){
                        if(data.status == true){
                            $("#merge_invoice").html('');
                            $("#unmerge_invoice").html('');
                            var unmerge_invoices = data.message.unmerge_invoice_type;
                            var count = 0;
                            $.each(unmerge_invoices,function (index,value) {
                                if(count==0){
                                    var input = '<label><input type="radio" name ="payment_method_entry" value="'+index+'" checked>'+value+'<label>&nbsp;&nbsp;' ;
                                    count ++;
                                }else{
                                    var input = '<label><input type="radio" name ="payment_method_entry" value="'+index+'">'+value+'<label>&nbsp;&nbsp;' ;
                                }

                                $("#merge_invoice").append(input);
                            })
                            $("#merge_invoice").append("<br/>");

                            var merge_invoice = data.message.merge_invoice_type;
                            $.each(merge_invoice,function (index,value) {
                                if(count==0) {
                                    var input = '<label><input type="radio" name ="payment_method_entry" value="' + index + '" checked>' + value + '<label>&nbsp;&nbsp;';
                                    count ++;
                                }else{
                                    var input = '<label><input type="radio" name ="payment_method_entry" value="' + index + '">' + value + '<label>&nbsp;&nbsp;';
                                }
                                $("#unmerge_invoice").append(input);
                            })
                            $("#unmerge_invoice").append("<br/>");

                        }else{
                            window.location.reload();
                        }
                    },
                });



            // $("#dialog_join_confirm_member").dialog({title:$(this).attr('data-title')});


            // 1:個人,2:法人
            if ($(this).attr('data-stu-category') == 2) {
                $("#dialog_join_confirm_member #participation2").prop('checked', true);

                $("#dialog_join_confirm_member span#2").show();
                $("#dialog_join_confirm_member span#2 input[name=total_member]").val($(this).attr('data-stu-total'));
                $("#dialog_join_confirm_member span#2 input[name=total_member]").attr('max', $(this).attr('data-stu-total'));
                $("#dialog_join_confirm_member span#1").hide();

                if($(this).attr('enter') == 1) {
                    var text = parseInt($(this).text().trim());
                    $("#dialog_join_confirm_member span#2 input[name=total_member]").val(text);
                    // $("#dialog_join_confirm_member #notice_joined_total").html(text); 
                }
            } else {
                
                $("#dialog_join_confirm_member #participation").prop('checked', true);
                $("#dialog_join_confirm_member span#1").show();
                $("#dialog_join_confirm_member span#2").hide();

                // remove total_member
                $("#dialog_join_confirm_member span#2 input[name=total_member]").val(1);
                $("#dialog_join_confirm_member span#2 input[name=total_member]").attr('max', 1);
            }
            $("#dialog_join_confirm_member").data('param_1', $(this)).data('param_2', 1).dialog('open');


            return false;
        });
        $(document).on('change','input[name=is_merge_invoice_entry]' ,function(){

            $('input[name=is_merge_invoice_entry]').each(function () {
                if($(this).is(":checked")){
                    var check = $(this).val();
                    if(check == 1){
                        $("#merge_invoice").hide();
                        $("#unmerge_invoice").show();

                    }else{
                        $("#unmerge_invoice").hide();
                        $("#merge_invoice").show();
                    }
                }
            })
        })

//     $(".entry_confirm_button").on('click', function(e){
//         if (seat_remain < 1 && application_deadline == 1) {
//             $("#dialog_prevent_join_member").dialog('open');
//             return false;
//         }
//         // ソート条件
//         var index = $(".entry_confirm_button").index(this);
//         var select_name = '[name=_course_fee_plan_id'+index+']';
//         var select_val = ($(select_name).val() != undefined)? $(select_name).val(): '';

//         // 検索条件
//         var select_word  = ($('#select_word').val() != undefined)? $('#select_word').val() : '';
//         var select_grade = ($('#select_grade').val() != undefined)? $('#select_grade').val() : '';
//         var select_state = ($('#select_state').val() != undefined)? $('#select_state').val() : '';

//         var dialog_title = $(this).attr('data-title')+"さん";
//         var link = $(this).attr('href');
// ////        link = link+'&_course_fee_plan_id='+select_val;
//         link = link+'&_course_fee_plan_id='+select_val+'&sortorder='+currentSort;
//         link = link+'&select_word=' + select_word + '&select_grade=' + select_grade + '&select_state=' + select_state;
//         e.preventDefault();
//         $( "#dialog_entry" ).dialog({
//           title:dialog_title,
//           autoOpen: false,
//           resizable: false,
//           height:140,
//           modal: true,
//           open: function (event, ui) {
//             $(this).css('overflow', 'hidden'); //this line does the actual hiding
//             },
//           buttons: {
//             "OK": function() {
//               window.location.href = link;
//               // java_post(link);
//               $( this ).dialog( "close" );
//             },
//             "{{$lan::get('cancel_title')}}": function() {
//               $( this ).dialog( "close" );
//             }
//           }
//         });
//         $("#dialog_entry").dialog("open");
//     });

    var currentSort;

//     $(".entry_cancel_button").on('click',function(e) {
//         // ソート条件
//         var index = $(".entry_confirm_button").index(this);
//         var select_name = '[name=_course_fee_plan_id'+index+']';
//         var select_val = ($(select_name).val() != undefined)? $(select_name).val() : '';

//         // 検索条件
//         var select_word  = ($('#select_word').val() != undefined)? $('#select_word').val() : '';
//         var select_grade = ($('#select_grade').val() != undefined)? $('#select_grade').val() : '';
//         var select_state = ($('#select_state').val() != undefined)? $('#select_state').val() : '';

//         var dialog_title = $(this).attr('data-title')+"さん";
//         var link = $(this).attr('href');
// ////        link = $link+'&sortorder='+currentSort;
//         link = link+'&_course_fee_plan_id='+select_val+'&sortorder='+currentSort;
//         link = link+'&select_word=' + select_word + '&select_grade=' + select_grade + '&select_state=' + select_state;
//         e.preventDefault();
//         $( "#dialog_cancel" ).dialog({
//           title:dialog_title,
//           autoOpen: false,
//           resizable: false,
//           height:140,
//           modal: true,
//           open: function (event, ui) {
//             $(this).css('overflow', 'hidden'); //this line does the actual hiding
//             },
//           buttons: {
//             "OK": function() {
//               window.location.href = link;
//               // java_post(link);
//               $( this ).dialog( "close" );
//               return false;
//             },
//             "{{$lan::get('cancel_title')}}": function() {
//               $( this ).dialog( "close" );
//               return false;
//             }
//           }
//         });
//         $("#dialog_cancel").dialog("open");
//     });

    $(".tablesorter").tablesorter({
        // initialization
        headers: {
            2: { sorter: false},
            3: { sorter: false},
            4: { sorter: false},
            5: { sorter: false},
            6: { sorter: false},
        },
        }).bind("sortEnd",
        function(sorter) {
            currentSort = sorter.target.config.sortList;
        }
    );

    $(".header").click(function (e) {
        e.preventDefault();
        if($(this).children().hasClass("fa-chevron-down")){
            $(this).children().removeClass("fa-chevron-down");
            $(this).children().addClass("fa-chevron-up");
        }else if($(this).children().hasClass("fa-chevron-up")){
            $(this).children().removeClass("fa-chevron-up");
            $(this).children().addClass("fa-chevron-down");
        }
    
    });
    // $(document).ready(function(){
    //     var order = "{{--request('sortorder')--}}";
    //     if( order != ""){
    //         if( order == "1,0" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[1,0]]});
    //         } else if( order == "1,1" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[1,1]]});
    //         } else if( order == "2,0" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[2,0]]});
    //         } else if( order == "2,1" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[2,1]]});
    //         } else if( order == "3,0" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[3,0]]});
    //         } else if( order == "3,1" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[3,1]]});
    //         } else if( order == "4,0" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[4,0]]});
    //         } else if( order == "4,1" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[4,1]]});
    //         } else if( order == "5,0" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[5,0]]});
    //         } else if( order == "5,1" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[5,1]]});
    //         } else if( order == "6,0" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[6,0]]});
    //         } else if( order == "6,1" ){
    //             $("#select_mail_sender").tablesorter({sortList:[[6,1]]});
    //         }
    //         currentSort = sorter.target.config.sortList;
    //     }
    // });
});

function save_confirm() {
    $('.message_area1').hide();
    if (!validate()) {
        $('.message_area1').show("blind");
        return;
    }
    var title = '{{$lan::get('send_mail_confirm_title')}}';
    var content = '';
    var action_url = '{{$_app_path}}mailMessage/completemail';
    if ($('#schedule_flag').is(':checked')) {
        content = '{{$lan::get('booking_time_send_title')}}:';
        $('.schedule_date').each(function(){
            content += ' '+$(this).val();
        });
        content += ' <br>';
    } 
    content += '{{$lan::get('send_mail_confirm_content')}}';
    common_save_confirm(title, content,action_url);
}

function confirm_register_multi() {
    $('#btn_submit_multi').val(1);
    // disable block 法人
    $("#dialog_join_confirm_member #participation").prop('checked', true);
    $("#dialog_join_confirm_member span#1").show();
    $("#dialog_join_confirm_member span#2").hide();

    // remove total_member
    $("#dialog_join_confirm_member span#2 input[name=total_member]").val(1);
    $("#dialog_join_confirm_member span#2 input[name=total_member]").attr('max', 1);

    $("#dialog_join_confirm_member").data('param_1', $(this)).data('param_2', 1).data('param_3', 1).dialog('open');

}
function save_confirm2() {
    $('.message_area1').hide();
    var val = $('#execute_method_select').val();
    if (!validate(val)) {
        $('.message_area1').show("blind");
        return;
    }
    
    if (val == '1') {
        save_confirm();
        return false;
    }
    
    var content = '{{$lan::get('and_participate_ok')}}';
    var title = $("#execute_method_select option:selected").text();
    var action_url = '{{$_app_path}}mailMessage/entry2';
    common_save_confirm(title, content,action_url);
    
    if (application_deadline == 1) {
        var join_cnt = 0;
        $('input.question_select:checked').each(function() {
            join_cnt += ($(this).attr('data-stu-category')==2)? parseInt($(this).attr('data-stu-total')) : 1;
        });

        if (join_cnt > seat_remain) {
            seat_remain = (seat_remain < 0)? 0 : seat_remain;
            var content = $('#dialog_prevent_join_member').html().replace("0","<b>"+seat_remain+"</b>");
            $("#dialog_prevent_join_member").html(content);
            $("#dialog_prevent_join_member").dialog('open');
        }
    }
}

function validate(val) {
    $('.message_area1 .error_message').hide();

    var is_valid = true;
    if ($('input.question_select:checked').length == 0) {
        is_valid = false;
        $('.message_area1 #select_destination').show();
    }

    if ($('#schedule_flag').is(':checked')) {
        if ($('.schedule_date').val() == "") {
            is_valid = false;
            $('.message_area1 #schedule_date_required').show();
        }
    }
    if (val=='') {
        is_valid = false;
        $('.message_area1 #select_execute').show();
    }
    return is_valid;
}
</script>
<script type="text/javascript">
function nextForm(event)
{
    if (event.keyCode == 0x0d)
    {
        var current = document.activeElement;

        var forcus = 0;
        for( var idx = 0; idx < document.action_form.elements.length; idx++){
            if( document.action_form[idx] == current ){
                forcus = idx;
                break;
            }
        }
        document.action_form[(forcus + 1)].focus();
    }
}
window.document.onkeydown = nextForm;
</script>
<style type="text/css">
@if (!request('editable'))
.disable_edit {
    display: none;
}
@endif

.fee_format {
    width: 95px;
    text-align: right;
    height: auto;
    display: block;
}

.submit2 {
    color: #595959;
    height: 30px;
    border-radius: 5px;
    background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
    font-size: 14px;
    font-weight: normal;
    text-shadow: 0 0px #FFF;
}

</style>
    <div id="center_content_header" class="box_border1">
            <h2 class="float_left"><i class="fa fa-list-alt"></i>{{$lan::get('main_title')}}</h2>
            <div class="center_content_header_right">
                <div class="top_btn">
                </div>
            </div>
            <div class="clr"></div>
        </div><!--center_content_header-->
        {{--<div id="topic_list" style="padding: 5px 10px;background:#B0AaA4;color:#fbfbfb;">
         {!! Breadcrumbs::render('school_mail_select') !!} 
        </div>
        @include('_parts.topic_list')--}}
        <div id="section_content">
            <h3 id="content_h3" class="box_border1">{{$lan::get('detail_info')}}</h3>

            <div class="info_content padding1 box_border1">
                <p class="info_name p32">@if (request('event_type_id')==1) {{$lan::get('implementation')}}@else{{$lan::get('holding')}}@endif{{$lan::get('announcement_of')}}</p>
                <p class="info_info p18">{{sprintf($lan::get('we_will_guide_you'),request('event_name'))}}</p>
                <p style="width: 70%;text-align: right">
                    @if($edit_auth)
                    <button type="button" class="submit2" id="btn_edit" ><i class="fa fa-pencil-square-o"></i>{{$lan::get('edit_page_title')}}</button>
                    @endif
                </p>
                <div class="clr"></div>
                <!-- 仕様変更2017/05/10開始-->
                <table id="table3_2" style="width: 70%">
                <colgroup>
                    <col width="25%"/>
                    <col width="25%"/>
                    <col width="25%"/>
                    <col width="25%"/>
                </colgroup>
                <tbody>
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('event_code_title')}}
                        </td>
                        <td class="t3_2td3" colspan="3" style="text-align: left;">
                        {{request('course_code')}}
                        </td>
                    </tr>
                    <!-- 募集締切日 -->
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('recruitment_start_title')}}
                        </td>
                        <td class="t3_2td3 @if (!request('editable')) error_message @endif">
                        @if (request('recruitment_start')) {{date('Y-m-d', strtotime(request('recruitment_start')))}} @endif
                        </td>
                        <td class="t3_2td2">
                            {{$lan::get('recruitment_deadline_title')}}
                        </td>
                        <td class="t3_2td3 @if (!request('editable')) error_message @endif">
                        @if (request('recruitment_finish')) {{date('Y-m-d', strtotime(request('recruitment_finish')))}} @endif</td>
                    </tr>
                    <!-- 開催日時 -->
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('start_date_time')}}
                        </td>
                        <td class="t3_2td3">
                        @if (request('start_date')) {{date('Y-m-d H:i', strtotime(request('start_date')))}} @endif</td>
                        <td class="t3_2td2">
                            {{$lan::get('end_date_time')}}
                        </td>
                        <td class="t3_2td3">
                        @if (request('close_date')){{date('Y-m-d H:i', strtotime(request('close_date')))}} @endif 
                        </td>
                    </tr>
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('event_location_title')}}
                        </td>
                        <td colspan="3">
                            {{request('course_location')}}
                        </td>
                    </tr>
                    @if (count($teacher_list) > 0)
                    <!-- 講師 -->
                    <tr>
                        <td class="t3_2td2">{{$lan::get('lecturer')}}</td>
                        <td class="t3_2td3" style="text-align:left;" colspan="3">
                            @foreach ($teacher_list as $teacher)
                            <li style="margin-bottom:0px;list-style-type:none;">{{array_get($teacher,'coach_name')}}</li>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    <!-- 担当者 -->
                    <tr>
                        <td class="t3_2td2">{{$lan::get('person_in_charge_title')}}</td>
                        <td class="t3_2td3" style="text-align:left;" colspan="3">
                            {{request('person_in_charge1')}} <br>
                            {{request('person_in_charge2')}}
                        </td>
                    </tr>
                    <tr>
                        <td  class="t3_2td1" colspan="4">
                        {{$lan::get('tuition_fee')}}
                        </td>
                    </tr>
<!-- 受講料を可変化 -->
                    @foreach ($course_fee as $idx1=>$fee)
                    <tr>
                        <td class="t3_2td2">
                            {{array_get($fee,'name')}} {{$lan::get('tuition_fee')}}
                        </td>
                        <td class="" colspan="3">
                            @if (array_get($fee,'fee'))
                                <label class="fee_format">{{number_format(array_get($fee,'fee'))}}&nbsp;{{$lan::get('circle')}}</label>
                            @else
                                @if (array_get($fee,'fee') !=null ||  array_get($fee,'fee') !='')
                                    <label class="fee_format">{{array_get($fee,'fee')}}&nbsp;{{$lan::get('circle')}}</label>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @forelse ($course_fee as $row)
                    @empty
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('tuition_fee')}}
                        </td>
                        <td class="t3_2td3" colspan="3">{{$lan::get('course_fee_not_found')}}</td>
                    </tr>
                    @endforelse
                <!-- 受講料を可変化 -->

                </tbody>
            </table>
            <table id="table3_2">
                <colgroup>
                    <col width="50%"/>
                    <col width=""/>
                </colgroup>
                <tbody>
                @php
                    $seat_remain = request('total_capacity') - $joined_memmber_no;
                @endphp
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('total_member_title')}}/{{$lan::get('member_capacity_title')}}
                        </td>
                        <td class="t3_2td3">
                        @if (request('total_capacity'))
                            {{$joined_memmber_no}}/{{request('total_capacity')}}
                        @else
                            {{$joined_memmber_no}}/_
                        @endif
                        {{$lan::get('person_title')}}</td>
                    </tr>
                    <tr>
                        <td class="t3_2td2">
                            {{$lan::get('total_fee_title')}}
                        </td>
                        <td class="t3_2td3">{{number_format($total_fee)}} {{$lan::get('circle')}}</td>
                    </tr>
                    
                    
                </tbody>
            </table>
            <!-- 仕様変更2017/05/10終了-->
            </div>

            <div id="center_header">
            <h3 id="content_h3" class="box_border1">
                @if( request('enable_send_mail'))
                    {{$lan::get('email_destination_selection')}}
                    @else
                    {{$lan::get('member_select_title')}}
                @endif
            </h3>
            </div>
            <form name="action_form" id="action_form" method="post" enctype="multipart/form-data" >
            {{ csrf_field() }}
                <input type="hidden" name="enable_send_mail" value="{{request('enable_send_mail')}}">
            <div class="search_box box_border1 padding1"><!-- 検索の入力ボックスの両サイドの余白 -->
                    <!-- include file="pages_parts/hidden.html"}} -->
                    <!-- include file="pages_pc/school/_parts/student_search3.html"}} -->

                   @include('_parts.student.student_search6')
            </div>

            <div id="section_content_in">
            @if (isset($failed_deli_list))
                {{$lan::get('following_email_sent_billing_failed')}}
                @foreach ($failed_deli_list as $parent_name)
                    {{$parent_name}}
                @endforeach
            @endif
                @if(isset($request->error_send_mail))
                    @if($request->error_send_mail)
                        <ul class="message_area">
                            <li class="error_message">{{$request->error_send_mail}}</li>
                        </ul>
                    @endif
                @endif
            @if (count($errors) > 0) 
            <ul class="message_area"> 
            @foreach ($errors->all() as $error)
            <li class="error_message">{{ $error }}</li>
            @endforeach
            </ul>
            @php
                session()->pull('errors');
            @endphp
            @endif 
                <ul class="message_area message_area1" style="display: none;">
                <!-- TODO あとで実装すること -->
                <li class="error_message" id="select_execute">{{$lan::get('select_execute_title')}}</li>
                <li class="error_message" id="select_destination">{{$lan::get('please_select_destination')}}</li>
                <li class="error_message" id="schedule_date_required">{{$lan::get('schedule_date_required')}}</li>
                </ul>

                <div class="disable_edit">
                    @if( request('enable_send_mail')) <pre> {{$lan::get('please_select_email_destination')}}</pre> @endif
                &nbsp;<input type="checkbox" id="selectall" >&nbsp;&nbsp;<label for="selectall">{{$lan::get('select_all')}}</label> 

                {{--
    // comment send_mail & set join to student by select all students (just html, js & php code is not deleted)
                <span style="padding-left: 200px">
                   <select style="width:180px;" id="execute_method_select" name="exe_method">
                    <option value=""></option>
                    <option value="1">{{$lan::get('select_send_mail_title')}}</option>
                    <option value="2">{{$lan::get('select_join_title')}}</option>
                    </select> 
                    <input type="button" name="" value="{{$lan::get('execute_title')}}" style="font-weight: 700;" onclick="save_confirm2()">
                </span>
    // comment set type to student by select all students (just html, js & php code is not deleted)
                <div class="center_content_header_right">
                <div class="top_btn">
                <input type="checkbox" id="selectallfee">&nbsp;&nbsp;<label for="selectallfee">{{$lan::get('select_all')}}</label>&nbsp;&nbsp;
                
                <select  style="width:300px;" name="" id="course_fee_list">
                   @if ($course_fee_plan)
                    @foreach ($course_fee_plan as $key=>$val)
                    <option value="{{$key}}">{{$val['value']}}</option>
                    @endforeach
                    @endif
                </select>
                
                </div>
                </div>
                --}}

                </div>
                <table class="table_list tablesorter" id="select_mail_sender">
                    <thead>
                    <tr>
                        <th class="text_title disable_edit" style="width:50px;text-align:center;"> {{$lan::get('selection')}} </th>
                        <th class="text_title header" style="width:160px;"> {{$lan::get('member_name')}} <i style="font-size:12px;" class="fa fa-chevron-down"> </i></th>
                        <th class="text_title" style="width:180px;"> {{$lan::get('membership_number')}}</th>
                    {{-- <th class="text_title " style="width:50px;"> {{$lan::get('student_type_title')}} </th> --}}
                    {{--<th class="text_title " style="width:80px;"> {{$lan::get('number_transmissions')}} </th>--}}
                        <th class="text_title " style="width:100px;"> {{$lan::get('state')}} </th>
                        <th class="text_title " style="width:80px;"> {{$lan::get('payment_status_title')}} </th>
                    {{-- <th class="text_title " style="width:100px;"> {{$lan::get('confirmed')}} </th> --}}
                        <th class="text_title " style="width:80px;">支払方法</th>
                        <th class="text_title " style="width:20px;">定期</th>
                        <th class="text_title " style="width:310px;">{{$lan::get('tuition_type_rates')}} </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($list as $student)
                    <tr>
                        <td style="width:50px;text-align:center;" class="disable_edit">
                            @if( request('enable_send_mail') || array_get($student,'student_category') == 1)
                                <input type="checkbox" name="student_ids[{{$loop->index}}]" id="student_ids_{{$loop->index}}" value="{{array_get($student,'student_id')}}"
                             @if (array_get($student,'selected') == 1)  checked="checked" @endif class="question_select" data-stu-category="{{array_get($student,'student_category')}}" data-stu-total="{{array_get($student,'total_member')}}">
                            @endif
                             
                             <!-- 法人 -->
                             @if (array_get($student,'student_category') == 2)
                                <input type="hidden" name="student_total[{{array_get($student,'student_id')}}]" value="{{array_get($student,'total_member')}}">
                             @endif
                        </td>
                        <td style="width:160px;">
                            <label for="student_ids_{{$loop->index}}">{{array_get($student,'student_name')}}</label>
                        </td>
                        <td style="width:180px;">
                            <label for="student_ids_{{$loop->index}}">{{array_get($student,'student_no')}}</label>
                        </td>
                        <td style="width:100px;text-align: center" >
                            <button type="button" class="entry_confirm_button btn2
                                @if (!(array_get($student,'eid')) || ( (array_get($student,'enter')) && is_null(array_get($student,'payment_selected')))) not_reply @elseif (array_get($student,'enter') == '0') non_enter @else entered @endif"
                                    {{--                                @if (!request('editable')) disabled @endif--}}
                                    data-stu-id="{{array_get($student,'student_id')}}" data-stu-category="{{array_get($student,'student_category')}}"
                                    data-stu-total="{{array_get($student,'total_member')}}" data-title="{{array_get($student,'student_name')}}"
                                    enter="{{array_get($student,'enter')}}"
                                    @if(!$edit_auth) disabled @endif
                            >
                            @if (!(array_get($student,'eid')) || ( (array_get($student,'enter')) && is_null(array_get($student,'payment_selected'))))
                                <!-- 未応答 -->
                                {{$lan::get('not_reply_title')}}
                            @else
                                @if (array_get($student,'enter'))
                                    <!-- 法人: No人参加 -->
                                    @if (array_get($student,'student_category') == 2)
                                        {{array_get($student, 'joined_total')}}{{$lan::get('person_title')}}{{$lan::get('participation')}}
                                    @else
                                        <!-- 個人：　参加 -->
                                        {{$lan::get('participation')}}
                                    @endif
                                @else
                                    <!-- 不参加 -->
                                        {{$lan::get('nonparticipation')}}
                                    @endif
                                @endif
                            </button>

                        </td>
                        <td style="width:80px;" >
                            @if (array_get($student,'status') == 1 && array_get($student,'enter') && array_get($student,'payment_selected'))
                                {{$lan::get('payment_title')}}
                            @elseif (array_get($student,'status') == '0' && array_get($student,'enter') && array_get($student,'payment_selected'))
                                {{$lan::get('not_payment_title')}}
                            @endif
                        </td>
                        <td style="width:80px;text-align: center">
                            @if(isset($student['invoice_type']))
                                <li style = "list-style-type: none; margin : auto; width : 120px; border-radius: 5px;background-color: {{$invoice_background_color[$student['invoice_type']]['top']}} ; background: linear-gradient(to bottom, {{$invoice_background_color[$student['invoice_type']]['top']}} 0%, {{$invoice_background_color[$student['invoice_type']]['bottom']}} 100%); color :white ; font-weight: 500" >
                                    {{$invoice_type[$student['invoice_type']]}}
                                </li>
                            @endif
                        </td>
                        <td style="width:20px;text-align: left">
                            <input type="checkbox" disabled @if(isset($student['is_merge_invoice'])&&$student['is_merge_invoice']==1) checked @endif>
                        </td>
                        <td style="width:310px;">
                            @if ($course_fee_plan)
                                {{-- Case: join or over-recruitment_finish => show text --}}
                                @if ( (array_get($student,'enter') && array_get($student,'payment_selected')) || !request('editable'))
                                    @php
                                        $fee_plan = array_get($course_fee_plan,$student['plan_id']);
                                        $fee_plan['total_fee'] = 0;
                                        $joined_student = $student['joined_total'] ? $student['joined_total'] : 1;
                                        if (array_get($fee_plan,'payment_unit') == 2) {
                                            $fee_plan['total_fee'] = $fee_plan['fee'];
                                        } elseif (array_get($fee_plan,'payment_unit') == 1) {
                                            $fee_plan['total_fee'] = floor($fee_plan['fee'] * $joined_student);
                                        }
                                    @endphp
                                    @if (array_get($student,'student_category') == 2)
                                        {{array_get($fee_plan,'fee_plan_name')}} | {{array_get($fee_plan,'payment_unit_text')}} | {{array_get($student, 'joined_total')}}{{$lan::get('person_title')}} | {{number_format(array_get($fee_plan,'total_fee'))}} {{$lan::get('circle')}}
                                    @else
                                        {{array_get($fee_plan,'fee_plan_name')}} | {{array_get($fee_plan,'payment_unit_text')}} | {{number_format(array_get($fee_plan,'total_fee'))}} {{$lan::get('circle')}}
                                    @endif
                                @else {{-- Case: non-reply, unjoin or in valid recruitment_finish => show select --}}
                                    <select  style="width:300px;" name="_course_fee_plan_id{{$loop->index}}"
                                             {{--class="@if (!array_get($student,'plan_id')) course_fee_plan @else course_fee_plan_seleted @endif">--}}
                                             class="@if (array_get($student,'delivery_count') > 0) course_fee_plan_seleted @else course_fee_plan @endif">
                                        <!-- selected priority: 1: plan_id , 2 m_student_type_id -->
                                        @foreach ($course_fee_plan as $key=>$val)
                                            <option value="{{$key}}" @if (array_get($student,'plan_id') == $key) selected @elseif (!array_get($student,'plan_id') && array_get($student, 'default_plan_id') == $key) selected @endif>{{$val['fee_plan_name']}} | {{$val['payment_unit_text']}} | {{$val['fee']}} {{$lan::get('circle')}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endif
                        <input type="hidden" name="enter[]" value="{{array_get($student,'enter')}}"/>
                        </td>
                    </tr>
                    @endforeach
                    @forelse ($list as $student)
                    @empty
                    <tr>
                    <td class="error_row" colspan="7">{{$lan::get('no_information_display_title')}}</td>
                    </tr>
                    @endforelse
                    </tbody>

                </table>
                @if( request('enable_send_mail') && $edit_auth)
                <div class="disable_edit" style="margin-top: 30px;margin-bottom: 30px;">
                    {{--<span>{{$lan::get('description_send_mail_schedule')}}</span><br>--}}
                    <input type="checkbox" name="schedule_flag_update" id="schedule_flag" @if (old('schedule_flag', request('schedule_flag'))) checked @endif>&nbsp;&nbsp;<label for="schedule_flag">{{$lan::get('booking_time_send_title')}}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span style="word-spacing: 10px;">
                        <label>{{$lan::get('day_send_title')}}</label>&nbsp;<input form="action_form" type="text" class="DateTimeInput schedule_date" name="schedule_date_update" value="@if (old('schedule_date', request('schedule_date'))) {{date('Y-m-d H:i',strtotime(old('schedule_date', request('schedule_date'))))}} @endif">
                        {{--
                        &nbsp;&nbsp;<label>{{$lan::get('time_send_title')}}</label>&nbsp;<input form="action_form" type="text" id="datetime12" data-format="h:mm A" data-template="hh : mm A" name="schedule_date[]" value="{{request('schedule_time')}}" class="schedule_date"> --}}

                    </span>
                </div>
                @endif
                <div class="exe_button" style="margin-bottom: 30px;">
                    <!--
                    <input type="button" value="戻る" id="btn_return" class="submit3"/>
                     -->
                    @if($edit_auth)
                    @if( request('enable_send_mail'))
                        <button type="button" value="" id="btn_submit" class="submit2 disable_edit" onclick="save_confirm()" ><i style="font-size:16px;" class="fa fa-paper-plane"></i>{{$lan::get('transmission')}}</button>
                    @else
                        <button type="button" value="" id="btn_submit_multi" class="submit2 disable_edit" onclick="confirm_register_multi()" ><i style="font-size:16px;" class="fa fa-save"></i>{{$lan::get('register_title')}}</button>
                        <input type="hidden" name="entry_flag" value="">
                    @endif
                    @endif
                    <button type="button" value="" class="submit2" id="btn_return" ><i style="font-size:16px;" class="fa fa-arrow-circle-left"></i>{{$lan::get('return_title')}}</button>
                </div>
            </div>
            </form>
        </div> <!-- section_content -->

<div id="dialog_entry" class="no_title" style="display:none;">
    {{$lan::get('and_participate_ok')}}
</div> <!-- dialog_receive_check -->
<div id="dialog_cancel" class="no_title" style="display:none;">
    {{$lan::get('and_absence_ok')}}
</div> <!-- dialog_receive_check -->
<div id="dialog_warning" class="no_title" style="display:none;">
    募集締切日を過ぎています
</div> <!-- dialog_warning when recruitment finished -->
<div id="dialog_receive_confirm" style="display:none;">
        <form method="post" id="dialog_form" action="{{$_app_path}}mailMessage/updateIsReceived" >
            {{--@foreach ($invoice_ids as $idx => $row)
                <input type="hidden" name="invoice_ids[]" value="{{$row}}"/>
            @endforeach --}}
            {{ csrf_field() }}
                <input type="hidden" name="student_id" value=""/>
                <input type="hidden" name="relative_id" value="{{request('relative_id')}}"/>
                <input type="hidden" name="msg_type_id" value="{{request('msg_type_id')}}"/>
                <input type="hidden" name="event_type_id" value="{{request('event_type_id')}}"/>
                <input type="hidden" name="event_name" value="{{request('event_name')}}"/>
        <table>
            <tr>
                <td style="width:100px;">
                    {{$lan::get('payment_category_title')}}
                </td>
                <td>
                    <select name="payment_type">
                        @foreach($payment_method as $key=>$item)
                        <option value="{{$key}}">{{$item}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td style="width:100px;">
                    {{$lan::get('payment_day_title')}}
                </td>
                <td>
                    <input class="DateInput" type="text" name="payment_date" value="">
                </td>
            </tr>
            {{--<tr>
                <td style="width:100px;">
                    <input type="checkbox" name="dialogs_receipt" value="1"/>{{$lan::get('receipt_issue_title')}}
                </td> 
                <td>
                </td>
            </tr>--}}
        </table>
        </form>
    </div> <!-- dialog_receive_confirm -->
    <div id="dialog_prevent_join_member" style="display:none;">
        {{$lan::get('only_few_seat_title, $seat_remain')}}
    </div>

    <div id="dialog_join_confirm_member" style="display: none;">
        <form action="{{$_app_path}}mailMessage/entry" method="" >
            {{ csrf_field() }}
            <input type="hidden" name="student_id" value=""/>
            <input type="hidden" name="relative_id" value="{{request('relative_id')}}"/>
            <input type="hidden" name="msg_type_id" value="{{request('msg_type_id')}}"/>
            <input type="hidden" name="event_type_id" value="{{request('event_type_id')}}"/>
            <input type="hidden" name="_course_fee_plan_id" value="">
            <input type="hidden" name="enable_send_mail" value="{{request('enable_send_mail')}}">

            <div class="dialog-content">
            <span id="1">
                <br>
                <input type="radio" name="entry_status" id="participation" value="1" checked/>&nbsp;<label for="participation">{{$lan::get('participation')}}</label><br/>
                <!-- 個人 -->
            </span>
            <span id="2">
                <!-- <span id="notice_joined_total" style="color: #00008B;"></span><br> -->
                <input type="radio" name="entry_status" id="participation2" value="1" checked/>
                <!-- 法人 -->
                <input type="number" name="total_member" min="1" value="" style="width: 50px">&nbsp;
                <label for="participation2">{{$lan::get('number_member_confirm_title')}}</label><br/>
            </span>

            <div id="merge_content" style="margin-left: 20px;">
                支払方法<br/>
                <span id="merge_invoice"></span>
                <span id="unmerge_invoice" style="display: none;"></span>
                @if($is_merge_invoice)
                定期請求と&nbsp;<label><input type="radio" name="is_merge_invoice_entry" value="0" checked>まとめない</label>
                                <label><input type="radio" name="is_merge_invoice_entry" value="1">まとめる</label>
                @endif
            </div>

            <input type="radio" name="entry_status" id="nonparticipation" value="2" />&nbsp;<label for="nonparticipation">{{$lan::get('nonparticipation')}}</label><br/>
            <input type="radio" name="entry_status" id="not_reply" value="3" />&nbsp;<label for="not_reply">{{$lan::get('not_reply_title')}}</label><br/>
            </div> 
        </form>
    </div>
@stop