@extends('_parts.master_layout')
@section('content')
{{--CSS content begin--}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="/css/{{$_app_path}}/coach.css" />
{{--CSS content end--}}

{{--HTML content begin--}}
<div id="center_content_header" class="box_border1">
    <h2 class="float_left main-title"><i class="fa fa-black-tie"></i>{{$lan::get('main_title')}}</h2>
    <div class="clr"></div>
</div><!--center_content_header-->
<h3 id="content_h3" class="screen-title">
    @if (request('id')) {{$lan::get('detail_info_edit_title')}}@else{{$lan::get('detail_info_register_title')}}@endif
</h3>


@if (count($errors) > 0) 
    <ul class="message_area"> 
    @foreach ($errors->all() as $error)
        <li class="error_message">{{ $lan::get($error) }}</li>
    @endforeach
    </ul>
@endif
@if (session()->has('messages'))
    <ul class="message_area">
        <li class="info_message">{{ $lan::get(session()->pull('messages')) }}</li>
    </ul>
@endif

<div id="section_content1">
    <p><span class="aster">&lowast;</span>{{$lan::get('required_text_explain_title')}}</p>
    <form id="entry_form" name="entry_form" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        {{-- Menu setting begin --}}
        <table width="100%">
            <colgroup>
                <col width="30%"/>
                <col width="70%"/>
            </colgroup>
            <tr>
                <td><b>{{$lan::get('permission_setting_title')}}</b></td>
                <td style="padding-bottom: 10px;">
                    <select id = "student_type_select" name = "m_student_type_id">
                        @foreach($list_student_type as $k => $v)
                            <option value="{{$v['id']}}" @if(!empty($m_student_type_id)&& $m_student_type_id == $v['id']) selected @endif>{{$v['name']}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="content_student_menu">
                    @include('_parts.menu_auth_student')
                </td>
            </tr>
        </table>
        {{-- Menu setting end --}}



        {{-- Register button--}}
        @if(request()->has('id'))
            <div class="div-btn">
                <ul>
                    <!-- <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;"><i class="glyphicon glyphicon-save"></i>{{$lan::get('edit_title')}}</li></a> -->
                    <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;"><i class="glyphicon glyphicon-floppy-disk"></i> {{$lan::get('register_title')}}</li></a>
                    <a href="" class="button" id="btn_back"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;"><i class="glyphicon glyphicon-circle-arrow-left"></i> {{$lan::get('back_btn')}}</li></a>
                </ul>
            </div>
        @else
            <div class="div-btn">
                <ul>
                    <a href="" class="button" id="submit2"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;"><i class="glyphicon glyphicon-floppy-disk"></i> {{$lan::get('register_title')}}</li></a>
                    <a href="" class="button" id="btn_back"><li style="color: #595959; font-weight: normal;width:14%;height: 30px;"><i class="glyphicon glyphicon-circle-arrow-left"></i> {{$lan::get('back_btn')}}</li></a>
                </ul>
            </div>
        @endif
    </form>
    <div id="dialog_active" class="no_title" style="display:none;">
        {{$lan::get('message_save_confirm')}}
    </div>
</div>

{{--HTML content end--}}


{{--JS content begin--}}
<script type="text/javascript">

    $(function(){

        //Toran load student menu
        $("#student_type_select").change(function () {

            var student_type = $(this).val();
            var _token = "{{csrf_token()}}";
            $.ajax({
                type: "post",
                url: "/school/school/loadStudentMenu",
                data: {student_type: student_type, _token: _token},
                success: function(data) {

                    $("#content_student_menu").html('');
                    $("#content_student_menu").html(data);

                    drap_drop();
                }
            });

        })

        // 確認ボタン
        $("#submit2").click(function () {
            $("#dialog_active").dialog('open');
            return false;
        });

        $("#dialog_active").dialog({
            title: '{{$lan::get('main_title')}}',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            buttons: {
                "{{$lan::get('ok_btn')}}": function () {
                    $(this).dialog("close");
                    $("#entry_form").attr('action', '/school/school/saveStudentMenu');
                    $("#entry_form").submit();
                    return false;
                },
                "{{$lan::get('cancel_title')}}": function () {
                    $(this).dialog("close");
                    return false;
                }
            }
        });

        $('#btn_back').click(function (event) {
            event.preventDefault();
            java_post("{{$_app_path}}school/accountlist");
            return false;
        });

        function drap_drop(){
            //====================================
            //=========DRAG & DROP AREA=========//
            //====================================

//        SORT LIST
            $( "#filter-menu" ).sortable({
                revert: true,
                start: function(e, ui){
                    $(ui.item).data('old-idx' , ui.item.index());
                },
                update: function(e, ui) {
                    loadEditAuth(ui);
                }
            });

//        DRAG LIST
            var $myDraggable =$( "#reference-menu li.menu-refer-row" ).draggable({
                start:  function(event, ui){
                    ui.helper.css("width", $(this).width());  // or set a value if constant
                },
                connectToSortable: "#filter-menu",
                helper: "clone",
                revert: "invalid",
            });

//        click X icon to delete item
            $(document).on('click', "#filter-menu li",function (e) {
                e.preventDefault();
                $target = $( e.target );
                if ( $target.is( "a.delete-menu" ) ) {
                    $(this).remove();
                }
            });

            $( "ul, li" ).disableSelection();
            //====================================
            //============FORM EVENT=============//
            //====================================
//    button 「＞＞」「＜＜」 click
            $(document).on('click', "#reference-menu li.menu-refer-row, #filter-menu li",function (e) {
                e.preventDefault();
                if (e.target !== this)
                    return;
                $(this).hasClass('filter_view')? $(this).removeClass('filter_view') : $(this).addClass('filter_view');
            });

//    button 「＞＞」 click
            $('#move_to_filter').click(function () {
                $myDraggable.each(function (idx, el) {
                    var idx  = $(el).attr('idx');

//            todo check selected class and move into filter_list if doesn't exist yet
                    if ($(el).hasClass('filter_view') && (!$('#filter-menu li[idx=' +idx+ ']').length)) {
                        $(el).removeClass('filter_view');

                        $clone = $(el).clone();
//                    append Input menu_list[x], delete icon, view checkbox, edit checkbox
                        appendItem($clone, idx, $(el).attr('editable'));
                        $('#filter-menu').append($clone);

                    } else if ($(el).hasClass('filter_view')) {
                        $(el).removeClass('filter_view');
                    }
                })
            });
//    button 「＜＜」 click
            $('#revert_to_refer').click(function () {
                $('#filter-menu li.filter_view').each(function (idx, el) {
                    $(el).remove();
                })
            });

//        Click button view, edit
            $(document).on('click', ".viewable, .editable",function (e) {
//            $child: input checkbox
                $child = $(this).children('input');
                $className = $(this).attr('class').split(" ");

                if ($child.is(':checked')) {
                    unSelectItem($child);
//                if not view => not edit

                    if ($(this).hasClass('viewable')) {
                        $idx = $className[1].substr(('viewable-').length);
                        unSelectItem($('.editable-'+$idx+' input'));
                    }
                } else {
                    selectItem($child);
//                if edit => view
                    if ($(this).hasClass('editable')) {
                        $idx = $className[1].substr(('editable-').length);
                        selectItem($('.viewable-'+$idx+' input'));
                    }
                }
            })
        }
    });

{{-- ここまで --}}
</script>
{{--JS content end--}}
    <style>
        .div-btn li:hover  {
            background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
            box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
            cursor: pointer;
            text-shadow: 0 0px #FFF;
        }
        .div-btn li {
            color: #595959;
            height: 30px;
            border-radius: 5px;
            background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
            /*font-size: 14px;*/
            font-weight: normal;
            text-shadow: 0 0px #FFF;
        }
    </style>
@stop