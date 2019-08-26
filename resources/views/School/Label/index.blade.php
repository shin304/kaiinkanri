@extends('_parts.master_layout')
@section('content')
    <div id="center_content_header" class="box_border1">
        <h2 class="float_left"><i class="fa fa-group"></i> @if (old('type', request('type')) == 1) {{$lan::get('student_main_title')}} @else {{$lan::get('parent_main_title')}} @endif</h2>
        <div class="center_content_header_right">
            <div class="top_btn">
            </div>
        </div>
        <div class="clr"></div>
    </div><!--center_content_header-->
    <div id="section_content">
        <h3 id="content_h3" class="box_border1">{{$lan::get('output_item_title')}}</h3>
        <p id="success_save_template_alert" class="info_message" style="display: none;">{{$lan::get('lb_save_template_success_title')}}</p>
        <p id="success_delete_template_alert" class="info_message" style="display: none;">{{$lan::get('lb_delete_template_success_title')}}</p>
        <div class="center_content_header_right" style="margin-right: 2%;">
            <div class="top_btn">
                <button type="button" id="lb_select_template" class="submit2">{{$lan::get('lb_select_template_title')}}</button>
                @if($edit_auth)
                <button type="button" id="lb_save_template" class="submit2">{{$lan::get('lb_save_template_title')}}</button>
                @endif
            </div>
        </div>
        <div class="clr"></div>
        <br>
        <table style="width:100%;">
            <colgroup>
                <col style="width: 45%">
                <col style="width: 10%">
                <col style="width: 45%">
            </colgroup>
            <tr>
                <!-- FULL COLUMN TABLE  -->
                <td>
                    <ul id="reference-column" class="ul-column-title">
                        @foreach($column_titles as $idx=>$column)
                            <li idx="{{$idx}}" class="column-row ">{{$lan::get('lb_'.$column.'_title')}}{{--<label class="lb-holder">（{{$column}}）</label>--}}</li>
                        @endforeach
                    </ul>
                </td>
                <td style="vertical-align: middle">
                    <div><button type="button" class="btn-move-col" id="move_to_filter"><i style="font-size:12px;" class="fa fa-angle-double-right"></i></button><br>
                    <button type="button" class="btn-move-col" id="revert_to_refer"><i style="font-size:12px;" class="fa  fa-angle-double-left"></i></button></div>
                </td>
                <!-- FILTER COLUMN TABLE  -->
                <td>
                    <ul id="filter-column" class="ul-column-title" style="min-height: 200px;">
                        @foreach($default_column_titles as $column)
                            <li class="filter-row column-{{$column}}" >{{$lan::get('lb_'.$column.'_title')}}
                                {{--<label class="lb-holder">（{{$column}}）</label>--}}
                                <a href="" title="Delete " class="delete-column ui-icon ui-icon-close" style="float: right;"></a></li>
                        @endforeach
                    </ul>
                </td>
            </tr>


        </table>
        <form class="action-form" action="{{$_app_path}}label/exportcsv">
            {{ csrf_field() }}
            <input type="hidden" name="type" value="{{old( 'type', request('type'))}}">
            <br><hr>

            <div id="member-area">
                <!-- FORMAT ENCODE EXPORT CSV -->
                <div style="font-weight: 700">
                    <input type="checkbox" value="1" name="export_header" id="export_header" @if($template && $template->export_header) checked @endif> <label for="export_header" >{{$lan::get('confirm_header_export_title')}}</label>

                    <label style="margin-left: 50px;">{{$lan::get('item_export_title')}}</label>
                    <input type="radio" name="export_encode" value="1" id="export_encode_sjis" checked> <label for="export_encode_sjis" >SHIFT-JIS(Excel)</label>
                    <input type="radio" name="export_encode" value="2" id="export_encode_utf8" @if($template && $template->encode == 2) checked @endif> <label for="export_encode_utf8" >UTF-8</label>
                </div><!-- END FORMAT ENCODE EXPORT CSV -->
                <!-- SEARCH AREA -->
                <div id="search_member_area">

                @include('_parts.parent.student_search')
                </div>
                <div class="top_btn">
                    <table>
                        <tr>
                            <td>
                                <button type="button" style="color: #595959; width: 130px; height: 29px; border-radius: 5px; " id="submit_2"><i class="fa fa-download"></i>{{$lan::get('export_btn_title')}}</button>
                            </td>
                            <td>
                                <a class="text_link" href="javascript: window.history.go(-1)"><li style="color: #595959; font-weight: normal; width: 110px; margin-top: 1px !important;" align="center"><i class="fa fa-arrow-circle-left" style="width: 20%;font-size:16px;"></i>戻る</li></a>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </form>
        <!--テンプレート保存 -->
