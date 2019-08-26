<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/class.css" />
<script type="text/javascript">
$(function() {
    $('#search_cond_clear').click(function() {  // clear
        $("input[name='search\\[name\\]']").val("");
        $("select[name='search\\[state\\]']").val("1");
        return false;
    });

    $('#btn_student_search').click(function(e) {
    // $('input[name="search_button"]').click(function(e) {
       e.preventDefault();
       $data = $(".action-form").serialize();
        $.ajax(
            {
            url: "{{$_app_path}}label/search",
            data: $data,
            dataType: 'html',
            context: document.body,
            success: function(data)
            {
                $('#search_member_area').html(data);
                deleteNullTD();
            },
            error: function (data) {
                console.log(data);
            },
        });
       return false;
    });
});
</script>
<style>
    .search_box #search_cond_clear:hover, .top_btn li:hover, .btn_search:hover {
        background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
        box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
        cursor: pointer;
        text-shadow: 0 0px #FFF;
    }
    .search_box #search_cond_clear {
        height: 31px;
        border-radius: 5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
    .top_btn li {
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
    .btn_search {
        border-radius: 5px;
        background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
        text-shadow: 0 0px #FFF;
    }
</style>

<div class="search_box box_border1 padding1">
    <table>
        <tbody>
        <tr>

            <th>
                @if($table_name == 'student')
                {{$lan::get('member_name')}}
                @else
                {{$lan::get('lb_parent_name_title')}}
                @endif
            </th>
            <td><input class="text_long" type="search" name="search[name]" id="search[name]" value="{{old('search.name', request('search.name'))}}"></td>
        </tr>

        <tr>
            <th> {{$lan::get('status_title')}}</th>
            <td>
                <select name="search[state]" id="select_state" style="max-width: 200px;">
                    <option value=""></option>
                    @foreach($states as $key=>$val)

                        <option value="{{$key}}" @if (old('search.state', request('search.state')) == $key) selected @endif>{{$val}}</option>
                    @endforeach
                </select>
            </td>
        </tr>

        @if($table_name == 'student' )
            <tr>
                <th>{{$lan::get('student_type_title')}}</th>
                <td>
                    <select name="student_type" >
                        <option value=""></option>
                        @foreach($studentTypes as $type)
                            <option value="{{array_get($type, 'id')}}"
                                    @if(request('student_type') == array_get($type, 'id')) selected @endif>{{array_get($type, 'name')}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @endif
        </tbody>
    </table>

    <!-- <input type="submit" id="btn_student_search" class="submit" name="search_button" value="{{$lan::get('search_title')}}"> -->
    <button class="btn_search" type="submit" name="search_button" id="btn_student_search" style="height:31px;width: 150px !important;"><i class="fa fa-search " style="width: 20%;font-size:16px;"></i>{{$lan::get('search_title')}}</button>
    <input type="button" class="submit" id="search_cond_clear" style="font-size: 14px;font-weight: normal; color: #595959;" value="{{$lan::get('clear_all_title')}}">
</div> <!--END SEARCH AREA -->

<ul class="message_area " style="padding-left: 20px;display: none;">
    <li class="error_message" id="select_member">{{$lan::get('please_select_member_title')}}</li>
</ul>
<div>
    <input type="checkbox" name="select_all" id="select_all"><label for="select_all">{{$lan::get('select_all_title')}}</label>
</div>
<!-- TABLE LIST MEMBER  -->
<table id="list-memeber-label" class="table1">
    @foreach($list as $idx=>$member)
        <tr>
            <td class="td-checkbox{{$idx}}">
                <input type="checkbox" value="{{$member->id}}" class="select_rec" name="member_ids[]">
            </td>
            @foreach($default_column_titles as $column)

                <td class="td-{{$column}}{{$idx}} td-{{$column}}">
                    @if($member->$column) {{$member->$column}} @else null @endif
                    @if($loop->parent->first)
                        <input type="hidden" name="columns[]" value="{{$column}}">
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    @forelse ($list as $member)
    @empty
        <tr style="display: none">
            @foreach($default_column_titles as $idx=>$column)
                <td class="td-{{$column}}0 td-{{$column}}">
                    <input type="hidden" name="columns[]" value="{{$column}}">
                </td>
            @endforeach
        </tr>
        <tr>
            <td class="error_row" colspan="">{{$lan::get('no_information_display_title')}}</td>
        </tr>
    @endforelse
</table> <!--END TABLE LIST MEMBER  -->

