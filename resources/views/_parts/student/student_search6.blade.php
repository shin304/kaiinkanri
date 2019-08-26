<script type="text/javascript">
$(function() {
    $('#search_cond_clear').click(function() {  // clear
        $("input[name='select_word']").val("");
        $("select[name='select_grade']").val("");
        $("select[name='select_pschool']").val("");
        $("select[name='class_id']").val("");
        $("select[name='fee_type_id']").val("");
        $('[name^=_student_types]').prop('checked',true);
        $("select[name='select_state']").val("1");
        $("input[name='disp_billing']").prop('checked', false);
        $('#billing_state').hide();
        $("select[name='workflow_status']").val("");
        return false;
    });

    $('input[name="disp_billing"]').change(function() {
        var prop = $('#prop').prop('checked');
        if (prop) {
            $('#billing_state').show();
          } else {
            $('#billing_state').hide();
          }
    });
});
</script>
<style>
    .search_box #search_cond_clear {
        height: 29.5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
    .btn_search {
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
    .search_box #search_cond_clear:hover, .btn_search:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
        text-shadow: 0 0px #FFF;
    }
</style>

                <input type="hidden" name="relative_id" value="{{request('relative_id')}}">
                <input type="hidden" name="msg_type_id" value="{{request('msg_type_id')}}">
                <input type="hidden" name="event_type_id" value="{{request('event_type_id')}}">
                <input type="hidden" name="event_type" value="{{request('event_type')}}">
                <input type="hidden" name="event_name" value="{{request('event_name')}}">
                <table>
                    <colgroup>
                        <col width="10%"/>
                        <col width="30%"/>
                        <col width="10%"/>
                        <col width="30%"/>
                    </colgroup>
                    <tr>
                        <th>
                            {{$lan::get('member_name')}}
                        </th>
                        <td>
                            <input class="text_long" type="search" name="select_word" id="select_word" value="{{old('select_word', request('select_word'))}}" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{$lan::get('status_title')}}
                        </th>
                        <td>
                            <select name="select_state" id="select_state" style="max-width:200px;">
                                <option value=""></option>
                                @foreach ($states as $key=>$item)
                                <option value="{{$key}}" @if (old('select_state', request('select_state')) == $key) selected @endif>{{$lan::get($item)}}</option> 
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @if (count(request('_student_types')) > 0)
                    <tr>
                        <th>
                            {{$lan::get('student_type_title')}}
                        </th>
                        <td>
                            @foreach (request('_student_types') as $idx=>$type)
                                <input type="checkbox" name="_student_types[{{$idx}}]" id="student_type_{{$idx}}" value="{{array_get($type, 'id')}}" @if (array_get($type, 'is_display') == 1) checked @endif>
                                <label for="student_type_{{$idx}}">{{array_get($type, 'name')}}</label>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @if (isset($class_list) && count($class_list) > 0)
                    <tr>
                        <th>
                            {{$lan::get('class_list_title')}}
                        </th>
                        <td>
                            <select name="class_id" id="class_id" style="max-width:200px;">
                                <option value=""></option>
                                @foreach ($class_list as $class)
                                <option value="{{array_get($class, 'id')}}" @if (old('class_id', request('class_id')) == array_get($class, 'id')) selected @endif>{{array_get($class, 'class_name')}}</option> 
                                @endforeach
                            </select>
                            
                        </td>
                    </tr>
                        <tr>
                            <th>
                                {{$lan::get('ttl_fee_type')}}
                            </th>
                            <td>
                                @php
                                    $fee_plan_list = array();
                                    if (request('event_type_id') == 2) {
                                        $fee_plan_list = $course_fee_plan;
                                    } elseif (request('event_type_id') == 3) {
                                        $fee_plan_list = $program_fee_plan;
                                    }
                                @endphp
                                <select name="fee_type_id" id="fee_type_id">
                                    <option value=""></option>
                                    @foreach ($fee_plan_list as $key=>$val)
                                        <option value="@if ($loop->first) {{$key}}|1 @else {{$key}}|0 @endif" @if (old('fee_type_id', request('fee_type_id')) == $key) selected @endif>{{$val['fee_plan_name']}} | {{$val['payment_unit_text']}} | {{$val['fee']}} {{$lan::get('circle')}}</option>
                                    @endforeach

                                </select>
                            </td>
                        </tr>
                    @endif
                </table>
                <div class="clr"></div>
                <!-- <input type="submit" id="btn_student_search" class="submit" name="search_button" value="{{$lan::get('search_title')}}"> -->
                <button class="btn_search" type="submit" name="search_button" id="btn_student_search" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search')}}</button>
                <input type="button" id="search_cond_clear" class="submit" value="{{$lan::get('clear_all_title')}}"/>
                <div class="clr"></div>