<div id="dialog_save_lb_template" style="display: none;">
    <table  id="template_save_tbl" style="width: 100%;">
        <tr>
            <td colspan="2"><p class="error_message name_required"  style="display: none;">{{$lan::get('lb_name_required_title')}}</p>
                            <p class="error_message name_existed"  style="display: none;">{{$lan::get('lb_name_existed_title')}}</p></td>
        </tr>
        <tr>
            <td class="t6_td1" style="width: 100px;">{{$lan::get('lb_name_title')}}<span class="aster">&lowast;</span></td>
            <td colspan="3"><input id="template_name" class="text_l form-group" type="text" name="template_name" placeholder="{{ $lan::get('lb_name_required_title') }}"/></td>
        </tr>
    </table>
    
</div>
        <!-- テンプレート選択 -->
<div id="dialog_select_lb_template" class="display_none" aria-hidden="true">
    <div class="box_border1 padding1">
        <table id="template_select_tbl" class="table1">
            <tr>
                <th style="width: 150px;">{{$lan::get('lb_name_title')}}</th>
                <th>{{$lan::get('output_item_title')}}</th>
            </tr>
            <tbody id="content_template">

            </tbody>
        </table>
    </div>
    <br />
</div>
<style rel="stylesheet">
.filter_view {
    background: #ff8c00 !important;
    color: #fff;
}
.filter_view label{
    color: inherit;
}
#template_select_tbl tbody#content_template {
    cursor: pointer;
}
.selected {
    background-color: #ff8c00 !important;
    color: #FFF;
}

#template_select_tbl tbody#content_template tr:hover {
    background-color: #6A90A4;
    color: #fff;
}
.input {
    position: relative;
}
.input span {
    position: absolute;
    display: block;
    left: 10px;
    top: 8px;
    font-size: 20px;
}
.input input {
    padding: 10px 5px 10px 40px;
    display: block;
    border: 1px solid #EDEDED;
    border-radius: 4px;
    transition: 0.2s ease-out;
    color: #a1a1a1;
}
.top_btn li:hover, .submit2:hover, #submit_2:hover,input[type="button"]:hover {
    background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
    box-shadow: 0 1px 3px rgba(204, 204, 204, 0.82);
    cursor: pointer;
    text-shadow: 0 0px #FFF;
}
.top_btn li {
    border-radius: 5px;
    background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
    text-shadow: 0 0px #FFF;
}
.submit2, #submit_2 {
    height: 30px;
    border-radius: 5px;
    background: -webkit-linear-gradient(top,  #f4f5f5 0%,#dfdddd 47%,#eaeaea 100%);
    text-shadow: 0 0px #FFF;
    font-weight: normal !important;
}
input[type="button"], button {
    border-radius: 3px;
    text-shadow: 0 0px #FFF;
}
</style>

