<style>
    .submit {
        height: 29.5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
        border-radius: 5px;
        text-shadow: 0 0px #FFF;
    }
    .btn_search {
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
    .submit:hover, .btn_search:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
        text-shadow: 0 0px #FFF;
    }
</style>
<div id="section_content1">
    <form id="action_form1" name="action_form1" action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <h3 id="content_h3" class="box_border1">送信先検索</h3>
        <input type="hidden" name="title" value="{{array_get($request, 'title')}}"/>
        <input type="hidden" name="id" value="{{array_get($request, 'id')}}"/>
        <input type="hidden" name="send_flag" value="{{array_get($request, 'send_flag')}}"/>
        <div style="display:none;">
            <textarea name="content">{{array_get($request, 'content')}}</textarea>
        </div>
        <div class="search_box box_border1 padding1"><!-- 検索の入力ボックスの両サイドの余白 -->
            <table>
                <tr>
                    <th style="width:10%;">
                        {{$lan::get('student_name')}}
                    </th>
                    <td style="width:30%;">
                        <input class="text_long" type="search" name="input_search"
                               value="{{array_get($request, 'input_search')}}" placeholder="{{$lan::get('input_search_student_name_title')}}">
                    </td>
                    <th style="width:10%;">
                        {{$lan::get('student_classification')}}
                    </th>
                    <td style="width:30%;">
                        @if (isset($studentTypes))
                            @foreach ($studentTypes as $index => $studenttype)
                                <input type="hidden" name="_student_types[{{$index}}][name]"
                                       value="{{array_get($studenttype, 'name')}}"/>
                                <input type="hidden" name="_student_types[{{$index}}][id]"
                                       value="{{array_get($studenttype, 'id')}}"/>
                                <label style="display: -webkit-inline-box">
                                    <input class="student_types" type="checkbox" id="student_type{{$index}}"
                                           name="_student_types[{{$index}}][is_display]"
                                           value="{{array_get($studenttype, 'id')}}"
                                       @if(array_get($request, '_student_types.index.is_display') || !array_get($request, '_student_types'))
                                           checked
                                       @endif/>&nbsp;{{array_get($studenttype, 'name')}}
                                </label>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="width:15%;">
                        {{$lan::get('student_no')}}
                    </th>
                    <td style="width:30%;">
                        <input class="text_long" type="search" name="input_search_student_no"
                               value="{{array_get($request, 'input_search_student_no')}}" placeholder="{{$lan::get('student_no')}}{{$lan::get('placeholder_input_temp')}}">
                    </td>
                    <th style="width:15%;">
                        {{$lan::get(array_get($main_captions,'8'))}}

                    </th>
                    <td style="width:30%;">
                        <select name="class_id" class="select1">
                            <option value=""></option>
                            @if(isset($request->class_list))
                                @foreach($request->class_list as $key =>$item)
                                    @if(array_get($request, 'class_id') == $key)<option value="{{$key}}" selected="selected" @endif>{{$item}}</option>
                                        <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{{$lan::get('valid_date_title').$lan::get('sun_title')}}</th>
                    <td>
                        <input style="width: 117px" type="search" id="valid_date_from" class="ip_date_type " name="valid_date_from" value="{{$request->valid_date_from}}"> ～
                        <input style="width: 117px" type="search" id="valid_date_to" class="ip_date_type " name="valid_date_to" value="{{$request->valid_date_to}}">
                    </td>
                    <th>
                        {{--{{$lan::get('Active_flag')}}--}}
                    </th>
                    <td>
                        <input class="active_flag" type="checkbox" id="member" name="member" value="1" checked/>{{$lan::get('member_title')}}
                        <input class="active_flag" type="checkbox" id="not_member" name="not_member"
                               value="0"/>{{$lan::get('withdrawal_members')}}
                    </td>
                </tr>
            </table>
            <br/>
            <div class="clr"></div>
            <!-- <input type="button" id="btn_search" class="submit2" name="search_button" value="{{$lan::get('search')}}"> -->
            <button class="btn_search" type="button" name="search_button" id="btn_search" style="height:30px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search')}}</button>
            <input type="reset" class="submit" id="search_cond_clear" style="font-size: 14px;" value="{{$lan::get('clear')}}">
            <div class="clr"></div>

        </div>
    </form>
</div>