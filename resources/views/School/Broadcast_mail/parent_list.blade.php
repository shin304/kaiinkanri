<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script>
    $(function () {
        $( window ).resize(function(){
            render_header();
        })
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
        function render_header(){
            var num =0;
            $(".parent_row").find('tr').first().find('td').each(function(){
                var width = $(this).width();
                $(".table_list").find('th:eq('+num+')').width(width+10);
                num++;
            });
        }
        $("#student").change(function(){
            render_header();
        });

    });
</script>
<style>
    #students tr th {
        text-align: left;
    }

    #students tr td {
        text-align: left;
    }
    .parent_row{
        background-color: #ddd !important;
    }
    .table_list th{
        padding-left:10px !important;
        padding-right:10px !important;
    }
    .student_row td{
        /*background-color:#fcf6ef;*/
        background-color: rgba(248, 247, 243, 0.66);
    }
    /*.student_row td:nth-child(1){
        background-color:white;
    }
    .student_row td:nth-child(2){
        background-color:white;
    }*/
    .student_row{
        border: 2px white solid ;
        margin-top:2px;
        margin-bottom:2px;
    }

</style>
<div id="student_table">
    <table class="table_list body_scroll_table" id="students">
        {{-- <thead>
        <tr>
            <th class="text_title" style="width:60px;"><input form="action_form" type="checkbox" id="selectAll1"></th>
            <th class="text_title header" style="width: 170px;">{{$lan::get('parent_name')}}</th>
            <th class="text_title header" style="width:150px;">{{$lan::get('parent_time_send')}}</th>
            <th class="text_title" style="width:60px;"><input form="action_form" type="checkbox" id="selectAll3"></th>
            <th class="text_title header" style="width:150px;">{{$lan::get('student_no')}}</th>
            <th class="text_title header" style="width:150px;">{{$lan::get('student_time_send')}}</th>
            <th class="text_title header" style="width:170px;">{{$lan::get('student_name')}}</th>
            <th class="text_title header" style="width:100px;">{{$lan::get('student_classification')}}</th>


        </tr>
        </thead>
       <tbody>
        <input form="action_form" type="hidden" name="check_search" value="{{$request->check_search}}"/>
        @if(isset($parent_list))
            @foreach ($parent_list as $parent)
                @php
                    $parent_id = array_get($parent, 'id');
                @endphp
                <tr>
                    @if(isset($request->parent_list))
                        @foreach ($request->parent_list as $parent_new_id)
                            <input form="action_form" type="hidden"
                                   name="parent_list[{{$parent_new_id}}][parent_mail1_target]"
                                   value="{{$parent_new_id}}">
                        @endforeach
                    @endif
                    @if(isset($request->student_list))
                        @foreach ($request->student_list as $student_new_id)
                            <input form="action_form" type="hidden" name="student_list[{{$student_new_id}}][target]"
                                   value="{{$student_new_id}}">
                        @endforeach
                    @endif
                    <td style="width:60px;">
                        @if(array_get($parent, 'parent_mailaddress1'))
                            <input form="action_form" type="checkbox" class="select1"
                                   name="parent_list[{{$parent_id}}][parent_mail1_target]" value="{{$parent_id}}"
                                   @if(isset($request->parent_list))
                                   @foreach ($request->parent_list as $parent_save_id)
                                   @if( $parent_id == $parent_save_id) checked="checked" @endif
                                    @endforeach
                                    @endif
                            />
                        @endif
                    </td>
                    <td style="width:170px;">{{array_get($parent, 'parent_name')}}</td>
                    <td style="width:150px;">
                        {{Carbon\Carbon::parse(array_get($parent, 'parent_time_send'))->format('Y年m月d日')}}
                    </td>
                    <td style="width:60px;">
                        @foreach (array_get($parent, 'students') as $student)
                            @php
                                $student_id = array_get($student, 'id');
                            @endphp
                            <input form="action_form" type="checkbox" class="select3"
                                   name="student_list[{{$student_id}}][target]" value="{{$student_id}}"
                                   @if(isset($request->student_list))
                                   @foreach ($request->student_list as $student_save_id)
                                   @if($student_id == $student_save_id) checked="checked" @endif
                                    @endforeach
                                    @endif
                            />
                            <br/>
                        @endforeach
                    </td>
                    <td style="width:150px;">
                        @foreach (array_get($parent, 'students') as $student)
                            {{array_get($student, 'student_no')}}
                            <br/>
                        @endforeach
                    </td>
                    <td style="width:150px;">
                        @foreach (array_get($parent, 'students') as $student)
                            {{Carbon\Carbon::parse(array_get($student, 'student_time_send'))->format('Y年m月d日')}}
                            <br/>
                        @endforeach
                    </td>
                    <td style="width:170px;">
                        @foreach (array_get($parent, 'students') as $student)
                            {{array_get($student, 'student_name')}}
                            <br/>
                        @endforeach
                    </td>
                    <td style="width:100px;text-align:center;">
                        @foreach (array_get($parent, 'students') as $student)
                            {{array_get($student, 'student_type')}}
                            <br/>
                        @endforeach
                    </td>
                    <input form="action_form" type="hidden" name="enter[]"
                           value="{{array_get($student, 'enter_memo')}}"/>
                </tr>
            @endforeach
        @elseif(!isset($parent_list))
            <tr>
                <td class="t4td2 error_row">{{$lan::get('no_data_to_show')}}</td>
            </tr>
        @endif
        </tbody>--}}
        <thead>
        <tr>
            <th class="text_title" style="width: 5%;padding-left: 10px !important;"><input form="action_form" type="checkbox" id="selectAll1"></th>
            <th class="text_title header" style="width:15%;">{{$lan::get('parent_name')}}</th>
            <th class="text_title" style="width: 5%;text-align: center"><input form="action_form" type="checkbox" id="selectAll3"></th>
            <th class="text_title header" style="width:15%;">{{$lan::get('student_name')}}</th>
            <th class="text_title header sort_student" style="width:15%;">{{$lan::get('student_no')}}<span class="sort_student_no glyphicon @if(isset($request['sort_cond']) && in_array('student_no',$request['sort_cond']) && in_array('DESC',$request['sort_cond']))
                                                                                glyphicon-triangle-top @else glyphicon-triangle-bottom @endif " ></span></th>
            <th class="text_title header sort_student" style="width:15%;">{{$lan::get('student_classification')}}<span class="sort_student_type glyphicon @if(isset($request['sort_cond']) && in_array('student_type_name',$request['sort_cond']) && in_array('DESC',$request['sort_cond']))
                                                                                glyphicon-triangle-top @else glyphicon-triangle-bottom @endif " ></span></th>
            <th class="text_title header" >{{$lan::get('valid_date_title')}}{{$lan::get('sun_title')}}</th>
            <th class="text_title header" >{{$lan::get('sent_date_title')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($parent_list))
            @foreach ($parent_list as $parent)
                @php
                    $parent_id = array_get($parent, 'id');
                @endphp
                <tr>
                    <td style="width: 5%">
                        <input type="checkbox" form="action_form" name="parent_list[{{$parent_id}}][parent_mail1_target]" class="select1">
                    </td>
                    <td>
                        {{array_get($parent,'parent_name')}}
                    </td>
                    <td style="width: 5%"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @if(!empty($parent['parent_time_send']))
                            {{Carbon\Carbon::parse(array_get($parent, 'parent_time_send'))->format('Y-m-d')}}
                        @endif
                    </td>
                </tr>
                @if(isset($parent['students']))
                    @foreach($parent['students'] as $student)
                    <tr class="student_row">
                        <td style="width: 5%">
                        </td>
                        <td></td>
                        <td style="width: 5%;text-align: center">
                            <input type="checkbox" form="action_form" name="student_list[{{$student['id']}}][target]" class="select3">
                        </td>
                        <td>
                            {{array_get($student,'student_name')}}
                        </td>
                        <td>
                            {{array_get($student,'student_no')}}
                        </td>
                        <td>
                            {{array_get($student,'student_type_name')}}
                        </td>
                        @if(date('Y-m-d') > array_get($student, 'valid_date'))
                            <td class="text_left" style=" color: red; font-weight: 600;" >
                                {{array_get($student, 'valid_date')}}
                            </td>
                        @else
                            <td class="text_left">
                                {{array_get($student, 'valid_date')}}
                            </td>
                        @endif
                        <td>
                            @if(!empty($student['student_time_send']))
                                {{Carbon\Carbon::parse(array_get($student, 'student_time_send'))->format('Y-m-d')}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
    <br/>
</div>