<script type="text/javascript">
    var list = {!! json_encode($list) !!};
    var column_titles = {!! json_encode($column_titles) !!};

    $(function () {
    //     $("#btn_return").click(function() {
    //     window.location.href = '{{$_app_path}}bulletinboard/';
    //     return false;
    // });
    //====================================
    //=========DRAG & DROP AREA=========//
    //====================================
//      copy index of column (to sort table member)
        $( "#reference-column li" ).each(function () {
            $column = column_titles[$(this).attr('idx')];
            if ($( "#filter-column" ).has('li.column-'+$column).length) {
                $( "#filter-column li.column-"+$column ).attr('idx', $(this).attr('idx'));
            }
        });


        $( "#filter-column" ).sortable({
            revert: true,
//            change: function( e, ui ) {
//                reloadTableMember(e, ui);
//            },
            start: function(e, ui){
                $(ui.item).data('old-idx' , ui.item.index());
            },
            update: function(e, ui) {
                reloadTableMember(ui);
                destroyDraggable();
            }
        });

        var $myDraggable =$( "#reference-column li" ).draggable({
            start:  function(event, ui){
                ui.helper.css("width", $(this).width());  // or set a value if constant
            },
            connectToSortable: "#filter-column",
            helper: "clone",
            revert: "invalid",
        });
        
//      click X icon to delete item
        $(document).on('click', "#filter-column li",function (e) {
            e.preventDefault();
            $target = $( e.target );
            if ( $target.is( "a.delete-column" ) ) {
                deleteColumnTableMember($(this).attr('idx'));
                $(this).remove();
            }
        });

        $( "ul, li" ).disableSelection();

//      drop column from filter list to reference list
//        function dropColumn($item) {
//            var idx = $item.attr("id")-1;
//            $item.fadeOut(function() {
//
//                $item.find( "a.ui-icon-close" ).remove();
//
//                if ($('#reference-column li#'+idx).length) {
//                    $item.addClass('column-row').removeClass('ui-sortable-handle').insertAfter( $('#reference-column li#'+idx) ).fadeIn(function() {
//                        $item.animate();
////                    .find( "img" )
////                    .animate({ height: "36px" });
//                    });
//                } else { // Append to top list
//                    $item.addClass('column-row').removeClass('ui-sortable-handle').prependTo( $('#reference-column') ).fadeIn(function() {
//                        $item.animate();
//                    });
//                }
//
//
//            });
//        }
    //====================================
    //============FORM EVENT=============//
    //====================================
    $("button#submit_2").click(function () {
        $('.message_area').hide();

        if ($('input.select_rec:checked').length == 0 || $('#filter-column li').length == 0) {
            $('.message_area').show();
            return;
        }

        $('.action-form').submit();
        return true;
    });
    deleteNullTD();
    $(document).on('click', '#select_all', function () {
        if ($(this).is(':checked')){
            $('.select_rec').prop('checked', true);
        } else {
            $('.select_rec').attr('checked', false);
        }
    });

    $('.select_rec').click(function () {
        if (!$(this).is(':checked')){
            $('#select_all').attr('checked', false);
        }
    });

//    button 「＞＞」「＜＜」 click
    $(document).on('click', "#reference-column li, #filter-column li",function (e) {
        e.preventDefault();

        $(this).hasClass('filter_view')? $(this).removeClass('filter_view') : $(this).addClass('filter_view');
    });

    $('#move_to_filter').click(function () {
        $myDraggable.each(function (idx, el) {
            var new_column_title = column_titles[$(el).attr('idx')],
                pre_column = $('#filter-column li').last().attr('idx'),
                pre_column_title = column_titles[pre_column];

//            todo check selected class and move into filter_list if doesn't exist yet
            if ($(el).hasClass('filter_view') && (!$('#filter-column li.column-' +new_column_title).length)) {
                $(el).removeClass('filter_view');

                $('#filter-column').append($(el).clone().append( '<a href="" title="Delete " class="delete-column ui-icon ui-icon-close" style="float: right;"></a>' ));

                swapPositionTD(new_column_title, pre_column_title);
            } else if ($(el).hasClass('filter_view')) {
                $(el).removeClass('filter_view');
            }
        })
    });
    $('#revert_to_refer').click(function () {
        $('#filter-column li.filter_view').each(function (idx, el) {
            deleteColumnTableMember($(el).attr('idx'));
            $(el).remove();
        })
    });
    // End button 「＞＞」「＜＜」 click

    var template_arr =load_all_template();
        $( "#dialog_save_lb_template" ).dialog({
            title: '{{$lan::get('lb_save_template_title')}}',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            width: 550,
            height: 250,
            buttons: {
                "OK": function() {
                    $('#template_save_tbl tr:eq(0) td p').hide();

                    // validate name input
                    if ($('#template_name').val() == "") {
                        $('#template_save_tbl tr:eq(0) td p.name_required').show();
                        return;
                    // validate name existed
                    } else {
                        for (x in template_arr) {
                            if (template_arr[x]['name'] == $('#template_name').val().trim()) {
                                $('#template_save_tbl tr:eq(0) td p.name_existed').show();
                                return;
                            }
                        }
                    }


                    var data = $(".action-form").serialize();
                    data += '&name=' + $('#template_name').val();

                    // save template
                    $.ajax({
                        type:"get",
                        url: '/school/label/store',
                        data: data,
                        success: function(data) {
                            $("#success_save_template_alert").fadeTo(2000, 500).slideUp(500, function(){
                                $("#success_save_template_alert").slideUp(500);
                            });
                            load_all_template();
                        }
                    });

                    $( this ).dialog( "close" );

                },
                "{{$lan::get('cancel_title')}}": function() {
                    $( this ).dialog( "close" );
                }
            }
        });

        $( "#dialog_select_lb_template" ).dialog({
            title: '{{$lan::get('lb_select_template_title')}}',
            autoOpen: false,
            dialogClass: "no-close",
            resizable: false,
            modal: true,
            width: 750,
            height: 450,
            buttons: {
                "OK": function() {
                    // reload label page
                    if ($('tbody#content_template tr.selected').length == 1) {
                        var template_id = $('tbody#content_template tr.selected td').attr('id');
                        java_post('{{$_app_path}}label/index?template_id='+template_id);
                    }
                    $( this ).dialog( "close" );
                },
                "{{$lan::get('delete_title')}}": function() {
                    if ($('tbody#content_template tr.selected').length == 1) {
                        $.ajax({
                            type:"get",
                            url: '/school/label/destroy',
                            data: {template_id : $('tbody#content_template tr.selected td').attr('id')},
                            success: function(data) {
                                $("#success_delete_template_alert").fadeTo(2000, 500).slideUp(500, function(){
                                    $("#success_delete_template_alert").slideUp(500);
                                });
                                load_all_template();
                            }
                        });
                    }
                    $( this ).dialog( "close" );
                },
                "{{$lan::get('cancel_title')}}": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        // テンプレート保存
        $('#lb_save_template').click(function () {

            $('#template_name').val('');
            $('#template_save_tbl tr:eq(0) td p').hide();

            //load all template
            load_all_template();
            $( "#dialog_save_lb_template" ).dialog('open');
        });

        // テンプレート選択
        $('#lb_select_template').click(function () {
            //load all template
            load_all_template();
            $('#template_select_tbl tbody#content_template').html('');
            for (x in template_arr) {
                var html = '<tr><td id="'+template_arr[x]['id']+'">'+template_arr[x]['name']+'</td>';
                if (template_arr[x]['columns_title']) {
                    html += '<td>'+template_arr[x]['columns_title']+'</td></tr>';
                } else {
                    html += '<td></td></tr>';
                }

                $('#template_select_tbl tbody#content_template').append(html);
            }
            $( "#dialog_select_lb_template" ).dialog('open');

            // create select tr event
            $('tbody#content_template tr').click(function () {
                $('tbody#content_template tr').removeClass('selected');
                $(this).addClass('selected');
            })
        });

        // Load all template
        function load_all_template() {
            $.ajax({
                type:"get",
                url: '/school/label/loadTemplate',
                data: {type : $('[name=type]').val()},
                dataType: 'json',
                success: function(data) {
                    template_arr = data;
                }
            });
        }
});


    /**
     * Reload position column when column name was sorted
     * @param ui
     */
    function reloadTableMember(ui) {
//        var old_index = $(ui.item).data('old-idx');
        var new_index = (ui.item.index());
        var new_column = ui.item.parent().find( 'li').get(new_index),
            new_column_title = column_titles[new_column.getAttribute("idx")];

//        todo delete if exist
        if ($('#filter-column li[idx=' + new_column.getAttribute("idx")+']').length > 1) {

            $(ui.item).remove();
            $('#filter-column li').css({width:'', height:''});
            $('.column-row').removeClass('filter_view');
            return;
        }
//        todo swap position in table
        if (new_index-1 >= 0) {
            var pre_column = ui.item.parent().find( 'li').get(new_index-1),
                pre_column_title = column_titles[pre_column.getAttribute("idx")];
            swapPositionTD(new_column_title, pre_column_title);

        } else {
            swapPositionTD(new_column_title);
        }
//        todo append delete icon
        ui.item.parent().find( 'li').each( function( idx, el ) {
            var $this = $( el );
            if (!$this.has('a.delete-column').length) {
//                append delete icon
                $this.append( '<a href="" title="Delete " class="delete-column ui-icon ui-icon-close" style="float: right;"></a>' );
            }
        });

//      todo clear class filter_view
        $('#filter-column li').css({width:'', height:''});
        $(ui.item).removeClass('filter_view');
        $('.column-row').removeClass('filter_view');

        //Delete all null td
        deleteNullTD();
    }

    /**
     * delete column
     * @param idx
     */
    function deleteColumnTableMember(idx) {
        if (column_titles.hasOwnProperty(idx)) {
            $('td.td-'+column_titles[idx]).remove();
        }
    }

    function deleteNullTD() {
        $('#list-memeber-label td').each(function(){
            if($(this).text().trim() == 'null') {
                var html = $(this).html().trim().replace(null, ''); // replace(null, '') : td have text = null, but html have input hidden
                $(this).html(html);
            }

        })
    }
//    function reloadTableMember(e, ui) {
//        var seq
//            , startPos = ui.item.data( 'start-pos' )
//            , $index
//            , correction
//            , startIdx
//        ;
//
//        // if startPos < placeholder pos, we go from top to bottom
//        // else startPos > placeholder pos, we go from bottom to top and we need to correct the index with +1
//        //
//        correction = startPos <= ui.placeholder.index() ? 0 : 1;
//        startIdx = ui.item.attr('idx'); //console.log("start:"+ui.placeholder.index());
//        ui.item.parent().find( 'li').each( function( idx, el )
//        {
//            var $this = $( el )
//                , $index = $this.index()
//                , endIdx = $this.attr('idx')
//            ;
////            console.log(endIdx);
//            if (!$this.has('a.delete-column').length) {
////                append delete icon
//                $this.append( '<a href="" title="Delete " class="delete-column ui-icon ui-icon-close" style="float: right;"></a>' );
////                add column value into table
//                $column = column_titles[$this.attr('idx')];
//
//            }
//            // correction 0 means moving top to bottom, correction 1 means bottom to top
//            //
////            console.log("end:"+$index);
//
//            if ( ( $index+1 >= startPos && correction === 0) || ($index+1 <= startPos && correction === 1 ) )
//            {
//            }
//            swapPositionTD(startIdx, endIdx);
//        });
//
//    }

    function swapPositionTD(new_col, pre_col) {
        var data_arr = list.map(function(value,index) { return value[new_col]; });

        var new_td;

        for (i in data_arr) {
//                existed in filter list => move
            if ($(".td-" + new_col + i).length)  {

                new_td = ".td-" + new_col + i;
//                not existed in filter list => create
            } else {
                new_td = '<td class="td-' + new_col + i + ' td-' + new_col +'">' + data_arr[i]
                if(i ==0) {
                    new_td += '<input type="hidden" name="columns[]" value="'+new_col+'">';
                }
                new_td += '</td>';
            }

            (pre_col)? $(new_td).insertAfter( ".td-" + pre_col + i) :$(new_td).insertAfter( ".td-checkbox"+ i);
        }
    }

    function destroyDraggable() {
        $( "#filter-column li" ).each(function () {
        })
    }

</script>
    </div>

@stop
