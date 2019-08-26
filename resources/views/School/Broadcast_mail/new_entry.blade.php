@extends('_parts.master_layout') @section('content')
    <script type="text/javascript" src="/js/school/mail_template.js"></script>
    <script type="text/javascript">
        $(function () {
            //search cond
            var cond= ['student_name_kana','ASC'];

            search_mail_target();
            $("#dialog-confirm").dialog({
                title: "{{$lan::get('mail_send_confirm')}}",
                autoOpen: false,
                modal: true,

                buttons: {
                    "{{$lan::get('confirm')}}": function () {
                        $(this).dialog("close");
                        @if($request['id'] !== null)
                            $("#action_form").attr('action', '{{$_app_path}}broadcastmail/completeSend');
                        @else
                            $("#action_form").attr('action', '{{$_app_path}}broadcastmail/completeSend');
                        @endif
                        $("#action_form").submit();
                        return false;
                    },
                    "{{$lan::get('close')}}": function () {
                        $(this).dialog("close");
                    }
                }
            });
            $("#btn_submit").click(function () {
                if($("#schedule_flag").prop("checked")==true){
                    if($("#send_datepicker").val()=="" ||$("#send_datepicker").val()==undefined ){
                        $(".error_schedule_date").show();
                        return false;
                    }else{
                        $(".error_schedule_date").hide();
                    }

                    var title = '{{$lan::get('main_title')}}';
                    var content = '{{$lan::get('mail_will_be_send_confirm')}}';
                    var action_url ='';
                        @if($request['id'] !== null)
                        action_url = '{{$_app_path}}broadcastmail/completeSend';
                    @else
                        action_url = '{{$_app_path}}broadcastmail/completeSend';
                    @endif
                    common_save_confirm(title, content,action_url);
                    return false;
                }else{
                    $("#dialog-confirm").dialog('open');
                    return false;
                }
            });
            $("#btn_save").click(function(){
                $("#action_form").attr('action', '{{$_app_path}}broadcastmail/save');
                $("#action_form").submit();
            });

            $("#btn_return").click(function(){

                $("#action_form").attr('action', '{{$_app_path}}broadcastmail');
                $("#action_form").submit();
                return false;
            })
            $("#send_datepicker").change(function(){
                if($("#send_datepicker").val()!= null && $("#send_datepicker").val()!= undefined){
                    $(".error_schedule_date").hide();
                    $("#schedule_flag").prop("checked",true);
                }else{
                    $("#schedule_flag").prop("checked",false);
                }
            })

//search
            $("#btn_search").click(function () {
                search_mail_target();
            });
            $(document).on("click",".sort_student",function(){
                cond=[];
//                if($(this).find('span').hasClass('glyphicon-triangle-bottom')){
//                    $(this).find('span').removeClass('glyphicon-triangle-bottom');
//                    $(this).find('span').addClass('glyphicon-triangle-top');
//
//
//                    if($(".sort_student_no").hasClass('glyphicon-triangle-bottom')){
//                        cond.push('student_no',"ASC");
//                    }else{
//                        cond.push('student_no',"DESC");
//                    }
//                    search_mail_target();
//                }else{
//                    $(this).find('span').removeClass('glyphicon-triangle-top');
//                    $(this).find('span').addClass('glyphicon-triangle-bottom');
//
//                    cond=[];
//                    if($(".sort_student_type").hasClass('glyphicon-triangle-bottom')){
//                        cond.push('student_type_name',"ASC");
//                    }else{
//                        cond.push('student_type_name',"DESC");
//                    }
//                    search_mail_target();
//                }
                if($(this).find('span').hasClass('sort_student_no')){
                    if($(this).find('span').hasClass('glyphicon-triangle-bottom')){
                        $(this).find('span').removeClass('glyphicon-triangle-bottom');
                        $(this).find('span').addClass('glyphicon-triangle-top');
                        cond.push('student_no',"DESC");
                    }else{
                        $(this).find('span').removeClass('glyphicon-triangle-top');
                        $(this).find('span').addClass('glyphicon-triangle-bottom');
                        cond.push('student_no',"ASC");
                    }
                    search_mail_target();
                }else{
                    if($(this).find('span').hasClass('glyphicon-triangle-bottom')){
                        $(this).find('span').removeClass('glyphicon-triangle-bottom');
                        $(this).find('span').addClass('glyphicon-triangle-top');
                        cond.push('student_type_name',"DESC");
                    }else{
                        $(this).find('span').removeClass('glyphicon-triangle-top');
                        $(this).find('span').addClass('glyphicon-triangle-bottom');
                        cond.push('student_type_name',"ASC");
                    }
                    search_mail_target();
                }
            });
            function search_mail_target(){
                var not_member = "";
                var input_search = $("input[name='input_search']").val();
                var id = $("input[name='id']").val();
                var input_search_student_no = $("input[name='input_search_student_no']").val();
                var valid_date_from = $("input[name='valid_date_from']").val();
                var valid_date_to = $("input[name='valid_date_to']").val();
                var class_id = $(".select1").val();
                var student_types_arr =[];
                var parent_list_arr =[];
                var student_list_arr =[];
                var active_flag=0;
                var arr_active_flag =[];
                $("#not_member:checked").each(function () {
                    var not_member = $(this).val();
                })
                $(".student_types:checked").each(function () {
                    var student_types = $(this).val();
                    student_types_arr.push(student_types);
                })
                $(".select1:checked").each(function () {
                    var parent_list_check = $(this).val();
                    parent_list_arr.push(parent_list_check);
                })
                $(".select3:checked").each(function () {
                    var student_list_check = $(this).val();
                    student_list_arr.push(student_list_check);
                })
                $(".active_flag").each(function () {
                    if($(this).is(":checked")){
                        arr_active_flag.push($(this).val());
                    }
                })

                if(arr_active_flag.length ==2 || arr_active_flag.length ==0){
                    active_flag=2
                }else{
                    active_flag = arr_active_flag[0];
                }

                //
                $.ajax({
                    type: "get",
                    dataType: "html",
                    url: "/school/broadcastmail/searchBroadcastmail",
                    data: {
                        input_search: input_search,
                        input_search_student_no: input_search_student_no,
                        class_id: class_id,
                        not_member: not_member,
                        student_types: student_types_arr,
                        id: id,
                        parent_list_arr: parent_list_arr,
                        student_list_arr: student_list_arr,
                        active_flag:active_flag,
                        sort_cond : cond,
                        valid_date_from : valid_date_from,
                        valid_date_to : valid_date_to
                    },
                    contentType: "application/x-www-form-urlencoded",
                    success: function (data) {
                        $("#ajax_parent").html(data);
                    },
                    error: function (data) {
                        console.log("error");
                    },
                });
            }
        });

    </script>

    <script type="text/javascript">
        $(function () {
            // ファイル追加
            var nowFileIndex =  ($('.tbl_clone_file').length)-1;
            $("#fileAdd").click(function(){

                var newTable = $( "TABLE", "#fileBase" ).clone();//fileBaseのIDのTABLEタグをnewTableへ
                var newHR    = $( "HR"   , "#fileBase" ).clone();//fileBaseのIDのHRタグをnewHRへ

                $( "#inputActive2" ).append( newTable );//inputActive2のID指定にnewTableの内容を追加する
                $( "#inputActive2" ).append( newHR    );//inputActive2のID指定にnewHRの内容を追加する

                // 表示
                $( newTable ).show();
                nowFileIndex++;
                return false;
            });

            $( ".fileDelete" ).click( function(e){
                var div         = $(this).closest("div");
                var file_id     = $(div).attr('id').split('_')[1];
                var file_name   = $(div).find('a').text();
                var title       = '{{$lan::get('delete_title')}}';
                // var content     = '「'+ file_name + '」' + '{{$lan::get('file_delete_content_title')}}';
                var content     = '{{$lan::get('file_delete_content_title')}}';
                if ($.isNumeric( file_id )) {
                    var delete_dialog = $( "#delete-file-dialog-confirm" );
                    delete_dialog.dialog({
                        title: title,
                        autoOpen: false,
                        dialogClass: "no-close",
                        resizable: false,
                        modal: true,
                        buttons: {
                            "OK": function() {
                                $.ajax({
                                    type:"get",
                                    dataType:"text",
                                    url: "{{$_app_path}}broadcastmail/deleteUploadFile",
                                    data: { file_id : file_id },
                                    contentType: "application/x-www-form-urlencoded",
                                    success: function(data) {
                                        delete_dialog.dialog( "close" );
                                        if (data == 'success') {
                                            div.remove();
                                        }
                                    },
                                    error: function(xhr, status) {
                                    },
                                });
                            },
                            "{{$lan::get('cancel_title')}}": function() {
                                delete_dialog.dialog( "close" );
                            }
                        }
                    });
                    delete_dialog.html(content);
                    delete_dialog.dialog('open');
                }
                return false;
            });

            //
            $('#selectAll1').click(function () {  //on click
                if (this.checked) { // check select status
                    $('.select1').each(function () { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "question_select"
                    });
                } else {
                    $('.select1').each(function () { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "question_select"
                    });
                }
            });
            $('#selectAll2').click(function () {  //on click
                if (this.checked) { // check select status
                    $('.select2').each(function () { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "question_select"
                    });
                } else {
                    $('.select2').each(function () { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "question_select"
                    });
                }
            });
            $('#selectAll3').click(function () {  //on click
                if (this.checked) { // check select status
                    $('.select3').each(function () { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "question_select"
                    });
                } else {
                    $('.select3').each(function () { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "question_select"
                    });
                }
            });
            $('#selectAll4').click(function () {  //on click
                if (this.checked) { // check select status
                    $('.select4').each(function () { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "question_select"
                    });
                } else {
                    $('.select4').each(function () { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "question_select"
                    });
                }
            });
            $('#selectAll5').click(function () {  //on click
                if (this.checked) { // check select status
                    $('.select5').each(function () { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "question_select"
                    });
                } else {
                    $('.select5').each(function () { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "question_select"
                    });
                }
            });

            $('#student_box').change(function () {
                if ($('#student_box').prop('checked') || $('#parent_box').prop('checked')) {
                    $('#student_table').show();
                } else {
                    $('#student_table').hide();
                }
                return false;
            });
            $('#parent_box').change(function () {
                if ($('#student_box').prop('checked') || $('#parent_box').prop('checked')) {
                    $('#student_table').show();
                } else {
                    $('#student_table').hide();
                }
                return false;
            });
            $('#teacher_box').change(function () {
                if ($(this).prop('checked')) {
                    $('#teacher_table').show();
                } else {
                    $('#teacher_table').hide();
                }
                return false;
            });
            $('#staff_box').change(function () {
                if ($(this).prop('checked')) {
                    $('#staff_table').show();
                } else {
                    $('#staff_table').hide();
                }
                return false;
            });
        });
    </script>
    <style type="text/css">
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

    </style>
    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-envelope-o"></i>{{$lan::get('broadcast_mail_main')}}</h2>
        <div class="center_content_header_right">
            <div class="top_btn">
            </div>
        </div>
        <div class="clr"></div>
    </div>
    <h3 id="content_h3" class="box_border1">{{$lan::get('detail')}}
        @if( isset($request['id']))
            {{$lan::get('edit')}}
        @else
            {{$lan::get('register')}}
        @endif</h3>

    <form id="action_form" method="post" name="action_form" enctype='multipart/form-data'>
        {{ csrf_field() }}
        <div id="section_content1">
            @if($request->errors)
                <ul class="message_area">
                    @foreach($request->errors as $error)
                        <li class="error_message">{{$lan::get($error)}}</li>
                    @endforeach
                </ul>
            @endif
            <input form="action_form" type="hidden" name="id" value="{{array_get($request, 'id')}}"/>
            <input form="action_form" type="hidden" name="send_flag" value="{{array_get($request, 'send_flag')}}"/>
            @if( isset($request['student_ids']))
                @foreach (array_get($request, 'student_ids') as $student_id)
                    <input type="hidden" name="student_ids[]" value="{{$student_id}}"/>
                @endforeach
            @endif
            @if( isset($request['bc_option']))
                <input form="action_form" type="hidden" name="bc_option" value="{{array_get($request, 'bc_option')}}"/>
            @else
                <input form="action_form" type="hidden" name="bc_option" value="1"/>
            @endif
            <table id="table6">
                <colgroup>
                    <col width="25%"/>
                    <col width="75%"/>
                </colgroup>
                <tr>
                    <td></td>
                    <td class="sending_mail_area" style="padding-right: 10%; float:right">
                        <input type="button" id="btn_load_list" name="btn_load_list" value="{{$lan::get('btn_list_mail_template')}}" style="height: 32px; font-weight: 400;">
                        <div class="divider"></div>
                        @if($edit_auth)
                        <input type="button" id="btn_create_list" name="btn_create_list" value="{{$lan::get('mail_template_create')}}" style="height: 32px; font-weight: 400;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('mail_title_title')}}<span class="aster">&lowast;</span></td>
                    <td>
                        <input style="ime-mode:active;" type="text" size="70" name="title" value="{{$request->title}}"
                               id="mail_subject" placeholder="{{$lan::get('mail_title_title')}}{{$lan::get('placeholder_input_temp')}}"/>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('mail_content_title')}}<span class="aster">&lowast;</span></td>
                    <td>
                        <textarea style="width:90%;ime-mode:active;" name="content" cols="10" rows="8" wrap="hard"
                                  class="description_textarea"
                                  id="mail_description">{{$request->content}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('mail_footer_title')}}</td>
                    <td>
                        <textarea style="ime-mode:active;width:80%" name="footer" rows="3" wrap="hard"
                                  class="footer_textarea"
                                  id="mail_footer">{{$request->footer}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td class="t6_td1">{{$lan::get('memo')}}</td>
                    <td>
                                <textarea style="ime-mode:active;width:80%" name="memo"  rows="3" wrap="hard"
                                          class="description_textarea"
                                          placeholder="{{$lan::get('memo')}}{{$lan::get('placeholder_input_temp')}}">{{$request->memo}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td  class="t6_td1">{{$lan::get('attachment_title')}}</td>
                    <td>
                        <div id="inputActive2" >
                            @if ( count(request('files')) > 0 )
                                @foreach (request('files') as $key => $file)
                                    <div id="file_{{array_get($file,'id')}}" style="padding-bottom: 10px;" >
                                        <a href="{{$file_dir}}{{array_get($file,'file_path')}}" download="{{array_get($file,'disp_file_name')}}">{{array_get($file,'disp_file_name')}}</a>&nbsp;&nbsp;
                                        <i class="fa fa-trash-o fileDelete" style="cursor: pointer;"></i>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button id="fileAdd" style="width:15%;margin:10px 0px 10px 0px;"><i class="fa fa-plus"></i>{{$lan::get('add_file_title')}}</button>
                        <div id="fileBase" style="display:none;">
                            <table class="tbl_clone_file">
                                <tr>
                                    <td class="t4d2">
                                        <input type="file" name="files[]">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </form>
    <br/>
    @include('School.Broadcast_mail.search')
    @include('_mail.mail_template')
    <div id="section_content1">
        @if(isset($failed_deli_list))
            {{$lan::get('fail_send_guardian_title')}}
            @foreach ($failed_deli_list as $parent_name)
                {{$parent_name}}
            @endforeach
        @endif
        @php
            $arr_teacher = [7,8,6,9,4,10,5];
            $arr_staff = [8,9,10,11,12,13];
        @endphp
        <p>{{$lan::get('bc_target')}}<br/>
            <label><input form="action_form" id="student_box" type="checkbox" name="bc_student" value="1" checked>
            {{$lan::get('student_and_parent')}}</label>
            &nbsp;&nbsp;
            <label ><input form="action_form" id="teacher_box" type="checkbox" name="bc_teacher" value="1">
            {{$lan::get('teacher')}}</label>
            &nbsp;&nbsp;
            <label><input form="action_form" id="staff_box" type="checkbox" name="bc_staff" value="1">
            {{$lan::get('staff')}}</label>
        </p><br/>
            @if($request->error_send_mail)
                <ul class="message_area">
                        <li class="error_message">{{$request->error_send_mail}}</li>
                </ul>
            @endif
        <div id="ajax_parent">@include('School.Broadcast_mail.parent_list')</div>

        <div id="teacher_table" style="display: none">
            <table class="table_list tablesorter body_scroll_table" id="teachers">
                <thead>
                <tr>
                    <th class="text_title" style="width:5%;"><input type="checkbox" id="selectAll4"></th>
                    <th class="text_title header" style="width:20%;text-align: left">{{$lan::get('teacher_name')}}</th>
                    <th class="text_title header" style="text-align: left">{{$lan::get('teacher_time_send')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($teacher_list))
                    @foreach ($teacher_list as $teacher)
                        <tr>
                            <td style="width: 5%;text-align:center;">
                                <input form="action_form" type="checkbox" class="select4" name="teacher_ids[]"
                                       value="{{array_get($teacher, 'id')}}"
                                       @if(isset($request['teacher_ids']))
                                       @foreach (array_get($request, 'teacher_ids') as $teacher_id)
                                       @if(array_get($teacher, 'id') == $teacher_id)  checked="checked" @endif
                                       @endforeach
                                       @endif class="teacher_select">
                                </input>
                            </td>
                            <td style="width:20%; padding-left:10px;">{{array_get($teacher, 'coach_name')}}</td>
                            <td style="padding-left:10px;"> @if(!empty($teacher['teacher_time_send']))
                                    {{Carbon\Carbon::parse(array_get($teacher, 'teacher_time_send'))->format('Y-m-d')}}
                                                            @endif
                            </td>
                        </tr>
                    @endforeach
                @elseif(!isset($teacher_list))
                    <tr>
                        <td class="t4td2 error_row">{{$lan::get('no_data_to_show')}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <br/>
        </div>
        <div id="staff_table" @if(!array_search($request->bc_option, $arr_staff)) style="display: none" @endif>
            <table class="table_list tablesorter body_scroll_table" id="staffs">
                <thead>
                <tr>
                    <th class="text_title" style="width:5%;"><input type="checkbox" id="selectAll5"></th>
                    <th class="text_title header" style="width:20%;text-align: left">{{$lan::get('staff_name')}}</th>
                    <th class="text_title header" style="text-align: left;">{{$lan::get('Staff_Time_Send')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($staff_list))
                    @foreach ($staff_list as $staff)
                        <tr>
                            <td style="width:5%;text-align:center;">
                                <input form="action_form" type="checkbox" class="select5" name="staff_ids[]"
                                       value="{{$staff['id']}}"
                                       @if(isset($request['staff_ids']))
                                       @foreach (array_get($request, 'staff_ids') as $staff_id)
                                       @if(array_get($staff, 'id') == $staff_id)  checked="checked" @endif
                                       @endforeach
                                       @endif class="teacher_select">
                                </input>
                            </td>
                            <td style="width:20%;padding-left:10px">{{array_get($staff, 'staff_name')}}</td>
                            <td style="padding-left:10px">
                                @if(!empty($staff['staff_time_send']))
                                {{Carbon\Carbon::parse(array_get($staff, 'staff_time_send'))->format('Y-m-d')}}</td>
                                @endif
                        </tr>
                    @endforeach
                @elseif(!isset($staff_list))
                    <tr>
                        <td class="t4td2 error_row">{{$lan::get('no_data_to_show')}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <br/>
        </div>
        <br/>
        <table>
            <tr>
                <input type="checkbox" name="schedule_flag_update" id="schedule_flag"
                       @if (old('schedule_flag', request('schedule_flag'))) checked @endif>&nbsp;&nbsp;<label
                        for="schedule_flag">{{$lan::get('booking_time_send_title')}}</label>&nbsp;
                <input form="action_form" type="text" {{--id="datepicker"--}} class="DateTimeInput"
                       name="send_date" value="" id="send_datepicker"
                       placeholder="{{$lan::get('Choose_Send_Date')}}{{$lan::get('placeholder_input_temp')}}">&nbsp;
                <span class="error_message error_schedule_date display_none">{{$lan::get("Choose_Send_Date")}}{{$lan::get('placeholder_input_temp')}}</span>
            </tr>

        </table>
        <br/>
        <div class="exe_button">
            <!-- <input form="action_form" type="button" value="{{$lan::get('send')}}" id="btn_submit" class="submit2"/> -->
            <button form="action_form" class="submit2" type="button"  id="btn_submit" style="height:30px;width: 150px !important;"><i class="fa fa-send " style="width: 20%;font-size:16px;"></i>{{$lan::get('send')}}</button>
            <button form="action_form" class="submit2" type="button"  id="btn_save" style="height:30px;width: 150px !important;"><i class="fa fa-envelope-open" style="width: 20%;font-size:16px;"></i>{{$lan::get('save_draft')}}</button>
            <!-- <input form="action_form" type="button" value="{{$lan::get('return')}}" id="btn_return" class="submit2"/> -->
            <button form="action_form" class="submit2" type="button" name="search_button" id="btn_return" style="height:30px;width: 150px !important;"><i class="fa fa-arrow-circle-left " style="width: 20%;font-size:16px;"></i>{{$lan::get('return')}}</button>
        </div>
    </div>
    <div id="dialog-confirm" class="no_title" style="display: none;">
        {{$lan::get('mail_will_be_send_confirm')}}
    </div>
    <script>
        $(function () {
            $.datetimepicker.setLocale('ja');

            $('#valid_date_from').datetimepicker({
                timepicker: false,
                format:'Y-m-d',
                scrollInput: false,
                scrollMonth: false
            });
            $('#valid_date_to').datetimepicker({
                timepicker: false,
                format:'Y-m-d',
                scrollInput: false,
                scrollMonth: false
            });

            tinymce.init({
                selector: 'textarea#mail_description',
                menubar: false,
                toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                plugins: "advlist autolink lists link image charmap print preview anchor"
            });

            jQuery(function () {
                jQuery('.DateTimeInput').datetimepicker({
                    format: 'Y-m-d H:i',
                    step: 5,
                    minDate: new Date(),
                    scrollMonth: false,
                    scrollInput: false
                });
            });
            getTypeMail(3);
        });

    </script>
    <div id="delete-file-dialog-confirm" style="display: none;"></div>
@stop

